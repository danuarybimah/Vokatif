@php
    $role = auth()->user()?->role?->slug;
    $layout = ($role === 'admin' || $role === 'organizer') ? 'layouts.dashboard' : 'layouts.user';
@endphp

@extends($layout)

@section('content')

<div class="space-y-8">

    <!-- HEADER -->
    <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-6 pb-6 border-b border-zinc-900">
        <div>
            <p class="text-red-400 text-xs font-bold uppercase tracking-[0.25em] mb-2">Vokatif Ecosystem</p>
            <h1 class="text-4xl font-extrabold text-white tracking-tight leading-none">Jelajahi Event</h1>
            <p class="text-zinc-550 text-sm mt-2">
                Temukan event teknologi, musik, bisnis, edukasi, dan lifestyle terbaik di kotamu.
            </p>
        </div>

        <form method="GET" action="{{ route('events.index') }}" class="flex gap-2.5">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Cari event atau kota..."
                   class="w-60 bg-zinc-900/60 border border-zinc-800 rounded-xl px-4 py-2.5 text-sm text-white placeholder-zinc-600 outline-none focus:border-red-500/50 transition">
            <button class="px-5 py-2.5 bg-red-650 hover:bg-red-500 text-white text-xs font-bold uppercase tracking-wider rounded-xl transition cursor-pointer shadow-md">
                Cari
            </button>
        </form>
    </div>

    <!-- CATEGORY -->
    <div class="flex flex-wrap gap-2.5">
        <a href="{{ route('events.index') }}"
           class="px-4 py-2 rounded-full text-xs font-semibold uppercase tracking-wider transition
           {{ !request('category') ? 'bg-red-600 text-white shadow-md shadow-red-950/20' : 'text-zinc-400 hover:text-white border border-zinc-850 bg-zinc-900/10 hover:border-zinc-700' }}">
            Semua
        </a>
        @foreach ($categories as $category)
            <a href="{{ route('events.index', ['category' => $category->slug]) }}"
               class="px-4 py-2 rounded-full text-xs font-semibold uppercase tracking-wider transition
               {{ request('category') === $category->slug ? 'bg-red-600 text-white shadow-md shadow-red-950/20' : 'text-zinc-400 hover:text-white border border-zinc-850 bg-zinc-900/10 hover:border-zinc-700' }}">
                {{ $category->name }}
            </a>
        @endforeach
    </div>

    <!-- EVENT GRID -->
    <div class="grid md:grid-cols-2 xl:grid-cols-3 gap-6">

        @forelse ($events as $event)
            <a href="{{ route('events.show', $event->slug) }}"
               class="group premium-glass rounded-3xl overflow-hidden premium-glass-hover block p-4 shadow-xl">

                <div class="h-48 overflow-hidden relative rounded-2xl mb-4">
                    @if($event->cover_image)
                        <img src="{{ asset('storage/' . $event->cover_image) }}"
                             alt="{{ $event->title }}"
                             class="w-full h-full object-cover group-hover:scale-103 transition duration-500">
                    @else
                        <div class="h-full w-full bg-gradient-to-br from-red-950/40 to-zinc-900"></div>
                    @endif
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                    <span class="absolute bottom-3 left-3 px-2.5 py-1 rounded-full bg-black/45 text-[10px] font-bold uppercase tracking-wider backdrop-blur-md text-white">
                        {{ $event->category->name ?? 'Event' }}
                    </span>
                </div>

                <div class="px-1 pb-1 space-y-3">
                    <div class="flex items-center justify-between text-[11px] text-red-400 font-bold uppercase tracking-wider">
                        <span>{{ $event->city }}</span>
                        <span>{{ $event->start_at->format('d M Y') }}</span>
                    </div>

                    <h2 class="text-lg font-bold text-white group-hover:text-red-400 transition line-clamp-1 leading-snug">
                        {{ $event->title }}
                    </h2>

                    <p class="text-zinc-450 text-xs font-normal line-clamp-2 leading-relaxed">
                        {{ $event->description }}
                    </p>

                    <div class="pt-3 border-t border-zinc-800/40 flex items-center justify-between">
                        <div>
                            <p class="text-[9px] text-zinc-550 uppercase font-bold tracking-wider">Mulai Dari</p>
                            <p class="text-sm font-extrabold text-white mt-0.5">
                                Rp{{ number_format($event->ticketTypes->min('price') ?? 0, 0, ',', '.') }}
                            </p>
                        </div>
                        <span class="text-[10px] font-bold text-red-400 group-hover:text-red-300 uppercase tracking-wider flex items-center gap-1">
                            Detail →
                        </span>
                    </div>
                </div>

            </a>

        @empty
            <div class="md:col-span-3 py-20 text-center premium-glass rounded-3xl border border-dashed border-zinc-800 shadow-md">
                <p class="text-4xl mb-4">🔍</p>
                <p class="text-zinc-400 font-bold text-base">Event tidak ditemukan</p>
                <p class="text-zinc-550 text-xs mt-1">Coba masukkan kata kunci pencarian atau kategori event yang berbeda.</p>
            </div>
        @endforelse

    </div>

    <div class="pt-6">{{ $events->links() }}</div>

</div>

@endsection
