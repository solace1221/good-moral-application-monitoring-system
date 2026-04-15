<x-dashboard-layout>
  <x-slot name="roleTitle">Admin</x-slot>

  <x-slot name="navigation">
    <x-admin-navigation />
  </x-slot>

  <!-- Header Section -->
  <div class="header-section">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
      <div>
        <h1 class="role-title">Position Management</h1>
        <p class="welcome-text">Manage positions for organizations</p>
        <div class="accent-line"></div>
      </div>
      <a href="{{ route('admin.positions.create') }}" class="btn-primary"
         style="padding: 10px 16px; font-size: 14px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px;">
        <svg xmlns="http://www.w3.org/2000/svg" style="width: 18px; height: 18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
        </svg>
        Add New Position
      </a>
    </div>
  </div>

  <!-- Main Content -->
  <div class="content-section">
    <div class="card">
      @include('shared.alerts.flash')

      @php
        $deptBadge = [
          'SASTE'   => ['bg' => '#eff6ff', 'color' => '#3b82f6', 'border' => '1px solid #bfdbfe'],
          'SBAHM'   => ['bg' => '#f0fdf4', 'color' => '#16a34a', 'border' => '1px solid #bbf7d0'],
          'SITE'    => ['bg' => '#f3f4f6', 'color' => '#6b7280', 'border' => '1px solid #d1d5db'],
          'SNAHS'   => ['bg' => '#fff1f2', 'color' => '#f43f5e', 'border' => '1px solid #fecdd3'],
          'SOM'     => ['bg' => '#ffffff', 'color' => '#6b7280', 'border' => '1px solid #d1d5db'],
          'GRADSCH' => ['bg' => '#fefce8', 'color' => '#ca8a04', 'border' => '1px solid #fde68a'],
        ];
        $univBadge = ['bg' => '#f0fdfa', 'color' => '#0f766e', 'border' => '1px solid #99f6e4'];
      @endphp

      <!-- Filter Bar -->
      <div style="margin-bottom: 20px; display: flex; flex-wrap: wrap; gap: 10px; align-items: center;">
        <div style="position: relative; flex: 1; min-width: 200px;">
          <svg xmlns="http://www.w3.org/2000/svg" style="position: absolute; left: 10px; top: 50%; transform: translateY(-50%); width: 16px; height: 16px; color: #9ca3af;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z" />
          </svg>
          <input type="text" id="searchTitle" placeholder="Search position..."
            value="{{ request('search_title') }}"
            style="width: 100%; padding: 8px 12px 8px 34px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px; box-sizing: border-box;">
        </div>

        <select id="searchOrganization" onchange="applyFilters()"
          style="padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px; min-width: 180px;">
          <option value="">All Organizations</option>
          @foreach($allOrganizations as $org)
          <option value="{{ $org->id }}" {{ request('search_organization') == $org->id ? 'selected' : '' }}>
            {{ $org->description }}
          </option>
          @endforeach
        </select>

        <a href="{{ route('admin.positions.index') }}"
           style="padding: 8px 14px; background: #f3f4f6; color: #374151; border-radius: 6px; text-decoration: none; font-size: 14px; border: 1px solid #d1d5db; white-space: nowrap;"
           onmouseover="this.style.background='#e5e7eb'" onmouseout="this.style.background='#f3f4f6'">Reset</a>
      </div>

      @if($positions->isEmpty())
      <div style="text-align: center; padding: 40px 20px; color: #6b7280;">
        <p>No positions found.</p>
      </div>
      @else

        <table style="width: 100%; border-collapse: collapse;">
          <thead>
            <tr style="background: #f9fafb; border-top: 1px solid #e5e7eb; border-bottom: 1px solid #e5e7eb;">
              <th style="padding: 10px 14px; text-align: left; font-size: 11px; font-weight: 600; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em;">Organization</th>
              <th style="padding: 10px 14px; text-align: left; font-size: 11px; font-weight: 600; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em;">Position</th>
              <th style="padding: 10px 14px; text-align: left; font-size: 11px; font-weight: 600; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em;">Created</th>
              <th style="padding: 10px 14px; text-align: right; font-size: 11px; font-weight: 600; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em;">Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach($positions as $position)
            @php
              $org   = $position->organization;
              $code  = $org && $org->department ? $org->department->department_code : null;
              $badge = $code ? ($deptBadge[$code] ?? ['bg' => '#f3f4f6', 'color' => '#374151', 'border' => '1px solid #d1d5db']) : $univBadge;
            @endphp
            <tr style="border-bottom: 1px solid #f3f4f6;">
              <td style="padding: 11px 14px; font-size: 14px; color: #111827;">
                <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                  <span>{{ $org ? $org->description : '—' }}</span>
                  <span style="padding: 2px 8px; border-radius: 9999px; font-size: 11px; font-weight: 600;
                    background: {{ $badge['bg'] }}; color: {{ $badge['color'] }}; border: {{ $badge['border'] }};">
                    {{ $code ?? 'Uni-wide' }}
                  </span>
                </div>
              </td>
              <td style="padding: 11px 14px; font-size: 14px; color: #111827;">{{ $position->position_title }}</td>
              <td style="padding: 11px 14px; font-size: 13px; color: #6b7280; white-space: nowrap;">{{ $position->created_at->format('M d, Y') }}</td>
              <td style="padding: 11px 14px; text-align: right;">
                <div style="display: inline-flex; gap: 6px;">
                  <!-- Edit -->
                  <a href="{{ route('admin.positions.edit', $position->position_id) }}" title="Edit"
                     style="display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; background: #f3f4f6; border-radius: 6px; border: 1px solid #e5e7eb; color: #374151; text-decoration: none;"
                     onmouseover="this.style.background='#e5e7eb'" onmouseout="this.style.background='#f3f4f6'">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width: 15px; height: 15px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487a2.1 2.1 0 1 1 2.97 2.97L7.5 19.79l-4 1 1-4 12.362-12.303z" />
                    </svg>
                  </a>
                  <!-- Delete form (hidden) -->
                  <form id="pos-del-{{ $position->position_id }}"
                        action="{{ route('admin.positions.destroy', $position->position_id) }}"
                        method="POST" style="display:none;">
                    @csrf
                    @method('DELETE')
                  </form>
                  <!-- Delete button -->
                  <button type="button" title="Delete"
                    onclick="openPosDeleteModal('pos-del-{{ $position->position_id }}', '{{ addslashes($position->position_title) }}')"
                    style="display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; background: #fff0f0; border-radius: 6px; border: 1px solid #fecaca; color: #ef4444; cursor: pointer;"
                    onmouseover="this.style.background='#fee2e2'" onmouseout="this.style.background='#fff0f0'">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width: 15px; height: 15px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M3 6h18M8 6V4h8v2M19 6l-1 14H6L5 6" />
                    </svg>
                  </button>
                </div>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>

        <!-- Pagination -->
        <div style="margin-top: 20px;">
          {{ $positions->links('vendor.pagination.custom') }}
        </div>
      @endif
    </div>
  </div>

  <!-- Delete Modal -->
  <div id="posDeleteModal" style="display:none; position:fixed; inset:0; z-index:9999; align-items:center; justify-content:center; background:rgba(0,0,0,0.5);">
    <div style="background:#fff; border-radius:12px; padding:28px 32px; max-width:420px; width:90%; box-shadow:0 20px 60px rgba(0,0,0,0.2);">
      <div style="display:flex; align-items:center; gap:12px; margin-bottom:16px;">
        <div style="width:40px; height:40px; border-radius:8px; background:#fff0f0; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
          <svg xmlns="http://www.w3.org/2000/svg" style="width:20px; height:20px; color:#ef4444;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 6h18M8 6V4h8v2M19 6l-1 14H6L5 6" />
          </svg>
        </div>
        <h3 class="modal-plain-title" style="margin:0; font-size:16px; font-weight:600;">Delete Position</h3>
      </div>
      <p id="posDeleteMsg" style="margin:0 0 24px; font-size:14px; color:#6b7280;"></p>
      <div style="display:flex; gap:10px; justify-content:flex-end;">
        <button onclick="closePosDeleteModal()"
          style="padding:8px 18px; background:#f3f4f6; color:#374151; border:none; border-radius:6px; font-size:14px; cursor:pointer;"
          onmouseover="this.style.background='#e5e7eb'" onmouseout="this.style.background='#f3f4f6'">Cancel</button>
        <button id="posDeleteConfirmBtn"
          style="padding:8px 18px; background:#ef4444; color:#fff; border:none; border-radius:6px; font-size:14px; cursor:pointer;"
          onmouseover="this.style.background='#dc2626'" onmouseout="this.style.background='#ef4444'">Delete</button>
      </div>
    </div>
  </div>

  <script>
    // --- Live filters ---
    let _titleTimer;
    document.getElementById('searchTitle').addEventListener('input', function () {
      clearTimeout(_titleTimer);
      _titleTimer = setTimeout(applyFilters, 400);
    });

    function applyFilters() {
      const params = new URLSearchParams();
      const title = document.getElementById('searchTitle').value.trim();
      const org   = document.getElementById('searchOrganization').value;
      if (title) params.set('search_title', title);
      if (org)   params.set('search_organization', org);
      window.location.href = '{{ route("admin.positions.index") }}' + (params.toString() ? '?' + params.toString() : '');
    }

    // --- Delete modal ---
    let _posDeleteFormId = null;

    function openPosDeleteModal(formId, title) {
      _posDeleteFormId = formId;
      document.getElementById('posDeleteMsg').textContent =
        'Are you sure you want to delete "' + title + '"? This action cannot be undone.';
      const modal = document.getElementById('posDeleteModal');
      modal.style.display = 'flex';
    }

    function closePosDeleteModal() {
      document.getElementById('posDeleteModal').style.display = 'none';
      _posDeleteFormId = null;
    }

    document.getElementById('posDeleteConfirmBtn').addEventListener('click', function () {
      if (_posDeleteFormId) document.getElementById(_posDeleteFormId).submit();
    });

    document.getElementById('posDeleteModal').addEventListener('click', function (e) {
      if (e.target === this) closePosDeleteModal();
    });
  </script>
</x-dashboard-layout>

