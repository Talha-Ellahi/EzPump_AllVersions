@extends('layouts.app')
@section('content')

<div class="row">
    @php
        $system = \Illuminate\Support\Facades\DB::table('SysConfig')->first();
        $sysMode = $system?->Sys_Mode;
    @endphp
    <div class="row">
        <div id="sidebarWrapper"
             class="{{ $sysMode==3 ? 'd-none col-lg-3' : 'col-lg-3' }}">

            @include('tank.sidebar')

        </div>


        {{-- ================= MAIN CONTENT ================= --}}
        <div id="mainContent"
             class="{{ $sysMode==3 ? 'col-lg-12' : 'col-lg-9' }} inner_content">

        <h2>Shift Logs</h2>
        <div class="card border-top border-0 border-4 border-danger">
            <div class="card-body">
                <button class="btn btn-primary px-5" id="printTop" style="margin: 30px;">Print</button>

                <!-- Date range filter -->
                <div class="mb-4">
                    <label for="startDate">Start Date: </label>
                    <input type="date" id="startDate" class="form-control d-inline-block" style="width: 200px;">
                    <label for="endDate" class="ml-3">End Date: </label>
                    <input type="date" id="endDate" class="form-control d-inline-block" style="width: 200px;">

                    <label for="tank_id" class="ml-3">Tank: </label>
                    <select id="tank_id" class="form-control d-inline-block" style="width: 200px;">
                        <option value="">All</option>
                        @foreach(\App\Models\Tank::all() as $tank)
                            <option value="{{ $tank->id }}">{{ $tank->tank_name }}</option>
                        @endforeach
                    </select>

                    <button class="btn btn-secondary" id="filterDateRange">Filter</button>
                </div>

                <!-- Select All Button -->
                <button class="btn btn-secondary mb-3" id="selectAll">Select All</button>

                <style>
        .tank_shift_logs span {
            color: white !important;
        }
    </style>


                <table class="table" id="shiftTable" border="1">
                    <thead>
                        <tr>
                            <th>Select</th>
                            <th>ID</th>
                            <th>Tank ID</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Opening Level</th>
                            <th>Closing Level</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Data will be populated here -->
                    </tbody>
                    <!-- <tfoot>
                    <tr>
                            <th>Select</th>
                            <th>ID</th>
                            <th>Tank ID</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Closing Totalizer</th>
                        </tr>
                    </tfoot> -->
                </table>

                <button class="btn btn-primary px-5" id="printBottom" style="margin: 30px;">Print</button>
            </div>
        </div>
    </div>
</div>
@endsection
    @if($sysMode==3)
        <script>
            document.addEventListener("DOMContentLoaded", function () {

                const toggleBtn = document.getElementById("sidebarToggle");
                const sidebar   = document.getElementById("sidebarWrapper");
                const content   = document.getElementById("mainContent");

                if (!toggleBtn) return;

                toggleBtn.addEventListener("click", function () {

                    sidebar.classList.toggle("d-none");

                    if (sidebar.classList.contains("d-none")) {
                        content.classList.remove("col-lg-9");
                        content.classList.add("col-lg-12");
                    } else {
                        content.classList.remove("col-lg-12");
                        content.classList.add("col-lg-9");
                    }

                });

            });
        </script>
    @endif
@section('scripts')
<link rel="stylesheet" href="//cdn.datatables.net/2.1.6/css/dataTables.dataTables.min.css" />
<script type="text/javascript" src="//cdn.datatables.net/2.1.6/js/dataTables.min.js"></script>


<script>
    @php
    $total_tanks = DB::table('tanks')->count();

    @endphp
    total_tanks = <?php echo $total_tanks; ?>;
    $(document).ready(function () {
        // Set default start date to yesterday
        var yesterday = new Date();
        yesterday.setDate(yesterday.getDate() - 1);
        var yesterdayString = yesterday.toISOString().split('T')[0];
        // $('#startDate').val(yesterdayString);

        // Initialize DataTable
        var table = $('#shiftTable').DataTable({
        "pageLength": total_tanks, // Default number of rows to display
        "lengthMenu": [ [total_tanks, total_tanks*2, total_tanks*3, total_tanks*4], [total_tanks*1, total_tanks*2, total_tanks*3, total_tanks*4] ]
    });


        // Fetch data from the API
        function fetchData(startDate = '', endDate = '', tank_id = '') {
            $.ajax({
                url: '/api/tanks/tank-shift-logs',
                method: 'GET',
                data: {
                    start_date: startDate,
                    end_date: endDate,
                    tank_id: tank_id
                },
                success: function (data) {
                    table.clear().draw();
                    data.tank_shift_logs.forEach(function (shift) {
                        table.row.add([
                            `<input type="checkbox" class="shift-checkbox" value="${shift.id}">`,
                            shift.id,
                            shift.tank_id,
                            shift.start_time,
                            shift.end_time,
                            // shift.opening_totalizer/100,
                            // shift.closing_totalizer/100
                            shift.opening_dip,
                            // shift.closing_dip/100
                            shift.closing_dip
                        ]).draw(false);
                    });
                }
            });
        }

        // Fetch initial data
        fetchData();

        // Filter by date range
        $('#filterDateRange').click(function () {
            let startDate = $('#startDate').val();
            let endDate = $('#endDate').val();
            let tank_id = $('#tank_id').val();

            if (!endDate) {
                endDate = startDate;
            }

            fetchData(startDate, endDate, tank_id);
        });

        // Select All/Unselect All logic
        $('#selectAll').click(function () {
            let isChecked = $(this).data('checked');
            $('.shift-checkbox').prop('checked', !isChecked);
            $(this).data('checked', !isChecked);
            $(this).text(isChecked ? 'Select All' : 'Unselect All');
        });

        // Print button click handler
        $('#printTop, #printBottom').click(function () {
            let selectedIds = [];
            $('.shift-checkbox:checked').each(function () {
                selectedIds.push($(this).val());
            });
            window.location.href = `/tanks/shift_print?ids=${selectedIds.join(',')}`;
        });
    });
</script>
@endsection
