<div style="text-align: center; border-bottom: 1px solid #c6a44a; padding-bottom: 10px; {{ $isRtl ? 'direction: rtl;' : 'direction: ltr;' }}">
    <table width="100%" style="border-collapse: collapse;">
        <tr>
            <td width="33%" style="text-align: {{ $isRtl ? 'right' : 'left' }}; vertical-align: middle;">
                <div style="font-size: 20pt; color: #c6a44a;">⚖️</div>
            </td>
            <td width="34%" style="text-align: center; vertical-align: middle;">
                <div style="font-size: 12pt; font-weight: bold; color: #2e4029;">
                    {{ __('Sarie Eldin & Partners Legal Advisors') }}
                </div>
                <div style="font-size: 9pt; color: #666; margin-top: 2px;">
                    {{ __('hrms.payslip.title') }} - {{ $payslip->pay_period }}
                </div>
            </td>
            <td width="33%" style="text-align: {{ $isRtl ? 'left' : 'right' }}; vertical-align: middle; font-size: 8pt; color: #666;">
                {{ $payslip->employee_code }}<br>
                {{ $payslip->employee_display_name }}
            </td>
        </tr>
    </table>
</div>