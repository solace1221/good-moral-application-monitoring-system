<x-dashboard-layout>
  <x-slot name="roleTitle">PSG Officer</x-slot>

  <x-slot name="navigation">
    <a href="{{ route('PsgOfficer.dashboard') }}" class="nav-link {{ request()->routeIs('PsgOfficer.dashboard') ? 'active' : '' }}">
      <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 7.125C2.25 6.504 2.754 6 3.375 6h6c.621 0 1.125.504 1.125 1.125v3.75c0 .621-.504 1.125-1.125 1.125h-6a1.125 1.125 0 0 1-1.125-1.125v-3.75ZM14.25 8.625c0-.621.504-1.125 1.125-1.125h5.25c.621 0 1.125.504 1.125 1.125v8.25c0 .621-.504 1.125-1.125 1.125h-5.25a1.125 1.125 0 0 1-1.125-1.125v-8.25ZM3.75 16.125c0-.621.504-1.125 1.125-1.125h5.25c.621 0 1.125.504 1.125 1.125v2.25c0 .621-.504 1.125-1.125 1.125h-5.25a1.125 1.125 0 0 1-1.125-1.125v-2.25Z" />
      </svg>
      Dashboard
    </a>

    <a href="{{ route('PsgOfficer.PsgAddViolation') }}" class="nav-link {{ request()->routeIs('PsgOfficer.PsgAddViolation') ? 'active' : '' }}">
      <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
      </svg>
      Add Minor Violation
    </a>

    <a href="{{ route('PsgOfficer.PsgViolation') }}" class="nav-link {{ request()->routeIs('PsgOfficer.PsgViolation') ? 'active' : '' }}">
      <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
      </svg>
      Minor Violations
    </a>

    <a href="{{ route('PsgOfficer.goodMoralForm') }}" class="nav-link {{ request()->routeIs('PsgOfficer.goodMoralForm') ? 'active' : '' }}">
      <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" />
      </svg>
      Apply for Good Moral
    </a>

    <a href="{{ route('PsgOfficer.personalViolations') }}" class="nav-link {{ request()->routeIs('PsgOfficer.personalViolations') ? 'active' : '' }}">
      <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
        <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275" />
      </svg>
      My Violations
    </a>

    <a href="{{ route('PsgOfficer.applications') }}" class="nav-link {{ request()->routeIs('PsgOfficer.applications') ? 'active' : '' }}">
      <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" />
      </svg>
      My Applications
    </a>

    <form method="POST" action="{{ route('logout') }}" class="nav-logout-form">
      @csrf
      <button type="submit" class="nav-link nav-logout">
        <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
          <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" />
        </svg>
        <span>Logout</span>
      </button>
    </form>
  </x-slot>

  <!-- Header Section -->
  <div class="header-section">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
      <div>
        <h1 class="role-title">PSG Officer Dashboard</h1>
        <p class="welcome-text">Student Government Portal - Violations Management & Good Moral Applications</p>
        <div class="accent-line"></div>
      </div>
      <div style="display: flex; gap: 12px; align-items: center;">
        <a href="{{ route('PsgOfficer.goodMoralForm') }}" class="btn-primary">
          <svg style="width: 16px; height: 16px; margin-right: 8px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
          </svg>
          Apply for Good Moral
        </a>
      </div>
    </div>
  </div>

  <!-- Personal Overview -->
  <div class="stats-grid">
    <div class="stat-card" style="border-top-color: var(--primary-green);">
      <div style="display: flex; align-items: center; gap: 16px;">
        <div style="background: var(--primary-green); color: white; padding: 16px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
          <svg style="width: 32px; height: 32px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
          </svg>
        </div>
        <div>
          <div class="stat-number">{{ isset($personalViolations) && $personalViolations->isEmpty() ? 'Clean' : (isset($personalViolations) ? $personalViolations->count() : '0') }}</div>
          <div class="stat-label">Personal Record</div>
        </div>
      </div>
    </div>

    <div class="stat-card" style="border-top-color: #007bff;">
      <div style="display: flex; align-items: center; gap: 16px;">
        <div style="background: #007bff; color: white; padding: 16px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
          <svg style="width: 32px; height: 32px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
          </svg>
        </div>
        <div>
          <div class="stat-number">{{ isset($existingApplications) ? $existingApplications->count() : '0' }}</div>
          <div class="stat-label">My Applications</div>
        </div>
      </div>
    </div>

    <div class="stat-card" style="border-top-color: var(--primary-yellow);">
      <div style="display: flex; align-items: center; gap: 16px;">
        <div style="background: var(--primary-yellow); color: #333; padding: 16px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
          <svg style="width: 32px; height: 32px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
          </svg>
        </div>
        <div>
          <div class="stat-number">{{ isset($availableCertificates) && !empty($availableCertificates) ? 'Available' : 'N/A' }}</div>
          <div class="stat-label">Certificate Eligibility</div>
        </div>
      </div>
    </div>

    <div class="stat-card" style="border-top-color: #6f42c1;">
      <div style="display: flex; align-items: center; gap: 16px;">
        <div style="background: #6f42c1; color: white; padding: 16px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
          <svg style="width: 32px; height: 32px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
          </svg>
        </div>
        <div>
          <div class="stat-number">PSG</div>
          <div class="stat-label">Officer Status</div>
        </div>
      </div>
    </div>
  </div>

  <!-- Quick Actions -->
  <div class="stats-grid">
    <div class="stat-card">
      <div style="display: flex; align-items: center; gap: 16px;">
        <div style="background: var(--primary-green); color: white; padding: 16px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
          <svg style="width: 32px; height: 32px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
          </svg>
        </div>
        <div>
          <div class="stat-number">Add</div>
          <div class="stat-label">Minor Violation</div>
          <a href="{{ route('PsgOfficer.PsgAddViolation') }}" class="btn-primary" style="margin-top: 8px; padding: 8px 16px; font-size: 14px;">Create New</a>
        </div>
      </div>
    </div>

    <div class="stat-card">
      <div style="display: flex; align-items: center; gap: 16px;">
        <div style="background: #e74c3c; color: white; padding: 16px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
          <svg style="width: 32px; height: 32px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
          </svg>
        </div>
        <div>
          <div class="stat-number">View</div>
          <div class="stat-label">All Violations</div>
          <a href="{{ route('PsgOfficer.PsgViolation') }}" class="btn-primary" style="margin-top: 8px; padding: 8px 16px; font-size: 14px;">View List</a>
        </div>
      </div>
    </div>

    <div class="stat-card">
      <div style="display: flex; align-items: center; gap: 16px;">
        <div style="background: #007bff; color: white; padding: 16px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
          <svg style="width: 32px; height: 32px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
          </svg>
        </div>
        <div>
          <div class="stat-number">Apply</div>
          <div class="stat-label">Good Moral Certificate</div>
          <a href="{{ route('PsgOfficer.goodMoralForm') }}" class="btn-primary" style="margin-top: 8px; padding: 8px 16px; font-size: 14px;">Apply Now</a>
        </div>
      </div>
    </div>

    <div class="stat-card">
      <div style="display: flex; align-items: center; gap: 16px;">
        <div style="background: #6f42c1; color: white; padding: 16px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
          <svg style="width: 32px; height: 32px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
          </svg>
        </div>
        <div>
          <div class="stat-number">{{ isset($personalViolations) ? $personalViolations->count() : 0 }}</div>
          <div class="stat-label">My Violations</div>
          <a href="{{ route('PsgOfficer.personalViolations') }}" class="btn-primary" style="margin-top: 8px; padding: 8px 16px; font-size: 14px;">View Details</a>
        </div>
      </div>
    </div>
  </div>

  <!-- Personal Status Section -->
  @if(isset($personalViolations) && isset($availableCertificates))
  <div class="header-section">
    <h3 style="color: var(--primary-green); margin-bottom: 16px; font-size: 1.2rem;">Personal Status & Good Moral Eligibility</h3>

    @if ($personalViolations->isEmpty())
    <div style="background: #d4edda; color: #155724; padding: 20px; border-radius: 8px; border-left: 4px solid #28a745; display: flex; align-items: center; gap: 16px; margin-bottom: 20px;">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="height: 24px; width: 24px; color: #28a745;">
        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
      </svg>
      <div>
        <strong>No Violations</strong>
        <p style="margin: 4px 0 0 0;">You have no existing violations. You are eligible to apply for a Good Moral Certificate.</p>
      </div>
    </div>
    @else
    <div style="background: #fff3cd; color: #856404; padding: 20px; border-radius: 8px; border-left: 4px solid #ffc107; display: flex; align-items: center; gap: 16px; margin-bottom: 20px;">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="height: 24px; width: 24px; color: #856404;">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
      </svg>
      <div>
        <strong>{{ $personalViolations->count() }} Violation(s) Found</strong>
        <p style="margin: 4px 0 0 0;">You have unresolved violations. You can apply for a Certificate of Residency instead.</p>
      </div>
    </div>
    @endif

    @if(!empty($availableCertificates))
    <div style="background: #e8f5e8; padding: 16px; border-radius: 8px; margin-bottom: 24px; border-left: 4px solid var(--primary-green);">
      <h4 style="color: var(--primary-green); margin: 0 0 8px 0; font-size: 1rem;">{{ $availableCertificates[0]['name'] }}</h4>
      <p style="color: #333; margin: 0; font-size: 14px;">{{ $availableCertificates[0]['description'] }}</p>
      @if (!$personalViolations->isEmpty())
      <p style="color: #856404; margin: 8px 0 0 0; font-size: 14px; font-weight: 500;">
        <strong>Note:</strong> Due to unresolved violations, you can only apply for a Certificate of Residency at this time.
      </p>
      @endif
    </div>
    @endif

    @if(isset($existingApplications) && $existingApplications->count() > 0)
    <div style="background: white; border-radius: 8px; border: 1px solid #e9ecef; overflow: hidden; margin-bottom: 20px;">
      <div style="background: #f8f9fa; padding: 16px; border-bottom: 1px solid #e9ecef;">
        <h4 style="margin: 0; color: #333; font-size: 1rem;">Recent Applications</h4>
      </div>
      <div style="padding: 16px;">
        @foreach($existingApplications as $app)
        <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px 0; border-bottom: 1px solid #f1f3f4;">
          <div>
            <div style="font-weight: 500; color: #333;">{{ $app->reference_number }}</div>
            <div style="font-size: 14px; color: #666;">{{ ucfirst(str_replace('_', ' ', $app->certificate_type)) }} • {{ $app->created_at->format('M d, Y') }}</div>
          </div>
          <div>
            @php
              $statusColor = '#6c757d';
              $statusText = 'Pending';
              if ($app->application_status) {
                if (str_contains($app->application_status, 'Approved')) {
                  $statusColor = '#28a745';
                  $statusText = 'Approved';
                } elseif (str_contains($app->application_status, 'Rejected')) {
                  $statusColor = '#dc3545';
                  $statusText = 'Rejected';
                }
              }
            @endphp
            <span style="display: inline-block; padding: 4px 8px; background: {{ $statusColor }}20; color: {{ $statusColor }}; border-radius: 12px; font-size: 12px; font-weight: 500;">
              {{ $statusText }}
            </span>
          </div>
        </div>
        @endforeach
        <div style="text-align: center; margin-top: 16px;">
          <a href="{{ route('PsgOfficer.applications') }}" style="color: var(--primary-green); text-decoration: none; font-weight: 500; font-size: 14px;">View All Applications →</a>
        </div>
      </div>
    </div>
    @endif
  </div>
  @endif



  <!-- Information Section -->
  <div class="content-section">
    <h3 class="section-title">PSG Officer Guidelines & Features</h3>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-top: 20px;">
      <div style="padding: 20px; background: #f8f9fa; border-radius: 8px; border-left: 4px solid var(--primary-green);">
        <h4 style="color: var(--primary-green); font-weight: 600; margin-bottom: 12px;">Minor Violations Only</h4>
        <p style="color: #666; line-height: 1.6;">As a PSG Officer, you can only issue minor violations. Major violations must be handled by other authorized personnel.</p>
      </div>

      <div style="padding: 20px; background: #f8f9fa; border-radius: 8px; border-left: 4px solid #007bff;">
        <h4 style="color: #007bff; font-weight: 600; margin-bottom: 12px;">Good Moral Applications</h4>
        <p style="color: #666; line-height: 1.6;">You can now apply for Good Moral Certificates or Certificate of Residency. Your eligibility depends on your personal violation record.</p>
      </div>

      <div style="padding: 20px; background: #f8f9fa; border-radius: 8px; border-left: 4px solid #6f42c1;">
        <h4 style="color: #6f42c1; font-weight: 600; margin-bottom: 12px;">Personal Violations</h4>
        <p style="color: #666; line-height: 1.6;">Monitor violations issued against you. A clean record allows you to apply for Good Moral Certificates.</p>
      </div>

      <div style="padding: 20px; background: #f8f9fa; border-radius: 8px; border-left: 4px solid var(--primary-yellow);">
        <h4 style="color: #333; font-weight: 600; margin-bottom: 12px;">Proper Documentation</h4>
        <p style="color: #666; line-height: 1.6;">Ensure all violation details are accurately recorded including student information, violation description, and date/time.</p>
      </div>

      <div style="padding: 20px; background: #f8f9fa; border-radius: 8px; border-left: 4px solid #e74c3c;">
        <h4 style="color: #e74c3c; font-weight: 600; margin-bottom: 12px;">Follow Protocol</h4>
        <p style="color: #666; line-height: 1.6;">All violations will be reviewed by the Dean and Admin for approval. Maintain professionalism in all interactions.</p>
      </div>

      <div style="padding: 20px; background: #f8f9fa; border-radius: 8px; border-left: 4px solid #17a2b8;">
        <h4 style="color: #17a2b8; font-weight: 600; margin-bottom: 12px;">Application Tracking</h4>
        <p style="color: #666; line-height: 1.6;">Track your Good Moral Certificate applications through the approval process from Registrar to Dean to Administrator.</p>
      </div>
    </div>
  </div>

</x-dashboard-layout>