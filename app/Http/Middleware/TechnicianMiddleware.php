<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TechnicianMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Please login first.');
        }

        if (!in_array(auth()->user()->role, ['admin', 'technician'])) {
            abort(403, 'Access denied. Technician access required.');
        }

        return $next($request);
    }
}
