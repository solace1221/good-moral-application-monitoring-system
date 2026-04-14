<x-dashboard-layout>
  <x-slot name="roleTitle">Admin</x-slot>

  <x-slot name="navigation">
    <x-admin-navigation />
  </x-slot>

  <style>
    .btn-outline-secondary:hover {
      color: #fff;
      background-color: #6c757d;
      border-color: #6c757d;
    }
  </style>

  <!-- Header Section -->
  <div class="header-section">
    <div>
      <h1 class="role-title">Certificate Applications</h1>
      <p class="welcome-text">Review and manage Good Moral and Residency certificate applications</p>
      <div class="accent-line"></div>
    </div>
  </div>
  @include('shared.alerts.flash')

  @if(session('pdf_url'))
  <script>
    window.addEventListener('load', function() {
      window.open("{{ session('pdf_url') }}", '_blank');
    });
  </script>
  @endif

  <!-- Filter Tabs -->
  <div style="background: white; border-radius: 12px 12px 0 0; box-shadow: 0 2px 8px rgba(0,0,0,0.1); margin-top: 24px;">
    <div style="display: flex; border-bottom: 1px solid #e9ecef;">
      <button onclick="showTab('all')" id="tab-all" class="tab-button active" style="flex: 1; padding: 16px; border: none; background: none; cursor: pointer; font-weight: 600; color: var(--primary-green); border-bottom: 3px solid var(--primary-green);">
        All Applications ({{ $applications['all_good_moral']->count() }})
      </button>
      <button onclick="showTab('good_moral')" id="tab-good_moral" class="tab-button" style="flex: 1; padding: 16px; border: none; background: none; cursor: pointer; font-weight: 600; color: #6c757d; border-bottom: 3px solid transparent;">
        Good Moral ({{ $applications['good_moral']->count() }})
      </button>
      <button onclick="showTab('residency')" id="tab-residency" class="tab-button" style="flex: 1; padding: 16px; border: none; background: none; cursor: pointer; font-weight: 600; color: #6c757d; border-bottom: 3px solid transparent;">
        Residency ({{ $applications['residency']->count() }})
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
            <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057; font-size: 14px;">Date Applied</th>
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
              <span style="color: #6c757d; font-size: 13px;">
                {{ $application->created_at->format('M j, Y') }}
              </span>
              <br>
              <span style="color: #9ca3af; font-size: 11px;">
                {{ $application->created_at->format('g:i A') }}
              </span>
            </td>
            <td style="padding: 16px; color: #495057; font-size: 14px;">
              @if($application->application_status === 'Approved by Administrator')
                <span style="display: inline-block; padding: 6px 12px; background: #ffc10720; color: #ffc107; border-radius: 20px; font-size: 12px; font-weight: 500;">
                  Ready for Print
                </span>
              @elseif($application->application_status === 'Ready for Pickup')
                <span style="display: inline-block; padding: 6px 12px; background: #28a74520; color: #28a745; border-radius: 20px; font-size: 12px; font-weight: 500;">
                  Printed
                </span>
              @elseif($application->application_status === 'Claimed')
                <span style="display: inline-block; padding: 6px 12px; background: #6f42c120; color: #6f42c1; border-radius: 20px; font-size: 12px; font-weight: 500;">
                  Claimed
                </span>
              @else
                <span style="display: inline-block; padding: 6px 12px; background: #6c757d20; color: #6c757d; border-radius: 20px; font-size: 12px; font-weight: 500;">
                  {{ $application->application_status }}
                </span>
              @endif
            </td>
            <td style="padding: 16px; color: #495057; font-size: 14px;">
              <div style="display: flex; gap: 8px; align-items: center; flex-wrap: wrap;">
                <button data-application='@json($application)' data-receipt='@json($receipt)'
                        @if($receipt && $receipt->document_path)
                        data-receipt-url="{{ route('files.serve', $receipt->document_path) }}"
                        @endif
                        onclick="openGoodMoralModal(this)"
                        style="background: transparent; color: #6c757d; border: 1.5px solid #6c757d; padding: 8px 12px; border-radius: 6px; font-size: 12px; font-weight: 500; cursor: pointer; white-space: nowrap; display: inline-flex; align-items: center; gap: 6px;"
                        onmouseover="this.style.background='#6c757d'; this.style.color='white'"
                        onmouseout="this.style.background='transparent'; this.style.color='#6c757d'">
                  <svg style="width: 12px; height: 12px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                  View
                </button>

                @if(in_array($application->application_status, ['Approved by Administrator', 'Ready for Moderator Print']) && $receipt && $receipt->document_path)
                  <form action="{{ route('admin.printCertificate', $application->id) }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit"
                            onclick="return confirm('Are you sure you want to print this certificate? This will mark it as ready for pickup at the Office of Student Affairs (OSA).'))"
                            style="background: #28a745; color: white; border: none; padding: 8px 12px; border-radius: 6px; font-size: 12px; font-weight: 500; cursor: pointer;">
                      Print Certificate
                    </button>
                  </form>
                @elseif($application->application_status === 'Ready for Pickup' && $receipt && $receipt->document_path)

                  <a href="{{ route('admin.downloadCertificate', $application->id) }}"

                     style="background: #17a2b8; color: white; border: none; padding: 8px 12px; border-radius: 6px; font-size: 12px; font-weight: 500; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">

                    <svg style="width: 12px; height: 12px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>

                    </svg>

                    Download

                  </a>

                  <form action="{{ route('admin.printCertificate', $application->id) }}" method="POST" style="display: inline;">

                    @csrf

                    <button type="submit"

                            onclick="return confirm('Are you sure you want to reprint this certificate?')"

                            style="background: #28a745; color: white; border: none; padding: 8px 12px; border-radius: 6px; font-size: 12px; font-weight: 500; cursor: pointer; display: inline-flex; align-items: center; gap: 6px;">

                      <svg style="width: 12px; height: 12px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                      Reprint

                    </button>

                  </form>

                  <form action="{{ route('admin.markAsClaimed', $application->id) }}" method="POST" style="display: inline;">

                    @csrf

                    <button type="submit"

                            onclick="return confirm('Mark this certificate as claimed by the student?')"

                            style="background: #495057; color: white; border: none; padding: 8px 12px; border-radius: 6px; font-size: 12px; font-weight: 500; cursor: pointer;">

                      Mark as Claimed

                    </button>

                  </form>

                @elseif($application->application_status === 'Claimed' && $receipt && $receipt->document_path)

                  <form action="{{ route('admin.printCertificate', $application->id) }}" method="POST" style="display: inline;">

                    @csrf

                    <button type="submit"

                            onclick="return confirm('Are you sure you want to reprint this certificate?')"

                            style="background: #28a745; color: white; border: none; padding: 8px 12px; border-radius: 6px; font-size: 12px; font-weight: 500; cursor: pointer; display: inline-flex; align-items: center; gap: 6px;">

                      <svg style="width: 12px; height: 12px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                      Reprint

                    </button>

                  </form>

                @endif
              </div>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    <!-- Pagination Links -->
    @if($applications['all_good_moral']->hasPages())
    <div style="margin-top: 24px; display: flex; justify-content: center;">
      {{ $applications['all_good_moral']->links() }}
    </div>
    @endif
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
            <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057; font-size: 14px;">Date Applied</th>
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
              <span style="color: #6c757d; font-size: 13px;">
                {{ $application->created_at->format('M j, Y') }}
              </span>
              <br>
              <span style="color: #9ca3af; font-size: 11px;">
                {{ $application->created_at->format('g:i A') }}
              </span>
            </td>
            <td style="padding: 16px; color: #495057; font-size: 14px;">
              @php
                $receipt = \App\Models\Receipt::where('reference_num', $application->reference_number)->first();
                $hasReceipt = $receipt && $receipt->document_path;
              @endphp

              @if($application->application_status === 'Approved by Administrator')
                @if($hasReceipt)
                  <span style="display: inline-block; padding: 6px 12px; background: #28a74520; color: #28a745; border-radius: 20px; font-size: 12px; font-weight: 500;">
                    Ready to Print
                  </span>
                @else
                  <span style="display: inline-block; padding: 6px 12px; background: #ffc10720; color: #ffc107; border-radius: 20px; font-size: 12px; font-weight: 500;">
                    Pending
                  </span>
                @endif
              @elseif($application->application_status === 'Ready for Pickup')
                <span style="display: inline-block; padding: 6px 12px; background: #28a74520; color: #28a745; border-radius: 20px; font-size: 12px; font-weight: 500;">
                  Printed
                </span>
              @elseif($application->application_status === 'Claimed')
                <span style="display: inline-block; padding: 6px 12px; background: #6f42c120; color: #6f42c1; border-radius: 20px; font-size: 12px; font-weight: 500;">
                  Claimed
                </span>
              @else
                <span style="display: inline-block; padding: 6px 12px; background: #6c757d20; color: #6c757d; border-radius: 20px; font-size: 12px; font-weight: 500;">
                  {{ $application->application_status }}
                </span>
              @endif
            </td>
            <td style="padding: 16px; color: #495057; font-size: 14px;">
              <div style="display: flex; gap: 8px; align-items: center; flex-wrap: wrap;">
                <button data-application='@json($application)' data-receipt='@json($receipt)'
                        @if($receipt && $receipt->document_path)
                        data-receipt-url="{{ route('files.serve', $receipt->document_path) }}"
                        @endif
                        onclick="openGoodMoralModal(this)"
                        style="background: transparent; color: #6c757d; border: 1.5px solid #6c757d; padding: 8px 12px; border-radius: 6px; font-size: 12px; font-weight: 500; cursor: pointer; white-space: nowrap; display: inline-flex; align-items: center; gap: 6px;"
                        onmouseover="this.style.background='#6c757d'; this.style.color='white'"
                        onmouseout="this.style.background='transparent'; this.style.color='#6c757d'">
                  <svg style="width: 12px; height: 12px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                  View
                </button>

                @if(in_array($application->application_status, ['Approved by Administrator', 'Ready for Moderator Print']) && $receipt && $receipt->document_path)
                  <form action="{{ route('admin.printCertificate', $application->id) }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit"
                            onclick="return confirm('Are you sure you want to print this certificate? This will mark it as ready for pickup at the Office of Student Affairs (OSA).'))"
                            style="background: #28a745; color: white; border: none; padding: 8px 12px; border-radius: 6px; font-size: 12px; font-weight: 500; cursor: pointer;">
                      Print Certificate
                    </button>
                  </form>
                @elseif($application->application_status === 'Ready for Pickup' && $receipt && $receipt->document_path)

                  <a href="{{ route('admin.downloadCertificate', $application->id) }}"

                     style="background: #17a2b8; color: white; border: none; padding: 8px 12px; border-radius: 6px; font-size: 12px; font-weight: 500; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">

                    <svg style="width: 12px; height: 12px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>

                    </svg>

                    Download

                  </a>

                  <form action="{{ route('admin.printCertificate', $application->id) }}" method="POST" style="display: inline;">

                    @csrf

                    <button type="submit"

                            onclick="return confirm('Are you sure you want to reprint this certificate?')"

                            style="background: #28a745; color: white; border: none; padding: 8px 12px; border-radius: 6px; font-size: 12px; font-weight: 500; cursor: pointer; display: inline-flex; align-items: center; gap: 6px;">

                      <svg style="width: 12px; height: 12px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                      Reprint

                    </button>

                  </form>

                  <form action="{{ route('admin.markAsClaimed', $application->id) }}" method="POST" style="display: inline;">

                    @csrf

                    <button type="submit"

                            onclick="return confirm('Mark this certificate as claimed by the student?')"

                            style="background: #495057; color: white; border: none; padding: 8px 12px; border-radius: 6px; font-size: 12px; font-weight: 500; cursor: pointer;">

                      Mark as Claimed

                    </button>

                  </form>

                @elseif($application->application_status === 'Claimed' && $receipt && $receipt->document_path)

                  <form action="{{ route('admin.printCertificate', $application->id) }}" method="POST" style="display: inline;">

                    @csrf

                    <button type="submit"

                            onclick="return confirm('Are you sure you want to reprint this certificate?')"

                            style="background: #28a745; color: white; border: none; padding: 8px 12px; border-radius: 6px; font-size: 12px; font-weight: 500; cursor: pointer; display: inline-flex; align-items: center; gap: 6px;">

                      <svg style="width: 12px; height: 12px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                      Reprint

                    </button>

                  </form>

                @endif
              </div>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    <!-- Pagination Links -->
    @if($applications['good_moral']->hasPages())
    <div style="margin-top: 24px; display: flex; justify-content: center;">
      {{ $applications['good_moral']->links() }}
    </div>
    @endif
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
            <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057; font-size: 14px;">Date Applied</th>
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
              <span style="color: #6c757d; font-size: 13px;">
                {{ $application->created_at->format('M j, Y') }}
              </span>
              <br>
              <span style="color: #9ca3af; font-size: 11px;">
                {{ $application->created_at->format('g:i A') }}
              </span>
            </td>
            <td style="padding: 16px; color: #495057; font-size: 14px;">
              @if($application->application_status === 'Approved by Administrator')
                @if($receipt && $receipt->document_path)
                  <span style="display: inline-block; padding: 6px 12px; background: #28a74520; color: #28a745; border-radius: 20px; font-size: 12px; font-weight: 500;">
                    Ready to Print
                  </span>
                @else
                  <span style="display: inline-block; padding: 6px 12px; background: #ffc10720; color: #ffc107; border-radius: 20px; font-size: 12px; font-weight: 500;">
                    Pending
                  </span>
                @endif
              @elseif($application->application_status === 'Ready for Pickup')
                <span style="display: inline-block; padding: 6px 12px; background: #28a74520; color: #28a745; border-radius: 20px; font-size: 12px; font-weight: 500;">
                  Printed
                </span>
              @elseif($application->application_status === 'Claimed')
                <span style="display: inline-block; padding: 6px 12px; background: #6f42c120; color: #6f42c1; border-radius: 20px; font-size: 12px; font-weight: 500;">
                  Claimed
                </span>
              @else
                <span style="display: inline-block; padding: 6px 12px; background: #6c757d20; color: #6c757d; border-radius: 20px; font-size: 12px; font-weight: 500;">
                  {{ $application->application_status }}
                </span>
              @endif
            </td>
            <td style="padding: 16px; color: #495057; font-size: 14px;">
              <div style="display: flex; gap: 8px; align-items: center; flex-wrap: wrap;">
                <button data-application='@json($application)' data-receipt='@json($receipt)'
                        @if($receipt && $receipt->document_path)
                        data-receipt-url="{{ route('files.serve', $receipt->document_path) }}"
                        @endif
                        onclick="openGoodMoralModal(this)"
                        style="background: transparent; color: #6c757d; border: 1.5px solid #6c757d; padding: 8px 12px; border-radius: 6px; font-size: 12px; font-weight: 500; cursor: pointer; white-space: nowrap; display: inline-flex; align-items: center; gap: 6px;"
                        onmouseover="this.style.background='#6c757d'; this.style.color='white'"
                        onmouseout="this.style.background='transparent'; this.style.color='#6c757d'">
                  <svg style="width: 12px; height: 12px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                  View
                </button>

                @if(in_array($application->application_status, ['Approved by Administrator', 'Ready for Moderator Print']) && $receipt && $receipt->document_path)
                  <form action="{{ route('admin.printCertificate', $application->id) }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit"
                            onclick="return confirm('Are you sure you want to print this certificate? This will mark it as ready for pickup at the Office of Student Affairs (OSA).'))"
                            style="background: #28a745; color: white; border: none; padding: 8px 12px; border-radius: 6px; font-size: 12px; font-weight: 500; cursor: pointer;">
                      Print Certificate
                    </button>
                  </form>
                @elseif($application->application_status === 'Ready for Pickup' && $receipt && $receipt->document_path)

                  <a href="{{ route('admin.downloadCertificate', $application->id) }}"

                     style="background: #17a2b8; color: white; border: none; padding: 8px 12px; border-radius: 6px; font-size: 12px; font-weight: 500; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">

                    <svg style="width: 12px; height: 12px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>

                    </svg>

                    Download

                  </a>

                  <form action="{{ route('admin.printCertificate', $application->id) }}" method="POST" style="display: inline;">

                    @csrf

                    <button type="submit"

                            onclick="return confirm('Are you sure you want to reprint this certificate?')"

                            style="background: #28a745; color: white; border: none; padding: 8px 12px; border-radius: 6px; font-size: 12px; font-weight: 500; cursor: pointer; display: inline-flex; align-items: center; gap: 6px;">

                      <svg style="width: 12px; height: 12px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                      Reprint

                    </button>

                  </form>

                  <form action="{{ route('admin.markAsClaimed', $application->id) }}" method="POST" style="display: inline;">

                    @csrf

                    <button type="submit"

                            onclick="return confirm('Mark this certificate as claimed by the student?')"

                            style="background: #495057; color: white; border: none; padding: 8px 12px; border-radius: 6px; font-size: 12px; font-weight: 500; cursor: pointer;">

                      Mark as Claimed

                    </button>

                  </form>

                @elseif($application->application_status === 'Claimed' && $receipt && $receipt->document_path)

                  <form action="{{ route('admin.printCertificate', $application->id) }}" method="POST" style="display: inline;">

                    @csrf

                    <button type="submit"

                            onclick="return confirm('Are you sure you want to reprint this certificate?')"

                            style="background: #28a745; color: white; border: none; padding: 8px 12px; border-radius: 6px; font-size: 12px; font-weight: 500; cursor: pointer; display: inline-flex; align-items: center; gap: 6px;">

                      <svg style="width: 12px; height: 12px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                      Reprint

                    </button>

                  </form>

                @endif
              </div>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    <!-- Pagination Links -->
    @if($applications['residency']->hasPages())
    <div style="margin-top: 24px; display: flex; justify-content: center;">
      {{ $applications['residency']->links() }}
    </div>
    @endif
    @endif
  </div>

  <!-- Modal -->
  <div id="modal" style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center; display: none; overflow-y: auto; padding: 20px;">
    <div style="background: white; padding: 32px; border-radius: 16px; box-shadow: 0 10px 40px rgba(0,0,0,0.15); width: 100%; max-width: 600px; margin: auto; max-height: 90vh; overflow-y: auto;">
      <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; padding-bottom: 16px; border-bottom: 2px solid #e9ecef;">
        <h3 style="margin: 0; color: var(--primary-green); font-size: 1.5rem; font-weight: 700;">Application Details</h3>
        <button onclick="closeModal()" style="background: none; border: none; font-size: 28px; cursor: pointer; color: #6c757d; line-height: 1; padding: 0; width: 32px; height: 32px; border-radius: 50%; transition: all 0.2s;" onmouseover="this.style.background='#f8f9fa'" onmouseout="this.style.background='none'">&times;</button>
      </div>

      <div style="display: grid; gap: 16px;">
        <div style="padding: 16px; background: linear-gradient(135deg, #e8f5e8 0%, #f8f9fa 100%); border-radius: 8px; border-left: 4px solid var(--primary-green);">
          <div style="font-size: 12px; color: #6c757d; font-weight: 600; text-transform: uppercase; margin-bottom: 4px;">Full Name</div>
          <div style="font-size: 16px; font-weight: 600; color: #333;" id="modalFullName"></div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
          <div style="padding: 14px; background: #f8f9fa; border-radius: 8px;">
            <div style="font-size: 11px; color: #6c757d; font-weight: 600; text-transform: uppercase; margin-bottom: 4px;">Reference No.</div>
            <div style="font-size: 14px; font-weight: 600; color: #495057;" id="modalrefnum"></div>
          </div>
          <div style="padding: 14px; background: #f8f9fa; border-radius: 8px;">
            <div style="font-size: 11px; color: #6c757d; font-weight: 600; text-transform: uppercase; margin-bottom: 4px;">Number of Copies</div>
            <div style="font-size: 14px; font-weight: 600; color: #495057;" id="modalnumcop"></div>
          </div>
        </div>

        <div style="padding: 16px; background: linear-gradient(135deg, #d4edda 0%, #e8f5e8 100%); border-radius: 8px; border: 2px solid var(--primary-green);">
          <div style="font-size: 12px; color: var(--primary-green); font-weight: 700; text-transform: uppercase; margin-bottom: 6px;">Payment Amount</div>
          <div style="font-size: 18px; font-weight: 700; color: var(--primary-green);" id="modalPaymentAmount"></div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
          <div style="padding: 14px; background: #f8f9fa; border-radius: 8px;">
            <div style="font-size: 11px; color: #6c757d; font-weight: 600; text-transform: uppercase; margin-bottom: 4px;">Certificate Type</div>
            <div style="font-size: 14px; font-weight: 600; color: #495057;" id="modalCertificateType"></div>
          </div>
          <div style="padding: 14px; background: #f8f9fa; border-radius: 8px;">
            <div style="font-size: 11px; color: #6c757d; font-weight: 600; text-transform: uppercase; margin-bottom: 4px;">Status</div>
            <div style="font-size: 14px; font-weight: 600; color: var(--primary-green);" id="modalStatus"></div>
          </div>
        </div>

        <div style="padding: 14px; background: #f8f9fa; border-radius: 8px;">
          <div style="font-size: 11px; color: #6c757d; font-weight: 600; text-transform: uppercase; margin-bottom: 4px;">Reason</div>
          <div style="font-size: 14px; color: #495057;" id="modalReason"></div>
        </div>

        <div style="padding: 14px; background: #f8f9fa; border-radius: 8px;">
          <div style="font-size: 11px; color: #6c757d; font-weight: 600; text-transform: uppercase; margin-bottom: 4px;">Course Completed</div>
          <div style="font-size: 14px; color: #495057;" id="modalCourseCompleted"></div>
        </div>

        <div style="padding: 14px; background: #f8f9fa; border-radius: 8px;">
          <div style="font-size: 11px; color: #6c757d; font-weight: 600; text-transform: uppercase; margin-bottom: 4px;">Graduation Date</div>
          <div style="font-size: 14px; color: #495057;" id="modalGraduationDate"></div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 12px;">
          <div style="padding: 12px; background: #f8f9fa; border-radius: 8px;">
            <div style="font-size: 10px; color: #6c757d; font-weight: 600; text-transform: uppercase; margin-bottom: 4px;">Undergraduate</div>
            <div style="font-size: 14px; font-weight: 600; color: #495057;" id="modalUndergraduate"></div>
          </div>
          <div style="padding: 12px; background: #f8f9fa; border-radius: 8px;">
            <div style="font-size: 10px; color: #6c757d; font-weight: 600; text-transform: uppercase; margin-bottom: 4px;">Last Course Year Level</div>
            <div style="font-size: 14px; font-weight: 600; color: #495057;" id="modalLastCourseYearLevel"></div>
          </div>
          <div style="padding: 12px; background: #f8f9fa; border-radius: 8px;">
            <div style="font-size: 10px; color: #6c757d; font-weight: 600; text-transform: uppercase; margin-bottom: 4px;">Last Semester SY</div>
            <div style="font-size: 14px; font-weight: 600; color: #495057;" id="modalLastSemesterSY"></div>
          </div>
        </div>
      </div>

      {{-- Claim info: only shown when status is Claimed --}}
      <div id="modalClaimSection" style="display: none; margin-top: 16px; padding: 16px; background: #f3e8ff; border-left: 4px solid #6f42c1; border-radius: 6px;">
        <div style="font-size: 12px; font-weight: 700; color: #6f42c1; text-transform: uppercase; margin-bottom: 8px;">Certificate Claimed</div>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px; font-size: 13px; color: #495057;">
          <div><strong>Released by:</strong> <span id="modalClaimedBy"></span></div>
          <div><strong>Released on:</strong> <span id="modalClaimedAt"></span></div>
        </div>
      </div>

      {{-- Payment Receipt section --}}
      <div style="margin-top: 16px;">
        <div style="font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; padding-bottom: 6px; border-bottom: 1px solid #dee2e6; margin-bottom: 12px; color: #6c757d;">Payment Receipt</div>
        <div id="modalReceiptContent"></div>
      </div>

      <div style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 28px; padding-top: 20px; border-top: 1px solid #e9ecef;">
        <button onclick="closeModal()" style="background: #6c757d; color: white; border: none; padding: 12px 28px; border-radius: 8px; cursor: pointer; font-weight: 600; font-size: 14px; transition: all 0.2s;" onmouseover="this.style.background='#5a6268'" onmouseout="this.style.background='#6c757d'">
          Close
        </button>
      </div>
    </div>
  </div>

  <!-- Modal Script -->
  <script>
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
    }

    // Good Moral Application Modal
    function openGoodMoralModal(button) {
      const app = JSON.parse(button.getAttribute('data-application'));
      document.getElementById('modal').style.display = 'flex';
      document.getElementById('modalFullName').innerText = app.fullname ?? 'N/A';
      document.getElementById('modalrefnum').innerText = app.reference_number ?? 'N/A';
      document.getElementById('modalnumcop').innerText = app.number_of_copies ?? 'N/A';

      // Calculate and display payment amount
      const copies = parseInt(app.number_of_copies) || 1;
      const reasonCount = Array.isArray(app.reason) ? app.reason.length : 1;
      const totalAmount = reasonCount * copies * 50;
      const reasonText = reasonCount === 1 ? 'reason' : 'reasons';
      const copyText = copies === 1 ? 'copy' : 'copies';
      document.getElementById('modalPaymentAmount').innerText = `₱${totalAmount.toFixed(2)} (${reasonCount} ${reasonText} × ${copies} ${copyText} × ₱50.00)`;
      document.getElementById('modalCertificateType').innerText = app.certificate_type === 'good_moral' ? 'Good Moral Certificate' : 'Certificate of Residency';
      document.getElementById('modalStatus').innerText = app.application_status ?? 'N/A';
      document.getElementById('modalReason').innerText = Array.isArray(app.reason) ? app.reason.join(', ') : (app.reason ?? 'N/A');
      document.getElementById('modalCourseCompleted').innerText = app.course_completed ?? 'N/A';
      document.getElementById('modalGraduationDate').innerText = app.graduation_date ?? 'N/A';
      document.getElementById('modalUndergraduate').innerText = (app.is_undergraduate) ? 'Yes' : 'No';
      document.getElementById('modalLastCourseYearLevel').innerText = app.last_course_year_level ?? 'N/A';
      document.getElementById('modalLastSemesterSY').innerText = app.last_semester_sy ?? 'N/A';

      // Claim info
      const claimSection = document.getElementById('modalClaimSection');
      if (app.application_status === 'Claimed') {
        claimSection.style.display = 'block';
        document.getElementById('modalClaimedBy').innerText = app.claimer_name ?? 'Unknown';
        const claimedAt = app.claimed_at ? new Date(app.claimed_at).toLocaleString() : 'N/A';
        document.getElementById('modalClaimedAt').innerText = claimedAt;
      } else {
        claimSection.style.display = 'none';
      }

      // Payment Receipt
      const receiptData = JSON.parse(button.getAttribute('data-receipt') || 'null');
      const receiptUrl = button.getAttribute('data-receipt-url');
      const receiptContent = document.getElementById('modalReceiptContent');
      if (receiptData && receiptData.official_receipt_no) {
        const datePaid = receiptData.date_paid
          ? new Date(receiptData.date_paid).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' })
          : 'N/A';
        receiptContent.innerHTML = `
          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: ${receiptUrl ? '12px' : '0'}">
            <div style="padding: 14px; background: linear-gradient(135deg, #e8f5e8 0%, #f8f9fa 100%); border-radius: 8px; border-left: 3px solid #28a745;">
              <div style="font-size: 11px; color: #6c757d; font-weight: 600; text-transform: uppercase; margin-bottom: 4px;">Official Receipt No.</div>
              <div style="font-family: monospace; font-weight: 600; font-size: 14px; color: #155724;">${receiptData.official_receipt_no}</div>
            </div>
            <div style="padding: 14px; background: #f8f9fa; border-radius: 8px;">
              <div style="font-size: 11px; color: #6c757d; font-weight: 600; text-transform: uppercase; margin-bottom: 4px;">Date Paid</div>
              <div style="font-size: 14px; font-weight: 600; color: #495057;">${datePaid}</div>
            </div>
          </div>
          ${receiptUrl ? `<a href="${receiptUrl}" target="_blank" style="display: inline-flex; align-items: center; gap: 6px; padding: 8px 14px; background: #28a745; border-radius: 6px; font-size: 13px; font-weight: 600; text-decoration: none; color: white;">
            <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
            </svg>
            View Receipt
          </a>` : ''}
        `;
      } else {
        receiptContent.innerHTML = '<div style="padding: 14px; background: #f8f9fa; border-radius: 8px; color: #6c757d; font-size: 13px; font-style: italic; font-weight: 500;">No receipt uploaded.</div>';
      }
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
  </script>

</x-dashboard-layout>


