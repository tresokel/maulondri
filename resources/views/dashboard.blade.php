<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-3">
            <div>
                <div class="flex items-center gap-2">
                    <span class="px-2 py-0.5 rounded bg-raptors-purple text-white text-[10px] font-black uppercase tracking-widest shadow-2xs">
                        PANEL UTAMA
                    </span>
                    <h2 class="font-black text-xl text-slate-900 leading-tight tracking-tight uppercase">
                        <span>Dashboard Manajemen Laundry</span>
                    </h2>
                </div>
                <p class="text-xs text-slate-500 mt-1 font-medium">
                    Operator: <strong class="text-raptors-purple font-bold">{{ Auth::user()->name }}</strong> 
                    <span class="px-1.5 py-0.2 rounded text-[10px] font-black uppercase {{ Auth::user()->role === 'admin' ? 'bg-purple-100 text-raptors-purple' : 'bg-red-100 text-raptors-red' }}">
                        {{ ucfirst(Auth::user()->role) }}
                    </span> 
                    &bull; Pantau operasional cucian & rekapan transaksi harian
                </p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('transactions.create') }}" class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-gradient-to-r from-raptors-purple to-raptors-red hover:from-[#4E1E80] hover:to-[#B00D35] text-white rounded-xl text-xs sm:text-sm font-black uppercase tracking-wider shadow-md shadow-purple-900/25 transition active:scale-95">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>+ Transaksi Baru</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6 sm:py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- 4 KARTU STATISTIK UTAMA --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- 1. Total Transaksi -->
                <div class="bg-white p-5 rounded-2xl shadow-xs border border-purple-100 border-t-4 border-t-raptors-purple hover:shadow-md transition">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-black text-slate-400 uppercase tracking-wider">Total Transaksi</span>
                        <div class="w-10 h-10 rounded-xl bg-purple-100/80 text-raptors-purple flex items-center justify-center font-black">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-2 text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">{{ $totalTransactions }}</div>
                    <p class="text-[11px] text-slate-400 mt-1 font-medium">Semua pesanan tercatat</p>
                </div>

                <!-- 2. Sedang Dicuci / Diproses -->
                <div class="bg-white p-5 rounded-2xl shadow-xs border border-red-100 border-t-4 border-t-raptors-red hover:shadow-md transition">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-black text-raptors-red uppercase tracking-wider">Sedang Dicuci</span>
                        <div class="w-10 h-10 rounded-xl bg-red-100/80 text-raptors-red flex items-center justify-center font-black animate-pulse">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <rect x="4" y="2" width="16" height="20" rx="3" stroke-width="2"/>
                                <circle cx="12" cy="13" r="4" stroke-width="2"/>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-2 text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">{{ $processingCount }}</div>
                    <p class="text-[11px] text-red-600/80 font-bold mt-1">Dalam proses pencucian</p>
                </div>

                <!-- 3. Siap Diambil -->
                <div class="bg-white p-5 rounded-2xl shadow-xs border border-purple-100 border-t-4 border-t-raptors-lightpurple hover:shadow-md transition">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-black text-purple-700 uppercase tracking-wider">Siap Diambil</span>
                        <div class="w-10 h-10 rounded-xl bg-purple-100/80 text-purple-700 flex items-center justify-center font-black">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-2 text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">{{ $readyCount }}</div>
                    <p class="text-[11px] text-purple-600/80 font-bold mt-1">Selesai dicuci & disetrika</p>
                </div>

                <!-- 4. Total Pendapatan Lunas -->
                <div class="bg-white p-5 rounded-2xl shadow-xs border border-slate-200 border-t-4 border-t-[#3B1564] hover:shadow-md transition">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-black text-slate-800 uppercase tracking-wider">Pendapatan Lunas</span>
                        <div class="w-10 h-10 rounded-xl bg-slate-900 text-amber-400 flex items-center justify-center font-black">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-2 text-xl sm:text-2xl font-black text-slate-900 tracking-tight">Rp {{ number_format($totalIncome, 0, ',', '.') }}</div>
                    <p class="text-[11px] text-slate-400 mt-1 font-medium">Kas pembayaran terverifikasi</p>
                </div>
            </div>

            {{-- TABEL 5 TRANSAKSI TERAKHIR --}}
            <div class="bg-white overflow-hidden shadow-xs rounded-2xl border border-purple-100">
                <div class="p-4 sm:p-6">
                    <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-2 mb-4">
                        <div>
                            <h3 class="font-black text-base sm:text-lg text-slate-900 uppercase tracking-tight flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-raptors-red"></span>
                                <span>5 Transaksi Terkini</span>
                            </h3>
                            <p class="text-xs text-slate-400">Daftar transaksi pelanggan yang baru masuk atau diperbarui</p>
                        </div>
                        <a href="{{ route('transactions.index') }}" class="text-xs sm:text-sm font-bold text-raptors-purple hover:text-raptors-red transition inline-flex items-center gap-1 uppercase tracking-wider">
                            <span>Semua Transaksi</span>
                            <span>&rarr;</span>
                        </a>
                    </div>

                    <div class="overflow-x-auto -mx-4 sm:mx-0">
                        <table class="min-w-full divide-y divide-purple-100 text-sm">
                            <thead class="bg-purple-50/70 text-raptors-purple text-xs font-black uppercase tracking-wider border-b border-purple-200">
                                <tr>
                                    <th class="px-4 py-3.5 text-left whitespace-nowrap">Pelanggan</th>
                                    <th class="px-4 py-3.5 text-left whitespace-nowrap">Paket Cucian</th>
                                    <th class="px-4 py-3.5 text-left whitespace-nowrap">Berat/Qty</th>
                                    <th class="px-4 py-3.5 text-left whitespace-nowrap">Total Biaya</th>
                                    <th class="px-4 py-3.5 text-left whitespace-nowrap">Status Pengerjaan</th>
                                    <th class="px-4 py-3.5 text-left whitespace-nowrap">Pembayaran</th>
                                    <th class="px-4 py-3.5 text-left whitespace-nowrap">Kasir</th>
                                    <th class="px-4 py-3.5 text-center whitespace-nowrap">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-purple-50">
                                @forelse($recentTransactions as $tx)
                                    <tr class="hover:bg-purple-50/40 transition">
                                        <td class="px-4 py-3.5 whitespace-nowrap">
                                            <div class="font-bold text-slate-900">{{ $tx->customer->name ?? '-' }}</div>
                                            <div class="text-[11px] text-slate-400">{{ $tx->customer->phone ?? '' }}</div>
                                        </td>
                                        <td class="px-4 py-3.5 whitespace-nowrap">
                                            <span class="font-semibold text-slate-800">{{ $tx->package->name ?? '-' }}</span>
                                            @if($tx->package && $tx->package->duration)
                                                <span class="block text-[11px] text-slate-400">({{ $tx->package->duration }})</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3.5 whitespace-nowrap font-bold text-slate-700">
                                            {{ $tx->quantity }} {{ $tx->package->unit ?? 'kg' }}
                                        </td>
                                        <td class="px-4 py-3.5 whitespace-nowrap font-black text-slate-900 text-base">
                                            Rp {{ number_format($tx->total_price, 0, ',', '.') }}
                                        </td>
                                        <td class="px-4 py-3.5 whitespace-nowrap">
                                            @php
                                                $statusConfig = [
                                                    'baru' => ['label' => 'Baru Masuk', 'class' => 'bg-purple-100 text-raptors-purple border border-purple-300'],
                                                    'proses' => ['label' => 'Sedang Dicuci', 'class' => 'bg-red-100 text-raptors-red border border-red-300'],
                                                    'selesai' => ['label' => 'Siap Diambil', 'class' => 'bg-emerald-100 text-emerald-800 border border-emerald-300'],
                                                    'diambil' => ['label' => 'Sudah Diambil', 'class' => 'bg-slate-100 text-slate-700 border border-slate-300'],
                                                ];
                                                $cfg = $statusConfig[$tx->status] ?? ['label' => ucfirst($tx->status), 'class' => 'bg-slate-100 text-slate-700'];
                                            @endphp
                                            <span class="px-2.5 py-1 text-xs font-bold rounded-lg {{ $cfg['class'] }}">
                                                {{ $cfg['label'] }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3.5 whitespace-nowrap">
                                            <span class="px-2.5 py-1 text-xs font-bold rounded-lg {{ $tx->payment_status === 'lunas' ? 'bg-emerald-100 text-emerald-800 border border-emerald-300' : 'bg-red-50 text-raptors-red border border-red-200' }}">
                                                {{ $tx->payment_status === 'lunas' ? 'Lunas' : 'Belum Lunas' }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3.5 text-slate-500 text-xs whitespace-nowrap font-medium">
                                            {{ $tx->user->name ?? '-' }}
                                        </td>
                                        <td class="px-4 py-3.5 text-center whitespace-nowrap space-x-1">
                                            <a href="{{ route('transactions.edit', $tx->id) }}" class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-purple-100 text-raptors-purple hover:bg-purple-200 transition">
                                                Update Status
                                            </a>
                                            <a href="{{ route('transactions.receipt', $tx->id) }}" target="_blank" class="inline-flex items-center px-2 py-1 rounded-md text-xs font-bold bg-slate-100 text-slate-700 hover:bg-slate-200 transition" title="Cetak Nota">
                                                Nota
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="px-4 py-10 text-center text-slate-400">
                                            <div class="flex flex-col items-center justify-center">
                                                <span class="text-3xl mb-2">🦖</span>
                                                <p class="font-bold text-slate-500">Belum ada transaksi laundry saat ini.</p>
                                                <a href="{{ route('transactions.create') }}" class="mt-2 text-xs text-raptors-red font-bold hover:underline">+ Catat Transaksi Baru</a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
