<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpFoundation\Response;

class InstallationModeMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $installationMode = 1; // fallback

        // Sirf tabhi query run hogi jab column exist kare
        if (Schema::hasColumn('SysConfig', 'Sys_Mode')) {
            $installationMode = DB::table('SysConfig')->value('Sys_Mode') ?? 1;
        }

        // Mode 3 (Only ATG) => sirf tank routes allowed
        if ($installationMode == 3) {
            if (! $request->is('tanks*') && ! $request->is('tanks/*')) {
                return redirect()->route('tank.list')
                    ->with('error', 'Only Tank module is accessible in ATG mode.');
            }
        }
        return $next($request);
    }
}
