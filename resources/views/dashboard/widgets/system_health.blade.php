@if(isset($data['access_denied']))
    <div class="text-center text-muted py-3">
        <i class="fas fa-lock fa-2x mb-2"></i>
        <p>{{ __('hrms.dashboard.access_restricted') }}</p>
    </div>
@else
    <!-- System Status Indicators -->
    <div class="row text-center mb-3">
        <div class="col-4">
            <div class="{{ $data['database'] ? 'text-success' : 'text-danger' }}">
                <i class="fas fa-database fa-2x mb-1"></i>
                <div class="small">{{ __('hrms.system.database') }}</div>
                <div class="small fw-bold">{{ $data['database'] ? __('hrms.system.online') : __('hrms.system.offline') }}</div>
            </div>
        </div>
        <div class="col-4">
            <div class="{{ $data['cache'] ? 'text-success' : 'text-danger' }}">
                <i class="fas fa-memory fa-2x mb-1"></i>
                <div class="small">{{ __('hrms.system.cache') }}</div>
                <div class="small fw-bold">{{ $data['cache'] ? __('hrms.system.working') : __('hrms.system.failed') }}</div>
            </div>
        </div>
        <div class="col-4">
            <div class="{{ $data['disk_free_percent'] > 20 ? 'text-success' : ($data['disk_free_percent'] > 10 ? 'text-warning' : 'text-danger') }}">
                <i class="fas fa-hdd fa-2x mb-1"></i>
                <div class="small">{{ __('hrms.system.disk_space') }}</div>
                <div class="small fw-bold">{{ number_format($data['disk_free_percent'], 1) }}%</div>
            </div>
        </div>
    </div>

    <!-- Activity Stats -->
    <hr class="my-3">
    <div class="row">
        <div class="col-6">
            <div class="text-center p-2 bg-light rounded">
                <div class="text-primary h5 mb-0">{{ number_format($data['active_users']) }}</div>
                <small class="text-muted">{{ __('hrms.system.active_users') }}</small>
            </div>
        </div>
        <div class="col-6">
            <div class="text-center p-2 bg-light rounded">
                <div class="text-info h5 mb-0">{{ number_format($data['total_activities_today']) }}</div>
                <small class="text-muted">{{ __('hrms.system.activities_today') }}</small>
            </div>
        </div>
    </div>

    <!-- Health Status Summary -->
    <div class="mt-3">
        @php
            $healthScore = 0;
            if($data['database']) $healthScore += 40;
            if($data['cache']) $healthScore += 30;
            if($data['disk_free_percent'] > 20) $healthScore += 30;
        @endphp

        <div class="d-flex justify-content-between align-items-center">
            <small class="text-muted">{{ __('hrms.system.system_health') }}</small>
            <span class="badge bg-{{ $healthScore >= 90 ? 'success' : ($healthScore >= 70 ? 'warning' : 'danger') }}">
                {{ $healthScore }}% {{ __('hrms.system.healthy') }}
            </span>
        </div>

        <div class="progress mt-1" style="height: 4px;">
            <div class="progress-bar bg-{{ $healthScore >= 90 ? 'success' : ($healthScore >= 70 ? 'warning' : 'danger') }}"
                 style="width: {{ $healthScore }}%"></div>
        </div>
    </div>
@endif