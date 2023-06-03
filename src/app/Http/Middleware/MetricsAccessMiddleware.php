<?php

namespace App\Http\Middleware;
use Illuminate\Http\Request;
use Closure;

class MetricsAccessMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $allowedDomain =env('METRICS_ALLOWED_DOMAIN');

        if ($request->getHost() !== $allowedDomain) {
            abort(403, 'Unauthorized.');
        }

        return $next($request);
    }
}
