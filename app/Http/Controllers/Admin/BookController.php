<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class BookController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->input('q');
        $categoryId = $request->input('category_id');

        $query = Book::with('category')->withCount('loans');

        if ($search) {
            $query->search($search);
        }

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        $books = $query->latest()->paginate(10)->withQueryString();
        $categories = Category::orderBy('name')->get();

        return view('admin.books.index', compact('books', 'categories', 'search', 'categoryId'));
    }

    public function create(): View
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.books.create', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'title' => ['required', 'string', 'max:255'],
            'author' => ['required', 'string', 'max:255'],
            'publisher' => ['required', 'string', 'max:255'],
            'publication_year' => ['required', 'integer', 'min:1900', 'max:' . (date('Y') + 1)],
            'isbn' => ['required', 'string', 'max:50', 'unique:books,isbn'],
            'pages' => ['nullable', 'integer', 'min:1'],
            'language' => ['required', 'string', 'max:50'],
            'shelf_location' => ['required', 'string', 'max:50'],
            'stock_total' => ['required', 'integer', 'min:0'],
            'description' => ['required', 'string'],
            'cover_file' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'cover_url' => ['nullable', 'url', 'max:500'],
            'has_ebook' => ['nullable', 'boolean'],
            'ebook_file' => ['nullable', 'file', 'mimes:pdf', 'max:20480'],
            'is_featured' => ['nullable', 'boolean'],
        ]);

        $slug = Str::slug($validated['title']);
        $originalSlug = $slug;
        $counter = 1;
        while (Book::where('slug', $slug)->exists()) {
            $slug = "{$originalSlug}-{$counter}";
            $counter++;
        }

        $coverPath = null;
        if ($request->hasFile('cover_file')) {
            $coverPath = $request->file('cover_file')->store('covers', 'public');
            $coverPath = Storage::url($coverPath);
        } elseif (! empty($validated['cover_url'])) {
            $coverPath = $validated['cover_url'];
        }

        $ebookPath = null;
        $hasEbook = $request->boolean('has_ebook');
        if ($request->hasFile('ebook_file')) {
            $ebookPath = $request->file('ebook_file')->store('ebooks', 'public');
            $hasEbook = true;
        }

        Book::create([
            'category_id' => $validated['category_id'],
            'title' => $validated['title'],
            'slug' => $slug,
            'author' => $validated['author'],
            'publisher' => $validated['publisher'],
            'publication_year' => $validated['publication_year'],
            'isbn' => $validated['isbn'],
            'pages' => $validated['pages'] ?? null,
            'language' => $validated['language'],
            'shelf_location' => $validated['shelf_location'],
            'stock_total' => $validated['stock_total'],
            'stock_available' => $validated['stock_total'],
            'description' => $validated['description'],
            'cover_image' => $coverPath,
            'has_ebook' => $hasEbook,
            'ebook_file' => $ebookPath,
            'is_featured' => $request->boolean('is_featured'),
        ]);

        return redirect()->route('admin.books.index')
            ->with('success', 'Buku baru berhasil ditambahkan ke katalog!');
    }

    public function edit(Book $book): View
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.books.edit', compact('book', 'categories'));
    }

    public function update(Request $request, Book $book): RedirectResponse
    {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'title' => ['required', 'string', 'max:255'],
            'author' => ['required', 'string', 'max:255'],
            'publisher' => ['required', 'string', 'max:255'],
            'publication_year' => ['required', 'integer', 'min:1900', 'max:' . (date('Y') + 1)],
            'isbn' => ['required', 'string', 'max:50', Rule::unique('books')->ignore($book->id)],
            'pages' => ['nullable', 'integer', 'min:1'],
            'language' => ['required', 'string', 'max:50'],
            'shelf_location' => ['required', 'string', 'max:50'],
            'stock_total' => ['required', 'integer', 'min:0'],
            'description' => ['required', 'string'],
            'cover_file' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'cover_url' => ['nullable', 'url', 'max:500'],
            'has_ebook' => ['nullable', 'boolean'],
            'ebook_file' => ['nullable', 'file', 'mimes:pdf', 'max:20480'],
            'is_featured' => ['nullable', 'boolean'],
        ]);

        // Adjust stock available if total stock changed
        $stockDiff = $validated['stock_total'] - $book->stock_total;
        $newAvailable = max(0, $book->stock_available + $stockDiff);

        $bookData = [
            'category_id' => $validated['category_id'],
            'title' => $validated['title'],
            'author' => $validated['author'],
            'publisher' => $validated['publisher'],
            'publication_year' => $validated['publication_year'],
            'isbn' => $validated['isbn'],
            'pages' => $validated['pages'] ?? null,
            'language' => $validated['language'],
            'shelf_location' => $validated['shelf_location'],
            'stock_total' => $validated['stock_total'],
            'stock_available' => $newAvailable,
            'description' => $validated['description'],
            'has_ebook' => $request->boolean('has_ebook'),
            'is_featured' => $request->boolean('is_featured'),
        ];

        if ($request->hasFile('cover_file')) {
            $coverPath = $request->file('cover_file')->store('covers', 'public');
            $bookData['cover_image'] = Storage::url($coverPath);
        } elseif (! empty($validated['cover_url'])) {
            $bookData['cover_image'] = $validated['cover_url'];
        }

        if ($request->hasFile('ebook_file')) {
            $ebookPath = $request->file('ebook_file')->store('ebooks', 'public');
            $bookData['ebook_file'] = $ebookPath;
            $bookData['has_ebook'] = true;
        }

        $book->update($bookData);

        return redirect()->route('admin.books.index')
            ->with('success', 'Data buku berhasil diperbarui!');
    }

    public function destroy(Book $book): RedirectResponse
    {
        $hasActiveLoans = $book->loans()
            ->whereIn('status', ['pending', 'approved', 'borrowed', 'overdue'])
            ->exists();

        if ($hasActiveLoans) {
            return back()->with('error', 'Buku tidak dapat dihapus karena sedang ada peminjaman aktif.');
        }

        $book->delete();

        return redirect()->route('admin.books.index')
            ->with('success', 'Buku berhasil dihapus dari katalog.');
    }

    public function exportCsv(): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $books = Book::with('category')->get();
        $filename = 'katalog-buku-aksaraya-' . date('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($books) {
            $handle = fopen('php://output', 'w');
            // Write UTF-8 BOM for Excel compatibility
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            // CSV Header
            fputcsv($handle, [
                'ID', 'ISBN', 'Judul Buku', 'Pengarang', 'Penerbit', 'Tahun',
                'Kategori', 'Lokasi Rak', 'Stok Total', 'Stok Tersedia', 'Format E-Book', 'Deskripsi'
            ]);

            foreach ($books as $b) {
                fputcsv($handle, [
                    $b->id,
                    $b->isbn,
                    $b->title,
                    $b->author,
                    $b->publisher,
                    $b->publication_year,
                    $b->category ? $b->category->name : '-',
                    $b->shelf_location,
                    $b->stock_total,
                    $b->stock_available,
                    $b->has_ebook ? 'Ya' : 'Tidak',
                    $b->description,
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function importCsv(Request $request): RedirectResponse
    {
        $request->validate([
            'csv_file' => ['required', 'file', 'mimes:csv,txt'],
        ], [
            'csv_file.required' => 'Pilih file CSV untuk diimpor.',
        ]);

        $file = $request->file('csv_file');
        $handle = fopen($file->getRealPath(), 'r');
        $header = fgetcsv($handle); // skip header line

        $imported = 0;
        $defaultCat = Category::firstOrCreate(['name' => 'Umum'], ['slug' => 'umum', 'icon' => 'book', 'color' => 'blue']);

        while (($row = fgetcsv($handle, 2000, ',')) !== false) {
            if (empty($row[1]) && empty($row[2])) continue;

            $isbn = trim($row[1] ?? 'ISBN-' . rand(100000, 999999));
            $title = trim($row[2] ?? 'Buku Tanpa Judul');
            $author = trim($row[3] ?? 'Penulis Anonim');
            $publisher = trim($row[4] ?? 'Penerbit');
            $year = (int) ($row[5] ?? date('Y'));
            $catName = trim($row[6] ?? 'Umum');
            $shelf = trim($row[7] ?? 'RAK-A1');
            $stock = (int) ($row[8] ?? 5);

            $category = Category::where('name', $catName)->first() ?: $defaultCat;

            $slug = Str::slug($title);
            $baseSlug = $slug;
            $count = 1;
            while (Book::where('slug', $slug)->exists()) {
                $slug = "{$baseSlug}-{$count}";
                $count++;
            }

            Book::updateOrCreate(
                ['isbn' => $isbn],
                [
                    'category_id' => $category->id,
                    'title' => $title,
                    'slug' => $slug,
                    'author' => $author,
                    'publisher' => $publisher,
                    'publication_year' => $year,
                    'shelf_location' => $shelf,
                    'stock_total' => $stock,
                    'stock_available' => $stock,
                    'language' => 'Indonesia',
                    'description' => trim($row[11] ?? "Buku bahan pustaka {$title} oleh {$author}."),
                    'cover_image' => 'https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?auto=format&fit=crop&w=400&q=80',
                ]
            );

            $imported++;
        }

        fclose($handle);

        return back()->with('success', "Proses impor selesai! {$imported} buku berhasil disinkronkan ke katalog Aksaraya.");
    }
}

