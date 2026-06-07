@extends('layouts.user')

@section('content')
<div class="space-y-8">

    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-4xl font-extrabold text-white tracking-tight">QR Tickets</h1>
            <p class="text-zinc-500 text-sm mt-1">Semua QR tiket aktif milikmu untuk masuk ke venue.</p>
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
            <div class="premium-glass rounded-3xl p-6 md:p-8 flex flex-col md:flex-row gap-8 items-center justify-between shadow-2xl border border-white/5 relative overflow-hidden">
                <!-- Glowing corner light -->
                <div class="absolute -right-16 -top-16 w-32 h-32 bg-red-650/5 rounded-full blur-2xl pointer-events-none"></div>

                <div class="flex flex-col md:flex-row gap-8 items-center w-full md:w-auto">
                    <!-- QR PASS WRAPPER (BOARDING PASS LOOK) -->
                    <div class="flex-shrink-0 bg-white p-4.5 rounded-2xl flex flex-col items-center gap-3 shadow-lg shadow-black/40 border border-zinc-800/40">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ $ticket->ticket_code }}"
                             alt="QR {{ $ticket->ticket_code }}"
                             class="w-[140px] h-[140px] object-contain rounded-lg">
                        <p class="font-mono text-xs text-zinc-600 font-bold uppercase tracking-widest leading-none">{{ $ticket->ticket_code }}</p>
                    </div>

                    <!-- PASS META DETAILS -->
                    <div class="flex-1 space-y-5 text-center md:text-left">
                        <div class="space-y-1">
                            <span class="px-2.5 py-0.5 rounded-full bg-red-950/20 border border-red-500/20 text-red-400 text-[10px] font-bold tracking-wider uppercase">Active Pass</span>
                            <h2 class="text-2xl font-bold text-white tracking-tight leading-tight pt-1.5">{{ $ticket->event->title }}</h2>
                            <p class="text-zinc-500 text-xs font-semibold">{{ $ticket->event->city }} · {{ $ticket->event->start_at->format('d M Y H:i') }} WIB</p>
                        </div>

                        <div class="grid grid-cols-3 gap-3 max-w-md">
                            <div class="bg-zinc-950/50 border border-zinc-900 rounded-xl p-3 text-center md:text-left">
                                <p class="text-[9px] text-zinc-550 uppercase font-bold tracking-wider">Tipe</p>
                                <p class="font-bold text-xs mt-1 text-red-400 truncate">{{ $ticket->ticketType->name }}</p>
                            </div>
                            <div class="bg-zinc-950/50 border border-zinc-900 rounded-xl p-3 text-center md:text-left">
                                <p class="text-[9px] text-zinc-550 uppercase font-bold tracking-wider">Status</p>
                                @if($ticket->status === 'active')
                                    <p class="font-bold text-xs mt-1 text-emerald-400">ACTIVE</p>
                                @elseif($ticket->status === 'used')
                                    <p class="font-bold text-xs mt-1 text-zinc-400">USED</p>
                                @else
                                    <p class="font-bold text-xs mt-1 text-rose-400">INVALID</p>
                                @endif
                            </div>
                            <div class="bg-zinc-950/50 border border-zinc-900 rounded-xl p-3 text-center md:text-left">
                                <p class="text-[9px] text-zinc-550 uppercase font-bold tracking-wider">Dibeli</p>
                                <p class="font-bold text-xs mt-1 text-zinc-350 truncate">{{ $ticket->created_at->format('d M Y') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ACTIONS -->
                <div class="w-full md:w-auto flex-shrink-0">
                    <a href="{{ route('tickets.show', $ticket->ticket_code) }}"
                       class="w-full md:w-auto btn-premium-primary text-center px-6 py-3 font-bold text-xs flex items-center justify-center gap-2 shadow-md uppercase tracking-wider">
                        Lihat Tiket Detail
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>

            </div>
        @empty
            <div class="premium-glass rounded-[32px] p-16 text-center shadow-lg border border-white/5 space-y-6">
                <div class="w-16 h-16 rounded-full bg-red-950/20 border border-red-500/15 flex items-center justify-center mx-auto text-2xl">
                    🎫
                </div>
                <div class="space-y-2">
                    <h2 class="text-2xl font-bold text-white tracking-tight">Belum ada tiket aktif</h2>
                    <p class="text-zinc-500 text-sm max-w-sm mx-auto">Silakan beli tiket event terlebih dahulu untuk mendapatkan QR Code masuk.</p>
                </div>
                <a href="/events" class="btn-premium-primary inline-flex items-center gap-2 px-8 py-3.5 font-bold text-xs uppercase tracking-wider shadow-md">
                    Explore Events
                </a>
            </div>
        @endforelse
    </div>

</div>
@endsection
