@extends('layouts.admin')

@section('title', 'Katalog Koleksi Buku & E-Book - Aksaraya')
@section('page-title', 'Katalog & Koleksi Buku')

@section('content')
<div class="space-y-6 mt-6">
    
    <!-- Action & Filter Bar -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 bg-white p-4 rounded-3xl border border-slate-200/80 shadow-sm" x-data="{ showImportModal: false }">
        <form method="GET" action="{{ route('admin.books.index') }}" class="flex flex-wrap items-center gap-3 flex-1">
            <div class="relative min-w-[220px] flex-1">
                <input type="text" name="q" value="{{ $search }}" placeholder="Cari judul, penulis, ISBN..." 
                    class="w-full pl-9 pr-3 py-2 text-xs bg-slate-50 border border-slate-300 rounded-xl focus:ring-2 focus:ring-brand-500 font-medium">
                <i data-lucide="search" class="w-4 h-4 text-slate-400 absolute left-3 top-2.5"></i>
            </div>

            <select name="category_id" class="py-2 px-3 text-xs bg-slate-50 border border-slate-300 rounded-xl focus:ring-2 focus:ring-brand-500 font-medium">
                <option value="">Semua Kategori</option>
                @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ $categoryId == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>

            <button type="submit" class="px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs rounded-xl shadow-xs transition-colors">
                Filter
            </button>
            @if($search || $categoryId)
            <a href="{{ route('admin.books.index') }}" class="text-xs font-bold text-rose-600 hover:underline">Reset</a>
            @endif
        </form>

        <div class="flex flex-wrap items-center gap-2 shrink-0">
            <!-- Export CSV -->
            <a href="{{ route('admin.books.export') }}" class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition-all flex items-center gap-1.5" title="Ekspor Katalog ke CSV/Excel">
                <i data-lucide="file-spreadsheet" class="w-4 h-4 text-emerald-600"></i>
                <span>Ekspor CSV</span>
            </a>

            <!-- Import CSV Modal Trigger -->
            <button @click="showImportModal = true" type="button" class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition-all flex items-center gap-1.5" title="Impor Data Buku dari CSV (Format SLiMS/Inlislite)">
                <i data-lucide="upload-cloud" class="w-4 h-4 text-brand-600"></i>
                <span>Impor CSV</span>
            </button>

            <!-- Add Book -->
            <a href="{{ route('admin.books.create') }}" class="px-4 py-2 bg-gradient-to-r from-brand-600 to-teal-700 hover:from-brand-700 hover:to-teal-800 text-white font-bold text-xs rounded-xl shadow-glow flex items-center justify-center gap-2 transition-all">
                <i data-lucide="plus" class="w-4 h-4"></i>
                <span>Tambah Buku</span>
            </a>
        </div>

        <!-- Import Modal -->
        <div x-show="showImportModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div @click="showImportModal = false" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm"></div>
            <div class="relative bg-white rounded-3xl max-w-md w-full p-6 sm:p-8 shadow-2xl z-10 space-y-4 border border-slate-100">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <h3 class="font-display font-bold text-slate-900 text-base">Impor Buku Massal (CSV)</h3>
                    <button @click="showImportModal = false" class="text-slate-400 hover:text-slate-600 p-1">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>
                <p class="text-xs text-slate-500 leading-relaxed">
                    Unggah berkas CSV daftar buku. Format kolom yang didukung: <br>
                    <code class="text-[10px] bg-slate-100 px-1.5 py-0.5 rounded font-mono text-slate-800 font-bold">ID, ISBN, Judul, Penulis, Penerbit, Tahun, Kategori, Rak, Stok</code>
                </p>
                <form method="POST" action="{{ route('admin.books.import') }}" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <div class="p-4 bg-slate-50 border-2 border-dashed border-slate-300 rounded-2xl text-center space-y-2">
                        <i data-lucide="file-up" class="w-8 h-8 text-brand-600 mx-auto"></i>
                        <input type="file" name="csv_file" accept=".csv,.txt" required class="text-xs text-slate-500 file:mr-2 file:py-1 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-brand-50 file:text-brand-700 hover:file:bg-brand-100">
                    </div>
                    <div class="flex justify-end gap-2 pt-2">
                        <button type="button" @click="showImportModal = false" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl">Batal</button>
                        <button type="submit" class="px-5 py-2 bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold rounded-xl shadow-glow">Mulai Impor</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Books Table -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 font-bold uppercase tracking-wider text-[10px]">
                        <th class="p-4 pl-6">Koleksi Buku</th>
                        <th class="p-4">Kategori & Rak</th>
                        <th class="p-4">ISBN</th>
                        <th class="p-4">Tahun</th>
                        <th class="p-4">Stok (Sisa / Total)</th>
                        <th class="p-4">Format</th>
                        <th class="p-4 pr-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($books as $book)
                    <tr class="hover:bg-slate-50/60 transition-colors">
                        <td class="p-4 pl-6">
                            <div class="flex items-center gap-3">
                                <img src="{{ $book->cover_image ?? 'https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?auto=format&fit=crop&w=200&q=80' }}" 
                                     alt="" class="w-10 h-14 object-cover rounded-xl shadow-xs shrink-0">
                                <div class="max-w-[220px]">
                                    <a href="{{ route('catalog.show', $book->slug) }}" target="_blank" class="font-display font-bold text-slate-900 hover:text-brand-700 line-clamp-1">
                                        {{ $book->title }}
                                    </a>
                                    <p class="text-[11px] text-slate-400 truncate">{{ $book->author }}</p>
                                    @if($book->is_featured)
                                    <span class="inline-block mt-0.5 text-[9px] font-extrabold text-amber-800 bg-amber-50 px-1.5 py-0.2 rounded border border-amber-200">
                                        Unggulan
                                    </span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="p-4">
                            <span class="font-bold text-brand-700 block">{{ $book->category->name }}</span>
                            <span class="text-[11px] text-slate-400 font-medium">{{ $book->shelf_location }}</span>
                        </td>
                        <td class="p-4 font-mono font-medium">{{ $book->isbn }}</td>
                        <td class="p-4 font-medium">{{ $book->publication_year }}</td>
                        <td class="p-4">
                            <span class="font-bold {{ $book->stock_available > 0 ? 'text-emerald-700' : 'text-rose-600' }}">
                                {{ $book->stock_available }}
                            </span>
                            <span class="text-slate-400">/ {{ $book->stock_total }} eks</span>
                        </td>
                        <td class="p-4">
                            @if($book->has_ebook)
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                E-Book Online
                            </span>
                            @else
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-medium bg-slate-100 text-slate-600">
                                Buku Fisik
                            </span>
                            @endif
                        </td>
                        <td class="p-4 pr-6 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.books.edit', $book->id) }}" class="p-1.5 text-slate-500 hover:text-brand-700 hover:bg-slate-100 rounded-lg transition-colors" title="Edit Buku">
                                    <i data-lucide="edit" class="w-4 h-4"></i>
                                </a>
                                <form action="{{ route('admin.books.destroy', $book->id) }}" method="POST" onsubmit="return confirm('Hapus buku {{ $book->title }} dari sistem?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors" title="Hapus">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="p-10 text-center text-slate-400 text-xs">
                            Tidak ada koleksi buku yang ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-100">
            {{ $books->links() }}
        </div>
    </div>

</div>
@endsection
