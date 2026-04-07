<!-- Program Coordinator Navigation -->
<a href="{{ route('prog_coor.major') }}" class="nav-link {{ request()->routeIs('prog_coor.major') ? 'active' : '' }}">
  <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" class="nav-icon text-red-500">
    <path fill-rule="evenodd" d="M11.484 2.17a.75.75 0 011.032 0C14.082 3.45 15.55 4.75 17.75 6.5c2.2 1.75 2.25 2.75 2.25 5.25 0 2.5-.05 3.5-2.25 5.25-2.2 1.75-3.668 3.05-5.234 4.33a.75.75 0 01-1.032 0C9.918 20.05 8.45 18.75 6.25 17c-2.2-1.75-2.25-2.75-2.25-5.25 0-2.5.05-3.5 2.25-5.25 2.2-1.75 3.668-3.05 5.234-4.33zM12 8.25a.75.75 0 01.75.75v3.75a.75.75 0 01-1.5 0V9a.75.75 0 01.75-.75zm0 8.25a.75.75 0 100-1.5.75.75 0 000 1.5z" clip-rule="evenodd" />
  </svg>
  Major Violations
</a>

<!-- Profile -->
<a href="{{ route('profile.edit') }}" class="nav-link {{ request()->routeIs('profile.edit') ? 'active' : '' }}">
  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="nav-icon">
    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
  </svg>
  Profile
</a>

<!-- Logout Section -->
<form method="POST" action="{{ route('logout') }}" class="nav-logout-form">
  @csrf
  <button type="submit" class="nav-link nav-logout">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="nav-icon">
      <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
    </svg>
    Logout
  </button>
</form>
