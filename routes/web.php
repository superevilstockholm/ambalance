<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('pages.index');
})->name('index');
Route::get('/about', function () {

})->name('about');

// Optional protected routes
Route::middleware(['optional.auth.sanctum.cookie'])->group(function () {
    Route::get('/login', function (Request $request) {
        if ($request->user()) {
            return redirect(route($request->user()->role . '.dashboard'));
        }
        return response()->view('pages.auth.login', [
            'meta' => [
                'showNavbar' => false,
                'showFooter' => false
            ]
        ])->withoutCookie('auth_token', '/');
    })->name('login');
    Route::get('/register', function (Request $request) {
        if ($request->user()) {
            return redirect(route($request->user()->role . '.dashboard'));
        }

    })->name('register');
});

// Protected routes
Route::middleware(['auth.sanctum.cookie'])->group(function () {
    Route::prefix('dashboard')->group(function () {
        // Protectetd - Role: Student
        Route::middleware(['role:student'])->group(function () {
            Route::prefix('student')->group(function () {
                Route::get('/', function () {

                })->name('student.dashboard');
                Route::get('/savings-history', function () {

                })->name('student.savings-history');
            });
        });
        // Protectetd - Role: Teacher
        Route::middleware(['role:teacher'])->group(function () {
            Route::prefix('teacher')->group(function () {
                Route::get('/', function () {

                })->name('teacher.dashboard');
                Route::get('/students', function () {

                })->name('teacher.students');
            });
        });
        // Protectetd - Role: Admin
        Route::middleware(['role:admin'])->group(function () {
            Route::prefix('admin')->group(function () {
                Route::get('/', function () {

                })->name('admin.dashboard');
                Route::prefix('master-data')->group(function () {
                    Route::get('/classes', function () {

                    })->name('admin.master-data.classes');
                    Route::get('/students', function () {

                    })->name('admin.master-data.students');
                    Route::get('/teachers', function () {

                    })->name('admin.master-data.teachers');
                    Route::get('/savings', function () {

                    })->name('admin.master-data.savings');
                });
            });
        });
    });
});
