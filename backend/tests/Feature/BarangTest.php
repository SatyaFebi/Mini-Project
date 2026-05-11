<?php

use App\Models\User;
use App\Models\Barang;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('authenticated user can list barang', function () {
    $user = User::factory()->create();
    
    Barang::create([
        'KODE' => 'B001',
        'NAMA' => 'Buku Tulis',
        'KATEGORI' => 'ATK',
        'HARGA' => 15000
    ]);

    $response = $this->actingAs($user, 'sanctum')
                     ->getJson('/api/v1/master/barang');

    $response->assertStatus(200)
             ->assertJsonStructure([
                 'data' => [
                     '*' => ['id', 'kode', 'nama', 'kategori', 'harga']
                 ]
             ]);
});

test('authenticated user can create barang', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user, 'sanctum')
                     ->postJson('/api/v1/master/barang', [
                         'kode' => 'B001',
                         'nama' => 'Buku Tulis',
                         'kategori' => 'ATK',
                         'harga' => 15000,
                     ]);

    $response->assertStatus(201)
             ->assertJsonPath('data.nama', 'Buku Tulis');
});

test('authenticated user can update barang', function () {
    $user = User::factory()->create();
    $barang = Barang::create([
        'KODE' => 'B001',
        'NAMA' => 'Buku Tulis',
        'KATEGORI' => 'ATK',
        'HARGA' => 15000
    ]);

    $response = $this->actingAs($user, 'sanctum')
                     ->putJson("/api/v1/master/barang/{$barang->id}", [
                         'nama' => 'Buku Tulis Updated',
                     ]);

    $response->assertStatus(200)
             ->assertJsonPath('data.nama', 'Buku Tulis Updated');
});

test('authenticated user can delete barang', function () {
    $user = User::factory()->create();
    $barang = Barang::create([
        'KODE' => 'B001',
        'NAMA' => 'Buku Tulis',
        'KATEGORI' => 'ATK',
        'HARGA' => 15000
    ]);

    $response = $this->actingAs($user, 'sanctum')
                     ->deleteJson("/api/v1/master/barang/{$barang->id}");

    $response->assertStatus(200);
    $this->assertSoftDeleted('barangs', ['id' => $barang->id]);
});
