@php
  $accountType = Auth::user()->account_type;
  $roleTitle = $accountType === 'alumni' ? 'Alumni' : 'Student';
@endphp

<x-dashboard-layout>
  <x-slot name="roleTitle">{{ $roleTitle }}</x-slot>

  <x-slot name="navigation">
    <a href="{{ route('dashboard') }}"
       class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="nav-icon">
        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z" />
      </svg>
      <span>Application</span>
    </a>

    <a href="{{ route('notification') }}"
       class="nav-link {{ request()->routeIs('notification') ? 'active' : '' }}">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="nav-icon">
        <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
      </svg>
      <span>Application Notifications</span>
    </a>

    <a href="{{ route('notificationViolation') }}"
       class="nav-link {{ request()->routeIs('notificationViolation') ? 'active' : '' }}">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="nav-icon">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
      </svg>
      <span>Violation Notifications</span>
    </a>

    <a href="{{ route('student.profile') }}"
       class="nav-link {{ request()->routeIs('student.profile') ? 'active' : '' }}">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="nav-icon">
        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
      </svg>
      <span>Profile</span>
    </a>

    <form method="POST" action="{{ route('logout') }}" class="nav-logout-form">
      @csrf
      <button type="submit" class="nav-link nav-logout">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="nav-icon">
          <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
        </svg>
        <span>Logout</span>
      </button>
    </form>
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
          {{ $notifications->count() }} Notification{{ $notifications->count() !== 1 ? 's' : '' }}
        </div>
      </div>
    </div>
  </div>

  @if(session('status'))
  <div style="background: #d4edda; color: #155724; padding: 16px; border-radius: 8px; margin-bottom: 24px; border-left: 4px solid #28a745;">
    <strong>Success!</strong> {{ session('status') }}
  </div>
  @endif

  <!-- Notifications Section -->
  <div class="header-section">
    <h3 style="color: var(--primary-green); margin-bottom: 20px; font-size: 1.3rem; display: flex; align-items: center; gap: 12px;">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="height: 24px; width: 24px;">
        <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
      </svg>
      Your Application Status Updates
    </h3>

    @if($notifications->isEmpty())
    <div style="background: #f8f9fa; color: #6c757d; padding: 40px; border-radius: 8px; text-align: center; border: 2px dashed #dee2e6;">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="height: 48px; width: 48px; margin: 0 auto 16px; color: #adb5bd;">
        <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
      </svg>
      <h4 style="font-size: 1.2rem; margin-bottom: 8px; color: #495057;">No Notifications</h4>
      <p style="margin: 0;">You have no new notifications at the moment.</p>
    </div>
    @else
    <div style="display: grid; gap: 20px;">
      @foreach ($notifications as $notification)
      <div style="background: white; border-radius: 12px; padding: 24px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
                  border-left: 4px solid
                  @switch($notification->status)
                    @case('approved') #28a745 @break
                    @case('0') #ffc107 @break
                    @case('1') #17a2b8 @break
                    @case('3') #28a745 @break
                    @case('2') #28a745 @break
                    @case('4') #28a745 @break
                    @case('5') #28a745 @break
                    @case('-1') #dc3545 @break
                    @case('-2') #dc3545 @break
                    @case('-3') #dc3545 @break
                    @default #6c757d
                  @endswitch;">

        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 16px;">
          <div style="flex: 1;">
            <h5 style="font-size: 1.2rem; font-weight: 600; color: #333; margin-bottom: 8px;">
              Good Moral Certificate Application
            </h5>
            <div style="display: flex; align-items: center; gap: 8px; color: #666; font-size: 14px;">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="height: 16px; width: 16px;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5a2.25 2.25 0 002.25-2.25m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5a2.25 2.25 0 012.25 2.25v7.5" />
              </svg>
              {{ $notification->created_at->format('M d, Y \a\t g:i A') }}
            </div>
          </div>

          <span style="padding: 6px 16px; border-radius: 20px; font-size: 12px; font-weight: 600; text-transform: uppercase;
                       background:
                       @switch($notification->status)
                         @case('approved') #28a745 @break
                         @case('0') #ffc107 @break
                         @case('1') #17a2b8 @break
                         @case('3') #28a745 @break
                         @case('2') #28a745 @break
                         @case('4') #28a745 @break
                         @case('5') #28a745 @break
                         @case('-1') #dc3545 @break
                         @case('-2') #dc3545 @break
                         @case('-3') #dc3545 @break
                         @default #6c757d
                       @endswitch;
                       color:
                       @if(in_array($notification->status, ['0']))
                         #333
                       @else
                         white
                       @endif;">
            @switch($notification->status)
              @case('0') With Registrar @break
              @case('-1') Rejected by Registrar @break
              @case('-2') Rejected by Administrator @break
              @case('-3') Rejected by Dean @break
              @case('1') Approved by Registrar @break
              @case('2') Approved by Administrator @break
              @case('3') Approved by Dean @break
              @case('4') Ready for Pickup @break
              @case('5') Certificate Printed @break
              @default {{ ucfirst($notification->status) }}
            @endswitch
          </span>
        </div>

        <div style="display: grid; gap: 12px; margin-bottom: 16px;">
          <div style="display: flex; align-items: center; gap: 12px;">
            <strong style="color: #333; min-width: 140px;">Reference Number:</strong>
            <span style="font-family: monospace; background: #f8f9fa; padding: 4px 8px; border-radius: 4px; font-size: 14px;">{{ $notification->reference_number }}</span>
          </div>
          <div style="display: flex; align-items: center; gap: 12px;">
            <strong style="color: #333; min-width: 140px;">Reason:</strong>
            <span style="color: #666;">{{ $notification->formatted_reasons }}</span>
          </div>
        </div>

        <div style="background: #f8f9fa; padding: 16px; border-radius: 8px; margin-bottom: 16px;">
          <strong style="color: #333; display: block; margin-bottom: 8px;">Status Message:</strong>
          <div style="max-height: 120px; overflow-y: auto; padding: 8px; background: white; border-radius: 6px; border: 1px solid #e1e5e9;">
            <p style="margin: 0; color: #555; line-height: 1.5;">
              @switch($notification->status)
                @case('0')
                  <strong>Step 1 of 6:</strong> Your application has been submitted successfully and is now with the <strong>Registrar</strong> for review & approval.
                  @break
                @case('1')
                  <strong>Step 2 of 6:</strong> Your application has been approved by the Registrar and forwarded to the <strong>Dean</strong> for review & approval.
                  @break
                @case('3')
                  <strong>Step 3 of 6:</strong> Your application has been approved by the Dean and forwarded to the <strong>Administrator</strong> for final approval.
                  @break
              @case('2')
                <strong>Step 4 of 6:</strong> Your application has been approved by the Administrator. Please <strong>upload your payment receipt</strong> to proceed to certificate printing.
                @break
              @case('4')
                <strong>Step 5 of 6:</strong> Your payment receipt has been verified. Your certificate is now ready for <strong>printing & pickup</strong>.
                @break
              @case('5')
                <strong>Step 6 of 6:</strong> Your {{ $notification->certificate_type === 'good_moral' ? 'Good Moral Certificate' : 'Certificate of Residency' }} has been printed and is ready for pickup at the Registrar's Office.
                @break
              @case('-1')
                <strong style="color: #dc3545;">❌ Application Rejected by Registrar</strong>
                @if(isset($rejectionDetails[$notification->reference_number]))
                  <br><br>
                  <strong style="color: #dc3545;">Rejection Details:</strong><br>
                  <strong>Reason:</strong> {{ $rejectionDetails[$notification->reference_number]['rejection_reason'] }}<br>
                  @if($rejectionDetails[$notification->reference_number]['rejection_details'])
                    <strong>Additional Details:</strong> {{ $rejectionDetails[$notification->reference_number]['rejection_details'] }}<br>
                  @endif
                  <strong>Rejected by:</strong> {{ $rejectionDetails[$notification->reference_number]['rejected_by'] }}<br>
                  <strong>Date:</strong> {{ \Carbon\Carbon::parse($rejectionDetails[$notification->reference_number]['rejected_at'])->format('F j, Y g:i A') }}
                @else
                  Please contact the registrar's office for more information.
                @endif
                @break
              @case('-3')
                <strong style="color: #dc3545;">❌ Application Rejected by Dean</strong>
                @if(isset($rejectionDetails[$notification->reference_number]))
                  <br><br>
                  <strong style="color: #dc3545;">Rejection Details:</strong><br>
                  <strong>Reason:</strong> {{ $rejectionDetails[$notification->reference_number]['rejection_reason'] }}<br>
                  @if($rejectionDetails[$notification->reference_number]['rejection_details'])
                    <strong>Additional Details:</strong> {{ $rejectionDetails[$notification->reference_number]['rejection_details'] }}<br>
                  @endif
                  <strong>Rejected by:</strong> {{ $rejectionDetails[$notification->reference_number]['rejected_by'] }}<br>
                  <strong>Date:</strong> {{ \Carbon\Carbon::parse($rejectionDetails[$notification->reference_number]['rejected_at'])->format('F j, Y g:i A') }}
                @else
                  Please contact the dean's office for more information.
                @endif
                @break
              @case('-2')
                <strong style="color: #dc3545;">❌ Application Rejected by Administrator</strong>
                @if(isset($rejectionDetails[$notification->reference_number]))
                  <br><br>
                  <strong style="color: #dc3545;">Rejection Details:</strong><br>
                  <strong>Reason:</strong> {{ $rejectionDetails[$notification->reference_number]['rejection_reason'] }}<br>
                  @if($rejectionDetails[$notification->reference_number]['rejection_details'])
                    <strong>Additional Details:</strong> {{ $rejectionDetails[$notification->reference_number]['rejection_details'] }}<br>
                  @endif
                  <strong>Rejected by:</strong> {{ $rejectionDetails[$notification->reference_number]['rejected_by'] }}<br>
                  <strong>Date:</strong> {{ \Carbon\Carbon::parse($rejectionDetails[$notification->reference_number]['rejected_at'])->format('F j, Y g:i A') }}
                @else
                  Please contact the administrator's office for more information.
                @endif
                @break
              @default {{ $notification->message ?? 'Status update available.' }}
            @endswitch
            </p>
          </div>
        </div>

        {{-- Handle receipt display for status 2 (admin approval - payment required) --}}
        @if(in_array($notification->status, ['2']))
        @php
        $receipt = $receipts[$notification->reference_number] ?? null;
        @endphp

        {{-- Show payment notice if it exists for status 2 --}}
        @if($receipt && $receipt->document_path && $receipt->status === 'pending_payment')
        {{-- Payment Notice Section --}}
        <div style="background: #fff3cd; color: #856404; padding: 16px; border-radius: 8px; border-left: 4px solid #ffc107; margin-bottom: 16px;">
          <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="height: 20px; width: 20px; color: #856404;">
              <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-4.5B4.875 8.25 2.25 10.875 2.25 14.25v2.625M12 9.75v6.75m0 0l-3-3m3 3l3-3" />
            </svg>
            <span style="font-weight: 600;">Payment Notice Generated</span>
          </div>

          <div style="display: grid; gap: 8px; font-size: 14px; background: rgba(255,255,255,0.3); padding: 12px; border-radius: 6px; margin-bottom: 16px;">
            @if($receipt->receipt_number)
            <div style="display: flex; justify-content: space-between;">
              <strong>Notice Number:</strong>
              <span style="font-family: monospace; font-weight: 600; color: #856404;">{{ $receipt->receipt_number }}</span>
            </div>
            @endif

            @if($receipt->amount)
            <div style="display: flex; justify-content: space-between;">
              <strong>Amount Due:</strong>
              <span style="font-weight: 600; color: #856404;">₱{{ number_format($receipt->amount, 2) }}</span>
            </div>
            @endif

            <div style="display: flex; justify-content: space-between;">
              <strong>Generated:</strong>
              <span>{{ $receipt->created_at->format('F j, Y g:i A') }}</span>
            </div>

            <div style="display: flex; justify-content: space-between; align-items: center;">
              <strong>Payment Notice:</strong>
              <a href="{{ route('files.serve', $receipt->document_path) }}" target="_blank"
                 style="color: #856404; text-decoration: underline; font-weight: 600; display: flex; align-items: center; gap: 4px;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="height: 16px; width: 16px;">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                </svg>
                Download Payment Notice
              </a>
            </div>
          </div>

          <div style="background: #f8f9fa; padding: 12px; border-radius: 6px; border: 1px solid #dee2e6;">
            <p style="margin: 0; font-size: 14px; color: #495057;">
              <strong>Next Steps:</strong><br>
              1. Download the payment notice above<br>
              2. Proceed to the <strong>Business Affairs Office</strong> to make payment<br>
              3. Upload your official receipt from Business Affairs Office below
            </p>
          </div>
        </div>
        @endif

        {{-- Check if there's an uploaded receipt for this application --}}
        @php
        $uploadedReceipt = $receipts[$notification->reference_number] ?? null;
        $hasUploadedReceipt = $uploadedReceipt && $uploadedReceipt->status === 'uploaded' && $uploadedReceipt->official_receipt_no;
        @endphp

        @if($hasUploadedReceipt)
        {{-- Uploaded Receipt Section --}}
        <div style="background: #d4edda; color: #155724; padding: 16px; border-radius: 8px; margin-top: 16px;">
          <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="height: 20px; width: 20px; color: #28a745;">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span style="font-weight: 600;">Receipt uploaded successfully!</span>
          </div>

          <div style="display: grid; gap: 8px; font-size: 14px; background: rgba(255,255,255,0.3); padding: 12px; border-radius: 6px;">
            @if($uploadedReceipt->official_receipt_no)
            <div style="display: flex; justify-content: space-between;">
              <strong>Official Receipt No.:</strong>
              <span style="font-family: monospace; font-weight: 600; color: #155724;">{{ $uploadedReceipt->official_receipt_no }}</span>
            </div>
            @endif

            @if($uploadedReceipt->amount)
            <div style="display: flex; justify-content: space-between;">
              <strong>Amount Paid:</strong>
              <span style="font-weight: 600; color: #155724;">₱{{ number_format($uploadedReceipt->amount, 2) }}</span>
            </div>
            @endif

            @if($uploadedReceipt->date_paid)
            <div style="display: flex; justify-content: space-between;">
              <strong>Date Paid:</strong>
              <span>{{ \Carbon\Carbon::parse($uploadedReceipt->date_paid)->format('F j, Y') }}</span>
            </div>
            @endif

            <div style="display: flex; justify-content: space-between;">
              <strong>Uploaded:</strong>
              <span>{{ $uploadedReceipt->updated_at->format('F j, Y g:i A') }}</span>
            </div>

            <div style="display: flex; justify-content: space-between; align-items: center;">
              <strong>Receipt Document:</strong>
              <a href="{{ route('files.serve', $uploadedReceipt->document_path) }}" target="_blank"
                 style="color: #155724; text-decoration: underline; font-weight: 600; display: flex; align-items: center; gap: 4px;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="height: 16px; width: 16px;">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                </svg>
                View Uploaded Receipt
              </a>
            </div>
          </div>
        </div>
        @endif

        {{-- Always show upload form for status 2 if no receipt uploaded yet --}}
        @if(!$hasUploadedReceipt)
        {{-- Upload Form Section --}}
        <div style="background: #fff3cd; color: #856404; padding: 16px; border-radius: 8px; border-left: 4px solid #ffc107; margin-top: 16px;">
          <h6 style="margin: 0 0 12px 0; font-weight: 600;">
            Payment Receipt Required
          </h6>
          <p style="margin: 0 0 8px 0; font-size: 14px;">
            Please upload your payment receipt to continue processing your application.
          </p>

          <!-- Payment Amount Information -->
          <div style="background: #f8f9fa; padding: 12px; border-radius: 6px; margin-bottom: 16px; border: 1px solid #dee2e6;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
              <strong style="color: #495057;">Payment Details:</strong>
            </div>
            <div style="display: flex; justify-content: space-between; margin-bottom: 4px;">
              <span>Number of Reasons:</span>
              <span style="font-weight: 600;">{{ count($notification->reasons_array) }}</span>
            </div>
            <div style="display: flex; justify-content: space-between; margin-bottom: 4px;">
              <span>Number of Copies:</span>
              <span style="font-weight: 600;">{{ $notification->number_of_copies }}</span>
            </div>
            <div style="display: flex; justify-content: space-between; margin-bottom: 4px;">
              <span>Rate per Unit:</span>
              <span style="font-weight: 600;">₱50.00</span>
            </div>
            <hr style="margin: 8px 0; border: none; border-top: 1px solid #dee2e6;">
            <div style="display: flex; justify-content: space-between; font-size: 16px;">
              <strong style="color: #495057;">Total Amount:</strong>
              <strong style="color: #28a745; font-size: 18px;">₱{{ number_format($notification->payment_amount, 2) }}</strong>
            </div>
            <div style="font-size: 12px; color: #666; text-align: center; margin-top: 4px;">
              {{ count($notification->reasons_array) }} {{ count($notification->reasons_array) === 1 ? 'reason' : 'reasons' }} × {{ $notification->number_of_copies }} {{ $notification->number_of_copies == 1 ? 'copy' : 'copies' }} × ₱50.00
            </div>
          </div>

          <form action="{{ route('receipt.upload') }}" method="POST" enctype="multipart/form-data" style="display: grid; gap: 16px;">
            @csrf
            <input type="hidden" name="reference_num" value="{{ $notification->reference_number }}">

            <!-- Official Receipt Number -->
            <div>
              <label style="display: block; font-weight: 600; color: #333; margin-bottom: 8px;">
                Official Receipt No. from Business Affairs Office <span style="color: #dc3545;">*</span>
              </label>
              <input type="text" name="official_receipt_no" required placeholder="Enter official receipt number from Business Affairs Office"
                     style="width: 100%; padding: 12px; border: 2px solid #e1e5e9; border-radius: 8px; font-size: 14px;"
                     value="{{ old('official_receipt_no') }}">
              @error('official_receipt_no')
                <span style="color: #dc3545; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span>
              @enderror
            </div>

            <!-- Date Paid -->
            <div>
              <label style="display: block; font-weight: 600; color: #333; margin-bottom: 8px;">
                Date Paid <span style="color: #dc3545;">*</span>
              </label>
              <input type="date" name="date_paid" required
                     style="width: 100%; padding: 12px; border: 2px solid #e1e5e9; border-radius: 8px; font-size: 14px;"
                     value="{{ old('date_paid') }}" max="{{ date('Y-m-d') }}">
              @error('date_paid')
                <span style="color: #dc3545; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span>
              @enderror
            </div>

            <!-- Upload Receipt Document -->
            <div>
              <label style="display: block; font-weight: 600; color: #333; margin-bottom: 8px;">
                Upload Receipt Document from Business Affairs Office <span style="color: #dc3545;">*</span>
              </label>

              <!-- Receipt Requirements Notice -->
              <div style="background: #e3f2fd; border: 1px solid #2196f3; border-radius: 6px; padding: 12px; margin-bottom: 12px;">
                <div style="display: flex; align-items: flex-start; gap: 8px;">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="height: 20px; width: 20px; color: #2196f3; margin-top: 2px; flex-shrink: 0;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                  </svg>
                  <div style="font-size: 13px; color: #1976d2;">
                    <strong>⚠️ IMPORTANT: Valid Receipt Requirements</strong><br>
                    • Must be the <strong>original receipt from St. Paul University Philippines Business Affairs Office</strong><br>
                    • Should contain: Official Receipt header, receipt number, amount paid, date, and university details<br>
                    • <strong style="color: #d32f2f;">❌ WILL BE REJECTED: Screenshots, camera photos, social media images, edited files</strong><br>
                    • <strong style="color: #d32f2f;">❌ WILL BE REJECTED: Random images, non-receipt documents, corrupted files</strong><br>
                    • ✅ File should be clear, complete, and readable<br>
                    • ✅ Accepted formats: PDF, JPG, JPEG, PNG (Max: 2MB)
                  </div>
                </div>
              </div>

              <input type="file" name="document_path" required accept=".pdf,.jpg,.jpeg,.png"
                     style="width: 100%; padding: 12px; border: 2px solid #e1e5e9; border-radius: 8px; font-size: 14px;">
              <small style="color: #6c757d; font-size: 12px; margin-top: 4px; display: block;">
                Upload the official receipt you received from Business Affairs Office<br>
                Accepted formats: PDF, JPG, JPEG, PNG (Max: 2MB)
              </small>
              @error('document_path')
                <span style="color: #dc3545; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span>
              @enderror
            </div>

            <button type="submit" class="btn-primary" style="justify-self: start; display: flex; align-items: center; gap: 8px;">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="height: 16px; width: 16px;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
              </svg>
              Upload Receipt
            </button>
          </form>
        </div>
        @endif
        @endif

      </div>
      @endforeach
    </div>
    @endif
  </div>

</x-dashboard-layout>