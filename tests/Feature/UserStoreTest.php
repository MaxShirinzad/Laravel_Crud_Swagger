<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

use Illuminate\Support\Facades\Cache;
use function Pest\Laravel\postJson;

use App\Models\User;

use Tests\TestCase;

uses(TestCase::class);
uses(RefreshDatabase::class);

beforeEach(function () {
    //Storage::fake('public');
    //Cache::flush();
});

test('User can register with valid data', function () {
    $response = $this->postJson('/api/signup', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'Password123!',
        'password_confirmation' => 'Password123!',
    ]);

    $response->assertStatus(201);
    $response->assertJsonStructure([
            'user' => ['id', 'name', 'email']
        ]);
    $this->assertDatabaseHas('users', ['email' => 'test@example.com']);

});

test('Registration fails with invalid data', function () {
    $response = $this->postJson('/api/signup', [
        'name' => '',
        'email' => 'not-an-email',
        'password' => 'short',
        'password_confirmation' => 'mismatch',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['name', 'email', 'password']);
});


//test('user can register with image', function () {
//    $file = UploadedFile::fake()->image('avatar.jpg');
//
//    $response = $this->post('/api/signup', [
//        'name' => 'Test User',
//        'email' => 'test@example.com',
//        'password' => 'Password123!',
//        'password_confirmation' => 'Password123!',
//        'image' => $file,
//    ]);
//
//    $response->assertStatus(201);
//    $this->assertNotNull($response->json('data.image'));
//    Storage::disk('public')->assertExists('users/' . $file->hashName());
//});


