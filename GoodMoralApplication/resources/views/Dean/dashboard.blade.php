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
        <p class="welcome-text">{{ $department }} Department Analytics</p>
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

  @include('shared.alerts.flash')

  <!-- Summary Cards -->
  <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; margin-bottom: 28px;">
    <!-- Total Applications -->
    <div class="stat-card" style="border-top-color: #6c757d;">
      <div style="display: flex; align-items: center; gap: 16px;">
        <div style="display: flex; align-items: center; justify-content: center; height: 56px; width: 56px; border-radius: 50%; background: #6c757d; flex-shrink: 0;">
          <svg style="width: 22px; height: 22px;" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
          </svg>
        </div>
        <div>
          <div class="stat-number">{{ $totalApplications }}</div>
          <div class="stat-label">Total Applications</div>
        </div>
      </div>
    </div>

    <!-- Pending Dean Approval -->
    <div class="stat-card" style="border-top-color: #F59E0B;">
      <div style="display: flex; align-items: center; gap: 16px;">
        <div style="display: flex; align-items: center; justify-content: center; height: 56px; width: 56px; border-radius: 50%; background: #F59E0B; flex-shrink: 0;">
          <svg style="width: 22px; height: 22px;" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
          </svg>
        </div>
        <div>
          <div class="stat-number">{{ $pendingGoodMoralApplications + $pendingResidencyApplications }}</div>
          <div class="stat-label">Pending Dean Approval</div>
        </div>
      </div>
    </div>

    <!-- Approved by Dean -->
    <div class="stat-card" style="border-top-color: #10B981;">
      <div style="display: flex; align-items: center; gap: 16px;">
        <div style="display: flex; align-items: center; justify-content: center; height: 56px; width: 56px; border-radius: 50%; background: #10B981; flex-shrink: 0;">
          <svg style="width: 22px; height: 22px;" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
          </svg>
        </div>
        <div>
          <div class="stat-number">{{ $approvedByDean }}</div>
          <div class="stat-label">Approved</div>
        </div>
      </div>
    </div>

    <!-- Rejected by Dean -->
    <div class="stat-card" style="border-top-color: #EF4444;">
      <div style="display: flex; align-items: center; gap: 16px;">
        <div style="display: flex; align-items: center; justify-content: center; height: 56px; width: 56px; border-radius: 50%; background: #EF4444; flex-shrink: 0;">
          <svg style="width: 22px; height: 22px;" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
          </svg>
        </div>
        <div>
          <div class="stat-number">{{ $rejectedByDean }}</div>
          <div class="stat-label">Rejected</div>
        </div>
      </div>
    </div>
  </div>

  <!-- Row 1: Applications by Program | Violations Overview -->
  <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 24px;">

    <!-- Applications by Program -->
    <div class="header-section" style="padding: 24px;">
      <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 20px; gap: 8px; flex-wrap: wrap;">
        <h3 style="color: var(--primary-green); margin: 0; font-size: 1.1rem; font-weight: 600;">Applications by Program</h3>
        <span style="display: inline-block; padding: 3px 9px; background: #e3f2fd; color: #1565c0; border-radius: 12px; font-size: 11px; font-weight: 600; white-space: nowrap;">AY 2025–2026</span>
      </div>
      @if(empty($courseCounts) || array_sum($courseCounts) === 0)
        <div style="text-align: center; padding: 48px 0; color: #6c757d;">
          <svg style="width: 48px; height: 48px; margin: 0 auto 12px; opacity: 0.4;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
          </svg>
          <p style="margin: 0; font-weight: 500;">No application data for this period</p>
        </div>
      @else
        <div style="position: relative; height: 260px;">
          <canvas id="appsByProgramChart"></canvas>
        </div>
      @endif
    </div>

    <!-- Violations Overview -->
    <div class="header-section" style="padding: 24px;">
      <h3 style="color: var(--primary-green); margin: 0 0 20px 0; font-size: 1.1rem; font-weight: 600;">Violations Overview</h3>
      @if($minorTotal + $majorTotal === 0)
        <div style="text-align: center; padding: 48px 0; color: #6c757d;">
          <svg style="width: 48px; height: 48px; margin: 0 auto 12px; opacity: 0.4;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
          </svg>
          <p style="margin: 0; font-weight: 500;">No violations for this period</p>
        </div>
      @else
        <div style="display: flex; align-items: center; gap: 24px;">
          <div style="position: relative; width: 180px; height: 180px; flex-shrink: 0;">
            <canvas id="violationsOverviewChart"></canvas>
          </div>
          <div style="flex: 1;">
            <div style="margin-bottom: 16px;">
              <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
                <span style="font-size: 14px; color: #495057; display: flex; align-items: center; gap: 6px;">
                  <span style="display: inline-block; width: 12px; height: 12px; border-radius: 2px; background: #F59E0B;"></span>
                  Minor
                </span>
                <span style="font-weight: 700; color: #495057;">{{ $minorTotal }}</span>
              </div>
              <div style="font-size: 12px; color: #6c757d; display: flex; justify-content: space-between;">
                <span>Pending: {{ $minorpending }}</span>
                <span>Resolved: {{ $minorcomplied }}</span>
              </div>
            </div>
            <div style="margin-bottom: 16px;">
              <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
                <span style="font-size: 14px; color: #495057; display: flex; align-items: center; gap: 6px;">
                  <span style="display: inline-block; width: 12px; height: 12px; border-radius: 2px; background: #EF4444;"></span>
                  Major
                </span>
                <span style="font-weight: 700; color: #495057;">{{ $majorTotal }}</span>
              </div>
              <div style="font-size: 12px; color: #6c757d; display: flex; justify-content: space-between;">
                <span>Pending: {{ $majorpending }}</span>
                <span>Resolved: {{ $majorcomplied }}</span>
              </div>
            </div>
            <div style="border-top: 1px solid #e9ecef; padding-top: 12px; display: flex; justify-content: space-between; font-weight: 700; font-size: 15px; color: #343a40;">
              <span>Total</span>
              <span>{{ $minorTotal + $majorTotal }}</span>
            </div>
          </div>
        </div>
      @endif
    </div>
  </div>

  <!-- Violations by Program (full-width grouped bar) -->
  <div class="header-section" style="padding: 24px; margin-bottom: 24px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 8px;">
      <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
        <h3 style="color: var(--primary-green); margin: 0; font-size: 1.1rem; font-weight: 600;">Violations by Program</h3>
        <span style="display: inline-block; padding: 3px 9px; background: #e3f2fd; color: #1565c0; border-radius: 12px; font-size: 11px; font-weight: 600; white-space: nowrap;">AY 2025–2026</span>
      </div>
      <div style="display: flex; gap: 16px; font-size: 13px; color: #6c757d;">
        <span style="display: flex; align-items: center; gap: 5px;">
          <span style="display: inline-block; width: 12px; height: 12px; border-radius: 2px; background: #F59E0B;"></span> Minor
        </span>
        <span style="display: flex; align-items: center; gap: 5px;">
          <span style="display: inline-block; width: 12px; height: 12px; border-radius: 2px; background: #EF4444;"></span> Major
        </span>
      </div>
    </div>
    @php
      $anyVbp = $minorViolationsByProgram->sum('count') + $majorViolationsByProgram->sum('count') > 0;
    @endphp
    @if(!$anyVbp)
      <div style="text-align: center; padding: 40px 0; color: #6c757d;">
        <p style="margin: 0; font-weight: 500;">No violations recorded for this period</p>
      </div>
    @else
      <div style="position: relative; height: 280px;">
        <canvas id="violationsByProgramChart"></canvas>
      </div>
    @endif
  </div>

  <!-- Violation Trend Analysis -->
  <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 24px;">

    <!-- Minor Violations Trend -->
    <div class="header-section" style="padding: 24px;">
      <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 20px;">
        <span style="display: inline-block; width: 14px; height: 14px; border-radius: 3px; background: #F97316; flex-shrink: 0;"></span>
        <h3 style="color: var(--primary-green); margin: 0; font-size: 1.1rem; font-weight: 600;">Minor Violations Trend</h3>
      </div>

      <!-- Year cards with variance indicators -->
      <div style="display: flex; align-items: stretch; gap: 6px; margin-bottom: 20px;">
        @php $minorAyKeys = array_keys($minorTrend); @endphp
        @foreach($minorTrend as $ay => $count)
          @php $idx = array_search($ay, $minorAyKeys); @endphp
          @if($idx > 0)
            @php
              $v = $minorVariance[$ay] ?? 0;
              $up = $v >= 0;
              $vDisplay = $up ? '+' . $v . '%' : $v . '%';
            @endphp
            <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 0 4px; min-width: 52px;">
              @if($up)
                <svg style="width: 14px; height: 14px;" fill="none" stroke="#dc3545" stroke-width="2.5" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7"/>
                </svg>
                <span style="font-size: 10px; color: #dc3545; white-space: nowrap; font-weight: 600;">{{ $vDisplay }}</span>
                <span style="font-size: 9px; color: #dc3545; white-space: nowrap;">Increasing</span>
              @else
                <svg style="width: 14px; height: 14px;" fill="none" stroke="#28a745" stroke-width="2.5" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                </svg>
                <span style="font-size: 10px; color: #28a745; white-space: nowrap; font-weight: 600;">{{ $vDisplay }}</span>
                <span style="font-size: 9px; color: #28a745; white-space: nowrap;">Decreasing</span>
              @endif
            </div>
          @endif
          <div style="flex: 1; text-align: center; padding: 10px 8px; background: #fff8e1; border-radius: 8px; border: 1px solid #ffe082; min-width: 70px;">
            <div style="font-size: 22px; font-weight: 700; color: #F97316;">{{ $count }}</div>
            <div style="font-size: 10px; color: #6c757d; white-space: nowrap;">{{ $ay }}</div>
          </div>
        @endforeach
      </div>

      <!-- Line chart -->
      <div style="position: relative; height: 200px;">
        <canvas id="minorTrendChart"></canvas>
      </div>
    </div>

    <!-- Major Violations Trend -->
    <div class="header-section" style="padding: 24px;">
      <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 20px;">
        <span style="display: inline-block; width: 14px; height: 14px; border-radius: 3px; background: #EF4444; flex-shrink: 0;"></span>
        <h3 style="color: var(--primary-green); margin: 0; font-size: 1.1rem; font-weight: 600;">Major Violations Trend</h3>
      </div>

      <!-- Year cards with variance indicators -->
      <div style="display: flex; align-items: stretch; gap: 6px; margin-bottom: 20px;">
        @php $majorAyKeys = array_keys($majorTrend); @endphp
        @foreach($majorTrend as $ay => $count)
          @php $idx = array_search($ay, $majorAyKeys); @endphp
          @if($idx > 0)
            @php
              $v = $majorVariance[$ay] ?? 0;
              $up = $v >= 0;
              $vDisplay = $up ? '+' . $v . '%' : $v . '%';
            @endphp
            <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 0 4px; min-width: 52px;">
              @if($up)
                <svg style="width: 14px; height: 14px;" fill="none" stroke="#dc3545" stroke-width="2.5" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7"/>
                </svg>
                <span style="font-size: 10px; color: #dc3545; white-space: nowrap; font-weight: 600;">{{ $vDisplay }}</span>
                <span style="font-size: 9px; color: #dc3545; white-space: nowrap;">Increasing</span>
              @else
                <svg style="width: 14px; height: 14px;" fill="none" stroke="#28a745" stroke-width="2.5" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                </svg>
                <span style="font-size: 10px; color: #28a745; white-space: nowrap; font-weight: 600;">{{ $vDisplay }}</span>
                <span style="font-size: 9px; color: #28a745; white-space: nowrap;">Decreasing</span>
              @endif
            </div>
          @endif
          <div style="flex: 1; text-align: center; padding: 10px 8px; background: #fdeaea; border-radius: 8px; border: 1px solid #f5c6cb; min-width: 70px;">
            <div style="font-size: 22px; font-weight: 700; color: #EF4444;">{{ $count }}</div>
            <div style="font-size: 10px; color: #6c757d; white-space: nowrap;">{{ $ay }}</div>
          </div>
        @endforeach
      </div>

      <!-- Line chart -->
      <div style="position: relative; height: 200px;">
        <canvas id="majorTrendChart"></canvas>
      </div>
    </div>
  </div>

  <!-- Chart.js -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
  <script>
    Chart.defaults.font.family = "'Inter', sans-serif";
    Chart.defaults.color = '#495057';

    @if(!empty($courseCounts) && array_sum($courseCounts) > 0)
    (function() {
      const labels = @json(array_keys($courseCounts));
      const data   = @json(array_values($courseCounts));
      new Chart(document.getElementById('appsByProgramChart'), {
        type: 'bar',
        data: {
          labels,
          datasets: [{
            label: 'Applications',
            data,
            backgroundColor: 'rgba(0, 176, 80, 0.75)',
            borderColor: 'rgba(0, 176, 80, 1)',
            borderWidth: 1,
            borderRadius: 4,
          }]
        },
        options: {
          indexAxis: 'y',
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: { display: false },
            tooltip: {
              callbacks: {
                label: ctx => ` ${ctx.parsed.x} application${ctx.parsed.x !== 1 ? 's' : ''}`
              }
            }
          },
          scales: {
            x: { beginAtZero: true, ticks: { precision: 0, font: { size: 11 } }, grid: { color: 'rgba(0,0,0,0.05)' } },
            y: { ticks: { font: { size: 11 } }, grid: { display: false } }
          }
        }
      });
    })();
    @endif

    @if($minorTotal + $majorTotal > 0)
    (function() {
      new Chart(document.getElementById('violationsOverviewChart'), {
        type: 'doughnut',
        data: {
          labels: ['Minor', 'Major'],
          datasets: [{
            data: [{{ $minorTotal }}, {{ $majorTotal }}],
            backgroundColor: ['#F59E0B', '#EF4444'],
            borderWidth: 2,
            borderColor: '#fff',
            hoverOffset: 6,
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          cutout: '68%',
          plugins: {
            legend: { display: false },
            tooltip: { callbacks: { label: ctx => ` ${ctx.label}: ${ctx.parsed}` } }
          }
        }
      });
    })();
    @endif

    @php
      $vbpLabels = $minorViolationsByProgram->pluck('program')->toArray();
      $vbpMinor  = $minorViolationsByProgram->pluck('count')->toArray();
      $vbpMajor  = $majorViolationsByProgram->pluck('count')->toArray();
      $anyVbpJs  = array_sum($vbpMinor) + array_sum($vbpMajor) > 0;
    @endphp
    @if($anyVbpJs)
    (function() {
      new Chart(document.getElementById('violationsByProgramChart'), {
        type: 'bar',
        data: {
          labels: @json($vbpLabels),
          datasets: [
            {
              label: 'Minor',
              data: @json($vbpMinor),
              backgroundColor: 'rgba(245,158,11,0.8)',
              borderColor: 'rgba(245,158,11,1)',
              borderWidth: 1,
              borderRadius: 4,
            },
            {
              label: 'Major',
              data: @json($vbpMajor),
              backgroundColor: 'rgba(239,68,68,0.8)',
              borderColor: 'rgba(239,68,68,1)',
              borderWidth: 1,
              borderRadius: 4,
            }
          ]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: { display: false },
            tooltip: { mode: 'index', intersect: false }
          },
          scales: {
            x: { ticks: { font: { size: 11 }, maxRotation: 35 }, grid: { display: false } },
            y: { beginAtZero: true, ticks: { precision: 0, font: { size: 11 } }, grid: { color: 'rgba(0,0,0,0.05)' } }
          }
        }
      });
    })();
    @endif

    // Minor Violations Trend Chart
    (function() {
      new Chart(document.getElementById('minorTrendChart'), {
        type: 'line',
        data: {
          labels: @json(array_keys($minorTrend)),
          datasets: [{
            label: 'Minor Violations',
            data: @json(array_values($minorTrend)),
            borderColor: '#F97316',
            backgroundColor: 'rgba(249,115,22,0.12)',
            borderWidth: 2.5,
            pointBackgroundColor: '#F97316',
            pointBorderColor: '#fff',
            pointBorderWidth: 2,
            pointRadius: 6,
            tension: 0.3,
            fill: true,
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: { display: false },
            tooltip: { callbacks: { label: ctx => ` ${ctx.parsed.y} violations` } }
          },
          scales: {
            y: { beginAtZero: true, ticks: { precision: 0, font: { size: 11 } }, grid: { color: 'rgba(0,0,0,0.05)' } },
            x: { ticks: { font: { size: 11 } }, grid: { display: false } }
          }
        }
      });
    })();

    // Major Violations Trend Chart
    (function() {
      new Chart(document.getElementById('majorTrendChart'), {
        type: 'line',
        data: {
          labels: @json(array_keys($majorTrend)),
          datasets: [{
            label: 'Major Violations',
            data: @json(array_values($majorTrend)),
            borderColor: '#EF4444',
            backgroundColor: 'rgba(239,68,68,0.12)',
            borderWidth: 2.5,
            pointBackgroundColor: '#EF4444',
            pointBorderColor: '#fff',
            pointBorderWidth: 2,
            pointRadius: 6,
            tension: 0.3,
            fill: true,
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: { display: false },
            tooltip: { callbacks: { label: ctx => ` ${ctx.parsed.y} violations` } }
          },
          scales: {
            y: { beginAtZero: true, ticks: { precision: 0, font: { size: 11 } }, grid: { color: 'rgba(0,0,0,0.05)' } },
            x: { ticks: { font: { size: 11 } }, grid: { display: false } }
          }
        }
      });
    })();
  </script>

</x-dashboard-layout>
