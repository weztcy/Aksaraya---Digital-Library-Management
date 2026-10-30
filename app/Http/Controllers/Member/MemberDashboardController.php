<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use App\Models\ReadingChallenge;
use App\Models\Setting;
use App\Models\UserShelf;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class MemberDashboardController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();

        $activeLoans = $user->loans()
            ->with('book.category')
            ->whereIn('status', ['pending', 'approved', 'borrowed', 'overdue'])
            ->latest()
            ->get();

        $recentReturned = $user->loans()
            ->with('book')
            ->where('status', 'returned')
            ->latest('return_date')
            ->take(5)
            ->get();

        $bookmarks = $user->bookmarkedBooks()
            ->with('category')
            ->latest('bookmarks.created_at')
            ->take(4)
            ->get();

        $totalFines = $user->loans()
            ->where('fine_paid', false)
            ->where('fine_amount', '>', 0)
            ->sum('fine_amount');

        $maxBorrowLimit = (int) Setting::get('max_borrow_limit', 3);

        $currentYear = (int) date('Y');
        $challenge = ReadingChallenge::firstOrCreate(
            ['user_id' => $user->id, 'year' => $currentYear],
            ['target_books' => 12]
        );

        $completedCount = $user->readingHistories()->where('is_completed', true)->count()
            + $user->loans()->where('status', 'returned')->count();

        $shelvesCounts = [
            'want_to_read' => $user->userShelves()->where('shelf_type', 'want_to_read')->count(),
            'currently_reading' => $user->userShelves()->where('shelf_type', 'currently_reading')->count(),
            'already_read' => $user->userShelves()->where('shelf_type', 'already_read')->count(),
        ];

        return view('member.dashboard', compact(
            'user', 'activeLoans', 'recentReturned', 'bookmarks', 'totalFines', 
            'maxBorrowLimit', 'challenge', 'completedCount', 'shelvesCounts'
        ));
    }

    public function loans(Request $request): View
    {
        $status = $request->input('status');
        $query = Auth::user()->loans()->with(['book.category', 'officer'])->latest();

        if ($status && in_array($status, ['pending', 'approved', 'borrowed', 'returned', 'overdue', 'rejected'], true)) {
            $query->where('status', $status);
        }

        $loans = $query->paginate(10)->withQueryString();

        return view('member.loans', compact('loans', 'status'));
    }

    public function cancelLoan(int $id): RedirectResponse
    {
        $loan = Auth::user()->loans()->where('id', $id)->firstOrFail();

        if ($loan->status !== 'pending') {
            return back()->with('error', 'Hanya pengajuan pinjaman yang berstatus Menunggu Konfirmasi yang dapat dibatalkan.');
        }

        $loan->update(['status' => 'rejected', 'notes' => 'Dibatalkan oleh peminjam.']);

        return back()->with('success', 'Pengajuan peminjaman berhasil dibatalkan.');
    }

    public function payFineSimulated(Request $request, int $id): RedirectResponse
    {
        $loan = Auth::user()->loans()->where('id', $id)->firstOrFail();

        if ($loan->fine_paid || $loan->fine_amount <= 0) {
            return back()->with('error', 'Tidak ada denda yang perlu dibayar untuk peminjaman ini.');
        }

        $paymentMethod = $request->input('payment_method', 'qris_simulasi');
        $refCode = 'PAY-' . strtoupper($paymentMethod) . '-' . date('YmdHis') . '-' . rand(100, 999);

        $loan->update([
            'fine_paid' => true,
            'payment_method' => $paymentMethod,
            'payment_reference' => $refCode,
            'fine_paid_at' => now(),
        ]);

        return back()->with('success', "Simulasi pembayaran denda sebesar Rp " . number_format($loan->fine_amount, 0, ',', '.') . " berhasil diverifikasi! No. Ref: {$refCode}");
    }

    public function shelves(Request $request): View
    {
        $user = Auth::user();
        $shelfType = $request->input('type', 'want_to_read');

        $shelves = $user->userShelves()
            ->with(['book.category'])
            ->where('shelf_type', $shelfType)
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $counts = [
            'want_to_read' => $user->userShelves()->where('shelf_type', 'want_to_read')->count(),
            'currently_reading' => $user->userShelves()->where('shelf_type', 'currently_reading')->count(),
            'already_read' => $user->userShelves()->where('shelf_type', 'already_read')->count(),
        ];

        return view('member.shelves', compact('shelves', 'shelfType', 'counts'));
    }

    public function updateShelf(Request $request, int $bookId): JsonResponse|RedirectResponse
    {
        $request->validate([
            'shelf_type' => ['required', Rule::in(['want_to_read', 'currently_reading', 'already_read', 'remove'])],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        $user = Auth::user();
        $shelfType = $request->input('shelf_type');

        if ($shelfType === 'remove') {
            UserShelf::where('user_id', $user->id)->where('book_id', $bookId)->delete();
            $msg = 'Buku dihapus dari rak pribadi Anda.';
        } else {
            UserShelf::updateOrCreate(
                ['user_id' => $user->id, 'book_id' => $bookId],
                ['shelf_type' => $shelfType, 'notes' => $request->input('notes')]
            );
            $msg = 'Buku berhasil dimasukkan ke rak: ' . UserShelf::getShelfLabel($shelfType);
        }

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => $msg]);
        }

        return back()->with('success', $msg);
    }

    public function saveChallenge(Request $request): RedirectResponse
    {
        $request->validate([
            'target_books' => ['required', 'integer', 'min:1', 'max:365'],
        ]);

        $user = Auth::user();
        $currentYear = (int) date('Y');

        ReadingChallenge::updateOrCreate(
            ['user_id' => $user->id, 'year' => $currentYear],
            ['target_books' => $request->integer('target_books')]
        );

        return back()->with('success', 'Target Tantangan Membaca Tahun ' . $currentYear . ' berhasil diperbarui!');
    }

    public function card(): View
    {
        $user = Auth::user();
        $libraryName = Setting::get('library_name', 'Aksaraya');
        $libraryAddress = Setting::get('library_address', 'Jakarta, Indonesia');

        return view('member.card', compact('user', 'libraryName', 'libraryAddress'));
    }

    public function bookmarks(): View
    {
        $user = Auth::user();
        $bookmarks = $user->bookmarkedBooks()
            ->with('category')
            ->paginate(12);

        return view('member.bookmarks', compact('bookmarks'));
    }

    public function profile(): View
    {
        $user = Auth::user();
        return view('member.profile', compact('user'));
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:500'],
            'current_password' => ['nullable', 'required_with:new_password', 'current_password'],
            'new_password' => ['nullable', 'confirmed', Password::min(6)],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.unique' => 'Email ini sudah dipakai oleh akun lain.',
            'current_password.current_password' => 'Password saat ini salah.',
            'new_password.confirmed' => 'Konfirmasi password baru tidak cocok.',
            'new_password.min' => 'Password baru minimal 6 karakter.',
        ]);

        $userData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
        ];

        if (! empty($validated['new_password'])) {
            $userData['password'] = Hash::make($validated['new_password']);
        }

        $user->update($userData);

        return back()->with('success', 'Profil Anda berhasil diperbarui!');
    }
}

