<x-dashboard-layout>
  <x-slot name="roleTitle">Dean</x-slot>

  <x-slot name="navigation">
    @include('components.dean-navigation')
  </x-slot>

  <!-- Header Section -->
  <div class="header-section">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
      <div>
        <h1 class="role-title">Major Violations</h1>
        <p class="welcome-text">Review major violations in your department</p>
        <div class="accent-line"></div>
      </div>
      <div style="display: flex; gap: 12px; align-items: center;">
        <div style="padding: 12px 16px; background: #fdeaea; border-radius: 8px; font-size: 14px; color: #dc3545; font-weight: 600;">
          {{ $students->total() }} Major Violation{{ $students->total() !== 1 ? 's' : '' }}
        </div>
      </div>
    </div>
  </div>

  <!-- Main Content -->
  <div class="header-section">
    <!-- Status Messages -->
    @if(session('success'))
    <div style="margin-bottom: 24px; padding: 16px; background: #d4edda; color: #155724; border: 1px solid #c3e6cb; border-radius: 8px;">
      {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div style="margin-bottom: 24px; padding: 16px; background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; border-radius: 8px;">
      {{ session('error') }}
    </div>
    @endif

    <!-- Violations Table -->
    @if($students->isEmpty())
    <div style="text-align: center; padding: 48px; background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
      <svg style="width: 64px; height: 64px; margin: 0 auto 16px; color: #9ca3af;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
      </svg>
      <h3 style="margin: 0 0 8px; color: #374151; font-size: 1.25rem;">No Major Violations Found</h3>
      <p style="margin: 0; color: #6b7280;">There are currently no major violations in your department.</p>
    </div>
    @else
    <div style="background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); overflow: hidden;">
      <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;">
          <thead>
            <tr style="background: #f8f9fa; border-bottom: 2px solid #e9ecef;">
              <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057; font-size: 14px;">Reference Number</th>
              <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057; font-size: 14px;">Student ID</th>
              <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057; font-size: 14px;">Student Name</th>
              <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057; font-size: 14px;">Course</th>
              <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057; font-size: 14px;">Violation Details</th>
              <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057; font-size: 14px;">Status</th>
              <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057; font-size: 14px;">Document</th>
            </tr>
          </thead>
          <tbody>
            @foreach($students as $student)
            <tr style="border-bottom: 1px solid #e9ecef; transition: background-color 0.2s ease;"
                onmouseover="this.style.backgroundColor='#f8f9fa'"
                onmouseout="this.style.backgroundColor='transparent'">
              <td style="padding: 16px; color: #495057; font-size: 14px; font-family: monospace; background: #f8f9fa; font-weight: 600;">
                @if($student->ref_num)
                  {{ $student->ref_num }}
                @else
                  <span style="color: #6c757d; font-style: italic;">No Reference Number</span>
                @endif
              </td>
              <td style="padding: 16px; color: #495057; font-size: 14px; font-weight: 500;">{{ $student->student_id }}</td>
              <td style="padding: 16px; color: #495057; font-size: 14px;">{{ $student->first_name }} {{ $student->last_name }}</td>
              <td style="padding: 16px; color: #495057; font-size: 14px;">
                <span style="display: inline-block; padding: 4px 8px; background: #e3f2fd; color: #1976d2; border-radius: 4px; font-size: 12px; font-weight: 500;">
                  {{ $student->course ?? 'N/A' }}
                </span>
              </td>
              <td style="padding: 16px; color: #495057; font-size: 14px; max-width: 200px;">
                <div style="font-weight: 600; color: #333; margin-bottom: 4px;">{{ $student->violation }}</div>
                <span style="display: inline-block; padding: 4px 8px; background: #dc354520; color: #dc3545; border-radius: 4px; font-size: 11px; font-weight: 500; text-transform: uppercase;">
                  Major Violation
                </span>
              </td>
              <td style="padding: 16px; color: #495057; font-size: 14px;">
                @if($student->status == 0)
                  <span style="display: inline-block; padding: 6px 12px; background: #ffc107; color: #333; border-radius: 20px; font-size: 12px; font-weight: 500;">
                    ⏳ Pending
                  </span>
                @elseif($student->status == 2)
                  <span style="display: inline-block; padding: 6px 12px; background: #28a745; color: white; border-radius: 20px; font-size: 12px; font-weight: 500;">
                    ✅ Resolved
                  </span>
                @else
                  <span style="display: inline-block; padding: 6px 12px; background: #17a2b8; color: white; border-radius: 20px; font-size: 12px; font-weight: 500;">
                    🔄 In Progress
                  </span>
                @endif
              </td>
              <td style="padding: 16px; color: #495057; font-size: 14px;">
                @if($student->status == 0 || !$student->document_path)
                  <span style="color: #6c757d; font-size: 12px; font-style: italic; padding: 8px 12px; background: #f8f9fa; border-radius: 6px;">
                    📄 No Document
                  </span>
                @else
                  <a href="{{ route('files.serve', $student->document_path) }}" target="_blank"
                     style="background: #28a745; color: white; border: none; padding: 8px 12px; border-radius: 6px; font-size: 12px; font-weight: 500; cursor: pointer; display: flex; align-items: center; gap: 6px; transition: all 0.2s ease; text-decoration: none; justify-content: center; max-width: 100px;"
                     onmouseover="this.style.background='#218838'"
                     onmouseout="this.style.background='#28a745'">
                    <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Download
                  </a>
                @endif
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      @if($students->hasPages())
      <div style="padding: 20px; border-top: 1px solid #e9ecef; background: #f8f9fa;">
        <div style="display: flex; justify-content: between; align-items: center; flex-wrap: wrap; gap: 16px;">
          <div style="color: #6c757d; font-size: 14px;">
            Showing {{ $students->firstItem() }} to {{ $students->lastItem() }} of {{ $students->total() }} violations
          </div>
          <div style="display: flex; gap: 8px;">
            @if($students->onFirstPage())
              <span style="padding: 8px 12px; background: #e9ecef; color: #6c757d; border-radius: 4px; font-size: 14px;">Previous</span>
            @else
              <a href="{{ $students->previousPageUrl() }}" style="padding: 8px 12px; background: var(--primary-green); color: white; border-radius: 4px; font-size: 14px; text-decoration: none;">Previous</a>
            @endif

            @if($students->hasMorePages())
              <a href="{{ $students->nextPageUrl() }}" style="padding: 8px 12px; background: var(--primary-green); color: white; border-radius: 4px; font-size: 14px; text-decoration: none;">Next</a>
            @else
              <span style="padding: 8px 12px; background: #e9ecef; color: #6c757d; border-radius: 4px; font-size: 14px;">Next</span>
            @endif
          </div>
        </div>
      </div>
      @endif
    </div>
    @endif
  </div>
</x-dashboard-layout>