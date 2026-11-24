<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-3">
            <div>
                <div class="flex items-center gap-2">
                    <span class="px-2 py-0.5 rounded bg-raptors-purple text-white text-[10px] font-black uppercase tracking-widest shadow-2xs">
                        LAYANAN
                    </span>
                    <h2 class="font-black text-xl text-slate-900 leading-tight uppercase tracking-tight">
                        <span>Data Paket & Layanan Cuci</span>
                    </h2>
                </div>
                <p class="text-xs text-slate-500 mt-1 font-medium">Daftar paket cuci, tarif per satuan, dan estimasi durasi pengerjaan.</p>
            </div>
            @if(Auth::user()->role === 'admin')
                <a href="{{ route('packages.create') }}" class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-gradient-to-r from-raptors-purple to-raptors-red hover:from-[#4E1E80] hover:to-[#B00D35] text-white rounded-xl text-xs sm:text-sm font-black uppercase tracking-wider shadow-md shadow-purple-900/20 transition active:scale-95">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>+ Tambah Layanan</span>
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-6 sm:py-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-4">

        @if(session('success'))
            <div class="p-3.5 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-900 rounded-r-xl text-xs sm:text-sm font-bold flex items-center gap-2">
                <span>✓</span>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="p-3.5 bg-rose-50 border-l-4 border-raptors-red text-rose-900 rounded-r-xl text-xs sm:text-sm font-bold flex items-center gap-2">
                <span>⚠️</span>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <div class="bg-white overflow-hidden shadow-xs rounded-2xl border border-purple-100 border-t-4 border-t-raptors-purple">
            <div class="p-4 sm:p-6 overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-600">
                    <thead class="bg-purple-50/70 text-raptors-purple uppercase font-black text-xs border-b border-purple-200">
                        <tr>
                            <th class="p-3.5 whitespace-nowrap">No</th>
                            <th class="p-3.5 whitespace-nowrap">Nama Paket Layanan</th>
                            <th class="p-3.5 whitespace-nowrap">Tarif Harga</th>
                            <th class="p-3.5 whitespace-nowrap">Satuan</th>
                            <th class="p-3.5 whitespace-nowrap">Estimasi Durasi</th>
                            <th class="p-3.5 whitespace-nowrap">Deskripsi</th>
                            <th class="p-3.5 text-center whitespace-nowrap">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-purple-50">
                        @forelse($packages as $index => $package)
                            <tr class="hover:bg-purple-50/30 transition">
                                <td class="p-3.5 whitespace-nowrap text-slate-400 font-bold">
                                    {{ method_exists($packages, 'firstItem') && $packages->firstItem() ? $packages->firstItem() + $index : $index + 1 }}
                                </td>
                                <td class="p-3.5 whitespace-nowrap">
                                    <div class="font-extrabold text-slate-900 flex items-center gap-2">
                                        <span class="w-2.5 h-2.5 rounded-full bg-raptors-red"></span>
                                        <span>{{ $package->name }}</span>
                                    </div>
                                </td>
                                <td class="p-3.5 whitespace-nowrap font-black text-slate-900 text-base">
                                    Rp {{ number_format($package->price, 0, ',', '.') }}
                                </td>
                                <td class="p-3.5 whitespace-nowrap text-slate-500 font-semibold">
                                    / {{ $package->unit }}
                                </td>
                                <td class="p-3.5 whitespace-nowrap">
                                    <span class="px-2.5 py-1 text-xs font-bold rounded-lg bg-purple-100 text-raptors-purple border border-purple-200">
                                        ⏱️ {{ $package->duration ?? 'Sesuai antrean' }}
                                    </span>
                                </td>
                                <td class="p-3.5 text-slate-400 text-xs max-w-xs truncate">
                                    {{ $package->description ?? '-' }}
                                </td>
                                <td class="p-3.5 text-center space-x-2 whitespace-nowrap">
                                    @if(Auth::user()->role === 'admin')
                                        <a href="{{ route('packages.edit', $package->id) }}" class="inline-flex items-center px-2.5 py-1 bg-purple-100 text-raptors-purple hover:bg-purple-200 rounded-md text-xs font-bold transition">
                                            Edit
                                        </a>
                                        <form action="{{ route('packages.destroy', $package->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus paket layanan ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center px-2.5 py-1 bg-red-50 text-raptors-red hover:bg-red-100 rounded-md text-xs font-bold transition">
                                                Hapus
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-xs text-slate-400 italic">Hanya Admin</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="p-8 text-center text-slate-400 font-medium">
                                    Belum ada data paket laundry terdaftar.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-4">
                    {{ $packages->links() }}
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
