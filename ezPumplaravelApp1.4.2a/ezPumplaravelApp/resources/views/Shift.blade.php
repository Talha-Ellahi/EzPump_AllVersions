@extends('layouts.app')

<style>
    .modal-fullscreen {
        width: 100vw!important;
        max-width: none!important;
        height: 90%!important;
        margin: 0!important;
    }
    @media (min-width: 1200px) {
        .col-item-8 {
            width: 12.5%;
        }
    }
    #summary_total_sale{
        font-weight: bold;
    }
    .singleCard .card-img-top{
        /*width: 100%;*/
        width: 60%;
        aspect-ratio: 1;
    }

    .d-grid {
        display: grid !important;
        justify-content: center;
    }

    .table>thead:first-child>tr:first-child>th {
        border-top: 0;
        font-size: small;
    }

    .fs-8 {
        font-size: .8rem !important;
    }

    table {
        #summary_total_sale{
            prefix: "Rs";
            font-weight: bold;
        }
        th,
        tr {
            border-width: 0px !important;
        }

        .cs-f11 {
            font-size: 11px;
        }

        .cs-f12 {
            font-size: 12px;
        }

        .cs-f13 {
            font-size: 13px;
        }

        .cs-f14 {
            font-size: 14px;
        }

        .cs-f15 {
            font-size: 15px;
        }

        .cs-f16 {
            font-size: 16px;
        }

        .cs-f17 {
            font-size: 17px;
        }

        .cs-f18 {
            font-size: 18px;
        }

        .cs-f19 {
            font-size: 19px;
        }

        .cs-f20 {
            font-size: 20px;
        }

        .cs-f21 {
            font-size: 21px;
        }

        .cs-f22 {
            font-size: 22px;
        }

        .cs-f23 {
            font-size: 23px;
        }

        .cs-f24 {
            font-size: 24px;
        }

        .cs-f25 {
            font-size: 25px;
        }

        .cs-f26 {
            font-size: 26px;
        }

        .cs-f27 {
            font-size: 27px;
        }

        .cs-f28 {
            font-size: 28px;
        }

        .cs-f29 {
            font-size: 29px;
        }

        .cs-light {
            font-weight: 300;
        }

        .cs-normal {
            font-weight: 400;
        }

        .cs-medium {
            font-weight: 500;
        }

        .cs-semi_bold {
            font-weight: 600;
        }

        .cs-bold {
            font-weight: 700;
        }

        .cs-m0 {
            margin: 0px;
        }

        .cs-mb0 {
            margin-bottom: 0px;
        }

        .cs-mb1 {
            margin-bottom: 1px;
        }

        .cs-mb2 {
            margin-bottom: 2px;
        }

        .cs-mb3 {
            margin-bottom: 3px;
        }

        .cs-mb4 {
            margin-bottom: 4px;
        }

        .cs-mb5 {
            margin-bottom: 5px;
        }

        .cs-mb6 {
            margin-bottom: 6px;
        }

        .cs-mb7 {
            margin-bottom: 7px;
        }

        .cs-mb8 {
            margin-bottom: 8px;
        }

        .cs-mb9 {
            margin-bottom: 9px;
        }

        .cs-mb10 {
            margin-bottom: 10px;
        }

        .cs-mb11 {
            margin-bottom: 11px;
        }

        .cs-mb12 {
            margin-bottom: 12px;
        }

        .cs-mb13 {
            margin-bottom: 13px;
        }

        .cs-mb14 {
            margin-bottom: 14px;
        }

        .cs-mb15 {
            margin-bottom: 15px;
        }

        .cs-mb16 {
            margin-bottom: 16px;
        }

        .cs-mb17 {
            margin-bottom: 17px;
        }

        .cs-mb18 {
            margin-bottom: 18px;
        }

        .cs-mb19 {
            margin-bottom: 19px;
        }

        .cs-mb20 {
            margin-bottom: 20px;
        }

        .cs-mb21 {
            margin-bottom: 21px;
        }

        .cs-mb22 {
            margin-bottom: 22px;
        }

        .cs-mb23 {
            margin-bottom: 23px;
        }

        .cs-mb24 {
            margin-bottom: 24px;
        }

        .cs-mb25 {
            margin-bottom: 25px;
        }

        .cs-mb26 {
            margin-bottom: 26px;
        }

        .cs-mb27 {
            margin-bottom: 27px;
        }

        .cs-mb28 {
            margin-bottom: 28px;
        }

        .cs-mb29 {
            margin-bottom: 29px;
        }

        .cs-mb30 {
            margin-bottom: 30px;
        }

        .cs-pt25 {
            padding-top: 25px;
        }

        .cs-width_1 {
            width: 8.33333333%;
        }

        .cs-width_2 {
            width: 15.66666667%;
        }

        .cs-width_3 {
            width: 25%;
        }

        .cs-width_4 {
            width: 33.33333333%;
        }

        .cs-width_5 {
            width: 41.66666667%;
        }

        .cs-width_6 {
            width: 50%;
        }

        .cs-width_7 {
            width: 58.33333333%;
        }

        .cs-width_8 {
            width: 66.66666667%;
        }

        .cs-width_9 {
            width: 75%;
        }

        .cs-width_10 {
            width: 83.33333333%;
        }
    }
    .cs-width_11 {
        width: 91.66666667%;
    }

    .cs-width_12 {
        width: 100%;
    }

    .cs-accent_color,
    .cs-accent_color_hover:hover {
        color: #2ad19d;
    }

    .cs-accent_bg,
    .cs-accent_bg_hover:hover {
        background-color: #2ad19d;
    }

    .cs-primary_color {
        color: #111111;
    }

    .cs-secondary_color {
        color: #777777;
    }

    .cs-ternary_color {
        color: #353535;
    }

    .cs-ternary_color {
        border-color: #eaeaea;
    }

    .cs-focus_bg {
        background: #f6f6f6;
    }

    .cs-accent_10_bg {
        background-color: rgba(42, 209, 157, 0.1);
    }

    .cs-container {
        max-width: 880px;
        padding: 30px 15px;
        margin-left: auto;
        margin-right: auto;
    }

    .cs-text_center {
        text-align: center;
    }

    .cs-text_right {
        text-align: right;
    }

    .cs-border_bottom_0 {
        border-bottom: 0;
    }

    .cs-border_top_0 {
        border-top: 0;
    }

    .cs-border_bottom {
        border-bottom: 1px solid #eaeaea;
    }

    .cs-border_top {
        border-top: 1px solid #eaeaea;
    }

    .cs-border_left {
        border-left: 1px solid #eaeaea;
    }

    .cs-border_right {
        border-right: 1px solid #eaeaea;
    }

    .cs-table_baseline {
        vertical-align: baseline;
    }

    .cs-round_border {
        border: 1px solid #eaeaea;
        overflow: hidden;
        border-radius: 6px;
    }

    .cs-border_none {
        border: none;
    }

    .cs-border_left_none {
        border-left-width: 0;
    }

    .cs-border_right_none {
        border-right-width: 0;
    }

    .cs-invoice.cs-style1 {
        background: #fff;
        border-radius: 10px;
        padding: 50px;
    }

    .cs-invoice.cs-style1 .cs-invoice_head {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-pack: justify;
        -ms-flex-pack: justify;
        justify-content: space-between;
    }

    .cs-invoice.cs-style1 .cs-invoice_head.cs-type1 {
        -webkit-box-align: end;
        -ms-flex-align: end;
        align-items: flex-end;
        padding-bottom: 25px;
        border-bottom: 1px solid #eaeaea;
    }

    .cs-invoice.cs-style1 .cs-invoice_footer {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
    }

    .cs-invoice.cs-style1 .cs-invoice_footer table {
        margin-top: -1px;
    }

    .cs-invoice.cs-style1 .cs-left_footer {
        width: 55%;
        padding: 10px 15px;
    }

    .cs-invoice.cs-style1 .cs-right_footer {
        width: 46%;
    }

    .cs-invoice.cs-style1 .cs-note {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-align: start;
        -ms-flex-align: start;
        align-items: flex-start;
        margin-top: 40px;
    }

    .cs-invoice.cs-style1 .cs-note_left {
        margin-right: 10px;
        margin-top: 6px;
        margin-left: -5px;
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
    }

    .cs-invoice.cs-style1 .cs-note_left svg {
        width: 32px;
    }

    .cs-invoice.cs-style1 .cs-invoice_left {
        max-width: 55%;
    }

    .cs-invoice_btns {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-pack: center;
        -ms-flex-pack: center;
        justify-content: center;
        margin-top: 30px;
    }

    .cs-invoice_btns .cs-invoice_btn:first-child {
        border-radius: 5px 0 0 5px;
    }

    .cs-invoice_btns .cs-invoice_btn:last-child {
        border-radius: 0 5px 5px 0;
    }

    .cs-invoice_btn {
        display: -webkit-inline-box;
        display: -ms-inline-flexbox;
        display: inline-flex;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
        border: none;
        font-weight: 600;
        padding: 8px 20px;
        cursor: pointer;
    }

    .cs-invoice_btn svg {
        width: 24px;
        margin-right: 5px;
    }

    .cs-invoice_btn.cs-color1 {
        color: #111111;
        background: rgba(42, 209, 157, 0.15);
    }

    .cs-invoice_btn.cs-color1:hover {
        background-color: rgba(42, 209, 157, 0.3);
    }

    .cs-invoice_btn.cs-color2 {
        color: #fff;
        background: #2ad19d;
    }

    .cs-invoice_btn.cs-color2:hover {
        background-color: rgba(42, 209, 157, 0.8);
    }

    .cs-table_responsive {
        overflow-x: auto;
    }

    .cs-table_responsive>table {
        min-width: 600px;
    }

    .cs-50_col>* {
        width: 50%;
        -webkit-box-flex: 0;
        -ms-flex: none;
        flex: none;
    }

    .cs-bar_list {
        margin: 0;
        padding: 0;
        list-style: none;
        position: relative;
    }

    .cs-bar_list::before {
        content: '';
        height: 75%;
        width: 2px;
        position: absolute;
        left: 4px;
        top: 50%;
        -webkit-transform: translateY(-50%);
        transform: translateY(-50%);
        background-color: #eaeaea;
    }

    .cs-bar_list li {
        position: relative;
        padding-left: 25px;
    }

    .cs-bar_list li:before {
        content: '';
        height: 10px;
        width: 10px;
        border-radius: 50%;
        background-color: #eaeaea;
        position: absolute;
        left: 0;
        top: 6px;
    }

    .cs-bar_list li:not(:last-child) {
        margin-bottom: 10px;
    }

    .cs-table.cs-style1.cs-type1 {
        padding: 10px 30px;
    }

    .cs-table.cs-style1.cs-type1 tr:first-child td {
        border-top: none;
    }

    .cs-table.cs-style1.cs-type1 tr td:first-child {
        padding-left: 0;
    }

    .cs-table.cs-style1.cs-type1 tr td:last-child {
        padding-right: 0;
    }

    .cs-table.cs-style1.cs-type2>* {
        padding: 0 10px;
    }

    .cs-table.cs-style1.cs-type2 .cs-table_title {
        padding: 20px 0 0 15px;
        margin-bottom: -5px;
    }

    .cs-table.cs-style2 td {
        border: none;
    }

    .cs-table.cs-style2 td,
    .cs-table.cs-style2 th {
        padding: 12px 15px;
        line-height: 1.55em;
    }

    .cs-table.cs-style2 tr:not(:first-child) {
        border-top: 1px dashed #eaeaea;
    }

    .cs-list.cs-style1 {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .cs-list.cs-style1 li {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
    }

    .cs-list.cs-style1 li:not(:last-child) {
        border-bottom: 1px dashed #eaeaea;
    }

    .cs-list.cs-style1 li>* {
        -webkit-box-flex: 0;
        -ms-flex: none;
        flex: none;
        width: 50%;
        padding: 7px 0px;
    }
    .modal-header .close {
        margin-top: 0;
        background-color: red;
        opacity: 100;
        height: 33px;
        width: 33px;
        display: flex
    ;
        align-items: center;
        padding: 0;
        justify-content: center;
        border-radius: 50px;
        font-size: 24px;
        border: unset;
    }

    .cs-list.cs-style2 {
        list-style: none;
        margin: 0 0 30px 0;
        padding: 12px 0;
        border: 1px solid #eaeaea;
        border-radius: 5px;
    }

    .cs-list.cs-style2 li {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
    }

    .cs-list.cs-style2 li>* {
        -webkit-box-flex: 1;
        -ms-flex: 1;
        flex: 1;
        padding: 5px 25px;
    }

    .cs-heading.cs-style1 {
        line-height: 1.5em;
        border-top: 1px solid #eaeaea;
        border-bottom: 1px solid #eaeaea;
        padding: 10px 0;
    }

    .cs-no_border {
        border: none !important;
    }

    .cs-grid_row {
        display: -ms-grid;
        display: grid;
        grid-gap: 20px;
        list-style: none;
        padding: 0;
    }

    .cs-col_2 {
        -ms-grid-columns: (1fr)[2];
        grid-template-columns: repeat(2, 1fr);
    }

    .cs-col_3 {
        -ms-grid-columns: (1fr)[3];
        grid-template-columns: repeat(3, 1fr);
    }

    .cs-col_4 {
        -ms-grid-columns: (1fr)[4];
        grid-template-columns: repeat(4, 1fr);
    }

    .cs-border_less td {
        border-color: transparent;
    }

    .cs-special_item {
        position: relative;
    }

    .cs-special_item:after {
        content: '';
        height: 52px;
        width: 1px;
        background-color: #eaeaea;
        position: absolute;
        top: 50%;
        -webkit-transform: translateY(-50%);
        transform: translateY(-50%);
        right: 0;
    }

    .cs-table.cs-style1 .cs-table.cs-style1 tr:not(:first-child) td {
        border-color: #eaeaea;
    }

    .cs-table.cs-style1 .cs-table.cs-style2 td {
        padding: 12px 0px;
    }

    .cs-ticket_wrap {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
    }

    .cs-ticket_left {
        -webkit-box-flex: 1;
        -ms-flex: 1;
        flex: 1;
    }

    .cs-ticket_right {
        -webkit-box-flex: 0;
        -ms-flex: none;
        flex: none;
        width: 215px;
    }

    .cs-box.cs-style1 {
        border: 2px solid #eaeaea;
        border-radius: 5px;
        padding: 20px 10px;
        min-width: 150px;
    }

    .cs-box.cs-style1.cs-type1 {
        padding: 12px 10px 10px;
    }

    .cs-max_w_150 {
        max-width: 150px;
    }

    .cs-left_auto {
        margin-left: auto;
    }

    .cs-title_1 {
        display: inline-block;
        border-bottom: 1px solid #eaeaea;
        min-width: 60%;
        padding-bottom: 5px;
        margin-bottom: 10px;
    }

    .cs-box2_wrap {
        display: -ms-grid;
        display: grid;
        grid-gap: 30px;
        list-style: none;
        padding: 0;
        -ms-grid-columns: (1fr)[2];
        grid-template-columns: repeat(2, 1fr);
    }

    .cs-box.cs-style2 {
        border: 1px solid #eaeaea;
        padding: 25px 30px;
        border-radius: 5px;
    }

    .cs-box.cs-style2 .cs-table.cs-style2 td {
        padding: 12px 0;
    }

    @media print {
        .cs-hide_print {
            display: none !important;
        }
    }

    @media (max-width: 767px) {
        .cs-mobile_hide {
            display: none;
        }

        .cs-invoice.cs-style1 {
            padding: 30px 20px;
        }

        .cs-invoice.cs-style1 .cs-right_footer {
            width: 100%;
        }
    }

    @media (max-width: 500px) {
        .cs-invoice.cs-style1 .cs-logo {
            margin-bottom: 10px;
        }

        .cs-invoice.cs-style1 .cs-invoice_head {
            -webkit-box-orient: vertical;
            -webkit-box-direction: normal;
            -ms-flex-direction: column;
            flex-direction: column;
        }

        .cs-invoice.cs-style1 .cs-invoice_head.cs-type1 {
            -webkit-box-orient: vertical;
            -webkit-box-direction: reverse;
            -ms-flex-direction: column-reverse;
            flex-direction: column-reverse;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
            text-align: center;
        }

        .cs-invoice.cs-style1 .cs-invoice_head .cs-text_right {
            text-align: left;
        }

        .cs-list.cs-style2 li {
            -webkit-box-orient: vertical;
            -webkit-box-direction: normal;
            -ms-flex-direction: column;
            flex-direction: column;
        }

        .cs-list.cs-style2 li>* {
            padding: 5px 20px;
        }

        .cs-grid_row {
            grid-gap: 0px;
        }

        .cs-col_2,
        .cs-col_3,
        .cs-col_4 {
            -ms-grid-columns: (1fr)[1];
            grid-template-columns: repeat(1, 1fr);
        }

        .cs-table.cs-style1.cs-type1 {
            padding: 0px 20px;
        }

        .cs-box2_wrap {
            -ms-grid-columns: (1fr)[1];
            grid-template-columns: repeat(1, 1fr);
        }

        .cs-box.cs-style1.cs-type1 {
            max-width: 100%;
            width: 100%;
        }

        .cs-invoice.cs-style1 .cs-invoice_left {
            max-width: 100%;
        }
    }
</style>


@section('content')
    @php
        use Illuminate\Support\Facades\DB;

        $tz     = 'Asia/Karachi';
        $now    = now($tz);
        $today  = $now->toDateString();

        // Pumps
        $pumps = DB::table('PUMPS')
            ->select('id','SHRT','ICODE','FC_NZNo','AmtD','DspID','display_name')
            ->orderBy('ICODE')          // pehle product wise
        //    ->orderByRaw('CAST(FC_NZNo as UNSIGNED) ASC') // FC_NZNo ko number bana ke sort
             ->orderByRaw('CAST(display_name AS UNSIGNED) ASC')
            ->get();

    //$pumps = DB::table('PUMP_STATE as ps')
    //    ->select(
    //        'ps.*',
    //        'p.FC_NZNo',
    //        'p.display_name',
    //        'p.ICODE',
    //        DB::raw('shift.total_qty as shift_quantity'),
    //        DB::raw('shift.rate as shift_rate'),
    //        'shift.total_amount'
    //    )
    //    ->leftJoin('PUMPS as p', 'p.FC_NZNo', '=', 'ps.PUMPID')
    //    ->leftJoin('shift', function ($join) {
    //        $join->on('shift.pump_id', '=', 'p.FC_NZNo')
    //             ->where('shift.status', '=', 1);
    //    })
    //    ->get();


        $pumpIds = $pumps->pluck('FC_NZNo')->all();
    $productWiseSales = DB::select("
        SELECT icode, icode as ICODE, icode as product_code,
               SUM(sale_count) as sale_count,
               SUM(total_amount) as total_amount,
               SUM(total_qty) as total_qty
        FROM shift
        GROUP BY icode
    ");
    //dd($productWiseSales);
    $totalSales = DB::select("SELECT SUM(total_amount) as total_amount FROM shift");
    $totalSales = $totalSales[0]->total_amount ?? 0;

        // Latest state per pump
        $latestStates = DB::table('PUMP_STATE as ps')
            ->join(DB::raw('(SELECT PUMPID, MAX(time) as max_time FROM PUMP_STATE GROUP BY PUMPID) t'),
                function ($join) {
                    $join->on('t.PUMPID', '=', 'ps.PUMPID')
                         ->on('t.max_time', '=', 'ps.time');
                })
            ->whereIn('ps.PUMPID', $pumpIds ?: [0])
            ->select('ps.PUMPID','ps.qty','ps.rate','ps.amt','ps.shift_qty','ps.shift_amt','ps.STS','ps.time')
            ->get()
            ->keyBy('PUMPID');

        // Active shifts
    //    $activeShifts = DB::table('shift')
    //        ->whereIn('pump_id', $pumpIds ?: [0])
    //        ->whereNull('end_date')
    //        ->select('id','pump_id','start_date','rate','total_qty')
    //        ->orderBy('id','desc')
    //        ->get()
    //        ->keyBy('pump_id');

    $activeShifts = DB::table('shift')
        ->select('id','pump_id','start_date','rate','total_qty','total_amount','is_changed')
        ->whereIn('pump_id', $pumpIds ?: [0])
        ->whereNull('end_date')
        ->orderBy('id','desc')
        ->get()
        ->groupBy('pump_id')
        ->map(function ($rows) {
            return $rows->first(); // har pump ka latest active shift
        });


        // Sales today
        $salesToday = DB::table('saledata')
            ->whereDate('pdate', $today)
            ->select('ICODE', DB::raw('SUM(qty) as qty_x100'), DB::raw('SUM(amt) as amt'))
            ->groupBy('ICODE')
            ->get();

        // Payments today
        $paymentsToday = DB::table('saledata')
            ->whereDate('pdate', $today)
            ->select('p_mode', DB::raw('COUNT(*) as cnt'), DB::raw('SUM(amt) as amt'))
            ->groupBy('p_mode')
            ->orderBy('amt','desc')
            ->get();

        // Products
        $products = DB::table('PRODUCT')
            ->select('ICODE','ITMNAME','SRATE')
            ->get()
            ->keyBy('ICODE');

        // Helpers
        $fmt2 = fn($n) => number_format((float)$n, 2);
        $toL  = fn($x100) => $fmt2($x100 / 100);
    @endphp
    <div class="wrapper">

        <!--start page wrapper -->

        <div class="page-content">
            <div class="mx-auto">

                <div class="card border-top border-0 border-4 border-danger">

                    <div class="card-body ">
                        @php
                            use Carbon\Carbon;

                            $shift_time = DB::select("SELECT * FROM shift LIMIT 1");
                        $shiftDate=0;
                        if (!empty($shift_time)) {
                            $shiftDate = $shift_time[0];  // Access the first element
                        }
                            if ($shiftDate) {
                                $formattedStartDate = Carbon::parse($shiftDate->start_date)->format('Y-m-d H:i:s'); // Adjust the format as needed
                            } else {
                                $formattedStartDate = 'No shift found';
                            }
                        @endphp
                        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                            <div class="pe-3">
                                <h5 class="mb-0 text-uppercase"><b>Shift Start Date & Time: </b>{{ $formattedStartDate }}
                                </h5>
                            </div>


                        </div>
                        {{--                        <div class="d-flex justify-content-between align-items-center mb-3">--}}
                        {{--                            <h2 class="mb-0">Shifts & Pumps <small class="text-muted">{{ $today }}</small></h2>--}}
                        {{--                            <div class="small text-muted">Server time: {{ $now->format('h:i A') }} ({{ $tz }})</div>--}}
                        {{--                        </div>--}}
                        <hr>
                        <br>
                        {{-- Pump Cards --}}
                        <div class="row g-2 singleCard ">
                            @foreach ($pumps as $pump)
                                @php
                                    $state = $latestStates[$pump->FC_NZNo] ?? null;
                                    $shift = $activeShifts[$pump->FC_NZNo] ?? null;
                                @endphp

                                <div class="col-lg-2 col-sm-6 col-lg-4 col-xl-2 singleCard">
                                    <div class="card h-100 shadow-sm pump-card openSaleHistory"
                                         data-id="{{ $shift->FC_NZNo ?? '' }}"
                                         data-pumpid="{{ $pump->FC_NZNo }}"
                                         data-icode="{{ $pump->ICODE }}"
                                         data-sts="{{ $state->STS ?? '' }}">
                                        <img src="assets/images/Nozzle.png" class="card-img-top nozzle-image" alt="Nozzle">
                                        {{--                                        <div class="product-name-overlay position-absolute top-0 start-50 translate-middle-x fw-bold text-dark"--}}
                                        {{--                                             style="font-size: 18px; text-shadow: 1px 1px 2px #fff;">--}}
                                        {{--                                            {{ $pump->display_name ?? ($products[$pump->ICODE]->ITMNAME ?? '') }}--}}
                                        {{--                                        </div>--}}
                                        {{--                                        @php--}}
                                        {{--                                        $dispener_name=\DB::table('DISPENSERS')->where('DspID',$pump->DspID)->first();--}}
                                        {{--//                                       dd($dispener_name);--}}
                                        {{--                                        @endphp--}}
                                        {{--                                        <div class="">--}}
                                        {{--                                            <div class="sanat position-absolute top-0 end-0 m-3 product-discount" style="width: 3.5rem;height: 3.5rem;"><span class=""><b>
                                        {{ $pump->display_name.':'.$dispener_name->TYP.''.$pump->DspID}}</b></span></div>--}}
                                        {{--                                        </div>--}}
                                        {{--                                        //new code//--}}
                                        <div style="position: absolute; top: 0; left: 0; width: 100%; padding: 8px; display: flex; justify-content: space-between; align-items: center;">

                                            {{-- Product name (center) --}}
                                            <div class="product-name-overlay top-0" style="flex-grow: 1; text-align: center; font-weight: bold; color: #000; font-size: 18px; text-shadow: 1px 1px 2px #fff;">
                                                {{ $pump->display_name ?? ($products[$pump->ICODE]->ITMNAME ?? '') }}
                                            </div>


                                            {{-- Dispenser info (right side) --}}
                                            @php
                                                $dispener_name = \DB::table('DEVICES')->where('DevID',$pump->DspID)->first();
                                            @endphp
                                            <div class="position-absolute top-0 end-0 m-3 product-discount" style=" text-align: right; font-weight: bold; color: #000;width: 3.5rem;height: 3.5rem;font-size:10px">
                                                {{ 'N'.$pump->display_name.':D'.$pump->DspID}}
                                            </div>
                                        </div>

                                        <div class="card-body">
                                            <div class="last_sale">
                                                <p class="mb-0 ms-auto">Last Sale:</p>
                                                <b class="mb-0 ms-auto qty">{{ $toL($state->qty) }}</b> <span>Ltrs</span> -- Rs.
                                                {{--                            <b class="mb-0 ms-auto amt">{{ $fmt2($state->amt / 100) }}</b>--}}
                                                <b class="mb-0 ms-auto amt">{{ (int) round($state->amt / 100) }}</b>

                                            </div>

                                            <div class="total_sale">
                                                @php
                                                    // Pump ka shift data lo (ho bhi sakta hai ya na ho)
                                                    $shiftData = $activeShifts[$pump->FC_NZNo] ?? null;

                                                    $qty100  = $shiftData->total_qty ?? 0;   // total_qty shift table se
//                                                    $rate100 = ($products[$pump->ICODE]->SRATE ?? 0) / 100;
//
                                                    if ($shiftData && $shiftData->is_changed) {
                                                        $rate100 = ($shiftData->new_rate ?? $shiftData->rate) / 100;
                                                    } else {
                                                        $rate100 = ($shiftData->rate ?? ($products[$pump->ICODE]->SRATE ?? 0)) / 100;
                                                    }
                                                    // Liters aur Amount calculate
                                                    $displayQty = $qty100 / 100; // keep 2 decimal precision
                                                   $displayAmount = round($displayQty * $rate100, 2);

                                                @endphp

                                                <p class="mb-0 ms-auto">Total Sale:</p>
                                                <b class="mb-0 ms-auto qty">{{ $displayQty }}</b> <span>Ltrs</span> -- Rs.
                                                <b class="mb-0 ms-auto amt">{{ $displayAmount }}</b>
                                            </div>
                                            <span class="mb-0 ms-auto rate">Rate. </span><b class="mb-0 ms-auto amt">{{ $rate100 }}</b>


                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- Today Stats --}}
                        @php

                            // Fetch product-wise sales
                            $productWiseSales = DB::table('shift')
                                ->selectRaw('icode, SUM(sale_count) as sale_count, SUM(total_amount) as total_amount, SUM(total_qty) as total_qty')
                                ->groupBy('icode')
                                ->get();
                            // Fetch all products
                            $products = DB::table('PRODUCT')->get()->keyBy('ICODE');
                            // Calculate total amount
                            $totalAmt = $productWiseSales->sum('total_amount') / 100;

                            // Fetch payment-wise sales
                            $paymentMethodSales = DB::table('shift_payment_wise_sale as SPS')
                                ->leftJoin('paymentmethod as PM', 'SPS.paymentmethod_id', '=', 'PM.id')
                                ->selectRaw('PM.Des as payment_method, SUM(SPS.total_sale)/100 as total_amount')
                                ->where('PM.id', '!=', 1)
                                ->groupBy('PM.Des')
                                ->get();

                            // Cash = total - non-cash
                            $totalNonCash = $paymentMethodSales->sum('total_amount');
                            $totalShiftSales = DB::table('shift')->sum('total_amount') / 100;
                            $cashSale = round($totalShiftSales - $totalNonCash, 2);

                            $paymentMethodSales->push((object)['payment_method' => 'Cash', 'total_amount' => $cashSale]);

                        @endphp
                        <br>
                        <div class="pe-3">
                            <h5 class="mb-0 text-uppercase"><b>Short Summary </b></h5>
                        </div>
                        <hr>

                        <!-- Product-wise Sales Table -->
                        <div class="cs-container table-responsive mb-4">
                            <table class="table table-sm mb-0 align-middle">
                                <thead>
                                <tr class="text-muted">
                                    <th>Product</th>
                                    <th class="text-end">Price</th>
                                    <th class="text-end">Quantity</th>
                                    <th class="text-end">Sale Count</th>
                                    <th class="text-end">Amount (Rs)</th>
                                    <th style="width: 140px;">%</th>
                                </tr>
                                </thead>
                                <tbody id="product-summary-body">
                                @foreach($productWiseSales as $row)
                                    @php
                                        $p = $products[$row->icode] ?? null;
                                        $name = $p->ITMNAME ?? $row->icode;
                                        $rate = $p->SRATE ?? $row->SRATE;
                                        $qtyL = $row->total_qty / 100;
                                        $amtR = $row->total_amount / 100;
                                        $saleCount = $row->sale_count;
                                        $pct = $totalAmt > 0 ? round(($amtR / $totalAmt) * 100, 2) : 0;



                                    @endphp
                                    <tr>
                                        <td>{{ $name }}</td>
                                        <td class="text-end">{{ $rate/100 }}</td>
                                        <td class="text-end">Ltrs. {{ number_format($qtyL, 2) }}</td>
                                        <td class="text-end">{{ $saleCount }}</td>
                                        <td class="text-end">{{ number_format($amtR, 2) }}</td>
                                        {{--                                        <td>--}}
                                        {{--                                            <div class="progress" style="height: 8px;">--}}
                                        {{--                                                <div class="progress-bar" role="progressbar" title="{{ $pct }}"--}}
                                        {{--                                                     style="width: {{ $pct }}%"--}}
                                        {{--                                                     aria-valuenow="{{ $pct }}" aria-valuemin="0" aria-valuemax="100">--}}
                                        {{--                                                </div>--}}
                                        {{--                                            </div>--}}
                                        {{--                                        </td>--}}
                                        <td>
                                            <div class="progress" style="height: 20px; position: relative;">
                                                <div class="progress-bar" role="progressbar"
                                                     style="width: {{ $pct }}%; background-color: #007bff;"
                                                     aria-valuenow="{{ $pct }}" aria-valuemin="0" aria-valuemax="100">
                                                </div>
                                                <span style="
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            font-size: 12px;
            font-weight: bold;
            color: {{ $pct > 50 ? 'white' : 'black' }};
        ">
            {{ $pct }}%
        </span>
                                            </div>
                                        </td>

                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>


                        <!-- Payment Methods Table -->
                        <div class="table-responsive w-50" style="float: right">
                            <table class="table table-sm mb-0 align-middle col-6">
                                <thead>
                                <tr class="text-muted">
                                    <th>Payment Method</th>
                                    <th class="text-end">Amount (Rs)</th>
                                </tr>
                                </thead>
                                <tbody id="payment-summary-body">
                                {{--                                @foreach($paymentMethodSales as $p)--}}

                                <tr>
                                    <td></td>
                                    <td class="text-end" id="payment-total"></td>
                                </tr>
                                {{--                                @endforeach--}}
                                {{--                                @foreach($paymentMethodSales as $p)--}}

                                {{--                                    <tr>--}}
                                {{--                                        <td>{{ $p->payment_method }}</td>--}}
                                {{--                                        <td class="text-end">{{ number_format($p->total_amount, 2) }}</td>--}}
                                {{--                                    </tr>--}}
                                {{--                                @endforeach--}}
                                </tbody>
                                <tfoot>
                                {{--                                <tr class="fw-bold">--}}
                                {{--                                    <td>Total</td>--}}
                                {{--                                    <td id="payment-total" class="text-end">--}}
                                {{--                                        {{ number_format($paymentMethodSales->sum('total_amount'), 2) }}--}}
                                {{--                                    </td>--}}
                                {{--                                </tr>--}}
                                </tfoot>
                            </table>
                        </div>





                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Shift History Modal -->
    <div class="modal fade" id="historyModal" tabindex="-1" aria-labelledby="historyModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="historyModalLabel">Shift History</h5>
                    <button type="button" class="btn-close btn btn-light" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-3">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-bordered mb-0">
                            <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Date & Time</th>
                                <th>Rate</th>
                                <th>Quantity (Ltrs)</th>
                                <th>Amount (Rs)</th>
                                <th>Payment Method</th>
                                <th>Customer Name</th>
                                <th>Print</th>
                            </tr>
                            </thead>
                            <tbody id="shift_sale_data">
                            <!-- Shift rows will be appended here -->
                            </tbody>
                        </table>
                    </div>
                    {{--                    <div class="d-flex justify-content-center mt-3">--}}
                    {{--                        <button id="loadMoreBtn" class="btn btn-success">Load More</button>--}}
                    {{--                    </div>--}}
                    <div class="d-flex justify-content-center align-items-center gap-2 mt-3" id="pagination">
                        <!-- Pagination buttons will be injected here -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        #historyModal .modal-header {
            border-bottom: none;
        }
        #historyModal table {
            font-size: 0.9rem;
        }
        #loadMoreBtn {
            width: 150px;
            font-weight: bold;
        }
        .print-btn {
            background: linear-gradient(135deg, #007bff, #0056b3);
            border: none;
            color: #fff;
            font-size: 14px;
            padding: 6px 14px;
            border-radius: 30px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.15);
            transition: 0.3s;
        }
        .print-btn:hover {
            background: linear-gradient(135deg, #0056b3, #003f7f);
            transform: translateY(-1px);
        }
        .print-btn i {
            font-size: 16px;
        }

    </style>

    <!-- Edit Display Modal -->
    <div class="modal fade" id="displayModal" tabindex="-1" aria-labelledby="displayModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content rounded-3 shadow">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="displayModalLabel">Assign Display Number</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="displayForm">
                        @csrf
                        <input type="hidden" id="pump_id" name="pump_id">

                        <div class="mb-3">
                            <label for="pump_no" class="form-label">Pump / Nozzle</label>
                            <input type="text" id="pump_no" class="form-control" readonly>
                        </div>

                        <div class="mb-3">
                            <label for="display_name" class="form-label">Display Number / Custom Name</label>
                            <input type="text" id="display_name" name="display_name" class="form-control" placeholder="e.g. Nozzle 1A">
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection


@section('scripts')
    <script src="https://unpkg.com/infinite-scroll@4.0.1/dist/infinite-scroll.pkgd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-infinitescroll/4.0.1/infinite-scroll.pkgd.min.js"></script>
    <script src="/assets/js/highcharts.js"></script>
    <script>
        const evtSource = new EventSource("/pump-stream");

        evtSource.onmessage = function(event) {
            const data = JSON.parse(event.data);

            data.forEach(row => {
                const pumpCard = document.querySelector(`.pump-card[data-pumpid="${row.PUMPID}"]`);
                if (!pumpCard) return;

                // 🔹 Image update
                const nozzleImg = pumpCard.querySelector(".nozzle-image");
                if (nozzleImg) {
                    nozzleImg.src = row.image;
                }
                const productNameDiv = pumpCard.querySelector(".product-name-overlay");
                if (productNameDiv) {
                    // display_name prefer karega, fallback product_name
                    productNameDiv.textContent = row.display_name || row.product_name || "";
                }

                // 🔹 Last Sale
                const lastQty = pumpCard.querySelector(".last_sale .qty");
                const lastAmt = pumpCard.querySelector(".last_sale .amt");
                if (lastQty) lastQty.textContent = (row.qty / 100).toFixed(2);
                // if (lastQty) lastQty.textContent = (row.qty / 100);
                if (lastAmt) lastAmt.textContent = Math.round(row.amt / 100);
                // if (lastAmt) {
                //     console.log(row.amt);
                //     let amount = Math.round(row.amt / 100);
                //     lastAmt.textContent = amount.toLocaleString('en-IN');
                // }


                // 🔹 Total Sale
                // const totalQty = pumpCard.querySelector(".total_sale .qty");
                // const totalAmt = pumpCard.querySelector(".total_sale .amt");
                // const displayQty = Math.ceil(row.shift_qty / 100);
                // const displayAmount = Math.round(displayQty * (row.rate / 100));
                //
                // if (totalQty) totalQty.textContent = displayQty;
                // if (totalAmt) totalAmt.textContent = displayAmount;
                //
                // // 🔹 Rate
                // const rateEl = pumpCard.querySelector(".rate + .amt");
                // if (rateEl) rateEl.textContent = (row.rate / 100).toFixed(2);
                // 🔹 Total Sale
                const totalQty = pumpCard.querySelector(".total_sale .qty");
                const totalAmt = pumpCard.querySelector(".total_sale .amt");

                const shiftQty = row.shift_total_qty ?? row.shift_qty ?? 0;
                const shiftRate = row.shift_rate ?? row.rate ?? 0;

                const displayQty = shiftQty / 100;
                const effectiveRate = row.effective_rate ?? row.shift_rate ?? row.rate ?? 0;
                // const displayAmount = Math.round(displayQty * (shiftRate / 100));
                const displayAmount = Math.round(displayQty * (effectiveRate / 100));
                if (totalQty) totalQty.textContent = displayQty;
                if (totalAmt) totalAmt.textContent = displayAmount;

// 🔹 Rate

//                 const rateEl = pumpCard.querySelector(".rate + .amt");
//                 if (rateEl) rateEl.textContent = (shiftRate / 100).toFixed(2);
                const rateEl = pumpCard.querySelector(".rate + .amt");
                if (rateEl) rateEl.textContent = (effectiveRate / 100).toFixed(2);

            });
        };
    </script>
    <script>
        let summarySource = null;

        function startSummaryStream() {

            // 🔁 Purana connection band karo
            if (summarySource) {
                summarySource.close();
            }

            summarySource = new EventSource("/summary-stream");

            summarySource.onmessage = function (event) {
                const data = JSON.parse(event.data);

                /* ------------------------------
                   PRODUCT-WISE TABLE
                ------------------------------ */
                let productRows = "";
                let totalProductAmount = 0;

                data.products.forEach(p => {
                    const rate = Number(p.rate);
                    const qty  = Number(p.qty);
                    const amount = qty * rate;
                    totalProductAmount += amount;

                    productRows += `
<tr>
    <td>${p.name}</td>
    <td class="text-end">${rate.toFixed(2)}</td>
    <td class="text-end">Ltrs. ${qty.toFixed(2)}</td>
    <td class="text-end">${p.sale_count}</td>
    <td class="text-end">${amount.toLocaleString('en-US', {minimumFractionDigits:2})}</td>
    <td>
        <div class="progress" style="height:20px; position:relative;">
            <div class="progress-bar bg-success" style="width:${p.percentage}%"></div>
            <span style="
                position:absolute;
                left:50%;
                top:50%;
                transform:translate(-50%,-50%);
                font-size:12px;
                font-weight:bold;
                color:${p.percentage > 50 ? 'white' : 'black'};
            ">
                ${p.percentage}%
            </span>
        </div>
    </td>
</tr>`;
                });

                document.querySelector("#product-summary-body").innerHTML = productRows;

                /* ------------------------------
                   PAYMENT METHODS (CORRECT LOGIC)
                ------------------------------ */
                let paymentRows = "";
                let cardAmount = 0;

                // 1️⃣ Card ka total
                data.payments.forEach(pm => {
                    if (pm.payment_method.toLowerCase().includes('card')) {
                        cardAmount += Number(pm.total_amount);
                    }
                });

                // 2️⃣ Rows generate
                data.payments.forEach(pm => {
                    let displayAmount = Number(pm.total_amount);

                    // ✅ Cash = Total Product - Card
                    if (pm.payment_method.toLowerCase().includes('cash')) {
                        displayAmount = totalProductAmount - cardAmount;
                    }

                    paymentRows += `
<tr>
    <td>${pm.payment_method}</td>
    <td class="text-end">${displayAmount.toFixed(2)}</td>
</tr>`;
                });

                document.querySelector("#payment-summary-body").innerHTML = paymentRows;

                /* ------------------------------
                   DEBUG (optional)
                ------------------------------ */
                console.log("Total Products:", totalProductAmount.toFixed(2));
                // console.log("Card Amount:", cardAmount.toFixed(2));
                // console.log("Cash Amount:", (totalProductAmount - cardAmount).toFixed(2));
            };

            summarySource.onerror = function () {
                console.warn("SSE connection lost, retrying...");
                summarySource.close();
            };
        }

        // ▶️ Start first time
        startSummaryStream();

        // 🔄 Refresh stream every 45 seconds
        setInterval(() => {
            startSummaryStream();
        }, 5000);
    </script>




    {{--    <script>--}}
    {{--        const summarySource = new EventSource("/summary-stream");--}}

    {{--        summarySource.onmessage = function(event) {--}}
    {{--            const data = JSON.parse(event.data);--}}

    {{--            // Product-wise table update--}}
    {{--            let productRows = "";--}}
    {{--            data.products.forEach(p => {--}}
    {{--                productRows += `--}}
    {{--            <tr>--}}
    {{--            <td>${p.name}</td>--}}
    {{--            <td class="text-end">${Number(p.rate).toFixed(2)}</td>--}}
    {{--            <td class="text-end">Ltrs. ${Number(p.qty).toFixed(2)}</td>--}}
    {{--            <td class="text-end">${p.sale_count}</td>--}}
    {{--            <td class="text-end">${Number(p.amount).toFixed(2)}</td>--}}
    {{--            <td>--}}
    {{--               <div class="progress" style="height: 20px;">--}}
    {{--                   <div class="progress-bar bg-success" role="progressbar"--}}
    {{--                        style="width: ${Number(p.percentage)}%;"--}}
    {{--                        aria-valuenow="${Number(p.percentage)}" aria-valuemin="0" aria-valuemax="100">--}}
    {{--                       ${Number(p.percentage)}%--}}
    {{--                   </div>--}}
    {{--               </div>--}}
    {{--            </td>--}}
    {{--        </tr>--}}
    {{--        `;--}}
    {{--            });--}}

    {{--            const productBody = document.querySelector("#product-summary-body");--}}
    {{--            if (productBody) productBody.innerHTML = productRows;--}}

    {{--            // Payment methods table update--}}
    {{--            let paymentRows = "";--}}
    {{--            let totalAmount = 0;--}}
    {{--            data.payments.forEach(pm => {--}}
    {{--                paymentRows += `--}}
    {{--            <tr>--}}
    {{--                <td>${pm.payment_method}</td>--}}
    {{--                <td class="text-end">${pm.total_amount.toFixed(2)}</td>--}}
    {{--            </tr>--}}
    {{--        `;--}}
    {{--                totalAmount += pm.total_amount;--}}
    {{--            });--}}

    {{--            const paymentBody = document.querySelector("#payment-summary-body");--}}
    {{--            if (paymentBody) paymentBody.innerHTML = paymentRows;--}}

    {{--            const paymentTotal = document.querySelector("#payment-total");--}}
    {{--            if (paymentTotal) paymentTotal.textContent = totalAmount.toFixed(2);--}}
    {{--        };--}}


    {{--    </script>--}}

    {{--    <script>--}}
    {{--        let activeShift = null;--}}
    {{--        let pageNo = 1;--}}
    {{--        let $tableBody = $('#shift_sale_data'); // define globally--}}

    {{--        // Open modal and load first page--}}
    {{--        $('.openSaleHistory').click(function() {--}}
    {{--            activeShift = $(this).data('pumpid');--}}
    {{--            if(!activeShift) {--}}
    {{--                alert("No active shift for this pump!");--}}
    {{--                return;--}}
    {{--            }--}}

    {{--            pageNo = 1;--}}
    {{--            $tableBody.html('');      // clear previous data--}}
    {{--            $('#loadMoreBtn').show(); // show button--}}

    {{--            loadShiftData();--}}
    {{--            $('#historyModal').modal('show');--}}
    {{--        });--}}

    {{--        // Load more button--}}
    {{--        $('#loadMoreBtn').click(function() {--}}
    {{--            loadShiftData();--}}
    {{--        });--}}

    {{--        function loadShiftData() {--}}
    {{--            fetch(`/api/shiftSaleData?shift_id=${activeShift}&page=${pageNo}`)--}}
    {{--                .then(res => res.json())--}}
    {{--                .then(data => {--}}
    {{--                    if(!data.data || data.data.length === 0) {--}}
    {{--                        $('#loadMoreBtn').hide();--}}
    {{--                        return;--}}
    {{--                    }--}}

    {{--                    data.data.forEach(sale => {--}}
    {{--                        const row = `--}}
    {{--                    <tr>--}}
    {{--                        <td>${sale.id}</td>--}}
    {{--                        <td>${sale.tdate}</td>--}}
    {{--                        <td>${(sale.rate/100).toFixed(2)}</td>--}}
    {{--                        <td>${(sale.qty/100).toFixed(2)}</td>--}}
    {{--                        <td>${(sale.amt/100).toFixed(2)}</td>--}}
    {{--                        <td>${sale.pMethod}</td>--}}
    {{--                        <td>${sale.customer}</td>--}}
    {{--                        <td>--}}
    {{--                            <button onclick="printReceipt(${sale.id}, ${sale.shift_table_id})"--}}
    {{--                                    class="lni lni-printer d-flex align-items-center gap-3  rounded-pill px-3">--}}
    {{--<!--                                <i class="lni lni-printer fs-8"></i>-->--}}
    {{--                            </button>--}}
    {{--<!--// <button onclick="printReceipt(${sale.id}, ${sale.shift_table_id})" class="btn-outline-primary lni lni-printer"></button>-->--}}
    {{--                        </td>--}}
    {{--                    </tr>--}}
    {{--                `;--}}
    {{--                        $tableBody.append(row);--}}
    {{--                    });--}}

    {{--                    pageNo++;--}}
    {{--                })--}}
    {{--                .catch(err => console.error(err));--}}
    {{--        }--}}

    {{--        function printReceipt(saleId, shift_table_id) {--}}
    {{--            fetch(`/api/printDuplicate/${shift_table_id}/${saleId}`).then(res => {--}}
    {{--                alert("Print sent!");--}}
    {{--            });--}}
    {{--        }--}}

    {{--    </script>--}}

    <script>
        let activeShift = null;
        let pageNo = 1;
        let perPage = 10;
        let totalPages = 1;
        let $tableBody = $('#shift_sale_data');
        let $pagination = $('#pagination');

        // Open modal and load data
        $('.openSaleHistory').click(function() {
            activeShift = $(this).data('pumpid');
            if(!activeShift){
                alert("No active shift for this pump!");
                return;
            }
            pageNo = 1;
            $tableBody.html('');
            loadShiftData();
            $('#historyModal').modal('show');
        });

        function loadShiftData() {
            fetch(`/api/shiftSaleData?shift_id=${activeShift}&page=${pageNo}&per_page=${perPage}`)
                .then(res => res.json())
                .then(data => {
                    $tableBody.html('');

                    if(!data.data || data.data.length === 0){
                        $pagination.html('');
                        return;
                    }

                    data.data.forEach(sale => {
                        const row = `
                    <tr>
                        <td>${sale.id}</td>
                        <td>${sale.tdate}</td>
                        <td>${(sale.rate/100).toFixed(2)}</td>
                        <td>${(sale.qty/100).toFixed(2)}</td>
                        <td>${(sale.amt/100).toFixed(2)}</td>
                        <td>${sale.pMethod}</td>
                        <td>${sale.customer}</td>
                        <td>
                            <button onclick="printReceipt(${sale.id}, ${sale.shift_table_id})"
                                class="lni lni-printer d-flex align-items-center gap-3 rounded-pill px-3">
                                Print
                            </button>
                        </td>
                    </tr>
                `;
                        $tableBody.append(row);
                    });

                    // Update pagination
                    totalPages = data.last_page || 1;
                    pageNo = data.current_page || 1;
                    renderPagination();
                })
                .catch(err => console.error(err));
        }

        function renderPagination() {
            let html = '';

            // Previous button
            html += `<button class="btn btn-secondary" ${pageNo === 1 ? 'disabled' : ''} onclick="goPage(${pageNo-1})">Previous</button>`;

            // Show page numbers (max 5 pages)
            let start = Math.max(1, pageNo - 2);
            let end = Math.min(totalPages, start + 4);
            for(let i = start; i <= end; i++){
                html += `<button class="btn ${i===pageNo ? 'btn-primary' : 'btn-outline-primary'} mx-1" onclick="goPage(${i})">${i}</button>`;
            }

            // Next button
            html += `<button class="btn btn-secondary" ${pageNo === totalPages ? 'disabled' : ''} onclick="goPage(${pageNo+1})">Next</button>`;

            $pagination.html(html);
        }

        function goPage(num){
            if(num < 1 || num > totalPages) return;
            pageNo = num;
            loadShiftData();
        }

        function printReceipt(saleId, shift_table_id) {
            fetch(`/api/printDuplicate/${shift_table_id}/${saleId}`)
                .then(res => {
                    alert("Print sent!");
                });
        }
    </script>
@endsection
