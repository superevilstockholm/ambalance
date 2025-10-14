<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class GzipAndMinifyMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($response instanceof Response && str_contains($response->headers->get('Content-Type', ''), 'text/html')) {
            $content = $response->getContent();

            // Minify
            $content = preg_replace([
                '/\s{2,}/', // 2 or more spaces
                '/\n|\r/', // new line
                '/<!--.*?-->/', // HTML comments
                '/\/\*.*?\*\//', // CSS comments
                '/\/\*.*?\*\//', // JS Comments /* ... */
            ], [' ', '', '', '', ''], $content);

            // Gzip
            if (isset($_SERVER['HTTP_ACCEPT_ENCODING']) && strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip') !== false) {
                $content = gzencode($content, 9);
                $response->headers->set('Content-Encoding', 'gzip');
            }

            $response->setContent($content);
            $response->headers->set('Content-Length', strlen($content));
        }

        return $response;
    }
}
