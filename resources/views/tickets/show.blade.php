@extends('layouts.user')

@section('content')

<!-- PRINT STYLING -->
<style>
/* Base Styles to enforce stable widths on screen and print */
.printable-ticket-card .ticket-details-side {
    width: 65% !important;
    flex: 0 0 65% !important;
}

.printable-ticket-card .ticket-qr-side {
    width: 35% !important;
    flex: 0 0 35% !important;
}

@media print {
    /* Hide layout parts */
    nav, footer, header, aside, .topbar, .sidebar, .no-print, .action-buttons-container {
        display: none !important;
    }
    
    body, html {
        background: #ffffff !important;
        color: #000000 !important;
        margin: 0 !important;
        padding: 0 !important;
    }

    .main-content-wrapper {
        padding: 0 !important;
        margin: 0 !important;
    }

    /* Reset background colors on print */
    .printable-ticket-card {
        background: #ffffff !important;
        border: 2px solid #e4e4e7 !important;
        color: #000000 !important;
        box-shadow: none !important;
        margin: 20mm auto !important;
        width: 100% !important;
        max-width: 700px !important;
        display: flex !important;
        flex-direction: row !important;
        page-break-inside: avoid;
    }

    .printable-ticket-card h2, 
    .printable-ticket-card p, 
    .printable-ticket-card span, 
    .printable-ticket-card div {
        color: #000000 !important;
    }

    .printable-ticket-card .ticket-details-side {
        width: 65% !important;
        flex: 0 0 65% !important;
    }

    .printable-ticket-card .ticket-qr-side {
        width: 35% !important;
        flex: 0 0 35% !important;
        background: #fafafa !important;
        border-left: 2px dashed #e4e4e7 !important;
    }

    /* Muted labels */
    .printable-ticket-card p.text-zinc-550 {
        color: #71717a !important;
    }

    /* Red colors */
    .printable-ticket-card .text-red-400,
    .printable-ticket-card .text-red-500 {
        color: #dc2626 !important;
    }

    /* Dotted line holes matching print background */
    .printable-ticket-card .bg-punch-hole {
        background: #ffffff !important;
        border: 1px solid #e4e4e7 !important;
    }

    @page {
        size: portrait;
        margin: 10mm;
    }
}
</style>

<div class="max-w-3xl mx-auto space-y-8 main-content-wrapper">

    <!-- Header Actions (Hidden in Print) -->
    <div class="flex items-center justify-between gap-4 no-print">
        <div>
            <h1 class="text-4xl font-extrabold text-white tracking-tight">Digital Ticket</h1>
            <p class="text-zinc-500 text-sm mt-1">Tunjukkan kode QR di bawah ini kepada panitia event saat check-in.</p>
        </div>

        <div>
            <a href="/my-tickets"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-zinc-900 border border-zinc-800 text-zinc-400 hover:text-white hover:border-zinc-700 transition text-xs font-bold uppercase tracking-wider">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Daftar Tiket
            </a>
        </div>
    </div>

    <!-- TICKET PASS CARD -->
    <div class="printable-ticket-card bg-zinc-900 border border-zinc-800/80 rounded-3xl overflow-hidden shadow-2xl flex flex-col md:flex-row relative">
        
        <!-- Left Section: Details -->
        <div class="ticket-details-side p-8 space-y-6">
            <!-- Event Title -->
            <div>
                <span class="text-xs font-bold text-red-500 uppercase tracking-widest">Vokatif Ticket Pass</span>
                <h2 class="text-2xl font-black text-white mt-1 leading-tight">{{ $ticket->event->title }}</h2>
            </div>
            
            <!-- Grid details -->
            <div class="grid grid-cols-2 gap-y-6 gap-x-4 pt-2">
                <div>
                    <p class="text-[10px] text-zinc-550 font-bold uppercase tracking-wider">Nama Peserta</p>
                    <p class="text-zinc-200 font-bold text-sm leading-tight mt-1">{{ $ticket->user->name }}</p>
                </div>
                <div>
                    <p class="text-[10px] text-zinc-550 font-bold uppercase tracking-wider">Kategori Tiket</p>
                    <p class="text-red-400 font-bold text-sm leading-tight mt-1">{{ $ticket->ticketType->name }}</p>
                </div>
                <div>
                    <p class="text-[10px] text-zinc-550 font-bold uppercase tracking-wider">Tanggal &amp; Waktu</p>
                    <p class="text-zinc-200 font-bold text-sm leading-tight mt-1">{{ $ticket->event->start_at->format('d M Y, H:i') }} WIB</p>
                </div>
                <div>
                    <p class="text-[10px] text-zinc-550 font-bold uppercase tracking-wider">Lokasi Venue</p>
                    <p class="text-zinc-200 font-bold text-sm leading-tight mt-1">{{ $ticket->event->location }}, {{ $ticket->event->city }}</p>
                </div>
                <div>
                    <p class="text-[10px] text-zinc-550 font-bold uppercase tracking-wider">Kode Tiket</p>
                    <p class="font-mono text-zinc-300 font-bold text-sm tracking-wider leading-none mt-1">{{ $ticket->ticket_code }}</p>
                </div>
                <div>
                    <p class="text-[10px] text-zinc-550 font-bold uppercase tracking-wider">Status Tiket</p>
                    <div class="mt-1">
                        @if($ticket->status == 'active')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-[10px] font-bold tracking-wider uppercase">VALID</span>
                        @elseif($ticket->status == 'used')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full bg-red-500/15 border border-red-500/25 text-red-400 text-[10px] font-bold tracking-wider uppercase">USED</span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full bg-yellow-500/10 border border-yellow-500/20 text-yellow-455 text-[10px] font-bold tracking-wider uppercase">INVALID</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Dotted Divider Line with punch holes -->
        <div class="hidden md:block w-px border-l-2 border-dashed border-zinc-800/80 relative">
            <div class="bg-punch-hole absolute -top-3.5 -left-3.5 w-7 h-7 rounded-full bg-[#09090b] border border-zinc-800/80"></div>
            <div class="bg-punch-hole absolute -bottom-3.5 -left-3.5 w-7 h-7 rounded-full bg-[#09090b] border border-zinc-800/80"></div>
        </div>
        
        <!-- Right Section: QR Code -->
        <div class="ticket-qr-side bg-zinc-950 p-8 flex flex-col items-center justify-center text-center flex-shrink-0">
            <div class="bg-white rounded-2xl p-4 shadow-xl">
                {!!
                    QrCode::format('svg')
                        ->size(130)
                        ->margin(1)
                        ->generate($ticket->ticket_code)
                !!}
            </div>
            <p class="text-zinc-500 text-[10px] uppercase font-bold tracking-widest mt-4">Gate Pass QR</p>
            <p class="font-mono text-zinc-400 text-xs mt-1 truncate max-w-[180px]">{{ $ticket->ticket_code }}</p>
        </div>
    </div>

    <!-- Print CTA button (Hidden in Print) -->
    <div class="flex justify-center pt-2 no-print">
        <button onclick="window.print()"
                class="px-8 py-4 bg-red-600 hover:bg-red-500 text-white font-bold rounded-2xl text-sm uppercase tracking-wider flex items-center justify-center gap-2 cursor-pointer shadow-lg shadow-red-950/20 hover:shadow-red-800/30 transition duration-200">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
            </svg>
            Cetak / Simpan Tiket
        </button>
    </div>

</div>

@endsection
