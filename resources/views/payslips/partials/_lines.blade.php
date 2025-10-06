@php($showAmounts = $showAmounts ?? true)
<div class="table-responsive">
    <table class="table table-sm table-striped align-middle mb-0">
        <thead class="table-light">
            <tr>
                <th>{{ __('payroll.payslip.headers.component') }}</th>
                <th>{{ __('payroll.payslip.headers.details') }}</th>
                <th class="text-end">{{ __('payroll.payslip.headers.amount') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse($lines as $line)
            <tr>
                <td class="fw-semibold">{{ $line->component_display_name ?: $line->component_name }}</td>
                <td class="text-muted small">
                    @if($line->formula_used)
                    {{ __('payroll.payslip.formula_label') }} {{ $line->formula_used }}
                    @elseif($line->calculation_notes)
                    {{ $line->calculation_notes }}
                    @else
                    {{ __('payroll.payslip.manual_entry') }}
                    @endif
                </td>
                <td class="text-end">
                    @if($showAmounts)
                    {{ number_format($line->amount, 2) }} {{ $currency ?? '' }}
                    @else
                    <span class="text-muted">{{ __('payroll.payslip.restricted') }}</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="3" class="text-center text-muted py-3">
                    {{ $emptyMessage ?? __('payroll.payslip.empty') }}
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>