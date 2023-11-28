<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\TaskController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoleController;

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

// Protected Routes

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post("/logout", [AuthController::class, 'logout']);
    Route::get("/user-info", [AuthController::class, 'user_info']);
    Route::post("/change-password", [AuthController::class, 'changePassword']);
    Route::get("/tasks", [TaskController::class, 'index']);
    Route::get("/tasks/{id}", [TaskController::class, 'show']);
    Route::get("/tasks/search/{name}", [TaskController::class, 'search']);
    Route::post("/tasks", [TaskController::class, 'store']);
    Route::put("/tasks/{id}", [TaskController::class, 'update']);
    Route::delete("/tasks/{id}", [TaskController::class, 'destroy']);
    Route::get("/roles", [RoleController::class, 'index']);
    Route::post("/add-role", [RoleController::class, 'store']);
    Route::delete("/roles/{id}", [RoleController::class, 'destroy']);
    Route::post("/attach-task", [TaskController::class, 'attachTaskToUser']);
    Route::delete("/task/{task_id}", [TaskController::class, 'detachTask']);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

