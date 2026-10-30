@extends('layouts.app')

@section('title', 'Aksaraya - Perpustakaan Digital Nusantara Modern')

@section('content')
<!-- Hero Section -->
<section class="relative bg-gradient-to-b from-navy-950 via-slate-900 to-navy-900 text-white overflow-hidden pt-12 pb-24 sm:pt-20 sm:pb-32">
    <!-- Ambient mesh gradients -->
    <div class="absolute -top-40 right-0 w-[550px] h-[550px] bg-brand-500/15 rounded-full blur-[130px] pointer-events-none"></div>
    <div class="absolute top-1/3 -left-40 w-[550px] h-[550px] bg-indigo-500/15 rounded-full blur-[130px] pointer-events-none"></div>
    <div class="absolute inset-0 opacity-[0.04] bg-[radial-gradient(#fff_1px,transparent_1px)] [background-size:24px_24px] pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            
            <!-- Left: Hero Headline & Search -->
            <div class="lg:col-span-7 space-y-7 text-center lg:text-left">
                
                <div class="inline-flex items-center gap-2.5 px-4 py-1.5 rounded-full bg-white/10 backdrop-blur-md border border-white/15 text-xs font-semibold text-brand-300 shadow-inner">
                    <span class="flex h-2 w-2 relative">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-brand-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-brand-400"></span>
                    </span>
                    <span>Platform Literasi & Sirkulasi Digital Indonesia</span>
                </div>

                <h1 class="font-display text-4xl sm:text-5xl lg:text-6xl font-black tracking-tight text-white leading-[1.12]">
                    Jelajahi Cakrawala Ilmu Bersama <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-300 via-teal-300 to-sky-300">Aksaraya</span>
                </h1>

                <p class="text-base sm:text-lg text-slate-300 max-w-xl mx-auto lg:mx-0 leading-relaxed font-normal">
                    Akses ribuan karya sastra agung Nusantara, sains, teknologi, hingga buku pengembangan diri. Nikmati sensasi membaca e-book online instan atau pinjam buku fisik dengan mudah.
                </p>

                <!-- Modern Live Search Bar -->
                <div class="pt-2">
                    <form action="{{ route('catalog.index') }}" method="GET" class="relative max-w-xl mx-auto lg:mx-0">
                        <div class="relative flex items-center p-2 rounded-2xl bg-white/95 backdrop-blur-xl shadow-2xl shadow-black/50 border border-white/30 focus-within:ring-2 focus-within:ring-brand-400 transition-all">
                            <div class="pl-3.5 pr-2 text-slate-400">
                                <i data-lucide="search" class="w-5 h-5"></i>
                            </div>
                            <input type="text" name="q" placeholder="Cari judul buku, pengarang (Andrea Hirata, Pramoedya...), atau ISBN..." 
                                class="w-full bg-transparent text-slate-900 placeholder-slate-400 text-xs sm:text-sm focus:outline-none pr-3 py-1 font-medium">
                            <button type="submit" class="shrink-0 px-6 py-3 rounded-xl bg-gradient-to-r from-brand-600 to-teal-700 hover:from-brand-700 hover:to-teal-800 text-white font-bold text-xs sm:text-sm shadow-glow transition-all flex items-center gap-2">
                                <span>Eksplorasi</span>
                                <i data-lucide="arrow-right" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </form>

                    <!-- Quick Filter Tags -->
                    <div class="flex flex-wrap items-center justify-center lg:justify-start gap-2 pt-3 text-xs text-slate-400">
                        <span class="text-slate-400 font-medium">Populer:</span>
                        <a href="{{ route('catalog.index', ['q' => 'Laskar Pelangi']) }}" class="px-2.5 py-1 rounded-lg bg-white/10 hover:bg-white/20 text-slate-200 transition-colors">Laskar Pelangi</a>
                        <a href="{{ route('catalog.index', ['tipe' => 'ebook']) }}" class="px-2.5 py-1 rounded-lg bg-brand-500/20 text-brand-300 border border-brand-500/30 hover:bg-brand-500/30 transition-colors">E-Book Gratis</a>
                        <a href="{{ route('catalog.index', ['q' => 'Bumi Manusia']) }}" class="px-2.5 py-1 rounded-lg bg-white/10 hover:bg-white/20 text-slate-200 transition-colors">Bumi Manusia</a>
                        <a href="{{ route('catalog.index', ['q' => 'Filosofi Teras']) }}" class="px-2.5 py-1 rounded-lg bg-white/10 hover:bg-white/20 text-slate-200 transition-colors">Filosofi Teras</a>
                    </div>
                </div>

                <!-- Trust Indicator Badges -->
                <div class="pt-4 flex flex-wrap items-center justify-center lg:justify-start gap-6 text-xs text-slate-300">
                    <div class="flex items-center gap-2">
                        <i data-lucide="shield-check" class="w-4 h-4 text-emerald-400"></i>
                        <span>100% Keanggotaan Gratis</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i data-lucide="id-card" class="w-4 h-4 text-sky-400"></i>
                        <span>Kartu Anggota Ber-Barcode</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i data-lucide="smartphone" class="w-4 h-4 text-amber-400"></i>
                        <span>E-Reader Responsif</span>
                    </div>
                </div>

            </div>

            <!-- Right: 3D Book Visual Showcase Stack -->
            <div class="lg:col-span-5 relative flex items-center justify-center">
                <!-- Outer floating decorative orb -->
                <div class="relative w-full max-w-sm sm:max-w-md mx-auto aspect-[4/5] flex items-center justify-center">
                    
                    <!-- Decorative back card -->
                    <div class="absolute w-56 sm:w-64 aspect-[3/4] -rotate-12 translate-x-12 -translate-y-4 rounded-3xl overflow-hidden shadow-2xl border border-white/20 bg-slate-800 opacity-60 pointer-events-none">
                        <img src="https://images.unsplash.com/photo-1497633762265-9d179a990aa6?auto=format&fit=crop&w=600&q=80" alt="" class="w-full h-full object-cover">
                    </div>

                    <!-- Decorative second card -->
                    <div class="absolute w-56 sm:w-64 aspect-[3/4] rotate-6 -translate-x-10 translate-y-6 rounded-3xl overflow-hidden shadow-2xl border border-white/20 bg-slate-800 opacity-70 pointer-events-none">
                        <img src="https://images.unsplash.com/photo-1512820790803-83ca734da794?auto=format&fit=crop&w=600&q=80" alt="" class="w-full h-full object-cover">
                    </div>

                    <!-- Main center card -->
                    <div class="relative w-64 sm:w-72 aspect-[3/4] rounded-3xl overflow-hidden shadow-book-hover border-2 border-white/30 bg-slate-900 group book-3d z-20">
                        <img src="https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?auto=format&fit=crop&w=800&q=80" 
                             alt="Laskar Pelangi" 
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        
                        <!-- Floating Glass Pill Info -->
                        <div class="absolute inset-x-3 bottom-3 p-3 rounded-2xl glass-dark border border-white/20 text-white space-y-1">
                            <div class="flex items-center justify-between">
                                <span class="px-2 py-0.5 rounded-md text-[9px] font-bold bg-brand-500 text-white uppercase tracking-wider">Koleksi Terpopuler</span>
                                <div class="flex items-center gap-1 text-[11px] text-amber-300 font-bold">
                                    <i data-lucide="star" class="w-3.5 h-3.5 fill-amber-300 text-amber-300"></i>
                                    <span>5.0</span>
                                </div>
                            </div>
                            <h4 class="font-bold text-xs sm:text-sm line-clamp-1">Laskar Pelangi</h4>
                            <p class="text-[11px] text-slate-300">Andrea Hirata &bull; Bentang Pustaka</p>
                        </div>
                    </div>

                </div>
            </div>

        </div>

        <!-- KPI Metrics Grid -->
        <div class="mt-20 grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
            <div class="p-6 rounded-3xl glass-dark border border-white/10 hover:border-brand-500/50 transition-all text-center group">
                <div class="w-10 h-10 mx-auto rounded-xl bg-brand-500/20 text-brand-400 flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                    <i data-lucide="book-copy" class="w-5 h-5"></i>
                </div>
                <p class="font-display text-3xl sm:text-4xl font-black text-white">{{ number_format($stats['total_books']) }}</p>
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400 mt-1">Judul Koleksi</p>
            </div>

            <div class="p-6 rounded-3xl glass-dark border border-white/10 hover:border-teal-500/50 transition-all text-center group">
                <div class="w-10 h-10 mx-auto rounded-xl bg-teal-500/20 text-teal-400 flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                    <i data-lucide="layers" class="w-5 h-5"></i>
                </div>
                <p class="font-display text-3xl sm:text-4xl font-black text-teal-300">{{ number_format($stats['total_copies']) }}</p>
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400 mt-1">Eksemplar Buku</p>
            </div>

            <div class="p-6 rounded-3xl glass-dark border border-white/10 hover:border-sky-500/50 transition-all text-center group">
                <div class="w-10 h-10 mx-auto rounded-xl bg-sky-500/20 text-sky-400 flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                    <i data-lucide="users" class="w-5 h-5"></i>
                </div>
                <p class="font-display text-3xl sm:text-4xl font-black text-sky-300">{{ number_format($stats['total_members']) }}</p>
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400 mt-1">Anggota Terdaftar</p>
            </div>

            <div class="p-6 rounded-3xl glass-dark border border-white/10 hover:border-amber-500/50 transition-all text-center group">
                <div class="w-10 h-10 mx-auto rounded-xl bg-amber-500/20 text-amber-400 flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                    <i data-lucide="arrow-left-right" class="w-5 h-5"></i>
                </div>
                <p class="font-display text-3xl sm:text-4xl font-black text-amber-300">{{ number_format($stats['total_loans']) }}</p>
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400 mt-1">Sirkulasi Pinjaman</p>
            </div>
        </div>

    </div>
