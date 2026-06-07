@extends('layouts.user')

@section('content')
    <div class="space-y-12">

        {{-- WELCOMING HERO (ASYMMETRICAL FINTECH ROADMAP STYLE) --}}
        <div data-hero-section class="relative rounded-[32px] p-8 md:p-10 flex flex-col md:flex-row items-center justify-between gap-8 overflow-hidden shadow-2xl border border-white/5"
            style="background: linear-gradient(135deg, rgba(220, 38, 38, 0.15), rgba(244, 63, 94, 0.05));">

            <!-- Glow background overlay -->
            <div class="absolute -right-20 -bottom-20 w-80 h-80 rounded-full bg-red-600/10 blur-[80px]"></div>

            <div class="space-y-4 relative z-10">
                <p class="text-xs font-bold uppercase tracking-[0.25em] text-red-400">Hai, selamat datang kembali</p>
                <h1 class="text-4xl sm:text-5xl font-extrabold text-white tracking-tight leading-none">{{ $user->name }}</h1>
                <p class="text-zinc-400 text-base max-w-md font-normal leading-relaxed">
                    Kamu memegang <span class="text-red-400 font-bold">{{ $totalTickets }} tiket</span> aktif
                    dan telah menginvestasikan total belanja senilai
                    <span class="text-red-300 font-bold">Rp{{ number_format($totalSpent, 0, ',', '.') }}</span>.
                </p>
            </div>

            <div class="flex gap-4 flex-wrap relative z-10">
                <a href="/events"
                    class="btn-premium-primary px-6 py-3.5 font-bold text-sm flex items-center gap-2.5 shadow-md">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    Explore Events
                </a>
                <a href="/my-tickets"
                    class="btn-premium-secondary px-6 py-3.5 font-bold text-sm flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                    </svg>
                    My Tickets
                </a>
            </div>

        </div>

        {{-- STATS GRID --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-5">
            <a href="/my-tickets" class="premium-glass rounded-2xl p-6 hover:border-red-500/25 hover:bg-zinc-900/60 transition-all duration-300 shadow-lg group block">
                <p class="text-zinc-500 text-xs font-bold uppercase tracking-wider">Total Tiket</p>
                <div class="flex items-end justify-between mt-3">
                    <h2 class="text-4xl font-extrabold text-white group-hover:text-red-400 transition-colors">{{ $totalTickets }}</h2>
                    <span class="text-zinc-650 text-xs font-semibold group-hover:text-red-400 transition">Detail →</span>
                </div>
            </a>
            <a href="/transactions" class="premium-glass rounded-2xl p-6 hover:border-zinc-700/60 hover:bg-zinc-900/60 transition-all duration-300 shadow-lg group block">
                <p class="text-zinc-500 text-xs font-bold uppercase tracking-wider">Transaksi</p>
                <div class="flex items-end justify-between mt-3">
                    <h2 class="text-4xl font-extrabold text-white">{{ $totalOrders }}</h2>
                    <span class="text-zinc-650 text-xs font-semibold">Riwayat →</span>
                </div>
            </a>
            <a href="/upcoming" class="premium-glass rounded-2xl p-6 hover:border-red-500/25 hover:bg-zinc-900/60 transition-all duration-300 shadow-lg group block">
                <p class="text-zinc-500 text-xs font-bold uppercase tracking-wider">Upcoming</p>
                <div class="flex items-end justify-between mt-3">
                    <h2 class="text-4xl font-extrabold text-white group-hover:text-red-400 transition-colors">{{ $upcomingTickets->count() }}</h2>
                    <span class="text-zinc-650 text-xs font-semibold group-hover:text-red-400 transition">Jadwal →</span>
                </div>
            </a>
            <a href="/transactions" class="premium-glass rounded-2xl p-6 hover:border-zinc-700/60 hover:bg-zinc-900/60 transition-all duration-300 shadow-lg group block">
                <p class="text-zinc-500 text-xs font-bold uppercase tracking-wider">Belanja</p>
                <div class="flex items-end justify-between mt-3">
                    <h2 class="text-xl font-extrabold text-red-400">Rp{{ number_format($totalSpent, 0, ',', '.') }}</h2>
                    <span class="text-zinc-650 text-[10px] uppercase font-bold tracking-wider">Total</span>
                </div>
            </a>
        </div>

        {{-- UPCOMING EVENTS SECTIONS --}}
        @if ($upcomingTickets->count() > 0)
            <div class="space-y-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-2xl font-bold text-white tracking-tight">Upcoming Events</h2>
                        <p class="text-zinc-500 text-xs mt-1">Event yang terdaftar dan akan segera kamu hadiri.</p>
                    </div>
                    <a href="/upcoming" class="text-xs font-bold text-red-400 hover:text-red-300 transition uppercase tracking-wider">
                        Lihat semua →
                    </a>
                </div>

                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($upcomingTickets as $ticket)
                        @php $daysLeft = now()->diffInDays($ticket->event->start_at, false); @endphp
                        <div class="premium-glass rounded-3xl overflow-hidden hover:border-red-500/20 hover:bg-zinc-900/60 transition duration-300 flex flex-col justify-between shadow-lg">

                            <div>
                                <div class="relative h-44 overflow-hidden">
                                    @if ($ticket->event->cover_image)
                                        <img src="{{ asset('storage/' . $ticket->event->cover_image) }}"
                                            alt="{{ $ticket->event->title }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="h-full bg-gradient-to-br from-red-900/40 to-zinc-900"></div>
                                    @endif
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                                    <span class="absolute bottom-4 left-4 text-[10px] font-bold bg-black/40 px-3 py-1 rounded-full backdrop-blur-md text-white uppercase tracking-wider">
                                        {{ $ticket->event->category->name ?? 'Event' }}
                                    </span>
                                </div>

                                <div class="p-6 space-y-3">
                                    <div class="flex items-center gap-2">
                                        @if ($daysLeft === 0)
                                            <span class="px-2.5 py-0.5 rounded-full bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-[10px] font-bold tracking-wider animate-pulse">HARI INI</span>
                                        @elseif($daysLeft > 0)
                                            <span class="px-2.5 py-0.5 rounded-full bg-red-950/20 border border-red-500/20 text-red-400 text-[10px] font-bold tracking-wider">{{ $daysLeft }} hari lagi</span>
                                        @endif
                                    </div>
                                    <h3 class="text-lg font-bold text-white leading-snug line-clamp-1">{{ $ticket->event->title }}</h3>
                                    <p class="text-zinc-500 text-xs">
                                        {{ $ticket->event->start_at->format('d M Y · H:i') }} WIB
                                    </p>
                                </div>
                            </div>

                            <div class="px-6 pb-6 pt-2 border-t border-zinc-800/30 flex items-center justify-between">
                                <span class="text-red-400 font-bold text-xs uppercase tracking-wider">{{ $ticket->ticketType->name }}</span>
                                <a href="{{ route('tickets.show', $ticket->ticket_code) }}"
                                    class="px-4 py-2 rounded-xl bg-red-950/30 hover:bg-red-900/40 border border-red-500/20 text-red-400 text-xs font-bold transition shadow-sm">
                                    Lihat QR
                                </a>
                            </div>

                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- QUICK MENU --}}
        <div class="space-y-6">
            <h2 class="text-2xl font-bold text-white tracking-tight">Menu Cepat</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-5">

                <a href="/upcoming"
                    class="premium-glass rounded-2xl p-6 flex flex-col items-center gap-4 hover:border-red-500/20 hover:bg-zinc-900/60 transition-all duration-300 text-center shadow-lg group">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-red-650/10 to-red-600/5 border border-red-500/10 flex items-center justify-center group-hover:border-red-500/30 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-red-400" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <p class="font-semibold text-sm text-zinc-300 group-hover:text-white transition">Upcoming</p>
                </a>

                <a href="/my-tickets"
                    class="premium-glass rounded-2xl p-6 flex flex-col items-center gap-4 hover:border-red-500/20 hover:bg-zinc-900/60 transition-all duration-300 text-center shadow-lg group">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-red-650/10 to-red-600/5 border border-red-500/10 flex items-center justify-center group-hover:border-red-500/30 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-red-400" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                        </svg>
                    </div>
                    <p class="font-semibold text-sm text-zinc-300 group-hover:text-white transition">My Tickets</p>
                </a>

                <a href="/transactions"
                    class="premium-glass rounded-2xl p-6 flex flex-col items-center gap-4 hover:border-rose-500/20 hover:bg-zinc-900/60 transition-all duration-300 text-center shadow-lg group">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-rose-650/10 to-rose-600/5 border border-rose-500/10 flex items-center justify-center group-hover:border-rose-500/30 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-rose-400" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <p class="font-semibold text-sm text-zinc-300 group-hover:text-white transition">Transaksi</p>
                </a>

                <a href="/qr-ticket"
                    class="premium-glass rounded-2xl p-6 flex flex-col items-center gap-4 hover:border-red-500/20 hover:bg-zinc-900/60 transition-all duration-300 text-center shadow-lg group">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-red-650/10 to-red-600/5 border border-red-500/10 flex items-center justify-center group-hover:border-red-500/30 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-red-400" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                        </svg>
                    </div>
                    <p class="font-semibold text-sm text-zinc-300 group-hover:text-white transition">QR Ticket</p>
                </a>

            </div>
        </div>

        {{-- LATEST EVENTS --}}
        <div class="space-y-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-white tracking-tight">Event Terbaru</h2>
                    <p class="text-zinc-500 text-xs mt-1">Eksplorasi event-event musik dan komunitas terpopuler.</p>
                </div>
                <a href="/events" class="text-xs font-bold text-red-400 hover:text-red-300 transition uppercase tracking-wider">
                    Lihat semua →
                </a>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($latestEvents as $event)
                    <a href="{{ route('events.show', $event->slug) }}"
                        class="premium-glass rounded-3xl overflow-hidden hover:border-red-500/20 hover:bg-zinc-900/60 transition duration-300 group block shadow-lg">
                        <div class="relative h-44 overflow-hidden">
                            @if ($event->cover_image)
                                <img src="{{ asset('storage/' . $event->cover_image) }}" alt="{{ $event->title }}"
                                    class="w-full h-full object-cover group-hover:scale-103 transition duration-500">
                            @else
                                <div class="h-full bg-gradient-to-br from-red-900/40 to-zinc-900"></div>
                            @endif
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                            <span class="absolute bottom-4 left-4 text-[10px] font-bold bg-black/40 px-3 py-1 rounded-full backdrop-blur-md text-white uppercase tracking-wider">
                                {{ $event->category->name ?? 'Event' }}
                            </span>
                        </div>
                        <div class="p-6 space-y-3">
                            <div class="flex items-center justify-between text-xs text-red-400 font-semibold uppercase tracking-wider">
                                <span>{{ $event->city }}</span>
                                <span>{{ $event->start_at->format('d M Y') }}</span>
                            </div>
                            <h3 class="text-lg font-bold text-white group-hover:text-red-400 transition leading-snug line-clamp-1">{{ $event->title }}</h3>
                            <p class="text-zinc-400 text-xs leading-relaxed line-clamp-2 font-normal">{{ $event->description }}</p>
                        </div>
                    </a>
                @empty
                    <div class="md:col-span-3 text-center text-zinc-500 py-12 text-sm">Belum ada event tersedia.</div>
                @endforelse
            </div>
        </div>

    </div>
@endsection
