<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Event;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | USER MANAGEMENT
    |--------------------------------------------------------------------------
    */

    public function users(Request $request)
    {
        $search = $request->query('search');
        $query = User::with('role');

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->latest()->paginate(10)->withQueryString();
        $roles = Role::all();

        return view('admin.users', compact('users', 'roles'));
    }

    public function updateUserRole(Request $request, int $id)
    {
        $request->validate([
            'role_id' => ['required', 'exists:roles,id'],
        ]);

        $user = User::findOrFail($id);
        $user->update(['role_id' => $request->role_id]);

        return redirect()->back()->with('success', 'Role user berhasil diubah.');
    }

    /*
    |--------------------------------------------------------------------------
    | EVENT MANAGEMENT (ADMIN)
    |--------------------------------------------------------------------------
    */

    public function events()
    {
        $events = Event::with(['category', 'organizer', 'ticketTypes'])
            ->latest()
            ->get();

        $categories = Category::all();

        return view('admin.events', compact('events', 'categories'));
    }

    public function createEvent()
    {
        $categories = Category::all();
        return view('admin.events_create', compact('categories'));
    }

    public function storeEvent(Request $request)
    {
        $validated = $request->validate([
            'title'        => ['required', 'string', 'max:255'],
            'description'  => ['required'],
            'city'         => ['required', 'string', 'max:255'],
            'location'     => ['required', 'string', 'max:255'],
            'status'       => ['required', 'in:draft,published'],
            'category_id'  => ['required', 'exists:categories,id'],
            'start_at'     => ['nullable', 'date'],
            'end_at'       => ['nullable', 'date', 'after_or_equal:start_at'],
            'cover_image'  => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'tickets'      => ['required', 'array', 'min:1'],
            'tickets.*.name'  => ['required', 'string', 'max:255'],
            'tickets.*.price' => ['required', 'numeric', 'min:0'],
            'tickets.*.quota' => ['required', 'integer', 'min:1'],
        ]);

        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = $request->file('cover_image')->store('events', 'public');
        }

        $eventData = collect($validated)->except(['tickets'])->toArray();
        $eventData['organizer_id'] = Auth::id();
        $eventData['slug']         = Str::slug($validated['title']) . '-' . time();
        $eventData['is_featured']  = false;

        $event = Event::create($eventData);

        foreach ($validated['tickets'] as $ticketData) {
            $event->ticketTypes()->create([
                'name'      => $ticketData['name'],
                'price'     => $ticketData['price'],
                'quota'     => $ticketData['quota'],
                'is_active' => true,
            ]);
        }

        return redirect('/admin/events')->with('success', 'Event berhasil ditambahkan.');
    }

    public function destroyEvent(int $id)
    {
        $event = Event::findOrFail($id);

        if ($event->cover_image && Storage::disk('public')->exists($event->cover_image)) {
            Storage::disk('public')->delete($event->cover_image);
        }

        $event->delete();

        return redirect()->back()->with('success', 'Event berhasil dihapus.');
    }

    public function editEvent(int $id)
    {
        $event = Event::with(['category', 'ticketTypes'])->findOrFail($id);
        $categories = Category::all();

        return view('admin.events_edit', compact('event', 'categories'));
    }

    public function updateEvent(Request $request, int $id)
    {
        $event = Event::findOrFail($id);

        $validated = $request->validate([
            'title'        => ['required', 'string', 'max:255'],
            'description'  => ['required'],
            'city'         => ['required', 'string', 'max:255'],
            'location'     => ['required', 'string', 'max:255'],
            'status'       => ['required', 'in:draft,published'],
            'category_id'  => ['required', 'exists:categories,id'],
            'start_at'     => ['nullable', 'date'],
            'end_at'       => ['nullable', 'date', 'after_or_equal:start_at'],
            'cover_image'  => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'ticket_name'  => ['required', 'string', 'max:255'],
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
                'name'      => $validated['ticket_name'],
                'price'     => $validated['ticket_price'],
                'quota'     => $validated['ticket_quota'],
            ]);
        } else {
            $event->ticketTypes()->create([
                'name'      => $validated['ticket_name'],
                'price'     => $validated['ticket_price'],
                'quota'     => $validated['ticket_quota'],
                'is_active' => true,
            ]);
        }

        return redirect('/admin/events')->with('success', 'Event berhasil diupdate.');
    }
}
