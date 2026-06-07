<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Ticket;
use App\Models\TicketType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class OrderPageController extends Controller
{
    public function store(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | GET EVENT & TICKET TYPE
        |--------------------------------------------------------------------------
        */

        $event = Event::findOrFail(
            $request->event_id
        );

        $ticketType = TicketType::findOrFail(
            $request->ticket_type_id
        );

        /*
        |--------------------------------------------------------------------------
        | CREATE ORDER
        |--------------------------------------------------------------------------
        */

        $order = Order::create([

            'user_id' => Auth::user()->id,

            'event_id' => $event->id,

            'invoice_number' =>
                'INV-' . strtoupper(Str::random(10)),

            'total_amount' => $ticketType->price,

            'payment_status' => 'paid'

        ]);

        /*
        |--------------------------------------------------------------------------
        | CREATE ORDER ITEM
        |--------------------------------------------------------------------------
        */

        $orderItem = OrderItem::create([

    'order_id' => $order->id,

    'ticket_type_id' => $ticketType->id,

    'quantity' => 1,

    'unit_price' => $ticketType->price,

    'subtotal' => $ticketType->price

]);

        /*
        |--------------------------------------------------------------------------
        | GENERATE TICKET CODE
        |--------------------------------------------------------------------------
        */

        $ticketCode =
            'VOK-' .
            now()->format('Y') .
            '-' .
            strtoupper(Str::random(8));

        /*
        |--------------------------------------------------------------------------
        | QR PAYLOAD
        |--------------------------------------------------------------------------
        */

        $payload = json_encode([

            'ticket_code' => $ticketCode,

            'event_id' => $event->id,

            'user_id' => Auth::user()->id

        ]);

        /*
        |--------------------------------------------------------------------------
        | CREATE TICKET
        |--------------------------------------------------------------------------
        */

        Ticket::create([

            'user_id' => Auth::user()->id,

            'event_id' => $event->id,

            'order_id' => $order->id,

            'order_item_id' => $orderItem->id,

            'ticket_type_id' => $ticketType->id,

            'ticket_code' => $ticketCode,

            'qr_payload' => $payload,

            'status' => 'active'

        ]);

        /*
        |--------------------------------------------------------------------------
        | REDIRECT
        |--------------------------------------------------------------------------
        */

        return redirect('/my-tickets')
            ->with(
                'success',
                'Ticket berhasil dibeli'
            );
    }
}
