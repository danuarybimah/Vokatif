<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Vokatif — Event & Ticketing Platform</title>
    <meta name="description" content="Platform manajemen event dan ticketing modern. Temukan event terbaik, beli tiket, dan check-in dengan QR Code.">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600;750&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            background: #09090b;
            color: #f4f4f5;
            font-family: 'Inter', sans-serif;
            overflow-x: hidden;
        }
        h1, h2, h3, h4 {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        #home {
            background: radial-gradient(ellipse 70% 50% at 10% -10%, rgba(220,38,38,0.15) 0%, transparent 60%),
                        radial-gradient(ellipse 50% 40% at 90% 110%, rgba(244,63,94,0.08) 0%, transparent 50%);
        }
        #events {
            background: radial-gradient(ellipse 60% 40% at 100% 0%, rgba(220,38,38,0.06) 0%, transparent 60%),
                        radial-gradient(ellipse 60% 40% at 0% 100%, rgba(220,38,38,0.04) 0%, transparent 60%);
        }
        #about {
            background: radial-gradient(circle at 50% 50%, rgba(220,38,38,0.04), transparent 50%);
        }
        #contact {
            background: radial-gradient(ellipse 60% 50% at 50% 100%, rgba(220,38,38,0.10) 0%, transparent 55%);
        }
        html { scroll-behavior: smooth; }
    </style>
