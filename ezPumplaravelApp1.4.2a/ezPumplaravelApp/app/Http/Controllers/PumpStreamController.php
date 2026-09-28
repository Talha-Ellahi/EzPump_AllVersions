<?php

namespace App\Http\Controllers;

use App\Models\ATGConfigLog;
use App\Models\DeviceConfigLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\Process\Process;

class PumpStreamController extends Controller
{
    public function stream()
    {
        return response()->stream(function () {
            while (true) {
                // Pumps table se FC_NZNo aur ICODE lo
                $pumps = DB::table('PUMPS')
                    ->select('FC_NZNo', 'ICODE')
                    ->get()
                    ->keyBy('FC_NZNo');

                $pumpIds = $pumps->keys()->all();
                $products = DB::table('PRODUCT')
                    ->select('ICODE', 'ITMNAME')
                    ->get()
                    ->keyBy('ICODE');

                $latestStates = DB::table('PUMP_STATE as ps')
                    ->join(DB::raw('(SELECT PUMPID, MAX(time) as max_time FROM PUMP_STATE GROUP BY PUMPID) t'),
                        function ($join) {
                            $join->on('t.PUMPID', '=', 'ps.PUMPID')
                                ->on('t.max_time', '=', 'ps.time');
                        })
                    ->leftJoin('shift', function($join) {
                        $join->on('shift.pump_id', '=', 'ps.PUMPID')
                            ->where('shift.status', '=', 1);   // only active shift
                    })
                    ->whereIn('ps.PUMPID', $pumpIds ?: [0])
                    ->select(
                        'ps.PUMPID','ps.qty','ps.rate','ps.amt',
                        'ps.shift_qty','ps.shift_amt','ps.STS','ps.time',
                        'shift.total_qty as shift_total_qty',
                        'shift.rate as shift_rate', DB::raw("CASE
                WHEN shift.is_changed = 1
                THEN shift.new_rate
                ELSE shift.rate
             END as effective_rate")
                    )
                    ->get()
                    ->map(function ($row) use ($pumps,$products) {
                        // Attach ICODE
                        $row->ICODE = $pumps[$row->PUMPID]->ICODE ?? null;

                        // Decide image
                        $icodeImages = [
//                            1 => "assets/images/Nozzle_green.png",
//                            2 => "assets/images/Nozzle_Blue.png",
//                            3 => "assets/images/Nozzle_yellow.png",
//                            1 => "assets/images/green_transparent.png",
//                            2 => "assets/images/blue_transparent.png",
//                            3 => "assets/images/yellow_transparent.png",
                            1 => "assets/images/green_transparent.webp",
                            2 => "assets/images/blue_transparent.webp",
                            3 => "assets/images/yellow_transparent.webp",
                        ];
                        $stsImages = [
                            1 => "assets/images/Nozzle_one.png",
                            2 => "assets/images/Nozzle_three.png",
                        ];

                        if (!empty($row->STS) && isset($stsImages[$row->STS])) {
                            $image = $stsImages[$row->STS];
                        } else {
                            $image = $icodeImages[$row->ICODE] ?? "assets/images/Nozzle.png";
                        }

                        $row->image = $image;
                        $row->product_name = $products[$row->ICODE]->ITMNAME.' ' ?? '';
                        return $row;
                    });

                // Latest pump states
//                $latestStates = DB::table('PUMP_STATE as ps')
//                    ->join(DB::raw('(SELECT PUMPID, MAX(time) as max_time FROM PUMP_STATE GROUP BY PUMPID) t'),
//                        function ($join) {
//                            $join->on('t.PUMPID', '=', 'ps.PUMPID')
//                                ->on('t.max_time', '=', 'ps.time');
//                        })
//                    ->whereIn('ps.PUMPID', $pumpIds ?: [0])
//                    ->select('ps.PUMPID','ps.qty','ps.rate','ps.amt','ps.shift_qty','ps.shift_amt','ps.STS','ps.time')
//                    ->get()
//                    ->map(function ($row) use ($pumps) {
//
//                        // ICODE attach karo
//                        $row->ICODE = $pumps[$row->PUMPID]->ICODE ?? null;
//
//                        // Backend me hi image decide karo
//                        $icodeImages = [
//                            1 => "assets/images/Nozzle_green.png",
//                            2 => "assets/images/Nozzle_blue.png",
//                            3 => "assets/images/Nozzle_yellow.png",
//                        ];
//
//                        $stsImages = [
//                            1=> "assets/images/Nozzle_one.png",
//                            2=> "assets/images/Nozzle_three.png",
//                        ];
//
//                        // Default image → ICODE se
////                    $image = $icodeImages[$row->ICODE] ?? "assets/images/Nozzle_default.png";
//
//                        // Agar STS set hai to override
//                        if (!empty($row->STS) && isset($stsImages[$row->STS])) {
//                            // Agar STS > 0 aur mapping available hai
//                            $image = $stsImages[$row->STS];
//                        } else {
//                            // Warna ICODE ki image
//                            $image = $icodeImages[$row->ICODE] ?? "assets/images/Nozzle.png";
//                        }
//
//
//                        $row->image = $image;
//                        return $row;
//                    });

                echo "data: " . json_encode($latestStates) . "\n\n";
                ob_flush();
                flush();
                sleep(2);
            }
        }, 200, [
            'Content-Type'  => 'text/event-stream',
            'Cache-Control' => 'no-cache',
            'Connection'    => 'keep-alive',
        ]);
    }

    public function summaryStream()
    {
        return response()->stream(function () {
            while (true) {
                // Product-wise sales
                $productWiseSales = DB::table('shift')
                    ->selectRaw('icode, SUM(sale_count) as sale_count, SUM(total_amount) as total_amount, SUM(total_qty) as total_qty')
                    ->groupBy('icode')
                    ->get();

                $products = DB::table('PRODUCT')->get()->keyBy('ICODE');
                $totalAmt = $productWiseSales->sum('total_amount') / 100;

                $productData = $productWiseSales->map(function ($row) use ($products, $totalAmt) {
                    $p = $products[$row->icode] ?? null;
                    $name = $p->ITMNAME ?? $row->icode;
                    $rate = $p->SRATE ?? 0;
                    $qtyL = $row->total_qty / 100;
                    $amtR = $row->total_amount / 100;
                    $pct = $totalAmt > 0 ? round(($amtR / $totalAmt) * 100, 2) : 0;

//                    return [
//                        'icode'       => $row->icode,
//                        'name'        => $name,
//                        'rate'        => $rate / 100,
//                        'qty'         => $qtyL,
//                        'sale_count'  => $row->sale_count,
//                        'amount'      => $amtR,
//                        'percentage'  => $pct,
//                    ];
                    return [
                        'icode'       => $row->icode,
                        'name'        => $name,
                        'rate'        => (float) $rate / 100,
                        'qty'         => (float) $qtyL,
                        'sale_count'  => (int) $row->sale_count,
                        'amount'      => (float) $amtR,
                        'percentage'  => (float) $pct,
                    ];
                });

                // Payment-wise sales
                $paymentMethodSales = DB::table('shift_payment_wise_sale as SPS')
                    ->leftJoin('paymentmethod as PM', 'SPS.paymentmethod_id', '=', 'PM.id')
                    ->selectRaw('PM.Des as payment_method, SUM(SPS.total_sale) as total_amount')
                    ->where('PM.id', '!=', 1)
                    ->groupBy('PM.Des')
                    ->get();

                $totalNonCash = $paymentMethodSales->sum('total_amount');
                $totalShiftSales = DB::table('shift')->sum('total_amount') / 100;
                $cashSale = round($totalShiftSales - $totalNonCash, 2);

                $paymentMethodSales->push((object)['payment_method' => 'Cash', 'total_amount' => $cashSale]);

                $paymentData = $paymentMethodSales->map(function ($row) {
                    return [
                        'payment_method' => $row->payment_method,
                        'total_amount'   => $row->total_amount,
                    ];
                });

                $data = [
                    'products' => $productData,
                    'payments' => $paymentData,
                    'totalAmt' => $totalAmt,
                ];

                echo "data: " . json_encode($data) . "\n\n";
                ob_flush();
                flush();
                sleep(5);
            }
        }, 200, [
            'Content-Type'  => 'text/event-stream',
            'Cache-Control' => 'no-cache',
            'Connection'    => 'keep-alive',
        ]);
    }
    public function saveDisplayName(Request $request)
    {
        $request->validate([
            'pump_id' => 'required|integer',
            'display_name' => 'nullable|string|max:50',
        ]);
        DB::create('pump_display_names')->create(
            ['pump_id' => $request->pump_id],
            ['display_name' => $request->display_name]
        );

        return response()->json(['success' => true]);
    }

//    public function index()
//    {
//        return response()->json(\DB::table('dispensers')->get());
//    }
    public function index()
    {

//        $data=\DB::table('DISPENSERS')->get();
        $data=\DB::table('DEVICES')->get();
        $type='dispenser';
        $cmdType = $type === 'dispenser' ? '200' : '100';

        $commands = DB::table('commands')
            ->where('type', $cmdType)
            ->whereIn('exec', [1, 2])
            ->orderByDesc('id')
            ->first();
        return response()->json([
            'datas'=>$data,
            'commands'=>$commands
        ]);
    }

    public function runConfigReadol(Request $request)
    {
        try {
            $dispenserId = $request->input('dispenserId');
            if (!$dispenserId) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Dispenser ID is required'
                ], 400);
            }

