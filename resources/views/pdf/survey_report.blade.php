<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Survey Report</title>
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

    <h2>Survey Report</h2>
    <h1 style="text-align: center;">{{ $survey_info->template?->template_name ?? 'N/A' }}</h1><hr>
    <div class="section">
        <p class="info-row">
            <strong>Employee Name:</strong> {{ $survey_info->employee?->full_name ?? 'N/A' }} &nbsp; | &nbsp;
            <strong>Department:</strong> {{ $survey_info->template?->department_info?->department ?? 'N/A' }} &nbsp; | &nbsp;
            <strong>Designation:</strong> {{ $survey_info->employee?->designation?->designation ?? 'N/A' }} &nbsp; | &nbsp;
            <strong>Created By:</strong> {{ $survey_info->template?->creator?->full_name ?? 'N/A' }}
        </p>
    </div>

    <div class="section">
        <h4>Question Responses</h4>

        @foreach ($answers as $index => $qa)
            <div class="question-block">
                <h5>Q{{ $index + 1 }}: {{ $qa['question_text'] }}</h5>
                @if($qa['answer_type'] === 'rating')
                        @php
                           $stars = (int) $qa['answer'];
                            $starOutput = '';
                            for ($i = 1; $i <= 5; $i++) {
                                $starOutput .= $i <= $stars ? '<b> * </b>' : '';
                            }
                        @endphp
                       <p><strong>Answer:</strong> {!! $qa['answer'] ? $starOutput : '<span class="text-muted">No response</span>' !!}</p>
                @else
                    <p><strong>Answer:</strong> {{ $qa['answer'] ?? 'No response' }}</p>
                @endif
            </div>
        @endforeach
    </div>

</body>
</html>
