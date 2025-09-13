@if(isset($data['access_denied']))
    <div class="text-center text-muted py-3">
        <i class="fas fa-lock fa-2x mb-2"></i>
        <p>{{ __('Access restricted') }}</p>
    </div>
@else
    <div class="row text-center">
        <div class="col-6 mb-3">
            <div class="border-end">
                <div class="widget-stat text-primary">{{ number_format($data['total']) }}</div>
                <small class="text-muted">{{ __('Total Employees') }}</small>
            </div>
        </div>
        <div class="col-6 mb-3">
            <div class="widget-stat text-success">{{ number_format($data['active']) }}</div>
            <small class="text-muted">{{ __('Active') }}</small>
        </div>
    </div>

    <div class="row text-center">
        <div class="col-6">
            <div class="border-end">
                <div class="widget-stat text-info">{{ number_format($data['new_this_month']) }}</div>
                <small class="text-muted">{{ __('New This Month') }}</small>
            </div>
        </div>
        <div class="col-6">
            <div class="widget-stat text-warning">{{ number_format($data['on_leave']) }}</div>
            <small class="text-muted">{{ __('On Leave') }}</small>
        </div>
    </div>

    @if(count($data['departments']) > 0)
        <hr class="my-3">
        <h6 class="mb-2">{{ __('By Department') }}</h6>
        <div class="row">
            @foreach($data['departments']->take(4) as $dept => $count)
            <div class="col-6 mb-2">
                <div class="d-flex justify-content-between">
                    <small>{{ Str::limit($dept, 15) }}</small>
                    <span class="badge bg-secondary">{{ $count }}</span>
                </div>
            </div>
            @endforeach
        </div>
    @endif
@endif