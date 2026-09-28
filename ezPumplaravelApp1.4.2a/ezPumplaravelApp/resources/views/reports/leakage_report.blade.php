@extends('layouts.app')
<style>
    .text-muted{
        display: none;
    }
</style>
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

                <h2>Leakage Reports</h2>
                <div class="card border-top border-0 border-4 border-danger">
                    <div class="card-body">



                        <table class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Tank</th>
                                <th>Opening Fuel MM</th>
                                <th>Closing Fuel MM</th>
                                <th>Difference</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Status</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($reports as $report)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $report->tank_name }}</td>
                                    <td>{{ $report->opening_fuel_mm }}</td>
                                    <td>{{ $report->closing_fuel_mm ?? '-' }}</td>
                                    <td>{{ $report->difference_fuel ?? '-' }}</td>
                                    <td>{{ $report->start_date }}</td>
                                    <td>{{ $report->end_date ?? '-' }}</td>
                                    <td>
                                        @if($report->is_button == 1)
                                            <span class="badge bg-success">Running</span>
                                        @else
                                            <span class="badge bg-secondary">Stopped</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                        {{-- Pagination links --}}
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <!-- Left: Showing X to Y of Z -->
                            <div>
                                Showing {{ $reports->firstItem() }} to {{ $reports->lastItem() }} of {{ $reports->total() }} results
                            </div>

                            <!-- Right: Pagination links -->
                            <div>
                                {{ $reports->links() }}
                            </div>
                        </div>
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
