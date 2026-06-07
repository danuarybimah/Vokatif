<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Ticket;
use App\Models\TicketType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/v1/orders",
     *     summary="Buat order tiket baru",
     *     tags={"Orders"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"event_id","items"},
     *             @OA\Property(property="event_id", type="integer", example=1),
     *             @OA\Property(property="payment_method", type="string", enum={"manual","qris","bank_transfer","ewallet"}, example="manual"),
     *             @OA\Property(
     *                 property="items",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     required={"ticket_type_id","quantity"},
     *                     @OA\Property(property="ticket_type_id", type="integer", example=2),
     *                     @OA\Property(property="quantity", type="integer", example=1, minimum=1, maximum=10)
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Order berhasil dibuat",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Order berhasil dibuat. Silakan lanjutkan pembayaran."),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="order", type="object")
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
     *         response=400,
     *         description="Kuota tiket tidak mencukupi atau error lainnya",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Kuota tiket tidak mencukupi.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'event_id' => ['required', 'exists:events,id'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.ticket_type_id' => ['required', 'exists:ticket_types,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1', 'max:10'],
            'payment_method' => ['nullable', 'in:manual,qris,bank_transfer,ewallet'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = auth('api')->user();

        try {
            $order = DB::transaction(function () use ($request, $user) {
                $totalAmount = 0;
                $preparedItems = [];

                foreach ($request->items as $item) {
                    $ticketType = TicketType::where('id', $item['ticket_type_id'])
                        ->where('event_id', $request->event_id)
                        ->where('is_active', true)
                        ->lockForUpdate()
                        ->first();

                    if (!$ticketType) {
                        throw new \Exception('Tipe tiket tidak valid untuk event ini.');
                    }

                    $availableQuota = $ticketType->quota - $ticketType->sold;

                    if ($item['quantity'] > $availableQuota) {
                        throw new \Exception('Kuota tiket ' . $ticketType->name . ' tidak mencukupi.');
                    }

                    $subtotal = $ticketType->price * $item['quantity'];
                    $totalAmount += $subtotal;

                    $preparedItems[] = [
                        'ticket_type' => $ticketType,
                        'quantity' => $item['quantity'],
                        'unit_price' => $ticketType->price,
                        'subtotal' => $subtotal,
                    ];
                }

                $order = Order::create([
                    'invoice_number' => 'INV-' . now()->format('YmdHis') . '-' . strtoupper(Str::random(5)),
                    'user_id' => $user->id,
                    'event_id' => $request->event_id,
                    'total_amount' => $totalAmount,
                    'payment_status' => 'pending',
                    'payment_method' => $request->payment_method ?? 'manual',
                ]);

                foreach ($preparedItems as $preparedItem) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'ticket_type_id' => $preparedItem['ticket_type']->id,
                        'quantity' => $preparedItem['quantity'],
                        'unit_price' => $preparedItem['unit_price'],
                        'subtotal' => $preparedItem['subtotal'],
                    ]);
                }

                return $order;
            });

            return response()->json([
                'success' => true,
                'message' => 'Order berhasil dibuat. Silakan lanjutkan pembayaran.',
                'data' => [
                    'order' => $order->load(['event', 'items.ticketType']),
                ],
            ], 201);
        } catch (\Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage(),
            ], 400);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/v1/orders/{id}",
     *     summary="Lihat detail order",
     *     tags={"Orders"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID order",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Detail order berhasil diambil",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Detail order berhasil diambil."),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="order", type="object")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Order tidak ditemukan",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Order tidak ditemukan.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     )
     * )
     */
    public function show($id)
    {
        $user = auth('api')->user();

        $order = Order::with(['event', 'items.ticketType', 'tickets'])
            ->where('id', $id)
            ->where('user_id', $user->id)
            ->first();

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail order berhasil diambil.',
            'data' => [
                'order' => $order,
            ],
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/orders/{id}/pay",
     *     summary="Bayar order",
     *     tags={"Orders"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID order yang akan dibayar",
     *         @OA\Schema(type="integer", example=5)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Pembayaran berhasil",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Pembayaran berhasil. Tiket QR berhasil dibuat."),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="order", type="object")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Order tidak ditemukan",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Order tidak ditemukan.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Order sudah dibayar atau error lainnya",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Order ini sudah dibayar.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     )
     * )
     */
    public function pay($id)
    {
        $user = auth('api')->user();

        $order = Order::with(['items.ticketType'])
            ->where('id', $id)
            ->where('user_id', $user->id)
            ->first();

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order tidak ditemukan.',
            ], 404);
        }

        if ($order->payment_status === 'paid') {
            return response()->json([
                'success' => false,
                'message' => 'Order ini sudah dibayar.',
            ], 400);
        }

        try {
            DB::transaction(function () use ($order, $user) {
                $order->update([
                    'payment_status' => 'paid',
                    'paid_at' => now(),
                ]);

                foreach ($order->items as $item) {
                    $ticketType = TicketType::where('id', $item->ticket_type_id)
                        ->lockForUpdate()
                        ->first();

                    $availableQuota = $ticketType->quota - $ticketType->sold;

                    if ($item->quantity > $availableQuota) {
                        throw new \Exception('Kuota tiket tidak mencukupi saat pembayaran.');
                    }

                    $ticketType->increment('sold', $item->quantity);

                    for ($i = 1; $i <= $item->quantity; $i++) {
                        $ticketCode = 'VOK-' . now()->format('Y') . '-' . strtoupper(Str::random(8));

                        $payload = [
                            'ticket_code' => $ticketCode,
                            'order_id' => $order->id,
                            'event_id' => $order->event_id,
                            'user_id' => $user->id,
                            'signature' => hash_hmac('sha256', $ticketCode . '|' . $order->id, config('app.key')),
                        ];

                        Ticket::create([
                            'user_id' => $user->id,
                            'event_id' => $order->event_id,
                            'order_id' => $order->id,
                            'order_item_id' => $item->id,
                            'ticket_type_id' => $item->ticket_type_id,
                            'ticket_code' => $ticketCode,
                            'qr_payload' => json_encode($payload),
                            'status' => 'active',
                        ]);
                    }
                }
            });

            return response()->json([
                'success' => true,
                'message' => 'Pembayaran berhasil. Tiket QR berhasil dibuat.',
                'data' => [
                    'order' => $order->fresh()->load(['event', 'items.ticketType', 'tickets']),
                ],
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage(),
            ], 400);
        }
    }
}
