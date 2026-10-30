<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Loan;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoanController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'book_id' => ['required', 'exists:books,id'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        $user = Auth::user();
        $book = Book::findOrFail($request->book_id);

        if (! $user->is_active) {
            return back()->with('error', 'Akun keanggotaan Anda tidak aktif. Silakan hubungi petugas perpustakaan.');
        }

        // Check if book has available stock
        if ($book->stock_available <= 0) {
            return back()->with('error', 'Maaf, stok fisik buku ini sedang habis dipinjam oleh anggota lain.');
        }

        // Check max borrow limit
        $maxLimit = (int) Setting::get('max_borrow_limit', 3);
        $activeLoansCount = $user->activeLoansCount();

        if ($activeLoansCount >= $maxLimit) {
            return back()->with('error', "Anda telah mencapai batas maksimal peminjaman aktif ({$maxLimit} buku). Harap kembalikan buku terlebih dahulu.");
        }

        // Check if already borrowed this book
        $alreadyBorrowed = $user->loans()
            ->where('book_id', $book->id)
            ->whereIn('status', ['pending', 'approved', 'borrowed', 'overdue'])
            ->exists();

        if ($alreadyBorrowed) {
            return back()->with('error', 'Anda sudah memiliki permohonan atau sedang meminjam buku ini.');
        }

        $loanDays = (int) Setting::get('loan_period_days', 7);
        $loanDate = Carbon::today();
        $dueDate = Carbon::today()->addDays($loanDays);

        // Generate unique loan code
        $dateStr = date('Ymd');
        $randomSeq = str_pad((string) (Loan::whereDate('created_at', Carbon::today())->count() + 1), 4, '0', STR_PAD_LEFT);
        $loanCode = "PINJAM-{$dateStr}-{$randomSeq}";

        Loan::create([
            'loan_code' => $loanCode,
            'user_id' => $user->id,
            'book_id' => $book->id,
            'loan_date' => $loanDate,
            'due_date' => $dueDate,
            'status' => 'pending',
            'notes' => $request->input('notes'),
        ]);

        return redirect()->route('member.loans')
            ->with('success', "Permohonan pinjam buku '{$book->title}' berhasil diajukan! Kode Pinjam: {$loanCode}. Silakan tunggu verifikasi petugas.");
    }
}

