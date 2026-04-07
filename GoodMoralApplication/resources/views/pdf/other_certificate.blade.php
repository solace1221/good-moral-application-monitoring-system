<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <link href="https://fonts.googleapis.com/css2?family=UnifrakturCook:wght@700&display=swap" rel="stylesheet" />
  <title>Certificate</title>
  <style>
    body {
      font-family: "Times New Roman", Times, serif;
      margin: 50px;
      line-height: 1.5;
      text-align: justify;
    }

    .center {
      text-align: center;
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

    .page-number {
      position: fixed;
      bottom: 20px;
      right: 50px;
      font-family: Arial, Helvetica, sans-serif;
      font-size: 10px;
      color: #666;
      z-index: 1000;
    }

    .page-break {
      page-break-after: always;
    }

    .certificate-page {
      min-height: 100vh;
      position: relative;
    }

    .header-content {
      display: flex;
      align-items: flex-start;
      gap: 15px;
      max-width: 600px;
      margin: 0 auto;
    }

    .header-content img {
      width: 60px;
      height: auto;
      display: block;
    }

    .university-info {
      text-align: center;
      font-size: 14px;
      line-height: 1.3;
      margin: 0;
      padding: 0;
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

    .signature {
      margin-top: 80px;
      text-align: left;
    }

    .note {
      margin-top: 50px;
      font-family: Arial, Helvetica, sans-serif;
      font-size: 10px;
      
    }

    .footer-logos {
      display: flex;
      justify-content: center;
      align-items: center;
      margin-top: 30px;
      flex-wrap: wrap;
    }

    .footer-logos img {
      height: 40px;
      margin: 0 10px;
    }

    .tagline {
      font-size: 12px;
      color: #666;
      margin-top: 5px;
    }
  </style>
</head>

<body>
  @php
    $header = base64_encode(file_get_contents(public_path('images/header.png')));
    $footer = base64_encode(file_get_contents(public_path('images/footer.png')));
    $logo = base64_encode(file_get_contents(public_path('images/logo/logo.png')));
    $log1 = base64_encode(file_get_contents(public_path('images/logo/log1.png')));
    $log2 = base64_encode(file_get_contents(public_path('images/logo/log2.png')));
    $log3 = base64_encode(file_get_contents(public_path('images/logo/log3.png')));
    $log4 = base64_encode(file_get_contents(public_path('images/logo/log4.png')));
    $log5 = base64_encode(file_get_contents(public_path('images/logo/log5.png')));

    $programs = [
      'BAELS' => 'Bachelor of Arts in English Language Studies',
      'BS Psych' => 'Bachelor of Science in Psychology',
      'BS Bio' => 'Bachelor of Science in Biology',
      'BSSW' => 'Bachelor of Science in Social Work',
      'BSPA' => 'Bachelor of Science in Public Administration',
      'BS Bio MB' => 'Bachelor of Science in Biology Major in Microbiology',
      'BSEd' => 'Bachelor of Secondary Education',
      'BEEd' => 'Bachelor of Elementary Education',
      'BPEd' => 'Bachelor of Physical Education',

      'BSA' => 'Bachelor of Science in Accountancy',
      'BSE' => 'Bachelor of Science in Entrepreneurship',
      'BSBAMM' => 'Bachelor of Science in Business Administration major in Marketing Management',
      'BSBA MFM' => 'Bachelor of Science in Business Administration major in Financial Management',
      'BSBA MOP' => 'Bachelor of Science in Business Administration major in Operations Management',
      'BSMA' => 'Bachelor of Science in Management Accounting',
      'BSHM' => 'Bachelor of Science in Hospitality Management',
      'BSTM' => 'Bachelor of Science in Tourism Management',
      'BSPDMI' => 'Bachelor of Science in Product Design and Marketing Innovation',

      'BSIT' => 'Bachelor of Science in Information Technology',
      'BLIS' => 'Bachelor of Library and Information Science',
      'BSCE' => 'Bachelor of Science in Civil Engineering',
      'BS ENSE' => 'Bachelor of Science in Environmental and Sanitary Engineering',
      'BS CpE' => 'Bachelor of Science in Computer Engineering',

      'BSN' => 'Bachelor of Science in Nursing',
      'BSPh' => 'Bachelor of Science in Pharmacy',
      'BSRT' => 'Bachelor of Science in Radiologic Technology',
      'BSMT' => 'Bachelor of Science in Medical Technology',
      'BSPT' => 'Bachelor of Science in Physical Therapy',
    ];

    // Get the program name from application data
    $programName = $application->course_completed ?? $application->last_course_year_level ?? null;

    // If programName is an abbreviation, expand it; otherwise use as-is
    if ($programName && isset($programs[$programName])) {
      $fullProgramName = $programs[$programName];
    } else {
      $fullProgramName = $programName;
    }

    // Format the student name correctly using global helper function
    $formattedName = formatNameForCertificate($application->fullname, $studentDetails->extension ?? null);

    // Gender-based titles and pronouns
    $gender = $application->gender ?? 'male'; // Default to male if not specified
    $title = $gender === 'female' ? 'MS.' : 'MR.';
    $possessivePronoun = $gender === 'female' ? 'her' : 'his';
    $subjectPronoun = $gender === 'female' ? 'she' : 'he';
    $objectPronoun = $gender === 'female' ? 'her' : 'him';

    // Get number of copies and reasons
    $numberOfCopies = $number_of_copies ?? (int)($application->number_of_copies ?? 1);
    $numberOfCopies = max(1, $numberOfCopies); // Ensure at least 1 copy
    $reasonsArray = $reasons_array ?? $application->reasons_array ?? [$application->reason];
    $totalPages = count($reasonsArray) * $numberOfCopies;
    $currentPage = 0;
  @endphp

  @foreach($reasonsArray as $reasonIndex => $currentReason)
    @for($copyNumber = 1; $copyNumber <= $numberOfCopies; $copyNumber++)
      @php $currentPage++; @endphp
  <div class="certificate-page{{ $currentPage < $totalPages ? ' page-break' : '' }}">

  <!-- HEADER -->
  <div class="header">
    <img src="data:image/png;base64,{{ $header }}" alt="University Header" style="width: 100%; height: auto; margin-bottom: 5px; max-width: 600px; display: block; margin-left: auto; margin-right: auto;" />

    <h3 style="margin-top: 5px; margin-bottom: 5px;">OFFICE OF STUDENT AFFAIRS</h3>
    <div class="two-tone-line"></div>
  </div>

  <!-- MAIN CONTENT -->
  <div class="content">
    <h2 class="center">C E R T I F I C A T I O N</h2>

    <p style="margin-top: 40px;"><strong>TO WHOM IT MAY CONCERN:</strong></p>

    <p style="margin-bottom: 0; line-height: 1.2;">
      This is to certify that <strong>{{ $title }} {{ $formattedName }}</strong> is a graduate of the
      @if($application->department == 'SNAHS')
      <strong>School of Nursing and Allied Health Sciences</strong>
      @elseif($application->department === 'SITE')
      <strong>School of Information Technology and Engineering</strong>
      @elseif($application->department === 'SASTE')
      <strong>School of Arts, Sciences and Teacher Education</strong>
      @elseif($application->department === 'SBAHM')
      <strong>School of Business, Accountancy and Hospitality Management</strong>
      @else
      <strong>[Unknown Department]</strong>
      @endif
      under the program
      <strong>
        @if($fullProgramName)
        {{ $fullProgramName }}
        @else
        [Unknown Program]
        @endif
      </strong>
      @if($application->graduation_date)
      who graduated on <strong>{{ \Carbon\Carbon::parse($application->graduation_date)->format('F j, Y') }}</strong>
      @endif
      for {{ $studentDetails1->last_semester_sy ?? $application->last_semester_sy ?? 'the academic period' }}.
    </p>

    <p>This certification is issued on <strong>{{ now()->format('j') }}<sup>{{ now()->format('S') }}</sup> {{ now()->format('\\d\\a\\y \\o\\f F Y') }}</strong> in connection with the application for <strong>{{ $currentReason }}</strong>.</p>

    <p>Any courtesy extended to {{ $objectPronoun }} will be highly appreciated.</p>

    <div class="signature">
      <strong>RUCELJ D. PUGEDA, MIT</strong><br />
      Head, Student Affairs
    </div> <br><br>
    <div class="note">Not valid without University Dry Seal</div>
    <br>
  </div>


    <!-- FOOTER -->
    <div class="footer">
      <div class="two-tone-line"></div>
      <img src="data:image/png;base64,{{ $footer }}" alt="Footer" style="width: 100%; height: auto; margin-top: 10px;" />
    </div>
  </div>
    @endfor
  @endforeach
</body>

</html>
