@extends('layouts.app')

@section('title', __('letters.templates.label'))

@section('header')
<div class="d-flex justify-content-between align-items-center">
  <h1 class="h4 m-0">{{ $template->name }}</h1>
  <div class="d-flex gap-2">
    <a href="{{ route('letters.templates.index') }}" class="btn btn-outline-secondary">{{ __('common.back') }}</a>
    @can('update', $template)
    <a href="{{ route('letters.templates.edit', $template) }}" class="btn btn-warning">{{ __('common.edit') }}</a>
    @endcan
  </div>
</div>
@endsection

@section('content')
<div class="row g-3">
  <div class="col-12 col-lg-7">
    <div class="card shadow-sm">
      <div class="card-header card-header-custom">{{ __('letters.templates.details_title') }}</div>
      <div class="card-body">
        <dl class="row mb-0">
          <dt class="col-sm-4">{{ __('common.name') }}</dt>
          <dd class="col-sm-8">{{ $template->name }}</dd>
          <dt class="col-sm-4">{{ __('common.type') }}</dt>
          <dd class="col-sm-8">{{ $template->type }}</dd>
          <dt class="col-sm-4">{{ __('common.category') }}</dt>
          <dd class="col-sm-8">{{ $template->category }}</dd>
          <dt class="col-sm-4">{{ __('common.language') }}</dt>
          <dd class="col-sm-8">{{ strtoupper($template->language) }}</dd>
          <dt class="col-sm-4">{{ __('common.subject') }}</dt>
          <dd class="col-sm-8">{{ $template->subject }}</dd>
        </dl>
        <hr>
        <h6 class="fw-semibold">{{ __('common.content') }}</h6>
        <div class="border rounded p-3 bg-white" style="white-space:pre-wrap">{!! nl2br(e($template->content)) !!}</div>
      </div>
    </div>
  </div>
  <div class="col-12 col-lg-5">
    <div class="card shadow-sm mb-3">
      <div class="card-header card-header-custom">{{ __('Available Variables') }}</div>
      <div class="card-body">
        @php($vars = method_exists($template,'getAllAvailableVariables') ? $template->getAllAvailableVariables() : [])
        @if(!empty($vars))
        <ul class="mb-0 small">
          @foreach($vars as $v)
          <li><code>@{{ {{ $v }} }}</code></li>
          @endforeach
        </ul>
        @else
        <p class="text-muted mb-0">{{ __('No variables registered for this template.') }}</p>
        @endif
      </div>
    </div>
    @if(!empty($recentLetters) && $recentLetters->count())
    <div class="card shadow-sm">
      <div class="card-header card-header-custom">{{ __('Recent Letters') }}</div>
      <div class="list-group list-group-flush">
        @foreach($recentLetters as $letter)
        <div class="list-group-item d-flex justify-content-between align-items-center">
          <div>
            <div class="fw-semibold">{{ $letter->employee->full_name ?? __('Employee') }}</div>
            <div class="text-muted small">{{ $letter->created_at->format('Y-m-d H:i') }}</div>
          </div>
          <a class="btn btn-sm btn-outline-secondary" href="{{ route('letters.show', $letter) }}">{{ __('View') }}</a>
        </div>
        @endforeach
      </div>
    </div>
    @endif
  </div>
</div>
@endsection