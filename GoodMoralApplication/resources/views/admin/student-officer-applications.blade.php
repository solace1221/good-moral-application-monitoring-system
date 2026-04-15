<x-dashboard-layout>
  <x-slot name="roleTitle">Admin</x-slot>

  <x-slot name="navigation">
    <x-admin-navigation />
  </x-slot>

  {{-- Shared JS-driven confirmation modal (title/icon/message/buttons set at runtime) --}}
  @include('shared.modals.confirmation-modal')

  <!-- Header Section -->
  <div class="header-section">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
      <div>
        <h1 class="role-title">Student Officer Applications</h1>
        <p class="welcome-text">Manage student officer role applications</p>
        <div class="accent-line"></div>
      </div>
    </div>
  </div>

  <!-- Main Content -->
  <div class="header-section">
      <div class="bg-white shadow-sm sm:rounded-lg">
        <h3 class="text-lg font-semibold mb-4">Student Officer Role Applications</h3>

        @include('shared.alerts.flash')

        <!-- Tab Navigation -->
        <div class="mb-8">
          <nav style="display: flex; border-bottom: 2px solid #e5e7eb; margin-bottom: 20px;">
            <!-- Pending Tab -->
            <a href="{{ route('admin.studentOfficerApplications', ['status' => 'pending']) }}"
               class="tab-link {{ $status == 'pending' ? 'active' : '' }}"
               style="padding: 14px 24px; text-decoration: none; font-weight: 600; font-size: 14px; display: flex; align-items: center; gap: 8px; border-bottom: 3px solid {{ $status == 'pending' ? 'var(--primary-green)' : 'transparent' }}; color: {{ $status == 'pending' ? 'var(--primary-green)' : '#6c757d' }}; margin-bottom: -2px; transition: all 0.2s ease; background: {{ $status == 'pending' ? '#f0fdf4' : 'transparent' }}; border-radius: 6px 6px 0 0;">
              <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
              </svg>
              Pending
            </a>

            <!-- Approved Tab -->
            <a href="{{ route('admin.studentOfficerApplications', ['status' => 'approved']) }}"
               class="tab-link {{ $status == 'approved' ? 'active' : '' }}"
               style="padding: 14px 24px; text-decoration: none; font-weight: 600; font-size: 14px; display: flex; align-items: center; gap: 8px; border-bottom: 3px solid {{ $status == 'approved' ? 'var(--primary-green)' : 'transparent' }}; color: {{ $status == 'approved' ? 'var(--primary-green)' : '#6c757d' }}; margin-bottom: -2px; transition: all 0.2s ease; background: {{ $status == 'approved' ? '#f0fdf4' : 'transparent' }}; border-radius: 6px 6px 0 0;">
              <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
              </svg>
              Approved
            </a>

            <!-- Rejected Tab -->
            <a href="{{ route('admin.studentOfficerApplications', ['status' => 'rejected']) }}"
               class="tab-link {{ $status == 'rejected' ? 'active' : '' }}"
               style="padding: 14px 24px; text-decoration: none; font-weight: 600; font-size: 14px; display: flex; align-items: center; gap: 8px; border-bottom: 3px solid {{ $status == 'rejected' ? 'var(--primary-green)' : 'transparent' }}; color: {{ $status == 'rejected' ? 'var(--primary-green)' : '#6c757d' }}; margin-bottom: -2px; transition: all 0.2s ease; background: {{ $status == 'rejected' ? '#f0fdf4' : 'transparent' }}; border-radius: 6px 6px 0 0;">
              <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
              </svg>
              Rejected
            </a>

            <!-- Revoked Tab -->
            <a href="{{ route('admin.studentOfficerApplications', ['status' => 'revoked']) }}"
               class="tab-link {{ $status == 'revoked' ? 'active' : '' }}"
               style="padding: 14px 24px; text-decoration: none; font-weight: 600; font-size: 14px; display: flex; align-items: center; gap: 8px; border-bottom: 3px solid {{ $status == 'revoked' ? 'var(--primary-green)' : 'transparent' }}; color: {{ $status == 'revoked' ? 'var(--primary-green)' : '#6c757d' }}; margin-bottom: -2px; transition: all 0.2s ease; background: {{ $status == 'revoked' ? '#f0fdf4' : 'transparent' }}; border-radius: 6px 6px 0 0;">
              <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636" />
              </svg>
              Revoked
            </a>
          </nav>
        </div>


        @if($applications->isEmpty())
        <div style="text-align: center; padding: 60px 20px; background: #f8f9fa; border-radius: 12px;">
          <svg style="width: 64px; height: 64px; color: #dee2e6; margin: 0 auto 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
          </svg>
          <h3 style="color: #6b7280; font-size: 18px; margin-bottom: 8px;">No Applications Found</h3>
          <p style="color: #9ca3af; font-size: 14px;">There are no student officer applications in this category.</p>
        </div>
        @else
        <div style="background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);">
          <table style="width: 100%; border-collapse: collapse;">
            <thead>
              <tr style="background: white; color: black; border-bottom: 2px solid #e5e7eb;">
                <th style="padding: 16px; text-align: left; font-weight: 600; font-size: 14px; color: black;">Student ID</th>
                <th style="padding: 16px; text-align: left; font-weight: 600; font-size: 14px; color: black;">Full Name</th>
                <th style="padding: 16px; text-align: left; font-weight: 600; font-size: 14px; color: black;">Department</th>
                <th style="padding: 16px; text-align: left; font-weight: 600; font-size: 14px; color: black;">Organization</th>
                <th style="padding: 16px; text-align: left; font-weight: 600; font-size: 14px; color: black;">Position</th>
                <th style="padding: 16px; text-align: left; font-weight: 600; font-size: 14px; color: black;">Status</th>
                <th style="padding: 16px; text-align: left; font-weight: 600; font-size: 14px; color: black;">Applied On</th>
                <th style="padding: 16px; text-align: center; font-weight: 600; font-size: 14px; color: black;">Actions</th>
              </tr>
            </thead>
            <tbody>
              @foreach($applications as $application)
              <tr style="border-bottom: 1px solid #f3f4f6; transition: background-color 0.2s ease;" onmouseover="this.style.backgroundColor='#f9fafb'" onmouseout="this.style.backgroundColor='transparent'">
                <td style="padding: 20px 16px; color: #374151; font-weight: 500;">{{ $application->student->student_id ?? 'N/A' }}</td>
                <td style="padding: 20px 16px; color: #374151; font-weight: 500;">{{ $application->student->fullname ?? 'N/A' }}</td>
                <td style="padding: 20px 16px; color: #6b7280;">
                  @php
                    $dept = $application->student->department ?? '';
                    $deptColor = '#f5f5f5';
                    $deptTextColor = '#424242';
                    switch($dept) {
                      case 'SASTE': $deptColor = '#e3f2fd'; $deptTextColor = '#1976d2'; break;
                      case 'SBAHM': $deptColor = '#e8f5e8'; $deptTextColor = '#2e7d32'; break;
                      case 'SITE': $deptColor = '#f3e5f5'; $deptTextColor = '#7b1fa2'; break;
                      case 'SNAHS': $deptColor = '#ffebee'; $deptTextColor = '#c62828'; break;
                    }
                  @endphp
                  <span style="display: inline-block; padding: 4px 12px; background: {{ $deptColor }}; color: {{ $deptTextColor }}; border-radius: 20px; font-size: 12px; font-weight: 600; text-transform: uppercase;">
                    {{ $dept ?: 'N/A' }}
                  </span>
                </td>
                <td style="padding: 16px; color: #6b7280;">{{ $application->organization->description ?? 'N/A' }}</td>
                <td style="padding: 16px; color: #6b7280;">{{ $application->position->position_title ?? 'N/A' }}</td>
                <td style="padding: 16px;">
                  @if($application->status == 'pending')
                    <span style="display: inline-flex; align-items: center; padding: 6px 12px; background: #fbbf24; color: #ffffff; border-radius: 20px; font-size: 12px; font-weight: 600;">
                      <svg style="width: 14px; height: 14px; margin-right: 4px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                      </svg>
                      Pending
                    </span>
                  @elseif($application->status == 'approved')
                    <span style="display: inline-flex; align-items: center; padding: 6px 12px; background: #10b981; color: #ffffff; border-radius: 20px; font-size: 12px; font-weight: 600;">
                      <svg style="width: 14px; height: 14px; margin-right: 4px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                      </svg>
                      Approved
                    </span>
                  @elseif($application->status == 'revoked')
                    <span style="display: inline-flex; align-items: center; padding: 6px 12px; background: #f59e0b; color: #ffffff; border-radius: 20px; font-size: 12px; font-weight: 600;">
                      <svg style="width: 14px; height: 14px; margin-right: 4px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636" />
                      </svg>
                      Revoked
                    </span>
                  @else
                    <span style="display: inline-flex; align-items: center; padding: 6px 12px; background: #ef4444; color: #ffffff; border-radius: 20px; font-size: 12px; font-weight: 600;">
                      <svg style="width: 14px; height: 14px; margin-right: 4px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                      </svg>
                      Rejected
                    </span>
                  @endif
                </td>
                <td style="padding: 16px; color: #6b7280; font-weight: 500;">{{ $application->created_at->format('Y-m-d') }}</td>
                <td style="padding: 16px; text-align: center;">
                  <div style="display: flex; gap: 8px; justify-content: center; flex-wrap: wrap;">
                    @if($application->status == 'pending')
                      <!-- Approve Button -->
                      <form action="{{ route('admin.approveOfficer', $application->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('PATCH')
                        <button type="submit" style="padding: 8px 16px; background: #10b981; color: #ffffff; border: none; border-radius: 8px; font-size: 12px; font-weight: 600; cursor: pointer; transition: all 0.3s ease; display: flex; align-items: center; gap: 6px; box-shadow: 0 2px 4px rgba(16, 185, 129, 0.2);"
                                onmouseover="this.style.background='#059669'; this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 8px rgba(16, 185, 129, 0.3)'"
                                onmouseout="this.style.background='#10b981'; this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 4px rgba(16, 185, 129, 0.2)'">
                          <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                          </svg>
                          Approve
                        </button>
                      </form>

                      <!-- Reject Button -->
                      <form action="{{ route('admin.rejectOfficer', $application->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" style="padding: 8px 16px; background: #ef4444; color: #ffffff; border: none; border-radius: 8px; font-size: 12px; font-weight: 600; cursor: pointer; transition: all 0.3s ease; display: flex; align-items: center; gap: 6px; box-shadow: 0 2px 4px rgba(239, 68, 68, 0.2);"
                                onmouseover="this.style.background='#dc2626'; this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 8px rgba(239, 68, 68, 0.3)'"
                                onmouseout="this.style.background='#ef4444'; this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 4px rgba(239, 68, 68, 0.2)'">
                          <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                          </svg>
                          Reject
                        </button>
                      </form>

                    @elseif($application->status == 'approved')
                      <!-- Sync Role Button (re-applies officer role if out of sync) -->
                      <form action="{{ route('admin.approveOfficer', $application->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('PATCH')
                        <button type="submit" style="padding: 8px 16px; background: #3b82f6; color: #ffffff; border: none; border-radius: 8px; font-size: 12px; font-weight: 600; cursor: pointer; transition: all 0.3s ease; display: flex; align-items: center; gap: 6px; box-shadow: 0 2px 4px rgba(59, 130, 246, 0.2);"
                                onmouseover="this.style.background='#2563eb'; this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 8px rgba(59, 130, 246, 0.3)'"
                                onmouseout="this.style.background='#3b82f6'; this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 4px rgba(59, 130, 246, 0.2)'">
                          <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182" />
                          </svg>
                          Sync Role
                        </button>
                      </form>

                      <!-- Revoke Button with Custom Modal -->
                      <button onclick="showRevokeConfirmation('{{ $application->id }}', '{{ $application->student->fullname ?? 'N/A' }}')"
                              style="padding: 8px 16px; background: #f59e0b; color: #ffffff; border: none; border-radius: 8px; font-size: 12px; font-weight: 600; cursor: pointer; transition: all 0.3s ease; display: flex; align-items: center; gap: 6px; box-shadow: 0 2px 4px rgba(245, 158, 11, 0.2);"
                              onmouseover="this.style.background='#d97706'; this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 8px rgba(245, 158, 11, 0.3)'"
                              onmouseout="this.style.background='#f59e0b'; this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 4px rgba(245, 158, 11, 0.2)'">
                        <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728" />
                        </svg>
                        Revoke
                      </button>

                    @elseif($application->status == 'rejected')
                      <!-- Reconsider Button -->
                      <button onclick="showReconsiderConfirmation('{{ $application->id }}', '{{ $application->student->fullname ?? 'N/A' }}')"
                              style="padding: 8px 16px; background: #8b5cf6; color: #ffffff; border: none; border-radius: 8px; font-size: 12px; font-weight: 600; cursor: pointer; transition: all 0.3s ease; display: flex; align-items: center; gap: 6px; box-shadow: 0 2px 4px rgba(139, 92, 246, 0.2);"
                              onmouseover="this.style.background='#7c3aed'; this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 8px rgba(139, 92, 246, 0.3)'"
                              onmouseout="this.style.background='#8b5cf6'; this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 4px rgba(139, 92, 246, 0.2)'">
                        <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Reconsider
                      </button>
                    @elseif($application->status == 'revoked')
                      <span style="color: #f59e0b; font-style: italic; font-size: 12px; font-weight: 600;">Officer privileges revoked</span>
                    @else
                      <span style="color: #9ca3af; font-style: italic; font-size: 12px;">No action available</span>
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
  </div>

  <!-- JavaScript for Modal Functionality -->
  <script>
    let currentAction = null;
    let currentApplicationId = null;

    function showConfirmationModal(title, message, confirmText, confirmColor, iconColor, iconSvg, action, applicationId) {
      document.getElementById('modalTitle').textContent = title;
      document.getElementById('modalMessage').textContent = message;
      
      const confirmBtn = document.getElementById('modalConfirm');
      confirmBtn.textContent = confirmText;
      confirmBtn.style.background = confirmColor;
      
      const iconDiv = document.getElementById('modalIcon');
      iconDiv.style.background = iconColor + '20';
      iconDiv.innerHTML = `<svg style="width: 40px; height: 40px; color: ${iconColor};" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">${iconSvg}</svg>`;
      
      currentAction = action;
      currentApplicationId = applicationId;
      
      const modal = document.getElementById('confirmationModal');
      modal.style.display = 'flex';
      setTimeout(() => {
        modal.querySelector('div').style.transform = 'scale(1)';
      }, 10);
    }

    function closeConfirmationModal() {
      const modal = document.getElementById('confirmationModal');
      modal.querySelector('div').style.transform = 'scale(0.9)';
      setTimeout(() => {
        modal.style.display = 'none';
        currentAction = null;
        currentApplicationId = null;
      }, 300);
    }

    function confirmAction() {
      if (currentAction && currentApplicationId) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = currentAction.replace('__ID__', currentApplicationId);
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);
        
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'PATCH';
        form.appendChild(methodField);
        
        document.body.appendChild(form);
        form.submit();
      }
      
      closeConfirmationModal();
    }

    function showRevokeConfirmation(applicationId, fullname) {
      showConfirmationModal(
        'Revoke Student Officer Status',
        `Are you sure you want to revoke student officer privileges for ${fullname}? This will remove their officer role and restore their student account.`,
        'Revoke Access',
        '#f59e0b',
        '#f59e0b',
        '<path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728" />',
        '{{ route("admin.revokeOfficer", "__ID__") }}',
        applicationId
      );
    }

    function showReconsiderConfirmation(applicationId, fullname) {
      showConfirmationModal(
        'Reconsider Application',
        `Are you sure you want to reconsider the student officer application for ${fullname}? This will move their application back to pending status for review.`,
        'Reconsider',
        '#8b5cf6',
        '#8b5cf6',
        '<path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />',
        '{{ route("admin.reconsiderOfficer", "__ID__") }}',
        applicationId
      );
    }

    document.getElementById('confirmationModal').addEventListener('click', function(e) {
      if (e.target === this) {
        closeConfirmationModal();
      }
    });

    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape') {
        closeConfirmationModal();
      }
    });
  </script>
</x-dashboard-layout>
