<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-3">
            <div>
                <div class="flex items-center gap-2">
                    <span class="px-2 py-0.5 rounded bg-raptors-purple text-white text-[10px] font-black uppercase tracking-widest shadow-2xs">
                        REKAP
                    </span>
                    <h2 class="font-black text-xl text-slate-900 leading-tight uppercase tracking-tight">
                        <span>Daftar Transaksi Laundry</span>
                    </h2>
                </div>
                <p class="text-xs text-slate-500 mt-1 font-medium">Rekapitulasi pesanan cucian pelanggan, status pengerjaan, dan pencatatan pembayaran.</p>
            </div>
            <a href="{{ route('transactions.create') }}" class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-gradient-to-r from-raptors-purple to-raptors-red hover:from-[#4E1E80] hover:to-[#B00D35] text-white rounded-xl text-xs sm:text-sm font-black uppercase tracking-wider shadow-md shadow-purple-900/20 transition active:scale-95">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                </svg>
                <span>+ Buat Transaksi Baru</span>
            </a>
        </div>
    </x-slot>

    <div class="py-6 sm:py-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-4">

        @if(session('success'))
            <div class="p-3.5 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-900 rounded-r-xl text-xs sm:text-sm font-bold flex items-center gap-2">
                <span>✓</span>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <div class="bg-white overflow-hidden shadow-xs rounded-2xl border border-purple-100 border-t-4 border-t-raptors-purple">
            <div class="p-4 sm:p-6 overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-600">
                    <thead class="bg-purple-50/70 text-raptors-purple uppercase font-black text-xs border-b border-purple-200">
                        <tr>
                            <th class="p-3.5 whitespace-nowrap">Tanggal & Invoice</th>
                            <th class="p-3.5 whitespace-nowrap">Pelanggan</th>
                            <th class="p-3.5 whitespace-nowrap">Paket Cucian</th>
                            <th class="p-3.5 whitespace-nowrap">Berat/Qty</th>
                            <th class="p-3.5 whitespace-nowrap">Total Biaya</th>
                            <th class="p-3.5 whitespace-nowrap">Status Pengerjaan</th>
                            <th class="p-3.5 whitespace-nowrap">Pembayaran</th>
                            <th class="p-3.5 whitespace-nowrap">Kasir</th>
                            <th class="p-3.5 text-center whitespace-nowrap">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-purple-50">
                        @forelse($transactions as $tx)
                            <tr class="hover:bg-purple-50/30 transition">
                                <td class="p-3.5 text-slate-500 whitespace-nowrap">
                                    <div class="font-extrabold text-slate-900 text-xs">{{ $tx->invoice_code }}</div>
                                    <div class="text-[11px] text-slate-400 font-medium">{{ $tx->created_at->format('d/m/Y H:i') }}</div>
                                </td>
                                <td class="p-3.5 font-bold text-slate-900 whitespace-nowrap">
                                    <div class="font-extrabold text-slate-900">{{ $tx->customer->name ?? '-' }}</div>
                                    <div class="text-[11px] text-slate-400 font-medium">{{ $tx->customer->phone ?? '' }}</div>
                                </td>
                                <td class="p-3.5 whitespace-nowrap">
                                    <span class="font-bold text-slate-800">{{ $tx->package->name ?? '-' }}</span>
                                    @if($tx->package && $tx->package->duration)
                                        <span class="block text-[11px] text-slate-400">({{ $tx->package->duration }})</span>
                                    @endif
                                </td>
                                <td class="p-3.5 whitespace-nowrap font-bold text-slate-700">
                                    {{ $tx->quantity }} {{ $tx->package->unit ?? 'kg' }}
                                </td>
                                <td class="p-3.5 font-black text-slate-900 whitespace-nowrap text-base">
                                    Rp {{ number_format($tx->total_price, 0, ',', '.') }}
                                </td>
                                <td class="p-3.5 whitespace-nowrap">
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
                                <td class="p-3.5 whitespace-nowrap">
                                    <span class="px-2.5 py-1 text-xs font-bold rounded-lg {{ $tx->payment_status === 'lunas' ? 'bg-emerald-100 text-emerald-800 border border-emerald-300' : 'bg-red-50 text-raptors-red border border-red-200' }}">
                                        {{ $tx->payment_status === 'lunas' ? 'Lunas' : 'Belum Lunas' }}
                                    </span>
                                </td>
                                <td class="p-3.5 text-slate-500 text-xs whitespace-nowrap font-medium">
                                    {{ $tx->user->name ?? '-' }}
                                </td>
                                <td class="p-3.5 text-center space-x-1.5 whitespace-nowrap">
                                    <a href="{{ route('transactions.edit', $tx->id) }}" class="inline-flex items-center px-2.5 py-1 bg-purple-100 text-raptors-purple hover:bg-purple-200 rounded-md text-xs font-bold transition">
                                        Update Status
                                    </a>
                                    <a href="{{ route('transactions.receipt', $tx->id) }}" target="_blank" class="inline-flex items-center px-2 py-1 bg-slate-100 text-slate-700 hover:bg-slate-200 rounded-md text-xs font-bold transition" title="Cetak Nota">
                                        Nota
                                    </a>
                                    <form action="{{ route('transactions.destroy', $tx->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus transaksi ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center px-2 py-1 bg-red-50 text-raptors-red hover:bg-red-100 rounded-md text-xs font-bold transition">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="p-8 text-center text-slate-400 font-medium">
                                    Belum ada transaksi laundry saat ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-4">
                    {{ $transactions->links() }}
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
