<x-dashboard-layout>
  <x-slot name="roleTitle">Moderator</x-slot>

  <x-slot name="navigation">
    <x-moderator-navigation />
  </x-slot>

  <!-- Header Section -->
  <div class="header-section">
    <h1 class="role-title">Minor Violations</h1>
    <p class="welcome-text">Review and manage minor violations</p>
    <div class="accent-line"></div>
  </div>

  <!-- Search and Filter Section -->
  <div style="background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); overflow: hidden; margin-bottom: 24px;">
    <div style="padding: 16px 24px; border-bottom: 1px solid #e9ecef;">
      <h3 style="margin: 0; color: var(--primary-green); font-size: 1rem; font-weight: 600;">Search & Filter Minor Violations</h3>
    </div>
    <form method="GET" action="{{ route('sec_osa.searchMinor') }}" style="padding: 20px 24px;">
      <div style="display: grid; grid-template-columns: 2fr 1fr auto; gap: 12px; align-items: end;">

        <!-- Name -->
        <div>
          <label style="display: block; margin-bottom: 6px; font-weight: 500; color: #495057; font-size: 13px;">Name</label>
          <input type="text" name="name" value="{{ request('name') }}"
                 placeholder="Search student name"
                 style="width: 100%; padding: 9px 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
        </div>

        <!-- Department -->
        <div>
          <label style="display: block; margin-bottom: 6px; font-weight: 500; color: #495057; font-size: 13px;">Department</label>
          <select name="department" style="width: 100%; padding: 9px 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
            <option value="">All Departments</option>
            @foreach ($departments as $dept)
              <option value="{{ $dept }}" {{ request('department') == $dept ? 'selected' : '' }}>{{ $dept }}</option>
            @endforeach
          </select>
        </div>

        <!-- Buttons -->
        <div style="display: flex; gap: 6px;">
          <button type="submit" class="btn btn-success btn-sm" style="display: inline-flex; align-items: center; gap: 5px; color: #fff !important;">
            <svg width="13" height="13" fill="#fff" viewBox="0 0 16 16">
              <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
            </svg>
            Search
          </button>
          <a href="{{ route('sec_osa.minor') }}" class="btn btn-secondary btn-sm" style="display: inline-flex; align-items: center; gap: 5px; color: #fff !important; text-decoration: none;">
            <svg width="13" height="13" fill="#fff" viewBox="0 0 16 16">
              <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
              <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708"/>
            </svg>
            Clear
          </a>
        </div>

      </div>
    </form>
  </div>

  <!-- Violations Table -->
  <div style="background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); overflow: hidden;">
    @if ($students->isEmpty())
      <div style="padding: 48px; text-align: center; color: #6c757d;">
        <svg style="width: 64px; height: 64px; margin: 0 auto 16px; color: #dee2e6;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <p style="margin: 0; font-size: 1.1rem; font-weight: 500;">No minor violations found</p>
        <p style="margin: 8px 0 0; font-size: 0.9rem;">Minor violations will appear here for review</p>
      </div>
    @else
      <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;">
          <thead>
            <tr style="background: #f8f9fa; border-bottom: 2px solid #e9ecef;">
              <th style="padding: 12px 16px; text-align: left; font-weight: 600; color: #495057; font-size: 13px;">Reference No.</th>
              <th style="padding: 12px 16px; text-align: left; font-weight: 600; color: #495057; font-size: 13px;">Student</th>
              <th style="padding: 12px 16px; text-align: left; font-weight: 600; color: #495057; font-size: 13px;">Article</th>
              <th style="padding: 12px 16px; text-align: left; font-weight: 600; color: #495057; font-size: 13px;">Status</th>
              <th style="padding: 12px 16px; text-align: left; font-weight: 600; color: #495057; font-size: 13px;">Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($students as $student)
            @php
              $violationText = $student->getRawOriginal('violation');
              $article = $student->violation?->article
                  ?? ($violationText ? ($articleMap[$violationText] ?? '—') : '—');
            @endphp
            <tr style="border-bottom: 1px solid #f0f0f0;"
                onmouseover="this.style.backgroundColor='#f8f9fa'"
                onmouseout="this.style.backgroundColor='transparent'">

              {{-- Reference No. --}}
              <td style="padding: 12px 16px; font-size: 13px;">
                @if($student->ref_num)
                  <span style="font-family: monospace; background: #f8f9fa; padding: 3px 7px; border-radius: 4px; font-size: 12px; color: #495057;">
                    {{ $student->ref_num }}
                  </span>
                @else
                  <span style="color: #adb5bd; font-size: 12px; font-style: italic;">No Reference</span>
                @endif
              </td>

              {{-- Student --}}
              <td style="padding: 12px 16px; font-size: 13px;">
                <div style="font-weight: 600; color: #212529;">{{ $student->first_name }} {{ $student->last_name }}</div>
                <div style="font-size: 12px; color: #6c757d; margin-top: 2px;">ID: {{ $student->student_id }}</div>
                <div style="font-size: 12px; color: #6c757d;">{{ $student->department }}</div>
              </td>

              {{-- Article --}}
              <td style="padding: 12px 16px; font-size: 13px; color: #495057;">
                {{ $article }}
              </td>

              {{-- Status --}}
              <td style="padding: 12px 16px;">
                @if($student->status == 0)
                  <span style="display: inline-flex; align-items: center; gap: 5px; padding: 4px 10px; background: #fff3cd; color: #856404; border-radius: 12px; font-size: 12px; font-weight: 500;">
                    <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    Pending
                  </span>
                @else
                  <span style="display: inline-flex; align-items: center; gap: 5px; padding: 4px 10px; background: #d4edda; color: #155724; border-radius: 12px; font-size: 12px; font-weight: 500;">
                    <svg width="12" height="12" fill="currentColor" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Complied
                  </span>
                @endif
              </td>

              {{-- Actions --}}
              <td style="padding: 12px 16px;">
                <button type="button"
                   class="btn btn-secondary btn-sm viewViolationBtn"
                   data-id="{{ $student->id }}"
                   style="display: inline-flex; align-items: center; gap: 4px; color: #fff !important; cursor: pointer;">
                  <svg width="13" height="13" fill="#fff" viewBox="0 0 16 16"><path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8M1.173 8a13 13 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5s3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5s-3.879-1.168-5.168-2.457A13 13 0 0 1 1.172 8z"/><path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5M4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0"/></svg>
                  View
                </button>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      {{-- Pagination --}}
      <div style="padding: 16px 24px; border-top: 1px solid #e9ecef; background: #f8f9fa;">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
          <span style="font-size: 13px; color: #495057;">
            Showing {{ $students->firstItem() ?? 0 }} to {{ $students->lastItem() ?? 0 }} of {{ $students->total() }} results
          </span>
          @if($students->hasPages())
            <div class="minor-pagination">
              {{ $students->links() }}
            </div>
          @endif
        </div>
      </div>
    @endif
  </div>

  <!-- Violation Detail Modal -->
  <div id="violationModalOverlay" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:9999; justify-content:center; align-items:center;">
    <div style="background:#fff; border-radius:12px; width:90%; max-width:720px; max-height:85vh; overflow-y:auto; box-shadow:0 8px 32px rgba(0,0,0,0.25); animation: modalSlideIn 0.2s ease;">
      <!-- Modal Header -->
      <div style="display:flex; justify-content:space-between; align-items:center; padding:20px 24px; border-bottom:1px solid #e9ecef;">
        <h5 style="margin:0; font-size:1.1rem; font-weight:700; color:var(--primary-green);">Violation Details</h5>
        <button type="button" id="closeViolationModal" style="background:none; border:none; font-size:22px; cursor:pointer; color:#6c757d; padding:0; line-height:1;">&times;</button>
      </div>
      <!-- Modal Body -->
      <div id="violationModalContent" style="padding:24px;">
        <div style="text-align:center; padding:32px; color:#6c757d;">Loading...</div>
      </div>
    </div>
  </div>

  <style>
    @keyframes modalSlideIn {
      from { opacity: 0; transform: translateY(-20px); }
      to { opacity: 1; transform: translateY(0); }
    }

    /* Pagination styling */
    .minor-pagination nav { display: flex; align-items: center; gap: 4px; }
    .minor-pagination nav > div:first-child { display: none; }
    .minor-pagination nav > div:last-child span,
    .minor-pagination nav > div:last-child a {
      display: inline-flex !important;
      align-items: center;
      justify-content: center;
      min-width: 36px;
      height: 36px;
      padding: 4px 12px;
      font-size: 13px;
      font-weight: 500;
      border-radius: 6px;
      text-decoration: none;
      transition: all 0.15s ease;
    }
    .minor-pagination nav > div:last-child a {
      background: #f3f4f6 !important;
      color: #333 !important;
      border: 1px solid #d1d5db !important;
    }
    .minor-pagination nav > div:last-child a:hover {
      background: #e5e7eb !important;
    }
    .minor-pagination nav > div:last-child span[aria-current="page"] span {
      background: #198754 !important;
      border-color: #198754 !important;
      color: #fff !important;
    }
    .minor-pagination nav > div:last-child span:not([aria-current]) {
      background: #f3f4f6 !important;
      color: #9ca3af !important;
      border: 1px solid #e5e7eb !important;
      cursor: default;
    }
  </style>

  <script>
  document.addEventListener('DOMContentLoaded', function () {
    const overlay = document.getElementById('violationModalOverlay');
    const content = document.getElementById('violationModalContent');
    const closeBtn = document.getElementById('closeViolationModal');

    function openModal() {
      overlay.style.display = 'flex';
      document.body.style.overflow = 'hidden';
    }

    function closeModal() {
      overlay.style.display = 'none';
      document.body.style.overflow = '';
    }

    closeBtn.addEventListener('click', closeModal);
    overlay.addEventListener('click', function (e) {
      if (e.target === overlay) closeModal();
    });
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape') closeModal();
    });

    function statusBadge(status) {
      if (status == 0) return '<span style="display:inline-flex;align-items:center;gap:5px;padding:4px 10px;background:#fff3cd;color:#856404;border-radius:12px;font-size:12px;font-weight:500;">Pending</span>';
      return '<span style="display:inline-flex;align-items:center;gap:5px;padding:4px 10px;background:#d4edda;color:#155724;border-radius:12px;font-size:12px;font-weight:500;">Complied</span>';
    }

    function field(label, value) {
      return '<div style="padding:14px;background:#f8f9fa;border-radius:8px;">' +
        '<strong style="color:#495057;display:block;margin-bottom:4px;font-size:12px;text-transform:uppercase;letter-spacing:0.5px;">' + label + '</strong>' +
        '<span style="color:#212529;font-size:14px;">' + (value || '<em style=\"color:#adb5bd;\">N/A</em>') + '</span></div>';
    }

    document.querySelectorAll('.viewViolationBtn').forEach(function (btn) {
      btn.addEventListener('click', function () {
        var id = this.getAttribute('data-id');
        content.innerHTML = '<div style="text-align:center;padding:32px;color:#6c757d;">Loading...</div>';
        openModal();

        fetch('/sec_osa/minor/' + id, {
          headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
          }
        })
        .then(function (res) { return res.json(); })
        .then(function (json) {
          if (!json.success) {
            content.innerHTML = '<div style="text-align:center;padding:32px;color:#dc3545;">Failed to load details.</div>';
            return;
          }
          var d = json.data;
          var html = '<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:12px;margin-bottom:16px;">';
          html += field('Student Name', d.first_name + ' ' + d.last_name);
          html += field('Student ID', '<span style="font-family:monospace;">' + (d.student_id || '') + '</span>');
          html += field('Department', d.department);
          html += field('Course', d.course);
          html += '</div>';

          html += '<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:12px;margin-bottom:16px;">';
          html += field('Reference No.', d.ref_num ? '<span style="font-family:monospace;">' + d.ref_num + '</span>' : null);
          html += field('Article', d.article);
          html += field('Offense Type', '<span style="display:inline-block;padding:3px 10px;background:#fff3cd;color:#856404;border-radius:12px;font-size:12px;font-weight:700;text-transform:uppercase;">' + (d.offense_type || 'Minor') + '</span>');
          html += field('Status', statusBadge(d.status));
          html += '</div>';

          html += '<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:12px;margin-bottom:16px;">';
          html += field('Added By', d.added_by);
          html += field('Date Recorded', d.created_at);
          html += '</div>';

          if (d.violation) {
            html += '<div style="padding:16px;background:#fff3cd;border-radius:8px;border-left:4px solid #ffc107;margin-top:4px;">' +
              '<strong style="color:#856404;display:block;margin-bottom:8px;font-size:12px;text-transform:uppercase;letter-spacing:0.5px;">Violation Description</strong>' +
              '<p style="margin:0;color:#856404;line-height:1.6;font-size:14px;">' + d.violation + '</p></div>';
          }

          content.innerHTML = html;
        })
        .catch(function () {
          content.innerHTML = '<div style="text-align:center;padding:32px;color:#dc3545;">An error occurred. Please try again.</div>';
        });
      });
    });
  });
  </script>

</x-dashboard-layout>
