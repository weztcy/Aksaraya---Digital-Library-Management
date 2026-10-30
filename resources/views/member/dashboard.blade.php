@extends('layouts.app')

@section('title', 'Dashboard Anggota - Aksaraya')

@section('content')
<!-- Header Banner -->
<div class="bg-gradient-to-r from-navy-950 via-slate-900 to-brand-950 text-white py-12 border-b border-slate-800 relative overflow-hidden">
    <div class="absolute -right-20 -top-20 w-80 h-80 bg-brand-500/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6">
            
            <div class="space-y-2">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-brand-500/20 text-brand-300 border border-brand-500/30 text-xs font-bold uppercase tracking-wider">
                    Ruang Anggota
                </span>
                <h1 class="font-display text-3xl sm:text-4xl font-black tracking-tight text-white">
                    Halo, {{ $user->name }}!
                </h1>
                <p class="text-xs sm:text-sm text-slate-300 flex items-center gap-2">
                    <span>Nomor Kartu:</span>
                    <span class="font-mono font-bold text-amber-300 bg-white/10 px-2.5 py-0.5 rounded-lg border border-white/10">
                        {{ $user->member_number ?? 'AKS-' . date('Ym') . '-0001' }}
                    </span>
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ route('member.card') }}" class="px-5 py-2.5 bg-white/10 hover:bg-white/20 border border-white/20 text-white font-bold text-xs rounded-xl backdrop-blur transition-all flex items-center gap-2 shadow-sm">
                    <i data-lucide="id-card" class="w-4 h-4 text-brand-300"></i>
                    <span>Buka Kartu Digital</span>
                </a>
                <a href="{{ route('catalog.index') }}" class="px-5 py-2.5 bg-gradient-to-r from-brand-600 to-teal-700 hover:from-brand-700 hover:to-teal-800 text-white font-bold text-xs rounded-xl shadow-glow transition-all flex items-center gap-2">
                    <i data-lucide="search" class="w-4 h-4"></i>
                    <span>Cari Buku Baru</span>
                </a>
            </div>

        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-8">
    
    <!-- KPI Metrics Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        
        <div class="p-6 bg-white rounded-3xl border border-slate-200/80 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Pinjaman Aktif</p>
                <p class="font-display text-2xl font-black text-slate-900 mt-1">
                    {{ $activeLoans->count() }} <span class="text-xs font-normal text-slate-400">/ maks {{ $maxBorrowLimit }}</span>
                </p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-brand-50 text-brand-600 flex items-center justify-center font-bold">
                <i data-lucide="book-marked" class="w-6 h-6"></i>
            </div>
        </div>

        <div class="p-6 bg-white rounded-3xl border border-slate-200/80 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Buku Selesai Dibaca</p>
                <p class="font-display text-2xl font-black text-emerald-600 mt-1">{{ $completedCount }}</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold">
                <i data-lucide="check-circle-2" class="w-6 h-6"></i>
            </div>
        </div>

        <div class="p-6 bg-white rounded-3xl border border-slate-200/80 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Rak Buku Pribadi</p>
                <p class="font-display text-2xl font-black text-indigo-600 mt-1">
                    {{ array_sum($shelvesCounts) }} <span class="text-xs font-normal text-slate-400">buku</span>
                </p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold">
                <i data-lucide="library" class="w-6 h-6"></i>
            </div>
        </div>

        <div class="p-6 bg-white rounded-3xl border border-slate-200/80 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Tunggakan Denda</p>
                <p class="font-display text-2xl font-black {{ $totalFines > 0 ? 'text-rose-600' : 'text-slate-900' }} mt-1">
                    Rp {{ number_format($totalFines, 0, ',', '.') }}
                </p>
            </div>
            <div class="w-12 h-12 rounded-2xl {{ $totalFines > 0 ? 'bg-rose-50 text-rose-600' : 'bg-slate-50 text-slate-400' }} flex items-center justify-center font-bold">
                <i data-lucide="receipt" class="w-6 h-6"></i>
            </div>
        </div>

    </div>

    <!-- Reading Challenge 2026 & Custom Shelf Ribbon -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Reading Challenge Bento Card -->
        <div class="lg:col-span-2 p-6 sm:p-8 bg-gradient-to-br from-navy-950 via-slate-900 to-brand-950 rounded-3xl text-white shadow-xl relative overflow-hidden flex flex-col justify-between" x-data="{ editTarget: false }">
            <div class="absolute -right-20 -bottom-20 w-64 h-64 bg-brand-500/15 rounded-full blur-3xl pointer-events-none"></div>
            
            <div class="relative z-10 space-y-3">
                <div class="flex items-center justify-between">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-brand-500/20 text-brand-300 border border-brand-500/30 text-xs font-bold uppercase tracking-wider">
                        <i data-lucide="award" class="w-3.5 h-3.5"></i>
                        <span>Tantangan Membaca {{ $challenge->year }}</span>
                    </span>
                    <button @click="editTarget = !editTarget" class="text-xs font-semibold text-slate-300 hover:text-white underline">
                        Ubah Target
                    </button>
                </div>

                <div class="flex flex-col sm:flex-row sm:items-baseline gap-2">
                    <h3 class="font-display text-2xl sm:text-3xl font-black text-white">
                        {{ $completedCount }} dari {{ $challenge->target_books }} Buku
                    </h3>
                    @php
                        $percentage = min(100, round(($completedCount / max(1, $challenge->target_books)) * 100));
                    @endphp
                    <span class="text-xs font-bold text-teal-300">({{ $percentage }}% Tercapai)</span>
                </div>

                <!-- Progress Bar -->
                <div class="w-full h-3 bg-white/10 rounded-full overflow-hidden p-0.5 border border-white/10">
                    <div class="h-full bg-gradient-to-r from-teal-400 to-emerald-400 rounded-full transition-all duration-700" style="width: {{ $percentage }}%"></div>
                </div>

                <p class="text-xs text-slate-300 leading-relaxed">
                    Setiap e-book yang Anda tandai selesai atau buku fisik yang selesai dikembalikan akan otomatis dihitung ke dalam pencapaian tantangan literasi Anda!
                </p>

                <!-- Target Edit Form -->
                <form x-show="editTarget" x-cloak method="POST" action="{{ route('member.challenge.save') }}" class="pt-3 flex items-center gap-2">
                    @csrf
                    <input type="number" name="target_books" value="{{ $challenge->target_books }}" min="1" max="365" class="w-24 px-3 py-1.5 bg-white/10 border border-white/20 rounded-xl text-xs text-white focus:outline-none focus:ring-2 focus:ring-brand-400">
                    <button type="submit" class="px-4 py-1.5 bg-brand-500 hover:bg-brand-600 text-white font-bold text-xs rounded-xl shadow-glow">Simpan Target</button>
                    <button type="button" @click="editTarget = false" class="px-3 py-1.5 text-xs text-slate-300 hover:text-white">Batal</button>
                </form>
            </div>
        </div>

        <!-- Custom Shelf Quick Links -->
        <div class="p-6 sm:p-8 bg-white rounded-3xl border border-slate-200/80 shadow-sm flex flex-col justify-between space-y-4">
            <div>
                <div class="flex items-center gap-2 text-brand-600 mb-2">
                    <i data-lucide="bookmark-plus" class="w-5 h-5"></i>
                    <h3 class="font-display font-bold text-slate-900 text-base">Rak Buku Pribadi</h3>
                </div>
                <p class="text-xs text-slate-500 leading-relaxed">
                    Kelola koleksi bahan pustaka yang ingin Anda baca, sedang dibaca, atau sudah tamat.
                </p>
            </div>

            <div class="space-y-2">
                <a href="{{ route('member.shelves', ['type' => 'want_to_read']) }}" class="flex items-center justify-between p-3 rounded-2xl bg-slate-50 hover:bg-brand-50/60 transition-colors text-xs font-bold text-slate-700 hover:text-brand-800">
                    <span class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                        <span>Ingin Dibaca</span>
                    </span>
                    <span class="px-2 py-0.5 rounded-full bg-white border border-slate-200 text-[10px]">{{ $shelvesCounts['want_to_read'] }}</span>
                </a>
                <a href="{{ route('member.shelves', ['type' => 'currently_reading']) }}" class="flex items-center justify-between p-3 rounded-2xl bg-slate-50 hover:bg-amber-50/60 transition-colors text-xs font-bold text-slate-700 hover:text-amber-800">
                    <span class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                        <span>Sedang Dibaca</span>
                    </span>
                    <span class="px-2 py-0.5 rounded-full bg-white border border-slate-200 text-[10px]">{{ $shelvesCounts['currently_reading'] }}</span>
                </a>
                <a href="{{ route('member.shelves', ['type' => 'already_read']) }}" class="flex items-center justify-between p-3 rounded-2xl bg-slate-50 hover:bg-emerald-50/60 transition-colors text-xs font-bold text-slate-700 hover:text-emerald-800">
                    <span class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        <span>Selesai Dibaca</span>
                    </span>
                    <span class="px-2 py-0.5 rounded-full bg-white border border-slate-200 text-[10px]">{{ $shelvesCounts['already_read'] }}</span>
                </a>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-8 shadow-sm space-y-6">
        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
            <div>
                <h2 class="font-display text-lg font-bold text-slate-900 flex items-center gap-2">
                    <i data-lucide="clock" class="w-5 h-5 text-brand-600"></i>
                    <span>Buku yang Sedang Dipinjam & Status Pengajuan</span>
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">Perhatikan tanggal tenggat pengembalian buku fisik di perpustakaan</p>
            </div>
            <a href="{{ route('member.loans') }}" class="text-xs font-bold text-brand-700 hover:underline">
                Lihat Semua Sirkulasi &rarr;
            </a>
        </div>

        @if($activeLoans->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($activeLoans as $loan)
            <div class="p-5 rounded-2xl border border-slate-200/80 bg-slate-50/60 hover:bg-slate-50 transition-colors flex gap-4">
                <div class="w-20 shrink-0 aspect-[3/4] rounded-xl bg-slate-200 overflow-hidden shadow-xs">
                    <img src="{{ $loan->book->cover_image ?? 'https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?auto=format&fit=crop&w=400&q=80' }}" 
                         alt="{{ $loan->book->title }}" 
                         class="w-full h-full object-cover">
                </div>

                <div class="flex-1 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between gap-2">
                            <span class="text-[10px] font-mono text-slate-500 font-bold">{{ $loan->loan_code }}</span>
                            <span class="px-2.5 py-0.5 text-[10px] font-bold rounded-full border {{ $loan->status_badge }}">
                                {{ $loan->status_label }}
                            </span>
                        </div>
                        <h4 class="font-display font-bold text-sm text-slate-900 mt-1 line-clamp-1">
                            <a href="{{ route('catalog.show', $loan->book->slug) }}" class="hover:text-brand-700">{{ $loan->book->title }}</a>
                        </h4>
                        <p class="text-xs text-slate-500 truncate">{{ $loan->book->author }}</p>
                    </div>

                    <div class="pt-2.5 border-t border-slate-200/80 mt-2 text-[11px] space-y-1">
                        <div class="flex justify-between text-slate-600">
                            <span>Tenggat Pengembalian:</span>
                            <span class="font-bold {{ $loan->status === 'overdue' ? 'text-rose-600' : 'text-slate-900' }}">
                                {{ $loan->due_date ? $loan->due_date->translatedFormat('d F Y') : '-' }}
                            </span>
                        </div>
                        @if($loan->fine_amount > 0 && !$loan->fine_paid)
                        <div class="flex items-center justify-between text-rose-600 font-bold pt-1">
                            <span>Denda: Rp {{ number_format($loan->fine_amount, 0, ',', '.') }}</span>
                            <form action="{{ route('member.loan.paySimulated', $loan->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="payment_method" value="qris_simulasi">
                                <button type="submit" class="px-2.5 py-1 bg-gradient-to-r from-emerald-600 to-teal-700 hover:from-emerald-700 hover:to-teal-800 text-white rounded-lg text-[10px] font-bold shadow-glow flex items-center gap-1">
                                    <i data-lucide="qr-code" class="w-3 h-3"></i>
                                    <span>Bayar QRIS (Simulasi)</span>
                                </button>
                            </form>
                        </div>
                        @elseif($loan->fine_amount > 0 && $loan->fine_paid)
                        <div class="flex items-center justify-between text-emerald-600 text-[10px] font-bold pt-1">
                            <span>Denda Lunas ({{ strtoupper($loan->payment_method ?? 'TUNAI') }})</span>
                            <span class="font-mono text-[9px] text-slate-400">{{ $loan->payment_reference }}</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-10 text-slate-400 text-xs space-y-2">
            <i data-lucide="book-check" class="w-10 h-10 mx-auto text-slate-300"></i>
            <p class="font-semibold text-slate-700 text-sm">Tidak ada pinjaman buku yang aktif saat ini.</p>
            <p class="text-slate-400">Jelajahi koleksi kami dan ajukan peminjaman buku favorit Anda!</p>
            <div class="pt-2">
                <a href="{{ route('catalog.index') }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs rounded-xl shadow-xs transition-colors">
                    <span>Eksplorasi Katalog</span>
                </a>
            </div>
        </div>
        @endif
    </div>

    <!-- Favorite Books Gallery -->
    @if($bookmarks->count() > 0)
    <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-8 shadow-sm space-y-6">
        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
            <div>
                <h3 class="font-display text-lg font-bold text-slate-900 flex items-center gap-2">
                    <i data-lucide="bookmark" class="w-5 h-5 text-indigo-600"></i>
                    <span>Koleksi Favorit yang Disimpan</span>
                </h3>
            </div>
            <a href="{{ route('member.bookmarks') }}" class="text-xs font-bold text-brand-700 hover:underline">
                Lihat Semua &rarr;
            </a>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            @foreach($bookmarks as $bBook)
            <a href="{{ route('catalog.show', $bBook->slug) }}" class="group block p-3.5 bg-slate-50 hover:bg-slate-100 rounded-2xl transition-all">
                <div class="aspect-[3/4] rounded-xl bg-slate-200 overflow-hidden mb-2.5 shadow-xs">
                    <img src="{{ $bBook->cover_image ?? 'https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?auto=format&fit=crop&w=400&q=80' }}" 
                         alt="{{ $bBook->title }}" 
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                </div>
                <h4 class="text-xs font-bold text-slate-900 group-hover:text-brand-700 transition-colors line-clamp-1">{{ $bBook->title }}</h4>
                <p class="text-[10px] text-slate-500 truncate">{{ $bBook->author }}</p>
            </a>
            @endforeach
        </div>
    </div>
    @endif

</div>
@endsection
