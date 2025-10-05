@extends('layouts.app')

@section('title','User Management')

@section('header')
  <div class="d-flex align-items-center justify-content-between">
    <h1 class="h4 m-0">User Management</h1>
  </div>
@endsection

@section('content')
  <div class="d-flex justify-content-between align-items-center mb-3">
    <div></div>
    <a class="btn btn-brand-primary" href="{{ route('admin.users.create') }}">New User</a>
  </div>
  <div class="card shadow-sm">
    <div class="card-header card-header-custom">Users</div>
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-striped mb-0">
          <thead class="table-header-custom">
            <tr>
              <th class="text-white">#</th>
              <th class="text-white">Name</th>
              <th class="text-white">Email</th>
              <th class="text-white">Roles</th>
              <th class="text-white">Created</th>
              <th class="text-white">Actions</th>
            </tr>
          </thead>
          <tbody>
            @forelse($users as $u)
              <tr>
                <td>{{ $u->id }}</td>
                <td>{{ $u->name }}</td>
                <td>{{ $u->email }}</td>
                <td>
                  @php($rs = $rolesMap[$u->id] ?? [])
                  {{ $rs ? implode(', ', $rs) : '—' }}
                </td>
                <td>{{ $u->created_at }}</td>
                <td>
                  <a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.users.edit', $u->id) }}">Edit</a>
                  <form method="POST" action="{{ route('admin.users.destroy', $u->id) }}" class="d-inline" onsubmit="return confirm('Delete this user?');">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-sm btn-outline-danger" type="submit">Delete</button>
                  </form>
                </td>
              </tr>
            @empty
              <tr><td colspan="5" class="text-center p-3">No users found.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
@endsection
