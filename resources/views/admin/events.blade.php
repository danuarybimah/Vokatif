@extends('layouts.dashboard')

@section('content')

<div class="space-y-8">

    <!-- HEADER -->
    <div class="flex items-center justify-between flex-wrap gap-4">
        <div>
            <p class="text-red-300 uppercase tracking-[0.2em] text-xs font-bold mb-2">Admin</p>
            <h1 class="text-5xl font-black">Semua Event</h1>
            <p class="text-zinc-400 mt-3">Kelola seluruh event di platform Vokatif.</p>
        </div>
        <a href="/admin/events/create"
            class="px-6 py-4 rounded-2xl btn btn-primary transition">
            + Tambah Event
        </a>
    </div>

    @if (session('success'))
        <div id="flash-success" class="bg-emerald-500/20 border border-emerald-500/30 text-emerald-300 px-6 py-4 rounded-2xl flex items-center justify-between">
            <span>✓ {{ session('success') }}</span>
            <button onclick="document.getElementById('flash-success').style.display='none'" class="text-emerald-400 font-bold ml-4 text-lg leading-none">✕</button>
        </div>
    @endif

    <!-- TABLE -->
    <div class="glass rounded-[32px] p-8 overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="text-left border-b border-zinc-800">
                    <th class="pb-4 text-zinc-400 font-semibold text-sm uppercase tracking-wide">Event</th>
                    <th class="pb-4 text-zinc-400 font-semibold text-sm uppercase tracking-wide">Organizer</th>
                    <th class="pb-4 text-zinc-400 font-semibold text-sm uppercase tracking-wide">Kategori</th>
                    <th class="pb-4 text-zinc-400 font-semibold text-sm uppercase tracking-wide">Status</th>
                    <th class="pb-4 text-zinc-400 font-semibold text-sm uppercase tracking-wide">Tiket</th>
                    <th class="pb-4 text-zinc-400 font-semibold text-sm uppercase tracking-wide">Tanggal</th>
                    <th class="pb-4 text-zinc-400 font-semibold text-sm uppercase tracking-wide">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($events as $event)
                    <tr class="border-b border-zinc-800/60 hover:bg-zinc-800/30 transition">

                        <td class="py-5">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-xl overflow-hidden flex-shrink-0">
                                    @if($event->cover_image)
                                        <img src="{{ asset('storage/' . $event->cover_image) }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full bg-gradient-to-br from-red-500 to-rose-500"></div>
                                    @endif
                                </div>
                                <div>
                                    <p class="font-bold">{{ $event->title }}</p>
                                    <p class="text-zinc-400 text-xs">{{ $event->city }}</p>
                                </div>
                            </div>
                        </td>

                        <td class="py-5 text-zinc-300 text-sm">{{ $event->organizer->name ?? '-' }}</td>

                        <td class="py-5">
                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-red-500/20 text-red-300">
                                {{ $event->category->name ?? '-' }}
                            </span>
                        </td>

                        <td class="py-5">
                            <span class="px-3 py-1 rounded-full text-xs font-bold
                                {{ $event->status === 'published' ? 'bg-emerald-500/20 text-emerald-400' : 'bg-yellow-500/20 text-yellow-400' }}">
                                {{ ucfirst($event->status) }}
                            </span>
                        </td>

                        <td class="py-5 text-zinc-300 text-sm">{{ $event->ticketTypes->count() }} tipe</td>

                        <td class="py-5 text-zinc-400 text-sm">
                            {{ optional($event->start_at)->format('d M Y') ?? 'TBA' }}
                        </td>

                        <td class="py-5">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.events.edit', $event->id) }}"
                                   class="px-4 py-2 rounded-xl bg-amber-500/20 text-amber-450 text-sm font-bold hover:bg-amber-500/30 transition">
                                    Edit
                                </a>
                                <form action="/admin/events/{{ $event->id }}" method="POST"
                                    onsubmit="return confirm('Hapus event ini?')" class="m-0">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="px-4 py-2 rounded-xl bg-red-500/20 text-red-450 text-sm font-bold hover:bg-red-500/30 transition">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="py-16 text-center text-zinc-400">
                            Belum ada event.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

@endsection