            // Run command (for now commented in test mode)
//             $cmd = "./dev_config 2 {$dispenserId} 2";
             $cmd = "2 {$dispenserId} 2";
             $output = shell_exec($cmd);
            $commandsRecord=DB::table('commands')->where('type',$cmd)->whereIn('exec',[1,2])->orderBy('id','desc')->first();
            $csvData='processing';
          if (!$commandsRecord){
              $commandId = DB::table('commands')->insertGetId([
                  'type'       => $cmd,
                  'response'   => null,
                  'exec'       => 0, // completed
                  'created_at' => now(),
                  'updated_at' => now(),
              ]);
          }else{
              $csvData = $commandsRecord->response;
              DB::transaction(function () use ($dispenserId, $cmd, $csvData) {
                  // Purane active ko inactive kar do
                  // Sirf jab command successful ho (csvData mila ho)
                  if (!empty($csvData)) {
                      // Purane active ko inactive kar do
                      DeviceConfigLog::where('dispenser_id', $dispenserId)->update(['is_active' => 0]);

                      // Naya record insert
                      DeviceConfigLog::create([
                          'dispenser_id' => $dispenserId,
                          'command'      => 'Read',
                          'csv_data'  => !empty($csvData) ? json_encode($csvData) : null,
                          'status'    => !empty($csvData) ? 'success' : 'error',
                          'is_active'    => 1,
                      ]);
                  }
              });
          }

