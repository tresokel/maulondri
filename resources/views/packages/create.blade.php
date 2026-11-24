<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-black text-xl text-slate-900 leading-tight uppercase tracking-tight">
                {{ __('Tambah Paket Laundry Baru') }}
            </h2>
            <a href="{{ route('packages.index') }}" class="text-xs font-bold text-slate-500 hover:text-raptors-purple">
                &larr; Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-6 sm:py-10 max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white p-5 sm:p-8 rounded-2xl shadow-xs border border-purple-100 border-t-4 border-t-raptors-purple">
            <form action="{{ route('packages.store') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-xs font-black uppercase tracking-wider text-slate-700 mb-1.5">Nama Paket</label>
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="Contoh: Reguler, Ekspres, Superkilat" required class="w-full bg-slate-50 border border-purple-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-raptors-purple focus:border-raptors-purple p-2.5 font-medium">
                    @error('name') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-black uppercase tracking-wider text-slate-700 mb-1.5">Harga (Rp)</label>
                    <input type="number" name="price" value="{{ old('price') }}" placeholder="Contoh: 7000" required class="w-full bg-slate-50 border border-purple-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-raptors-purple focus:border-raptors-purple p-2.5 font-medium">
                    @error('price') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-black uppercase tracking-wider text-slate-700 mb-1.5">Satuan</label>
                    <select name="unit" required class="w-full bg-slate-50 border border-purple-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-raptors-purple focus:border-raptors-purple p-2.5 font-medium">
                        <option value="kg" {{ old('unit') == 'kg' ? 'selected' : '' }}>Kilogram (kg)</option>
                        <option value="pcs" {{ old('unit') == 'pcs' ? 'selected' : '' }}>Potong / Pcs</option>
                        <option value="meter" {{ old('unit') == 'meter' ? 'selected' : '' }}>Meter</option>
                    </select>
                    @error('unit') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-black uppercase tracking-wider text-slate-700 mb-1.5">Durasi / Estimasi Pengerjaan</label>
                    <input type="text" name="duration" value="{{ old('duration') }}" placeholder="Contoh: 3-4 hari, 1-2 hari, 5 jam selesai" class="w-full bg-slate-50 border border-purple-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-raptors-purple focus:border-raptors-purple p-2.5 font-medium">
                    @error('duration') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-black uppercase tracking-wider text-slate-700 mb-1.5">Deskripsi (Opsional)</label>
                    <textarea name="description" rows="2" placeholder="Keterangan singkat paket layanan..." class="w-full bg-slate-50 border border-purple-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-raptors-purple focus:border-raptors-purple p-2.5 font-medium">{{ old('description') }}</textarea>
                    @error('description') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="flex flex-col sm:flex-row items-center gap-3 pt-3">
                    <button type="submit" class="w-full sm:w-auto bg-gradient-to-r from-raptors-purple to-raptors-red hover:from-[#4E1E80] hover:to-[#B00D35] text-white font-black uppercase tracking-wider px-6 py-2.5 rounded-xl text-sm shadow-md shadow-purple-900/25 transition active:scale-95">
                        Simpan Layanan
                    </button>
                    <a href="{{ route('packages.index') }}" class="w-full sm:w-auto text-center py-2.5 text-slate-600 hover:text-slate-800 text-sm font-medium">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
