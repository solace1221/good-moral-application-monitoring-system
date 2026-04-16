<x-dashboard-layout>
  <x-slot name="roleTitle">Moderator</x-slot>

  <x-slot name="navigation">
    <x-moderator-navigation />
  </x-slot>

  <!-- Header Section -->
  <div class="header-section">
    <div>
      <h1 class="role-title">Certificate Release</h1>
      <p class="welcome-text">Manage certificates ready for printing, release, and track claimed certificates</p>
      <div class="accent-line"></div>
    </div>
  </div>
  <!-- Status Messages -->
  @include('shared.alerts.flash')

  @if(session('pdf_url'))
  <script>
    window.addEventListener('load', function() {
      window.open("{{ session('pdf_url') }}", '_blank');
    });
  </script>
  @endif

  @php
    $deptBg = function(string $d): string {
      return match(strtoupper($d)) {
        'SITE'   => '#e9ecef',
        'SNAHS'  => '#fce4ec',
        'SBAHM'  => '#e8f5e9',
        'SASTE'  => '#dbeafe',
        default  => '#f0f0f0',
      };
    };
    $deptColor = function(string $d): string {
      return match(strtoupper($d)) {
        'SITE'   => '#495057',
        'SNAHS'  => '#c62828',
        'SBAHM'  => '#2e7d32',
        'SASTE'  => '#1565c0',
        default  => '#6c757d',
      };
    };
  @endphp

  <!-- Search and Filter Controls -->
  <div style="background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); margin-top: 24px; padding: 20px;">
    <div style="display: grid; grid-template-columns: 1fr 200px 200px; gap: 16px; align-items: end;">
      <div>
        <label style="display: block; margin-bottom: 6px; font-weight: 600; color: #495057; font-size: 14px;">
          Search Applications
        </label>
        <input type="text" id="searchInput" placeholder="Search by name or reference number..."
               style="width: 100%; padding: 10px 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;"
               oninput="filterApplications()">
      </div>
      <div>
        <label style="display: block; margin-bottom: 6px; font-weight: 600; color: #495057; font-size: 14px;">
          Department
        </label>
        <select id="departmentFilter" onchange="filterApplications()"
                style="width: 100%; padding: 10px 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
          <option value="">All Departments</option>
          @foreach($departments as $dept)
            <option value="{{ $dept }}">{{ $dept }}</option>
          @endforeach
        </select>
      </div>
      <div>
        <label style="display: block; margin-bottom: 6px; font-weight: 600; color: #495057; font-size: 14px;">
          Status
        </label>
        <select id="statusFilter" onchange="filterApplications()"
                style="width: 100%; padding: 10px 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
          <option value="">All Status</option>
          <option value="Ready to Print">Ready to Print</option>
          <option value="Ready for Pickup">Ready for Pickup</option>
          <option value="Claimed">Claimed</option>
        </select>
      </div>
    </div>
    <div style="margin-top: 16px; display: flex; gap: 8px; justify-content: flex-end;">
      <button onclick="filterApplications()" class="btn btn-success" style="padding: 8px 20px; font-size: 14px; cursor: pointer;">Search</button>
      <button onclick="clearFilters()" class="btn btn-secondary" style="padding: 8px 20px; font-size: 14px; cursor: pointer;">Clear Filters</button>
    </div>
  </div>

  <!-- Filter Tabs -->
  <div style="background: white; border-radius: 12px 12px 0 0; box-shadow: 0 2px 8px rgba(0,0,0,0.1); margin-top: 16px;">
    <div style="display: flex; border-bottom: 1px solid #e9ecef; flex-wrap: wrap;">
      <button onclick="showTab('ready_to_print')" id="tab-ready_to_print" class="tab-button active" style="flex: 1; min-width: 120px; padding: 12px 8px; border: none; background: none; cursor: pointer; font-weight: 600; color: var(--primary-green); border-bottom: 3px solid var(--primary-green); font-size: 13px;">
        Ready to Print (<span id="count-ready_to_print">{{ $applications['ready_to_print']->count() }}</span>)
      </button>
      <button onclick="showTab('ready_for_pickup')" id="tab-ready_for_pickup" class="tab-button" style="flex: 1; min-width: 120px; padding: 12px 8px; border: none; background: none; cursor: pointer; font-weight: 600; color: #6c757d; border-bottom: 3px solid transparent; font-size: 13px;">
        Ready for Pickup (<span id="count-ready_for_pickup">{{ $applications['ready_for_pickup']->count() }}</span>)
      </button>
      <button onclick="showTab('claimed')" id="tab-claimed" class="tab-button" style="flex: 1; min-width: 120px; padding: 12px 8px; border: none; background: none; cursor: pointer; font-weight: 600; color: #6c757d; border-bottom: 3px solid transparent; font-size: 13px;">
        Claimed (<span id="count-claimed">{{ $applications['claimed']->count() }}</span>)
      </button>
    </div>
  </div>

  {{-- â•â•â•â•â•â•â• Ready to Print Tab â•â•â•â•â•â•â• --}}
  <div id="content-ready_to_print" class="tab-content" style="background: white; border-radius: 0 0 12px 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); overflow: hidden;">
    <div style="padding: 20px 24px; border-bottom: 1px solid #e9ecef;">
      <h2 style="margin: 0; color: #0d6efd; font-size: 1.1rem; font-weight: 600;">Ready to Print</h2>
    </div>
    @if($applications['ready_to_print']->isEmpty())
    <div style="padding: 48px; text-align: center; color: #6c757d;">
      <svg style="width: 64px; height: 64px; margin: 0 auto 16px; color: #dee2e6;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
      </svg>
      <p style="margin: 0; font-size: 1rem; font-weight: 500;">No certificates waiting to be printed</p>
    </div>
    @else
    <div style="overflow-x: auto;">
      <table style="width: 100%; border-collapse: collapse;">
        <thead>
          <tr style="background: #f8f9fa; border-bottom: 2px solid #e9ecef;">
            <th style="padding: 12px 16px; text-align: left; font-weight: 600; color: #495057; font-size: 13px;">Reference Number</th>
            <th style="padding: 12px 16px; text-align: left; font-weight: 600; color: #495057; font-size: 13px;">Name</th>
            <th style="padding: 12px 16px; text-align: left; font-weight: 600; color: #495057; font-size: 13px;">Department</th>
            <th style="padding: 12px 16px; text-align: left; font-weight: 600; color: #495057; font-size: 13px;">Certificate</th>
            <th style="padding: 12px 16px; text-align: left; font-weight: 600; color: #495057; font-size: 13px;">Status</th>
            <th style="padding: 12px 16px; text-align: left; font-weight: 600; color: #495057; font-size: 13px;">Actions</th>
          </tr>
        </thead>
        <tbody>
          @foreach($applications['ready_to_print'] as $application)
          <tr style="border-bottom: 1px solid #f0f0f0;" onmouseover="this.style.backgroundColor='#f8f9fa'" onmouseout="this.style.backgroundColor='transparent'">
            <td style="padding: 10px 16px; color: #495057; font-size: 13px; font-weight: 500;">{{ $application->reference_number }}</td>
            <td style="padding: 10px 16px; color: #495057; font-size: 13px;">{{ $application->fullname }}</td>
            <td style="padding: 10px 16px; font-size: 13px;">
              <span style="display: inline-block; padding: 3px 8px; background: {{ $deptBg($application->department) }}; color: {{ $deptColor($application->department) }}; border-radius: 4px; font-size: 12px; font-weight: 500;">{{ $application->department }}</span>
            </td>
            <td style="padding: 10px 16px; font-size: 13px;">
              <span style="display: inline-block; padding: 3px 8px; background: {{ $application->certificate_type === 'good_moral' ? '#e8f5e8' : '#fff3cd' }}; color: {{ $application->certificate_type === 'good_moral' ? 'var(--primary-green)' : '#856404' }}; border-radius: 4px; font-size: 12px; font-weight: 500;">
                {{ $application->certificate_type === 'good_moral' ? 'Good Moral' : 'Residency' }}
              </span>
            </td>
            <td style="padding: 10px 16px; font-size: 13px;">
              <span style="display: inline-block; padding: 4px 10px; background: #cfe2ff; color: #0d6efd; border-radius: 20px; font-size: 12px; font-weight: 600; white-space: nowrap;">Ready to Print</span>
            </td>
            <td style="padding: 10px 16px;">
              <div style="display: flex; gap: 8px; align-items: center;">
                <button data-application='@json($application)' onclick="openGoodMoralModal(this)"
                        class="btn btn-sm btn-outline-secondary" title="View Application">
                  <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16"><path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8M1.173 8a13 13 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5s3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5s-3.879-1.168-5.168-2.457A13 13 0 0 1 1.172 8z"/><path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5M4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0"/></svg>
                </button>
                {{-- Print Certificate (first print) --}}
                <form action="{{ route('moderator.printCertificate', $application->id) }}" method="POST" style="display:inline;">@csrf
                  <button type="submit" onclick="return confirm('Print this certificate?')"
                          class="btn btn-sm btn-outline-primary" title="Print Certificate">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16"><path d="M2.5 8a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1"/><path d="M5 1a2 2 0 0 0-2 2v2H2a2 2 0 0 0-2 2v3a2 2 0 0 0 2 2h1v1a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2v-1h1a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-1V3a2 2 0 0 0-2-2zM4 3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2H4zm1 5a2 2 0 0 0-2 2v1H2a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-1v-1a2 2 0 0 0-2-2zm7 2v3a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1"/></svg>
                  </button>
                </form>
                {{-- Download Certificate --}}
                <a href="{{ route('moderator.downloadCertificate', $application->id) }}"
                   class="btn btn-sm btn-outline-info" title="Download Certificate">
                  <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16"><path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5"/><path d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708z"/></svg>
                </a>
              </div>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    @endif
  </div>

  {{-- â•â•â•â•â•â•â• Ready for Pickup Tab â•â•â•â•â•â•â• --}}
  <div id="content-ready_for_pickup" class="tab-content" style="background: white; border-radius: 0 0 12px 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); overflow: hidden; display: none;">
    <div style="padding: 20px 24px; border-bottom: 1px solid #e9ecef;">
      <h2 style="margin: 0; color: var(--primary-green); font-size: 1.1rem; font-weight: 600;">Ready for Pickup</h2>
    </div>
    @if($applications['ready_for_pickup']->isEmpty())
    <div style="padding: 48px; text-align: center; color: #6c757d;">
      <svg style="width: 64px; height: 64px; margin: 0 auto 16px; color: #dee2e6;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
      </svg>
      <p style="margin: 0; font-size: 1rem; font-weight: 500;">No certificates ready for pickup</p>
    </div>
    @else
    <div style="overflow-x: auto;">
      <table style="width: 100%; border-collapse: collapse;">
        <thead>
          <tr style="background: #f8f9fa; border-bottom: 2px solid #e9ecef;">
            <th style="padding: 12px 16px; text-align: left; font-weight: 600; color: #495057; font-size: 13px;">Reference Number</th>
            <th style="padding: 12px 16px; text-align: left; font-weight: 600; color: #495057; font-size: 13px;">Name</th>
            <th style="padding: 12px 16px; text-align: left; font-weight: 600; color: #495057; font-size: 13px;">Department</th>
            <th style="padding: 12px 16px; text-align: left; font-weight: 600; color: #495057; font-size: 13px;">Certificate</th>
            <th style="padding: 12px 16px; text-align: left; font-weight: 600; color: #495057; font-size: 13px;">Status</th>
            <th style="padding: 12px 16px; text-align: left; font-weight: 600; color: #495057; font-size: 13px;">Actions</th>
          </tr>
        </thead>
        <tbody>
          @foreach($applications['ready_for_pickup'] as $application)
          <tr style="border-bottom: 1px solid #f0f0f0;" onmouseover="this.style.backgroundColor='#f8f9fa'" onmouseout="this.style.backgroundColor='transparent'">
            <td style="padding: 10px 16px; color: #495057; font-size: 13px; font-weight: 500;">{{ $application->reference_number }}</td>
            <td style="padding: 10px 16px; color: #495057; font-size: 13px;">{{ $application->fullname }}</td>
            <td style="padding: 10px 16px; font-size: 13px;">
              <span style="display: inline-block; padding: 3px 8px; background: {{ $deptBg($application->department) }}; color: {{ $deptColor($application->department) }}; border-radius: 4px; font-size: 12px; font-weight: 500;">{{ $application->department }}</span>
            </td>
            <td style="padding: 10px 16px; font-size: 13px;">
              <span style="display: inline-block; padding: 3px 8px; background: {{ $application->certificate_type === 'good_moral' ? '#e8f5e8' : '#fff3cd' }}; color: {{ $application->certificate_type === 'good_moral' ? 'var(--primary-green)' : '#856404' }}; border-radius: 4px; font-size: 12px; font-weight: 500;">
                {{ $application->certificate_type === 'good_moral' ? 'Good Moral' : 'Residency' }}
              </span>
            </td>
            <td style="padding: 10px 16px; font-size: 13px;">
              <span style="display: inline-block; padding: 4px 10px; background: #d1e7dd; color: #0f5132; border-radius: 20px; font-size: 12px; font-weight: 600; white-space: nowrap;">Ready for Pickup</span>
            </td>
            <td style="padding: 10px 16px;">
              <div style="display: flex; gap: 8px; align-items: center;">
                {{-- View --}}
                <button data-application='@json($application)' onclick="openGoodMoralModal(this)"
                        class="btn btn-sm btn-outline-secondary" title="View Application">
                  <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16"><path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8M1.173 8a13 13 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5s3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5s-3.879-1.168-5.168-2.457A13 13 0 0 1 1.172 8z"/><path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5M4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0"/></svg>
                </button>
                {{-- Reprint Certificate --}}
                <form action="{{ route('moderator.printCertificate', $application->id) }}" method="POST" style="display:inline;">@csrf
                  <button type="submit" onclick="return confirm('Reprint this certificate?')"
                          class="btn btn-sm btn-outline-primary" title="Reprint Certificate">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16"><path d="M2.5 8a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1"/><path d="M5 1a2 2 0 0 0-2 2v2H2a2 2 0 0 0-2 2v3a2 2 0 0 0 2 2h1v1a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2v-1h1a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-1V3a2 2 0 0 0-2-2zM4 3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2H4zm1 5a2 2 0 0 0-2 2v1H2a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-1v-1a2 2 0 0 0-2-2zm7 2v3a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1"/></svg>
                  </button>
                </form>
                {{-- Mark as Claimed --}}
                <form action="{{ route('moderator.markAsClaimed', $application->id) }}" method="POST" style="display:inline;">@csrf
                  <button type="button" onclick="openClaimModal(this.closest('form'), '{{ addslashes($application->fullname) }}')"
                          class="btn btn-sm btn-outline-success" title="Mark as Claimed">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16"><path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/><path d="m10.97 4.97-.02.022-3.473 4.425-2.093-2.094a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-1.071-1.05"/></svg>
                  </button>
                </form>
              </div>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    @endif
  </div>

  <!-- Claimed Tab -->
  <div id="content-claimed" class="tab-content" style="background: white; border-radius: 0 0 12px 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); overflow: hidden; display: none;">
    <div style="padding: 20px 24px; border-bottom: 1px solid #e9ecef;">
      <h2 style="margin: 0; color: #6f42c1; font-size: 1.1rem; font-weight: 600;">Claimed Certificates</h2>
    </div>
    @if($applications['claimed']->isEmpty())
    <div style="padding: 48px; text-align: center; color: #6c757d;">
      <p style="margin: 0; font-size: 1rem; font-weight: 500;">No claimed certificates</p>
    </div>
    @else
    <div style="overflow-x: auto;">
      <table style="width: 100%; border-collapse: collapse;">
        <thead>
          <tr style="background: #f8f9fa; border-bottom: 2px solid #e9ecef;">
            <th style="padding: 12px 16px; text-align: left; font-weight: 600; color: #495057; font-size: 13px;">Reference Number</th>
            <th style="padding: 12px 16px; text-align: left; font-weight: 600; color: #495057; font-size: 13px;">Name</th>
            <th style="padding: 12px 16px; text-align: left; font-weight: 600; color: #495057; font-size: 13px;">Department</th>
            <th style="padding: 12px 16px; text-align: left; font-weight: 600; color: #495057; font-size: 13px;">Certificate</th>
            <th style="padding: 12px 16px; text-align: left; font-weight: 600; color: #495057; font-size: 13px;">Status</th>
            <th style="padding: 12px 16px; text-align: left; font-weight: 600; color: #495057; font-size: 13px;">Actions</th>
          </tr>
        </thead>
        <tbody>
          @foreach($applications['claimed'] as $application)
          <tr style="border-bottom: 1px solid #f0f0f0;" onmouseover="this.style.backgroundColor='#f8f9fa'" onmouseout="this.style.backgroundColor='transparent'">
            <td style="padding: 10px 16px; color: #495057; font-size: 13px; font-weight: 500;">{{ $application->reference_number }}</td>
            <td style="padding: 10px 16px; color: #495057; font-size: 13px;">{{ $application->fullname }}</td>
            <td style="padding: 10px 16px; font-size: 13px;">
              <span style="display: inline-block; padding: 3px 8px; background: {{ $deptBg($application->department) }}; color: {{ $deptColor($application->department) }}; border-radius: 4px; font-size: 12px; font-weight: 500;">{{ $application->department }}</span>
            </td>
            <td style="padding: 10px 16px; font-size: 13px;">
              <span style="display: inline-block; padding: 3px 8px; background: {{ $application->certificate_type === 'good_moral' ? '#e8f5e8' : '#fff3cd' }}; color: {{ $application->certificate_type === 'good_moral' ? 'var(--primary-green)' : '#856404' }}; border-radius: 4px; font-size: 12px; font-weight: 500;">
                {{ $application->certificate_type === 'good_moral' ? 'Good Moral' : 'Residency' }}
              </span>
            </td>
            <td style="padding: 10px 16px; font-size: 13px;">
              <span style="display: inline-block; padding: 4px 10px; background: #e8d5ff; color: #6f42c1; border-radius: 20px; font-size: 12px; font-weight: 600; white-space: nowrap;">Claimed</span>
            </td>
            <td style="padding: 10px 16px;">
              <div style="display: flex; gap: 8px; align-items: center;">
                <button data-application='@json($application)' onclick="openGoodMoralModal(this)"
                        class="btn btn-sm btn-outline-secondary" title="View Application">
                  <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16"><path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8M1.173 8a13 13 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5s3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5s-3.879-1.168-5.168-2.457A13 13 0 0 1 1.172 8z"/><path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5M4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0"/></svg>
                </button>
                <form action="{{ route('moderator.printCertificate', $application->id) }}" method="POST" style="display:inline;">@csrf
                  <button type="submit" onclick="return confirm('Reprint this certificate?')"
                          class="btn btn-sm btn-outline-warning" title="Reprint Certificate">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16"><path d="M2.5 8a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1"/><path d="M5 1a2 2 0 0 0-2 2v2H2a2 2 0 0 0-2 2v3a2 2 0 0 0 2 2h1v1a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2v-1h1a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-1V3a2 2 0 0 0-2-2zM4 3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2H4zm1 5a2 2 0 0 0-2 2v1H2a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-1v-1a2 2 0 0 0-2-2zm7 2v3a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1"/></svg>
                  </button>
                </form>
              </div>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    @endif
  </div>

  <!-- Modal -->
  <div id="modal" style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 1050; display: none; align-items: center; justify-content: center; padding: 16px;">
    <div style="background: white; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.2); width: 100%; max-width: 600px; max-height: 90vh; display: flex; flex-direction: column; overflow: hidden;">

      <!-- Header -->
      <div style="display: flex; justify-content: space-between; align-items: center; padding: 16px 20px; border-bottom: 1px solid #e9ecef; flex-shrink: 0;">
        <h5 style="margin: 0; color: var(--primary-green); font-size: 1.1rem; font-weight: 600;">Application Details</h5>
        <button onclick="closeModal()" style="background: none; border: none; font-size: 22px; cursor: pointer; color: #6c757d; line-height: 1;">&times;</button>
      </div>

      <!-- Scrollable Body -->
      <div style="padding: 1.25rem 1.5rem; overflow-y: auto; flex: 1;">
        <div style="display: grid; gap: 10px;">
          <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 8px; padding: 10px 12px; background: #f8f9fa; border-radius: 6px; font-size: 14px;">
            <strong>Full Name:</strong>
            <span id="modalFullName"></span>
          </div>
          <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 8px; padding: 10px 12px; background: #f8f9fa; border-radius: 6px; font-size: 14px;">
            <strong>Reference Number:</strong>
            <span id="modalrefnum"></span>
          </div>
          <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 8px; padding: 10px 12px; background: #f8f9fa; border-radius: 6px; font-size: 14px;">
            <strong>Number of Copies:</strong>
            <span id="modalnumcop"></span>
          </div>
          <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 8px; padding: 10px 12px; background: #f8f9fa; border-radius: 6px; font-size: 14px;">
            <strong>Certificate Type:</strong>
            <span id="modalCertificateType"></span>
          </div>
          <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 8px; padding: 10px 12px; background: #f8f9fa; border-radius: 6px; font-size: 14px;">
            <strong>Status:</strong>
            <span id="modalStatus"></span>
          </div>
          <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 8px; padding: 10px 12px; background: #f8f9fa; border-radius: 6px; font-size: 14px;">
            <strong>Reason:</strong>
            <span id="modalReason"></span>
          </div>
          <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 8px; padding: 10px 12px; background: #f8f9fa; border-radius: 6px; font-size: 14px;">
            <strong>Course & Year Level:</strong>
            <span id="modalCourseYearLevel"></span>
          </div>
          <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 8px; padding: 10px 12px; background: #f8f9fa; border-radius: 6px; font-size: 14px;">
            <strong>Last Semester SY:</strong>
            <span id="modalLastSemesterSY"></span>
          </div>
        </div>

        {{-- Claim info: only shown when status is Claimed --}}
        <div id="modalClaimSection" style="display: none; margin-top: 16px; padding: 16px; background: #f3e8ff; border-left: 4px solid #6f42c1; border-radius: 6px;">
          <div style="font-size: 12px; font-weight: 700; color: #6f42c1; text-transform: uppercase; margin-bottom: 8px;">Certificate Claimed</div>
          <div style="display: flex; flex-wrap: wrap; gap: 12px; font-size: 13px; color: #495057;">
            <div><strong>Released by:</strong> <span id="modalClaimedBy"></span></div>
            <div><strong>Released on:</strong> <span id="modalClaimedAt"></span></div>
          </div>
        </div>
      </div>

      <!-- Footer -->
      <div style="display: flex; justify-content: flex-end; padding: 12px 20px; border-top: 1px solid #e9ecef; flex-shrink: 0;">
        <button onclick="closeModal()" style="background: #6c757d; color: white; border: none; padding: 8px 20px; border-radius: 6px; font-size: 14px; cursor: pointer;">Close</button>
      </div>
    </div>
  </div>

  <script>
    // Tab functionality
    function showTab(tabName) {
      const contents = document.querySelectorAll('.tab-content');
      contents.forEach(content => content.style.display = 'none');

      const tabs = document.querySelectorAll('.tab-button');
      tabs.forEach(tab => {
        tab.style.color = '#6c757d';
        tab.style.borderBottomColor = 'transparent';
      });

      document.getElementById('content-' + tabName).style.display = 'block';

      const activeTab = document.getElementById('tab-' + tabName);
      activeTab.style.color = 'var(--primary-green)';
      activeTab.style.borderBottomColor = 'var(--primary-green)';

      filterApplications();
    }

    // Filter applications
    function filterApplications() {
      const searchTerm = document.getElementById('searchInput').value.toLowerCase();
      const departmentFilter = document.getElementById('departmentFilter').value;
      const statusFilter = document.getElementById('statusFilter').value;

      const activeTab = document.querySelector('.tab-button[style*="var(--primary-green)"]');
      const activeTabName = activeTab ? activeTab.id.replace('tab-', '') : 'ready_to_print';

      const table = document.querySelector(`#content-${activeTabName} tbody`);
      if (!table) return;

      const rows = table.querySelectorAll('tr');

      rows.forEach(row => {
        const cells = row.querySelectorAll('td');
        if (cells.length === 0) return;

        const referenceNumber = cells[0].textContent.toLowerCase();
        const fullName = cells[1].textContent.toLowerCase();
        const department = cells[2].textContent.trim();
        const status = cells[4].textContent.trim();

        let showRow = true;

        if (searchTerm && !referenceNumber.includes(searchTerm) && !fullName.includes(searchTerm)) {
          showRow = false;
        }

        if (departmentFilter && department !== departmentFilter) {
          showRow = false;
        }

        if (statusFilter && status !== statusFilter) {
          showRow = false;
        }

        row.style.display = showRow ? '' : 'none';
      });

      updateTabCounts();
    }

    function updateTabCounts() {
      ['ready_to_print', 'ready_for_pickup', 'claimed'].forEach(tabName => {
        const contentDiv = document.getElementById(`content-${tabName}`);
        if (contentDiv) {
          const visibleRows = contentDiv.querySelectorAll('tbody tr:not([style*="display: none"])');
          const countSpan = document.getElementById(`count-${tabName}`);
          if (countSpan) countSpan.textContent = visibleRows.length;
        }
      });
    }

    function clearFilters() {
      document.getElementById('searchInput').value = '';
      document.getElementById('departmentFilter').value = '';
      document.getElementById('statusFilter').value = '';
      filterApplications();
    }

    // Modal
    function openGoodMoralModal(button) {
      const app = JSON.parse(button.getAttribute('data-application'));
      document.getElementById('modalFullName').innerText = app.fullname ?? 'N/A';
      document.getElementById('modalrefnum').innerText = app.reference_number ?? 'N/A';
      document.getElementById('modalnumcop').innerText = app.number_of_copies ?? 'N/A';
      document.getElementById('modalCertificateType').innerText = app.certificate_type === 'good_moral' ? 'Good Moral Certificate' : 'Certificate of Residency';

      const statusMap = {
        'Ready for Moderator Print': 'Ready to Print',
        'Ready for Pickup': 'Ready for Pickup',
        'Claimed': 'Claimed'
      };
      document.getElementById('modalStatus').innerText = statusMap[app.application_status] ?? (app.application_status ?? 'N/A');
      document.getElementById('modalReason').innerText = app.reason ?? 'N/A';

      let courseYearLevel = app.course_completed ?? 'N/A';
      if (app.last_course_year_level) {
        courseYearLevel += ' - ' + app.last_course_year_level;
      }
      document.getElementById('modalCourseYearLevel').innerText = courseYearLevel;
      document.getElementById('modalLastSemesterSY').innerText = app.last_semester_sy ?? 'N/A';

      const claimSection = document.getElementById('modalClaimSection');
      if (app.application_status === 'Claimed') {
        claimSection.style.display = 'block';
        document.getElementById('modalClaimedBy').innerText = app.claimer_name ?? 'Unknown';
        const claimedAt = app.claimed_at ? new Date(app.claimed_at).toLocaleString() : 'N/A';
        document.getElementById('modalClaimedAt').innerText = claimedAt;
      } else {
        claimSection.style.display = 'none';
      }
      document.getElementById('modal').style.display = 'flex';
    }

    function closeModal() {
      document.getElementById('modal').style.display = 'none';
    }

    document.getElementById('modal').addEventListener('click', function(e) {
      if (e.target === this) closeModal();
    });

    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape') closeModal();
    });

    document.addEventListener('DOMContentLoaded', function() {
      showTab('ready_to_print');
    });

    document.addEventListener('keydown', function(e) {
      if (e.key === 'ArrowLeft' || e.key === 'ArrowRight') {
        const tabs = ['ready_to_print', 'ready_for_pickup', 'claimed'];
        const activeTab = document.querySelector('.tab-button[style*="var(--primary-green)"]');
        if (activeTab) {
          const currentIndex = tabs.indexOf(activeTab.id.replace('tab-', ''));
          let nextIndex;
          if (e.key === 'ArrowLeft') {
            nextIndex = currentIndex > 0 ? currentIndex - 1 : tabs.length - 1;
          } else {
            nextIndex = currentIndex < tabs.length - 1 ? currentIndex + 1 : 0;
          }
          showTab(tabs[nextIndex]);
        }
      }
    });
  </script>

  <!-- Mark as Claimed Confirmation Modal -->
  <div id="claimModal" style="display:none; position:fixed; inset:0; z-index:9999; align-items:center; justify-content:center; background:rgba(0,0,0,0.5);">
    <div style="background:#fff; border-radius:12px; padding:28px 32px; max-width:440px; width:90%; box-shadow:0 20px 60px rgba(0,0,0,0.2);">
      <div style="display:flex; align-items:center; gap:12px; margin-bottom:16px;">
        <div style="width:42px; height:42px; border-radius:8px; background:#f0fdf4; display:flex; align-items:center; justify-content:center; flex-shrink:0; border:1px solid #bbf7d0;">
          <svg xmlns="http://www.w3.org/2000/svg" style="width:20px; height:20px; color:#16a34a;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
        </div>
        <h3 style="margin:0; font-size:17px; font-weight:700;">Mark as Claimed</h3>
      </div>
      <p style="margin:0 0 6px; font-size:14px; color:#111827; font-weight:500;">Are you sure you want to mark this certificate as claimed?</p>
      <p style="margin:0 0 24px; font-size:14px; color:#6b7280;" id="claimStudentName"></p>
      <div style="display:flex; gap:10px; justify-content:flex-end;">
        <button onclick="closeClaimModal()"
          style="padding:9px 20px; background:#f3f4f6; color:#374151; border:none; border-radius:6px; font-size:14px; cursor:pointer; font-weight:500;"
          onmouseover="this.style.background='#e5e7eb'" onmouseout="this.style.background='#f3f4f6'">Cancel</button>
        <button id="claimConfirmBtn"
          style="padding:9px 20px; background:#495057; color:#fff; border:none; border-radius:6px; font-size:14px; cursor:pointer; font-weight:500; display:inline-flex; align-items:center; gap:7px;"
          onmouseover="this.style.opacity='0.85'" onmouseout="this.style.opacity='1'">
          <svg xmlns="http://www.w3.org/2000/svg" style="width:14px; height:14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          Confirm Claimed
        </button>
      </div>
    </div>
  </div>

  <script>
    let _claimForm = null;

    function openClaimModal(form, studentName) {
      _claimForm = form;
      document.getElementById('claimStudentName').textContent = studentName ? 'Student: ' + studentName : '';
      document.getElementById('claimModal').style.display = 'flex';
    }

    function closeClaimModal() {
      document.getElementById('claimModal').style.display = 'none';
      _claimForm = null;
    }

    document.getElementById('claimConfirmBtn').addEventListener('click', function () {
      if (_claimForm) _claimForm.submit();
    });

    document.getElementById('claimModal').addEventListener('click', function (e) {
      if (e.target === this) closeClaimModal();
    });
  </script>

</x-dashboard-layout>
