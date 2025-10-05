@extends('layouts.app')

@section('title', $mode==='create' ? 'Create User' : 'Edit User')

@section('header')
  <div class="d-flex align-items-center justify-content-between">
    <h1 class="h4 m-0">{{ $mode==='create' ? 'Create User' : 'Edit User' }}</h1>
    <a class="btn btn-outline-secondary" href="{{ route('admin.users.index') }}">Back</a>
  </div>
@endsection

@section('content')
  <div class="card shadow-sm">
    <div class="card-body">
      <form method="POST" action="{{ $mode==='create' ? route('admin.users.store') : route('admin.users.update', $user) }}" style="max-width:640px;">
        @csrf
        @if($mode==='edit')
          @method('PUT')
        @endif

        <div class="mb-3">
          <label class="form-label">Name</label>
          <input class="form-control" type="text" name="name" value="{{ old('name', $user->name ?? '') }}" required>
          @error('name')<div class="text-danger small">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
          <label class="form-label">Email</label>
          <input class="form-control" type="email" name="email" value="{{ old('email', $user->email ?? '') }}" required>
          @error('email')<div class="text-danger small">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
          <label class="form-label">Password {{ $mode==='edit' ? '(leave blank to keep)' : '' }}</label>
          <input class="form-control" type="password" name="password" {{ $mode==='create' ? 'required' : '' }}>
          @error('password')<div class="text-danger small">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
          <label class="form-label">Confirm Password</label>
          <input class="form-control" type="password" name="password_confirmation" {{ $mode==='create' ? 'required' : '' }}>
        </div>

        @if($allRoles && count($allRoles))
        <div class="mb-3">
          <label class="form-label">Roles</label>
          <div class="d-flex flex-wrap" style="gap:10px;">
            @foreach($allRoles as $role)
              @php($checked = in_array($role, old('roles', $assigned ?? [])))
              <label class="form-check-label"><input type="checkbox" class="form-check-input me-1" name="roles[]" value="{{ $role }}" {{ $checked ? 'checked' : '' }}> {{ $role }}</label>
            @endforeach
          </div>
        </div>
        @endif

        <button class="btn btn-brand-primary" type="submit">Save</button>
      </form>
    </div>
  </div>
@endsection

