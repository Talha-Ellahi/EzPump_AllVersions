<!DOCTYPE html>
<html class="no-js" lang="en">

<head>
    <!-- Meta Tags -->
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="ThemeMarch">
    <!-- Site Title -->
    <title> Shift Summary Report - {{ $settings['name'] }} - {{ now()->format('Y-m-d H:i') }} </title>
    @include('reports.report_css')
</head>

<body>
    <div class="cs-container">
        <div class="cs-invoice cs-style1">
            <div class="cs-invoice_in" id="download_section">


                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
                    <!-- Left Section -->
                    <div style="flex: 1; display: flex; align-items: center;">
                        <div style="width: 150px; height: 100px; align-content: center;">
                            <img src="/assets/images/ezpump logo landscape.png" alt="Logo"
                                style="width: 100%; height: auto; max-height: 100px; object-fit: contain;">
                        </div>
                    </div>

                    <!-- Center Section -->
                    <div style="flex: 1; text-align: center; font-size: 18px;">
                        <span class="cs-primary_color cs-semi_bold">{{ $settings['name'] }}</span>
                        <hr />
                        <span class="cs-primary_color " >{{ $settings['line1'] ?? '' }}</span>
                        <span class="cs-primary_color">{{ $settings['line2'] ?? '' }}</span>

                    </div>

                    <!-- Right Section -->
                    <div style="flex: 1; display: flex; justify-content: flex-end; align-items: center;">
                        <div style="width: 150px; height: 100px; align-content: center;">
                            <img src="/storage/{{ $settings['logo'] }}" alt="Logo"
                                style="width: 100%; height: auto;max-height: 75px; object-fit: contain;">
                        </div>
                    </div>
                </div>
                @if(isset($reportType) && $reportType == 'combined_summary')
                <h3 style="text-align: center">Daily Shift Report</h2>
                @elseif (isset($reportType) && $reportType == 'shift_summary')
                <h3 style="text-align: center">Shift Summary Report</h2>
                @elseif (isset($reportType) && $reportType == 'tank_summary')
                <h4 style="text-align: center">Tank Summary Report {{ \Carbon\Carbon::parse($tankShiftLogs[0]->start_time)->format('d M Y H:i')  }}</h2>

                @endif
                @if (isset($shifts) && count($shifts) > 0)

                <div class="cs-row"
                    style="flex: 1;  display: flex; justify-content: space-between; align-items: center; margin-bottom: 1em; margin-top: 1em;">
                    <div class="cs-col">
                        <span class="cs-semi_bold">Shift Start Date: </span> <span>{{ $shifts[0]->start_date }} </span>
                    </div>
                    <div class="cs-col">
                        <span class="cs-semi_bold">Shift End Date: </span> <span>{{ $shifts[0]->end_date }} </span>
                    </div>
                </div>

                @endif

