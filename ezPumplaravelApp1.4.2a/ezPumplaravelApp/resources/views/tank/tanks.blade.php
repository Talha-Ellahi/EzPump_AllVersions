<!-- resources/views/tank/tanks.blade.php (1-15) -->
{{--@extends('layouts.app')--}}

{{--@section('content')--}}
{{--<div id="app">--}}

{{--<div class="row">--}}
{{--    @include('tank.sidebar')--}}
{{--    <div class="col-lg-9 inner_content">--}}
{{--        <tank-list />--}}
{{--    </div>--}}
{{--</div>--}}

{{--</div>--}}
{{--@endsection--}}
@extends('layouts.app')

@section('content')
    <div id="app">

        @php
            $system = \Illuminate\Support\Facades\DB::table('SysConfig')->first();
            $sysMode = $system?->Sys_Mode;
        @endphp

        <div class="row">

            {{-- ================= SIDEBAR ================= --}}
            <div id="sidebarWrapper"
                 class="{{ $sysMode==3 ? 'd-none  sanat col-lg-2' : 'col-lg-3' }}">

                @include('tank.sidebar')

            </div>


            {{-- ================= MAIN CONTENT ================= --}}
            <div id="mainContent"
                 class="{{ $sysMode==3 ? 'col-lg-12' : 'col-lg-9' }} inner_content">

                {{-- Toggle button only in ATG Mode --}}


                <tank-list />

            </div>

        </div>

    </div>
@endsection


{{-- ================= TOGGLE SCRIPT ================= --}}
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
                    content.classList.add("col-lg-10");
                }

            });

        });
    </script>
@endif
