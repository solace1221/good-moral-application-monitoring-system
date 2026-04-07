<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>Good Moral Application Portal - St. Paul University Philippines</title>

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link href="https://db.onlinewebfonts.com/c/a0f33b8a3febb69aa498dba7d9e8cab2?family=Old+English+Text+MT+Std" rel="stylesheet">

  <!-- Styles / Scripts -->
  @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  @else
  <link rel="stylesheet" href="{{ asset('css/app.css') }}">
  @endif
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

  .hero-title {
    font-family: 'Playfair Display', serif;
    background: linear-gradient(135deg, var(--primary-green) 0%, #2c5530 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
  }

  .cta-button {
    background: linear-gradient(135deg, var(--primary-green) 0%, #2c5530 100%);
    border: none;
    color: white;
    padding: 16px 32px;
    border-radius: 8px;
    font-weight: 600;
    font-size: 16px;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(0, 176, 80, 0.3);
    position: relative;
    overflow: hidden;
  }

  .cta-button::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
    transition: left 0.5s;
  }

  .cta-button:hover::before {
    left: 100%;
  }

  .cta-button:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 25px rgba(0, 176, 80, 0.4);
  }

  .feature-card {
    background: white;
    border-radius: 12px;
    padding: 24px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
    border-top: 4px solid var(--primary-green);
  }

  .feature-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
  }

  .accent-line {
    width: 60px;
    height: 3px;
    background: linear-gradient(90deg, var(--primary-yellow) 0%, var(--primary-green) 100%);
    margin: 16px auto;
    border-radius: 2px;
  }

  .old-english {
    font-family: 'Old English Text MT Std', serif;
  }

  /* Image Responsiveness Fixes */
  img {
    max-width: 100%;
    height: auto;
    object-fit: contain;
  }

  .logo-container img {
    width: auto;
    height: 48px;
    object-fit: contain;
    display: block;
  }

  /* Mobile Responsiveness */
  @media (max-width: 768px) {
    .header-container {
      flex-direction: column !important;
      gap: 16px !important;
      text-align: center;
    }

    .logo-section {
      flex-direction: column !important;
      align-items: center !important;
      gap: 12px !important;
    }

    .logo-container {
      flex-shrink: 0;
    }

    .logo-container img {
      height: 40px !important;
    }

    .university-info {
      text-align: center !important;
    }

    .university-info h1 {
      font-size: 18px !important;
      line-height: 1.3 !important;
    }

    .university-info p {
      font-size: 12px !important;
    }

    .nav-section {
      flex-direction: row !important;
      justify-content: center !important;
      gap: 20px !important;
    }

    .hero-title {
      font-size: 2.5rem !important;
      line-height: 1.2 !important;
    }

    .feature-grid {
      grid-template-columns: 1fr !important;
      gap: 24px !important;
    }

    .cta-buttons {
      flex-direction: column !important;
      align-items: center !important;
    }

    .cta-button,
    .cta-secondary {
      width: 100% !important;
      max-width: 280px !important;
      text-align: center !important;
    }

    .footer-content {
      flex-direction: column !important;
      gap: 12px !important;
    }

    .footer-logo-section {
      flex-direction: column !important;
      align-items: center !important;
      gap: 8px !important;
    }

    .footer-logo-container img {
      height: 28px !important;
    }
  }

  @media (max-width: 480px) {
    .hero-title {
      font-size: 2rem !important;
    }

    .university-info h1 {
      font-size: 16px !important;
    }

    .logo-container img {
      height: 36px !important;
    }

    .footer-logo-container img {
      height: 24px !important;
    }

    .feature-card {
      padding: 20px !important;
    }

    .nav-section {
      gap: 16px !important;
    }

    .nav-link {
      font-size: 14px !important;
    }
  }
</style>

