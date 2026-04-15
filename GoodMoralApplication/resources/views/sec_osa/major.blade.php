<x-dashboard-layout>
  <x-slot name="roleTitle">Moderator</x-slot>

  <x-slot name="navigation">
    <x-moderator-navigation />
  </x-slot>

  <!-- Header Section -->
  <div class="header-section">
    <h1 class="role-title">Major Violations - Proceedings Management</h1>
    <p class="welcome-text">Upload proceedings and forward cases to Admin</p>
    <div class="accent-line"></div>
  </div>
  <!-- Status Messages -->
  @include('shared.alerts.flash')

  <!-- Search and Filter Form -->
  <div style="background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); overflow: hidden; margin-top: 24px;">
    <div style="padding: 16px 24px; border-bottom: 1px solid #e9ecef;">
      <h3 style="margin: 0; color: var(--primary-green); font-size: 1rem; font-weight: 600;">Search & Filter Major Violations</h3>
    </div>
    <form method="GET" action="{{ route('sec_osa.searchMajor') }}" style="padding: 20px 24px;">
      <div style="display: grid; grid-template-columns: 2fr 1fr 1fr 1fr auto; gap: 12px; align-items: end;">

        <!-- Search -->
        <div>
          <label for="search" style="display: block; margin-bottom: 6px; font-weight: 500; color: #495057; font-size: 13px;">Search</label>
          <input type="text" id="search" name="search"
                 style="width: 100%; padding: 9px 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;"
                 value="{{ old('search', request('search') ?? implode(' ', array_filter([request('student_id'), request('ref_num')]))) }}"
                 placeholder="Search student name, student ID, or case reference">
        </div>

        <!-- Department -->
        <div>
          <label for="department" style="display: block; margin-bottom: 6px; font-weight: 500; color: #495057; font-size: 13px;">Department</label>
          <select id="department" name="department"
                  style="width: 100%; padding: 9px 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
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

        <!-- Case Status -->
        <div>
          <label for="status" style="display: block; margin-bottom: 6px; font-weight: 500; color: #495057; font-size: 13px;">Case Status</label>
          <select id="status" name="status"
                  style="width: 100%; padding: 9px 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
            <option value="">All Status</option>
            <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Needs Proceedings</option>
            <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Proceedings Uploaded</option>
            <option value="1.5" {{ request('status') == '1.5' ? 'selected' : '' }}>Forwarded to Admin</option>
            <option value="2" {{ request('status') == '2' ? 'selected' : '' }}>Case Closed</option>
          </select>
        </div>

        <!-- Date Range -->
        <div>
          <label style="display: block; margin-bottom: 6px; font-weight: 500; color: #495057; font-size: 13px;">Date Range</label>
          <div style="display: flex; gap: 4px; align-items: center;">
            <input type="date" name="date_from"
                   style="width: 100%; padding: 9px 8px; border: 1px solid #ddd; border-radius: 6px; font-size: 13px;"
                   value="{{ old('date_from', request('date_from')) }}"
                   title="Date From">
            <span style="color: #adb5bd; font-size: 12px; flex-shrink: 0;">–</span>
            <input type="date" name="date_to"
                   style="width: 100%; padding: 9px 8px; border: 1px solid #ddd; border-radius: 6px; font-size: 13px;"
                   value="{{ old('date_to', request('date_to')) }}"
                   title="Date To">
          </div>
        </div>

        <!-- Buttons -->
        <div style="display: flex; gap: 6px;">
          <button type="submit" class="btn btn-success btn-sm">Apply</button>
          <a href="{{ route('sec_osa.major') }}" class="btn btn-secondary btn-sm">Clear</a>
        </div>

      </div>
    </form>
  </div>

  <!-- Violations Table -->
  <div style="background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); overflow: hidden; margin-top: 24px;">
    <div style="padding: 24px; border-bottom: 1px solid #e9ecef;">
      <h2 style="margin: 0; color: var(--primary-green); font-size: 1.25rem; font-weight: 600;">Major Violations List</h2>
    </div>

    @if ($students->isEmpty())
    <div style="padding: 48px; text-align: center; color: #6c757d;">
      <svg style="width: 64px; height: 64px; margin: 0 auto 16px; color: #dee2e6;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
      </svg>
      <p style="margin: 0; font-size: 1.1rem; font-weight: 500;">No proceedings to review</p>
      <p style="margin: 8px 0 0; font-size: 0.9rem;">Major violations with uploaded proceedings will appear here for review</p>
    </div>
    @else
    <div style="overflow-x: auto;">
      <table style="width: 100%; border-collapse: collapse;">
        <thead>
          <tr style="background: #f8f9fa; border-bottom: 2px solid #e9ecef;">
            <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057; font-size: 14px;">Case #</th>
            <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057; font-size: 14px;">Student Info</th>
            <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057; font-size: 14px;">Name</th>
            <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057; font-size: 14px;">Department</th>
            <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057; font-size: 14px;">Violation</th>
            <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057; font-size: 14px;">Proceedings</th>
            <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057; font-size: 14px;">Actions</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($students as $student)
          <tr style="border-bottom: 1px solid #e9ecef; transition: background-color 0.2s ease;"
              onmouseover="this.style.backgroundColor='#f8f9fa'"
              onmouseout="this.style.backgroundColor='transparent'">
            <td style="padding: 16px; color: #495057; font-size: 14px; font-weight: 500;">
              <span style="font-family: monospace; background: #f8f9fa; padding: 4px 8px; border-radius: 4px; font-size: 12px;">
                {{ $student->ref_num }}
              </span>
            </td>
            <td style="padding: 16px; color: #495057; font-size: 14px;">
              <div style="font-weight: 500; margin-bottom: 4px;">{{ $student->student_id }}</div>
              <div style="font-size: 12px; color: #007bff; font-weight: 600;">
                Year Level: {{ $student->getStudentYearLevel() }}
              </div>
            </td>
            <td style="padding: 16px; color: #495057; font-size: 14px;">{{ $student->first_name }} {{ $student->last_name }}</td>
            <td style="padding: 16px; color: #495057; font-size: 14px;">
              <span style="display: inline-block; padding: 4px 8px; background: var(--light-green); color: var(--primary-green); border-radius: 4px; font-size: 12px; font-weight: 500;">
                {{ $student->department }}
              </span>
            </td>
            <td style="padding: 16px; color: #495057; font-size: 14px;">
              <div style="max-width: 250px;">
                <div style="font-weight: 500; color: #dc3545; margin-bottom: 4px;">{{ ucfirst($student->offense_type) }} Violation</div>
                <div style="color: #6c757d; font-size: 13px; line-height: 1.4;">{{ Str::limit($student->violation, 100) }}</div>
              </div>
            </td>
            <td style="padding: 16px; color: #495057; font-size: 14px;">
              @if ($student->document_path)
                <div style="display: flex; flex-direction: column; gap: 4px;">
                  <a href="/files/{{ $student->document_path }}" target="_blank"
                     style="background: #007bff; color: white; border: none; padding: 6px 12px; border-radius: 6px; font-size: 12px; font-weight: 500; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; transition: all 0.2s ease;"
                     onmouseover="this.style.background='#0056b3'"
                     onmouseout="this.style.background='#007bff'">
                    <svg style="width: 12px; height: 12px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    View Proceedings
                  </a>
                  @if ($student->proceedings_uploaded_at)
                    <small style="color: #6c757d; font-size: 11px;">
                      Uploaded: {{ $student->proceedings_uploaded_at->format('M d, Y') }}
                    </small>
                  @endif
                  @if ($student->proceedings_uploaded_by)
                    <small style="color: #6c757d; font-size: 11px;">
                      By: {{ $student->proceedings_uploaded_by }}
                    </small>
                  @endif
                </div>
              @else
                <span style="color: #6c757d; font-size: 12px; font-style: italic; padding: 8px 12px; background: #f8f9fa; border-radius: 6px;">
                  No Proceedings
                </span>
              @endif
            </td>
            <td style="padding: 16px; color: #495057; font-size: 14px;">
              @if ($student->status == 0)
                {{-- Status 0: Needs proceedings upload --}}
                <a href="{{ route('sec_osa.showUploadProceedings', $student->id) }}"
                   style="background: var(--primary-green); color: white; border: none; padding: 8px 12px; border-radius: 6px; font-size: 12px; font-weight: 500; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; transition: all 0.2s ease;"
                   onmouseover="this.style.background='var(--dark-green)'"
                   onmouseout="this.style.background='var(--primary-green)'">
                  <svg style="width: 12px; height: 12px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                  </svg>
                  Upload Proceedings
                </a>
              @elseif ($student->status == 1 && $student->document_path && $student->forwarded_to_admin_at)
                {{-- Status 1: Already forwarded to admin --}}
                <span style="color: #17a2b8; font-size: 12px; font-weight: 500; padding: 8px 12px; background: #d1ecf120; border-radius: 6px;">
                  📤 Forwarded to Admin
                </span>
              @elseif ($student->status == 1 && $student->document_path)
                {{-- Status 1: Proceedings uploaded, can forward to admin --}}
                <form action="{{ route('sec_osa.forwardToAdmin', $student->id) }}" method="POST" style="display: inline;"
                      onsubmit="return confirm('Are you sure you want to forward this case to the Admin for closure?')">
                  @csrf
                  <button type="submit"
                          style="background: #e17055; color: white; border: none; padding: 8px 12px; border-radius: 6px; font-size: 12px; font-weight: 500; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; transition: all 0.2s ease;"
                          onmouseover="this.style.background='#d63031'"
                          onmouseout="this.style.background='#e17055'">
                    <svg style="width: 12px; height: 12px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                    </svg>
                    Forward to Admin
                  </button>
                </form>
              @elseif ($student->status == 2)
                {{-- Status 2: Case closed --}}
                <span style="color: #28a745; font-size: 12px; font-weight: 500; padding: 8px 12px; background: #28a74520; border-radius: 6px;">
                  ✅ Case Closed
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
    </div>

    <!-- Pagination -->
    @if($students->hasPages())
    <div style="padding: 16px 24px; border-top: 1px solid #e9ecef; background: #f8f9fa;">
      {{ $students->links() }}
    </div>
    @endif
    @endif
  </div>

</x-dashboard-layout>