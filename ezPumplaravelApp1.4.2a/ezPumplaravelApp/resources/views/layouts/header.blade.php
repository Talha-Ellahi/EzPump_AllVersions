{{--@php--}}
{{--    use \App\Models\Settings;--}}
{{--    $settings = Settings::getSettingsArray();--}}
{{--@endphp--}}
{{--<header class="login-header">--}}
{{--    <nav class="navbar navbar-expand-lg navbar-light bg-white rounded fixed-top rounded-0 shadow-sm">--}}
{{--        <div class="container-fluid">--}}
{{--            <a class="navbar-brand" href="/">--}}
{{--                <img class="me-1" src="/assets/images/ezpump logo landscape.png" width="150" alt="">--}}
{{--            </a>--}}
{{--            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"--}}
{{--                data-bs-target="#navbarSupportedContent1" aria-controls="navbarSupportedContent1" aria-expanded="false"--}}
{{--                aria-label="Toggle navigation"> <span class="navbar-toggler-icon"></span>--}}
{{--            </button>--}}


{{--            <div class="collapse navbar-collapse" id="navbarSupportedContent1">--}}
{{--                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">--}}
{{--                    @auth--}}
{{--                        @php $userRole = auth()->user()->role; @endphp--}}

{{--                        --}}{{-- Home Link --}}
{{--                        <li class="nav-item {{ request()->is('/') || request()->is('home') ? 'active' : '' }}">--}}
{{--                            <a class="nav-link" href="/">--}}
{{--                                <i class="lni lni-home m-1"></i>Home--}}
{{--                            </a>--}}
{{--                        </li>--}}

{{--                        --}}{{-- Limit Check: Accessible by roles 10, 6, 5, 0 --}}
{{--                        @if ($userRole <= 10)--}}
{{--                            <li class="nav-item {{ request()->is('check-customer-limit') ? 'active' : '' }}">--}}
{{--                                <a class="nav-link" href="/check-customer-limit">--}}
{{--                                    <i class="lni lni-map-marker m-1"></i>Limit Check--}}
{{--                                </a>--}}
{{--                            </li>--}}
{{--                        @endif--}}

{{--                        --}}{{-- Rate Change: Accessible only by role 0 (admin) --}}
{{--                        @if ($userRole <= 2)--}}
{{--                            <li class="nav-item {{ request()->is('Rates') ? 'active' : '' }}">--}}
{{--                                <a class="nav-link" href="/Rates">--}}
{{--                                    <i class="lni lni-money-location me-1"></i>Rate Change--}}
{{--                                </a>--}}
{{--                            </li>--}}
{{--                        @endif--}}

{{--                        --}}{{-- Dashboard Dropdown --}}
{{--                        @if ($userRole <= 6)--}}
{{--                            <li class="nav-item dropdown {{ request()->is('shift-dashboard') || request()->is('shift') ? 'active' : '' }}">--}}
{{--                                <a class="nav-link dropdown-toggle" href="#" id="dashboardDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">--}}
{{--                                    <i class="lni lni-dashboard m-1"></i>Dashboard--}}
{{--                                </a>--}}
{{--                                <a class="nav-link" href="/shift">--}}
{{--                                    <i class="lni lni-grid-alt m-1"></i>Shift Dashboard--}}
{{--                                </a>--}}
{{--                                <ul class="dropdown-menu" aria-labelledby="dashboardDropdown">--}}
{{--                                    <li>--}}
{{--                                        <a class="dropdown-item" href="/shift-dashboard">--}}
{{--                                            <i class="lni lni-dashboard m-1"></i>New Dashboard--}}
{{--                                        </a>--}}
{{--                                    </li>--}}
{{--                                    <li>--}}
{{--                                        <a class="dropdown-item" href="/shift">--}}
{{--                                            <i class="lni lni-grid-alt m-1"></i>Shift Dashboard--}}
{{--                                        </a>--}}
{{--                                    </li>--}}
{{--                                </ul>--}}
{{--                            </li>--}}

{{--                            --}}{{-- Shift Dropdown --}}
{{--                            <li class="nav-item dropdown {{ request()->is('nozzle') || request()->is('shift_logs') ? 'active' : '' }}">--}}
{{--                                <a class="nav-link dropdown-toggle" href="#" id="shiftDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">--}}
{{--                                    <i class="lni lni-grid-alt m-1"></i>Shift--}}
{{--                                </a>--}}
{{--                                <ul class="dropdown-menu" aria-labelledby="shiftDropdown">--}}
{{--                                    <li>--}}
{{--                                        <a class="dropdown-item" href="/nozzle">--}}
{{--                                            <i class="lni lni-reload m-1"></i>Shift Change--}}
{{--                                        </a>--}}
{{--                                    </li>--}}
{{--                                    <li>--}}
{{--                                        <a class="dropdown-item" href="/shift_logs">--}}
{{--                                            <i class="lni lni-timer m-1"></i>Shift Logs--}}
{{--                                        </a>--}}
{{--                                    </li>--}}
{{--                                </ul>--}}
{{--                            </li>--}}
{{--                            --}}{{-- Reports Dropdown --}}
{{--                            <li class="nav-item dropdown {{ request()->is('summary-reports') || request()->is('shift_logs') || request()->is('shift_date_select') ? 'active' : '' }}">--}}
{{--                                <a class="nav-link dropdown-toggle" href="#" id="reportsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">--}}
{{--                                    <i class="lni lni-files m-1"></i>Reports--}}
{{--                                </a>--}}
{{--                                <ul class="dropdown-menu" aria-labelledby="reportsDropdown">--}}
{{--                                    <li>--}}
{{--                                        <a class="dropdown-item" href="/summary-reports">--}}
{{--                                            <i class="lni lni-stats-up m-1"></i>Summary Reports--}}
{{--                                        </a>--}}
{{--                                    </li>--}}
{{--                                    <!-- <li>--}}
{{--                                        <a class="dropdown-item" href="/register-report">--}}
{{--                                            <i class="lni lni-clipboard m-1"></i>Register Report--}}
{{--                                        </a>--}}
{{--                                    </li> -->--}}
{{--                                    <li>--}}
{{--                                        <a class="dropdown-item" href="/shift_logs">--}}
{{--                                            <i class="lni lni-timer m-1"></i>Shift Logs--}}
{{--                                        </a>--}}
{{--                                    </li>--}}
{{--                                    <li>--}}
{{--                                        <a class="dropdown-item" href="/shift_date_select">--}}
{{--                                            <i class="lni lni-book m-1"></i>Combined Report--}}
{{--                                        </a>--}}
{{--                                    </li>--}}
{{--                                </ul>--}}
{{--                            </li>--}}
{{--                            --}}{{-- Tanks Link --}}
{{--                            <li class="nav-item {{ request()->routeIs('tank.*') ? 'active' : '' }}">--}}
{{--                                <a class="nav-link" href="{{ route('tank.tanks') }}">--}}
{{--                                    <i class="lni lni-drop m-1"></i>Tanks--}}
{{--                                </a>--}}
{{--                            </li>--}}
{{--                            @if(auth()->user()->role == 0 || auth()->user()->role == 2)--}}
{{--                                <li class="nav-item" id="bypassButtonContainer" >--}}
{{--                                    <button style="border:none; background: none; position:relative;"--}}
{{--                                            class="nav-link"--}}
{{--                                            id="alert_btn2"--}}
{{--                                            data-bs-toggle="modal"--}}
{{--                                            data-bs-target="#lockedUsersModal">--}}
{{--                                        <i class="lni lni-lock m-1"></i>--}}
{{--                                        <span id="alertBadge" class="blink"--}}
{{--                                              style="display:none;--}}
{{--                      position:absolute;--}}
{{--                      top:0;--}}
{{--                      right:0;--}}
{{--                      background:#ef4523;--}}
{{--                      color:#fff;--}}
{{--                      font-size:10px;--}}
{{--                      padding:5px 5px;--}}
{{--                      border-radius:80%;--}}
{{--                      animation: blink 1s infinite;">--}}
{{--                Shift over--}}
{{--            </span>--}}
{{--                                    </button>--}}
{{--                                </li>--}}

{{--                                <script>--}}
{{--                                    function checkLockedUsers() {--}}
{{--                                        fetch('/api/locked-users')--}}
{{--                                            .then(res => res.json())--}}
{{--                                            .then(locked => {--}}
{{--                                                const badge = document.getElementById('alertBadge');--}}
{{--                                                const btnContainer = document.getElementById('bypassButtonContainer');--}}

{{--                                                // console.log('bypass_active_until:', locked.bypass_active_until);--}}


{{--                                                if (locked.bypass_active_until) {--}}
{{--                                                    // Bypass Active → Hide Button--}}
{{--                                                    badge.style.display = 'block';--}}
{{--                                                    badge.classList.add('blink');--}}
{{--                                                    if (btnContainer) btnContainer.style.display = 'block';--}}

{{--                                                    console.log('locked:', locked);--}}
{{--                                                    return;--}}
{{--                                                }--}}

{{--                                                if (locked.locked) {--}}
{{--                                                    console.log('locked12:', locked);--}}
{{--                                                    // Shift Locked → Show Badge & Button--}}
{{--                                                    if (badge) {--}}
{{--                                                        badge.style.display = 'block';--}}
{{--                                                        badge.classList.add('blink');--}}
{{--                                                    }--}}
{{--                                                    if (btnContainer) btnContainer.style.display = 'none';--}}
{{--                                                } else {--}}
{{--                                                    console.log('notlocked:', locked);--}}
{{--                                                    // Shift Not Locked → Hide Badge & Button--}}
{{--                                                    if (badge) {--}}
{{--                                                        badge.style.display = 'none';--}}
{{--                                                        badge.classList.remove('blink');--}}
{{--                                                    }--}}
{{--                                                    if (btnContainer) btnContainer.style.display = 'none';--}}
{{--                                                }--}}
{{--                                            });--}}
{{--                                    }--}}

{{--                                    // Initial check and interval--}}
{{--                                    checkLockedUsers();--}}
{{--                                    setInterval(checkLockedUsers, 300000);--}}

{{--                                    // Request Notification Permission--}}
{{--                                    if (Notification.permission !== 'denied') {--}}
{{--                                        Notification.requestPermission();--}}
{{--                                    }--}}


{{--                                    // Initial check and set interval--}}
{{--                                    checkLockedUsers();--}}
{{--                                    setInterval(checkLockedUsers, 300000);--}}
{{--                                </script>--}}

{{--                                <style>--}}
{{--                                    @keyframes blink {--}}
{{--                                        0%, 100% { opacity: 1; }--}}
{{--                                        50% { opacity: 0.3; }--}}
{{--                                    }--}}

{{--                                    .blink {--}}
{{--                                        animation: blink 1s infinite;--}}
{{--                                    }--}}

{{--                                    /* Badge Styles */--}}
{{--                                    #alertBadge {--}}
{{--                                        box-shadow: 0 0 5px rgba(239, 69, 35, 0.7);--}}
{{--                                        font-weight: bold;--}}
{{--                                        min-width: 18px;--}}
{{--                                        text-align: center;--}}
{{--                                    }--}}
{{--                                </style>--}}


{{--                            @endif--}}

{{--                            <li class="nav-item">--}}
{{--                                <button style="border:none; background: none;" class="nav-link" id="alert_btn">--}}
{{--                                    <i class="lni lni-alarm m-1"></i>Alerts--}}
{{--                                </button>--}}
{{--                            </li>--}}
{{--                            <div id="alert_div" class="position-absolute rounded" style="background: white;border:1px solid #c5c4c4; box-shadow: 0px 0px 10px -5px #000000; padding: 15px 8px;width: 300px; height:330px; overflow: auto; max-width: 300px; max-height: 330px; right: 43px; top: 55px; z-index: 999;">--}}
{{--                            </div>--}}
{{--                        @endif--}}

{{--                    @endauth--}}
{{--                    @guest--}}
{{--                        @if (Route::has('login'))--}}
{{--                            <li class="nav-item">--}}
{{--                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>--}}
{{--                            </li>--}}
{{--                        @endif--}}
{{--                    @else--}}
{{--                        <li class="nav-item dropdown" style="list-style-type: none;">--}}
{{--                            <a id="navbarDropdown" class="nav-link dropdown-toggle show" href="#" role="button"--}}
{{--                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="true" >--}}
{{--                                <img src="storage/{{ $settings['logo'] }}" width="30" alt="">--}}
{{--                                {{ Auth::user()->name }}--}}
{{--                            </a>--}}
{{--                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">--}}
{{--                                @if ($userRole == 0)--}}
{{--                                    <a class="dropdown-item" href="{{ route('index.settings') }}">--}}
{{--                                        <i class="lni lni-cog m-1"></i>Settings--}}
{{--                                    </a>--}}
{{--                                    <a class="dropdown-item" href="{{ route('user-management.index') }}">--}}
{{--                                        <i class="lni lni-users m-1"></i>User Management--}}
{{--                                    </a>--}}
{{--                                    <a class="dropdown-item" href="{{url('admin/bypass-limit')}}">--}}
{{--                                        <i class="lni lni-users m-1"></i>By Pass Limit--}}
{{--                                    </a>--}}
{{--                                    <a class="dropdown-item" href="">--}}
{{--                                        <i class="lni lni-users m-1"></i>Dispensers--}}
{{--                                    </a>--}}
{{--                                    <div class="dropdown-divider"></div>--}}
{{--                                @endif--}}
{{--                                <a class="dropdown-item" href="#">--}}
{{--                                    <i class="lni lni-user m-1"></i>Profile--}}
{{--                                </a>--}}
{{--                                <div class="dropdown-divider"></div>--}}
{{--                                <a class="dropdown-item" href="{{ route('logout') }}"--}}
{{--                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">--}}
{{--                                    <i class="lni lni-power-switch m-1"></i>{{ __('Logout') }}--}}
{{--                                </a>--}}
{{--                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">--}}
{{--                                    @csrf--}}
{{--                                </form>--}}
{{--                            </div>--}}
{{--                        </li>--}}
{{--                    @endguest--}}
{{--                </ul>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </nav>--}}


{{--    <script src="{{ asset('/assets/js/alerts.js') }}"></script>--}}
{{--    <!-- Modal for Locked Users -->--}}
{{--    @auth--}}
{{--    <div class="modal fade" id="lockedUsersModal" tabindex="-1" aria-labelledby="lockedUsersModalLabel" aria-hidden="true">--}}
{{--        <div class="modal-dialog">--}}
{{--            <form method="POST" action="{{ route('admin.bypass.unlock') }}">--}}
{{--                @csrf--}}
{{--                <div class="modal-content">--}}
{{--                    <div class="modal-header">--}}
{{--                        <h5 class="modal-title">Bypass Screen Lock</h5>--}}
{{--                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>--}}
{{--                    </div>--}}
{{--                    <div class="modal-body">--}}
{{--                        <p>Are you sure you want to bypass the screen lock for this user?</p>--}}
{{--                        <div class="mb-3">--}}
{{--                            <label for="addHours" class="form-label">Bypass Duration (Hours)</label>--}}
{{--                            <input type="number" class="form-control" name="add_hours" id="addHours" min="1" required>--}}
{{--                        </div>--}}
{{--                        <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">--}}
{{--                    </div>--}}
{{--                    <div class="modal-footer">--}}
{{--                        <button type="submit" class="btn btn-warning">Bypass & Unlock</button>--}}
{{--                        <button type="button" class="btn btn-warning" onclick="checkBypassLimit()">Bypass Lock</button>--}}

{{--                    </div>--}}
{{--                </div>--}}
{{--            </form>--}}
{{--        </div>--}}
{{--    </div>--}}

{{--    @endauth--}}
{{--    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>--}}
{{--    <script>--}}
{{--        document.addEventListener("DOMContentLoaded", function () {--}}
{{--            @if(session('error') || (is_object($errors) && $errors->any()))--}}
{{--            const modal = new bootstrap.Modal(document.getElementById('lockedUsersModal'));--}}
{{--            modal.show();--}}
{{--            @endif--}}
{{--        });--}}
{{--    </script>--}}

{{--    <script>--}}
{{--        function checkBypassLimit() {--}}
{{--            fetch('/check-bypass-limit')--}}
{{--                .then(res => res.json())--}}
{{--                .then(data => {--}}
{{--                    if (data.status) {--}}
{{--                        // Limit is available, show modal--}}
{{--                        const myModal = new bootstrap.Modal(document.getElementById('lockedUsersModal'));--}}
{{--                        myModal.show();--}}
{{--                    } else {--}}
{{--                        // Show error alert (or use toastr/swal)--}}
{{--                        alert(data.message); // replace with toast if needed--}}
{{--                    }--}}
{{--                })--}}
{{--                .catch(err => {--}}
{{--                    alert('Failed to check bypass limit.');--}}
{{--                    console.error(err);--}}
{{--                });--}}
{{--        }--}}
{{--    </script>--}}

{{--    <!-- Simple unlock button in your HTML -->--}}


{{--    <!-- Make sure this meta tag exists in your layout -->--}}

{{--    <style>--}}
{{--        .blink { animation: blink-animation 1s steps(5, start) infinite; }--}}
{{--        @keyframes blink-animation { to { visibility: hidden; } }--}}
{{--    </style>--}}
{{--</header>--}}
@php
    use App\Models\Settings;
    use Illuminate\Support\Facades\Schema;
    use Illuminate\Support\Facades\DB;

    $settings = Settings::getSettingsArray();


    // Default NORMAL mode (1)
    $installationMode = 1;
    $sysMode = 1; // Initialize default value

    // Agar column exist karta hai to hi query run kare
    if (Schema::hasColumn('SysConfig', 'Sys_Mode')) {
        $installationMode = DB::table('SysConfig')->value('Sys_Mode') ?? 1;

        $system = \Illuminate\Support\Facades\DB::table('SysConfig')->first();
        $sysMode = $system?->Sys_Mode ?? 1;

    }
@endphp

<header class="login-header">
    <nav class="navbar navbar-expand-lg navbar-light bg-white rounded fixed-top rounded-0 shadow-sm">

        <div class="container-fluid">
            @if($sysMode==3)
            <div class="mb-1">
{{--                <button id="sidebarToggle"--}}
{{--                        class="btn btn-dark">--}}
{{--                    ☰--}}
{{--                </button>--}}
                <button id="sidebarToggle" class="btn d-flex align-items-center justify-content-center">

                    <!-- SVG Icon -->
                    <img src="{{ asset('assets/images/menu.svg') }}"
                         alt="Menu"
                         width="22"
                         height="22">

                </button>
            </div>
            @endif
            <a class="navbar-brand" href="/">
                <img class="me-1" src="/assets/images/ezpump logo landscape.png" width="150" alt="">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent1" aria-controls="navbarSupportedContent1"
                    aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent1">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    @auth
                        @php $userRole = auth()->user()->role; @endphp

                        {{-- ======================= --}}
                        {{-- Tanks Only Mode (ATG) --}}
                        {{-- ======================= --}}
                        @if($installationMode == 3)
{{--                            <li class="nav-item {{ request()->routeIs('tank.*') ? 'active' : '' }}">--}}
{{--                                <a class="nav-link" href="{{ route('tank.tanks') }}">--}}
{{--                                    <i class="lni lni-drop m-1"></i>Tanks--}}
{{--                                </a>--}}
{{--                            </li>--}}
                        @else

                            {{-- ======================= --}}
                            {{-- Normal Menu --}}
                            {{-- ======================= --}}

                            {{-- Home Link --}}
                            <li class="nav-item {{ request()->is('/') || request()->is('home') ? 'active' : '' }}">
                                <a class="nav-link" href="/">
                                    <i class="lni lni-home m-1"></i>Home
                                </a>
                            </li>

                            {{-- Limit Check --}}
                            @if ($userRole <= 10)
                                <li class="nav-item {{ request()->is('check-customer-limit') ? 'active' : '' }}">
                                    <a class="nav-link" href="/check-customer-limit">
                                        <i class="lni lni-map-marker m-1"></i>Limit Check
                                    </a>
                                </li>
                            @endif

                            {{-- Rate Change --}}
                            @if ($userRole <= 2)
                                <li class="nav-item {{ request()->is('Rates') ? 'active' : '' }}">
                                    <a class="nav-link" href="/Rates">
                                        <i class="lni lni-money-location me-1"></i>Rate Change
                                    </a>
                                </li>
                            @endif

                            {{-- Shift Dashboard --}}
                            @if ($userRole <= 6)
                                <li class="nav-item {{ request()->is('shift') ? 'active' : '' }}">
                                    <a class="nav-link" href="/shift">
                                        <i class="lni lni-grid-alt m-1"></i>Shift Dashboard
                                    </a>
                                </li>

                                {{-- Shift Dropdown --}}
                                <li class="nav-item dropdown {{ request()->is('nozzle') || request()->is('shift_logs') ? 'active' : '' }}">
                                    <a class="nav-link dropdown-toggle" href="#" id="shiftDropdown" role="button"
                                       data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="lni lni-grid-alt m-1"></i>Shift
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="shiftDropdown">
                                        <li>
                                            <a class="dropdown-item" href="/nozzle">
                                                <i class="lni lni-reload m-1"></i>Shift Change
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="/shift_logs">
                                                <i class="lni lni-timer m-1"></i>Shift Logs
                                            </a>
                                        </li>
                                    </ul>
                                </li>

                                {{-- Reports Dropdown --}}
                                <li class="nav-item dropdown {{ request()->is('summary-reports') || request()->is('shift_date_select') ? 'active' : '' }}">
                                    <a class="nav-link dropdown-toggle" href="#" id="reportsDropdown" role="button"
                                       data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="lni lni-files m-1"></i>Reports
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="reportsDropdown">
                                        <li>
                                            <a class="dropdown-item" href="/summary-reports">
                                                <i class="lni lni-stats-up m-1"></i>Manual Shift Reports
                                            </a>
                                        </li>
{{--                                        <li>--}}
{{--                                            <a class="dropdown-item" href="/shift_logs">--}}
{{--                                                <i class="lni lni-timer m-1"></i>Comparison Report--}}
{{--                                            </a>--}}
{{--                                        </li>--}}
                                        <li>
                                            <a class="dropdown-item" href="/shift_date_select">
                                                <i class="lni lni-book m-1"></i>System Shift Report
                                            </a>
                                        </li>
                                    </ul>
                                </li>

                                {{-- Tanks Link --}}
                                <li class="nav-item {{ request()->routeIs('tank.*') ? 'active' : '' }}">
                                    <a class="nav-link" href="{{ route('tank.tanks') }}">
                                        <i class="lni lni-drop m-1"></i>Tanks
                                    </a>
                                </li>
                            @endif

                            {{-- Alerts --}}
{{--                            <li class="nav-item">--}}
{{--                                <button style="border:none; background: none;" class="nav-link" id="alert_btn">--}}
{{--                                    <i class="lni lni-alarm m-1"></i>Alerts--}}
{{--                                </button>--}}
{{--                            </li>--}}
                            @if($sysMode == 2 || $sysMode==3 || $sysMode==1)

                                @php
                                    $alarms = DB::table('tank_alarm_logs')
                                        ->where('status','active')
                                        ->orderByDesc('created_at')
                                        ->get();
                                @endphp

                                <li class="nav-item dropdown">

                                    <!-- 🔔 Button -->
                                    <button class="nav-link dropdown-toggle position-relative border-0 bg-transparent"
                                            id="alertDropdown"
                                            data-bs-toggle="dropdown"
                                            aria-expanded="false">

                                        <span style="font-size:18px;">🔔</span>

                                        <span class="badge bg-danger position-absolute top-0 start-100 translate-middle"
                                              style="font-size:10px;">
            {{ $alarms->count() ?: '' }}
        </span>

                                    </button>

                                    <!-- 📦 Dropdown -->
                                    <div class="dropdown-menu dropdown-menu-end p-3 shadow-lg"
                                         style="width:400px; border-radius:18px; max-height:450px; overflow-y:auto;">

                                        <!-- Header -->
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <h6 class="fw-bold mb-0">
                                                🚨  Tank Alarms
                                            </h6>

                                            <span class="small text-muted">
                {{ $alarms->count() }} Total
            </span>
                                        </div>

                                        <!-- Content -->
                                        @if($alarms->count())

                                            @foreach($alarms as $alarm)

                                                <div class="alarm-modern-card {{ $alarm->alarm_type }} mb-2">

                                                    <div class="d-flex justify-content-between">

                                                        <div>

                                                            <div class="fw-bold text-dark">
                                                                Tank #{{ $alarm->tank_id }}
                                                            </div>

                                                            <div class="small text-muted">
                                                                {{ $alarm->alarm_info }}
                                                            </div>

                                                            <div class="small text-secondary">
                                                                ⏰ {{ $alarm->created_at }}
                                                            </div>

                                                        </div>

                                                        <div class="alarm-status-badge">
                                                            {{ strtoupper($alarm->alarm_type) }}
                                                        </div>

                                                    </div>

                                                </div>

                                            @endforeach

                                        @else

                                            <div class="text-center py-4 text-muted">
                                                ✅ No Active Alarms
                                            </div>

                                        @endif

                                    </div>

                                </li>

                            @endif

                        @endif
                    @endauth

                    {{-- Guest Login --}}
                    @guest
                        @if (Route::has('login'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                        @endif
                    @else
                        {{-- User Dropdown --}}
                        <li class="nav-item dropdown" style="list-style-type: none;">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle show" href="#" role="button"
                               data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="true" >
                                <img src="storage/{{ $settings['logo'] }}" width="30" alt="">
                                {{ Auth::user()->name }}
                            </a>
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                @if ($userRole == 0)
                                    <a class="dropdown-item" href="{{ route('index.settings') }}">
                                        <i class="lni lni-cog m-1"></i>Settings
                                    </a>
                                @php
                                $onlyAtg=DB::table('SysConfig')->first();

                                @endphp
                                @if($onlyAtg->Sys_Mode==3)
                                    <a class="dropdown-item" href="{{url('atg/setup')}}">
                                        <i class="lni lni-drop m-1"></i>Setup
                                    </a>
                                    @endif
                                    <a class="dropdown-item" href="{{ route('user-management.index') }}">
                                        <i class="lni lni-users m-1"></i>User Management
                                    </a>
{{--                                    <a class="dropdown-item" href="{{url('admin/bypass-limit')}}">--}}
{{--                                        <i class="lni lni-users m-1"></i>By Pass Limit--}}
{{--                                    </a>--}}
                                    <div class="dropdown-divider"></div>
                                    @php
                                        $user = Auth::user();
                                    @endphp

                                    @if($user && $user->email === 'superadmin@ez-pump.com')
                                        <a class="dropdown-item" href="{{ route('admin.tank-shift.start') }}">
                                            <i class="lni lni-timer m-1"></i> Tank Shift Start
                                        </a>
                                    @endif
                                @endif
                                <a class="dropdown-item" href="#">
                                    <i class="lni lni-user m-1"></i>Profile
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="lni lni-power-switch m-1"></i>{{ __('Logout') }}
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <script src="{{ asset('/assets/js/alerts.js') }}"></script>
</header>
