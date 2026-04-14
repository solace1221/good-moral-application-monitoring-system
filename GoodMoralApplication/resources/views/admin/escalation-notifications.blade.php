<x-dashboard-layout>
  <x-slot name="roleTitle">Admin</x-slot>

  <x-slot name="navigation">
    <x-admin-navigation />
  </x-slot>

  <!-- Header Section -->
  <div class="header-section">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
      <div>
        <h1 class="role-title">Escalation Notifications</h1>
        <p class="welcome-text">Students with 3 Minor Violations (Auto-Escalation to Major)</p>
        <div class="accent-line"></div>
      </div>
      <div style="display: flex; gap: 12px; align-items: center;">
        <div style="padding: 10px 16px; background: var(--light-green); border-radius: 8px; font-size: 14px; color: var(--primary-green); font-weight: 600;">
          {{ count($escalationNotifications) }} Student{{ count($escalationNotifications) !== 1 ? 's' : '' }} with 3+ Minor Violations
        </div>
      </div>
    </div>
  </div>

  <!-- Table Section -->
  <div class="header-section" style="margin-top: 24px; padding: 0; overflow: hidden;">

    @if(empty($escalationNotifications))
    <div style="padding: 40px; background: #f8f9fa; color: #6c757d; text-align: center; border: 2px dashed #dee2e6; border-radius: 8px; margin: 24px;">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="height: 48px; width: 48px; margin: 0 auto 16px; color: #adb5bd; display: block;">
        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
      </svg>
      <h4 style="font-size: 1.2rem; margin-bottom: 8px; color: #495057;">No Escalation Alerts</h4>
      <p style="margin: 0;">No students currently have 3 or more minor violations requiring escalation.</p>
    </div>
    @else
    <div style="overflow-x: auto;">
      <table style="width: 100%; border-collapse: collapse; table-layout: fixed;">
        <colgroup>
          <col style="width: 16%;">
          <col style="width: 18%;">
          <col style="width: 13%;">
          <col style="width: 12%;">
          <col style="width: 10%;">
          <col style="width: 14%;">
          <col style="width: 9%;">
          <col style="width: 8%;">
        </colgroup>
        <thead>
          <tr style="background: white; border-bottom: 2px solid #e5e7eb;">
            <th style="padding: 12px 12px 12px 20px; text-align: left; font-weight: 600; color: #111827; font-size: 13px;">Reference No.</th>
            <th style="padding: 12px; text-align: left; font-weight: 600; color: #111827; font-size: 13px;">Student Name</th>
            <th style="padding: 12px; text-align: left; font-weight: 600; color: #111827; font-size: 13px;">Student ID</th>
            <th style="padding: 12px; text-align: left; font-weight: 600; color: #111827; font-size: 13px;">Department</th>
            <th style="padding: 12px; text-align: center; font-weight: 600; color: #111827; font-size: 13px;">Minor Violations</th>
            <th style="padding: 12px; text-align: left; font-weight: 600; color: #111827; font-size: 13px;">Status</th>
            <th style="padding: 12px; text-align: center; font-weight: 600; color: #111827; font-size: 13px;">Latest</th>
            <th style="padding: 12px 20px 12px 12px; text-align: center; font-weight: 600; color: #111827; font-size: 13px;">Actions</th>
          </tr>
        </thead>
        <tbody>
          @foreach($escalationNotifications as $i => $notification)
          <tr style="border-bottom: 1px solid #e9ecef; transition: background-color 0.15s;"
              onmouseover="this.style.backgroundColor='#f8f9fa'"
              onmouseout="this.style.backgroundColor='transparent'">

            {{-- Reference No. --}}
            <td style="padding: 14px 12px 14px 20px; font-size: 13px;">
              @if($notification['auto_major_violation'])
                <span style="font-family: monospace; font-size: 11px; color: #374151; word-break: break-all;">
                  {{ $notification['auto_major_violation']->ref_num ?? '—' }}
                </span>
              @else
                <span style="color: #9ca3af; font-size: 12px; font-style: italic;">Not yet assigned</span>
              @endif
            </td>

            {{-- Student Name --}}
            <td style="padding: 14px 12px; font-size: 13px;">
              <div style="font-weight: 600; color: #111827;">{{ $notification['fullname'] }}</div>
            </td>

            {{-- Student ID --}}
            <td style="padding: 14px 12px; font-size: 13px; color: #6b7280; font-family: monospace;">
              {{ $notification['student_id'] }}
            </td>

            {{-- Department --}}
            <td style="padding: 14px 12px; font-size: 13px; color: #374151;">
              {{ $notification['department'] ?? '—' }}
            </td>

            {{-- Minor Violations Count --}}
            <td style="padding: 14px 12px; text-align: center;">
              <span style="display: inline-block; background: #fef3c7; color: #92400e; font-weight: 700; font-size: 14px; padding: 4px 12px; border-radius: 20px; border: 1px solid #f59e0b;">
                {{ $notification['minor_violation_count'] }}
              </span>
            </td>

            {{-- Status --}}
            <td style="padding: 14px 12px; font-size: 13px;">
              @if($notification['escalation_status'] === 'escalated')
                <span style="display: inline-block; padding: 4px 12px; border-radius: 20px; font-size: 11px; font-weight: 700; text-transform: uppercase; background: #f97316; color: white !important; letter-spacing: 0.5px;">
                  Escalated
                </span>
              @else
                <span style="display: inline-block; padding: 4px 12px; border-radius: 20px; font-size: 11px; font-weight: 700; text-transform: uppercase; background: #dc3545; color: white !important; letter-spacing: 0.5px;">
                  Needs Escalation
                </span>
              @endif
            </td>

            {{-- Latest Date --}}
            <td style="padding: 14px 12px; text-align: center; font-size: 12px; color: #6b7280;">
              {{ $notification['latest_violation_date'] ? $notification['latest_violation_date']->format('M j, Y') : '—' }}
            </td>

            {{-- Actions --}}
            <td style="padding: 14px 20px 14px 12px; text-align: center;">
              <button onclick="openEscModal({{ $i }})"
                      style="display: inline-flex; align-items: center; gap: 5px; padding: 6px 13px; background: var(--primary-green); color: white !important; border: none; border-radius: 6px; font-size: 12px; font-weight: 600; cursor: pointer; white-space: nowrap; transition: background 0.15s;"
                      onmouseover="this.style.background='#059669'" onmouseout="this.style.background='var(--primary-green)'">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="white" style="width: 13px; height: 13px; flex-shrink: 0;">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.964-7.178Z" />
                  <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                </svg>
                <span style="color: white !important;">View</span>
              </button>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    @endif

  </div>

  <!-- Detail Modal -->
  <div id="escModal" style="display: none; position: fixed; inset: 0; z-index: 9999; align-items: center; justify-content: center; padding: 20px;">
    <div style="position: absolute; inset: 0; background: rgba(0,0,0,0.5);" onclick="closeEscModal()"></div>
    <div style="position: relative; background: white; border-radius: 12px; width: 100%; max-width: 760px; max-height: 88vh; display: flex; flex-direction: column; box-shadow: 0 20px 60px rgba(0,0,0,0.3); overflow: hidden; margin: auto;">

      <!-- Modal Header -->
      <div style="display: flex; justify-content: space-between; align-items: center; padding: 18px 24px; border-bottom: 1px solid #e5e7eb; background: #f9fafb; flex-shrink: 0;">
        <div>
          <h3 id="escModalTitle" style="margin: 0; font-size: 16px; font-weight: 700; color: #111827;"></h3>
          <p id="escModalSub" style="margin: 4px 0 0; font-size: 13px; color: #6b7280;"></p>
        </div>
        <button onclick="closeEscModal()" style="background: none; border: none; cursor: pointer; padding: 4px; border-radius: 6px; color: #6b7280; line-height: 1;" onmouseover="this.style.background='#e5e7eb'" onmouseout="this.style.background='none'">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 20px; height: 20px;">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
          </svg>
        </button>
      </div>

      <!-- Modal Body (scrollable) -->
      <div id="escModalBody" style="overflow-y: auto; padding: 24px; display: flex; flex-direction: column; gap: 20px;">
      </div>

      <!-- Modal Footer -->
      <div style="padding: 14px 24px; border-top: 1px solid #e5e7eb; background: #f9fafb; flex-shrink: 0; display: flex; justify-content: flex-end; gap: 10px;" id="escModalFooter">
      </div>
    </div>
  </div>

  @php
    $escJsData = [];
    foreach ($escalationNotifications as $n) {
      $mvList = [];
      foreach ($n['minor_violations'] as $mv) {
        $mvList[] = [
          'violation'  => $mv->violation,
          'ref_num'    => $mv->ref_num,
          'date'       => $mv->created_at->format('M j, Y'),
          'status'     => $mv->status,
        ];
      }
      $amv = $n['auto_major_violation'];
      $escJsData[] = [
        'fullname'             => $n['fullname'],
        'student_id'           => $n['student_id'],
        'department'           => $n['department'] ?? '—',
        'minor_violation_count'=> $n['minor_violation_count'],
        'escalation_status'    => $n['escalation_status'],
        'latest_date'          => $n['latest_violation_date'] ? $n['latest_violation_date']->format('F j, Y g:i A') : null,
        'minor_violations'     => $mvList,
        'esc_ref_num'          => $amv ? ($amv->ref_num ?? null) : null,
        'esc_violation'        => $amv ? $amv->violation : null,
        'esc_created'          => $amv ? $amv->created_at->format('M j, Y g:i A') : null,
      ];
    }
  @endphp

