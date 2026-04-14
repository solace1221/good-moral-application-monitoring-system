{{--
  Read-only application details modal.
  The #contentId div is populated by JavaScript via innerHTML.
  A "Close" footer button is shown by default.

  Use with @include:

    @include('shared.modals.application-details', [
        'id'        => 'detailsModal',
        'contentId' => 'modalContent',
        'closeFn'   => 'closeModal()',
    ])

  Then in JavaScript:
    function viewDetails(data) {
      document.getElementById('modalContent').innerHTML = `...`;
      document.getElementById('detailsModal').style.display = 'flex';
    }
    function closeModal() {
      document.getElementById('detailsModal').style.display = 'none';
    }

  Variables:
    id          – HTML id for the overlay div           (default: 'detailsModal')
    title       – Header title text                     (default: 'Application Details')
    contentId   – id of the JS-populated content div    (default: 'modalContent')
    closeFn     – JS for close/× buttons                (default: auto-computed)
    maxWidth    – Inner card max-width                  (default: '500px')
    footerHtml  – Optional raw HTML to replace the default Close button ({!! !!})
--}}
@php
    $id         = $id         ?? 'detailsModal';
    $title      = $title      ?? 'Application Details';
    $contentId  = $contentId  ?? 'modalContent';
    $maxWidth   = $maxWidth   ?? '500px';
    $footerHtml = $footerHtml ?? null;
    $jsClose    = (!empty($closeFn))
        ? $closeFn
        : "document.getElementById('" . $id . "').style.display='none'";
@endphp

<div id="{{ $id }}"
     style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0,0,0,0.5); z-index: 1000;
            align-items: center; justify-content: center; padding: 20px;"
     onclick="if(event.target===this){ {{ $jsClose }} }">

  <div style="background: white; padding: 0; border-radius: 12px;
              box-shadow: 0 10px 25px rgba(0,0,0,0.1);
              width: 100%; max-width: {{ $maxWidth }};
              max-height: 80vh; overflow: hidden;
              display: flex; flex-direction: column;">

    {{-- Header --}}
    <div style="display: flex; justify-content: space-between; align-items: center;
                padding: 20px 24px 16px; flex-shrink: 0; border-bottom: 1px solid #e9ecef;">
      <h2 style="margin: 0; color: var(--primary-green, #2d7a4f); font-size: 1.25rem; font-weight: 600;">
        {{ $title }}
      </h2>
      <button type="button"
              onclick="{{ $jsClose }}"
              style="background: none; border: none; font-size: 24px; cursor: pointer; color: #6c757d;">
        &times;
      </button>
    </div>

    {{-- JavaScript-populated content grid --}}
    <div id="{{ $contentId }}" style="padding: 20px 24px; overflow-y: auto; flex: 1;">
      {{-- Populated by JavaScript --}}
    </div>

    {{-- Footer: custom HTML string or default Close button --}}
    @if($footerHtml)
      <div style="padding: 0 24px 20px; flex-shrink: 0;">{!! $footerHtml !!}</div>
    @else
      <div style="display: flex; justify-content: flex-end; gap: 12px; padding: 12px 24px 20px; flex-shrink: 0; border-top: 1px solid #e9ecef;">
        <button type="button"
                onclick="{{ $jsClose }}"
                style="background: #6c757d; color: white; border: none;
                       padding: 10px 20px; border-radius: 6px; cursor: pointer;">
          Close
        </button>
      </div>
    @endif

  </div>
</div>
