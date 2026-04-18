<x-dashboard-layout>
  <x-slot name="roleTitle">Admin</x-slot>

  <x-slot name="navigation">
    <x-admin-navigation />
  </x-slot>

  <!-- Header Section -->
  <div class="header-section">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
      <div>
        <h1 class="role-title">Generate Reports</h1>
        <p class="welcome-text">Generate comprehensive reports by academic year and report type</p>
        <div class="accent-line"></div>
      </div>
      <div>
        <a href="{{ route('admin.reports.history') }}" class="btn-secondary" style="padding: 12px 24px; font-size: 14px; display: flex; align-items: center; gap: 8px; text-decoration: none;">
          <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
          </svg>
          View Reports History
        </a>
      </div>
    </div>
  </div>

  <!-- Report Generation Form -->
  <div class="header-section" style="margin-top: 24px;">
    <form id="reportForm" method="POST" action="{{ route('admin.generateSelectedReport') }}" style="background: white; padding: 32px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
      @csrf
      
      <!-- Academic Year Selection -->
      <div style="margin-bottom: 32px;">
        <h3 style="color: var(--primary-green); margin-bottom: 16px; font-size: 1.2rem; display: flex; align-items: center; gap: 8px;">
          <svg style="width: 24px; height: 24px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
          </svg>
          Academic Year
        </h3>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px;" id="academicYearsList">
          @foreach($academicYears as $year)
            <label style="display: flex; align-items: center; gap: 12px; padding: 16px; border: 2px solid #e1e5e9; border-radius: 8px; cursor: pointer; transition: all 0.3s ease;"
                   class="academic-year-option" data-year="{{ $year }}">
              <input type="radio" name="academic_year" value="{{ $year }}" style="width: 20px; height: 20px;" {{ $loop->first ? 'checked' : '' }}>
              <div>
                <div style="font-weight: 600; color: #333;">{{ $year }}</div>
                <div style="font-size: 14px; color: #666;">Academic Year</div>
              </div>
            </label>
          @endforeach
        </div>
      </div>

      <!-- Time Period Filter -->
      <div style="margin-bottom: 32px;">
        <h3 style="color: var(--primary-green); margin-bottom: 16px; font-size: 1.2rem; display: flex; align-items: center; gap: 8px;">
          <svg style="width: 24px; height: 24px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
          </svg>
          Time Period Filter
        </h3>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 16px;">
          <label style="display: flex; align-items: center; gap: 12px; padding: 16px; border: 2px solid #e1e5e9; border-radius: 8px; cursor: pointer; transition: all 0.3s ease;"
                 class="time-period-option" data-period="all">
            <input type="radio" name="time_period" value="all" style="width: 20px; height: 20px;" checked>
            <div>
              <div style="font-weight: 600; color: #333;">All Time</div>
              <div style="font-size: 14px; color: #666;">Complete Data</div>
            </div>
          </label>

          <label style="display: flex; align-items: center; gap: 12px; padding: 16px; border: 2px solid #e1e5e9; border-radius: 8px; cursor: pointer; transition: all 0.3s ease;"
                 class="time-period-option" data-period="daily">
            <input type="radio" name="time_period" value="daily" style="width: 20px; height: 20px;">
            <div>
              <div style="font-weight: 600; color: #333;">Today</div>
              <div style="font-size: 14px; color: #666;">Current Day</div>
            </div>
          </label>

          <label style="display: flex; align-items: center; gap: 12px; padding: 16px; border: 2px solid #e1e5e9; border-radius: 8px; cursor: pointer; transition: all 0.3s ease;"
                 class="time-period-option" data-period="monthly">
            <input type="radio" name="time_period" value="monthly" style="width: 20px; height: 20px;">
            <div style="flex: 1;">
              <div style="font-weight: 600; color: #333;">Monthly</div>
              <div style="font-size: 14px; color: #666;">Select Month</div>
              <select id="monthSelector" style="width: 100%; margin-top: 8px; padding: 4px; border: 1px solid #ddd; border-radius: 4px; font-size: 12px;" disabled>
                <option value="1">January</option>
                <option value="2">February</option>
                <option value="3">March</option>
                <option value="4">April</option>
                <option value="5">May</option>
                <option value="6">June</option>
                <option value="7">July</option>
                <option value="8">August</option>
                <option value="9">September</option>
                <option value="10">October</option>
                <option value="11">November</option>
                <option value="12">December</option>
              </select>
            </div>
          </label>

          <label style="display: flex; align-items: center; gap: 12px; padding: 16px; border: 2px solid #e1e5e9; border-radius: 8px; cursor: pointer; transition: all 0.3s ease;"
                 class="time-period-option" data-period="yearly">
            <input type="radio" name="time_period" value="yearly" style="width: 20px; height: 20px;">
            <div>
              <div style="font-weight: 600; color: #333;">This Year</div>
              <div style="font-size: 14px; color: #666;">Calendar Year</div>
            </div>
          </label>

          <label style="display: flex; align-items: center; gap: 12px; padding: 16px; border: 2px solid #e1e5e9; border-radius: 8px; cursor: pointer; transition: all 0.3s ease;"
                 class="time-period-option" data-period="first_semester">
            <input type="radio" name="time_period" value="first_semester" style="width: 20px; height: 20px;">
            <div>
              <div style="font-weight: 600; color: #333;">First Semester</div>
              <div style="font-size: 14px; color: #666;">Sep - Jan</div>
            </div>
          </label>

          <label style="display: flex; align-items: center; gap: 12px; padding: 16px; border: 2px solid #e1e5e9; border-radius: 8px; cursor: pointer; transition: all 0.3s ease;"
                 class="time-period-option" data-period="second_semester">
            <input type="radio" name="time_period" value="second_semester" style="width: 20px; height: 20px;">
            <div>
              <div style="font-weight: 600; color: #333;">Second Semester</div>
              <div style="font-size: 14px; color: #666;">Feb - Jun</div>
            </div>
          </label>

          <label style="display: flex; align-items: center; gap: 12px; padding: 16px; border: 2px solid #e1e5e9; border-radius: 8px; cursor: pointer; transition: all 0.3s ease;"
                 class="time-period-option" data-period="summer_term">
            <input type="radio" name="time_period" value="summer_term" style="width: 20px; height: 20px;">
            <div>
              <div style="font-weight: 600; color: #333;">Summer Term</div>
              <div style="font-size: 14px; color: #666;">Jul - Aug</div>
            </div>
          </label>
        </div>
      </div>

      <!-- Report Type Selection -->
      <div style="margin-bottom: 32px;">
        <h3 style="color: var(--primary-green); margin-bottom: 16px; font-size: 1.2rem; display: flex; align-items: center; gap: 8px;">
          <svg style="width: 24px; height: 24px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
          </svg>
          Report Type
        </h3>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 16px;">
          
          <!-- Good Moral Applications Report -->
          <label style="display: flex; align-items: center; gap: 16px; padding: 20px; border: 2px solid #e1e5e9; border-radius: 12px; cursor: pointer; transition: all 0.3s ease;" 
                 class="report-type-option" data-type="good_moral_applicants">
            <input type="radio" name="report_type" value="good_moral_applicants" style="width: 20px; height: 20px;" checked>
            <div style="background: var(--primary-green); color: white; padding: 12px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
              <svg style="width: 24px; height: 24px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
            </div>
            <div>
              <div style="font-weight: 600; color: #333; margin-bottom: 4px;">Good Moral Certificate</div>
              <div style="font-size: 14px; color: #666;">List of applicants for Certificate of Good Moral Character</div>
            </div>
          </label>

          <!-- Residency Applications Report -->
          <label style="display: flex; align-items: center; gap: 16px; padding: 20px; border: 2px solid #e1e5e9; border-radius: 12px; cursor: pointer; transition: all 0.3s ease;" 
                 class="report-type-option" data-type="residency_applicants">
            <input type="radio" name="report_type" value="residency_applicants" style="width: 20px; height: 20px;">
            <div style="background: #3498db; color: white; padding: 12px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
              <svg style="width: 24px; height: 24px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
              </svg>
            </div>
            <div>
              <div style="font-weight: 600; color: #333; margin-bottom: 4px;">Certificate of Residency</div>
              <div style="font-size: 14px; color: #666;">List of applicants for Certificate of Residency</div>
            </div>
          </label>

          <!-- Minor Violations Report -->
          <label style="display: flex; align-items: center; gap: 16px; padding: 20px; border: 2px solid #e1e5e9; border-radius: 12px; cursor: pointer; transition: all 0.3s ease;" 
                 class="report-type-option" data-type="minor_violators">
            <input type="radio" name="report_type" value="minor_violators" style="width: 20px; height: 20px;">
            <div style="background: #f39c12; color: white; padding: 12px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
              <svg style="width: 24px; height: 24px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
              </svg>
            </div>
            <div>
              <div style="font-weight: 600; color: #333; margin-bottom: 4px;">Minor Violations</div>
              <div style="font-size: 14px; color: #666;">List of violators with minor offenses</div>
            </div>
          </label>

          <!-- Major Violations Report -->
          <label style="display: flex; align-items: center; gap: 16px; padding: 20px; border: 2px solid #e1e5e9; border-radius: 12px; cursor: pointer; transition: all 0.3s ease;" 
                 class="report-type-option" data-type="major_violators">
            <input type="radio" name="report_type" value="major_violators" style="width: 20px; height: 20px;">
            <div style="background: #e74c3c; color: white; padding: 12px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
              <svg style="width: 24px; height: 24px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728"></path>
              </svg>
            </div>
            <div>
              <div style="font-weight: 600; color: #333; margin-bottom: 4px;">Major Violations</div>
              <div style="font-size: 14px; color: #666;">List of violators with major offenses</div>
            </div>
          </label>

          <!-- Overall Report -->
          <label style="display: flex; align-items: center; gap: 16px; padding: 20px; border: 2px solid #e1e5e9; border-radius: 12px; cursor: pointer; transition: all 0.3s ease;"
                 class="report-type-option" data-type="overall_report">
            <input type="radio" name="report_type" value="overall_report" style="width: 20px; height: 20px;">
            <div style="background: #9b59b6; color: white; padding: 12px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
              <svg style="width: 24px; height: 24px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 00-2-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
              </svg>
            </div>
            <div>
              <div style="font-weight: 600; color: #333; margin-bottom: 4px;">Overall Report</div>
              <div style="font-size: 14px; color: #666;">Comprehensive department statistics and violation summary</div>
            </div>
          </label>

          <!-- Minor Offenses Overall Report -->
          <label style="display: flex; align-items: center; gap: 16px; padding: 20px; border: 2px solid #e1e5e9; border-radius: 12px; cursor: pointer; transition: all 0.3s ease;"
                 class="report-type-option" data-type="minor_offenses_overall">
            <input type="radio" name="report_type" value="minor_offenses_overall" style="width: 20px; height: 20px;">
            <div style="background: #17a2b8; color: white; padding: 12px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
              <svg style="width: 24px; height: 24px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
              </svg>
            </div>
            <div>
              <div style="font-weight: 600; color: #333; margin-bottom: 4px;">Minor Offenses Overall</div>
              <div style="font-size: 14px; color: #666;">Overall report on minor offenses as of June 2025</div>
            </div>
          </label>
        </div>
      </div>

      <!-- Hidden inputs for form submission -->
      <input type="hidden" name="time_period" id="selected_time_period" value="all">


      <!-- Generate Button -->
      <div style="display: flex; justify-content: center; gap: 16px;">
        <button type="submit" class="btn-primary" style="padding: 16px 32px; font-size: 16px; display: flex; align-items: center; gap: 12px;">
          <svg style="width: 24px; height: 24px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
          </svg>
          Generate Report
        </button>
      </div>
    </form>
  </div>

  <style>
    .academic-year-option:hover,
    .report-type-option:hover {
      border-color: var(--primary-green);
      background-color: #f8f9fa;
    }

    .academic-year-option:has(input:checked),
    .report-type-option:has(input:checked),
    .time-period-option:has(input:checked) {
      border-color: var(--primary-green);
      background-color: rgba(0, 176, 80, 0.1);
    }

    .btn-primary {
      background: var(--primary-green);
      color: white;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      transition: all 0.3s ease;
      text-decoration: none;
    }

    .btn-primary:hover {
      background: #00a050;
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(0, 176, 80, 0.3);
    }

    .btn-secondary {
      background: #6c757d;
      color: white;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      transition: all 0.3s ease;
      text-decoration: none;
    }

    .btn-secondary:hover {
      background: #5a6268;
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(108, 117, 125, 0.3);
      color: white;
      text-decoration: none;
    }
  </style>

  <script>
    // Add interactive feedback for form selections
    document.addEventListener('DOMContentLoaded', function() {
      const academicYearOptions = document.querySelectorAll('.academic-year-option');
      const reportTypeOptions = document.querySelectorAll('.report-type-option');
      const timePeriodOptions = document.querySelectorAll('.time-period-option');

      academicYearOptions.forEach(option => {
        option.addEventListener('click', function() {
          academicYearOptions.forEach(opt => opt.style.borderColor = '#e1e5e9');
          this.style.borderColor = 'var(--primary-green)';
        });
      });

      reportTypeOptions.forEach(option => {
        option.addEventListener('click', function() {
          reportTypeOptions.forEach(opt => opt.style.borderColor = '#e1e5e9');
          this.style.borderColor = 'var(--primary-green)';


        });
      });



      timePeriodOptions.forEach(option => {
        option.addEventListener('click', function() {
          timePeriodOptions.forEach(opt => opt.style.borderColor = '#e1e5e9');
          this.style.borderColor = 'var(--primary-green)';

          // Update hidden input with selected time period
          const selectedPeriod = this.querySelector('input[name="time_period"]').value;
          document.getElementById('selected_time_period').value = selectedPeriod;

          // Enable/disable month selector based on selection
          const monthSelector = document.getElementById('monthSelector');
          if (selectedPeriod === 'monthly') {
            monthSelector.disabled = false;
            monthSelector.style.opacity = '1';
            // Set current month as default
            const currentMonth = new Date().getMonth() + 1;
            monthSelector.value = currentMonth;
          } else {
            monthSelector.disabled = true;
            monthSelector.style.opacity = '0.5';
          }
        });
      });

      // Handle month selector change
      document.getElementById('monthSelector').addEventListener('change', function() {
        // Update the hidden input to include month information
        const selectedMonth = this.value;
        document.getElementById('selected_time_period').value = 'monthly_' + selectedMonth;
      });
    });

  </script>
</x-dashboard-layout>
