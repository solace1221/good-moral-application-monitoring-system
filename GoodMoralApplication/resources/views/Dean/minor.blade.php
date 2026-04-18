<x-dashboard-layout>
  <x-slot name="roleTitle">Dean</x-slot>

  <x-slot name="navigation">
    <x-dean-navigation />
  </x-slot>

  <!-- Header Section -->
  <div class="header-section">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
      <div>
        <h1 class="role-title">Minor Violations</h1>
        <p class="welcome-text">Review and approve minor violations in your department</p>
        <div class="accent-line"></div>
      </div>
    </div>
  </div>

  <!-- Main Content -->
  <div class="header-section">
    @include('shared.alerts.flash')

    <!-- Tab Filters -->
    <div style="display: flex; gap: 8px; margin-bottom: 24px; flex-wrap: wrap;">
      <a href="{{ route('dean.minor', ['tab' => 'pending']) }}"
         class="tab-filter {{ $tab === 'pending' ? 'active' : '' }}">
        Pending Review
        @if($pendingCount > 0)
          <span style="background: #dc3545; color: white; font-size: 11px; padding: 2px 7px; border-radius: 10px; margin-left: 6px;">{{ $pendingCount }}</span>
        @endif
      </a>
      <a href="{{ route('dean.minor', ['tab' => 'approved']) }}"
         class="tab-filter {{ $tab === 'approved' ? 'active' : '' }}">
        Approved
        @if($approvedCount > 0)
          <span style="background: #28a745; color: white; font-size: 11px; padding: 2px 7px; border-radius: 10px; margin-left: 6px;">{{ $approvedCount }}</span>
        @endif
      </a>
      <a href="{{ route('dean.minor', ['tab' => 'completed']) }}"
         class="tab-filter {{ $tab === 'completed' ? 'active' : '' }}">
        Completed
        @if($completedCount > 0)
          <span style="background: #6c757d; color: white; font-size: 11px; padding: 2px 7px; border-radius: 10px; margin-left: 6px;">{{ $completedCount }}</span>
        @endif
      </a>
      <a href="{{ route('dean.minor', ['tab' => 'declined']) }}"
         class="tab-filter {{ $tab === 'declined' ? 'active' : '' }}">
        Declined
        @if($declinedCount > 0)
          <span style="background: #f59e0b; color: white; font-size: 11px; padding: 2px 7px; border-radius: 10px; margin-left: 6px;">{{ $declinedCount }}</span>
        @endif
      </a>
      <a href="{{ route('dean.minor', ['tab' => 'history']) }}"
         class="tab-filter {{ $tab === 'history' ? 'active' : '' }}">
        History
        @if($historyCount > 0)
          <span style="background: #6f42c1; color: white; font-size: 11px; padding: 2px 7px; border-radius: 10px; margin-left: 6px;">{{ $historyCount }}</span>
        @endif
      </a>
    </div>

    @if($students->isEmpty())
    <div style="text-align: center; padding: 48px; background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
      <svg style="width: 64px; height: 64px; margin: 0 auto 16px; color: #9ca3af;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
      </svg>
      <h3 style="margin: 0 0 8px; color: #374151; font-size: 1.25rem;">No Violations Found</h3>
      <p style="margin: 0; color: #6b7280;">No minor violations in this category.</p>
    </div>
    @else
    <div style="background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); overflow: hidden;">
      <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;">
          <thead>
            <tr style="background: #f8f9fa; border-bottom: 2px solid #e9ecef;">
              <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057; font-size: 14px;">Student ID</th>
              <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057; font-size: 14px;">Student Name</th>
              <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057; font-size: 14px;">Course</th>
              <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057; font-size: 14px;">Violation Details</th>
              <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057; font-size: 14px;">Issued By</th>
              <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057; font-size: 14px;">Status</th>
              @if($tab === 'pending')
              <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057; font-size: 14px;">Actions</th>
              @endif
              @if(in_array($tab, ['approved', 'completed', 'declined', 'history']))
              <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057; font-size: 14px;">Reviewer</th>
              @endif
            </tr>
          </thead>
          <tbody>
            @foreach($students as $student)
            <tr style="border-bottom: 1px solid #e9ecef; transition: background-color 0.2s ease;"
                onmouseover="this.style.backgroundColor='#f8f9fa'"
                onmouseout="this.style.backgroundColor='transparent'">
              <td style="padding: 16px; color: #495057; font-size: 14px; font-weight: 500;">{{ $student->student_id }}</td>
              <td style="padding: 16px; color: #495057; font-size: 14px;">
                <div style="font-weight: 500; color: #333; margin-bottom: 4px;">{{ $student->first_name }} {{ $student->last_name }}</div>
                @php
                  $minorCount = \App\Models\StudentViolation::where('student_id', $student->student_id)
                    ->where('offense_type', 'minor')->count();
                  $statusColor = $minorCount >= 3 ? '#dc3545' : ($minorCount == 2 ? '#fd7e14' : '#ffc107');
                @endphp
                <div style="font-size: 11px; padding: 2px 6px; border-radius: 3px; display: inline-block; background: {{ $statusColor }}20; color: {{ $statusColor }}; border: 1px solid {{ $statusColor }}40;">
                  {{ $minorCount }}/3 Minor Violations
                </div>
              </td>
              <td style="padding: 16px; color: #495057; font-size: 14px;">
                <span style="display: inline-block; padding: 4px 8px; background: #e3f2fd; color: #1976d2; border-radius: 4px; font-size: 12px; font-weight: 500;">
                  {{ $student->course ?? 'N/A' }}
                </span>
              </td>
              <td style="padding: 16px; color: #495057; font-size: 14px; max-width: 200px;">
                <div style="font-weight: 600; color: #333; margin-bottom: 4px;">{{ $student->violation }}</div>
                <span style="display: inline-block; padding: 4px 8px; background: #ffc10720; color: #ffc107; border-radius: 4px; font-size: 11px; font-weight: 500; text-transform: uppercase;">Minor Violation</span>
              </td>
              <td style="padding: 16px; color: #495057; font-size: 14px;">
                @if($student->added_by)
                  <div style="font-weight: 500; color: #495057; font-size: 13px;">{{ $student->added_by }}</div>
                @else
                  <span style="color: #6c757d; font-size: 12px; font-style: italic;">Unknown</span>
                @endif
              </td>
              <td style="padding: 16px; color: #495057; font-size: 14px;">
                @if($student->status === 'Reported')
                  <span style="display: inline-block; padding: 6px 12px; background: #ffc10720; color: #856404; border-radius: 20px; font-size: 12px; font-weight: 500;">Reported</span>
                @elseif($student->status === 'Approved')
                  <span style="display: inline-block; padding: 6px 12px; background: #28a74520; color: #28a745; border-radius: 20px; font-size: 12px; font-weight: 500;">Approved</span>
                @elseif($student->status === 'Declined')
                  <span style="display: inline-block; padding: 6px 12px; background: #dc354520; color: #dc3545; border-radius: 20px; font-size: 12px; font-weight: 500;">Declined</span>
                @elseif($student->status === 'Complied')
                  <span style="display: inline-block; padding: 6px 12px; background: #10b98120; color: #10b981; border-radius: 20px; font-size: 12px; font-weight: 500;">Complied</span>
                @elseif($student->status === 'Closed')
                  <span style="display: inline-block; padding: 6px 12px; background: #6c757d20; color: #6c757d; border-radius: 20px; font-size: 12px; font-weight: 500;">Closed</span>
                @endif
              </td>
              @if($tab === 'pending')
              <td style="padding: 16px; color: #495057; font-size: 14px;">
                <div style="display: flex; gap: 8px; align-items: center;">
                  <form id="approve-form-{{ $student->id }}" action="{{ route('dean.violation.approve', $student->id) }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="button"
                            onclick="showApproveModal({{ $student->id }}, '{{ addslashes($student->first_name . ' ' . $student->last_name) }}')"
                            style="background: #28a745; color: #ffffff; border: none; padding: 8px 16px; border-radius: 6px; font-size: 12px; font-weight: 500; cursor: pointer; display: inline-flex; align-items: center; gap: 6px;"
                            onmouseover="this.style.background='#218838'"
                            onmouseout="this.style.background='#28a745'">
                      <svg style="width: 13px; height: 13px; flex-shrink: 0;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                      </svg>
                      Approve
                    </button>
                  </form>
                  <button type="button" class="dean-btn-danger"
                          onclick="showDeclineModal({{ $student->id }}, '{{ addslashes($student->first_name . ' ' . $student->last_name) }}')"
                          style="color: #ffffff; border: none; padding: 8px 16px; border-radius: 6px; font-size: 12px; font-weight: 500; cursor: pointer; display: inline-flex; align-items: center; gap: 6px;"
                          onmouseover="this.style.background='#c82333'"
                          onmouseout="this.style.background='#dc3545'">
                    <svg style="width: 13px; height: 13px; flex-shrink: 0;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    Decline
                  </button>
                </div>
              </td>
              @endif
              @if(in_array($tab, ['approved', 'completed', 'declined', 'history']))
              <td style="padding: 16px; color: #495057; font-size: 14px;">
                @if($student->reviewed_by)
                  <div style="font-weight: 500; color: #333; font-size: 13px;">{{ ucfirst(str_replace('_', ' ', $student->reviewed_role ?? '')) }} {{ $student->reviewed_by }}</div>
                  @if($student->reviewed_at)
                    <div style="font-size: 11px; color: #6c757d;">{{ $student->reviewed_at->format('M d, Y â€“ h:i A') }}</div>
                  @endif
                  @if($student->status === 'Declined' && $student->decline_reason)
                    <div style="margin-top: 6px; padding: 6px 10px; background: #dc354510; border-left: 3px solid #dc3545; border-radius: 4px; font-size: 12px; color: #dc3545;">
                      <strong>Reason:</strong> {{ $student->decline_reason }}
                    </div>
                  @endif
                @else
                  <span style="color: #6c757d; font-size: 12px; font-style: italic;">â€”</span>
                @endif
              </td>
              @endif
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      @if($students->hasPages())
      <div style="padding: 20px; border-top: 1px solid #e9ecef; background: #f8f9fa;">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
          <div style="color: #6c757d; font-size: 14px;">
            Showing {{ $students->firstItem() }} to {{ $students->lastItem() }} of {{ $students->total() }} violations
          </div>
          <div style="display: flex; gap: 8px;">
            @if($students->onFirstPage())
              <span style="padding: 8px 12px; background: #e9ecef; color: #6c757d; border-radius: 4px; font-size: 14px;">Previous</span>
            @else
              <a href="{{ $students->previousPageUrl() }}" style="padding: 8px 12px; background: var(--primary-green); color: white; border-radius: 4px; font-size: 14px; text-decoration: none;">Previous</a>
            @endif
            @if($students->hasMorePages())
              <a href="{{ $students->nextPageUrl() }}" style="padding: 8px 12px; background: var(--primary-green); color: white; border-radius: 4px; font-size: 14px; text-decoration: none;">Next</a>
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

  <!-- Approval Confirmation Modal -->
  <div id="approveModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); z-index: 9999; align-items: center; justify-content: center;">
    <div style="background: white; border-radius: 12px; padding: 0; max-width: 500px; width: 90%; box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2); animation: slideDown 0.3s ease;">
      <div style="padding: 24px; border-bottom: 1px solid #e9ecef;">
        <h3 style="margin: 0; font-size: 18px; color: #333; font-weight: 600;">Approve Minor Violation</h3>
        <p style="margin: 4px 0 0; font-size: 13px; color: #6c757d;">Confirm your action</p>
      </div>
      <div style="padding: 24px;">
        <p style="margin: 0 0 16px; font-size: 15px; color: #495057; line-height: 1.6;">
          Are you sure you want to approve this minor violation for <strong id="studentName" style="color: #28a745;"></strong>?
        </p>
        <div style="background: #f8f9fa; padding: 16px; border-radius: 8px; border-left: 4px solid #28a745;">
          <p style="margin: 0; font-size: 13px; color: #495057; line-height: 1.5;">
            âœ“ This will mark the violation as <strong>Approved</strong><br>
            âœ“ It will be forwarded to the Administrator for finalization
          </p>
        </div>
      </div>
      <div style="padding: 20px 24px; background: #f8f9fa; border-top: 1px solid #e9ecef; display: flex; gap: 12px; justify-content: flex-end; border-radius: 0 0 12px 12px;">
        <button onclick="closeApproveModal()" style="padding: 10px 20px; background: white; color: #6c757d; border: 1px solid #dee2e6; border-radius: 6px; font-size: 14px; font-weight: 500; cursor: pointer;">Cancel</button>
        <button onclick="confirmApprove()" style="padding: 10px 24px; background: #28a745; color: white; border: none; border-radius: 6px; font-size: 14px; font-weight: 500; cursor: pointer;">Approve Violation</button>
      </div>
    </div>
  </div>

  <!-- Decline Modal with Reason -->
  <div id="declineModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); z-index: 9999; align-items: center; justify-content: center;">
    <div style="background: white; border-radius: 12px; padding: 0; max-width: 500px; width: 90%; box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2); animation: slideDown 0.3s ease;">
      <div style="padding: 24px; border-bottom: 1px solid #e9ecef;">
        <h3 style="margin: 0; font-size: 18px; color: #333; font-weight: 600;">Decline Minor Violation</h3>
        <p style="margin: 4px 0 0; font-size: 13px; color: #6c757d;">Provide a reason for declining</p>
      </div>
      <form id="declineForm" method="POST" action="">
        @csrf
        <div style="padding: 24px;">
          <p style="margin: 0 0 16px; font-size: 15px; color: #495057; line-height: 1.6;">
            Declining violation for <strong id="declineStudentName" style="color: #dc3545;"></strong>
          </p>
          <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #333; font-size: 14px;">Reason for declining <span style="color: #dc3545;">*</span></label>
          <textarea name="decline_reason" id="declineReasonInput" required maxlength="1000" rows="4" placeholder="Enter the reason for declining this violation..." style="width: 100%; padding: 12px; border: 2px solid #e1e5e9; border-radius: 8px; font-size: 14px; resize: vertical; box-sizing: border-box;"></textarea>
          <div style="margin-top: 8px; background: #f8f9fa; padding: 12px; border-radius: 8px; border-left: 4px solid #dc3545;">
            <p style="margin: 0; font-size: 13px; color: #495057; line-height: 1.5;">The student will be notified with this reason.</p>
          </div>
        </div>
        <div style="padding: 20px 24px; background: #f8f9fa; border-top: 1px solid #e9ecef; display: flex; gap: 12px; justify-content: flex-end; border-radius: 0 0 12px 12px;">
          <button type="button" onclick="closeDeclineModal()" style="padding: 10px 20px; background: white; color: #6c757d; border: 1px solid #dee2e6; border-radius: 6px; font-size: 14px; font-weight: 500; cursor: pointer;">Cancel</button>
          <button type="submit" class="dean-btn-danger" style="padding: 10px 24px; color: white; border: none; border-radius: 6px; font-size: 14px; font-weight: 500; cursor: pointer;">Decline Violation</button>
        </div>
      </form>
    </div>
  </div>

  <style>
    @keyframes slideDown {
      from { opacity: 0; transform: translateY(-20px); }
      to { opacity: 1; transform: translateY(0); }
    }
    .tab-filter {
      background: #f1f3f5; border: none; color: #333; padding: 8px 16px; border-radius: 8px;
      font-weight: 500; text-decoration: none; font-size: 14px; display: inline-flex; align-items: center;
      transition: all 0.2s ease;
    }
    .tab-filter:hover { background: #e8f5ec; color: #1a7f37; }
    .tab-filter.active { background: #e8f5ec; color: #1a7f37; border-bottom: 2px solid #1a7f37; }
    .main-content table td button,
    .main-content table td button:hover,
    .main-content table td button:focus,
    .main-content table td button:active,
    .main-content table td button * { color: #ffffff !important; }
    .dean-btn-danger,
    .dean-btn-danger:hover,
    .dean-btn-danger:focus,
    .dean-btn-danger:active { background: #dc3545 !important; color: #ffffff !important; }
  </style>

  <script>
    let currentFormId = null;

    function showApproveModal(violationId, studentName) {
      currentFormId = 'approve-form-' + violationId;
      document.getElementById('studentName').textContent = studentName;
      document.getElementById('approveModal').style.display = 'flex';
      document.body.style.overflow = 'hidden';
    }
    function closeApproveModal() {
      document.getElementById('approveModal').style.display = 'none';
      document.body.style.overflow = 'auto';
      currentFormId = null;
    }
    function confirmApprove() {
      if (currentFormId) document.getElementById(currentFormId).submit();
    }

    function showDeclineModal(violationId, studentName) {
      document.getElementById('declineStudentName').textContent = studentName;
      document.getElementById('declineForm').action = '/dean/violation/' + violationId + '/decline';
      document.getElementById('declineReasonInput').value = '';
      document.getElementById('declineModal').style.display = 'flex';
      document.body.style.overflow = 'hidden';
    }
    function closeDeclineModal() {
      document.getElementById('declineModal').style.display = 'none';
      document.body.style.overflow = 'auto';
    }

    document.getElementById('approveModal').addEventListener('click', function(e) { if (e.target === this) closeApproveModal(); });
    document.getElementById('declineModal').addEventListener('click', function(e) { if (e.target === this) closeDeclineModal(); });
    document.addEventListener('keydown', function(e) { if (e.key === 'Escape') { closeApproveModal(); closeDeclineModal(); } });
  </script>
</x-dashboard-layout>
