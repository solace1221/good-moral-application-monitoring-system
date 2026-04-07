@php
  $accountType = Auth::user()->account_type;
  $roleTitle = $accountType === 'alumni' ? 'Alumni' : 'Student';
@endphp

<x-dashboard-layout>
  <x-slot name="roleTitle">{{ $roleTitle }}</x-slot>

  <x-slot name="navigation">
    <a href="{{ route('dashboard') }}"
       class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="nav-icon">
        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z" />
      </svg>
      <span>Application</span>
    </a>

    <a href="{{ route('notification') }}"
       class="nav-link {{ request()->routeIs('notification') ? 'active' : '' }}">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="nav-icon">
        <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
      </svg>
      <span>Application Notifications</span>
    </a>

    <a href="{{ route('notificationViolation') }}"
       class="nav-link {{ request()->routeIs('notificationViolation') ? 'active' : '' }}">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="nav-icon">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
      </svg>
      <span>Violation Notifications</span>
    </a>

    <a href="{{ route('student.profile') }}"
       class="nav-link {{ request()->routeIs('student.profile') ? 'active' : '' }}">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="nav-icon">
        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
      </svg>
      <span>Profile</span>
    </a>

    <form method="POST" action="{{ route('logout') }}" class="nav-logout-form">
      @csrf
      <button type="submit" class="nav-link nav-logout">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="nav-icon">
          <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
        </svg>
        <span>Logout</span>
      </button>
    </form>
  </x-slot>

  <!-- Header Section -->
  <div class="header-section">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
      <div>
        <h1 class="role-title">Violation Notifications</h1>
        <p class="welcome-text">Track your violation status and updates</p>
        <div class="accent-line"></div>
      </div>
      <div style="display: flex; gap: 12px; align-items: center;">
        <div style="padding: 12px 16px; background: var(--light-green); border-radius: 8px; font-size: 14px; color: var(--primary-green); font-weight: 600;">
          {{ $notifications->count() }} Notification{{ $notifications->count() !== 1 ? 's' : '' }}
        </div>
      </div>
    </div>
  </div>

  @if(session('status'))
  <div style="background: #d4edda; color: #155724; padding: 16px; border-radius: 8px; margin-bottom: 24px; border-left: 4px solid #28a745;">
    <strong>Success!</strong> {{ session('status') }}
  </div>
  @endif

  <!-- Violations Notifications Section -->
  <div class="header-section">
    <h3 style="color: var(--primary-green); margin-bottom: 20px; font-size: 1.3rem; display: flex; align-items: center; gap: 12px;">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="height: 24px; width: 24px;">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
      </svg>
      Your Violation Status Updates
    </h3>

    @if($notifications->isEmpty())
    <div style="background: #f8f9fa; color: #6c757d; padding: 40px; border-radius: 8px; text-align: center; border: 2px dashed #dee2e6;">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="height: 48px; width: 48px; margin: 0 auto 16px; color: #adb5bd;">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
      </svg>
      <h4 style="font-size: 1.2rem; margin-bottom: 8px; color: #495057;">No Violation Notifications</h4>
      <p style="margin: 0;">You have no violation notifications at the moment.</p>
    </div>
    @else
    <div style="display: grid; gap: 20px;">
      @foreach ($notifications as $notification)
      <div style="background: white; border-radius: 12px; padding: 24px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
                  border-left: 4px solid
                  @if($notification->status == '0')
                    #ffc107
                  @elseif($notification->status == '1')
                    #28a745
                  @elseif($notification->status == '2')
                    #28a745
                  @else
                    #6c757d
                  @endif;">

        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 16px;">
          <div style="flex: 1;">
            <h5 style="font-size: 1.2rem; font-weight: 600; color: #333; margin-bottom: 8px;">
              Violation Notification
            </h5>
            <div style="display: flex; align-items: center; gap: 8px; color: #666; font-size: 14px;">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="height: 16px; width: 16px;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5a2.25 2.25 0 002.25-2.25m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5a2.25 2.25 0 012.25 2.25v7.5" />
              </svg>
              {{ $notification->created_at->format('M d, Y \a\t g:i A') }}
            </div>
          </div>

          <span style="padding: 6px 16px; border-radius: 20px; font-size: 12px; font-weight: 600; text-transform: uppercase;
                       background:
                       @if($notification->status == '0')
                         #ffc107
                       @elseif($notification->status == '1')
                         #28a745
                       @elseif($notification->status == '2')
                         #28a745
                       @else
                         #6c757d
                       @endif;
                       color:
                       @if($notification->status == '0')
                         #333
                       @else
                         white
                       @endif;">
            @if($notification->status == '0')
              Under Review
            @elseif($notification->status == '1')
              Resolved
            @elseif($notification->status == '2')
              Approved
            @else
              {{ ucfirst($notification->status) }}
            @endif
          </span>
        </div>

        @if($notification->ref_num)
        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 16px;">
          <strong style="color: #333; min-width: 140px;">Reference Number:</strong>
          <span style="font-family: monospace; background: #f8f9fa; padding: 4px 8px; border-radius: 4px; font-size: 14px;">{{ $notification->ref_num }}</span>
        </div>
        @endif

        <div style="background: #f8f9fa; padding: 16px; border-radius: 8px; margin-bottom: 16px;">
          <strong style="color: #333; display: block; margin-bottom: 8px;">Notification Message:</strong>
          <p style="margin: 0; color: #555; line-height: 1.5;">
            {{ $notification->notif ?? 'No additional message provided.' }}
          </p>
        </div>

        <div style="background:
                    @if($notification->status == '0')
                      #fff3cd
                    @elseif(in_array($notification->status, ['1', '2']))
                      #d4edda
                    @else
                      #f8f9fa
                    @endif;
                    color:
                    @if($notification->status == '0')
                      #856404
                    @elseif(in_array($notification->status, ['1', '2']))
                      #155724
                    @else
                      #6c757d
                    @endif;
                    padding: 16px; border-radius: 8px; display: flex; align-items: center; gap: 12px;">

          @if($notification->status == '0')
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="height: 20px; width: 20px; color: #ffc107;">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <div>
              <strong>Under Review</strong>
              <p style="margin: 4px 0 0 0; font-size: 14px;">Your violation is currently being reviewed by the Administrator.</p>
            </div>
          @elseif($notification->status == '1')
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="height: 20px; width: 20px; color: #28a745;">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <div>
              <strong>Violation Resolved</strong>
              <p style="margin: 4px 0 0 0; font-size: 14px;">Your violation has been successfully resolved.</p>
            </div>
          @elseif($notification->status == '2')
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="height: 20px; width: 20px; color: #28a745;">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <div>
              <strong>Approved by Dean</strong>
              <p style="margin: 4px 0 0 0; font-size: 14px;">Your violation resolution has been approved by the Dean.</p>
            </div>
          @else
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="height: 20px; width: 20px; color: #6c757d;">
              <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
            </svg>
            <div>
              <strong>Status Update</strong>
              <p style="margin: 4px 0 0 0; font-size: 14px;">{{ ucfirst($notification->status) }}</p>
            </div>
          @endif
        </div>

      </div>
      @endforeach
    </div>
    @endif
  </div>

</x-dashboard-layout>