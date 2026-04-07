@php
  $accountType = Auth::user()->account_type;
  $roleTitle = $accountType === 'alumni' ? 'Alumni' : 'Student';
@endphp

<x-dashboard-layout>
  <x-slot name="roleTitle">{{ $roleTitle }}</x-slot>

  <x-slot name="navigation">
    <a href="{{ route('dashboard') }}"
       class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="nav-icon">
        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z" />
      </svg>
      <span>Application</span>
    </a>

    <a href="{{ route('notification') }}"
       class="nav-link {{ request()->routeIs('notification') ? 'active' : '' }}">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="nav-icon">
        <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
      </svg>
      <span>Application Notifications</span>
    </a>

    <a href="{{ route('notificationViolation') }}"
       class="nav-link {{ request()->routeIs('notificationViolation') ? 'active' : '' }}">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="nav-icon">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
      </svg>
      <span>Violation Notifications</span>
    </a>

    <a href="{{ route('student.profile') }}"
       class="nav-link {{ request()->routeIs('student.profile') ? 'active' : '' }}">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="nav-icon">
        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
      </svg>
      <span>Profile</span>
    </a>

    <form method="POST" action="{{ route('logout') }}" class="nav-logout-form">
      @csrf
      <button type="submit" class="nav-link nav-logout">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="nav-icon">
          <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
        </svg>
        <span>Logout</span>
      </button>
    </form>
  </x-slot>

  <!-- Header Section -->
  <div class="header-section">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
      <div>
        <h1 class="role-title">{{ $roleTitle }} Profile</h1>
        <p class="welcome-text">Manage your account settings</p>
        <div class="accent-line"></div>
      </div>
      <div style="display: flex; gap: 12px; align-items: center;">
        <div style="padding: 12px 16px; background: var(--light-green); border-radius: 8px; font-size: 14px; color: var(--primary-green); font-weight: 600;">
          {{ date('F j, Y') }}
        </div>
      </div>
    </div>
  </div>

  @if(session('status'))
  <div style="background: #d4edda; color: #155724; padding: 16px; border-radius: 8px; margin-bottom: 24px; border-left: 4px solid #28a745;">
    <strong>Success!</strong> {{ session('status') }}
  </div>
  @endif

  @if($errors->any())
  <div style="background: #f8d7da; color: #721c24; padding: 16px; border-radius: 8px; margin-bottom: 24px; border-left: 4px solid #dc3545;">
    <strong>Error!</strong>
    <ul style="margin: 8px 0 0 0; padding-left: 20px;">
      @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
  </div>
  @endif

  <!-- Profile Information Section -->
  <div class="header-section">
    <h3 style="color: var(--primary-green); margin-bottom: 20px; font-size: 1.3rem; display: flex; align-items: center; gap: 12px;">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="height: 24px; width: 24px;">
        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
      </svg>
      Profile Information
    </h3>

    <div style="background: white; border-radius: 8px; padding: 24px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1); margin-bottom: 24px;">
      <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h3 style="margin: 0; color: #333; font-size: 18px; font-weight: 600;">Personal Information</h3>
        <button type="button" onclick="toggleProfileEdit()" id="profile-edit-btn" style="padding: 10px 16px; background: var(--primary-green); color: white; border: none; border-radius: 6px; font-size: 14px; cursor: pointer; display: flex; align-items: center; gap: 6px;">
          <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
            <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708L10.5 8.207l-3-3L12.146.146zM11.207 9l-3-3L2.5 11.707V14.5a.5.5 0 0 0 .5.5h2.793L11.207 9zM1 13.5A1.5 1.5 0 0 1 2.5 12l5.793-5.793-1.414-1.414L1.086 10.586A2 2 0 0 0 1 11.414V13.5z"/>
          </svg>
          Edit Profile
        </button>
      </div>

      <!-- Profile Display Mode -->
      <div id="profile-display" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
        <div>
          <label style="font-weight: 600; color: #333; font-size: 14px; margin-bottom: 8px; display: block;">Full Name</label>
          <div style="padding: 12px 16px; background: #f8f9fa; border-radius: 8px; color: #666; border: 2px solid #e1e5e9;">
            {{ $student->fullname }}
          </div>
        </div>

        <div>
          <label style="font-weight: 600; color: #333; font-size: 14px; margin-bottom: 8px; display: block;">Student ID</label>
          <div style="padding: 12px 16px; background: #f8f9fa; border-radius: 8px; color: #666; border: 2px solid #e1e5e9;">
            {{ $student->student_id }}
          </div>
        </div>

        <div>
          <label style="font-weight: 600; color: #333; font-size: 14px; margin-bottom: 8px; display: block;">Email Address</label>
          <div style="padding: 12px 16px; background: #f8f9fa; border-radius: 8px; color: #666; border: 2px solid #e1e5e9;">
            {{ $student->email }}
          </div>
        </div>

        <div>
          <label style="font-weight: 600; color: #333; font-size: 14px; margin-bottom: 8px; display: block;">Department</label>
          <div style="padding: 12px 16px; background: #f8f9fa; border-radius: 8px; color: #666; border: 2px solid #e1e5e9;">
            {{ $student->department }}
          </div>
        </div>

        @if($student->year_level)
        <div>
          <label style="font-weight: 600; color: #333; font-size: 14px; margin-bottom: 8px; display: block;">Course & Year</label>
          <div style="padding: 12px 16px; background: #f8f9fa; border-radius: 8px; color: #666; border: 2px solid #e1e5e9;">
            {{ $student->year_level }}
          </div>
        </div>
        @endif

        <div>
          <label style="font-weight: 600; color: #333; font-size: 14px; margin-bottom: 8px; display: block;">Gender</label>
          <div style="padding: 12px 16px; background: #f8f9fa; border-radius: 8px; color: #666; border: 2px solid #e1e5e9;">
            {{ $student->gender ? ucfirst($student->gender) : 'Not specified' }}
          </div>
        </div>

        @if($student->account_type === 'student' || $student->account_type === 'alumni')
        <div>
          <label style="font-weight: 600; color: #333; font-size: 14px; margin-bottom: 8px; display: block;">Course</label>
          <div style="padding: 12px 16px; background: #f8f9fa; border-radius: 8px; color: #666; border: 2px solid #e1e5e9;">
            {{ $student->course ?: 'Not specified' }}
          </div>
        </div>

        <div>
          <label style="font-weight: 600; color: #333; font-size: 14px; margin-bottom: 8px; display: block;">Year Level</label>
          <div style="padding: 12px 16px; background: #f8f9fa; border-radius: 8px; color: #666; border: 2px solid #e1e5e9;">
            {{ $student->year_level ?: 'Not specified' }}
          </div>
        </div>
        @endif

        <div>
          <label style="font-weight: 600; color: #333; font-size: 14px; margin-bottom: 8px; display: block;">Account Type</label>
          <div style="padding: 12px 16px; background: #f8f9fa; border-radius: 8px; color: #666; border: 2px solid #e1e5e9;">
            {{ ucfirst($student->account_type) }}
          </div>
        </div>

        @if($student->organization)
        <div>
          <label style="font-weight: 600; color: #333; font-size: 14px; margin-bottom: 8px; display: block;">Organization</label>
          <div style="padding: 12px 16px; background: #f8f9fa; border-radius: 8px; color: #666; border: 2px solid #e1e5e9;">
            {{ $student->organization }}
          </div>
        </div>
        @endif

        @if($student->position)
        <div>
          <label style="font-weight: 600; color: #333; font-size: 14px; margin-bottom: 8px; display: block;">Position</label>
          <div style="padding: 12px 16px; background: #f8f9fa; border-radius: 8px; color: #666; border: 2px solid #e1e5e9;">
            {{ $student->position }}
          </div>
        </div>
        @endif
      </div>

      <!-- Profile Edit Mode -->
      <div id="profile-edit-form" style="display: none;">
        <form method="POST" action="{{ route('student.profile.update') }}">
          @csrf
          @method('PATCH')

          <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 20px;">
            <!-- Full Name (Read-Only - Official Records) -->
            @php
              $nameParts = explode(',', $student->fullname);
              $lastName = trim($nameParts[0] ?? '');
              $firstMiddle = trim($nameParts[1] ?? '');
              $nameWords = explode(' ', $firstMiddle);
              $firstName = $nameWords[0] ?? '';
              $middleName = isset($nameWords[1]) ? implode(' ', array_slice($nameWords, 1)) : '';
            @endphp

            <!-- Name Fields - Read Only -->
            <div style="grid-column: 1 / -1; background: #f8f9fa; border: 2px solid #e9ecef; border-radius: 8px; padding: 20px; margin-bottom: 16px;">
              <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 16px;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="height: 20px; width: 20px; color: #6c757d;">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                </svg>
                <h4 style="margin: 0; color: #495057; font-size: 1rem; font-weight: 600;">Official Name (Read-Only)</h4>
              </div>
              
              <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 12px;">
                <div>
                  <label style="font-weight: 600; color: #6c757d; font-size: 14px; margin-bottom: 8px; display: block;">First Name</label>
                  <div style="padding: 12px 16px; background: #e9ecef; border-radius: 8px; color: #495057; border: 2px solid #dee2e6; font-weight: 500;">
                    {{ $firstName }}
                  </div>
                </div>

                <div>
                  <label style="font-weight: 600; color: #6c757d; font-size: 14px; margin-bottom: 8px; display: block;">Middle Name</label>
                  <div style="padding: 12px 16px; background: #e9ecef; border-radius: 8px; color: #495057; border: 2px solid #dee2e6; font-weight: 500;">
                    {{ $student->mname ?: 'N/A' }}
                  </div>
                </div>

                <div>
                  <label style="font-weight: 600; color: #6c757d; font-size: 14px; margin-bottom: 8px; display: block;">Last Name</label>
                  <div style="padding: 12px 16px; background: #e9ecef; border-radius: 8px; color: #495057; border: 2px solid #dee2e6; font-weight: 500;">
                    {{ $lastName }}
                  </div>
                </div>

                <div>
                  <label style="font-weight: 600; color: #6c757d; font-size: 14px; margin-bottom: 8px; display: block;">Extension</label>
                  <div style="padding: 12px 16px; background: #e9ecef; border-radius: 8px; color: #495057; border: 2px solid #dee2e6; font-weight: 500;">
                    {{ $student->extension ?: 'N/A' }}
                  </div>
                </div>
              </div>

              <div style="background: #fff3cd; border: 1px solid #ffeaa7; border-radius: 6px; padding: 12px;">
                <div style="display: flex; align-items: center; gap: 8px;">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="height: 16px; width: 16px; color: #856404;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                  </svg>
                  <span style="color: #856404; font-size: 13px; font-weight: 600;">
                    Name changes require official request to the Registrar or OSA. Contact admin for corrections (typos, legal name changes, marriage).
                  </span>
                </div>
              </div>
            </div>

            <div>
              <label style="font-weight: 600; color: #333; font-size: 14px; margin-bottom: 8px; display: block;">
                Email Address <span style="color: #dc3545; font-weight: bold;">*</span>
              </label>
              <input type="email" name="email" value="{{ $student->email }}" required
                     style="width: 100%; padding: 12px 16px; border: 2px solid #e1e5e9; border-radius: 8px; font-size: 14px;">
            </div>

            <div>
              <label style="font-weight: 600; color: #333; font-size: 14px; margin-bottom: 8px; display: block;">
                Gender <span style="color: #dc3545; font-weight: bold;">*</span>
              </label>
              <select name="gender" required style="width: 100%; padding: 12px 16px; border: 2px solid #e1e5e9; border-radius: 8px; font-size: 14px;">
                <option value="male" {{ $student->gender == 'male' ? 'selected' : '' }}>Male</option>
                <option value="female" {{ $student->gender == 'female' ? 'selected' : '' }}>Female</option>
              </select>
            </div>

            @if($student->account_type === 'student' || $student->account_type === 'alumni')
            <!-- Academic Information - Read Only -->
            <div style="grid-column: 1 / -1; background: #f8f9fa; border: 2px solid #e9ecef; border-radius: 8px; padding: 20px; margin-bottom: 16px;">
              <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 16px;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="height: 20px; width: 20px; color: #6c757d;">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 00-.491 6.347A48.62 48.62 0 0112 20.904a48.62 48.62 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.636 50.636 0 00-2.658-.813A59.906 59.906 0 0112 3.493a59.903 59.903 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443a55.381 55.381 0 015.25 2.882V15" />
                </svg>
                <h4 style="margin: 0; color: #495057; font-size: 1rem; font-weight: 600;">Academic Information (Read-Only)</h4>
              </div>

              <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 12px;">
                <div>
                  <label style="font-weight: 600; color: #6c757d; font-size: 14px; margin-bottom: 8px; display: block;">Course</label>
                  <div style="padding: 12px 16px; background: #e9ecef; border-radius: 8px; color: #495057; border: 2px solid #dee2e6; font-weight: 500;">
                    {{ $student->course ?: 'Not specified' }}
                  </div>
                </div>

                <div>
                  <label style="font-weight: 600; color: #6c757d; font-size: 14px; margin-bottom: 8px; display: block;">Year Level</label>
                  <div style="padding: 12px 16px; background: #e9ecef; border-radius: 8px; color: #495057; border: 2px solid #dee2e6; font-weight: 500;">
                    {{ $student->year_level ?: 'Not specified' }}
                  </div>
                </div>
              </div>

              <div style="background: #e3f2fd; border: 1px solid #90caf9; border-radius: 6px; padding: 12px;">
                <div style="display: flex; align-items: center; gap: 8px;">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="height: 16px; width: 16px; color: #1976d2;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                  </svg>
                  <span style="color: #1976d2; font-size: 13px; font-weight: 600;">
                    Academic information is managed by the Registrar. Year level progression updated based on official enrollment records.
                  </span>
                </div>
              </div>
            </div>
            @endif

            @if($student->account_type === 'psg_officer')
            <div>
              <label style="font-weight: 600; color: #333; font-size: 14px; margin-bottom: 8px; display: block;">
                Organization <span style="color: #dc3545; font-weight: bold;">*</span>
              </label>
              <input type="text" name="organization" value="{{ $student->organization }}" required
                     style="width: 100%; padding: 12px 16px; border: 2px solid #e1e5e9; border-radius: 8px; font-size: 14px;">
            </div>

            <div>
              <label style="font-weight: 600; color: #333; font-size: 14px; margin-bottom: 8px; display: block;">
                Position <span style="color: #dc3545; font-weight: bold;">*</span>
              </label>
              <input type="text" name="position" value="{{ $student->position }}" required
                     style="width: 100%; padding: 12px 16px; border: 2px solid #e1e5e9; border-radius: 8px; font-size: 14px;">
            </div>
            @endif
          </div>

          <div style="display: flex; gap: 12px; justify-content: flex-end;">
            <button type="button" onclick="toggleProfileEdit()" style="padding: 12px 20px; background: #6c757d; color: white; border: none; border-radius: 6px; font-size: 14px; cursor: pointer;">
              Cancel
            </button>
            <button type="submit" style="padding: 12px 20px; background: var(--primary-green); color: white; border: none; border-radius: 6px; font-size: 14px; cursor: pointer;">
              Save Changes
            </button>
          </div>
        </form>
      </div>

      <div style="background: #e8f5e8; padding: 16px; border-radius: 8px; margin-top: 20px; border-left: 4px solid var(--primary-green);">
        <p style="color: #333; margin: 0; font-size: 14px;">
          <strong>Profile Update Policy:</strong> You can edit contact information and personal details. 
          <strong>Name changes</strong> require formal request to Registrar/OSA. 
          <strong>Academic information</strong> (course, year level) is managed by the Registrar based on official enrollment records.
        </p>
      </div>
    </div>
  </div>

  <!-- Graduation Status Section (Only for Students) -->
  @if($student->account_type === 'student')
  <div class="header-section">
    <h3 style="color: var(--primary-green); margin-bottom: 20px; font-size: 1.3rem; display: flex; align-items: center; gap: 12px;">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="height: 24px; width: 24px;">
        <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443a55.381 55.381 0 015.25 2.882V15" />
      </svg>
      Graduation Status
    </h3>

    <div style="background: white; border-radius: 8px; padding: 24px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1); margin-bottom: 24px;">
      <div id="graduation-status-container">
        @if($student->is_graduating)
          <!-- Currently Graduating -->
          <div style="background: linear-gradient(135deg, #e8f5e8 0%, #f0f8f0 100%); border: 2px solid var(--primary-green); border-radius: 12px; padding: 24px; margin-bottom: 20px;">
            <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 16px;">
              <div style="width: 48px; height: 48px; background: var(--primary-green); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="white" viewBox="0 0 24 24" style="width: 24px; height: 24px;">
                  <path d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443a55.381 55.381 0 015.25 2.882V15"/>
                </svg>
              </div>
              <div>
                <h4 style="margin: 0; color: var(--primary-green); font-size: 1.2rem; font-weight: 600;">🎓 Graduating Student</h4>
                <p style="margin: 4px 0 0 0; color: #666; font-size: 14px;">You are marked as graduating</p>
              </div>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 20px;">
              <div>
                <label style="font-weight: 600; color: #333; font-size: 14px; margin-bottom: 8px; display: block;">Graduation Date</label>
                <div style="padding: 12px 16px; background: white; border-radius: 8px; color: var(--primary-green); border: 2px solid var(--primary-green); font-weight: 600;">
                  {{ $student->graduation_date ? $student->graduation_date->format('F j, Y') : 'Not set' }}
                </div>
              </div>

              @if($student->graduation_date && $student->graduation_date->isPast())
              <div>
                <label style="font-weight: 600; color: #333; font-size: 14px; margin-bottom: 8px; display: block;">Ready for Alumni Conversion</label>
                <button onclick="convertToAlumni()" class="btn-primary" style="width: 100%; justify-content: center;">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 20px; height: 20px; margin-right: 8px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443a55.381 55.381 0 015.25 2.882V15" />
                  </svg>
                  Convert to Alumni
                </button>
              </div>
              @endif
            </div>

            <div style="display: flex; gap: 12px; flex-wrap: wrap;">
              <button onclick="updateGraduationDate()" class="btn-secondary" style="display: flex; align-items: center; gap: 8px;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 16px; height: 16px;">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" />
                </svg>
                Update Date
              </button>
              <button onclick="removeGraduationStatus()" class="btn-danger" style="display: flex; align-items: center; gap: 8px; background: #dc3545; color: white; padding: 10px 16px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 16px; height: 16px;">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
                Remove Status
              </button>
            </div>
          </div>
        @else
          <!-- Not Graduating -->
          <div style="background: #f8f9fa; border: 2px dashed #dee2e6; border-radius: 12px; padding: 32px; text-align: center;">
            <div style="width: 64px; height: 64px; background: #e9ecef; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 32px; height: 32px; color: #6c757d;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443a55.381 55.381 0 015.25 2.882V15" />
              </svg>
            </div>
            <h4 style="margin: 0 0 12px 0; color: #495057; font-size: 1.1rem;">Not Currently Graduating</h4>
            <p style="margin: 0 0 24px 0; color: #6c757d; font-size: 14px; max-width: 400px; margin-left: auto; margin-right: auto;">
              Mark yourself as graduating to prepare for your transition to alumni status. This will help the system track your graduation progress.
            </p>
            <button onclick="showGraduationModal()" class="btn btn-primary" style="display: inline-flex; align-items: center; gap: 8px;">
              <x-icon name="success" size="20" />
              Mark as Graduating
            </button>
          </div>
        @endif
      </div>

      <div style="background: #e8f4fd; padding: 16px; border-radius: 8px; margin-top: 20px; border-left: 4px solid #0ea5e9;">
        <h4 style="color: #0369a1; margin: 0 0 8px 0; font-size: 14px; font-weight: 600;">ℹ️ About Graduation Status</h4>
        <ul style="margin: 0; padding-left: 20px; color: #0369a1; font-size: 14px; line-height: 1.6;">
          <li>Mark yourself as graduating when you're in your final semester</li>
          <li>Set your expected graduation date to track your progress</li>
          <li>On or after your graduation date, you can convert to alumni status</li>
          <li>Alumni accounts have access to different features and services</li>
          <li>This change is permanent once converted to alumni</li>
        </ul>
      </div>
    </div>
  </div>
  @endif

  <!-- Change Password Section -->
  <div class="header-section">
    <h3 style="color: var(--primary-green); margin-bottom: 20px; font-size: 1.3rem; display: flex; align-items: center; gap: 12px;">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="height: 24px; width: 24px;">
        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
      </svg>
      Change Password
    </h3>

    <div style="background: white; border-radius: 8px; padding: 24px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);">
      <form method="PATCH" action="{{ route('student.profile.password.update') }}" style="display: grid; gap: 20px;">
        @csrf
        @method('PATCH')

        <!-- Current Password -->
        <div style="display: grid; gap: 8px;">
          <label for="current_password" style="font-weight: 600; color: #333;">Current Password</label>
          <input id="current_password" name="current_password" type="password" required
                 style="padding: 12px 16px; border: 2px solid #e1e5e9; border-radius: 8px; font-size: 14px; transition: border-color 0.3s ease;"
                 placeholder="Enter your current password">
          @error('current_password')
            <span style="color: #dc3545; font-size: 14px;">{{ $message }}</span>
          @enderror
        </div>

        <!-- New Password -->
        <div style="display: grid; gap: 8px;">
          <label for="password" style="font-weight: 600; color: #333;">New Password</label>
          <input id="password" name="password" type="password" required
                 style="padding: 12px 16px; border: 2px solid #e1e5e9; border-radius: 8px; font-size: 14px; transition: border-color 0.3s ease;"
                 placeholder="Enter your new password">
          @error('password')
            <span style="color: #dc3545; font-size: 14px;">{{ $message }}</span>
          @enderror
        </div>

        <!-- Confirm New Password -->
        <div style="display: grid; gap: 8px;">
          <label for="password_confirmation" style="font-weight: 600; color: #333;">Confirm New Password</label>
          <input id="password_confirmation" name="password_confirmation" type="password" required
                 style="padding: 12px 16px; border: 2px solid #e1e5e9; border-radius: 8px; font-size: 14px; transition: border-color 0.3s ease;"
                 placeholder="Confirm your new password">
        </div>

        <!-- Password Requirements -->
        <div style="background: #f8f9fa; padding: 16px; border-radius: 8px; border-left: 4px solid #6c757d;">
          <h4 style="color: #333; margin: 0 0 12px 0; font-size: 14px; font-weight: 600;">Password Requirements:</h4>
          <ul style="margin: 0; padding-left: 20px; color: #666; font-size: 14px;">
            <li>At least 8 characters long</li>
            <li>Mix of uppercase and lowercase letters</li>
            <li>At least one number</li>
            <li>At least one special character</li>
          </ul>
        </div>

        <!-- Forgot Password Help -->
        <div style="background: #fff3cd; padding: 16px; border-radius: 8px; border-left: 4px solid #ffc107; margin-top: 16px;">
          <h4 style="color: #856404; margin: 0 0 8px 0; font-size: 14px; font-weight: 600;">💡 Forgot Your Password?</h4>
          <p style="color: #856404; margin: 0; font-size: 14px;">
            If you can't remember your current password, you can use the
            <a href="{{ route('password.request') }}" style="color: #856404; text-decoration: underline; font-weight: 600;">
              "Forgot Password"
            </a>
            link on the login page to reset it via email.
          </p>
        </div>

        <!-- Submit Button -->
        <div style="display: flex; justify-content: flex-end; margin-top: 20px;">
          <button type="submit" class="btn-primary" style="display: flex; align-items: center; gap: 8px;">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="height: 20px; width: 20px;">
              <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
            </svg>
            Update Password
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- JavaScript for form interactions -->
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Add focus effects to form inputs
      const inputs = document.querySelectorAll('input[type="password"]');
      inputs.forEach(input => {
        input.addEventListener('focus', function() {
          this.style.borderColor = 'var(--primary-green)';
          this.style.boxShadow = '0 0 0 3px rgba(0, 176, 80, 0.1)';
        });

        input.addEventListener('blur', function() {
          this.style.borderColor = '#e1e5e9';
          this.style.boxShadow = 'none';
        });
      });
    });

    // Profile Edit Functions
    function toggleProfileEdit() {
      const profileDisplay = document.getElementById('profile-display');
      const profileEditForm = document.getElementById('profile-edit-form');
      const profileEditBtn = document.getElementById('profile-edit-btn');

      if (profileEditForm.style.display === 'none') {
        profileDisplay.style.display = 'none';
        profileEditForm.style.display = 'block';
        profileEditBtn.style.display = 'none';
      } else {
        profileDisplay.style.display = 'grid';
        profileEditForm.style.display = 'none';
        profileEditBtn.style.display = 'flex';
      }
    }

    // Graduation Status Functions
    function setGraduationStatus() {
      const today = new Date().toISOString().split('T')[0];
      const graduationDate = prompt('Enter your expected graduation date (YYYY-MM-DD):', today);

      if (graduationDate && graduationDate >= today) {
        updateGraduationStatus(true, graduationDate);
      } else if (graduationDate) {
        alert('Graduation date must be today or in the future.');
      }
    }

    function updateGraduationDate() {
      const today = new Date().toISOString().split('T')[0];
      const currentDate = '{{ $student->graduation_date ? $student->graduation_date->format("Y-m-d") : "" }}';
      const newDate = prompt('Enter new graduation date (YYYY-MM-DD):', currentDate || today);

      if (newDate && newDate >= today) {
        updateGraduationStatus(true, newDate);
      } else if (newDate) {
        alert('Graduation date must be today or in the future.');
      }
    }

    function removeGraduationStatus() {
      if (confirm('Are you sure you want to remove your graduation status? This will clear your graduation date.')) {
        updateGraduationStatus(false, null);
      }
    }

    function convertToAlumni() {
      if (confirm('Are you sure you want to convert your account to alumni status? This action cannot be undone.')) {
        fetch('{{ route("profile.convert.alumni") }}', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
          }
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            alert(data.message);
            location.reload(); // Reload to show updated status
          } else {
            alert('Error: ' + data.message);
          }
        })
        .catch(error => {
          console.error('Error:', error);
          alert('An error occurred while converting to alumni status.');
        });
      }
    }

    function updateGraduationStatus(isGraduating, graduationDate) {
      const data = {
        is_graduating: isGraduating,
        graduation_date: graduationDate
      };

      fetch('{{ route("profile.graduation.update") }}', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify(data)
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          alert(data.message);
          location.reload(); // Reload to show updated status
        } else {
          alert('Error: ' + data.message);
        }
      })
      .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while updating graduation status.');
      });
    }

    // Enhanced Graduation Modal Functions
    function showGraduationModal() {
      const modal = document.getElementById('graduationModal');
      modal.style.display = 'flex';
      setTimeout(() => {
        modal.querySelector('.modal-content').style.transform = 'scale(1)';
        modal.querySelector('.modal-content').style.opacity = '1';
      }, 10);
    }

    function closeGraduationModal() {
      const modal = document.getElementById('graduationModal');
      modal.querySelector('.modal-content').style.transform = 'scale(0.9)';
      modal.querySelector('.modal-content').style.opacity = '0';
      setTimeout(() => {
        modal.style.display = 'none';
      }, 300);
    }

    function proceedWithGraduation() {
      const graduationDateInput = document.getElementById('graduationDateInput');
      const graduationDate = graduationDateInput.value;
      const today = new Date().toISOString().split('T')[0];

      if (!graduationDate) {
        showToast('Please select your expected graduation date.', 'error');
        return;
      }

      if (graduationDate < today) {
        showToast('Graduation date must be today or in the future.', 'error');
        return;
      }

      closeGraduationModal();
      updateGraduationStatus(true, graduationDate);
    }

    function showToast(message, type = 'info') {
      // Create toast notification
      const toast = document.createElement('div');
      toast.className = `toast toast-${type}`;
      toast.innerHTML = `
        <div class="toast-content">
          <span class="toast-icon">${type === 'error' ? '⚠️' : 'ℹ️'}</span>
          <span class="toast-message">${message}</span>
        </div>
      `;
      
      // Add toast styles
      toast.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 10000;
        background: ${type === 'error' ? '#dc3545' : '#17a2b8'};
        color: white;
        padding: 16px 20px;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        transform: translateX(100%);
        transition: transform 0.3s ease;
      `;

      document.body.appendChild(toast);
      
      // Animate in
      setTimeout(() => {
        toast.style.transform = 'translateX(0)';
      }, 10);

      // Remove after delay
      setTimeout(() => {
        toast.style.transform = 'translateX(100%)';
        setTimeout(() => {
          document.body.removeChild(toast);
        }, 300);
      }, 3000);
    }

    // Close modal when clicking outside
    document.addEventListener('click', function(e) {
      const modal = document.getElementById('graduationModal');
      if (e.target === modal) {
        closeGraduationModal();
      }
    });

    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape') {
        closeGraduationModal();
      }
    });
  </script>

  <!-- Graduation Confirmation Modal -->
  <div id="graduationModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.6); z-index: 9999; align-items: center; justify-content: center; backdrop-filter: blur(4px);">
    <div class="modal-content" style="background: white; border-radius: 16px; padding: 0; max-width: 520px; width: 90%; max-height: 90vh; overflow-y: auto; box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3); transform: scale(0.9); opacity: 0; transition: all 0.3s ease;">
      
      <!-- Modal Header -->
      <div style="background: linear-gradient(135deg, var(--primary-green) 0%, var(--dark-green) 100%); color: white; padding: 24px; border-radius: 16px 16px 0 0; text-align: center;">
        <div style="width: 80px; height: 80px; background: rgba(255, 255, 255, 0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px;">
          <x-icon name="success" size="40" style="color: white;" />
        </div>
        <h3 style="margin: 0; font-size: 24px; font-weight: 700;">Mark as Graduating</h3>
        <p style="margin: 8px 0 0 0; opacity: 0.9; font-size: 16px;">Prepare for your transition to alumni status</p>
      </div>

      <!-- Modal Body -->
      <div style="padding: 32px;">
        <div style="text-align: center; margin-bottom: 24px;">
          <p style="margin: 0 0 16px 0; font-size: 16px; color: #374151; line-height: 1.6;">
            By marking yourself as graduating, you're indicating that you're in your final semester and preparing for graduation. This will help the system track your progress and prepare your transition to alumni status.
          </p>
        </div>

        <!-- Graduation Date Input -->
        <div style="margin-bottom: 24px;">
          <label for="graduationDateInput" style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151; font-size: 14px;">
            Expected Graduation Date
          </label>
          <input type="date" 
                 id="graduationDateInput" 
                 style="width: 100%; padding: 12px 16px; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 16px; transition: all 0.3s ease;"
                 onfocus="this.style.borderColor='var(--primary-green)'; this.style.boxShadow='0 0 0 3px rgba(0, 176, 80, 0.1)'"
                 onblur="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none'"
                 min="{{ date('Y-m-d') }}">
        </div>

        <!-- Important Information -->
        <div style="background: #f3f4f6; padding: 20px; border-radius: 12px; border-left: 4px solid var(--primary-green); margin-bottom: 24px;">
          <h4 style="color: var(--primary-green); margin: 0 0 12px 0; font-size: 16px; font-weight: 600; display: flex; align-items: center; gap: 8px;">
            <x-icon name="info" size="20" />
            Important Notes
          </h4>
          <ul style="margin: 0; padding-left: 20px; color: #374151; font-size: 14px; line-height: 1.6;">
            <li>This action can be reversed if needed</li>
            <li>On your graduation date, you can convert to alumni status</li>
            <li>Alumni accounts have different features and permissions</li>
            <li>Your academic records will be preserved</li>
          </ul>
        </div>
      </div>

      <!-- Modal Footer -->
      <div style="padding: 0 32px 32px 32px;">
        <div style="display: flex; gap: 12px; justify-content: center;">
          <button onclick="closeGraduationModal()" 
                  class="btn btn-outline-secondary" 
                  style="min-width: 120px;">
            <x-icon name="cancel" size="16" />
            Cancel
          </button>
          <button onclick="proceedWithGraduation()" 
                  class="btn btn-primary" 
                  style="min-width: 120px;">
            <x-icon name="success" size="16" />
            Confirm
          </button>
        </div>
      </div>
    </div>
  </div>

</x-dashboard-layout>
