<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Training Assigned</title>
</head>
<body>
    <p>Dear {{ $user->employee->full_name }},</p>

    <p>You have been assigned to a new training.</p>

    <p><strong>Training Title:</strong> {{ $training->training_title }}</p>
    <p><strong>Start:</strong> {{ $training->start_date_time }}</p>
    <p><strong>End:</strong> {{ $training->end_date_time }}</p>
    <p><strong>Link: </strong><a></a></p>
    <p><strong>Details:</strong></p>
    <p>{{ $training->training_details }}</p>
    <p>
    <a href="{{ $trainingUrl }}"
       style="display:inline-block;
              padding:10px 16px;
              background:#0d6efd;
              color:#ffffff;
              text-decoration:none;
              border-radius:5px;">
        View Training
    </a>
</p>

<p>If the button doesn’t work, copy and paste this link:</p>
<p>{{ $trainingUrl }}</p>


    <p>Regards,<br>
    HR Team</p>
</body>
</html>
