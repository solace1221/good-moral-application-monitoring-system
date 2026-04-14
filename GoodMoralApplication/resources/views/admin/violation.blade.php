<x-dashboard-layout>
  <x-slot name="roleTitle">Admin</x-slot>

  <x-slot name="navigation">
    <x-admin-navigation />
  </x-slot>

  <!-- Header Section -->
  <div class="header-section">
    <div>
      <h1 class="role-title">View Violations</h1>
      <p class="welcome-text">Browse and manage student violations</p>
      <div class="accent-line"></div>
    </div>
  </div>

  <!-- Main Content -->
  <div class="header-section" style="margin-top: 24px;">


    @include('shared.alerts.flash')

    <!-- Search Form -->
    <form method="GET" action="{{ route('admin.violation') }}" style="margin-top: 24px; margin-bottom: 0; padding: 24px; background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">


      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 20px;">
        <div>
          <label for="search" style="display: block; margin-bottom: 8px; font-weight: 600; color: #333;">Student Name or ID</label>
          <input type="text" id="search" name="search"
                 style="width: 100%; padding: 12px 16px; border: 2px solid #e1e5e9; border-radius: 8px; font-size: 14px; transition: border-color 0.3s ease;"
                 value="{{ old('search', request('search')) }}"
                 placeholder="Enter name or student ID">
        </div>
        <div>
          <label for="department" style="display: block; margin-bottom: 8px; font-weight: 600; color: #333;">Department</label>
          <input type="text" id="department" name="department"
                 style="width: 100%; padding: 12px 16px; border: 2px solid #e1e5e9; border-radius: 8px; font-size: 14px; transition: border-color 0.3s ease;"
                 value="{{ old('department', request('department')) }}"
                 placeholder="Enter Department">
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
        <div>
          <label for="display_mode" style="display: block; margin-bottom: 8px; font-weight: 600; color: #333;">Display Mode</label>
          <select id="display_mode" name="display_mode"
                  style="width: 100%; padding: 12px 16px; border: 2px solid #e1e5e9; border-radius: 8px; font-size: 14px; transition: border-color 0.3s ease;">
            <option value="all" {{ request('display_mode', 'all') === 'all' ? 'selected' : '' }}>All</option>
            <option value="individual" {{ request('display_mode') === 'individual' ? 'selected' : '' }}>Individual</option>
            <option value="grouped" {{ request('display_mode') === 'grouped' ? 'selected' : '' }}>Grouped</option>
          </select>
        </div>
      </div>
      <div style="display: flex; gap: 12px; justify-content: flex-end;">
        <a href="{{ route('admin.violation') }}" style="display: inline-flex; align-items: center; gap: 6px; padding: 10px 18px; background: white; color: #374151; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px; font-weight: 500; text-decoration: none; cursor: pointer; transition: all 0.2s ease;"
           onmouseover="this.style.background='#f9fafb'"
           onmouseout="this.style.background='white'">
          <svg style="width: 15px; height: 15px;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
          </svg>
          Reset
        </a>
        <button type="submit" style="display: inline-flex; align-items: center; gap: 6px; padding: 10px 18px; background: var(--primary-green); color: white; border: none; border-radius: 8px; font-size: 14px; font-weight: 500; cursor: pointer; transition: all 0.2s ease;"
                onmouseover="this.style.background='#059669'"
                onmouseout="this.style.background='var(--primary-green)'">
          <svg style="width: 15px; height: 15px;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
          </svg>
          Search
        </button>
      </div>
    </form>

    <!-- Violations Table -->
    @php
      $currentViolations = $violations['all'];
    @endphp

    @if($currentViolations->isEmpty())
    <div style="text-align: center; padding: 48px; background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); margin-top: 24px;">
      <svg style="width: 64px; height: 64px; margin: 0 auto 16px; color: #9ca3af;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
      </svg>
      <h3 style="margin: 0 0 8px; color: #374151; font-size: 1.25rem;">No Violations Found</h3>
      <p style="margin: 0; color: #6b7280;">There are currently no student violations to display.</p>
    </div>
    @else
    @php
      $displayMode = request('display_mode', 'individual');
      $tableRows = $displayMode === 'grouped'
        ? $currentViolations->getCollection()->sortBy('ref_num')->values()
        : $currentViolations->getCollection();
    @endphp
    <div style="background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); overflow: hidden; margin-top: 24px;">
      <div style="padding: 24px; border-bottom: 1px solid #e9ecef;">
        <h2 style="margin: 0; color: var(--primary-green); font-size: 1.25rem; font-weight: 600;">
          All Violations ({{ $currentViolations->total() }} total violation{{ $currentViolations->total() !== 1 ? 's' : '' }})
        </h2>
      </div>
      <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; table-layout: fixed;">
          <colgroup>
            <col style="width: 13%;">
            <col style="width: 15%;">
            <col style="width: 10%;">
            <col style="width: 10%;">
            <col style="width: 22%;">
            <col style="width: 16%;">
          </colgroup>
          <thead>
            <tr style="background: white; color: black; border-bottom: 2px solid #e5e7eb;">
              <th style="padding: 12px 12px 12px 16px; text-align: left; font-weight: 600; color: black; font-size: 14px;">Reference No.</th>
              <th style="padding: 12px; text-align: left; font-weight: 600; color: black; font-size: 14px;">Student</th>
              <th style="padding: 12px; text-align: left; font-weight: 600; color: black; font-size: 14px;">Article</th>
              <th style="padding: 12px; text-align: left; font-weight: 600; color: black; font-size: 14px;">Type</th>
              <th style="padding: 12px; text-align: left; font-weight: 600; color: black; font-size: 14px;">Status</th>
              <th style="padding: 12px 16px 12px 12px; text-align: center; font-weight: 600; color: black; font-size: 14px;">Actions</th>
            </tr>
          </thead>
          <tbody>
            @php
              $violationTexts = $currentViolations->pluck('violation')->filter()->unique()->values();
              $rawArticles = \App\Models\Violation::whereIn('description', $violationTexts)->get(['offense_type', 'description', 'article']);
              $articleMap = [];
              foreach ($rawArticles as $_v) {
                $articleMap[$_v->offense_type . ':' . $_v->description] = $_v->article;
              }

              // Build proceedings map: ref_num => true if ANY record in that case has a document
              $refNums = $currentViolations->pluck('ref_num')->filter()->unique()->values();
              $refNumsWithDoc = \App\Models\StudentViolation::whereIn('ref_num', $refNums)
                ->whereNotNull('document_path')
                ->pluck('ref_num')
                ->flip()
                ->toArray();
            @endphp
            @foreach($tableRows as $student)
            <tr style="border-bottom: 1px solid #e9ecef; transition: background-color 0.2s ease;"
                onmouseover="this.style.backgroundColor='#f8f9fa'"
                onmouseout="this.style.backgroundColor='transparent'">

              <!-- Reference No. Column -->
              <td style="padding: 12px 12px 12px 16px; color: #495057; font-size: 14px;">
                <div style="font-family: monospace; font-size: 12px; color: #6b7280; word-break: break-all;">{{ $student->ref_num ?? 'N/A' }}</div>
              </td>

              <!-- Student Column -->
              <td style="padding: 12px; color: #495057; font-size: 14px;">
                <div style="font-weight: 600; color: #333; margin-bottom: 2px;">{{ $student->first_name }} {{ $student->last_name }}</div>
                <div style="font-size: 12px; color: #888; margin-bottom: 1px;">ID: {{ $student->student_id }}</div>
                <div style="font-size: 12px; color: #888;">{{ $student->course ?? 'N/A' }}</div>
              </td>

              <!-- Article Column -->
              <td style="padding: 12px; color: #495057; font-size: 14px;">
                <span style="font-weight: 600; color: #333;">
                  @php $articleKey = ($student->offense_type ?? '') . ':' . ($student->violation ?? ''); @endphp
                  @if(isset($articleMap[$articleKey]) && $articleMap[$articleKey])
                    {{ $articleMap[$articleKey] }}
                  @else
                    N/A
                  @endif
                </span>
              </td>

              <!-- Type Column -->
              <td style="padding: 12px; font-size: 14px;">
                @if($student->offense_type === 'minor')
                  <span style="display: inline-block; padding: 4px 10px; background: #FACC15 !important; color: #78350F !important; border-radius: 999px; font-size: 12px; font-weight: 600; text-transform: uppercase;">
                    Minor
                  </span>
                @else
                  <span style="display: inline-block; padding: 4px 10px; background: #EF4444 !important; color: #FFFFFF !important; border-radius: 999px; font-size: 12px; font-weight: 600; text-transform: uppercase;">
                    Major
                  </span>
                @endif
              </td>

              <!-- Status Column -->
              <td style="padding: 12px; font-size: 14px;">
                @if($student->status == 2 && $student->offense_type === 'minor')
                  <span style="color: #10b981; font-weight: 500; display: inline-flex; align-items: center; gap: 4px;"><svg style="width:12px;height:12px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg> Complied</span>
                @elseif($student->status == 2 && $student->offense_type === 'major')
                  <span style="color: #10b981; font-weight: 500; display: inline-flex; align-items: center; gap: 4px;"><svg style="width:12px;height:12px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg> Case Resolved</span>
                @elseif($student->status == 1 && $student->offense_type === 'major')
                  <span style="color: #8b5cf6; font-weight: 500; display: inline-flex; align-items: center; gap: 4px;"><svg style="width:12px;height:12px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/></svg> Proceedings Submitted</span>
                @elseif($student->status == 1 && $student->offense_type === 'minor')
                  <span style="color: #3b82f6; font-weight: 500; display: inline-flex; align-items: center; gap: 4px;"><svg style="width:12px;height:12px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg> Dean Approved</span>
                @elseif($student->status == 0 && $student->offense_type == 'major' && isset($refNumsWithDoc[$student->ref_num]))
                  <span style="color: #8b5cf6; font-weight: 500; display: inline-flex; align-items: center; gap: 4px;"><svg style="width:12px;height:12px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/></svg> Proceedings Submitted</span>
                @elseif($student->status == 0 && $student->offense_type == 'major')
                  <span style="color: #f59e0b; font-weight: 500; display: inline-flex; align-items: center; gap: 4px;"><svg style="width:12px;height:12px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2M9 5a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2M9 5a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2m-6 9 2 2 4-4"/></svg> Awaiting Proceedings</span>
                @elseif($student->status == 3)
                  <span style="color: #ef4444; font-weight: 500; display: inline-flex; align-items: center; gap: 4px;"><svg style="width:12px;height:12px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg> Declined</span>
                @else
                  <span style="color: #fbbf24; font-weight: 500; display: inline-flex; align-items: center; gap: 4px;"><svg style="width:12px;height:12px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg> Pending</span>
                @endif
              </td>

              <!-- Actions Column -->
              <td style="padding: 12px 16px 12px 12px; text-align: center;">
                <div style="display: flex; gap: 8px; justify-content: center; align-items: center;">
                  <!-- View Button -->
                  <button onclick="viewViolationModal('{{ $student->id }}')" 
                          style="background: #6b7280; color: white !important; border: none; padding: 8px 12px; border-radius: 6px; font-size: 12px; cursor: pointer; display: flex; align-items: center; gap: 6px; transition: all 0.2s ease;"
                          onmouseover="this.style.background='#4b5563'"
                          onmouseout="this.style.background='#6b7280'"
                          title="View violation details">
                    <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                      <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                    </svg>
                    View
                  </button>

                  @if($student->status == 1)
                    <button onclick="openResolveModal('{{ $student->id }}')"
                            style="background: #f59e0b; color: white; border: none; padding: 8px 12px; border-radius: 6px; font-size: 12px; cursor: pointer; display: flex; align-items: center; gap: 6px; transition: all 0.2s ease;"
                            onmouseover="this.style.background='#d97706'"
                            onmouseout="this.style.background='#f59e0b'"
                            title="Resolve this violation">
                      <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m9 12.75 2.25 2.25 4.5-4.5m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z" />
                      </svg>
                      Resolve
                    </button>
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
            Showing {{ $currentViolations->firstItem() }} to {{ $currentViolations->lastItem() }} of {{ $currentViolations->total() }} violations
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
    <div style="background: white; border-radius: 12px; padding: 24px; max-width: 450px; width: 90%; max-height: 90%; overflow-y: auto; box-shadow: 0 20px 60px rgba(0,0,0,0.3);">
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

  <!-- Resolve Violation Modal -->
  <div id="resolveViolationModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; justify-content: center; align-items: center;">
    <div style="background: white; border-radius: 12px; padding: 24px; max-width: 500px; width: 90%; box-shadow: 0 20px 60px rgba(0,0,0,0.3);">
      <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 2px solid #e5e7eb; padding-bottom: 16px;">
        <h3 style="margin: 0; color: #f59e0b; font-size: 1.5rem; font-weight: 600;">Resolve Violation</h3>
        <button onclick="closeResolveModal()" 
                style="background: none; border: none; font-size: 24px; color: #6b7280; cursor: pointer; padding: 4px;"
                onmouseover="this.style.color='#374151'"
                onmouseout="this.style.color='#6b7280'">×</button>
      </div>
      
      <div id="resolveViolationContent">
        <!-- Content will be loaded here -->
      </div>
    </div>
  </div>



