<x-dashboard-layout>
  <x-slot name="roleTitle">Admin</x-slot>

  <x-slot name="navigation">
    <x-admin-navigation />
  </x-slot>

  <!-- Header Section -->
  <div class="header-section">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
      <div>
        <h1 class="role-title">Organization Management</h1>
        <p class="welcome-text">Manage organizations for departments</p>
        <div class="accent-line"></div>
      </div>
      <div style="display: flex; gap: 12px; align-items: center;">
        <a href="{{ route('admin.organizations.create') }}" class="btn-primary" style="padding: 10px 16px; font-size: 14px; text-decoration: none; display: flex; align-items: center; gap: 8px;">
          <svg xmlns="http://www.w3.org/2000/svg" style="width: 18px; height: 18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
          </svg>
          Add Organization
        </a>
      </div>
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
        ];
        $univBadge = ['bg' => '#f0fdfa', 'color' => '#0f766e', 'border' => '1px solid #99f6e4'];
      @endphp

      <!-- Filter Bar -->
      <div style="margin-bottom: 16px; display: flex; flex-wrap: wrap; gap: 10px; align-items: center;">
        <div style="position: relative; flex: 2; min-width: 240px;">
          <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="#9ca3af" stroke-width="2"
               style="position: absolute; left: 10px; top: 50%; transform: translateY(-50%); pointer-events: none;">
            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/>
          </svg>
          <input type="text" id="filter-name" value="{{ request('search_name') }}" placeholder="Search organization..."
                 style="width: 100%; padding: 8px 12px 8px 32px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px; color: #374151; box-sizing: border-box;">
        </div>
        <div style="flex: 1; min-width: 140px; max-width: 180px;">
          <select id="filter-dept" style="width: 100%; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px; color: #374151;">
            <option value="">Department</option>
            <option value="none" {{ request('search_department') === 'none' ? 'selected' : '' }}>University-wide</option>
            @foreach($departments as $dept)
              <option value="{{ $dept->department_code }}" {{ request('search_department') === $dept->department_code ? 'selected' : '' }}>{{ $dept->department_code }}</option>
            @endforeach
          </select>
        </div>
        <a href="{{ route('admin.organizations.index') }}" style="padding: 8px 14px; font-size: 14px; background: #f3f4f6; color: #374151; border-radius: 6px; text-decoration: none; white-space: nowrap;"
           onmouseover="this.style.background='#e5e7eb'" onmouseout="this.style.background='#f3f4f6'">Reset</a>
      </div>

      @if($organizations->isEmpty())
      <div style="text-align: center; padding: 40px 20px; color: #666;">
        <p>No organizations found.</p>
      </div>
      @else
        <div style="overflow-x: auto;">
          <table style="width: 100%; border-collapse: collapse; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 1px 6px rgba(0,0,0,0.08);">
            <thead>
              <tr style="background: #f9fafb; border-bottom: 1px solid #e5e7eb;">
                <th style="padding: 11px 16px; text-align: left; font-weight: 600; color: #374151; font-size: 12px; text-transform: uppercase; letter-spacing: 0.04em;">Organization Name</th>
                <th style="padding: 11px 16px; text-align: left; font-weight: 600; color: #374151; font-size: 12px; text-transform: uppercase; letter-spacing: 0.04em;">Department</th>
                <th style="padding: 11px 16px; text-align: left; font-weight: 600; color: #374151; font-size: 12px; text-transform: uppercase; letter-spacing: 0.04em;">Created</th>
                <th style="padding: 11px 16px; text-align: center; font-weight: 600; color: #374151; font-size: 12px; text-transform: uppercase; letter-spacing: 0.04em;">Actions</th>
              </tr>
            </thead>
            <tbody>
              @foreach($organizations as $organization)
              @php
                $code = $organization->department?->department_code;
                $badge = $code ? ($deptBadge[$code] ?? ['bg' => '#f3f4f6', 'color' => '#374151', 'border' => '1px solid #d1d5db']) : $univBadge;
                $label = $code ?? 'University-wide';
              @endphp
              <tr style="border-bottom: 1px solid #f3f4f6;" onmouseover="this.style.background='#fafafa'" onmouseout="this.style.background='white'">
                <td style="padding: 14px 16px; font-size: 14px; color: #374151; font-weight: 500;">{{ $organization->description }}</td>
                <td style="padding: 14px 16px;">
                  <span style="display: inline-block; padding: 3px 10px; background: {{ $badge['bg'] }}; color: {{ $badge['color'] }}; border: {{ $badge['border'] }}; border-radius: 999px; font-size: 12px; font-weight: 600;">{{ $label }}</span>
                </td>
                <td style="padding: 14px 16px; font-size: 13px; color: #6b7280;">{{ $organization->created_at->format('M d, Y') }}</td>
                <td style="padding: 14px 16px; text-align: center;">
                  <div style="display: inline-flex; gap: 6px; align-items: center;">
                    <a href="{{ route('admin.organizations.edit', $organization->id) }}" title="Edit"
                       style="display: inline-flex; align-items: center; justify-content: center; width: 30px; height: 30px; background: #f3f4f6; border-radius: 6px; color: #374151; text-decoration: none;"
                       onmouseover="this.style.background='#e5e7eb'" onmouseout="this.style.background='#f3f4f6'">
                      <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487a2.1 2.1 0 1 1 2.97 2.97L7.5 19.79l-4 1 1-4 12.362-12.303z"/>
                      </svg>
                    </a>
                    <form id="org-delete-form-{{ $organization->id }}" action="{{ route('admin.organizations.destroy', $organization->id) }}" method="POST" style="display:inline;">
                      @csrf
                      @method('DELETE')
                      <button type="button" title="Delete"
                              onclick="openOrgDeleteModal('org-delete-form-{{ $organization->id }}')"
                              style="display: inline-flex; align-items: center; justify-content: center; width: 30px; height: 30px; background: #fff0f0; border: none; border-radius: 6px; cursor: pointer; color: #dc2626;"
                              onmouseover="this.style.background='#fde8e8'" onmouseout="this.style.background='#fff0f0'">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M3 6h18M8 6V4h8v2M19 6l-1 14H6L5 6"/>
                        </svg>
                      </button>
                    </form>
                  </div>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
        {{ $organizations->links('vendor.pagination.custom') }}
      @endif
    </div>
  </div>

  <!-- Delete Confirmation Modal -->
  <div id="orgDeleteModal" style="display:none; position:fixed; inset:0; z-index:60; background:rgba(0,0,0,0.5); align-items:center; justify-content:center;">
    <div style="background:#fff; border-radius:12px; padding:32px; max-width:440px; width:90%; box-shadow:0 10px 30px rgba(0,0,0,0.2);">
      <div style="display:flex; align-items:center; gap:12px; margin-bottom:16px;">
        <div style="display:inline-flex; align-items:center; justify-content:center; width:40px; height:40px; background:#fff0f0; border-radius:8px; flex-shrink:0;">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="#dc2626" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 6h18M8 6V4h8v2M19 6l-1 14H6L5 6"/>
          </svg>
        </div>
        <h3 class="modal-white-title" style="margin:0; font-size:18px; font-weight:600;">Delete Organization</h3>
      </div>
      <p style="font-size:14px; color:#6b7280; margin:0 0 8px; line-height:1.6;">Are you sure you want to delete this organization?</p>
      <p style="font-size:14px; color:#6b7280; margin:0 0 24px; line-height:1.6;">This action cannot be undone.</p>
      <div style="display:flex; gap:10px; justify-content:flex-end;">
        <button onclick="closeOrgDeleteModal()" style="padding:8px 20px; background:#f3f4f6; color:#374151; border:none; border-radius:6px; font-size:14px; cursor:pointer;" onmouseover="this.style.background='#e5e7eb'" onmouseout="this.style.background='#f3f4f6'">Cancel</button>
        <button onclick="submitOrgDelete()" style="padding:8px 20px; background:#dc2626; color:#fff; border:none; border-radius:6px; font-size:14px; font-weight:600; cursor:pointer;" onmouseover="this.style.background='#b91c1c'" onmouseout="this.style.background='#dc2626'">Delete Organization</button>
      </div>
    </div>
  </div>

  <script>
    let pendingOrgDeleteFormId = null;
    function openOrgDeleteModal(formId) {
      pendingOrgDeleteFormId = formId;
      document.getElementById('orgDeleteModal').style.display = 'flex';
    }
    function closeOrgDeleteModal() {
      pendingOrgDeleteFormId = null;
      document.getElementById('orgDeleteModal').style.display = 'none';
    }
    function submitOrgDelete() {
      if (pendingOrgDeleteFormId) document.getElementById(pendingOrgDeleteFormId).submit();
    }
    document.getElementById('orgDeleteModal').addEventListener('click', function(e) {
      if (e.target === this) closeOrgDeleteModal();
    });

    // Live filtering
    const baseUrl = '{{ route('admin.organizations.index') }}';
    const filterName = document.getElementById('filter-name');
    const filterDept = document.getElementById('filter-dept');
    let debounceTimer;
    function applyFilters() {
      const params = new URLSearchParams();
      if (filterName.value.trim()) params.set('search_name', filterName.value.trim());
      if (filterDept.value) params.set('search_department', filterDept.value);
      window.location.href = baseUrl + (params.toString() ? '?' + params.toString() : '');
    }
    filterName.addEventListener('input', function() {
      clearTimeout(debounceTimer);
      debounceTimer = setTimeout(applyFilters, 400);
    });
    filterDept.addEventListener('change', applyFilters);
  </script>
</x-dashboard-layout>

