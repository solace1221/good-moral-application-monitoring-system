<x-dashboard-layout>
  <x-slot name="roleTitle">Admin</x-slot>

  <x-slot name="navigation">
    <x-admin-navigation />
  </x-slot>

  <!-- Header Section -->
  <div class="header-section">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
      <div>
        <h1 class="role-title">Pending Applications</h1>
        <p class="welcome-text">Review and approve applications that have been approved by the Dean</p>
      </div>
    </div>
  </div>

  <!-- Main Content -->
  <div class="header-section">
    @include('shared.alerts.flash')

    <!-- Filter Card -->
    <div style="margin-bottom: 24px; padding: 24px; background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 16px;">
        <div>
          <label for="filter_student_id" style="display: block; margin-bottom: 6px; font-weight: 600; color: #333; font-size: 13px;">Student ID</label>
          <input type="text" id="filter_student_id"
                 style="width: 100%; padding: 10px 14px; border: 2px solid #e1e5e9; border-radius: 8px; font-size: 14px; transition: border-color 0.2s ease; box-sizing: border-box;"
                 placeholder="Enter Student ID"
                 oninput="filterApplicationsTable()">
        </div>
        <div>
          <label for="filter_department" style="display: block; margin-bottom: 6px; font-weight: 600; color: #333; font-size: 13px;">Department</label>
          <input type="text" id="filter_department"
                 style="width: 100%; padding: 10px 14px; border: 2px solid #e1e5e9; border-radius: 8px; font-size: 14px; transition: border-color 0.2s ease; box-sizing: border-box;"
                 placeholder="Enter Department"
                 oninput="filterApplicationsTable()">
        </div>
        <div>
          <label for="filter_fullname" style="display: block; margin-bottom: 6px; font-weight: 600; color: #333; font-size: 13px;">Full Name</label>
          <input type="text" id="filter_fullname"
                 style="width: 100%; padding: 10px 14px; border: 2px solid #e1e5e9; border-radius: 8px; font-size: 14px; transition: border-color 0.2s ease; box-sizing: border-box;"
                 placeholder="Enter Full Name"
                 oninput="filterApplicationsTable()">
        </div>
      </div>
      <div style="display: flex; justify-content: flex-end; gap: 8px; align-items: center;">
        <button type="button" onclick="resetApplicationsFilter()" class="btn-outline-reset"
                style="display: inline-flex; align-items: center; gap: 6px; padding: 9px 16px; background: transparent; color: #6c757d; border: 1.5px solid #6c757d; border-radius: 8px; font-size: 13px; font-weight: 500; cursor: pointer; transition: all 0.2s ease;"
                onmouseover="this.style.background='#6c757d'"
                onmouseout="this.style.background='transparent'; this.style.color='#6c757d'">
          <svg style="width: 14px; height: 14px; flex-shrink: 0;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
          </svg>
          Reset
        </button>
        <button type="button" onclick="filterApplicationsTable()" class="btn-green"
                style="display: inline-flex; align-items: center; gap: 6px; padding: 9px 18px; background: #28a745; color: white !important; border: none; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer; transition: all 0.2s ease;"
                onmouseover="this.style.background='#218838'"
                onmouseout="this.style.background='#28a745'">
          <svg style="width: 14px; height: 14px; flex-shrink: 0;" fill="none" stroke="white" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
          </svg>
          Search
        </button>
      </div>
    </div>

    <!-- Applications Table -->
    @if($applications->isEmpty())
    <div style="text-align: center; padding: 48px; background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
      <svg style="width: 64px; height: 64px; margin: 0 auto 16px; color: #9ca3af;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
      </svg>
      <h3 style="margin: 0 0 8px; color: #374151; font-size: 1.25rem;">No Applications Found</h3>
      <p style="margin: 0; color: #6b7280;">There are currently no good moral applications to display.</p>
    </div>
    @else
    <div style="background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); overflow: hidden;">
      <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;">
          <thead>
            <tr style="background: #f8f9fa; border-bottom: 2px solid #e9ecef;">
              <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057; font-size: 14px;">Student ID</th>
              <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057; font-size: 14px;">Department</th>
              <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057; font-size: 14px;">Full Name</th>
              <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057; font-size: 14px;">Status</th>
              <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057; font-size: 14px;">Certificate Type</th>
              <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057; font-size: 14px;">Date Applied</th>
              <th style="padding: 14px 16px; text-align: left; font-weight: 600; color: #495057; font-size: 14px; min-width: 240px;">Actions</th>
            </tr>
          </thead>
          <tbody id="applicationsTableBody">
            @foreach($applications as $application)
            <tr class="app-row"
                data-student-id="{{ strtolower($application->student_id ?? '') }}"
                data-department="{{ strtolower($application->department ?? '') }}"
                data-fullname="{{ strtolower($application->fullname ?? '') }}"
                style="border-bottom: 1px solid #e9ecef; transition: background-color 0.2s ease;"
                onmouseover="this.style.backgroundColor='#f8f9fa'"
                onmouseout="this.style.backgroundColor='transparent'">
              <td style="padding: 16px; color: #495057; font-size: 14px;">{{ $application->student_id }}</td>
              <td style="padding: 16px; color: #495057; font-size: 14px;">
                @php
                  $deptColors = [
                    'SASTE'    => ['bg' => '#dbeafe', 'color' => '#1d4ed8'],
                    'SITE'     => ['bg' => '#e5e7eb', 'color' => '#374151'],
                    'SBAHM'    => ['bg' => '#d1fae5', 'color' => '#065f46'],
                    'SNAHS'    => ['bg' => '#fce7f3', 'color' => '#9d174d'],
                    'GRAD SCH' => ['bg' => '#fef3c7', 'color' => '#92400e'],
                    'SOM'      => ['bg' => '#f3f4f6', 'color' => '#111827'],
                  ];
                  $dept = strtoupper(trim($application->department ?? ''));
                  $deptBg    = $deptColors[$dept]['bg']    ?? '#e3f2fd';
                  $deptColor = $deptColors[$dept]['color'] ?? '#1976d2';
                @endphp
                <span style="display: inline-block; padding: 4px 10px; background: {{ $deptBg }}; color: {{ $deptColor }}; border-radius: 12px; font-size: 12px; font-weight: 600; white-space: nowrap;">
                  {{ $application->department }}
                </span>
              </td>
              <td style="padding: 16px; color: #495057; font-size: 14px; font-weight: 500;">{{ $application->fullname }}</td>
              <td style="padding: 16px; color: #495057; font-size: 14px;">
                @php
                  $statusColor = '#6c757d'; // Default color
                  $statusText = $application->application_status ?? 'Unknown';

                  if (str_contains($statusText, 'Approved by Dean:')) {
                    $statusColor = '#17a2b8'; // Info blue for dean approved
                    $statusText = 'Approved by Dean';
                  } elseif (str_contains($statusText, 'Approved by Administrator')) {
                    $statusColor = '#28a745'; // Green for admin approved
                    $statusText = 'Approved by Administrator';
                  } elseif (str_contains($statusText, 'Rejected by Administrator')) {
                    $statusColor = '#dc3545'; // Red for admin rejected
                    $statusText = 'Rejected by Administrator';
                  } elseif (str_contains($statusText, 'Rejected by Dean:')) {
                    $statusColor = '#dc3545'; // Red for dean rejected
                    $statusText = 'Rejected by Dean';
                  } elseif ($statusText === 'Pending' || $statusText === 'pending') {
                    $statusColor = '#ffc107'; // Yellow for pending
                    $statusText = 'Pending';
                  }
                @endphp
                <span style="display: inline-block; padding: 6px 12px; background: {{ $statusColor }}20; color: {{ $statusColor }}; border-radius: 20px; font-size: 12px; font-weight: 500;">
                  {{ $statusText }}
                </span>
              </td>
              <td style="padding: 16px; color: #495057; font-size: 14px;">
                @php
                  $certType = $application->certificate_type ?? 'good_moral';
                @endphp
                @if($certType === 'residency')
                  <span style="display: inline-block; padding: 4px 10px; background: #e3f2fd; color: #1565c0; border-radius: 12px; font-size: 12px; font-weight: 600;">Residency</span>
                @else
                  <span style="display: inline-block; padding: 4px 10px; background: #e8f5e9; color: #2e7d32; border-radius: 12px; font-size: 12px; font-weight: 600;">Good Moral</span>
                @endif
              </td>
              <td style="padding: 16px; color: #6c757d; font-size: 14px;">{{ $application->created_at->format('M j, Y') }}</td>
              <td style="padding: 14px 16px; color: #495057; font-size: 14px;">
                <div style="display: flex; gap: 8px; align-items: center; flex-wrap: nowrap;">
                  <!-- View Details -->
                  @php
                    $appReceipt = $application->receipts()->orderByDesc('created_at')->first();
                  @endphp
                  <button onclick="viewDetails({{ json_encode($application) }}, {{ json_encode($appReceipt) }})" class="btn-outline-view"
                          style="background: transparent; color: #6c757d; border: 1.5px solid #6c757d; padding: 7px 12px; border-radius: 6px; font-size: 12px; font-weight: 500; cursor: pointer; display: flex; align-items: center; gap: 5px; transition: all 0.2s ease; white-space: nowrap;"
                          onmouseover="this.style.background='#6c757d'"
                          onmouseout="this.style.background='transparent'; this.style.color='#6c757d'"
                          title="View application details">
                    <svg style="width: 13px; height: 13px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    View
                  </button>

                  @if(str_contains($application->application_status ?? '', 'Receipt Uploaded') || str_contains($application->application_status ?? '', 'Approved by Dean:'))
                    <!-- Approve -->
                    <button type="button" class="btn-green"
                            onclick="openApproveModal({{ $application->id }}, '{{ $application->fullname }}', '{{ $application->reference_number ?? 'N/A' }}')"
                            style="background: #28a745; color: white !important; border: none; padding: 7px 12px; border-radius: 6px; font-size: 12px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 5px; transition: all 0.2s ease; white-space: nowrap;"
                            onmouseover="this.style.background='#218838'; this.style.transform='translateY(-1px)'"
                            onmouseout="this.style.background='#28a745'; this.style.transform='translateY(0)'"
                            title="Approve this application">
                      <svg style="width: 13px; height: 13px;" fill="none" stroke="white" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                      </svg>
                      Approve
                    </button>

                    <!-- Decline -->
                    <button type="button" class="btn-outline-decline"
                            onclick="openDeclineModal({{ $application->id }}, '{{ $application->fullname }}', '{{ $application->reference_number ?? 'N/A' }}')"
                            style="background: transparent; color: #dc3545; border: 1.5px solid #dc3545; padding: 7px 12px; border-radius: 6px; font-size: 12px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 5px; transition: all 0.2s ease; white-space: nowrap;"
                            onmouseover="this.style.background='#dc3545'"
                            onmouseout="this.style.background='transparent'; this.style.color='#dc3545'"
                            title="Decline this application">
                      <svg style="width: 13px; height: 13px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                      </svg>
                      Decline
                    </button>
                  @elseif(str_contains($application->application_status ?? '', 'Approved by Administrator'))
                    <span style="color: #28a745; font-size: 12px; font-style: italic; padding: 6px 10px; background: #d4edda; border-radius: 6px; border: 1px solid #c3e6cb; white-space: nowrap;">
                      ✅ Approved
                    </span>
                  @elseif(str_contains($application->application_status ?? '', 'Rejected by Administrator'))
                    <span style="color: #dc3545; font-size: 12px; font-style: italic; padding: 6px 10px; background: #f8d7da; border-radius: 6px; border: 1px solid #f5c6cb; white-space: nowrap;">
                      ❌ Declined
                    </span>
                  @else
                    <span style="color: #6c757d; font-size: 12px; font-style: italic; padding: 6px 10px; background: #f8f9fa; border-radius: 6px; white-space: nowrap;">
                      Awaiting Earlier Steps
                    </span>
                  @endif
                </div>
              </td>
            </tr>
            @endforeach
            <tr id="filterNoResults" style="display: none;">
              <td colspan="7" style="text-align: center; padding: 32px 16px; color: #6b7280; font-size: 14px;">
                <svg style="width: 40px; height: 40px; margin: 0 auto 12px; display: block; color: #d1d5db;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                No applications match the current filter criteria.
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
    @endif

    {{-- View Details Modal --}}
    @include('shared.modals.application-details', [
        'id'        => 'detailsModal',
        'title'     => 'Application Details',
        'contentId' => 'modalContent',
        'closeFn'   => 'closeModal()',
    ])

    {{-- Approve Modal --}}
    <x-shared.modals.confirm-action
        id="adminApproveModal"
        title="Approve Application"
        title-color="#28a745"
        close-fn="closeAdminApproveModal()">

      <div style="background: #d4edda; color: #155724; padding: 16px; border-radius: 8px; margin-bottom: 20px;">
        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
          <svg style="width: 24px; height: 24px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
          </svg>
          <span style="font-weight: 600;">Final Approval</span>
        </div>
        <p style="margin: 0 0 8px 0;">You are about to give final approval to the following application:</p>
        <div style="margin-left: 10px;">
          <p style="margin: 4px 0;"><strong>Student Name:</strong> <span id="adminApproveStudentName"></span></p>
          <p style="margin: 4px 0;"><strong>Reference Number:</strong> <span id="adminApproveRefNumber"></span></p>
        </div>
        <p style="margin: 8px 0 0 0; font-style: italic;">This will notify the student that their certificate is ready for pickup at the Office of Student Affairs (OSA).</p>
      </div>

      <x-slot name="footer">
        <form id="adminApproveForm" method="POST" style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 20px;">
          @csrf
          @method('PATCH')
          <button type="button" onclick="closeAdminApproveModal()"
                  style="background: #6c757d; color: white; border: none; padding: 10px 20px; border-radius: 6px; cursor: pointer; font-size: 14px; font-weight: 500;">
            Cancel
          </button>
          <button type="submit"
                  style="background: #28a745; color: white; border: none; padding: 10px 20px; border-radius: 6px; cursor: pointer; font-size: 14px; font-weight: 500; display: flex; align-items: center; gap: 8px;">
            <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            Final Approve
          </button>
        </form>
      </x-slot>

    </x-shared.modals.confirm-action>

    {{-- Decline Modal --}}
    <x-shared.modals.confirm-action
        id="adminDeclineModal"
        title="Decline Application"
        title-color="#dc3545"
        close-fn="closeAdminDeclineModal()">

      <div style="background: #f8d7da; color: #721c24; padding: 16px; border-radius: 8px; margin-bottom: 20px;">
        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
          <svg style="width: 24px; height: 24px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
          </svg>
          <span style="font-weight: 600;">Confirm Decline</span>
        </div>
        <p style="margin: 0 0 8px 0;">You are about to decline the following application:</p>
        <div style="margin-left: 10px;">
          <p style="margin: 4px 0;"><strong>Student Name:</strong> <span id="adminDeclineStudentName"></span></p>
          <p style="margin: 4px 0;"><strong>Reference Number:</strong> <span id="adminDeclineRefNumber"></span></p>
        </div>
        <p style="margin: 8px 0 0 0; font-style: italic; font-weight: 600;">Warning: This action cannot be undone.</p>
      </div>

      <x-slot name="footer">
        <form id="adminDeclineForm" method="POST" style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 20px;">
          @csrf
          @method('DELETE')
          <button type="button" onclick="closeAdminDeclineModal()"
                  style="background: #6c757d; color: white; border: none; padding: 10px 20px; border-radius: 6px; cursor: pointer; font-size: 14px; font-weight: 500;">
            Cancel
          </button>
          <button type="submit"
                  style="background: #dc3545; color: white; border: none; padding: 10px 20px; border-radius: 6px; cursor: pointer; font-size: 14px; font-weight: 500; display: flex; align-items: center; gap: 8px;">
            <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
            Decline Application
          </button>
        </form>
      </x-slot>

    </x-shared.modals.confirm-action>
  </div>

  <!-- JavaScript for modal functionality -->
  <style>
    /* Override .main-content * { color: inherit !important } for green action buttons */
    .main-content .btn-green,
    .main-content .btn-green *,
    .main-content .btn-green span,
    .main-content .btn-green svg { color: white !important; }

    /* Reset button hover */
    .main-content .btn-outline-reset:hover,
    .main-content .btn-outline-reset:hover * { color: white !important; }

    /* View button hover */
    .main-content .btn-outline-view:hover,
    .main-content .btn-outline-view:hover * { color: white !important; }

    /* Decline button hover */
    .main-content .btn-outline-decline:hover,
    .main-content .btn-outline-decline:hover * { color: white !important; }
  </style>
  <script>
    function viewDetails(application, receipt) {
      const modal = document.getElementById('detailsModal');
      const content = document.getElementById('modalContent');

      // Build Payment Receipt section
      let receiptSection;
      if (receipt && receipt.official_receipt_no) {
        const datePaid = receipt.date_paid
          ? new Date(receipt.date_paid).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' })
          : 'N/A';
        const docUrl = receipt.document_path
          ? `{{ asset('storage/') }}/${receipt.document_path}`
          : null;
        receiptSection = `
          <div style="padding:14px 16px; background:#e8f5e8; border-radius:8px; border-left:4px solid #28a745;">
            <div style="font-size:11px; font-weight:700; color:#155724; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:10px;">Payment Receipt</div>
            <div style="display:grid; gap:8px; font-size:13px;">
              <div style="display:grid; grid-template-columns:1fr 2fr; gap:8px;">
                <strong style="color:#333;">Official Receipt No:</strong>
                <span style="font-family:monospace; font-weight:600; color:#155724;">${receipt.official_receipt_no}</span>
              </div>
              <div style="display:grid; grid-template-columns:1fr 2fr; gap:8px;">
                <strong style="color:#333;">Date Paid:</strong>
                <span>${datePaid}</span>
              </div>
              ${docUrl ? `<div style="margin-top:6px;"><a href="${docUrl}" target="_blank"
                style="display:inline-flex; align-items:center; gap:6px; padding:8px 14px; background:#28a745; color:white; border-radius:6px; font-size:13px; font-weight:600; text-decoration:none;"
                onmouseover="this.style.background='#218838'" onmouseout="this.style.background='#28a745'">
                <svg style="width:14px;height:14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
                View Receipt
              </a></div>` : ''}
            </div>
          </div>`;
      } else {
        receiptSection = `
          <div style="padding:14px 16px; background:#f8f9fa; border-radius:8px; border-left:4px solid #dee2e6;">
            <div style="font-size:11px; font-weight:700; color:#888; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:6px;">Payment Receipt</div>
            <span style="font-size:13px; color:#6c757d; font-style:italic;">No receipt uploaded.</span>
          </div>`;
      }

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
            <strong>Purpose:</strong>
            <span>${Array.isArray(application.reason) ? application.reason.join(', ') : application.reason}</span>
          </div>
          <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 8px; padding: 12px; background: #f8f9fa; border-radius: 6px;">
            <strong>Number of Copies:</strong>
            <span>${application.number_of_copies}</span>
          </div>
          <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 8px; padding: 12px; background: #e8f5e8; border-radius: 6px;">
            <strong>Payment Amount:</strong>
            <span style="color: var(--primary-green); font-weight: 600;">₱${((Array.isArray(application.reason) ? application.reason.length : 1) * application.number_of_copies * 50).toFixed(2)} (${Array.isArray(application.reason) ? application.reason.length : 1} ${Array.isArray(application.reason) && application.reason.length === 1 ? 'reason' : 'reasons'} × ${application.number_of_copies} ${application.number_of_copies == 1 ? 'copy' : 'copies'} × ₱50.00)</span>
          </div>
          <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 8px; padding: 12px; background: #f8f9fa; border-radius: 6px;">
            <strong>Status:</strong>
            <span style="color: ${application.application_status === 'Pending' ? '#ffc107' : application.application_status.includes('Approved') ? '#28a745' : '#dc3545'}; font-weight: 600;">
              ${application.application_status}
            </span>
          </div>
          <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 8px; padding: 12px; background: #f8f9fa; border-radius: 6px;">
            <strong>Applied On:</strong>
            <span>${new Date(application.created_at).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' })}</span>
          </div>
          ${receiptSection}
        </div>
      `;

      modal.style.display = 'flex';
    }

    function closeModal() {
      document.getElementById('detailsModal').style.display = 'none';
    }

    // Admin Approve Modal Functions - Direct action, no view required!
    function openApproveModal(applicationId, studentName, refNumber) {
      const modal = document.getElementById('adminApproveModal');
      const form = document.getElementById('adminApproveForm');
      const studentNameElement = document.getElementById('adminApproveStudentName');
      const refNumberElement = document.getElementById('adminApproveRefNumber');
      
      // Fix: Use correct route URL - PATCH /admin/application/{id}/approve
      form.action = `/admin/application/${applicationId}/approve`;
      studentNameElement.textContent = studentName;
      refNumberElement.textContent = refNumber;
      
      // Show the modal directly - no need to view first!
      modal.style.display = 'flex';
      
      // Visual feedback that direct action worked
      console.log('✅ Approve modal opened directly for:', studentName);
      
      // Optional: Add subtle animation to show modal opened directly
      modal.style.opacity = '0';
      modal.style.transform = 'scale(0.9)';
      setTimeout(() => {
        modal.style.transition = 'opacity 0.2s ease, transform 0.2s ease';
        modal.style.opacity = '1';
        modal.style.transform = 'scale(1)';
      }, 10);
    }

    function closeAdminApproveModal() {
      const modal = document.getElementById('adminApproveModal');
      modal.style.display = 'none';
      // Reset transition styles for next open
      modal.style.transition = '';
      modal.style.opacity = '';
      modal.style.transform = '';
    }

    // Admin Decline Modal Functions - Direct action, no view required!
    function openDeclineModal(applicationId, studentName, refNumber) {
      const modal = document.getElementById('adminDeclineModal');
      const form = document.getElementById('adminDeclineForm');
      const studentNameElement = document.getElementById('adminDeclineStudentName');
      const refNumberElement = document.getElementById('adminDeclineRefNumber');
      
      // Fix: Use correct route URL - DELETE /admin/application/{id}/reject
      form.action = `/admin/application/${applicationId}/reject`;
      studentNameElement.textContent = studentName;
      refNumberElement.textContent = refNumber;
      
      // Show the modal directly - no need to view first!
      modal.style.display = 'flex';
      
      // Visual feedback that direct action worked
      console.log('⚠️ Decline modal opened directly for:', studentName);
      
      // Optional: Add subtle animation to show modal opened directly
      modal.style.opacity = '0';
      modal.style.transform = 'scale(0.9)';
      setTimeout(() => {
        modal.style.transition = 'opacity 0.2s ease, transform 0.2s ease';
        modal.style.opacity = '1';
        modal.style.transform = 'scale(1)';
      }, 10);
    }

    function closeAdminDeclineModal() {
      const modal = document.getElementById('adminDeclineModal');
      modal.style.display = 'none';
      // Reset transition styles for next open
      modal.style.transition = '';
      modal.style.opacity = '';
      modal.style.transform = '';
    }

    // Close modal when clicking outside
    document.getElementById('detailsModal').addEventListener('click', function(e) {
      if (e.target === this) {
        closeModal();
      }
    });

    document.getElementById('adminApproveModal').addEventListener('click', function(e) {
      if (e.target === this) {
        closeAdminApproveModal();
      }
    });

    document.getElementById('adminDeclineModal').addEventListener('click', function(e) {
      if (e.target === this) {
        closeAdminDeclineModal();
      }
    });

    // Client-side table filtering
    function filterApplicationsTable() {
      const sid  = document.getElementById('filter_student_id').value.trim().toLowerCase();
      const dept = document.getElementById('filter_department').value.trim().toLowerCase();
      const name = document.getElementById('filter_fullname').value.trim().toLowerCase();

      const rows = document.querySelectorAll('#applicationsTableBody tr.app-row');
      const noResultsRow = document.getElementById('filterNoResults');

      let visibleCount = 0;
      rows.forEach(function (row) {
        const rowSid  = (row.dataset.studentId  || '').toLowerCase();
        const rowDept = (row.dataset.department  || '').toLowerCase();
        const rowName = (row.dataset.fullname    || '').toLowerCase();

        const matches = rowSid.includes(sid) && rowDept.includes(dept) && rowName.includes(name);
        row.style.display = matches ? '' : 'none';
        if (matches) visibleCount++;
      });

      if (noResultsRow) {
        noResultsRow.style.display = visibleCount === 0 ? '' : 'none';
      }
    }

    function resetApplicationsFilter() {
      document.getElementById('filter_student_id').value = '';
      document.getElementById('filter_department').value  = '';
      document.getElementById('filter_fullname').value    = '';
      filterApplicationsTable();
    }
  </script>
</x-dashboard-layout>
