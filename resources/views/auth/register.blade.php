@extends('layouts.app')

@section('title', 'Pendaftaran Anggota Baru - Aksaraya')

@section('content')
<div class="py-12 sm:py-20 flex items-center justify-center px-4">
    <div class="max-w-4xl w-full bg-white rounded-3xl shadow-2xl border border-slate-200/80 overflow-hidden grid grid-cols-1 md:grid-cols-12">
        
        <!-- Left Side: Editorial & Benefits -->
        <div class="md:col-span-5 bg-gradient-to-br from-navy-950 via-slate-900 to-brand-950 p-8 sm:p-10 text-white relative overflow-hidden flex flex-col justify-between">
            <div class="absolute -right-20 -bottom-20 w-64 h-64 bg-brand-500/20 rounded-full blur-3xl pointer-events-none"></div>
            
            <div class="space-y-6 relative z-10">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-brand-500 to-teal-500 flex items-center justify-center text-white shadow-glow">
                        <i data-lucide="book-open-check" class="w-5 h-5"></i>
                    </div>
                    <span class="font-display font-black text-xl text-white">Aksaraya</span>
                </div>

                <div class="space-y-3 pt-6">
                    <span class="text-xs font-bold uppercase tracking-wider text-brand-300 font-mono">Keanggotaan Gratis</span>
                    <h2 class="font-display text-2xl sm:text-3xl font-extrabold leading-snug">
                        Mulai Akses Ribuan Buku Berkualitas Hari Ini
                    </h2>
                    <p class="text-xs text-slate-400 leading-relaxed">
                        Pendaftaran terbuka untuk seluruh warga Indonesia tanpa dipungut biaya sepeserpun.
                    </p>
                </div>

                <div class="space-y-3 pt-4 text-xs text-slate-300">
                    <div class="flex items-start gap-2.5">
                        <i data-lucide="check" class="w-4 h-4 text-emerald-400 shrink-0 mt-0.5"></i>
                        <span>Penerbitan otomatis <strong>Kartu Anggota Digital Ber-Barcode</strong></span>
                    </div>
                    <div class="flex items-start gap-2.5">
                        <i data-lucide="check" class="w-4 h-4 text-emerald-400 shrink-0 mt-0.5"></i>
                        <span>Akses e-book online kapan saja dan di mana saja</span>
                    </div>
                    <div class="flex items-start gap-2.5">
                        <i data-lucide="check" class="w-4 h-4 text-emerald-400 shrink-0 mt-0.5"></i>
                        <span>Reservasi peminjaman buku fisik hingga 3 judul</span>
                    </div>
                </div>
            </div>

            <div class="pt-8 border-t border-white/10 relative z-10 text-[11px] text-slate-400">
                &copy; {{ date('Y') }} Aksaraya Digital Pass
            </div>
        </div>

        <!-- Right Side: Registration Form -->
        <div class="md:col-span-7 p-8 sm:p-12 space-y-6 bg-white">
            
            <div>
                <h3 class="font-display text-2xl font-black text-slate-900 tracking-tight">Formulir Pendaftaran</h3>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">Lengkapi data diri Anda untuk membuat kartu perpustakaan digital</p>
            </div>

            <form method="POST" action="{{ route('register.post') }}" class="space-y-4">
                @csrf

                <div>
                    <label for="name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nama Lengkap *</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <i data-lucide="user" class="w-4 h-4"></i>
                        </div>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required autofocus
                            placeholder="Contoh: Budi Santoso"
                            class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:bg-white transition-all font-medium">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Alamat Email *</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <i data-lucide="mail" class="w-4 h-4"></i>
                            </div>
                            <input type="email" name="email" id="email" value="{{ old('email') }}" required
                                placeholder="budi@gmail.com"
                                class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:bg-white transition-all">
                        </div>
                    </div>

                    <div>
                        <label for="phone" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">No. WhatsApp / HP</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <i data-lucide="phone" class="w-4 h-4"></i>
                            </div>
                            <input type="text" name="phone" id="phone" value="{{ old('phone') }}"
                                placeholder="081234567890"
                                class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:bg-white transition-all">
                        </div>
                    </div>
                </div>

                <div>
                    <label for="address" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Alamat Domisili</label>
                    <textarea name="address" id="address" rows="2"
                        placeholder="Kota atau alamat lengkap domisili saat ini"
                        class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:bg-white transition-all">{{ old('address') }}</textarea>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Kata Sandi *</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <i data-lucide="lock" class="w-4 h-4"></i>
                            </div>
                            <input type="password" name="password" id="password" required
                                placeholder="Min. 6 karakter"
                                class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:bg-white transition-all">
                        </div>
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Ulangi Sandi *</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <i data-lucide="check-check" class="w-4 h-4"></i>
                            </div>
                            <input type="password" name="password_confirmation" id="password_confirmation" required
                                placeholder="Konfirmasi sandi"
                                class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:bg-white transition-all">
                        </div>
                    </div>
                </div>

                <button type="submit" class="w-full py-3.5 px-4 text-xs sm:text-sm font-bold text-white bg-gradient-to-r from-brand-600 to-teal-700 hover:from-brand-700 hover:to-teal-800 rounded-xl shadow-glow hover:shadow-lg transition-all flex items-center justify-center gap-2">
                    <i data-lucide="check" class="w-4 h-4"></i>
                    <span>Daftar & Terbitkan Kartu Anggota</span>
                </button>
            </form>

            <div class="pt-4 border-t border-slate-100 text-center">
                <p class="text-xs text-slate-500">
                    Sudah memiliki akun anggota?
                    <a href="{{ route('login') }}" class="font-bold text-brand-700 hover:underline ml-1">Masuk Sekarang</a>
                </p>
            </div>

        </div>

    </div>
</div>
@endsection
