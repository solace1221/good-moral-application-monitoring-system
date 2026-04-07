<!-- Dashboard -->
<a href="{{ route('sec_osa.dashboard') }}" class="nav-link {{ request()->routeIs('sec_osa.dashboard') ? 'active' : '' }}">
  <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 7.125C2.25 6.504 2.754 6 3.375 6h6c.621 0 1.125.504 1.125 1.125v3.75c0 .621-.504 1.125-1.125 1.125h-6a1.125 1.125 0 0 1-1.125-1.125v-3.75ZM14.25 8.625c0-.621.504-1.125 1.125-1.125h5.25c.621 0 1.125.504 1.125 1.125v8.25c0 .621-.504 1.125-1.125 1.125h-5.25a1.125 1.125 0 0 1-1.125-1.125v-8.25ZM3.75 16.125c0-.621.504-1.125 1.125-1.125h5.25c.621 0 1.125.504 1.125 1.125v2.25c0 .621-.504 1.125-1.125 1.125h-5.25a1.125 1.125 0 0 1-1.125-1.125v-2.25Z" />
  </svg>
  Dashboard
</a>

<!-- Applications Section -->
<div class="nav-section" data-section="applications">
  <div class="nav-link nav-dropdown-header" onclick="toggleDropdown('applications')">
    <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
      <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" />
    </svg>
    Applications
    <svg xmlns="http://www.w3.org/2000/svg" class="nav-chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
      <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
    </svg>
  </div>
  <div class="nav-submenu" id="submenu-applications">
    <a href="{{ route('sec_osa.application') }}" class="nav-sublink {{ request()->routeIs('sec_osa.application') ? 'active' : '' }}">
      <svg xmlns="http://www.w3.org/2000/svg" class="nav-subicon" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
      </svg>
      Pending Applications
      <span class="notification-bell" id="pending-applications-bell" style="display: none;">
        <svg style="width: 16px; height: 16px; color: #ffc107;" fill="currentColor" viewBox="0 0 24 24">
          <path d="M5.85 3.5a.75.75 0 0 0-1.117-1 9.719 9.719 0 0 0-2.348 4.876.75.75 0 0 0 1.479.248A8.219 8.219 0 0 1 5.85 3.5ZM19.267 2.5a.75.75 0 1 0-1.118 1 8.22 8.22 0 0 1 1.987 4.124.75.75 0 0 0 1.48-.248A9.72 9.72 0 0 0 19.266 2.5Z" />
          <path fill-rule="evenodd" d="A7.5 7.5 0 0 1 4.5 10.5v.75c0 .851.461 1.614 1.178 2.045l.115.058c.892.453 1.207 1.543.754 2.435a.75.75 0 1 0 1.342.67c.14-.28.07-.635-.14-.903-.21-.268-.554-.4-.896-.4H4.5a6 6 0 0 0 12 0h-2.353c-.342 0-.686.132-.896.4-.21.268-.28.623-.14.903a.75.75 0 1 0 1.342-.67c-.453-.892-.138-1.982.754-2.435l.115-.058A2.25 2.25 0 0 0 16.5 11.25v-.75A7.5 7.5 0 0 1 12 2.5ZM8.25 8.25a3.75 3.75 0 1 1 7.5 0 3.75 3.75 0 0 1-7.5 0Z" clip-rule="evenodd" />
        </svg>
        <span class="notification-count" id="pending-applications-count">0</span>
      </span>
    </a>
  </div>
</div>

