@extends('layouts.dashboard')

@section('content')
    <div class="space-y-8">

        <!-- HEADER -->
        <div class="flex items-center justify-between">
            <div>
                <p class="text-red-300 uppercase tracking-[0.2em] text-xs font-bold mb-2">Admin</p>
                <h1 class="text-5xl font-black">Manajemen User</h1>
                <p class="text-zinc-400 mt-3">Kelola semua user dan ubah role mereka.</p>
            </div>
            <div class="glass px-6 py-4 rounded-2xl">
                <p class="text-zinc-400 text-sm">Total Users</p>
                <h2 class="text-3xl font-bold text-red-400">{{ $users->total() }}</h2>
            </div>
        </div>

        @if (session('success'))
            <div id="flash-success"
                class="bg-emerald-500/20 border border-emerald-500/30 text-emerald-300 px-6 py-4 rounded-2xl flex items-center justify-between">
                <span>✓ {{ session('success') }}</span>
                <button onclick="document.getElementById('flash-success').style.display='none'"
                    class="text-emerald-400 font-bold ml-4 text-lg leading-none">✕</button>
            </div>
        @endif

        <!-- SEARCH BAR -->
        <div class="flex justify-end">
            <form action="{{ route('admin.users') }}" method="GET" class="w-full max-w-md flex items-center gap-2">
                <div class="relative w-full">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none">
                        <svg class="w-4 h-4 text-zinc-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                        </svg>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari nama atau email user..."
                        class="w-full pl-10 pr-10 py-2.5 rounded-xl bg-zinc-900/60 border border-zinc-800/80 text-zinc-200 placeholder-zinc-500 text-sm focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500 transition duration-200"
                        oninput="clearTimeout(this.searchTimeout); this.searchTimeout = setTimeout(() => this.form.submit(), 600)">
                    @if(request('search'))
                        <a href="{{ route('admin.users') }}" class="absolute inset-y-0 right-0 flex items-center pr-3 text-zinc-500 hover:text-zinc-300">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- TABLE -->
        <div class="glass rounded-[32px] p-8">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="text-left border-b border-zinc-800">
                            <th class="pb-4 text-zinc-400 font-semibold text-sm uppercase tracking-wide">User</th>
                            <th class="pb-4 text-zinc-400 font-semibold text-sm uppercase tracking-wide">Email</th>
                            <th class="pb-4 text-zinc-400 font-semibold text-sm uppercase tracking-wide">Role Sekarang</th>
                            <th class="pb-4 text-zinc-400 font-semibold text-sm uppercase tracking-wide">Bergabung</th>
                            <th class="pb-4 text-zinc-400 font-semibold text-sm uppercase tracking-wide">Ubah Role</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr class="border-b border-zinc-800/60 hover:bg-zinc-800/30 transition">
    
                                <td class="py-5">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-10 h-10 rounded-full bg-gradient-to-br from-red-500 to-rose-500 flex items-center justify-center font-bold text-sm flex-shrink-0">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                        <span class="font-bold">{{ $user->name }}</span>
                                    </div>
                                </td>
    
                                <td class="py-5 text-zinc-400">{{ $user->email }}</td>
    
                                <td class="py-5">
                                    @php
                                        $roleSlug = $user->role?->slug;
                                        $roleColor = match ($roleSlug) {
                                            'admin' => 'bg-red-500/20 text-red-400',
                                            'organizer' => 'bg-rose-500/20 text-rose-400',
                                            'user' => 'bg-red-500/20 text-red-400',
                                            default => 'bg-zinc-500/20 text-zinc-400',
                                        };
                                    @endphp
                                    <span class="px-3 py-1 rounded-full text-sm font-bold {{ $roleColor }}">
                                        {{ ucfirst($roleSlug ?? 'unknown') }}
                                    </span>
                                </td>
    
                                <td class="py-5 text-zinc-400 text-sm">
                                    {{ $user->created_at->format('d M Y') }}
                                </td>
    
                                <td class="py-5">
                                    <form action="/admin/users/{{ $user->id }}/role" method="POST"
                                        class="flex items-center gap-2">
                                        @csrf
                                        @method('PUT')
                                        <select name="role_id" class="select-field-sm">
                                            @foreach ($roles as $role)
                                                @if ($role->slug === 'admin' || strtolower($role->name) === 'admin')
                                                    @continue
                                                @endif
                                                <option value="{{ $role->id }}"
                                                    {{ $user->role_id == $role->id ? 'selected' : '' }}>
                                                    {{ ucfirst($role->name) }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <button type="submit"
                                            class="px-4 py-2 rounded-xl bg-red-500/20 text-red-400 text-sm font-bold hover:bg-red-500/30 transition">
                                            Simpan
                                        </button>
                                    </form>
                                </td>
    
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- PAGINATION -->
            @if ($users->hasPages())
                <div class="flex flex-col sm:flex-row items-center justify-between border-t border-zinc-800/60 pt-6 mt-6 gap-4">
                    <!-- Info -->
                    <div class="text-sm text-zinc-400">
                        Menampilkan <span class="font-semibold text-zinc-200">{{ $users->firstItem() }}</span> 
                        sampai <span class="font-semibold text-zinc-200">{{ $users->lastItem() }}</span> 
                        dari <span class="font-semibold text-zinc-200">{{ $users->total() }}</span> user
                    </div>

                    <!-- Navigation -->
                    <div class="flex items-center gap-1.5">
                        {{-- Previous Page Link --}}
                        @if ($users->onFirstPage())
                            <span class="px-4 py-2 rounded-xl bg-zinc-900/40 border border-zinc-800/40 text-zinc-650 text-sm font-semibold cursor-not-allowed select-none">
                                Previous
                            </span>
                        @else
                            <a href="{{ $users->previousPageUrl() }}" 
                               class="px-4 py-2 rounded-xl bg-zinc-900/60 border border-zinc-800/80 text-zinc-300 hover:text-white hover:bg-zinc-800/60 hover:border-red-500/30 text-sm font-semibold transition duration-200">
                                Previous
                            </a>
                        @endif

                        {{-- Page Numbers --}}
                        @foreach ($users->getUrlRange(1, $users->lastPage()) as $page => $url)
                            @if ($page == $users->currentPage())
                                <span class="px-3.5 py-2 rounded-xl bg-red-500/20 border border-red-500/35 text-red-400 text-sm font-bold shadow-sm shadow-red-950/20 select-none">
                                    {{ $page }}
                                </span>
                            @else
                                <a href="{{ $url }}" 
                                   class="px-3.5 py-2 rounded-xl bg-zinc-900/60 border border-zinc-800/80 text-zinc-400 hover:text-white hover:bg-zinc-800/60 hover:border-red-500/30 text-sm font-semibold transition duration-200">
                                    {{ $page }}
                                </a>
                            @endif
                        @endforeach

                        {{-- Next Page Link --}}
                        @if ($users->hasMorePages())
                            <a href="{{ $users->nextPageUrl() }}" 
                               class="px-4 py-2 rounded-xl bg-zinc-900/60 border border-zinc-800/80 text-zinc-300 hover:text-white hover:bg-zinc-800/60 hover:border-red-500/30 text-sm font-semibold transition duration-200">
                                Next
                            </a>
                        @else
                            <span class="px-4 py-2 rounded-xl bg-zinc-900/40 border border-zinc-800/40 text-zinc-650 text-sm font-semibold cursor-not-allowed select-none">
                                Next
                            </span>
                        @endif
                    </div>
                </div>
            @endif
        </div>

    </div>
@endsection
