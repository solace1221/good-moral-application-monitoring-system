{{--
  Shared JS-driven confirmation modal.
  All content (title, icon, message, button text/color) is set at runtime via JavaScript.
  Required element IDs: modalTitle, modalIcon, modalMessage, modalCancel, modalConfirm
  Open  : document.getElementById('confirmationModal').style.display = 'flex'
  Close : closeConfirmationModal()
--}}
<div id="confirmationModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
  <div style="background: white; padding: 24px; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); width: 100%; max-width: 500px; margin: 20px;">

    {{-- Header: × button; h2 title set by JS --}}
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
      <h2 id="modalTitle" style="margin: 0; font-size: 1.25rem; font-weight: 700; color: #1f2937;">Confirm Action</h2>
      <button type="button" onclick="closeConfirmationModal()" style="background: none; border: none; font-size: 24px; cursor: pointer; color: #6c757d;">&times;</button>
    </div>

    {{-- Icon div — background/innerHTML set by JS --}}
    <div style="text-align: center; margin-bottom: 16px;">
      <div id="modalIcon" style="width: 80px; height: 80px; margin: 0 auto 16px; border-radius: 50%; display: flex; align-items: center; justify-content: center;"></div>
    </div>

    {{-- Message — textContent set by JS --}}
    <div style="text-align: center; margin-bottom: 32px;">
      <p id="modalMessage" style="margin: 0; font-size: 16px; color: #6b7280; line-height: 1.6;">Are you sure you want to proceed?</p>
    </div>

    {{-- Footer buttons --}}
    <div style="display: flex; gap: 12px; justify-content: center;">
      <button id="modalCancel" onclick="closeConfirmationModal()"
              style="padding: 12px 24px; background: #f3f4f6; color: #374151; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; transition: all 0.3s ease; min-width: 100px;"
              onmouseover="this.style.background='#e5e7eb'" onmouseout="this.style.background='#f3f4f6'">
        Cancel
      </button>
      <button id="modalConfirm" onclick="confirmAction()"
              style="padding: 12px 24px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; transition: all 0.3s ease; min-width: 100px; color: white;">
        Confirm
      </button>
    </div>

  </div>
</div>
