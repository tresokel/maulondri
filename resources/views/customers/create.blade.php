<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-black text-xl text-slate-900 leading-tight uppercase tracking-tight">
                {{ __('Tambah Pelanggan Baru') }}
            </h2>
            <a href="{{ route('customers.index') }}" class="text-xs font-bold text-slate-500 hover:text-raptors-purple">
                &larr; Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-6 sm:py-10 max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white p-5 sm:p-8 rounded-2xl shadow-xs border border-purple-100 border-t-4 border-t-raptors-purple">
            <form action="{{ route('customers.store') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-xs font-black uppercase tracking-wider text-slate-700 mb-1.5">Nama Lengkap Pelanggan</label>
                    <input type="text" name="name" value="{{ old('name') }}" required class="w-full bg-slate-50 border border-purple-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-raptors-purple focus:border-raptors-purple p-2.5 font-medium">
                    @error('name') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-black uppercase tracking-wider text-slate-700 mb-1.5">No. HP / WhatsApp</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" required placeholder="Contoh: 08123456789" class="w-full bg-slate-50 border border-purple-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-raptors-purple focus:border-raptors-purple p-2.5 font-medium">
                    @error('phone') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-black uppercase tracking-wider text-slate-700 mb-1.5">Alamat (Opsional)</label>
                    <textarea name="address" rows="3" class="w-full bg-slate-50 border border-purple-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-raptors-purple focus:border-raptors-purple p-2.5 font-medium">{{ old('address') }}</textarea>
                    @error('address') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="flex flex-col sm:flex-row items-center gap-3 pt-3">
                    <button type="submit" class="w-full sm:w-auto bg-gradient-to-r from-raptors-purple to-raptors-red hover:from-[#4E1E80] hover:to-[#B00D35] text-white font-black uppercase tracking-wider px-6 py-2.5 rounded-xl text-sm shadow-md shadow-purple-900/25 transition active:scale-95">
                        Simpan Pelanggan
                    </button>
                    <a href="{{ route('customers.index') }}" class="w-full sm:w-auto text-center py-2.5 text-slate-600 hover:text-slate-800 text-sm font-medium">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
