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

  @include('shared.alerts.flash')

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
        View Accounts ({{ $students->total() + $adminAccounts->total() }})
      </button>
    </div>
  </div>

  <!-- Main Content -->
  <div class="header-section">
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
        <div id="department_field" x-data="{ showOther: false }">
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

        <!-- Course (filtered by department) -->
        <div id="course_field">
          <label for="course_id" style="display: block; margin-bottom: 8px; font-weight: 600; color: #333;">Course <span style="color: #dc3545;">*</span></label>
          <select
            id="course_id"
            name="course_id"
            disabled
            style="width: 100%; padding: 12px 16px; border: 2px solid #e1e5e9; border-radius: 8px; font-size: 14px; transition: border-color 0.3s ease; background: white;">
            <option value="">Select a department first</option>
          </select>
          <x-input-error :messages="$errors->get('course_id')" class="mt-1" />
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

        <!-- PSG Organization -->
        <div id="organization_field" style="display: none;">
          <label for="organization" style="display: block; margin-bottom: 8px; font-weight: 600; color: #333;">Organization <span style="color: #dc3545;">*</span></label>
          <select
            id="organization"
            name="organization"
            style="width: 100%; padding: 12px 16px; border: 2px solid #e1e5e9; border-radius: 8px; font-size: 14px; transition: border-color 0.3s ease; background: white;">
            <option value="">Select Organization</option>
            @foreach ($organizations as $org)
              <option value="{{ $org->description }}" {{ old('organization') == $org->description ? 'selected' : '' }}>{{ $org->description }}</option>
            @endforeach
          </select>
          <x-input-error :messages="$errors->get('organization')" class="mt-1" />
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
    <div id="list-content" style="background: white; border-radius: 0 0 12px 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); display: none;">

      @php
        $roleColors = [
          'admin'      => '#dc3545',
          'dean'       => '#6f42c1',
          'registrar'  => '#fd7e14',
          'sec_osa'    => '#20c997',
          'prog_coor'  => '#17a2b8',
          'alumni'     => '#6610f2',
          'psg_officer'=> '#e83e8c',
        ];
        $roleLabels = [
          'admin'      => 'Admin',
          'dean'       => 'Dean',
          'registrar'  => 'Registrar',
          'sec_osa'    => 'Moderator',
          'prog_coor'  => 'Prog. Coordinator',
          'alumni'     => 'Alumni',
          'psg_officer'=> 'PSG Officer',
        ];
      @endphp

      <!-- Sub-tab Navigation -->
      <div style="display: flex; border-bottom: 2px solid #e9ecef; padding: 0 24px;">
        <button onclick="showAccountSubTab('students')" id="subtab-students"
                style="padding: 14px 20px; background: none; border: none; font-size: 14px; font-weight: 600; border-bottom: 3px solid var(--primary-green); color: var(--primary-green); cursor: pointer; margin-bottom: -2px; display: flex; align-items: center; gap: 8px; transition: all 0.2s;">
          <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5"/>
          </svg>
          Students
          <span style="padding: 2px 8px; background: #e8f5e9; color: var(--primary-green); border-radius: 10px; font-size: 11px; font-weight: 700;">{{ $students->total() }}</span>
        </button>
        <button onclick="showAccountSubTab('admin')" id="subtab-admin"
                style="padding: 14px 20px; background: none; border: none; font-size: 14px; font-weight: 600; border-bottom: 3px solid transparent; color: #6c757d; cursor: pointer; margin-bottom: -2px; display: flex; align-items: center; gap: 8px; transition: all 0.2s;">
          <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
          </svg>
          Administrative Accounts
          <span style="padding: 2px 8px; background: #e9ecef; color: #495057; border-radius: 10px; font-size: 11px; font-weight: 700;">{{ $adminAccounts->total() }}</span>
        </button>
      </div>

      <!-- Students Search Form -->
      <div id="filter-students" style="padding: 16px 24px 0;">
        <form method="GET" action="{{ route('admin.AddAccount') }}" style="margin-bottom: 16px;">
          <input type="hidden" name="tab" value="list">
          <input type="hidden" name="subtab" value="students">
          <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
            <input type="text" id="search_name" name="search_name"
                   style="flex: 1; min-width: 160px; padding: 10px 12px; border: 2px solid #e1e5e9; border-radius: 6px; font-size: 14px;"
                   value="{{ request('subtab', 'students') === 'students' ? request('search_name') : '' }}"
                   placeholder="Search by name">
            <input type="text" id="search_student_id" name="search_student_id"
                   style="flex: 1; min-width: 140px; padding: 10px 12px; border: 2px solid #e1e5e9; border-radius: 6px; font-size: 14px;"
                   value="{{ request('subtab', 'students') === 'students' ? request('search_student_id') : '' }}"
                   placeholder="Student ID">
            <button type="submit" style="padding: 10px 16px; background: var(--primary-green); color: #fff !important; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 6px; white-space: nowrap;">
              <svg style="width: 16px; height: 16px;" fill="none" stroke="white" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
              </svg>
              <span style="color: #fff !important;">Search</span>
            </button>
            <a href="{{ route('admin.AddAccount', ['tab' => 'list', 'subtab' => 'students']) }}" style="padding: 10px 16px; background: #e74c3c; color: white; text-decoration: none; border-radius: 6px; font-weight: 600; display: flex; align-items: center; gap: 6px; white-space: nowrap;">
              <svg style="width: 16px; height: 16px;" fill="none" stroke="white" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
              </svg>
              Clear
            </a>
            @if(request('subtab', 'students') === 'students' && request()->hasAny(['search_name', 'search_student_id']))
            <span style="color: var(--primary-green); font-size: 13px; font-weight: 600;">Filters Active</span>
            @endif
          </div>
        </form>
      </div>

      <!-- Administrative Accounts Search Form -->
      <div id="filter-admin" style="padding: 16px 24px 0; display: none;">
        <form method="GET" action="{{ route('admin.AddAccount') }}" style="margin-bottom: 16px;">
          <input type="hidden" name="tab" value="list">
          <input type="hidden" name="subtab" value="admin">
          <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
            <input type="text" id="admin_search_name" name="search_name"
                   style="flex: 1; min-width: 160px; padding: 10px 12px; border: 2px solid #e1e5e9; border-radius: 6px; font-size: 14px;"
                   value="{{ request('subtab') === 'admin' ? request('search_name') : '' }}"
                   placeholder="Search by name">
            <select name="search_department"
                    style="flex: 1; min-width: 160px; padding: 10px 12px; border: 2px solid #e1e5e9; border-radius: 6px; font-size: 14px; background: white; color: #495057;">
              <option value="">All Departments</option>
              @foreach(\App\Models\Department::allCodes() as $dept)
              <option value="{{ $dept }}" {{ request('subtab') === 'admin' && request('search_department') === $dept ? 'selected' : '' }}>{{ $dept }}</option>
              @endforeach
            </select>
            <button type="submit" style="padding: 10px 16px; background: var(--primary-green); color: #fff !important; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 6px; white-space: nowrap;">
              <svg style="width: 16px; height: 16px;" fill="none" stroke="white" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
              </svg>
              <span style="color: #fff !important;">Search</span>
            </button>
            <a href="{{ route('admin.AddAccount', ['tab' => 'list', 'subtab' => 'admin']) }}" style="padding: 10px 16px; background: #e74c3c; color: white; text-decoration: none; border-radius: 6px; font-weight: 600; display: flex; align-items: center; gap: 6px; white-space: nowrap;">
              <svg style="width: 16px; height: 16px;" fill="none" stroke="white" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
              </svg>
              Clear
            </a>
            @if(request('subtab') === 'admin' && request()->hasAny(['search_name', 'search_department']))
            <span style="color: var(--primary-green); font-size: 13px; font-weight: 600;">Filters Active</span>
            @endif
          </div>
        </form>
      </div>

      <!-- Sub-tab: Students -->
      <div id="subtab-students-content" style="padding: 0 24px 24px;">
        <div style="overflow-x: auto;">
          <table style="width: 100%; border-collapse: collapse; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 1px 6px rgba(0,0,0,0.08);">
            <thead>
              <tr style="background: #f9fafb; border-bottom: 1px solid #e5e7eb;">
                <th style="padding: 11px 14px; text-align: left; font-weight: 600; color: #374151; font-size: 12px; text-transform: uppercase; letter-spacing: 0.04em;">Full Name</th>
                <th style="padding: 11px 14px; text-align: left; font-weight: 600; color: #374151; font-size: 12px; text-transform: uppercase; letter-spacing: 0.04em;">Student ID</th>
                <th style="padding: 11px 14px; text-align: left; font-weight: 600; color: #374151; font-size: 12px; text-transform: uppercase; letter-spacing: 0.04em;">Email</th>
                <th style="padding: 11px 14px; text-align: left; font-weight: 600; color: #374151; font-size: 12px; text-transform: uppercase; letter-spacing: 0.04em;">Program</th>
                <th style="padding: 11px 14px; text-align: left; font-weight: 600; color: #374151; font-size: 12px; text-transform: uppercase; letter-spacing: 0.04em;">Year Level</th>
                <th style="padding: 11px 14px; text-align: left; font-weight: 600; color: #374151; font-size: 12px; text-transform: uppercase; letter-spacing: 0.04em;">Role</th>
                <th style="padding: 11px 14px; text-align: left; font-weight: 600; color: #374151; font-size: 12px; text-transform: uppercase; letter-spacing: 0.04em;">Status</th>
                <th style="padding: 11px 14px; text-align: center; font-weight: 600; color: #374151; font-size: 12px; text-transform: uppercase; letter-spacing: 0.04em;">Actions</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($students as $student)
              <tr style="border-bottom: 1px solid #e9ecef;"
                  onmouseover="this.style.backgroundColor='#f9fafb'"
                  onmouseout="this.style.backgroundColor='transparent'">
                <td style="padding: 12px 14px; color: #212529; font-size: 14px; font-weight: 500;">
                  {{ $student->fullname }}
                </td>
                <td style="padding: 12px 14px; font-size: 13px;">
                  @if($student->student_id)
                    <span style="font-family: monospace; background: #e9ecef; padding: 2px 7px; border-radius: 4px;">{{ $student->student_id }}</span>
                  @else
                    <span style="color: #adb5bd; font-style: italic; font-size: 12px;">Not set</span>
                  @endif
                </td>
                <td style="padding: 12px 14px; color: #495057; font-size: 13px;">{{ $student->email }}</td>
                <td style="padding: 12px 14px; color: #495057; font-size: 13px;">
                  {{ $student->department ?? '—' }}@if($student->course)<span style="color: #ced4da; margin: 0 4px;">•</span>{{ $student->course }}@endif
                </td>
                <td style="padding: 12px 14px; font-size: 13px;">
                  @if($student->year_level)
                    <span style="display: inline-block; padding: 3px 9px; background: #f1f3f5; color: #6b7280; border-radius: 12px; font-size: 12px; font-weight: 500;">{{ $student->year_level }}</span>
                  @else
                    <span style="color: #adb5bd; font-style: italic; font-size: 12px;">Not set</span>
                  @endif
                </td>
                <td style="padding: 12px 14px;">
                  @if($student->account_type === 'alumni')
                    <span style="display: inline-block; padding: 4px 11px; background: #6610f2; color: #fff !important; border-radius: 20px; font-size: 12px; font-weight: 500;">Alumni</span>
                  @else
                    <span style="display: inline-block; padding: 4px 11px; background: #17a2b8; color: #fff !important; border-radius: 20px; font-size: 12px; font-weight: 500;">Student</span>
                  @endif
                </td>
                <td style="padding: 12px 14px;">
                  @if($student->status === 'active')
                    <span style="display: inline-flex; align-items: center; gap: 4px; padding: 4px 10px; background: #28a745; color: #fff !important; border-radius: 20px; font-size: 12px; font-weight: 500;">
                      <svg style="width: 12px; height: 12px;" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                      <span style="color: #fff !important;">Active</span>
                    </span>
                  @else
                    <span style="display: inline-flex; align-items: center; gap: 4px; padding: 4px 10px; background: #dc3545; color: #fff !important; border-radius: 20px; font-size: 12px; font-weight: 500;">
                      <svg style="width: 12px; height: 12px;" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 0 0 5.636 5.636m12.728 12.728A9 9 0 0 1 5.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                      <span style="color: #fff !important;">Inactive</span>
                    </span>
                  @endif
                </td>
                <td style="padding: 12px 14px; text-align: center;">
                  <div style="display: flex; gap: 5px; justify-content: center;">
                    <button onclick="editAccount({{ $student->id }})" title="Edit"
                            style="padding: 7px; background: var(--primary-green); border: none; border-radius: 7px; cursor: pointer; display: flex; transition: all 0.2s;"
                            onmouseover="this.style.background='#0b5d1e'; this.style.transform='translateY(-1px)'"
                            onmouseout="this.style.background='var(--primary-green)'; this.style.transform='none'">
                      <svg style="width: 15px; height: 15px;" fill="none" stroke="white" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10"/>
                      </svg>
                    </button>
                    @if($student->academic_status !== 'Course Completed')
                    <button onclick="convertToAlumni({{ $student->id }}, '{{ addslashes($student->fullname) }}')" title="Mark as Graduated"
                            style="padding: 7px; background: #6c757d; border: none; border-radius: 7px; cursor: pointer; display: flex; transition: all 0.2s;"
                            onmouseover="this.style.background='#545b62'; this.style.transform='translateY(-1px)'"
                            onmouseout="this.style.background='#6c757d'; this.style.transform='none'">
                      <svg style="width: 15px; height: 15px;" fill="none" stroke="white" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5"/>
                      </svg>
                    </button>
                    @endif
                  </div>
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="8" style="padding: 32px; text-align: center; color: #6c757d;">
                  @if(request()->hasAny(['search_name', 'search_student_id']))
                    No students match the current search criteria.
                  @else
                    No student accounts found.
                  @endif
                </td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>

        {{ $students->appends(['tab' => 'list', 'subtab' => 'students'])->appends(request()->except(['tab', 'subtab', 'page']))->links('vendor.pagination.custom') }}
      </div>

      <!-- Sub-tab: Administrative Accounts -->
      <div id="subtab-admin-content" style="padding: 0 24px 24px; display: none;">
        <div style="overflow-x: auto;">
          <table style="width: 100%; border-collapse: collapse; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 1px 6px rgba(0,0,0,0.08);">
            <thead>
              <tr style="background: #f9fafb; border-bottom: 1px solid #e5e7eb;">
                <th style="padding: 11px 14px; text-align: left; font-weight: 600; color: #374151; font-size: 12px; text-transform: uppercase; letter-spacing: 0.04em;">Full Name</th>
                <th style="padding: 11px 14px; text-align: left; font-weight: 600; color: #374151; font-size: 12px; text-transform: uppercase; letter-spacing: 0.04em;">Email</th>
                <th style="padding: 11px 14px; text-align: left; font-weight: 600; color: #374151; font-size: 12px; text-transform: uppercase; letter-spacing: 0.04em;">Role</th>
                <th style="padding: 11px 14px; text-align: left; font-weight: 600; color: #374151; font-size: 12px; text-transform: uppercase; letter-spacing: 0.04em;">Department</th>
                <th style="padding: 11px 14px; text-align: left; font-weight: 600; color: #374151; font-size: 12px; text-transform: uppercase; letter-spacing: 0.04em;">Status</th>
                <th style="padding: 11px 14px; text-align: center; font-weight: 600; color: #374151; font-size: 12px; text-transform: uppercase; letter-spacing: 0.04em;">Actions</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($adminAccounts as $account)
              @php
                $rColor = $roleColors[$account->account_type] ?? '#6c757d';
                $rLabel = $roleLabels[$account->account_type] ?? ucfirst(str_replace('_', ' ', $account->account_type));
              @endphp
              <tr style="border-bottom: 1px solid #e9ecef;"
                  onmouseover="this.style.backgroundColor='#f9fafb'"
                  onmouseout="this.style.backgroundColor='transparent'">
                <td style="padding: 12px 14px; color: #212529; font-size: 14px; font-weight: 500;">{{ $account->fullname }}</td>
                <td style="padding: 12px 14px; color: #495057; font-size: 13px;">{{ $account->email }}</td>
                <td style="padding: 12px 14px;">
                  <span style="display: inline-block; padding: 4px 11px; background: {{ $rColor }}; color: #fff !important; border-radius: 20px; font-size: 12px; font-weight: 500;">
                    <span style="color: #fff !important;">{{ $rLabel }}</span>
                  </span>
                </td>
                <td style="padding: 12px 14px; color: #495057; font-size: 13px;">{{ $account->department ?? '—' }}</td>
                <td style="padding: 12px 14px;">
                  @if($account->status === 'active')
                    <span style="display: inline-flex; align-items: center; gap: 4px; padding: 4px 10px; background: #28a745; color: #fff !important; border-radius: 20px; font-size: 12px; font-weight: 500;">
                      <svg style="width: 12px; height: 12px;" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                      <span style="color: #fff !important;">Active</span>
                    </span>
                  @else
                    <span style="display: inline-flex; align-items: center; gap: 4px; padding: 4px 10px; background: #dc3545; color: #fff !important; border-radius: 20px; font-size: 12px; font-weight: 500;">
                      <svg style="width: 12px; height: 12px;" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 0 0 5.636 5.636m12.728 12.728A9 9 0 0 1 5.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                      <span style="color: #fff !important;">Inactive</span>
                    </span>
                  @endif
                </td>
                <td style="padding: 12px 14px; text-align: center;">
                  <div style="display: flex; gap: 5px; justify-content: center;">
                    <button onclick="editAccount({{ $account->id }})" title="Edit"
                            style="padding: 7px; background: var(--primary-green); border: none; border-radius: 7px; cursor: pointer; display: flex; transition: all 0.2s;"
                            onmouseover="this.style.background='#0b5d1e'; this.style.transform='translateY(-1px)'"
                            onmouseout="this.style.background='var(--primary-green)'; this.style.transform='none'">
                      <svg style="width: 15px; height: 15px;" fill="none" stroke="white" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10"/>
                      </svg>
                    </button>
                  </div>
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="6" style="padding: 32px; text-align: center; color: #6c757d;">
                  @if(request()->filled('search_name'))
                    No administrative accounts match the current search.
                  @else
                    No administrative accounts found.
                  @endif
                </td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
        {{ $adminAccounts->appends(['tab' => 'list', 'subtab' => 'admin'])->appends(request()->except(['tab', 'subtab', 'page']))->links('vendor.pagination.custom') }}
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
          <label for="edit_first_name" style="display: block; margin-bottom: 8px; font-weight: 600; color: #333;">First Name *</label>
          <input type="text" id="edit_first_name" name="first_name" required
                 style="width: 100%; padding: 12px 16px; border: 2px solid #e1e5e9; border-radius: 8px; font-size: 14px; transition: border-color 0.3s ease;"
                 onfocus="this.style.borderColor='var(--primary-green)'"
                 onblur="this.style.borderColor='#e1e5e9'">
        </div>

        <div>
          <label for="edit_middle_initial" style="display: block; margin-bottom: 8px; font-weight: 600; color: #333;">Middle Initial</label>
          <input type="text" id="edit_middle_initial" name="middle_initial" maxlength="10"
                 style="width: 100%; padding: 12px 16px; border: 2px solid #e1e5e9; border-radius: 8px; font-size: 14px; transition: border-color 0.3s ease;"
                 onfocus="this.style.borderColor='var(--primary-green)'"
                 onblur="this.style.borderColor='#e1e5e9'"
                 placeholder="e.g. M">
        </div>

        <div>
          <label for="edit_last_name" style="display: block; margin-bottom: 8px; font-weight: 600; color: #333;">Last Name *</label>
          <input type="text" id="edit_last_name" name="last_name" required
                 style="width: 100%; padding: 12px 16px; border: 2px solid #e1e5e9; border-radius: 8px; font-size: 14px; transition: border-color 0.3s ease;"
                 onfocus="this.style.borderColor='var(--primary-green)'"
                 onblur="this.style.borderColor='#e1e5e9'">
        </div>

        <div>
          <label for="edit_extension_name" style="display: block; margin-bottom: 8px; font-weight: 600; color: #333;">Extension Name</label>
          <input type="text" id="edit_extension_name" name="extension_name" maxlength="50"
                 style="width: 100%; padding: 12px 16px; border: 2px solid #e1e5e9; border-radius: 8px; font-size: 14px; transition: border-color 0.3s ease;"
                 onfocus="this.style.borderColor='var(--primary-green)'"
                 onblur="this.style.borderColor='#e1e5e9'"
                 placeholder="e.g. Jr., Sr., III">
        </div>

        <div id="edit_student_id_field">
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

        <div id="edit_course_field">
          <label for="edit_course_id" style="display: block; margin-bottom: 8px; font-weight: 600; color: #333;">Course</label>
          <select id="edit_course_id" name="course_id"
                  style="width: 100%; padding: 12px 16px; border: 2px solid #e1e5e9; border-radius: 8px; font-size: 14px; transition: border-color 0.3s ease;"
                  onfocus="this.style.borderColor='var(--primary-green)'"
                  onblur="this.style.borderColor='#e1e5e9'">
            <option value="">Select Course</option>
            @foreach ($courses as $course)
              <option value="{{ $course->id }}">{{ $course->course_code }} - {{ $course->course_name }}</option>
            @endforeach
          </select>
        </div>

        <div id="edit_year_level_field">
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
          <p id="edit_imported_notice" style="display: none; margin-top: 8px; font-size: 13px; color: #DC2626; font-style: italic;">
            This account was created via Import Students. Account type cannot be changed.
          </p>
        </div>

        <div>
          <label for="edit_status" style="display: block; margin-bottom: 8px; font-weight: 600; color: #333;">Status *</label>
          <select id="edit_status" name="status" required
                  style="width: 100%; padding: 12px 16px; border: 2px solid #e1e5e9; border-radius: 8px; font-size: 14px; transition: border-color 0.3s ease;"
                  onfocus="this.style.borderColor='var(--primary-green)'"
                  onblur="this.style.borderColor='#e1e5e9'">
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
          </select>
        </div>

        <div style="grid-column: 1 / -1; margin-top: 28px; display: flex; justify-content: flex-end;">
          <button type="submit" class="btn-primary" style="color: #ffffff !important; display: flex; align-items: center; gap: 8px;">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 16px; height: 16px;">
              <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
            </svg>
            Save Changes
          </button>
        </div>
      </form>
    </div>
  </div>

  @php
    $courseData = $courses->map(fn($c) => ['id' => $c->id, 'code' => $c->course_code, 'name' => $c->course_name, 'department' => $c->department]);
  @endphp
  <script>
    // Course data from database
    const allCourses = @json($courseData);

    function populateCourseDropdown(department, selectedCourseId = '') {
      const courseSelect = document.getElementById('edit_course_id');
      courseSelect.innerHTML = '<option value="">Select Course</option>';

      allCourses.forEach(course => {
        if (!department || course.department === department) {
          const option = document.createElement('option');
          option.value = course.id;
          option.textContent = `${course.code} - ${course.name}`;
          if (String(course.id) === String(selectedCourseId)) {
            option.selected = true;
          }
          courseSelect.appendChild(option);
        }
      });
    }

    // ── Create form: filter courses by department ──
    function populateCreateCourseDropdown(department, selectedCourseId = '') {
      const courseSelect = document.getElementById('course_id');
      courseSelect.innerHTML = '<option value="">Select Course</option>';

      if (!department || department === 'Others') {
        courseSelect.disabled = true;
        courseSelect.innerHTML = '<option value="">Select a department first</option>';
        return;
      }

      const filtered = allCourses.filter(c => c.department === department);
      if (filtered.length === 0) {
        courseSelect.disabled = true;
        courseSelect.innerHTML = '<option value="">No courses for this department</option>';
        return;
      }

      courseSelect.disabled = false;
      filtered.forEach(course => {
        const option = document.createElement('option');
        option.value = course.id;
        option.textContent = `${course.code} - ${course.name}`;
        if (String(course.id) === String(selectedCourseId)) {
          option.selected = true;
        }
        courseSelect.appendChild(option);
      });
    }

    // Add event listeners for department change on both forms
    document.addEventListener('DOMContentLoaded', function() {
      // Edit modal department listener
      const editDepartmentSelect = document.getElementById('edit_department');
      if (editDepartmentSelect) {
        editDepartmentSelect.addEventListener('change', function() {
          populateCourseDropdown(this.value);
        });
      }

      // Create form department listener
      const createDepartmentSelect = document.getElementById('department');
      if (createDepartmentSelect) {
        createDepartmentSelect.addEventListener('change', function() {
          populateCreateCourseDropdown(this.value);
        });

        // If department was pre-selected (e.g. validation error redirect), populate courses
        if (createDepartmentSelect.value) {
          populateCreateCourseDropdown(createDepartmentSelect.value, '{{ old("course_id", "") }}');
        }
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

    function showAccountSubTab(name) {
      const isStudents = name === 'students';
      document.getElementById('subtab-students-content').style.display = isStudents ? 'block' : 'none';
      document.getElementById('subtab-admin-content').style.display   = isStudents ? 'none'  : 'block';
      document.getElementById('filter-students').style.display        = isStudents ? 'block' : 'none';
      document.getElementById('filter-admin').style.display           = isStudents ? 'none'  : 'block';
      document.getElementById('subtab-students').style.borderBottomColor = isStudents ? 'var(--primary-green)' : 'transparent';
      document.getElementById('subtab-students').style.color            = isStudents ? 'var(--primary-green)' : '#6c757d';
      document.getElementById('subtab-admin').style.borderBottomColor  = isStudents ? 'transparent' : 'var(--primary-green)';
      document.getElementById('subtab-admin').style.color              = isStudents ? '#6c757d' : 'var(--primary-green)';
      // Persist sub-tab in URL
      const url = new URL(window.location);
      if (name !== 'students') { url.searchParams.set('subtab', name); } else { url.searchParams.delete('subtab'); }
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
        // Restore sub-tab state
        const subtabParam = urlParams.get('subtab');
        if (subtabParam === 'admin') {
          showAccountSubTab('admin');
        }
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
            // Populate separate name fields
            document.getElementById('edit_first_name').value = data.account.first_name || '';
            document.getElementById('edit_middle_initial').value = data.account.middle_initial || '';
            document.getElementById('edit_last_name').value = data.account.last_name || '';
            document.getElementById('edit_extension_name').value = data.account.extension_name || '';

            document.getElementById('edit_student_id').value = data.account.student_id || '';
            document.getElementById('edit_email').value = data.account.email || '';
            document.getElementById('edit_department').value = data.account.department || '';
            document.getElementById('edit_year_level').value = data.account.year_level || '';
            // Configure account type dropdown based on current account type
            const accountTypeSelect = document.getElementById('edit_account_type');
            const importedNotice = document.getElementById('edit_imported_notice');
            // Remove any previously added hidden input for account_type
            const existingHidden = document.getElementById('edit_account_type_hidden');
            if (existingHidden) existingHidden.remove();

            const isStudent = data.account.account_type === 'student';
            const isImported = data.account.is_imported;

            if (isStudent || isImported) {
              // Students: lock to Student only; Imported: lock to current type
              accountTypeSelect.disabled = true;
              accountTypeSelect.style.backgroundColor = '#f3f4f6';
              accountTypeSelect.style.cursor = 'not-allowed';
              importedNotice.style.display = 'block';
              importedNotice.textContent = isStudent
                ? 'Student accounts can only have the Student role.'
                : 'This account was created via Import Students. Account type cannot be changed.';
              // Add hidden input so the value is still submitted
              const hiddenInput = document.createElement('input');
              hiddenInput.type = 'hidden';
              hiddenInput.name = 'account_type';
              hiddenInput.id = 'edit_account_type_hidden';
              hiddenInput.value = data.account.account_type;
              accountTypeSelect.parentNode.appendChild(hiddenInput);
            } else {
              accountTypeSelect.disabled = false;
              accountTypeSelect.style.backgroundColor = 'white';
              accountTypeSelect.style.cursor = '';
              importedNotice.style.display = 'none';
            }

            document.getElementById('edit_account_type').value = data.account.account_type || '';

            // Toggle field visibility based on account type
            const editStudentIdField = document.getElementById('edit_student_id_field');
            const editCourseField = document.getElementById('edit_course_field');
            const editYearLevelField = document.getElementById('edit_year_level_field');

            if (data.account.account_type === 'student') {
              // Students (including graduated/alumni): show all academic fields
              editStudentIdField.style.display = 'block';
              editCourseField.style.display = 'block';
              editYearLevelField.style.display = 'block';
            } else {
              // Staff: hide all academic fields
              editStudentIdField.style.display = 'none';
              editCourseField.style.display = 'none';
              editYearLevelField.style.display = 'none';
              document.getElementById('edit_student_id').value = '';
              document.getElementById('edit_course_id').value = '';
              document.getElementById('edit_year_level').value = '';
            }
            document.getElementById('edit_status').value = data.account.status || '';

            // Populate course dropdown based on department, select current course_id
            populateCourseDropdown(data.account.department, data.account.course_id);

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

    // Close modals when clicking outside
    document.getElementById('editAccountModal').addEventListener('click', function(e) {
      if (e.target === this) {
        closeEditModal();
      }
    });

    // Close modals with Escape key
    document.addEventListener('keydown', function(event) {
      if (event.key === 'Escape') {
        closeEditModal();
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

  </script>

  {{-- Convert to Alumni Confirmation Modal --}}
  <x-shared.modals.confirm-action
      id="alumniConfirmModal"
      title="Convert to Alumni"
      title-color="#6a1b9a"
      close-fn="closeAlumniModal()"
      z-index="10000">

    <div style="text-align: center;">
      <div style="width: 64px; height: 64px; background: linear-gradient(135deg, #6610f2, #520dc2); border-radius: 50%; margin: 0 auto 20px; display: flex; align-items: center; justify-content: center;">
        <svg style="width: 32px; height: 32px; color: white;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5"></path>
        </svg>
      </div>
      <p style="margin: 0 0 8px 0; color: #4a5568; font-size: 16px; line-height: 1.5;">
        Are you sure you want to convert this student to alumni?
      </p>
      <p id="alumniAccountName" style="margin: 0 0 16px 0; color: #2d3748; font-size: 18px; font-weight: 600; background: #f7fafc; padding: 12px; border-radius: 8px; border-left: 4px solid #6610f2;"></p>
      <div style="background: #f3e5f5; padding: 16px; border-radius: 8px; border: 1px solid #ce93d8; margin-bottom: 4px; text-align: left;">
        <p style="margin: 0 0 8px; font-size: 14px; font-weight: 600; color: #6a1b9a;">This will:</p>
        <ul style="margin: 0; padding-left: 20px; font-size: 14px; color: #6a1b9a; line-height: 1.6;">
          <li>Change account type from <strong>Student</strong> to <strong>Alumni</strong></li>
          <li>Remove access to PSG Officer applications</li>
          <li>Retain Good Moral application and violation history access</li>
        </ul>
      </div>
    </div>

    <x-slot name="footer">
      <div style="display: flex; gap: 12px; margin-top: 20px;">
        <button onclick="closeAlumniModal()" style="flex: 1; padding: 12px 20px; background: #e2e8f0; color: #2d3748; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; transition: all 0.3s ease;">
          Cancel
        </button>
        <button id="confirmAlumniBtn" onclick="confirmConvertToAlumni()" style="flex: 1; padding: 12px 20px; background: linear-gradient(135deg, #6610f2, #520dc2); color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(102, 16, 242, 0.3);">
          Convert to Alumni
        </button>
      </div>
    </x-slot>

  </x-shared.modals.confirm-action>

  <script>
    // Convert to Alumni Functions
    let pendingAlumniId = null;

    function convertToAlumni(accountId, accountName) {
      pendingAlumniId = accountId;
      document.getElementById('alumniAccountName').textContent = accountName;
      document.getElementById('alumniConfirmModal').style.display = 'flex';
      document.body.style.overflow = 'hidden';
    }

    function closeAlumniModal() {
      document.getElementById('alumniConfirmModal').style.display = 'none';
      document.body.style.overflow = 'auto';
      pendingAlumniId = null;
    }

    function confirmConvertToAlumni() {
      if (!pendingAlumniId) return;

      const confirmBtn = document.getElementById('confirmAlumniBtn');
      confirmBtn.disabled = true;
      confirmBtn.textContent = 'Converting...';

      const form = document.createElement('form');
      form.method = 'POST';
      form.action = `/admin/account/${pendingAlumniId}/convert-to-alumni`;

      const csrfToken = document.createElement('input');
      csrfToken.type = 'hidden';
      csrfToken.name = '_token';
      csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
      form.appendChild(csrfToken);

      document.body.appendChild(form);
      form.submit();

      closeAlumniModal();
    }

    document.getElementById('alumniConfirmModal').addEventListener('click', function(e) {
      if (e.target === this) closeAlumniModal();
    });

    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape' && document.getElementById('alumniConfirmModal').style.display === 'flex') {
        closeAlumniModal();
      }
    });
  </script>

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
    
    /* Enhance existing edit buttons */
    button[onclick^="editAccount"]:hover {
      transform: translateY(-2px) !important;
      box-shadow: 0 6px 20px rgba(59, 130, 246, 0.4) !important;
    }
  </style>

  <script>
    /**
     * Toggle visibility of student-specific fields based on account type
     */
    function toggleStudentFields() {
      const accountType = document.getElementById('account_type').value;
      const studentIdField = document.getElementById('student_id_field');
      const courseField = document.getElementById('course_field');
      const yearLevelField = document.getElementById('year_level_field');
      const departmentField = document.getElementById('department_field');
      const organizationField = document.getElementById('organization_field');

      const studentIdInput = document.getElementById('student_id');
      const courseInput = document.getElementById('course_id');
      const yearLevelInput = document.getElementById('year_level');
      const departmentInput = document.getElementById('department');
      const organizationInput = document.getElementById('organization');

      // Roles that do not require a department
      const noDepartmentRoles = ['sec_osa'];

      // Toggle department field based on account type
      if (noDepartmentRoles.includes(accountType)) {
        departmentField.style.display = 'none';
        departmentInput.removeAttribute('required');
        departmentInput.value = '';
      } else {
        departmentField.style.display = 'block';
        departmentInput.setAttribute('required', 'required');
      }

      // Toggle PSG-specific fields (no longer applicable in create form)
      organizationField.style.display = 'none';
      organizationInput.removeAttribute('required');
      organizationInput.value = '';

      // Show/hide student-specific fields based on account type
      if (accountType === 'student') {
        // Students: show student ID, course, and year level
        studentIdField.style.display = 'block';
        courseField.style.display = 'block';
        yearLevelField.style.display = 'block';

        studentIdInput.setAttribute('required', 'required');
        courseInput.setAttribute('required', 'required');
        yearLevelInput.setAttribute('required', 'required');
      } else if (accountType === 'alumni') {
        // Alumni: show student ID only (optional), hide course and year level
        studentIdField.style.display = 'block';
        courseField.style.display = 'none';
        yearLevelField.style.display = 'none';

        studentIdInput.removeAttribute('required');
        courseInput.removeAttribute('required');
        yearLevelInput.removeAttribute('required');
        courseInput.value = '';
        yearLevelInput.value = '';
      } else {
        // Staff roles: hide all student fields
        studentIdField.style.display = 'none';
        courseField.style.display = 'none';
        yearLevelField.style.display = 'none';

        studentIdInput.removeAttribute('required');
        courseInput.removeAttribute('required');
        yearLevelInput.removeAttribute('required');

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