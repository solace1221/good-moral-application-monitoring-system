<x-dashboard-layout>
  <x-slot name="roleTitle">PSG Officer</x-slot>

  <x-slot name="navigation">
    <x-psg-officer-navigation />
  </x-slot>

  <!-- Header Section -->
  <div class="header-section">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
      <div>
        <h1 class="role-title">My Good Moral Applications</h1>
        <p class="welcome-text">Track your Good Moral Certificate and Certificate of Residency applications</p>
        <div class="accent-line"></div>
      </div>
      <div style="display: flex; gap: 12px; align-items: center;">
        <div style="padding: 12px 16px; background: var(--light-green); border-radius: 8px; font-size: 14px; color: var(--primary-green); font-weight: 600;">
          {{ $applications->total() }} Total Application{{ $applications->total() !== 1 ? 's' : '' }}
        </div>
        <a href="{{ route('PsgOfficer.goodMoralForm') }}" class="btn-primary">
          <svg style="width: 16px; height: 16px; margin-right: 8px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
          </svg>
          New Application
        </a>
      </div>
    </div>
  </div>

  <!-- Applications Table -->
  <div style="background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); overflow: hidden; margin-bottom: 24px;">
    <div style="padding: 24px; border-bottom: 1px solid #e9ecef;">
      <h2 style="margin: 0; color: var(--primary-green); font-size: 1.25rem; font-weight: 600;">Application History</h2>
    </div>

    @if ($applications->isEmpty())
    <div style="padding: 48px; text-align: center; color: #6c757d;">
      <svg style="width: 64px; height: 64px; margin: 0 auto 16px; color: #dee2e6;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
      </svg>
      <p style="margin: 0; font-size: 1.1rem; font-weight: 500;">No applications found</p>
      <p style="margin: 8px 0 16px; font-size: 0.9rem;">You haven't submitted any Good Moral Certificate applications yet</p>
      <a href="{{ route('PsgOfficer.goodMoralForm') }}" class="btn-primary">
        <svg style="width: 16px; height: 16px; margin-right: 8px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
        </svg>
        Submit Your First Application
      </a>
    </div>
    @else
    <div style="overflow-x: auto;">
      <table style="width: 100%; border-collapse: collapse;">
        <thead>
          <tr style="background: #f8f9fa; border-bottom: 2px solid #e9ecef;">
            <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057; font-size: 14px;">Reference Number</th>
            <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057; font-size: 14px;">Certificate Type</th>
            <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057; font-size: 14px;">Purpose</th>
            <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057; font-size: 14px;">Copies</th>
            <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057; font-size: 14px;">Date Applied</th>
            <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057; font-size: 14px;">Status</th>
            <th style="padding: 16px; text-align: center; font-weight: 600; color: #495057; font-size: 14px;">Action</th>
          </tr>
        </thead>
        <tbody>
          @foreach($applications as $application)
          <tr style="border-bottom: 1px solid #e9ecef; transition: background-color 0.2s ease;" 
              onmouseover="this.style.backgroundColor='#f8f9fa'" 
              onmouseout="this.style.backgroundColor='transparent'">
            <td style="padding: 16px; color: #495057; font-size: 14px;">
              <div style="font-weight: 500; color: #333; font-family: monospace;">{{ $application->reference_number }}</div>
            </td>
            <td style="padding: 16px; color: #495057; font-size: 14px;">
              <span style="display: inline-block; padding: 6px 12px; 
                           background: {{ $application->certificate_type === 'good_moral' ? '#28a74520' : '#17a2b820' }}; 
                           color: {{ $application->certificate_type === 'good_moral' ? '#28a745' : '#17a2b8' }}; 
                           border-radius: 20px; font-size: 12px; font-weight: 500; text-transform: capitalize;">
                {{ str_replace('_', ' ', $application->certificate_type) }}
              </span>
            </td>
            <td style="padding: 16px; color: #495057; font-size: 14px;">
              <div style="max-width: 200px;">
                @if(is_array($application->reason))
                  {{ implode(', ', $application->reason) }}
                @else
                  {{ $application->reason }}
                @endif
              </div>
            </td>
            <td style="padding: 16px; color: #495057; font-size: 14px; text-align: center;">
              <span style="font-weight: 500; color: #333;">{{ $application->number_of_copies }}</span>
            </td>
            <td style="padding: 16px; color: #495057; font-size: 14px;">
              <div style="font-weight: 500; color: #333;">{{ $application->created_at->format('M d, Y') }}</div>
              <div style="font-size: 12px; color: #6c757d;">{{ $application->created_at->format('h:i A') }}</div>
            </td>
            <td style="padding: 16px; color: #495057; font-size: 14px;">
              @php
                $statusColor = '#6c757d';
                $statusText = 'Pending';
                $statusBg = '#6c757d20';
                $progressMsg = 'Your application is currently under review by the Registrar.';

                if ($application->application_status) {
                  if (str_contains($application->application_status, 'Approved by Administrator')) {
                    $statusColor = '#28a745';
                    $statusText = 'Ready for Print';
                    $statusBg = '#28a74520';
                    $progressMsg = 'Your payment receipt has been received and your application is now under Administrator review.';
                  } elseif (str_contains($application->application_status, 'Approved by Dean') || str_contains($application->application_status, 'Approved By Dean')) {
                    $statusColor = '#17a2b8';
                    $statusText = 'Dean Approved';
                    $statusBg = '#17a2b820';
                    $progressMsg = 'Your application has been approved by the Dean. Please upload your payment receipt.';
                  } elseif (str_contains($application->application_status, 'Approved by Registrar') || str_contains($application->application_status, 'Approved By Registrar')) {
                    $statusColor = '#007bff';
                    $statusText = 'Registrar Approved';
                    $statusBg = '#007bff20';
                    $progressMsg = 'Your application has been approved by the Registrar and is now waiting for Dean approval.';
                  } elseif (str_contains($application->application_status, 'Rejected')) {
                    $statusColor = '#dc3545';
                    $statusText = 'Rejected';
                    $statusBg = '#dc354520';
                    if (str_contains($application->application_status, 'Registrar')) {
                      $progressMsg = 'Your application was rejected by the Registrar.';
                    } elseif (str_contains($application->application_status, 'Dean')) {
                      $progressMsg = 'Your application was rejected by the Dean.';
                    } elseif (str_contains($application->application_status, 'Administrator')) {
                      $progressMsg = 'Your application was rejected by the Administrator.';
                    } else {
                      $progressMsg = 'Your application has been rejected.';
                    }
                  } elseif ($application->application_status === 'Claimed') {
                    $statusColor = '#6f42c1';
                    $statusText = 'Claimed';
                    $statusBg = '#6f42c120';
                    $progressMsg = 'Your certificate has been claimed. Thank you!';
                  } elseif (str_contains($application->application_status, 'Ready for Pickup') || str_contains($application->application_status, 'Ready for Moderator Print')) {
                    $statusColor = '#28a745';
                    $statusText = 'Ready for Pickup';
                    $statusBg = '#28a74520';
                    $progressMsg = 'Your certificate is ready for pickup at the Office of Student Affairs (OSA).';
                  } elseif (str_contains($application->application_status, 'Printed')) {
                    $statusColor = '#28a745';
                    $statusText = 'Printed';
                    $statusBg = '#28a74520';
                    $progressMsg = 'Your certificate has been printed and is ready for pickup at the Office of Student Affairs (OSA).';
                  } elseif (str_contains($application->application_status, 'Receipt Uploaded')) {
                    $progressMsg = 'Your payment receipt has been received and your application is now under Administrator review.';
                  } elseif (str_contains($application->application_status, 'Registrar')) {
                    $progressMsg = 'Your application is currently under review by the Registrar.';
                  }
                } else {
                  $statusColor = '#ffc107';
                  $statusText = 'Pending Review';
                  $statusBg = '#ffc10720';
                }

                $copies = (int) ($application->number_of_copies ?? 1);
                $rate   = 100;
                $total  = $copies * $rate;
              @endphp
              <span style="display: inline-block; padding: 6px 12px; background: {{ $statusBg }}; color: {{ $statusColor }}; border-radius: 20px; font-size: 12px; font-weight: 500;">
                {{ $statusText }}
              </span>
            </td>
            <td style="padding: 16px; text-align: center;">
              <button type="button"
                onclick="openAppModal(
                  '{{ $application->reference_number }}',
                  '{{ addslashes(str_replace('_', ' ', $application->certificate_type)) }}',
                  '{{ addslashes($application->application_status ?? '') }}',
                  '{{ $statusText }}',
                  '{{ $statusColor }}',
                  '{{ $statusBg }}',
                  '{{ addslashes($progressMsg) }}',
                  {{ $copies }},
                  {{ $total }},
                  '{{ addslashes($application->rejection_reason ?? '') }}',
                  '{{ addslashes($application->rejection_details ?? '') }}'
                )"
                style="display: inline-flex; align-items: center; gap: 6px; padding: 7px 14px; background: #f8f9fa; color: #495057; border: 1px solid #dee2e6; border-radius: 6px; font-size: 13px; font-weight: 500; cursor: pointer; transition: background 0.15s;"  
                onmouseover="this.style.background='#e9ecef'" onmouseout="this.style.background='#f8f9fa'">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width:15px;height:15px;">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.964-7.178Z" />
                  <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                </svg>
                View
              </button>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
    @if($applications->hasPages())
    <div style="padding: 20px; border-top: 1px solid #e9ecef; display: flex; justify-content: center;">
      {{ $applications->links() }}
    </div>
    @endif
    @endif
  </div>

