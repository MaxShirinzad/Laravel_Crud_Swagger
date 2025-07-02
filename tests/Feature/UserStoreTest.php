<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

use Illuminate\Support\Facades\Cache;
use Laravel\Sanctum\Sanctum;
use function Pest\Laravel\postJson;

use App\Models\User;

use Tests\TestCase;

uses(TestCase::class);
//uses(RefreshDatabase::class);

beforeEach(function () {
    Artisan::call('migrate:fresh --seed');
    //Storage::fake('public');
    //Cache::flush();
});

function createUser(array $overrides = []): User
{
    return User::query()->create(array_merge([
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'Password123!',
        'password_confirmation' => 'Password123!',
    ], $overrides));
}

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

//test('allows a user to login and access protected route', function () {
//    $response = $this->postJson('/api/login', [
//        "email" => 'user@site.com',
//        "password" => '1234567890'
//    ]);
//
//    //dd($response->json()['user']['email']);
//
//    $response->assertStatus(200);
//    $token = $response->json('token');
//
////    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
////        ->getJson('/api/user')
////        ->assertJsonPath('user.email', 'user@site.com');
//
//    $this->withHeader('Authorization', 'Bearer ' . $token)
//        ->getJson('/api/user')
//        //->assertOk()
//        ->assertJson(['email' => 'user@site.com']);
//});

//it('authenticates a user and creates another user', function () {
//    // Step 1: Create the first user and log in
//    $user = User::factory()->create([
//        'password' => bcrypt('password'), // Set the password
//    ]);
//
//
////    $user = User::find(1);
//
//    // Use Sanctum to login
//    Sanctum::actingAs($user);
//
//    // Step 2: Create another user (assuming you have an API endpoint for this)
//    $newUserData = [
//        'name' => 'New User',
//        'email' => 'newuser@example.com',
//        'password' => 'newpassword', // Use hashed password if necessary
//        'password_confirmation' => 'newpassword',
//    ];
//
//    // Making an API request to create a new user
//    $response = $this->postJson('/api/users', $newUserData);
//
//    // Assert the response
//    $response->assertStatus(201);
//    $this->assertDatabaseHas('users', [
//        'email' => 'newuser@example.com',
//    ]);
//});


//test('user can register with image', function () {
//    $file = UploadedFile::fake()->image('avatar.jpg');
//
//    $response = $this->post('/api/users', [
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


