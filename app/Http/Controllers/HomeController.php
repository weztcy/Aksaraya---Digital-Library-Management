<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use App\Models\Loan;
use App\Models\User;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $stats = [
            'total_books' => Book::count(),
            'total_copies' => Book::sum('stock_total'),
            'total_members' => User::where('role', 'anggota')->count(),
            'total_loans' => Loan::count(),
        ];

        $featuredBooks = Book::with('category')
            ->where('is_featured', true)
            ->latest()
            ->take(5)
            ->get();

        $latestBooks = Book::with('category')
            ->latest()
            ->take(8)
            ->get();

        $ebooks = Book::with('category')
            ->where('has_ebook', true)
            ->latest()
            ->take(6)
            ->get();

        $categories = Category::withCount('books')
            ->orderBy('books_count', 'desc')
            ->take(6)
            ->get();

        return view('home', compact('stats', 'featuredBooks', 'latestBooks', 'ebooks', 'categories'));
    }

    public function about(): View
    {
        return view('about');
    }
}

