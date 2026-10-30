@extends('layouts.app')

@section('title', 'Kartu Anggota Perpustakaan Digital - Aksaraya')

@section('content')
<div class="py-12 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
    
    <!-- Action Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-brand-50 text-brand-700 border border-brand-200 text-xs font-bold uppercase tracking-wider">
                Kartu Identitas Anggota
            </span>
            <h1 class="font-display text-2xl sm:text-3xl font-black text-slate-900 tracking-tight mt-1">
                Kartu Anggota Digital Aksaraya
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Tunjukkan barcode digital ini kepada petugas di meja sirkulasi perpustakaan</p>
        </div>

        <button onclick="window.print()" class="px-5 py-2.5 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-bold shadow-md flex items-center gap-2 transition-all shrink-0">
            <i data-lucide="printer" class="w-4 h-4"></i>
            <span>Cetak Kartu Fisik</span>
        </button>
    </div>

    <!-- The Luxury Digital Card -->
    <div class="max-w-lg mx-auto aspect-[1.586/1] bg-gradient-to-tr from-navy-950 via-slate-900 to-teal-950 text-white rounded-3xl p-7 sm:p-8 shadow-2xl relative overflow-hidden border border-white/20 select-none print:border-slate-800 print:shadow-none">
        
        <!-- Ambient holographic background -->
        <div class="absolute -right-24 -top-24 w-72 h-72 bg-brand-500/25 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -left-24 -bottom-24 w-72 h-72 bg-teal-500/25 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute inset-0 bg-[radial-gradient(#fff_1px,transparent_1px)] [background-size:20px_20px] opacity-10 pointer-events-none"></div>

        <div class="relative z-10 flex flex-col justify-between h-full">
            
            <!-- Top Row: Logo & Chip -->
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-brand-500 to-teal-400 p-[1.5px] shadow-glow">
                        <div class="w-full h-full bg-navy-950 rounded-[10px] flex items-center justify-center text-white">
                            <i data-lucide="book-open-check" class="w-5 h-5 text-brand-300"></i>
                        </div>
                    </div>
                    <div>
                        <h2 class="font-display font-black text-base text-white tracking-tight leading-tight">Aksaraya</h2>
                        <span class="text-[9px] font-bold text-teal-300 tracking-widest uppercase block font-mono">Digital Library Pass</span>
                    </div>
                </div>

                <!-- Gold RFID Chip Mock -->
                <div class="w-11 h-8 rounded-lg bg-gradient-to-br from-amber-200 via-amber-400 to-amber-600 border border-amber-300 shadow-md flex items-center justify-center opacity-95">
                    <div class="w-9 h-6 border border-amber-800/40 rounded flex flex-col justify-around py-0.5">
                        <div class="border-b border-amber-800/40"></div>
                        <div class="border-b border-amber-800/40"></div>
                    </div>
                </div>
            </div>

            <!-- Middle: Member Details -->
            <div class="space-y-1 my-auto">
                <div class="text-[9px] uppercase font-bold text-slate-400 tracking-wider font-mono">Nama Anggota Resmi</div>
                <div class="font-display text-xl sm:text-2xl font-black text-white tracking-wide truncate">
                    {{ strtoupper($user->name) }}
                </div>
                <div class="text-xs sm:text-sm font-mono tracking-widest text-amber-300 font-bold">
                    {{ $user->member_number ?? 'AKS-' . date('Ym') . '-0001' }}
                </div>
            </div>

            <!-- Bottom: Meta & Barcode -->
            <div class="pt-3 border-t border-white/10 flex items-end justify-between">
                <div class="space-y-0.5 text-[10px] text-slate-300 font-medium">
                    <div>Status: <span class="text-emerald-400 font-bold">Anggota Terverifikasi</span></div>
                    <div>Bergabung: {{ $user->created_at->format('d/m/Y') }}</div>
                </div>

                <!-- Crisp Barcode -->
                <div class="bg-white p-2 rounded-xl flex flex-col items-center shadow-md">
                    <svg class="h-6 w-32 text-slate-900" viewBox="0 0 100 24" fill="currentColor">
                        <rect x="0" y="0" width="3" height="24"/>
                        <rect x="5" y="0" width="2" height="24"/>
                        <rect x="9" y="0" width="4" height="24"/>
                        <rect x="15" y="0" width="1" height="24"/>
                        <rect x="18" y="0" width="3" height="24"/>
                        <rect x="23" y="0" width="2" height="24"/>
                        <rect x="27" y="0" width="4" height="24"/>
                        <rect x="33" y="0" width="2" height="24"/>
                        <rect x="37" y="0" width="1" height="24"/>
                        <rect x="40" y="0" width="3" height="24"/>
                        <rect x="45" y="0" width="4" height="24"/>
                        <rect x="51" y="0" width="2" height="24"/>
                        <rect x="55" y="0" width="3" height="24"/>
                        <rect x="60" y="0" width="1" height="24"/>
                        <rect x="63" y="0" width="4" height="24"/>
                        <rect x="69" y="0" width="2" height="24"/>
                        <rect x="73" y="0" width="3" height="24"/>
                        <rect x="78" y="0" width="1" height="24"/>
                        <rect x="81" y="0" width="4" height="24"/>
                        <rect x="87" y="0" width="2" height="24"/>
                        <rect x="91" y="0" width="3" height="24"/>
                        <rect x="96" y="0" width="4" height="24"/>
                    </svg>
                </div>
            </div>

        </div>
    </div>

    <!-- Instructions & Rules -->
    <div class="max-w-lg mx-auto bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-8 shadow-sm space-y-4 text-xs text-slate-600">
        <h3 class="font-display font-bold text-slate-900 text-sm flex items-center gap-2">
            <i data-lucide="shield-check" class="w-4 h-4 text-emerald-600"></i>
            <span>Pedoman Penggunaan Kartu Digital</span>
        </h3>
        <ul class="list-disc list-inside space-y-2 text-slate-500 leading-relaxed">
            <li>Kartu digital ini berlaku sebagai kartu identitas resmi peminjam bahan pustaka fisik di Perpustakaan Aksaraya.</li>
            <li>Simpan kartu digital ini di perangkat ponsel Anda atau cetak pada kertas tebal / ID card fisik.</li>
            <li>Petugas akan memindai barcode di kartu untuk memproses pengambilan dan pengembalian buku.</li>
            <li>Maksimal 3 (tiga) buku dapat dipinjam secara bersamaan untuk durasi 7 hari kalender.</li>
        </ul>
    </div>

</div>

@push('styles')
<style>
    @media print {
        header, footer, button, nav, .announcement { display: none !important; }
        body { background: white !important; padding: 0 !important; }
    }
</style>
@endpush
@endsection
