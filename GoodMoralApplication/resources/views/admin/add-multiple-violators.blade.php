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
          <h1 class="role-title">Add Multiple Violators</h1>
          <p class="welcome-text">Add violations to multiple students efficiently</p>
          <div class="accent-line"></div>
        </div>
        
        <div style="display: flex; gap: 12px; align-items: center;">
          <a href="{{ route('admin.AddViolator') }}"
             class="{{ request()->routeIs('admin.AddViolator') ? 'tab-active' : 'tab-inactive' }}">
            Single Violator
          </a>
          <a href="{{ route('admin.AddMultipleViolators') }}"
             class="{{ request()->routeIs('admin.AddMultipleViolators') ? 'tab-active' : 'tab-inactive' }}">
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

    @include('shared.alerts.flash')

    @if (session('warning'))
    <div style="background: #fff8e1; border: 1px solid #ffe082; color: #f57f17; padding: 16px; border-radius: 8px; margin-bottom: 24px;">
      {{ session('warning') }}
    </div>
    @endif

    <!-- Main Form -->
    <div class="card shadow-sm card-standard">
      <h3 style="margin: 0 0 20px; color: var(--primary-green); font-size: 1.25rem; font-weight: 600;">Add Multiple Violators</h3>
      
      <form method="POST" action="{{ route('admin.storeMultipleViolators') }}" id="multiple-violators-form">
        @csrf

        <!-- Step 1: Offense Type -->
        <div style="margin-bottom: 24px;">
          <label for="offense_type" style="display: block; margin-bottom: 8px; font-weight: 600; color: #333;">
            <span style="background: #10B981; color: white; border-radius: 50%; width: 24px; height: 24px; display: inline-flex; align-items: center; justify-content: center; margin-right: 8px; font-size: 12px; font-weight: bold;">1</span>
            Offense Type
          </label>
          <select id="offense_type" name="offense_type" required onchange="loadViolations()"
                  style="width: 100%; padding: 12px 16px; border: 2px solid #e1e5e9; border-radius: 8px; font-size: 14px; background: white;">
            <option value="" disabled selected>Select Offense Type</option>
            <option value="minor" {{ old('offense_type') == 'minor' ? 'selected' : '' }}>Minor Violation</option>
            <option value="major" {{ old('offense_type') == 'major' ? 'selected' : '' }}>Major Violation</option>
          </select>
        </div>

        <!-- Step 2: Violation Mode Selection -->
        <div id="violation_mode_section" style="display: none; margin-bottom: 24px;">
          <label style="display: block; margin-bottom: 16px; font-weight: 600; color: #333;">
            <span style="background: #10B981; color: white; border-radius: 50%; width: 24px; height: 24px; display: inline-flex; align-items: center; justify-content: center; margin-right: 8px; font-size: 12px; font-weight: bold;">2</span>
            Violation Mode
          </label>
          
          <div style="display: flex; gap: 20px; margin-bottom: 20px;">
            <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; padding: 12px 16px; border: 2px solid #e1e5e9; border-radius: 8px; background: white;">
              <input type="radio" name="violation_mode" value="single" checked onchange="toggleViolationMode()">
              <span>Single Violation (same violation to multiple students)</span>
            </label>
            
            <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; padding: 12px 16px; border: 2px solid #e1e5e9; border-radius: 8px; background: white;">
              <input type="radio" name="violation_mode" value="multiple" onchange="toggleViolationMode()">
              <span>Multiple Violations (multiple violations to students)</span>
            </label>
          </div>

          <!-- Single Violation Mode -->
          <div id="single_violation_section">
            <label for="violation" style="display: block; margin-bottom: 8px; font-weight: 500; color: #333;">Select Violation</label>
            <select id="violation" name="violation"
                    style="width: 100%; padding: 12px 16px; border: 2px solid #e1e5e9; border-radius: 8px; font-size: 14px; background: white;">
              <option value="" disabled selected>Select offense type first</option>
            </select>
          </div>

          <!-- Multiple Violations Mode -->
          <div id="multiple_violations_section" style="display: none;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
              <label style="font-weight: 500; color: #333;">Selected Violations</label>
              <button type="button" onclick="addViolation()" 
                      style="background: #10B981; color: white; border: none; padding: 8px 16px; border-radius: 6px; font-size: 14px; cursor: pointer;">
                + Add Violation
              </button>
            </div>
            
            <div id="selected_violations_list" style="border: 2px solid #e1e5e9; border-radius: 8px; padding: 16px; min-height: 100px; background: #f9fafb;">
              <p style="color: #666; font-style: italic; margin: 0; text-align: center;">No violations selected. Click "Add Violation" to start.</p>
            </div>
            
            <!-- Hidden field for multiple violations data -->
            <input type="hidden" id="multiple_violations_data" name="multiple_violations_data" value="">
          </div>
        </div>

        <!-- Step 3: Students Selection -->
        <div id="students_section" style="display: none; margin-bottom: 24px;">
          <label style="display: block; margin-bottom: 16px; font-weight: 600; color: #333;">
            <span style="background: #10B981; color: white; border-radius: 50%; width: 24px; height: 24px; display: inline-flex; align-items: center; justify-content: center; margin-right: 8px; font-size: 12px; font-weight: bold;">3</span>
            Students Selection
          </label>

          <div style="margin-bottom: 16px;">
            <label for="student_search" style="display: block; margin-bottom: 8px; font-weight: 500; color: #333;">Search Students</label>
            <div style="display: flex; gap: 8px;">
              <input type="text" id="student_search" placeholder="Type student name or ID..." 
                     style="flex: 1; padding: 12px 16px; border: 2px solid #e1e5e9; border-radius: 8px; font-size: 14px;">
              <button type="button" onclick="searchStudents()" 
                      style="padding: 12px 20px; background: #10B981; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">
                Search
              </button>
            </div>
          </div>

          <!-- Search Results -->
          <div id="search_results" style="display: none; margin-bottom: 16px; padding: 12px; border: 2px solid #e1e5e9; border-radius: 8px; background: white; max-height: 200px; overflow-y: auto;"></div>

          <!-- Selected Students -->
          <div>
            <label style="display: block; margin-bottom: 8px; font-weight: 500; color: #333;">Selected Students</label>
            <div id="selected_students" style="border: 2px solid #e1e5e9; border-radius: 8px; padding: 16px; min-height: 100px; background: #f9fafb;">
              <p style="color: #666; font-style: italic; margin: 0; text-align: center;">No students selected. Search and add students above.</p>
            </div>
          </div>

          <!-- Hidden inputs for student IDs -->
          <div id="student_ids_inputs"></div>
        </div>

        <!-- Additional Information -->
        <div id="additional_info_section" style="display: none; margin-bottom: 24px;">
          <label style="display: block; margin-bottom: 16px; font-weight: 600; color: #333;">
            <span style="background: #10B981; color: white; border-radius: 50%; width: 24px; height: 24px; display: inline-flex; align-items: center; justify-content: center; margin-right: 8px; font-size: 12px; font-weight: bold;">4</span>
            Additional Information
          </label>

          <div>
            <label style="display: block; margin-bottom: 8px; font-weight: 500; color: #333;">Reference Number</label>
            <div style="width: 100%; padding: 12px 16px; border: 2px solid #e1e5e9; border-radius: 8px; font-size: 14px; background: #f9fafb; color: #6b7280;">
              Will be auto-generated (e.g. VIO-{{ date('Y') }}-0001)
            </div>
          </div>
        </div>

        <!-- Submit Button -->
        <div style="display: flex; justify-content: flex-start; gap: 12px;">
          <button type="submit" id="submit_button" disabled
                  style="background: #10B981; color: white; border: none; padding: 12px 24px; border-radius: 8px; font-weight: 600; cursor: not-allowed; opacity: 0.5; transition: all 0.3s ease;">
            Add Multiple Violators
          </button>
        </div>
      </form>
    </div>
  </div>

  <script>
    // Global variables
    const violations = @json($violations);
    let selectedViolations = [];
    let selectedStudents = [];

    // Step 1: Load violations based on offense type
    function loadViolations() {
      const offenseType = document.getElementById('offense_type').value;
      const violationSelect = document.getElementById('violation');
      
      // Clear previous options
      violationSelect.innerHTML = '<option value="" disabled selected>Select a violation</option>';
      
      // Filter violations by offense type
      const filteredViolations = violations.filter(v => v.offense_type === offenseType);
      
      filteredViolations.forEach(violation => {
        const option = document.createElement('option');
        option.value = violation.description;
        option.textContent = violation.description;
        violationSelect.appendChild(option);
      });

      // Show violation mode section
      document.getElementById('violation_mode_section').style.display = 'block';
      updateFormState();
    }

    // Step 2: Toggle between single and multiple violation modes
    function toggleViolationMode() {
      const mode = document.querySelector('input[name="violation_mode"]:checked').value;
      const singleSection = document.getElementById('single_violation_section');
      const multipleSection = document.getElementById('multiple_violations_section');
      
      if (mode === 'single') {
        singleSection.style.display = 'block';
        multipleSection.style.display = 'none';
        document.getElementById('violation').required = true;
      } else {
        singleSection.style.display = 'none';
        multipleSection.style.display = 'block';
        document.getElementById('violation').required = false;
      }
      
      updateFormState();
    }

    // Add violation to multiple violations list
    function addViolation() {
      const offenseType = document.getElementById('offense_type').value;
      const filteredViolations = violations.filter(v => v.offense_type === offenseType);

      const available = filteredViolations.filter(
        v => !selectedViolations.some(sv => sv.description === v.description)
      );

      if (available.length === 0) {
        openViolationModal([], true);
        return;
      }

      openViolationModal(available, false);
    }

    function openViolationModal(available, allSelected) {
      const modal = document.getElementById('addViolationModal');
      const list  = document.getElementById('avViolationList');
      const msg   = document.getElementById('avAllSelectedMsg');

      list.innerHTML = '';
      document.getElementById('avSearchInput').value = '';

      if (allSelected) {
        list.style.display  = 'none';
        msg.style.display   = 'block';
      } else {
        list.style.display  = 'block';
        msg.style.display   = 'none';
        available.forEach(v => {
          const btn = document.createElement('button');
          btn.type = 'button';
          btn.className = 'av-item';
          btn.textContent = v.description;
          btn.dataset.description = v.description;
          btn.addEventListener('click', () => {
            selectedViolations.push(v);
            updateMultipleViolationsList();
            updateFormState();
            closeViolationModal();
          });
          list.appendChild(btn);
        });
      }

      modal.style.display = 'flex';
    }

    function closeViolationModal() {
      document.getElementById('addViolationModal').style.display = 'none';
    }

    document.addEventListener('DOMContentLoaded', () => {
      // Close modal on backdrop click
      document.getElementById('addViolationModal').addEventListener('click', function(e) {
        if (e.target === this) closeViolationModal();
      });

      // Live search inside modal
      document.getElementById('avSearchInput').addEventListener('input', function() {
        const term = this.value.toLowerCase();
        document.querySelectorAll('#avViolationList .av-item').forEach(btn => {
          btn.style.display = btn.dataset.description.toLowerCase().includes(term) ? '' : 'none';
        });
      });
    });

    // Update multiple violations display
    function updateMultipleViolationsList() {
      const container = document.getElementById('selected_violations_list');
      
      if (selectedViolations.length === 0) {
        container.innerHTML = '<p style="color: #666; font-style: italic; margin: 0; text-align: center;">No violations selected. Click "Add Violation" to start.</p>';
      } else {
        container.innerHTML = selectedViolations.map((violation, index) => `
          <div style="display: flex; justify-content: space-between; align-items: center; padding: 8px 12px; margin-bottom: 8px; background: white; border: 1px solid #e1e5e9; border-radius: 6px;">
            <span>${violation.description}</span>
            <button type="button" onclick="removeViolation(${index})" 
                    style="background: #dc3545; color: white; border: none; padding: 4px 8px; border-radius: 4px; cursor: pointer; font-size: 12px;">
              Remove
            </button>
          </div>
        `).join('');
      }
      
      // Update hidden input
      document.getElementById('multiple_violations_data').value = JSON.stringify(selectedViolations);
    }

    // Remove violation from multiple violations list
    function removeViolation(index) {
      selectedViolations.splice(index, 1);
      updateMultipleViolationsList();
      updateFormState();
    }

    // Step 3: Search students
    function searchStudents() {
      const searchTerm = document.getElementById('student_search').value.trim();
      if (searchTerm.length < 2) {
        alert('Please enter at least 2 characters to search.');
        return;
      }

      // Show loading
      const container = document.getElementById('search_results');
      container.innerHTML = '<p style="color: #666; margin: 0;">Searching...</p>';
      container.style.display = 'block';

      // AJAX call to search students
      fetch(`/api/students/search?q=${encodeURIComponent(searchTerm)}`, {
        method: 'GET',
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
          'Accept': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}'
        },
        credentials: 'same-origin'
      })
      .then(response => {
        if (!response.ok) {
          throw new Error(`Server returned ${response.status}: ${response.statusText}`);
        }
        return response.json();
      })
      .then(students => displaySearchResults(students))
      .catch(error => {
        console.error('Search error:', error);
        container.innerHTML = '<p style="color: #dc3545; margin: 0;">Error searching students: ' + error.message + '. Please try again.</p>';
      });
    }

    function displaySearchResults(students) {
      const container = document.getElementById('search_results');
      
      if (students.length === 0) {
        container.innerHTML = '<p style="color: #666; margin: 0;">No students found.</p>';
      } else {
        container.innerHTML = students.map(student => `
          <div style="display: flex; justify-content: space-between; align-items: center; padding: 8px 12px; border-bottom: 1px solid #e1e5e9;">
            <div>
              <div style="font-weight: 500;">${student.fullname}</div>
              <div style="font-size: 12px; color: #666;">${student.student_id} - ${student.department} (${student.course})</div>
            </div>
            <button type="button" onclick="addStudent('${student.student_id}', '${student.fullname}', '${student.department}', '${student.course}')" 
                    style="background: #10B981; color: white; border: none; padding: 4px 12px; border-radius: 4px; cursor: pointer; font-size: 12px;">
              Add
            </button>
          </div>
        `).join('');
      }
      
      container.style.display = 'block';
    }

    function addStudent(studentId, fullname, department, course) {
      if (selectedStudents.some(s => s.student_id === studentId)) {
        alert('Student already selected.');
        return;
      }

      selectedStudents.push({ student_id: studentId, fullname, department, course });
      updateSelectedStudentsList();
      updateFormState();
      
      // Clear search
      document.getElementById('student_search').value = '';
      document.getElementById('search_results').style.display = 'none';
    }

    function updateSelectedStudentsList() {
      const container = document.getElementById('selected_students');
      const inputsContainer = document.getElementById('student_ids_inputs');
      
      // Clear previous hidden inputs
      inputsContainer.innerHTML = '';
      
      if (selectedStudents.length === 0) {
        container.innerHTML = '<p style="color: #666; font-style: italic; margin: 0; text-align: center;">No students selected. Search and add students above.</p>';
      } else {
        container.innerHTML = selectedStudents.map((student, index) => `
          <div style="display: flex; justify-content: space-between; align-items: center; padding: 8px 12px; margin-bottom: 8px; background: white; border: 1px solid #e1e5e9; border-radius: 6px;">
            <div>
              <div style="font-weight: 500;">${student.fullname}</div>
              <div style="font-size: 12px; color: #666;">${student.student_id} - ${student.department} (${student.course})</div>
            </div>
            <button type="button" onclick="removeStudent(${index})" 
                    style="background: #dc3545; color: white; border: none; padding: 4px 8px; border-radius: 4px; cursor: pointer; font-size: 12px;">
              Remove
            </button>
          </div>
        `).join('');

        // Add hidden inputs for student IDs
        selectedStudents.forEach(student => {
          const input = document.createElement('input');
          input.type = 'hidden';
          input.name = 'student_ids[]';
          input.value = student.student_id;
          inputsContainer.appendChild(input);
        });
      }
    }

    function removeStudent(index) {
      selectedStudents.splice(index, 1);
      updateSelectedStudentsList();
      updateFormState();
    }

    // Update form state and enable/disable submit button
    function updateFormState() {
      const offenseType = document.getElementById('offense_type').value;
      const violationMode = document.querySelector('input[name="violation_mode"]:checked')?.value;
      const submitButton = document.getElementById('submit_button');

      let hasViolations = false;
      if (violationMode === 'single') {
        hasViolations = document.getElementById('violation').value !== '';
      } else if (violationMode === 'multiple') {
        hasViolations = selectedViolations.length > 0;
      }

      const hasStudents = selectedStudents.length > 0;
      const canSubmit = offenseType && hasViolations && hasStudents;

      // Show/hide sections based on progress
      if (offenseType && violationMode && hasViolations) {
        document.getElementById('students_section').style.display = 'block';
      }

      if (canSubmit) {
        document.getElementById('additional_info_section').style.display = 'block';
        submitButton.disabled = false;
        submitButton.style.cursor = 'pointer';
        submitButton.style.opacity = '1';
      } else {
        submitButton.disabled = true;
        submitButton.style.cursor = 'not-allowed';
        submitButton.style.opacity = '0.5';
      }
    }

    // Event listeners
    document.getElementById('violation').addEventListener('change', updateFormState);
    document.getElementById('student_search').addEventListener('keypress', function(e) {
      if (e.key === 'Enter') {
        e.preventDefault();
        searchStudents();
      }
    });

    // Form submission validation
    document.getElementById('multiple-violators-form').addEventListener('submit', function(e) {
      const violationMode = document.querySelector('input[name="violation_mode"]:checked').value;
      
      if (violationMode === 'single' && !document.getElementById('violation').value) {
        alert('Please select a violation.');
        e.preventDefault();
        return false;
      }
      
      if (violationMode === 'multiple' && selectedViolations.length === 0) {
        alert('Please add at least one violation.');
        e.preventDefault();
        return false;
      }
      
      if (selectedStudents.length === 0) {
        alert('Please select at least one student.');
        e.preventDefault();
        return false;
      }

      // Show loading state
      const submitBtn = document.getElementById('submit_button');
      submitBtn.innerHTML = 'Processing...';
      submitBtn.disabled = true;
      
      return true;
    });
  </script>

  <style>
    .container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 20px;
    }

    .header-section {
      margin-bottom: 24px;
    }

    .tab-active {
      text-decoration: none;
      padding: 12px 20px;
      background: #D4AF37;
      color: white !important;
      border: 2px solid #D4AF37;
      border-radius: 8px;
      font-weight: 600;
      display: flex;
      align-items: center;
      gap: 8px;
      transition: all 0.3s ease;
    }
    .tab-inactive {
      text-decoration: none;
      padding: 12px 20px;
      background: transparent;
      color: #15803d !important;
      border: 2px solid #15803d;
      border-radius: 8px;
      font-weight: 600;
      display: flex;
      align-items: center;
      gap: 8px;
      transition: all 0.3s ease;
    }
    .tab-inactive:hover {
      background: #f0fdf4;
    }
    .card-standard {
      background: white;
      padding: 24px;
      border-radius: 12px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1) !important;
    }

    /* Violation picker modal */
    #addViolationModal {
      display: none;
      position: fixed;
      inset: 0;
      background: rgba(0,0,0,.45);
      z-index: 9999;
      align-items: center;
      justify-content: center;
    }
    .av-modal-box {
      background: #fff;
      border-radius: 12px;
      width: min(480px, 92vw);
      box-shadow: 0 8px 32px rgba(0,0,0,.18);
      overflow: hidden;
    }
    .av-modal-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 18px 20px 14px;
      border-bottom: 1px solid #e5e7eb;
    }
    .av-modal-header h3 {
      margin: 0;
      font-size: 16px;
      font-weight: 600;
      color: #111827;
    }
    .av-modal-close {
      background: none;
      border: none;
      cursor: pointer;
      color: #6b7280;
      padding: 4px;
      border-radius: 4px;
      line-height: 1;
    }
    .av-modal-close:hover { color: #111827; background: #f3f4f6; }
    .av-modal-body {
      padding: 16px 20px;
    }
    #avSearchInput {
      width: 100%;
      padding: 9px 12px;
      border: 2px solid #e1e5e9;
      border-radius: 8px;
      font-size: 14px;
      margin-bottom: 12px;
      box-sizing: border-box;
    }
    #avSearchInput:focus { outline: none; border-color: #10B981; }
    #avViolationList {
      max-height: 280px;
      overflow-y: auto;
      display: flex;
      flex-direction: column;
      gap: 6px;
    }
    .av-item {
      width: 100%;
      text-align: left;
      padding: 10px 14px;
      border: 1px solid #e5e7eb;
      border-radius: 8px;
      background: #f9fafb;
      font-size: 14px;
      cursor: pointer;
      transition: background .15s, border-color .15s;
    }
    .av-item:hover { background: #d1fae5; border-color: #10B981; }
    #avAllSelectedMsg {
      text-align: center;
      padding: 24px 0;
      color: #6b7280;
      font-size: 14px;
    }
    .av-modal-footer {
      display: flex;
      justify-content: flex-end;
      padding: 12px 20px 16px;
      border-top: 1px solid #e5e7eb;
    }
    .av-cancel-btn {
      padding: 9px 18px;
      border: 1px solid #d1d5db;
      border-radius: 8px;
      background: white;
      font-size: 14px;
      font-weight: 500;
      cursor: pointer;
      color: #374151;
    }
    .av-cancel-btn:hover { background: #f9fafb; }
  </style>

  <!-- Violation Picker Modal -->
  <div id="addViolationModal" role="dialog" aria-modal="true" aria-labelledby="avModalTitle">
    <div class="av-modal-box">
      <div class="av-modal-header">
        <h3 id="avModalTitle">Select a Violation</h3>
        <button type="button" class="av-modal-close" onclick="closeViolationModal()" aria-label="Close">
          <svg style="width:18px;height:18px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
          </svg>
        </button>
      </div>
      <div class="av-modal-body">
        <input type="text" id="avSearchInput" placeholder="Search violations..." autocomplete="off">
        <div id="avViolationList"></div>
        <p id="avAllSelectedMsg" style="display:none;">All violations for this offense type have already been selected.</p>
      </div>
      <div class="av-modal-footer">
        <button type="button" class="av-cancel-btn" onclick="closeViolationModal()">Cancel</button>
      </div>
    </div>
  </div>
</x-dashboard-layout>