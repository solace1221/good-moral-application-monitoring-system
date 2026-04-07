<x-dashboard-layout>
  <x-slot name="roleTitle">Admin Mobile Demo</x-slot>

  <x-slot name="navigation">
    <x-admin-navigation />
  </x-slot>

  <!-- Header Section -->
  <div class="header-section">
    <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 16px;">
      <div style="flex: 1; min-width: 250px;">
        <h1 class="role-title">Mobile Responsiveness Demo</h1>
        <p class="welcome-text">Testing mobile-friendly interface improvements</p>
        <div class="accent-line"></div>
      </div>
      
      <!-- Desktop Controls -->
      <div class="desktop-header-controls" style="gap: 12px; align-items: center;">
        <select style="padding: 8px 12px; border: 2px solid #e1e5e9; border-radius: 6px; font-size: 14px; background: white; cursor: pointer;">
          <option>Filter Option 1</option>
          <option>Filter Option 2</option>
        </select>
        <input type="text" placeholder="Search..." style="padding: 12px 16px; border: 2px solid #e1e5e9; border-radius: 8px; font-size: 14px; width: 250px;">
        <button class="btn-primary">Search</button>
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
      <select class="mobile-form-control">
        <option>Filter Option 1</option>
        <option>Filter Option 2</option>
      </select>
      <input type="text" placeholder="Search..." class="mobile-form-control">
      <button class="btn-primary mobile-btn">Search</button>
    </div>
  </div>

  <!-- Mobile Stats Demo -->
  <div class="stats-grid">
    <div class="stat-card" style="border-top-color: #7B2CBF;">
      <div style="display: flex; align-items: center; gap: 16px; flex-wrap: wrap;">
        <div style="width: 60px; height: 60px; background: #7B2CBF; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; flex-shrink: 0;">
          123
        </div>
        <div style="flex: 1; min-width: 120px;">
          <div class="stat-number">123</div>
          <div class="stat-label">Sample Metric</div>
        </div>
      </div>
    </div>

    <div class="stat-card" style="border-top-color: #0066CC;">
      <div style="display: flex; align-items: center; gap: 16px; flex-wrap: wrap;">
        <div style="width: 60px; height: 60px; background: #0066CC; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; flex-shrink: 0;">
          456
        </div>
        <div style="flex: 1; min-width: 120px;">
          <div class="stat-number">456</div>
          <div class="stat-label">Another Metric</div>
        </div>
      </div>
    </div>

    <div class="stat-card" style="border-top-color: #28A745;">
      <div style="display: flex; align-items: center; gap: 16px; flex-wrap: wrap;">
        <div style="width: 60px; height: 60px; background: #28A745; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; flex-shrink: 0;">
          789
        </div>
        <div style="flex: 1; min-width: 120px;">
          <div class="stat-number">789</div>
          <div class="stat-label">Third Metric</div>
        </div>
      </div>
    </div>
  </div>

  <!-- Responsive Table Demo -->
  <div class="header-section">
    <h3 style="color: var(--primary-green); margin-bottom: 16px;">Responsive Table Demo</h3>
    <div class="responsive-table-container">
      <table class="responsive-table" style="width: 100%; border-collapse: collapse; background: white; border-radius: 8px; overflow: hidden;">
        <thead>
          <tr style="background: var(--primary-green); color: white;">
            <th style="padding: 16px; text-align: left;">Name</th>
            <th style="padding: 16px; text-align: center;">Department</th>
            <th style="padding: 16px; text-align: center;">Status</th>
            <th style="padding: 16px; text-align: center;">Date</th>
            <th style="padding: 16px; text-align: center;">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr style="border-bottom: 1px solid #e5e7eb;">
            <td style="padding: 16px;">John Doe</td>
            <td style="padding: 16px; text-align: center;">SITE</td>
            <td style="padding: 16px; text-align: center;">
              <span style="background: #28a745; color: white; padding: 4px 8px; border-radius: 4px; font-size: 12px;">Active</span>
            </td>
            <td style="padding: 16px; text-align: center;">2024-01-15</td>
            <td style="padding: 16px; text-align: center;">
              <button style="background: var(--primary-green); color: white; border: none; padding: 6px 12px; border-radius: 4px; margin: 2px;">View</button>
              <button style="background: #dc3545; color: white; border: none; padding: 6px 12px; border-radius: 4px; margin: 2px;">Delete</button>
            </td>
          </tr>
          <tr style="border-bottom: 1px solid #e5e7eb;">
            <td style="padding: 16px;">Jane Smith</td>
            <td style="padding: 16px; text-align: center;">SASTE</td>
            <td style="padding: 16px; text-align: center;">
              <span style="background: #ffc107; color: black; padding: 4px 8px; border-radius: 4px; font-size: 12px;">Pending</span>
            </td>
            <td style="padding: 16px; text-align: center;">2024-01-14</td>
            <td style="padding: 16px; text-align: center;">
              <button style="background: var(--primary-green); color: white; border: none; padding: 6px 12px; border-radius: 4px; margin: 2px;">View</button>
              <button style="background: #dc3545; color: white; border: none; padding: 6px 12px; border-radius: 4px; margin: 2px;">Delete</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Mobile Form Demo -->
  <div class="header-section">
    <h3 style="color: var(--primary-green); margin-bottom: 16px;">Mobile Form Demo</h3>
    <div style="display: grid; gap: 16px;">
      <div class="desktop-only" style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
        <input type="text" placeholder="First Name" style="padding: 12px; border: 2px solid #e1e5e9; border-radius: 8px;">
        <input type="text" placeholder="Last Name" style="padding: 12px; border: 2px solid #e1e5e9; border-radius: 8px;">
      </div>
      
      <div class="mobile-only" style="display: none;">
        <input type="text" placeholder="First Name" class="mobile-form-control">
        <input type="text" placeholder="Last Name" class="mobile-form-control">
      </div>
      
      <select class="mobile-form-control desktop-only" style="padding: 12px; border: 2px solid #e1e5e9; border-radius: 8px;">
        <option>Select Department</option>
        <option>SITE</option>
        <option>SASTE</option>
        <option>SBAHM</option>
      </select>
      
      <select class="mobile-form-control mobile-only" style="display: none;">
        <option>Select Department</option>
        <option>SITE</option>
        <option>SASTE</option>
        <option>SBAHM</option>
      </select>
      
      <div style="display: flex; gap: 12px;" class="desktop-only">
        <button class="btn-primary">Submit</button>
        <button style="background: #6c757d; color: white; border: none; padding: 12px 24px; border-radius: 8px;">Cancel</button>
      </div>
      
      <div class="mobile-only" style="display: none;">
        <button class="btn-primary mobile-btn">Submit</button>
        <button class="mobile-btn" style="background: #6c757d; color: white; border: none; border-radius: 8px;">Cancel</button>
      </div>
    </div>
  </div>

  <!-- Mobile Testing Instructions -->
  <div class="header-section" style="background: #e7f3ff; border: 1px solid #b3d9ff;">
    <h3 style="color: #0066cc; margin-bottom: 16px;">📱 Mobile Testing Instructions</h3>
    <div style="color: #0066cc;">
      <p><strong>To test mobile responsiveness:</strong></p>
      <ol style="margin: 12px 0; padding-left: 20px;">
        <li>Resize your browser window to mobile width (&lt; 768px)</li>
        <li>Use browser developer tools to simulate mobile devices</li>
        <li>Test the hamburger menu functionality</li>
        <li>Try the mobile search toggle</li>
        <li>Scroll horizontally on the table</li>
        <li>Test form inputs (should not zoom on iOS)</li>
        <li>Verify all buttons are touch-friendly (44px minimum)</li>
      </ol>
      <p><strong>Key features to test:</strong></p>
      <ul style="margin: 12px 0; padding-left: 20px;">
        <li>Animated hamburger menu</li>
        <li>Responsive header layout</li>
        <li>Mobile search panel</li>
        <li>Table horizontal scrolling</li>
        <li>Form element stacking</li>
        <li>Touch-friendly button sizes</li>
      </ul>
    </div>
  </div>

  <script>
    // Mobile search toggle functionality
    function toggleMobileSearch() {
      const searchPanel = document.getElementById('mobileSearchPanel');
      searchPanel.classList.toggle('active');
    }

    // Close mobile search when clicking outside
    document.addEventListener('click', function(event) {
      const searchPanel = document.getElementById('mobileSearchPanel');
      const searchToggle = event.target.closest('.mobile-search-toggle');
      
      if (!searchToggle && !searchPanel.contains(event.target)) {
        searchPanel.classList.remove('active');
      }
    });
  </script>

</x-dashboard-layout>
