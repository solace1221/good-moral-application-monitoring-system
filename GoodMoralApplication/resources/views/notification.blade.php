@php
  $accountType = Auth::user()->account_type;
  $roleTitle = $accountType === 'alumni' ? 'Alumni' : 'Student';
@endphp

<x-dashboard-layout>
  <x-slot name="roleTitle">{{ $roleTitle }}</x-slot>

  <x-slot name="navigation">
    @if($accountType === 'alumni')
      <x-alumni-navigation />
    @else
      <x-student-navigation />
    @endif
  </x-slot>

  <!-- Header Section -->
  <div class="header-section">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
      <div>
        <h1 class="role-title">Application Notifications</h1>
        <p class="welcome-text">Track your Good Moral Certificate application status</p>
        <div class="accent-line"></div>
      </div>
      <div style="display: flex; gap: 12px; align-items: center;">
        <div style="padding: 12px 16px; background: var(--light-green); border-radius: 8px; font-size: 14px; color: var(--primary-green); font-weight: 600;">
          {{ $grouped->count() }} Application{{ $grouped->count() !== 1 ? 's' : '' }}
        </div>
      </div>
    </div>
  </div>

  @if(session('status'))
  <div style="background: #d4edda; color: #155724; padding: 16px; border-radius: 8px; margin-bottom: 24px; border-left: 4px solid #28a745;">
    <strong>Success!</strong> {{ session('status') }}
  </div>
  @endif

  <!-- Applications Table -->
  <div style="background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); overflow: hidden; margin-bottom: 24px;">
    <div style="padding: 20px 24px; border-bottom: 1px solid #e9ecef;">
      <h2 style="margin: 0; color: var(--primary-green); font-size: 1.15rem; font-weight: 600;">Application History</h2>
    </div>

    @if($grouped->isEmpty())
    <div style="padding: 48px; text-align: center; color: #6c757d;">
      <svg style="width: 64px; height: 64px; margin: 0 auto 16px; color: #dee2e6;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
      </svg>
      <p style="margin: 0; font-size: 1.1rem; font-weight: 500;">No applications found</p>
      <p style="margin: 8px 0 0; font-size: 0.9rem;">You haven't submitted any certificate applications yet.</p>
    </div>
    @else
    <div style="overflow-x: auto;">
      <table style="width: 100%; border-collapse: collapse;">
        <thead>
          <tr style="background: #f8f9fa; border-bottom: 2px solid #e9ecef;">
            <th style="padding: 14px 16px; text-align: left; font-weight: 600; color: #495057; font-size: 13px;">Reference Number</th>
            <th style="padding: 14px 16px; text-align: left; font-weight: 600; color: #495057; font-size: 13px;">Certificate Type</th>
            <th style="padding: 14px 16px; text-align: left; font-weight: 600; color: #495057; font-size: 13px;">Purpose</th>
            <th style="padding: 14px 16px; text-align: left; font-weight: 600; color: #495057; font-size: 13px;">Copies</th>
            <th style="padding: 14px 16px; text-align: left; font-weight: 600; color: #495057; font-size: 13px;">Date Applied</th>
            <th style="padding: 14px 16px; text-align: left; font-weight: 600; color: #495057; font-size: 13px;">Status</th>
            <th style="padding: 14px 16px; text-align: center; font-weight: 600; color: #495057; font-size: 13px;">Action</th>
          </tr>
        </thead>
        <tbody>
          @foreach($grouped as $refNum => $updates)
          @php
            $latest = $updates->first(); // ordered desc, so first = most recent
            $first  = $updates->last();  // oldest = original submission

            $statusColors = [
              '0' => ['bg' => '#ffc10720', 'text' => '#856404', 'label' => 'With Registrar'],
              '1' => ['bg' => '#17a2b820', 'text' => '#17a2b8',  'label' => 'Registrar Approved'],
              '3' => ['bg' => '#007bff20', 'text' => '#007bff',  'label' => 'Dean Approved'],
              '4' => ['bg' => '#6610f220', 'text' => '#6610f2',  'label' => 'Receipt Uploaded'],
              '2' => ['bg' => '#28a74520', 'text' => '#28a745',  'label' => 'Ready for Pickup'],
              '5' => ['bg' => '#28a74520', 'text' => '#28a745',  'label' => 'Certificate Printed'],
              '6' => ['bg' => '#6f42c120', 'text' => '#6f42c1',  'label' => 'Released'],
              '-1'=> ['bg' => '#dc354520', 'text' => '#dc3545',  'label' => 'Rejected'],
              '-2'=> ['bg' => '#dc354520', 'text' => '#dc3545',  'label' => 'Rejected'],
              '-3'=> ['bg' => '#dc354520', 'text' => '#dc3545',  'label' => 'Rejected'],
            ];
            $sc = $statusColors[$latest->status] ?? ['bg' => '#6c757d20', 'text' => '#6c757d', 'label' => ucfirst($latest->status)];

            $certName = $latest->certificate_type === 'good_moral' ? 'Good Moral' : 'Residency';
            $purpose  = is_array($latest->reason) ? implode(', ', $latest->reason) : ($latest->reason ?? '—');
            $copies      = (int) ($latest->number_of_copies ?? 1);
            $reasonCount = is_array($latest->reason) ? count($latest->reason) : ($latest->reason ? 1 : 0);
            $total       = $reasonCount * $copies * 100;
            $rejDetail = $rejectionDetails[$refNum] ?? null;

            // Build rejection JSON for the modal
            $rejJson = $rejDetail ? json_encode([
              'reason'  => $rejDetail['rejection_reason'],
              'details' => $rejDetail['rejection_details'] ?? '',
              'by'      => $rejDetail['rejected_by'] ?? '',
              'at'      => $rejDetail['rejected_at'] ? \Carbon\Carbon::parse($rejDetail['rejected_at'])->format('M j, Y g:i A') : '',
            ]) : 'null';

            // Determine if receipt upload is needed (Dean approved = status '3', student must upload before Admin review)
            // Use status === 'uploaded' check, NOT document_path, because the Dean-generated payment notice
            // also populates document_path (status='pending_payment') before the student actually uploads.
            $needsReceipt = $latest->status === '3';
            $receipt = $receipts[$refNum] ?? null;
            $alreadyUploaded = $receipt && $receipt->status === 'uploaded';
          @endphp
          <tr style="border-bottom: 1px solid #e9ecef; transition: background-color 0.15s;"
              onmouseover="this.style.backgroundColor='#f8f9fa'"
              onmouseout="this.style.backgroundColor='transparent'">

            <td style="padding: 14px 16px; font-family: monospace; font-size: 13px; font-weight: 600; color: #333;">
              {{ $refNum }}
            </td>

            <td style="padding: 14px 16px;">
              <span style="display: inline-block; padding: 4px 10px; background: {{ $latest->certificate_type === 'good_moral' ? '#28a74520' : '#17a2b820' }}; color: {{ $latest->certificate_type === 'good_moral' ? '#28a745' : '#17a2b8' }}; border-radius: 20px; font-size: 12px; font-weight: 500;">
                {{ $certName }}
              </span>
            </td>

            <td style="padding: 14px 16px; color: #555; font-size: 13px; max-width: 200px;">
              {{ Str::limit($purpose, 50) }}
            </td>

            <td style="padding: 14px 16px; text-align: center; font-weight: 600; color: #333; font-size: 13px;">
              {{ $copies }}
            </td>

            <td style="padding: 14px 16px; color: #555; font-size: 13px;">
              <div style="font-weight: 500; color: #333;">{{ $first->created_at->format('M d, Y') }}</div>
              <div style="font-size: 11px; color: #6c757d;">{{ $first->created_at->format('h:i A') }}</div>
            </td>

            <td style="padding: 14px 16px;">
              <span style="display: inline-block; padding: 5px 12px; background: {{ $sc['bg'] }}; color: {{ $sc['text'] }}; border-radius: 20px; font-size: 12px; font-weight: 500;">
                {{ $sc['label'] }}
              </span>
            </td>

            <td style="padding: 14px 16px; text-align: center;">
              <button type="button"
                onclick="openAppModal(
                  '{{ $refNum }}',
                  '{{ addslashes($certName) }}',
                  '{{ $latest->status }}',
                  '{{ addslashes($sc['label']) }}',
                  '{{ $sc['text'] }}',
                  '{{ $sc['bg'] }}',
                  {{ $copies }},
                  {{ $total }},
                  {{ $reasonCount }},
                  {{ $needsReceipt && !$alreadyUploaded ? 'true' : 'false' }},
                  {{ $rejJson }}
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
    @endif
  </div>

