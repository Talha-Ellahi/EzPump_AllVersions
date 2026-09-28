@extends('layouts.app')

@section('content')
    <div class="row">
        @php
            $system = \Illuminate\Support\Facades\DB::table('SysConfig')->first();
            $sysMode = $system?->Sys_Mode;
        @endphp
        <div id="sidebarWrapper"
             class="{{ $sysMode==3 ? 'd-none col-lg-3' : 'col-lg-3' }}">

            @include('tank.sidebar')

        </div>


        {{-- ================= MAIN CONTENT ================= --}}
        <div id="mainContent"
             class="{{ $sysMode==3 ? 'col-lg-12' : 'col-lg-9' }} inner_content">

            <h2>Alarms Logs</h2>
            <div class="card border-top border-0 border-4 border-danger">
                <div class="card-body">
{{--                    <button class="btn btn-primary px-5" id="printTop" style="margin: 30px;">Print</button>--}}

                    <!-- Date range filter -->
{{--                    <div class="mb-4">--}}
{{--                        <label for="startDate">Start Date: </label>--}}
{{--                        <input type="date" id="startDate" class="form-control d-inline-block" style="width: 200px;">--}}
{{--                        <label for="endDate" class="ml-3">End Date: </label>--}}
{{--                        <input type="date" id="endDate" class="form-control d-inline-block" style="width: 200px;">--}}

{{--                        <label for="tank_id" class="ml-3">Tank: </label>--}}
{{--                        <select id="tank_id" class="form-control d-inline-block" style="width: 200px;">--}}
{{--                            <option value="">All</option>--}}
{{--                            @foreach(\App\Models\Tank::all() as $tank)--}}
{{--                                <option value="{{ $tank->id }}">{{ $tank->tank_name }}</option>--}}
{{--                            @endforeach--}}
{{--                        </select>--}}

{{--                        <button class="btn btn-secondary" id="filterDateRange">Filter</button>--}}
{{--                    </div>--}}

                    <!-- Select All Button -->
{{--                    <button class="btn btn-secondary mb-3" id="selectAll">Select All</button>--}}

                    <style>
                        .tank_shift_logs span {
                            color: white !important;
                        }
                    </style>
{{--                        <button class="btn btn-primary" onclick="openPrintPage()">--}}
{{--                            Print--}}
{{--                        </button>--}}


                {{-- ================= TABLE ================= --}}
                <table class="table table-bordered" id="alarmTable">
                    <thead>
                    <tr>
{{--                        <th>Select</th>--}}
                        <th>Tank</th>
                        <th>Product</th>
                        <th>Alarm Type</th>
                        <th>Alarm Info</th>
                        <th>Level (MM)</th>
                        <th>Status</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach($alarms as $alarm)
                        <tr>
{{--                            <td><input type="checkbox" class="shift-checkbox" value="{{$alarm->id}}"></td>--}}
                            <td>{{ $alarm->tank_name }}</td>
                            <td>{{ $alarm->product_name }}</td>
                            <td>{{ $alarm->alarm_type }}</td>
                            <td>{{ $alarm->alarm_info }}</td>
                            <td>{{ $alarm->level_mm }}</td>
                            <td>
                            <span class="badge bg-{{ $alarm->status=='active'?'danger':'success' }}">
                                {{ $alarm->status }}
                            </span>
                            </td>
                            <td>{{ $alarm->start_time }}</td>
                            <td>{{ $alarm->end_time ?? '-' }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
{{--                    <button class="btn btn-primary px-5" id="printBottom" style="margin: 30px;">Print</button>--}}

            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css"/>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <script>

        $(document).ready(function () {

            // ✅ Initialize DataTable
            let table = $('#alarmTable').DataTable({
                pageLength: 25,
                order: [[6,'desc']] // Sort by start time
            });

            // ✅ FILTER BUTTON
            $('#filterBtn').click(function () {

                let startDate = $('#startDate').val();
                let endDate   = $('#endDate').val();

                let url = "{{ route('alarm.history') }}";

                if(startDate || endDate){
                    url += "?start_date="+startDate+"&end_date="+endDate;
                }

                window.location.href = url;
            });

        });


        // ✅ PRINT FUNCTION
        function openPrintPage() {

            let startDate = $('#startDate').val();
            let endDate   = $('#endDate').val();

            let url = "/alarm-print";

            if(startDate || endDate){
                url += "?start_date="+startDate+"&end_date="+endDate;
            }

            window.open(url, "_blank"); // ✅ New Page Open Hoga
        }
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
            window.location.href = `/alarm-print?ids=${selectedIds.join(',')}`;
        });
    </script>

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