<!-- Violations Section -->
<div class="nav-section" data-section="violations">
  <div class="nav-link nav-dropdown-header" onclick="toggleDropdown('violations')">
    <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
      <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
    </svg>
    Violations
    <svg xmlns="http://www.w3.org/2000/svg" class="nav-chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
      <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
    </svg>
  </div>
  <div class="nav-submenu" id="submenu-violations">
    <a href="{{ route('sec_osa.major') }}" class="nav-sublink {{ request()->routeIs('sec_osa.major') ? 'active' : '' }}">
      <svg xmlns="http://www.w3.org/2000/svg" class="nav-subicon" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
      </svg>
      Major Violations
      <span class="notification-bell" id="major-violations-bell" style="display: none;">
        <svg style="width: 16px; height: 16px; color: #ffc107;" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
          <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
        </svg>
        <span class="notification-count" id="major-violations-count">0</span>
      </span>
    </a>
    <a href="{{ route('sec_osa.minor') }}" class="nav-sublink {{ request()->routeIs('sec_osa.minor') ? 'active' : '' }}">
      <svg xmlns="http://www.w3.org/2000/svg" class="nav-subicon" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
      </svg>
      Minor Violations
      <span class="notification-bell" id="minor-violations-bell" style="display: none;">
        <svg style="width: 16px; height: 16px; color: #ffc107;" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
          <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
        </svg>
        <span class="notification-count" id="minor-violations-count">0</span>
      </span>
    </a>
    <a href="{{ route('sec_osa.escalationNotifications') }}" class="nav-sublink {{ request()->routeIs('sec_osa.escalationNotifications') ? 'active' : '' }}">
      <svg xmlns="http://www.w3.org/2000/svg" class="nav-subicon" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
        <path stroke-linecap="round" stroke-linejoin="round" d="M10.34 15.84c-.688-.06-1.386-.09-2.09-.09H7.5a4.5 4.5 0 1 1 0-9h.75c.704 0 1.402-.03 2.09-.09m0 9.18c.253.962.584 1.892.985 2.783.247.55.06 1.21-.463 1.511l-.657.38c-.551.318-1.26.117-1.527-.461a20.845 20.845 0 0 1-1.44-4.282m3.102.069a18.03 18.03 0 0 1-.59-4.59c0-1.586.205-3.124.59-4.59m0 9.18a23.848 23.848 0 0 1 8.835 2.535M10.34 6.66a23.847 23.847 0 0 0 8.835-2.535m0 0A23.74 23.74 0 0 0 18.795 3m.38 1.125a23.91 23.91 0 0 1 1.014 5.395m-1.014 8.855c-.118.38-.245.754-.38 1.125m.38-1.125a23.91 23.91 0 0 0 1.014-5.395m0-3.46c.495.413.811 1.035.811 1.73 0 .695-.316 1.317-.811 1.73m0-3.46a24.347 24.347 0 0 1 0 3.46" />
      </svg>
      Escalation Notifications
      <span class="notification-bell" id="escalation-notifications-bell" style="display: none;">
        <svg style="width: 16px; height: 16px; color: #ffc107;" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
          <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
        </svg>
        <span class="notification-count" id="escalation-notifications-count">0</span>
      </span>
    </a>
  </div>
</div>

<!-- Profile -->
<a href="{{ route('sec_osa.profile') }}" class="nav-link {{ request()->routeIs('sec_osa.profile') ? 'active' : '' }}">
  <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
    <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275" />
  </svg>
  Profile
</a>

<!-- Logout -->
<form method="POST" action="{{ route('logout') }}" class="nav-logout-form">
  @csrf
  <button type="submit" class="nav-link nav-logout">
    <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
      <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" />
    </svg>
    Logout
  </button>
</form>

<style>
/* Base Nav-Link Styles - Match Dashboard Exactly */
.nav-link {
  display: flex;
  align-items: center;
  padding: 16px 24px;
  color: white;
  text-decoration: none;
  transition: all 0.3s ease;
  border-left: 4px solid transparent;
}

.nav-link:hover {
  background: rgba(0, 176, 80, 0.2);
  border-left-color: rgba(0, 176, 80, 0.8);
}

.nav-link.active {
  background: rgba(0, 176, 80, 0.25);
  border-left-color: rgba(0, 176, 80, 1);
}

.nav-icon {
  width: 24px;
  height: 24px;
  margin-right: 12px;
}

/* Navigation Section Dropdown Styles */
.nav-section {
  margin-bottom: 0;
}

/* Dropdown Header - Inherits from nav-link but adds dropdown functionality */
.nav-dropdown-header {
  cursor: pointer;
  justify-content: space-between;
  position: relative;
}

.nav-chevron {
  width: 20px;
  height: 20px;
  transition: transform 0.3s ease;
  margin-left: auto;
  opacity: 0.7;
  flex-shrink: 0;
}

.nav-section.collapsed .nav-chevron {
  transform: rotate(-90deg);
}

.nav-submenu {
  max-height: 500px;
  overflow: hidden;
  transition: max-height 0.3s ease, opacity 0.3s ease;
  opacity: 1;
  display: block;
  background: rgba(0, 0, 0, 0.1);
  margin-top: 0;
}

.nav-section.collapsed .nav-submenu {
  max-height: 0;
  opacity: 0;
}

/* Sublink Styles - Match System Theme */
.nav-sublink {
  display: flex;
  align-items: center;
  padding: 12px 24px 12px 48px;
  color: rgba(255, 255, 255, 0.9);
  text-decoration: none;
  transition: all 0.3s ease;
  border-left: 4px solid transparent;
  font-size: 0.95rem;
  font-weight: 400;
  background: rgba(0, 0, 0, 0.1);
}

.nav-sublink:hover {
  background: rgba(0, 176, 80, 0.15);
  border-left-color: rgba(0, 176, 80, 0.6);
  color: white;
}

.nav-sublink.active {
  background: rgba(0, 176, 80, 0.25);
  border-left-color: rgba(0, 176, 80, 1);
  color: white;
}

.nav-subicon {
  width: 20px;
  height: 20px;
  margin-right: 12px;
}

/* Logout Form and Button Styles - Match Dashboard */
.nav-logout-form {
  margin: 0;
  padding: 0;
}

.nav-logout {
  background: none;
  border: none;
  cursor: pointer;
  width: 100%;
  text-align: left;
  font-family: inherit;
  font-size: inherit;
  font-weight: 400;
}

