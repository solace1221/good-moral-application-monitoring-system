<!-- DASHBOARD -->
<div class="sidebar-section">DASHBOARD</div>
<a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
  <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 7.125C2.25 6.504 2.754 6 3.375 6h6c.621 0 1.125.504 1.125 1.125v3.75c0 .621-.504 1.125-1.125 1.125h-6a1.125 1.125 0 0 1-1.125-1.125v-3.75ZM14.25 8.625c0-.621.504-1.125 1.125-1.125h5.25c.621 0 1.125.504 1.125 1.125v8.25c0 .621-.504 1.125-1.125 1.125h-5.25a1.125 1.125 0 0 1-1.125-1.125v-8.25ZM3.75 16.125c0-.621.504-1.125 1.125-1.125h5.25c.621 0 1.125.504 1.125 1.125v2.25c0 .621-.504 1.125-1.125 1.125h-5.25a1.125 1.125 0 0 1-1.125-1.125v-2.25Z" />
  </svg>
  Dashboard
</a>

<!-- CERTIFICATES -->
<div class="sidebar-section">CERTIFICATES</div>
<a href="{{ route('admin.Application') }}" class="nav-link {{ request()->routeIs('admin.Application') ? 'active' : '' }}">
  <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
  </svg>
  Pending Applications
  <span class="notification-icon" id="pending-applications-bell" style="display: none;">
    <svg fill="currentColor" viewBox="0 0 24 24"><path d="M12 22c1.1 0 2-.9 2-2h-4c0 1.1.9 2 2 2zm6-6v-5c0-3.07-1.64-5.64-4.5-6.32V4c0-.83-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5v.68C7.63 5.36 6 7.92 6 11v5l-2 2v1h16v-1l-2-2z"/></svg>
    <span class="notification-badge" id="pending-applications-count">0</span>
  </span>
</a>
<a href="{{ route('admin.readyForPrintApplications') }}" class="nav-link {{ request()->routeIs('admin.readyForPrintApplications') ? 'active' : '' }}">
  <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
    <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0 1 10.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0 .229 2.523a1.125 1.125 0 0 1-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0 0 21 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 0 0-1.913-.247M18.25 9.456v5.294M7.75 9.456A48.833 48.833 0 0 1 12 9.75c1.414 0 2.775.062 4.083.181M7.75 9.456v5.294" />
  </svg>
  Certificate Applications
</a>

<!-- VIOLATIONS -->
<div class="sidebar-section">VIOLATIONS</div>
<a href="{{ route('admin.violation') }}" class="nav-link {{ request()->routeIs('admin.violation') ? 'active' : '' }}">
  <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
  </svg>
  View Violations
  <span class="notification-icon" id="view-violations-bell" style="display: none;">
    <svg fill="currentColor" viewBox="0 0 24 24"><path d="M12 22c1.1 0 2-.9 2-2h-4c0 1.1.9 2 2 2zm6-6v-5c0-3.07-1.64-5.64-4.5-6.32V4c0-.83-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5v.68C7.63 5.36 6 7.92 6 11v5l-2 2v1h16v-1l-2-2z"/></svg>
    <span class="notification-badge" id="view-violations-count">0</span>
  </span>
</a>
<a href="{{ route('admin.AddViolation') }}" class="nav-link {{ request()->routeIs('admin.AddViolation') ? 'active' : '' }}">
  <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
  </svg>
  Add Violation
</a>
<a href="{{ route('admin.AddViolator') }}" class="nav-link {{ request()->routeIs('admin.AddViolator') ? 'active' : '' }}">
  <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
    <path stroke-linecap="round" stroke-linejoin="round" d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766Z" />
  </svg>
  Add Violator
</a>
<a href="{{ route('admin.studentOfficerApplications') }}" class="nav-link {{ request()->routeIs('admin.studentOfficerApplications') ? 'active' : '' }}">
  <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
    <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443a55.381 55.381 0 0 1 5.25 2.882V15" />
  </svg>
  Student Officer Applications
  <span class="notification-icon" id="officer-applications-bell" style="display: none;">
    <svg fill="currentColor" viewBox="0 0 24 24"><path d="M12 22c1.1 0 2-.9 2-2h-4c0 1.1.9 2 2 2zm6-6v-5c0-3.07-1.64-5.64-4.5-6.32V4c0-.83-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5v.68C7.63 5.36 6 7.92 6 11v5l-2 2v1h16v-1l-2-2z"/></svg>
    <span class="notification-badge" id="officer-applications-count">0</span>
  </span>
