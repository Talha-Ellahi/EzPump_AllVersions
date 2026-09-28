<?php

namespace App\Http\Controllers;

use App\Models\SummmaryReport;
use Illuminate\Http\Request;

class SummmaryReportController extends Controller
{
    public function main(Request $request)
    {
        $model = new SummmaryReport;
        $model->calendar_id = $request->calendar_id;
        $model->FormData = json_encode($request->except('calendar_id'));
        $model->save();
        return response()->json(['success' => 'Form Saved In Database Successfully']);
    }

    public function getall()
    {
        $data = SummmaryReport::all();
        return response()->json($data);
    }

    public function indexold()
    {
        $reports = SummmaryReport::latest()->get();
        return view('reports.index', compact('reports'));
    }
    public function index()
    {
        $reports = SummmaryReport::orderBy('id', 'DESC')->get();

        $reports = $reports->map(function ($report) {
            // JSON decode
            $data = json_decode($report->FormData, true);

            // Nozel details array me first shift ka start_date
            if (!empty($data['nozel_details']) && isset($data['nozel_details'][0]['shift']['start_date'])) {
                $report->shift_start_date = $data['nozel_details'][0]['shift']['start_date'];
            } else {
                $report->shift_start_date = null;
            }
            return $report;
        });

        return view('reports.index', compact('reports'));
    }


    public function showold($id)
    {
        $report = SummmaryReport::findOrFail($id);
        $data = json_decode($report->FormData, true);
        // Remove items where all values are null from specified arrays
        $keysToFilter = ['sales', 'credit', 'credit_cards', 'expense','cash_expense','fuel_stock','pay_rei_am','credit_customers',];
        foreach ($keysToFilter as $key) {
            if (isset($data[$key])) {
                $data[$key] = array_filter($data[$key], function($item) {
                return !empty(array_filter($item, function($value) {
                    return $value !== null;
                }));
            });
        }
        }
        $key = 'credit_cards';
        if (isset($data[$key])) {
                $data[$key] = array_filter($data[$key], function($item) {
                return $item['item'] !== null && $item['amount'] !== null;
            });
        }

        return view('reports.summary', compact('data'));
    }
    public function show($id)
    {
        $report = SummmaryReport::findOrFail($id);
        $data = json_decode($report->FormData, true);

        // Arrays which should be filtered
        $keysToFilter = [
            'sales',
            'credit',
            'credit_cards',
            'expense',
            'cash_expense',
            'pay_rei_am',
            'credit_customers',
        ];

        foreach ($keysToFilter as $key) {
            if (isset($data[$key])) {
                $data[$key] = array_filter($data[$key], function($item) {
                    return !empty(array_filter($item, fn($v) => $v !== null));
                });
            }
        }

        // Special condition for credit_cards
        if (isset($data['credit_cards'])) {
            $data['credit_cards'] = array_filter($data['credit_cards'], function($item) {
                return ($item['item'] ?? null) !== null && ($item['amount'] ?? null) !== null;
            });
        }
//        $data = json_decode($report->FormData, true);
//
        if (!empty($data['tank_stock']) && is_array($data['tank_stock'])) {
            foreach ($data['tank_stock'] as $i => $tank) {
                $data['tank_stock'][$i] = $this->computeTankStock($tank);
            }
        }
        // fuel_stock: DO NOT FILTER (important)

        return view('reports.summary', compact('data'));
    }

    protected function computeTankStock(array $tank): array
    {
        // helper to safely convert to float (treat null/'' as 0)
        $toFloat = fn($v) => is_null($v) || $v === '' ? 0.0 : (float) $v;

        $prevDipLitre   = $toFloat($tank['prevDipLitre'] ?? $tank['prev_dip_litre'] ?? $tank['prevDip'] ?? 0);
        $buy            = $toFloat($tank['buy'] ?? $tank['purchase'] ?? 0);
        $sale           = $toFloat($tank['sale'] ?? 0);
        $currentDipLitre= $toFloat($tank['currentDipLitre'] ?? $tank['current_dip_litre'] ?? $tank['currentDip'] ?? 0);

        $expectedClosing = $prevDipLitre + $buy - $sale;
        $gainLoss = $currentDipLitre - $expectedClosing;

        // format to 2 decimals (string) if you want to keep JSON consistent
        $tank['prevDipLitre']    = $prevDipLitre;
        $tank['buy']             = $buy === 0.0 ? null : $buy; // keep null if originally null (optional)
        $tank['sale']            = $sale === 0.0 ? null : $sale;
        $tank['currentDipLitre'] = $currentDipLitre;
        $tank['gainLoss']        = number_format($gainLoss, 2, '.', '');

        return $tank;
    }

