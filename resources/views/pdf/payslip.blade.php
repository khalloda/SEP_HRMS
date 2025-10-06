<!DOCTYPE html>
<html {{ $isRtl ? 'dir=rtl' : 'dir=ltr' }} lang="{{ app()->getLocale() }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('hrms.payslip.title') }} - {{ $payslip->employee_display_name }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 10pt;
            margin: 0;
            padding: 20px;
            color: #333;

                {
                    {
                    $isRtl ? 'direction: rtl;': 'direction: ltr;'
                }
            }
        }

        .header-section {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #c6a44a;
            padding-bottom: 20px;
        }

        .company-logo {
            font-size: 24pt;
            font-weight: bold;
            color: #2e4029;
            margin-bottom: 10px;
        }

        .company-name {
            font-size: 16pt;
            font-weight: bold;
            color: #2e4029;
            margin-bottom: 5px;
        }

        .company-subtitle {
            font-size: 12pt;
            color: #666;
            margin-bottom: 20px;
        }

        .document-title {
            font-size: 18pt;
            font-weight: bold;
            color: #c6a44a;
            margin-bottom: 10px;
        }

        .info-grid {
            display: table;
            width: 100%;
            margin-bottom: 25px;
        }

        .info-row {
            display: table-row;
        }

        .info-cell {
            display: table-cell;
            padding: 8px;
            border-bottom: 1px solid #eee;
        }

        .info-label {
            font-weight: bold;
            color: #2e4029;
            width: 25%;
        }

        .info-value {
            color: #333;
            width: 25%;
        }

        .salary-section {
            margin-bottom: 25px;
        }

        .section-title {
            font-size: 14pt;
            font-weight: bold;
            color: #2e4029;
            background-color: #f9f5e6;
            padding: 10px;
            margin-bottom: 15px;
            border-left: 4px solid #c6a44a;
        }

        .salary-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .salary-table th {
            background-color: #2e4029;
            color: white;
            padding: 12px;

            text-align: {
                    {
                    $isRtl ? 'right': 'left'
                }
            }

            ;
            font-weight: bold;
        }

        .salary-table td {
            padding: 10px 12px;
            border-bottom: 1px solid #ddd;

            text-align: {
                    {
                    $isRtl ? 'right': 'left'
                }
            }

            ;
        }

        .salary-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .amount-cell {
            text-align: {
                    {
                    $isRtl ? 'left': 'right'
                }
            }

            ;
            font-weight: bold;
            color: #2e4029;
        }

        .totals-section {
            background-color: #f9f5e6;
            border: 2px solid #c6a44a;
            border-radius: 5px;
            padding: 20px;
            margin-top: 25px;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px solid #c6a44a;
            margin-bottom: 8px;
        }

        .total-row:last-child {
            border-bottom: none;
            font-size: 14pt;
            font-weight: bold;
            color: #2e4029;
        }

        .total-label {
            font-weight: bold;
            color: #2e4029;
        }

        .total-amount {
            font-weight: bold;
            color: #2e4029;
        }

        .net-pay-highlight {
            background-color: #2e4029;
            color: white;
            padding: 15px;
            border-radius: 5px;
            text-align: center;
            font-size: 16pt;
            font-weight: bold;
            margin-top: 20px;
        }

        .footer-section {
            margin-top: 40px;
            text-align: center;
            font-size: 9pt;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 15px;
        }

        .confidential-notice {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
            padding: 10px;
            border-radius: 3px;
            margin-top: 20px;
            text-align: center;
            font-size: 9pt;
        }

        @media print {
            body {
                margin: 0;
                padding: 15px;
            }
        }
    </style>
</head>

