<x-dashboard-layout>
  <x-slot name="roleTitle">Admin</x-slot>

  <x-slot name="navigation">
    <x-admin-navigation />
  </x-slot>

  <!-- Header Section -->
  <div class="header-section">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
      <div>
        <h1 class="role-title">View Violations</h1>
        <p class="welcome-text">Browse and manage student violations</p>
        <div class="accent-line"></div>
      </div>
      <div style="display: flex; gap: 12px; align-items: center;">
        <!-- View Mode Toggle -->
        <a href="{{ route('admin.violation', ['tab' => $activeTab, 'view' => 'individual']) }}"
           class="btn-primary" style="padding: 10px 16px; font-size: 14px; text-decoration: none; display: flex; align-items: center; gap: 8px;">
          <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 17.25v3.375c0 .621-.504 1.125-1.125 1.125h-9.75a1.125 1.125 0 0 1-1.125-1.125V7.875c0-.621.504-1.125 1.125-1.125H6.75a9.06 9.06 0 0 1 1.5.124m7.5 10.376h3.375c.621 0 1.125-.504 1.125-1.125V11.25c0-4.46-3.243-8.161-7.5-8.876a9.06 9.06 0 0 0-1.5-.124H9.375c-.621 0-1.125.504-1.125 1.125v3.5m7.5 10.375H9.375a1.125 1.125 0 0 1-1.125-1.125v-9.25m12 6.625v-1.875a3.375 3.375 0 0 0-3.375-3.375h-1.5a1.125 1.125 0 0 1-1.125-1.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H9.75" />
          </svg>
          Individual View
        </a>
        <a href="{{ route('admin.violation', ['tab' => $activeTab, 'view' => 'grouped']) }}"
           class="btn-secondary" style="padding: 10px 16px; font-size: 14px; text-decoration: none; display: flex; align-items: center; gap: 8px;">
          <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" />
          </svg>
          Grouped View
        </a>
      </div>
    </div>
  </div>

  <!-- Tab Navigation -->
  <div style="background: white; border-radius: 12px 12px 0 0; box-shadow: 0 2px 8px rgba(0,0,0,0.1); margin-top: 24px;">
    <div style="display: flex; border-bottom: 2px solid #e9ecef;">
      <a href="{{ route('admin.violation', ['tab' => 'all']) }}"
         style="padding: 16px 24px; text-decoration: none; font-weight: 600; border-bottom: 3px solid {{ $activeTab === 'all' ? 'var(--primary-green)' : 'transparent' }}; color: {{ $activeTab === 'all' ? 'var(--primary-green)' : '#6c757d' }}; transition: all 0.3s ease; display: flex; align-items: center; gap: 8px;">
        <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3-7.5H21m-4.5 0H21m-4.5 0h4.5V3a2.25 2.25 0 0 0-2.25-2.25H5.25A2.25 2.25 0 0 0 3 5.25v13.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
        </svg>
        All Violations ({{ $violations['all']->total() }})
      </a>
      <a href="{{ route('admin.violation', ['tab' => 'minor']) }}"
         style="padding: 16px 24px; text-decoration: none; font-weight: 600; border-bottom: 3px solid {{ $activeTab === 'minor' ? 'var(--primary-green)' : 'transparent' }}; color: {{ $activeTab === 'minor' ? 'var(--primary-green)' : '#6c757d' }}; transition: all 0.3s ease; display: flex; align-items: center; gap: 8px;">
        <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
        </svg>
        Minor Violations ({{ $violations['minor']->total() }})
      </a>
      <a href="{{ route('admin.violation', ['tab' => 'major']) }}"
         style="padding: 16px 24px; text-decoration: none; font-weight: 600; border-bottom: 3px solid {{ $activeTab === 'major' ? 'var(--primary-green)' : 'transparent' }}; color: {{ $activeTab === 'major' ? 'var(--primary-green)' : '#6c757d' }}; transition: all 0.3s ease; display: flex; align-items: center; gap: 8px;">
        <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
        </svg>
        Major Violations ({{ $violations['major']->total() }})
      </a>
    </div>
  </div>

  <!-- Main Content -->
  <div class="header-section">


    @include('shared.alerts.flash')

    <!-- Search Form -->
    <form method="GET" action="{{ route('admin.violationsearch') }}" style="margin-bottom: 0; padding: 24px; background: white; border-radius: 0 0 12px 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); border-top: 1px solid #e9ecef;">
      <!-- Hidden field to maintain active tab -->
      <input type="hidden" name="tab" value="{{ $activeTab }}">

      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 20px;">
        <div>
          <label for="search" style="display: block; margin-bottom: 8px; font-weight: 600; color: #333;">Student Name or ID</label>
          <input type="text" id="search" name="search"
                 style="width: 100%; padding: 12px 16px; border: 2px solid #e1e5e9; border-radius: 8px; font-size: 14px; transition: border-color 0.3s ease;"
                 value="{{ old('search', request('search')) }}"
                 placeholder="Enter name or student ID">
        </div>
        <div>
          <label for="course" style="display: block; margin-bottom: 8px; font-weight: 600; color: #333;">Course</label>
          <input type="text" id="course" name="course"
                 style="width: 100%; padding: 12px 16px; border: 2px solid #e1e5e9; border-radius: 8px; font-size: 14px; transition: border-color 0.3s ease;"
                 value="{{ old('course', request('course')) }}"
                 placeholder="Enter Course">
        </div>
        <div>
          <label for="offense_type" style="display: block; margin-bottom: 8px; font-weight: 600; color: #333;">Violation Type</label>
          <select id="offense_type" name="offense_type"
                  style="width: 100%; padding: 12px 16px; border: 2px solid #e1e5e9; border-radius: 8px; font-size: 14px; transition: border-color 0.3s ease;">
            <option value="">-- All Types --</option>
            <option value="minor" {{ request('offense_type') == 'minor' ? 'selected' : '' }}>Minor</option>
            <option value="major" {{ request('offense_type') == 'major' ? 'selected' : '' }}>Major</option>
          </select>
        </div>
        <div>
          <label for="status" style="display: block; margin-bottom: 8px; font-weight: 600; color: #333;">Status</label>
          <select id="status" name="status"
                  style="width: 100%; padding: 12px 16px; border: 2px solid #e1e5e9; border-radius: 8px; font-size: 14px; transition: border-color 0.3s ease;">
            <option value="">-- All Statuses --</option>
            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Resolved</option>
          </select>
        </div>
      </div>
      <div style="display: flex; gap: 12px;">
        <button type="submit" class="btn-primary" style="display: flex; align-items: center; gap: 8px;">
          <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
          </svg>
          Search
        </button>
        <a href="{{ route('admin.violation', ['tab' => $activeTab]) }}" class="btn-secondary" style="display: flex; align-items: center; gap: 8px; text-decoration: none;">
          <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
          </svg>
          Clear
        </a>
      </div>
    </form>

    <!-- Violations Table -->
    @php
      $currentViolations = $violations[$activeTab];
    @endphp

    @if($currentViolations->isEmpty())
    <div style="text-align: center; padding: 48px; background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); margin-top: 24px;">
      <svg style="width: 64px; height: 64px; margin: 0 auto 16px; color: #9ca3af;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
      </svg>
      <h3 style="margin: 0 0 8px; color: #374151; font-size: 1.25rem;">No {{ ucfirst($activeTab) }} Violations Found</h3>
      <p style="margin: 0; color: #6b7280;">There are currently no {{ $activeTab === 'all' ? '' : $activeTab }} student violations to display.</p>
    </div>
    @else
    <div style="background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); overflow: hidden; margin-top: 24px;">
      <div style="padding: 24px; border-bottom: 1px solid #e9ecef;">
        <h2 style="margin: 0; color: var(--primary-green); font-size: 1.25rem; font-weight: 600;">
          {{ ucfirst($activeTab) }} Violations
          @if($activeTab !== 'all')
            ({{ $currentViolations->total() }} {{ $activeTab }} violation{{ $currentViolations->total() !== 1 ? 's' : '' }})
          @else
            ({{ $currentViolations->total() }} total violation{{ $currentViolations->total() !== 1 ? 's' : '' }})
          @endif
        </h2>
      </div>
      <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;">
          <thead>
            <tr style="background: white; color: black; border-bottom: 2px solid #e5e7eb;">
              <th style="padding: 16px; text-align: left; font-weight: 600; color: black; font-size: 14px; width: 30%;">Student Name</th>
              <th style="padding: 16px; text-align: left; font-weight: 600; color: black; font-size: 14px; width: 50%;">Violation</th>
              <th style="padding: 16px; text-align: center; font-weight: 600; color: black; font-size: 14px; width: 20%;">Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach($currentViolations as $student)
            <tr style="border-bottom: 1px solid #e9ecef; transition: background-color 0.2s ease;"
                onmouseover="this.style.backgroundColor='#f8f9fa'"
                onmouseout="this.style.backgroundColor='transparent'">
              
              <!-- Student Name Column -->
              <td style="padding: 16px; color: #495057; font-size: 14px;">
                <div style="font-weight: 600; color: #333; margin-bottom: 2px;">{{ $student->first_name }} {{ $student->last_name }}</div>
                <div style="font-size: 12px; color: #888; margin-bottom: 1px;">ID: {{ $student->student_id }}</div>
                <div style="font-size: 12px; color: #888;">{{ $student->course ?? 'N/A' }}</div>
              </td>

              <!-- Violation Column -->
              <td style="padding: 20px 16px; color: #495057; font-size: 14px;">
                <div style="display: flex; align-items: flex-start; gap: 10px;">
                  @if($student->offense_type === 'minor')
                    <span style="display: inline-block; padding: 4px 8px; background: #ffc107; color: #333; border-radius: 12px; font-size: 11px; font-weight: 600; text-transform: uppercase; flex-shrink: 0;">
                      Minor
                    </span>
                  @else
                    <span style="display: inline-block; padding: 4px 8px; background: #dc3545; color: white; border-radius: 12px; font-size: 11px; font-weight: 600; text-transform: uppercase; flex-shrink: 0;">
                      Major
                    </span>
                  @endif
                  <div style="flex: 1;">
                    <div style="font-weight: 600; color: #333; margin-bottom: 4px; line-height: 1.4;">{{ $student->violation }}</div>
                    <div style="font-size: 12px; color: #666;">
                      @if($student->status == 2)
                        <span style="color: #10b981; font-weight: 500; display: inline-flex; align-items: center; gap: 4px;"><svg style="width:12px;height:12px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg> Case Closed</span>
                      @elseif($student->status == 1.5 && $student->offense_type == 'major')
                        <span style="color: #e17055; font-weight: 500; display: inline-flex; align-items: center; gap: 4px;"><svg style="width:12px;height:12px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99"/></svg> Ready for Admin Closure</span>
                      @elseif($student->status == 1)
                        <span style="color: #3b82f6; font-weight: 500; display: inline-flex; align-items: center; gap: 4px;"><svg style="width:12px;height:12px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg> Dean Approved</span>
                      @elseif($student->status == 0 && $student->offense_type == 'major')
                        <span style="color: #f59e0b; font-weight: 500; display: inline-flex; align-items: center; gap: 4px;"><svg style="width:12px;height:12px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2M9 5a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2M9 5a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2m-6 9 2 2 4-4"/></svg> Awaiting Proceedings</span>
                      @else
                        <span style="color: #fbbf24; font-weight: 500; display: inline-flex; align-items: center; gap: 4px;"><svg style="width:12px;height:12px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg> Pending</span>
                      @endif
                    </div>
                  </div>
                </div>
              </td>

              <!-- Actions Column -->
              <td style="padding: 20px 16px; text-align: center;">
                <div style="display: flex; gap: 8px; justify-content: center; align-items: center;">
                  <!-- View Button -->
                  <button onclick="viewViolationModal('{{ $student->id }}')" 
                          style="background: #6b7280; color: white; border: none; padding: 8px 12px; border-radius: 6px; font-size: 12px; cursor: pointer; display: flex; align-items: center; gap: 6px; transition: all 0.2s ease;"
                          onmouseover="this.style.background='#4b5563'"
                          onmouseout="this.style.background='#6b7280'"
                          title="View violation details">
                    <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                      <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                    </svg>
                    View
                  </button>

                  @if($student->status != 2)
                    @if($student->offense_type === 'minor' && $student->status == 1)
                      <!-- Approve Button for Minor Violations -->
                      <button onclick="openApproveModal('{{ $student->id }}')" 
                              style="background: #10b981; color: white; border: none; padding: 8px 12px; border-radius: 6px; font-size: 12px; cursor: pointer; display: flex; align-items: center; gap: 6px; transition: all 0.2s ease;"
                              onmouseover="this.style.background='#059669'"
                              onmouseout="this.style.background='#10b981'"
                              title="Approve minor violation">
                        <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                        </svg>
                        Approve
                      </button>
                    @elseif($student->offense_type === 'major' && $student->status == 1.5)
                      <!-- Approve Button for Major Violations -->
                      <button onclick="openApproveModal('{{ $student->id }}')" 
                              style="background: #10b981; color: white; border: none; padding: 8px 12px; border-radius: 6px; font-size: 12px; cursor: pointer; display: flex; align-items: center; gap: 6px; transition: all 0.2s ease;"
                              onmouseover="this.style.background='#059669'"
                              onmouseout="this.style.background='#10b981'"
                              title="Close major violation case">
                        <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                        Approve
                      </button>
                    @endif


                  @endif
                </div>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
    @endif

      <!-- Pagination -->
      @if($currentViolations->hasPages())
      <div style="padding: 20px; border-top: 1px solid #e9ecef; background: #f8f9fa;">
        <div style="display: flex; justify-content: between; align-items: center; flex-wrap: wrap; gap: 16px;">
          <div style="color: #6c757d; font-size: 14px;">
            Showing {{ $currentViolations->firstItem() }} to {{ $currentViolations->lastItem() }} of {{ $currentViolations->total() }} {{ $activeTab === 'all' ? '' : $activeTab }} violations
          </div>
          <div style="display: flex; gap: 8px;">
            @if($currentViolations->onFirstPage())
              <span style="padding: 8px 12px; background: #e9ecef; color: #6c757d; border-radius: 4px; font-size: 14px;">Previous</span>
            @else
              <a href="{{ $currentViolations->appends(request()->query())->previousPageUrl() }}" style="padding: 8px 12px; background: var(--primary-green); color: white; border-radius: 4px; font-size: 14px; text-decoration: none;">Previous</a>
            @endif

            @if($currentViolations->hasMorePages())
              <a href="{{ $currentViolations->appends(request()->query())->nextPageUrl() }}" style="padding: 8px 12px; background: var(--primary-green); color: white; border-radius: 4px; font-size: 14px; text-decoration: none;">Next</a>
            @else
              <span style="padding: 8px 12px; background: #e9ecef; color: #6c757d; border-radius: 4px; font-size: 14px;">Next</span>
            @endif
          </div>
        </div>
      </div>
      @endif
    </div>
  </div>

  <!-- Violation Details Modal -->
  <div id="violationDetailsModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; justify-content: center; align-items: center;">
    <div style="background: white; border-radius: 12px; padding: 24px; max-width: 800px; width: 90%; max-height: 90%; overflow-y: auto; box-shadow: 0 20px 60px rgba(0,0,0,0.3);">
      <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 2px solid #e5e7eb; padding-bottom: 16px;">
        <h3 style="margin: 0; color: var(--primary-green); font-size: 1.5rem; font-weight: 600;">Violation Details</h3>
        <button onclick="closeViolationModal()" 
                style="background: none; border: none; font-size: 24px; color: #6b7280; cursor: pointer; padding: 4px;"
                onmouseover="this.style.color='#374151'"
                onmouseout="this.style.color='#6b7280'">×</button>
      </div>
      
      <div id="violationDetailsContent">
        <!-- Content will be loaded here -->
      </div>
    </div>
  </div>

  <!-- Approve Violation Modal -->
  <div id="approveViolationModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; justify-content: center; align-items: center;">
    <div style="background: white; border-radius: 12px; padding: 24px; max-width: 500px; width: 90%; box-shadow: 0 20px 60px rgba(0,0,0,0.3);">
      <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 2px solid #e5e7eb; padding-bottom: 16px;">
        <h3 style="margin: 0; color: #10b981; font-size: 1.5rem; font-weight: 600;">Approve Violation</h3>
        <button onclick="closeApproveModal()" 
                style="background: none; border: none; font-size: 24px; color: #6b7280; cursor: pointer; padding: 4px;"
                onmouseover="this.style.color='#374151'"
                onmouseout="this.style.color='#6b7280'">×</button>
      </div>
      
      <div id="approveViolationContent">
        <!-- Content will be loaded here -->
      </div>
    </div>
  </div>



