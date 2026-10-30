@extends('layouts.admin')

@section('title', 'Pengaturan Perpustakaan')
@section('page-title', 'Konfigurasi Sistem Aksaraya')

@section('content')
<div class="max-w-3xl mx-auto space-y-6 mt-6">
    
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 sm:p-8 space-y-6">
        <div class="border-b border-slate-100 pb-4">
            <h3 class="font-bold text-slate-900 text-base">Aturan Sirkulasi & Profil Instansi</h3>
            <p class="text-xs text-slate-500 mt-0.5">Pengaturan ini akan memengaruhi perhitungan denda, batas waktu pinjam, dan informasi kontak publik.</p>
        </div>

        <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Nama & Tagline -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Nama Perpustakaan *</label>
                    <input type="text" name="library_name" value="{{ old('library_name', $settings['library_name']) }}" required
                        class="w-full text-xs p-3 bg-slate-50 border border-slate-300 rounded-xl focus:ring-2 focus:ring-brand-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Tagline / Slogan *</label>
                    <input type="text" name="library_tagline" value="{{ old('library_tagline', $settings['library_tagline']) }}" required
                        class="w-full text-xs p-3 bg-slate-50 border border-slate-300 rounded-xl focus:ring-2 focus:ring-brand-500">
                </div>
            </div>

            <!-- Sirkulasi Rules -->
            <div class="p-5 bg-slate-50 rounded-2xl border border-slate-200 space-y-4">
                <h4 class="font-bold text-xs uppercase tracking-wider text-slate-800 flex items-center gap-1.5">
                    <i data-lucide="sliders" class="w-4 h-4 text-brand-600"></i>
                    <span>Kebijakan Peminjaman & Denda</span>
                </h4>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Masa Pinjam (Hari)</label>
                        <input type="number" name="loan_period_days" value="{{ old('loan_period_days', $settings['loan_period_days']) }}" required min="1" max="30"
                            class="w-full text-xs p-2.5 bg-white border border-slate-300 rounded-xl focus:ring-2 focus:ring-brand-500">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Tarif Denda / Hari (Rp)</label>
                        <input type="number" name="daily_fine_rate" value="{{ old('daily_fine_rate', $settings['daily_fine_rate']) }}" required min="0" step="500"
                            class="w-full text-xs p-2.5 bg-white border border-slate-300 rounded-xl focus:ring-2 focus:ring-brand-500">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Maks Buku / Anggota</label>
                        <input type="number" name="max_borrow_limit" value="{{ old('max_borrow_limit', $settings['max_borrow_limit']) }}" required min="1" max="10"
                            class="w-full text-xs p-2.5 bg-white border border-slate-300 rounded-xl focus:ring-2 focus:ring-brand-500">
                    </div>
                </div>
            </div>

            <!-- Kontak & Alamat -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Nomor Telepon Layanan</label>
                    <input type="text" name="library_phone" value="{{ old('library_phone', $settings['library_phone']) }}" required
                        class="w-full text-xs p-3 bg-slate-50 border border-slate-300 rounded-xl focus:ring-2 focus:ring-brand-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Email Layanan</label>
                    <input type="email" name="library_email" value="{{ old('library_email', $settings['library_email']) }}" required
                        class="w-full text-xs p-3 bg-slate-50 border border-slate-300 rounded-xl focus:ring-2 focus:ring-brand-500">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Alamat Lengkap Gedung Perpustakaan</label>
                <textarea name="library_address" rows="2" required
                    class="w-full text-xs p-3 bg-slate-50 border border-slate-300 rounded-xl focus:ring-2 focus:ring-brand-500">{{ old('library_address', $settings['library_address']) }}</textarea>
            </div>

            <!-- Announcement Banner -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Pesan Pengumuman Global (Muncul di Header Banner Publik)</label>
                <textarea name="announcement" rows="2" placeholder="Kosongkan jika tidak ada pengumuman khusus..."
                    class="w-full text-xs p-3 bg-slate-50 border border-slate-300 rounded-xl focus:ring-2 focus:ring-brand-500">{{ old('announcement', $settings['announcement']) }}</textarea>
            </div>

            <div class="flex justify-end pt-4 border-t border-slate-100">
                <button type="submit" class="px-6 py-3 bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs rounded-xl shadow-md transition-colors flex items-center gap-2">
                    <i data-lucide="check" class="w-4 h-4"></i>
                    <span>Simpan Pengaturan</span>
                </button>
            </div>
        </form>

    </div>
</div>
@endsection

