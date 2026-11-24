<nav x-data="{ open: false }" class="bg-white border-b border-purple-100/80 shadow-xs sticky top-0 z-40 backdrop-blur-md bg-white/95">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Brand Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 group">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-raptors-purple via-[#6B28B0] to-raptors-red text-white flex items-center justify-center shadow-md shadow-purple-900/25 group-hover:scale-105 transition border-2 border-white">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v16a2 2 0 002 2h12a2 2 0 002-2V4a2 2 0 00-2-2H6a2 2 0 00-2 2zm8 5a4 4 0 100 8 4 4 0 000-8zm-4-3h1m2 0h1" />
                            </svg>
                        </div>
                        <div>
                            <span class="tracking-tight font-black text-slate-900 text-lg flex items-center gap-1.5 uppercase">
                                <span class="text-raptors-purple">Sistem</span><span class="text-raptors-red">Laundry</span>
                                <span class="text-[9px] font-black uppercase tracking-widest px-1.5 py-0.5 rounded bg-raptors-purple text-white shadow-2xs">
                                    PRO
                                </span>
                            </span>
                            <span class="text-[10px] text-slate-400 font-bold block -mt-1 tracking-wider uppercase">Manajemen Laundry</span>
                        </div>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-7 sm:-my-px sm:ms-8 sm:flex">
                    <a href="{{ route('dashboard') }}" 
                       class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-bold transition duration-150 {{ request()->routeIs('dashboard') ? 'border-raptors-red text-raptors-purple' : 'border-transparent text-slate-600 hover:text-raptors-purple hover:border-purple-300' }}">
                        {{ __('Dashboard') }}
                    </a>

                    <a href="{{ route('transactions.index') }}" 
                       class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-bold transition duration-150 {{ request()->routeIs('transactions.*') ? 'border-raptors-red text-raptors-purple' : 'border-transparent text-slate-600 hover:text-raptors-purple hover:border-purple-300' }}">
                        {{ __('Transaksi') }}
                    </a>

                    <a href="{{ route('customers.index') }}" 
                       class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-bold transition duration-150 {{ request()->routeIs('customers.*') ? 'border-raptors-red text-raptors-purple' : 'border-transparent text-slate-600 hover:text-raptors-purple hover:border-purple-300' }}">
                        {{ __('Pelanggan') }}
                    </a>

                    <a href="{{ route('packages.index') }}" 
                       class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-bold transition duration-150 {{ request()->routeIs('packages.*') ? 'border-raptors-red text-raptors-purple' : 'border-transparent text-slate-600 hover:text-raptors-purple hover:border-purple-300' }}">
                        {{ __('Paket Laundry') }}
                    </a>

                    <a href="{{ route('reports.index') }}" 
                       class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-bold transition duration-150 {{ request()->routeIs('reports.*') ? 'border-raptors-red text-raptors-purple' : 'border-transparent text-slate-600 hover:text-raptors-purple hover:border-purple-300' }}">
                        {{ __('Laporan') }}
                    </a>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center gap-2 px-3 py-1.5 border border-purple-200 text-sm leading-4 font-bold rounded-xl text-slate-700 bg-purple-50/50 hover:bg-purple-100/50 focus:outline-none transition">
                            <div class="w-6 h-6 rounded-lg bg-raptors-purple text-white flex items-center justify-center font-black text-xs uppercase shadow-2xs">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                            <div class="font-bold text-slate-800">{{ Auth::user()->name }}</div>
                            <span class="text-[10px] uppercase font-black tracking-wider px-2 py-0.5 rounded-full {{ Auth::user()->role === 'admin' ? 'bg-raptors-purple text-white' : 'bg-raptors-red text-white' }}">
                                {{ ucfirst(Auth::user()->role) }}
                            </span>

                            <div class="ms-0.5">
                                <svg class="fill-current h-3.5 w-3.5 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile Saya') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();"
                                    class="text-raptors-red hover:bg-red-50 font-bold">
                                {{ __('Keluar (Log Out)') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger (Mobile) -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-lg text-slate-600 hover:text-raptors-purple hover:bg-purple-50 focus:outline-none transition">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu (Mobile) -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden border-t border-purple-100 bg-[#FBF9FE]">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="font-bold">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('transactions.index')" :active="request()->routeIs('transactions.*')" class="font-bold">
                {{ __('Transaksi') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('customers.index')" :active="request()->routeIs('customers.*')" class="font-bold">
                {{ __('Pelanggan') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('packages.index')" :active="request()->routeIs('packages.*')" class="font-bold">
                {{ __('Paket Laundry') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('reports.index')" :active="request()->routeIs('reports.*')" class="font-bold">
                {{ __('Laporan') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-3 border-t border-purple-100 px-4">
            <div class="flex items-center gap-2.5 mb-2">
                <div class="w-8 h-8 rounded-lg bg-raptors-purple text-white flex items-center justify-center font-bold text-xs uppercase shadow-2xs">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
                <div>
                    <div class="font-extrabold text-sm text-slate-800">{{ Auth::user()->name }}</div>
                    <div class="text-xs text-slate-500">{{ Auth::user()->email }} ({{ ucfirst(Auth::user()->role) }})</div>
                </div>
            </div>

            <div class="mt-2 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile Saya') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();"
                            class="text-raptors-red font-bold">
                        {{ __('Keluar (Log Out)') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
