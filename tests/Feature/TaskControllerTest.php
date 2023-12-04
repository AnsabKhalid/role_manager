<?php

use App\Models\Task;

it('can list tasks', function () {
    $this->getJson('/api/tasks')->assertStatus(200);
});

// it('can store a task', function () {
//     $task = [
//         'name' => 'Test2',
//         'status' => 'pending',
//     ];

//     // Send a POST request to store the tasks
//     $response = $this->postJson('/api/tasks', $task);

//     // Assert that the response is successful (status code 201)
//     $response->assertStatus(201);

//     // Assert that the task was created in the database
//     $this->assertDatabaseHas('tasks', $task);
// });