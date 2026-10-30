<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Category;
use App\Models\Loan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    public function index(): View
    {
        $totalBooks = Book::count();
        $totalCopies = Book::sum('stock_total');
        $totalAvailableCopies = Book::sum('stock_available');
        $totalEbooks = Book::where('has_ebook', true)->count();
        $totalMembers = User::where('role', 'anggota')->count();

        $pendingLoansCount = Loan::where('status', 'pending')->count();
        $activeBorrowedCount = Loan::whereIn('status', ['approved', 'borrowed'])->count();
        $overdueLoansCount = Loan::where('status', 'overdue')
            ->orWhere(function ($q) {
                $q->where('status', 'borrowed')
                    ->where('due_date', '<', Carbon::today());
            })->count();

        $paidFines = Loan::where('fine_paid', true)->sum('fine_amount');
        $unpaidFines = Loan::where('fine_paid', false)->where('fine_amount', '>', 0)->sum('fine_amount');

        // Recent loan activities
        $recentLoans = Loan::with(['user', 'book', 'officer'])
            ->latest()
            ->take(6)
            ->get();

        // Top borrowed books
        $topBooks = Book::with('category')
            ->withCount('loans')
            ->orderBy('loans_count', 'desc')
            ->take(5)
            ->get();

        // Weekly loan stats for last 7 days
        $weeklyStats = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $count = Loan::whereDate('created_at', $date)->count();
            $weeklyStats[] = [
                'date' => $date->translatedFormat('d M'),
                'count' => $count,
            ];
        }

        return view('admin.dashboard', compact(
            'totalBooks',
            'totalCopies',
            'totalAvailableCopies',
            'totalEbooks',
            'totalMembers',
            'pendingLoansCount',
            'activeBorrowedCount',
            'overdueLoansCount',
            'paidFines',
            'unpaidFines',
            'recentLoans',
            'topBooks',
            'weeklyStats'
        ));
    }
}

