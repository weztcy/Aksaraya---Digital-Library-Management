@extends('layouts.app')

@section('title', 'Tentang Layanan & Tata Tertib - Aksaraya')

@section('content')
<!-- Header Banner -->
<div class="bg-gradient-to-r from-navy-950 via-slate-900 to-brand-950 text-white py-14 border-b border-slate-800 relative overflow-hidden">
    <div class="absolute -right-20 -top-20 w-80 h-80 bg-brand-500/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="max-w-3xl space-y-3">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-brand-500/20 text-brand-300 border border-brand-500/30 text-xs font-bold uppercase tracking-wider">
                Profil & Pedoman Resmi
            </span>
            <h1 class="font-display text-3xl sm:text-4xl lg:text-5xl font-black tracking-tight">Tentang Aksaraya</h1>
            <p class="text-sm sm:text-base text-slate-300 leading-relaxed font-normal">
                Menyediakan akses sumber daya ilmu pengetahuan dan bahan pustaka bermutu bagi seluruh masyarakat Indonesia secara inklusif, modern, dan terpercaya.
            </p>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 space-y-16">
    
    <!-- Vision & Mission Bento Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        
        <div class="p-8 sm:p-10 bg-white rounded-3xl border border-slate-200/80 shadow-sm space-y-4 relative overflow-hidden">
            <div class="w-14 h-14 rounded-2xl bg-brand-50 text-brand-600 flex items-center justify-center font-bold shadow-inner">
                <i data-lucide="compass" class="w-7 h-7"></i>
            </div>
            <span class="text-xs font-bold uppercase tracking-wider text-brand-600 font-mono">Arah Masa Depan</span>
            <h2 class="font-display text-2xl font-extrabold text-slate-900">Visi Aksaraya</h2>
            <p class="text-slate-600 text-xs sm:text-sm leading-relaxed">
                Menjadi episentrum perpustakaan digital terdepan di Indonesia yang menjembatani kesetaraan literasi, kemajuan teknologi informasi, dan pelestarian khazanah kebudayaan Nusantara bagi generasi masa kini dan masa depan.
            </p>
        </div>

        <div class="p-8 sm:p-10 bg-white rounded-3xl border border-slate-200/80 shadow-sm space-y-4 relative overflow-hidden">
            <div class="w-14 h-14 rounded-2xl bg-teal-50 text-teal-600 flex items-center justify-center font-bold shadow-inner">
                <i data-lucide="target" class="w-7 h-7"></i>
            </div>
            <span class="text-xs font-bold uppercase tracking-wider text-teal-600 font-mono">Komitmen Layanan</span>
            <h2 class="font-display text-2xl font-extrabold text-slate-900">Misi Utama Kami</h2>
            <ul class="text-slate-600 text-xs sm:text-sm space-y-2.5 list-disc list-inside leading-relaxed">
                <li>Menyediakan koleksi buku cetak dan e-book berkualitas tinggi secara cuma-cuma bagi anggota.</li>
                <li>Menerapkan sistem sirkulasi peminjaman buku modern, transparan, dan terintegrasi secara digital.</li>
                <li>Mendorong budaya membaca aktif melalui fitur interaktif, ruang ulasan, dan komunitas pembaca.</li>
            </ul>
        </div>

    </div>

    <!-- Circulation Regulations Section -->
    <div class="bg-white rounded-3xl border border-slate-200/80 p-8 sm:p-12 shadow-sm space-y-8">
        <div>
            <span class="text-xs font-bold uppercase tracking-wider text-brand-600 font-mono">Pedoman Anggota</span>
            <h2 class="font-display text-2xl sm:text-3xl font-extrabold text-slate-900 mt-1">Tata Tertib & Ketentuan Peminjaman</h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Harap ditaati oleh seluruh anggota terdaftar demi kelancaran sirkulasi buku</p>
        </div>

        @php
            $loanDays = \App\Models\Setting::get('loan_period_days', 7);
            $dailyFine = \App\Models\Setting::get('daily_fine_rate', 1000);
            $maxBorrow = \App\Models\Setting::get('max_borrow_limit', 3);
        @endphp

        <!-- Metric badges -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
            <div class="p-6 rounded-3xl bg-slate-50 border border-slate-200/80 text-center space-y-1">
                <div class="font-display text-3xl font-black text-brand-700">{{ $loanDays }} Hari</div>
                <div class="text-xs font-bold text-slate-800 uppercase tracking-wider">Durasi Pinjam Standar</div>
                <div class="text-[11px] text-slate-500">Dapat diperpanjang jika belum direservasi anggota lain.</div>
            </div>

            <div class="p-6 rounded-3xl bg-slate-50 border border-slate-200/80 text-center space-y-1">
                <div class="font-display text-3xl font-black text-indigo-700">{{ $maxBorrow }} Judul Buku</div>
                <div class="text-xs font-bold text-slate-800 uppercase tracking-wider">Batas Maksimal Pinjaman</div>
                <div class="text-[11px] text-slate-500">Maksimal pinjaman aktif bersamaan per anggota.</div>
            </div>

            <div class="p-6 rounded-3xl bg-slate-50 border border-slate-200/80 text-center space-y-1">
                <div class="font-display text-3xl font-black text-rose-600">Rp {{ number_format($dailyFine, 0, ',', '.') }}</div>
                <div class="text-xs font-bold text-slate-800 uppercase tracking-wider">Tarif Denda Keterlambatan</div>
                <div class="text-[11px] text-slate-500">Dihitung otomatis per hari untuk tiap buku yang telat.</div>
            </div>
        </div>

        <!-- Visual Step-by-Step Borrowing Flow -->
        <div class="pt-6 border-t border-slate-100 space-y-6">
            <h3 class="font-display font-bold text-slate-900 text-lg">Alur Peminjaman & Pengembalian Buku Fisik:</h3>
            
            <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-2">
                    <span class="w-7 h-7 rounded-full bg-brand-600 text-white font-bold text-xs flex items-center justify-center">1</span>
                    <h4 class="font-bold text-xs text-slate-900">Pilih Koleksi</h4>
                    <p class="text-[11px] text-slate-500 leading-relaxed">Cari buku melalui katalog dan pastikan stok fisik buku berstatus tersedia.</p>
                </div>

                <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-2">
                    <span class="w-7 h-7 rounded-full bg-brand-600 text-white font-bold text-xs flex items-center justify-center">2</span>
                    <h4 class="font-bold text-xs text-slate-900">Ajukan Pinjam</h4>
                    <p class="text-[11px] text-slate-500 leading-relaxed">Klik tombol pinjam di web untuk membuat kode transaksi reservasi unik.</p>
                </div>

                <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-2">
                    <span class="w-7 h-7 rounded-full bg-brand-600 text-white font-bold text-xs flex items-center justify-center">3</span>
                    <h4 class="font-bold text-xs text-slate-900">Pengambilan</h4>
                    <p class="text-[11px] text-slate-500 leading-relaxed">Tunjukkan kartu anggota digital atau kode pinjam kepada petugas perpustakaan.</p>
                </div>

                <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-2">
                    <span class="w-7 h-7 rounded-full bg-brand-600 text-white font-bold text-xs flex items-center justify-center">4</span>
                    <h4 class="font-bold text-xs text-slate-900">Pengembalian</h4>
                    <p class="text-[11px] text-slate-500 leading-relaxed">Kembalikan sebelum due date di meja sirkulasi untuk verifikasi akhir.</p>
                </div>
            </div>
        </div>

    </div>

    <!-- Contact & Operating Hours Card -->
    <div class="bg-gradient-to-r from-navy-950 via-slate-900 to-brand-950 rounded-3xl p-8 sm:p-12 text-white shadow-xl relative overflow-hidden">
        <div class="absolute -right-20 -bottom-20 w-80 h-80 bg-brand-500/15 rounded-full blur-3xl pointer-events-none"></div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-10 items-center relative z-10">
            
            <div class="space-y-4">
                <span class="text-xs font-bold uppercase tracking-wider text-brand-300 font-mono">Hubungi Tim Layanan</span>
                <h2 class="font-display text-2xl sm:text-3xl font-black">Layanan Pelanggan Aksaraya</h2>
                <p class="text-slate-300 text-xs sm:text-sm leading-relaxed">
                    Punya pertanyaan, usulan pengadaan buku baru, atau butuh bantuan dengan akun Anda? Tim pustakawan kami siap melayani setiap hari kerja.
                </p>
                <div class="space-y-2.5 text-xs sm:text-sm text-slate-300 pt-2">
                    <p class="flex items-center gap-2.5">
                        <i data-lucide="map-pin" class="w-4 h-4 text-brand-400"></i>
                        <span>{{ \App\Models\Setting::get('library_address', 'Jl. Merdeka Literasi No. 45, Jakarta') }}</span>
                    </p>
                    <p class="flex items-center gap-2.5">
                        <i data-lucide="phone" class="w-4 h-4 text-brand-400"></i>
                        <span>{{ \App\Models\Setting::get('library_phone', '+62 21 555-2026') }}</span>
                    </p>
                    <p class="flex items-center gap-2.5">
                        <i data-lucide="mail" class="w-4 h-4 text-brand-400"></i>
                        <span>{{ \App\Models\Setting::get('library_email', 'layanan@aksaraya.id') }}</span>
                    </p>
                </div>
            </div>

            <!-- Operating Hours Box -->
            <div class="p-6 sm:p-8 bg-white/10 rounded-3xl border border-white/15 backdrop-blur-md space-y-4">
                <h3 class="font-display font-bold text-white text-base flex items-center gap-2">
                    <i data-lucide="clock" class="w-5 h-5 text-emerald-400"></i>
                    <span>Jam Operasional Gedung Perpustakaan</span>
                </h3>
                <div class="space-y-2.5 text-xs text-slate-300">
                    <div class="flex justify-between border-b border-white/10 pb-2">
                        <span>Senin - Kamis</span>
                        <span class="font-bold text-white">08:00 - 17:00 WIB</span>
                    </div>
                    <div class="flex justify-between border-b border-white/10 pb-2">
                        <span>Jumat</span>
                        <span class="font-bold text-white">08:00 - 16:30 WIB (Istirahat 11:30 - 13:00)</span>
                    </div>
                    <div class="flex justify-between border-b border-white/10 pb-2">
                        <span>Sabtu</span>
                        <span class="font-bold text-white">09:00 - 15:00 WIB</span>
                    </div>
                    <div class="flex justify-between text-amber-300 font-semibold pt-1">
                        <span>Minggu & Hari Libur</span>
                        <span>Layanan Fisik Tutup (Portal E-Book 24 Jam)</span>
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>
@endsection
