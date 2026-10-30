@extends('layouts.admin')

@section('title', 'Manajemen Anggota - Aksaraya')
@section('page-title', 'Kelola Pengguna & Anggota Perpustakaan')

@section('content')
<div class="space-y-6 mt-6">
    
    <!-- Filter & Search Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-4 rounded-3xl border border-slate-200/80 shadow-sm">
        <form method="GET" action="{{ route('admin.members.index') }}" class="flex flex-wrap items-center gap-3 flex-1">
            <div class="relative min-w-[220px] flex-1">
                <input type="text" name="q" value="{{ $search }}" placeholder="Cari nama, email, nomor anggota, no HP..." 
                    class="w-full pl-9 pr-3 py-2 text-xs bg-slate-50 border border-slate-300 rounded-xl focus:ring-2 focus:ring-brand-500 font-medium">
                <i data-lucide="search" class="w-4 h-4 text-slate-400 absolute left-3 top-2.5"></i>
            </div>

            <select name="role" class="py-2 px-3 text-xs bg-slate-50 border border-slate-300 rounded-xl focus:ring-2 focus:ring-brand-500 font-medium">
                <option value="">Semua Peran</option>
                <option value="anggota" {{ $role === 'anggota' ? 'selected' : '' }}>Anggota Saja</option>
                <option value="petugas" {{ $role === 'petugas' ? 'selected' : '' }}>Petugas Saja</option>
                <option value="admin" {{ $role === 'admin' ? 'selected' : '' }}>Administrator Saja</option>
            </select>

            <button type="submit" class="px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs rounded-xl shadow-xs transition-colors">
                Filter
            </button>
            @if($search || $role)
            <a href="{{ route('admin.members.index') }}" class="text-xs font-bold text-rose-600 hover:underline">Reset</a>
            @endif
        </form>
    </div>

    <!-- Members Table -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 font-bold uppercase tracking-wider text-[10px]">
                        <th class="p-4 pl-6">Pengguna</th>
                        <th class="p-4">Nomor Anggota</th>
                        <th class="p-4">Kontak & Domisili</th>
                        <th class="p-4">Peran (Role)</th>
                        <th class="p-4">Status Akun</th>
                        <th class="p-4">Total Peminjaman</th>
                        <th class="p-4 pr-6 text-right">Tindakan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($users as $u)
                    <tr class="hover:bg-slate-50/60 transition-colors">
                        <td class="p-4 pl-6">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-brand-600 to-teal-700 text-white flex items-center justify-center font-bold text-xs shrink-0 shadow-xs">
                                    {{ strtoupper(substr($u->name, 0, 2)) }}
                                </div>
                                <div>
                                    <a href="{{ route('admin.members.show', $u->id) }}" class="font-display font-bold text-slate-900 hover:text-brand-700 block">
                                        {{ $u->name }}
                                    </a>
                                    <span class="text-[11px] text-slate-400 font-mono">{{ $u->email }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="p-4 font-mono font-bold text-slate-900">
                            {{ $u->member_number ?? '-' }}
                        </td>
                        <td class="p-4">
                            <span class="block text-slate-800 font-medium">{{ $u->phone ?? 'No HP -' }}</span>
                            <span class="text-[10px] text-slate-400 line-clamp-1 max-w-[150px]">{{ $u->address ?? 'Alamat -' }}</span>
                        </td>
                        <td class="p-4">
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold border 
                                {{ $u->role === 'admin' ? 'bg-purple-100 text-purple-800 border-purple-200' : ($u->role === 'petugas' ? 'bg-sky-100 text-sky-800 border-sky-200' : 'bg-slate-100 text-slate-700 border-slate-200') }}">
                                {{ ucfirst($u->role) }}
                            </span>
                        </td>
                        <td class="p-4">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold {{ $u->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $u->is_active ? 'bg-emerald-500' : 'bg-rose-500' }}"></span>
                                {{ $u->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>
                        <td class="p-4 font-bold text-slate-900 font-mono">
                            {{ $u->loans_count }}x transaksi
                        </td>
                        <td class="p-4 pr-6 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.members.show', $u->id) }}" class="p-1.5 text-slate-500 hover:text-brand-700 rounded-lg transition-colors" title="Lihat Profil">
                                    <i data-lucide="eye" class="w-4 h-4"></i>
                                </a>

                                <form action="{{ route('admin.members.toggleStatus', $u->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="px-2.5 py-1 text-[10px] font-bold rounded-lg border transition-colors {{ $u->is_active ? 'bg-slate-100 hover:bg-rose-50 text-slate-600 hover:text-rose-600 border-slate-200' : 'bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border-emerald-200' }}" title="{{ $u->is_active ? 'Nonaktifkan Akun' : 'Aktifkan Akun' }}">
                                        {{ $u->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="p-10 text-center text-slate-400 text-xs">
                            Tidak ada anggota yang ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-100">
            {{ $users->links() }}
        </div>
    </div>

</div>
@endsection
