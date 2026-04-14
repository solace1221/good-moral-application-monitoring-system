<!-- PSG Officer Navigation -->
<a href="{{ route('PsgOfficer.dashboard') }}" class="nav-link {{ request()->routeIs('PsgOfficer.dashboard') ? 'active' : '' }}">
  <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
    <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
  </svg>
  Dashboard
</a>

<!-- Add Violator -->
<a href="{{ route('PsgOfficer.PsgAddViolation') }}" class="nav-link {{ request()->routeIs('PsgOfficer.PsgAddViolation') ? 'active' : '' }}">
  <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
  </svg>
  Add Violator
</a>

<!-- Violator List -->
<a href="{{ route('PsgOfficer.Violator') }}" class="nav-link {{ request()->routeIs('PsgOfficer.Violator') ? 'active' : '' }}">
  <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
  </svg>
  Violator List
</a>

<!-- Minor Violations -->
<a href="{{ route('PsgOfficer.PsgViolation') }}" class="nav-link {{ request()->routeIs('PsgOfficer.PsgViolation') ? 'active' : '' }}">
  <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
  </svg>
  Minor Violations
</a>

<!-- Apply for Good Moral -->
<a href="{{ route('PsgOfficer.goodMoralForm') }}" class="nav-link {{ request()->routeIs('PsgOfficer.goodMoralForm') ? 'active' : '' }}">
  <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5.586a1 1 0 0 1 .707.293l5.414 5.414a1 1 0 0 1 .293.707V19a2 2 0 0 1-2 2Z" />
  </svg>
  Apply for Good Moral
</a>

<!-- My Violations -->
<a href="{{ route('PsgOfficer.personalViolations') }}" class="nav-link {{ request()->routeIs('PsgOfficer.personalViolations') ? 'active' : '' }}">
  <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
  </svg>
  My Violations
</a>

<!-- PSG Applications -->
<a href="{{ route('PsgOfficer.applications') }}" class="nav-link {{ request()->routeIs('PsgOfficer.applications') ? 'active' : '' }}">
  <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" />
  </svg>
  Applications
</a>

<!-- Profile -->
<a href="{{ route('profile.edit') }}" class="nav-link {{ request()->routeIs('profile.edit') ? 'active' : '' }}">
  <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
  </svg>
  Profile
</a>

<!-- Logout -->
<form method="POST" action="{{ route('logout') }}" class="nav-logout-form">
  @csrf
  <button type="submit" class="nav-link nav-logout">
    <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
      <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15M12 9l-3 3m0 0 3 3m-3-3h12.75" />
    </svg>
    Logout
  </button>
</form>

<script>
function updatePsgNotificationCounts() {
  fetch('/psg-officer/notification-counts')
    .then(response => response.json())
    .then(data => {
      // Notification counts can be consumed by inline bell elements in individual views
    })
    .catch(() => {});
}
document.addEventListener('DOMContentLoaded', updatePsgNotificationCounts);
</script>
