<!doctype html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale()==='ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="utf-8">
    <title>{{ __('Salary History') }} — {{ $employee->display_name }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        .brand-header { color: #2e4029; border-bottom: 2px solid #c6a44a; padding-bottom: 6px; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ccc; padding: 6px; }
        th { background: #f9f5e6; color: #2e4029; }
        .small { font-size: 11px; color: #666; }
    </style>
</head>

<body>
    <h3 class="brand-header">{{ __('Salary History') }} — {{ $employee->display_name }}</h3>
    <p class="small">{{ __('Generated at') }}: {{ $generatedAt }}</p>
    <table>
        <thead>
            <tr>
                <th>{{ __('Effective From') }}</th>
                <th>{{ __('Effective To') }}</th>
                <th>{{ __('Earnings') }}</th>
                <th>{{ __('Deductions') }}</th>
                <th>{{ __('Gross') }}</th>
                <th>{{ __('Net') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rows as $r)
            <tr>
                <td>{{ $r['Effective From'] }}</td>
                <td>{{ $r['Effective To'] }}</td>
                <td>{{ $r['Earnings'] }}</td>
                <td>{{ $r['Deductions'] }}</td>
                <td>{{ $r['Gross'] }}</td>
                <td>{{ $r['Net'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
