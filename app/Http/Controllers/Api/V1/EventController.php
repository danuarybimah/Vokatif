<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v1/events",
     *     summary="Ambil daftar event",
     *     tags={"Events"},
     *     @OA\Parameter(
     *         name="X-VOKATIF-KEY",
     *         in="header",
     *         required=true,
     *         description="API Key",
     *         @OA\Schema(type="string", example="vokatif_demo_key_2026")
     *     ),
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         required=false,
     *         description="Cari berdasarkan judul, kota, atau lokasi",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="category",
     *         in="query",
     *         required=false,
     *         description="Filter berdasarkan slug kategori",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="city",
     *         in="query",
     *         required=false,
     *         description="Filter berdasarkan kota",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="featured",
     *         in="query",
     *         required=false,
     *         description="Filter event unggulan saja (true/false)",
     *         @OA\Schema(type="boolean")
     *     ),
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         required=false,
     *         description="Nomor halaman untuk paginasi",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Daftar event berhasil diambil",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Daftar event berhasil diambil."),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="API Key tidak valid"
     *     )
     * )
     */
    public function index(Request $request)
    {
        $events = Event::with([
                'category',
                'organizer:id,name,email',
                'ticketTypes'
            ])
            ->where('status', 'published')
            ->when($request->search, function ($query) use ($request) {
                $query->where('title', 'like', '%' . $request->search . '%')
                    ->orWhere('city', 'like', '%' . $request->search . '%')
                    ->orWhere('location', 'like', '%' . $request->search . '%');
            })
            ->when($request->category, function ($query) use ($request) {
                $query->whereHas('category', function ($categoryQuery) use ($request) {
                    $categoryQuery->where('slug', $request->category);
                });
            })
            ->when($request->city, function ($query) use ($request) {
                $query->where('city', $request->city);
            })
            ->when($request->featured, function ($query) {
                $query->where('is_featured', true);
            })
            ->latest()
            ->paginate(10);

        return response()->json([
            'success' => true,
            'message' => 'Daftar event berhasil diambil.',
            'data' => $events,
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/events/{slug}",
     *     summary="Detail event berdasarkan slug",
     *     tags={"Events"},
     *     @OA\Parameter(
     *         name="X-VOKATIF-KEY",
     *         in="header",
     *         required=true,
     *         description="API Key",
     *         @OA\Schema(type="string", example="vokatif_demo_key_2026")
     *     ),
     *     @OA\Parameter(
     *         name="slug",
     *         in="path",
     *         required=true,
     *         description="Slug event",
     *         @OA\Schema(type="string", example="vokatif-tech-summit-2026")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Detail event berhasil diambil",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Detail event berhasil diambil."),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="event", type="object")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Event tidak ditemukan",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Event tidak ditemukan.")
     *         )
     *     )
     * )
     */
    public function show(string $slug)
    {
        $event = Event::with([
                'category',
                'organizer:id,name,email',
                'organizer.organizerProfile',
                'ticketTypes' => function ($query) {
                    $query->where('is_active', true);
                }
            ])
            ->where('slug', $slug)
            ->where('status', 'published')
            ->first();

        if (!$event) {
            return response()->json([
                'success' => false,
                'message' => 'Event tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail event berhasil diambil.',
            'data' => [
                'event' => $event,
            ],
        ]);
    }
}
