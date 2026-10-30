@extends('layouts.admin')

@section('title', 'Laporan & Rekapitulasi - Aksaraya')
@section('page-title', 'Laporan Sirkulasi & Keuangan')

@section('content')
<div class="space-y-6 mt-6">
    
    <!-- Filter Card -->
    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/80 shadow-sm space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-100 pb-4">
            <div>
                <h3 class="font-display font-bold text-slate-900 text-base">Filter Periode & Status Laporan</h3>
                <p class="text-xs text-slate-500 mt-0.5">Tentukan parameter rentang tanggal untuk merekap transaksi peminjaman dan denda</p>
            </div>

            <a href="{{ route('admin.reports.index', array_merge(request()->all(), ['print' => 1])) }}" target="_blank" class="px-5 py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs rounded-xl shadow-md flex items-center gap-2 transition-all shrink-0">
                <i data-lucide="printer" class="w-4 h-4"></i>
                <span>Cetak Lembar Resmi</span>
            </a>
        </div>

        <form method="GET" action="{{ route('admin.reports.index') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-4">
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Mulai Tanggal</label>
                <input type="date" name="start_date" value="{{ $startDate }}" class="w-full text-xs p-2.5 bg-slate-50 border border-slate-300 rounded-xl focus:ring-2 focus:ring-brand-500 font-medium">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Sampai Tanggal</label>
                <input type="date" name="end_date" value="{{ $endDate }}" class="w-full text-xs p-2.5 bg-slate-50 border border-slate-300 rounded-xl focus:ring-2 focus:ring-brand-500 font-medium">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Status Peminjaman</label>
                <select name="status" class="w-full text-xs p-2.5 bg-slate-50 border border-slate-300 rounded-xl focus:ring-2 focus:ring-brand-500 font-medium">
                    <option value="">Semua Status</option>
                    <option value="returned" {{ $status === 'returned' ? 'selected' : '' }}>Selesai Dikembalikan</option>
                    <option value="borrowed" {{ $status === 'borrowed' ? 'selected' : '' }}>Sedang Dipinjam</option>
                    <option value="overdue" {{ $status === 'overdue' ? 'selected' : '' }}>Terlambat</option>
                    <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Menunggu Konfirmasi</option>
                </select>
            </div>

            <div class="flex items-end gap-2">
                <button type="submit" class="flex-1 py-2.5 px-4 bg-gradient-to-r from-brand-600 to-teal-700 hover:from-brand-700 hover:to-teal-800 text-white font-bold text-xs rounded-xl shadow-glow transition-all flex items-center justify-center gap-1.5">
                    <i data-lucide="filter" class="w-4 h-4"></i>
                    <span>Tampilkan</span>
                </button>
                <a href="{{ route('admin.reports.index') }}" class="py-2.5 px-3.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition-colors">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Summary Metrics -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="p-5 bg-white rounded-3xl border border-slate-200/80 shadow-sm">
            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 font-mono">Total Transaksi</span>
            <p class="font-display text-2xl font-black text-slate-900 mt-1">{{ number_format($summary['total_transactions']) }}</p>
        </div>

        <div class="p-5 bg-white rounded-3xl border border-slate-200/80 shadow-sm">
            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 font-mono">Buku Dikembalikan</span>
            <p class="font-display text-2xl font-black text-emerald-600 mt-1">{{ number_format($summary['returned_count']) }}</p>
        </div>

        <div class="p-5 bg-white rounded-3xl border border-slate-200/80 shadow-sm">
            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 font-mono">Total Denda Terhitung</span>
            <p class="font-display text-2xl font-black text-slate-900 mt-1 font-mono">Rp {{ number_format($summary['total_fines'], 0, ',', '.') }}</p>
        </div>

        <div class="p-5 bg-white rounded-3xl border border-slate-200/80 shadow-sm">
            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 font-mono">Denda Terbayar</span>
            <p class="font-display text-2xl font-black text-emerald-600 mt-1 font-mono">Rp {{ number_format($summary['paid_fines'], 0, ',', '.') }}</p>
        </div>
    </div>

    <!-- Report Table -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 font-bold uppercase tracking-wider text-[10px]">
                        <th class="p-4 pl-6">Kode Transaksi</th>
                        <th class="p-4">Nama Anggota</th>
                        <th class="p-4">Judul Koleksi</th>
                        <th class="p-4">Tgl Pinjam</th>
                        <th class="p-4">Tenggat</th>
                        <th class="p-4">Tgl Kembali</th>
                        <th class="p-4">Status</th>
                        <th class="p-4 pr-6 text-right">Denda (Rp)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($loans as $loan)
                    <tr class="hover:bg-slate-50/60 transition-colors">
                        <td class="p-4 pl-6 font-mono font-bold text-slate-900">{{ $loan->loan_code }}</td>
                        <td class="p-4 font-bold text-slate-800">{{ $loan->user->name }}</td>
                        <td class="p-4 font-medium max-w-[200px] truncate">{{ $loan->book->title }}</td>
                        <td class="p-4">{{ $loan->loan_date ? $loan->loan_date->format('d/m/Y') : '-' }}</td>
                        <td class="p-4">{{ $loan->due_date ? $loan->due_date->format('d/m/Y') : '-' }}</td>
                        <td class="p-4">{{ $loan->return_date ? $loan->return_date->format('d/m/Y') : '-' }}</td>
                        <td class="p-4">
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold border {{ $loan->status_badge }}">
                                {{ $loan->status_label }}
                            </span>
                        </td>
                        <td class="p-4 pr-6 text-right font-mono font-bold {{ $loan->fine_paid ? 'text-emerald-600' : ($loan->fine_amount > 0 ? 'text-rose-600' : 'text-slate-400') }}">
                            {{ $loan->fine_amount > 0 ? number_format($loan->fine_amount, 0, ',', '.') : '-' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="p-10 text-center text-slate-400 text-xs">
                            Tidak ada transaksi sirkulasi pada parameter tanggal yang dipilih.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-100">
            {{ $loans->links() }}
        </div>
    </div>

</div>
@endsection
