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
        <h1 class="role-title">My Personal Violations</h1>
        <p class="welcome-text">View violations issued against you as a PSG Officer</p>
        <div class="accent-line"></div>
      </div>
      <div style="display: flex; gap: 12px; align-items: center;">
        <div style="padding: 12px 16px; background: var(--light-green); border-radius: 8px; font-size: 14px; color: var(--primary-green); font-weight: 600;">
          {{ $violations->total() }} Total Violation{{ $violations->total() !== 1 ? 's' : '' }}
        </div>
        <a href="{{ route('PsgOfficer.dashboard') }}" class="btn-secondary">
          <svg style="width: 16px; height: 16px; margin-right: 8px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
          </svg>
          Back to Dashboard
        </a>
      </div>
    </div>
  </div>

  <!-- Violations Table -->
  <div style="background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); overflow: hidden; margin-bottom: 24px;">
    <div style="padding: 24px; border-bottom: 1px solid #e9ecef;">
      <h2 style="margin: 0; color: var(--primary-green); font-size: 1.25rem; font-weight: 600;">Personal Violations Record</h2>
    </div>

    @if ($violations->isEmpty())
    <div style="padding: 48px; text-align: center; color: #6c757d;">
      <svg style="width: 64px; height: 64px; margin: 0 auto 16px; color: #28a745;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
      </svg>
      <p style="margin: 0; font-size: 1.1rem; font-weight: 500; color: #28a745;">No violations found</p>
      <p style="margin: 8px 0 0; font-size: 0.9rem;">You have a clean record with no violations issued against you</p>
    </div>
    @else
    <div style="overflow-x: auto;">
      <table style="width: 100%; border-collapse: collapse;">
        <thead>
          <tr style="background: #f8f9fa; border-bottom: 2px solid #e9ecef;">
            <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057; font-size: 14px;">Violation Type</th>
            <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057; font-size: 14px;">Description</th>
            <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057; font-size: 14px;">Issued By</th>
            <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057; font-size: 14px;">Date Issued</th>
            <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057; font-size: 14px;">Status</th>
          </tr>
        </thead>
        <tbody>
          @foreach($violations as $violation)
          <tr style="border-bottom: 1px solid #e9ecef; transition: background-color 0.2s ease;" 
              onmouseover="this.style.backgroundColor='#f8f9fa'" 
              onmouseout="this.style.backgroundColor='transparent'">
            <td style="padding: 16px; color: #495057; font-size: 14px;">
              <span style="display: inline-block; padding: 6px 12px; 
                           background: {{ $violation->offense_type === 'minor' ? '#28a74520' : '#dc354520' }}; 
                           color: {{ $violation->offense_type === 'minor' ? '#28a745' : '#dc3545' }}; 
                           border-radius: 20px; font-size: 12px; font-weight: 500; text-transform: uppercase;">
                {{ $violation->offense_type }}
              </span>
            </td>
            <td style="padding: 16px; color: #495057; font-size: 14px;">
              <div style="font-weight: 500; color: #333; margin-bottom: 4px;">{{ $violation->violation }}</div>
              @if($violation->ref_num)
              <div style="font-size: 12px; color: #6c757d;">Ref: {{ $violation->ref_num }}</div>
              @endif
            </td>
            <td style="padding: 16px; color: #495057; font-size: 14px;">
              <div style="font-weight: 500; color: #333;">{{ $violation->added_by }}</div>
            </td>
            <td style="padding: 16px; color: #495057; font-size: 14px;">
              <div style="font-weight: 500; color: #333;">{{ $violation->created_at->format('M d, Y') }}</div>
              <div style="font-size: 12px; color: #6c757d;">{{ $violation->created_at->format('h:i A') }}</div>
            </td>
            <td style="padding: 16px; color: #495057; font-size: 14px;">
              @php
                $statusColor = '#6c757d';
                $statusText = 'Pending';
                $statusBg = '#6c757d20';
                
                switch($violation->status) {
                  case '0':
                    $statusColor = '#ffc107';
                    $statusText = 'Pending';
                    $statusBg = '#ffc10720';
                    break;
                  case '1':
                    $statusColor = '#17a2b8';
                    $statusText = 'Under Review';
                    $statusBg = '#17a2b820';
                    break;
                  case '2':
                    $statusColor = '#28a745';
                    $statusText = 'Resolved';
                    $statusBg = '#28a74520';
                    break;
                  default:
                    $statusColor = '#6c757d';
                    $statusText = 'Unknown';
                    $statusBg = '#6c757d20';
                }
              @endphp
              <span style="display: inline-block; padding: 6px 12px; background: {{ $statusBg }}; color: {{ $statusColor }}; border-radius: 20px; font-size: 12px; font-weight: 500;">
                {{ $statusText }}
              </span>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
    @if($violations->hasPages())
    <div style="padding: 20px; border-top: 1px solid #e9ecef; display: flex; justify-content: center;">
      {{ $violations->links() }}
    </div>
    @endif
    @endif
  </div>

  <!-- Information Section -->
  <div class="header-section">
    <h3 style="color: var(--primary-green); margin-bottom: 16px; font-size: 1.2rem;">Understanding Your Violations</h3>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
      <div style="padding: 20px; background: #fff3cd; border-radius: 8px; border-left: 4px solid #ffc107;">
        <h4 style="color: #856404; font-weight: 600; margin-bottom: 12px;">📋 Violation Status</h4>
        <ul style="color: #856404; line-height: 1.6; margin: 0; padding-left: 20px;">
          <li><strong>Pending:</strong> Violation has been reported and is awaiting review</li>
          <li><strong>Under Review:</strong> Violation is being processed by administrators</li>
          <li><strong>Resolved:</strong> Violation has been addressed and closed</li>
        </ul>
      </div>

      <div style="padding: 20px; background: #d1ecf1; border-radius: 8px; border-left: 4px solid #17a2b8;">
        <h4 style="color: #0c5460; font-weight: 600; margin-bottom: 12px;">⚖️ Impact on Applications</h4>
        <p style="color: #0c5460; line-height: 1.6; margin: 0;">Unresolved violations may affect your eligibility for Good Moral Certificates. You may still apply for Certificate of Residency.</p>
      </div>

      <div style="padding: 20px; background: #d4edda; border-radius: 8px; border-left: 4px solid #28a745;">
        <h4 style="color: #155724; font-weight: 600; margin-bottom: 12px;">✅ Resolution Process</h4>
        <p style="color: #155724; line-height: 1.6; margin: 0;">Contact the issuing authority or Student Affairs Office if you need clarification about any violation or wish to appeal.</p>
      </div>
    </div>
  </div>

</x-dashboard-layout>
