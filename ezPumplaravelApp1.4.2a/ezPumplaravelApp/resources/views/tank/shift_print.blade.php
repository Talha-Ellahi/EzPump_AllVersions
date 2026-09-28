@if (!isset($excludeHeader) || !$excludeHeader)
    @include('reports.print_headers')
@endif

@if($tankShiftLogs->isEmpty())
    <div class="container mt-5">
        <h2 class="text-center">No Stock Report Data Available</h2>
    </div>
@else

    <p class="cs-primary_color cs-semi_bold cs-f18 cs-mb0 cs-table_title" style="text-align: center">
        Stock Report
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
                    <th>Product</th>
                    <th>Opening Dip (mm)</th>
                    <th>Opening Stock (L)</th>
                    <th>Purchase (Litres)</th>
{{--                    <th>Expected Sales (Litres)</th>--}}
                    <th>Closing Dip (mm)</th>
                    <th>Closing Stock (L)</th>
{{--                    <th>Expected Stock (L)</th>--}}
{{--                    <th>Gain/Loss (L)</th>--}}
                </tr>
                </thead>

                <tbody>
                @foreach($tankShiftLogs as $shiftLog)
                    @php
                        $tank = $shiftLog->tank;
                        $payload = is_array($shiftLog->data)
                            ? $shiftLog->data
                            : (json_decode($shiftLog->data ?? '[]', true) ?: []);

                        $shiftStart = $shiftLog->start_time;
                        $shiftEnd   = $shiftLog->end_time;

                        // Check if tank exists
                        if (!$tank) continue;

                        // 🔹 If tank inactive → force 0 sale/purchase
                        if ($tank->is_active == 0) {
                            $credits = 0;
                            $debits  = 0;
                        } else {
                            // Purchases (stock adjustments)
                            $credits = \DB::table('tank_stock_ledger')
                                ->where('tank_id', $tank->id)
                                ->where('transaction_type', 'stock_adjustment')
                                ->whereBetween('created_at', [$shiftStart, $shiftEnd])
                                ->sum('stock_change');

                            // Sales
                            $debits = 0;
                            if (!empty($payload['sales_data'])) {
                                foreach ($payload['sales_data'] as $sale) {
                                    $debits += abs($sale['total_quantity'] ?? 0);
                                }
                            }
                        }

                        $openingDipMm = $shiftLog->opening_dip ?? 0;
                        $closingDipMm = $shiftLog->closing_dip ?? 0;
                    @endphp

                    <tr class="{{ $tank->is_active == 0 ? 'table-secondary' : '' }}" data-purchase="{{ $credits/100 }}" data-sales="{{ $debits/100 }}">
                        <td class="cs-text_left">{{ $tank->tank_name ?? '—' }}</td>
                        <td>{{ number_format($openingDipMm / 100, 0) }}</td>

                        {{-- 🔹 Opening Stock (API) --}}
                        <td class="opening-stock"
                            data-tank="{{ $tank->id }}"
                            data-mm="{{ $openingDipMm }}">
                            {{ number_format($openingDipMm / 100, 0) }}
                        </td>

                        <td    data-purchase="{{ $credits/100 }}">{{ number_format($credits / 100, 2) }}</td>
{{--                        <td  class="expected-sale"  data-sales="{{ $debits/100 }}"></td>--}}

                        <td>{{ number_format($closingDipMm / 100, 0) }}</td>

                        {{-- 🔹 Closing Stock (API) --}}
                        <td class="closing-stock"
                            data-tank="{{ $tank->id }}"
                            data-mm="{{ $closingDipMm }}">
                            {{ number_format($closingDipMm / 100, 0) }}
                        </td>

{{--                        <td class="expected-stock">—</td>--}}
{{--                        <td class="gain-loss">—</td>--}}
                    </tr>
                @endforeach

                </tbody>
            </table>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const stockCache = {}; // ✅ cache

            function fetchStock(el) {
                const tankId = el.dataset.tank;
                const mm = el.dataset.mm / 100; // normalize
                const cacheKey = tankId + '_' + mm;

                // ✅ Agar pehle se data hai → API CALL NAHI
                if (stockCache[cacheKey] !== undefined) {
                    return Promise.resolve(stockCache[cacheKey]);
                }

                // ❗ Sirf first time API call
                return axios.post('/api/tanks/convert-mm-to-totalizer', {
                    tank_id: tankId,
                    millimeter_value: mm
                }).then(res => {
                    const value = res.data.status === 'success'
                        ? parseFloat(res.data.totalizer_value)
                        : 0;

                    stockCache[cacheKey] = value; // ✅ cache save
                    return value;
                }).catch(() => {
                    stockCache[cacheKey] = 0;
                    return 0;
                });
            }

            document.querySelectorAll('tbody tr').forEach(async row => {

                const openingEl = row.querySelector('.opening-stock');
                const closingEl = row.querySelector('.closing-stock');
                const expectedEl = row.querySelector('.expected-stock');
                const expectedSl = row.querySelector('.expected-sale');
                const gainLossEl = row.querySelector('.gain-loss');

                const purchase = parseFloat(row.dataset.purchase) || 0;
                const sales = parseFloat(row.dataset.sales) || 0;
// console.log(row.dataset.sales);
                const openingStock = await fetchStock(openingEl);
                const closingStock = await fetchStock(closingEl);
                openingEl.innerText = openingStock.toFixed(2);
                closingEl.innerText = closingStock.toFixed(2);

                const sale = (openingStock + purchase) - closingStock;

                const expectedSale = sale > 0 ? sale : 0;

                const expectedStock = openingStock + purchase - expectedSale;

                console.log("Sale:", expectedSale);
                console.log("Stock:", expectedStock);

                expectedEl.innerText = expectedStock.toFixed(2);
                expectedSl.innerText = expectedSale.toFixed(2);

                const diff = closingStock - expectedStock;
                gainLossEl.innerText = diff.toFixed(2);

                if (diff < 0) gainLossEl.style.background = 'red';
                else if (diff > 0) gainLossEl.style.background = 'green';
                else gainLossEl.style.background = 'orange';

                gainLossEl.style.color = '#fff';
            });
        });
    </script>
