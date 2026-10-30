<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Loan;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        $startDate = $request->input('start_date', Carbon::today()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::today()->toDateString());
        $status = $request->input('status');
        $categoryId = $request->input('category_id');

        $query = Loan::with(['user', 'book.category', 'officer'])
            ->whereBetween('loan_date', [$startDate, $endDate])
            ->latest('loan_date');

        if ($status) {
            $query->where('status', $status);
        }

        if ($categoryId) {
            $query->whereHas('book', function ($q) use ($categoryId) {
                $q->where('category_id', $categoryId);
            });
        }

        $summary = [
            'total_transactions' => (clone $query)->count(),
            'returned_count' => (clone $query)->where('status', 'returned')->count(),
            'borrowed_count' => (clone $query)->whereIn('status', ['borrowed', 'approved'])->count(),
            'total_fines' => (clone $query)->sum('fine_amount'),
            'paid_fines' => (clone $query)->where('fine_paid', true)->sum('fine_amount'),
        ];

        $categories = Category::orderBy('name')->get();

        if ($request->has('print')) {
            $loans = $query->get();
            $libraryName = Setting::get('library_name', 'Aksaraya');
            $libraryAddress = Setting::get('library_address', 'Jakarta, Indonesia');
            return view('admin.reports.print', compact('loans', 'startDate', 'endDate', 'summary', 'libraryName', 'libraryAddress'));
        }

        $loans = $query->paginate(20)->withQueryString();

        return view('admin.reports.index', compact('loans', 'categories', 'startDate', 'endDate', 'status', 'categoryId', 'summary'));
    }
}

