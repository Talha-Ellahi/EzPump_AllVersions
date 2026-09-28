<?php

namespace App\Http\Controllers;

use App\Models\paymentmethod;
use App\Models\Pumps;
use App\Models\saledata;
use App\Models\ShiftPaymentWiseSale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Shift;
use DateTime;
use DateTimeZone;
use Carbon\Carbon;
use App\Helpers\DatabaseHelper;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use App\Models\Settings;
use function Laravel\Prompts\table;

class ShiftController extends Controller
{

    public function disablePumpProcessing()
    {
        // Update settings table to disable pump processing
        DB::update('UPDATE PUMPS SET closed = 1 and status_changed = 1 where closed = 0');
        // $setting = Settings::where('key', 'pump_processing_enabled')->first();
        // if ($setting) {
        //     $setting->value = 1;
        //     $setting->save();
        // } else {
        //     $setting = new Settings();
        //     $setting->key = 'pump_processing_enabled';
        //     $setting->value = 0;
        //     $setting->save();
        // }

        return response()->json(['message' => 'Pump processing disabled successfully.', 'status' => true]);
    }

    public function enablePumpProcessing()
    {
        // Update settings table to enable pump processing
        DB::update('UPDATE PUMPS SET closed = 0 and status_changed = 1 where closed = 1');
        return response()->json(['message' => 'Pump processing enabled successfully.', 'status' => true]);
    }

    public function shiftsold()
    {
        $shifts = Shift::all();

        return response()->json($shifts);
    }
    public function shifts()
    {
        $shifts = Shift::where('status', 1)->get()->map(function ($s) {
            return [
                'id' => $s->id,
                'nozzle_no' => $s->pump_id,
                'opening_fuel' => $s->opening_fuel,
                'closing_fuel' => $s->closing_fuel,
                'opening_rate' => $s->rate,
                'closing_rate' => $s->new_rate ?? $s->rate,
                'sale_qty' => $s->total_qty,
                'sale_amount' => $s->total_amount,
                'icode' => $s->icode,
                'product_type' => $s->icode == 1 ? 'petrol' : 'diesel',
            ];
        });

        return response()->json($shifts);
    }


    // Method to check if a shift exists for a given pump ID
    public function checkShift($pumpId)
    {

        $shift = Shift::where('pump_id', $pumpId)->where('status', true)->first();
        $tableName = "shift_n$pumpId";
        if ($shift) {

            return response()->json(array_merge(
                ['status' => 'exists'],
                $this->_shiftStats($shift),

            ));
        } else {
            return response()->json([
                'status' => 'not_exists'
            ]);
        }
    }

    // Method to update shift data
    public function updateShift(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|integer|exists:shift,id',
            'opening_fuel_manual' => 'nullable|integer',
            'closing_fuel_manual' => 'nullable|integer',
            'opening_balance' => 'nullable|integer',
            'closing_balance' => 'nullable|integer',
            'adjustments' => 'nullable|integer',
            'changed_fuel_balance' => 'nullable|integer',
            'pump_id' => 'nullable|integer',
            'cashier_name' => 'nullable|string|max:255',
            'cashier_id' => 'required|integer',
            'last_sale_id' => 'nullable|integer',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'icode' => 'nullable|integer',
        ]);

        $shift = Shift::find($request->id);
        $employeeName=DB::table('employee')->where('emp_id',$shift->cashier_id)->first();
//        dd($employeeName,$shift->cashier_id);
        $validated['cashier_name']=$employeeName->emp_name;
        // Check if bIsUpdated is 1, exclude opening_balance and cashier_name

        if ($shift->bIsUpdated == 1) {
            $validated = array_filter($validated, function ($key) {
                return !in_array($key, ['opening_balance', 'cashier_id']);
            }, ARRAY_FILTER_USE_KEY);
        }
        if ($shift->bIsUpdated == 0) {
            // $validated = array_filter($validated, function ($key) {
            //     return in_array($key, ['id', 'opening_fuel_manual', 'closing_fuel_manual', 'opening_balance', 'closing_balance', 'adjustments', 'changed_fuel_balance', 'pump_id', 'cashier_name', 'last_sale_id', 'start_date', 'end_date']);
            // }, ARRAY_FILTER_USE_KEY);
            if (!array_key_exists('cashier_id', $validated)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Cashier name is required when bIsUpdated is 0'
                ], 400);
            }
        }
