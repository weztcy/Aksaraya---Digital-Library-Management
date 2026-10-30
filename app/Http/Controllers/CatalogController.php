<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Bookmark;
use App\Models\Category;
use App\Models\ReadingHistory;
use App\Models\Review;
use App\Models\Setting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CatalogController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->input('q');
        $categorySlug = $request->input('kategori');
        $type = $request->input('tipe'); // ebook, fisik
        $availability = $request->input('ketersediaan'); // tersedia
        $sortBy = $request->input('urutan', 'terbaru');

        $query = Book::with('category')->withCount('reviews');

        if ($search) {
            $query->search($search);
        }

        if ($categorySlug) {
            $query->whereHas('category', function ($q) use ($categorySlug) {
                $q->where('slug', $categorySlug);
            });
        }

        if ($type === 'ebook') {
            $query->where('has_ebook', true);
        } elseif ($type === 'fisik') {
            $query->where('stock_total', '>', 0);
        }

        if ($availability === 'tersedia') {
            $query->where('stock_available', '>', 0);
        }

        switch ($sortBy) {
            case 'populer':
                $query->orderBy('views_count', 'desc');
                break;
            case 'judul_asc':
                $query->orderBy('title', 'asc');
                break;
            case 'tahun_desc':
                $query->orderBy('publication_year', 'desc');
                break;
            case 'terbaru':
            default:
                $query->latest();
                break;
        }

        $books = $query->paginate(12)->withQueryString();
        $categories = Category::withCount('books')->get();
        $currentCategory = $categorySlug ? Category::where('slug', $categorySlug)->first() : null;

        return view('catalog.index', compact('books', 'categories', 'currentCategory', 'search', 'categorySlug', 'type', 'availability', 'sortBy'));
    }

    public function show(string $slug): View
    {
        $book = Book::with(['category', 'reviews.user'])
            ->where('slug', $slug)
            ->firstOrFail();

        // Increment views safely
        $book->increment('views_count');

        $relatedBooks = Book::with('category')
            ->where('category_id', $book->category_id)
            ->where('id', '!=', $book->id)
            ->take(4)
            ->get();

        $user = Auth::user();
        $isBookmarked = $book->isBookmarkedBy($user);

        $hasActiveLoan = false;
        $userReview = null;
        if ($user) {
            $hasActiveLoan = $user->loans()
                ->where('book_id', $book->id)
                ->whereIn('status', ['pending', 'approved', 'borrowed', 'overdue'])
                ->exists();

            $userReview = $book->reviews()->where('user_id', $user->id)->first();
        }

        $loanPeriodDays = (int) Setting::get('loan_period_days', 7);
        $dailyFineRate = (int) Setting::get('daily_fine_rate', 1000);

        return view('catalog.show', compact('book', 'relatedBooks', 'isBookmarked', 'hasActiveLoan', 'userReview', 'loanPeriodDays', 'dailyFineRate'));
    }

    public function readEbook(string $slug): View|RedirectResponse
    {
        $book = Book::where('slug', $slug)->firstOrFail();

        if (! $book->has_ebook) {
            return redirect()->route('catalog.show', $book->slug)->with('error', 'Buku ini belum memiliki versi e-book digital.');
        }

        $history = null;
        if (Auth::check()) {
            $history = ReadingHistory::firstOrCreate(
                ['user_id' => Auth::id(), 'book_id' => $book->id],
                ['last_page' => 1, 'total_pages' => 50, 'last_read_at' => now()]
            );
            $history->update(['last_read_at' => now()]);
        }

        return view('catalog.reader', compact('book', 'history'));
    }

    public function updateReadingProgress(Request $request, int $id): JsonResponse
    {
        if (! Auth::check()) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $request->validate([
            'last_page' => ['required', 'integer', 'min:1'],
            'total_pages' => ['nullable', 'integer', 'min:1'],
            'is_completed' => ['nullable', 'boolean'],
        ]);

        $history = ReadingHistory::updateOrCreate(
            ['user_id' => Auth::id(), 'book_id' => $id],
            [
                'last_page' => $request->integer('last_page'),
                'total_pages' => $request->integer('total_pages', 50),
                'is_completed' => $request->boolean('is_completed', false),
                'last_read_at' => now(),
            ]
        );

        return response()->json([
            'success' => true,
            'last_page' => $history->last_page,
            'is_completed' => $history->is_completed,
        ]);
    }

    public function toggleBookmark(Request $request, int $id): JsonResponse|RedirectResponse
    {
        if (! Auth::check()) {
            if ($request->wantsJson()) {
                return response()->json(['error' => 'Silakan login terlebih dahulu.'], 401);
            }
            return redirect()->route('login')->with('error', 'Silakan login untuk menyimpan buku favorit.');
        }

        $book = Book::findOrFail($id);
        $user = Auth::user();

        $bookmark = Bookmark::where('user_id', $user->id)->where('book_id', $book->id)->first();

        if ($bookmark) {
            $bookmark->delete();
            $bookmarked = false;
            $message = 'Buku dihapus dari daftar favorit Anda.';
        } else {
            Bookmark::create([
                'user_id' => $user->id,
                'book_id' => $book->id,
            ]);
            $bookmarked = true;
            $message = 'Buku berhasil disimpan ke daftar favorit!';
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'bookmarked' => $bookmarked,
                'message' => $message,
            ]);
        }

        return back()->with('success', $message);
    }

    public function storeReview(Request $request, int $id): RedirectResponse
    {
        $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['required', 'string', 'min:5', 'max:1000'],
        ], [
            'rating.required' => 'Pilih nilai rating bintang 1-5.',
            'comment.required' => 'Tulis ulasan Anda minimal 5 karakter.',
        ]);

        $book = Book::findOrFail($id);
        $user = Auth::user();

        Review::updateOrCreate(
            ['user_id' => $user->id, 'book_id' => $book->id],
            [
                'rating' => $request->integer('rating'),
                'comment' => $request->string('comment'),
            ]
        );

        return back()->with('success', 'Terima kasih! Ulasan dan rating Anda berhasil disimpan.');
    }
}

