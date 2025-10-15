<?php

use Illuminate\Support\Facades\Route;

// Auth
use App\Http\Controllers\AuthController;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::post('/admin/login', [AuthController::class, 'adminLogin']);

// Protected routes
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::patch('/change-password', [AuthController::class, 'changePassword']);

    // Protectetd - Role: Student
    Route::middleware(['role:student'])->group(function () {

    });

    // Protectetd - Role: Teacher
    Route::middleware(['role:teacher'])->group(function () {

    });

    // Protectetd - Role: Admin
    Route::middleware(['role:admin'])->group(function () {

    });
});
