<div style="text-align: center; border-top: 1px solid #ddd; padding-top: 8px; font-size: 8pt; color: #666; {{ $isRtl ? 'direction: rtl;' : 'direction: ltr;' }}">
    <table width="100%" style="border-collapse: collapse;">
        <tr>
            <td width="33%" style="text-align: {{ $isRtl ? 'right' : 'left' }};">
                {{ __('hrms.payslip.generated_on') }}: {{ $generatedAt->format('d/m/Y H:i') }}
            </td>
            <td width="34%" style="text-align: center;">
                {{ __('hrms.confidential') }} - {{ __('hrms.payslip.system_generated') }}
            </td>
            <td width="33%" style="text-align: {{ $isRtl ? 'left' : 'right' }};">
                {{ __('hrms.page') }} {PAGENO} {{ __('hrms.of') }} {nbpg}
            </td>
        </tr>
    </table>
</div>