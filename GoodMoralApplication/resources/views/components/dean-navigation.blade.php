<!-- Dean Navigation Component -->
<a href="{{ route('dean.dashboard') }}" class="nav-link {{ request()->routeIs('dean.dashboard') ? 'active' : '' }}">
  <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6H8V5z"></path>
  </svg>
  Dashboard
</a>

<a href="{{ route('dean.application') }}" class="nav-link {{ request()->routeIs('dean.application') ? 'active' : '' }}" style="position: relative;">
  <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
  </svg>
  Applications
  <span class="notification-bell" id="dean-applications-bell" style="display: none; position: absolute; top: 8px; right: 8px; background: rgba(255, 193, 7, 0.2); padding: 4px 8px; border-radius: 16px; font-size: 12px; font-weight: 700; align-items: center; gap: 6px; flex-direction: row; border: 1px solid rgba(255, 193, 7, 0.3);">
    <svg style="width: 14px; height: 14px; color: #ffc107;" fill="currentColor" viewBox="0 0 24 24">
      <path d="M12 2C9.79 2 8 3.79 8 6v5.26a6.94 6.94 0 00-1.7.96L5 13v3h14v-3l-1.3-1.78a6.94 6.94 0 00-1.7-.96V6c0-2.21-1.79-4-4-4z"/>
    </svg>
    <span class="notification-count" id="dean-applications-count" style="color: #ffc107; min-width: 18px; text-align: center; font-weight: 700; font-size: 12px;">0</span>
  </span>
</a>

<a href="{{ route('dean.major') }}" class="nav-link {{ request()->routeIs('dean.major') ? 'active' : '' }}" style="position: relative;">
  <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
  </svg>
  Major Violations
  <span class="notification-bell" id="dean-major-violations-bell" style="display: none; position: absolute; top: 8px; right: 8px; background: rgba(220, 53, 69, 0.2); padding: 4px 8px; border-radius: 16px; font-size: 12px; font-weight: 700; align-items: center; gap: 6px; flex-direction: row; border: 1px solid rgba(220, 53, 69, 0.3);">
    <svg style="width: 14px; height: 14px; color: #dc3545;" fill="currentColor" viewBox="0 0 24 24">
      <path d="M12 2C9.79 2 8 3.79 8 6v5.26a6.94 6.94 0 00-1.7.96L5 13v3h14v-3l-1.3-1.78a6.94 6.94 0 00-1.7-.96V6c0-2.21-1.79-4-4-4z"/>
    </svg>
    <span class="notification-count" id="dean-major-violations-count" style="color: #dc3545; min-width: 18px; text-align: center; font-weight: 700; font-size: 12px;">0</span>
  </span>
</a>

<a href="{{ route('dean.minor') }}" class="nav-link {{ request()->routeIs('dean.minor') ? 'active' : '' }}" style="position: relative;">
  <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
  </svg>
  Minor Violations
  <span class="notification-bell" id="dean-minor-violations-bell" style="display: none; position: absolute; top: 8px; right: 8px; background: rgba(255, 193, 7, 0.2); padding: 4px 8px; border-radius: 16px; font-size: 12px; font-weight: 700; align-items: center; gap: 6px; flex-direction: row; border: 1px solid rgba(255, 193, 7, 0.3);">
    <svg style="width: 14px; height: 14px; color: #ffc107;" fill="currentColor" viewBox="0 0 24 24">
      <path d="M12 2C9.79 2 8 3.79 8 6v5.26a6.94 6.94 0 00-1.7.96L5 13v3h14v-3l-1.3-1.78a6.94 6.94 0 00-1.7-.96V6c0-2.21-1.79-4-4-4z"/>
    </svg>
    <span class="notification-count" id="dean-minor-violations-count" style="color: #ffc107; min-width: 18px; text-align: center; font-weight: 700; font-size: 12px;">0</span>
  </span>
</a>

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
  /* Notification Bell Animations and Styles */
  @keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.1); }
    100% { transform: scale(1); }
  }

  @keyframes bounce {
    0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
    40% { transform: translateY(-3px); }
    60% { transform: translateY(-2px); }
  }

  .notification-bell {
    transition: all 0.3s ease;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
  }

  .notification-bell:hover {
    transform: scale(1.05);
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
  }

  /* Enhanced notification count styling */
  .notification-count {
    background: rgba(255,255,255,0.9);
    border-radius: 10px;
    padding: 2px 6px;
    line-height: 1;
  }

  /* Responsive notification bells */
  @media (max-width: 768px) {
    .notification-bell {
      padding: 3px 6px;
      font-size: 10px;
    }
    
    .notification-count {
      min-width: 16px;
      font-size: 10px;
    }
  }
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
    console.log(`Updating notification bell: ${bellId} with count: ${count}`);
    
    const bell = document.getElementById(bellId + '-bell');
    const countElement = document.getElementById(bellId + '-count');

    console.log(`Bell element found: ${!!bell}, Count element found: ${!!countElement}`);

    if (bell && countElement) {
      const previousCount = parseInt(countElement.textContent) || 0;
      console.log(`Previous count: ${previousCount}, New count: ${count}`);

      if (count > 0) {
        bell.style.display = 'flex';
        bell.style.alignItems = 'center';
        bell.style.justifyContent = 'center';
        countElement.textContent = count;
        
        console.log(`Bell ${bellId} is now visible with count ${count}`);

        // Add animation effect for new or increased notifications
        if (count > previousCount) {
          bell.style.animation = 'bounce 0.6s ease-in-out';
          setTimeout(() => {
            bell.style.animation = '';
          }, 600);
        }
        
        // Add urgent styling for high counts
        if (count >= 10) {
          bell.style.background = 'rgba(220, 53, 69, 0.3)';
          bell.style.borderColor = 'rgba(220, 53, 69, 0.5)';
          countElement.style.color = '#dc3545';
        } else if (count >= 5) {
          bell.style.background = 'rgba(255, 193, 7, 0.3)';
          bell.style.borderColor = 'rgba(255, 193, 7, 0.5)';
          countElement.style.color = '#ffc107';
        }
      } else {
        bell.style.display = 'none';
        console.log(`Bell ${bellId} is now hidden`);
      }
    } else {
      console.error(`Could not find bell elements for ${bellId}`);
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
