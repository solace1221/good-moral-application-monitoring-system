<!-- DASHBOARD -->
<div class="sidebar-section">DASHBOARD</div>
<a href="{{ route('sec_osa.dashboard') }}" class="nav-link {{ request()->routeIs('sec_osa.dashboard') ? 'active' : '' }}">
  <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 7.125C2.25 6.504 2.754 6 3.375 6h6c.621 0 1.125.504 1.125 1.125v3.75c0 .621-.504 1.125-1.125 1.125h-6a1.125 1.125 0 0 1-1.125-1.125v-3.75ZM14.25 8.625c0-.621.504-1.125 1.125-1.125h5.25c.621 0 1.125.504 1.125 1.125v8.25c0 .621-.504 1.125-1.125 1.125h-5.25a1.125 1.125 0 0 1-1.125-1.125v-8.25ZM3.75 16.125c0-.621.504-1.125 1.125-1.125h5.25c.621 0 1.125.504 1.125 1.125v2.25c0 .621-.504 1.125-1.125 1.125h-5.25a1.125 1.125 0 0 1-1.125-1.125v-2.25Z" />
  </svg>
  Dashboard
</a>

<!-- APPLICATIONS -->
<div class="sidebar-section">APPLICATIONS</div>
<a href="{{ route('sec_osa.application') }}" class="nav-link {{ request()->routeIs('sec_osa.application') ? 'active' : '' }}">
  <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
  </svg>
  Certificate Release
  <span class="notification-icon" id="pending-applications-bell" style="display: none;">
    <svg fill="currentColor" viewBox="0 0 24 24"><path d="M12 22c1.1 0 2-.9 2-2h-4c0 1.1.9 2 2 2zm6-6v-5c0-3.07-1.64-5.64-4.5-6.32V4c0-.83-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5v.68C7.63 5.36 6 7.92 6 11v5l-2 2v1h16v-1l-2-2z"/></svg>
    <span class="notification-badge" id="pending-applications-count">0</span>
  </span>
</a>

<!-- VIOLATIONS -->
<div class="sidebar-section">VIOLATIONS</div>
<a href="{{ route('sec_osa.addViolator') }}" class="nav-link {{ request()->routeIs('sec_osa.addViolator') ? 'active' : '' }}">
  <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
    <path stroke-linecap="round" stroke-linejoin="round" d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766Z" />
  </svg>
  Add Violator
</a>
<a href="{{ route('sec_osa.major') }}" class="nav-link {{ request()->routeIs('sec_osa.major') ? 'active' : '' }}">
  <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
  </svg>
  Major Violations
  <span class="notification-icon" id="major-violations-bell" style="display: none;">
    <svg fill="currentColor" viewBox="0 0 24 24"><path d="M12 22c1.1 0 2-.9 2-2h-4c0 1.1.9 2 2 2zm6-6v-5c0-3.07-1.64-5.64-4.5-6.32V4c0-.83-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5v.68C7.63 5.36 6 7.92 6 11v5l-2 2v1h16v-1l-2-2z"/></svg>
    <span class="notification-badge" id="major-violations-count">0</span>
  </span>
</a>
<a href="{{ route('sec_osa.minor') }}" class="nav-link {{ request()->routeIs('sec_osa.minor') ? 'active' : '' }}">
  <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
  </svg>
  Minor Violations
  <span class="notification-icon" id="minor-violations-bell" style="display: none;">
    <svg fill="currentColor" viewBox="0 0 24 24"><path d="M12 22c1.1 0 2-.9 2-2h-4c0 1.1.9 2 2 2zm6-6v-5c0-3.07-1.64-5.64-4.5-6.32V4c0-.83-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5v.68C7.63 5.36 6 7.92 6 11v5l-2 2v1h16v-1l-2-2z"/></svg>
    <span class="notification-badge" id="minor-violations-count">0</span>
  </span>
</a>
<a href="{{ route('sec_osa.escalationNotifications') }}" class="nav-link {{ request()->routeIs('sec_osa.escalationNotifications') ? 'active' : '' }}">
  <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
    <path stroke-linecap="round" stroke-linejoin="round" d="M10.34 15.84c-.688-.06-1.386-.09-2.09-.09H7.5a4.5 4.5 0 1 1 0-9h.75c.704 0 1.402-.03 2.09-.09m0 9.18c.253.962.584 1.892.985 2.783.247.55.06 1.21-.463 1.511l-.657.38c-.551.318-1.26.117-1.527-.461a20.845 20.845 0 0 1-1.44-4.282m3.102.069a18.03 18.03 0 0 1-.59-4.59c0-1.586.205-3.124.59-4.59m0 9.18a23.848 23.848 0 0 1 8.835 2.535M10.34 6.66a23.847 23.847 0 0 0 8.835-2.535m0 0A23.74 23.74 0 0 0 18.795 3m.38 1.125a23.91 23.91 0 0 1 1.014 5.395m-1.014 8.855c-.118.38-.245.754-.38 1.125m.38-1.125a23.91 23.91 0 0 0 1.014-5.395m0-3.46c.495.413.811 1.035.811 1.73 0 .695-.316 1.317-.811 1.73m0-3.46a24.347 24.347 0 0 1 0 3.46" />
  </svg>
  Escalation Notifications
  <span class="notification-icon" id="escalation-notifications-bell" style="display: none;">
    <svg fill="currentColor" viewBox="0 0 24 24"><path d="M12 22c1.1 0 2-.9 2-2h-4c0 1.1.9 2 2 2zm6-6v-5c0-3.07-1.64-5.64-4.5-6.32V4c0-.83-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5v.68C7.63 5.36 6 7.92 6 11v5l-2 2v1h16v-1l-2-2z"/></svg>
    <span class="notification-badge" id="escalation-notifications-count">0</span>
  </span>
</a>

<!-- ACCOUNT -->
<div class="sidebar-section">ACCOUNT</div>
<a href="{{ route('profile.edit') }}" class="nav-link {{ request()->routeIs('profile.edit') ? 'active' : '' }}">
  <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
    <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275" />
  </svg>
  Profile
</a>
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
/* Logout Form and Button Styles */
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
}

/* Notification Bell Styles - Now centralized in dashboard-layout */

/* Responsive */
@media (max-width: 768px) {
  .nav-link {
    padding: 16px 24px;
    font-size: 15px;
    min-height: 44px;
  }

  .nav-icon {
    width: 22px;
    height: 22px;
  }
}

@media (max-width: 480px) {
  .nav-link {
    padding: 18px 20px;
    font-size: 16px;
  }

  .nav-icon {
    width: 24px;
    height: 24px;
  }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
  // Fetch and update notification counts
  updateNotificationCounts();

  // Update notification counts every 30 seconds
  setInterval(updateNotificationCounts, 30000);
});

function updateNotificationCounts() {
  fetch('/sec_osa/notification-counts')
    .then(response => response.json())
    .then(data => {
      updateNotificationBell('pending-applications', data.printReadyApplications || 0);
      updateNotificationBell('major-violations', data.pendingMajorViolations || 0);
      updateNotificationBell('minor-violations', data.pendingMinorViolations || 0);
      updateNotificationBell('escalation-notifications', data.escalationNotifications || 0);
    })
    .catch(error => {
      console.log('Error fetching notification counts:', error);
    });
}

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
</script>