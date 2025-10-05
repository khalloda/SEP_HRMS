@extends('layouts.app')

@section('title', $mode==='create' ? 'Create Role' : 'Edit Role')

@section('header')
  <div class="d-flex align-items-center justify-content-between">
    <h1 class="h4 m-0">{{ $mode==='create' ? 'Create Role' : 'Edit Role' }}</h1>
    <a class="btn btn-outline-secondary" href="{{ route('admin.roles.index') }}">Back</a>
  </div>
@endsection

@section('content')
  <div class="card shadow-sm">
    <div class="card-body">
      <form method="POST" action="{{ $mode==='create' ? route('admin.roles.store') : route('admin.roles.update', $role) }}" style="max-width:720px;">
        @csrf
        @if($mode==='edit')
          @method('PUT')
        @endif

        <div class="mb-3">
          <label class="form-label">Name</label>
          <input class="form-control" type="text" name="name" value="{{ old('name', $role->name ?? '') }}" required>
          @error('name')<div class="text-danger small">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
          <label class="form-label">Permissions</label>
          <div class="row">
            @php($assigned = old('permissions', $assigned ?? []))
            @foreach($perms as $p)
              <div class="col-12 col-md-6 col-lg-4">
                <label class="form-check-label">
                  <input type="checkbox" class="form-check-input me-1" name="permissions[]" value="{{ $p->name }}" {{ in_array($p->name, $assigned) ? 'checked' : '' }}>
                  {{ $p->name }}
                </label>
              </div>
            @endforeach
          </div>
        </div>

        <button class="btn btn-brand-primary" type="submit">Save</button>
      </form>
    </div>
  </div>
@endsection

