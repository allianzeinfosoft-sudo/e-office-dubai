<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Your Leave Application Is Rejected</title>
</head>
<body>
    <p>Dear,</p>

    <p>I hope this email finds you well.</p>

    <p>Your leave for the period from <strong>{{ $details['start_date'] }}</strong> to <strong>{{ $details['end_date'] }}</strong> due to {{ $details['leave_reason'] }} is rejected and taken on record accordingly.</p>
    <p>Below are the details of your leave request:</p>
    <ul>
        <li><strong>Employee Name:</strong> {{ $details['employee_name'] }}</li>
        <li><strong>Employee ID:</strong> {{ $details['employeeID'] }}</li>
        <li><strong>Leave Type:</strong> {{ $details['leave_type'] }}</li>
        <li><strong>Number of Days:</strong> {{ $details['days_count'] }}</li>
    </ul>

    <p>Best Regards,<br> HR</p>
</body>
</html>
