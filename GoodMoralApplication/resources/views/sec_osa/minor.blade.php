<x-dashboard-layout>
  <x-slot name="roleTitle">Moderator</x-slot>

  <x-slot name="navigation">
    <x-sec-osa-navigation />
  </x-slot>

  <!-- Header Section -->
  <div class="header-section">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
      <div>
        <h1 class="role-title">Minor Violations</h1>
        <p class="welcome-text">Review and manage minor violations</p>
        <div class="accent-line"></div>
      </div>

      <!-- Statistics Cards -->
      <div style="display: flex; gap: 16px; flex-wrap: wrap;">
        <div style="background: #fff3cd; padding: 12px 16px; border-radius: 8px; border-left: 4px solid #ffc107;">
          <div style="font-size: 24px; font-weight: 700; color: #856404;">{{ $pendingCount ?? 0 }}</div>
          <div style="font-size: 12px; color: #856404; font-weight: 500;">Pending</div>
        </div>
        <div style="background: #d1ecf1; padding: 12px 16px; border-radius: 8px; border-left: 4px solid #17a2b8;">
          <div style="font-size: 24px; font-weight: 700; color: #0c5460;">{{ $approvedCount ?? 0 }}</div>
          <div style="font-size: 12px; color: #0c5460; font-weight: 500;">Dean Approved</div>
        </div>
        <div style="background: #d4edda; padding: 12px 16px; border-radius: 8px; border-left: 4px solid #28a745;">
          <div style="font-size: 24px; font-weight: 700; color: #155724;">{{ $closedCount ?? 0 }}</div>
          <div style="font-size: 12px; color: #155724; font-weight: 500;">Cases Closed</div>
        </div>
      </div>
    </div>
  </div>

    <!-- Enhanced Search and Filter Section -->
    <div style="background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); overflow: hidden; margin-bottom: 24px;">
      <div style="padding: 20px; border-bottom: 1px solid #e9ecef;">
        <h3 style="margin: 0; color: var(--primary-green); font-size: 1.1rem; font-weight: 600;">Search & Filter Minor Violations</h3>
      </div>
      <form method="GET" action="{{ route('sec_osa.searchMinor') }}" style="padding: 24px;">
        <!-- Primary Search Fields -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 16px; margin-bottom: 20px;">
          <div>
            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #495057;">Reference Number</label>
            <input type="text" name="ref_num" value="{{ request('ref_num') }}" placeholder="Enter reference number..."
                   style="width: 100%; padding: 12px 16px; border: 2px solid #e1e5e9; border-radius: 8px; font-size: 14px;">
          </div>
          <div>
            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #495057;">Student ID</label>
            <input type="text" name="student_id" value="{{ request('student_id') }}" placeholder="Enter student ID..."
                   style="width: 100%; padding: 12px 16px; border: 2px solid #e1e5e9; border-radius: 8px; font-size: 14px;">
          </div>
          <div>
            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #495057;">First Name</label>
            <input type="text" name="first_name" value="{{ request('first_name') }}" placeholder="Enter first name..."
                   style="width: 100%; padding: 12px 16px; border: 2px solid #e1e5e9; border-radius: 8px; font-size: 14px;">
          </div>
          <div>
            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #495057;">Last Name</label>
            <input type="text" name="last_name" value="{{ request('last_name') }}" placeholder="Enter last name..."
                   style="width: 100%; padding: 12px 16px; border: 2px solid #e1e5e9; border-radius: 8px; font-size: 14px;">
          </div>
        </div>
        
        <!-- Filter Fields -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 20px; padding-top: 20px; border-top: 1px solid #e9ecef;">
          <div>
            <label style="display: block; margin-bottom: 8px; font-weight: 500; color: #495057;">Department</label>
            <select name="department" style="width: 100%; padding: 12px 16px; border: 2px solid #e1e5e9; border-radius: 8px; font-size: 14px;">
              <option value="">All Departments</option>
              <option value="BSIT" {{ request('department') == 'BSIT' ? 'selected' : '' }}>BSIT</option>
              <option value="BLIS" {{ request('department') == 'BLIS' ? 'selected' : '' }}>BLIS</option>
              <option value="BSCE" {{ request('department') == 'BSCE' ? 'selected' : '' }}>BSCE</option>
              <option value="BSCPE" {{ request('department') == 'BSCPE' ? 'selected' : '' }}>BSCPE</option>
              <option value="BSENSE" {{ request('department') == 'BSENSE' ? 'selected' : '' }}>BSENSE</option>
              <option value="BSN" {{ request('department') == 'BSN' ? 'selected' : '' }}>BSN</option>
              <option value="BSPH" {{ request('department') == 'BSPH' ? 'selected' : '' }}>BSPH</option>
              <option value="BSMT" {{ request('department') == 'BSMT' ? 'selected' : '' }}>BSMT</option>
              <option value="BSPT" {{ request('department') == 'BSPT' ? 'selected' : '' }}>BSPT</option>
              <option value="BSRT" {{ request('department') == 'BSRT' ? 'selected' : '' }}>BSRT</option>
              <option value="BSM" {{ request('department') == 'BSM' ? 'selected' : '' }}>BSM</option>
              <option value="BSA" {{ request('department') == 'BSA' ? 'selected' : '' }}>BSA</option>
              <option value="BSE" {{ request('department') == 'BSE' ? 'selected' : '' }}>BSE</option>
              <option value="BSBAMM" {{ request('department') == 'BSBAMM' ? 'selected' : '' }}>BSBAMM</option>
              <option value="BSBAMFM" {{ request('department') == 'BSBAMFM' ? 'selected' : '' }}>BSBAMFM</option>
              <option value="BSBAMOP" {{ request('department') == 'BSBAMOP' ? 'selected' : '' }}>BSBAMOP</option>
            </select>
          </div>
          <div>
            <label style="display: block; margin-bottom: 8px; font-weight: 500; color: #495057;">Status</label>
            <select name="status" style="width: 100%; padding: 12px 16px; border: 2px solid #e1e5e9; border-radius: 8px; font-size: 14px;">
              <option value="">All Status</option>
              <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Pending</option>
              <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Dean Approved</option>
              <option value="2" {{ request('status') == '2' ? 'selected' : '' }}>Case Closed</option>
            </select>
          </div>
          <div>
            <label style="display: block; margin-bottom: 8px; font-weight: 500; color: #495057;">Violation Count</label>
            <select name="violation_count" style="width: 100%; padding: 12px 16px; border: 2px solid #e1e5e9; border-radius: 8px; font-size: 14px;">
              <option value="">All Counts</option>
              <option value="1" {{ request('violation_count') == '1' ? 'selected' : '' }}>1st Violation</option>
              <option value="2" {{ request('violation_count') == '2' ? 'selected' : '' }}>2nd Violation</option>
              <option value="3" {{ request('violation_count') == '3' ? 'selected' : '' }}>3+ Violations (Critical)</option>
            </select>
          </div>
          <div>
            <label style="display: block; margin-bottom: 8px; font-weight: 500; color: #495057;">Added By</label>
            <input type="text" name="added_by" value="{{ request('added_by') }}" placeholder="Officer name..."
                   style="width: 100%; padding: 12px 16px; border: 2px solid #e1e5e9; border-radius: 8px; font-size: 14px;">
          </div>
          <div>
            <label style="display: block; margin-bottom: 8px; font-weight: 500; color: #495057;">Date From</label>
            <input type="date" name="date_from" value="{{ request('date_from') }}"
                   style="width: 100%; padding: 12px 16px; border: 2px solid #e1e5e9; border-radius: 8px; font-size: 14px;">
          </div>
          <div>
            <label style="display: block; margin-bottom: 8px; font-weight: 500; color: #495057;">Date To</label>
            <input type="date" name="date_to" value="{{ request('date_to') }}"
                   style="width: 100%; padding: 12px 16px; border: 2px solid #e1e5e9; border-radius: 8px; font-size: 14px;">
          </div>
        </div>
        
        <!-- Action Buttons -->
        <div style="display: flex; gap: 12px; align-items: center;">
          <button type="submit" class="btn-primary">Apply Filters</button>
          <a href="{{ route('sec_osa.minor') }}" class="btn-secondary">Clear All</a>
          <div style="margin-left: auto; color: #6c757d; font-size: 14px;">
            @if(request()->hasAny(['ref_num', 'student_id', 'first_name', 'last_name', 'department', 'status', 'violation_count', 'added_by', 'date_from', 'date_to']))
              <span style="background: #007bff; color: white; padding: 4px 8px; border-radius: 12px; font-size: 12px; font-weight: 500;">
                Filters Active
              </span>
            @endif
          </div>
        </div>
      </form>
    </div>

    <!-- Violations Table -->
    <div style="background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); overflow: hidden;">
      @if ($students->isEmpty())
        <div style="padding: 48px; text-align: center; color: #6c757d;">
          <svg style="width: 64px; height: 64px; margin: 0 auto 16px; color: #dee2e6;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
          </svg>
          <p style="margin: 0; font-size: 1.1rem; font-weight: 500;">No minor violations found</p>
          <p style="margin: 8px 0 0; font-size: 0.9rem;">Minor violations will appear here for review</p>
        </div>
      @else
        <table style="width: 100%; border-collapse: collapse;">
          <thead>
            <tr style="background: #f8f9fa; border-bottom: 2px solid #e9ecef;">
              <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057; font-size: 14px;">Reference #</th>
              <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057; font-size: 14px;">Student Info</th>
              <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057; font-size: 14px;">Name</th>
              <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057; font-size: 14px;">Department</th>
              <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057; font-size: 14px;">Violation</th>
              <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057; font-size: 14px;">Issued By</th>
              <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057; font-size: 14px;">Status</th>
              <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057; font-size: 14px;">Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($students as $student)
            <tr style="border-bottom: 1px solid #e9ecef; transition: background-color 0.2s ease;"
                onmouseover="this.style.backgroundColor='#f8f9fa'"
                onmouseout="this.style.backgroundColor='transparent'">
              <td style="padding: 16px; color: #495057; font-size: 14px; font-weight: 500;">
                @if($student->ref_num)
                  <span style="font-family: monospace; background: #f8f9fa; padding: 4px 8px; border-radius: 4px; font-size: 12px;">
                    {{ $student->ref_num }}
                  </span>
                @else
                  <span style="color: #6c757d; font-size: 12px; font-style: italic;">No Reference</span>
                @endif
              </td>
              <td style="padding: 16px; color: #495057; font-size: 14px;">
                <div style="font-weight: 500; margin-bottom: 4px;">{{ $student->student_id }}</div>
                <div style="font-size: 12px; color: #007bff; font-weight: 600;">
                  Year Level: {{ $student->getStudentYearLevel() }}
                </div>
              </td>
              <td style="padding: 16px; color: #495057; font-size: 14px;">
                <div style="font-weight: 500; color: #333; margin-bottom: 4px;">{{ $student->first_name }} {{ $student->last_name }}</div>
                @php
                  // Count ALL minor violations for this student regardless of status
                  $minorCount = \App\Models\StudentViolation::where('student_id', $student->student_id)
                    ->where('offense_type', 'minor')
                    ->count(); // Count all minor violations (pending, approved, resolved)

                  $statusColor = '#28a745'; // Green
                  $statusIcon = '✅';
                  if ($minorCount >= 3) {
                    $statusColor = '#dc3545'; // Red
                    $statusIcon = '🚨';
                  } elseif ($minorCount == 2) {
                    $statusColor = '#fd7e14'; // Orange
                    $statusIcon = '⚠️';
                  } elseif ($minorCount == 1) {
                    $statusColor = '#ffc107'; // Yellow
                    $statusIcon = '⚠️';
                  }
                @endphp
                <div style="font-size: 11px; padding: 2px 6px; border-radius: 3px; display: inline-block; background: {{ $statusColor }}20; color: {{ $statusColor }}; border: 1px solid {{ $statusColor }}40;">
                  {{ $statusIcon }} {{ $minorCount }}/3 Minor Violations
                </div>
              </td>
              <td style="padding: 16px; color: #495057; font-size: 14px;">
                <span style="display: inline-block; padding: 4px 8px; background: var(--light-green); color: var(--primary-green); border-radius: 4px; font-size: 12px; font-weight: 500;">
                  {{ $student->department }}
                </span>
              </td>
              <td style="padding: 16px; color: #495057; font-size: 14px;">
                <div style="max-width: 250px;">
                  <div style="font-weight: 500; color: #ffc107; margin-bottom: 4px;">{{ ucfirst($student->offense_type) }} Violation</div>
                  <div style="color: #6c757d; font-size: 13px; line-height: 1.4;">{{ Str::limit($student->violation, 100) }}</div>
                </div>
              </td>
              <td style="padding: 16px; color: #495057; font-size: 14px;">
                @if($student->added_by)
                  <div style="display: flex; align-items: center; gap: 8px;">
                    <svg style="width: 16px; height: 16px; color: #6c757d;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    <div>
                      <div style="font-weight: 500; color: #495057; font-size: 13px;">{{ $student->added_by }}</div>
                      <div style="font-size: 11px; color: #6c757d;">PSG Officer</div>
                    </div>
                  </div>
                @else
                  <span style="color: #6c757d; font-size: 12px; font-style: italic;">Unknown</span>
                @endif
              </td>
              <td style="padding: 16px; color: #495057; font-size: 14px;">
                @if($student->status == 0)
                  <span style="display: inline-block; padding: 6px 12px; background: #ffc10720; color: #ffc107; border-radius: 20px; font-size: 12px; font-weight: 500;">
                    ⏳ Pending
                  </span>
                @elseif($student->status == 1)
                  <span style="display: inline-block; padding: 6px 12px; background: #17a2b820; color: #17a2b8; border-radius: 20px; font-size: 12px; font-weight: 500;">
                    ✅ Dean Approved
                  </span>
                @elseif($student->status == 2)
                  <span style="display: inline-block; padding: 6px 12px; background: #28a74520; color: #28a745; border-radius: 20px; font-size: 12px; font-weight: 500;">
                    🏁 Case Closed
                  </span>
                @else
                  <span style="display: inline-block; padding: 6px 12px; background: #6c757d20; color: #6c757d; border-radius: 20px; font-size: 12px; font-weight: 500;">
                    ❓ Unknown
                  </span>
                @endif
              </td>
              <td style="padding: 16px; color: #495057; font-size: 14px;">
                @if($student->status == 0)
                  <span style="color: #ffc107; font-size: 12px; font-weight: 500; padding: 8px 12px; background: #ffc10720; border-radius: 6px;">
                    📋 Awaiting Dean Action
                  </span>
                @elseif($student->status == 1)
                  <span style="color: #17a2b8; font-size: 12px; font-weight: 500; padding: 8px 12px; background: #17a2b820; border-radius: 6px;">
                    📤 Awaiting Admin Action
                  </span>
                @elseif($student->status == 2)
                  <span style="color: #28a745; font-size: 12px; font-weight: 500; padding: 8px 12px; background: #28a74520; border-radius: 6px;">
                    ✅ Case Resolved
                  </span>
                @else
                  <span style="color: #6c757d; font-size: 12px; font-style: italic; padding: 8px 12px; background: #f8f9fa; border-radius: 6px;">
                    ❓ No Action Available
                  </span>
                @endif
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>

        <!-- Pagination -->
        @if($students->hasPages())
          <div style="padding: 24px; border-top: 1px solid #e9ecef; background: #f8f9fa;">
            {{ $students->links() }}
          </div>
        @endif
      @endif
    </div>

</x-dashboard-layout>