</section>

<!-- Bento Grid: Keunggulan Fitur Aksaraya -->
<section class="py-24 bg-white border-b border-slate-200/80 relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="text-center max-w-2xl mx-auto mb-16 space-y-3">
            <span class="px-3 py-1 rounded-full text-xs font-bold bg-brand-50 text-brand-700 border border-brand-200 uppercase tracking-wider">
                Fasilitas Lengkap
            </span>
            <h2 class="font-display text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">
                Membaca Lebih Cerdas, Meminjam Lebih Cepat
            </h2>
            <p class="text-sm sm:text-base text-slate-600">
                Aksaraya menggabungkan fleksibilitas e-book digital dengan kemudahan reservasi buku fisik perpustakaan.
            </p>
        </div>

        <!-- Bento Grid 4 Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            
            <!-- Bento 1: Large Span 2 -->
            <div class="md:col-span-2 bg-gradient-to-br from-slate-900 to-navy-950 rounded-3xl p-8 text-white relative overflow-hidden flex flex-col justify-between shadow-xl">
                <div class="absolute top-0 right-0 w-80 h-80 bg-brand-500/10 rounded-full blur-3xl pointer-events-none"></div>
                <div class="space-y-4 max-w-lg relative z-10">
                    <div class="w-12 h-12 rounded-2xl bg-brand-500/20 text-brand-400 flex items-center justify-center">
                        <i data-lucide="tablet" class="w-6 h-6"></i>
                    </div>
                    <h3 class="font-display text-xl sm:text-2xl font-extrabold text-white leading-snug">E-Reader Terintegrasi Tanpa Perlu Aplikasi Tambahan</h3>
                    <p class="text-slate-300 text-xs sm:text-sm leading-relaxed">
                        Cukup buka peramban di laptop atau ponsel Anda. E-book digital langsung terbuka dalam format PDF interaktif dengan navigasi halaman dan mode layar penuh.
                    </p>
                    <div class="pt-2">
                        <a href="{{ route('catalog.index', ['tipe' => 'ebook']) }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-brand-500 hover:bg-brand-600 text-white font-bold text-xs rounded-xl shadow-glow transition-all">
                            <span>Buka Koleksi E-Book</span>
                            <i data-lucide="arrow-right" class="w-4 h-4"></i>
                        </a>
                    </div>
                </div>

                <div class="pt-8 flex items-center gap-4 text-xs text-slate-400">
                    <span class="flex items-center gap-1.5"><i data-lucide="check-circle" class="w-4 h-4 text-brand-400"></i> Gratis & Tanpa Kuota Pinjam</span>
                    <span class="flex items-center gap-1.5"><i data-lucide="check-circle" class="w-4 h-4 text-brand-400"></i> Tersedia 24 Jam Nonstop</span>
                </div>
            </div>

            <!-- Bento 2: Kartu Anggota Digital -->
            <div class="bg-gradient-to-tr from-brand-900 to-teal-950 rounded-3xl p-8 text-white relative overflow-hidden flex flex-col justify-between shadow-xl">
                <div class="space-y-4 relative z-10">
                    <div class="w-12 h-12 rounded-2xl bg-white/10 text-brand-300 flex items-center justify-center">
                        <i data-lucide="id-card" class="w-6 h-6"></i>
                    </div>
                    <h3 class="font-display text-xl sm:text-2xl font-extrabold text-white leading-snug">Kartu Anggota Ber-Barcode Resmi</h3>
                    <p class="text-slate-300 text-xs sm:text-sm leading-relaxed">
                        Dapatkan nomor anggota unik saat mendaftar. Kartu digital siap cetak kapan saja untuk verifikasi transaksi di perpustakaan fisik.
                    </p>
                </div>

                <div class="pt-6">
                    <div class="p-4 bg-white/10 rounded-2xl border border-white/10 backdrop-blur-md">
                        <div class="text-[10px] text-brand-300 font-mono">NOMOR IDENTITAS ANGGOTA:</div>
                        <div class="text-base font-black font-mono tracking-wider text-white">AKS-202610-0001</div>
                    </div>
                </div>
            </div>

            <!-- Bento 3: Pinjam Fisik Praktis -->
            <div class="bg-slate-50 border border-slate-200/80 rounded-3xl p-8 flex flex-col justify-between hover:shadow-md transition-shadow">
                <div class="space-y-3">
                    <div class="w-12 h-12 rounded-2xl bg-indigo-100 text-indigo-600 flex items-center justify-center">
                        <i data-lucide="clock" class="w-6 h-6"></i>
                    </div>
                    <h3 class="font-display text-lg font-bold text-slate-900">Peminjaman Online Praktis</h3>
                    <p class="text-slate-600 text-xs sm:text-sm leading-relaxed">
                        Pesan buku fisik dari rumah sebelum datang ke perpustakaan. Cek sisa stok di rak secara akurat dan transparan.
                    </p>
                </div>
                <div class="pt-4 text-xs font-semibold text-indigo-600 flex items-center gap-1">
                    <span>Masa pinjam 7 hari &bull; Perpanjangan fleksibel</span>
                </div>
            </div>

            <!-- Bento 4: Hitung Denda Otomatis -->
            <div class="bg-slate-50 border border-slate-200/80 rounded-3xl p-8 flex flex-col justify-between hover:shadow-md transition-shadow">
                <div class="space-y-3">
                    <div class="w-12 h-12 rounded-2xl bg-amber-100 text-amber-700 flex items-center justify-center">
                        <i data-lucide="calculator" class="w-6 h-6"></i>
                    </div>
                    <h3 class="font-display text-lg font-bold text-slate-900">Hitung Denda Otomatis & Transparan</h3>
                    <p class="text-slate-600 text-xs sm:text-sm leading-relaxed">
                        Sistem sirkulasi otomatis menghitung keterlambatan hari demi hari (Rp 1.000/hari) secara adil dan transparan tanpa biaya siluman.
                    </p>
                </div>
                <div class="pt-4 text-xs font-semibold text-amber-700 flex items-center gap-1">
                    <span>Notifikasi tenggat waktu di dashboard</span>
                </div>
            </div>

            <!-- Bento 5: Komunitas & Ulasan -->
            <div class="bg-slate-50 border border-slate-200/80 rounded-3xl p-8 flex flex-col justify-between hover:shadow-md transition-shadow">
                <div class="space-y-3">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-100 text-emerald-700 flex items-center justify-center">
                        <i data-lucide="star" class="w-6 h-6"></i>
                    </div>
                    <h3 class="font-display text-lg font-bold text-slate-900">Ulasan & Rating Komunitas</h3>
                    <p class="text-slate-600 text-xs sm:text-sm leading-relaxed">
                        Tulis ulasan dan beri bintang untuk buku yang telah Anda selesaikan. Bagikan inspirasi kepada sesama pecinta buku.
                    </p>
                </div>
                <div class="pt-4 text-xs font-semibold text-emerald-700 flex items-center gap-1">
                    <span>Ribuan pembaca aktif se-Indonesia</span>
                </div>
            </div>

        </div>

    </div>
