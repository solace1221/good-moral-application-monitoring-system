<x-dashboard-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      {{ __('Multiple Violators Debug') }}
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
      <div style="background: white; padding: 24px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
        <h3>Multiple Violators Debug Page</h3>
        
        <!-- Simple test form -->
        <form method="POST" action="{{ route('admin.storeMultipleViolators') }}" onsubmit="return debugSubmit()" id="debug-form">
          @csrf
          
          <!-- Basic test data -->
          <input type="hidden" name="offense_type" value="minor">
          <input type="hidden" name="violation" value="Improper Uniform">
          <input type="hidden" name="student_ids[]" value="2021-1234">
          <input type="hidden" name="multiple_violations_data" value="">
          
          <h4>Test 1: Basic Form Submission</h4>
          <p>This will test if the basic route and controller are working.</p>
          <button type="submit" class="btn-primary">Test Basic Submission</button>
          
          <div id="debug-output" style="margin-top: 20px; padding: 16px; background: #f3f4f6; border-radius: 8px;">
            <h5>Debug Information:</h5>
            <p>Form Action: {{ route('admin.storeMultipleViolators') }}</p>
            <p>CSRF Token: {{ csrf_token() }}</p>
            <p>Route Exists: {{ Route::has('admin.storeMultipleViolators') ? 'Yes' : 'No' }}</p>
          </div>
        </form>

        <!-- JavaScript test -->
        <div style="margin-top: 30px; padding: 20px; border: 2px solid #e5e7eb; border-radius: 8px;">
          <h4>Test 2: JavaScript Functionality</h4>
          <button type="button" onclick="testJavaScript()" class="btn-primary">Test JavaScript</button>
          <div id="js-output" style="margin-top: 10px; padding: 10px; background: #f9f9f9; border-radius: 4px;"></div>
        </div>

        <!-- AJAX test -->
        <div style="margin-top: 30px; padding: 20px; border: 2px solid #e5e7eb; border-radius: 8px;">
          <h4>Test 3: AJAX Submission</h4>
          <button type="button" onclick="testAjax()" class="btn-primary">Test AJAX</button>
          <div id="ajax-output" style="margin-top: 10px; padding: 10px; background: #f9f9f9; border-radius: 4px;"></div>
        </div>

      </div>
    </div>
  </div>

  <script>
    function debugSubmit() {
      console.log('🚀 DEBUG: Form submitted');
      console.log('Form action:', document.getElementById('debug-form').action);
      console.log('Form method:', document.getElementById('debug-form').method);
      
      const formData = new FormData(document.getElementById('debug-form'));
      console.log('Form data:');
      for (let [key, value] of formData.entries()) {
        console.log(`  ${key}: ${value}`);
      }
      
      alert('Form submission started - check browser console for details');
      return true; // Allow form to submit
    }

    function testJavaScript() {
      const output = document.getElementById('js-output');
      try {
        // Test if basic JavaScript is working
        const testData = {
          timestamp: new Date().toISOString(),
          userAgent: navigator.userAgent,
          url: window.location.href
        };
        
        output.innerHTML = '<strong>JavaScript Test Results:</strong><br>' +
          'Timestamp: ' + testData.timestamp + '<br>' +
          'URL: ' + testData.url + '<br>' +
          'Status: ✅ JavaScript is working';
        
        console.log('JavaScript test successful:', testData);
      } catch (error) {
        output.innerHTML = '<strong>JavaScript Error:</strong><br>' + error.message;
        console.error('JavaScript test failed:', error);
      }
    }

    function testAjax() {
      const output = document.getElementById('ajax-output');
      output.innerHTML = 'Testing AJAX...';
      
      const formData = new FormData();
      formData.append('_token', '{{ csrf_token() }}');
      formData.append('offense_type', 'minor');
      formData.append('violation', 'Test Violation');
      formData.append('student_ids[]', '2021-TEST');
      formData.append('debug_mode', 'true');

      fetch('{{ route('admin.storeMultipleViolators') }}', {
        method: 'POST',
        body: formData,
        headers: {
          'X-Requested-With': 'XMLHttpRequest'
        }
      })
      .then(response => {
        console.log('AJAX Response Status:', response.status);
        return response.text();
      })
      .then(data => {
        console.log('AJAX Response Data:', data);
        output.innerHTML = '<strong>AJAX Test Results:</strong><br>' +
          'Status: ✅ AJAX request completed<br>' +
          'Response: <pre>' + data + '</pre>';
      })
      .catch(error => {
        console.error('AJAX Error:', error);
        output.innerHTML = '<strong>AJAX Error:</strong><br>' + error.message;
      });
    }
  </script>
</x-dashboard-layout>