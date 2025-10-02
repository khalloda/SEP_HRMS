@php($showAmounts = $showAmounts ?? true)
<div class="table-responsive">
    <table class="table table-sm table-striped align-middle mb-0">
        <thead class="table-light">
            <tr>
                <th>{{ __('Component') }}</th>
                <th>{{ __('Details') }}</th>
                <th class="text-end">{{ __('Amount') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse($lines as $line)
                <tr>
                    <td class="fw-semibold">{{ $line->component_display_name ?: $line->component_name }}</td>
                    <td class="text-muted small">
                        @if($line->formula_used)
                            {{ __('Formula:') }} {{ $line->formula_used }}
                        @elseif($line->calculation_notes)
                            {{ $line->calculation_notes }}
                        @else
                            {{ __('Manual entry') }}
                        @endif
                    </td>
                    <td class="text-end">
                        @if($showAmounts)
                            {{ number_format($line->amount, 2) }} {{ $currency ?? '' }}
                        @else
                            <span class="text-muted">{{ __('Restricted') }}</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="text-center text-muted py-3">
                        {{ $emptyMessage ?? __('No components available.') }}
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
