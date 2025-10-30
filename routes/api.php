<?php

use Illuminate\Support\Facades\Route;

// Auth
use App\Http\Controllers\AuthController;

// Dashboard
use App\Http\Controllers\DashboardController;

// Notification
use App\Http\Controllers\Settings\NotificationController;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::post('/admin-login', [AuthController::class, 'adminLogin']);

// Protected routes
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::patch('/change-password', [AuthController::class, 'changePassword']);
    Route::get('/me', [AuthController::class, 'getUser']);
    Route::get('/profile', [AuthController::class, 'getUserProfile']);

    Route::get('/dashboard-data', [DashboardController::class, 'getStudentDashboardData']);

    Route::get('/notifications', [NotificationController::class, 'getNotifications']);
    Route::patch('/notifications/{id}/read', [NotificationController::class, 'markNotificationAsRead']);
    Route::patch('/notifications/read-all', [NotificationController::class, 'markAllNotificationsAsRead']);

    // Protectetd - Role: Student
    Route::middleware(['role:student'])->group(function () {
        Route::get('/savings-statistics', [DashboardController::class, 'getSavingsStatistics']);
        Route::get('/savings-histories', [DashboardController::class, 'getSavingsHistories']);
    });

    // Protectetd - Role: Teacher
    Route::middleware(['role:teacher'])->group(function () {

    });

    // Protectetd - Role: Admin
    Route::middleware(['role:admin'])->group(function () {

    });
});
