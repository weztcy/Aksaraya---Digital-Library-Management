@extends('layouts.admin')

@section('title', 'Sirkulasi Peminjaman - Aksaraya')
@section('page-title', 'Sirkulasi & Peminjaman Bahan Pustaka')

@section('content')
<div class="space-y-6 mt-6" x-data="{ returnModal: false, rejectModal: false, activeLoan: {} }">
    
    <!-- Filter Tabs Ribbon -->
    <div class="flex flex-wrap items-center gap-2 bg-white p-2.5 rounded-3xl border border-slate-200/80 shadow-sm text-xs font-bold">
        <a href="{{ route('admin.loans.index') }}" class="px-4 py-2 rounded-2xl transition-all {{ empty($status) ? 'bg-brand-600 text-white shadow-glow' : 'text-slate-600 hover:bg-slate-100' }}">
            Semua ({{ $counts['all'] }})
        </a>
        <a href="{{ route('admin.loans.index', ['status' => 'pending']) }}" class="px-4 py-2 rounded-2xl transition-all flex items-center gap-1.5 {{ $status === 'pending' ? 'bg-amber-500 text-slate-950 shadow-sm' : 'text-slate-600 hover:bg-slate-100' }}">
            <span>Menunggu Konfirmasi</span>
            @if($counts['pending'] > 0)
            <span class="px-2 py-0.5 rounded-full text-[10px] bg-amber-200 text-amber-950 font-black">{{ $counts['pending'] }}</span>
            @endif
        </a>
        <a href="{{ route('admin.loans.index', ['status' => 'approved']) }}" class="px-4 py-2 rounded-2xl transition-all {{ $status === 'approved' ? 'bg-indigo-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100' }}">
            Siap Diambil ({{ $counts['approved'] }})
        </a>
        <a href="{{ route('admin.loans.index', ['status' => 'borrowed']) }}" class="px-4 py-2 rounded-2xl transition-all {{ $status === 'borrowed' ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100' }}">
            Sedang Dipinjam ({{ $counts['borrowed'] }})
        </a>
        <a href="{{ route('admin.loans.index', ['status' => 'overdue']) }}" class="px-4 py-2 rounded-2xl transition-all flex items-center gap-1.5 {{ $status === 'overdue' ? 'bg-rose-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100' }}">
            <span>Terlambat</span>
            @if($counts['overdue'] > 0)
            <span class="px-2 py-0.5 rounded-full text-[10px] bg-rose-200 text-rose-950 font-black">{{ $counts['overdue'] }}</span>
            @endif
        </a>
        <a href="{{ route('admin.loans.index', ['status' => 'returned']) }}" class="px-4 py-2 rounded-2xl transition-all {{ $status === 'returned' ? 'bg-emerald-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100' }}">
            Selesai ({{ $counts['returned'] }})
        </a>
    </div>

    <!-- Search Bar & Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-4 rounded-3xl border border-slate-200/80 shadow-sm">
        <form method="GET" action="{{ route('admin.loans.index') }}" class="flex-1 max-w-md relative">
            @if($status)
            <input type="hidden" name="status" value="{{ $status }}">
            @endif
            <input type="text" name="q" value="{{ $search }}" placeholder="Cari kode pinjam, nama anggota, nomor anggota, judul buku..." 
                class="w-full pl-9 pr-3 py-2 text-xs bg-slate-50 border border-slate-300 rounded-xl focus:ring-2 focus:ring-brand-500 font-medium">
            <i data-lucide="search" class="w-4 h-4 text-slate-400 absolute left-3 top-2.5"></i>
        </form>

        <a href="{{ route('admin.loans.create') }}" class="px-5 py-2.5 bg-gradient-to-r from-brand-600 to-teal-700 hover:from-brand-700 hover:to-teal-800 text-white font-bold text-xs rounded-xl shadow-glow flex items-center gap-2 shrink-0 transition-all">
            <i data-lucide="plus-circle" class="w-4 h-4"></i>
            <span>Peminjaman Langsung di Meja</span>
        </a>
    </div>

    <!-- Loans Table -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 font-bold uppercase tracking-wider text-[10px]">
                        <th class="p-4 pl-6">Kode & Tgl</th>
                        <th class="p-4">Anggota Peminjam</th>
                        <th class="p-4">Buku & Rak</th>
                        <th class="p-4">Tenggat Waktu</th>
                        <th class="p-4">Status & Denda</th>
                        <th class="p-4 pr-6 text-right">Tindakan Petugas</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($loans as $loan)
                    <tr class="hover:bg-slate-50/60 transition-colors">
                        <td class="p-4 pl-6">
                            <span class="font-mono font-bold text-slate-900 block">{{ $loan->loan_code }}</span>
                            <span class="text-[11px] text-slate-400">{{ $loan->loan_date ? $loan->loan_date->format('d/m/Y') : '-' }}</span>
                        </td>
                        <td class="p-4">
                            <a href="{{ route('admin.members.show', $loan->user_id) }}" class="font-bold text-slate-900 hover:text-brand-700 block">
                                {{ $loan->user->name }}
                            </a>
                            <span class="text-[10px] text-slate-400 font-mono">{{ $loan->user->member_number }}</span>
                        </td>
                        <td class="p-4">
                            <div class="max-w-[220px]">
                                <a href="{{ route('catalog.show', $loan->book->slug) }}" target="_blank" class="font-display font-bold text-slate-900 hover:text-brand-700 line-clamp-1">
                                    {{ $loan->book->title }}
                                </a>
                                <span class="text-[10px] text-slate-400 font-medium">{{ $loan->book->shelf_location }}</span>
                            </div>
                        </td>
                        <td class="p-4">
                            <span class="font-medium {{ $loan->status === 'overdue' ? 'text-rose-600 font-bold' : 'text-slate-800' }}">
                                {{ $loan->due_date ? $loan->due_date->format('d/m/Y') : '-' }}
                            </span>
                            @if($loan->status === 'borrowed' && $loan->due_date && now()->gt($loan->due_date))
                            <span class="block text-[10px] text-rose-600 font-bold">
                                Terlambat {{ (int) $loan->due_date->diffInDays(now()) }} hari
                            </span>
                            @endif
                        </td>
                        <td class="p-4">
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold border {{ $loan->status_badge }}">
                                {{ $loan->status_label }}
                            </span>

                            @if($loan->fine_amount > 0)
                            <div class="mt-1 text-[11px]">
                                <span class="font-bold {{ $loan->fine_paid ? 'text-emerald-600' : 'text-rose-600' }}">
                                    Rp {{ number_format($loan->fine_amount, 0, ',', '.') }}
                                </span>
                                <span class="text-[10px] {{ $loan->fine_paid ? 'text-emerald-600 font-semibold' : 'text-rose-500' }}">
                                    {{ $loan->fine_paid ? '(Lunas)' : '(Belum Lunas)' }}
                                </span>
                            </div>
                            @endif
                        </td>
                        <td class="p-4 pr-6 text-right">
                            <div class="flex items-center justify-end gap-1.5 flex-wrap">
                                
                                @if($loan->status === 'pending')
                                <form action="{{ route('admin.loans.approve', $loan->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="px-3 py-1 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-bold text-[11px] shadow-xs">
                                        Setujui
                                    </button>
                                </form>
                                <button @click="activeLoan = { id: {{ $loan->id }}, code: '{{ $loan->loan_code }}' }; rejectModal = true" class="px-2.5 py-1 bg-slate-100 hover:bg-rose-50 text-slate-600 hover:text-rose-600 rounded-lg font-semibold text-[11px]">
                                    Tolak
                                </button>
                                @endif

                                @if($loan->status === 'approved')
                                <form action="{{ route('admin.loans.borrow', $loan->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-bold text-[11px] shadow-xs flex items-center gap-1">
                                        <i data-lucide="hand" class="w-3.5 h-3.5"></i>
                                        <span>Serahkan</span>
                                    </button>
                                </form>
                                @endif

                                @if(in_array($loan->status, ['borrowed', 'overdue']))
                                <button @click="activeLoan = { id: {{ $loan->id }}, code: '{{ $loan->loan_code }}', title: '{{ addslashes($loan->book->title) }}', borrower: '{{ addslashes($loan->user->name) }}', due: '{{ $loan->due_date ? $loan->due_date->format('Y-m-d') : '' }}' }; returnModal = true;" class="px-3 py-1 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-bold text-[11px] shadow-xs flex items-center gap-1">
                                    <i data-lucide="arrow-down-left" class="w-3.5 h-3.5"></i>
                                    <span>Kembalikan</span>
                                </button>

                                <!-- WhatsApp & Email Auto-reminder -->
                                <form action="{{ route('admin.loans.reminder', $loan->id) }}" method="POST" class="inline">
                                    @csrf
                                    <input type="hidden" name="channel" value="whatsapp">
                                    <button type="submit" class="p-1.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 rounded-lg transition-colors" title="Kirim Pengingat WhatsApp ke Anggota">
                                        <i data-lucide="message-circle" class="w-3.5 h-3.5"></i>
                                    </button>
                                </form>
                                <form action="{{ route('admin.loans.reminder', $loan->id) }}" method="POST" class="inline">
                                    @csrf
                                    <input type="hidden" name="channel" value="email">
                                    <button type="submit" class="p-1.5 bg-blue-50 hover:bg-blue-100 text-blue-700 rounded-lg transition-colors" title="Kirim Pengingat Email ke Anggota">
                                        <i data-lucide="mail" class="w-3.5 h-3.5"></i>
                                    </button>
                                </form>
                                @endif

                                @if($loan->fine_amount > 0 && !$loan->fine_paid)
                                <form action="{{ route('admin.loans.payFine', $loan->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="px-2.5 py-1 bg-amber-100 hover:bg-amber-200 text-amber-900 rounded-lg font-bold text-[10px]" title="Tandai Denda Telah Lunas">
                                        Lunasi Denda
                                    </button>
                                </form>
                                @endif

                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="p-8 text-center text-slate-400 text-xs">
                            Tidak ada transaksi sirkulasi yang sesuai dengan kriteria.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-100">
            {{ $loans->links() }}
        </div>
    </div>

    <!-- Return Book Modal -->
    <div x-show="returnModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div @click="returnModal = false" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm"></div>
        <div class="relative bg-white rounded-3xl max-w-md w-full p-6 sm:p-8 shadow-2xl z-10 space-y-4 border border-slate-100">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <h3 class="font-display font-bold text-slate-900 text-base">Proses Pengembalian Buku</h3>
                <button @click="returnModal = false" class="text-slate-400 hover:text-slate-600 p-1">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <div class="p-4 bg-slate-50 rounded-2xl space-y-2 text-xs text-slate-600">
                <p>Kode Transaksi: <strong class="text-slate-900 font-mono" x-text="activeLoan.code"></strong></p>
                <p>Judul Buku: <strong class="text-slate-900" x-text="activeLoan.title"></strong></p>
                <p>Nama Peminjam: <strong class="text-slate-900" x-text="activeLoan.borrower"></strong></p>
                <p class="text-[11px] text-slate-400 pt-1">Sistem otomatis menghitung keterlambatan dan menambah stok buku kembali ke katalog.</p>
            </div>

            <form method="POST" :action="'/admin/peminjaman/' + activeLoan.id + '/kembalikan'" class="space-y-4">
                @csrf
                <div>
                    <label class="flex items-center gap-2 cursor-pointer text-xs font-bold text-slate-800">
                        <input type="checkbox" name="fine_paid" value="1" checked class="rounded text-brand-600">
                        <span>Tandai Denda Keterlambatan Telah Dibayar / Lunas (Jika ada)</span>
                    </label>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Catatan Tambahan (Opsional)</label>
                    <textarea name="notes" rows="2" placeholder="Catatan kondisi buku saat dikembalikan..." class="w-full text-xs p-3 bg-slate-50 border border-slate-300 rounded-xl focus:ring-2 focus:ring-brand-500"></textarea>
                </div>

                <div class="flex justify-end gap-2.5 pt-2">
                    <button @click="returnModal = false" type="button" class="px-4 py-2.5 text-xs font-semibold text-slate-600 hover:bg-slate-100 rounded-xl">Batal</button>
                    <button type="submit" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-xs">Konfirmasi Pengembalian</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Reject Modal -->
    <div x-show="rejectModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div @click="rejectModal = false" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm"></div>
        <div class="relative bg-white rounded-3xl max-w-md w-full p-6 sm:p-8 shadow-2xl z-10 space-y-4 border border-slate-100">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <h3 class="font-display font-bold text-slate-900 text-base">Tolak Permohonan Pinjam</h3>
                <button @click="rejectModal = false" class="text-slate-400 hover:text-slate-600 p-1">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <form method="POST" :action="'/admin/peminjaman/' + activeLoan.id + '/tolak'" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Alasan Penolakan</label>
                    <textarea name="reason" rows="3" required placeholder="Contoh: Stok buku fisik sedang dalam perawatan atau reservasi prioritas." class="w-full text-xs p-3 bg-slate-50 border border-slate-300 rounded-xl focus:ring-2 focus:ring-brand-500"></textarea>
                </div>

                <div class="flex justify-end gap-2.5 pt-2">
                    <button @click="rejectModal = false" type="button" class="px-4 py-2.5 text-xs font-semibold text-slate-600 hover:bg-slate-100 rounded-xl">Batal</button>
                    <button type="submit" class="px-5 py-2.5 bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs rounded-xl shadow-xs">Tolak Permohonan</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
