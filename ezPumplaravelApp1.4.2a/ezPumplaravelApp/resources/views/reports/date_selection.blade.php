@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card mb-4 shadow-sm">
            <div class="card-header text-black">
                <h5 class="mb-0">Shifts for: {{ request('date', date('Y-m-d')) }}</h5>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered mb-0 align-middle" >
                        <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Shift Name</th>
                            <th>Timing</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @php
                            $config=DB::table('SysConfig')->first();
                                $shifts = [];
                                if ($config->NoofShifts == 1) {
                                    $shifts = [['id' => 6, 'name' => '24 Hour Shift', 'time' => '6 AM - Next Day 6 AM']];
                                } elseif ($config->NoofShifts == 2) {
                                    $shifts = [
                                        ['id' => 4, 'name' => 'Shift 1', 'time' => '6 AM - 6 PM'],
                                        ['id' => 5, 'name' => 'Shift 2', 'time' => '6 PM - 6 AM']
                                    ];
                                } else {
                                    $shifts = [
                                        ['id' => 1, 'name' => 'Shift 1', 'time' => '6 AM - 2 PM'],
                                        ['id' => 2, 'name' => 'Shift 2', 'time' => '2 PM - 10 PM'],
                                        ['id' => 3, 'name' => 'Shift 3', 'time' => '10 PM - 6 AM']
                                    ];
                                }
                        @endphp

                        @foreach($shifts as $index => $s)
                            @php
                                $closed = DB::table('shift')
                                            ->where('shift_type', $s['id'])
                                            ->whereDate('start_date', request('date', date('Y-m-d')))
                                            ->exists();

                                // 👇 Comparison report link
                                // Assuming you want: /comparison-report/{calendar_id}
                                $calendar_id = DB::table('shift_calendars')->where('work_date',date('Y-m-d'))->first(); // get from request or default 0
