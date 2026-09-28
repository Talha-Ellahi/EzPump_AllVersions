<?php

namespace App\Http\Controllers;

use App\Models\TankShift;
use App\Models\Tank;
use App\Models\TankShiftLog;
use App\Models\DipChartValue; // Added DipChartValue model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use DateTime;
use Carbon\Carbon; // Corrected Carbon use statement
use App\Helpers\EventHelper; // Added EventHelper
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Response; // Added Response facade

class TankShiftController extends Controller
{
    public function getActiveTankShifts(Request $request)
    {
        $validated = $request->validate([
            'tank_id' => 'nullable|integer|exists:tanks,id',
            'product_id' => 'nullable|integer|exists:PRODUCT,ICODE',
        ]);

        DB::enableQueryLog();
        $query = TankShift::query()->with('tank');

        if ($request->has('tank_id') && $request->filled('tank_id')) {
            $query->where('tank_id', $request->tank_id);
        }

        if ($request->has('product_id') && $request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        $activeTankShifts = $query->whereNull('end_time')->get();
        return Response::json($activeTankShifts);
    }

    /**
     * Converts a millimeter value to a totalizer (liter) value using the tank's dip chart.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function convertMmToTotalizer(Request $request)
    {
        $validated = $request->validate([
            'tank_id' => 'required|integer|exists:tanks,id',
            'millimeter_value' => 'required|numeric|min:0',
        ]);

        $tankId = $validated['tank_id'];
        $millimeterValue = $validated['millimeter_value'];

        try {
            $dipChartData = DipChartValue::where('tank_id', $tankId)
                                        ->orderBy('millimeter')
                                        ->get();

            if ($dipChartData->isEmpty()) {
                return Response::json([
                    'status' => 'error',
                    'message' => 'Dip chart data not found for the specified tank.'
                ], 404);
            }

            $calculatedTotalizer = $this->calculateStockVolume($millimeterValue, $dipChartData);

            return Response::json([
                'status' => 'success',
                'totalizer_value' => $calculatedTotalizer,
            ]);

        } catch (\Exception $e) {
            Log::error("Error converting MM to totalizer for tank {$tankId}: " . $e->getMessage());
            return Response::json([
                'status' => 'error',
                'message' => 'Failed to convert millimeter to totalizer.',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Calculates stock volume (liters) based on millimeter value and dip chart data.
     * This function implements linear interpolation.
     *
     * @param float $millimeterValue
     * @param \Illuminate\Database\Eloquent\Collection $dipChartData
     * @return float
     */
    private function calculateStockVolume(float $millimeterValue, $dipChartData): float
    {
        // Ensure dipChartData is sorted by millimeter
        $dipChartData = $dipChartData->sortBy('millimeter')->values();

        // Find the index where the millimeter value is greater than or equal to the input
        $index = $dipChartData->search(function ($data) use ($millimeterValue) {
            return (float)$data->millimeter >= $millimeterValue;
        });

        // If millimeterValue is greater than all values in the dip chart
        if ($index === false) {
            return (float)$dipChartData->last()->liter_value;
        }
        // If millimeterValue is less than or equal to the first value
        else if ($index === 0) {
            return (float)$dipChartData->first()->liter_value;
        }
        // If an exact match is found
        else if ((float)$dipChartData[$index]->millimeter === $millimeterValue) {
            return (float)$dipChartData[$index]->liter_value;
        }
        // Perform linear interpolation
        else {
            $lower = $dipChartData[$index - 1];
            $upper = $dipChartData[$index];

            $interpolation = ($millimeterValue - (float)$lower->millimeter) / ((float)$upper->millimeter - (float)$lower->millimeter);
            return (float)$lower->liter_value + ((float)$upper->liter_value - (float)$lower->liter_value) * $interpolation;
        }
    }

    // Method to check if a shift exists for a given tank ID
    public function checkShift($tankId)
    {
        $shift = TankShift::where('tank_id', $tankId)->first();
        if ($shift) {
            return Response::json([
                'status' => 'exists',
                'shift' => $shift
            ]);
        } else {
            return Response::json([
                'status' => 'not_exists'
            ]);
        }
    }

    // Method to update shift data
    public function updateShift(Request $request)
    {

        $validated = $request->validate([
            'id' => 'required|integer|exists:tank_shifts,id',
            'opening_mm' => 'nullable|integer',
            'closing_mm' => 'nullable|integer',
//            'manual_opening_totalizer' => 'nullable|integer',
//            'manual_closing_totalizer' => 'nullable|integer',
            'manual_closing_mm' => 'nullable|integer',
//            'manual_opening_mm' => 'nullable|integer',
            'tank_id' => 'nullable|integer|exists:tanks,id',
            'product_id' => 'nullable|integer|exists:PRODUCT,ICODE',
            'user_id' => 'nullable|integer|exists:users,id',
//            'is_modified' => 'nullable|boolean',
            'start_time' => 'nullable|date',
            'end_time' => 'nullable|date',
        ]);
//dd($request->all());
        $shift = TankShift::find($request->id);

        // Get ATG setting
        $atgInstalled = DB::table('SysConfig')->value('ATGEnable');
        $atgInstalled = (int) $atgInstalled;

        if ($atgInstalled == 1 && $shift->tank_id) {
            $lastFuelStatus = $this->getLastFuelStatus($shift->tank_id);
            if ($lastFuelStatus) {
//                $validated['closing_totalizer'] = $lastFuelStatus->Qty;
                $validated['closing_mm'] = $lastFuelStatus->Level;
            }
//        } elseif ($request->has('manual_closing_totalizer') && $request->has('manual_closing_mm') && $shift->tank_id) {
        } elseif ($request->has('manual_closing_totalizer') && $request->has('manual_closing_mm') && $shift->tank_id) {
            // Manual measurement - insert into both fuel status tables
            $now = Carbon::now();
            $tankId = $shift->tank_id;
            $productId = $shift->product_id;
            // Insert into LastFuelStatus
            DB::table('LastFuelStatus')->updateOrInsert(
                ['TID' => $tankId],
                [
                    'Qty' => $request->manual_closing_totalizer,
                    'Level' => $request->manual_closing_mm,
                    'tdate' => $now
                ]
            );
//dd($request->manual_closing_totalizer);
            // Insert into FuelStatus
            DB::table('FuelStatus')->insert([
                'TID' => $tankId,
                'Qty' => $request->manual_closing_totalizer,
                'Level' => $request->manual_closing_mm,
                'tdate' => $now,
                // Optional fields can be set to null or default if needed
                'PAddr' => null,
                'WaterLevel' => null,
                'TEMP' => null,
            ]);
//            $validated['closing_totalizer'] = $request->manual_closing_totalizer;
            $validated['closing_mm'] = $request->manual_closing_mm;
        }

        $shift->update($validated);

        return Response::json([
            'status' => 'success',
            'shift' => $shift
        ]);
    }

    // Method to create a new shift
    public function openShift(Request $request)
    {
        $validated = $request->validate([
            'tank_id' => 'required|integer|exists:tanks,id',
            'product_id' => 'required|integer|exists:PRODUCT,ICODE',
            'user_id' => 'required|integer|exists:users,id',
            'is_modified' => 'nullable|boolean',
        ]);
        // Get ATG setting
        $atgInstalled = DB::table('SysConfig')->value('ATGEnable');
        $atgInstalled = (int) $atgInstalled; // Ensure it's an integer
        if($atgInstalled){
            $lastFuelStatus = $this->getLastFuelStatus($request->tank_id);
            if (!$lastFuelStatus) {
                return Response::json([
                    'status' => 'error',
                    'message' => 'No fuel status found for this tank.'
                ], 400);
            }
        $validated['opening_totalizer'] = $lastFuelStatus->Level;
        }else{
        $validated['opening_totalizer'] = 0;
        }
        $validated['manual_opening_totalizer'] = 0;
        $validated['manual_closing_totalizer'] = 0;



        $validated['start_time'] = Carbon::now();

        $shift = TankShift::create($validated);

        $this->transferTankShiftData($shift, 1,$shift->id);

        // Prepare payload for the event
        $payload = $shift->toArray();
        // Ensure standard MySQL datetime format for relevant fields
        foreach (['created_at', 'updated_at', 'start_time', 'end_time'] as $dateField) {
            if (isset($payload[$dateField]) && $payload[$dateField]) {
                try {
                    $parsedDate = Carbon::parse($payload[$dateField]);
                    $payload[$dateField] = $parsedDate->format('Y-m-d H:i:s');
                } catch (\Exception $e) {
                    Log::error("Failed to parse date field '{$dateField}' for event send in openShift: " . $e->getMessage(), ['value' => $payload[$dateField]]);
                    $payload[$dateField] = null; // Nullify on error
                }
            }
        }
        $eventMapping = Config::get('event_config.events_mapping');
        $eventType = $eventMapping['tank_shift_opening'] ?? null;

        if ($eventType !== null) {
            $tankshiftId =$request->tank_id;
            EventHelper::sendEventData($eventType, $payload,$tankshiftId);
        } else {
            Log::error("Event type 'tank_shift_opening' not found in event_config.php");
        }

        return Response::json([
            'status' => 'success',
            'shift' => $shift
        ]);
    }

    public function updateShiftAtg(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|integer|exists:tanks,id',
            'manual_closing_mm' => 'nullable|integer',
            'manual_closing_totalizer' => 'nullable|integer',
        ]);

        $now = Carbon::now();

        // 🔹 1️⃣ Always update the tank from request
        $shift = TankShift::where('tank_id', $request->id)
            ->whereNull('end_time')
            ->first();

        if ($shift) {
            $tankConfig = DB::table('ATGs')->where('TankID', $request->id)->first();
            $isAtgTank = $tankConfig->is_atg ?? 0;

            // ATG tank
            if ($isAtgTank == 1) {
                $lastFuel = $this->getLastFuelStatus($shift->tank_id);
                if ($lastFuel) {
                    $validated['closing_mm'] = $lastFuel->Level > 10000 ? $lastFuel->Level  : $lastFuel->Level;
                } else {
                    $validated['closing_mm'] = $shift->closing_mm;
                }
            }
            // Manual tank → update only if manual data sent
            else {
//                dd('is atg sid7');
                if ($request->filled('manual_closing_mm') && $request->filled('manual_closing_totalizer')) {
                    $manualMm = $request->manual_closing_mm / 100;
                    $manualTot = $request->manual_closing_totalizer;

                    DB::table('LastFuelStatus')->updateOrInsert(
                        ['TID' => $shift->tank_id],
                        ['Qty' => $manualTot, 'Level' => $manualMm*100, 'tdate' => $now]
                    );

                    DB::table('FuelStatus')->insert([
                        'TID' => $shift->tank_id,
                        'Qty' => $manualTot,
                        'Level' => $manualMm,
                        'tdate' => $now,
                        'PAddr' => null,
                        'WaterLevel' => null,
                        'TEMP' => null,
                    ]);

                    $validated['closing_mm'] = $manualMm;
                } else {
                    $validated['closing_mm'] = $shift->closing_mm;
                }
            }

            $shift->update($validated);
        }

        // 🔹 2️⃣ Update all other ATG tanks (except request tank)
        $atgTanks = DB::table('ATGs')
            ->where('is_atg', 1)
            ->where('TankID', '!=', $request->id)
            ->pluck('TankID');

