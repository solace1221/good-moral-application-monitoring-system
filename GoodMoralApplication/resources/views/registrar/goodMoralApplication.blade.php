<x-dashboard-layout>
  <x-slot name="roleTitle">Registrar</x-slot>

  <x-slot name="navigation">
    <x-registrar-navigation />
  </x-slot>

  <!-- Header Section -->
  <div class="header-section">
    <div>
      <h1 class="role-title">Certificate Applications</h1>
      <p class="welcome-text">Manage and review student certificate applications</p>
      <div class="accent-line"></div>
    </div>
    <div style="display: flex; align-items: center; gap: 16px;">
      <!-- Notification Bell -->
      @if($pendingCount > 0)
        <div style="position: relative;">
          <svg style="width: 32px; height: 32px; color: #ffc107;" fill="currentColor" viewBox="0 0 24 24">
            <path d="M12 22c1.1 0 2-.9 2-2h-4c0 1.1.9 2 2 2zm6-6v-5c0-3.07-1.64-5.64-4.5-6.32V4c0-.83-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5v.68C7.63 5.36 6 7.92 6 11v5l-2 2v1h16v-1l-2-2z"/>
          </svg>
          <span style="position: absolute; top: -8px; right: -8px; background: #dc3545; color: white; border-radius: 50%; width: 24px; height: 24px; font-size: 12px; font-weight: bold; display: flex; align-items: center; justify-content: center;">
            {{ $pendingCount > 99 ? '99+' : $pendingCount }}
          </span>
        </div>
        <div style="color: #495057;">
          <strong>{{ $pendingCount }}</strong> new application{{ $pendingCount > 1 ? 's' : '' }} pending
        </div>
      @endif
    </div>
  </div>

  <!-- Status Messages -->
  @include('shared.alerts.flash')

  <!-- Filter Navigation -->
  <style>
    .tab-filter {
      background: #f1f3f5;
      border: none;
      color: #333;
      padding: 8px 16px;
      border-radius: 8px;
      font-weight: 500;
      transition: all 0.2s ease;
      text-decoration: none;
      font-size: 14px;
    }
    .tab-filter:hover {
      background: #e8f5ec;
      color: #1a7f37;
    }
    .tab-filter.active {
      background: #e8f5ec;
      color: #1a7f37;
      border-bottom: 2px solid #1a7f37;
    }
  </style>
  <div style="background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); margin-bottom: 24px; padding: 20px;">
    <nav style="display: flex; gap: 8px; flex-wrap: wrap;">
      <a href="{{ route('registrar.goodMoralApplication', ['status' => 'pending']) }}"
         class="tab-filter {{ request()->get('status', 'pending') == 'pending' ? 'active' : '' }}">
        Pending ({{ \App\Models\GoodMoralApplication::where('status', 'pending')->count() }})
      </a>
      <a href="{{ route('registrar.goodMoralApplication', ['status' => 'approved']) }}"
         class="tab-filter {{ request()->get('status') == 'approved' ? 'active' : '' }}">
        Approved ({{ \App\Models\GoodMoralApplication::where('status', 'approved')->count() }})
      </a>
      <a href="{{ route('registrar.goodMoralApplication', ['status' => 'rejected']) }}"
         class="tab-filter {{ request()->get('status') == 'rejected' ? 'active' : '' }}">
        Rejected ({{ \App\Models\GoodMoralApplication::where('status', 'rejected')->count() }})
      </a>
      <a href="{{ route('registrar.goodMoralApplication', ['status' => 'all']) }}"
         class="tab-filter {{ request()->get('status') == 'all' ? 'active' : '' }}">
        All Applications
      </a>
    </nav>
  </div>

  <!-- Applications Table -->
  <div style="background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); overflow: hidden; margin-bottom: 24px;">
    <div style="padding: 24px; border-bottom: 1px solid #e9ecef;">
      <h2 style="margin: 0; color: var(--primary-green); font-size: 1.25rem; font-weight: 600;">
        {{ ucfirst(request()->get('status', 'pending')) }} Applications
      </h2>
    </div>

    @if($applications->isEmpty())
    <div style="padding: 48px; text-align: center; color: #6c757d;">
      <svg style="width: 64px; height: 64px; margin: 0 auto 16px; opacity: 0.5;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
      </svg>
      <p style="font-size: 18px; font-weight: 500; margin-bottom: 8px;">No Applications Found</p>
      <p style="font-size: 14px;">There are currently no {{ request()->get('status', 'pending') }} applications to review.</p>
    </div>
    @else
    <div style="overflow-x: auto; -webkit-overflow-scrolling: touch;">
      <table style="width: 100%; border-collapse: collapse; min-width: 800px; table-layout: fixed;">
        <thead>
          <tr style="background: #f8f9fa; border-bottom: 2px solid #e9ecef;">
            <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057; font-size: 14px; width: 12%;">Student ID</th>
            <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057; font-size: 14px; width: 20%;">Name</th>
            <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057; font-size: 14px; width: 15%;">Department</th>
            <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057; font-size: 14px; width: 12%;">Status</th>
            <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057; font-size: 14px; width: 13%;">Date</th>
            <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057; font-size: 14px; width: 10%;">Actions</th>
          </tr>
        </thead>
        <tbody>
          @foreach($applications as $application)
          <tr style="border-bottom: 1px solid #e9ecef; transition: background-color 0.2s ease;"
              onmouseover="this.style.backgroundColor='#f8f9fa'"
              onmouseout="this.style.backgroundColor='transparent'">
            <td style="padding: 16px; color: #495057; font-size: 14px; font-weight: 500;">{{ $application->student->student_id }}</td>
            <td style="padding: 16px; color: #495057; font-size: 14px;">{{ $application->student->fullname }}</td>
            <td style="padding: 16px; color: #495057; font-size: 14px;">{{ $application->student->department }}</td>
            <td style="padding: 16px; color: #495057; font-size: 14px;">
              @php
                $statusColors = [
                  'pending' => '#ffc107',
                  'approved' => '#28a745',
                  'rejected' => '#dc3545'
                ];
                $statusColor = $statusColors[$application->status] ?? '#6c757d';
              @endphp
              <span style="display: inline-block; padding: 6px 12px; background: {{ $statusColor }}20; color: {{ $statusColor }}; border-radius: 20px; font-size: 12px; font-weight: 500;">
                {{ ucfirst($application->status) }}
              </span>
            </td>
            <td style="padding: 16px; color: #495057; font-size: 14px;">
              {{ $application->status === 'pending' ? $application->created_at->format('M d, Y') : $application->updated_at->format('M d, Y') }}
            </td>
            <td style="padding: 16px; color: #495057; font-size: 14px;">
              <div style="display: flex; gap: 6px; align-items: center;">
                <!-- View -->
                <button onclick="viewDetails({{ json_encode($application) }})"
                        style="background: #007bff; border: none; width: 32px; height: 32px; border-radius: 6px; cursor: pointer; display: flex; align-items: center; justify-content: center; flex-shrink: 0; transition: background 0.2s ease;"
                        onmouseover="this.style.background='#0056b3'"
                        onmouseout="this.style.background='#007bff'"
                        title="View application details">
                  <svg style="width: 15px; height: 15px;" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                  </svg>
                </button>

                @if($application->status == 'pending')
                  <!-- Approve -->
                  <button type="button"
                          onclick="openApproveModal({{ $application->id }}, '{{ $application->student->fullname }}', '{{ $application->reference_number }}')"
                          style="background: #28a745; border: none; width: 32px; height: 32px; border-radius: 6px; cursor: pointer; display: flex; align-items: center; justify-content: center; flex-shrink: 0; transition: background 0.2s ease;"
                          onmouseover="this.style.background='#218838'"
                          onmouseout="this.style.background='#28a745'"
                          title="Approve application">
                    <svg style="width: 15px; height: 15px;" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                    </svg>
                  </button>

                  <!-- Reject -->
                  <button type="button"
                          onclick="openRejectModal({{ $application->id }}, 'registrar')"
                          style="background: #dc3545; border: none; width: 32px; height: 32px; border-radius: 6px; cursor: pointer; display: flex; align-items: center; justify-content: center; flex-shrink: 0; transition: background 0.2s ease;"
                          onmouseover="this.style.background='#c82333'"
                          onmouseout="this.style.background='#dc3545'"
                          title="Reject application">
                    <svg style="width: 15px; height: 15px;" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                  </button>
                @elseif($application->status == 'rejected')
                  <!-- Reconsider -->
                  <button type="button"
                          onclick="openReconsiderModal({{ $application->id }}, 'registrar')"
                          style="background: #ffc107; border: none; width: 32px; height: 32px; border-radius: 6px; cursor: pointer; display: flex; align-items: center; justify-content: center; flex-shrink: 0; transition: background 0.2s ease;"
                          onmouseover="this.style.background='#e0a800'"
                          onmouseout="this.style.background='#ffc107'"
                          title="Reconsider application">
                    <svg style="width: 15px; height: 15px;" fill="none" stroke="#212529" stroke-width="2" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                  </button>
                @else
                  <span style="color: #6c757d; font-size: 11px; font-style: italic;">—</span>
                @endif
              </div>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    @endif
    <!-- Pagination -->
    <div style="padding: 24px; border-top: 1px solid #e9ecef; display: flex; justify-content: center;">
      {{ $applications->appends(request()->query())->links() }}
    </div>
  </div>

  <!-- Reject Modal -->
  <div id="rejectModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
    <div style="background: white; padding: 24px; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); width: 100%; max-width: 500px; margin: 20px;">
      <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2 style="margin: 0; color: #dc3545; font-size: 1.25rem; font-weight: 600;">Reject Application</h2>
        <button onclick="closeRejectModal()" style="background: none; border: none; font-size: 24px; cursor: pointer; color: #6c757d;">&times;</button>
      </div>

      <form id="rejectForm" method="POST">
        @csrf
        @method('PATCH')

        <div style="margin-bottom: 16px;">
          <label style="display: block; font-weight: 600; color: #333; margin-bottom: 8px;">
            Rejection Reason <span style="color: #dc3545;">*</span>
          </label>
          <select name="rejection_reason" required style="width: 100%; padding: 12px; border: 2px solid #e1e5e9; border-radius: 8px; font-size: 14px;">
            <option value="">Select a reason</option>
            <option value="Student not officially enrolled">Student not officially enrolled</option>
            <option value="Others: specify">Others: specify</option>
          </select>
        </div>

        <!-- Specify Field (only shown when "Others: specify" is selected) -->
        <div id="specify-field" style="margin-bottom: 16px; display: none;">
          <label style="display: block; font-weight: 600; color: #333; margin-bottom: 8px;">
            Please Specify <span style="color: #dc3545;">*</span>
          </label>
          <input type="text" name="specify_reason" placeholder="Please specify the reason for rejection..."
                 style="width: 100%; padding: 12px; border: 2px solid #e1e5e9; border-radius: 8px; font-size: 14px;">
        </div>

        <div style="margin-bottom: 20px;">
          <label style="display: block; font-weight: 600; color: #333; margin-bottom: 8px;">
            Additional Details
          </label>
          <textarea name="rejection_details" rows="4" placeholder="Please provide additional details about the rejection..."
                    style="width: 100%; padding: 12px; border: 2px solid #e1e5e9; border-radius: 8px; font-size: 14px; resize: vertical;"></textarea>
        </div>

        <div style="display: flex; justify-content: flex-end; gap: 12px;">
          <button type="button" onclick="closeRejectModal()"
                  style="background: #6c757d; color: white; border: none; padding: 10px 20px; border-radius: 6px; cursor: pointer;">
            Cancel
          </button>
          <button type="submit"
                  style="background: #dc3545; color: white; border: none; padding: 10px 20px; border-radius: 6px; cursor: pointer;">
            Reject Application
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- Reconsider Modal -->
  <div id="reconsiderModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
    <div style="background: white; padding: 24px; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); width: 100%; max-width: 500px; margin: 20px;">
      <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2 style="margin: 0; color: #ffc107; font-size: 1.25rem; font-weight: 600;">Reconsider Application</h2>
        <button onclick="closeReconsiderModal()" style="background: none; border: none; font-size: 24px; cursor: pointer; color: #6c757d;">&times;</button>
      </div>

      <div id="rejectionInfo" style="background: #f8d7da; color: #721c24; padding: 16px; border-radius: 8px; margin-bottom: 20px;">
        <!-- Rejection details will be populated here -->
      </div>

      <form id="reconsiderForm" method="POST">
        @csrf
        @method('PATCH')

        <div style="margin-bottom: 20px;">
          <label style="display: block; font-weight: 600; color: #333; margin-bottom: 8px;">
            Reconsideration Notes
          </label>
          <textarea name="reconsider_notes" rows="4" placeholder="Please provide notes for reconsidering this application..."
                    style="width: 100%; padding: 12px; border: 2px solid #e1e5e9; border-radius: 8px; font-size: 14px; resize: vertical;"></textarea>
        </div>

        <div style="display: flex; justify-content: flex-end; gap: 12px;">
          <button type="button" onclick="closeReconsiderModal()"
                  style="background: #6c757d; color: white; border: none; padding: 10px 20px; border-radius: 6px; cursor: pointer;">
            Cancel
          </button>
          <button type="submit"
                  style="background: #ffc107; color: #212529; border: none; padding: 10px 20px; border-radius: 6px; cursor: pointer;">
            Reconsider Application
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- View Details Modal -->
  <div id="detailsModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center; padding: 16px;">
    <div style="background: white; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.15); width: 100%; max-width: 500px; max-height: 90vh; display: flex; flex-direction: column;">
      <!-- Fixed Header -->
      <div style="display: flex; justify-content: space-between; align-items: center; padding: 20px 24px; border-bottom: 1px solid #e9ecef; flex-shrink: 0;">
        <h2 style="margin: 0; color: var(--primary-green); font-size: 1.25rem; font-weight: 600;">Application Details</h2>
        <button onclick="closeModal()" style="background: none; border: none; font-size: 24px; cursor: pointer; color: #6c757d; line-height: 1;">&times;</button>
      </div>

      <!-- Scrollable Body -->
      <div id="modalContent" style="display: grid; gap: 16px; padding: 24px; overflow-y: auto;">
        <!-- Content will be populated by JavaScript -->
      </div>

      <!-- Fixed Footer -->
      <div style="display: flex; justify-content: flex-end; gap: 12px; padding: 16px 24px; border-top: 1px solid #e9ecef; flex-shrink: 0;">
        <button onclick="closeModal()" style="background: #6c757d; color: white; border: none; padding: 10px 20px; border-radius: 6px; cursor: pointer;">
          Close
        </button>
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
            <span>${application.student.student_id}</span>
          </div>
          <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 8px; padding: 12px; background: #f8f9fa; border-radius: 6px;">
            <strong>Full Name:</strong>
            <span>${application.student.fullname}</span>
          </div>
          <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 8px; padding: 12px; background: #f8f9fa; border-radius: 6px;">
            <strong>Gender:</strong>
            <span style="text-transform: capitalize;">${application.gender || 'Not specified'}</span>
          </div>
          <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 8px; padding: 12px; background: #f8f9fa; border-radius: 6px;">
            <strong>Department:</strong>
            <span>${application.student.department}</span>
          </div>
          <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 8px; padding: 12px; background: #f8f9fa; border-radius: 6px;">
            <strong>Reference Number:</strong>
            <span>${application.reference_number}</span>
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
            <span style="color: var(--primary-green); font-weight: 600;">₱${((Array.isArray(application.reason) ? application.reason.length : 1) * application.number_of_copies * 100).toFixed(2)} (${Array.isArray(application.reason) ? application.reason.length : 1} ${Array.isArray(application.reason) && application.reason.length === 1 ? 'reason' : 'reasons'} × ${application.number_of_copies} ${application.number_of_copies == 1 ? 'copy' : 'copies'} × ₱100.00)</span>
          </div>
          <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 8px; padding: 12px; background: #f8f9fa; border-radius: 6px;">
            <strong>Status:</strong>
            <span style="color: ${application.status === 'pending' ? '#ffc107' : application.status === 'approved' ? '#28a745' : '#dc3545'}; font-weight: 600;">
              ${application.status.charAt(0).toUpperCase() + application.status.slice(1)}
            </span>
          </div>
          <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 8px; padding: 12px; background: #f8f9fa; border-radius: 6px;">
            <strong>Applied On:</strong>
            <span>${new Date(application.created_at).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' })}</span>
          </div>
          ${application.status !== 'pending' ? `
          <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 8px; padding: 12px; background: #f8f9fa; border-radius: 6px;">
            <strong>Processed On:</strong>
            <span>${new Date(application.updated_at).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' })}</span>
          </div>
          ` : ''}
        </div>
      `;

      modal.style.display = 'flex';
    }

    function closeModal() {
      document.getElementById('detailsModal').style.display = 'none';
    }

    // Approve Modal Functions
    function openApproveModal(applicationId, studentName, refNumber) {
      const modal = document.getElementById('approveModal');
      const form = document.getElementById('approveForm');
      const studentNameElement = document.getElementById('approveStudentName');
      const refNumberElement = document.getElementById('approveRefNumber');
      
      form.action = `/registrar/application/${applicationId}/approve`;
      studentNameElement.textContent = studentName;
      refNumberElement.textContent = refNumber;
      
      modal.style.display = 'flex';
    }

    function closeApproveModal() {
      document.getElementById('approveModal').style.display = 'none';
    }

    // Reject Modal Functions
    function openRejectModal(applicationId, role) {
      const modal = document.getElementById('rejectModal');
      const form = document.getElementById('rejectForm');
      form.action = `/${role}/reject/${applicationId}`;
      modal.style.display = 'flex';
    }

    function closeRejectModal() {
      document.getElementById('rejectModal').style.display = 'none';
      document.getElementById('rejectForm').reset();

      // Hide specify field and clear its value
      const specifyField = document.getElementById('specify-field');
      const specifyInput = document.querySelector('input[name="specify_reason"]');
      if (specifyField) {
        specifyField.style.display = 'none';
      }
      if (specifyInput) {
        specifyInput.required = false;
        specifyInput.value = '';
      }
    }

    // Reconsider Modal Functions
    function openReconsiderModal(applicationId, role) {
      const modal = document.getElementById('reconsiderModal');
      const form = document.getElementById('reconsiderForm');
      const rejectionInfo = document.getElementById('rejectionInfo');

      // Fetch application details to show rejection reason
      fetch(`/${role}/application/${applicationId}/details`)
        .then(response => response.json())
        .then(data => {
          rejectionInfo.innerHTML = `
            <strong>Previously rejected for:</strong> ${data.rejection_reason}<br>
            ${data.rejection_details ? `<strong>Details:</strong> ${data.rejection_details}<br>` : ''}
            <strong>Rejected by:</strong> ${data.rejected_by}<br>
            <strong>Rejected on:</strong> ${new Date(data.rejected_at).toLocaleDateString()}
          `;
        })
        .catch(error => {
          rejectionInfo.innerHTML = '<strong>Unable to load rejection details</strong>';
        });

      form.action = `/${role}/reconsider/${applicationId}`;
      modal.style.display = 'flex';
    }

    function closeReconsiderModal() {
      document.getElementById('reconsiderModal').style.display = 'none';
      document.getElementById('reconsiderForm').reset();
    }

    // Handle rejection reason change to show/hide specify field
    document.addEventListener('DOMContentLoaded', function() {
      const rejectModal = document.getElementById('rejectModal');
      if (rejectModal) {
        const reasonSelect = rejectModal.querySelector('select[name="rejection_reason"]');
        const specifyField = document.getElementById('specify-field');
        const specifyInput = rejectModal.querySelector('input[name="specify_reason"]');

        if (reasonSelect && specifyField && specifyInput) {
          reasonSelect.addEventListener('change', function() {
            if (this.value === 'Others: specify') {
              specifyField.style.display = 'block';
              specifyInput.required = true;
            } else {
              specifyField.style.display = 'none';
              specifyInput.required = false;
              specifyInput.value = ''; // Clear the field when hidden
            }
          });
        }
      }
    });

    // Close modal when clicking outside
    document.getElementById('detailsModal').addEventListener('click', function(e) {
      if (e.target === this) {
        closeModal();
      }
    });

    document.getElementById('rejectModal').addEventListener('click', function(e) {
      if (e.target === this) {
        closeRejectModal();
      }
    });

    document.getElementById('reconsiderModal').addEventListener('click', function(e) {
      if (e.target === this) {
        closeReconsiderModal();
      }
    });
    
    document.getElementById('approveModal').addEventListener('click', function(e) {
      if (e.target === this) {
        closeApproveModal();
      }
    });

    // Auto-refresh notification count every 30 seconds
    setInterval(function() {
      fetch('{{ route("registrar.goodMoralApplication") }}?ajax=1')
        .then(response => response.json())
        .then(data => {
          if (data.pendingCount !== undefined) {
            // Update notification count in navigation if it exists
            const notificationElements = document.querySelectorAll('[data-notification-count]');
            notificationElements.forEach(el => {
              el.textContent = data.pendingCount > 99 ? '99+' : data.pendingCount;
              el.style.display = data.pendingCount > 0 ? 'flex' : 'none';
            });
          }
        })
        .catch(error => console.log('Notification update failed:', error));
    }, 30000);
  </script>

  <!-- Approve Modal -->
  <div id="approveModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
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
          <p style="margin: 4px 0;"><strong>Student Name:</strong> <span id="approveStudentName"></span></p>
          <p style="margin: 4px 0;"><strong>Reference Number:</strong> <span id="approveRefNumber"></span></p>
        </div>
        <p style="margin: 8px 0 0 0; font-style: italic;">This action will move the application to the next approval stage.</p>
      </div>

      <form id="approveForm" method="POST" style="display: flex; justify-content: flex-end; gap: 12px;">
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
          Confirm Approval
        </button>
      </form>
    </div>
  </div>
</x-dashboard-layout>