//        unset($validated['shifttimer']);
        // Update the shift
        $shift->update(array_merge($validated, ['bIsUpdated' => 1]));
        $shiftData = $shift->toArray();
        unset($shiftData['shiftTimer']);
        $shiftData['cashier_id']=$employeeName->emp_id;
        DB::table('shiftsend')->insert(array_merge($shiftData, ['type' => 2]));
//        DB::table('shiftsend')->insert(array_merge($shift->toArray(), ['type' => 2]));

        return response()->json([
            'status' => 'success',
            'shift' => $shift
        ]);
    }

    // // Method to create a new shift
    // public function openShift(Request $request)
    // {
    //     $request->validate([
    //         'opening_fuel' => 'nullable|integer',
    //         'closing_fuel' => 'nullable|integer',
    //         'opening_balance' => 'nullable|integer',
    //         'closing_balance' => 'nullable|integer',
    //         'adjustments' => 'nullable|integer',
    //         'total_qty' => 'nullable|integer',
    //         'status' => 'nullable|boolean',
    //         'rate' => 'nullable|integer',
    //         'is_changed' => 'nullable|boolean',
    //         'new_rate' => 'nullable|integer',
    //         'changed_fuel_balance' => 'nullable|integer',
    //         'pump_id' => 'required|integer',
    //         'cashier_name' => 'nullable|string|max:255',
    //         'last_sale_id' => 'nullable|integer',
    //         'start_date' => 'required|date',
    //         'end_date' => 'nullable|date'
    //     ]);

    //     $shift = Shift::create($request->all());

    //     return response()->json([
    //         'status' => 'success',
    //         'shift' => $shift
    //     ]);
    // }
    private function _shiftStats($shift)
    {
        $pumpId = $shift->pump_id;
        $tableName = "shift_n$pumpId";
        // $allEntries = DB::table($tableName)->get();
//        $result = DB::table($tableName)
//            ->select('p_mode', DB::raw('SUM(amt) as total_amount'), 'paymentmethod.Des as pMethod')
//            ->leftJoin('paymentmethod', 'paymentmethod.id', '=', "$tableName.p_mode")
//            ->groupBy('p_mode', 'paymentmethod.Des')
//            ->get();
        $customerCredits = DB::table("$tableName as s")
            ->select(DB::raw('SUM(s.amt) as total_amt'), 'c.Des', 's.RegNo')
            ->leftJoin('customer as c', 's.customer_id', '=', 'c.id')
            ->groupBy('c.Des', "s.RegNo")
            ->where('c.id', '!=', '1')
            ->get();
        //
//        $pump = \App\Models\Pumps::find($pumpId);;
        $pump = \App\Models\Pumps::where('FC_NZNo',$pumpId)->first();
        // Fetch shift payment wise sales data
        $shiftPaymentWiseSales = \App\Models\ShiftPaymentWiseSale::where('shift_id', $shift->id)->get();
        $result=[];
//        $customerCredits=[];
        return [
            'shift' => $shift,
            'paymentMethods' => $result,
            'customers' => $customerCredits,
            // 'allEntries' => $allEntries,
            'shiftPaymentWiseSales' => $shiftPaymentWiseSales, // Add this line
            'pump' => $pump,
        ];
    }

    public function shiftStates(Request $request, int $id)
    {
        $shift = Shift::findOrFail($id);

        return response()->json($this->_shiftStats($shift));
    }

    public function disablePumps(Request $request)
    {
        DB::table('PUMPS')->update(['closed' => 1]);
        return response()->json(['message' => 'Pumps disabled successfully.', 'status' => true]);
    }

    public function enablePumps(Request $request)
    {
        DB::table('PUMPS')->update(['closed' => 0]);
        return response()->json(['message' => 'Pumps enabled successfully.', 'status' => true]);
    }

    public function closeAllShifts(Request $request)
    {
        /**
         * =====================================================
         * 🔹 STEP 1: GET ALL SHIFTS
         * =====================================================
         */
        $shifts = Shift::orderBy('pump_id')->get();

        if ($shifts->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'No shifts found'
            ], 404);
        }

        $firstShift = $shifts->first();

        $workDate = $firstShift->start_date instanceof \Carbon\Carbon
            ? $firstShift->start_date->format('Y-m-d')
            : \Carbon\Carbon::parse($firstShift->start_date)->format('Y-m-d');

        $today = now()->toDateString();

        /**
         * =====================================================
         * 🔹 STEP 2: GET ACTIVE CALENDAR (000 / PARTIAL)
         * =====================================================
         */
        $calendarold = DB::table('shift_calendars')
            ->where('work_date', $workDate)
            ->where(function ($q) {
                $q->whereNull('shift_end_time')
                    ->orWhere('petrol_closed', 0)
                    ->orWhere('diesel_closed', 0)
                    ->orWhere('hi_octane_closed', 0);
            })
            ->latest('id')
            ->first();
        $calendar=DB::table('tank_shifts')->first();
        $newCalendarId=$calendar->calendar_id;
        /**
         * =====================================================
         * 🔁 STEP 6: CLOSE SHIFTS WITH NEW CALENDAR ID
         * =====================================================
         */

        foreach ($shifts as $shift) {
            $result = $this->closeShift($request, $shift->id, $newCalendarId, false);
            if ($result) {
                return $result;
            }
        }

        /**
         * =====================================================
         * 🔹 STEP 7: ALERT + EMPLOYEE SYNC
         * =====================================================
         */
        $alert = \App\Models\Alert::whereColumn('key', 'value')->first();

        if ($alert && $alert->value == '1') {
            $columns = Schema::getColumnListing('employee');
            DB::table('employee')->truncate();
            DB::table('employee')->insertUsing(
                $columns,
                DB::table('employee_shadow')->select($columns)
            );
        }

        \App\Models\Alert::updateOrCreate(['key' => 'is_shift_closed'], ['value' => '1']);
        \App\Models\Alert::updateOrCreate(['key' => 'is_shift_opened'], ['value' => '1']);

        return response()->json([
            'status'  => true,
            'message' => 'All shifts closed successfully'
        ]);
    }

