@extends('layouts.user')

@section('content')
<div class="space-y-8">

    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-4xl font-extrabold text-white tracking-tight">Upcoming Events</h1>
            <p class="text-zinc-500 text-sm mt-1">Jadwal event yang akan kamu hadiri dalam waktu dekat.</p>
        </div>

        <!-- BACK BUTTON -->
        @php $role = auth()->user()?->role?->slug; @endphp
        <div>
            <a href="{{ $role === 'admin' ? '/admin/dashboard' : ($role === 'organizer' ? '/organizer/dashboard' : '/home') }}"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-zinc-900 border border-zinc-800 text-zinc-400 hover:text-white hover:border-zinc-700 transition text-xs font-bold uppercase tracking-wider">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali
            </a>
        </div>
    </div>

    <div class="space-y-6">
        @forelse($tickets as $ticket)
            @php
                $event     = $ticket->event;
                $daysLeft  = now()->diffInDays($event->start_at, false);
            @endphp

            <div class="premium-glass rounded-3xl p-6 md:p-8 flex flex-col md:flex-row gap-8 items-center shadow-2xl border border-white/5 relative overflow-hidden">
                <!-- Glowing corner light -->
                <div class="absolute -right-16 -top-16 w-32 h-32 bg-red-650/5 rounded-full blur-2xl pointer-events-none"></div>

                <!-- GRADIENT OR IMAGE BANNER -->
                <div class="w-full md:w-48 h-40 rounded-2xl overflow-hidden flex-shrink-0 relative border border-zinc-850 shadow-md">
                    @if ($event->cover_image)
                        <img src="{{ asset('storage/' . $event->cover_image) }}" alt="{{ $event->title }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full bg-gradient-to-br from-red-950/45 to-zinc-900 flex items-center justify-center font-bold text-red-500">Vokatif</div>
                    @endif
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                    <span class="absolute bottom-3 left-3 text-[10px] font-bold bg-black/40 px-2.5 py-1 rounded-full backdrop-blur-md text-white uppercase tracking-wider">
                        {{ $event->category->name ?? 'Event' }}
                    </span>
                </div>

                <!-- INFO -->
                <div class="flex-1 space-y-4 text-center md:text-left w-full">
                    <div class="space-y-1">
                        <div class="flex flex-wrap items-center justify-center md:justify-start gap-2.5 mb-1">
                            @if($daysLeft > 0)
                                <span class="px-2.5 py-0.5 rounded-full bg-red-950/20 border border-red-500/20 text-red-400 text-[10px] font-bold tracking-wider uppercase">
                                    {{ $daysLeft }} hari lagi
                                </span>
                            @elseif($daysLeft === 0)
                                <span class="px-2.5 py-0.5 rounded-full bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-[10px] font-bold tracking-wider uppercase animate-pulse">
                                    HARI INI!
                                </span>
                            @endif
                            <span class="text-zinc-550 text-xs font-semibold uppercase tracking-wider">{{ $event->city }}</span>
                        </div>
                        <h2 class="text-2xl font-bold text-white tracking-tight leading-tight pt-0.5">{{ $event->title }}</h2>
                    </div>

                    <div class="grid grid-cols-3 gap-3 max-w-md">
                        <div class="bg-zinc-950/50 border border-zinc-900 rounded-xl p-3 text-center md:text-left">
                            <p class="text-[9px] text-zinc-550 uppercase font-bold tracking-wider">Tanggal</p>
                            <p class="font-bold text-xs mt-1 text-zinc-350 truncate">{{ $event->start_at->format('d M Y') }}</p>
                        </div>
                        <div class="bg-zinc-950/50 border border-zinc-900 rounded-xl p-3 text-center md:text-left">
                            <p class="text-[9px] text-zinc-550 uppercase font-bold tracking-wider">Waktu</p>
                            <p class="font-bold text-xs mt-1 text-zinc-350 truncate">{{ $event->start_at->format('H:i') }} WIB</p>
                        </div>
                        <div class="bg-zinc-950/50 border border-zinc-900 rounded-xl p-3 text-center md:text-left">
                            <p class="text-[9px] text-zinc-550 uppercase font-bold tracking-wider">Tiket</p>
                            <p class="font-bold text-xs mt-1 text-red-400 truncate">{{ $ticket->ticketType->name }}</p>
                        </div>
                    </div>
                </div>

                <!-- ACTIONS -->
                <div class="w-full md:w-auto flex flex-col sm:flex-row md:flex-col gap-3 flex-shrink-0">
                    <a href="{{ route('tickets.show', $ticket->ticket_code) }}"
                       class="btn-premium-primary text-center px-6 py-3 font-bold text-xs shadow-md uppercase tracking-wider flex items-center justify-center gap-1.5">
                        Lihat QR Pass
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v1m6 11h2m-6 0h-2v4" />
                        </svg>
                    </a>
                    <a href="{{ route('events.show', $event->slug) }}"
                       class="btn-premium-secondary text-center px-6 py-3 font-bold text-xs uppercase tracking-wider">
                        Detail Event
                    </a>
                </div>

            </div>

        @empty
            <div class="premium-glass rounded-[32px] p-16 text-center shadow-lg border border-white/5 space-y-6">
                <div class="w-16 h-16 rounded-full bg-red-950/20 border border-red-500/15 flex items-center justify-center mx-auto text-2xl">
                    📅
                </div>
                <div class="space-y-2">
                    <h2 class="text-2xl font-bold text-white tracking-tight">Tidak ada event upcoming</h2>
                    <p class="text-zinc-500 text-sm max-w-sm mx-auto">Kamu belum memiliki tiket untuk event mendatang. Eksplorasi event terbaru dan segera lakukan pembelian.</p>
                </div>
                <a href="/events" class="btn-premium-primary inline-flex items-center gap-2 px-8 py-3.5 font-bold text-xs uppercase tracking-wider shadow-md">
                    Explore Events
                </a>
            </div>
        @endforelse
    </div>

</div>
@endsection
