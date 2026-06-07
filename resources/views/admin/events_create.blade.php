@extends('layouts.dashboard')

@section('content')

<div class="max-w-4xl mx-auto">

    <div class="section-header mb-10">
        <div>
            <p class="text-red-300 uppercase tracking-[0.2em] text-xs font-bold mb-3">Admin</p>
            <h1 class="text-5xl font-black">Tambah Event</h1>
            <p class="text-zinc-400 mt-3 max-w-2xl leading-7">
                Buat event baru di platform Vokatif. Event akan langsung tersedia untuk semua user.
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

    <form action="/admin/events"
          method="POST"
          enctype="multipart/form-data"
          class="glass rounded-[40px] p-10 space-y-6">

        @csrf

        <div>
            <label class="block mb-3 font-bold">Event Title</label>
            <input type="text" name="title" value="{{ old('title') }}"
                   class="w-full rounded-3xl input-field px-5 py-4"
                   placeholder="Judul event">
        </div>

        <div class="grid md:grid-cols-2 gap-6">
            <div>
                <label class="block mb-3 font-bold">City</label>
                <input type="text" name="city" value="{{ old('city') }}"
                       class="w-full rounded-3xl input-field px-5 py-4"
                       placeholder="Kota event">
            </div>
            <div>
                <label class="block mb-3 font-bold">Location</label>
                <input type="text" name="location" value="{{ old('location') }}"
                       class="w-full rounded-3xl input-field px-5 py-4"
                       placeholder="Lokasi event">
            </div>
        </div>

        <div class="grid md:grid-cols-2 gap-6">
            <div>
                <label class="block mb-3 font-bold">Start Date & Time</label>
                <input type="datetime-local" name="start_at" value="{{ old('start_at') }}"
                       class="w-full rounded-3xl input-field px-5 py-4">
            </div>
            <div>
                <label class="block mb-3 font-bold">End Date & Time</label>
                <input type="datetime-local" name="end_at" value="{{ old('end_at') }}"
                       class="w-full rounded-3xl input-field px-5 py-4">
            </div>
        </div>

        <div>
            <label class="block mb-3 font-bold">Category</label>
            <select name="category_id" class="w-full rounded-3xl select-field px-5 py-4 bg-no-repeat bg-right pr-12">
                <option value="">Pilih kategori</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block mb-3 font-bold">Description</label>
            <textarea name="description" rows="6"
                      class="w-full rounded-3xl textarea-field px-5 py-4"
                      placeholder="Deskripsi event">{{ old('description') }}</textarea>
        </div>

        <div class="grid md:grid-cols-2 gap-6">
            <div>
                <label class="block mb-3 font-bold">Status</label>
                <select name="status" class="w-full rounded-3xl select-field px-5 py-4 bg-no-repeat bg-right pr-12">
                    <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Published</option>
                </select>
            </div>
            <div>
                <label class="block mb-3 font-bold">Cover Image</label>
                <input type="file" name="cover_image" accept="image/*"
                       class="w-full rounded-3xl input-field px-5 py-4">
                <p class="text-zinc-500 text-sm mt-3">Upload gambar event untuk tampilan promosi.</p>
            </div>
        </div>

        <div class="pt-6 border-t border-zinc-800">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold">Informasi Tiket</h2>
                <button type="button" id="add-ticket-btn"
                        class="px-4 py-2 rounded-xl bg-zinc-800 text-zinc-300 hover:text-white hover:bg-zinc-700 transition font-bold text-sm">
                    + Tambah Tipe Tiket
                </button>
            </div>
            
            <div id="tickets-container" class="space-y-6">
                <!-- First Ticket Row (Default) -->
                <div class="ticket-row bg-zinc-900/30 border border-zinc-800/85 rounded-[32px] p-6 relative">
                    <div class="grid md:grid-cols-3 gap-6">
                        <div>
                            <label class="block mb-3 font-bold">Nama Tiket</label>
                            <input type="text" name="tickets[0][name]" value="{{ old('tickets.0.name', 'Regular Pass') }}"
                                   class="w-full rounded-3xl input-field px-5 py-4" placeholder="Contoh: Regular Pass" required>
                        </div>
                        <div>
                            <label class="block mb-3 font-bold">Harga (Rp)</label>
                            <input type="number" name="tickets[0][price]" value="{{ old('tickets.0.price', 0) }}"
                                   class="w-full rounded-3xl input-field px-5 py-4" min="0" required>
                        </div>
                        <div>
                            <label class="block mb-3 font-bold">Kuota</label>
                            <input type="number" name="tickets[0][quota]" value="{{ old('tickets.0.quota', 100) }}"
                                   class="w-full rounded-3xl input-field px-5 py-4" min="1" required>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <button type="submit"
            class="w-full py-5 rounded-3xl btn btn-primary text-xl transition shadow-xl shadow-red-900/20">
            Create Event
        </button>

@push('scripts')
<script>
let ticketIndex = 1;

document.getElementById('add-ticket-btn').addEventListener('click', function() {
    const container = document.getElementById('tickets-container');
    const newRow = document.createElement('div');
    newRow.className = 'ticket-row bg-zinc-900/30 border border-zinc-800/85 rounded-[32px] p-6 relative';
    newRow.innerHTML = `
        <button type="button" class="remove-ticket-btn absolute top-6 right-6 text-zinc-500 hover:text-red-400 transition" title="Hapus Tipe Tiket">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
            </svg>
        </button>
        <div class="grid md:grid-cols-3 gap-6 pr-8">
            <div>
                <label class="block mb-3 font-bold">Nama Tiket</label>
                <input type="text" name="tickets[${ticketIndex}][name]"
                       class="w-full rounded-3xl input-field px-5 py-4" placeholder="Contoh: VIP Pass" required>
            </div>
            <div>
                <label class="block mb-3 font-bold">Harga (Rp)</label>
                <input type="number" name="tickets[${ticketIndex}][price]" value="0"
                       class="w-full rounded-3xl input-field px-5 py-4" min="0" required>
            </div>
            <div>
                <label class="block mb-3 font-bold">Kuota</label>
                <input type="number" name="tickets[${ticketIndex}][quota]" value="100"
                       class="w-full rounded-3xl input-field px-5 py-4" min="1" required>
            </div>
        </div>
    `;
    
    newRow.querySelector('.remove-ticket-btn').addEventListener('click', function() {
        newRow.remove();
    });
    
    container.appendChild(newRow);
    ticketIndex++;
});
</script>
@endpush

    </form>

</div>

@endsection
