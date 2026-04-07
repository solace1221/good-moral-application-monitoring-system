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
          <p class="welcome-text">Add the same violation to multiple students at once</p>
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

    <!-- Multiple Violators Form -->
    <div style="background: white; padding: 24px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
      <h3 style="margin: 0 0 20px; color: var(--primary-green); font-size: 1.25rem; font-weight: 600;">Add Multiple Violators</h3>
      
      <form method="POST" action="{{ route('admin.storeMultipleViolators') }}" onsubmit="return validateMultipleViolatorsForm()" style="display: grid; gap: 20px;" id="multiple-violators-form">
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

          <!-- Multiple Violations Toggle -->
          <div style="margin-bottom: 16px; padding: 12px; background: #f8f9fa; border-radius: 8px; border: 2px solid #e1e5e9;">
            <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
              <input type="checkbox" id="enable_multiple_violations" style="width: 18px; height: 18px;">
              <span style="font-weight: 600; color: #333;">Add Multiple Violations</span>
            </label>
            <p style="margin: 4px 0 0 26px; color: #666; font-size: 13px;">
              Enable this to add multiple violations for the same incident.
            </p>
          </div>

          <!-- Single Violation Mode -->
          <div id="single_violation_mode">
            <label for="violation" style="display: block; margin-bottom: 8px; font-weight: 600; color: #333;">Violation Description</label>
            <select id="violation" name="violation" required
                    style="width: 100%; padding: 12px 16px; border: 2px solid #e1e5e9; border-radius: 8px; font-size: 14px; background: white;">
              <option value="" disabled selected>Select a violation type first</option>
            </select>
            <x-input-error :messages="$errors->get('violation')" class="mt-2" />
          </div>

          <!-- Multiple Violations Mode -->
          <div id="multiple_violations_mode" style="display: none;">
            <div style="border: 2px solid #e1e5e9; border-radius: 8px; padding: 12px; background: white; min-height: 120px;">
              <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                <span style="font-weight: 600; color: #333;">Selected Violations:</span>
                <button type="button" id="add_violation_btn" onclick="showViolationSelector()"
                        style="background: #10B981; color: white; border: none; padding: 6px 12px; border-radius: 4px; font-size: 12px; cursor: pointer;">
                  + Add Violation
                </button>
              </div>
              <div id="selected_violations_list" style="display: flex; flex-direction: column; gap: 8px;">
                <p style="color: #666; font-style: italic; margin: 0;">No violations selected yet.</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Step 3: Students Selection -->
        <div id="students_selection_section" style="display: none; margin-bottom: 24px;">
          <label style="display: block; margin-bottom: 16px; font-weight: 600; color: #333;">
            <span style="background: #10B981; color: white; border-radius: 50%; width: 24px; height: 24px; display: inline-flex; align-items: center; justify-content: center; margin-right: 8px; font-size: 12px; font-weight: bold;">3</span>
            Students Selection
          </label>

          <!-- Student Search -->
          <div style="position: relative; margin-bottom: 16px;">
            <label for="student_search" style="display: block; margin-bottom: 8px; font-weight: 500; color: #495057;">Search and Add Students</label>
            <div style="display: flex; gap: 8px;">
              <input type="text" id="student_search" placeholder="Type student name or ID..."
                     style="flex: 1; padding: 12px 16px; border: 2px solid #e1e5e9; border-radius: 8px; font-size: 14px;">
              <button type="button" id="add_student_btn" onclick="addSelectedStudent()" disabled
                      style="padding: 12px 20px; background: #10B981; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">
                Add Student
              </button>
            </div>

            <!-- Search Results -->
            <div id="search_results" style="display: none; position: absolute; top: 100%; left: 0; right: 0; background: white; border: 2px solid #e1e5e9; border-top: none; border-radius: 0 0 8px 8px; max-height: 200px; overflow-y: auto; z-index: 1000;">
            </div>
          </div>

          <!-- Selected Students Display -->
          <div>
            <h4 style="margin: 0 0 12px; color: #333; font-size: 14px; font-weight: 600;">Selected Students:</h4>
            <div id="selected_students_container" style="display: flex; flex-direction: column; gap: 8px; min-height: 60px; padding: 12px; border: 2px solid #e1e5e9; border-radius: 8px; background: #f8f9fa;">
              <p style="color: #666; font-style: italic; margin: 0;">No students selected yet. Search and add students above.</p>
            </div>
          </div>

          <!-- Hidden inputs for student IDs -->
          <div id="student_ids_hidden_inputs"></div>

          <!-- Hidden input for multiple violations data -->
          <input type="hidden" id="multiple_violations_data" name="multiple_violations_data" value="">
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

        <!-- Submit Button -->
        <div style="display: flex; justify-content: flex-start; gap: 12px;">
          <button type="submit" id="submit_button" class="btn-primary" disabled
                  style="cursor: not-allowed; opacity: 0.6; transition: all 0.3s ease;">
            <svg style="width: 16px; height: 16px; margin-right: 8px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
            Add Multiple Violators
          </button>


        </div>

        <!-- Form Progress Indicator -->
        <div id="form_progress" style="margin-top: 16px; padding: 12px; background: #f8f9fa; border-radius: 8px; border-left: 4px solid #ffc107;">
          <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px;">
            <svg style="width: 16px; height: 16px; color: #ffc107;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span style="font-weight: 600; color: #856404;">Complete all steps to enable submission</span>
          </div>
          <div id="progress_checklist" style="font-size: 14px; color: #856404;">
            <div id="step1_status">❌ Step 1: Select offense type</div>
            <div id="step2_status">❌ Step 2: Select violation(s)</div>
            <div id="step3_status">❌ Step 3: Select student(s)</div>
          </div>
        </div>
      </form>
    </div>

    <!-- Information Card -->
    <div style="background: #f8f9fa; padding: 20px; border-radius: 12px; border-left: 4px solid var(--primary-green); margin-top: 24px;">
      <h4 style="margin: 0 0 12px; color: var(--primary-green); font-size: 1.1rem; font-weight: 600;">
        <svg style="width: 20px; height: 20px; display: inline-block; margin-right: 8px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        Multiple Violators
      </h4>
      <p style="margin: 0; color: #495057; line-height: 1.5;">
        This form allows you to add the <strong>same violation</strong> to <strong>multiple students</strong> at once. 
        This is useful for group violations or incidents involving multiple students. 
        Each student will receive the same violation and notification.
      </p>
    </div>
  </div>

  <!-- Violation Selector Modal -->
  <div id="violation_selector_modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 10000;">
    <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; padding: 24px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.3); max-width: 500px; width: 90%;">
      <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h3 style="margin: 0; color: var(--primary-green);">Select Violation</h3>
        <button type="button" onclick="closeViolationSelector()" style="background: none; border: none; font-size: 24px; cursor: pointer; color: #666;">&times;</button>
      </div>

      <div style="margin-bottom: 20px;">
        <label style="display: block; margin-bottom: 8px; font-weight: 600;">Available Violations:</label>
        <select id="modal_violation_select" style="width: 100%; padding: 12px; border: 2px solid #e1e5e9; border-radius: 8px;">
          <option value="">Select a violation...</option>
        </select>
      </div>

      <div style="display: flex; gap: 12px; justify-content: flex-end;">
        <button type="button" onclick="closeViolationSelector()" style="padding: 10px 20px; border: 2px solid #ccc; background: white; border-radius: 6px; cursor: pointer;">Cancel</button>
        <button type="button" onclick="addSelectedViolation()" style="padding: 10px 20px; background: var(--primary-green); color: white; border: none; border-radius: 6px; cursor: pointer;">Add Violation</button>
      </div>
    </div>
  </div>

  <!-- JavaScript -->
  <script>
    const violations = @json($violations);
    let selectedStudents = [];
    let selectedViolations = [];
    let currentSearchResults = [];
    let selectedStudentIndex = -1;
    let isSubmitting = false; // Prevent duplicate submissions

    document.addEventListener('DOMContentLoaded', function() {
      const offenseTypeSelect = document.getElementById('offense_type');
      const violationSelect = document.getElementById('violation');
      const studentSearch = document.getElementById('student_search');
      const searchResults = document.getElementById('search_results');
      const addStudentBtn = document.getElementById('add_student_btn');

      // Initialize form progress
      updateFormProgress();

      // Add click event listener to submit button for debugging
      const submitButton = document.getElementById('submit_button');
      if (submitButton) {
        submitButton.addEventListener('click', function(e) {
          console.log('🔘 Submit button clicked!');
          console.log('🔘 Button disabled state:', this.disabled);
          console.log('🔘 Form data check:', {
            offenseType: document.getElementById('offense_type').value,
            studentsSelected: selectedStudents.length,
            violationsSelected: selectedViolations.length
          });
        });
      }

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

        // Update form progress
        updateFormProgress();
      });

      // Violation change handler
      violationSelect.addEventListener('change', function() {
        if (this.value) {
          document.getElementById('students_selection_section').style.display = 'block';
        }
        // Update form progress
        updateFormProgress();
      });

      // Show students section when multiple violations toggle is changed
      window.checkAndShowStudentsSection = function() {
        const isMultipleViolations = document.getElementById('enable_multiple_violations').checked;
        const singleViolation = document.getElementById('violation').value;

        if (isMultipleViolations && selectedViolations.length > 0) {
          document.getElementById('students_selection_section').style.display = 'block';
        } else if (!isMultipleViolations && singleViolation) {
          document.getElementById('students_selection_section').style.display = 'block';
        }
        // Update form progress
        updateFormProgress();
      }

      // Multiple violations toggle handler
      const multipleViolationsCheckbox = document.getElementById('enable_multiple_violations');
      multipleViolationsCheckbox.addEventListener('change', function() {
        toggleMultipleViolations();
        // Update form progress
        updateFormProgress();
      });

      // Student search functionality
      let searchTimeout;
      studentSearch.addEventListener('input', function() {
        const query = this.value.trim();
        clearTimeout(searchTimeout);

        if (query.length < 2) {
          searchResults.style.display = 'none';
          addStudentBtn.disabled = true;
          return;
        }

        searchTimeout = setTimeout(() => {
          fetch(`{{ route('api.students.search') }}?q=${encodeURIComponent(query)}`)
            .then(response => response.json())
            .then(students => {
              currentSearchResults = students;
              displaySearchResults(students);
            })
            .catch(error => {
              console.error('Search error:', error);
              searchResults.style.display = 'none';
            });
        }, 300);
      });

      window.displaySearchResults = function(students) {
        const searchResults = document.getElementById('search_results');
        if (students.length === 0) {
          searchResults.innerHTML = '<div style="padding: 12px; color: #666;">No students found</div>';
        } else {
          searchResults.innerHTML = students.map((student, index) => {
            const isSelected = selectedStudents.some(s => s.student_id === student.student_id);
            const style = isSelected ? 'opacity: 0.5; cursor: not-allowed;' : 'cursor: pointer;';
            const text = isSelected ? ' (Already selected)' : '';

            return `
              <div onclick="${!isSelected ? `selectStudentFromSearch(${index})` : ''}"
                   style="padding: 12px; border-bottom: 1px solid #eee; ${style}"
                   onmouseover="this.style.backgroundColor='#f5f5f5'"
                   onmouseout="this.style.backgroundColor='white'">
                <div style="font-weight: 600;">${student.fullname}${text}</div>
                <div style="font-size: 12px; color: #666;">ID: ${student.student_id} | ${student.department}</div>
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

    window.selectStudentFromSearch = function(index) {
      selectedStudentIndex = index;
      const student = currentSearchResults[index];
      document.getElementById('student_search').value = student.fullname;
      document.getElementById('search_results').style.display = 'none';
      document.getElementById('add_student_btn').disabled = false;
    }

    window.addSelectedStudent = function() {
      if (selectedStudentIndex >= 0 && currentSearchResults[selectedStudentIndex]) {
        const student = currentSearchResults[selectedStudentIndex];

        // Check if already selected
        if (selectedStudents.some(s => s.student_id === student.student_id)) {
          alert('This student is already selected.');
          return;
        }

        selectedStudents.push(student);
        updateSelectedStudentsDisplay();

        // Clear search
        document.getElementById('student_search').value = '';
        document.getElementById('add_student_btn').disabled = true;
        selectedStudentIndex = -1;

        // Show additional info section
        document.getElementById('additional_info_section').style.display = 'block';

        // Update form progress
        updateFormProgress();

        // Show success message
        showSuccessMessage(`✅ Student "${student.fullname}" added successfully!`);
      }
    }

    window.removeStudent = function(index) {
      selectedStudents.splice(index, 1);
      updateSelectedStudentsDisplay();
      // Update form progress
      updateFormProgress();
    }

    window.updateSelectedStudentsDisplay = function() {
      const container = document.getElementById('selected_students_container');
      const hiddenInputsContainer = document.getElementById('student_ids_hidden_inputs');

      if (selectedStudents.length === 0) {
        container.innerHTML = '<p style="color: #666; font-style: italic; margin: 0;">No students selected yet. Search and add students above.</p>';
        hiddenInputsContainer.innerHTML = '';
      } else {
        container.innerHTML = selectedStudents.map((student, index) => `
          <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px; background: white; border: 1px solid #dee2e6; border-radius: 6px;">
            <div>
              <div style="font-weight: 600;">${student.fullname}</div>
              <div style="font-size: 12px; color: #666;">ID: ${student.student_id} | ${student.department}</div>
            </div>
            <button type="button" onclick="removeStudent(${index})"
                    style="background: #dc3545; color: white; border: none; padding: 4px 8px; border-radius: 4px; cursor: pointer; font-size: 11px;">
              Remove
            </button>
          </div>
        `).join('');

        // Create hidden inputs for each student ID (Laravel expects array format)
        hiddenInputsContainer.innerHTML = selectedStudents.map((student, index) =>
          `<input type="hidden" name="student_ids[]" value="${student.student_id}">`
        ).join('');
      }
    }

    // Multiple violations functions
    window.toggleMultipleViolations = function() {
      const isMultiple = document.getElementById('enable_multiple_violations').checked;
      const singleMode = document.getElementById('single_violation_mode');
      const multipleMode = document.getElementById('multiple_violations_mode');

      if (isMultiple) {
        singleMode.style.display = 'none';
        multipleMode.style.display = 'block';
        // Clear single violation selection
        document.getElementById('violation').value = '';
        // Hide students section until violations are selected
        if (selectedViolations.length === 0) {
          document.getElementById('students_selection_section').style.display = 'none';
        }
      } else {
        singleMode.style.display = 'block';
        multipleMode.style.display = 'none';
        // Clear multiple violations selection
        selectedViolations = [];
        updateMultipleViolationsDisplay();
        // Hide students section until single violation is selected
        document.getElementById('students_selection_section').style.display = 'none';
      }

      // Check if we should show students section
      checkAndShowStudentsSection();
    }

    window.showViolationSelector = function() {
      const modal = document.getElementById('violation_selector_modal');
      const modalSelect = document.getElementById('modal_violation_select');
      const offenseType = document.getElementById('offense_type').value;

      if (!offenseType) {
        alert('Please select an offense type first.');
        return;
      }

      // Clear and populate modal select with available violations
      modalSelect.innerHTML = '<option value="">Select a violation...</option>';

      const filteredViolations = violations.filter(v => v.offense_type === offenseType);
      filteredViolations.forEach(violation => {
        // Check if violation is already selected
        const isSelected = selectedViolations.some(v => v.description === violation.description);
        if (!isSelected) {
          const option = document.createElement('option');
          option.value = violation.description;
          option.textContent = violation.description;
          option.setAttribute('data-article', violation.article || '');
          modalSelect.appendChild(option);
        }
      });

      modal.style.display = 'block';
    }

    window.closeViolationSelector = function() {
      document.getElementById('violation_selector_modal').style.display = 'none';
      document.getElementById('modal_violation_select').value = '';
    }

    window.addSelectedViolation = function() {
      const modalSelect = document.getElementById('modal_violation_select');
      const selectedValue = modalSelect.value;

      if (!selectedValue) {
        alert('Please select a violation.');
        return;
      }

      // Find the violation object
      const violationObj = violations.find(v => v.description === selectedValue);
      if (violationObj) {
        selectedViolations.push(violationObj);
        updateMultipleViolationsDisplay();
        closeViolationSelector();

        // Show students section if violations are selected
        if (selectedViolations.length > 0) {
          document.getElementById('students_selection_section').style.display = 'block';
        }

        // Update form progress
        updateFormProgress();

        // Show success message
        showSuccessMessage(`✅ Violation "${violationObj.description}" added successfully!`);
      }
    }

    window.removeViolation = function(index) {
      selectedViolations.splice(index, 1);
      updateMultipleViolationsDisplay();

      // Hide students section if no violations are selected in multiple mode
      const isMultipleViolations = document.getElementById('enable_multiple_violations').checked;
      if (isMultipleViolations && selectedViolations.length === 0) {
        document.getElementById('students_selection_section').style.display = 'none';
      }

      // Update form progress
      updateFormProgress();
    }

    window.updateMultipleViolationsDisplay = function() {
      const container = document.getElementById('selected_violations_list');

      if (selectedViolations.length === 0) {
        container.innerHTML = '<p style="color: #666; font-style: italic; margin: 0;">No violations selected yet.</p>';
      } else {
        container.innerHTML = selectedViolations.map((violation, index) => `
          <div style="display: flex; justify-content: space-between; align-items: center; padding: 8px 12px; background: #f8f9fa; border: 1px solid #dee2e6; border-radius: 6px;">
            <div>
              <div style="font-weight: 600; color: #333;">${violation.description}</div>
              <div style="font-size: 12px; color: #666;">Article: ${violation.article || 'N/A'}</div>
            </div>
            <button type="button" onclick="removeViolation(${index})"
                    style="background: #dc3545; color: white; border: none; padding: 4px 8px; border-radius: 4px; cursor: pointer; font-size: 11px;">
              Remove
            </button>
          </div>
        `).join('');
      }
    }

    // Success message function
    window.showSuccessMessage = function(message) {
      // Create or update success message element
      let successDiv = document.getElementById('success_message');
      if (!successDiv) {
        successDiv = document.createElement('div');
        successDiv.id = 'success_message';
        successDiv.style.cssText = `
          position: fixed;
          top: 20px;
          right: 20px;
          background: #10B981;
          color: white;
          padding: 12px 20px;
          border-radius: 8px;
          box-shadow: 0 4px 12px rgba(0,0,0,0.15);
          z-index: 10001;
          font-weight: 600;
          transform: translateX(100%);
          transition: transform 0.3s ease;
        `;
        document.body.appendChild(successDiv);
      }

      successDiv.textContent = message;

      // Animate in
      setTimeout(() => {
        successDiv.style.transform = 'translateX(0)';
      }, 100);

      // Animate out after 3 seconds
      setTimeout(() => {
        successDiv.style.transform = 'translateX(100%)';
        setTimeout(() => {
          if (successDiv.parentNode) {
            successDiv.parentNode.removeChild(successDiv);
          }
        }, 300);
      }, 3000);
    }

    // Form progress tracking function
    window.updateFormProgress = function() {
      const offenseType = document.getElementById('offense_type').value;
      const isMultipleViolations = document.getElementById('enable_multiple_violations').checked;
      const singleViolation = document.getElementById('violation').value;
      const hasStudents = selectedStudents.length > 0;

      // Check each step
      const step1Complete = !!offenseType;
      const step2Complete = isMultipleViolations ? selectedViolations.length > 0 : !!singleViolation;
      const step3Complete = hasStudents;

      // Update step indicators
      document.getElementById('step1_status').innerHTML = step1Complete ?
        '✅ Step 1: Select offense type' : '❌ Step 1: Select offense type';
      document.getElementById('step2_status').innerHTML = step2Complete ?
        '✅ Step 2: Select violation(s)' : '❌ Step 2: Select violation(s)';
      document.getElementById('step3_status').innerHTML = step3Complete ?
        '✅ Step 3: Select student(s)' : '❌ Step 3: Select student(s)';

      // Check if all steps are complete
      const allStepsComplete = step1Complete && step2Complete && step3Complete;

      // Update submit button
      const submitBtn = document.getElementById('submit_button');
      if (allStepsComplete) {
        submitBtn.disabled = false;
        submitBtn.style.cursor = 'pointer';
        submitBtn.style.opacity = '1';

        // Update progress indicator
        const progressDiv = document.getElementById('form_progress');
        progressDiv.style.borderLeftColor = '#10B981';
        progressDiv.style.background = '#f0fdf4';
        progressDiv.innerHTML = `
          <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px;">
            <svg style="width: 16px; height: 16px; color: #10B981;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            <span style="font-weight: 600; color: #166534;">Ready to submit!</span>
          </div>
          <div style="font-size: 14px; color: #166534;">
            <div>✅ Step 1: Select offense type</div>
            <div>✅ Step 2: Select violation(s)</div>
            <div>✅ Step 3: Select student(s)</div>
          </div>
        `;
      } else {
        submitBtn.disabled = true;
        submitBtn.style.cursor = 'not-allowed';
        submitBtn.style.opacity = '0.6';

        // Keep original progress indicator
        const progressDiv = document.getElementById('form_progress');
        progressDiv.style.borderLeftColor = '#ffc107';
        progressDiv.style.background = '#f8f9fa';
      }
    }



    // Enhanced form submission with loading state
    window.validateMultipleViolatorsForm = function() {
      console.log('🔍 Form validation started');
      console.log('🔍 Submit button clicked - form validation function called');

      // Add debugging to console
      console.log('🔍 Form validation function called!');
      console.log('🔍 Current form state:', {
        submitButtonDisabled: document.getElementById('submit_button').disabled,
        formAction: document.getElementById('multiple-violators-form').action,
        formMethod: document.getElementById('multiple-violators-form').method
      });

      const offenseType = document.getElementById('offense_type').value;
      const isMultipleViolations = document.getElementById('enable_multiple_violations').checked;
      const singleViolation = document.getElementById('violation').value;

      console.log('📊 Form data:', {
        offenseType,
        isMultipleViolations,
        singleViolation,
        selectedStudents: selectedStudents.length,
        selectedViolations: selectedViolations.length
      });

      // Check offense type
      if (!offenseType) {
        console.log('❌ Validation failed: No offense type selected');
        alert('Please select an offense type.');
        return false;
      }

      // Check violations
      let hasViolations = false;
      if (isMultipleViolations) {
        console.log('🔄 Multiple violations mode');
        if (selectedViolations.length > 0) {
          hasViolations = true;
          // Set multiple violations data
          const violationsData = JSON.stringify(selectedViolations);
          document.getElementById('multiple_violations_data').value = violationsData;
          console.log('✅ Multiple violations data set:', violationsData);
          // Clear single violation to avoid conflicts
          document.getElementById('violation').value = '';
          // Make violation field not required for multiple mode
          document.getElementById('violation').removeAttribute('required');
        } else {
          console.log('❌ Validation failed: No violations selected in multiple mode');
          alert('Please select at least one violation in multiple violations mode.');
          return false;
        }
      } else {
        console.log('🔄 Single violation mode');
        if (singleViolation) {
          hasViolations = true;
          // Clear multiple violations data
          document.getElementById('multiple_violations_data').value = '';
          // Make violation field required for single mode
          document.getElementById('violation').setAttribute('required', 'required');
          console.log('✅ Single violation selected:', singleViolation);
        } else {
          console.log('❌ Validation failed: No single violation selected');
          alert('Please select a violation.');
          return false;
        }
      }

      if (!hasViolations) {
        console.log('❌ Validation failed: No violations at all');
        alert('Please select at least one violation.');
        return false;
      }

      // Check students
      if (selectedStudents.length === 0) {
        console.log('❌ Validation failed: No students selected');
        alert('Please select at least one student.');
        return false;
      }

      // Show loading state
      const submitBtn = document.querySelector('button[type="submit"]');
      const originalText = submitBtn.innerHTML;
      submitBtn.innerHTML = `
        <svg style="width: 16px; height: 16px; margin-right: 8px; animation: spin 1s linear infinite;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none" opacity="0.25"></circle>
          <path fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" opacity="0.75"></path>
        </svg>
        Processing...
      `;
      submitBtn.disabled = true;

      // Add spin animation
      const spinStyle = document.createElement('style');
      spinStyle.textContent = `
        @keyframes spin {
          from { transform: rotate(0deg); }
          to { transform: rotate(360deg); }
        }
      `;
      document.head.appendChild(spinStyle);

      // Show confirmation message
      const studentCount = selectedStudents.length;
      const violationCount = isMultipleViolations ? selectedViolations.length : 1;
      const confirmMessage = `You are about to add ${violationCount} violation(s) to ${studentCount} student(s). This will create ${studentCount * violationCount} violation record(s). Continue?`;

      console.log('📋 Final form data before submission:', {
        studentCount,
        violationCount,
        totalRecords: studentCount * violationCount,
        formAction: document.getElementById('multiple-violators-form').action,
        multipleViolationsData: document.getElementById('multiple_violations_data').value,
        studentIds: Array.from(document.querySelectorAll('input[name="student_ids[]"]')).map(input => input.value)
      });

      if (!confirm(confirmMessage)) {
        console.log('❌ User cancelled submission');
        // Restore button if user cancels
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
        return false;
      }

      console.log('✅ Form validation passed, submitting...');

      // Add a final check to ensure form data is properly set
      console.log('🔍 Final form check before submission:');
      console.log('Form action:', document.getElementById('multiple-violators-form').action);
      console.log('Form method:', document.getElementById('multiple-violators-form').method);
      console.log('CSRF token:', document.querySelector('input[name="_token"]')?.value);
      console.log('Multiple violations data:', document.getElementById('multiple_violations_data').value);
      console.log('Student IDs:', Array.from(document.querySelectorAll('input[name="student_ids[]"]')).map(input => input.value));

      console.log('🚀 RETURNING TRUE - Form should submit now!');
      alert('🚀 Form validation passed! Submitting form...');

      return true;
    }












  </script>
</x-dashboard-layout>
