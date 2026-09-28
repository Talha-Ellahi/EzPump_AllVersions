<?php

namespace App\Http\Controllers;

use App\Models\Shift;
use App\Models\TankShift;
use Illuminate\Http\Request;
use App\Models\Tank;
use App\Models\TankStock;
use App\Models\TankStockLedger;
use App\Models\DipChartValue;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\DipChartImport;
use Illuminate\Support\Facades\DB; // Correct Facade usage
use Carbon\Carbon; // Added for timestamping
use App\Helpers\EventHelper; // Added EventHelper
use Illuminate\Support\Facades\Config;

class TankController extends Controller
{
    // Get all tanks
    public function index()
    {
        $tanks = Tank::with('stock')->get();

        $vendors = DB::table('VENDOR')
            ->select('VENDOR_ID', 'VNAME','id')
            ->orderBy('VNAME')
            ->get();
//dd($vendors);
        return response()->json([
            'tanks' => $tanks,
            'vendors' => $vendors
        ]);
//        return response()->json($tanks);
    }

    // Create new tank
    public function storeold(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tank_name' => 'required|string|max:255',
            'fuel_type' => 'required|string|max:255',
            'capacity_liters' => 'required|numeric',
            'temperature' => 'nullable|numeric',
            'water_height_mm' => 'nullable|numeric',
            'product_id' => 'required',
            'is_active'=>'nullable'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
$system = DB::table('SysConfig')->first();
        if (!$system){
            if ($system->Sys_Mode==3){
                $request['nozzle_ids']=0;
            }
        }
        $tank = Tank::create($request->all());

        // Initialize tank stock
        TankStock::create([
            'tank_id' => $tank->id,
            'stock_value' => 0,
            'millimeter' => 0
        ]);

        return response()->json($tank, 201);
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tank_name' => 'required|string|max:255',
            'fuel_type' => 'required|string|max:255',
            'capacity_liters' => 'required|numeric',
            'temperature' => 'nullable|numeric',
            'water_height_mm' => 'nullable|numeric',
            'product_id' => 'required',
            'is_active' => 'nullable',
            'low_level_alarm_mm'=>'nullable',
            'low_low_level_alarm_mm'=>'nullable',
            'high_level_alarm_mm'=>'nullable',
            'high_high_level_alarm_mm'=>'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        /* ================= SYSTEM MODE CHECK ================= */

        $system = DB::table('SysConfig')->first();

        $sysMode = $system ? $system->Sys_Mode : null;

        // ✅ IF SYSTEM MODE == 3 THEN REMOVE NOZZLE IDS
        if ($sysMode == 3) {
            $requestData = $request->all();
            $requestData['nozzle_ids'] = null; // or 0 or empty string
        } else {
            $requestData = $request->all();
        }

        /* ================= CREATE TANK ================= */

        $tank = Tank::create($requestData);

        /* ================= INIT STOCK ================= */

        TankStock::create([
            'tank_id' => $tank->id,
            'stock_value' => 0,
            'millimeter' => 0
        ]);

        return response()->json($tank, 201);
    }

    // In TankController.php