</section>

<!-- Featured Books Showcase -->
<section class="py-24 bg-slate-50/70 border-b border-slate-200/80">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-12">
            <div>
                <span class="inline-flex items-center gap-1.5 text-xs font-bold uppercase tracking-wider text-amber-600">
                    <i data-lucide="sparkles" class="w-4 h-4 text-amber-500 fill-amber-500"></i>
                    <span>Koleksi Terpopuler</span>
                </span>
                <h2 class="font-display text-3xl sm:text-4xl font-extrabold text-slate-900 mt-1 tracking-tight">
                    Buku Pilihan Pembaca Aksaraya
                </h2>
            </div>
            <a href="{{ route('catalog.index', ['urutan' => 'populer']) }}" class="inline-flex items-center gap-2 text-xs font-bold text-brand-700 hover:text-brand-900 transition-colors">
                <span>Lihat Seluruh Koleksi</span>
                <i data-lucide="arrow-right" class="w-4 h-4"></i>
            </a>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-6">
            @foreach($featuredBooks as $book)
            <div class="group bg-white rounded-3xl border border-slate-200/80 overflow-hidden shadow-book hover:shadow-book-hover transition-all duration-300 flex flex-col justify-between hover:-translate-y-2">
                
                <div>
                    <!-- Book Cover -->
                    <div class="relative aspect-[3/4] bg-slate-100 overflow-hidden">
                        <img src="{{ $book->cover_image ?? 'https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?auto=format&fit=crop&w=600&q=80' }}" 
                             alt="{{ $book->title }}" 
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">

                        <!-- E-Book Badge -->
                        @if($book->has_ebook)
                        <span class="absolute top-2.5 left-2.5 px-2 py-0.5 rounded-md text-[10px] font-extrabold bg-gradient-to-r from-emerald-600 to-teal-600 text-white shadow-md flex items-center gap-1">
                            <i data-lucide="file-text" class="w-3 h-3"></i>
                            E-Book
                        </span>
                        @endif

                        <!-- Rating Badge -->
                        <div class="absolute bottom-2.5 right-2.5 px-2 py-0.5 rounded-lg text-[10px] font-black bg-slate-950/80 text-white backdrop-blur flex items-center gap-1">
                            <i data-lucide="star" class="w-3 h-3 text-amber-400 fill-amber-400"></i>
                            <span>{{ $book->average_rating > 0 ? $book->average_rating : '5.0' }}</span>
                        </div>
                    </div>

                    <!-- Details -->
                    <div class="p-4 space-y-1.5">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-brand-600">{{ $book->category->name }}</span>
                        <h3 class="font-bold text-xs sm:text-sm text-slate-900 group-hover:text-brand-700 transition-colors line-clamp-2 leading-snug">
                            <a href="{{ route('catalog.show', $book->slug) }}">{{ $book->title }}</a>
                        </h3>
                        <p class="text-[11px] text-slate-500 truncate">{{ $book->author }}</p>
                    </div>
                </div>

                <!-- Footer Card -->
                <div class="p-4 pt-0">
                    <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                        <span class="text-[11px] font-bold {{ $book->stock_available > 0 ? 'text-emerald-700' : 'text-slate-400' }}">
                            {{ $book->stock_available > 0 ? 'Sisa ' . $book->stock_available : 'Stok Kosong' }}
                        </span>
                        <a href="{{ route('catalog.show', $book->slug) }}" class="font-bold text-brand-700 hover:text-brand-900 text-xs">
                            Detail &rarr;
                        </a>
                    </div>
                </div>

            </div>
            @endforeach
        </div>

    </div>