<script>
const escData = @json($escJsData);

const statusLabel = { '0': 'PENDING', '1': 'APPROVED', '2': 'RESOLVED' };
const statusColor = { '0': '#f59e0b', '1': '#10b981', '2': '#6b7280' };

function openEscModal(i) {
  const n = escData[i];

  // Header
  document.getElementById('escModalTitle').textContent = n.fullname;
  document.getElementById('escModalSub').textContent   = 'ID: ' + n.student_id + '  ·  ' + n.department;

  // Build body HTML
  let html = '';

  // Student info strip
  html += `
    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:12px;">
      <div style="background:#f9fafb;border:1px solid #e5e7eb;border-radius:8px;padding:12px 14px;">
        <p style="margin:0 0 4px;font-size:11px;font-weight:700;color:#6b7280;text-transform:uppercase;letter-spacing:.5px;">Student</p>
        <p style="margin:0;font-size:13px;font-weight:600;color:#111827;">${escHtml(n.fullname)}</p>
      </div>
      <div style="background:#f9fafb;border:1px solid #e5e7eb;border-radius:8px;padding:12px 14px;">
        <p style="margin:0 0 4px;font-size:11px;font-weight:700;color:#6b7280;text-transform:uppercase;letter-spacing:.5px;">Student ID</p>
        <p style="margin:0;font-size:13px;font-weight:600;color:#374151;font-family:monospace;">${escHtml(n.student_id)}</p>
      </div>
      <div style="background:#f9fafb;border:1px solid #e5e7eb;border-radius:8px;padding:12px 14px;">
        <p style="margin:0 0 4px;font-size:11px;font-weight:700;color:#6b7280;text-transform:uppercase;letter-spacing:.5px;">Department</p>
        <p style="margin:0;font-size:13px;font-weight:600;color:#374151;">${escHtml(n.department)}</p>
      </div>
    </div>`;

  // Latest violation date
  html += `
    <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:8px;padding:14px 16px;display:flex;align-items:center;gap:14px;">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width:20px;height:20px;color:#16a34a;flex-shrink:0;">
        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
      </svg>
      <div>
        <p style="margin:0 0 2px;font-size:11px;font-weight:700;color:#15803d;text-transform:uppercase;letter-spacing:.5px;">Latest Violation Date</p>
        <p style="margin:0;font-size:14px;font-weight:600;color:#14532d;">${n.latest_date ? escHtml(n.latest_date) : 'N/A'}</p>
      </div>
    </div>`;

  // Minor violations list
  html += `
    <div>
      <p style="margin:0 0 10px;font-size:13px;font-weight:700;color:#374151;">Minor Violations (${n.minor_violation_count})</p>
      <div style="display:flex;flex-direction:column;gap:8px;">`;
  n.minor_violations.forEach((mv, idx) => {
    const sl = statusLabel[mv.status] || 'UNKNOWN';
    const sc = statusColor[mv.status] || '#ef4444';
    html += `
        <div style="background:white;border:1px solid #e5e7eb;border-left:3px solid #f59e0b;border-radius:6px;padding:10px 12px;">
          <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:8px;">
            <div style="flex:1;">
              <span style="font-size:12px;font-weight:600;color:#374151;">${idx+1}. ${escHtml(mv.violation)}</span>
              ${mv.ref_num ? `<span style="display:block;font-size:11px;color:#9ca3af;font-family:monospace;margin-top:2px;">Ref: ${escHtml(mv.ref_num)}</span>` : ''}
            </div>
            <div style="text-align:right;flex-shrink:0;">
              <span style="display:block;font-size:11px;color:#6b7280;margin-bottom:3px;">${escHtml(mv.date)}</span>
              <span style="display:inline-block;padding:2px 8px;border-radius:4px;font-size:10px;font-weight:600;color:white;background:${sc};">${sl}</span>
            </div>
          </div>
        </div>`;
  });
  html += `</div></div>`;

  // Escalation information
  html += `
    <div style="background:white;border:1px solid #e5e7eb;border-radius:8px;padding:16px;">
      <p style="margin:0 0 12px;font-size:12px;font-weight:700;color:#6b7280;text-transform:uppercase;letter-spacing:.5px;">Escalation Information</p>`;

  if (n.esc_ref_num) {
    html += `
      <div style="display:flex;flex-direction:column;gap:8px;">
        <div style="display:flex;gap:10px;font-size:13px;">
          <span style="color:#6b7280;width:110px;flex-shrink:0;">Reference:</span>
          <span style="font-family:monospace;font-weight:600;color:#374151;">${escHtml(n.esc_ref_num)}</span>
        </div>
        <div style="display:flex;gap:10px;font-size:13px;">
          <span style="color:#6b7280;width:110px;flex-shrink:0;">Violation:</span>
          <span style="color:#374151;">${escHtml(n.esc_violation)}</span>
        </div>
        <div style="display:flex;gap:10px;font-size:13px;">
          <span style="color:#6b7280;width:110px;flex-shrink:0;">Created:</span>
          <span style="color:#374151;">${escHtml(n.esc_created)}</span>
        </div>
        <div style="display:flex;gap:10px;align-items:center;font-size:13px;">
          <span style="color:#6b7280;width:110px;flex-shrink:0;">Status:</span>
          <span style="display:inline-block;padding:3px 12px;border-radius:20px;font-size:11px;font-weight:700;text-transform:uppercase;background:#f97316;color:white;">Escalated</span>
        </div>
      </div>`;
  } else {
    html += `
      <div style="display:flex;align-items:center;gap:10px;padding:12px;background:#fff7ed;border:1px solid #fed7aa;border-radius:6px;">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="height:18px;width:18px;color:#ea580c;flex-shrink:0;">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
        </svg>
        <span style="font-size:13px;color:#9a3412;">Auto-escalation major violation not yet created.</span>
      </div>`;
  }
  html += `</div>`;

  document.getElementById('escModalBody').innerHTML = html;

  // Footer
  let footer = `<button onclick="closeEscModal()" style="padding:8px 20px;background:white;color:#374151;border:1px solid #d1d5db;border-radius:6px;font-size:13px;font-weight:600;cursor:pointer;">Close</button>`;
  if (!n.esc_ref_num) {
    footer += `<button onclick="closeEscModal();triggerEscalation('${escHtml(n.student_id)}')" style="padding:8px 20px;background:#dc3545;color:white;border:none;border-radius:6px;font-size:13px;font-weight:600;cursor:pointer;">Trigger Manual Escalation</button>`;
  }
  document.getElementById('escModalFooter').innerHTML = footer;

  const modal = document.getElementById('escModal');
  modal.style.display = 'flex';
  document.body.style.overflow = 'hidden';
}

function closeEscModal() {
  document.getElementById('escModal').style.display = 'none';
  document.body.style.overflow = '';
}

function escHtml(str) {
  if (str == null) return '';
  return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

document.addEventListener('keydown', function(e) {
  if (e.key === 'Escape') closeEscModal();
});

function triggerEscalation(studentId) {
  if (confirm('Are you sure you want to manually trigger escalation for this student? This will create a major violation.')) {
    fetch(`/admin/trigger-escalation/${studentId}`, {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        'Content-Type': 'application/json',
      },
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        alert('Escalation triggered successfully!');
        location.reload();
      } else {
        alert('Error triggering escalation: ' + data.message);
      }
    })
    .catch(error => {
      console.error('Error:', error);
      alert('An error occurred while triggering escalation.');
    });
  }
}
</script>

</x-dashboard-layout>
