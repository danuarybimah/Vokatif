<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Ticket;

class TicketController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v1/my-tickets",
     *     summary="Lihat daftar tiket user yang sedang login",
     *     tags={"Tickets"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Daftar tiket berhasil diambil",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Daftar tiket berhasil diambil."),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="tickets", type="array", @OA\Items(type="object"))
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     )
     * )
     */
    public function myTickets()
    {
        $user = auth('api')->user();

        $tickets = Ticket::with(['event', 'ticketType', 'order'])
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar tiket berhasil diambil.',
            'data' => [
                'tickets' => $tickets,
            ],
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/my-tickets/{ticketCode}",
     *     summary="Detail tiket beserta QR payload",
     *     tags={"Tickets"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="ticketCode",
     *         in="path",
     *         required=true,
     *         description="Kode tiket unik",
     *         @OA\Schema(type="string", example="VOK-2026-FD8P3PVU")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Detail tiket berhasil diambil",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Detail tiket berhasil diambil."),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="ticket", type="object"),
     *                 @OA\Property(property="qr_payload", type="object",
     *                     @OA\Property(property="ticket_code", type="string", example="VOK-2026-FD8P3PVU"),
     *                     @OA\Property(property="order_id", type="integer", example=1),
     *                     @OA\Property(property="event_id", type="integer", example=1),
     *                     @OA\Property(property="user_id", type="integer", example=3),
     *                     @OA\Property(property="signature", type="string", example="abc123...")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Tiket tidak ditemukan",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Tiket tidak ditemukan.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     )
     * )
     */
    public function show($ticketCode)
    {
        $user = auth('api')->user();

        $ticket = Ticket::with(['event.category', 'ticketType', 'order'])
            ->where('ticket_code', $ticketCode)
            ->where('user_id', $user->id)
            ->first();

        if (!$ticket) {
            return response()->json([
                'success' => false,
                'message' => 'Tiket tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail tiket berhasil diambil.',
            'data' => [
                'ticket' => $ticket,
                'qr_payload' => json_decode($ticket->qr_payload, true),
            ],
        ]);
    }
}
