@extends('layouts.admin')

@section('title', 'Tambah Buku Baru')
@section('page-title', 'Tambah Buku ke Katalog')

@section('content')
<div class="max-w-4xl mx-auto space-y-6 mt-6">
    
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.books.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-500 hover:text-slate-900 transition-colors">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            <span>Kembali ke Daftar Buku</span>
        </a>
    </div>

    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 sm:p-8">
        <form method="POST" action="{{ route('admin.books.store') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <!-- Judul Buku -->
                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Judul Buku *</label>
                    <input type="text" name="title" value="{{ old('title') }}" required placeholder="Contoh: Laut Bercerita"
                        class="w-full text-xs p-3 bg-slate-50 border border-slate-300 rounded-xl focus:ring-2 focus:ring-brand-500">
                </div>

                <!-- Kategori -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Kategori Koleksi *</label>
                    <select name="category_id" required class="w-full text-xs p-3 bg-slate-50 border border-slate-300 rounded-xl focus:ring-2 focus:ring-brand-500">
                        <option value="">Pilih Kategori</option>
                        @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <!-- Penulis -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Penulis / Pengarang *</label>
                    <input type="text" name="author" value="{{ old('author') }}" required placeholder="Contoh: Leila S. Chudori"
                        class="w-full text-xs p-3 bg-slate-50 border border-slate-300 rounded-xl focus:ring-2 focus:ring-brand-500">
                </div>

                <!-- Penerbit -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Penerbit *</label>
                    <input type="text" name="publisher" value="{{ old('publisher') }}" required placeholder="Contoh: Kepustakaan Populer Gramedia"
                        class="w-full text-xs p-3 bg-slate-50 border border-slate-300 rounded-xl focus:ring-2 focus:ring-brand-500">
                </div>

                <!-- Tahun Terbit -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Tahun Terbit *</label>
                    <input type="number" name="publication_year" value="{{ old('publication_year', date('Y')) }}" required min="1900" max="{{ date('Y') + 1 }}"
                        class="w-full text-xs p-3 bg-slate-50 border border-slate-300 rounded-xl focus:ring-2 focus:ring-brand-500">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-4 gap-6">
                <!-- ISBN -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Nomor ISBN *</label>
                    <input type="text" name="isbn" value="{{ old('isbn') }}" required placeholder="978-602-xxx"
                        class="w-full text-xs p-3 bg-slate-50 border border-slate-300 rounded-xl focus:ring-2 focus:ring-brand-500 font-mono">
                </div>

                <!-- Halaman -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Jumlah Halaman</label>
                    <input type="number" name="pages" value="{{ old('pages') }}" min="1" placeholder="380"
                        class="w-full text-xs p-3 bg-slate-50 border border-slate-300 rounded-xl focus:ring-2 focus:ring-brand-500">
                </div>

                <!-- Lokasi Rak -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Lokasi Rak *</label>
                    <input type="text" name="shelf_location" value="{{ old('shelf_location', 'Rak A-01') }}" required placeholder="Rak A-01"
                        class="w-full text-xs p-3 bg-slate-50 border border-slate-300 rounded-xl focus:ring-2 focus:ring-brand-500">
                </div>

                <!-- Jumlah Stok Fisik -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Total Stok Fisik *</label>
                    <input type="number" name="stock_total" value="{{ old('stock_total', 5) }}" required min="0"
                        class="w-full text-xs p-3 bg-slate-50 border border-slate-300 rounded-xl focus:ring-2 focus:ring-brand-500">
                </div>
            </div>

            <!-- Bahasa -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Bahasa Buku</label>
                <input type="text" name="language" value="{{ old('language', 'Indonesia') }}" required
                    class="w-full text-xs p-3 bg-slate-50 border border-slate-300 rounded-xl focus:ring-2 focus:ring-brand-500">
            </div>

            <!-- Deskripsi / Sinopsis -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Sinopsis & Deskripsi Buku *</label>
                <textarea name="description" rows="4" required placeholder="Tuliskan ringkasan isi buku, target pembaca, dan pokok bahasan..."
                    class="w-full text-xs p-3 bg-slate-50 border border-slate-300 rounded-xl focus:ring-2 focus:ring-brand-500 leading-relaxed">{{ old('description') }}</textarea>
            </div>

            <!-- Cover Image Options -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 p-4 bg-slate-50 rounded-2xl border border-slate-200">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Upload File Cover (JPG/PNG)</label>
                    <input type="file" name="cover_file" accept="image/*" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-brand-50 file:text-brand-700 hover:file:bg-brand-100">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Atau URL Gambar Cover Online</label>
                    <input type="url" name="cover_url" value="{{ old('cover_url') }}" placeholder="https://images.unsplash.com/photo-..."
                        class="w-full text-xs p-2.5 bg-white border border-slate-300 rounded-xl focus:ring-2 focus:ring-brand-500">
                </div>
            </div>

            <!-- Digital E-Book Upload -->
            <div class="p-4 bg-emerald-50/50 rounded-2xl border border-emerald-200/80 space-y-3">
                <div class="flex items-center gap-2">
                    <input type="checkbox" name="has_ebook" id="has_ebook" value="1" {{ old('has_ebook') ? 'checked' : '' }} class="rounded text-emerald-600">
                    <label for="has_ebook" class="text-xs font-bold text-emerald-900 cursor-pointer">Buku Memiliki Versi E-Book Digital (Bisa Dibaca Online)</label>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-emerald-800 mb-1">Unggah Dokumen PDF E-Book (Maks 20MB)</label>
                    <input type="file" name="ebook_file" accept="application/pdf" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-100 file:text-emerald-800 hover:file:bg-emerald-200">
                </div>
            </div>

            <!-- Featured Checkbox -->
            <div class="flex items-center gap-2">
                <input type="checkbox" name="is_featured" id="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }} class="rounded text-brand-600">
                <label for="is_featured" class="text-xs font-bold text-slate-700 cursor-pointer">Tandai Sebagai Buku Pilihan / Unggulan di Halaman Utama</label>
            </div>

            <!-- Submit -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="{{ route('admin.books.index') }}" class="px-5 py-2.5 text-xs font-semibold text-slate-600 hover:bg-slate-100 rounded-xl transition-colors">Batal</a>
                <button type="submit" class="px-6 py-2.5 bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs rounded-xl shadow-md transition-colors flex items-center gap-2">
                    <i data-lucide="check" class="w-4 h-4"></i>
                    <span>Simpan Buku ke Katalog</span>
                </button>
            </div>
        </form>
    </div>

</div>
@endsection

