@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <h2 class="mb-4">⛽ Dispensers Status</h2>

        <table class="table table-bordered table-striped">
            <thead>
            <tr>
                <th>ID</th>
                <th>Status</th>
                <th>Fuel Type</th>
                <th>Nozzles</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($dispensers as $dispenser)
                <tr>
                    <td>{{ $dispenser['id'] }}</td>
                    <td>
                        @if($dispenser['status'] == 'active')
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-danger">Inactive</span>
                        @endif
                    </td>
                    <td>{{ $dispenser['fuel_type'] }}</td>
                    <td>{{ $dispenser['nozzles'] }}</td>
                    <td>
                        <form method="POST" action="{{ route('dispensers.command', [$dispenser['id'], 'start']) }}" style="display:inline">
                            @csrf
                            <button class="btn btn-sm btn-success">Start</button>
                        </form>
                        <form method="POST" action="{{ route('dispensers.command', [$dispenser['id'], 'stop']) }}" style="display:inline">
                            @csrf
                            <button class="btn btn-sm btn-danger">Stop</button>
                        </form>
                        <form method="POST" action="{{ route('dispensers.command', [$dispenser['id'], 'reset']) }}" style="display:inline">
                            @csrf
                            <button class="btn btn-sm btn-warning">Reset</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

    </div>
@endsection
