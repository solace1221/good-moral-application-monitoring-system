<x-dashboard-layout>
  <x-slot name="roleTitle">Admin</x-slot>

  <x-slot name="navigation">
    <x-admin-navigation />
  </x-slot>

  <!-- Header Section -->
  <div class="header-section">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
      <div>
        <h1 class="role-title">Database Summary</h1>
        <p class="welcome-text">Complete system data overview as of {{ now()->format('F j, Y g:i A') }}</p>
        <div class="accent-line"></div>
      </div>
      <div style="display: flex; gap: 12px; flex-wrap: wrap;">
        <button onclick="downloadPDF()" class="btn-primary" style="display: flex; align-items: center; gap: 8px;">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 20px; height: 20px;">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
          </svg>
          Download PDF
        </button>
        <button onclick="downloadExcel()" class="btn-primary" style="display: flex; align-items: center; gap: 8px; background: #28a745;">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 20px; height: 20px;">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
          </svg>
          Download Excel
        </button>
      </div>
    </div>
  </div>

  <!-- Quick Stats Overview -->
  <div class="stats-grid" style="margin-bottom: 24px;">
    <div class="stat-card" style="border-top-color: #7B2CBF;">
      <div class="stat-number">{{ $totalApplications }}</div>
      <div class="stat-label">Total Applications</div>
    </div>
    <div class="stat-card" style="border-top-color: #DC3545;">
      <div class="stat-number">{{ $totalViolations }}</div>
      <div class="stat-label">Total Violations</div>
    </div>
    <div class="stat-card" style="border-top-color: #0066CC;">
      <div class="stat-number">{{ $totalUsers }}</div>
      <div class="stat-label">Total Users</div>
    </div>
    <div class="stat-card" style="border-top-color: #28A745;">
      <div class="stat-number">{{ $totalStudents }}</div>
      <div class="stat-label">Total Students</div>
    </div>
  </div>

  <!-- 1. USERS & ACCOUNTS -->
  <div class="header-section" style="margin-bottom: 24px;">
    <h2 style="color: var(--primary-green); margin-bottom: 16px; font-size: 1.4rem; display: flex; align-items: center; gap: 12px;">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 28px; height: 28px;">
        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
      </svg>
      Users & Accounts
    </h2>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 16px; margin-bottom: 20px;">
      <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 20px; border-radius: 8px; color: white;">
        <div style="font-size: 2rem; font-weight: bold;">{{ $usersByRole['admin'] ?? 0 }}</div>
        <div style="font-size: 14px; opacity: 0.9;">Administrators</div>
      </div>
      <div style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); padding: 20px; border-radius: 8px; color: white;">
        <div style="font-size: 2rem; font-weight: bold;">{{ $usersByRole['registrar'] ?? 0 }}</div>
        <div style="font-size: 14px; opacity: 0.9;">Registrars</div>
      </div>
      <div style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); padding: 20px; border-radius: 8px; color: white;">
        <div style="font-size: 2rem; font-weight: bold;">{{ $usersByRole['headosa'] ?? 0 }}</div>
        <div style="font-size: 14px; opacity: 0.9;">Head OSA</div>
      </div>
      <div style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); padding: 20px; border-radius: 8px; color: white;">
        <div style="font-size: 2rem; font-weight: bold;">{{ $usersByRole['secosa'] ?? 0 }}</div>
        <div style="font-size: 14px; opacity: 0.9;">SEC OSA</div>
      </div>
      <div style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); padding: 20px; border-radius: 8px; color: white;">
        <div style="font-size: 2rem; font-weight: bold;">{{ $usersByRole['dean'] ?? 0 }}</div>
        <div style="font-size: 14px; opacity: 0.9;">Deans</div>
      </div>
      <div style="background: linear-gradient(135deg, #30cfd0 0%, #330867 100%); padding: 20px; border-radius: 8px; color: white;">
        <div style="font-size: 2rem; font-weight: bold;">{{ $usersByRole['student'] ?? 0 }}</div>
        <div style="font-size: 14px; opacity: 0.9;">Students/Alumni</div>
      </div>
    </div>

    <div class="responsive-table-container">
      <table class="responsive-table">
        <thead>
          <tr>
            <th>Role</th>
            <th>Active Users</th>
            <th>Archived Users</th>
            <th>Total</th>
          </tr>
        </thead>
        <tbody>
          @foreach($usersByRole as $role => $count)
          <tr>
            <td style="font-weight: 600; text-transform: capitalize;">{{ ucfirst($role) }}</td>
            <td style="text-align: center;">{{ $count }}</td>
            <td style="text-align: center;">{{ $archivedUsersByRole[$role] ?? 0 }}</td>
            <td style="text-align: center; font-weight: bold;">{{ $count + ($archivedUsersByRole[$role] ?? 0) }}</td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>

  <!-- 2. STUDENT REGISTRATIONS BY DEPARTMENT -->
  <div class="header-section" style="margin-bottom: 24px;">
    <h2 style="color: var(--primary-green); margin-bottom: 16px; font-size: 1.4rem; display: flex; align-items: center; gap: 12px;">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 28px; height: 28px;">
        <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5" />
      </svg>
      Students by Department
    </h2>

    <div class="responsive-table-container">
      <table class="responsive-table">
        <thead>
          <tr>
            <th>Department</th>
            <th>Total Students</th>
            <th>Male</th>
            <th>Female</th>
            <th>Percentage</th>
          </tr>
        </thead>
        <tbody>
          @foreach($studentsByDepartment as $dept => $data)
          <tr>
            <td style="font-weight: 600; color: 
              @switch($dept)
                @case('SITE') #7B2CBF @break
                @case('SBAHM') #28A745 @break
                @case('SNAHS') #DC3545 @break
                @case('SASTE') #0066CC @break
                @case('SOM') #FFC107 @break
                @case('GRADSCH') #6F42C1 @break
                @default #2c3e50
              @endswitch
            ;">{{ $dept }}</td>
            <td style="text-align: center; font-weight: bold;">{{ $data['total'] }}</td>
            <td style="text-align: center;">{{ $data['male'] }}</td>
            <td style="text-align: center;">{{ $data['female'] }}</td>
            <td style="text-align: center;">
              <div style="display: flex; align-items: center; gap: 8px;">
                <div style="flex: 1; background: #e5e7eb; border-radius: 10px; height: 8px; overflow: hidden;">
                  <div style="background: var(--primary-green); height: 100%; width: {{ $data['percentage'] }}%; transition: width 0.3s ease;"></div>
                </div>
                <span style="font-weight: 600; min-width: 50px;">{{ number_format($data['percentage'], 1) }}%</span>
              </div>
            </td>
          </tr>
          @endforeach
          <tr style="background: #f8f9fa; font-weight: bold;">
            <td>TOTAL</td>
            <td style="text-align: center;">{{ array_sum(array_column($studentsByDepartment, 'total')) }}</td>
            <td style="text-align: center;">{{ array_sum(array_column($studentsByDepartment, 'male')) }}</td>
            <td style="text-align: center;">{{ array_sum(array_column($studentsByDepartment, 'female')) }}</td>
            <td style="text-align: center;">100%</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>

  <!-- 3. GOOD MORAL APPLICATIONS -->
  <div class="header-section" style="margin-bottom: 24px;">
    <h2 style="color: var(--primary-green); margin-bottom: 16px; font-size: 1.4rem; display: flex; align-items: center; gap: 12px;">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 28px; height: 28px;">
        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
      </svg>
      Good Moral Applications
    </h2>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 12px; margin-bottom: 20px;">
      <div style="background: #e3f2fd; padding: 16px; border-radius: 8px; border-left: 4px solid #2196f3;">
        <div style="font-size: 1.8rem; font-weight: bold; color: #1976d2;">{{ $applicationsByStatus['Pending'] ?? 0 }}</div>
        <div style="font-size: 13px; color: #666;">Pending</div>
      </div>
      <div style="background: #fff3e0; padding: 16px; border-radius: 8px; border-left: 4px solid #ff9800;">
        <div style="font-size: 1.8rem; font-weight: bold; color: #f57c00;">{{ $applicationsByStatus['Approved by SEC-OSA'] ?? 0 }}</div>
        <div style="font-size: 13px; color: #666;">SEC-OSA Approved</div>
      </div>
      <div style="background: #f3e5f5; padding: 16px; border-radius: 8px; border-left: 4px solid #9c27b0;">
        <div style="font-size: 1.8rem; font-weight: bold; color: #7b1fa2;">{{ $applicationsByStatus['Approved by HEAD-OSA'] ?? 0 }}</div>
        <div style="font-size: 13px; color: #666;">HEAD-OSA Approved</div>
      </div>
      <div style="background: #e1f5fe; padding: 16px; border-radius: 8px; border-left: 4px solid #00bcd4;">
        <div style="font-size: 1.8rem; font-weight: bold; color: #0097a7;">{{ $applicationsByStatus['Approved by DEAN'] ?? 0 }}</div>
        <div style="font-size: 13px; color: #666;">Dean Approved</div>
      </div>
      <div style="background: #e8f5e9; padding: 16px; border-radius: 8px; border-left: 4px solid #4caf50;">
        <div style="font-size: 1.8rem; font-weight: bold; color: #388e3c;">{{ $applicationsByStatus['Approved by Administrator'] ?? 0 }}</div>
        <div style="font-size: 13px; color: #666;">Admin Approved</div>
      </div>
      <div style="background: #c8e6c9; padding: 16px; border-radius: 8px; border-left: 4px solid #2e7d32;">
        <div style="font-size: 1.8rem; font-weight: bold; color: #1b5e20;">{{ $applicationsByStatus['Ready for Pickup'] ?? 0 }}</div>
        <div style="font-size: 13px; color: #666;">Ready for Pickup</div>
      </div>
      <div style="background: #ffebee; padding: 16px; border-radius: 8px; border-left: 4px solid #f44336;">
        <div style="font-size: 1.8rem; font-weight: bold; color: #c62828;">{{ $applicationsByStatus['Rejected'] ?? 0 }}</div>
        <div style="font-size: 13px; color: #666;">Rejected</div>
      </div>
      <div style="background: #fce4ec; padding: 16px; border-radius: 8px; border-left: 4px solid #e91e63;">
        <div style="font-size: 1.8rem; font-weight: bold; color: #ad1457;">{{ $applicationsByStatus['Cancelled'] ?? 0 }}</div>
        <div style="font-size: 13px; color: #666;">Cancelled</div>
      </div>
    </div>

    <div class="responsive-table-container">
      <table class="responsive-table">
        <thead>
          <tr>
            <th>Department</th>
            <th>Total Apps</th>
            <th>Pending</th>
            <th>Approved</th>
            <th>Rejected</th>
            <th>Ready for Pickup</th>
          </tr>
        </thead>
        <tbody>
          @foreach($applicationsByDepartment as $dept => $data)
          <tr>
            <td style="font-weight: 600;">{{ $dept }}</td>
            <td style="text-align: center; font-weight: bold;">{{ $data['total'] }}</td>
            <td style="text-align: center;">{{ $data['pending'] }}</td>
            <td style="text-align: center; color: #28a745;">{{ $data['approved'] }}</td>
            <td style="text-align: center; color: #dc3545;">{{ $data['rejected'] }}</td>
            <td style="text-align: center; color: #17a2b8;">{{ $data['ready'] }}</td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>

  <!-- 4. VIOLATIONS SUMMARY -->
  <div class="header-section" style="margin-bottom: 24px;">
    <h2 style="color: var(--primary-green); margin-bottom: 16px; font-size: 1.4rem; display: flex; align-items: center; gap: 12px;">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 28px; height: 28px;">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
      </svg>
      Violations Summary
    </h2>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 16px; margin-bottom: 20px;">
      <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 24px; border-radius: 12px; color: white; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
        <div style="font-size: 2.5rem; font-weight: bold;">{{ $violationsByType['minor'] ?? 0 }}</div>
        <div style="font-size: 15px; opacity: 0.95; margin-top: 4px;">Minor Violations</div>
      </div>
      <div style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); padding: 24px; border-radius: 12px; color: white; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
        <div style="font-size: 2.5rem; font-weight: bold;">{{ $violationsByType['major'] ?? 0 }}</div>
        <div style="font-size: 15px; opacity: 0.95; margin-top: 4px;">Major Violations</div>
      </div>
      <div style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); padding: 24px; border-radius: 12px; color: white; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
        <div style="font-size: 2.5rem; font-weight: bold;">{{ $violationsByStatus['resolved'] ?? 0 }}</div>
        <div style="font-size: 15px; opacity: 0.95; margin-top: 4px;">Resolved</div>
      </div>
      <div style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); padding: 24px; border-radius: 12px; color: white; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
        <div style="font-size: 2.5rem; font-weight: bold;">{{ $violationsByStatus['pending'] ?? 0 }}</div>
        <div style="font-size: 15px; opacity: 0.95; margin-top: 4px;">Pending</div>
      </div>
    </div>

    <div class="responsive-table-container">
      <table class="responsive-table">
        <thead>
          <tr>
            <th>Department</th>
            <th>Total Violations</th>
            <th>Minor</th>
            <th>Major</th>
            <th>Resolved</th>
            <th>Pending</th>
          </tr>
        </thead>
        <tbody>
          @foreach($violationsByDepartment as $dept => $data)
          <tr>
            <td style="font-weight: 600;">{{ $dept }}</td>
            <td style="text-align: center; font-weight: bold;">{{ $data['total'] }}</td>
            <td style="text-align: center;">{{ $data['minor'] }}</td>
            <td style="text-align: center; color: #dc3545;">{{ $data['major'] }}</td>
            <td style="text-align: center; color: #28a745;">{{ $data['resolved'] }}</td>
            <td style="text-align: center; color: #ffc107;">{{ $data['pending'] }}</td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>

  <!-- 5. RECEIPTS & PAYMENTS -->
  <div class="header-section" style="margin-bottom: 24px;">
    <h2 style="color: var(--primary-green); margin-bottom: 16px; font-size: 1.4rem; display: flex; align-items: center; gap: 12px;">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 28px; height: 28px;">
        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z" />
      </svg>
      Receipts & Payments
    </h2>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 20px;">
      <div style="background: #e8f5e9; padding: 20px; border-radius: 8px; border-left: 4px solid #4caf50;">
        <div style="font-size: 2rem; font-weight: bold; color: #2e7d32;">{{ $totalReceipts }}</div>
        <div style="font-size: 14px; color: #666;">Total Receipts</div>
      </div>
      <div style="background: #e3f2fd; padding: 20px; border-radius: 8px; border-left: 4px solid #2196f3;">
        <div style="font-size: 2rem; font-weight: bold; color: #1565c0;">₱{{ number_format($totalRevenue, 2) }}</div>
        <div style="font-size: 14px; color: #666;">Total Revenue</div>
      </div>
      <div style="background: #fff3e0; padding: 20px; border-radius: 8px; border-left: 4px solid #ff9800;">
        <div style="font-size: 2rem; font-weight: bold; color: #e65100;">₱{{ number_format($averagePayment, 2) }}</div>
        <div style="font-size: 14px; color: #666;">Average Payment</div>
      </div>
    </div>

    <div class="responsive-table-container">
      <table class="responsive-table">
        <thead>
          <tr>
            <th>Payment Method</th>
            <th>Count</th>
            <th>Total Amount</th>
            <th>Percentage</th>
          </tr>
        </thead>
        <tbody>
          @foreach($receiptsByPaymentMethod as $method => $data)
          <tr>
            <td style="font-weight: 600;">{{ $method ?: 'Not Specified' }}</td>
            <td style="text-align: center;">{{ $data['count'] }}</td>
            <td style="text-align: center;">₱{{ number_format($data['total'], 2) }}</td>
            <td style="text-align: center;">{{ number_format($data['percentage'], 1) }}%</td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>

  <!-- 6. ARCHIVED RECORDS -->
  <div class="header-section" style="margin-bottom: 24px;">
    <h2 style="color: var(--primary-green); margin-bottom: 16px; font-size: 1.4rem; display: flex; align-items: center; gap: 12px;">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 28px; height: 28px;">
        <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
      </svg>
      Archived Records
    </h2>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 16px;">
      <div style="background: #f5f5f5; padding: 20px; border-radius: 8px; border-left: 4px solid #757575;">
        <div style="font-size: 2rem; font-weight: bold; color: #424242;">{{ $archivedAccounts }}</div>
        <div style="font-size: 14px; color: #666;">Archived User Accounts</div>
      </div>
      <div style="background: #fafafa; padding: 20px; border-radius: 8px; border-left: 4px solid #9e9e9e;">
        <div style="font-size: 2rem; font-weight: bold; color: #616161;">{{ $archivedNotifications }}</div>
        <div style="font-size: 14px; color: #666;">Archived Notifications</div>
      </div>
    </div>
  </div>

  <!-- 7. DATABASE TABLES OVERVIEW -->
  <div class="header-section" style="margin-bottom: 24px;">
    <h2 style="color: var(--primary-green); margin-bottom: 16px; font-size: 1.4rem; display: flex; align-items: center; gap: 12px;">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 28px; height: 28px;">
        <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75m-16.5-3.75v3.75m16.5 0v3.75C20.25 16.153 16.556 18 12 18s-8.25-1.847-8.25-4.125v-3.75m16.5 0c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125" />
      </svg>
      Database Tables Overview
    </h2>

    <div class="responsive-table-container">
      <table class="responsive-table">
        <thead>
          <tr>
            <th>Table Name</th>
            <th>Total Records</th>
            <th>Description</th>
          </tr>
        </thead>
        <tbody>
          @foreach($databaseTables as $table)
          <tr>
            <td style="font-family: monospace; font-weight: 600; color: #7B2CBF;">{{ $table['name'] }}</td>
            <td style="text-align: center; font-weight: bold;">{{ number_format($table['count']) }}</td>
            <td style="color: #666; font-size: 13px;">{{ $table['description'] }}</td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>

  <script>
    function downloadPDF() {
      window.location.href = '{{ route("admin.database-summary.pdf") }}';
    }

    function downloadExcel() {
      window.location.href = '{{ route("admin.database-summary.excel") }}';
    }
  </script>

  <style>
    .responsive-table-container {
      overflow-x: auto;
      margin-top: 16px;
    }

    .responsive-table {
      width: 100%;
      border-collapse: collapse;
      background: white;
      border-radius: 8px;
      overflow: hidden;
      box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }

    .responsive-table thead tr {
      background: linear-gradient(135deg, var(--primary-green) 0%, var(--dark-green) 100%);
      color: white;
    }

    .responsive-table th {
      padding: 14px 16px;
      text-align: left;
      font-weight: 600;
      font-size: 14px;
    }

    .responsive-table td {
      padding: 12px 16px;
      border-bottom: 1px solid #e5e7eb;
      font-size: 14px;
    }

    .responsive-table tbody tr:hover {
      background-color: #f8f9fa;
    }

    .responsive-table tbody tr:last-child td {
      border-bottom: none;
    }

    @media (max-width: 768px) {
      .stats-grid {
        grid-template-columns: 1fr;
      }
    }
  </style>
</x-dashboard-layout>
