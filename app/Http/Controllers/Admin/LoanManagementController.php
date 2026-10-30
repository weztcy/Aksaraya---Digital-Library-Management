<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Loan;
use App\Models\Setting;
use App\Models\User;
use App\Services\CirculationNotificationService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class LoanManagementController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->input('status');
        $search = $request->input('q');

        $query = Loan::with(['user', 'book.category', 'officer'])->latest();

        if ($status) {
            if ($status === 'overdue') {
                $query->where(function ($q) {
                    $q->where('status', 'overdue')
                        ->orWhere(function ($sub) {
                            $sub->where('status', 'borrowed')
                                ->where('due_date', '<', Carbon::today());
                        });
                });
            } else {
                $query->where('status', $status);
            }
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('loan_code', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($uq) use ($search) {
                        $uq->where('name', 'like', "%{$search}%")
                            ->orWhere('member_number', 'like', "%{$search}%");
                    })
                    ->orWhereHas('book', function ($bq) use ($search) {
                        $bq->where('title', 'like', "%{$search}%")
                            ->orWhere('isbn', 'like', "%{$search}%");
                    });
            });
        }

        $loans = $query->paginate(15)->withQueryString();

        $counts = [
            'all' => Loan::count(),
            'pending' => Loan::where('status', 'pending')->count(),
            'approved' => Loan::where('status', 'approved')->count(),
            'borrowed' => Loan::where('status', 'borrowed')->count(),
            'overdue' => Loan::where('status', 'overdue')
                ->orWhere(function ($q) {
                    $q->where('status', 'borrowed')->where('due_date', '<', Carbon::today());
                })->count(),
            'returned' => Loan::where('status', 'returned')->count(),
        ];

        return view('admin.loans.index', compact('loans', 'status', 'search', 'counts'));
    }

    public function create(): View
    {
        $members = User::where('role', 'anggota')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $books = Book::where('stock_available', '>', 0)
            ->orderBy('title')
            ->get();

        $loanDays = (int) Setting::get('loan_period_days', 7);

        return view('admin.loans.create', compact('members', 'books', 'loanDays'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'book_id' => ['required', 'exists:books,id'],
            'loan_date' => ['required', 'date'],
            'due_date' => ['required', 'date', 'after_or_equal:loan_date'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        $user = User::findOrFail($request->user_id);
        $book = Book::findOrFail($request->book_id);

        if ($book->stock_available <= 0) {
            return back()->with('error', 'Stok buku tidak tersedia.');
        }

        $maxLimit = (int) Setting::get('max_borrow_limit', 3);
        if ($user->activeLoansCount() >= $maxLimit) {
            return back()->with('error', "Anggota ini telah mencapai batas peminjaman maksimal ({$maxLimit} buku).");
        }

        DB::transaction(function () use ($request, $user, $book) {
            $dateStr = date('Ymd');
            $randomSeq = str_pad((string) (Loan::whereDate('created_at', Carbon::today())->count() + 1), 4, '0', STR_PAD_LEFT);
            $loanCode = "PINJAM-{$dateStr}-{$randomSeq}";

            Loan::create([
                'loan_code' => $loanCode,
                'user_id' => $user->id,
                'book_id' => $book->id,
                'loan_date' => $request->loan_date,
                'due_date' => $request->due_date,
                'status' => 'borrowed',
                'notes' => $request->notes,
                'officer_id' => Auth::id(),
            ]);

            $book->decrement('stock_available');
        });

        return redirect()->route('admin.loans.index')
            ->with('success', 'Transaksi peminjaman langsung berhasil dicatat dan buku telah diserahkan.');
    }

    public function approve(int $id): RedirectResponse
    {
        $loan = Loan::findOrFail($id);

        if ($loan->status !== 'pending') {
            return back()->with('error', 'Peminjaman tidak dalam status menunggu konfirmasi.');
        }

        $loan->update([
            'status' => 'approved',
            'officer_id' => Auth::id(),
        ]);

        // Trigger WhatsApp/Email notification
        CirculationNotificationService::sendWhatsApp($loan, 'loan_approved');
        CirculationNotificationService::sendEmail($loan, 'loan_approved');

        return back()->with('success', "Peminjaman [{$loan->loan_code}] telah disetujui & notifikasi otomatis terkirim ke anggota.");
    }

    public function borrow(int $id): RedirectResponse
    {
        $loan = Loan::with('book')->findOrFail($id);

        if (! in_array($loan->status, ['pending', 'approved'], true)) {
            return back()->with('error', 'Status peminjaman tidak valid untuk diserahkan.');
        }

        if ($loan->book->stock_available <= 0) {
            return back()->with('error', 'Stok fisik buku ini tidak mencukupi.');
        }

        DB::transaction(function () use ($loan) {
            $loan->update([
                'status' => 'borrowed',
                'officer_id' => Auth::id(),
            ]);

            $loan->book->decrement('stock_available');
        });

        return back()->with('success', "Buku telah diserahkan kepada peminjam. Status diubah menjadi Sedang Dipinjam.");
    }

    public function returnBook(Request $request, int $id): RedirectResponse
    {
        $loan = Loan::with('book')->findOrFail($id);

        if (! in_array($loan->status, ['borrowed', 'overdue', 'approved'], true)) {
            return back()->with('error', 'Buku ini sudah dikembalikan atau tidak dalam status pinjam aktif.');
        }

        $returnDate = Carbon::today();
        $dailyFineRate = (int) Setting::get('daily_fine_rate', 1000);

        $fineAmount = 0;
        if ($loan->due_date && $returnDate->gt($loan->due_date)) {
            $daysLate = (int) $loan->due_date->diffInDays($returnDate);
            $fineAmount = $daysLate * $dailyFineRate;
        }

        $finePaid = $request->boolean('fine_paid') || $fineAmount === 0;

        DB::transaction(function () use ($loan, $returnDate, $fineAmount, $finePaid, $request) {
            $loan->update([
                'status' => 'returned',
                'return_date' => $returnDate,
                'fine_amount' => $fineAmount,
                'fine_paid' => $finePaid,
                'notes' => $request->input('notes') ?? $loan->notes,
                'officer_id' => Auth::id(),
            ]);

            // If loan was borrowed or approved, restore available stock
            if (in_array($loan->getOriginal('status'), ['borrowed', 'overdue'], true)) {
                $loan->book->increment('stock_available');
            }
        });

        $msg = "Buku telah berhasil dikembalikan!";
        if ($fineAmount > 0) {
            $statusFine = $finePaid ? 'Lunas' : 'Belum Lunas';
            $msg .= " Terlambat dengan denda: Rp " . number_format($fineAmount, 0, ',', '.') . " ({$statusFine}).";
        }

        return back()->with('success', $msg);
    }

    public function payFine(int $id): RedirectResponse
    {
        $loan = Loan::findOrFail($id);
        $loan->update(['fine_paid' => true]);

        return back()->with('success', "Denda untuk transaksi [{$loan->loan_code}] berhasil ditandai Lunas.");
    }

    public function reject(Request $request, int $id): RedirectResponse
    {
        $loan = Loan::findOrFail($id);

        if ($loan->status !== 'pending') {
            return back()->with('error', 'Hanya permohonan yang masih pending yang dapat ditolak.');
        }

        $loan->update([
            'status' => 'rejected',
            'notes' => $request->input('reason', 'Permohonan pinjam ditolak oleh petugas perpustakaan.'),
            'officer_id' => Auth::id(),
        ]);

        return back()->with('success', "Permohonan pinjaman [{$loan->loan_code}] telah ditolak.");
    }

    public function sendReminder(Request $request, int $id): RedirectResponse
    {
        $loan = Loan::with(['user', 'book'])->findOrFail($id);
        $channel = $request->input('channel', 'whatsapp');
        $type = $loan->due_date && Carbon::today()->gt($loan->due_date) ? 'overdue_alert' : 'due_reminder';

        if ($channel === 'whatsapp') {
            CirculationNotificationService::sendWhatsApp($loan, $type);
            $target = $loan->user->phone ?? 'WhatsApp Anggota';
        } else {
            CirculationNotificationService::sendEmail($loan, $type);
            $target = $loan->user->email;
        }

        return back()->with('success', "Pesan pengingat otomatis ({$channel}) berhasil dikirim ke {$target}!");
    }

    public function lookupBarcode(Request $request): JsonResponse
    {
        $code = trim($request->input('code', ''));
        if (!$code) {
            return response()->json(['found' => false, 'message' => 'Kode kosong'], 400);
        }

        // Check if it's a book ISBN or barcode
        $book = Book::where('isbn', $code)->orWhere('title', 'like', "%{$code}%")->first();
        if ($book) {
            return response()->json([
                'found' => true,
                'type' => 'book',
                'data' => [
                    'id' => $book->id,
                    'title' => $book->title,
                    'author' => $book->author,
                    'isbn' => $book->isbn,
                    'stock_available' => $book->stock_available,
                    'shelf_location' => $book->shelf_location,
                ]
            ]);
        }

        // Check if it's a member barcode / member_number
        $user = User::where('member_number', $code)->orWhere('email', $code)->first();
        if ($user) {
            return response()->json([
                'found' => true,
                'type' => 'member',
                'data' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'member_number' => $user->member_number,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'active_loans' => $user->activeLoansCount(),
                ]
            ]);
        }

        // Check if it's a loan code
        $loan = Loan::with(['user', 'book'])->where('loan_code', $code)->first();
        if ($loan) {
            return response()->json([
                'found' => true,
                'type' => 'loan',
                'data' => [
                    'id' => $loan->id,
                    'loan_code' => $loan->loan_code,
                    'user_name' => $loan->user->name,
                    'book_title' => $loan->book->title,
                    'status' => $loan->status,
                    'due_date' => $loan->due_date ? $loan->due_date->format('d/m/Y') : '-',
                    'fine_amount' => $loan->fine_amount,
                ]
            ]);
        }

        return response()->json(['found' => false, 'message' => 'Data tidak ditemukan untuk barcode/kode ini.']);
    }
}

