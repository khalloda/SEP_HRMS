@extends('layouts.app')
@section('title','Leave Requests')
@section('header')
  <div class="d-flex justify-content-between align-items-center">
    <h1 class="h4 m-0">Leave Requests</h1>
  </div>
@endsection
@section('content')
  <div class="row g-3">
    <div class="col-12 col-lg-5">
      <div class="card shadow-sm">
        <div class="card-header card-header-custom">New Request</div>
        <div class="card-body">
          <form method="POST" action="{{ route('leave.requests.store') }}">
            @csrf
            <div class="mb-2">
              <label class="form-label">Policy</label>
              <select class="form-select" name="policy_id" required>
                @foreach($policies as $p)
                <option value="{{ $p->id }}">{{ $p->name }} ({{ $p->code }})</option>
                @endforeach
              </select>
            </div>
            <div class="mb-2">
              <label class="form-label">From</label>
              <input class="form-control" type="date" name="from_date" required>
            </div>
            <div class="mb-2">
              <label class="form-label">To</label>
              <input class="form-control" type="date" name="to_date" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Reason</label>
              <textarea class="form-control" name="reason" rows="2"></textarea>
            </div>
            <button class="btn btn-brand-primary" type="submit">Submit</button>
          </form>
        </div>
      </div>
      @if(!empty($balances))
      <div class="card shadow-sm mt-3">
        <div class="card-header card-header-custom">Your Balances (YTD)</div>
        <div class="table-responsive">
          <table class="table table-striped mb-0">
            <thead class="table-header-custom"><tr>
              <th class="text-white">Policy</th>
              <th class="text-white">Accrued</th>
              <th class="text-white">Taken</th>
              <th class="text-white">Closing</th>
            </tr></thead>
            <tbody>
              @foreach($balances as $b)
              <tr>
                <td>{{ $b['policy'] }}</td>
                <td>{{ $b['accrued'] }}</td>
                <td>{{ $b['taken'] }}</td>
                <td><strong>{{ $b['closing'] }}</strong></td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
      @endif
      <div class="card shadow-sm mt-3">
        <div class="card-header card-header-custom">Calendar</div>
        <div class="card-body">
          <div id="leave-cal"></div>
        </div>
      </div>
    </div>
    <div class="col-12 col-lg-7">
      <div class="card shadow-sm">
        <div class="card-header card-header-custom">Recent Requests</div>
        <div class="table-responsive">
          <table class="table table-striped mb-0">
            <thead class="table-header-custom">
              <tr>
                <th class="text-white">Employee</th>
                <th class="text-white">Policy</th>
                <th class="text-white">From</th>
                <th class="text-white">To</th>
                <th class="text-white">Days</th>
                <th class="text-white">Status</th>
                <th class="text-white">Actions</th>
              </tr>
            </thead>
            <tbody>
              @forelse($requests as $r)
                <tr>
                  <td>{{ $r->user_name }}</td>
                  <td>{{ $r->policy_name }}</td>
                  <td>{{ $r->from_date }}</td>
                  <td>{{ $r->to_date }}</td>
                  <td>{{ $r->days }}</td>
                  <td><span class="badge {{ $r->status==='approved'?'bg-success':($r->status==='rejected'?'bg-secondary':'bg-warning') }}">{{ ucfirst($r->status) }}</span></td>
                  <td>
                    @can('attendance.manage')
                      @if($r->status==='pending')
                        <form class="d-inline" method="POST" action="{{ route('leave.requests.approve',$r->id) }}">@csrf<button class="btn btn-sm btn-outline-success" type="submit">Approve</button></form>
                        <form class="d-inline" method="POST" action="{{ route('leave.requests.reject',$r->id) }}">@csrf<button class="btn btn-sm btn-outline-danger" type="submit">Reject</button></form>
                      @endif
                    @endcan
                  </td>
                </tr>
              @empty
                <tr><td colspan="7" class="text-center p-3">No requests.</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  @push('scripts')
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css' rel='stylesheet' />
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js'></script>
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('leave-cal');
        if (!calendarEl) return;
        var calendar = new FullCalendar.Calendar(calendarEl, {
          initialView: 'dayGridMonth',
          height: 450,
          events: '{{ route('leave.events') }}'
        });
        calendar.render();
      });
    </script>
  @endpush
@endsection
