<x-dashboard-layout>
  <x-slot name="roleTitle">Admin</x-slot>

  <x-slot name="navigation">
    <x-admin-navigation />
  </x-slot>

  <!-- Header Section -->
  <div class="header-section">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
      <div>
        <h1 class="role-title">Organization Management</h1>
        <p class="welcome-text">Manage organizations for departments</p>
        <div class="accent-line"></div>
      </div>
      <div style="display: flex; gap: 12px; align-items: center;">
        <a href="{{ route('admin.organizations.create') }}" class="btn-primary" style="padding: 10px 16px; font-size: 14px; text-decoration: none; display: flex; align-items: center; gap: 8px;">
          <svg xmlns="http://www.w3.org/2000/svg" style="width: 18px; height: 18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
          </svg>
          Add New Organization
        </a>
      </div>
    </div>
  </div>

  <!-- Main Content -->
  <div class="content-section">
    <div class="card">
      @if(session('success'))
      <div class="alert alert-success" style="margin-bottom: 20px;">
        {{ session('success') }}
      </div>
      @endif

      @if($organizations->isEmpty())
      <div style="text-align: center; padding: 40px 20px; color: #666;">
        <p>No organizations found.</p>
      </div>
      @else
        <div class="overflow-x-auto">
          <table class="min-w-full bg-white border border-gray-300 rounded-lg">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Description</th>
                <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Department</th>
                <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Created At</th>
                <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Actions</th>
              </tr>
            </thead>
            <tbody>
              @foreach($organizations as $organization)
              <tr class="border-b hover:bg-gray-50">
                <td class="px-6 py-4 text-sm text-gray-600">{{ $organization->description }}</td>
                <td class="px-6 py-4 text-sm text-gray-600">{{ $organization->department ? $organization->department->department_name : 'N/A' }}</td>
                <td class="px-6 py-4 text-sm text-gray-600">{{ $organization->created_at->format('Y-m-d H:i') }}</td>
                <td class="px-6 py-4 text-sm text-gray-600">
                  <div class="flex gap-2">
                    <a href="{{ route('admin.organizations.edit', $organization->id) }}" class="bg-blue-500 hover:bg-blue-700 text-white px-3 py-1 rounded-md text-xs">
                      Edit
                    </a>
                    <form action="{{ route('admin.organizations.destroy', $organization->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this organization?');">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="bg-red-500 hover:bg-red-700 text-white px-3 py-1 rounded-md text-xs">
                        Delete
                      </button>
                    </form>
                  </div>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
        
        <!-- Pagination -->
        <div style="margin-top: 20px;">
          {{ $organizations->links('vendor.pagination.custom') }}
        </div>
      @endif
    </div>
  </div>
</x-dashboard-layout>
