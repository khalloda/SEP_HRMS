@if(isset($data['no_profile']))
    <div class="text-center text-muted py-3">
        <i class="fas fa-user-times fa-2x mb-2"></i>
        <p>{{ __('No employee profile linked') }}</p>
        <a href="{{ route('profile.link-employee') }}" class="btn btn-sm btn-primary">
            {{ __('Link Profile') }}
        </a>
    </div>
@else
    <!-- Progress Circle -->
    <div class="text-center mb-3">
        <div class="position-relative d-inline-flex">
            <svg width="80" height="80" class="progress-ring">
                <circle
                    cx="40"
                    cy="40"
                    r="30"
                    stroke="#e9ecef"
                    stroke-width="6"
                    fill="transparent">
                </circle>
                <circle
                    cx="40"
                    cy="40"
                    r="30"
                    stroke="{{ $data['percentage'] >= 80 ? '#28a745' : ($data['percentage'] >= 50 ? '#ffc107' : '#dc3545') }}"
                    stroke-width="6"
                    fill="transparent"
                    stroke-dasharray="188"
                    stroke-dashoffset="{{ 188 - (188 * $data['percentage'] / 100) }}"
                    stroke-linecap="round"
                    transform="rotate(-90 40 40)">
                </circle>
            </svg>
            <div class="position-absolute top-50 start-50 translate-middle">
                <div class="h4 mb-0 {{ $data['percentage'] >= 80 ? 'text-success' : ($data['percentage'] >= 50 ? 'text-warning' : 'text-danger') }}">
                    {{ $data['percentage'] }}%
                </div>
            </div>
        </div>
    </div>

    <!-- Completion Stats -->
    <div class="text-center mb-3">
        <p class="mb-1">
            <strong>{{ $data['completed'] }}</strong> of <strong>{{ $data['total'] }}</strong> fields completed
        </p>
        @if($data['percentage'] < 100)
            <small class="text-muted">Complete your profile to access all features</small>
        @else
            <small class="text-success">
                <i class="fas fa-check-circle"></i> Profile Complete!
            </small>
        @endif
    </div>

    <!-- Missing Fields -->
    @if(count($data['missing_fields']) > 0)
        <hr class="my-3">
        <h6 class="text-warning mb-2">{{ __('Missing Fields') }}</h6>
        <div class="list-group list-group-flush">
            @foreach($data['missing_fields'] as $field)
            <div class="list-group-item px-0 py-1">
                <small class="text-muted">
                    <i class="fas fa-circle text-danger me-1"></i> {{ $field }}
                </small>
            </div>
            @endforeach
        </div>
        <div class="mt-3">
            <a href="{{ route('employee-portal.profile') }}" class="btn btn-sm btn-primary w-100">
                <i class="fas fa-edit"></i> {{ __('Update Profile') }}
            </a>
        </div>
    @endif
@endif