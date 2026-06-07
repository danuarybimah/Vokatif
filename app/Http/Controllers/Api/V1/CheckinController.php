<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Checkin;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CheckinController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/v1/organizer/checkin",
     *     summary="Check-in tiket peserta (organizer)",
     *     tags={"Organizer"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="X-VOKATIF-KEY",
     *         in="header",
     *         required=false,
     *         description="API Key (opsional)",
     *         @OA\Schema(type="string", example="vokatif_demo_key_2026")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="ticket_code", type="string", example="VOK-2026-QSVCASHO", description="Kode tiket (wajib jika qr_payload tidak ada)"),
     *             @OA\Property(property="qr_payload", type="string", description="QR payload JSON (wajib jika ticket_code tidak ada)"),
     *             @OA\Property(property="notes", type="string", example="Check-in via gate A", description="Catatan tambahan (opsional)")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Check-in berhasil",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Check-in berhasil. Tiket valid."),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="ticket", type="object")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validasi gagal",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Validasi gagal."),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Tiket tidak ditemukan",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Tiket tidak ditemukan atau QR tidak valid.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=409,
     *         description="Tiket sudah pernah digunakan",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Tiket sudah pernah digunakan."),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="ticket", type="object"),
     *                 @OA\Property(property="checked_in_at", type="string", format="date-time")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Tiket tidak aktif",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Tiket tidak aktif.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     )
     * )
     */
    public function validateTicket(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ticket_code' => ['required_without:qr_payload', 'string'],
            'qr_payload' => ['required_without:ticket_code'],
            'notes' => ['nullable', 'string', 'max:255'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $ticketCode = $request->ticket_code;

        if (!$ticketCode && $request->qr_payload) {
            $payload = is_array($request->qr_payload)
                ? $request->qr_payload
                : json_decode($request->qr_payload, true);

            $ticketCode = $payload['ticket_code'] ?? null;
        }

        $scanner = auth('api')->user();

        $ticket = Ticket::with(['event', 'user', 'ticketType'])
            ->where('ticket_code', $ticketCode)
            ->first();

        if (!$ticket) {
            return response()->json([
                'success' => false,
                'message' => 'Tiket tidak ditemukan atau QR tidak valid.',
            ], 404);
        }

        if ($ticket->status === 'used') {
            Checkin::create([
                'ticket_id' => $ticket->id,
                'event_id' => $ticket->event_id,
                'checked_by' => $scanner->id,
                'status' => 'failed',
                'notes' => 'Tiket sudah pernah digunakan.',
                'checked_at' => now(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Tiket sudah pernah digunakan.',
                'data' => [
                    'ticket' => $ticket,
                    'checked_in_at' => $ticket->checked_in_at,
                ],
            ], 409);
        }

        if ($ticket->status !== 'active') {
            return response()->json([
                'success' => false,
                'message' => 'Tiket tidak aktif.',
            ], 400);
        }

        DB::transaction(function () use ($ticket, $scanner, $request) {
            $ticket->update([
                'status' => 'used',
                'checked_in_at' => now(),
            ]);

            Checkin::create([
                'ticket_id' => $ticket->id,
                'event_id' => $ticket->event_id,
                'checked_by' => $scanner->id,
                'status' => 'success',
                'notes' => $request->notes ?? 'Check-in berhasil.',
                'checked_at' => now(),
            ]);
        });

        return response()->json([
            'success' => true,
            'message' => 'Check-in berhasil. Tiket valid.',
            'data' => [
                'ticket' => $ticket->fresh()->load(['event', 'user', 'ticketType']),
            ],
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/organizer/checkin/history",
     *     summary="Riwayat check-in (organizer)",
     *     tags={"Organizer"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Riwayat check-in berhasil diambil",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Riwayat check-in berhasil diambil."),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="checkins", type="array", @OA\Items(type="object"))
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     )
     * )
     */
    public function history()
    {
        $checkins = Checkin::with(['ticket.user', 'ticket.ticketType', 'event', 'scanner'])
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Riwayat check-in berhasil diambil.',
            'data' => [
                'checkins' => $checkins,
            ],
        ]);
    }
}
