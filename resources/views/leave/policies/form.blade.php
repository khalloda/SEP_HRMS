@extends('layouts.app')

@section('title', __('leave.policies.title'))

@section('header')
  <div class="d-flex justify-content-between align-items-center">
    <a href="{{ route('leave.policies.index') }}" class="btn btn-outline-secondary">{{ __('common.back') }}</a>
  </div>
@endsection

@section('content')
  <div class="card">
    <div class="card-body">
      <form action="{{ $action }}" method="POST">
        @csrf
        @if($mode==='edit') @method('PUT') @endif

        <div class="mb-3">
          <label class="form-label">{{ __('leave.policies.code') }}</label>
          <input type="text" name="code" value="{{ old('code', $policy->code ?? '') }}" required class="form-control">
        </div>
        <div class="mb-3">
          <label class="form-label">{{ __('leave.policies.name') }}</label>
          <input type="text" name="name" value="{{ old('name', $policy->name ?? '') }}" required class="form-control">
        </div>
        <div class="mb-3">
          <label class="form-label">{{ __('leave.policies.accrual') }}</label>
          <select name="accrual_rule" class="form-select">
            @foreach(['fixed','monthly','daily'] as $r)
              <option value="{{ $r }}" {{ old('accrual_rule', $policy->accrual_rule ?? 'fixed')===$r?'selected':'' }}>{{ ucfirst($r) }}</option>
            @endforeach
          </select>
        </div>
        <div class="mb-3">
          <label class="form-label">{{ __('leave.policies.days_per_year') }}</label>
          <input type="number" name="days_per_year" value="{{ old('days_per_year', $policy->days_per_year ?? 0) }}" required class="form-control">
        </div>
        <div class="form-check mb-3">
          <input class="form-check-input" type="checkbox" name="carry_over" id="carry_over" {{ old('carry_over', $policy->carry_over ?? false)?'checked':'' }}>
          <label class="form-check-label" for="carry_over">{{ __('leave.policies.allow_carry_over') }}</label>
        </div>
        <div class="mb-3">
          <label class="form-label">{{ __('leave.policies.max_carry_over') }}</label>
          <input type="number" name="max_carry_over" value="{{ old('max_carry_over', $policy->max_carry_over ?? 0) }}" class="form-control">
        </div>

        <button class="btn btn-brand-primary">{{ __('leave.policies.save') }}</button>
      </form>
    </div>
  </div>
@endsection

