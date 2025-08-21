<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArmadaController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CheckinController;
use App\Http\Controllers\ShipmentController;

Route::get('/', function () {
    return view('layouts.index');
});

Route::prefix('armada')->group(function () {
    Route::get('/', [ArmadaController::class, 'index'])->name('armada.index');
    Route::post('/create', [ArmadaController::class, 'store'])->name('armada.store');
    Route::put('/{id}', [ArmadaController::class, 'update'])->name('armada.update');
    Route::delete('/{id}', [ArmadaController::class, 'destroy'])->name('armada.destroy');
});

Route::prefix('shipments')->group(function () {
    Route::get('/', [ShipmentController::class, 'index'])->name('shipments.index');
    Route::post('/create', [ShipmentController::class, 'store'])->name('shipments.store');
    Route::put('/{id}', [ShipmentController::class, 'update'])->name('shipments.update');
    Route::delete('/{id}', [ShipmentController::class, 'destroy'])->name('shipments.destroy');
    Route::get('/laporan', [ShipmentController::class, 'laporanPengiriman'])->name('shipments.laporan');
});

Route::prefix('bookings')->group(function () {
    Route::get('/', [BookingController::class, 'index'])->name('bookings.index');
    Route::post('/create', [BookingController::class, 'store'])->name('bookings.store');
    Route::delete('/{id}', [BookingController::class, 'destroy'])->name('bookings.destroy');
});

Route::prefix('checkins')->group(function () {
    Route::get('/', [CheckinController::class, 'index'])->name('checkins.index');
    Route::post('/create', [CheckinController::class, 'store'])->name('checkins.store');
    Route::delete('/{id}', [CheckinController::class, 'destroy'])->name('checkins.destroy');
});