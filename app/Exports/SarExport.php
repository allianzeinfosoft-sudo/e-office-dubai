<?php

namespace App\Exports;

use App\Models\SarQuestion;
use App\Models\SarUserAssign;
use App\Models\SelfAppraisalReport;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SarExport implements FromCollection, WithHeadings
{

    protected $sarId;
    protected $questions;

     public function __construct($sarId)
    {
        $this->sarId = $sarId;
        $this->questions = SarQuestion::where('template_id', $sarId)->get();
    }


    public function collection()
    {

        return SarUserAssign::with(['employee.department', 'employee.user'])
            ->where('template_id', $this->sarId)
            ->where('status', 2)
            ->get()
            ->map(function ($assignment) {
                // Get related answers
                $reports = SelfAppraisalReport::where('sar_id', $assignment->id)->get()->keyBy('question');

                $row = [
                    $assignment->employee->full_name ?? 'N/A',
                    $assignment->employee->user->email ?? 'N/A',
                    $assignment->employee->department->department ?? 'N/A',
                ];

                // Map answers to question columns
                foreach ($this->questions as $question) {
                    $report = $reports->get($question->id);
                    $row[] = $report ? "{$report->mark}" : 'N/A';
                }

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
            $headings[] = "Q" . ($index + 1).") ".$question->question;
        }

        $headings[] = 'Total Score';
        $headings[] = 'Maximum Score';
        $headings[] = 'Score Percentage';

        return $headings;
    }
}
