<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Laundry</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gradient-to-br from-[#1F0A38] via-[#2B104E] to-[#130524] min-h-screen flex items-center justify-center p-4 sm:p-6 text-slate-800 relative overflow-hidden">

    <!-- Decorative 90s Retro Glows -->
    <div class="absolute -top-32 -left-32 w-80 h-80 rounded-full bg-raptors-purple/30 blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-32 -right-32 w-80 h-80 rounded-full bg-raptors-red/25 blur-3xl pointer-events-none"></div>

    <div class="w-full max-w-md bg-white rounded-3xl shadow-2xl shadow-purple-950/60 p-6 sm:p-9 relative z-10 border-t-4 border-raptors-red border-b border-purple-200">
        
        <!-- Header Brand -->
        <div class="text-center mb-6">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-tr from-raptors-purple via-[#6C25B0] to-raptors-red text-white shadow-lg shadow-purple-900/40 mb-3 border-2 border-white">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v16a2 2 0 002 2h12a2 2 0 002-2V4a2 2 0 00-2-2H6a2 2 0 00-2 2zm8 5a4 4 0 100 8 4 4 0 000-8zm-4-3h1m2 0h1" />
                </svg>
            </div>
            <div class="inline-block px-2.5 py-0.5 rounded-full bg-raptors-purple text-white text-[10px] font-black uppercase tracking-widest mb-1 shadow-2xs">
                SISTEM MANAJEMEN LAUNDRY
            </div>
            <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-slate-900 uppercase">
                <span class="text-raptors-purple">Sistem</span> <span class="text-raptors-red">Laundry</span>
            </h1>
            <p class="text-xs text-slate-500 font-medium mt-0.5">Sistem Manajemen & Kasir Operasional Laundry</p>
        </div>

        {{-- Notifikasi Error --}}
        @if ($errors->any())
            <div class="mb-5 p-3.5 bg-rose-50 border-l-4 border-raptors-red text-rose-800 text-xs sm:text-sm rounded-r-xl flex items-center gap-2">
                <svg class="w-5 h-5 shrink-0 text-raptors-red" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST" class="space-y-4">
            @csrf
            
            <!-- Email Input -->
            <div>
                <label for="email" class="block text-xs font-black uppercase tracking-wider text-slate-700 mb-1.5">Email Pengguna</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                        </svg>
                    </div>
                    <input 
                        type="email" 
                        name="email" 
                        id="email" 
                        value="{{ old('email') }}" 
                        required 
                        placeholder="admin@gmail.com atau kasir@gmail.com"
                        class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-purple-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-raptors-purple focus:border-raptors-purple focus:outline-none text-sm font-medium transition"
                    >
                </div>
            </div>

            <!-- Password Input -->
            <div>
                <div class="flex justify-between items-center mb-1.5">
                    <label for="password" class="block text-xs font-black uppercase tracking-wider text-slate-700">Password</label>
                </div>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <input 
                        type="password" 
                        name="password" 
                        id="password" 
                        required 
                        placeholder="••••••••"
                        class="w-full pl-10 pr-11 py-2.5 bg-slate-50 border border-purple-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-raptors-purple focus:border-raptors-purple focus:outline-none text-sm font-medium transition"
                    >
                    <!-- Tombol Lihat Password -->
                    <button 
                        type="button" 
                        onclick="togglePasswordVisibility()" 
                        class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-raptors-purple focus:outline-none"
                        title="Tampilkan / Sembunyikan Password"
                    >
                        <svg id="eye-icon" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Tombol Masuk -->
            <button 
                type="submit" 
                class="w-full bg-gradient-to-r from-raptors-purple to-raptors-red hover:from-[#4E1E80] hover:to-[#B00D35] text-white font-black uppercase tracking-wider py-3 rounded-xl text-sm transition shadow-lg shadow-purple-900/30 active:scale-[0.99] flex items-center justify-center gap-2 mt-2"
            >
                <span>Masuk ke Sistem</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                </svg>
            </button>
        </form>

        <!-- Kotak Demo Akun (One-Click Fill) -->
        <div class="mt-6 pt-5 border-t border-purple-100">
            <p class="text-xs text-slate-400 text-center font-bold uppercase tracking-wider mb-3">Klik tombol untuk autofill akun demo:</p>
            <div class="grid grid-cols-2 gap-2.5">
                <button 
                    type="button" 
                    onclick="fillAccount('admin@gmail.com', '123')" 
                    class="py-2.5 px-3 bg-purple-50 hover:bg-purple-100/80 border-2 border-purple-300 text-raptors-purple rounded-xl text-xs font-black transition flex flex-col items-center justify-center gap-0.5 active:scale-95 shadow-2xs"
                >
                    <span class="flex items-center gap-1 font-black uppercase tracking-wider">
                        <span>⚡</span> Akun Admin
                    </span>
                    <span class="text-[10px] text-purple-600 font-semibold">admin@gmail.com</span>
                </button>

                <button 
                    type="button" 
                    onclick="fillAccount('kasir@gmail.com', '123')" 
                    class="py-2.5 px-3 bg-red-50 hover:bg-red-100/80 border-2 border-red-300 text-raptors-red rounded-xl text-xs font-black transition flex flex-col items-center justify-center gap-0.5 active:scale-95 shadow-2xs"
                >
                    <span class="flex items-center gap-1 font-black uppercase tracking-wider">
                        <span>🧺</span> Akun Kasir
                    </span>
                    <span class="text-[10px] text-red-600 font-semibold">kasir@gmail.com</span>
                </button>
            </div>
            <p class="text-[11px] text-slate-400 text-center mt-2.5 font-medium">Password default: <strong class="text-slate-700">123</strong></p>
        </div>
    </div>

    <!-- Script Pengisian Otomatis & Toggle Password -->
    <script>
        function fillAccount(email, password) {
            const emailInput = document.getElementById('email');
            const passInput = document.getElementById('password');
            emailInput.value = email;
            passInput.value = password;
            
            // Efek visual highlight retro
            emailInput.classList.add('ring-2', 'ring-raptors-purple');
            passInput.classList.add('ring-2', 'ring-raptors-purple');
            setTimeout(() => {
                emailInput.classList.remove('ring-2', 'ring-raptors-purple');
                passInput.classList.remove('ring-2', 'ring-raptors-purple');
            }, 600);
        }

        function togglePasswordVisibility() {
            const passInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eye-icon');
            if (passInput.type === 'password') {
                passInput.type = 'text';
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                `;
            } else {
                passInput.type = 'password';
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                `;
            }
        }
    </script>

</body>
</html>
