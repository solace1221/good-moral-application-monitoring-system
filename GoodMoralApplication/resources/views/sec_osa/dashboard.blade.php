<x-dashboard-layout>
  <x-slot name="roleTitle">Moderator</x-slot>

  <x-slot name="navigation">
    <x-moderator-navigation />
  </x-slot>

  <!-- Header Section -->
  <div class="header-section">
    <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 16px;">
      <div style="flex: 1; min-width: 250px;">
        <h1 class="role-title">Moderator Dashboard</h1>
        <p class="welcome-text">Welcome back, {{ auth()->user()->fullname ?? 'Moderator' }}!</p>
        <p class="welcome-text" style="font-size: 14px; color: #666; margin-top: 4px;">Showing data for: {{ $frequencyLabel ?? 'Current Month' }}</p>
        <div class="accent-line"></div>
      </div>

      <!-- Desktop Controls -->
      <div class="desktop-header-controls" style="gap: 12px; align-items: center;">
        <!-- Frequency Filter -->
        <form method="GET" action="{{ route('sec_osa.dashboard') }}" style="display: flex; gap: 8px; align-items: center;">
          <label for="frequency" style="font-size: 14px; color: #666; font-weight: 500;">Filter by:</label>
          <select name="frequency" id="frequency" onchange="this.form.submit()"
                  style="padding: 8px 12px; border: 2px solid #e1e5e9; border-radius: 6px; font-size: 14px; background: white; cursor: pointer;">
            @foreach($frequencyOptions ?? [] as $value => $label)
              <option value="{{ $value }}" {{ ($frequency ?? 'monthly') === $value ? 'selected' : '' }}>{{ $label }}</option>
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
      <form method="GET" action="{{ route('sec_osa.dashboard') }}" style="margin-bottom: 12px;">
        <label for="mobile-frequency" style="display: block; font-size: 14px; color: #666; font-weight: 500; margin-bottom: 8px;">Filter by:</label>
        <select name="frequency" id="mobile-frequency" onchange="this.form.submit()" class="mobile-form-control">
          @foreach($frequencyOptions ?? [] as $value => $label)
            <option value="{{ $value }}" {{ ($frequency ?? 'monthly') === $value ? 'selected' : '' }}>{{ $label }}</option>
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

  <!-- Escalation Alerts section removed as requested -->

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
            <circle cx="60" cy="60" r="50" fill="none" stroke="#ffc107" stroke-width="8"
                    stroke-dasharray="{{ $minorResolvedPercentage * 3.14 }} 314" stroke-linecap="round"/>
          </svg>
          <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); text-align: center;">
            <div style="font-size: 1.5rem; font-weight: bold; color: #ffc107;">{{ $minorResolvedPercentage }}%</div>
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

  <!-- Violations by Department - Overall Report -->
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
  </div>

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
  </div>

  <!-- Applications by Department section removed as requested -->  <!-- JavaScript for mobile search and notification handling -->
  <script>
    // Mobile search toggle functionality
    function toggleMobileSearch() {
      const searchPanel = document.getElementById('mobileSearchPanel');
      searchPanel.classList.toggle('active');
    }

    function markAsRead(notificationId) {
      fetch(`/sec_osa/notification/${notificationId}/mark-read`, {
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

    // Handle responsive behavior
    document.addEventListener('DOMContentLoaded', function() {
      // Close mobile search when clicking outside
      document.addEventListener('click', function(event) {
        const searchPanel = document.getElementById('mobileSearchPanel');
        const searchToggle = event.target.closest('.mobile-search-toggle');

        if (!searchToggle && searchPanel && !searchPanel.contains(event.target)) {
          searchPanel.classList.remove('active');
        }
      });
    });
  </script>

</x-dashboard-layout>
