@php
  $accountType = Auth::user()->account_type;
  $roleTitle = $accountType === 'alumni' ? 'Alumni' : 'Student';
@endphp

<x-dashboard-layout>
  <x-slot name="roleTitle">{{ $roleTitle }}</x-slot>

  <x-slot name="navigation">
    @if($accountType === 'alumni')
      <x-alumni-navigation />
    @else
      <x-student-navigation />
    @endif
  </x-slot>

  <!-- Header Section -->
  <div class="header-section">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
      <div>
        <h1 class="role-title">Violation Notifications</h1>
        <p class="welcome-text">Track your violation status and updates</p>
        <div class="accent-line"></div>
      </div>
      <div style="display: flex; gap: 12px; align-items: center;">
        <div style="padding: 12px 16px; background: var(--light-green); border-radius: 8px; font-size: 14px; color: var(--primary-green); font-weight: 600;">
          {{ $notifications->count() }} Notification{{ $notifications->count() !== 1 ? 's' : '' }}
        </div>
      </div>
    </div>
  </div>

  @if(session('status'))
  <div style="background: #d4edda; color: #155724; padding: 16px; border-radius: 8px; margin-bottom: 24px; border-left: 4px solid #28a745;">
    <strong>Success!</strong> {{ session('status') }}
  </div>
  @endif

  <!-- Violations Table -->
  <div style="background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); overflow: hidden; margin-bottom: 24px;">
    <div style="padding: 20px 24px; border-bottom: 1px solid #e9ecef;">
      <h2 style="margin: 0; color: var(--primary-green); font-size: 1.15rem; font-weight: 600;">Violation History</h2>
    </div>

    @if($notifications->isEmpty())
    <div style="padding: 48px; text-align: center; color: #6c757d;">
      <svg style="width: 64px; height: 64px; margin: 0 auto 16px; color: #dee2e6;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"></path>
      </svg>
      <p style="margin: 0; font-size: 1.1rem; font-weight: 500;">No Violation Notifications</p>
      <p style="margin: 8px 0 0; font-size: 0.9rem;">You have no violation notifications at the moment.</p>
    </div>
    @else
    <div style="overflow-x: auto;">
      <table style="width: 100%; border-collapse: collapse;">
        <thead>
          <tr style="background: #f8f9fa; border-bottom: 2px solid #e9ecef;">
            <th style="padding: 14px 16px; text-align: left; font-weight: 600; color: #495057; font-size: 13px;">Reference Number</th>
            <th style="padding: 14px 16px; text-align: left; font-weight: 600; color: #495057; font-size: 13px;">Violation Type</th>
            <th style="padding: 14px 16px; text-align: left; font-weight: 600; color: #495057; font-size: 13px;">Article</th>
            <th style="padding: 14px 16px; text-align: left; font-weight: 600; color: #495057; font-size: 13px;">Date Issued</th>
            <th style="padding: 14px 16px; text-align: left; font-weight: 600; color: #495057; font-size: 13px;">Status</th>
            <th style="padding: 14px 16px; text-align: center; font-weight: 600; color: #495057; font-size: 13px;">Action</th>
          </tr>
        </thead>
        <tbody>
          @foreach($notifications as $notification)
          @php
            $sv = $notification->studentViolation;
            $viol = $sv?->violation;

            $offenseType = $sv->offense_type ?? '—';
            $article = $viol->article ?? ($articleMap[$sv->violation ?? ''] ?? '—');
            $violationDesc = $sv->violation ?? ($viol->description ?? '—');

            $statusColors = [
              '0' => ['bg' => '#ffc10720', 'text' => '#856404', 'label' => 'Under Review'],
              '1' => ['bg' => '#28a74520', 'text' => '#28a745', 'label' => 'Resolved'],
              '2' => ['bg' => '#28a74520', 'text' => '#28a745', 'label' => 'Approved'],
              'Reported' => ['bg' => '#ffc10720', 'text' => '#856404', 'label' => 'Reported'],
              'Approved' => ['bg' => '#3b82f620', 'text' => '#3b82f6', 'label' => 'Approved'],
              'Declined' => ['bg' => '#ef444420', 'text' => '#ef4444', 'label' => 'Declined'],
              'Complied' => ['bg' => '#10b98120', 'text' => '#10b981', 'label' => 'Complied'],
              'Closed' => ['bg' => '#6c757d20', 'text' => '#6c757d', 'label' => 'Closed'],
            ];
            $sc = $statusColors[$notification->status] ?? ['bg' => '#6c757d20', 'text' => '#6c757d', 'label' => ucfirst($notification->status)];

            $modalData = json_encode([
              'ref_num' => $notification->ref_num,
              'offense_type' => ucfirst($offenseType),
              'article' => $article,
              'violation' => $violationDesc,
              'issued_by' => $sv->added_by ?? '—',
              'message' => $notification->notif ?? 'No additional message provided.',
              'status_label' => $sc['label'],
              'status_color' => $sc['text'],
              'status_bg' => $sc['bg'],
              'date' => $notification->created_at->format('M d, Y'),
              'time' => $notification->created_at->format('h:i A'),
              'decline_reason' => $sv->decline_reason ?? null,
              'reviewed_by' => $sv->reviewed_by ?? null,
              'reviewed_role' => $sv->reviewed_role ?? null,
              'reviewed_at' => $sv->reviewed_at ? $sv->reviewed_at->format('M d, Y – h:i A') : null,
            ]);
          @endphp
          <tr style="border-bottom: 1px solid #e9ecef; transition: background-color 0.15s;"
              onmouseover="this.style.backgroundColor='#f8f9fa'"
              onmouseout="this.style.backgroundColor='transparent'">

            <td style="padding: 14px 16px; font-family: monospace; font-size: 13px; font-weight: 600; color: #333;">
              {{ $notification->ref_num ?? '—' }}
            </td>

            <td style="padding: 14px 16px;">
              <span style="display: inline-block; padding: 4px 10px; background: {{ $offenseType === 'major' ? '#dc354520' : '#ffc10720' }}; color: {{ $offenseType === 'major' ? '#dc3545' : '#856404' }}; border-radius: 20px; font-size: 12px; font-weight: 500;">
                {{ ucfirst($offenseType) }}
              </span>
            </td>

            <td style="padding: 14px 16px; color: #555; font-size: 13px; max-width: 200px;">
              {{ Str::limit($article, 50) }}
            </td>

            <td style="padding: 14px 16px; color: #555; font-size: 13px;">
              <div style="font-weight: 500; color: #333;">{{ $notification->created_at->format('M d, Y') }}</div>
              <div style="font-size: 11px; color: #6c757d;">{{ $notification->created_at->format('h:i A') }}</div>
            </td>

            <td style="padding: 14px 16px;">
              <span style="display: inline-block; padding: 5px 12px; background: {{ $sc['bg'] }}; color: {{ $sc['text'] }}; border-radius: 20px; font-size: 12px; font-weight: 500;">
                {{ $sc['label'] }}
              </span>
            </td>

            <td style="padding: 14px 16px; text-align: center;">
              <button type="button"
                onclick='openViolationModal({{ $modalData }})'
                style="display: inline-flex; align-items: center; gap: 6px; padding: 7px 14px; background: #f8f9fa; color: #495057; border: 1px solid #dee2e6; border-radius: 6px; font-size: 13px; font-weight: 500; cursor: pointer; transition: background 0.15s;"
                onmouseover="this.style.background='#e9ecef'" onmouseout="this.style.background='#f8f9fa'">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width:15px;height:15px;">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.964-7.178Z" />
                  <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                </svg>
                View
              </button>
            </td>

          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    @endif
  </div>

  <!-- Violation Details Modal -->
  <div id="violationModal" style="display:none; position:fixed; inset:0; z-index:1050; background:rgba(0,0,0,0.5); align-items:center; justify-content:center; padding:16px;">
    <div style="background:white; border-radius:12px; width:100%; max-width:520px; box-shadow:0 20px 60px rgba(0,0,0,0.2); overflow:hidden; max-height:90vh; display:flex; flex-direction:column;">

      <!-- Modal Header -->
      <div style="padding:20px 24px; border-bottom:1px solid #e9ecef; display:flex; justify-content:space-between; align-items:center; flex-shrink:0; background:linear-gradient(135deg, var(--primary-green) 0%, #009944 100%);">
        <div>
          <h5 style="margin:0; font-size:1.1rem; font-weight:700; color:white !important;">Violation Details</h5>
          <div id="vModalRef" style="font-family:monospace; font-size:13px; color:rgba(255,255,255,0.85); margin-top:4px;"></div>
        </div>
        <button onclick="closeViolationModal()" style="background:rgba(255,255,255,0.2); border:none; cursor:pointer; padding:6px; color:white; font-size:22px; line-height:1; border-radius:50%; width:32px; height:32px; display:flex; align-items:center; justify-content:center;">&times;</button>
      </div>

      <!-- Modal Body -->
      <div style="padding:24px; overflow-y:auto; flex:1;">

        <!-- Status Badge -->
        <div style="margin-bottom:20px;">
          <span id="vModalBadge" style="display:inline-block; padding:6px 16px; border-radius:20px; font-size:13px; font-weight:600;"></span>
        </div>

        <!-- Detail Rows -->
        <div style="display:flex; flex-direction:column; gap:16px;">

          <div style="display:flex; gap:12px;">
            <div style="min-width:130px; font-weight:600; color:#333; font-size:14px;">Offense Type</div>
            <div id="vModalOffense" style="color:#555; font-size:14px;"></div>
          </div>

          <div style="display:flex; gap:12px;">
            <div style="min-width:130px; font-weight:600; color:#333; font-size:14px;">Article</div>
            <div id="vModalArticle" style="color:#555; font-size:14px;"></div>
          </div>

          <div style="display:flex; gap:12px;">
            <div style="min-width:130px; font-weight:600; color:#333; font-size:14px;">Date Issued</div>
            <div id="vModalDate" style="color:#555; font-size:14px;"></div>
          </div>

          <div style="display:flex; gap:12px;">
            <div style="min-width:130px; font-weight:600; color:#333; font-size:14px;">Issued By</div>
            <div id="vModalIssuedBy" style="color:#555; font-size:14px;"></div>
          </div>

          <hr style="border:none; border-top:1px solid #e9ecef; margin:4px 0;">

          <div>
            <div style="font-weight:600; color:#333; font-size:14px; margin-bottom:8px;">Violation Description</div>
            <div id="vModalViolation" style="background:#f8f9fa; padding:12px 16px; border-radius:8px; color:#555; font-size:14px; line-height:1.5;"></div>
          </div>

          <div>
            <div style="font-weight:600; color:#333; font-size:14px; margin-bottom:8px;">Notification Message</div>
            <div id="vModalMessage" style="background:#f8f9fa; padding:12px 16px; border-radius:8px; color:#555; font-size:14px; line-height:1.5;"></div>
          </div>

          <!-- Decline Reason (shown when declined) -->
          <div id="vModalDeclineSection" style="display:none;">
            <div style="font-weight:600; color:#ef4444; font-size:14px; margin-bottom:8px;">Decline Reason</div>
            <div id="vModalDeclineReason" style="background:#fef2f2; padding:12px 16px; border-radius:8px; color:#ef4444; font-size:14px; line-height:1.5; border-left:3px solid #ef4444;"></div>
          </div>

          <!-- Reviewer Info -->
          <div id="vModalReviewerSection" style="display:none;">
            <div style="font-weight:600; color:#333; font-size:14px; margin-bottom:8px;">Reviewed By</div>
            <div id="vModalReviewer" style="background:#f8f9fa; padding:12px 16px; border-radius:8px; color:#555; font-size:14px; line-height:1.5;"></div>
          </div>

        </div>
      </div>

      <!-- Modal Footer -->
      <div style="padding:16px 24px; border-top:1px solid #e9ecef; text-align:right; flex-shrink:0;">
        <button onclick="closeViolationModal()" style="padding:8px 20px; background:#f8f9fa; color:#495057; border:1px solid #dee2e6; border-radius:6px; font-size:14px; font-weight:500; cursor:pointer;">Close</button>
      </div>
    </div>
  </div>

  <script>
    function openViolationModal(data) {
      document.getElementById('vModalRef').textContent = data.ref_num || '—';
      document.getElementById('vModalOffense').textContent = data.offense_type || '—';
      document.getElementById('vModalArticle').textContent = data.article || '—';
      document.getElementById('vModalDate').textContent = (data.date || '') + ' at ' + (data.time || '');
      document.getElementById('vModalIssuedBy').textContent = data.issued_by || '—';
      document.getElementById('vModalViolation').textContent = data.violation || '—';
      document.getElementById('vModalMessage').textContent = data.message || '—';

      var badge = document.getElementById('vModalBadge');
      badge.textContent = data.status_label || '';
      badge.style.background = data.status_bg || '#6c757d20';
      badge.style.color = data.status_color || '#6c757d';

      // Decline reason
      var declineSection = document.getElementById('vModalDeclineSection');
      if (data.decline_reason) {
        document.getElementById('vModalDeclineReason').textContent = data.decline_reason;
        declineSection.style.display = 'block';
      } else {
        declineSection.style.display = 'none';
      }

      // Reviewer info
      var reviewerSection = document.getElementById('vModalReviewerSection');
      if (data.reviewed_by) {
        var role = data.reviewed_role ? data.reviewed_role.replace('_', ' ') : '';
        role = role.charAt(0).toUpperCase() + role.slice(1);
        var reviewerText = role + ' ' + data.reviewed_by;
        if (data.reviewed_at) reviewerText += ' — ' + data.reviewed_at;
        document.getElementById('vModalReviewer').textContent = reviewerText;
        reviewerSection.style.display = 'block';
      } else {
        reviewerSection.style.display = 'none';
      }

      var modal = document.getElementById('violationModal');
      modal.style.display = 'flex';
    }

    function closeViolationModal() {
      document.getElementById('violationModal').style.display = 'none';
    }

    document.getElementById('violationModal').addEventListener('click', function(e) {
      if (e.target === this) closeViolationModal();
    });

    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape') closeViolationModal();
    });
  </script>

</x-dashboard-layout>