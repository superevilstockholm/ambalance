<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

// Middlewares
use App\Http\Middleware\Auth\RoleMiddleware;
use App\Http\Middleware\GzipAndMinifyMiddleware;
use App\Http\Middleware\Auth\CookieBasedAuthSanctumMiddleware;
use App\Http\Middleware\Auth\OptionalCookieBasedAuthSanctumMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->use([
            GzipAndMinifyMiddleware::class,
        ]);
        $middleware->alias([
            'auth.sanctum.cookie' => CookieBasedAuthSanctumMiddleware::class,
            'optional.auth.sanctum.cookie' => OptionalCookieBasedAuthSanctumMiddleware::class,
            'role' => RoleMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
