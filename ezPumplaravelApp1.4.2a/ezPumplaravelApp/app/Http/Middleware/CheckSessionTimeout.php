<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class CheckSessionTimeout
{
    public function handle12($request, Closure $next)
    {
        if ($request->is('setup') || $request->is('setup/*')) {
            return $next($request);
        }

        // Step 2: Check if database connection and tables exist
        try {
            if (!DB::connection()->getSchemaBuilder()->hasTable('users')) {
                return redirect('/setup');
            }
        } catch (\Exception $e) {
            // Database connection failed
            return redirect('/setup');
        }
        // Check if the user is authenticated
        if (Auth::check()) {
            // Get the last activity timestamp from the session
            $lastActivity = session('last_activity');

            // Check if the last activity timestamp is present and check the timeout (10 minutes = 600 seconds)
            if ($lastActivity && time() - $lastActivity > 600) {
                // User has been inactive for more than 10 minutes, logout the user
                Auth::logout();
                Session::flush(); // Clear all session data

                return redirect('/login')->with('message', 'Session expired. Please login again.');
            }

            // Update last activity timestamp
            session(['last_activity' => time()]);
        }
        return $next($request);
    }
    public function handle($request, Closure $next)
    {
        /**
         * 1️⃣ Setup routes ko bypass karein
         */
        if ($request->is('setup') || $request->is('setup/*')) {
            return $next($request);
        }

        /**
         * 2️⃣ Database ready check (CACHED – only once)
         */
        try {
            $dbReady = Cache::rememberForever('db_ready', function () {
                return DB::connection()
                    ->getSchemaBuilder()
                    ->hasTable('users');
            });

            if (!$dbReady) {
                return redirect('/setup');
            }
        } catch (\Exception $e) {
            return redirect('/setup');
        }

        /**
         * 3️⃣ Session timeout logic (10 minutes)
         */
        if (Auth::check()) {

            $lastActivity = session('last_activity');

            if ($lastActivity && time() - $lastActivity > 600) {

                Auth::logout();
                Session::flush();

                return redirect('/login')
                    ->with('message', 'Session expired. Please login again.');
            }

            // update activity time
            session(['last_activity' => time()]);
        }

        return $next($request);
    }
}
