@if (!isset($excludeHeader) || !$excludeHeader)
    @include('reports.print_headers')
@endif
@if($query==null)
    <div class="container mt-5">
        <h2 class="text-center">No Stock Report Data Available</h2>
    </div>
@else

    <p class="cs-primary_color cs-semi_bold cs-f18 cs-mb0 cs-table_title" style="text-align: center">
        Adjustment Report
    </p>

    @php
        $scale = 100; // litres ×100
     $system=\Illuminate\Support\Facades\DB::table('SysConfig')->first();
      $sysMode = $system ? $system->Sys_Mode : null;
    @endphp
    @if($sysMode==3)
        <div class="container mt-5">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover text-center">
                    <thead class="table-dark">
                    <tr>
                        <th>Date time</th>
                        <th>Tank</th>
                        <th>Product</th>
                        <th>stock</th>
{{--                        <th>Vendor</th>--}}
                        <th>Adjustment</th>
                           <th>Comment</th>
                        {{--                        <th>Expected Stock (L)</th>--}}
                        {{--                        <th>Gain/Loss (L)</th>--}}
                    </tr>
                    </thead>

                    <tbody>
                    <tr>
                        @php
                            $tank=\Illuminate\Support\Facades\DB::table('tanks')->where('id',$query->tank_id)->first();
                            $product=\Illuminate\Support\Facades\DB::table('PRODUCT')->where('ICODE',$tank->product_id)->first();
                           $vendor=\Illuminate\Support\Facades\DB::table('VENDOR')->where('id',$query->vendor_id)->first();
                        @endphp
                        <th>{{$query->created_at->format('Y-m-d H:i')}}</th>
                        <th>{{$tank->tank_name}}</th>
                        <th>{{$product->ITMNAME}}</th>
                        <th>{{$query->adjustment_stock}}</th>
{{--                        <th>{{$vendor->VNAME}}</th>--}}
                        <th>{{ $query->adjustment == 1 ? 'yes' : 'no' }}</th>
                                                <th>{{$query->comments}}</th>
                        {{--                        <th>Expected Stock (L)</th>--}}
                        {{--                        <th>Gain/Loss (L)</th>--}}
                    </tr>
                    {{--                    @foreach($tankShiftLogs as $shiftLog)--}}
                    {{--                        @php--}}
                    {{--                            $tank = $shiftLog->tank;--}}
                    {{--                            $payload = is_array($shiftLog->data)--}}
                    {{--                                ? $shiftLog->data--}}
                    {{--                                : (json_decode($shiftLog->data ?? '[]', true) ?: []);--}}

                    {{--                            $shiftStart = $shiftLog->start_time;--}}
                    {{--                            $shiftEnd   = $shiftLog->end_time;--}}

                    {{--                            // Check if tank exists--}}
                    {{--                            if (!$tank) continue;--}}

                    {{--                            // 🔹 If tank inactive → force 0 sale/purchase--}}
                    {{--                            if ($tank->is_active == 0) {--}}
                    {{--                                $credits = 0;--}}
                    {{--                                $debits  = 0;--}}
                    {{--                            } else {--}}
                    {{--                                // Purchases (stock adjustments)--}}
                    {{--                                $credits = \DB::table('tank_stock_ledger')--}}
                    {{--                                    ->where('tank_id', $tank->id)--}}
                    {{--                                    ->where('transaction_type', 'stock_adjustment')--}}
                    {{--                                    ->whereBetween('created_at', [$shiftStart, $shiftEnd])--}}
                    {{--                                    ->sum('stock_change');--}}

                    {{--                                // Sales--}}
                    {{--                                $debits = 0;--}}
                    {{--                                if (!empty($payload['sales_data'])) {--}}
                    {{--                                    foreach ($payload['sales_data'] as $sale) {--}}
                    {{--                                        $debits += abs($sale['total_quantity'] ?? 0);--}}
                    {{--                                    }--}}
                    {{--                                }--}}
                    {{--                            }--}}

                    {{--                            $openingDipMm = $shiftLog->opening_dip ?? 0;--}}
                    {{--                            $closingDipMm = $shiftLog->closing_dip ?? 0;--}}
                    {{--                        @endphp--}}

                    {{--                        <tr class="{{ $tank->is_active == 0 ? 'table-secondary' : '' }}" data-purchase="{{ $credits/100 }}" data-sales="{{ $debits/100 }}">--}}
                    {{--                            <td class="cs-text_left">{{ $tank->tank_name ?? '—' }}</td>--}}
                    {{--                            <td>{{ number_format($openingDipMm / 100, 0) }}</td>--}}

                    {{--                            --}}{{-- 🔹 Opening Stock (API) --}}
                    {{--                            <td class="opening-stock"--}}
                    {{--                                data-tank="{{ $tank->id }}"--}}
                    {{--                                data-mm="{{ $openingDipMm }}">--}}
                    {{--                                {{ number_format($openingDipMm / 100, 0) }}--}}
                    {{--                            </td>--}}

                    {{--                            <td    data-purchase="{{ $credits/100 }}">{{ number_format($credits / 100, 2) }}</td>--}}
                    {{--                            <td  class="expected-sale"  data-sales="{{ $debits/100 }}"></td>--}}

                    {{--                            <td>{{ number_format($closingDipMm / 100, 0) }}</td>--}}

                    {{--                            --}}{{-- 🔹 Closing Stock (API) --}}
                    {{--                            <td class="closing-stock"--}}
                    {{--                                data-tank="{{ $tank->id }}"--}}
                    {{--                                data-mm="{{ $closingDipMm }}">--}}
                    {{--                                {{ number_format($closingDipMm / 100, 0) }}--}}
                    {{--                            </td>--}}

                    {{--                            <td class="expected-stock">—</td>--}}
                    {{--                            <td class="gain-loss">—</td>--}}
                    {{--                        </tr>--}}
                    {{--                    @endforeach--}}

                    </tbody>
                </table>
            </div>
        </div>
    @endif
@endif
