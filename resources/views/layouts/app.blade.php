<!DOCTYPE html>
<html lang="id" class="h-full scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Aksaraya - Perpustakaan Digital Nusantara Modern')</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN with custom config -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'system-ui', 'sans-serif'],
                        display: ['Outfit', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#f0fdfa',
                            100: '#ccfbf1',
                            200: '#99f6e4',
                            300: '#5eead4',
                            400: '#2dd4bf',
                            500: '#14b8a6',
                            600: '#0d9488',
                            700: '#0f766e',
                            800: '#115e59',
                            900: '#134e4a',
                            950: '#042f2e',
                        },
                        navy: {
                            50: '#f8fafc',
                            100: '#f1f5f9',
                            200: '#e2e8f0',
                            300: '#cbd5e1',
                            400: '#94a3b8',
                            500: '#64748b',
                            600: '#475569',
                            700: '#334155',
                            800: '#1e293b',
                            900: '#0f172a',
                            950: '#090d16',
                        },
                        indigo: {
                            50: '#eef2ff',
                            100: '#e0e7ff',
                            500: '#6366f1',
                            600: '#4f46e5',
                            700: '#4338ca',
                            800: '#3730a3',
                            900: '#312e81',
                            950: '#1e1b4b',
                        },
                        amber: {
                            50: '#fffbeb',
                            400: '#fbbf24',
                            500: '#f59e0b',
                            600: '#d97706',
                        }
                    },
                    boxShadow: {
                        'glow': '0 0 25px -5px rgba(13, 148, 136, 0.3)',
                        'glow-indigo': '0 0 25px -5px rgba(99, 102, 241, 0.3)',
                        'glow-amber': '0 0 25px -5px rgba(245, 158, 11, 0.35)',
                        'book': '0 20px 25px -5px rgba(15, 23, 42, 0.15), 0 8px 10px -6px rgba(15, 23, 42, 0.1)',
                        'book-hover': '0 25px 35px -5px rgba(15, 23, 42, 0.25), 0 12px 16px -6px rgba(13, 148, 136, 0.2)',
                    }
                }
            }
        }
    </script>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        [x-cloak] { display: none !important; }
        
        /* Modern subtle scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 9999px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        /* Glassmorphism utility */
        .glass-nav {
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
        }

        .glass-dark {
            background: rgba(15, 23, 42, 0.75);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }

        /* 3D Book spine reflection */
        .book-3d {
            transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
            transform-style: preserve-3d;
        }
        .book-3d:hover {
            transform: translateY(-8px) scale(1.02);
        }
    </style>
    @stack('styles')
