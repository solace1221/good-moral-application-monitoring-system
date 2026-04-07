<x-dashboard-layout>
  <x-slot name="roleTitle">Dean</x-slot>

  <x-slot name="navigation">
    @include('components.dean-navigation')
  </x-slot>

  <!-- Header Section -->
  <div class="header-section">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
      <div>
        <h1 class="role-title">Minor Violations</h1>
        <p class="welcome-text">Review and approve minor violations in your department</p>
        <div class="accent-line"></div>
      </div>
      <div style="display: flex; gap: 12px; align-items: center;">
        <div style="padding: 12px 16px; background: #fff3cd; border-radius: 8px; font-size: 14px; color: #856404; font-weight: 600;">
          {{ $students->total() }} Minor Violation{{ $students->total() !== 1 ? 's' : '' }}
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

    <!-- Violations Table -->
    @if($students->isEmpty())
    <div style="text-align: center; padding: 48px; background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
      <svg style="width: 64px; height: 64px; margin: 0 auto 16px; color: #9ca3af;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
      </svg>
      <h3 style="margin: 0 0 8px; color: #374151; font-size: 1.25rem;">No Minor Violations Found</h3>
      <p style="margin: 0; color: #6b7280;">There are currently no minor violations in your department.</p>
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
              <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057; font-size: 14px;">Actions</th>
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
                  // Count ALL minor violations for this student regardless of status
                  $minorCount = \App\Models\StudentViolation::where('student_id', $student->student_id)
                    ->where('offense_type', 'minor')
                    ->count(); // Count all minor violations (pending, approved, resolved)

                  $statusColor = '#28a745'; // Green
                  $statusIcon = '✅';
                  if ($minorCount >= 3) {
                    $statusColor = '#dc3545'; // Red
                    $statusIcon = '🚨';
                  } elseif ($minorCount == 2) {
                    $statusColor = '#fd7e14'; // Orange
                    $statusIcon = '⚠️';
                  } elseif ($minorCount == 1) {
                    $statusColor = '#ffc107'; // Yellow
                    $statusIcon = '⚠️';
                  }
                @endphp
                <div style="font-size: 11px; padding: 2px 6px; border-radius: 3px; display: inline-block; background: {{ $statusColor }}20; color: {{ $statusColor }}; border: 1px solid {{ $statusColor }}40;">
                  {{ $statusIcon }} {{ $minorCount }}/3 Minor Violations
                </div>
              </td>
              <td style="padding: 16px; color: #495057; font-size: 14px;">
                <span style="display: inline-block; padding: 4px 8px; background: #e3f2fd; color: #1976d2; border-radius: 4px; font-size: 12px; font-weight: 500;">
                  {{ $student->course ?? 'N/A' }}
                </span>
              </td>
              <td style="padding: 16px; color: #495057; font-size: 14px; max-width: 200px;">
                <div style="font-weight: 600; color: #333; margin-bottom: 4px;">{{ $student->violation }}</div>
                <span style="display: inline-block; padding: 4px 8px; background: #ffc10720; color: #ffc107; border-radius: 4px; font-size: 11px; font-weight: 500; text-transform: uppercase;">
                  Minor Violation
                </span>
              </td>
              <td style="padding: 16px; color: #495057; font-size: 14px;">
                @if($student->added_by)
                  <div style="display: flex; align-items: center; gap: 8px;">
                    <svg style="width: 16px; height: 16px; color: #6c757d;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    <div>
                      <div style="font-weight: 500; color: #495057; font-size: 13px;">{{ $student->added_by }}</div>
                      <div style="font-size: 11px; color: #6c757d;">PSG Officer</div>
                    </div>
                  </div>
                @else
                  <span style="color: #6c757d; font-size: 12px; font-style: italic;">Unknown</span>
                @endif
              </td>
              <td style="padding: 16px; color: #495057; font-size: 14px;">
                @if($student->status == 0)
                  <span style="display: inline-block; padding: 6px 12px; background: #ffc107; color: #333; border-radius: 20px; font-size: 12px; font-weight: 500;">
                    ⏳ Pending
                  </span>
                @elseif($student->status == 2)
                  <span style="display: inline-block; padding: 6px 12px; background: #28a745; color: white; border-radius: 20px; font-size: 12px; font-weight: 500;">
                    ✅ Resolved
                  </span>
                @else
                  <span style="display: inline-block; padding: 6px 12px; background: #17a2b8; color: white; border-radius: 20px; font-size: 12px; font-weight: 500;">
                    🔄 In Progress
                  </span>
                @endif
              </td>
              <td style="padding: 16px; color: #495057; font-size: 14px;">
                @if($student->status == 2)
                  <span style="color: #28a745; font-size: 12px; font-style: italic; padding: 8px 12px; background: #d4edda; border-radius: 6px; border: 1px solid #c3e6cb;">
                    ✅ Fully Approved (Dean + Admin)
                  </span>
                @elseif($student->status == 1)
                  <span style="color: #17a2b8; font-size: 12px; font-style: italic; padding: 8px 12px; background: #d1ecf1; border-radius: 6px; border: 1px solid #bee5eb;">
                    📤 Dean Approved - Pending Admin Approval
                  </span>
                @else
                  <form id="approve-form-{{ $student->id }}" action="{{ route('dean.violation.approve', $student->id) }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="button"
                            onclick="showApproveModal({{ $student->id }}, '{{ $student->first_name }} {{ $student->last_name }}')"
                            style="background: #28a745; color: white; border: none; padding: 8px 12px; border-radius: 6px; font-size: 12px; font-weight: 500; cursor: pointer; display: flex; align-items: center; gap: 6px; transition: all 0.2s ease;"
                            onmouseover="this.style.background='#218838'"
                            onmouseout="this.style.background='#28a745'">
                      <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                      </svg>
                      Approve (Send to Admin)
                    </button>
                  </form>
                @endif
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      @if($students->hasPages())
      <div style="padding: 20px; border-top: 1px solid #e9ecef; background: #f8f9fa;">
        <div style="display: flex; justify-content: between; align-items: center; flex-wrap: wrap; gap: 16px;">
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
      <!-- Modal Header -->
      <div style="padding: 24px; border-bottom: 1px solid #e9ecef; display: flex; align-items: center; gap: 12px;">
        <div style="width: 48px; height: 48px; background: #28a74520; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
          <svg style="width: 24px; height: 24px; color: #28a745;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
          </svg>
        </div>
        <div style="flex: 1;">
          <h3 style="margin: 0; font-size: 18px; color: #333; font-weight: 600;">Approve Minor Violation</h3>
          <p style="margin: 4px 0 0; font-size: 13px; color: #6c757d;">Confirm your action</p>
        </div>
      </div>
      
      <!-- Modal Body -->
      <div style="padding: 24px;">
        <p style="margin: 0 0 16px; font-size: 15px; color: #495057; line-height: 1.6;">
          Are you sure you want to approve this minor violation for <strong id="studentName" style="color: #28a745;"></strong>?
        </p>
        <div style="background: #f8f9fa; padding: 16px; border-radius: 8px; border-left: 4px solid #28a745;">
          <p style="margin: 0; font-size: 13px; color: #495057; line-height: 1.5;">
            ✓ This will mark the violation as <strong>Dean Approved</strong><br>
            ✓ It will be forwarded to the Administrator for final approval
          </p>
        </div>
      </div>
      
      <!-- Modal Footer -->
      <div style="padding: 20px 24px; background: #f8f9fa; border-top: 1px solid #e9ecef; display: flex; gap: 12px; justify-content: flex-end; border-radius: 0 0 12px 12px;">
        <button onclick="closeApproveModal()" 
                style="padding: 10px 20px; background: white; color: #6c757d; border: 1px solid #dee2e6; border-radius: 6px; font-size: 14px; font-weight: 500; cursor: pointer; transition: all 0.2s ease;"
                onmouseover="this.style.background='#e9ecef'"
                onmouseout="this.style.background='white'">
          Cancel
        </button>
        <button onclick="confirmApprove()" 
                style="padding: 10px 24px; background: #28a745; color: white; border: none; border-radius: 6px; font-size: 14px; font-weight: 500; cursor: pointer; transition: all 0.2s ease; display: flex; align-items: center; gap: 8px;"
                onmouseover="this.style.background='#218838'"
                onmouseout="this.style.background='#28a745'">
          <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
          </svg>
          Approve Violation
        </button>
      </div>
    </div>
  </div>

  <style>
    @keyframes slideDown {
      from {
        opacity: 0;
        transform: translateY(-20px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }
  </style>

  <script>
    let currentFormId = null;

    function showApproveModal(violationId, studentName) {
      currentFormId = 'approve-form-' + violationId;
      document.getElementById('studentName').textContent = studentName;
      const modal = document.getElementById('approveModal');
      modal.style.display = 'flex';
      document.body.style.overflow = 'hidden';
    }

    function closeApproveModal() {
      const modal = document.getElementById('approveModal');
      modal.style.display = 'none';
      document.body.style.overflow = 'auto';
      currentFormId = null;
    }

    function confirmApprove() {
      if (currentFormId) {
        document.getElementById(currentFormId).submit();
      }
    }

    // Close modal when clicking outside
    document.getElementById('approveModal').addEventListener('click', function(e) {
      if (e.target === this) {
        closeApproveModal();
      }
    });

    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape') {
        closeApproveModal();
      }
    });
  </script>
</x-dashboard-layout>