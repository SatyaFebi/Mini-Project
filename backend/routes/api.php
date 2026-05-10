<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Master\PelangganController;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Master\BarangController;
use App\Http\Controllers\Api\Transaksi\PenjualanController;

Route::prefix('v1')->group(function () {
    // Auth Routes
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me', [AuthController::class, 'me']);

         Route::prefix('master')->group(function() {
         Route::get('pelanggan/all', [PelangganController::class, 'all']);
         Route::apiResource('pelanggan', PelangganController::class);

         Route::get('barang/all', [BarangController::class, 'all']);
         Route::apiResource('barang', BarangController::class);
      });

      Route::prefix('transaksi')->group(function () {
         Route::apiResource('penjualan', PenjualanController::class);
      });
    });
});