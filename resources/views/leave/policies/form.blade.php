@extends('layouts.app')
@section('title', $mode==='create'?'New Leave Policy':'Edit Leave Policy')
@section('header')
  <div class="d-flex justify-content-between align-items-center">
    <h1 class="h4 m-0">{{ $mode==='create'?'New Leave Policy':'Edit Leave Policy' }}</h1>
    <a class="btn btn-outline-secondary" href="{{ route('leave.policies.index') }}">Back</a>
  </div>
@endsection
@section('content')
  <div class="card shadow-sm">
    <div class="card-body" style="max-width:680px;">
      <form method="POST" action="{{ $mode==='create'? route('leave.policies.store') : route('leave.policies.update', $policy->id) }}">
        @csrf
        @if($mode==='edit') @method('PUT') @endif
        <div class="mb-3">
          <label class="form-label">Code</label>
          <input class="form-control" name="code" value="{{ old('code', $policy->code ?? '') }}" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Name</label>
          <input class="form-control" name="name" value="{{ old('name', $policy->name ?? '') }}" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Accrual Rule</label>
          <select class="form-select" name="accrual_rule" required>
            @foreach(['fixed','monthly','yearly'] as $r)
              <option value="{{ $r }}" {{ old('accrual_rule', $policy->accrual_rule ?? 'fixed')===$r?'selected':'' }}>{{ ucfirst($r) }}</option>
            @endforeach
          </select>
        </div>
        <div class="mb-3">
          <label class="form-label">Days per Year</label>
          <input class="form-control" type="number" step="0.5" name="days_per_year" value="{{ old('days_per_year', $policy->days_per_year ?? 0) }}" required>
        </div>
        <div class="mb-3 form-check">
          <input type="checkbox" class="form-check-input" id="carry" name="carry_over" value="1" {{ old('carry_over', $policy->carry_over ?? false)?'checked':'' }}>
          <label class="form-check-label" for="carry">Allow carry over</label>
        </div>
        <div class="mb-3">
          <label class="form-label">Max Carry Over</label>
          <input class="form-control" type="number" step="0.5" name="max_carry_over" value="{{ old('max_carry_over', $policy->max_carry_over ?? 0) }}">
        </div>
        <button class="btn btn-brand-primary" type="submit">Save</button>
      </form>
    </div>
  </div>
@endsection

