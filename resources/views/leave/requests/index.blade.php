@extends('layouts.app')

@section('title', __('leave.requests.title'))

@section('header')
  <div class="d-flex justify-content-between align-items-center">
    <h1 class="h3 m-0 brand-dark-green">{{ __('leave.requests.title') }}</h1>
    <a href="{{ route('leave.requests.create') }}" class="btn btn-brand-primary">{{ __('leave.requests.new') }}</a>
  </div>
@endsection

@section('content')
  <div class="row g-3">
    <div class="col-lg-8">
      <div class="card">
        <div class="card-body">
          <form method="GET">
            <div class="row g-2 align-items-end">
              <div class="col-md-3">
                <label class="form-label">{{ __('leave.requests.policy') }}</label>
                <select name="policy_id" class="form-select">
                  <option value="">—</option>
                  @foreach($policies as $p)
                    <option value="{{ $p->id }}" {{ request('policy_id')==$p->id?'selected':'' }}>{{ $p->name }} ({{ $p->code }})</option>
                  @endforeach
                </select>
              </div>
              <div class="col-md-3">
                <label class="form-label">{{ __('leave.requests.from') }}</label>
                <input type="date" name="from" value="{{ request('from') }}" class="form-control">
              </div>
              <div class="col-md-3">
                <label class="form-label">{{ __('leave.requests.to') }}</label>
                <input type="date" name="to" value="{{ request('to') }}" class="form-control">
              </div>
              <div class="col-md-3">
                <button class="btn btn-brand-primary w-100">{{ __('common.filter') }}</button>
              </div>
            </div>
          </form>
        </div>
      </div>

      <div class="card">
        <div class="card-body">
          <h5 class="mb-3">{{ __('leave.requests.recent_requests') }}</h5>
          <table class="table table-striped">
            <thead>
              <tr>
                <th>{{ __('leave.requests.policy') }}</th>
                <th>{{ __('leave.requests.from') }}</th>
                <th>{{ __('leave.requests.to') }}</th>
                <th>{{ __('leave.requests.days') }}</th>
                <th>{{ __('leave.requests.status') }}</th>
                <th>{{ __('leave.requests.actions') }}</th>
              </tr>
            </thead>
            <tbody>
              @forelse($requests as $r)
                <tr>
                  <td>{{ $r->policy->name }}</td>
                  <td>{{ $r->from_date }}</td>
                  <td>{{ $r->to_date }}</td>
                  <td>{{ $r->days }}</td>
                  <td>
                    <span class="badge {{ $r->status==='approved'?'bg-success':($r->status==='rejected'?'bg-secondary':'bg-warning') }}">{{ ucfirst($r->status) }}</span>
                  </td>
                  <td>
                    <form action="{{ route('leave.requests.approve', $r->id) }}" method="POST" class="d-inline">@csrf
                      <button class="btn btn-sm btn-outline-success">{{ __('leave.requests.approve') }}</button>
                    </form>
                    <form action="{{ route('leave.requests.reject', $r->id) }}" method="POST" class="d-inline">@csrf
                      <button class="btn btn-sm btn-outline-danger">{{ __('leave.requests.reject') }}</button>
                    </form>
                  </td>
                </tr>
              @empty
                <tr><td colspan="6" class="text-center text-muted">{{ __('leave.requests.none') }}</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <div class="col-lg-4">
      <div class="card">
        <div class="card-body">
          <h6 class="mb-3">{{ __('leave.requests.balances_ytd') }}</h6>
          @foreach($policies as $p)
            <div class="d-flex justify-content-between small">
              <span>{{ $p->name }}</span>
              <span>{{ $balances[$p->id]['closing'] ?? 0 }} {{ __('leave.requests.days') }}</span>
            </div>
          @endforeach
        </div>
      </div>

      <div class="card mt-3">
        <div class="card-body">
          <h6 class="mb-3">{{ __('leave.requests.calendar') }}</h6>
          <div id="leave-cal"></div>
        </div>
      </div>
    </div>
  </div>
@endsection
