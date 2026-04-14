<x-dashboard-layout>
  <x-slot name="roleTitle">PSG Officer</x-slot>

  <x-slot name="navigation">
    <x-psg-officer-navigation />
  </x-slot>

  <!-- Header Section -->
  <div class="header-section">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
      <div>
        <h1 class="role-title">My Minor Violations</h1>
        <p class="welcome-text">View minor violations you have recorded</p>
        <div class="accent-line"></div>
      </div>

    </div>
  </div>

  <!-- Status Messages -->
  @include('shared.alerts.flash')


  <!-- Violations Table -->
  <div style="background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); overflow: hidden; margin-top: 24px;">
    <div style="padding: 24px; border-bottom: 1px solid #e9ecef;">
      <h2 style="margin: 0; color: var(--primary-green); font-size: 1.25rem; font-weight: 600;">My Minor Violations List</h2>
    </div>

    @if ($students->isEmpty())
    <div style="padding: 48px; text-align: center; color: #6c757d;">
      <svg style="width: 64px; height: 64px; margin: 0 auto 16px; color: #dee2e6;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
      </svg>
      <p style="margin: 0; font-size: 1.1rem; font-weight: 500;">No violations found</p>
      <p style="margin: 8px 0 0; font-size: 0.9rem;">You haven't recorded any violations yet or try adjusting your search criteria</p>
    </div>
    @else
    <div style="overflow-x: auto;">
      <table style="width: 100%; border-collapse: collapse;">
        <thead>
          <tr style="background: #f8f9fa; border-bottom: 2px solid #e9ecef;">
            <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057; font-size: 14px;">Student ID</th>
            <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057; font-size: 14px;">Name</th>
            <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057; font-size: 14px;">Course</th>
            <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057; font-size: 14px;">Violation Details</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($students as $student)
          <tr style="border-bottom: 1px solid #e9ecef; transition: background-color 0.2s ease;"
              onmouseover="this.style.backgroundColor='#f8f9fa'"
              onmouseout="this.style.backgroundColor='transparent'">
            <td style="padding: 16px; color: #495057; font-size: 14px; font-weight: 500;">{{ $student->student_id }}</td>
            <td style="padding: 16px; color: #495057; font-size: 14px;">{{ $student->first_name }} {{ $student->last_name }}</td>
            <td style="padding: 16px; color: #495057; font-size: 14px;">
              @if($student->course)
                <span style="display: inline-block; padding: 4px 8px; background: #e3f2fd; color: #1976d2; border-radius: 4px; font-size: 12px; font-weight: 500;">
                  {{ $student->course }}
                </span>
              @else
                <span style="color: #6c757d; font-style: italic;">N/A</span>
              @endif
            </td>
            <td style="padding: 16px; color: #495057; font-size: 14px;">
              <div>
                <div style="font-weight: 500; color: #e74c3c; margin-bottom: 4px;">{{ $student->offense_type }}</div>
                <div style="color: #6c757d; font-size: 13px;">{{ $student->violation }}</div>
              </div>
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