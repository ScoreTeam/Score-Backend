<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if the authenticated user is an admin
        if ($request->user() && $request->user()->isAdmin()) {
            return $next($request);
        }

        // Redirect or respond with unauthorized error
        return redirect()->route('home')->with('error', 'You are not authorized to access this resource.');
    }
}
