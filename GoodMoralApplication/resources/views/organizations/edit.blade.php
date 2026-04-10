<x-dashboard-layout>
  <x-slot name="roleTitle">Admin</x-slot>

  <x-slot name="navigation">
    <x-admin-navigation />
  </x-slot>

  <!-- Header Section -->
  <div class="header-section">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
      <div>
        <h1 class="role-title">Edit: {{ $organization->description }}</h1>
        <p class="welcome-text">Update organization information</p>
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

      <form action="{{ route('admin.organizations.update', $organization->id) }}" method="POST" style="max-width: 600px;">
        @csrf
        @method('PUT')

        <div style="margin-top: 24px;">
          <label for="department_id" style="display: block; margin-bottom: 8px; font-weight: 500; color: #374151;">Department</label>
          <select name="department_id" id="department_id" required style="width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px;">
            <option value="">Select Department</option>
            @foreach($departments as $department)
            <option value="{{ $department->id }}" {{ (old('department_id', $organization->department_id) == $department->id) ? 'selected' : '' }}>
              {{ $department->department_name }}
            </option>
            @endforeach
          </select>
        </div>

        <div style="margin-top: 24px;">
          <label for="description" style="display: block; margin-bottom: 8px; font-weight: 500; color: #374151;">Description</label>
          <input type="text" name="description" id="description" value="{{ old('description', $organization->description) }}" required style="width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px;">
        </div>

        <div style="margin-top: 32px; display: flex; gap: 12px;">
          <button type="submit" class="btn-primary" style="padding: 10px 20px;">
            Update Organization
          </button>
          <a href="{{ route('admin.organizations.index') }}" class="btn-secondary" style="padding: 10px 20px; text-decoration: none; display: inline-block;">
            Cancel
          </a>
        </div>
      </form>
    </div>
  </div>
</x-dashboard-layout>
