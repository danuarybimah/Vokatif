@extends('layouts.user')

@section('content')
<div class="space-y-10">

    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-4xl font-extrabold text-white tracking-tight">Riwayat Transaksi</h1>
            <p class="text-zinc-500 text-sm mt-1 font-normal">Semua riwayat pembelian tiket dan status pembayaran kamu.</p>
        </div>

        <!-- BACK BUTTON -->
        @php $role = auth()->user()?->role?->slug; @endphp
        <div>
            <a href="{{ $role === 'admin' ? '/admin/dashboard' : ($role === 'organizer' ? '/organizer/dashboard' : '/home') }}"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-zinc-900 border border-zinc-800 text-zinc-400 hover:text-white hover:border-zinc-700 transition text-xs font-bold uppercase tracking-wider">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali
            </a>
        </div>
    </div>

    <!-- SUMMARY GRID (FINTECH METRICS) -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-5">
        <div class="premium-glass rounded-2xl p-5 shadow-lg">
            <p class="text-zinc-500 text-[10px] font-bold uppercase tracking-wider">Total Transaksi</p>
            <h2 class="text-3xl font-extrabold text-white mt-2 leading-none">{{ $orders->count() }}</h2>
        </div>
        <div class="premium-glass rounded-2xl p-5 shadow-lg border-l-2 border-l-red-500/30">
            <p class="text-zinc-500 text-[10px] font-bold uppercase tracking-wider">Total Pembayaran</p>
            <h2 class="text-xl font-extrabold text-red-400 mt-2 leading-none">
                Rp{{ number_format($orders->where('payment_status','paid')->sum('total_amount'), 0, ',', '.') }}
            </h2>
        </div>
        <div class="premium-glass rounded-2xl p-5 shadow-lg border-l-2 border-l-emerald-500/30">
            <p class="text-zinc-500 text-[10px] font-bold uppercase tracking-wider">Paid Order</p>
            <h2 class="text-3xl font-extrabold text-emerald-400 mt-2 leading-none">
                {{ $orders->where('payment_status','paid')->count() }}
            </h2>
        </div>
        <div class="premium-glass rounded-2xl p-5 shadow-lg border-l-2 border-l-yellow-500/30">
            <p class="text-zinc-500 text-[10px] font-bold uppercase tracking-wider">Pending Order</p>
            <h2 class="text-3xl font-extrabold text-yellow-400 mt-2 leading-none">
                {{ $orders->where('payment_status','pending')->count() }}
            </h2>
        </div>
    </div>

    <!-- LEDGER TABLE -->
    <div class="premium-glass rounded-[24px] overflow-hidden shadow-2xl border border-white/5">
        <div class="overflow-x-auto">
            <table class="w-full border-collapse">
                <thead class="border-b border-zinc-800 bg-zinc-950/40">
                    <tr>
                        <th class="text-left px-8 py-5 text-zinc-500 text-[10px] font-bold uppercase tracking-wider">Event Details</th>
                        <th class="text-left px-8 py-5 text-zinc-500 text-[10px] font-bold uppercase tracking-wider">Tiket</th>
                        <th class="text-left px-8 py-5 text-zinc-500 text-[10px] font-bold uppercase tracking-wider">Total Bayar</th>
                        <th class="text-left px-8 py-5 text-zinc-500 text-[10px] font-bold uppercase tracking-wider">Status</th>
                        <th class="text-left px-8 py-5 text-zinc-500 text-[10px] font-bold uppercase tracking-wider">Tanggal Transaksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-900/60">
                    @forelse($orders as $order)
                        <tr class="hover:bg-zinc-900/20 transition-all duration-200">
                            <td class="px-8 py-5">
                                <p class="font-bold text-white text-sm leading-snug">{{ $order->event->title }}</p>
                                <p class="text-zinc-550 text-xs mt-1.5 font-medium">{{ $order->event->city }}</p>
                            </td>
                            <td class="px-8 py-5 text-red-400 font-bold text-xs uppercase tracking-wider">
                                {{ $order->tickets->first()?->ticketType->name ?? '-' }}
                            </td>
                            <td class="px-8 py-5 font-bold text-white text-sm">
                                Rp{{ number_format($order->total_amount, 0, ',', '.') }}
                            </td>
                            <td class="px-8 py-5">
                                @if($order->payment_status === 'paid')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-[10px] font-bold uppercase tracking-wider leading-none">Paid</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-yellow-500/10 border border-yellow-500/20 text-yellow-400 text-[10px] font-bold uppercase tracking-wider leading-none">Pending</span>
                                @endif
                            </td>
                            <td class="px-8 py-5 text-zinc-500 text-xs font-semibold">
                                {{ $order->created_at->format('d M Y · H:i') }} WIB
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-20 text-zinc-500 text-sm font-medium">
                                Belum ada riwayat transaksi pembayaran.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