</section>

<!-- Categories Showcase Section -->
<section class="py-20 bg-white border-b border-slate-200/80">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="flex items-end justify-between mb-10">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-brand-600">Klasifikasi Topik</span>
                <h2 class="font-display text-2xl sm:text-3xl font-extrabold text-slate-900 mt-1 tracking-tight">Kategori Koleksi Populer</h2>
            </div>
            <a href="{{ route('catalog.index') }}" class="text-xs font-bold text-brand-700 hover:underline">
                Lihat Semua &rarr;
            </a>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
            @foreach($categories as $cat)
            <a href="{{ route('catalog.index', ['kategori' => $cat->slug]) }}" 
               class="group p-5 rounded-3xl border border-slate-200/80 hover:border-brand-500/70 bg-white hover:bg-brand-50/40 shadow-xs hover:shadow-md transition-all text-center flex flex-col items-center">
                <div class="w-14 h-14 rounded-2xl bg-brand-50 text-brand-600 group-hover:bg-brand-600 group-hover:text-white flex items-center justify-center transition-all duration-300 mb-3 shadow-inner">
                    <i data-lucide="{{ $cat->icon ?? 'book-open' }}" class="w-7 h-7"></i>
                </div>
                <h3 class="text-xs sm:text-sm font-bold text-slate-900 group-hover:text-brand-700 transition-colors line-clamp-1">
                    {{ $cat->name }}
                </h3>
                <span class="text-[11px] text-slate-400 mt-1">{{ $cat->books_count }} Judul Koleksi</span>
            </a>
            @endforeach
        </div>

    </div>
