<?php // app/Http/Middleware/CheckRole.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  int  $role
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $role)
    {
        if (Auth::check() && Auth::user()->role <= $role) {
            return $next($request);
        }

        $message = "Unauthorized. Requires Role: $role. user role: " . (Auth::user() ? Auth::user()->role : 'guest');

        if ($request->ajax() || $request->wantsJson() || $request->isXmlHttpRequest()) {
            return response()->json([
                'message' => $message,
                'user' => Auth::user(),
            ], 403);
        }

        abort(403, $message);
    }
}
