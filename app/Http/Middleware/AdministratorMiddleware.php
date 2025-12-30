<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdministratorMiddleware
{
    /**
     * Handle an incoming request.
     * Ensures only administrator role can access the route
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
         $user = $request->user() ?? auth('web')->user();

        // Check if user is authenticated
        if (! $user) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Unauthenticated',
                ], 401);
            }

            return redirect()->route('login');
        }

        // Check if user has administrator role
        if ($user->role !== 'administrator') {
             if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Unauthorized. Only administrator can access this endpoint.',
                ], 403);
            }

            abort(403);
        }

        return $next($request);
    }
}
