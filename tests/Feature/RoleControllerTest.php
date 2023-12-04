<?php

use App\Models\Role;

it('can store a role', function () {
    $roleData = [
        'name' => 'Test11',
        'slug' => 'test11',
    ];

    // Send a POST request to store the role
    $response = $this->postJson('/api/add-role', $roleData);

    // Assert that the response is successful (status code 201)
    $response->assertStatus(201);

    // Assert that the role was created in the database
    $this->assertDatabaseHas('roles', $roleData);
});

it('can list roles', function () {
    $this->getJson('/api/roles')->assertStatus(200);
});

it('can destroy a role', function () {
    // Create a role for testing
    $role = Role::create([
        'name' => 'delete',
        'slug' => 'delete',
    ]);

    // Send a DELETE request to destroy the role
    $response = $this->deleteJson("/api/roles/{$role->id}");

    // Assert that the response is successful (status code 200)
    $response->assertStatus(200);

    // Assert that the role was deleted from the database
    $this->assertDatabaseMissing('roles', ['id' => $role->id]);
});
