<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\ApiRequestLog;
use App\Models\Checkin;
use App\Models\Event;
use App\Models\Order;
use App\Models\Ticket;
use App\Models\TicketType;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function organizer()
    {
        $eventIds = Event::where('organizer_id', Auth::id())->pluck('id');

        $totalEvents = $eventIds->count();
        $publishedEvents = Event::whereIn('id', $eventIds)->where('status', 'published')->count();
        $totalTicketTypes = TicketType::whereIn('event_id', $eventIds)->count();
        $totalTicketsSold = Ticket::whereIn('event_id', $eventIds)->count();
        $totalCheckins = Checkin::whereIn('event_id', $eventIds)->where('status', 'success')->count();
        $totalRevenue = Order::whereIn('event_id', $eventIds)
            ->where('payment_status', 'paid')
            ->sum('total_amount');

        $events = Event::with(['category', 'ticketTypes'])
            ->whereIn('id', $eventIds)
            ->latest()
            ->take(6)
            ->get();

        $latestOrders = Order::with(['user', 'event'])
            ->whereIn('event_id', $eventIds)
            ->latest()
            ->take(6)
            ->get();

        $latestCheckins = Checkin::with(['ticket.user', 'event', 'scanner'])
            ->whereIn('event_id', $eventIds)
            ->latest()
            ->take(6)
            ->get();

            /*
|--------------------------------------------------------------------------
| ANALYTICS CHART
|--------------------------------------------------------------------------
*/

$monthlyRevenue = [];

$monthlyTickets = [];

$months = [
    'Jan',
    'Feb',
    'Mar',
    'Apr',
    'May',
    'Jun',
    'Jul',
    'Aug',
    'Sep',
    'Oct',
    'Nov',
    'Dec'
];

foreach (range(1, 12) as $month) {

    $monthlyRevenue[] = Order::whereIn('event_id', $eventIds)
        ->where('payment_status', 'paid')
        ->whereMonth('created_at', $month)
        ->sum('total_amount');

    $monthlyTickets[] = Ticket::whereIn('event_id', $eventIds)
        ->whereMonth('created_at', $month)
        ->count();
}

        return view('organizer.dashboard', compact(
            'totalEvents',
            'publishedEvents',
            'totalTicketTypes',
            'totalTicketsSold',
            'totalCheckins',
            'totalRevenue',
            'events',
            'latestOrders',
            'latestCheckins',
            'monthlyRevenue',
            'monthlyTickets',
            'months'
        ));
    }

    public function admin()
    {
        $totalUsers = User::count();
        $totalEvents = Event::count();
        $totalOrders = Order::count();
        $totalTickets = Ticket::count();
        $totalCheckins = Checkin::where('status', 'success')->count();
        $totalApiLogs = ApiRequestLog::count();

        $totalRevenue = Order::where('payment_status', 'paid')->sum('total_amount');

        $latestOrders = Order::with(['user', 'event'])
            ->latest()
            ->take(6)
            ->get();

        $latestEvents = Event::with(['category', 'organizer'])
            ->latest()
            ->take(6)
            ->get();

        $apiLogs = ApiRequestLog::latest()
            ->take(8)
            ->get();

        $roleStats = User::with('role')
            ->get()
            ->groupBy(fn ($user) => $user->role?->slug ?? 'unknown')
            ->map(fn ($items) => $items->count());

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalEvents',
            'totalOrders',
            'totalTickets',
            'totalCheckins',
            'totalApiLogs',
            'totalRevenue',
            'latestOrders',
            'latestEvents',
            'apiLogs',
            'roleStats'
        ));
    }
    public function adminAnalytics()
    {
        $monthlyRevenue = [];
        $monthlyOrders  = [];
        $monthlyUsers   = [];

        $months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];

        foreach (range(1, 12) as $month) {

            $monthlyRevenue[] = Order::where('payment_status', 'paid')
                ->whereMonth('created_at', $month)
                ->sum('total_amount');

            $monthlyOrders[] = Order::whereMonth('created_at', $month)
                ->count();

            $monthlyUsers[] = User::whereMonth('created_at', $month)
                ->count();
        }

        $totalRevenue  = Order::where('payment_status', 'paid')->sum('total_amount');
        $totalOrders   = Order::count();
        $totalUsers    = User::count();
        $totalEvents   = Event::count();
        $paidOrders    = Order::where('payment_status', 'paid')->count();
        $pendingOrders = Order::where('payment_status', 'pending')->count();

        $totalOrganizers = User::whereHas('role', fn($q) => $q->where('slug', 'organizer'))->count();

        $topEvents = Event::withSum(['orders as revenue' => function ($q) {
                $q->where('payment_status', 'paid');
            }], 'total_amount')
            ->withCount('orders')
            ->orderByDesc('revenue')
            ->limit(5)
            ->get();

        return view('admin.analytics', compact(
            'months',
            'monthlyRevenue',
            'monthlyOrders',
            'monthlyUsers',
            'totalRevenue',
            'totalOrders',
            'totalUsers',
            'totalEvents',
            'paidOrders',
            'pendingOrders',
            'topEvents',
            'totalOrganizers'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | ORGANIZER ANALYTICS
    |--------------------------------------------------------------------------
    */

    public function organizerAnalytics()
    {
        $organizerId = Auth::id();

        $eventIds = Event::where('organizer_id', $organizerId)->pluck('id');

        $monthlyRevenue = [];
        $monthlyTickets = [];

        $months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];

        foreach (range(1, 12) as $month) {

            $monthlyRevenue[] = Order::whereIn('event_id', $eventIds)
                ->where('payment_status', 'paid')
                ->whereMonth('created_at', $month)
                ->sum('total_amount');

            $monthlyTickets[] = Ticket::whereIn('event_id', $eventIds)
                ->whereMonth('created_at', $month)
                ->count();
        }

        $totalRevenue    = Order::whereIn('event_id', $eventIds)->where('payment_status', 'paid')->sum('total_amount');
        $totalOrders     = Order::whereIn('event_id', $eventIds)->count();
        $totalEvents     = Event::where('organizer_id', $organizerId)->count();
        $publishedEvents = Event::where('organizer_id', $organizerId)->where('status', 'published')->count();
        $paidOrders      = Order::whereIn('event_id', $eventIds)->where('payment_status', 'paid')->count();
        $pendingOrders   = Order::whereIn('event_id', $eventIds)->where('payment_status', 'pending')->count();

        $topEvents = Event::where('organizer_id', $organizerId)
            ->withSum(['orders as revenue' => function ($q) {
                $q->where('payment_status', 'paid');
            }], 'total_amount')
            ->withCount('orders')
            ->orderByDesc('revenue')
            ->limit(5)
            ->get();

        return view('organizer.analytics', compact(
            'months',
            'monthlyRevenue',
            'monthlyTickets',
            'totalRevenue',
            'totalOrders',
            'totalEvents',
            'publishedEvents',
            'paidOrders',
            'pendingOrders',
            'topEvents'
        ));
    }
}

