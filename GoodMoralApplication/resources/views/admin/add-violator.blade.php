<x-dashboard-layout>
  <x-slot name="roleTitle">Admin</x-slot>

  <x-slot name="navigation">
    <x-admin-navigation />
  </x-slot>

  <div class="container">
    <!-- Header Section -->
    <div class="header-section">
      <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
        <div>
          <h1 class="role-title">Add Violator</h1>
          <p class="welcome-text">Add students who have committed violations (both minor and major)</p>
          <div class="accent-line"></div>
        </div>
        <div style="display: flex; gap: 12px;">
          <a href="{{ route('admin.AddViolator') }}"
             class="btn-secondary"
             style="text-decoration: none; padding: 12px 20px; background: #6366f1; color: white; border-radius: 8px; font-weight: 600;">
            Single Violator
          </a>
          <a href="{{ route('admin.AddMultipleViolators') }}"
             class="btn-primary"
             style="text-decoration: none; padding: 12px 20px; background: #10B981; color: white; border-radius: 8px; font-weight: 600;">
            Multiple Violators
          </a>
        </div>
      </div>
    </div>

    @if ($errors->any())
    <div style="background: #fee; border: 1px solid #fcc; color: #c33; padding: 16px; border-radius: 8px; margin-bottom: 24px;">
      <strong>Please fix the following errors:</strong>
      <ul style="margin: 0; padding-left: 20px;">
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
    @endif

    @if (session('success'))
    <div style="background: #efe; border: 1px solid #cfc; color: #363; padding: 16px; border-radius: 8px; margin-bottom: 24px;">
      {{ session('success') }}
    </div>
    @endif

    <!-- Single Violator Form -->
    <div style="background: white; padding: 24px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
      <h3 style="margin: 0 0 20px; color: var(--primary-green); font-size: 1.25rem; font-weight: 600;">Add Single Violator</h3>

      <form method="POST" action="{{ route('admin.storeViolator') }}" onsubmit="return validateAndPrepareForm()" style="display: grid; gap: 20px;">
        @csrf

        <!-- Step 1: Offense Type -->
        <div style="margin-bottom: 24px;">
          <label for="offense_type" style="display: block; margin-bottom: 8px; font-weight: 600; color: #333;">
            <span style="background: #10B981; color: white; border-radius: 50%; width: 24px; height: 24px; display: inline-flex; align-items: center; justify-content: center; margin-right: 8px; font-size: 12px; font-weight: bold;">1</span>
            Offense Type
          </label>
          <select id="offense_type" name="offense_type" required
                  style="width: 100%; padding: 12px 16px; border: 2px solid #e1e5e9; border-radius: 8px; font-size: 14px; background: white;">
            <option value="" disabled selected>Select Offense Type</option>
            <option value="minor" {{ old('offense_type') == 'minor' ? 'selected' : '' }}>Minor Violation</option>
            <option value="major" {{ old('offense_type') == 'major' ? 'selected' : '' }}>Major Violation</option>
          </select>
          <x-input-error :messages="$errors->get('offense_type')" class="mt-2" />
        </div>

        <!-- Step 2: Violation Selection -->
        <div id="violation_selection_section" style="display: none; margin-bottom: 24px;">
          <label style="display: block; margin-bottom: 16px; font-weight: 600; color: #333;">
            <span style="background: #10B981; color: white; border-radius: 50%; width: 24px; height: 24px; display: inline-flex; align-items: center; justify-content: center; margin-right: 8px; font-size: 12px; font-weight: bold;">2</span>
            Violation Selection
          </label>

          <div>
            <label for="violation" style="display: block; margin-bottom: 8px; font-weight: 600; color: #333;">Violation Description</label>
            <select id="violation" name="violation" required
                    style="width: 100%; padding: 12px 16px; border: 2px solid #e1e5e9; border-radius: 8px; font-size: 14px; background: white;">
              <option value="" disabled selected>Select a violation type first</option>
            </select>
            <x-input-error :messages="$errors->get('violation')" class="mt-2" />
          </div>
        </div>

        <!-- Step 3: Student Selection -->
        <div id="student_selection_section" style="display: none; margin-bottom: 24px;">
          <label style="display: block; margin-bottom: 16px; font-weight: 600; color: #333;">
            <span style="background: #10B981; color: white; border-radius: 50%; width: 24px; height: 24px; display: inline-flex; align-items: center; justify-content: center; margin-right: 8px; font-size: 12px; font-weight: bold;">3</span>
            Student Selection
          </label>

          <!-- Student Search Section -->
          <div style="position: relative; margin-bottom: 24px;">
            <label for="student_search" style="display: block; margin-bottom: 8px; font-weight: 500; color: #495057;">Search Student</label>
            <input type="text" id="student_search" placeholder="Type student name or ID..."
                   style="width: 100%; padding: 12px 16px; border: 2px solid #e1e5e9; border-radius: 8px; font-size: 14px;">

            <!-- Search Results Dropdown -->
            <div id="search_results" style="display: none; position: absolute; top: 100%; left: 0; right: 0; background: white; border: 2px solid #e1e5e9; border-top: none; border-radius: 0 0 8px 8px; max-height: 200px; overflow-y: auto; z-index: 1000;">
            </div>
          </div>

          <!-- Selected Student Display -->
          <div id="selected_student" style="display: none; margin-bottom: 24px; padding: 16px; background: #f8f9fa; border: 2px solid #28a745; border-radius: 8px;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
              <div>
                <h4 style="margin: 0 0 4px 0; color: #28a745; font-size: 16px;">Selected Student</h4>
                <p style="margin: 0; color: #495057;">
                  <span id="selected_name"></span> (ID: <span id="selected_id"></span>) - <span id="selected_dept"></span>
                </p>
              </div>
              <button type="button" onclick="clearSelection()" style="background: #dc3545; color: white; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer; font-size: 12px;">Clear</button>
            </div>
          </div>
        </div>

        <!-- Step 4: Additional Information -->
        <div id="additional_info_section" style="display: none; margin-bottom: 24px;">
          <label style="display: block; margin-bottom: 16px; font-weight: 600; color: #333;">
            <span style="background: #10B981; color: white; border-radius: 50%; width: 24px; height: 24px; display: inline-flex; align-items: center; justify-content: center; margin-right: 8px; font-size: 12px; font-weight: bold;">4</span>
            Additional Information
          </label>

          <div>
            <label for="ref_num" style="display: block; margin-bottom: 8px; font-weight: 600; color: #333;">Reference Number <span style="color: #6b7280; font-weight: normal;">(Optional)</span></label>
            <input id="ref_num" type="text" name="ref_num"
                   value="{{ old('ref_num') }}"
                   placeholder="Enter reference number"
                   style="width: 100%; padding: 12px 16px; border: 2px solid #e1e5e9; border-radius: 8px; font-size: 14px;">
            <x-input-error :messages="$errors->get('ref_num')" class="mt-2" />
          </div>
        </div>

        <!-- Hidden fields for form submission -->
        <input type="hidden" id="first_name" name="first_name" required>
        <input type="hidden" id="last_name" name="last_name" required>
        <input type="hidden" id="student_id" name="student_id" required>
        <input type="hidden" id="department" name="department" required>
        <input type="hidden" id="course" name="course" required>

        <!-- Submit Button -->
        <div style="display: flex; justify-content: flex-start;">
          <button type="submit" class="btn-primary" style="cursor: pointer;">
            <svg style="width: 16px; height: 16px; margin-right: 8px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
            Add Violator
          </button>
        </div>
      </form>
    </div>

    <!-- Information Card -->
    <div style="background: #f8f9fa; padding: 20px; border-radius: 12px; border-left: 4px solid var(--primary-green); margin-top: 24px;">
      <h4 style="margin: 0 0 12px; color: var(--primary-green); font-size: 1.1rem; font-weight: 600;">
        <svg style="width: 20px; height: 20px; display: inline-block; margin-right: 8px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        Single Violator
      </h4>
      <p style="margin: 0; color: #495057; line-height: 1.5;">
        This form allows you to add a <strong>single violation</strong> to a <strong>single student</strong>.
        For adding the same violation to multiple students, use the "Multiple Violators" form.
      </p>
    </div>
  </div>

  <!-- JavaScript -->
  <script>
    const violations = @json($violations);

    document.addEventListener('DOMContentLoaded', function() {
      const offenseTypeSelect = document.getElementById('offense_type');
      const violationSelect = document.getElementById('violation');
      const studentSearch = document.getElementById('student_search');
      const searchResults = document.getElementById('search_results');

      // Offense type change handler
      offenseTypeSelect.addEventListener('change', function() {
        const selectedType = this.value;

        // Clear and populate violations
        violationSelect.innerHTML = '<option value="" disabled selected>Select violation</option>';

        const filteredViolations = violations.filter(v => v.offense_type === selectedType);
        filteredViolations.forEach(violation => {
          const option = document.createElement('option');
          option.value = violation.description;
          option.textContent = violation.description;
          violationSelect.appendChild(option);
        });

        violationSelect.disabled = false;

        if (selectedType) {
          document.getElementById('violation_selection_section').style.display = 'block';
        }
      });

      // Violation change handler
      violationSelect.addEventListener('change', function() {
        if (this.value) {
          document.getElementById('student_selection_section').style.display = 'block';
        }
      });

      // Student search functionality
      let searchTimeout;
      studentSearch.addEventListener('input', function() {
        const query = this.value.trim();
        clearTimeout(searchTimeout);

        if (query.length < 2) {
          searchResults.style.display = 'none';
          return;
        }

        searchTimeout = setTimeout(() => {
          fetch(`{{ route('api.students.search') }}?q=${encodeURIComponent(query)}`)
            .then(response => response.json())
            .then(students => {
              displaySearchResults(students);
            })
            .catch(error => {
              console.error('Search error:', error);
              searchResults.style.display = 'none';
            });
        }, 300);
      });

      function displaySearchResults(students) {
        if (students.length === 0) {
          searchResults.innerHTML = '<div style="padding: 12px; color: #666;">No students found</div>';
        } else {
          searchResults.innerHTML = students.map(student => `
            <div onclick="selectStudent('${student.student_id}', '${student.fullname}', '${student.department}', '${student.course || ''}')"
                 style="padding: 12px; border-bottom: 1px solid #eee; cursor: pointer;"
                 onmouseover="this.style.backgroundColor='#f5f5f5'"
                 onmouseout="this.style.backgroundColor='white'">
              <div style="font-weight: 600;">${student.fullname}</div>
              <div style="font-size: 12px; color: #666;">ID: ${student.student_id} | ${student.department}</div>
            </div>
          `).join('');
        }
        searchResults.style.display = 'block';
      }

      // Hide search results when clicking outside
      document.addEventListener('click', function(e) {
        if (!studentSearch.contains(e.target) && !searchResults.contains(e.target)) {
          searchResults.style.display = 'none';
        }
      });
    });

    // Global functions for student selection
    function selectStudent(studentId, fullname, department, course) {
      // Parse the fullname
      let firstName = '';
      let lastName = '';

      if (fullname.includes(',')) {
        const nameParts = fullname.split(',');
        lastName = nameParts[0].trim();
        const firstMiddle = nameParts[1] ? nameParts[1].trim() : '';
        const firstNameParts = firstMiddle.split(' ');
        firstName = firstNameParts[0] || '';
      } else {
        const nameParts = fullname.trim().split(' ');
        firstName = nameParts[0] || '';
        lastName = nameParts[nameParts.length - 1] || '';
      }

      // Set hidden form fields
      document.getElementById('student_id').value = studentId;
      document.getElementById('first_name').value = firstName;
      document.getElementById('last_name').value = lastName;
      document.getElementById('department').value = department;
      document.getElementById('course').value = course || '';

      // Update display
      document.getElementById('selected_name').textContent = fullname;
      document.getElementById('selected_id').textContent = studentId;
      document.getElementById('selected_dept').textContent = department;

      // Show selected student and hide search results
      document.getElementById('selected_student').style.display = 'block';
      document.getElementById('search_results').style.display = 'none';
      document.getElementById('student_search').value = '';

      // Show additional info section
      document.getElementById('additional_info_section').style.display = 'block';
    }

    function clearSelection() {
      // Clear hidden fields
      document.getElementById('student_id').value = '';
      document.getElementById('first_name').value = '';
      document.getElementById('last_name').value = '';
      document.getElementById('department').value = '';
      document.getElementById('course').value = '';

      // Hide selected student display
      document.getElementById('selected_student').style.display = 'none';
      document.getElementById('additional_info_section').style.display = 'none';
    }

    // Simple form validation
    function validateAndPrepareForm() {
      const offenseType = document.getElementById('offense_type').value;
      const violation = document.getElementById('violation').value;
      const studentId = document.getElementById('student_id').value;

      if (!offenseType) {
        alert('Please select an offense type.');
        return false;
      }

      if (!violation) {
        alert('Please select a violation.');
        return false;
      }

      if (!studentId) {
        alert('Please select a student.');
        return false;
      }

      return true;
    }
  </script>
</x-dashboard-layout>