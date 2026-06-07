<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApiRequestLog extends Model
{
    protected $fillable = [
        'api_client_id',
        'method',
        'endpoint',
        'ip_address',
        'status_code',
        'requested_at',
    ];

    protected $casts = [
        'requested_at' => 'datetime',
    ];

    public function apiClient()
    {
        return $this->belongsTo(ApiClient::class);
    }
}
