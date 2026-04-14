<x-dashboard-layout>
  <x-slot name="roleTitle">Admin</x-slot>

  <x-slot name="navigation">
    <x-admin-navigation />
  </x-slot>

  <div class="header-section">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
      <div>
        <h1 class="role-title">Database Summary</h1>
        <p class="welcome-text">System-wide data overview and record counts</p>
        <div class="accent-line"></div>
      </div>
    </div>
  </div>

  <!-- Top Stats -->
  <div class="stats-grid" style="margin-bottom: 24px;">
    <div class="stat-card">
      <div class="stat-number">{{ $totalUsers }}</div>
      <div class="stat-label">Total User Accounts</div>
    </div>
    <div class="stat-card">
      <div class="stat-number">{{ $totalStudents }}</div>
      <div class="stat-label">Student Registrations</div>
    </div>
    <div class="stat-card">
      <div class="stat-number">{{ $totalApplications }}</div>
      <div class="stat-label">Certificate Applications</div>
    </div>
    <div class="stat-card">
      <div class="stat-number">{{ $totalViolations }}</div>
      <div class="stat-label">Violation Records</div>
    </div>
  </div>

  <!-- Database Tables Overview -->
  <div style="background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); padding: 24px; border-top: 4px solid var(--primary-green); margin-bottom: 24px;">
    <h2 style="color: var(--primary-green); font-size: 1.125rem; font-weight: 600; margin-bottom: 20px;">Database Tables</h2>
    <div class="responsive-table-container">
      <table class="responsive-table" style="width: 100%; border-collapse: collapse;">
        <thead>
          <tr style="background: var(--primary-green); color: white;">
            <th style="padding: 12px 16px; text-align: left;">Table</th>
            <th style="padding: 12px 16px; text-align: left;">Description</th>
            <th style="padding: 12px 16px; text-align: right;">Records</th>
          </tr>
        </thead>
        <tbody>
          @foreach($databaseTables as $table)
            <tr style="border-bottom: 1px solid #f3f4f6; {{ $loop->even ? 'background: #f9fafb;' : '' }}">
              <td style="padding: 12px 16px; font-weight: 600; color: #111827; font-family: monospace; font-size: 13px;">{{ $table['name'] }}</td>
              <td style="padding: 12px 16px; color: #6b7280;">{{ $table['description'] }}</td>
              <td style="padding: 12px 16px; text-align: right; font-weight: 700; color: var(--primary-green);">{{ number_format($table['count']) }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>

  <!-- Users by Role -->
  <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(340px, 1fr)); gap: 24px; margin-bottom: 24px;">
    <div style="background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); padding: 24px; border-top: 4px solid var(--primary-green);">
      <h2 style="color: var(--primary-green); font-size: 1.125rem; font-weight: 600; margin-bottom: 16px;">Active Accounts by Role</h2>
      @forelse($usersByRole as $role => $count)
        <div style="display: flex; justify-content: space-between; align-items: center; padding: 10px 0; border-bottom: 1px solid #f3f4f6;">
          <span style="color: #374151; text-transform: capitalize;">{{ str_replace('_', ' ', $role) }}</span>
          <span style="font-weight: 700; color: var(--primary-green); background: var(--light-green); padding: 2px 10px; border-radius: 12px;">{{ $count }}</span>
        </div>
      @empty
        <p style="color: #6b7280; font-style: italic;">No accounts found.</p>
      @endforelse
    </div>

    <div style="background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); padding: 24px; border-top: 4px solid #6c757d;">
      <h2 style="color: #6c757d; font-size: 1.125rem; font-weight: 600; margin-bottom: 16px;">Archived Accounts by Role</h2>
      @forelse($archivedUsersByRole as $role => $count)
        <div style="display: flex; justify-content: space-between; align-items: center; padding: 10px 0; border-bottom: 1px solid #f3f4f6;">
          <span style="color: #374151; text-transform: capitalize;">{{ str_replace('_', ' ', $role) }}</span>
          <span style="font-weight: 700; color: #6c757d; background: #f3f4f6; padding: 2px 10px; border-radius: 12px;">{{ $count }}</span>
        </div>
      @empty
        <p style="color: #6b7280; font-style: italic;">No archived accounts.</p>
      @endforelse
      <div style="margin-top: 16px; padding-top: 12px; border-top: 2px solid #e5e7eb;">
        <div style="display: flex; justify-content: space-between;">
          <span style="color: #374151; font-weight: 600;">Archived Notifications</span>
          <span style="font-weight: 700; color: #6c757d;">{{ $archivedNotifications }}</span>
        </div>
      </div>
    </div>
  </div>

  <!-- Students by Department -->
  <div style="background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); padding: 24px; border-top: 4px solid var(--primary-green); margin-bottom: 24px;">
    <h2 style="color: var(--primary-green); font-size: 1.125rem; font-weight: 600; margin-bottom: 20px;">Students by Department</h2>
    <div class="responsive-table-container">
      <table class="responsive-table" style="width: 100%; border-collapse: collapse;">
        <thead>
          <tr style="background: var(--primary-green); color: white;">
            <th style="padding: 12px 16px; text-align: left;">Department</th>
            <th style="padding: 12px 16px; text-align: right;">Total</th>
            <th style="padding: 12px 16px; text-align: right;">Male</th>
            <th style="padding: 12px 16px; text-align: right;">Female</th>
            <th style="padding: 12px 16px; text-align: right;">%</th>
          </tr>
        </thead>
        <tbody>
          @foreach($studentsByDepartment as $dept => $data)
            <tr style="border-bottom: 1px solid #f3f4f6; {{ $loop->even ? 'background: #f9fafb;' : '' }}">
              <td style="padding: 12px 16px; color: #111827; font-weight: 500;">{{ $dept }}</td>
              <td style="padding: 12px 16px; text-align: right; font-weight: 700; color: var(--primary-green);">{{ number_format($data['total']) }}</td>
              <td style="padding: 12px 16px; text-align: right; color: #2563eb;">{{ number_format($data['male']) }}</td>
              <td style="padding: 12px 16px; text-align: right; color: #db2777;">{{ number_format($data['female']) }}</td>
              <td style="padding: 12px 16px; text-align: right; color: #6b7280;">{{ number_format($data['percentage'], 1) }}%</td>
            </tr>
          @endforeach
        </tbody>
        <tfoot>
          <tr style="background: #f9fafb; font-weight: 700; border-top: 2px solid #e5e7eb;">
            <td style="padding: 12px 16px; color: #111827;">Total</td>
            <td style="padding: 12px 16px; text-align: right; color: var(--primary-green);">{{ number_format($totalStudents) }}</td>
            <td colspan="3"></td>
          </tr>
        </tfoot>
      </table>
    </div>
  </div>

  <!-- Applications by Department -->
  <div style="background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); padding: 24px; border-top: 4px solid var(--primary-green); margin-bottom: 24px;">
    <h2 style="color: var(--primary-green); font-size: 1.125rem; font-weight: 600; margin-bottom: 20px;">Certificate Applications by Department</h2>
    <div class="responsive-table-container">
      <table class="responsive-table" style="width: 100%; border-collapse: collapse;">
        <thead>
          <tr style="background: var(--primary-green); color: white;">
            <th style="padding: 12px 16px; text-align: left;">Department</th>
            <th style="padding: 12px 16px; text-align: right;">Total</th>
            <th style="padding: 12px 16px; text-align: right;">Pending</th>
            <th style="padding: 12px 16px; text-align: right;">Approved</th>
            <th style="padding: 12px 16px; text-align: right;">Rejected</th>
            <th style="padding: 12px 16px; text-align: right;">Ready</th>
          </tr>
        </thead>
        <tbody>
          @foreach($applicationsByDepartment as $dept => $data)
            <tr style="border-bottom: 1px solid #f3f4f6; {{ $loop->even ? 'background: #f9fafb;' : '' }}">
              <td style="padding: 12px 16px; color: #111827; font-weight: 500;">{{ $dept }}</td>
              <td style="padding: 12px 16px; text-align: right; font-weight: 700; color: var(--primary-green);">{{ number_format($data['total']) }}</td>
              <td style="padding: 12px 16px; text-align: right; color: #f59e0b;">{{ number_format($data['pending']) }}</td>
              <td style="padding: 12px 16px; text-align: right; color: #10b981;">{{ number_format($data['approved']) }}</td>
              <td style="padding: 12px 16px; text-align: right; color: #ef4444;">{{ number_format($data['rejected']) }}</td>
              <td style="padding: 12px 16px; text-align: right; color: #3b82f6;">{{ number_format($data['ready']) }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>

  <!-- Violations by Department -->
  <div style="background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); padding: 24px; border-top: 4px solid var(--primary-green); margin-bottom: 24px;">
    <h2 style="color: var(--primary-green); font-size: 1.125rem; font-weight: 600; margin-bottom: 20px;">Violations by Department</h2>
    <div class="responsive-table-container">
      <table class="responsive-table" style="width: 100%; border-collapse: collapse;">
        <thead>
          <tr style="background: var(--primary-green); color: white;">
            <th style="padding: 12px 16px; text-align: left;">Department</th>
            <th style="padding: 12px 16px; text-align: right;">Total</th>
            <th style="padding: 12px 16px; text-align: right;">Minor</th>
            <th style="padding: 12px 16px; text-align: right;">Major</th>
            <th style="padding: 12px 16px; text-align: right;">Resolved</th>
            <th style="padding: 12px 16px; text-align: right;">Pending</th>
          </tr>
        </thead>
        <tbody>
          @foreach($violationsByDepartment as $dept => $data)
            <tr style="border-bottom: 1px solid #f3f4f6; {{ $loop->even ? 'background: #f9fafb;' : '' }}">
              <td style="padding: 12px 16px; color: #111827; font-weight: 500;">{{ $dept }}</td>
              <td style="padding: 12px 16px; text-align: right; font-weight: 700; color: var(--primary-green);">{{ number_format($data['total']) }}</td>
              <td style="padding: 12px 16px; text-align: right; color: #f59e0b;">{{ number_format($data['minor']) }}</td>
              <td style="padding: 12px 16px; text-align: right; color: #ef4444;">{{ number_format($data['major']) }}</td>
              <td style="padding: 12px 16px; text-align: right; color: #10b981;">{{ number_format($data['resolved']) }}</td>
              <td style="padding: 12px 16px; text-align: right; color: #6b7280;">{{ number_format($data['pending']) }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>

  <!-- Revenue Summary -->
  <div style="background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); padding: 24px; border-top: 4px solid var(--primary-green); margin-bottom: 24px;">
    <h2 style="color: var(--primary-green); font-size: 1.125rem; font-weight: 600; margin-bottom: 16px;">Revenue Summary</h2>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 20px;">
      <div style="background: #f0fdf4; border-radius: 8px; padding: 16px; text-align: center;">
        <div style="font-size: 1.75rem; font-weight: 700; color: var(--primary-green);">{{ number_format($totalReceipts) }}</div>
        <div style="color: #374151; font-size: 14px; margin-top: 4px;">Total Receipts</div>
      </div>
      <div style="background: #f0fdf4; border-radius: 8px; padding: 16px; text-align: center;">
        <div style="font-size: 1.75rem; font-weight: 700; color: var(--primary-green);">₱{{ number_format($totalRevenue, 2) }}</div>
        <div style="color: #374151; font-size: 14px; margin-top: 4px;">Total Revenue</div>
      </div>
      <div style="background: #f0fdf4; border-radius: 8px; padding: 16px; text-align: center;">
        <div style="font-size: 1.75rem; font-weight: 700; color: var(--primary-green);">₱{{ number_format($averagePayment, 2) }}</div>
        <div style="color: #374151; font-size: 14px; margin-top: 4px;">Average Payment</div>
      </div>
    </div>
    @if(!empty($receiptsByPaymentMethod))
      <h3 style="color: #374151; font-size: 1rem; font-weight: 600; margin-bottom: 12px;">By Payment Method</h3>
      @foreach($receiptsByPaymentMethod as $method => $data)
        <div style="display: flex; justify-content: space-between; align-items: center; padding: 10px 0; border-bottom: 1px solid #f3f4f6;">
          <span style="color: #374151; text-transform: capitalize;">{{ $method ?? 'Unknown' }}</span>
          <div style="display: flex; gap: 16px; align-items: center;">
            <span style="color: #6b7280; font-size: 14px;">{{ number_format($data['count']) }} receipts</span>
            <span style="font-weight: 700; color: var(--primary-green);">₱{{ number_format($data['total'], 2) }}</span>
          </div>
        </div>
      @endforeach
    @endif
  </div>
</x-dashboard-layout>
