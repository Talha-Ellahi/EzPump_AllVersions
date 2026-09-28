<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Customer;
use App\Models\paymentmethod;
use App\Models\saledata;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProductSaleController extends Controller
{
    public function getProducts(Request $request)
    {
        $search = $request->get('q');

        $products = Product::where('product_type', 'sellable')
            ->where('CLOSED', 0)
            ->when($search, function ($query, $search) {
                return $query->where('ITMNAME', 'LIKE', "%{$search}%");
            })
            ->select('ICODE as id', 'ITMNAME as text', 'SRATE', 'UOM')
            ->get();

        return response()->json($products);
    }

    public function getCustomers(Request $request)
    {
        $search = $request->get('q');

        $customers = Customer::where('CustomerBlocked', 0)
            ->when($search, function ($query, $search) {
                return $query->where('Des', 'LIKE', "%{$search}%");
            })
            ->select('id', 'Des as text', 'CreditLimit', 'LimitUsed')
            ->get();

        return response()->json($customers);
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:PRODUCT,ICODE',
            'customer_id' => 'required|exists:customer,id',
            'payment_method' => 'required|exists:paymentmethod,id',
            'quantity' => 'required|numeric|min:0.01',
            'rate' => 'required|numeric|min:0.01',
            'amount' => 'required|numeric|min:0.01',
        ]);

        // Check if customer is blocked
        $customer = Customer::find($request->customer_id);
        if ($customer->CustomerBlocked == 1) {
            return response()->json(['success' => false, 'message' => 'Customer is blocked'], 400);
        }

        // Check credit limit for credit sales
        if ($request->payment_method == 2) { // Assuming 2 is credit payment
            $newAmount = $customer->LimitUsed + $request->amount;
            if ($newAmount > $customer->CreditLimit) {
                return response()->json(['success' => false, 'message' => 'Credit limit exceeded'], 400);
            }
        }

        try {
            DB::beginTransaction();

            // Create sale record
            $sale = new saledata();
            $sale->pdate = Carbon::now();
            $sale->tdate = Carbon::now();
            $sale->TAG_ID = null;
            $sale->pos_id = 0; // Manual sale
            $sale->FC_NZNo = 0; // No nozzle for product sales
            $sale->icode = $request->product_id;
            $sale->qty = $request->quantity * 100; // Store in smallest unit
            $sale->rate = $request->rate * 100; // Store in smallest unit
            $sale->amt = $request->amount * 100; // Store in smallest unit
            $sale->totalizer = 0;
            $sale->FSN = 0;
            $sale->VTotalizer = 0;
            $sale->p_mode = $request->payment_method;
            $sale->customer_id = $request->customer_id;
            $sale->vid = null;
            $sale->RegNO = null;
            $sale->bPrint = 1;
            $sale->user_id = Auth::id();
            $sale->shift_id = null;
            $sale->erp_id = null;
            $sale->npick = null;
            $sale->ndrop = null;
            $sale->bIsUpdated = 0;
            $sale->save();
            $id = $sale->id;
            // insert same data to salesend
            $salesend = $sale->replicate(['erp_id', 'bIsUpdated']);
            $salesend->setTable('salesend');

            $salesend->id = $id;
            $salesend->save();

            // Update customer credit limit if credit sale
            if ($request->payment_method == 2) {
                $customer->LimitUsed += $request->amount;
                $customer->save();
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Product sale recorded successfully',
                'sale_id' => $sale->id
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['success' => false, 'message' => 'Error recording sale: ' . $e->getMessage()], 500);
        }
    }
}
