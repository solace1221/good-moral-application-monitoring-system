<x-dashboard-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      {{ __('Multiple Violators - Simple Test') }}
    </h2>
  </x-slot>

  <style>
    :root {
      --primary-green: #10B981;
    }

    .btn-primary {
      background: var(--primary-green);
      color: white;
      border: none;
      padding: 12px 24px;
      border-radius: 8px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s ease;
    }

    .btn-primary:hover {
      background: #059669;
      transform: translateY(-1px);
    }
  </style>

  <div class="py-12" style="background: #f9fafb; min-height: 100vh;">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      @if ($errors->any())
      <div style="background: #fef2f2; border: 1px solid #fca5a5; color: #dc2626; padding: 16px; border-radius: 8px; margin-bottom: 24px;">
        <h4>Validation Errors:</h4>
        <ul style="margin: 0; padding-left: 20px;">
          @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
      @endif

      @if (session('success'))
      <div style="background: #efe; border: 1px solid #cfc; color: #363; padding: 16px; border-radius: 8px; margin-bottom: 24px;">
        {{ session('success') }}
      </div>
      @endif

      <div style="background: white; padding: 24px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
        <h3>Simple Multiple Violators Test</h3>
        <p>This is a simplified version to test the basic functionality.</p>
        
        <form method="POST" action="{{ route('admin.storeMultipleViolators') }}" id="simple-test-form">
          @csrf
          
          <div style="margin-bottom: 20px;">
            <label>Offense Type:</label><br>
            <select name="offense_type" required>
              <option value="">Select</option>
              <option value="minor">Minor</option>
              <option value="major">Major</option>
            </select>
          </div>

          <div style="margin-bottom: 20px;">
            <label>Violation:</label><br>
            <select name="violation">
              <option value="">Select</option>
              <option value="Improper Uniform">Improper Uniform</option>
              <option value="Late Arrival">Late Arrival</option>
              <option value="Disruptive Behavior">Disruptive Behavior</option>
            </select>
          </div>

          <div style="margin-bottom: 20px;">
            <label>Students (one per line):</label><br>
            <textarea name="student_list" rows="5" cols="50" placeholder="Enter student IDs, one per line:
2021-1234
2021-5678
2021-9999"></textarea>
          </div>

          <button type="submit" class="btn-primary">Submit Simple Test</button>
        </form>

        <!-- Debug info -->
        <div style="margin-top: 30px; padding: 20px; background: #f3f4f6; border-radius: 8px;">
          <h4>Debug Info:</h4>
          <p><strong>Route URL:</strong> {{ route('admin.storeMultipleViolators') }}</p>
          <p><strong>Available Violations:</strong></p>
          <ul>
            @foreach (\App\Models\Violation::take(5)->get() as $violation)
            <li>{{ $violation->description }} ({{ $violation->offense_type }})</li>
            @endforeach
          </ul>
        </div>
      </div>
    </div>
  </div>

  <script>
    document.getElementById('simple-test-form').addEventListener('submit', function(e) {
      console.log('Simple form submitted');
      
      // Convert textarea student list to hidden inputs
      const studentList = document.getElementsByName('student_list')[0].value;
      const studentIds = studentList.split('\n').filter(id => id.trim() !== '');
      
      // Remove existing student_ids inputs
      const existingInputs = document.querySelectorAll('input[name="student_ids[]"]');
      existingInputs.forEach(input => input.remove());
      
      // Add student IDs as hidden inputs
      const form = this;
      studentIds.forEach(studentId => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'student_ids[]';
        input.value = studentId.trim();
        form.appendChild(input);
      });

      console.log('Student IDs:', studentIds);
      
      if (studentIds.length === 0) {
        alert('Please enter at least one student ID');
        e.preventDefault();
        return false;
      }
    });
  </script>
</x-dashboard-layout>