<!-- Application Progress Modal -->
<div id="appModal" style="display:none; position:fixed; inset:0; z-index:1050; background:rgba(0,0,0,0.5); align-items:center; justify-content:center; padding:16px;">
  <div style="background:white; border-radius:12px; width:100%; max-width:560px; box-shadow:0 20px 60px rgba(0,0,0,0.2); overflow:hidden; max-height:90vh; display:flex; flex-direction:column;">

    <!-- Modal Header -->
    <div style="padding:20px 24px; border-bottom:1px solid #e9ecef; display:flex; justify-content:space-between; align-items:center; flex-shrink:0; background:linear-gradient(135deg, var(--primary-green) 0%, #009944 100%);">
      <div>
        <h5 style="margin:0; font-size:1.1rem; font-weight:700; color:white !important;">Application Details</h5>
        <div id="modalRef" style="font-family:monospace; font-size:13px; color:rgba(255,255,255,0.85); margin-top:4px;"></div>
      </div>
      <button onclick="closeAppModal()" style="background:rgba(255,255,255,0.2); border:none; cursor:pointer; padding:6px; color:white; font-size:22px; line-height:1; border-radius:50%; width:32px; height:32px; display:flex; align-items:center; justify-content:center;">&times;</button>
    </div>

    <!-- Modal Body (scrollable) -->
    <div style="padding:24px; overflow-y:auto; flex:1;">

      <!-- Status Badge + Cert Type -->
      <div style="display:flex; align-items:center; gap:12px; margin-bottom:24px; flex-wrap:wrap;">
        <span id="modalBadge" style="display:inline-block; padding:6px 16px; border-radius:20px; font-size:13px; font-weight:600;"></span>
        <span style="color:#adb5bd; font-size:13px;">|</span>
        <span id="modalCertType" style="color:#555; font-size:14px;"></span>
      </div>

      <!-- Workflow Progress (vertical stepper) -->
      <div style="margin-bottom:8px;">
        <div style="font-size:11px; font-weight:700; color:#888; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:16px;">Workflow Progress</div>

        <!-- Step 0: Registrar Review -->
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

        <!-- Step 1: Dean Approval -->
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

        <!-- Step 2: Upload Receipt -->
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

        <!-- Step 3: Administrator Approval -->
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

        <!-- Step 4: Ready for Pickup (no connector line) -->
        <div style="display:flex; align-items:flex-start; gap:14px;">
          <div style="display:flex; flex-direction:column; align-items:center; flex-shrink:0;">
            <div id="wf-icon-4" style="width:34px;height:34px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:16px;font-weight:700;border:2px solid #dee2e6;background:#f8f9fa;color:#adb5bd;"></div>
          </div>
          <div style="padding-top:6px; flex:1;">
            <div style="font-weight:600; font-size:14px; color:#333;">Ready for Pickup at OSA</div>
            <div id="wf-note-4" style="font-size:12px; color:#6c757d; margin-top:2px;"></div>
          </div>
        </div>
      </div><!-- /stepper -->

      <!-- Rejection Details (shown only when rejected) -->
      <div id="rejectionSection" style="display:none; margin-top:20px; padding:14px 16px; background:#fff5f5; border-radius:8px; border-left:4px solid #dc3545; font-size:13px; color:#721c24; line-height:1.7;">
        <div style="font-weight:700; margin-bottom:6px; color:#dc3545;">Application Rejected</div>
        <div id="rejReason"></div>
        <div id="rejDetails"></div>
        <div id="rejBy"></div>
      </div>

      <!-- Receipt Upload Section (shown only when Dean approved and receipt not yet uploaded) -->
      <div id="receiptUploadSection" style="display:none; border-top:1px solid #e9ecef; padding-top:20px; margin-top:20px;">

        <div style="background:#e8f4fd; border:1px solid #2196f3; border-radius:8px; padding:16px; margin-bottom:16px;">
          <div style="font-size:11px; font-weight:700; color:#1565c0; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:10px;">Payment Instructions</div>
          <p style="margin:0 0 10px; font-size:13px; color:#1976d2; line-height:1.6;">
            Please proceed to the <strong>Business Affairs Office (BAO)</strong> to pay for your certificate. Present the breakdown below when paying.
          </p>
          <div style="background:white; border-radius:6px; padding:12px 14px; font-size:13px; color:#333; line-height:1.9;">
            <div style="display:flex; gap:8px;">
              <span style="color:#6c757d; min-width:130px; flex-shrink:0;">Reference Number:</span>
              <span id="payRef" style="font-family:monospace; font-weight:600; color:#1565c0;"></span>
            </div>
            <div style="display:flex; gap:8px;">
              <span style="color:#6c757d; min-width:130px; flex-shrink:0;">Certificate Type:</span>
              <span id="payCertType" style="font-weight:500;"></span>
            </div>
            <div style="display:flex; gap:8px; border-top:1px solid #e9ecef; margin-top:6px; padding-top:6px;">
              <span style="color:#6c757d; min-width:130px; flex-shrink:0;">Reasons:</span>
              <span id="payReasons" style="font-weight:500;"></span>
            </div>
            <div style="display:flex; gap:8px;">
              <span style="color:#6c757d; min-width:130px; flex-shrink:0;">Copies:</span>
              <span id="payCopies" style="font-weight:500;"></span>
            </div>
            <div style="display:flex; gap:8px;">
              <span style="color:#6c757d; min-width:130px; flex-shrink:0;">Rate per Copy:</span>
              <span style="font-weight:500;">&#8369;100</span>
            </div>
            <div style="display:flex; gap:8px; border-top:1px solid #e9ecef; margin-top:6px; padding-top:6px; font-weight:700;">
              <span style="color:#6c757d; min-width:130px; flex-shrink:0;">Total Amount:</span>
              <span id="payTotal" style="color:#1565c0; font-size:15px;"></span>
            </div>
          </div>
        </div>

        <div style="font-size:11px; font-weight:700; color:#888; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:12px;">Upload Payment Receipt</div>
        <div style="background:#fff8e1; border:1px solid #ffc107; border-radius:8px; padding:10px 14px; margin-bottom:14px; font-size:13px; color:#856404;">
          After paying at the BAO, upload your official payment receipt below to proceed.
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
            <div style="background:#fff8e6; border:1px solid #ffc107; border-radius:6px; padding:8px 12px; margin-bottom:8px; font-size:12px; color:#856404;">
              Must be the <strong>original receipt from Business Affairs Office</strong>. Screenshots will be rejected. PDF, JPG, PNG · Max 2MB.
            </div>
            <input type="file" name="document_path" required accept=".pdf,.jpg,.jpeg,.png"
                   style="width:100%; padding:10px 12px; border:1px solid #ced4da; border-radius:6px; font-size:13px; box-sizing:border-box;">
          </div>
          <button type="submit"
                  style="width:100%; padding:10px 16px; background:#28a745; color:white !important; border:none; border-radius:6px; font-size:14px; font-weight:600; cursor:pointer;"
                  onmouseover="this.style.background='#218838'" onmouseout="this.style.background='#28a745'">
            Upload Receipt
          </button>
        </form>
      </div>

    </div><!-- /modal body -->

    <!-- Modal Footer -->
    <div style="padding:14px 24px; border-top:1px solid #e9ecef; text-align:right; flex-shrink:0;">
      <button onclick="closeAppModal()" style="padding:8px 20px; background:#6c757d; color:white !important; border:none; border-radius:6px; font-size:14px; cursor:pointer;">Close</button>
    </div>

  </div>
