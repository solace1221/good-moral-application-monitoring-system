<x-dashboard-layout>
  <x-slot name="roleTitle">PSG Officer</x-slot>

  <x-slot name="navigation">
    <a href="{{ route('PsgOfficer.dashboard') }}" class="nav-link {{ request()->routeIs('PsgOfficer.dashboard') ? 'active' : '' }}">
      <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6H8V5z"></path>
      </svg>
      Dashboard
    </a>

    <a href="{{ route('PsgOfficer.PsgAddViolation') }}" class="nav-link {{ request()->routeIs('PsgOfficer.PsgAddViolation') ? 'active' : '' }}">
      <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
      </svg>
      Add Minor Violation
    </a>

    <a href="{{ route('PsgOfficer.PsgViolation') }}" class="nav-link {{ request()->routeIs('PsgOfficer.PsgViolation') ? 'active' : '' }}">
      <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
      </svg>
      Minor Violations
    </a>

    <a href="{{ route('PsgOfficer.goodMoralForm') }}" class="nav-link {{ request()->routeIs('PsgOfficer.goodMoralForm') ? 'active' : '' }}">
      <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
      </svg>
      Apply for Good Moral
    </a>

    <a href="{{ route('PsgOfficer.personalViolations') }}" class="nav-link {{ request()->routeIs('PsgOfficer.personalViolations') ? 'active' : '' }}">
      <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
      </svg>
      My Violations
    </a>

    <a href="{{ route('PsgOfficer.applications') }}" class="nav-link {{ request()->routeIs('PsgOfficer.applications') ? 'active' : '' }}">
      <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002 2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
      </svg>
      My Applications
    </a>

    <form method="POST" action="{{ route('logout') }}" class="nav-logout-form">
      @csrf
      <button type="submit" class="nav-link nav-logout">
        <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75"></path>
        </svg>
        <span>Logout</span>
      </button>
    </form>
  </x-slot>

  <!-- Header Section -->
  <div class="header-section">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
      <div>
        <h1 class="role-title">Apply for Good Moral Certificate</h1>
        <p class="welcome-text">Submit your application for a Good Moral Certificate or Certificate of Residency</p>
        <div class="accent-line"></div>
      </div>
      <div style="display: flex; gap: 12px; align-items: center;">
        <a href="{{ route('PsgOfficer.dashboard') }}" class="btn-secondary">
          <svg style="width: 16px; height: 16px; margin-right: 8px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
          </svg>
          Back to Dashboard
        </a>
      </div>
    </div>
  </div>

  <!-- Violation Status Check -->
  @if ($violations->isEmpty())
  <div style="background: #d4edda; color: #155724; padding: 20px; border-radius: 8px; border-left: 4px solid #28a745; display: flex; align-items: center; gap: 16px; margin-bottom: 24px;">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="height: 24px; width: 24px; color: #28a745;">
      <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
    </svg>
    <div>
      <strong>No Violations</strong>
      <p style="margin: 4px 0 0 0;">You have no existing violations. You are eligible to apply for a Good Moral Certificate.</p>
    </div>
  </div>
  @else
  <div style="background: #fff3cd; color: #856404; padding: 20px; border-radius: 8px; border-left: 4px solid #ffc107; display: flex; align-items: center; gap: 16px; margin-bottom: 24px;">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="height: 24px; width: 24px; color: #856404;">
      <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
    </svg>
    <div>
      <strong>{{ $violations->count() }} Violation(s) Found</strong>
      <p style="margin: 4px 0 0 0;">You have unresolved violations. You can apply for a Certificate of Residency instead.</p>
    </div>
  </div>
  @endif

  <!-- Available Certificate Type -->
  @if(!empty($availableCertificates))
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

  <!-- Application Process Guide -->
  <div style="background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); overflow: hidden; margin-bottom: 24px;">
    <div style="padding: 24px; border-bottom: 1px solid #e9ecef;">
      <h3 style="color: var(--primary-green); margin: 0; font-size: 1.2rem;">Application Process Guide</h3>
    </div>
    <div style="padding: 24px;">
      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
        <div style="padding: 20px; background: #e8f5e8; border-radius: 8px; border-left: 4px solid #28a745;">
          <h4 style="color: #155724; font-weight: 600; margin-bottom: 12px;">📋 Application Flow</h4>
          <ol style="color: #155724; line-height: 1.6; margin: 0; padding-left: 20px;">
            <li>Submit application</li>
            <li>Registrar review & approval</li>
            <li>Dean review & approval</li>
            <li>Administrator final approval</li>
            <li>Upload Receipt</li>
            <li>Certificate printing & pickup</li>
          </ol>
        </div>

        <div style="padding: 20px; background: #fff3cd; border-radius: 8px; border-left: 4px solid #ffc107;">
          <h4 style="color: #856404; font-weight: 600; margin-bottom: 12px;">⏱️ Processing Time</h4>
          <p style="color: #856404; line-height: 1.6; margin: 0;">Applications typically take 3-5 business days to process. You will be notified when your certificate is ready for pickup.</p>
        </div>

        <div style="padding: 20px; background: #d1ecf1; border-radius: 8px; border-left: 4px solid #17a2b8;">
          <h4 style="color: #0c5460; font-weight: 600; margin-bottom: 12px;">📞 Need Help?</h4>
          <p style="color: #0c5460; line-height: 1.6; margin: 0;">Contact the Registrar's Office or Student Affairs for questions about your application status or requirements.</p>
        </div>
      </div>
    </div>
  </div>

  <!-- Application Form -->
  <div style="background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); overflow: hidden; margin-bottom: 24px;">
    <div style="padding: 24px; border-bottom: 1px solid #e9ecef;">
      <h2 style="margin: 0; color: var(--primary-green); font-size: 1.25rem; font-weight: 600;">Application Form</h2>
    </div>
    
    <form method="POST" action="{{ route('PsgOfficer.applyGoodMoral') }}" class="responsive-form" style="padding: 24px;">
      @csrf
      
      <!-- Hidden field for certificate type -->
      <input type="hidden" name="certificate_type" value="{{ $availableCertificates[0]['type'] ?? 'residency' }}">

      <!-- Personal Information Section -->
      <div style="margin-bottom: 32px;">
        <h3 style="color: #333; margin-bottom: 16px; font-size: 1.1rem; border-bottom: 2px solid #e9ecef; padding-bottom: 8px;">Personal Information</h3>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
          <!-- Gender (from profile) -->
          <input type="hidden" name="gender" value="{{ Auth::user()->gender ?? 'male' }}">

          <!-- Number of Copies -->
          <div class="form-group">
            <label for="num_copies" class="form-label">Number of Copies <span style="color: #dc3545;">*</span></label>
            <select name="num_copies" id="num_copies" class="form-input" required>
              <option value="">Select Number of Copies</option>
              <option value="1" {{ old('num_copies') == '1' ? 'selected' : '' }}>1 Copy</option>
              <option value="2" {{ old('num_copies') == '2' ? 'selected' : '' }}>2 Copies</option>
              <option value="3" {{ old('num_copies') == '3' ? 'selected' : '' }}>3 Copies</option>
              <option value="4" {{ old('num_copies') == '4' ? 'selected' : '' }}>4 Copies</option>
              <option value="5" {{ old('num_copies') == '5' ? 'selected' : '' }}>5 Copies</option>
            </select>
            @error('num_copies')
              <div class="error-message">{{ $message }}</div>
            @enderror
          </div>
        </div>

        <!-- Course and Year Level (Static Display) -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-top: 20px;">
          <div class="form-group">
            <label class="form-label">Course</label>
            <div style="padding: 12px 16px; background: #f8f9fa; border: 2px solid #e9ecef; border-radius: 8px; color: #495057; font-weight: 500;">
              {{ $studentCourseName ?? 'Not specified' }}
            </div>
          </div>

          <div class="form-group">
            <label class="form-label">Year Level</label>
            <div style="padding: 12px 16px; background: #f8f9fa; border: 2px solid #e9ecef; border-radius: 8px; color: #495057; font-weight: 500;">
              {{ $studentYearLevel ?? 'Not specified' }}
            </div>
          </div>
        </div>
      </div>

      <!-- Purpose Section -->
      <div style="margin-bottom: 32px;">
        <h3 style="color: #333; margin-bottom: 16px; font-size: 1.1rem; border-bottom: 2px solid #e9ecef; padding-bottom: 8px;">Purpose of Application</h3>
        
        <div class="form-group">
          <label class="form-label">Select Purpose(s) <span style="color: #dc3545;">*</span></label>
          <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 12px; margin-top: 8px;">
            @php
              $purposes = ['Employment', 'Scholarship', 'Transfer', 'Graduate School', 'Others'];
              $oldReasons = old('reason', []);
            @endphp
            
            @foreach($purposes as $purpose)
            <label style="display: flex; align-items: center; gap: 8px; padding: 12px; border: 2px solid #e9ecef; border-radius: 8px; cursor: pointer; transition: all 0.2s ease;"
                   onmouseover="this.style.borderColor='var(--primary-green)'"
                   onmouseout="this.style.borderColor='#e9ecef'">
              <input type="checkbox" name="reason[]" value="{{ $purpose }}" 
                     {{ in_array($purpose, $oldReasons) ? 'checked' : '' }}
                     style="margin: 0;">
              <span style="font-weight: 500; color: #333;">{{ $purpose }}</span>
            </label>
            @endforeach
          </div>
          @error('reason')
            <div class="error-message">{{ $message }}</div>
          @enderror
        </div>

        <!-- Other Purpose Input -->
        <div class="form-group" style="margin-top: 16px;">
          <label for="reason_other" class="form-label">If Others, please specify:</label>
          <input type="text" name="reason_other" id="reason_other" class="form-input" 
                 value="{{ old('reason_other') }}" placeholder="Please specify other purpose">
          @error('reason_other')
            <div class="error-message">{{ $message }}</div>
          @enderror
        </div>
      </div>

      <!-- Submit Button -->
      <div style="display: flex; justify-content: center; gap: 16px; margin-top: 32px;">
        <a href="{{ route('PsgOfficer.dashboard') }}" class="btn-secondary">Cancel</a>
        <button type="submit" class="btn-primary">
          <svg style="width: 16px; height: 16px; margin-right: 8px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
          </svg>
          Submit Application
        </button>
      </div>
    </form>
  </div>

</x-dashboard-layout>