    public function allReports()
    {
        $data = SummmaryReport::all();
        return view('All_reports', compact('data'));
    }
    public function edit($id)
    {
        $report = SummmaryReport::findOrFail($id);
        // FormData stored as JSON in DB
        $data = json_decode($report->FormData, true);

        return view('summary-report-form', compact('report', 'data'));
    }

    // Update form submission
    public function update(Request $request, $id)
    {
        $report = SummmaryReport::findOrFail($id);

        $report->FormData = json_encode($request->all());
        $report->save();

        return response()->json(['success' => true, 'message' => 'Report updated successfully']);
    }
    public function comparisonReport($calendarId)
    {
        // 🔹 Fetch system shift logs
        $shiftLogs = \DB::table('shift_log')
            ->where('calendar_id', $calendarId)
            ->get();

        // 🔹 Fetch manual reports
        $manualLogs = \DB::table('summmary_reports')
            ->where('calendar_id', $calendarId)
            ->get();

        $systemNozzleRows = [];

        // 1️⃣ Build sequential system nozzle array
        foreach ($shiftLogs as $log) {
            $data = json_decode($log->data, true);
//            dd($data['paymentMethods']);
            if (!isset($data['shift'])) continue;

            $shift = $data['shift'];
//            dd($shift['total_qty']);
            $unit = $log->unit_name ?? 'Dis-1';

            $systemNozzleRows[] = [
                'unit'        => $unit,
                'type'        => 'Tatsuno (Sunny)',
                'nozzle'      => $shift['pump_id'] ?? 0,
                'nozzle_icode'=> $shift['product_id'] ?? 0,
                'sys_opening' => isset($shift['opening_fuel']) ? $shift['opening_fuel']/100 : 0,
                'sys_closing' => isset($shift['closing_fuel']) ? $shift['closing_fuel']/100 : 0,
                'sys_sale'    => $shift['total_qty'] ?? 0,
            ];
        }

        // 2️⃣ Merge manual data with system data sequentially
        $nozzleRows = [];
        $systemIndex = 0;

        foreach ($manualLogs as $mLog) {
            $formData = json_decode($mLog->FormData, true);
            if (!isset($formData['nozel_details'])) continue;
//dd($formData['nozel_details']['NzlNo']);
            $unit = $mLog->unit_name ?? 'Dis-1';

            foreach ($formData['nozel_details'] as $nozel) {
//                dd($nozel);
                $sys = $systemNozzleRows[$systemIndex] ?? [
                    'sys_opening'=>0,
                    'sys_closing'=>0,
                    'sys_sale'=>0,
                    'unit'=>$unit,
                    'nozzle_icode'=>$nozel['ICODE'] ?? 0,
                    'nozzle'=>$nozel['NzlNo'] ?? 0
                ];
                $sys_sale = $sys['sys_sale'] / 100;  // system sale
                $manual_sale = (($nozel['closing'] ?? 0) - ($nozel['opening'] ?? 0)) ; // manual sale
                $difference = $sys_sale - $manual_sale;
                $nozzleRows[] = [
                    'unit'           => $unit,
                    'type'           => 'Tatsuno (Sunny)',
                    'nozzle'         => $sys['nozzle'] ?? 0,
                    'nozzle_icode'   => $nozel['ICODE'] ?? 0,
                    'sys_opening'    => $sys['sys_opening'],
                    'manual_opening' => $nozel['opening'] ?? 0,
                    'sys_closing'    => $sys['sys_closing'],
                    'manual_closing' => $nozel['closing'] ?? 0,
                    'sys_sale'       => round($sys_sale, 2),
                    'manual_sale'    => round($manual_sale, 2),
                    'difference'     => round($difference, 2),
                ];

                $systemIndex++;
            }
        }

        // 3️⃣ Optional: sort by nozzle number
        $nozzleRows = collect($nozzleRows)->sortBy('nozzle')->values()->all();
//dd($nozzleRows);
        // 4️⃣ Product-wise summary
        $productSummary = [];
        $products = [
            1 => 'Petrol',
            2 => 'HiOctane',
            3 => 'Diesel',
        ];

        foreach ($products as $icode => $name) {
            $systemSale = collect($nozzleRows)
                ->where('nozzle_icode', $icode)
                ->sum('sys_sale');
//            dd($systemSale);
            $manualSale = collect($nozzleRows)
                ->where('nozzle_icode', $icode)
                ->sum('manual_sale');

            $productSummary[] = [
                'product'     => $name,
                'system_sale' => round($systemSale, 2),
                'manual_sale' => round($manualSale, 2),
                'difference'  => round($systemSale - $manualSale, 2),
            ];
        }
        // 5️⃣ Tank shift report

        // 1️⃣ Manual Summary Report (ONLY ONE per calendar)
        $manualSummary = \DB::table('summmary_reports')
            ->where('calendar_id', $calendarId)
            ->first();
//dd($manualSummary);
        $manualTankStock = [];
        if ($manualSummary) {
            $formData = json_decode($manualSummary->FormData, true);
            $manualTankStock = $formData['tank_stock'] ?? [];
        }

// 2️⃣ System Tank Shift Logs
        $tankLogs = \DB::table('tank_shift_logs')
            ->where('calendar_id', $calendarId)
            ->get();

        $tankRows = $tankLogs->map(function ($row) use ($manualTankStock) {

            // 🔹 System ledger
            $ledger = json_decode($row->data, true)['stock_ledger'] ?? [];
            $salesData = json_decode($row->data, true)['sales_data'] ?? [];

            $sale = collect($salesData)->sum(function ($item) {
                return (float) ($item['total_quantity']/100 ?? 0);
            });

//            dd($totalQuantity);
            $purchase = collect($ledger)
                ->where('transaction_type', 'purchase')
                ->sum(fn($t) => $t['stock_change'] ?? 0);

            // 🔹 System dips
            $sys_open_dip  = $row->opening_dip ?? 0;
            $sys_close_dip = $row->closing_dip ?? 0;
            // 🔹 Manual values from summary JSON
            $man_open_dip = 0;
            $man_open_stock = 0;
            $man_close_dip = 0;
            $man_closing_stock = 0;
            $gain_loss = 0;
            $man_sale=0;
            $man_purchase=0;
            $tank_name='';
//dd($manualTankStock);
            foreach ($manualTankStock as $tank) {
                $tankName = trim($tank['tankName']);
                $expectedTank = 'Tank' . str_pad($row->tank_id, 2, '0', STR_PAD_LEFT);

                if (stripos($tankName, $expectedTank) !== false) { // case-insensitive contains
                    // map manual values
                    $man_open_dip      = (float)($tank['prevDip'] ?? 0);
                    $man_open_stock    = (float)($tank['prevDipLitre'] ?? 0);
                    $man_close_dip     = (float)($tank['currentDip'] ?? 0);
                    $man_closing_stock = (float)($tank['currentDipLitre'] ?? 0);
                    $man_sale          = (float)($tank['sale'] ?? 0);
                    $man_purchase      = (float)($tank['buy'] ?? 0);
                    $gain_loss         = (float)($tank['gainLoss'] ?? 0);
                    $tank_name         = $tank['product'] ?? '';
                    // do NOT break; in case multiple matches exist
                }
            }





            // 🔹 System stock (ONLY system formula)
            $mm1=$sys_open_dip/100;
            $stock=\DB::table('dip_chart_values')->where('tank_id', $row->tank_id)
                ->orderByRaw("ABS(millimeter - {$mm1})")->first();
            $mm=$sys_close_dip/100;
            $stock_closing=\DB::table('dip_chart_values')->where('tank_id', $row->tank_id)
                ->orderByRaw("ABS(millimeter - {$mm})")
                ->value('liter_value');
            $sys_open_stock    = $sys_open_dip;
            $sys_closing_stock = $sys_open_stock + $purchase - $sale;

//            $diff = $sys_closing_stock - $man_closing_stock;
//            $diff =  round($sale - $man_sale, 2);
            $consumedSys=($sys_close_dip/100)-($sys_open_dip/100);
            $consumedMan=($man_close_dip)-($man_open_dip);
            $diff=$consumedSys - $consumedMan;
//dd($gain_loss);
//dd($row);
            return [
                'tank_no'            => 'Tank' . $row->tank_id,
                'product'            =>  $tank_name,

                'sys_open_dip'       => $sys_open_dip/100,
                'sys_open_stock'     => round($stock->liter_value, 2),

                'man_open_dip'       => $man_open_dip,
                'man_open_stock'     => round($man_open_stock, 2),

                'sys_purchase' => round($purchase, 2),
                'man_purchase' => round($man_purchase ,2),
                'sys_sale'     => round($sale, 2),
                'man_sale'     => round($man_sale, 2),


                'sys_close_dip'      => $sys_close_dip/100,
                'sys_closing_stock'  => round($stock_closing, 2),

                'man_close_dip'      => $man_close_dip,
                'man_closing_stock'  => round($man_closing_stock, 2),

                'diff'               => round($diff, 2),
                'gain_loss'          => round($gain_loss, 2),

                'comments'           => '',
                'tank_id'=>$row->tank_id
            ];
        });

// --------------------------
        // 4️⃣ Payment Methods Comparison
        // --------------------------
//        $paymentRows = [];
//        foreach ($shiftLogs as $log) {
//            $data = json_decode($log->data, true);
////            dd($data);
//            foreach ($data['paymentMethods'] ?? [] as $pm) {
//                $paymentRows[$pm['pMethod']] = [
//                    'system_amount' => (float)($pm['total_amount'] ?? 0),
//                    'manual_amount' => 0
//                ];
//            }
//        }
//        foreach ($manualLogs as $mLog) {
//            $formData = json_decode($mLog->FormData, true);
//            foreach ($formData['credit_cards'] ?? [] as $pm) {
//                if(isset($paymentRows[$pm['item']])) {
//                    $paymentRows[$pm['item']]['manual_amount'] = (float)($pm['amount']?? 0);
//                }
//            }
//        }
//        foreach ($paymentRows as $method => &$row) {
//            $row['difference'] = round($row['system_amount'] - $row['manual_amount'], 2);
//        }
        // 1️⃣ Get all payment method names for mapping
        $paymentMethodMap = \DB::table('paymentmethod')
            ->pluck('Des', 'id')   // id => Des
            ->toArray();

// 2️⃣ Prepare system amounts from shift logs
        $paymentRows = [];

        foreach ($shiftLogs as $log) {
            $data = json_decode($log->data, true);
//dd($data);
            foreach ($data['shiftPaymentWiseSales'] ?? [] as $pm) {
                $methodId   = $pm['paymentmethod_id'];
                $methodName = $paymentMethodMap[$methodId] ?? 'Unknown';

                if (!isset($paymentRows[$methodName])) {
                    $paymentRows[$methodName] = [
                        'system_amount' => 0,
                        'manual_amount' => 0
                    ];
                }

                // Sum amounts across multiple shifts
                $paymentRows[$methodName]['system_amount'] += (float)($pm['total_sale'] ?? 0);
            }
        }

// 3️⃣ Add manual payments (e.g., card/credit logs)
        foreach ($manualLogs as $mLog) {
            $formData = json_decode($mLog->FormData, true);

            foreach ($formData['credit_cards'] ?? [] as $pm) {
                $method = $pm['item']; // e.g., "Cash", "Card", etc.

                if (!isset($paymentRows[$method])) {
                    $paymentRows[$method] = [
                        'system_amount' => 0,
                        'manual_amount' => 0
                    ];
                }

                // Sum manual amounts
                $paymentRows[$method]['manual_amount'] += (float)($pm['amount'] ?? 0);
            }
        }

// 4️⃣ Calculate differences
        foreach ($paymentRows as $method => &$row) {
            $row['difference'] = round(
                $row['system_amount'] - $row['manual_amount'],
                2
            );
        }

        unset($row);




        // 5️⃣ Return view
        return view('reports.comparison', compact('nozzleRows', 'productSummary','tankRows','shiftLogs', 'paymentRows'));
    }

    public function list(Request $request)
    {
//        return response()->json(
//            \DB::table('shift_calendars')
//                ->orderBy('work_date', 'desc')
//                ->get(['id', 'work_date'])
//        );
        $date = $request->query('date'); // get ?date=YYYY-MM-DD

        $query = \DB::table('shift_calendars')
            ->orderBy('work_date', 'desc');

        if ($date) {
            $query->whereDate('work_date', $date);
        }

        $calendars = $query->get(['id', 'work_date']);

        return response()->json($calendars);
    }






}
