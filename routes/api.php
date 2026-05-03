<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\GroupController;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\ChecklistController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\TaskOverviewController;

// Auth routes
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
});

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Dashboard
    Route::get('/dashboard', DashboardController::class);

    // Auth
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // Groups
    Route::get('/groups', [GroupController::class, 'index']);
    Route::post('/groups', [GroupController::class, 'store']);
    Route::get('/groups/{group}', [GroupController::class, 'show']);
    Route::put('/groups/{group}', [GroupController::class, 'update']);
    Route::delete('/groups/{group}', [GroupController::class, 'destroy']);
    Route::post('/groups/join', [GroupController::class, 'join']);
    Route::post('/groups/{group}/leave', [GroupController::class, 'leave']);
    Route::get('/groups/{group}/members', [GroupController::class, 'members']);

    // Overview
    Route::get('/groups/{group}/overview', [TaskOverviewController::class, 'show']);
    Route::post('/groups/{group}/overview', [TaskOverviewController::class, 'store']);

    // Tasks
    Route::get('/groups/{group}/tasks', [TaskController::class, 'index']);
    Route::post('/groups/{group}/tasks', [TaskController::class, 'store']);
    Route::get('/tasks/{task}', [TaskController::class, 'show']);
    Route::match(['put', 'patch'], '/tasks/{task}', [TaskController::class, 'update']);
    Route::delete('/tasks/{task}', [TaskController::class, 'destroy']);
    Route::patch('/tasks/{task}/status', [TaskController::class, 'updateStatus']);

    // Checklists
    Route::get('/tasks/{task}/checklist', [ChecklistController::class, 'index']);
    Route::post('/tasks/{task}/checklist', [ChecklistController::class, 'store']);
    Route::patch('/checklists/{checklist}', [ChecklistController::class, 'update']);
    Route::patch('/checklists/{checklist}/toggle', [ChecklistController::class, 'toggle']);
    Route::delete('/checklists/{checklist}', [ChecklistController::class, 'destroy']);
});
