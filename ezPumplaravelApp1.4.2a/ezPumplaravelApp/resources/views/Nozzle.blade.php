@extends('layouts.app')
<style>
    .details-row {
        display: none;
    }

    .modal-fullscreen {
        width: 100vw !important;
        max-width: none !important;
        height: 90% !important;
        margin: 0 !important;
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
        border-collapse: initial !important;
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

    @media print {
        .no-print {
            display: none;
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
            <div class="col-xl-9 mx-auto">

                <div class="card border-top border-0 border-4 border-danger">

                    <div class="card-body ">

                        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                            <div class="pe-3">
                                <button type="button" class="btn btn-warning shift-close-all">Close All Shifts </button>
                                <!-- <h5 class="mb-0 text-uppercase"><b>Shift Start Date & Time: </b>8 July 2024 07:42:17 AM
                                    </h5> -->
                            </div>


                        </div>
                        <hr>
                        <br>
                        <div id="pump-cards"
                            class="row row-cols-xl-10 row-cols-lg-8 row-cols-md-6 row-cols-sm-4 cs-hide_print"></div>
                        <hr>
                        <br>

                        <div class="pe-3">
                            <h5 class="mb-0 text-uppercase"><b>Shift Summary </b></h5>
                        </div>

                        <br>
                        <form id="shiftForm">
                            <input type="hidden" name="id" id="id">
                            <input type="hidden" name="end_date" id="end_date">
                            {{-- <input type="hidden" name="start_date" id="start_date"> --}}
                            {{-- <input type="hidden" name="pump_id" id="pump_id"> --}}
                            <input type="hidden" class="form-control" id="status" name="status">
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="start_date">Start Date</label>
                                    <input type="text" class="form-control" id="start_date" name="start_date" disabled>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="pump_id">Nozzle ID</label>
                                    <input type="number" class="form-control" id="pump_id" name="pump_id" disabled>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="opening_fuel">System Opening Totalizer</label>
                                    <input type="number" class="form-control" id="opening_fuel" name="opening_fuel"
                                        disabled>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="opening_fuel_manual">Mannual Opening Totalizer</label>
                                        <input type="number" class="form-control" id="opening_fuel_manual"
                                            name="opening_fuel_manual">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="closing_fuel">System Closing Totalizer</label>
                                        <input type="number" class="form-control" id="closing_fuel" name="closing_fuel"
                                            disabled>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="closing_fuel_manual">Mannual Closing Totalizer</label>
                                        <input type="number" class="form-control" id="closing_fuel_manual"
                                            name="closing_fuel_manual">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="opening_balance">Opening Balance</label>
                                        <input type="number" class="form-control" id="opening_balance"
                                            name="opening_balance">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="closing_balance">Closing Balance</label>
                                        <input type="number" class="form-control" id="closing_balance"
                                            name="closing_balance">
                                    </div>
                                </div>
                                <div class="form-row">

                                    <div class="form-group col-md-6">
                                        <label for="total_qty">Total Quantity</label>
                                        <input type="number" class="form-control" id="total_qty" name="total_qty"
                                            readonly>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="rate">Rate</label>
                                        <input type="number" class="form-control" id="rate" name="rate" readonly>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="adjustments">Adjustments</label>
                                        <input type="number" class="form-control" id="adjustments" name="adjustments">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="is_changed">Rate Changed during shift?</label>
                                        <b for="is_changed">Yes</b>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="new_rate">New Rate</label>
                                        <input type="number" class="form-control" id="new_rate" name="new_rate"
                                            readonly>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="changed_fuel_balance">Changed Fuel Balance</label>
                                        <input type="number" class="form-control" id="changed_fuel_balance"
                                            name="changed_fuel_balance" readonly>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="cashier_name">Cashier Name</label>
                                        <input type="text" class="form-control" id="cashier_name" name="cashier_name">
                                    </div>
                                    <div class="form-group col-md-6 d-none">
                                        <label for="last_sale_id">Last Sale ID</label>
                                        <input type="number" class="form-control" id="last_sale_id" name="last_sale_id">
                                    </div>
                                </div>
                                <div class="justify-content-between cs-hide_print" style="text-align-last: center;">

                                    <button type="button" class="btn btn-primary d-none">Previous</button>
                                    <button type="close" class="btn btn-danger shift-close">Shift Close</button>
                                    <button type="submit" class="btn btn-warning" id="submitButton">Save</button>

                                    <button type="button" class="btn btn-primary next-shift">Next </button>
                                </div>
                        </form>



                        <div class="shift_details d-none">
                            <h3><b>Total Sale:</b> <span class="total_sale"> </span></h3>
                            <h2>Payment Methods</h2>
                            <div class="payment_stats table-responsive">
                                <!-- The table will be inserted here dynamically -->
                            </div>
                            <h2>Customers</h2>
                            <div class="customer_stats table-responsive">
                                <!-- The table will be inserted here dynamically -->
                            </div>

                        </div>

                    </div>

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
<script src="https://unpkg.com/infinite-scroll@4.0.1/dist/infinite-scroll.pkgd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-infinitescroll/4.0.1/infinite-scroll.pkgd.min.js"></script>

<script>
    let pumpCards;
    let pageNo = 1;
    let activeShift = 0;
    let activePumpId;

    let hasNext;
    const pumpImages = {
        // 0: "assets/images/Nozzle_red.png",
        1: "assets/images/Nozzle_one.png",
        2: "assets/images/Nozzle_three.png",
        // 3: "assets/images/"
        // Add more states if needed
    };

    const pumpICodeImages = {
        1: "assets/images/Nozzle_green.png",
        2: "assets/images/Nozzle_Blue.png",
        3: "assets/images/Nozzle_yellow.png",
        4: "assets/images/Nozzle_black.png",
        // Add more ICODE images if needed
    };

    function createPumpCard(pump, state) {
        let image;

        // Check if the state is defined and has a valid image
        if (state !== undefined && state.STS !== undefined) {
            image = pumpImages[state.STS];
        }

        // If the image is not set from the state, use the pump code
        if (!image && pump.ICODE !== undefined) {
            image = pumpImages[pump.ICODE];
        }

        return `
            <div class="col-xl-1 col-lg-1 col-md-2 col-sm-3">
                    <div class="card pump-card openShiftInfo" data-id="${pump.id}" data-pumpid="${pump.POS_ID}">
                        <img src="${image}" class="card-img-top" alt="Nozzle">
                        <div class="">
                        </div>
                        <div class="card-body">
                            <span class=""><b>${pump.POS_ID}</b></span>


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
                // pumpCard.find('.fs-6').eq(0).find('p').last().text(state.amt);
                // pumpCard.find('.fs-6').eq(1).find('p').last().text(state.qty / 100); // Assuming qty is in centiliters
                // pumpCard.find('.fs-6').eq(2).find('p').last().text(state.rate / 100); // Assuming rate is in cents

            } else {
                pumpCard.find('.card-img-top').attr('src', pumpICodeImages[pumpCardJson.ICODE]);
                // pumpCard.find('.fs-6').eq(0).find('p').last().text(state.shift_qty * state.shift_rate / 10000);
                // pumpCard.find('.fs-6').eq(1).find('p').last().text(state.shift_qty /
                //     100); // Assuming qty is in centiliters
                // pumpCard.find('.fs-6').eq(2).find('p').last().text(state.shift_rate / 100); // Assuming rate is in cents

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
                $('.openSaleHistory').click(openSaleHistory);

                $('.openShiftInfo').click(openShiftInfo);
                $('.next-shift').click(function () {

                    $(`.openShiftInfo[data-id=${activeShift + 1}]`).click();
                    if (activeShift >= pumpCards.length) {
                        $('.next-shift').hide();
                    }
                });


                $('#shiftForm').submit(function (event) {
                    event.preventDefault();
                    var url = $('#id').val() ? '/api/shift/update' : '/api/shift/create';
                    var method = $('#id').val() ? 'POST' : 'POST';

                    $.ajax({
                        url: url,
                        method: method,
                        data: $(this).serialize(),
                        success: function (response) {
                            alert('Shift saved successfully!');
                        }
                    });
                });

                $('.shift-close').click(function () {
                    $(this).attr('disabled', true);

                    var id = $('#id').val();
                    var url = id ? '/api/shift/update' : '/api/shift/create';
                    var method = id ? 'POST' : 'POST';

                    $.ajax({
                        url: url,
                        method: method,
                        data: $('#shiftForm').serialize(),
                        success: function (response) {
                            fetch(`/api/shift/close/${id}`)
                                .then(async (res) => {
                                    // Check if the response is not successful (status code not 200-299)
                                    if (!res.ok) {
                                        // Throw an error with the response message
                                        const errorData = await res.json(); // Get the error message from the response
                                        throw new Error(errorData.error || 'Something went wrong.');
                                    }

                                    // Parse the response JSON if successful
                                    var response = await res.json();
                                    activeShift = activeShift - 1;
                                    $(this).attr('disabled', false);

                                    // Simulate a click on the next shift
                                    document.querySelector('.next-shift').click();
                                })
                                .catch((error) => {
                                    // Display error message (replace with your UI logic for showing errors)
                                    alert(`Error: ${error.message}`);
                                    $(this).attr('disabled', false);

                                });
                        }
                    });

                });
                $('.shift-close-all').click(function () {
                    $(this).attr('disabled', true);

                    fetch(`/api/shift/closeAllShifts`, {
                        method: 'GET',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        }
                    }).then(async (res) => {
                        // Check if the response is not successful (status code not 200-299)
                        if (!res.ok) {
                            // Throw an error with the response message
                            const errorData = await res.json(); // Get the error message from the response
                            throw new Error(errorData.error || 'Something went wrong.');
                        }

                        // Parse the response JSON if successful
                        var response = await res.json();
                        $(this).attr('disabled', false);

                        alert(response.message);
                    })
                        .catch((error) => {
                            // Display error message (replace with your UI logic for showing errors)
                            alert(`Error: ${error.message}`);
                            $(this).attr('disabled', false);

                        });

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
        // Fetch data from the API
        $.ajax({
            url: '/api/stats',
            method: 'GET',
            success: function (response) {
                // Assuming response has the stats and products arrays
                let stats = response.stats;
                let products = response.products;

                // Find the product details by icode
                let productDetails = {};
                products.forEach(product => {
                    productDetails[product.ICODE] = product;
                });

                // Calculate total sales amount to determine percentages
                let totalSalesAmount = stats.reduce((total, stat) => total + parseFloat(stat.total_amount),
                    0);

                // Generate table rows
                let rows = stats.map(stat => {
                    let product = productDetails[stat.icode];
                    let percentage = ((parseFloat(stat.total_amount) / totalSalesAmount) * 100)
                        .toFixed(2) + '%';

                    return `
                    <tr>
                        <td class="cs-width_2">${product.ITMNAME}</td>
                        <td class="cs-width_2">Rs. ${product.SRATE / 100}</td>
                        <td class="cs-width_2">Ltrs. ${(parseFloat(stat.total_qty)).toFixed(2)}</td>
                        <td class="cs-width_2">${percentage}</td>
                        <td class="cs-width_3 cs-text_right cs-primary_color cs-semi_bold">${parseFloat(stat.total_amount).toFixed(2)}</td>
                    </tr>
                `;
                }).join('');
                $("#summary_total_sale").text(totalSalesAmount.toFixed(2));
                // Append the rows to the table body
                $('#product-stats').html(rows);
            },
            error: function (error) {
                console.log('Error fetching data:', error);
            }
        });

    }

    function openShiftInfo() {
        activeShift = $(this).data('id');

        $('#shiftForm').get(0).scrollIntoView({
            behavior: 'smooth'
        });

        var pumpId = $(this).data('id');
        $.ajax({
            url: `/api/shift/check/${pumpId}`,
            method: 'GET',
            success: function (response) {
                if (response.status === 'exists') {
                    // Populate the form with the existing shift data

                    $('#id').val(response.shift.id);
                    $('#opening_fuel').val(response.shift.opening_fuel);
                    $('#closing_fuel').val(response.shift.closing_fuel);
                    $('#opening_fuel_manual').val(response.shift.opening_fuel_manual);
                    $('#closing_fuel_manual').val(response.shift.closing_fuel_manual);

                    $('#opening_balance').val(response.shift
                        .opening_balance);
                    $('#closing_balance').val(response.shift
                        .closing_balance);
                    $('#adjustments').val(response.shift.adjustments);
                    $('#total_qty').val(response.shift.total_qty);
                    $('#rate').val(response.shift.rate / 100);
                    $('#new_rate').val(response.shift.new_rate);
                    $('#changed_fuel_balance').val(response.shift
                        .changed_fuel_balance);
                    $('b[for=is_changed]').text(response.shift.is_changed ? 'yes' : 'no');

                    $('#is_changed').prop('checked', response.shift
                        .is_changed);
                    $('#status').prop('checked', response.shift.status);
                    $('#cashier_name').val(response.shift.cashier_name);
                    $('#last_sale_id').val(response.shift.last_sale_id);
                    $('#start_date').val(response.shift.start_date);
                    $('#end_date').val(response.shift.end_date);
                    $('#pump_id').val(response.shift.pump_id);
                    $('#submitButton').text('Update Shift');
                    $('#shift-close').show();
                    let totalAmount = 0;


                    var data = response.paymentMethods;

                    var table = `
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>Payment Mode</th>
                                <th>Total Amount (PKR)</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>`;

                    $('.payment_stats').html(table);

                    data.forEach(function (row) {
                        console.log(row.total_amount);
                        totalAmount = totalAmount + (row.total_amount / 100);
                        var tableRow = `
                                <tr>
                                    <td>${row.pMethod}</td>
                                <td>Rs ${row.total_amount / 100}</td>
                                </tr>

                        `;
                        $('.payment_stats tbody').append(tableRow);
                    });

                    var data = response.customers;

                    var table = `
<table class="table table-bordered">
    <thead>
    <tr>
        <th>Customer</th>
        <th>Vehicle</th>
        <th>Amount</th>
    </tr>
    </thead>
    <tbody>
    </tbody>
</table>`;

                    $('.customer_stats').html(table);

                    data.forEach(function (row) {
                        var tableRow = `
        <tr>
            <td>${row.Des}</td>
        <td>${row.RegNo}</td>
        <td>Rs ${row.total_amt / 100}</td>
        </tr>
`;
                        $('.customer_stats tbody').append(tableRow);
                    });
                    $('.shift_details.d-none').removeClass('d-none');
                    $('.total_sale').text(totalAmount);
                    $(document).on('click', '.toggle-details', function () {
                        $(this).closest('tr').next('.details-row').toggle();
                    });


                } else {
                    // Clear the form for a new shift
                    $('#shiftForm')[0].reset();
                    $('#pump_id').val(pumpId);
                    $('#submitButton').text('Create Shift');
                    $('#shift-close').hide();
                }
                // $('#shiftModal').modal('show');
            }
        });


    }

    function openSaleHistory() {
        activeShift = $(this).data('id');
        $("#historyModal").modal('show');

        try {
            pageNo = 1;
            $('#shift_sale_data').html('');
        } catch {

        }
        $('#shift_sale_data').infiniteScroll({
            itemSelector: "#shift_sale_data tr", // Selector for all items you'll retrieve
            loading: {
                finishedMsg: 'No more items to load.',
                img: 'https://i.imgur.com/6RMhx.gif' // Loading image
            },
            checkLastPage: true,
            // Using jQuery's AJAX
            responseBody: 'json',
            append: false,
            loadOnScroll: false,
            history: false,

            // scrollThreshold: 100,
            // elementScroll: '#pagination',

            appendCallback: false, // We'll handle appending ourselves
            path: function () {
                return `/api/shiftSaleData?shift_id=${activeShift}&page=${pageNo}`;
            },
            button: '.shift_sale_data.next-button',
        });
        $('#shift_sale_data').on('load.infiniteScroll', function (event, data,) {
            pageNo++;

            if (data.next_page_url) {

            } else {
                $('.shift_sale_data.next-button').hide();
            }
            // Parse the JSON data and append the rows to the table
            data.data.forEach(function (sale) {
                const row = `
                                <tr>
                                    <td>${sale.id}</td>
                                    <td>${sale.pdate}</td>
                                    <td>${sale.rate / 100}</td>
                                    <td>${sale.qty / 100}</td>
                                    <td>${sale.amt / 100}</td>
                                    <td>${sale.pMethod}</td>
                                    <td>${sale.customer}</td>
                                    <td><button onclick="printReceipt(${sale.id})" class="lni lni-printer"></button></td>
                                </tr>
                            `;
                $('#shift_sale_data').append(row);
            });

            // Function to handle printing (you'll need to define this function based on your requirements)

        });
        $('#shift_sale_data').infiniteScroll('loadNextPage');

    }

    $(document).ready(function () {
        $('[data-dismiss="modal"]').click(function () {
            $(this).closest('div.modal').modal('hide');
        });
        fetchInitialData();
        setInterval(updatePumpStates, 3000); // Update every 3 seconds
        setInterval(updateStats, 5000); // Update every 5 seconds


        // Function to format the date
        function formatDate(date) {
            const options = {
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: 'numeric',
                minute: 'numeric',
                second: 'numeric',
                hour12: true,
                timeZone: 'Asia/Karachi'
            };
            return date.toLocaleDateString('en-US', options);
        }

        // Get the current date and time
        const now = new Date();

        // Adjust the time zone offset (e.g., +5 hours)
        now.setHours(now.getUTCHours() + 5);

        // Format the date
        const formattedDate = formatDate(now);

        // Set the formatted date into the span
        document.getElementById('current-date').textContent = formattedDate;



    });

    function printReceipt(saleId) {
        fetch(`/api/printDuplicate/${saleId}`).then((res) => {
            alert("print sent");
        })
    }
</script>
@endsection
