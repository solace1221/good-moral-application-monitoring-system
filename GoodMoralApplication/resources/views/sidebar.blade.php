<!-- resources/views/admin/sidebar.blade.php -->
<aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-64'"
  class="w-64 bg-spupGreen text-dark min-h-screen fixed sm:relative left-0 transform transition-transform duration-300 sm:translate-x-0 z-10">
  <nav>
    <!-- Application Link -->
    <a href="{{ route('dashboard') }}"
      class="flex items-center h-16 px-4 py-2 relative transition-colors duration-200 {{ request()->routeIs('dashboard') ? 'bg-gray-800 text-spupGold' : 'text-white hover:bg-gray-800 hover:text-spupGold' }}">
      <div class="flex items-center flex-1 min-w-0">
        <div class="flex-shrink-0 w-10 flex items-center justify-center">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z" />
          </svg>
        </div>
        <span class="ml-3 text-sm font-medium truncate">Application</span>
      </div>
    </a>

    <!-- Application Notifications Link -->
    <a href="{{ route('notification') }}"
      class="flex items-center h-16 px-4 py-2 relative transition-colors duration-200 {{ request()->routeIs('notification') ? 'bg-gray-800 text-spupGold' : 'text-white hover:bg-gray-800 hover:text-spupGold' }}">
      <div class="flex items-center flex-1 min-w-0">
        <div class="flex-shrink-0 w-10 flex items-center justify-center">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
          </svg>
        </div>
        <span class="ml-3 text-sm font-medium truncate flex-1">Application Notifications</span>
      </div>
      <span class="notification-bell flex items-center gap-1 ml-2 bg-yellow-400 bg-opacity-20 px-2 py-1 rounded-full" id="student-notifications-bell" style="display: none;">
        <svg class="w-3 h-3 text-yellow-400" fill="currentColor" viewBox="0 0 24 24">
          <path d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/>
        </svg>
        <span class="notification-count text-yellow-400 font-semibold text-xs min-w-[16px] text-center" id="student-notifications-count">0</span>
      </span>
    </a>

    <!-- Violation Notifications Link -->
    <a href="{{ route('notificationViolation') }}"
      class="flex items-center h-16 px-4 py-2 relative transition-colors duration-200 {{ request()->routeIs('notificationViolation') ? 'bg-gray-800 text-spupGold' : 'text-white hover:bg-gray-800 hover:text-spupGold' }}">
      <div class="flex items-center flex-1 min-w-0">
        <div class="flex-shrink-0 w-10 flex items-center justify-center">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
          </svg>
        </div>
        <span class="ml-3 text-sm font-medium truncate flex-1">Violation Notifications</span>
      </div>
      <span class="notification-bell flex items-center gap-1 ml-2 bg-yellow-400 bg-opacity-20 px-2 py-1 rounded-full" id="student-violations-bell" style="display: none;">
        <svg class="w-3 h-3 text-yellow-400" fill="currentColor" viewBox="0 0 24 24">
          <path d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
        </svg>
        <span class="notification-count text-yellow-400 font-semibold text-xs min-w-[16px] text-center" id="student-violations-count">0</span>
      </span>
    </a>

    <!-- Profile Link -->
    <a href="{{ route('student.profile') }}"
      class="flex items-center h-16 px-4 py-2 relative transition-colors duration-200 {{ request()->routeIs('student.profile') ? 'bg-gray-800 text-spupGold' : 'text-white hover:bg-gray-800 hover:text-spupGold' }}">
      <div class="flex items-center flex-1 min-w-0">
        <div class="flex-shrink-0 w-10 flex items-center justify-center">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
          </svg>
        </div>
        <span class="ml-3 text-sm font-medium truncate">Profile</span>
      </div>
    </a>

    <!-- Logout Link -->
    <form method="POST" action="{{ route('logout') }}">
      @csrf
      <a href="{{ route('logout') }}"
        class="flex items-center h-16 px-4 py-2 relative transition-colors duration-200 text-white hover:bg-gray-800 hover:text-red-600"
        onclick="event.preventDefault(); this.closest('form').submit();">
        <div class="flex items-center flex-1 min-w-0">
          <div class="flex-shrink-0 w-10 flex items-center justify-center">
            <x-icon-logout class="w-6 h-6" />
          </div>
          <span class="ml-3 text-sm font-medium truncate">Logout</span>
        </div>
      </a>
    </form>
  </nav>
</aside>

<script>
// Function to update student notification counts
function updateStudentNotificationCounts() {
  fetch('/student/notification-counts')
    .then(response => {
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }
      return response.json();
    })
    .then(data => {
      console.log('Student notification data received:', data);

      // Update application notifications
      updateNotificationBell('student-notifications', data.applicationNotifications || 0);

      // Update violation notifications
      updateNotificationBell('student-violations', data.violationNotifications || 0);

      // Update page title with total count
      const totalCount = data.totalNotifications || 0;
      if (totalCount > 0) {
        document.title = `(${totalCount}) Student Dashboard - Good Moral Application System`;
      } else {
        document.title = 'Student Dashboard - Good Moral Application System';
      }
    })
    .catch(error => {
      console.error('Error fetching student notification counts:', error);
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
  console.log('Student sidebar loaded, initializing notifications...');

  updateStudentNotificationCounts();

  // Update notification counts every 30 seconds
  setInterval(updateStudentNotificationCounts, 30000);
});
</script>