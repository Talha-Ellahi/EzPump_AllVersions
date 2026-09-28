@extends('user-management.layout')

@section('title', 'Edit User')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0"><i class="bi bi-pencil me-2"></i>Edit User: {{ $user->name }}</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('user-management.update', ['user' => $user]) }}">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Full Name</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                       id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                       id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="role" class="form-label">User Role</label>
                        <select class="form-select @error('role') is-invalid @enderror" id="role" name="role" required>
                            <option value="">Select Role</option>
                            <option value="10" {{ (string) old('role', $user->role) === "10" ? 'selected' : '' }}>Cashier</option>
                            <option value="2"  {{ (string) old('role', $user->role) === "2" ? 'selected' : '' }}>Admin</option>

                            @if(auth()->user()->role == 0)
                                <option value="0" {{ (string) old('role', $user->role) === "0" ? 'selected' : '' }}>Super Admin</option>
                            @endif
                        </select>
                        @error('role')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        <strong>Note:</strong> To change the password, use the password change option from the users list.
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('user-management.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-1"></i>Back to Users
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-1"></i>Update User
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
