<!-- Dean Navigation Component -->
<div class="sidebar-section">DASHBOARD</div>

<a href="{{ route('dean.dashboard') }}" class="nav-link {{ request()->routeIs('dean.dashboard') ? 'active' : '' }}">
  <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6H8V5z"></path>
  </svg>
  Dashboard
</a>

<div class="sidebar-section">APPLICATIONS</div>

<a href="{{ route('dean.application') }}" class="nav-link {{ request()->routeIs('dean.application') ? 'active' : '' }}">
  <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
  </svg>
  Applications
  <span class="notification-icon" id="dean-applications-bell" style="display: none;">
    <svg fill="currentColor" viewBox="0 0 24 24"><path d="M12 22c1.1 0 2-.9 2-2h-4c0 1.1.9 2 2 2zm6-6v-5c0-3.07-1.64-5.64-4.5-6.32V4c0-.83-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5v.68C7.63 5.36 6 7.92 6 11v5l-2 2v1h16v-1l-2-2z"/></svg>
    <span class="notification-badge" id="dean-applications-count">0</span>
  </span>
</a>

<div class="sidebar-section">VIOLATIONS</div>

<a href="{{ route('dean.major') }}" class="nav-link {{ request()->routeIs('dean.major') ? 'active' : '' }}">
  <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
  </svg>
  Major Violations
  <span class="notification-icon" id="dean-major-violations-bell" style="display: none;">
    <svg fill="currentColor" viewBox="0 0 24 24"><path d="M12 22c1.1 0 2-.9 2-2h-4c0 1.1.9 2 2 2zm6-6v-5c0-3.07-1.64-5.64-4.5-6.32V4c0-.83-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5v.68C7.63 5.36 6 7.92 6 11v5l-2 2v1h16v-1l-2-2z"/></svg>
    <span class="notification-badge" id="dean-major-violations-count">0</span>
  </span>
</a>

<a href="{{ route('dean.minor') }}" class="nav-link {{ request()->routeIs('dean.minor') ? 'active' : '' }}">
  <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
  </svg>
  Minor Violations
  <span class="notification-icon" id="dean-minor-violations-bell" style="display: none;">
    <svg fill="currentColor" viewBox="0 0 24 24"><path d="M12 22c1.1 0 2-.9 2-2h-4c0 1.1.9 2 2 2zm6-6v-5c0-3.07-1.64-5.64-4.5-6.32V4c0-.83-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5v.68C7.63 5.36 6 7.92 6 11v5l-2 2v1h16v-1l-2-2z"/></svg>
    <span class="notification-badge" id="dean-minor-violations-count">0</span>
  </span>
</a>

<div class="sidebar-section">ACCOUNT</div>

<a href="{{ route('profile.edit') }}" class="nav-link {{ request()->routeIs('profile.edit') ? 'active' : '' }}">
  <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
  </svg>
  Profile
</a>

<form method="POST" action="{{ route('logout') }}" class="nav-logout-form">
  @csrf
  <button type="submit" class="nav-link nav-logout">
    <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75"></path>
    </svg>
    <span>Logout</span>
  </button>
</form>

<!-- Notification Bell Styles and Scripts -->
<style>
  /* Notification styles now centralized in dashboard-layout */
</style>

<script>
  // Function to update notification counts for Dean
  function updateDeanNotificationCounts() {
    fetch('/dean/notification-counts')
      .then(response => {
        if (!response.ok) {
          throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
      })
      .then(data => {
        console.log('Dean notification data received:', data);
        
        // Update applications with enhanced feedback
        updateNotificationBell('dean-applications', data.pendingApplications || 0);

        // Update major violations with enhanced feedback
        updateNotificationBell('dean-major-violations', data.majorViolations || 0);

        // Update minor violations with enhanced feedback
        updateNotificationBell('dean-minor-violations', data.minorViolations || 0);
        
        // Update page title with total count
        const totalCount = (data.pendingApplications || 0) + (data.majorViolations || 0) + (data.minorViolations || 0);
        if (totalCount > 0) {
          document.title = `(${totalCount}) Dean Dashboard - Good Moral Application System`;
        } else {
          document.title = 'Dean Dashboard - Good Moral Application System';
        }
      })
      .catch(error => {
        console.error('Error fetching dean notification counts:', error);
      });
  }

  // Function to update individual notification bell
  function updateNotificationBell(bellId, count) {
    const bell = document.getElementById(bellId + '-bell');
    const countElement = document.getElementById(bellId + '-count');

    if (bell && countElement) {
      if (count > 0) {
        bell.style.display = 'inline-flex';
        countElement.textContent = count;
      } else {
        bell.style.display = 'none';
      }
    }
  }

  // Initialize notification counts when page loads
  document.addEventListener('DOMContentLoaded', function() {
    console.log('Dean navigation loaded, initializing notifications...');

    updateDeanNotificationCounts();

    // Update notification counts every 30 seconds
    setInterval(updateDeanNotificationCounts, 30000);
  });
</script>
