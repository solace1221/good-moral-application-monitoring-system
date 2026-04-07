<!-- resources/views/admin/sidebar.blade.php -->
<aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-64'"
  class="w-64 bg-spupGreen text-dark min-h-screen fixed sm:relative left-0 transform transition-transform duration-300 sm:translate-x-0 z-10">
  <nav>
    <!-- Dashboard -->
    <a href="{{ route('dean.dashboard') }}"
      class="gap-2 px-4 py-2 hover:bg-gray-800 hover:text-spupGold relative h-20 items-center flex {{ request()->routeIs('dean.dashboard') ? 'bg-gray-800 text-spupGold' : 'text-white hover:bg-gray-800 hover:text-spupGold' }}">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-10 w-10 inline-block">
        <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
      </svg>
      Dashboard
    </a>

    <!-- Good Moral Application -->
    <a href="{{ route('dean.application') }}"
      class="gap-2 h-20 items-center flex px-4 py-2 hover:bg-gray-800 hover:text-spupGold {{ request()->routeIs('dean.application') ? 'bg-gray-800 text-spupGold' : 'text-white hover:bg-gray-800 hover:text-spupGold' }}">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-10">
        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
      </svg>
      Applications
      <span class="notification-bell" id="dean-applications-bell" style="display: none; margin-left: auto; background: rgba(255, 193, 7, 0.2); padding: 4px 8px; border-radius: 16px; font-size: 12px; font-weight: 700; align-items: center; gap: 6px; flex-direction: row; border: 1px solid rgba(255, 193, 7, 0.3);">
        <svg style="width: 14px; height: 14px; color: #ffc107;" fill="currentColor" viewBox="0 0 24 24">
          <path d="M12 2C9.79 2 8 3.79 8 6v5.26a6.94 6.94 0 00-1.7.96L5 13v3h14v-3l-1.3-1.78a6.94 6.94 0 00-1.7-.96V6c0-2.21-1.79-4-4-4z"/>
        </svg>
        <span class="notification-count" id="dean-applications-count" style="color: #ffc107; min-width: 18px; text-align: center; font-weight: 700; font-size: 12px;">0</span>
      </span>
    </a>
    <!-- Major Violations -->
        <!-- Violations Dropdown -->
    <div x-data="{ violationsOpen: false }" class="relative">
      <!-- Violations Dropdown Toggle -->
      <button @click="violationsOpen = !violationsOpen" 
        class="gap-2 h-20 items-center flex px-4 py-2 w-full text-left hover:bg-gray-800 hover:text-spupGold text-white hover:bg-gray-800 hover:text-spupGold focus:outline-none">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-10">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m0 3.75h.008M10.29 3.86L1.82 18a1.5 1.5 0 001.29 2.25h17.78a1.5 1.5 0 001.29-2.25L13.71 3.86a1.5 1.5 0 00-2.42 0z" />
        </svg>
        Violations
        <!-- Combined notification badges for violations -->
        <div class="flex gap-1 ml-auto">
          <span class="notification-bell" id="dean-major-violations-bell" style="display: none; background: rgba(220, 53, 69, 0.2); padding: 4px 8px; border-radius: 16px; font-size: 12px; font-weight: 700; align-items: center; gap: 6px; flex-direction: row; border: 1px solid rgba(220, 53, 69, 0.3);">
            <svg style="width: 14px; height: 14px; color: #dc3545;" fill="currentColor" viewBox="0 0 24 24">
              <path d="M12 2C9.79 2 8 3.79 8 6v5.26a6.94 6.94 0 00-1.7.96L5 13v3h14v-3l-1.3-1.78a6.94 6.94 0 00-1.7-.96V6c0-2.21-1.79-4-4-4z"/>
            </svg>
            <span class="notification-count" id="dean-major-violations-count" style="color: #dc3545; min-width: 18px; text-align: center; font-weight: 700; font-size: 12px;">0</span>
          </span>
          <span class="notification-bell" id="dean-minor-violations-bell" style="display: none; background: rgba(255, 193, 7, 0.2); padding: 4px 8px; border-radius: 16px; font-size: 12px; font-weight: 700; align-items: center; gap: 6px; flex-direction: row; border: 1px solid rgba(255, 193, 7, 0.3);">
            <svg style="width: 14px; height: 14px; color: #ffc107;" fill="currentColor" viewBox="0 0 24 24">
              <path d="M12 2C9.79 2 8 3.79 8 6v5.26a6.94 6.94 0 00-1.7.96L5 13v3h14v-3l-1.3-1.78a6.94 6.94 0 00-1.7-.96V6c0-2.21-1.79-4-4-4z"/>
            </svg>
            <span class="notification-count" id="dean-minor-violations-count" style="color: #ffc107; min-width: 18px; text-align: center; font-weight: 700; font-size: 12px;">0</span>
          </span>
        </div>
        <!-- Dropdown Arrow -->
        <svg x-bind:class="violationsOpen ? 'rotate-180' : ''" class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
        </svg>
      </button>

      <!-- Violations Dropdown Menu -->
      <div x-show="violationsOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-95" class="bg-gray-700">
        <!-- Major Violations -->
        <a href="{{ route('dean.major') }}" class="gap-3 h-16 items-center flex px-6 py-2 hover:bg-gray-600 hover:text-spupGold {{ request()->routeIs('dean.major') ? 'bg-gray-600 text-spupGold' : 'text-gray-300' }} border-l-4 border-red-500">
          <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" class="size-8 text-red-500">
            <path fill-rule="evenodd" d="M11.484 2.17a.75.75 0 011.032 0C14.082 3.45 15.55 4.75 17.75 6.5c2.2 1.75 2.25 2.75 2.25 5.25 0 2.5-.05 3.5-2.25 5.25-2.2 1.75-3.668 3.05-5.234 4.33a.75.75 0 01-1.032 0C9.918 20.05 8.45 18.75 6.25 17c-2.2-1.75-2.25-2.75-2.25-5.25 0-2.5.05-3.5 2.25-5.25 2.2-1.75 3.668-3.05 5.234-4.33zM12 8.25a.75.75 0 01.75.75v3.75a.75.75 0 01-1.5 0V9a.75.75 0 01.75-.75zm0 8.25a.75.75 0 100-1.5.75.75 0 000 1.5z" clip-rule="evenodd" />
          </svg>
          Major Violations
        </a>

        <!-- Minor Violations -->
        <a href="{{ route('dean.minor') }}" class="gap-3 h-16 items-center flex px-6 py-2 hover:bg-gray-600 hover:text-spupGold {{ request()->routeIs('dean.minor') ? 'bg-gray-600 text-spupGold' : 'text-gray-300' }} border-l-4 border-yellow-500">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-8 text-yellow-500">
            <path fill-rule="evenodd" d="M11.484 2.17a.75.75 0 011.032 0C14.082 3.45 15.55 4.75 17.75 6.5c2.2 1.75 2.25 2.75 2.25 5.25 0 2.5-.05 3.5-2.25 5.25-2.2 1.75-3.668 3.05-5.234 4.33a.75.75 0 01-1.032 0C9.918 20.05 8.45 18.75 6.25 17c-2.2-1.75-2.25-2.75-2.25-5.25 0-2.5.05-3.5 2.25-5.25 2.2-1.75 3.668-3.05 5.234-4.33zM12 8.25a.75.75 0 01.75.75v3.75a.75.75 0 01-1.5 0V9a.75.75 0 01.75-.75zm0 8.25a.75.75 0 100-1.5.75.75 0 000 1.5z" clip-rule="evenodd" />
          </svg>
          Minor Violations
        </a>
      </div>
    </div>

    <a href="{{ route('dean.minor') }}"
      class="gap-2 h-20 items-center flex px-4 py-2 hover:bg-gray-800 hover:text-spupGold {{ request()->routeIs('dean.minor') ? 'bg-gray-800 text-spupGold' : 'text-white hover:bg-gray-800 hover:text-spupGold' }}">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
        stroke="currentColor" class="size-10">
        <path stroke-linecap="round" stroke-linejoin="round"
          d="M6 3v18m0 0h.01M6 3h8.25a1.5 1.5 0 011.18 2.42L13.5 9l1.93 3.58a1.5 1.5 0 01-1.18 2.42H6" />
      </svg>
      Minor Violations
      <span class="notification-bell" id="dean-minor-violations-bell" style="display: none; margin-left: auto; background: rgba(255, 193, 7, 0.2); padding: 4px 8px; border-radius: 16px; font-size: 12px; font-weight: 700; align-items: center; gap: 6px; flex-direction: row; border: 1px solid rgba(255, 193, 7, 0.3);">
        <svg style="width: 14px; height: 14px; color: #ffc107;" fill="currentColor" viewBox="0 0 24 24">
          <path d="M12 2C9.79 2 8 3.79 8 6v5.26a6.94 6.94 0 00-1.7.96L5 13v3h14v-3l-1.3-1.78a6.94 6.94 0 00-1.7-.96V6c0-2.21-1.79-4-4-4z"/>
        </svg>
        <span class="notification-count" id="dean-minor-violations-count" style="color: #ffc107; min-width: 18px; text-align: center; font-weight: 700; font-size: 12px;">0</span>
      </span>
    </a>

    <!-- Profile -->
    <a href="{{ route('profile.edit') }}"
      class="gap-2 h-20 items-center flex px-4 py-2 hover:bg-gray-800 hover:text-spupGold {{ request()->routeIs('profile.edit') ? 'bg-gray-800 text-spupGold' : 'text-white hover:bg-gray-800 hover:text-spupGold' }}">
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
      // Optionally show a subtle error indicator
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
  console.log('Dean sidebar loaded, initializing notifications...');

  // Force show notification bells for testing (remove this in production)
  setTimeout(() => {
    console.log('Forcing notification bells to show for testing...');
    updateNotificationBell('dean-applications', 3);
    updateNotificationBell('dean-major-violations', 1);
    updateNotificationBell('dean-minor-violations', 5);
  }, 1000);

  updateDeanNotificationCounts();

  // Update notification counts every 30 seconds
  setInterval(updateDeanNotificationCounts, 30000);

  // Add manual refresh button for testing (always show for now)
  addDebugNotificationButton();
});

