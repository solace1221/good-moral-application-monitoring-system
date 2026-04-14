<x-dashboard-layout>
  <x-slot name="roleTitle">Admin</x-slot>

  <x-slot name="navigation">
    <x-admin-navigation />
  </x-slot>

  <!-- Header Section -->
  <div class="header-section">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
      <div>
        <h1 class="role-title">Department Management</h1>
        <p class="welcome-text">Manage departments and their associated courses</p>
        <div class="accent-line"></div>
      </div>
      <div style="display: flex; gap: 12px; align-items: center;">
        <button onclick="openCreateModal()" class="btn-primary" style="padding: 10px 16px; font-size: 14px; display: flex; align-items: center; gap: 8px;">
          <svg xmlns="http://www.w3.org/2000/svg" style="width: 18px; height: 18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
          </svg>
          Add Department
        </button>
      </div>
    </div>
  </div>

  <!-- Main Content -->
  <div class="content-section">
    <div class="card">
      @include('shared.alerts.flash')

      @if($departments->isEmpty())
        <div style="text-align: center; padding: 40px 20px; color: #666;">
          <p>No departments found. Add one to get started.</p>
        </div>
      @else
        <div style="overflow-x: auto;">
          <table style="width: 100%; border-collapse: collapse; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 1px 6px rgba(0,0,0,0.08);">
            <thead>
              <tr style="background: #f9fafb; border-bottom: 1px solid #e5e7eb;">
                <th style="padding: 11px 16px; text-align: left; font-weight: 600; color: #374151; font-size: 12px; text-transform: uppercase; letter-spacing: 0.04em;">Code</th>
                <th style="padding: 11px 16px; text-align: left; font-weight: 600; color: #374151; font-size: 12px; text-transform: uppercase; letter-spacing: 0.04em;">Department Name</th>
                <th style="padding: 11px 16px; text-align: left; font-weight: 600; color: #374151; font-size: 12px; text-transform: uppercase; letter-spacing: 0.04em;">Courses</th>
                <th style="padding: 11px 16px; text-align: center; font-weight: 600; color: #374151; font-size: 12px; text-transform: uppercase; letter-spacing: 0.04em;">Actions</th>
              </tr>
            </thead>
            <tbody>
              @foreach($departments as $department)
                <tr style="border-bottom: 1px solid #f3f4f6;" onmouseover="this.style.background='#fafafa'" onmouseout="this.style.background='white'">
                  <td style="padding: 14px 16px;">
                    <span style="display: inline-block; padding: 3px 10px; background: #f3f4f6; color: #374151; border-radius: 999px; font-size: 12px; font-weight: 600; letter-spacing: 0.03em;">{{ $department->department_code }}</span>
                  </td>
                  <td style="padding: 14px 16px; font-size: 14px; color: #374151;">{{ $department->department_name }}</td>
                  <td style="padding: 14px 16px;">
                    <span style="display: inline-block; padding: 3px 10px; background: #f3f4f6; color: #374151; border-radius: 999px; font-size: 12px; font-weight: 600;">{{ $department->courses_count }}</span>
                  </td>
                  <td style="padding: 14px 16px; text-align: center;">
                    <div style="display: inline-flex; gap: 6px; align-items: center;">
                      <button onclick="openEditModal({{ json_encode($department) }})" title="Edit"
                              style="display: inline-flex; align-items: center; justify-content: center; width: 30px; height: 30px; background: #f3f4f6; border: none; border-radius: 6px; cursor: pointer; color: #374151;"
                              onmouseover="this.style.background='#e5e7eb'" onmouseout="this.style.background='#f3f4f6'">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487a2.1 2.1 0 1 1 2.97 2.97L7.5 19.79l-4 1 1-4 12.362-12.303z"/>
                        </svg>
                      </button>
                      <form id="dept-delete-form-{{ $department->id }}" action="{{ route('admin.departments.destroy', $department) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="button" title="Delete"
                                onclick="openDeleteModal('dept-delete-form-{{ $department->id }}')"
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
      @endif
    </div>
  </div>

  <!-- Delete Confirmation Modal -->
  <div id="deleteModal" style="display:none; position:fixed; inset:0; z-index:60; background:rgba(0,0,0,0.5); align-items:center; justify-content:center;">
    <div style="background:#fff; border-radius:12px; padding:32px; max-width:440px; width:90%; box-shadow:0 10px 30px rgba(0,0,0,0.2);">
      <div style="display:flex; align-items:center; gap:12px; margin-bottom:16px;">
        <div style="display:inline-flex; align-items:center; justify-content:center; width:40px; height:40px; background:#fff0f0; border-radius:8px; flex-shrink:0;">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="#dc2626" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 6h18M8 6V4h8v2M19 6l-1 14H6L5 6"/>
          </svg>
        </div>
        <h3 class="modal-white-title" style="margin:0; font-size:18px; font-weight:600;">Delete Department</h3>
      </div>
      <p style="font-size:14px; color:#6b7280; margin:0 0 8px; line-height:1.6;">This department cannot be deleted if courses are assigned to it.</p>
      <p style="font-size:14px; color:#6b7280; margin:0 0 24px; line-height:1.6;">Please ensure that all courses are removed or reassigned before deleting.</p>
      <div style="display:flex; gap:10px; justify-content:flex-end;">
        <button onclick="closeDeleteModal()" style="padding:8px 20px; background:#f3f4f6; color:#374151; border:none; border-radius:6px; font-size:14px; cursor:pointer;" onmouseover="this.style.background='#e5e7eb'" onmouseout="this.style.background='#f3f4f6'">Cancel</button>
        <button id="deleteConfirmBtn" onclick="submitDelete()" style="padding:8px 20px; background:#dc2626; color:#fff; border:none; border-radius:6px; font-size:14px; font-weight:600; cursor:pointer;" onmouseover="this.style.background='#b91c1c'" onmouseout="this.style.background='#dc2626'">Delete Department</button>
      </div>
    </div>
  </div>

  <!-- Create Department Modal -->
  <div id="createModal" style="display:none; position:fixed; inset:0; z-index:50; background:rgba(0,0,0,0.5); align-items:center; justify-content:center;">
    <div style="background:#fff; border-radius:12px; padding:32px; max-width:500px; width:90%; max-height:90vh; overflow-y:auto;">
      <h2 style="font-size:20px; font-weight:600; margin-bottom:20px;">Add New Department</h2>
      <form action="{{ route('admin.departments.store') }}" method="POST">
        @csrf
        <div style="margin-bottom:16px;">
          <label style="display:block; font-weight:500; margin-bottom:4px;">Department Code</label>
          <input type="text" name="department_code" required maxlength="20" placeholder="e.g. SITE" style="width:100%; padding:8px 12px; border:1px solid #d1d5db; border-radius:6px;">
        </div>
        <div style="margin-bottom:20px;">
          <label style="display:block; font-weight:500; margin-bottom:4px;">Department Name</label>
          <input type="text" name="department_name" required maxlength="255" placeholder="e.g. School of Information Technology and Engineering" style="width:100%; padding:8px 12px; border:1px solid #d1d5db; border-radius:6px;">
        </div>
        <div style="display:flex; gap:12px; justify-content:flex-end;">
          <button type="button" onclick="closeCreateModal()" class="btn-secondary" style="padding:8px 20px;">Cancel</button>
          <button type="submit" class="btn-primary" style="padding:8px 20px;">Create Department</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Edit Department Modal -->
  <div id="editModal" style="display:none; position:fixed; inset:0; z-index:50; background:rgba(0,0,0,0.5); align-items:center; justify-content:center;">
    <div style="background:#fff; border-radius:12px; padding:32px; max-width:500px; width:90%; max-height:90vh; overflow-y:auto;">
      <h2 style="font-size:20px; font-weight:600; margin-bottom:20px;">Edit Department</h2>
      <form id="editForm" method="POST">
        @csrf
        @method('PUT')
        <div style="margin-bottom:16px;">
          <label style="display:block; font-weight:500; margin-bottom:4px;">Department Code</label>
          <input type="text" name="department_code" id="edit_department_code" required maxlength="20" style="width:100%; padding:8px 12px; border:1px solid #d1d5db; border-radius:6px;">
        </div>
        <div style="margin-bottom:20px;">
          <label style="display:block; font-weight:500; margin-bottom:4px;">Department Name</label>
          <input type="text" name="department_name" id="edit_department_name" required maxlength="255" style="width:100%; padding:8px 12px; border:1px solid #d1d5db; border-radius:6px;">
        </div>
        <div style="display:flex; gap:12px; justify-content:flex-end;">
          <button type="button" onclick="closeEditModal()" style="padding:8px 20px; background:#f3f4f6; color:#374151; border:none; border-radius:6px; font-size:14px; cursor:pointer;" onmouseover="this.style.background='#e5e7eb'" onmouseout="this.style.background='#f3f4f6'">Cancel</button>
          <button type="submit" class="btn-primary" style="padding:8px 20px;">Update Department</button>
        </div>
      </form>
    </div>
  </div>

  <script>
    let pendingDeleteFormId = null;

    function openDeleteModal(formId) {
      pendingDeleteFormId = formId;
      document.getElementById('deleteModal').style.display = 'flex';
    }
    function closeDeleteModal() {
      pendingDeleteFormId = null;
      document.getElementById('deleteModal').style.display = 'none';
    }
    function submitDelete() {
      if (pendingDeleteFormId) {
        document.getElementById(pendingDeleteFormId).submit();
      }
    }
    document.getElementById('deleteModal').addEventListener('click', function(e) {
      if (e.target === this) closeDeleteModal();
    });

    function openCreateModal() {
      document.getElementById('createModal').style.display = 'flex';
    }
    function closeCreateModal() {
      document.getElementById('createModal').style.display = 'none';
    }
    function openEditModal(dept) {
      document.getElementById('edit_department_code').value = dept.department_code;
      document.getElementById('edit_department_name').value = dept.department_name;
      document.getElementById('editForm').action = '/admin/departments/' + dept.id;
      document.getElementById('editModal').style.display = 'flex';
    }
    function closeEditModal() {
      document.getElementById('editModal').style.display = 'none';
    }
    document.getElementById('createModal').addEventListener('click', function(e) {
      if (e.target === this) closeCreateModal();
    });
    document.getElementById('editModal').addEventListener('click', function(e) {
      if (e.target === this) closeEditModal();
    });
  </script>
</x-dashboard-layout>
