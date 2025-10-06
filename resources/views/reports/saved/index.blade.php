@extends('layouts.app')

@section('title', __('reports.saved.title'))

@section('header')
<div class="d-flex justify-content-between align-items-center">
  <h1 class="h4 m-0">{{ __('reports.saved.title') }}</h1>
</div>
@endsection

@section('content')
<div class="row g-3">
  <div class="col-12 col-lg-5">
    <div class="card shadow-sm">
      <div class="card-header card-header-custom">{{ __('reports.saved.save') }} {{ __('reports.report') }}</div>
      <div class="card-body">
        <form method="POST" action="{{ route('reports.saved.store') }}">
          @csrf
          <div class="mb-2">
            <label class="form-label">{{ __('reports.saved.name') }}</label>
            <input class="form-control" type="text" name="name" required>
          </div>
          <div class="mb-2">
            <label class="form-label">{{ __('reports.saved.report') }}</label>
            <input class="form-control" type="text" name="report_key" placeholder="reports.employee.list" required>
          </div>
          <div class="mb-2">
            <label class="form-label">{{ __('reports.saved.params_json') }}</label>
            <textarea class="form-control" name="params" rows="3" placeholder='{"department_id":1}'></textarea>
          </div>
          <div class="mb-2">
            <label class="form-label">{{ __('reports.saved.format') }}</label>
            <select class="form-select" name="format">
              <option value="xlsx">{{ __('reports.saved.xlsx') }}</option>
              <option value="csv">{{ __('reports.saved.csv') }}</option>
              <option value="pdf">{{ __('reports.saved.pdf') }}</option>
            </select>
          </div>
          <div class="mb-2">
            <label class="form-label">{{ __('reports.saved.schedule') }}</label>
            <input class="form-control" type="text" name="schedule" placeholder="daily | weekly | cron expr">
          </div>
          <div class="mb-3">
            <label class="form-label">{{ __('reports.saved.recipients') }}</label>
            <input class="form-control" type="text" name="recipients" placeholder="hr@example.com,manager@example.com">
          </div>
          <button class="btn btn-brand-primary" type="submit">{{ __('reports.saved.save') }}</button>
        </form>
      </div>
    </div>
  </div>
  <div class="col-12 col-lg-7">
    <div class="card shadow-sm">
      <div class="card-header card-header-custom">{{ __('reports.saved.title') }}</div>
      <div class="card-body p-0">
        <table class="table table-striped mb-0">
          <thead class="table-header-custom">
            <tr>
              <th class="text-white">{{ __('reports.saved.name') }}</th>
              <th class="text-white">{{ __('reports.saved.report') }}</th>
              <th class="text-white">{{ __('reports.saved.format') }}</th>
              <th class="text-white">{{ __('reports.saved.schedule') }}</th>
              <th class="text-white">{{ __('reports.saved.actions') }}</th>
            </tr>
          </thead>
          <tbody>
            @forelse($reports as $r)
            <tr>
              <td>{{ $r->name }}</td>
              <td><code>{{ $r->report_key }}</code></td>
              <td>{{ strtoupper($r->format) }}</td>
              <td>{{ $r->schedule ?: '—' }}</td>
              <td>
                <form method="POST" action="{{ route('reports.saved.destroy', $r) }}" onsubmit="return confirm('{{ __('reports.saved.delete') }}');">
                  @csrf
                  @method('DELETE')
                  <button class="btn btn-sm btn-outline-danger" type="submit">{{ __('reports.saved.delete') }}</button>
                </form>
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="5" class="text-center p-3">{{ __('reports.saved.empty') }}</td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection