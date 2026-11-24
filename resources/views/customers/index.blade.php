<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-3">
            <div>
                <div class="flex items-center gap-2">
                    <span class="px-2 py-0.5 rounded bg-raptors-purple text-white text-[10px] font-black uppercase tracking-widest shadow-2xs">
                        PELANGGAN
                    </span>
                    <h2 class="font-black text-xl text-slate-900 leading-tight uppercase tracking-tight">
                        <span>Data Pelanggan Laundry</span>
                    </h2>
                </div>
                <p class="text-xs text-slate-500 mt-1 font-medium">Daftar kontak nama, nomor WhatsApp/telepon, dan alamat pelanggan laundry.</p>
            </div>
            <a href="{{ route('customers.create') }}" class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-gradient-to-r from-raptors-purple to-raptors-red hover:from-[#4E1E80] hover:to-[#B00D35] text-white rounded-xl text-xs sm:text-sm font-black uppercase tracking-wider shadow-md shadow-purple-900/20 transition active:scale-95">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                </svg>
                <span>+ Tambah Pelanggan</span>
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
                            <th class="p-3.5 whitespace-nowrap">No</th>
                            <th class="p-3.5 whitespace-nowrap">Nama Pelanggan</th>
                            <th class="p-3.5 whitespace-nowrap">No. WhatsApp / HP</th>
                            <th class="p-3.5 whitespace-nowrap">Alamat Domisili</th>
                            <th class="p-3.5 text-center whitespace-nowrap">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-purple-50">
                        @forelse($customers as $index => $customer)
                            <tr class="hover:bg-purple-50/30 transition">
                                <td class="p-3.5 whitespace-nowrap text-slate-400 font-bold">
                                    {{ method_exists($customers, 'firstItem') && $customers->firstItem() ? $customers->firstItem() + $index : $index + 1 }}
                                </td>
                                <td class="p-3.5 font-extrabold text-slate-900 whitespace-nowrap">
                                    {{ $customer->name }}
                                </td>
                                <td class="p-3.5 whitespace-nowrap text-slate-700 font-semibold">
                                    <span class="inline-flex items-center gap-1">
                                        <span>📞</span> {{ $customer->phone }}
                                    </span>
                                </td>
                                <td class="p-3.5 text-slate-500 whitespace-nowrap">
                                    {{ $customer->address ?? '-' }}
                                </td>
                                <td class="p-3.5 text-center space-x-2 whitespace-nowrap">
                                    <a href="{{ route('customers.edit', $customer->id) }}" class="inline-flex items-center px-2.5 py-1 bg-purple-100 text-raptors-purple hover:bg-purple-200 rounded-md text-xs font-bold transition">
                                        Edit
                                    </a>
                                    <form action="{{ route('customers.destroy', $customer->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus data pelanggan ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center px-2.5 py-1 bg-red-50 text-raptors-red hover:bg-red-100 rounded-md text-xs font-bold transition">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-8 text-center text-slate-400 font-medium">
                                    Belum ada data pelanggan laundry.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-4">
                    {{ $customers->links() }}
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
