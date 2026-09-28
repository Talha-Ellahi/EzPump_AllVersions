<?php

namespace App\Http\Controllers;

use App\Models\FuelStatus;
use App\Models\Product;
use App\Models\Tank;
use App\Models\TankStock;
use App\Models\TankStockLedger;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;



class AtgController extends Controller
{

    public function getSystem()
    {
        // Agar table sysconfig me data hai
        $system = DB::table('SysConfig')->first();

        if(!$system){
            return response()->json([
                'Sys_Mode' => 1   // Default value
            ]);
        }

        return response()->json([
            'Sys_Mode' => $system->Sys_Mode ?? 1
        ]);
    }

    /* =====================================================
       =================== PRODUCTS ========================
       ===================================================== */



    // ✅ Add Product
    public function addProduct(Request $request)
    {
        $request->validate([
            'ITMNAME' => 'required',
            'PRATE'   => 'nullable|numeric',
        ]);

        // 🔥 Last ICODE find karein
        $lastCode = Product::max('ICODE');

        // Agar table empty hai to 1 se start kare
        $newCode = $lastCode ? $lastCode + 1 : 1;
        $product = Product::create([
            'ICODE'  => $newCode,
            'ITMNAME'=> $request->ITMNAME,
            'UOM'    => $request->UOM,
            'PRATE'  => $request->PRATE / 100,
        ]);

        return response()->json([
            'status' => 200,
            'message'=> 'Product added successfully',
            'data'   => $product
        ]);
    }
    // ✅ Update Product
    public function updateProduct(Request $request, $id)
    {
        $request->validate([
            'ITMNAME' => 'required',
            'PRATE'   => 'nullable|numeric',
            'SRATE'   => 'nullable|numeric',
        ]);

        $product = Product::findOrFail($id);

        $product->update([
            'ITMNAME' => $request->ITMNAME,
            'UOM'     => $request->UOM,
            'PRATE'   => $request->PRATE/100,
//            'SRATE'   => $request->SRATE,
        ]);

        return response()->json([
            'status'  => 200,
            'message' => 'Product updated successfully'
        ]);
    }

    /* =====================================================
       =================== VENDORS =========================
       ===================================================== */

    // ✅ Vendor List
    public function vendors()
    {
        $vendors = DB::table('VENDOR')
            ->orderBy('id','desc')
            ->get();
//dd($vendors);
        return response()->json($vendors);
    }

    // ✅ Add Vendor
    public function addVendor(Request $request)
    {
//        dd($request->all());
        $request->validate([
            'VNAME'  => 'required',
            'PH1' => 'nullable',
            'CNIC'  => 'nullable',
        ]);

        DB::table('VENDOR')->insert([
            'VNAME'  => $request->VNAME,
            'PH1' => $request->PH1,
            'CNIC'  => $request->CNIC,
        ]);

        return response()->json([
            'status'  => 200,
            'message' => 'Vendor added successfully'
        ]);
    }

    // ✅ Update Vendor
    public function updateVendor(Request $request, $id)
    {
        $request->validate([
            'VNAME'  => 'required',
            'PH1' => 'nullable',
            'CNIC'  => 'nullable',
        ]);

        DB::table('VENDOR')
            ->where('id',$id)
            ->update([
                'VNAME'  => $request->VNAME,
                'PH1' => $request->PH1,
                'CNIC'  => $request->CNIC,
            ]);

        return response()->json([
            'status' => 200,
            'message'=> 'Vendor updated successfully'
        ]);
    }

    // ✅ Delete Vendor
    public function deleteVendor($id)
    {
        DB::table('VENDOR')->where('id',$id)->delete();

        return response()->json([
            'status'=>200,
            'message'=>'Vendor deleted'
        ]);
    }


    /* =====================================================
       =================== ALARMS ==========================
       ===================================================== */

    // ✅ Alarm List
    public function alarmList()
    {
        $alarm = DB::table('atg_alarm_settings')->first();

        return response()->json($alarm);
    }

