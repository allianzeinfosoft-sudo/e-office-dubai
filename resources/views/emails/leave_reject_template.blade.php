<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Your Leave Application Is Rejected</title>
</head>
<body>
   <div class="card-body">
        <div class="badge w-100 bg-success mb-3">
        <h5 class="mb-0 text-white">Leave Reject Slip</h5>
        </div>
        <p class="mb-0">Dear,</p>
        <p>{{ $details['employee_name'] }}</p>

        <p>Your leave for the period from
        <span class="badge bg-label-warning p-2">{{ $details['start_date'] }}</span> to <span class="badge bg-label-warning p-2">{{ $details['end_date'] }}</span>
        due to {{ $details['leave_reason'] }} is <Span class="fw-bolder bg-label-success">sanctioned </Span>and taken on record accordingly.</p>
        <p>Below are the details of your leave request:</p>
        <div class="row">
        <div class="col-md-6 col-xl-4">
            <div class="card h-px-120 shadow-none bg-transparent border border-danger mb-3">
            <div class="card-body">
                <h6 class="card-title">Leave Type:</h6>
                <p class="card-text"><span class="badge bg-label-dark fw-bold p-2">{{ $details['leave_type'] }}</span></p>
            </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-4">
            <div class="card h-px-120 shadow-none bg-transparent border border-danger mb-3">
            <div class="card-body">
                <h6 class="card-title">No of Days:</h6>
                <p class="card-text"><span class="badge bg-label-dark fw-bold p-2">{{ $details['days_count'] }}</span></p>
            </div>
            </div>
        </div>
        </div>
        <div class="row justify-content-between d-flex">
        <div class="col-md-6"><span>Best Regards,<br>{{ $details['employee_name'] }}</span></div>
        <div class="col-md-5 mt-3 text-end"><span>{{ date('d-m-Y H:i:s') }}</span></div>
        </div>
    </div>
</body>
</html>



