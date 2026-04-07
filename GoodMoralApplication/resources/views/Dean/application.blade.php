<x-dashboard-layout>
  <x-slot name="roleTitle">Dean</x-slot>

  <x-slot name="navigation">
    @include('components.dean-navigation')
  </x-slot>

  <!-- Header Section -->
  <div class="header-section">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
      <div>
        <h1 class="role-title">Certificate Applications</h1>
        <p class="welcome-text">Review and approve Good Moral and Residency certificate applications</p>
        <div class="accent-line"></div>
      </div>
      <div style="display: flex; gap: 12px; align-items: center;">
        <div style="padding: 12px 16px; background: var(--light-green); border-radius: 8px; font-size: 14px; color: var(--primary-green); font-weight: 600;">
          {{ $applications['all_new']->count() }} Total Application{{ $applications['all_new']->count() !== 1 ? 's' : '' }}
        </div>
      </div>
    </div>
  </div>

  <!-- Main Content -->
  <div class="header-section">
    <!-- Status Messages -->
    @if(session('status'))
    <div style="margin-bottom: 24px; padding: 16px; background: #d4edda; color: #155724; border: 1px solid #c3e6cb; border-radius: 8px;">
      {{ session('status') }}
    </div>
    @endif

    @if(session('success'))
    <div style="margin-bottom: 24px; padding: 16px; background: #d4edda; color: #155724; border: 1px solid #c3e6cb; border-radius: 8px;">
      {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div style="margin-bottom: 24px; padding: 16px; background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; border-radius: 8px;">
      {{ session('error') }}
    </div>
    @endif

    <!-- Filter Tabs -->
    <div style="background: white; border-radius: 12px 12px 0 0; box-shadow: 0 2px 8px rgba(0,0,0,0.1); margin-bottom: 0;">
      <div style="display: flex; border-bottom: 1px solid #e9ecef;">
        <button onclick="showTab('all')" id="tab-all" class="tab-button active" style="flex: 1; padding: 16px; border: none; background: none; cursor: pointer; font-weight: 600; color: var(--primary-green); border-bottom: 3px solid var(--primary-green);">
          All Applications ({{ $applications['all_new']->count() }})
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
      @if($applications['all_new']->isEmpty())
      <div style="text-align: center; padding: 48px;">
        <svg style="width: 64px; height: 64px; margin: 0 auto 16px; color: #9ca3af;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
        </svg>
        <h3 style="margin: 0 0 8px; color: #374151; font-size: 1.25rem;">No Applications Found</h3>
        <p style="margin: 0; color: #6b7280;">There are currently no certificate applications to display.</p>
      </div>
      @else
    <div style="background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); overflow: hidden;">
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
            @foreach($applications['all_new'] as $application)
            <tr style="border-bottom: 1px solid #e9ecef; transition: background-color 0.2s ease;"
                onmouseover="this.style.backgroundColor='#f8f9fa'"
                onmouseout="this.style.backgroundColor='transparent'">
              <td style="padding: 16px; color: #495057; font-size: 14px; font-weight: 500;">{{ $application->student_id }}</td>
              <td style="padding: 16px; color: #495057; font-size: 14px; font-weight: 500;">{{ $application->fullname }}</td>
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
                @php
                  $statusColor = '#ffc107'; // Yellow for pending dean approval
                  $statusText = 'Pending Dean Approval';
                @endphp
                <span style="display: inline-block; padding: 6px 12px; background: {{ $statusColor }}20; color: {{ $statusColor }}; border-radius: 20px; font-size: 12px; font-weight: 500;">
                  {{ $statusText }}
                </span>
              </td>
              <td style="padding: 16px; color: #495057; font-size: 14px;">
                <div style="display: flex; gap: 8px; align-items: center; flex-wrap: wrap;">
                  <!-- Debug: Show application status -->
                  <small style="display: block; color: #666; margin-bottom: 4px; font-size: 10px; background: #f8f9fa; padding: 2px 6px; border-radius: 3px; width: 100%;">
                    Status: "{{ $application->application_status }}" | ID: {{ $application->id }}
                  </small>
                  
                  <!-- View Details Button -->
                  <button onclick="viewGoodMoralDetails({{ json_encode($application) }})"
                          style="background: #007bff; color: white; border: none; padding: 8px 12px; border-radius: 6px; font-size: 12px; font-weight: 500; cursor: pointer; display: flex; align-items: center; gap: 6px; transition: all 0.2s ease;"
                          onmouseover="this.style.background='#0056b3'"
                          onmouseout="this.style.background='#007bff'">
                    <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    View
                  </button>

                  @if(str_contains($application->application_status, 'Approved By Registrar'))
                    <!-- Approve Button -->
                    <form action="{{ route('dean.approveGoodMoral', $application->id) }}" method="POST" style="display: inline;">
                      @csrf
                      @method('PATCH')
                      <button type="submit"
                              style="background: #28a745; color: white; border: none; padding: 8px 12px; border-radius: 6px; font-size: 12px; font-weight: 500; cursor: pointer; display: flex; align-items: center; gap: 6px; transition: all 0.2s ease;"
                              onmouseover="this.style.background='#218838'"
                              onmouseout="this.style.background='#28a745'">
                        <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Approve
                      </button>
                    </form>

                    <!-- Reject Button (Direct Form) -->
                    <button type="button"
                            data-bs-toggle="modal" 
                            data-bs-target="#rejectFormModal{{ $application->id }}"
                            style="background: #dc3545; color: white; border: none; padding: 8px 12px; border-radius: 6px; font-size: 12px; font-weight: 500; cursor: pointer; display: flex; align-items: center; gap: 6px; transition: all 0.2s ease;"
                            onmouseover="this.style.background='#c82333'"
                            onmouseout="this.style.background='#dc3545'">
                      <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                      </svg>
                      Reject
                    </button>
                    
                    <!-- Reject Modal for this specific application -->
                    <div class="modal fade" id="rejectFormModal{{ $application->id }}" tabindex="-1" aria-labelledby="rejectFormModalLabel{{ $application->id }}" aria-hidden="true">
                      <div class="modal-dialog">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="rejectFormModalLabel{{ $application->id }}" style="color: #dc3545; font-weight: 600;">Reject Application</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                          </div>
                          <form action="{{ route('dean.reject', $application->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <div class="modal-body">
                              <div class="mb-3">
                                <label for="rejection_reason{{ $application->id }}" class="form-label" style="font-weight: 600; color: #333; margin-bottom: 8px;">Rejection Reason <span style="color: #dc3545;">*</span></label>
                                <select class="form-control" id="rejection_reason{{ $application->id }}" name="rejection_reason" required style="width: 100%; padding: 12px; border: 2px solid #e1e5e9; border-radius: 8px; font-size: 14px;">
                                  <option value="">Select a reason</option>
                                  <option value="Incomplete Documents">Incomplete Documents</option>
                                  <option value="Invalid Information">Invalid Information</option>
                                  <option value="Outstanding Violations">Outstanding Violations</option>
                                  <option value="Eligibility Requirements Not Met">Eligibility Requirements Not Met</option>
                                  <option value="Department Policy Violation">Department Policy Violation</option>
                                  <option value="Other">Other</option>
                                </select>
                              </div>
                              <div class="mb-3">
                                <label for="rejection_details{{ $application->id }}" class="form-label" style="font-weight: 600; color: #333; margin-bottom: 8px;">Additional Details</label>
                                <textarea class="form-control" id="rejection_details{{ $application->id }}" name="rejection_details" rows="4" placeholder="Please provide additional details about the rejection..." style="width: 100%; padding: 12px; border: 2px solid #e1e5e9; border-radius: 8px; font-size: 14px; resize: vertical;"></textarea>
                              </div>
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="background: #6c757d; color: white; border: none; padding: 10px 20px; border-radius: 6px; cursor: pointer;">Cancel</button>
                              <button type="submit" class="btn btn-danger" style="background: #dc3545; color: white; border: none; padding: 10px 20px; border-radius: 6px; cursor: pointer;">Reject Application</button>
                            </div>
                          </form>
                        </div>
                      </div>
                    </div>
                  @else
                    <span style="color: #6c757d; font-size: 12px; font-style: italic; padding: 8px 12px; background: #f8f9fa; border-radius: 6px;">
                      Already Processed
                    </span>
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
  </div>

  <!-- Good Moral Applications Tab -->
  <div id="content-good_moral" class="tab-content" style="display: none; background: white; border-radius: 0 0 12px 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); overflow: hidden;">
    @if($applications['good_moral']->isEmpty())
    <div style="text-align: center; padding: 48px;">
      <svg style="width: 64px; height: 64px; margin: 0 auto 16px; color: #9ca3af;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
      </svg>
      <h3 style="margin: 0 0 8px; color: #374151; font-size: 1.25rem;">No Good Moral Applications</h3>
      <p style="margin: 0; color: #6b7280;">There are currently no Good Moral certificate applications to display.</p>
    </div>
    @else
    <div style="background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); overflow: hidden;">
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
            <tr style="border-bottom: 1px solid #e9ecef; transition: background-color 0.2s ease;"
                onmouseover="this.style.backgroundColor='#f8f9fa'"
                onmouseout="this.style.backgroundColor='transparent'">
              <td style="padding: 16px; color: #495057; font-size: 14px; font-weight: 500;">{{ $application->student_id }}</td>
              <td style="padding: 16px; color: #495057; font-size: 14px; font-weight: 500;">{{ $application->fullname }}</td>
              <td style="padding: 16px; color: #495057; font-size: 14px;">
                <span style="display: inline-block; padding: 4px 8px; background: var(--light-green); color: var(--primary-green); border-radius: 4px; font-size: 12px; font-weight: 500;">
                  {{ $application->department }}
                </span>
              </td>
              <td style="padding: 16px; color: #495057; font-size: 14px;">
                <span style="display: inline-block; padding: 6px 12px; background: #ffc10720; color: #ffc107; border-radius: 20px; font-size: 12px; font-weight: 500;">
                  Pending Dean Approval
                </span>
              </td>
              <td style="padding: 16px; color: #495057; font-size: 14px;">
                <div style="display: flex; gap: 8px; align-items: center; flex-wrap: wrap;">
                  <!-- Debug: Show application status for Good Moral tab -->
                  <small style="display: block; color: #666; margin-bottom: 4px; font-size: 10px; background: #e3f2fd; padding: 2px 6px; border-radius: 3px; width: 100%;">
                    Good Moral Tab - Status: "{{ $application->application_status }}" | ID: {{ $application->id }}
                  </small>
                  
                  <!-- View Details Button -->
                  <button onclick="viewGoodMoralDetails({{ json_encode($application) }})"
                          style="background: #007bff; color: white; border: none; padding: 8px 12px; border-radius: 6px; font-size: 12px; font-weight: 500; cursor: pointer; display: flex; align-items: center; gap: 6px; transition: all 0.2s ease;"
                          onmouseover="this.style.background='#0056b3'"
                          onmouseout="this.style.background='#007bff'">
                    <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    View
                  </button>

                  @if(str_contains($application->application_status, 'Approved By Registrar'))
                    <!-- Approve Button -->
                    <form action="{{ route('dean.approveGoodMoral', $application->id) }}" method="POST" style="display: inline;">
                      @csrf
                      @method('PATCH')
                      <button type="submit"
                              style="background: #28a745; color: white; border: none; padding: 8px 12px; border-radius: 6px; font-size: 12px; font-weight: 500; cursor: pointer; display: flex; align-items: center; gap: 6px; transition: all 0.2s ease;"
                              onmouseover="this.style.background='#218838'"
                              onmouseout="this.style.background='#28a745'">
                        <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Approve
                      </button>
                    </form>

                    <!-- Reject Button (Direct Form) -->
                    <button type="button"
                            data-bs-toggle="modal" 
                            data-bs-target="#rejectFormModal{{ $application->id }}"
                            style="background: #dc3545; color: white; border: none; padding: 8px 12px; border-radius: 6px; font-size: 12px; font-weight: 500; cursor: pointer; display: flex; align-items: center; gap: 6px; transition: all 0.2s ease;"
                            onmouseover="this.style.background='#c82333'"
                            onmouseout="this.style.background='#dc3545'">
                      <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                      </svg>
                      Reject
                    </button>
                    
                    <!-- Reject Modal for this specific application -->
                    <div class="modal fade" id="rejectFormModal{{ $application->id }}" tabindex="-1" aria-labelledby="rejectFormModalLabel{{ $application->id }}" aria-hidden="true">
                      <div class="modal-dialog">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="rejectFormModalLabel{{ $application->id }}" style="color: #dc3545; font-weight: 600;">Reject Application</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                          </div>
                          <form action="{{ route('dean.reject', $application->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <div class="modal-body">
                              <div class="mb-3">
                                <label for="rejection_reason{{ $application->id }}" class="form-label" style="font-weight: 600; color: #333; margin-bottom: 8px;">Rejection Reason <span style="color: #dc3545;">*</span></label>
                                <select class="form-control" id="rejection_reason{{ $application->id }}" name="rejection_reason" required style="width: 100%; padding: 12px; border: 2px solid #e1e5e9; border-radius: 8px; font-size: 14px;">
                                  <option value="">Select a reason</option>
                                  <option value="Incomplete Documents">Incomplete Documents</option>
                                  <option value="Invalid Information">Invalid Information</option>
                                  <option value="Outstanding Violations">Outstanding Violations</option>
                                  <option value="Eligibility Requirements Not Met">Eligibility Requirements Not Met</option>
                                  <option value="Department Policy Violation">Department Policy Violation</option>
                                  <option value="Other">Other</option>
                                </select>
                              </div>
                              <div class="mb-3">
                                <label for="rejection_details{{ $application->id }}" class="form-label" style="font-weight: 600; color: #333; margin-bottom: 8px;">Additional Details</label>
                                <textarea class="form-control" id="rejection_details{{ $application->id }}" name="rejection_details" rows="4" placeholder="Please provide additional details about the rejection..." style="width: 100%; padding: 12px; border: 2px solid #e1e5e9; border-radius: 8px; font-size: 14px; resize: vertical;"></textarea>
                              </div>
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="background: #6c757d; color: white; border: none; padding: 10px 20px; border-radius: 6px; cursor: pointer;">Cancel</button>
                              <button type="submit" class="btn btn-danger" style="background: #dc3545; color: white; border: none; padding: 10px 20px; border-radius: 6px; cursor: pointer;">Reject Application</button>
                            </div>
                          </form>
                        </div>
                      </div>
                    </div>
                  @else
                    <span style="color: #6c757d; font-size: 12px; font-style: italic; padding: 8px 12px; background: #f8f9fa; border-radius: 6px;">
                      Already Processed
                    </span>
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
  </div>

  <!-- Residency Applications Tab -->
  <div id="content-residency" class="tab-content" style="display: none; background: white; border-radius: 0 0 12px 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); overflow: hidden;">
    @if($applications['residency']->isEmpty())
    <div style="text-align: center; padding: 48px;">
      <svg style="width: 64px; height: 64px; margin: 0 auto 16px; color: #9ca3af;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
      </svg>
      <h3 style="margin: 0 0 8px; color: #374151; font-size: 1.25rem;">No Residency Applications</h3>
      <p style="margin: 0; color: #6b7280;">There are currently no Residency certificate applications to display.</p>
    </div>
    @else
    <div style="background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); overflow: hidden;">
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
            <tr style="border-bottom: 1px solid #e9ecef; transition: background-color 0.2s ease;"
                onmouseover="this.style.backgroundColor='#f8f9fa'"
                onmouseout="this.style.backgroundColor='transparent'">
              <td style="padding: 16px; color: #495057; font-size: 14px; font-weight: 500;">{{ $application->student_id }}</td>
              <td style="padding: 16px; color: #495057; font-size: 14px; font-weight: 500;">{{ $application->fullname }}</td>
              <td style="padding: 16px; color: #495057; font-size: 14px;">
                <span style="display: inline-block; padding: 4px 8px; background: var(--light-green); color: var(--primary-green); border-radius: 4px; font-size: 12px; font-weight: 500;">
                  {{ $application->department }}
                </span>
              </td>
              <td style="padding: 16px; color: #495057; font-size: 14px;">
                <span style="display: inline-block; padding: 6px 12px; background: #ffc10720; color: #ffc107; border-radius: 20px; font-size: 12px; font-weight: 500;">
                  Pending Dean Approval
                </span>
              </td>
              <td style="padding: 16px; color: #495057; font-size: 14px;">
                <div style="display: flex; gap: 8px; align-items: center; flex-wrap: wrap;">
                  <!-- View Details Button -->
                  <button onclick="viewGoodMoralDetails({{ json_encode($application) }})"
                          style="background: #007bff; color: white; border: none; padding: 8px 12px; border-radius: 6px; font-size: 12px; font-weight: 500; cursor: pointer; display: flex; align-items: center; gap: 6px; transition: all 0.2s ease;"
                          onmouseover="this.style.background='#0056b3'"
                          onmouseout="this.style.background='#007bff'">
                    <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    View
                  </button>

                  @if(str_contains($application->application_status, 'Approved By Registrar'))
                    <!-- Approve Button -->
                    <form action="{{ route('dean.approveGoodMoral', $application->id) }}" method="POST" style="display: inline;">
                      @csrf
                      @method('PATCH')
                      <button type="submit"
                              style="background: #28a745; color: white; border: none; padding: 8px 12px; border-radius: 6px; font-size: 12px; font-weight: 500; cursor: pointer; display: flex; align-items: center; gap: 6px; transition: all 0.2s ease;"
                              onmouseover="this.style.background='#218838'"
                              onmouseout="this.style.background='#28a745'">
                        <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Approve
                      </button>
                    </form>

                    <!-- Reject Button (Direct Form) -->
                    <button type="button"
                            data-bs-toggle="modal" 
                            data-bs-target="#rejectFormModal{{ $application->id }}"
                            style="background: #dc3545; color: white; border: none; padding: 8px 12px; border-radius: 6px; font-size: 12px; font-weight: 500; cursor: pointer; display: flex; align-items: center; gap: 6px; transition: all 0.2s ease;"
                            onmouseover="this.style.background='#c82333'"
                            onmouseout="this.style.background='#dc3545'">
                      <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                      </svg>
                      Reject
                    </button>
                    
                    <!-- Reject Modal for this specific application -->
                    <div class="modal fade" id="rejectFormModal{{ $application->id }}" tabindex="-1" aria-labelledby="rejectFormModalLabel{{ $application->id }}" aria-hidden="true">
                      <div class="modal-dialog">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="rejectFormModalLabel{{ $application->id }}" style="color: #dc3545; font-weight: 600;">Reject Application</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                          </div>
                          <form action="{{ route('dean.reject', $application->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <div class="modal-body">
                              <div class="mb-3">
                                <label for="rejection_reason{{ $application->id }}" class="form-label" style="font-weight: 600; color: #333; margin-bottom: 8px;">Rejection Reason <span style="color: #dc3545;">*</span></label>
                                <select class="form-control" id="rejection_reason{{ $application->id }}" name="rejection_reason" required style="width: 100%; padding: 12px; border: 2px solid #e1e5e9; border-radius: 8px; font-size: 14px;">
                                  <option value="">Select a reason</option>
                                  <option value="Incomplete Documents">Incomplete Documents</option>
                                  <option value="Invalid Information">Invalid Information</option>
                                  <option value="Outstanding Violations">Outstanding Violations</option>
                                  <option value="Eligibility Requirements Not Met">Eligibility Requirements Not Met</option>
                                  <option value="Department Policy Violation">Department Policy Violation</option>
                                  <option value="Other">Other</option>
                                </select>
                              </div>
                              <div class="mb-3">
                                <label for="rejection_details{{ $application->id }}" class="form-label" style="font-weight: 600; color: #333; margin-bottom: 8px;">Additional Details</label>
                                <textarea class="form-control" id="rejection_details{{ $application->id }}" name="rejection_details" rows="4" placeholder="Please provide additional details about the rejection..." style="width: 100%; padding: 12px; border: 2px solid #e1e5e9; border-radius: 8px; font-size: 14px; resize: vertical;"></textarea>
                              </div>
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="background: #6c757d; color: white; border: none; padding: 10px 20px; border-radius: 6px; cursor: pointer;">Cancel</button>
                              <button type="submit" class="btn btn-danger" style="background: #dc3545; color: white; border: none; padding: 10px 20px; border-radius: 6px; cursor: pointer;">Reject Application</button>
                            </div>
                          </form>
                        </div>
                      </div>
                    </div>
                  @else
                    <span style="color: #6c757d; font-size: 12px; font-style: italic; padding: 8px 12px; background: #f8f9fa; border-radius: 6px;">
                      Already Processed
                    </span>
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
  </div>

  <!-- View Details Modal -->
  <div id="detailsModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
    <div style="background: white; padding: 24px; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); width: 100%; max-width: 600px; margin: 20px;">
      <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2 style="margin: 0; color: var(--primary-green); font-size: 1.25rem; font-weight: 600;">Application Details</h2>
        <button onclick="closeModal()" style="background: none; border: none; font-size: 24px; cursor: pointer; color: #6c757d;">&times;</button>
      </div>

      <div id="modalContent" style="display: grid; gap: 16px;">
        <!-- Content will be populated by JavaScript -->
      </div>

      <div style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 24px;">
        <button onclick="closeModal()" style="background: #6c757d; color: white; border: none; padding: 10px 20px; border-radius: 6px; cursor: pointer;">
          Close
        </button>
      </div>
    </div>
  </div>

  <!-- Approve Modal -->
  <div id="approveModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
    <div style="background: white; padding: 24px; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); width: 100%; max-width: 500px; margin: 20px;">
      <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2 style="margin: 0; color: #28a745; font-size: 1.25rem; font-weight: 600;">Approve Application</h2>
        <button onclick="closeApproveModal()" style="background: none; border: none; font-size: 24px; cursor: pointer; color: #6c757d;">&times;</button>
      </div>
      
      <div style="background: #d4edda; color: #155724; padding: 16px; border-radius: 8px; margin-bottom: 20px;">
        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
          <svg style="width: 24px; height: 24px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
          </svg>
          <span style="font-weight: 600;">Confirm Approval</span>
        </div>
        <p style="margin: 0 0 8px 0;">You are about to approve the following application:</p>
        <div style="margin-left: 10px;">
          <p style="margin: 4px 0;"><strong>Student Name:</strong> <span id="deanApproveStudentName"></span></p>
          <p style="margin: 4px 0;"><strong>Reference Number:</strong> <span id="deanApproveRefNumber"></span></p>
          <p style="margin: 4px 0;"><strong>Certificate Type:</strong> <span id="deanApproveCertType"></span></p>
        </div>
        <p style="margin: 8px 0 0 0; font-style: italic;">This action will move the application to the administrator for final approval.</p>
        <div id="approvalStatus" style="margin-top: 8px; display: none; padding: 8px; background: rgba(255,255,255,0.7); border-radius: 4px; text-align: center;">
          <span id="statusMessage">Processing your request...</span>
        </div>
      </div>

      <!-- Fixed form with proper action URL -->
      <form id="deanApproveForm" method="POST" action="" style="display: flex; justify-content: flex-end; gap: 12px;">
        @csrf
        @method('PATCH')
        <button type="button" onclick="closeApproveModal()"
                style="background: #6c757d; color: white; border: none; padding: 10px 20px; border-radius: 6px; cursor: pointer; font-size: 14px; font-weight: 500;">
          Cancel
        </button>
        <button type="submit"
                style="background: #28a745; color: white; border: none; padding: 10px 20px; border-radius: 6px; cursor: pointer; font-size: 14px; font-weight: 500; display: flex; align-items: center; gap: 8px;">
          <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
          </svg>
          Approve Application
        </button>
      </form>
    </div>
  </div>

  <!-- Custom CSS for loading animation -->
  <style>
    @keyframes spin {
      from { transform: rotate(0deg); }
      to { transform: rotate(360deg); }
    }
  </style>

  <!-- JavaScript for modal and tab functionality -->
  <script>
    // Tab switching functionality
    function showTab(tabName) {
      // Hide all tab contents
      const tabContents = document.querySelectorAll('.tab-content');
      tabContents.forEach(content => {
        content.style.display = 'none';
      });

      // Remove active class from all tab buttons
      const tabButtons = document.querySelectorAll('.tab-button');
      tabButtons.forEach(button => {
        button.style.color = '#6c757d';
        button.style.borderBottom = '3px solid transparent';
      });

      // Show selected tab content
      document.getElementById('content-' + tabName).style.display = 'block';

      // Add active class to selected tab button
      const activeButton = document.getElementById('tab-' + tabName);
      activeButton.style.color = 'var(--primary-green)';
      activeButton.style.borderBottom = '3px solid var(--primary-green)';
    }

    function viewDetails(application) {
      const modal = document.getElementById('detailsModal');
      const content = document.getElementById('modalContent');

      content.innerHTML = `
        <div style="display: grid; gap: 12px;">
          <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 8px; padding: 12px; background: #f8f9fa; border-radius: 6px;">
            <strong>Student ID:</strong>
            <span>${application.student.student_id}</span>
          </div>
          <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 8px; padding: 12px; background: #f8f9fa; border-radius: 6px;">
            <strong>Full Name:</strong>
            <span>${application.student.fullname}</span>
          </div>
          <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 8px; padding: 12px; background: #f8f9fa; border-radius: 6px;">
            <strong>Department:</strong>
            <span>${application.student.department}</span>
          </div>
          <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 8px; padding: 12px; background: #f8f9fa; border-radius: 6px;">
            <strong>Reference Number:</strong>
            <span>${application.reference_number || 'N/A'}</span>
          </div>
          <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 8px; padding: 12px; background: #f8f9fa; border-radius: 6px;">
            <strong>Number of Copies:</strong>
            <span>${application.number_of_copies}</span>
          </div>
          <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 8px; padding: 12px; background: #f8f9fa; border-radius: 6px;">
            <strong>Purpose/Reason:</strong>
            <span>${application.reason}</span>
          </div>
          <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 8px; padding: 12px; background: #f8f9fa; border-radius: 6px;">
            <strong>Status:</strong>
            <span style="color: ${application.status === 'pending' ? '#ffc107' : application.status === 'approved' ? '#28a745' : '#dc3545'}; font-weight: 600;">
              ${application.status.charAt(0).toUpperCase() + application.status.slice(1)}
            </span>
          </div>
          <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 8px; padding: 12px; background: #f8f9fa; border-radius: 6px;">
            <strong>Applied On:</strong>
            <span>${new Date(application.created_at).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' })}</span>
          </div>
          <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 8px; padding: 12px; background: #f8f9fa; border-radius: 6px;">
            <strong>Course Completed:</strong>
            <span>${application.course_completed || 'N/A'}</span>
          </div>
          <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 8px; padding: 12px; background: #f8f9fa; border-radius: 6px;">
            <strong>Graduation Date:</strong>
            <span>${application.graduation_date || 'N/A'}</span>
          </div>
        </div>
      `;

      modal.style.display = 'flex';
    }

    function viewGoodMoralDetails(application) {
      const modal = document.getElementById('detailsModal');
      const content = document.getElementById('modalContent');

      content.innerHTML = `
        <div style="display: grid; gap: 12px;">
          <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 8px; padding: 12px; background: #f8f9fa; border-radius: 6px;">
            <strong>Student ID:</strong>
            <span>${application.student_id}</span>
          </div>
          <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 8px; padding: 12px; background: #f8f9fa; border-radius: 6px;">
            <strong>Full Name:</strong>
            <span>${application.fullname}</span>
          </div>
          <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 8px; padding: 12px; background: #f8f9fa; border-radius: 6px;">
            <strong>Gender:</strong>
            <span style="text-transform: capitalize;">${application.gender || 'Not specified'}</span>
          </div>
          <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 8px; padding: 12px; background: #f8f9fa; border-radius: 6px;">
            <strong>Department:</strong>
            <span>${application.department}</span>
          </div>
          <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 8px; padding: 12px; background: #f8f9fa; border-radius: 6px;">
            <strong>Certificate Type:</strong>
            <span>${application.certificate_type === 'good_moral' ? 'Good Moral Certificate' : 'Certificate of Residency'}</span>
          </div>
          <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 8px; padding: 12px; background: #f8f9fa; border-radius: 6px;">
            <strong>Reference Number:</strong>
            <span>${application.reference_number || 'N/A'}</span>
          </div>
          <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 8px; padding: 12px; background: #f8f9fa; border-radius: 6px;">
            <strong>Number of Copies:</strong>
            <span>${application.number_of_copies}</span>
          </div>
          <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 8px; padding: 12px; background: #f8f9fa; border-radius: 6px;">
            <strong>Purpose/Reason:</strong>
            <span>${application.reason}</span>
          </div>
          <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 8px; padding: 12px; background: #f8f9fa; border-radius: 6px;">
            <strong>Application Status:</strong>
            <span style="color: #ffc107; font-weight: 600;">Pending Dean Approval</span>
          </div>
          <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 8px; padding: 12px; background: #f8f9fa; border-radius: 6px;">
            <strong>Applied On:</strong>
            <span>${new Date(application.created_at).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' })}</span>
          </div>
          <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 8px; padding: 12px; background: #f8f9fa; border-radius: 6px;">
            <strong>Course Completed:</strong>
            <span>${application.course_completed || 'N/A'}</span>
          </div>
          <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 8px; padding: 12px; background: #f8f9fa; border-radius: 6px;">
            <strong>Graduation Date:</strong>
            <span>${application.graduation_date || 'N/A'}</span>
          </div>
        </div>
      `;

      modal.style.display = 'flex';
    }

    function closeModal() {
      document.getElementById('detailsModal').style.display = 'none';
    }

    // Dean Approve Modal Functions
    function openApproveModal(applicationId, studentName, refNumber, certType) {
      const modal = document.getElementById('approveModal');
      const form = document.getElementById('deanApproveForm');
      const studentNameElement = document.getElementById('deanApproveStudentName');
      const refNumberElement = document.getElementById('deanApproveRefNumber');
      const certTypeElement = document.getElementById('deanApproveCertType');
      
      if (!modal || !form || !studentNameElement || !refNumberElement || !certTypeElement) {
        console.error('Modal elements not found');
        return;
      }
      
      // Set the proper URL path for the form action
      if (certType === 'Good Moral') {
        form.action = `/dean/good-moral/${applicationId}/approve`;
      } else {
        form.action = `/dean/good-moral/${applicationId}/approve`; // Same endpoint handles both types
      }
      
      console.log('Form action set to:', form.action);
      
      // Update modal information
      studentNameElement.textContent = studentName;
      refNumberElement.textContent = refNumber;
      certTypeElement.textContent = certType + ' Certificate';
      modal.style.display = 'flex';
    }

    function closeApproveModal() {
      document.getElementById('approveModal').style.display = 'none';
    }

    // Add form submission handler for basic feedback
    document.addEventListener('DOMContentLoaded', function() {
      const approveForm = document.getElementById('deanApproveForm');
      if (approveForm) {
        approveForm.addEventListener('submit', function(e) {
          // Show loading state
          const submitButton = approveForm.querySelector('button[type="submit"]');
          if (submitButton) {
            submitButton.disabled = true;
            submitButton.innerHTML = `
              <svg style="width: 16px; height: 16px; animation: spin 1s linear infinite;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
              </svg>
              Processing...
            `;
          }
          
          // Show status message
          const statusDiv = document.getElementById('approvalStatus');
          const statusMessage = document.getElementById('statusMessage');
          if (statusDiv && statusMessage) {
            statusDiv.style.display = 'block';
            statusMessage.textContent = 'Submitting approval...';
          }
        });
      }
    });

    // Close modal when clicking outside
    document.getElementById('detailsModal').addEventListener('click', function(e) {
      if (e.target === this) {
        closeModal();
      }
    });

    document.getElementById('approveModal').addEventListener('click', function(e) {
      if (e.target === this) {
        closeApproveModal();
      }
    });
  </script>
</x-dashboard-layout>