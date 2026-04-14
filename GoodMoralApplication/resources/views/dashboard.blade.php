@php
  $accountType = Auth::user()->account_type;
  $roleTitle = $accountType === 'alumni' ? 'Alumni' : 'Student';
@endphp

<x-dashboard-layout>
  <x-slot name="roleTitle">{{ $roleTitle }}</x-slot>

  <x-slot name="navigation">
    <x-student-navigation />
  </x-slot>

  <!-- Header Section -->
  <div class="header-section">
    <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 16px;">
      <div style="flex: 1; min-width: 250px;">
        <h1 class="responsive-title role-title">{{ $roleTitle }} Dashboard</h1>
        <p class="responsive-text welcome-text">Welcome back, {{ $fullname }}!</p>
        <div class="accent-line"></div>
      </div>
      <div style="display: flex; gap: 12px; align-items: center; flex-wrap: wrap;">
        <div style="padding: 12px 16px; background: var(--light-green); border-radius: 8px; font-size: 14px; color: var(--primary-green); font-weight: 600; white-space: nowrap;">
          {{ date('F j, Y') }}
        </div>
      </div>
    </div>
  </div>

  @if(session('status'))
  <div style="background: #d4edda; color: #155724; padding: 16px; border-radius: 8px; margin-bottom: 24px; border-left: 4px solid #28a745;">
    <strong>Success!</strong> {{ session('status') }}
  </div>
  @endif

  <!-- Status Overview -->
  <div class="responsive-container">
    <div class="stats-grid responsive-grid responsive-grid-3" style="width: 100%; margin: 0; gap: 16px; align-items: stretch;">
      <!-- Application Status -->
      <div class="stat-card responsive-card" style="border-top-color: var(--primary-green); flex: 1; height: 100%;">
        <div style="display: flex; align-items: center; gap: 16px; flex-wrap: wrap; justify-content: center;">
          <div style="height: 60px; width: 60px; border-radius: 50%; background: var(--primary-green); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="white" style="height: 30px; width: 30px;">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0.621 0 1.125-.504 1.125-1.125V9.375c0-.621.504-1.125 1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
            </svg>
          </div>
          <div style="flex: 1; min-width: 120px;">
            <div class="stat-number">
              @if (count($availableCertificates) > 0)
                Eligible
              @else
                Restricted
              @endif
            </div>
            <div class="stat-label">Application Status</div>
          </div>
        </div>
      </div>

      <!-- Violations Status -->
      <div class="stat-card responsive-card" style="border-top-color: {{ $Violation->isEmpty() ? '#28a745' : '#dc3545' }}; flex: 1; height: 100%;">
        <div style="display: flex; align-items: center; gap: 16px; flex-wrap: wrap; justify-content: center;">
          <div style="height: 60px; width: 60px; border-radius: 50%; background: {{ $Violation->isEmpty() ? '#28a745' : '#dc3545' }}; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="white" style="height: 30px; width: 30px;">
              @if($Violation->isEmpty())
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
              @else
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
              @endif
            </svg>
          </div>
          <div style="flex: 1; min-width: 120px;">
            <div class="stat-number">{{ $Violation->count() }}</div>
            <div class="stat-label">Active Violations</div>
          </div>
        </div>
      </div>

      <!-- Account Type -->
      <div class="stat-card responsive-card" style="border-top-color: var(--primary-yellow); flex: 1; height: 100%;">
        <div style="display: flex; align-items: center; gap: 16px; flex-wrap: wrap; justify-content: center;">
          <div style="height: 60px; width: 60px; border-radius: 50%; background: var(--primary-yellow); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="#333" style="height: 30px; width: 30px;">
              <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
            </svg>
          </div>
          <div style="flex: 1; min-width: 120px;">
            <div class="stat-number">{{ ucfirst($accountType) }}</div>
            <div class="stat-label">Account Type</div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Application Process Guide -->
  <div class="responsive-container">
    <div class="header-section">
      <h3 class="process-guide-title">Application Process Guide</h3>
      <div class="process-guide-grid">
        <div class="process-guide-card process-flow-card">
          <h4 class="process-guide-card-title">📋 Application Flow</h4>
          <ol class="process-flow-list">
            <li>Submit application</li>
            <li>Registrar review & approval</li>
            <li>Dean review & approval</li>
            <li>Administrator final approval</li>
            <li>Upload Receipt</li>
            <li>Certificate pickup at Office of Student Affairs</li>
          </ol>
        </div>

        <div class="process-guide-card processing-time-card">
          <h4 class="process-guide-card-title">⏱️ Processing Time</h4>
          <p class="process-guide-text">Applications typically take 3-5 business days to process. You will be notified when your certificate is ready for pickup.</p>
        </div>

        <div class="process-guide-card help-card">
          <h4 class="process-guide-card-title">📞 Need Help?</h4>
          <p class="process-guide-text">Contact the Office of Student Affairs for questions about your application status, requirements, or certificate pickup.</p>
        </div>
      </div>
    </div>
  </div>

  @include('certificates._application-form', ['violations' => $Violation, 'formAction' => route('apply.good_moral_certificate')])

</x-dashboard-layout>