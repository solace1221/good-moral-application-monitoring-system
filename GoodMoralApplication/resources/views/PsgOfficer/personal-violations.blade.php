<x-dashboard-layout>
  <x-slot name="roleTitle">PSG Officer</x-slot>

  <x-slot name="navigation">
    <x-psg-officer-navigation />
  </x-slot>

  <!-- Header Section -->
  <div class="header-section">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
      <div>
        <h1 class="role-title">My Violations</h1>
        <p class="welcome-text">View violations that have been issued against you</p>
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
    @include('shared.alerts.flash')

    @if ($violations->isEmpty())
    <div style="text-align: center; padding: 48px; background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
      <svg style="width: 64px; height: 64px; margin: 0 auto 16px; color: #28a745;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
      </svg>
      <h3 style="margin: 0 0 8px; color: #374151; font-size: 1.25rem;">No Violations Found</h3>
      <p style="margin: 0; color: #6b7280;">You have a clean record with no violations issued against you.</p>
    </div>
    @else
    <div style="background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); overflow: hidden;">
      <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;">
          <thead>
            <tr style="background: #f8f9fa; border-bottom: 2px solid #e9ecef;">
              <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057; font-size: 14px;">Student ID</th>
              <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057; font-size: 14px;">Violation</th>
              <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057; font-size: 14px;">Offense Type</th>
              <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057; font-size: 14px;">Date Issued</th>
              <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057; font-size: 14px;">Status</th>
              <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057; font-size: 14px;">Action</th>
            </tr>
          </thead>
          <tbody>
            @foreach($violations as $violation)
            @php
              if ($violation->offense_type === 'major') {
                $badgeBg = '#fee2e2'; $badgeColor = '#dc2626'; $badgeText = 'Escalated';
              } elseif ($violation->status == 2) {
                $badgeBg = '#dcfce7'; $badgeColor = '#16a34a'; $badgeText = 'Complied';
              } else {
                $badgeBg = '#fef9c3'; $badgeColor = '#ca8a04'; $badgeText = 'Pending';
              }
            @endphp
            <tr style="border-bottom: 1px solid #e9ecef; transition: background-color 0.2s ease;"
                onmouseover="this.style.backgroundColor='#f8f9fa'"
                onmouseout="this.style.backgroundColor='transparent'">
              <td style="padding: 16px; color: #495057; font-size: 14px; font-weight: 500;">
                {{ $violation->student_id }}
              </td>
              <td style="padding: 16px; color: #495057; font-size: 14px;">
                <div style="font-weight: 500; color: #333;">{{ $violation->violation }}</div>
                @if($violation->description)
                  <div style="font-size: 12px; color: #6b7280; margin-top: 2px;">{{ Str::limit($violation->description, 60) }}</div>
                @endif
              </td>
              <td style="padding: 16px; color: #495057; font-size: 14px;">
                <span style="display: inline-block; padding: 4px 10px;
                  background: {{ $violation->offense_type === 'minor' ? '#e0f2fe' : '#fee2e2' }};
                  color: {{ $violation->offense_type === 'minor' ? '#0369a1' : '#dc2626' }};
                  border-radius: 20px; font-size: 12px; font-weight: 500; text-transform: capitalize;">
                  {{ ucfirst($violation->offense_type) }}
                </span>
              </td>
              <td style="padding: 16px; color: #6c757d; font-size: 14px;">
                {{ $violation->created_at->format('M j, Y') }}
                <div style="font-size: 12px; color: #9ca3af;">{{ $violation->created_at->format('g:i A') }}</div>
              </td>
              <td style="padding: 16px;">
                <span style="display: inline-block; padding: 6px 14px; background: {{ $badgeBg }}; color: {{ $badgeColor }}; border-radius: 20px; font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.03em;">
                  {{ $badgeText }}
                </span>
              </td>
              <td style="padding: 16px;">
                <button type="button"
                  onclick="openViolationModal({{ $violation->id }})"
                  style="padding: 7px 16px; background: var(--primary-green); color: white; border: none; border-radius: 6px; font-size: 13px; font-weight: 500; cursor: pointer; transition: opacity 0.2s;"
                  onmouseover="this.style.opacity='0.85'"
                  onmouseout="this.style.opacity='1'">
                  View
                </button>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      @if($violations->hasPages())
      <div style="padding: 20px; border-top: 1px solid #e9ecef; background: #f8f9fa;">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
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

  <!-- Violation Detail Modal -->
  <div id="violation-modal-overlay"
       onclick="if(event.target===this)closeViolationModal()"
       style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:1000; align-items:center; justify-content:center; padding:16px;">
    <div style="background:white; border-radius:16px; width:100%; max-width:560px; max-height:90vh; overflow-y:auto; box-shadow:0 20px 60px rgba(0,0,0,0.3);">

      <!-- Modal Header -->
      <div style="padding:24px 28px 20px; border-bottom:1px solid #e9ecef; display:flex; justify-content:space-between; align-items:center;">
        <h2 style="margin:0; font-size:1.2rem; font-weight:700; color:#111827;">Violation Details</h2>
        <button onclick="closeViolationModal()" style="background:none; border:none; cursor:pointer; color:#6b7280; padding:4px;">
          <svg style="width:22px;height:22px;" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
          </svg>
        </button>
      </div>

      <!-- Modal Body -->
      <div style="padding:24px 28px;">

        <!-- Student Information -->
        <div style="margin-bottom:24px;">
          <h3 style="margin:0 0 14px; font-size:0.85rem; font-weight:700; text-transform:uppercase; letter-spacing:0.07em; color:var(--primary-green);">Student Information</h3>
          <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
            <div style="background:#f8fafc; border-radius:8px; padding:12px 14px;">
              <div style="font-size:11px; color:#9ca3af; font-weight:600; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:4px;">Student ID</div>
              <div id="modal-student-id" style="font-size:14px; color:#111827; font-weight:600;">—</div>
            </div>
            <div style="background:#f8fafc; border-radius:8px; padding:12px 14px;">
              <div style="font-size:11px; color:#9ca3af; font-weight:600; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:4px;">Name</div>
              <div id="modal-name" style="font-size:14px; color:#111827; font-weight:600;">—</div>
            </div>
            <div style="background:#f8fafc; border-radius:8px; padding:12px 14px;">
              <div style="font-size:11px; color:#9ca3af; font-weight:600; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:4px;">Department</div>
              <div id="modal-department" style="font-size:14px; color:#111827;">—</div>
            </div>
            <div style="background:#f8fafc; border-radius:8px; padding:12px 14px;">
              <div style="font-size:11px; color:#9ca3af; font-weight:600; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:4px;">Course</div>
              <div id="modal-course" style="font-size:14px; color:#111827;">—</div>
            </div>
          </div>
        </div>

        <!-- Violation Information -->
        <div>
          <h3 style="margin:0 0 14px; font-size:0.85rem; font-weight:700; text-transform:uppercase; letter-spacing:0.07em; color:var(--primary-green);">Violation Information</h3>
          <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
            <div style="background:#f8fafc; border-radius:8px; padding:12px 14px;">
              <div style="font-size:11px; color:#9ca3af; font-weight:600; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:4px;">Violation Type</div>
              <div id="modal-violation" style="font-size:14px; color:#111827; font-weight:600;">—</div>
            </div>
            <div style="background:#f8fafc; border-radius:8px; padding:12px 14px;">
              <div style="font-size:11px; color:#9ca3af; font-weight:600; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:4px;">Date Issued</div>
              <div id="modal-date" style="font-size:14px; color:#111827;">—</div>
            </div>
            <div style="grid-column:1/-1; background:#f8fafc; border-radius:8px; padding:12px 14px;">
              <div style="font-size:11px; color:#9ca3af; font-weight:600; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:4px;">Description</div>
              <div id="modal-description" style="font-size:14px; color:#111827; line-height:1.6;">—</div>
            </div>
            <div style="background:#f8fafc; border-radius:8px; padding:12px 14px;">
              <div style="font-size:11px; color:#9ca3af; font-weight:600; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:4px;">Issued By</div>
              <div id="modal-issued-by" style="font-size:14px; color:#111827;">—</div>
            </div>
            <div style="background:#f8fafc; border-radius:8px; padding:12px 14px;">
              <div style="font-size:11px; color:#9ca3af; font-weight:600; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:4px;">Status</div>
              <div id="modal-status" style="font-size:14px;">—</div>
            </div>
          </div>
        </div>
      </div>

      <!-- Modal Footer -->
      <div style="padding:16px 28px; border-top:1px solid #e9ecef; text-align:right;">
        <button onclick="closeViolationModal()" style="padding:9px 20px; background:#f3f4f6; color:#374151; border:none; border-radius:8px; font-size:14px; font-weight:500; cursor:pointer;">Close</button>
      </div>
    </div>
  </div>

  <!-- Violation data for JS -->
  <script>
    const violationData = {
      @foreach($violations as $v)
      {{ $v->id }}: {
        studentId:   @json($v->student_id),
        name:        @json(trim($v->first_name . ' ' . ($v->middle_name ? $v->middle_name . ' ' : '') . $v->last_name)),
        department:  @json($v->department ?? '—'),
        course:      @json($v->course ?? '—'),
        violation:   @json($v->violation),
        description: @json($v->description ?? '—'),
        date:        @json($v->created_at->format('M j, Y g:i A')),
        issuedBy:    @json($v->added_by),
        status:      @json($v->status),
        offenseType: @json($v->offense_type),
      },
      @endforeach
    };

    function openViolationModal(id) {
      const d = violationData[id];
      if (!d) return;

      document.getElementById('modal-student-id').textContent   = d.studentId || '—';
      document.getElementById('modal-name').textContent         = d.name || '—';
      document.getElementById('modal-department').textContent   = d.department;
      document.getElementById('modal-course').textContent       = d.course;
      document.getElementById('modal-violation').textContent    = d.violation;
      document.getElementById('modal-description').textContent  = d.description;
      document.getElementById('modal-date').textContent         = d.date;
      document.getElementById('modal-issued-by').textContent    = d.issuedBy || '—';

      // Status badge
      let badgeBg, badgeColor, badgeText;
      if (d.offenseType === 'major') {
        badgeBg = '#fee2e2'; badgeColor = '#dc2626'; badgeText = 'Escalated';
      } else if (d.status == 2) {
        badgeBg = '#dcfce7'; badgeColor = '#16a34a'; badgeText = 'Complied';
      } else {
        badgeBg = '#fef9c3'; badgeColor = '#ca8a04'; badgeText = 'Pending';
      }
      const statusEl = document.getElementById('modal-status');
      statusEl.innerHTML = `<span style="display:inline-block;padding:4px 12px;background:${badgeBg};color:${badgeColor};border-radius:20px;font-size:12px;font-weight:600;text-transform:uppercase;">${badgeText}</span>`;

      const overlay = document.getElementById('violation-modal-overlay');
      overlay.style.display = 'flex';
      document.body.style.overflow = 'hidden';
    }

    function closeViolationModal() {
      document.getElementById('violation-modal-overlay').style.display = 'none';
      document.body.style.overflow = '';
    }

    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape') closeViolationModal();
    });
  </script>

</x-dashboard-layout>
