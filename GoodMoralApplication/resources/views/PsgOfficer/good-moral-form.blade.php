<x-dashboard-layout>
  <x-slot name="roleTitle">PSG Officer</x-slot>

  <x-slot name="navigation">
    <x-psg-officer-navigation />
  </x-slot>

  @include('shared.alerts.flash')

  <!-- Header Section -->
  <div class="header-section">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
      <div>
        <h1 class="role-title">Apply for Good Moral Certificate</h1>
        <p class="welcome-text">Submit your application for a Good Moral Certificate or Certificate of Residency</p>
        <div class="accent-line"></div>
      </div>

    </div>
  </div>

  @include('certificates._application-form', ['violations' => $violations, 'formAction' => route('PsgOfficer.applyGoodMoral')])
</x-dashboard-layout>