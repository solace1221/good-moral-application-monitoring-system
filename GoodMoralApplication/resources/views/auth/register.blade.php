<x-guest-layout>
  <div class="form-container-wide">
    <h2 class="form-title">Create Account</h2>
    <div class="accent-line"></div>
    <p class="form-subtitle">Join the Good Moral Application Portal</p>

    <form method="POST" action="{{ route('register') }}">
      @csrf
      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
        <div>
          <!-- First Name -->
          <div style="margin-bottom: 20px;">
            <label for="fname" class="form-label">First Name <span style="color: #dc3545; font-weight: bold;">*</span></label>
            <input id="fname" class="form-input" type="text" name="fname" value="{{ old('fname') }}"
                   required autofocus autocomplete="fname" placeholder="Enter First Name"
                   style="text-transform: uppercase;" pattern="[A-Za-z\s]+" title="Only letters and spaces are allowed">
            <x-input-error :messages="$errors->get('fname')" class="mt-2" style="color: #e74c3c; font-size: 12px;" />
          </div>

          <!-- Middle Name -->
          <div style="margin-bottom: 20px;">
            <label for="mname" class="form-label">Middle Initial</label>
            <input id="mname" class="form-input" type="text" name="mname" value="{{ old('mname') }}"
                   autocomplete="mname" placeholder="Enter Middle Initial"
                   style="text-transform: uppercase;" pattern="[A-Za-z\s]*" title="Only letters and spaces are allowed">
            <x-input-error :messages="$errors->get('mname')" class="mt-2" style="color: #e74c3c; font-size: 12px;" />
          </div>

          <!-- Department -->
          <div style="margin-bottom: 20px;">
            <label for="department" class="form-label">Department <span style="color: #dc3545; font-weight: bold;">*</span></label>
            <select id="department" name="department" class="form-input" required>
              <option value="" disabled selected>Select Department</option>
              @foreach($departments as $deptCode => $deptName)
                <option value="{{ $deptCode }}" {{ old('department') == $deptCode ? 'selected' : '' }}>
                  {{ $deptName }}
                </option>
              @endforeach
            </select>
            <x-input-error :messages="$errors->get('department')" class="mt-2" style="color: #e74c3c; font-size: 12px;" />
          </div>

          <!-- Course (This will be shown/hidden based on account type) -->
          <div id="course-dropdown-main" style="margin-bottom: 20px; display: none; border: 2px solid #e8f5e8; padding: 12px; border-radius: 8px; background: #f8fff8;">
            <label for="year_level_main" class="form-label" style="color: var(--primary-green); font-weight: 600;">Course & Year Level (Required for Students)</label>
            <select id="year_level_main" name="year_level" class="form-input">
              <option value="" disabled selected>Select Course & Year</option>
            </select>
            <x-input-error :messages="$errors->get('year_level')" class="mt-2" style="color: #e74c3c; font-size: 12px;" />
            <p style="color: #6b7280; font-size: 12px; margin-top: 4px;">
              Select your department first to see available courses.
            </p>
          </div>

          <!-- Account Type -->
          <div style="margin-bottom: 20px;">
            <label for="account_type" class="form-label">Account Type <span style="color: #dc3545; font-weight: bold;">*</span></label>
            <select id="account_type" name="account_type" class="form-input" required>
              <option value="" disabled selected>Select Account Type</option>
              <option value="student">Student</option>
              <option value="alumni">Alumni</option>
              <option value="psg_officer">PSG Officer</option>
            </select>
            <x-input-error :messages="$errors->get('account_type')" class="mt-2" style="color: #e74c3c; font-size: 12px;" />
            <p style="color: #6c757d; font-size: 12px; margin-top: 8px;">
              <strong>Note:</strong> Select the account type that applies to you.
            </p>
          </div>

          <!-- PSG Officer Fields (Hidden by default) -->
          <div id="psg-fields" style="display: none;">
            <!-- Designation -->
            <div style="margin-bottom: 20px;">
              <label for="designation_id" class="form-label">Designation</label>
              <select id="designation_id" class="form-input" name="designation_id" style="padding: 12px 16px; border-radius: 8px; border: 1px solid #d1d5db;">
                <option value="">Select Designation</option>
                @foreach($designations as $designation)
                  <option value="{{ $designation->dsn_id }}" 
                          data-dept-id="{{ $designation->dept_id }}" 
                          data-dept-code="{{ $designation->department ? $designation->department->department_code : '' }}"
                          data-dept-name="{{ $designation->department ? $designation->department->department_name : '' }}"
                          {{ old('designation_id') == $designation->dsn_id ? 'selected' : '' }}>
                    {{ $designation->description }}
                  </option>
                @endforeach
              </select>
              <x-input-error :messages="$errors->get('designation_id')" class="mt-2" style="color: #e74c3c; font-size: 12px;" />
            </div>

            <!-- Position -->
            <div style="margin-bottom: 20px;">
              <label for="position_id" class="form-label">Position</label>
              <select id="position_id" class="form-input" name="position_id" style="padding: 12px 16px; border-radius: 8px; border: 1px solid #d1d5db;">
                <option value="">Select Designation First</option>
                @foreach($positions as $position)
                  <option value="{{ $position->position_id }}" 
                          data-dsn-id="{{ $position->dsn_id }}"
                          {{ old('position_id') == $position->position_id ? 'selected' : '' }}>
                    {{ $position->position_title }}
                  </option>
                @endforeach
              </select>
              <x-input-error :messages="$errors->get('position_id')" class="mt-2" style="color: #e74c3c; font-size: 12px;" />
            </div>
          </div>

          <!-- Student Fields (Hidden by default) -->
          <div id="student-fields" style="display: none;">
            <p style="color: #6c757d; font-size: 12px; margin-bottom: 8px;">
              <strong>Note:</strong> Course selection will appear above after selecting your department.
            </p>

          </div>

        </div>
        <div>
          <!-- Last Name -->
          <div style="margin-bottom: 20px;">
            <label for="lname" class="form-label">Last Name <span style="color: #dc3545; font-weight: bold;">*</span></label>
            <input id="lname" class="form-input" type="text" name="lname" value="{{ old('lname') }}"
                   required autocomplete="lname" placeholder="Enter Last Name"
                   style="text-transform: uppercase;" pattern="[A-Za-z\s]+" title="Only letters and spaces are allowed">
            <x-input-error :messages="$errors->get('lname')" class="mt-2" style="color: #e74c3c; font-size: 12px;" />
          </div>

          <!-- Extension -->
          <div style="margin-bottom: 20px;">
            <label for="extension" class="form-label">Extension</label>
            <input id="extension" class="form-input" type="text" name="extension" value="{{ old('extension') }}"
                   autocomplete="extension" placeholder="Jr., Sr., III, etc."
                   style="text-transform: uppercase;" pattern="[A-Za-z\s]*" title="Only letters and spaces are allowed">
            <x-input-error :messages="$errors->get('extension')" class="mt-2" style="color: #e74c3c; font-size: 12px;" />
          </div>

          <!-- Gender -->
          <div style="margin-bottom: 20px;">
            <label for="gender" class="form-label">Gender <span style="color: #dc3545; font-weight: bold;">*</span></label>
            <select id="gender" name="gender" class="form-input" required>
              <option value="" disabled selected>Select Gender</option>
              <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
              <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
            </select>
            <x-input-error :messages="$errors->get('gender')" class="mt-2" style="color: #e74c3c; font-size: 12px;" />
          </div>
          <!-- Student ID -->
          <div style="margin-bottom: 20px;">
            <label for="student_id" class="form-label">Student ID <span style="color: #dc3545; font-weight: bold;">*</span></label>
            <input id="student_id" class="form-input" type="text" name="student_id" value="{{ old('student_id') }}"
                   required autocomplete="student_id" placeholder="Enter Student ID">
            <x-input-error :messages="$errors->get('student_id')" class="mt-2" style="color: #e74c3c; font-size: 12px;" />
          </div>

          <!-- Email Address -->
          <div style="margin-bottom: 20px;">
            <label for="email" class="form-label">Email Address <span style="color: #dc3545; font-weight: bold;">*</span></label>
            <input id="email" class="form-input" type="email" name="email" value="{{ old('email') }}"
                   required autocomplete="username" placeholder="Enter Email Address">
            <x-input-error :messages="$errors->get('email')" class="mt-2" style="color: #e74c3c; font-size: 12px;" />
          </div>
      </div>
    </div>

      <!-- Password Fields (Full Width) -->
      <div style="grid-column: 1 / -1; display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-top: 20px;">
        <!-- Password -->
        <div>
          <label for="password" class="form-label">Password <span style="color: #dc3545; font-weight: bold;">*</span></label>
          <input id="password" class="form-input" type="password" name="password"
                 required autocomplete="new-password" placeholder="Enter Password">
          <x-input-error :messages="$errors->get('password')" class="mt-2" style="color: #e74c3c; font-size: 12px;" />
        </div>

        <!-- Confirm Password -->
        <div>
          <label for="password_confirmation" class="form-label">Confirm Password <span style="color: #dc3545; font-weight: bold;">*</span></label>
          <input id="password_confirmation" class="form-input" type="password" name="password_confirmation"
                 required autocomplete="new-password" placeholder="Confirm Password">
          <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" style="color: #e74c3c; font-size: 12px;" />
        </div>
      </div>

      <!-- Submit Button -->
      <div style="margin-top: 32px; position: relative; z-index: 100;">
        <!-- Submit Button -->
        <button type="submit" id="submitButton" class="form-button">
          Create Account
        </button>

        <!-- Links -->
        <div style="text-align: center; margin-top: 24px; padding-top: 24px; border-top: 1px solid #e1e8ed;">
          <p style="color: #7f8c8d; font-size: 14px; margin-bottom: 8px;">Already have an account?</p>
          <a href="{{ route('login') }}" class="form-link">Sign in here</a>
        </div>
      </div>
    </form>

    <!-- JavaScript for dynamic course dropdown and PSG fields -->
    <script>
      const coursesByDepartment = @json($coursesByDepartment);
      const designationsData = @json($designations);
      const positionsData = @json($positions);
      console.log('Courses by department data:', coursesByDepartment);
      console.log('Total departments:', Object.keys(coursesByDepartment).length);

      // Store all designation and position options at page load
      let allDesignationOptions = [];
      let allPositionOptions = [];

      // Function to toggle account type specific fields
      function toggleAccountTypeFields() {
        const accountType = document.getElementById('account_type').value;
        const psgFields = document.getElementById('psg-fields');
        const studentFields = document.getElementById('student-fields');
        const courseDropdown = document.getElementById('course-dropdown-main');
        const designationInput = document.getElementById('designation_id');
        const positionInput = document.getElementById('position_id');
        const yearLevelInput = document.getElementById('year_level_main');

        console.log('Account type changed to:', accountType);

        // Reset all fields
        psgFields.style.display = 'none';
        studentFields.style.display = 'none';
        courseDropdown.style.display = 'none';

        // Clear requirements
        if (designationInput) designationInput.required = false;
        if (positionInput) positionInput.required = false;
        if (yearLevelInput) yearLevelInput.required = false;

        // Clear values
        if (designationInput) designationInput.value = '';
        if (positionInput) positionInput.value = '';
        if (yearLevelInput) yearLevelInput.value = '';

        if (accountType === 'psg_officer') {
          psgFields.style.display = 'block';
          if (designationInput) designationInput.required = true;
          if (positionInput) positionInput.required = true;
        } else if (accountType === 'student') {
          console.log('Student account type selected');
          studentFields.style.display = 'block';
          yearLevelInput.required = true;

          // Show course dropdown if department is already selected
          const department = document.getElementById('department').value;
          console.log('Current department:', department);

          courseDropdown.style.display = 'block';
          console.log('Course dropdown should now be visible');

          if (department && coursesByDepartment[department]) {
            console.log('Updating course options for department:', department);
            updateCourseOptions(department);
          } else {
            // Show course dropdown but with placeholder if no department selected
            const courseSelect = document.getElementById('year_level_main');
            courseSelect.innerHTML = '<option value="" disabled selected>Select Department First</option>';
            console.log('Set placeholder for course dropdown');
          }
        }
      }

      document.addEventListener('DOMContentLoaded', function() {
        // Store all designation options
        const designationSelect = document.getElementById('designation_id');
        allDesignationOptions = Array.from(designationSelect.querySelectorAll('option')).filter(opt => opt.value);
        
        // Store all position options
        const positionSelect = document.getElementById('position_id');
        allPositionOptions = Array.from(positionSelect.querySelectorAll('option')).filter(opt => opt.value);

        // Department change handler
        document.getElementById('department').addEventListener('change', function() {
          const department = this.value;
          const accountType = document.getElementById('account_type').value;
          const courseDropdown = document.getElementById('course-dropdown-main');

          console.log('Department changed:', department, 'Account type:', accountType);

          if (accountType === 'student') {
            courseDropdown.style.display = 'block';
            if (department && coursesByDepartment[department]) {
              updateCourseOptions(department);
            } else {
              // Clear options if no valid department
              const courseSelect = document.getElementById('year_level_main');
              courseSelect.innerHTML = '<option value="" disabled selected>Select Department First</option>';
            }
          } else {
            // No filtering needed for other account types
            courseDropdown.style.display = 'none';
          }
        });

        // Designation change handler for PSG Officers
        document.getElementById('designation_id').addEventListener('change', function() {
          const designationId = this.value;
          console.log('Designation changed:', designationId);
          filterPositionsByDesignation(designationId);
        });

        // Check if all elements are found
        const courseDropdown = document.getElementById('course-dropdown-main');
        const yearLevelSelect = document.getElementById('year_level_main');
        console.log('Course dropdown element found:', courseDropdown);
        console.log('Year level select found:', yearLevelSelect);

        // Account type change handler
        document.getElementById('account_type').addEventListener('change', toggleAccountTypeFields);

        toggleAccountTypeFields();

        // Simplified form validation
        const form = document.querySelector('form');
        const submitButton = document.getElementById('submitButton');

        if (form && submitButton) {
          console.log('Form and submit button found successfully');

          // Add click event listener to submit button
          submitButton.addEventListener('click', function(e) {
            console.log('Submit button clicked!');
          });

          // Add form submit event listener with basic validation
          form.addEventListener('submit', function(e) {
            console.log('Form submission started');

            // Basic validation for conditional fields
            const accountType = document.getElementById('account_type').value;

            if (accountType === 'student') {
              const yearLevel = document.getElementById('year_level_main').value;
              const department = document.getElementById('department').value;

              if (!department) {
                alert('Please select your department first.');
                e.preventDefault();
                return false;
              }

              if (!yearLevel) {
                alert('Please select your course and year level.');
                e.preventDefault();
                return false;
              }
            }

            if (accountType === 'psg_officer') {
              const designation = document.getElementById('designation_id').value;
              const position = document.getElementById('position_id').value;

              if (!designation || !position) {
                alert('Please select your designation and position.');
                e.preventDefault();
                return false;
              }
            }

            console.log('Form validation passed, submitting...');
          });
        } else {
          console.error('Form or submit button not found');
        }
      });

      // Function to update course options based on department
      function updateCourseOptions(department) {
        const courseSelect = document.getElementById('year_level_main');

        // Clear existing options
        courseSelect.innerHTML = '<option value="" disabled selected>Select Course & Year</option>';

        if (coursesByDepartment[department]) {
          coursesByDepartment[department].forEach(course => {
            // Add year levels for each course
            const years = ['1st Year', '2nd Year', '3rd Year', '4th Year', '5th Year'];
            years.forEach(year => {
              const option = document.createElement('option');
              option.value = `${course} - ${year}`;
              option.textContent = `${course} - ${year}`;
              courseSelect.appendChild(option);
            });
          });
        }
      }

      // Function to filter designations based on selected department
      function filterDesignationsByDepartment(departmentCode) {
        const designationSelect = document.getElementById('designation_id');
        
        // Clear current options
        designationSelect.innerHTML = '<option value="">Select Designation</option>';
        
        if (!departmentCode) {
          designationSelect.innerHTML = '<option value="">Select Department First</option>';
          return;
        }

        // Filter and add matching designations
        let hasOptions = false;
        allDesignationOptions.forEach(option => {
          if (option.dataset.deptCode == departmentCode) {
            const newOption = option.cloneNode(true);
            designationSelect.appendChild(newOption);
            hasOptions = true;
          }
        });

        if (!hasOptions) {
          designationSelect.innerHTML = '<option value="">No designations available for this department</option>';
        }

        // Reset position dropdown when designation changes
        filterPositionsByDesignation('');
      }

      // Function to filter positions based on selected designation
      function filterPositionsByDesignation(designationId) {
        const positionSelect = document.getElementById('position_id');
        
        // Clear current options
        positionSelect.innerHTML = '<option value="">Select Position</option>';
        
        if (!designationId) {
          positionSelect.innerHTML = '<option value="">Select Designation First</option>';
          return;
        }

        // Filter and add matching positions
        let hasOptions = false;
        allPositionOptions.forEach(option => {
          if (option.dataset.dsnId == designationId) {
            const newOption = option.cloneNode(true);
            positionSelect.appendChild(newOption);
            hasOptions = true;
          }
        });

        if (!hasOptions) {
          positionSelect.innerHTML = '<option value="">No positions available for this designation</option>';
        }
      }

    </script>

    <!-- Ensure button is clickable -->
    <style>
      #submitButton {
        position: relative !important;
        z-index: 999 !important;
        pointer-events: auto !important;
        cursor: pointer !important;
        display: block !important;
        border: 2px solid transparent !important;
      }

      /* Debug: Add a visible border when hovering to confirm button is responsive */
      #submitButton:hover {
        border: 2px solid #fff !important;
        transform: translateY(-1px) !important;
      }

      /* Ensure no elements are blocking the button */
      .form-container-wide * {
        pointer-events: auto;
      }
    </style>
  </div>
</x-guest-layout>