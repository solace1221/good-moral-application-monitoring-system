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
    <!-- Status Messages -->
    @if(session('status'))
    <div style="margin-bottom: 24px; padding: 16px; background: #d4edda; color: #155724; border: 1px solid #c3e6cb; border-radius: 8px;">
      {{ session('status') }}
    </div>
    @endif

    @if (session('success'))
    <div style="margin-bottom: 24px; padding: 16px; background: #d4edda; color: #155724; border: 1px solid #c3e6cb; border-radius: 8px;">
      {{ session('success') }}
    </div>
    @endif

    <!-- Add Violation Form -->
    <div style="background: white; padding: 24px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); margin-bottom: 24px;">
      <h3 style="margin: 0 0 20px; color: var(--primary-green); font-size: 1.25rem; font-weight: 600;">Add New Violation</h3>
      
      <form method="POST" action="{{ route('RegisterViolation') }}" style="display: grid; gap: 20px;">
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
              <th style="padding: 16px; text-align: left; font-weight: 600; color: #495057;">Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($violations as $violation)
            <tr style="border-bottom: 1px solid #e9ecef;">
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

                  <button onclick="showDeleteConfirmation({{ $violation->id }}, '{{ addslashes($violation->description) }}')"
                            style="padding: 8px 16px; background: #dc3545; color: white; border: none; border-radius: 6px; font-size: 14px; cursor: pointer; display: flex; align-items: center; gap: 6px; transition: all 0.3s ease; box-shadow: 0 2px 8px rgba(220, 53, 69, 0.3);"
                            onmouseover="this.style.background='#c82333'; this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 12px rgba(220, 53, 69, 0.4)'"
                            onmouseout="this.style.background='#dc3545'; this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 8px rgba(220, 53, 69, 0.3)'">
                      <svg style="width: 14px; height: 14px; color: white;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/>
                      </svg>
                      Delete
                    </button>
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

    <!-- Delete Confirmation Modal -->
    <div id="deleteConfirmModal" 
         style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0, 0, 0, 0.5); z-index: 10000; backdrop-filter: blur(4px); padding: 20px;">
      <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; border-radius: 16px; box-shadow: 0 25px 50px rgba(0, 0, 0, 0.25); min-width: 400px; max-width: 500px; animation: modalSlideIn 0.3s ease;">
        <div style="padding: 24px 24px 0 24px; text-align: center;">
          <!-- Warning Icon -->
          <div style="width: 64px; height: 64px; background: linear-gradient(135deg, #ff6b6b, #ee5a52); border-radius: 50%; margin: 0 auto 20px; display: flex; align-items: center; justify-content: center; animation: pulse 2s infinite;">
            <svg style="width: 32px; height: 32px; color: white;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z"/>
            </svg>
          </div>
          
          <h3 style="margin: 0 0 12px 0; color: #2d3748; font-size: 20px; font-weight: 700;">Confirm Violation Deletion</h3>
          
          <p style="margin: 0 0 8px 0; color: #4a5568; font-size: 16px; line-height: 1.5;">
            Are you sure you want to delete this violation:
          </p>
          
          <p id="deleteViolationDescription" style="margin: 0 0 16px 0; color: #2d3748; font-size: 18px; font-weight: 600; background: #f7fafc; padding: 12px; border-radius: 8px; border-left: 4px solid #ff6b6b;"></p>
          
          <div style="background: #fff5f5; padding: 16px; border-radius: 8px; border: 1px solid #fed7d7; margin-bottom: 24px;">
            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px;">
              <svg style="width: 16px; height: 16px; color: #e53e3e;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z"/>
              </svg>
              <strong style="color: #c53030; font-size: 14px;">Warning:</strong>
            </div>
            <p style="margin: 0; color: #c53030; font-size: 14px; line-height: 1.4;">
              This action cannot be undone. All data associated with this violation type will be permanently deleted.
            </p>
          </div>
        </div>
        
        <div style="display: flex; gap: 12px; padding: 0 24px 24px 24px;">
          <button onclick="closeDeleteModal()" style="flex: 1; padding: 12px 20px; background: #e2e8f0; color: #2d3748; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; transition: all 0.3s ease;">
            Cancel
          </button>
          <button id="confirmDeleteBtn" onclick="confirmDelete()" style="flex: 1; padding: 12px 20px; background: linear-gradient(135deg, #ff6b6b, #ee5a52); color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(255, 107, 107, 0.3);">
            Delete Violation
          </button>
        </div>
      </div>
    </div>
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
    
    #confirmDeleteBtn:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 25px rgba(255, 107, 107, 0.4);
      background: linear-gradient(135deg, #ee5a52, #ff6b6b);
    }
    
    button[onclick="closeDeleteModal()"]:hover {
      background: #cbd5e0;
      transform: translateY(-1px);
    }
  </style>

  <script>
    let pendingDeleteId = null;
    let pendingDeleteDescription = null;

    function showDeleteConfirmation(id, description) {
      pendingDeleteId = id;
      pendingDeleteDescription = description;
      
      document.getElementById('deleteViolationDescription').textContent = description;
      document.getElementById('deleteConfirmModal').style.display = 'block';
      document.body.style.overflow = 'hidden';
    }

    function closeDeleteModal() {
      document.getElementById('deleteConfirmModal').style.display = 'none';
      document.body.style.overflow = 'auto';
      pendingDeleteId = null;
      pendingDeleteDescription = null;
    }

    function confirmDelete() {
      if (!pendingDeleteId) return;
      
      const confirmBtn = document.getElementById('confirmDeleteBtn');
      const originalText = confirmBtn.textContent;
      
      // Show loading state
      confirmBtn.disabled = true;
      confirmBtn.innerHTML = '<svg style="width: 16px; height: 16px; animation: spin 1s linear infinite; margin-right: 8px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>Deleting...';
      
      // Create and submit form
      const form = document.createElement('form');
      form.method = 'POST';
      form.action = `/admin/violation/delete/${pendingDeleteId}`;

      // Add CSRF token
      const csrfToken = document.createElement('input');
      csrfToken.type = 'hidden';
      csrfToken.name = '_token';
      csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
      form.appendChild(csrfToken);

      // Add DELETE method
      const methodField = document.createElement('input');
      methodField.type = 'hidden';
      methodField.name = '_method';
      methodField.value = 'DELETE';
      form.appendChild(methodField);

      document.body.appendChild(form);
      form.submit();
    }

    // Close modal when clicking outside or pressing Escape
    document.getElementById('deleteConfirmModal').addEventListener('click', function(e) {
      if (e.target === this) {
        closeDeleteModal();
      }
    });

    document.addEventListener('keydown', function(event) {
      if (event.key === 'Escape') {
        closeDeleteModal();
      }
    });
  </script>
</x-dashboard-layout>