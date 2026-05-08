<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Master\PelangganController;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Master\BarangController;

Route::prefix('v1')->group(function () {
    // Auth Routes
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me', [AuthController::class, 'me']);

        // Protected Resource Routes
        Route::prefix('pelanggan')->group(function () {
            Route::get('/', [PelangganController::class, 'index']);
            Route::post('/', [PelangganController::class, 'store']);
            Route::put('/{id}', [PelangganController::class, 'update']);
        });

        Route::prefix('barang')->group(function () {
            Route::get('/', [BarangController::class, 'index']);
            Route::post('/', [BarangController::class, 'store']);
            Route::put('/{id}', [BarangController::class, 'update']);
        });
    });
});