</section>

<!-- Call to Action Banner -->
<section class="py-20 bg-gradient-to-r from-navy-950 via-brand-950 to-slate-950 text-white relative overflow-hidden">
    <div class="absolute -right-20 -bottom-20 w-96 h-96 bg-brand-500/20 rounded-full blur-3xl pointer-events-none"></div>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-6 relative z-10">
        
        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-brand-400/20 text-brand-300 border border-brand-400/30 text-xs font-bold uppercase tracking-wider">
            Literasi Tanpa Batas
        </span>

        <h2 class="font-display text-3xl sm:text-5xl font-black tracking-tight text-white leading-tight">
            Mulai Petualangan Membaca Anda di Aksaraya
        </h2>

        <p class="text-sm sm:text-base text-slate-300 max-w-2xl mx-auto leading-relaxed">
            Daftarkan diri Anda hari ini dan peroleh akses gratis ke ribuan bahan pustaka terbaik serta kartu perpustakaan digital resmi.
        </p>

        <div class="flex flex-wrap items-center justify-center gap-4 pt-4">
            <a href="{{ route('register') }}" class="px-8 py-3.5 bg-gradient-to-r from-brand-500 to-teal-500 hover:from-brand-600 hover:to-teal-600 text-white font-bold text-sm rounded-xl shadow-glow hover:shadow-lg transition-all">
                Daftar Jadi Anggota Gratis
            </a>
            <a href="{{ route('catalog.index') }}" class="px-8 py-3.5 bg-white/10 hover:bg-white/20 text-white border border-white/20 font-bold text-sm rounded-xl backdrop-blur transition-all">
                Jelajahi Katalog Buku
            </a>
        </div>

    </div>
</section>
@endsection
