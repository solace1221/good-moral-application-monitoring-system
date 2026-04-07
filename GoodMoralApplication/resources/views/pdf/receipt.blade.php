<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://db.onlinewebfonts.com/c/a0f33b8a3febb69aa498dba7d9e8cab2?family=Old+English+Text+MT+Std" rel="stylesheet">
    <title>Payment Receipt - {{ $receipt_number }}</title>
    <style>
        body {
            font-family: 'Times New Roman', serif;
            margin: 0;
            padding: 20px;
            background: white;
            color: #333;
            line-height: 1.4;
        }
        
        .receipt-container {
            max-width: 600px;
            margin: 0 auto;
            border: 2px solid #333;
            padding: 30px;
            background: white;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }
        
        .university-name {
            font-family: 'Old English Text MT Std', serif;
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 5px;
            color: #333;
        }

        .university-address {
            font-size: 12px;
            margin-bottom: 5px;
            color: #333;
            font-weight: normal;
        }

        .university-contact {
            font-size: 12px;
            margin-bottom: 3px;
            color: #333;
        }

        .university-website {
            font-size: 12px;
            margin-bottom: 15px;
            color: #0066cc;
            text-decoration: none;
        }
        
        .receipt-title {
            font-size: 24px;
            font-weight: bold;
            color: #333;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        
        .receipt-number {
            font-size: 14px;
            color: #666;
            font-weight: bold;
        }
        
        .receipt-details {
            margin: 30px 0;
        }
        
        .detail-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
            padding: 8px 0;
            border-bottom: 1px dotted #ccc;
        }
        
        .detail-label {
            font-weight: bold;
            color: #333;
            width: 40%;
        }
        
        .detail-value {
            color: #666;
            width: 55%;
            text-align: right;
        }
        
        .payment-breakdown {
            background: #f8f9fa;
            padding: 20px;
            border: 1px solid #dee2e6;
            margin: 20px 0;
            border-radius: 5px;
        }
        
        .breakdown-title {
            font-weight: bold;
            margin-bottom: 15px;
            color: #333;
            text-align: center;
            font-size: 16px;
        }
        
        .breakdown-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            padding: 5px 0;
        }
        
        .total-amount {
            background: #28a745;
            color: white;
            padding: 15px;
            text-align: center;
            margin: 20px 0;
            border-radius: 5px;
        }
        
        .total-label {
            font-size: 16px;
            margin-bottom: 5px;
        }
        
        .total-value {
            font-size: 24px;
            font-weight: bold;
        }
        
        .footer {
            margin-top: 40px;
            text-align: center;
            border-top: 2px solid #333;
            padding-top: 20px;
        }
        
        .footer-note {
            font-size: 12px;
            color: #666;
            margin-bottom: 10px;
        }
        
        .signature-section {
            margin-top: 30px;
            text-align: right;
        }
        
        .signature-line {
            border-bottom: 1px solid #333;
            width: 200px;
            margin: 20px 0 5px auto;
        }
        
        .signature-label {
            font-size: 12px;
            color: #666;
        }
        
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 60px;
            color: rgba(220, 53, 69, 0.2);
            font-weight: bold;
            z-index: -1;
            pointer-events: none;
        }

        .logo-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding: 0 20px;
        }

        .logo-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .logo-container img {
            width: 60px;
            height: 60px;
            margin-bottom: 8px;
        }

        .logo-text {
            font-size: 10px;
            font-weight: bold;
            color: #333;
        }

        .spup-text {
            font-family: 'Old English Text MT Std', serif;
            font-size: 14px;
            color: #333;
            font-weight: bold;
        }

        .osa-text {
            font-size: 8px;
            color: #666;
            margin-top: 2px;
        }
    </style>
