<x-dashboard-layout>
  <x-slot name="roleTitle">Moderator</x-slot>

  <x-slot name="navigation">
    <x-sec-osa-navigation />
  </x-slot>

  <!-- Header Section -->
  <div class="header-section">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
      <div>
        <h1 class="role-title">Major Violations - Proceedings Management</h1>
        <p class="welcome-text">Upload proceedings and forward cases to Admin</p>
        <div class="accent-line"></div>
      </div>

      <!-- Statistics Cards -->
      <div style="display: flex; gap: 16px; flex-wrap: wrap;">
        <div style="background: #fff3cd; padding: 12px 16px; border-radius: 8px; border-left: 4px solid #ffc107;">
          <div style="font-size: 24px; font-weight: 700; color: #856404;">{{ $pendingCount ?? 0 }}</div>
          <div style="font-size: 12px; color: #856404; font-weight: 500;">Needs Proceedings</div>
        </div>
        <div style="background: #d1ecf1; padding: 12px 16px; border-radius: 8px; border-left: 4px solid #17a2b8;">
          <div style="font-size: 24px; font-weight: 700; color: #0c5460;">{{ $proceedingsUploadedCount ?? 0 }}</div>
          <div style="font-size: 12px; color: #0c5460; font-weight: 500;">Proceedings Uploaded</div>
        </div>
        <div style="background: #f8d7da; padding: 12px 16px; border-radius: 8px; border-left: 4px solid #e17055;">
          <div style="font-size: 24px; font-weight: 700; color: #721c24;">{{ $forwardedCount ?? 0 }}</div>
          <div style="font-size: 12px; color: #721c24; font-weight: 500;">Forwarded to Admin</div>
        </div>
        <div style="background: #d4edda; padding: 12px 16px; border-radius: 8px; border-left: 4px solid #28a745;">
          <div style="font-size: 24px; font-weight: 700; color: #155724;">{{ $closedCount ?? 0 }}</div>
          <div style="font-size: 12px; color: #155724; font-weight: 500;">Cases Closed</div>
        </div>
      </div>
    </div>
  </div>
  <!-- Status Messages -->
  @if(session('success'))
  <div style="margin-bottom: 24px; padding: 16px; background: #d4edda; color: #155724; border: 1px solid #c3e6cb; border-radius: 8px;">
    {{ session('success') }}
  </div>
  @endif

  <!-- Enhanced Search and Filter Form -->
  <div style="background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); overflow: hidden; margin-top: 24px;">
    <div style="padding: 24px; border-bottom: 1px solid #e9ecef;">
      <h3 style="margin: 0; color: var(--primary-green); font-size: 1.1rem; font-weight: 600;">Search & Filter Major Violations</h3>
    </div>
    <form method="GET" action="{{ route('sec_osa.searchMajor') }}" style="padding: 24px;">
      <!-- Primary Search Fields -->
      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 16px; margin-bottom: 20px;">
        <div>
          <label for="student_id" style="display: block; margin-bottom: 8px; font-weight: 500; color: #495057;">Student ID</label>
          <input type="text" id="student_id" name="student_id"
                 style="width: 100%; padding: 12px 16px; border: 2px solid #e1e5e9; border-radius: 8px; font-size: 14px;"
                 value="{{ old('student_id', request('student_id')) }}"
                 placeholder="Enter Student ID">
        </div>
        <div>
          <label for="first_name" style="display: block; margin-bottom: 8px; font-weight: 500; color: #495057;">First Name</label>
          <input type="text" id="first_name" name="first_name"
                 style="width: 100%; padding: 12px 16px; border: 2px solid #e1e5e9; border-radius: 8px; font-size: 14px;"
                 value="{{ old('first_name', request('first_name')) }}"
                 placeholder="Enter First Name">
        </div>
        <div>
          <label for="last_name" style="display: block; margin-bottom: 8px; font-weight: 500; color: #495057;">Last Name</label>
          <input type="text" id="last_name" name="last_name"
                 style="width: 100%; padding: 12px 16px; border: 2px solid #e1e5e9; border-radius: 8px; font-size: 14px;"
                 value="{{ old('last_name', request('last_name')) }}"
                 placeholder="Enter Last Name">
        </div>
        <div>
          <label for="ref_num" style="display: block; margin-bottom: 8px; font-weight: 500; color: #495057;">Case Reference</label>
          <input type="text" id="ref_num" name="ref_num"
                 style="width: 100%; padding: 12px 16px; border: 2px solid #e1e5e9; border-radius: 8px; font-size: 14px;"
                 value="{{ old('ref_num', request('ref_num')) }}"
                 placeholder="Enter Case Reference">
        </div>
      </div>
      
      <!-- Filter Fields -->
      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 20px; padding-top: 20px; border-top: 1px solid #e9ecef;">
        <div>
          <label for="department" style="display: block; margin-bottom: 8px; font-weight: 500; color: #495057;">Department</label>
          <select id="department" name="department" 
                  style="width: 100%; padding: 12px 16px; border: 2px solid #e1e5e9; border-radius: 8px; font-size: 14px;">
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
          <label for="status" style="display: block; margin-bottom: 8px; font-weight: 500; color: #495057;">Case Status</label>
          <select id="status" name="status" 
                  style="width: 100%; padding: 12px 16px; border: 2px solid #e1e5e9; border-radius: 8px; font-size: 14px;">
            <option value="">All Status</option>
            <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Needs Proceedings</option>
            <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Proceedings Uploaded</option>
            <option value="1.5" {{ request('status') == '1.5' ? 'selected' : '' }}>Forwarded to Admin</option>
            <option value="2" {{ request('status') == '2' ? 'selected' : '' }}>Case Closed</option>
          </select>
        </div>
        <div>
          <label for="has_proceedings" style="display: block; margin-bottom: 8px; font-weight: 500; color: #495057;">Proceedings</label>
          <select id="has_proceedings" name="has_proceedings" 
                  style="width: 100%; padding: 12px 16px; border: 2px solid #e1e5e9; border-radius: 8px; font-size: 14px;">
            <option value="">All Cases</option>
            <option value="1" {{ request('has_proceedings') == '1' ? 'selected' : '' }}>With Proceedings</option>
            <option value="0" {{ request('has_proceedings') == '0' ? 'selected' : '' }}>No Proceedings</option>
          </select>
        </div>
        <div>
          <label for="date_from" style="display: block; margin-bottom: 8px; font-weight: 500; color: #495057;">Date From</label>
          <input type="date" id="date_from" name="date_from"
                 style="width: 100%; padding: 12px 16px; border: 2px solid #e1e5e9; border-radius: 8px; font-size: 14px;"
                 value="{{ old('date_from', request('date_from')) }}">
        </div>
        <div>
          <label for="date_to" style="display: block; margin-bottom: 8px; font-weight: 500; color: #495057;">Date To</label>
          <input type="date" id="date_to" name="date_to"
                 style="width: 100%; padding: 12px 16px; border: 2px solid #e1e5e9; border-radius: 8px; font-size: 14px;"
                 value="{{ old('date_to', request('date_to')) }}">
        </div>
      </div>
      
      <!-- Action Buttons -->
      <div style="display: flex; gap: 12px; align-items: center;">
        <button type="submit" class="btn-primary">Apply Filters</button>
        <a href="{{ route('sec_osa.major') }}" class="btn-secondary">Clear All</a>
        <div style="margin-left: auto; color: #6c757d; font-size: 14px;">
          @if(request()->hasAny(['student_id', 'first_name', 'last_name', 'ref_num', 'department', 'status', 'has_proceedings', 'date_from', 'date_to']))
            <span style="background: #007bff; color: white; padding: 4px 8px; border-radius: 12px; font-size: 12px; font-weight: 500;">
              Filters Active
            </span>
          @endif
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
                  <a href="{{ route('sec_osa.downloadProceedings', $student->id) }}"
                     style="background: #007bff; color: white; border: none; padding: 6px 12px; border-radius: 6px; font-size: 12px; font-weight: 500; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; transition: all 0.2s ease;"
                     onmouseover="this.style.background='#0056b3'"
                     onmouseout="this.style.background='#007bff'">
                    <svg style="width: 12px; height: 12px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2z"></path>
                    </svg>
                    Download Proceedings
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
              @elseif ($student->status == 1.5)
                {{-- Status 1.5: Already forwarded to admin --}}
                <span style="color: #17a2b8; font-size: 12px; font-weight: 500; padding: 8px 12px; background: #d1ecf120; border-radius: 6px;">
                  📤 Forwarded to Admin
                </span>
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