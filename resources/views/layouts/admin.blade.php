<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Admin Panel') - Panel Pengelola Aksaraya</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS -->
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
                            800: '#1e293b',
                            900: '#0f172a',
                            950: '#090d16',
                        }
                    },
                    boxShadow: {
                        'glow': '0 0 20px -3px rgba(13, 148, 136, 0.35)',
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
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 9999px; }
    </style>
    @stack('styles')
</head>
<body class="h-full antialiased font-sans text-slate-800" x-data="{ sidebarOpen: false }">

    <div class="min-h-full flex">
        <!-- Sidebar Desktop -->
        <aside class="hidden lg:flex lg:flex-col lg:w-72 lg:fixed lg:inset-y-0 bg-navy-950 border-r border-slate-800 text-slate-300 z-50">
            
            <!-- Brand -->
            <div class="flex items-center gap-3 px-6 h-20 border-b border-slate-800/80 bg-slate-950/60 shrink-0">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-brand-500 to-teal-400 p-[1.5px] shadow-glow shrink-0">
                    <div class="w-full h-full bg-navy-950 rounded-[10px] flex items-center justify-center text-white">
                        <i data-lucide="book-open-check" class="w-5 h-5 text-brand-400"></i>
                    </div>
                </div>
                <div class="flex flex-col items-start justify-center">
                    <h1 class="font-display font-extrabold text-white text-lg tracking-tight leading-none mb-1">Aksaraya</h1>
                    <span class="inline-block text-[9px] font-bold tracking-wider uppercase px-2 py-0.5 rounded bg-brand-950 text-brand-400 border border-brand-800/80 font-mono leading-tight whitespace-nowrap">
                        {{ auth()->user()->role === 'admin' ? 'Administrator' : 'Staf Petugas' }}
                    </span>
                </div>
            </div>

            <!-- Navigation Links -->
            <nav class="flex-1 px-4 py-6 space-y-1.5 overflow-y-auto">
                <div class="px-3 pb-2 text-[10px] font-bold uppercase tracking-wider text-slate-400 font-mono">
                    Sirkulasi & Layanan
                </div>

                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-gradient-to-r from-brand-600 to-teal-700 text-white shadow-glow' : 'text-slate-400 hover:text-white hover:bg-slate-900' }}">
                    <i data-lucide="layout-dashboard" class="w-4 h-4"></i>
                    <span>Dashboard Utama</span>
                </a>

                @php
                    $pendingCount = \App\Models\Loan::where('status', 'pending')->count();
                @endphp

                <a href="{{ route('admin.loans.index') }}" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all {{ request()->routeIs('admin.loans.index') ? 'bg-gradient-to-r from-brand-600 to-teal-700 text-white shadow-glow' : 'text-slate-400 hover:text-white hover:bg-slate-900' }}">
                    <div class="flex items-center gap-3">
                        <i data-lucide="arrow-left-right" class="w-4 h-4"></i>
                        <span>Sirkulasi Peminjaman</span>
                    </div>
                    @if($pendingCount > 0)
                    <span class="px-2 py-0.5 text-[10px] font-black rounded-full bg-amber-400 text-slate-950 animate-pulse">
                        {{ $pendingCount }}
                    </span>
                    @endif
                </a>

                <a href="{{ route('admin.loans.create') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all {{ request()->routeIs('admin.loans.create') ? 'bg-gradient-to-r from-brand-600 to-teal-700 text-white shadow-glow' : 'text-slate-400 hover:text-white hover:bg-slate-900' }}">
                    <i data-lucide="plus-circle" class="w-4 h-4"></i>
                    <span>Peminjaman Manual di Meja</span>
                </a>

                <div class="pt-5 px-3 pb-2 text-[10px] font-bold uppercase tracking-wider text-slate-400 font-mono">
                    Katalog & Koleksi
                </div>

                <a href="{{ route('admin.books.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all {{ request()->routeIs('admin.books.*') ? 'bg-gradient-to-r from-brand-600 to-teal-700 text-white shadow-glow' : 'text-slate-400 hover:text-white hover:bg-slate-900' }}">
                    <i data-lucide="book" class="w-4 h-4"></i>
                    <span>Katalog Buku & E-Book</span>
                </a>

                <a href="{{ route('admin.categories.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all {{ request()->routeIs('admin.categories.*') ? 'bg-gradient-to-r from-brand-600 to-teal-700 text-white shadow-glow' : 'text-slate-400 hover:text-white hover:bg-slate-900' }}">
                    <i data-lucide="tags" class="w-4 h-4"></i>
                    <span>Kategori & Rak</span>
                </a>

                <div class="pt-5 px-3 pb-2 text-[10px] font-bold uppercase tracking-wider text-slate-400 font-mono">
                    Pengguna & Rekapitulasi
                </div>

                <a href="{{ route('admin.members.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all {{ request()->routeIs('admin.members.*') ? 'bg-gradient-to-r from-brand-600 to-teal-700 text-white shadow-glow' : 'text-slate-400 hover:text-white hover:bg-slate-900' }}">
                    <i data-lucide="users" class="w-4 h-4"></i>
                    <span>Kelola Anggota</span>
                </a>

                <a href="{{ route('admin.reports.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all {{ request()->routeIs('admin.reports.*') ? 'bg-gradient-to-r from-brand-600 to-teal-700 text-white shadow-glow' : 'text-slate-400 hover:text-white hover:bg-slate-900' }}">
                    <i data-lucide="file-bar-chart" class="w-4 h-4"></i>
                    <span>Laporan & Cetak Lembar</span>
                </a>

                @if(auth()->user()->role === 'admin')
                <div class="pt-5 px-3 pb-2 text-[10px] font-bold uppercase tracking-wider text-slate-400 font-mono">
                    Konfigurasi
                </div>
                <a href="{{ route('admin.settings.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all {{ request()->routeIs('admin.settings.*') ? 'bg-gradient-to-r from-brand-600 to-teal-700 text-white shadow-glow' : 'text-slate-400 hover:text-white hover:bg-slate-900' }}">
                    <i data-lucide="sliders" class="w-4 h-4"></i>
                    <span>Pengaturan Sistem</span>
                </a>
                @endif
            </nav>

            <!-- Bottom User Pill -->
            <div class="p-4 border-t border-slate-800 bg-slate-950/40">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3 overflow-hidden">
                        <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-brand-600 to-teal-700 text-white flex items-center justify-center font-bold text-xs shrink-0">
                            {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                        </div>
                        <div class="truncate">
                            <p class="text-xs font-bold text-white truncate">{{ auth()->user()->name }}</p>
                            <p class="text-[10px] text-brand-400 font-mono truncate">{{ auth()->user()->email }}</p>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="p-2 text-slate-400 hover:text-rose-400 hover:bg-slate-900 rounded-lg transition-colors" title="Keluar">
                            <i data-lucide="log-out" class="w-4 h-4"></i>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="lg:pl-72 flex flex-col flex-1 w-full min-w-0">
            
            <!-- Admin Top Navbar -->
            <header class="sticky top-0 z-40 bg-white/95 backdrop-blur border-b border-slate-200/90 h-20 flex items-center justify-between px-4 sm:px-8">
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = true" class="lg:hidden p-2 text-slate-600 hover:bg-slate-100 rounded-xl">
                        <i data-lucide="menu" class="w-6 h-6"></i>
                    </button>
                    <div>
                        <h2 class="font-display text-lg sm:text-xl font-extrabold text-slate-900 leading-tight">@yield('page-title', 'Dashboard Petugas')</h2>
                        <p class="text-xs text-slate-400 hidden sm:block">Perpustakaan Digital Aksaraya</p>
                    </div>
                </div>

                <div class="flex items-center gap-3.5">
                    <a href="{{ route('home') }}" target="_blank" class="inline-flex items-center gap-2 px-3.5 py-2 text-xs font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-xl transition-all">
                        <i data-lucide="external-link" class="w-4 h-4 text-slate-500"></i>
                        <span class="hidden sm:inline">Portal Publik</span>
                    </a>

                    <div class="w-px h-6 bg-slate-200"></div>

                    <div class="flex items-center gap-2 text-xs font-semibold text-slate-600 bg-emerald-50 text-emerald-800 px-3 py-1.5 rounded-xl border border-emerald-200">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span class="hidden sm:inline">Layanan Aktif</span>
                    </div>
                </div>
            </header>

            <!-- Alerts Banner -->
            <div class="px-4 sm:px-8 pt-6">
                @if(session('success'))
                <div x-data="{ show: true }" x-show="show" class="mb-4 flex items-center justify-between p-4 bg-emerald-50 border border-emerald-200 rounded-2xl text-emerald-900 shadow-sm">
                    <div class="flex items-center gap-3">
                        <i data-lucide="check-circle-2" class="w-5 h-5 text-emerald-600 shrink-0"></i>
                        <p class="text-xs sm:text-sm font-semibold">{{ session('success') }}</p>
                    </div>
                    <button @click="show = false" class="text-emerald-500 hover:text-emerald-800 p-1">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                </div>
                @endif

                @if(session('error'))
                <div x-data="{ show: true }" x-show="show" class="mb-4 flex items-center justify-between p-4 bg-rose-50 border border-rose-200 rounded-2xl text-rose-900 shadow-sm">
                    <div class="flex items-center gap-3">
                        <i data-lucide="alert-circle" class="w-5 h-5 text-rose-600 shrink-0"></i>
                        <p class="text-xs sm:text-sm font-semibold">{{ session('error') }}</p>
                    </div>
                    <button @click="show = false" class="text-rose-500 hover:text-rose-800 p-1">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                </div>
                @endif

                @if($errors->any())
                <div x-data="{ show: true }" x-show="show" class="mb-4 p-4 bg-amber-50 border border-amber-200 rounded-2xl text-amber-900 shadow-sm">
                    <div class="flex items-start gap-3">
                        <i data-lucide="alert-triangle" class="w-5 h-5 text-amber-600 shrink-0 mt-0.5"></i>
                        <div class="text-xs sm:text-sm">
                            <p class="font-bold">Mohon periksa input formulir:</p>
                            <ul class="list-disc list-inside mt-1 space-y-0.5">
                                @foreach($errors->all() as $err)
                                    <li>{{ $err }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Page Main Body -->
            <main class="flex-1 px-4 sm:px-8 pb-14">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Mobile Drawer -->
    <div x-show="sidebarOpen" x-cloak class="lg:hidden fixed inset-0 z-50 flex">
        <div @click="sidebarOpen = false" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm"></div>
        <div class="relative flex-1 flex flex-col max-w-xs w-full bg-navy-950 text-slate-300 p-5">
            <div class="flex items-center justify-between pb-4 border-b border-slate-800">
                <span class="font-display font-bold text-white text-base">Menu Petugas</span>
                <button @click="sidebarOpen = false" class="text-slate-400 hover:text-white p-1">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            <nav class="flex-1 py-4 space-y-1.5 overflow-y-auto">
                <a href="{{ route('admin.dashboard') }}" class="block px-3.5 py-2 text-xs font-semibold text-slate-300 hover:bg-slate-900 rounded-xl">Dashboard Utama</a>
                <a href="{{ route('admin.loans.index') }}" class="block px-3.5 py-2 text-xs font-semibold text-slate-300 hover:bg-slate-900 rounded-xl">Sirkulasi Pinjaman</a>
                <a href="{{ route('admin.loans.create') }}" class="block px-3.5 py-2 text-xs font-semibold text-slate-300 hover:bg-slate-900 rounded-xl">Peminjaman di Meja</a>
                <a href="{{ route('admin.books.index') }}" class="block px-3.5 py-2 text-xs font-semibold text-slate-300 hover:bg-slate-900 rounded-xl">Katalog Buku</a>
                <a href="{{ route('admin.categories.index') }}" class="block px-3.5 py-2 text-xs font-semibold text-slate-300 hover:bg-slate-900 rounded-xl">Kategori & Rak</a>
                <a href="{{ route('admin.members.index') }}" class="block px-3.5 py-2 text-xs font-semibold text-slate-300 hover:bg-slate-900 rounded-xl">Kelola Anggota</a>
                <a href="{{ route('admin.reports.index') }}" class="block px-3.5 py-2 text-xs font-semibold text-slate-300 hover:bg-slate-900 rounded-xl">Laporan</a>
                @if(auth()->user()->role === 'admin')
                <a href="{{ route('admin.settings.index') }}" class="block px-3.5 py-2 text-xs font-semibold text-slate-300 hover:bg-slate-900 rounded-xl">Pengaturan</a>
                @endif
                <div class="border-t border-slate-800 pt-3 mt-3">
                    <a href="{{ route('home') }}" class="block px-3.5 py-2 text-xs font-bold text-brand-400">Portal Publik &rarr;</a>
                </div>
            </nav>
        </div>
    </div>

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
