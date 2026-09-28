@extends('layouts.app')
@section('content')

<div class="container shift_log">
    <style>
        .main span {
            color: white !important;
        }
    </style>
    <div class="all_new_style mx-auto">
        <div class="card border-top border-0 border-4 border-danger">
            <div class="card-body">

                <!-- Date range filter -->
                <div class="d-flex gap-3 align-items-center justify-content-between">
                    <div>
                        <button class="btn btn-primary" id="printTop">Print</button>
                    </div>
                    <div class="d-flex gap-3 align-items-end mb-4">
                        <div class="d-flex align-items-center gap-3">
                            <div class="form-group">
                             <label class="d-block" for="startDate">Start Date: </label>
                        <input type="date" id="startDate" class="form-control d-inline-block" style="width: 200px;">
                        </div>
                       <div class="form-group">
                            <label class="d-block" for="endDate" class="ml-3">End Date: </label>
                        <input type="date" id="endDate" class="form-control d-inline-block" style="width: 200px;">
                       </div>
                        </div>
                        <div>
                            <button class="btn btn-secondary" id="filterDateRange">Filter</button>
                        </div>
                    </div>
                </div>
                <input type="hidden" id="select_all" value="">
                <!-- Select All Button -->


                <table class="table new_style_table table-striped" id="shiftTable" border="1">
                    <thead>
                        <tr>
                            <th><button class="btn btn-sm btn-light " style="padding: 0 8px;" id="selectAll">Select All</button></th>
                            <th>ID</th>
                            <th>Nozzle ID</th>
                            <th>Calendar ID</th>
                            <th>Start Date</th>
                            <th style="color: white !important;">End Date</th>
                            {{-- <th>Sale</th> --}}
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Data will be populated here -->
                    </tbody>
                </table>

                <!-- <button class="btn btn-primary px-5" id="printBottom" style="margin: 30px;">Print</button> -->
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
{{--<link rel="stylesheet" href="//cdn.datatables.net/2.1.6/css/dataTables.dataTables.min.css" />--}}
{{--<script type="text/javascript" src="//cdn.datatables.net/2.1.6/js/dataTables.min.js"></script>--}}
<link rel="stylesheet" href="https://cdn.datatables.net/2.1.6/css/dataTables.dataTables.min.css" />

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/2.1.6/js/dataTables.min.js"></script>

<script>
    @php
    $total_nozzles = DB::table('shift')->count();

    @endphp
    total_nozzles = <?php echo $total_nozzles; ?>;
    $(document).ready(function () {
        // Set default start date to yesterday
        var yesterday = new Date();
        yesterday.setDate(yesterday.getDate() - 1);
        var yesterdayString = yesterday.toISOString().split('T')[0];
        // $('#startDate').val(yesterdayString);

        // Initialize DataTable
        var table = $('#shiftTable').DataTable({
            "pageLength": total_nozzles, // Default number of rows to display
            "lengthMenu": [ [total_nozzles, total_nozzles*2, total_nozzles*3, total_nozzles*4], [total_nozzles*1, total_nozzles*2, total_nozzles*3, total_nozzles*4] ], // Options for 10, 25, 50, and 100 rows
            "columnDefs": [
                { "orderable": false, "targets": 0 } // Disable ordering on first column (Select All column)
            ]
        });


        // Fetch data from the API
        function fetchData(startDate = '', endDate = '') {
            $.ajax({
                url: '/api/shift/shift_logs',
                method: 'GET',
                data: {
                    start_date: startDate,
                    end_date: endDate
                },
                success: function (data) {
                    table.clear().draw();
                    data.forEach(function (shift) {
                        table.row.add([
                            `<input type="checkbox" class="shift-checkbox" value="${shift.id}">`,
                            shift.id,
                            shift.pump_id,
                            shift.calendar_id,

                            shift.start_date,
                            shift.end_date,
                            0
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

            if (!endDate) {
                endDate = startDate;
            }

            fetchData(startDate, endDate);
        });

        // Select All/Unselect All logic
        $('#selectAll').click(function (e) {
            e.stopPropagation(); // Prevent event bubbling to avoid triggering DataTable sorting
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
            window.location.href = `/shift_print?ids=${selectedIds.join(',')}`;
        });
    });
</script>
@endsection