<body>
    <!-- Header Section -->
    <div class="header-section">
        <div class="company-logo">⚖️</div>
        <div class="company-name">{{ __('common.org_full_name') }}</div>
        <div class="company-subtitle">{{ __('common.hrms_full') }}</div>
        <div class="document-title">{{ __('hrms.payslip.title') }}</div>
    </div>

    <!-- Employee Information -->
    <div class="info-grid">
        <div class="info-row">
            <div class="info-cell info-label">{{ __('hrms.employee_code') }}:</div>
            <div class="info-cell info-value">{{ $payslip->employee_code }}</div>
            <div class="info-cell info-label">{{ __('hrms.payslip.pay_period') }}:</div>
            <div class="info-cell info-value">{{ $payslip->pay_period }}</div>
        </div>
        <div class="info-row">
            <div class="info-cell info-label">{{ __('hrms.employee_name') }}:</div>
            <div class="info-cell info-value">{{ $payslip->employee_display_name }}</div>
            <div class="info-cell info-label">{{ __('hrms.payslip.pay_date') }}:</div>
            <div class="info-cell info-value">{{ $payslip->pay_date->format('d/m/Y') }}</div>
        </div>
        <div class="info-row">
            <div class="info-cell info-label">{{ __('hrms.department') }}:</div>
            <div class="info-cell info-value">{{ $payslip->department_name ?? __('common.n_a') }}</div>
            <div class="info-cell info-label">{{ __('hrms.position') }}:</div>
            <div class="info-cell info-value">{{ $payslip->position_name ?? __('common.n_a') }}</div>
        </div>
    </div>

    <!-- Earnings Section -->
    @if($earnings->count() > 0)
    <div class="salary-section">
        <div class="section-title">{{ __('hrms.salary_structure.earnings') }}</div>
        <table class="salary-table">
            <thead>
                <tr>
                    <th>{{ __('hrms.salary_structure.component_name') }}</th>
                    <th>{{ __('hrms.salary_structure.calculation_mode') }}</th>
                    <th>{{ __('hrms.salary_structure.component_value') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($earnings as $earning)
                <tr>
                    <td>{{ $earning->component_display_name }}</td>
                    <td>
                        @switch($earning->calculation_mode)
                        @case('fixed')
                        {{ __('hrms.salary_component.calc_mode.fixed') }}
                        @break
                        @case('formula')
                        {{ __('hrms.salary_component.calc_mode.formula') }}
                        @if($earning->formula_used)
                        <br><small style="color: #666;">({{ $earning->formula_used }})</small>
                        @endif
                        @break
                        @case('variable_net_based')
                        {{ __('hrms.salary_component.calc_mode.variable') }}
                        @break
                        @endswitch
                    </td>
                    <td class="amount-cell">{{ number_format($earning->amount, 2) }} {{ $payslip->currency }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <!-- Deductions Section -->
    @if($deductions->count() > 0)
    <div class="salary-section">
        <div class="section-title">{{ __('hrms.salary_structure.deductions') }}</div>
        <table class="salary-table">
            <thead>
                <tr>
                    <th>{{ __('hrms.salary_structure.component_name') }}</th>
                    <th>{{ __('hrms.salary_structure.calculation_mode') }}</th>
                    <th>{{ __('hrms.salary_structure.component_value') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($deductions as $deduction)
                <tr>
                    <td>{{ $deduction->component_display_name }}</td>
                    <td>
                        @switch($deduction->calculation_mode)
                        @case('fixed')
                        {{ __('hrms.salary_component.calc_mode.fixed') }}
                        @break
                        @case('formula')
                        {{ __('hrms.salary_component.calc_mode.formula') }}
                        @if($deduction->formula_used)
                        <br><small style="color: #666;">({{ $deduction->formula_used }})</small>
                        @endif
                        @break
                        @case('variable_net_based')
                        {{ __('hrms.salary_component.calc_mode.variable') }}
                        @break
                        @endswitch
                    </td>
                    <td class="amount-cell">{{ number_format($deduction->amount, 2) }} {{ $payslip->currency }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <!-- Totals Section -->
    <div class="totals-section">
        <div class="total-row">
            <span class="total-label">{{ __('hrms.salary_structure.total_earnings') }}:</span>
            <span class="total-amount">{{ number_format($payslip->gross_pay, 2) }} {{ $payslip->currency }}</span>
        </div>
        <div class="total-row">
            <span class="total-label">{{ __('hrms.salary_structure.total_deductions') }}:</span>
            <span class="total-amount">{{ number_format($payslip->total_deductions, 2) }} {{ $payslip->currency }}</span>
        </div>
        <div class="total-row">
            <span class="total-label">{{ __('hrms.salary_structure.net_salary') }}:</span>
            <span class="total-amount">{{ number_format($payslip->net_pay, 2) }} {{ $payslip->currency }}</span>
        </div>
    </div>

    <!-- Net Pay Highlight -->
    <div class="net-pay-highlight">
        {{ __('hrms.payslip.net_pay_amount') }}: {{ number_format($payslip->net_pay, 2) }} {{ $payslip->currency }}
    </div>

    <!-- Information Components -->
    @if($infoComponents->count() > 0)
    <div class="salary-section">
        <div class="section-title">{{ __('hrms.salary_structure.info_components') }}</div>
        <table class="salary-table">
            <thead>
                <tr>
                    <th>{{ __('hrms.salary_structure.component_name') }}</th>
                    <th>{{ __('hrms.salary_structure.component_value') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($infoComponents as $info)
                <tr>
                    <td>{{ $info->component_display_name }}</td>
                    <td class="amount-cell">{{ number_format($info->amount, 2) }} {{ $payslip->currency }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <!-- Footer Section -->
    <div class="footer-section">
        <p>{{ __('hrms.payslip.generated_on') }}: {{ $generatedAt->format('d/m/Y H:i') }}</p>
        <p>{{ __('hrms.payslip.system_generated') }}</p>
    </div>

    <!-- Confidential Notice -->
    <div class="confidential-notice">
        <strong>{{ __('hrms.confidential') }}</strong> - {{ __('hrms.payslip.confidential_notice') }}
    </div>
</body>

</html>