</head>
<body class="flex flex-col min-h-screen bg-slate-50/60 text-slate-800 font-sans antialiased selection:bg-brand-500 selection:text-white" x-data="{ mobileMenuOpen: false }">

    @php
        $announcement = \App\Models\Setting::get('announcement');
        $hasAlerts = session('success') || session('error') || $errors->any();
    @endphp

    @if($announcement)
    <!-- Top Announcement Ribbon -->
    <div class="relative bg-gradient-to-r from-navy-950 via-brand-950 to-indigo-950 text-white text-xs py-2.5 px-4 text-center font-medium border-b border-brand-800/40 shadow-sm flex items-center justify-center gap-2 overflow-hidden">
        <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full bg-amber-400/20 text-amber-300 border border-amber-400/30 text-[10px] font-bold uppercase tracking-wider">
            <i data-lucide="sparkles" class="w-3 h-3 text-amber-300"></i>
            Warta
        </span>
        <span class="text-slate-200 tracking-wide">{{ $announcement }}</span>
    </div>
    @endif

    <!-- Global Modern Navbar -->
    <header class="sticky top-0 z-40 bg-white/95 backdrop-blur-xl border-b border-slate-200/80 shadow-xs transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 sm:h-20">
                
                <!-- Brand Logo & Identity -->
                <div class="flex items-center gap-6 xl:gap-8">
                    <a href="{{ route('home') }}" class="flex items-center gap-3 group shrink-0">
                        <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-brand-600 to-teal-500 p-[1.5px] shadow-sm group-hover:shadow-glow transition-all duration-300">
                            <div class="w-full h-full bg-slate-950 rounded-[14px] flex items-center justify-center text-white">
                                <i data-lucide="book-open-check" class="w-5 h-5 text-brand-400 group-hover:rotate-6 transition-transform"></i>
                            </div>
                        </div>
                        <div class="flex flex-col">
                            <div class="flex items-center gap-1.5">
                                <span class="font-display font-black text-xl sm:text-2xl text-slate-900 tracking-tight group-hover:text-brand-700 transition-colors">Aksaraya</span>
                                <span class="w-2 h-2 rounded-full bg-brand-500"></span>
                            </div>
                            <span class="text-[9px] font-bold tracking-widest text-brand-600 uppercase">Perpustakaan Digital</span>
                        </div>
                    </a>

                    <!-- Clean Main Navigation Links -->
                    <nav class="hidden lg:flex items-center gap-1 text-sm font-semibold text-slate-600">
                        <a href="{{ route('home') }}" class="px-3.5 py-2 rounded-xl transition-all {{ request()->routeIs('home') ? 'text-brand-700 font-bold bg-brand-50/80' : 'hover:text-slate-900 hover:bg-slate-100/70' }}">
                            Beranda
                        </a>
                        <a href="{{ route('catalog.index') }}" class="px-3.5 py-2 rounded-xl transition-all {{ request()->routeIs('catalog.index') && !request()->query('tipe') ? 'text-brand-700 font-bold bg-brand-50/80' : 'hover:text-slate-900 hover:bg-slate-100/70' }}">
                            Katalog Buku
                        </a>
                        <a href="{{ route('catalog.index', ['tipe' => 'ebook']) }}" class="px-3.5 py-2 rounded-xl transition-all flex items-center gap-1.5 {{ request()->query('tipe') === 'ebook' ? 'text-brand-700 font-bold bg-brand-50/80' : 'hover:text-slate-900 hover:bg-slate-100/70' }}">
                            <span>E-Book Digital</span>
                            <span class="px-1.5 py-0.5 text-[9px] font-extrabold bg-emerald-500 text-white rounded-md uppercase tracking-wider">Online</span>
                        </a>
                        <a href="{{ route('about') }}" class="px-3.5 py-2 rounded-xl transition-all {{ request()->routeIs('about') ? 'text-brand-700 font-bold bg-brand-50/80' : 'hover:text-slate-900 hover:bg-slate-100/70' }}">
                            Tentang Layanan
                        </a>
                    </nav>
                </div>

                <!-- Right Action Bar -->
                <div class="hidden md:flex items-center gap-3">
                    
                    <!-- Slim Search Shortcut Button -->
                    <a href="{{ route('catalog.index') }}" class="flex items-center gap-2.5 px-3 py-2 text-xs font-medium text-slate-500 bg-slate-100/80 hover:bg-slate-200/70 hover:text-slate-800 rounded-xl transition-all border border-slate-200/60" title="Cari buku">
                        <i data-lucide="search" class="w-3.5 h-3.5 text-slate-400"></i>
                        <span class="text-xs">Cari buku</span>
                    </a>

                    @auth
                        @if(auth()->user()->isStaff())
                            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-bold text-indigo-700 bg-indigo-50 border border-indigo-200/80 hover:bg-indigo-100/80 rounded-xl transition-all shadow-xs">
                                <i data-lucide="shield-check" class="w-4 h-4 text-indigo-600"></i>
                                <span>Panel Petugas</span>
                            </a>
                        @endif

                        <!-- Member Menu Dropdown -->
                        <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                            <button @click="open = !open" class="flex items-center gap-2.5 p-1.5 pr-3 rounded-2xl hover:bg-slate-100/80 border border-slate-200/80 bg-white transition-all shadow-xs">
                                <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-brand-600 to-teal-700 text-white flex items-center justify-center font-bold text-xs shadow-xs">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                                </div>
                                <div class="text-left hidden lg:block">
                                    <div class="text-xs font-bold text-slate-900 leading-snug">{{ Str::limit(auth()->user()->name, 15) }}</div>
                                    <div class="text-[10px] font-mono text-brand-600 font-semibold">{{ auth()->user()->member_number ?? ucfirst(auth()->user()->role) }}</div>
                                </div>
                                <i data-lucide="chevron-down" class="w-3.5 h-3.5 text-slate-400 transition-transform duration-200" :class="{ 'rotate-180': open }"></i>
                            </button>

                            <!-- Dropdown Box -->
                            <div x-show="open" x-cloak x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                                x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                                x-transition:leave="transition ease-in duration-150"
                                x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                                x-transition:leave-end="opacity-0 translate-y-2 scale-95"
                                class="absolute right-0 mt-2.5 w-64 bg-white rounded-2xl shadow-xl border border-slate-100 py-2.5 z-50">
                                
                                <div class="px-4 py-2.5 border-b border-slate-100">
                                    <p class="text-[11px] font-medium text-slate-400">Akun Anggota:</p>
                                    <p class="text-sm font-bold text-slate-900 truncate">{{ auth()->user()->name }}</p>
                                    <p class="text-[11px] text-brand-600 font-mono">{{ auth()->user()->email }}</p>
                                </div>

                                <div class="py-1.5">
                                    <a href="{{ route('member.dashboard') }}" class="flex items-center gap-3 px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-brand-50/70 hover:text-brand-700 transition-colors">
                                        <i data-lucide="layout-dashboard" class="w-4 h-4 text-slate-400"></i>
                                        <span>Dashboard Anggota</span>
                                    </a>
                                    <a href="{{ route('member.loans') }}" class="flex items-center gap-3 px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-brand-50/70 hover:text-brand-700 transition-colors">
                                        <i data-lucide="book-marked" class="w-4 h-4 text-slate-400"></i>
                                        <span>Peminjaman Saya</span>
                                    </a>
                                    <a href="{{ route('member.bookmarks') }}" class="flex items-center gap-3 px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-brand-50/70 hover:text-brand-700 transition-colors">
                                        <i data-lucide="bookmark" class="w-4 h-4 text-slate-400"></i>
                                        <span>Koleksi Favorit</span>
                                    </a>
                                    <a href="{{ route('member.shelves') }}" class="flex items-center gap-3 px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-brand-50/70 hover:text-brand-700 transition-colors">
                                        <i data-lucide="library" class="w-4 h-4 text-slate-400"></i>
                                        <span>Rak Buku Pribadi</span>
                                    </a>
                                    <a href="{{ route('member.card') }}" class="flex items-center gap-3 px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-brand-50/70 hover:text-brand-700 transition-colors">
                                        <i data-lucide="id-card" class="w-4 h-4 text-slate-400"></i>
                                        <span>Kartu Anggota Digital</span>
                                    </a>
                                    <a href="{{ route('member.profile') }}" class="flex items-center gap-3 px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-brand-50/70 hover:text-brand-700 transition-colors">
                                        <i data-lucide="settings" class="w-4 h-4 text-slate-400"></i>
                                        <span>Pengaturan Profil</span>
                                    </a>
                                </div>

                                <div class="border-t border-slate-100 pt-1.5">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="w-full flex items-center gap-3 px-4 py-2 text-xs font-bold text-rose-600 hover:bg-rose-50/80 transition-colors">
                                            <i data-lucide="log-out" class="w-4 h-4 text-rose-500"></i>
                                            <span>Keluar</span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="px-4 py-2 text-xs font-bold text-slate-700 hover:text-brand-700 transition-colors">
                            Masuk
                        </a>
                        <a href="{{ route('register') }}" class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-bold text-white bg-gradient-to-r from-brand-600 to-teal-700 hover:from-brand-700 hover:to-teal-800 rounded-xl shadow-xs hover:shadow-glow transition-all duration-200">
                            <i data-lucide="user-plus" class="w-3.5 h-3.5"></i>
                            <span>Daftar</span>
                        </a>
                    @endauth

                </div>

                <!-- Mobile Hamburger Button -->
                <div class="flex items-center gap-2 md:hidden">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="p-2 text-slate-600 hover:bg-slate-100 rounded-xl transition-colors">
                        <i x-show="!mobileMenuOpen" data-lucide="menu" class="w-6 h-6"></i>
                        <i x-show="mobileMenuOpen" x-cloak data-lucide="x" class="w-6 h-6"></i>
                    </button>
                </div>

            </div>
        </div>

        <!-- Mobile Drawer Menu -->
        <div x-show="mobileMenuOpen" x-cloak 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             class="md:hidden border-t border-slate-200/80 bg-white/95 backdrop-blur px-5 pt-3 pb-6 space-y-3">
            <a href="{{ route('home') }}" class="block px-3.5 py-2.5 rounded-xl text-sm font-semibold text-slate-700 hover:bg-slate-100">Beranda</a>
            <a href="{{ route('catalog.index') }}" class="block px-3.5 py-2.5 rounded-xl text-sm font-semibold text-slate-700 hover:bg-slate-100">Katalog Buku</a>
            <a href="{{ route('catalog.index', ['tipe' => 'ebook']) }}" class="block px-3.5 py-2.5 rounded-xl text-sm font-semibold text-slate-700 hover:bg-slate-100">E-Book Digital Online</a>
            <a href="{{ route('about') }}" class="block px-3.5 py-2.5 rounded-xl text-sm font-semibold text-slate-700 hover:bg-slate-100">Tentang Layanan</a>

            <div class="border-t border-slate-100 pt-3">
                @auth
                    <div class="px-3.5 py-3 bg-slate-50 rounded-2xl mb-3 flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-brand-600 text-white font-bold text-sm flex items-center justify-center">
                            {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                        </div>
                        <div>
                            <p class="font-bold text-sm text-slate-900">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-brand-600 font-mono">{{ auth()->user()->member_number ?? auth()->user()->email }}</p>
                        </div>
                    </div>

                    @if(auth()->user()->isStaff())
                    <a href="{{ route('admin.dashboard') }}" class="block px-3.5 py-2.5 text-xs font-bold text-indigo-700 bg-indigo-50 rounded-xl mb-2">Panel Petugas</a>
                    @endif

                    <a href="{{ route('member.dashboard') }}" class="block px-3.5 py-2 text-xs font-medium text-slate-700 hover:bg-slate-100 rounded-lg">Dashboard Anggota</a>
                    <a href="{{ route('member.loans') }}" class="block px-3.5 py-2 text-xs font-medium text-slate-700 hover:bg-slate-100 rounded-lg">Peminjaman Saya</a>
                    <a href="{{ route('member.card') }}" class="block px-3.5 py-2 text-xs font-medium text-slate-700 hover:bg-slate-100 rounded-lg">Kartu Anggota Digital</a>
                    <a href="{{ route('member.profile') }}" class="block px-3.5 py-2 text-xs font-medium text-slate-700 hover:bg-slate-100 rounded-lg">Pengaturan Profil</a>

                    <form method="POST" action="{{ route('logout') }}" class="mt-3">
                        @csrf
                        <button type="submit" class="w-full text-left px-3.5 py-2 text-xs font-bold text-rose-600 hover:bg-rose-50 rounded-lg">Keluar</button>
                    </form>
                @else
                    <div class="grid grid-cols-2 gap-2.5 pt-2">
                        <a href="{{ route('login') }}" class="w-full text-center py-2.5 text-xs font-bold border border-slate-300 rounded-xl hover:bg-slate-50">Masuk</a>
                        <a href="{{ route('register') }}" class="w-full text-center py-2.5 text-xs font-bold bg-brand-600 text-white rounded-xl shadow-glow">Daftar</a>
                    </div>
                @endauth
            </div>
        </div>
    </header>

    <!-- Global Toast / Alert Notifications -->
    @if($hasAlerts)
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-5 w-full">
        @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-transition class="mb-4 flex items-center justify-between p-4 bg-emerald-50/90 border border-emerald-200/90 rounded-2xl text-emerald-900 shadow-sm backdrop-blur">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-xl bg-emerald-500 text-white flex items-center justify-center shrink-0 shadow-xs">
                    <i data-lucide="check" class="w-4 h-4"></i>
                </div>
                <p class="text-xs sm:text-sm font-semibold">{{ session('success') }}</p>
            </div>
            <button @click="show = false" class="text-emerald-500 hover:text-emerald-800 p-1">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>
        @endif

        @if(session('error'))
        <div x-data="{ show: true }" x-show="show" x-transition class="mb-4 flex items-center justify-between p-4 bg-rose-50/90 border border-rose-200/90 rounded-2xl text-rose-900 shadow-sm backdrop-blur">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-xl bg-rose-500 text-white flex items-center justify-center shrink-0 shadow-xs">
                    <i data-lucide="alert-circle" class="w-4 h-4"></i>
                </div>
                <p class="text-xs sm:text-sm font-semibold">{{ session('error') }}</p>
            </div>
            <button @click="show = false" class="text-rose-500 hover:text-rose-800 p-1">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>
        @endif

        @if($errors->any())
        <div x-data="{ show: true }" x-show="show" class="mb-4 p-4 bg-amber-50/90 border border-amber-200 rounded-2xl text-amber-900 shadow-sm">
            <div class="flex items-start gap-3">
                <div class="w-8 h-8 rounded-xl bg-amber-500 text-white flex items-center justify-center shrink-0 mt-0.5">
                    <i data-lucide="alert-triangle" class="w-4 h-4"></i>
                </div>
                <div class="text-xs sm:text-sm">
                    <p class="font-bold">Mohon perhatikan data formulir Anda:</p>
                    <ul class="list-disc list-inside mt-1 space-y-0.5 text-xs text-amber-800">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        @endif
    </div>
    @endif

    <!-- Main Dynamic Content -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-navy-950 text-slate-300 border-t border-slate-800/80 {{ request()->routeIs('home') ? 'mt-0 border-t-0' : 'mt-24' }} relative overflow-hidden">
        <!-- Glow gradient in footer background -->
        <div class="absolute -top-32 -right-32 w-80 h-80 bg-brand-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-32 -left-32 w-80 h-80 bg-indigo-500/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 relative z-10">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12">
                
                <!-- Col 1: Brand & Tagline -->
                <div class="space-y-4 md:col-span-1">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-brand-500 to-teal-500 flex items-center justify-center text-white shadow-glow">
                            <i data-lucide="book-open-check" class="w-5 h-5"></i>
                        </div>
                        <span class="font-display font-extrabold text-2xl tracking-tight text-white">Aksaraya</span>
                    </div>
                    <p class="text-xs sm:text-sm text-slate-400 leading-relaxed">
                        {{ \App\Models\Setting::get('library_tagline', 'Perpustakaan Digital Nusantara Modern. Menghubungkan pembaca dengan khazanah literasi Indonesia tanpa batas jarak dan waktu.') }}
                    </p>
                    <div class="flex items-center gap-2.5 pt-2 text-slate-400">
                        <a href="#" class="w-9 h-9 rounded-xl bg-slate-900 hover:bg-brand-600 hover:text-white flex items-center justify-center transition-all border border-slate-800">
                            <i data-lucide="globe" class="w-4 h-4"></i>
                        </a>
                        <a href="mailto:{{ \App\Models\Setting::get('library_email', 'layanan@aksaraya.id') }}" class="w-9 h-9 rounded-xl bg-slate-900 hover:bg-brand-600 hover:text-white flex items-center justify-center transition-all border border-slate-800">
                            <i data-lucide="mail" class="w-4 h-4"></i>
                        </a>
                        <a href="tel:{{ \App\Models\Setting::get('library_phone', '+62 21 555-2026') }}" class="w-9 h-9 rounded-xl bg-slate-900 hover:bg-brand-600 hover:text-white flex items-center justify-center transition-all border border-slate-800">
                            <i data-lucide="phone" class="w-4 h-4"></i>
                        </a>
                    </div>
                </div>

                <!-- Col 2: Navigation Links -->
                <div class="space-y-4">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-brand-400 font-display">Koleksi & Eksplorasi</h3>
                    <ul class="space-y-2.5 text-xs text-slate-400">
                        <li><a href="{{ route('home') }}" class="hover:text-white transition-colors">Beranda Utama</a></li>
                        <li><a href="{{ route('catalog.index') }}" class="hover:text-white transition-colors">Semua Katalog Buku</a></li>
                        <li><a href="{{ route('catalog.index', ['tipe' => 'ebook']) }}" class="hover:text-white transition-colors">Koleksi E-Book Online</a></li>
                        <li><a href="{{ route('about') }}" class="hover:text-white transition-colors">Tata Tertib & Denda</a></li>
                    </ul>
                </div>

                <!-- Col 3: Keanggotaan -->
                <div class="space-y-4">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-brand-400 font-display">Layanan Anggota</h3>
                    <ul class="space-y-2.5 text-xs text-slate-400">
                        <li><a href="{{ route('register') }}" class="hover:text-white transition-colors">Registrasi Anggota Baru</a></li>
                        <li><a href="{{ route('login') }}" class="hover:text-white transition-colors">Masuk ke Portal Anggota</a></li>
                        @auth
                        <li><a href="{{ route('member.card') }}" class="hover:text-white transition-colors">Cetak Kartu Anggota Digital</a></li>
                        <li><a href="{{ route('member.loans') }}" class="hover:text-white transition-colors">Status Peminjaman Buku</a></li>
                        @endauth
                    </ul>
                </div>

                <!-- Col 4: Informasi Kontak & Layanan -->
                <div class="space-y-4">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-brand-400 font-display">Alamat & Jam Layanan</h3>
                    <div class="text-xs text-slate-400 space-y-2.5">
                        <p class="flex items-start gap-2.5">
                            <i data-lucide="map-pin" class="w-4 h-4 text-brand-400 shrink-0 mt-0.5"></i>
                            <span>{{ \App\Models\Setting::get('library_address', 'Jl. Merdeka Literasi No. 45, Jakarta') }}</span>
                        </p>
                        <p class="flex items-center gap-2.5">
                            <i data-lucide="phone" class="w-4 h-4 text-brand-400 shrink-0"></i>
                            <span>{{ \App\Models\Setting::get('library_phone', '+62 21 555-2026') }}</span>
                        </p>
                        <p class="flex items-center gap-2.5">
                            <i data-lucide="clock" class="w-4 h-4 text-emerald-400 shrink-0"></i>
                            <span>Senin - Sabtu: 08.00 - 17.00 WIB</span>
                        </p>
                        <div class="pt-2">
                            <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-slate-900 border border-slate-800 text-[11px] text-slate-300">
                                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                                <span>Katalog & E-Book Aktif 24 Jam</span>
                            </span>
                        </div>
                    </div>
                </div>

            </div>

            <div class="mt-14 pt-8 border-t border-slate-800/80 text-xs text-slate-500 flex flex-col sm:flex-row items-center justify-between gap-4">
                <p>&copy; {{ date('Y') }} {{ config('app.name', 'Aksaraya') }}. Seluruh hak cipta dilindungi.</p>
                <div class="flex items-center gap-4 text-slate-400">
                    <span class="text-[11px]">Dibuat dengan Laravel & Tailwind CSS</span>
                </div>
            </div>
        </div>
    </footer>

    <!-- Initialize Lucide Icons -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (window.lucide) {
                lucide.createIcons();
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
