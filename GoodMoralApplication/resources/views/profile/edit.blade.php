@php
  $accountType = Auth::user()->account_type;
  $roleTitle = match($accountType) {
    'admin'       => 'Admin',
    'dean'        => 'Dean',
    'sec_osa'     => 'Moderator',
    'registrar'   => 'Registrar',
    'prog_coor'   => 'Program Coordinator',
    'psg_officer' => 'PSG Officer',
    'alumni'      => 'Alumni',
    default       => 'Student',
  };
@endphp

<x-dashboard-layout>
  <x-slot name="roleTitle">{{ $roleTitle }}</x-slot>

  <x-slot name="navigation">
    @if($accountType === 'admin')
      <x-admin-navigation />
    @elseif($accountType === 'dean')
      <x-dean-navigation />
    @elseif($accountType === 'sec_osa')
      <x-sec-osa-navigation />
    @elseif($accountType === 'registrar')
      <x-registrar-navigation />
    @elseif($accountType === 'prog_coor')
      <x-prog-coor-navigation />
    @elseif($accountType === 'psg_officer')
      <x-psg-officer-navigation />
    @else
      <x-student-navigation />
    @endif
  </x-slot>

  <!-- Header Section -->
  <div class="header-section">
    <div>
      <h1 class="role-title">{{ __('Profile') }}</h1>
      <p class="welcome-text">Manage your account settings and preferences</p>
      <div class="accent-line"></div>
    </div>
  </div>

  <div style="display: grid; gap: 24px;">
    <div style="background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); padding: 24px;">
      @include('profile.partials.update-profile-information-form')
    </div>

    <div style="background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); padding: 24px;">
      @include('profile.partials.update-password-form')
    </div>

    <div style="background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); padding: 24px;">
      @include('profile.partials.delete-user-form')
    </div>
  </div>
</x-dashboard-layout>