.nav-logout:hover {
  background: rgba(0, 176, 80, 0.2);
  border-left-color: rgba(0, 176, 80, 0.8);
}

/* Notification Bell Styles */
.notification-bell {
  display: flex;
  align-items: center;
  gap: 4px;
  margin-left: auto;
  background: rgba(255, 193, 7, 0.15);
  padding: 3px 8px;
  border-radius: 14px;
  font-size: 11px;
  font-weight: 600;
  border: 1px solid rgba(255, 193, 7, 0.3);
}

.notification-count {
  color: #ffc107;
  min-width: 16px;
  text-align: center;
  font-weight: 700;
}

/* Responsive Navigation Styles */
@media (max-width: 768px) {
  .nav-section-header {
    padding: 16px 24px;
    font-size: 16px;
    min-height: 44px;
  }

  .nav-sublink {
    padding: 14px 24px 14px 48px;
    font-size: 15px;
    min-height: 44px;
  }

  .nav-section-header:hover,
  .nav-sublink:hover {
    transform: none;
  }

  .nav-icon,
  .nav-subicon {
    width: 22px;
    height: 22px;
  }

  .nav-chevron {
    width: 22px;
    height: 22px;
  }

  .notification-bell {
    padding: 4px 8px;
    font-size: 12px;
  }
}

@media (max-width: 480px) {
  .nav-section-header {
    padding: 18px 20px;
    font-size: 17px;
  }

  .nav-sublink {
    padding: 16px 20px 16px 44px;
    font-size: 16px;
  }

  .nav-section-title {
    font-size: 16px;
  }

  .nav-icon,
  .nav-subicon {
    width: 24px;
    height: 24px;
  }

  .nav-chevron {
    width: 24px;
    height: 24px;
  }
}
</style>

<script>
function toggleDropdown(sectionName) {
  const section = document.querySelector(`[data-section="${sectionName}"]`);
  if (section) {
    section.classList.toggle('collapsed');

    // Save state to localStorage
    const isCollapsed = section.classList.contains('collapsed');
    localStorage.setItem(`nav-${sectionName}-collapsed`, isCollapsed);
  }
}

// Initialize dropdown states from localStorage on page load
document.addEventListener('DOMContentLoaded', function() {
  const sections = ['applications', 'violations'];
  const currentRoute = window.location.pathname;

  sections.forEach(sectionName => {
    const section = document.querySelector(`[data-section="${sectionName}"]`);
    
    // Auto-expand section if current page is within that section
    let shouldExpand = false;
    if (sectionName === 'applications' && currentRoute.includes('/sec_osa/application')) {
      shouldExpand = true;
    } else if (sectionName === 'violations' && (currentRoute.includes('/sec_osa/major') || 
                                                 currentRoute.includes('/sec_osa/minor') || 
                                                 currentRoute.includes('/sec_osa/escalationNotifications'))) {
      shouldExpand = true;
    }

    if (shouldExpand) {
      section.classList.remove('collapsed');
      localStorage.setItem(`nav-${sectionName}-collapsed`, 'false');
    } else {
      // Only apply saved collapsed state if not on a related page
      const isCollapsed = localStorage.getItem(`nav-${sectionName}-collapsed`) === 'true';
      if (section && isCollapsed) {
        section.classList.add('collapsed');
      }
    }
  });

  // Debug: Log current state
  console.log('Navigation initialized. Current route:', currentRoute);
  console.log('Applications section found:', document.querySelector('[data-section="applications"]'));
  console.log('Violations section found:', document.querySelector('[data-section="violations"]'));

  // Fetch and update notification counts
  updateNotificationCounts();

  // Update notification counts every 30 seconds
  setInterval(updateNotificationCounts, 30000);
});

// Function to update notification counts
function updateNotificationCounts() {
  fetch('/sec_osa/notification-counts')
    .then(response => response.json())
    .then(data => {
      // Update pending applications (using printReadyApplications from API)
      updateNotificationBell('pending-applications', data.printReadyApplications || 0);

      // Update major violations (using pendingMajorViolations from API)
      updateNotificationBell('major-violations', data.pendingMajorViolations || 0);
      
      // Update minor violations (using pendingMinorViolations from API)
      updateNotificationBell('minor-violations', data.pendingMinorViolations || 0);

      // Update escalation notifications (using escalationNotifications from API)
      updateNotificationBell('escalation-notifications', data.escalationNotifications || 0);
    })
    .catch(error => {
      console.log('Error fetching notification counts:', error);
    });
}

// Function to update individual notification bell
function updateNotificationBell(bellId, count) {
  const bell = document.getElementById(bellId + '-bell');
  const countElement = document.getElementById(bellId + '-count');

  if (bell && countElement) {
    if (count > 0) {
      bell.style.display = 'flex';
      countElement.textContent = count;
    } else {
      bell.style.display = 'none';
    }
  }
}
</script>


