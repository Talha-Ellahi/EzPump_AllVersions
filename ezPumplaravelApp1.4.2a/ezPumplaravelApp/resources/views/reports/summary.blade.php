@extends('layouts.app')

@section('content')
<style>
    @media print {
        @page {
            margin: 0;
        }

        body {
            margin: 0;
            padding: 10px;
        }

        .login-header,
        .cs-hide_print {
            display: none !important;
        }

        .page-break {
            page-break-before: always;
            padding-top: 0;
        }

        .print-page {
            min-height: calc(100vh - 20px);
        }

        .page-2-title {
            font-size: 24px !important;
            margin-bottom: 20px !important;
        }
    }
</style>
<div class="container">
    <div class="mb-3 text-end cs-hide_print">
        <a href="javascript:window.print()" class="cs-invoice_btn cs-color1" style="margin-right: 5px; display: inline-flex; align-items: center; padding: 8px 16px; background-color: #007bff; color: white; text-decoration: none; border-radius: 4px;">
            <svg xmlns="http://www.w3.org/2000/svg" class="ionicon" viewBox="0 0 512 512" style="width: 20px; height: 20px; margin-right: 8px;">
                <path
                    d="M384 368h24a40.12 40.12 0 0040-40V168a40.12 40.12 0 00-40-40H104a40.12 40.12 0 00-40 40v160a40.12 40.12 0 0040 40h24"
                    fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="32" />
                <rect x="128" y="240" width="256" height="208" rx="24.32" ry="24.32" fill="none"
                    stroke="currentColor" stroke-linejoin="round" stroke-width="32" />
                <path d="M384 128v-24a40.12 40.12 0 00-40-40H168a40.12 40.12 0 00-40 40v24" fill="none"
                    stroke="currentColor" stroke-linejoin="round" stroke-width="32" />
                <circle cx="392" cy="184" r="24" />
            </svg>
            <span>Print</span>
        </a>
    </div>
    <!-- PAGE 1: Nozzle Details and Tank Stock -->
    <div class="print-page">
        <h1 class="text-center mb-4" style="font-size: 40px">Ez-Pump Summary Report</h1>

        <div class="row">
            <!-- Nozel Details Section -->
            @if(isset($data['nozel_details']) && count($data['nozel_details']) > 0)
            <div class="col-md-12 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-light">
                        <h2 class="mb-0">Nozel Details</h2>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>ID</th>
                                        <th>Opening</th>
                                        <th>Closing</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($data['nozel_details'] as $nozel)
                                        <tr>
{{--                                            <td>{{ $nozel['display_name']??'null' }}</td>--}}
                                            <td>{{ $nozel['FC_NZNo']??'null' }}</td>
                                            <td>{{ $nozel['opening'] }}</td>
                                            <td>{{ $nozel['closing'] }}</td>
                                            <td>{{ (float)$nozel['closing'] - (float)$nozel['opening'] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Tank Stock Section -->
            @if(isset($data['tank_stock']) && count($data['tank_stock']) > 0)
            <div class="col-md-12 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-light">
                        <h2 class="mb-0">Tank Stock</h2>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>Tank Name</th>
                                        <th>Product</th>
                                        <th>Prev Dip</th>
                                        <th>Prev Dip Litre</th>
                                        <th>Buy</th>
                                        <th>Sale</th>
                                        <th>Current Dip</th>
                                        <th>Current Dip Litre</th>
                                        <th>Gain / Loss</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach($data['tank_stock'] as $tank)
                                        <tr>
                                            <td>{{ $tank['tankName'] }}</td>
                                            <td>{{ $tank['product'] }}</td>
                                            <td>{{ $tank['prevDip'] }}</td>
                                            <td>{{ $tank['prevDipLitre'] }}</td>
                                            <td>{{ $tank['buy'] }}</td>
                                            <td>{{ $tank['sale'] }}</td>
                                            <td>{{ $tank['currentDip'] }}</td>
                                            <td>{{ $tank['currentDipLitre'] }}</td>
                                            <td>{{ $tank['gainLoss'] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- PAGE 2: All Other Sections -->
            @php
                $keysToFilter = ['sales', 'credit', 'credit_cards', 'expense','cash_expense','fuel_stock','pay_rei_am','credit_customers'];
                $hasData = false;
                foreach($keysToFilter as $key) {
                    if(isset($data[$key]) && count($data[$key]) > 0) {
                        $hasData = true;
                        break;
                    }
                }
            @endphp
            @if($hasData)
    <div class="page-break print-page">
        <h2 class="text-center mb-4 page-2-title" style="font-size: 24px">Summary Report - Financial Details</h2>
            @endif

    <div class="row">
        <!-- Left Column -->
        <div class="col-md-6">
            <!-- Summary Net Sale / Receipts -->
            @if(isset($data['sales']) && count($data['sales']) > 0)
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h2 class="mb-0">Summary Net Sale / Receipts</h2>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Item Name</th>
                                    <th>Quantity</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data['sales'] as $sale)
                                    <tr>
                                        <td>{{ $sale['item'] }}</td>
                                        <td>{{ $sale['quantity'] }}</td>
                                        <td>{{ $sale['amount'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="text-right">
                        <strong>Total Price:</strong> {{ array_reduce($data['sales'], function($carry, $item) { return $carry + (float)($item['amount'] ?? 0); }, 0) }}
                    </div>
                </div>
            </div>
            @endif
            <!-- Summary Credit / Payments -->
            @if(isset($data['credit']) && count($data['credit']) > 0)
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h2 class="mb-0">Summary Credit / Payments</h2>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Item Name</th>
                                    <th>Quantity</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data['credit'] as $credit)
                                    <tr>
                                        <td>{{ $credit['item'] }}</td>
                                        <td>{{ $credit['quantity'] }}</td>
                                        <td>{{ $credit['amount'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="text-right">
                        <strong>Total Price:</strong> {{ array_reduce($data['credit'], function($carry, $item) { return $carry + (float)($item['amount'] ?? 0); }, 0) }}
                    </div>
                </div>
            </div>
            @endif

            <!-- MCB / UBL / PSO / Credit Cards -->
            @if(isset($data['credit_cards']) && count($data['credit_cards']) > 0)
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h2 class="mb-0">MCB / UBL / PSO / Credit Cards</h2>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Credit Cards</th>
                                    <th>Other</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data['credit_cards'] as $card)
                                    <tr>
                                        <td>{{ $card['item'] }}</td>
                                        <td>{{ $card['quantity'] }}</td>
                                        <td>{{ $card['amount'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="text-right">
                        <strong>Total Price:</strong> {{ array_reduce($data['credit_cards'], function($carry, $item) { return $carry + (float)($item['amount'] ?? 0); }, 0) }}
                    </div>
                </div>
            </div>
            @endif

            <!-- Summary Cash Payments -->
            @if(isset($data['cash_expense']) && count($data['cash_expense']) > 0)
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h2 class="mb-0">Summary Cash Payments</h2>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Item</th>
                                    <th>Other</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data['cash_expense'] as $expense)
                                    <tr>
                                        <td>{{ $expense['item'] }}</td>
                                        <td>{{ $expense['other'] }}</td>
                                        <td>{{ $expense['amount'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="text-right">
                        <strong>Total Price:</strong> {{ array_reduce($data['cash_expense'], function($carry, $item) { return $carry + (float)($item['amount'] ?? 0); }, 0) }}
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Right Column -->
        <div class="col-md-6">
            <!-- All Expenses -->
            @if(isset($data['expense']) && count($data['expense']) > 0)
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h2 class="mb-0">All Expenses</h2>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Expense</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data['expense'] as $expense)
                                    <tr>
                                        <td>{{ $expense['item'] }}</td>
                                        <td>{{ $expense['amount'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="text-right">
                        <strong>Total Price:</strong> {{ array_reduce($data['expense'], function($carry, $item) { return $carry + (float)($item['amount'] ?? 0); }, 0) }}
                    </div>
                </div>
            </div>
            @endif

            <!-- Credit Customers -->
            @if(isset($data['credit_customers']) && count($data['credit_customers']) > 0)
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h2 class="mb-0">Credit Customers</h2>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Name</th>
                                    <th>Product</th>
                                    <th>Quantity</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data['credit_customers'] as $customer)
                                    <tr>
                                        <td>{{ $customer['name'] ?? 'N/A' }}</td>
                                        <td>{{ $customer['product'] }}</td>
                                        <td>{{ $customer['quantity'] }}</td>
                                        <td>{{ $customer['amount'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif

            <!-- Received Payment (Wasooli) -->
            @if(isset($data['pay_rei_am']) && count($data['pay_rei_am']) > 0)
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h2 class="mb-0">Received Payment (Wasooli)</h2>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Name</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data['pay_rei_am'] as $payment)
                                    <tr>
                                        <td>{{ $payment['name'] }}</td>
                                        <td>{{ $payment['amount'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
