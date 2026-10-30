@extends('layouts.admin')

@section('title', 'Detail Anggota: ' . $user->name . ' - Aksaraya')
@section('page-title', 'Profil & Riwayat Anggota')

@section('content')
<div class="space-y-6 mt-6">
    
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.members.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-500 hover:text-slate-900 transition-colors">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            <span>Kembali ke Daftar Anggota</span>
        </a>
    </div>

    <!-- Member Profile & History Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Left: Member Card Snapshot -->
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 space-y-5">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-brand-600 to-teal-700 text-white flex items-center justify-center font-black text-xl shadow-glow">
                    {{ strtoupper(substr($user->name, 0, 2)) }}
                </div>
                <div>
                    <h3 class="font-display font-bold text-slate-900 text-base">{{ $user->name }}</h3>
                    <p class="text-xs text-brand-700 font-mono font-bold">{{ $user->member_number ?? 'Belum ada nomor' }}</p>
                    <span class="inline-block mt-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold {{ $user->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                        {{ $user->is_active ? 'Akun Aktif' : 'Akun Dinonaktifkan' }}
                    </span>
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 space-y-2.5 text-xs text-slate-600">
                <div class="flex justify-between">
                    <span class="text-slate-400">Email:</span>
                    <span class="font-bold text-slate-900">{{ $user->email }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-400">No. HP / WA:</span>
                    <span class="font-bold text-slate-900">{{ $user->phone ?? '-' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-400">Domisili:</span>
                    <span class="font-bold text-slate-900 text-right max-w-[180px]">{{ $user->address ?? '-' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-400">Tanggal Daftar:</span>
                    <span class="font-bold text-slate-900">{{ $user->created_at->translatedFormat('d F Y') }}</span>
                </div>
            </div>

            <div class="pt-2 border-t border-slate-100">
                <form action="{{ route('admin.members.toggleStatus', $user->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full py-2.5 px-4 rounded-xl text-xs font-bold border transition-colors {{ $user->is_active ? 'bg-rose-50 text-rose-700 border-rose-200 hover:bg-rose-100' : 'bg-emerald-50 text-emerald-700 border-emerald-200 hover:bg-emerald-100' }}">
                        {{ $user->is_active ? 'Nonaktifkan Akun Ini' : 'Aktifkan Kembali Akun' }}
                    </button>
                </form>
            </div>
        </div>

        <!-- Right 2 Cols: Loans History Table -->
        <div class="lg:col-span-2 bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 sm:p-8 space-y-5">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h3 class="font-display font-bold text-slate-900 text-base">Riwayat Peminjaman Buku</h3>
                <span class="text-xs text-slate-500 font-semibold">Tunggakan Denda: <strong class="text-rose-600">Rp {{ number_format($totalFines, 0, ',', '.') }}</strong></span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="text-[10px] uppercase font-bold text-slate-400 border-b border-slate-100 pb-2">
                            <th class="pb-2.5">Kode</th>
                            <th class="pb-2.5">Buku</th>
                            <th class="pb-2.5">Tgl Pinjam</th>
                            <th class="pb-2.5">Tenggat</th>
                            <th class="pb-2.5">Status</th>
                            <th class="pb-2.5">Denda</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($loans as $loan)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="py-3 font-mono font-bold text-slate-900">{{ $loan->loan_code }}</td>
                            <td class="py-3">
                                <span class="font-display font-bold text-slate-900 line-clamp-1 max-w-[200px]">{{ $loan->book->title }}</span>
                            </td>
                            <td class="py-3 font-medium">{{ $loan->loan_date ? $loan->loan_date->format('d/m/Y') : '-' }}</td>
                            <td class="py-3 font-medium">{{ $loan->due_date ? $loan->due_date->format('d/m/Y') : '-' }}</td>
                            <td class="py-3">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold border {{ $loan->status_badge }}">
                                    {{ $loan->status_label }}
                                </span>
                            </td>
                            <td class="py-3 font-bold font-mono {{ $loan->fine_paid ? 'text-emerald-600' : 'text-rose-600' }}">
                                {{ $loan->fine_amount > 0 ? 'Rp ' . number_format($loan->fine_amount, 0, ',', '.') : '-' }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-400 text-xs">
                                Anggota ini belum memiliki transaksi peminjaman.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="pt-2">
                {{ $loans->links() }}
            </div>
        </div>

    </div>

</div>
@endsection
