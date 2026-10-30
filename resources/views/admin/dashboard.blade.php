@extends('layouts.admin')

@section('title', 'Dashboard Petugas & Admin - Aksaraya')
@section('page-title', 'Pusat Kendali Perpustakaan')

@section('content')
<div class="space-y-8 mt-6">
    
    <!-- Top KPI Metric Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        
        <!-- Total Books -->
        <div class="p-6 bg-white rounded-3xl border border-slate-200/80 shadow-sm flex items-center justify-between hover:shadow-md transition-shadow">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400 font-mono">Judul Buku</p>
                <h3 class="font-display text-3xl font-black text-slate-900 mt-1">{{ number_format($totalBooks) }}</h3>
                <p class="text-[11px] text-slate-400 mt-1 font-medium">{{ number_format($totalCopies) }} total eksemplar</p>
            </div>
            <div class="w-14 h-14 rounded-2xl bg-brand-50 text-brand-600 flex items-center justify-center font-bold shadow-inner">
                <i data-lucide="book-copy" class="w-7 h-7"></i>
            </div>
        </div>

        <!-- Active Loans -->
        <div class="p-6 bg-white rounded-3xl border border-slate-200/80 shadow-sm flex items-center justify-between hover:shadow-md transition-shadow">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400 font-mono">Sedang Dipinjam</p>
                <h3 class="font-display text-3xl font-black text-blue-600 mt-1">{{ number_format($activeBorrowedCount) }}</h3>
                <p class="text-[11px] text-amber-600 font-bold mt-1">{{ $pendingLoansCount }} permohonan baru</p>
            </div>
            <div class="w-14 h-14 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold shadow-inner">
                <i data-lucide="arrow-left-right" class="w-7 h-7"></i>
            </div>
        </div>

        <!-- Overdue -->
        <div class="p-6 bg-white rounded-3xl border border-slate-200/80 shadow-sm flex items-center justify-between hover:shadow-md transition-shadow">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400 font-mono">Peminjaman Telat</p>
                <h3 class="font-display text-3xl font-black {{ $overdueLoansCount > 0 ? 'text-rose-600' : 'text-slate-900' }} mt-1">
                    {{ number_format($overdueLoansCount) }}
                </h3>
                <p class="text-[11px] text-slate-400 mt-1 font-mono">Denda: Rp {{ number_format($unpaidFines, 0, ',', '.') }}</p>
            </div>
            <div class="w-14 h-14 rounded-2xl {{ $overdueLoansCount > 0 ? 'bg-rose-50 text-rose-600' : 'bg-slate-50 text-slate-400' }} flex items-center justify-center font-bold shadow-inner">
                <i data-lucide="alert-triangle" class="w-7 h-7"></i>
            </div>
        </div>

        <!-- Registered Members -->
        <div class="p-6 bg-white rounded-3xl border border-slate-200/80 shadow-sm flex items-center justify-between hover:shadow-md transition-shadow">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400 font-mono">Anggota Terdaftar</p>
                <h3 class="font-display text-3xl font-black text-emerald-600 mt-1">{{ number_format($totalMembers) }}</h3>
                <p class="text-[11px] text-teal-600 font-semibold mt-1">{{ $totalEbooks }} judul e-book digital</p>
            </div>
            <div class="w-14 h-14 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold shadow-inner">
                <i data-lucide="users" class="w-7 h-7"></i>
            </div>
        </div>

    </div>

    <!-- Quick Action Ribbon -->
    <div class="p-7 bg-gradient-to-r from-navy-950 via-slate-900 to-brand-950 rounded-3xl text-white shadow-xl flex flex-col sm:flex-row sm:items-center justify-between gap-5 relative overflow-hidden">
        <div class="absolute -right-20 -bottom-20 w-64 h-64 bg-brand-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="relative z-10 space-y-1">
            <h3 class="font-display text-lg font-bold text-white">Pusat Layanan Sirkulasi Petugas</h3>
            <p class="text-xs text-brand-200 font-normal">Catat peminjaman walk-in atau perbarui koleksi buku perpustakaan</p>
        </div>
        <div class="relative z-10 flex flex-wrap items-center gap-3">
            <a href="{{ route('admin.loans.create') }}" class="px-4 py-2.5 bg-gradient-to-r from-brand-600 to-teal-700 hover:from-brand-700 hover:to-teal-800 text-white rounded-xl text-xs font-bold shadow-glow flex items-center gap-2 transition-all">
                <i data-lucide="plus-circle" class="w-4 h-4"></i>
                <span>Pinjam di Meja</span>
            </a>
            <a href="{{ route('admin.books.create') }}" class="px-4 py-2.5 bg-white/10 hover:bg-white/20 text-white border border-white/20 rounded-xl text-xs font-bold transition-all flex items-center gap-2 backdrop-blur">
                <i data-lucide="book-plus" class="w-4 h-4 text-brand-300"></i>
                <span>Tambah Buku</span>
            </a>
            <a href="{{ route('admin.loans.index') }}" class="px-4 py-2.5 bg-white/10 hover:bg-white/20 text-white border border-white/20 rounded-xl text-xs font-bold transition-all flex items-center gap-2 backdrop-blur">
                <i data-lucide="check-square" class="w-4 h-4 text-emerald-300"></i>
                <span>Verifikasi Sirkulasi</span>
            </a>
        </div>
    </div>

    <!-- 2 Columns: Activity Table & Top Books -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Left 2 Cols: Recent Circulation Activities -->
        <div class="lg:col-span-2 bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 sm:p-8 space-y-6">
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <div>
                    <h3 class="font-display font-bold text-slate-900 text-base">Aktivitas Peminjaman Terbaru</h3>
                    <p class="text-xs text-slate-400 mt-0.5">Transaksi peminjaman dan pengembalian terakhir</p>
                </div>
                <a href="{{ route('admin.loans.index') }}" class="text-xs font-bold text-brand-700 hover:underline">
                    Semua Sirkulasi &rarr;
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="text-[10px] uppercase font-bold text-slate-400 border-b border-slate-100 pb-2">
                            <th class="pb-3">Kode</th>
                            <th class="pb-3">Peminjam</th>
                            <th class="pb-3">Koleksi Buku</th>
                            <th class="pb-3">Tenggat</th>
                            <th class="pb-3">Status</th>
                            <th class="pb-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($recentLoans as $loan)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="py-3 font-mono font-bold text-slate-900">{{ $loan->loan_code }}</td>
                            <td class="py-3">
                                <span class="font-bold text-slate-800 block">{{ $loan->user->name }}</span>
                                <span class="text-[10px] text-slate-400 font-mono">{{ $loan->user->member_number }}</span>
                            </td>
                            <td class="py-3">
                                <span class="font-bold text-slate-900 line-clamp-1 max-w-[180px]">{{ $loan->book->title }}</span>
                            </td>
                            <td class="py-3 text-slate-600 font-medium">{{ $loan->due_date ? $loan->due_date->format('d/m/Y') : '-' }}</td>
                            <td class="py-3">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold border {{ $loan->status_badge }}">
                                    {{ $loan->status_label }}
                                </span>
                            </td>
                            <td class="py-3 text-right">
                                <a href="{{ route('admin.loans.index', ['q' => $loan->loan_code]) }}" class="text-brand-700 font-bold hover:underline">
                                    Kelola &rarr;
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Right Col: Top Borrowed & Fines Recap -->
        <div class="lg:col-span-1 bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 sm:p-8 space-y-6">
            <div class="border-b border-slate-100 pb-4">
                <h3 class="font-display font-bold text-slate-900 text-base">Buku Paling Populer</h3>
                <p class="text-xs text-slate-400 mt-0.5">Koleksi dengan frekuensi pinjam tertinggi</p>
            </div>

            <div class="space-y-4">
                @foreach($topBooks as $idx => $b)
                <div class="flex items-center gap-3">
                    <span class="w-6 h-6 rounded-full bg-slate-100 text-slate-700 font-black text-xs flex items-center justify-center shrink-0 font-mono">
                        {{ $idx + 1 }}
                    </span>
                    <img src="{{ $b->cover_image ?? 'https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?auto=format&fit=crop&w=150&q=80' }}" 
                         alt="" class="w-9 h-12 object-cover rounded-lg shadow-xs shrink-0">
                    <div class="flex-1 min-w-0">
                        <h4 class="text-xs font-bold text-slate-900 truncate">{{ $b->title }}</h4>
                        <p class="text-[11px] text-slate-400 truncate">{{ $b->author }}</p>
                    </div>
                    <span class="px-2.5 py-1 text-[10px] font-bold rounded-lg bg-brand-50 text-brand-700 shrink-0">
                        {{ $b->loans_count }}x Pinjam
                    </span>
                </div>
                @endforeach
            </div>

            <div class="pt-4 border-t border-slate-100">
                <div class="p-4 bg-slate-50 rounded-2xl space-y-2.5 text-xs">
                    <div class="flex justify-between text-slate-600">
                        <span>Total Denda Lunas:</span>
                        <span class="font-bold text-emerald-600">Rp {{ number_format($paidFines, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-slate-600">
                        <span>Tunggakan Denda:</span>
                        <span class="font-bold text-rose-600">Rp {{ number_format($unpaidFines, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection
