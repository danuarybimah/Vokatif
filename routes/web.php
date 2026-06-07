<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\EventPageController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\TicketPageController;
use App\Http\Controllers\Web\OrderPageController;
use App\Http\Controllers\Web\OrganizerEventController;
use App\Http\Controllers\Web\AdminController;
use App\Http\Controllers\Web\Auth\LoginController;
use App\Http\Controllers\Web\CheckinPageController;
use App\Http\Controllers\Web\UserPageController;
use App\Http\Controllers\Web\ProfileController;
use App\Http\Controllers\Web\CertificateController;
use App\Http\Controllers\Web\ContactController;

/*
|--------------------------------------------------------------------------
| PUBLIC
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])
    ->name('home');

Route::get('/events', [EventPageController::class, 'index'])
    ->name('events.index');

Route::get('/events/{slug}', [EventPageController::class, 'show'])
    ->name('events.show');

Route::get('/my-ticket/{ticketCode}', [TicketPageController::class, 'show'])
    ->name('ticket.show');

// Public: contact form submit (AJAX, no auth needed)
Route::post('/contact', [ContactController::class, 'store'])
    ->name('contact.store');

/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {

    Route::get('/login', [LoginController::class, 'showLogin'])
        ->name('login');

    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [LoginController::class, 'showRegister'])->name('register');
    Route::post('/register', [LoginController::class, 'register']);
});

Route::get('/admin/analytics', [DashboardController::class, 'adminAnalytics'])
    ->name('admin.analytics');

Route::get('/organizer/analytics', [DashboardController::class, 'organizerAnalytics'])
    ->name('organizer.analytics');

Route::post('/logout', [LoginController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

/*
|--------------------------------------------------------------------------
| AUTHENTICATED
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | DASHBOARD
    |--------------------------------------------------------------------------
    */

    Route::get('/organizer/dashboard', [DashboardController::class, 'organizer'])
        ->name('organizer.dashboard');

    Route::get('/admin/dashboard', [DashboardController::class, 'admin'])
        ->name('admin.dashboard');

    /*
    |--------------------------------------------------------------------------
    | USER TICKETS
    |--------------------------------------------------------------------------
    */

    Route::get('/my-tickets', [TicketPageController::class, 'index'])
        ->name('tickets.index');

    Route::get('/my-tickets/{ticketCode}', [TicketPageController::class, 'show'])
        ->name('tickets.show');

    Route::get('/upcoming',      [UserPageController::class, 'upcoming'])->name('user.upcoming');
    Route::get('/transactions',  [UserPageController::class, 'transactions'])->name('user.transactions');
    Route::get('/qr-ticket',     [UserPageController::class, 'qrTicket'])->name('user.qr-ticket');

    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::get('/home', [UserPageController::class, 'home'])->name('user.home');

    // Certificate download
    Route::get('/my-tickets/{ticketCode}/certificate', [CertificateController::class, 'download'])
        ->name('ticket.certificate');

    /*
    |--------------------------------------------------------------------------
    | BUY TICKET
    |--------------------------------------------------------------------------
    */

    Route::post('/buy-ticket', [OrderPageController::class, 'store'])
        ->name('buy.ticket');

    /*
    |--------------------------------------------------------------------------
    | ORGANIZER
    |--------------------------------------------------------------------------
    */

    Route::prefix('organizer')->group(function () {

        Route::get('/events', [OrganizerEventController::class, 'index'])
            ->name('organizer.events.index');

        Route::get('/checkin-participants', [OrganizerEventController::class, 'checkinParticipants'])
            ->name('organizer.checkin.participants');

        Route::get('/events/create', [OrganizerEventController::class, 'create'])
            ->name('organizer.events.create');

        Route::post('/events', [OrganizerEventController::class, 'store'])
            ->name('organizer.events.store');

        Route::get('/events/{id}/edit', [OrganizerEventController::class, 'edit'])
            ->name('organizer.events.edit');

        Route::put('/events/{id}', [OrganizerEventController::class, 'update'])
            ->name('organizer.events.update');

        Route::delete('/events/{id}', [OrganizerEventController::class, 'destroy'])
            ->name('organizer.events.destroy');

        Route::get('/checkin-scanner', [CheckinPageController::class, 'index'])
            ->name('organizer.checkin.scanner');

        Route::post('/checkin', [CheckinPageController::class, 'checkin'])
            ->name('organizer.checkin');
    });

    /*
    |--------------------------------------------------------------------------
    | ADMIN
    |--------------------------------------------------------------------------
    */

    Route::prefix('admin')->group(function () {

        Route::get('/users', [AdminController::class, 'users'])
            ->name('admin.users');

        Route::put('/users/{id}/role', [AdminController::class, 'updateUserRole'])
            ->name('admin.users.role');

        Route::get('/events', [AdminController::class, 'events'])
            ->name('admin.events');

        Route::get('/events/create', [AdminController::class, 'createEvent'])
            ->name('admin.events.create');

        Route::post('/events', [AdminController::class, 'storeEvent'])
            ->name('admin.events.store');

        Route::get('/events/{id}/edit', [AdminController::class, 'editEvent'])
            ->name('admin.events.edit');

        Route::put('/events/{id}', [AdminController::class, 'updateEvent'])
            ->name('admin.events.update');

        Route::delete('/events/{id}', [AdminController::class, 'destroyEvent'])
            ->name('admin.events.destroy');

        // Admin messages inbox
        Route::get('/messages', [ContactController::class, 'index'])
            ->name('admin.messages');

        // Admin check-in QR scanner
        Route::get('/checkin-scanner', [CheckinPageController::class, 'index'])
            ->name('admin.checkin.scanner');
    });
});
