@if (!isset($excludeHeader) || !$excludeHeader)
    @include('reports.print_headers')
@endif
@if($alarms==null)
    <div class="container mt-5">
        <h2 class="text-center">No Stock Report Data Available</h2>
    </div>
@else
    <div class="container mt-5">
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover text-center">
                <thead class="table-dark">
                <tr>
                    <div class="container mt-5">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover text-center">
                                <thead class="table-dark">
                                <tr>
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
                                        <td>{{ $alarm->tank_name }}</td>
                                        <td>{{ $alarm->product_name }}</td>
                                        <td>{{ $alarm->alarm_type }}</td>
                                        <td>{{ $alarm->alarm_info }}</td>
                                        <td>{{ $alarm->level_mm }}</td>
                                        <td>{{ $alarm->status }}</td>
                                        <td>{{ $alarm->start_time }}</td>
                                        <td>{{ $alarm->end_time ?? '-' }}</td>
                                    </tr>
                                @endforeach


                                </tbody>
                            </table>
                        </div>

@endif
