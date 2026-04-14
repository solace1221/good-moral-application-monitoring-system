{{--
  Rejection form modal with reason <select>, optional specify field, and details <textarea>.
  Self-contained; the form <action> is set dynamically by JavaScript before opening.

  Use with @include:

    @include('shared.modals.reject-with-reason', [
        'id'      => 'rejectModal',
        'formId'  => 'rejectForm',
        'closeFn' => 'closeRejectModal()',
        'reasons' => ['Student not officially enrolled', 'Others: specify'],
    ])

  Set the form action and open from JavaScript:
    function openRejectModal(applicationId, role) {
      document.getElementById('rejectForm').action = `/${role}/reject/${applicationId}`;
      document.getElementById('rejectModal').style.display = 'flex';
    }
    function closeRejectModal() {
      const modal = document.getElementById('rejectModal');
      modal.style.display = 'none';
      document.getElementById('rejectForm').reset();
      document.getElementById('rejectFormSpecifyField').style.display = 'none';
      document.getElementById('rejectFormSpecifyInput').required = false;
    }

  For a "Reconsider" variant, pass title, submitLabel, and optional extraHtml:
    @include('shared.modals.reject-with-reason', [
        'id'          => 'reconsiderModal',
        'formId'      => 'reconsiderForm',
        'title'       => 'Reconsider Application',
        'submitLabel' => 'Reconsider Application',
        'closeFn'     => 'closeReconsiderModal()',
        'extraHtml'   => '<div id="rejectionInfo" style="...">...</div>',
    ])

  Variables:
    id              – HTML id for the overlay div         (default: 'rejectModal')
    title           – Header title text                   (default: 'Reject Application')
    formId          – id for the <form> element           (default: 'rejectForm')
    closeFn         – JS for close/cancel                 (default: auto-computed)
    submitLabel     – Confirm button text                 (default: 'Reject Application')
    specifyTrigger  – Option value that reveals specify   (default: 'Others: specify')
    reasons         – PHP array of reason strings
    maxWidth        – Inner card max-width                (default: '500px')
    extraHtml       – Optional raw HTML shown above form  (use {!! !!} internally)
--}}
@php
    $id             = $id             ?? 'rejectModal';
    $title          = $title          ?? 'Reject Application';
    $formId         = $formId         ?? 'rejectForm';
    $submitLabel    = $submitLabel    ?? 'Reject Application';
    $specifyTrigger = $specifyTrigger ?? 'Others: specify';
    $maxWidth       = $maxWidth       ?? '500px';
    $extraHtml      = $extraHtml      ?? null;
    $reasons        = $reasons        ?? [
        'Incomplete Documents',
        'Invalid Information',
        'Outstanding Violations',
        'Eligibility Requirements Not Met',
        'Others: specify',
    ];
    $jsClose        = (!empty($closeFn))
        ? $closeFn
        : "document.getElementById('" . $id . "').style.display='none'";
    $escapedTrigger = addslashes($specifyTrigger);
@endphp

<div id="{{ $id }}"
     style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0,0,0,0.5); z-index: 1000;
            align-items: center; justify-content: center;"
     onclick="if(event.target===this){ {{ $jsClose }} }">

  <div style="background: white; padding: 24px; border-radius: 12px;
              box-shadow: 0 10px 25px rgba(0,0,0,0.1);
              width: 100%; max-width: {{ $maxWidth }}; margin: 20px;">

    {{-- Header --}}
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
      <h2 style="margin: 0; color: #dc3545; font-size: 1.25rem; font-weight: 600;">
        {{ $title }}
      </h2>
      <button type="button"
              onclick="{{ $jsClose }}"
              style="background: none; border: none; font-size: 24px; cursor: pointer; color: #6c757d;">
        &times;
      </button>
    </div>

    {{-- Optional extra content (e.g., previous rejection info for reconsider flow) --}}
    @if($extraHtml)
      {!! $extraHtml !!}
    @endif

    {{-- Rejection form --}}
    <form id="{{ $formId }}" method="POST">
      @csrf
      @method('PATCH')

      {{-- Rejection Reason --}}
      <div style="margin-bottom: 16px;">
        <label style="display: block; font-weight: 600; color: #333; margin-bottom: 8px;">
          Rejection Reason <span style="color: #dc3545;">*</span>
        </label>
        <select id="{{ $formId }}Reason"
                name="rejection_reason"
                required
                onchange="_rejectReasonChange('{{ $formId }}', '{{ $escapedTrigger }}')"
                style="width: 100%; padding: 12px; border: 2px solid #e1e5e9; border-radius: 8px; font-size: 14px;">
          <option value="">Select a reason</option>
          @foreach($reasons as $reason)
            <option value="{{ $reason }}">{{ $reason }}</option>
          @endforeach
        </select>
      </div>

      {{-- Specify field (shown when specifyTrigger is selected) --}}
      <div id="{{ $formId }}SpecifyField" style="margin-bottom: 16px; display: none;">
        <label style="display: block; font-weight: 600; color: #333; margin-bottom: 8px;">
          Please Specify <span style="color: #dc3545;">*</span>
        </label>
        <input id="{{ $formId }}SpecifyInput"
               type="text"
               name="specify_reason"
               placeholder="Please specify the reason for rejection..."
               style="width: 100%; padding: 12px; border: 2px solid #e1e5e9; border-radius: 8px; font-size: 14px;">
      </div>

      {{-- Additional Details --}}
      <div style="margin-bottom: 20px;">
        <label style="display: block; font-weight: 600; color: #333; margin-bottom: 8px;">
          Additional Details
        </label>
        <textarea name="rejection_details"
                  rows="4"
                  placeholder="Please provide additional details about the rejection..."
                  style="width: 100%; padding: 12px; border: 2px solid #e1e5e9; border-radius: 8px; font-size: 14px; resize: vertical;"></textarea>
      </div>

      {{-- Buttons --}}
      <div style="display: flex; justify-content: flex-end; gap: 12px;">
        <button type="button"
                onclick="{{ $jsClose }}"
                style="background: #6c757d; color: white; border: none;
                       padding: 10px 20px; border-radius: 6px; cursor: pointer;">
          Cancel
        </button>
        <button type="submit"
                style="background: #dc3545; color: white; border: none;
                       padding: 10px 20px; border-radius: 6px; cursor: pointer;">
          {{ $submitLabel }}
        </button>
      </div>
    </form>

  </div>
</div>

<script>
  (function () {
    if (typeof window._rejectReasonChange === 'undefined') {
      window._rejectReasonChange = function (formId, trigger) {
        var select = document.getElementById(formId + 'Reason');
        var field  = document.getElementById(formId + 'SpecifyField');
        var input  = document.getElementById(formId + 'SpecifyInput');
        if (select && select.value === trigger) {
          field.style.display = 'block';
          input.required = true;
        } else if (field) {
          field.style.display = 'none';
          input.required = false;
          input.value = '';
        }
      };
    }
  }());
</script>