<!-- Application Progress Modal -->
<div id="appModal" style="display:none; position:fixed; inset:0; z-index:1050; background:rgba(0,0,0,0.5); align-items:center; justify-content:center; padding:16px;">
  <div style="background:white; border-radius:12px; width:100%; max-width:560px; box-shadow:0 20px 60px rgba(0,0,0,0.2); overflow:hidden; max-height:90vh; display:flex; flex-direction:column;">

    <!-- Modal Header -->
    <div style="padding:20px 24px; border-bottom:1px solid #e9ecef; display:flex; justify-content:space-between; align-items:center; flex-shrink:0;">
      <div>
        <h5 style="margin:0; font-size:1.1rem; font-weight:600; color:#333;">Application Details</h5>
        <div id="modalRef" style="font-family:monospace; font-size:13px; color:#6c757d; margin-top:4px;"></div>
      </div>
      <button onclick="closeAppModal()" style="background:none; border:none; cursor:pointer; padding:4px; color:#6c757d; font-size:22px; line-height:1;">&times;</button>
    </div>

    <!-- Modal Body (scrollable) -->
    <div style="padding:24px; overflow-y:auto; flex:1;">

      <!-- Status Badge + Cert Type -->
      <div style="display:flex; align-items:center; gap:12px; margin-bottom:24px; flex-wrap:wrap;">
        <span id="modalBadge" style="display:inline-block; padding:6px 16px; border-radius:20px; font-size:13px; font-weight:500;"></span>
        <span style="color:#adb5bd; font-size:13px;">|</span>
        <span id="modalCertType" style="color:#555; text-transform:capitalize; font-size:14px;"></span>
      </div>

      <!-- Workflow Progress (vertical stepper) -->
      <div style="margin-bottom:8px;">
        <div style="font-size:11px; font-weight:700; color:#888; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:16px;">Workflow Progress</div>

        <!-- Step 1: Registrar Review -->
        <div style="display:flex; align-items:flex-start; gap:14px;">
          <div style="display:flex; flex-direction:column; align-items:center; flex-shrink:0;">
            <div id="wf-icon-0" style="width:34px;height:34px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:16px;font-weight:700;border:2px solid #dee2e6;background:#f8f9fa;color:#adb5bd;"></div>
            <div id="wf-line-0" style="width:2px;height:26px;background:#e9ecef;margin:3px 0;"></div>
          </div>
          <div style="padding-top:6px; flex:1;">
            <div style="font-weight:600; font-size:14px; color:#333;">Registrar Review</div>
            <div id="wf-note-0" style="font-size:12px; color:#6c757d; margin-top:2px;"></div>
          </div>
        </div>

        <!-- Step 2: Dean Approval -->
        <div style="display:flex; align-items:flex-start; gap:14px;">
          <div style="display:flex; flex-direction:column; align-items:center; flex-shrink:0;">
            <div id="wf-icon-1" style="width:34px;height:34px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:16px;font-weight:700;border:2px solid #dee2e6;background:#f8f9fa;color:#adb5bd;"></div>
            <div id="wf-line-1" style="width:2px;height:26px;background:#e9ecef;margin:3px 0;"></div>
          </div>
          <div style="padding-top:6px; flex:1;">
            <div style="font-weight:600; font-size:14px; color:#333;">Dean Approval</div>
            <div id="wf-note-1" style="font-size:12px; color:#6c757d; margin-top:2px;"></div>
          </div>
        </div>

        <!-- Step 3: Upload Receipt -->
        <div style="display:flex; align-items:flex-start; gap:14px;">
          <div style="display:flex; flex-direction:column; align-items:center; flex-shrink:0;">
            <div id="wf-icon-2" style="width:34px;height:34px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:16px;font-weight:700;border:2px solid #dee2e6;background:#f8f9fa;color:#adb5bd;"></div>
            <div id="wf-line-2" style="width:2px;height:26px;background:#e9ecef;margin:3px 0;"></div>
          </div>
          <div style="padding-top:6px; flex:1;">
            <div style="font-weight:600; font-size:14px; color:#333;">Upload Receipt</div>
            <div id="wf-note-2" style="font-size:12px; color:#6c757d; margin-top:2px;"></div>
          </div>
        </div>

        <!-- Step 4: Administrator Approval -->
        <div style="display:flex; align-items:flex-start; gap:14px;">
          <div style="display:flex; flex-direction:column; align-items:center; flex-shrink:0;">
            <div id="wf-icon-3" style="width:34px;height:34px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:16px;font-weight:700;border:2px solid #dee2e6;background:#f8f9fa;color:#adb5bd;"></div>
            <div id="wf-line-3" style="width:2px;height:26px;background:#e9ecef;margin:3px 0;"></div>
          </div>
          <div style="padding-top:6px; flex:1;">
            <div style="font-weight:600; font-size:14px; color:#333;">Administrator Approval</div>
            <div id="wf-note-3" style="font-size:12px; color:#6c757d; margin-top:2px;"></div>
          </div>
        </div>

        <!-- Step 5: Ready for Pickup -->
        <div style="display:flex; align-items:flex-start; gap:14px;">
          <div style="display:flex; flex-direction:column; align-items:center; flex-shrink:0;">
            <div id="wf-icon-4" style="width:34px;height:34px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:16px;font-weight:700;border:2px solid #dee2e6;background:#f8f9fa;color:#adb5bd;"></div>
            <div id="wf-line-4" style="width:2px;height:26px;background:#e9ecef;margin:3px 0;"></div>
          </div>
          <div style="padding-top:6px; flex:1;">
            <div style="font-weight:600; font-size:14px; color:#333;">Ready for Pickup at OSA</div>
            <div id="wf-note-4" style="font-size:12px; color:#6c757d; margin-top:2px;"></div>
          </div>
        </div>

        <!-- Step 6: Certificate Claimed (no connector line below) -->
        <div style="display:flex; align-items:flex-start; gap:14px;">
          <div style="display:flex; flex-direction:column; align-items:center; flex-shrink:0;">
            <div id="wf-icon-5" style="width:34px;height:34px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:16px;font-weight:700;border:2px solid #dee2e6;background:#f8f9fa;color:#adb5bd;"></div>
          </div>
          <div style="padding-top:6px; flex:1;">
            <div style="font-weight:600; font-size:14px; color:#333;">Certificate Claimed</div>
            <div id="wf-note-5" style="font-size:12px; color:#6c757d; margin-top:2px;"></div>
          </div>
        </div>
      </div>

      <!-- Rejection Reason (shown only when rejected) -->
      <div id="rejectionReasonSection" style="display:none; background:#fff5f5; border:1px solid #f5c2c7; border-radius:8px; padding:16px; margin-bottom:20px;">
        <div style="font-size:11px; font-weight:700; color:#842029; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:8px;">Reason for Rejection</div>
        <div id="rejectionReasonText" style="font-size:14px; color:#842029; font-weight:600; margin-bottom:4px;"></div>
        <div id="rejectionDetailsText" style="font-size:13px; color:#a0291c; line-height:1.6;"></div>
      </div>

      <!-- Receipt Upload Section (shown only when Dean approved and receipt not yet uploaded) -->
      <div id="receiptUploadSection" style="display:none; border-top:1px solid #e9ecef; padding-top:20px; margin-top:20px;">

        <!-- Payment Instructions -->
        <div style="background:#e8f4fd; border:1px solid #2196f3; border-radius:8px; padding:16px; margin-bottom:16px;">
          <div style="font-size:11px; font-weight:700; color:#1565c0; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:10px;">Payment Instructions</div>
          <p style="margin:0 0 12px; font-size:13px; color:#1976d2; line-height:1.6;">
            Please proceed to the <strong>Business Affairs Office (BAO)</strong> to pay for your certificate request. Present the details below when paying.
          </p>
          <div style="background:white; border-radius:6px; padding:12px 14px; font-size:13px; color:#333; line-height:1.8;">
            <div style="display:flex; gap:8px;">
              <span style="color:#6c757d; min-width:130px; flex-shrink:0;">Reference Number:</span>
              <span id="payRef" style="font-family:monospace; font-weight:600; color:#1565c0;"></span>
            </div>
            <div style="display:flex; gap:8px;">
              <span style="color:#6c757d; min-width:130px; flex-shrink:0;">Certificate Type:</span>
              <span id="payCertType" style="font-weight:500; text-transform:capitalize;"></span>
            </div>
          </div>
          <!-- Payment Breakdown -->
          <div style="margin-top:12px; border-top:1px solid #90caf9; padding-top:12px;">
            <div style="font-size:11px; font-weight:700; color:#1565c0; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:8px;">Payment Breakdown</div>
            <div style="background:white; border-radius:6px; padding:12px 14px; font-size:13px; color:#333; line-height:1.8;">
              <div style="display:flex; gap:8px;">
                <span style="color:#6c757d; min-width:130px; flex-shrink:0;">Copies Requested:</span>
                <span id="payCopies" style="font-weight:500;"></span>
              </div>
              <div style="display:flex; gap:8px;">
                <span style="color:#6c757d; min-width:130px; flex-shrink:0;">Rate per Copy:</span>
                <span style="font-weight:500;">&#8369;50</span>
              </div>
              <div style="display:flex; gap:8px; border-top:1px solid #e9ecef; margin-top:6px; padding-top:6px;">
                <span style="color:#6c757d; min-width:130px; flex-shrink:0; font-weight:600;">Total Amount:</span>
                <span id="payTotal" style="font-weight:700; color:#1565c0; font-size:14px;"></span>
              </div>
            </div>
          </div>
          <p style="margin:12px 0 0; font-size:12px; color:#1565c0; font-style:italic;">
            Please present your reference number when paying at the Business Affairs Office (BAO).
          </p>
        </div>

        <div style="font-size:11px; font-weight:700; color:#888; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:14px;">Upload Payment Receipt</div>
        <div style="background:#fff8e1; border:1px solid #ffc107; border-radius:8px; padding:12px 14px; margin-bottom:16px; font-size:13px; color:#856404; line-height:1.5;">
          After paying at the BAO, upload your official payment receipt below to proceed to the next step.
        </div>
        <form id="receiptUploadForm" action="{{ route('receipt.upload') }}" method="POST" enctype="multipart/form-data" style="display:grid; gap:14px;">
          @csrf
          <input type="hidden" name="reference_num" id="modalReceiptRef" value="">
          <div>
            <label style="display:block; font-weight:600; color:#333; margin-bottom:6px; font-size:13px;">Official Receipt No. <span style="color:#dc3545;">*</span></label>
            <input type="text" name="official_receipt_no" required placeholder="Enter receipt number from Business Affairs Office"
                   style="width:100%; padding:10px 12px; border:1px solid #ced4da; border-radius:6px; font-size:14px; box-sizing:border-box;">
          </div>
          <div>
            <label style="display:block; font-weight:600; color:#333; margin-bottom:6px; font-size:13px;">Date Paid <span style="color:#dc3545;">*</span></label>
            <input type="date" name="date_paid" required max="{{ date('Y-m-d') }}"
                   style="width:100%; padding:10px 12px; border:1px solid #ced4da; border-radius:6px; font-size:14px; box-sizing:border-box;">
          </div>
          <div>
            <label style="display:block; font-weight:600; color:#333; margin-bottom:6px; font-size:13px;">Receipt Document <span style="color:#dc3545;">*</span></label>
            <input type="file" name="document_path" required accept=".pdf,.jpg,.jpeg,.png"
                   style="width:100%; padding:10px 12px; border:1px solid #ced4da; border-radius:6px; font-size:13px; box-sizing:border-box;">
            <div style="font-size:11px; color:#6c757d; margin-top:5px;">Accepted: PDF, JPG, PNG · Max 2MB · Must be from Business Affairs Office</div>
          </div>
          <button type="submit"
                  style="width:100%; padding:10px 16px; background:#28a745; color:white; border:none; border-radius:6px; font-size:14px; font-weight:600; cursor:pointer;"
                  onmouseover="this.style.background='#218838'" onmouseout="this.style.background='#28a745'">
            Upload Receipt
          </button>
        </form>
      </div>

    </div><!-- /modal body -->

  </div>
