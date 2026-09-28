<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class SetupController extends Controller
{

    public function index()
    {
        $dbName = config('database.connections.mysql.database');
        $dbExists = false;

        try {
            // Check if DB exists by running a simple query
            DB::connection()->getPdo();
            $dbExists = true;
        } catch (\Throwable $t) {
            $dbExists = false;
        }

        if ($dbExists) {
            // ✅ If DB exists → go to login page
            return redirect()->route('login'); // or url('/login')
        }

        // ❌ DB missing → show setup page
        return view('no-database', [
            'dbExists' => $dbExists,
            'dbName'   => $dbName,
        ]);
    }
    public function runExternalCommand(Request $request)
    {
        try {

            $request->validate([
                'password' => ['required', 'string'],
            ]);

            // Check if DB exists
            $dbExists = true;
            try {
                DB::connection()->getPdo();
            } catch (\Throwable $e) {
                $dbExists = false;
            }
            // Case 1: Database does NOT exist → use hardcoded password
            if (!$dbExists) {
                if ($request->password !== "S@n@tDev#2025!") {
                    return redirect()->back()->with('error', '❌ Incorrect setup password');
                }

                $cmd = "./sys_config";
                $output = shell_exec($cmd . " 2>&1");

                if (!$output) {
                    return redirect()->back()->with('error', 'No output returned from dev_config.');
                }

                return redirect()->back()
                    ->with('success', '✅ Config executed successfully (no DB mode)')
                    ->with('output', trim($output));
            }

        } catch (\Throwable $t) {
            return redirect()->back()->with('error', $t->getMessage());
        }
    }



    public function resetold(Request $request)
    {
        try {
            $dbExists = true;
            try {
                DB::connection()->getPdo();
            } catch (\Throwable $e) {
                $dbExists = false;
            }

            if (! $dbExists) {
                // Run reset command without password
                // Example using artisan or an OS command. Be careful with actual reset in dev env.
                // Here we'll simulate with 'cache:clear' for example's sake or call your dev_config tool:
                 $cmd = './sys_reset' ;
                $output = shell_exec($cmd . " 2>&1");

                $dbName = env('DB_DATABASE');
                return view('no-database', compact('output','dbExists','dbName'));

            }


            if ($request->password !== "S@n@tDev#2025!") {
                return redirect()->back()->with('error', '❌ Incorrect setup password');
            }

            // Password ok -> run reboot
            // Example: call your dev_config or system reboot here
             $cmd = './sys_reset';
             $output = shell_exec($cmd . " 2>&1");
            return response()->json([
                'status'  => 'success',
                'message' => '🔁 reset triggered.',
                'output'  => $output,
            ]);
        } catch (\Throwable $t) {
            Log::error('System reset error: '.$t->getMessage(), ['exception' => $t]);
            return response()->json([
                'status'  => 'error',
                'message' => 'Internal error while attempting reboot',
                'detail'  => $t->getMessage(),
            ], 500);
        }
    }

    public function rebootold(Request $request)
    {
        try {
            $dbExists = true;
            try {
                DB::connection()->getPdo();
            } catch (\Throwable $e) {
                $dbExists = false;
            }

            // 🔹 If database missing, use setup password
            if (!$dbExists) {
                if ($request->password !== "S@n@tDev#2025!") {
                    return redirect()->back()->with('error', '❌ Incorrect setup password');
                }

                // 🔹 Create reboot flag file
                $flagPath = '/var/www/html/tmp/reboot.flag';
                touch($flagPath);

                // 🔹 Run optional shell command (safe simulation)
                $cmd = 'echo "Simulated reboot without DB"';
                $output = shell_exec($cmd . " 2>&1");

                return redirect()->back()->with('success', "✅ Reboot flag created at {$flagPath}")->with('output', trim($output));
            }

            // 🔹 If DB exists
            $user = Auth::user();
            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthorized - please login first.',
                ], 401);
            }

            if ($request->password !== "S@n@tDev#2025!") {
                return redirect()->back()->with('error', '❌ Incorrect setup password');
            }

            // 🔹 Create reboot flag file
            $flagPath = '/var/www/html/tmp/reboot.flag';
            touch($flagPath);

            // 🔹 Run shell command (can be actual reboot or your custom script)
            // Example safe simulation:
            // $cmd = 'sudo /sbin/reboot';   // Uncomment for real reboot (requires sudoers config)
            $cmd = 'echo "System reboot simulated successfully"';
            $output = shell_exec($cmd . " 2>&1");

            return response()->json([
                'status' => 'success',
                'message' => '🔁 Reboot process triggered.',
                'output' => trim($output),
                'flag_path' => $flagPath,
            ]);
        } catch (\Throwable $t) {
            Log::error('System reboot error: ' . $t->getMessage(), ['exception' => $t]);
            return response()->json([
                'status' => 'error',
                'message' => 'Internal error while attempting reboot',
                'detail' => $t->getMessage(),
            ], 500);
        }
    }
    public function reset(Request $request)
    {
        try {
            $dbExists = true;
            try {
                DB::connection()->getPdo();
            } catch (\Throwable $e) {
                $dbExists = false;
            }

            if (!$dbExists) {
                // ✅ DB nahi hai → direct reset command
                $cmd = './sys_reset';
                $output = shell_exec($cmd . " 2>&1");

                $dbName = env('DB_DATABASE');
                return view('no-database', compact('output', 'dbExists', 'dbName'));
            }

            // ✅ DB exists → password verify
            if ($request->password !== "S@n@tDev#2025!") {
                return redirect()->back()->with('error', '❌ Incorrect setup password');
            }

            // ✅ DB exists → insert type 666 command
            $writeCmd = "666";

            // Check existing latest SUCCESS or PENDING
            $command = DB::table('commands')
                ->where('type', $writeCmd)
                ->whereIn('exec', [0,1,2])
                ->whereNull('response')
                ->orderByDesc('id')
                ->first();

            if (!$command) {
                // Insert new command if none exists
                $commandId = DB::table('commands')->insertGetId([
                    'type'       => $writeCmd,
                    'response'   => null,
                    'exec'       => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $command = DB::table('commands')->where('id', $commandId)->first();
            }

            // ✅ Return response without running shell command
            return response()->json([
                'status'  => 'success',
                'message' => '🔁 Reset command (666) added to commands table.',
                'command' => $command,
            ]);
        } catch (\Throwable $t) {
            Log::error('System reset error: '.$t->getMessage(), ['exception' => $t]);
            return response()->json([
                'status'  => 'error',
                'message' => 'Internal error while attempting reboot',
                'detail'  => $t->getMessage(),
            ], 500);
        }
    }
    public function reboot(Request $request)
    {
        try {
            $dbExists = true;
            try {
                DB::connection()->getPdo();
            } catch (\Throwable $e) {
                $dbExists = false;
            }

            $password = "S@n@tDev#2025!";

            // 🔹 If DB missing, run reboot directly
            if (!$dbExists) {
                if ($request->password !== $password) {
                    return redirect()->back()->with('error', '❌ Incorrect setup password');
                }

                // 🔹 Optional flag file
                $flagPath = '/var/www/html/tmp/reboot.flag';
                touch($flagPath);

                // 🔹 Safe shell simulation (no DB exists)
                $cmd = 'echo "Simulated reboot without DB"';
                $output = shell_exec($cmd . " 2>&1");

                return redirect()->back()
                    ->with('success', "✅ Reboot flag created at {$flagPath}")
                    ->with('output', trim($output));
            }

            // 🔹 DB exists → insert type 777 command
            if ($request->password !== $password) {
                return redirect()->back()->with('error', '❌ Incorrect setup password');
            }

            $writeCmd = "777";

            // Check for existing latest SUCCESS or PENDING
            $command = DB::table('commands')
                ->where('type', $writeCmd)
                ->whereIn('exec', [0, 1, 2])
                ->whereNull('response')
                ->orderByDesc('id')
                ->first();

            if (!$command) {
                $commandId = DB::table('commands')->insertGetId([
                    'type'       => $writeCmd,
                    'response'   => null,
                    'exec'       => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $command = DB::table('commands')->where('id', $commandId)->first();
            }

            // 🔹 Return JSON response without running shell command
            return response()->json([
                'status'  => 'success',
                'message' => '🔁 Reboot command (777) added to commands table.',
                'command' => $command,
            ]);
        } catch (\Throwable $t) {
            Log::error('System reboot error: '.$t->getMessage(), ['exception' => $t]);
            return response()->json([
                'status'  => 'error',
                'message' => 'Internal error while attempting reboot',
                'detail'  => $t->getMessage(),
            ], 500);
        }
    }

    public function serviceRestart(Request $request)
    {
        try {
            $dbExists = true;
            try {
                DB::connection()->getPdo();
            } catch (\Throwable $e) {
                $dbExists = false;
            }

            $password = "S@n@tDev#2025!";

            if (!$dbExists) {
                if ($request->password !== $password) {
                    return redirect()->back()->with('error', '❌ Incorrect setup password');
                }

                return response()->json([
                    'status' => 'success',
                    'message' => '♻ Service restart queued (no DB mode).',
                ]);
            }

            if ($request->password !== $password) {
                return redirect()->back()->with('error', '❌ Incorrect setup password');
            }

            $writeCmd = "901";

            $command = DB::table('commands')
                ->where('type', $writeCmd)
                ->whereIn('exec', [0, 1, 2])
                ->whereNull('response')
                ->orderByDesc('id')
                ->first();

            if (!$command) {
                $commandId = DB::table('commands')->insertGetId([
                    'type'       => $writeCmd,
                    'response'   => null,
                    'exec'       => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $command = DB::table('commands')->where('id', $commandId)->first();
            }

            return response()->json([
                'status'  => 'success',
                'message' => '♻ Service restart command (901) added to commands table.',
                'command' => $command,
            ]);
        } catch (\Throwable $t) {
            Log::error('Service restart error: '.$t->getMessage(), ['exception' => $t]);
            return response()->json([
                'status'  => 'error',
                'message' => 'Internal error while attempting service restart',
                'detail'  => $t->getMessage(),
            ], 500);
        }
    }


}