</head>
<body>
    <div class="watermark">NOT PAID</div>
    
    <div class="receipt-container">
        <div class="header">
            <!-- Logo Header with SPUP and OSA logos -->
            <div class="logo-header">
                <div class="logo-container">
                    @php
                        $spupLogo = file_exists(public_path('images/backgrounds/spup-logo.png')) ?
                                   base64_encode(file_get_contents(public_path('images/backgrounds/spup-logo.png'))) : null;
                    @endphp
                    @if($spupLogo)
                        <img src="data:image/png;base64,{{ $spupLogo }}" alt="SPUP Logo" />
                    @endif
                    <div class="logo-text spup-text">SPUP</div>
                    <div class="osa-text">St. Paul University Philippines</div>
                </div>

                <div class="logo-container">
                    @php
                        $osaLogo = file_exists(public_path('images/osa-logo.png')) ?
                                  base64_encode(file_get_contents(public_path('images/osa-logo.png'))) : null;
                    @endphp
                    @if($osaLogo)
                        <img src="data:image/png;base64,{{ $osaLogo }}" alt="OSA Logo" />
                    @endif
                    <div class="logo-text">OSA</div>
                    <div class="osa-text">Office of Student Affairs</div>
                </div>
            </div>

            <div class="university-name">St. Paul University Philippines</div>
            <div class="university-address">Tuguegarao City, Cagayan 3500</div>
            <div class="university-contact">Tel: 396-1987-1994</div>
            <div class="university-contact">Fax: 078-8464305</div>
            <div class="university-website">www.spup.edu.ph</div>
            <div class="university-address" style="margin-top: 10px;">Office of Student Affairs</div>
            <div class="receipt-title">Payment Notice</div>
            <div class="receipt-number">Receipt No: {{ $receipt_number }}</div>
        </div>
        
        <div class="receipt-details">
            <div class="detail-row">
                <span class="detail-label">Student Name:</span>
                <span class="detail-value">{{ $application->fullname }}</span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">Student ID:</span>
                <span class="detail-value">{{ $application->student_id }}</span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">Department:</span>
                <span class="detail-value">{{ $application->department }}</span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">Reference Number:</span>
                <span class="detail-value">{{ $application->reference_number }}</span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">Certificate Type:</span>
                <span class="detail-value">
                    {{ $application->certificate_type === 'good_moral' ? 'Good Moral Certificate' : 'Certificate of Residency' }}
                </span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">Purpose:</span>
                <span class="detail-value">{{ $application->formatted_reasons }}</span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">Date Issued:</span>
                <span class="detail-value">{{ $date_issued->format('F j, Y g:i A') }}</span>
            </div>
        </div>
        
        <div class="payment-breakdown">
            <div class="breakdown-title">Payment Breakdown</div>
            
            <div class="breakdown-row">
                <span>Number of Reasons:</span>
                <span>{{ $reasons_count }}</span>
            </div>
            
            <div class="breakdown-row">
                <span>Number of Copies:</span>
                <span>{{ $copies_count }}</span>
            </div>
            
            <div class="breakdown-row">
                <span>Rate per Unit:</span>
                <span>₱50.00</span>
            </div>
            
            <div class="breakdown-row" style="border-top: 1px solid #333; padding-top: 10px; margin-top: 10px;">
                <span><strong>Calculation:</strong></span>
                <span><strong>{{ $reasons_count }} × {{ $copies_count }} × ₱50.00</strong></span>
            </div>
        </div>
        
        <div class="total-amount" style="background: #dc3545;">
            <div class="total-label">Total Amount Due</div>
            <div class="total-value">₱{{ number_format($payment_amount, 2) }}</div>
        </div>
        
        <div class="signature-section">
            <div class="signature-line"></div>
            <div class="signature-label">Authorized Signature</div>
        </div>
        
        <div class="footer">
            <div class="footer-note">
                This is a system-generated payment notice issued upon approval of your certificate application.<br>
                Please proceed to the <strong>Business Affairs Office</strong> to make payment and upload your official receipt.
            </div>
            <div class="footer-note">
                <strong>Status:</strong> PAYMENT PENDING | <strong>Generated:</strong> {{ now()->format('F j, Y g:i A') }}
            </div>
        </div>
    </div>
</body>
</html>
