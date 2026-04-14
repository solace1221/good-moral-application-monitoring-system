<x-dashboard-layout>
  <x-slot name="roleTitle">Admin</x-slot>

  <x-slot name="navigation">
    <x-admin-navigation />
  </x-slot>

  <!-- Add CSRF token for JavaScript -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <style>
    [x-cloak] { display: none !important; }
  </style>

  <!-- Header Section -->
  <div class="header-section">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
      <div>
        <h1 class="role-title">Add Violation</h1>
        <p class="welcome-text">Create and manage violation types in the system</p>
        <div class="accent-line"></div>
      </div>
    </div>
  </div>

  <!-- Main Content -->
  <div x-data="{
    showEditModal: false,
    selectedViolation: {},
    openEditModal(violation) {
      this.selectedViolation = { 
        ...violation, 
        article: violation.article || '' 
      };
      this.showEditModal = true;
    }
  }" class="header-section">
    @include('shared.alerts.flash')

    @if ($errors->any())
    <div style="margin-bottom: 24px; padding: 16px; background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; border-radius: 8px;">
      <ul style="margin: 0; padding-left: 20px;">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
    @endif

    <!-- Add Violation Form -->
    <div style="background: white; padding: 24px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); margin-bottom: 24px;">
      <h3 style="margin: 0 0 20px; color: var(--primary-green); font-size: 1.25rem; font-weight: 600;">Add New Violation</h3>
      
      <form method="POST" action="{{ route('admin.storeViolation') }}" style="display: grid; gap: 20px;">
        @csrf

        <div>
          <label for="offense_type" style="display: block; margin-bottom: 8px; font-weight: 600; color: #333;">Offense Type</label>
          <select id="offense_type" name="offense_type" required
                  style="width: 100%; padding: 12px 16px; border: 2px solid #e1e5e9; border-radius: 8px; font-size: 14px; background: white;">
            <option value="" disabled selected>Select Offense Type</option>
            <option value="major">Major</option>
            <option value="minor">Minor</option>
          </select>
          <x-input-error :messages="$errors->get('offense_type')" class="mt-2" />
        </div>

        <div>
          <label for="description" style="display: block; margin-bottom: 8px; font-weight: 600; color: #333;">Description</label>
          <input id="description" type="text" name="description" required
                 value="{{ old('description') }}"
                 placeholder="Enter violation description"
                 style="width: 100%; padding: 12px 16px; border: 2px solid #e1e5e9; border-radius: 8px; font-size: 14px;">
          <x-input-error :messages="$errors->get('description')" class="mt-2" />
        </div>

        <div>
          <label for="article" style="display: block; margin-bottom: 8px; font-weight: 600; color: #333;">Article Reference</label>
          <input id="article" type="text" name="article"
                 value="{{ old('article') }}"
                 placeholder="e.g., Article 1, Article 2.1 (optional)"
                 style="width: 100%; padding: 12px 16px; border: 2px solid #e1e5e9; border-radius: 8px; font-size: 14px;">
          <small style="color: #666; font-size: 12px; margin-top: 4px; display: block;">
            Optional: Reference to specific article in the Student Handbook
          </small>
          <x-input-error :messages="$errors->get('article')" class="mt-2" />
        </div>

        <button type="submit" class="btn-primary" style="justify-self: start;">
          <svg style="width: 16px; height: 16px; margin-right: 8px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14m7-7H5"/>
          </svg>
          Add Violation
        </button>
      </form>
    </div>

    <!-- Violations List -->
    <div style="background: white; padding: 24px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
      <h3 style="margin: 0 0 20px; color: var(--primary-green); font-size: 1.25rem; font-weight: 600;">Violation Types</h3>
      
      @if ($violations->isEmpty())
      <div style="text-align: center; padding: 48px; color: #6b7280;">
        <svg style="width: 64px; height: 64px; margin: 0 auto 16px; color: #9ca3af;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z"/>
        </svg>
        <p style="margin: 0; font-size: 1.1rem;">No violations found</p>
      </div>
      @else
      <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;">
          <thead>
            <tr style="background: #f8f9fa; border-bottom: 2px solid #e9ecef;">
              <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057;">Offense Type</th>
              <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057;">Description</th>
              <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057;">Article</th>
              <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057;">Status</th>
              <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057;">Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($violations as $violation)
            <tr style="border-bottom: 1px solid #e9ecef; {{ $violation->status === 'inactive' ? 'opacity: 0.6;' : '' }}">
              <td style="padding: 16px;">
                <span style="display: inline-block; padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 500; 
                             background: {{ $violation->offense_type == 'major' ? '#dc354520' : '#28a74520' }}; 
                             color: {{ $violation->offense_type == 'major' ? '#dc3545' : '#28a745' }};">
                  {{ ucfirst($violation->offense_type) }}
                </span>
              </td>
              <td style="padding: 16px; color: #495057;">{{ $violation->description }}</td>
              <td style="padding: 16px; color: #495057;">
                @if(isset($violation->article) && $violation->article)
                  <span style="background: #e3f2fd; color: #1976d2; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 500;">
                    {{ $violation->article }}
                  </span>
                @else
                  <span style="color: #999; font-style: italic;">No article</span>
                @endif
              </td>
              <td style="padding: 16px;">
                @if($violation->status === 'active')
                  <span style="display: inline-block; padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: 500; background: #d4edda; color: #155724;">Active</span>
                @else
                  <span style="display: inline-block; padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: 500; background: #f8d7da; color: #721c24;">Archived</span>
                @endif
              </td>
              <td style="padding: 16px;">
                <div style="display: flex; gap: 8px;">
                  <button @click="openEditModal({{ json_encode($violation) }})"
                          style="padding: 8px 16px; font-size: 14px; background: var(--primary-green); color: white; border: none; border-radius: 6px; cursor: pointer; display: flex; align-items: center; gap: 6px; transition: all 0.3s ease; box-shadow: 0 2px 8px rgba(40, 167, 69, 0.3);"
                          onmouseover="this.style.background='#0f5132'; this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 12px rgba(40, 167, 69, 0.4)'"
                          onmouseout="this.style.background='var(--primary-green)'; this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 8px rgba(40, 167, 69, 0.3)'">
                    <svg style="width: 14px; height: 14px; color: white;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10"/>
                    </svg>
                    Edit
                  </button>

                  @if($violation->status === 'active')
                    <button onclick="showArchiveConfirmation({{ $violation->id }}, '{{ addslashes($violation->description) }}')"
                            style="padding: 8px 16px; background: #ffc107; color: #212529; border: none; border-radius: 6px; font-size: 14px; cursor: pointer; display: flex; align-items: center; gap: 6px; transition: all 0.3s ease; box-shadow: 0 2px 8px rgba(255, 193, 7, 0.3);"
                            onmouseover="this.style.background='#e0a800'; this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 12px rgba(255, 193, 7, 0.4)'"
                            onmouseout="this.style.background='#ffc107'; this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 8px rgba(255, 193, 7, 0.3)'">
                      <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5m8.25 3v6.75m0 0-3-3m3 3 3-3M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z"/>
                      </svg>
                      Archive
                    </button>
                  @else
                    <form method="POST" action="{{ route('admin.restoreViolation', $violation->id) }}" style="display: inline;">
                      @csrf
                      @method('PATCH')
                      <button type="submit"
                              style="padding: 8px 16px; background: #17a2b8; color: white; border: none; border-radius: 6px; font-size: 14px; cursor: pointer; display: flex; align-items: center; gap: 6px; transition: all 0.3s ease; box-shadow: 0 2px 8px rgba(23, 162, 184, 0.3);"
                              onmouseover="this.style.background='#138496'; this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 12px rgba(23, 162, 184, 0.4)'"
                              onmouseout="this.style.background='#17a2b8'; this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 8px rgba(23, 162, 184, 0.3)'">
                        <svg style="width: 14px; height: 14px; color: white;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182"/>
                        </svg>
                        Restore
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

      <!-- Pagination -->
      @if($violationpage->hasPages())
      <div style="margin-top: 24px; display: flex; justify-content: center;">
        {{ $violationpage->links() }}
      </div>
      @endif
      @endif
    </div>

    <!-- Edit Modal -->
    <div x-show="showEditModal"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-90"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-90"
         style="position: fixed; inset: 0; background: rgba(0,0,0,0.5); display: flex; align-items: center; justify-content: center; z-index: 9999; padding: 20px; backdrop-filter: blur(4px);"
         x-cloak>
      <div @click.away="showEditModal = false"
           style="background: white; padding: 32px; border-radius: 16px; box-shadow: 0 25px 50px rgba(0,0,0,0.25); width: 100%; max-width: 500px; max-height: 90vh; overflow-y: auto; position: relative; margin: 0 auto; transform: none; left: auto;">

        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; border-bottom: 2px solid #e9ecef; padding-bottom: 16px;">
          <h2 style="margin: 0; color: var(--primary-green); font-size: 1.5rem; font-weight: 600;">Edit Violation</h2>
          <button @click="showEditModal = false" 
                  style="background: none; border: none; color: #6c757d; cursor: pointer; padding: 8px; border-radius: 6px; transition: all 0.3s ease; display: flex; align-items: center; justify-content: center;"
                  onmouseover="this.style.background='#f8f9fa'; this.style.color='#dc3545'"
                  onmouseout="this.style.background='none'; this.style.color='#6c757d'" 
                  title="Close">
            <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
          </button>
        </div>

        <form method="POST" :action="'/admin/violation/update/' + selectedViolation.id">
          @csrf
          @method('PATCH')

          <div style="margin-bottom: 20px;">
            <label for="edit_offense_type" style="display: block; margin-bottom: 8px; font-weight: 600; color: #333;">Offense Type</label>
            <select name="offense_type" id="edit_offense_type" x-model="selectedViolation.offense_type"
                    style="width: 100%; padding: 12px 16px; border: 2px solid #e1e5e9; border-radius: 8px; font-size: 14px; background: white;">
              <option value="major">Major</option>
              <option value="minor">Minor</option>
            </select>
          </div>

          <div style="margin-bottom: 20px;">
            <label for="edit_description" style="display: block; margin-bottom: 8px; font-weight: 600; color: #333;">Description</label>
            <input type="text" name="description" id="edit_description" x-model="selectedViolation.description"
                   style="width: 100%; padding: 12px 16px; border: 2px solid #e1e5e9; border-radius: 8px; font-size: 14px;"
                   required>
          </div>

          <div style="margin-bottom: 24px;">
            <label for="edit_article" style="display: block; margin-bottom: 8px; font-weight: 600; color: #333;">Article Reference</label>
            <input type="text" name="article" id="edit_article" x-model="selectedViolation.article"
                   style="width: 100%; padding: 12px 16px; border: 2px solid #e1e5e9; border-radius: 8px; font-size: 14px;"
                   placeholder="e.g., Article 1, Article 2.1 (optional)">
            <small style="color: #666; font-size: 12px; margin-top: 4px; display: block;">
              Optional: Reference to specific article in the Student Handbook
            </small>
          </div>

          <div style="display: flex; justify-content: flex-end;">
            <button type="submit" class="btn-primary">
              Save Changes
            </button>
          </div>
        </form>
      </div>
    </div>

    {{-- Archive Confirmation Modal --}}
    <x-shared.modals.confirm-action
        id="archiveConfirmModal"
        title="Archive Violation Type"
        title-color="#2d3748"
        close-fn="closeArchiveModal()"
        z-index="10000"
        max-width="500px">

      <div style="text-align: center;">
        {{-- Warning Icon --}}
        <div style="width: 64px; height: 64px; background: linear-gradient(135deg, #ffc107, #e0a800); border-radius: 50%; margin: 0 auto 20px; display: flex; align-items: center; justify-content: center; animation: pulse 2s infinite;">
          <svg style="width: 32px; height: 32px; color: white;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5m8.25 3v6.75m0 0-3-3m3 3 3-3M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z"/>
          </svg>
        </div>

        <p style="margin: 0 0 8px 0; color: #4a5568; font-size: 16px; line-height: 1.5;">
          Are you sure you want to archive this violation type:
        </p>

        <p id="archiveViolationDescription" style="margin: 0 0 16px 0; color: #2d3748; font-size: 18px; font-weight: 600; background: #f7fafc; padding: 12px; border-radius: 8px; border-left: 4px solid #ffc107;"></p>

        <div style="background: #fff3cd; padding: 16px; border-radius: 8px; border: 1px solid #ffeeba; margin-bottom: 4px; text-align: left;">
          <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px;">
            <svg style="width: 16px; height: 16px; color: #856404;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z"/>
            </svg>
            <strong style="color: #856404; font-size: 14px;">Note:</strong>
          </div>
          <p style="margin: 0; color: #856404; font-size: 14px; line-height: 1.4;">
            Archived violation types will no longer appear in dropdowns but will remain visible in existing records. You can restore them later.
          </p>
        </div>
      </div>

      <x-slot name="footer">
        <div style="display: flex; gap: 12px; margin-top: 20px;">
          <button onclick="closeArchiveModal()" style="flex: 1; padding: 12px 20px; background: #e2e8f0; color: #2d3748; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; transition: all 0.3s ease;">
            Cancel
          </button>
          <button id="confirmArchiveBtn" onclick="confirmArchive()" style="flex: 1; padding: 12px 20px; background: linear-gradient(135deg, #ffc107, #e0a800); color: #212529; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(255, 193, 7, 0.3);">
            Archive Violation
          </button>
        </div>
      </x-slot>

    </x-shared.modals.confirm-action>
  </div>

  <!-- Styles and JavaScript -->
  <style>
    @keyframes modalSlideIn {
      from {
        transform: translate(-50%, -60%);
        opacity: 0;
      }
      to {
        transform: translate(-50%, -50%);
        opacity: 1;
      }
    }
    
    @keyframes pulse {
      0%, 100% {
        transform: scale(1);
      }
      50% {
        transform: scale(1.05);
      }
    }

    @keyframes spin {
      from {
        transform: rotate(0deg);
      }
      to {
        transform: rotate(360deg);
      }
    }
    
    #confirmArchiveBtn:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 25px rgba(255, 193, 7, 0.4);
      background: linear-gradient(135deg, #e0a800, #ffc107);
    }
    
    button[onclick="closeArchiveModal()"]:hover {
      background: #cbd5e0;
      transform: translateY(-1px);
    }
  </style>

  <script>
    let pendingArchiveId = null;
    let pendingArchiveDescription = null;

    function showArchiveConfirmation(id, description) {
      pendingArchiveId = id;
      pendingArchiveDescription = description;
      
      document.getElementById('archiveViolationDescription').textContent = description;
      document.getElementById('archiveConfirmModal').style.display = 'flex';
      document.body.style.overflow = 'hidden';
    }

    function closeArchiveModal() {
      document.getElementById('archiveConfirmModal').style.display = 'none';
      document.body.style.overflow = 'auto';
      pendingArchiveId = null;
      pendingArchiveDescription = null;
    }

    function confirmArchive() {
      if (!pendingArchiveId) return;
      
      const confirmBtn = document.getElementById('confirmArchiveBtn');
      
      // Show loading state
      confirmBtn.disabled = true;
      confirmBtn.innerHTML = '<svg style="width: 16px; height: 16px; animation: spin 1s linear infinite; margin-right: 8px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>Archiving...';
      
      // Create and submit form
      const form = document.createElement('form');
      form.method = 'POST';
      form.action = `/admin/violation/${pendingArchiveId}/archive`;

      // Add CSRF token
      const csrfToken = document.createElement('input');
      csrfToken.type = 'hidden';
      csrfToken.name = '_token';
      csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
      form.appendChild(csrfToken);

      // Add PATCH method
      const methodField = document.createElement('input');
      methodField.type = 'hidden';
      methodField.name = '_method';
      methodField.value = 'PATCH';
      form.appendChild(methodField);

      document.body.appendChild(form);
      form.submit();
    }

    // Close modal when clicking outside or pressing Escape
    document.getElementById('archiveConfirmModal').addEventListener('click', function(e) {
      if (e.target === this) {
        closeArchiveModal();
      }
    });

    document.addEventListener('keydown', function(event) {
      if (event.key === 'Escape') {
        closeArchiveModal();
      }
    });
  </script>
</x-dashboard-layout>