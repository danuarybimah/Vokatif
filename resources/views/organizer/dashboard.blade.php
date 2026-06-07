@extends('layouts.dashboard')

@section('content')
    <div class="space-y-10">

        <!-- HEADER -->
<div class="flex items-center justify-between flex-wrap gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-bold text-white">Organizer Dashboard</h1>
        <p class="text-zinc-500 text-sm mt-1">Kelola event, tiket, dan check-in peserta.</p>
    </div>
    <div class="flex items-center gap-2 flex-wrap">
        <a href="/organizer/events" class="btn btn-primary text-sm">Manage Events</a>
        <a href="/organizer/events/create" class="btn text-sm" style="background:#1A0808; color:#F87171; border:1px solid rgba(220,38,38,0.3);">+ New Event</a>
        <a href="/organizer/checkin-scanner" class="btn text-sm" style="background:rgba(220,38,38,0.1); color:#FCA5A5; border:1px solid rgba(220,38,38,0.2);">QR Scanner</a>
    </div>
</div>

<!-- STATS -->
<div class="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
    <div class="stat-card">
        <p class="stat-label">Total Events</p>
        <p class="stat-value">{{ $totalEvents }}</p>
    </div>
    <div class="stat-card">
        <p class="stat-label">Published</p>
        <p class="stat-value text-emerald-400">{{ $publishedEvents }}</p>
    </div>
    <div class="stat-card">
        <p class="stat-label">Tickets Sold</p>
        <p class="stat-value">{{ $totalTicketsSold }}</p>
    </div>
    <div class="stat-card">
        <p class="stat-label">Check-ins</p>
        <p class="stat-value">{{ $totalCheckins }}</p>
    </div>
    <div class="stat-card lg:col-span-1" style="border-color:rgba(220,38,38,0.2);">
        <p class="stat-label">Revenue</p>
        <p class="stat-value text-red-400" style="font-size:18px;">
            Rp{{ number_format($totalRevenue, 0, ',', '.') }}
        </p>
    </div>
