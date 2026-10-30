@extends('layouts.admin')

@section('title', 'Peminjaman Manual di Tempat')
@section('page-title', 'Peminjaman Langsung di Meja Sirkulasi')

@section('content')
<div class="max-w-3xl mx-auto space-y-6 mt-6">
    
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.loans.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-500 hover:text-slate-900 transition-colors">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            <span>Kembali ke Sirkulasi</span>
        </a>
    </div>

    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 sm:p-8" x-data="walkinScanner()">
        <div class="mb-6 pb-4 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="font-bold text-slate-900 text-base">Formulir Peminjaman Buku Walk-in</h3>
                <p class="text-xs text-slate-500 mt-0.5">Digunakan ketika anggota datang langsung ke meja sirkulasi perpustakaan</p>
            </div>
            
            <!-- Quick Barcode/ISBN Scanner Box -->
            <div class="flex items-center gap-2">
                <div class="relative">
                    <input type="text" x-model="scanInput" @keydown.enter.prevent="lookupCode()" placeholder="Scan Barcode / ISBN..." 
                           class="w-48 pl-8 pr-3 py-1.5 text-xs bg-slate-50 border border-brand-400 rounded-xl focus:ring-2 focus:ring-brand-500 font-mono">
                    <i data-lucide="scan-barcode" class="w-4 h-4 text-brand-600 absolute left-2.5 top-2"></i>
                </div>
                <button type="button" @click="lookupCode()" class="px-3 py-1.5 bg-brand-600 hover:bg-brand-700 text-white rounded-xl text-xs font-bold shadow-xs">
                    Cari
                </button>
            </div>
        </div>

        <div x-show="scanFeedback" x-cloak class="mb-4 p-3 rounded-xl text-xs font-medium" :class="scanSuccess ? 'bg-emerald-50 text-emerald-800 border border-emerald-200' : 'bg-rose-50 text-rose-800 border border-rose-200'">
            <span x-text="scanFeedback"></span>
        </div>

        <form method="POST" action="{{ route('admin.loans.store') }}" class="space-y-6">
            @csrf

            <!-- Pilih Anggota -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Pilih Anggota Peminjam *</label>
                <select name="user_id" x-model="selectedUserId" required class="w-full text-xs p-3 bg-slate-50 border border-slate-300 rounded-xl focus:ring-2 focus:ring-brand-500 font-medium">
                    <option value="">-- Pilih Anggota Terdaftar --</option>
                    @foreach($members as $m)
                    <option value="{{ $m->id }}" {{ old('user_id') == $m->id ? 'selected' : '' }}>
                        {{ $m->name }} ({{ $m->member_number }}) - Pinjam aktif: {{ $m->activeLoansCount() }} buku
                    </option>
                    @endforeach
                </select>
                <p class="text-[11px] text-slate-400 mt-1">Gunakan scanner barcode kartu anggota di atas untuk memilih instan.</p>
            </div>

            <!-- Pilih Buku -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Pilih Buku Koleksi *</label>
                <select name="book_id" x-model="selectedBookId" required class="w-full text-xs p-3 bg-slate-50 border border-slate-300 rounded-xl focus:ring-2 focus:ring-brand-500 font-medium">
                    <option value="">-- Pilih Buku yang Tersedia --</option>
                    @foreach($books as $b)
                    <option value="{{ $b->id }}" {{ old('book_id') == $b->id ? 'selected' : '' }}>
                        {{ $b->title }} - {{ $b->author }} (Tersedia: {{ $b->stock_available }} eks / Lokasi: {{ $b->shelf_location }} / ISBN: {{ $b->isbn }})
                    </option>
                    @endforeach
                </select>
                <p class="text-[11px] text-slate-400 mt-1">Atau pindai barcode/ISBN fisik buku untuk pengisian otomatis.</p>
            </div>

            <!-- Tanggal Pinjam & Tenggat -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Tanggal Peminjaman *</label>
                    <input type="date" name="loan_date" value="{{ old('loan_date', date('Y-m-d')) }}" required
                        class="w-full text-xs p-3 bg-slate-50 border border-slate-300 rounded-xl focus:ring-2 focus:ring-brand-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Batas Tenggat Kembali *</label>
                    <input type="date" name="due_date" value="{{ old('due_date', date('Y-m-d', strtotime('+' . $loanDays . ' days'))) }}" required
                        class="w-full text-xs p-3 bg-slate-50 border border-slate-300 rounded-xl focus:ring-2 focus:ring-brand-500">
                    <p class="text-[11px] text-slate-400 mt-1">Default masa pinjam: {{ $loanDays }} hari.</p>
                </div>
            </div>

            <!-- Catatan -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Catatan Tambahan (Opsional)</label>
                <textarea name="notes" rows="2" placeholder="Catatan kondisi buku saat diserahkan..." class="w-full text-xs p-3 bg-slate-50 border border-slate-300 rounded-xl focus:ring-2 focus:ring-brand-500">{{ old('notes') }}</textarea>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="{{ route('admin.loans.index') }}" class="px-5 py-2.5 text-xs font-semibold text-slate-600 hover:bg-slate-100 rounded-xl transition-colors">Batal</a>
                <button type="submit" class="px-6 py-2.5 bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs rounded-xl shadow-md transition-colors flex items-center gap-2">
                    <i data-lucide="check" class="w-4 h-4"></i>
                    <span>Catat & Serahkan Buku</span>
                </button>
            </div>
        </form>
    </div>

</div>

<script>
    function walkinScanner() {
        return {
            scanInput: '',
            scanFeedback: '',
            scanSuccess: false,
            selectedUserId: '{{ old('user_id', '') }}',
            selectedBookId: '{{ old('book_id', '') }}',
            lookupCode() {
                if (!this.scanInput.trim()) return;
                fetch('{{ route('admin.loans.scan') }}?code=' + encodeURIComponent(this.scanInput.trim()))
                    .then(r => r.json())
                    .then(res => {
                        if (res.found) {
                            this.scanSuccess = true;
                            if (res.type === 'member') {
                                this.selectedUserId = res.data.id;
                                this.scanFeedback = '✓ Anggota Terdeteksi: ' + res.data.name + ' (' + res.data.member_number + ')';
                            } else if (res.type === 'book') {
                                this.selectedBookId = res.data.id;
                                this.scanFeedback = '✓ Buku Terdeteksi: ' + res.data.title + ' (Sisa: ' + res.data.stock_available + ' eks)';
                            }
                        } else {
                            this.scanSuccess = false;
                            this.scanFeedback = '✗ ' + (res.message || 'Data barcode tidak ditemukan.');
                        }
                        this.scanInput = '';
                    })
                    .catch(e => {
                        this.scanSuccess = false;
                        this.scanFeedback = 'Gagal memindai kode barcode.';
                    });
            }
        };
    }
</script>
@endsection

