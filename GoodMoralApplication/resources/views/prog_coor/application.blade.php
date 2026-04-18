<x-dashboard-layout>
  <x-slot name="roleTitle">Program Coordinator</x-slot>

  <x-slot name="navigation">
    <x-prog-coor-navigation />
  </x-slot>

  <!-- Header Section -->
  <div class="header-section">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
      <div>
        <h1 class="role-title">Certificate Applications</h1>
        <p class="welcome-text">Review and approve Good Moral and Residency certificate applications</p>
        <div class="accent-line"></div>
      </div>
      <div style="display: flex; gap: 12px; align-items: center;">
        <div style="padding: 12px 16px; background: var(--light-green); border-radius: 8px; font-size: 14px; color: var(--primary-green); font-weight: 600;">
          {{ $applications['all_new']->count() }} Total Application{{ $applications['all_new']->count() !== 1 ? 's' : '' }}
        </div>
      </div>
    </div>
  </div>

  <!-- Main Content -->
  <div class="header-section">
    <!-- Status Messages -->
    @include('shared.alerts.flash')

    <!-- Filter Tabs -->
    <div style="background: white; border-radius: 12px 12px 0 0; box-shadow: 0 2px 8px rgba(0,0,0,0.1); margin-bottom: 0;">
      <div style="display: flex; border-bottom: 1px solid #e9ecef;">
        <button onclick="showTab('all')" id="tab-all" class="tab-button active" style="flex: 1; padding: 16px; border: none; background: none; cursor: pointer; font-weight: 600; color: var(--primary-green); border-bottom: 3px solid var(--primary-green);">
          All Applications ({{ $applications['all_new']->count() }})
        </button>
        <button onclick="showTab('good_moral')" id="tab-good_moral" class="tab-button" style="flex: 1; padding: 16px; border: none; background: none; cursor: pointer; font-weight: 600; color: #6c757d; border-bottom: 3px solid transparent;">
          Good Moral ({{ $applications['good_moral']->count() }})
        </button>
        <button onclick="showTab('residency')" id="tab-residency" class="tab-button" style="flex: 1; padding: 16px; border: none; background: none; cursor: pointer; font-weight: 600; color: #6c757d; border-bottom: 3px solid transparent;">
          Residency ({{ $applications['residency']->count() }})
        </button>
        <button onclick="showTab('history')" id="tab-history" class="tab-button" style="flex: 1; padding: 16px; border: none; background: none; cursor: pointer; font-weight: 600; color: #6c757d; border-bottom: 3px solid transparent;">
          History ({{ $applications['reviewed']->count() }})
        </button>
      </div>
    </div>

    <!-- All Applications Tab -->
    <div id="content-all" class="tab-content" style="background: white; border-radius: 0 0 12px 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); overflow: hidden;">
      @if($applications['all_new']->isEmpty())
      <div style="text-align: center; padding: 48px;">
        <svg style="width: 64px; height: 64px; margin: 0 auto 16px; color: #9ca3af;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
        </svg>
        <h3 style="margin: 0 0 8px; color: #374151; font-size: 1.25rem;">No Applications Found</h3>
        <p style="margin: 0; color: #6b7280;">There are currently no certificate applications to display.</p>
      </div>
      @else
    <div style="background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); overflow: hidden;">
      <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;">
          <thead>
            <tr style="background: #f8f9fa; border-bottom: 2px solid #e9ecef;">
              <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057; font-size: 14px;">Student ID</th>
              <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057; font-size: 14px;">Full Name</th>
              <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057; font-size: 14px;">Department</th>
              <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057; font-size: 14px;">Certificate Type</th>
              <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057; font-size: 14px;">Status</th>
              <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057; font-size: 14px;">Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach($applications['all_new'] as $application)
            <tr style="border-bottom: 1px solid #e9ecef; transition: background-color 0.2s ease;"
                onmouseover="this.style.backgroundColor='#f8f9fa'"
                onmouseout="this.style.backgroundColor='transparent'">
              <td style="padding: 16px; color: #495057; font-size: 14px; font-weight: 500;">{{ $application->student_id }}</td>
              <td style="padding: 16px; color: #495057; font-size: 14px; font-weight: 500;">{{ $application->fullname }}</td>
              <td style="padding: 16px; color: #495057; font-size: 14px;">
                <span style="display: inline-block; padding: 4px 8px; background: var(--light-green); color: var(--primary-green); border-radius: 4px; font-size: 12px; font-weight: 500;">
                  {{ $application->department }}
                </span>
              </td>
              <td style="padding: 16px; color: #495057; font-size: 14px;">
                <span style="display: inline-block; padding: 4px 8px;
                      background: {{ $application->certificate_type === 'good_moral' ? '#e8f5e8' : '#fff3cd' }};
                      color: {{ $application->certificate_type === 'good_moral' ? 'var(--primary-green)' : '#856404' }};
                      border-radius: 4px; font-size: 12px; font-weight: 500;">
                  {{ $application->certificate_type === 'good_moral' ? 'Good Moral' : 'Residency' }}
                </span>
              </td>
              <td style="padding: 16px; color: #495057; font-size: 14px;">
                @php
                  $statusColor = '#ffc107';
                  $statusText = 'Pending Approval';
                @endphp
                <span style="display: inline-block; padding: 6px 12px; background: {{ $statusColor }}20; color: {{ $statusColor }}; border-radius: 20px; font-size: 12px; font-weight: 500;">
                  {{ $statusText }}
                </span>
              </td>
              <td style="padding: 16px; color: #495057; font-size: 14px;">
                <div style="display: flex; gap: 8px; align-items: center;">
                  <!-- View Details Button -->
                  <button onclick="viewGoodMoralDetails({{ json_encode($application) }})"
                          style="background: #007bff; color: white; border: none; padding: 8px 12px; border-radius: 6px; font-size: 12px; font-weight: 500; cursor: pointer; display: flex; align-items: center; gap: 6px; transition: all 0.2s ease;"
                          onmouseover="this.style.background='#0056b3'"
                          onmouseout="this.style.background='#007bff'">
                    <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    View
                  </button>

                  @if(str_contains(strtolower($application->application_status), 'approved by registrar'))
                    <!-- Approve Button -->
                    <button type="button"
                            onclick="openApproveModal({{ $application->id }}, '{{ addslashes($application->fullname) }}', '{{ $application->reference_number }}', '{{ $application->certificate_type === 'good_moral' ? 'Good Moral' : 'Residency' }}')"
                            style="background: #28a745; color: white; border: none; padding: 8px 12px; border-radius: 6px; font-size: 12px; font-weight: 500; cursor: pointer; display: flex; align-items: center; gap: 6px; transition: all 0.2s ease;"
                            onmouseover="this.style.background='#218838'"
                            onmouseout="this.style.background='#28a745'">
                      <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                      </svg>
                      Approve
                    </button>

                    <!-- Reject Button -->
                    <button type="button"
                            onclick="openRejectModal({{ $application->id }})"
                            style="background: #dc3545; color: white; border: none; padding: 8px 12px; border-radius: 6px; font-size: 12px; font-weight: 500; cursor: pointer; display: flex; align-items: center; gap: 6px; transition: all 0.2s ease;"
                            onmouseover="this.style.background='#c82333'"
                            onmouseout="this.style.background='#dc3545'">
                      <svg style="width: 14px; height: 14px;" fill="none" stroke="white" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                      </svg>
                      Reject
                    </button>
                  @else
                    <span style="color: #6c757d; font-size: 12px; font-style: italic; padding: 8px 12px; background: #f8f9fa; border-radius: 6px;">
                      Already Processed
                    </span>
                  @endif
                </div>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
    @endif
  </div>

  <!-- Good Moral Applications Tab -->
  <div id="content-good_moral" class="tab-content" style="display: none; background: white; border-radius: 0 0 12px 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); overflow: hidden;">
    @if($applications['good_moral']->isEmpty())
    <div style="text-align: center; padding: 48px;">
      <svg style="width: 64px; height: 64px; margin: 0 auto 16px; color: #9ca3af;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
      </svg>
      <h3 style="margin: 0 0 8px; color: #374151; font-size: 1.25rem;">No Good Moral Applications</h3>
      <p style="margin: 0; color: #6b7280;">There are currently no Good Moral certificate applications to display.</p>
    </div>
    @else
    <div style="background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); overflow: hidden;">
      <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;">
          <thead>
            <tr style="background: #f8f9fa; border-bottom: 2px solid #e9ecef;">
              <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057; font-size: 14px;">Student ID</th>
              <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057; font-size: 14px;">Full Name</th>
              <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057; font-size: 14px;">Department</th>
              <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057; font-size: 14px;">Status</th>
              <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057; font-size: 14px;">Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach($applications['good_moral'] as $application)
            <tr style="border-bottom: 1px solid #e9ecef; transition: background-color 0.2s ease;"
                onmouseover="this.style.backgroundColor='#f8f9fa'"
                onmouseout="this.style.backgroundColor='transparent'">
              <td style="padding: 16px; color: #495057; font-size: 14px; font-weight: 500;">{{ $application->student_id }}</td>
              <td style="padding: 16px; color: #495057; font-size: 14px; font-weight: 500;">{{ $application->fullname }}</td>
              <td style="padding: 16px; color: #495057; font-size: 14px;">
                <span style="display: inline-block; padding: 4px 8px; background: var(--light-green); color: var(--primary-green); border-radius: 4px; font-size: 12px; font-weight: 500;">
                  {{ $application->department }}
                </span>
              </td>
              <td style="padding: 16px; color: #495057; font-size: 14px;">
                <span style="display: inline-block; padding: 6px 12px; background: #ffc10720; color: #ffc107; border-radius: 20px; font-size: 12px; font-weight: 500;">
                  Pending Approval
                </span>
              </td>
              <td style="padding: 16px; color: #495057; font-size: 14px;">
                <div style="display: flex; gap: 8px; align-items: center;">
                  <button onclick="viewGoodMoralDetails({{ json_encode($application) }})"
                          style="background: #007bff; color: white; border: none; padding: 8px 12px; border-radius: 6px; font-size: 12px; font-weight: 500; cursor: pointer; display: flex; align-items: center; gap: 6px; transition: all 0.2s ease;"
                          onmouseover="this.style.background='#0056b3'"
                          onmouseout="this.style.background='#007bff'">
                    <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    View
                  </button>

                  @if(str_contains(strtolower($application->application_status), 'approved by registrar'))
                    <button type="button"
                            onclick="openApproveModal({{ $application->id }}, '{{ addslashes($application->fullname) }}', '{{ $application->reference_number }}', 'Good Moral')"
                            style="background: #28a745; color: white; border: none; padding: 8px 12px; border-radius: 6px; font-size: 12px; font-weight: 500; cursor: pointer; display: flex; align-items: center; gap: 6px; transition: all 0.2s ease;"
                            onmouseover="this.style.background='#218838'"
                            onmouseout="this.style.background='#28a745'">
                      <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                      </svg>
                      Approve
                    </button>

                    <button type="button"
                            onclick="openRejectModal({{ $application->id }})"
                            style="background: #dc3545; color: white; border: none; padding: 8px 12px; border-radius: 6px; font-size: 12px; font-weight: 500; cursor: pointer; display: flex; align-items: center; gap: 6px; transition: all 0.2s ease;"
                            onmouseover="this.style.background='#c82333'"
                            onmouseout="this.style.background='#dc3545'">
                      <svg style="width: 14px; height: 14px;" fill="none" stroke="white" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                      </svg>
                      Reject
                    </button>
                  @else
                    <span style="color: #6c757d; font-size: 12px; font-style: italic; padding: 8px 12px; background: #f8f9fa; border-radius: 6px;">
                      Already Processed
                    </span>
                  @endif
                </div>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
    @endif
  </div>

  <!-- Residency Applications Tab -->
  <div id="content-residency" class="tab-content" style="display: none; background: white; border-radius: 0 0 12px 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); overflow: hidden;">
    @if($applications['residency']->isEmpty())
    <div style="text-align: center; padding: 48px;">
      <svg style="width: 64px; height: 64px; margin: 0 auto 16px; color: #9ca3af;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
      </svg>
      <h3 style="margin: 0 0 8px; color: #374151; font-size: 1.25rem;">No Residency Applications</h3>
      <p style="margin: 0; color: #6b7280;">There are currently no Residency certificate applications to display.</p>
    </div>
    @else
    <div style="background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); overflow: hidden;">
      <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;">
          <thead>
            <tr style="background: #f8f9fa; border-bottom: 2px solid #e9ecef;">
              <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057; font-size: 14px;">Student ID</th>
              <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057; font-size: 14px;">Full Name</th>
              <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057; font-size: 14px;">Department</th>
              <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057; font-size: 14px;">Status</th>
              <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057; font-size: 14px;">Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach($applications['residency'] as $application)
            <tr style="border-bottom: 1px solid #e9ecef; transition: background-color 0.2s ease;"
                onmouseover="this.style.backgroundColor='#f8f9fa'"
                onmouseout="this.style.backgroundColor='transparent'">
              <td style="padding: 16px; color: #495057; font-size: 14px; font-weight: 500;">{{ $application->student_id }}</td>
              <td style="padding: 16px; color: #495057; font-size: 14px; font-weight: 500;">{{ $application->fullname }}</td>
              <td style="padding: 16px; color: #495057; font-size: 14px;">
                <span style="display: inline-block; padding: 4px 8px; background: var(--light-green); color: var(--primary-green); border-radius: 4px; font-size: 12px; font-weight: 500;">
                  {{ $application->department }}
                </span>
              </td>
              <td style="padding: 16px; color: #495057; font-size: 14px;">
                <span style="display: inline-block; padding: 6px 12px; background: #ffc10720; color: #ffc107; border-radius: 20px; font-size: 12px; font-weight: 500;">
                  Pending Approval
                </span>
              </td>
              <td style="padding: 16px; color: #495057; font-size: 14px;">
                <div style="display: flex; gap: 8px; align-items: center;">
                  <button onclick="viewGoodMoralDetails({{ json_encode($application) }})"
                          style="background: #007bff; color: white; border: none; padding: 8px 12px; border-radius: 6px; font-size: 12px; font-weight: 500; cursor: pointer; display: flex; align-items: center; gap: 6px; transition: all 0.2s ease;"
                          onmouseover="this.style.background='#0056b3'"
                          onmouseout="this.style.background='#007bff'">
                    <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    View
                  </button>

                  @if(str_contains(strtolower($application->application_status), 'approved by registrar'))
                    <button type="button"
                            onclick="openApproveModal({{ $application->id }}, '{{ addslashes($application->fullname) }}', '{{ $application->reference_number }}', 'Residency')"
                            style="background: #28a745; color: white; border: none; padding: 8px 12px; border-radius: 6px; font-size: 12px; font-weight: 500; cursor: pointer; display: flex; align-items: center; gap: 6px; transition: all 0.2s ease;"
                            onmouseover="this.style.background='#218838'"
                            onmouseout="this.style.background='#28a745'">
                      <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                      </svg>
                      Approve
                    </button>

                    <button type="button"
                            onclick="openRejectModal({{ $application->id }})"
                            style="background: #dc3545; color: white; border: none; padding: 8px 12px; border-radius: 6px; font-size: 12px; font-weight: 500; cursor: pointer; display: flex; align-items: center; gap: 6px; transition: all 0.2s ease;"
                            onmouseover="this.style.background='#c82333'"
                            onmouseout="this.style.background='#dc3545'">
                      <svg style="width: 14px; height: 14px;" fill="none" stroke="white" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                      </svg>
                      Reject
                    </button>
                  @else
                    <span style="color: #6c757d; font-size: 12px; font-style: italic; padding: 8px 12px; background: #f8f9fa; border-radius: 6px;">
                      Already Processed
                    </span>
                  @endif
                </div>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
    @endif
  </div>

  <!-- History Tab -->
  <div id="content-history" class="tab-content" style="display: none; background: white; border-radius: 0 0 12px 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); overflow: hidden;">
    @if($applications['reviewed']->isEmpty())
    <div style="text-align: center; padding: 48px;">
      <svg style="width: 64px; height: 64px; margin: 0 auto 16px; color: #9ca3af;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
      </svg>
      <h3 style="margin: 0 0 8px; color: #374151; font-size: 1.25rem;">No Review History</h3>
      <p style="margin: 0; color: #6b7280;">You haven't reviewed any applications yet.</p>
    </div>
    @else
    <div style="background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); overflow: hidden;">
      <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;">
          <thead>
            <tr style="background: #f8f9fa; border-bottom: 2px solid #e9ecef;">
              <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057; font-size: 14px;">Student ID</th>
              <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057; font-size: 14px;">Full Name</th>
              <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057; font-size: 14px;">Certificate Type</th>
              <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057; font-size: 14px;">Decision</th>
              <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057; font-size: 14px;">Reviewed By</th>
              <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057; font-size: 14px;">Date Reviewed</th>
              <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057; font-size: 14px;">Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach($applications['reviewed'] as $application)
            @php
              $isApproved = str_contains($application->application_status, 'Approved by Program Coordinator');
              $isRejected = str_contains($application->application_status, 'Rejected by Program Coordinator');
              // Extract reviewer name from status: "Approved by Program Coordinator: John Doe - ..."
              $reviewerName = 'Unknown';
              if (preg_match('/(Approved|Rejected) by Program Coordinator:\s*([^-]+)/', $application->application_status, $matches)) {
                $reviewerName = trim($matches[2]);
              }
            @endphp
            <tr style="border-bottom: 1px solid #e9ecef; transition: background-color 0.2s ease;"
                onmouseover="this.style.backgroundColor='#f8f9fa'"
                onmouseout="this.style.backgroundColor='transparent'">
              <td style="padding: 16px; color: #495057; font-size: 14px; font-weight: 500;">{{ $application->student_id }}</td>
              <td style="padding: 16px; color: #495057; font-size: 14px; font-weight: 500;">{{ $application->fullname }}</td>
              <td style="padding: 16px; color: #495057; font-size: 14px;">
                <span style="display: inline-block; padding: 4px 8px;
                      background: {{ $application->certificate_type === 'good_moral' ? '#e8f5e8' : '#fff3cd' }};
                      color: {{ $application->certificate_type === 'good_moral' ? 'var(--primary-green)' : '#856404' }};
                      border-radius: 4px; font-size: 12px; font-weight: 500;">
                  {{ $application->certificate_type === 'good_moral' ? 'Good Moral' : 'Residency' }}
                </span>
              </td>
              <td style="padding: 16px; color: #495057; font-size: 14px;">
                @if($isApproved)
                  <span style="display: inline-block; padding: 6px 12px; background: #d4edda; color: #155724; border-radius: 20px; font-size: 12px; font-weight: 500;">
                    Approved
                  </span>
                @elseif($isRejected)
                  <span style="display: inline-block; padding: 6px 12px; background: #f8d7da; color: #721c24; border-radius: 20px; font-size: 12px; font-weight: 500;">
                    Rejected
                  </span>
                @endif
              </td>
              <td style="padding: 16px; color: #495057; font-size: 14px;">
                <span style="display: inline-block; padding: 4px 8px; background: #e2e3e5; color: #383d41; border-radius: 4px; font-size: 12px; font-weight: 500;">
                  {{ $reviewerName }}
                </span>
              </td>
              <td style="padding: 16px; color: #495057; font-size: 14px;">
                {{ $application->updated_at->format('M d, Y h:i A') }}
              </td>
              <td style="padding: 16px; color: #495057; font-size: 14px;">
                <button onclick="viewGoodMoralDetails({{ json_encode($application) }})"
                        style="background: #007bff; color: white; border: none; padding: 8px 12px; border-radius: 6px; font-size: 12px; font-weight: 500; cursor: pointer; display: flex; align-items: center; gap: 6px; transition: all 0.2s ease;"
                        onmouseover="this.style.background='#0056b3'"
                        onmouseout="this.style.background='#007bff'">
                  <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                  </svg>
                  View
                </button>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
    @endif
  </div>

  <!-- Approve Confirmation Modal -->
  <div id="progCoorApproveModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center;">
    <div style="background: white; padding: 24px; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); width: 100%; max-width: 500px; margin: 20px;">
      <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2 style="margin: 0; color: #28a745; font-size: 1.25rem; font-weight: 600;">Approve Application</h2>
        <button onclick="closeApproveModal()" style="background: none; border: none; font-size: 24px; cursor: pointer; color: #6c757d;">&times;</button>
      </div>

      <div style="background: #d4edda; color: #155724; padding: 16px; border-radius: 8px; margin-bottom: 20px;">
        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
          <svg style="width: 24px; height: 24px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
          </svg>
          <span style="font-weight: 600;">Confirm Approval</span>
        </div>
        <p style="margin: 0 0 8px 0;">You are about to approve the following application:</p>
        <div style="margin-left: 10px;">
          <p style="margin: 4px 0;"><strong>Student Name:</strong> <span id="pcApproveStudentName"></span></p>
          <p style="margin: 4px 0;"><strong>Reference Number:</strong> <span id="pcApproveRefNumber"></span></p>
          <p style="margin: 4px 0;"><strong>Certificate Type:</strong> <span id="pcApproveCertType"></span></p>
        </div>
        <p style="margin: 8px 0 0 0; font-style: italic;">This action will move the application to the payment stage. The student will be notified to pay at Business Affairs.</p>
      </div>

      <form id="progCoorApproveForm" method="POST" action="" style="display: flex; justify-content: flex-end; gap: 12px;">
        @csrf
        @method('PATCH')
        <button type="button" onclick="closeApproveModal()"
                style="background: #6c757d; color: white; border: none; padding: 10px 20px; border-radius: 6px; cursor: pointer; font-size: 14px; font-weight: 500;">
          Cancel
        </button>
        <button type="submit"
                style="background: #28a745; color: white; border: none; padding: 10px 20px; border-radius: 6px; cursor: pointer; font-size: 14px; font-weight: 500; display: flex; align-items: center; gap: 8px;">
          <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
          </svg>
          Approve Application
        </button>
      </form>
    </div>
  </div>

  <!-- Shared Reject Modal -->
  <div id="progCoorRejectModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center; padding: 16px;">
    <div style="background: white; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.15); width: 100%; max-width: 500px; max-height: 90vh; display: flex; flex-direction: column;">
      <!-- Fixed Header -->
      <div style="display: flex; justify-content: space-between; align-items: center; padding: 20px 24px; border-bottom: 1px solid #e9ecef; flex-shrink: 0;">
        <h2 style="margin: 0; color: #dc3545; font-size: 1.25rem; font-weight: 600;">Reject Application</h2>
        <button onclick="closeRejectModal()" style="background: none; border: none; font-size: 24px; cursor: pointer; color: #6c757d; line-height: 1;">&times;</button>
      </div>
      <!-- Scrollable Body + Fixed Footer inside form -->
      <form id="progCoorRejectForm" method="POST" action="" style="display: flex; flex-direction: column; overflow: hidden; flex: 1;">
        @csrf
        @method('PATCH')
        <div style="padding: 24px; overflow-y: auto; flex: 1; display: grid; gap: 16px;">
          <div>
            <label style="display: block; font-weight: 600; color: #333; margin-bottom: 8px;">Rejection Reason <span style="color: #dc3545;">*</span></label>
            <select name="rejection_reason" required style="width: 100%; padding: 12px; border: 2px solid #e1e5e9; border-radius: 8px; font-size: 14px; background: white;">
              <option value="">Select a reason</option>
              <option value="Incomplete Documents">Incomplete Documents</option>
              <option value="Invalid Information">Invalid Information</option>
              <option value="Outstanding Violations">Outstanding Violations</option>
              <option value="Eligibility Requirements Not Met">Eligibility Requirements Not Met</option>
              <option value="Department Policy Violation">Department Policy Violation</option>
              <option value="Other">Other</option>
            </select>
          </div>
          <div>
            <label style="display: block; font-weight: 600; color: #333; margin-bottom: 8px;">Additional Details</label>
            <textarea name="rejection_details" rows="4" placeholder="Please provide additional details about the rejection..." style="width: 100%; padding: 12px; border: 2px solid #e1e5e9; border-radius: 8px; font-size: 14px; resize: vertical; box-sizing: border-box;"></textarea>
          </div>
        </div>
        <div style="display: flex; justify-content: flex-end; gap: 12px; padding: 16px 24px; border-top: 1px solid #e9ecef; flex-shrink: 0;">
          <button type="button" onclick="closeRejectModal()" style="background: #6c757d; color: white; border: none; padding: 10px 20px; border-radius: 6px; cursor: pointer; font-size: 14px; font-weight: 500;">Cancel</button>
          <button type="submit" style="background: #dc3545; color: white; border: none; padding: 10px 20px; border-radius: 6px; cursor: pointer; font-size: 14px; font-weight: 500;">Reject Application</button>
        </div>
      </form>
    </div>
  </div>

  <!-- View Details Modal -->
  <div id="detailsModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center; padding: 16px;">
    <div style="background: white; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); width: 100%; max-width: 600px; max-height: 90vh; display: flex; flex-direction: column;">
      <!-- Modal Header -->
      <div style="display: flex; justify-content: space-between; align-items: center; padding: 20px 24px; border-bottom: 1px solid #dee2e6; flex-shrink: 0;">
        <h2 style="margin: 0; color: var(--primary-green); font-size: 1.25rem; font-weight: 600;">Application Details</h2>
        <button onclick="closeModal()" style="background: none; border: none; font-size: 24px; cursor: pointer; color: #6c757d;">&times;</button>
      </div>

      <!-- Modal Body (scrollable) -->
      <div id="modalContent" style="display: grid; gap: 16px; padding: 24px; overflow-y: auto; flex: 1 1 auto;">
        <!-- Content will be populated by JavaScript -->
      </div>
    </div>
  </div>

  <!-- Custom CSS -->
  <style>
    .main-content table td button,
    .main-content table td button:hover,
    .main-content table td button:focus,
    .main-content table td button:active,
    .main-content table td button * {
      color: #ffffff !important;
    }
  </style>

  <!-- JavaScript for modal and tab functionality -->
  <script>
    function showTab(tabName) {
      const tabContents = document.querySelectorAll('.tab-content');
      tabContents.forEach(content => { content.style.display = 'none'; });

      const tabButtons = document.querySelectorAll('.tab-button');
      tabButtons.forEach(button => {
        button.style.color = '#6c757d';
        button.style.borderBottom = '3px solid transparent';
      });

      document.getElementById('content-' + tabName).style.display = 'block';

      const activeButton = document.getElementById('tab-' + tabName);
      activeButton.style.color = 'var(--primary-green)';
      activeButton.style.borderBottom = '3px solid var(--primary-green)';
    }

    function viewGoodMoralDetails(application) {
      const modal = document.getElementById('detailsModal');
      const content = document.getElementById('modalContent');

      content.innerHTML = `
        <div style="display: grid; gap: 12px;">
          <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 8px; padding: 12px; background: #f8f9fa; border-radius: 6px;">
            <strong>Student ID:</strong>
            <span>${application.student_id}</span>
          </div>
          <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 8px; padding: 12px; background: #f8f9fa; border-radius: 6px;">
            <strong>Full Name:</strong>
            <span>${application.fullname}</span>
          </div>
          <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 8px; padding: 12px; background: #f8f9fa; border-radius: 6px;">
            <strong>Gender:</strong>
            <span style="text-transform: capitalize;">${application.gender || 'Not specified'}</span>
          </div>
          <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 8px; padding: 12px; background: #f8f9fa; border-radius: 6px;">
            <strong>Department:</strong>
            <span>${application.department}</span>
          </div>
          <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 8px; padding: 12px; background: #f8f9fa; border-radius: 6px;">
            <strong>Certificate Type:</strong>
            <span>${application.certificate_type === 'good_moral' ? 'Good Moral Certificate' : 'Certificate of Residency'}</span>
          </div>
          <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 8px; padding: 12px; background: #f8f9fa; border-radius: 6px;">
            <strong>Reference Number:</strong>
            <span>${application.reference_number || 'N/A'}</span>
          </div>
          <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 8px; padding: 12px; background: #f8f9fa; border-radius: 6px;">
            <strong>Number of Copies:</strong>
            <span>${application.number_of_copies}</span>
          </div>
          <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 8px; padding: 12px; background: #f8f9fa; border-radius: 6px;">
            <strong>Purpose/Reason:</strong>
            <span>${application.reason}</span>
          </div>
          <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 8px; padding: 12px; background: #f8f9fa; border-radius: 6px;">
            <strong>Application Status:</strong>
            <span style="color: #ffc107; font-weight: 600;">Pending Approval</span>
          </div>
          <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 8px; padding: 12px; background: #f8f9fa; border-radius: 6px;">
            <strong>Applied On:</strong>
            <span>${new Date(application.created_at).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' })}</span>
          </div>
          <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 8px; padding: 12px; background: #f8f9fa; border-radius: 6px;">
            <strong>Course Completed:</strong>
            <span>${application.course_completed || 'N/A'}</span>
          </div>
          <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 8px; padding: 12px; background: #f8f9fa; border-radius: 6px;">
            <strong>Graduation Date:</strong>
            <span>${application.graduation_date || 'N/A'}</span>
          </div>
        </div>
      `;

      modal.style.display = 'flex';
    }

    function closeModal() {
      document.getElementById('detailsModal').style.display = 'none';
    }

    function openRejectModal(applicationId) {
      const modal = document.getElementById('progCoorRejectModal');
      const form = document.getElementById('progCoorRejectForm');
      form.action = `/prog_coor/good-moral/${applicationId}/reject`;
      form.querySelector('select[name="rejection_reason"]').value = '';
      form.querySelector('textarea[name="rejection_details"]').value = '';
      modal.style.display = 'flex';
    }

    function closeRejectModal() {
      document.getElementById('progCoorRejectModal').style.display = 'none';
    }

    function openApproveModal(applicationId, studentName, refNumber, certType) {
      document.getElementById('pcApproveStudentName').textContent = studentName;
      document.getElementById('pcApproveRefNumber').textContent = refNumber || 'N/A';
      document.getElementById('pcApproveCertType').textContent = certType;
      document.getElementById('progCoorApproveForm').action = `/prog_coor/good-moral/${applicationId}/approve`;
      document.getElementById('progCoorApproveModal').style.display = 'flex';
    }

    function closeApproveModal() {
      document.getElementById('progCoorApproveModal').style.display = 'none';
    }

    // Close modals when clicking outside
    document.getElementById('detailsModal').addEventListener('click', function(e) {
      if (e.target === this) closeModal();
    });

    document.getElementById('progCoorRejectModal').addEventListener('click', function(e) {
      if (e.target === this) closeRejectModal();
    });

    document.getElementById('progCoorApproveModal').addEventListener('click', function(e) {
      if (e.target === this) closeApproveModal();
    });
  </script>
</x-dashboard-layout>
