<?php

namespace App\Exports;

use App\Models\ParQuestion;
use App\Models\ParUserAssign;
use App\Models\PerformanceAppraisalReport;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ParExport implements FromCollection, WithHeadings
{
    protected $parId;
    protected $questions;

     public function __construct($parId)
    {
        $this->parId = $parId;
        $this->questions = ParQuestion::where('template_id', $parId)->get();
    }


    public function collection()
    {
        return ParUserAssign::with(['employee.department', 'employee.user'])
            ->where('template_id', $this->parId)
            ->where('status',2)
            ->get()
            ->map(function ($assignment) {
                // Get related answers
                $reports = PerformanceAppraisalReport::where('par_id', $assignment->id)->get()->keyBy('question');

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
