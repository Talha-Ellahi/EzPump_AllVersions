
@extends('layouts.app')

@section('content')
    <div class="container">
        <h2 class="mb-4">Bypass Limit Management</h2>


        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
@auth
{{--    --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header  text-white" style="background-color: #ededed">
                <h5 class="mb-0">Add New Bypass Limit</h5>
            </div>
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <strong>There were some errors with your input:</strong>
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
<form action="{{ isset($editLimit) ? route('admin.bypass-limits.update', $editLimit->id) : route('admin.bypass.limit.store') }}" method="POST">
    @csrf
    <div class="row mb-3">
        <div class="col">
            <input type="text" name="sys_id" class="form-control" placeholder="System ID" value="{{ $editLimit->sys_id ?? '' }}" required>
        </div>
        <div class="col">
            <input type="month" name="month" class="form-control" value="{{ $editLimit->month ?? '' }}" id="month" required>
        </div>
        <div class="col">
            <input type="number" name="total_limit" class="form-control" placeholder="Total Limit" value="{{ $editLimit->total_limit ?? '' }}" required>
        </div>
        <div class="col">
            <button type="submit" class="btn btn-{{ isset($editLimit) ? 'warning' : 'primary' }}">
                {{ isset($editLimit) ? 'Update' : 'Add' }}
            </button>
        </div>
    </div>
</form>
            </div>
        </div>
        @endauth
         Existing Limits Table
        <div class="card shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
<table class="table table-bordered">
    <thead>
    <tr>
        <th>ID</th>
        <th>SYS ID</th>
        <th>Month</th>
        <th>Total Limit</th>
        <th>Used Limit</th>
        <th>Remaining Limit</th>
        <th>Actions</th>
    </tr>
    </thead>
    <tbody>
    @forelse($limits as $limit)
        <tr>
            <td>{{ $limit->id }}</td>
            <td>{{ $limit->sys_id }}</td>
            <td>{{ $limit->month }}</td>
            <td>{{ $limit->total_limit }}</td>
            <td>{{ collect($limit->records)->sum('used_limit') }}</td>
            <td>{{ collect($limit->records)->last()['remaining_limit'] ?? 0 }}</td>
            <td>
                <a href="{{ route('admin.bypass-limits.edit', $limit->id) }}" class="btn btn-sm btn-warning">Edit</a>
                <form action="{{ route('admin.bypass-limits.destroy', $limit->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-sm btn-danger" onclick="return confirm('Are you sure to delete?')">Delete</button>
                </form>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="7">No records found.</td>
        </tr>
    @endforelse
    </tbody>
</table>
                    <div class="d-flex justify-content-end mt-3 mr-2">
                        {{ $limits->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const monthPicker = document.getElementById('month');
            const today = new Date();
            const year = today.getFullYear();
            const month = (today.getMonth() + 1).toString().padStart(2, '0');
            const minMonth = `${year}-${month}`;

            monthPicker.setAttribute('min', minMonth);
        });
    </script>

@endsection



