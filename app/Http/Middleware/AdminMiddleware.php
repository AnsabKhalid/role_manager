<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        {
            // Check if the authenticated user is an admin
            
            if (auth()->check() && auth()->user()->role->name === 'Admin') {
                return $next($request);
            }
    
            return response()->json(['message' => 'Unauthorized. Only Admin can perform this action.'], 403);
        }
    }
}
