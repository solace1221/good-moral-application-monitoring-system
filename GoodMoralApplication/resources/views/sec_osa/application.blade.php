<x-dashboard-layout>
  <x-slot name="roleTitle">Moderator</x-slot>

  <x-slot name="navigation">
    <x-sec-osa-navigation />
  </x-slot>

  <!-- Header Section -->
  <div class="header-section">
    <div>
      <h1 class="role-title">Certificate Applications</h1>
      <p class="welcome-text">Review and manage Good Moral and Residency certificate applications</p>
      <div class="accent-line"></div>
    </div>
  </div>
  <!-- Status Messages -->
  @if(session('success'))
  <div style="margin-bottom: 24px; padding: 16px; background: #d4edda; color: #155724; border: 1px solid #c3e6cb; border-radius: 8px;">
    {{ session('success') }}
  </div>
  @endif

  @if(session('pdf_url'))
  <script>
    window.addEventListener('load', function() {
      window.open("{{ session('pdf_url') }}", '_blank');
    });
  </script>
  @endif

  <!-- Search and Filter Controls -->
  <div style="background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); margin-top: 24px; padding: 20px;">
    <div style="display: grid; grid-template-columns: 1fr 200px 200px 200px; gap: 16px; align-items: end;">
      <!-- Search Input -->
      <div>
        <label style="display: block; margin-bottom: 6px; font-weight: 600; color: #495057; font-size: 14px;">
          Search Applications
        </label>
        <input type="text" id="searchInput" placeholder="Search by name or student ID..." 
               style="width: 100%; padding: 10px 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;"
               oninput="filterApplications()">
      </div>
      
      <!-- Department Filter -->
      <div>
        <label style="display: block; margin-bottom: 6px; font-weight: 600; color: #495057; font-size: 14px;">
          Department
        </label>
        <select id="departmentFilter" onchange="filterApplications()" 
                style="width: 100%; padding: 10px 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
          <option value="">All Departments</option>
          <option value="BSIT">BSIT</option>
          <option value="BLIS">BLIS</option>
          <option value="BSCE">BSCE</option>
          <option value="BSCPE">BSCPE</option>
          <option value="BSENSE">BSENSE</option>
          <option value="BSN">BSN</option>
          <option value="BSPH">BSPH</option>
          <option value="BSMT">BSMT</option>
          <option value="BSPT">BSPT</option>
          <option value="BSRT">BSRT</option>
          <option value="BSM">BSM</option>
          <option value="BSA">BSA</option>
          <option value="BSE">BSE</option>
          <option value="BSBAMM">BSBAMM</option>
          <option value="BSBAMFM">BSBAMFM</option>
          <option value="BSBAMOP">BSBAMOP</option>
        </select>
      </div>
      
      <!-- Status Filter -->
      <div>
        <label style="display: block; margin-bottom: 6px; font-weight: 600; color: #495057; font-size: 14px;">
          Status
        </label>
        <select id="statusFilter" onchange="filterApplications()" 
                style="width: 100%; padding: 10px 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
          <option value="">All Status</option>
          <option value="With Registrar">With Registrar</option>
          <option value="Approved by Registrar">With Dean</option>
          <option value="Approved by Dean">With Administrator</option>
          <option value="Approved by Administrator">Payment Required</option>
          <option value="Ready for Moderator Print">Ready for Print</option>
          <option value="Ready for Pickup">Printed</option>
          <option value="rejected">Rejected</option>
        </select>
      </div>
      
      <!-- Date Filter -->
      <div>
        <label style="display: block; margin-bottom: 6px; font-weight: 600; color: #495057; font-size: 14px;">
          Date Range
        </label>
        <input type="date" id="dateFilter" onchange="filterApplications()" 
               style="width: 100%; padding: 10px 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
      </div>
    </div>
    
    <!-- Results Summary -->
    <div style="margin-top: 16px; padding-top: 16px; border-top: 1px solid #e9ecef;">
      <span id="resultsCount" style="color: #6c757d; font-size: 14px;">Showing all applications</span>
      <button onclick="clearFilters()" style="float: right; background: #6c757d; color: white; border: none; padding: 6px 12px; border-radius: 4px; font-size: 12px; cursor: pointer;">
        Clear Filters
      </button>
    </div>
  </div>

  <!-- Filter Tabs -->
  <div style="background: white; border-radius: 12px 12px 0 0; box-shadow: 0 2px 8px rgba(0,0,0,0.1); margin-top: 16px;">
    <div style="display: flex; border-bottom: 1px solid #e9ecef; flex-wrap: wrap;">
      <button onclick="showTab('all')" id="tab-all" class="tab-button active" style="flex: 1; min-width: 120px; padding: 12px 8px; border: none; background: none; cursor: pointer; font-weight: 600; color: var(--primary-green); border-bottom: 3px solid var(--primary-green); font-size: 13px;">
        All (<span id="count-all">{{ $applications['all_good_moral']->count() }}</span>)
      </button>
      <button onclick="showTab('good_moral')" id="tab-good_moral" class="tab-button" style="flex: 1; min-width: 120px; padding: 12px 8px; border: none; background: none; cursor: pointer; font-weight: 600; color: #6c757d; border-bottom: 3px solid transparent; font-size: 13px;">
        Good Moral (<span id="count-good_moral">{{ $applications['good_moral']->count() }}</span>)
      </button>
      <button onclick="showTab('residency')" id="tab-residency" class="tab-button" style="flex: 1; min-width: 120px; padding: 12px 8px; border: none; background: none; cursor: pointer; font-weight: 600; color: #6c757d; border-bottom: 3px solid transparent; font-size: 13px;">
        Residency (<span id="count-residency">{{ $applications['residency']->count() }}</span>)
      </button>
      <button onclick="showTab('ready_for_print')" id="tab-ready_for_print" class="tab-button" style="flex: 1; min-width: 120px; padding: 12px 8px; border: none; background: none; cursor: pointer; font-weight: 600; color: #6c757d; border-bottom: 3px solid transparent; font-size: 13px;">
        Ready to Print (<span id="count-ready_for_print">{{ $applications['by_status']['ready_for_print']->count() }}</span>)
      </button>
      <button onclick="showTab('printed')" id="tab-printed" class="tab-button" style="flex: 1; min-width: 120px; padding: 12px 8px; border: none; background: none; cursor: pointer; font-weight: 600; color: #6c757d; border-bottom: 3px solid transparent; font-size: 13px;">
        Printed (<span id="count-printed">{{ $applications['by_status']['ready_for_pickup']->count() }}</span>)
      </button>
    </div>
  </div>

  <!-- All Applications Tab -->
  <div id="content-all" class="tab-content" style="background: white; border-radius: 0 0 12px 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); overflow: hidden;">
    <div style="padding: 24px; border-bottom: 1px solid #e9ecef;">
      <h2 style="margin: 0; color: var(--primary-green); font-size: 1.25rem; font-weight: 600;">All Certificate Applications</h2>
    </div>

    @if($applications['all_good_moral']->isEmpty())
    <div style="padding: 48px; text-align: center; color: #6c757d;">
      <svg style="width: 64px; height: 64px; margin: 0 auto 16px; color: #dee2e6;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
      </svg>
      <p style="margin: 0; font-size: 1.1rem; font-weight: 500;">No applications available</p>
    </div>
    @else
    <div style="overflow-x: auto;">
      <table style="width: 100%; border-collapse: collapse;">
        <thead>
          <tr style="background: #f8f9fa; border-bottom: 2px solid #e9ecef;">
            <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057; font-size: 14px;">Student ID</th>
            <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057; font-size: 14px;">Full Name</th>
            <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057; font-size: 14px;">Department</th>
            <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057; font-size: 14px;">Certificate Type</th>
            <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057; font-size: 14px;">Status</th>
            <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057; font-size: 14px;">Actions</th>
          </tr>
        </thead>
        <tbody>
          @foreach($applications['all_good_moral'] as $application)
          @php
            $receipt = \App\Models\Receipt::where('reference_num', $application->reference_number)->first();
          @endphp
          <tr style="border-bottom: 1px solid #e9ecef; transition: background-color 0.2s ease;"
              onmouseover="this.style.backgroundColor='#f8f9fa'"
              onmouseout="this.style.backgroundColor='transparent'">
            <td style="padding: 16px; color: #495057; font-size: 14px; font-weight: 500;">{{ $application->student_id }}</td>
            <td style="padding: 16px; color: #495057; font-size: 14px;">{{ $application->fullname }}</td>
            <td style="padding: 16px; color: #495057; font-size: 14px;">
              <span style="display: inline-block; padding: 4px 8px; background: var(--light-green); color: var(--primary-green); border-radius: 4px; font-size: 12px; font-weight: 500;">
                {{ $application->department }}
              </span>
            </td>
            <td style="padding: 16px; color: #495057; font-size: 14px;">
              <span style="display: inline-block; padding: 4px 8px;
                    background: {{ $application->certificate_type === 'good_moral' ? '#e8f5e8' : '#fff3cd' }};
                    color: {{ $application->certificate_type === 'good_moral' ? 'var(--primary-green)' : '#856404' }};
                    border-radius: 4px; font-size: 12px; font-weight: 500;">
                {{ $application->certificate_type === 'good_moral' ? 'Good Moral' : 'Residency' }}
              </span>
            </td>
            <td style="padding: 16px; color: #495057; font-size: 14px;">
              @if($application->application_status === 'Ready for Moderator Print')
                <span style="display: inline-block; padding: 6px 12px; background: #ffc10720; color: #ffc107; border-radius: 20px; font-size: 12px; font-weight: 500;">
                  Ready for Print
                </span>
              @elseif($application->application_status === 'Ready for Pickup')
                <span style="display: inline-block; padding: 6px 12px; background: #28a74520; color: #28a745; border-radius: 20px; font-size: 12px; font-weight: 500;">
                  Printed
                </span>
              @elseif($application->application_status === 'Approved by Administrator')
                <span style="display: inline-block; padding: 6px 12px; background: #17a2b820; color: #17a2b8; border-radius: 20px; font-size: 12px; font-weight: 500;">
                  Payment Required
                </span>
              @elseif($application->application_status === 'Approved by Dean')
                <span style="display: inline-block; padding: 6px 12px; background: #20c99720; color: #20c997; border-radius: 20px; font-size: 12px; font-weight: 500;">
                  With Administrator
                </span>
              @elseif($application->application_status === 'Approved by Registrar')
                <span style="display: inline-block; padding: 6px 12px; background: #007bff20; color: #007bff; border-radius: 20px; font-size: 12px; font-weight: 500;">
                  With Dean
                </span>
              @elseif($application->status === 'rejected')
                <span style="display: inline-block; padding: 6px 12px; background: #dc354520; color: #dc3545; border-radius: 20px; font-size: 12px; font-weight: 500;">
                  Rejected
                </span>
              @elseif($application->application_status === null)
                <span style="display: inline-block; padding: 6px 12px; background: #fd7e1420; color: #fd7e14; border-radius: 20px; font-size: 12px; font-weight: 500;">
                  With Registrar
                </span>
              @else
                <span style="display: inline-block; padding: 6px 12px; background: #6c757d20; color: #6c757d; border-radius: 20px; font-size: 12px; font-weight: 500;">
                  {{ $application->application_status ?? 'Pending' }}
                </span>
              @endif
            </td>
            <td style="padding: 16px; color: #495057; font-size: 14px;">
              <div style="display: flex; gap: 8px; align-items: center; flex-wrap: wrap;">
                <button data-application='@json($application)' onclick="openGoodMoralModal(this)"
                        style="background: #007bff; color: white; border: none; padding: 8px 12px; border-radius: 6px; font-size: 12px; font-weight: 500; cursor: pointer;">
                  View Details
                </button>

                {{-- Actions based on application status --}}
                @if($application->application_status === 'Ready for Moderator Print' && $receipt && $receipt->document_path)
                  <form action="{{ route('moderator.printCertificate', $application->id) }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit"
                            onclick="return confirm('Are you sure you want to print this certificate? This will mark it as ready for pickup.')"
                            style="background: #28a745; color: white; border: none; padding: 8px 12px; border-radius: 6px; font-size: 12px; font-weight: 500; cursor: pointer;">
                      Print Certificate
                    </button>
                  </form>
                @elseif($application->application_status === 'Ready for Pickup' && $receipt && $receipt->document_path)
                  <a href="{{ route('moderator.downloadCertificate', $application->id) }}"
                     style="background: #17a2b8; color: white; border: none; padding: 8px 12px; border-radius: 6px; font-size: 12px; font-weight: 500; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; margin-right: 8px;">
                    <svg style="width: 12px; height: 12px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Download
                  </a>
                  <form action="{{ route('moderator.printCertificate', $application->id) }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit"
                            onclick="return confirm('Are you sure you want to reprint this certificate?')"
                            style="background: #ffc107; color: #212529; border: none; padding: 8px 12px; border-radius: 6px; font-size: 12px; font-weight: 500; cursor: pointer;">
                      Reprint
                    </button>
                  </form>
                @else
                  {{-- For other statuses, show view-only status --}}
                  <span style="color: #6c757d; font-size: 12px; font-style: italic; padding: 8px 12px; background: #f8f9fa; border-radius: 6px;">
                    @if($application->application_status === 'Approved by Administrator')
                      Awaiting payment receipt
                    @elseif($application->status === 'rejected')
                      Application rejected
                    @else
                      In progress
                    @endif
                  </span>
                @endif

                {{-- Receipt viewing (available for all applications that have receipts) --}}
                @if($receipt && $receipt->document_path)
                <a href="{{ route('files.serve', $receipt->document_path) }}" target="_blank"
                   style="background: #6f42c1; color: white; border: none; padding: 8px 12px; border-radius: 6px; font-size: 12px; font-weight: 500; text-decoration: none; display: inline-block;">
                  View Receipt
                </a>
                @endif
              </div>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    @endif
  </div>

  <!-- Good Moral Applications Tab -->
  <div id="content-good_moral" class="tab-content" style="background: white; border-radius: 0 0 12px 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); overflow: hidden; display: none;">
    <div style="padding: 24px; border-bottom: 1px solid #e9ecef;">
      <h2 style="margin: 0; color: var(--primary-green); font-size: 1.25rem; font-weight: 600;">Good Moral Certificate Applications</h2>
    </div>

    @if($applications['good_moral']->isEmpty())
    <div style="padding: 48px; text-align: center; color: #6c757d;">
      <svg style="width: 64px; height: 64px; margin: 0 auto 16px; color: #dee2e6;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
      </svg>
      <p style="margin: 0; font-size: 1.1rem; font-weight: 500;">No Good Moral applications available</p>
    </div>
    @else
    <div style="overflow-x: auto;">
      <table style="width: 100%; border-collapse: collapse;">
        <thead>
          <tr style="background: #f8f9fa; border-bottom: 2px solid #e9ecef;">
            <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057; font-size: 14px;">Student ID</th>
            <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057; font-size: 14px;">Full Name</th>
            <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057; font-size: 14px;">Department</th>
            <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057; font-size: 14px;">Status</th>
            <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057; font-size: 14px;">Actions</th>
          </tr>
        </thead>
        <tbody>
          @foreach($applications['good_moral'] as $application)
          @php
            $receipt = \App\Models\Receipt::where('reference_num', $application->reference_number)->first();
          @endphp
          <tr style="border-bottom: 1px solid #e9ecef; transition: background-color 0.2s ease;"
              onmouseover="this.style.backgroundColor='#f8f9fa'"
              onmouseout="this.style.backgroundColor='transparent'">
            <td style="padding: 16px; color: #495057; font-size: 14px; font-weight: 500;">{{ $application->student_id }}</td>
            <td style="padding: 16px; color: #495057; font-size: 14px;">{{ $application->fullname }}</td>
            <td style="padding: 16px; color: #495057; font-size: 14px;">
              <span style="display: inline-block; padding: 4px 8px; background: var(--light-green); color: var(--primary-green); border-radius: 4px; font-size: 12px; font-weight: 500;">
                {{ $application->department }}
              </span>
            </td>
            <td style="padding: 16px; color: #495057; font-size: 14px;">
              @if($application->application_status === 'Ready for Moderator Print')
                <span style="display: inline-block; padding: 6px 12px; background: #ffc10720; color: #ffc107; border-radius: 20px; font-size: 12px; font-weight: 500;">
                  Ready for Print
                </span>
              @elseif($application->application_status === 'Ready for Pickup')
                <span style="display: inline-block; padding: 6px 12px; background: #28a74520; color: #28a745; border-radius: 20px; font-size: 12px; font-weight: 500;">
                  Printed
                </span>
              @else
                <span style="display: inline-block; padding: 6px 12px; background: #6c757d20; color: #6c757d; border-radius: 20px; font-size: 12px; font-weight: 500;">
                  {{ $application->application_status }}
                </span>
              @endif
            </td>
            <td style="padding: 16px; color: #495057; font-size: 14px;">
              <div style="display: flex; gap: 8px; align-items: center; flex-wrap: wrap;">
                <button data-application='@json($application)' onclick="openGoodMoralModal(this)"
                        style="background: #007bff; color: white; border: none; padding: 8px 12px; border-radius: 6px; font-size: 12px; font-weight: 500; cursor: pointer;">
                  View Details
                </button>

                @if($application->application_status === 'Ready for Moderator Print' && $receipt && $receipt->document_path)
                  <form action="{{ route('moderator.printCertificate', $application->id) }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit"
                            onclick="return confirm('Are you sure you want to print this certificate? This will mark it as ready for pickup.')"
                            style="background: #28a745; color: white; border: none; padding: 8px 12px; border-radius: 6px; font-size: 12px; font-weight: 500; cursor: pointer;">
                      Print Certificate
                    </button>
                  </form>
                @elseif($application->application_status === 'Ready for Pickup' && $receipt && $receipt->document_path)
                  <a href="{{ route('moderator.downloadCertificate', $application->id) }}"
                     style="background: #17a2b8; color: white; border: none; padding: 8px 12px; border-radius: 6px; font-size: 12px; font-weight: 500; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; margin-right: 8px;">
                    <svg style="width: 12px; height: 12px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Download
                  </a>
                  <form action="{{ route('moderator.printCertificate', $application->id) }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit"
                            onclick="return confirm('Are you sure you want to reprint this certificate?')"
                            style="background: #ffc107; color: #212529; border: none; padding: 8px 12px; border-radius: 6px; font-size: 12px; font-weight: 500; cursor: pointer;">
                      Reprint
                    </button>
                  </form>
                @endif

                @if($receipt && $receipt->document_path)
                <a href="{{ route('files.serve', $receipt->document_path) }}" target="_blank"
                   style="background: #6f42c1; color: white; border: none; padding: 8px 12px; border-radius: 6px; font-size: 12px; font-weight: 500; text-decoration: none; display: inline-block;">
                  View Receipt
                </a>
                @else
                <span style="color: #6c757d; font-size: 12px; font-style: italic; padding: 8px 12px; background: #f8f9fa; border-radius: 6px;">
                  No receipt uploaded
                </span>
                @endif
              </div>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    @endif
  </div>

  <!-- Residency Applications Tab -->
  <div id="content-residency" class="tab-content" style="background: white; border-radius: 0 0 12px 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); overflow: hidden; display: none;">
    <div style="padding: 24px; border-bottom: 1px solid #e9ecef;">
      <h2 style="margin: 0; color: #856404; font-size: 1.25rem; font-weight: 600;">Certificate of Residency Applications</h2>
    </div>

    @if($applications['residency']->isEmpty())
    <div style="padding: 48px; text-align: center; color: #6c757d;">
      <svg style="width: 64px; height: 64px; margin: 0 auto 16px; color: #dee2e6;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
      </svg>
      <p style="margin: 0; font-size: 1.1rem; font-weight: 500;">No Residency applications available</p>
    </div>
    @else
    <div style="overflow-x: auto;">
      <table style="width: 100%; border-collapse: collapse;">
        <thead>
          <tr style="background: #f8f9fa; border-bottom: 2px solid #e9ecef;">
            <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057; font-size: 14px;">Student ID</th>
            <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057; font-size: 14px;">Full Name</th>
            <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057; font-size: 14px;">Department</th>
            <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057; font-size: 14px;">Status</th>
            <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057; font-size: 14px;">Actions</th>
          </tr>
        </thead>
        <tbody>
          @foreach($applications['residency'] as $application)
          @php
            $receipt = \App\Models\Receipt::where('reference_num', $application->reference_number)->first();
          @endphp
          <tr style="border-bottom: 1px solid #e9ecef; transition: background-color 0.2s ease;"
              onmouseover="this.style.backgroundColor='#f8f9fa'"
              onmouseout="this.style.backgroundColor='transparent'">
            <td style="padding: 16px; color: #495057; font-size: 14px; font-weight: 500;">{{ $application->student_id }}</td>
            <td style="padding: 16px; color: #495057; font-size: 14px;">{{ $application->fullname }}</td>
            <td style="padding: 16px; color: #495057; font-size: 14px;">
              <span style="display: inline-block; padding: 4px 8px; background: #fff3cd; color: #856404; border-radius: 4px; font-size: 12px; font-weight: 500;">
                {{ $application->department }}
              </span>
            </td>
            <td style="padding: 16px; color: #495057; font-size: 14px;">
              @if($application->application_status === 'Ready for Moderator Print')
                <span style="display: inline-block; padding: 6px 12px; background: #ffc10720; color: #ffc107; border-radius: 20px; font-size: 12px; font-weight: 500;">
                  Ready for Print
                </span>
              @elseif($application->application_status === 'Ready for Pickup')
                <span style="display: inline-block; padding: 6px 12px; background: #28a74520; color: #28a745; border-radius: 20px; font-size: 12px; font-weight: 500;">
                  Printed
                </span>
              @else
                <span style="display: inline-block; padding: 6px 12px; background: #6c757d20; color: #6c757d; border-radius: 20px; font-size: 12px; font-weight: 500;">
                  {{ $application->application_status }}
                </span>
              @endif
            </td>
            <td style="padding: 16px; color: #495057; font-size: 14px;">
              <div style="display: flex; gap: 8px; align-items: center; flex-wrap: wrap;">
                <button data-application='@json($application)' onclick="openGoodMoralModal(this)"
                        style="background: #007bff; color: white; border: none; padding: 8px 12px; border-radius: 6px; font-size: 12px; font-weight: 500; cursor: pointer;">
                  View Details
                </button>

                @if($application->application_status === 'Ready for Moderator Print' && $receipt && $receipt->document_path)
                  <form action="{{ route('moderator.printCertificate', $application->id) }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit"
                            onclick="return confirm('Are you sure you want to print this certificate? This will mark it as ready for pickup.')"
                            style="background: #28a745; color: white; border: none; padding: 8px 12px; border-radius: 6px; font-size: 12px; font-weight: 500; cursor: pointer;">
                      Print Certificate
                    </button>
                  </form>
                @elseif($application->application_status === 'Ready for Pickup' && $receipt && $receipt->document_path)
                  <a href="{{ route('moderator.downloadCertificate', $application->id) }}"
                     style="background: #17a2b8; color: white; border: none; padding: 8px 12px; border-radius: 6px; font-size: 12px; font-weight: 500; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; margin-right: 8px;">
                    <svg style="width: 12px; height: 12px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Download
                  </a>
                  <form action="{{ route('moderator.printCertificate', $application->id) }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit"
                            onclick="return confirm('Are you sure you want to reprint this certificate?')"
                            style="background: #ffc107; color: #212529; border: none; padding: 8px 12px; border-radius: 6px; font-size: 12px; font-weight: 500; cursor: pointer;">
                      Reprint
                    </button>
                  </form>
                @endif

                @if($receipt && $receipt->document_path)
                <a href="{{ route('files.serve', $receipt->document_path) }}" target="_blank"
                   style="background: #6f42c1; color: white; border: none; padding: 8px 12px; border-radius: 6px; font-size: 12px; font-weight: 500; text-decoration: none; display: inline-block;">
                  View Receipt
                </a>
                @else
                <span style="color: #6c757d; font-size: 12px; font-style: italic; padding: 8px 12px; background: #f8f9fa; border-radius: 6px;">
                  No receipt uploaded
                </span>
                @endif
              </div>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    @endif
  </div>

  <!-- Ready to Print Applications Tab -->
  <div id="content-ready_for_print" class="tab-content" style="background: white; border-radius: 0 0 12px 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); overflow: hidden; display: none;">
    <div style="padding: 24px; border-bottom: 1px solid #e9ecef;">
      <h2 style="margin: 0; color: #ffc107; font-size: 1.25rem; font-weight: 600;">Applications Ready for Printing</h2>
    </div>

    @if($applications['by_status']['ready_for_print']->isEmpty())
    <div style="padding: 48px; text-align: center; color: #6c757d;">
      <svg style="width: 64px; height: 64px; margin: 0 auto 16px; color: #dee2e6;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H9.5a2 2 0 01-2-2V5a2 2 0 012-2H14"></path>
      </svg>
      <p style="margin: 0; font-size: 1.1rem; font-weight: 500;">No applications ready for printing</p>
    </div>
    @else
    <div style="overflow-x: auto;">
      <table style="width: 100%; border-collapse: collapse;">
        <thead>
          <tr style="background: #f8f9fa; border-bottom: 2px solid #e9ecef;">
            <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057; font-size: 14px;">Student ID</th>
            <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057; font-size: 14px;">Full Name</th>
            <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057; font-size: 14px;">Department</th>
            <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057; font-size: 14px;">Certificate Type</th>
            <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057; font-size: 14px;">Actions</th>
          </tr>
        </thead>
        <tbody>
          @foreach($applications['by_status']['ready_for_print'] as $application)
          @php
            $receipt = \App\Models\Receipt::where('reference_num', $application->reference_number)->first();
          @endphp
          <tr style="border-bottom: 1px solid #e9ecef; transition: background-color 0.2s ease;"
              onmouseover="this.style.backgroundColor='#f8f9fa'"
              onmouseout="this.style.backgroundColor='transparent'">
            <td style="padding: 16px; color: #495057; font-size: 14px; font-weight: 500;">{{ $application->student_id }}</td>
            <td style="padding: 16px; color: #495057; font-size: 14px;">{{ $application->fullname }}</td>
            <td style="padding: 16px; color: #495057; font-size: 14px;">
              <span style="display: inline-block; padding: 4px 8px; background: var(--light-green); color: var(--primary-green); border-radius: 4px; font-size: 12px; font-weight: 500;">
                {{ $application->department }}
              </span>
            </td>
            <td style="padding: 16px; color: #495057; font-size: 14px;">
              <span style="display: inline-block; padding: 4px 8px;
                    background: {{ $application->certificate_type === 'good_moral' ? '#e8f5e8' : '#fff3cd' }};
                    color: {{ $application->certificate_type === 'good_moral' ? 'var(--primary-green)' : '#856404' }};
                    border-radius: 4px; font-size: 12px; font-weight: 500;">
                {{ $application->certificate_type === 'good_moral' ? 'Good Moral' : 'Residency' }}
              </span>
            </td>
            <td style="padding: 16px; color: #495057; font-size: 14px;">
              <div style="display: flex; gap: 8px; align-items: center; flex-wrap: wrap;">
                <button data-application='@json($application)' onclick="openGoodMoralModal(this)"
                        style="background: #007bff; color: white; border: none; padding: 8px 12px; border-radius: 6px; font-size: 12px; font-weight: 500; cursor: pointer;">
                  View Details
                </button>

                @if($receipt && $receipt->document_path)
                  <form action="{{ route('moderator.printCertificate', $application->id) }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit"
                            onclick="return confirm('Are you sure you want to print this certificate? This will mark it as ready for pickup.')"
                            style="background: #28a745; color: white; border: none; padding: 8px 12px; border-radius: 6px; font-size: 12px; font-weight: 500; cursor: pointer;">
                      Print Certificate
                    </button>
                  </form>

                  <a href="{{ route('files.serve', $receipt->document_path) }}" target="_blank"
                     style="background: #6f42c1; color: white; border: none; padding: 8px 12px; border-radius: 6px; font-size: 12px; font-weight: 500; text-decoration: none; display: inline-block;">
                    View Receipt
                  </a>
                @else
                  <span style="color: #dc3545; font-size: 12px; font-style: italic; padding: 8px 12px; background: #f8d7da; border-radius: 6px;">
                    No receipt found
                  </span>
                @endif
              </div>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    @endif
  </div>

  <!-- Printed Applications Tab -->
  <div id="content-printed" class="tab-content" style="background: white; border-radius: 0 0 12px 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); overflow: hidden; display: none;">
    <div style="padding: 24px; border-bottom: 1px solid #e9ecef;">
      <h2 style="margin: 0; color: #28a745; font-size: 1.25rem; font-weight: 600;">Printed Certificates</h2>
    </div>

    @if($applications['by_status']['ready_for_pickup']->isEmpty())
    <div style="padding: 48px; text-align: center; color: #6c757d;">
      <svg style="width: 64px; height: 64px; margin: 0 auto 16px; color: #dee2e6;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
      </svg>
      <p style="margin: 0; font-size: 1.1rem; font-weight: 500;">No printed certificates</p>
    </div>
    @else
    <div style="overflow-x: auto;">
      <table style="width: 100%; border-collapse: collapse;">
        <thead>
          <tr style="background: #f8f9fa; border-bottom: 2px solid #e9ecef;">
            <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057; font-size: 14px;">Student ID</th>
            <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057; font-size: 14px;">Full Name</th>
            <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057; font-size: 14px;">Department</th>
            <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057; font-size: 14px;">Certificate Type</th>
            <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057; font-size: 14px;">Actions</th>
          </tr>
        </thead>
        <tbody>
          @foreach($applications['by_status']['ready_for_pickup'] as $application)
          @php
            $receipt = \App\Models\Receipt::where('reference_num', $application->reference_number)->first();
          @endphp
          <tr style="border-bottom: 1px solid #e9ecef; transition: background-color 0.2s ease;"
              onmouseover="this.style.backgroundColor='#f8f9fa'"
              onmouseout="this.style.backgroundColor='transparent'">
            <td style="padding: 16px; color: #495057; font-size: 14px; font-weight: 500;">{{ $application->student_id }}</td>
            <td style="padding: 16px; color: #495057; font-size: 14px;">{{ $application->fullname }}</td>
            <td style="padding: 16px; color: #495057; font-size: 14px;">
              <span style="display: inline-block; padding: 4px 8px; background: var(--light-green); color: var(--primary-green); border-radius: 4px; font-size: 12px; font-weight: 500;">
                {{ $application->department }}
              </span>
            </td>
            <td style="padding: 16px; color: #495057; font-size: 14px;">
              <span style="display: inline-block; padding: 4px 8px;
                    background: {{ $application->certificate_type === 'good_moral' ? '#e8f5e8' : '#fff3cd' }};
                    color: {{ $application->certificate_type === 'good_moral' ? 'var(--primary-green)' : '#856404' }};
                    border-radius: 4px; font-size: 12px; font-weight: 500;">
                {{ $application->certificate_type === 'good_moral' ? 'Good Moral' : 'Residency' }}
              </span>
            </td>
            <td style="padding: 16px; color: #495057; font-size: 14px;">
              <div style="display: flex; gap: 8px; align-items: center; flex-wrap: wrap;">
                <button data-application='@json($application)' onclick="openGoodMoralModal(this)"
                        style="background: #007bff; color: white; border: none; padding: 8px 12px; border-radius: 6px; font-size: 12px; font-weight: 500; cursor: pointer;">
                  View Details
                </button>

                @if($receipt && $receipt->document_path)
                  <a href="{{ route('moderator.downloadCertificate', $application->id) }}"
                     style="background: #17a2b8; color: white; border: none; padding: 8px 12px; border-radius: 6px; font-size: 12px; font-weight: 500; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; margin-right: 8px;">
                    <svg style="width: 12px; height: 12px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Download
                  </a>

                  <form action="{{ route('moderator.printCertificate', $application->id) }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit"
                            onclick="return confirm('Are you sure you want to reprint this certificate?')"
                            style="background: #ffc107; color: #212529; border: none; padding: 8px 12px; border-radius: 6px; font-size: 12px; font-weight: 500; cursor: pointer;">
                      Reprint
                    </button>
                  </form>

                  <a href="{{ route('files.serve', $receipt->document_path) }}" target="_blank"
                     style="background: #6f42c1; color: white; border: none; padding: 8px 12px; border-radius: 6px; font-size: 12px; font-weight: 500; text-decoration: none; display: inline-block;">
                    View Receipt
                  </a>
                @endif
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
  <div id="modal" style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center; display: none;">
    <div style="background: white; padding: 24px; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); width: 100%; max-width: 500px; margin: 20px;">
      <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h3 style="margin: 0; color: var(--primary-green); font-size: 1.25rem; font-weight: 600;">Application Details</h3>
        <button onclick="closeModal()" style="background: none; border: none; font-size: 24px; cursor: pointer; color: #6c757d;">&times;</button>
      </div>

      <div style="display: grid; gap: 12px;">
        <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 8px; padding: 12px; background: #f8f9fa; border-radius: 6px;">
          <strong>Full Name:</strong>
          <span id="modalFullName"></span>
        </div>
        <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 8px; padding: 12px; background: #f8f9fa; border-radius: 6px;">
          <strong>Reference Number:</strong>
          <span id="modalrefnum"></span>
        </div>
        <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 8px; padding: 12px; background: #f8f9fa; border-radius: 6px;">
          <strong>Number of Copies:</strong>
          <span id="modalnumcop"></span>
        </div>
        <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 8px; padding: 12px; background: #f8f9fa; border-radius: 6px;">
          <strong>Certificate Type:</strong>
          <span id="modalCertificateType"></span>
        </div>
        <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 8px; padding: 12px; background: #f8f9fa; border-radius: 6px;">
          <strong>Status:</strong>
          <span id="modalStatus"></span>
        </div>
        <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 8px; padding: 12px; background: #f8f9fa; border-radius: 6px;">
          <strong>Reason:</strong>
          <span id="modalReason"></span>
        </div>
        <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 8px; padding: 12px; background: #f8f9fa; border-radius: 6px;">
          <strong>Course Completed:</strong>
          <span id="modalCourseCompleted"></span>
        </div>
        <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 8px; padding: 12px; background: #f8f9fa; border-radius: 6px;">
          <strong>Graduation Date:</strong>
          <span id="modalGraduationDate"></span>
        </div>
        <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 8px; padding: 12px; background: #f8f9fa; border-radius: 6px;">
          <strong>Undergraduate:</strong>
          <span id="modalUndergraduate"></span>
        </div>
        <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 8px; padding: 12px; background: #f8f9fa; border-radius: 6px;">
          <strong>Last Course Year Level:</strong>
          <span id="modalLastCourseYearLevel"></span>
        </div>
        <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 8px; padding: 12px; background: #f8f9fa; border-radius: 6px;">
          <strong>Last Semester SY:</strong>
          <span id="modalLastSemesterSY"></span>
        </div>
      </div>

      <div style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 24px;">
        <button onclick="closeModal()" style="background: #6c757d; color: white; border: none; padding: 10px 20px; border-radius: 6px; cursor: pointer;">
          Close
        </button>
      </div>
    </div>
  </div>

  <!-- Modal Script -->
  <script>
    // Store original data for filtering
    let originalApplications = {
      all: @json($applications['all_good_moral']),
      good_moral: @json($applications['good_moral']),
      residency: @json($applications['residency']),
      ready_for_print: @json($applications['by_status']['ready_for_print']),
      ready_for_pickup: @json($applications['by_status']['ready_for_pickup'])
    };

    // Tab functionality
    function showTab(tabName) {
      // Hide all tab contents
      const contents = document.querySelectorAll('.tab-content');
      contents.forEach(content => content.style.display = 'none');

      // Remove active class from all tabs
      const tabs = document.querySelectorAll('.tab-button');
      tabs.forEach(tab => {
        tab.style.color = '#6c757d';
        tab.style.borderBottomColor = 'transparent';
      });

      // Show selected tab content
      document.getElementById('content-' + tabName).style.display = 'block';

      // Add active class to selected tab
      const activeTab = document.getElementById('tab-' + tabName);
      activeTab.style.color = 'var(--primary-green)';
      activeTab.style.borderBottomColor = 'var(--primary-green)';
      
      // Apply current filters to the newly shown tab
      filterApplications();
    }

    // Filter applications based on search and filter criteria
    function filterApplications() {
      const searchTerm = document.getElementById('searchInput').value.toLowerCase();
      const departmentFilter = document.getElementById('departmentFilter').value;
      const statusFilter = document.getElementById('statusFilter').value;
      const dateFilter = document.getElementById('dateFilter').value;
      
      // Get currently active tab
      const activeTab = document.querySelector('.tab-button[style*="var(--primary-green)"]');
      const activeTabName = activeTab ? activeTab.id.replace('tab-', '') : 'all';
      
      // Get table based on active tab
      const tableSelector = `#content-${activeTabName} tbody`;
      const table = document.querySelector(tableSelector);
      
      if (!table) return;
      
      const rows = table.querySelectorAll('tr');
      let visibleCount = 0;
      
      rows.forEach(row => {
        const cells = row.querySelectorAll('td');
        if (cells.length === 0) return;
        
        const studentId = cells[0].textContent.toLowerCase();
        const fullName = cells[1].textContent.toLowerCase();  
        const department = cells[2].textContent.trim();
        const status = cells[activeTabName === 'all' ? 4 : 3].textContent.trim();
        
        // Apply filters
        let showRow = true;
        
        // Search filter
        if (searchTerm && !studentId.includes(searchTerm) && !fullName.includes(searchTerm)) {
          showRow = false;
        }
        
        // Department filter
        if (departmentFilter && department !== departmentFilter) {
          showRow = false;
        }
        
        // Status filter  
        if (statusFilter) {
          let statusMatch = false;
          if (statusFilter === 'With Registrar' && (status.includes('With Registrar') || status === 'Pending')) {
            statusMatch = true;
          } else if (statusFilter === 'Approved by Registrar' && status.includes('With Dean')) {
            statusMatch = true;
          } else if (statusFilter === 'Approved by Dean' && status.includes('With Administrator')) {
            statusMatch = true;
          } else if (statusFilter === 'Approved by Administrator' && status.includes('Payment Required')) {
            statusMatch = true;
          } else if (status.includes(statusFilter)) {
            statusMatch = true;
          }
          if (!statusMatch) {
            showRow = false;
          }
        }
        
        // Date filter (if needed, would require date data to be accessible)
        
        if (showRow) {
          row.style.display = '';
          visibleCount++;
        } else {
          row.style.display = 'none';
        }
      });
      
      // Update results count
      const resultsText = visibleCount === rows.length ? 
        `Showing all ${visibleCount} applications` : 
        `Showing ${visibleCount} of ${rows.length} applications`;
      document.getElementById('resultsCount').textContent = resultsText;
      
      // Update tab counts
      updateTabCounts();
    }
    
    // Update counts in tab buttons
    function updateTabCounts() {
      const tabs = ['all', 'good_moral', 'residency', 'ready_for_print', 'printed'];
      
      tabs.forEach(tabName => {
        const contentDiv = document.getElementById(`content-${tabName}`);
        if (contentDiv) {
          const visibleRows = contentDiv.querySelectorAll('tbody tr:not([style*="display: none"])');
          const countSpan = document.getElementById(`count-${tabName}`);
          if (countSpan) {
            countSpan.textContent = visibleRows.length;
          }
        }
      });
    }
    
    // Clear all filters
    function clearFilters() {
      document.getElementById('searchInput').value = '';
      document.getElementById('departmentFilter').value = '';
      document.getElementById('statusFilter').value = '';
      document.getElementById('dateFilter').value = '';
      filterApplications();
    }

    // Good Moral Application Modal
    function openGoodMoralModal(button) {
      const app = JSON.parse(button.getAttribute('data-application'));
      document.getElementById('modal').style.display = 'flex';
      document.getElementById('modalFullName').innerText = app.fullname ?? 'N/A';
      document.getElementById('modalrefnum').innerText = app.reference_number ?? 'N/A';
      document.getElementById('modalnumcop').innerText = app.number_of_copies ?? 'N/A';
      document.getElementById('modalCertificateType').innerText = app.certificate_type === 'good_moral' ? 'Good Moral Certificate' : 'Certificate of Residency';
      document.getElementById('modalStatus').innerText = app.application_status ?? 'N/A';
      document.getElementById('modalReason').innerText = app.reason ?? 'N/A';
      document.getElementById('modalCourseCompleted').innerText = app.course_completed ?? 'N/A';
      document.getElementById('modalGraduationDate').innerText = app.graduation_date ?? 'N/A';
      document.getElementById('modalUndergraduate').innerText = (app.is_undergraduate) ? 'Yes' : 'No';
      document.getElementById('modalLastCourseYearLevel').innerText = app.last_course_year_level ?? 'N/A';
      document.getElementById('modalLastSemesterSY').innerText = app.last_semester_sy ?? 'N/A';
    }

    // Legacy SecOSA Application Modal (for backward compatibility)
    function openModal(button) {
      const app = JSON.parse(button.getAttribute('data-application'));
      document.getElementById('modal').style.display = 'flex';
      document.getElementById('modalFullName').innerText = app.student?.fullname ?? 'N/A';
      document.getElementById('modalrefnum').innerText = app.reference_number ?? 'N/A';
      document.getElementById('modalnumcop').innerText = app.number_of_copies ?? 'N/A';
      document.getElementById('modalCertificateType').innerText = 'Legacy Application';
      document.getElementById('modalStatus').innerText = app.status ?? 'N/A';
      document.getElementById('modalReason').innerText = app.reason ?? 'N/A';
      document.getElementById('modalCourseCompleted').innerText = app.course_completed ?? 'N/A';
      document.getElementById('modalGraduationDate').innerText = app.graduation_date ?? 'N/A';
      document.getElementById('modalUndergraduate').innerText = (app.is_undergraduate) ? 'Yes' : 'No';
      document.getElementById('modalLastCourseYearLevel').innerText = app.last_course_year_level ?? 'N/A';
      document.getElementById('modalLastSemesterSY').innerText = app.last_semester_sy ?? 'N/A';
    }

    function closeModal() {
      document.getElementById('modal').style.display = 'none';
    }

    // Close modal when clicking outside
    document.getElementById('modal').addEventListener('click', function(e) {
      if (e.target === this) {
        closeModal();
      }
    });

    // Initialize first tab as active
    document.addEventListener('DOMContentLoaded', function() {
      showTab('all');
    });

    // Add keyboard navigation for tabs
    document.addEventListener('keydown', function(e) {
      if (e.key === 'ArrowLeft' || e.key === 'ArrowRight') {
        const tabs = ['all', 'good_moral', 'residency', 'ready_for_print', 'printed'];
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

</x-dashboard-layout>