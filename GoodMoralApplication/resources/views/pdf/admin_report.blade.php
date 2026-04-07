<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin System Report</title>
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

        .content {
            margin-top: 10px;
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

        .logo {
            width: 80px;
            height: auto;
            margin-bottom: 10px;
        }

        .university-name {
            font-size: 18px;
            font-weight: bold;
            color: #00B050;
            margin-bottom: 5px;
        }

        .report-title {
            font-size: 16px;
            font-weight: bold;
            color: #333;
            margin-bottom: 10px;
        }

        .report-info {
            font-size: 10px;
            color: #666;
        }

        .section {
            margin-bottom: 25px;
            page-break-inside: avoid;
        }

        .section-title {
            font-size: 14px;
            font-weight: bold;
            color: #00B050;
            margin-bottom: 15px;
            border-bottom: 2px solid #FFFF00;
            padding-bottom: 5px;
        }

        .stats-grid {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }

        .stats-row {
            display: table-row;
        }

        .stats-cell {
            display: table-cell;
            width: 25%;
            padding: 10px;
            border: 1px solid #ddd;
            text-align: center;
            vertical-align: middle;
        }

        .stats-cell.header {
            background-color: white;
            color: black;
            font-weight: bold;
            border: 2px solid #ddd;
        }

        .stat-number {
            font-size: 24px;
            font-weight: bold;
            color: #00B050;
            margin-bottom: 5px;
        }

        .stat-label {
            font-size: 10px;
            color: #666;
        }

        .violations-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 12px;
        }

        .violations-table th,
        .violations-table td {
            border: 1px solid #ddd;
            padding: 12px 8px;
            text-align: center;
        }

        .violations-table th {
            background-color: white;
            color: black;
            font-weight: bold;
            border: 2px solid #ddd;
        }



        .summary-box {
            background-color: #f8f9fa;
            border: 2px solid #00B050;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
        }

        .summary-title {
            font-size: 12px;
            font-weight: bold;
            color: #00B050;
            margin-bottom: 10px;
        }

        .summary-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
            font-size: 11px;
        }

        .percentage {
            color: #e74c3c;
            font-weight: bold;
        }



        .page-break {
            page-break-before: always;
        }

        .two-column {
            display: table;
            width: 100%;
        }

        .column {
            display: table-cell;
            width: 50%;
            padding: 0 10px;
            vertical-align: top;
        }
    </style>
