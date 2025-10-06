@extends('layouts.app')
@section('title', __('letters.templates.create_title'))
@section('header')
<div class="d-flex justify-content-between align-items-center">
  <h1 class="h4 m-0">{{ __('letters.templates.create_template') }}</h1>
  <a href="{{ route('letters.templates.index') }}" class="btn btn-outline-secondary">{{ __('common.back') }}</a>
</div>
@endsection
@section('content')
<div class="card shadow-sm">
  <div class="card-body" style="max-width:820px;">
    <form method="POST" action="{{ route('letters.templates.store') }}">
      @csrf
      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label">{{ __('common.name') }}</label>
          <input class="form-control" name="name" value="{{ old('name') }}" required>
        </div>
        <div class="col-md-3">
          <label class="form-label">{{ __('common.type') }}</label>
          <input class="form-control" name="type" list="types" value="{{ old('type') }}" required>
          <datalist id="types">
            @foreach(($types ?? []) as $t)<option value="{{ $t }}">@endforeach
          </datalist>
        </div>
        <div class="col-md-3">
          <label class="form-label">{{ __('common.language') }}</label>
          <select class="form-select" name="language" required>
            <option value="en">{{ __('common.language_name.en') }}</option>
            <option value="ar">{{ __('common.language_name.ar') }}</option>
          </select>
        </div>
        <div class="col-md-6">
          <label class="form-label">{{ __('common.category') }}</label>
          <input class="form-control" name="category" list="cats" value="{{ old('category') }}" required>
          <datalist id="cats">
            @foreach(($categories ?? []) as $c)<option value="{{ $c }}">@endforeach
          </datalist>
        </div>
        <div class="col-md-6 d-flex align-items-end">
          <div class="form-check">
            <input class="form-check-input" type="checkbox" name="is_active" value="1" id="active">
            <label class="form-check-label" for="active">{{ __('hrms.active') }}</label>
          </div>
        </div>
        <div class="col-12">
          <label class="form-label">{{ __('common.subject') }}</label>
          <input class="form-control" name="subject" value="{{ old('subject') }}" required>
        </div>
        <div class="col-12">
          <label class="form-label">{{ __('common.content') }}</label>
          <textarea class="form-control" name="content" rows="10" required>{{ old('content') }}</textarea>
          <div class="form-text">{{ __('letters.templates.use_variables_hint') }} @{{ employee_name }}, @{{ employee_code }}.</div>
        </div>
      </div>
      <div class="mt-3">
        <button class="btn btn-brand-primary" type="submit">{{ __('common.save') }}</button>
      </div>
    </form>
  </div>
</div>
@endsection