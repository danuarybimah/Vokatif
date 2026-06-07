@extends('layouts.dashboard')

@section('content')

<div class="space-y-8">

    <!-- HEADER -->
    <div class="flex items-center justify-between flex-wrap gap-4">
        <div>
            <p class="text-red-300 uppercase tracking-[0.2em] text-xs font-bold mb-2">Admin</p>
            <h1 class="text-5xl font-black">Pesan Masuk</h1>
            <p class="text-zinc-400 mt-3">Pesan dari pengunjung melalui halaman Contact.</p>
        </div>
        <div class="glass px-6 py-4 rounded-2xl text-right">
            <p class="text-zinc-400 text-sm">Total Pesan</p>
            <h2 class="text-3xl font-bold text-red-400">{{ $messages->count() }}</h2>
        </div>
    </div>

    <!-- MESSAGES CHAT -->
    <div class="space-y-4">
        @forelse($messages as $msg)
            <div class="glass rounded-2xl p-6 border border-zinc-800 hover:border-red-500/20 transition">
                <div class="flex items-start gap-4">
                    <!-- AVATAR -->
                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-red-500 to-rose-500 flex items-center justify-center font-bold text-lg flex-shrink-0">
                        {{ strtoupper(substr($msg->name, 0, 1)) }}
                    </div>
                    <!-- CONTENT -->
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-3 flex-wrap mb-1">
                            <h3 class="font-bold text-white">{{ $msg->name }}</h3>
                            <span class="text-zinc-400 text-sm">{{ $msg->email }}</span>
                            @if($msg->subject)
                                <span class="px-2 py-0.5 rounded-full text-xs font-bold bg-red-500/20 text-red-300">
                                    {{ $msg->subject }}
                                </span>
                            @endif
                            <span class="ml-auto text-zinc-500 text-xs">
                                {{ $msg->created_at->diffForHumans() }}
                            </span>
                        </div>
                        <p class="text-zinc-300 leading-relaxed mt-2">{{ $msg->message }}</p>
                        <!-- Reply button (mailto) -->
                        <a href="mailto:{{ $msg->email }}?subject=Re: {{ $msg->subject }}"
                            class="inline-flex items-center gap-2 mt-3 px-4 py-2 rounded-xl bg-red-500/10 text-red-400 text-sm font-bold hover:bg-red-500/20 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
                            </svg>
                            Balas via Email
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="glass rounded-[32px] p-16 text-center border border-zinc-800">
                <div class="w-20 h-20 mx-auto mb-4 rounded-full bg-red-500/10 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-zinc-300">Belum ada pesan</h3>
                <p class="text-zinc-500 mt-2">Pesan dari form Contact akan muncul di sini.</p>
            </div>
        @endforelse
    </div>

</div>

@endsection