</div>

<script>
function _wfGetStepState(appStatus) {
  if (!appStatus) return { current: 0, rejected: -1 };
  if (appStatus === 'Claimed')                                         return { current: 6, rejected: -1 };
  if (appStatus.includes('Ready') || appStatus.includes('Printed'))   return { current: 5, rejected: -1 };
  if (appStatus.includes('Approved by Administrator'))                 return { current: 4, rejected: -1 };
  if (appStatus.includes('Receipt Uploaded'))                          return { current: 3, rejected: -1 };
  if (appStatus.includes('Approved by Dean') || appStatus.includes('Approved By Dean'))           return { current: 2, rejected: -1 };
  if (appStatus.includes('Approved by Registrar') || appStatus.includes('Approved By Registrar')) return { current: 1, rejected: -1 };
  if (appStatus.includes('Rejected')) {
    if (appStatus.includes('Dean'))          return { current: -1, rejected: 1 };
    if (appStatus.includes('Administrator')) return { current: -1, rejected: 3 };
    return { current: -1, rejected: 0 };
  }
  return { current: 0, rejected: -1 };
}

var _wfNotes = [
  'Application submitted and awaiting Registrar decision.',
  'Registrar approved. Awaiting Dean review.',
  'Dean approved. Please upload your payment receipt.',
  'Receipt received. Awaiting Administrator approval.',
  'Approved. Your certificate will be ready for pickup at the Office of Student Affairs (OSA).',
  'Your certificate has been claimed. Thank you!',
];

