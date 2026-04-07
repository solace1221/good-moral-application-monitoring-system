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

  <!-- Scripts -->
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<style>
  :root {
    --primary-yellow: rgba(255, 255, 0, 1);
    --primary-green: rgba(0, 176, 80, 1);
    --light-yellow: rgba(255, 255, 0, 0.1);
    --light-green: rgba(0, 176, 80, 0.1);
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

  .logo-container {
    background: white;
    border-radius: 50%;
    padding: 8px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
  }

  .old-english {
    font-family: 'Old English Text MT Std', serif;
  }

  .nav-link {
    position: relative;
    transition: all 0.3s ease;
  }

  .nav-link:hover {
    color: var(--primary-green);
  }

  .nav-link::after {
    content: '';
    position: absolute;
    width: 0;
    height: 2px;
    bottom: -4px;
    left: 50%;
    background: var(--primary-green);
    transition: all 0.3s ease;
    transform: translateX(-50%);
  }

  .nav-link:hover::after {
    width: 100%;
  }

  .form-container {
    background: white;
    border-radius: 16px;
    padding: 40px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
    border-top: 4px solid var(--primary-green);
    max-width: 500px;
    width: 100%;
  }

  .form-container-wide {
    background: white;
    border-radius: 16px;
    padding: 40px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
    border-top: 4px solid var(--primary-green);
    max-width: 800px;
    width: 100%;
  }

  .form-title {
    font-family: 'Playfair Display', serif;
    font-size: 2rem;
    font-weight: 600;
    color: var(--primary-green);
    text-align: center;
    margin-bottom: 8px;
  }

  .form-subtitle {
    text-align: center;
    color: #7f8c8d;
    margin-bottom: 32px;
    font-size: 14px;
  }

  .accent-line {
    width: 60px;
    height: 3px;
    background: linear-gradient(90deg, var(--primary-yellow) 0%, var(--primary-green) 100%);
    margin: 16px auto;
    border-radius: 2px;
  }

  .form-input {
    width: 100%;
    padding: 12px 16px;
    border: 2px solid #e1e8ed;
    border-radius: 8px;
    font-size: 14px;
    transition: all 0.3s ease;
    background: #fafbfc;
  }

  .form-input:focus {
    outline: none;
    border-color: var(--primary-green);
    background: white;
    box-shadow: 0 0 0 3px rgba(0, 176, 80, 0.1);
  }

  .form-label {
    display: block;
    font-weight: 500;
    color: #2c3e50;
    margin-bottom: 6px;
    font-size: 14px;
  }

  .form-button {
    background: linear-gradient(135deg, var(--primary-green) 0%, #2c5530 100%);
    border: none;
    color: white;
    padding: 14px 24px;
    border-radius: 8px;
    font-weight: 600;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(0, 176, 80, 0.3);
    width: 100%;
  }

  .form-button:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 25px rgba(0, 176, 80, 0.4);
  }

  .form-link {
    color: var(--primary-green);
    text-decoration: none;
    font-weight: 500;
    transition: all 0.3s ease;
  }

  .form-link:hover {
    color: #2c5530;
    text-decoration: underline;
  }
</style>

<body>
  <!-- Header Accent Line -->
  <div class="header-accent"></div>

  <!-- Header -->
  <header style="background: white; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);">
    <div style="max-width: 1200px; margin: 0 auto; padding: 20px 24px; display: flex; align-items: center; justify-content: space-between;">
      <div style="display: flex; align-items: center; gap: 16px;">
        <div class="logo-container">
          <img src="{{ asset('images/backgrounds/spup-logo.png') }}" alt="SPUP Logo" style="height: 48px; width: auto;">
        </div>
        <div>
          <h1 class="old-english" style="font-size: 22px; font-weight: 400; color: var(--primary-green); margin: 0; line-height: 1.2; letter-spacing: 1px;">St. Paul University Philippines</h1>
          <p style="font-size: 14px; color: #7f8c8d; margin: 0;">Good Moral Application and Monitoring System</p>
        </div>
      </div>

      <nav style="display: flex; gap: 24px; align-items: center;">
        <a href="{{ route('welcome') }}" class="nav-link" style="text-decoration: none; font-size: 16px; font-weight: 500; color: #2c3e50; padding: 8px 0;">
          Home
        </a>
        <a href="{{ route('login') }}" class="nav-link" style="text-decoration: none; font-size: 16px; font-weight: 500; color: #2c3e50; padding: 8px 0;">
          Sign In
        </a>
        <a href="{{ route('register') }}" class="nav-link" style="text-decoration: none; font-size: 16px; font-weight: 500; color: #2c3e50; padding: 8px 0;">
          Create Account
        </a>
      </nav>
    </div>
  </header>

  <!-- Main Content -->
  <main style="flex: 1; display: flex; align-items: center; justify-content: center; padding: 60px 24px;">
    {{ $slot }}
  </main>

  <!-- Footer -->
  <footer style="background: white; border-top: 1px solid #e1e8ed; padding: 24px;">
    <div style="max-width: 1200px; margin: 0 auto; text-align: center;">
      <div class="accent-line" style="margin-bottom: 16px;"></div>
      <p style="color: #7f8c8d; font-size: 14px; margin: 0;">
        © {{ date('Y') }} Good Moral Applicant and Monitoring System. All rights reserved.
      </p>
      <p style="color: #95a5a6; font-size: 12px; margin: 8px 0 0 0; font-style: italic;">
        Tap. Act. Stay on Track.
      </p>
    </div>
  </footer>

  <!-- Footer Accent Line -->
  <div class="header-accent"></div>
</body>

</html>