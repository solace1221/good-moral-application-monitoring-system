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
        <h1 class="responsive-title role-title">Apply for PSG Officer</h1>
        <p class="responsive-text welcome-text">Submit your application to become a PSG Officer</p>
        <div class="accent-line"></div>
      </div>
    </div>
  </div>

  @include('shared.alerts.flash')

  <div class="header-section">
    @if($approvedApplication)
      {{-- Student is already an approved PSG Officer --}}
      <div style="text-align: center; padding: 40px 20px; background: #d4edda; border-radius: 12px; border: 2px solid #28a745;">
        <svg style="width: 64px; height: 64px; color: #28a745; margin: 0 auto 16px;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
        </svg>
        <h3 style="color: #155724; font-size: 20px; margin-bottom: 8px;">You are an Approved PSG Officer</h3>
        <p style="color: #155724; font-size: 14px;">Your PSG Officer application has been approved. You can access the PSG Officer dashboard.</p>
        <p style="color: #155724; font-size: 13px; margin-top: 12px;">
          <strong>Organization:</strong> {{ $approvedApplication->organization->description ?? 'N/A' }} |
          <strong>Position:</strong> {{ $approvedApplication->position->position_title ?? 'N/A' }}
        </p>
      </div>

    @elseif($pendingApplication)
      {{-- Student has a pending application --}}
      <div style="text-align: center; padding: 40px 20px; background: #fff3cd; border-radius: 12px; border: 2px solid #ffc107;">
        <svg style="width: 64px; height: 64px; color: #856404; margin: 0 auto 16px;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
        </svg>
        <h3 style="color: #856404; font-size: 20px; margin-bottom: 8px;">Application Pending</h3>
        <p style="color: #856404; font-size: 14px;">Your PSG Officer application is under review. You will be notified once a decision is made.</p>
        <p style="color: #856404; font-size: 13px; margin-top: 12px;">
          <strong>Organization:</strong> {{ $pendingApplication->organization->description ?? 'N/A' }} |
          <strong>Position:</strong> {{ $pendingApplication->position->position_title ?? 'N/A' }} |
          <strong>Submitted:</strong> {{ $pendingApplication->created_at->format('F j, Y') }}
        </p>
      </div>

    @else
      {{-- Show the application form --}}
      <div style="background: white; padding: 32px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);">
        <h3 style="font-size: 18px; font-weight: 700; color: #1f2937; margin-bottom: 8px;">PSG Officer Application Form</h3>
        <p style="color: #6b7280; font-size: 14px; margin-bottom: 24px;">Fill out the form below to apply for a PSG Officer position. Your existing student information will be used.</p>

        <!-- Student Info Summary -->
        <div style="background: #f8f9fa; padding: 16px; border-radius: 8px; margin-bottom: 24px; border-left: 4px solid var(--primary-green);">
          <h4 style="font-size: 14px; font-weight: 600; color: #374151; margin-bottom: 8px;">Your Information</h4>
          <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 8px;">
            <p style="font-size: 13px; color: #6b7280;"><strong>Name:</strong> {{ $student->fullname }}</p>
            <p style="font-size: 13px; color: #6b7280;"><strong>Student ID:</strong> {{ $student->student_id ?? 'N/A' }}</p>
            <p style="font-size: 13px; color: #6b7280;"><strong>Department:</strong> {{ $student->department ?? 'N/A' }}</p>
            <p style="font-size: 13px; color: #6b7280;"><strong>Email:</strong> {{ $student->email }}</p>
          </div>
        </div>

        <form method="POST" action="{{ route('student.submitPsgApplication') }}">
          @csrf

          <!-- Organization -->
          <div style="margin-bottom: 20px;">
            <label for="organization_id" style="display: block; margin-bottom: 8px; font-weight: 600; color: #333;">Organization *</label>
            <select id="organization_id" name="organization_id" required
              style="width: 100%; padding: 12px 16px; border: 2px solid #e1e5e9; border-radius: 8px; font-size: 14px; transition: border-color 0.3s ease;"
              onfocus="this.style.borderColor='var(--primary-green)'"
              onblur="this.style.borderColor='#e1e5e9'"
              onchange="loadPositions(this.value)">
              <option value="" disabled selected>Select Organization</option>
              @foreach($organizations as $org)
                <option value="{{ $org->id }}" {{ old('organization_id') == $org->id ? 'selected' : '' }}>{{ $org->description }}</option>
              @endforeach
            </select>
            @error('organization_id')
              <p style="color: #dc3545; font-size: 12px; margin-top: 4px;">{{ $message }}</p>
            @enderror
          </div>

          <!-- Position -->
          <div style="margin-bottom: 20px;">
            <label for="position_id" style="display: block; margin-bottom: 8px; font-weight: 600; color: #333;">Position *</label>
            <select id="position_id" name="position_id" required
              style="width: 100%; padding: 12px 16px; border: 2px solid #e1e5e9; border-radius: 8px; font-size: 14px; transition: border-color 0.3s ease;"
              onfocus="this.style.borderColor='var(--primary-green)'"
              onblur="this.style.borderColor='#e1e5e9'"
              disabled>
              <option value="" disabled selected>Select an organization first</option>
            </select>
            @error('position_id')
              <p style="color: #dc3545; font-size: 12px; margin-top: 4px;">{{ $message }}</p>
            @enderror
          </div>

          <!-- Submit Button -->
          <div style="margin-top: 32px;">
            <button type="submit"
              style="padding: 14px 32px; background: var(--primary-green); color: white; border: none; border-radius: 8px; font-size: 16px; font-weight: 600; cursor: pointer; transition: all 0.3s ease; display: flex; align-items: center; gap: 8px;"
              onmouseover="this.style.background='#2c5530'; this.style.transform='translateY(-1px)'"
              onmouseout="this.style.background='var(--primary-green)'; this.style.transform='translateY(0)'">
              <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
              </svg>
              Submit Application
            </button>
          </div>
        </form>
      </div>
    @endif
  </div>

  <script>
    function loadPositions(organizationId) {
      const positionSelect = document.getElementById('position_id');
      positionSelect.innerHTML = '<option value="" disabled selected>Loading positions...</option>';
      positionSelect.disabled = true;

      if (!organizationId) {
        positionSelect.innerHTML = '<option value="" disabled selected>Select an organization first</option>';
        return;
      }

      fetch(`/student/psg-positions/${organizationId}`)
        .then(response => response.json())
        .then(positions => {
          positionSelect.innerHTML = '<option value="" disabled selected>Select Position</option>';
          positions.forEach(position => {
            const option = document.createElement('option');
            option.value = position.id;
            option.textContent = position.position_title;
            positionSelect.appendChild(option);
          });
          positionSelect.disabled = false;
        })
        .catch(() => {
          positionSelect.innerHTML = '<option value="" disabled selected>Failed to load positions</option>';
        });
    }

    // If organization was previously selected (old input), reload positions
    document.addEventListener('DOMContentLoaded', function() {
      const orgSelect = document.getElementById('organization_id');
      if (orgSelect.value) {
        loadPositions(orgSelect.value);
      }
    });
  </script>
</x-dashboard-layout>
