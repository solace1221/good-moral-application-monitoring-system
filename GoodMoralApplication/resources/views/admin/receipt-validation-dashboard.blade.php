<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt Validation Dashboard</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .header {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .header h1 {
            color: #333;
            margin-bottom: 10px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .stat-number {
            font-size: 2.5em;
            font-weight: bold;
            color: #667eea;
            margin-bottom: 10px;
        }

        .stat-label {
            color: #666;
            font-size: 1.1em;
        }

        .content-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 30px;
        }

        .card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .card h3 {
            color: #333;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #f0f0f0;
        }

        .config-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .config-item:last-child {
            border-bottom: none;
        }

        .config-label {
            font-weight: 600;
            color: #555;
        }

        .config-value {
            color: #667eea;
            font-weight: bold;
        }

        .recent-uploads {
            grid-column: 1 / -1;
        }

        .uploads-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        .uploads-table th,
        .uploads-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #f0f0f0;
        }

        .uploads-table th {
            background: #f8f9fa;
            font-weight: 600;
            color: #555;
        }

        .status-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.85em;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-uploaded {
            background: #d4edda;
            color: #155724;
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
        }

        .test-section {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .file-input {
            width: 100%;
            padding: 12px;
            border: 2px dashed #667eea;
            border-radius: 8px;
            background: #f8f9ff;
            margin: 15px 0;
            text-align: center;
            cursor: pointer;
        }

        .btn {
            background: #667eea;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            transition: background 0.3s ease;
        }

        .btn:hover {
            background: #5a6fd8;
        }

        .alert {
            padding: 15px;
            border-radius: 8px;
            margin: 15px 0;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-danger {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        @media (max-width: 768px) {
            .content-grid {
                grid-template-columns: 1fr;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Receipt Validation Dashboard</h1>
            <p>Monitor and manage receipt upload validation system</p>
        </div>

        <!-- Statistics Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number">{{ $totalUploads }}</div>
                <div class="stat-label">Total Uploads</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ $todayUploads }}</div>
                <div class="stat-label">Today's Uploads</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ $weeklyUploads }}</div>
                <div class="stat-label">This Week</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ $monthlyUploads }}</div>
                <div class="stat-label">This Month</div>
            </div>
        </div>

        <!-- Content Grid -->
        <div class="content-grid">
            <!-- Validation Configuration -->
            <div class="card">
                <h3>Validation Configuration</h3>
                <div class="config-item">
                    <span class="config-label">Required Score Minimum</span>
                    <span class="config-value">{{ $validationConfig['thresholds']['required_score_minimum'] ?? 50 }}%</span>
                </div>
                <div class="config-item">
                    <span class="config-label">Important Score Minimum</span>
                    <span class="config-value">{{ $validationConfig['thresholds']['important_score_minimum'] ?? 40 }}%</span>
                </div>
                <div class="config-item">
                    <span class="config-label">Min Image Width</span>
                    <span class="config-value">{{ $validationConfig['thresholds']['minimum_image_width'] ?? 200 }}px</span>
                </div>
                <div class="config-item">
                    <span class="config-label">Min Image Height</span>
                    <span class="config-value">{{ $validationConfig['thresholds']['minimum_image_height'] ?? 200 }}px</span>
                </div>
                <div class="config-item">
                    <span class="config-label">Required Patterns</span>
                    <span class="config-value">{{ count($validationConfig['required_patterns'] ?? []) }}</span>
                </div>
                <div class="config-item">
                    <span class="config-label">Suspicious Patterns</span>
                    <span class="config-value">{{ count($validationConfig['suspicious_filename_patterns'] ?? []) }}</span>
                </div>
            </div>

            <!-- Test Validation -->
            <div class="card">
                <h3>Test Receipt Validation</h3>
                <p style="color: #666; margin-bottom: 15px;">Upload a test file to see how the validation system responds</p>
                
                <form id="testForm" enctype="multipart/form-data">
                    @csrf
                    <input type="file" name="test_file" class="file-input" accept=".pdf,.jpg,.jpeg,.png" required>
                    <button type="submit" class="btn">Test Validation</button>
                </form>
                
                <div id="testResult" style="margin-top: 15px;"></div>
            </div>
        </div>

        <!-- Recent Uploads -->
        <div class="card recent-uploads">
            <h3>Recent Receipt Uploads</h3>
            <table class="uploads-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Reference Number</th>
                        <th>Official Receipt No</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Student ID</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentUploads as $upload)
                    <tr>
                        <td>{{ $upload->updated_at->format('M j, Y g:i A') }}</td>
                        <td>{{ $upload->reference_num }}</td>
                        <td>{{ $upload->official_receipt_no ?? 'N/A' }}</td>
                        <td>₱{{ number_format($upload->amount ?? 0, 2) }}</td>
                        <td>
                            <span class="status-badge status-{{ $upload->status }}">
                                {{ ucfirst(str_replace('_', ' ', $upload->status)) }}
                            </span>
                        </td>
                        <td>{{ $upload->student_id ?? 'N/A' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="text-align: center; color: #666; padding: 30px;">
                            No recent uploads found
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <script>
        document.getElementById('testForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const resultDiv = document.getElementById('testResult');
            
            resultDiv.innerHTML = '<div style="color: #666;">Testing validation...</div>';
            
            try {
                const response = await fetch('/admin/receipt-validation/test', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                    }
                });
                
                const data = await response.json();
                
                if (data.success) {
                    const result = data.validation_result;
                    const alertClass = result.is_valid ? 'alert-success' : 'alert-danger';
                    const status = result.is_valid ? 'VALID' : 'REJECTED';
                    
                    resultDiv.innerHTML = `
                        <div class="alert ${alertClass}">
                            <strong>Result: ${status}</strong><br>
                            ${result.error_message || 'Receipt validation passed successfully!'}
                            ${result.confidence_score ? `<br><small>Confidence Score: ${result.confidence_score.toFixed(1)}%</small>` : ''}
                        </div>
                    `;
                } else {
                    resultDiv.innerHTML = '<div class="alert alert-danger">Test failed. Please try again.</div>';
                }
            } catch (error) {
                resultDiv.innerHTML = '<div class="alert alert-danger">Error testing validation. Please try again.</div>';
            }
        });
    </script>
</body>
</html>