    public function show($id)
    {
        $tank = Tank::find($id);
        if (!$tank) {
            return response()->json(['message' => 'Tank not found'], 404);
        }
        return response()->json($tank);
    }
    // Update tank
    public function update(Request $request, $id)
    {
//        dd($request->all());
        $validator = Validator::make($request->all(), [
            'tank_name' => 'sometimes|string|max:255',
            'fuel_type' => 'sometimes|string|max:255',
            'capacity_liters' => 'sometimes|numeric',
            'temperature' => 'nullable|numeric',
            'water_height_mm' => 'nullable|numeric',
            'product_id' => 'required',
            'is_active'=>'nullable',
            'low_level_alarm_mm'=>'nullable',
            'low_low_level_alarm_mm'=>'nullable',
            'high_level_alarm_mm'=>'nullable',
            'high_high_level_alarm_mm'=>'nullable',

        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $system = DB::table('SysConfig')->first();

        $sysMode = $system ? $system->Sys_Mode : null;

        // ✅ IF SYSTEM MODE == 3 THEN REMOVE NOZZLE IDS
        if ($sysMode == 3) {
            $requestData = $request->all();
            $requestData['nozzle_ids'] = null; // or 0 or empty string
        } else {
            $requestData= $request->all();
        }
        $tank = Tank::findOrFail($id);
        $tank->update($requestData);

        return response()->json($tank);
    }

    // Delete tank
    public function destroy($id)
    {
        $tank = Tank::findOrFail($id);
        $tank->delete();

        return response()->json(null, 204);
    }

    // Add stock to tank
    public function addStock(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'stock_change' => 'required|numeric',
            'millimeter' => 'required|numeric',
            'comments' => 'nullable|string',
            'net_amount'=>'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $tank = Tank::findOrFail($id);
        $stock = TankStock::where('tank_id', $id)->first();
        $vendor=DB::table('VENDOR')->where('VENDOR_ID',$request->vendor_id)->first();
        // ✅ Duplicate check before anything
//        $tankShift=TankShift::where('tank_id',$tank->id)->first();
        $tankShift=DB::table('shift')->where('status','=','1')->first();
//        dd($vendor,$request->vendor_id,$request->all());
        $duplicateExists = DB::table('tank_stock_event')
            ->where('tank_id', $id)
            ->where('chemb', $request->chemb)
            ->whereDate('invoice_date', $request->invoice_date)
            ->where('invoice_no', $request->invoice_no)
            ->exists();

        if ($duplicateExists) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Stock already exists for this Chemb with same Invoice Date & Invoice No.'
            ], 409);
        }
        // Update stock
        $stock->stock_value += $request->stock_change;
        $stock->millimeter = $request->millimeter ?? 0;

        $stock->save();
        $comments = $request->comments;
//        dd($comments,$request->all());
        // Insert/update LastFuelStatus and FuelStatus tables
        $now = Carbon::now();
        $tankId = $id;
        $tankshiftId=null;
        $atgInstalled = DB::table('SysConfig')->value('ATGEnable');
        $atgInstalled = (int) $atgInstalled; // Ensure it's an integer
        if ($atgInstalled != 1) {

            // Get the current active shift for this tank
            $currentShift = DB::table('tank_shifts')
                ->where('tank_id', $tankId)
                ->orderBy('start_time', 'desc')
                ->first();

            if ($currentShift) {
                $tankshiftId = $currentShift->id;

                // Calculate sales during the current shift period
                $salesTotal = \App\Models\SaleData::whereIn('FC_NZNo', explode(',', $tank->nozzle_ids))
                    ->whereBetween('tdate', [$currentShift->start_time, $now])
                    ->sum('qty');

                // Calculate all positive stock changes during this shift period (including current one)
                $positiveStock = DB::table('tank_stock_ledger')
                    ->where('tank_id', $tankId)
                    ->whereBetween('created_at', [$currentShift->start_time, $now])
                    ->where('stock_change', '>', 0)
                    ->sum('stock_change');

                // Add the current stock change to positive stock if it's positive
                if ($request->stock_change > 0) {
                    $positiveStock += $request->stock_change;
                }

                // Calculate the new quantity: opening totalizer - sales + positive stock changes
//                $newQty = $currentShift->opening_totalizer - $salesTotal + ($positiveStock * 100);
                $lastFuelStatus = DB::table('LastFuelStatus')->where('TID', $tankId)->first();
                $previousQty = $lastFuelStatus ? $lastFuelStatus->Qty : 0;
                $newQty = $previousQty + ($request->stock_change * 100);
            } else {
                // Fallback to simple addition if no active shift found
                $lastFuelStatus = DB::table('LastFuelStatus')->where('TID', $tankId)->first();
                $previousQty = $lastFuelStatus ? $lastFuelStatus->Qty : 0;
                $newQty = $previousQty + ($request->stock_change * 100);
            }
//dd($request->millimeter==0$lastFuelStatus->Level);
            // Insert into LastFuelStatus
            DB::table('LastFuelStatus')->updateOrInsert(
                ['TID' => $tankId],
                [
                    'Qty' => $newQty, // Previous value + stock_change
                    'Level' => ($request->millimeter == 0)
                        ? $lastFuelStatus->Level
                        : $request->millimeter,
                    'tdate' => $now
                ]
            );

            // Insert into FuelStatus
            DB::table('FuelStatus')->insert([
                'TID' => $tankId,
                'Qty' => $newQty, // Previous value + stock_change
                'Level' => ($request->millimeter == 0)
                    ? $lastFuelStatus->Level
                    : $request->millimeter,
                'tdate' => $now,
                // Optional fields can be set to null or default if needed
                'PAddr' => null,
                'WaterLevel' => null,
                'TEMP' => null,
            ]);
        }
        // Record in ledger
        else{
            $currentShift = DB::table('tank_shifts')
                ->where('tank_id', $tankId)
                ->orderBy('start_time', 'desc')
                ->first();

            if ($currentShift) {
                $tankshiftId = $currentShift->id;
            }

            $lastFuelStatus = DB::table('LastFuelStatus')->where('TID', $tankId)->first();
            $previousQty = $lastFuelStatus->Qty ?? 0;

            $newQty = $previousQty + ($request->stock_change * 100);

            // Manual tank → dip reading required
            $newLevel = $request->millimeter ?? ($lastFuelStatus->Level ?? 0);


// 🔥 COMMON INSERT
        DB::table('LastFuelStatus')->updateOrInsert(
            ['TID' => $tankId],
            [
                'Qty'   => $newQty,
                'Level' => $newLevel,
                'tdate' => $now
            ]
        );

        DB::table('FuelStatus')->insert([
            'TID'        => $tankId,
            'Qty'        => $newQty,
            'Level'      => $newLevel,
            'tdate'      => $now,
            'PAddr'      => null,
            'WaterLevel' => null,
            'TEMP'       => null,
        ]);
        }

