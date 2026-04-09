<x-dashboard-layout>
  <x-slot name="roleTitle">Admin</x-slot>

  <x-slot name="navigation">
    <x-admin-navigation />
  </x-slot>

  <!-- Custom Modal for Confirmations -->
  <div id="confirmationModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); z-index: 1000; align-items: center; justify-content: center;">
    <div style="background: white; border-radius: 16px; padding: 32px; max-width: 500px; margin: 20px; box-shadow: 0 20px 50px rgba(0, 0, 0, 0.3); transform: scale(0.9); transition: transform 0.3s ease;">
      <!-- Modal Header -->
      <div style="text-align: center; margin-bottom: 24px;">
        <div id="modalIcon" style="width: 80px; height: 80px; margin: 0 auto 16px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
          <!-- Icon will be inserted here -->
        </div>
        <h3 id="modalTitle" style="margin: 0; font-size: 24px; font-weight: 700; color: #1f2937;">Confirm Action</h3>
      </div>

      <!-- Modal Body -->
      <div style="text-align: center; margin-bottom: 32px;">
        <p id="modalMessage" style="margin: 0; font-size: 16px; color: #6b7280; line-height: 1.6;">Are you sure you want to proceed?</p>
      </div>

      <!-- Modal Footer -->
      <div style="display: flex; gap: 12px; justify-content: center;">
        <button id="modalCancel" onclick="closeConfirmationModal()" style="padding: 12px 24px; background: #f3f4f6; color: #374151; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; transition: all 0.3s ease; min-width: 100px;"
                onmouseover="this.style.background='#e5e7eb'" onmouseout="this.style.background='#f3f4f6'">
          Cancel
        </button>
        <button id="modalConfirm" onclick="confirmAction()" style="padding: 12px 24px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; transition: all 0.3s ease; min-width: 100px; color: white;">
          Confirm
        </button>
      </div>
    </div>
  </div>

  <!-- Header Section -->
  <div class="header-section">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
      <div>
        <h1 class="role-title">PSG Applications</h1>
        <p class="welcome-text">Manage PSG officer account applications</p>
        <div class="accent-line"></div>
      </div>
    </div>
  </div>

  <!-- Main Content -->
  <div class="header-section">
      <div class="bg-white shadow-sm sm:rounded-lg">
        <h3 class="text-lg font-semibold mb-4">PSG Account Applications</h3>

        @if(session('status'))
        <div style="background: #d4edda; color: #155724; padding: 16px; border-radius: 8px; border-left: 4px solid #28a745; margin-bottom: 24px; display: flex; align-items: center; gap: 12px;">
          <svg style="width: 20px; height: 20px; flex-shrink: 0;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
          </svg>
          <span style="font-weight: 500;">{{ session('status') }}</span>
        </div>
        @endif

        <!-- Enhanced Navigation Bar -->
        <div class="mb-8">
          <nav class="flex space-x-4 items-center" style="background: #f8f9fa; padding: 8px; border-radius: 12px; display: inline-flex;">
            <!-- Pending Button -->
            <a href="{{ route('admin.psgApplication', ['status' => 'pending']) }}" 
               style="padding: 12px 20px; border-radius: 8px; font-weight: 600; text-decoration: none; transition: all 0.3s ease; display: flex; align-items: center; gap: 8px; {{ request()->get('status') == 'pending' ? 'background: #3b82f6; color: white; box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);' : 'background: white; color: #3b82f6; border: 2px solid #3b82f6;' }}"
               onmouseover="if (!'{{ request()->get('status') == 'pending' }}') { this.style.background='#3b82f6'; this.style.color='white'; }"
               onmouseout="if (!'{{ request()->get('status') == 'pending' }}') { this.style.background='white'; this.style.color='#3b82f6'; }">
              <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
              </svg>
              Pending
            </a>

            <!-- Approved Button -->
            <a href="{{ route('admin.psgApplication', ['status' => 'approved']) }}" 
               style="padding: 12px 20px; border-radius: 8px; font-weight: 600; text-decoration: none; transition: all 0.3s ease; display: flex; align-items: center; gap: 8px; {{ request()->get('status') == 'approved' ? 'background: #10b981; color: white; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);' : 'background: white; color: #10b981; border: 2px solid #10b981;' }}"
               onmouseover="if (!'{{ request()->get('status') == 'approved' }}') { this.style.background='#10b981'; this.style.color='white'; }"
               onmouseout="if (!'{{ request()->get('status') == 'approved' }}') { this.style.background='white'; this.style.color='#10b981'; }">
              <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
              </svg>
              Approved
            </a>

            <!-- Rejected Button -->
            <a href="{{ route('admin.psgApplication', ['status' => 'rejected']) }}" 
               style="padding: 12px 20px; border-radius: 8px; font-weight: 600; text-decoration: none; transition: all 0.3s ease; display: flex; align-items: center; gap: 8px; {{ request()->get('status') == 'rejected' ? 'background: #ef4444; color: white; box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);' : 'background: white; color: #ef4444; border: 2px solid #ef4444;' }}"
               onmouseover="if (!'{{ request()->get('status') == 'rejected' }}') { this.style.background='#ef4444'; this.style.color='white'; }"
               onmouseout="if (!'{{ request()->get('status') == 'rejected' }}') { this.style.background='white'; this.style.color='#ef4444'; }">
              <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
              </svg>
              Rejected
            </a>
          </nav>
        </div>


        @if($applications->isEmpty())
        <div style="text-align: center; padding: 60px 20px; background: #f8f9fa; border-radius: 12px;">
          <svg style="width: 64px; height: 64px; color: #dee2e6; margin: 0 auto 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
          </svg>
          <h3 style="color: #6b7280; font-size: 18px; margin-bottom: 8px;">No Applications Found</h3>
          <p style="color: #9ca3af; font-size: 14px;">There are no PSG applications in this category.</p>
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
                <td style="padding: 20px 16px; color: #374151; font-weight: 500;">{{ $application->student_id }}</td>
                <td style="padding: 20px 16px; color: #374151; font-weight: 500;">{{ $application->fullname }}</td>
                <td style="padding: 20px 16px; color: #6b7280;">
                  @php
                    $deptColor = '#e3f2fd'; // default light blue
                    $deptTextColor = '#1976d2'; // default text color
                    switch($application->department) {
                      case 'SASTE': $deptColor = '#e3f2fd'; $deptTextColor = '#1976d2'; break; // Light blue
                      case 'SBAHM': $deptColor = '#e8f5e8'; $deptTextColor = '#2e7d32'; break; // Light green
                      case 'SITE': $deptColor = '#f3e5f5'; $deptTextColor = '#7b1fa2'; break; // Light purple
                      case 'SNAHS': $deptColor = '#ffebee'; $deptTextColor = '#c62828'; break; // Light red
                      default: $deptColor = '#f5f5f5'; $deptTextColor = '#424242'; break; // Light gray for others
                    }
                  @endphp
                  <span style="display: inline-block; padding: 4px 12px; background: {{ $deptColor }}; color: {{ $deptTextColor }}; border-radius: 20px; font-size: 12px; font-weight: 600; text-transform: uppercase;">
                    {{ $application->department }}
                  </span>
                </td>
                <td style="padding: 16px; color: #6b7280;">{{ $application->organization ?? 'N/A' }}</td>
                <td style="padding: 16px; color: #6b7280;">{{ $application->position ?? 'N/A' }}</td>
                <td style="padding: 16px;">
                  @if($application->status == '5')
                    <span style="display: inline-flex; align-items: center; padding: 6px 12px; background: #fbbf24; color: #ffffff; border-radius: 20px; font-size: 12px; font-weight: 600;">
                      <svg style="width: 14px; height: 14px; margin-right: 4px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                      </svg>
                      Pending
                    </span>
                  @elseif($application->status == '1')
                    <span style="display: inline-flex; align-items: center; padding: 6px 12px; background: #10b981; color: #ffffff; border-radius: 20px; font-size: 12px; font-weight: 600;">
                      <svg style="width: 14px; height: 14px; margin-right: 4px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                      </svg>
                      Approved
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
                    @if($application->status == '5')
                      <!-- Approve Button -->
                      <form action="{{ route('admin.approvepsg', $application->student_id) }}" method="POST" style="display:inline;">
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
                      <form action="{{ route('admin.rejectpsg', $application->student_id) }}" method="POST" style="display:inline;">
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

                    @elseif($application->status == '1')
                      <!-- Revoke Button with Custom Modal -->
                      <button onclick="showRevokeConfirmation('{{ $application->student_id }}', '{{ $application->fullname }}')"
                              style="padding: 8px 16px; background: #f59e0b; color: #ffffff; border: none; border-radius: 8px; font-size: 12px; font-weight: 600; cursor: pointer; transition: all 0.3s ease; display: flex; align-items: center; gap: 6px; box-shadow: 0 2px 4px rgba(245, 158, 11, 0.2);"
                              onmouseover="this.style.background='#d97706'; this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 8px rgba(245, 158, 11, 0.3)'"
                              onmouseout="this.style.background='#f59e0b'; this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 4px rgba(245, 158, 11, 0.2)'">
                        <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728" />
                        </svg>
                        Revoke
                      </button>

                    @elseif($application->status == '3')
                      <!-- Reconsider Button -->
                      <button onclick="showReconsiderConfirmation('{{ $application->student_id }}', '{{ $application->fullname }}')"
                              style="padding: 8px 16px; background: #8b5cf6; color: #ffffff; border: none; border-radius: 8px; font-size: 12px; font-weight: 600; cursor: pointer; transition: all 0.3s ease; display: flex; align-items: center; gap: 6px; box-shadow: 0 2px 4px rgba(139, 92, 246, 0.2);"
                              onmouseover="this.style.background='#7c3aed'; this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 8px rgba(139, 92, 246, 0.3)'"
                              onmouseout="this.style.background='#8b5cf6'; this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 4px rgba(139, 92, 246, 0.2)'">
                        <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Reconsider
                      </button>
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
    let currentStudentId = null;

    function showConfirmationModal(title, message, confirmText, confirmColor, iconColor, iconSvg, action, studentId) {
      // Set modal content
      document.getElementById('modalTitle').textContent = title;
      document.getElementById('modalMessage').textContent = message;
      
      // Set confirm button color and text
      const confirmBtn = document.getElementById('modalConfirm');
      confirmBtn.textContent = confirmText;
      confirmBtn.style.background = confirmColor;
      
      // Set icon
      const iconDiv = document.getElementById('modalIcon');
      iconDiv.style.background = iconColor + '20'; // 20% opacity
      iconDiv.innerHTML = `<svg style="width: 40px; height: 40px; color: ${iconColor};" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">${iconSvg}</svg>`;
      
      // Store action details
      currentAction = action;
      currentStudentId = studentId;
      
      // Show modal with animation
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
        currentStudentId = null;
      }, 300);
    }

    function confirmAction() {
      if (currentAction && currentStudentId) {
        // Create and submit form
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = currentAction.replace('STUDENT_ID', currentStudentId);
        
        // Add CSRF token
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);
        
        // Add method override if needed
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

    function showRevokeConfirmation(studentId, fullname) {
      showConfirmationModal(
        'Revoke PSG Officer Status',
        `Are you sure you want to revoke PSG officer privileges for ${fullname}? This action will remove their officer status and they will no longer have PSG officer access.`,
        'Revoke Access',
        '#f59e0b',
        '#f59e0b',
        '<path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728" />',
        '{{ route("admin.revokepsg", "STUDENT_ID") }}',
        studentId
      );
    }

    function showReconsiderConfirmation(studentId, fullname) {
      showConfirmationModal(
        'Reconsider Application',
        `Are you sure you want to reconsider the PSG officer application for ${fullname}? This will move their application back to pending status for review.`,
        'Reconsider',
        '#8b5cf6',
        '#8b5cf6',
        '<path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />',
        '{{ route("admin.reconsiderpsg", "STUDENT_ID") }}',
        studentId
      );
    }

    // Close modal when clicking outside
    document.getElementById('confirmationModal').addEventListener('click', function(e) {
      if (e.target === this) {
        closeConfirmationModal();
      }
    });

    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape') {
        closeConfirmationModal();
      }
    });
  </script>
</x-dashboard-layout>