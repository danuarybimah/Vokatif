<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk — Vokatif</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            background: #09090b;
            color: #f4f4f5;
            font-family: 'Inter', sans-serif;
        }
        h1, h2, h3, h4 {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .grid-bg {
            background-size: 40px 40px;
            background-image: linear-gradient(to right, rgba(220, 38, 38, 0.03) 1px, transparent 1px),
                              linear-gradient(to bottom, rgba(220, 38, 38, 0.03) 1px, transparent 1px);
        }
    </style>
</head>
<body class="min-h-screen flex selection:bg-red-500/30 selection:text-white">

    <!-- LEFT PANEL -->
    <div class="hidden lg:flex flex-col justify-between w-[48%] p-16 relative overflow-hidden border-r border-zinc-900 bg-gradient-to-br from-zinc-950 via-zinc-900 to-zinc-950">

        <!-- Subtle mesh glow -->
        <div class="absolute top-1/3 left-1/4 w-[350px] h-[350px] bg-red-600/5 rounded-full blur-[80px] pointer-events-none"></div>

        <!-- Decorative grids -->
        <div class="absolute inset-0 grid-bg opacity-70 pointer-events-none"></div>

        <div class="relative z-10">
            <a href="/">
                <img src="{{ asset('images/logo-full.png') }}" alt="Vokatif" class="h-8 w-auto object-contain"
                     style="filter: drop-shadow(0 0 6px rgba(220,38,38,0.25));">
            </a>
        </div>

        <div class="relative z-10 space-y-8">
            <div>
                <p class="text-red-400 text-xs font-bold uppercase tracking-[0.25em] mb-4">Event Ticketing Ecosystem</p>
                <h2 class="text-4.5xl font-extrabold leading-[1.12] text-white tracking-tight">
                    Temukan event,<br>beli tiket,<br>hadir dengan QR.
                </h2>
            </div>

            <p class="text-zinc-400 text-sm leading-relaxed max-w-sm font-normal">
                Platform ticketing modern berbasis Laravel dengan e-ticket system dan dashboard panel analitis.
            </p>

            <div class="pt-6 flex items-center gap-8">
                <div>
                    <p class="text-2xl font-extrabold text-white">500+</p>
                    <p class="text-zinc-500 text-[10px] uppercase font-bold tracking-wider mt-1">Events</p>
                </div>
                <div class="w-px h-8 bg-zinc-800"></div>
                <div>
                    <p class="text-2xl font-extrabold text-white">10K+</p>
                    <p class="text-zinc-500 text-[10px] uppercase font-bold tracking-wider mt-1">Tickets Sold</p>
                </div>
                <div class="w-px h-8 bg-zinc-800"></div>
                <div>
                    <p class="text-2xl font-extrabold text-white">99.9%</p>
                    <p class="text-zinc-500 text-[10px] uppercase font-bold tracking-wider mt-1">Uptime SLA</p>
                </div>
            </div>
        </div>

        <p class="relative z-10 text-zinc-650 text-xs font-medium">© {{ date('Y') }} Vokatif. All rights reserved.</p>
    </div>

    <!-- RIGHT PANEL -->
    <div class="flex-1 flex flex-col justify-center px-8 sm:px-16 lg:px-24 py-16 bg-[#09090b]">

        <div class="max-w-sm w-full mx-auto space-y-10">

            <!-- Mobile logo -->
            <div class="lg:hidden">
                <a href="/">
                    <img src="{{ asset('images/logo-full.png') }}" alt="Vokatif" class="h-7 w-auto object-contain"
                         style="filter: drop-shadow(0 0 6px rgba(220,38,38,0.25));">
                </a>
            </div>

            <div>
                <h1 class="text-3xl font-bold text-white tracking-tight">Masuk ke akun</h1>
                <p class="text-zinc-500 text-sm mt-2 font-normal">Selamat datang kembali di Vokatif.</p>
            </div>

            @if($errors->has('email'))
                <div class="px-4 py-3.5 rounded-xl border border-red-500/20 bg-red-950/20 text-red-400 text-xs font-semibold leading-relaxed">
                    {{ $errors->first('email') }}
                </div>
            @endif

            <form action="/login" method="POST" class="space-y-6">
                @csrf

                <div class="space-y-2">
                    <label class="block text-xs font-bold text-zinc-400 uppercase tracking-wider">Email Address</label>
                    <input type="email" name="email" value="{{ old('email') }}"
                           placeholder="nama@email.com"
                           class="w-full bg-zinc-900/40 border border-zinc-800 rounded-xl px-4 py-3.5 text-sm text-white placeholder-zinc-600 outline-none focus:border-red-500/50 focus:bg-zinc-900 transition duration-200"
                           required>
                </div>

                <div class="space-y-2">
                    <div class="flex items-center justify-between">
                        <label class="block text-xs font-bold text-zinc-400 uppercase tracking-wider">Password</label>
                    </div>
                    <div class="relative">
                        <input type="password" name="password" id="password"
                               placeholder="••••••••"
                               class="w-full bg-zinc-900/40 border border-zinc-800 rounded-xl px-4 py-3.5 text-sm text-white placeholder-zinc-600 outline-none focus:border-red-500/50 focus:bg-zinc-900 transition duration-200 pr-12"
                               required>
                        <button type="button" onclick="togglePassword('password', 'eyeIcon')"
                                class="absolute right-3.5 top-1/2 -translate-y-1/2 text-zinc-500 hover:text-zinc-300 transition cursor-pointer">
                            <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <button type="submit"
                        class="w-full py-4 rounded-xl bg-red-600 hover:bg-red-500 text-white font-bold text-sm shadow-lg shadow-red-950/20 hover:shadow-red-800/30 transition-all duration-250 cursor-pointer">
                    Masuk
                </button>

            </form>

            <p class="text-center text-xs font-semibold text-zinc-500 uppercase tracking-wider pt-2">
                Belum punya akun?
                <a href="/register" class="text-red-400 hover:text-red-300 font-bold transition ml-1">Daftar Gratis</a>
            </p>

        </div>
    </div>

</body>
<script>
function togglePassword(inputId, iconId) {
    const input = document.getElementById(inputId);
    const icon  = document.getElementById(iconId);
    if (input.type === 'password') {
        input.type = 'text';
        icon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/>`;
    } else {
        input.type = 'password';
        icon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>`;
    }
}
</script>
</html>
