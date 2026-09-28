<?php

namespace App\Http\Controllers;

use App\Models\Shift;
use App\Models\ShiftPaymentWiseSale;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\saledata;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Data2Print;
use App\Models\SaledataUpdate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\Models\paymentmethod;
use App\Models\Vehicles;
use Carbon\Carbon;


class pumpdata extends Controller
{
    public function index()
    {
        $nozzles = DB::select('select * from PUMPS');

        $payments = paymentmethod::all();

        $data = compact('nozzles', 'payments');

        return view('index')->with($data);
    }
    public function fresh(Request $request)
    {
        return view('sales_page');
    }
    public function fresh_old(Request $request)
    {
        // Apply filters if they are present
        // Build the query
        $query = saledata::query();
//        dd($query);
        $autoUpdate = true;
        $user = Auth::user();
        // Apply filters if they are present
        if ($request->filled('nozzles')) {
            $nozzles = explode(',', $request->query('nozzles'));
            $query->whereIn('FC_NZNo', $nozzles);
//            dd($nozzles);

        }
        if ($request->filled('startDate') && $request->filled('endDate')) {
            // Convert the dates to the proper format
            $startDate = \Carbon\Carbon::createFromFormat('Y-m-d\TH:i', $request->query('startDate'))->format('Y-m-d H:i:s');
            $endDate = \Carbon\Carbon::createFromFormat('Y-m-d\TH:i', $request->query('endDate'))->format('Y-m-d H:i:s');

            // Use the formatted dates in the query
            $query->whereBetween('pdate', [$startDate, $endDate]);
            $autoUpdate = false;
        } else {
            // Apply limit only if start and end dates are not given
            $query->limit(50);
        }


        if ($request->filled('amountMin') && $request->filled('amountMax')) {
            $query->whereBetween('amt', [$request->query('amountMin') * 100, $request->query('amountMax') * 100]);
        }

        if ($request->filled('qtyMin') && $request->filled('qtyMax')) {
            $query->whereBetween('qty', [$request->query('qtyMin') * 100, $request->query('qtyMax') * 100]);
        }

        // Check for not null fields
        if ($request->filled('checkNotNull')) {
            $notNullFields = explode(',', $request->query('checkNotNull'));
            foreach ($notNullFields as $field) {
                $query->whereNotNull($field);
            }
        }
        if ($request->filled('paymentMethods')) {
            $paymentMethods = explode(',', $request->query('paymentMethods'));
            // Apply 'whereIn' to match the selected payment methods
            $query->whereIn('p_mode', $paymentMethods);
        }
        if (false && $user->role > 0) {
            $shifts = DB::select("SELECT `start_date` FROM shift LIMIT 1");

            if (!empty($shifts)) {
                $shift = $shifts[0];  // Access the first element
            }
            if ($shift) {
                $formattedStartDate = Carbon::parse($shift->start_date); // Adjust the format as needed
                $query->where('pdate', '>=', $formattedStartDate);
            } else {
            }
        }
        // Execute the query
        $Sales = $query->orderBy('id', 'desc')->get();


        // $queries = DB::getQueryLog();
        // $lastQuery = end($queries);

        // Log the last query or do something with it
        // For demonstration, we're just dumping it

        $Products = product::all();
        $Payments = paymentmethod::all();
        $Customers = Customer::all();
        $data = compact('Sales', 'Products', 'Payments', 'Customers', 'autoUpdate');
        return view('CardSale')->with($data);
    }
    public function printDuplicate(Request $request, $shiftTableId, $saleId)
    {
        // $saleData = saledata::findOrFail($saleId);
        $tableName = "shift_n$shiftTableId";
        $saleData = DB::selectOne("select * from $tableName where id=?", [$saleId]);
        $shiftData = DB::selectOne("select * from PUMPS  where id=?", [$shiftTableId]);
        $print = new Data2Print();
        $print->datasale_id = $saleData->tid;
        $print->pdate = $saleData->tdate;

        $print->pos_id = $saleData->pos_id;
        $print->FC_NZNo = $shiftData->FC_NZNo;
        $print->icode = $shiftData->ICODE;
        $print->qty = $saleData->qty;
        $print->rate = $saleData->rate;
        $print->amt = $saleData->amt;
        $print->p_mode = $saleData->p_mode;
        $print->customer_id = $saleData->customer_id;
        $print->RegNO = $saleData->RegNO;
        $print->bDuplicatePrint = $saleData->bPrint;

        $print->save();
        return response()->json(array(['status' => 1]), 200);
    }

//    public function updated(Request $request)
//    {
//
//
//        $validatedData = $request->validate([
//            'id' => 'required|exists:saledata,id',
//            'Payment_method' => 'nullable',
//            'reg_no' => [
//
//                function ($attribute, $value1, $fail) {
//                    $vehicle = Vehicles::where('RegNO', $value1)
//                        ->where('VehBlocked', 1)
//                        ->exists();
//                    if ($vehicle) {
//                        $fail('The vehicle is blocked.');
//                    }
//                },
//            ],
//            'vid' => 'nullable|integer',
//
//            'save_type' => 'nullable|string',
//            'un_reg_no' => 'nullable|string',
//            'customers' => [
//                function ($attribute, $value, $fail) {
//                    $customer = Customer::find($value);
//                    if ($customer && $customer->CustomerBlocked == 1) {
//                        $fail('The customer is blocked.');
//                    }
//                },
//            ],
//        ]);
//
//        $customer = Customer::find($request["customers"]);
//        $saleData = saledata::find($request->id);
//        $Payment_method = $request['Payment_method'];
//
//        if ($Payment_method == '2') {
//            // dd($Payment_method);
//            if ($customer->CreditLimit <= $customer->LimitUsed) {
//                if ($request->ajax()) {
//                    return response()->json(['success' => false, 'message' => 'Credit limit exceeded. Cannot proceed with the sale.'], 401);
//                } else {
//                    return redirect()->back()->withErrors(['Credit limit exceeded. Cannot proceed with the sale.']);
//                }
//            }
//            // Calculate the new amount after adding the sale amount
//            $newAmount = $customer->LimitUsed + $saleData->amt / 100;
//
//            // Check if the new amount exceeds the credit limit
//            if ($newAmount >= $customer->CreditLimit) {
//                if ($request->ajax()) {
//                    return response()->json(['success' => false, 'message' => 'Credit limit exceeded. Cannot proceed with the sale.'], 401);
//                } else {
//                    return redirect()->back()->withErrors(['Credit limit exceeded. Cannot proceed with the sale.']);
//                }
//            }
//
//            // Update the customer's LimitUsed to include the new sale amount
//            $customer->LimitUsed = $newAmount;
//
//            // Save the updated customer record
//            $customer->save();
//         //cart of accounts wala agr credit paymetn es table hm customer append krna hai
//            \App\Models\Alert::updateOrCreate(['key' => 'credit_customer_sale'], ['value' => '1']);
//        }
//
//
//
//
//
//        if (is_null($saleData)) {
////            dd('1233');
//            return redirect('/');
//        } else {
//
//            if ($request['save_type'] == 'onlyPrint') {
//                $print = new Data2Print();
//                $print->datasale_id = $saleData->id;
//                $print->pdate = $saleData->pdate;
//
//                $print->pos_id = $saleData->pos_id;
//                $print->ForeCourt_NzNo = $saleData->ForeCourt_NzNo;
//                $print->icode = $saleData->icode;
//                $print->qty = $saleData->qty;
//                $print->rate = $saleData->rate;
//                $print->amt = $saleData->amt;
//                $print->p_mode = $saleData->p_mode;
//                $print->customer_id = $saleData->customer_id;
//                $print->RegNO = $saleData->RegNO??0;
//                $print->bDuplicatePrint = $saleData->bPrint;
//                $print->save();
//                \Log::debug($print);
//                return redirect()->back()->with('message', 'print sent')->with('print_data', $print);
//            }
//            if ($saleData->bIsUpdated) {
//
//                if ($request->ajax()) {
//                    return response()->json(['error' => 'Sale data has been updated. Access denied.'], 403);
//                } else {
//                    return redirect()->back()->withErrors(['Sale data has been updated. Access denied.']);
//                }
//            }
//            $pos = $saleData->pos_id;
//
//            // Log the last query
//            // Log::info('Last executed query:', $lastQuery);
//
//            $saleData->p_mode = $request["Payment_method"];
//            $saleData->vid = $request["vid"] ?? null;
//            if ($request->has('vid')) {
//                $vehicle = Vehicles::find($request->input('vid'));
//                if ($vehicle) {
//                    $unRegNo = $vehicle->RegNo;
//                    $saleData->RegNO = $vehicle->RegNO;
//
//                    $request->merge(['un_reg_no' => $unRegNo]);
//                }
//            } else {
//                if (!is_null($request->reg_no)) {
//                    $saleData->RegNO = $request["reg_no"] ?? null;
//                } else {
//                    $saleData->RegNO = $request["un_reg_no"] ?? null;
//                }
//            }
//
//
//            $saleData->customer_id = $request["customers"] ?? 1;
//            $saleData->bIsUpdated = 1;
//            $saleData->user_id = Auth::user()->id;
//
//            $saleData->save();
//            // DB::enableQueryLog();
//
//            DB::update("UPDATE shift_n$pos AS s
//        JOIN saledata AS sd ON s.tid = sd.id
//        SET
//            s.tdate = sd.tdate,
//            s.pos_id = sd.pos_id,
//            s.qty = sd.qty,
//            s.rate = sd.rate,
//            s.amt = sd.amt,
//            s.p_mode = sd.p_mode,
//            s.customer_id = sd.customer_id,
//            s.RegNO = sd.RegNO,
//            s.bPrint = sd.bPrint,
//            s.user_id = sd.user_id
//        WHERE sd.id=?;", [$saleData->id]);
//            // $lastQuery = DB::getQueryLog();
//            // $lastQuery = end($lastQuery); // Get the last query from the log
//            // dd($lastQuery);
//            if ($request['save_type'] == 'print') {
//
//                $print = new Data2Print();
//                $print->datasale_id = $saleData->id;
//                $print->pdate = $saleData->pdate;
//                $print->pos_id = $saleData->pos_id;
//                $print->ForeCourt_NzNo = $saleData->ForeCourt_NzNo;
//                $print->icode = $saleData->icode;
//                $print->qty = $saleData->qty;
//                $print->rate = $saleData->rate;
//                $print->amt = $saleData->amt;
//                $print->p_mode = $saleData->p_mode;
//                $print->customer_id = $saleData->customer_id;
//                $print->RegNO = $saleData->RegNO??0;
//                $print->bDuplicatePrint = $saleData->bPrint;
//                $print->user_id = Auth::user()->id;
//
//                $print->save();
//
//            }
////            dd($saleData,$saleData->erp_id != 0 && $saleData->erp_id != null);
//            if ($saleData->erp_id != 0 && $saleData->erp_id != null) {
////
//                $print = new SaledataUpdate();
//                $print->sale_id = $saleData->id;
//                $print->pos_id = $saleData->pos_id;
//                $print->pdate = $saleData->pdate;
//                $print->tdate = $saleData->tdate;
//                $print->ForeCourt_NzNo = $saleData->ForeCourt_NzNo;
//                $print->icode = $saleData->icode;
//                $print->qty = $saleData->qty;
//                $print->rate = $saleData->rate;
//                $print->amt = $saleData->amt;
//                $print->p_mode = $saleData->p_mode;
//                $print->customer_id = $saleData->customer_id;
//                $print->RegNO = !empty($saleData->RegNO) ? $saleData->RegNO : 0;
//                $print->vid   = !empty($saleData->vid)   ? $saleData->vid   : 0;
//                $print->bPrint = $saleData->bPrint;
//                $print->erp_id = $saleData->erp_id;
//                //coa pending sir umar
////                $print->coa_id = $saleData->coa_id;
//
//                $saleData->user_id = Auth::user()->id;
//
//                $print->save();
//            } else {
//                // TODO: update in salesend
//            }
//
//            return redirect()->back()->with('message', 'updated')->with('print_data', $print);
//        }
//    }

