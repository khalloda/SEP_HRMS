<!doctype html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale()==='ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <title>{{ __('Salary History') }} — {{ $employee->display_name }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ccc; padding: 6px; }
        th { background: #f0f0f0; }
    </style>
    </head>
<body>
    <h3>{{ __('Salary History') }} — {{ $employee->display_name }}</h3>
    <p>{{ __('Generated at') }}: {{ $generatedAt }}</p>
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

