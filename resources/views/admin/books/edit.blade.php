@extends('layouts.admin')

@section('title', 'Edit Buku: ' . $book->title)
@section('page-title', 'Edit Koleksi Buku')

@section('content')
<div class="max-w-4xl mx-auto space-y-6 mt-6">
    
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.books.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-500 hover:text-slate-900 transition-colors">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            <span>Kembali ke Daftar Buku</span>
        </a>
    </div>

    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 sm:p-8">
        <form method="POST" action="{{ route('admin.books.update', $book->id) }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <!-- Judul Buku -->
                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Judul Buku *</label>
                    <input type="text" name="title" value="{{ old('title', $book->title) }}" required
                        class="w-full text-xs p-3 bg-slate-50 border border-slate-300 rounded-xl focus:ring-2 focus:ring-brand-500">
                </div>

                <!-- Kategori -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Kategori Koleksi *</label>
                    <select name="category_id" required class="w-full text-xs p-3 bg-slate-50 border border-slate-300 rounded-xl focus:ring-2 focus:ring-brand-500">
                        @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id', $book->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <!-- Penulis -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Penulis / Pengarang *</label>
                    <input type="text" name="author" value="{{ old('author', $book->author) }}" required
                        class="w-full text-xs p-3 bg-slate-50 border border-slate-300 rounded-xl focus:ring-2 focus:ring-brand-500">
                </div>

                <!-- Penerbit -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Penerbit *</label>
                    <input type="text" name="publisher" value="{{ old('publisher', $book->publisher) }}" required
                        class="w-full text-xs p-3 bg-slate-50 border border-slate-300 rounded-xl focus:ring-2 focus:ring-brand-500">
                </div>

                <!-- Tahun Terbit -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Tahun Terbit *</label>
                    <input type="number" name="publication_year" value="{{ old('publication_year', $book->publication_year) }}" required min="1900" max="{{ date('Y') + 1 }}"
                        class="w-full text-xs p-3 bg-slate-50 border border-slate-300 rounded-xl focus:ring-2 focus:ring-brand-500">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-4 gap-6">
                <!-- ISBN -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Nomor ISBN *</label>
                    <input type="text" name="isbn" value="{{ old('isbn', $book->isbn) }}" required
                        class="w-full text-xs p-3 bg-slate-50 border border-slate-300 rounded-xl focus:ring-2 focus:ring-brand-500 font-mono">
                </div>

                <!-- Halaman -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Jumlah Halaman</label>
                    <input type="number" name="pages" value="{{ old('pages', $book->pages) }}" min="1"
                        class="w-full text-xs p-3 bg-slate-50 border border-slate-300 rounded-xl focus:ring-2 focus:ring-brand-500">
                </div>

                <!-- Lokasi Rak -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Lokasi Rak *</label>
                    <input type="text" name="shelf_location" value="{{ old('shelf_location', $book->shelf_location) }}" required
                        class="w-full text-xs p-3 bg-slate-50 border border-slate-300 rounded-xl focus:ring-2 focus:ring-brand-500">
                </div>

                <!-- Jumlah Stok Fisik -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Total Stok Fisik *</label>
                    <input type="number" name="stock_total" value="{{ old('stock_total', $book->stock_total) }}" required min="0"
                        class="w-full text-xs p-3 bg-slate-50 border border-slate-300 rounded-xl focus:ring-2 focus:ring-brand-500">
                    <span class="text-[10px] text-slate-500">Tersedia saat ini: {{ $book->stock_available }}</span>
                </div>
            </div>

            <!-- Bahasa -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Bahasa Buku</label>
                <input type="text" name="language" value="{{ old('language', $book->language) }}" required
                    class="w-full text-xs p-3 bg-slate-50 border border-slate-300 rounded-xl focus:ring-2 focus:ring-brand-500">
            </div>

            <!-- Deskripsi / Sinopsis -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Sinopsis & Deskripsi Buku *</label>
                <textarea name="description" rows="4" required
                    class="w-full text-xs p-3 bg-slate-50 border border-slate-300 rounded-xl focus:ring-2 focus:ring-brand-500 leading-relaxed">{{ old('description', $book->description) }}</textarea>
            </div>

            <!-- Cover Image Options -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 p-4 bg-slate-50 rounded-2xl border border-slate-200">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Ganti File Cover (JPG/PNG)</label>
                    <input type="file" name="cover_file" accept="image/*" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-brand-50 file:text-brand-700 hover:file:bg-brand-100">
                    @if($book->cover_image)
                    <div class="mt-2 flex items-center gap-2">
                        <img src="{{ $book->cover_image }}" alt="" class="w-8 h-12 object-cover rounded shadow-xs">
                        <span class="text-[11px] text-slate-500">Cover saat ini aktif</span>
                    </div>
                    @endif
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Atau URL Gambar Cover Online</label>
                    <input type="url" name="cover_url" value="{{ old('cover_url', $book->cover_image) }}"
                        class="w-full text-xs p-2.5 bg-white border border-slate-300 rounded-xl focus:ring-2 focus:ring-brand-500">
                </div>
            </div>

            <!-- Digital E-Book Upload -->
            <div class="p-4 bg-emerald-50/50 rounded-2xl border border-emerald-200/80 space-y-3">
                <div class="flex items-center gap-2">
                    <input type="checkbox" name="has_ebook" id="has_ebook" value="1" {{ old('has_ebook', $book->has_ebook) ? 'checked' : '' }} class="rounded text-emerald-600">
                    <label for="has_ebook" class="text-xs font-bold text-emerald-900 cursor-pointer">Buku Memiliki Versi E-Book Digital (Bisa Dibaca Online)</label>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-emerald-800 mb-1">Ganti Dokumen PDF E-Book (Maks 20MB)</label>
                    <input type="file" name="ebook_file" accept="application/pdf" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-100 file:text-emerald-800 hover:file:bg-emerald-200">
                    @if($book->ebook_file)
                    <p class="text-[11px] text-emerald-700 mt-1">File E-Book saat ini tersimpan: {{ basename($book->ebook_file) }}</p>
                    @endif
                </div>
            </div>

            <!-- Featured Checkbox -->
            <div class="flex items-center gap-2">
                <input type="checkbox" name="is_featured" id="is_featured" value="1" {{ old('is_featured', $book->is_featured) ? 'checked' : '' }} class="rounded text-brand-600">
                <label for="is_featured" class="text-xs font-bold text-slate-700 cursor-pointer">Tandai Sebagai Buku Pilihan / Unggulan di Halaman Utama</label>
            </div>

            <!-- Submit -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="{{ route('admin.books.index') }}" class="px-5 py-2.5 text-xs font-semibold text-slate-600 hover:bg-slate-100 rounded-xl transition-colors">Batal</a>
                <button type="submit" class="px-6 py-2.5 bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs rounded-xl shadow-md transition-colors flex items-center gap-2">
                    <i data-lucide="check" class="w-4 h-4"></i>
                    <span>Simpan Perubahan Buku</span>
                </button>
            </div>
        </form>
    </div>

</div>
@endsection

