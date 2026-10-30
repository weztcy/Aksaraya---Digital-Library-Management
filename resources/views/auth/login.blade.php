@extends('layouts.app')

@section('title', 'Masuk ke Portal Anggota - Aksaraya')

@section('content')
<div class="py-12 sm:py-20 flex items-center justify-center px-4">
    <div class="max-w-4xl w-full bg-white rounded-3xl shadow-2xl border border-slate-200/80 overflow-hidden grid grid-cols-1 md:grid-cols-12">
        
        <!-- Left Side: Editorial & Atmospheric Artwork -->
        <div class="md:col-span-5 bg-gradient-to-br from-navy-950 via-slate-900 to-brand-950 p-8 sm:p-10 text-white relative overflow-hidden flex flex-col justify-between">
            <div class="absolute -right-20 -bottom-20 w-64 h-64 bg-brand-500/20 rounded-full blur-3xl pointer-events-none"></div>
            
            <div class="space-y-6 relative z-10">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-brand-500 to-teal-500 flex items-center justify-center text-white shadow-glow">
                        <i data-lucide="book-open-check" class="w-5 h-5"></i>
                    </div>
                    <span class="font-display font-black text-xl text-white">Aksaraya</span>
                </div>

                <div class="space-y-2 pt-8">
                    <span class="text-xs font-bold uppercase tracking-wider text-brand-300 font-mono">Ruang Literasi Nusantara</span>
                    <h2 class="font-display text-2xl sm:text-3xl font-extrabold leading-snug">
                        "Membaca adalah jembatan menuju peradaban agung."
                    </h2>
                    <p class="text-xs text-slate-400 leading-relaxed pt-2">
                        Masuk untuk menikmati ribuan buku digital, mengecek masa pinjam buku fisik, serta melihat riwayat ulasan Anda.
                    </p>
                </div>
            </div>

            <div class="pt-8 border-t border-white/10 relative z-10 text-xs text-slate-400 space-y-2">
                <div class="flex items-center gap-2">
                    <i data-lucide="check-circle" class="w-4 h-4 text-emerald-400"></i>
                    <span>Akses e-book 24 jam nonstop</span>
                </div>
                <div class="flex items-center gap-2">
                    <i data-lucide="check-circle" class="w-4 h-4 text-emerald-400"></i>
                    <span>Pinjam & perpanjang buku online</span>
                </div>
            </div>
        </div>

        <!-- Right Side: Login Form -->
        <div class="md:col-span-7 p-8 sm:p-12 space-y-6 bg-white">
            
            <div>
                <h3 class="font-display text-2xl font-black text-slate-900 tracking-tight">Selamat Datang Kembali</h3>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">Masukkan kredensial email dan kata sandi Anda</p>
            </div>

            <!-- Quick Demo 1-Click Persona Bar -->
            <div class="p-4 bg-slate-50 border border-slate-200/90 rounded-2xl space-y-2">
                <p class="text-[11px] font-bold text-slate-500 uppercase tracking-wider flex items-center gap-1.5">
                    <i data-lucide="zap" class="w-3.5 h-3.5 text-amber-500 fill-amber-500"></i>
                    <span>Akun Demo Cepat (1-Klik Langsung Terisi)</span>
                </p>
                <div class="grid grid-cols-3 gap-2">
                    <button type="button" onclick="fillLogin('admin@aksaraya.id', 'password')" class="px-3 py-1.5 text-xs font-bold text-indigo-700 bg-indigo-50 hover:bg-indigo-100 border border-indigo-200 rounded-xl transition-colors text-center">
                        Admin
                    </button>
                    <button type="button" onclick="fillLogin('petugas@aksaraya.id', 'password')" class="px-3 py-1.5 text-xs font-bold text-sky-700 bg-sky-50 hover:bg-sky-100 border border-sky-200 rounded-xl transition-colors text-center">
                        Petugas
                    </button>
                    <button type="button" onclick="fillLogin('budi@gmail.com', 'password')" class="px-3 py-1.5 text-xs font-bold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 rounded-xl transition-colors text-center">
                        Anggota
                    </button>
                </div>
            </div>

            <!-- Form -->
            <form method="POST" action="{{ route('login.post') }}" class="space-y-4">
                @csrf

                <div>
                    <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Alamat Email</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <i data-lucide="mail" class="w-4 h-4"></i>
                        </div>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                            placeholder="nama@email.com"
                            class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:bg-white transition-all font-medium">
                    </div>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Kata Sandi</label>
                    </div>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <i data-lucide="lock" class="w-4 h-4"></i>
                        </div>
                        <input type="password" name="password" id="password" required
                            placeholder="••••••••"
                            class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:bg-white transition-all">
                    </div>
                </div>

                <div class="flex items-center justify-between pt-1">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="remember" class="rounded text-brand-600 focus:ring-brand-500">
                        <span class="text-xs text-slate-600 font-medium">Ingat saya di perangkat ini</span>
                    </label>
                </div>

                <button type="submit" class="w-full py-3 px-4 text-xs sm:text-sm font-bold text-white bg-gradient-to-r from-brand-600 to-teal-700 hover:from-brand-700 hover:to-teal-800 rounded-xl shadow-glow hover:shadow-lg transition-all flex items-center justify-center gap-2">
                    <i data-lucide="log-in" class="w-4 h-4"></i>
                    <span>Masuk ke Akun Anda</span>
                </button>
            </form>

            <div class="pt-4 border-t border-slate-100 text-center">
                <p class="text-xs text-slate-500">
                    Belum menjadi anggota perpustakaan?
                    <a href="{{ route('register') }}" class="font-bold text-brand-700 hover:underline ml-1">Daftar Anggota Baru</a>
                </p>
            </div>

        </div>

    </div>
</div>

@push('scripts')
<script>
    function fillLogin(email, pass) {
        document.getElementById('email').value = email;
        document.getElementById('password').value = pass;
    }
</script>
@endpush
@endsection