<body>
  <!-- Header Accent Line -->
  <div class="header-accent"></div>

  <!-- Header -->
  <header style="background: white; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);">
    <div class="header-container" style="max-width: 1200px; margin: 0 auto; padding: 20px 24px; display: flex; align-items: center; justify-content: space-between;">
      <div class="logo-section" style="display: flex; align-items: center; gap: 16px;">
        <div class="logo-container">
          <img src="{{ asset('images/backgrounds/spup-logo.png') }}" alt="SPUP Logo" style="height: 48px; width: auto;">
        </div>
        <div class="university-info">
          <h1 class="old-english" style="font-size: 22px; font-weight: 400; color: var(--primary-green); margin: 0; line-height: 1.2; letter-spacing: 1px;">St. Paul University Philippines</h1>
          <p style="font-size: 14px; color: #7f8c8d; margin: 0;">Good Moral Application and Monitoring System</p>
        </div>
      </div>

      <nav class="nav-section" style="display: flex; gap: 24px; align-items: center;">
        @if (Route::has('login'))
        <a href="{{ route('login') }}" class="nav-link" style="text-decoration: none; font-size: 16px; font-weight: 500; color: #2c3e50; padding: 8px 0;">
          Sign In
        </a>
        @if (Route::has('register'))
        <a href="{{ route('register') }}" class="nav-link" style="text-decoration: none; font-size: 16px; font-weight: 500; color: #2c3e50; padding: 8px 0;">
          Create Account
        </a>
        @endif
        @endif
      </nav>
    </div>
  </header>

  <!-- Main Content -->
  <main style="flex: 1; display: flex; align-items: center; justify-content: center; padding: 60px 24px;">
    <div style="max-width: 1200px; width: 100%; text-align: center;">

      <!-- Hero Section -->
      <section style="margin-bottom: 80px;">
        <h1 class="hero-title" style="font-size: 3.5rem; font-weight: 700; margin-bottom: 24px; line-height: 1.1;">
          Good Moral Application and Monitoring System
        </h1>
        <div class="accent-line"></div>
        <p style="font-size: 1.25rem; color: #5a6c7d; margin-bottom: 32px; max-width: 600px; margin-left: auto; margin-right: auto; line-height: 1.6;">
          A streamlined digital platform for requesting and processing good moral character certifications with integrity and efficiency.
        </p>
        <p style="font-size: 1.1rem; font-style: italic; color: var(--primary-green); font-weight: 500; margin-bottom: 40px;">
          Tap. Act. Stay on Track.
        </p>

        @if (Route::has('login'))
        <div class="cta-buttons" style="display: flex; gap: 16px; justify-content: center; flex-wrap: wrap;">
          <a href="{{ route('login') }}" class="cta-button" style="text-decoration: none; display: inline-block;">
            Apply Now
          </a>
          <a href="{{ route('register') }}" class="cta-secondary" style="text-decoration: none; display: inline-block; padding: 16px 32px; border: 2px solid var(--primary-green); color: var(--primary-green); border-radius: 8px; font-weight: 600; transition: all 0.3s ease; background: white;">
            Create Account
          </a>
        </div>
        @endif
      </section>

      <!-- Features Section -->
      <section class="feature-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 32px; margin-bottom: 60px;">
        <div class="feature-card">
          <div style="width: 60px; height: 60px; background: var(--light-green); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">
            <svg style="width: 32px; height: 32px; color: var(--primary-green);" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
            </svg>
          </div>
          <h3 style="font-size: 1.25rem; font-weight: 600; color: #2c3e50; margin-bottom: 12px;">Fast Processing</h3>
          <p style="color: #7f8c8d; line-height: 1.6;">Quick and efficient processing of your good moral character certification requests.</p>
        </div>

        <div class="feature-card">
          <div style="width: 60px; height: 60px; background: var(--light-green); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">
            <svg style="width: 32px; height: 32px; color: var(--primary-green);" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
            </svg>
          </div>
          <h3 style="font-size: 1.25rem; font-weight: 600; color: #2c3e50; margin-bottom: 12px;">Secure Platform</h3>
          <p style="color: #7f8c8d; line-height: 1.6;">Your personal information and documents are protected with enterprise-grade security.</p>
        </div>

        <div class="feature-card">
          <div style="width: 60px; height: 60px; background: var(--light-green); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">
            <svg style="width: 32px; height: 32px; color: var(--primary-green);" fill="currentColor" viewBox="0 0 20 20">
              <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
          </div>
          <h3 style="font-size: 1.25rem; font-weight: 600; color: #2c3e50; margin-bottom: 12px;">Real-time Tracking</h3>
          <p style="color: #7f8c8d; line-height: 1.6;">Track your application status in real-time from submission to completion.</p>
        </div>


      </section>

      <!-- Student Registration Notice -->
      <section style="background: linear-gradient(135deg, #e8f5e8 0%, #f0f8f0 100%); border: 2px solid var(--primary-green); border-radius: 16px; padding: 32px; margin-bottom: 60px; text-align: center;">
        <div style="display: flex; align-items: center; justify-content: center; gap: 16px; margin-bottom: 20px;">
          <div style="width: 48px; height: 48px; background: var(--primary-green); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
            <svg style="width: 24px; height: 24px; color: white;" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
            </svg>
          </div>
          <h3 style="color: var(--primary-green); font-size: 1.5rem; font-weight: 600; margin: 0;">📚 New: Student Registration Available!</h3>
        </div>
        <p style="color: #333; font-size: 1.1rem; line-height: 1.6; margin: 0 0 24px 0; max-width: 800px; margin-left: auto; margin-right: auto;">
          Students who don't have an account in our system can now register directly! Create your account to apply for Good Moral Certificates and track your applications.
        </p>
        <div style="display: flex; gap: 16px; justify-content: center; flex-wrap: wrap;">
          <a href="{{ route('register') }}" style="display: inline-block; background: var(--primary-green); color: white; padding: 14px 28px; border-radius: 8px; text-decoration: none; font-weight: 600; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(0, 176, 80, 0.3);">
            Register as Student
          </a>
          <a href="{{ route('login') }}" style="display: inline-block; background: white; color: var(--primary-green); padding: 14px 28px; border: 2px solid var(--primary-green); border-radius: 8px; text-decoration: none; font-weight: 600; transition: all 0.3s ease;">
            Already Have Account? Sign In
          </a>
        </div>
        <p style="color: #666; font-size: 14px; margin: 16px 0 0 0;">
          <strong>Account Types:</strong> Students • Alumni • PSG Officers
        </p>
      </section>


    </div>
  </main>

  <!-- Application Process Guide Section -->
  <section style="padding: 40px 24px 60px 24px;">
    <div style="max-width: 1200px; margin: 0 auto;">
      <div style="text-align: center; margin-bottom: 48px;">
        <h2 style="font-size: 2.5rem; font-weight: 700; color: #2c3e50; margin-bottom: 16px;">Application Process</h2>
        <div class="accent-line" style="margin: 0 auto 16px;"></div>
        <p style="font-size: 1.1rem; color: #2c3e50; max-width: 600px; margin: 0 auto;">
          Follow these simple steps to apply for your Good Moral Certificate or Certificate of Residency
        </p>
      </div>

      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 24px; margin-bottom: 40px;">
        <div style="background: white; padding: 24px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); border-left: 4px solid var(--primary-green);">
          <h4 style="color: var(--primary-green); font-weight: 600; margin-bottom: 16px; font-size: 1.1rem;">📋 Application Flow</h4>
          <ol style="color: #2c3e50; line-height: 1.8; margin: 0; padding-left: 20px; font-size: 15px;">
            <li>Submit application</li>
            <li>Registrar review & approval</li>
            <li>Dean review & approval</li>
            <li>Administrator final approval</li>
            <li><strong>Upload Receipt</strong></li>
            <li>Certificate printing & pickup</li>
          </ol>
        </div>

        <div style="background: white; padding: 24px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); border-left: 4px solid #ffc107;">
          <h4 style="color: #856404; font-weight: 600; margin-bottom: 16px; font-size: 1.1rem;">⏱️ Processing Time</h4>
          <p style="color: #2c3e50; line-height: 1.6; margin: 0; font-size: 15px;">
            Applications typically take <strong>3-5 business days</strong> to process. You will be notified at each step and when your certificate is ready for pickup.
          </p>
        </div>

        <div style="background: white; padding: 24px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); border-left: 4px solid #17a2b8;">
          <h4 style="color: #0c5460; font-weight: 600; margin-bottom: 16px; font-size: 1.1rem;">📞 Need Help?</h4>
          <p style="color: #2c3e50; line-height: 1.6; margin: 0; font-size: 15px;">
            Contact the <strong>Registrar's Office</strong> or <strong>Student Affairs</strong> for questions about your application status or requirements.
          </p>
        </div>
      </div>

      <div style="text-align: center;">
        @if (Route::has('login'))
        <a href="{{ route('login') }}" style="display: inline-block; background: var(--primary-green); color: white; padding: 16px 32px; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 1.1rem; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(0, 176, 80, 0.3);">
          Start Your Application
        </a>
        @endif
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer style="background: white; border-top: 1px solid #e1e8ed; padding: 32px 24px;">
    <div style="max-width: 1200px; margin: 0 auto; text-align: center;">
      <div class="accent-line" style="margin-bottom: 24px;"></div>
      <div class="footer-content" style="display: flex; justify-content: center; align-items: center; gap: 16px; margin-bottom: 16px; flex-wrap: wrap;">
        <div class="footer-logo-section" style="display: flex; align-items: center; gap: 12px;">
          <div class="footer-logo-container logo-container" style="padding: 4px;">
            <img src="{{ asset('images/backgrounds/spup-logo.png') }}" alt="SPUP Logo" style="height: 32px; width: auto;">
          </div>
          <div style="text-align: left;">
            <p style="font-weight: 600; color: #2c3e50; margin: 0; font-size: 14px;">St. Paul University Philippines</p>
            <p style="color: #7f8c8d; margin: 0; font-size: 12px;">Tuguegarao City, Cagayan</p>
          </div>
        </div>
      </div>
      <p style="color: #7f8c8d; font-size: 14px; margin: 0;">
        © {{ date('Y') }} Good Moral Certification Portal. All rights reserved.
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
