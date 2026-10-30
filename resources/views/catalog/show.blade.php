@extends('layouts.app')

@section('title', $book->title . ' - Aksaraya')

@section('content')
<!-- Breadcrumbs -->
<div class="bg-white border-b border-slate-200/80 py-3.5">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="flex items-center gap-2 text-xs text-slate-500 font-medium">
            <a href="{{ route('home') }}" class="hover:text-brand-700 transition-colors">Beranda</a>
            <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-slate-400"></i>
            <a href="{{ route('catalog.index') }}" class="hover:text-brand-700 transition-colors">Katalog</a>
            <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-slate-400"></i>
            <a href="{{ route('catalog.index', ['kategori' => $book->category->slug]) }}" class="hover:text-brand-700 transition-colors">{{ $book->category->name }}</a>
            <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-slate-400"></i>
            <span class="text-slate-900 font-bold truncate max-w-xs">{{ $book->title }}</span>
        </nav>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
        
        <!-- Left 4 Cols: Book Cover & Sticky Action Card -->
        <div class="lg:col-span-4 space-y-6">
            <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-book sticky top-28 space-y-6">
                
                <!-- Book Cover Presentation -->
                <div class="relative aspect-[3/4] rounded-2xl overflow-hidden bg-slate-100 shadow-2xl group">
                    <img src="{{ $book->cover_image ?? 'https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?auto=format&fit=crop&w=800&q=80' }}" 
                         alt="{{ $book->title }}" 
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    
                    @if($book->has_ebook)
                    <span class="absolute top-3 left-3 px-3 py-1 rounded-lg text-xs font-extrabold bg-gradient-to-r from-emerald-600 to-teal-600 text-white shadow-md flex items-center gap-1.5">
                        <i data-lucide="file-text" class="w-3.5 h-3.5"></i>
                        E-Book Digital
                    </span>
                    @endif
                </div>

                <!-- Primary Action Buttons -->
                <div class="space-y-3">
                    @if($book->has_ebook)
                    <a href="{{ route('catalog.read', $book->slug) }}" class="w-full py-3.5 px-4 bg-gradient-to-r from-emerald-600 to-teal-700 hover:from-emerald-700 hover:to-teal-800 text-white font-bold text-xs sm:text-sm rounded-xl shadow-glow hover:shadow-lg transition-all flex items-center justify-center gap-2">
                        <i data-lucide="book-open" class="w-4 h-4"></i>
                        <span>Baca E-Book Online Sekarang</span>
                    </a>
                    @endif

                    <!-- Loan Action Modal Trigger -->
                    @auth
                        @if($hasActiveLoan)
                        <div class="p-3.5 bg-amber-50 border border-amber-200 text-amber-900 rounded-2xl text-xs flex items-center gap-2.5">
                            <i data-lucide="info" class="w-4 h-4 text-amber-600 shrink-0"></i>
                            <span class="font-medium">Anda memiliki peminjaman aktif atau pengajuan untuk buku ini.</span>
                        </div>
                        @elseif($book->stock_available > 0)
                        <div x-data="{ openLoanModal: false }">
                            <button @click="openLoanModal = true" type="button" class="w-full py-3.5 px-4 bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs sm:text-sm rounded-xl shadow-md shadow-brand-600/20 hover:shadow-lg transition-all flex items-center justify-center gap-2">
                                <i data-lucide="plus-circle" class="w-4 h-4"></i>
                                <span>Ajukan Pinjam Buku Fisik</span>
                            </button>

                            <!-- Loan Confirmation Modal -->
                            <div x-show="openLoanModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
                                <div @click="openLoanModal = false" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm"></div>
                                <div class="relative bg-white rounded-3xl max-w-md w-full p-6 sm:p-8 shadow-2xl z-10 space-y-5 border border-slate-100">
                                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                                        <div>
                                            <h3 class="font-display font-bold text-slate-900 text-base">Reservasi Peminjaman Buku</h3>
                                            <p class="text-[11px] text-slate-400">Konfirmasi pengajuan peminjaman bahan pustaka</p>
                                        </div>
                                        <button @click="openLoanModal = false" class="text-slate-400 hover:text-slate-600 p-1">
                                            <i data-lucide="x" class="w-5 h-5"></i>
                                        </button>
                                    </div>

                                    <div class="p-4 bg-slate-50 rounded-2xl space-y-2.5 text-xs text-slate-600">
                                        <div class="flex justify-between">
                                            <span class="text-slate-400">Judul Buku:</span>
                                            <span class="font-bold text-slate-900 text-right max-w-[200px] truncate">{{ $book->title }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-slate-400">Durasi Peminjaman:</span>
                                            <span class="font-bold text-brand-700">{{ $loanPeriodDays }} Hari Kalender</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-slate-400">Lokasi Rak Pengambilan:</span>
                                            <span class="font-bold text-slate-900">{{ $book->shelf_location }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-slate-400">Ketentuan Denda Keterlambatan:</span>
                                            <span class="font-bold text-rose-600">Rp {{ number_format($dailyFineRate, 0, ',', '.') }} / hari</span>
                                        </div>
                                    </div>

                                    <form method="POST" action="{{ route('member.loan.store') }}" class="space-y-4">
                                        @csrf
                                        <input type="hidden" name="book_id" value="{{ $book->id }}">

                                        <div>
                                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Catatan Tambahan untuk Petugas (Opsional)</label>
                                            <textarea name="notes" rows="2" placeholder="Contoh: Rencana diambil pukul 14.00 WIB di meja sirkulasi" class="w-full text-xs p-3 bg-slate-50 border border-slate-300 rounded-xl focus:ring-2 focus:ring-brand-500"></textarea>
                                        </div>

                                        <div class="flex items-center justify-end gap-2.5 pt-2">
                                            <button @click="openLoanModal = false" type="button" class="px-4 py-2.5 text-xs font-semibold text-slate-600 hover:bg-slate-100 rounded-xl">Batal</button>
                                            <button type="submit" class="px-5 py-2.5 bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold rounded-xl shadow-glow">Ajukan Permohonan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @else
                        <div class="p-3 bg-rose-50 border border-rose-200 text-rose-700 rounded-xl text-xs text-center font-bold">
                            Stok buku fisik saat ini sedang habis dipinjam.
                        </div>
                        @endif

                        <!-- Bookmark Button -->
                        <div class="grid grid-cols-2 gap-2">
                            <form action="{{ route('catalog.bookmark', $book->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full py-2.5 px-3 bg-slate-100 hover:bg-slate-200/80 text-slate-700 font-bold text-xs rounded-xl transition-colors flex items-center justify-center gap-1.5">
                                    <i data-lucide="bookmark" class="w-3.5 h-3.5 {{ $isBookmarked ? 'fill-rose-500 text-rose-500' : '' }}"></i>
                                    <span>{{ $isBookmarked ? 'Tersimpan' : 'Favorit' }}</span>
                                </button>
                            </form>

                            <!-- Add to Custom Shelf Dropdown -->
                            <div x-data="{ shelfMenu: false }" class="relative">
                                <button @click="shelfMenu = !shelfMenu" type="button" class="w-full py-2.5 px-3 bg-slate-100 hover:bg-slate-200/80 text-slate-700 font-bold text-xs rounded-xl transition-colors flex items-center justify-center gap-1.5">
                                    <i data-lucide="library" class="w-3.5 h-3.5 text-brand-600"></i>
                                    <span>Rak Saya</span>
                                </button>

                                <div x-show="shelfMenu" @click.outside="shelfMenu = false" x-cloak class="absolute bottom-full mb-2 left-0 w-48 bg-white rounded-2xl shadow-xl border border-slate-100 p-2 z-20 space-y-1 text-xs">
                                    <p class="text-[10px] uppercase font-bold text-slate-400 px-2 py-1">Pilih Rak Buku</p>
                                    <form method="POST" action="{{ route('member.shelves.update', $book->id) }}">
                                        @csrf
                                        <input type="hidden" name="shelf_type" value="want_to_read">
                                        <button type="submit" class="w-full text-left px-2.5 py-1.5 hover:bg-brand-50 rounded-lg text-slate-700 hover:text-brand-700 font-medium flex items-center gap-2">
                                            <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                                            <span>Ingin Dibaca</span>
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('member.shelves.update', $book->id) }}">
                                        @csrf
                                        <input type="hidden" name="shelf_type" value="currently_reading">
                                        <button type="submit" class="w-full text-left px-2.5 py-1.5 hover:bg-amber-50 rounded-lg text-slate-700 hover:text-amber-700 font-medium flex items-center gap-2">
                                            <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                                            <span>Sedang Dibaca</span>
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('member.shelves.update', $book->id) }}">
                                        @csrf
                                        <input type="hidden" name="shelf_type" value="already_read">
                                        <button type="submit" class="w-full text-left px-2.5 py-1.5 hover:bg-emerald-50 rounded-lg text-slate-700 hover:text-emerald-700 font-medium flex items-center gap-2">
                                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                            <span>Selesai Dibaca</span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="p-4 bg-brand-50/70 border border-brand-100 rounded-2xl text-center space-y-2">
                            <p class="text-xs text-brand-900 font-medium">Ingin meminjam buku fisik ini?</p>
                            <a href="{{ route('login') }}" class="inline-block w-full py-2.5 px-4 bg-brand-600 hover:bg-brand-700 text-white rounded-xl text-xs font-bold shadow-xs transition-colors">
                                Masuk untuk Meminjam
                            </a>
                        </div>
                    @endauth
                </div>

                <!-- Stock & Location Micro-Indicator -->
                <div class="pt-5 border-t border-slate-100 space-y-3 text-xs">
                    <div>
                        <div class="flex items-center justify-between text-slate-600 mb-1.5 font-medium">
                            <span class="flex items-center gap-1.5">
                                <i data-lucide="layers" class="w-3.5 h-3.5 text-slate-400"></i>
                                <span>Ketersediaan Stok Fisik</span>
                            </span>
                            <span class="font-bold text-slate-900">{{ $book->stock_available }} dari {{ $book->stock_total }} buku</span>
                        </div>
                        <div class="w-full h-2 bg-slate-100 rounded-full overflow-hidden">
                            @php
                                $percent = $book->stock_total > 0 ? ($book->stock_available / $book->stock_total) * 100 : 0;
                            @endphp
                            <div class="h-full rounded-full transition-all duration-500 {{ $book->stock_available > 0 ? 'bg-emerald-500' : 'bg-rose-500' }}" style="width: {{ $percent }}%"></div>
                        </div>
                    </div>

                    <div class="flex items-center justify-between text-slate-600 pt-1">
                        <span class="flex items-center gap-1.5 text-slate-500">
                            <i data-lucide="map-pin" class="w-3.5 h-3.5"></i>
                            <span>Nomor Rak Buku</span>
                        </span>
                        <span class="font-bold text-slate-900 bg-slate-100 px-2 py-0.5 rounded-lg">{{ $book->shelf_location }}</span>
                    </div>

                    <div class="flex items-center justify-between text-slate-600">
                        <span class="flex items-center gap-1.5 text-slate-500">
                            <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                            <span>Frekuensi Dilihat</span>
                        </span>
                        <span class="font-bold text-slate-900">{{ number_format($book->views_count) }} kali</span>
                    </div>
                </div>

            </div>
        </div>

        <!-- Right 8 Cols: Book Meta, Synopsis & Reviews -->
        <div class="lg:col-span-8 space-y-8">
            
            <!-- Book Header Information Card -->
            <div class="bg-white p-8 sm:p-10 rounded-3xl border border-slate-200/80 shadow-sm space-y-6">
                <div>
                    <div class="flex flex-wrap items-center gap-2 mb-3">
                        <a href="{{ route('catalog.index', ['kategori' => $book->category->slug]) }}" 
                           class="px-3 py-1 rounded-full text-xs font-bold bg-brand-50 text-brand-700 border border-brand-200 hover:bg-brand-100 transition-colors">
                            {{ $book->category->name }}
                        </a>
                        <span class="text-xs text-slate-400 font-medium">&bull; Terbit {{ $book->publication_year }}</span>
                    </div>

                    <h1 class="font-display text-3xl sm:text-4xl font-black text-slate-900 tracking-tight leading-tight">
                        {{ $book->title }}
                    </h1>
                    <p class="text-sm font-semibold text-slate-600 mt-2">
                        Karya: <span class="text-slate-900 font-bold">{{ $book->author }}</span>
                    </p>
                </div>

                <!-- Rating Score Pill -->
                <div class="flex items-center gap-4 py-3 px-5 bg-slate-50 rounded-2xl border border-slate-100 max-w-max">
                    <div class="flex items-center gap-1.5">
                        <i data-lucide="star" class="w-5 h-5 text-amber-400 fill-amber-400"></i>
                        <span class="text-xl font-black text-slate-900">{{ $book->average_rating > 0 ? $book->average_rating : '5.0' }}</span>
                        <span class="text-xs text-slate-400">/ 5.0</span>
                    </div>
                    <div class="w-px h-6 bg-slate-200"></div>
                    <span class="text-xs text-slate-600 font-semibold">{{ $book->reviews_count }} Ulasan Pembaca</span>
                </div>

                <!-- Book Specs Grid -->
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 pt-2">
                    <div class="p-3.5 bg-slate-50 rounded-2xl border border-slate-100">
                        <span class="text-[10px] uppercase font-bold text-slate-400">Penerbit</span>
                        <p class="text-xs font-bold text-slate-800 mt-0.5 truncate">{{ $book->publisher }}</p>
                    </div>
                    <div class="p-3.5 bg-slate-50 rounded-2xl border border-slate-100">
                        <span class="text-[10px] uppercase font-bold text-slate-400">ISBN</span>
                        <p class="text-xs font-bold text-slate-800 mt-0.5 font-mono truncate">{{ $book->isbn }}</p>
                    </div>
                    <div class="p-3.5 bg-slate-50 rounded-2xl border border-slate-100">
                        <span class="text-[10px] uppercase font-bold text-slate-400">Ketebalan</span>
                        <p class="text-xs font-bold text-slate-800 mt-0.5">{{ $book->pages ?? '-' }} Halaman</p>
                    </div>
                    <div class="p-3.5 bg-slate-50 rounded-2xl border border-slate-100">
                        <span class="text-[10px] uppercase font-bold text-slate-400">Bahasa</span>
                        <p class="text-xs font-bold text-slate-800 mt-0.5">{{ $book->language }}</p>
                    </div>
                </div>

                <!-- Synopsis Text -->
                <div class="pt-6 border-t border-slate-100 space-y-3">
                    <h3 class="font-display font-bold text-slate-900 text-lg flex items-center gap-2">
                        <i data-lucide="align-left" class="w-5 h-5 text-brand-600"></i>
                        <span>Sinopsis & Ikhtisar Buku</span>
                    </h3>
                    <div class="text-sm text-slate-700 leading-relaxed whitespace-pre-line font-normal">
                        {{ $book->description }}
                    </div>
                </div>

            </div>

            <!-- Reviews & Comments Section -->
            <div class="bg-white p-8 sm:p-10 rounded-3xl border border-slate-200/80 shadow-sm space-y-6">
                
                <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                    <div>
                        <h3 class="font-display font-bold text-slate-900 text-lg sm:text-xl flex items-center gap-2">
                            <i data-lucide="message-square" class="w-5 h-5 text-brand-600"></i>
                            <span>Ulasan & Penilaian Pembaca</span>
                        </h3>
                        <p class="text-xs text-slate-500 mt-0.5">Pendapat dan catatan reflektif dari anggota komunitas Aksaraya</p>
                    </div>
                </div>

                <!-- User Review Form (Logged in) -->
                @auth
                <div class="p-6 bg-slate-50 rounded-2xl border border-slate-200/80 space-y-4">
                    <h4 class="font-bold text-xs uppercase tracking-wider text-slate-800 flex items-center gap-1.5">
                        <i data-lucide="pen-tool" class="w-4 h-4 text-brand-600"></i>
                        <span>{{ $userReview ? 'Perbarui Ulasan Anda' : 'Tulis Ulasan & Beri Rating Bintang' }}</span>
                    </h4>

                    <form method="POST" action="{{ route('catalog.review', $book->id) }}" class="space-y-4">
                        @csrf
                        
                        <div>
                            <label class="block text-xs font-bold text-slate-600 mb-2">Penilaian Bintang:</label>
                            <div class="flex items-center gap-4">
                                @for($i = 5; $i >= 1; $i--)
                                <label class="flex items-center gap-1 cursor-pointer">
                                    <input type="radio" name="rating" value="{{ $i }}" {{ ($userReview?->rating ?? 5) == $i ? 'checked' : '' }} class="text-amber-500">
                                    <span class="text-xs font-bold text-slate-800 flex items-center">
                                        {{ $i }} <i data-lucide="star" class="w-3.5 h-3.5 text-amber-400 fill-amber-400 inline ml-0.5"></i>
                                    </span>
                                </label>
                                @endfor
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-600 mb-1.5">Ulasan / Resensi:</label>
                            <textarea name="comment" rows="3" required placeholder="Tuliskan komentar mengenai isi buku, gaya penulisan, atau wawasan yang didapatkan..." class="w-full text-xs p-3 bg-white border border-slate-300 rounded-xl focus:ring-2 focus:ring-brand-500 leading-relaxed">{{ $userReview?->comment }}</textarea>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="px-5 py-2.5 bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs rounded-xl shadow-xs transition-colors">
                                {{ $userReview ? 'Simpan Perubahan' : 'Kirim Ulasan' }}
                            </button>
                        </div>
                    </form>
                </div>
                @else
                <div class="p-4 bg-slate-50 border border-slate-200/80 rounded-2xl text-center text-xs text-slate-600">
                    <p>Ingin berbagi ulasan dan rating? <a href="{{ route('login') }}" class="font-bold text-brand-700 hover:underline">Masuk terlebih dahulu</a></p>
                </div>
                @endauth

                <!-- Reviews Timeline -->
                <div class="space-y-4">
                    @forelse($book->reviews as $review)
                    <div class="p-5 rounded-2xl border border-slate-100 bg-white hover:bg-slate-50/50 transition-colors space-y-2">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-brand-600 to-teal-700 text-white flex items-center justify-center font-bold text-xs shadow-xs">
                                    {{ strtoupper(substr($review->user->name, 0, 2)) }}
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-slate-900">{{ $review->user->name }}</p>
                                    <p class="text-[10px] text-slate-400 font-mono">{{ $review->created_at->diffForHumans() }}</p>
                                </div>
                            </div>

                            <div class="flex items-center gap-1">
                                @for($s = 1; $s <= 5; $s++)
                                <i data-lucide="star" class="w-3.5 h-3.5 {{ $s <= $review->rating ? 'text-amber-400 fill-amber-400' : 'text-slate-200' }}"></i>
                                @endfor
                            </div>
                        </div>

                        <p class="text-xs text-slate-700 leading-relaxed pl-12 font-normal">
                            {{ $review->comment }}
                        </p>
                    </div>
                    @empty
                    <div class="text-center py-10 text-slate-400 text-xs">
                        <i data-lucide="message-square-dashed" class="w-8 h-8 mx-auto mb-2 text-slate-300"></i>
                        <span>Belum ada ulasan untuk buku ini. Jadilah yang pertama memberikan review!</span>
                    </div>
                    @endforelse
                </div>

            </div>

        </div>

    </div>

    <!-- Related Books Section -->
    @if($relatedBooks->count() > 0)
    <div class="mt-20 pt-12 border-t border-slate-200/80">
        <div class="flex items-center justify-between mb-8">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-brand-600">Rekomendasi Terkait</span>
                <h3 class="font-display text-2xl font-bold text-slate-900 mt-1">Buku Lain dalam Kategori Ini</h3>
            </div>
            <a href="{{ route('catalog.index', ['kategori' => $book->category->slug]) }}" class="text-xs font-bold text-brand-700 hover:underline">
                Lihat Semua &rarr;
            </a>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-4 gap-6">
            @foreach($relatedBooks as $related)
            <a href="{{ route('catalog.show', $related->slug) }}" class="group block bg-white rounded-3xl border border-slate-200/80 overflow-hidden shadow-sm hover:shadow-lg transition-all p-3 hover:-translate-y-1">
                <div class="aspect-[3/4] rounded-2xl bg-slate-100 overflow-hidden mb-3">
                    <img src="{{ $related->cover_image ?? 'https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?auto=format&fit=crop&w=400&q=80' }}" 
                         alt="{{ $related->title }}" 
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                </div>
                <h4 class="text-xs font-bold text-slate-900 group-hover:text-brand-700 transition-colors line-clamp-1">{{ $related->title }}</h4>
                <p class="text-[11px] text-slate-500 truncate mt-0.5">{{ $related->author }}</p>
            </a>
            @endforeach
        </div>
    </div>
    @endif

</div>
@endsection
