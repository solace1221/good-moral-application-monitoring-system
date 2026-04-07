<x-dashboard-layout>
  <x-slot name="roleTitle">Admin</x-slot>

  <x-slot name="navigation">
    <x-admin-navigation />
  </x-slot>

  <!-- Header Section -->
  <div class="header-section">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
      <div>
        <h1 class="role-title">Reports History</h1>
        <p class="welcome-text">View and manage all generated reports</p>
        <div class="accent-line"></div>
      </div>
      <div>
        <a href="{{ route('admin.reports') }}" class="btn-secondary" style="padding: 12px 24px; font-size: 14px; display: flex; align-items: center; gap: 8px; text-decoration: none;">
          <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
          </svg>
          Generate New Report
        </a>
      </div>
    </div>
  </div>

  <!-- Main Content -->
  <div class="header-section" style="margin-top: 24px;">
    <div style="background: white; padding: 32px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);"
          
          <!-- Statistics Cards -->
          <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-blue-50 p-6 rounded-lg border border-blue-200">
              <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                  <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                  </svg>
                </div>
                <div class="ml-4">
                  <p class="text-sm font-medium text-gray-600">Total Reports</p>
                  <p class="text-2xl font-semibold text-gray-900">{{ $statistics['total_reports'] ?? 0 }}</p>
                </div>
              </div>
            </div>

            <div class="bg-green-50 p-6 rounded-lg border border-green-200">
              <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                  <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                  </svg>
                </div>
                <div class="ml-4">
                  <p class="text-sm font-medium text-gray-600">Completed</p>
                  <p class="text-2xl font-semibold text-gray-900">{{ $statistics['completed_reports'] ?? 0 }}</p>
                </div>
              </div>
            </div>

            <div class="bg-red-50 p-6 rounded-lg border border-red-200">
              <div class="flex items-center">
                <div class="p-3 rounded-full bg-red-100 text-red-600">
                  <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                  </svg>
                </div>
                <div class="ml-4">
                  <p class="text-sm font-medium text-gray-600">Failed</p>
                  <p class="text-2xl font-semibold text-gray-900">{{ $statistics['failed_reports'] ?? 0 }}</p>
                </div>
              </div>
            </div>

            <div class="bg-yellow-50 p-6 rounded-lg border border-yellow-200">
              <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                  <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2-2v12a2 2 0 002 2z"></path>
                  </svg>
                </div>
                <div class="ml-4">
                  <p class="text-sm font-medium text-gray-600">This Month</p>
                  <p class="text-2xl font-semibold text-gray-900">{{ $statistics['reports_this_month'] ?? 0 }}</p>
                </div>
              </div>
            </div>
          </div>

          <!-- Filters -->
          <div class="mb-6 p-4 bg-gray-50 rounded-lg">
            <form method="GET" action="{{ route('admin.reports.history') }}" class="flex flex-wrap gap-4">
              <div class="flex-1 min-w-48">
                <label for="report_type" class="block text-sm font-medium text-gray-700 mb-1">Report Type</label>
                <select name="report_type" id="report_type" class="w-full rounded-md border-gray-300 shadow-sm">
                  <option value="">All Types</option>
                  <option value="good_moral_applicants" {{ request('report_type') == 'good_moral_applicants' ? 'selected' : '' }}>Good Moral Applicants</option>
                  <option value="residency_applicants" {{ request('report_type') == 'residency_applicants' ? 'selected' : '' }}>Residency Applicants</option>
                  <option value="minor_violators" {{ request('report_type') == 'minor_violators' ? 'selected' : '' }}>Minor Violators</option>
                  <option value="major_violators" {{ request('report_type') == 'major_violators' ? 'selected' : '' }}>Major Violators</option>
                  <option value="overall_report" {{ request('report_type') == 'overall_report' ? 'selected' : '' }}>Overall Report</option>
                </select>
              </div>

              <div class="flex-1 min-w-48">
                <label for="academic_year" class="block text-sm font-medium text-gray-700 mb-1">Academic Year</label>
                <select name="academic_year" id="academic_year" class="w-full rounded-md border-gray-300 shadow-sm">
                  <option value="">All Years</option>
                  @foreach($academicYears as $year)
                    <option value="{{ $year }}" {{ request('academic_year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                  @endforeach
                </select>
              </div>

              <div class="flex-1 min-w-48">
                <label for="generated_by" class="block text-sm font-medium text-gray-700 mb-1">Generated By</label>
                <input type="text" name="generated_by" id="generated_by" value="{{ request('generated_by') }}" 
                       placeholder="User name" class="w-full rounded-md border-gray-300 shadow-sm">
              </div>

              <div class="flex items-end">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                  Filter
                </button>
                <a href="{{ route('admin.reports.history') }}" class="ml-2 px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                  Clear
                </a>
              </div>
            </form>
          </div>

          <!-- Reports Table -->
          <div class="overflow-x-auto" style="margin-top: 24px;">
            <table class="min-w-full divide-y divide-gray-200" style="border-collapse: collapse; background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
              <thead class="bg-gray-50">
                <tr style="background: white; border-bottom: 2px solid #e5e7eb;">
                  <th class="px-6 py-4 text-left text-xs font-medium text-black uppercase tracking-wider" style="padding: 20px 24px; font-size: 14px; font-weight: 600; color: black;">Report</th>
                  <th class="px-6 py-4 text-left text-xs font-medium text-black uppercase tracking-wider" style="padding: 20px 24px; font-size: 14px; font-weight: 600; color: black;">Academic Year</th>
                  <th class="px-6 py-4 text-left text-xs font-medium text-black uppercase tracking-wider" style="padding: 20px 24px; font-size: 14px; font-weight: 600; color: black;">Time Period</th>
                  <th class="px-6 py-4 text-left text-xs font-medium text-black uppercase tracking-wider" style="padding: 20px 24px; font-size: 14px; font-weight: 600; color: black;">Records</th>
                  <th class="px-6 py-4 text-left text-xs font-medium text-black uppercase tracking-wider" style="padding: 20px 24px; font-size: 14px; font-weight: 600; color: black;">Generated By</th>
                  <th class="px-6 py-4 text-left text-xs font-medium text-black uppercase tracking-wider" style="padding: 20px 24px; font-size: 14px; font-weight: 600; color: black;">Generated At</th>
                  <th class="px-6 py-4 text-left text-xs font-medium text-black uppercase tracking-wider" style="padding: 20px 24px; font-size: 14px; font-weight: 600; color: black;">Status</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                @forelse($reports as $report)
                  <tr class="hover:bg-gray-50" style="border-bottom: 1px solid #f3f4f6; transition: background-color 0.2s ease;">
                    <td class="px-6 py-6 whitespace-nowrap" style="padding: 20px 24px;">
                      <div>
                        <div class="text-sm font-medium text-gray-900" style="font-size: 14px; font-weight: 600; color: #374151;">{{ $report->human_readable_type }}</div>
                        <div class="text-sm text-gray-500" style="font-size: 13px; color: #6b7280; margin-top: 4px;">{{ $report->filename }}</div>
                      </div>
                    </td>
                    <td class="px-6 py-6 whitespace-nowrap text-sm text-gray-900" style="padding: 20px 24px; font-size: 14px; color: #374151;">
                      {{ $report->academic_year }}
                    </td>
                    <td class="px-6 py-6 whitespace-nowrap text-sm text-gray-900" style="padding: 20px 24px; font-size: 14px; color: #374151;">
                      {{ $report->human_readable_time_period }}
                    </td>
                    <td class="px-6 py-6 whitespace-nowrap text-sm text-gray-900" style="padding: 20px 24px; font-size: 14px; color: #374151;">
                      {{ number_format($report->total_records) }}
                    </td>
                    <td class="px-6 py-6 whitespace-nowrap" style="padding: 20px 24px;">
                      <div>
                        <div class="text-sm font-medium text-gray-900" style="font-size: 14px; font-weight: 600; color: #374151;">{{ $report->generated_by }}</div>
                        <div class="text-sm text-gray-500" style="font-size: 13px; color: #6b7280; margin-top: 4px;">{{ ucfirst($report->generated_by_role) }}</div>
                      </div>
                    </td>
                    <td class="px-6 py-6 whitespace-nowrap text-sm text-gray-900" style="padding: 20px 24px; font-size: 14px; color: #374151;">
                      {{ $report->generated_at->format('M j, Y g:i A') }}
                    </td>
                    <td class="px-6 py-6 whitespace-nowrap" style="padding: 20px 24px;">
                      @if($report->status === 'completed')
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800" style="padding: 8px 12px; font-size: 13px;">
                          Completed
                        </span>
                      @elseif($report->status === 'failed')
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800" style="padding: 8px 12px; font-size: 13px;">
                          Failed
                        </span>
                      @else
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800" style="padding: 8px 12px; font-size: 13px;">
                          {{ ucfirst($report->status) }}
                        </span>
                      @endif
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="7" class="px-6 py-8 text-center text-gray-500" style="padding: 40px 24px; font-size: 14px; color: #6b7280;">
                      No reports found.
                    </td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>

          <!-- Pagination -->
          @if($reports->hasPages())
            <div class="mt-6">
              {{ $reports->appends(request()->query())->links() }}
            </div>
          @endif

        </div>
      </div>
    </div>
  </div>
</x-dashboard-layout>
