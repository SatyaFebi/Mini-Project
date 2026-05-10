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

    // Public POS Routes
    Route::get('master/pelanggan/all', [PelangganController::class, 'all']);
    Route::get('master/barang/all', [BarangController::class, 'all']);
    Route::post('transaksi/penjualan', [PenjualanController::class, 'store']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me', [AuthController::class, 'me']);

         Route::prefix('master')->group(function() {
         Route::apiResource('pelanggan', PelangganController::class);
         Route::apiResource('barang', BarangController::class);
      });

      Route::prefix('transaksi')->group(function () {
         Route::apiResource('penjualan', PenjualanController::class)->except(['store']);
      });
    });
});