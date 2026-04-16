<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Good Moral Applicants Report</title>
    <style>
        @page {
            size: 8.5in 11in;
            margin: 0.5in;
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

        /* Report Title Section */
        .report-title-section {
            text-align: center;
            margin: 20px 0 30px 0;
        }

        .report-title {
            font-size: 18px;
            font-weight: bold;
            color: #333;
            margin-bottom: 10px;
        }

        .report-subtitle {
            font-size: 16px;
            color: #333;
            margin-bottom: 10px;
        }

        .report-date {
            font-size: 16px;
            font-weight: bold;
            color: #333;
        }

        /* Table Styles */
        .applications-table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
            font-size: 9pt;
        }

        .applications-table thead {
            display: table-header-group;
        }

        .applications-table tbody {
            display: table-row-group;
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
            padding: 5px 3px;
            line-height: 1.15;
        }

        .text-center {
            text-align: center;
        }

        /* Page break control - temporarily removed */
        /* .applications-table tr {
            page-break-inside: avoid;
        }

        .applications-table thead {
            page-break-after: avoid;
        } */
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
                    $studentDetails = $application->student;
                    $formattedName = formatNameForCertificate($application->fullname, $studentDetails->extension ?? null);
                    if ($studentDetails && $studentDetails->course) {
                        $courseDisplay = $studentDetails->account_type === 'alumni'
                            ? $studentDetails->course . ' - Completed'
                            : ($studentDetails->year_level ? $studentDetails->course . ' - ' . $studentDetails->year_level : $studentDetails->course);
                    } else {
                        $courseDisplay = $application->course_completed ?? 'N/A';
                    }
                @endphp
                <tr>
                    <td style="text-align: center;">{{ $application->created_at->format('M j, Y') }}</td>
                    <td>{{ $formattedName }}</td>
                    <td>{{ is_array($application->reason) ? implode(', ', $application->reason) : ($application->reason ?? 'N/A') }}</td>
                    <td style="text-align: center;">{{ $courseDisplay }}</td>
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
