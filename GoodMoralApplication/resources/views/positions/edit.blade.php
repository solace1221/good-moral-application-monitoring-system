<x-dashboard-layout>
  <x-slot name="roleTitle">Admin</x-slot>

  <x-slot name="navigation">
    <x-admin-navigation />
  </x-slot>

  <!-- Header Section -->
  <div class="header-section">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
      <div>
        <h1 class="role-title">Edit: {{ $position->position_title }}</h1>
        <p class="welcome-text">Update position information</p>
        <div class="accent-line"></div>
      </div>
    </div>
  </div>

  <!-- Main Content -->
  <div class="content-section">
    <div class="card">
      @if($errors->any())
      <div class="alert alert-danger" style="margin-bottom: 20px;">
        <ul style="margin: 0; padding-left: 20px;">
          @foreach($errors->all() as $error)
          <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
      @endif

      <form action="{{ route('admin.positions.update', $position->position_id) }}" method="POST" style="max-width: 600px;">
        @csrf
        @method('PUT')

        <div style="margin-top: 24px;">
          <label for="organization_id" style="display: block; margin-bottom: 8px; font-weight: 500; color: #374151;">Organization</label>
          <select name="organization_id" id="organization_id" required style="width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px;">
            <option value="">Select Organization</option>
            @foreach($organizations as $organization)
            <option value="{{ $organization->id }}" {{ (old('organization_id', $position->organization_id) == $organization->id) ? 'selected' : '' }}>
              {{ $organization->description }}{{ $organization->department ? ' — ' . $organization->department->department_code : '' }}
            </option>
            @endforeach
          </select>
        </div>

        <div style="margin-top: 24px;">
          <label for="position_title" style="display: block; margin-bottom: 8px; font-weight: 500; color: #374151;">Position Title</label>
          <input type="text" name="position_title" id="position_title" value="{{ old('position_title', $position->position_title) }}" required style="width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px;">
        </div>

        <div style="margin-top: 32px; display: flex; gap: 12px;">
          <button type="submit" class="btn-primary" style="padding: 10px 20px;">
            Update Position
          </button>
          <a href="{{ route('admin.positions.index') }}" style="padding: 10px 20px; background: #f3f4f6; color: #374151; border-radius: 6px; text-decoration: none; font-size: 14px; display: inline-block;"
             onmouseover="this.style.background='#e5e7eb'" onmouseout="this.style.background='#f3f4f6'">
            Cancel
          </a>
        </div>
      </form>
    </div>
  </div>
</x-dashboard-layout>
