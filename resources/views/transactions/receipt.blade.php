<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Nota Struk {{ $transaction->invoice_code }} - MauLondri</title>

    <!-- Tailwind CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        @media print {
            .no-print { display: none !important; }
            body { background-color: #fff !important; color: #000 !important; }
            .receipt-container { box-shadow: none !important; border: none !important; max-width: 100% !important; padding: 0 !important; }
        }
    </style>
</head>
<body class="bg-slate-100 text-slate-800 antialiased min-h-screen py-8 px-4 flex flex-col items-center">

    {{-- TOP ACTION BUTTONS (NO-PRINT) --}}
    <div class="no-print w-full max-w-md mb-6 flex flex-wrap items-center justify-between gap-3">
        <a href="{{ route('transactions.index') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-white text-slate-700 border border-slate-300 font-semibold text-xs shadow-xs hover:bg-slate-50 transition">
            ← Kembali
        </a>

        <div class="flex items-center gap-2">
            @php
                $cleanPhone = preg_replace('/[^0-9]/', '', $transaction->customer->phone);
                if (str_starts_with($cleanPhone, '0')) {
                    $cleanPhone = '62' . substr($cleanPhone, 1);
                }
                $message = "Halo *" . urlencode($transaction->customer->name) . "*,\nTerima kasih telah menggunakan jasa *MauLondri*.\n\nBerikut rincian cucian Anda:\n- No. Invoice: *" . $transaction->invoice_code . "*\n- Layanan: " . $transaction->package->name . " (" . $transaction->quantity . " " . $transaction->package->unit . ")\n- Total: *Rp " . number_format($transaction->total_price, 0, ',', '.') . "*\n- Status Cucian: *" . strtoupper($transaction->status) . "*\n- Status Pembayaran: *" . strtoupper($transaction->payment_status) . "*\n\nTerima kasih atas kepercayaan Anda!";
            @endphp
            <a href="https://wa.me/{{ $cleanPhone }}?text={{ $message }}" target="_blank" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs shadow-sm transition">
                <span>Kirim WhatsApp</span>
            </a>

            <button onclick="window.print()" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs shadow-sm transition">
                <span>Cetak Nota (Print)</span>
            </button>
        </div>
    </div>

    {{-- NOTA RECEIPT CARD (THERMAL / STANDARD 80mm FRIENDLY) --}}
    <div class="receipt-container w-full max-w-md bg-white rounded-2xl shadow-xl border border-slate-200 p-6 sm:p-8 font-mono text-xs">
        
        <!-- Header Toko -->
        <div class="text-center pb-4 border-b border-dashed border-slate-300">
            <h1 class="text-lg font-extrabold tracking-tight text-slate-900 font-sans">MAULONDRI</h1>
            <p class="text-[11px] text-slate-500 font-sans mt-0.5">Sistem Manajemen Laundry Terpercaya</p>
            <p class="text-[10px] text-slate-400 mt-1">Jl. Laundry Utama No. 88 • WA: 0812-3456-7890</p>
        </div>

        <!-- Detail Invoice & Pelanggan -->
        <div class="py-4 border-b border-dashed border-slate-300 space-y-1.5">
            <div class="flex justify-between">
                <span class="text-slate-500">No. Invoice:</span>
                <span class="font-bold text-slate-900">{{ $transaction->invoice_code }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-500">Tanggal:</span>
                <span>{{ $transaction->created_at->format('d/m/Y H:i') }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-500">Kasir:</span>
                <span>{{ $transaction->user->name }}</span>
            </div>
            <div class="flex justify-between pt-1">
                <span class="text-slate-500">Pelanggan:</span>
                <span class="font-bold text-slate-900">{{ $transaction->customer->name }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-500">No. HP/WA:</span>
                <span>{{ $transaction->customer->phone }}</span>
            </div>
        </div>

        <!-- Tabel Item Cucian -->
        <div class="py-4 border-b border-dashed border-slate-300">
            <div class="flex justify-between font-bold text-slate-700 pb-2 border-b border-slate-200">
                <span>ITEM / LAYANAN</span>
                <span>TOTAL</span>
            </div>

            <div class="pt-2 space-y-1">
                <div class="flex justify-between font-semibold text-slate-900">
                    <span>{{ $transaction->package->name }}</span>
                    <span>Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-slate-500 text-[11px]">
                    <span>{{ $transaction->quantity }} {{ $transaction->package->unit }} x Rp {{ number_format($transaction->package->price, 0, ',', '.') }}</span>
                    @if($transaction->package->duration)
                        <span>⏱️ {{ $transaction->package->duration }}</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Rincian Biaya & Status -->
        <div class="py-4 border-b border-dashed border-slate-300 space-y-1.5">
            <div class="flex justify-between text-sm font-bold text-slate-900 pt-1">
                <span>TOTAL TAGIHAN:</span>
                <span>Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between pt-2">
                <span class="text-slate-500">Metode Bayar:</span>
                <span>{{ $transaction->payment_method }}</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-slate-500">Status Pembayaran:</span>
                <span class="px-2 py-0.5 rounded font-bold text-[10px] {{ $transaction->payment_status === 'lunas' ? 'bg-slate-900 text-white' : 'bg-slate-200 text-slate-800' }}">
                    {{ strtoupper($transaction->payment_status === 'lunas' ? 'LUNAS' : 'BELUM LUNAS') }}
                </span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-slate-500">Status Pengerjaan:</span>
                <span class="font-bold text-indigo-700">
                    {{ strtoupper($transaction->status) }}
                </span>
            </div>
        </div>

        <!-- Catatan -->
        @if($transaction->notes)
            <div class="py-3 border-b border-dashed border-slate-300 text-[11px] text-slate-600">
                <span class="font-bold block">Catatan:</span>
                {{ $transaction->notes }}
            </div>
        @endif

        <!-- Ketentuan & Footer -->
        <div class="pt-4 text-center text-[10px] text-slate-500 space-y-1">
            <p class="font-semibold text-slate-700">SYARAT & KETENTUAN:</p>
            <p>1. Pengambilan cucian wajib membawa struk nota ini.</p>
            <p>2. Komplain maksimal 1x24 jam setelah cucian diambil.</p>
            <p>3. Barang tidak diambil dalam 30 hari di luar tanggung jawab.</p>
            <p class="pt-3 font-bold text-slate-800 font-sans text-xs">~ TERIMA KASIH ATAS KUNJUNGAN ANDA ~</p>
        </div>

    </div>

</body>
</html>
