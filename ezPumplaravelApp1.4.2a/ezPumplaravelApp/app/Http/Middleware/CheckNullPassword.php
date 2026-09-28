<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckNullPassword
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->password === null) {
            return redirect()->route('password.set');
        }

        return $next($request);
    }
}