        TankStockLedger::create([
            'tank_id' => $id,
            'transaction_type' => 'stock_adjustment',
            'stock_change' => $request->stock_change*100,
            'millimeter' => $request->millimeter,
            'comments' => $comments,
            'vendor_id'=> $vendor->id,
            'adjustment'=> 0,
            'adjustment_stock'=> 0,
        ]);

        // Prepare payload for the event
//        $payload = [
//            'tank_id' => $id,
//            'stock_change' => $request->stock_change,
//            'millimeter' => $request->millimeter,
//            'comments' => $comments,
//            'new_stock_value' => $stock->stock_value, // Stock value after addition
//            'added_at' => Carbon::now()->toIso8601String(),
//        ];
        $payload = [
            'tank_id'            => $id,
            'stock_change'       => $request->stock_change,
            'millimeter'         => $request->millimeter ?? 0,
            'comments'           => $request->comments ?? '',
            'new_stock_value'    => $stock->stock_value,
            'added_at'           => Carbon::now()->toIso8601String(),
            'product_id'          => $tank->product_id,

            // ✅ New fields
            'invoice_date'       => $request->invoice_date ? Carbon::parse($request->invoice_date) : null,
            'vendor_name'        => $vendor->VNAME ?? null,
            'invoice_no'         => $request->invoice_no ?? null,
            'delivery_or_sap_no' => $request->delivery_or_sap_no ?? null,
            'vehicle_no'         => $request->vehicle_no ?? null,
            'driver_name'        => $request->driver_name ?? null,
            'driver_cell'        => $request->driver_cell ?? null,
            'chemb'              => $request->chemb ?? null,
            'chemb_filling_dip'  => $request->chemb_filling_dip ?? null,
            'chemb_decanting_dip'=> $request->chemb_decanting_dip ?? null,
            'seal_no'            => $request->seal_no ?? null,
            'filling_tmp'        => $request->filling_tmp ?? null,
            'decanting_tmp'      => $request->decanting_tmp ?? null,
            'net_amount'         => $request->net_amount?? null,
            'vendor_id' =>$vendor->VENDOR_ID ?? $vendor->id,
            'pid' => $tankShift->id ?? null,
            'calendar_id' => $tankShift->calendar_id ?? null,
        ];

//        dd($payload);


