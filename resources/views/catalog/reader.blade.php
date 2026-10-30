<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-950">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Membaca E-Book: {{ $book->title }} - Aksaraya</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .font-display { font-family: 'Outfit', sans-serif; }
        .watermark-overlay {
            pointer-events: none;
            background-image: repeating-linear-gradient(
                -45deg,
                rgba(15, 23, 42, 0.05),
                rgba(15, 23, 42, 0.05) 120px,
                transparent 120px,
                transparent 240px
            );
        }
    </style>
</head>
<body class="h-full flex flex-col bg-slate-950 text-slate-100 overflow-hidden select-none"
      x-data="ebookReader({
          bookId: {{ $book->id }},
          initialPage: {{ $history ? $history->last_page : 1 }},
          totalPages: {{ $history ? $history->total_pages : 50 }},
          isCompleted: {{ $history && $history->is_completed ? 'true' : 'false' }},
          updateUrl: '{{ route('catalog.progress', $book->id) }}',
          userEmail: '{{ auth()->check() ? auth()->user()->email : 'Tamu Aksaraya' }}',
          userName: '{{ auth()->check() ? auth()->user()->name : 'Guest' }}'
      })">

    <!-- Top Reader Navigation Bar -->
    <header class="h-16 bg-slate-900/95 backdrop-blur border-b border-slate-800 px-4 sm:px-6 flex items-center justify-between shrink-0 z-20">
        
        <div class="flex items-center gap-3 sm:gap-4">
            <a href="{{ route('catalog.show', $book->slug) }}" class="p-2 text-slate-400 hover:text-white hover:bg-slate-800 rounded-xl transition-all flex items-center gap-1.5 text-xs font-bold">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                <span class="hidden sm:inline">Kembali</span>
            </a>

            <div class="h-5 w-px bg-slate-800"></div>

            <div class="truncate max-w-[180px] sm:max-w-md">
                <h1 class="font-display text-xs sm:text-sm font-bold text-white truncate">{{ $book->title }}</h1>
                <p class="text-[11px] text-slate-400 truncate">{{ $book->author }} &bull; <span class="text-teal-400 font-medium">{{ $book->category->name }}</span></p>
            </div>
        </div>

        <!-- Controls: Page, Mode, & Progress Tracker -->
        <div class="flex items-center gap-2 sm:gap-3">
            <!-- Reader Progress Tracker -->
            <div class="flex items-center gap-2 px-3 py-1.5 bg-slate-800/90 border border-slate-700 rounded-xl text-xs">
                <span class="text-slate-400 hidden sm:inline">Hal:</span>
                <input type="number" min="1" :max="totalPages" x-model.number="currentPage" @change="saveProgress()"
                       class="w-12 bg-slate-900 border border-slate-700 rounded px-1.5 py-0.5 text-center font-bold text-teal-300 text-xs focus:ring-1 focus:ring-teal-400">
                <span class="text-slate-400">/ <span x-text="totalPages"></span></span>
                
                <button @click="markComplete()" :class="isCompleted ? 'bg-emerald-600 text-white' : 'bg-slate-700 text-slate-300 hover:bg-slate-600'" 
                        class="ml-2 px-2.5 py-1 rounded-lg text-[10px] font-bold transition-all flex items-center gap-1"
                        :title="isCompleted ? 'Sudah Selesai Dibaca' : 'Tandai Selesai Membaca'">
                    <i data-lucide="check" class="w-3 h-3"></i>
                    <span class="hidden md:inline" x-text="isCompleted ? 'Selesai Dibaca' : 'Tandai Selesai'"></span>
                </button>
            </div>

            <!-- Theme Mode Toggle -->
            <div class="flex items-center bg-slate-800/80 p-0.5 rounded-xl border border-slate-700">
                <button @click="mode = 'normal'" :class="mode === 'normal' ? 'bg-slate-700 text-white' : 'text-slate-400 hover:text-white'" class="p-1.5 rounded-lg text-xs" title="Mode Normal (Terang)">
                    <i data-lucide="sun" class="w-3.5 h-3.5"></i>
                </button>
                <button @click="mode = 'sepia'" :class="mode === 'sepia' ? 'bg-amber-900/60 text-amber-200' : 'text-slate-400 hover:text-white'" class="p-1.5 rounded-lg text-xs" title="Mode Sepia">
                    <i data-lucide="coffee" class="w-3.5 h-3.5"></i>
                </button>
                <button @click="mode = 'dark'" :class="mode === 'dark' ? 'bg-slate-900 text-teal-300' : 'text-slate-400 hover:text-white'" class="p-1.5 rounded-lg text-xs" title="Mode Gelap">
                    <i data-lucide="moon" class="w-3.5 h-3.5"></i>
                </button>
            </div>

            @php
                $pdfUrl = $book->ebook_file ? asset('storage/' . $book->ebook_file) : asset('storage/ebooks/sample-ebook.pdf');
            @endphp

            <a href="{{ $pdfUrl }}" download class="p-2 text-slate-400 hover:text-white hover:bg-slate-800 rounded-xl transition-all" title="Unduh Salinan PDF">
                <i data-lucide="download" class="w-4 h-4"></i>
            </a>

            <button onclick="toggleFullscreen()" class="p-2 text-slate-400 hover:text-white hover:bg-slate-800 rounded-xl transition-all" title="Layar Penuh">
                <i data-lucide="maximize" class="w-4 h-4"></i>
            </button>
        </div>
    </header>

    <!-- Reader Canvas -->
    <main class="flex-1 relative flex items-center justify-center p-2 sm:p-5 overflow-hidden transition-colors"
          :class="{
              'bg-slate-900/60': mode === 'normal',
              'bg-[#2c2217]': mode === 'sepia',
              'bg-[#0b0f19]': mode === 'dark'
          }">
        
        <div class="w-full h-full max-w-5xl bg-white rounded-2xl shadow-2xl overflow-hidden border border-slate-800 relative flex flex-col"
             :class="{
                 'filter sepia-[.30]': mode === 'sepia',
                 'filter invert-[.90] hue-rotate-180': mode === 'dark'
             }">
            
            <!-- Dynamic Anti-piracy Watermark -->
            <div class="absolute inset-0 z-10 pointer-events-none watermark-overlay flex flex-col justify-around overflow-hidden select-none opacity-20">
                @for($i = 0; $i < 6; $i++)
                <div class="flex justify-around text-slate-900 font-mono text-[11px] font-bold tracking-widest uppercase rotate-[-25deg]">
                    <span>Aksaraya &bull; {{ auth()->check() ? auth()->user()->member_number : 'GUEST' }}</span>
                    <span>{{ auth()->check() ? auth()->user()->email : 'layanan@aksaraya.id' }}</span>
                </div>
                @endfor
            </div>

            <iframe 
                :src="'{{ $pdfUrl }}#page=' + currentPage + '&toolbar=1&navpanes=0&scrollbar=1'" 
                class="w-full h-full border-0 relative z-0"
                title="E-Book Reader: {{ $book->title }}">
                <div class="p-8 text-center text-slate-800 space-y-3">
                    <p class="font-bold text-base">Peramban Anda memerlukan plugin pembaca PDF.</p>
                    <a href="{{ $pdfUrl }}" target="_blank" class="px-5 py-2.5 bg-teal-600 text-white font-bold rounded-xl text-xs inline-block shadow">
                        Buka Dokumen PDF di Tab Baru
                    </a>
                </div>
            </iframe>
        </div>
    </main>

    <!-- Save Notification Toast -->
    <div x-show="toast" x-cloak 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 translate-y-2"
         class="fixed bottom-6 right-6 z-50 bg-slate-900 text-teal-400 border border-teal-500/30 px-4 py-2.5 rounded-2xl shadow-2xl text-xs flex items-center gap-2">
        <i data-lucide="bookmark-check" class="w-4 h-4 text-teal-400"></i>
        <span x-text="toastMessage"></span>
    </div>

    <script>
        function ebookReader(config) {
            return {
                bookId: config.bookId,
                currentPage: config.initialPage,
                totalPages: config.totalPages,
                isCompleted: config.isCompleted,
                mode: 'normal',
                toast: false,
                toastMessage: '',
                showToast(msg) {
                    this.toastMessage = msg;
                    this.toast = true;
                    setTimeout(() => { this.toast = false; }, 2500);
                },
                saveProgress() {
                    if (this.currentPage < 1) this.currentPage = 1;
                    if (this.currentPage > this.totalPages) this.currentPage = this.totalPages;
                    
                    fetch(config.updateUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            last_page: this.currentPage,
                            total_pages: this.totalPages,
                            is_completed: this.isCompleted
                        })
                    }).then(res => res.json()).then(data => {
                        if (data.success) {
                            this.showToast('Halaman ' + this.currentPage + ' tersimpan otomatis');
                        }
                    }).catch(err => {});
                },
                markComplete() {
                    this.isCompleted = !this.isCompleted;
                    this.saveProgress();
                    this.showToast(this.isCompleted ? 'Buku ditandai selesai dibaca!' : 'Status selesai dibatalkan');
                }
            };
        }

        document.addEventListener('DOMContentLoaded', function () {
            if (window.lucide) {
                lucide.createIcons();
            }
        });

        function toggleFullscreen() {
            if (!document.fullscreenElement) {
                document.documentElement.requestFullscreen().catch(err => {});
            } else {
                if (document.exitFullscreen) {
                    document.exitFullscreen().catch(err => {});
                }
            }
        }
    </script>
</body>
</html>
