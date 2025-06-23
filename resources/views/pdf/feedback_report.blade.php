<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Feedback Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 13px;
            line-height: 1.6;
            margin: 5px;
            color: #000;
        }

        h3, h4, h2 {
            text-align: center;
            margin-bottom: 5px;
        }

        .section {
            margin-bottom: 15px;
        }

        .info-row {
            margin: 4px 0;
        }

        .score-summary {
            border: 1px solid #ccc;
            background-color: #f8f8f8;
            padding: 10px 10px;
            border-radius: 5px;
        }

        .score-summary table {
            width: 100%;
            margin-top: 3px;
            border-collapse: collapse;
        }

        .score-summary td {
            padding: 3px 3px;
            border-bottom: 1px solid #eaeaea;
        }

        .question-block {
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 10px 10px;
            margin-bottom: 15px;
        }

        .question-block h5 {
            margin: 0 0 10px;
            font-size: 14px;
        }

        .question-block p {
            margin: 4px 0;
        }

        hr {
            margin: 30px 0;
            border: none;
            border-top: 1px solid #ccc;
        }
        .info-row {
            white-space: nowrap;
        }
    </style>
</head>
<body>

    <h2>Feedback Report</h2>

    <div class="section">
        <p class="info-row">
            <strong>Employee Name:</strong> {{ $feedback_info->employee->full_name ?? 'N/A' }} &nbsp; | &nbsp;
            <strong>Department:</strong> {{ $feedback_info->employee->department->department ?? 'N/A' }} &nbsp; | &nbsp;
            <strong>Designation:</strong> {{ $feedback_info->employee->designation->designation ?? 'N/A' }} &nbsp; | &nbsp;
            <strong>Created By:</strong> {{ $feedback_info->feedback->creator->full_name ?? 'N/A' }}
        </p>
    </div>

    <div class="section score-summary">
        <h4>Score Summary</h4>
        <table>
            <tr>
                <td><strong>Total Score:</strong></td>
                <td>{{ $total_score }}</td>
            </tr>
            <tr>
                <td><strong>Maximum Score:</strong></td>
                <td>{{ $maximum_score }}</td>
            </tr>
            <tr>
                <td><strong>Score Percentage:</strong></td>
                <td>{{ $score_percent }}%</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <h4>Question Responses</h4>

        @foreach ($answers as $index => $qa)
            <div class="question-block">
                <h5>Q{{ $index + 1 }}: {{ $qa['question_text'] }}</h5>
                <p><strong>Grade:</strong>
                    @switch($qa['mark'])
                        @case(5) Outstanding @break
                        @case(4) Very Good @break
                        @case(3) Good @break
                        @case(2) Average @break
                        @case(1) Poor @break
                        @default N/A
                    @endswitch
                </p>
                <p><strong>Comment:</strong> {{ $qa['answer'] ?? 'No response' }}</p>
            </div>
        @endforeach
    </div>

</body>
</html>
