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

  <!-- Scripts -->
  @vite(['resources/css/app.css', 'resources/js/app.js'])

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
  </style>
</head>

<body>
  <!-- Header Accent Line -->
  <div class="header-accent"></div>

  <div class="min-h-screen">
    @include('layouts.navigation')

    <!-- Page Heading -->
    @isset($header)
    <header style="background: white; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);">
      <div style="max-width: 1200px; margin: 0 auto; padding: 20px 24px; display: flex; align-items: center; gap: 16px;">
        <div style="background: white; border-radius: 50%; padding: 8px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);">
          <img src="{{ asset('images/backgrounds/spup-logo.png') }}" alt="SPUP Logo" style="height: 48px; width: auto;">
        </div>
        <div style="flex: 1;">
          {{ $header }}
        </div>
      </div>
    </header>
    @endisset

    <!-- Page Content -->
    <main style="padding: 0;">
      {{ $slot }}
    </main>

    <!-- Footer Accent Line -->
    <div class="header-accent"></div>
  </div>
</body>

</html>