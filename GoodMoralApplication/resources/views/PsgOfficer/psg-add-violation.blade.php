<x-dashboard-layout>
  <x-slot name="roleTitle">PSG Officer</x-slot>

  <x-slot name="navigation">
    <a href="{{ route('PsgOfficer.dashboard') }}" class="nav-link {{ request()->routeIs('PsgOfficer.dashboard') ? 'active' : '' }}">
      <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6H8V5z"></path>
      </svg>
      Dashboard
    </a>

    <a href="{{ route('PsgOfficer.PsgAddViolation') }}" class="nav-link {{ request()->routeIs('PsgOfficer.PsgAddViolation') ? 'active' : '' }}">
      <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
      </svg>
      Add Minor Violation
    </a>

    <a href="{{ route('PsgOfficer.PsgViolation') }}" class="nav-link {{ request()->routeIs('PsgOfficer.PsgViolation') ? 'active' : '' }}">
      <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
      </svg>
      Minor Violations
    </a>

    <a href="{{ route('PsgOfficer.goodMoralForm') }}" class="nav-link {{ request()->routeIs('PsgOfficer.goodMoralForm') ? 'active' : '' }}">
      <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
      </svg>
      Apply for Good Moral
    </a>

    <a href="{{ route('PsgOfficer.personalViolations') }}" class="nav-link {{ request()->routeIs('PsgOfficer.personalViolations') ? 'active' : '' }}">
      <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
      </svg>
      My Violations
    </a>

    <a href="{{ route('PsgOfficer.applications') }}" class="nav-link {{ request()->routeIs('PsgOfficer.applications') ? 'active' : '' }}">
      <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
      </svg>
      My Applications
    </a>

    <form method="POST" action="{{ route('logout') }}" class="nav-logout-form">
      @csrf
      <button type="submit" class="nav-link nav-logout">
        <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75"></path>
        </svg>
        <span>Logout</span>
      </button>
    </form>
  </x-slot>

  <!-- Header Section -->
  <div class="header-section">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
      <div>
        <h1 class="role-title">Add Minor Violation</h1>
        <p class="welcome-text">Record a new minor violation for a student</p>
        <div class="accent-line"></div>
      </div>
    </div>
  </div>

  <!-- Status Messages -->
  @if(session('success'))
  <div style="margin-bottom: 24px; padding: 16px; background: #d4edda; color: #155724; border: 1px solid #c3e6cb; border-radius: 8px;">
    {{ session('success') }}
  </div>
  @endif

  <!-- Add Violation Form -->
  <div style="background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); overflow: hidden; margin-top: 24px;">
    <div style="padding: 24px; border-bottom: 1px solid #e9ecef;">
      <h2 style="margin: 0; color: var(--primary-green); font-size: 1.25rem; font-weight: 600;">Student Violation Form</h2>
    </div>
    <div style="padding: 24px;">
      <form method="POST" action="{{ route('psg.registerviolation') }}" onsubmit="return validateForm()">
        @csrf
        <!-- Student Search -->
        <div style="position: relative; margin-bottom: 24px;">
          <label for="student_search" style="display: block; margin-bottom: 8px; font-weight: 500; color: #495057;">Search Student</label>
          <input type="text" id="student_search" placeholder="Type student ID or name to search..."
                 style="width: 100%; padding: 12px 16px; border: 2px solid #e1e5e9; border-radius: 8px; font-size: 14px; transition: border-color 0.2s ease;"
                 autocomplete="off"
                 onfocus="this.style.borderColor='var(--primary-green)'"
                 onblur="this.style.borderColor='#e1e5e9'">

          <!-- Search Results Dropdown -->
          <div id="search_results" style="position: absolute; top: 100%; left: 0; right: 0; background: white; border: 1px solid #e1e5e9; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); z-index: 1000; display: none; max-height: 200px; overflow-y: auto;"></div>

          <!-- Selected Student Display -->
          <div id="selected_student" style="margin-top: 12px; padding: 12px; background: #e8f5e8; border: 1px solid #4caf50; border-radius: 8px; display: none;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
              <div>
                <strong id="selected_name"></strong><br>
                <small style="color: #666;">ID: <span id="selected_id"></span> | Department: <span id="selected_dept"></span></small>
              </div>
              <button type="button" onclick="clearSelection()" style="background: #f44336; color: white; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer;">Clear</button>
            </div>

            <!-- Violation Warning Alert -->
            <div id="violation-warning" style="display: none; margin-top: 12px; padding: 12px; border-radius: 6px; font-size: 13px; font-weight: 500;">
              <!-- Warning content will be populated by JavaScript -->
            </div>
          </div>
        </div>

        <!-- Hidden fields for form submission -->
        <input type="hidden" id="first_name" name="first_name" required>
        <input type="hidden" id="last_name" name="last_name" required>
        <input type="hidden" id="student_id" name="student_id" required>
        <input type="hidden" id="department" name="department" required>
        <input type="hidden" id="course" name="course" required>

        <!-- Violation Type -->
        <div x-data="{ showExtraInput: false }" style="margin-bottom: 24px;">
          <label for="violation" style="display: block; margin-bottom: 8px; font-weight: 500; color: #495057;">Violation Type</label>
          <select id="violation" name="violation" required
                  @change="showExtraInput = ($event.target.value === 'Others')"
                  style="width: 100%; padding: 12px 16px; border: 2px solid #e1e5e9; border-radius: 8px; font-size: 14px; transition: border-color 0.2s ease;"
                  onfocus="this.style.borderColor='var(--primary-green)'"
                  onblur="this.style.borderColor='#e1e5e9'">
            <option value="" disabled selected>Select Violation Type</option>
            @foreach ($violations as $violation)
            <option value="{{ $violation->offense_type }}|{{ $violation->description }}">
              {{ $violation->description }}
            </option>
            @endforeach
            <option value="Others">Others</option>
          </select>
          @error('violation')
          <div style="color: #e74c3c; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
          @enderror

          <!-- Hidden input for minor violation type -->
          <input type="hidden" id="OtherType" name="OtherType" value="minor">

          <!-- Custom Violation Input -->
          <div x-show="showExtraInput" style="margin-top: 16px;">
            <label for="others" style="display: block; margin-bottom: 8px; font-weight: 500; color: #495057;">Custom Violation</label>
            <input type="text" id="others" name="others"
                   style="width: 100%; padding: 12px 16px; border: 2px solid #e1e5e9; border-radius: 8px; font-size: 14px; transition: border-color 0.2s ease;"
                   placeholder="Enter custom violation description"
                   onfocus="this.style.borderColor='var(--primary-green)'"
                   onblur="this.style.borderColor='#e1e5e9'">
            @error('others')
            <div style="color: #e74c3c; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
            @enderror
          </div>
        </div>

        <!-- Submit Button -->
        <div style="display: flex; gap: 12px; justify-content: flex-end; padding-top: 24px; border-top: 1px solid #e9ecef;">
          <button type="submit" class="btn-primary">
            <svg style="width: 16px; height: 16px; margin-right: 8px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Add Violation
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- JavaScript for student search and violation checking -->
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const studentSearch = document.getElementById('student_search');
      const searchResults = document.getElementById('search_results');
      const selectedStudentDiv = document.getElementById('selected_student');

      let searchTimeout;
      let selectedStudent = null;

      // Student search functionality
      studentSearch.addEventListener('input', function() {
        const query = this.value.trim();

        clearTimeout(searchTimeout);

        if (query.length < 2) {
          searchResults.style.display = 'none';
          return;
        }

        searchTimeout = setTimeout(() => {
          console.log('Searching for:', query);
          fetch(`{{ route('api.students.search') }}?q=${encodeURIComponent(query)}`)
            .then(response => {
              console.log('Response status:', response.status);
              return response.json();
            })
            .then(students => {
              console.log('Students found:', students);
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
          searchResults.innerHTML = '<div style="padding: 12px; color: #666; text-align: center;">No students found</div>';
        } else {
          searchResults.innerHTML = students.map((student, index) => {
            // Escape quotes in student data to prevent JavaScript errors
            const escapedId = student.student_id.replace(/'/g, "\\'");
            const escapedName = student.fullname.replace(/'/g, "\\'");
            const escapedDept = student.department.replace(/'/g, "\\'");
            const escapedCourse = (student.course || '').replace(/'/g, "\\'");

            return `
              <div class="search-result-item" onclick="selectStudent('${escapedId}', '${escapedName}', '${escapedDept}', '${escapedCourse}')"
                   style="padding: 12px; cursor: pointer; border-bottom: 1px solid #eee; transition: background-color 0.2s;"
                   onmouseover="this.style.backgroundColor='#f5f5f5'"
                   onmouseout="this.style.backgroundColor='white'">
                <div style="font-weight: 600;">${student.fullname}</div>
                <div style="font-size: 12px; color: #666;">ID: ${student.student_id} | ${student.department} | ${student.course || 'N/A'}</div>
              </div>
            `;
          }).join('');
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
      console.log('selectStudent called with:', { studentId, fullname, department, course });

      // Parse the fullname - handle different formats
      let firstName = '';
      let lastName = '';

      if (fullname.includes(',')) {
        // Format: "LASTNAME,FIRSTNAME" or "LASTNAME, FIRSTNAME"
        const nameParts = fullname.split(',');
        lastName = nameParts[0].trim();
        const firstMiddle = nameParts[1] ? nameParts[1].trim() : '';
        const firstNameParts = firstMiddle.split(' ');
        firstName = firstNameParts[0] || '';
      } else {
        // Format: "FIRSTNAME LASTNAME" or "FIRSTNAME MIDDLE LASTNAME"
        const nameParts = fullname.trim().split(' ');
        firstName = nameParts[0] || '';
        lastName = nameParts[nameParts.length - 1] || '';
      }

      console.log('Parsed names:', { firstName, lastName });

      // Set hidden form fields
      document.getElementById('student_id').value = studentId;
      document.getElementById('first_name').value = firstName;
      document.getElementById('last_name').value = lastName;
      document.getElementById('department').value = department;
      document.getElementById('course').value = course || '';

      console.log('Hidden fields set:', {
        student_id: document.getElementById('student_id').value,
        first_name: document.getElementById('first_name').value,
        last_name: document.getElementById('last_name').value,
        department: document.getElementById('department').value,
        course: document.getElementById('course').value
      });

      // Update display
      document.getElementById('selected_name').textContent = fullname;
      document.getElementById('selected_id').textContent = studentId;
      document.getElementById('selected_dept').textContent = department;

      // Show selected student and hide search results
      document.getElementById('selected_student').style.display = 'block';
      document.getElementById('search_results').style.display = 'none';
      document.getElementById('student_search').value = '';

      selectedStudent = { studentId, fullname, department, course };

      // Check violations for this student
      checkStudentViolations(studentId);
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
      hideViolationWarning();

      selectedStudent = null;
    }

    // Function to check student violations
    function checkStudentViolations(studentId) {
      if (!studentId || studentId.trim() === '') {
        hideViolationWarning();
        return;
      }

      fetch(`/psg-officer/check-violations/${studentId}`, {
        method: 'GET',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          showViolationWarning(data.warning);
        } else {
          hideViolationWarning();
        }
      })
      .catch(error => {
        console.error('Error checking violations:', error);
        hideViolationWarning();
      });
    }

    function showViolationWarning(warningData) {
      const warningDiv = document.getElementById('violation-warning');
      const count = warningData.current_minor_count;
      const remaining = warningData.violations_until_escalation;
      const level = warningData.warning_level;

      let backgroundColor, textColor, icon, message;

      switch (level) {
        case 'critical':
          backgroundColor = '#f8d7da';
          textColor = '#721c24';
          icon = '🚨';
          message = `CRITICAL: This student has ${count} minor violations! Adding another will trigger escalation to major violation status.`;
          break;
        case 'high':
          backgroundColor = '#fff3cd';
          textColor = '#856404';
          icon = '⚠️';
          message = `WARNING: This student has ${count} minor violations. Only ${remaining} more before escalation to major violation.`;
          break;
        case 'medium':
          backgroundColor = '#d1ecf1';
          textColor = '#0c5460';
          icon = '⚡';
          message = `NOTICE: This student has ${count} minor violation. ${remaining} more violations will trigger escalation.`;
          break;
        default:
          hideViolationWarning();
          return;
      }

      warningDiv.innerHTML = `${icon} ${message}`;
      warningDiv.style.backgroundColor = backgroundColor;
      warningDiv.style.color = textColor;
      warningDiv.style.border = `1px solid ${textColor}`;
      warningDiv.style.display = 'block';
    }

    function hideViolationWarning() {
      const warningDiv = document.getElementById('violation-warning');
      warningDiv.style.display = 'none';
    }

    // Form validation function
    function validateForm() {
      const studentId = document.getElementById('student_id').value;
      const firstName = document.getElementById('first_name').value;
      const lastName = document.getElementById('last_name').value;
      const department = document.getElementById('department').value;
      const violation = document.getElementById('violation').value;

      console.log('Form validation check:', {
        studentId: studentId,
        firstName: firstName,
        lastName: lastName,
        department: department,
        violation: violation,
        studentIdEmpty: !studentId,
        firstNameEmpty: !firstName,
        lastNameEmpty: !lastName,
        departmentEmpty: !department
      });

      if (!studentId || !firstName || !lastName || !department) {
        console.log('Validation failed - missing student data');
        alert('Please select a student first before submitting the violation.');
        return false;
      }

      if (!violation) {
        console.log('Validation failed - no violation selected');
        alert('Please select a violation type.');
        return false;
      }

      console.log('Form validation passed, submitting...');
      return true;
    }
  </script>

</x-dashboard-layout>