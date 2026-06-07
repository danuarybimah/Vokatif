<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *     title="Vokatif API",
 *     version="1.0.0",
 *     description="API Documentation for Vokatif Event & Ticketing Platform",
 *     @OA\Contact(email="admin@vokatif.test")
 * )
 *
 * @OA\Server(
 *     url="http://127.0.0.1:8000",
 *     description="Local Development Server"
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT"
 * )
 *
 * @OA\Tag(name="Health", description="API Health Check")
 * @OA\Tag(name="Auth", description="Authentication")
 * @OA\Tag(name="Events", description="Event Management")
 * @OA\Tag(name="Orders", description="Order Management")
 * @OA\Tag(name="Tickets", description="Ticket Management")
 * @OA\Tag(name="Checkin", description="Check-in Management")
 * @OA\Tag(name="Organizer", description="Organizer Features")
 * @OA\Tag(name="Admin", description="Admin Features")
 *
 * @OA\Get(
 *     path="/api/v1/health",
 *     summary="Health check",
 *     tags={"Health"},
 *     @OA\Response(
 *         response=200,
 *         description="API is running",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="Vokatif API is running."),
 *             @OA\Property(property="app", type="string", example="Vokatif"),
 *             @OA\Property(property="version", type="string", example="1.0.0"),
 *             @OA\Property(property="timestamp", type="string", format="date-time")
 *         )
 *     )
 * )
 *
 * @OA\Get(
 *     path="/api/v1/admin/overview",
 *     summary="Statistik overview admin",
 *     tags={"Admin"},
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="X-VOKATIF-KEY",
 *         in="header",
 *         required=false,
 *         description="API Key",
 *         @OA\Schema(type="string", example="vokatif_demo_key_2026")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Admin overview berhasil diakses",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="Admin overview berhasil diakses."),
 *             @OA\Property(property="data", type="object",
 *                 @OA\Property(property="total_users", type="integer", example=10),
 *                 @OA\Property(property="total_events", type="integer", example=5),
 *                 @OA\Property(property="total_orders", type="integer", example=20),
 *                 @OA\Property(property="total_tickets", type="integer", example=50),
 *                 @OA\Property(property="total_api_logs", type="integer", example=100),
 *                 @OA\Property(property="total_revenue", type="number", format="float", example=5000000)
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthenticated"
 *     ),
 *     @OA\Response(
 *         response=403,
 *         description="Forbidden - hanya admin"
 *     )
 * )
 *
 * @OA\Get(
 *     path="/api/v1/organizer/overview",
 *     summary="Statistik overview organizer",
 *     tags={"Organizer"},
 *     security={{"bearerAuth":{}}},
 *     @OA\Response(
 *         response=200,
 *         description="Organizer overview berhasil diakses",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="Organizer overview berhasil diakses."),
 *             @OA\Property(property="data", type="object",
 *                 @OA\Property(property="total_events", type="integer", example=3),
 *                 @OA\Property(property="published_events", type="integer", example=2),
 *                 @OA\Property(property="draft_events", type="integer", example=1),
 *                 @OA\Property(property="total_ticket_types", type="integer", example=6),
 *                 @OA\Property(property="total_tickets_sold", type="integer", example=30),
 *                 @OA\Property(property="total_checkins", type="integer", example=15),
 *                 @OA\Property(property="estimated_revenue", type="number", format="float", example=3000000)
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthenticated"
 *     ),
 *     @OA\Response(
 *         response=403,
 *         description="Forbidden - hanya organizer/admin"
 *     )
 * )
 */
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}
