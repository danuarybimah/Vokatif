<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrganizerProfile extends Model
{
    protected $fillable = [
        'user_id',
        'organization_name',
        'slug',
        'bio',
        'logo',
        'website',
        'social_links',
        'is_verified',
    ];

    protected $casts = [
        'social_links' => 'array',
        'is_verified' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
