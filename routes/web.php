<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

Route::get('lang/{locale}', function ($locale) {
    if (!in_array($locale, ['en', 'id'])) { abort(400); }
    App::setLocale($locale);
    Session::put('locale', $locale);
    return redirect()->back();
})->name('switch-lang');

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
    Route::get('/admin-login', function (Request $request) {
        if ($request->user()) {
            return redirect(route($request->user()->role . '.dashboard'));
        }
        return response()->view('pages.auth.admin-login', [
            'meta' => [
                'showNavbar' => false,
                'showFooter' => false
            ]
        ])->withoutCookie('auth_token', '/');
    })->name('admin.login');
    Route::get('/register', function (Request $request) {
        if ($request->user()) {
            return redirect(route($request->user()->role . '.dashboard'));
        }
        return response()->view('pages.auth.register', [
            'meta' => [
                'showNavbar' => false,
                'showFooter' => false
            ]
        ])->withoutCookie('auth_token', '/');
    })->name('register');
});

// Protected routes
Route::middleware(['auth.sanctum.cookie'])->group(function () {
    Route::prefix('student')->group(function () {
        // Protectetd - Role: Student
        Route::middleware(['role:student'])->group(function () {
            Route::get('/', function () {
                return view('pages.dashboard.student.index', [
                    'meta' => ['sidebarItems' => studentSidebarItems()]
                ]);
            })->name('student.dashboard');
            Route::get('/statistics', function () {
                return view('pages.dashboard.student.statistics', [
                    'meta' => ['sidebarItems' => studentSidebarItems()]
                ]);
            })->name('student.statistics');
            Route::get('/savings-history', function () {
                return view('pages.dashboard.student.savings-history', [
                    'meta' => ['sidebarItems' => studentSidebarItems()]
                ]);
            })->name('student.savings-history');
        });
    });
    Route::prefix('teacher')->group(function () {
        // Protectetd - Role: Teacher
        Route::middleware(['role:teacher'])->group(function () {
            Route::get('/', function () {
                return view('pages.dashboard.teacher.index');
            })->name('teacher.dashboard');
            Route::get('/students', function () {
                return view('pages.dashboard.teacher.students');
            })->name('teacher.students');
        });
    });
    Route::prefix('admin')->group(function () {
        // Protectetd - Role: Admin
        Route::middleware(['role:admin'])->group(function () {
            Route::get('/', function () {
                return view('pages.dashboard.admin.index');
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
