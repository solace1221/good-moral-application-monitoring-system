<!-- resources/views/head_osa/sidebar.blade.php -->
<aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-64'"
  class="w-64 bg-spupGreen text-dark min-h-screen fixed sm:relative left-0 transform transition-transform duration-300 sm:translate-x-0 z-10">
  <nav>
    <!-- Dashboard -->
    <a href="{{ route('head_osa.dashboard') }}"
      class="gap-2 h-20 items-center flex px-4 py-2 {{ request()->routeIs('head_osa.dashboard') ? 'bg-gray-800 text-spupGold' : 'text-white hover:bg-gray-800 hover:text-spupGold' }}">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-10 w-10 inline-block">
        <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
      </svg>
      Dashboard
    </a>

    <!-- Applications -->
    <a href="{{ route('head_osa.dashboard') }}"
      class="gap-2 h-20 items-center flex px-4 py-2 {{ request()->routeIs('head_osa.dashboard') ? 'bg-gray-800 text-spupGold' : 'text-white hover:bg-gray-800 hover:text-spupGold' }}" style="position: relative;">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-10">
        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z" />
      </svg>
      Applications
      <span class="notification-bell" id="head-osa-applications-bell" style="display: none; margin-left: auto; background: rgba(255, 193, 7, 0.1); padding: 2px 6px; border-radius: 12px; font-size: 11px; font-weight: 600; align-items: center; gap: 4px;">
        <svg style="width: 16px; height: 16px; color: #ffc107;" fill="currentColor" viewBox="0 0 24 24">
          <path d="M12 2C9.79 2 8 3.79 8 6v5.26a6.94 6.94 0 00-1.7.96L5 13v3h14v-3l-1.3-1.78a6.94 6.94 0 00-1.7-.96V6c0-2.21-1.79-4-4-4z"/>
        </svg>
        <span class="notification-count" id="head-osa-applications-count" style="color: #ffc107; min-width: 16px; text-align: center;">0</span>
      </span>
    </a>

    <!-- Designations -->
    <a href="{{ route('admin.designations.index') }}"
      class="gap-2 h-20 items-center flex px-4 py-2 {{ request()->routeIs('admin.designations.*') ? 'bg-gray-800 text-spupGold' : 'text-white hover:bg-gray-800 hover:text-spupGold' }}">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-10">
        <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 00.75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 00-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0112 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 01-.673-.38m0 0A2.18 2.18 0 013 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 013.413-.387m7.5 0V5.25A2.25 2.25 0 0013.5 3h-3a2.25 2.25 0 00-2.25 2.25v.894m7.5 0a48.667 48.667 0 00-7.5 0M12 12.75h.008v.008H12v-.008z" />
      </svg>
      Designations
    </a>

    <!-- Positions -->
    <a href="{{ route('admin.positions.index') }}"
      class="gap-2 h-20 items-center flex px-4 py-2 {{ request()->routeIs('admin.positions.*') ? 'bg-gray-800 text-spupGold' : 'text-white hover:bg-gray-800 hover:text-spupGold' }}">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-10">
        <path stroke-linecap="round" stroke-linejoin="round" d="M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5zm6-10.125a1.875 1.875 0 11-3.75 0 1.875 1.875 0 013.75 0zm1.294 6.336a6.721 6.721 0 01-3.17.789 6.721 6.721 0 01-3.168-.789 3.376 3.376 0 016.338 0z" />
      </svg>
      Positions
    </a>

    <!-- Profile -->
    <a href="{{ route('profile.edit') }}"
      class="gap-2 h-20 items-center flex px-4 py-2 {{ request()->routeIs('profile.edit') ? 'bg-gray-800 text-spupGold' : 'text-white hover:bg-gray-800 hover:text-spupGold' }}">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-10">
        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
      </svg>
      Profile
    </a>

    <!-- Logout -->
    <form method="POST" action="{{ route('logout') }}">
      @csrf
      <a href="{{ route('logout') }}"
        class="flex h-20 items-center gap-2 px-4 py-2 text-sm text-white hover:bg-gray-800 hover:text-red-600"
        onclick="event.preventDefault(); this.closest('form').submit();">
        <x-icon-logout class="w-10 h-10" />
        LOGOUT
      </a>
    </form>
  </nav>
</aside>

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
</style>

<script>
// Function to update Head OSA notification counts
function updateHeadOsaNotificationCounts() {
  fetch('/head-osa/notification-counts')
    .then(response => response.json())
    .then(data => {
      console.log('Head OSA notification data received:', data);
      
      // Update application notifications
      updateNotificationBell('head-osa-applications', data.applicationNotifications || 0);
    })
    .catch(error => {
      console.log('Error fetching Head OSA notification counts:', error);
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
  console.log('Head OSA sidebar loaded, initializing notifications...');
  
  updateHeadOsaNotificationCounts();
  
  // Update notification counts every 30 seconds
  setInterval(updateHeadOsaNotificationCounts, 30000);
});
</script>
