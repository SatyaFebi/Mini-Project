<?php

use App\Models\User;
use App\Models\Pelanggan;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('authenticated user can list pelanggan', function () {
    $user = User::factory()->create();
    
    Pelanggan::create([
        'NAMA' => 'ANDI',
        'DOMISILI' => 'JAK-UT',
        'JENIS_KELAMIN' => 'PRIA'
    ]);

    $response = $this->actingAs($user, 'sanctum')
                     ->getJson('/api/v1/master/pelanggan');

    $response->assertStatus(200)
             ->assertJsonStructure([
                 'data' => [
                     '*' => ['id', 'id_pelanggan', 'nama', 'domisili', 'jenis_kelamin']
                 ]
             ]);
});

test('authenticated user can create pelanggan', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user, 'sanctum')
                     ->postJson('/api/v1/master/pelanggan', [
                         'nama' => 'John Doe',
                         'domisili' => 'Jakarta',
                         'jenis_kelamin' => 'PRIA',
                     ]);

    $response->assertStatus(201)
             ->assertJsonPath('data.nama', 'John Doe');
});

test('authenticated user can update pelanggan', function () {
    $user = User::factory()->create();
    $pelanggan = Pelanggan::create([
        'NAMA' => 'ANDI',
        'DOMISILI' => 'JAK-UT',
        'JENIS_KELAMIN' => 'PRIA'
    ]);

    $response = $this->actingAs($user, 'sanctum')
                     ->putJson("/api/v1/master/pelanggan/{$pelanggan->id}", [
                         'nama' => 'Jane Doe',
                     ]);

    $response->assertStatus(200)
             ->assertJsonPath('data.nama', 'Jane Doe');
});

test('authenticated user can delete pelanggan', function () {
    $user = User::factory()->create();
    $pelanggan = Pelanggan::create([
        'NAMA' => 'ANDI',
        'DOMISILI' => 'JAK-UT',
        'JENIS_KELAMIN' => 'PRIA'
    ]);

    $response = $this->actingAs($user, 'sanctum')
                     ->deleteJson("/api/v1/master/pelanggan/{$pelanggan->id}");

    $response->assertStatus(200);
    $this->assertSoftDeleted('pelanggans', ['id' => $pelanggan->id]);
});
