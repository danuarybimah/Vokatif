<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class CertificateController extends Controller
{
    /**
     * Download sertifikat PDF untuk tiket yang sudah digunakan
     * (kategori: Technology, Business, Education)
     */
    public function download(string $ticketCode)
    {
        $ticket = Ticket::with(['event.category', 'ticketType', 'user'])
            ->where('ticket_code', $ticketCode)
            ->where('user_id', Auth::id())
            ->where('status', 'used')
            ->firstOrFail();

        $allowedCategories = ['technology', 'business', 'education'];
        $categorySlug = strtolower($ticket->event->category->slug ?? '');

        abort_unless(in_array($categorySlug, $allowedCategories), 403, 'Sertifikat tidak tersedia untuk kategori ini.');

        // Load template image as base64
        $imagePath = public_path('images/sertifikat_template.png');
        if (!file_exists($imagePath)) {
            $imagePath = public_path('images/SERTIFIKAT (1).jpg');
        }
        
        $imageData = '';
        $imageMime = 'image/png';
        if (file_exists($imagePath)) {
            $imageData = base64_encode(file_get_contents($imagePath));
            $ext = strtolower(pathinfo($imagePath, PATHINFO_EXTENSION));
            $imageMime = ($ext === 'jpg' || $ext === 'jpeg') ? 'image/jpeg' : 'image/png';
        }

        $pdf = Pdf::loadView('tickets.certificate', compact('ticket', 'imageData', 'imageMime'))
            ->setPaper([0, 0, 841.89, 595.28], 'landscape') // A4 landscape in points
            ->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled'      => false,
                'defaultFont'          => 'DejaVu Sans',
                'isFontSubsettingEnabled' => true,
                'chroot'               => public_path(),
                'dpi'                  => 150,
            ]);

        $filename = 'sertifikat-' . str_replace(' ', '-', strtolower($ticket->event->title)) . '-' . $ticketCode . '.pdf';

        return $pdf->download($filename);
    }
}
