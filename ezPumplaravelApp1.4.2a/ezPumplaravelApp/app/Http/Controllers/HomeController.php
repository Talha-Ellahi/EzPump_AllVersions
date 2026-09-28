<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Rateslog;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }
    public function customerLimit(Request $request)
    {
        $customers = DB::select('select * from customer where id!=1');
        return view('customerLimit', compact('customers'));
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function rate()
    {
        $Products = product::all();
        $data = compact('Products');
        return view('Rates')->with($data);
    }

    public function RateUpdate(Request $request)
    {
        $validatedData = $request->validate([
            'Code' => 'required',
            'Latest_Price' => 'required|numeric|gt:0', // gt:0 ensures the value is greater than 0
        ]);
        $value = $request->Code;

        // Find the product by Code (assuming Code is the identifier)
        $product = Product::find($value);

        if ($product) {
            // Update SRATE attribute of the product
            \App\Models\Alert::updateOrCreate(['key' => 'price_update_alert'], ['value' => '1']);
            $Rates = new Rateslog;
            $Rates->ICODE = $product->ICODE;
            $Rates->ITMNAME = $product->ITMNAME;
            $Rates->Previous_Rate = $product->SRATE;
            $Rates->New_Rate = $request->Latest_Price;
            $Rates->Save();
//            dd( $product);
            // 🔥 Insert into rate_send (new log table)
            DB::table('rate_send')->insert([
                'ICODE'         => $product->ICODE,
                'ITMNAME'       => $product->ITMNAME,
                'Previous_Rate' => $product->SRATE,
                'New_Rate'      => $request->Latest_Price * 100, // same scaling logic
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);

            $product->RT = 1;
            $product->SRATE = $request->Latest_Price * 100; // Assuming Latest_Price is the field to update
            $product->save();
            // $shift_id = DB::select('select shift.id from shift left join PUMP on shift.pump_id=PUMP.id and PUMP.ICODE=?', [$value]);
            // DB::update('update shift set changed_fuel_balance=1, new_rate=?, is_changed=1 where shift.id=?', [, $shift_id]);


            // Step 1: Gather the IDs with the LIMIT clause
            DB::enableQueryLog();
            $ids = DB::table('shift')
//                ->join('PUMPS', 'shift.pump_id', '=', 'PUMPS.id')
                ->join('PUMPS', 'shift.pump_id', '=', 'PUMPS.FC_NZNo')
                ->where('PUMPS.ICODE', '=', $value)
                ->select('shift.id')

                ->pluck('id')
                ->toArray();

            // Step 2: Perform the update query if IDs are found
            if (!empty($ids)) {

                DB::table('shift')
                    ->whereIn('id', $ids)
                    ->update([
                        'changed_fuel_balance' => DB::raw('closing_fuel'),
                        'rate_change_qty' => DB::raw('total_qty'),
                        'rate_change_amount'=> DB::raw('total_amount'),
                        'new_rate' => $request->Latest_Price * 100,
                        'is_changed' => 1,
                    ]);
            }
            // Step 3: Log the query
            $queries = DB::getQueryLog();
            // dd($queries);
            // Redirect to 'Rates' view after successful update
            // \App\Models\Alert::updateOrCreate(['key' => 'price_update_alert'], ['value' => '1']);
            return redirect()->route('rate'); // Adjust 'rates' to your actual route name

        } else {
            // Handle case where product with Code $value was not found
            return response()->json(['message' => 'Product not found'], 404);
        }
    }
    public function Ratelogs()
    {
        $Logs = Rateslog::orderBy('ID', 'desc')->get();
        $data = compact('Logs');

        return view('Ratelog')->with($data);
    }
    public function PrintShiftStats(Request $request)
    {
        $ids = $request->query('ids');
        $idArray = explode(',', $ids);

        $shifts = DB::select('SELECT * FROM shift_log WHERE id IN (' . implode(',', array_map('intval', $idArray)) . ')');

        foreach ($shifts as $shift) {
            $shift->data = json_decode($shift->data);
        }
        $shifts = collect($shifts);
        $shifts = $shifts->sortBy(function($shift) {
            return $shift->data->shift->pump_id;
        });
        // Fetch all records from the PRODUCT table
//        $products = DB::select('select PUMPS.id, PRODUCT.ICODE, PRODUCT.ITMNAME from PUMPS left join PRODUCT on PRODUCT.ICODE=PUMPS.ICODE;');
        $products = DB::select('select PUMPS.id, PUMPS.FC_NZNo, PRODUCT.ICODE, PRODUCT.ITMNAME from PUMPS left join PRODUCT on PRODUCT.ICODE=PUMPS.ICODE;');

        // Create a dictionary where ICODE is the key and ITMNAME is the value
        $productDict = [];

        foreach ($products as $product) {
//            $productDict[$product->id] = $product->ITMNAME;
            $productDict[$product->FC_NZNo] = $product->ITMNAME;

        }

        $paymentmethods = DB::select('SELECT * FROM `paymentmethod`');
        // Create a dictionary where ICODE is the key and ITMNAME is the value
        $paymentmethodsDict = ["" => "None"];

        foreach ($paymentmethods as $paymentmethod) {
            $paymentmethodsDict[$paymentmethod->id] = $paymentmethod->Des;
        }

                $settings = \App\Models\Settings::getSettingsArray();
        $settings = $settings ?? [];
        $totalFuelICode1 = DB::table('shift_log')
            ->whereIn('id', $idArray)
            ->whereRaw("JSON_EXTRACT(data, '$.shift.icode') = 1")
            ->get()
            ->sum(function($row) {
                $shiftData = json_decode($row->data, true);
                return $shiftData['shift']['closing_fuel'] - $shiftData['shift']['opening_fuel'];
            });

        return view('reports.print_shift', compact('shifts', 'productDict', 'paymentmethodsDict', 'settings','totalFuelICode1'));
    }


}
