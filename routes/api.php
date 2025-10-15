<?php

use Illuminate\Support\Facades\Route;

// Auth
use App\Http\Controllers\AuthController;

// Dashboard
use App\Http\Controllers\DashboardController;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// Protected routes
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::patch('/change-password', [AuthController::class, 'changePassword']);
    Route::get('/me', [AuthController::class, 'getUser']);

    Route::get('/dashboard-data', [DashboardController::class, 'getStudentDashboardData']);

    // Protectetd - Role: Student
    Route::middleware(['role:student'])->group(function () {
        Route::get('/savings-statistics', [DashboardController::class, 'getSavingsStatistics']);
    });

    // Protectetd - Role: Teacher
    Route::middleware(['role:teacher'])->group(function () {

    });

    // Protectetd - Role: Admin
    Route::middleware(['role:admin'])->group(function () {

    });
});
