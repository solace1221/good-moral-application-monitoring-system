<x-dashboard-layout>
  <x-slot name="roleTitle">Admin</x-slot>

  <x-slot name="navigation">
    <x-admin-navigation />
  </x-slot>

  <!-- Header Section -->
  <div class="header-section">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
      <div>
        <h1 class="role-title">View Violations (Grouped)</h1>
        <p class="welcome-text">Browse and manage student violations - grouped by violation details</p>
        <div class="accent-line"></div>
      </div>
      <div style="display: flex; gap: 12px; align-items: center;">
        <!-- View Mode Toggle -->
        <a href="{{ route('admin.violation', ['tab' => $activeTab, 'view' => 'individual']) }}" 
           class="btn-secondary" style="padding: 10px 16px; font-size: 14px; text-decoration: none; display: flex; align-items: center; gap: 8px;">
          <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 17.25v3.375c0 .621-.504 1.125-1.125 1.125h-9.75a1.125 1.125 0 0 1-1.125-1.125V7.875c0-.621.504-1.125 1.125-1.125H6.75a9.06 9.06 0 0 1 1.5.124m7.5 10.376h3.375c.621 0 1.125-.504 1.125-1.125V11.25c0-4.46-3.243-8.161-7.5-8.876a9.06 9.06 0 0 0-1.5-.124H9.375c-.621 0-1.125.504-1.125 1.125v3.5m7.5 10.375H9.375a1.125 1.125 0 0 1-1.125-1.125v-9.25m12 6.625v-1.875a3.375 3.375 0 0 0-3.375-3.375h-1.5a1.125 1.125 0 0 1-1.125-1.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H9.75" />
          </svg>
          Individual View
        </a>
        <a href="{{ route('admin.violation', ['tab' => $activeTab, 'view' => 'grouped']) }}" 
           class="btn-primary" style="padding: 10px 16px; font-size: 14px; text-decoration: none; display: flex; align-items: center; gap: 8px;">
          <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" />
          </svg>
          Grouped View
        </a>
      </div>
    </div>
  </div>

  <!-- Search Section -->
  <div style="background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); padding: 24px; margin-bottom: 24px;">
    <h2 style="margin: 0 0 16px 0; color: var(--primary-green); font-size: 1.1rem; font-weight: 600;">Search Violations</h2>
    <form method="GET" action="{{ route('admin.violationsearch') }}" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; align-items: end;">
      <input type="hidden" name="view" value="grouped">
      <input type="hidden" name="tab" value="{{ $activeTab }}">
      
      <div>
        <label style="display: block; margin-bottom: 4px; font-weight: 500; color: #333;">Reference Number</label>
        <input type="text" name="ref_num" value="{{ request('ref_num') }}" placeholder="Enter reference number" 
               style="width: 100%; padding: 8px 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
      </div>
      
      <div>
        <label style="display: block; margin-bottom: 4px; font-weight: 500; color: #333;">Student ID</label>
        <input type="text" name="student_id" value="{{ request('student_id') }}" placeholder="Enter student ID" 
               style="width: 100%; padding: 8px 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
      </div>
      
      <div>
        <label style="display: block; margin-bottom: 4px; font-weight: 500; color: #333;">Last Name</label>
        <input type="text" name="last_name" value="{{ request('last_name') }}" placeholder="Enter last name" 
               style="width: 100%; padding: 8px 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
      </div>
      
      <div>
        <label style="display: block; margin-bottom: 4px; font-weight: 500; color: #333;">Course</label>
        <input type="text" name="course" value="{{ request('course') }}" placeholder="Enter course" 
               style="width: 100%; padding: 8px 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
      </div>
      
      <div>
        <label style="display: block; margin-bottom: 4px; font-weight: 500; color: #333;">Offense Type</label>
        <select name="offense_type" style="width: 100%; padding: 8px 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
          <option value="">All Types</option>
          <option value="minor" {{ request('offense_type') == 'minor' ? 'selected' : '' }}>Minor</option>
          <option value="major" {{ request('offense_type') == 'major' ? 'selected' : '' }}>Major</option>
        </select>
      </div>
      
      <div style="display: flex; gap: 8px;">
        <button type="submit" class="btn-primary" style="padding: 8px 16px; font-size: 14px; white-space: nowrap; display: flex; align-items: center; gap: 6px;">
          <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
          </svg>
          Search
        </button>
        <a href="{{ route('admin.violation', ['view' => 'grouped', 'tab' => $activeTab]) }}" 
           class="btn-secondary" style="padding: 8px 16px; font-size: 14px; text-decoration: none; white-space: nowrap; display: flex; align-items: center; gap: 6px;">
          <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
          </svg>
          Reset
        </a>
      </div>
    </form>
  </div>

  <!-- Tabs Section -->
  <div style="background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); overflow: hidden; margin-bottom: 24px;">
    <div style="display: flex; border-bottom: 1px solid #e9ecef;">
      <a href="{{ route('admin.violation', ['tab' => 'all', 'view' => 'grouped']) }}" 
         class="tab-link {{ $activeTab === 'all' ? 'active' : '' }}" 
         style="flex: 1; padding: 16px 24px; text-align: center; text-decoration: none; font-weight: 600; border-bottom: 3px solid {{ $activeTab === 'all' ? 'var(--primary-green)' : 'transparent' }}; color: {{ $activeTab === 'all' ? 'var(--primary-green)' : '#6c757d' }}; transition: all 0.3s ease; display: flex; align-items: center; justify-content: center; gap: 8px;">
        <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3-7.5H21m-4.5 0H21m-4.5 0h4.5V3a2.25 2.25 0 0 0-2.25-2.25H5.25A2.25 2.25 0 0 0 3 5.25v13.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
        </svg>
        All Violations ({{ $violations['all']->total() }})
      </a>
      <a href="{{ route('admin.violation', ['tab' => 'minor', 'view' => 'grouped']) }}" 
         class="tab-link {{ $activeTab === 'minor' ? 'active' : '' }}" 
         style="flex: 1; padding: 16px 24px; text-align: center; text-decoration: none; font-weight: 600; border-bottom: 3px solid {{ $activeTab === 'minor' ? 'var(--primary-green)' : 'transparent' }}; color: {{ $activeTab === 'minor' ? 'var(--primary-green)' : '#6c757d' }}; transition: all 0.3s ease; display: flex; align-items: center; justify-content: center; gap: 8px;">
        <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
        </svg>
        Minor Violations ({{ $violations['minor']->total() }})
      </a>
      <a href="{{ route('admin.violation', ['tab' => 'major', 'view' => 'grouped']) }}" 
         class="tab-link {{ $activeTab === 'major' ? 'active' : '' }}" 
         style="flex: 1; padding: 16px 24px; text-align: center; text-decoration: none; font-weight: 600; border-bottom: 3px solid {{ $activeTab === 'major' ? 'var(--primary-green)' : 'transparent' }}; color: {{ $activeTab === 'major' ? 'var(--primary-green)' : '#6c757d' }}; transition: all 0.3s ease; display: flex; align-items: center; justify-content: center; gap: 8px;">
        <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
        </svg>
        Major Violations ({{ $violations['major']->total() }})
      </a>
    </div>
  </div>

  <!-- Violations Table -->
  <div style="background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); overflow: hidden; margin-top: 24px;">
    <div style="padding: 24px; border-bottom: 1px solid #e9ecef;">
      <h2 style="margin: 0; color: var(--primary-green); font-size: 1.25rem; font-weight: 600;">
        {{ ucfirst($activeTab) }} Violations (Grouped)
        @if($activeTab !== 'all')
          ({{ $currentViolations->total() }} {{ $activeTab }} violation group{{ $currentViolations->total() !== 1 ? 's' : '' }})
        @else
          ({{ $currentViolations->total() }} total violation group{{ $currentViolations->total() !== 1 ? 's' : '' }})
        @endif
      </h2>
    </div>
    <div style="overflow-x: auto;">
      <table style="width: 100%; border-collapse: collapse;">
        <thead>
          <tr style="background: white; color: black; border-bottom: 2px solid #e5e7eb;">
            <th style="padding: 16px; text-align: left; font-weight: 600; color: black; font-size: 14px;">Reference Number</th>
            <th style="padding: 16px; text-align: left; font-weight: 600; color: black; font-size: 14px;">Student IDs</th>
            <th style="padding: 16px; text-align: left; font-weight: 600; color: black; font-size: 14px;">Student Names</th>
            <th style="padding: 16px; text-align: left; font-weight: 600; color: black; font-size: 14px;">Courses</th>
            @if($activeTab === 'all')
            <th style="padding: 16px; text-align: left; font-weight: 600; color: black; font-size: 14px;">Violation Type</th>
            @endif
            <th style="padding: 16px; text-align: left; font-weight: 600; color: black; font-size: 14px;">Violation Details</th>
            <th style="padding: 16px; text-align: left; font-weight: 600; color: black; font-size: 14px;">Issued By</th>
            <th style="padding: 16px; text-align: left; font-weight: 600; color: black; font-size: 14px;">Status</th>
            <th style="padding: 16px; text-align: left; font-weight: 600; color: black; font-size: 14px;">Actions</th>
          </tr>
        </thead>
        <tbody>
          @foreach($currentViolations as $violationGroup)
          <tr style="border-bottom: 1px solid #e9ecef; transition: background-color 0.2s ease;"
              onmouseover="this.style.backgroundColor='#f8f9fa'"
              onmouseout="this.style.backgroundColor='transparent'">
            <td style="padding: 16px; color: #495057; font-size: 14px; font-family: monospace; background: #f8f9fa; font-weight: 600;">{{ $violationGroup->ref_num }}</td>
            <td style="padding: 16px; color: #495057; font-size: 14px; font-weight: 500;">
              <div style="max-width: 200px; word-wrap: break-word;">{{ $violationGroup->student_ids }}</div>
              <small style="color: #6c757d;">({{ $violationGroup->student_count }} student{{ $violationGroup->student_count > 1 ? 's' : '' }})</small>
            </td>
            <td style="padding: 16px; color: #495057; font-size: 14px;">
              <div style="max-width: 250px; word-wrap: break-word; font-weight: 500; color: #333;">{{ $violationGroup->student_names }}</div>
            </td>
            <td style="padding: 16px; color: #495057; font-size: 14px;">
              <div style="max-width: 150px; word-wrap: break-word;">
                @foreach(array_unique(explode(', ', $violationGroup->courses)) as $course)
                  <span style="display: inline-block; padding: 2px 6px; background: #e3f2fd; color: #1976d2; border-radius: 4px; font-size: 11px; font-weight: 500; margin: 1px;">
                    {{ $course }}
                  </span>
                @endforeach
              </div>
            </td>
            @if($activeTab === 'all')
            <td style="padding: 16px; color: #495057; font-size: 14px;">
              @if($violationGroup->offense_type === 'minor')
                <span style="display: inline-block; padding: 6px 12px; background: #ffc107; color: #333; border-radius: 20px; font-size: 12px; font-weight: 500; text-transform: uppercase;">
                  Minor
                </span>
              @else
                <span style="display: inline-block; padding: 6px 12px; background: #dc3545; color: white; border-radius: 20px; font-size: 12px; font-weight: 500; text-transform: uppercase;">
                  Major
                </span>
              @endif
            </td>
            @endif
            <td style="padding: 16px; color: #495057; font-size: 14px; max-width: 200px;">
              <div style="font-weight: 600; color: #333; margin-bottom: 4px;">{{ $violationGroup->violation }}</div>
            </td>
            <td style="padding: 16px; color: #495057; font-size: 14px;">
              <div style="display: flex; align-items: center; gap: 8px;">
                <svg style="width: 16px; height: 16px; color: #6c757d;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                </svg>
                <div>
                  <div style="font-weight: 500; color: #495057; font-size: 13px;">{{ $violationGroup->added_by }}</div>
                  <div style="font-size: 11px; color: #6c757d;">{{ $violationGroup->created_at->format('M d, Y') }}</div>
                </div>
              </div>
            </td>
            <td style="padding: 16px; color: #495057; font-size: 14px;">
              @if($violationGroup->status == 2)
                <span style="display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; background: #10b981; color: white; border-radius: 20px; font-size: 12px; font-weight: 500;">
                  <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                  </svg>
                  Case Closed
                </span>
              @elseif($violationGroup->status == 1.5 && $violationGroup->offense_type == 'major')
                <span style="display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; background: #e17055; color: white; border-radius: 20px; font-size: 12px; font-weight: 500;">
                  <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m0-10.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.75c0 5.592 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.57-.598-3.75h-.152c-3.196 0-6.1-1.25-8.25-3.286Zm0 13.036h.008v.008H12v-.008Z" />
                  </svg>
                  Ready for Admin Closure
                </span>
              @elseif($violationGroup->status == 1 && $violationGroup->offense_type == 'minor')
                <span style="display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; background: #3b82f6; color: white; border-radius: 20px; font-size: 12px; font-weight: 500;">
                  <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L5.999 12Zm0 0h7.5" />
                  </svg>
                  Dean Approved - Pending Admin
                </span>
              @elseif($violationGroup->status == 1 && $violationGroup->offense_type == 'major')
                <span style="display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; background: #8b5cf6; color: white; border-radius: 20px; font-size: 12px; font-weight: 500;">
                  <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                  </svg>
                  Proceedings Uploaded
                </span>
              @elseif($violationGroup->status == 0 && $violationGroup->offense_type == 'major')
                <span style="display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; background: #f59e0b; color: white; border-radius: 20px; font-size: 12px; font-weight: 500;">
                  <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                  </svg>
                  Awaiting Proceedings
                </span>
              @else
                <span style="display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; background: #fbbf24; color: #111827; border-radius: 20px; font-size: 12px; font-weight: 500;">
                  <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                  </svg>
                  Pending
                </span>
              @endif
            </td>
            <td style="padding: 16px; color: #495057; font-size: 14px;">
              <div style="display: flex; gap: 8px; align-items: center; flex-wrap: wrap;">
                <a href="{{ route('admin.violation', ['view' => 'individual', 'tab' => $activeTab, 'ref_num' => $violationGroup->ref_num]) }}" 
                   class="btn-secondary" style="padding: 6px 12px; font-size: 12px; text-decoration: none; white-space: nowrap; display: flex; align-items: center; gap: 6px;">
                  <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                  </svg>
                  View Details
                </a>
              </div>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    
    <!-- Pagination -->
    @if($currentViolations->hasPages())
    <div style="padding: 24px; border-top: 1px solid #e9ecef;">
      {{ $currentViolations->appends(request()->query())->links() }}
    </div>
    @endif
  </div>
</x-dashboard-layout>
