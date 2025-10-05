@if(isset($data['access_denied']))
    <div class="text-center text-muted py-3">
        <i class="fas fa-lock fa-2x mb-2"></i>
        <p>{{ __('Access restricted') }}</p>
    </div>
@else
    <!-- Activity Summary -->
    <div class="row text-center mb-3">
        <div class="col-4">
            <div class="text-primary">
                <div class="h5 mb-0">{{ number_format($data['total_today']) }}</div>
                <small>{{ __('Today') }}</small>
            </div>
        </div>
        <div class="col-4">
            <div class="text-info">
                <div class="h5 mb-0">{{ number_format($data['total_this_week']) }}</div>
                <small>{{ __('This Week') }}</small>
            </div>
        </div>
        <div class="col-4">
            <div class="text-success">
                <div class="h5 mb-0">{{ $data['recent_activities']->count() }}</div>
                <small>{{ __('Recent') }}</small>
            </div>
        </div>
    </div>

    <!-- Recent Activities -->
    @if($data['recent_activities']->count() > 0)
        <hr class="my-3">
        <h6 class="mb-2">{{ __('Latest Activities') }}</h6>
        <div class="list-group list-group-flush">
            @foreach($data['recent_activities']->take(5) as $activity)
            <div class="list-group-item px-0 py-2">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="flex-grow-1">
                        <div class="small fw-bold">{{ Str::limit($activity['description'], 30) }}</div>
                        <small class="text-muted">
                            {{ $activity['causer'] }}
                            @if($activity['subject_type'])
                                • {{ $activity['subject_type'] }}
                            @endif
                        </small>
                    </div>
                    <small class="text-muted">{{ $activity['created_at'] }}</small>
                </div>
            </div>
            @endforeach
        </div>
    @else
        <div class="text-center text-muted py-3">
            <i class="fas fa-history fa-2x mb-2"></i>
            <p>{{ __('No recent activities') }}</p>
        </div>
    @endif

    <!-- Quick Action -->
    <div class="mt-3">
        <a href="{{ route('audit-trail.index') }}" class="btn btn-sm btn-outline-secondary w-100">
            <i class="fas fa-history"></i> {{ __('View Full Audit Trail') }}
        </a>
    </div>
@endif