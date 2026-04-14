<x-dashboard-layout>
  <x-slot name="roleTitle">Admin</x-slot>

  <x-slot name="navigation">
    <x-admin-navigation />
  </x-slot>

  <div class="header-section">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
      <div>
        <h1 class="role-title">Organization Details</h1>
        <p class="welcome-text">{{ $organization->name }}</p>
        <div class="accent-line"></div>
      </div>
      <div style="display: flex; gap: 12px;">
        <a href="{{ route('admin.organizations.edit', $organization->id) }}"
           class="btn btn-primary" style="text-decoration: none;">
          <svg xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125" />
          </svg>
          Edit
        </a>
        <a href="{{ route('admin.organizations.index') }}"
           class="btn btn-secondary" style="text-decoration: none;">
          ← Back to Organizations
        </a>
      </div>
    </div>
  </div>

  <div style="display: grid; gap: 24px;">
    <!-- Organization Info Card -->
    <div style="background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); padding: 24px; border-top: 4px solid var(--primary-green);">
      <h2 style="color: var(--primary-green); font-size: 1.125rem; font-weight: 600; margin-bottom: 20px;">Organization Information</h2>
      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 20px;">
        <div>
          <p style="font-size: 12px; font-weight: 600; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 4px;">Name</p>
          <p style="color: #111827; font-weight: 500;">{{ $organization->name }}</p>
        </div>
        <div>
          <p style="font-size: 12px; font-weight: 600; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 4px;">Department</p>
          <p style="color: #111827; font-weight: 500;">{{ $organization->department->name ?? 'N/A' }}</p>
        </div>
        <div>
          <p style="font-size: 12px; font-weight: 600; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 4px;">Created</p>
          <p style="color: #111827; font-weight: 500;">{{ $organization->created_at->format('M d, Y') }}</p>
        </div>
      </div>
    </div>

    <!-- Positions Card -->
    <div style="background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); padding: 24px; border-top: 4px solid var(--primary-green);">
      <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2 style="color: var(--primary-green); font-size: 1.125rem; font-weight: 600; margin: 0;">Positions</h2>
        <span style="background: var(--light-green); color: var(--primary-green); padding: 4px 12px; border-radius: 20px; font-size: 14px; font-weight: 600;">
          {{ $organization->positions->count() }} position(s)
        </span>
      </div>

      @if($organization->positions->isEmpty())
        <p style="color: #6b7280; font-style: italic; text-align: center; padding: 24px 0;">No positions defined for this organization yet.</p>
      @else
        <div class="responsive-table-container">
          <table class="responsive-table" style="width: 100%; border-collapse: collapse;">
            <thead>
              <tr style="background: var(--primary-green); color: white;">
                <th style="padding: 12px 16px; text-align: left; font-weight: 600;">#</th>
                <th style="padding: 12px 16px; text-align: left; font-weight: 600;">Position Name</th>
                <th style="padding: 12px 16px; text-align: left; font-weight: 600;">Actions</th>
              </tr>
            </thead>
            <tbody>
              @foreach($organization->positions as $index => $position)
                <tr style="border-bottom: 1px solid #f3f4f6; {{ $loop->even ? 'background: #f9fafb;' : '' }}">
                  <td style="padding: 12px 16px; color: #6b7280;">{{ $index + 1 }}</td>
                  <td style="padding: 12px 16px; color: #111827; font-weight: 500;">{{ $position->name }}</td>
                  <td style="padding: 12px 16px;">
                    <a href="{{ route('admin.positions.edit', $position->id) }}"
                       style="color: var(--primary-green); text-decoration: none; font-size: 14px; font-weight: 500;">Edit</a>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      @endif
    </div>
  </div>
</x-dashboard-layout>
