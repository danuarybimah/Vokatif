@extends('layouts.dashboard')

@section('content')
<div class="space-y-6">

    <!-- HEADER -->
    <div class="flex items-center justify-between flex-wrap gap-4">
        <div>
            <h1 class="text-2xl font-bold text-white">Admin Dashboard</h1>
            <p class="text-zinc-500 text-sm mt-1">Monitor aktivitas platform Vokatif.</p>
        </div>
        <div class="flex items-center gap-2 px-3 py-2 rounded-lg" style="background:#18181B; border:1px solid rgba(63,63,70,0.45);">
            <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
            <span class="text-emerald-400 text-sm font-semibold">Operational</span>
        </div>
    </div>

    <!-- STATS -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="stat-card">
            <p class="stat-label">Total Users</p>
            <p class="stat-value">{{ $totalUsers }}</p>
        </div>
        <div class="stat-card">
            <p class="stat-label">Total Events</p>
            <p class="stat-value">{{ $totalEvents }}</p>
        </div>
        <div class="stat-card">
            <p class="stat-label">Tickets Sold</p>
            <p class="stat-value">{{ $totalTickets }}</p>
        </div>
        <div class="stat-card" style="border-color: rgba(220,38,38,0.2);">
            <p class="stat-label">Revenue</p>
            <p class="stat-value text-red-400" style="font-size:22px;">
                Rp{{ number_format($totalRevenue, 0, ',', '.') }}
            </p>
        </div>
    </div>

    <!-- CHART + ROLES -->
    <div class="grid lg:grid-cols-3 gap-4">

        <div class="lg:col-span-2 card rounded-xl p-6">
            <div class="mb-5">
                <h2 class="text-base font-semibold text-white">Revenue Analytics</h2>
                <p class="text-zinc-500 text-xs mt-1">Monthly transaction overview</p>
            </div>
            <canvas id="revenueChart" height="120"></canvas>
        </div>

        <div class="card rounded-xl p-6">
            <h2 class="text-base font-semibold text-white mb-5">User Roles</h2>
            <div class="space-y-3">
                @foreach($roleStats as $role => $count)
                    <div class="flex items-center justify-between py-2 border-b border-zinc-800/60 last:border-0">
                        <span class="text-sm text-zinc-300 capitalize">{{ $role }}</span>
                        <span class="text-white font-bold">{{ $count }}</span>
                    </div>
                @endforeach
            </div>
        </div>

    </div>

    <!-- LATEST ORDERS -->
    <div class="card rounded-xl">
        <div class="px-6 py-4 border-b border-zinc-800/60">
            <h2 class="text-base font-semibold text-white">Latest Orders</h2>
            <p class="text-zinc-500 text-xs mt-0.5">Recent ticket transactions</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-zinc-800/60">
                        <th class="text-left px-6 py-3 text-xs font-semibold text-zinc-500 uppercase tracking-wide">User</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-zinc-500 uppercase tracking-wide">Event</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-zinc-500 uppercase tracking-wide">Amount</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-zinc-500 uppercase tracking-wide">Status</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-zinc-500 uppercase tracking-wide">Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($latestOrders as $order)
                        <tr class="border-b border-zinc-800/60 hover:bg-zinc-800/30 transition">
                            <td class="px-6 py-4 text-sm text-white">{{ $order->user->name }}</td>
                            <td class="px-6 py-4 text-sm text-zinc-400">{{ $order->event->title }}</td>
                            <td class="px-6 py-4 text-sm font-semibold text-white">
                                Rp{{ number_format($order->total_amount, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="badge {{ $order->payment_status === 'paid' ? 'badge-green' : 'badge-yellow' }}">
                                    {{ $order->payment_status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-zinc-500">{{ $order->created_at->format('d M Y') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>

@push('scripts')
<script>
new Chart(document.getElementById('revenueChart'), {
    type: 'line',
    data: {
        labels: ['Jan','Feb','Mar','Apr','May','Jun'],
        datasets: [{
            label: 'Revenue',
            data: [1200000,1900000,3000000,2500000,4200000,5100000],
            borderColor: '#DC2626',
            backgroundColor: 'rgba(220,38,38,0.08)',
            fill: true,
            tension: 0.4,
            borderWidth: 2,
            pointRadius: 3,
            pointBackgroundColor: '#DC2626'
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            x: { ticks: { color: '#475569', fontSize: 11 }, grid: { color: 'rgba(255,255,255,0.03)' } },
            y: { ticks: { color: '#475569', fontSize: 11 }, grid: { color: 'rgba(255,255,255,0.03)' } }
        }
    }
});
</script>
@endpush

@endsection
