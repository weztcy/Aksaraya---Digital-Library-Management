@extends('layouts.app')

@section('title', 'Katalog Koleksi Buku & E-Book - Aksaraya')

@section('content')
<!-- Header Banner -->
<div class="bg-gradient-to-r from-navy-950 via-slate-900 to-brand-950 text-white py-14 border-b border-slate-800 relative overflow-hidden">
    <div class="absolute -right-20 -top-20 w-80 h-80 bg-brand-500/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="max-w-3xl space-y-3">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-brand-500/20 text-brand-300 border border-brand-500/30 text-xs font-bold uppercase tracking-wider">
                Khazanah Literasi
            </span>
            <h1 class="font-display text-3xl sm:text-4xl lg:text-5xl font-black tracking-tight">Katalog Koleksi Aksaraya</h1>
            <p class="text-sm sm:text-base text-slate-300 leading-relaxed font-normal">
                Telusuri dan temukan koleksi buku fisik di rak perpustakaan maupun buku digital yang siap dibaca secara online kapan saja.
            </p>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="{ viewMode: 'grid' }">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        
        <!-- Filter Sidebar -->
        <aside class="lg:col-span-1 space-y-6">
            <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm sticky top-28 space-y-6">
                
                <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                    <h2 class="font-display font-bold text-slate-900 flex items-center gap-2 text-sm tracking-tight">
                        <i data-lucide="sliders-horizontal" class="w-4 h-4 text-brand-600"></i>
                        <span>Filter Koleksi</span>
                    </h2>
                    <a href="{{ route('catalog.index') }}" class="text-xs font-bold text-rose-600 hover:text-rose-700 hover:underline">
                        Reset Filter
                    </a>
                </div>

                <form method="GET" action="{{ route('catalog.index') }}" class="space-y-5">
                    
                    <!-- Search Input -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Kata Kunci</label>
                        <div class="relative">
                            <input type="text" name="q" value="{{ $search }}" placeholder="Judul, pengarang, ISBN..." 
                                class="w-full pl-9 pr-3 py-2.5 text-xs bg-slate-50 border border-slate-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-500 focus:bg-white transition-all">
                            <i data-lucide="search" class="w-4 h-4 text-slate-400 absolute left-3 top-3"></i>
                        </div>
                    </div>

                    <!-- Category Filter -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Kategori Buku</label>
                        <select name="kategori" class="w-full py-2.5 px-3 text-xs bg-slate-50 border border-slate-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-500 font-medium">
                            <option value="">Semua Kategori</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->slug }}" {{ $categorySlug === $cat->slug ? 'selected' : '' }}>
                                    {{ $cat->name }} ({{ $cat->books_count }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Format / Type Filter -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Format Buku</label>
                        <div class="space-y-1.5 text-xs text-slate-600">
                            <label class="flex items-center gap-2.5 cursor-pointer p-2 rounded-xl hover:bg-slate-50 transition-colors {{ empty($type) ? 'bg-slate-50 font-bold text-slate-900' : '' }}">
                                <input type="radio" name="tipe" value="" {{ empty($type) ? 'checked' : '' }} class="text-brand-600">
                                <span>Semua Koleksi</span>
                            </label>
                            <label class="flex items-center gap-2.5 cursor-pointer p-2 rounded-xl hover:bg-slate-50 transition-colors {{ $type === 'ebook' ? 'bg-brand-50/70 font-bold text-brand-900' : '' }}">
                                <input type="radio" name="tipe" value="ebook" {{ $type === 'ebook' ? 'checked' : '' }} class="text-brand-600">
                                <span class="flex items-center gap-1.5">
                                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                    <span>E-Book Digital (Baca Online)</span>
                                </span>
                            </label>
                            <label class="flex items-center gap-2.5 cursor-pointer p-2 rounded-xl hover:bg-slate-50 transition-colors {{ $type === 'fisik' ? 'bg-slate-50 font-bold text-slate-900' : '' }}">
                                <input type="radio" name="tipe" value="fisik" {{ $type === 'fisik' ? 'checked' : '' }} class="text-brand-600">
                                <span>Buku Fisik (Tersedia di Rak)</span>
                            </label>
                        </div>
                    </div>

                    <!-- Availability -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Ketersediaan Stok Fisik</label>
                        <label class="flex items-center gap-2.5 text-xs text-slate-700 cursor-pointer p-2 rounded-xl hover:bg-slate-50">
                            <input type="checkbox" name="ketersediaan" value="tersedia" {{ $availability === 'tersedia' ? 'checked' : '' }} class="rounded text-brand-600">
                            <span>Hanya buku yang siap dipinjam</span>
                        </label>
                    </div>

                    <!-- Sorting -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Urutan Tampilan</label>
                        <select name="urutan" class="w-full py-2.5 px-3 text-xs bg-slate-50 border border-slate-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-500 font-medium">
                            <option value="terbaru" {{ $sortBy === 'terbaru' ? 'selected' : '' }}>Terbaru Ditambahkan</option>
                            <option value="populer" {{ $sortBy === 'populer' ? 'selected' : '' }}>Paling Banyak Dilihat</option>
                            <option value="judul_asc" {{ $sortBy === 'judul_asc' ? 'selected' : '' }}>Judul (A - Z)</option>
                            <option value="tahun_desc" {{ $sortBy === 'tahun_desc' ? 'selected' : '' }}>Tahun Terbit Terbaru</option>
                        </select>
                    </div>

                    <button type="submit" class="w-full py-3 px-4 bg-gradient-to-r from-brand-600 to-teal-700 hover:from-brand-700 hover:to-teal-800 text-white font-bold text-xs rounded-xl shadow-glow transition-all flex items-center justify-center gap-2">
                        <i data-lucide="check" class="w-4 h-4"></i>
                        <span>Terapkan Filter</span>
                    </button>
                </form>

            </div>
        </aside>

        <!-- Books Main Content -->
        <main class="lg:col-span-3 space-y-6">
            
            <!-- Result & View Controls Bar -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-4 rounded-3xl border border-slate-200/80 shadow-sm">
                <div>
                    <h2 class="font-display text-sm font-bold text-slate-900">
                        @if($currentCategory)
                            Kategori: <span class="text-brand-700">{{ $currentCategory->name }}</span>
                        @elseif($search)
                            Hasil pencarian: "<span class="text-brand-700">{{ $search }}</span>"
                        @elseif($type === 'ebook')
                            Format: <span class="text-emerald-700">Koleksi E-Book Digital Online</span>
                        @else
                            Seluruh Koleksi Aksaraya
                        @endif
                    </h2>
                    <p class="text-xs text-slate-500 mt-0.5">Ditemukan <strong>{{ $books->total() }}</strong> judul buku</p>
                </div>

                <!-- Right View Mode Toggle -->
                <div class="flex items-center gap-2">
                    <button @click="viewMode = 'grid'" :class="{ 'bg-slate-900 text-white': viewMode === 'grid', 'bg-slate-100 text-slate-600': viewMode !== 'grid' }" class="p-2 rounded-xl transition-colors" title="Tampilan Kotak (Grid)">
                        <i data-lucide="grid" class="w-4 h-4"></i>
                    </button>
                    <button @click="viewMode = 'list'" :class="{ 'bg-slate-900 text-white': viewMode === 'list', 'bg-slate-100 text-slate-600': viewMode !== 'list' }" class="p-2 rounded-xl transition-colors" title="Tampilan Daftar (List)">
                        <i data-lucide="list" class="w-4 h-4"></i>
                    </button>
                </div>
            </div>

            <!-- Active Filters Pills -->
            @if($categorySlug || $type || $availability || $search)
            <div class="flex flex-wrap items-center gap-2">
                <span class="text-xs text-slate-500 font-medium">Filter aktif:</span>

                @if($categorySlug)
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-brand-50 text-brand-700 border border-brand-200">
                    Kategori: {{ $currentCategory->name ?? $categorySlug }}
                    <a href="{{ route('catalog.index', array_merge(request()->except('kategori'))) }}" class="text-brand-400 hover:text-brand-800 font-bold">&times;</a>
                </span>
                @endif

                @if($type)
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                    Format: {{ $type === 'ebook' ? 'E-Book Online' : 'Buku Fisik' }}
                    <a href="{{ route('catalog.index', array_merge(request()->except('tipe'))) }}" class="text-emerald-400 hover:text-emerald-800 font-bold">&times;</a>
                </span>
                @endif

                @if($availability)
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-sky-50 text-sky-700 border border-sky-200">
                    Stok Tersedia
                    <a href="{{ route('catalog.index', array_merge(request()->except('ketersediaan'))) }}" class="text-sky-400 hover:text-sky-800 font-bold">&times;</a>
                </span>
                @endif

                @if($search)
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-700 border border-slate-200">
                    Kata: "{{ $search }}"
                    <a href="{{ route('catalog.index', array_merge(request()->except('q'))) }}" class="text-slate-400 hover:text-slate-800 font-bold">&times;</a>
                </span>
                @endif

                <a href="{{ route('catalog.index') }}" class="text-xs font-bold text-rose-600 hover:underline ml-1">
                    Bersihkan Semua
                </a>
            </div>
            @endif

            <!-- Books Display -->
            @if($books->count() > 0)
            
            <!-- GRID VIEW -->
            <div x-show="viewMode === 'grid'" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($books as $book)
                <div class="group bg-white rounded-3xl border border-slate-200/80 overflow-hidden shadow-book hover:shadow-book-hover transition-all duration-300 flex flex-col justify-between hover:-translate-y-2">
                    
                    <div>
                        <!-- Cover Image with Badges -->
                        <div class="relative aspect-[3/4] bg-slate-100 overflow-hidden">
                            <img src="{{ $book->cover_image ?? 'https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?auto=format&fit=crop&w=600&q=80' }}" 
                                alt="{{ $book->title }}" 
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">

                            <!-- Top Badges -->
                            <div class="absolute top-3 left-3 flex flex-col gap-1.5">
                                @if($book->has_ebook)
                                <span class="px-2.5 py-0.5 rounded-md text-[10px] font-extrabold bg-gradient-to-r from-emerald-600 to-teal-600 text-white shadow-md flex items-center gap-1">
                                    <i data-lucide="file-text" class="w-3 h-3"></i>
                                    E-Book
                                </span>
                                @endif

                                @if($book->is_featured)
                                <span class="px-2.5 py-0.5 rounded-md text-[10px] font-extrabold bg-amber-400 text-slate-950 shadow-md">
                                    Unggulan
                                </span>
                                @endif
                            </div>

                            <!-- Bookmark Toggle Form -->
                            @auth
                            <form action="{{ route('catalog.bookmark', $book->id) }}" method="POST" class="absolute top-3 right-3">
                                @csrf
                                <button type="submit" class="w-8 h-8 rounded-full bg-white/90 backdrop-blur text-slate-600 hover:text-rose-500 hover:bg-white flex items-center justify-center shadow-md transition-colors" title="Simpan ke Favorit">
                                    <i data-lucide="bookmark" class="w-4 h-4 {{ $book->isBookmarkedBy(auth()->user()) ? 'fill-rose-500 text-rose-500' : '' }}"></i>
                                </button>
                            </form>
                            @endauth

                            <!-- Rating Pill -->
                            <div class="absolute bottom-3 right-3 px-2 py-0.5 rounded-lg text-[10px] font-black bg-slate-950/80 text-white backdrop-blur flex items-center gap-1">
                                <i data-lucide="star" class="w-3 h-3 text-amber-400 fill-amber-400"></i>
                                <span>{{ $book->average_rating > 0 ? $book->average_rating : '5.0' }}</span>
                            </div>
                        </div>

                        <!-- Info Content -->
                        <div class="p-5 space-y-2">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-brand-600">{{ $book->category->name }}</span>
                            <h3 class="font-display font-bold text-sm sm:text-base text-slate-900 group-hover:text-brand-700 transition-colors line-clamp-2 leading-snug">
                                <a href="{{ route('catalog.show', $book->slug) }}">{{ $book->title }}</a>
                            </h3>
                            <p class="text-xs text-slate-500 truncate">{{ $book->author }} ({{ $book->publication_year }})</p>
                            <p class="text-xs text-slate-400 flex items-center gap-1.5 pt-0.5">
                                <i data-lucide="map-pin" class="w-3.5 h-3.5 text-slate-400"></i>
                                <span>{{ $book->shelf_location }}</span>
                            </p>
                        </div>
                    </div>

                    <!-- Card Actions Footer -->
                    <div class="p-5 pt-0">
                        <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                            <span class="inline-flex items-center gap-1.5 font-bold {{ $book->stock_available > 0 ? 'text-emerald-700' : 'text-slate-400' }}">
                                <span class="w-2 h-2 rounded-full {{ $book->stock_available > 0 ? 'bg-emerald-500' : 'bg-slate-300' }}"></span>
                                <span>{{ $book->stock_available > 0 ? 'Stok ' . $book->stock_available : 'Fisik Kosong' }}</span>
                            </span>

                            <div class="flex items-center gap-2">
                                @if($book->has_ebook)
                                <a href="{{ route('catalog.read', $book->slug) }}" class="px-2.5 py-1 text-[11px] font-bold bg-emerald-50 text-emerald-700 hover:bg-emerald-100 rounded-lg transition-colors">
                                    Baca
                                </a>
                                @endif
                                <a href="{{ route('catalog.show', $book->slug) }}" class="px-3.5 py-1 text-[11px] font-bold bg-slate-900 text-white hover:bg-brand-700 rounded-lg shadow-sm transition-colors">
                                    Detail
                                </a>
                            </div>
                        </div>
                    </div>

                </div>
                @endforeach
            </div>

            <!-- LIST VIEW -->
            <div x-show="viewMode === 'list'" x-cloak class="space-y-4">
                @foreach($books as $book)
                <div class="bg-white rounded-3xl border border-slate-200/80 p-5 shadow-sm hover:shadow-md transition-all flex flex-col sm:flex-row gap-5">
                    <div class="w-24 shrink-0 aspect-[3/4] rounded-2xl bg-slate-100 overflow-hidden shadow-xs">
                        <img src="{{ $book->cover_image ?? 'https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?auto=format&fit=crop&w=400&q=80' }}" 
                             alt="{{ $book->title }}" 
                             class="w-full h-full object-cover">
                    </div>

                    <div class="flex-1 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between gap-2">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-brand-600">{{ $book->category->name }}</span>
                                <div class="flex items-center gap-1 text-xs text-amber-600 font-bold">
                                    <i data-lucide="star" class="w-3.5 h-3.5 fill-amber-400 text-amber-400"></i>
                                    <span>{{ $book->average_rating > 0 ? $book->average_rating : '5.0' }}</span>
                                </div>
                            </div>

                            <h3 class="font-display font-bold text-base text-slate-900 hover:text-brand-700 mt-1">
                                <a href="{{ route('catalog.show', $book->slug) }}">{{ $book->title }}</a>
                            </h3>
                            <p class="text-xs text-slate-500">{{ $book->author }} &bull; {{ $book->publisher }} ({{ $book->publication_year }})</p>
                            
                            <p class="text-xs text-slate-600 line-clamp-2 mt-2 leading-relaxed">
                                {{ $book->description }}
                            </p>
                        </div>

                        <div class="pt-3 border-t border-slate-100 mt-3 flex items-center justify-between text-xs">
                            <div class="flex items-center gap-4 text-slate-500">
                                <span class="font-bold {{ $book->stock_available > 0 ? 'text-emerald-700' : 'text-slate-400' }}">
                                    Stok Fisik: {{ $book->stock_available }}
                                </span>
                                <span>Lokasi: {{ $book->shelf_location }}</span>
                            </div>

                            <div class="flex items-center gap-2">
                                @if($book->has_ebook)
                                <a href="{{ route('catalog.read', $book->slug) }}" class="px-3 py-1.5 text-xs font-bold bg-emerald-50 text-emerald-700 hover:bg-emerald-100 rounded-xl transition-colors">
                                    Baca E-Book
                                </a>
                                @endif
                                <a href="{{ route('catalog.show', $book->slug) }}" class="px-4 py-1.5 text-xs font-bold bg-slate-900 text-white hover:bg-brand-700 rounded-xl transition-colors shadow-xs">
                                    Detail Buku
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="pt-6">
                {{ $books->links() }}
            </div>

            @else
            <!-- Empty State -->
            <div class="bg-white rounded-3xl p-16 text-center border border-slate-200/80 shadow-sm space-y-4">
                <div class="w-16 h-16 rounded-3xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto">
                    <i data-lucide="book-x" class="w-8 h-8"></i>
                </div>
                <h3 class="font-display font-bold text-lg text-slate-900">Koleksi Tidak Ditemukan</h3>
                <p class="text-xs sm:text-sm text-slate-500 max-w-md mx-auto">
                    Buku yang Anda cari belum tersedia atau tidak memenuhi kriteria filter. Silakan ubah kata kunci atau hapus filter.
                </p>
                <div class="pt-2">
                    <a href="{{ route('catalog.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-brand-600 text-white rounded-xl text-xs font-bold hover:bg-brand-700 shadow-glow transition-all">
                        <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                        <span>Reset Semua Filter</span>
                    </a>
                </div>
            </div>
            @endif

        </main>
    </div>
</div>
@endsection
