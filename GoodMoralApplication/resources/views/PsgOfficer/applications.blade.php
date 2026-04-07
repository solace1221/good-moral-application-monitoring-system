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
      <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" />
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
        <h1 class="role-title">My Good Moral Applications</h1>
        <p class="welcome-text">Track your Good Moral Certificate and Certificate of Residency applications</p>
        <div class="accent-line"></div>
      </div>
      <div style="display: flex; gap: 12px; align-items: center;">
        <div style="padding: 12px 16px; background: var(--light-green); border-radius: 8px; font-size: 14px; color: var(--primary-green); font-weight: 600;">
          {{ $applications->total() }} Total Application{{ $applications->total() !== 1 ? 's' : '' }}
        </div>
        <a href="{{ route('PsgOfficer.goodMoralForm') }}" class="btn-primary">
          <svg style="width: 16px; height: 16px; margin-right: 8px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
          </svg>
          New Application
        </a>
        <a href="{{ route('PsgOfficer.dashboard') }}" class="btn-secondary">
          <svg style="width: 16px; height: 16px; margin-right: 8px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
          </svg>
          Back to Dashboard
        </a>
      </div>
    </div>
  </div>

  <!-- Applications Table -->
  <div style="background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); overflow: hidden; margin-bottom: 24px;">
    <div style="padding: 24px; border-bottom: 1px solid #e9ecef;">
      <h2 style="margin: 0; color: var(--primary-green); font-size: 1.25rem; font-weight: 600;">Application History</h2>
    </div>

    @if ($applications->isEmpty())
    <div style="padding: 48px; text-align: center; color: #6c757d;">
      <svg style="width: 64px; height: 64px; margin: 0 auto 16px; color: #dee2e6;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
      </svg>
      <p style="margin: 0; font-size: 1.1rem; font-weight: 500;">No applications found</p>
      <p style="margin: 8px 0 16px; font-size: 0.9rem;">You haven't submitted any Good Moral Certificate applications yet</p>
      <a href="{{ route('PsgOfficer.goodMoralForm') }}" class="btn-primary">
        <svg style="width: 16px; height: 16px; margin-right: 8px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
        </svg>
        Submit Your First Application
      </a>
    </div>
    @else
    <div style="overflow-x: auto;">
      <table style="width: 100%; border-collapse: collapse;">
        <thead>
          <tr style="background: #f8f9fa; border-bottom: 2px solid #e9ecef;">
            <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057; font-size: 14px;">Reference Number</th>
            <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057; font-size: 14px;">Certificate Type</th>
            <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057; font-size: 14px;">Purpose</th>
            <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057; font-size: 14px;">Copies</th>
            <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057; font-size: 14px;">Date Applied</th>
            <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057; font-size: 14px;">Status</th>
          </tr>
        </thead>
        <tbody>
          @foreach($applications as $application)
          <tr style="border-bottom: 1px solid #e9ecef; transition: background-color 0.2s ease;" 
              onmouseover="this.style.backgroundColor='#f8f9fa'" 
              onmouseout="this.style.backgroundColor='transparent'">
            <td style="padding: 16px; color: #495057; font-size: 14px;">
              <div style="font-weight: 500; color: #333; font-family: monospace;">{{ $application->reference_number }}</div>
            </td>
            <td style="padding: 16px; color: #495057; font-size: 14px;">
              <span style="display: inline-block; padding: 6px 12px; 
                           background: {{ $application->certificate_type === 'good_moral' ? '#28a74520' : '#17a2b820' }}; 
                           color: {{ $application->certificate_type === 'good_moral' ? '#28a745' : '#17a2b8' }}; 
                           border-radius: 20px; font-size: 12px; font-weight: 500; text-transform: capitalize;">
                {{ str_replace('_', ' ', $application->certificate_type) }}
              </span>
            </td>
            <td style="padding: 16px; color: #495057; font-size: 14px;">
              <div style="max-width: 200px;">
                @if(is_array($application->reason))
                  {{ implode(', ', $application->reason) }}
                @else
                  {{ $application->reason }}
                @endif
              </div>
            </td>
            <td style="padding: 16px; color: #495057; font-size: 14px; text-align: center;">
              <span style="font-weight: 500; color: #333;">{{ $application->number_of_copies }}</span>
            </td>
            <td style="padding: 16px; color: #495057; font-size: 14px;">
              <div style="font-weight: 500; color: #333;">{{ $application->created_at->format('M d, Y') }}</div>
              <div style="font-size: 12px; color: #6c757d;">{{ $application->created_at->format('h:i A') }}</div>
            </td>
            <td style="padding: 16px; color: #495057; font-size: 14px;">
              @php
                $statusColor = '#6c757d';
                $statusText = 'Pending';
                $statusBg = '#6c757d20';
                
                if ($application->application_status) {
                  if (str_contains($application->application_status, 'Approved by Administrator')) {
                    $statusColor = '#28a745';
                    $statusText = 'Ready for Print';
                    $statusBg = '#28a74520';
                  } elseif (str_contains($application->application_status, 'Approved by Dean')) {
                    $statusColor = '#17a2b8';
                    $statusText = 'Dean Approved';
                    $statusBg = '#17a2b820';
                  } elseif (str_contains($application->application_status, 'Approved By Registrar')) {
                    $statusColor = '#007bff';
                    $statusText = 'Registrar Approved';
                    $statusBg = '#007bff20';
                  } elseif (str_contains($application->application_status, 'Rejected')) {
                    $statusColor = '#dc3545';
                    $statusText = 'Rejected';
                    $statusBg = '#dc354520';
                  } elseif (str_contains($application->application_status, 'Ready for Pickup')) {
                    $statusColor = '#28a745';
                    $statusText = 'Ready for Pickup';
                    $statusBg = '#28a74520';
                  }
                } else {
                  $statusColor = '#ffc107';
                  $statusText = 'Pending Review';
                  $statusBg = '#ffc10720';
                }
              @endphp
              <span style="display: inline-block; padding: 6px 12px; background: {{ $statusBg }}; color: {{ $statusColor }}; border-radius: 20px; font-size: 12px; font-weight: 500;">
                {{ $statusText }}
              </span>
              @if($application->application_status)
              <div style="font-size: 11px; color: #6c757d; margin-top: 4px; max-width: 150px;">
                {{ $application->application_status }}
              </div>
              @endif
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
    @if($applications->hasPages())
    <div style="padding: 20px; border-top: 1px solid #e9ecef; display: flex; justify-content: center;">
      {{ $applications->links() }}
    </div>
    @endif
    @endif
  </div>

  <!-- Information Section -->
  <div class="header-section">
    <h3 style="color: var(--primary-green); margin-bottom: 16px; font-size: 1.2rem;">Application Process Guide</h3>
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

</x-dashboard-layout>
