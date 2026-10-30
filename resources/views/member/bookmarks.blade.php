@extends('layouts.app')

@section('title', 'Koleksi Buku Favorit Saya - Aksaraya')

@section('content')
<!-- Header Banner -->
<div class="bg-gradient-to-r from-navy-950 via-slate-900 to-brand-950 text-white py-12 border-b border-slate-800 relative overflow-hidden">
    <div class="absolute -right-20 -top-20 w-80 h-80 bg-brand-500/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="max-w-3xl space-y-2">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-brand-500/20 text-brand-300 border border-brand-500/30 text-xs font-bold uppercase tracking-wider">
                Daftar Bacaan
            </span>
            <h1 class="font-display text-3xl sm:text-4xl font-black tracking-tight text-white">Koleksi Buku Favorit Saya</h1>
            <p class="text-xs sm:text-sm text-slate-300">
                Koleksi bahan pustaka pilihan yang Anda simpan untuk dibaca atau dipinjam sewaktu-waktu.
            </p>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    @if($bookmarks->count() > 0)
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-6">
        @foreach($bookmarks as $book)
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-book hover:shadow-book-hover transition-all duration-300 overflow-hidden flex flex-col justify-between hover:-translate-y-2">
            <div>
                <div class="relative aspect-[3/4] bg-slate-100 overflow-hidden">
                    <img src="{{ $book->cover_image ?? 'https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?auto=format&fit=crop&w=400&q=80' }}" 
                         alt="{{ $book->title }}" 
                         class="w-full h-full object-cover">
                    
                    @if($book->has_ebook)
                    <span class="absolute top-2.5 left-2.5 px-2 py-0.5 rounded text-[10px] font-extrabold bg-gradient-to-r from-emerald-600 to-teal-600 text-white shadow">
                        E-Book
                    </span>
                    @endif

                    <form action="{{ route('catalog.bookmark', $book->id) }}" method="POST" class="absolute top-2.5 right-2.5">
                        @csrf
                        <button type="submit" class="w-8 h-8 rounded-full bg-white/90 text-rose-500 hover:bg-white flex items-center justify-center shadow-md transition-colors" title="Hapus dari Favorit">
                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                        </button>
                    </form>
                </div>

                <div class="p-4 space-y-1.5">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-brand-600">{{ $book->category->name }}</span>
                    <h3 class="font-display font-bold text-xs sm:text-sm text-slate-900 line-clamp-1 hover:text-brand-700">
                        <a href="{{ route('catalog.show', $book->slug) }}">{{ $book->title }}</a>
                    </h3>
                    <p class="text-[11px] text-slate-500 truncate">{{ $book->author }}</p>
                </div>
            </div>

            <div class="p-4 pt-0">
                <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                    <span class="font-bold {{ $book->stock_available > 0 ? 'text-emerald-700' : 'text-slate-400' }}">
                        {{ $book->stock_available > 0 ? 'Stok ' . $book->stock_available : 'Fisik Habis' }}
                    </span>
                    <a href="{{ route('catalog.show', $book->slug) }}" class="font-bold text-brand-700 hover:text-brand-900 text-xs">
                        Lihat Buku &rarr;
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="pt-6">
        {{ $bookmarks->links() }}
    </div>
    @else
    <div class="bg-white rounded-3xl p-16 text-center border border-slate-200/80 shadow-sm space-y-4">
        <div class="w-16 h-16 rounded-3xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto">
            <i data-lucide="bookmark-x" class="w-8 h-8"></i>
        </div>
        <h3 class="font-display font-bold text-slate-900 text-base">Belum Ada Buku Favorit</h3>
        <p class="text-xs text-slate-500 max-w-sm mx-auto">
            Anda belum menandai buku apapun. Klik ikon bookmark pada buku di katalog untuk menyimpannya di sini.
        </p>
        <div class="pt-2">
            <a href="{{ route('catalog.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-brand-600 text-white rounded-xl text-xs font-bold hover:bg-brand-700 shadow-glow transition-all">
                <span>Jelajahi Katalog Buku</span>
            </a>
        </div>
    </div>
    @endif
</div>
@endsection
