@extends('layouts.app')

@section('title', 'Rak Buku Pribadi - Aksaraya')

@section('content')
<!-- Header Banner -->
<div class="bg-gradient-to-r from-navy-950 via-slate-900 to-brand-950 text-white py-12 border-b border-slate-800 relative overflow-hidden">
    <div class="absolute -right-20 -top-20 w-80 h-80 bg-brand-500/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="max-w-3xl space-y-2">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-brand-500/20 text-brand-300 border border-brand-500/30 text-xs font-bold uppercase tracking-wider">
                Personal Bookshelf
            </span>
            <h1 class="font-display text-3xl sm:text-4xl font-black tracking-tight">Rak Buku Pribadi</h1>
            <p class="text-xs sm:text-sm text-slate-300">
                Koleksi buku impian dan riwayat bacaan yang Anda atur ke dalam rak personal.
            </p>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-8">

    <!-- Shelf Tabs Ribbon -->
    <div class="flex flex-wrap items-center gap-3 bg-white p-2.5 rounded-3xl border border-slate-200/80 shadow-sm text-xs font-bold">
        <a href="{{ route('member.shelves', ['type' => 'want_to_read']) }}" class="px-5 py-2.5 rounded-2xl transition-all flex items-center gap-2 {{ $shelfType === 'want_to_read' ? 'bg-brand-600 text-white shadow-glow' : 'text-slate-600 hover:bg-slate-100' }}">
            <i data-lucide="bookmark-plus" class="w-4 h-4"></i>
            <span>Ingin Dibaca ({{ $counts['want_to_read'] }})</span>
        </a>
        <a href="{{ route('member.shelves', ['type' => 'currently_reading']) }}" class="px-5 py-2.5 rounded-2xl transition-all flex items-center gap-2 {{ $shelfType === 'currently_reading' ? 'bg-amber-500 text-slate-950 shadow-sm' : 'text-slate-600 hover:bg-slate-100' }}">
            <i data-lucide="book-open" class="w-4 h-4"></i>
            <span>Sedang Dibaca ({{ $counts['currently_reading'] }})</span>
        </a>
        <a href="{{ route('member.shelves', ['type' => 'already_read']) }}" class="px-5 py-2.5 rounded-2xl transition-all flex items-center gap-2 {{ $shelfType === 'already_read' ? 'bg-emerald-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100' }}">
            <i data-lucide="check-circle" class="w-4 h-4"></i>
            <span>Selesai Dibaca ({{ $counts['already_read'] }})</span>
        </a>
    </div>

    <!-- Shelf Books Grid -->
    @if($shelves->count() > 0)
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-5">
        @foreach($shelves as $item)
        <div class="bg-white rounded-3xl border border-slate-200/80 p-4 shadow-sm hover:shadow-md transition-all flex flex-col justify-between group">
            <div>
                <div class="aspect-[3/4] rounded-2xl bg-slate-100 overflow-hidden mb-3 relative shadow-xs">
                    <img src="{{ $item->book->cover_image ?? 'https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?auto=format&fit=crop&w=400&q=80' }}" 
                         alt="{{ $item->book->title }}" 
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    
                    @if($item->book->has_ebook)
                    <span class="absolute top-2 left-2 px-2 py-0.5 rounded-md text-[9px] font-extrabold bg-emerald-600 text-white shadow">
                        E-Book
                    </span>
                    @endif
                </div>

                <span class="text-[10px] font-bold text-brand-700 block uppercase tracking-wider">{{ $item->book->category->name }}</span>
                <a href="{{ route('catalog.show', $item->book->slug) }}" class="font-display font-bold text-xs sm:text-sm text-slate-900 group-hover:text-brand-700 transition-colors line-clamp-2 mt-0.5">
                    {{ $item->book->title }}
                </a>
                <p class="text-[11px] text-slate-400 truncate mt-0.5">{{ $item->book->author }}</p>
            </div>

            <div class="pt-3 border-t border-slate-100 mt-3 flex items-center justify-between">
                <a href="{{ route('catalog.show', $item->book->slug) }}" class="text-xs font-bold text-brand-700 hover:underline">
                    Buka
                </a>

                <form method="POST" action="{{ route('member.shelves.update', $item->book->id) }}">
                    @csrf
                    <input type="hidden" name="shelf_type" value="remove">
                    <button type="submit" class="text-[11px] text-rose-500 hover:text-rose-700 font-semibold p-1 hover:bg-rose-50 rounded-lg transition-colors" title="Hapus dari Rak">
                        <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                    </button>
                </form>
            </div>
        </div>
        @endforeach
    </div>

    <div class="pt-4">
        {{ $shelves->links() }}
    </div>
    @else
    <div class="bg-white rounded-3xl border border-slate-200/80 p-12 text-center text-slate-400 space-y-3">
        <i data-lucide="library" class="w-12 h-12 mx-auto text-slate-300"></i>
        <h3 class="font-display font-bold text-slate-700 text-base">Belum ada buku di rak ini</h3>
        <p class="text-xs max-w-sm mx-auto">Buka katalog buku dan masukkan buku favorit ke rak pribadi Anda untuk memudahkan rencana membaca.</p>
        <div class="pt-2">
            <a href="{{ route('catalog.index') }}" class="px-5 py-2.5 bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs rounded-xl shadow-glow inline-flex items-center gap-2">
                <i data-lucide="search" class="w-4 h-4"></i>
                <span>Eksplorasi Katalog</span>
            </a>
        </div>
    </div>
    @endif

</div>
@endsection

