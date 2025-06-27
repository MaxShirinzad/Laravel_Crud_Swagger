<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

uses(RefreshDatabase::class);

beforeEach(function () {
    Storage::fake('public');
});

test('user can register with valid data', function () {
    $response = $this->postJson('/api/users', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'Password123!',
        'password_confirmation' => 'Password123!',
    ]);

    $response->assertStatus(201)
        ->assertJsonStructure([
            'data' => ['id', 'name', 'email', 'image_url']
        ]);
});

test('user can register with image', function () {
    $file = UploadedFile::fake()->image('avatar.jpg');

    $response = $this->post('/api/users', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'Password123!',
        'password_confirmation' => 'Password123!',
        'image' => $file,
    ]);

    $response->assertStatus(201);
    $this->assertNotNull($response->json('data.image_url'));
    Storage::disk('public')->assertExists('users/' . $file->hashName());
});

test('registration fails with invalid data', function () {
    $response = $this->postJson('/api/users', [
        'name' => '',
        'email' => 'not-an-email',
        'password' => 'short',
        'password_confirmation' => 'mismatch',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['name', 'email', 'password']);
});
