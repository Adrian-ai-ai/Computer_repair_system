<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quotation #{{ $quotation->id }}</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            border-bottom: 3px solid #2563eb;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #2563eb;
            margin: 0;
            font-size: 28px;
        }
        .header p {
            color: #666;
            margin: 5px 0 0 0;
            font-size: 14px;
        }
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 30px;
        }
        .info-box {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid #2563eb;
        }
        .info-box h3 {
            margin: 0 0 10px 0;
            color: #2563eb;
            font-size: 16px;
        }
        .info-box p {
            margin: 5px 0;
            font-size: 14px;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .items-table th {
            background: #2563eb;
            color: white;
            padding: 12px;
            text-align: left;
            font-weight: 600;
        }
        
        .items-table tbody tr {
            background: white;
            border-bottom: 1px solid #f1f5f9;
            transition: background-color 0.2s ease;
        }
        
        .items-table tbody tr:nth-child(even) {
            background: #f8fafc;
        }
        
        .items-table tbody tr:hover {
            background: #f1f5f9;
        }
        
        .items-table td {
            padding: 15px;
            font-size: 12px;
            color: #475569;
        }
        
        .summary {
            background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
            border: 1px solid #cbd5e1;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.07);
        }
        
        .summary h4 {
            font-size: 18px;
            color: #1e293b;
            margin: 0 0 20px 0;
            font-weight: 600;
        }
        
        .fault-description-box {
            background: #fef3c7;
            border: 1px solid #f59e0b;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }
        
        .fault-description-box p {
            margin: 0;
            color: #92400e;
            font-size: 14px;
            line-height: 1.6;
        }
        
        .summary-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #cbd5e1;
            font-size: 14px;
        }
        
        .summary-row:last-child {
            border-bottom: none;
        }
        
        .summary-row.total {
            background: linear-gradient(135deg, #1e3a8a, #3730a3);
            color: white;
            padding: 15px 20px;
            border-radius: 8px;
            margin-top: 10px;
            font-size: 16px;
            font-weight: 700;
        }
        
        .footer {
            text-align: center;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #e2e8f0;
            color: #64748b;
            font-size: 11px;
        }
        
        .footer p {
            margin: 5px 0;
        }
        
        .terms {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 20px;
            margin-top: 20px;
        }
        
        .terms h5 {
            font-size: 14px;
            color: #1e293b;
            margin: 0 0 10px 0;
            font-weight: 600;
        }
        
        .terms ul {
            margin: 10px 0;
            padding-left: 20px;
            font-size: 11px;
            color: #475569;
        }
        
        .terms li {
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Repair Quotation</h1>
            <div class="subtitle">Professional Computer Repair Services</div>
        </div>

        <div class="company-info">
            <h2>Vso's Computer Repair System</h2>
            <div class="company-details">
                <div>
                    <p><strong>Business:</strong> Professional Computer Repair & IT Services</p>
                    <p><strong>Location:</strong> Zambia</p>
                    <p><strong>Email:</strong> chundamaviyeso@gmail.com</p>
                </div>
                <div>
                    <p><strong>Phone:</strong> +260 XXX XXX XXX</p>
                    <p><strong>License:</strong> Registered IT Service Provider</p>
                    <p><strong>Website:</strong> www.vsocomputerrepair.com</p>
                </div>
            </div>
        </div>

        <div class="info-grid">
            <div class="info-box">
                <h3>Job Information</h3>
                <p><strong>Job Number:</strong> {{ $quotation->job_number }}</p>
                <p><strong>Status:</strong> <span class="status-badge status-{{ $quotation->status }}">{{ ucfirst($quotation->status) }}</span></p>
                <p><strong>Created:</strong> 
                @php
                    $jobCreatedAt = is_string($quotation->created_at) ? \Carbon\Carbon::parse($quotation->created_at) : $quotation->created_at;
                @endphp
                {{ $jobCreatedAt->format('M d, Y H:i') }}
            </p>
            </div>
            <div class="info-box">
                <h3>Client Information</h3>
                <p><strong>Name:</strong> {{ $clientName }}</p>
                <p><strong>Email:</strong> {{ $quotation->client_email ?? 'N/A' }}</p>
                <p><strong>Phone:</strong> {{ $jobData->client_phone ?? 'N/A' }}</p>
            </div>
            @if($jobData)
            <div class="info-box">
                <h3>Device Information</h3>
                <p><strong>Device:</strong> {{ $jobData->device_type ?? 'N/A' }} - {{ $jobData->brand ?? 'N/A' }} {{ $jobData->model ?? '' }}</p>
                <p><strong>Serial Number:</strong> {{ $jobData->serial_number ?? 'N/A' }}</p>
            </div>
            @endif
        </div>

        <div class="quotation-info">
            <div class="quotation-header">
                <div class="quotation-number">Quotation #{{ $quotation->id }}</div>
                <div class="quotation-date">
                    @php
                        $quotationDate = is_string($quotation->created_at) ? \Carbon\Carbon::parse($quotation->created_at) : $quotation->created_at;
                    @endphp
                    {{ $quotationDate->format('M d, Y') }}
                </div>
            </div>
            @if($quotation->valid_until)
                <div class="quotation-validity">
                    <strong>Valid Until:</strong>
                    @php
                        $validUntil = is_string($quotation->valid_until) ? \Carbon\Carbon::parse($quotation->valid_until) : $quotation->valid_until;
                    @endphp
                    {{ $validUntil->format('M d, Y') }}
                </div>
            @endif
            <div class="quotation-status">
                <strong>Status:</strong> <span class="status-badge status-{{ $quotation->status }}">{{ ucfirst($quotation->status) }}</span>
            </div>
        </div>

        @if($jobData && $jobData->fault_description)
        <div class="items-section">
            <h4>Fault Description</h4>
            <div class="fault-description-box">
                <p>{{ $jobData->fault_description }}</p>
            </div>
        </div>
        @endif

        <div class="items-section">
            <h4>Items & Services</h4>
            <table class="items-table">
                <thead>
                    <tr>
                        <th style="width: 50%;">Description</th>
                        <th style="width: 15%;">Quantity</th>
                        <th style="width: 17%;">Unit Price</th>
                        <th style="width: 18%;">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $item)
                    <tr>
                        <td>{{ $item->description }}</td>
                        <td style="text-align: center;">{{ $item->quantity }}</td>
                        <td style="text-align: right;">
                            @if($item->unit_price > 0)
                                ZMW{{ number_format($item->unit_price, 2) }}
                            @else
                                <span style="color: #6b7280; font-style: italic;">To be determined</span>
                            @endif
                        </td>
                        <td style="text-align: right; font-weight: 600;">
                            @if($item->total > 0)
                                ZMW{{ number_format($item->total, 2) }}
                            @else
                                <span style="color: #6b7280; font-style: italic;">To be determined</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            
            @if(count($items) === 0)
            <div style="text-align: center; padding: 40px; color: #6b7280; font-style: italic;">
                No items have been added to this quotation yet.
            </div>
            @endif
        </div>

        @if($quotation->total_amount > 0 || in_array($recipientType, ['admin_request', 'admin', 'staff']))
        <div class="summary">
            <h4>Financial Summary</h4>
            <div class="summary-row">
                <span>Subtotal:</span>
                <span>@if($quotation->subtotal > 0) ZMW{{ number_format($quotation->subtotal, 2) }} @else <span style="color: #6b7280; font-style: italic;">To be determined</span> @endif</span>
            </div>
            <div class="summary-row">
                <span>Tax (16%):</span>
                <span>@if($quotation->tax > 0) ZMW{{ number_format($quotation->tax, 2) }} @else <span style="color: #6b7280; font-style: italic;">To be determined</span> @endif</span>
            </div>
            <div class="summary-row">
                <span>Discount:</span>
                <span>@if($quotation->discount > 0) ZMW{{ number_format($quotation->discount, 2) }} @else ZMW0.00 @endif</span>
            </div>
<<<<<<< HEAD
            <div class="summary-row total">
                <span>Total Amount:</span>
=======
            <div class="summary-row" style="border-top: 2px solid #cbd5e1; margin-top: 10px; padding-top: 15px;">
                <span style="font-weight: 600;">Grand Total:</span>
                <span style="font-weight: 600;">
                    @if($quotation->total_amount > 0) 
                        ZMW{{ number_format($quotation->subtotal + $quotation->tax - $quotation->discount, 2) }}
                    @else 
                        <span style="color: #6b7280; font-style: italic;">To be determined</span>
                    @endif
                </span>
            </div>
            <div class="summary-row total">
                <span>Final Amount Due:</span>
>>>>>>> 814c0e3a080a93b0a4c40958610f5493345a9fd8
                <span>@if($quotation->total_amount > 0) ZMW{{ number_format($quotation->total_amount, 2) }} @else <span style="color: #6b7280; font-style: italic;">To be determined</span> @endif</span>
            </div>
        </div>
        @endif
        
        @if($quotation->total_amount == 0)
        <div style="margin: 20px 0; padding: 15px; background: #fef3c7; border: 1px solid #f59e0b; border-radius: 8px; text-align: center;">
            <p style="margin: 0; color: #92400e; font-size: 14px;">
                <strong>Important Note:</strong> This is a technician quotation request. Pricing will be determined by administration.
            </p>
        </div>
        @endif

        @if($recipientType === 'client')
        <div class="terms">
            <h5>Terms & Conditions</h5>
            <ul>
                <li>This quotation is valid for 14 days from the issue date</li>
                <li>Prices include all parts and labor as specified</li>
                <li>Additional charges may apply for unforeseen complications</li>
                <li>50% deposit required before commencement of work</li>
                <li>Balance payment due upon completion of service</li>
                <li>30-day warranty on all repairs and replacement parts</li>
                <li>This quotation is subject to availability of parts</li>
            </ul>
        </div>
        @endif

        <div class="footer">
            <p><strong>Thank you for choosing Vso's Computer Repair System</strong></p>
            <p>Professional IT Services • Quality Assurance • Customer Satisfaction</p>
            <p>Generated on {{ now()->format('M d, Y H:i') }} • Page 1 of 1</p>
            <p>This quotation was generated from the Inventory Repair System.<br>
            For questions, please contact our service department.</p>
            <p>© {{ date('Y') }} Inventory Repair System. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
