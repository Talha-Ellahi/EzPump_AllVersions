<?php

namespace App\Http\Controllers;

use App\Models\Shift;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use DateTime;
use DateTimeZone;
use App\Models\saledata;
use Carbon\Carbon;
use App\Models\paymentmethod;
use App\Models\Vehicles;
use App\Models\Product;
use Illuminate\Http\JsonResponse;

use App\Models\Customer;
use App\Models\FuelStatus;
use App\Models\Pumps;

class ApiController extends Controller
{
    public function shift(Request $request)
    {
        $shifts = DB::select('SELECT * FROM shift');
        return response()->json($shifts);
    }
    //
    public function salesData(Request $request)
    {
        // DB::enableQueryLog();

        // Apply filters if they are present
        // Build the query
        $query = saledata::query();
        $autoUpdate = true;

        // Apply filters if they are present
        if ($request->filled('nozzles')) {
            $nozzles = explode(',', $request->query('nozzles'));
            $query->whereIn('FC_NZNo', $nozzles);
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
        // Execute the query
        $Sales = $query->orderBy('id', 'desc')
            ->with(['customer', 'user', 'product', 'paymentMethod'])
            ->get();

        // $queries = DB::getQueryLog();
        // $lastQuery = end($queries);

        // Log the last query or do something with it
        // For demonstration, we're just dumping it
        // dd($queries);
        // dd($Sales);
        // $Products = product::all();
        // $Payments = paymentmethod::all();
        // $Customers = Customer::all();
        // $data = compact('Sales', 'Products', 'Payments', 'Customers', 'autoUpdate');
        return response()->json($Sales);

    }
    /**
     * Get all products
     *
     * @return JsonResponse
     */
    public function getAllProducts(): JsonResponse
    {
        $products = Product::all();
        return response()->json($products);
    }

    /**
     * Get all payment methods
     *
     * @return JsonResponse
     */
    public function getAllPaymentMethods(): JsonResponse
    {
        $payments = PaymentMethod::all();
        return response()->json($payments);
    }

    /**
     * Get all customers
     *
     * @return JsonResponse
     */
    public function getAllCustomers(Request $request): JsonResponse
    {
        $query = Customer::query();

        if ($request->filled('search')) {
            $search = $request->query('search');
            $query->where('Des', 'like', '%' . $search . '%');
        }

        if ($request->query('all') === 'true') {
            $customers = $query->get();
        } else {
            $customers = $query->limit(10)->get();
        }

        return response()->json($customers);
    }
    public function getAllNozzles(): JsonResponse
    {
        return response()->json(DB::select('SELECT * FROM `PUMPS`'));
    }

    public function pumps(Request $request)
    {
        return Pumps::with('shift')->orderBy('FC_NZNo')->get();
    }

//    public function pumpstates(Request $request)
//    {
//
//        return DB::select('SELECT PUMP_STATE.*, shift.total_qty as shift_qty, shift.rate as shift_rate, shift.total_amount FROM PUMP_STATE left join shift on shift.pump_id=PUMP_STATE.PUMPID and shift.status=1;');
//    }
    public function pumpstates(Request $request)
    {
        return DB::select("
        SELECT
            ps.*,
            p.FC_NZNo,
            p.display_name,
            p.ICODE,
            shift.total_qty AS shift_qty,
            shift.rate AS shift_rate,
            shift.total_amount
        FROM PUMP_STATE ps
        LEFT JOIN PUMPS p
            ON p.FC_NZNo = ps.PUMPID
        LEFT JOIN shift
            ON shift.pump_id = p.FC_NZNo
           AND shift.status = 1
    ");
    }

    public function stats(Request $request)
    {
        // // Create a DateTime object with the current date and time
        // $date = new DateTime('2024-07-11', new DateTimeZone('UTC'));

        // // Adjust the timezone to +5
        // $date->modify('+5 hours');
        $shifts = DB::select("SELECT * FROM shift LIMIT 1");

        if (!empty($shifts)) {
            $shift = $shifts[0];  // Access the first element
        }
        if ($shift) {
            $formattedStartDate = Carbon::parse($shift->start_date)->format('Y-m-d H:i:s'); // Adjust the format as needed
        } else {
            $formattedStartDate = 'No shift found';
        }
//dd($shifts);
        // // Format the date to 'Y-m-d' for the SQL query
        // $formattedDate = $date->format('Y-m-d');
// Assuming you have a table for payments like PAYMENT_METHODS with a column 'payment_method'

        // Fetch product-wise stats from shift_payment_wise_sale
        $productWiseSales = DB::select("
        SELECT icode, icode as ICODE, icode as product_code, sum(sale_count) as sale_count, sum(total_amount) as total_amount, sum(total_qty) as total_qty FROM `shift` group by icode;", []);
        $totalSales = DB::select("SELECT SUM(total_amount) as total_amount FROM shift", []);
        $totalSales = $totalSales[0]->total_amount;
        // $stats  = [];
// Fetch all products (assuming PRODUCT table exists for product details)
        $products = DB::select("SELECT * FROM PRODUCT");

        // Fetch payment method-wise sales from shift_payment_wise_sale and paymentmethod
        $paymentMethodSales = DB::select("
    SELECT
        PM.Des AS payment_method,
        SUM(SPS.total_sale)/100 AS total_amount
    FROM shift_payment_wise_sale SPS
    LEFT JOIN paymentmethod PM ON SPS.paymentmethod_id = PM.id
    WHERE PM.id != 1  -- Exclude payment method with id=1

    GROUP BY PM.Des
", []);
// Calculate the total amount from non-cash methods
$totalNonCash = 0;
foreach ($paymentMethodSales as $method) {
    $totalNonCash += $method->total_amount;
}

// Compute the cash total as total sales minus non-cash sales
$cashSale = round(($totalSales / 100) - $totalNonCash, 2);
        // add payment method with id=1 to paymentMethodSales
        $paymentMethodSales[] = (object) ['payment_method' => 'Cash', 'total_amount' => $cashSale];

        // // Calculate total sales from shift table
        // $totalShiftSales = DB::selectOne("
        //     SELECT SUM(total_amount) AS total_amount FROM shift
        // ")->total_amount;

        // // Calculate sum of other payment methods
        // $otherPaymentMethodsTotal = collect($paymentMethodSales)->sum('total_amount');

        // // Calculate Cash in Hand
        // $cashInHand = $totalShiftSales - $otherPaymentMethodsTotal;

        // // Fetch payment method-wise sales for id=1 and add to paymentMethodSales
        // $paymentMethodSalesForId1 = DB::select("
        //     SELECT
        //         SUM(total_sale)/100 AS total_amount
        //     FROM shift_payment_wise_sale
        //     WHERE paymentmethod_id = 1
        // ")[0]->total_amount;

        // // Add Cash in Hand to payment method sales
        // $paymentMethodSales[] = [
        //     'payment_method' => 'Cash in Hand',
        //     'total_amount' => $paymentMethodSalesForId1,
        // ];

        // Format data for pie charts
        $productPieChart = array_map(function ($item) {
            return [
                'label' => $item->product_code,
                'value' => $item->total_qty,
            ];
        }, $productWiseSales);

        $paymentPieChart = array_map(function ($item) {
            return [
                'label' => $item->payment_method,
                'value' => $item->total_amount,
            ];
        },  $paymentMethodSales);
        $shifts = DB::select("SELECT * from shift");

        // Response to the frontend
        return compact('productWiseSales', 'products', 'productPieChart', 'paymentPieChart', 'shifts');


    }
    public function employees(Request $request)
    {
        return DB::select('SELECT * from employee');
    }
    public function customerLimit(Request $request, $id)
    {
        return DB::selectOne("SELECT * from customers where id=?", [$id]);
    }
    public function customerPrintLimit(Request $request, $id)
    {
        $customer = DB::selectOne("SELECT * from customers where id=?", [$id]);


    }

//    public function shiftSaleData(Request $request)
//    {
//        $validatedData = $request->validate(
//            [
//                'shift_id' => 'required|integer',
//            ]
//        );
//        $shift_id = $validatedData['shift_id'];
//        $shift=Shift::where('pump_id',$shift_id)->first();
//        $rate=$shift->rate;
////        if ($shift->is_changed==1) {
////            $new_rate=$shift->new_rate;
////            $rate=$shift->rate;
////
////
////        }elseif($shift->is_changed==0) {
////             $rate=$shift->rate;
////        }
//        $tableName = "shift_n$shift_id";
//        return DB::table($tableName)
//            ->leftJoin('customer', 'customer.id', '=', "$tableName.customer_id")
//            ->leftJoin('paymentmethod', 'paymentmethod.id', '=', "$tableName.p_mode")
//            ->select("$tableName.*", 'customer.Des as customer', 'paymentmethod.Des as pMethod', DB::raw("$shift_id as shift_table_id"))
//
//            ->where("$tableName.rate",'=',$rate)
//            ->orderBy("$tableName.tdate", 'desc')
//            ->paginate(15);
//    }
    public function shiftSaleData(Request $request)
    {
        $validatedData = $request->validate([
            'shift_id' => 'required|integer',
        ]);

        $shift_id = $validatedData['shift_id'];
        $shift = Shift::where('pump_id', $shift_id)->first();

        $currentRate = $shift->is_changed ? $shift->new_rate : $shift->rate;

        $tableName = "shift_n$shift_id";

        return DB::table($tableName)
            ->leftJoin('customer', 'customer.id', '=', "$tableName.customer_id")
            ->leftJoin('paymentmethod', 'paymentmethod.id', '=', "$tableName.p_mode")
            ->select(
                "$tableName.*",
                'customer.Des as customer',
                'paymentmethod.Des as pMethod',
                DB::raw("$shift_id as shift_table_id"),
                DB::raw("$currentRate as effective_rate")
            )
            ->orderBy("$tableName.tdate", 'desc')
            ->paginate(10);
    }


    public function getCustomers()
    {
        $customers = DB::select('SELECT * FROM customer where id!=1');
        return response()->json($customers);
    }



    public function getVehicles(Request $request)
    {
        $customerId = $request->input('customer_id');
        $vehicles = DB::select('SELECT * FROM customer_vehicle_data WHERE customer_id = ?', [$customerId]);
        return response()->json($vehicles);
    }
    public function getPumps(Request $request)
    {
        $icode = $request->input('icode');
        $pumps = DB::select('SELECT * FROM PUMPS WHERE ICODE = ?', [$icode]);
        // $product = DB::selectOne('SELECT * from PRODUCT where ICODE=?', [$icode]);
        return response()->json($pumps);
    }
    public function getProductName(Request $request)
    {
        $icode = $request->input('icode');
        $product = DB::select('SELECT ITMNAME FROM PRODUCT WHERE ICODE = ?', [$icode]);
        return response()->json($product[0]);
    }
    public function addCreditSlip(Request $request)
    {
        $vehicleId = $request->input('vehicle_id');
        $qty = $request->input('qty');
        $pumpId = $request->input('pump_id');

        $vehicle = DB::selectOne('SELECT * FROM customer_vehicle_data WHERE id = ?', [$vehicleId]);
        // dd($vehicle);
        $product = DB::selectOne('SELECT * FROM PRODUCT WHERE ICODE = ?', [$vehicle->icode]);
        $pump = DB::selectOne('SELECT * FROM PUMPS WHERE id = ?', [$pumpId]);

        $amt = $qty * $product->SRATE;

        $slip=DB::insert('INSERT INTO credit_slip (pdate, pos_id, FC_NZNo, icode, qty, rate, amt, customer_id, RegNO) VALUES (NOW(), ?, ?, ?, ?, ?, ?, ?, ?)', [
            $pump->POS_ID,
            $pump->FC_NZNo,
            $vehicle->icode,
            $qty,
            $product->SRATE,
            $amt,
            $vehicle->customer_id,
            $vehicle->RegNO
        ]);


        return response()->json(['message' => 'Credit Slip Added Successfully']);
    }
    public function updateTime(Request $request)
    {

        // Get the time and offset from the request
        $userTime = Carbon::parse($request->input('user_time'));  // Parse the user's local time
        $userOffset = $request->input('user_offset');  // Get the offset in minutes

        // Adjust the user's time based on their offset
        $userTimeAdjusted = $userTime->copy()->addMinutes($userOffset);  // Add the offset to get the correct local time

        // // Get the system's last seen time from settings table
        // $systemTime = Carbon::parse(DB::table('settings')
        //     ->where('key', 'last_date_seen')
        //     ->value('value'));
        // // Calculate the difference in hours or days
        // $differenceInDays = $systemTime->diffInDays($userTimeAdjusted);
        // if ($differenceInDays > 1) {
        // If the time difference is more than a day, take action
        // Example: log the difference, notify the user, or adjust times as needed
        DB::table('settings')
            ->where('key', 'last_date_seen')
            ->update([
                'value' => $userTimeAdjusted->toDateTimeString(),  // Convert Carbon object to string
                'updated_at' => now()  // Update the updated_at field
            ]);


        // }

        return response()->json();
    }

    public function getFuelStatus()
    {
        $fuelStatus = FuelStatus::where('tdate', '>', Carbon\Carbon::now()->subMonth())->get();
        return response()->json($fuelStatus);
    }
    public function getAlerts(Request $request)
    {
        // Mock data for alerts
        $alerts = [
            ['id' => 1, 'message' => 'Low fuel level in Tank 1', 'severity' => 'warning', 'type' => 'warning', 'created_at' => '2023-04-01 10:00:00'],
            ['id' => 2, 'message' => 'Pump 3 is offline', 'severity' => 'error', 'type' => 'error', 'created_at' => '2023-04-02 11:00:00'],
            ['id' => 3, 'message' => 'Tank 2 needs maintenance', 'severity' => 'info', 'type' => 'info', 'created_at' => '2023-04-03 12:00:00'],
            ['id' => 4, 'message' => 'Shift change completed successfully', 'severity' => 'success', 'type' => 'success', 'created_at' => '2023-04-04 13:00:00'],
            ['id' => 1, 'message' => 'Low fuel level in Tank 1', 'severity' => 'warning', 'type' => 'warning', 'created_at' => '2023-04-01 10:00:00'],
            ['id' => 2, 'message' => 'Pump 3 is offline', 'severity' => 'error', 'type' => 'error', 'created_at' => '2023-04-02 11:00:00'],
            ['id' => 3, 'message' => 'Tank 2 needs maintenance', 'severity' => 'info', 'type' => 'info', 'created_at' => '2023-04-03 12:00:00'],
            ['id' => 4, 'message' => 'Shift change completed successfully', 'severity' => 'success', 'type' => 'success', 'created_at' => '2023-04-04 13:00:00'],
            ['id' => 1, 'message' => 'Low fuel level in Tank 1', 'severity' => 'warning', 'type' => 'warning', 'created_at' => '2023-04-01 10:00:00'],
            ['id' => 2, 'message' => 'Pump 3 is offline', 'severity' => 'error', 'type' => 'error', 'created_at' => '2023-04-02 11:00:00'],
            ['id' => 3, 'message' => 'Tank 2 needs maintenance', 'severity' => 'info', 'type' => 'info', 'created_at' => '2023-04-03 12:00:00'],
            ['id' => 4, 'message' => 'Shift change completed successfully', 'severity' => 'success', 'type' => 'success', 'created_at' => '2023-04-04 13:00:00'],
            ['id' => 1, 'message' => 'Low fuel level in Tank 1', 'severity' => 'warning', 'type' => 'warning', 'created_at' => '2023-04-01 10:00:00'],
            ['id' => 2, 'message' => 'Pump 3 is offline', 'severity' => 'error', 'type' => 'error', 'created_at' => '2023-04-02 11:00:00'],
            ['id' => 3, 'message' => 'Tank 2 needs maintenance', 'severity' => 'info', 'type' => 'info', 'created_at' => '2023-04-03 12:00:00'],
            ['id' => 4, 'message' => 'Shift change completed successfully', 'severity' => 'success', 'type' => 'success', 'created_at' => '2023-04-04 13:00:00']
        ];

        return response()->json($alerts);
    }

    /**
     * Get hourly sales data for histogram
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getHourlySales(Request $request)
    {
        // Get date parameter or use today
        $date = $request->input('date', Carbon::today()->format('Y-m-d'));

        // Query to get hourly sales data
        $hourlySales = DB::select("
            SELECT
                HOUR(tdate) as hour,
                SUM(qty) as total_qty,
                SUM(amt) as total_amount,
                COUNT(*) as transaction_count
            FROM saledata
            WHERE DATE(tdate) = ?
            GROUP BY HOUR(tdate)
            ORDER BY hour ASC
        ", [$date]);

        // Format data for frontend
        $hours = [];
        $quantities = [];
        $amounts = [];
        $transactions = [];

        // Initialize all hours with zero values
        for ($i = 0; $i < 24; $i++) {
            $hours[] = $i;
            $quantities[$i] = 0;
            $amounts[$i] = 0;
            $transactions[$i] = 0;
        }

        // Fill in actual data
        foreach ($hourlySales as $sale) {
            $hour = (int)$sale->hour;
            $quantities[$hour] = $sale->total_qty / 100; // Convert to actual units
            $amounts[$hour] = $sale->total_amount / 100; // Convert to actual currency
            $transactions[$hour] = $sale->transaction_count;
        }

        return response()->json([
            'hours' => $hours,
            'quantities' => array_values($quantities),
            'amounts' => array_values($amounts),
            'transactions' => array_values($transactions),
            'date' => $date
        ]);
    }

    /**
     * Get nozzle-wise sales data for histogram
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getNozzleSales(Request $request)
    {
        // Get date parameter or use today
        $date = $request->input('date', Carbon::today()->format('Y-m-d'));
dd('s');
        // Query to get nozzle-wise sales data
        $nozzleSales = DB::select("
            SELECT
                s.FC_NZNo as nozzle_id,
                p.POS_ID as pump_id,
                p.POS_ID as nozzle_name,
                pr.ITMNAME as product_name,
                SUM(s.qty) as total_qty,
                SUM(s.amt) as total_amount,
                COUNT(*) as transaction_count
            FROM saledata s
            JOIN PUMPS p ON s.FC_NZNo = p.FC_NZNo
            JOIN PRODUCT pr ON s.icode = pr.ICODE
            WHERE DATE(s.tdate) = ?
            GROUP BY s.FC_NZNo, p.POS_ID, pr.ITMNAME
            ORDER BY p.POS_ID ASC
        ", [$date]);

        return response()->json([
            'nozzleSales' => $nozzleSales,
            'date' => $date
        ]);
    }
}
