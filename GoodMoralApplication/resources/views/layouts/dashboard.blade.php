<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{ config('app.name', 'Good Moral Application Portal') }}</title>

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link href="https://db.onlinewebfonts.com/c/a0f33b8a3febb69aa498dba7d9e8cab2?family=Old+English+Text+MT+Std" rel="stylesheet">
  <!-- Heroicons CDN -->
  <script src="https://unpkg.com/heroicons@2.0.16/dist/heroicons.js"></script>

  <!-- Chart.js CDN -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <!-- Scripts -->
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  
  <!-- Dashboard Responsive CSS -->
  <link href="{{ asset('css/dashboard-responsive.css') }}" rel="stylesheet">

  <style>
    :root {
      --primary-yellow: rgba(255, 255, 0, 1);
      --primary-green: rgba(0, 176, 80, 1);
      --light-yellow: rgba(255, 255, 0, 0.1);
      --light-green: rgba(0, 176, 80, 0.1);
      --dark-green: #2c5530;
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Inter', sans-serif;
      line-height: 1.6;
      color: #2c3e50;
      background: linear-gradient(135deg, var(--light-yellow) 0%, var(--light-green) 100%);
      min-height: 100vh;
    }

    .header-accent {
      height: 4px;
      background: linear-gradient(90deg, var(--primary-yellow) 0%, var(--primary-green) 100%);
    }

    .old-english {
      font-family: 'Old English Text MT Std', serif;
    }

    .sidebar-green {
      background: linear-gradient(180deg, var(--primary-green) 0%, var(--dark-green) 100%);
    }

    .nav-item {
      transition: all 0.3s ease;
      border-left: 4px solid transparent;
    }

    .nav-item:hover {
      background: rgba(255, 255, 255, 0.1);
      border-left-color: var(--primary-yellow);
    }

    .nav-item.active {
      background: rgba(255, 255, 255, 0.15);
      border-left-color: var(--primary-yellow);
    }

    .card {
      background: white;
      border-radius: 12px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
      border-top: 4px solid var(--primary-green);
      transition: all 0.3s ease;
    }

    .card:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
    }

    .btn-primary {
      background: linear-gradient(135deg, var(--primary-green) 0%, var(--dark-green) 100%);
      border: none;
      color: white;
      padding: 12px 24px;
      border-radius: 8px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s ease;
      box-shadow: 0 4px 15px rgba(0, 176, 80, 0.3);
    }

    .btn-primary:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 25px rgba(0, 176, 80, 0.4);
    }

    .accent-line {
      width: 60px;
      height: 3px;
      background: linear-gradient(90deg, var(--primary-yellow) 0%, var(--primary-green) 100%);
      margin: 16px 0;
      border-radius: 2px;
    }

    .dashboard-container {
      display: flex;
      min-height: 100vh;
    }

    .sidebar {
      width: 280px;
      background: linear-gradient(180deg, var(--primary-green) 0%, var(--dark-green) 100%);
      color: white;
      position: fixed;
      height: 100vh;
      overflow-y: auto;
      z-index: 1000;
      transition: transform 0.3s ease;
    }

    .sidebar.mobile-hidden {
      transform: translateX(-100%);
    }

    .main-content {
      flex: 1;
      margin-left: 280px;
      padding: 24px;
      transition: margin-left 0.3s ease;
    }

    .main-content.sidebar-hidden {
      margin-left: 0;
    }

    .mobile-toggle {
      display: none;
      position: fixed;
      top: 20px;
      left: 20px;
      z-index: 1100;
      background: var(--primary-green);
      color: white;
      border: none;
      padding: 12px;
      border-radius: 8px;
      cursor: pointer;
    }

    @media (max-width: 768px) {
      .sidebar {
        transform: translateX(-100%);
      }

      .sidebar.mobile-visible {
        transform: translateX(0);
      }

      .main-content {
        margin-left: 0;
        padding: 80px 16px 16px;
      }

      .mobile-toggle {
        display: block;
      }
    }

    .header-section {
      background: white;
      border-radius: 12px;
      padding: 24px;
      margin-bottom: 24px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
      border-top: 4px solid var(--primary-green);
    }

    .role-title {
      font-family: 'Old English Text MT Std', serif;
      font-size: 2.5rem;
      color: var(--primary-green);
      margin-bottom: 8px;
    }

    .welcome-text {
      color: #666;
      font-size: 1.1rem;
    }

    .stats-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 20px;
      margin-bottom: 24px;
    }

    .stat-card {
      background: white;
      border-radius: 12px;
      padding: 24px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
      border-top: 4px solid var(--primary-green);
      transition: all 0.3s ease;
    }

    .stat-card:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
    }

    .stat-number {
      font-size: 2.5rem;
      font-weight: 700;
      color: var(--primary-green);
      margin-bottom: 8px;
    }

    .stat-label {
      color: #666;
      font-weight: 500;
    }

    .content-section {
      background: white;
      border-radius: 12px;
      padding: 24px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
      border-top: 4px solid var(--primary-green);
    }

    .section-title {
      font-size: 1.5rem;
      font-weight: 600;
      color: #2c3e50;
      margin-bottom: 16px;
    }

    .nav-link {
      display: flex;
      align-items: center;
      padding: 16px 24px;
      color: white;
      text-decoration: none;
      transition: all 0.3s ease;
      border-left: 4px solid transparent;
    }

    .nav-link:hover {
      background: rgba(255, 255, 255, 0.1);
      border-left-color: var(--primary-yellow);
    }

    .nav-link.active {
      background: rgba(255, 255, 255, 0.15);
      border-left-color: var(--primary-yellow);
    }

    .nav-icon {
      width: 24px;
      height: 24px;
      margin-right: 12px;
    }

    /* Navigation Section Styles */
    .nav-section {
      margin-bottom: 8px;
    }

    .nav-section-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 16px 24px;
      color: white;
      font-weight: 600;
      font-size: 0.95rem;
      background: rgba(255, 255, 255, 0.05);
      border-left: 4px solid rgba(255, 255, 0, 0.3);
      cursor: pointer;
      position: relative;
    }

    .nav-submenu {
      background: rgba(0, 0, 0, 0.1);
      max-height: 500px;
      overflow: hidden;
      transition: max-height 0.3s ease, opacity 0.3s ease;
      opacity: 1;
    }

    .nav-sublink {
      display: flex;
      align-items: center;
      padding: 12px 24px 12px 48px;
      color: rgba(255, 255, 255, 0.9);
      text-decoration: none;
      transition: all 0.3s ease;
      border-left: 4px solid transparent;
      font-size: 0.9rem;
    }

    .nav-sublink:hover {
      background: rgba(255, 255, 255, 0.08);
      border-left-color: var(--primary-yellow);
      color: white;
    }

    .nav-sublink.active {
      background: rgba(255, 255, 255, 0.12);
      border-left-color: var(--primary-yellow);
      color: white;
    }

    .nav-subicon {
      width: 18px;
      height: 18px;
      margin-right: 10px;
    }

    /* Dropdown Styles */
    .nav-section-title {
      flex: 1;
    }

    .nav-chevron {
      width: 20px;
      height: 20px;
      transition: transform 0.3s ease;
      margin-left: auto;
    }

    .nav-section.collapsed .nav-chevron {
      transform: rotate(-90deg);
    }

    .nav-section.collapsed .nav-submenu {
      max-height: 0;
      opacity: 0;
    }

    .nav-section-header:hover {
      background: rgba(255, 255, 255, 0.1);
    }

    /* Logout Form Styles */
    .nav-logout-form {
      margin-top: 16px;
    }

    .nav-logout {
      background: none;
      border: none;
      width: 100%;
      text-align: left;
      cursor: pointer;
      color: rgba(255, 255, 255, 0.9);
    }

    .nav-logout:hover {
      background: rgba(255, 255, 255, 0.1);
      border-left-color: var(--primary-yellow);
      color: white;
    }

    .logout-section {
      margin-top: auto;
      padding: 24px;
      border-top: 1px solid rgba(255, 255, 255, 0.2);
    }

    .logout-btn {
      display: flex;
      align-items: center;
      width: 100%;
      padding: 12px 16px;
      background: rgba(255, 255, 255, 0.1);
      color: white;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      transition: all 0.3s ease;
    }

    .logout-btn:hover {
      background: rgba(255, 255, 255, 0.2);
    }
  </style>
