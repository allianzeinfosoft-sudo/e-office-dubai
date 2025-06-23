<?php

namespace App\Exports;

use App\Models\FeedbackAssign;
use App\Models\FeedbackQuestion;
use App\Models\FeedbackReport;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class FeedbackReportExport implements FromCollection, WithHeadings
{
    protected $feedbackId;
    protected $questions;

    public function __construct($feedbackId)
    {
        $this->feedbackId = $feedbackId;
        $this->questions = FeedbackQuestion::where('feedback_id', $feedbackId)->get();
    }

    public function collection()
    {

         return FeedbackAssign::with(['employee.department', 'employee.user'])
            ->where('feedback_id', $this->feedbackId)
            ->get()
            ->map(function ($assignment) {
                // Get related answers
                $reports = FeedbackReport::where('feedback_assign_id', $assignment->id)->get()->keyBy('question');

                $row = [
                    $assignment->employee->full_name ?? 'N/A',
                    $assignment->employee->user->email ?? 'N/A',
                    $assignment->employee->department->department ?? 'N/A',
                ];

                // Map answers to question columns
                foreach ($this->questions as $question) {
                    $report = $reports->get($question->id);
                    $row[] = $report ? "{$report->mark}" : 'N/A';
                    // $row[] = $report ? "{$report->mark} ({$report->comment})" : 'N/A';
                }

                // Add score summary
                $row[] = $assignment->total_score;
                $row[] = $assignment->maximum_score;
                $row[] = $assignment->score_percentage;

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

        $headings[] = 'Total Score';
        $headings[] = 'Maximum Score';
        $headings[] = 'Score Percentage';

        return $headings;
    }
}
