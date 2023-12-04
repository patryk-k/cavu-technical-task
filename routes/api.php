<?php

use App\Http\Controllers\AvailabilityController;
use App\Http\Controllers\BookingController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('availability', AvailabilityController::class);

Route::post('booking', [BookingController::class, 'store'])->name('booking.store');

Route::middleware('auth:sanctum')
    ->prefix('booking/{booking}')
    ->name('booking.')
    ->group(function () {
        Route::put('', [BookingController::class, 'update'])->name('update');
        Route::delete('', [BookingController::class, 'destroy'])->name('destroy');
    });