</head>
<body class="selection:bg-red-500/30 selection:text-white">
    <!-- HEADER -->
    @include('partials.header')

    <!-- MAIN CONTENT -->
    <main class="min-h-screen">

        <!-- HERO SECTION -->
        <section id="home" class="relative px-6 py-20 lg:py-32 overflow-hidden border-b border-zinc-900">
            <div class="absolute inset-0 pointer-events-none">
                <div class="absolute top-0 left-1/4 w-[600px] h-[600px] bg-red-650/8 rounded-full blur-[140px]"></div>
                <div class="absolute bottom-0 right-1/4 w-[500px] h-[500px] bg-rose-650/6 rounded-full blur-[120px]"></div>
            </div>

            <div class="max-w-7xl mx-auto relative grid lg:grid-cols-12 gap-16 items-center">
                <!-- HERO CONTENT -->
                <div class="lg:col-span-7 space-y-8 text-left">
                    <!-- BADGE -->
                    <div class="inline-flex items-center gap-2 rounded-full border border-red-500/20 bg-red-950/20 px-4.5 py-2 text-xs font-semibold text-red-300 backdrop-blur-md">
                        <span class="w-1.5 h-1.5 rounded-full bg-red-500 animate-pulse"></span>
                        Event Management & Ticketing
                    </div>

                    <!-- HEADLINE -->
                    <h1 class="text-5xl sm:text-6xl xl:text-7xl font-extrabold leading-[1.08] tracking-tight text-white">
                        Temukan Event,<br>Beli Tiket,<br>
                        <span class="bg-gradient-to-r from-red-400 via-rose-450 to-red-550 bg-clip-text text-transparent">
                            Check-in Instan QR
                        </span>
                    </h1>

                    <p class="text-lg text-zinc-400 max-w-xl leading-relaxed font-normal">
                        Platform manajemen event generasi baru. Dukungan penuh untuk JWT secure auth, simulator pembayaran, visualisasi analitik, dan validasi tiket QR real-time.
                    </p>

                    <!-- CTA BUTTONS -->
                    <div class="flex flex-wrap gap-4 pt-2">
                        <a href="{{ route('events.index') }}"
                           class="btn-premium-primary inline-flex items-center gap-2.5 px-8 py-4 font-bold text-white text-base">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                            Explore Events
                        </a>
                        <a href="#about"
                           class="btn-premium-secondary inline-flex items-center gap-2 px-8 py-4 font-bold text-base">
                            Pelajari Lebih
                        </a>
                    </div>

                    <!-- STATS -->
                    <div class="grid grid-cols-3 gap-6 pt-10 border-t border-zinc-900 max-w-lg">
                        <div class="space-y-1">
                            <p class="text-3xl font-extrabold text-white">100+</p>
                            <p class="text-xs font-semibold text-zinc-500 uppercase tracking-wider">Events Active</p>
                        </div>
                        <div class="space-y-1">
                            <p class="text-3xl font-extrabold text-white">10K+</p>
                            <p class="text-xs font-semibold text-zinc-500 uppercase tracking-wider">Tickets Sold</p>
                        </div>
                        <div class="space-y-1">
                            <p class="text-3xl font-extrabold text-white">99.9%</p>
                            <p class="text-xs font-semibold text-zinc-500 uppercase tracking-wider">System Uptime</p>
                        </div>
                    </div>
                </div>

                <!-- HERO GRAPHICAL ELEMENT (ASYSMMETRICAL MOCKUP) -->
                <div class="lg:col-span-5 relative hidden lg:block">
                    <!-- Glow background -->
                    <div class="absolute -inset-1 rounded-3xl bg-gradient-to-tr from-red-600/20 to-rose-600/25 blur-2xl opacity-80"></div>

                    <!-- Main Card Pass -->
                    <div class="relative premium-glass rounded-3xl p-8 border border-white/5 space-y-6 shadow-2xl">
                        <div class="flex items-center justify-between border-b border-zinc-800/60 pb-5">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-red-650 to-rose-600 flex items-center justify-center font-bold text-sm text-white shadow-md shadow-red-950/30">
                                    V
                                </div>
                                <div>
                                    <p class="font-bold text-sm text-white">Vokatif Live Pass</p>
                                    <p class="text-[10px] text-zinc-500 uppercase font-semibold tracking-wider">Premium Access</p>
                                </div>
                            </div>
                            <span class="px-2.5 py-1 rounded-full bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-[10px] font-bold tracking-wider uppercase">Active Ticket</span>
                        </div>

                        <!-- Card Event Graphic -->
                        <div class="h-44 rounded-2xl bg-gradient-to-br from-zinc-900 to-zinc-950 border border-zinc-850 p-5 flex flex-col justify-between relative overflow-hidden group">
                            <!-- Subtle mesh glow -->
                            <div class="absolute -right-10 -bottom-10 w-36 h-36 rounded-full bg-red-600/10 blur-xl"></div>

                            <div class="flex justify-between items-start z-10">
                                <span class="px-2.5 py-1 rounded-full bg-black/40 text-[10px] font-bold text-white uppercase tracking-wider backdrop-blur-md">Concert</span>
                                <p class="text-zinc-500 text-xs font-semibold">Surabaya, ID</p>
                            </div>

                            <div class="z-10 space-y-1">
                                <h3 class="font-extrabold text-white text-lg leading-tight">Sound of Vokatif 2026</h3>
                                <p class="text-[11px] text-zinc-400">Sabtu, 20 Juni 2026 · 19:00 WIB</p>
                            </div>
                        </div>

                        <!-- Bottom Meta -->
                        <div class="flex items-center justify-between pt-2">
                            <div>
                                <p class="text-[10px] text-zinc-500 uppercase tracking-wider font-semibold">Holder Name</p>
                                <p class="text-sm font-bold text-white mt-0.5">Fahrel Handsome</p>
                            </div>
                            <div class="text-right">
                                <p class="text-[10px] text-zinc-500 uppercase tracking-wider font-semibold">Seat Code</p>
                                <p class="text-sm font-mono font-bold text-red-400 mt-0.5">VK-202606</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- FEATURED EVENTS SECTION -->
        <section id="events" class="px-6 py-24 border-b border-zinc-900">
            <div class="max-w-7xl mx-auto">
                <div class="space-y-16">
                    <div class="text-center space-y-4">
                        <p class="text-red-400 font-bold uppercase tracking-[0.2em] text-xs">JELAJAHI EVENT</p>
                        <h2 class="text-4xl lg:text-5xl font-extrabold text-white tracking-tight">Featured Events</h2>
                        <p class="text-zinc-400 text-base max-w-xl mx-auto">Jelajahi event-event terbaik dan terbaru yang telah terkurasi untuk menemani hari-harimu.</p>
                    </div>

                    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                        @forelse ($featuredEvents as $event)
                            <a href="{{ route('events.show', $event->slug) }}"
                               class="group premium-glass rounded-3xl overflow-hidden premium-glass-hover block p-4 shadow-xl">
                                <div class="relative h-52 rounded-2xl overflow-hidden mb-5">
                                    @if($event->cover_image)
                                        <img src="{{ asset('storage/' . $event->cover_image) }}" alt="{{ $event->title }}" class="w-full h-full object-cover group-hover:scale-103 transition-transform duration-500">
                                    @else
                                        <div class="w-full h-full gradient-red-primary group-hover:scale-103 transition-transform duration-500 opacity-90"></div>
                                    @endif
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent"></div>
                                    @if($event->category)
                                        <span class="absolute top-4 left-4 px-3 py-1 rounded-full bg-black/50 backdrop-blur-md text-[10px] font-bold uppercase tracking-wider text-white">
                                            {{ $event->category->name }}
                                        </span>
                                    @endif
                                </div>

                                <div class="px-2 pb-2 space-y-3">
                                    <div class="flex items-center justify-between text-xs text-red-400 font-semibold uppercase tracking-wider">
                                        <span>{{ $event->city }}</span>
                                        <span>•</span>
                                        <span>{{ optional($event->start_at)->format('d M Y') ?? 'TBA' }}</span>
                                    </div>

                                    <h3 class="text-xl font-bold text-white line-clamp-1 group-hover:text-red-400 transition-colors">
                                        {{ $event->title }}
                                    </h3>

                                    <p class="text-sm text-zinc-400 line-clamp-2 leading-relaxed">
                                        {{ $event->description }}
                                    </p>

                                    <div class="pt-3 border-t border-zinc-800/40 flex items-center justify-between">
                                        @if($event->ticketTypes->count() > 0)
                                            <p class="text-white font-extrabold text-sm">
                                                Mulai <span class="text-red-400">Rp{{ number_format($event->ticketTypes->min('price'), 0, ',', '.') }}</span>
                                            </p>
                                        @else
                                            <p class="text-zinc-500 text-xs font-semibold">TBA</p>
                                        @endif

                                        <div class="flex items-center gap-1.5 text-xs text-red-400 font-bold uppercase tracking-wider group-hover:gap-2.5 transition-all duration-300">
                                            Tiket
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @empty
                            <div class="md:col-span-3 rounded-3xl border border-dashed border-zinc-800 bg-zinc-900/20 backdrop-blur-sm p-16 text-center">
                                <p class="text-4xl mb-4">🎉</p>
                                <h3 class="text-lg font-bold text-zinc-400">Belum ada event featured.</h3>
                                <p class="mt-2 text-zinc-500 text-sm">Silakan buat event atau jalankan seeder terlebih dahulu.</p>
                            </div>
                        @endforelse
                    </div>

                    <div class="text-center pt-4">
                        <a href="{{ route('events.index') }}"
                           class="btn-premium-secondary inline-flex items-center gap-2 px-8 py-3.5 font-bold text-sm">
                            Lihat Semua Event
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- CATEGORIES SECTION -->
        <section class="px-6 py-24 border-b border-zinc-900 bg-zinc-950/10">
            <div class="max-w-7xl mx-auto">
                <div class="space-y-16">
                    <div class="text-center space-y-4">
                        <p class="text-rose-450 font-bold uppercase tracking-[0.2em] text-xs">KATEGORI</p>
                        <h2 class="text-4xl lg:text-5xl font-extrabold text-white tracking-tight">Kategori Event</h2>
                        <p class="text-zinc-400 text-base max-w-xl mx-auto">Cari event favoritmu berdasarkan kategori musik, teknologi, seni, dan lainnya.</p>
                    </div>

                    <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-5">
                        @foreach ($categories as $category)
                            <a href="{{ route('events.index', ['category' => $category->slug]) }}"
                               class="group relative rounded-2xl border border-zinc-800/60 bg-zinc-900/30 hover:bg-zinc-900/80 px-6 py-6 transition-all duration-300 hover:border-red-500/30 overflow-hidden shadow-lg">
                                <div class="absolute inset-0 bg-gradient-to-r from-red-650/5 to-rose-600/5 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                                <div class="relative flex items-center justify-between">
                                    <div>
                                        <h3 class="font-bold text-white group-hover:text-red-400 transition-colors text-base">{{ $category->name }}</h3>
                                        <p class="text-xs text-zinc-500 mt-1.5 font-medium">{{ $category->events_count }} events tersedia</p>
                                    </div>
                                    <div class="w-8 h-8 rounded-lg bg-zinc-800/40 group-hover:bg-red-650/10 flex items-center justify-center transition">
                                        <svg xmlns="http://www.w3.org/2500/svg" class="w-4 h-4 text-zinc-400 group-hover:text-red-400 transition-all group-hover:translate-x-0.5 duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                        </svg>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>

        <!-- HOW IT WORKS / ABOUT SECTION -->
        <section id="about" class="px-6 py-28 relative overflow-hidden border-b border-zinc-900">
            <div class="absolute inset-0 pointer-events-none">
                <div class="absolute top-1/2 left-0 w-72 h-72 bg-red-750/3 rounded-full blur-[100px] -translate-y-1/2"></div>
                <div class="absolute top-1/2 right-0 w-72 h-72 bg-red-650/3 rounded-full blur-[100px] -translate-y-1/2"></div>
            </div>

            <div class="max-w-7xl mx-auto relative">
                <div class="text-center space-y-4 mb-20">
                    <p class="text-red-450 font-bold uppercase tracking-[0.2em] text-xs">MENGAPA VOKATIF</p>
                    <h2 class="text-4xl lg:text-5xl font-extrabold text-white tracking-tight">System Core & Value</h2>
                    <p class="text-zinc-400 text-base max-w-xl mx-auto">
                        Membangun infrastruktur event management terpercaya dengan integrasi validasi offline & online.
                    </p>
                </div>

                <div class="grid md:grid-cols-3 gap-8 mb-20">
                    <div class="rounded-2xl border border-zinc-800/60 bg-zinc-900/20 p-8 space-y-5 hover:border-red-500/20 transition-all duration-300 shadow-md">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-red-650 to-rose-600 flex items-center justify-center text-xl shadow-md shadow-red-950/20">
                            🎫
                        </div>
                        <h3 class="text-lg font-bold text-white">E-Ticket Mudah</h3>
                        <p class="text-zinc-400 text-sm leading-relaxed font-normal">
                            Proses checkout aman dengan data terenkripsi. Ticket instan tersimpan langsung dalam akun e-ticket portal Anda.
                        </p>
                    </div>

                    <div class="rounded-2xl border border-zinc-800/60 bg-zinc-900/20 p-8 space-y-5 hover:border-rose-500/20 transition-all duration-300 shadow-md">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-rose-500 to-rose-600 flex items-center justify-center text-xl shadow-md shadow-rose-950/20">
                            📱
                        </div>
                        <h3 class="text-lg font-bold text-white">Validation Check-in</h3>
                        <p class="text-zinc-400 text-sm leading-relaxed font-normal">
                            Teknologi validasi check-in real-time menggunakan ticket code direct string scanner untuk meminimalisir delay antrean.
                        </p>
                    </div>

                    <div class="rounded-2xl border border-zinc-800/60 bg-zinc-900/20 p-8 space-y-5 hover:border-red-500/20 transition-all duration-300 shadow-md">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-red-600 to-red-700 flex items-center justify-center text-xl shadow-md shadow-red-950/20">
                            📊
                        </div>
                        <h3 class="text-lg font-bold text-white">Dashboard Analytics</h3>
                        <p class="text-zinc-400 text-sm leading-relaxed font-normal">
                            Akses monitoring komparasi kuota tiket terjual, live check-ins, dan status orders yang mudah dipahami bagi organizer.
                        </p>
                    </div>
                </div>

                <!-- STEPS -->
                <div class="premium-glass rounded-3xl p-10 shadow-2xl">
                    <h3 class="text-xl font-bold mb-10 text-center text-white">Alur Penggunaan Platform</h3>
                    <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-8">
                        @foreach([
                            ['01', 'Daftar Akun', 'Registrasi gratis dalam hitungan detik sebagai peserta atau organizer.'],
                            ['02', 'Pilih Event', 'Telusuri event terbaik di kotamu melalui filter kategori.'],
                            ['03', 'Checkout Aman', 'Simulasikan pembayaran tiket dengan aman dan langsung.'],
                            ['04', 'Check-in QR', 'Tunjukkan QR tiket di venue dan check-in langsung oleh petugas.'],
                        ] as $step)
                            <div class="text-center space-y-3 relative group">
                                <div class="w-11 h-11 rounded-xl bg-zinc-900 border border-zinc-800 text-red-400 flex items-center justify-center font-bold text-sm mx-auto shadow-md group-hover:border-red-500/30 transition">
                                    {{ $step[0] }}
                                </div>
                                <h4 class="font-bold text-white text-base pt-1">{{ $step[1] }}</h4>
                                <p class="text-zinc-400 text-xs leading-relaxed max-w-[200px] mx-auto">{{ $step[2] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>

        <!-- CONTACT SECTION -->
        <section id="contact" class="px-6 py-24">
            <div class="max-w-7xl mx-auto">
                <div class="grid lg:grid-cols-12 gap-16 items-start">

                    <!-- LEFT -->
                    <div class="lg:col-span-5 space-y-8">
                        <div class="space-y-4">
                            <p class="text-red-400 font-bold uppercase tracking-[0.2em] text-xs">HUBUNGI KAMI</p>
                            <h2 class="text-4xl lg:text-5xl font-extrabold text-white tracking-tight">Mari Berkolaborasi</h2>
                            <p class="text-zinc-400 text-sm leading-relaxed font-normal">
                                Punya pertanyaan seputar cara mengintegrasikan event Anda? Atau mengalami masalah terkait transaksi? Tim support Vokatif siap melayani kebutuhan Anda.
                            </p>
                        </div>

                        <div class="space-y-3 max-w-sm">
                            <div class="flex items-center gap-4 p-4 rounded-2xl border border-zinc-900 bg-zinc-900/30 shadow-md">
                                <div class="w-10 h-10 rounded-xl bg-red-650/10 flex items-center justify-center flex-shrink-0 border border-red-500/10">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                </div>
                                <div>
                                    <p class="text-[10px] text-zinc-500 uppercase font-bold tracking-wider">Email</p>
                                    <p class="font-bold text-sm text-zinc-200">adminvokatif@gmail.com</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-4 p-4 rounded-2xl border border-zinc-900 bg-zinc-900/30 shadow-md">
                                <div class="w-10 h-10 rounded-xl bg-rose-650/10 flex items-center justify-center flex-shrink-0 border border-rose-500/10">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5 text-rose-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                </div>
                                <div>
                                    <p class="text-[10px] text-zinc-500 uppercase font-bold tracking-wider">WhatsApp</p>
                                    <p class="font-bold text-sm text-zinc-200">+62 895 2654 9546</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- RIGHT: CONTACT FORM (FINTECH STYLE INPUT PANEL) -->
                    <div class="lg:col-span-7 premium-glass rounded-3xl p-8 lg:p-10 shadow-2xl border border-white/5" id="contact-form-wrapper">
                        <h3 class="text-xl font-bold text-white mb-6">Kirim Pesan</h3>
                        <form id="contact-form" class="space-y-5">
                            @csrf
                            <div class="grid sm:grid-cols-2 gap-5">
                                <div>
                                    <label class="block text-xs font-semibold text-zinc-400 uppercase tracking-wider mb-2.5">Nama Lengkap</label>
                                    <input id="cf-name" name="name" type="text" placeholder="Nama kamu" required
                                        class="w-full rounded-xl px-4 py-3 bg-zinc-950/60 border border-zinc-800 text-white placeholder-zinc-650 focus:outline-none focus:border-red-500/60 focus:bg-zinc-950 transition-all text-sm">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-zinc-400 uppercase tracking-wider mb-2.5">Email</label>
                                    <input id="cf-email" name="email" type="email" placeholder="email@kamu.com" required
                                        class="w-full rounded-xl px-4 py-3 bg-zinc-950/60 border border-zinc-800 text-white placeholder-zinc-650 focus:outline-none focus:border-red-500/60 focus:bg-zinc-950 transition-all text-sm">
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-zinc-400 uppercase tracking-wider mb-2.5">Subjek</label>
                                <select id="cf-subject" name="subject" required
                                    class="w-full rounded-xl px-4 py-3 bg-zinc-950/60 border border-zinc-800 text-zinc-400 focus:outline-none focus:border-red-500/60 focus:bg-zinc-950 transition-all text-sm">
                                    <option value="">Pilih subjek...</option>
                                    <option value="Saya ingin jadi Organizer">Saya ingin jadi Organizer</option>
                                    <option value="Masalah pembelian tiket">Masalah pembelian tiket</option>
                                    <option value="Partnership / Sponsorship">Partnership / Sponsorship</option>
                                    <option value="Lainnya">Lainnya</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-zinc-400 uppercase tracking-wider mb-2.5">Pesan</label>
                                <textarea id="cf-message" name="message" rows="4" placeholder="Tuliskan detail pertanyaan atau kolaborasi di sini..." required
                                    class="w-full rounded-xl px-4 py-3 bg-zinc-950/60 border border-zinc-800 text-white placeholder-zinc-650 focus:outline-none focus:border-red-500/60 focus:bg-zinc-950 transition-all text-sm resize-none"></textarea>
                            </div>
                            <p id="cf-error" class="text-red-400 text-xs hidden"></p>
                            <button type="submit" id="cf-submit"
                                class="w-full py-4 rounded-xl bg-red-600 hover:bg-red-500 text-white font-bold text-sm transition-all duration-350 hover:shadow-lg hover:shadow-red-900/20 inline-flex items-center justify-center gap-2 cursor-pointer shadow-md">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                                Kirim Pesan
                            </button>
                        </form>
                    </div>

                </div>
            </div>
        </section>

    </main>

    <!-- SUCCESS POPUP -->
    <div id="success-popup" class="fixed inset-0 z-[999] flex items-center justify-center hidden">
        <div class="absolute inset-0 bg-black/70 backdrop-blur-md" onclick="document.getElementById('success-popup').classList.add('hidden')"></div>
        <div class="relative z-10 max-w-sm w-full mx-4 rounded-3xl p-10 text-center bg-zinc-950 border border-red-500/20 shadow-2xl">
            <div class="w-16 h-16 mx-auto mb-6 rounded-2xl bg-gradient-to-br from-red-500/20 to-rose-500/20 flex items-center justify-center border border-red-500/20">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <h3 class="text-xl font-bold mb-2 text-white">Pesan Terkirim</h3>
            <p class="text-zinc-400 text-xs leading-relaxed mb-6">Terima kasih telah menghubungi Vokatif. Tim representatif kami akan segera membalas pesan Anda.</p>
            <button onclick="document.getElementById('success-popup').classList.add('hidden')"
                class="w-full py-3.5 rounded-xl bg-zinc-900 hover:bg-zinc-850 text-white font-bold text-xs transition border border-zinc-800">
                Tutup
            </button>
        </div>
    </div>

    <!-- FOOTER -->
    @include('partials.footer')

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- CONTACT FORM AJAX -->
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('contact-form');
        if (!form) return;

        form.addEventListener('submit', async function (e) {
            e.preventDefault();
            const btn = document.getElementById('cf-submit');
            const errEl = document.getElementById('cf-error');
            errEl.classList.add('hidden');
            btn.disabled = true;
            btn.textContent = 'Mengirim...';

            const body = new FormData();
            body.append('name',    document.getElementById('cf-name').value);
            body.append('email',   document.getElementById('cf-email').value);
            body.append('subject', document.getElementById('cf-subject').value);
            body.append('message', document.getElementById('cf-message').value);

            const token = document.querySelector('meta[name="csrf-token"]')?.content
                       || document.querySelector('input[name="_token"]')?.value
                       || '';
            if (token) body.append('_token', token);

            try {
                const res = await fetch('/contact', { method: 'POST', body });
                const data = await res.json();

                if (data.success) {
                    form.reset();
                    document.getElementById('success-popup').classList.remove('hidden');
                } else {
                    errEl.textContent = data.message || 'Terjadi kesalahan, silakan coba kembali.';
                    errEl.classList.remove('hidden');
                }
            } catch (err) {
                errEl.textContent = 'Gagal mengirim pesan. Silakan periksa koneksi internet Anda.';
                errEl.classList.remove('hidden');
            }

            btn.disabled = false;
            btn.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg> Kirim Pesan`;
        });

        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                const target = document.querySelector(this.getAttribute('href'));
                if (target) { e.preventDefault(); target.scrollIntoView({ behavior: 'smooth' }); }
            });
        });
    });
    </script>
</body>
</html>
