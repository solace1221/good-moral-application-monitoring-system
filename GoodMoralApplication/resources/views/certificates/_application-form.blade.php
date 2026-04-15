@php
  $accountType = $accountType ?? Auth::user()->account_type;
@endphp

  <!-- Application Form Section -->
  @if (!empty($availableCertificates))
  <div class="responsive-container">
    <div class="header-section">
      <h3 class="responsive-subtitle" style="color: var(--primary-green); margin-bottom: 20px; display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="height: 24px; width: 24px; flex-shrink: 0;">
          <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0.621 0 1.125-.504 1.125-1.125V9.375c0-.621.504-1.125 1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
        </svg>
        <span>Apply for Certificate</span>
      </h3>

    <!-- Certificate Type Selection -->
    @if (count($availableCertificates) > 1)
    <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; margin-bottom: 24px;">
      <h4 style="color: #333; margin-bottom: 16px; font-size: 1.1rem;">Available Certificate Types</h4>
      <div style="display: grid; gap: 12px;">
        @foreach($availableCertificates as $cert)
        <div style="background: white; padding: 16px; border-radius: 8px; border: 2px solid #e1e5e9;">
          <h5 style="color: var(--primary-green); margin: 0 0 8px 0; font-size: 1rem;">{{ $cert['name'] }}</h5>
          <p style="color: #666; margin: 0; font-size: 14px;">{{ $cert['description'] }}</p>
        </div>
        @endforeach
      </div>
    </div>
    @elseif (count($availableCertificates) === 1)
    <div style="background: #e8f5e8; padding: 16px; border-radius: 8px; margin-bottom: 24px; border-left: 4px solid var(--primary-green);">
      <h4 style="color: var(--primary-green); margin: 0 0 8px 0; font-size: 1rem;">{{ $availableCertificates[0]['name'] }}</h4>
      <p style="color: #333; margin: 0; font-size: 14px;">{{ $availableCertificates[0]['description'] }}</p>
      @if (!$violations->isEmpty())
      <p style="color: #856404; margin: 8px 0 0 0; font-size: 14px; font-weight: 500;">
        <strong>Note:</strong> Due to unresolved violations, you can only apply for a Certificate of Residency at this time.
      </p>
      @endif
    </div>
    @endif

    <form method="POST" action="{{ $formAction }}" class="responsive-form">
      @csrf

      {{-- Global validation error banner so failures are NEVER silent --}}
      @if ($errors->any())
        <div style="background: #fef2f2; border: 1px solid #fca5a5; border-left: 4px solid #dc2626; border-radius: 8px; padding: 16px; margin-bottom: 20px;">
          <h4 style="color: #dc2626; margin: 0 0 8px 0; font-size: 15px; font-weight: 600;">⚠️ Please fix the following errors:</h4>
          <ul style="margin: 0; padding-left: 20px; color: #991b1b; font-size: 14px;">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <!-- Certificate Type Selection -->
      @if (count($availableCertificates) > 1)
      <div class="responsive-form-group">
        <label style="font-weight: 600; color: #333;">Certificate Type</label>
        <div style="display: grid; gap: 12px; background: #f8f9fa; padding: 20px; border-radius: 8px;">
          @foreach($availableCertificates as $cert)
          <label style="display: flex; align-items: flex-start; gap: 12px; cursor: pointer; padding: 12px; border-radius: 6px; border: 2px solid #e1e5e9; background: white; transition: all 0.2s ease; min-height: 44px;"
                 onmouseover="this.style.borderColor='var(--primary-green)'"
                 onmouseout="this.style.borderColor='#e1e5e9'">
            <input type="radio" name="certificate_type" value="{{ $cert['type'] }}" required
                   style="accent-color: var(--primary-green); transform: scale(1.2); margin-top: 2px; flex-shrink: 0;">
            <div style="flex: 1;">
              <div style="font-weight: 600; color: var(--primary-green); margin-bottom: 4px; font-size: clamp(14px, 2vw, 16px);">{{ $cert['name'] }}</div>
              <div style="font-size: clamp(12px, 1.5vw, 14px); color: #666; line-height: 1.4;">{{ $cert['description'] }}</div>
            </div>
          </label>
          @endforeach
        </div>
        @error('certificate_type')
          <span style="color: #dc3545; font-size: 14px;">{{ $message }}</span>
        @enderror
      </div>
      @else
      <!-- Hidden input for single certificate type -->
      <input type="hidden" name="certificate_type" value="{{ $availableCertificates[0]['type'] }}">
      @endif

      <!-- Gender (from profile) -->
      <input type="hidden" name="gender" value="{{ Auth::user()->gender ?? 'male' }}">

      <!-- Number of Copies -->
      <div class="responsive-form-group">
        <label for="num_copies" style="font-weight: 600; color: #333; font-size: 15px; margin-bottom: 12px; display: block;">
          Number of Copies <span style="color: #dc3545; font-weight: bold;">*</span>
        </label>
        <div style="background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%); padding: 20px; border-radius: 12px; border: 2px solid #e1e5e9; transition: all 0.3s ease;">
          <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 16px;">
            <div style="flex: 1;">
              <input id="num_copies" name="num_copies" type="number" min="1" required
                     value="{{ old('num_copies') }}" 
                     style="width: 100%; padding: 14px 18px; border: 2px solid #e1e5e9; border-radius: 8px; font-size: 16px; font-weight: 500; transition: all 0.3s ease; background: white;"
                     placeholder="Enter number of copies" onchange="updatePaymentAmount()"
                     onfocus="this.style.borderColor='var(--primary-green)'; this.style.boxShadow='0 0 0 3px rgba(0, 176, 80, 0.1)'"
                     onblur="this.style.borderColor='#e1e5e9'; this.style.boxShadow='none'">
            </div>
            <div style="display: flex; flex-direction: column; align-items: center; padding: 12px 20px; background: var(--primary-green); border-radius: 8px; min-width: 60px;">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="white" style="width: 24px; height: 24px; margin-bottom: 4px;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 17.25v3.375c0 .621-.504 1.125-1.125 1.125h-9.75a1.125 1.125 0 01-1.125-1.125V7.875c0-.621.504-1.125 1.125-1.125H6.75a9.06 9.06 0 011.5.124m7.5 10.376h3.375c.621 0 1.125-.504 1.125-1.125V11.25c0-4.46-3.243-8.161-7.5-8.876a9.06 9.06 0 00-1.5-.124H9.375c-.621 0-1.125.504-1.125 1.125v3.5m7.5 10.375H9.375a1.125 1.125 0 01-1.125-1.125v-9.25m12 6.625v-1.875a3.375 3.375 0 00-3.375-3.375h-1.5a1.125 1.125 0 01-1.125-1.125v-1.5a3.375 3.375 0 00-3.375-3.375H9.75" />
              </svg>
              <span style="color: white; font-size: 11px; font-weight: 600; text-transform: uppercase;">Copies</span>
            </div>
          </div>

          <!-- Payment Information -->
          <div style="background: linear-gradient(135deg, #e8f5e8 0%, #f0f9f0 100%); padding: 16px; border-radius: 8px; border: 1px solid rgba(0, 176, 80, 0.2);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
              <span style="font-weight: 600; color: #2d5016; font-size: 14px; display: flex; align-items: center; gap: 6px;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 18px; height: 18px;">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z" />
                </svg>
                Payment Required:
              </span>
              <span style="font-weight: 700; color: var(--primary-green); font-size: 20px;" id="totalAmount">₱50.00</span>
            </div>
            <div style="font-size: 13px; color: #5a6c57; background: white; padding: 8px 12px; border-radius: 6px; margin-bottom: 8px;">
              <span id="paymentCalculation">1 reason × 1 copy × ₱50.00</span>
            </div>
            <div style="font-size: 12px; color: #5a6c57; display: flex; align-items: center; gap: 6px;">
              <span style="font-size: 16px;">💡</span>
              <span style="font-style: italic;">Payment receipt upload required after admin approval</span>
            </div>
          </div>
        </div>

        @error('num_copies')
          <span style="color: #dc3545; font-size: 14px; margin-top: 8px; display: block;">{{ $message }}</span>
        @enderror
      </div>

      <!-- Reason of Application -->
      <div style="display: grid; gap: 12px;">
        <label style="font-weight: 600; color: #333;">Reason of Application (select all that apply) <span style="color: #dc3545; font-weight: bold;">*</span></label>
        <div style="display: grid; gap: 12px; background: #f8f9fa; padding: 20px; border-radius: 8px;">
          @php
            $reasons = [
              'Transfer',
              'Employment',
              'Scholarship',
              'Board Examination',
              'Government examination',
              'VISA/Passport application',
              'PSG Election',
              'Cross enrollment'
            ];

            // Remove PSG Election for alumni
            if ($accountType === 'alumni') {
              $reasons = array_filter($reasons, function($reason) {
                return $reason !== 'PSG Election';
              });
            }
          @endphp

          @foreach($reasons as $reason)
          <label style="display: flex; align-items: center; gap: 12px; cursor: pointer; padding: 8px; border-radius: 6px; transition: background-color 0.2s ease;"
                 onmouseover="this.style.backgroundColor='#e9ecef'"
                 onmouseout="this.style.backgroundColor='transparent'">
            <input type="checkbox" name="reason[]" value="{{ $reason }}" class="reason-checkbox"
                   style="accent-color: var(--primary-green); transform: scale(1.2);">
            <span style="font-size: 14px;">{{ $reason }}</span>
          </label>
          @endforeach

          <label style="display: flex; align-items: center; gap: 12px; cursor: pointer; padding: 8px; border-radius: 6px; transition: background-color 0.2s ease;"
                 onmouseover="this.style.backgroundColor='#e9ecef'"
                 onmouseout="this.style.backgroundColor='transparent'">
            <input type="checkbox" name="reason[]" value="Others" id="reasonOthers" class="reason-checkbox"
                   style="accent-color: var(--primary-green); transform: scale(1.2);">
            <span style="font-size: 14px;">Others (please specify)</span>
          </label>

          <input type="text" name="reason_other" id="reasonOtherInput"
                 style="padding: 12px 16px; border: 2px solid #e1e5e9; border-radius: 8px; font-size: 14px; margin-top: 8px; transition: border-color 0.3s ease;"
                 placeholder="Please specify..." disabled>
        </div>
        <div id="reasonError" style="color: #dc3545; font-size: 14px; display: none;">Please select at least one reason.</div>
        @error('reason')
          <span style="color: #dc3545; font-size: 14px;">{{ $message }}</span>
        @enderror
      </div>

      @if ($accountType === 'alumni')
      <!-- Alumni specific fields -->
      <div class="responsive-form-row responsive-grid-2">
        <div class="responsive-form-group">
          <label for="graduation_date" style="font-weight: 600; color: #333;">Date of Graduation <span style="color: #dc3545; font-weight: bold;">*</span></label>
          <input id="graduation_date" name="graduation_date" type="date"
                 value="{{ old('graduation_date') }}" class="responsive-form-input">
          @error('graduation_date')
            <span style="color: #dc3545; font-size: 14px;">{{ $message }}</span>
          @enderror
        </div>

        <div class="responsive-form-group">
          <label for="course_completed" style="font-weight: 600; color: #333;">Course Completed</label>
          @if($studentCourse && $studentCourseName)
            <div class="static-field">
              <span class="course-code">{{ $studentCourse }}</span> - <span class="course-name">{{ $studentCourseName }}</span>
              @if($studentYearLevel)
                <span class="year-level">({{ $studentYearLevel }})</span>
              @endif
            </div>
            <input type="hidden" name="course_completed" value="{{ $studentCourse }}">
            <small style="color: #666; font-size: 12px; margin-top: 4px; display: block;">
              📌 Course and year level information is automatically populated from your student profile
            </small>
          @else
            <div class="static-field error">
              <span style="color: #dc3545;">⚠️ Course not set in your profile</span>
            </div>
            <small style="color: #dc3545; font-size: 12px; margin-top: 4px; display: block;">
              Please contact the registrar to update your course information
            </small>
          @endif
          @error('course_completed')
            <span style="color: #dc3545; font-size: 14px;">{{ $message }}</span>
          @enderror
        </div>
      </div>
      @elseif ($accountType === 'student')
      <!-- Student specific fields -->
      <div class="responsive-form">
        <div class="responsive-form-group">
          <label for="last_course_year_level" style="font-weight: 600; color: #333;">Course of Last School Attended in SPUP</label>
          @if($studentCourse && $studentCourseName)
            <div class="static-field">
              <span class="course-code">{{ $studentCourse }}</span> - <span class="course-name">{{ $studentCourseName }}</span>
              @if($studentYearLevel)
                <span class="year-level">({{ $studentYearLevel }})</span>
              @endif
            </div>
            <input type="hidden" name="last_course_year_level" value="{{ $studentCourse }}">
            <small style="color: #666; font-size: 12px; margin-top: 4px; display: block;">
              📌 Course and year level information is automatically populated from your student profile
            </small>
          @else
            <div class="static-field error">
              <span style="color: #dc3545;">⚠️ Course not set in your profile</span>
            </div>
            <small style="color: #dc3545; font-size: 12px; margin-top: 4px; display: block;">
              Please contact the registrar to update your course information
            </small>
          @endif
          @error('last_course_year_level')
            <span style="color: #dc3545; font-size: 14px;">{{ $message }}</span>
          @enderror
        </div>

        <!-- Last Attendance Information Card -->
        <div style="background: linear-gradient(135deg, #f0f7ff 0%, #ffffff 100%); padding: 24px; border-radius: 12px; border: 2px solid #dbe9ff; margin-top: 8px;">
          <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 20px;">
            <div style="background: linear-gradient(135deg, #4a90e2 0%, #357abd 100%); padding: 10px; border-radius: 10px; box-shadow: 0 4px 6px rgba(74, 144, 226, 0.2);">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="white" style="width: 24px; height: 24px;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
              </svg>
            </div>
            <div>
              <h3 style="font-weight: 700; color: #2c5282; font-size: 16px; margin: 0;">Last Attendance Information</h3>
              <p style="font-size: 13px; color: #64748b; margin: 2px 0 0 0;">Select your semester and school year</p>
            </div>
          </div>

          <div style="display: grid; gap: 16px;">
            <!-- Semester Selection -->
            <div class="responsive-form-group" style="margin: 0;">
              <label for="last_semester" style="font-weight: 600; color: #2c5282; font-size: 14px; margin-bottom: 8px; display: block;">
                Semester <span style="color: #dc3545; font-weight: bold;">*</span>
              </label>
              <select id="last_semester" name="last_semester" required
                      style="width: 100%; padding: 14px 40px 14px 18px; border: 2px solid #dbe9ff; border-radius: 8px; font-size: 15px; font-weight: 500; transition: all 0.3s ease; background: white; background-image: url('data:image/svg+xml,%3csvg xmlns=\'http://www.w3.org/2000/svg\' fill=\'none\' viewBox=\'0 0 20 20\'%3e%3cpath stroke=\'%234a90e2\' stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'1.5\' d=\'M6 8l4 4 4-4\'/%3e%3c/svg%3e'); background-position: right 12px center; background-repeat: no-repeat; background-size: 20px; appearance: none; cursor: pointer;"
                      onfocus="this.style.borderColor='#4a90e2'; this.style.boxShadow='0 0 0 3px rgba(74, 144, 226, 0.1)'; this.style.backgroundImage='url(\'data:image/svg+xml,%3csvg xmlns=\\\'http://www.w3.org/2000/svg\\\' fill=\\\'none\\\' viewBox=\\\'0 0 20 20\\\'%3e%3cpath stroke=\\\'%234a90e2\\\' stroke-linecap=\\\'round\\\' stroke-linejoin=\\\'round\\\' stroke-width=\\\'2\\\' d=\\\'M6 8l4 4 4-4\\\'/%3e%3c/svg%3e')'"
                      onblur="this.style.borderColor='#dbe9ff'; this.style.boxShadow='none'; this.style.backgroundImage='url(\'data:image/svg+xml,%3csvg xmlns=\\\'http://www.w3.org/2000/svg\\\' fill=\\\'none\\\' viewBox=\\\'0 0 20 20\\\'%3e%3cpath stroke=\\\'%234a90e2\\\' stroke-linecap=\\\'round\\\' stroke-linejoin=\\\'round\\\' stroke-width=\\\'1.5\\\' d=\\\'M6 8l4 4 4-4\\\'/%3e%3c/svg%3e')'">
                <option value="" disabled selected style="color: #94a3b8;">Select semester</option>
                <option value="First Semester" {{ old('last_semester') == 'First Semester' ? 'selected' : '' }}>First Semester</option>
                <option value="Second Semester" {{ old('last_semester') == 'Second Semester' ? 'selected' : '' }}>Second Semester</option>
                <option value="Summer Term" {{ old('last_semester') == 'Summer Term' ? 'selected' : '' }}>Summer Term</option>
              </select>
              @error('last_semester')
                <span style="color: #dc3545; font-size: 13px; margin-top: 6px; display: block;">{{ $message }}</span>
              @enderror
            </div>

            <!-- School Year Selection -->
            <div class="responsive-form-group" style="margin: 0;">
              <label for="last_school_year" style="font-weight: 600; color: #2c5282; font-size: 14px; margin-bottom: 8px; display: block;">
                School Year <span style="color: #dc3545; font-weight: bold;">*</span>
              </label>
              <select id="last_school_year" name="last_school_year" required
                      style="width: 100%; padding: 14px 40px 14px 18px; border: 2px solid #dbe9ff; border-radius: 8px; font-size: 15px; font-weight: 500; transition: all 0.3s ease; background: white; background-image: url('data:image/svg+xml,%3csvg xmlns=\'http://www.w3.org/2000/svg\' fill=\'none\' viewBox=\'0 0 20 20\'%3e%3cpath stroke=\'%234a90e2\' stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'1.5\' d=\'M6 8l4 4 4-4\'/%3e%3c/svg%3e'); background-position: right 12px center; background-repeat: no-repeat; background-size: 20px; appearance: none; cursor: pointer;"
                      onfocus="this.style.borderColor='#4a90e2'; this.style.boxShadow='0 0 0 3px rgba(74, 144, 226, 0.1)'; this.style.backgroundImage='url(\'data:image/svg+xml,%3csvg xmlns=\\\'http://www.w3.org/2000/svg\\\' fill=\\\'none\\\' viewBox=\\\'0 0 20 20\\\'%3e%3cpath stroke=\\\'%234a90e2\\\' stroke-linecap=\\\'round\\\' stroke-linejoin=\\\'round\\\' stroke-width=\\\'2\\\' d=\\\'M6 8l4 4 4-4\\\'/%3e%3c/svg%3e')'"
                      onblur="this.style.borderColor='#dbe9ff'; this.style.boxShadow='none'; this.style.backgroundImage='url(\'data:image/svg+xml,%3csvg xmlns=\\\'http://www.w3.org/2000/svg\\\' fill=\\\'none\\\' viewBox=\\\'0 0 20 20\\\'%3e%3cpath stroke=\\\'%234a90e2\\\' stroke-linecap=\\\'round\\\' stroke-linejoin=\\\'round\\\' stroke-width=\\\'1.5\\\' d=\\\'M6 8l4 4 4-4\\\'/%3e%3c/svg%3e')'">
                <option value="" disabled selected style="color: #94a3b8;">Select school year</option>
                @php
                  $currentYear = date('Y');
                  $startYear = 2020;
                  for ($year = $currentYear; $year >= $startYear; $year--) {
                    $nextYear = $year + 1;
                    $schoolYear = $year . '-' . $nextYear;
                    $selected = old('last_school_year') == $schoolYear ? 'selected' : '';
                    echo "<option value=\"{$schoolYear}\" {$selected}>{$schoolYear}</option>";
                  }
                @endphp
              </select>
              @error('last_school_year')
                <span style="color: #dc3545; font-size: 13px; margin-top: 6px; display: block;">{{ $message }}</span>
              @enderror
            </div>
          </div>
        </div>
      </div>
      @endif

      <!-- Submit Button -->
      <div style="margin-top: 32px; padding-top: 24px; border-top: 2px solid #e9ecef;">
        <div style="display: flex; flex-direction: column; gap: 12px; align-items: center;">
          <button type="button" onclick="showReviewModal()" 
                  style="width: 100%; max-width: 400px; background: linear-gradient(135deg, var(--primary-green) 0%, #009944 100%); color: #ffffff !important; padding: 16px 32px; border: none; border-radius: 12px; font-size: 16px; font-weight: 600; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 12px; box-shadow: 0 4px 12px rgba(0, 176, 80, 0.3); transition: all 0.3s ease; position: relative; overflow: hidden;"
                  onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 16px rgba(0, 176, 80, 0.4)'"
                  onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 12px rgba(0, 176, 80, 0.3)'">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="#ffffff" style="width: 24px; height: 24px;">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span style="color: #ffffff !important;">Review Application</span>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="#ffffff" style="width: 20px; height: 20px;">
              <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
            </svg>
          </button>
          <p style="font-size: 13px; color: #666; text-align: center; margin: 0;">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 16px; height: 16px; display: inline; vertical-align: middle;">
              <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
            </svg>
            You'll be able to review and edit your application before final submission
          </p>
        </div>
      </div>
    </form>

    <!-- Review Modal -->
    <div id="reviewModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.7); z-index: 9999; overflow-y: auto; backdrop-filter: blur(5px);">
      <div style="min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 20px;">
        <div style="background: white; border-radius: 16px; max-width: 560px; width: 100%; box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3); animation: slideDown 0.3s ease; max-height: 90vh; overflow-y: auto;">
          <!-- Modal Header -->
          <div style="background: linear-gradient(135deg, var(--primary-green) 0%, #009944 100%); color: #ffffff !important; padding: 24px; border-radius: 16px 16px 0 0; position: sticky; top: 0; z-index: 10;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
              <div style="display: flex; align-items: center; gap: 12px;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="#ffffff" style="width: 28px; height: 28px;">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
                </svg>
                <div>
                  <h2 style="margin: 0 !important; font-size: 22px; font-weight: 700; color: #ffffff !important;">Review Your Application</h2>
                  <p style="margin: 4px 0 0 0 !important; font-size: 14px; opacity: 0.9; color: #ffffff !important;">Please verify all details before submitting</p>
                </div>
              </div>
              <button type="button" onclick="closeReviewModal()" style="background: rgba(255, 255, 255, 0.2); border: none; color: #ffffff !important; width: 36px; height: 36px; border-radius: 50%; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.3s ease;"
                      onmouseover="this.style.background='rgba(255, 255, 255, 0.3)'"
                      onmouseout="this.style.background='rgba(255, 255, 255, 0.2)'">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="#ffffff" style="width: 20px; height: 20px;">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
              </button>
            </div>
          </div>

          <!-- Modal Body -->
          <div style="padding: 24px;" id="reviewContent">
            <!-- Content will be populated by JavaScript -->
          </div>

          <!-- Modal Footer -->
          <div style="background: #f8f9fa; padding: 20px 32px; border-radius: 0 0 16px 16px; display: flex; justify-content: space-between; align-items: center; gap: 12px; flex-wrap: wrap; border-top: 1px solid #e9ecef;">
            <button type="button" onclick="editApplication()" 
                    style="background: white; color: #495057; padding: 12px 24px; border: 2px solid #dee2e6; border-radius: 8px; font-size: 15px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px; transition: all 0.3s ease;"
                    onmouseover="this.style.borderColor='#adb5bd'; this.style.background='#f8f9fa'"
                    onmouseout="this.style.borderColor='#dee2e6'; this.style.background='white'">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 18px; height: 18px;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
              </svg>
              Edit Application
            </button>
            <button type="button" onclick="submitApplication()" 
                    style="background: linear-gradient(135deg, var(--primary-green) 0%, #009944 100%); color: white !important; padding: 12px 32px; border: none; border-radius: 8px; font-size: 15px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px; box-shadow: 0 4px 12px rgba(0, 176, 80, 0.3); transition: all 0.3s ease;"
                    onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 16px rgba(0, 176, 80, 0.4)'"
                    onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 12px rgba(0, 176, 80, 0.3)'">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 20px; height: 20px;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
              </svg>
              Submit Application
            </button>
          </div>
        </div>
      </div>
    </div>

    </div>
  </div>
  @else
  <div class="header-section">
    <div style="background: #f8d7da; color: #721c24; padding: 20px; border-radius: 8px; border-left: 4px solid #dc3545; display: flex; align-items: center; gap: 16px;">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="height: 24px; width: 24px; color: #dc3545;">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
      </svg>
      <div>
        <strong>No Certificates Available</strong>
        <p style="margin: 4px 0 0 0;">No certificate applications are available at this time. Please contact the Dean's office for assistance.</p>
      </div>
    </div>
  </div>
  @endif

  <!-- Violations Section -->
  <div class="responsive-container">
    <div class="header-section">
      <h3 class="responsive-subtitle" style="color: var(--primary-green); margin-bottom: 20px; display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="height: 24px; width: 24px; flex-shrink: 0;">
          <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <span>Violation Status</span>
      </h3>

    @if ($violations->isEmpty())
    <div style="background: #d4edda; color: #155724; padding: 20px; border-radius: 8px; border-left: 4px solid #28a745; display: flex; align-items: center; gap: 16px;">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="height: 24px; width: 24px; color: #28a745;">
        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
      </svg>
      <div>
        <strong>No Violations</strong>
        <p style="margin: 4px 0 0 0;">You have no existing violations. You are eligible to apply for a Good Moral Certificate.</p>
      </div>
    </div>
    @else
    <div style="background: #f8d7da; color: #721c24; padding: 16px 20px; border-radius: 8px; border-left: 4px solid #dc3545; margin-bottom: 20px; display: flex; align-items: center; gap: 16px;">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="height: 24px; width: 24px; color: #dc3545;">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
      </svg>
      <div>
        <strong>Active Violations Found</strong>
        <p style="margin: 4px 0 0 0;">You have {{ $violations->count() }} active violation(s). Please resolve these before applying for a Good Moral Certificate.</p>
      </div>
    </div>

    <!-- Violations Table -->
    <div class="responsive-table-container">
      <table class="responsive-table">
        <thead>
          <tr style="background: var(--dark-green); color: white;">
            <th style="color: white;">Offense Type</th>
            <th style="color: white;">Description</th>
            <th class="desktop-only" style="color: white;">Date Committed</th>
            <th style="color: white;">Status</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($violations as $index => $violation)
          <tr style="border-bottom: 1px solid #e9ecef; {{ $index % 2 === 0 ? 'background: #f8f9fa;' : 'background: white;' }}">
            <td>
              <span style="background: {{ $violation->offense_type === 'major' ? '#dc3545' : '#ffc107' }}; color: {{ $violation->offense_type === 'major' ? 'white' : '#333' }}; padding: 4px 8px; border-radius: 20px; font-size: 11px; font-weight: 600; text-transform: uppercase; white-space: nowrap;">
                {{ ucfirst($violation->offense_type) }}
              </span>
            </td>
            <td>
              <div style="color: #333; line-height: 1.4;">{{ $violation->violation }}</div>
              <div class="mobile-only" style="color: #666; font-size: 12px; margin-top: 4px;">{{ $violation->created_at->format('M d, Y') }}</div>
            </td>
            <td class="desktop-only" style="color: #666;">{{ $violation->created_at->format('M d, Y') }}</td>
            <td>
              <span style="background: #ffc107; color: #333; padding: 4px 8px; border-radius: 20px; font-size: 11px; font-weight: 600; white-space: nowrap;">
                PENDING
              </span>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    @endif
    </div>
  </div>

  <!-- JavaScript for form interactions -->
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const otherCheckbox = document.getElementById('reasonOthers');
      const otherInput = document.getElementById('reasonOtherInput');
      const allCheckboxes = document.querySelectorAll('.reason-checkbox');

      // Handle "Others" checkbox
      allCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
          if (otherCheckbox.checked) {
            otherInput.disabled = false;
            otherInput.required = true;
            otherInput.style.borderColor = 'var(--primary-green)';
          } else {
            otherInput.disabled = true;
            otherInput.required = false;
            otherInput.value = '';
            otherInput.style.borderColor = '#e1e5e9';
          }

          // Hide error message when user selects a reason
          const checkedBoxes = document.querySelectorAll('.reason-checkbox:checked');
          if (checkedBoxes.length > 0) {
            const reasonError = document.getElementById('reasonError');
            if (reasonError) reasonError.style.display = 'none';
          }

          // Update payment calculation when reasons change
          updatePaymentAmount();
        });
      });

      // Form validation - only apply to application form, not logout form
      const applicationForm = document.querySelector('form:not(.nav-logout-form)');
      if (applicationForm) {
        applicationForm.addEventListener('submit', function(e) {
          const checkedBoxes = document.querySelectorAll('.reason-checkbox:checked');
          if (checkedBoxes.length === 0) {
            e.preventDefault();
            const reasonError = document.getElementById('reasonError');
            if (reasonError) {
              reasonError.style.display = 'block';
              reasonError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
            return false;
          }
        });
      }

      // Add focus effects to form inputs and selects
      const inputs = document.querySelectorAll('input[type="text"], input[type="number"], input[type="date"], select');
      inputs.forEach(input => {
        input.addEventListener('focus', function() {
          this.style.borderColor = 'var(--primary-green)';
          this.style.boxShadow = '0 0 0 3px rgba(0, 176, 80, 0.1)';
        });

        input.addEventListener('blur', function() {
          this.style.borderColor = '#e1e5e9';
          this.style.boxShadow = 'none';
        });
      });
    });

    // Payment calculation function
    function updatePaymentAmount() {
      const copiesInput = document.getElementById('num_copies');
      const totalAmountSpan = document.getElementById('totalAmount');
      const paymentCalculationSpan = document.getElementById('paymentCalculation');

      const copies = parseInt(copiesInput.value) || 1;
      const checkedReasons = document.querySelectorAll('.reason-checkbox:checked').length || 1;
      const ratePerUnit = 50;
      const totalAmount = checkedReasons * copies * ratePerUnit;

      totalAmountSpan.textContent = `₱${totalAmount.toFixed(2)}`;

      const reasonText = checkedReasons === 1 ? 'reason' : 'reasons';
      const copyText = copies === 1 ? 'copy' : 'copies';
      paymentCalculationSpan.textContent = `${checkedReasons} ${reasonText} × ${copies} ${copyText} × ₱${ratePerUnit}.00`;
    }

    // Initialize payment calculation on page load
    document.addEventListener('DOMContentLoaded', function() {
      updatePaymentAmount();
    });

    // Course fields are now static and populated from student profile
    // No JavaScript needed for course selection

    // Review Modal Functions
    function showReviewModal() {
      // Validate form first
      const form = document.querySelector('form[action="{{ $formAction }}"]');
      if (!form.checkValidity()) {
        form.reportValidity();
        return;
      }

      // Check if at least one reason is selected
      const checkedReasons = document.querySelectorAll('.reason-checkbox:checked');
      const reasonError = document.getElementById('reasonError');
      if (checkedReasons.length === 0) {
        reasonError.style.display = 'block';
        reasonError.scrollIntoView({ behavior: 'smooth', block: 'center' });
        return;
      }
      reasonError.style.display = 'none';

      // Get form data
      const formData = new FormData(form);
      const reviewContent = document.getElementById('reviewContent');
      
      // Build review content
      const certType = formData.get('certificate_type');
      const certName = certType === 'good_moral' ? 'Good Moral Certificate' : 'Certificate of Residency';
      const numCopies = formData.get('num_copies');

      // Collect reasons
      const reasons = [];
      checkedReasons.forEach(checkbox => {
        if (checkbox.value === 'Others') {
          const otherReason = formData.get('reason_other');
          if (otherReason) reasons.push(otherReason);
        } else {
          reasons.push(checkbox.value);
        }
      });

      const accountType = '{{ $accountType }}';
      const copies = parseInt(numCopies) || 1;
      const ratePerUnit = 50;
      const totalAmount = reasons.length * copies * ratePerUnit;

      // Helper to build a summary row
      function summaryRow(label, value, last = false) {
        const border = last ? '' : 'border-bottom: 1px solid #f1f3f5;';
        return `<div style="display: flex; justify-content: space-between; align-items: flex-start; padding: 11px 20px; ${border} gap: 16px;">
          <span style="color: #6c757d; font-size: 14px; white-space: nowrap; flex-shrink: 0;">${label}</span>
          <span style="color: #1a1a1a; font-size: 14px; font-weight: 600; text-align: right;">${value}</span>
        </div>`;
      }

      // Application Summary rows
      let rows = '';
      rows += summaryRow('Certificate Type', certName);
      rows += summaryRow('Number of Copies', numCopies);
      rows += summaryRow('Purpose', reasons.join(', '));

      if (accountType === 'student') {
        const lastCourse = formData.get('last_course_year_level');
        const lastSemester = formData.get('last_semester');
        const lastSchoolYear = formData.get('last_school_year');
        if (lastCourse) rows += summaryRow('Course &amp; Year Level', lastCourse);
        if (lastSemester && lastSchoolYear) rows += summaryRow('Last Attendance', `${lastSemester} ${lastSchoolYear}`);
      }

      if (accountType === 'alumni') {
        const courseCompleted = formData.get('course_completed');
        const graduationDate = formData.get('graduation_date');
        if (courseCompleted) rows += summaryRow('Course Completed', courseCompleted);
        if (graduationDate) rows += summaryRow('Graduation Date', new Date(graduationDate).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' }));
      }

      // Payment rows (last row has no border)
      let paymentRows = '';
      paymentRows += summaryRow('Rate', `₱${ratePerUnit}.00 per copy per reason`);
      paymentRows += summaryRow('Calculation', `${reasons.length} reason(s) × ${copies} cop${copies > 1 ? 'ies' : 'y'} × ₱${ratePerUnit}.00`);
      paymentRows += summaryRow('Total Amount', `₱${totalAmount.toFixed(2)}`, true);

      const html = `
        <div style="border: 1px solid #e9ecef; border-radius: 10px; overflow: hidden;">

          <div style="padding: 12px 20px; background: #f8f9fa; border-bottom: 1px solid #e9ecef;">
            <span style="font-size: 11px; font-weight: 700; color: #6c757d; text-transform: uppercase; letter-spacing: 0.08em;">Application Summary</span>
          </div>

          ${rows}

          <div style="padding: 12px 20px; background: #f8f9fa; border-top: 1px solid #e9ecef; border-bottom: 1px solid #e9ecef;">
            <span style="font-size: 11px; font-weight: 700; color: #6c757d; text-transform: uppercase; letter-spacing: 0.08em;">Payment Summary</span>
          </div>

          ${paymentRows}

        </div>
        <p style="margin: 14px 0 0 0; font-size: 13px; color: #6c757d; text-align: center;">Payment receipt upload is required after admin approval.</p>
      `;

      reviewContent.innerHTML = html;
      document.getElementById('reviewModal').style.display = 'block';
      document.body.style.overflow = 'hidden';
    }

    function closeReviewModal() {
      document.getElementById('reviewModal').style.display = 'none';
      document.body.style.overflow = 'auto';
    }

    function editApplication() {
      closeReviewModal();
      window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function submitApplication() {
      const form = document.querySelector('form[action="{{ $formAction }}"]');
      closeReviewModal();
      form.submit();
    }

    // Close modal when clicking outside
    document.addEventListener('click', function(event) {
      const modal = document.getElementById('reviewModal');
      if (event.target === modal) {
        closeReviewModal();
      }
    });
  </script>



  <style>
    /* Application Process Guide Responsive Styling */
    .process-guide-title {
      color: var(--primary-green);
      margin-bottom: 16px;
      font-size: 1.2rem;
      font-weight: 600;
    }

    .process-guide-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
      gap: 20px;
    }

    .process-guide-card {
      padding: 20px;
      border-radius: 8px;
      border-left: 4px solid;
      transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .process-guide-card:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .process-flow-card {
      background: #e8f5e8;
      border-left-color: #28a745;
    }

    .processing-time-card {
      background: #fff3cd;
      border-left-color: #ffc107;
    }

    .help-card {
      background: #d1ecf1;
      border-left-color: #17a2b8;
    }

    .process-guide-card-title {
      font-weight: 600;
      margin-bottom: 12px;
      font-size: 1rem;
    }

    .process-flow-card .process-guide-card-title {
      color: #155724;
    }

    .processing-time-card .process-guide-card-title {
      color: #856404;
    }

    .help-card .process-guide-card-title {
      color: #0c5460;
    }

    .process-flow-list {
      margin: 0;
      padding-left: 20px;
      line-height: 1.6;
      color: #155724;
    }

    .process-flow-list li {
      margin-bottom: 4px;
    }

    .process-guide-text {
      line-height: 1.6;
      margin: 0;
    }

    .processing-time-card .process-guide-text {
      color: #856404;
    }

    .help-card .process-guide-text {
      color: #0c5460;
    }

    /* Mobile Responsive Design */
    @media (max-width: 768px) {
      .process-guide-title {
        font-size: 1.1rem;
        margin-bottom: 12px;
        text-align: center;
      }

      .process-guide-grid {
        grid-template-columns: 1fr;
        gap: 16px;
      }

      .process-guide-card {
        padding: 16px;
        margin: 0 4px;
      }

      .process-guide-card-title {
        font-size: 0.95rem;
        margin-bottom: 10px;
      }

      .process-flow-list {
        padding-left: 16px;
        font-size: 14px;
      }

      .process-guide-text {
        font-size: 14px;
      }
    }

    /* Small Mobile Devices */
    @media (max-width: 480px) {
      .process-guide-title {
        font-size: 1rem;
      }

      .process-guide-card {
        padding: 12px;
        margin: 0 2px;
      }

      .process-guide-card-title {
        font-size: 0.9rem;
        margin-bottom: 8px;
      }

      .process-flow-list {
        padding-left: 14px;
        font-size: 13px;
      }

      .process-guide-text {
        font-size: 13px;
      }
    }

    /* Tablet Landscape */
    @media (min-width: 769px) and (max-width: 1024px) {
      .process-guide-grid {
        grid-template-columns: repeat(2, 1fr);
      }

      .process-guide-card:last-child {
        grid-column: 1 / -1;
        max-width: 50%;
        margin: 0 auto;
      }
    }

    /* Large Screens */
    @media (min-width: 1200px) {
      .process-guide-grid {
        gap: 24px;
      }

      .process-guide-card {
        padding: 24px;
      }
    }

    /* Static course field styling */
    .static-field {
      padding: 12px 16px;
      background: #f8f9fa;
      border: 2px solid #e1e5e9;
      border-radius: 8px;
      color: #333;
      font-size: 14px;
      line-height: 1.4;
      min-height: 44px;
      display: flex;
      align-items: center;
    }

    .static-field.error {
      background: #fff5f5;
      border-color: #fed7d7;
      color: #c53030;
    }

    .static-field .course-code {
      font-weight: 600;
      color: #2d3748;
      background: #edf2f7;
      padding: 2px 8px;
      border-radius: 4px;
      margin-right: 8px;
      font-size: 13px;
    }

    .static-field .course-name {
      color: #4a5568;
      flex: 1;
    }

    .static-field .year-level {
      color: #7b1fa2;
      font-weight: 500;
      background: #f3e5f5;
      padding: 2px 8px;
      border-radius: 4px;
      margin-left: 8px;
      font-size: 12px;
    }

    /* Mobile-specific improvements */
    @media (max-width: 768px) {
      .static-field {
        font-size: 13px;
        padding: 10px 12px;
        min-height: 40px;
      }

      .static-field .course-code {
        font-size: 12px;
        padding: 1px 6px;
      }
    }

    /* Enhanced styling for remaining form elements */
    select.responsive-form-input {
      background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
      background-position: right 12px center;
      background-repeat: no-repeat;
      background-size: 16px;
      padding-right: 40px;
      appearance: none;
      -webkit-appearance: none;
      -moz-appearance: none;
    }

    select.responsive-form-input:focus {
      background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%2300b050' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
    }

    /* Option styling */
    select.responsive-form-input option {
      padding: 8px 12px;
      font-size: 14px;
      line-height: 1.4;
    }

    /* Mobile-specific improvements for selects */
    @media (max-width: 768px) {
      select.responsive-form-input {
        font-size: 16px; /* Prevents zoom on iOS */
        padding: 14px 40px 14px 16px;
      }
    }

    /* Review Modal Animation */
    @keyframes slideDown {
      from {
        opacity: 0;
        transform: translateY(-50px) scale(0.95);
      }
      to {
        opacity: 1;
        transform: translateY(0) scale(1);
      }
    }

    /* Review Modal Responsive Design */
    @media (max-width: 768px) {
      #reviewModal > div {
        padding: 10px;
      }
      
      #reviewModal > div > div {
        border-radius: 12px;
        max-height: 95vh;
      }
      
      #reviewModal #reviewContent {
        padding: 20px;
      }
      
      #reviewModal .responsive-btn {
        font-size: 14px;
        padding: 10px 20px;
      }
    }
  </style>