<script>
  const refNumsWithDoc = @json(array_keys($refNumsWithDoc));

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
            <div style="display: flex; flex-direction: column; gap: 20px; margin-bottom: 24px;">
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
                    <span style="display: inline-block; padding: 4px 10px; background: ${violation.offense_type === 'minor' ? '#FACC15' : '#EF4444'} !important; color: ${violation.offense_type === 'minor' ? '#78350F' : '#FFFFFF'} !important; border-radius: 999px; font-size: 12px; font-weight: 600; text-transform: uppercase; margin-left: 4px;">
                      ${violation.offense_type}
                    </span>
                  </div>
                  <div style="margin-bottom: 8px;"><strong>Date Reported:</strong> ${new Date(violation.created_at).toLocaleDateString()}</div>
                  ${violation.offense_type === 'major' && violation.document_path
                    ? `<div style="margin-bottom: 8px;"><strong>Proceedings Uploaded By:</strong> ${violation.added_by || 'Unknown'}</div>`
                    : `<div style="margin-bottom: 8px;"><strong>Issued By:</strong> ${violation.added_by || 'Unknown'}</div>`
                  }
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
                    ${getStatusDisplay(violation.status, violation.offense_type, violation.document_path)}
                  </span>
                </div>
                ${violation.forwarded_to_admin_at ? `<div style="margin-bottom: 8px;"><strong>Forwarded to Admin:</strong> ${new Date(violation.forwarded_to_admin_at).toLocaleDateString()}</div>` : ''}
                ${violation.closed_at ? `<div style="margin-bottom: 8px;"><strong>Case Closed:</strong> ${new Date(violation.closed_at).toLocaleDateString()}</div>` : ''}
              </div>
            </div>
            
            ${violation.offense_type === 'major' && violation.document_path ? `
              <div style="margin-bottom: 24px;">
                <h4 style="margin: 0 0 12px; color: #374151; font-size: 1rem; font-weight: 600;">Proceedings Documents</h4>
                <div style="background: #f9fafb; padding: 16px; border-radius: 8px; border: 1px solid #e5e7eb;">
                  <div style="margin-bottom: 8px;"><strong>Uploaded By:</strong> ${violation.added_by || 'Unknown'}</div>
                  <div style="margin-bottom: 16px;"><strong>Date Uploaded:</strong> ${violation.forwarded_to_admin_at ? new Date(violation.forwarded_to_admin_at).toLocaleDateString() : new Date(violation.created_at).toLocaleDateString()}</div>
                  <div style="display: flex; gap: 16px; flex-wrap: wrap;">
                    <a href="/files/${violation.document_path}" target="_blank" 
                       style="display: inline-flex; align-items: center; gap: 8px; color: #3b82f6; text-decoration: none; font-weight: 500;">
                      <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                      </svg>
                      View Document
                    </a>
                    <a href="/admin/violation/${violation.id}/download-proceedings" 
                       style="display: inline-flex; align-items: center; gap: 8px; color: #059669; text-decoration: none; font-weight: 500;">
                      <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                      </svg>
                      Download Document
                    </a>
                  </div>
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

  function getStatusDisplay(status, offenseType, documentPath) {
    if (status == 2 && offenseType === 'minor') {
      return '<span style="background: #10b981; color: white; display:inline-flex; align-items:center; gap:4px; padding:2px 6px; border-radius:4px;"><svg style="width:11px;height:11px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg> Complied</span>';
    } else if (status == 2 && offenseType === 'major') {
      return '<span style="background: #10b981; color: white; display:inline-flex; align-items:center; gap:4px; padding:2px 6px; border-radius:4px;"><svg style="width:11px;height:11px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg> Case Resolved</span>';
    } else if (status == 3) {
      return '<span style="background: #ef4444; color: white; display:inline-flex; align-items:center; gap:4px; padding:2px 6px; border-radius:4px;"><svg style="width:11px;height:11px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg> Declined</span>';
    } else if (status == 1 && offenseType === 'major') {
      return '<span style="background: #8b5cf6; color: white; display:inline-flex; align-items:center; gap:4px; padding:2px 6px; border-radius:4px;"><svg style="width:11px;height:11px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/></svg> Proceedings Submitted</span>';
    } else if (status == 1 && offenseType === 'minor') {
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
      closeResolveModal();
    }
  });

  // Updated function name for consistency
  function viewViolationModal(violationId) {
    viewViolationDetails(violationId);
  }

  // Resolve Violation Modal Functions
  function openResolveModal(violationId) {
    const modal = document.getElementById('resolveViolationModal');
    const content = document.getElementById('resolveViolationContent');
    
    content.innerHTML = '<div style="text-align: center; padding: 40px;"><div style="color: #6b7280;">Loading violation data...</div></div>';
    modal.style.display = 'flex';
    
    fetch(`/admin/violation-details/${violationId}`)
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          const violation = data.violation;
          console.log('[openResolveModal] violation object:', violation);
          console.log('[openResolveModal] offense_type:', violation.offense_type, '| document_path:', violation.document_path, '| forwarded_to_admin_at:', violation.forwarded_to_admin_at, '| status:', violation.status);
          console.log('[openResolveModal] Warning will show?', violation.offense_type === 'major' && !violation.document_path);
          content.innerHTML = `
            <div>
              <div style="background: #f9fafb; padding: 16px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #e5e7eb;">
                <div style="margin-bottom: 6px;"><strong>Student:</strong> ${violation.first_name} ${violation.last_name} (${violation.student_id})</div>
                <div style="margin-bottom: 6px;"><strong>Violation:</strong> ${violation.violation}</div>
                <div style="margin-bottom: 6px;"><strong>Type:</strong> ${violation.offense_type.charAt(0).toUpperCase() + violation.offense_type.slice(1)}</div>
                <div><strong>Status:</strong> ${getStatusText(violation.status, violation.offense_type)}</div>
              </div>
              <p style="margin: 0 0 20px; color: #374151; font-size: 15px;">Choose an action for this violation:</p>
              <div id="resolveActions_${violationId}">
                <div style="display: flex; gap: 12px; justify-content: center; flex-wrap: wrap;">
                  <button type="button" onclick="closeResolveModal()" style="padding: 10px 18px; background: white; color: #374151; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px; font-weight: 500; cursor: pointer;" onmouseover="this.style.background='#f9fafb'" onmouseout="this.style.background='white'">Cancel</button>
                  <button type="button" onclick="showDeclineForm('${violationId}')" style="padding: 10px 18px; background: #ef4444; color: white; border: none; border-radius: 8px; font-size: 14px; font-weight: 500; cursor: pointer;" onmouseover="this.style.background='#dc2626'" onmouseout="this.style.background='#ef4444'">Decline Case</button>
                  <form method="POST" action="/admin/violation/${violationId}/close-case" style="display: inline;">
                    @csrf
                    <button type="submit" style="padding: 10px 18px; background: #10b981; color: white; border: none; border-radius: 8px; font-size: 14px; font-weight: 500; cursor: pointer;" onmouseover="this.style.background='#059669'" onmouseout="this.style.background='#10b981'">Approve Case</button>
                  </form>
                </div>
              </div>
              <div id="declineForm_${violationId}" style="display: none;">
                <form method="POST" action="/admin/violation/${violationId}/decline-case">
                  @csrf
                  <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #333; font-size: 14px;">Reason for declining <span style="color: #ef4444;">*</span></label>
                  <textarea name="decline_reason" required maxlength="1000" rows="4" placeholder="Enter the reason for declining this violation case..." style="width: 100%; padding: 12px; border: 2px solid #e1e5e9; border-radius: 8px; font-size: 14px; resize: vertical; margin-bottom: 16px; box-sizing: border-box;"></textarea>
                  <div style="display: flex; gap: 12px; justify-content: center;">
                    <button type="button" onclick="hideDeclineForm('${violationId}')" style="padding: 10px 18px; background: white; color: #374151; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px; font-weight: 500; cursor: pointer;" onmouseover="this.style.background='#f9fafb'" onmouseout="this.style.background='white'">Back</button>
                    <button type="submit" style="padding: 10px 18px; background: #ef4444; color: white; border: none; border-radius: 8px; font-size: 14px; font-weight: 500; cursor: pointer;" onmouseover="this.style.background='#dc2626'" onmouseout="this.style.background='#ef4444'">Confirm Decline</button>
                  </div>
                </form>
              </div>
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

  function closeResolveModal() {
    document.getElementById('resolveViolationModal').style.display = 'none';
  }

  function showDeclineForm(violationId) {
    document.getElementById('resolveActions_' + violationId).style.display = 'none';
    document.getElementById('declineForm_' + violationId).style.display = 'block';
  }

  function hideDeclineForm(violationId) {
    document.getElementById('declineForm_' + violationId).style.display = 'none';
    document.getElementById('resolveActions_' + violationId).style.display = 'block';
  }



  function getStatusText(status, offenseType) {
    if (status == 2 && offenseType === 'minor') {
      return 'Complied';
    } else if (status == 2 && offenseType === 'major') {
      return 'Case Resolved';
    } else if (status == 3) {
      return 'Declined';
    } else if (status == 1 && offenseType === 'major') {
      return 'Proceedings Submitted';
    } else if (status == 1 && offenseType === 'minor') {
      return 'Dean Approved';
    } else if (status == 0 && offenseType == 'major') {
      return 'Awaiting Proceedings';
    } else {
      return 'Pending';
    }
  }

  function getStatusTextWithDoc(status, offenseType, documentPath) {
    if (status == 0 && offenseType === 'major' && documentPath) {
      return 'Proceedings Submitted';
    }
    return getStatusText(status, offenseType);
  }

  // Close modals when clicking outside
  document.getElementById('resolveViolationModal').addEventListener('click', function(e) {
    if (e.target === this) {
      closeResolveModal();
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