// Debug function to test notifications
function addDebugNotificationButton() {
  const debugButton = document.createElement('button');
  debugButton.textContent = 'Test Notifications';
  debugButton.style.cssText = 'position: fixed; top: 10px; right: 10px; z-index: 9999; padding: 8px 12px; background: #28a745; color: white; border: none; border-radius: 6px; font-size: 14px; cursor: pointer; box-shadow: 0 2px 4px rgba(0,0,0,0.2);';
  debugButton.onclick = function() {
    console.log('Testing notification bells...');
    // Test with sample data
    const apps = Math.floor(Math.random() * 10) + 1;
    const major = Math.floor(Math.random() * 5) + 1;
    const minor = Math.floor(Math.random() * 15) + 1;

    console.log(`Setting notifications: Apps=${apps}, Major=${major}, Minor=${minor}`);
    updateNotificationBell('dean-applications', apps);
    updateNotificationBell('dean-major-violations', major);
    updateNotificationBell('dean-minor-violations', minor);
  };
  document.body.appendChild(debugButton);

  // Add a clear button too
  const clearButton = document.createElement('button');
  clearButton.textContent = 'Clear Notifications';
  clearButton.style.cssText = 'position: fixed; top: 50px; right: 10px; z-index: 9999; padding: 8px 12px; background: #dc3545; color: white; border: none; border-radius: 6px; font-size: 14px; cursor: pointer; box-shadow: 0 2px 4px rgba(0,0,0,0.2);';
  clearButton.onclick = function() {
    console.log('Clearing notification bells...');
    updateNotificationBell('dean-applications', 0);
    updateNotificationBell('dean-major-violations', 0);
    updateNotificationBell('dean-minor-violations', 0);
  };
  document.body.appendChild(clearButton);
}
</script>