//    public function closeShift(Request $request, int $id, $return = true)
//    {
//        // Check if closing_fuel_manual is provided in the request
//        $closingFuelManual = $request->input('closing_fuel_manual');
//
//        $shift = Shift::findOrFail($id);
//        $newShift = $shift;
//
//        // Get shift configuration
//        $shiftConfig = DB::table('SysConfig')->first();
//        $shiftType = $this->determineShiftType($shiftConfig);
//
//        // Update closing_fuel_manual if provided
//        if ($closingFuelManual !== null) {
//            $shift->closing_fuel_manual = $closingFuelManual;
//            $shift->save();
//        }
//
//        if ($shift->cashier_id === null) {
//            return response()->json(['error' => 'Cashier ID is required'], 400);
//        }
//        // if($shift->closing_fuel_manual === null || $shift->closing_fuel_manual >= $shift->opening_fuel_manual) {
//        //     return response()->json(['error' => 'Closing fuel manual is required'], 400);
//        // }
//        $shiftCloseDurationInMinutes = DB::table('settings')
//            ->where('key', 'shift_min_close_duration')
//            ->value('value'); // Get the value as a string
//
//        // Convert the string value to an integer
//        $shiftCloseDurationInMinutes = (int) $shiftCloseDurationInMinutes;
//        // Get the start date of the shift and the current time
//        $startDate = new DateTime($shift->start_date);
//        $currentTime = new DateTime();
//
//        // Calculate the difference in hours
//        // Convert minutes to hours
//        $shiftCloseDurationInHours = $shiftCloseDurationInMinutes / 60;
//
//        // Current time
//        $currentTime = Carbon::now();
//
//        // Calculate the difference in hours between the startDate and the current time
//        $interval = $currentTime->diff($startDate);
//        $hoursDifference = $interval->h + ($interval->days * 24); // Total hours difference
//
//        // // Check if the difference is less than the dynamically fetched close duration
////        if ($hoursDifference < $shiftCloseDurationInHours) {
////            return response()->json([
////                'error' => 'Shift cannot be closed as it has started less than the required duration.'
////            ], 400);
////        }
//
//        $pumpId = $shift->pump_id;
//        $shift->update(['status' => 0, 'end_date' => new DateTime()]);
//        $stats = $this->_shiftStats($shift);
//        $stats['shift_type'] = $shiftType;
//        $statsJson = json_encode($stats);
//
//        // Insert entry into shift_log table
//        DB::table('shift_log')->insert([
//            'pump_id' => $pumpId,
//            'start_date' => $shift->start_date,
//            'end_date' => $shift->end_date,
//            'data' => $statsJson,
//        ]);
//        DB::table('shiftsend')->insert(array_merge($shift->toArray(), ['type' => 3]));
//
//        $totalizerArray = DB::select('select totalizer from saledata where saledata.pos_id=? order by id desc limit 1', [$shift->pump_id]);
//        if (!empty($totalizerArray))
//            $totalizer = $totalizerArray[0]->totalizer;
//        else
//            $totalizer = $shift->opening_fuel;
//        $product = DB::table('PUMPS')
//            ->leftJoin('PRODUCT', 'PRODUCT.ICODE', '=', 'PUMPS.ICODE')
//            ->select('PRODUCT.*')
////            ->where('PUMPS.id', '=', $shift->pump_id)
//            ->where('PUMPS.ForeCourt_NzNo', '=', $shift->pump_id)
//            ->first();
//        \App\Models\ShiftPaymentWiseSale::where('shift_id', $shift->id)->delete();
//        // Delete the current shift
//        $shift->delete();
//        // Create a new shift
//        $newShift = Shift::create([
//            'opening_fuel' => $totalizer,
//            'closing_fuel' => $totalizer,
//            'opening_balance' => $shift->closing_balance ?? $shift->opening_balance,
//            'closing_balance' => 0, // $shift->closing_balance ?? $shift->opening_balance,
//            'adjustments' => null,
//            'total_qty' => 0,
//            'status' => 1, // New shift is active
//            'rate' => $product->SRATE,
//            'is_changed' => null,
//            'new_rate' => null,
//            'changed_fuel_balance' => null,
//            'pump_id' => $shift->pump_id,
//            'cashier_name' => $shift->cashier_name,
//            'cashier_id' => $shift->cashier_id,
//            'last_sale_id' => null,
//            'start_date' => new DateTime(), // Set to the current date and time
////            'icode' => $shift->icode,
//            'icode' => $product->ICODE,
//            'shiftTimer' => now()->format('H:i:s'),
//            'shift_type' => $shiftType,
//        ]);
//
//        DB::table('shiftsend')->insert(array_merge($newShift->toArray(), ['type' => 1]));
//
//        DB::table("shift_n$pumpId")->truncate();
//        DB::update('UPDATE PUMPS SET closed = 0 and status_changed = 1 where id = ?', [$pumpId]);
//
//        // Emit ShiftClosed event
//        event(new \App\Events\ShiftClosed($stats));
//
//        if ($return) {
//            \App\Models\Alert::updateOrCreate(['key' => 'is_shift_closed'], ['value' => '1']);
//            \App\Models\Alert::updateOrCreate(['key' => 'is_shift_opened'], ['value' => '1']);
//
//            return response()->json([
//                'status' => 'success',
//                'shift' => $newShift
//            ]);
//        }
//
//    }

    public function closeShift(Request $request, int $id,$calendarNewId, $return = true)
    {


        $shift = Shift::findOrFail($id);

        $closingFuelManual = $request->input('closing_fuel_manual');

//        $newShift = $shift;

        // Get shift configuration
        $shiftConfig = DB::table('SysConfig')->first();

        $shiftType = $this->determineShiftType($shiftConfig);

        // Update closing_fuel_manual if provided
        if ($closingFuelManual !== null) {
            $shift->closing_fuel_manual = $closingFuelManual;
            $shift->save();
        }

        if ($shift->cashier_id === null) {
            return response()->json(['error' => 'Cashier ID is required'], 400);
        }
        // if($shift->closing_fuel_manual === null || $shift->closing_fuel_manual >= $shift->opening_fuel_manual) {
        //     return response()->json(['error' => 'Closing fuel manual is required'], 400);
        // }
        $shiftCloseDurationInMinutes = DB::table('settings')
            ->where('key', 'shift_min_close_duration')
            ->value('value'); // Get the value as a string

        // Convert the string value to an integer
        $shiftCloseDurationInMinutes = (int) $shiftCloseDurationInMinutes;
        // Get the start date of the shift and the current time
        $startDate = new DateTime($shift->start_date);
        $currentTime = new DateTime();
 //check one more time upper and lower and value exists and not  0 and  0 check bypass and 1 value check

        // Calculate the difference in hours
        // Convert minutes to hours
        $shiftCloseDurationInHours = $shiftCloseDurationInMinutes / 60;

        // Current time
        $currentTime = Carbon::now();

        // Calculate the difference in hours between the startDate and the current time
        $interval = $currentTime->diff($startDate);
        $hoursDifference = $interval->h + ($interval->days * 24); // Total hours difference

        // // Check if the difference is less than the dynamically fetched close duration
        if ($hoursDifference < $shiftCloseDurationInHours) {
            return response()->json([
                'error' => 'Shift cannot be closed as it has started less than the required duration.'
            ], 400);
        }


        $pumpId = $shift->pump_id;
        $shift->update(['status' => 0, 'end_date' => new DateTime()]);

        // ✅ FIX: hardware-reported total_qty can be wrong; save meter difference (closing_fuel - opening_fuel)
        if ($shift->opening_fuel !== null && $shift->closing_fuel !== null) {
            $shift->total_qty = $shift->closing_fuel - $shift->opening_fuel;
            $shift->save();
        }

        $stats = $this->_shiftStats($shift);
        $stats['shift_type'] = $shiftType;
        $statsJson = json_encode($stats);
       $employeeName=DB::table('employee')->where('id',$shift->cashier_id)->first();
        // Log closed shift
        DB::table('shift_log')->insert([
            'pump_id'    =>$shift->pump_id, // physical pump ID
            'start_date' => $shift->start_date,
            'end_date'   => $shift->end_date,
            'data'       => $statsJson,
            'calendar_id'=>$shift->calendar_id,
        ]);

        $data = $shift->toArray();

        unset($data['shiftTimer']);
        $data['cashier_id']=$employeeName->emp_id;
        DB::table('shiftsend')->insert(array_merge($data, ['type' => 3]));
//        DB::table('shiftsend')->insert(array_merge($shift->toArray(), ['type' => 3]));
        // Get totalizer value
//        $totalizerArray = DB::select(
//            'SELECT totalizer FROM saledata WHERE pos_id = ? ORDER BY id DESC LIMIT 1',
//            [$shift->pump_id]
        $totalizerArray = DB::select(
            'SELECT totalizer FROM saledata WHERE FC_NZNo = ?  ORDER BY id DESC LIMIT 1',
            [$shift->pump_id]
        );
        $totalizer = !empty($totalizerArray) ? $totalizerArray[0]->totalizer : $shift->opening_fuel;

        // Get product
        $product = DB::table('PUMPS')
            ->leftJoin('PRODUCT', 'PRODUCT.ICODE', '=', 'PUMPS.ICODE')
            ->select('PRODUCT.*')
//            ->where('PUMPS.id', '=', $shift->pump_id)
            ->where('PUMPS.FC_NZNo', '=', $shift->pump_id)
            ->first();

        \App\Models\ShiftPaymentWiseSale::where('shift_id', $shift->id)->delete();

        // Save old data for new shift
        $oldShiftData = $shift->toArray();
        DB::table("shift_n{$shift->pump_id}")->truncate();
        // ✅ Hard delete old shift to avoid unique constraint error
        $shift->forceDelete();

//        dd($calendarNewId);
        // Create new shift
        $newShift = Shift::create([
            'calendar_id'=>$calendarNewId,
            'opening_fuel' => $totalizer,
            'closing_fuel' => $totalizer,
            'opening_balance' => $shift->closing_balance ?? $shift->opening_balance,
            'closing_balance' => 0, // $shift->closing_balance ?? $shift->opening_balance,
            'adjustments' => null,
            'total_qty' => 0,
            'status' => 1, // New shift is active
            'rate' => $product->SRATE,
            'is_changed' => null,
            'new_rate' => null,
            'changed_fuel_balance' => null,
            'pump_id' => $shift->pump_id,
            'cashier_name' => $employeeName->emp_name,
            'cashier_id' => $shift->cashier_id,
            'last_sale_id' => null,
//            'start_date' => new DateTime(), // Set to the current date and time
            'start_date'=>now()->addSeconds(1),
            'icode' => $shift->icode,
            'shiftTimer' => now()->format('H:i:s'),
            'shift_type' => $shiftType, //shift id add 1,2,3

        ]);

//        DB::table('shiftsend')->insert(array_merge($newShift->toArray(), ['type' => 1]));
        $data_shift = $newShift->toArray();
        unset($data_shift['shiftTimer']);
// Now insert the modified array
        $data_shift['cashier_id']=$employeeName->emp_id;
        DB::table('shiftsend')->insert(array_merge($data_shift, ['type' => 1]));
        // Truncate shift temp table
//        DB::table("shift_n{$shift->pump_id}")->truncate();
        $pump = Pumps::where('FC_NZNo',$shift->pump_id)->first();
        // Reset pump status
        DB::update('UPDATE PUMPS SET closed = 0, status_changed = 1 WHERE id = ?', [$pump->id]);
        $this->shiftPaymentWise($newShift->id);
        // Emit event
//        event(new \App\Events\ShiftClosed($stats));
 //shift code update

        if ($return) {
            \App\Models\Alert::updateOrCreate(['key' => 'is_shift_closed'], ['value' => '1']);
            \App\Models\Alert::updateOrCreate(['key' => 'is_shift_opened'], ['value' => '1']);

            return response()->json([
                'status' => 'success',
                'shift'  => $newShift
            ]);
        }
    }


 private function shiftPaymentWise($shift_id)
 {
     $paymentMethod=PaymentMethod::get();
     foreach ($paymentMethod as  $value) {
         $shiftPayment = ShiftPaymentWiseSale::create([
             'shift_id'=>$shift_id,
              'paymentmethod_id'=>$value->id,
                'total_sale'=>0,
                'total_qty'=>0,
         ]);
     }

 }
    private function _createTankStockLedgerTransaction($stats)
    {
        $shift = $stats['shift'];
        $tankId = $shift->tank_id;

        if (!$tankId) {
            return;
        }

        \App\Models\TankStockLedger::create([
            'tank_id' => $tankId,
            'shift_id' => $shift->id,
            'opening_fuel' => $shift->opening_fuel,
            'closing_fuel' => $shift->closing_fuel,
            'opening_balance' => $shift->opening_balance,
            'closing_balance' => $shift->closing_balance,
            'adjustments' => $shift->adjustments,
            'total_qty' => $shift->total_qty,
            'transaction_date' => now(),
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }

    public function shiftLogs(Request $request)
    {
        // Retrieve start and end date from request
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        // Base query
        $query = DB::table('shift_log')->select('pump_id', 'start_date', 'end_date', 'id','calendar_id');

        // Check if only one date is provided
        if ($startDate && $endDate) {
            $query->whereDate('start_date', '>=', $startDate)
                  ->whereDate('start_date', '<=', $endDate);
        } elseif ($startDate) {


            $query->whereDate('start_date', '=', $startDate);
        } else {
            // pump count
            $pumpCount = DB::table('PUMPS')->count();
            // limit the results to the number of pumps
            $query->limit($pumpCount*30);
        }

        $shiftLogs = $query->orderBy('id', 'desc')->get();

        return $shiftLogs;
    }
    /**
     * Determine current shift type based on time and configuration
     */
    private function determineShiftType($config)
    {
        $now = Carbon::now();
        $hour = $now->hour;

        if (!isset($config->NoofShifts)) {
            return 6;
        }

        if ($config->NoofShifts == 1) {
            return 6; // 24 hour shift
        } elseif ($config->NoofShifts == 2) {
            // 12 hour shifts (6AM-6PM = shift 1, 6PM-6AM = shift 2)
            return ($hour >= 6 && $hour < 18) ? 4 : 5;
        } else {
            // 8 hour shifts
            if ($hour >= 6 && $hour < 14) return 1;
            if ($hour >= 14 && $hour < 22) return 2;
            return 3; // 10PM-6AM
        }
    }
    // app/Http/Controllers/ShiftController.php
    public function closeByTank(Request $request)
    {
        $request->validate([
            'tank_id' => 'required|integer|exists:tanks,id'
        ]);

        $tank = DB::table('tanks')
            ->where('id', $request->tank_id)
            ->first();
//
        $productId = $tank->product_id;
//

//
//        // ─── Get nozzles/pumps for this product ─────────────────────────────────
        $pumpIds = DB::table('PUMPS')
            ->where('ICODE', $productId)
            ->pluck('FC_NZNo');

        if ($pumpIds->isEmpty()) {
            return response()->json([
                'status'  => false,
                'message' => 'No nozzles/pumps found for this product'
            ], 404);
        }
//
//        // ─── Get active shifts ──────────────────────────────────────────────────
        $shifts = Shift::whereIn('pump_id', $pumpIds)
            ->where('status', 1)
            ->get();

        if ($shifts->isEmpty()) {
            return response()->json([
                'status'  => false,
                'message' => 'No active shifts found for this product'
            ], 404);
        }

        $firstShift = $shifts->first();


        // ─── Close all related active shifts ────────────────────────────────────
        $activeCalendar = DB::table('shift_calendars')
            ->whereNull('shift_end_time')
            ->orderBy('id', 'desc')
            ->first();

        foreach ($shifts as $shift) {
            $this->closeShift($request, $shift->id, $activeCalendar->id , false);
        }

        return response()->json([
            'status'  => true,
            'message' => "Product closed successfully ( $tank->id)",
            'calendar_id' =>  $activeCalendar->id
        ]);
    }




    public function checkTodayShift()
    {
        $today = now()->toDateString();

        /**
         * 🔹 STEP 1: GET ACTIVE CALENDAR (000 or partial)
         */
        $calendar = DB::table('shift_calendars')
            ->where('work_date', $today)
            ->whereNull('shift_end_time')   // ✅ only active row
            ->orderBy('id', 'asc')          // oldest active
            ->first();

        /**
         * 🔹 STEP 2: IF NO ACTIVE SHIFT
         */
        if (!$calendar) {
            return response()->json([
                'petrol'     => 0,
                'diesel'     => 0,
                'hi_octane'  => 0,
                'message'    => 'No active shift calendar found'
            ]);
        }

        /**
         * 🔹 STEP 3: RETURN EXACT PRODUCT STATUS
         * 0 = button ENABLE
         * 1 = button DISABLE
         */
        return response()->json([
            'petrol'     => (int) $calendar->petrol_closed,
            'diesel'     => (int) $calendar->diesel_closed,
            'hi_octane'  => (int) $calendar->hi_octane_closed,
            'message'    => 'Product status fetched'
        ]);
    }




}

