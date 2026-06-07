<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Dashboard' }} - Vokatif</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            background-color: #09090b;
            color: #f4f4f5;
            font-family: 'Inter', sans-serif;
        }
        .sidebar {
            background: #0c0c0e;
            border-right: 1px solid rgba(63, 63, 70, 0.25);
        }
        .topbar {
            background: rgba(9, 9, 11, 0.7);
            backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(63, 63, 70, 0.2);
        }
        .nav-link-sidebar {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 11px 16px;
            border-radius: 14px;
            font-size: 14px;
            font-weight: 500;
            color: #a1a1aa;
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
            text-decoration: none;
            border: 1px solid transparent;
        }
        .nav-link-sidebar:hover {
            background: rgba(39, 39, 42, 0.5);
            color: white;
            border-color: rgba(63, 63, 70, 0.2);
            transform: translateX(2px);
        }
        .nav-link-sidebar.active {
            background: rgba(220, 38, 38, 0.12);
            color: #fca5a5;
            border: 1px solid rgba(220, 38, 38, 0.25);
            box-shadow: 0 4px 12px rgba(220, 38, 38, 0.05);
            font-weight: 600;
        }
    </style>
    @stack('head')
</head>
<body class="min-h-screen">

<div class="flex min-h-screen">

    <!-- SIDEBAR -->
    <aside class="sidebar w-64 min-h-screen flex flex-col py-6 px-4 flex-shrink-0">

        <div class="px-3 mb-10">
            <a href="{{ auth()->user()?->role?->slug === 'admin' ? '/admin/dashboard' : '/organizer/dashboard' }}">
                <img src="{{ asset('images/logo-full.png') }}" alt="Vokatif" class="h-8 w-auto object-contain"
                     style="filter: drop-shadow(0 0 6px rgba(220,38,38,0.25));">
            </a>
        </div>

        <nav class="flex flex-col gap-1.5 flex-1">
            @php $role = auth()->user()?->role?->slug; @endphp

            @if($role === 'admin')
                @php $unread = \App\Models\ContactMessage::where('is_read', false)->count(); @endphp

                <a href="/admin/dashboard"
                   class="nav-link-sidebar {{ request()->is('admin/dashboard') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                    </svg>
                    Dashboard
                </a>
                <a href="/admin/users"
                   class="nav-link-sidebar {{ request()->is('admin/users*') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    Users
                </a>
                <a href="/admin/events"
                   class="nav-link-sidebar {{ request()->is('admin/events*') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    Events
                </a>
                <a href="/admin/analytics"
                   class="nav-link-sidebar {{ request()->is('admin/analytics') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    Analytics
                </a>
                <a href="/admin/checkin-scanner"
                   class="nav-link-sidebar {{ request()->is('admin/checkin-scanner') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                    </svg>
                    QR Scanner
                </a>
                <a href="/admin/messages"
                   class="nav-link-sidebar {{ request()->is('admin/messages') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                    </svg>
                    Pesan
                    @if($unread > 0)
                        <span class="ml-auto text-xs bg-red-600 text-white px-2 py-0.5 rounded-full font-bold shadow-md shadow-red-900/40">{{ $unread }}</span>
                    @endif
                </a>

            @elseif($role === 'organizer')

                <a href="/organizer/dashboard"
                   class="nav-link-sidebar {{ request()->is('organizer/dashboard') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                    </svg>
                    Dashboard
                </a>
                <a href="/organizer/events"
                   class="nav-link-sidebar {{ request()->is('organizer/events*') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    Events
                </a>
                <a href="/organizer/checkin-participants"
                   class="nav-link-sidebar {{ request()->is('organizer/checkin-participants') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                    Peserta
                </a>
                <a href="/organizer/analytics"
                   class="nav-link-sidebar {{ request()->is('organizer/analytics') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    Analytics
                </a>
                <a href="/organizer/checkin-scanner"
                   class="nav-link-sidebar {{ request()->is('organizer/checkin-scanner') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                    </svg>
                    QR Scanner
                </a>

            @endif

        </nav>

        <!-- SIDEBAR BOTTOM -->
        <div class="mt-6 pt-5 border-t border-zinc-800/40 px-1">
            <a href="/profile" class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-zinc-900/50 border border-transparent hover:border-zinc-800/40 transition duration-200">
                <div class="w-7 h-7 rounded-lg bg-gradient-to-br from-red-600 to-rose-600 flex items-center justify-center text-xs font-bold text-white shadow-sm shadow-red-950/20 flex-shrink-0">
                    {{ strtoupper(substr(auth()->user()?->name ?? 'U', 0, 1)) }}
                </div>
                <div class="min-w-0">
                    <p class="text-zinc-100 text-xs font-semibold truncate leading-none">{{ auth()->user()?->name }}</p>
                    <p class="text-zinc-500 text-[10px] uppercase font-bold tracking-wide mt-1 truncate">{{ ucfirst(auth()->user()?->role?->slug) }}</p>
                </div>
            </a>
        </div>

    </aside>

    <main class="flex-1 min-w-0 flex flex-col">

        <!-- TOPBAR -->
        <div class="topbar px-8 py-4.5 flex items-center justify-between flex-shrink-0 sticky top-0 z-40">
            <div>
                <h2 class="text-sm font-semibold text-zinc-300">
                    Selamat datang, {{ auth()->user()?->name }}
                </h2>
                <p class="text-[10px] text-zinc-500 font-bold uppercase tracking-wider mt-0.5">
                    {{ ucfirst(auth()->user()?->role?->slug) }} Account
                </p>
            </div>

            <div class="flex items-center gap-3" x-data="{ open: false }">

                <button @click="open = !open"
                        class="flex items-center gap-3 px-3 py-1.5 rounded-xl hover:bg-zinc-900/60 border border-transparent hover:border-zinc-800/40 transition duration-200 outline-none">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-red-600 to-rose-600 flex items-center justify-center text-sm font-bold text-white shadow-md shadow-red-950/20 flex-shrink-0">
                        {{ strtoupper(substr(auth()->user()?->name ?? 'U', 0, 1)) }}
                    </div>
                    <div class="hidden sm:block text-left">
                        <p class="text-xs font-bold text-white leading-none">{{ auth()->user()?->name }}</p>
                        <p class="text-[9px] font-extrabold uppercase text-zinc-500 tracking-wide mt-1.5 leading-none">{{ auth()->user()?->role?->slug }}</p>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-zinc-500 transition-transform" :class="open ? 'rotate-180 text-zinc-300' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>

                <div x-show="open" @click.outside="open = false" x-transition
                     class="absolute right-8 top-16 w-52 rounded-2xl shadow-2xl z-50 py-1.5 bg-zinc-900 border border-zinc-800/80 backdrop-blur-xl">
                    <a href="/profile" class="flex items-center gap-3 px-4 py-2 text-sm text-zinc-400 hover:bg-zinc-850 hover:text-white transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-zinc-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        Profil Saya
                    </a>
                    <div class="border-t border-zinc-800/50 my-1.5"></div>
                    <form action="/logout" method="POST">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-3 px-4 py-2 text-sm text-red-400 hover:bg-red-950/10 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            Keluar
                        </button>
                    </form>
                </div>

            </div>
        </div>

        <!-- CONTENT -->
        <div class="flex-1 p-8 overflow-auto">
            @yield('content')
        </div>

        <!-- MINI FOOTER -->
        <div class="px-8 py-5 border-t border-zinc-900 flex items-center justify-between flex-shrink-0">
            <p class="text-zinc-650 text-xs font-medium">© {{ date('Y') }} Vokatif Platform</p>
            <div class="flex items-center gap-2">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-450 operational-light"></span>
                <span class="text-zinc-550 text-[10px] font-bold tracking-wider uppercase">Operational</span>
            </div>
        </div>

    </main>

</div>

<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
@stack('scripts')
</body>
</html>
