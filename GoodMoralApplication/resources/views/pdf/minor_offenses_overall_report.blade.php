<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://db.onlinewebfonts.com/c/a0f33b8a3febb69aa498dba7d9e8cab2?family=Old+English+Text+MT+Std" rel="stylesheet">
    <title>{{ $report_title }} - {{ $generated_date }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Times New Roman', serif;
            line-height: 1.5;
            color: #333;
            font-size: 12px;
            background: white;
            margin: 50px;
            text-align: justify;
        }

        @page {
            size: 8.5in 11in;
            margin: 0.5in;
        }

        .header {
            text-align: center;
            margin-top: -50px;
        }

        .footer {
            text-align: center;
            position: fixed;
            bottom: -5px;
            left: 0;
            right: 0;
            margin: 0;
        }

        .page-break {
            page-break-after: always;
        }

        .two-tone-line {
            width: 1000px;
            height: 10px;
            margin: 10px -100px;
            background-color: white;
            position: relative;
            overflow: hidden;
            margin-bottom: 4px;
        }

        .two-tone-line::before {
            content: "";
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            height: 3px;
            background-color: rgba(255, 255, 0, 255);
        }

        .two-tone-line::after {
            content: "";
            position: absolute;
            left: 0;
            bottom: 0;
            width: 100%;
            height: 3px;
            background-color: rgba(0, 176, 80, 255);
        }

        .content {
            margin-top: 10px;
        }

        .report-title {
            font-size: 18px;
            font-weight: bold;
            text-align: center;
            margin: 30px 0;
            color: #333;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .report-info {
            margin: 20px 0;
            font-size: 11px;
        }

        .report-info table {
            width: 100%;
            border-collapse: collapse;
        }

        .report-info td {
            padding: 4px 0;
            vertical-align: top;
        }

        .report-info .label {
            font-weight: bold;
            width: 150px;
        }

        .main-table {
            width: 100%;
            border-collapse: collapse;
            margin: 30px 0;
            font-size: 11px;
        }

        .main-table th {
            background-color: white;
            color: black;
            padding: 12px 8px;
            text-align: center;
            font-weight: bold;
            border: 2px solid #333;
            font-size: 10px;
            line-height: 1.3;
        }

        .main-table td {
            padding: 10px 8px;
            text-align: center;
            border: 1px solid #ddd;
            vertical-align: middle;
        }

        .main-table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .main-table tbody tr:hover {
            background-color: #f0f8f0;
        }

        .department-cell {
            font-weight: bold;
            color: #00b050;
            text-align: left !important;
            padding-left: 12px !important;
        }

        .number-cell {
            font-weight: 600;
            color: #333;
        }

        .variance-cell {
            font-weight: bold;
        }

        .variance-positive {
            color: #e74c3c;
        }

        .variance-negative {
            color: #27ae60;
        }

        .variance-neutral {
            color: #6c757d;
        }

        .trend-cell {
            font-weight: bold;
        }

        .trend-increase {
            color: #e74c3c;
        }

        .trend-decrease {
            color: #27ae60;
        }

        .trend-stable {
            color: #6c757d;
        }

        .total-row {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%) !important;
            font-weight: bold;
            border-top: 3px solid #00b050 !important;
        }

        .total-row td {
            font-weight: bold;
            color: #333;
        }

        .summary-section {
            margin: 30px 0;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 8px;
            border-left: 5px solid #00b050;
        }

        .summary-title {
            font-size: 14px;
            font-weight: bold;
            color: #00b050;
            margin-bottom: 15px;
        }

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }

        .summary-item {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #dee2e6;
        }

        .summary-label {
            font-weight: 600;
            color: #495057;
        }

        .summary-value {
            font-weight: bold;
            color: #00b050;
        }

        .note-section {
            margin: 20px 0;
            padding: 15px;
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 5px;
            font-size: 10px;
            color: #856404;
        }

        .note-title {
            font-weight: bold;
            margin-bottom: 8px;
        }
    </style>