            // CSV file path
//            $csvPath = base_path("tmp/device{$dispenserId}.csv");
//            $csvPath = "/var/www/html/tmp/device{$dispenserId}.csv";
//            $csvData = null;
//            if (file_exists($csvPath)) {
//                $csvData = array_map('str_getcsv', file($csvPath));
//
//                // ✅ File delete after reading
//                unlink($csvPath);
//                //row delete table
//            }

//            DB::transaction(function () use ($dispenserId, $cmd, $csvData) {
//                // Purane active ko inactive kar do
//                // Sirf jab command successful ho (csvData mila ho)
//                if (!empty($csvData)) {
//                    // Purane active ko inactive kar do
//                    DeviceConfigLog::where('dispenser_id', $dispenserId)->update(['is_active' => 0]);
//
//                    // Naya record insert
//                    DeviceConfigLog::create([
//                        'dispenser_id' => $dispenserId,
//                        'command'      => 'Read',
//                        'csv_data'  => !empty($csvData) ? json_encode($csvData) : null,
//                        'status'    => !empty($csvData) ? 'success' : 'error',
//                        'is_active'    => 1,
//                    ]);
//                }
//            });
            return response()->json([
                'status'   => !empty($csvData) ? 'success' : 'error',
                'os'       => 'Linux',
                 'command'  => $cmd,
//                'command'  => 'test',
                 'output'   => trim($output),
                'csv_file' => "device{$dispenserId}.csv",
                'csv_data' => $csvData
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    public function runConfigWriteold(Request $request)
    {
        try {
            $dispenserId = $request->input('dispenserId');
            if (!$dispenserId) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Dispenser ID is required'
                ], 400);
            }

            /**
             * STEP 1: First Run READ Command (Silent, only log in DB)
             */
            $readCmd = "./dev_config 2 {$dispenserId} 2";
            $readOutput = shell_exec($readCmd);
            $commandsRecord=DB::table('commands')->where('type',$readCmd)->whereIn('exec',[1,2])->orderBy('id','desc')->first();
            $writeCsvData='processing';
            if (!$commandsRecord){
                $commandId = DB::table('commands')->insertGetId([
                    'type'       => $readCmd,
                    'response'   => null,
                    'exec'       => 0, // completed
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }else{
                $writeCsvData = $commandsRecord->response;
                DB::transaction(function () use ($dispenserId, $readCmd, $writeCsvData) {
                    // Purane active ko inactive kar do
                    // Sirf jab command successful ho (csvData mila ho)
                    if (!empty($writeCsvData)) {
                        // Purane active ko inactive kar do
                        DeviceConfigLog::where('dispenser_id', $dispenserId)->update(['is_active' => 0]);

                        // Naya record insert
                        DeviceConfigLog::create([
                            'dispenser_id' => $dispenserId,
                            'command'      => 'Read',
                            'csv_data'  => !empty($csvData) ? json_encode($csvData) : null,
                            'status'    => !empty($csvData) ? 'success' : 'error',
                            'is_active'    => 1,
                        ]);
                    }
                });
            }
//            $readCsvPath = base_path("tmp/device{$dispenserId}.csv");
//            $readCsvPath = "/var/www/html/tmp/device{$dispenserId}.csv";
//
//            $readCsvData = null;
//            if (file_exists($readCsvPath)) {
//                $readCsvData = array_map('str_getcsv', file($readCsvPath));
//                 unlink($readCsvPath); // optional delete
//            }
//
//            DB::transaction(function () use ($dispenserId, $readCmd, $readCsvData) {
//                // ✅ Sirf Read commands ko inactive karo
//                DeviceConfigLog::where('dispenser_id', $dispenserId)
//                    ->where('command', 'Read')
//                    ->update(['is_active' => 0]);
//
//                // ✅ Naya Read record insert karo
//                DeviceConfigLog::create([
//                    'dispenser_id' => $dispenserId,
//                    'command'      => 'Read',
//                    'csv_data'     => !empty($readCsvData) ? json_encode($readCsvData) : null,
//                    'status'       => !empty($readCsvData) ? 'success' : 'error',
//                    'is_active'    => 1,
//                ]);
//            });

            /**
             * STEP 2: Now Run WRITE Command (Actual response for frontend)
             */
            $writeCmd = "./dev_config 2 {$dispenserId} 1";
//            $writeOutput = shell_exec($writeCmd);
////            $writeCsvPath = base_path("tmp/device{$dispenserId}.csv");
//            $writeCsvPath = "/var/www/html/tmp/device{$dispenserId}.csv";
//
//            $writeCsvData = null;
//            if (file_exists($writeCsvPath)) {
//                $writeCsvData = array_map('str_getcsv', file($writeCsvPath));
//                 unlink($writeCsvPath); // optional delete
//            }
//
//            DB::transaction(function () use ($dispenserId, $writeCmd, $writeCsvData) {
//            DeviceConfigLog::where('dispenser_id', $dispenserId)->update(['is_active' => 0]);
//
//                DeviceConfigLog::create([
//                    'dispenser_id' => $dispenserId,
//                    'command'      => 'Write',
//                    'csv_data'     => !empty($writeCsvData) ? json_encode($writeCsvData) : null,
//                    'status'       => !empty($writeCsvData) ? 'success' : 'error',
//                    'is_active'    => 1,
//                ]);
//            });

            // ✅ Frontend ko sirf WRITE ka result bhejna hai
            return response()->json([
                'status'   => !empty($writeCsvData) ? 'success' : 'error',
                'os'       => 'Linux',
                'command'  => $writeCmd,
                'output'   => trim($writeCsvData),
//                'output'   => 'out',
                'csv_file' => "device{$dispenserId}.csv",
                'csv_data' => $writeCsvData
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function runConfigRead(Request $request)
    {
        try {
            $dispenserId = $request->input('dispenserId');
//dd($dispenserId);
            if (!$dispenserId) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Dispenser ID is required'
                ], 400);
            }

            // ✅ Command format (same as C++ side)
            $readCmd = "2 {$dispenserId} 2";

            /*
            |--------------------------------------------------------------------------
            | STEP 1: Latest SUCCESS command (exec = 1,2)
            |--------------------------------------------------------------------------
            */
            $command = DB::table('commands')
                ->where('type', $readCmd)
                ->whereIn('exec', [1, 2])
                ->orderByDesc('id')
                ->first();

            /*
            |--------------------------------------------------------------------------
            | STEP 2: Agar success na mile → existing PENDING reuse
            |--------------------------------------------------------------------------
            */
            if (!$command) {
                $command = DB::table('commands')
                    ->where('type', $readCmd)
                    ->where('exec', 0)
                    ->whereNull('response')
                    ->orderByDesc('id')
                    ->first();
            }

            /*
            |--------------------------------------------------------------------------
            | STEP 3: Agar kuch bhi na mile → INSERT NEW pending command
            |--------------------------------------------------------------------------
            */
            if (!$command) {
                $commandId = DB::table('commands')->insertGetId([
                    'type'       => $readCmd,
                    'response'   => null,
                    'exec'       => 0, // pending
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $command = DB::table('commands')->where('id', $commandId)->first();
            }

            /*
            |--------------------------------------------------------------------------
            | STEP 4: Agar abhi response nahi aaya → processing
            |--------------------------------------------------------------------------
            */
            if (empty($command->response)) {
                return response()->json([
                    'status'     => 'processing',
                    'command_id' => $command->id,
                    'message'    => 'Read command sent, waiting for response...'
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | STEP 5: Parse CSV response
            |--------------------------------------------------------------------------
            */
            $rawCsv = trim($command->response);

            $csvArray = array_map(
                'str_getcsv',
                preg_split("/\r\n|\n|\r/", $rawCsv)
            );

            /*
            |--------------------------------------------------------------------------
            | STEP 6: Save device config log (READ)
            |--------------------------------------------------------------------------
            */
            DB::transaction(function () use ($dispenserId, $csvArray) {

                DeviceConfigLog::where('dispenser_id', $dispenserId)
                    ->update(['is_active' => 0]);

                DeviceConfigLog::create([
                    'dispenser_id' => $dispenserId,
                    'command'      => 'Read',
                    'csv_data'     => json_encode($csvArray),
                    'status'       => 'success',
                    'is_active'    => 1,
                ]);
            });

            /*
            |--------------------------------------------------------------------------
            | STEP 7: Return success response
            |--------------------------------------------------------------------------
            */
            return response()->json([
                'status'   => 'success',
                'os'       => 'Linux',
                'command'  => $readCmd,
                'output'   => $rawCsv,
                'csv_data' => $csvArray,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }


    public function runConfigWrite(Request $request)
    {
        try {
            $dispenserId = $request->input('dispenserId');

            if (!$dispenserId) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Dispenser ID is required'
                ], 400);
            }

            // ✅ Command format (same as C++ side)
            $writeCmd = "2 {$dispenserId} 1";

            /*
            |--------------------------------------------------------------------------
            | STEP 1: Latest SUCCESS command (exec = 1,2)
            |--------------------------------------------------------------------------
            */
            $command = DB::table('commands')
                ->where('type', $writeCmd)
                ->whereIn('exec', [1, 2])
                ->orderByDesc('id')
                ->first();

            /*
            |--------------------------------------------------------------------------
            | STEP 2: Agar success na mile → existing PENDING reuse (exec = 0)
            |--------------------------------------------------------------------------
            */
            if (!$command) {
                $command = DB::table('commands')
                    ->where('type', $writeCmd)
                    ->where('exec', 0)
                    ->whereNull('response')
                    ->orderByDesc('id')
                    ->first();
            }

            /*
            |--------------------------------------------------------------------------
            | STEP 3: Agar kuch bhi na mile → INSERT NEW pending command
            |--------------------------------------------------------------------------
            */
            if (!$command) {
                $commandId = DB::table('commands')->insertGetId([
                    'type'       => $writeCmd,
                    'response'   => null,
                    'exec'       => 0, // pending
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $command = DB::table('commands')->where('id', $commandId)->first();
            }

            /*
            |--------------------------------------------------------------------------
            | STEP 4: Agar abhi bhi response nahi aaya → processing state
            |--------------------------------------------------------------------------
            */
            if (empty($command->response)) {
                return response()->json([
                    'status'     => 'processing',
                    'command_id' => $command->id,
                    'message'    => 'Command sent to device, waiting for response...'
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | STEP 5: Parse CSV response
            |--------------------------------------------------------------------------
            */
            $rawCsv = trim($command->response);

            $csvArray = array_map(
                'str_getcsv',
                preg_split("/\r\n|\n|\r/", $rawCsv)
            );

            /*
            |--------------------------------------------------------------------------
            | STEP 6: Save device config log
            |--------------------------------------------------------------------------
            */
            DB::transaction(function () use ($dispenserId, $csvArray) {

                DeviceConfigLog::where('dispenser_id', $dispenserId)
                    ->update(['is_active' => 0]);

                DeviceConfigLog::create([
                    'dispenser_id' => $dispenserId,
                    'command'      => 'Write',
                    'csv_data'     => json_encode($csvArray),
                    'status'       => 'success',
                    'is_active'    => 1,
                ]);
            });

            /*
            |--------------------------------------------------------------------------
            | STEP 7: Return success response to frontend
            |--------------------------------------------------------------------------
            */
            return response()->json([
                'status'   => 'success',
                'os'       => 'Linux',
                'command'  => $writeCmd,
                'output'   => $rawCsv,
                'csv_data' => $csvArray,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }


    public function runATGConfigReadold(Request $request)
    {
        try {
            $tankId = $request->input('TankID');
            if (!$tankId) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Tank ID is required'
                ], 400);
            }

            // Run command (for now commented in test mode)
            $cmd = "./dev_config 1 {$tankId} 2";
            $output = shell_exec($cmd);
            // CSV file path
//            $csvPath = base_path("tmp/device{$tankId}.csv");
            $csvPath = "/var/www/html/tmp/device{$tankId}.csv";
            $csvData = null;
            if (file_exists($csvPath)) {
                $csvData = array_map('str_getcsv', file($csvPath));

                // ✅ File delete after reading
                 unlink($csvPath);
            }

            DB::transaction(function () use ($tankId, $cmd, $csvData) {
//                if (!empty($csvData)) {
                ATGConfigLog::where('tank_id', $tankId)->update(['is_active' => 0]);

                ATGConfigLog::create([
                    'tank_id'   => $tankId,
                    'command'   => 'ATG Read',
                    'csv_data'  => !empty($csvData) ? json_encode($csvData) : null,
                    'status'    => !empty($csvData) ? 'success' : 'error',
                    'is_active' => 1,
                ]);
//                }
            });

            return response()->json([
                'status'   => !empty($csvData) ? 'success' : 'error',
                'os'       => 'Linux',
                'command'  => $cmd,
                'output'   => trim($output),
                'csv_file' => "atg{$tankId}.csv",
                'csv_data' => $csvData
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }


    public function runATGConfigWriteold(Request $request)
    {
        try {
            $tankId = $request->input('TankID');
            if (!$tankId) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Tank ID is required'
                ], 400);
            }

            // Run command (for now commented in test mode)
            $cmd = "./dev_config 1 {$tankId} 1";
            $output = shell_exec($cmd);
//            $csvPath = base_path("tmp/device{$tankId}.csv");
            $csvPath = "/var/www/html/tmp/device{$tankId}.csv";
            $csvData = null;
            if (file_exists($csvPath)) {
                $csvData = array_map('str_getcsv', file($csvPath));
                $rows = array_map('str_getcsv', file($csvPath));

                // ✅ File delete after reading
                 unlink($csvPath);
            }

            DB::transaction(function () use ($tankId, $cmd, $csvData) {
                ATGConfigLog::where('tank_id', $tankId)->update(['is_active' => 0]);

                ATGConfigLog::create([
                    'tank_id'   => $tankId,
                    'command'   => 'ATG Write',
                    'csv_data'  => !empty($csvData) ? json_encode($csvData) : null,
                    'status'    => !empty($csvData) ? 'success' : 'error',
                    'is_active' => 1,
                ]);
            });

            return response()->json([
                'status'   => !empty($csvData) ? 'success' : 'error',
                'os'       => 'Linux',
                'command'  => $cmd,
                'output'   => trim($output),
                'csv_file' => "atg{$tankId}.csv",
                'csv_data' => $csvData
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    public function runATGConfigRead(Request $request)
    {
        try {
            $tankId = $request->input('TankID');

            if (!$tankId) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Tank ID is required'
                ], 400);
            }

            // ✅ ATG READ command (C++ compatible)
            $readCmd = "1 {$tankId} 2";

            /*
            |--------------------------------------------------------------------------
            | STEP 1: Latest SUCCESS
            |--------------------------------------------------------------------------
            */
            $command = DB::table('commands')
                ->where('type', $readCmd)
                ->whereIn('exec', [1, 2])
                ->orderByDesc('id')
                ->first();

            /*
            |--------------------------------------------------------------------------
            | STEP 2: Pending reuse
            |--------------------------------------------------------------------------
            */
            if (!$command) {
                $command = DB::table('commands')
                    ->where('type', $readCmd)
                    ->where('exec', 0)
                    ->whereNull('response')
                    ->orderByDesc('id')
                    ->first();
            }

            /*
            |--------------------------------------------------------------------------
            | STEP 3: Insert new if nothing found
            |--------------------------------------------------------------------------
            */
            if (!$command) {
                $commandId = DB::table('commands')->insertGetId([
                    'type'       => $readCmd,
                    'response'   => null,
                    'exec'       => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $command = DB::table('commands')->where('id', $commandId)->first();
            }

            /*
            |--------------------------------------------------------------------------
            | STEP 4: Still processing
            |--------------------------------------------------------------------------
            */
            if (empty($command->response)) {
                return response()->json([
                    'status'     => 'processing',
                    'command_id' => $command->id,
                    'message'    => 'ATG Read command sent, waiting for response...'
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | STEP 5: Parse CSV
            |--------------------------------------------------------------------------
            */
            $rawCsv = trim($command->response);

            $csvArray = array_map(
                'str_getcsv',
                preg_split("/\r\n|\n|\r/", $rawCsv)
            );

            /*
            |--------------------------------------------------------------------------
            | STEP 6: Save ATG config log
            |--------------------------------------------------------------------------
            */
            DB::transaction(function () use ($tankId, $csvArray) {

                ATGConfigLog::where('tank_id', $tankId)
                    ->update(['is_active' => 0]);

                ATGConfigLog::create([
                    'tank_id'   => $tankId,
                    'command'   => 'ATG Read',
                    'csv_data'  => json_encode($csvArray),
                    'status'    => 'success',
                    'is_active' => 1,
                ]);
            });

            return response()->json([
                'status'   => 'success',
                'os'       => 'Linux',
                'command'  => $readCmd,
                'output'   => $rawCsv,
                'csv_data' => $csvArray,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    public function runATGConfigWrite(Request $request)
    {
        try {
            $tankId = $request->input('TankID');

            if (!$tankId) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Tank ID is required'
                ], 400);
            }

            // ✅ ATG WRITE command
            $writeCmd = "1 {$tankId} 1";

            /*
            |--------------------------------------------------------------------------
            | STEP 1: Latest SUCCESS
            |--------------------------------------------------------------------------
            */
            $command = DB::table('commands')
                ->where('type', $writeCmd)
                ->whereIn('exec', [1, 2])
                ->orderByDesc('id')
                ->first();

            /*
            |--------------------------------------------------------------------------
            | STEP 2: Pending reuse
            |--------------------------------------------------------------------------
            */
            if (!$command) {
                $command = DB::table('commands')
                    ->where('type', $writeCmd)
                    ->where('exec', 0)
                    ->whereNull('response')
                    ->orderByDesc('id')
                    ->first();
            }

            /*
            |--------------------------------------------------------------------------
            | STEP 3: Insert new if required
            |--------------------------------------------------------------------------
            */
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

            /*
            |--------------------------------------------------------------------------
            | STEP 4: Processing state
            |--------------------------------------------------------------------------
            */
            if (empty($command->response)) {
                return response()->json([
                    'status'     => 'processing',
                    'command_id' => $command->id,
                    'message'    => 'ATG Write command sent, waiting for response...'
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | STEP 5: Parse CSV
            |--------------------------------------------------------------------------
            */
            $rawCsv = trim($command->response);

            $csvArray = array_map(
                'str_getcsv',
                preg_split("/\r\n|\n|\r/", $rawCsv)
            );

            /*
            |--------------------------------------------------------------------------
            | STEP 6: Save ATG WRITE log
            |--------------------------------------------------------------------------
            */
            DB::transaction(function () use ($tankId, $csvArray) {

                ATGConfigLog::where('tank_id', $tankId)
                    ->update(['is_active' => 0]);

                ATGConfigLog::create([
                    'tank_id'   => $tankId,
                    'command'   => 'ATG Write',
                    'csv_data'  => json_encode($csvArray),
                    'status'    => 'success',
                    'is_active' => 1,
                ]);
            });

            return response()->json([
                'status'   => 'success',
                'os'       => 'Linux',
                'command'  => $writeCmd,
                'output'   => $rawCsv,
                'csv_data' => $csvArray,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }




    public function run(Request $request)
    {
        $command = $request->input('command');

        if (!$command) {
            return response()->json([
                'status' => 'error',
                'message' => 'No command provided'
            ], 400);
        }

        try {
            // ⚠️ SECURITY WARNING:
            // Ye sirf testing ke liye hai. Production me sirf whitelisted commands allow karein.
            $output = shell_exec($command . ' 2>&1');

            if ($output === null) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Command failed or returned no output',
                ], 500);
            }

            return response()->json([
                'status' => 'success',
                'command' => $command,
                'output' => $output,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
    public function tagStatus()
    {
        try {
            $tag_status = DB::table('TAGS_STATUS')
                ->where(function ($q) {
                    $q->where('Nozzel1', 1)
                        ->orWhere('Nozzel2', 1)
                        ->orWhere('Nozzel3', 1)
                        ->orWhere('Nozzel4', 1)
                        ->orWhere('Nozzel5', 1)
                        ->orWhere('Nozzel6', 1);
                })
                ->get();
            return response()->json([
                'status' => 'success',
                'data'   => $tag_status
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    public function atgStatus()
    {
        try {
            $atg_status = DB::table('ATG_Config')
                ->get();
            return response()->json([
                'status' => 'success',
                'data'   => $atg_status
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
        }
    public function sysparams()
    {
        try {
            $data = DB::table('SysParams')
                ->latest('id')
                ->first();
            return response()->json([
                'status' => 'success',
                'data'   => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function saleMaster(Request $request)
    {
        $query = DB::table('salesend');

        if ($request->start_date && $request->end_date) {
            $query->whereBetween('tdate', [$request->start_date, $request->end_date]);
        }

        return $query->orderBy('id', 'desc')->get();
    }



    public function detectDevice(Request $request)
    {
        try {
//            $dispenserId = $request->input('dispenserId');
            $type = $request->input('type'); // 'dispenser' or 'atg'
//            if (!isset($dispenserId)) {
//                return response()->json([
//                    'status' => 'error',
//                    'message' => 'Dispenser ID is required'
//                ], 400);
//            }

            if (!in_array($type, ['dispenser', 'atg'])) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Type must be "dispenser" or "atg"'
                ], 400);
            }

            // ✅ Decide command series based on type
            $cmdType = $type === 'dispenser' ? '200' : '100';
            // 🔹 Check latest success / pending command from DB
            $command = DB::table('commands')
                ->where('type', $cmdType)
                ->whereIn('exec', [1, 2])
                ->orderByDesc('id')
                ->first();

            if (!$command) {
                $command = DB::table('commands')
                    ->where('type', $cmdType)
                    ->where('exec', 0)
                    ->whereNull('response')
                    ->orderByDesc('id')
                    ->first();
            }

            if (!$command) {
                $commandId = DB::table('commands')->insertGetId([
                    'type'       => $cmdType,
                    'response'   => null,
                    'exec'       => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $command = DB::table('commands')->where('id', $commandId)->first();
            }

            // 🔹 Still processing
            if (empty($command->response)) {
                return response()->json([
                    'status'     => 'processing',
                    'command_id' => $command->id,
                    'message'    => 'Detect command sent, waiting for response...'
                ]);
            }

            // 🔹 Parse CSV if exists
            $rawCsv = trim($command->response);

            $csvArray = array_map(
                'str_getcsv',
                preg_split("/\r\n|\n|\r/", $rawCsv)
            );

            // 🔹 Save in DeviceConfigLog or ATGConfigLog
//            DB::transaction(function () use ($dispenserId, $type, $csvArray) {
//                if ($type === 'dispenser') {
//                    DeviceConfigLog::where('dispenser_id', $dispenserId)->update(['is_active' => 0]);
//                    DeviceConfigLog::create([
//                        'dispenser_id' => $dispenserId,
//                        'command'      => 'Detect',
//                        'csv_data'     => json_encode($csvArray),
//                        'status'       => 'success',
//                        'is_active'    => 1,
//                    ]);
//                } else {
//                    ATGConfigLog::where('tank_id', $dispenserId)->update(['is_active' => 0]);
//                    ATGConfigLog::create([
//                        'tank_id'   => $dispenserId,
//                        'command'   => 'ATG Detect',
//                        'csv_data'  => json_encode($csvArray),
//                        'status'    => 'success',
//                        'is_active' => 1,
//                    ]);
//                }
//            });

            return response()->json([
                'status'   => 'success',
                'command'  => $command->type,
                'csv_data' => $csvArray,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    public function detectDeviceAtg(Request $request)
    {
        try {
//            $dispenserId = $request->input('dispenserId');
            $type = $request->input('type'); // 'dispenser' or 'atg'
//            if (!isset($dispenserId)) {
//                return response()->json([
//                    'status' => 'error',
//                    'message' => 'Dispenser ID is required'
//                ], 400);
//            }

            if (!in_array($type, ['dispenser', 'atg'])) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Type must be "dispenser" or "atg"'
                ], 400);
            }

            // ✅ Decide command series based on type
            $cmdType = $type === 'dispenser' ? '200' : '100';
            // 🔹 Check latest success / pending command from DB
            $command = DB::table('commands')
                ->where('type', $cmdType)
                ->whereIn('exec', [1, 2])
                ->orderByDesc('id')
                ->first();

            if (!$command) {
                $command = DB::table('commands')
                    ->where('type', $cmdType)
                    ->where('exec', 0)
                    ->whereNull('response')
                    ->orderByDesc('id')
                    ->first();
            }

            if (!$command) {
                $commandId = DB::table('commands')->insertGetId([
                    'type'       => $cmdType,
                    'response'   => null,
                    'exec'       => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $command = DB::table('commands')->where('id', $commandId)->first();
            }

            // 🔹 Still processing
            if (empty($command->response)) {
                return response()->json([
                    'status'     => 'processing',
                    'command_id' => $command->id,
                    'message'    => 'Detect command sent, waiting for response...'
                ]);
            }

            // 🔹 Parse CSV if exists
            $rawCsv = trim($command->response);

            $csvArray = array_map(
                'str_getcsv',
                preg_split("/\r\n|\n|\r/", $rawCsv)
            );

            // 🔹 Save in DeviceConfigLog or ATGConfigLog
//            DB::transaction(function () use ($dispenserId, $type, $csvArray) {
//                if ($type === 'dispenser') {
//                    DeviceConfigLog::where('dispenser_id', $dispenserId)->update(['is_active' => 0]);
//                    DeviceConfigLog::create([
//                        'dispenser_id' => $dispenserId,
//                        'command'      => 'Detect',
//                        'csv_data'     => json_encode($csvArray),
//                        'status'       => 'success',
//                        'is_active'    => 1,
//                    ]);
//                } else {
//                    ATGConfigLog::where('tank_id', $dispenserId)->update(['is_active' => 0]);
//                    ATGConfigLog::create([
//                        'tank_id'   => $dispenserId,
//                        'command'   => 'ATG Detect',
//                        'csv_data'  => json_encode($csvArray),
//                        'status'    => 'success',
//                        'is_active' => 1,
//                    ]);
//                }
//            });

            return response()->json([
                'status'   => 'success',
                'command'  => $command->type,
                'csv_data' => $csvArray,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    public function getDetectUUIDs(Request $request)
    {
        try {
            $type = $request->input('type'); // dispenser / atg
            $cmdType = $type === 'dispenser' ? '200' : '100';

            $commands = DB::table('commands')
                ->where('type', $cmdType)
                ->whereIn('exec', [1, 2])
                ->orderByDesc('id')
                ->get();

            $sessions = [];

            foreach ($commands as $cmd) {
                if (!empty($cmd->response)) {
                    $sessions[] = [
                        'uuid'       => $cmd->response ?? $cmd->response, // ✅ FIX
                        'type'       => $type,
                        'command_id' => $cmd->id,
                        'status'     => $cmd->exec == 1 ? 'success' : 'pending',
                        'created_at' => $cmd->created_at,
                    ];
                }
            }

            return response()->json([
                'status' => 'success',
                'sessions' => $sessions
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }




    public function updateMode(Request $request)
    {
        try {
            $dispenserId = $request->input('dispenserId');
            $mode = $request->input('mode'); // "wired" / "wireless"
            if (!$dispenserId || !$mode) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Dispenser ID and mode are required'
                ], 400);
            }

            // Update mode in dispensers table


            return response()->json([
                'status' => 'success',
                'message' => "Mode updated to {$mode} for dispenser #{$dispenserId}"
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    public function appendSession(Request $request)
    {
        $request->validate([
            'dispenserId' => 'required|integer',
//            'uuid' => 'nullable|string'
        ]);

        try {
            // Example: append UUID to existing sessions (JSON column) or save
//            $dispenser = DB::table('DISPENSERS')->where('DevID', $request->dispenserId)->first();
            $dispenser = DB::table('DEVICES')->where('DevID', $request->dispenserId)->first();

            if (!$dispenser) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Dispenser not found'
                ], 404);
            }
            $type = 'dispenser'; // dispenser / atg
            $cmdType = $type === 'dispenser' ? '200' : '100';

            $commands = DB::table('commands')
                ->where('type', $cmdType)
                ->whereIn('exec', [1, 2])
                ->orderByDesc('id')
                ->first();
            // Assuming `sessions` is JSON column
            $sessions =$commands->response??null;
//            $sessions[] = $request->uuid;

            DB::table('DEVICES')
                ->where('DevID', $request->dispenserId)
                ->update(['detectUUID' => $sessions]);

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    /* ===============================
    GET ROUTER CONFIG
 =============================== */
    public function nwkConfig()
    {
        $config = DB::table('NWK_Config')->first();

        return response()->json([
            'status' => 'success',
            'data'   => $config
        ]);
    }
    /* ===============================
   UPDATE SSID & KEY
=============================== */
    public function update(Request $request)
    {
        /* 🔐 CHECK PERMISSION */
        $sys = DB::table('sysparams')->first();

        if (!$sys || $sys->wireless_interface_available != 1) {
            return response()->json([
                'status' => 'error',
                'message' => 'Wireless interface disabled'
            ], 403);
        }

        /* ✅ VALIDATION */
        $validator = Validator::make($request->all(), [
            'SSID' => 'required|string|max:45',
            'key'  => 'required|string|max:45',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        /* 💾 UPDATE */
        DB::table('NWK_Config')->update([
            'SSID' => $request->SSID,
            'key'  => $request->key,
//            'updated_at' => now()
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Wireless settings updated successfully'
        ]);
    }
    // app/Http/Controllers/CommandController.php
    public function getCommand($id)
    {
        $command = DB::table('commands')->where('id', $id)->first();

        if (!$command) {
            return response()->json(['message' => 'Command not found'], 404);
        }

        return response()->json($command);
    }


}
