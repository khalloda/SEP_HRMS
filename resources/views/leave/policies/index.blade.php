@extends('layouts.app')
@section('title','Leave Policies')
@section('header')
  <div class="d-flex justify-content-between align-items-center">
    <h1 class="h4 m-0">Leave Policies</h1>
    @can('attendance.manage')
      <a class="btn btn-brand-primary" href="{{ route('leave.policies.create') }}">New Policy</a>
    @endcan
  </div>
@endsection
@section('content')
  <div class="card shadow-sm">
    <div class="table-responsive">
      <table class="table table-striped mb-0">
        <thead class="table-header-custom">
          <tr>
            <th class="text-white">Code</th>
            <th class="text-white">Name</th>
            <th class="text-white">Accrual</th>
            <th class="text-white">Days/Year</th>
            <th class="text-white">Carry Over</th>
            <th class="text-white">Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($policies as $p)
            <tr>
              <td>{{ $p->code }}</td>
              <td>{{ $p->name }}</td>
              <td>{{ ucfirst($p->accrual_rule) }}</td>
              <td>{{ $p->days_per_year }}</td>
              <td>{{ $p->carry_over ? 'Yes' : 'No' }} @if($p->carry_over && $p->max_carry_over) (max {{ $p->max_carry_over }}) @endif</td>
              <td>
                @can('attendance.manage')
                <a class="btn btn-sm btn-outline-secondary" href="{{ route('leave.policies.edit', $p->id) }}">Edit</a>
                <form class="d-inline" method="POST" action="{{ route('leave.policies.destroy', $p->id) }}" onsubmit="return confirm('Delete this policy?');">
                  @csrf @method('DELETE')
                  <button class="btn btn-sm btn-outline-danger" type="submit">Delete</button>
                </form>
                @endcan
                @if(!empty($balances[$p->id]))
                  <span class="badge bg-light text-dark ms-2">You: {{ $balances[$p->id]['closing'] }} days</span>
                @endif
              </td>
            </tr>
          @empty
            <tr><td colspan="6" class="text-center p-3">No policies.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
@endsection
