<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = [
        'user_id',
        'event_id',
        'order_id',
        'order_item_id',
        'ticket_type_id',
        'ticket_code',
        'qr_payload',
        'status',
        'checked_in_at',
    ];

    protected $casts = [
        'checked_in_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class);
    }

    public function ticketType()
    {
        return $this->belongsTo(TicketType::class);
    }

    public function checkins()
    {
        return $this->hasMany(Checkin::class);
    }
}
