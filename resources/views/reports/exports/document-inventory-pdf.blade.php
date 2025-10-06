<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ __('reports.document_inventory.title') }} - {{ now()->format('Y-m-d') }}</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 10px; line-height: 1.4; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #c6a44a; padding-bottom: 10px; }
        .company-name { font-size: 16px; font-weight: bold; color: #2e4029; margin-bottom: 5px; }
        .report-title { font-size: 14px; color: #c6a44a; margin-bottom: 5px; }
        .report-date { font-size: 10px; color: #666; }
        .summary { margin: 10px 0; }
        .summary-item { margin-bottom: 4px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #2e4029; color: white; font-weight: bold; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        .footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 8px; color: #666; border-top: 1px solid #ddd; padding-top: 5px; }
    </style>
</head>
<body>
    <div class="header">
        <div class="company-name">{{ __('common.org_full_name') }}</div>
        <div class="report-title">{{ __('reports.document_inventory.title') }}</div>
        <div class="report-date">{{ __('reports.document_inventory.generated_on') }}: {{ now()->format('F j, Y \a\t g:i A') }}</div>
    </div>

    <div class="summary">
        <strong>{{ __('reports.document_inventory.summary') }}:</strong><br>
        <div class="summary-item">{{ __('reports.document_inventory.total_documents') }}: {{ $summary['total'] }}</div>
        <div class="summary-item">{{ __('reports.document_inventory.expiring_soon') }}: {{ $summary['expiring_soon'] }}</div>
        <div class="summary-item">{{ __('reports.document_inventory.expired') }}: {{ $summary['expired'] }}</div>
        @foreach($summary['by_type'] as $type => $count)
            <div class="summary-item">{{ __(ucfirst(str_replace('_', ' ', $type))) }}: {{ $count }}</div>
        @endforeach
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 15%;">{{ __('reports.document_inventory.headers.employee') }}</th>
                <th style="width: 12%;">{{ __('reports.document_inventory.headers.department') }}</th>
                <th style="width: 18%;">{{ __('reports.document_inventory.headers.document_name') }}</th>
                <th style="width: 12%;">{{ __('reports.document_inventory.headers.type') }}</th>
                <th style="width: 10%;">{{ __('reports.document_inventory.headers.upload_date') }}</th>
                <th style="width: 10%;">{{ __('reports.document_inventory.headers.expiry_date') }}</th>
                <th style="width: 10%;">{{ __('reports.document_inventory.headers.status') }}</th>
                <th style="width: 13%;">{{ __('reports.document_inventory.headers.tags') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $document)
            <tr>
                <td>{{ $document['Employee'] }}</td>
                <td>{{ $document['Department'] }}</td>
                <td>{{ $document['Document Name'] }}</td>
                <td>{{ $document['Type'] }}</td>
                <td>{{ $document['Upload Date'] }}</td>
                <td>{{ $document['Expiry Date'] }}</td>
                <td class="status-{{ strtolower(str_replace(' ', '-', $document['Status'])) }}">
                    {{ $document['Status'] }}
                </td>
                <td>{{ $document['Tags'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        {{ __('reports.document_inventory.title') }} | {{ __('reports.document_inventory.footer_total') }}: {{ count($data) }} |
        {{ __('reports.document_inventory.generated_by') }} | {{ __('hrms.page') }} {PAGENO} {{ __('hrms.of') }} {nbpg}
    </div>
</body>
</html>