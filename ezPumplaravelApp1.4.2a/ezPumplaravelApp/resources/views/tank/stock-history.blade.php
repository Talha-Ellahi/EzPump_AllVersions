@extends('layouts.app')

@section('content')
<div id="app">

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
         <stock-history />
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
