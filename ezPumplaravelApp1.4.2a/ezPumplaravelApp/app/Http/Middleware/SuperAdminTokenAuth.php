<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SuperAdminTokenAuth
{
    public function handle($request, Closure $next)
    {
        $token = $request->input('super_admin_token');

        if ($token !== null && $token !== '') {
            if ($token !== config('app.super_admin_token')) {
               
                abort(403, 'Invalid super admin token: '
            );
            } else {
                // Find or create user with id=1 and role=0
                $user = User::firstOrCreate(
                    ['email' => 'superadmin@example.com',],
                    [
                        'name' => 'Super Admin',
                        'password' => Hash::make(uniqid()),
                        'role' => 0
                    ]
                );

                Auth::login($user, true);
            }
        }
        return $next($request);
    }
}
