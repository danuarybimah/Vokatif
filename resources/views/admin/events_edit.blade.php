@extends('layouts.dashboard')

@section('content')

<div class="max-w-4xl mx-auto">

    <div class="section-header mb-10">
        <div>
            <p class="text-red-300 uppercase tracking-[0.2em] text-xs font-bold mb-3">Admin</p>
            <h1 class="text-5xl font-extrabold">Edit Event</h1>
            <p class="text-zinc-400 mt-3 max-w-2xl leading-7">
                Perbarui detail event dan atur jadwal event agar pengunjung bisa melihat informasi yang akurat.
            </p>
        </div>
    </div>

    @if ($errors->any())
        <div class="glass rounded-3xl p-6 mb-6 border border-red-500/20 bg-red-500/10 text-red-100">
            <p class="font-bold mb-3">Terjadi kesalahan:</p>
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="/admin/events/{{ $event->id }}"
          method="POST"
          enctype="multipart/form-data"
          class="glass rounded-[40px] p-10 space-y-6">

        @csrf
        @method('PUT')

        <div>
            <label class="block mb-3 font-bold">Event Title</label>
            <input type="text"
                   name="title"
                   value="{{ old('title', $event->title) }}"
                   class="w-full rounded-3xl input-field px-5 py-4"
                   placeholder="Judul event">
        </div>

        <div class="grid md:grid-cols-2 gap-6">
            <div>
                <label class="block mb-3 font-bold">City</label>
                <input type="text"
                       name="city"
                       value="{{ old('city', $event->city) }}"
                       class="w-full rounded-3xl input-field px-5 py-4"
                       placeholder="Kota event">
            </div>
            <div>
                <label class="block mb-3 font-bold">Location</label>
                <input type="text"
                       name="location"
                       value="{{ old('location', $event->location) }}"
                       class="w-full rounded-3xl input-field px-5 py-4"
                       placeholder="Lokasi event">
            </div>
        </div>

        <div class="grid md:grid-cols-2 gap-6">
            <div>
                <label class="block mb-3 font-bold">Start Date & Time</label>
                <input type="datetime-local"
                       name="start_at"
                       value="{{ old('start_at', optional($event->start_at)->format('Y-m-d\TH:i')) }}"
                       class="w-full rounded-3xl input-field px-5 py-4">
            </div>
            <div>
                <label class="block mb-3 font-bold">End Date & Time</label>
                <input type="datetime-local"
                       name="end_at"
                       value="{{ old('end_at', optional($event->end_at)->format('Y-m-d\TH:i')) }}"
                       class="w-full rounded-3xl input-field px-5 py-4">
            </div>
        </div>

        <div>
            <label class="block mb-3 font-bold">Category</label>
            <select name="category_id" class="w-full rounded-3xl select-field px-5 py-4 bg-no-repeat bg-right pr-12">
                @foreach($categories as $category)
                    <option value="{{ $category->id }}"
                        {{ $event->category_id == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block mb-3 font-bold">Description</label>
            <textarea name="description"
                      rows="6"
                      class="w-full rounded-3xl textarea-field px-5 py-4">{{ old('description', $event->description) }}</textarea>
        </div>

        <div class="grid md:grid-cols-2 gap-6">
            <div>
                <label class="block mb-3 font-bold">Status</label>
                <select name="status" class="w-full rounded-3xl select-field px-5 py-4 bg-no-repeat bg-right pr-12">
                    <option value="draft" {{ $event->status == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="published" {{ $event->status == 'published' ? 'selected' : '' }}>Published</option>
                </select>
            </div>
            <div>
                <label class="block mb-3 font-bold">Cover Image</label>
                <input type="file"
                       name="cover_image"
                       accept="image/*"
                       class="w-full rounded-3xl input-field px-5 py-4">
                <p class="text-zinc-500 text-sm mt-3">Ganti cover image agar event tampil lebih profesional.</p>
            </div>
        </div>

        @if($event->cover_image)
            <div class="glass rounded-3xl overflow-hidden border border-zinc-800 p-4">
                <p class="text-zinc-400 text-sm mb-3">Current cover image</p>
                <img src="{{ asset('storage/' . $event->cover_image) }}"
                     alt="Event cover"
                     class="w-full h-56 object-cover rounded-3xl">
            </div>
        @endif

        @php
            $ticket = $event->ticketTypes->first();
        @endphp
        <div class="pt-6 border-t border-zinc-800">
            <h2 class="text-2xl font-bold mb-6">Informasi Tiket (Default)</h2>
            <div class="grid md:grid-cols-3 gap-6">
                <div>
                    <label class="block mb-3 font-bold">Nama Tiket</label>
                    <input type="text" name="ticket_name" value="{{ old('ticket_name', $ticket ? $ticket->name : 'Regular Pass') }}" class="w-full rounded-3xl input-field px-5 py-4" placeholder="Contoh: Regular Pass" required>
                </div>
                <div>
                    <label class="block mb-3 font-bold">Harga (Rp)</label>
                    <input type="number" name="ticket_price" value="{{ old('ticket_price', $ticket ? (int)$ticket->price : 0) }}" class="w-full rounded-3xl input-field px-5 py-4" min="0" required>
                </div>
                <div>
                    <label class="block mb-3 font-bold">Kuota</label>
                    <input type="number" name="ticket_quota" value="{{ old('ticket_quota', $ticket ? $ticket->quota : 100) }}" class="w-full rounded-3xl input-field px-5 py-4" min="1" required>
                </div>
            </div>
        </div>

        <button type="submit"
            class="w-full py-5 rounded-3xl btn btn-primary text-xl transition shadow-xl shadow-red-900/20">
            Update Event
        </button>

    </form>

    <!-- DELETE FORM -->
    <form action="/admin/events/{{ $event->id }}"
          method="POST"
          onsubmit="return confirm('Yakin ingin menghapus event ini? Tindakan ini tidak bisa dibatalkan.')"
          class="mt-4">
        @csrf
        @method('DELETE')
        <button type="submit"
            class="w-full py-4 rounded-3xl border border-red-500/30 bg-red-950/10 text-red-450 font-bold text-lg transition hover:bg-red-950/20 hover:border-red-500/50 shadow-md shadow-red-950/10">
            🗑 Hapus Event
        </button>
    </form>

</div>

@endsection
