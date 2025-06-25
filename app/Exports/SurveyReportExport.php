<?php

namespace App\Exports;

use App\Models\SurveyQuestion;
use App\Models\SurveyReport;
use App\Models\SurveyUserAssign;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SurveyReportExport implements FromCollection, WithHeadings
{

    protected $surveyId;
    protected $questions;

    public function __construct($surveyId)
        {
            $this->surveyId = $surveyId;

            $this->questions = SurveyQuestion::where('template_id', $surveyId)->get();
        }

     public function collection()
    {

         return SurveyUserAssign::with(['employee.department', 'employee.user'])
            ->where('template_id', $this->surveyId)
            ->get()
            ->map(function ($assignment) {
                // Get related answers
                $reports = SurveyReport::where('survey_id', $assignment->id)->get()->keyBy('question');

                $row = [
                    $assignment->employee->full_name ?? 'N/A',
                    $assignment->employee->user->email ?? 'N/A',
                    $assignment->employee->department->department ?? 'N/A',
                ];

                // Map answers to question columns
                foreach ($this->questions as $question) {
                    $report = $reports->get($question->id);
                    $row[] = $report ? "{$report->answer}" : 'N/A';
                }
                return $row;
            });
    }

    public function headings(): array
    {
        $headings = ['Employee Name', 'Email ID', 'Department'];

        // Add dynamic question headings
        foreach ($this->questions as $index => $question) {
            // $headings[] = "Question " . ($index + 1);
            $headings[] = "Q" . ($index + 1).") ".$question->question;
        }
        return $headings;
    }
}
