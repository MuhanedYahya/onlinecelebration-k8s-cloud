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
            return view('errors.404');
        }

        return $next($request);
    }
}
