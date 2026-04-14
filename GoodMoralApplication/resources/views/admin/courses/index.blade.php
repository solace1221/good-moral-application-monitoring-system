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

  <!-- Statistics Cards -->
  <div class="content-section" style="margin-bottom: 0;">
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 24px;">
      <div class="card" style="padding: 20px; text-align: center;">
        <p style="font-size: 14px; color: #666; margin-bottom: 4px;">Total Courses</p>
        <p style="font-size: 28px; font-weight: 700; color: #333;">{{ $totalCourses }}</p>
      </div>
      <div class="card" style="padding: 20px; text-align: center;">
        <p style="font-size: 14px; color: #666; margin-bottom: 4px;">Departments</p>
        <p style="font-size: 28px; font-weight: 700; color: #3b82f6;">{{ count($departments) }}</p>
      </div>
    </div>
  </div>

  <!-- Main Content -->
  <div class="content-section">
    <div class="card">
      @include('shared.alerts.flash')

      @if($courses->isEmpty())
        <div style="text-align: center; padding: 40px 20px; color: #666;">
          <p>No courses found. Click "Add Course" to create one.</p>
        </div>
      @else
        @foreach($courses as $deptCode => $deptCourses)
          <div style="margin-bottom: 32px;">
            <h3 style="font-size: 16px; font-weight: 600; color: #1f2937; padding: 10px 16px; background: #f3f4f6; border-radius: 8px; margin-bottom: 12px;">
              {{ $departments[$deptCode] ?? $deptCode }} ({{ $deptCode }})
              <span style="font-weight: 400; color: #6b7280; font-size: 14px;">— {{ $deptCourses->count() }} course(s)</span>
            </h3>
            <div class="overflow-x-auto">
              <table class="min-w-full bg-white border border-gray-300 rounded-lg">
                <thead class="bg-gray-50">
                  <tr>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Code</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Course Name</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($deptCourses as $course)
                    <tr class="border-b hover:bg-gray-50">
                      <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $course->course_code }}</td>
                      <td class="px-6 py-4 text-sm text-gray-600">{{ $course->course_name }}</td>
                      <td class="px-6 py-4 text-sm">
                        <div class="flex gap-2">
                          <button onclick="openEditModal({{ json_encode($course) }})" class="bg-blue-500 hover:bg-blue-700 text-white px-3 py-1 rounded-md text-xs">
                            Edit
                          </button>
                          <form action="{{ route('admin.courses.destroy', $course) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this course?');">
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
          </div>
        @endforeach
      @endif
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
          <button type="button" onclick="closeCreateModal()" class="btn-secondary" style="padding:8px 20px;">Cancel</button>
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
          <button type="button" onclick="closeEditModal()" class="btn-secondary" style="padding:8px 20px;">Cancel</button>
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
  </script>
</x-dashboard-layout>
