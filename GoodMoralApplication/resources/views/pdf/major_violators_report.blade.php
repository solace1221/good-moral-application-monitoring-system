<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $report_title }} - {{ $academic_year }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        @page {
            size: letter portrait;
            margin: 0;
        }

        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 9px;
            line-height: 1.3;
            color: #000;
            margin: 0;
            padding: 0;
        }

        .header-img, .footer-img {
            width: 100%;
            height: auto;
            display: block;
        }
        .content-area {
            padding: 0 12mm;
        }

        .report-titles {
            text-align: center;
            margin: 6px 0 4px 0;
        }
        .report-titles .ay {
            font-size: 11px;
            font-weight: bold;
        }
        .report-titles .list {
            font-size: 10px;
            font-weight: bold;
            text-decoration: underline;
            margin: 2px 0;
        }
        .report-titles .offense {
            font-size: 9px;
            font-weight: bold;
        }
        .report-titles .period {
            font-size: 8px;
            color: #555;
            margin-top: 2px;
        }

        /* Violations Table */
        table.violations {
            width: 100%;
            border-collapse: collapse;
            margin: 4px 0;
            font-size: 8px;
        }
        table.violations th,
        table.violations td {
            border: 1px solid #000;
            padding: 3px 4px;
            vertical-align: top;
        }
        table.violations th {
            background-color: #fff;
            font-weight: bold;
            font-size: 7px;
            text-align: center;
            text-transform: uppercase;
        }

        /* Status-based row colors */
        tr.row-closed { background-color: #87CEEB; }
        tr.row-pending { background-color: #90EE90; }
        tr.row-total { background-color: #FFFF00; font-weight: bold; }

        /* Overall Report Table */
        table.overall {
            width: 100%;
            border-collapse: collapse;
            margin: 4px 0;
            font-size: 8px;
        }
        table.overall th,
        table.overall td {
            border: 1px solid #000;
            padding: 3px 4px;
            text-align: center;
            vertical-align: middle;
        }
        table.overall th {
            background-color: #fff;
            font-weight: bold;
            font-size: 7px;
            text-transform: uppercase;
        }

        .overall-title {
            text-align: center;
            font-size: 10px;
            font-weight: bold;
            margin: 8px 0 4px 0;
        }

        .footer-section {
            margin-top: 6px;
        }
    </style>
</head>
<body>
    @php
        $headerImg = base64_encode(file_get_contents(public_path('reports/header.png')));
        $footerImg = base64_encode(file_get_contents(public_path('reports/footer.png')));
    @endphp

    {{-- HEADER IMAGE --}}
    <img src="data:image/png;base64,{{ $headerImg }}" class="header-img" />

    <div class="content-area">
    {{-- REPORT TITLES --}}
    <div class="report-titles">
        <div class="ay">ACADEMIC YEAR {{ $academic_year }}</div>
        <div class="list">LIST OF VIOLATORS</div>
        <div class="offense">(Major Offense)</div>
        @if(isset($time_period_info) && $time_period !== 'all')
            <div class="period">{{ $time_period_info['period'] ?? $time_period_info['description'] ?? '' }}</div>
        @endif
    </div>

    {{-- VIOLATIONS TABLE --}}
    <table class="violations">
        <thead>
            <tr>
                <th style="width: 7%;">CASE NO.</th>
                <th style="width: 26%;">NAME/S</th>
                <th style="width: 10%;">COURSE</th>
                <th style="width: 25%;">VIOLATION/S OR ACCUSATION</th>
                <th style="width: 14%;">REMARKS</th>
                <th style="width: 18%;">DATE SUBMITTED/<br>FILED (YYYY-MM-DD)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($cases as $index => $case)
            <tr class="{{ $case->is_closed ? 'row-closed' : 'row-pending' }}">
                <td style="text-align: center; font-weight: bold;">{{ $index + 1 }}</td>
                <td>{{ $case->names }}</td>
                <td style="text-align: center;">{{ $case->courses }}</td>
                <td>{{ $case->violation }}</td>
                <td style="text-align: center; font-weight: bold;">{{ $case->status_text }}</td>
                <td style="text-align: center;">{{ $case->date_filed->format('Y-m-d') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- OVERALL REPORT --}}
    @php
        $departmentStats = [];
        $totalCases = 0;
        $totalClosed = 0;
        $totalPending = 0;
        $totalStudents = 0;
        $totalPopulationSum = 0;

        $casesByDept = $cases->groupBy('department');

        foreach($casesByDept as $dept => $deptCases) {
            $caseCount = $deptCases->count();
            $closed = $deptCases->where('is_closed', true)->count();
            $pending = $caseCount - $closed;
            $uniqueStudents = $deptCases->flatMap(fn($c) => $c->student_ids)->unique()->count();
            $pop = $populationData[$dept] ?? 0;

            $departmentStats[] = [
                'department' => $dept,
                'cases' => $caseCount,
                'closed' => $closed,
                'pending' => $pending,
                'students' => $uniqueStudents,
                'population' => $pop,
                'percentage' => $pop > 0 ? ($uniqueStudents / $pop) * 100 : 0,
            ];

            $totalCases += $caseCount;
            $totalClosed += $closed;
            $totalPending += $pending;
            $totalStudents += $uniqueStudents;
            $totalPopulationSum += $pop;
        }
    @endphp

    <div class="overall-title">OVERALL REPORT</div>

    <table class="overall">
        <thead>
            <tr>
                <th>DEPARTMENT</th>
                <th>NUMBER OF CASES</th>
                <th>NUMBER OF CLOSED CASES</th>
                <th>NUMBER OF PENDING CASES</th>
                <th>NUMBER OF STUDENTS</th>
                <th>TOTAL POPULATION</th>
                <th>PERCENTAGE FROM TOTAL POPULATION</th>
            </tr>
        </thead>
        <tbody>
            @foreach($departmentStats as $stat)
            <tr>
                <td style="font-weight: bold;">{{ $stat['department'] }}</td>
                <td>{{ $stat['cases'] }}</td>
                <td>{{ $stat['closed'] }}</td>
                <td>{{ $stat['pending'] }}</td>
                <td>{{ $stat['students'] }}</td>
                <td>{{ $stat['population'] }}</td>
                <td>{{ number_format($stat['percentage'], 2) }}%</td>
            </tr>
            @endforeach
            <tr class="row-total">
                <td>TOTAL</td>
                <td>{{ $totalCases }}</td>
                <td>{{ $totalClosed }}</td>
                <td>{{ $totalPending }}</td>
                <td>{{ $totalStudents }}</td>
                <td>{{ $totalPopulationSum }}</td>
                <td>{{ $totalPopulationSum > 0 ? number_format(($totalStudents / $totalPopulationSum) * 100, 2) : '0.00' }}%</td>
            </tr>
        </tbody>
    </table>
    </div>{{-- end content-area --}}

    {{-- FOOTER IMAGE --}}
    <img src="data:image/png;base64,{{ $footerImg }}" class="footer-img" />
</body>
</html>