        $eventMapping = Config::get('event_config.events_mapping');
        $eventType = $eventMapping['tank_stock_change'] ?? 0;

        // Send event data using the helper
        EventHelper::sendEventData($eventType, $payload ,$tankshiftId);


        return response()->json($stock);
    }

    // Get stock history
    public function stockHistory(Request $request)
    {
        $query = TankStockLedger::with('tank'); // Eager load the tank relationship

        if ($request->has('transaction_type') && $request->input('transaction_type') !== '') {
            $query->where('transaction_type', $request->input('transaction_type'));
        }
        if ($request->has('tank_id') && $request->input('tank_id') !== '' && $request->input('tank_id') !== null) {
            $query->where('tank_id', $request->input('tank_id'));
        }

        $history = $query->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json($history);
    }

    // Upload dip chart
    public function uploadDipChart(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:xls,xlsx',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Process Excel file
        $file = $request->file('file');
        DipChartValue::where('tank_id', $id)->delete();

        Excel::import(new DipChartImport($id), $file);

        return response()->json([
            'message' => 'Dip chart uploaded successfully',
        ], 201);
    }

    // Retrieve dip chart
    public function retreiveDipChart(Request $request, $id)
    {
        $dipChart = DipChartValue::where('tank_id', $id)->get();

        return response()->json($dipChart);
    }

