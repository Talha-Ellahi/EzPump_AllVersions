@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Summary Reports</h1>
        <a href="{{ route('summary-report-form') }}" class="btn btn-success">
            Create New Report
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-dark text-white">
            <h2 class="mb-0">All Reports</h2>
        </div>
        <div class="card-body">
            @if($reports->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="thead-dark">
                        <tr>
                            <th>ID</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($reports as $report)
                            <tr>
                                <td>{{ $report->id }}</td>
{{--                                <td>{{ $report->created_at->format('Y-m-d H:i') }}</td>--}}
                                <td>{{ $report->shift_start_date }}</td>
                                <td>
                                    <a href="{{ route('summary-reports.show', $report->id) }}"
                                       class="btn btn-primary btn-sm">
                                        View
                                    </a>

{{--                                    <a href="{{ route('summary-report-form.edit', $report->id) }}"--}}
{{--                                       class="btn btn-warning btn-sm">--}}
{{--                                        Edit--}}
{{--                                    </a>--}}
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-muted">No summary reports available</p>
            @endif

        </div>
    </div>
</div>
@endsection
