<!DOCTYPE html>
<html>

<head>
    <title>Test Email from BAC System</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .content {
            background-color: #ffffff;
            padding: 20px;
            border: 1px solid #dee2e6;
            border-radius: 5px;
        }

        .footer {
            margin-top: 20px;
            font-size: 12px;
            color: #6c757d;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1 style="color: #0d6efd; margin: 0;">BAC System</h1>
        <p style="margin: 5px 0 0;">Bids and Awards Committee</p>
    </div>

    <div class="content">
        <h2>Test Email</h2>
        <p>This is a test email to verify that our email notification system is working correctly.</p>
        <p>When implemented, this system will send notifications for:</p>
        <ul>
            <li>Purchase Request status updates</li>
            <li>New Purchase Request submissions</li>
            <li>Important deadlines</li>
            <li>System notifications</li>
        </ul>
        <p><strong>Time sent:</strong> {{ now()->format('F j, Y - g:i A') }}</p>
    </div>

    <div class="footer">
        <p>This is an automated message from the BAC System. Please do not reply to this email.</p>
        <p>© {{ date('Y') }} BAC System. All rights reserved.</p>
    </div>
</body>

</html>