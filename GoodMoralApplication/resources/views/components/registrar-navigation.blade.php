<!-- Good Moral Application -->
<a href="{{ route('registrar.goodMoralApplication') }}" class="nav-link {{ request()->routeIs('registrar.goodMoralApplication') ? 'active' : '' }}">
  <div style="position: relative; display: flex; align-items: center;">
    <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
    </svg>
    <span>Good Moral Application</span>
    <!-- Notification Bell for Pending Applications -->
    <div id="registrar-notification-bell" style="position: relative; margin-left: 8px; display: none;">
      <svg style="width: 20px; height: 20px; color: #ffc107;" fill="currentColor" viewBox="0 0 24 24">
        <path d="M12 22c1.1 0 2-.9 2-2h-4c0 1.1.9 2 2 2zm6-6v-5c0-3.07-1.64-5.64-4.5-6.32V4c0-.83-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5v.68C7.63 5.36 6 7.92 6 11v5l-2 2v1h16v-1l-2-2z"/>
      </svg>
      <span id="registrar-pending-count" style="position: absolute; top: -8px; right: -8px; background: #dc3545; color: white; border-radius: 50%; width: 18px; height: 18px; font-size: 10px; font-weight: bold; display: flex; align-items: center; justify-content: center;">
        0
      </span>
    </div>
  </div>
</a>

<!-- Profile -->
<a href="{{ route('profile.edit') }}" class="nav-link {{ request()->routeIs('profile.edit') ? 'active' : '' }}">
  <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
  </svg>
  Profile
</a>

<!-- Logout -->
<form method="POST" action="{{ route('logout') }}" class="nav-logout-form">
  @csrf
  <button type="submit" class="nav-link nav-logout">
    <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
    </svg>
    Logout
  </button>
</form>

<script>
// Function to update registrar notification counts
function updateRegistrarNotificationCounts() {
  fetch('{{ route("registrar.notificationCounts") }}')
    .then(response => response.json())
    .then(data => {
      // Update pending applications count
      const pendingCount = data.pendingApplications || 0;
      const bellElement = document.getElementById('registrar-notification-bell');
      const countElement = document.getElementById('registrar-pending-count');

      if (pendingCount > 0) {
        bellElement.style.display = 'block';
        countElement.textContent = pendingCount > 99 ? '99+' : pendingCount;
      } else {
        bellElement.style.display = 'none';
      }
    })
    .catch(error => {
      console.error('Error fetching registrar notification counts:', error);
    });
}

// Update notification counts on page load
document.addEventListener('DOMContentLoaded', function() {
  // Fetch and update notification counts
  updateRegistrarNotificationCounts();

  // Update notification counts every 30 seconds
  setInterval(updateRegistrarNotificationCounts, 30000);
});
</script>
