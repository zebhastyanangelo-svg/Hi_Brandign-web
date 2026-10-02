<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\CommunityController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\MembershipController;
use Illuminate\Support\Facades\Route;

Route::get('/', [MembershipController::class, 'index'])->name('home');
Route::get('/memberships', [MembershipController::class, 'plans'])->name('memberships.index');
Route::view('/location', 'location.index')->name('location.index');
Route::get('/appointments', [AppointmentController::class, 'index'])->name('appointments.index');
Route::post('/appointments', [AppointmentController::class, 'store'])->name('appointments.store');

Route::get('/community', [CommunityController::class, 'index'])->name('community.index');
Route::get('/events', [EventController::class, 'index'])->name('events.index');