</head>
<body>
    @php
        $header = base64_encode(file_get_contents(public_path('images/header.png')));
        $footer = base64_encode(file_get_contents(public_path('images/footer.png')));
    @endphp

    <!-- PAGE HEADER (appears on every page) -->
    <div class="page-header">
        <img src="data:image/png;base64,{{ $header }}" alt="University Header" style="width: 100%; height: auto; margin-bottom: 5px; max-width: 600px; display: block; margin-left: auto; margin-right: auto;" />
        <h3 style="margin-top: 5px; margin-bottom: 5px;">OFFICE OF STUDENT AFFAIRS</h3>
        <div class="two-tone-line"></div>
    </div>

    <!-- PAGE FOOTER (appears on every page) -->
    <div class="page-footer">
        <div class="two-tone-line"></div>
        <img src="data:image/png;base64,{{ $footer }}" alt="Footer" style="width: 100%; height: auto; margin-top: 10px;" />
    </div>

    <!-- MAIN HEADER (first page only) -->
    <div class="header">
        <img src="data:image/png;base64,{{ $header }}" alt="University Header" style="width: 100%; height: auto; margin-bottom: 5px; max-width: 600px; display: block; margin-left: auto; margin-right: auto;" />
        <h3 style="margin-top: 5px; margin-bottom: 5px;">OFFICE OF STUDENT AFFAIRS</h3>
        <div class="two-tone-line"></div>
    </div>

    <!-- MAIN CONTENT -->
    <div class="content">
        <!-- Report Title -->
        <div style="text-align: center; margin: 30px 0;">
            <div style="font-size: 18px; font-weight: bold; color: #333; margin-bottom: 10px;">
                ADMINISTRATIVE SYSTEM REPORT
            </div>
            <div style="font-size: 11px; color: #666;">
                Generated on: {{ $generated_date }} at {{ $generated_time }}<br>
                Generated by: {{ $generated_by }}
            </div>
        </div>

        <!-- Applications Overview -->
        <div class="section">
        <div class="section-title">Good Moral Applications by Department</div>
        <div class="stats-grid">
            <div class="stats-row">
                <div class="stats-cell header">SITE</div>
                <div class="stats-cell header">SASTE</div>
                <div class="stats-cell header">SBAHM</div>
                <div class="stats-cell header">SNAHS</div>
            </div>
            <div class="stats-row">
                <div class="stats-cell">
                    <div class="stat-number">{{ $applications['site'] }}</div>
                    <div class="stat-label">Applications</div>
                </div>
                <div class="stats-cell">
                    <div class="stat-number">{{ $applications['saste'] }}</div>
                    <div class="stat-label">Applications</div>
                </div>
                <div class="stats-cell">
                    <div class="stat-number">{{ $applications['sbahm'] }}</div>
                    <div class="stat-label">Applications</div>
                </div>
                <div class="stats-cell">
                    <div class="stat-number">{{ $applications['snahs'] }}</div>
                    <div class="stat-label">Applications</div>
                </div>
            </div>
        </div>
        
        <div class="summary-box">
            <div class="summary-title">Application Summary</div>
            <div class="summary-item">
                <span>Total Applications:</span>
                <span><strong>{{ $applications['total'] }}</strong></span>
            </div>
        </div>
    </div>

    <!-- Violations Overview -->
    <div class="section">
        <div class="section-title">Violations Overview</div>
        
        <div class="two-column">
            <div class="column">
                <div class="summary-box">
                    <div class="summary-title">Minor Violations</div>
                    <div class="summary-item">
                        <span>Pending:</span>
                        <span class="percentage">{{ $violations['minor_pending'] }} ({{ $violations['minor_pending_percent'] }}%)</span>
                    </div>
                    <div class="summary-item">
                        <span>Resolved:</span>
                        <span>{{ $violations['minor_resolved'] }}</span>
                    </div>
                    <div class="summary-item">
                        <span><strong>Total:</strong></span>
                        <span><strong>{{ $violations['minor_total'] }}</strong></span>
                    </div>
                </div>
            </div>
            
            <div class="column">
                <div class="summary-box">
                    <div class="summary-title">Major Violations</div>
                    <div class="summary-item">
                        <span>Pending:</span>
                        <span class="percentage">{{ $violations['major_pending'] }} ({{ $violations['major_pending_percent'] }}%)</span>
                    </div>
                    <div class="summary-item">
                        <span>Resolved:</span>
                        <span>{{ $violations['major_resolved'] }}</span>
                    </div>
                    <div class="summary-item">
                        <span><strong>Total:</strong></span>
                        <span><strong>{{ $violations['major_total'] }}</strong></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Department Violations Breakdown -->
    <div class="section">
        <div class="section-title">Violations by Department</div>
        <table class="violations-table">
            <thead>
                <tr>
                    <th>Department</th>
                    <th>Major Violations</th>
                    <th>Minor Violations</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($department_violations as $dept => $counts)
                <tr>
                    <td><strong>{{ $dept }}</strong></td>
                    <td>{{ $counts['major'] }}</td>
                    <td>{{ $counts['minor'] }}</td>
                    <td><strong>{{ $counts['major'] + $counts['minor'] }}</strong></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- PSG Officer Applications -->
    <div class="section">
        <div class="section-title">PSG Officer Applications</div>
        <div class="summary-box">
            <div class="summary-title">Application Status</div>
            <div class="summary-item">
                <span>Pending Applications:</span>
                <span class="percentage">{{ $psg_applications['pending'] }}</span>
            </div>
            <div class="summary-item">
                <span>Approved Applications:</span>
                <span>{{ $psg_applications['approved'] }}</span>
            </div>
            <div class="summary-item">
                <span>Rejected Applications:</span>
                <span>{{ $psg_applications['rejected'] }}</span>
            </div>
            <div class="summary-item">
                <span><strong>Total Applications:</strong></span>
                <span><strong>{{ $psg_applications['pending'] + $psg_applications['approved'] + $psg_applications['rejected'] }}</strong></span>
            </div>
        </div>
    </div>
    </div>

    <!-- FOOTER -->
    <div class="footer">
        <div class="two-tone-line"></div>
        <img src="data:image/png;base64,{{ $footer }}" alt="Footer" style="width: 100%; height: auto; margin-top: 10px;" />
    </div>
</body>
</html>
