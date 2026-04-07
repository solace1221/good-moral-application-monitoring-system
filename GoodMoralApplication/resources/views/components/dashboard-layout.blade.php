@props(['roleTitle' => 'Dashboard'])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{ config('app.name', 'Good Moral Application Portal') }}</title>

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link href="https://db.onlinewebfonts.com/c/a0f33b8a3febb69aa498dba7d9e8cab2?family=Old+English+Text+MT+Std" rel="stylesheet">
  <!-- Heroicons CDN -->
  <script src="https://unpkg.com/heroicons@2.0.16/dist/heroicons.js"></script>

  <!-- Vite Assets -->
  @vite(['resources/css/app.css', 'resources/js/app.js'])

  <style>
    :root {
      --primary-yellow: rgba(255, 255, 0, 1);
      --primary-green: rgba(0, 176, 80, 1);
      --light-yellow: rgba(255, 255, 0, 0.1);
      --light-green: rgba(0, 176, 80, 0.1);
      --dark-green: #2c5530;
      
      /* Enhanced Button Color System */
      --btn-primary-bg: #00B050;
      --btn-primary-hover: #0e9549;
      --btn-primary-active: #0b7d3e;
      --btn-primary-text: #ffffff;
      
      --btn-secondary-bg: #6c757d;
      --btn-secondary-hover: #5a6268;
      --btn-secondary-active: #495057;
      --btn-secondary-text: #ffffff;
      
      --btn-success-bg: #28a745;
      --btn-success-hover: #218838;
      --btn-success-active: #1e7e34;
      --btn-success-text: #ffffff;
      
      --btn-warning-bg: #ffc107;
      --btn-warning-hover: #e0a800;
      --btn-warning-active: #d39e00;
      --btn-warning-text: #212529;
      
      --btn-danger-bg: #dc3545;
      --btn-danger-hover: #c82333;
      --btn-danger-active: #bd2130;
      --btn-danger-text: #ffffff;
      
      --btn-info-bg: #17a2b8;
      --btn-info-hover: #138496;
      --btn-info-active: #117a8b;
      --btn-info-text: #ffffff;
      
      --btn-outline-primary-border: #00B050;
      --btn-outline-primary-text: #00B050;
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

    /* Image Responsiveness Fixes */
    img {
      max-width: 100%;
      height: auto;
      object-fit: contain;
    }

    /* Sidebar Logo Fixes - Enhanced for Official SPUP Logo */
    .sidebar img {
      height: 48px !important;
      width: 48px !important;
      object-fit: contain !important;
      display: block;
      flex-shrink: 0;
    }

    /* SPUP Logo Container Responsiveness */
    .sidebar .logo-container {
      display: flex;
      align-items: center;
      gap: 16px;
      padding: 24px;
      border-bottom: 1px solid rgba(255, 255, 255, 0.2);
    }

    .sidebar .logo-wrapper {
      flex-shrink: 0;
      transition: all 0.3s ease;
    }

    .sidebar .logo-text {
      flex: 1;
      min-width: 0;
    }

    /* Hover effect for logo */
    .sidebar .logo-wrapper:hover img {
      transform: scale(1.05);
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    /* Department Logo Fixes */
    .stat-card img {
      height: 60px !important;
      width: auto !important;
      flex-shrink: 0;
      object-fit: contain;
    }

    .dashboard-container {
      display: flex;
      min-height: 100vh;
      position: relative;
      width: 100%;
      background: transparent;
      margin: 0;
      padding: 0;
    }

    /* Fixed Sidebar */
    .sidebar {
      width: 280px;
      background: linear-gradient(180deg, var(--primary-green) 0%, var(--dark-green) 100%);
      color: white;
      position: fixed;
      top: 0;
      left: 0;
      height: 100vh;
      overflow-y: auto;
      z-index: 1000;
      transition: transform 0.3s ease;
      flex-shrink: 0;
    }

    .sidebar.mobile-hidden {
      transform: translateX(-100%);
    }

    /* Main Content - positioned beside fixed sidebar */
    .main-content {
      flex: 1;
      margin-left: 280px; /* Account for fixed sidebar width */
      padding: 24px;
      min-height: 100vh;
      background: rgba(255, 255, 255, 0.95);
      position: relative;
      z-index: 1;
      overflow-x: hidden;
      overflow-y: auto;
      width: calc(100% - 280px);
    }

    /* Mobile Menu Button - Hidden by default on desktop */
    .mobile-menu-btn {
      display: none;
      position: fixed;
      top: 20px;
      left: 20px;
      z-index: 1001;
      background: var(--primary-green);
      color: white;
      border: none;
      padding: 14px;
      border-radius: 12px;
      cursor: pointer;
      box-shadow: 0 4px 20px rgba(0, 176, 80, 0.3);
      transition: all 0.3s ease;
      min-width: 48px;
      min-height: 48px;
      align-items: center;
      justify-content: center;
    }

    .mobile-menu-btn:hover {
      background: var(--dark-green);
      transform: translateY(-2px);
      box-shadow: 0 6px 25px rgba(0, 176, 80, 0.4);
    }

    .mobile-menu-btn:active {
      transform: translateY(0);
    }

    /* Mobile SPUP Logo Icon Updates */
    .mobile-logo-icon {
      width: 32px;
      height: 32px;
      object-fit: contain;
      filter: none;
      background: rgba(255, 255, 255, 0.95);
      border-radius: 6px;
      padding: 2px;
      transition: all 0.3s ease;
    }

    .mobile-menu-btn:hover .mobile-logo-icon {
      transform: scale(1.1);
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    /* Additional Mobile Responsiveness for SPUP Logo */
    @media (max-width: 480px) {
      .sidebar img {
        height: 40px !important;
        width: 40px !important;
      }
      
      .sidebar .logo-container {
        padding: 16px !important;
        gap: 10px !important;
      }
      
      .sidebar .logo-text div:first-child {
        font-size: 1rem !important;
        font-weight: 700 !important;
      }
      
      .sidebar .logo-text div:last-child {
        font-size: 0.75rem !important;
      }

      .mobile-logo-icon {
        width: 28px !important;
        height: 28px !important;
      }
    }

    /* Overlay for mobile */
    .sidebar-overlay {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.5);
      z-index: 999;
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
      background: rgba(0, 176, 80, 0.2);
      border-left-color: rgba(0, 176, 80, 0.8);
    }

    .nav-link.active {
      background: rgba(0, 176, 80, 0.25);
      border-left-color: rgba(0, 176, 80, 1);
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
      padding: 16px 24px;
      color: white;
      font-weight: 600;
      font-size: 0.95rem;
      background: rgba(255, 255, 255, 0.05);
      border-left: 4px solid rgba(0, 176, 80, 0.5);
    }

    .nav-submenu {
      background: rgba(0, 0, 0, 0.1);
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
      background: rgba(0, 176, 80, 0.15);
      border-left-color: rgba(0, 176, 80, 0.8);
      color: white;
    }

    .nav-sublink.active {
      background: rgba(0, 176, 80, 0.2);
      border-left-color: rgba(0, 176, 80, 1);
      color: white;
    }

    .nav-subicon {
      width: 18px;
      height: 18px;
      margin-right: 10px;
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
      background: rgba(0, 176, 80, 0.2);
      border-left-color: rgba(0, 176, 80, 0.8);
      color: white;
    }

    .btn-primary {
      background: linear-gradient(135deg, var(--primary-green) 0%, var(--dark-green) 100%);
      border: none;
      color: #ffffff !important;
      padding: 12px 24px;
      border-radius: 8px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s ease;
      box-shadow: 0 4px 15px rgba(0, 176, 80, 0.3);
      font-size: 14px;
      min-height: 44px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      text-decoration: none;
      border: 2px solid transparent;
    }

    .btn-primary:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 25px rgba(0, 176, 80, 0.4);
      background: linear-gradient(135deg, var(--dark-green) 0%, var(--primary-green) 100%);
      color: #ffffff !important;
    }

    .btn-primary:focus {
      outline: none;
      border: 2px solid #fff;
      box-shadow: 0 0 0 3px rgba(0, 176, 80, 0.5);
      color: #ffffff !important;
    }

    .btn-primary:active {
      transform: translateY(0);
      box-shadow: 0 2px 10px rgba(0, 176, 80, 0.3);
      color: #ffffff !important;
    }

    .btn-primary * {
      color: #ffffff !important;
    }

    .header-section {
      background: white;
      border-radius: 12px;
      padding: 24px;
      margin-bottom: 24px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
      border-top: 4px solid var(--primary-green);
    }

    /* Ensure first element in main content has no top margin */
    .main-content > *:first-child {
      margin-top: 0 !important;
      padding-top: 24px !important;
    }

    /* Remove any default margins that could cause spacing issues */
    .main-content > .header-section:first-child {
      margin-top: 0 !important;
    }

    /* Debug styles to ensure content visibility */
    .main-content * {
      color: inherit !important;
      opacity: 1 !important;
      visibility: visible !important;
    }

    .main-content h1, .main-content h2, .main-content h3 {
      color: var(--primary-green) !important;
      font-weight: 700 !important;
    }

    .main-content p, .main-content div {
      color: #2c3e50 !important;
    }

    .main-content .role-title {
      font-family: 'Old English Text MT Std', serif;
      font-size: 2.5rem;
      color: var(--primary-green) !important;
      margin-bottom: 8px;
      visibility: visible !important;
      display: block !important;
    }

    .accent-line {
      width: 60px;
      height: 3px;
      background: linear-gradient(90deg, var(--primary-yellow) 0%, var(--primary-green) 100%);
      margin: 16px 0;
      border-radius: 2px;
    }

    .stats-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
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

    .welcome-text {
      color: #2c3e50;
      font-size: 1.1rem;
      font-weight: 500;
      margin-bottom: 4px;
    }

    .stat-label {
      color: #495057;
      font-size: 1rem;
      font-weight: 600;
    }

    /* Better text contrast for tables and content */
    .responsive-table th {
      color: white !important;
      font-weight: 700 !important;
    }

    .responsive-table td {
      color: #2c3e50;
      font-weight: 500;
    }

    /* Mobile Header Styles */
    .mobile-header-controls {
      display: none;
    }

    .desktop-header-controls {
      display: flex;
    }

    .mobile-search-toggle {
      background: var(--primary-green);
      color: white;
      border: 2px solid transparent;
      padding: 10px;
      border-radius: 8px;
      cursor: pointer;
      min-width: 44px;
      min-height: 44px;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: all 0.3s ease;
      box-shadow: 0 2px 8px rgba(0, 176, 80, 0.3);
    }

    .mobile-search-toggle:hover {
      background: var(--dark-green);
      transform: translateY(-1px);
      box-shadow: 0 4px 12px rgba(0, 176, 80, 0.4);
    }

    .mobile-search-toggle:focus {
      outline: none;
      border: 2px solid #fff;
      box-shadow: 0 0 0 3px rgba(0, 176, 80, 0.5);
    }

    .mobile-search-panel {
      display: none;
      margin-top: 16px;
      padding: 16px;
      background: #f8f9fa;
      border-radius: 8px;
      border: 1px solid #e1e5e9;
    }

    .mobile-search-panel.active {
      display: block;
    }

    /* Responsive Tables */
    .responsive-table-container {
      overflow-x: auto;
      margin: 16px 0;
      border-radius: 8px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .responsive-table {
      min-width: 600px;
      width: 100%;
    }

    /* Responsive Design */
    /* Desktop-only: Ensure sidebar is fixed and main content is positioned correctly */
    @media (min-width: 769px) {
      .mobile-menu-btn {
        display: none !important;
      }
      
      .sidebar {
        position: fixed;
        transform: translateX(0);
      }
      
      .main-content {
        margin-left: 280px;
        width: calc(100% - 280px);
        padding: 24px;
      }
      
      .main-content > *:first-child {
        margin-top: 0 !important;
      }
    }

    @media (max-width: 1024px) {
      .stats-grid {
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 16px;
      }

      .stat-card {
        padding: 20px;
      }

      .stat-number {
        font-size: 2rem;
      }

      /* Better tablet layout for header */
      .header-section {
        padding: 20px;
      }

      .role-title {
        font-size: 2.2rem;
      }
    }

    @media (max-width: 768px) {
      .mobile-menu-btn {
        display: flex !important;
      }

      .sidebar {
        position: fixed;
        transform: translateX(-100%);
      }

      .sidebar.mobile-visible {
        transform: translateX(0);
      }

      .sidebar-overlay.active {
        display: block;
      }

      .main-content {
        margin-left: 0;
        width: 100%;
        padding: 80px 16px 16px 16px;
      }

      .stats-grid {
        grid-template-columns: 1fr;
        gap: 16px;
      }

      .stat-card {
        padding: 16px;
      }

      .stat-card img {
        height: 50px !important;
        width: auto !important;
      }

      .stat-number {
        font-size: 1.8rem;
      }

      .role-title {
        font-size: 2rem;
      }

      .header-section {
        padding: 16px;
        margin-bottom: 16px;
      }

      /* Mobile header layout */
      .desktop-header-controls {
        display: none;
      }

      .mobile-header-controls {
        display: flex;
        gap: 12px;
        align-items: center;
        margin-top: 16px;
      }

      /* Mobile form controls */
      .mobile-form-control {
        width: 100%;
        padding: 12px 16px;
        border: 2px solid #e1e5e9;
        border-radius: 8px;
        font-size: 16px; /* Prevents zoom on iOS */
        margin-bottom: 12px;
      }

      .mobile-btn {
        width: 100%;
        padding: 14px 20px;
        font-size: 16px;
        min-height: 48px;
        border-radius: 8px;
        margin-bottom: 8px;
      }

      /* Chart responsiveness */
      .chart-container {
        position: relative;
        height: 300px !important;
        margin: 16px 0;
      }

      /* Responsive tables */
      .responsive-table {
        min-width: 500px;
      }

      .responsive-table th,
      .responsive-table td {
        padding: 8px 12px;
        font-size: 14px;
      }
    }

    @media (max-width: 480px) {
      .main-content {
        padding: 80px 12px 12px 12px;
      }

      .stat-card {
        padding: 12px;
      }

      .stat-card img {
        height: 40px !important;
        width: auto !important;
      }

      .stat-number {
        font-size: 1.5rem;
      }

      .role-title {
        font-size: 1.5rem;
      }

      .header-section {
        padding: 12px;
      }

      .stats-grid {
        gap: 12px;
      }

      /* Extra small mobile adjustments */
      .mobile-menu-btn {
        top: 16px;
        left: 16px;
        padding: 12px;
        min-width: 44px;
        min-height: 44px;
      }

      .mobile-form-control {
        padding: 10px 14px;
        font-size: 16px;
      }

      .mobile-btn {
        padding: 12px 16px;
        font-size: 15px;
        min-height: 44px;
      }

      .chart-container {
        height: 250px !important;
      }

      .responsive-table {
        min-width: 400px;
      }

      .responsive-table th,
      .responsive-table td {
        padding: 6px 8px;
        font-size: 12px;
      }

      /* Smaller text for very small screens */
      .stat-label {
        font-size: 0.9rem;
      }

      .nav-link {
        padding: 14px 20px;
        font-size: 0.9rem;
      }

      .nav-sublink {
        padding: 10px 20px 10px 40px;
        font-size: 0.85rem;
      }
    }

    @media (max-width: 768px) {
      .mobile-menu-btn {
        display: flex !important;
      }

      .sidebar {
        transform: translateX(-100%);
      }

      .sidebar.mobile-visible {
        transform: translateX(0);
      }

      .sidebar-overlay.active {
        display: block;
      }

      .main-content {
        margin-left: 0;
        padding: 80px 16px 16px 16px;
      }

      .stats-grid {
        grid-template-columns: 1fr;
        gap: 16px;
      }

      .stat-card {
        padding: 16px;
      }

      .stat-card img {
        height: 50px !important;
        width: auto !important;
      }

      .stat-number {
        font-size: 1.8rem;
      }

      .role-title {
        font-size: 2rem;
      }

      .header-section {
        padding: 16px;
        margin-bottom: 16px;
      }

      /* Mobile header layout */
      .desktop-header-controls {
        display: none;
      }

      .mobile-header-controls {
        display: flex;
        gap: 12px;
        align-items: center;
        margin-top: 16px;
      }

      /* Mobile form controls */
      .mobile-form-control {
        width: 100%;
        padding: 12px 16px;
        border: 2px solid #e1e5e9;
        border-radius: 8px;
        font-size: 16px; /* Prevents zoom on iOS */
        margin-bottom: 12px;
      }

      .mobile-btn {
        width: 100%;
        padding: 14px 20px;
        font-size: 16px;
        min-height: 48px;
        border-radius: 8px;
        margin-bottom: 8px;
      }

      /* Chart responsiveness */
      .chart-container {
        position: relative;
        height: 300px !important;
        margin: 16px 0;
      }

      /* Responsive tables */
      .responsive-table {
        min-width: 500px;
      }

      .responsive-table th,
      .responsive-table td {
        padding: 8px 12px;
        font-size: 14px;
      }
    }

    @media (max-width: 480px) {
      .main-content {
        padding: 80px 12px 12px 12px;
      }

      .stat-card {
        padding: 12px;
      }

      .stat-card img {
        height: 40px !important;
        width: auto !important;
      }

      .stat-number {
        font-size: 1.5rem;
      }

      .role-title {
        font-size: 1.5rem;
      }

      .header-section {
        padding: 12px;
      }

      .stats-grid {
        gap: 12px;
      }

      /* Extra small mobile adjustments */
      .mobile-menu-btn {
        top: 16px;
        left: 16px;
        padding: 12px;
        min-width: 44px;
        min-height: 44px;
      }

      .mobile-form-control {
        padding: 10px 14px;
        font-size: 16px;
      }

      .mobile-btn {
        padding: 12px 16px;
        font-size: 15px;
        min-height: 44px;
      }

      .chart-container {
        height: 250px !important;
      }

      .responsive-table {
        min-width: 400px;
      }

      .responsive-table th,
      .responsive-table td {
        padding: 6px 8px;
        font-size: 12px;
      }

      /* Smaller text for very small screens */
      .stat-label {
        font-size: 0.9rem;
      }

      .nav-link {
        padding: 14px 20px;
        font-size: 0.9rem;
      }

      .nav-sublink {
        padding: 10px 20px 10px 40px;
        font-size: 0.85rem;
      }
    }

    /* Enhanced Button System */
    .btn {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      padding: 12px 20px;
      font-size: 14px;
      font-weight: 600;
      line-height: 1.5;
      text-align: center;
      text-decoration: none;
      border: 2px solid transparent;
      border-radius: 8px;
      cursor: pointer;
      transition: all 0.3s ease;
      min-height: 44px;
      user-select: none;
      white-space: nowrap;
    }

    .btn:focus {
      outline: none;
      box-shadow: 0 0 0 3px rgba(0, 176, 80, 0.2);
    }

    .btn:disabled {
      opacity: 0.6;
      cursor: not-allowed;
      pointer-events: none;
    }

    /* Button Size Variations */
    .btn-sm {
      padding: 8px 16px;
      font-size: 12px;
      min-height: 36px;
    }

    .btn-lg {
      padding: 16px 32px;
      font-size: 16px;
      min-height: 52px;
    }

    /* Button Styles */
    .btn-primary {
      background: var(--btn-primary-bg);
      border-color: var(--btn-primary-bg);
      color: var(--btn-primary-text);
      box-shadow: 0 4px 12px rgba(0, 176, 80, 0.3);
    }

    .btn-primary:hover:not(:disabled) {
      background: var(--btn-primary-hover);
      border-color: var(--btn-primary-hover);
      transform: translateY(-2px);
      box-shadow: 0 6px 18px rgba(0, 176, 80, 0.4);
    }

    .btn-primary:active:not(:disabled) {
      background: var(--btn-primary-active);
      border-color: var(--btn-primary-active);
      transform: translateY(0);
      box-shadow: 0 2px 6px rgba(0, 176, 80, 0.3);
    }

    .btn-secondary {
      background: var(--btn-secondary-bg);
      border-color: var(--btn-secondary-bg);
      color: var(--btn-secondary-text);
      box-shadow: 0 2px 8px rgba(108, 117, 125, 0.3);
    }

    .btn-secondary:hover:not(:disabled) {
      background: var(--btn-secondary-hover);
      border-color: var(--btn-secondary-hover);
      transform: translateY(-1px);
      box-shadow: 0 4px 12px rgba(108, 117, 125, 0.4);
    }

    .btn-success {
      background: var(--btn-success-bg);
      border-color: var(--btn-success-bg);
      color: var(--btn-success-text);
      box-shadow: 0 2px 8px rgba(40, 167, 69, 0.3);
    }

    .btn-success:hover:not(:disabled) {
      background: var(--btn-success-hover);
      border-color: var(--btn-success-hover);
      transform: translateY(-1px);
      box-shadow: 0 4px 12px rgba(40, 167, 69, 0.4);
    }

    .btn-warning {
      background: var(--btn-warning-bg);
      border-color: var(--btn-warning-bg);
      color: var(--btn-warning-text);
      box-shadow: 0 2px 8px rgba(255, 193, 7, 0.3);
    }

    .btn-warning:hover:not(:disabled) {
      background: var(--btn-warning-hover);
      border-color: var(--btn-warning-hover);
      transform: translateY(-1px);
      box-shadow: 0 4px 12px rgba(255, 193, 7, 0.4);
    }

    .btn-danger {
      background: var(--btn-danger-bg);
      border-color: var(--btn-danger-bg);
      color: var(--btn-danger-text);
      box-shadow: 0 2px 8px rgba(220, 53, 69, 0.3);
    }

    .btn-danger:hover:not(:disabled) {
      background: var(--btn-danger-hover);
      border-color: var(--btn-danger-hover);
      transform: translateY(-1px);
      box-shadow: 0 4px 12px rgba(220, 53, 69, 0.4);
    }

    .btn-info {
      background: var(--btn-info-bg);
      border-color: var(--btn-info-bg);
      color: var(--btn-info-text);
      box-shadow: 0 2px 8px rgba(23, 162, 184, 0.3);
    }

    .btn-info:hover:not(:disabled) {
      background: var(--btn-info-hover);
      border-color: var(--btn-info-hover);
      transform: translateY(-1px);
      box-shadow: 0 4px 12px rgba(23, 162, 184, 0.4);
    }

    /* Outline Button Styles */
    .btn-outline-primary {
      background: transparent;
      border-color: var(--btn-outline-primary-border);
      color: var(--btn-outline-primary-text);
    }

    .btn-outline-primary:hover:not(:disabled) {
      background: var(--btn-primary-bg);
      border-color: var(--btn-primary-bg);
      color: var(--btn-primary-text);
    }

    .btn-outline-secondary {
      background: transparent;
      border-color: var(--btn-secondary-bg);
      color: var(--btn-secondary-bg);
    }

    .btn-outline-secondary:hover:not(:disabled) {
      background: var(--btn-secondary-bg);
      color: var(--btn-secondary-text);
    }

    /* Legacy button class support */
    .btn-primary, button[type="submit"]:not([class]) {
      background: var(--btn-primary-bg) !important;
      color: var(--btn-primary-text) !important;
      border: none !important;
      border-radius: 8px !important;
      font-weight: 600 !important;
      cursor: pointer !important;
      transition: all 0.3s ease !important;
      box-shadow: 0 4px 12px rgba(0, 176, 80, 0.3) !important;
    }

    .btn-primary:hover:not(:disabled), button[type="submit"]:not([class]):hover:not(:disabled) {
      background: var(--btn-primary-hover) !important;
      transform: translateY(-2px) !important;
      box-shadow: 0 6px 18px rgba(0, 176, 80, 0.4) !important;
    }

    /* Mobile responsive buttons */
    @media (max-width: 768px) {
      .btn {
        min-height: 48px;
        font-size: 16px;
        width: 100%;
        justify-content: center;
      }

      .btn-sm {
        min-height: 40px;
        font-size: 14px;
      }

      .btn-group {
        flex-direction: column;
        gap: 8px;
      }

      .btn-group .btn {
        width: 100%;
      }
    }

    /* Button Groups */
    .btn-group {
      display: flex;
      gap: 12px;
      flex-wrap: wrap;
      align-items: center;
    }

    .btn-group .btn {
      flex: 1;
      min-width: 120px;
    }
  </style>
</head>

<body>
  <!-- Mobile Menu Button -->
  <button class="mobile-menu-btn" id="mobileMenuBtn" onclick="toggleSidebar()">
    <img src="{{ asset('images/backgrounds/spup-logo.png') }}" alt="SPUP Menu" class="mobile-logo-icon">
  </button>

  <!-- Sidebar Overlay for Mobile -->
  <div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

  <div class="dashboard-container">
    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
      <div style="padding: 24px; border-bottom: 1px solid rgba(255, 255, 255, 0.2);">
        <div style="display: flex; align-items: center; justify-content: space-between;">
          <div style="display: flex; align-items: center; gap: 16px;">
            <div style="flex-shrink: 0;">
              <img 
                src="{{ asset('images/backgrounds/spup-logo.png') }}" 
                alt="SPUP Official Logo" 
                style="
                  height: 48px; 
                  width: 48px; 
                  object-fit: contain; 
                  background: rgba(255, 255, 255, 0.95); 
                  border-radius: 12px; 
                  padding: 6px;
                  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
                  transition: all 0.3s ease;
                "
                onload="this.style.opacity='1'"
                onerror="this.style.display='none'"
              >
            </div>
            <div style="flex: 1; min-width: 0;">
              <div style="font-weight: 700; font-size: 1.2rem; color: white; line-height: 1.2; letter-spacing: 0.5px;">SPUP</div>
              <div style="font-size: 0.85rem; opacity: 0.9; color: rgba(255, 255, 255, 0.9); font-weight: 500; margin-top: 2px;">{{ $roleTitle }}</div>
            </div>
          </div>
          <!-- Close button for mobile -->
          <button onclick="closeSidebar()" style="display: none; background: none; border: none; color: white; padding: 8px;" id="sidebarCloseBtn">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 20px; height: 20px;">
              <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
      </div>

      <nav style="flex: 1; padding: 16px 0;">
        {{ $navigation ?? '' }}
      </nav>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
      {{ $slot }}
    </main>
  </div>

  <script>
    function toggleSidebar() {
      const sidebar = document.getElementById('sidebar');
      const overlay = document.getElementById('sidebarOverlay');
      const closeBtn = document.getElementById('sidebarCloseBtn');

      sidebar.classList.toggle('mobile-hidden');
      sidebar.classList.toggle('mobile-visible');
      overlay.classList.toggle('active');

      // Show/hide close button on mobile
      if (window.innerWidth <= 768) {
        closeBtn.style.display = sidebar.classList.contains('mobile-visible') ? 'block' : 'none';
      }
    }

    function closeSidebar() {
      const sidebar = document.getElementById('sidebar');
      const overlay = document.getElementById('sidebarOverlay');
      const closeBtn = document.getElementById('sidebarCloseBtn');

      sidebar.classList.add('mobile-hidden');
      sidebar.classList.remove('mobile-visible');
      overlay.classList.remove('active');
      closeBtn.style.display = 'none';
    }

    // Initialize sidebar state based on screen size
    function initializeSidebar() {
      const sidebar = document.getElementById('sidebar');
      const overlay = document.getElementById('sidebarOverlay');
      const closeBtn = document.getElementById('sidebarCloseBtn');

      if (window.innerWidth <= 768) {
        sidebar.classList.add('mobile-hidden');
        sidebar.classList.remove('mobile-visible');
        overlay.classList.remove('active');
        closeBtn.style.display = 'none';
      } else {
        sidebar.classList.remove('mobile-hidden', 'mobile-visible');
        overlay.classList.remove('active');
        closeBtn.style.display = 'none';
      }
    }

    // Toggle mobile search panel
    function toggleMobileSearch() {
      const searchPanel = document.getElementById('mobileSearchPanel');
      searchPanel.classList.toggle('active');
    }

    // Toggle Violations Dropdown
    function toggleViolationsDropdown() {
      const dropdown = document.getElementById('violations-dropdown');
      const arrow = document.querySelector('.violations-dropdown .dropdown-arrow');
      
      if (dropdown && arrow) {
        if (dropdown.style.display === 'none' || dropdown.style.display === '') {
          dropdown.style.display = 'block';
          arrow.style.transform = 'rotate(180deg)';
        } else {
          dropdown.style.display = 'none';
          arrow.style.transform = 'rotate(0deg)';
        }
      }
    }

    // Handle window resize
    window.addEventListener('resize', function() {
      initializeSidebar();
    });

    // Initialize on page load
    window.addEventListener('load', function() {
      initializeSidebar();
    });

    // Close sidebar when clicking on navigation links on mobile
    document.addEventListener('DOMContentLoaded', function() {
      const navLinks = document.querySelectorAll('.nav-link');
      navLinks.forEach(link => {
        link.addEventListener('click', function() {
          if (window.innerWidth <= 768) {
            closeSidebar();
          }
        });
      });

      // Close violations dropdown when clicking outside
      document.addEventListener('click', function(event) {
        const dropdown = document.getElementById('violations-dropdown');
        const violationsButton = event.target.closest('.violations-dropdown button');
        
        if (!violationsButton && dropdown && !dropdown.contains(event.target)) {
          dropdown.style.display = 'none';
          const arrow = document.querySelector('.violations-dropdown .dropdown-arrow');
          if (arrow) {
            arrow.style.transform = 'rotate(0deg)';
          }
        }
      });
    });
  </script>
</body>

</html>