</head>
<body>
    @php
        $header = base64_encode(file_get_contents(public_path('images/header.png')));
        $footer = base64_encode(file_get_contents(public_path('images/footer.png')));
    @endphp

    <div class="container">
        <!-- HEADER -->
        <div class="header">
            <img src="data:image/png;base64,{{ $header }}" alt="University Header" style="width: 100%; height: auto; margin-bottom: 5px; max-width: 600px; display: block; margin-left: auto; margin-right: auto;" />
            <h3 style="margin-top: 5px; margin-bottom: 5px;">OFFICE OF STUDENT AFFAIRS</h3>
            <div class="two-tone-line"></div>
        </div>

        <div class="content">

        <!-- Report Title -->
        <div class="report-title">{{ $report_title }}</div>

        <!-- Report Information -->
        <div class="report-info">
            <table>
                <tr>
                    <td class="label">Generated Date:</td>
                    <td>{{ $generated_date }}</td>
                    <td class="label">Generated Time:</td>
                    <td>{{ $generated_time }}</td>
                </tr>
                <tr>
                    <td class="label">Generated By:</td>
                    <td>{{ $generated_by }}</td>
                    <td class="label">Academic Year:</td>
                    <td>{{ $academic_year }}</td>
                </tr>
            </table>
        </div>

        <!-- Main Report Table -->
        <table class="main-table">
            <thead>
                <tr>
                    <th style="width: 20%;">DEPARTMENT</th>
                    <th style="width: 20%;">NUMBER OF VIOLATORS<br><small>(A.Y. 2023-2024)</small></th>
                    <th style="width: 20%;">NUMBER OF VIOLATORS<br><small>(as of June 2025)</small></th>
                    <th style="width: 20%;">VARIANCE</th>
                    <th style="width: 20%;">TREND</th>
                </tr>
            </thead>
            <tbody>
                @foreach($departments_data as $dept => $data)
                <tr>
                    <td class="department-cell">{{ $dept }}</td>
                    <td class="number-cell">{{ number_format($data['violators_2023_2024']) }}</td>
                    <td class="number-cell">{{ number_format($data['violators_june_2025']) }}</td>
                    <td class="variance-cell {{ $data['variance'] > 0 ? 'variance-positive' : ($data['variance'] < 0 ? 'variance-negative' : 'variance-neutral') }}">
                        {{ $data['variance'] > 0 ? '+' : '' }}{{ $data['variance'] }}
                        @if($data['variance'] != 0)
                            <br><small>({{ $data['variance_percentage'] > 0 ? '+' : '' }}{{ $data['variance_percentage'] }}%)</small>
                        @endif
                    </td>
                    <td class="trend-cell {{ $data['trend'] == 'increase' ? 'trend-increase' : ($data['trend'] == 'decrease' ? 'trend-decrease' : 'trend-stable') }}">
                        @if($data['trend'] == 'increase')
                            ↗ INCREASING
                        @elseif($data['trend'] == 'decrease')
                            ↘ DECREASING
                        @else
                            → STABLE
                        @endif
                    </td>
                </tr>
                @endforeach
                <!-- Total Row -->
                <tr class="total-row">
                    <td class="department-cell">TOTAL</td>
                    <td class="number-cell">{{ number_format($totals['violators_2023_2024']) }}</td>
                    <td class="number-cell">{{ number_format($totals['violators_june_2025']) }}</td>
                    <td class="variance-cell {{ $totals['total_variance'] > 0 ? 'variance-positive' : ($totals['total_variance'] < 0 ? 'variance-negative' : 'variance-neutral') }}">
                        {{ $totals['total_variance'] > 0 ? '+' : '' }}{{ $totals['total_variance'] }}
                    </td>
                    <td class="trend-cell {{ $totals['total_variance'] > 0 ? 'trend-increase' : ($totals['total_variance'] < 0 ? 'trend-decrease' : 'trend-stable') }}">
                        @if($totals['total_variance'] > 0)
                            ↗ INCREASING
                        @elseif($totals['total_variance'] < 0)
                            ↘ DECREASING
                        @else
                            → STABLE
                        @endif
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- Summary Section -->
        <div class="summary-section">
            <div class="summary-title">SUMMARY STATISTICS</div>
            <div class="summary-grid">
                <div class="summary-item">
                    <span class="summary-label">Total Population:</span>
                    <span class="summary-value">{{ number_format($totals['total_population']) }}</span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">Total Violators (A.Y. 2023-2024):</span>
                    <span class="summary-value">{{ number_format($totals['violators_2023_2024']) }}</span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">Total Violators (June 2025):</span>
                    <span class="summary-value">{{ number_format($totals['violators_june_2025']) }}</span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">Overall Variance:</span>
                    <span class="summary-value {{ $totals['total_variance'] > 0 ? 'variance-positive' : ($totals['total_variance'] < 0 ? 'variance-negative' : 'variance-neutral') }}">
                        {{ $totals['total_variance'] > 0 ? '+' : '' }}{{ $totals['total_variance'] }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Note Section -->
        <div class="note-section">
            <div class="note-title">NOTE:</div>
            <p>This report shows the comparison of minor offense violators between Academic Year 2023-2024 and the combined data as of June 2025. The variance indicates the change in the number of violators, with negative values showing a decrease and positive values showing an increase.</p>
        </div>

        <!-- FOOTER -->
        <div class="footer">
            <div class="two-tone-line"></div>
            <img src="data:image/png;base64,{{ $footer }}" alt="Footer" style="width: 100%; height: auto; margin-top: 10px;" />
        </div>
        </div>
    </div>
</body>
</html>
