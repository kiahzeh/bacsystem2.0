<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase Request - {{ $purchaseRequest->pr_number }}</title>
    <style>
        @page {
            margin: 0.75in;
            size: A4;
        }
        
        body {
            font-family: 'Times New Roman', serif;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 15px;
            font-size: 12px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
        }
        
        .header h1 {
            font-size: 20px;
            font-weight: bold;
            margin: 0;
            color: #1a365d;
        }
        
        .header p {
            font-size: 12px;
            margin: 3px 0;
            color: #666;
        }
        
        .document-title {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            margin: 20px 0;
            text-transform: uppercase;
            color: #1a365d;
        }
        
        .info-section {
            margin-bottom: 15px;
        }
        
        .info-section h3 {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 8px;
            color: #1a365d;
            border-bottom: 1px solid #ddd;
            padding-bottom: 3px;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }
        
        .info-item {
            margin-bottom: 8px;
        }
        
        .info-label {
            font-weight: bold;
            color: #555;
            margin-bottom: 2px;
            font-size: 11px;
        }
        
        .info-value {
            color: #333;
            padding: 2px 0;
            font-size: 11px;
        }
        
        .status-badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .status-alternative {
            background-color: #dbeafe;
            color: #1e40af;
        }
        
        .status-competitive {
            background-color: #dcfce7;
            color: #166534;
        }
        
        .status-others {
            background-color: #f3f4f6;
            color: #374151;
        }
        
        .description-section {
            margin-top: 15px;
        }
        
        .description-box {
            border: 1px solid #ddd;
            padding: 10px;
            background-color: #f9f9f9;
            border-radius: 3px;
            min-height: 60px;
            font-size: 11px;
        }
        
        .footer {
            margin-top: 25px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        
        .signature-section {
            margin-top: 30px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
        }
        
        .signature-box {
            text-align: center;
        }
        
        .signature-line {
            border-top: 1px solid #333;
            margin-top: 25px;
            padding-top: 3px;
        }
        
        .document-number {
            position: absolute;
            top: 15px;
            right: 15px;
            font-size: 10px;
            color: #666;
        }
        
        .date-printed {
            position: absolute;
            bottom: 15px;
            left: 15px;
            font-size: 9px;
            color: #999;
        }
        
        .budget-highlight {
            background-color: #fff3cd;
            padding: 5px 8px;
            border-radius: 3px;
            border-left: 3px solid #ffc107;
            font-weight: bold;
        }
        
        @media print {
            body {
                -webkit-print-color-adjust: exact;
                color-adjust: exact;
            }
        }
    </style>
</head>
<body>
    <div class="document-number">
        PR-{{ $purchaseRequest->pr_number }}
    </div>
    
    <div class="header">
        <h1>BUKIDNON STATE UNIVERSITY</h1>
        <p>Republic of the Philippines</p>
        <p>Malaybalay City, Bukidnon</p>
        <p>Office of the Vice President for Administration and Finance</p>
    </div>
    
    <div class="document-title">
        Purchase Request Details
    </div>
    
    <div class="info-section">
        <h3>Basic Information</h3>
        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">PR Number:</div>
                <div class="info-value">{{ $purchaseRequest->pr_number }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Project Title:</div>
                <div class="info-value">{{ $purchaseRequest->project_title ?? 'N/A' }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Requestor Name:</div>
                <div class="info-value">{{ $purchaseRequest->name }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Order Date:</div>
                <div class="info-value">{{ $purchaseRequest->order_date->format('F j, Y') }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Department/Office:</div>
                <div class="info-value">{{ $purchaseRequest->department->name }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Category:</div>
                <div class="info-value">{{ $purchaseRequest->category ?? 'N/A' }}</div>
            </div>
        </div>
    </div>
    
    <div class="info-section">
        <h3>Procurement Details</h3>
        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">Mode of Procurement:</div>
                <div class="info-value">
                    <span class="status-badge 
                        @if($purchaseRequest->mode_of_procurement == 'Alternative') status-alternative
                        @elseif($purchaseRequest->mode_of_procurement == 'Competitive') status-competitive
                        @else status-others @endif">
                        {{ $purchaseRequest->mode_of_procurement }}
                    </span>
                </div>
            </div>
            <div class="info-item">
                <div class="info-label">Current Status:</div>
                <div class="info-value">{{ $purchaseRequest->status }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">ABC/Approved Budget:</div>
                <div class="info-value budget-highlight">₱{{ number_format($purchaseRequest->abc_approved_budget ?? 0, 2) }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Funding Source:</div>
                <div class="info-value">{{ $purchaseRequest->funding ?? 'N/A' }}</div>
            </div>
        </div>
    </div>
    
    @if($purchaseRequest->purpose_description)
    <div class="description-section">
        <h3>Purpose/Description</h3>
        <div class="description-box">
            {{ $purchaseRequest->purpose_description }}
        </div>
    </div>
    @endif
    
    @if($purchaseRequest->remarks)
    <div class="description-section">
        <h3>Remarks</h3>
        <div class="description-box">
            {{ $purchaseRequest->remarks }}
        </div>
    </div>
    @endif
    
    <div class="signature-section">
        <div class="signature-box">
            <div class="signature-line"></div>
            <p style="font-size: 10px; margin: 5px 0;"><strong>Requestor's Signature</strong></p>
            <p style="font-size: 10px; margin: 0;">{{ $purchaseRequest->name }}</p>
            <p style="font-size: 9px; margin: 0; color: #666;">Requestor</p>
        </div>
        <div class="signature-box">
            <div class="signature-line"></div>
            <p style="font-size: 10px; margin: 5px 0;"><strong>Department Head</strong></p>
            <p style="font-size: 10px; margin: 0;">{{ $purchaseRequest->department->name }}</p>
            <p style="font-size: 9px; margin: 0; color: #666;">Department Head</p>
        </div>
    </div>
    
    <div class="footer">
        <p>This document was generated by the Procurement Monitoring System</p>
        <p>Bukidnon State University - Malaybalay City, Bukidnon</p>
        <p>Document ID: PR-{{ $purchaseRequest->pr_number }}-{{ date('Ymd') }}</p>
    </div>
    
    <div class="date-printed">
        Printed on: {{ now()->format('F j, Y - g:i A') }}
    </div>
</body>
</html> 