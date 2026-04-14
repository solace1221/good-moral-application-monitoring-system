<x-dashboard-layout>
  <x-slot name="roleTitle">Admin</x-slot>

  <x-slot name="navigation">
    <x-admin-navigation />
  </x-slot>

  <!-- Header Section -->
  <div class="header-section">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
      <div>
        <h1 class="role-title">Course Management</h1>
        <p class="welcome-text">Manage courses across all departments</p>
        <div class="accent-line"></div>
      </div>
      <div style="display: flex; gap: 12px; align-items: center;">
        <button onclick="openCreateModal()" class="btn-primary" style="padding: 10px 16px; font-size: 14px; display: flex; align-items: center; gap: 8px;">
          <svg xmlns="http://www.w3.org/2000/svg" style="width: 18px; height: 18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
          </svg>
          Add Course
        </button>
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
          'SOM'     => ['bg' => '#ffffff', 'color' => '#6b7280', 'border' => '1px solid #d1d5db'],
          'GRADSCH' => ['bg' => '#fefce8', 'color' => '#ca8a04', 'border' => '1px solid #fde68a'],
        ];
      @endphp

      <!-- Filter Bar -->
      <div style="margin-bottom: 16px; display: flex; flex-wrap: wrap; gap: 10px; align-items: center;">
        <div style="position: relative; flex: 2; min-width: 240px;">
          <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="#9ca3af" stroke-width="2"
               style="position: absolute; left: 10px; top: 50%; transform: translateY(-50%); pointer-events: none;">
            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/>
          </svg>
          <input type="text" id="filter-name" value="{{ request('search_name') }}" placeholder="Search course name..."
                 style="width: 100%; padding: 8px 12px 8px 32px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px; color: #374151; box-sizing: border-box;">
        </div>
        <div style="flex: 1; min-width: 140px; max-width: 180px;">
          <select id="filter-dept" style="width: 100%; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px; color: #374151;">
            <option value="">Department</option>
            @foreach($departments as $code => $name)
              <option value="{{ $code }}" {{ request('search_department') === $code ? 'selected' : '' }}>{{ $code }}</option>
            @endforeach
          </select>
        </div>
        <a href="{{ route('admin.courses.index') }}" style="padding: 8px 14px; font-size: 14px; background: #f3f4f6; color: #374151; border-radius: 6px; text-decoration: none; white-space: nowrap;"
           onmouseover="this.style.background='#e5e7eb'" onmouseout="this.style.background='#f3f4f6'">Reset</a>
      </div>

      @if($courses->isEmpty())
        <div style="text-align: center; padding: 40px 20px; color: #666;">
          <p>No courses found.</p>
        </div>
      @else
        <div style="overflow-x: auto;">
          <table style="width: 100%; border-collapse: collapse; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 1px 6px rgba(0,0,0,0.08);">
            <thead>
              <tr style="background: #f9fafb; border-bottom: 1px solid #e5e7eb;">
                <th style="padding: 11px 16px; text-align: left; font-weight: 600; color: #374151; font-size: 12px; text-transform: uppercase; letter-spacing: 0.04em;">Code</th>
                <th style="padding: 11px 16px; text-align: left; font-weight: 600; color: #374151; font-size: 12px; text-transform: uppercase; letter-spacing: 0.04em;">Course Name</th>
                <th style="padding: 11px 16px; text-align: left; font-weight: 600; color: #374151; font-size: 12px; text-transform: uppercase; letter-spacing: 0.04em;">Department</th>
                <th style="padding: 11px 16px; text-align: center; font-weight: 600; color: #374151; font-size: 12px; text-transform: uppercase; letter-spacing: 0.04em;">Actions</th>
              </tr>
            </thead>
            <tbody>
              @foreach($courses as $course)
                <tr style="border-bottom: 1px solid #f3f4f6;" onmouseover="this.style.background='#fafafa'" onmouseout="this.style.background='white'">
                  <td style="padding: 14px 16px;">
                    <span style="display: inline-block; padding: 3px 10px; background: #f3f4f6; color: #374151; border-radius: 999px; font-size: 12px; font-weight: 600; letter-spacing: 0.03em;">{{ $course->course_code }}</span>
                  </td>
                  <td style="padding: 14px 16px; font-size: 14px; color: #374151;">{{ $course->course_name }}</td>
                  <td style="padding: 14px 16px;">
                    @php $dc = $deptBadge[$course->department] ?? ['bg' => '#f3f4f6', 'color' => '#374151', 'border' => '1px solid #d1d5db']; @endphp
                    <span style="display: inline-block; padding: 3px 10px; background: {{ $dc['bg'] }}; color: {{ $dc['color'] }}; border: {{ $dc['border'] }}; border-radius: 999px; font-size: 12px; font-weight: 600;">{{ $course->department }}</span>
                  </td>
                  <td style="padding: 14px 16px; text-align: center;">
                    <div style="display: inline-flex; gap: 6px; align-items: center;">
                      <button onclick="openEditModal({{ json_encode($course) }})" title="Edit"
                              style="display: inline-flex; align-items: center; justify-content: center; width: 30px; height: 30px; background: #f3f4f6; border: none; border-radius: 6px; cursor: pointer; color: #374151;"
                              onmouseover="this.style.background='#e5e7eb'" onmouseout="this.style.background='#f3f4f6'">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487a2.1 2.1 0 1 1 2.97 2.97L7.5 19.79l-4 1 1-4 12.362-12.303z"/>
                        </svg>
                      </button>
                      <form id="course-delete-form-{{ $course->id }}" action="{{ route('admin.courses.destroy', $course) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="button" title="Delete"
                                onclick="openCourseDeleteModal('course-delete-form-{{ $course->id }}')"
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
        {{ $courses->links('vendor.pagination.custom') }}
      @endif
    </div>
  </div>

  <!-- Delete Confirmation Modal -->
  <div id="courseDeleteModal" style="display:none; position:fixed; inset:0; z-index:60; background:rgba(0,0,0,0.5); align-items:center; justify-content:center;">
    <div style="background:#fff; border-radius:12px; padding:32px; max-width:440px; width:90%; box-shadow:0 10px 30px rgba(0,0,0,0.2);">
      <div style="display:flex; align-items:center; gap:12px; margin-bottom:16px;">
        <div style="display:inline-flex; align-items:center; justify-content:center; width:40px; height:40px; background:#fff0f0; border-radius:8px; flex-shrink:0;">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="#dc2626" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 6h18M8 6V4h8v2M19 6l-1 14H6L5 6"/>
          </svg>
        </div>
        <h3 class="modal-white-title" style="margin:0; font-size:18px; font-weight:600;">Delete Course</h3>
      </div>
      <p style="font-size:14px; color:#6b7280; margin:0 0 8px; line-height:1.6;">Are you sure you want to delete this course?</p>
      <p style="font-size:14px; color:#6b7280; margin:0 0 24px; line-height:1.6;">This action cannot be undone.</p>
      <div style="display:flex; gap:10px; justify-content:flex-end;">
        <button onclick="closeCourseDeleteModal()" style="padding:8px 20px; background:#f3f4f6; color:#374151; border:none; border-radius:6px; font-size:14px; cursor:pointer;" onmouseover="this.style.background='#e5e7eb'" onmouseout="this.style.background='#f3f4f6'">Cancel</button>
        <button onclick="submitCourseDelete()" style="padding:8px 20px; background:#dc2626; color:#fff; border:none; border-radius:6px; font-size:14px; font-weight:600; cursor:pointer;" onmouseover="this.style.background='#b91c1c'" onmouseout="this.style.background='#dc2626'">Delete Course</button>
      </div>
    </div>
  </div>

  <!-- Create Course Modal -->
  <div id="createModal" style="display:none; position:fixed; inset:0; z-index:50; background:rgba(0,0,0,0.5); align-items:center; justify-content:center;">
    <div style="background:#fff; border-radius:12px; padding:32px; max-width:500px; width:90%; max-height:90vh; overflow-y:auto;">
      <h2 style="font-size:20px; font-weight:600; margin-bottom:20px;">Add New Course</h2>
      <form action="{{ route('admin.courses.store') }}" method="POST">
        @csrf
        <div style="margin-bottom:16px;">
          <label style="display:block; font-weight:500; margin-bottom:4px;">Course Code</label>
          <input type="text" name="course_code" required maxlength="20" placeholder="e.g. BSIT" style="width:100%; padding:8px 12px; border:1px solid #d1d5db; border-radius:6px;">
        </div>
        <div style="margin-bottom:16px;">
          <label style="display:block; font-weight:500; margin-bottom:4px;">Course Name</label>
          <input type="text" name="course_name" required maxlength="300" placeholder="e.g. Bachelor of Science in Information Technology" style="width:100%; padding:8px 12px; border:1px solid #d1d5db; border-radius:6px;">
        </div>
        <div style="margin-bottom:16px;">
          <label style="display:block; font-weight:500; margin-bottom:4px;">Department</label>
          <select name="department" required style="width:100%; padding:8px 12px; border:1px solid #d1d5db; border-radius:6px;">
            <option value="">Select Department</option>
            @foreach($departments as $code => $name)
              <option value="{{ $code }}">{{ $name }} ({{ $code }})</option>
            @endforeach
          </select>
        </div>
        <div style="display:flex; gap:12px; justify-content:flex-end;">
          <button type="button" onclick="closeCreateModal()" style="padding:8px 20px; background:#f3f4f6; color:#374151; border:none; border-radius:6px; font-size:14px; cursor:pointer;" onmouseover="this.style.background='#e5e7eb'" onmouseout="this.style.background='#f3f4f6'">Cancel</button>
          <button type="submit" class="btn-primary" style="padding:8px 20px;">Create Course</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Edit Course Modal -->
  <div id="editModal" style="display:none; position:fixed; inset:0; z-index:50; background:rgba(0,0,0,0.5); align-items:center; justify-content:center;">
    <div style="background:#fff; border-radius:12px; padding:32px; max-width:500px; width:90%; max-height:90vh; overflow-y:auto;">
      <h2 style="font-size:20px; font-weight:600; margin-bottom:20px;">Edit Course</h2>
      <form id="editForm" method="POST">
        @csrf
        @method('PUT')
        <div style="margin-bottom:16px;">
          <label style="display:block; font-weight:500; margin-bottom:4px;">Course Code</label>
          <input type="text" name="course_code" id="edit_course_code" required maxlength="20" style="width:100%; padding:8px 12px; border:1px solid #d1d5db; border-radius:6px;">
        </div>
        <div style="margin-bottom:16px;">
          <label style="display:block; font-weight:500; margin-bottom:4px;">Course Name</label>
          <input type="text" name="course_name" id="edit_course_name" required maxlength="300" style="width:100%; padding:8px 12px; border:1px solid #d1d5db; border-radius:6px;">
        </div>
        <div style="margin-bottom:16px;">
          <label style="display:block; font-weight:500; margin-bottom:4px;">Department</label>
          <select name="department" id="edit_department" required style="width:100%; padding:8px 12px; border:1px solid #d1d5db; border-radius:6px;">
            <option value="">Select Department</option>
            @foreach($departments as $code => $name)
              <option value="{{ $code }}">{{ $name }} ({{ $code }})</option>
            @endforeach
          </select>
        </div>
        <div style="display:flex; gap:12px; justify-content:flex-end;">
          <button type="button" onclick="closeEditModal()" style="padding:8px 20px; background:#f3f4f6; color:#374151; border:none; border-radius:6px; font-size:14px; cursor:pointer;" onmouseover="this.style.background='#e5e7eb'" onmouseout="this.style.background='#f3f4f6'">Cancel</button>
          <button type="submit" class="btn-primary" style="padding:8px 20px;">Update Course</button>
        </div>
      </form>
    </div>
  </div>

  <script>
    function openCreateModal() {
      document.getElementById('createModal').style.display = 'flex';
    }
    function closeCreateModal() {
      document.getElementById('createModal').style.display = 'none';
    }
    function openEditModal(course) {
      document.getElementById('edit_course_code').value = course.course_code;
      document.getElementById('edit_course_name').value = course.course_name;
      document.getElementById('edit_department').value = course.department;
      document.getElementById('editForm').action = '/admin/courses/' + course.id;
      document.getElementById('editModal').style.display = 'flex';
    }
    function closeEditModal() {
      document.getElementById('editModal').style.display = 'none';
    }
    // Close modals on backdrop click
    document.getElementById('createModal').addEventListener('click', function(e) {
      if (e.target === this) closeCreateModal();
    });
    document.getElementById('editModal').addEventListener('click', function(e) {
      if (e.target === this) closeEditModal();
    });
    document.getElementById('courseDeleteModal').addEventListener('click', function(e) {
      if (e.target === this) closeCourseDeleteModal();
    });

    let pendingCourseDeleteFormId = null;
    function openCourseDeleteModal(formId) {
      pendingCourseDeleteFormId = formId;
      document.getElementById('courseDeleteModal').style.display = 'flex';
    }
    function closeCourseDeleteModal() {
      pendingCourseDeleteFormId = null;
      document.getElementById('courseDeleteModal').style.display = 'none';
    }
    function submitCourseDelete() {
      if (pendingCourseDeleteFormId) document.getElementById(pendingCourseDeleteFormId).submit();
    }

    // Live filtering
    const baseUrl = '{{ route('admin.courses.index') }}';
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