</div>

<script>
// Student workflow: 0=Registrar, 1=Dean, 2=Upload Receipt, 3=Admin, 4=Pickup
function _wfGetStepState(status) {
  if (['5','6'].indexOf(status) >= 0)  return { current: 5, rejected: -1 }; // printed/released
  if (status === '2')  return { current: 5, rejected: -1 }; // admin approved → ready for pickup (all steps done)
  if (status === '4')  return { current: 3, rejected: -1 }; // receipt uploaded → awaiting admin approval
  if (status === '3')  return { current: 2, rejected: -1 }; // dean approved → upload receipt (student action)
  if (status === '1')  return { current: 1, rejected: -1 }; // registrar approved → dean
  if (status === '-1') return { current: -1, rejected: 0 }; // rejected by registrar
  if (status === '-3') return { current: -1, rejected: 1 }; // rejected by dean
  if (status === '-2') return { current: -1, rejected: 3 }; // rejected by admin (step 3)
  return { current: 0, rejected: -1 }; // default: with registrar
}

var _wfNotes = [
  'Application submitted and awaiting Registrar decision.',
  'Registrar approved. Awaiting Dean review.',
  'Dean approved. Please upload your payment receipt.',
  'Receipt received. Awaiting Administrator approval.',
  'Approved. Your certificate will be ready for pickup at the Office of Student Affairs (OSA).',
];

