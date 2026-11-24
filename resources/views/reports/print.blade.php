<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laporan Keuangan Resmi - MauLondri</title>

    <!-- Tailwind CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        @media print {
            .no-print { display: none !important; }
            body { background: #fff !important; color: #000 !important; }
            .print-card { box-shadow: none !important; border: 1px solid #ddd !important; }
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 antialiased p-6 sm:p-10 max-w-5xl mx-auto">

    {{-- TOP ACTION (NO-PRINT) --}}
    <div class="no-print mb-6 flex items-center justify-between">
        <a href="{{ route('reports.index') }}" class="px-4 py-2 rounded-xl bg-white border border-slate-300 text-slate-700 text-xs font-semibold hover:bg-slate-50">
            ← Kembali ke Panel Laporan
        </a>

        <button onclick="window.print()" class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs shadow-md">
            Cetak Laporan / PDF
        </button>
    </div>

    {{-- KOP SURAT RESMI TOKO --}}
    <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-200 print-card mb-6">
        <div class="text-center pb-6 border-b-2 border-slate-800">
            <h1 class="text-2xl font-extrabold tracking-tight uppercase">MAULONDRI MANAGEMENT</h1>
            <p class="text-xs text-slate-600 mt-1">Sistem Informasi Manajemen Laundry Berbasis Web</p>
            <p class="text-[11px] text-slate-500">Jl. Laundry Utama No. 88 • Telp/WhatsApp: 0812-3456-7890 • Email: info@maulondri.com</p>
        </div>

        <div class="py-4 flex justify-between items-center text-xs">
            <div>
                <h2 class="font-bold text-sm text-slate-900 uppercase">LAPORAN REKAPITULASI KEUANGAN & TRANSAKSI</h2>
                <p class="text-slate-500 mt-0.5">
                    Periode: <strong>{{ \Carbon\Carbon::parse($startDate)->format('d F Y') }}</strong> s/d <strong>{{ \Carbon\Carbon::parse($endDate)->format('d F Y') }}</strong>
                </p>
            </div>
            <div class="text-right text-slate-500">
                <p>Dicetak pada: {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}</p>
                <p>Oleh: {{ Auth::user()->name }} ({{ ucfirst(Auth::user()->role) }})</p>
            </div>
        </div>

        {{-- SUMMARY KOTAK --}}
        <div class="grid grid-cols-4 gap-4 my-4 p-4 rounded-xl bg-slate-50 border border-slate-200 text-xs">
            <div>
                <span class="text-slate-500 block">Total Omset Lunas:</span>
                <strong class="text-base text-slate-900 block mt-1">Rp {{ number_format($totalIncome, 0, ',', '.') }}</strong>
            </div>
            <div>
                <span class="text-slate-500 block">Total Piutang:</span>
                <strong class="text-base text-rose-600 block mt-1">Rp {{ number_format($unpaidIncome, 0, ',', '.') }}</strong>
            </div>
            <div>
                <span class="text-slate-500 block">Jumlah Nota:</span>
                <strong class="text-base text-slate-900 block mt-1">{{ $totalTransactions }} Transaksi</strong>
            </div>
            <div>
                <span class="text-slate-500 block">Total Berat:</span>
                <strong class="text-base text-slate-900 block mt-1">{{ number_format($totalWeight, 1, ',', '.') }} kg</strong>
            </div>
        </div>

        {{-- TABEL TRANSAKSI --}}
        <div class="mt-6">
            <h3 class="font-bold text-xs uppercase tracking-wider mb-2 text-slate-700">Rincian Data Transaksi Laundry:</h3>
            <table class="w-full text-left text-xs border border-slate-200">
                <thead class="bg-slate-100 font-semibold text-slate-700 border-b border-slate-200">
                    <tr>
                        <th class="p-2 border-r border-slate-200">No.</th>
                        <th class="p-2 border-r border-slate-200">Invoice</th>
                        <th class="p-2 border-r border-slate-200">Pelanggan</th>
                        <th class="p-2 border-r border-slate-200">Layanan</th>
                        <th class="p-2 border-r border-slate-200">Berat</th>
                        <th class="p-2 border-r border-slate-200">Total Harga</th>
                        <th class="p-2 border-r border-slate-200">Status Cuci</th>
                        <th class="p-2 border-r border-slate-200">Bayar</th>
                        <th class="p-2">Kasir</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse ($transactions as $index => $tx)
                        <tr>
                            <td class="p-2 border-r border-slate-200">{{ $index + 1 }}</td>
                            <td class="p-2 border-r border-slate-200 font-bold">{{ $tx->invoice_code }}</td>
                            <td class="p-2 border-r border-slate-200">{{ $tx->customer->name }}</td>
                            <td class="p-2 border-r border-slate-200">{{ $tx->package->name }}</td>
                            <td class="p-2 border-r border-slate-200">{{ $tx->quantity }} {{ $tx->package->unit }}</td>
                            <td class="p-2 border-r border-slate-200 font-bold">Rp {{ number_format($tx->total_price, 0, ',', '.') }}</td>
                            <td class="p-2 border-r border-slate-200 uppercase text-[10px] font-bold">{{ $tx->status }}</td>
                            <td class="p-2 border-r border-slate-200 uppercase text-[10px] font-bold">{{ $tx->payment_status }}</td>
                            <td class="p-2 text-slate-600">{{ $tx->user->name }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="p-4 text-center text-slate-400">Tidak ada transaksi pada periode ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- TANDA TANGAN --}}
        <div class="mt-12 pt-8 flex justify-between items-end text-xs">
            <div class="text-center w-48">
                <p>Mengetahui / Kasir,</p>
                <div class="h-20"></div>
                <p class="font-bold uppercase underline">{{ Auth::user()->name }}</p>
            </div>

            <div class="text-center w-48">
                <p>Pemilik Usaha (Owner),</p>
                <div class="h-20"></div>
                <p class="font-bold uppercase underline">( Admin Laundry )</p>
            </div>
        </div>

    </div>

</body>
</html>
