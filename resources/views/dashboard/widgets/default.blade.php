{{-- Default Widget View --}}
<div class="text-center py-4">
    <div class="text-muted mb-2">
        <i class="fas fa-info-circle fa-2x"></i>
    </div>
    @if(isset($data['message']))
        <p class="text-muted mb-0">{{ $data['message'] }}</p>
    @else
        <p class="text-muted mb-0">{{ __('Widget not configured') }}</p>
    @endif
</div>