function _wfSetIcon(i, bg, borderColor, color, symbol) {
  var el = document.getElementById('wf-icon-' + i);
  el.style.background  = bg;
  el.style.borderColor = borderColor;
  el.style.color       = color;
  el.textContent       = symbol;
  if (i < 4) {
    document.getElementById('wf-line-' + i).style.background =
      (borderColor === '#28a745') ? '#28a745' : '#e9ecef';
  }
}

function openAppModal(ref, certType, status, statusLabel, statusColor, statusBg, copies, total, reasonCount, needsReceipt, rejDetail) {
  document.getElementById('modalRef').textContent = ref;
  document.getElementById('modalCertType').textContent = certType;

  var badge = document.getElementById('modalBadge');
  badge.textContent      = statusLabel;
  badge.style.background = statusBg;
  badge.style.color      = statusColor;

  var state    = _wfGetStepState(status);
  var current  = state.current;
  var rejected = state.rejected;

  for (var i = 0; i < 5; i++) {
    var note = document.getElementById('wf-note-' + i);
    note.textContent = '';
    if (rejected >= 0) {
      if (i < rejected)       { _wfSetIcon(i, '#d4edda', '#28a745', '#155724', '✓'); }
      else if (i === rejected) { _wfSetIcon(i, '#f8d7da', '#dc3545', '#721c24', '✕'); note.textContent = 'Rejected at this stage.'; }
      else                     { _wfSetIcon(i, '#f8f9fa', '#dee2e6', '#adb5bd', '○'); }
    } else if (i < current) {
      _wfSetIcon(i, '#d4edda', '#28a745', '#155724', '✓');
    } else if (i === current && current < 5) {
      _wfSetIcon(i, '#fff3cd', '#ffc107', '#856404', '●');
      note.textContent = _wfNotes[i] || '';
    } else {
      _wfSetIcon(i, '#f8f9fa', '#dee2e6', '#adb5bd', '○');
    }
  }

  // Rejection details panel
  var rejSec = document.getElementById('rejectionSection');
  if (rejDetail && rejected >= 0) {
    document.getElementById('rejReason').textContent  = rejDetail.reason  ? 'Reason: ' + rejDetail.reason : '';
    document.getElementById('rejDetails').textContent = rejDetail.details ? 'Details: ' + rejDetail.details : '';
    document.getElementById('rejBy').textContent      = (rejDetail.by ? 'By: ' + rejDetail.by : '') + (rejDetail.at ? ' — ' + rejDetail.at : '');
    rejSec.style.display = 'block';
  } else {
    rejSec.style.display = 'none';
  }

  // Receipt upload section
  var uploadSection = document.getElementById('receiptUploadSection');
  uploadSection.style.display = needsReceipt ? 'block' : 'none';
  if (needsReceipt) {
    document.getElementById('modalReceiptRef').value   = ref;
    document.getElementById('payRef').textContent      = ref;
    document.getElementById('payCertType').textContent = certType;
    document.getElementById('payReasons').textContent  = reasonCount;
    document.getElementById('payCopies').textContent   = copies;
    document.getElementById('payTotal').textContent    = '\u20B1' + (reasonCount * copies * 100).toLocaleString();
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
