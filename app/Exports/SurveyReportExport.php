<?php

namespace App\Exports;

use App\Models\SurveyQuestion;
use App\Models\SurveyReport;
use App\Models\SurveyUserAssign;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SurveyReportExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles, WithEvents, WithCustomStartCell
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
            ->where('status',2)
            ->get()
            ->map(function ($assignment) {
                // Get related answers
                $reports = SurveyReport::where('survey_id', $assignment->id)->get()->keyBy('question');

                $row = [
                    $assignment->employee->full_name ?? 'N/A',
                    $assignment->employee->user->email ?? 'N/A',
                    $assignment->employee->department->department ?? 'N/A',
                    $assignment->survey_name ?? 'N/A',
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
        $headings = ['Employee Name', 'Email ID', 'Department', 'Survay Name'];

        // Add dynamic question headings
        foreach ($this->questions as $index => $question) {
            // $headings[] = "Question " . ($index + 1);
            $headings[] = "Q" . ($index + 1).") ".$question->question;
        }
        return $headings;
    }

    public function startCell(): string
    {
        return 'A2'; // Headings in row 2, data from row 3
    }

     public function styles(Worksheet $sheet)
    {
        // Bold headings (Row 2)
        return [
            2 => ['font' => ['bold' => true]],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Report Title in Row 1
                $title = 'Survay Report';
                $highestColumn = $sheet->getHighestColumn(); // Get last column dynamically
                $sheet->mergeCells("A1:{$highestColumn}1"); // Merge cells for title
                $sheet->setCellValue('A1', $title);

                // Style for Title
                $sheet->getStyle('A1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 16,
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                ]);

                // Bold Headings (Row 2)
                $sheet->getStyle("A2:{$highestColumn}2")->applyFromArray([
                    'font' => ['bold' => true],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                ]);

                // Set Row Height for Title
                $sheet->getRowDimension(1)->setRowHeight(25);

                // Auto wrap for headings
                $sheet->getStyle("A2:{$highestColumn}2")->getAlignment()->setWrapText(true);
            },
        ];
    }
}