        foreach ($atgTanks as $tankId) {
            $shiftAtg = TankShift::where('tank_id', $tankId)
                ->whereNull('end_time')
                ->first();

            if ($shiftAtg) {
                $lastFuel = $this->getLastFuelStatus($tankId);
                if ($lastFuel) {
                    $shiftAtg->update([
                        'closing_mm' => $lastFuel->Level > 10000 ? $lastFuel->Level  : $lastFuel->Level
                    ]);
                }
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Shift(s) updated successfully',
        ]);
    }


    /**
     * Method to close a tank shift and record shift log.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id TankShift ID
     * @return \Illuminate\Http\JsonResponse
     */

    public function closeShift(Request $request, int $id,$calendarNewId)
    {

        $shift = TankShift::findOrFail($id);
        $tank = \App\Models\Tank::find($shift->tank_id);
        $shift->end_time = Carbon::now();
        // ATG setting
        $atgInstalled = (int) DB::table('SysConfig')->value('ATGEnable');
        // Shift close min duration
        $shiftCloseDurationInMinutes = (int) DB::table('settings')
            ->where('key', 'shift_min_close_duration')
            ->value('value');

        $startDate = new DateTime($shift->start_time);
        $shiftCloseDurationInHours = $shiftCloseDurationInMinutes / 60;
        $currentTime = Carbon::now();

        $interval = $currentTime->diff($startDate);
        $hoursDifference = $interval->h + ($interval->days * 24);

        // Restrict closing if duration not met (optional)
    if ($hoursDifference < $shiftCloseDurationInHours) {
        return response()->json([
            'error' => 'Tank Shift cannot be closed as it has started less than the required duration.'
        ], 400);
    }

        $startTime = $shift->start_time;
        $endTime = Carbon::now();

        // --- Sales ---
        $nozzleIds = explode(',', $tank->nozzle_ids);
        $salesTotal = \App\Models\SaleData::whereIn('FC_NZNo', $nozzleIds)
            ->whereBetween('tdate', [$startTime, $endTime])
            ->sum('qty');

        // --- Purchases (positive stock change) ---
        $positiveStock = DB::table('tank_stock_ledger')
            ->where('tank_id', $tank->id)
            ->whereBetween('created_at', [$startTime, $endTime])
            ->where('stock_change', '>', 0)
            ->sum('stock_change');

        // --- Manual closing stock calculation (Litres → Dip) ---
        $normalizedMm = $shift->opening_mm > 10000
            ? $shift->opening_mm / 100
            : $shift->opening_mm;
        $openingLitres = $this->getLitresFromDip($normalizedMm, $tank->id);

        // ⚡ FIX: stock_change litres me hota hai, isliye multiply(100) hata diya
        $closingLitres = $openingLitres + $positiveStock - $salesTotal;
//        $manualClosingDip = $this->getDipFromLitres($closingLitres, $tank->id);
        $manualClosingDip = $shift->closing_mm;



        // Old mix logic (mm - litres) commented for reference
//    $manualClosingDip = $shift->opening_mm - $salesTotal - ($positiveStock * 100);
//    $manualClosingTotalizer = $shift->opening_totalizer - $salesTotal - ($positiveStock*100);

        // --- Closing values based on ATG ---
        if ($atgInstalled == 1) {
            $lastFuelStatus = $this->getLastFuelStatus($shift->tank_id);

            if (!$lastFuelStatus) {
                return Response::json([
                    'status' => 'error',
                    'message' => 'No fuel status found for this tank.'
                ], 400);
            }

            $closingTotalizer = $lastFuelStatus->Qty;

            // ⚡ FIX: Normalize mm from ATG (127000 → 1270)
            $closingMm = ($lastFuelStatus->Level > 10000) ? $lastFuelStatus->Level / 100 : $lastFuelStatus->Level;

            $newShiftOpeningTotalizer = $lastFuelStatus->Qty;
            $newShiftOpeningMm = $closingMm;
        } else { // Manual case
            $closingTotalizer = $shift->closing_mm;
            $closingMm = $manualClosingDip ?? 0;
            $newShiftOpeningTotalizer = $shift->closing_mm;
            $newShiftOpeningMm = $manualClosingDip; // ✅ use calculated dip
        }
        // --- Update closing dip in current shift ---
        $shift->update([
            'closing_mm' => $closingMm, // ✅ normalized value save hogi
        ]);

        $shiftData = $shift->toArray();

        unset($shiftData['id']);

        // --- Stock Ledger + Sales Data Logs ---
        $stockLedger = DB::table('tank_stock_ledger')
            ->where('tank_id', $shift->tank_id)
            ->whereBetween('created_at', [$startTime, $endTime])
            ->get();

//        $salesData = \App\Models\saledata::whereIn('FC_NZNo', $nozzleIds)
//            ->whereBetween('tdate', [$startTime, $endTime])
//            ->select('p_mode', DB::raw('SUM(qty) as total_quantity'), DB::raw('SUM(amt) as total_amount'))
//            ->groupBy('p_mode')
//            ->get()
//            ->toArray();
        $shiftIds = DB::table('shift')
            ->whereIn('pump_id', $nozzleIds)
//                    ->whereDate('start_date', date('Y-m-d'))  // ya shift ke start_date ke hisaab se
            ->pluck('id')  // array of shift IDs
            ->toArray();
// 3️⃣ Shift Payment Wise Sale for all these shifts
        $salesData = DB::table('shift_payment_wise_sale')
            ->whereIn('shift_id', $shiftIds)
            ->select(
                'paymentmethod_id as p_mode',
                DB::raw('SUM(total_qty) as total_quantity'),
                DB::raw('SUM(total_sale) as total_amount')
            )
            ->groupBy('paymentmethod_id')
            ->get()
            ->toArray();

        $logData = array_merge($shiftData, [
            'data' => [
                'stock_ledger' => $stockLedger,
                'sales_data'   => $salesData,
            ]
        ], ['end_time' => Carbon::now()]);

        $this->transferTankShiftLogData($logData);
        $this->transferTankShiftData($shift, 3,$shift->id);

        // --- Closing Event ---
        $closingPayload = $this->formatPayloadDates($shift->toArray());
        $eventMapping = Config::get('event_config.events_mapping');
        $eventType = $eventMapping['tank_shift_closing'] ?? null;

        if ($eventType !== null) {
            EventHelper::sendEventData($eventType, $closingPayload, $shift->tank_id);
        }


        // --- Update Tank Stock ---
        $existingStock = DB::table('tank_stock')
            ->where('tank_id', $shift->tank_id)
            ->first();

        if ($existingStock) {
            DB::table('tank_stock')
                ->where('tank_id', $shift->tank_id)
                ->update([
                    'stock_value' => $closingLitres,
                    'millimeter'  => $closingMm, // ✅ normalized dip save hoga
                    'updated_at'  => now(),
                ]);
        } else {
            DB::table('tank_stock')->insert([
                'tank_id'     => $shift->tank_id,
                'stock_value' => $closingLitres,
                'millimeter'  => $closingMm, // ✅ normalized
                'created_at'  => now(),
                'updated_at'  => now(),

            ]);
        }
        $shift->delete();

        // --- Create New Shift ---
        $newShift = TankShift::create([
            'tank_id'     => $shift->tank_id,
            'product_id'  => $shift->product_id,
            'opening_mm'  => $newShiftOpeningMm,
            'closing_mm'  => 0,
            'user_id'     => $shift->user_id,
            'start_time'  => Carbon::now(),
            'calendar_id'=>$calendarNewId
        ]);
        DB::table('shift_calendars')->where('id',$calendarNewId)
            ->update([
                'work_date'=> now()->format('Y-m-d'),
            ]);
        $this->transferTankShiftData($newShift, 1,$newShift->id);

        // --- New Opening Event ---
        $newShiftPayload = $this->formatPayloadDates($newShift->toArray());
        $eventType = $eventMapping['tank_shift_opening'] ?? null;
        if ($eventType !== null) {
            EventHelper::sendEventData($eventType, $newShiftPayload, $shift->tank_id);
        }

        return Response::json([
            'status' => 'success',
            'shift'  => $newShift
        ]);
    }
    private function formatPayloadDates(array $payload): array {
        foreach (['created_at', 'updated_at', 'start_time', 'end_time'] as $dateField)
        { if (!empty($payload[$dateField]))
    { try
    { $parsedDate = Carbon::parse($payload[$dateField]);
        $payload[$dateField] = $parsedDate->format('Y-m-d H:i:s');
    }
    catch (\Exception $e)
    { Log::error("Failed to parse date field '{$dateField}' in formatPayloadDates: " . $e->getMessage(),
        ['value' => $payload[$dateField]]);
        $payload[$dateField] = null; } } } return $payload; }
    /**
     * Convert MM → Litres using dip chart
     */
    private function getLitresFromDip($mm, $tankId)
    {
        // ⚡ FIX: Normalize mm from DB values (127000 → 1270)
//        if ($mm > 10000) {
//            $mm = $mm / 100;
//        }

        // Nearest 10 mm round
        $dipMm = round($mm, -1);

        // Try exact match
        $litres = DB::table('dip_chart_values')
            ->where('tank_id', $tankId)
            ->where('millimeter', $dipMm)
            ->value('liter_value');
//dd($litres);
//        Log::info($dipMm." Final Insert Data for Tank Shift Logs ".$litres);

        if ($litres) {
            return (float) $litres;
        }

        // Agar exact nahi mila → nearest available (lower or higher)
        $nearestDip = DB::table('dip_chart_values')
            ->where('tank_id', $tankId)
            ->orderByRaw("ABS(millimeter - {$mm})")
            ->value('liter_value');

//        Log::info($nearestDip." Final Insert Data for Tank Shift Logs " .$mm);

        return $nearestDip ? (float) $nearestDip : 0;
    }

    public function closeAtgTankShifts(Request $request)
    {
        try {
            DB::beginTransaction();
//$this->updateShiftAtg();

            $tankIds = $request->input('tank_ids', []);

            $activeShiftsAtg = TankShift::join('ATGs', 'tank_shifts.tank_id', '=', 'ATGs.TankID')
                ->whereNull('tank_shifts.end_time')
                ->where('ATGs.is_atg', 1)
                ->when(!empty($tankIds), function ($q) use ($tankIds) {
                    $q->whereIn('tank_shifts.tank_id', $tankIds);
                })
                ->select('tank_shifts.*', 'ATGs.is_atg')
                ->get();
//dd($activeShiftsAtg->toArray());

            // Check if there are any active shifts to close
            if ($activeShiftsAtg->isEmpty()) {
                DB::rollBack();
                return response()->json([
                    'status'  => 'success',
                    'message' => 'No active ATG tank shifts found to close.',
                ]);
            }

            $shiftCloseDurationInMinutes = (int) DB::table('settings')
                ->where('key', 'shift_min_close_duration')
                ->value('value');
            Log::info("⚙️ Found {$activeShiftsAtg->count()} ATG tanks to close.");
            $processedProducts = [];
            $calendarId = null;

            foreach ($activeShiftsAtg as $tankId) {

                $tank = DB::table('tanks')->where('id', $tankId->tank_id)->first();
                if (!$tank) continue;

                $productId = (int) $tank->product_id;

                if (in_array($productId, [1, 2, 3]) && !in_array($productId, $processedProducts)) {
                    $currentCalendarId = $this->closeByTank($tankId->tank_id);
                    $calendarId = $currentCalendarId; // last one should be same for all
                    $processedProducts[] = $productId;
                }
            }
//            dd($tankIds);
            if (!$calendarId && $activeShiftsAtg->isNotEmpty()) {
                // Fallback: use first tank if no product was closed
                $firstShift = $activeShiftsAtg->first();
                $calendarId = $this->closeByTank($firstShift->tank_id);
            }
//            dd($calendarId);
            foreach ($activeShiftsAtg as $shift) {
                // === Fetch Stock Ledger + Sales Data ===
                $shiftData = $shift->toArray();
//                unset($shiftData['id']);
                $tankId = $shift->tank_id;
                $lastFuelStatus = $this->getLastFuelStatus($tankId);
//                $startTime = Carbon::parse($shift->start_time);
//                $minutesDiff = $startTime->diffInMinutes(Carbon::now());

                if (!$lastFuelStatus) {
                    Log::warning("⚠️ No ATG data found for Tank {$tankId}, skipping...");
                    continue;
                }

                $closingMm = $lastFuelStatus->Level > 10000 ? $lastFuelStatus->Level : $lastFuelStatus->Level;

//               $test= $shift->update([
//                    'closing_mm' => $closingMm,
//                    'end_time'   => now(),
//                ]);
                $shiftModel = TankShift::where('tank_id', $tankId)->first();

                if (!$shiftModel) {
                    Log::warning("⚠️ No active shift found for Tank {$tankId}, skipping...");
                    continue;
                }

// 2️⃣ Update values
                $shiftModel->closing_mm = $closingMm; // or $closingMm
                $shiftModel->end_time   = now();

// 3️⃣ Save
                $shiftModel->save();

// 4️⃣ Now $shiftModel has updated values
//                dd($shiftModel->toArray(), $closingMm);

                Log::info("📡ATG closing used for Tank  atg{$tankId} | MM: {$closingMm}");
                $startTime = Carbon::parse($shift->start_time);
                $endTime = Carbon::now();

               $tank = \App\Models\Tank::find($shift->tank_id);
               
               if (!$tank) {
                   Log::warning("⚠️ Tank not found for Tank ID {$shift->tank_id}, skipping...");
                   continue;
               }
               
                $nozzleIds = explode(',', $tank->nozzle_ids);

                $stockLedger = DB::table('tank_stock_ledger')
                    ->where('tank_id', $tankId)
                    ->whereBetween('created_at', [$startTime, $endTime])
                    ->get();

                $shiftIds = DB::table('shift')
                    ->whereIn('pump_id', $nozzleIds)
//                    ->whereDate('start_date', date('Y-m-d'))  // ya shift ke start_date ke hisaab se
                    ->pluck('id')  // array of shift IDs
                    ->toArray();
// 3️⃣ Shift Payment Wise Sale for all these shifts
                $salesData = DB::table('shift_payment_wise_sale')
                    ->whereIn('shift_id', $shiftIds)
                    ->select(
                        'paymentmethod_id as p_mode',
                        DB::raw('SUM(total_qty) as total_quantity'),
                        DB::raw('SUM(total_sale) as total_amount')
                    )
                    ->groupBy('paymentmethod_id')
                    ->get()
                    ->toArray();
//dd($shiftData,$closingMm);
                $tankRecord=$shiftModel->toArray();
                unset($tankRecord['id']);
                $logData = array_merge($tankRecord, [
                    'data' => [
                        'stock_ledger' => $stockLedger,
                        'sales_data'   => $salesData,
                    ],
                    'end_time' => $endTime,
                ]);

                $this->transferTankShiftLogData($logData);
                $shift->opening_mm = ($shift->opening_mm ?? 0);
//                $shift->closing_dip = ($shift->closing_dip ?? 0) * 100;
                $this->transferTankShiftData($shift, 3,$shift->id);

                // === Start new shift ===
                $newShift=TankShift::create([
                    'tank_id'     => $tankId,
                    'product_id'  => $shift->product_id,
                    'opening_mm'  => $closingMm,
                    'closing_mm'  => 0,
                    'user_id'     => $shift->user_id,
                    'start_time'  => now(),
                    'calendar_id'=>$calendarId
                ]);
                DB::table('shift_calendars')->where('id',$calendarId)
                    ->update([
                       'work_date'=> now()->format('Y-m-d'),
                    ]);
                $this->transferTankShiftData($newShift, 1,$newShift->id);

                $shift->delete();
                Log::info("✅ Tank {$tankId} shift closed and new started | Mode: ATG");
            }

            DB::commit();
            return response()->json([
                'status'  => 'success',
                'message' => 'All ATG tank shifts closed successfully.',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("❌ Error in closeAtgTankShifts: " . $e->getMessage());
            return response()->json([
                'status'  => 'error',
                'message' => 'Error closing ATG tank shifts: ' . $e->getMessage(),
            ], 500);
        }
    }
//    public function calenderData($shifts)
//    {
//        // Saare IDs ascending order mein le aao
//        $ids = DB::table('shift_calendars')
//            ->orderBy('id')
//            ->pluck('id')
//            ->toArray();
//
//        $expectedId = 1;
//
//        foreach ($ids as $id) {
//            if ($id != $expectedId) {
//                // jahan gap mila wahi return
//                return $expectedId;
//            }
//            $expectedId++;
//        }
//
//        // Agar koi gap nahi mila
//        return $expectedId;
//    }
    /**
     * Close logic for SINGLE tank (used internally)
     * Returns calendar_id on success
     *
     * @param int $tank_id
     * @return int|null
     * @throws \Exception
     */
    /**
     * Close SINGLE tank's product flag (used for product-level close)
     * Returns current calendar ID
     */
    private function closeByTank(int $tank_id): int
    {

        $tank = DB::table('tanks')->where('id', $tank_id)->first();

        if (!$tank) {
            throw new \Exception("Tank not found: {$tank_id}");
        }

        $productId = (int) $tank->product_id;

        if (!in_array($productId, [1, 2, 3])) {
            throw new \Exception("Invalid product type: {$productId}");
        }

        // Find the currently open shift for this tank
        $activeShift = TankShift::where('tank_id', $tank_id)
            ->whereNull('end_time')
            ->first();

        if (!$activeShift) {
            throw new \Exception("No active shift for tank {$tank_id}");
        }

        $workDate = Carbon::parse($activeShift->start_time)->startOfDay()->toDateString();



        // ────────────────────────────────────────────────
        // Find the calendar row linked to this shift first
        // (work_date can differ from start_time's date because of timezone)
        // ────────────────────────────────────────────────
        $calendar = DB::table('shift_calendars')
            ->where('id', $activeShift->calendar_id)
            ->first();

        if (!$calendar) {
            $calendar = DB::table('shift_calendars')
                ->where('work_date', $workDate)
                ->where(function ($q) {
                    $q->whereNull('shift_end_time')
                        ->orWhere('petrol_closed', 0)
                        ->orWhere('diesel_closed', 0)
                        ->orWhere('hi_octane_closed', 0);
                })
                ->latest('id')
                ->first();
        }

        if (!$calendar) {
            throw new \Exception("No shift calendar found for tank {$tank_id} (calendar id: " . ($activeShift->calendar_id ?? 'null') . ", work date: {$workDate})");
        }


        // ────────────────────────────────────────────────
        // Prepare update
        // ────────────────────────────────────────────────
        $update = ['updated_at' => now()];
        $sendData = [
            'calendar_id'       => $calendar->id,
            'work_date'         => $workDate,
            'petrol_closed'     => $calendar->petrol_closed,
            'diesel_closed'     => $calendar->diesel_closed,
            'hi_octane_closed'  => $calendar->hi_octane_closed,
            'type'              => 3, // product close action
            'shift_start_time'  => $calendar->shift_start_time,
            'created_at'        => now(),
            'updated_at'        => now(),
        ];

        $hasChange = false;

        if ($productId === 1 && $calendar->petrol_closed == 0) {
            $update['petrol_closed'] = 1;
            $sendData['petrol_closed'] = 1;
            $hasChange = true;
        } elseif ($productId === 2 && $calendar->diesel_closed == 0) {
            $update['diesel_closed'] = 1;
            $sendData['diesel_closed'] = 1;
            $hasChange = true;
        } elseif ($productId === 3 && $calendar->hi_octane_closed == 0) {
            $update['hi_octane_closed'] = 1;
            $sendData['hi_octane_closed'] = 1;
            $hasChange = true;
        }

        if (!$hasChange) {
            Log::info("Product already closed - no change needed", [
                'calendar_id' => $calendar->id,
                'product_id'  => $productId,
            ]);
            return $calendar->id;
        }

        // ────────────────────────────────────────────────
        // Check if this is the FIRST product close of a new cycle
        // (i.e. the last row before this update was 000)
        // ────────────────────────────────────────────────
        $lastCalendar = DB::table('shift_calendars')
            ->where('id', $activeShift->calendar_id)
            ->first();

        $isFirstCloseOfCycle = $lastCalendar &&
            $lastCalendar->petrol_closed == 0 &&
            $lastCalendar->diesel_closed == 0 &&
            $lastCalendar->hi_octane_closed == 0;

        // Apply the product close
        DB::table('shift_calendars')
            ->where('id', $calendar->id)
            ->update($update);

        DB::table('shift_calendar_send')->insert($sendData);

//        Log::info("Product closed", [
//            'calendar_id' => $calendar->id,
//            'product_id'  => $productId,
//            'flags_now'   => "{$update['petrol_closed'] ?? $calendar->petrol_closed} "
//                . "{$update['diesel_closed'] ?? $calendar->diesel_closed} "
//                . "{$update['hi_octane_closed'] ?? $calendar->hi_octane_closed}",
//        ]);

        // If this was the first close → create next blank row (000)
        if ($isFirstCloseOfCycle) {
            $newBlankId = DB::table('shift_calendars')->insertGetId([
                'work_date'         => now()->format('Y-m-d'),
                'shift_start_time'  => $calendar->shift_start_time,
                'petrol_closed'     => 0,
                'diesel_closed'     => 0,
                'hi_octane_closed'  => 0,
                'created_at'        => now(),
                'updated_at'        => now(),
            ]);

            DB::table('shift_calendar_send')->insert([
                'calendar_id'       => $newBlankId,
                'work_date'         => now()->format('Y-m-d'),
                'shift_start_time'  => $calendar->shift_start_time,
                'petrol_closed'     => 0,
                'diesel_closed'     => 0,
                'hi_octane_closed'  => 0,
                'type'              => 1,
                'created_at'        => now(),
                'updated_at'        => now(),
            ]);

            Log::info("First product closed in cycle → created next blank calendar", [
                'new_calendar_id' => $workDate,
            ]);
        }

        // ────────────────────────────────────────────────
        // Refresh calendar state after update
        // ────────────────────────────────────────────────
        $calendar = DB::table('shift_calendars')->find($calendar->id);

        // ────────────────────────────────────────────────
        // Check if this was the LAST product (all 3 closed)
        // ────────────────────────────────────────────────
        $closedCount = (int)$calendar->petrol_closed
            + (int)$calendar->diesel_closed
            + (int)$calendar->hi_octane_closed;

        if ($closedCount === 3 && is_null($calendar->shift_end_time)) {
            $endTime = now();
            $minutes = Carbon::parse($calendar->shift_start_time)->diffInMinutes($endTime);

            DB::table('shift_calendars')
                ->where('id', $calendar->id)
                ->update([
                    'shift_end_time'   => $endTime,
                    'total_duration'   => $minutes,
                    'updated_at'       => now(),
                ]);

            DB::table('shift_calendar_send')
                ->where('calendar_id', $calendar->id)
                ->update([
                    'shift_end_time'   => $endTime,
                    'total_duration'   => $minutes,
                    'updated_at'       => now(),
                ]);

            // Create new blank calendar for next cycle
//            $newCalendarId = DB::table('shift_calendars')->insertGetId([
//                'work_date'         => $workDate,
//                'shift_start_time'  => $endTime,           // ← usually now()
//                'petrol_closed'     => 0,
//                'diesel_closed'     => 0,
//                'hi_octane_closed'  => 0,
//                'created_at'        => now(),
//                'updated_at'        => now(),
//            ]);
//
//            DB::table('shift_calendar_send')->insert([
//                'calendar_id'       => $newCalendarId,
//                'work_date'         => $workDate,
//                'shift_start_time'  => $endTime,
//                'petrol_closed'     => 0,
//                'diesel_closed'     => 0,
//                'hi_octane_closed'  => 0,
//                'type'              => 1,
//                'created_at'        => now(),
//                'updated_at'        => now(),
//            ]);
            $calendarNew = DB::table('shift_calendars')
//                ->where('work_date', $workDate)
//                ->where('id', $activeShift->calendar_id)
                ->where(function ($q) {
                    $q->whereNull('shift_end_time')
                        ->orWhere('petrol_closed', 0)
                        ->orWhere('diesel_closed', 0)
                        ->orWhere('hi_octane_closed', 0);
                })
                ->latest('id')
                ->first();
            Log::info("All products closed → shift ended + new calendar created", [
                'ended_calendar_id' => $calendar->id,
//                'new_calendar_id'   => $newCalendarId,
            ]);

            return  $calendarNew->id;
        }
        $calendarNew = DB::table('shift_calendars')
//            ->where('work_date', $workDate)
//                ->where('id', $activeShift->calendar_id)
            ->where(function ($q) {
                $q->whereNull('shift_end_time')
                    ->orWhere('petrol_closed', 0)
                    ->orWhere('diesel_closed', 0)
                    ->orWhere('hi_octane_closed', 0);
            })
            ->latest('id')
            ->first();
        return $calendarNew->id;
    }    /**
     * Close multiple MANUAL tanks
     */


    public function closeSingleManualTank(Request $request)
    {
        try {
            DB::beginTransaction();

            $tankIds = $request->input('tank_ids', []);
            $manualMmInput = $request->input('manual_mm', []);
            $manualTotalizersInput = $request->input('manual_totalizers', []);
//dd($tankIds);
            $activeShiftsManual = TankShift::join('ATGs', 'tank_shifts.tank_id', '=', 'ATGs.TankID')
                ->whereNull('tank_shifts.end_time')
                ->where(function ($q) {
                    $q->whereNull('ATGs.is_atg')
                        ->orWhere('ATGs.is_atg', 0);
                })
                ->when(!empty($tankIds), function ($q) use ($tankIds) {
                    $q->whereIn('tank_shifts.tank_id', $tankIds);
                })
                ->select('tank_shifts.*', 'ATGs.is_atg')
                ->get();
//dd($activeShiftsManual);
            $shiftCloseDurationInMinutes = (int) DB::table('settings')
                ->where('key', 'shift_min_close_duration')
                ->value('value');
//            $calendarNewId=$this->calenderData($activeShiftsManual);
//            $calendarNewId=$this->closeByTank($tankIds);
            Log::info("⚙️ Found {$activeShiftsManual->count()} Manual tanks to close.");
            $processedProducts = [];
            $calendarId = null;

            foreach ($tankIds as $tankId) {
                $tank = DB::table('tanks')->where('id', $tankId)->first();
                if (!$tank) continue;

                $productId = (int) $tank->product_id;

                if (in_array($productId, [1, 2, 3]) && !in_array($productId, $processedProducts)) {
                    $currentCalendarId = $this->closeByTank($tankId);
                    $calendarId = $currentCalendarId; // last one should be same for all
                    $processedProducts[] = $productId;
                }
            }
//            dd($tankIds);
            if (!$calendarId) {
                // Fallback: use first tank if no product was closed
                $calendarId = $this->closeByTank($tankIds[0]);
            }
//dd($calendarId);
            Log::info('calendar manual new add'.$calendarId);
            // Refresh final calendar state
            $calendar = DB::table('shift_calendars')->find($calendarId);
//            dd($calendar);
            foreach ($activeShiftsManual as $shift) {
                $tankId = $shift->tank_id;
                $manualMm = $manualMmInput[$tankId] ?? $shift->closing_mm ?? 0;
                $manualTotalizer = $manualTotalizersInput[$tankId] ?? 0;
                $now = now();

                // Update dip values
                DB::table('LastFuelStatus')->updateOrInsert(
                    ['TID' => $tankId],
                    ['Qty' => $manualTotalizer, 'Level' => $manualMm, 'tdate' => $now]
                );

                DB::table('FuelStatus')->insert([
                    'TID' => $tankId,
                    'Qty' => $manualTotalizer,
                    'Level' => $manualMm,
                    'tdate' => $now,
                    'PAddr' => null,
                    'WaterLevel' => null,
                    'TEMP' => null,
                ]);

                $shift->update([
                    'closing_mm' => $manualMm,
                    'end_time'   => $now,
                ]);

//                Log::info("🧾Manual dip updated for Tank sanat {$tankId} | MM: {$manualMm}, Totalizer: {$manualTotalizer}");

                // === Fetch Stock Ledger + Sales Data ===
                $shiftData = $shift->toArray();
                unset($shiftData['id']);

                $startTime = Carbon::parse($shift->start_time);
                $endTime = Carbon::now();

                $tank = \App\Models\Tank::find($shift->tank_id);
                $nozzleIds = explode(',', $tank->nozzle_ids);

                $stockLedger = DB::table('tank_stock_ledger')
                    ->where('tank_id', $tankId)
                    ->whereBetween('created_at', [$startTime, $endTime])
                    ->get();

//                $salesData = \App\Models\saledata::whereIn('FC_NZNo', $nozzleIds)
//                    ->whereBetween('tdate', [$startTime, $endTime])
//                    ->select(
//                        'p_mode',
//                        DB::raw('SUM(qty) as total_quantity'),
//                        DB::raw('SUM(amt) as total_amount')
//                    )
//                    ->groupBy('p_mode')
//                    ->get()
//                    ->toArray();
                $shiftIds = DB::table('shift')
                    ->whereIn('pump_id', $nozzleIds)
//                    ->whereDate('start_date', date('Y-m-d'))  // ya shift ke start_date ke hisaab se
                    ->pluck('id')  // array of shift IDs
                    ->toArray();
// 3️⃣ Shift Payment Wise Sale for all these shifts
                $salesData = DB::table('shift_payment_wise_sale')
                    ->whereIn('shift_id', $shiftIds)
                    ->select(
                        'paymentmethod_id as p_mode',
                        DB::raw('SUM(total_qty) as total_quantity'),
                        DB::raw('SUM(total_sale) as total_amount')
                    )
                    ->groupBy('paymentmethod_id')
                    ->get()
                    ->toArray();

                $logData = array_merge($shiftData, [
                    'data' => [
                        'stock_ledger' => $stockLedger,
                        'sales_data'   => $salesData,
                    ],
                    'end_time' => $endTime,
                ]);

                $this->transferTankShiftLogData($logData);
                $this->transferTankShiftData($shift, 3,$shift->id);

//sanat
                $newShift=TankShift::create([
                    'tank_id'     => $tankId,
                    'product_id'  => $shift->product_id,
                    'opening_mm'  => $manualMm,
                    'closing_mm'  => 0,
                    'user_id'     => $shift->user_id,
                    'start_time'  => now(),
                    'calendar_id'=>$calendar->id
                ]);
                DB::table('shift_calendars')->where('id',$calendar->id)
                    ->update([
                        'work_date'=> now()->format('Y-m-d'),
                    ]);
                $this->transferTankShiftData($newShift, 1,$shift->id);

                $shift->delete();
                Log::info("✅ Tank {$tankId} shift closed and new started | Mode: MANUAL");
            }

            DB::commit();
            return response()->json([
                'status'  => 'success',
                'message' => 'All Manual tank shifts closed successfully.',
            ]);
        } catch (\Exception $e) {
//            dd($e);
            DB::rollBack();
            Log::error("❌ Error in closeManualTankShifts: " . $e->getMessage());
            return response()->json([
                'status'  => 'error',
                'message' => 'Error closing manual tank shifts: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Convert Litres → MM using dip chart
     */
    private function getDipFromLitres($litres, $tankId)
    {
        $mm = DB::table('dip_chart_values')
            ->where('tank_id', $tankId)
            ->where('liter_value', '<=', $litres)
            ->orderByDesc('liter_value')
            ->value('millimeter');

//        Log::info('mm closing dip check value insert '.$mm);

        return $mm !== null ? (float) $mm : 0;
    }

    public function closeShift_old(Request $request, int $id)
    {
        $shift = TankShift::findOrFail($id);
        $tank = \App\Models\Tank::find($shift->tank_id);
        $shift->end_time = Carbon::now();

        // ATG setting
        $atgInstalled = (int) DB::table('SysConfig')->value('ATGEnable');

        $shiftCloseDurationInMinutes = (int) DB::table('settings')
            ->where('key', 'shift_min_close_duration')
            ->value('value');

        $startDate = new DateTime($shift->start_time);
        $shiftCloseDurationInHours = $shiftCloseDurationInMinutes / 60;
        $currentTime = Carbon::now();

        $interval = $currentTime->diff($startDate);
        $hoursDifference = $interval->h + ($interval->days * 24);

//        if ($hoursDifference < $shiftCloseDurationInHours) {
//            return response()->json([
//                'error' => 'Tank Shift cannot be closed as it has started less than the required duration.'
//            ], 400);
//        }

        $startTime = $shift->start_time;
        $endTime = Carbon::now();
//
//        $stockLedger = DB::table('tank_stock_ledger')
//            ->where('tank_id', $shift->tank_id)
//            ->whereBetween('created_at', [$startTime, $endTime])
//            ->get();
//
        // Sales
//        $tank = \App\Models\Tank::find($shift->tank_id);
        $nozzleIds = explode(',', $tank->nozzle_ids);
        $salesTotal = \App\Models\SaleData::whereIn('FC_NZNo', $nozzleIds)
            ->whereBetween('tdate', [$startTime, $endTime])
            ->sum('qty');

        $positiveStock = DB::table('tank_stock_ledger')
            ->where('tank_id', $tank->id)
            ->whereBetween('created_at', [$startTime, $endTime])
            ->where('stock_change', '>', 0)
            ->sum('stock_change');

        // Manual closing stock calculation
        $manualClosingDip = $shift->opening_mm - $salesTotal - ($positiveStock * 100);
//        $manualClosingTotalizer = $shift->opening_totalizer - $salesTotal - ($positiveStock*100);
//dd($manualClosingDip);

        $closingDip = 0;
        $newShiftOpeningDip = 0;
        $tankShiftId = null;

        if ($atgInstalled == 1) {
            $lastFuelStatus = $this->getLastFuelStatus($shift->tank_id);

            if (!$lastFuelStatus) {
                return Response::json([
                    'status' => 'error',
                    'message' => 'No fuel status found for this tank.'
                ], 400);
            }

            $closingTotalizer = $lastFuelStatus->Qty;
            $closingMm = $lastFuelStatus->Level;
            $newShiftOpeningTotalizer = $lastFuelStatus->Qty;
            $newShiftOpeningMm = $lastFuelStatus->Level;

        } else { // atgInstalled == 0
//            $closingTotalizer = $shift->manual_closing_totalizer; // Use manually entered closing totalizer
            $closingTotalizer = $shift->closing_mm; // Use manually entered closing totalizer
            $closingMm = $shift->closing_mm ?? 0; // Use manually entered closing mm, default to 0 if null
            $newShiftOpeningTotalizer = $shift->closing_mm; // New shift opens with manual closing totalizer
            $newShiftOpeningMm = $shift->opening_mm ?? 0; // New shift opens with manual closing mm, default to 0 if null
        }
//        dd($manualClosingTotalizer);
        $shift->update([
            'closing_mm' => $manualClosingDip,
//            'closing_mm' => $closingMm,
//            'manual_closing_totalizer' => $manualClosingTotalizer, // This is always calculated
//            'manual_closing_mm' => $manualClosingDip, // This is always calculated
        ]);

        $shiftData = $shift->toArray();
        unset($shiftData['id']);


        $stockLedger = DB::table('tank_stock_ledger')
            ->where('tank_id', $shift->tank_id)
            ->whereBetween('created_at', [$startTime, $endTime])
            ->get();
        $nozzleIds = explode(',', $tank->nozzle_ids);
        $salesData = \App\Models\saledata::whereIn('FC_NZNo', $nozzleIds)
            ->whereBetween('tdate', [$startTime, $endTime])
//            ->select('p_mode', DB::raw('SUM(qty) as total_quantity'), DB::raw('SUM(amt) as total_amount'))
//            ->groupBy('p_mode')
             ->select('p_mode', DB::raw('SUM(qty) as total_quantity'), DB::raw('SUM(amt) as total_amount'))
            ->groupBy('p_mode')
            ->get()
            ->toArray();

        $logData = array_merge($shiftData, [
            'data' => [
                'stock_ledger' => $stockLedger,
                'sales_data' => $salesData,
            ]
        ], ['end_time' => Carbon::now()]);

//        TankShiftLog::create($logData);

        $this->transferTankShiftLogData($logData);
        $this->transferTankShiftData($shift, 2,$shift->id);

        // Prepare payload for closing event
        $closingPayload = $shift->toArray();
        foreach (['created_at', 'updated_at', 'start_time', 'end_time'] as $dateField) {
            if (!empty($closingPayload[$dateField])) {
                try {
                    $parsedDate = Carbon::parse($closingPayload[$dateField]);
                    $closingPayload[$dateField] = $parsedDate->format('Y-m-d H:i:s');
                } catch (\Exception $e) {
                    Log::error("Failed to parse date field '{$dateField}' for event send in closeShift (closing): " . $e->getMessage(), ['value' => $closingPayload[$dateField]]);
                    $closingPayload[$dateField] = null;
                }
            }
        }

        $eventMapping = Config::get('event_config.events_mapping');
        $eventType = $eventMapping['tank_shift_closing'] ?? null;

        if ($eventType !== null) {
            $tankShiftId = $shift->tank_id;
            EventHelper::sendEventData($eventType, $closingPayload, $tankShiftId);
        }

        $shift->delete();

        // stock update
        $existingStock = \DB::table('tank_stock')
            ->where('tank_id', $shift->tank_id)
            ->first();

        if ($existingStock) {
            \DB::table('tank_stock')
                ->where('tank_id', $shift->tank_id)
                ->update([
//                    'stock_value' => $manualClosingTotalizer,
                    'stock_value' => $manualClosingDip,
                    'millimeter' => $closingDip,
                    'updated_at' => now(),
                ]);
        } else {
            \DB::table('tank_stock')->insert([
                'tank_id' => $shift->tank_id,
//                'stock_value' => $manualClosingTotalizer,
                'stock_value' => $manualClosingDip,
                'millimeter' => $closingDip,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Create new shift
    $newShift = TankShift::create([
    'tank_id' => $shift->tank_id,
    'product_id' => $shift->product_id,
//    'opening_totalizer' => $newShiftOpeningTotalizer,
//    'closing_totalizer' => 0,
    'opening_mm' => $newShiftOpeningMm,
//    'manual_opening_mm' => $newShiftOpeningMm, // Manual opening mm is the same as automatic opening mm for the new shift
//    'manual_closing_mm' => 0,
//    'manual_opening_totalizer' => $newShiftOpeningTotalizer, // Manual opening totalizer is the same as automatic opening totalizer for the new shift
    'closing_mm' => 0,
    'user_id' => $shift->user_id,
//    'is_modified' => false,
    'start_time' => Carbon::now(),
    ]);

        $this->transferTankShiftData($newShift, 1,$shift->id);

        // Payload for new opening event
        $newShiftPayload = $newShift->toArray();
        foreach (['created_at', 'updated_at', 'start_time', 'end_time'] as $dateField) {
            if (!empty($newShiftPayload[$dateField])) {
                try {
                    $parsedDate = Carbon::parse($newShiftPayload[$dateField]);
                    $newShiftPayload[$dateField] = $parsedDate->format('Y-m-d H:i:s');
                } catch (\Exception $e) {
                    Log::error("Failed to parse date field '{$dateField}' for event send in closeShift (new opening): " . $e->getMessage(), ['value' => $newShiftPayload[$dateField]]);
                    $newShiftPayload[$dateField] = null;
                }
            }
        }

        $eventType = $eventMapping['tank_shift_opening'] ?? null;
        if ($eventType !== null) {
            $tankShiftId = $shift->tank_id;
            EventHelper::sendEventData($eventType, $newShiftPayload, $tankShiftId);
        }

        return Response::json([
            'status' => 'success',
            'shift' => $newShift
        ]);
    }
    /**
     * This function closes ALL products in one go,
     * ends current shift calendar, and creates new 000 calendar
     * Returns the NEW calendar ID (for next shifts)
     */
    private function allProductCloseOneCLick($shifts): int
    {
        if ($shifts->isEmpty()) {
            throw new \Exception("No shifts found to close");
        }

        $firstShift = $shifts->first();

        // Use consistent date logic (prefer start_time if available)
        $workDate = $firstShift->start_time
            ? Carbon::parse($firstShift->start_time)->format('Y-m-d')
            : Carbon::parse($firstShift->start_date ?? now())->format('Y-m-d');

        $today = now()->format('Y-m-d');

        // Use same date everywhere
        $useDate = $workDate; // or $today if you want current day always

        // ── Find the latest relevant calendar for this date ─────────────────────
        $calendar = DB::table('shift_calendars')
            ->where('work_date', $useDate)
            ->latest('id')           // always take the most recent one
            ->first();

        // If no calendar exists at all → create initial one
        if (!$calendar) {
            $calendarId = DB::table('shift_calendars')->insertGetId([
                'work_date'        => $today,
                'petrol_closed'    => 0,
                'diesel_closed'    => 0,
                'hi_octane_closed' => 0,
                'shift_start_time' => now(),
                'created_at'       => now(),
                'updated_at'       => now(),
            ]);

            DB::table('shift_calendar_send')->insert([
                'calendar_id'      => $calendarId,
                'work_date'        =>  $today,
                'petrol_closed'    => 0,
                'diesel_closed'    => 0,
                'hi_octane_closed' => 0,
                'type'             => 1,
                'shift_start_time' => now(),
                'created_at'       => now(),
                'updated_at'       => now(),
            ]);

            $calendar = DB::table('shift_calendars')->find($calendarId);
        }

        // ── Step 1: Close all products (set 1 1 1) ───────────────────────────────
        DB::table('shift_calendars')->where('id', $calendar->id)->update([
            'petrol_closed'    => 1,
            'diesel_closed'    => 1,
            'hi_octane_closed' => 1,
            'updated_at'       => now(),
        ]);

        DB::table('shift_calendar_send')->where('calendar_id', $calendar->id)->update([
            'petrol_closed'    => 1,
            'diesel_closed'    => 1,
            'hi_octane_closed' => 1,
            'updated_at'       => now(),
        ]);

        // ── Step 2: End current calendar ────────────────────────────────────────
        $endTime = now();
        $duration = Carbon::parse($calendar->shift_start_time)->diffInMinutes($endTime);

        DB::table('shift_calendars')->where('id', $calendar->id)->update([
            'shift_end_time'   => $endTime,
            'total_duration'   => $duration,
            'updated_at'       => now(),
        ]);

        DB::table('shift_calendar_send')->where('calendar_id', $calendar->id)->update([
            'shift_end_time'   => $endTime,
            'total_duration'   => $duration,
            'updated_at'       => now(),
        ]);

        Log::info("All products closed & current shift ended", [
            'calendar_id' => $calendar->id,
            'work_date'   => $useDate
        ]);

        // ── Step 3: Create NEW calendar (000) for next shift ─────────────────────
        $newCalendarId = DB::table('shift_calendars')->insertGetId([
            'work_date'        => now()->format('Y-m-d'),
            'petrol_closed'    => 0,
            'diesel_closed'    => 0,
            'hi_octane_closed' => 0,
            'shift_start_time' => now(),           // new shift starts now
            'created_at'       => now(),
            'updated_at'       => now(),
        ]);

        DB::table('shift_calendar_send')->insert([
            'calendar_id'      => $newCalendarId,
            'work_date'        => now()->format('Y-m-d'),
            'petrol_closed'    => 0,
            'diesel_closed'    => 0,
            'hi_octane_closed' => 0,
            'type'             => 1,
            'shift_start_time' => now(),
            'created_at'       => now(),
            'updated_at'       => now(),
        ]);

        Log::info("New shift calendar created after all products closed", [
            'new_calendar_id' => $newCalendarId,
            'work_date'       => $useDate
        ]);

        // Return the NEW calendar ID (important!)
        return $newCalendarId;
    }

    /**
     * Close ALL tank shifts with one click
     */
    public function closeAllShifts(Request $request)
    {
        try {
            $shifts = TankShift::whereNull('end_time')->get(); // only active ones

            if ($shifts->isEmpty()) {
                return response()->json([
                    'status'  => 'warning',
                    'message' => 'No active shifts found to close'
                ]);
            }

            // Close all products + end old calendar + create new one
            $newCalendarId = $this->allProductCloseOneCLick($shifts);

            // Now close each individual tank shift
            foreach ($shifts as $shift) {
                $response = $this->closeShift($request, $shift->id, $newCalendarId);

//                if ($response->getStatusCode() !== 200) {
//                    throw new \Exception("Failed to close shift ID {$shift->id}");
//                }
            }

            return response()->json([
                'status'      => 'success',
                'message'     => 'All shifts closed successfully.',
                'calendar_id' => $newCalendarId
            ]);
        } catch (\Exception $e) {
            Log::error("closeAllShifts failed", [
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString()
            ]);

            return response()->json([
                'status'  => 'error',
                'message' => 'Error closing all shifts: ' . $e->getMessage()
            ], 500);
        }
    }


    /**
     * Method to get tank shift logs with filters.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTankShiftLogs(Request $request)
    {

        $validated = $request->validate([
            'tank_id' => 'nullable|integer|exists:tanks,id',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
        ]);
        DB::enableQueryLog();
//        $query = TankShiftLog::query()->select([
//            'id', 'tank_id', 'product_id', 'opening_totalizer', 'closing_totalizer',
//            'manual_opening_totalizer', 'manual_closing_totalizer', 'opening_mm',
//            'closing_mm', 'manual_opening_mm', 'manual_closing_mm', 'user_id',
//            'start_time', 'end_time', 'is_modified', 'created_at', 'updated_at'
//        ]);
//        dd($request->all());
        $query = TankShiftLog::query()->select([
            'id', 'tank_id', 'product_id', 'opening_dip',
            'closing_dip', 'manual_opening_dip', 'manual_closing_dip', 'user_id',
            'start_time', 'end_time', 'is_modified', 'created_at', 'updated_at'
        ]);

        if ($request->has('tank_id') && $request->filled('tank_id')) {
            $query->where('tank_id', $request->tank_id);
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereDate('start_time', '>=', $request->start_date)
                  ->whereDate('start_time', '<=', $request->end_date);
        } elseif ($request->filled('start_date')) {
            $query->whereDate('start_time', '=', $request->start_date);
        } else {
            $query->whereDate('start_time', '>=', Carbon::now()->subDays(30));
        }

        $query->orderBy('start_time', 'desc');
        $tankShiftLogs = $query->get();
//dd($tankShiftLogs);
        return Response::json([
            'status' => 'success',
            'tank_shift_logs' => $tankShiftLogs,
        ]);
    }

    // Method to get the last fuel status for a given tank
    private function getLastFuelStatus($tankId)
    {
        $lastFuelStatus = DB::table('LastFuelStatus')
            ->where('TID', $tankId)
            ->orderBy('tdate', 'desc')
            ->first();

        return $lastFuelStatus;
    }
    private function getFuelStock($tankId)
    {
        $fuelStock = DB::table('tank_stock')
            ->where('tank_id', $tankId)
            ->first();

        return $fuelStock;
    }

    public function getTankStatusHistory(Request $request)
    {
        $tankId = $request->query('tank_id');

        if (!$tankId) {
            return Response::json([
                'status' => 'error',
                'message' => 'Tank ID is required.'
            ], 400);
        }
        $fuelStatusHistory = DB::select("SELECT * FROM FuelStatus WHERE TID = ? and tdate > ?", [$tankId,  Carbon::now()->subMonth()]);
        return Response::json($fuelStatusHistory);
    }

    /**
     * Method to display the tank shift print view.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function shiftPrint(Request $request)
    {
        $ids = explode(',', $request->ids);
        $tankShiftLogs = TankShiftLog::whereIn('id', $ids)->get();
//        dd($tankShiftLogs);
        $settings = \App\Models\Settings::getSettingsArray();
        $settings = $settings ?? [];
        $reportType = 'tank_summary';
        return view('tank.shift_print', compact('tankShiftLogs', 'settings', 'reportType'));
    }

    /**
     * Method to display the tank shift print view filtered by date.
     *
     * @param  string  $date The date string (YYYY-MM-DD)
     * @return \Illuminate\View\View
     */
    public function shiftPrintByDateold(Request $request)
    {
        $date = $request->input('date');
        try {
            $carbonDate = Carbon::parse($date)->startOfDay();
        } catch (\Exception $e) {
            // Handle invalid date format, perhaps redirect back with an error
            return redirect()->back()->with('error', 'Invalid date format provided.');
        }

        // Find shift logs for the given date
        $shiftLogs = \App\Models\ShiftLog::whereDate('start_date', $carbonDate)->get();


        // Fetch TankShiftLogs based on the collected IDs
        $tankShiftLogs = TankShiftLog::whereDate('start_time', $carbonDate)->get();

        $shifts = $shiftLogs;

        foreach ($shifts as $shift) {
            $shift->data = json_decode($shift->data);
        }
        $shifts = collect($shifts);
        $shifts = $shifts->sortBy(function($shift) {
            return $shift->data->shift->pump_id;
        });
        // Fetch all records from the PRODUCT table
        $products = DB::select('select PUMPS.id, PRODUCT.ICODE, PRODUCT.ITMNAME from PUMPS left join PRODUCT on PRODUCT.ICODE=PUMPS.ICODE;');

        // Create a dictionary where ICODE is the key and ITMNAME is the value
        $productDict = [];

        foreach ($products as $product) {
            $productDict[$product->id] = $product->ITMNAME;
        }

        $paymentmethods = DB::select('SELECT * FROM `paymentmethod`');

        // Create a dictionary where ICODE is the key and ITMNAME is the value
        $paymentmethodsDict = ["" => "None"];

        foreach ($paymentmethods as $paymentmethod) {
            $paymentmethodsDict[$paymentmethod->id] = $paymentmethod->Des;
        }

        $settings = \App\Models\Settings::getSettingsArray();
        $settings = $settings ?? [];
        $excludeHeader = true;
         $reportType = 'combined_summary';
        return view('reports.combined_print_shift', compact('shifts', 'productDict', 'paymentmethodsDict', 'tankShiftLogs', 'settings', 'excludeHeader', 'reportType'));
    }
    public function shiftPrintByDate(Request $request)
    {
        $date = $request->input('date');
        $shift = $request->input('shift');

        try {
            $carbonDate = Carbon::parse($date)->startOfDay();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Invalid date format provided.');
        }

        // SysConfig get shift type
        $config = DB::table('SysConfig')->first();
        $noOfShifts = $config->NoofShifts ?? 1;

        // Shift filtering
        $shiftLogs = \App\Models\ShiftLog::whereDate('start_date', $carbonDate)
            ->when($shift, function ($q) use ($shift) {
                $q->whereJsonContains('data->shift->shift_type', (int)$shift);
            })
            ->get();
        // Tank shift logs also filter
        $tankShiftLogs = TankShiftLog::whereDate('start_time', $carbonDate)

            ->orderBy('id','desc')->get()->take(3);

        foreach ($shiftLogs as $s) {
            $s->data = json_decode($s->data);

            // ✅ FIX: hardware-reported total_qty can be wrong; use meter difference (closing_fuel - opening_fuel) per nozzle
            if (isset($s->data->shift->opening_fuel) && isset($s->data->shift->closing_fuel)) {
                $s->data->shift->total_qty = $s->data->shift->closing_fuel - $s->data->shift->opening_fuel;
            }
        }

        $shifts = collect($shiftLogs)->sortBy(function($shift) {
            return $shift->data->shift->pump_id;
        });

        // PRODUCTS
        $products = DB::select('select PUMPS.id, PRODUCT.ICODE, PRODUCT.ITMNAME
                            from PUMPS
                            left join PRODUCT on PRODUCT.ICODE=PUMPS.ICODE;');

        $productDict = [];
        foreach ($products as $product) {
            $productDict[$product->id] = $product->ITMNAME;
        }

        // Payment Methods
        $paymentmethods = DB::select('SELECT * FROM `paymentmethod`');
        $paymentmethodsDict = ["" => "None"];
        foreach ($paymentmethods as $pm) {
            $paymentmethodsDict[$pm->id] = $pm->Des;
        }

        $settings = \App\Models\Settings::getSettingsArray() ?? [];

        return view('reports.combined_print_shift', [
            'shifts' => $shifts,
            'productDict' => $productDict,
            'paymentmethodsDict' => $paymentmethodsDict,
            'tankShiftLogs' => $tankShiftLogs,
            'settings' => $settings,
            'excludeHeader' => true,
            'reportType' => 'combined_summary'
        ]);
    }


    public function insertShiftDataFromShifts()
    {
        echo "Starting shift data insertion...\n";
        // Define the dates to process (6th and 7th March 2025)
        $dates = [];
        $startDate = Carbon::create(2025, 3, 1);
        $endDate = Carbon::create(2025, 5, 25);

        while ($startDate->lte($endDate)) {
            $dates[] = $startDate->toDateString();
            $startDate->addDay();
        }
        $tanks = \App\Models\Tank::all();

        foreach ($dates as $date)
        {
            echo "Processing date: $date\n";

            foreach ($tanks as $tank) {
                DB::enableQueryLog();
                $tankId = $tank->id;
                echo "Processing tank ID: $tankId\n";
                // Retrieve one shift log record for the given day.
                $shiftLog = \App\Models\ShiftLog::whereDate('start_date', $date)
                    ->orderBy('start_date', 'asc')
                    ->first();
                $shiftCloseLog = \App\Models\ShiftLog::whereDate('start_date', $date)
                ->orderBy('end_date', 'desc')
                ->first();
                if (!$shiftLog) {
                    echo "No shift log found for date: $date\n";
                    echo "Query log: " . json_encode(DB::getQueryLog()) . "\n";
                    echo "--------------------------------------\n";
                    continue;
                }
                echo "Shift log found with ID: " . $shiftLog->id . " for date: $date\n";

                // Retrieve the FuelStatus record nearest to the shift log's start_date.
                $fuelStatus = \App\Models\FuelStatus::where('ICode', $tank->product_id)
                    ->where('TID', $tankId)
                    ->orderByRaw('ABS(TIMESTAMPDIFF(SECOND, tdate, ?))', [$shiftLog->start_date])
                    ->first();
                if (!$fuelStatus) {
                    echo "No FuelStatus record found for tank ID: $tankId and product ID: {$shiftLog->product_id} at start_date: {$shiftLog->start_date}\n";
                    echo "Query log: " . json_encode(DB::getQueryLog()) . "\n";
                    echo "--------------------------------------\n";
                    continue;
                }
                echo "FuelStatus record found with Qty: " . $fuelStatus->Qty . " and Level: " . $fuelStatus->Level . "\n";

                // Retrieve the FuelStatus record nearest to the shift log's end_date.
                $fuelEndStatus = \App\Models\FuelStatus::where('ICode', $tank->product_id)
                    ->where('TID', $tankId)
                    ->orderByRaw('ABS(TIMESTAMPDIFF(SECOND, tdate, ?))', [$shiftCloseLog->end_date])
                    ->first();

                if ($fuelEndStatus) {
                    echo "FuelEndStatus record found with Level: " . $fuelEndStatus->Level . "\n";
                    // dd(json_decode(json_encode([$fuelEndStatus, $fuelStatus, $shiftLog, DB::getQueryLog()])));
                } else {
                    echo "No FuelEndStatus record found for tank ID: $tankId and product ID: {$shiftLog->product_id} at end_date: {$shiftCloseLog->end_date}\n";
                }

                // Use FuelStatus Qty as both automatic and manual totalizers.
                $automaticTotalizer = $fuelStatus->Qty;
                $manualTotalizer = $fuelStatus->Qty;

                // Assume the shift log stores the starting totalizer.
                $startingTotalizer = $fuelStatus->Qty ?? 0;
                $closingTotalizer = $fuelEndStatus->Qty ?? 0;
                echo "Starting totalizer: $startingTotalizer, Closing totalizer: $closingTotalizer\n";

                // Retrieve tank details to get nozzle IDs.
                $tank = \App\Models\Tank::find($tankId);
                if (!$tank) {
                    echo "Tank not found for shift log tank_id: " . $tankId . "\n";
                    continue;
                }
                $nozzleIds = explode(',', $tank->nozzle_ids);
                echo "Nozzle IDs for tank: " . implode(', ', $nozzleIds) . "\n";

                // Calculate total sales for the shift period.
                $salesTotal = \App\Models\SaleData::whereIn('pos_id', $nozzleIds)
                    ->whereBetween('tdate', [$shiftLog->start_date, $shiftCloseLog->end_date])
                    ->sum('qty');
                echo "Total sales for period: $salesTotal\n";
                $salesData =\App\Models\saledata::whereIn('pos_id', $nozzleIds)
                ->whereBetween('tdate', [$shiftLog->start_date, $shiftCloseLog->end_date])
                ->select('p_mode', DB::raw('SUM(qty) as total_quantity'), DB::raw('SUM(amt) as total_amount'))
                ->groupBy('p_mode')
                ->get()
                ->toArray();
                // Sum positive entries in the stock ledger for the shift period.
                $positiveStock = DB::table('tank_stock_ledger')
                    ->where('tank_id', $tankId)
                    ->whereBetween('created_at', [$shiftLog->start_date, $shiftCloseLog->end_date])
                    ->where('stock_change', '>', 0)
                    ->sum('stock_change');
                echo "Positive stock entries sum: $positiveStock\n";

                // Calculate the manual closing totalizer.
                $manualClosingTotalizer = $startingTotalizer - $salesTotal + ($positiveStock*100);
                echo "Calculated manual closing totalizer: $manualClosingTotalizer\n";
                $salesData = \App\Models\saledata::whereIn('pos_id', $nozzleIds)
                ->whereBetween('tdate', [$shiftLog->start_date, $shiftCloseLog->end_date])
                ->select('p_mode', DB::raw('SUM(qty) as total_quantity'), DB::raw('SUM(amt) as total_amount'))
                ->groupBy('p_mode')
                ->get()
                ->toArray();

        $stockLedger = DB::table('tank_stock_ledger')
            ->where('tank_id', $tankId)
            ->whereBetween('created_at', [$shiftLog->start_date, $shiftCloseLog->end_date])
            ->get();

                // Prepare the data to insert into the tank_shift_logs table.
                $data = [
                    'tank_id' => $tankId,
                    'product_id' => $tank->product_id,
                    'start_time' => $shiftLog->start_date,
                    'end_time' => $shiftCloseLog->end_date,
                    'opening_totalizer' => $startingTotalizer,
                    'manual_opening_totalizer' => $automaticTotalizer,
                    'manual_closing_totalizer' => $manualClosingTotalizer,
                    'closing_totalizer' => $closingTotalizer,
                    'closing_mm' => $fuelEndStatus->Level,
                    'opening_mm' => $fuelStatus->Level,
                    'created_at' => now(),
                    'updated_at' => now(),
                    'user_id' => 8,

                    'data' => json_encode([
                        'stock_ledger' => $stockLedger,
                        'sales_data' => $salesData,
                    ]),
                    // 'product_id' => $tanks->product_id,
                    // 'shift_id' => $shiftLog->id,
                ];
                echo "Data to be inserted: " . json_encode($data) . "\n";

                // Insert the computed data into the tank_shift_logs table.
                DB::table('tank_shift_logs')->insert($data);
                echo "Data inserted successfully for tank ID: " . $tankId . " on date: $date\n";
                echo "--------------------------------------\n";
            }
        }

        echo "Shift data insertion completed.\n";
        return Response::json([
            'status' => 'success',
            'message' => 'Shift data inserted for the specified dates.'
        ]);
    }

//    private function transferTankShiftData(TankShift $tankShift, string $type): void
//    {
//        $data = $tankShift->toArray();
//        $exclude_columns = ['opening_totalizer', 'closing_totalizer','manual_opening_totalizer','manual_closing_totalizer'];
//        $get_columns = array_diff($data, $exclude_columns);
//        dd($get_columns);
//        // Ensure standard MySQL datetime format for relevant fields before inserting via Query Builder
//        foreach (['created_at', 'updated_at', 'start_time', 'end_time'] as $dateField) {
//            if (isset($data[$dateField]) && $data[$dateField]) { // Check if field exists and is not null/empty
//                try {
//                    // Parse the date (handles Carbon instances or ISO strings from toArray)
//                    $parsedDate = Carbon::parse($data[$dateField]);
//                    // Format it
//                    $data[$dateField] = $parsedDate->format('Y-m-d H:i:s');
//                } catch (\Exception $e) {
//                    // Log error if parsing fails, maybe the format is unexpected
//                    Log::error("Failed to parse date field '{$dateField}' in transferTankShiftData: " . $e->getMessage(), ['value' => $data[$dateField]]);
//                    // Decide how to handle: nullify, keep original, or throw? Let's nullify for now to prevent DB error.
//                    $data[$dateField] = null;
//                }
//            }
//        }
//
//        DB::table('tank_shifts_send')->insert(array_merge($data, ['type' => $type]));
//    }
    private function transferTankShiftData(TankShift $tankShift, string $type,int $pid): void
    {
        $data = $tankShift->toArray();

        // Ensure standard MySQL datetime format
        foreach (['created_at', 'updated_at', 'start_time', 'end_time'] as $dateField) {
            if (isset($data[$dateField]) && $data[$dateField]) {
                try {
                    $parsedDate = Carbon::parse($data[$dateField]);
                    $data[$dateField] = $parsedDate->format('Y-m-d H:i:s');
                } catch (\Exception $e) {
                    Log::error("Failed to parse date field '{$dateField}' in transferTankShiftData: " . $e->getMessage(), ['value' => $data[$dateField]]);
                    $data[$dateField] = null;
                }
            }
        }

        // Totalizer fields already removed from DB → unko unset karo
//        unset(
//            $data['user_id'],
//            $data['closing_totalizer'],
//            $data['manual_opening_totalizer'],
//            $data['manual_closing_totalizer']
//        );
        unset($data['id']);
        // Rename mm → dip
        if (isset($data['opening_mm'])) {
            $data['opening_dip'] = $data['opening_mm'];
            unset($data['opening_mm']);
        }
        if (isset($data['closing_mm'])) {
            $data['closing_dip'] = $data['closing_mm'];
            unset($data['closing_mm']);
        }

        unset(
            $data['opening_mm'],
            $data['closing_mm'],
            $data['manual_opening_mm'],
            $data['manual_closing_mm'],
//            $data['user_id'],
            $data['is_atg'],
            $data['vendor_id'],
        );
        if ($type == 1) {
            $data['closing_dip'] = 0;
        }
        // ✔ Always save original shift id
        $data['pid'] = $pid;
//        Log::error('check calendar_id'.$data);
        // Insert into send table
        DB::table('tank_shifts_send')->insert(array_merge($data, ['type' => $type]));
    }
    private function transferTankShiftLogDataold($logData): void
    {
        $data = $logData;

        // Ensure standard MySQL datetime format
        foreach (['created_at', 'updated_at', 'start_time', 'end_time'] as $dateField) {
            if (isset($data[$dateField]) && $data[$dateField]) {
                try {
                    $parsedDate = Carbon::parse($data[$dateField]);
                    $data[$dateField] = $parsedDate->format('Y-m-d H:i:s');
                } catch (\Exception $e) {
                    Log::error("Failed to parse date field '{$dateField}' in transferTankShiftData: " . $e->getMessage(), [
                        'value' => $data[$dateField]
                    ]);
                    $data[$dateField] = null;
                }
            }
        }

        // Remove totalizer fields (not required in logs)
        unset(
            $data['opening_totalizer'],
            $data['closing_totalizer']
        );

        // ✅ Rename mm → dip (0 bhi allow karein)
        if (array_key_exists('opening_mm', $data)) {
            $data['opening_dip'] = $data['opening_mm'];
            unset($data['opening_mm']);
        }

        if (array_key_exists('closing_mm', $data)) {
            $data['closing_dip'] = $data['closing_mm'];
            unset($data['closing_mm']);
        }

        if (array_key_exists('manual_opening_mm', $data)) {
            $data['manual_opening_dip'] = $data['manual_opening_mm'];
            unset($data['manual_opening_mm']);
        }

        if (array_key_exists('manual_closing_mm', $data)) {
            $data['manual_closing_dip'] = $data['manual_closing_mm'];
            unset($data['manual_closing_mm']);
        }

        // ✅ Ensure 'data' is JSON
        if (isset($data['data']) && is_array($data['data'])) {
            $data['data'] = json_encode($data['data']);
        }

        // Debugging (optional)
        Log::info("Final Insert Data for Tank Shift Logs", $data);

        // Insert into logs table
        DB::table('tank_shift_logs')->insert($data);
    }


    private function transferTankShiftLogData($logData): void
    {
        $data = $logData;

        // Ensure standard MySQL datetime format
        foreach (['created_at', 'updated_at', 'start_time', 'end_time'] as $dateField) {
            if (isset($data[$dateField]) && $data[$dateField]) {
                try {
                    $parsedDate = Carbon::parse($data[$dateField]);
                    $data[$dateField] = $parsedDate->format('Y-m-d H:i:s');
                } catch (\Exception $e) {
                    Log::error("Failed to parse date field '{$dateField}' in transferTankShiftData: " . $e->getMessage(), ['value' => $data[$dateField]]);
                    $data[$dateField] = null;
                }
            }
        }

        // Remove totalizer fields
        unset(
            $data['opening_totalizer'],
            $data['closing_totalizer'],
            $data['is_atg'],
            $data['id'],
            $data['vendor_id'],

//            $data['manual_opening_totalizer'],
//            $data['manual_closing_totalizer']
        );
//dd($data);
        // Rename mm → dip
        if (isset($data['opening_mm'])) {
            $data['opening_dip'] = $data['opening_mm'];
            unset($data['opening_mm']);
        }
        if (isset($data['closing_mm'])) {
            $data['closing_dip'] = $data['closing_mm'];
//            dd($data['closing_dip']);
            unset($data['closing_mm']);
        }
        if (array_key_exists('manual_opening_mm', $data)) {
            $data['manual_opening_dip'] = $data['manual_opening_mm'];
            unset($data['manual_opening_mm']);
        }

        if (array_key_exists('manual_closing_mm', $data)) {
            $data['manual_closing_dip'] = $data['manual_closing_mm'];
            unset($data['manual_closing_mm']);
        }

        unset(
            $data['opening_mm'],
            $data['closing_mm'],
            $data['manual_opening_mm'],
            $data['manual_closing_mm']
        );
        // ✅ Ensure 'data' is JSON
        if (isset($data['data']) && is_array($data['data'])) {
            $data['data'] = json_encode($data['data']);
        }
//        Log::info("Final Insert Data for Tank Shift Logs", $data);

        // Insert into logs table
        DB::table('tank_shift_logs')->insert($data);
    }
    public function runSeeder()
    {
        $user = Auth::user();

        if (!$user || $user->email !== 'superadmin@ez-pump.com') {
            abort(403, 'Unauthorized action.');
        }

        try {
            // Run the seeder
            Artisan::call('db:seed', [
                '--class' => 'TankShiftSeeder',
                '--force' => true,
            ]);

            return redirect()->back()->with('success', '✅ Tank Shift Seeder executed successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', '❌ Seeder failed: ' . $e->getMessage());
        }
    }
    public function closeAtgTankShiftsByProduct(Request $request, $productKey)
    {
        try {
            DB::beginTransaction();

            // 🔹 Product map
            $productMap = [
                'Petrol'    => 1,
                'hi_octane' => 2,
                'diesel'    => 3,
            ];
//dd($productKey);
            if (!isset($productMap[$productKey])) {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid product key'
                ], 400);
            }

            $productId = $productMap[$productKey];
//dd($productId);
            // 🔹 ATG enabled tanks of this product
            $atgTanks = DB::table('tanks')
                ->join('ATGs', 'tanks.id', '=', 'ATGs.TankID')
                ->where('tanks.product_id', $productId)
                ->where('ATGs.is_atg', 1)
                ->select('tanks.id')
                ->pluck('id')
                ->toArray();
            if (empty($atgTanks)) {
                return response()->json([
                    'status' => false,
                    'message' => 'No ATG tanks found for this product'
                ], 404);
            }

            // 🔹 Active ATG tank shifts
            $activeShifts = TankShift::whereIn('tank_id', $atgTanks)
                ->whereNull('end_time')
                ->get();
            $processedProducts[]='';
            $calendarId='';
            foreach ($activeShifts as $tankId) {

                $tank = DB::table('tanks')->where('id', $tankId->tank_id)->first();
                if (!$tank) continue;

                $productId = (int) $tank->product_id;

                if (in_array($productId, [1, 2, 3]) && !in_array($productId, $processedProducts)) {
                    $currentCalendarId = $this->closeByTank($tankId->tank_id);
                    $calendarId = $currentCalendarId; // last one should be same for all
                    $processedProducts[] = $productId;
                }
            }
//            dd($tankIds);
            if (!$calendarId) {
                // Fallback: use first tank if no product was closed
                $calendarId = $this->closeByTank($atgTanks);
            }
//            dd(  $calendarId);
            foreach ($activeShifts as $shift) {

                $tankId = $shift->tank_id;
                $lastFuelStatus = $this->getLastFuelStatus($tankId);

                if (!$lastFuelStatus) {
                    Log::warning("No ATG data for tank {$tankId}");
                    continue;
                }
                $startTime = $shift->start_time;
                $endTime = Carbon::now();
                $closingMm = $lastFuelStatus->Level > 10000
                    ? $lastFuelStatus->Level
                    : $lastFuelStatus->Level;

                // 🔹 Close old shift
                $shift->update([
                    'closing_mm' => $closingMm,
                    'end_time'   => now(),
                ]);
                $shiftData = $shift->toArray();


                $stockLedger = DB::table('tank_stock_ledger')
                    ->where('tank_id', $shift->tank_id)
                    ->whereBetween('created_at', [$startTime, $endTime])
                    ->get();
                $tankNozzle=Tank::where('id',$shift->tank_id)->first();
                $nozzleIds = explode(',', $tankNozzle->nozzle_ids);
//                $salesData = \App\Models\saledata::whereIn('FC_NZNo', $nozzleIds)
//                    ->whereBetween('tdate', [$startTime, $endTime])
////            ->select('p_mode', DB::raw('SUM(qty) as total_quantity'), DB::raw('SUM(amt) as total_amount'))
////            ->groupBy('p_mode')
//                    ->select('p_mode', DB::raw('SUM(qty) as total_quantity'), DB::raw('SUM(amt) as total_amount'))
//                    ->groupBy('p_mode')
//                    ->get()
//                    ->toArray();
                $shiftIds = DB::table('shift')
                    ->whereIn('pump_id', $nozzleIds)
//                    ->whereDate('start_date', date('Y-m-d'))  // ya shift ke start_date ke hisaab se
                    ->pluck('id')  // array of shift IDs
                    ->toArray();
// 3️⃣ Shift Payment Wise Sale for all these shifts
                $salesData = DB::table('shift_payment_wise_sale')
                    ->whereIn('shift_id', $shiftIds)
                    ->select(
                        'paymentmethod_id as p_mode',
                        DB::raw('SUM(total_qty) as total_quantity'),
                        DB::raw('SUM(total_sale) as total_amount')
                    )
                    ->groupBy('paymentmethod_id')
                    ->get()
                    ->toArray();
                unset($shiftData['id']);

                $logData = array_merge($shiftData, [
                    'data' => [
                        'stock_ledger' => $stockLedger,
                        'sales_data' => $salesData,
                    ]
                ], ['end_time' => Carbon::now()]);



                $this->transferTankShiftLogData($logData);
                // 🔹 Log + archive
                $this->transferTankShiftData($shift, 3, $shift->id);
                $shift->delete();

                // 🔹 Start new shift (+1 second safe gap)
                $newShift = TankShift::create([
                    'tank_id'     => $tankId,
                    'product_id'  => $shift->product_id,
                    'opening_mm'  => $closingMm,
                    'closing_mm'  => 0,
                    'user_id'     => $shift->user_id,
                    'start_time'  => now()->addSecond(),
                    'calendar_id'=>$calendarId,
//                    'calendar_id'=>62,
                ]);
                DB::table('shift_calendars')->where('id',$calendarId)
                    ->update([
                        'work_date'=> now()->format('Y-m-d'),
                    ]);
                $this->transferTankShiftData($newShift, 1, $shift->id);

            }

            DB::commit();

            return response()->json([
                'status'  => true,
                'message' => 'ATG tank shifts closed product wise successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("ATG Product Close Error: ".$e->getMessage());

            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function checkStock(Request $request)
    {
        $request->validate([
            'tank_id' => 'required|integer|exists:tanks,id',
            'dip'     => 'required|numeric|min:0',
            'dip_mm'  => 'nullable|numeric|min:0'
        ]);

        $tankId     = $request->tank_id;
        $closingDip = (float)$request->dip;
        $closingMM  = $request->dip_mm ? $this->normalizeMM((float)$request->dip_mm) : null;

        try {

            // ✅ 1️⃣ Get tank FIRST
            $tank = DB::table('tanks')->where('id', $tankId)->first();

            if (!$tank) {
                return response()->json([
                    'status'=>'error',
                    'message'=>'Tank not found'
                ],404);
            }

            // ✅ 2️⃣ Skip if inactive (IMPORTANT FIX)
            if ($tank->is_active == 0) {
                return response()->json([
                    'status'  => 'skipped',
                    'message' => 'Stock check skipped. Tank is inactive.'
                ]);
            }

            // 3️⃣ Latest tank shift
            $tankShift = DB::table('tank_shifts')
                ->where('tank_id', $tankId)
                ->orderByDesc('id')
                ->first();

            if (!$tankShift) {
                return response()->json([
                    'status'=>'error',
                    'message'=>'Tank shift not found'
                ],404);
            }

            $shiftStart = $tankShift->start_time;

            // 4️⃣ Opening dip
            $openingMM = $this->normalizeMM((float)$tankShift->opening_mm);
            $openingDipLiters = $this->convertMmToLitersCheck($tankId, $openingMM);

            // 🚨 Capacity check
            if ($closingDip > $tank->capacity_liters) {
                return response()->json([
                    'status'=>'error',
                    'message'=>"Tank overflow! Capacity = {$tank->capacity_liters} L"
                ],400);
            }

            // ✅ 5️⃣ Handle nozzle safely
            $nozzleIds = [];
            if (!empty($tank->nozzle_ids)) {
                $nozzleIds = array_map('trim', explode(',', $tank->nozzle_ids));
            }

            // 6️⃣ Get shifts (only if nozzle exists)
            $shiftIds = collect();
            if (!empty($nozzleIds)) {
                $shiftIds = DB::table('shift')
                    ->whereIn('pump_id', $nozzleIds)
                    ->whereDate('start_date','>=', date('Y-m-d', strtotime($shiftStart)))
                    ->pluck('id');
            }

            // 7️⃣ SALES
            $totalSale = 0;
            if ($shiftIds->isNotEmpty()) {
                $totalSale = DB::table('shift_payment_wise_sale')
                        ->whereIn('shift_id', $shiftIds)
                        ->sum('total_qty') / 100;
            }

            // 8️⃣ PURCHASE
            $totalPurchase = DB::table('tank_stock_ledger')
                ->where('tank_id',$tankId)
                ->where('created_at','>=',$shiftStart)
                ->sum('stock_change');

            // 9️⃣ Expected dip
            $expectedClosingDip = round(
                $openingDipLiters + $totalPurchase,
                2
            );

            // 🚨 MAIN RULE
            if ($closingDip > $expectedClosingDip) {
                return response()->json([
                    'status'=>'error',
                    'message'=>"INVALID DIP! Please Add Purchase Stock"
                ],400);
            }

            // 🟢 MM CHECK
            if ($closingMM !== null) {
                $closingMMinLiters = round(
                    $this->convertMmToLitersCheck($tankId, $closingMM),
                    2
                );

                if ($closingMMinLiters > $expectedClosingDip) {
                    return response()->json([
                        'status'=>'error',
                        'message'=>"INVALID MM DIP!
Maximum Possible : {$expectedClosingDip} L
MM Dip           : {$closingMMinLiters} L"
                    ],400);
                }
            }

            return response()->json([
                'status'=>'ok',
                'opening_dip'=>$openingDipLiters,
                'sale'=>$totalSale,
                'purchase'=>$totalPurchase,
                'expected_closing_dip'=>$expectedClosingDip,
                'message'=>"Stock check passed."
            ]);

        } catch (\Exception $e) {
            Log::error('CHECK STOCK ERROR',[
                'msg'=>$e->getMessage(),
                'line'=>$e->getLine()
            ]);

            return response()->json([
                'status'=>'error',
                'message'=>$e->getMessage()
            ]);
        }
    }


    private function normalizeMM(float $mm): float
    {
        // ATG sends mm ×100 sometimes
        if ($mm > 1000) {
            return $mm / 100;
        }
        return $mm;
    }

    /**
     * Convert millimeter dip value to liters using dip chart
     */
    private function convertMmToLitersCheck(int $tankId, float $mmValue): float
    {
        $dipChartData = DipChartValue::where('tank_id', $tankId)
            ->orderBy('millimeter')
            ->get();

        if ($dipChartData->isEmpty()) {
            throw new \Exception("Dip chart not found for tank {$tankId}");
        }

        // find nearest lower and upper points for linear interpolation
        $lower = null;
        $upper = null;

        foreach ($dipChartData as $row) {
            if ($row->millimeter <= $mmValue) {
                $lower = $row;
            }
            if ($row->millimeter >= $mmValue) {
                $upper = $row;
                break;
            }
        }

        if (!$lower) $lower = $dipChartData->first();
        if (!$upper) $upper = $dipChartData->last();

        if ($lower->millimeter == $upper->millimeter) {
            return (float)$lower->liter_value;
        }

        // linear interpolation
        $liters = $lower->liter_value + (($mmValue - $lower->millimeter) / ($upper->millimeter - $lower->millimeter)) * ($upper->liter_value - $lower->liter_value);
        return round($liters, 2);
    }




}
