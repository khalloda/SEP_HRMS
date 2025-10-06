@extends('layouts.app')

@section('title', __('leave.policies.title'))

@section('header')
  <div class="d-flex justify-content-between align-items-center">
    <h1 class="h3 m-0 brand-dark-green">{{ __('leave.policies.title') }}</h1>
    <a href="{{ route('leave.policies.create') }}" class="btn btn-brand-primary">{{ __('leave.policies.new') }}</a>
  </div>
@endsection

@section('content')
  <div class="card">
    <div class="card-body">
      <table class="table table-striped">
        <thead>
          <tr>
            <th>{{ __('leave.policies.code') }}</th>
            <th>{{ __('leave.policies.name') }}</th>
            <th>{{ __('leave.policies.accrual') }}</th>
            <th>{{ __('leave.policies.days_per_year') }}</th>
            <th>{{ __('leave.policies.carry_over') }}</th>
            <th>{{ __('leave.policies.actions') }}</th>
          </tr>
        </thead>
        <tbody>
          @forelse($policies as $p)
            <tr>
              <td>{{ $p->code }}</td>
              <td>{{ $p->name }}</td>
              <td>{{ ucfirst($p->accrual_rule) }}</td>
              <td>{{ $p->days_per_year }}</td>
              <td>{{ $p->carry_over ? __('common.yes') : __('common.no') }}</td>
              <td>
                <a href="{{ route('leave.policies.edit', $p->id) }}" class="btn btn-sm btn-outline-primary">{{ __('leave.policies.edit') }}</a>
                <form action="{{ route('leave.policies.destroy', $p->id) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('leave.policies.confirm_delete') }}');">
                  @csrf @method('DELETE')
                  <button class="btn btn-sm btn-outline-danger">{{ __('leave.policies.delete') }}</button>
                </form>
              </td>
            </tr>
          @empty
            <tr><td colspan="6" class="text-center text-muted">{{ __('leave.policies.none') }}</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
@endsection
