<?php
// tests/Feature/AuthControllerTest.php

use App\Models\User;

it('can register a user with valid credentials', function () {
    // Create a user for testing
    $user = User::factory()->create([
        'email' => 'test17@gmail.com',
        'password' => bcrypt('1234567890'),
        'role_id' => '1',
    ]);

    // Make a request to the login endpoint
    $loginResponse = $this->postJson('/api/login', [
        'email' => 'test17@gmail.com',
        'password' => '1234567890',
    ]);

    // Assert that the response is successful (status code 200)
    $loginResponse->assertStatus(200);

    // Assert the structure of the JSON response
    $loginResponse->assertJsonStructure([
        'user' => [
            'id',
            'first_name',
            'last_name',
            'email',
            'phone',
            'address',
            'country',
            'role_id',
            'updated_at',
            'created_at',
        ],
        'token',
    ]);

    // Extract the token from the login response
    $loginToken = $loginResponse->json('token');

    // Assert that the user's token was created
    $this->assertDatabaseHas('personal_access_tokens', [
        'tokenable_id' => $user->id,
    ]);

    dd($loginToken);

    // Assert the response for your protected endpoint
    $loginResponse->assertStatus(200);
});

it('returns an error for invalid credentials', function () {
    // Make a request with invalid credentials
    $response = $this->postJson('/api/login', [
        'email' => 'nonexistent@gmail.com',
        'password' => 'password',
    ]);

    // Assert that the response indicates invalid credentials (status code 422)
    $response->assertStatus(422);

    // Assert that the response contains the expected error message
    $response->assertJson([
        'message' => 'The selected email is invalid.',
    ]);
});
