<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://db.onlinewebfonts.com/c/a0f33b8a3febb69aa498dba7d9e8cab2?family=Old+English+Text+MT+Std" rel="stylesheet">
    <title>{{ $report_title }} - {{ $academic_year }}</title>
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

        .report-page {
            min-height: 100vh;
            position: relative;
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

        .content {
            margin-top: 10px;
        }



        .logo-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding: 0 50px;
        }

        .logo-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .logo-container img {
            width: 80px;
            height: 80px;
            margin-bottom: 10px;
        }

        .logo-text {
            font-size: 12px;
            font-weight: bold;
            color: #333;
        }

        .spup-text {
            font-family: 'Old English Text MT Std', serif;
            font-size: 16px;
            color: #333;
            font-weight: bold;
        }

        .osa-text {
            font-size: 10px;
            color: #666;
            margin-top: 2px;
        }

        .university-name {
            font-family: 'Old English Text MT Std', serif;
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 5px;
            color: #333;
        }

        .university-address {
            font-size: 14px;
            margin-bottom: 3px;
            color: #333;
            font-weight: normal;
        }

        .university-contact {
            font-size: 12px;
            margin-bottom: 2px;
            color: #333;
        }

        .university-website {
            font-size: 12px;
            margin-bottom: 15px;
            color: #0066cc;
            text-decoration: none;
        }

        .office-title {
            font-size: 18px;
            font-weight: bold;
            color: #333;
            margin: 20px 0;
            text-transform: uppercase;
            letter-spacing: 2px;
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

        .report-title {
            font-size: 20px;
            font-weight: bold;
            color: #333;
            margin: 30px 0 20px 0;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .report-info {
            font-size: 11px;
            color: #666;
            margin-bottom: 30px;
        }

        .summary-box {
            background-color: #f8f9fa;
            border: 2px solid #f39c12;
            border-radius: 8px;
            padding: 20px;
            margin: 30px 0;
        }

        .summary-title {
            font-size: 14px;
            font-weight: bold;
            color: #f39c12;
            margin-bottom: 15px;
            text-align: center;
        }

        .summary-stats {
            display: table;
            width: 100%;
        }

        .summary-row {
            display: table-row;
        }

        .summary-cell {
            display: table-cell;
            width: 20%;
            padding: 10px;
            text-align: center;
            vertical-align: middle;
        }

        .stat-number {
            font-size: 20px;
            font-weight: bold;
            color: #f39c12;
            margin-bottom: 5px;
        }

        .stat-label {
            font-size: 10px;
            color: #666;
            text-transform: uppercase;
        }

        .violations-table {
            width: 100%;
            border-collapse: collapse;
            margin: 30px 0;
            font-size: 12px;
        }

        .violations-table th,
        .violations-table td {
            border: 1px solid #333;
            padding: 12px 6px;
            text-align: left;
            vertical-align: middle;
        }

        .violations-table th {
            background-color: white;
            color: black;
            font-weight: bold;
            font-size: 9px;
            text-transform: uppercase;
            text-align: center;
            border: 2px solid #333;
        }



        .department-cell {
            text-align: center;
            font-weight: bold;
            color: #333;
        }

        .status-cell {
            text-align: center;
            font-weight: bold;
        }

        .status-resolved {
            color: #00B050;
        }

        .status-pending {
            color: #f39c12;
        }

        .status-approved {
            color: #3498db;
        }



        .page-number {
            position: fixed;
            bottom: 10px;
            right: 20px;
            font-size: 10px;
            color: #666;
        }
    </style>
</head>
<body>
    @php
        $header = base64_encode(file_get_contents(public_path('images/header.png')));
        $footer = base64_encode(file_get_contents(public_path('images/footer.png')));
    @endphp

    <div class="report-page">
        <!-- HEADER -->
        <div class="header">
            <img src="data:image/png;base64,{{ $header }}" alt="University Header" style="width: 100%; height: auto; margin-bottom: 5px; max-width: 600px; display: block; margin-left: auto; margin-right: auto;" />
            <h3 style="margin-top: 5px; margin-bottom: 5px;">OFFICE OF STUDENT AFFAIRS</h3>
            <div class="two-tone-line"></div>
        </div>

        <!-- MAIN CONTENT -->
        <div class="content">
            <!-- Report Title and Info -->
            <div class="report-title">{{ $report_title }}</div>
            @if(isset($time_period_info) && $time_period !== 'all')
                <div class="report-title" style="font-size: 16px; margin: 10px 0;">{{ $time_period_info['title'] }}</div>
            @endif
            <div class="report-info">
                @if(isset($time_period_info) && $time_period !== 'all')
                    Time Period: {{ $time_period_info['period'] }}<br>
                @else
                    Academic Year: {{ $academic_year }}<br>
                @endif
                Generated on: {{ $generated_date }} at {{ $generated_time }}<br>
                Generated by: {{ $generated_by }}
            </div>

    <!-- Summary Statistics -->
    <div class="summary-box">
        <div class="summary-title">SUMMARY STATISTICS</div>
        <div class="summary-stats">
            <div class="summary-row">
                <div class="summary-cell">
                    <div class="stat-number">{{ $total_count }}</div>
                    <div class="stat-label">Total Violations</div>
                </div>
                <div class="summary-cell">
                    <div class="stat-number">{{ $unique_violators }}</div>
                    <div class="stat-label">Unique Violators</div>
                </div>
                @foreach($departments_summary as $dept => $count)
                <div class="summary-cell">
                    <div class="stat-number">{{ $count }}</div>
                    <div class="stat-label">{{ $dept }}</div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Violations Table -->
    <table class="violations-table">
        <thead>
            <tr>
                <th style="width: 6%;">NO.</th>
                <th style="width: 12%;">STUDENT ID</th>
                <th style="width: 20%;">FULL NAME</th>
                <th style="width: 10%;">DEPARTMENT</th>
                <th style="width: 12%;">COURSE</th>
                <th style="width: 25%;">VIOLATION</th>
                <th style="width: 10%;">DATE</th>
                <th style="width: 5%;">STATUS</th>
            </tr>
        </thead>
        <tbody>
            @foreach($violations as $index => $violation)
            <tr>
                <td style="text-align: center;">{{ $index + 1 }}</td>
                <td style="text-align: center;">{{ $violation->student_id }}</td>
                <td>{{ ucwords(strtolower($violation->first_name)) }} {{ ucwords(strtolower($violation->last_name)) }}</td>
                <td class="department-cell">{{ $violation->department }}</td>
                <td>{{ $violation->course ?? 'N/A' }}</td>
                <td>{{ $violation->violation }}</td>
                <td style="text-align: center;">{{ $violation->created_at->format('F j, Y') }}</td>
                <td class="status-cell 
                    @if($violation->status == 2) status-resolved
                    @elseif($violation->status == 1) status-approved
                    @else status-pending
                    @endif">
                    @if($violation->status == 2)
                        RESOLVED
                    @elseif($violation->status == 1)
                        APPROVED
                    @else
                        PENDING
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
        </div>

        <!-- FOOTER -->
        <div class="footer">
            <div class="two-tone-line"></div>
            <img src="data:image/png;base64,{{ $footer }}" alt="Footer" style="width: 100%; height: auto; margin-top: 10px;" />
        </div>
    </div>
</body>
</html>
