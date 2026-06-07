<!-- PROFESSIONAL HEADER -->
<nav class="sticky top-0 z-50 backdrop-blur-xl border-b border-zinc-800/50 bg-zinc-950/75">
    <div class="max-w-7xl mx-auto px-6 py-4">
        <div class="flex items-center justify-between">
            <!-- LOGO & BRAND -->
            <div class="flex items-center">
                <a href="{{ auth()->check() ? '/home' : '/' }}" class="flex items-center group">
                    {{-- Full logo (with text) on md+ screens --}}
                    <img src="{{ asset('images/logo-full.png') }}" alt="Vokatif"
                        class="hidden md:block h-9 w-auto object-contain transition duration-300 group-hover:brightness-110"
                        style="filter: drop-shadow(0 0 8px rgba(220,38,38,0.25));">
                    {{-- Icon only on small screens --}}
                    <img src="{{ asset('images/logo-icon.png') }}" alt="Vokatif"
                        class="block md:hidden h-9 w-auto object-contain transition duration-300 group-hover:brightness-110"
                        style="filter: drop-shadow(0 0 8px rgba(220,38,38,0.25));">
                </a>
            </div>

            <!-- NAV MENU -->
            <div class="hidden lg:flex items-center gap-9">
                @auth
                    <a href="/home"
                        class="nav-underline-anim text-sm font-medium text-zinc-400 hover:text-white transition-colors duration-200">Dashboard</a>
                @else
                    <a href="/"
                        class="nav-underline-anim text-sm font-medium text-zinc-400 hover:text-white transition-colors duration-200">Home</a>
                @endauth
                <a href="/events"
                    class="nav-underline-anim text-sm font-medium text-zinc-400 hover:text-white transition-colors duration-200">Events</a>
                <a href="/#about"
                    class="nav-underline-anim text-sm font-medium text-zinc-400 hover:text-white transition-colors duration-200">About</a>
                <a href="/#contact"
                    class="nav-underline-anim text-sm font-medium text-zinc-400 hover:text-white transition-colors duration-200">Contact</a>
            </div>

            <!-- RIGHT SECTION -->
            <div class="flex items-center gap-5">
                @auth
                    <!-- PROFILE DROPDOWN -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open"
                            class="flex items-center gap-3 px-3 py-1.5 rounded-xl bg-zinc-900/40 hover:bg-zinc-800/60 border border-zinc-800/40 transition-all duration-200 outline-none">
                            <div
                                class="w-8 h-8 rounded-lg bg-gradient-to-br from-red-600 to-rose-600 flex items-center justify-center font-bold text-sm text-white shadow-md shadow-red-950/20">
                                {{ strtoupper(substr(auth()->user()?->name ?? 'U', 0, 1)) }}
                            </div>
                            <div class="hidden sm:block text-left">
                                <p class="font-semibold text-xs leading-none text-zinc-200">{{ auth()->user()?->name ?? '-' }}</p>
                                <p class="text-[10px] text-zinc-500 font-medium tracking-wide uppercase mt-0.5">{{ auth()->user()?->role?->name ?? 'User' }}</p>
                            </div>
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="w-3.5 h-3.5 text-zinc-500 transition-transform duration-300"
                                :class="open ? 'rotate-180 text-zinc-300' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <!-- DROPDOWN MENU -->
                        <div x-show="open" @click.outside="open = false"
                            x-transition:enter="transition ease-out duration-150"
                            x-transition:enter-start="opacity-0 scale-95"
                            x-transition:enter-end="opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-100"
                            x-transition:leave-start="opacity-100 scale-100"
                            x-transition:leave-end="opacity-0 scale-95"
                            class="absolute right-0 mt-3 w-60 rounded-2xl bg-zinc-900/95 backdrop-blur-xl border border-zinc-800/70 shadow-2xl overflow-hidden z-50">

                            <div class="px-5 py-4 border-b border-zinc-800/75 bg-gradient-to-br from-red-950/20 to-zinc-900/20">
                                <p class="font-semibold text-sm text-white">{{ auth()->user()?->name }}</p>
                                <p class="text-zinc-500 text-xs truncate mt-0.5">{{ auth()->user()?->email }}</p>
                            </div>

                            <div class="p-2 space-y-0.5">
                                <a href="/home"
                                    class="flex items-center gap-3 px-4 py-2.5 rounded-xl hover:bg-zinc-800/60 transition-colors text-sm text-zinc-400 hover:text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-zinc-500" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                    </svg>
                                    Dashboard
                                </a>
                                <a href="/profile"
                                    class="flex items-center gap-3 px-4 py-2.5 rounded-xl hover:bg-zinc-800/60 transition-colors text-sm text-zinc-400 hover:text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-zinc-500" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    My Profile
                                </a>
                                @if (auth()->user()?->role?->slug === 'user' || !auth()->user()?->role)
                                    <a href="/my-tickets"
                                        class="flex items-center gap-3 px-4 py-2.5 rounded-xl hover:bg-zinc-800/60 transition-colors text-sm text-zinc-400 hover:text-white">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-zinc-500" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                                        </svg>
                                        My Tickets
                                    </a>
                                    <a href="/transactions"
                                        class="flex items-center gap-3 px-4 py-2.5 rounded-xl hover:bg-zinc-800/60 transition-colors text-sm text-zinc-400 hover:text-white">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-zinc-500" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                        </svg>
                                        History
                                    </a>
                                @elseif(auth()->user()?->role?->slug === 'organizer')
                                    <a href="/organizer/dashboard"
                                        class="flex items-center gap-3 px-4 py-2.5 rounded-xl hover:bg-zinc-800/60 transition-colors text-sm text-zinc-400 hover:text-white">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-zinc-500" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                                        </svg>
                                        Organizer Panel
                                    </a>
                                @elseif(auth()->user()?->role?->slug === 'admin')
                                    <a href="/admin/dashboard"
                                        class="flex items-center gap-3 px-4 py-2.5 rounded-xl hover:bg-zinc-800/60 transition-colors text-sm text-zinc-400 hover:text-white">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-zinc-500" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                        </svg>
                                        Admin Panel
                                    </a>
                                @endif

                                <div class="border-t border-zinc-800/60 my-1"></div>

                                <form action="/logout" method="POST">
                                    @csrf
                                    <button type="submit"
                                        class="w-full flex items-center gap-3 px-4 py-2.5 rounded-xl text-left text-sm text-rose-400 hover:bg-rose-950/20 transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                        </svg>
                                        Sign Out
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    <a href="/login"
                        class="px-4 py-2 text-sm font-semibold text-zinc-400 hover:text-white transition-all duration-200">
                        Sign In
                    </a>
                    <a href="/register"
                        class="px-5 py-2.5 rounded-xl bg-red-600 hover:bg-red-500 text-white text-sm font-bold shadow-lg shadow-red-950/20 hover:shadow-red-800/35 transition-all duration-250">
                        Get Started
                    </a>
                @endauth
            </div>
        </div>
    </div>
</nav>
