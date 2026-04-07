<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://db.onlinewebfonts.com/c/a0f33b8a3febb69aa498dba7d9e8cab2?family=Old        .violations-table th,
        .violations-table td {
            border: 1px solid #333;
            padding: 12px 4px;
            text-align: left;
            vertical-align: middle;
        }h+Text+MT+Std" rel="stylesheet">
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
            border: 2px solid #e74c3c;
            border-radius: 8px;
            padding: 20px;
            margin: 30px 0;
        }

        .summary-title {
            font-size: 14px;
            font-weight: bold;
            color: #e74c3c;
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
            color: #e74c3c;
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
            padding: 8px 6px;
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
            background-color: #87CEEB; /* Light blue for closed cases */
            color: #000;
        }

        .status-pending {
            background-color: #90EE90; /* Light green for pending cases */
            color: #000;
        }

        .status-proceedings {
            background-color: #90EE90; /* Light green for proceedings */
            color: #000;
        }

        .status-forwarded {
            background-color: #90EE90; /* Light green for forwarded */
            color: #000;
        }

        /* Additional row colors for variety */
        .violations-table tbody tr:nth-child(odd) {
            background-color: #90EE90; /* Light green for odd rows */
        }

        .violations-table tbody tr:nth-child(even) {
            background-color: #87CEEB; /* Light blue for even rows */
        }

        /* Override for specific status colors */
        .violations-table tbody tr.status-resolved {
            background-color: #87CEEB !important; /* Light blue for closed cases */
        }

        .violations-table tbody tr.status-pending,
        .violations-table tbody tr.status-proceedings,
        .violations-table tbody tr.status-forwarded {
            background-color: #90EE90 !important; /* Light green for pending cases */
        }

        /* Yellow highlight for specific cases */
        .violations-table tbody tr.highlight-yellow {
            background-color: #ffff00 !important; /* Yellow */
        }

        /* Pink highlight for specific cases */
        .violations-table tbody tr.highlight-pink {
            background-color: #FFB6C1 !important; /* Light pink */
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
            <div class="report-title">ACADEMIC YEAR {{ $academic_year }}</div>
            <div class="report-title" style="font-size: 16px; margin: 10px 0; text-decoration: underline;">LIST OF VIOLATORS</div>
            <div class="report-title" style="font-size: 14px; margin: 5px 0;">(Major Offense)</div>
            @if(isset($time_period_info) && $time_period !== 'all')
                <div class="report-info" style="margin: 10px 0;">
                    Time Period: {{ $time_period_info['period'] }}
                </div>
            @endif

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
                <th style="width: 8%;">CASE NO.</th>
                <th style="width: 25%;">NAME/S</th>
                <th style="width: 12%;">COURSE</th>
                <th style="width: 15%;">VIOLATION/S or ACCUSATION</th>
                <th style="width: 12%;">REMARKS</th>
                <th style="width: 18%;">DATE SUBMITTED/ FILED (YYYY-MM-DD)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($violations as $index => $violation)
            @php
                // Determine row color based on status
                $rowClass = '';
                $statusText = '';
                if($violation->status == 2) {
                    $rowClass = 'status-resolved';
                    $statusText = 'CLOSED';
                } elseif($violation->status == 1.5) {
                    $rowClass = 'status-forwarded';
                    $statusText = 'PENDING - WITHDRAWN';
                } elseif($violation->status == 1) {
                    $rowClass = 'status-proceedings';
                    $statusText = 'PENDING';
                } else {
                    $rowClass = 'status-pending';
                    $statusText = 'PENDING';
                }
            @endphp
            <tr class="{{ $rowClass }}">
                <td style="text-align: center; font-weight: bold;">{{ $index + 1 }}</td>
                <td style="font-weight: bold;">{{ ucwords(strtolower($violation->first_name)) }} {{ ucwords(strtolower($violation->last_name)) }}</td>
                <td style="text-align: center; font-weight: bold;">{{ $violation->course ?? 'N/A' }}</td>
                <td style="text-align: center;">{{ $violation->violation }}</td>
                <td style="text-align: center; font-weight: bold;">{{ $statusText }}</td>
                <td style="text-align: center;">{{ $violation->created_at->format('Y-m-d') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Overall Report Section -->
    @php
        // Calculate department statistics
        $departmentStats = [];
        $totalCases = 0;
        $totalClosed = 0;
        $totalPending = 0;
        $totalStudents = 0;

        // Group violations by department
        $violationsByDept = $violations->groupBy('department');

        foreach($violationsByDept as $dept => $deptViolations) {
            $cases = $deptViolations->count();
            $closed = $deptViolations->where('status', 2)->count();
            $pending = $cases - $closed;

            $departmentStats[] = [
                'department' => $dept,
                'cases' => $cases,
                'closed' => $closed,
                'pending' => $pending,
                'students' => $deptViolations->unique('student_id')->count()
            ];

            $totalCases += $cases;
            $totalClosed += $closed;
            $totalPending += $pending;
            $totalStudents += $deptViolations->unique('student_id')->count();
        }

        // Sample population data (this should come from actual student enrollment data)
        $populationData = [
            'SITE' => 610,
            'SBAHM' => 707,
            'SNAHS' => 2641,
            'SASTE' => 390,
            'SOM' => 200,
            'GRADSCH' => 150
        ];

        $totalPopulation = array_sum($populationData);
    @endphp

    <div style="margin-top: 30px;">
        <h3 style="text-align: center; font-weight: bold; margin-bottom: 15px;">OVERALL REPORT</h3>

        <table class="violations-table">
            <thead>
                <tr>
                    <th style="width: 15%;">DEPARTMENT</th>
                    <th style="width: 12%;">NUMBER OF CASES</th>
                    <th style="width: 12%;">NUMBER OF CLOSED CASES</th>
                    <th style="width: 12%;">NUMBER OF PENDING CASES</th>
                    <th style="width: 12%;">NUMBER OF STUDENTS</th>
                    <th style="width: 12%;">TOTAL POPULATION</th>
                    <th style="width: 15%;">PERCENTAGE FROM TOTAL POPULATION</th>
                </tr>
            </thead>
            <tbody>
                @foreach($departmentStats as $stat)
                <tr>
                    <td style="text-align: center; font-weight: bold;">{{ $stat['department'] }}</td>
                    <td style="text-align: center;">{{ $stat['cases'] }}</td>
                    <td style="text-align: center;">{{ $stat['closed'] }}</td>
                    <td style="text-align: center;">{{ $stat['pending'] }}</td>
                    <td style="text-align: center;">{{ $stat['students'] }}</td>
                    <td style="text-align: center;">{{ $populationData[$stat['department']] ?? 0 }}</td>
                    <td style="text-align: center;">
                        @php
                            $population = $populationData[$stat['department']] ?? 1;
                            $percentage = $population > 0 ? ($stat['students'] / $population) * 100 : 0;
                        @endphp
                        {{ number_format($percentage, 2) }}%
                    </td>
                </tr>
                @endforeach
                <tr style="background-color: #ffff00; font-weight: bold;">
                    <td style="text-align: center;">TOTAL</td>
                    <td style="text-align: center;">{{ $totalCases }}</td>
                    <td style="text-align: center;">{{ $totalClosed }}</td>
                    <td style="text-align: center;">{{ $totalPending }}</td>
                    <td style="text-align: center;">{{ $totalStudents }}</td>
                    <td style="text-align: center;">{{ $totalPopulation }}</td>
                    <td style="text-align: center;">{{ number_format(($totalStudents / $totalPopulation) * 100, 2) }}%</td>
                </tr>
            </tbody>
        </table>
    </div>
        </div>

        <!-- FOOTER -->
        <div class="footer">
            <div class="two-tone-line"></div>
            <div style="text-align: right; margin-top: 20px; margin-bottom: 10px;">
                <div style="font-size: 10px; color: #666; margin-bottom: 5px;">MAKING A DIFFERENCE</div>
                <div style="font-size: 14px; font-weight: bold; color: #00B050;">GLOBALLY</div>
                <div style="width: 30px; height: 30px; border-radius: 50%; background-color: #00B050; display: inline-block; margin-left: 10px; position: relative;">
                    <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); color: white; font-size: 12px; font-weight: bold;">🌍</div>
                </div>
            </div>
            @if(isset($footer))
                <img src="data:image/png;base64,{{ $footer }}" alt="Footer" style="width: 100%; height: auto; margin-top: 10px;" />
            @endif
        </div>
    </div>
</body>
</html>
