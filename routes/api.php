<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\TaskController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public Routes

Route::post("/register", [AuthController::class, 'register']);
Route::post("/login", [AuthController::class, 'login']);
// Route::get("/roles", [RoleController::class, 'index']);
Route::post("/add-role", [RoleController::class, 'store']);
Route::delete("/roles/{id}", [RoleController::class, 'destroy']);
Route::get("/tasks", [TaskController::class, 'index']);


// Protected Routes

Route::group(['middleware' => ['auth:sanctum']], function () {

    // Auth API

    Route::post("/logout", [AuthController::class, 'logout']);
    Route::get("/user-info", [AuthController::class, 'userInfo']);
    Route::post("/change-password", [AuthController::class, 'changePassword']);

    // User API

    Route::get("/users-list", [UserController::class, 'usersList'])->middleware('admin');
    Route::get("/users/{id}", [UserController::class, 'show'])->middleware('admin');
    Route::get("/users/search/{name}", [UserController::class, 'search']);
    Route::put("/users/{id}", [UserController::class, 'update'])->middleware('admin');
    Route::delete("/users/{id}", [UserController::class, 'destroy'])->middleware('admin');

    // Tasks API

    // Route::get("/tasks", [TaskController::class, 'index']);
    Route::get("/tasks/{id}", [TaskController::class, 'show'])->middleware('admin');
    Route::get("/tasks/search/{name}", [TaskController::class, 'search']);
    Route::post("/tasks", [TaskController::class, 'store'])->middleware('admin');
    Route::put("/tasks/{id}", [TaskController::class, 'update'])->middleware('admin');
    Route::delete("/tasks/{id}", [TaskController::class, 'destroy'])->middleware('admin');

    // Roles API

    Route::get("/roles", [RoleController::class, 'index'])->middleware('admin');
    // Route::post("/add-role", [RoleController::class, 'store'])->middleware('admin');
    // Route::delete("/roles/{id}", [RoleController::class, 'destroy'])->middleware('admin');

    // Attach and Detach Tasks to users API

    Route::post("/attach-task", [TaskController::class, 'attachTaskToUser'])->middleware('admin');
    Route::delete("/task/{task_id}", [TaskController::class, 'detachTask'])->middleware('admin');
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

