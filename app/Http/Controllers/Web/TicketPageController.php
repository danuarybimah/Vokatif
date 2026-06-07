<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;

class TicketPageController extends Controller
{
    public function index()
    {
        $tickets = Ticket::with([
                'event',
                'ticketType'
            ])
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('tickets.index', compact('tickets'));
    }

    public function show(string $ticketCode)
    {
        $ticket = Ticket::with([
                'event',
                'ticketType',
                'user'
            ])
            ->where('ticket_code', $ticketCode)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('tickets.show', compact('ticket'));
    }
}
