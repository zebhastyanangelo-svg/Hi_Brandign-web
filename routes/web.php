<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EventController as AdminEventController;
use App\Http\Controllers\Admin\IncomeController;
use App\Http\Controllers\Admin\MembershipController as AdminMembershipController;
use App\Http\Controllers\Admin\MembershipOrderController;
use App\Http\Controllers\Admin\PaymentMethodController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\CommunityController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\MembershipController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

Route::get('/storage/{path}', function (string $path) {
    abort_if(str_contains($path, '..'), 404);
    abort_unless(Storage::disk('public')->exists($path), 404);

    return Storage::disk('public')->response($path);
})->where('path', '.*')->name('storage.public');

Route::get('/', [MembershipController::class, 'index'])->name('home');
Route::get('/memberships', [MembershipController::class, 'plans'])->name('memberships.index');
Route::view('/location', 'location.index')->name('location.index');
Route::get('/appointments', [AppointmentController::class, 'index'])->name('appointments.index');
Route::post('/appointments', [AppointmentController::class, 'store'])->name('appointments.store');

Route::get('/community', [CommunityController::class, 'index'])->name('community.index');
Route::get('/events', [EventController::class, 'index'])->name('events.index');
Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');
Route::post('/events/{event}/checkout', [EventController::class, 'checkout'])->name('events.checkout');

Route::get('/memberships/checkout/confirmation/{notification:reference}', [MembershipController::class, 'checkoutConfirmation'])->name('memberships.checkout.confirmation');
Route::get('/memberships/checkout/{slug}', [MembershipController::class, 'checkout'])->name('memberships.checkout');
Route::post('/memberships/checkout/{slug}/notify', [MembershipController::class, 'notifyPayment'])->name('memberships.checkout.notify');
Route::post('/memberships/checkout/{slug}', [MembershipController::class, 'processCheckout'])->name('memberships.checkout.submit');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::middleware('admin.auth')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        Route::resource('events', AdminEventController::class);
        Route::resource('bookings', AdminBookingController::class)->only(['index', 'show']);
        Route::post('bookings/{booking}/approve', [AdminBookingController::class, 'approve'])->name('bookings.approve');
        Route::post('bookings/{booking}/reject', [AdminBookingController::class, 'reject'])->name('bookings.reject');

        Route::match(['put', 'patch'], 'memberships/{membership}', [AdminMembershipController::class, 'update'])->name('memberships.update');

        Route::post('membership-orders/{order}/approve', [MembershipOrderController::class, 'approve'])->name('membership-orders.approve');
        Route::post('membership-orders/{order}/reject', [MembershipOrderController::class, 'reject'])->name('membership-orders.reject');

        Route::get('incomes', [IncomeController::class, 'index'])->name('incomes.index');
        Route::post('incomes/{notification}/approve', [IncomeController::class, 'approve'])->name('incomes.approve');
        Route::post('incomes/{notification}/reject', [IncomeController::class, 'reject'])->name('incomes.reject');
        Route::post('incomes/notifications/{notification}/approve', [IncomeController::class, 'approve'])->name('incomes.notifications.approve');
        Route::post('incomes/notifications/{notification}/reject', [IncomeController::class, 'reject'])->name('incomes.notifications.reject');

        Route::resource('payment-methods', PaymentMethodController::class)->except(['show', 'create']);
        Route::post('payment-methods/{payment_method}/toggle', [PaymentMethodController::class, 'toggle'])->name('payment-methods.toggle');

        Route::get('clients', [ClientController::class, 'index'])->name('clients.index');
        Route::view('editorial-guide', 'admin.guide')->name('guide');
        Route::get('settings', [SettingController::class, 'edit'])->name('settings.edit');
        Route::post('settings/password', [SettingController::class, 'updatePassword'])->name('settings.password');
        Route::get('reports/payments', [ReportController::class, 'payments'])->name('reports.payments');
    });
});
