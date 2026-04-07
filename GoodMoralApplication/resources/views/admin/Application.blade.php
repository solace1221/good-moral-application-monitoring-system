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
        <div style="background: #e8f5e8; color: #2d5a2d; padding: 12px 16px; border-radius: 8px; margin: 12px 0; border-left: 4px solid #28a745; font-size: 14px;">
          <strong>💡 Quick Actions:</strong> You can directly <strong>Approve</strong> or <strong>Decline</strong> applications from the table below - no need to click "Details" first!
        </div>
        <div class="accent-line"></div>
      </div>
    </div>
  </div>

  <!-- Main Content -->
  <div class="header-section">
    <!-- Status Messages -->
    @if(session('status'))
    <div style="margin-bottom: 24px; padding: 16px; background: #d4edda; color: #155724; border: 1px solid #c3e6cb; border-radius: 8px;">
      {{ session('status') }}
    </div>
    @endif

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

    <!-- Search Form -->
    <form method="GET" action="{{ route('admin.Application') }}" style="margin-bottom: 24px; padding: 24px; background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 20px;">
        <div>
          <label for="student_id" style="display: block; margin-bottom: 8px; font-weight: 600; color: #333;">Student ID</label>
          <input type="text" id="student_id" name="student_id" 
                 style="width: 100%; padding: 12px 16px; border: 2px solid #e1e5e9; border-radius: 8px; font-size: 14px; transition: border-color 0.3s ease;"
                 value="{{ old('student_id', request('student_id')) }}" 
                 placeholder="Enter Student ID">
        </div>
        <div>
          <label for="department" style="display: block; margin-bottom: 8px; font-weight: 600; color: #333;">Department</label>
          <input type="text" id="department" name="department" 
                 style="width: 100%; padding: 12px 16px; border: 2px solid #e1e5e9; border-radius: 8px; font-size: 14px; transition: border-color 0.3s ease;"
                 value="{{ old('department', request('department')) }}" 
                 placeholder="Enter Department">
        </div>
        <div>
          <label for="fullname" style="display: block; margin-bottom: 8px; font-weight: 600; color: #333;">Full Name</label>
          <input type="text" id="fullname" name="fullname" 
                 style="width: 100%; padding: 12px 16px; border: 2px solid #e1e5e9; border-radius: 8px; font-size: 14px; transition: border-color 0.3s ease;"
                 value="{{ old('fullname', request('fullname')) }}" 
                 placeholder="Enter Full Name">
        </div>
      </div>
      <div style="display: flex; gap: 12px;">
        <button type="submit" class="btn-primary">
          <svg style="width: 16px; height: 16px; margin-right: 8px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
          </svg>
          Search
        </button>
        <a href="{{ route('admin.Application') }}" class="btn-secondary">
          <svg style="width: 16px; height: 16px; margin-right: 8px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
          </svg>
          Clear
        </a>
      </div>
    </form>

    <!-- Status Messages -->
    @if(session('status'))
    <div style="margin-bottom: 24px; padding: 16px; background: #d4edda; color: #155724; border: 1px solid #c3e6cb; border-radius: 8px;">
      {{ session('status') }}
    </div>
    @endif

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
              <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057; font-size: 14px;">Purpose</th>
              <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057; font-size: 14px;">Date Applied</th>
              <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057; font-size: 14px;">Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach($applications as $application)
            <tr style="border-bottom: 1px solid #e9ecef; transition: background-color 0.2s ease;" 
                onmouseover="this.style.backgroundColor='#f8f9fa'" 
                onmouseout="this.style.backgroundColor='transparent'">
              <td style="padding: 16px; color: #495057; font-size: 14px;">{{ $application->student_id }}</td>
              <td style="padding: 16px; color: #495057; font-size: 14px;">
                <span style="display: inline-block; padding: 4px 8px; background: #e3f2fd; color: #1976d2; border-radius: 4px; font-size: 12px; font-weight: 500;">
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
              <td style="padding: 16px; color: #495057; font-size: 14px;">{{ $application->formatted_reasons }}</td>
              <td style="padding: 16px; color: #6c757d; font-size: 14px;">{{ $application->created_at->format('M j, Y') }}</td>
              <td style="padding: 16px; color: #495057; font-size: 14px;">
                <div style="display: flex; gap: 8px; align-items: center; flex-wrap: wrap;">
                  <!-- View Details Button (optional - for detailed info only) -->
                  <button onclick="viewDetails({{ json_encode($application) }})"
                          style="background: #6c757d; color: white; border: none; padding: 8px 12px; border-radius: 6px; font-size: 11px; font-weight: 500; cursor: pointer; display: flex; align-items: center; gap: 6px; transition: all 0.2s ease; opacity: 0.8;"
                          onmouseover="this.style.background='#5a6268'; this.style.opacity='1'"
                          onmouseout="this.style.background='#6c757d'; this.style.opacity='0.8'"
                          title="Optional: View detailed information">
                    <svg style="width: 12px; height: 12px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    Details
                  </button>

                  @if(str_contains($application->application_status ?? '', 'Approved by Dean:'))
                    <!-- Approve Button - Direct Action, No View Required -->
                    <button type="button"
                            onclick="openApproveModal({{ $application->id }}, '{{ $application->fullname }}', '{{ $application->reference_number ?? 'N/A' }}')"
                            style="background: #28a745; color: white; border: none; padding: 10px 16px; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px; min-width: 100px; justify-content: center; transition: all 0.3s ease; box-shadow: 0 2px 4px rgba(40, 167, 69, 0.2);"
                            onmouseover="this.style.background='#218838'; this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 8px rgba(40, 167, 69, 0.3)'"
                            onmouseout="this.style.background='#28a745'; this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 4px rgba(40, 167, 69, 0.2)'"
                            title="Approve this application directly - No need to view first!">
                      <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                      </svg>
                      Approve
                    </button>

                    <!-- Decline Button - Direct Action, No View Required -->
                    <button type="button"
                            onclick="openDeclineModal({{ $application->id }}, '{{ $application->fullname }}', '{{ $application->reference_number ?? 'N/A' }}')"
                            style="background: #dc3545; color: white; border: none; padding: 10px 16px; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px; min-width: 100px; justify-content: center; transition: all 0.3s ease; box-shadow: 0 2px 4px rgba(220, 53, 69, 0.2);"
                            onmouseover="this.style.background='#c82333'; this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 8px rgba(220, 53, 69, 0.3)'"
                            onmouseout="this.style.background='#dc3545'; this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 4px rgba(220, 53, 69, 0.2)'"
                            title="Decline this application directly - No need to view first!">
                      <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                      </svg>
                      Decline
                    </button>
                  @elseif(str_contains($application->application_status ?? '', 'Approved by Administrator'))
                    <span style="color: #28a745; font-size: 12px; font-style: italic; padding: 8px 12px; background: #d4edda; border-radius: 6px; border: 1px solid #c3e6cb;">
                      ✅ Approved by Administrator
                    </span>
                  @elseif(str_contains($application->application_status ?? '', 'Rejected by Administrator'))
                    <span style="color: #dc3545; font-size: 12px; font-style: italic; padding: 8px 12px; background: #f8d7da; border-radius: 6px; border: 1px solid #f5c6cb;">
                      ❌ Rejected by Administrator
                    </span>
                  @else
                    <span style="color: #6c757d; font-size: 12px; font-style: italic; padding: 8px 12px; background: #f8f9fa; border-radius: 6px;">
                      Awaiting Dean Approval
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

    <!-- View Details Modal -->
    <div id="detailsModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
      <div style="background: white; padding: 24px; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); width: 100%; max-width: 500px; margin: 20px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
          <h2 style="margin: 0; color: var(--primary-green); font-size: 1.25rem; font-weight: 600;">Application Details</h2>
          <button onclick="closeModal()" style="background: none; border: none; font-size: 24px; cursor: pointer; color: #6c757d;">&times;</button>
        </div>

        <div id="modalContent" style="display: grid; gap: 16px;">
          <!-- Content will be populated by JavaScript -->
        </div>

        <div style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 24px;">
          <button onclick="closeModal()" style="background: #6c757d; color: white; border: none; padding: 10px 20px; border-radius: 6px; cursor: pointer;">
            Close
          </button>
      </div>
    </div>

    <!-- Approve Modal -->
    <div id="adminApproveModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
      <div style="background: white; padding: 24px; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); width: 100%; max-width: 500px; margin: 20px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
          <h2 style="margin: 0; color: #28a745; font-size: 1.25rem; font-weight: 600;">Approve Application</h2>
          <button onclick="closeAdminApproveModal()" style="background: none; border: none; font-size: 24px; cursor: pointer; color: #6c757d;">&times;</button>
        </div>
        
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
          <p style="margin: 8px 0 0 0; font-style: italic;">This will notify the student that their certificate is ready for pickup.</p>
        </div>

        <form id="adminApproveForm" method="POST" style="display: flex; justify-content: flex-end; gap: 12px;">
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
      </div>
    </div>

    <!-- Decline Modal -->
    <div id="adminDeclineModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
      <div style="background: white; padding: 24px; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); width: 100%; max-width: 500px; margin: 20px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
          <h2 style="margin: 0; color: #dc3545; font-size: 1.25rem; font-weight: 600;">Decline Application</h2>
          <button onclick="closeAdminDeclineModal()" style="background: none; border: none; font-size: 24px; cursor: pointer; color: #6c757d;">&times;</button>
        </div>
        
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

        <form id="adminDeclineForm" method="POST" style="display: flex; justify-content: flex-end; gap: 12px;">
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
      </div>
    </div>
  </div>

  <!-- JavaScript for modal functionality -->
  <script>
    function viewDetails(application) {
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
      
      // Fix: Use correct route URL - PATCH /admin/Application/{id}/approve
      form.action = `/admin/Application/${applicationId}/approve`;
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
      
      // Fix: Use correct route URL - DELETE /admin/Application/{id}/reject
      form.action = `/admin/Application/${applicationId}/reject`;
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
  </script>
</x-dashboard-layout>
