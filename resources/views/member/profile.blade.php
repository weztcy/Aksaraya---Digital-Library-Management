@extends('layouts.app')

@section('title', 'Pengaturan Profil Anggota - Aksaraya')

@section('content')
<!-- Header Banner -->
<div class="bg-gradient-to-r from-navy-950 via-slate-900 to-brand-950 text-white py-12 border-b border-slate-800 relative overflow-hidden">
    <div class="absolute -right-20 -top-20 w-80 h-80 bg-brand-500/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="max-w-3xl space-y-2">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-brand-500/20 text-brand-300 border border-brand-500/30 text-xs font-bold uppercase tracking-wider">
                Profil Pengguna
            </span>
            <h1 class="font-display text-3xl sm:text-4xl font-black tracking-tight text-white">Pengaturan Akun</h1>
            <p class="text-xs sm:text-sm text-slate-300">
                Kelola informasi kontak dan ubah kata sandi akun Aksaraya Anda.
            </p>
        </div>
    </div>
</div>

<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 sm:p-10 space-y-8">
        
        <div class="flex items-center gap-4 pb-6 border-b border-slate-100">
            <div class="w-16 h-16 rounded-2xl bg-gradient-to-tr from-brand-600 to-teal-700 text-white flex items-center justify-center font-black text-xl shadow-glow">
                {{ strtoupper(substr($user->name, 0, 2)) }}
            </div>
            <div>
                <h3 class="font-display font-bold text-slate-900 text-lg">{{ $user->name }}</h3>
                <p class="text-xs text-brand-700 font-mono font-bold">{{ $user->member_number ?? 'Belum ada nomor' }} &bull; {{ ucfirst($user->role) }}</p>
                <span class="inline-flex items-center gap-1.5 text-[10px] text-emerald-700 font-semibold mt-1">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                    <span>Anggota Aktif Sejak {{ $user->created_at->format('M Y') }}</span>
                </span>
            </div>
        </div>

        <form method="POST" action="{{ route('member.profile.update') }}" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nama Lengkap *</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                        class="w-full text-xs p-3 bg-slate-50 border border-slate-300 rounded-xl focus:ring-2 focus:ring-brand-500 font-medium">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Alamat Email *</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                        class="w-full text-xs p-3 bg-slate-50 border border-slate-300 rounded-xl focus:ring-2 focus:ring-brand-500 font-medium">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nomor Telepon / WhatsApp</label>
                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                        class="w-full text-xs p-3 bg-slate-50 border border-slate-300 rounded-xl focus:ring-2 focus:ring-brand-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Alamat Domisili</label>
                    <input type="text" name="address" value="{{ old('address', $user->address) }}"
                        class="w-full text-xs p-3 bg-slate-50 border border-slate-300 rounded-xl focus:ring-2 focus:ring-brand-500">
                </div>
            </div>

            <!-- Password Change Section -->
            <div class="pt-6 border-t border-slate-100 space-y-4">
                <h4 class="font-display font-bold text-sm text-slate-900">Ubah Kata Sandi (Opsional)</h4>
                <p class="text-xs text-slate-400">Kosongkan jika Anda tidak ingin memperbarui kata sandi.</p>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Kata Sandi Saat Ini</label>
                        <input type="password" name="current_password"
                            placeholder="Password lama"
                            class="w-full text-xs p-3 bg-slate-50 border border-slate-300 rounded-xl focus:ring-2 focus:ring-brand-500">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Kata Sandi Baru</label>
                        <input type="password" name="new_password"
                            placeholder="Min. 6 karakter"
                            class="w-full text-xs p-3 bg-slate-50 border border-slate-300 rounded-xl focus:ring-2 focus:ring-brand-500">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Konfirmasi Kata Sandi Baru</label>
                    <input type="password" name="new_password_confirmation"
                        placeholder="Ulangi password baru"
                        class="w-full text-xs p-3 bg-slate-50 border border-slate-300 rounded-xl focus:ring-2 focus:ring-brand-500">
                </div>
            </div>

            <div class="flex justify-end pt-4 border-t border-slate-100">
                <button type="submit" class="px-7 py-3 bg-gradient-to-r from-brand-600 to-teal-700 hover:from-brand-700 hover:to-teal-800 text-white font-bold text-xs rounded-xl shadow-glow transition-all flex items-center gap-2">
                    <i data-lucide="check" class="w-4 h-4"></i>
                    <span>Simpan Perubahan Akun</span>
                </button>
            </div>
        </form>

    </div>
</div>
@endsection