    // ✅ Add / Update Alarm
    public function saveAlarm(Request $request)
    {
//        dd($request->all());
        $data = [
            'alarm_time'              => $request->alarm_time,
            'alarm_after_unloading'    => $request->alarm_after_unloading,
            'alarm_display_time'       => $request->alarm_display_time,

            'tank_low_alarm'             => $request->tank_low_alarm ? 1 : 0,
            'low_low_level_alarm_mm'     => $request->low_low_level_alarm_mm ? 1 : 0,
            'tank_high_alarm'            => $request->tank_high_alarm ? 1 : 0,
            'high_high_level_alarm_mm'   => $request->high_high_level_alarm_mm ? 1 : 0,
            'water_alarm'              => $request->water_alarm ? 1 : 0,

            'beep_once'                 => $request->beep_once ? 1 : 0,
            'is_email'                 => $request->is_email ? 1 : 0,
        ];

        $exists = DB::table('atg_alarm_settings')->first();

        if ($exists) {

            DB::table('atg_alarm_settings')
                ->update($data);

        } else {

            DB::table('atg_alarm_settings')
                ->insert($data);
        }

        return response()->json([
            'status' => 200,
            'message' => 'Alarm Settings Saved Successfully'
        ]);
    }
    public function updateShift(Request $request)
    {
        $request->validate([
            'NoofShifts' => 'required'
        ]);

        DB::table('SysConfig')->update([
            'NoofShifts' => $request->NoofShifts
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Shift updated successfully'
        ]);
    }
    public function tankDelete($id){
        DB::table('tanks')->where('id',$id)->delete();
        return response()->json([
            'success' => true,
            'message' => 'Tank Deleted successfully'
        ]);
    }
    public  function purchaseReport($tank_id)
    {
        $query = TankStockLedger::with('tank')
        ->where('id',$tank_id) // Eager load the tank relationship
        ->where('transaction_type','stock_adjustment')->first(); // Eager load the tank relationship

        $settings = \App\Models\Settings::getSettingsArray();
        $settings = $settings ?? [];
       return  view('tank.purchase_print',compact('query','settings'));

    }
    public  function adjustmentReport($id)
    {
        $query = TankStockLedger::with('tank')
        ->where('id',$id) // Eager load the tank relationship
        ->where('transaction_type','stock_adjustment')->first(); // Eager load the tank relationship

        $settings = \App\Models\Settings::getSettingsArray();
        $settings = $settings ?? [];
       return  view('tank.adjustment_print',compact('query','settings'));

    }
    public function alarmHistory()
    {
        $alarms = DB::table('tank_alarm_history')
            ->join('tanks','tank_alarm_history.tank_id','=','tanks.id')
            ->leftJoin('PRODUCT','tank_alarm_history.product_id','=','PRODUCT.ICODE')
            ->select(
                'tank_alarm_history.*',
                'tanks.tank_name as tank_name',
                'PRODUCT.ITMNAME as product_name'
            )
            ->orderBy('tank_alarm_history.start_time','desc')
            ->paginate(50); // pagination best practice

        return view('alarms.history', compact('alarms'));
    }
    public function alarmPrint(Request $request)
    {
        $query = DB::table('tank_alarm_history')
            ->join('tanks','tank_alarm_history.tank_id','=','tanks.id')
            ->leftJoin('PRODUCT','tank_alarm_history.product_id','=','PRODUCT.ICODE')
            ->select(
                'tank_alarm_history.*',
                'tanks.tank_name as tank_name',
                'PRODUCT.ITMNAME as product_name'
            )
            ->orderBy('tank_alarm_history.start_time','desc');

        /* ======================================
            ✅ FILTER BY SELECTED IDS
        ====================================== */

        if ($request->ids) {

            $ids = explode(',', $request->ids);

            $query->whereIn('tank_alarm_history.id', $ids);
        }

        /* ======================================
            ✅ DATE FILTER
        ====================================== */

        if ($request->start_date && $request->end_date) {

            $query->whereBetween('tank_alarm_history.start_time', [
                $request->start_date,
                $request->end_date
            ]);
        }

        $alarms = $query->get();

        $settings = \App\Models\Settings::getSettingsArray();
        $settings = $settings ?? [];

        return view('alarms.print', compact('alarms','settings'));
    }
    /**
     * Get chart data for stepline chart
     */
    public function getChartData(Request $request, $tankId)
    {
        try {

            $tank = Tank::findOrFail($tankId);

            $range = $request->range ?? 'today';
            $start = $request->start;
            $end   = $request->end;

            // ✅ DATE FILTER LOGIC
            if ($range == 'today') {

                $startDate = Carbon::today()->startOfDay();
                $endDate   = Carbon::today()->endOfDay();

            } elseif ($range == '30') {

                $startDate = Carbon::today()->subDays(30)->startOfDay();
                $endDate   = Carbon::today()->endOfDay();

            } elseif ($range == 'custom' && $start && $end) {

                $startDate = Carbon::parse($start)->startOfDay();
                $endDate   = Carbon::parse($end)->endOfDay();

            } else {

                // Default = 7 days
                $startDate = Carbon::today()->subDays(7)->startOfDay();
                $endDate   = Carbon::today()->endOfDay();
            }

            // 🔥 Fuel Data
            $fuelStatuses = FuelStatus::where('TID', $tankId)
                ->whereBetween('tdate', [$startDate, $endDate])
                ->orderBy('tdate', 'asc')
                ->get();
//dd($fuelStatuses,$startDate, $endDate);
            // 🔥 Stock / Refuel Data
            $stocks = TankStock::where('tank_id', $tankId)
                ->whereBetween('updated_at', [$startDate, $endDate])
                ->orderBy('updated_at', 'asc')
                ->get();
//            $stocks = DB::table('fuelstatus')->where('TID', $tankId)
//                ->whereBetween('tdate', [$startDate, $endDate])
//                ->orderBy('tdate', 'asc')
//                ->get();
//dd($stocks);
            $timestamps = [];
            $fuelLevels = [];
            $waterLevels = [];
            $refuelEvents = [];

            foreach ($fuelStatuses as $status) {

                $timestamps[] = Carbon::parse($status->tdate)->timestamp * 1000;
                $fuelLevels[]  = (float) ($status->Level ?? 0);
                $waterLevels[] = (float) ($status->Level/100 ?? 0);
//                dd($timestamps);

            }

            foreach ($stocks as $stock) {

                $fuelLevelAtTime = $this->getFuelLevelAtTime($tankId, $stock->updated_at);

                $refuelEvents[] = [
                    "timestamp"  => Carbon::parse($stock->updated_at)->timestamp * 1000,
                    "fuelLevel"  => $fuelLevelAtTime,
                    "amount"     => (float) ($stock->stock_change ?? 0)
                ];

            }

            return response()->json([
                "success" => true,
                "tank_name" => $tank->tank_name,
                "timestamps" => $timestamps,
                "fuelLevels" => $fuelLevels,
                "waterLevels" => $waterLevels,
                "refuelEvents" => $refuelEvents,
                "meta" => [
                    "start_date" => $startDate->format('Y-m-d H:i:s'),
                    "end_date"   => $endDate->format('Y-m-d H:i:s'),
                    "total_points" => count($timestamps)
                ]
            ]);

        } catch (\Exception $e) {

            return response()->json([
                "success" => false,
                "error" => $e->getMessage()
            ], 500);

        }
    }

