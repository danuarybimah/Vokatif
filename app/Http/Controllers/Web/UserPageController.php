<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;

class UserPageController extends Controller
{
    public function upcoming()
    {
        $tickets = Ticket::with(['event.category', 'ticketType'])
            ->where('user_id', Auth::id())
            ->whereHas('event', fn($q) => $q->where('start_at', '>=', now()))
            ->orderBy('created_at', 'desc')
            ->get();

        return view('user.upcoming', compact('tickets'));
    }

    public function transactions()
    {
        $orders = Order::with(['event', 'tickets.ticketType'])
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('user.transactions', compact('orders'));
    }

    public function qrTicket()
    {
        $tickets = Ticket::with(['event', 'ticketType'])
            ->where('user_id', Auth::id())
            ->where('status', 'active')
            ->latest()
            ->get();

        return view('user.qr-ticket', compact('tickets'));
    }

    public function home()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Tiket aktif
        $activeTickets = Ticket::with(['event', 'ticketType'])
            ->where('user_id', $user->id)
            ->where('status', 'active')
            ->latest()
            ->take(3)
            ->get();

        // Upcoming events dari tiket yang dimiliki
        $upcomingTickets = Ticket::with(['event.category', 'ticketType'])
            ->where('user_id', $user->id)
            ->whereHas('event', fn($q) => $q->where('start_at', '>=', now()))
            ->join('events', 'tickets.event_id', '=', 'events.id')
            ->orderBy('events.start_at', 'asc')
            ->select('tickets.*')
            ->take(3)
            ->get();

        // Total transaksi
        $totalSpent = Order::where('user_id', $user->id)
            ->where('payment_status', 'paid')
            ->sum('total_amount');

        $totalTickets = Ticket::where('user_id', $user->id)->count();

        $totalOrders = Order::where('user_id', $user->id)->count();

        // Event terbaru untuk dijelajahi
        $latestEvents = \App\Models\Event::with('category')
            ->where('status', 'published')
            ->where('start_at', '>=', now())
            ->latest()
            ->take(3)
            ->get();

        return view('user.home', compact(
            'user',
            'activeTickets',
            'upcomingTickets',
            'totalSpent',
            'totalTickets',
            'totalOrders',
            'latestEvents'
        ));
    }
}
