<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <div class="flex items-center gap-2">
                    <span class="px-2 py-0.5 rounded bg-raptors-purple text-white text-[10px] font-black uppercase tracking-widest shadow-2xs">
                        REKAP KEUANGAN
                    </span>
                    <h1 class="text-xl font-black text-slate-900 uppercase tracking-tight">Laporan Keuangan & Rekapitulasi</h1>
                </div>
                <p class="text-xs text-slate-500 mt-1 font-medium">Laporan omset pendapatan, rekap piutang, dan kinerja layanan laundry.</p>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('reports.index', array_merge(request()->all(), ['print' => 1])) }}" target="_blank" 
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-gradient-to-r from-raptors-purple to-raptors-red hover:from-[#4E1E80] hover:to-[#B00D35] text-white text-xs sm:text-sm font-black uppercase tracking-wider shadow-md shadow-purple-900/20 transition active:scale-95">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    <span>Cetak Laporan</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6 sm:py-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        {{-- FILTER PERIODE & KRITERIA --}}
        <div class="bg-white p-5 sm:p-6 rounded-2xl shadow-xs border border-purple-100 border-t-4 border-t-raptors-purple">
            <h3 class="font-black text-xs text-raptors-purple uppercase tracking-wider mb-4 flex items-center gap-1.5">
                <span>🔍 Filter Periode Laporan:</span>
            </h3>

            <form method="GET" action="{{ route('reports.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3">
                <!-- Dari Tanggal -->
                <div class="lg:col-span-3">
                    <label class="block text-[11px] font-black uppercase tracking-wider text-slate-600 mb-1">Dari Tanggal:</label>
                    <input type="date" 
                           name="start_date" 
                           value="{{ $startDate }}" 
                           class="w-full px-3 py-2 bg-slate-50 border border-purple-200 rounded-xl text-xs sm:text-sm focus:outline-none focus:border-raptors-purple focus:bg-white font-medium transition" />
                </div>

                <!-- Sampai Tanggal -->
                <div class="lg:col-span-3">
                    <label class="block text-[11px] font-black uppercase tracking-wider text-slate-600 mb-1">Sampai Tanggal:</label>
                    <input type="date" 
                           name="end_date" 
                           value="{{ $endDate }}" 
                           class="w-full px-3 py-2 bg-slate-50 border border-purple-200 rounded-xl text-xs sm:text-sm focus:outline-none focus:border-raptors-purple focus:bg-white font-medium transition" />
                </div>

                <!-- Status Pembayaran -->
                <div class="lg:col-span-2">
                    <label class="block text-[11px] font-black uppercase tracking-wider text-slate-600 mb-1">Pembayaran:</label>
                    <select name="payment_status" class="w-full px-3 py-2 bg-slate-50 border border-purple-200 rounded-xl text-xs sm:text-sm focus:outline-none focus:border-raptors-purple focus:bg-white font-medium transition">
                        <option value="all">Semua Status</option>
                        <option value="lunas" {{ request('payment_status') === 'lunas' ? 'selected' : '' }}>Lunas</option>
                        <option value="belum_lunas" {{ request('payment_status') === 'belum_lunas' ? 'selected' : '' }}>Belum Lunas (Piutang)</option>
                    </select>
                </div>

                <!-- Pilihan Layanan -->
                <div class="lg:col-span-2">
                    <label class="block text-[11px] font-black uppercase tracking-wider text-slate-600 mb-1">Layanan:</label>
                    <select name="package_id" class="w-full px-3 py-2 bg-slate-50 border border-purple-200 rounded-xl text-xs sm:text-sm focus:outline-none focus:border-raptors-purple focus:bg-white font-medium transition">
                        <option value="all">Semua Paket</option>
                        @foreach ($packages as $pkg)
                            <option value="{{ $pkg->id }}" {{ request('package_id') == $pkg->id ? 'selected' : '' }}>
                                {{ $pkg->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Button Filter -->
                <div class="lg:col-span-2 flex items-end gap-2">
                    <button type="submit" class="w-full py-2 px-4 bg-raptors-purple hover:bg-[#4E1E80] text-white text-xs sm:text-sm font-black uppercase tracking-wider rounded-xl transition shadow-xs">
                        Terapkan
                    </button>
                    <a href="{{ route('reports.index') }}" class="py-2 px-3 bg-purple-50 hover:bg-purple-100 text-raptors-purple text-xs sm:text-sm font-bold rounded-xl transition text-center">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        {{-- 4 STATISTIK KEUANGAN (KPI) --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- 1. Total Pemasukan Lunas -->
            <div class="bg-white p-5 rounded-2xl shadow-xs border border-purple-100 border-t-4 border-t-raptors-purple">
                <span class="text-xs font-black text-raptors-purple uppercase tracking-wider block">Total Omset (Lunas)</span>
                <span class="text-xl sm:text-2xl font-black text-slate-900 mt-2 block tracking-tight">
                    Rp {{ number_format($totalIncome, 0, ',', '.') }}
                </span>
                <p class="text-[11px] text-slate-400 mt-1 font-medium">Uang masuk bersih</p>
            </div>

            <!-- 2. Piutang Belum Lunas -->
            <div class="bg-white p-5 rounded-2xl shadow-xs border border-red-100 border-t-4 border-t-raptors-red">
                <span class="text-xs font-black text-raptors-red uppercase tracking-wider block">Piutang (Belum Lunas)</span>
                <span class="text-xl sm:text-2xl font-black text-slate-900 mt-2 block tracking-tight">
                    Rp {{ number_format($unpaidIncome, 0, ',', '.') }}
                </span>
                <p class="text-[11px] text-slate-400 mt-1 font-medium">Tagihan belum tertagih</p>
            </div>

            <!-- 3. Total Transaksi Terfilter -->
            <div class="bg-white p-5 rounded-2xl shadow-xs border border-purple-100 border-t-4 border-t-raptors-lightpurple">
                <span class="text-xs font-black text-slate-700 uppercase tracking-wider block">Jumlah Transaksi</span>
                <span class="text-xl sm:text-2xl font-black text-slate-900 mt-2 block tracking-tight">
                    {{ $totalTransactions }}
                </span>
                <p class="text-[11px] text-slate-400 mt-1 font-medium">Nota pada periode ini</p>
            </div>

            <!-- 4. Total Berat Cucian (Kg) -->
            <div class="bg-white p-5 rounded-2xl shadow-xs border border-slate-200 border-t-4 border-t-[#3B1564]">
                <span class="text-xs font-black text-slate-800 uppercase tracking-wider block">Total Beban Cucian</span>
                <span class="text-xl sm:text-2xl font-black text-slate-900 mt-2 block tracking-tight">
                    {{ number_format($totalWeight, 1, ',', '.') }} <span class="text-sm font-semibold text-slate-400">kg</span>
                </span>
                <p class="text-[11px] text-slate-400 mt-1 font-medium">Volume cucian diproses</p>
            </div>
        </div>

        {{-- BREAKDOWN KINERJA PER LAYANAN --}}
        <div class="bg-white rounded-2xl shadow-xs border border-purple-100 border-t-4 border-t-raptors-red p-5 sm:p-6 overflow-hidden">
            <h3 class="font-black text-sm text-slate-900 uppercase tracking-tight mb-4 flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-raptors-red"></span>
                <span>Ringkasan Kinerja per Jenis Layanan</span>
            </h3>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs sm:text-sm">
                    <thead class="bg-purple-50/70 text-raptors-purple uppercase font-black text-[11px] border-b border-purple-200">
                        <tr>
                            <th class="px-4 py-3">Nama Layanan</th>
                            <th class="px-4 py-3">Tarif Satuan</th>
                            <th class="px-4 py-3">Jumlah Transaksi</th>
                            <th class="px-4 py-3">Total Volume</th>
                            <th class="px-4 py-3 text-right">Total Pendapatan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-purple-50">
                        @forelse ($packagePerformance ?? $packageBreakdown ?? [] as $perf)
                            <tr class="hover:bg-purple-50/30 transition">
                                <td class="px-4 py-3 font-bold text-slate-900 flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full bg-raptors-purple"></span>
                                    <span>{{ $perf['name'] }}</span>
                                </td>
                                <td class="px-4 py-3 text-slate-600 font-medium">
                                    Rp {{ number_format($perf['price'], 0, ',', '.') }} / {{ $perf['unit'] }}
                                </td>
                                <td class="px-4 py-3 font-extrabold text-slate-800">
                                    {{ $perf['count'] }} order
                                </td>
                                <td class="px-4 py-3 text-slate-700 font-semibold">
                                    {{ number_format($perf['quantity'] ?? $perf['total_weight'] ?? 0, 1, ',', '.') }} {{ $perf['unit'] }}
                                </td>
                                <td class="px-4 py-3 text-right font-black text-raptors-purple text-sm sm:text-base">
                                    Rp {{ number_format($perf['revenue'] ?? $perf['total_revenue'] ?? 0, 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-6 text-center text-slate-400 text-xs font-medium">
                                    Tidak ada data untuk periode ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- DETAIL TRANSAKSI HASIL FILTER --}}
        <div class="bg-white rounded-2xl shadow-xs border border-purple-100 overflow-hidden">
            <div class="p-5 border-b border-purple-100 flex justify-between items-center bg-purple-50/40">
                <h3 class="font-black text-sm text-slate-900 uppercase tracking-tight flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-raptors-purple"></span>
                    <span>Rincian Nota Transaksi ({{ $transactions->count() }} Data)</span>
                </h3>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs sm:text-sm">
                    <thead class="bg-purple-50/70 text-raptors-purple uppercase font-black text-[11px] border-b border-purple-200">
                        <tr>
                            <th class="px-4 py-3 whitespace-nowrap">Tanggal</th>
                            <th class="px-4 py-3 whitespace-nowrap">Invoice</th>
                            <th class="px-4 py-3 whitespace-nowrap">Pelanggan</th>
                            <th class="px-4 py-3 whitespace-nowrap">Layanan</th>
                            <th class="px-4 py-3 whitespace-nowrap">Berat</th>
                            <th class="px-4 py-3 whitespace-nowrap">Total</th>
                            <th class="px-4 py-3 whitespace-nowrap">Status Bayar</th>
                            <th class="px-4 py-3 whitespace-nowrap">Kasir</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-purple-50">
                        @forelse ($transactions as $tx)
                            <tr class="hover:bg-purple-50/30 transition">
                                <td class="px-4 py-3 text-slate-500 whitespace-nowrap font-medium">{{ $tx->created_at->format('d/m/Y') }}</td>
                                <td class="px-4 py-3 font-extrabold text-raptors-purple whitespace-nowrap text-xs">{{ $tx->invoice_code }}</td>
                                <td class="px-4 py-3 font-bold text-slate-900 whitespace-nowrap">{{ $tx->customer->name ?? '-' }}</td>
                                <td class="px-4 py-3 text-slate-700 whitespace-nowrap font-semibold">{{ $tx->package->name ?? '-' }}</td>
                                <td class="px-4 py-3 text-slate-600 whitespace-nowrap">{{ $tx->quantity }} {{ $tx->package->unit ?? 'kg' }}</td>
                                <td class="px-4 py-3 font-black text-slate-900 whitespace-nowrap">
                                    Rp {{ number_format($tx->total_price, 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <span class="px-2 py-0.5 rounded-md text-[11px] font-bold {{ $tx->payment_status === 'lunas' ? 'bg-emerald-100 text-emerald-800' : 'bg-red-50 text-raptors-red border border-red-200' }}">
                                        {{ $tx->payment_status === 'lunas' ? 'Lunas' : 'Belum Lunas' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-slate-500 text-xs whitespace-nowrap font-medium">{{ $tx->user->name ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-4 py-8 text-center text-slate-400 text-xs font-medium">
                                    Tidak ada transaksi pada kriteria filter ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-app-layout>
