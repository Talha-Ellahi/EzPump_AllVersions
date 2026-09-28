@extends('layouts.app')
<style>
    .modal-fullscreen {
        width: 100vw !important;
        max-width: none !important;
        height: 90% !important;
        margin: 0 !important;
    }

    @media (min-width: 1200px) {
        .col-item-8 {
            width: 12.5%;
        }
    }

    #summary_total_sale {
        font-weight: bold;
    }

    .singleCard .card-img-top {
        width: 100%;
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
        #summary_total_sale {
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
        display: flex;
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
        // Define an array of background colors
        $backgroundColors = ['bg-primary', 'bg-danger', 'bg-success', 'bg-dark'];
        // Initialize a counter variable
        $colorIndex = 0;

    @endphp

    <div class="wrapper">

        <!--start page wrapper -->

        <div class="page-content">


            <div class="row">
                <div class="mx-auto">

                    <div class="card border-top border-0 border-4 border-danger">

                        <div class="card-body ">
                            @php
                                use Carbon\Carbon;

                                $shifts = DB::select("SELECT * FROM shift LIMIT 1");

                                if (!empty($shifts)) {
                                    $shift = $shifts[0];  // Access the first element
                                }
                                if ($shift) {
                                    $formattedStartDate = Carbon::parse($shift->start_date)->format('Y-m-d H:i:s'); // Adjust the format as needed
                                } else {
                                    $formattedStartDate = 'No shift found';
                                }
                            @endphp
                            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                                <div class="pe-3">
                                    <h5 class="mb-0 text-uppercase"><b>Shift Start Date & Time:
                                        </b>{{ $formattedStartDate }}
                                    </h5>
                                </div>


                            </div>
                            <hr>
                            <br>
                            <div id="pump-cards" class="row "></div>

                            <div class="pe-3">
                                <h5 class="mb-0 text-uppercase"><b>Short Summary </b></h5>
                            </div>
                            <hr>
                            <div class="cs-container">
                                <div class="cs-invoice cs-style1">
                                    <div class="cs-invoice_in" id="download_section">
                                        <div class="cs-invoice_head cs-type1 cs-mb25">
                                            <div class="cs-invoice_left">
                                                <p class="cs-invoice_number cs-primary_color cs-mb0 cs-f16">
                                                    <b class="cs-primary_color">Shift Date:</b> <span
                                                        id="current-date">{{$formattedStartDate}}</span>
                                                </p>
                                            </div>
                                            <div class="cs-invoice_right cs-text_right">
                                                <div class="cs-logo cs-mb5"><img class="logo"
                                                        src="assets/images/ezpump_dashboard.png" alt="Logo"></div>
                                            </div>
                                        </div>

                                        <div class="cs-table cs-style2">
                                            <div class="cs-round_border">
                                                <div class="cs-table_responsive">
                                                    <table class="table new_style_table table-striped">
                                                        <thead>
                                                            <tr class="cs-focus_bg">
                                                                <th class="cs-width_2 cs-semi_bold cs-primary_color">
                                                                    Product Name</th>
                                                                <th
                                                                    class="cs-width_2 cs-text_right cs-semi_bold cs-primary_color">
                                                                    Price
                                                                </th>
                                                                <th
                                                                    class="cs-width_2 cs-text_right cs-semi_bold cs-primary_color">
                                                                    Quantity</th>
                                                                <th
                                                                    class="cs-width_2 cs-text_right cs-semi_bold cs-primary_color">
                                                                    Sale Count</th>
                                                                <th
                                                                    class="cs-width_2 cs-text_right cs-semi_bold cs-primary_color">
                                                                    Percentage</th>
                                                                <th
                                                                    class="cs-width_3 cs-semi_bold cs-primary_color cs-text_right">
                                                                    Total</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="product-stats">


                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="cs-invoice_footer">
                                                <div class="cs-left_footer cs-mobile_hide"></div>
                                                <div class="cs-right_footer">
                                                    <table class="table">
                                                        <tbody>
                                                            <tr class="cs-border_none">
                                                                <td
                                                                    class="cs-width_3 cs-border_top_0 cs-bold cs-f16 cs-primary_color">
                                                                    Total Sale</td>
                                                                <td id="summary_total_sale"
                                                                    class="cs-width_3 cs-border_top_0 cs-bold cs-f16 cs-primary_color cs-text_right">
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- .cs-note -->
                                    </div>

                                </div>
                            </div>


                        </div>
                        </form>


                        <!--end row-->

                    </div>
                </div>
                <!--end page wrapper -->

            </div>
            <!--end wrapper-->

        </div>
    </div>

@endsection
@section('scripts')
    <div class="modal" id="historyModal" tabindex="-1" aria-labelledby="historyModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title" id="historyModalLabel">Shift Management</h2>
                    <button type="button" class="close btn btn-primary btn-lg" data-dismiss="modal" aria-label="Close">
                        &times; </button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">

                        <h2 class="my-4"></h2>
                        <table class="table new_style_table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>DateTime</th>
                                    <th>Rate</th>
                                    <th>Quantity</th>
                                    <th>Amount</th>
                                    <th>Mode of payment</th>
                                    <th>Customer Name</th>
                                    <th>Print</th>
                                </tr>
                            </thead>
                            <tbody id="shift_sale_data">
                                <!-- Rows will be appended here -->
                            </tbody>
                        </table>
                        <div id="pagination" class="">
                            <button class="shift_sale_data btn btn-dark next-button">Next Button</button>
                            <!-- Initial next page link -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="shiftModal" tabindex="-1" aria-labelledby="shiftModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="shiftModalLabel">Shift Management</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="shiftForm">
                        <input type="hidden" name="id" id="id">
                        <input type="hidden" name="start_date" id="start_date">
                        <input type="hidden" name="end_date" id="end_date">
                        <input type="hidden" name="pump_id" id="pump_id">
                        <input type="hidden" class="form-control" id="status" name="status">

                        <div class="form-group">
                            <label for="opening_fuel"> System Opening Fuel</label>
                            <input type="number" class="form-control" id="opening_fuel" name="opening_fuel">
                        </div>
                        <div class="form-group">
                            <label for="opening_fuel"> Mannual Opening Fuel</label>
                            <input type="number" class="form-control" id="opening_fuel1" name="opening_fuel">
                        </div>
                        <div class="form-group">
                            <label for="closing_fuel">System Closing Fuel</label>
                            <input type="number" class="form-control" id="closing_fuel" name="closing_fuel">
                        </div>
                        <div class="form-group">
                            <label for="closing_fuel">Mannual Closing Fuel</label>
                            <input type="number" class="form-control" id="closing_fuel1" name="closing_fuel">
                        </div>
                        <div class="form-group">
                            <label for="opening_balance">Opening Balance</label>
                            <input type="number" class="form-control" id="opening_balance" name="opening_balance">
                        </div>
                        <div class="form-group">
                            <label for="closing_balance">Closing Balance</label>
                            <input type="number" class="form-control" id="closing_balance" name="closing_balance">
                        </div>
                        <div class="form-group">
                            <label for="adjustments">Adjustments</label>
                            <input type="number" class="form-control" id="adjustments" name="adjustments">
                        </div>
                        <div class="form-group">
                            <label for="total_qty">Total Quantity</label>
                            <input type="number" class="form-control" id="total_qty" name="total_qty" readonly>
                        </div>
                        <div class="form-group">
                            <label for="rate">Rate</label>
                            <input type="number" class="form-control" id="rate" name="rate" readonly>
                        </div>
                        <div class="form-group">
                            <label for="is_changed">Rate Changed during shift?</label>
                            <b for="is_changed">Yes</b>
                        </div>
                        <div class="form-group">
                            <label for="new_rate">New Rate</label>
                            <input type="number" class="form-control" id="new_rate" name="new_rate" readonly>
                        </div>
                        <div class="form-group">
                            <label for="changed_fuel_balance">Changed Fuel Balance</label>
                            <input type="number" class="form-control" id="changed_fuel_balance" name="changed_fuel_balance"
                                readonly>
                        </div>

                        <div class="form-group d-none">
                            <label for="status">Status</label>
                        </div>
                        <div class="form-group">
                            <label for="cashier_name">Cashier Name</label>
                            <input type="text" class="form-control" id="cashier_name" name="cashier_name">
                        </div>
                        <div class="form-group d-none">
                            <label for="last_sale_id">Last Sale ID</label>
                            <input type="number" class="form-control" id="last_sale_id" name="last_sale_id">
                        </div>
                        <button type="submit" class="btn btn-primary" id="submitButton">Save</button>
                    </form>
                    <button class="btn btn-danger mt-3" id="closeShiftButton" style="display:none;">Close Shift</button>
                </div>
            </div>
        </div>
    </div>
    <script src="https://unpkg.com/infinite-scroll@4.0.1/dist/infinite-scroll.pkgd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-infinitescroll/4.0.1/infinite-scroll.pkgd.min.js"></script>
    <script src="/assets/js/highcharts.js"></script>

    <script>
        let pumpCards = [];
        let activeSaleHistoryPump = null;
        let saleHistoryPageNo = 1;
        let saleHistoryData = [];
        let saleHistoryHasMore = true;
        let saleHistoryLoading = false;
        let activeShiftData = null;
        let employees = [];
        let shiftForm = {
            id: "",
            end_date: "",
            status: "",
            start_date: "",
            pump_id: "",
            opening_fuel: "",
            opening_fuel_manual: "",
            closing_fuel: "",
            closing_fuel_manual: "",
            opening_balance: "",
            closing_balance: "",
            manual_opening_dip: "",
            manual_closing_dip: "",
            tank_id: "",
            total_qty: "",
            rate: "",
            adjustments: "",
            is_changed: "",
            new_rate: "",
            changed_fuel_balance: "",
            cashier_name: "",
            cashier_id: "",
            last_sale_id: "",
            bIsUpdated: false,
        };
        let shiftResponse = {
            customers: [],
            paymentMethods: [],
        };

        const pumpImages = {
            1: "assets/images/Nozzle_one.png",
            2: "assets/images/Nozzle_three.png",
        };

        const pumpICodeImages = {
            1: "assets/images/Nozzle_green.png",
            2: "assets/images/Nozzle_Blue.png",
            3: "assets/images/Nozzle_yellow.png",
            4: "assets/images/Nozzle_black.png",
        };

        function createPumpCard(pump, state) {
            let image;

            if (state !== undefined && state.STS !== undefined) {
                image = pumpImages[state.STS];
            }

            if (!image && pump.ICODE !== undefined) {
                image = pumpICodeImages[pump.ICODE];
            }

            return `
                <div class="col-lg-2 singleCard">
                        <div class="card pump-card" data-pumpid="${pump.POS_ID}"  data-id="${pump.id}">
                            <img src="${image}" class="card-img-top" alt="Nozzle" style="max-height: 150px; width: fit-content!important">
                            <div class="">
                                <div class="position-absolute top-0 end-0 m-3 product-discount"><span class=""><b>${pump.POS_ID}</b></span></div>
                            </div>
                            <div class="card-body">

                                <div class="d-flex align-items-center">
                                    <div class="last_sale">
                                        <p class="mb-0 ms-auto">Last Sale:</p> <b class="mb-0 ms-auto qty">${pump.QtyD}</b> <span>Ltrs</span> -- Rs. <b class="mb-0 ms-auto amt">${pump.AmtD}</b>
                                    </div>

                                </div>
                                <div class="d-flex align-items-center">
                                    <div class="total_sale">
                                        <p class="mb-0 ms-auto">Total Sale: </p><b class="mb-0 ms-auto qty">${pump.QtyD}</b> <span>Ltrs</span> -- Rs. <b class="mb-0 ms-auto amt">${pump.AmtD.toFixed(2)}</b>
                                    </div>

                                </div>
                                <div class="d-flex align-items-center">
                                    <div class="cursor-pointer rate">
                                        <p class="mb-0 ms-auto"><strong>Rate.</strong> <span class="mb-0 ms-auto rate">${pump.RateD}</span></p>
                                    </div>

                                </div>
                                <div class="d-flex align-items-center d-none">
                                    <p class="mb-0 ms-auto"><button type="button" class="openSaleHistory btn btn-info" data-id="${pump.id}" value="History">History</button></input></p>
                                  <!--  <p class="mb-0 ms-auto"><input type="button" class="shiftButton" data-id="${pump.id}" value="Shift"></input></p> -->
                                </div>
                                <div style="display: flex; flex-direction: row; justify-content: space-between;">
                                     <button class=" btn btn-primary ms-1 me-1 sales-button" data-pumpid="${pump.id}">Sales</button>
                                     <button class=" btn btn-info ms-1 me-1 shift-button" data-pumpid="${pump.id}">Shift</button>
                                </div>
                                </div>
                            </div>
                        </div>
                        </div>
                    `;
        }

        function updatePumpCard(state) {
            let pumpCardJson = pumpCards.find((pumpCard) => state.PUMPID == pumpCard.id)
            const pumpCard = $(`.pump-card[data-pumpid=${state.PUMPID}]`);

            if (pumpCard.length) {
                if (pumpImages[state.STS]) {
                    pumpCard.find('.card-img-top').attr('src', pumpImages[state.STS] || pumpICodeImages[pumpCardJson
                        .ICODE]);
                    pumpCard.find('.last_sale').find('.qty').last().text((state.qty / 100).toFixed(2));
                    pumpCard.find('.last_sale').find('.amt').last().text((state.amt / 100).toFixed(0));
                    pumpCard.find('.total_sale').find('.qty').last().text((state.shift_qty / 100).toFixed(0));
                    pumpCard.find('.total_sale').find('.amt').last().text((state.total_amount / 100).toFixed(0));
                    pumpCard.find('.rate  .rate').text((state.rate / 100).toFixed(2));

                } else {
                    pumpCard.find('.card-img-top').attr('src', pumpICodeImages[pumpCardJson.ICODE]);
                    pumpCard.find('.last_sale').find('.qty').last().text((state.qty / 100).toFixed(2));
                    pumpCard.find('.last_sale').find('.amt').last().text((state.amt / 100).toFixed(0));
                    pumpCard.find('.total_sale').find('.qty').last().text((state.shift_qty / 100).toFixed(0));
                    pumpCard.find('.total_sale').find('.amt').last().text((state.total_amount / 100).toFixed(0));

                    pumpCard.find('.rate .rate').text(state.rate / 100);

                }
            }
        }

        function fetchInitialData() {
            $.ajax({
                url: '/api/pumps',
                method: 'GET',
                success: function (data) {
                    pumpCards = data;
                    pumpCardEls = data.map(pump => createPumpCard(pump)).join('');
                    $('#pump-cards').html(pumpCardEls);
                    // Attach event listeners using delegation for dynamically added buttons
                    $('#pump-cards').off('click', '.sales-button').on('click', '.sales-button', function() {
                        const pumpId = $(this).data('pumpid');
                        openSaleHistoryFromBlade(pumpId);
                    });
                    $('#pump-cards').off('click', '.shift-button').on('click', '.shift-button', function() {
                        const pumpId = $(this).data('pumpid');
                        openShiftInfoFromBlade(pumpId);
                    });
                },
                error: function (error) {
                    console.error('Error fetching initial data:', error);
                }
            });
        }

        function updatePumpStates() {
            $.ajax({
                url: '/api/pumpstates',
                method: 'GET',
                success: function (data) {
                    data.forEach(state => updatePumpCard(state));
                },
                error: function (error) {
                    console.error('Error updating pump states:', error);
                }
            });
        }

        function updateStats() {
            $.ajax({
                url: '/api/stats',
                method: 'GET',
                success: function (response) {
                    let stats = response.productWiseSales;
                    let products = response.products;
                    let productDetails = {};
                    products.forEach(product => {
                        productDetails[product.ICODE] = product;
                    });

                    let totalSalesAmount = stats.reduce((total, stat) => total + parseFloat(stat.total_amount), 0);

                    let rows = stats.map(stat => {
                        let product = productDetails[stat.ICODE];
                        let percentage = ((parseFloat(stat.total_amount) / totalSalesAmount) * 100).toFixed(2) + '%';
                        return `
                        <tr>
                            <td class="cs-width_2">${product.ITMNAME}</td>
                            <td class="cs-width_2 cs-text_right">Rs. ${(product.SRATE / 100).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>
                            <td class="cs-width_2  cs-text_right">Ltrs. ${(parseFloat(stat.total_qty / 100)).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>
                            <td class="cs-width_2 cs-text_right"> ${(parseFloat(stat.sale_count).toLocaleString('en-US', { minimumFractionDigits: 0, maximumFractionDigits: 0 }))}</td>
                            <td class="cs-width_2 cs-text_right">${percentage}</td>
                            <td class="cs-width_3 cs-text_right cs-primary_color cs-semi_bold">${parseFloat(stat.total_amount / 100).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>
                        </tr>
                    `;
                    }).join('');
                    $("#summary_total_sale").text((totalSalesAmount / 100).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
                    $('#product-stats').html(rows);

                    let productPieData = response.productPieChart.map(item => ({
                        name: productDetails[item.label].ITMNAME,
                        y: parseFloat(item.value / 100)
                    }));
                    renderPieChart('productPieChart', 'Product Sales Distribution', productPieData);

                    let paymentPieData = response.paymentPieChart.map(item => ({
                        name: item.label,
                        y: parseFloat(item.value)
                    }));
                    renderPieChart('paymentPieChart', 'Payment Method Distribution', paymentPieData);
                },
                error: function (error) {
                    console.log('Error fetching data:', error);
                }
            });
        }

        function renderPieChart(containerId, title, data) {
            Highcharts.chart(containerId, {
                chart: {
                    type: 'pie'
                },
                title: {
                    text: title
                },
                series: [{
                    name: 'Share',
                    data: data
                }]
            });
        }

        // --- Sales History Functions (Adapted from ShiftChange.vue) ---
        function openSaleHistoryFromBlade(pumpId) {
            activeSaleHistoryPump = pumpCards.find(p => p.id === pumpId);
            if (!activeSaleHistoryPump) {
                console.error('Pump not found for ID:', pumpId);
                return;
            }

            saleHistoryPageNo = 1;
            saleHistoryData = [];
            saleHistoryHasMore = true;
            saleHistoryLoading = false;

            $('#historyModal').modal('show');
            $('#shift_sale_data').html(''); // Clear previous data

            // Re-initialize infinite scroll for the new pump
            $('#shift_sale_data').infiniteScroll('destroy'); // Destroy previous instance
            $('#shift_sale_data').data('infinitescroll', null); // Clear data

            $('#shift_sale_data').infiniteScroll({
                itemSelector: "#shift_sale_data tr",
                loading: {
                    finishedMsg: 'No more items to load.',
                    img: 'https://i.imgur.com/6RMhx.gif'
                },
                checkLastPage: true,
                responseBody: 'json',
                append: false,
                loadOnScroll: false,
                history: false,
                appendCallback: false,
                path: function () {
                    return `/api/shiftSaleData?pump_id=${activeSaleHistoryPump.id}&page=${saleHistoryPageNo}&shift_id=${activeSaleHistoryPump.id}`;
                },
                button: '.shift_sale_data.next-button',
            });

            $('#shift_sale_data').on('load.infiniteScroll', function (event, data) {
                saleHistoryPageNo++;
                if (data.next_page_url) {
                    $('.shift_sale_data.next-button').show();
                } else {
                    $('.shift_sale_data.next-button').hide();
                }
                data.data.forEach(function (sale) {
                    const row = `
                                    <tr>
                                        <td>${sale.id}</td>
                                        <td>${sale.tdate}</td>
                                        <td>${(sale.rate / 100).toFixed(2)}</td>
                                        <td>${(sale.qty / 100).toFixed(2)}</td>
                                        <td>${(sale.amt / 100).toFixed(2)}</td>
                                        <td>${sale.pMethod}</td>
                                        <td>${sale.customer}</td>
                                        <td><button onclick="printReceipt(${sale.id}, ${sale.shift_table_id})" class="lni lni-printer"></button></td>
                                    </tr>
                                `;
                    $('#shift_sale_data').append(row);
                });
            });

            $('#shift_sale_data').infiniteScroll('loadNextPage');
        }

        function closeSaleHistory() {
            $('#historyModal').modal('hide');
            saleHistoryData = [];
            activeSaleHistoryPump = null;
            saleHistoryPageNo = 1;
            saleHistoryHasMore = true;
            saleHistoryLoading = false;
        }

        // --- Shift Info Functions (Adapted from ShiftChange.vue) ---
        async function openShiftInfoFromBlade(pumpId) {
            try {
                const response = await $.ajax({
                    url: `/api/shift/check/${pumpId}`,
                    method: 'GET'
                });

                if (response.shift) {
                    activeShiftData = response.shift;
                    shiftResponse.shift = response.shift;
                    shiftResponse.customers = response.customers;
                    shiftResponse.paymentMethods = response.paymentMethods;

                    // Populate the form with the existing shift data
                    $('#id').val(response.shift.id);
                    $('#start_date').val(response.shift.start_date);
                    $('#pump_id').val(response.shift.pump_id);
                    $('#opening_fuel').val((response.shift.opening_fuel / 100).toFixed(2));
                    $('#opening_fuel1').val((response.shift.opening_fuel_manual / 100).toFixed(2)); // Manual opening fuel
                    $('#closing_fuel').val((response.shift.closing_fuel / 100).toFixed(2));
                    $('#closing_fuel1').val((response.shift.closing_fuel_manual / 100).toFixed(2)); // Manual closing fuel
                    $('#opening_balance').val(response.shift.opening_balance);
                    $('#closing_balance').val(response.shift.closing_balance);
                    $('#adjustments').val(response.shift.adjustments);
                    $('#total_qty').val((response.shift.total_qty / 100).toFixed(2));
                    $('#rate').val((response.shift.rate / 100).toFixed(2));
                    $('#new_rate').val((response.shift.new_rate / 100).toFixed(2));
                    $('#changed_fuel_balance').val((response.shift.changed_fuel_balance / 100).toFixed(2));
                    $('#cashier_name').val(response.shift.cashier_name);
                    $('#last_sale_id').val(response.shift.last_sale_id);
                    $('#end_date').val(response.shift.end_date);
                    $('#status').val(response.shift.status);

                    // Populate cashier dropdown
                    populateCashierDropdown(response.shift.cashier_id);

                    // Populate payment methods table
                    let paymentRows = '';
                    if (shiftResponse.paymentMethods && shiftResponse.paymentMethods.length > 0) {
                        paymentRows = shiftResponse.paymentMethods.map(row => `
                            <tr>
                                <td>${row.pMethod}</td>
                                <td>Rs ${(row.total_amount / 100).toFixed(2)}</td>
                            </tr>
                        `).join('');
                    } else {
                        paymentRows = '<tr><td colspan="2" class="text-center">No payment data found.</td></tr>';
                    }
                    // Assuming you have a tbody with id 'payment-methods-table-body' in your shiftModal
                    // For now, I'll just log it, as there's no specific table for this in the provided Shift2.blade.php
                    // You might need to add a table for payment methods and customers in shiftModal
                    console.log("Payment Methods:", paymentRows);

                    // Populate customers table
                    let customerRows = '';
                    if (shiftResponse.customers && shiftResponse.customers.length > 0) {
                        customerRows = shiftResponse.customers.map(row => `
                            <tr>
                                <td>${row.Des}</td>
                                <td>${row.RegNo}</td>
                                <td>Rs ${(row.total_amt / 100).toFixed(2)}</td>
                            </tr>
                        `).join('');
                    } else {
                        customerRows = '<tr><td colspan="3" class="text-center">No customer data found.</td></tr>';
                    }
                    // Assuming you have a tbody with id 'customers-table-body' in your shiftModal
                    console.log("Customers:", customerRows);


                    $('#submitButton').text('Update Shift');
                    $('#closeShiftButton').show();
                } else {
                    // Clear the form for a new shift
                    $('#shiftForm')[0].reset();
                    $('#pump_id').val(pumpId);
                    $('#submitButton').text('Create Shift');
                    $('#closeShiftButton').hide();
                    populateCashierDropdown(null); // Clear cashier dropdown
                }
                $('#shiftModal').modal('show');
            } catch (error) {
                console.error('Error fetching shift info:', error);
                alert('Error fetching shift information.');
            }
        }

        async function populateCashierDropdown(selectedCashierId) {
            if (employees.length === 0) {
                try {
                    const response = await $.ajax({
                        url: '/api/employees',
                        method: 'GET'
                    });
                    employees = response;
                } catch (error) {
                    console.error('Error fetching employees:', error);
                    return;
                }
            }

            let optionsHtml = '';
            employees.forEach(employee => {
                if (employee.des_id == 4) { // Assuming des_id 4 is for cashiers
                    const selected = (selectedCashierId == employee.id) ? 'selected' : '';
                    optionsHtml += `<option value="${employee.id}" ${selected}>${employee.emp_name}</option>`;
                }
            });
            $('#cashier_name').html(optionsHtml);
        }

        // Override existing shiftForm submit handler
        $('#shiftForm').off('submit').on('submit', async function (event) {
            event.preventDefault();

            // Manually collect form data into shiftForm object
            shiftForm.id = $('#id').val();
            shiftForm.start_date = $('#start_date').val();
            shiftForm.end_date = $('#end_date').val();
            shiftForm.pump_id = $('#pump_id').val();
            shiftForm.status = $('#status').val();
            shiftForm.opening_fuel = parseFloat($('#opening_fuel').val()) * 100; // Convert back to cents
            shiftForm.opening_fuel_manual = parseFloat($('#opening_fuel1').val()) * 100; // Convert back to cents
            shiftForm.closing_fuel = parseFloat($('#closing_fuel').val()) * 100; // Convert back to cents
            shiftForm.closing_fuel_manual = parseFloat($('#closing_fuel1').val()) * 100; // Convert back to cents
            shiftForm.opening_balance = parseFloat($('#opening_balance').val());
            shiftForm.closing_balance = parseFloat($('#closing_balance').val());
            shiftForm.adjustments = parseFloat($('#adjustments').val());
            shiftForm.total_qty = parseFloat($('#total_qty').val()) * 100; // Convert back to cents
            shiftForm.rate = parseFloat($('#rate').val()) * 100; // Convert back to cents
            shiftForm.new_rate = parseFloat($('#new_rate').val()) * 100; // Convert back to cents
            shiftForm.changed_fuel_balance = parseFloat($('#changed_fuel_balance').val()) * 100; // Convert back to cents
            shiftForm.cashier_id = $('#cashier_name').val();
            shiftForm.cashier_name = $('#cashier_name option:selected').text(); // Get text for cashier_name
            shiftForm.last_sale_id = $('#last_sale_id').val();

            const url = shiftForm.id ? '/api/shift/update' : '/api/shift/create';
            const method = 'POST'; // Always POST for Laravel API

            try {
                const response = await $.ajax({
                    url: url,
                    method: method,
                    data: shiftForm, // Send the object directly
                    dataType: 'json'
                });
                alert('Shift saved successfully!');
                $('#shiftModal').modal('hide');
                fetchInitialData(); // Refresh pump cards after saving shift
            } catch (error) {
                console.error('Error saving shift:', error);
                alert('Error saving shift. Please check console for details.');
            }
        });

        // Override existing closeShiftButton click handler
        $('#closeShiftButton').off('click').on('click', async function () {
            const id = $('#id').val();
            if (!id) {
                alert('No shift selected to close.');
                return;
            }

            $(this).prop('disabled', true).text('Closing...');

            try {
                // First, update the shift with current form data (especially closing fuel/balance)
                // This re-uses the submit handler logic
                await $('#shiftForm').triggerHandler('submit');

                // Then, call the close API
                const closeResponse = await $.ajax({
                    url: `/api/shift/close/${id}`,
                    method: 'POST',
                    dataType: 'json'
                });

                if (closeResponse.status === "success" && closeResponse.shift) {
                    alert("Shift Closed Successfully.");
                    $('#shiftModal').modal('hide');
                    fetchInitialData(); // Refresh pump cards after closing shift
                } else {
                    console.error("Error closing shift:", closeResponse);
                    alert("Error closing shift. Please check the console for details.");
                }
            } catch (error) {
                alert(`Error: ${error.message || error.responseText}`);
            } finally {
                $(this).prop('disabled', false).text('Close Shift');
            }
        });


        $(document).ready(function () {
            $('[data-dismiss="modal"]').click(function () {
                $(this).closest('div.modal').modal('hide');
            });
            fetchInitialData();
            updatePumpStates();
            updateStats();
            setInterval(updatePumpStates, 3000);
            setInterval(updateStats, 5000);

            // Initial fetch for employees
            $.ajax({
                url: '/api/employees',
                method: 'GET',
                success: function(data) {
                    employees = data;
                },
                error: function(error) {
                    console.error('Error fetching employees on ready:', error);
                }
            });
        });

        function printReceipt(saleId, shift_table_id) {
            fetch(`/api/printDuplicate/${shift_table_id}/${saleId}`).then((res) => {
                alert("print sent");
            })
        }

    </script>
@endsection
