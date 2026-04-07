<x-dashboard-layout>
  <div style="padding: 24px; background: #f8f9fa; min-height: 100vh;">
    <!-- Header Section -->
    <div style="background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); padding: 24px; margin-bottom: 24px;">
      <div style="display: flex; justify-content: space-between; align-items: center;">
        <div>
          <h1 style="margin: 0 0 8px 0; color: var(--primary-green); font-size: 1.75rem; font-weight: 700;">Upload Meeting Proceedings</h1>
          <p style="margin: 0; color: #6c757d; font-size: 1rem;">Upload the proceedings document for major violation case</p>
        </div>
        <a href="{{ route('sec_osa.major') }}" 
           style="background: #6c757d; color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 500; transition: all 0.2s ease;"
           onmouseover="this.style.background='#5a6268'"
           onmouseout="this.style.background='#6c757d'">
          ← Back to Major Violations
        </a>
      </div>
    </div>

    <!-- Violation Details -->
    <div style="background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); padding: 24px; margin-bottom: 24px;">
      <h3 style="margin: 0 0 16px 0; color: #333; font-size: 1.25rem; font-weight: 600;">Violation Details</h3>
      
      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 16px; margin-bottom: 24px;">
        <div style="padding: 16px; background: #f8f9fa; border-radius: 8px;">
          <strong style="color: #495057; display: block; margin-bottom: 4px;">Student Name:</strong>
          <span style="color: #333;">{{ $violation->first_name }} {{ $violation->last_name }}</span>
        </div>
        <div style="padding: 16px; background: #f8f9fa; border-radius: 8px;">
          <strong style="color: #495057; display: block; margin-bottom: 4px;">Student ID:</strong>
          <span style="color: #333;">{{ $violation->student_id }}</span>
        </div>
        <div style="padding: 16px; background: #f8f9fa; border-radius: 8px;">
          <strong style="color: #495057; display: block; margin-bottom: 4px;">Department:</strong>
          <span style="color: #333;">{{ $violation->department }}</span>
        </div>
        <div style="padding: 16px; background: #f8f9fa; border-radius: 8px;">
          <strong style="color: #495057; display: block; margin-bottom: 4px;">Course:</strong>
          <span style="color: #333;">{{ $violation->course ?? 'N/A' }}</span>
        </div>
      </div>

      <div style="padding: 16px; background: #fff3cd; border-radius: 8px; border-left: 4px solid #ffc107;">
        <strong style="color: #856404; display: block; margin-bottom: 8px;">Violation Description:</strong>
        <p style="margin: 0; color: #856404; line-height: 1.5;">{{ $violation->violation }}</p>
      </div>
    </div>

    <!-- Upload Form -->
    <div style="background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); padding: 24px;">
      <h3 style="margin: 0 0 24px 0; color: #333; font-size: 1.25rem; font-weight: 600;">Upload Meeting Proceedings</h3>

      @if($errors->any())
        <div style="background: #f8d7da; color: #721c24; padding: 16px; border-radius: 8px; margin-bottom: 24px; border-left: 4px solid #dc3545;">
          <strong>Please correct the following errors:</strong>
          <ul style="margin: 8px 0 0 0; padding-left: 20px;">
            @foreach($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form action="{{ route('sec_osa.uploadProceedings', $violation->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div style="display: grid; gap: 24px;">
          <!-- Meeting Date -->
          <div>
            <label for="meeting_date" style="display: block; margin-bottom: 8px; font-weight: 600; color: #333;">
              Meeting Date <span style="color: #dc3545;">*</span>
            </label>
            <input type="date" 
                   id="meeting_date" 
                   name="meeting_date" 
                   value="{{ old('meeting_date') }}"
                   required
                   style="width: 100%; padding: 12px; border: 2px solid #e9ecef; border-radius: 8px; font-size: 16px; transition: border-color 0.2s ease;"
                   onfocus="this.style.borderColor='var(--primary-green)'"
                   onblur="this.style.borderColor='#e9ecef'">
          </div>

          <!-- Proceedings Document -->
          <div>
            <label for="proceedings_document" style="display: block; margin-bottom: 8px; font-weight: 600; color: #333;">
              Proceedings Document <span style="color: #dc3545;">*</span>
            </label>
            <input type="file" 
                   id="proceedings_document" 
                   name="proceedings_document" 
                   accept=".pdf,.doc,.docx"
                   required
                   style="width: 100%; padding: 12px; border: 2px solid #e9ecef; border-radius: 8px; font-size: 16px; transition: border-color 0.2s ease;"
                   onfocus="this.style.borderColor='var(--primary-green)'"
                   onblur="this.style.borderColor='#e9ecef'">
            <small style="color: #6c757d; font-size: 14px; margin-top: 4px; display: block;">
              Accepted formats: PDF, DOC, DOCX. Maximum file size: 10MB
            </small>
          </div>

          <!-- Meeting Notes -->
          <div>
            <label for="meeting_notes" style="display: block; margin-bottom: 8px; font-weight: 600; color: #333;">
              Meeting Notes (Optional)
            </label>
            <textarea id="meeting_notes" 
                      name="meeting_notes" 
                      rows="4"
                      placeholder="Enter any additional notes about the meeting..."
                      style="width: 100%; padding: 12px; border: 2px solid #e9ecef; border-radius: 8px; font-size: 16px; resize: vertical; transition: border-color 0.2s ease;"
                      onfocus="this.style.borderColor='var(--primary-green)'"
                      onblur="this.style.borderColor='#e9ecef'">{{ old('meeting_notes') }}</textarea>
          </div>

          <!-- Submit Button -->
          <div style="display: flex; gap: 12px; justify-content: flex-end;">
            <a href="{{ route('sec_osa.major') }}" 
               style="background: #6c757d; color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 500; transition: all 0.2s ease;"
               onmouseover="this.style.background='#5a6268'"
               onmouseout="this.style.background='#6c757d'">
              Cancel
            </a>
            <button type="submit" 
                    style="background: var(--primary-green); color: white; padding: 12px 24px; border: none; border-radius: 8px; font-weight: 500; cursor: pointer; transition: all 0.2s ease;"
                    onmouseover="this.style.background='var(--dark-green)'"
                    onmouseout="this.style.background='var(--primary-green)'">
              Upload Proceedings
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>
</x-dashboard-layout>
