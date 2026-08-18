<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quotation #{{ $quotation->id }} - Vso's Computer Repair System</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #2c3e50;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #f8fafc, #e2e8f0);
        }
        .container {
            max-width: 650px;
            margin: 20px auto;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #1e3a8a 0%, #3730a3 100%);
            color: white;
            padding: 30px;
            text-align: center;
            position: relative;
        }
        .header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 200px;
            height: 200px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 50%;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 300;
            letter-spacing: 1px;
            position: relative;
            z-index: 1;
        }
        .header .subtitle {
            font-size: 14px;
            opacity: 0.9;
            margin-top: 8px;
            position: relative;
            z-index: 1;
        }
        .content {
            padding: 30px;
        }
        .greeting {
            font-size: 18px;
            color: #1e293b;
            margin-bottom: 20px;
            font-weight: 500;
        }
        .intro {
            background: #f8fafc;
            border-left: 4px solid #3b82f6;
            padding: 20px;
            margin-bottom: 25px;
            border-radius: 8px;
            font-size: 14px;
            color: #475569;
        }
        .info-box {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 25px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.07);
        }
        .info-box h3 {
            font-size: 16px;
            color: #1e293b;
            margin: 0 0 20px 0;
            padding-bottom: 10px;
            border-bottom: 2px solid #f1f5f9;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }
        .info-item {
            margin-bottom: 10px;
        }
        .info-item strong {
            color: #1e293b;
            font-weight: 600;
            display: block;
            margin-bottom: 4px;
        }
        .info-item span {
            color: #475569;
            font-size: 14px;
        }
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .status-sent {
            background: linear-gradient(135deg, #dbeafe, #bfdbfe);
            color: #1e40af;
            border: 1px solid #60a5fa;
        }
        .status-accepted {
            background: linear-gradient(135deg, #d1fae5, #a7f3d0);
            color: #065f46;
            border: 1px solid #34d399;
        }
        .status-rejected {
            background: linear-gradient(135deg, #fee2e2, #fecaca);
            color: #991b1b;
            border: 1px solid #f87171;
        }
        .valid-until {
            background: linear-gradient(135deg, #fef3c7, #fde68a);
            border: 1px solid #f59e0b;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 25px;
            text-align: center;
            color: #92400e;
            font-weight: 500;
        }
        .cta-section {
            background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 25px;
            text-align: center;
        }
        .cta-button {
            display: inline-block;
            background: linear-gradient(135deg, #1e3a8a, #3730a3);
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
            transition: transform 0.2s ease;
        }
        .cta-button:hover {
            transform: translateY(-2px);
        }
        .footer {
            background: #f8fafc;
            padding: 25px;
            text-align: center;
            border-top: 1px solid #e2e8f0;
        }
        .footer p {
            margin: 5px 0;
            font-size: 12px;
            color: #64748b;
        }
        .company-signature {
            font-weight: 600;
            color: #1e293b;
            font-size: 14px;
        }
        @media (max-width: 600px) {
            .container {
                margin: 10px;
                border-radius: 8px;
            }
            .info-grid {
                grid-template-columns: 1fr;
            }
            .header {
                padding: 20px;
            }
            .header h1 {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Quotation #{{ $quotation->id }}</h1>
            <div class="subtitle">Professional Computer Repair Services</div>
        </div>

        <div class="content">
            @if($recipientType === 'client')
            <div class="greeting">Dear {{ $clientName }},</div>
            @else
            <div class="greeting">Dear Administrator,</div>
            @endif

            @if($recipientType === 'client')
            <div class="intro">
                Thank you for trusting Vso's Computer Repair System with your device repair needs. 
                We have carefully assessed your device and prepared a detailed quotation for the required services.
            </div>
            @else
            <div class="intro">
                A new quotation has been created and sent to the client. Please review the details below for your records.
            </div>
            @endif

            <div class="info-box">
                <h3>📋 Quotation Information</h3>
                <div class="info-grid">
                    <div class="info-item">
                        <strong>Quotation #:</strong>
                        <span>{{ $quotation->id }}</span>
                    </div>
                    <div class="info-item">
                        <strong>Job Number:</strong>
                        <span>{{ $quotation->job_number }}</span>
                    </div>
                    <div class="info-item">
                        <strong>Status:</strong>
                        <span class="status-badge status-{{ $quotation->status }}">{{ ucfirst($quotation->status) }}</span>
                    </div>
                    <div class="info-item">
                        <strong>Created:</strong>
                        <span>
                            @php
                                $createdAt = is_string($quotation->created_at) ? \Carbon\Carbon::parse($quotation->created_at) : $quotation->created_at;
                            @endphp
                            {{ $createdAt->format('M d, Y H:i') }}
                        </span>
                    </div>
                </div>
            </div>

            @if($recipientType === 'client')
            <div class="valid-until">
                <strong>⏰ Important: This quotation is valid until 
                    @php
                        $validUntil = is_string($quotation->valid_until) ? \Carbon\Carbon::parse($quotation->valid_until) : $quotation->valid_until;
                    @endphp
                    {{ $validUntil->format('M d, Y') }}
                </strong><br>
                Please accept before this date to lock in these prices and ensure prompt service.
            </div>
            @endif

            <div class="info-box">
                <h3>💰 Financial Summary</h3>
                <div class="info-grid">
                    <div class="info-item">
                        <strong>Subtotal:</strong>
                        <span>ZMW{{ number_format($quotation->subtotal, 2) }}</span>
                    </div>
                    <div class="info-item">
                        <strong>Tax (16%):</strong>
                        <span>ZMW{{ number_format($quotation->tax, 2) }}</span>
                    </div>
                    <div class="info-item">
                        <strong>Discount:</strong>
                        <span>ZMW{{ number_format($quotation->discount, 2) }}</span>
                    </div>
                    <div class="info-item">
                        <strong>Total Amount:</strong>
                        <span style="font-weight: 700; color: #1e3a8a; font-size: 16px;">ZMW{{ number_format($quotation->total_amount, 2) }}</span>
                    </div>
                </div>
            </div>

            @if($recipientType === 'client')
            <div class="cta-section">
                <h3 style="margin-top: 0; color: #1e293b;">Next Steps</h3>
                <p style="margin-bottom: 20px; color: #475569;">
                    Review the detailed quotation in the attached PDF and let us know if you have any questions. 
                    We're here to help with any modifications you may need.
                </p>
                <p style="margin-bottom: 20px; color: #475569;">
                    To proceed with the repair, simply reply to this email or call us at the number below.
                </p>
                <a href="tel:+260XXXXXXXXXX" class="cta-button">Contact Us Now</a>
            </div>
            @endif
        </div>

        <div class="footer">
            <p class="company-signature">Vso's Computer Repair System</p>
            <p>Professional IT Services • Quality Assurance • Customer Satisfaction</p>
            <p>📍 Zambia | 📧 chundamaviyeso@gmail.com | 📞 +260 XXX XXX XXX</p>
            <p>Generated on {{ now()->format('M d, Y H:i') }} • © {{ date('Y') }} Vso's Computer Repair System</p>
        </div>
    </div>
</body>
</html>