//                                $comparisonLink = route('comparison.report', ['calendar_id' => $calendar_id]);
                            @endphp
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td class="fw-bold">{{ $s['name'] }}</td>
                                <td>{{ $s['time'] }}</td>
                                <td>
            <span class="badge {{ $closed ? 'bg-danger' : 'bg-success' }}">
                {{ $closed ? 'Closed' : 'Open' }}
            </span>
                                </td>
                                <td>
                                    <a href="{{ route('tank.shift-print-by-date', ['date' => request('date', date('Y-m-d')), 'shift' => $s['id']]) }}"
                                       class="btn btn-sm btn-primary">
                                        <i class="fa fa-eye me-1"></i> View
                                    </a>

                                    <!-- Comparison Report Button -->
{{--                                    <a href="{{ route('comparison.report', ['calendarId' =>$calendar_id->id]) }}" target="_blank" class="btn btn-sm btn-warning">--}}
{{--                                        <i class="fa fa-chart-bar me-1"></i> Comparison--}}
{{--                                    </a>--}}

                                </td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- Search Bar -->
        <div class="card mb-4 shadow-sm">
            <div class="card-body">
                <h5 class="fw-bold mb-3">Search Shift By Date</h5>

                <div class="row">
                    <div class="col-md-3">
                        <label class="form-label">Select Date</label>
                        <input type="date" id="searchDate" class="form-control">
                    </div>
                </div>

            </div>
        </div>

        <!-- Last 7 Days Table -->
        {{-- ================= LAST 7 DAYS TABLE ================= --}}
        {{-- ================= LAST 7 DAYS TABLE ================= --}}
        <div class="card shadow-sm">
            <div class="card-header">
                <h5 class="mb-0">Last 7 Days Shift Records</h5>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive" style="max-height:450px;overflow-y:auto;">
                    <table class="table table-hover table-bordered align-middle" id="shiftTable">
                        <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Date</th>
                            <th>Shift Name</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>

                        @php
                            use Carbon\Carbon;

                            $lastWeek = DB::table('shift_log')
                                ->select(
                                    'pump_id',
                                    DB::raw('MAX(calendar_id) as calendar_id'),
                                    DB::raw('DATE(start_date) as shift_date'),
                                    DB::raw("MAX(data) as last_data"),
                                    DB::raw("DATE_FORMAT(MAX(start_date),'%Y-%m-%d') as last_start_date")
                                )
                                ->whereDate('start_date','>=',Carbon::now()->subDays(30))
                                ->groupBy('pump_id',DB::raw('DATE(start_date)'))
                                ->orderBy('shift_date','desc')
                                ->get()
                                ->unique('last_start_date');
                        @endphp

                        @foreach($lastWeek as $i => $lw)

                            @php
                                $shiftData = json_decode($lw->last_data ?? '{}',true);
                                $shiftType = $shiftData['shift_type'] ?? 0;

                                $shiftName = match($shiftType) {
                                    1 => 'Shift 1 (6 AM - 2 PM)',
                                    2 => 'Shift 2 (2 PM - 10 PM)',
                                    3 => 'Shift 3 (10 PM - 6 AM)',
                                    4 => 'Shift 1 (6 AM - 6 PM)',
                                    5 => 'Shift 2 (6 PM - 6 AM)',
                                    6 => '24 Hour Shift',
                                    default => 'Unknown Shift'
                                };

                                $status = $shiftData['status'] ?? "closed";
                                $shiftDate = $lw->last_start_date;
                            @endphp

                            <tr>
                                <td>{{ $i+1 }}</td>
                                <td class="shift-date">{{ $shiftDate }}</td>
                                <td>{{ $shiftName }}</td>
                                <td>
                            <span class="badge {{ $status ? 'bg-danger':'bg-success' }}">
                                {{ $status ? 'Closed':'Open' }}
                            </span>
                                </td>
                                <td>

                                    <a href="{{ route('tank.shift-print-by-date',[
                                    'date'=>$shiftDate,
                                    'shift'=>$shiftType
                                ]) }}"
                                       class="btn btn-sm btn-primary">
                                        View
                                    </a>

                                    {{-- 🔥 IMPORTANT: CLASS + DATA DATE --}}
                                    <button
                                        class="btn btn-warning open-comparison"
                                        data-bs-toggle="modal"
                                        data-bs-target="#calendarModal"
                                        data-date="{{ $shiftDate }}">

                                        Open Comparison

                                    </button>

                                </td>
                            </tr>

                        @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </div>


        {{-- ================= MODAL ================= --}}
        <div class="modal fade" id="calendarModal">
            <div class="modal-dialog">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title">Select Date & Calendar</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">

                        {{-- Date --}}
                        <div class="mb-3">
                            <label class="form-label">Date</label>

                            <select id="shiftDate" class="form-select">
                                <option value="">Select Date</option>

                                @php
                                    $dates = DB::table('shift_log')
                                        ->select(DB::raw('DATE(start_date) as date'))
                                        ->groupBy(DB::raw('DATE(start_date)'))
                                        ->orderBy('date','desc')
                                        ->pluck('date');
                                @endphp

                                @foreach($dates as $d)
                                    <option value="{{ $d }}">{{ $d }}</option>
                                @endforeach

                            </select>
                        </div>


                        {{-- Calendar --}}
                        <div class="mb-3">
                            <label class="form-label">Calendar ID</label>

                            <select id="calendarId" class="form-select">
                                <option value="">Select Calendar</option>
                            </select>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button id="goButton" class="btn btn-primary" disabled>
                            Go
                        </button>

                        <button class="btn btn-secondary"
                                data-bs-dismiss="modal">
                            Close
                        </button>
                    </div>

                </div>
            </div>
        </div>


        {{-- ================= JAVASCRIPT ================= --}}
        <script>
            document.addEventListener("DOMContentLoaded",function(){

                const shiftDate  = document.getElementById("shiftDate");
                const calendarId = document.getElementById("calendarId");
                const goButton   = document.getElementById("goButton");

                /* ==================================================
                   LOAD CALENDARS
                ================================================== */
                function loadCalendars(date){

                    if(!date) return;

                    calendarId.innerHTML =
                        '<option value="">Loading...</option>';

                    fetch(`/api/shift-calendars?date=${date}`)
                        .then(res => res.json())
                        .then(data => {

                            calendarId.innerHTML =
                                '<option value="">Select Calendar</option>';

                            data.forEach(cal=>{
                                let opt = document.createElement("option");
                                opt.value = cal.id;
                                opt.textContent = cal.id;
                                calendarId.appendChild(opt);
                            });

                        });
                }


                /* ==================================================
                   DATE CHANGE
                ================================================== */
                shiftDate.addEventListener("change",function(){
                    loadCalendars(this.value);
                    goButton.disabled = true;
                });


                /* ==================================================
                   CALENDAR CHANGE
                ================================================== */
                calendarId.addEventListener("change",function(){

                    if(this.value){
                        goButton.disabled = false;

                        goButton.onclick = function(){
                            window.open(`/comparison-report/${calendarId.value}`,'_blank');
                        };
                    }

                });


                /* ==================================================
                   🔥 BUTTON CLICK → AUTO SELECT DATE
                ================================================== */
                document.querySelectorAll(".open-comparison")
                    .forEach(btn=>{

                        btn.addEventListener("click",function(){

                            const rowDate = this.dataset.date;

                            // ✅ Directly set value
                            shiftDate.value = rowDate;

                            // ✅ Load calendar for that date
                            loadCalendars(rowDate);

                            goButton.disabled = true;

                        });

                    });

            });
        </script>

    </div>

    <!-- JS Date Filter -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {

            let input = document.getElementById("searchDate");
            let table = document.getElementById("shiftTable");

            input.addEventListener("change", function () {

                let selectedDate = this.value.trim();
                let rows = table.getElementsByTagName("tr");

                for (let i = 1; i < rows.length; i++) {  // skip header
                    let dateCell = rows[i].querySelector(".shift-date");

                    if (dateCell) {
                        let rowDate = dateCell.textContent.trim();

                        if (selectedDate === "" || rowDate === selectedDate) {
                            rows[i].style.display = "";
                        } else {
                            rows[i].style.display = "none";
                        }
                    }
                }

            });
        });
    </script>
@endsection


