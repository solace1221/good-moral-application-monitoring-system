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
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
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
        <h1 class="role-title">My Minor Violations</h1>
        <p class="welcome-text">View and manage minor violations you have issued</p>
        <div class="accent-line"></div>
      </div>
      <div style="display: flex; gap: 12px; align-items: center;">
        <div style="padding: 12px 16px; background: var(--light-green); border-radius: 8px; font-size: 14px; color: var(--primary-green); font-weight: 600;">
          {{ $violations->total() }} Total Violation{{ $violations->total() !== 1 ? 's' : '' }}
        </div>
      </div>
    </div>
  </div>

  <!-- Main Content -->
  <div class="header-section">
    <!-- Status Messages -->
    @if(session('success'))
    <div style="margin-bottom: 24px; padding: 16px; background: #d4edda; color: #155724; border: 1px solid #c3e6cb; border-radius: 8px;">
      {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div style="margin-bottom: 24px; padding: 16px; background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; border-radius: 8px;">
      {{ session('error') }}
    </div>
    @endif

    <!-- Quick Actions -->
    <div style="margin-bottom: 24px; display: flex; gap: 12px; flex-wrap: wrap;">
      <a href="{{ route('PsgOfficer.PsgAddViolation') }}" class="btn-primary">
        <svg style="width: 16px; height: 16px; margin-right: 8px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        Add New Violation
      </a>
    </div>

    <!-- Violations Table -->
    @if($violations->isEmpty())
    <div style="text-align: center; padding: 48px; background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
      <svg style="width: 64px; height: 64px; margin: 0 auto 16px; color: #9ca3af;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
      </svg>
      <h3 style="margin: 0 0 8px; color: #374151; font-size: 1.25rem;">No Minor Violations Found</h3>
      <p style="margin: 0; color: #6b7280;">You haven't issued any minor violations yet.</p>
    </div>
    @else
    <div style="background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); overflow: hidden;">
      <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;">
          <thead>
            <tr style="background: #f8f9fa; border-bottom: 2px solid #e9ecef;">
              <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057; font-size: 14px;">Student ID</th>
              <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057; font-size: 14px;">Student Name</th>
              <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057; font-size: 14px;">Department</th>
              <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057; font-size: 14px;">Course</th>
              <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057; font-size: 14px;">Violation</th>
              <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057; font-size: 14px;">Date Issued</th>
              <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057; font-size: 14px;">Status</th>
            </tr>
          </thead>
          <tbody>
            @foreach($violations as $violation)
            <tr style="border-bottom: 1px solid #e9ecef; transition: background-color 0.2s ease;" 
                onmouseover="this.style.backgroundColor='#f8f9fa'" 
                onmouseout="this.style.backgroundColor='transparent'">
              <td style="padding: 16px; color: #495057; font-size: 14px; font-weight: 500;">
                {{ $violation->student_id }}
              </td>
              <td style="padding: 16px; color: #495057; font-size: 14px;">
                {{ $violation->first_name }} {{ $violation->middle_name }} {{ $violation->last_name }}
              </td>
              <td style="padding: 16px; color: #495057; font-size: 14px;">
                <span style="display: inline-block; padding: 4px 8px; background: #e3f2fd; color: #1976d2; border-radius: 4px; font-size: 12px; font-weight: 500;">
                  {{ $violation->department }}
                </span>
              </td>
              <td style="padding: 16px; color: #495057; font-size: 14px;">
                {{ $violation->course }}
              </td>
              <td style="padding: 16px; color: #495057; font-size: 14px;">
                <div style="max-width: 200px;">
                  <strong style="color: #333;">{{ $violation->violation }}</strong>
                  @if($violation->description)
                    <div style="font-size: 12px; color: #666; margin-top: 4px;">
                      {{ Str::limit($violation->description, 50) }}
                    </div>
                  @endif
                </div>
              </td>
              <td style="padding: 16px; color: #6c757d; font-size: 14px;">
                {{ $violation->created_at->format('M j, Y') }}
                <div style="font-size: 12px; color: #999;">
                  {{ $violation->created_at->format('g:i A') }}
                </div>
              </td>
              <td style="padding: 16px; color: #495057; font-size: 14px;">
                <span style="display: inline-block; padding: 6px 12px; background: #ffc107; color: #333; border-radius: 20px; font-size: 12px; font-weight: 500; text-transform: uppercase;">
                  Minor
                </span>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      @if($violations->hasPages())
      <div style="padding: 20px; border-top: 1px solid #e9ecef; background: #f8f9fa;">
        <div style="display: flex; justify-content: between; align-items: center; flex-wrap: wrap; gap: 16px;">
          <div style="color: #6c757d; font-size: 14px;">
            Showing {{ $violations->firstItem() }} to {{ $violations->lastItem() }} of {{ $violations->total() }} violations
          </div>
          <div style="display: flex; gap: 8px;">
            @if($violations->onFirstPage())
              <span style="padding: 8px 12px; background: #e9ecef; color: #6c757d; border-radius: 4px; font-size: 14px;">Previous</span>
            @else
              <a href="{{ $violations->previousPageUrl() }}" style="padding: 8px 12px; background: var(--primary-green); color: white; border-radius: 4px; font-size: 14px; text-decoration: none;">Previous</a>
            @endif

            @if($violations->hasMorePages())
              <a href="{{ $violations->nextPageUrl() }}" style="padding: 8px 12px; background: var(--primary-green); color: white; border-radius: 4px; font-size: 14px; text-decoration: none;">Next</a>
            @else
              <span style="padding: 8px 12px; background: #e9ecef; color: #6c757d; border-radius: 4px; font-size: 14px;">Next</span>
            @endif
          </div>
        </div>
      </div>
      @endif
    </div>
    @endif
  </div>

  <!-- Information Section -->
  <div class="header-section">
    <h3 style="color: var(--primary-green); margin-bottom: 16px; font-size: 1.2rem;">PSG Officer Guidelines</h3>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
      <div style="padding: 20px; background: #fff3cd; border-radius: 8px; border-left: 4px solid #ffc107;">
        <h4 style="color: #856404; font-weight: 600; margin-bottom: 12px;">⚠️ Minor Violations Only</h4>
        <p style="color: #856404; line-height: 1.6; margin: 0;">As a PSG Officer, you can only issue minor violations. Major violations must be handled by authorized administrators.</p>
      </div>
      
      <div style="padding: 20px; background: #d4edda; border-radius: 8px; border-left: 4px solid #28a745;">
        <h4 style="color: #155724; font-weight: 600; margin-bottom: 12px;">✅ Proper Documentation</h4>
        <p style="color: #155724; line-height: 1.6; margin: 0;">Ensure all violation details are accurately recorded including student information, violation type, and description.</p>
      </div>
      
      <div style="padding: 20px; background: #d1ecf1; border-radius: 8px; border-left: 4px solid #17a2b8;">
        <h4 style="color: #0c5460; font-weight: 600; margin-bottom: 12px;">📋 Follow Procedures</h4>
        <p style="color: #0c5460; line-height: 1.6; margin: 0;">Follow proper procedures when issuing violations and ensure students understand the violation and consequences.</p>
      </div>
    </div>
  </div>

</x-dashboard-layout>
