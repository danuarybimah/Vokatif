@extends('layouts.dashboard')

@section('content')
    <div class="space-y-10">

        <!-- HEADER -->
        <div>
            <h1 class="text-5xl font-extrabold">Analytics</h1>
            <p class="text-zinc-400 mt-3">Performa event dan penjualan tiket kamu.</p>
        </div>

        <!-- STATS -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="stat-card border-l-2 border-l-red-600">
                <p class="stat-label">Total Revenue</p>
                <p class="stat-value text-red-400" style="font-size:20px;">
                    Rp{{ number_format($totalRevenue, 0, ',', '.') }}
                </p>
            </div>
            <div class="stat-card">
                <p class="stat-label">Total Orders</p>
                <p class="stat-value">{{ $totalOrders }}</p>
                <p class="text-xs text-zinc-400 mt-1">
                    <span class="text-emerald-450 font-semibold">{{ $paidOrders }} paid</span>
                    <span class="mx-1">·</span>
                    <span class="text-yellow-450 font-semibold">{{ $pendingOrders }} pending</span>
                </p>
            </div>
            <div class="stat-card">
                <p class="stat-label">Total Events</p>
                <p class="stat-value">{{ $totalEvents }}</p>
                <p class="text-xs text-emerald-450 mt-1 font-semibold">{{ $publishedEvents }} published</p>
            </div>
            <div class="stat-card">
                <p class="stat-label">Conversion Rate</p>
                <p class="stat-value text-rose-400">
                    {{ $totalOrders > 0 ? round(($paidOrders / $totalOrders) * 100) : 0 }}%
                </p>
                <p class="text-xs text-zinc-400 mt-1">paid / total orders</p>
            </div>
        </div>

    <!-- CHARTS -->
    <div class="grid lg:grid-cols-2 gap-6">

        <div class="glass rounded-3xl p-8">
            <h2 class="text-2xl font-bold mb-2">Revenue per Bulan</h2>
            <p class="text-zinc-400 text-sm mb-6">12 bulan terakhir</p>
            <canvas id="revenueChart" height="130"></canvas>
        </div>

        <div class="glass rounded-3xl p-8">
            <h2 class="text-2xl font-bold mb-2">Tiket Terjual per Bulan</h2>
            <p class="text-zinc-400 text-sm mb-6">12 bulan terakhir</p>
            <canvas id="ticketsChart" height="130"></canvas>
        </div>

    </div>

    <!-- TOP EVENTS -->
    <div class="glass rounded-3xl p-8">
        <h2 class="text-2xl font-bold mb-6">Top 5 Events by Revenue</h2>
        <div class="space-y-4">
            @forelse($topEvents as $i => $event)
                <div class="glass rounded-2xl p-5 flex items-center justify-between">
                    <div class="flex items-center gap-5">
                        <span
                            class="w-10 h-10 rounded-full gradient-card flex items-center justify-center font-bold text-lg">
                            {{ $i + 1 }}
                        </span>
                        <div>
                            <h3 class="font-bold text-lg">{{ $event->title }}</h3>
                            <p class="text-zinc-400 text-sm">{{ $event->orders_count }} orders · {{ $event->city }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-rose-400 font-bold text-xl">
                            Rp{{ number_format($event->revenue ?? 0, 0, ',', '.') }}
                        </p>
                    </div>
                </div>
            @empty
                <div class="text-center text-zinc-400 py-8">Belum ada data event.</div>
            @endforelse
        </div>
    </div>

    </div>
@endsection

@push('scripts')
    <script>
        const months = @json($months);
        const chartDefaults = {
            responsive: true,
            plugins: {
                legend: {
                    labels: {
                        color: '#fff'
                    }
                }
            },
            scales: {
                x: {
                    ticks: {
                        color: '#94a3b8'
                    },
                    grid: {
                        color: 'rgba(255,255,255,0.05)'
                    }
                },
                y: {
                    ticks: {
                        color: '#94a3b8'
                    },
                    grid: {
                        color: 'rgba(255,255,255,0.05)'
                    }
                }
            }
        };

        new Chart(document.getElementById('revenueChart'), {
            type: 'line',
            data: {
                labels: months,
                datasets: [{
                    label: 'Revenue',
                    data: @json($monthlyRevenue),
                    borderColor: '#E11D48',
                    backgroundColor: 'rgba(225,29,72,0.15)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: chartDefaults
        });

        new Chart(document.getElementById('ticketsChart'), {
            type: 'bar',
            data: {
                labels: months,
                datasets: [{
                    label: 'Tickets Sold',
                    data: @json($monthlyTickets),
                    backgroundColor: '#F87171',
                    borderRadius: 8
                }]
            },
            options: chartDefaults
        });
    </script>
@endpush
