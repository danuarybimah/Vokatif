@extends('layouts.dashboard')

@section('content')

<div class="space-y-8">

    <!-- HEADER -->
    <div class="flex items-center justify-between flex-wrap gap-4">
        <div>
            <p class="text-rose-300 uppercase tracking-[0.2em] text-xs font-bold mb-2">Organizer</p>
            <h1 class="text-5xl font-extrabold">Peserta Check-in</h1>
            <p class="text-zinc-400 mt-3">Daftar semua peserta yang telah melakukan check-in via QR Code.</p>
        </div>
        <div class="glass px-6 py-4 rounded-2xl text-right">
            <p class="text-zinc-400 text-sm">Total Check-in</p>
            <h2 class="text-3xl font-bold text-rose-400">{{ $checkins->count() }}</h2>
        </div>
    </div>

    <!-- TABLE -->
    <div class="glass rounded-[32px] p-8 overflow-x-auto">
        @if($checkins->isEmpty())
            <div class="text-center py-16">
                <div class="text-6xl mb-4">📋</div>
                <h3 class="text-2xl font-bold text-zinc-300">Belum ada check-in</h3>
                <p class="text-zinc-400 mt-2">Peserta yang check-in via QR Scanner akan muncul di sini.</p>
                <a href="/organizer/checkin-scanner"
                    class="inline-flex items-center gap-2 mt-6 px-6 py-3 rounded-xl bg-rose-500/20 text-rose-400 font-bold hover:bg-rose-500/30 transition">
                    Buka QR Scanner
                </a>
            </div>
        @else
            <table class="w-full">
                <thead>
                    <tr class="text-left border-b border-zinc-800">
                        <th class="pb-4 text-zinc-400 font-semibold text-sm uppercase tracking-wide">#</th>
                        <th class="pb-4 text-zinc-400 font-semibold text-sm uppercase tracking-wide">Peserta</th>
                        <th class="pb-4 text-zinc-400 font-semibold text-sm uppercase tracking-wide">Event</th>
                        <th class="pb-4 text-zinc-400 font-semibold text-sm uppercase tracking-wide">Tipe Tiket</th>
                        <th class="pb-4 text-zinc-400 font-semibold text-sm uppercase tracking-wide">Kode Tiket</th>
                        <th class="pb-4 text-zinc-400 font-semibold text-sm uppercase tracking-wide">Status</th>
                        <th class="pb-4 text-zinc-400 font-semibold text-sm uppercase tracking-wide">Waktu Check-in</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($checkins as $i => $checkin)
                        <tr class="border-b border-zinc-800/60 hover:bg-zinc-900/80 transition">

                            <td class="py-5 text-zinc-500 text-sm">{{ $i + 1 }}</td>

                            <td class="py-5">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-gradient-to-br from-rose-500 to-red-500 flex items-center justify-center font-bold text-sm flex-shrink-0">
                                        {{ strtoupper(substr($checkin->ticket->user->name ?? 'U', 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="font-bold">{{ $checkin->ticket->user->name ?? '-' }}</p>
                                        <p class="text-zinc-400 text-xs">{{ $checkin->ticket->user->email ?? '-' }}</p>
                                    </div>
                                </div>
                            </td>

                            <td class="py-5">
                                <p class="font-semibold text-sm">{{ $checkin->event->title ?? '-' }}</p>
                            </td>

                            <td class="py-5">
                                @if($checkin->ticket->ticketType)
                                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-red-500/20 text-red-300">
                                        {{ $checkin->ticket->ticketType->name }}
                                    </span>
                                @else
                                    <span class="text-zinc-500 text-xs">-</span>
                                @endif
                            </td>

                            <td class="py-5">
                                <code class="text-xs font-mono text-red-400 bg-red-500/10 px-2 py-1 rounded">
                                    {{ $checkin->ticket->ticket_code ?? '-' }}
                                </code>
                            </td>

                            <td class="py-5">
                                <span class="px-3 py-1 rounded-full text-xs font-bold
                                    {{ $checkin->status === 'success' ? 'bg-emerald-500/20 text-emerald-400' : 'bg-red-500/20 text-red-400' }}">
                                    {{ ucfirst($checkin->status) }}
                                </span>
                            </td>

                            <td class="py-5 text-zinc-400 text-sm">
                                {{ $checkin->created_at->format('d M Y, H:i') }}
                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

</div>

@endsection
