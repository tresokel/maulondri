<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-black text-xl text-slate-900 leading-tight uppercase tracking-tight flex items-center gap-2">
                    <span>Pencatatan Transaksi Baru</span>
                </h2>
                <p class="text-xs text-slate-500 mt-0.5 font-medium">Input data pelanggan, pilihan paket laundry, dan kalkulasi total biaya otomatis.</p>
            </div>
            <a href="{{ route('transactions.index') }}" class="text-xs font-bold text-slate-500 hover:text-raptors-purple">
                &larr; Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-6 sm:py-10 max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white p-5 sm:p-8 rounded-2xl shadow-xs border border-purple-100 border-t-4 border-t-raptors-red">
            <form action="{{ route('transactions.store') }}" method="POST" class="space-y-4">
                @csrf

                {{-- Pilih Pelanggan --}}
                <div>
                    <div class="flex justify-between items-center mb-1.5">
                        <label class="block text-xs font-black uppercase tracking-wider text-slate-700">Pilih Data Pelanggan</label>
                        <a href="{{ route('customers.create') }}" class="text-xs text-raptors-purple hover:text-raptors-red font-bold">+ Tambah Pelanggan Baru</a>
                    </div>
                    <select name="customer_id" required class="w-full bg-slate-50 border border-purple-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-raptors-purple focus:border-raptors-purple p-2.5 font-medium">
                        <option value="">-- Pilih Pelanggan Terdaftar --</option>
                        @foreach($customers as $c)
                            <option value="{{ $c->id }}" {{ old('customer_id') == $c->id ? 'selected' : '' }}>
                                {{ $c->name }} ({{ $c->phone }})
                            </option>
                        @endforeach
                    </select>
                    @error('customer_id') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                </div>

                {{-- Pilih Paket Laundry --}}
                <div>
                    <label class="block text-xs font-black uppercase tracking-wider text-slate-700 mb-1.5">Pilih Jenis Layanan Cuci</label>
                    <select name="package_id" id="package_select" required class="w-full bg-slate-50 border border-purple-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-raptors-purple focus:border-raptors-purple p-2.5 font-medium">
                        <option value="" data-price="0" data-unit="" data-duration="">-- Pilih Paket Layanan Laundry --</option>
                        @foreach($packages as $p)
                            <option value="{{ $p->id }}" data-price="{{ $p->price }}" data-unit="{{ $p->unit }}" data-duration="{{ $p->duration }}" {{ old('package_id') == $p->id ? 'selected' : '' }}>
                                {{ $p->name }} - Rp {{ number_format($p->price, 0, ',', '.') }} / {{ $p->unit }} {{ $p->duration ? "({$p->duration})" : '' }}
                            </option>
                        @endforeach
                    </select>
                    @error('package_id') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                </div>

                {{-- Berat / Jumlah --}}
                <div>
                    <label class="block text-xs font-black uppercase tracking-wider text-slate-700 mb-1.5">
                        Jumlah / Berat Cucian <span id="unit_label" class="text-slate-400 font-normal"></span>
                    </label>
                    <input type="number" step="0.1" name="quantity" id="quantity_input" value="{{ old('quantity', 1) }}" required min="0.1" class="w-full bg-slate-50 border border-purple-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-raptors-purple focus:border-raptors-purple p-2.5 font-medium">
                    @error('quantity') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                </div>

                {{-- Preview Total Harga Otomatis (Live Calculation) --}}
                <div class="p-4 bg-gradient-to-r from-purple-50 via-purple-50/60 to-red-50/60 rounded-xl border border-purple-200 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2">
                    <div>
                        <span class="text-xs font-black uppercase tracking-wider text-raptors-purple block">Total Biaya Cucian:</span>
                        <span id="detail_calc" class="text-[11px] text-purple-700 font-semibold">Pilih paket dan masukkan berat cucian</span>
                    </div>
                    <span id="total_display" class="text-2xl font-black text-raptors-red tracking-tight">Rp 0</span>
                </div>

                {{-- Status Pembayaran --}}
                <div>
                    <label class="block text-xs font-black uppercase tracking-wider text-slate-700 mb-2">Status Pembayaran</label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <label class="flex items-center gap-2.5 p-3 rounded-xl border border-purple-200 bg-slate-50 cursor-pointer hover:bg-white transition has-[:checked]:border-raptors-purple has-[:checked]:bg-purple-50/40">
                            <input type="radio" name="payment_status" value="belum_lunas" {{ old('payment_status', 'belum_lunas') == 'belum_lunas' ? 'checked' : '' }} class="text-raptors-purple focus:ring-raptors-purple">
                            <div>
                                <span class="text-sm font-bold text-slate-800 block">Belum Lunas</span>
                                <span class="text-[11px] text-slate-400">Bayar saat cucian selesai/diambil</span>
                            </div>
                        </label>
                        <label class="flex items-center gap-2.5 p-3 rounded-xl border border-purple-200 bg-slate-50 cursor-pointer hover:bg-white transition has-[:checked]:border-raptors-red has-[:checked]:bg-red-50/40">
                            <input type="radio" name="payment_status" value="lunas" {{ old('payment_status') == 'lunas' ? 'checked' : '' }} class="text-raptors-red focus:ring-raptors-red">
                            <div>
                                <span class="text-sm font-bold text-slate-800 block">Lunas (Bayar Sekarang)</span>
                                <span class="text-[11px] text-slate-400">Uang sudah diterima kasir</span>
                            </div>
                        </label>
                    </div>
                    @error('payment_status') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                </div>

                {{-- Metode Pembayaran --}}
                <div>
                    <label class="block text-xs font-black uppercase tracking-wider text-slate-700 mb-1.5">Metode Pembayaran</label>
                    <select name="payment_method" class="w-full bg-slate-50 border border-purple-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-raptors-purple focus:border-raptors-purple p-2.5 font-medium">
                        <option value="Tunai" {{ old('payment_method') == 'Tunai' ? 'selected' : '' }}>💵 Tunai (Cash)</option>
                        <option value="QRIS" {{ old('payment_method') == 'QRIS' ? 'selected' : '' }}>📱 QRIS / E-Wallet</option>
                        <option value="Transfer" {{ old('payment_method') == 'Transfer' ? 'selected' : '' }}>🏦 Transfer Bank</option>
                    </select>
                </div>

                {{-- Catatan --}}
                <div>
                    <label class="block text-xs font-black uppercase tracking-wider text-slate-700 mb-1.5">Catatan Khusus Cucian (Opsional)</label>
                    <textarea name="notes" rows="2" placeholder="Contoh: Baju putih dipisah, wangi mawar..." class="w-full bg-slate-50 border border-purple-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-raptors-purple focus:border-raptors-purple p-2.5 font-medium">{{ old('notes') }}</textarea>
                </div>

                <div class="flex flex-col sm:flex-row items-center gap-3 pt-3">
                    <button type="submit" class="w-full sm:w-auto bg-gradient-to-r from-raptors-purple to-raptors-red hover:from-[#4E1E80] hover:to-[#B00D35] text-white font-black uppercase tracking-wider px-6 py-2.5 rounded-xl text-sm shadow-md shadow-purple-900/25 transition active:scale-95">
                        Simpan Transaksi
                    </button>
                    <a href="{{ route('transactions.index') }}" class="w-full sm:w-auto text-center py-2.5 text-slate-600 hover:text-slate-800 text-sm font-medium">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Script Live Calculation Laundry --}}
    <script>
        const packageSelect = document.getElementById('package_select');
        const quantityInput = document.getElementById('quantity_input');
        const totalDisplay = document.getElementById('total_display');
        const unitLabel = document.getElementById('unit_label');
        const detailCalc = document.getElementById('detail_calc');

        function calculateTotal() {
            const selected = packageSelect.options[packageSelect.selectedIndex];
            const price = parseFloat(selected?.getAttribute('data-price')) || 0;
            const unit = selected?.getAttribute('data-unit') || '';
            const duration = selected?.getAttribute('data-duration') || '';
            const qty = parseFloat(quantityInput.value) || 0;

            unitLabel.textContent = unit ? '(' + unit + ')' : '';
            const total = Math.round(price * qty);
            totalDisplay.textContent = 'Rp ' + total.toLocaleString('id-ID');

            if (price > 0 && qty > 0) {
                detailCalc.textContent = qty + ' ' + unit + ' x Rp ' + price.toLocaleString('id-ID') + (duration ? ' (Estimasi: ' + duration + ')' : '');
            } else {
                detailCalc.textContent = 'Pilih paket dan masukkan berat cucian';
            }
        }

        packageSelect.addEventListener('change', calculateTotal);
        quantityInput.addEventListener('input', calculateTotal);
        calculateTotal();
    </script>
</x-app-layout>
