<x-dashboard-layout>
  <x-slot name="roleTitle">Program Coordinator</x-slot>

  <x-slot name="navigation">
    <x-prog-coor-navigation />
  </x-slot>

  <!-- Header Section -->
  <div class="header-section">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
      <div>
        <h1 class="role-title">Major Violations - Department View</h1>
        <p class="welcome-text">View major violations for your department ({{ Auth::user()->department }})</p>
        <div class="accent-line"></div>
      </div>

      <!-- Statistics Cards -->
      <div style="display: flex; gap: 16px; flex-wrap: wrap;">
        <div style="background: #fff3cd; padding: 12px 16px; border-radius: 8px; border-left: 4px solid #ffc107;">
          <div style="font-size: 24px; font-weight: 700; color: #856404;">{{ $pendingCount ?? 0 }}</div>
          <div style="font-size: 12px; color: #856404; font-weight: 500;">Awaiting Proceedings</div>
        </div>
        <div style="background: #d1ecf1; padding: 12px 16px; border-radius: 8px; border-left: 4px solid #17a2b8;">
          <div style="font-size: 24px; font-weight: 700; color: #0c5460;">{{ $proceedingsUploadedCount ?? 0 }}</div>
          <div style="font-size: 12px; color: #0c5460; font-weight: 500;">Under Review</div>
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

  <!-- Advanced Search Form -->
  <div style="background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); overflow: hidden; margin-top: 24px;">
    <div style="padding: 24px; border-bottom: 1px solid #e9ecef;">
      <h3 style="margin: 0; color: var(--primary-green); font-size: 1.1rem; font-weight: 600;">Advanced Search</h3>
    </div>
    <form method="GET" action="{{ route('CoorMajorSearch') }}" style="padding: 24px;">
      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 16px; margin-bottom: 20px;">
        <div>
          <label for="student_id" style="display: block; margin-bottom: 8px; font-weight: 500; color: #495057;">Student ID</label>
          <input type="text" id="student_id" name="student_id"
                 style="width: 100%; padding: 12px 16px; border: 2px solid #e1e5e9; border-radius: 8px; font-size: 14px;"
                 value="{{ old('student_id', request('student_id')) }}"
                 placeholder="Enter Student ID">
        </div>
        <div>
          <label for="ref_num" style="display: block; margin-bottom: 8px; font-weight: 500; color: #495057;">Reference Number</label>
          <input type="text" id="ref_num" name="ref_num"
                 style="width: 100%; padding: 12px 16px; border: 2px solid #e1e5e9; border-radius: 8px; font-size: 14px;"
                 value="{{ old('ref_num', request('ref_num')) }}"
                 placeholder="Enter Reference Number">
        </div>
        <div>
          <label for="last_name" style="display: block; margin-bottom: 8px; font-weight: 500; color: #495057;">Last Name</label>
          <input type="text" id="last_name" name="last_name"
                 style="width: 100%; padding: 12px 16px; border: 2px solid #e1e5e9; border-radius: 8px; font-size: 14px;"
                 value="{{ old('last_name', request('last_name')) }}"
                 placeholder="Enter Last Name">
        </div>
      </div>
      <div style="display: flex; gap: 12px;">
        <button type="submit" class="btn-primary">Search</button>
        <a href="{{ route('prog_coor.major') }}" style="display: inline-block; padding: 12px 24px; background: #dc3545; color: white; text-decoration: none; border-radius: 8px; font-weight: 600; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(220, 53, 69, 0.3); border: 2px solid #dc3545;" 
           onmouseover="this.style.background='#c82333'; this.style.borderColor='#bd2130'; this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 25px rgba(220, 53, 69, 0.4)'" 
           onmouseout="this.style.background='#dc3545'; this.style.borderColor='#dc3545'; this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(220, 53, 69, 0.3)'">Clear</a>
      </div>
    </form>
  </div>

  <!-- Major Violations Table -->
  <div style="background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); overflow: hidden; margin-top: 24px;">
    <div style="padding: 24px; border-bottom: 1px solid #e9ecef;">
      <h2 style="margin: 0; color: var(--primary-green); font-size: 1.25rem; font-weight: 600;">Major Violations Management</h2>
    </div>

    @if ($students->isEmpty())
    <div style="padding: 48px; text-align: center; color: #6c757d;">
      <svg style="width: 64px; height: 64px; margin: 0 auto 16px; color: #dee2e6;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
      </svg>
      <p style="margin: 0; font-size: 1.1rem; font-weight: 500;">No major violations found</p>
      <p style="margin: 8px 0 0; font-size: 0.9rem;">Try adjusting your search criteria</p>
    </div>
    @else
    <div style="overflow-x: auto;">
      <table style="width: 100%; border-collapse: collapse;">
        <thead>
          <tr style="background: #f8f9fa; border-bottom: 2px solid #e9ecef;">
            <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057; font-size: 14px;">Reference #</th>
            <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057; font-size: 14px;">Student ID</th>
            <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057; font-size: 14px;">Name</th>
            <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057; font-size: 14px;">Violation Details</th>
            <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057; font-size: 14px;">Status</th>
            <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057; font-size: 14px;">Proceedings</th>
            <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057; font-size: 14px;">Actions</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($students as $student)
          <tr style="border-bottom: 1px solid #e9ecef; transition: background-color 0.2s ease;"
              onmouseover="this.style.backgroundColor='#f8f9fa'"
              onmouseout="this.style.backgroundColor='transparent'">
            <td style="padding: 16px; color: #495057; font-size: 14px;">
              @if ($student->ref_num)
                <span style="font-weight: 500; color: var(--primary-green);">{{ $student->ref_num }}</span>
              @else
                <span style="color: #6c757d; font-style: italic;">No Reference Number</span>
              @endif
            </td>
            <td style="padding: 16px; color: #495057; font-size: 14px; font-weight: 500;">{{ $student->student_id }}</td>
            <td style="padding: 16px; color: #495057; font-size: 14px;">{{ $student->first_name }} {{ $student->last_name }}</td>
            <td style="padding: 16px; color: #495057; font-size: 14px;">
              <div style="max-width: 300px;">
                <div style="font-weight: 500; color: #e74c3c; margin-bottom: 4px;">{{ $student->offense_type }}</div>
                <div style="color: #6c757d; font-size: 13px;">{{ $student->violation }}</div>
              </div>
            </td>
            <td style="padding: 16px; color: #495057; font-size: 14px;">
              @if ($student->status == 0)
                <span style="display: inline-block; padding: 5px 10px; background: #ffc10720; color: #856404; border-radius: 4px; font-size: 12px; font-weight: 500;">Awaiting Moderator</span>
              @elseif ($student->status == 1)
                <span style="display: inline-block; padding: 5px 10px; background: #007bff20; color: #007bff; border-radius: 4px; font-size: 12px; font-weight: 500;">Under Review</span>
              @elseif ($student->status == 1.5)
                <span style="display: inline-block; padding: 5px 10px; background: #e1705520; color: #e17055; border-radius: 4px; font-size: 12px; font-weight: 500;">Forwarded to Admin</span>
              @elseif ($student->status == 2)
                <span style="display: inline-block; padding: 5px 10px; background: #28a74520; color: #28a745; border-radius: 4px; font-size: 12px; font-weight: 500;">Closed</span>
              @else
                <span style="display: inline-block; padding: 5px 10px; background: #6c757d20; color: #6c757d; border-radius: 4px; font-size: 12px; font-weight: 500;">Unknown</span>
              @endif
            </td>
            <td style="padding: 16px; color: #495057; font-size: 14px;">
              @if ($student->document_path)
                <div style="display: flex; flex-direction: column; gap: 4px;">
                  @if ($student->proceedings_uploaded_at)
                    <small style="color: #6c757d; font-size: 11px;">Uploaded {{ $student->proceedings_uploaded_at->format('M d, Y') }}</small>
                  @endif
                  <a href="{{ route('prog_coor.downloadProceedings', $student->id) }}"
                     style="background: #f8f9fa; color: #495057; border: 1px solid #dee2e6; padding: 4px 10px; border-radius: 4px; font-size: 12px; font-weight: 500; text-decoration: none; display: inline-flex; align-items: center; gap: 4px; width: fit-content; transition: all 0.2s ease;"
                     onmouseover="this.style.background='#e9ecef'"
                     onmouseout="this.style.background='#f8f9fa'">
                    <svg style="width: 11px; height: 11px; flex-shrink: 0;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg> Download
                  </a>
                </div>
              @else
                <span style="color: #6c757d; font-size: 12px; font-style: italic;">No Proceedings</span>
              @endif
            </td>
            <td style="padding: 16px; color: #495057; font-size: 14px;">
              @if ($student->status == 0)
                <span style="color: #856404; font-size: 12px; font-weight: 500; padding: 5px 10px; background: #ffc10720; border-radius: 4px; display: inline-flex; align-items: center; gap: 4px;">
                  <svg style="width: 11px; height: 11px; flex-shrink: 0;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg> Awaiting Moderator
                </span>
              @elseif ($student->status == 1)
                <span style="color: #007bff; font-size: 12px; font-weight: 500; padding: 5px 10px; background: #007bff20; border-radius: 4px; display: inline-flex; align-items: center; gap: 4px;">
                  <svg style="width: 11px; height: 11px; flex-shrink: 0;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg> Under Review
                </span>
              @elseif ($student->status == 1.5)
                <span style="color: #e17055; font-size: 12px; font-weight: 500; padding: 5px 10px; background: #e1705520; border-radius: 4px; display: inline-flex; align-items: center; gap: 4px;">
                  <svg style="width: 11px; height: 11px; flex-shrink: 0;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg> Forwarded to Admin
                </span>
              @elseif ($student->status == 2)
                <span style="color: #28a745; font-size: 12px; font-weight: 500; padding: 5px 10px; background: #28a74520; border-radius: 4px; display: inline-flex; align-items: center; gap: 4px;">
                  <svg style="width: 11px; height: 11px; flex-shrink: 0;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> Resolved
                </span>
              @else
                <span style="color: #6c757d; font-size: 12px; font-style: italic; padding: 5px 10px; background: #f8f9fa; border-radius: 4px;">
                  Unknown
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