</div>

        <!-- ANALYTICS -->
        <div class="grid lg:grid-cols-2 gap-8">

            <!-- REVENUE -->
            <div class="glass rounded-[32px] p-8">

                <div class="mb-8">

                    <h2 class="text-3xl font-bold">
                        Revenue Analytics
                    </h2>

                    <p class="text-zinc-400 mt-2">
                        Monthly revenue realtime
                    </p>

                </div>

                <canvas id="revenueChart" height="120"></canvas>

            </div>

            <!-- TICKETS -->
            <div class="glass rounded-[32px] p-8">

                <div class="mb-8">

                    <h2 class="text-3xl font-bold">
                        Ticket Analytics
                    </h2>

                    <p class="text-zinc-400 mt-2">
                        Ticket sold per month
                    </p>

                </div>

                <canvas id="ticketChart" height="120"></canvas>

            </div>

        </div>

        <!-- EVENT CARDS -->
        <div>

            <div class="flex items-center justify-between mb-6">

                <div>

                    <h2 class="text-3xl font-bold">
                        Managed Events
                    </h2>

                    <p class="text-zinc-400">
                        Event yang sedang dikelola organizer.
                    </p>

                </div>

            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">

                @foreach ($events as $event)
                    <div class="glass rounded-3xl overflow-hidden">

                        @if($event->cover_image)
                            <div class="h-48 overflow-hidden">
                                <img src="{{ asset('storage/' . $event->cover_image) }}"
                                     alt="{{ $event->title }}"
                                     class="w-full h-full object-cover">
                            </div>
                        @else
                            <div class="h-48 gradient-card"></div>
                        @endif

                        <div class="p-6">

                            <div class="flex items-center justify-between mb-4">

                                <span class="px-4 py-2 rounded-full bg-rose-500/20 text-rose-400 text-sm font-bold">
                                    {{ $event->category->name }}
                                </span>

                                <span class="text-zinc-400 text-sm">
                                    {{ $event->ticketTypes->count() }} ticket type
                                </span>

                            </div>

                            <h2 class="text-2xl font-bold mb-3">
                                {{ $event->title }}
                            </h2>

                            <p class="text-zinc-400 line-clamp-2">
                                {{ $event->description }}
                            </p>

                            <div class="mt-6 flex items-center justify-between">

                                <div>

                                    <p class="text-zinc-500 text-sm">
                                        Status
                                    </p>

                                    <h3
                                        class="
                                    font-bold

                                    {{ $event->status === 'published' ? 'text-emerald-400' : 'text-yellow-400' }}
                                ">
                                        {{ ucfirst($event->status) }}
                                    </h3>

                                </div>

                                <a href="/organizer/events/{{ $event->id }}/edit"
                                    class="px-5 py-3 rounded-2xl bg-red-500/20 text-red-400 font-bold hover:bg-red-500/30 transition">

                                    Manage

                                </a>

                            </div>

                        </div>

                    </div>
                @endforeach

            </div>

        </div>

        <!-- RECENT ORDERS -->
        <div class="glass rounded-3xl p-6">

            <div class="mb-6">

                <h2 class="text-3xl font-bold">
                    Latest Orders
                </h2>

                <p class="text-zinc-400">
                    Transaksi terbaru event organizer.
                </p>

            </div>

            <div class="overflow-x-auto">

                <table class="w-full">

                    <thead>

                        <tr class="border-b border-zinc-800 text-left">

                            <th class="pb-4">User</th>
                            <th class="pb-4">Event</th>
                            <th class="pb-4">Payment</th>
                            <th class="pb-4">Status</th>

                        </tr>

                    </thead>

                    <tbody>

                        @foreach ($latestOrders as $order)
                            <tr class="border-b border-zinc-800/60">

                                <td class="py-5">
                                    {{ $order->user->name }}
                                </td>

                                <td class="py-5">
                                    {{ $order->event->title }}
                                </td>

                                <td class="py-5 text-red-400 font-bold">
                                    Rp{{ number_format($order->total_amount, 0, ',', '.') }}
                                </td>

                                <td class="py-5">

                                    <span
                                        class="
                                    px-4 py-2 rounded-full text-sm font-bold

                                    {{ $order->payment_status === 'paid'
                                        ? 'bg-emerald-500/20 text-emerald-400'
                                        : 'bg-yellow-500/20 text-yellow-400' }}
                                ">
                                        {{ $order->payment_status }}
                                    </span>

                                </td>

                            </tr>
                        @endforeach

                    </tbody>

                </table>

            </div>

        </div>

        <!-- CHECKIN -->
        <div class="glass rounded-3xl p-6">

            <div class="mb-6">

                <h2 class="text-3xl font-bold">
                    Latest Check-ins
                </h2>

                <p class="text-zinc-400">
                    Aktivitas scan QR terbaru.
                </p>

            </div>

            <div class="space-y-4">

                @foreach ($latestCheckins as $checkin)
                    <div class="glass rounded-2xl p-5 flex items-center justify-between">

                        <div>

                            <h2 class="text-xl font-bold">
                                {{ $checkin->ticket->user->name }}
                            </h2>

                            <p class="text-zinc-400">
                                {{ $checkin->event->title }}
                            </p>

                        </div>

                        <div class="text-right">

                            <p class="text-emerald-400 font-bold">
                                {{ ucfirst($checkin->status) }}
                            </p>

                            <p class="text-zinc-500 text-sm">
                                {{ $checkin->created_at->format('d M Y H:i') }}
                            </p>

                        </div>

                    </div>
                @endforeach

            </div>

        </div>

    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            /*
            |--------------------------------------------------------------------------
            | DATA
            |--------------------------------------------------------------------------
            */

            const months = JSON.parse(
                '@json($months)'
            );

            const monthlyRevenue = JSON.parse(
                '@json($monthlyRevenue)'
            );

            const monthlyTickets = JSON.parse(
                '@json($monthlyTickets)'
            );


            /*
            |--------------------------------------------------------------------------
            | REVENUE CHART
            |--------------------------------------------------------------------------
            */

            const revenueCanvas = document.getElementById('revenueChart');

            if (revenueCanvas) {
                new Chart(revenueCanvas, {

                    type: 'line',

                    data: {

                        labels: months,

                        datasets: [{

                            label: 'Revenue',

                            data: monthlyRevenue,

                            borderColor: '#DC2626',

                            backgroundColor: 'rgba(220,38,38,0.2)',

                            fill: true,

                            tension: 0.4

                        }]

                    },

                    options: {

                        responsive: true,

                        plugins: {

                            legend: {

                                labels: {
                                    color: '#ffffff'
                                }

                            }

                        },

                        scales: {

                            x: {

                                ticks: {
                                    color: '#94a3b8'
                                }

                            },

                            y: {

                                ticks: {
                                    color: '#94a3b8'
                                }

                            }

                        }

                    }

                });
            }


            /*
            |--------------------------------------------------------------------------
            | TICKET CHART
            |--------------------------------------------------------------------------
            */

            const ticketCanvas = document.getElementById('ticketChart');

            if (ticketCanvas) {
                new Chart(ticketCanvas, {

                    type: 'bar',

                    data: {

                        labels: months,

                        datasets: [{

                            label: 'Tickets Sold',

                            data: monthlyTickets,

                            backgroundColor: '#F87171',

                            borderRadius: 12

                        }]

                    },

                    options: {

                        responsive: true,

                        plugins: {

                            legend: {

                                labels: {
                                    color: '#ffffff'
                                }

                            }

                        },

                        scales: {

                            x: {

                                ticks: {
                                    color: '#94a3b8'
                                }

                            },

                            y: {

                                ticks: {
                                    color: '#94a3b8'
                                }

                            }

                        }

                    }

                });
            }

        });
    </script>
@endpush
