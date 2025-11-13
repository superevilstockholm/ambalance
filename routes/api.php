<?php

use Illuminate\Support\Facades\Route;

// Auth
use App\Http\Controllers\AuthController;

// Profile
use App\Http\Controllers\ProfileController;

// Dashboard
use App\Http\Controllers\StudentDashboardController;

// Notification
use App\Http\Controllers\Settings\NotificationController;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::post('/admin-login', [AuthController::class, 'adminLogin']);

// Protected routes
Route::middleware(['auth:sanctum'])->group(function () {
    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::patch('/change-password', [AuthController::class, 'changePassword']);

    // Profile
    Route::get('/me', [ProfileController::class, 'getUser']);
    Route::get('/profile', [ProfileController::class, 'getUserProfile']);
    Route::patch('/profile', [ProfileController::class, 'editUserProfile']);

    // Notification
    Route::get('/notifications', [NotificationController::class, 'getNotifications']);
    Route::patch('/notifications/{id}/read', [NotificationController::class, 'markNotificationAsRead']);
    Route::patch('/notifications/read-all', [NotificationController::class, 'markAllNotificationsAsRead']);

    // Protectetd - Role: Student
    Route::middleware(['role:student'])->group(function () {
        Route::get('/dashboard-data', [StudentDashboardController::class, 'getStudentDashboardData']);
        Route::get('/savings-statistics', [StudentDashboardController::class, 'getSavingsStatistics']);
        Route::get('/savings-histories', [StudentDashboardController::class, 'getSavingsHistories']);
    });

    // Protectetd - Role: Teacher
    Route::middleware(['role:teacher'])->group(function () {

    });

    // Protectetd - Role: Admin
    Route::middleware(['role:admin'])->group(function () {

    });
});
