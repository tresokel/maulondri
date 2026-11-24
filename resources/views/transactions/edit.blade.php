<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-black text-xl text-slate-900 leading-tight uppercase tracking-tight flex items-center gap-2">
                    <span>Ubah Status Transaksi</span>
                </h2>
                <p class="text-xs text-slate-500 mt-0.5 font-medium">Update status pengerjaan cucian (Proses ke Selesai) dan status pembayaran.</p>
            </div>
            <a href="{{ route('transactions.index') }}" class="text-xs font-bold text-slate-500 hover:text-raptors-purple">
                &larr; Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-6 sm:py-10 max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white p-5 sm:p-8 rounded-2xl shadow-xs border border-purple-100 border-t-4 border-t-raptors-purple">
            
            {{-- Informasi Singkat Order Cucian --}}
            <div class="mb-6 p-4 bg-purple-50/50 rounded-xl border border-purple-200 text-xs sm:text-sm space-y-1.5">
                <div class="flex justify-between items-center pb-2 border-b border-purple-200/60">
                    <span class="text-slate-500 font-bold uppercase tracking-wider text-[11px]">Nomor Invoice:</span>
                    <strong class="text-raptors-purple font-black">{{ $transaction->invoice_code }}</strong>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-slate-500">Pelanggan:</span>
                    <strong class="text-slate-900 font-bold">{{ $transaction->customer->name ?? '-' }} ({{ $transaction->customer->phone ?? '-' }})</strong>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-slate-500">Paket Layanan:</span>
                    <span class="font-bold text-slate-800">{{ $transaction->package->name ?? '-' }} ({{ $transaction->quantity }} {{ $transaction->package->unit ?? 'kg' }})</span>
                </div>
                <div class="flex justify-between items-center pt-2 border-t border-purple-200/60">
                    <span class="text-slate-600 font-bold">Total Biaya Cucian:</span>
                    <strong class="text-base font-black text-raptors-red">Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</strong>
                </div>
            </div>

            <form action="{{ route('transactions.update', $transaction->id) }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                {{-- Status Pengerjaan Cucian --}}
                <div>
                    <label class="block text-xs font-black uppercase tracking-wider text-slate-700 mb-1.5">Status Pengerjaan Cucian</label>
                    <select name="status" required class="w-full bg-slate-50 border border-purple-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-raptors-purple focus:border-raptors-purple p-2.5 font-medium">
                        <option value="baru" {{ old('status', $transaction->status) == 'baru' ? 'selected' : '' }}>🟣 Baru Masuk (Antrean)</option>
                        <option value="proses" {{ old('status', $transaction->status) == 'proses' ? 'selected' : '' }}>🔴 Sedang Dicuci / Diproses</option>
                        <option value="selesai" {{ old('status', $transaction->status) == 'selesai' ? 'selected' : '' }}>🟢 Selesai (Siap Diambil)</option>
                        <option value="diambil" {{ old('status', $transaction->status) == 'diambil' ? 'selected' : '' }}>⚪ Sudah Diambil Pelanggan</option>
                    </select>
                    @error('status') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                </div>

                {{-- Status Pembayaran --}}
                <div>
                    <label class="block text-xs font-black uppercase tracking-wider text-slate-700 mb-1.5">Status Pembayaran</label>
                    <select name="payment_status" required class="w-full bg-slate-50 border border-purple-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-raptors-purple focus:border-raptors-purple p-2.5 font-medium">
                        <option value="belum_lunas" {{ old('payment_status', $transaction->payment_status) == 'belum_lunas' ? 'selected' : '' }}>🔴 Belum Lunas (Bayar Nanti)</option>
                        <option value="lunas" {{ old('payment_status', $transaction->payment_status) == 'lunas' ? 'selected' : '' }}>🟢 Lunas (Uang Diterima)</option>
                    </select>
                    @error('payment_status') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                </div>

                {{-- Metode Pembayaran --}}
                <div>
                    <label class="block text-xs font-black uppercase tracking-wider text-slate-700 mb-1.5">Metode Pembayaran</label>
                    <select name="payment_method" class="w-full bg-slate-50 border border-purple-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-raptors-purple focus:border-raptors-purple p-2.5 font-medium">
                        <option value="Tunai" {{ old('payment_method', $transaction->payment_method) == 'Tunai' ? 'selected' : '' }}>💵 Tunai (Cash)</option>
                        <option value="QRIS" {{ old('payment_method', $transaction->payment_method) == 'QRIS' ? 'selected' : '' }}>📱 QRIS / E-Wallet</option>
                        <option value="Transfer" {{ old('payment_method', $transaction->payment_method) == 'Transfer' ? 'selected' : '' }}>🏦 Transfer Bank</option>
                    </select>
                </div>

                {{-- Catatan --}}
                <div>
                    <label class="block text-xs font-black uppercase tracking-wider text-slate-700 mb-1.5">Catatan Khusus</label>
                    <textarea name="notes" rows="2" class="w-full bg-slate-50 border border-purple-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-raptors-purple focus:border-raptors-purple p-2.5 font-medium">{{ old('notes', $transaction->notes) }}</textarea>
                </div>

                <div class="flex flex-col sm:flex-row items-center gap-3 pt-3">
                    <button type="submit" class="w-full sm:w-auto bg-gradient-to-r from-raptors-purple to-raptors-red hover:from-[#4E1E80] hover:to-[#B00D35] text-white font-black uppercase tracking-wider px-6 py-2.5 rounded-xl text-sm shadow-md shadow-purple-900/25 transition active:scale-95">
                        Simpan Perubahan Status
                    </button>
                    <a href="{{ route('transactions.index') }}" class="w-full sm:w-auto text-center py-2.5 text-slate-600 hover:text-slate-800 text-sm font-medium">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
