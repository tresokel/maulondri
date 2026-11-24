<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('transactions.index') }}" class="p-2 rounded-xl text-slate-500 hover:text-slate-800 hover:bg-slate-100 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <div>
                <h1 class="text-xl font-bold text-slate-800">Detail Transaksi {{ $transaction->invoice_code }}</h1>
                <p class="text-xs text-slate-500">Informasi lengkap pesanan cucian, riwayat status, dan nota pelanggan.</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto space-y-6">

        {{-- STATUS HEADER CARD --}}
        <div class="bg-white rounded-3xl shadow-xs border border-slate-200/80 p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Status Cucian Saat Ini:</span>
                    <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider {{ $transaction->status === 'selesai' ? 'bg-emerald-100 text-emerald-800 border border-emerald-200' : ($transaction->status === 'proses' ? 'bg-amber-100 text-amber-800 border border-amber-200' : ($transaction->status === 'diambil' ? 'bg-slate-200 text-slate-800 border border-slate-300' : 'bg-blue-100 text-blue-800 border border-blue-200')) }}">
                        ● {{ ucfirst($transaction->status) }}
                    </span>
                </div>
                <h2 class="text-xl font-extrabold text-slate-800">{{ $transaction->customer->name }}</h2>
                <p class="text-xs text-slate-500">Didaftarkan pada {{ $transaction->created_at->format('l, d F Y - H:i') }} oleh kasir <strong>{{ $transaction->user->name }}</strong></p>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('transactions.receipt', $transaction) }}" target="_blank" class="px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs shadow-md shadow-emerald-600/20 transition flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    <span>Cetak Nota Struk</span>
                </a>

                <a href="{{ route('transactions.edit', $transaction) }}" class="px-4 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-900 text-white font-semibold text-xs transition">
                    Ubah Status / Bayar
                </a>
            </div>
        </div>

        {{-- GRID DETAILS --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            {{-- PELANGGAN & OPERATOR --}}
            <div class="bg-white rounded-3xl shadow-xs border border-slate-200/80 p-6 space-y-4">
                <h3 class="font-bold text-sm text-slate-800 border-b border-slate-100 pb-3 flex items-center gap-2">
                    <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    Informasi Pelanggan & Kasir
                </h3>

                <div class="space-y-3 text-xs">
                    <div class="flex justify-between">
                        <span class="text-slate-400">Nama Pelanggan:</span>
                        <span class="font-bold text-slate-800">{{ $transaction->customer->name }}</span>
                    </div>

                    <div class="flex justify-between items-center">
                        <span class="text-slate-400">Nomor Telepon/WA:</span>
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $transaction->customer->phone) }}" target="_blank" class="font-semibold text-emerald-600 hover:underline">
                            {{ $transaction->customer->phone }} (WhatsApp)
                        </a>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-slate-400">Alamat:</span>
                        <span class="text-slate-700 text-right max-w-xs">{{ $transaction->customer->address ?? 'Tidak ada alamat tercatat' }}</span>
                    </div>

                    <div class="flex justify-between pt-3 border-t border-slate-100">
                        <span class="text-slate-400">Petugas / Kasir:</span>
                        <span class="font-semibold text-indigo-600">{{ $transaction->user->name }} ({{ ucfirst($transaction->user->role) }})</span>
                    </div>
                </div>
            </div>

            {{-- RINCIAN LAYANAN & HARGA --}}
            <div class="bg-white rounded-3xl shadow-xs border border-slate-200/80 p-6 space-y-4">
                <h3 class="font-bold text-sm text-slate-800 border-b border-slate-100 pb-3 flex items-center gap-2">
                    <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                    </svg>
                    Rincian Layanan Cucian
                </h3>

                <div class="space-y-3 text-xs">
                    <div class="flex justify-between">
                        <span class="text-slate-400">Jenis Layanan:</span>
                        <span class="font-bold text-slate-800">{{ $transaction->package->name }}</span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-slate-400">Estimasi Durasi:</span>
                        <span class="font-semibold text-indigo-600">{{ $transaction->package->duration ?? 'Sesuai antrean' }}</span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-slate-400">Tarif Satuan:</span>
                        <span>Rp {{ number_format($transaction->package->price, 0, ',', '.') }} / {{ $transaction->package->unit }}</span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-slate-400">Jumlah / Berat:</span>
                        <span class="font-bold text-slate-900">{{ $transaction->quantity }} {{ $transaction->package->unit }}</span>
                    </div>

                    <div class="flex justify-between items-center pt-3 border-t border-slate-100">
                        <span class="text-slate-400">Status Pembayaran:</span>
                        <span class="px-2 py-0.5 rounded text-[11px] font-bold {{ $transaction->payment_status === 'lunas' ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                            {{ $transaction->payment_status === 'lunas' ? '✓ LUNAS' : 'BELUM LUNAS' }} ({{ $transaction->payment_method }})
                        </span>
                    </div>

                    <div class="flex justify-between items-baseline pt-2">
                        <span class="text-sm font-bold text-slate-800">Total Biaya:</span>
                        <span class="text-xl font-extrabold text-indigo-600">
                            Rp {{ number_format($transaction->total_price, 0, ',', '.') }}
                        </span>
                    </div>
                </div>
            </div>

        </div>

        {{-- CATATAN & QUICK STATUS WORKFLOW --}}
        <div class="bg-white rounded-3xl shadow-xs border border-slate-200/80 p-6 space-y-4">
            <h3 class="font-bold text-sm text-slate-800 border-b border-slate-100 pb-3">Alur & Catatan Cucian</h3>

            @if($transaction->notes)
                <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-700">
                    <strong class="text-slate-900 block mb-1">Catatan dari Kasir:</strong>
                    {{ $transaction->notes }}
                </div>
            @endif

            <div class="pt-2 flex flex-wrap items-center justify-between gap-4">
                <span class="text-xs text-slate-500">Perbarui Cepat Tahapan Cucian:</span>

                <div class="flex flex-wrap items-center gap-2">
                    <form method="POST" action="{{ route('transactions.quick-status', $transaction) }}">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="baru">
                        <button type="submit" class="px-3 py-1.5 rounded-lg text-xs font-semibold {{ $transaction->status === 'baru' ? 'bg-blue-600 text-white shadow-xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                            1. Baru
                        </button>
                    </form>

                    <form method="POST" action="{{ route('transactions.quick-status', $transaction) }}">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="proses">
                        <button type="submit" class="px-3 py-1.5 rounded-lg text-xs font-semibold {{ $transaction->status === 'proses' ? 'bg-amber-500 text-white shadow-xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                            2. Proses Cuci
                        </button>
                    </form>

                    <form method="POST" action="{{ route('transactions.quick-status', $transaction) }}">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="selesai">
                        <button type="submit" class="px-3 py-1.5 rounded-lg text-xs font-semibold {{ $transaction->status === 'selesai' ? 'bg-emerald-600 text-white shadow-xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                            3. Selesai
                        </button>
                    </form>

                    <form method="POST" action="{{ route('transactions.quick-status', $transaction) }}">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="diambil">
                        <button type="submit" class="px-3 py-1.5 rounded-lg text-xs font-semibold {{ $transaction->status === 'diambil' ? 'bg-slate-800 text-white shadow-xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                            4. Diambil Pelanggan
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
