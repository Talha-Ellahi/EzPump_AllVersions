{{--@if (!isset($excludeHeader) || !$excludeHeader)--}}
{{--    @include('reports.print_headers')--}}
{{--@endif--}}

{{--@if ($shifts->isEmpty())--}}
{{--    <div class="container mt-5">--}}
{{--        <h2 class="text-center">No Shift Report Data Available</h2>--}}
{{--    </div>--}}
{{--@else--}}



{{--    <!-- Cumulative Summary -->--}}
{{--    @php--}}
{{--        // Convert the shifts array into a Laravel Collection for easier manipulation--}}
{{--        $shiftsCollection = collect(json_decode(json_encode($shifts), true));--}}

{{--        // Group the shifts by 'pump_id'--}}
{{--        $groupedByPump = $shiftsCollection--}}
{{--            ->groupBy('pump_id')--}}
{{--            ->map(function ($pumpGroup) {--}}
{{--                return [--}}
{{--                    'pump_id' => $pumpGroup->first()['pump_id'],--}}
{{--                     'opening_fuel' => $pumpGroup->first()['data']['shift']['opening_fuel'] / 100, // ✅ first shift opening--}}
{{--                'closing_fuel' => $pumpGroup->last()['data']['shift']['closing_fuel'] / 100,   // ✅ last shift closing--}}
{{--                'rate' => $pumpGroup->first()['data']['shift']['rate'] / 100,--}}
{{--                    'total_amount' => $pumpGroup->sum(function ($shift) {--}}
{{--                        // Access the total_amount within the nested 'data.shift' array--}}
{{--                        return $shift['data']['shift']['total_amount'] / 100;--}}
{{--                    }),--}}
{{--                    'total_quantity' => $pumpGroup->sum(function ($shift) {--}}
{{--                        // Access the total_amount within the nested 'data.shift' array--}}
{{--                        return $shift['data']['shift']['total_qty'] / 100;--}}
{{--                    }),--}}
{{--                ];--}}
{{--            })--}}
{{--            ->sortBy('pump_id');--}}

{{--//         dd($groupedByPump);--}}

{{--    @endphp--}}
{{--    @php--}}
{{--        $paymentMethods = [];--}}
{{--        $productTotals = []; // product => [litres, cash]--}}

{{--        foreach ($shifts as $shift) {--}}
{{--            $product = $productDict[$shift->data->shift->pump_id] ?? 'Unknown';--}}

{{--            // Qty in litres--}}
{{--            $litres = ($shift->data->shift->closing_fuel - $shift->data->shift->opening_fuel) / 100;--}}

{{--            // Rate in Rs/L--}}
{{--            $rate = ($shift->data->shift->rate ?? 0) / 100;--}}

{{--            // Expected Amount--}}
{{--            $expectedAmount = $litres * $rate;--}}

{{--            // Collect totals--}}
{{--            $productTotals[$product]['litres'] = ($productTotals[$product]['litres'] ?? 0) + $litres;--}}
{{--            $productTotals[$product]['cash']   = ($productTotals[$product]['cash'] ?? 0) + $expectedAmount;--}}

{{--            // Collect payment methods (for table header)--}}
{{--            foreach ($shift->data->paymentMethods as $payment) {--}}
{{--                if (!in_array($payment->pMethod, $paymentMethods)) {--}}
{{--                    $paymentMethods[] = $payment->pMethod;--}}
{{--                }--}}
{{--            }--}}
{{--        }--}}

{{--        // Grand totals--}}
{{--        $grandLitres = array_sum(array_column($productTotals, 'litres'));--}}

{{--        $grandCash   = array_sum(array_column($productTotals, 'cash'));--}}
{{--    @endphp--}}


{{--    <div class="cs-table cs-mb15 mt-2">--}}
{{--        <div>--}}
{{--            <p class="cs-primary_color cs-semi_bold cs-f18 cs-mb0 cs-table_title" style="text-align: center">--}}
{{--                Sales Report--}}
{{--            </p>--}}
{{--            <div class="cs-table_responsive">--}}
{{--                <table>--}}
{{--                    <thead>--}}
{{--                    <tr class="cs-focus_bg">--}}
{{--                        <th class="cs-width_2 cs-semi_bold cs-primary_color">Product</th>--}}
{{--                        <th class="cs-width_4 cs-semi_bold cs-primary_color cs-text_right">Quantity (Ltrs)</th>--}}
{{--                        <th class="cs-width_4 cs-semi_bold cs-primary_color cs-text_right">Cash</th>--}}
{{--                    </tr>--}}
{{--                    </thead>--}}
{{--                    <tbody>--}}
{{--                    @foreach ($productTotals as $product => $totals)--}}
{{--                        <tr>--}}
{{--                            <td>{{ $product }}</td>--}}
{{--                            <td class="cs-text_right">{{ number_format($totals['litres'], 2) }}</td>--}}
{{--                            <td class="cs-text_right">{{ number_format($totals['cash'], 2) }}</td>--}}
{{--                        </tr>--}}
{{--                    @endforeach--}}

{{--                    <tr>--}}
{{--                        <td><strong>Total</strong></td>--}}
{{--                        <td class="cs-text_right"><strong>{{ number_format($grandLitres, 2) }}</strong></td>--}}
{{--                        <td class="cs-text_right"><strong>{{ number_format($grandCash, 2) }}</strong></td>--}}
{{--                    </tr>--}}
{{--                    </tbody>--}}
{{--                </table>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}




{{--    --}}{{--    @php--}}
{{--    --}}{{--        $paymentMethods = [];--}}
{{--    --}}{{--        $productTypes = [];--}}
{{--    --}}{{--        $productRateMap = [];--}}
{{--    --}}{{--        $cumulativeSales = [];--}}
{{--    --}}{{--        $cumulativeQuantity = ['Total' => 0];--}}
{{--    --}}{{--        $rounding_errors = [];--}}
{{--    --}}{{--        foreach ($shifts as $shift) {--}}
{{--    --}}{{--            $product = $productDict[$shift->data->shift->pump_id]; // Ensure this key exists--}}

{{--    --}}{{--            foreach ($shift->data->paymentMethods as $payment) {--}}
{{--    --}}{{--                //  dd([$payment, $shifts]);--}}
{{--    --}}{{--                $pMethod = $payment->pMethod; // Ensure this key exists--}}

{{--    --}}{{--                // Collect unique payment methods and product types--}}
{{--    --}}{{--                if (!in_array($pMethod, $paymentMethods)) {--}}
{{--    --}}{{--                    $paymentMethods[] = $pMethod;--}}
{{--    --}}{{--                }--}}
{{--    --}}{{--                if (!in_array($product, $productTypes)) {--}}
{{--    --}}{{--                    $productTypes[] = $product;--}}
{{--    --}}{{--                }--}}

{{--    --}}{{--                // Initialize if not set--}}
{{--    --}}{{--                if (!isset($cumulativeSales[$product][$pMethod])) {--}}
{{--    --}}{{--                    $cumulativeSales[$product][$pMethod] = 0;--}}
{{--    --}}{{--                }--}}
{{--    --}}{{--                $cumulativeSales[$product][$pMethod] += $payment->total_amount;--}}
{{--    --}}{{--            }--}}

{{--    --}}{{--            if (!isset($cumulativeQuantity[$product])) {--}}
{{--    --}}{{--                $cumulativeQuantity[$product] = 0;--}}
{{--    --}}{{--            }--}}
{{--    --}}{{--            if (!isset($rounding_errors[$product])) {--}}
{{--    --}}{{--                $rounding_errors[$product] = 0;--}}
{{--    --}}{{--            }--}}
{{--    --}}{{--            // Sum the amounts--}}
{{--    --}}{{--            $cumulativeQuantity[$product] += $shift->data->shift->total_qty;--}}

{{--    --}}{{--            //echo $shift->data->shift->total_qty . ", ";--}}
{{--    --}}{{--            $cumulativeQuantity['Total'] += $shift->data->shift->total_qty;--}}
{{--    --}}{{--            $productRateMap[$product] = $shift->data->shift->rate;--}}
{{--    --}}{{--            $total_sale = array_sum(array_column($shift->data->paymentMethods, 'total_amount')) / 100;--}}
{{--    --}}{{--            if ($shift->data->shift->is_changed) {--}}
{{--    --}}{{--                // Quantities before and after the rate change--}}
{{--    --}}{{--                $qty_before_change = $shift->data->shift->rate_change_qty;--}}
{{--    --}}{{--                $qty_after_change = $shift->data->shift->total_qty - $shift->data->shift->rate_change_qty;--}}
{{--    --}}{{--                // Rates before and after the change--}}
{{--    --}}{{--                $old_rate = $shift->data->shift->rate;--}}
{{--    --}}{{--                $new_rate = $shift->data->shift->new_rate;--}}
{{--    --}}{{--                // Expected total sales amount considering the rate change--}}
{{--    --}}{{--                $expected_total_sales = ($qty_before_change * $old_rate + $qty_after_change * $new_rate) / 10000;--}}
{{--    --}}{{--                // Calculate the rounding error--}}
{{--    --}}{{--                $rounding_error = $total_sale - $expected_total_sales;--}}
{{--    --}}{{--            } else {--}}
{{--    --}}{{--                $rounding_error =--}}
{{--    --}}{{--                    $total_sale - ($shift->data->shift->total_qty * $shift->data->shift->rate) / 100 / 100;--}}
{{--    --}}{{--            }--}}

{{--    --}}{{--            $shiftPayments = $shift->data->shiftPaymentWiseSales;--}}
{{--    --}}{{--            $other_payment_rounding_error = array_map(function ($sale) use ($shift) {--}}
{{--    --}}{{--                // echo json_encode( $sale );--}}
{{--    --}}{{--                if ($sale->paymentmethod_id == 1) {--}}
{{--    --}}{{--                    return 0;--}}
{{--    --}}{{--                }--}}
{{--    --}}{{--                $sale = (array) $sale;--}}
{{--    --}}{{--                $total_sale = $sale['total_sale'];--}}
{{--    --}}{{--                $total_qty = $sale['total_qty'];--}}
{{--    --}}{{--                $rate = $sale['price']; // assuming 'price' is the rate--}}
{{--    --}}{{--                if ($rate == 0) {--}}
{{--    --}}{{--                    $rate = $shift->data->shift->rate;--}}
{{--    --}}{{--                }--}}
{{--    --}}{{--                return $total_sale - ($total_qty * $rate) / 100;--}}
{{--    --}}{{--            }, $shiftPayments);--}}

{{--    --}}{{--            $other_payment_rounding_error = array_sum($other_payment_rounding_error) / 100; // Divide by 100 if needed--}}
{{--    --}}{{--            // echo json_encode([$rounding_error,$other_payment_rounding_error]);--}}
{{--    --}}{{--            $rounding_errors[$product] += $rounding_error - $other_payment_rounding_error;--}}
{{--    --}}{{--        }--}}
{{--    --}}{{--    @endphp--}}



{{--    --}}{{--    <div class="cs-table  cs-mb15 mt-2">--}}
{{--    --}}{{--        <div class="">--}}
{{--    --}}{{--            <p class="cs-primary_color cs-semi_bold cs-f18 cs-mb0 cs-table_title" style="text-align: center">Sales Report</p>--}}
{{--    --}}{{--            <div class="cs-table_responsive">--}}
{{--    --}}{{--                <table>--}}
{{--    --}}{{--                    <thead>--}}
{{--    --}}{{--                        <tr class="cs-focus_bg">--}}
{{--    --}}{{--                            <th class="cs-width_2 cs-semi_bold cs-primary_color">Product</th>--}}
{{--    --}}{{--                            <th class="cs-width_4 cs-semi_bold cs-primary_color cs-text_right">Quantity--}}
{{--    --}}{{--                                (Ltrs)</th>--}}
{{--    --}}{{--                            @foreach ($paymentMethods as $pMethod)--}}
{{--    --}}{{--                                <th class="cs-width_4 cs-semi_bold cs-primary_color cs-text_right">--}}
{{--    --}}{{--                                    {{ $pMethod }}</th>--}}
{{--    --}}{{--                            @endforeach--}}
{{--    --}}{{--                            <th class="cs-width_4 cs-semi_bold cs-primary_color cs-text_right">Total am</th>--}}
{{--    --}}{{--                            <th class="cs-width_4 cs-semi_bold cs-primary_color cs-text_right">Rounding Amount</th>--}}
{{--    --}}{{--                        </tr>--}}
{{--    --}}{{--                    </thead>--}}
{{--    --}}{{--                    <tbody>--}}
{{--    --}}{{--                        @php--}}
{{--    --}}{{--                            $rounding_total = 0;--}}
{{--    --}}{{--                        @endphp--}}
{{--    --}}{{--                        @foreach ($productTypes as $product)--}}
{{--    --}}{{--                            <tr>--}}
{{--    --}}{{--                                <td>{{ $product }}</td>--}}
{{--    --}}{{--                                <td class="cs-text_right">--}}
{{--    --}}{{--                                    {{ number_format($cumulativeQuantity[$product] / 100, 0) }}</td>--}}
{{--    --}}{{--                                @php--}}
{{--    --}}{{--                                    $productTotal = 0;--}}
{{--    --}}{{--                                @endphp--}}
{{--    --}}{{--                                @foreach ($paymentMethods as $pMethod)--}}
{{--    --}}{{--                                    @php--}}


{{--    --}}{{--                                        $amount = isset($cumulativeSales[$product][$pMethod])--}}
{{--    --}}{{--                                            ? $cumulativeSales[$product][$pMethod]--}}
{{--    --}}{{--                                            : 0;--}}
{{--    --}}{{--                                        $productTotal += $amount;--}}
{{--    --}}{{--//                                        dd($amount);--}}
{{--    --}}{{--                                    @endphp--}}
{{--    --}}{{--                                    <td class="cs-text_right">{{ number_format($amount / 100, 0) }}</td>--}}
{{--    --}}{{--                                @endforeach--}}
{{--    --}}{{--                                <td class="cs-text_right">{{ number_format($productTotal / 100, 0) }}</td>--}}
{{--    --}}{{--                                @php--}}

{{--    --}}{{--                                @endphp--}}
{{--    --}}{{--                                {{dd($rounding_errors)}}--}}
{{--    --}}{{--                                <td class="cs-text_right">{{ number_format($rounding_errors[$product], 0) }}</td>--}}
{{--    --}}{{--                            </tr>--}}
{{--    --}}{{--                        @endforeach--}}
{{--    --}}{{--                        <tr>--}}
{{--    --}}{{--                            <td><strong>Total</strong></td>--}}
{{--    --}}{{--                            <td class="cs-text_right">--}}
{{--    --}}{{--                                <strong>{{ number_format($cumulativeQuantity['Total'] / 100, 0) }}</strong>--}}
{{--    --}}{{--                            </td>--}}
{{--    --}}{{--                            @php $grandTotal = 0; @endphp--}}
{{--    --}}{{--                            @foreach ($paymentMethods as $pMethod)--}}
{{--    --}}{{--                                @php--}}
{{--    --}}{{--                                    $methodTotal = 0;--}}
{{--    --}}{{--                                    foreach ($productTypes as $product) {--}}
{{--    --}}{{--                                        $methodTotal += isset($cumulativeSales[$product][$pMethod])--}}
{{--    --}}{{--                                            ? $cumulativeSales[$product][$pMethod]--}}
{{--    --}}{{--                                            : 0;--}}
{{--    --}}{{--                                    }--}}
{{--    --}}{{--                                    $grandTotal += $methodTotal;--}}
{{--    --}}{{--                                @endphp--}}
{{--    --}}{{--                                <td class="cs-text_right">--}}
{{--    --}}{{--                                    <strong>{{ number_format($methodTotal / 100, 0) }}</strong>--}}
{{--    --}}{{--                                </td>--}}
{{--    --}}{{--                            @endforeach--}}
{{--    --}}{{--                            <td class="cs-text_right">--}}
{{--    --}}{{--                                <strong>{{ number_format($grandTotal / 100, 0) }}</strong>--}}
{{--    --}}{{--                            </td>--}}
{{--    --}}{{--                            <td class="cs-text_right">--}}
{{--    --}}{{--                                <strong>{{ number_format(array_sum(array_values($rounding_errors)), 0) }}</strong>--}}
{{--    --}}{{--                            </td>--}}
{{--    --}}{{--                        </tr>--}}
{{--    --}}{{--                    </tbody>--}}
{{--    --}}{{--                </table>--}}
{{--    --}}{{--            </div>--}}
{{--    --}}{{--        </div>--}}
{{--    --}}{{--    </div>--}}
{{--    @if ($groupedByPump->isNotEmpty())--}}
{{--        <table class="table-header-border">--}}
{{--            <thead>--}}
{{--            <tr>--}}
{{--                <th class="table-header-border" style="text-align:center; width: 25%;">Nozzle ID</th>--}}
{{--                <th class="table-header-border cs-text_right" style="width: 10%;">Total Quantity (Ltrs)</th>--}}
{{--                <th class="table-header-border cs-text_right" style="border-right: 2px solid #000;width: 15%;">Total--}}
{{--                    Amount (PKR)</th>--}}
{{--                <th class="table-header-border " style="text-align:center;width: 25%;">Nozzle ID</th>--}}
{{--                <th class="table-header-border cs-text_right" style="width: 10%;">Total Quantity (Ltrs)</th>--}}
{{--                <th class="table-header-border cs-text_right" style="border-right: 2px solid #000;width: 15%;">Total--}}
{{--                    Amount (PKR)</th>--}}

{{--            </tr>--}}
{{--            </thead>--}}
{{--            <tbody>--}}
{{--            @foreach ($groupedByPump->chunk(2) as $chunk)--}}
{{--                <tr>--}}
{{--                    @foreach ($chunk as $pump)--}}
{{--                        @php--}}
{{--                            // ✅ Difference (Closing - Opening)--}}
{{--                            $diffQty = $pump['closing_fuel'] - $pump['opening_fuel'];--}}

{{--                            // ✅ Amount = Difference × Rate--}}
{{--                            $diffAmount = $diffQty * $pump['rate'];--}}
{{--                        @endphp--}}

{{--                        <td style="border-left: 2px solid #000; text-align:center; display: flex;justify-content: space-between;">--}}
{{--                            <strong>{{ $pump['pump_id'] }}</strong>--}}
{{--                            <span>{{ $productDict[$pump['pump_id']] ?? '' }}</span>--}}
{{--                        </td>--}}

{{--                        --}}{{-- ✅ Difference Quantity --}}
{{--                        <td class="cs-text_right">--}}
{{--                            {{ number_format($diffQty, 0) }}--}}
{{--                        </td>--}}

{{--                        --}}{{-- ✅ Difference Amount --}}
{{--                        <td class="cs-text_right" style="border-right: 2px solid #000;">--}}
{{--                            {{ number_format($diffAmount, 0) }}--}}
{{--                        </td>--}}
{{--                    @endforeach--}}

{{--                    --}}{{-- Fill empty cells if only one pump in chunk --}}
{{--                    @if ($chunk->count() < 2)--}}
{{--                        <td></td>--}}
{{--                        <td></td>--}}
{{--                    @endif--}}
{{--                </tr>--}}
{{--            @endforeach--}}

{{--            </tbody>--}}
{{--        </table>--}}
{{--    @else--}}
{{--        <p>No pump data available.</p>--}}
{{--    @endif--}}


{{--    <div class=pagebreak></div>--}}
{{--    @foreach ($shifts as $index => $shift)--}}
{{--        <div class="cs-table cs-style1 cs-type2 cs-mb30">--}}
{{--            <div class="cs-round_border">--}}
{{--                <p class="cs-primary_color cs-semi_bold cs-f18 cs-mb0 cs-table_title">Shift summary</p>--}}
{{--                <div class="cs-table_responsive">--}}
{{--                    <table class="cs-border_less">--}}
{{--                        <tbody>--}}
{{--                        <tr class="cs-table_baseline">--}}
{{--                            <td class="cs-width_8">--}}
{{--                                <div class="cs-table cs-style2">--}}
{{--                                    <table>--}}
{{--                                        <tbody>--}}
{{--                                        <tr>--}}
{{--                                            <td><b class="cs-primary_color cs-semi_bold">Opening Date:</b>--}}
{{--                                                {{ \Carbon\Carbon::parse($shift->start_date)->format('d M Y H:i') }}--}}
{{--                                            </td>--}}
{{--                                            <td><b class="cs-primary_color cs-semi_bold">Closing Date:</b>--}}
{{--                                                {{ \Carbon\Carbon::parse($shift->end_date)->format('d M Y H:i') }}--}}
{{--                                            </td>--}}
{{--                                        </tr>--}}
{{--                                        <tr>--}}
{{--                                            <td>--}}
{{--                                                <b class="cs-primary_color cs-semi_bold">Nozzle ID:</b>--}}
{{--                                                <strong>{{ $shift->data->shift->pump_id }}</strong>--}}
{{--                                                <span class="cs-text-secondary">--}}
{{--                                                        ({{ $productDict[$shift->data->shift->pump_id] ?? 'Unknown Product' }})--}}
{{--                                                    </span>--}}
{{--                                            </td>--}}
{{--                                            <td>--}}
{{--                                                <b class="cs-primary_color cs-semi_bold">Product Rate:</b>--}}
{{--                                                {{ $shift->data->shift->rate / 100 }}--}}
{{--                                            </td>--}}
{{--                                        </tr>--}}

{{--                                        @php--}}
{{--                                            // ✅ Correct totalizer based total_qty--}}
{{--                                            $opening = $shift->data->shift->opening_fuel / 100;--}}
{{--                                            $closing = $shift->data->shift->closing_fuel / 100;--}}
{{--                                            $meterMax = 0; // adjust according to your nozzle meter max--}}
{{--//                                            $meterMax = 1000000; // adjust according to your nozzle meter max--}}

{{--                                            if ($closing < $opening) {--}}
{{--                                                $qty = ($closing + $meterMax) - $opening;--}}
{{--                                            } else {--}}
{{--                                                $qty = $closing - $opening;--}}
{{--                                            }--}}

{{--                                            // overwrite total_qty (system stores *100)--}}
{{--                                            $shift->data->shift->total_qty = $qty * 100;--}}
{{--                                        @endphp--}}

{{--                                        <tr>--}}
{{--                                            <td><b class="cs-primary_color cs-semi_bold">Total Quantity:</b>--}}
{{--                                                {{ number_format($shift->data->shift->total_qty / 100, 2) }} ltrs--}}
{{--                                            </td>--}}
{{--                                            <td><b class="cs-primary_color cs-semi_bold">New Rate (if Changed):</b>--}}
{{--                                                {{ $shift->data->shift->is_changed ? $shift->data->shift->new_rate / 100 : 'N/A' }}--}}
{{--                                            </td>--}}
{{--                                        </tr>--}}
{{--                                        <tr>--}}
{{--                                            <td><b class="cs-primary_color cs-semi_bold">Opening Totalizer:</b>--}}
{{--                                                {{ $opening }}--}}
{{--                                            </td>--}}
{{--                                            <td><b class="cs-primary_color cs-semi_bold">Closing Totalizer:</b>--}}
{{--                                                {{ $closing }}--}}
{{--                                            </td>--}}
{{--                                        </tr>--}}
{{--                                        <tr>--}}
{{--                                            <td><b class="cs-primary_color cs-semi_bold">Opening Balance:</b>--}}
{{--                                                {{ $shift->data->shift->opening_balance }}--}}
{{--                                            </td>--}}
{{--                                            <td><b class="cs-primary_color cs-semi_bold">Closing Balance:</b>--}}
{{--                                                {{ $shift->data->shift->closing_balance }}--}}
{{--                                            </td>--}}
{{--                                        </tr>--}}
{{--                                        <tr>--}}
{{--                                            <td><b class="cs-primary_color cs-semi_bold">Cashier Name:</b>--}}
{{--                                                {{ $shift->data->shift->cashier_name }}--}}
{{--                                            </td>--}}
{{--                                        </tr>--}}
{{--                                        </tbody>--}}
{{--                                    </table>--}}
{{--                                </div>--}}
{{--                            </td>--}}
{{--                        </tr>--}}
{{--                        </tbody>--}}
{{--                    </table>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}

{{--        @if (count($shift->data->paymentMethods) > 0)--}}
{{--            <div class="cs-table cs-style2 cs-mb15">--}}
{{--                <div class="cs-round_border">--}}
{{--                    <div class="cs-table_responsive">--}}
{{--                        <table>--}}
{{--                            <thead>--}}
{{--                            <tr class="cs-focus_bg">--}}
{{--                                <th class="cs-width_8 cs-semi_bold cs-primary_color">Payment Method</th>--}}
{{--                                <th class="cs-width_4 cs-semi_bold cs-primary_color cs-text_right">Total</th>--}}
{{--                            </tr>--}}
{{--                            </thead>--}}
{{--                            <tbody>--}}
{{--                            @foreach ($shift->data->paymentMethods as $payment)--}}
{{--                                @php--}}
{{--                                    $is_changed = $shift->data->shift->is_changed ?? false;--}}

{{--                                    if ($is_changed) {--}}
{{--                                        // ✅ Agar rate change hua ho to qty split karni hogi--}}
{{--                                        $qty_before_change = $shift->data->shift->rate_change_qty / 100;--}}
{{--                                        $qty_after_change  = ($shift->data->shift->total_qty / 100) - $qty_before_change;--}}

{{--                                        $old_rate = $shift->data->shift->rate / 100;--}}
{{--                                        $new_rate = $shift->data->shift->new_rate / 100;--}}

{{--                                        $expected_cash = ($qty_before_change * $old_rate) + ($qty_after_change * $new_rate);--}}
{{--//                                    dd($expected_cash);--}}
{{--                                    } else {--}}
{{--                                        // ✅ Normal case → opening & closing fuel--}}
{{--//                                        $closingA = $shift->data->shift->closing_fuel;--}}
{{--//                                        $openingA = $shift->data->shift->opening_fuel;--}}
{{--//                                        $totalEx  = $closingA - $openingA;  // ✅ Litres--}}
{{--//--}}
{{--//                                        $rate = $shift->data->shift->rate /100;--}}
{{--////                                        dd($rate,$shift->data->shift->rate);--}}
{{--//                                        $expected_cash = $totalEx * $rate;--}}
{{--//                                        dd($expected_cash);--}}
{{--                                        $closingA = $shift->data->shift->closing_fuel / 100;--}}
{{--                                        $openingA = $shift->data->shift->opening_fuel / 100;--}}
{{--                                        $totalEx  = $closingA - $openingA;  // ✅ litres--}}

{{--                                        $rate = $shift->data->shift->rate / 100; // ✅ rupees per litre--}}
{{--                                        $expected_cash = $totalEx * $rate;--}}
{{--                                    }--}}
{{--//dd($payment->total_amount,$payment->total_amount/100);--}}
{{--                                    // ✅ Actual Payment Method amount from DB--}}
{{--                                    $db_payment_amount = $payment->total_amount / 100;;--}}
{{--                                @endphp--}}

{{--                                <tr>--}}
{{--                                    <td class="cs-width_4">{{ $payment->pMethod }}</td>--}}
{{--                                    <td class="cs-width_2 cs-text_right sanat">--}}
{{--                                        @if(abs($expected_cash - $db_payment_amount) < 0.5)--}}
{{--                                            {{ number_format($db_payment_amount, 2) }}--}}
{{--                                        @else--}}
{{--                                            {{ number_format($expected_cash, 2) }}--}}
{{--                                        @endif--}}
{{--                                    </td>--}}
{{--                                </tr>--}}
{{--                            @endforeach--}}
{{--                            </tbody>--}}
{{--                        </table>--}}
{{--                    </div>--}}
{{--                </div>--}}

{{--                @php--}}
{{--                    $total_sale = array_sum(array_column($shift->data->paymentMethods, 'total_amount')) / 100;--}}

{{--                    // ✅ Make sure rates are divided by 100--}}
{{--                    $old_rate = $shift->data->shift->rate / 100;--}}
{{--                    $new_rate = $shift->data->shift->new_rate / 100;--}}

{{--                    if ($shift->data->shift->is_changed) {--}}
{{--                        $qty_before_change = $shift->data->shift->rate_change_qty / 100;--}}
{{--                        $qty_after_change = $shift->data->shift->total_qty / 100 - $qty_before_change;--}}

{{--                        $expected_total_sales =--}}
{{--                            ($qty_before_change * $old_rate + $qty_after_change * $new_rate);--}}

{{--                        $rounding_error = $total_sale - $expected_total_sales;--}}
{{--                    } else {--}}
{{--                        $rounding_error =--}}
{{--                            $total_sale - (($shift->data->shift->total_qty / 100) * $old_rate);--}}
{{--                    }--}}

{{--                    // Other payments adjustment--}}
{{--                    $shiftPayments = $shift->data->shiftPaymentWiseSales;--}}
{{--                    $other_payment_rounding_error = array_map(function ($sale) use ($shift, $old_rate) {--}}
{{--                        if ($sale->paymentmethod_id == 1) {--}}
{{--                            return 0;--}}
{{--                        }--}}
{{--                        $sale = (array) $sale;--}}
{{--                        $total_sale = $sale['total_sale'] / 100;--}}
{{--                        $total_qty = $sale['total_qty'] / 100;--}}
{{--                        $rate = $sale['price'] / 100 ?: $old_rate;--}}

{{--                        return $total_sale - ($total_qty * $rate);--}}
{{--                    }, $shiftPayments);--}}

{{--                    $other_payment_rounding_error = array_sum($other_payment_rounding_error);--}}

{{--                    $rounding_error -= $other_payment_rounding_error;--}}
{{--                @endphp--}}
{{--                @php--}}
{{--                    $total_amount = $shift->data->shift->total_amount / 100; // ✅ From DB--}}
{{--                    $old_rate = $shift->data->shift->rate / 100;             // ✅ Rs/Ltr--}}
{{--                    $new_rate = $shift->data->shift->new_rate / 100;         // ✅ New rate if changed--}}
{{--                    $is_changed = $shift->data->shift->is_changed ?? false;--}}

{{--                    if ($is_changed) {--}}
{{--                        // ✅ Jab rate change hua ho--}}
{{--                        $qty_before_change = $shift->data->shift->rate_change_qty / 100;--}}
{{--                        $qty_after_change  = ($shift->data->shift->total_qty / 100) - $qty_before_change;--}}

{{--                        $expected_cash = ($qty_before_change * $old_rate) + ($qty_after_change * $new_rate);--}}
{{--                    } else {--}}
{{--                        // ✅ Normal case → opening & closing fuel se calculate with old_rate--}}
{{--                        $closingA = $shift->data->shift->closing_fuel/100;--}}
{{--                        $openingA = $shift->data->shift->opening_fuel/100;--}}
{{--                        $totalEx  = $closingA - $openingA;  // ✅ Litres--}}

{{--                        $expected_cash = $totalEx * $old_rate;--}}
{{--                    }--}}

{{--                    // ✅ Condition check with tolerance (0.5 Rs rounding allowed)--}}
{{--                    if (abs($expected_cash - $total_amount) < 0.5) {--}}
{{--                        $cash_to_show = $total_amount;   // DB value is fine--}}
{{--                        $rounding_error_final = $total_amount - $expected_cash;--}}
{{--                    } else {--}}
{{--                        $cash_to_show = $expected_cash;  // Use calculated value--}}
{{--                        $rounding_error_final = 0;       // Ignore rounding error--}}
{{--                    }--}}
{{--                @endphp--}}

{{--                @php--}}
{{--                    // ✅ 1. Actual total sales from DB--}}
{{--                    $total_sale = array_sum(array_column($shift->data->paymentMethods, 'total_amount')) / 100;--}}

{{--                    // ✅ 2. Get old & new rates--}}
{{--                    $old_rate = $shift->data->shift->rate / 100;--}}
{{--                    $new_rate = $shift->data->shift->new_rate ? $shift->data->shift->new_rate / 100 : $old_rate;--}}

{{--                    // ✅ 3. Qty split if rate changed--}}
{{--                    if ($shift->data->shift->is_changed) {--}}
{{--                        $qty_before_change = ($shift->data->shift->rate_change_qty ?? 0) / 100;--}}
{{--                        $total_qty = $shift->data->shift->total_qty / 100;--}}
{{--                        $qty_after_change = $total_qty - $qty_before_change;--}}

{{--                        // ✅ Expected sales with mixed rates--}}
{{--                        $expected_total_sales = ($qty_before_change * $old_rate) + ($qty_after_change * $new_rate);--}}
{{--                    } else {--}}
{{--                        $total_qty = $shift->data->shift->total_qty / 100;--}}
{{--                        $expected_total_sales = $total_qty * $old_rate;--}}
{{--                    }--}}

{{--                    // ✅ 4. First level rounding error--}}
{{--                    $rounding_error_1 = $total_sale - $expected_total_sales;--}}

{{--                    // ✅ 5. Adjust per payment method--}}
{{--                    $shiftPayments = $shift->data->shiftPaymentWiseSales ?? [];--}}
{{--                    $other_payment_rounding_error = array_map(function ($sale) use ($old_rate, $new_rate) {--}}
{{--                        $sale = (array) $sale;--}}
{{--                        $total_sale = $sale['total_sale'] / 100;--}}
{{--                        $total_qty  = $sale['total_qty'] / 100;--}}
{{--                        $rate       = ($sale['price'] ?? 0) / 100;--}}

{{--                        if (!$rate) {--}}
{{--                            $rate = $old_rate; // fallback--}}
{{--                        }--}}

{{--                        return $total_sale - ($total_qty * $rate);--}}
{{--                    }, $shiftPayments);--}}

{{--                    $other_payment_rounding_error = array_sum($other_payment_rounding_error);--}}

{{--                    // ✅ 6. Final rounding error--}}
{{--//                    $rounding_error_1 -= $other_payment_rounding_error;--}}
{{--                    $rounding_error_1 -= 0;--}}
{{--                @endphp--}}


{{--                <div class="cs-invoice_footer">--}}
{{--                    <div class="cs-left_footer cs-mobile_hide"></div>--}}
{{--                    <div class="cs-right_footer">--}}
{{--                        <table>--}}
{{--                            <tbody>--}}


{{--                           <tr class="cs-border_none">--}}
{{--                                <td class="cs-width_3 cs-border_top_0 cs-bold cs-f16 cs-primary_color">Total Amount</td>--}}
{{--                                <td class="cs-width_3 cs-border_top_0 cs-bold cs-f16 cs-primary_color cs-text_right">--}}
{{--                                    Rs. {{ number_format($cash_to_show, 0) }}--}}
{{--                                </td>--}}
{{--                            </tr>--}}
{{--                            <tr class="cs-border_none">--}}
{{--                                <td class="cs-width_3 cs-border_top_0 cs-bold cs-f16 cs-primary_color">Rounding Amount</td>--}}
{{--                                <td class="cs-width_3 cs-border_top_0 cs-bold cs-f16 cs-primary_color cs-text_right">--}}
{{--                                    Rs. 0--}}
{{--                                </td>--}}
{{--                            </tr>--}}
{{--                            </tbody>--}}
{{--                        </table>--}}
{{--                    </div>--}}
{{--                </div>--}}

{{--            </div>--}}
{{--            @endif--}}
{{--            @endforeach--}}




{{--            </div>--}}
{{--            <div class="cs-invoice_btns cs-hide_print" style="--}}
{{--    position: fixed;--}}
{{--    right: 30px;--}}
{{--    bottom: 21px;--}}
{{--">--}}
{{--                <a href="javascript:window.print()" class="cs-invoice_btn cs-color1" style="margin-right: 5px">--}}
{{--                    <svg xmlns="http://www.w3.org/2000/svg" class="ionicon" viewBox="0 0 512 512">--}}
{{--                        <path--}}
{{--                            d="M384 368h24a40.12 40.12 0 0040-40V168a40.12 40.12 0 00-40-40H104a40.12 40.12 0 00-40 40v160a40.12 40.12 0 0040 40h24"--}}
{{--                            fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="32" />--}}
{{--                        <rect x="128" y="240" width="256" height="208" rx="24.32" ry="24.32" fill="none"--}}
{{--                              stroke="currentColor" stroke-linejoin="round" stroke-width="32" />--}}
{{--                        <path d="M384 128v-24a40.12 40.12 0 00-40-40H168a40.12 40.12 0 00-40 40v24" fill="none"--}}
{{--                              stroke="currentColor" stroke-linejoin="round" stroke-width="32" />--}}
{{--                        <circle cx="392" cy="184" r="24" />--}}
{{--                    </svg>--}}
{{--                    <span>Print</span>--}}
{{--                </a>--}}
{{--                <!-- <button onclick="downloadHTML()" id="download_btn" class="cs-invoice_btn cs-color2">--}}
{{--                            <svg xmlns="http://www.w3.org/2000/svg" class="ionicon" viewBox="0 0 512 512">--}}
{{--                                <title>Download</title>--}}
{{--                                <path--}}
{{--                                    d="M336 176h40a40 40 0 0140 40v208a40 40 0 01-40 40H136a40 40 0 01-40-40V216a40 40 0 0140-40h40"--}}
{{--                                    fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"--}}
{{--                                    stroke-width="32" />--}}
{{--                                <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"--}}
{{--                                    stroke-width="32" d="M176 272l80 80 80-80M256 48v288" />--}}
{{--                            </svg>--}}
{{--                            <span>Download</span>--}}
{{--                        </button> -->--}}
{{--            </div>--}}
{{--            </div>--}}
{{--            </div>--}}
{{--            <style>--}}

{{--            </style>--}}
{{--            <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.8.0/html2pdf.bundle.min.js"--}}
{{--                    integrity="sha512-w3u9q/DeneCSwUDjhiMNibTRh/1i/gScBVp2imNVAMCt6cUHIw6xzhzcPFIaL3Q1EbI2l+nu17q2aLJJLo4ZYg=="--}}
{{--                    crossorigin="anonymous" referrerpolicy="no-referrer"></script>--}}

{{--            <script>--}}
{{--                // add code to print document--}}
{{--                function downloadHTML() {--}}
{{--                    // Get the HTML content of the entire document--}}
{{--                    var htmlContent = document.documentElement.outerHTML;--}}

{{--                    // Create a Blob from the HTML content--}}
{{--                    var blob = new Blob([htmlContent], {--}}
{{--                        type: 'text/html'--}}
{{--                    });--}}

{{--                    // Create a link element--}}
{{--                    var element = document.body;--}}
{{--                    var now = new Date();--}}
{{--                    var formattedDate = now.getFullYear() + '-' +--}}
{{--                        ('0' + (now.getMonth() + 1)).slice(-2) + '-' +--}}
{{--                        ('0' + now.getDate()).slice(-2) + '_' +--}}
{{--                        ('0' + now.getHours()).slice(-2) + '-' +--}}
{{--                        ('0' + now.getMinutes()).slice(-2);--}}

{{--                    // Set the filename with the current date--}}
{{--                    var filename = 'shift_report_{{ $settings['name'] }}_' + formattedDate + '.pdf';--}}
{{--                    // Use html2pdf to convert the HTML to PDF and trigger the download--}}
{{--                    html2pdf(element, {--}}
{{--                        html2canvas: {--}}
{{--                            scale: 4--}}
{{--                        },--}}
{{--                        filename: filename--}}
{{--                    });--}}
{{--                }--}}

{{--                function downloadHTML2() {--}}
{{--                    // Get the element you want to convert to PDF--}}
{{--                    var element = document.body;--}}

{{--                    // Debug: Check if element is defined--}}
{{--                    if (!element) {--}}
{{--                        console.error('Element not found');--}}
{{--                        return;--}}
{{--                    }--}}

{{--                    // Get the current date and format it--}}
{{--                    var now = new Date();--}}
{{--                    var formattedDate = now.getFullYear() + '-' +--}}
{{--                        ('0' + (now.getMonth() + 1)).slice(-2) + '-' +--}}
{{--                        ('0' + now.getDate()).slice(-2) + '_' +--}}
{{--                        ('0' + now.getHours()).slice(-2) + '-' +--}}
{{--                        ('0' + now.getMinutes()).slice(-2);--}}

{{--                    // Set the filename with the current date--}}
{{--                    var filename = 'shift_report_{{ $settings['name'] }}_' + formattedDate + '.pdf';--}}

{{--                    // Debug: Log the filename--}}
{{--                    console.log('Filename:', filename);--}}

{{--                    // Use html2pdf with chainable methods--}}
{{--                    html2pdf(element, {--}}
{{--                        margin: 10,--}}
{{--                        filename: filename,--}}
{{--                        image: {--}}
{{--                            type: 'jpeg',--}}
{{--                            quality: 0.98--}}
{{--                        },--}}
{{--                        html2canvas: {--}}
{{--                            scale: 2--}}
{{--                        },--}}
{{--                        jsPDF: {--}}
{{--                            unit: 'mm',--}}
{{--                            format: 'a4',--}}
{{--                            orientation: 'portrait'--}}
{{--                        },--}}
{{--                        pagebreak: {--}}
{{--                            mode: ['css', 'legacy'],--}}
{{--                            before: '.page-break'--}}
{{--                        }--}}
{{--                    });--}}
{{--                }--}}
{{--            </script>--}}
{{--            </body>--}}

{{--            </html>--}}
{{--        @endif--}}
@if (!isset($excludeHeader) || !$excludeHeader)
    @include('reports.print_headers')
@endif

@if ($shifts->isEmpty())
    <div class="container mt-5">
        <h2 class="text-center">No Shift Report Data Available</h2>
    </div>
@else



    <!-- Cumulative Summary -->
    @php
        // Convert the shifts array into a Laravel Collection for easier manipulation
        $shiftsCollection = collect(json_decode(json_encode($shifts), true));

        // Group the shifts by 'pump_id'
        $groupedByPump = $shiftsCollection
            ->groupBy('pump_id')
            ->map(function ($pumpGroup) {
                return [
                    'pump_id' => $pumpGroup->first()['pump_id'],
                    'total_amount' => $pumpGroup->sum(function ($shift) {
                        // Access the total_amount within the nested 'data.shift' array
                        return $shift['data']['shift']['total_amount'] / 100;
                    }),
                    'total_quantity' => $pumpGroup->sum(function ($shift) {
                        // Access the total_amount within the nested 'data.shift' array
                        return $shift['data']['shift']['total_qty'] / 100;
                    }),
                ];
            })
            ->sortBy('pump_id');

        // dd($groupedByPump);

    @endphp
    @php
        /* ================= PAYMENT METHODS LOAD ================= */
        $paymentMethodsTable = DB::table('paymentmethod')->orderBy('id')->get();
        $paymentMethodMap = [];
        foreach ($paymentMethodsTable as $pm) {
            $paymentMethodMap[$pm->id] = $pm->Des;
        }
        $cashMethodName = $paymentMethodMap[1] ?? 'Cash';

        /* ================= ACCUMULATION ================= */
        $productTypes       = [];
        $cumulativeQty      = ['Total' => 0];
        $cumulativeExpected = [];
        $cumulativePayments = [];
        $grandPayments      = [];

        foreach ($shifts as $shift) {

            $shiftData = $shift->data->shift;
            $product   = $productDict[$shiftData->pump_id] ?? 'Unknown';

            if (!in_array($product, $productTypes)) {
                $productTypes[] = $product;
            }

            // Liters
            $qtyLiters = $shiftData->total_qty / 100;
            $rateRs    = $shiftData->rate / 100;

            $cumulativeQty[$product] = ($cumulativeQty[$product] ?? 0) + $qtyLiters;
            $cumulativeQty['Total']  += $qtyLiters;

            // Expected = liters × rate
            $expectedThis = $qtyLiters * $rateRs;
            $cumulativeExpected[$product] =
                ($cumulativeExpected[$product] ?? 0) + $expectedThis;

            // Payments accumulation
            foreach ($shift->data->shiftPaymentWiseSales as $row) {

                $method = $paymentMethodMap[$row->paymentmethod_id] ?? null;
                if (!$method) continue;

                $amountRs = ($method === $cashMethodName)
                    ? $row->total_sale / 100
                    : $row->total_sale;

                $cumulativePayments[$product][$method] =
                    ($cumulativePayments[$product][$method] ?? 0) + $amountRs;

                $grandPayments[$method] =
                    ($grandPayments[$method] ?? 0) + $amountRs;
            }
        }

        /* ================= METHODS TO DISPLAY ================= */
        $paymentMethods = [$cashMethodName];
        foreach ($grandPayments as $method => $amt) {
            if ($method !== $cashMethodName && $amt > 0) {
                $paymentMethods[] = $method;
            }
        }

        $grandTotalSales = 0;
        $totalRounding   = 0;
    @endphp



    <div class="cs-table cs-mb15 mt-4">
        <div>
            <p class="cs-primary_color cs-semi_bold cs-f18 text-center">
                Shift Sales Report
            </p>

            <div class="cs-table_responsive">
                <table class="table table-bordered table-striped">

                    <thead class="bg-primary text-white">
                    <tr>
                        <th>Product</th>
                        <th class="text-right">Quantity (Ltrs)</th>
                        @foreach ($paymentMethods as $method)
                            <th class="text-right">{{ $method }}</th>
                        @endforeach
                        <th class="text-right">Total Sales</th>
                        <th class="text-right">Rounding</th>
                    </tr>
                    </thead>

                    <tbody>

                    @foreach ($productTypes as $product)
                        @php
                            $qty      = $cumulativeQty[$product] ?? 0;
                            $expected = $cumulativeExpected[$product] ?? 0;

                            // CASH
                            $cash = $cumulativePayments[$product][$cashMethodName] ?? 0;

                            // ALL NON-CASH PAYMENTS (Card / Bank / POS / Online etc)
                            $nonCashTotal = 0;
                            foreach ($paymentMethods as $method) {
                                if ($method !== $cashMethodName) {
                                    $nonCashTotal += $cumulativePayments[$product][$method] ?? 0;
                                }
                            }

                            // ⭐ FINAL ROUNDING FORMULA
                            $rounding = ($expected - $nonCashTotal) - $cash;
                            if ($rounding < 0) $rounding = abs($rounding);

                            // Product total sales
                            $productTotal = 0;
                            foreach ($paymentMethods as $method) {
                                $productTotal += $cumulativePayments[$product][$method] ?? 0;
                            }

                            $grandTotalSales += $productTotal;
                            $totalRounding   += $rounding;
                        @endphp

                        <tr>
                            <td>{{ $product }}</td>
                            <td class="text-right">{{ number_format($qty, 2) }}</td>

                            @foreach ($paymentMethods as $method)
                                <td class="text-right">
                                    {{ number_format($cumulativePayments[$product][$method] ?? 0, 0) }}
                                </td>
                            @endforeach

                            <td class="text-right">{{ number_format($productTotal, 0) }}</td>
                            <td class="text-right">{{ number_format($rounding, 2) }}</td>
                        </tr>
                    @endforeach


                    <tr class="bg-secondary text-white font-weight-bold">
                        <td>Total</td>
                        <td class="text-right">{{ number_format($cumulativeQty['Total'], 2) }}</td>

                        @foreach ($paymentMethods as $method)
                            <td class="text-right">
                                {{ number_format($grandPayments[$method] ?? 0, 0) }}
                            </td>
                        @endforeach

                        <td class="text-right">{{ number_format($grandTotalSales, 0) }}</td>
                        <td class="text-right">{{ number_format($totalRounding, 2) }}</td>
                    </tr>

                    </tbody>
                </table>
            </div>
        </div>
    </div>











    @if ($groupedByPump->isNotEmpty())
        <table class="table-header-border">
            <thead>
            <tr>
                <th class="table-header-border" style="text-align:center; width: 25%;">Nozzle ID</th>
                <th class="table-header-border cs-text_right" style="width: 10%;">Total Quantity (Ltrs)</th>
                <th class="table-header-border cs-text_right" style="border-right: 2px solid #000;width: 15%;">Total
                    Amount (PKR)</th>
                <th class="table-header-border " style="text-align:center;width: 25%;">Nozzle ID</th>
                <th class="table-header-border cs-text_right" style="width: 10%;">Total Quantity (Ltrs)</th>
                <th class="table-header-border cs-text_right" style="border-right: 2px solid #000;width: 15%;">Total
                    Amount (PKR)</th>

            </tr>
            </thead>
            <tbody>
            @foreach ($groupedByPump->chunk(2) as $chunk)
                <tr>
                    @foreach ($chunk as $pump)
                        <td
                            style="border-left: 2px solid #000; text-align:center; display: flex;justify-content: space-between;">
                            <strong>{{ $pump['pump_id'] }}</strong> <span>
                                    {{ $productDict[$pump['pump_id']] }}</span>
                        </td>
                        <td class="cs-text_right">{{ number_format($pump['total_quantity'], 0) }}</td>
                        <td class="cs-text_right" style="border-right: 2px solid #000;">
                            {{ number_format($pump['total_amount'], 0) }}</td>
                    @endforeach
                    {{-- If the chunk has only one item, fill the remaining cells --}}
                    @if ($chunk->count() < 0)
                        <td></td>
                        <td></td>
                    @endif
                </tr>
            @endforeach
            </tbody>
        </table>
    @else
        <p>No pump data available.</p>
    @endif
    <div class="cs-table cs-mb15 print-only">
        <div class="">
            <p class="cs-primary_color cs-semi_bold cs-f18 cs-mb10" style="text-align:center">
                Nozzle Wise Summary
            </p>

            <div class="cs-table_responsive">
                <table>
                    <thead>
                    <tr class="cs-focus_bg">
                        <th>Nozzle</th>
                        <th class="cs-text_right">Opening</th>
                        <th class="cs-text_right">Closing</th>
                        <th class="cs-text_right">Total Quantity</th>
                        <th class="cs-text_right">Rate</th>
                        <th class="cs-text_right">Amount</th>
                        <th class="cs-text_right">Cashier
                            Name</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($shifts as $shift)
                        @php
                            $opening = $shift->data->shift->opening_fuel / 100;
                            $closing = $shift->data->shift->closing_fuel / 100;
                            $diff    = $closing - $opening;
                        @endphp
                        <tr>
                            <td>
                                {{ $shift->data->shift->pump_id }}
                                ({{ $productDict[$shift->data->shift->pump_id] ?? '' }})
                            </td>
                            <td class="cs-text_right">{{ number_format($opening, 2) }}</td>
                            <td class="cs-text_right">{{ number_format($closing, 2) }}</td>
                            <td class="cs-text_right">{{ number_format($diff, 2) }}</td>
                            <td class="cs-text_right">{{ number_format($shift->data->shift->rate/100 , 2) }}</td>
                            <td class="cs-text_right">{{ number_format($shift->data->shift->total_amount/100, 2) }}</td>
                            <td class="cs-text_right">{{ $shift->data->shift->cashier_name }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<style>
    .print-only {
        display: none; /* hide on screen */
    }
    @media print {
        .print-only {
            display: block !important;
            width: 100%;
            margin: 0;
            padding: 0;
            page-break-inside: avoid;
        }
        .no-print,
        .no-print * {
            display: none !important;
            height: 0 !important;
            margin: 0 !important;
            padding: 0 !important;
            overflow: hidden !important;
        }

        /* Remove page breaks for hidden content */
        .no-print ~ .pagebreak,
        .no-print .pagebreak {
            display: none !important;
        }
    }

</style>

    <div class=pagebreak></div>

    @foreach ($shifts as $index => $shift)
        <div class="cs-table cs-style1 cs-type2 cs-mb30 no-print">
            <div class="cs-round_border">
                <p class="cs-primary_color cs-semi_bold cs-f18 cs-mb0 cs-table_title">Shift summary</p>
                <div class="cs-table_responsive">
                    <table class="cs-border_less">
                        <tbody>
                        <tr class="cs-table_baseline">
                            <td class="cs-width_8">
                                <div class="cs-table cs-style2">
                                    <table>
                                        <tbody>
                                        <tr>
                                            <td><b class="cs-primary_color cs-semi_bold">Opening
                                                    Date:</b>
                                                {{ \Carbon\Carbon::parse($shift->start_date)->format('d M Y H:i') }}
                                            </td>
                                            <td><b class="cs-primary_color cs-semi_bold">Closing
                                                    Date:</b>
                                                {{ \Carbon\Carbon::parse(time: $shift->end_date)->format('d M Y H:i') }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><b class="cs-primary_color cs-semi_bold">Nozzle ID:</b>
                                                <strong>{{ $shift->data->shift->pump_id }}</strong> <span
                                                    class="cs-text-secondary">({{ $productDict[$shift->data->shift->pump_id] }})</span>
                                            </td>

                                            <td><b class="cs-primary_color cs-semi_bold">Product
                                                    Rate:</b> {{ $shift->data->shift->rate / 100 }} </td>
                                        </tr>
                                        <tr>
                                            <td><b class="cs-primary_color cs-semi_bold">Total
                                                    Quantity:</b>
                                                {{ $shift->data->shift->total_qty / 100 }} ltrs</td>
                                            <td><b class="cs-primary_color cs-semi_bold">New Rate (if
                                                    Changed):</b>
                                                {{ $shift->data->shift->is_changed ? $shift->data->shift->new_rate : 'N/A' }}
                                            </td>

                                        </tr>
                                        <tr>
                                            <td><b class="cs-primary_color cs-semi_bold">Opening
                                                    Totalizer:</b>
                                                {{ $shift->data->shift->opening_fuel / 100 }}</td>
                                            <td><b class="cs-primary_color cs-semi_bold">Closing
                                                    Totalizer:</b>
                                                {{ $shift->data->shift->closing_fuel / 100 }}</td>
                                        </tr>
                                        <tr>
                                            <td><b class="cs-primary_color cs-semi_bold">Opening
                                                    Balance:</b>
                                                {{ $shift->data->shift->opening_balance }}</td>
                                            <td><b class="cs-primary_color cs-semi_bold">Closing
                                                    Balance:</b>
                                                {{ $shift->data->shift->closing_balance }}</td>
                                        </tr>
                                        <tr>
                                            <td><b class="cs-primary_color cs-semi_bold">Cashier
                                                    Name:</b> {{ $shift->data->shift->cashier_name }}
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
{{--{{dd($shift->data)}}--}}
{{--        @if (count($shift->data->paymentMethods) > 0)--}}
        @if (count($shift->data->shiftPaymentWiseSales) > 0)
            <div class="cs-table cs-style2 cs-mb15 no-print">
                <div class="cs-round_border">
                    <div class="cs-table_responsive">
                        <table>
                            <thead>
                            <tr class="cs-focus_bg">
                                <th class="cs-width_8 cs-semi_bold cs-primary_color">Payment Method</th>
                                <!-- <th class="cs-width_4 cs-semi_bold cs-primary_color">Product</th> -->
                                <th class="cs-width_4 cs-semi_bold cs-primary_color cs-text_right">Total</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php
                                $paymentMethods = DB::table('paymentmethod')->pluck('Des','id');
                            @endphp

                            @foreach ($shift->data->shiftPaymentWiseSales as $payment)

                                @if($payment->total_sale > 0)

                                    @php
                                        $amount = $payment->paymentmethod_id == 1
                                                    ? $payment->total_sale / 100   // CASH only
                                                    : $payment->total_sale;        // Cards 그대로
                                    @endphp

                                    <tr>
                                        <td class="cs-width_4">
                                            {{ $paymentMethods[$payment->paymentmethod_id] ?? '' }}
                                        </td>

                                        <td class="cs-width_2 cs-text_right">
                                            {{ number_format($amount, 0) }}
                                        </td>
                                    </tr>

                                @endif

                            @endforeach


                            </tbody>
                        </table>
                    </div>
                </div>
{{--                @php--}}
{{--                    $total_sale = array_sum(array_column($shift->data->paymentMethods, 'total_amount')) / 100;--}}
{{--                    if ($shift->data->shift->is_changed) {--}}
{{--                        // Quantities before and after the rate change--}}
{{--                        $qty_before_change = $shift->data->shift->rate_change_qty;--}}
{{--                        $qty_after_change = $shift->data->shift->total_qty - $shift->data->shift->rate_change_qty;--}}
{{--                        // Rates before and after the change--}}
{{--                        $old_rate = $shift->data->shift->rate;--}}
{{--                        $new_rate = $shift->data->shift->new_rate;--}}
{{--                        // Expected total sales amount considering the rate change--}}
{{--                        $expected_total_sales =--}}
{{--                            ($qty_before_change * $old_rate + $qty_after_change * $new_rate) / 10000;--}}
{{--                        // Calculate the rounding error--}}
{{--                        $rounding_error = $total_sale - $expected_total_sales;--}}
{{--                    } else {--}}
{{--                        $rounding_error =--}}
{{--                            $total_sale - ($shift->data->shift->total_qty * $shift->data->shift->rate) / 100 / 100;--}}
{{--                    }--}}
{{--//                    $shiftPayments = $shift->data->shiftPaymentWiseSales;--}}
{{--$shiftPayments = $shift->data->shiftPaymentWiseSales ?? [];--}}

{{--                    $other_payment_rounding_error = array_map(function ($sale) use ($shift) {--}}
{{--                        // echo json_encode( $sale );--}}
{{--                        if ($sale->paymentmethod_id == 1) {--}}
{{--                            return 0;--}}
{{--                        }--}}
{{--                        $sale = (array) $sale;--}}
{{--                        $total_sale = $sale['total_sale'];--}}
{{--                        $total_qty = $sale['total_qty'];--}}
{{--                        $rate = $sale['price']; // assuming 'price' is the rate--}}
{{--                        if ($rate == 0) {--}}
{{--                            $rate = $shift->data->shift->rate;--}}
{{--                        }--}}
{{--                        return $total_sale - ($total_qty * $rate) / 100;--}}
{{--                    }, $shiftPayments);--}}

{{--                    $other_payment_rounding_error = array_sum($other_payment_rounding_error) / 100; // Divide by 100 if needed--}}

{{--//                    $rounding_error -= max(0,$other_payment_rounding_error);--}}
{{--                $rounding_error = max(0, $rounding_error - max(0, $other_payment_rounding_error));--}}

{{--                @endphp--}}
                @php

                    $shiftData = $shift->data->shift;
                    $payments  = $shift->data->shiftPaymentWiseSales ?? [];

                    /* =========================================================
                       1️⃣ TOTAL SALE FROM SHIFT PAYMENT WISE
                       ========================================================= */

                    $total_sale = 0;

                    foreach ($payments as $p) {
                        if ($p->paymentmethod_id == 1) {
                            // CASH stored in paisa
                            $total_sale += $p->total_sale / 100;
                        } else {
                            // Cards stored in rupees
                            $total_sale += $p->total_sale;
                        }
                    }


                    /* =========================================================
                       2️⃣ GET CASH RECORD ONLY
                       ========================================================= */

                    $cash = collect($payments)->firstWhere('paymentmethod_id', 1);

                    $cash_qty  = $cash->total_qty  ?? 0;
                    $cash_sale = $cash->total_sale ?? 0; // paisa


                    /* =========================================================
                       3️⃣ EXPECTED CASH SALE FROM QTY × RATE
                       ========================================================= */

                    if ($shiftData->is_changed) {

                        // qty split
                        $qty_before = $shiftData->rate_change_qty;
                        $qty_after  = $cash_qty - $shiftData->rate_change_qty;

                        $expected_cash_sale =
                            ($qty_before * $shiftData->rate +
                             $qty_after  * $shiftData->new_rate) / 10000;

                    } else {

                        $expected_cash_sale =
                            ($cash_qty * $shiftData->rate) / 10000;

                    }


                    /* =========================================================
                       4️⃣ ACTUAL CASH SALE (from payment table)
                       ========================================================= */

                    $actual_cash_sale = $cash_sale / 100;


                    /* =========================================================
                       5️⃣ FINAL ROUNDING (ONLY CASH)
                       ========================================================= */

                    $rounding_error = max(0, $actual_cash_sale - $expected_cash_sale);

                @endphp
                <div class="cs-invoice_footer">
                    <div class="cs-left_footer cs-mobile_hide"></div>
                    <div class="cs-right_footer">
                        <table>
                            <tbody>
                            <tr class="cs-border_none">
                                <td class="cs-width_3 cs-border_top_0 cs-bold cs-f16 cs-primary_color">
                                    Total Amount
                                </td>
                                <td class="cs-width_3 cs-border_top_0 cs-bold cs-f16 cs-primary_color cs-text_right">
                                    Rs. {{ number_format($total_sale, 0) }}
                                </td>
                            </tr>

                            <tr class="cs-border_none">
                                <td class="cs-width_3 cs-border_top_0 cs-bold cs-f16 cs-primary_color">
                                    Rounding Amount
                                </td>
                                <td class="cs-width_3 cs-border_top_0 cs-bold cs-f16 cs-primary_color cs-text_right">
                                    Rs. {{ number_format($rounding_error, 0) }}
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

{{--                <div class="cs-invoice_footer">--}}
{{--                    <div class="cs-left_footer cs-mobile_hide"></div>--}}
{{--                    <div class="cs-right_footer">--}}
{{--                        <table>--}}
{{--                            <tbody>--}}
{{--                            <tr class="cs-border_none">--}}
{{--                                <td class="cs-width_3 cs-border_top_0 cs-bold cs-f16 cs-primary_color">Total--}}
{{--                                    Amount</td>--}}
{{--                                <td--}}
{{--                                    class="cs-width_3 cs-border_top_0 cs-bold cs-f16 cs-primary_color cs-text_right">--}}
{{--                                    Rs.--}}
{{--                                    {{ number_format($total_sale, 0) }}--}}
{{--                                </td>--}}
{{--                            </tr>--}}
{{--                            <tr class="cs-border_none">--}}
{{--                                <td class="cs-width_3 cs-border_top_0 cs-bold cs-f16 cs-primary_color">Rounding--}}
{{--                                    Amount</td>--}}
{{--                                <td--}}
{{--                                    class="cs-width_3 cs-border_top_0 cs-bold cs-f16 cs-primary_color cs-text_right">--}}
{{--                                    Rs.--}}
{{--                                    {{ number_format($rounding_error, 0) }}--}}
{{--                                </td>--}}
{{--                            </tr>--}}
{{--                            </tbody>--}}
{{--                        </table>--}}
{{--                    </div>--}}
{{--                </div>--}}

            </div>
        @endif

        @if (count($shift->data->customers) > 0)
            <div class="cs-table cs-style1 cs-accent_10_bg no-print">
                <div class="cs-table_responsive">
                    <table>
                        <tbody>
                        <tr>
                            <td class="cs-primary_color cs-bold cs-f18">Customer</td>
                            <td class="cs-text_center cs-semi_bold">Vehicle</td>
                            <td class="cs-text_right cs-semi_bold">Amount (PKR)</td>
                        </tr>
                        @foreach ($shift->data->customers as $customer)
                            <tr>
                                <td class="cs-primary_color">{{ $customer->Des }}</td>
                                <td class="cs-primary_color cs-text_center">{{ $customer->RegNo ?? '' }}</td>
                                <td class="cs-primary_color cs-text_right">
                                    {{ number_format($customer->total_amt / 100, 0) }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <hr />
        @endif

        @if ($index < count($shifts) - 1)
            <div class=pagebreak></div>
            @endif
            @endforeach


            </div>
            <div class="cs-invoice_btns cs-hide_print" style="
    position: fixed;
    right: 30px;
    bottom: 21px;
">
                <a href="javascript:window.print()" class="cs-invoice_btn cs-color1" style="margin-right: 5px">
                    <svg xmlns="http://www.w3.org/2000/svg" class="ionicon" viewBox="0 0 512 512">
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
                <!-- <button onclick="downloadHTML()" id="download_btn" class="cs-invoice_btn cs-color2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="ionicon" viewBox="0 0 512 512">
                                <title>Download</title>
                                <path
                                    d="M336 176h40a40 40 0 0140 40v208a40 40 0 01-40 40H136a40 40 0 01-40-40V216a40 40 0 0140-40h40"
                                    fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="32" />
                                <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="32" d="M176 272l80 80 80-80M256 48v288" />
                            </svg>
                            <span>Download</span>
                        </button> -->
            </div>
            </div>
            </div>
            <style>

            </style>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.8.0/html2pdf.bundle.min.js"
                    integrity="sha512-w3u9q/DeneCSwUDjhiMNibTRh/1i/gScBVp2imNVAMCt6cUHIw6xzhzcPFIaL3Q1EbI2l+nu17q2aLJJLo4ZYg=="
                    crossorigin="anonymous" referrerpolicy="no-referrer"></script>

            <script>
                // add code to print document
                function downloadHTML() {
                    // Get the HTML content of the entire document
                    var htmlContent = document.documentElement.outerHTML;

                    // Create a Blob from the HTML content
                    var blob = new Blob([htmlContent], {
                        type: 'text/html'
                    });

                    // Create a link element
                    var element = document.body;
                    var now = new Date();
                    var formattedDate = now.getFullYear() + '-' +
                        ('0' + (now.getMonth() + 1)).slice(-2) + '-' +
                        ('0' + now.getDate()).slice(-2) + '_' +
                        ('0' + now.getHours()).slice(-2) + '-' +
                        ('0' + now.getMinutes()).slice(-2);

                    // Set the filename with the current date
                    var filename = 'shift_report_{{ $settings['name'] }}_' + formattedDate + '.pdf';
                    // Use html2pdf to convert the HTML to PDF and trigger the download
                    html2pdf(element, {
                        html2canvas: {
                            scale: 4
                        },
                        filename: filename
                    });
                }

                function downloadHTML2() {
                    // Get the element you want to convert to PDF
                    var element = document.body;

                    // Debug: Check if element is defined
                    if (!element) {
                        console.error('Element not found');
                        return;
                    }

                    // Get the current date and format it
                    var now = new Date();
                    var formattedDate = now.getFullYear() + '-' +
                        ('0' + (now.getMonth() + 1)).slice(-2) + '-' +
                        ('0' + now.getDate()).slice(-2) + '_' +
                        ('0' + now.getHours()).slice(-2) + '-' +
                        ('0' + now.getMinutes()).slice(-2);

                    // Set the filename with the current date
                    var filename = 'shift_report_{{ $settings['name'] }}_' + formattedDate + '.pdf';

                    // Debug: Log the filename
                    console.log('Filename:', filename);

                    // Use html2pdf with chainable methods
                    html2pdf(element, {
                        margin: 10,
                        filename: filename,
                        image: {
                            type: 'jpeg',
                            quality: 0.98
                        },
                        html2canvas: {
                            scale: 2
                        },
                        jsPDF: {
                            unit: 'mm',
                            format: 'a4',
                            orientation: 'portrait'
                        },
                        pagebreak: {
                            mode: ['css', 'legacy'],
                            before: '.page-break'
                        }
                    });
                }
            </script>
            </body>

            </html>
        @endif