function _wfSetIcon(i, bg, borderColor, color, symbol) {
  var el = document.getElementById('wf-icon-' + i);
  el.style.background     = bg;
  el.style.borderColor    = borderColor;
  el.style.color          = color;
  el.textContent          = symbol;
  if (i < 5) {
    var line = document.getElementById('wf-line-' + i);
    if (line) line.style.background = (borderColor === '#28a745' || borderColor === '#6f42c1') ? borderColor : '#e9ecef';
  }
}

function openAppModal(ref, certType, appStatus, statusText, statusColor, statusBg, progressMsg, copies, total, rejectionReason, rejectionDetails) {
  document.getElementById('modalRef').textContent = ref;
  document.getElementById('modalCertType').textContent = certType;

  var badge = document.getElementById('modalBadge');
  badge.textContent    = statusText;
  badge.style.background = statusBg;
  badge.style.color      = statusColor;

  var state    = _wfGetStepState(appStatus);
  var current  = state.current;
  var rejected = state.rejected;

  for (var i = 0; i < 6; i++) {
    var note = document.getElementById('wf-note-' + i);
    if (rejected >= 0) {
      if (i < rejected)      { _wfSetIcon(i, '#d4edda', '#28a745', '#155724', '✓'); note.textContent = ''; }
      else if (i === rejected){ _wfSetIcon(i, '#f8d7da', '#dc3545', '#721c24', '✕'); note.textContent = 'Rejected at this stage.'; }
      else                    { _wfSetIcon(i, '#f8f9fa', '#dee2e6', '#adb5bd', '○'); note.textContent = ''; }
    } else if (current === 6) {
      // All steps complete (Claimed)
      _wfSetIcon(i, '#e8d5ff', '#6f42c1', '#4a1f8a', '✓');
      note.textContent = (i === 5) ? _wfNotes[5] : '';
    } else if (i < current) {
      _wfSetIcon(i, '#d4edda', '#28a745', '#155724', '✓');
      note.textContent = '';
    } else if (i === current && current < 6) {
      _wfSetIcon(i, '#fff3cd', '#ffc107', '#856404', '●');
      note.textContent = _wfNotes[i] || '';
    } else {
      _wfSetIcon(i, '#f8f9fa', '#dee2e6', '#adb5bd', '○');
      note.textContent = '';
    }
  }

  // Show rejection reason when rejected
  var isRejected = appStatus && appStatus.includes('Rejected');
  var rejSection = document.getElementById('rejectionReasonSection');
  rejSection.style.display = isRejected ? 'block' : 'none';
  if (isRejected) {
    document.getElementById('rejectionReasonText').textContent  = rejectionReason || 'No reason provided.';
    document.getElementById('rejectionDetailsText').textContent = rejectionDetails || '';
  }

  // Show receipt upload only when Dean approved and receipt not yet submitted
  var needsReceipt = appStatus &&
    (appStatus.includes('Approved by Dean') || appStatus.includes('Approved By Dean'));
  var uploadSection = document.getElementById('receiptUploadSection');
  uploadSection.style.display = needsReceipt ? 'block' : 'none';
  if (needsReceipt) {
    document.getElementById('modalReceiptRef').value    = ref;
    document.getElementById('payRef').textContent       = ref;
    document.getElementById('payCertType').textContent  = certType;
    document.getElementById('payCopies').textContent    = copies;
    document.getElementById('payTotal').textContent     = '\u20B1' + (copies * 100).toLocaleString();
  }

  document.getElementById('appModal').style.display = 'flex';
}

function closeAppModal() {
  document.getElementById('appModal').style.display = 'none';
}

document.getElementById('appModal').addEventListener('click', function(e) {
  if (e.target === this) closeAppModal();
});
</script>

</x-dashboard-layout>
