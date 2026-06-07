<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Checkin;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class OrganizerEventController extends Controller
{
    public function index()
    {
        $events = Event::with([
                'category',
                'ticketTypes'
            ])
            ->where('organizer_id', Auth::id())
            ->latest()
            ->get();

        return view('organizer.events.index', compact('events'));
    }

    public function checkinParticipants()
    {
        $eventIds = Event::where('organizer_id', Auth::id())->pluck('id');

        $checkins = Checkin::with(['ticket.user', 'ticket.ticketType', 'event'])
            ->whereIn('event_id', $eventIds)
            ->latest()
            ->get();

        return view('organizer.checkin_participants', compact('checkins'));
    }

    public function edit(int $id)
    {
        $event = Event::with([
                'category',
                'ticketTypes'
            ])
            ->where('organizer_id', Auth::id())
            ->findOrFail($id);

        $categories = Category::all();

        return view('organizer.events.edit', compact(
            'event',
            'categories'
        ));
    }

    public function create()
    {
        $categories = Category::all();

        return view('organizer.events.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required'],
            'city' => ['required', 'string', 'max:255'],
            'location' => ['required', 'string', 'max:255'],
            'status' => ['required', 'in:draft,published'],
            'category_id' => ['required', 'exists:categories,id'],
            'start_at' => ['nullable', 'date'],
            'end_at' => ['nullable', 'date', 'after_or_equal:start_at'],
            'cover_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'ticket_name' => ['required', 'string', 'max:255'],
            'ticket_price' => ['required', 'numeric', 'min:0'],
            'ticket_quota' => ['required', 'integer', 'min:1'],
        ]);

        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = $request->file('cover_image')->store('events', 'public');
        }

        $eventData = collect($validated)->except(['ticket_name', 'ticket_price', 'ticket_quota'])->toArray();
        $eventData['organizer_id'] = Auth::id();
        $eventData['slug'] = Str::slug($validated['title']) . '-' . time();
        $eventData['is_featured'] = false;

        $event = Event::create($eventData);

        $event->ticketTypes()->create([
            'name' => $validated['ticket_name'],
            'price' => $validated['ticket_price'],
            'quota' => $validated['ticket_quota'],
            'is_active' => true,
        ]);

        return redirect('/organizer/events')
            ->with('success', 'Event berhasil ditambahkan.');
    }

    public function update(Request $request, int $id)
    {
        $event = Event::where('organizer_id', Auth::id())
            ->findOrFail($id);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required'],
            'city' => ['required', 'string', 'max:255'],
            'location' => ['required', 'string', 'max:255'],
            'status' => ['required', 'in:draft,published'],
            'category_id' => ['required', 'exists:categories,id'],
            'start_at' => ['nullable', 'date'],
            'end_at' => ['nullable', 'date', 'after_or_equal:start_at'],
            'cover_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'ticket_name' => ['required', 'string', 'max:255'],
            'ticket_price' => ['required', 'numeric', 'min:0'],
            'ticket_quota' => ['required', 'integer', 'min:1'],
        ]);

        if ($request->hasFile('cover_image')) {
            if ($event->cover_image && Storage::disk('public')->exists($event->cover_image)) {
                Storage::disk('public')->delete($event->cover_image);
            }
            $validated['cover_image'] = $request->file('cover_image')->store('events', 'public');
        }

        $eventData = collect($validated)->except(['ticket_name', 'ticket_price', 'ticket_quota'])->toArray();
        $event->update($eventData);

        $ticket = $event->ticketTypes()->first();
        if ($ticket) {
            $ticket->update([
                'name' => $validated['ticket_name'],
                'price' => $validated['ticket_price'],
                'quota' => $validated['ticket_quota'],
            ]);
        } else {
            $event->ticketTypes()->create([
                'name' => $validated['ticket_name'],
                'price' => $validated['ticket_price'],
                'quota' => $validated['ticket_quota'],
                'is_active' => true,
            ]);
        }

        return redirect('/organizer/events')
            ->with('success', 'Event berhasil diupdate.');
    }

    public function destroy(int $id)
    {
        $event = Event::where('organizer_id', Auth::id())
            ->findOrFail($id);

        if ($event->cover_image && Storage::disk('public')->exists($event->cover_image)) {
            Storage::disk('public')->delete($event->cover_image);
        }

        $event->delete();

        return redirect('/organizer/events')
            ->with('success', 'Event berhasil dihapus.');
    }
}
