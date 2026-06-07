@extends('layouts.dashboard')

@section('content')
    <div class="flex flex-col gap-8">

        <div>
            <h1 class="text-5xl font-black">Analytics</h1>
            <p class="text-zinc-400 mt-3">Overview performa seluruh platform Vokatif.</p>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6">
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
                <p class="stat-label">Total Users</p>
                <p class="stat-value">{{ $totalUsers }}</p>
            </div>
            <div class="stat-card">
                <p class="stat-label">Total Events</p>
                <p class="stat-value">{{ $totalEvents }}</p>
            </div>
            <div class="stat-card">
                <p class="stat-label">Total Organizers</p>
                <p class="stat-value text-rose-450">{{ $totalOrganizers }}</p>
                <p class="text-xs text-zinc-400 mt-1">active organizers</p>
            </div>
        </div>

        <div class="grid lg:grid-cols-2 gap-6">
            <div class="glass rounded-3xl p-8">
                <h2 class="text-2xl font-bold mb-2">Revenue per Bulan</h2>
                <p class="text-zinc-400 text-sm mb-6">12 bulan terakhir</p>
                <canvas id="revenueChart" height="130"></canvas>
            </div>

            <div class="glass rounded-3xl p-8">
                <h2 class="text-2xl font-bold mb-2">Orders per Bulan</h2>
                <p class="text-zinc-400 text-sm mb-6">12 bulan terakhir</p>
                <canvas id="ordersChart" height="130"></canvas>
            </div>

            <div class="glass rounded-3xl p-8 lg:col-span-2">
                <h2 class="text-2xl font-bold mb-2">Registrasi User per Bulan</h2>
                <p class="text-zinc-400 text-sm mb-6">12 bulan terakhir</p>
                <canvas id="usersChart" height="80"></canvas>
            </div>
        </div>

        <div class="glass rounded-3xl p-8">
            <h2 class="text-2xl font-bold mb-6">Top 5 Events by Revenue</h2>
            <div class="space-y-4">
                @foreach ($topEvents as $i => $event)
                    <div class="glass rounded-2xl p-5 flex items-center justify-between">
                        <div class="flex items-center gap-5">
                            <span class="w-10 h-10 rounded-full bg-red-600 flex items-center justify-center font-bold text-lg">
                                {{ $i + 1 }}
                            </span>
                            <div>
                                <h3 class="font-bold text-lg">{{ $event->title }}</h3>
                                <p class="text-zinc-400 text-sm">{{ $event->orders_count }} orders</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-red-400 font-extrabold text-xl">
                                Rp{{ number_format($event->revenue ?? 0, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

    </div> @endsection

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
                    borderColor: '#DC2626',
                    backgroundColor: 'rgba(220,38,38,0.15)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: chartDefaults
        });

        new Chart(document.getElementById('ordersChart'), {
            type: 'bar',
            data: {
                labels: months,
                datasets: [{
                    label: 'Orders',
                    data: @json($monthlyOrders),
                    backgroundColor: '#F87171',
                    borderRadius: 8
                }]
            },
            options: chartDefaults
        });

        new Chart(document.getElementById('usersChart'), {
            type: 'bar',
            data: {
                labels: months,
                datasets: [{
                    label: 'New Users',
                    data: @json($monthlyUsers),
                    backgroundColor: '#E11D48',
                    borderRadius: 8
                }]
            },
            options: chartDefaults
        });
    </script>
@endpush
