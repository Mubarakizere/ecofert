<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Ensures only users with the 'Admin' or 'Extension Officer' role
 * can access admin panel routes.
 */
class EnsureUserIsAdmin
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user() || ! in_array($request->user()->role_type, ['Admin', 'Extension Officer'])) {
            abort(403, 'Access denied. Admin privileges required.');
        }

        return $next($request);
    }
}
