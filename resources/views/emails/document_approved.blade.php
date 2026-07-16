<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document Approved</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background-color: #f4f6f8;
            margin: 0;
            padding: 0;
            -webkit-font-smoothing: antialiased;
        }
        .wrapper {
            width: 100%;
            background-color: #f4f6f8;
            padding: 40px 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            border: 1px solid #eef2f5;
        }
        .header {
            background-color: #10b981;
            padding: 30px;
            text-align: center;
            border-bottom: 3px solid #059669;
        }
        .header h1 {
            color: #ffffff;
            margin: 0;
            font-size: 20px;
            font-weight: 700;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }
        .content {
            padding: 40px 30px;
        }
        .greeting {
            font-size: 16px;
            color: #333333;
            font-weight: bold;
            margin-top: 0;
            margin-bottom: 15px;
        }
        .intro {
            font-size: 14px;
            color: #555555;
            line-height: 1.6;
            margin-top: 0;
            margin-bottom: 25px;
        }
        .details-card {
            background-color: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 30px;
        }
        .details-title {
            font-size: 13px;
            font-weight: bold;
            color: #065f46;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 0;
            margin-bottom: 15px;
            border-bottom: 1px solid #bbf7d0;
            padding-bottom: 8px;
        }
        .detail-row {
            margin-bottom: 12px;
            font-size: 14px;
        }
        .detail-row:last-child {
            margin-bottom: 0;
        }
        .detail-label {
            font-weight: bold;
            color: #047857;
            display: inline-block;
            width: 130px;
        }
        .detail-value {
            color: #065f46;
            font-weight: 500;
        }
        .status-pill {
            display: inline-block;
            padding: 2px 8px;
            font-size: 12px;
            font-weight: bold;
            border-radius: 12px;
            background-color: #d1fae5;
            color: #065f46;
            border: 1px solid #a7f3d0;
            text-transform: uppercase;
        }
        .cta-container {
            text-align: center;
            margin-bottom: 10px;
        }
        .btn {
            display: inline-block;
            background-color: #10b981;
            color: #ffffff !important;
            text-decoration: none;
            padding: 12px 30px;
            font-size: 14px;
            font-weight: bold;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(16, 185, 129, 0.2);
        }
        .footer {
            background-color: #f8fafc;
            padding: 20px 30px;
            text-align: center;
            font-size: 12px;
            color: #a0aec0;
            border-top: 1px solid #edf2f7;
        }
        .footer p {
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container">
            <div class="header">
                <h1>Document Approved</h1>
            </div>
            <div class="content">
                <p class="greeting">Dear {{ $document->user->name }},</p>
                <p class="intro">Great news! Your uploaded document has been reviewed and successfully approved by the compliance team. Below are the details:</p>
                
                <div class="details-card">
                    <div class="details-title">Document Information</div>
                    <div class="detail-row">
                        <span class="detail-label">Document:</span>
                        <span class="detail-value">{{ \App\Models\Document::getDocumentName($document->document_number) }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Status:</span>
                        <span class="status-pill">Approved</span>
                    </div>
                </div>

                <p class="intro">Thank you for submitting your document and cooperating with our compliance procedures.</p>

                <div class="cta-container">
                    <a href="{{ $statusUrl }}" class="btn">View Document Status</a>
                </div>
            </div>
            <div class="footer">
                <p>Triumph Residential &bull; Compliance Management System</p>
                <p>Do not reply to this automated email notification.</p>
            </div>
        </div>
    </div>
</body>
</html>