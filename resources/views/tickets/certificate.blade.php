<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Sertifikat Kehadiran</title>
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }

    @page {
        margin: 0;
        size: A4 landscape;
    }

    html, body {
        width: 297mm;
        height: 210mm;
        overflow: hidden;
        font-family: 'DejaVu Sans', Arial, sans-serif;
    }

    .cert-container {
        width: 297mm;
        height: 210mm;
        position: relative;
        background-image: url("data:{{ $imageMime ?? 'image/png' }};base64,{{ $imageData }}");
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
    }

    /* Sub-header: Partisipasi & Kehadiran */
    .sub-header {
        position: absolute;
        top: 75mm;
        width: 100%;
        text-align: center;
        font-size: 13pt;
        letter-spacing: 4px;
        text-transform: uppercase;
        color: #b5893d; /* Elegant gold color */
        font-weight: bold;
    }

    /* Teks Pengantar: Diberikan kepada */
    .intro {
        position: absolute;
        top: 92mm;
        width: 100%;
        text-align: center;
        font-size: 11pt;
        letter-spacing: 1px;
        color: #475569; /* Slate-600 */
        font-style: italic;
    }

    /* Nama Peserta */
    .name {
        position: absolute;
        top: 101mm;
        width: 100%;
        text-align: center;
        font-size: 28pt;
        font-weight: bold;
        color: #0f172a; /* Deep Slate-900 */
        letter-spacing: 1px;
    }

    /* Teks Keterangan: Telah menghadiri event */
    .event-label {
        position: absolute;
        top: 121mm;
        width: 100%;
        text-align: center;
        font-size: 11pt;
        letter-spacing: 1px;
        color: #475569;
    }

    /* Nama Event */
    .event-name {
        position: absolute;
        top: 130mm;
        width: 100%;
        text-align: center;
        font-size: 18pt;
        font-weight: bold;
        color: #1e3a8a; /* Deep Blue-900 */
        letter-spacing: 1px;
    }

    /* Footer Metadata Table */
    .metadata-table {
        position: absolute;
        top: 156mm;
        left: 28.5mm; /* Centering the table (297 - 240) / 2 */
        width: 240mm;
        border-collapse: collapse;
    }

    .metadata-cell {
        width: 25%;
        text-align: center;
        vertical-align: middle;
        border-right: 1px solid rgba(15, 23, 42, 0.12);
        padding: 0 10px;
    }

    .metadata-cell:last-child {
        border-right: none;
    }

    .meta-label {
        font-size: 8pt;
        color: #64748b; /* Slate-500 */
        letter-spacing: 1.5px;
        text-transform: uppercase;
        margin-bottom: 4px;
        display: block;
    }

    .meta-value {
        font-size: 10pt;
        font-weight: bold;
        color: #0f172a;
    }
</style>
</head>
<body>

<div class="cert-container">
    <!-- Sub-header -->
    <div class="sub-header">Partisipasi &amp; Kehadiran</div>

    <!-- Teks Pengantar -->
    <div class="intro">Diberikan kepada</div>

    <!-- Nama Peserta -->
    <div class="name">{{ $ticket->user->name }}</div>

    <!-- Teks Keterangan -->
    <div class="event-label">Telah menghadiri event</div>

    <!-- Nama Event -->
    <div class="event-name">{{ $ticket->event->title }}</div>

    <!-- Metadata Grid -->
    <table class="metadata-table">
        <tr>
            <td class="metadata-cell">
                <span class="meta-label">Kategori</span>
                <span class="meta-value">{{ $ticket->event->category->name ?? '-' }}</span>
            </td>
            <td class="metadata-cell">
                <span class="meta-label">Tipe Tiket</span>
                <span class="meta-value">{{ $ticket->ticketType->name ?? '-' }}</span>
            </td>
            <td class="metadata-cell">
                <span class="meta-label">Tanggal</span>
                <span class="meta-value">{{ $ticket->updated_at->format('d M Y') }}</span>
            </td>
            <td class="metadata-cell">
                <span class="meta-label">Kode Tiket / ID</span>
                <span class="meta-value">{{ $ticket->ticket_code }}</span>
            </td>
        </tr>
    </table>
</div>

</body>
</html>
