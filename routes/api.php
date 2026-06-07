<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\EventController;
use App\Http\Controllers\Api\V1\OrderController;
use App\Http\Controllers\Api\V1\TicketController;
use App\Http\Controllers\Api\V1\CheckinController;
use App\Models\ApiRequestLog;
use App\Models\Category;
use App\Models\Event;
use App\Models\Order;
use App\Models\Ticket;
use App\Models\TicketType;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::get('/health', function () {
        return response()->json([
            'success' => true,
            'message' => 'Vokatif API is running.',
            'app' => 'Vokatif',
            'version' => '1.0.0',
            'timestamp' => now(),
        ]);
    });

 Route::prefix('auth')->group(function () {

        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/login', [AuthController::class, 'login']);

        Route::middleware('auth:api')->group(function () {

            Route::get('/me', [AuthController::class, 'me']);
            Route::post('/logout', [AuthController::class, 'logout']);
            Route::post('/refresh', [AuthController::class, 'refresh']);
    });
    });

    Route::middleware('api.key')->group(function () {
        Route::get('/categories', function () {
            $categories = Category::withCount('events')->get();

            return response()->json([
                'success' => true,
                'message' => 'Daftar kategori berhasil diambil.',
                'data' => [
                    'categories' => $categories,
                ],
            ]);
        });

        Route::get('/events', [EventController::class, 'index']);
        Route::get('/events/{slug}', [EventController::class, 'show']);
    });

    Route::middleware('auth:api')->group(function () {
        Route::post('/orders', [OrderController::class, 'store']);
        Route::get('/orders/{id}', [OrderController::class, 'show']);
        Route::post('/orders/{id}/pay', [OrderController::class, 'pay']);

        Route::get('/my-tickets', [TicketController::class, 'myTickets']);
        Route::get('/my-tickets/{ticketCode}', [TicketController::class, 'show']);
    });

    Route::middleware(['auth:api', 'role:organizer,admin'])->group(function () {
        Route::post('/checkins/validate', [CheckinController::class, 'validateTicket']);
        Route::get('/checkins/history', [CheckinController::class, 'history']);
    });

    Route::middleware(['auth:api', 'role:organizer,admin'])->prefix('organizer')->group(function () {
        Route::get('/overview', function () {
            $user = auth('api')->user();

            $eventQuery = Event::query();

            if ($user->role?->slug === 'organizer') {
                $eventQuery->where('organizer_id', $user->id);
            }

            $eventIds = $eventQuery->pluck('id');

            return response()->json([
                'success' => true,
                'message' => 'Organizer overview berhasil diakses.',
                'data' => [
                    'total_events' => $eventIds->count(),
                    'published_events' => Event::whereIn('id', $eventIds)
                        ->where('status', 'published')
                        ->count(),
                    'draft_events' => Event::whereIn('id', $eventIds)
                        ->where('status', 'draft')
                        ->count(),
                    'total_ticket_types' => TicketType::whereIn('event_id', $eventIds)->count(),
                    'total_tickets_sold' => Ticket::whereIn('event_id', $eventIds)->count(),
                    'total_checkins' => Ticket::whereIn('event_id', $eventIds)
                        ->where('status', 'used')
                        ->count(),
                    'estimated_revenue' => Order::whereIn('event_id', $eventIds)
                        ->where('payment_status', 'paid')
                        ->sum('total_amount'),
                ],
            ]);
        });

        // Alias check-in routes — lebih intuitif untuk Postman
        Route::post('/checkin', [CheckinController::class, 'validateTicket']);
        Route::get('/checkin/history', [CheckinController::class, 'history']);
    });

    Route::middleware(['auth:api', 'role:admin'])->prefix('admin')->group(function () {
        Route::get('/overview', function () {
            return response()->json([
                'success' => true,
                'message' => 'Admin overview berhasil diakses.',
                'data' => [
                    'total_users' => User::count(),
                    'total_events' => Event::count(),
                    'total_orders' => Order::count(),
                    'total_tickets' => Ticket::count(),
                    'total_api_logs' => ApiRequestLog::count(),
                    'total_revenue' => Order::where('payment_status', 'paid')->sum('total_amount'),
                ],
            ]);
        });
    });

    Route::middleware('basic.auth')->prefix('internal')->group(function () {
        Route::get('/system-report', function () {
            return response()->json([
                'success' => true,
                'message' => 'Internal system report berhasil diakses menggunakan Basic Auth.',
                'data' => [
                    'app' => 'Vokatif API',
                    'environment' => app()->environment(),
                    'users' => User::count(),
                    'events' => Event::count(),
                    'orders' => Order::count(),
                    'tickets' => Ticket::count(),
                    'api_logs' => ApiRequestLog::count(),
                    'server_time' => now(),
                ],
            ]);
        });
    });
});
