<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Checkin;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckinPageController extends Controller
{
    public function index()
    {
        return view('organizer.scanner');
    }

    public function checkin(Request $request)
    {
        $request->validate([
            'ticket_code' => 'required'
        ]);

        $ticket = Ticket::where(
            'ticket_code',
            $request->ticket_code
        )->first();

        if (!$ticket) {

            return response()->json([
                'success' => false,
                'message' => 'Ticket tidak ditemukan'
            ]);

        }

        if ($ticket->status === 'used') {

            return response()->json([
                'success' => false,
                'message' => 'Ticket sudah digunakan'
            ]);

        }

        $ticket->update([
            'status' => 'used',
            'checked_in_at' => now()
        ]);

        Checkin::create([
            'event_id' => $ticket->event_id,
            'ticket_id' => $ticket->id,
            'checked_by' => Auth::id(),
            'checked_at' => now(),
            'status' => 'success'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Check-in berhasil',
            'user' => $ticket->user->name,
            'event' => $ticket->event->title
        ]);
    }
}
