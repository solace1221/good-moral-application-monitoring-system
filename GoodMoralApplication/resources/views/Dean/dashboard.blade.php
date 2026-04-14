<x-dashboard-layout>
  <x-slot name="roleTitle">Dean</x-slot>

  <x-slot name="navigation">
    <x-dean-navigation />
  </x-slot>

  <!-- Header Section -->
  <div class="header-section">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
      <div>
        <h1 class="role-title">Dean Dashboard</h1>
        <p class="welcome-text">{{ $department }} Department Overview</p>
        <p class="welcome-text" style="font-size: 14px; color: #666; margin-top: 4px;">Showing data for: {{ $frequencyLabel }}</p>
        <div class="accent-line"></div>
      </div>
      <div style="display: flex; gap: 12px; align-items: center;">
        <!-- Frequency Filter -->
        <form method="GET" action="{{ route('dean.dashboard') }}" style="display: flex; gap: 8px; align-items: center;">
          <label for="frequency" style="font-size: 14px; color: #666; font-weight: 500;">Filter by:</label>
          <select name="frequency" id="frequency" onchange="this.form.submit()"
                  style="padding: 8px 12px; border: 2px solid #e1e5e9; border-radius: 6px; font-size: 14px; background: white; cursor: pointer;">
            @foreach($frequencyOptions as $value => $label)
              <option value="{{ $value }}" {{ $frequency === $value ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
          </select>
        </form>
      </div>
    </div>
  </div>

  <!-- Application Status Cards -->
  <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 32px;">
    <!-- Good Moral Applications -->
    <div class="stat-card" style="border-top-color: #10B981;">
      <div style="display: flex; align-items: center; gap: 16px;">
        <div style="display: flex; align-items: center; justify-content: center; height: 60px; width: 60px; border-radius: 50%; background: #10B981; color: white;">
          <svg style="width: 24px; height: 24px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
          </svg>
        </div>
        <div>
          <div class="stat-number">{{ $pendingGoodMoralApplications }}</div>
          <div class="stat-label">Pending Good Moral Applications</div>
        </div>
      </div>
    </div>

    <!-- Residency Applications -->
    <div class="stat-card" style="border-top-color: #3B82F6;">
      <div style="display: flex; align-items: center; gap: 16px;">
        <div style="display: flex; align-items: center; justify-content: center; height: 60px; width: 60px; border-radius: 50%; background: #3B82F6; color: white;">
          <svg style="width: 24px; height: 24px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
          </svg>
        </div>
        <div>
          <div class="stat-number">{{ $pendingResidencyApplications }}</div>
          <div class="stat-label">Pending Residency Applications</div>
        </div>
      </div>
    </div>

    <!-- Total Pending Applications -->
    <div class="stat-card" style="border-top-color: #F59E0B;">
      <div style="display: flex; align-items: center; gap: 16px;">
        <div style="display: flex; align-items: center; justify-content: center; height: 60px; width: 60px; border-radius: 50%; background: #F59E0B; color: white;">
          <svg style="width: 24px; height: 24px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
          </svg>
        </div>
        <div>
          <div class="stat-number">{{ $pendingGoodMoralApplications + $pendingResidencyApplications }}</div>
          <div class="stat-label">Total Pending Applications</div>
        </div>
      </div>
    </div>
  </div>

  <!-- Program Statistics -->
  @if ($department === 'SITE')
  <div class="stats-grid">
    @foreach ($programs as $program)
    <div class="stat-card" style="border-top-color: #7B2CBF;">
      <div style="display: flex; align-items: center; gap: 16px;">
        <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; height: 60px; width: 60px; border-radius: 50%; background: #7B2CBF; color: white; font-weight: 700; font-size: 12px;">
          <span>{{ $program['abbr1'] }}</span>
          <span>{{ $program['abbr2'] }}</span>
        </div>
        <div>
          <div class="stat-number">{{ $program['count'] }}</div>
          <div class="stat-label">{{ $program['name'] ?? 'Applications' }}</div>
        </div>
      </div>
    </div>
    @endforeach
  </div>
  @endif

  @if ($department === 'SNAHS')
  <div class="stats-grid">
    @foreach ($programs as $program)
    <div class="stat-card" style="border-top-color: #DC3545;">
      <div style="display: flex; align-items: center; gap: 16px;">
        <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; height: 60px; width: 60px; border-radius: 50%; background: #DC3545; color: white; font-weight: 700; font-size: 12px;">
          <span>{{ $program['abbr1'] }}</span>
          <span>{{ $program['abbr2'] }}</span>
        </div>
        <div>
          <div class="stat-number">{{ $program['count'] }}</div>
          <div class="stat-label">{{ $program['name'] ?? 'Applications' }}</div>
        </div>
      </div>
    </div>
    @endforeach
  </div>
  @endif

  @if ($department === 'SBAHM')
  <div class="stats-grid">
    @if(isset($programsRow1))
      @foreach ($programsRow1 as $program)
      <div class="stat-card" style="border-top-color: #28A745;">
        <div style="display: flex; align-items: center; gap: 16px;">
          <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; height: 60px; width: 60px; border-radius: 50%; background: #28A745; color: white; font-weight: 700; font-size: 12px;">
            <span>{{ $program['abbr1'] }}</span>
            <span>{{ $program['abbr2'] }}</span>
          </div>
          <div>
            <div class="stat-number">{{ $program['count'] }}</div>
            <div class="stat-label">{{ $program['name'] ?? 'Applications' }}</div>
          </div>
        </div>
      </div>
      @endforeach
    @endif

    @if(isset($programsRow2))
      @foreach ($programsRow2 as $program)
      <div class="stat-card" style="border-top-color: #28A745;">
        <div style="display: flex; align-items: center; gap: 16px;">
          <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; height: 60px; width: 60px; border-radius: 50%; background: #28A745; color: white; font-weight: 700; font-size: 12px;">
            <span>{{ $program['abbr1'] }}</span>
            <span>{{ $program['abbr2'] }}</span>
          </div>
          <div>
            <div class="stat-number">{{ $program['count'] }}</div>
            <div class="stat-label">{{ $program['name'] ?? 'Applications' }}</div>
          </div>
        </div>
      </div>
      @endforeach
    @endif
  </div>
  @endif

  @if ($department === 'SASTE')
  <div class="stats-grid">
    @if(isset($programsRow1))
      @foreach ($programsRow1 as $program)
      <div class="stat-card" style="border-top-color: #0066CC;">
        <div style="display: flex; align-items: center; gap: 16px;">
          <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; height: 60px; width: 60px; border-radius: 50%; background: #0066CC; color: white; font-weight: 700; font-size: 12px;">
            <span>{{ $program['abbr1'] }}</span>
            <span>{{ $program['abbr2'] }}</span>
          </div>
          <div>
            <div class="stat-number">{{ $program['count'] }}</div>
            <div class="stat-label">{{ $program['name'] ?? 'Applications' }}</div>
          </div>
        </div>
      </div>
      @endforeach
    @endif

    @if(isset($programsRow2))
      @foreach ($programsRow2 as $program)
      <div class="stat-card" style="border-top-color: #0066CC;">
        <div style="display: flex; align-items: center; gap: 16px;">
          <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; height: 60px; width: 60px; border-radius: 50%; background: #0066CC; color: white; font-weight: 700; font-size: 12px;">
            <span>{{ $program['abbr1'] }}</span>
            <span>{{ $program['abbr2'] }}</span>
          </div>
          <div>
            <div class="stat-number">{{ $program['count'] }}</div>
            <div class="stat-label">{{ $program['name'] ?? 'Applications' }}</div>
          </div>
        </div>
      </div>
      @endforeach
    @endif
  </div>
  @endif

  <!-- Violations Overview -->
  <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 24px;">
    <!-- Minor Violations Chart -->
    <div class="header-section">
      <h3 style="color: var(--primary-green); margin-bottom: 16px; font-size: 1.2rem;">Minor Violations Overview</h3>
      <div style="display: flex; align-items: center; gap: 24px;">
        <div style="position: relative; width: 120px; height: 120px;">
          <svg width="120" height="120" style="transform: rotate(-90deg);">
            <circle cx="60" cy="60" r="50" fill="none" stroke="#e5e7eb" stroke-width="8"/>
            <circle cx="60" cy="60" r="50" fill="none" stroke="var(--primary-green)" stroke-width="8"
                    stroke-dasharray="{{ $minorResolvedPercentage * 3.14 }} 314" stroke-linecap="round"/>
          </svg>
          <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); text-align: center;">
            <div style="font-size: 1.5rem; font-weight: 700; color: var(--primary-green);">{{ number_format($minorResolvedPercentage, 0) }}%</div>
            <div style="font-size: 0.8rem; color: #666;">Resolved</div>
          </div>
        </div>
        <div style="flex: 1;">
          <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
            <span style="color: #666;">Pending</span>
            <span style="font-weight: 600; color: #e74c3c;">{{ $minorpending }}</span>
          </div>
          <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
            <span style="color: #666;">Resolved</span>
            <span style="font-weight: 600; color: var(--primary-green);">{{ $minorcomplied }}</span>
          </div>
          <div style="display: flex; justify-content: space-between; font-weight: 700; border-top: 1px solid #e5e7eb; padding-top: 8px;">
            <span>Total</span>
            <span>{{ $minorTotal }}</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Major Violations Chart -->
    <div class="header-section">
      <h3 style="color: var(--primary-green); margin-bottom: 16px; font-size: 1.2rem;">Major Violations Overview</h3>
      <div style="display: flex; align-items: center; gap: 24px;">
        <div style="position: relative; width: 120px; height: 120px;">
          <svg width="120" height="120" style="transform: rotate(-90deg);">
            <circle cx="60" cy="60" r="50" fill="none" stroke="#e5e7eb" stroke-width="8"/>
            <circle cx="60" cy="60" r="50" fill="none" stroke="#e74c3c" stroke-width="8"
                    stroke-dasharray="{{ $majorResolvedPercentage * 3.14 }} 314" stroke-linecap="round"/>
          </svg>
          <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); text-align: center;">
            <div style="font-size: 1.5rem; font-weight: 700; color: #e74c3c;">{{ number_format($majorResolvedPercentage, 0) }}%</div>
            <div style="font-size: 0.8rem; color: #666;">Resolved</div>
          </div>
        </div>
        <div style="flex: 1;">
          <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
            <span style="color: #666;">Pending</span>
            <span style="font-weight: 600; color: #e74c3c;">{{ $majorpending }}</span>
          </div>
          <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
            <span style="color: #666;">Resolved</span>
            <span style="font-weight: 600; color: var(--primary-green);">{{ $majorcomplied }}</span>
          </div>
          <div style="display: flex; justify-content: space-between; font-weight: 700; border-top: 1px solid #e5e7eb; padding-top: 8px;">
            <span>Total</span>
            <span>{{ $majorTotal }}</span>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Violations by Program Charts -->
  <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 24px;">
    <!-- Minor Violations by Program Chart -->
    <div class="header-section">
      <h3 style="color: var(--primary-green); margin-bottom: 16px; font-size: 1.2rem;">Minor Violations by Program</h3>
      @if($minorViolationsByProgram->isEmpty())
        <div style="text-align: center; padding: 48px; color: #6c757d;">
          <svg style="width: 48px; height: 48px; margin: 0 auto 12px; color: #dee2e6;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
          </svg>
          <p style="margin: 0; font-size: 1rem; font-weight: 500;">No Minor Violations</p>
          <p style="margin: 4px 0 0; font-size: 0.8rem;">No minor violations recorded</p>
        </div>
      @else
        @php
          $maxMinorViolations = $minorViolationsByProgram->max('count');
          $minorColors = ['#00b050', '#4ecdc4', '#45b7d1', '#96ceb4', '#98d8c8', '#f7dc6f'];
        @endphp

        <div style="display: flex; align-items: end; gap: 8px; height: 150px; padding: 16px; background: #f8f9fa; border-radius: 8px; margin-bottom: 16px;">
          @foreach($minorViolationsByProgram as $index => $violation)
            @php
              $heightPx = $maxMinorViolations > 0 ? ($violation->count / $maxMinorViolations) * 100 : 0;
              $color = $minorColors[$index % count($minorColors)];
            @endphp

            <div style="display: flex; flex-direction: column; align-items: center; flex: 1; min-width: 50px;">
              <div style="background: {{ $color }}; width: 100%; border-radius: 3px 3px 0 0; height: {{ $heightPx }}px; transition: all 0.3s ease;"
                   title="{{ $violation->program }}: {{ $violation->count }} minor violations"></div>
              <div style="margin-top: 6px; font-weight: 600; text-align: center; font-size: 10px; transform: rotate(-45deg); transform-origin: center; white-space: nowrap;">
                {{ $violation->program ?: 'Unknown' }}
              </div>
              <div style="font-size: 12px; color: #666; margin-top: 2px;">{{ $violation->count }}</div>
            </div>
          @endforeach
        </div>
      @endif
    </div>

    <!-- Major Violations by Program Chart -->
    <div class="header-section">
      <h3 style="color: var(--primary-green); margin-bottom: 16px; font-size: 1.2rem;">Major Violations by Program</h3>
      @if($majorViolationsByProgram->isEmpty())
        <div style="text-align: center; padding: 48px; color: #6c757d;">
          <svg style="width: 48px; height: 48px; margin: 0 auto 12px; color: #dee2e6;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
          </svg>
          <p style="margin: 0; font-size: 1rem; font-weight: 500;">No Major Violations</p>
          <p style="margin: 4px 0 0; font-size: 0.8rem;">No major violations recorded</p>
        </div>
      @else
        @php
          $maxMajorViolations = $majorViolationsByProgram->max('count');
          $majorColors = ['#e74c3c', '#ff6b6b', '#ff4757', '#ff3838', '#ff2f2f', '#ff1e1e'];
        @endphp

        <div style="display: flex; align-items: end; gap: 8px; height: 150px; padding: 16px; background: #f8f9fa; border-radius: 8px; margin-bottom: 16px;">
          @foreach($majorViolationsByProgram as $index => $violation)
            @php
              $heightPx = $maxMajorViolations > 0 ? ($violation->count / $maxMajorViolations) * 100 : 0;
              $color = $majorColors[$index % count($majorColors)];
            @endphp

            <div style="display: flex; flex-direction: column; align-items: center; flex: 1; min-width: 50px;">
              <div style="background: {{ $color }}; width: 100%; border-radius: 3px 3px 0 0; height: {{ $heightPx }}px; transition: all 0.3s ease;"
                   title="{{ $violation->program }}: {{ $violation->count }} major violations"></div>
              <div style="margin-top: 6px; font-weight: 600; text-align: center; font-size: 10px; transform: rotate(-45deg); transform-origin: center; white-space: nowrap;">
                {{ $violation->program ?: 'Unknown' }}
              </div>
              <div style="font-size: 12px; color: #666; margin-top: 2px;">{{ $violation->count }}</div>
            </div>
          @endforeach
        </div>
      @endif
    </div>
  </div>

  <!-- Recent Violations Section -->
  <div class="header-section">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
      <h3 style="color: var(--primary-green); margin: 0; font-size: 1.2rem;">Recent Violations in {{ $department }}</h3>
      <div style="display: flex; gap: 8px;">
        <a href="{{ route('dean.minor') }}" style="padding: 8px 16px; background: #fff3cd; color: #856404; border-radius: 6px; text-decoration: none; font-size: 14px; font-weight: 600; transition: all 0.2s ease; border: 1px solid #ffeaa7;">
          View All Minor
        </a>
        <a href="{{ route('dean.major') }}" style="padding: 8px 16px; background: #fdeaea; color: #dc3545; border-radius: 6px; text-decoration: none; font-size: 14px; font-weight: 600; transition: all 0.2s ease; border: 1px solid #f5c6cb;">
          View All Major
        </a>
      </div>
    </div>

    @if($recentViolations->isEmpty())
      <div style="text-align: center; padding: 48px; color: #6c757d; background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
        <svg style="width: 48px; height: 48px; margin: 0 auto 12px; color: #dee2e6;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <p style="margin: 0; font-size: 1rem; font-weight: 500; color: #28a745;">No Violations Found</p>
        <p style="margin: 4px 0 0; font-size: 0.8rem;">There are no student violations in your department</p>
      </div>
    @else
      <div style="background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); overflow: hidden;">
        <div style="overflow-x: auto;">
          <table style="width: 100%; border-collapse: collapse;">
            <thead>
              <tr style="background: #f8f9fa; border-bottom: 2px solid #e9ecef;">
                <th style="padding: 12px 16px; text-align: left; font-weight: 600; color: #495057; font-size: 13px;">Student ID</th>
                <th style="padding: 12px 16px; text-align: left; font-weight: 600; color: #495057; font-size: 13px;">Student Name</th>
                <th style="padding: 12px 16px; text-align: left; font-weight: 600; color: #495057; font-size: 13px;">Course</th>
                <th style="padding: 12px 16px; text-align: left; font-weight: 600; color: #495057; font-size: 13px;">Violation</th>
                <th style="padding: 12px 16px; text-align: left; font-weight: 600; color: #495057; font-size: 13px;">Type</th>
                <th style="padding: 12px 16px; text-align: left; font-weight: 600; color: #495057; font-size: 13px;">Status</th>
                <th style="padding: 12px 16px; text-align: left; font-weight: 600; color: #495057; font-size: 13px;">Date</th>
              </tr>
            </thead>
            <tbody>
              @foreach($recentViolations as $violation)
              <tr style="border-bottom: 1px solid #e9ecef; transition: background-color 0.2s ease;"
                  onmouseover="this.style.backgroundColor='#f8f9fa'"
                  onmouseout="this.style.backgroundColor='transparent'">
                <td style="padding: 12px 16px; color: #495057; font-size: 13px; font-weight: 500; font-family: monospace;">
                  {{ $violation->student_id }}
                </td>
                <td style="padding: 12px 16px; color: #495057; font-size: 13px;">
                  <div style="font-weight: 500; color: #333;">{{ $violation->first_name }} {{ $violation->last_name }}</div>
                  @php
                    $minorCount = \App\Models\StudentViolation::where('student_id', $violation->student_id)
                      ->where('offense_type', 'minor')
                      ->count();
                    
                    $statusColor = '#28a745';
                    $statusIcon = '✅';
                    if ($minorCount >= 3) {
                      $statusColor = '#dc3545';
                      $statusIcon = '🚨';
                    } elseif ($minorCount == 2) {
                      $statusColor = '#fd7e14';
                      $statusIcon = '⚠️';
                    } elseif ($minorCount == 1) {
                      $statusColor = '#ffc107';
                      $statusIcon = '⚠️';
                    }
                  @endphp
                  <div style="font-size: 10px; padding: 2px 6px; border-radius: 3px; display: inline-block; margin-top: 4px; background: {{ $statusColor }}20; color: {{ $statusColor }}; border: 1px solid {{ $statusColor }}40;">
                    {{ $statusIcon }} {{ $minorCount }}/3 Minor
                  </div>
                </td>
                <td style="padding: 12px 16px; color: #495057; font-size: 13px;">
                  <span style="display: inline-block; padding: 4px 8px; background: #e3f2fd; color: #1976d2; border-radius: 4px; font-size: 11px; font-weight: 500;">
                    {{ $violation->course ?? 'N/A' }}
                  </span>
                </td>
                <td style="padding: 12px 16px; color: #495057; font-size: 13px; max-width: 250px;">
                  <div style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $violation->violation }}">
                    {{ $violation->violation }}
                  </div>
                </td>
                <td style="padding: 12px 16px; color: #495057; font-size: 13px;">
                  @if($violation->offense_type === 'minor')
                    <span style="display: inline-block; padding: 4px 10px; background: #fff3cd; color: #856404; border-radius: 4px; font-size: 11px; font-weight: 600; text-transform: uppercase;">
                      Minor
                    </span>
                  @else
                    <span style="display: inline-block; padding: 4px 10px; background: #fdeaea; color: #dc3545; border-radius: 4px; font-size: 11px; font-weight: 600; text-transform: uppercase;">
                      Major
                    </span>
                  @endif
                </td>
                <td style="padding: 12px 16px; color: #495057; font-size: 13px;">
                  @if($violation->status == 0)
                    <span style="display: inline-block; padding: 4px 10px; background: #ffc107; color: #333; border-radius: 16px; font-size: 11px; font-weight: 500;">
                      ⏳ Pending
                    </span>
                  @elseif($violation->status == 1)
                    <span style="display: inline-block; padding: 4px 10px; background: #17a2b8; color: white; border-radius: 16px; font-size: 11px; font-weight: 500;">
                      🔄 In Progress
                    </span>
                  @elseif($violation->status == 2)
                    <span style="display: inline-block; padding: 4px 10px; background: #28a745; color: white; border-radius: 16px; font-size: 11px; font-weight: 500;">
                      ✅ Resolved
                    </span>
                  @else
                    <span style="display: inline-block; padding: 4px 10px; background: #6c757d; color: white; border-radius: 16px; font-size: 11px; font-weight: 500;">
                      Unknown
                    </span>
                  @endif
                </td>
                <td style="padding: 12px 16px; color: #6c757d; font-size: 12px;">
                  {{ $violation->created_at->format('M d, Y') }}
                  <div style="font-size: 10px; color: #adb5bd;">{{ $violation->created_at->format('h:i A') }}</div>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    @endif
  </div>



</x-dashboard-layout>