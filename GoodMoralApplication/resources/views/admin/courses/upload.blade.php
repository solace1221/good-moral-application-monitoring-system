<x-dashboard-layout title="Course Management - Upload CSV">
  <div class="responsive-container">
    <div class="responsive-card">
      <div class="card-header">
        <h2 class="responsive-title">📚 Upload Course Data</h2>
        <p class="responsive-text">Upload a CSV file to import course data into the system.</p>
      </div>

      <div class="card-body">
        @if (session('success'))
          <div class="alert alert-success">
            <strong>✅ Success!</strong> {{ session('success') }}
            
            @if (session('import_details'))
              @php $details = session('import_details') @endphp
              <div style="margin-top: 10px; padding: 10px; background: #f8f9fa; border-radius: 4px;">
                <strong>Import Summary:</strong><br>
                • Successfully imported: {{ $details['success_count'] }} courses<br>
                @if ($details['error_count'] > 0)
                  • Errors: {{ $details['error_count'] }}<br>
                  @if (!empty($details['errors']))
                    <details style="margin-top: 8px;">
                      <summary>View Errors</summary>
                      <ul style="margin: 8px 0; padding-left: 20px;">
                        @foreach ($details['errors'] as $error)
                          <li>{{ $error }}</li>
                        @endforeach
                      </ul>
                    </details>
                  @endif
                @endif
              </div>
            @endif
          </div>
        @endif

        @if (session('error'))
          <div class="alert alert-error">
            <strong>❌ Error!</strong> {{ session('error') }}
          </div>
        @endif

        @if ($errors->any())
          <div class="alert alert-error">
            <strong>❌ Validation Errors:</strong>
            <ul style="margin: 8px 0; padding-left: 20px;">
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <!-- CSV Upload Form -->
        <form action="{{ route('admin.courses.upload') }}" method="POST" enctype="multipart/form-data" class="responsive-form">
          @csrf
          
          <div class="responsive-form-group">
            <label for="csv_file" style="font-weight: 600; color: #333;">
              📄 Select CSV File
            </label>
            <input type="file" 
                   id="csv_file" 
                   name="csv_file" 
                   accept=".csv,.txt" 
                   required 
                   class="responsive-form-input">
            <small style="color: #666; font-size: 14px;">
              Accepted formats: CSV, TXT (Max size: 2MB)
            </small>
          </div>

          <div class="responsive-form-group">
            <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
              <input type="checkbox" name="clear_existing" value="1" style="margin: 0;">
              <span style="font-weight: 600; color: #333;">
                🗑️ Clear existing courses before import
              </span>
            </label>
            <small style="color: #dc3545; font-size: 14px; margin-left: 24px;">
              ⚠️ Warning: This will delete all existing course data!
            </small>
          </div>

          <div class="responsive-form-group">
            <button type="submit" class="responsive-btn responsive-btn-primary">
              📤 Upload and Import Courses
            </button>
          </div>
        </form>

        <!-- CSV Format Information -->
        <div style="margin-top: 30px; padding: 20px; background: #f8f9fa; border-radius: 8px; border-left: 4px solid #007bff;">
          <h3 style="margin: 0 0 15px 0; color: #333;">📋 CSV Format Requirements</h3>
          
          <p style="margin: 0 0 10px 0; color: #666;">
            Your CSV file must include the following columns in this exact order:
          </p>
          
          <ol style="margin: 10px 0; padding-left: 20px; color: #666;">
            <li><strong>course_code</strong> - Unique course identifier (e.g., "BSIT", "BSN")</li>
            <li><strong>course_name</strong> - Full course name (e.g., "Bachelor of Science in Information Technology")</li>
            <li><strong>department</strong> - Department code (e.g., "SITE", "SNAHS")</li>
            <li><strong>department_name</strong> - Full department name (e.g., "School of Information Technology and Engineering")</li>
            <li><strong>description</strong> - Course description (optional)</li>
            <li><strong>sort_order</strong> - Display order within department (optional, defaults to 0)</li>
          </ol>

          <div style="margin-top: 15px;">
            <a href="{{ route('admin.courses.template') }}" 
               class="responsive-btn responsive-btn-secondary"
               style="text-decoration: none;">
              📥 Download Sample Template
            </a>
          </div>
        </div>

        <!-- Sample Data Preview -->
        <div style="margin-top: 20px; padding: 15px; background: #f1f3f4; border-radius: 8px;">
          <h4 style="margin: 0 0 10px 0; color: #333;">📝 Sample CSV Data:</h4>
          <pre style="background: white; padding: 10px; border-radius: 4px; overflow-x: auto; font-size: 12px; margin: 0;">course_code,course_name,department,department_name,description,sort_order
BSIT,Bachelor of Science in Information Technology,SITE,School of Information Technology and Engineering,IT program,1
BSN,Bachelor of Science in Nursing,SNAHS,School of Nursing and Allied Health Sciences,Nursing program,1</pre>
        </div>

        <!-- Navigation -->
        <div style="margin-top: 30px; text-align: center;">
          <a href="{{ route('dashboard') }}" class="responsive-btn responsive-btn-secondary">
            ← Back to Dashboard
          </a>
        </div>
      </div>
    </div>
  </div>

  <style>
    .alert {
      padding: 15px;
      margin-bottom: 20px;
      border-radius: 8px;
      border: 1px solid transparent;
    }

    .alert-success {
      background-color: #d4edda;
      border-color: #c3e6cb;
      color: #155724;
    }

    .alert-error {
      background-color: #f8d7da;
      border-color: #f5c6cb;
      color: #721c24;
    }

    .responsive-btn-secondary {
      background-color: #6c757d;
      color: white;
      border: none;
      padding: 12px 24px;
      border-radius: 6px;
      cursor: pointer;
      font-size: 14px;
      text-decoration: none;
      display: inline-block;
      transition: background-color 0.2s;
    }

    .responsive-btn-secondary:hover {
      background-color: #5a6268;
    }

    pre {
      white-space: pre-wrap;
      word-wrap: break-word;
    }

    @media (max-width: 768px) {
      pre {
        font-size: 10px;
      }
    }
  </style>
</x-dashboard-layout>
