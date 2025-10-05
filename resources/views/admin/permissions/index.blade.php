@extends('layouts.app')

@section('title','Permissions')

@section('header')
  <div class="d-flex align-items-center justify-content-between">
    <h1 class="h4 m-0">Permissions</h1>
  </div>
@endsection

@section('content')
  <div class="row g-3">
    <div class="col-12 col-lg-5">
      <div class="card shadow-sm">
        <div class="card-header card-header-custom">Add Permission</div>
        <div class="card-body">
          <form method="POST" action="{{ route('admin.permissions.store') }}">
            @csrf
            <div class="mb-3">
              <label class="form-label">Name (slug)</label>
              <input class="form-control" type="text" name="name" placeholder="e.g., reports.view" required>
              @error('name')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>
            <button class="btn btn-brand-primary" type="submit">Add</button>
          </form>
        </div>
      </div>
    </div>
    <div class="col-12 col-lg-7">
      <div class="card shadow-sm">
        <div class="card-body p-0">
          <table class="table table-striped mb-0">
            <thead class="table-header-custom">
              <tr>
                <th class="text-white">Permission</th>
                <th class="text-white">Actions</th>
              </tr>
            </thead>
            <tbody>
              @forelse($perms as $perm)
                <tr>
                  <td>{{ $perm->name }}</td>
                  <td>
                    <form method="POST" action="{{ route('admin.permissions.destroy', $perm) }}" class="d-inline" onsubmit="return confirm('Delete this permission?');">
                      @csrf
                      @method('DELETE')
                      <button class="btn btn-sm btn-outline-danger" type="submit">Delete</button>
                    </form>
                  </td>
                </tr>
              @empty
                <tr><td colspan="2" class="text-center p-3">No permissions found.</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
@endsection