    public function updated(Request $request)
    {
        try {
//            dd($request->all());
            $validatedData = $request->validate([
                'id' => 'required|exists:saledata,id',
                'Payment_method' => 'nullable',
                'reg_no' => [
                    function ($attribute, $value1, $fail) {
                        $vehicle = Vehicles::where('RegNO', $value1)
                            ->where('VehBlocked', 1)
                            ->exists();
                        if ($vehicle) {
                            $fail('The vehicle is blocked.');
                        }
                    },
                ],
                'vid' => 'nullable|integer',
                'save_type' => 'nullable|string',
                'un_reg_no' => 'nullable|string',
                'customers' => [
                    function ($attribute, $value, $fail) {
                        $customer = Customer::find($value);
                        if ($customer && $customer->CustomerBlocked == 1) {
                            $fail('The customer is blocked.');
                        }
                    },
                ],
            ]);

            $customer = Customer::find($request["customers"]);
            $saleData = saledata::find($request->id);
            $Payment_method = $request['Payment_method'];
//            dd($saleData,$Payment_method);

            // --- Credit limit check ---
            if ($Payment_method == '2') {
                if ($customer->CreditLimit <= $customer->LimitUsed) {
                    return $request->ajax()
                        ? response()->json(['success' => false, 'message' => 'Credit limit exceeded. Cannot proceed with the sale.'], 401)
                        : redirect()->back()->withErrors(['Credit limit exceeded. Cannot proceed with the sale.']);
                }

                $newAmount = $customer->LimitUsed + $saleData->amt / 100;
                if ($newAmount >= $customer->CreditLimit) {
                    return $request->ajax()
                        ? response()->json(['success' => false, 'message' => 'Credit limit exceeded. Cannot proceed with the sale.'], 401)
                        : redirect()->back()->withErrors(['Credit limit exceeded. Cannot proceed with the sale.']);
                }

                $customer->LimitUsed = $newAmount;
                $customer->save();

                // alert update
                \App\Models\Alert::updateOrCreate(
                    ['key' => 'credit_customer_sale'],
                    ['value' => '1']
                );
            }

            if (is_null($saleData)) {
                return redirect('/');
            }

            // --- Only Print Save ---
            if ($request['save_type'] == 'onlyPrint') {
                $print = new Data2Print();
                $print->datasale_id = $saleData->id;
                $print->pdate = $saleData->pdate;
                $print->pos_id = $saleData->pos_id;
                $print->FC_NZNo = $saleData->FC_NZNo;
                $print->icode = $saleData->icode;
                $print->qty = $saleData->qty;
                $print->rate = $saleData->rate;
                $print->amt = $saleData->amt;
                $print->p_mode = $saleData->p_mode;
                $print->customer_id = $saleData->customer_id;
                $print->RegNO = !empty($saleData->RegNO) ? $saleData->RegNO : 0;
                $print->bDuplicatePrint = $saleData->bPrint;
                $print->save();
                $saleData->update([
                    'bPrint'=>1
                ]);
                \Log::debug('OnlyPrint data saved:', $print->toArray());
                return redirect()->back()->with('message', 'print sent')->with('print_data', $print);
            }

            // --- Check if already updated ---
            if ($saleData->bIsUpdated) {
                return $request->ajax()
                    ? response()->json(['error' => 'Sale data has been updated. Access denied.'], 403)
                    : redirect()->back()->withErrors(['Sale data has been updated. Access denied.']);
            }

            // --- Update SaleData ---
            $pos = $saleData->pos_id;
            $saleData->p_mode = $request["Payment_method"];
            $saleData->vid = $request["vid"] ?? null;

            if ($request->has('vid')) {
                $vehicle = Vehicles::find($request->input('vid'));
                if ($vehicle) {
                    $unRegNo = $vehicle->RegNo;
                    $saleData->RegNO = $vehicle->RegNO;
                    $request->merge(['un_reg_no' => $unRegNo]);
                }
            } else {
                $saleData->RegNO = $request->reg_no ?? $request->un_reg_no ?? null;
            }

            $saleData->customer_id = $request["customers"] ?? 1;
            $saleData->bIsUpdated = 1;
            $saleData->user_id = Auth::user()->id;
            $saleData->save();

            // --- Update shift table ---
            DB::update("UPDATE shift_n$pos AS s
            JOIN saledata AS sd ON s.tid = sd.id
            SET
                s.tdate = sd.tdate,
                s.pos_id = sd.pos_id,
                s.qty = sd.qty,
                s.rate = sd.rate,
                s.amt = sd.amt,
                s.p_mode = sd.p_mode,
                s.customer_id = sd.customer_id,
                s.RegNO = IFNULL(sd.RegNO, 0),
                s.bPrint = sd.bPrint,
                s.user_id = sd.user_id
            WHERE sd.id=?;", [$saleData->id]);

            // --- Print Save ---
            if ($request['save_type'] == 'print') {
                $pModeErp = DB::table('paymentmethod')
                    ->where('id', $Payment_method)
                    ->value('erp_id');
                $print = new Data2Print();
                $print->datasale_id = $saleData->id;
                $print->pdate = $saleData->pdate;
                $print->pos_id = $saleData->pos_id;
                $print->FC_NZNo = $saleData->FC_NZNo;
                $print->icode = $saleData->icode;
                $print->qty = $saleData->qty;
                $print->rate = $saleData->rate;
                $print->amt = $saleData->amt;
//                $print->p_mode = $saleData->p_mode;
                $print->p_mode = $pModeErp;
                $print->customer_id = $saleData->customer_id;
                $print->RegNO = !empty($saleData->RegNO) ? $saleData->RegNO : 0;
                $print->bDuplicatePrint = $saleData->bPrint;
                $print->user_id = Auth::user()->id??0;
                $print->save();
                $saleData->update([
                    'bPrint'=>1
                ]);
                \Log::info('Print data saved:', $print->toArray());
            }
//dd($saleData->erp_id);
            // --- ERP check ---
            if ($saleData->erp_id != 0 && $saleData->erp_id != null) {
                $pModeErp = DB::table('paymentmethod')
                    ->where('id', $Payment_method)
                    ->value('erp_id');

                $print = new SaledataUpdate();
                $print->sale_id = $saleData->id;
                $print->pos_id = $saleData->pos_id;
                $print->pdate = $saleData->pdate;
                $print->tdate = $saleData->tdate;
                $print->FC_NZNo = $saleData->FC_NZNo;
                $print->icode = $saleData->icode;
                $print->qty = $saleData->qty;
                $print->rate = $saleData->rate;
                $print->amt = $saleData->amt;
                $print->p_mode = $pModeErp;
//                $print->p_mode = $saleData->p_mode;
                $print->customer_id = $saleData->customer_id;
                $print->RegNO = !empty($saleData->RegNO) ? $saleData->RegNO : 0;
                $print->vid   = !empty($saleData->vid)   ? $saleData->vid   : 0;
                $print->bPrint = $saleData->bPrint;
                $print->erp_id = $saleData->erp_id;
                $print->user_id = !empty(Auth::user()->id)?Auth::user()->id:0;
                $print->updated_at = now();
                $print->save();
                $saleData->update([
                    'bPrint'=>1
                ]);
                $newPaymentId = DB::table('paymentmethod')
                    ->where('id', $Payment_method)
                    ->value('id');
//                dd($request->all());
               $saledataP=DB::table('saledata')->where('id',$saleData->id)->select('qty','amt','shift_id')->first();
//                $shift = Shift::where('last_sale_id', $saleData->id)->first();


                $newQty = $saledataP->qty;
//                $rate = $saleData->rate;
                $defaultPaymentId = 1;

// rows fetch
                $defaultRow = ShiftPaymentWiseSale::where('shift_id',$saledataP->shift_id)
                    ->where('paymentmethod_id',$defaultPaymentId)
                    ->first();

//                $newPaymentRow = ShiftPaymentWiseSale::
//                     where('shift_id',$saledataP->shift_id)
//                    ->where('paymentmethod_id',$newPaymentId)
//                    ->update([
//                        'total_qty' => total_qty-$saleData->qty,
//                    ]);
//dd($saledataP);
                DB::update("
    UPDATE shift_payment_wise_sale
    SET total_sale = total_sale - ?,
        total_qty  = total_qty - ?
    WHERE shift_id = ?
      AND paymentmethod_id = 1
", [
                    $saledataP->amt/100,
                    $saledataP->qty/100,
                    $saledataP->shift_id,


                ]);
                DB::update(
                    "UPDATE shift_payment_wise_sale
     SET total_sale = total_sale + ?,
         total_qty  = total_qty + ?
     WHERE shift_id = ?
       AND paymentmethod_id = ?",
                    [
                        $saledataP->amt/100,
                        $saledataP->qty/100,
                        $saledataP->shift_id,
                        $newPaymentId   // jo payment method pass karna hai
                    ]
                );

//                DB::update("shift_payment_wise_sale")->update shift_payment_wise_sale set total_sale=total_sale-$saleData->amt,total_qty=total_qty-$saleData->qty where shift_id=$saleData->shift_id and paymentmethod_id=1
//                update shift_payment_wise_sale set total_sale=total_sale-$saleData->amt,total_qty=total_qty-$saleData->qty where shift_id=$saleData->shift_id and paymentmethod_id=1;
//update shift_payment_wise_sale set total_sale=total_sale+$saleData->amt,total_qty=total_qty+$saleData->qty where shift_id=$saleData->shift_id and paymentmethod_id=paymnetmethod_id;


//                $shiftPayment= ShiftPaymentWiseSale::where('id', $shiftSaleId->id)->where('paymenetMethod_id',$pMode)->get();
//                \Log::info('ERP SaleDataUpdate saved:', $print->toArray());
            } else {
                \Log::warning("SaleData id={$saleData->id} skipped ERP update.");
            }

            return redirect()->back()->with('message', 'updated')->with('print_data', $print ?? null);

        } catch (\Exception $e) {
//            \Log::error("SaleData update error: " . $e->getMessage(), [
//                'trace' => $e->getTraceAsString(),
//                'request' => $request->all()
//            ]);


            return $request->ajax()
                ? response()->json(['error' => 'Something went wrong.'], 500)
                : redirect()->back()->withErrors(['Something went wrong. Please check logs.']);
        }
    }

    public function shift()
    {
        return view('Shift');
    }
    public function card_details(Request $request)
    {
        $saleData = saledata::all();
        $Products = product::all();
        $filters = SaleData::where('p_mode', $request->userid)->get();
        $logoProfile = PaymentMethod::where('id', $request->userid)->value('logo_profile');
        $Payments = PaymentMethod::where('id', $request->userid)->value('Des');
        $data = compact('filters', 'logoProfile', 'Sales', 'Products', 'Payments');
        return view('CardDetails')->with($data);
    }
    public function getCustomers()
    {
        $customers = Customer::all()->reject(function ($customer) {
            return $customer->id == 1;
        });
        return response()->json($customers);
    }

    public function getVehicles(Request $request)
    {
        $vehicles = Vehicles::where('customer_id', $request->customer_id)->get();
        return response()->json($vehicles);
    }
}