    /**
     * Get latest chart data for real-time updates
     */
    public function getLatestChartData(Request $request, $tankId)
    {
        try {

            $range = $request->range ?? 'today';
            $start = $request->start;
            $end   = $request->end;

            if ($range == 'today') {

                $startDate = Carbon::today()->startOfDay();
                $endDate   = Carbon::today()->endOfDay();

            } elseif ($range == '30') {

                $startDate = Carbon::today()->subDays(30)->startOfDay();
                $endDate   = Carbon::today()->endOfDay();

            } elseif ($range == 'custom' && $start && $end) {

                $startDate = Carbon::parse($start)->startOfDay();
                $endDate   = Carbon::parse($end)->endOfDay();

            } else {

                // Default = 7 days
                $startDate = Carbon::today()->subDays(7)->startOfDay();
                $endDate   = Carbon::today()->endOfDay();
            }

            $fuelStatuses = FuelStatus::where('TID', $tankId)
                ->whereBetween('tdate', [$startDate, $endDate])
                ->orderBy('tdate', 'asc')
                ->get();

            $stocks = TankStock::where('tank_id', $tankId)
                ->whereBetween('updated_at', [$startDate, $endDate])
                ->orderBy('updated_at', 'asc')
                ->get();
            $timestamps = [];
            $fuelLevels = [];
            $waterLevels = [];
            $refuelEvents = [];

            foreach ($fuelStatuses as $status) {

                $timestamps[] = Carbon::parse($status->tdate)->timestamp * 1000;
                $fuelLevels[]  = (float) ($status->Level ?? 0);
                $waterLevels[] = (float) ($status->Waterlevel ?? 0);

            }

            foreach ($stocks as $stock) {

                $refuelEvents[] = [
                    "timestamp" => Carbon::parse($stock->updated_at)->timestamp * 1000,
                    "fuelLevel" => $this->getFuelLevelAtTime($tankId, $stock->updated_at),
                    "amount" => (float) ($stock->stock_change ?? 0)
                ];
            }

            return response()->json([
                "success" => true,
                "timestamps" => $timestamps,
                "fuelLevels" => $fuelLevels,
                "waterLevels" => $waterLevels,
                "refuelEvents" => $refuelEvents
            ]);

        } catch (\Exception $e) {

            return response()->json([
                "success" => false,
                "error" => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Helper function to get fuel level at specific time
     */
    private function getFuelLevelAtTime($tankId, $timestamp)
    {
        try {
            $fuelStatus = FuelStatus::where('TID', $tankId)
                ->where('tdate', '<=', $timestamp)
                ->orderBy('tdate', 'desc')
                ->first();

            return $fuelStatus ? (float) $fuelStatus->Level : 0;
        } catch (\Exception $e) {
            return 0;
        }
    }

    public function startLeakage(Request $request)
    {
        $tankId = $request->tank_id;

        $lastfuelStatus = DB::table('lastfuelstatus')
            ->where('TID',$tankId)
            ->first();

        DB::table('leakage_report')->insert([
            'tank_id' => $tankId,
            'opening_fuel_mm' => $lastfuelStatus->Level,
            'start_date' => now(),
            'is_button' => 1
        ]);

        return response()->json([
            'message' => 'Leakage monitoring started'
        ]);
    }
    public function stopLeakage(Request $request)
    {
        $tankId = $request->tank_id;

        $report = DB::table('leakage_report')
            ->where('tank_id',$tankId)
            ->where('is_button',1)
            ->first();

        $lastfuelStatus = DB::table('lastfuelstatus')
            ->where('TID',$tankId)
            ->first();

        $closing = $lastfuelStatus->Level;

        $difference = $closing - $report->opening_fuel_mm;

        DB::table('leakage_report')
            ->where('id',$report->id)
            ->update([
                'closing_fuel_mm' => $closing,
                'difference_fuel' => $difference,
                'end_date' => now(),
                'is_button' => 0
            ]);

        return response()->json([
            'message' => 'Leakage monitoring stopped'
        ]);
    }
    public function leakageStatus()
    {
        $running = DB::table('leakage_report')
            ->where('is_button',1)
            ->pluck('tank_id');

        return response()->json($running);
    }
    public function leakageReport()
    {
        $reports = DB::table('leakage_report as lr')
            ->join('tanks as t', 't.id', '=', 'lr.tank_id')
            ->select(
                'lr.id',
                't.tank_name as tank_name',
                'lr.opening_fuel_mm',
                'lr.closing_fuel_mm',
                'lr.difference_fuel',
                'lr.start_date',
                'lr.end_date',
                'lr.is_button'
            )
            ->orderBy('lr.start_date','desc')
            ->paginate(5);

        return view('reports.leakage_report', compact('reports'));
    }
}
