<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Laporan Sirkulasi Aksaraya ({{ $startDate }} s/d {{ $endDate }})</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        @media print {
            .no-print { display: none !important; }
            body { padding: 0; }
        }
    </style>
</head>
<body class="p-8 max-w-5xl mx-auto text-slate-800 bg-white" onload="window.print()">

    <!-- Print Action Button -->
    <div class="no-print mb-6 flex justify-between items-center p-4 bg-slate-100 rounded-xl border border-slate-200">
        <span class="text-xs text-slate-600">Tekan tombol cetak atau gunakan Ctrl+P / Cmd+P di browser Anda.</span>
        <button onclick="window.print()" class="px-4 py-2 bg-blue-600 text-white font-bold text-xs rounded-lg shadow">
            Cetak Dokumen Ini
        </button>
    </div>

    <!-- Kop Surat Resmi -->
    <div class="border-b-4 border-double border-slate-900 pb-4 mb-6 text-center">
        <h1 class="text-2xl font-black uppercase tracking-wider text-slate-900">{{ $libraryName }}</h1>
        <p class="text-xs text-slate-600 mt-1">{{ $libraryAddress }}</p>
        <p class="text-[11px] text-slate-500 font-mono">Email: layanan@aksaraya.id | Portal Perpustakaan Digital Indonesia</p>
    </div>

    <!-- Judul Laporan -->
    <div class="text-center mb-6">
        <h2 class="text-base font-extrabold uppercase text-slate-900 tracking-wide">Laporan Sirkulasi & Peminjaman Bahan Pustaka</h2>
        <p class="text-xs text-slate-600 mt-0.5">Periode: {{ date('d F Y', strtotime($startDate)) }} s/d {{ date('d F Y', strtotime($endDate)) }}</p>
    </div>

    <!-- Ringkasan Angka -->
    <div class="grid grid-cols-4 gap-4 mb-6 text-xs border border-slate-300 rounded-lg p-3 bg-slate-50">
        <div>
            <span class="text-slate-500 block">Total Transaksi</span>
            <span class="font-bold text-slate-900 text-sm">{{ $summary['total_transactions'] }}</span>
        </div>
        <div>
            <span class="text-slate-500 block">Buku Dikembalikan</span>
            <span class="font-bold text-slate-900 text-sm">{{ $summary['returned_count'] }}</span>
        </div>
        <div>
            <span class="text-slate-500 block">Total Denda Terhitung</span>
            <span class="font-bold text-slate-900 text-sm">Rp {{ number_format($summary['total_fines'], 0, ',', '.') }}</span>
        </div>
        <div>
            <span class="text-slate-500 block">Denda Terbayar Lunas</span>
            <span class="font-bold text-emerald-700 text-sm">Rp {{ number_format($summary['paid_fines'], 0, ',', '.') }}</span>
        </div>
    </div>

    <!-- Tabel Transaksi -->
    <table class="w-full text-left text-xs border border-slate-300 border-collapse mb-8">
        <thead>
            <tr class="bg-slate-100 border-b border-slate-300 text-slate-800 font-bold uppercase text-[10px]">
                <th class="p-2 border-r border-slate-300">No</th>
                <th class="p-2 border-r border-slate-300">Kode</th>
                <th class="p-2 border-r border-slate-300">Peminjam</th>
                <th class="p-2 border-r border-slate-300">Judul Buku</th>
                <th class="p-2 border-r border-slate-300">Tgl Pinjam</th>
                <th class="p-2 border-r border-slate-300">Tenggat</th>
                <th class="p-2 border-r border-slate-300">Tgl Kembali</th>
                <th class="p-2 border-r border-slate-300">Status</th>
                <th class="p-2 text-right">Denda</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-200">
            @foreach($loans as $idx => $l)
            <tr>
                <td class="p-2 border-r border-slate-300 text-center">{{ $idx + 1 }}</td>
                <td class="p-2 border-r border-slate-300 font-mono font-bold">{{ $l->loan_code }}</td>
                <td class="p-2 border-r border-slate-300">{{ $l->user->name }}</td>
                <td class="p-2 border-r border-slate-300">{{ $l->book->title }}</td>
                <td class="p-2 border-r border-slate-300">{{ $l->loan_date ? $l->loan_date->format('d/m/Y') : '-' }}</td>
                <td class="p-2 border-r border-slate-300">{{ $l->due_date ? $l->due_date->format('d/m/Y') : '-' }}</td>
                <td class="p-2 border-r border-slate-300">{{ $l->return_date ? $l->return_date->format('d/m/Y') : '-' }}</td>
                <td class="p-2 border-r border-slate-300 font-semibold">{{ $l->status_label }}</td>
                <td class="p-2 text-right font-mono">{{ $l->fine_amount > 0 ? 'Rp ' . number_format($l->fine_amount, 0, ',', '.') : '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Tanda Tangan Pengesahan -->
    <div class="flex justify-between items-end pt-8 text-xs">
        <div>
            <p>Dicetak secara otomatis pada:</p>
            <p class="font-mono text-slate-500">{{ date('d F Y, H:i') }} WIB</p>
        </div>
        <div class="text-center w-60">
            <p>Jakarta, {{ date('d F Y') }}</p>
            <p class="font-semibold text-slate-700 mt-0.5">Kepala Perpustakaan Aksaraya</p>
            <div class="h-20"></div>
            <p class="font-bold text-slate-900 border-b border-slate-400 pb-0.5 inline-block min-w-[180px]">Dr. Pustakawan Utama, M.Hum</p>
            <p class="text-[11px] text-slate-500 font-mono">NIP. 19850412 201001 1 002</p>
        </div>
    </div>

</body>
</html>

