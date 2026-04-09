<x-dashboard-layout>
  <x-slot name="roleTitle">Admin</x-slot>

  <x-slot name="navigation">
    <x-admin-navigation />
  </x-slot>

  <!-- Add CSRF token for JavaScript -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <!-- Header Section -->
  <div class="header-section">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
      <div>
        <h1 class="role-title">Account Management</h1>
        <p class="welcome-text">Create and manage user accounts in the system</p>
        <div class="accent-line"></div>
      </div>
    </div>
  </div>

  <!-- Success/Error Messages -->
  @if(session('success'))
  <div style="background: #d4edda; color: #155724; padding: 16px; border-radius: 8px; border-left: 4px solid #28a745; margin-bottom: 24px; display: flex; align-items: center; gap: 12px;">
    <svg style="width: 20px; height: 20px; flex-shrink: 0;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
    </svg>
    <span style="font-weight: 500;">{{ session('success') }}</span>
  </div>
  @endif

  @if(session('error'))
  <div style="background: #f8d7da; color: #721c24; padding: 16px; border-radius: 8px; border-left: 4px solid #dc3545; margin-bottom: 24px; display: flex; align-items: center; gap: 12px;">
    <svg style="width: 20px; height: 20px; flex-shrink: 0;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
    </svg>
    <span style="font-weight: 500;">{{ session('error') }}</span>
  </div>
  @endif

  @if($errors->any())
  <div style="background: #fff3cd; color: #856404; padding: 16px; border-radius: 8px; border-left: 4px solid #ffc107; margin-bottom: 24px;">
    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
      <svg style="width: 20px; height: 20px; flex-shrink: 0;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
      </svg>
      <span style="font-weight: 600;">Please fix the following errors:</span>
    </div>
    <ul style="margin: 0; padding-left: 20px;">
      @foreach($errors->all() as $error)
      <li style="margin-bottom: 4px;">{{ $error }}</li>
      @endforeach
    </ul>
  </div>
  @endif

  <!-- Tab Navigation -->
  <div style="background: white; border-radius: 12px 12px 0 0; box-shadow: 0 2px 8px rgba(0,0,0,0.1); margin-top: 24px;">
    <div style="display: flex; border-bottom: 2px solid #e9ecef;">
      <button onclick="showTab('manual')" id="manual-tab"
              style="padding: 16px 24px; background: none; border: none; font-weight: 600; border-bottom: 3px solid var(--primary-green); color: var(--primary-green); cursor: pointer; transition: all 0.3s ease;">
        <svg style="width: 18px; height: 18px; margin-right: 8px; vertical-align: middle;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766Z"></path>
        </svg>
        Add Single Account
      </button>
      <button onclick="showTab('import')" id="import-tab"
              style="padding: 16px 24px; background: none; border: none; font-weight: 600; border-bottom: 3px solid transparent; color: #6c757d; cursor: pointer; transition: all 0.3s ease;">
        <svg style="width: 18px; height: 18px; margin-right: 8px; vertical-align: middle;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 16.5V9.75m0 0 3 3m-3-3-3 3M6.75 19.5a4.5 4.5 0 0 1-1.41-8.775 5.25 5.25 0 0 1 10.233-2.33 3 3 0 0 1 3.758 3.848A3.752 3.752 0 0 1 18 19.5H6.75Z"></path>
        </svg>
        Import Students
      </button>
      <button onclick="showTab('list')" id="list-tab"
              style="padding: 16px 24px; background: none; border: none; font-weight: 600; border-bottom: 3px solid transparent; color: #6c757d; cursor: pointer; transition: all 0.3s ease;">
        <svg style="width: 18px; height: 18px; margin-right: 8px; vertical-align: middle;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z"></path>
        </svg>
        View Accounts ({{ $students->total() }})
      </button>
    </div>
  </div>

  <!-- Main Content -->
  <div class="header-section">
    <!-- Status Messages -->
    @if (session('success'))
    <div style="margin-bottom: 24px; padding: 16px; background: #d4edda; color: #155724; border: 1px solid #c3e6cb; border-radius: 8px;">
      {{ session('success') }}
    </div>
    @endif

    @if (session('error'))
    <div style="margin-bottom: 24px; padding: 16px; background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; border-radius: 8px;">
      {{ session('error') }}
    </div>
    @endif

    @if (session('import_result'))
    <div style="margin-bottom: 24px; padding: 16px; background: #d1ecf1; color: #0c5460; border: 1px solid #bee5eb; border-radius: 8px;">
      {{ session('import_result') }}
      @if (session('import_errors'))
        <details style="margin-top: 12px;">
          <summary style="cursor: pointer; font-weight: 600;">View Errors</summary>
          <pre style="margin-top: 8px; padding: 12px; background: #f8f9fa; border-radius: 4px; font-size: 12px; white-space: pre-wrap;">{{ session('import_errors') }}</pre>
        </details>
      @endif
    </div>
    @endif

    <!-- Manual Add Account Tab -->
    <div id="manual-content" style="background: white; border-radius: 0 0 12px 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); padding: 24px;">
      <h3 style="margin: 0 0 24px 0; color: var(--primary-green); font-size: 1.25rem; font-weight: 600;">Add Single Account</h3>
      <!-- Form -->
      <form method="POST" action="{{ route('registeraccount') }}" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-bottom: 24px;">
        @csrf

        <!-- Account Type -->
        <div>
          <label for="account_type" style="display: block; margin-bottom: 8px; font-weight: 600; color: #333;">Account Type</label>
          <select id="account_type" name="account_type"
            style="width: 100%; padding: 12px 16px; border: 2px solid #e1e5e9; border-radius: 8px; font-size: 14px; transition: border-color 0.3s ease;"
            required
            onchange="toggleStudentFields()">
            <option value="" disabled selected>Select Account Type</option>
            <option value="dean" {{ old('account_type') == 'dean' ? 'selected' : '' }}>Dean</option>
            <option value="sec_osa" {{ old('account_type') == 'sec_osa' ? 'selected' : '' }}>Moderator</option>
            <option value="registrar" {{ old('account_type') == 'registrar' ? 'selected' : '' }}>Registrar</option>
            <option value="prog_coor" {{ old('account_type') == 'prog_coor' ? 'selected' : '' }}>Program Coordinator</option>
            <option value="student" {{ old('account_type') == 'student' ? 'selected' : '' }}>Student</option>
            <option value="alumni" {{ old('account_type') == 'alumni' ? 'selected' : '' }}>Alumni</option>
          </select>
          <x-input-error :messages="$errors->get('account_type')" class="mt-1" />
        </div>

        <!-- Department -->
        <div x-data="{ showOther: false }">
          <label for="department" style="display: block; margin-bottom: 8px; font-weight: 600; color: #333;">Department</label>
          <select id="department" name="department"
            style="width: 100%; padding: 12px 16px; border: 2px solid #e1e5e9; border-radius: 8px; font-size: 14px; transition: border-color 0.3s ease;"
            x-on:change="showOther = $event.target.value === 'Others'"
            required>
            <option value="" disabled selected>Select Department</option>
            <option value="SITE" {{ old('department') == 'SITE' ? 'selected' : '' }}>SITE</option>
            <option value="SASTE" {{ old('department') == 'SASTE' ? 'selected' : '' }}>SASTE</option>
            <option value="SBAHM" {{ old('department') == 'SBAHM' ? 'selected' : '' }}>SBAHM</option>
            <option value="SNAHS" {{ old('department') == 'SNAHS' ? 'selected' : '' }}>SNAHS</option>
            <option value="SOM" {{ old('department') == 'SOM' ? 'selected' : '' }}>SOM</option>
            <option value="GRADSCH" {{ old('department') == 'GRADSCH' ? 'selected' : '' }}>GRADSCH</option>
            <option value="Others" {{ old('department') == 'Others' ? 'selected' : '' }}>Others</option>
          </select>
          <div x-show="showOther" style="margin-top: 12px;">
            <label for="other_department" style="display: block; margin-bottom: 8px; font-weight: 600; color: #333;">Please specify other department</label>
            <input
              type="text"
              name="other_department"
              id="other_department"
              style="width: 100%; padding: 12px 16px; border: 2px solid #e1e5e9; border-radius: 8px; font-size: 14px; transition: border-color 0.3s ease;"
              placeholder="Please specify..."
              value="{{ old('other_department') }}" />
          </div>
          <x-input-error :messages="$errors->get('department')" class="mt-1" />
        </div>

        <!-- Student ID -->
        <div id="student_id_field">
          <label for="student_id" style="display: block; margin-bottom: 8px; font-weight: 600; color: #333;">Student ID <span style="color: #dc3545;">*</span></label>
          <input
            type="text"
            id="student_id"
            name="student_id"
            placeholder="Enter Student ID"
            style="width: 100%; padding: 12px 16px; border: 2px solid #e1e5e9; border-radius: 8px; font-size: 14px; transition: border-color 0.3s ease;"
            value="{{ old('student_id') }}" />
          <x-input-error :messages="$errors->get('student_id')" class="mt-1" />
        </div>

        <!-- Full Name -->
        <div>
          <label for="fullname" style="display: block; margin-bottom: 8px; font-weight: 600; color: #333;">Full Name</label>
          <input
            type="text"
            id="fullname"
            name="fullname"
            required
            placeholder="Surname, Firstname"
            style="width: 100%; padding: 12px 16px; border: 2px solid #e1e5e9; border-radius: 8px; font-size: 14px; transition: border-color 0.3s ease;"
            value="{{ old('fullname') }}" />
          <x-input-error :messages="$errors->get('fullname')" class="mt-1" />
        </div>

        <!-- Email -->
        <div>
          <label for="email" style="display: block; margin-bottom: 8px; font-weight: 600; color: #333;">Email</label>
          <input
            type="email"
            id="email"
            name="email"
            required
            placeholder="Enter Email"
            style="width: 100%; padding: 12px 16px; border: 2px solid #e1e5e9; border-radius: 8px; font-size: 14px; transition: border-color 0.3s ease;"
            value="{{ old('email') }}" />
          <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <!-- Course -->
        <div id="course_field">
          <label for="course" style="display: block; margin-bottom: 8px; font-weight: 600; color: #333;">Course <span style="color: #dc3545;">*</span></label>
          <select
            id="course"
            name="course"
            style="width: 100%; padding: 12px 16px; border: 2px solid #e1e5e9; border-radius: 8px; font-size: 14px; transition: border-color 0.3s ease; background: white;">
            <option value="">Select Course</option>
            <option value="BS Information Technology" {{ old('course') == 'BS Information Technology' ? 'selected' : '' }}>BS Information Technology</option>
            <option value="BS Computer Science" {{ old('course') == 'BS Computer Science' ? 'selected' : '' }}>BS Computer Science</option>
            <option value="BS Business Administration" {{ old('course') == 'BS Business Administration' ? 'selected' : '' }}>BS Business Administration</option>
            <option value="BS Accountancy" {{ old('course') == 'BS Accountancy' ? 'selected' : '' }}>BS Accountancy</option>
            <option value="BS Psychology" {{ old('course') == 'BS Psychology' ? 'selected' : '' }}>BS Psychology</option>
            <option value="BS Education" {{ old('course') == 'BS Education' ? 'selected' : '' }}>BS Education</option>
            <option value="BS Nursing" {{ old('course') == 'BS Nursing' ? 'selected' : '' }}>BS Nursing</option>
            <option value="BS Engineering" {{ old('course') == 'BS Engineering' ? 'selected' : '' }}>BS Engineering</option>
          </select>
          <x-input-error :messages="$errors->get('course')" class="mt-1" />
        </div>

        <!-- Year Level -->
        <div id="year_level_field">
          <label for="year_level" style="display: block; margin-bottom: 8px; font-weight: 600; color: #333;">Year Level <span style="color: #dc3545;">*</span></label>
          <select
            id="year_level"
            name="year_level"
            style="width: 100%; padding: 12px 16px; border: 2px solid #e1e5e9; border-radius: 8px; font-size: 14px; transition: border-color 0.3s ease; background: white;">
            <option value="">Select Year Level</option>
            <option value="1st Year" {{ old('year_level') == '1st Year' ? 'selected' : '' }}>1st Year</option>
            <option value="2nd Year" {{ old('year_level') == '2nd Year' ? 'selected' : '' }}>2nd Year</option>
            <option value="3rd Year" {{ old('year_level') == '3rd Year' ? 'selected' : '' }}>3rd Year</option>
            <option value="4th Year" {{ old('year_level') == '4th Year' ? 'selected' : '' }}>4th Year</option>
            <option value="5th Year" {{ old('year_level') == '5th Year' ? 'selected' : '' }}>5th Year</option>
          </select>
          <x-input-error :messages="$errors->get('year_level')" class="mt-1" />
        </div>

        <!-- Password -->
        <div>
          <label for="password" style="display: block; margin-bottom: 8px; font-weight: 600; color: #333;">Password</label>
          <input
            type="password"
            id="password"
            name="password"
            required
            placeholder="Enter Password"
            style="width: 100%; padding: 12px 16px; border: 2px solid #e1e5e9; border-radius: 8px; font-size: 14px; transition: border-color 0.3s ease;" />
          <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        <!-- Confirm Password -->
        <div>
          <label for="password_confirmation" style="display: block; margin-bottom: 8px; font-weight: 600; color: #333;">Confirm Password</label>
          <input
            type="password"
            id="password_confirmation"
            name="password_confirmation"
            required
            placeholder="Confirm Password"
            style="width: 100%; padding: 12px 16px; border: 2px solid #e1e5e9; border-radius: 8px; font-size: 14px; transition: border-color 0.3s ease;" />
          <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
        </div>

        <div style="grid-column: 1 / -1; margin-top: 24px;">
          <button type="submit" class="btn-primary" style="color: #ffffff !important;">
            <svg style="width: 18px; height: 18px; margin-right: 8px; color: #ffffff !important;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766Z" />
            </svg>
            <span style="color: #ffffff !important;">Create Account</span>
          </button>
        </div>
      </form>
    </div>

    <!-- Import Students Tab -->
    <div id="import-content" style="background: white; border-radius: 0 0 12px 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); padding: 24px; display: none;">
      <h3 style="margin: 0 0 24px 0; color: var(--primary-green); font-size: 1.25rem; font-weight: 600;">Import Students from CSV</h3>

      <!-- Instructions -->
      <div style="background: #e3f2fd; border-left: 4px solid #2196f3; padding: 16px; margin-bottom: 24px; border-radius: 4px;">
        <h4 style="margin: 0 0 8px 0; color: #1976d2; font-weight: 600;">Instructions:</h4>
        <ul style="margin: 0; padding-left: 20px; color: #1976d2;">
          <li>Download the CSV template below to see the required format</li>
          <li>Fill in student information: student_id, first_name, middle_initial, last_name, extension_name, department, course, year_level, email</li>
          <li>All students will be created with default password: <strong>student123</strong></li>
          <li>Students will need to change their password on first login</li>
          <li>Required fields: student_id, first_name, last_name, department, course, year_level, email</li>
        </ul>
      </div>

      <!-- Download Template -->
      <div style="margin-bottom: 24px;">
        <a href="{{ route('admin.downloadTemplate') }}" class="btn-secondary" style="display: inline-flex; align-items: center;">
          <svg style="width: 16px; height: 16px; margin-right: 8px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
          </svg>
          Download CSV Template
        </a>
      </div>

      <!-- Upload Form -->
      <form method="POST" action="{{ route('admin.importUsers') }}" enctype="multipart/form-data" style="border: 2px dashed #e1e5e9; border-radius: 8px; padding: 24px; text-align: center;">
        @csrf
        <div style="margin-bottom: 16px;">
          <svg style="width: 48px; height: 48px; margin: 0 auto 16px; color: #6c757d;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
          </svg>
          <h4 style="margin: 0 0 8px 0; color: #333;">Upload CSV File</h4>
          <p style="margin: 0; color: #6c757d; font-size: 14px;">Select a CSV file containing student data</p>
        </div>

        <input type="file" name="csv_file" accept=".csv,.txt" required
               style="margin-bottom: 16px; padding: 8px; border: 1px solid #e1e5e9; border-radius: 4px;">

        <div>
          <button type="submit" class="btn-primary" style="color: #ffffff !important;">
            <svg style="width: 16px; height: 16px; margin-right: 8px; color: #ffffff !important;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
            </svg>
            <span style="color: #ffffff !important;">Import Students</span>
          </button>
        </div>
      </form>
    </div>

    <!-- Accounts List Tab -->
    <div id="list-content" style="background: white; border-radius: 0 0 12px 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); padding: 24px; display: none;">
      <h3 style="margin: 0 0 24px 0; color: var(--primary-green); font-size: 1.25rem; font-weight: 600;">
        @if(request()->hasAny(['search_name', 'search_student_id']))
          Search Results ({{ $students->total() }} found)
        @else
          All User Accounts ({{ $students->total() }})
        @endif
      </h3>

      <!-- Search Form -->
      <form method="GET" action="{{ route('admin.AddAccount') }}" style="margin-bottom: 24px; padding: 20px; background: #f8f9fa; border-radius: 8px; border-left: 4px solid var(--primary-green);">
        <input type="hidden" name="tab" value="list">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 16px;">
          <div>
            <label for="search_name" style="display: block; margin-bottom: 6px; font-weight: 600; color: #333; font-size: 14px;">Full Name</label>
            <input type="text" id="search_name" name="search_name"
                   style="width: 100%; padding: 10px 12px; border: 2px solid #e1e5e9; border-radius: 6px; font-size: 14px; transition: border-color 0.3s ease;"
                   value="{{ request('search_name') }}"
                   placeholder="Search by name">
          </div>
          <div>
            <label for="search_student_id" style="display: block; margin-bottom: 6px; font-weight: 600; color: #333; font-size: 14px;">Student ID</label>
            <input type="text" id="search_student_id" name="search_student_id"
                   style="width: 100%; padding: 10px 12px; border: 2px solid #e1e5e9; border-radius: 6px; font-size: 14px; transition: border-color 0.3s ease;"
                   value="{{ request('search_student_id') }}"
                   placeholder="Search by student ID">
          </div>
        </div>
        <div style="display: flex; gap: 12px; align-items: center;">
          <button type="submit" class="btn-primary" style="display: flex; align-items: center; gap: 8px; color: #ffffff !important;">
            <svg style="width: 18px; height: 18px; color: #ffffff !important;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
            </svg>
            <span style="color: #ffffff !important;">Search Accounts</span>
          </button>
          <a href="{{ route('admin.AddAccount', ['tab' => 'list']) }}" style="padding: 12px 20px; background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%); color: white; text-decoration: none; border-radius: 6px; font-weight: 600; display: flex; align-items: center; gap: 8px; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(231, 76, 60, 0.3); border: none; cursor: pointer;">
            <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
            </svg>
            Clear Filters
          </a>
          @if(request()->hasAny(['search_name', 'search_student_id']))
          <span style="color: var(--primary-green); font-size: 14px; font-weight: 600;">
            <svg style="width: 16px; height: 16px; vertical-align: middle; margin-right: 4px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
            </svg>
            Filters Active
          </span>
          @endif
        </div>
      </form>

      <!-- Academic Year Management & Student Promotion -->
      <div style="background: #e8f5e8; padding: 20px; border-radius: 8px; border-left: 4px solid var(--primary-green); margin-bottom: 24px;">
        <h4 style="margin: 0 0 16px 0; color: var(--primary-green); font-weight: 600; display: flex; align-items: center; gap: 8px;">
          <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443a55.381 55.381 0 0 1 5.25 2.882V15" />
          </svg>
          Academic Year & Student Promotion
        </h4>
        <p style="margin: 0 0 16px 0; color: #495057; font-size: 14px; line-height: 1.5;">
          Set the current academic year and trigger student promotion to automatically update year levels for eligible students.
        </p>
        <div style="display: grid; grid-template-columns: 1fr auto; gap: 16px; align-items: end;">
          <div>
            <label for="current_academic_year" style="display: block; margin-bottom: 8px; font-weight: 600; color: #333;">Academic Year (Format: 2024-2025):</label>
            <input
              type="text"
              id="current_academic_year"
              name="current_academic_year"
              placeholder="e.g., 2025-2026"
              pattern="[0-9]{4}-[0-9]{4}"
              title="Please enter academic year in format: YYYY-YYYY (e.g., 2025-2026)"
              style="width: 100%; padding: 12px 16px; border: 2px solid #e1e5e9; border-radius: 8px; font-size: 14px; transition: border-color 0.3s ease;"
              value="{{ old('current_academic_year', '2025-2026') }}" />
            <small style="color: #495057; font-size: 12px; margin-top: 4px; display: block;">
              This will trigger student promotion and update year levels system-wide.
            </small>
          </div>
          <button type="button" onclick="triggerStudentPromotion()" 
                  style="padding: 12px 24px; background: #28a745; color: white; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; transition: all 0.3s ease; display: flex; align-items: center; gap: 8px;">
            <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
            </svg>
            Trigger Promotion
          </button>
        </div>
        <div id="promotion-status" style="margin-top: 12px; padding: 8px 12px; border-radius: 4px; font-size: 14px; display: none;"></div>
      </div>

      <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
          <thead>
            <tr style="background: #f8f9fa; border-bottom: 2px solid #e9ecef;">
              <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057; font-size: 14px;">Full Name</th>
              <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057; font-size: 14px;">Student ID</th>
              <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057; font-size: 14px;">Email</th>
              <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057; font-size: 14px;">Department</th>
              <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057; font-size: 14px;">Course</th>
              <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057; font-size: 14px;">Year Level</th>
              <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057; font-size: 14px;">Account Type</th>
              <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057; font-size: 14px;">Status</th>
              <th style="padding: 16px; text-align: center; font-weight: 600; color: #495057; font-size: 14px;">Actions</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($students as $student)
            <tr style="border-bottom: 1px solid #e9ecef; transition: background-color 0.2s ease;"
                onmouseover="this.style.backgroundColor='#f8f9fa'"
                onmouseout="this.style.backgroundColor='transparent'">
              <td style="padding: 16px; color: #495057; font-size: 14px; font-weight: 500;">{{ $student->fullname }}</td>
              <td style="padding: 16px; color: #495057; font-size: 14px;">
                @if($student->student_id)
                  <span style="font-family: monospace; background: #e9ecef; padding: 2px 6px; border-radius: 4px; font-size: 13px;">{{ $student->student_id }}</span>
                @else
                  <span style="color: #6c757d; font-style: italic;">N/A</span>
                @endif
              </td>
              <td style="padding: 16px; color: #495057; font-size: 14px;">{{ $student->email }}</td>
              <td style="padding: 16px; color: #495057; font-size: 14px;">
                @php
                  $deptColor = '#e3f2fd'; // default light blue
                  $deptTextColor = '#1976d2'; // default text color
                  switch($student->department) {
                    case 'SASTE': $deptColor = '#e3f2fd'; $deptTextColor = '#1976d2'; break; // Light blue
                    case 'SBAHM': $deptColor = '#e8f5e8'; $deptTextColor = '#2e7d32'; break; // Light green
                    case 'SITE': $deptColor = '#f3e5f5'; $deptTextColor = '#7b1fa2'; break; // Light purple
                    case 'SNAHS': $deptColor = '#ffebee'; $deptTextColor = '#c62828'; break; // Light red
                    case 'SOM': $deptColor = '#fff3e0'; $deptTextColor = '#ef6c00'; break; // Light orange (for other departments)
                    case 'GRADSCH': $deptColor = '#f1f8e9'; $deptTextColor = '#558b2f'; break; // Light green variant
                    default: $deptColor = '#f5f5f5'; $deptTextColor = '#424242'; break; // Light gray for others
                  }
                @endphp
                <span style="display: inline-block; padding: 4px 8px; background: {{ $deptColor }}; color: {{ $deptTextColor }}; border-radius: 4px; font-size: 12px; font-weight: 500;">
                  {{ $student->department }}
                </span>
              </td>
              <td style="padding: 16px; color: #495057; font-size: 14px;">
                @if($student->course)
                  <span style="display: inline-block; padding: 4px 8px; background: #e8f5e8; color: #2e7d32; border-radius: 4px; font-size: 12px; font-weight: 500;">
                    {{ $student->course }}
                  </span>
                @else
                  <span style="color: #6c757d; font-style: italic; font-size: 12px;">Not set</span>
                @endif
              </td>
              <td style="padding: 16px; color: #495057; font-size: 14px;">
                @if($student->year_level)
                  <span style="display: inline-block; padding: 4px 8px; background: #f3e5f5; color: #7b1fa2; border-radius: 4px; font-size: 12px; font-weight: 500;">
                    {{ $student->year_level }}
                  </span>
                @else
                  <span style="color: #6c757d; font-style: italic; font-size: 12px;">Not set</span>
                @endif
              </td>
              <td style="padding: 16px; color: #495057; font-size: 14px;">
                @php
                  $bgColor = '#ffc107'; // default yellow
                  $icon = '';
                  switch($student->account_type) {
                    case 'admin': $bgColor = '#dc3545'; $icon = '<svg style="width: 14px; height: 14px; margin-right: 4px; color: #ffffff;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /></svg>'; break;
                    case 'dean': $bgColor = '#6f42c1'; $icon = '<svg style="width: 14px; height: 14px; margin-right: 4px; color: #ffffff;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5" /></svg>'; break;
                    case 'registrar': $bgColor = '#fd7e14'; $icon = '<svg style="width: 14px; height: 14px; margin-right: 4px; color: #ffffff;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" /></svg>'; break;
                    case 'sec_osa': $bgColor = '#20c997'; $icon = '<svg style="width: 14px; height: 14px; margin-right: 4px; color: #ffffff;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z" /></svg>'; break;
                    case 'prog_coor': $bgColor = '#17a2b8'; $icon = '<svg style="width: 14px; height: 14px; margin-right: 4px; color: #ffffff;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" /></svg>'; break;
                    case 'student': $bgColor = '#28a745'; $icon = '<svg style="width: 14px; height: 14px; margin-right: 4px; color: #ffffff;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5" /></svg>'; break;
                    case 'alumni': $bgColor = '#6610f2'; $icon = '<svg style="width: 14px; height: 14px; margin-right: 4px; color: #ffffff;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5" /></svg>'; break;
                  }
                @endphp
                <span style="display: inline-flex; align-items: center; padding: 6px 12px; background: {{ $bgColor }}; color: #ffffff !important; border-radius: 20px; font-size: 12px; font-weight: 500; text-transform: uppercase;">
                  {!! $icon !!}<span style="color: #ffffff !important;">{{ str_replace('_', ' ', $student->account_type) }}</span>
                </span>
              </td>
              <td style="padding: 16px; color: #495057; font-size: 14px;">
                @if($student->status == 1)
                  <span style="display: inline-flex; align-items: center; padding: 6px 12px; background: #28a745; color: #ffffff !important; border-radius: 20px; font-size: 12px; font-weight: 500;">
                    <svg style="width: 14px; height: 14px; margin-right: 4px; color: #ffffff;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    <span style="color: #ffffff !important;">Active</span>
                  </span>
                @else
                  <span style="display: inline-flex; align-items: center; padding: 6px 12px; background: #ffc107; color: #ffffff !important; border-radius: 20px; font-size: 12px; font-weight: 500;">
                    <svg style="width: 14px; height: 14px; margin-right: 4px; color: #ffffff;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    <span style="color: #ffffff !important;">Pending</span>
                  </span>
                @endif
              </td>
              <td style="padding: 16px; text-align: center;">
                <div style="display: flex; gap: 8px; justify-content: center; align-items: center;">
                  <!-- Edit Button -->
                  <button onclick="editAccount({{ $student->id }})"
                          style="padding: 8px 14px; background: var(--primary-green); color: #ffffff !important; border: none; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 6px; transition: all 0.3s ease; box-shadow: 0 2px 8px rgba(0, 176, 80, 0.3);"
                          onmouseover="this.style.background='#0b5d1e'; this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 12px rgba(0, 176, 80, 0.4)'; this.style.color='#ffffff'"
                          onmouseout="this.style.background='var(--primary-green)'; this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 8px rgba(0, 176, 80, 0.3)'; this.style.color='#ffffff'"
                          title="Edit Account">
                    <svg style="width: 16px; height: 16px; color: #ffffff;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                    </svg>
                    <span style="color: #ffffff !important;">Edit</span>
                  </button>

                  <!-- Delete Button -->
                  <button onclick="deleteAccount({{ $student->id }}, '{{ $student->fullname }}')"
                          style="padding: 8px 14px; background: #dc3545; color: #ffffff !important; border: none; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 6px; transition: all 0.3s ease; box-shadow: 0 2px 8px rgba(220, 53, 69, 0.3);"
                          onmouseover="this.style.background='#c82333'; this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 12px rgba(220, 53, 69, 0.4)'; this.style.color='#ffffff'"
                          onmouseout="this.style.background='#dc3545'; this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 8px rgba(220, 53, 69, 0.3)'; this.style.color='#ffffff'"
                          title="Delete Account">
                    <svg style="width: 16px; height: 16px; color: #ffffff;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                    </svg>
                    <span style="color: #ffffff !important;">Delete</span>
                  </button>
                </div>
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="9" style="padding: 40px; text-align: center; color: #6c757d;">
                <div style="display: flex; flex-direction: column; align-items: center; gap: 16px;">
                  <svg style="width: 48px; height: 48px; color: #dee2e6;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414a1 1 0 00-.707-.293H8"></path>
                  </svg>
                  <div>
                    @if(request()->hasAny(['search_name', 'search_student_id', 'search_email', 'search_department', 'search_account_type', 'search_status']))
                      <h4 style="margin: 0 0 8px 0; color: #495057; font-size: 18px;">No accounts found</h4>
                      <p style="margin: 0; font-size: 14px;">Try adjusting your search criteria or <a href="{{ route('admin.AddAccount') }}" style="color: var(--primary-green); text-decoration: none; font-weight: 600;">clear all filters</a></p>
                    @else
                      <h4 style="margin: 0 0 8px 0; color: #495057; font-size: 18px;">No accounts available</h4>
                      <p style="margin: 0; font-size: 14px;">Start by creating new user accounts using the tabs above</p>
                    @endif
                  </div>
                </div>
              </td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      @if($students->hasPages())
      <div style="margin-top: 24px; display: flex; justify-content: center;">
        {{ $students->appends(['tab' => 'list'])->appends(request()->query())->links() }}
      </div>
      @endif
    </div>
  </div>

  <!-- Edit Account Modal -->
  <div id="editAccountModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; justify-content: center; align-items: center;">
    <div style="background: white; border-radius: 12px; padding: 24px; width: 90%; max-width: 600px; max-height: 90vh; overflow-y: auto; box-shadow: 0 10px 30px rgba(0,0,0,0.3);">
      <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; border-bottom: 2px solid #e9ecef; padding-bottom: 16px;">
        <h3 style="margin: 0; color: var(--primary-green); font-size: 1.5rem; font-weight: 600;">Edit Account</h3>
        <button onclick="closeEditModal()" style="background: none; border: none; color: #6c757d; cursor: pointer; padding: 8px; border-radius: 6px; transition: all 0.3s ease; display: flex; align-items: center; justify-content: center;"
                onmouseover="this.style.background='#f8f9fa'; this.style.color='#dc3545'"
                onmouseout="this.style.background='none'; this.style.color='#6c757d'" title="Close">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 20px; height: 20px;">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
          </svg>
        </button>
      </div>

      <form id="editAccountForm" method="POST" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
        @csrf
        @method('PUT')

        <div>
          <label for="edit_fullname" style="display: block; margin-bottom: 8px; font-weight: 600; color: #333;">Full Name *</label>
          <input type="text" id="edit_fullname" name="fullname" required
                 style="width: 100%; padding: 12px 16px; border: 2px solid #e1e5e9; border-radius: 8px; font-size: 14px; transition: border-color 0.3s ease;"
                 onfocus="this.style.borderColor='var(--primary-green)'"
                 onblur="this.style.borderColor='#e1e5e9'">
        </div>

        <div>
          <label for="edit_student_id" style="display: block; margin-bottom: 8px; font-weight: 600; color: #333;">Student ID</label>
          <input type="text" id="edit_student_id" name="student_id"
                 style="width: 100%; padding: 12px 16px; border: 2px solid #e1e5e9; border-radius: 8px; font-size: 14px; transition: border-color 0.3s ease;"
                 onfocus="this.style.borderColor='var(--primary-green)'"
                 onblur="this.style.borderColor='#e1e5e9'">
        </div>

        <div>
          <label for="edit_email" style="display: block; margin-bottom: 8px; font-weight: 600; color: #333;">Email *</label>
          <input type="email" id="edit_email" name="email" required
                 style="width: 100%; padding: 12px 16px; border: 2px solid #e1e5e9; border-radius: 8px; font-size: 14px; transition: border-color 0.3s ease;"
                 onfocus="this.style.borderColor='var(--primary-green)'"
                 onblur="this.style.borderColor='#e1e5e9'">
        </div>

        <div>
          <label for="edit_department" style="display: block; margin-bottom: 8px; font-weight: 600; color: #333;">Department *</label>
          <select id="edit_department" name="department" required
                  style="width: 100%; padding: 12px 16px; border: 2px solid #e1e5e9; border-radius: 8px; font-size: 14px; transition: border-color 0.3s ease;"
                  onfocus="this.style.borderColor='var(--primary-green)'"
                  onblur="this.style.borderColor='#e1e5e9'">
            <option value="">Select Department</option>
            <option value="SITE">SITE</option>
            <option value="SASTE">SASTE</option>
            <option value="SBAHM">SBAHM</option>
            <option value="SNAHS">SNAHS</option>
            <option value="SOM">SOM</option>
            <option value="GRADSCH">GRADSCH</option>
          </select>
        </div>

        <div>
          <label for="edit_course" style="display: block; margin-bottom: 8px; font-weight: 600; color: #333;">Course</label>
          <select id="edit_course" name="course"
                  style="width: 100%; padding: 12px 16px; border: 2px solid #e1e5e9; border-radius: 8px; font-size: 14px; transition: border-color 0.3s ease;"
                  onfocus="this.style.borderColor='var(--primary-green)'"
                  onblur="this.style.borderColor='#e1e5e9'">
            <option value="">Select Course</option>
            <!-- Course options will be populated by JavaScript based on department -->
          </select>
        </div>

        <div>
          <label for="edit_year_level" style="display: block; margin-bottom: 8px; font-weight: 600; color: #333;">Year Level</label>
          <select id="edit_year_level" name="year_level"
                  style="width: 100%; padding: 12px 16px; border: 2px solid #e1e5e9; border-radius: 8px; font-size: 14px; transition: border-color 0.3s ease;"
                  onfocus="this.style.borderColor='var(--primary-green)'"
                  onblur="this.style.borderColor='#e1e5e9'">
            <option value="">Select Year Level</option>
            <option value="1st Year">1st Year</option>
            <option value="2nd Year">2nd Year</option>
            <option value="3rd Year">3rd Year</option>
            <option value="4th Year">4th Year</option>
            <option value="5th Year">5th Year</option>
            <option value="Graduate">Graduate</option>
          </select>
        </div>

        <div>
          <label for="edit_account_type" style="display: block; margin-bottom: 8px; font-weight: 600; color: #333;">Account Type *</label>
          <select id="edit_account_type" name="account_type" required
                  style="width: 100%; padding: 12px 16px; border: 2px solid #e1e5e9; border-radius: 8px; font-size: 14px; transition: border-color 0.3s ease;"
                  onfocus="this.style.borderColor='var(--primary-green)'"
                  onblur="this.style.borderColor='#e1e5e9'">
            <option value="">Select Account Type</option>
            <option value="admin">Administrator</option>
            <option value="dean">Dean</option>
            <option value="registrar">Registrar</option>
            <option value="sec_osa">Moderator</option>
            <option value="prog_coor">Program Coordinator</option>
            <option value="psg_officer">PSG Officer</option>
            <option value="student">Student</option>
            <option value="alumni">Alumni</option>
          </select>
        </div>

        <div>
          <label for="edit_status" style="display: block; margin-bottom: 8px; font-weight: 600; color: #333;">Status *</label>
          <select id="edit_status" name="status" required
                  style="width: 100%; padding: 12px 16px; border: 2px solid #e1e5e9; border-radius: 8px; font-size: 14px; transition: border-color 0.3s ease;"
                  onfocus="this.style.borderColor='var(--primary-green)'"
                  onblur="this.style.borderColor='#e1e5e9'">
            <option value="1">Active</option>
            <option value="0">Pending</option>
          </select>
        </div>

        <div style="grid-column: 1 / -1; margin-top: 24px; display: flex; gap: 12px; justify-content: flex-end;">
          <button type="button" onclick="closeEditModal()" class="btn-secondary" style="display: flex; align-items: center; gap: 8px;">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 16px; height: 16px;">
              <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
            </svg>
            Cancel
          </button>
          <button type="submit" class="btn-primary" style="color: #ffffff !important; display: flex; align-items: center; gap: 8px;">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 16px; height: 16px;">
              <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
            </svg>
            Save Changes
          </button>
            <span style="color: #ffffff !important;">Update Account</span>
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- Student Promotion Confirmation Modal Styles -->
  <style>
    @media (max-width: 640px) {
      #promotionModal .modal-content {
        width: 95% !important;
        max-width: none !important;
        margin: 10px !important;
        border-radius: 12px !important;
      }
      #promotionModal .modal-header {
        padding: 20px 16px 12px !important;
      }
      #promotionModal .modal-body {
        padding: 16px !important;
      }
      #promotionModal .modal-buttons {
        flex-direction: column !important;
        gap: 8px !important;
      }
      #promotionModal .modal-buttons button {
        width: 100% !important;
        justify-content: center !important;
      }
    }
  </style>

  <!-- Student Promotion Confirmation Modal -->
  <div id="promotionModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); z-index: 10000; backdrop-filter: blur(2px);">
    <div class="modal-content" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; border-radius: 16px; box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15); max-width: 500px; width: 90%; max-height: 90vh; overflow-y: auto; margin: 20px;">
      
      <!-- Modal Header -->
      <div class="modal-header" style="padding: 24px 24px 16px; border-bottom: 1px solid #e5e7eb;">
        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 8px;">
          <div style="width: 40px; height: 40px; background: linear-gradient(135deg, #22c55e, #16a34a); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
            <svg style="width: 20px; height: 20px; color: white;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
            </svg>
          </div>
          <h3 style="margin: 0; font-size: 20px; font-weight: 700; color: #111827;">Confirm Student Promotion</h3>
        </div>
      </div>

      <!-- Modal Body -->
      <div class="modal-body" style="padding: 24px;">
        <div style="margin-bottom: 20px;">
          <p style="margin: 0 0 16px; font-size: 16px; color: #374151; line-height: 1.5;">
            Are you sure you want to trigger student promotion for Academic Year <strong id="modalAcademicYear">2025–2026</strong>?
          </p>
        </div>

        <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 20px; margin-bottom: 20px;">
          <h4 style="margin: 0 0 12px; font-size: 14px; font-weight: 600; color: #475569;">This action will:</h4>
          <ul style="margin: 0; padding-left: 20px; list-style: none;">
            <li style="margin-bottom: 8px; color: #64748b; font-size: 14px; display: flex; align-items: flex-start; gap: 8px;">
              <span style="color: #22c55e; font-weight: bold;">•</span>
              <span>Set the academic year as current</span>
            </li>
            <li style="margin-bottom: 8px; color: #64748b; font-size: 14px; display: flex; align-items: flex-start; gap: 8px;">
              <span style="color: #22c55e; font-weight: bold;">•</span>
              <span>Automatically promote eligible students to the next year level</span>
            </li>
            <li style="margin-bottom: 0; color: #64748b; font-size: 14px; display: flex; align-items: flex-start; gap: 8px;">
              <span style="color: #22c55e; font-weight: bold;">•</span>
              <span>Update student records system-wide</span>
            </li>
          </ul>
        </div>

        <div style="background: #fef3cd; border: 1px solid #fbbf24; border-radius: 8px; padding: 12px; margin-bottom: 24px; display: flex; align-items: flex-start; gap: 8px;">
          <svg style="width: 20px; height: 20px; color: #f59e0b; flex-shrink: 0; margin-top: 1px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
          </svg>
          <div>
            <p style="margin: 0; font-size: 14px; font-weight: 600; color: #92400e;">Note:</p>
            <p style="margin: 2px 0 0; font-size: 13px; color: #92400e;">This action cannot be undone.</p>
          </div>
        </div>

        <!-- Action Buttons -->
        <div class="modal-buttons" style="display: flex; gap: 12px; justify-content: flex-end;">
          <button type="button" onclick="closePromotionModal()" 
                  style="padding: 12px 24px; background: #f8fafc; color: #475569; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px; font-weight: 500; cursor: pointer; transition: all 0.2s ease; display: flex; align-items: center;"
                  onmouseover="this.style.background='#f1f5f9'; this.style.borderColor='#cbd5e1'"
                  onmouseout="this.style.background='#f8fafc'; this.style.borderColor='#e2e8f0'">
            Cancel
          </button>
          <button type="button" onclick="confirmStudentPromotion()" 
                  style="padding: 12px 24px; background: linear-gradient(135deg, #22c55e, #16a34a); color: white; border: none; border-radius: 8px; font-size: 14px; font-weight: 600; cursor: pointer; transition: all 0.2s ease; box-shadow: 0 2px 4px rgba(34, 197, 94, 0.2); display: flex; align-items: center;"
                  onmouseover="this.style.background='linear-gradient(135deg, #16a34a, #15803d)'; this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 8px rgba(34, 197, 94, 0.3)'"
                  onmouseout="this.style.background='linear-gradient(135deg, #22c55e, #16a34a)'; this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 4px rgba(34, 197, 94, 0.2)'">
            <svg style="width: 16px; height: 16px; margin-right: 6px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            Confirm
          </button>
        </div>
      </div>
    </div>
  </div>

  <script>
    // Course data by department
    const coursesByDepartment = {
      'SITE': ['BSIT', 'BLIS', 'BS ENSE', 'BS CpE', 'BSCE'],
      'SBAHM': ['BSA', 'BSE', 'BSBAMM', 'BSBA MFM', 'BSBA MOP', 'BSMA', 'BSHM', 'BSTM', 'BSPDMI'],
      'SASTE': ['BAELS', 'BS Psych', 'BS Bio', 'BSSW', 'BSPA', 'BS Bio MB', 'BSEd', 'BEEd', 'BPEd'],
      'SNAHS': ['BSN', 'BSPh', 'BSMT', 'BSPT', 'BSRT'],
      'SOM': ['MD', 'BS Med'],
      'GRADSCH': ['MBA', 'MPA', 'MEd', 'MS', 'MA', 'PhD', 'EdD']
    };

    function populateCourseDropdown(department, selectedCourse = '') {
      const courseSelect = document.getElementById('edit_course');
      courseSelect.innerHTML = '<option value="">Select Course</option>';

      if (department && coursesByDepartment[department]) {
        coursesByDepartment[department].forEach(course => {
          const option = document.createElement('option');
          option.value = course;
          option.textContent = course;
          if (course === selectedCourse) {
            option.selected = true;
          }
          courseSelect.appendChild(option);
        });
      }
    }

    // Add event listener for department change
    document.addEventListener('DOMContentLoaded', function() {
      const departmentSelect = document.getElementById('edit_department');
      if (departmentSelect) {
        departmentSelect.addEventListener('change', function() {
          populateCourseDropdown(this.value);
        });
      }
    });

    function showTab(tabName) {
      // Hide all tab contents
      document.getElementById('manual-content').style.display = 'none';
      document.getElementById('import-content').style.display = 'none';
      document.getElementById('list-content').style.display = 'none';

      // Reset all tab buttons
      document.getElementById('manual-tab').style.borderBottomColor = 'transparent';
      document.getElementById('manual-tab').style.color = '#6c757d';
      document.getElementById('import-tab').style.borderBottomColor = 'transparent';
      document.getElementById('import-tab').style.color = '#6c757d';
      document.getElementById('list-tab').style.borderBottomColor = 'transparent';
      document.getElementById('list-tab').style.color = '#6c757d';

      // Show selected tab content
      document.getElementById(tabName + '-content').style.display = 'block';

      // Activate selected tab button
      document.getElementById(tabName + '-tab').style.borderBottomColor = 'var(--primary-green)';
      document.getElementById(tabName + '-tab').style.color = 'var(--primary-green)';
      
      // Update URL to maintain tab state (without page refresh)
      const url = new URL(window.location);
      if (tabName !== 'manual') {
        url.searchParams.set('tab', tabName);
      } else {
        url.searchParams.delete('tab');
      }
      window.history.replaceState({}, '', url);
    }

    // Initialize with appropriate tab active
    document.addEventListener('DOMContentLoaded', function() {
      const urlParams = new URLSearchParams(window.location.search);
      
      // Check if tab is specified in URL
      const tabParam = urlParams.get('tab');
      
      // Check if there are search parameters
      const hasSearchParams = urlParams.has('search_name') || urlParams.has('search_student_id');

      // Check if there are success/error messages (indicating an edit/delete operation)
      const hasMessages = document.querySelector('[style*="background: #d4edda"]') ||
                         document.querySelector('[style*="background: #f8d7da"]') ||
                         document.querySelector('[style*="background: #fff3cd"]');

      // Determine which tab to show
      if (tabParam === 'list' || hasSearchParams || hasMessages) {
        showTab('list');
      } else if (tabParam === 'import') {
        showTab('import');
      } else {
        showTab('manual');
      }
    });

    // Edit Account Functions
    function editAccount(accountId) {
      // Fetch account data
      fetch(`/admin/account/${accountId}/edit`)
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            // Populate form fields
            document.getElementById('edit_fullname').value = data.account.fullname || '';
            document.getElementById('edit_student_id').value = data.account.student_id || '';
            document.getElementById('edit_email').value = data.account.email || '';
            document.getElementById('edit_department').value = data.account.department || '';
            document.getElementById('edit_course').value = data.account.course || '';
            document.getElementById('edit_year_level').value = data.account.year_level || '';
            document.getElementById('edit_account_type').value = data.account.account_type || '';
            document.getElementById('edit_status').value = data.account.status || '';

            // Populate course dropdown based on department
            populateCourseDropdown(data.account.department, data.account.course);

            // Set form action
            document.getElementById('editAccountForm').action = `/admin/account/${accountId}/update`;

            // Show modal
            document.getElementById('editAccountModal').style.display = 'flex';
          } else {
            alert('Error loading account data: ' + data.message);
          }
        })
        .catch(error => {
          console.error('Error:', error);
          alert('Error loading account data. Please try again.');
        });
    }

    function closeEditModal() {
      document.getElementById('editAccountModal').style.display = 'none';
    }

    function deleteAccount(accountId, accountName) {
      // Store the data for later use
      pendingDeleteId = accountId;
      pendingDeleteName = accountName;
      
      // Update modal content
      document.getElementById('deleteAccountName').textContent = accountName;
      
      // Show modal
      document.getElementById('deleteConfirmModal').style.display = 'block';
      document.body.style.overflow = 'hidden'; // Prevent background scrolling
    }

    function closeDeleteModal() {
      document.getElementById('deleteConfirmModal').style.display = 'none';
      document.body.style.overflow = 'auto'; // Restore scrolling
      pendingDeleteId = null;
      pendingDeleteName = null;
    }

    function confirmDelete() {
      if (!pendingDeleteId) return;
      
      const confirmBtn = document.getElementById('confirmDeleteBtn');
      const originalText = confirmBtn.textContent;
      
      // Show loading state
      confirmBtn.disabled = true;
      confirmBtn.innerHTML = '<svg style="width: 16px; height: 16px; animation: spin 1s linear infinite; margin-right: 8px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>Deleting...';
      
      // Create a form to submit DELETE request
      const form = document.createElement('form');
      form.method = 'POST';
      form.action = `/admin/account/${pendingDeleteId}/delete`;

      // Add CSRF token
      const csrfToken = document.createElement('input');
      csrfToken.type = 'hidden';
      csrfToken.name = '_token';
      csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
      form.appendChild(csrfToken);

      // Add DELETE method
      const methodField = document.createElement('input');
      methodField.type = 'hidden';
      methodField.name = '_method';
      methodField.value = 'DELETE';
      form.appendChild(methodField);

      // Submit form
      document.body.appendChild(form);
      form.submit();
      
      // Close modal
      closeDeleteModal();
    }

    // Close modals when clicking outside
    document.getElementById('editAccountModal').addEventListener('click', function(e) {
      if (e.target === this) {
        closeEditModal();
      }
    });

    document.getElementById('deleteConfirmModal').addEventListener('click', function(e) {
      if (e.target === this) {
        closeDeleteModal();
      }
    });

    // Close modals with Escape key
    document.addEventListener('keydown', function(event) {
      if (event.key === 'Escape') {
        closeEditModal();
        closeDeleteModal();
      }
    });

    // Add search functionality
    function clearSearch() {
      // Clear all search fields
      document.getElementById('search_name').value = '';
      document.getElementById('search_student_id').value = '';
      document.getElementById('search_email').value = '';
      document.getElementById('search_department').value = '';
      document.getElementById('search_account_type').value = '';
      document.getElementById('search_status').value = '';

      // Redirect to clear URL
      window.location.href = '{{ route("admin.AddAccount") }}';
    }

    // Add Enter key support for search fields
    document.addEventListener('DOMContentLoaded', function() {
      const searchFields = ['search_name', 'search_student_id', 'search_email'];
      searchFields.forEach(fieldId => {
        const field = document.getElementById(fieldId);
        if (field) {
          field.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
              e.preventDefault();
              this.closest('form').submit();
            }
          });
        }
      });
    });

    // Academic Year Management & Student Promotion Function
    function triggerStudentPromotion() {
      const input = document.getElementById('current_academic_year');
      const statusDiv = document.getElementById('promotion-status');
      const academicYear = input.value.trim();

      // Validate format
      const yearPattern = /^[0-9]{4}-[0-9]{4}$/;
      if (!academicYear || !yearPattern.test(academicYear)) {
        statusDiv.style.display = 'block';
        statusDiv.style.background = '#f8d7da';
        statusDiv.style.color = '#721c24';
        statusDiv.style.border = '1px solid #f5c6cb';
        statusDiv.innerHTML = '⚠️ Please enter a valid academic year format (e.g., 2025-2026)';
        return;
      }

      // Check year logic
      const years = academicYear.split('-');
      const startYear = parseInt(years[0]);
      const endYear = parseInt(years[1]);
      
      if (endYear !== startYear + 1) {
        statusDiv.style.display = 'block';
        statusDiv.style.background = '#f8d7da';
        statusDiv.style.color = '#721c24';
        statusDiv.style.border = '1px solid #f5c6cb';
        statusDiv.innerHTML = '⚠️ End year should be one year after start year (e.g., 2025-2026)';
        return;
      }

      // Show confirmation modal
      document.getElementById('modalAcademicYear').textContent = academicYear;
      document.getElementById('promotionModal').style.display = 'block';
      document.body.style.overflow = 'hidden'; // Prevent background scrolling
    }

    // Modal control functions
    function closePromotionModal() {
      document.getElementById('promotionModal').style.display = 'none';
      document.body.style.overflow = 'auto'; // Restore scrolling
    }

    // Close modal when clicking outside of it
    document.getElementById('promotionModal').addEventListener('click', function(e) {
      if (e.target === this) {
        closePromotionModal();
      }
    });

    // Close modal with ESC key
    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape' && document.getElementById('promotionModal').style.display === 'block') {
        closePromotionModal();
      }
    });

    function confirmStudentPromotion() {
      const input = document.getElementById('current_academic_year');
      const statusDiv = document.getElementById('promotion-status');
      const academicYear = input.value.trim();
      
      // Close modal
      closePromotionModal();

      // Send to server
      const promoteBtn = document.querySelector('button[onclick="triggerStudentPromotion()"]');
      const originalText = promoteBtn.innerHTML;
      
      promoteBtn.disabled = true;
      promoteBtn.innerHTML = '<svg style="width: 16px; height: 16px; animation: spin 1s linear infinite;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg> Processing...';

      fetch('/admin/trigger-student-promotion', {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({
          academic_year: academicYear
        })
      })
      .then(response => response.json())
      .then(data => {
        statusDiv.style.display = 'block';
        if (data.success) {
          statusDiv.style.background = '#d4edda';
          statusDiv.style.color = '#155724';
          statusDiv.style.border = '1px solid #c3e6cb';
          statusDiv.innerHTML = '✅ ' + data.message + ' Students promoted: ' + (data.promoted_count || 0);
        } else {
          statusDiv.style.background = '#f8d7da';
          statusDiv.style.color = '#721c24';
          statusDiv.style.border = '1px solid #f5c6cb';
          statusDiv.innerHTML = '❌ Error: ' + (data.message || 'Failed to trigger student promotion');
        }
      })
      .catch(error => {
        console.error('Error:', error);
        statusDiv.style.display = 'block';
        statusDiv.style.background = '#f8d7da';
        statusDiv.style.color = '#721c24';
        statusDiv.style.border = '1px solid #f5c6cb';
        statusDiv.innerHTML = '❌ An error occurred while triggering student promotion.';
      })
      .finally(() => {
        promoteBtn.disabled = false;
        promoteBtn.innerHTML = originalText;
      });
    }
  </script>

  <!-- Custom Delete Confirmation Modal -->
  <div id="deleteConfirmModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); z-index: 10000; backdrop-filter: blur(4px);">
    <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; border-radius: 16px; box-shadow: 0 25px 50px rgba(0, 0, 0, 0.25); min-width: 400px; max-width: 500px; animation: modalSlideIn 0.3s ease;">
      <div style="padding: 24px 24px 0 24px; text-align: center;">
        <!-- Warning Icon -->
        <div style="width: 64px; height: 64px; background: linear-gradient(135deg, #ff6b6b, #ee5a52); border-radius: 50%; margin: 0 auto 20px; display: flex; align-items: center; justify-content: center; animation: pulse 2s infinite;">
          <svg style="width: 32px; height: 32px; color: white;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M3 12a9 9 0 1118 0 9 9 0 01-18 0z"></path>
          </svg>
        </div>
        
        <h3 style="margin: 0 0 12px 0; color: #2d3748; font-size: 20px; font-weight: 700;">Confirm Account Deletion</h3>
        
        <p style="margin: 0 0 8px 0; color: #4a5568; font-size: 16px; line-height: 1.5;">
          Are you sure you want to delete the account for:
        </p>
        
        <p id="deleteAccountName" style="margin: 0 0 16px 0; color: #2d3748; font-size: 18px; font-weight: 600; background: #f7fafc; padding: 12px; border-radius: 8px; border-left: 4px solid #ff6b6b;"></p>
        
        <div style="background: #fff5f5; padding: 16px; border-radius: 8px; border: 1px solid #fed7d7; margin-bottom: 24px;">
          <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px;">
            <svg style="width: 16px; height: 16px; color: #e53e3e;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01"></path>
            </svg>
            <strong style="color: #c53030; font-size: 14px;">Warning:</strong>
          </div>
          <p style="margin: 0; color: #c53030; font-size: 14px; line-height: 1.4;">
            This action cannot be undone. All data associated with this account will be permanently deleted.
          </p>
        </div>
      </div>
      
      <div style="display: flex; gap: 12px; padding: 0 24px 24px 24px;">
        <button onclick="closeDeleteModal()" style="flex: 1; padding: 12px 20px; background: #e2e8f0; color: #2d3748; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; transition: all 0.3s ease;">
          Cancel
        </button>
        <button id="confirmDeleteBtn" onclick="confirmDelete()" style="flex: 1; padding: 12px 20px; background: linear-gradient(135deg, #ff6b6b, #ee5a52); color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(255, 107, 107, 0.3);">
          Delete Account
        </button>
      </div>
    </div>
  </div>

  <style>
    @keyframes spin {
      from {
        transform: rotate(0deg);
      }
      to {
        transform: rotate(360deg);
      }
    }
    
    @keyframes modalSlideIn {
      from {
        transform: translate(-50%, -60%);
        opacity: 0;
      }
      to {
        transform: translate(-50%, -50%);
        opacity: 1;
      }
    }
    
    @keyframes pulse {
      0%, 100% {
        transform: scale(1);
      }
      50% {
        transform: scale(1.05);
      }
    }
    
    /* Enhanced button hover effects */
    a[href*="admin.AddAccount"]:hover {
      transform: translateY(-2px) !important;
      box-shadow: 0 6px 25px rgba(231, 76, 60, 0.4) !important;
      background: linear-gradient(135deg, #c0392b 0%, #e74c3c 100%) !important;
    }
    
    #confirmDeleteBtn:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 25px rgba(255, 107, 107, 0.4);
      background: linear-gradient(135deg, #ee5a52, #ff6b6b);
    }
    
    button[onclick="closeDeleteModal()"]:hover {
      background: #cbd5e0;
      transform: translateY(-1px);
    }
    
    /* Enhance existing edit and delete buttons */
    button[onclick^="editAccount"]:hover {
      transform: translateY(-2px) !important;
      box-shadow: 0 6px 20px rgba(59, 130, 246, 0.4) !important;
    }
    
    button[onclick^="deleteAccount"]:hover {
      transform: translateY(-2px) !important;
      box-shadow: 0 6px 20px rgba(239, 68, 68, 0.4) !important;
      background: linear-gradient(135deg, #dc2626, #ef4444) !important;
    }
  </style>

  <script>
    let pendingDeleteId = null;
    let pendingDeleteName = null;

    /**
     * Toggle visibility of student-specific fields based on account type
     */
    function toggleStudentFields() {
      const accountType = document.getElementById('account_type').value;
      const studentIdField = document.getElementById('student_id_field');
      const courseField = document.getElementById('course_field');
      const yearLevelField = document.getElementById('year_level_field');

      const studentIdInput = document.getElementById('student_id');
      const courseInput = document.getElementById('course');
      const yearLevelInput = document.getElementById('year_level');

      // Show/hide fields based on account type
      if (accountType === 'student' || accountType === 'alumni') {
        // Show student fields
        studentIdField.style.display = 'block';
        courseField.style.display = 'block';
        yearLevelField.style.display = 'block';

        // Make fields required
        studentIdInput.setAttribute('required', 'required');
        courseInput.setAttribute('required', 'required');
        yearLevelInput.setAttribute('required', 'required');
      } else {
        // Hide student fields
        studentIdField.style.display = 'none';
        courseField.style.display = 'none';
        yearLevelField.style.display = 'none';

        // Remove required attribute
        studentIdInput.removeAttribute('required');
        courseInput.removeAttribute('required');
        yearLevelInput.removeAttribute('required');

        // Clear values
        studentIdInput.value = '';
        courseInput.value = '';
        yearLevelInput.value = '';
      }
    }

    // Initialize field visibility on page load
    document.addEventListener('DOMContentLoaded', function() {
      toggleStudentFields();
    });
  </script>
</x-dashboard-layout>