<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ __('reports.employee_directory.title') }} - {{ now()->format('Y-m-d') }}</title>
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
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
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
        .status-active {
            color: #28a745;
            font-weight: bold;
        }
        .status-inactive {
            color: #ffc107;
            font-weight: bold;
        }
        .status-terminated {
            color: #dc3545;
            font-weight: bold;
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
        <div class="report-title">{{ __('reports.employee_directory.title') }}</div>
        <div class="report-date">{{ __('reports.employee_directory.generated_on') }}: {{ now()->format('F j, Y \a\t g:i A') }}</div>
        @if($filters)
            <div class="report-date">
                @if($filters['department_id'] ?? null)
                    {{ __('reports.employee_directory.filters.department') }}: {{ \App\Models\Department::find($filters['department_id'])->name_en ?? 'Unknown' }} |
                @endif
                @if($filters['position_id'] ?? null)
                    {{ __('reports.employee_directory.filters.position') }}: {{ \App\Models\Position::find($filters['position_id'])->name_en ?? 'Unknown' }} |
                @endif
                @if($filters['employment_status'] ?? null)
                    {{ __('reports.employee_directory.filters.status') }}: {{ ucfirst($filters['employment_status']) }}
                @endif
            </div>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 15%;">{{ __('reports.employee_directory.headers.employee_code') }}</th>
                <th style="width: 20%;">{{ __('reports.employee_directory.headers.full_name') }}</th>
                <th style="width: 15%;">{{ __('reports.employee_directory.headers.department') }}</th>
                <th style="width: 15%;">{{ __('reports.employee_directory.headers.position') }}</th>
                <th style="width: 10%;">{{ __('reports.employee_directory.headers.employment_status') }}</th>
                <th style="width: 10%;">{{ __('reports.employee_directory.headers.hire_date') }}</th>
                <th style="width: 15%;">{{ __('reports.employee_directory.headers.manager') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $employee)
            <tr>
                <td>{{ $employee['Employee Code'] }}</td>
                <td>{{ $employee['Full Name'] }}</td>
                <td>{{ $employee['Department'] }}</td>
                <td>{{ $employee['Position'] }}</td>
                <td class="status-{{ strtolower($employee['Employment Status']) }}">
                    {{ $employee['Employment Status'] }}
                </td>
                <td>{{ $employee['Hire Date'] }}</td>
                <td>{{ $employee['Manager'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        {{ __('reports.employee_directory.title') }} | {{ __('reports.employee_directory.footer_total') }}: {{ count($data) }} |
        {{ __('reports.employee_directory.generated_by') }} | {{ __('hrms.page') }} {PAGENO} {{ __('hrms.of') }} {nbpg}
    </div>
</body>
</html>