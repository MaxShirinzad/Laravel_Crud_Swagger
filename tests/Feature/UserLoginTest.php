<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

const TEST_EMAIL = 'test2@example.com';
const TEST_PASSWORD = 'password123';

uses(TestCase::class);
uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create([
        'email' => TEST_EMAIL,
        'password' => Hash::make(TEST_PASSWORD),
    ]);
});

it('can login with valid credentials', function () {
    $response = $this->postJson('/api/login', [
        'email' => TEST_EMAIL,
        'password' => TEST_PASSWORD,
    ]);

    $response
        ->assertOk()
        ->assertJsonStructure([
            'user' => ['id', 'name', 'email'],
            'access_token',
            'token_type',
            'expires_in',
        ])
        ->assertJson([
            'token_type' => 'Bearer',
            'user' => [
                'email' => TEST_EMAIL,
            ],
        ]);
});

it('returns validation error for invalid email', function () {
    $response = $this->postJson('/api/login', [
        'email' => 'wrong@example.com',
        'password' => TEST_PASSWORD,
    ]);

    $response
        ->assertStatus(422)
        ->assertJsonValidationErrors(['email'])
        ->assertJson([
            'message' => 'Provided email or password is incorrect.',
            'errors' => [
                'email' => ['Provided email or password is incorrect'],
            ],
        ]);
});

    it('returns validation error for invalid password', function () {
        $response = $this->postJson('/api/login', [
            'email' => TEST_EMAIL,
            'password' => 'wrongpassword',
        ]);

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors(['email'])
            ->assertJson([
                'message' => 'The provided credentials are incorrect.',
                'errors' => [
                    'email' => ['The provided credentials are incorrect.'],
                ],
            ]);
    });

it('requires email and password', function () {
    $response = $this->postJson('/api/login', []);

    $response
        ->assertStatus(422)
        ->assertJsonValidationErrors(['email', 'password']);
});

//it('revokes previous tokens on new login', function () {
//    // First login
//    $firstResponse = $this->postJson('/api/login', [
//        'email' => TEST_EMAIL,
//        'password' => TEST_PASSWORD,
//    ]);
//    $firstToken = $firstResponse->json('access_token');
//
//    //dd($firstResponse->json());
//
//    // Second login should revoke first token
//    $secondResponse = $this->postJson('/api/login', [
//        'email' => TEST_EMAIL,
//        'password' => TEST_PASSWORD,
//    ]);
//    $secondToken = $secondResponse->json('access_token');
//
//    // Verify first token is no longer valid
//    $checkResponse = $this->withHeaders([
//        'Authorization' => 'Bearer ' . $firstToken,
//    ])->getJson('/api/user');
//
//    $checkResponse->assertStatus(404);
//
//    // Verify new token works
//    $checkResponse = $this->withHeaders([
//        'Authorization' => 'Bearer ' . $secondToken,
//    ])->getJson('/api/user');
//
//    $checkResponse->assertOk();
//});

it('returns 429 status code after too many attempts', function () {
    for ($i = 0; $i < 5; $i++) {
        $this->postJson('/api/login', [
            'email' => TEST_EMAIL,
            'password' => 'wrongpassword',
        ]);
    }

    $response = $this->postJson('/api/login', [
        'email' => TEST_EMAIL,
        'password' => 'wrongpassword',
    ]);

    $response
        ->assertStatus(429)
        ->assertJson([
            'message' => 'Too Many Attempts.',
        ]);
});

