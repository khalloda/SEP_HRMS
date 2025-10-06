<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="utf-8">
    <title>{{ __('reports.payroll_summary.title') }} - {{ now()->format('Y-m-d') }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10px;
            line-height: 1.4;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #c6a44a;
            padding-bottom: 10px;
        }

        .company-name {
            font-size: 16px;
            font-weight: bold;
            color: #2e4029;
            margin-bottom: 5px;
        }

        .report-title {
            font-size: 14px;
            color: #c6a44a;
            margin-bottom: 5px;
        }

        .report-date {
            font-size: 10px;
            color: #666;
        }

        .summary {
            margin: 10px 0;
        }

        .summary-item {
            margin-bottom: 4px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #2e4029;
            color: white;
            font-weight: bold;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 8px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 5px;
        }
    </style>
</head>

<body>
    <div class="header">
        <div class="company-name">{{ __('common.org_full_name') }}</div>
        <div class="report-title">{{ __('reports.payroll_summary.title') }}</div>
        <div class="report-date">{{ __('reports.payroll_summary.period') }}: {{ $month }} | {{ __('reports.payroll_summary.generated_on') }}: {{ now()->format('F j, Y \a\t g:i A') }}</div>
    </div>

    <div class="summary">
        <strong>{{ __('reports.payroll_summary.summary') }}:</strong><br>
        <div class="summary-item">{{ __('reports.payroll_summary.total_employees') }}: {{ $summary['total_employees'] }}</div>
        <div class="summary-item">{{ __('reports.payroll_summary.total_gross') }}: {{ number_format($summary['total_gross'], 2) }}</div>
        <div class="summary-item">{{ __('reports.payroll_summary.total_net') }}: {{ number_format($summary['total_net'], 2) }}</div>
        <div class="summary-item">{{ __('reports.payroll_summary.total_deductions') }}: {{ number_format($summary['total_deductions'], 2) }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 20%;">{{ __('reports.payroll_summary.employee') }}</th>
                <th style="width: 15%;">{{ __('reports.payroll_summary.department') }}</th>
                <th style="width: 15%;">{{ __('reports.payroll_summary.position') }}</th>
                <th style="width: 15%;">{{ __('reports.payroll_summary.gross_salary') }}</th>
                <th style="width: 15%;">{{ __('reports.payroll_summary.total_deductions') }}</th>
                <th style="width: 15%;">{{ __('reports.payroll_summary.net_salary') }}</th>
                <th style="width: 15%;">{{ __('reports.payroll_summary.pay_period') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $payslip)
            <tr>
                <td>{{ $payslip['Employee'] }}</td>
                <td>{{ $payslip['Department'] }}</td>
                <td>{{ $payslip['Position'] }}</td>
                <td class="amount">{{ $payslip['Gross Salary'] }}</td>
                <td class="amount">{{ $payslip['Total Deductions'] }}</td>
                <td class="amount">{{ $payslip['Net Salary'] }}</td>
                <td>{{ $payslip['Pay Period'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        {{ __('reports.payroll_summary.title') }} | {{ __('reports.payroll_summary.period') }}: {{ $month }} | {{ __('reports.payroll_summary.total_employees') }}: {{ count($data) }} |
        {{ __('reports.payroll_summary.generated_by') }} | {{ __('reports.payroll_summary.page') }} {PAGENO} {{ __('reports.payroll_summary.of') }} {nbpg}
    </div>
</body>

</html>