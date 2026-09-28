@extends('user-management.layout')

@section('title', 'Users List')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-people me-2"></i>Users Management</h2>
    <a href="{{ route('user-management.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle me-1"></i>Add New User
    </a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        @if($users->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr class="{{ $user->blocked_at ? 'table-warning' : '' }}">
                            <td>{{ $user->id }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px; font-size: 14px;">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    {{ $user->name }}
                                </div>
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>
{{--                                <span class="badge bg-{{ $user->role === 'admin' ? 'danger' : ($user->role === 'moderator' ? 'warning' : 'secondary') }}">--}}
                                   @if($user->role==0)
                                        <span class="badge split-bg-success text-white  align-items-center justify-content-center me-2">

                                        {{ ucfirst('super admin') }}
                                    @elseif($user->role==2)
                                                <span class="badge bg-primary text-white  align-items-center justify-content-center me-2">

                                        {{ ucfirst('admin') }}
                                    @else
                                                        <span class="badge btn-gradient-warning  align-items-center justify-content-center me-1">

                                        {{ ucfirst('cashier') }}
                                    @endif
                                </span>
                            </td>
                            <td>
                                @if($user->blocked_at)
                                    <span class="badge bg-warning text-dark text-white">
                                        <i class="bi bi-lock me-1"></i>Blocked
                                    </span>
                                @else
                                    <span class="badge bg-success">
                                        <i class="bi bi-check-circle me-1"></i>Active
                                    </span>
                                @endif
                            </td>
                            <td>{{ $user->created_at->format('M d, Y') }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('user-management.edit', ['user' => $user]) }}"
                                       class="btn btn-sm btn-outline-primary" title="Edit User">
                                        <i class="bi bi-pencil"></i>
                                    </a>

                                    <button type="button" class="btn btn-sm btn-outline-warning"
                                            data-bs-toggle="modal" data-bs-target="#passwordModal{{ $user->id }}" title="Change Password">
                                        <i class="bi bi-key"></i>
                                    </button>

                                    <form method="POST" action="{{ route('user-management.toggle-block', ['user' => $user]) }}" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-outline-{{ $user->blocked_at ? 'success' : 'warning' }}"
                                                title="{{ $user->blocked_at ? 'Unblock' : 'Block' }} User">
                                            <i class="bi bi-{{ $user->blocked_at ? 'unlock' : 'lock' }}"></i>
                                        </button>
                                    </form>

                                    <button type="button" class="btn btn-sm btn-outline-danger"
                                            data-bs-toggle="modal" data-bs-target="#deleteModal{{ $user->id }}" title="Delete User">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <!-- Password Change Modal -->
                        <div class="modal fade" id="passwordModal{{ $user->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form method="POST" action="{{ route('user-management.update-password', ['user' => $user]) }}">
                                        @csrf
                                        @method('PATCH')
                                        <div class="modal-header">
                                            <h5 class="modal-title">Change Password for {{ $user->name }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label for="password{{ $user->id }}" class="form-label">New Password</label>
                                                <input type="password" class="form-control" id="password{{ $user->id }}" name="password" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="password_confirmation{{ $user->id }}" class="form-label">Confirm Password</label>
                                                <input type="password" class="form-control" id="password_confirmation{{ $user->id }}" name="password_confirmation" required>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-primary">Update Password</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Delete Confirmation Modal -->
                        <div class="modal fade" id="deleteModal{{ $user->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Delete User</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p>Are you sure you want to delete <strong>{{ $user->name }}</strong>?</p>
                                        <p class="text-danger"><small>This action cannot be undone.</small></p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                        <form method="POST" action="{{ route('user-management.destroy', ['user' => $user]) }}" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">Delete User</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-4">
                {{ $users->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="bi bi-people display-1 text-muted"></i>
                <h4 class="mt-3">No Users Found</h4>
                <p class="text-muted">Get started by adding your first user.</p>
                <a href="{{ route('user-management.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-1"></i>Add First User
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
