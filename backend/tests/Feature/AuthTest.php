<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('user can register', function () {
    $response = $this->postJson('/api/v1/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response->assertStatus(200)
             ->assertJsonStructure([
                 'data' => [
                     'user' => ['id', 'name', 'email'],
                     'access_token',
                     'token_type'
                 ],
                 'message'
             ]);
});

test('user can login', function () {
    $user = User::factory()->create([
        'password' => bcrypt('password'),
    ]);

    $response = $this->postJson('/api/v1/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $response->assertStatus(200)
             ->assertJsonStructure([
                 'data' => [
                     'user' => ['id', 'name', 'email'],
                     'access_token',
                     'token_type'
                 ],
                 'message'
             ]);
});

test('user can get profile', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user, 'sanctum')
                     ->getJson('/api/v1/me');

    $response->assertStatus(200)
             ->assertJsonPath('email', $user->email);
});

test('user can logout', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user, 'sanctum')
                     ->postJson('/api/v1/logout');

    $response->assertStatus(200)
             ->assertJsonPath('message', 'Berhasil keluar');
});
