@extends($layout)

@section('content')
    <div class="max-w-3xl mx-auto space-y-8">

        <!-- BACK BUTTON & HEADER -->
        <div class="flex flex-col gap-4">
            @php $role = auth()->user()?->role?->slug; @endphp
            <div>
                <a href="{{ $role === 'admin' ? '/admin/dashboard' : ($role === 'organizer' ? '/organizer/dashboard' : '/home') }}"
                    class="inline-flex items-center gap-2 text-zinc-400 hover:text-white transition duration-200 font-bold group text-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 transform group-hover:-translate-x-1 transition duration-200" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali ke {{ $role === 'admin' ? 'Dashboard' : ($role === 'organizer' ? 'Dashboard' : 'Home') }}
                </a>
            </div>
            <div class="mt-2">
                <p class="text-red-400 uppercase tracking-[0.2em] text-xs font-extrabold mb-2">Akun Saya</p>
                <h1 class="text-5xl font-black tracking-tight text-white">Profil Saya</h1>
                <p class="text-zinc-400 mt-2">Kelola informasi pribadi, email, dan kata sandi akun kamu.</p>
            </div>
        </div>

        {{-- FLASH ALERT SUCCESS --}}
        @if (session('success'))
            <div id="flash-success"
                class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-300 px-6 py-4 rounded-3xl flex items-center justify-between shadow-lg shadow-emerald-950/20"
                style="transition: opacity 0.5s ease;">
                <span class="flex items-center gap-2 font-semibold">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-emerald-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    {{ session('success') }}
                </span>
                <button onclick="document.getElementById('flash-success').remove()"
                    class="text-emerald-400 hover:text-emerald-300 font-bold ml-4 p-1 rounded-lg hover:bg-emerald-500/10 transition">✕</button>
            </div>

            @push('scripts')
                <script>
                    setTimeout(() => {
                        const el = document.getElementById('flash-success');
                        if (el) {
                            el.style.opacity = '0';
                            setTimeout(() => el.remove(), 500);
                        }
                    }, 3000);
                </script>
            @endpush
        @endif

        <!-- AVATAR HERO BANNER -->
        <div class="glass rounded-[32px] p-8 md:p-10 flex flex-col md:flex-row items-center gap-8 relative overflow-hidden">
            <!-- Ambient Background Glows -->
            <div class="absolute -right-12 -top-12 w-44 h-44 rounded-full bg-red-600/5 blur-3xl"></div>
            <div class="absolute -left-12 -bottom-12 w-44 h-44 rounded-full bg-rose-600/5 blur-3xl"></div>

            <!-- Avatar with Premium Gradient Ring Glow -->
            <div class="relative group">
                <div class="absolute -inset-1 bg-gradient-to-br from-red-650 to-rose-500 rounded-full blur-md opacity-45 group-hover:opacity-75 transition duration-500"></div>
                <div class="relative w-28 h-28 rounded-full bg-zinc-900 border border-zinc-800/80 flex items-center justify-center font-black text-5xl text-white shadow-2xl flex-shrink-0">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
            </div>

            <div class="text-center md:text-left flex-1 space-y-3">
                <div class="flex flex-col md:flex-row md:items-center gap-3">
                    <h2 class="text-3xl font-bold tracking-tight text-white leading-none">{{ $user->name }}</h2>
                    <div>
                        <span class="inline-flex items-center px-4 py-1 rounded-full text-xs font-bold uppercase tracking-wider border
                        {{ $user->role?->slug === 'admin'
                            ? 'bg-red-500/10 text-red-400 border-red-500/25 shadow-sm shadow-red-950/20'
                            : ($user->role?->slug === 'organizer'
                                ? 'bg-rose-500/10 text-rose-400 border-rose-500/25 shadow-sm shadow-rose-950/20'
                                : 'bg-emerald-500/10 text-emerald-400 border-emerald-500/25 shadow-sm shadow-emerald-950/20') }}">
                            {{ $user->role?->slug ?? 'user' }}
                        </span>
                    </div>
                </div>
                <p class="text-zinc-400 font-medium leading-none">{{ $user->email }}</p>
            </div>
        </div>

        <!-- PROFILE EDIT FORM -->
        <div class="glass rounded-[32px] p-8 md:p-10 space-y-8">
            <div class="border-b border-zinc-850/80 pb-5">
                <h2 class="text-2xl font-bold text-white tracking-tight">Edit Informasi</h2>
                <p class="text-zinc-400 text-sm mt-1">Ubah nama lengkap, email, atau perbarui kata sandi akun kamu.</p>
            </div>

            <form action="/profile" method="POST" class="space-y-8">
                @csrf
                @method('PUT')

                <!-- Personal Information Grid -->
                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <label class="block mb-3 text-zinc-300 font-bold text-sm uppercase tracking-wide">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}"
                            class="w-full rounded-2xl input-field px-5 py-4" placeholder="Masukkan nama lengkap">
                        @error('name')
                            <p class="text-red-400 text-sm mt-2 flex items-center gap-1.5">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div>
                        <label class="block mb-3 text-zinc-300 font-bold text-sm uppercase tracking-wide">Email</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}"
                            class="w-full rounded-2xl input-field px-5 py-4" placeholder="Masukkan alamat email">
                        @error('email')
                            <p class="text-red-400 text-sm mt-2 flex items-center gap-1.5">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>

                <!-- Nested Credentials Modification Card -->
                <div class="bg-zinc-950/30 border border-zinc-900/80 rounded-3xl p-6 md:p-8 space-y-6">
                    <h3 class="text-lg font-bold text-zinc-200 flex items-center gap-2.5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5.5 h-5.5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        Ganti Kata Sandi
                        <span class="text-zinc-550 font-normal text-xs ml-1">(kosongkan jika tidak ingin ganti)</span>
                    </h3>

                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <label class="block mb-3 text-zinc-400 font-semibold text-sm uppercase tracking-wide">Password Baru</label>
                            <div class="relative">
                                <input type="password" name="password" id="new_password"
                                    class="w-full rounded-2xl input-field px-5 py-4 pr-14"
                                    placeholder="Minimal 8 karakter">
                                <button type="button" onclick="togglePassword('new_password', 'eye1')"
                                    class="absolute right-4 top-1/2 -translate-y-1/2 text-zinc-500 hover:text-white transition duration-200 outline-none">
                                    <svg id="eye1" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>
                            </div>
                            @error('password')
                                <p class="text-red-400 text-sm mt-2 flex items-center gap-1.5">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div>
                            <label class="block mb-3 text-zinc-400 font-semibold text-sm uppercase tracking-wide">Konfirmasi Password</label>
                            <div class="relative">
                                <input type="password" name="password_confirmation" id="confirm_password"
                                    class="w-full rounded-2xl input-field px-5 py-4 pr-14"
                                    placeholder="Ulangi password baru">
                                <button type="button" onclick="togglePassword('confirm_password', 'eye2')"
                                    class="absolute right-4 top-1/2 -translate-y-1/2 text-zinc-500 hover:text-white transition duration-200 outline-none">
                                    <svg id="eye2" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Simpan Button styled to premium Vokatif Button architecture -->
                <button type="submit"
                    class="w-full py-5 rounded-[20px] btn btn-primary text-xl transition shadow-xl shadow-red-950/30">
                    Simpan Perubahan
                </button>

            </form>
        </div>

        <!-- ACCOUNT DETAILS CARD -->
        <div class="glass rounded-[32px] p-8 md:p-10">
            <h2 class="text-2xl font-bold text-white tracking-tight mb-6">Informasi Akun</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                <div class="bg-zinc-950/30 border border-zinc-850/50 hover:border-zinc-800 rounded-2xl p-6 transition duration-300">
                    <p class="text-zinc-500 text-xs font-bold uppercase tracking-wider">Member Sejak</p>
                    <p class="font-extrabold text-lg text-white mt-2 leading-none">
                        {{ $user->created_at->format('d M Y') }}
                    </p>
                </div>

                <div class="bg-zinc-950/30 border border-zinc-850/50 hover:border-zinc-800 rounded-2xl p-6 transition duration-300">
                    <p class="text-zinc-500 text-xs font-bold uppercase tracking-wider">Role Akses</p>
                    <p class="font-extrabold text-lg text-white mt-2 leading-none capitalize">
                        {{ $user->role?->slug ?? 'user' }}
                    </p>
                </div>

                <div class="bg-zinc-950/30 border border-zinc-850/50 hover:border-zinc-800 rounded-2xl p-6 transition duration-300">
                    <p class="text-zinc-500 text-xs font-bold uppercase tracking-wider">Status Akun</p>
                    <p class="font-extrabold text-lg text-emerald-400 mt-2 leading-none flex items-center gap-2.5">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-450 operational-light"></span>
                        Aktif
                    </p>
                </div>

            </div>
        </div>

    </div>
@endsection

@push('scripts')
    <script>
        function togglePassword(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            if (input.type === 'password') {
                input.type = 'text';
                icon.innerHTML =
                    `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/>`;
            } else {
                input.type = 'password';
                icon.innerHTML =
                    `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>`;
            }
        }
    </script>
@endpush
