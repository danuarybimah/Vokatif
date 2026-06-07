<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Event;

class HomeController extends Controller
{
    public function index()
    {
        $featuredEvents = Event::with(['category', 'organizer', 'ticketTypes'])
            ->where('status', 'published')
            ->where('is_featured', true)
            ->latest()
            ->take(6)
            ->get();

        $upcomingEvents = Event::with(['category', 'organizer', 'ticketTypes'])
            ->where('status', 'published')
            ->where('start_at', '>=', now())
            ->orderBy('start_at')
            ->take(8)
            ->get();

        $categories = Category::withCount('events')
            ->take(6)
            ->get();

        return view('landing', compact(
            'featuredEvents',
            'upcomingEvents',
            'categories'
        ));
    }
}
