@extends('layouts.app')

@section('title','Roles')

@section('header')
  <div class="d-flex align-items-center justify-content-between">
    <h1 class="h4 m-0">Roles</h1>
    <a class="btn btn-brand-primary" href="{{ route('admin.roles.create') }}">New Role</a>
  </div>
@endsection

@section('content')
  <div class="card shadow-sm">
    <div class="card-body p-0">
      <table class="table table-striped mb-0">
        <thead class="table-header-custom">
          <tr>
            <th class="text-white">Name</th>
            <th class="text-white">Permissions</th>
            <th class="text-white">Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($roles as $r)
            <tr>
              <td>{{ $r->name }}</td>
              <td>{{ $r->permissions->pluck('name')->implode(', ') ?: '—' }}</td>
              <td>
                <a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.roles.edit', $r) }}">Edit</a>
                <form method="POST" action="{{ route('admin.roles.destroy', $r) }}" class="d-inline" onsubmit="return confirm('Delete this role?');">
                  @csrf
                  @method('DELETE')
                  <button class="btn btn-sm btn-outline-danger" type="submit">Delete</button>
                </form>
              </td>
            </tr>
          @empty
            <tr><td colspan="3" class="text-center p-3">No roles found.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
@endsection