//    public function getLastFuelStatus()
//    {
//        return response()->json(
//            DB::select("
//            SELECT
//                *,
//                (Level / 100) AS level
//            FROM LastFuelStatus
//        ")
//        );
////        return response()->json(DB::select('SELECT * FROM LastFuelStatus;'));
//    }

    public function getLastFuelStatusold()
    {

        return response()->json(
            DB::select("
            SELECT
                id,
                tdate,
                TID,
                Level,
                Qty,
                TEMP,
                water_level,
                water_qty,
                CASE
                WHEN Level >= 10000  THEN ROUND(Level / 100, 2)
                ELSE Level
            END AS level
            FROM LastFuelStatus
        ")
        );
    }
    public function getLastFuelStatus()
    {
        $fuelData = DB::select("
        SELECT
            id,
            tdate,
            TID,
            Level,
            Qty,
            TEMP,
            water_level,
            water_qty,
            CASE
                WHEN Level >= 10000 THEN ROUND(Level / 100, 2)
                ELSE Level
            END AS level
        FROM LastFuelStatus
    ");

        $sysCong = DB::table('SysConfig')->first();

        /* ===========================================
           🔥 UPDATE TANK TABLE + CHECK ALARMS
        =========================================== */

        if ($sysCong && $sysCong->Sys_Mode == 2 &&$sysCong->Sys_Mode == 3 && $sysCong->Sys_Mode ==1 ) {

            foreach ($fuelData as $row) {

                // ✅ Safe tank fetch
                $tank = \App\Models\Tank::find($row->TID);
                if (!$tank) {
                    continue;
                }

                // ✅ Convert safely
                $currentLevelMM = (float) $row->level;

                // 🔥 Update Tank Live Data
                $tank->temperature = isset($row->TEMP) ? ($row->TEMP / 100) : null;
                $tank->water_height_mm = $row->water_level ?? 0;
                $tank->save();

                // 🚀 CHECK ALARM (ONLY FOR THIS TANK)
                \App\Services\TankAlarmService::checkTankLevelLive(
                    $tank,
                    $currentLevelMM
                );
            }
        }

        return response()->json($fuelData);
    }
    public function getTankConfigTest()
    {
        try {
            // Agar alag table hai jaise "atg_config"
            $tanks = DB::table('ATGs')
                ->select('id', 'TankID','is_atg','iCode')
                ->get();

            return response()->json($tanks, 200);
        } catch (\Exception $e) {

            return response()->json([
                'error' => 'Unable to fetch tank configuration',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
    public function getTankByProduct($productKey)
    {
        $productMap = [
            'Petrol' => 1,
//            'diesel' => 2,
//            'hi_octane' => 3,
            'hi_octane' => 2,
            'diesel' => 3,

        ];

        if (!isset($productMap[$productKey])) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid product key'
            ], 400);
        }

        $productId = $productMap[$productKey];

        // ✅ GET ALL TANKS
        $tanks = DB::table('tanks')
            ->where('product_id', $productId)
            ->get();

        if ($tanks->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'Tank not found'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'tanks' => $tanks
        ]);
    }


    public function getTank($tankId)
    {
        $tank = DB::table('tanks')->where('id', $tankId)->first();

        if (!$tank) {
            return response()->json([
                'status' => false,
                'message' => 'Tank not found'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'tank' => $tank
        ]);
    }

    public function updateStock(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'stock_change' => 'required|numeric',
            'millimeter'   => 'nullable|numeric',
            'vendor_id'    => 'required',
            'invoice_no'   => 'nullable|string',
            'invoice_date' => 'nullable|date',
            'comments'     => 'nullable|string',
            'net_amount'   => 'nullable|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // 🔥 Find Ledger Entry
        $ledger = TankStockLedger::findOrFail($id);

        // 🔥 Get Tank Stock
        $tankStock = TankStock::where('tank_id', $ledger->tank_id)->first();

        if (!$tankStock) {
            return response()->json([
                'message' => 'Tank Stock Not Found'
            ], 404);
        }

        /**
         * ✅ SAFE UPDATE LOGIC
         * First rollback old stock
         * Then apply new stock
         */

        // Rollback Old Stock
        $oldStockChange = $ledger->stock_change; // already stored *100
        $tankStock->stock_value -= ($oldStockChange / 100);

        // Apply New Stock
        $newStockChange = $request->stock_change * 100;
        $tankStock->stock_value += ($newStockChange / 100);

        $tankStock->save();

        // 🔥 Update Ledger
        $ledger->update([
            'stock_change' => $newStockChange,
            'millimeter'   => $request->millimeter,
            'vendor_id'    => $request->vendor_id,
            'invoice_no'   => $request->invoice_no,
            'invoice_date' => $request->invoice_date,
            'comments'     => $request->comments,
            'net_amount'   => $request->net_amount,
            'adjustment'=>1,
            'adjustment_stock'=>$newStockChange,
        ]);

        return response()->json([
            'message' => 'Stock Updated Successfully'
        ]);
    }

    public function swapTanks(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tank_id' => 'required|integer|exists:tanks,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        DB::beginTransaction();

        try {
            $sourceTank = Tank::findOrFail($request->tank_id);

            if ((int) ($sourceTank->is_swapped ?? 0) !== 1) {
                return response()->json(['message' => 'Selected source tank is not in swap system.'], 422);
            }

            if (empty($sourceTank->swapped_with_tank_id)) {
                return response()->json(['message' => 'Source tank has no configured swap target.'], 422);
            }

            $targetTank = Tank::findOrFail($sourceTank->swapped_with_tank_id);

            if ((int) ($targetTank->is_swapped ?? 0) !== 1) {
                return response()->json(['message' => 'Configured target tank is not in swap system.'], 422);
            }

            // Source already active (target points back to source)
            if (!empty($targetTank->swapped_with_tank_id) && (int) $targetTank->swapped_with_tank_id === (int) $sourceTank->id) {
                $sourceNozzleIds = $sourceTank->nozzle_ids;

                // Idempotent behavior: keep pair active and resync state instead of erroring.
                $targetTank->update([
                    'is_active' => 1,
                    'nozzle_ids' => $sourceNozzleIds,
                ]);
                $this->syncPumpTankMapping($sourceNozzleIds, $targetTank->id);
//command table insert swap tank 911 type
                DB::table('commands')->insert([
                    'type'=>'911',
                    'response'=>null,
                    'exec'=>0,
                    'created_at'=>now(),
                    'updated_at'=>now(),
                ]);
                $sourceTank->update([
                    'is_active' => 0,
                    'nozzle_ids' => null,
                ]);

                DB::commit();
                return response()->json(['message' => 'Swap pair already active (state synchronized).']);
            }

            // Target engaged with another source
            if (!empty($targetTank->swapped_with_tank_id) && (int) $targetTank->swapped_with_tank_id !== (int) $sourceTank->id) {
                return response()->json(['message' => 'Configured target tank is already active with another source.'], 422);
            }

            // Strict one-active-pair rule: detect reciprocal links not belonging to this pair
            $pairs = Tank::query()
                ->whereNotNull('swapped_with_tank_id')
                ->get(['id', 'swapped_with_tank_id']);
            $links = $pairs->pluck('swapped_with_tank_id', 'id');

            foreach ($links as $tankId => $withId) {
                if (!$withId) continue;
                $back = $links->get((int) $withId);
                if ((int) $back === (int) $tankId) {
                    $isCurrentPair =
                        ((int) $tankId === (int) $sourceTank->id && (int) $withId === (int) $targetTank->id) ||
                        ((int) $tankId === (int) $targetTank->id && (int) $withId === (int) $sourceTank->id);
                    if (!$isCurrentPair) {
                        return response()->json(['message' => 'Another active swap pair already exists.'], 422);
                    }
                }
            }

            $sourceNozzleIds = $sourceTank->nozzle_ids;

            // Activate pair by writing reverse link on target.
            $targetTank->update([
                'swapped_with_tank_id' => $sourceTank->id,
                'is_active' => 1,
                'nozzle_ids' => $sourceNozzleIds,
            ]);
            $this->syncPumpTankMapping($sourceNozzleIds, $targetTank->id);
//command table insert swap tank 911 type
            DB::table('commands')->insert([
                'type'=>'911',
                'response'=>null,
                'exec'=>0
            ]);
            // Source tank becomes inactive after handover.
            $sourceTank->update([
                'is_active' => 0,
                'nozzle_ids' => null,
            ]);

            DB::commit();

            return response()->json(['message' => 'Tanks swapped successfully']);

        } catch (\Exception $e) {

            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function unswapTank(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tank_id' => 'required|integer|exists:tanks,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        DB::beginTransaction();

        try {
            $tank = Tank::findOrFail($request->tank_id);

            if (empty($tank->swapped_with_tank_id)) {
                return response()->json(['message' => 'Tank is not swapped.'], 422);
            }

            $sourceTank = Tank::find($tank->swapped_with_tank_id);

            if (!$sourceTank || (int) $sourceTank->swapped_with_tank_id !== (int) $tank->id) {
                return response()->json(['message' => 'Only active swap pair can be unswapped.'], 422);
            }

            $targetNozzleIds = $tank->nozzle_ids;

            // Restore both tanks to normal state.
            $tank->update([
//                'is_swapped' => 0,
//                'swapped_with_tank_id' => null,
                'is_active' => 0,
                'nozzle_ids' => null,
            ]);

            $sourceTank->update([
//                'is_swapped' => 0,
//                'swapped_with_tank_id' => null,
                'is_active' => 1,
                'nozzle_ids' => $targetNozzleIds,
            ]);
            $this->syncPumpTankMapping($targetNozzleIds, $sourceTank->id);

            DB::commit();

            return response()->json(['message' => 'Tank unswapped successfully']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    private function syncPumpTankMapping($nozzleIdsValue, int $tankId): void
    {
        if (empty($nozzleIdsValue)) {
            return;
        }

        $nozzleIds = collect(explode(',', (string) $nozzleIdsValue))
            ->map(fn ($id) => trim($id))
            ->filter(fn ($id) => $id !== '' && is_numeric($id))
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values()
            ->all();

        if (empty($nozzleIds)) {
            return;
        }

        DB::table('PUMPS')
            ->whereIn('FC_NZNo', $nozzleIds)
            ->update(['TNO' => $tankId]);
    }
}
