<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'    => ['required', 'string', 'max:255'],
            'email'   => ['required', 'email', 'max:255'],
            'subject' => ['nullable', 'string', 'max:255'],
            'message' => ['required', 'string'],
        ]);

        ContactMessage::create($validated);

        return response()->json(['success' => true, 'message' => 'Pesan berhasil terkirim!']);
    }

    /*
    |--------------------------------------------------------------------------
    | ADMIN: daftar pesan
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $messages = ContactMessage::latest()->get();

        // tandai semua sebagai sudah dibaca setelah dilihat admin
        ContactMessage::where('is_read', false)->update(['is_read' => true]);

        return view('admin.messages', compact('messages'));
    }
}