@else
    <div class="container mt-5">
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover text-center">
                <thead class="table-dark">
                <tr>
                    <th>Product</th>
                    <th>Opening Dip (mm)</th>
                    <th>Opening Stock (L)</th>
                    <th>Purchase (Litres)</th>
                    <th>Sales (Litres)</th>
                    <th>Closing Dip (mm)</th>
                    <th>Closing Stock (L)</th>
                    <th>Expected Stock (L)</th>
                    <th>Gain/Loss (L)</th>
                </tr>
                </thead>

                <tbody>
                @foreach($tankShiftLogs as $shiftLog)
                    @php
                        $tank = $shiftLog->tank;
                        $payload = is_array($shiftLog->data)
                            ? $shiftLog->data
                            : (json_decode($shiftLog->data ?? '[]', true) ?: []);

                        $shiftStart = $shiftLog->start_time;
                        $shiftEnd   = $shiftLog->end_time;

                        // Check if tank exists
                        if (!$tank) continue;

                        // 🔹 If tank inactive → force 0 sale/purchase
                        if ($tank->is_active == 0) {
                            $credits = 0;
                            $debits  = 0;
                        } else {
                            // Purchases (stock adjustments)
                            $credits = \DB::table('tank_stock_ledger')
                                ->where('tank_id', $tank->id)
                                ->where('transaction_type', 'stock_adjustment')
                                ->whereBetween('created_at', [$shiftStart, $shiftEnd])
                                ->sum('stock_change');

                            // Sales
                            $debits = 0;
                            if (!empty($payload['sales_data'])) {
                                foreach ($payload['sales_data'] as $sale) {
                                    $debits += abs($sale['total_quantity'] ?? 0);
                                }
                            }
                        }

                        $openingDipMm = $shiftLog->opening_dip ?? 0;
                        $closingDipMm = $shiftLog->closing_dip ?? 0;
                    @endphp

                    <tr class="{{ $tank->is_active == 0 ? 'table-secondary' : '' }}" data-purchase="{{ $credits/100 }}" data-sales="{{ $debits/100 }}">
                        <td class="cs-text_left">{{ $tank->tank_name ?? '—' }}</td>
                        <td>{{ number_format($openingDipMm / 100, 0) }}</td>

                        {{-- 🔹 Opening Stock (API) --}}
                        <td class="opening-stock"
                            data-tank="{{ $tank->id }}"
                            data-mm="{{ $openingDipMm }}">
                            {{ number_format($openingDipMm / 100, 0) }}
                        </td>

                        <td    data-purchase="{{ $credits/100 }}">{{ number_format($credits / 100, 2) }}</td>
                        <td    data-sales="{{ $debits/100 }}">{{ number_format($debits / 100, 2) }}</td>

                        <td>{{ number_format($closingDipMm / 100, 0) }}</td>

                        {{-- 🔹 Closing Stock (API) --}}
                        <td class="closing-stock"
                            data-tank="{{ $tank->id }}"
                            data-mm="{{ $closingDipMm }}">
                            {{ number_format($closingDipMm / 100, 0) }}
                        </td>

                        <td class="expected-stock">—</td>
                        <td class="gain-loss">—</td>
                    </tr>
                @endforeach

                </tbody>
            </table>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const stockCache = {}; // ✅ cache

            function fetchStock(el) {
                const tankId = el.dataset.tank;
                const mm = el.dataset.mm / 100; // normalize
                const cacheKey = tankId + '_' + mm;

                // ✅ Agar pehle se data hai → API CALL NAHI
                if (stockCache[cacheKey] !== undefined) {
                    return Promise.resolve(stockCache[cacheKey]);
                }

                // ❗ Sirf first time API call
                return axios.post('/api/tanks/convert-mm-to-totalizer', {
                    tank_id: tankId,
                    millimeter_value: mm
                }).then(res => {
                    const value = res.data.status === 'success'
                        ? parseFloat(res.data.totalizer_value)
                        : 0;

                    stockCache[cacheKey] = value; // ✅ cache save
                    return value;
                }).catch(() => {
                    stockCache[cacheKey] = 0;
                    return 0;
                });
            }

            document.querySelectorAll('tbody tr').forEach(async row => {

                const openingEl = row.querySelector('.opening-stock');
                const closingEl = row.querySelector('.closing-stock');
                const expectedEl = row.querySelector('.expected-stock');
                const gainLossEl = row.querySelector('.gain-loss');

                const purchase = parseFloat(row.dataset.purchase) || 0;
                const sales = parseFloat(row.dataset.sales) || 0;
// console.log(row.dataset.sales);
                const openingStock = await fetchStock(openingEl);
                const closingStock = await fetchStock(closingEl);
                openingEl.innerText = openingStock.toFixed(2);
                closingEl.innerText = closingStock.toFixed(2);

                const expectedStock = Math.max(0, openingStock + purchase - sales);
                console.log(expectedStock );

                expectedEl.innerText = expectedStock.toFixed(2);

                const diff = closingStock - expectedStock;
                gainLossEl.innerText = diff.toFixed(2);

                if (diff < 0) gainLossEl.style.background = 'red';
                else if (diff > 0) gainLossEl.style.background = 'green';
                else gainLossEl.style.background = 'orange';

                gainLossEl.style.color = '#fff';
            });
        });
    </script>
@endif


@endif



