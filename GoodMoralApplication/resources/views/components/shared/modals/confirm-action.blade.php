{{--
  Shared confirmation modal shell.
  Provides the overlay, white card, and header (title + close button).
  Body and footer are customisable via the default slot and the named "footer" slot.

  Usage:
    <x-shared.modals.confirm-action id="myModal" title="Confirm" title-color="#c53030" close-fn="closeMyModal()">
      body content here
      <x-slot name="footer">
        footer buttons here
      </x-slot>
    </x-shared.modals.confirm-action>

  Props:
    id          (required) - HTML id for the overlay div
    title       - Header title text              (default: 'Confirm Action')
    titleColor  - CSS colour for the h2          (default: '#1f2937')
    closeFn     - JS called by cancel / x button (default: auto-computed)
    maxWidth    - Inner card max-width           (default: '500px')
    zIndex      - Overlay z-index                (default: '1000')
--}}
@props([
    'id',
    'title'      => 'Confirm Action',
    'titleColor' => '#1f2937',
    'closeFn'    => '',
    'maxWidth'   => '500px',
    'zIndex'     => '1000',
])
@php
    $jsClose = (!empty($closeFn))
        ? $closeFn
        : "document.getElementById('" . $id . "').style.display='none'";
@endphp

<div id="{{ $id }}"
     style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0,0,0,0.5); z-index: {{ $zIndex }};
            align-items: center; justify-content: center;"
     onclick="if(event.target===this){ {{ $jsClose }} }">

  <div style="background: white; padding: 24px; border-radius: 12px;
              box-shadow: 0 10px 25px rgba(0,0,0,0.1);
              width: 100%; max-width: {{ $maxWidth }}; margin: 20px;">

    {{-- Header --}}
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
      <h2 style="margin: 0; color: {{ $titleColor }}; font-size: 1.25rem; font-weight: 600;">
        {{ $title }}
      </h2>
      <button type="button"
              onclick="{{ $jsClose }}"
              style="background: none; border: none; font-size: 24px; cursor: pointer; color: #6c757d;">
        &times;
      </button>
    </div>

    {{-- Body (default slot) --}}
    {{ $slot }}

    {{-- Optional custom footer --}}
    @isset($footer)
      {{ $footer }}
    @endisset

  </div>
</div>
