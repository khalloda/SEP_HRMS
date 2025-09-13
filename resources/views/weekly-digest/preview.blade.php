<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('Weekly Digest Email Preview') }}</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f8f9fa;
        }
        .email-container {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #2e4029 0%, #c6a44a 100%);
            color: white;
            padding: 30px 40px;
            text-align: center;
        }
        .content {
            padding: 40px;
        }
        .greeting {
            font-size: 24px;
            font-weight: 600;
            color: #2e4029;
            margin-bottom: 20px;
        }
        .section {
            margin-bottom: 30px;
        }
        .section h2 {
            color: #c6a44a;
            font-size: 20px;
            margin-bottom: 15px;
            border-bottom: 2px solid #c6a44a;
            padding-bottom: 5px;
        }
        .urgent {
            background-color: #fff5f5;
            border-left: 4px solid #e53e3e;
            padding: 15px;
            margin-bottom: 15px;
        }
        .critical {
            background-color: #fefcbf;
            border-left: 4px solid #d69e2e;
            padding: 15px;
            margin-bottom: 15px;
        }
        .soon {
            background-color: #f7fafc;
            border-left: 4px solid #4299e1;
            padding: 15px;
            margin-bottom: 15px;
        }
        .item {
            margin: 8px 0;
            padding-left: 20px;
            position: relative;
        }
        .item::before {
            content: '•';
            position: absolute;
            left: 0;
            color: #c6a44a;
            font-weight: bold;
        }
        .action-button {
            display: inline-block;
            background-color: #c6a44a;
            color: white !important;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            margin: 20px 0;
            text-align: center;
        }
        .footer {
            background-color: #2e4029;
            color: white;
            padding: 30px 40px;
            text-align: center;
        }
        .preview-note {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
            text-align: center;
        }
        ul {
            list-style: none;
            padding: 0;
        }
        .summary-stats {
            display: flex;
            justify-content: space-around;
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 6px;
            margin: 20px 0;
        }
        .stat {
            text-align: center;
        }
        .stat-number {
            font-size: 24px;
            font-weight: bold;
            color: #c6a44a;
        }
        .stat-label {
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="preview-note">
        <strong>📧 {{ __('Email Preview') }}</strong> - {{ __('This is how the weekly digest email will appear to recipients') }}
    </div>

    <div class="email-container">
        <!-- Email Header -->
        <div class="header">
            <h1 style="margin: 0; font-size: 28px;">{{ __('Weekly HRMS Digest') }}</h1>
            <p style="margin: 10px 0 0 0; opacity: 0.9;">
                {{ now()->startOfWeek()->format('M j') }} to {{ now()->endOfWeek()->format('M j, Y') }}
            </p>
        </div>

        <!-- Email Content -->
        <div class="content">
            <div class="greeting">{{ __('Weekly HRMS Digest') }}</div>

            <p>{{ __('Here\'s your weekly summary of important HRMS activities and upcoming items that require attention.') }}</p>

            <!-- Summary Statistics -->
            <div class="summary-stats">
                <div class="stat">
                    <div class="stat-number">{{ count($digestData['contracts']['expiring_urgently']) + count($digestData['contracts']['expiring_critically']) + count($digestData['contracts']['expiring_soon']) }}</div>
                    <div class="stat-label">{{ __('Contracts') }}</div>
                </div>
                <div class="stat">
                    <div class="stat-number">{{ count($digestData['documents']['expiring']) }}</div>
                    <div class="stat-label">{{ __('Documents') }}</div>
                </div>
                <div class="stat">
                    <div class="stat-number">{{ count($digestData['birthdays']) }}</div>
                    <div class="stat-label">{{ __('Birthdays') }}</div>
                </div>
                <div class="stat">
                    <div class="stat-number">{{ array_sum($digestData['activity_summary']) }}</div>
                    <div class="stat-label">{{ __('Activities') }}</div>
                </div>
            </div>

            <!-- Contract Expiries Section -->
            @if(!empty($digestData['contracts']['expiring_urgently']) ||
                !empty($digestData['contracts']['expiring_critically']) ||
                !empty($digestData['contracts']['expiring_soon']))
            <div class="section">
                <h2>{{ __('Contract Expiries') }}</h2>

                @if(!empty($digestData['contracts']['expiring_urgently']))
                <div class="urgent">
                    <strong>🔴 {{ __('Urgent (≤7 days):') }}</strong>
                    <ul>
                        @foreach($digestData['contracts']['expiring_urgently'] as $contract)
                        <li class="item">
                            {{ $contract->employee->display_name }} - {{ $contract->type_name }}
                            ({{ now()->diffInDays($contract->end_date, false) }} {{ __('days') }})
                        </li>
                        @endforeach
                    </ul>
                </div>
                @endif

                @if(!empty($digestData['contracts']['expiring_critically']))
                <div class="critical">
                    <strong>🟠 {{ __('Critical (8-15 days):') }}</strong>
                    <ul>
                        @foreach($digestData['contracts']['expiring_critically'] as $contract)
                        <li class="item">
                            {{ $contract->employee->display_name }} - {{ $contract->type_name }}
                            ({{ now()->diffInDays($contract->end_date, false) }} {{ __('days') }})
                        </li>
                        @endforeach
                    </ul>
                </div>
                @endif

                @if(!empty($digestData['contracts']['expiring_soon']))
                <div class="soon">
                    <strong>🟡 {{ __('Soon (16-30 days):') }}</strong>
                    <ul>
                        @foreach($digestData['contracts']['expiring_soon'] as $contract)
                        <li class="item">
                            {{ $contract->employee->display_name }} - {{ $contract->type_name }}
                            ({{ now()->diffInDays($contract->end_date, false) }} {{ __('days') }})
                        </li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <a href="{{ route('contracts.index') }}" class="action-button">{{ __('View Contract Management') }}</a>
            </div>
            @endif

            <!-- Document Expiries Section -->
            @if(!empty($digestData['documents']['expiring']))
            <div class="section">
                <h2>{{ __('Document Expiries') }}</h2>
                <ul>
                    @foreach($digestData['documents']['expiring'] as $document)
                    <li class="item">
                        {{ $document->owner_name }} - {{ $document->type_display_name }}
                        ({{ $document->expires_at ? now()->diffInDays($document->expires_at, false) : 0 }} {{ __('days') }})
                    </li>
                    @endforeach
                </ul>
                <a href="{{ route('documents.index') }}" class="action-button">{{ __('View Document Management') }}</a>
            </div>
            @endif

            <!-- Birthdays Section -->
            @if(!empty($digestData['birthdays']))
            <div class="section">
                <h2>{{ __('Upcoming Birthdays') }} 🎂</h2>
                <ul>
                    @foreach($digestData['birthdays'] as $employee)
                    @php
                        $birthdayDate = \Carbon\Carbon::createFromFormat('m-d', $employee->birth_date->format('m-d'));
                        if ($birthdayDate->isPast()) {
                            $birthdayDate->addYear();
                        }
                        $daysUntil = now()->diffInDays($birthdayDate, false);
                    @endphp
                    <li class="item">
                        {{ $employee->display_name }} - {{ $birthdayDate->format('M j') }} ({{ $daysUntil }} {{ __('days') }})
                    </li>
                    @endforeach
                </ul>
            </div>
            @endif

            <!-- New Hires Section -->
            @if(!empty($digestData['new_hires']))
            <div class="section">
                <h2>{{ __('New Hires This Week') }} 👋</h2>
                <ul>
                    @foreach($digestData['new_hires'] as $employee)
                    <li class="item">
                        {{ $employee->display_name }} - {{ $employee->position->name }} ({{ $employee->hire_date->format('M j') }})
                    </li>
                    @endforeach
                </ul>
            </div>
            @endif

            <!-- Activity Summary -->
            @if(array_sum($digestData['activity_summary']) > 0)
            <div class="section">
                <h2>{{ __('Weekly Activity Summary') }}</h2>
                <ul>
                    @foreach($digestData['activity_summary'] as $type => $count)
                        @if($count > 0)
                        <li class="item">{{ ucfirst(str_replace('_', ' ', $type)) }}: {{ $count }}</li>
                        @endif
                    @endforeach
                </ul>
            </div>
            @endif

            <p>{{ __('Thank you for using our HRMS system!') }}</p>
        </div>

        <!-- Email Footer -->
        <div class="footer">
            <p style="margin: 0;">{{ __('Best regards,') }}<br>{{ config('app.name') }}</p>
        </div>
    </div>

    <div class="preview-note" style="margin-top: 20px;">
        <strong>ℹ️ {{ __('Note') }}:</strong> {{ __('This preview shows the actual email content. Recipients will receive this in their email client.') }}
    </div>
</body>
</html>