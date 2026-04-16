<x-dashboard-layout>
  <x-slot name="roleTitle">Admin</x-slot>

  <x-slot name="navigation">
    <x-admin-navigation />
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
          {{ $applications['all_dean_approved']->count() }} Total Application{{ $applications['all_dean_approved']->count() !== 1 ? 's' : '' }}
        </div>
      </div>
    </div>
  </div>

  @include('shared.alerts.flash')

  <!-- Filter Tabs -->
  <div style="background: white; border-radius: 12px 12px 0 0; box-shadow: 0 2px 8px rgba(0,0,0,0.1); margin-bottom: 0;">
    <div style="display: flex; border-bottom: 1px solid #e9ecef;">
      <button onclick="showTab('all')" id="tab-all" class="tab-button active" style="flex: 1; padding: 16px; border: none; background: none; cursor: pointer; font-weight: 600; color: var(--primary-green); border-bottom: 3px solid var(--primary-green);">
        All Applications ({{ $applications['all_dean_approved']->count() }})
      </button>
      <button onclick="showTab('good_moral')" id="tab-good_moral" class="tab-button" style="flex: 1; padding: 16px; border: none; background: none; cursor: pointer; font-weight: 600; color: #6c757d; border-bottom: 3px solid transparent;">
        Good Moral ({{ $applications['good_moral']->count() }})
      </button>
      <button onclick="showTab('residency')" id="tab-residency" class="tab-button" style="flex: 1; padding: 16px; border: none; background: none; cursor: pointer; font-weight: 600; color: #6c757d; border-bottom: 3px solid transparent;">
        Residency ({{ $applications['residency']->count() }})
      </button>
    </div>
  </div>

  <!-- All Applications Tab -->
  <div id="content-all" class="tab-content" style="background: white; border-radius: 0 0 12px 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); overflow: hidden;">
    @if($applications['all_dean_approved']->isEmpty())
    <div style="text-align: center; padding: 48px;">
      <svg style="width: 64px; height: 64px; margin: 0 auto 16px; color: #9ca3af;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
      </svg>
      <h3 style="margin: 0 0 8px; color: #374151; font-size: 1.25rem;">No Applications Found</h3>
      <p style="margin: 0; color: #6b7280;">There are currently no certificate applications to display.</p>
    </div>
    @else
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
          @foreach($applications['all_dean_approved'] as $application)

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
                $statusColor = '#ffc107'; // Yellow for pending admin approval
                $statusText = 'Pending Admin Approval';
              @endphp
              <span style="display: inline-block; padding: 6px 12px; background: {{ $statusColor }}20; color: {{ $statusColor }}; border-radius: 20px; font-size: 12px; font-weight: 500;">
                {{ $statusText }}
              </span>
            </td>
            <td style="padding: 16px; color: #495057; font-size: 14px;">
              <div style="display: flex; gap: 8px; align-items: center; flex-wrap: wrap;">
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

                @if(str_contains($application->application_status, 'Receipt Uploaded') || str_contains($application->application_status, 'Approved by Dean:'))
                  <!-- Approve Button -->
                  <form action="{{ route('admin.approveGoodMoralApplication', $application->id) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('PATCH')
                    <button type="submit"
                            onclick="return confirm('Are you sure you want to approve this {{ $application->certificate_type === 'good_moral' ? 'Good Moral' : 'Residency' }} application? This will notify the student to upload payment receipt.')"
                            style="background: #28a745; color: white; border: none; padding: 8px 12px; border-radius: 6px; font-size: 12px; font-weight: 500; cursor: pointer; display: flex; align-items: center; gap: 6px; transition: all 0.2s ease;"
                            onmouseover="this.style.background='#218838'"
                            onmouseout="this.style.background='#28a745'">
                      <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                      </svg>
                      Approve
                    </button>
                  </form>

                  <!-- Reject Button (opens modal) -->
                  <button type="button"
                          onclick="openRejectModal({{ $application->id }}, '{{ addslashes($application->fullname) }}', '{{ $application->certificate_type === 'good_moral' ? 'Good Moral' : 'Residency' }}')"
                          style="background: #dc3545; color: white; border: none; padding: 8px 12px; border-radius: 6px; font-size: 12px; font-weight: 500; cursor: pointer; display: flex; align-items: center; gap: 6px; transition: all 0.2s ease;"
                          onmouseover="this.style.background='#c82333'"
                          onmouseout="this.style.background='#dc3545'">
                    <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
    @endif
  </div>
  <!-- Application Details Modal -->
  <div id="detailsModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center; padding: 16px;"
       onclick="if(event.target===this){ closeModal(); }">
    <div style="background: white; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.15); width: 100%; max-width: 500px; max-height: 90vh; display: flex; flex-direction: column;">
      <!-- Header -->
      <div style="display: flex; justify-content: space-between; align-items: center; padding: 20px 24px; border-bottom: 1px solid #e9ecef; flex-shrink: 0;">
        <h2 style="margin: 0; color: var(--primary-green); font-size: 1.25rem; font-weight: 600;">Application Details</h2>
        <button onclick="closeModal()" style="background: none; border: none; font-size: 24px; cursor: pointer; color: #6c757d; line-height: 1;">&times;</button>
      </div>

      <!-- Scrollable Body -->
      <div id="modalContent" style="display: grid; gap: 16px; padding: 24px; overflow-y: auto;">
        <!-- Content will be populated by JavaScript -->
      </div>

      <!-- Footer -->
      <div style="display: flex; justify-content: flex-end; gap: 12px; padding: 16px 24px; border-top: 1px solid #e9ecef; flex-shrink: 0;">
        <button onclick="closeModal()" style="background: #6c757d; color: white; border: none; padding: 10px 20px; border-radius: 6px; cursor: pointer; font-weight: 500;">
          Close
        </button>
      </div>
    </div>
  </div>

  <script>
    // View details modal
    function viewGoodMoralDetails(application) {
      const modal = document.getElementById('detailsModal');
      const content = document.getElementById('modalContent');

      const reasonCount = Array.isArray(application.reason) ? application.reason.length : 1;
      const copies = application.number_of_copies;
      const amount = (reasonCount * copies * 100).toFixed(2);

      content.innerHTML = `
        <div style="display: grid; gap: 12px;">
          <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 8px; padding: 12px; background: #f8f9fa; border-radius: 6px;">
            <strong>Student ID:</strong>
            <span>${application.student_id ?? 'N/A'}</span>
          </div>
          <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 8px; padding: 12px; background: #f8f9fa; border-radius: 6px;">
            <strong>Full Name:</strong>
            <span>${application.fullname ?? 'N/A'}</span>
          </div>
          <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 8px; padding: 12px; background: #f8f9fa; border-radius: 6px;">
            <strong>Department:</strong>
            <span>${application.department ?? 'N/A'}</span>
          </div>
          <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 8px; padding: 12px; background: #f8f9fa; border-radius: 6px;">
            <strong>Certificate Type:</strong>
            <span>${application.certificate_type === 'good_moral' ? 'Good Moral Certificate' : 'Certificate of Residency'}</span>
          </div>
          <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 8px; padding: 12px; background: #f8f9fa; border-radius: 6px;">
            <strong>Reference Number:</strong>
            <span>${application.reference_number ?? 'N/A'}</span>
          </div>
          <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 8px; padding: 12px; background: #f8f9fa; border-radius: 6px;">
            <strong>Number of Copies:</strong>
            <span>${copies}</span>
          </div>
          <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 8px; padding: 12px; background: #f8f9fa; border-radius: 6px;">
            <strong>Purpose:</strong>
            <span>${Array.isArray(application.reason) ? application.reason.join(', ') : (application.reason ?? 'N/A')}</span>
          </div>
          <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 8px; padding: 12px; background: #e8f5e8; border-radius: 6px;">
            <strong>Payment Amount:</strong>
            <span style="color: var(--primary-green); font-weight: 600;">₱${amount} (${reasonCount} ${reasonCount === 1 ? 'reason' : 'reasons'} × ${copies} ${copies == 1 ? 'copy' : 'copies'} × ₱100.00)</span>
          </div>
          <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 8px; padding: 12px; background: #f8f9fa; border-radius: 6px;">
            <strong>Status:</strong>
            <span style="font-weight: 600;">${application.application_status ?? 'N/A'}</span>
          </div>
          <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 8px; padding: 12px; background: #f8f9fa; border-radius: 6px;">
            <strong>Applied On:</strong>
            <span>${application.created_at ? new Date(application.created_at).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' }) : 'N/A'}</span>
          </div>
          <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 8px; padding: 12px; background: #f8f9fa; border-radius: 6px;">
            <strong>Course Completed:</strong>
            <span>${application.course_completed ?? 'N/A'}</span>
          </div>
          <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 8px; padding: 12px; background: #f8f9fa; border-radius: 6px;">
            <strong>Graduation Date:</strong>
            <span>${application.graduation_date ?? 'N/A'}</span>
          </div>
          <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 8px; padding: 12px; background: #f8f9fa; border-radius: 6px;">
            <strong>Undergraduate:</strong>
            <span>${(application.is_undergraduate !== null && application.is_undergraduate !== 0) ? 'Yes' : 'No'}</span>
          </div>
          <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 8px; padding: 12px; background: #f8f9fa; border-radius: 6px;">
            <strong>Last Course Year Level:</strong>
            <span>${application.last_course_year_level ?? 'N/A'}</span>
          </div>
          <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 8px; padding: 12px; background: #f8f9fa; border-radius: 6px;">
            <strong>Last Semester SY:</strong>
            <span>${application.last_semester_sy ?? 'N/A'}</span>
          </div>
        </div>
      `;

      modal.style.display = 'flex';
    }

    // Close the modal
    function closeModal() {
      document.getElementById('detailsModal').style.display = 'none';
    }

    // Tab switching
    function showTab(tab) {
      document.querySelectorAll('.tab-content').forEach(el => el.style.display = 'none');
      document.querySelectorAll('.tab-button').forEach(btn => {
        btn.style.color = '#6c757d';
        btn.style.borderBottomColor = 'transparent';
      });
      const content = document.getElementById('content-' + tab);
      if (content) content.style.display = 'block';
      const tabBtn = document.getElementById('tab-' + tab);
      if (tabBtn) {
        tabBtn.style.color = 'var(--primary-green)';
        tabBtn.style.borderBottomColor = 'var(--primary-green)';
      }
    }
  </script>

  <!-- Reject Application Modal -->
  <div id="rejectModal" style="display:none; position:fixed; inset:0; z-index:1050; background:rgba(0,0,0,0.5); align-items:center; justify-content:center; padding:16px;">
    <div style="background:white; border-radius:12px; width:100%; max-width:480px; box-shadow:0 20px 60px rgba(0,0,0,0.2); overflow:hidden;">

      <!-- Modal Header -->
      <div style="padding:20px 24px; border-bottom:1px solid #e9ecef; background:#dc3545;">
        <h5 style="margin:0; font-size:1.1rem; font-weight:700; color:white;">Reject Application</h5>
        <div id="rejectModalStudentName" style="font-size:13px; color:rgba(255,255,255,0.85); margin-top:4px;"></div>
      </div>

      <!-- Modal Body -->
      <div style="padding:24px;">
        <div style="background:#fff5f5; border:1px solid #f5c6cb; border-radius:8px; padding:12px 14px; margin-bottom:20px; font-size:13px; color:#721c24; line-height:1.6;">
          This action cannot be undone. The student will be notified about the rejection with the reason you provide.
        </div>

        <form id="rejectForm" method="POST" style="display:grid; gap:16px;">
          @csrf
          @method('PATCH')

          <div>
            <label style="display:block; font-weight:600; color:#333; margin-bottom:6px; font-size:13px;">Reason for Rejection <span style="color:#dc3545;">*</span></label>
            <select name="rejection_reason" id="rejectReasonSelect" required
                    style="width:100%; padding:10px 12px; border:1px solid #ced4da; border-radius:6px; font-size:14px; box-sizing:border-box; background:white;"
                    onchange="toggleCustomReason(this)">
              <option value="">Select a reason...</option>
              <option value="Incomplete or invalid receipt">Incomplete or invalid receipt</option>
              <option value="Receipt does not match application">Receipt does not match application</option>
              <option value="Payment amount is incorrect">Payment amount is incorrect</option>
              <option value="Fraudulent or tampered receipt">Fraudulent or tampered receipt</option>
              <option value="Student has unresolved violations">Student has unresolved violations</option>
              <option value="Application information mismatch">Application information mismatch</option>
              <option value="Other">Other (specify below)</option>
            </select>
          </div>

          <div id="customReasonWrapper" style="display:none;">
            <label style="display:block; font-weight:600; color:#333; margin-bottom:6px; font-size:13px;">Custom Reason <span style="color:#dc3545;">*</span></label>
            <input type="text" id="customReasonInput" placeholder="Enter your reason..."
                   style="width:100%; padding:10px 12px; border:1px solid #ced4da; border-radius:6px; font-size:14px; box-sizing:border-box;">
          </div>

          <div>
            <label style="display:block; font-weight:600; color:#333; margin-bottom:6px; font-size:13px;">Additional Details <span style="color:#6c757d; font-weight:400;">(optional)</span></label>
            <textarea name="rejection_details" rows="3" placeholder="Provide additional context or instructions for the student..."
                      style="width:100%; padding:10px 12px; border:1px solid #ced4da; border-radius:6px; font-size:14px; box-sizing:border-box; resize:vertical;"></textarea>
          </div>

          <!-- Modal Footer -->
          <div style="display:flex; justify-content:flex-end; gap:10px; padding-top:8px; border-top:1px solid #e9ecef; margin-top:4px;">
            <button type="button" onclick="closeRejectModal()"
                    style="padding:10px 20px; background:#6c757d; color:white; border:none; border-radius:6px; font-size:14px; cursor:pointer; font-weight:500;"
                    onmouseover="this.style.background='#5a6268'" onmouseout="this.style.background='#6c757d'">
              Cancel
            </button>
            <button type="submit"
                    style="padding:10px 20px; background:#dc3545; color:white; border:none; border-radius:6px; font-size:14px; cursor:pointer; font-weight:600;"
                    onmouseover="this.style.background='#c82333'" onmouseout="this.style.background='#dc3545'">
              Confirm Rejection
            </button>
          </div>
        </form>
      </div>

    </div>
  </div>

  <script>
    function openRejectModal(appId, studentName, certType) {
      document.getElementById('rejectModalStudentName').textContent = studentName + ' — ' + certType + ' Certificate';
      document.getElementById('rejectForm').action = '/admin/good-moral/' + appId + '/reject';
      document.getElementById('rejectReasonSelect').value = '';
      document.getElementById('customReasonInput').value = '';
      document.getElementById('customReasonWrapper').style.display = 'none';
      document.getElementById('rejectForm').querySelector('textarea').value = '';
      document.getElementById('rejectModal').style.display = 'flex';
    }

    function closeRejectModal() {
      document.getElementById('rejectModal').style.display = 'none';
    }

    function toggleCustomReason(select) {
      var wrapper = document.getElementById('customReasonWrapper');
      var input = document.getElementById('customReasonInput');
      if (select.value === 'Other') {
        wrapper.style.display = 'block';
        input.setAttribute('required', 'required');
        input.name = 'rejection_reason';
        select.removeAttribute('name');
      } else {
        wrapper.style.display = 'none';
        input.removeAttribute('required');
        input.removeAttribute('name');
        select.name = 'rejection_reason';
      }
    }

    // Close modal on backdrop click
    document.getElementById('rejectModal').addEventListener('click', function(e) {
      if (e.target === this) closeRejectModal();
    });
  </script>

</x-dashboard-layout>