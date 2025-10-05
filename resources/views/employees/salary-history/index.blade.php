@extends('layouts.app')

@section('title', __('Salary History'))

@section('content')
<div class="card mb-3">
    <div class="card-header card-header-custom">
        <h5 class="mb-0">{{ __('Salary History') }} — {{ $employee->display_name }}</h5>
    </div>
    <div class="card-body">
        <form method="GET" class="row g-2">
            <div class="col-md-3">
                <label class="form-label">{{ __('From') }}</label>
                <input type="date" name="from" value="{{ request('from') }}" class="form-control">
            </div>
            <div class="col-md-3">
                <label class="form-label">{{ __('To') }}</label>
                <input type="date" name="to" value="{{ request('to') }}" class="form-control">
            </div>
            <div class="col-md-3 align-self-end">
                <button class="btn btn-brand-primary">{{ __('Filter') }}</button>
            </div>
            <div class="col-md-3 align-self-end text-end">
                <a class="btn btn-outline-secondary" href="{{ route('employees.salary-history.export', $employee) }}?format=xlsx">Excel</a>
                <a class="btn btn-outline-secondary" href="{{ route('employees.salary-history.export', $employee) }}?format=pdf">PDF</a>
            </div>
        </form>
    </div>
</div>

<div class="alert alert-info">
  @unless($history['can_view_net'])
    <small class="text-muted">{{ __('NET/GROSS hidden based on permissions') }}</small>
  @endunless
</div>

<div class="card">
  <div class="card-body">
    @if(empty($history['items']))
      <div class="text-muted">{{ __('No salary history found for the selected filters.') }}</div>
    @else
      <div class="table-responsive">
        <table class="table table-striped">
          <thead>
            <tr>
              <th>{{ __('Effective From') }}</th>
              <th>{{ __('Effective To') }}</th>
              <th>{{ __('Earnings Total') }}</th>
              <th>{{ __('Deductions Total') }}</th>
              <th>{{ __('GROSS') }}</th>
              <th>{{ __('NET') }}</th>
            </tr>
          </thead>
          <tbody>
            @foreach($history['items'] as $row)
              <tr>
                <td>{{ $row['effective_from'] }}</td>
                <td>{{ $row['effective_to'] ?? '—' }}</td>
                <td>{{ $row['totals']['earnings'] ?? '—' }}</td>
                <td>{{ $row['totals']['deductions'] ?? '—' }}</td>
                <td>{{ $row['totals']['gross'] ?? '—' }}</td>
                <td>{{ $row['totals']['net'] ?? '—' }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    @endif
  </div>
</div>

@endsection