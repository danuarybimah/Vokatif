@php
    $role = auth()->user()?->role?->slug;
    $layout = ($role === 'admin' || $role === 'organizer') ? 'layouts.dashboard' : 'layouts.user';
@endphp

@extends($layout)

@section('content')

<div class="space-y-10">

    <!-- BREADCRUMB -->
    <div>
        <a href="{{ route('events.index') }}"
           class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl bg-zinc-900/60 border border-zinc-800 text-xs font-semibold text-zinc-400 hover:text-white transition duration-200">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali ke Daftar Event
        </a>
    </div>

    <!-- HERO -->
    <div class="grid lg:grid-cols-5 gap-8 items-start">

        <!-- IMAGE (LEFT) -->
        <div class="lg:col-span-3 relative rounded-3xl overflow-hidden h-72 lg:h-96 shadow-2xl border border-white/5">
            @if($event->cover_image)
                <img src="{{ asset('storage/' . $event->cover_image) }}"
                     alt="{{ $event->title }}"
                     class="w-full h-full object-cover">
            @else
                <div class="w-full h-full bg-gradient-to-br from-red-950/40 to-zinc-900"></div>
            @endif
            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
            <div class="absolute bottom-5 left-5">
                <span class="px-3 py-1 rounded-full bg-black/40 border border-zinc-850 text-[10px] font-bold uppercase tracking-wider backdrop-blur-md text-white">
                    {{ $event->category->name ?? 'Event' }}
                </span>
            </div>
        </div>

        <!-- INFO (RIGHT) -->
        <div class="lg:col-span-2 flex flex-col justify-between h-full space-y-6">
            <div class="space-y-4">
                <h1 class="text-3xl lg:text-4xl font-extrabold text-white tracking-tight leading-tight">
                    {{ $event->title }}
                </h1>
                <p class="text-zinc-400 text-sm leading-relaxed font-normal">
                    {{ $event->description }}
                </p>
            </div>

            <!-- METADATA WIDGETS -->
            <div class="space-y-3">
                <div class="flex items-center gap-4 p-3.5 rounded-2xl border border-zinc-900 bg-zinc-900/20 shadow-md">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-red-650/10 to-red-600/5 border border-red-500/10 flex items-center justify-center flex-shrink-0 text-red-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-[9px] text-zinc-550 uppercase font-bold tracking-wider">Tanggal & Waktu</p>
                        <p class="text-zinc-200 font-bold text-sm mt-0.5">{{ $event->start_at->format('d M Y, H:i') }} WIB</p>
                    </div>
                </div>

                <div class="flex items-center gap-4 p-3.5 rounded-2xl border border-zinc-900 bg-zinc-900/20 shadow-md">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-red-650/10 to-red-600/5 border border-red-500/10 flex items-center justify-center flex-shrink-0 text-red-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-[9px] text-zinc-550 uppercase font-bold tracking-wider">Lokasi</p>
                        <p class="text-zinc-200 font-bold text-sm mt-0.5 truncate max-w-[280px]">{{ $event->location }}, {{ $event->city }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-4 p-3.5 rounded-2xl border border-zinc-900 bg-zinc-900/20 shadow-md">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-red-650/10 to-red-600/5 border border-red-500/10 flex items-center justify-center flex-shrink-0 text-red-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-[9px] text-zinc-550 uppercase font-bold tracking-wider">Penyelenggara</p>
                        <p class="text-zinc-200 font-bold text-sm mt-0.5 truncate max-w-[280px]">
                            {{ $event->organizer->organizerProfile->organization_name ?? $event->organizer->name }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- TICKETS CHOOSE SECTIONS -->
    <div class="border-t border-zinc-900 pt-10">
        <h2 class="text-xl font-bold text-white tracking-tight mb-6">Pilih Kategori Tiket</h2>
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse ($event->ticketTypes as $ticketType)
                <form action="{{ route('buy.ticket') }}" method="POST">
                    @csrf
                    <input type="hidden" name="event_id" value="{{ $event->id }}">
                    <input type="hidden" name="ticket_type_id" value="{{ $ticketType->id }}">

                    <div class="premium-glass rounded-2xl p-6 hover:border-red-500/20 hover:bg-zinc-900/60 transition shadow-lg border border-white/5 space-y-5">
                        <div class="flex items-start justify-between gap-3 pb-3 border-b border-zinc-800/40">
                            <div>
                                <h3 class="font-bold text-white text-base leading-snug">{{ $ticketType->name }}</h3>
                                <p class="text-zinc-500 text-[10px] uppercase font-bold tracking-wider mt-1">
                                    Sisa {{ $ticketType->quota - ($ticketType->sold ?? 0) }} tiket
                                </p>
                            </div>
                            <p class="text-base font-extrabold text-red-400 whitespace-nowrap leading-none mt-0.5">
                                Rp{{ number_format($ticketType->price, 0, ',', '.') }}
                            </p>
                        </div>
                        <button type="submit"
                                class="w-full py-3 rounded-xl bg-red-600 hover:bg-red-500 text-white font-bold text-xs uppercase tracking-wider transition duration-250 cursor-pointer shadow-md shadow-red-950/20 hover:shadow-red-800/30">
                            Beli Tiket
                        </button>
                    </div>
                </form>
            @empty
                <div class="sm:col-span-2 lg:col-span-3 py-12 text-center text-zinc-500 text-sm premium-glass rounded-2xl border border-dashed border-zinc-800 shadow-md">
                    Belum ada tipe tiket yang terdaftar untuk event ini.
                </div>
            @endforelse
        </div>
    </div>

</div>

@endsection