<script>
  // Function to view violation details
  function viewViolationDetails(violationId) {
    const modal = document.getElementById('violationDetailsModal');
    const content = document.getElementById('violationDetailsContent');
    
    // Show loading state
    content.innerHTML = '<div style="text-align: center; padding: 40px;"><div style="color: #6b7280;">Loading violation details...</div></div>';
    modal.style.display = 'flex';
    
    // Fetch violation details
    fetch(`/admin/violation-details/${violationId}`)
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          const violation = data.violation;
          content.innerHTML = `
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 24px;">
              <div>
                <h4 style="margin: 0 0 12px; color: #374151; font-size: 1rem; font-weight: 600;">Student Information</h4>
                <div style="background: #f9fafb; padding: 16px; border-radius: 8px; border: 1px solid #e5e7eb;">
                  <div style="margin-bottom: 8px;"><strong>Name:</strong> ${violation.first_name} ${violation.last_name}</div>
                  <div style="margin-bottom: 8px;"><strong>Student ID:</strong> ${violation.student_id}</div>
                  <div style="margin-bottom: 8px;"><strong>Department:</strong> ${violation.department || 'N/A'}</div>
                  <div style="margin-bottom: 8px;"><strong>Course:</strong> ${violation.course || 'N/A'}</div>
                </div>
              </div>
              
              <div>
                <h4 style="margin: 0 0 12px; color: #374151; font-size: 1rem; font-weight: 600;">Violation Information</h4>
                <div style="background: #f9fafb; padding: 16px; border-radius: 8px; border: 1px solid #e5e7eb;">
                  <div style="margin-bottom: 8px;"><strong>Reference Number:</strong> ${violation.ref_num || 'N/A'}</div>
                  <div style="margin-bottom: 8px;">
                    <strong>Type:</strong> 
                    <span style="display: inline-block; padding: 4px 8px; background: ${violation.offense_type === 'minor' ? '#ffc107' : '#dc3545'}; color: ${violation.offense_type === 'minor' ? '#333' : 'white'}; border-radius: 12px; font-size: 12px; font-weight: 600; text-transform: uppercase; margin-left: 4px;">
                      ${violation.offense_type}
                    </span>
                  </div>
                  <div style="margin-bottom: 8px;"><strong>Date Reported:</strong> ${new Date(violation.created_at).toLocaleDateString()}</div>
                  <div style="margin-bottom: 8px;"><strong>Issued By:</strong> ${violation.added_by || 'Unknown'}</div>
                </div>
              </div>
            </div>
            
            <div style="margin-bottom: 24px;">
              <h4 style="margin: 0 0 12px; color: #374151; font-size: 1rem; font-weight: 600;">Violation Details</h4>
              <div style="background: #f9fafb; padding: 16px; border-radius: 8px; border: 1px solid #e5e7eb;">
                <div style="font-weight: 600; color: #333; margin-bottom: 8px; font-size: 15px;">${violation.violation}</div>
                ${violation.description ? `<div style="color: #6b7280; line-height: 1.5;">${violation.description}</div>` : '<div style="color: #9ca3af; font-style: italic;">No additional description provided.</div>'}
              </div>
            </div>
            
            <div style="margin-bottom: 24px;">
              <h4 style="margin: 0 0 12px; color: #374151; font-size: 1rem; font-weight: 600;">Status Information</h4>
              <div style="background: #f9fafb; padding: 16px; border-radius: 8px; border: 1px solid #e5e7eb;">
                <div style="margin-bottom: 8px;">
                  <strong>Current Status:</strong> 
                  <span id="statusBadge" style="display: inline-block; margin-left: 8px; padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 500;">
                    ${getStatusDisplay(violation.status, violation.offense_type)}
                  </span>
                </div>
                ${violation.forwarded_to_admin_at ? `<div style="margin-bottom: 8px;"><strong>Forwarded to Admin:</strong> ${new Date(violation.forwarded_to_admin_at).toLocaleDateString()}</div>` : ''}
                ${violation.closed_at ? `<div style="margin-bottom: 8px;"><strong>Case Closed:</strong> ${new Date(violation.closed_at).toLocaleDateString()}</div>` : ''}
              </div>
            </div>
            
            ${violation.document_path ? `
              <div style="margin-bottom: 24px;">
                <h4 style="margin: 0 0 12px; color: #374151; font-size: 1rem; font-weight: 600;">Documents</h4>
                <div style="background: #f9fafb; padding: 16px; border-radius: 8px; border: 1px solid #e5e7eb; display: flex; gap: 16px; flex-wrap: wrap;">
                  <a href="/files/${violation.document_path}" target="_blank" 
                     style="display: inline-flex; align-items: center; gap: 8px; color: #3b82f6; text-decoration: none; font-weight: 500;">
                    <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                    </svg>
                    View Proceedings Document
                  </a>
                  <a href="/admin/violation/${violation.id}/download-proceedings" 
                     style="display: inline-flex; align-items: center; gap: 8px; color: #059669; text-decoration: none; font-weight: 500;">
                    <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                    </svg>
                    Download
                  </a>
                </div>
              </div>
            ` : ''}
          `;
        } else {
          content.innerHTML = '<div style="text-align: center; padding: 40px; color: #ef4444;">Error loading violation details.</div>';
        }
      })
      .catch(error => {
        console.error('Error fetching violation details:', error);
        content.innerHTML = '<div style="text-align: center; padding: 40px; color: #ef4444;">Error loading violation details.</div>';
      });
  }

  function closeViolationModal() {
    document.getElementById('violationDetailsModal').style.display = 'none';
  }

  function getStatusDisplay(status, offenseType) {
    if (status == 2) {
      return '<span style="background: #10b981; color: white; display:inline-flex; align-items:center; gap:4px; padding:2px 6px; border-radius:4px;"><svg style="width:11px;height:11px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg> Case Closed</span>';
    } else if (status == 1.5 && offenseType == 'major') {
      return '<span style="background: #e17055; color: white; display:inline-flex; align-items:center; gap:4px; padding:2px 6px; border-radius:4px;"><svg style="width:11px;height:11px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99"/></svg> Ready for Admin Closure</span>';
    } else if (status == 1) {
      return '<span style="background: #3b82f6; color: white; display:inline-flex; align-items:center; gap:4px; padding:2px 6px; border-radius:4px;"><svg style="width:11px;height:11px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg> Dean Approved</span>';
    } else if (status == 0 && offenseType == 'major') {
      return '<span style="background: #f59e0b; color: white; display:inline-flex; align-items:center; gap:4px; padding:2px 6px; border-radius:4px;"><svg style="width:11px;height:11px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2M9 5a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2M9 5a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2m-6 9 2 2 4-4"/></svg> Awaiting Proceedings</span>';
    } else {
      return '<span style="background: #fbbf24; color: #111827; display:inline-flex; align-items:center; gap:4px; padding:2px 6px; border-radius:4px;"><svg style="width:11px;height:11px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg> Pending</span>';
    }
  }

  // Close modal when clicking outside
  document.getElementById('violationDetailsModal').addEventListener('click', function(e) {
    if (e.target === this) {
      closeViolationModal();
    }
  });

  // Close modal with Escape key
  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
      closeViolationModal();
      closeApproveModal();
    }
  });

  // Updated function name for consistency
  function viewViolationModal(violationId) {
    viewViolationDetails(violationId);
  }

  // Approve Violation Modal Functions
  function openApproveModal(violationId) {
    const modal = document.getElementById('approveViolationModal');
    const content = document.getElementById('approveViolationContent');
    
    // Show loading state
    content.innerHTML = '<div style="text-align: center; padding: 40px;"><div style="color: #6b7280;">Loading violation data...</div></div>';
    modal.style.display = 'flex';
    
    // Fetch violation details for approval confirmation
    fetch(`/admin/violation-details/${violationId}`)
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          const violation = data.violation;
          const isMinor = violation.offense_type === 'minor';
          const actionText = isMinor ? 'approve this Dean-approved minor violation' : 'close this major violation case';
          const resultText = isMinor ? 'marked as fully resolved' : 'marked as case closed';
          
          content.innerHTML = `
            <div style="text-align: center; padding: 20px;">
              <svg style="width: 64px; height: 64px; margin: 0 auto 16px; color: #10b981;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              <h3 style="margin: 0 0 16px; color: #10b981; font-size: 1.25rem;">${isMinor ? 'Approve Violation' : 'Close Case'}</h3>
              <p style="margin: 0 0 24px; color: #6b7280;">Are you sure you want to ${actionText}? This violation will be ${resultText}.</p>
              
              <div style="background: #f9fafb; padding: 16px; border-radius: 8px; margin-bottom: 24px; text-align: left;">
                <div style="margin-bottom: 8px;"><strong>Student:</strong> ${violation.first_name} ${violation.last_name} (${violation.student_id})</div>
                <div style="margin-bottom: 8px;"><strong>Violation:</strong> ${violation.violation}</div>
                <div style="margin-bottom: 8px;"><strong>Type:</strong> ${violation.offense_type.charAt(0).toUpperCase() + violation.offense_type.slice(1)}</div>
                <div><strong>Current Status:</strong> ${getStatusText(violation.status, violation.offense_type)}</div>
              </div>
              
              <form method="POST" action="/admin/violation/${violationId}/close-case" style="display: inline;">
                @csrf
                <div style="display: flex; gap: 12px; justify-content: center;">
                  <button type="button" onclick="closeApproveModal()" class="btn-secondary">Cancel</button>
                  <button type="submit" style="background: #10b981; color: white; border: none; padding: 12px 20px; border-radius: 8px; font-weight: 600; cursor: pointer; transition: all 0.2s ease;" onmouseover="this.style.background='#059669'" onmouseout="this.style.background='#10b981'">${isMinor ? 'Approve' : 'Close Case'}</button>
                </div>
              </form>
            </div>
          `;
        } else {
          content.innerHTML = '<div style="text-align: center; padding: 40px; color: #ef4444;">Error loading violation data.</div>';
        }
      })
      .catch(error => {
        console.error('Error fetching violation details:', error);
        content.innerHTML = '<div style="text-align: center; padding: 40px; color: #ef4444;">Error loading violation data.</div>';
      });
  }

  function closeApproveModal() {
    document.getElementById('approveViolationModal').style.display = 'none';
  }



  function getStatusText(status, offenseType) {
    if (status == 2) {
      return 'Case Closed';
    } else if (status == 1.5 && offenseType == 'major') {
      return 'Ready for Admin Closure';
    } else if (status == 1) {
      return 'Dean Approved';
    } else if (status == 0 && offenseType == 'major') {
      return 'Awaiting Proceedings';
    } else {
      return 'Pending';
    }
  }

  // Close modals when clicking outside
  document.getElementById('approveViolationModal').addEventListener('click', function(e) {
    if (e.target === this) {
      closeApproveModal();
    }
  });
</script>

<style>
  .btn-primary {
    background: var(--primary-green);
    color: white;
    border: none;
    padding: 12px 20px;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
  }

  .btn-primary:hover {
    background: #059669;
    transform: translateY(-1px);
  }

  .btn-secondary {
    background: #6b7280;
    color: white;
    border: none;
    padding: 12px 20px;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
  }

  .btn-secondary:hover {
    background: #4b5563;
    transform: translateY(-1px);
  }

  .header-section {
    padding: 0 24px;
  }

  .role-title {
    font-size: 32px;
    font-weight: 700;
    margin-bottom: 8px;
    color: var(--primary-green);
  }

  .welcome-text {
    font-size: 16px;
    color: #6b7280;
    margin-bottom: 8px;
  }

  .accent-line {
    width: 60px;
    height: 4px;
    background: linear-gradient(90deg, var(--primary-green) 0%, #34d399 100%);
    border-radius: 2px;
    margin-bottom: 24px;
  }
</style>

</x-dashboard-layout>