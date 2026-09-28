@php
    $system = \Illuminate\Support\Facades\DB::table('SysConfig')->first();
    $sysMode = $system?->Sys_Mode;
@endphp

<div class="side_id">

    @auth
        @php $userRole = auth()->user()->role; @endphp

        <ul class="navbar-nav">

            @if ($userRole == 0)
                @if($sysMode!=3)
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('tank.add-tank') }}">Add Tank</a>
                </li>
                @endif

                <li class="nav-item">
                    <a class="nav-link" href="{{ route('tank.dip-chart-upload') }}">Dip Chart Upload</a>
                </li>
            @endif

            @if ($userRole <= 6)

                    @if($sysMode==3)
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('tank.tanks') }}">Dashboard</a>
                </li>
{{--                        <li class="nav-item">--}}
{{--                            <a class="nav-link" href="{{ route('tank.stock-history') }}">Report</a>--}}
{{--                        </li>--}}
                        <!-- Reports Dropdown for Mode 3 -->

                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('tank.tanks') }}">Tanks</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('tank.stock-history') }}">Stock History</a>
                        </li>
                    @endif
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('tank.shift') }}">Shift</a>
                </li>
                        @if($sysMode!=3)
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('tank.shift-logs') }}">
                        {{ $sysMode==3 ? 'Shift Report' : 'Shift Logs' }}
                    </a>
                </li>
                        @endif
            @endif

            @if ($userRole == 10)
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('tank.stock-history') }}">Stock History</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('tank.tanks') }}">Tanks</a>
                </li>
            @endif

            @if($sysMode==3)
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('alarm-history') }}">Alarms</a>
                </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="reportsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Reports
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="reportsDropdown">
                            <li>
                                <a class="dropdown-item" href="{{ route('tank.stock-history') }}">Stock History</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('tank.shift-logs') }}">Shift Report</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ url('/atg/leakage-report') }}">Leakage Reports</a>
                            </li>
                        </ul>
                    </li>
{{--                    <li class="nav-item">--}}
{{--                    <a class="nav-link" href="{{ url('/atg/leakage-report') }}">leakage Reports</a>--}}
{{--                </li>--}}
            @endif

        </ul>
    @endauth

</div>
