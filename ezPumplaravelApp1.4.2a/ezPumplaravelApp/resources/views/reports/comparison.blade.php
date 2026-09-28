@extends('layouts.app')

@section('content')

    <style>
        /* ===== BASE STYLES ===== */
        body {
            font-size: 13px;
            background: #f8fafc;
            font-family: "Inter", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            color: #1e293b;
            line-height: 1.5;
        }

        .report-container {
            background: #ffffff;
            padding: 24px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(15, 23, 42, 0.05);
            border: 1px solid #e2e8f0;
            max-width: 1300px;
            margin: 0 auto;
        }

        /* ===== TABLES ===== */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 16px 0;
        }

        th, td {
            border: 1px solid #d1d5db;
            padding: 10px 12px;
            text-align: center;
            vertical-align: middle;
            font-size: 12.5px;
            font-weight: 600 !important; /* All table text bold */
        }

        th {
            background: #564b49 !important; /* Solid brown color */
            color: white !important;
            font-weight: 700 !important;
            font-size: 12.5px;
            border-color: #1e3a8a;
        }

        /* ===== HEADER ===== */
        .header-container {

            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #564b49;
            padding-bottom: 5px;
        }
        .header-container, .section-title {
            margin-bottom: 0 !important;
            padding-bottom: 5px !important;
        }

        .logo-title-container {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 20px;
            margin-bottom: 10px;
        }

        .site-logo {
            height: 70px;
            width: auto;
            max-width: 200px;
            object-fit: contain;
        }

        .site-name {
            font-size: 28px;
            font-weight: 800;
            color: #564b49;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .report-title {
            font-size: 20px;
            font-weight: 700;
            color: #1e40af;
            margin: 5px 0;
            text-transform: uppercase;
        }

        .report-subtitle {
            font-size: 14px;
            color: #64748b;
            font-weight: 600;
        }

        /* ===== INFO BOX ===== */
        .info-box {
            border: 1px solid #e2e8f0;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            padding: 15px 20px;
            margin: 20px 0;
            border-radius: 8px;
            font-size: 13px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 15px;
            font-weight: 600;
        }

        .info-item {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .info-box strong {
            color: #564b49;
            min-width: 100px;
            display: inline-block;
            font-weight: 700;
        }

        /* ===== ZEBRA STRIPING ===== */
        .zebra tbody tr:nth-child(even) {
            background: #f8fafc;
        }

        .zebra tbody tr:hover {
            background: #f1f5f9;
            transition: background 0.2s ease;
        }

        /* ===== TABLE HEADERS ===== */
        .sub-title {
            background: #059669 !important; /* Solid green */
            color: #ffffff !important;
            font-weight: 700 !important;
            text-align: center;
            font-size: 13px;
            padding: 12px !important;
            border-color: #047857;
        }

        .total-table th {
            background: #047857 !important;
            border-color: #047857;
        }

        /* ===== TEXT STYLES ===== */
        .right { text-align: right; }
        .left { text-align: left; }
        .center { text-align: center; }

        /* ===== DIFFERENCE INDICATORS ===== */
        .diff-negative {
            color: #dc2626 !important;
            font-weight: 700 !important;
            background: rgba(220, 38, 38, 0.08);
            border-radius: 4px;
            padding: 4px 8px;
            display: inline-block;
        }

        .diff-positive {
            color: #059669 !important;
            font-weight: 700 !important;
            background: rgba(5, 150, 105, 0.08);
            border-radius: 4px;
            padding: 4px 8px;
            display: inline-block;
        }

        /* ===== BADGES ===== */
        .badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            border: 1px solid;
        }

        .badge-primary {
            background: #dbeafe;
            color: #1e40af;
            border-color: #bfdbfe;
        }

        .badge-success {
            background: #d1fae5;
            color: #059669;
            border-color: #a7f3d0;
        }

        .badge-danger {
            background: #fee2e2;
            color: #dc2626;
            border-color: #fecaca;
        }

        .badge-dark {
            background: #d1d5db;
            color: #374151;
            border-color: #9ca3af;
        }

        .badge-info {
            background: #dbeafe;
            color: #1d4ed8;
            border-color: #bfdbfe;
        }

        .badge-warning {
            background: #fef3c7;
            color: #92400e;
            border-color: #fde68a;
        }

        /* ===== TOTAL ROW ===== */
        .total-row {
            background: #f0f9ff !important;
            font-weight: 700 !important;
            border-top: 2px solid #0ea5e9;
            border-bottom: 2px solid #0ea5e9;
        }

        .total-row td {
            font-weight: 700 !important;
        }

        /* ===== SECTION TITLES ===== */
        .section-title {
            font-size: 16px;
            font-weight: 700;
            color: #564b49;
            margin: 25px 0 15px 0;
            padding-bottom: 10px;
            border-bottom: 2px solid #e2e8f0;
            text-align: center;
            text-transform: uppercase;
        }

        /* ===== ACTION BUTTONS ===== */
        .action-buttons {
            display: flex;
            gap: 5px;
            /*margin-bottom: 20px;*/
            justify-content: flex-end;
        }

        .btn {
            padding: 10px 20px;
            border-radius: 6px;
            font-weight: 600;
            font-size: 13px;
            border: none;
            cursor: pointer;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            border: 1px solid transparent;
        }

        .btn-print {
            background: linear-gradient(135deg, #564b49 0%, #3d3635 100%);
            color: white;
            border-color: #3d3635;
        }

        .btn-pdf {
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
            color: white;
            border-color: #b91c1c;
        }

        .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        /* ===== SUMMARY CARDS ===== */
        .summary-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin: 25px 0;
        }

        .card {
            background: white;
            border-radius: 10px;
            padding: 18px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            border: 1px solid #e2e8f0;
            text-align: center;
        }

        .card-title {
            font-size: 12px;
            color: #64748b;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
        }

        .card-value {
            font-size: 22px;
            font-weight: 800;
            color: #564b49;
            margin: 8px 0;
        }

        .card-subtext {
            font-size: 11px;
            color: #94a3b8;
            margin-top: 4px;
            font-weight: 600;
        }

        /* ===== FOOTER ===== */
        .report-footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px solid #e2e8f0;
            font-size: 12px;
            color: #64748b;
            text-align: center;
            font-weight: 600;
        }

        /* ===== PRINT STYLES ===== */
        /* ===== PRINT STYLES ===== */
        .print-wide-table {
            overflow-x: auto;
        }
        .cs-invoice.cs-style1 {
            background: #fff;
            border-radius: 10px;
            padding: 4px;
        }
        @media print {
            /* Hide app header/nav when printing */
            .login-header,
            .navbar,
            .action-buttons,
            .btn {
                display: none !important;
                visibility: hidden !important;
            }
            /* Page Settings */
            @page {
                size: A4 landscape;
                /*margin: 10mm;*/
            }
            .print-section {
                page-break-inside: avoid !important;
                break-inside: avoid !important;
                padding: 2px;
            }
            .print-wide-table {
                overflow: visible !important;
            }
            .cs-invoice.cs-style1 {
                background: #fff;
                border-radius: 10px;
                padding: 2px;
            }
            body {
                background: #ffffff !important;
                font-size: 10px !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                color-adjust: exact !important;
                font-family: "Inter", sans-serif !important;
                margin: 0 !important;
                padding: 0 !important;
            }

            .report-container {
                box-shadow: none !important;
                border: none !important;
                padding: 0 !important;
                max-width: 100% !important;
                margin: 0 !important;
                page-break-before: auto !important;
                page-break-after: auto !important;
                break-inside: avoid !important;
            }

            /* Hide Non-Print Elements */
            .no-print1,
            .action-buttons,
            .btn,
            button {
                display: none !important;
                visibility: hidden !important;
            }

            /* Force Colors and Bold for Print */
            th {
                background: #564b49 !important;
                color: #ffffff !important;
                font-weight: 700 !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                border-color: #3d3635 !important;
            }

            td {
                color: #000000 !important;
                font-weight: 600 !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            .sub-title {
                background: #059669 !important;
                color: #ffffff !important;
                font-weight: 700 !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            /* Table Adjustments */
            table {
                page-break-inside: avoid;
                break-inside: avoid;
                width: 100% !important;
                border-collapse: collapse !important;
                margin: 12px 0 !important;
            }

            th, td {
                font-size: 9px !important;
                padding: 6px 8px !important;
                border: 1px solid #d1d5db !important;
                font-weight: 600 !important;
            }

            /* Header for Print */
            .header-container {
                border-bottom: 2px solid #564b49 !important;
                padding-bottom: 10px !important;
                margin-bottom: 15px !important;
            }

            .site-name {
                color: #564b49 !important;
                font-weight: 800 !important;
                font-size: 24px !important;
            }

            .report-title {
                color: #1e40af !important;
                font-weight: 700 !important;
                font-size: 18px !important;
            }

            /* Info Box for Print */
            .info-box {
                border: 1px solid #e2e8f0;
                background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
                padding: 15px 20px;
                margin: 20px 0;
                border-radius: 8px;
                font-size: 13px;
                display: grid !important;
                grid-template-columns: repeat(3, 1fr) !important;
                gap: 15px !important;
                font-weight: 600;
                page-break-inside: avoid !important;
                break-inside: avoid !important;
            }

            .info-box strong {
                color: #564b49 !important;
                font-weight: 700 !important;
            }

            /* Cards for Print */
            .card {
                border: 1px solid #d1d5db !important;
                box-shadow: none !important;
                page-break-inside: avoid !important;
                break-inside: avoid !important;
            }

            .card-value {
                color: #564b49 !important;
                font-weight: 800 !important;
            }

            /* Badges in Print */
            .badge {
                border: 1px solid #000 !important;
                background: #ffffff !important;
                color: #000000 !important;
                font-weight: 700 !important;
            }

            /* Difference Indicators */
            .diff-negative,
            .diff-positive {
                font-weight: 700 !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            .diff-negative {
                color: #dc2626 !important;
            }

            .diff-positive {
                color: #059669 !important;
            }

            /* Total Row */
            .total-row {
                background: #f0f9ff !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                font-weight: 700 !important;
            }

            /* Footer */
            .report-footer {
                border-top: 1px solid #d1d5db !important;
                color: #666666 !important;
                font-weight: 600 !important;
            }

            /* Prevent Widows and Orphans */
            h1, h2, h3, h4, h5, h6 {
                page-break-after: avoid !important;
            }

            p, li, div {
                page-break-inside: avoid !important;
            }

            /* Adjust logo size for print */
            .site-logo {
                height: 60px !important;
            }
        }


        /* ===== RESPONSIVE ===== */
        @media (max-width: 768px) {
            .report-container {
                padding: 15px;
            }

            .logo-title-container {
                flex-direction: column;
                text-align: center;
                gap: 10px;
            }

            .info-box {
                grid-template-columns: 1fr;
            }



            .action-buttons {
                flex-direction: column;
                align-items: stretch;
            }

            .btn {
                justify-content: center;
            }

            .site-name {
                font-size: 22px;
            }

            .report-title {
                font-size: 18px;
            }
        }

        /* ===== WIDE TABLE FOR PRINT ===== */
        .print-wide-table {
            overflow-x: auto;
        }

        @media print {
            body {
                margin: 0;
                padding: 0;
            }
            .print-wide-table {
                overflow: visible !important;
            }

            .print-wide-table table {
                width: 100% !important;
            }
            .info-box {
                display: grid !important;
                grid-template-columns: repeat(3, 1fr) !important;
                gap: 15px !important;
            }
        }

        /* ===== UTILITY CLASSES ===== */
        .text-bold {
            font-weight: 700 !important;
        }

        .text-center {
            text-align: center !important;
        }

        .mb-20 {
            margin-bottom: 20px;
        }

        .mt-20 {
            margin-top: 20px;
        }

        .mb-30 {
            margin-bottom: 30px;
        }

        .mt-30 {
            margin-top: 30px;
        }
    </style>

    <div class="report-container">

        {{-- Action Buttons --}}
        {{-- Action Buttons --}}
        <div class="action-buttons no-print">
            <button class="btn btn-print" onclick="window.print()">
                <span>🖨️</span> Print Report
            </button>
            <button class="btn btn-pdf" onclick="saveAsPDF()">
                <span>📄</span> Save as PDF
            </button>
        </div>
        <div class="print-section">
        {{-- Header Section --}}
{{--        <div class="header-container">--}}
{{--            <div class="logo-title-container">--}}
{{--                @php--}}
{{--                    $name = DB::table('settings')->where('key','name')->first();--}}
{{--                    $siteLogo = DB::table('settings')->where('key','logo')->first();--}}
{{--                @endphp--}}

{{--                @if($siteLogo && $siteLogo->value)--}}
{{--                    <img src="{{ Storage::url($siteLogo->value) }}" class="site-logo" alt="Site Logo">--}}
{{--                @endif--}}

{{--                <div class="site-name">--}}
{{--                    {{ $name->value ?? 'Fuel Management System' }}--}}
{{--                </div>--}}
{{--            </div>--}}

{{--            <div class="report-title">DAILY SHIFT REPORT</div>--}}
{{--        </div>--}}
            @php
                $settings = \App\Models\Settings::getSettingsArray();
                $settings = $settings ?? [];
            @endphp
            @if (!isset($excludeHeader) || !$excludeHeader)
                @include('reports.print_headers', [
        'settings' => $settings,
//        'shifts' => $shifts ?? [],
//        'reportType' => $reportType ?? 'shift_summary',
//        'tankShiftLogs' => $shiftLogs ?? []
    ])
            @endif
        {{-- Info Box --}}
        @php
            $sysParam = DB::table('settings')->where('key','line2')->first();
            use Carbon\Carbon;
            $backendparam = $shiftLogs->first() ?? null;
            $now = Carbon::now();
            $hour = $now->hour;
            $sys_config = DB::table('SysConfig')->first();

            $shiftName = '';
            $shiftTime = '';
            $badge = 'badge-primary';

            if (!isset($sys_config->NoofShifts) || $sys_config->NoofShifts == 1) {
                $shiftName = 'Full Day Shift';
                $shiftTime = '00:00 - 23:59';
            } elseif ($sys_config->NoofShifts == 2) {
                if ($hour >= 6 && $hour < 18) {
                    $shiftName = 'Day Shift';
                    $shiftTime = '06:00 - 18:00';
                    $badge = 'badge-success';
                } else {
                    $shiftName = 'Night Shift';
                    $shiftTime = '18:00 - 06:00';
                    $badge = 'badge-dark';
                }
            } elseif ($sys_config->NoofShifts == 3) {
                if ($hour >= 6 && $hour < 14) {
                    $shiftName = 'Morning Shift';
                    $shiftTime = '06:00 - 14:00';
                    $badge = 'badge-info';
                } elseif ($hour >= 14 && $hour < 22) {
                    $shiftName = 'Evening Shift';
                    $shiftTime = '14:00 - 22:00';
                    $badge = 'badge-warning';
                } else {
                    $shiftName = 'Night Shift';
                    $shiftTime = '22:00 - 06:00';
                    $badge = 'badge-dark';
                }
            }
        @endphp

{{--        <div class="info-box print-keep-together">--}}
{{--            <div class="info-item">--}}
{{--                <strong>Site Name:</strong>--}}
{{--                <span class="badge badge-primary">{{ $name->value ?? 'N/A' }}</span>--}}
{{--            </div>--}}
{{--            <div class="info-item">--}}
{{--                <strong>Start Date:</strong>--}}
{{--                <span class="text-bold">{{ $backendparam->start_date ?? 'N/A' }}</span>--}}
{{--            </div>--}}
{{--            <div class="info-item">--}}
{{--                <strong>Shift:</strong>--}}
{{--                <span class="badge {{ $badge }} text-bold">--}}
{{--                    {{ $shiftName }} ({{ $shiftTime }})--}}
{{--                </span>--}}
{{--            </div><br>--}}
{{--            <div class="info-item">--}}
{{--                <strong>Location:</strong>--}}
{{--                <span class="text-bold">{{ $sysParam->value ?? 'N/A' }}</span>--}}
{{--            </div>--}}
{{--            <div class="info-item">--}}
{{--                <strong>End Date:</strong>--}}
{{--                <span class="text-bold">{{ $backendparam->end_date ?? 'N/A' }}</span>--}}
{{--            </div>--}}
{{--            <div class="info-item">--}}
{{--                <strong>Report Date:</strong>--}}
{{--                <span class="text-bold">{{ Carbon::now()->format('d-m-Y H:i:s') }}</span>--}}
{{--            </div>--}}
{{--        </div>--}}


{{--            <div style="display: flex; justify-content: space-between; margin-bottom: 1px;">--}}
{{--                <span>{{ $backendparam->start_date ?? 'N/A' }}</span>--}}
{{--                <span>{{ $backendparam->end_date ?? 'N/A' }}</span>--}}
{{--            </div>--}}
{{--        --}}{{-- ===== NOZZLE DETAILS ===== --}}
{{--        <div class="section-title">Nozzle Readings & Sales</div>--}}
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px;">
                <!-- Left: Start Date -->
                <span class="cs-primary_color cs-semi_bold" style="font-weight: 700; font-size: 13px;">Start Date -{{ $backendparam->start_date ?? 'N/A' }}</span>

                <!-- Center: Section Title / Nozzle Name -->
                <span  class="cs-primary_color cs-semi_bold" style="text-align: center; font-weight: 700; font-size: 14px;">
        Nozzle Readings & Sales
    </span>

                <!-- Right: End Date -->
                <span class="cs-primary_color cs-semi_bold" style="font-weight: 700; font-size: 13px;">End Date - {{ $backendparam->end_date ?? 'N/A' }}</span>
            </div>

            <div class="print-wide-table">
            <table class="zebra">
                <thead>
                <tr>
{{--                    <th class="sub-title" colspan="8">NOZZLE READINGS & SALES</th>--}}
                </tr>
                <tr>
                    <th style="width: 12%;">Nozzle #</th>
                    <th style="width: 11%;">System Opening</th>
                    <th style="width: 11%;">Manual Opening</th>
                    <th style="width: 11%;">System Closing</th>
                    <th style="width: 11%;">Manual Closing</th>
                    <th style="width: 11%;">System Sale</th>
                    <th style="width: 11%;">Manual Sale</th>
                    <th style="width: 12%;">Difference</th>
                </tr>
                </thead>
                <tbody>
                @foreach($nozzleRows as $row)
                    <tr>
                        <td class="text-bold">{{ $row['nozzle'] ?? 'N/A' }}</td>
                        <td>{{ number_format($row['sys_opening'] ?? 0, 2, '.', '') }}</td>
                        <td>{{ number_format($row['manual_opening'] ?? 0, 2, '.', '') }}</td>
                        <td>{{ number_format($row['sys_closing'] ?? 0, 2, '.', '') }}</td>
                        <td>{{ number_format($row['manual_closing'] ?? 0, 2, '.', '') }}</td>
                        <td class="text-bold">{{ number_format($row['sys_sale'] ?? 0, 2, '.', '') }}</td>
                        <td class="text-bold">{{ number_format($row['manual_sale'] ?? 0, 2, '.', '') }}</td>
                        <td>
                            @php
                                $diff = ($row['difference'] ?? 0);
                            @endphp
                            @if($diff < 0)
                                <span class="diff-negative">▼ {{ number_format(abs($diff), 2, '.', '') }}</span>
                            @elseif($diff > 0)
                                <span class="diff-positive">▲ {{ number_format($diff, 2, '.', '') }}</span>
                            @else
                                <span>0.00</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        </div>
        {{-- ===== PRODUCT TOTAL ===== --}}
        <div class="section-title mt-30 cs-primary_color cs-semi_bold">Product Wise Summary</div>
        <div class="print-wide-table">
            <table>
                <thead>
                <tr>
{{--                    <th class="sub-title" colspan="5">PRODUCT WISE TOTAL SALE (LITERS)</th>--}}
                </tr>
                <tr>
                    <th style="width: 25%;">Product</th>
                    <th style="width: 25%;">System Sale</th>
                    <th style="width: 25%;">Manual Sale</th>
                    <th style="width: 25%;">Difference</th>
                </tr>
                </thead>
                <tbody>
                @foreach($productSummary as $row)
                    <tr>
                        <td class="text-bold">{{ $row['product'] ?? 'N/A' }}</td>
                        <td class="text-bold">{{ number_format($row['system_sale'] ?? 0, 2, '.', '') }}</td>
                        <td class="text-bold">{{ number_format($row['manual_sale'] ?? 0, 2, '.', '') }}</td>
                        <td>
                            @php
                                $diff = ($row['difference'] ?? 0);
                            @endphp
                            @if($diff < 0)
                                <span class="diff-negative">▼ {{ number_format(abs($diff), 2, '.', '') }}</span>
                            @elseif($diff > 0)
                                <span class="diff-positive">▲ {{ number_format($diff, 2, '.', '') }}</span>
                            @else
                                <span>0.00</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
                {{-- Total Row --}}
{{--                <tr class="total-row">--}}
{{--                    <td class="text-bold">TOTAL</td>--}}
{{--                    <td class="text-bold">{{ number_format($totalSystem ?? 0, 2) }}</td>--}}
{{--                    <td class="text-bold">{{ number_format($totalManual ?? 0, 2) }}</td>--}}
{{--                    <td class="text-bold">--}}
{{--                        @if(($totalDiff ?? 0) < 0)--}}
{{--                            <span class="diff-negative">▼ {{ number_format(abs($totalDiff ?? 0), 2) }}</span>--}}
{{--                        @elseif(($totalDiff ?? 0) > 0)--}}
{{--                            <span class="diff-positive">▲ {{ number_format(($totalDiff ?? 0), 2) }}</span>--}}
{{--                        @else--}}
{{--                            <span>0.00</span>--}}
{{--                        @endif--}}
{{--                    </td>--}}
{{--                </tr>--}}
                </tbody>
            </table>
        </div>

        {{-- ===== TANK INVENTORY ===== --}}
        <div class="section-title mt-30 cs-primary_color cs-semi_bold">Tank Inventory Management</div>
        <div class="print-wide-table">
            {{--                {{dd($tankRows)}}--}}
            <table class="zebra" style="font-size: 0.9em;">
                <thead>
                <tr>
{{--                    <th class="sub-title" colspan="16">TANK INVENTORY MANAGEMENT</th>--}}
                </tr>
                <tr>
                    <th>Tank # </th>
{{--                    <th>Product</th>--}}
                    <th>Sys Opening Dip</th>
                    <th>Sys Opening Stock</th>
                    <th>Man Opening Dip</th>
                    <th>Man Opening Stock</th>
                    <th>Sys Purchase</th>
                    <th>Man Purchase</th>
                    <th>Sys Sale</th>
                    <th>Man Sale</th>
                    <th>Sys Closing Dip</th>
                    <th>Man Closing Dip</th>
                    <th>Sys Closing Stock</th>
                    <th>Man Closing Stock</th>
                    <th>Diff</th>
                    <th>Gain/Loss</th>
                </tr>
                </thead>
                <tbody>
                @php
                    use Illuminate\Support\Facades\Log;


//                        dd($tankRows)
                @endphp

                @foreach($tankRows as $row)

                    <tr>
                        <td><span class="badge badge-primary text-bold">{{ $row['tank_no'] ?? 'N/A' }}</span></td>
{{--                        <td class="text-bold">{{ $row['product'] ?? 'N/A' }}</td>--}}
                        <td class="text-bold">{{ $row['sys_open_dip'] ?? '0.00' }}</td>
                        <td class="text-bold">
                                    <span class="opening-stock"
                                          data-tank="{{ $row['tank_id'] ?? '' }}"
                                          data-mm="{{ $row['sys_open_dip'] ?? 0 }}">
                                        --
                                    </span>
                        </td>
                        <td class="text-bold">{{ $row['man_open_dip'] ?? '0.00' }}</td>
                        <td class="text-bold">{{ $row['man_open_stock'] ?? '0.00' }}</td>
                        <td class="text-bold">{{ number_format($row['sys_purchase'] ?? 0, 2) }}</td>
                        <td class="text-bold">{{ number_format($row['man_purchase'] ?? 0, 2) }}</td>
                        <td class="text-bold">{{ number_format($row['sys_sale'] ?? 0, 2) }}</td>
                        <td class="text-bold">{{ number_format($row['man_sale'] ?? 0, 2) }}</td>
                        <td class="text-bold">{{ $row['sys_close_dip'] ?? '0.00' }}</td>
                        <td class="text-bold">{{ $row['man_close_dip'] ?? '0.00' }}</td>
                        <td class="text-bold">
                                    <span class="closing-stock"
                                          data-tank="{{ $row['tank_id'] ?? '' }}"
                                          data-mm="{{ $row['sys_close_dip'] ?? 0 }}">
                                        --
                                    </span>
                        </td>
                        <td class="text-bold">{{ $row['man_closing_stock'] ?? '0.00' }}</td>
                        <td class="text-bold">
                            @php
                                $diff = ($row['diff'] ?? 0);
                            @endphp
                            @if($diff < 0)
                                <span class="diff-negative">{{ $diff }}</span>
                            @elseif($diff > 0)
                                <span class="diff-positive">+{{ $diff }}</span>
                            @else
                                <span>0.00</span>
                            @endif
                        </td>
                        <td class="text-bold">
                            @php
                                $gainLoss = ($row['gain_loss'] ?? 0);
                            @endphp
                            @if($gainLoss < 0)
                                <span class="diff-negative">▼ {{ abs($gainLoss) }}</span>
                            @elseif($gainLoss > 0)
                                <span class="diff-positive">▲ {{ $gainLoss }}</span>
                            @else
                                <span>0.00</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        {{-- Footer --}}
        <div class="section-title mt-30 cs-primary_color cs-semi_bold">Payment Methods Comparison</div>


        <div class="print-wide-table">
            <table class="zebra">
                <thead>
                <tr>
{{--                    <th class="sub-title" colspan="16">Payment Methods Comparison</th>--}}
                </tr>
                <tr><th>Payment Method</th><th>System Amount</th><th>Manual Amount</th><th>Difference</th></tr>
                </thead>
                <tbody>
                @foreach($paymentRows as $method => $p)
                    @php
                        // Cash = paymentmethod_id 1, divide by 100
                        $isCash = strtolower($method) === 'cash'; // or you can map by id
                        $systemAmount = $isCash ? $p['system_amount'] / 100 : $p['system_amount'];
                        $manualAmount = $isCash ? $p['manual_amount'] / 100 : $p['manual_amount'];
                        $difference   = $isCash ? ($p['system_amount'] - $p['manual_amount']) / 100 : ($p['system_amount'] - $p['manual_amount']);
                    @endphp
                    <tr>
                        <td>{{ $method }}</td>
                        <td class="text-right">{{ number_format($systemAmount, 2) }}</td>
                        <td class="text-right">{{ number_format($manualAmount, 2) }}</td>
                        <td class="text-right">{{ number_format($difference, 2) }}</td>
                    </tr>
{{--                    <tr>--}}
{{--                        <td>{{ $method }}</td>--}}
{{--                        <td>{{ $p['system_amount']/100 }}</td>--}}
{{--                        <td>{{ $p['manual_amount']}}</td>--}}
{{--                        <td>{{ $p['difference'] }}</td>--}}
{{--                    </tr>--}}
                @endforeach
                </tbody>
            </table>
        </div>

    </div>


    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const stockCache = {};

            function fetchStock(el) {
                if (!el) return Promise.resolve(0);

                const tankId = el.dataset.tank;
                const mm = parseFloat(el.dataset.mm) || 0;
                const cacheKey = tankId + '_' + mm;

                if (stockCache[cacheKey] !== undefined) {
                    return Promise.resolve(stockCache[cacheKey]);
                }

                return axios.post('/api/tanks/convert-mm-to-totalizer', {
                    tank_id: tankId,
                    millimeter_value: mm
                }).then(res => {
                    const value = res.data.status === 'success'
                        ? parseFloat(res.data.totalizer_value)
                        : 0;

                    stockCache[cacheKey] = value;
                    return value;
                }).catch(() => {
                    stockCache[cacheKey] = 0;
                    return 0;
                });
            }

            document.querySelectorAll('tbody tr').forEach(async row => {
                const openingEl = row.querySelector('.opening-stock');
                const closingEl = row.querySelector('.closing-stock');

                const openingStock = await fetchStock(openingEl);
                const closingStock = await fetchStock(closingEl);

                if (openingEl) {
                    openingEl.innerText = openingStock.toFixed(2);
                    openingEl.classList.add('text-bold');
                }
                if (closingEl) {
                    closingEl.innerText = closingStock.toFixed(2);
                    closingEl.classList.add('text-bold');
                }
            });

            // Print functionality
            document.querySelector('.btn-print').addEventListener('click', function() {
                window.print();
            });

            // PDF save functionality
            window.saveAsPDF = function() {
                window.print();
            };
        });
    </script>
@endsection
