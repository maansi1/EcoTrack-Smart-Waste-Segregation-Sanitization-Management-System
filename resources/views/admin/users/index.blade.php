{{-- resources/views/admin/users/index.blade.php --}}
@extends('layouts.app')
@section('title','User Management')
@section('page-title','User Management')
@section('page-sub','Manage all system users and their roles')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <form method="GET" class="d-flex gap-2">
        <input type="text" name="search" value="{{ request('search') }}"
               class="swms-input" style="width:200px" placeholder="Name or email...">
        <select name="role" class="swms-select" style="width:120px">
            <option value="">All roles</option>
            <option value="admin" {{ request('role')=='admin' ? 'selected' : '' }}>Admin</option>
            <option value="staff" {{ request('role')=='staff' ? 'selected' : '' }}>Staff</option>
            <option value="user"  {{ request('role')=='user'  ? 'selected' : '' }}>User</option>
        </select>
        <button type="submit" class="btn-swms-primary"><i class="ti ti-search"></i> Search</button>
    </form>
    <a href="{{ route('admin.users.create') }}" class="btn-swms-primary">
        <i class="ti ti-user-plus"></i> Add user
    </a>
</div>

<div class="swms-card">
    <div class="table-responsive">
        <table class="swms-table">
            <thead>
                <tr>
                    <th>#</th><th>Name</th><th>Email</th><th>Role</th>
                    <th>Area</th><th>Points</th><th>Status</th><th>Joined</th><th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $u)
                <tr>
                    <td class="complaint-id">{{ $u->id }}</td>
                    <td class="fw-500">{{ $u->name }}</td>
                    <td>{{ $u->email }}</td>
                    <td>
                        <span class="role-chip role-{{ $u->role }}">{{ $u->role_label }}</span>
                    </td>
                    <td>{{ $u->area ?? '—' }}</td>
                    <td class="mono">{{ $u->points }}</td>
                    <td>
                        @if($u->is_active)
                            <span class="badge-resolved">Active</span>
                        @else
                            <span class="badge-closed">Inactive</span>
                        @endif
                    </td>
                    <td>{{ $u->created_at->format('d M Y') }}</td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('admin.users.edit', $u) }}" class="btn-swms-secondary" style="padding:4px 10px;font-size:12px">
                                <i class="ti ti-edit"></i>
                            </a>
                            @if($u->id !== auth()->id())
                            <form method="POST" action="{{ route('admin.users.destroy', $u) }}" onsubmit="return confirm('Delete {{ $u->name }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-swms-danger" style="padding:4px 10px;font-size:12px">
                                    <i class="ti ti-trash"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="9" class="text-center py-4" style="color:var(--text-muted)">No users found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="d-flex justify-content-between align-items-center mt-3">
        <div style="font-size:12px;color:var(--text-muted)">Total: {{ $users->total() }} users</div>
        {{ $users->links() }}
    </div>
</div>
@endsection
