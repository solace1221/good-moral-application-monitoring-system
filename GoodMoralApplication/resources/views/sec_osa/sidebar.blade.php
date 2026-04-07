<!-- resources/views/sec_osa/sidebar.blade.php -->
<aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-64'"
  class="w-64 bg-spupGreen text-dark min-h-screen fixed sm:relative left-0 transform transition-transform duration-300 sm:translate-x-0 z-10">
  <nav>
    <!-- Dashboard -->
    <a href="{{ route('sec_osa.dashboard') }}"
      class="gap-2 h-20 items-center flex px-4 py-2 {{ request()->routeIs('sec_osa.dashboard') ? 'bg-gray-800 text-spupGold' : 'text-white hover:bg-gray-800 hover:text-spupGold' }}">
      <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-layout-dashboard">
        <rect width="7" height="9" x="3" y="3" rx="1"/>
        <rect width="7" height="5" x="14" y="3" rx="1"/>
        <rect width="7" height="9" x="14" y="12" rx="1"/>
        <rect width="7" height="5" x="3" y="16" rx="1"/>
      </svg>
      Dashboard
    </a>

    <!-- Applications -->
    <a href="{{ route('sec_osa.application') }}"
      class="gap-2 h-20 items-center flex px-4 py-2 {{ request()->routeIs('sec_osa.application') ? 'bg-gray-800 text-spupGold' : 'text-white hover:bg-gray-800 hover:text-spupGold' }}">
      <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-file-text">
        <path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/>
        <path d="M14 2v4a2 2 0 0 0 2 2h4"/>
        <path d="M10 9H8"/>
        <path d="M16 13H8"/>
        <path d="M16 17H8"/>
      </svg>
      Applications
      <span class="notification-bell" id="pending-applications-bell" style="display: none; margin-left: auto; background: rgba(255, 193, 7, 0.1); padding: 2px 6px; border-radius: 12px; font-size: 11px; font-weight: 600; align-items: center; gap: 4px;">
        <svg style="width: 16px; height: 16px; color: #ffc107;" fill="currentColor" viewBox="0 0 24 24">
          <path d="M12 2C9.79 2 8 3.79 8 6v5.26a6.94 6.94 0 00-1.7.96L5 13v3h14v-3l-1.3-1.78a6.94 6.94 0 00-1.7-.96V6c0-2.21-1.79-4-4-4z"/>
        </svg>
        <span class="notification-count" id="pending-applications-count" style="color: #ffc107; min-width: 16px; text-align: center;">0</span>
      </span>
    </a>

    <!-- Violations Dropdown -->
    <div class="violations-dropdown">
      <a href="{{ route('sec_osa.violation') }}"
        class="gap-2 h-20 items-center flex px-4 py-2 w-full {{ request()->routeIs('sec_osa.violation') ? 'bg-gray-800 text-spupGold' : 'text-white hover:bg-gray-800 hover:text-spupGold' }}">
        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-alert-triangle">
          <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/>
          <path d="M12 9v4"/>
          <path d="m12 17 .01 0"/>
        </svg>
        Violations
        <button type="button" onclick="toggleViolationsDropdown()" class="ml-auto">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-down dropdown-arrow" style="transition: transform 0.3s ease;">
            <path d="m6 9 6 6 6-6"/>
          </svg>
        </button>
      </a>
      
      <!-- Dropdown Items -->
      <div id="violations-dropdown" class="dropdown-content" style="display: none; background: #16a085; margin-left: 20px;">
        <!-- Minor Violations -->
        <a href="{{ route('sec_osa.minor') }}"
          class="gap-2 h-16 items-center flex px-4 py-2 {{ request()->routeIs('sec_osa.minor') ? 'bg-gray-800 text-spupGold' : 'text-white hover:bg-gray-700 hover:text-spupGold' }}" style="border-left: 3px solid #3498db;">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-dot">
            <circle cx="12" cy="12" r="10"/>
            <circle cx="12" cy="12" r="1"/>
          </svg>
          <span class="font-medium">Minor Violations</span>
          <span class="notification-bell" id="minor-violations-bell" style="display: none; margin-left: auto; background: rgba(255, 193, 7, 0.1); padding: 2px 6px; border-radius: 12px; font-size: 11px; font-weight: 600; align-items: center; gap: 4px;">
            <svg style="width: 16px; height: 16px; color: #ffc107;" fill="currentColor" viewBox="0 0 24 24">
              <path d="M12 2C9.79 2 8 3.79 8 6v5.26a6.94 6.94 0 00-1.7.96L5 13v3h14v-3l-1.3-1.78a6.94 6.94 0 00-1.7-.96V6c0-2.21-1.79-4-4-4z"/>
            </svg>
            <span class="notification-count" id="minor-violations-count" style="color: #ffc107; min-width: 16px; text-align: center;">0</span>
          </span>
        </a>

        <!-- Major Violations -->
        <a href="{{ route('sec_osa.major') }}"
          class="gap-2 h-16 items-center flex px-4 py-2 {{ request()->routeIs('sec_osa.major') ? 'bg-gray-800 text-spupGold' : 'text-white hover:bg-gray-700 hover:text-spupGold' }}" style="border-left: 3px solid #e74c3c;">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-off">
            <path d="m2 2 20 20"/>
            <path d="M8.35 2.69A10 10 0 0 1 21.3 15.65"/>
            <path d="M19.08 19.08A10 10 0 1 1 4.92 4.92"/>
          </svg>
          <span class="font-medium">Major Violations</span>
          <span class="notification-bell" id="major-violations-bell" style="display: none; margin-left: auto; background: rgba(220, 53, 69, 0.1); padding: 2px 6px; border-radius: 12px; font-size: 11px; font-weight: 600; align-items: center; gap: 4px;">
            <svg style="width: 16px; height: 16px; color: #dc3545;" fill="currentColor" viewBox="0 0 24 24">
              <path d="M12 2C9.79 2 8 3.79 8 6v5.26a6.94 6.94 0 00-1.7.96L5 13v3h14v-3l-1.3-1.78a6.94 6.94 0 00-1.7-.96V6c0-2.21-1.79-4-4-4z"/>
            </svg>
            <span class="notification-count" id="major-violations-count" style="color: #dc3545; min-width: 16px; text-align: center;">0</span>
          </span>
        </a>
      </div>
    </div>

    <!-- Profile -->
    <a href="{{ route('sec_osa.profile') }}"
      class="gap-2 h-20 items-center flex px-4 py-2 {{ request()->routeIs('sec_osa.profile') ? 'bg-gray-800 text-spupGold' : 'text-white hover:bg-gray-800 hover:text-spupGold' }}">
      <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user">
        <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/>
        <circle cx="12" cy="7" r="4"/>
      </svg>
      Profile
    </a>

    <!-- Logout -->
    <form method="POST" action="{{ route('logout') }}">
      @csrf
      <a href="{{ route('logout') }}"
        class="gap-2 h-20 items-center flex px-4 py-2 text-white hover:bg-gray-800 hover:text-red-600"
        onclick="event.preventDefault(); this.closest('form').submit();">
        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-log-out">
          <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
          <polyline points="16,17 21,12 16,7"/>
          <line x1="21" y1="12" x2="9" y2="12"/>
        </svg>
        <span class="font-medium">LOGOUT</span>
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
// Function to update Sec OSA notification counts
function updateSecOsaNotificationCounts() {
  fetch('/sec-osa/notification-counts')
    .then(response => response.json())
    .then(data => {
      console.log('Sec OSA notification data received:', data);

      // Update application notifications
      updateNotificationBell('sec-osa-applications', data.applicationNotifications || 0);

      // Update minor violation notifications
      updateNotificationBell('sec-osa-minor-violations', data.minorViolationNotifications || 0);

      // Update major violation notifications
      updateNotificationBell('sec-osa-major-violations', data.majorViolationNotifications || 0);
    })
    .catch(error => {
      console.log('Error fetching Sec OSA notification counts:', error);
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
  console.log('Sec OSA sidebar loaded, initializing notifications...');

  updateSecOsaNotificationCounts();

  // Update notification counts every 30 seconds
  setInterval(updateSecOsaNotificationCounts, 30000);
  
  // Initialize Violations dropdown toggle
  function toggleViolationsDropdown() {
    const dropdown = document.getElementById('violations-dropdown');
    const arrow = document.querySelector('.dropdown-arrow');
    
    if (dropdown.style.display === 'none' || dropdown.style.display === '') {
      dropdown.style.display = 'block';
      arrow.style.transform = 'rotate(180deg)';
    } else {
      dropdown.style.display = 'none';
      arrow.style.transform = 'rotate(0)';
    }
  }
});
</script>