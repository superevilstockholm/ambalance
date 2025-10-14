<?php

namespace App\Http\Middleware\Auth;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;
use Symfony\Component\HttpFoundation\Response;

class CookieBasedAuthSanctumMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->cookie('auth_token');
        if (!$token) {
            return redirect(route('login'), 302)->withoutCookie('auth_token');
        }
        $accessToken = PersonalAccessToken::findToken($token);
        if (!$accessToken || !$accessToken->tokenable || ($accessToken->expires_at && $accessToken->expires_at->isPast())) {
            return redirect(route('login'), 302)->withoutCookie('auth_token');
        }
        // Set user
        Auth::setUser($accessToken->tokenable);
        // Inject user resolver $request->user()
        $request->setUserResolver(fn() => $accessToken->tokenable);
        return $next($request);
    }
}
