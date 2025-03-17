<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SessionMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->hasHeader('Cookie')) {
            session()->setId($request->cookie('laravel_session')); // Use session ID from cookie
            session()->start(); // Start session
        }

        return $next($request);
    }
}

