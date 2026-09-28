@extends('layouts.app')

@section('content')
    <div class="wrapper">
    <div class="page-content">
        <div class="card shadow-lg border-0 rounded-3">
            <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                <h4 class="mb-0">🐞 SQL Errors for {{ $currentDate }}</h4>
                <div>
                    @if($page > 1)
                        <a href="{{ url('/sql-errors?page='.($page-1)) }}" class="btn btn-sm btn-light">&laquo; Previous</a>
                    @endif

                    @if($page < 2)
                        <a href="{{ url('/sql-errors?page='.($page+1)) }}" class="btn btn-sm btn-light">Next &raquo;</a>
                    @endif
                </div>
            </div>
            <div class="card-body p-4">
                @if(count($errors) > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-dark">
                            <tr>
                                <th style="width: 220px;">Date & Time</th>
                                <th>Error Message</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($errors as $error)
                                @php
                                    preg_match('/\[(.*?)\]/', $error, $matches);
                                    $dateTime = $matches[1] ?? 'N/A';
                                    $message = preg_replace('/^\[.*?\]\s*/', '', $error);
                                @endphp
                                <tr>
                                    <td><span class="badge bg-secondary">{{ $dateTime }}</span></td>
                                    <td style="font-family: monospace; color: #dc3545;">
                                        {{ $message }}
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="p-4 text-center">
                        <h5 class="text-success">🎉 No SQL errors found for {{ $currentDate }}</h5>
                    </div>
                @endif
            </div>
        </div>
    </div>
    </div>
@endsection
