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
            font-size: 12pt;
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
            font-family: 'Times New Roman', serif;
            font-size: 12pt;
            line-height: 1.5;
            color: #333;
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
            border: 2px solid #00B050;
            border-radius: 8px;
            padding: 20px;
            margin: 30px 0;
        }

        .summary-title {
            font-size: 14px;
            font-weight: bold;
            color: #00B050;
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
            color: #00B050;
            margin-bottom: 5px;
        }

        .stat-label {
            font-size: 10px;
            color: #666;
            text-transform: uppercase;
        }

        .applications-table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
            font-size: 9pt;
        }

        .applications-table th,
        .applications-table td {
            border: 1px solid #333;
            padding: 6px 4px;
            text-align: left;
            vertical-align: middle;
            line-height: 1.2;
        }

        .applications-table th {
            background-color: white;
            color: black;
            font-weight: bold;
            font-size: 8.5pt;
            text-transform: uppercase;
            text-align: center;
            border: 1px solid #333;
            padding: 5px 3px;
            line-height: 1.15;
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

        .status-approved {
            color: #00B050;
        }

        .status-pending {
            color: #f39c12;
        }

        .status-rejected {
            color: #e74c3c;
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
            <img src="data:image/png;base64,{{ $header }}" alt="University Header" style="width: 100%; height: auto; margin-bottom: 0px; display: block;" />
            <div style="text-align: center; margin: 2px 0;">
                <div style="font-size: 8pt; color: black; margin-bottom: 2px; font-family: 'Times New Roman', Times, serif;">STUDENT AFFAIRS AND ACADEMIC SUPPORT SERVICES</div>
                <div style="font-size: 13pt; font-weight: bold; color: #333; margin-bottom: 5px;">OFFICE OF STUDENT AFFAIRS</div>
            </div>
            <div class="two-tone-line"></div>
        </div>

        <!-- MAIN CONTENT -->
        <div class="content">
            <!-- Report Title Section -->
            <div style="text-align: center; margin: 15px 0 10px 0;">
                <div style="font-size: 14pt; font-weight: bold; margin-bottom: 5px;">APPLICATION OF GOOD MORAL CHARACTER</div>
                <div style="font-size: 12pt; margin-bottom: 5px;">A.Y. {{ $academic_year }}</div>
                <div style="font-size: 13pt; font-weight: bold; text-decoration: underline; margin-bottom: 5px;">{{ strtoupper(date('F Y', strtotime($generated_date))) }}</div>
            </div>

    <!-- Applications Table -->
    <table class="applications-table">
        <thead>
            <tr>
                <th style="width: 12%;">DATE OF<br>APPLICATION</th>
                <th style="width: 22%;">NAME</th>
                <th style="width: 18%;">REASON</th>
                <th style="width: 18%;">COURSE YEAR/<br>COURSE<br>COMPLETED</th>
                <th style="width: 30%;">SEMESTER AND<br>SCHOOL YEAR<br>LAST<br>ATTENDED/<br>DATE OF<br>GRADUATION</th>
            </tr>
        </thead>
        <tbody>
            @foreach($applications as $index => $application)
            @php
                // Get student details for extension
                $studentDetails = \App\Models\RoleAccount::where('student_id', $application->student_id)->first();
                $formattedName = formatNameForCertificate($application->fullname, $studentDetails->extension ?? null);
            @endphp
            <tr>
                <td style="text-align: center;">{{ $application->created_at->format('F j, Y') }}</td>
                <td>{{ $formattedName }}</td>
                <td>
                    @if(is_array($application->reason))
                        {{ implode(', ', $application->reason) }}
                    @elseif(is_array($application->reasons_array))
                        {{ implode(', ', $application->reasons_array) }}
                    @else
                        {{ $application->reason ?? 'N/A' }}
                    @endif
                </td>
                <td style="text-align: center;">{{ $application->course_completed ?? $application->course ?? 'N/A' }}</td>
                <td style="text-align: center;">{{ $application->last_semester_sy ?? 'N/A' }}</td>
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
