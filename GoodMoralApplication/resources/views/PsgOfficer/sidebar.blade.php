<!-- resources/views/psgOfficer/sidebar.blade.php -->
<aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-64'"
  class="w-64 bg-spupGreen text-dark min-h-screen fixed sm:relative left-0 transform transition-transform duration-300 sm:translate-x-0 z-10">
  <nav>
    <!-- Dashboard -->
    <a href="{{ route('PsgOfficer.dashboard') }}"
      class="gap-2 px-4 py-2 hover:bg-gray-800 hover:text-spupGold relative h-20 items-center flex {{ request()->routeIs('PsgOfficer.dashboard') ? 'bg-gray-800 text-spupGold' : 'text-white hover:bg-gray-800 hover:text-spupGold' }}">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-10 w-10 inline-block">
        <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
      </svg>
      Dashboard
    </a>

    <!-- Add Violator -->
    <a href="{{ route('PsgOfficer.PsgAddViolation') }}"
      class="gap-2 px-4 py-2 hover:bg-gray-700 hover:text-gray-300 relative h-20 items-center flex
           {{ request()->routeIs('PsgOfficer.PsgAddViolation') ? 'bg-gray-800 text-spupGold' : 'text-white hover:bg-gray-800 hover:text-spupGold' }}">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-10 w-10 inline-block">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
      </svg>
      Add Violator
      <span class="notification-bell" id="psg-add-violator-bell" style="display: none; margin-left: auto; background: rgba(255, 193, 7, 0.1); padding: 2px 6px; border-radius: 12px; font-size: 11px; font-weight: 600; align-items: center; gap: 4px;">
        <svg style="width: 16px; height: 16px; color: #ffc107;" fill="currentColor" viewBox="0 0 24 24">
          <path d="M12 2C9.79 2 8 3.79 8 6v5.26a6.94 6.94 0 00-1.7.96L5 13v3h14v-3l-1.3-1.78a6.94 6.94 0 00-1.7-.96V6c0-2.21-1.79-4-4-4z"/>
        </svg>
        <span class="notification-count" id="psg-add-violator-count" style="color: #ffc107; min-width: 16px; text-align: center;">0</span>
      </span>
    </a>

    <!-- Violator -->
    <a href="{{ route('PsgOfficer.Violator') }}"
      class="gap-2 px-4 py-2 hover:bg-gray-700 hover:text-gray-300 relative h-20 items-center flex
           {{ request()->routeIs('PsgOfficer.Violator') ? 'bg-gray-800 text-spupGold' : 'text-white hover:bg-gray-800 hover:text-spupGold' }}">
      <!-- New SVG Icon -->
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-10">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 2.25C6.72 2.25 2.25 6.72 2.25 12s4.47 9.75 9.75 9.75 9.75-4.47 9.75-9.75S17.28 2.25 12 2.25zM12 17.25a5.25 5.25 0 1 1 0-10.5 5.25 5.25 0 0 1 0 10.5z" />
      </svg>
      Violator
      <span class="notification-bell" id="psg-violator-bell" style="display: none; margin-left: auto; background: rgba(255, 193, 7, 0.1); padding: 2px 6px; border-radius: 12px; font-size: 11px; font-weight: 600; align-items: center; gap: 4px;">
        <svg style="width: 16px; height: 16px; color: #ffc107;" fill="currentColor" viewBox="0 0 24 24">
          <path d="M12 2C9.79 2 8 3.79 8 6v5.26a6.94 6.94 0 00-1.7.96L5 13v3h14v-3l-1.3-1.78a6.94 6.94 0 00-1.7-.96V6c0-2.21-1.79-4-4-4z"/>
        </svg>
        <span class="notification-count" id="psg-violator-count" style="color: #ffc107; min-width: 16px; text-align: center;">0</span>
      </span>
    </a>

    <!-- Profile -->
    <a href="{{ route('student.profile') }}"
      class="gap-2 px-4 py-2 hover:bg-gray-700 hover:text-gray-300 relative h-20 items-center flex
           {{ request()->routeIs('student.profile') ? 'bg-gray-800 text-spupGold' : 'text-white hover:bg-gray-800 hover:text-spupGold' }}">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-10 w-10 inline-block">
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
// Function to update PSG Officer notification counts
function updatePsgNotificationCounts() {
  fetch('/psg-officer/notification-counts')
    .then(response => response.json())
    .then(data => {
      console.log('PSG Officer notification data received:', data);

      // Update violator notifications
      updateNotificationBell('psg-violator', data.violatorNotifications || 0);

      // Update add violator notifications (if any)
      updateNotificationBell('psg-add-violator', data.addViolatorNotifications || 0);
    })
    .catch(error => {
      console.log('Error fetching PSG Officer notification counts:', error);
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
  console.log('PSG Officer sidebar loaded, initializing notifications...');

  updatePsgNotificationCounts();

  // Update notification counts every 30 seconds
  setInterval(updatePsgNotificationCounts, 30000);
});
</script>