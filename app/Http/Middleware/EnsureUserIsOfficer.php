<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsOfficer
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user() || ! in_array($request->user()->role_type, ['Extension Officer', 'Admin'])) {
            abort(403, 'Access denied. Extension Officer privileges required.');
        }

        return $next($request);
    }
}
