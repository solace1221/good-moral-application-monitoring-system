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
      @if(session('success'))
        <div class="alert alert-success" style="margin-bottom: 20px;">
          {{ session('success') }}
        </div>
      @endif

      @if(session('error'))
        <div class="alert alert-error" style="margin-bottom: 20px;">
          {{ session('error') }}
        </div>
      @endif

      @if($departments->isEmpty())
        <div style="text-align: center; padding: 40px 20px; color: #666;">
          <p>No departments found. Add one to get started.</p>
        </div>
      @else
        <div class="overflow-x-auto">
          <table class="min-w-full bg-white border border-gray-300 rounded-lg">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Code</th>
                <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Department Name</th>
                <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Description</th>
                <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Courses</th>
                <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Actions</th>
              </tr>
            </thead>
            <tbody>
              @foreach($departments as $department)
                <tr class="border-b hover:bg-gray-50">
                  <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $department->department_code }}</td>
                  <td class="px-6 py-4 text-sm text-gray-600">{{ $department->department_name }}</td>
                  <td class="px-6 py-4 text-sm text-gray-600">{{ $department->description ?? '—' }}</td>
                  <td class="px-6 py-4 text-sm text-gray-600">
                    <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded-full">{{ $department->courses_count }}</span>
                  </td>
                  <td class="px-6 py-4 text-sm">
                    <div class="flex gap-2">
                      <button onclick="openEditModal({{ json_encode($department) }})" class="bg-blue-500 hover:bg-blue-700 text-white px-3 py-1 rounded-md text-xs">
                        Edit
                      </button>
                      <form action="{{ route('admin.departments.destroy', $department) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure? This department must have no courses assigned.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-500 hover:bg-red-700 text-white px-3 py-1 rounded-md text-xs">
                          Delete
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
        <div style="margin-bottom:16px;">
          <label style="display:block; font-weight:500; margin-bottom:4px;">Department Name</label>
          <input type="text" name="department_name" required maxlength="255" placeholder="e.g. School of Information Technology and Engineering" style="width:100%; padding:8px 12px; border:1px solid #d1d5db; border-radius:6px;">
        </div>
        <div style="margin-bottom:20px;">
          <label style="display:block; font-weight:500; margin-bottom:4px;">Description (optional)</label>
          <textarea name="description" rows="2" style="width:100%; padding:8px 12px; border:1px solid #d1d5db; border-radius:6px;"></textarea>
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
    <div style="background:#fff; border-radius:12px; padding:32px; max-width:500px; width:90%; max-height:20vh; overflow-y:auto;">
      <h2 style="font-size:20px; font-weight:600; margin-bottom:20px;">Edit Department</h2>
      <form id="editForm" method="POST">
        @csrf
        @method('PUT')
        <div style="margin-bottom:16px;">
          <label style="display:block; font-weight:500; margin-bottom:4px;">Department Code</label>
          <input type="text" name="department_code" id="edit_department_code" required maxlength="20" style="width:100%; padding:8px 12px; border:1px solid #d1d5db; border-radius:6px;">
        </div>
        <div style="margin-bottom:16px;">
          <label style="display:block; font-weight:500; margin-bottom:4px;">Department Name</label>
          <input type="text" name="department_name" id="edit_department_name" required maxlength="255" style="width:100%; padding:8px 12px; border:1px solid #d1d5db; border-radius:6px;">
        </div>
        <div style="margin-bottom:20px;">
          <label style="display:block; font-weight:500; margin-bottom:4px;">Description (optional)</label>
          <textarea name="description" id="edit_description" rows="2" style="width:100%; padding:8px 12px; border:1px solid #d1d5db; border-radius:6px;"></textarea>
        </div>
        <div style="display:flex; gap:12px; justify-content:flex-end;">
          <button type="button" onclick="closeEditModal()" class="btn-secondary" style="padding:8px 20px;">Cancel</button>
          <button type="submit" class="btn-primary" style="padding:8px 20px;">Update Department</button>
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
    function openEditModal(dept) {
      document.getElementById('edit_department_code').value = dept.department_code;
      document.getElementById('edit_department_name').value = dept.department_name;
      document.getElementById('edit_description').value = dept.description || '';
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
