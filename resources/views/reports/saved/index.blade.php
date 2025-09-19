@extends('layouts.app')

@section('title','Saved Reports')

@section('header')
  <div class="d-flex justify-content-between align-items-center">
    <h1 class="h4 m-0">Saved Reports</h1>
  </div>
@endsection

@section('content')
  <div class="row g-3">
    <div class="col-12 col-lg-5">
      <div class="card shadow-sm">
        <div class="card-header card-header-custom">Save Current Report</div>
        <div class="card-body">
          <form method="POST" action="{{ route('reports.saved.store') }}">
            @csrf
            <div class="mb-2">
              <label class="form-label">Name</label>
              <input class="form-control" type="text" name="name" required>
            </div>
            <div class="mb-2">
              <label class="form-label">Report Key</label>
              <input class="form-control" type="text" name="report_key" placeholder="reports.employee.list" required>
            </div>
            <div class="mb-2">
              <label class="form-label">Params (JSON)</label>
              <textarea class="form-control" name="params" rows="3" placeholder='{"department_id":1}'></textarea>
            </div>
            <div class="mb-2">
              <label class="form-label">Format</label>
              <select class="form-select" name="format">
                <option value="xlsx">XLSX</option>
                <option value="csv">CSV</option>
                <option value="pdf">PDF</option>
              </select>
            </div>
            <div class="mb-2">
              <label class="form-label">Schedule</label>
              <input class="form-control" type="text" name="schedule" placeholder="daily | weekly | cron expr">
            </div>
            <div class="mb-3">
              <label class="form-label">Recipients (comma emails)</label>
              <input class="form-control" type="text" name="recipients" placeholder="hr@example.com,manager@example.com">
            </div>
            <button class="btn btn-brand-primary" type="submit">Save</button>
          </form>
        </div>
      </div>
    </div>
    <div class="col-12 col-lg-7">
      <div class="card shadow-sm">
        <div class="card-header card-header-custom">Your Saved Reports</div>
        <div class="card-body p-0">
          <table class="table table-striped mb-0">
            <thead class="table-header-custom">
              <tr>
                <th class="text-white">Name</th>
                <th class="text-white">Report</th>
                <th class="text-white">Format</th>
                <th class="text-white">Schedule</th>
                <th class="text-white">Actions</th>
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
                    <form method="POST" action="{{ route('reports.saved.destroy', $r) }}" onsubmit="return confirm('Delete this saved report?');">
                      @csrf
                      @method('DELETE')
                      <button class="btn btn-sm btn-outline-danger" type="submit">Delete</button>
                    </form>
                  </td>
                </tr>
              @empty
                <tr><td colspan="5" class="text-center p-3">No saved reports yet.</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
@endsection

