@extends('layouts.dashboard')

@section('content')

<div class="max-w-4xl mx-auto">

    <div class="section-header mb-10">
        <div>
            <p class="text-red-300 uppercase tracking-[0.2em] text-xs font-bold mb-3">Organizer</p>
            <h1 class="text-5xl font-extrabold">Create New Event</h1>
            <p class="text-zinc-400 mt-3 max-w-2xl leading-7">
                Isi data event lengkap dengan jadwal dan kategori untuk memudahkan promosi ke audiens.
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

    <form action="/organizer/events"
          method="POST"
          enctype="multipart/form-data"
          class="glass rounded-[40px] p-10 space-y-6">

        @csrf

        <div>

            <label class="block mb-3 font-bold">
                Event Title
            </label>

            <input type="text"
                   name="title"
                   value="{{ old('title') }}"
                   class="w-full rounded-3xl input-field px-5 py-4"
                   placeholder="Judul event">

        </div>

        <div class="grid md:grid-cols-2 gap-6">

            <div>

                <label class="block mb-3 font-bold">
                    City
                </label>

                <input type="text"
                       name="city"
                       value="{{ old('city') }}"
                       class="w-full rounded-3xl input-field px-5 py-4"
                       placeholder="Kota event">

            </div>

            <div>

                <label class="block mb-3 font-bold">
                    Location
                </label>

                <input type="text"
                       name="location"
                       value="{{ old('location') }}"
                       class="w-full rounded-3xl input-field px-5 py-4"
                       placeholder="Lokasi event">

            </div>

        </div>

        <div class="grid md:grid-cols-2 gap-6">

            <div>
                <label class="block mb-3 font-bold">
                    Start Date & Time
                </label>

                <input type="datetime-local"
                       name="start_at"
                       value="{{ old('start_at') }}"
                       class="w-full rounded-3xl input-field px-5 py-4">
            </div>

            <div>
                <label class="block mb-3 font-bold">
                    End Date & Time
                </label>

                <input type="datetime-local"
                       name="end_at"
                       value="{{ old('end_at') }}"
                       class="w-full rounded-3xl input-field px-5 py-4">
            </div>

        </div>

        <div>

            <label class="block mb-3 font-bold">
                Category
            </label>

            <select name="category_id"
                    class="w-full rounded-3xl select-field px-5 py-4">

                <option value="">Pilih kategori</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}"
                        {{ old('category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach

            </select>

        </div>

        <div>

            <label class="block mb-3 font-bold">
                Description
            </label>

            <textarea name="description"
                      rows="6"
                      class="w-full rounded-3xl textarea-field px-5 py-4"
                      placeholder="Deskripsi event">{{ old('description') }}</textarea>

        </div>

        <div class="grid md:grid-cols-2 gap-6">

            <div>

                <label class="block mb-3 font-bold">
                    Status
                </label>

                <select name="status"
                        class="w-full rounded-3xl select-field px-5 py-4">

                    <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>
                        Draft
                    </option>

                    <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>
                        Published
                    </option>

                </select>

            </div>

            <div>

                <label class="block mb-3 font-bold">
                    Cover Image
                </label>

                <input type="file"
                       name="cover_image"
                       accept="image/*"
                       class="w-full rounded-3xl input-field px-5 py-4">

                <p class="text-zinc-500 text-sm mt-3">
                    Upload gambar event untuk tampilan promosi yang lebih menarik.
                </p>

            </div>

        </div>

        <div class="pt-6 border-t border-zinc-800">
            <h2 class="text-2xl font-bold mb-6">Informasi Tiket (Default)</h2>
            <div class="grid md:grid-cols-3 gap-6">
                <div>
                    <label class="block mb-3 font-bold">Nama Tiket</label>
                    <input type="text" name="ticket_name" value="{{ old('ticket_name', 'Regular Pass') }}" class="w-full rounded-3xl input-field px-5 py-4" placeholder="Contoh: Regular Pass" required>
                </div>
                <div>
                    <label class="block mb-3 font-bold">Harga (Rp)</label>
                    <input type="number" name="ticket_price" value="{{ old('ticket_price', 0) }}" class="w-full rounded-3xl input-field px-5 py-4" min="0" required>
                </div>
                <div>
                    <label class="block mb-3 font-bold">Kuota</label>
                    <input type="number" name="ticket_quota" value="{{ old('ticket_quota', 100) }}" class="w-full rounded-3xl input-field px-5 py-4" min="1" required>
                </div>
            </div>
        </div>

        <button
            type="submit"
            class="w-full py-5 rounded-3xl btn btn-primary text-xl transition shadow-xl shadow-red-900/20">

            Create Event

        </button>

    </form>

</div>

@endsection