</a>
<a href="{{ route('admin.escalationNotifications') }}" class="nav-link {{ request()->routeIs('admin.escalationNotifications') ? 'active' : '' }}">
  <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
    <path stroke-linecap="round" stroke-linejoin="round" d="M10.34 15.84c-.688-.06-1.386-.09-2.09-.09H7.5a4.5 4.5 0 1 1 0-9h.75c.704 0 1.402-.03 2.09-.09m0 9.18c.253.962.584 1.892.985 2.783.247.55.06 1.21-.463 1.511l-.657.38c-.551.318-1.26.117-1.527-.461a20.845 20.845 0 0 1-1.44-4.282m3.102.069a18.03 18.03 0 0 1-.59-4.59c0-1.586.205-3.124.59-4.59m0 9.18a23.848 23.848 0 0 1 8.835 2.535M10.34 6.66a23.847 23.847 0 0 0 8.835-2.535m0 0A23.74 23.74 0 0 0 18.795 3m.38 1.125a23.91 23.91 0 0 1 1.014 5.395m-1.014 8.855c-.118.38-.245.754-.38 1.125m.38-1.125a23.91 23.91 0 0 0 1.014-5.395m0-3.46c.495.413.811 1.035.811 1.73 0 .695-.316 1.317-.811 1.73m0-3.46a24.347 24.347 0 0 1 0 3.46" />
  </svg>
  Escalation Notifications
  <span class="notification-icon" id="escalation-notifications-bell" style="display: none;">
    <svg fill="currentColor" viewBox="0 0 24 24"><path d="M12 22c1.1 0 2-.9 2-2h-4c0 1.1.9 2 2 2zm6-6v-5c0-3.07-1.64-5.64-4.5-6.32V4c0-.83-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5v.68C7.63 5.36 6 7.92 6 11v5l-2 2v1h16v-1l-2-2z"/></svg>
    <span class="notification-badge" id="escalation-notifications-count">0</span>
  </span>
</a>

<!-- REPORTS -->
<div class="sidebar-section">REPORTS</div>
<a href="{{ route('admin.reports') }}" class="nav-link {{ request()->routeIs('admin.reports') ? 'active' : '' }}">
  <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
  </svg>
  Generate Reports
</a>

<!-- SYSTEM MANAGEMENT -->
<div class="sidebar-section">SYSTEM MANAGEMENT</div>
<a href="{{ route('registeraccount') }}" class="nav-link {{ request()->routeIs('registeraccount') ? 'active' : '' }}">
  <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
    <path stroke-linecap="round" stroke-linejoin="round" d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766Z" />
  </svg>
  Manage Users
</a>
<a href="{{ route('admin.departments.index') }}" class="nav-link {{ request()->routeIs('admin.departments.*') ? 'active' : '' }}">
  <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" />
  </svg>
  Departments
</a>
<a href="{{ route('admin.courses.index') }}" class="nav-link {{ request()->routeIs('admin.courses.*') ? 'active' : '' }}">
  <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
    <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443a55.381 55.381 0 0 1 5.25 2.882V15" />
  </svg>
  Courses
</a>
<a href="{{ route('admin.organizations.index') }}" class="nav-link {{ request()->routeIs('admin.organizations.*') ? 'active' : '' }}">
  <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
    <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 0 0 .75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 0 0-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0 1 12 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 0 1-.673-.38m0 0A2.18 2.18 0 0 1 3 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 0 1 3.413-.387m7.5 0V5.25A2.25 2.25 0 0 0 13.5 3h-3a2.25 2.25 0 0 0-2.25 2.25v.894m7.5 0a48.667 48.667 0 0 0-7.5 0M12 12.75h.008v.008H12v-.008Z" />
  </svg>
  Organizations
</a>
<a href="{{ route('admin.positions.index') }}" class="nav-link {{ request()->routeIs('admin.positions.*') ? 'active' : '' }}">
  <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
    <path stroke-linecap="round" stroke-linejoin="round" d="M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Zm6-10.125a1.875 1.875 0 1 1-3.75 0 1.875 1.875 0 0 1 3.75 0Zm1.294 6.336a6.721 6.721 0 0 1-3.17.789 6.721 6.721 0 0 1-3.168-.789 3.376 3.376 0 0 1 6.338 0Z" />
  </svg>
  Positions
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



<script>
document.addEventListener('DOMContentLoaded', function() {



  // Fetch and update notification counts
  updateNotificationCounts();

  // Update notification counts every 30 seconds
  setInterval(updateNotificationCounts, 30000);
});

// Function to update notification counts
function updateNotificationCounts() {
  fetch('/admin/notification-counts')
    .then(response => response.json())
    .then(data => {
      // Update pending applications
      updateNotificationBell('pending-applications', data.pendingApplications || 0);

      // Update student officer applications
      updateNotificationBell('officer-applications', data.psgApplications || 0);

      // Update violations
      updateNotificationBell('view-violations', data.pendingViolations || 0);

      // Update escalation notifications
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
      bell.style.display = 'inline-flex';
      countElement.textContent = count;
    } else {
      bell.style.display = 'none';
    }
  }
}
</script>
