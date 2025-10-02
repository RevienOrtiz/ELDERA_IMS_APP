<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CacheHeaders
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Only apply caching to GET requests
        if ($request->isMethod('GET')) {
            $path = $request->path();
            
            // Static assets - cache for 1 year
            if (str_contains($path, 'css') || str_contains($path, 'js') || str_contains($path, 'images') || str_contains($path, 'fonts')) {
                $response->headers->set('Cache-Control', 'public, max-age=31536000, immutable');
                $response->headers->set('Expires', gmdate('D, d M Y H:i:s', time() + 31536000) . ' GMT');
            }
            // API endpoints - cache for 5 minutes
            elseif (str_starts_with($path, 'api/')) {
                $response->headers->set('Cache-Control', 'public, max-age=300');
                $response->headers->set('Expires', gmdate('D, d M Y H:i:s', time() + 300) . ' GMT');
            }
            // Dashboard and statistics - cache for 2 minutes
            elseif (in_array($path, ['Dashboard'])) {
                $response->headers->set('Cache-Control', 'public, max-age=120');
                $response->headers->set('Expires', gmdate('D, d M Y H:i:s', time() + 120) . ' GMT');
            }
            // Seniors tables - disable caching to reflect updates immediately
            elseif (in_array($path, ['Seniors', 'Seniors/benefits', 'Seniors/pension', 'Seniors/id-applications'])) {
                $response->headers->set('Cache-Control', 'no-cache, no-store, must-revalidate');
                $response->headers->set('Pragma', 'no-cache');
                $response->headers->set('Expires', '0');
            }
            // Forms and dynamic content - cache for 30 seconds
            elseif (str_contains($path, 'Form_') || str_contains($path, 'edit-') || str_contains($path, 'view-')) {
                $response->headers->set('Cache-Control', 'public, max-age=30');
                $response->headers->set('Expires', gmdate('D, d M Y H:i:s', time() + 30) . ' GMT');
            }
            // Default - no cache for sensitive pages
            else {
                $response->headers->set('Cache-Control', 'no-cache, no-store, must-revalidate');
                $response->headers->set('Pragma', 'no-cache');
                $response->headers->set('Expires', '0');
            }
        }

        return $response;
    }
}
