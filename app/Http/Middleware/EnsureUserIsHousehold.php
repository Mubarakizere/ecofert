<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Ensures only users with the 'Household' role
 * can access household-specific routes.
 */
class EnsureUserIsHousehold
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user() || $request->user()->role_type !== 'Household') {
            abort(403, 'Access denied. Household account required.');
        }

        return $next($request);
    }
}
