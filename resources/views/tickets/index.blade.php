@extends('layouts.user')

@section('content')
<div class="space-y-8">

    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-4xl font-extrabold text-white tracking-tight">My Tickets</h1>
            <p class="text-zinc-500 text-sm mt-1">Daftar lengkap seluruh tiket event yang telah kamu pesan.</p>
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

    <!-- LEDGER TABLE -->
    <div class="premium-glass rounded-[24px] overflow-hidden shadow-2xl border border-white/5">
        <div class="overflow-x-auto">
            <table class="w-full border-collapse">
                <thead class="border-b border-zinc-800 bg-zinc-950/40">
                    <tr>
                        <th class="text-left px-8 py-5 text-zinc-500 text-[10px] font-bold uppercase tracking-wider">Event</th>
                        <th class="text-left px-8 py-5 text-zinc-500 text-[10px] font-bold uppercase tracking-wider">Tipe Tiket</th>
                        <th class="text-left px-8 py-5 text-zinc-500 text-[10px] font-bold uppercase tracking-wider">Kode Tiket</th>
                        <th class="text-left px-8 py-5 text-zinc-500 text-[10px] font-bold uppercase tracking-wider">Status</th>
                        <th class="text-left px-8 py-5 text-zinc-500 text-[10px] font-bold uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-900/60">
                    @forelse($tickets as $ticket)
                        @php
                            $categorySlug = strtolower($ticket->event->category->slug ?? '');
                            $eligibleCategories = ['technology', 'business', 'education'];
                            $canGetCertificate = $ticket->status === 'used' && in_array($categorySlug, $eligibleCategories);
                        @endphp
                        <tr class="hover:bg-zinc-900/20 transition-all duration-200">
                            <!-- EVENT -->
                            <td class="px-8 py-5">
                                <p class="font-bold text-white text-sm leading-snug">{{ $ticket->event->title }}</p>
                                <p class="text-zinc-550 text-xs mt-1.5 font-medium">{{ $ticket->event->city }}</p>
                            </td>

                            <!-- TYPE -->
                            <td class="px-8 py-5 text-red-400 font-bold text-xs uppercase tracking-wider">
                                {{ $ticket->ticketType->name }}
                            </td>

                            <!-- CODE -->
                            <td class="px-8 py-5">
                                <span class="font-mono text-xs font-bold text-zinc-300 block tracking-wider">{{ $ticket->ticket_code }}</span>
                                <span class="text-[10px] text-zinc-500 font-semibold mt-1 block">{{ $ticket->created_at->format('d M Y · H:i') }}</span>
                            </td>

                            <!-- STATUS -->
                            <td class="px-8 py-5">
                                @if($ticket->status === 'active')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-[10px] font-bold uppercase tracking-wider leading-none">ACTIVE</span>
                                @elseif($ticket->status === 'used')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-zinc-900/60 border border-zinc-800 text-zinc-400 text-[10px] font-bold uppercase tracking-wider leading-none">USED</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-rose-500/10 border border-rose-500/20 text-rose-400 text-[10px] font-bold uppercase tracking-wider leading-none">INVALID</span>
                                @endif
                            </td>

                            <!-- ACTION -->
                            <td class="px-8 py-5">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <!-- QR -->
                                    <a href="{{ route('tickets.show', $ticket->ticket_code) }}"
                                       class="inline-flex items-center px-4 py-2 rounded-xl bg-red-650 hover:bg-red-500 text-white font-bold text-xs uppercase tracking-wider transition shadow-sm">
                                        View QR
                                    </a>
                                    <!-- EVENT -->
                                    <a href="{{ route('events.show', $ticket->event->slug) }}"
                                       class="inline-flex items-center px-4 py-2 rounded-xl bg-zinc-900 hover:bg-zinc-850 border border-zinc-800 text-zinc-350 font-bold text-xs uppercase tracking-wider transition">
                                        Event
                                    </a>
                                    <!-- SERTIFIKAT -->
                                    @if($canGetCertificate)
                                        <a href="{{ route('ticket.certificate', $ticket->ticket_code) }}"
                                           class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-gradient-to-r from-amber-500/15 to-orange-500/15 border border-amber-500/25 text-amber-300 hover:from-amber-500/25 hover:to-orange-500/25 transition font-bold text-xs uppercase tracking-wider"
                                           title="Download Sertifikat">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                                            </svg>
                                            Sertifikat
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-20 text-zinc-500 text-sm font-medium">
                                Belum ada tiket terdaftar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
