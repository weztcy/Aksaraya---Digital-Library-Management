@extends('layouts.app')

@section('title', 'Riwayat Peminjaman Buku - Aksaraya')

@section('content')
<!-- Header Banner -->
<div class="bg-gradient-to-r from-navy-950 via-slate-900 to-brand-950 text-white py-12 border-b border-slate-800 relative overflow-hidden">
    <div class="absolute -right-20 -top-20 w-80 h-80 bg-brand-500/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="max-w-3xl space-y-2">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-brand-500/20 text-brand-300 border border-brand-500/30 text-xs font-bold uppercase tracking-wider">
                Sirkulasi Mandiri
            </span>
            <h1 class="font-display text-3xl sm:text-4xl font-black tracking-tight text-white">Daftar Peminjaman Saya</h1>
            <p class="text-xs sm:text-sm text-slate-300">
                Pantau seluruh pengajuan pinjaman, buku yang sedang dibawa, dan riwayat buku yang telah dikembalikan.
            </p>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-6">
    
    <!-- Filter Status Tabs -->
    <div class="flex flex-wrap items-center gap-2 bg-white p-2.5 rounded-3xl border border-slate-200/80 shadow-sm text-xs font-bold">
        <a href="{{ route('member.loans') }}" class="px-4 py-2 rounded-2xl transition-all {{ empty($status) ? 'bg-brand-600 text-white shadow-glow' : 'text-slate-600 hover:bg-slate-100' }}">
            Semua Riwayat
        </a>
        <a href="{{ route('member.loans', ['status' => 'pending']) }}" class="px-4 py-2 rounded-2xl transition-all {{ $status === 'pending' ? 'bg-amber-500 text-slate-950 shadow-sm' : 'text-slate-600 hover:bg-slate-100' }}">
            Menunggu Konfirmasi
        </a>
        <a href="{{ route('member.loans', ['status' => 'approved']) }}" class="px-4 py-2 rounded-2xl transition-all {{ $status === 'approved' ? 'bg-indigo-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100' }}">
            Siap Diambil
        </a>
        <a href="{{ route('member.loans', ['status' => 'borrowed']) }}" class="px-4 py-2 rounded-2xl transition-all {{ $status === 'borrowed' ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100' }}">
            Sedang Dipinjam
        </a>
        <a href="{{ route('member.loans', ['status' => 'overdue']) }}" class="px-4 py-2 rounded-2xl transition-all {{ $status === 'overdue' ? 'bg-rose-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100' }}">
            Terlambat
        </a>
        <a href="{{ route('member.loans', ['status' => 'returned']) }}" class="px-4 py-2 rounded-2xl transition-all {{ $status === 'returned' ? 'bg-emerald-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100' }}">
            Sudah Dikembalikan
        </a>
    </div>

    <!-- Loan List Table -->
    @if($loans->count() > 0)
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 font-bold uppercase tracking-wider text-[10px]">
                        <th class="p-4 pl-6">Kode Transaksi</th>
                        <th class="p-4">Koleksi Buku</th>
                        <th class="p-4">Tgl Pinjam</th>
                        <th class="p-4">Tenggat Waktu</th>
                        <th class="p-4">Tgl Kembali</th>
                        <th class="p-4">Denda</th>
                        <th class="p-4">Status</th>
                        <th class="p-4 pr-6 text-right">Tindakan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @foreach($loans as $loan)
                    <tr class="hover:bg-slate-50/60 transition-colors">
                        <td class="p-4 pl-6 font-mono font-bold text-slate-900">
                            {{ $loan->loan_code }}
                        </td>
                        <td class="p-4">
                            <div class="flex items-center gap-3">
                                <img src="{{ $loan->book->cover_image ?? 'https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?auto=format&fit=crop&w=200&q=80' }}" 
                                     alt="" class="w-10 h-14 object-cover rounded-xl shadow-xs shrink-0">
                                <div>
                                    <a href="{{ route('catalog.show', $loan->book->slug) }}" class="font-display font-bold text-slate-900 hover:text-brand-700 line-clamp-1">
                                        {{ $loan->book->title }}
                                    </a>
                                    <p class="text-[11px] text-slate-400">{{ $loan->book->author }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="p-4 font-medium">{{ $loan->loan_date ? $loan->loan_date->format('d/m/Y') : '-' }}</td>
                        <td class="p-4 font-medium {{ $loan->status === 'overdue' ? 'text-rose-600 font-bold' : '' }}">
                            {{ $loan->due_date ? $loan->due_date->format('d/m/Y') : '-' }}
                        </td>
                        <td class="p-4 font-medium">{{ $loan->return_date ? $loan->return_date->format('d/m/Y') : '-' }}</td>
                        <td class="p-4">
                            @if($loan->fine_amount > 0)
                                <div class="font-bold {{ $loan->fine_paid ? 'text-emerald-600' : 'text-rose-600' }}">
                                    Rp {{ number_format($loan->fine_amount, 0, ',', '.') }}
                                    <span class="block text-[10px] font-normal {{ $loan->fine_paid ? 'text-emerald-500' : 'text-rose-400' }}">
                                        {{ $loan->fine_paid ? '(Lunas)' : '(Belum Lunas)' }}
                                    </span>
                                </div>
                            @else
                                <span class="text-slate-400">-</span>
                            @endif
                        </td>
                        <td class="p-4">
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold border {{ $loan->status_badge }}">
                                {{ $loan->status_label }}
                            </span>
                        </td>
                        <td class="p-4 pr-6 text-right">
                            @if($loan->status === 'pending')
                            <form action="{{ route('member.loan.cancel', $loan->id) }}" method="POST" onsubmit="return confirm('Batalkan pengajuan pinjaman ini?')">
                                @csrf
                                <button type="submit" class="px-3 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 font-bold rounded-xl text-[11px] transition-colors">
                                    Batalkan
                                </button>
                            </form>
                            @else
                            <a href="{{ route('catalog.show', $loan->book->slug) }}" class="text-brand-700 hover:text-brand-900 font-bold text-xs">
                                Lihat Buku
                            </a>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-100">
            {{ $loans->links() }}
        </div>
    </div>
    @else
    <div class="bg-white rounded-3xl p-14 text-center border border-slate-200/80 shadow-sm space-y-3">
        <i data-lucide="inbox" class="w-12 h-12 mx-auto text-slate-300"></i>
        <h3 class="font-display font-bold text-slate-900 text-base">Tidak Ada Transaksi</h3>
        <p class="text-xs text-slate-500 max-w-sm mx-auto">
            Belum ada catatan peminjaman untuk status yang dipilih.
        </p>
    </div>
    @endif

</div>
@endsection
