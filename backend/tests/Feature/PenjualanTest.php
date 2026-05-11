<?php

use App\Models\User;
use App\Models\Pelanggan;
use App\Models\Barang;
use App\Models\Penjualan;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('authenticated user can list penjualan', function () {
    $user = User::factory()->create();
    
    $pelanggan = Pelanggan::create([
        'NAMA' => 'ANDI',
        'DOMISILI' => 'JAK-UT',
        'JENIS_KELAMIN' => 'PRIA'
    ]);

    $barang = Barang::create([
        'KODE' => 'B001',
        'NAMA' => 'Buku Tulis',
        'KATEGORI' => 'ATK',
        'HARGA' => 15000
    ]);

    // Create a penjualan via request
    $this->postJson('/api/v1/transaksi/penjualan', [
        'tgl' => '2026-05-11',
        'kode_pelanggan' => $pelanggan->ID_PELANGGAN,
        'items' => [
            ['kode_barang' => 'B001', 'qty' => 2]
        ]
    ]);

    $response = $this->actingAs($user, 'sanctum')
                     ->getJson('/api/v1/transaksi/penjualan');

    $response->assertStatus(200)
             ->assertJsonStructure([
                 'data' => [
                     '*' => ['id', 'id_nota', 'tgl', 'kode_pelanggan', 'subtotal']
                 ]
             ]);
});

test('user can create penjualan via public POS route', function () {
    $pelanggan = Pelanggan::create([
        'NAMA' => 'ANDI',
        'DOMISILI' => 'JAK-UT',
        'JENIS_KELAMIN' => 'PRIA'
    ]);

    $barang = Barang::create([
        'KODE' => 'B001',
        'NAMA' => 'Buku Tulis',
        'KATEGORI' => 'ATK',
        'HARGA' => 15000
    ]);

    $response = $this->postJson('/api/v1/transaksi/penjualan', [
        'tgl' => '2026-05-11',
        'kode_pelanggan' => $pelanggan->ID_PELANGGAN,
        'items' => [
            ['kode_barang' => 'B001', 'qty' => 2]
        ]
    ]);

    $response->assertStatus(201)
             ->assertJsonPath('data.subtotal', 30000); // 15000 * 2
});
