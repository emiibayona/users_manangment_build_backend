<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\Auth;

use Closure;

class Authenticate extends Middleware
{
    public function handle($request, Closure $next, ...$guards)
    {
        if (!$request->bearerToken() || ($request->bearerToken() and !Auth::check())) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }
        return $next($request);

    }

}
