<!DOCTYPE html>
<html lang="{{ $letter->letterTemplate->language === 'ar' ? 'ar' : 'en' }}"
      dir="{{ $letter->letterTemplate->language === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $letter->subject }}</title>
    <style>
        body {
            font-family: {{ $letter->letterTemplate->language === 'ar' ? '"Times New Roman", serif' : '"Times New Roman", serif' }};
            line-height: 1.6;
            margin: 0;
            padding: 0;
            color: #333;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        .letterhead {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #c6a44a;
            padding-bottom: 20px;
        }

        .company-name {
            font-size: 24px;
            font-weight: bold;
            color: #2e4029;
            margin-bottom: 5px;
        }

        .company-tagline {
            font-size: 14px;
            color: #666;
            font-style: italic;
        }

        .reference-number {
            text-align: right;
            margin-bottom: 20px;
            font-weight: bold;
            color: #c6a44a;
        }

        .letter-date {
            text-align: {{ $letter->letterTemplate->language === 'ar' ? 'right' : 'left' }};
            margin-bottom: 30px;
            font-weight: bold;
        }

        .letter-content {
            margin-bottom: 40px;
            text-align: {{ $letter->letterTemplate->language === 'ar' ? 'right' : 'left' }};
        }

        .letter-content h1, .letter-content h2 {
            color: #2e4029;
            text-align: center;
            margin-bottom: 20px;
        }

        .letter-content p {
            margin-bottom: 15px;
            text-align: justify;
        }

        .signature-section {
            margin-top: 60px;
            text-align: {{ $letter->letterTemplate->language === 'ar' ? 'right' : 'left' }};
        }

        .signature-line {
            border-bottom: 1px solid #333;
            width: 200px;
            margin-bottom: 5px;
            {{ $letter->letterTemplate->language === 'ar' ? 'margin-right: 0' : 'margin-left: 0' }};
        }

        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 48px;
            color: rgba(200, 200, 200, 0.3);
            z-index: -1;
            font-weight: bold;
        }

        .footer {
            position: fixed;
            bottom: 20px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }

        /* Arabic-specific styles */
        .rtl {
            direction: rtl;
            text-align: right;
        }

        .rtl .reference-number {
            text-align: left;
        }

        .rtl .letter-date {
            text-align: right;
        }

        .rtl .signature-section {
            text-align: right;
        }

        .rtl .signature-line {
            margin-left: auto;
            margin-right: 0;
        }
    </style>
</head>
<body>
    <!-- Watermark -->
    @if($letter->status === \App\Models\GeneratedLetter::STATUS_DRAFT)
        <div class="watermark">{{ __('DRAFT') }}</div>
    @elseif($letter->status === \App\Models\GeneratedLetter::STATUS_PENDING_APPROVAL)
        <div class="watermark">{{ __('PENDING APPROVAL') }}</div>
    @endif

    <div class="container">
        <!-- Letterhead -->
        <div class="letterhead">
            <div class="company-name">
                {{ $letter->letterTemplate->language === 'ar' ? 'ساري الدين وشركاؤه للاستشارات القانونية' : 'Sarie Eldin & Partners Legal Advisors' }}
            </div>
            <div class="company-tagline">
                {{ $letter->letterTemplate->language === 'ar' ? 'الخبرة القانونية التي تثق بها' : 'Legal Expertise You Can Trust' }}
            </div>
        </div>

        <!-- Reference Number -->
        <div class="reference-number">
            {{ __('Ref:') }} {{ $letter->reference_number }}
        </div>

        <!-- Date -->
        <div class="letter-date">
            {{ $letter->letterTemplate->language === 'ar' ? 'التاريخ:' : 'Date:' }}
            {{ $letter->created_at->format('d/m/Y') }}
            @if($letter->letterTemplate->language === 'ar')
                <br>{{ $letter->created_at->locale('ar')->isoFormat('D MMMM YYYY') }}
            @endif
        </div>

        <!-- Letter Content -->
        <div class="letter-content">
            {!! $letter->content !!}
        </div>

        <!-- Signature Section -->
        <div class="signature-section">
            <p>{{ $letter->letterTemplate->language === 'ar' ? 'مع التحية،' : 'Sincerely,' }}</p>
            <br><br>
            <div class="signature-line"></div>
            <p><strong>{{ $letter->approvedBy->name ?? $letter->generatedBy->name }}</strong><br>
            {{ $letter->letterTemplate->language === 'ar' ? 'قسم الموارد البشرية' : 'Human Resources Department' }}<br>
            {{ $letter->letterTemplate->language === 'ar' ? 'ساري الدين وشركاؤه للاستشارات القانونية' : 'Sarie Eldin & Partners Legal Advisors' }}</p>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        {{ $letter->letterTemplate->language === 'ar' ? 'هذه الوثيقة مُنتجة إلكترونياً وصالحة دون توقيع' : 'This document is electronically generated and valid without signature' }}<br>
        {{ __('Generated on') }} {{ now()->format('d/m/Y H:i') }} | {{ $letter->reference_number }}
    </div>
</body>
</html>