<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Training Test Invitation</title>
</head>
<body>

<p>Dear {{ $user->employee->full_name }},</p>

<p>You have been invited to attend a training test.</p>

<p>
    <strong>Test Title:</strong> {{ $trainingTest->title }} <br>
    <strong>Start Date:</strong> {{ \Carbon\Carbon::parse($trainingTest->start_at)->format('d M Y') }} <br>
    <strong>End Date:</strong> {{ \Carbon\Carbon::parse($trainingTest->end_at)->format('d M Y') }}
</p>

<p>
    <a href="{{ $testUrl }}" style="
        display:inline-block;
        padding:10px 16px;
        background:#0d6efd;
        color:#fff;
        text-decoration:none;
        border-radius:4px;">
        View Training Test
    </a>
</p>

<p>
Best regards,<br>
{{ config('app.name') }}
</p>

</body>
</html>
