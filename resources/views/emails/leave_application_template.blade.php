<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Leave Application Request</title>
</head>
<body>
    <p>Dear {{ $details['manager_name'] }},</p>

    <p>I hope this email finds you well.</p>

    <p>I am writing to formally request leave from <strong>{{ $details['start_date'] }}</strong> to <strong>{{ $details['end_date'] }}</strong> due to {{ $details['leave_reason'] }}.</p>

    <p>Below are the details of my leave request:</p>
    <ul>
        <li><strong>Employee Name:</strong> {{ $details['employee_name'] }}</li>
        <li><strong>Employee ID:</strong> {{ $details['employeeID'] }}</li>
        <li><strong>Leave Type:</strong> {{ $details['leave_type'] }}</li>
        <li><strong>Number of Days:</strong> {{ $details['days_count'] }}</li>
    </ul>

    <p>I have ensured that my pending work is managed in my absence. Kindly let me know if you need any further information regarding my leave request.</p>

    <p>Looking forward to your approval.</p>

    <p>Best Regards,<br>
    {{ $details['employee_name'] }}<br>
    {{ $details['employee_email'] }}</p>
</body>
</html>