</head>

<body>
  <!-- Header Accent Line -->
  <div class="header-accent"></div>

  <!-- Mobile Toggle Button -->
  <button class="mobile-toggle" onclick="toggleSidebar()">
    <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
    </svg>
  </button>

  <div class="dashboard-container">
    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
      <div style="padding: 24px; border-bottom: 1px solid rgba(255, 255, 255, 0.2);">
        <div style="display: flex; align-items: center; gap: 12px;">
          <img src="{{ asset('images/backgrounds/spup-logo.png') }}" alt="SPUP Logo" style="height: 40px; width: auto;">
          <div>
            <div style="font-weight: 600; font-size: 1.1rem;">SPUP</div>
            <div style="font-size: 0.9rem; opacity: 0.8;">{{ $roleTitle ?? 'Dashboard' }}</div>
            @auth
              <div style="font-size: 0.8rem; opacity: 0.7; margin-top: 2px;">{{ Auth::user()->name }}</div>
            @endauth
          </div>
        </div>
      </div>

      <nav style="flex: 1; padding: 16px 0;">
        {{ $navigation ?? '' }}
      </nav>

      <div class="logout-section">
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button type="submit" class="logout-btn">
            <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
            </svg>
            Logout
          </button>
        </form>
      </div>
    </aside>

    <!-- Main Content -->
    <main class="main-content" id="mainContent">
      {{ $slot }}
    </main>
  </div>

  <!-- Footer Accent Line -->
  <div class="header-accent"></div>

  <script>
    function toggleSidebar() {
      const sidebar = document.getElementById('sidebar');
      const mainContent = document.getElementById('mainContent');
      
      sidebar.classList.toggle('mobile-visible');
      
      // Close sidebar when clicking outside on mobile
      if (sidebar.classList.contains('mobile-visible')) {
        document.addEventListener('click', function closeSidebar(e) {
          if (!sidebar.contains(e.target) && !e.target.classList.contains('mobile-toggle')) {
            sidebar.classList.remove('mobile-visible');
            document.removeEventListener('click', closeSidebar);
          }
        });
      }
    }
  </script>
</body>

</html>
