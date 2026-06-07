<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Event;
use Illuminate\Http\Request;

class EventPageController extends Controller
{
    public function index(Request $request)
    {
        $events = Event::with(['category', 'organizer', 'ticketTypes'])
            ->where('status', 'published')
            ->when($request->search, function ($query) use ($request) {
                $query->where(function ($eventQuery) use ($request) {
                    $eventQuery->where('title', 'like', '%' . $request->search . '%')
                        ->orWhere('city', 'like', '%' . $request->search . '%')
                        ->orWhere('location', 'like', '%' . $request->search . '%');
                });
            })
            ->when($request->category, function ($query) use ($request) {
                $query->whereHas('category', function ($categoryQuery) use ($request) {
                    $categoryQuery->where('slug', $request->category);
                });
            })
            ->latest()
            ->paginate(9)
            ->withQueryString();

        $categories = Category::withCount('events')->get();

        return view('events.index', compact('events', 'categories'));
    }

    public function show(string $slug)
    {
        $event = Event::with([
                'category',
                'organizer.organizerProfile',
                'ticketTypes' => function ($query) {
                    $query->where('is_active', true);
                }
            ])
            ->where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        $relatedEvents = Event::with(['category', 'ticketTypes'])
            ->where('status', 'published')
            ->where('category_id', $event->category_id)
            ->where('id', '!=', $event->id)
            ->take(3)
            ->get();

        return view('events.show', compact('event', 'relatedEvents'));
    }
}
