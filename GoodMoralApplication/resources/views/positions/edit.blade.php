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
          <label for="dsn_id" style="display: block; margin-bottom: 8px; font-weight: 500; color: #374151;">Designation</label>
          <select name="dsn_id" id="dsn_id" required style="width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px;">
            <option value="">Select Designation</option>
            @foreach($designations as $designation)
            <option value="{{ $designation->dsn_id }}" {{ (old('dsn_id', $position->dsn_id) == $designation->dsn_id) ? 'selected' : '' }}>
              {{ $designation->description }} ({{ $designation->department ? $designation->department->department_name : 'N/A' }})
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
          <a href="{{ route('admin.positions.index') }}" class="btn-secondary" style="padding: 10px 20px; text-decoration: none; display: inline-block;">
            Cancel
          </a>
        </div>
      </form>
    </div>
  </div>
</x-dashboard-layout>
