<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Sistem Laundry') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased text-slate-800 bg-[#F5F2F9] selection:bg-[#CE1141] selection:text-white">
        <!-- Top Stripe Accent -->
        <div class="h-1.5 w-full raptors-stripe"></div>

        <div class="min-h-screen flex flex-col">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white/80 backdrop-blur-md shadow-2xs border-b border-purple-100">
                    <div class="max-w-7xl mx-auto py-5 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main class="flex-1">
                {{ $slot }}
            </main>

            <!-- Footer -->
            <footer class="py-4 text-center text-xs text-slate-400 border-t border-purple-100 bg-white/60">
                <div class="max-w-7xl mx-auto px-4 flex flex-col sm:flex-row justify-between items-center gap-2">
                    <span class="font-bold text-slate-600 flex items-center gap-1.5">
                        <span class="w-2.5 h-2.5 rounded-full bg-raptors-purple inline-block"></span>
                        <span class="w-2.5 h-2.5 rounded-full bg-raptors-red inline-block"></span>
                        Sistem Informasi Manajemen Laundry
                    </span>
                    <span class="text-slate-400">Cepat &bull; Bersih &bull; Terpercaya</span>
                </div>
            </footer>
        </div>
    </body>
</html>
