<?php

use App\Models\User;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('login thành công với đúng thông tin', function () {
    User::factory()->create([
        'email'    => 'test@petcare.test',
        'password' => bcrypt('password123'),
    ]);

    $response = $this->postJson('/api/v1/auth/login', [
        'email'    => 'test@petcare.test',
        'password' => 'password123',
    ]);

    $response->assertStatus(200)
             ->assertJsonPath('success', true)
             ->assertJsonStructure([
                 'data' => ['access_token', 'refresh_token', 'token_type', 'user'],
             ]);
});

test('login thất bại với sai password', function () {
    User::factory()->create([
        'email'    => 'test@petcare.test',
        'password' => bcrypt('password123'),
    ]);

    $response = $this->postJson('/api/v1/auth/login', [
        'email'    => 'test@petcare.test',
        'password' => 'wrong',
    ]);

    $response->assertStatus(401)
             ->assertJsonPath('success', false);
});

test('register tạo user mới với role customer', function () {
    $response = $this->postJson('/api/v1/auth/register', [
        'name'                  => 'New User',
        'email'                 => 'new@petcare.test',
        'password'              => 'password123',
        'password_confirmation' => 'password123',
    ]);

    $response->assertStatus(201)
             ->assertJsonPath('data.user.role', 'customer');

    $this->assertDatabaseHas('users', ['email' => 'new@petcare.test']);
});
