<x-dashboard-layout>
  <x-slot name="roleTitle">Admin</x-slot>

  <x-slot name="navigation">
    <x-admin-navigation />
  </x-slot>

  <!-- Header Section -->
  <div class="header-section">
    <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 16px;">
      <div style="flex: 1; min-width: 250px;">
        <h1 class="role-title">Admin Dashboard</h1>
        <p class="welcome-text">Welcome back, {{ $admin->fullname ?? 'Admin' }}!</p>
        <p class="welcome-text" style="font-size: 14px; color: #666; margin-top: 4px;">Showing data for: {{ $frequencyLabel }}</p>
        <div class="accent-line"></div>
      </div>

      <!-- Desktop Controls -->
      <div class="desktop-header-controls" style="gap: 12px; align-items: center;">
        <!-- Frequency Filter -->
        <form method="GET" action="{{ route('admin.dashboard') }}" style="display: flex; gap: 8px; align-items: center;">
          <label for="frequency" style="font-size: 14px; color: #666; font-weight: 500;">Filter by:</label>
          <select name="frequency" id="frequency" onchange="this.form.submit()"
                  style="padding: 8px 12px; border: 2px solid #e1e5e9; border-radius: 6px; font-size: 14px; background: white; cursor: pointer;">
            @foreach($frequencyOptions as $value => $label)
              <option value="{{ $value }}" {{ $frequency === $value ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
          </select>
        </form>

        <input type="text" placeholder="Search..." style="padding: 12px 16px; border: 2px solid #e1e5e9; border-radius: 8px; font-size: 14px; width: 250px;">
        <button class="btn-primary" style="display: flex; align-items: center; gap: 8px;">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 18px; height: 18px;">
            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
          </svg>
          Search
        </button>
      </div>

      <!-- Mobile Controls -->
      <div class="mobile-header-controls">
        <button class="mobile-search-toggle" onclick="toggleMobileSearch()" title="Toggle Search">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 20px; height: 20px;">
            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
          </svg>
        </button>
      </div>
    </div>

    <!-- Mobile Search Panel -->
    <div class="mobile-search-panel" id="mobileSearchPanel">
      <form method="GET" action="{{ route('admin.dashboard') }}" style="margin-bottom: 12px;">
        <label for="mobile-frequency" style="display: block; font-size: 14px; color: #666; font-weight: 500; margin-bottom: 8px;">Filter by:</label>
        <select name="frequency" id="mobile-frequency" onchange="this.form.submit()" class="mobile-form-control">
          @foreach($frequencyOptions as $value => $label)
            <option value="{{ $value }}" {{ $frequency === $value ? 'selected' : '' }}>{{ $label }}</option>
          @endforeach
        </select>
      </form>

      <input type="text" placeholder="Search..." class="mobile-form-control">
      <button class="btn-primary mobile-btn" style="display: flex; align-items: center; gap: 8px; justify-content: center;">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 18px; height: 18px;">
          <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
        </svg>
        Search
      </button>
    </div>
  </div>

  <!-- Escalation Alerts -->
  @if($escalationNotifications->count() > 0)
  <div class="header-section" style="background: #fff3cd; border: 1px solid #ffeaa7; border-radius: 8px; margin-bottom: 24px;">
    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 16px; flex-wrap: wrap;">
      <div style="background: #e17055; color: white; padding: 8px; border-radius: 50%; display: flex; align-items: center; justify-content: center; min-width: 40px; min-height: 40px;">
        <svg style="width: 24px; height: 24px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
        </svg>
      </div>
      <div style="flex: 1; min-width: 200px;">
        <h3 style="color: #856404; margin: 0; font-size: 1.2rem; font-weight: 600;">⚠️ Escalation Alerts</h3>
        <p style="color: #856404; margin: 4px 0 0; font-size: 14px;">Students with 3 minor violations (equivalent to 1 major violation)</p>
      </div>
    </div>

    <div style="display: grid; gap: 12px;">
      @foreach($escalationNotifications as $notification)
      <div style="background: white; padding: 16px; border-radius: 8px; border-left: 4px solid #e17055;">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 16px; flex-wrap: wrap;">
          <div style="flex: 1; min-width: 200px;">
            <p style="margin: 0; color: #333; font-weight: 500; line-height: 1.5;">{{ $notification->notif }}</p>
            <p style="margin: 8px 0 0; color: #666; font-size: 12px;">{{ $notification->created_at->format('M j, Y g:i A') }}</p>
          </div>
          <div style="display: flex; gap: 8px; flex-wrap: wrap; min-width: 200px;">
            <button onclick="markAsRead({{ $notification->id }})"
                    style="padding: 8px 12px; background: var(--primary-green); color: white; border: none; border-radius: 4px; font-size: 12px; cursor: pointer; min-height: 36px; flex: 1; font-weight: 600; transition: all 0.3s ease; box-shadow: 0 2px 8px rgba(0, 176, 80, 0.3);" 
                    onmouseover="this.style.background='var(--dark-green)'; this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 12px rgba(0, 176, 80, 0.4)'"
                    onmouseout="this.style.background='var(--primary-green)'; this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 8px rgba(0, 176, 80, 0.3)'"
                    onfocus="this.style.outline='none'; this.style.border='2px solid #fff'; this.style.boxShadow='0 0 0 3px rgba(0, 176, 80, 0.5)'"
                    onblur="this.style.border='none'; this.style.boxShadow='0 2px 8px rgba(0, 176, 80, 0.3)'">
              Mark as Read
            </button>
            <a href="{{ route('admin.violation') }}"
               style="padding: 8px 12px; background: #e17055; color: white; text-decoration: none; border-radius: 4px; font-size: 12px; display: flex; align-items: center; justify-content: center; min-height: 36px; flex: 1; font-weight: 600; transition: all 0.3s ease; box-shadow: 0 2px 8px rgba(225, 112, 85, 0.3); border: 2px solid transparent;"
               onmouseover="this.style.background='#c0392b'; this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 12px rgba(225, 112, 85, 0.4)'"
               onmouseout="this.style.background='#e17055'; this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 8px rgba(225, 112, 85, 0.3)'"
               onfocus="this.style.outline='none'; this.style.border='2px solid #fff'; this.style.boxShadow='0 0 0 3px rgba(225, 112, 85, 0.5)'"
               onblur="this.style.border='2px solid transparent'; this.style.boxShadow='0 2px 8px rgba(225, 112, 85, 0.3)'">
              View Violations
            </a>
          </div>
        </div>
      </div>
      @endforeach
    </div>
  </div>
  @endif

  <!-- Statistics Grid -->
  <div class="stats-grid">
    <!-- SITE -->
    <div class="stat-card" style="border-top-color: #7B2CBF;">
      <div style="display: flex; align-items: center; gap: 16px; flex-wrap: wrap;">
        <div style="flex-shrink: 0;">
          <img src="{{ asset('images/deptLogos/logoSITE.png') }}" alt="SITE Logo" style="height: 60px; width: auto; object-fit: contain; display: block;">
        </div>
        <div style="flex: 1; min-width: 120px;">
          <div class="stat-number">{{ $site }}</div>
          <div class="stat-label">SITE Applications</div>
        </div>
      </div>
    </div>

    <!-- SASTE -->
    <div class="stat-card" style="border-top-color: #0066CC;">
      <div style="display: flex; align-items: center; gap: 16px; flex-wrap: wrap;">
        <div style="flex-shrink: 0;">
          <img src="{{ asset('images/deptLogos/logoSASTE.png') }}" alt="SASTE Logo" style="height: 60px; width: auto; object-fit: contain; display: block;">
        </div>
        <div style="flex: 1; min-width: 120px;">
          <div class="stat-number">{{ $saste }}</div>
          <div class="stat-label">SASTE Applications</div>
        </div>
      </div>
    </div>

    <!-- SBAHM -->
    <div class="stat-card" style="border-top-color: #28A745;">
      <div style="display: flex; align-items: center; gap: 16px; flex-wrap: wrap;">
        <div style="flex-shrink: 0;">
          <img src="{{ asset('images/deptLogos/logoSBAHM.png') }}" alt="SBAHM Logo" style="height: 60px; width: auto; object-fit: contain; display: block;">
        </div>
        <div style="flex: 1; min-width: 120px;">
          <div class="stat-number">{{ $sbahm }}</div>
          <div class="stat-label">SBAHM Applications</div>
        </div>
      </div>
    </div>

    <!-- SNAHS -->
    <div class="stat-card" style="border-top-color: #DC3545;">
      <div style="display: flex; align-items: center; gap: 16px; flex-wrap: wrap;">
        <div style="flex-shrink: 0;">
          <img src="{{ asset('images/deptLogos/logoSNAHS.png') }}" alt="SNAHS Logo" style="height: 60px; width: auto; object-fit: contain; display: block;">
        </div>
        <div style="flex: 1; min-width: 120px;">
          <div class="stat-number">{{ $snahs }}</div>
          <div class="stat-label">SNAHS Applications</div>
        </div>
      </div>
    </div>

    <!-- SOM -->
    <div class="stat-card" style="border-top-color: #FFC107;">
      <div style="display: flex; align-items: center; gap: 16px; flex-wrap: wrap;">
        <div style="flex-shrink: 0;">
          <img src="{{ asset('images/deptLogos/logoSOM.png') }}" alt="SOM Logo" style="height: 60px; width: auto; object-fit: contain; display: block;">
        </div>
        <div style="flex: 1; min-width: 120px;">
          <div class="stat-number">{{ $som }}</div>
          <div class="stat-label">SOM Applications</div>
        </div>
      </div>
    </div>

    <!-- Graduate School -->
    <div class="stat-card" style="border-top-color: #6F42C1;">
      <div style="display: flex; align-items: center; gap: 16px; flex-wrap: wrap;">
        <div style="flex-shrink: 0;">
          <img src="{{ asset('images/deptLogos/logoGRADSCH.jpg') }}" alt="Graduate School Logo" style="height: 60px; width: auto; object-fit: contain; display: block;">
        </div>
        <div style="flex: 1; min-width: 120px;">
          <div class="stat-number">{{ $gradsch }}</div>
          <div class="stat-label">Graduate School Applications</div>
        </div>
      </div>
    </div>

  </div>

  <!-- Violations Overview -->
  <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 24px; margin-bottom: 24px;">
    <!-- Minor Violations Chart -->
    <div class="header-section">
      <h3 style="color: var(--primary-green); margin-bottom: 16px; font-size: 1.2rem;">Minor Violations Overview</h3>
      <div style="display: flex; align-items: center; gap: 24px; flex-wrap: wrap;">
        <div style="position: relative; width: 120px; height: 120px; flex-shrink: 0;">
          <svg width="120" height="120" style="transform: rotate(-90deg);">
            <circle cx="60" cy="60" r="50" fill="none" stroke="#e5e7eb" stroke-width="8"/>
            <circle cx="60" cy="60" r="50" fill="none" stroke="var(--primary-green)" stroke-width="8"
                    stroke-dasharray="{{ $minorResolvedPercentage * 3.14 }} 314" stroke-linecap="round"/>
          </svg>
          <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); text-align: center;">
            <div style="font-size: 1.5rem; font-weight: bold; color: var(--primary-green);">{{ $minorResolvedPercentage }}%</div>
            <div style="font-size: 0.8rem; color: #666;">Resolved</div>
          </div>
        </div>
        <div style="flex: 1; min-width: 150px;">
          <div style="margin-bottom: 8px;">
            <span style="color: var(--primary-green); font-weight: 600;">Resolved:</span> {{ $minorResolved }}
          </div>
          <div style="margin-bottom: 8px;">
            <span style="color: #e74c3c; font-weight: 600;">Pending:</span> {{ $minorPending }}
          </div>
          <div style="font-weight: 600; color: #333;">
            <span>Total:</span> {{ $minorTotal }}
          </div>
        </div>
      </div>
    </div>

    <!-- Major Violations Chart -->
    <div class="header-section">
      <h3 style="color: var(--primary-green); margin-bottom: 16px; font-size: 1.2rem;">Major Violations Overview</h3>
      <div style="display: flex; align-items: center; gap: 24px; flex-wrap: wrap;">
        <div style="position: relative; width: 120px; height: 120px; flex-shrink: 0;">
          <svg width="120" height="120" style="transform: rotate(-90deg);">
            <circle cx="60" cy="60" r="50" fill="none" stroke="#e5e7eb" stroke-width="8"/>
            <circle cx="60" cy="60" r="50" fill="none" stroke="#e74c3c" stroke-width="8"
                    stroke-dasharray="{{ $majorResolvedPercentage * 3.14 }} 314" stroke-linecap="round"/>
          </svg>
          <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); text-align: center;">
            <div style="font-size: 1.5rem; font-weight: bold; color: #e74c3c;">{{ $majorResolvedPercentage }}%</div>
            <div style="font-size: 0.8rem; color: #666;">Resolved</div>
          </div>
        </div>
        <div style="flex: 1; min-width: 150px;">
          <div style="margin-bottom: 8px;">
            <span style="color: var(--primary-green); font-weight: 600;">Resolved:</span> {{ $majorResolved }}
          </div>
          <div style="margin-bottom: 8px;">
            <span style="color: #e74c3c; font-weight: 600;">Pending:</span> {{ $majorPending }}
          </div>
          <div style="font-weight: 600; color: #333;">
            <span>Total:</span> {{ $majorTotal }}
          </div>
        </div>
      </div>
    </div>
  </div>



  <!-- Overall Report on Minor Offenses - Trends Analysis -->
  <div class="header-section">
    <h3 style="color: var(--primary-green); margin-bottom: 20px; font-size: 1.3rem;">Overall Report on Minor Offenses as of {{ date('F Y') }}</h3>

    <!-- Summary Table -->
    <div style="margin-bottom: 30px;">
      <div class="responsive-table-container">
        <table class="responsive-table" style="width: 100%; border-collapse: collapse; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
          <thead>
            <tr style="background: linear-gradient(135deg, #17a2b8 0%, #138496 100%); color: white;">
              <th style="padding: 16px; text-align: left; font-weight: 600;">Department</th>
              <th style="padding: 16px; text-align: center; font-weight: 600;">Total Population</th>
              <th style="padding: 16px; text-align: center; font-weight: 600;">Number of Student Violators (AY 2023–2024)</th>
              <th style="padding: 16px; text-align: center; font-weight: 600;">Number of Student Violators (AY 2024–2025)</th>
              <th style="padding: 16px; text-align: center; font-weight: 600;">Number of Student Violators (AY 2025–2026)</th>
              <th style="padding: 16px; text-align: center; font-weight: 600;">Variance (%)</th>
              <th style="padding: 16px; text-align: center; font-weight: 600;">Trend</th>
            </tr>
          </thead>
          <tbody>
            @foreach($minorOffensesData['departments_data'] as $dept => $data)
            <tr style="border-bottom: 1px solid #e5e7eb; transition: background-color 0.2s ease;"
                onmouseover="this.style.backgroundColor='#f8f9fa'"
                onmouseout="this.style.backgroundColor='white'">
              <td style="padding: 16px; font-weight: 600; color: 
                @switch($dept)
                  @case('SITE') #7B2CBF; @break
                  @case('SBAHM') #28A745; @break
                  @case('SNAHS') #DC3545; @break
                  @case('SASTE') #0066CC; @break
                  @default #2c3e50;
                @endswitch
                ">{{ $dept }}</td>
              <td style="padding: 16px; text-align: center; font-weight: 600; color: #6c757d;">{{ number_format($data['total_population']) }}</td>
              <td style="padding: 16px; text-align: center;">{{ $data['violators_2023_2024'] }}</td>
              <td style="padding: 16px; text-align: center;">{{ $data['violators_june_2025'] }}</td>
              <td style="padding: 16px; text-align: center;">{{ $data['current_violators'] }}</td>
              <td style="padding: 16px; text-align: center;">
                <span style="color: {{ $data['variance_percentage'] > 0 ? '#27ae60' : ($data['variance_percentage'] < 0 ? '#e74c3c' : '#6c757d') }}; font-weight: 600;">
                  {{ number_format($data['variance_percentage'], 2) }}%
                </span>
              </td>
              <td style="padding: 16px; text-align: center;">
                @if($data['violators_june_2025'] > $data['violators_2023_2024'])
                  <span style="color: #e74c3c; font-weight: 600;">↗ Increasing</span>
                @elseif($data['violators_june_2025'] < $data['violators_2023_2024'])
                  <span style="color: #27ae60; font-weight: 600;">↘ Decreasing</span>
                @else
                  <span style="color: #6c757d; font-weight: 600;">→ Stable</span>
                @endif
              </td>
            </tr>
            @endforeach
            <!-- Summary Row -->
            <tr style="background: #f8f9fa; border-top: 2px solid #17a2b8; font-weight: 600;">
              <td style="padding: 16px; color: #17a2b8;">TOTAL</td>
              <td style="padding: 16px; text-align: center; color: #17a2b8;">{{ number_format($minorOffensesData['total_summary']['total_population']) }}</td>
              <td style="padding: 16px; text-align: center;">{{ $minorOffensesData['total_summary']['total_violators_2023_2024'] }}</td>
              <td style="padding: 16px; text-align: center;">{{ $minorOffensesData['total_summary']['total_violators_june_2025'] }}</td>
              <td style="padding: 16px; text-align: center;">{{ $minorOffensesData['total_summary']['total_current_violators'] }}</td>
              <td style="padding: 16px; text-align: center;">
                <span style="color: {{ $minorOffensesData['total_summary']['total_variance'] > 0 ? '#e74c3c' : ($minorOffensesData['total_summary']['total_variance'] < 0 ? '#27ae60' : '#6c757d') }};">
                  {{ $minorOffensesData['total_summary']['total_variance'] > 0 ? '+' : '' }}{{ $minorOffensesData['total_summary']['total_variance'] }}
                </span>
              </td>
              <td style="padding: 16px; text-align: center;">
                @php
                  $totalTrend = $minorOffensesData['total_summary']['total_variance'] > 0 ? 'increase' : ($minorOffensesData['total_summary']['total_variance'] < 0 ? 'decrease' : 'stable');
                @endphp
                @if($totalTrend == 'increase')
                  <span style="color: #e74c3c;">↗ Increasing</span>
                @elseif($totalTrend == 'decrease')
                  <span style="color: #27ae60;">↘ Decreasing</span>
                @else
                  <span style="color: #6c757d;">→ Stable</span>
                @endif
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Interactive Bar Chart for Minor Offenses
    <div style="background: white; border-radius: 12px; padding: 24px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);">
      <h4 style="color: #17a2b8; margin-bottom: 20px; font-size: 1.1rem;">Minor Offenses Comparison Chart</h4>
      <div class="chart-container" style="position: relative; height: 400px;">
        <canvas id="minorOffensesChart"></canvas>
      </div>
    </div>
  </div>
  -->

  <!-- Overall Report on Major Offenses - Trends Analysis -->
  <div class="header-section">
    <h3 style="color: var(--primary-green); margin-bottom: 20px; font-size: 1.3rem;">Overall Report on Major Offenses - Trends Analysis</h3>

    <!-- Summary Table -->
    <div style="margin-bottom: 30px;">
      <div class="responsive-table-container">
        <table class="responsive-table" style="width: 100%; border-collapse: collapse; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
          <thead>
            <tr style="background: linear-gradient(135deg, var(--primary-green) 0%, var(--dark-green) 100%); color: white;">
              <th style="padding: 16px; text-align: left; font-weight: 600;">Department</th>
              <th style="padding: 16px; text-align: center; font-weight: 600;">Total Population</th>
              <th style="padding: 16px; text-align: center; font-weight: 600;">Number of Student Violators (AY 2023–2024)</th>
              <th style="padding: 16px; text-align: center; font-weight: 600;">Number of Student Violators (AY 2024–2025)</th>
              <th style="padding: 16px; text-align: center; font-weight: 600;">Number of Student Violators (AY 2025–2026)</th>
              <th style="padding: 16px; text-align: center; font-weight: 600;">Variance (%)</th>
              <th style="padding: 16px; text-align: center; font-weight: 600;">Trend</th>
            </tr>
          </thead>
          <tbody>
            @foreach($trendsData['departments_data'] as $dept => $data)
            <tr style="border-bottom: 1px solid #e5e7eb; transition: background-color 0.2s ease;"
                onmouseover="this.style.backgroundColor='#f8f9fa'"
                onmouseout="this.style.backgroundColor='white'">
              <td style="padding: 16px; font-weight: 600; color: #2c3e50;">{{ $dept }}</td>
              <td style="padding: 16px; text-align: center;">{{ number_format($data['total_population']) }}</td>
              <td style="padding: 16px; text-align: center;">{{ $data['violators_2023_2024'] }}</td>
              <td style="padding: 16px; text-align: center;">{{ $data['violators_june_2025'] }}</td>
              <td style="padding: 16px; text-align: center;">{{ $data['current_violators'] }}</td>
              <td style="padding: 16px; text-align: center;">
                <span style="color: {{ $data['variance_percentage_june'] > 0 ? '#27ae60' : ($data['variance_percentage_june'] < 0 ? '#e74c3c' : '#6c757d') }}; font-weight: 600;">
                  {{ number_format($data['variance_percentage_june'], 2) }}%
                </span>
              </td>
              <td style="padding: 16px; text-align: center;">
                @if($data['violators_june_2025'] > $data['violators_2023_2024'])
                  <span style="color: #e74c3c; font-weight: 600;">↗ Increasing</span>
                @elseif($data['violators_june_2025'] < $data['violators_2023_2024'])
                  <span style="color: #27ae60; font-weight: 600;">↘ Decreasing</span>
                @else
                  <span style="color: #6c757d; font-weight: 600;">→ Stable</span>
                @endif
              </td>
            </tr>
            @endforeach
            <!-- Summary Row -->
            <tr style="background: #f8f9fa; border-top: 2px solid var(--primary-green); font-weight: 600;">
              <td style="padding: 16px; color: #2c3e50; font-weight: 600;">TOTAL</td>
              <td style="padding: 16px; text-align: center;">{{ number_format($trendsData['total_summary']['total_population']) }}</td>
              <td style="padding: 16px; text-align: center;">{{ $trendsData['total_summary']['total_violators_2023_2024'] }}</td>
              <td style="padding: 16px; text-align: center;">{{ $trendsData['total_summary']['total_violators_june_2025'] }}</td>
              <td style="padding: 16px; text-align: center;">{{ $trendsData['total_summary']['total_current_violators'] }}</td>
              <td style="padding: 16px; text-align: center;">
                <span style="color: {{ $trendsData['total_summary']['total_variance_june'] > 0 ? '#e74c3c' : ($trendsData['total_summary']['total_variance_june'] < 0 ? '#27ae60' : '#6c757d') }};">
                  {{ $trendsData['total_summary']['total_variance_june'] > 0 ? '+' : '' }}{{ $trendsData['total_summary']['total_variance_june'] }}
                </span>
              </td>
              <td style="padding: 16px; text-align: center;">
                @php
                  $totalTrend = $trendsData['total_summary']['total_variance_june'] > 0 ? 'increase' : ($trendsData['total_summary']['total_variance_june'] < 0 ? 'decrease' : 'stable');
                @endphp
                @if($totalTrend == 'increase')
                  <span style="color: #e74c3c;">↗ Increasing</span>
                @elseif($totalTrend == 'decrease')
                  <span style="color: #27ae60;">↘ Decreasing</span>
                @else
                  <span style="color: #6c757d;">→ Stable</span>
                @endif
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Interactive Line Chart
    <div style="background: white; border-radius: 12px; padding: 24px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);">
      <h4 style="color: var(--primary-green); margin-bottom: 20px; font-size: 1.1rem;">Major Violations Trend Analysis</h4>
      <div class="chart-container" style="position: relative; height: 400px;">
        <canvas id="trendsChart"></canvas>
      </div>
    </div> -->
  </div>

  <!-- JavaScript for escalation notifications and trends chart -->
  <script>
    // Mobile search toggle functionality
    function toggleMobileSearch() {
      const searchPanel = document.getElementById('mobileSearchPanel');
      searchPanel.classList.toggle('active');
    }

    function markAsRead(notificationId) {
      fetch(`/admin/notification/${notificationId}/mark-read`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          // Remove the notification from the UI
          const notificationElement = event.target.closest('[style*="border-left: 4px solid #e17055"]');
          if (notificationElement) {
            notificationElement.style.transition = 'opacity 0.3s ease';
            notificationElement.style.opacity = '0';
            setTimeout(() => {
              notificationElement.remove();
              // Check if there are no more notifications and hide the entire section
              const remainingNotifications = document.querySelectorAll('[style*="border-left: 4px solid #e17055"]');
              if (remainingNotifications.length === 0) {
                const alertSection = document.querySelector('[style*="background: #fff3cd"]');
                if (alertSection) {
                  alertSection.style.display = 'none';
                }
              }
            }, 300);
          }
        }
      })
      .catch(error => {
        console.error('Error marking notification as read:', error);
      });
    }

    // Handle responsive chart sizing
    function handleChartResize() {
      const chartContainers = document.querySelectorAll('.chart-container');
      chartContainers.forEach(container => {
        if (window.innerWidth <= 768) {
          container.style.height = '300px';
        } else if (window.innerWidth <= 480) {
          container.style.height = '250px';
        } else {
          container.style.height = '400px';
        }
      });
    }

    // Initialize responsive features
    document.addEventListener('DOMContentLoaded', function() {
      handleChartResize();

      // Handle window resize for charts
      window.addEventListener('resize', handleChartResize);

      // Close mobile search when clicking outside
      document.addEventListener('click', function(event) {
        const searchPanel = document.getElementById('mobileSearchPanel');
        const searchToggle = event.target.closest('.mobile-search-toggle');

        if (!searchToggle && !searchPanel.contains(event.target)) {
          searchPanel.classList.remove('active');
        }
      });
    });

    // Initialize Minor Offenses Chart
    document.addEventListener('DOMContentLoaded', function() {
      // Minor Offenses Chart
      const minorCtx = document.getElementById('minorOffensesChart').getContext('2d');

      const minorOffensesData = {
        labels: ['SITE', 'SBAHM', 'SNAHS', 'SASTE'],
        datasets: [
          {
            label: 'A.Y. 2023-2024',
            data: [118, 88, 524, 97],
            backgroundColor: 'rgba(23, 162, 184, 0.8)',
            borderColor: 'rgba(23, 162, 184, 1)',
            borderWidth: 2,
            borderRadius: 4,
            borderSkipped: false,
          },
          {
            label: 'As of June 2025',
            data: [23, 21, 77, 12],
            backgroundColor: 'rgba(40, 167, 69, 0.8)',
            borderColor: 'rgba(40, 167, 69, 1)',
            borderWidth: 2,
            borderRadius: 4,
            borderSkipped: false,
          }
        ]
      };

      const minorOffensesChart = new Chart(minorCtx, {
        type: 'bar',
        data: minorOffensesData,
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            title: {
              display: true,
              text: 'Minor Offenses Comparison by Department',
              font: {
                size: 16,
                weight: 'bold'
              },
              color: '#17a2b8'
            },
            legend: {
              display: true,
              position: 'top',
              labels: {
                usePointStyle: true,
                padding: 20,
                font: {
                  size: 12,
                  weight: '600'
                }
              }
            },
            tooltip: {
              backgroundColor: 'rgba(0, 0, 0, 0.8)',
              titleColor: '#ffffff',
              bodyColor: '#ffffff',
              borderColor: 'rgba(23, 162, 184, 1)',
              borderWidth: 2,
              cornerRadius: 8,
              displayColors: true,
              callbacks: {
                label: function(context) {
                  return context.dataset.label + ': ' + context.parsed.y + ' violator(s)';
                },
                afterBody: function(context) {
                  if (context.length === 2) {
                    const current = context[1].parsed.y;
                    const previous = context[0].parsed.y;
                    const variance = current - previous;
                    const percentage = previous > 0 ? ((variance / previous) * 100).toFixed(1) : 0;
                    return ['', 'Variance: ' + (variance > 0 ? '+' : '') + variance + ' (' + (variance > 0 ? '+' : '') + percentage + '%)'];
                  }
                  return '';
                }
              }
            }
          },
          scales: {
            x: {
              display: true,
              title: {
                display: true,
                text: 'Department',
                font: {
                  size: 14,
                  weight: 'bold'
                },
                color: '#2c3e50'
              },
              grid: {
                display: false
              },
              ticks: {
                color: '#666',
                font: {
                  size: 11,
                  weight: '600'
                }
              }
            },
            y: {
              display: true,
              title: {
                display: true,
                text: 'Number of Violators',
                font: {
                  size: 14,
                  weight: 'bold'
                },
                color: '#2c3e50'
              },
              grid: {
                color: 'rgba(0, 0, 0, 0.1)',
                borderDash: [5, 5]
              },
              ticks: {
                color: '#666',
                font: {
                  size: 11
                },
                beginAtZero: true
              }
            }
          },
          interaction: {
            mode: 'index',
            intersect: false,
          }
        }
      });

      // Initialize Major Offenses Trends Chart
      const ctx = document.getElementById('trendsChart').getContext('2d');

      // Department colors matching the dashboard theme
      const departmentColors = {
        'SITE': '#7B2CBF',    // Purple
        'SASTE': '#0066CC',   // Blue
        'SBAHM': '#28A745',   // Green
        'SNAHS': '#DC3545'    // Red
      };

      // Chart data from PHP
      const chartLabels = @json($trendsData['chart_labels']);
      const chartDatasets = @json($trendsData['chart_datasets']);

      // Prepare datasets for Chart.js
      const datasets = [
        {
          label: 'SITE',
          data: chartDatasets.SITE,
          borderColor: departmentColors.SITE,
          backgroundColor: departmentColors.SITE + '20',
          borderWidth: 3,
          fill: false,
          tension: 0.4,
          pointRadius: 6,
          pointHoverRadius: 8,
          pointBackgroundColor: departmentColors.SITE,
          pointBorderColor: '#ffffff',
          pointBorderWidth: 2,
          pointHoverBackgroundColor: departmentColors.SITE,
          pointHoverBorderColor: '#ffffff',
          pointHoverBorderWidth: 3
        },
        {
          label: 'SASTE',
          data: chartDatasets.SASTE,
          borderColor: departmentColors.SASTE,
          backgroundColor: departmentColors.SASTE + '20',
          borderWidth: 3,
          fill: false,
          tension: 0.4,
          pointRadius: 6,
          pointHoverRadius: 8,
          pointBackgroundColor: departmentColors.SASTE,
          pointBorderColor: '#ffffff',
          pointBorderWidth: 2,
          pointHoverBackgroundColor: departmentColors.SASTE,
          pointHoverBorderColor: '#ffffff',
          pointHoverBorderWidth: 3
        },
        {
          label: 'SBAHM',
          data: chartDatasets.SBAHM,
          borderColor: departmentColors.SBAHM,
          backgroundColor: departmentColors.SBAHM + '20',
          borderWidth: 3,
          fill: false,
          tension: 0.4,
          pointRadius: 6,
          pointHoverRadius: 8,
          pointBackgroundColor: departmentColors.SBAHM,
          pointBorderColor: '#ffffff',
          pointBorderWidth: 2,
          pointHoverBackgroundColor: departmentColors.SBAHM,
          pointHoverBorderColor: '#ffffff',
          pointHoverBorderWidth: 3
        },
        {
          label: 'SNAHS',
          data: chartDatasets.SNAHS,
          borderColor: departmentColors.SNAHS,
          backgroundColor: departmentColors.SNAHS + '20',
          borderWidth: 3,
          fill: false,
          tension: 0.4,
          pointRadius: 6,
          pointHoverRadius: 8,
          pointBackgroundColor: departmentColors.SNAHS,
          pointBorderColor: '#ffffff',
          pointBorderWidth: 2,
          pointHoverBackgroundColor: departmentColors.SNAHS,
          pointHoverBorderColor: '#ffffff',
          pointHoverBorderWidth: 3
        }
      ];

      const trendsChart = new Chart(ctx, {
        type: 'line',
        data: {
          labels: chartLabels,
          datasets: datasets
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            title: {
              display: true,
              text: 'Major Violations Trend by Department',
              font: {
                size: 16,
                weight: 'bold'
              },
              color: '#2c3e50'
            },
            legend: {
              display: true,
              position: 'top',
              labels: {
                usePointStyle: true,
                padding: 20,
                font: {
                  size: 12,
                  weight: '600'
                }
              }
            },
            tooltip: {
              mode: 'index',
              intersect: false,
              backgroundColor: 'rgba(0, 0, 0, 0.8)',
              titleColor: '#ffffff',
              bodyColor: '#ffffff',
              borderColor: 'rgba(0, 176, 80, 1)',
              borderWidth: 2,
              cornerRadius: 8,
              displayColors: true,
              callbacks: {
                title: function(context) {
                  return 'Month: ' + context[0].label;
                },
                label: function(context) {
                  return context.dataset.label + ': ' + context.parsed.y + ' violator(s)';
                },
                afterBody: function(context) {
                  const total = context.reduce((sum, item) => sum + item.parsed.y, 0);
                  return ['', 'Total: ' + total + ' violator(s)'];
                }
              }
            }
          },
          scales: {
            x: {
              display: true,
              title: {
                display: true,
                text: 'Month',
                font: {
                  size: 14,
                  weight: 'bold'
                },
                color: '#2c3e50'
              },
              grid: {
                color: 'rgba(0, 0, 0, 0.1)',
                borderDash: [5, 5]
              },
              ticks: {
                color: '#666',
                font: {
                  size: 11
                }
              }
            },
            y: {
              display: true,
              title: {
                display: true,
                text: 'Number of Violators',
                font: {
                  size: 14,
                  weight: 'bold'
                },
                color: '#2c3e50'
              },
              beginAtZero: true,
              grid: {
                color: 'rgba(0, 0, 0, 0.1)',
                borderDash: [5, 5]
              },
              ticks: {
                color: '#666',
                font: {
                  size: 11
                },
                stepSize: 1
              }
            }
          },
          interaction: {
            mode: 'nearest',
            axis: 'x',
            intersect: false
          },
          hover: {
            mode: 'nearest',
            intersect: false,
            animationDuration: 200
          },
          animation: {
            duration: 1000,
            easing: 'easeInOutQuart'
          }
        }
      });

      // Add click event for data points
      ctx.canvas.addEventListener('click', function(event) {
        const points = trendsChart.getElementsAtEventForMode(event, 'nearest', { intersect: true }, true);
        if (points.length) {
          const firstPoint = points[0];
          const label = trendsChart.data.labels[firstPoint.index];
          const dataset = trendsChart.data.datasets[firstPoint.datasetIndex];
          const value = dataset.data[firstPoint.index];

          alert(`${dataset.label} in ${label}: ${value} violator(s)`);
        }
      });
    });
  </script>

</x-dashboard-layout>
