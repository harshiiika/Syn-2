<?php

namespace App\Exports;

use App\Models\TestSeries\TestSeries;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class TestResultTemplateExport
{
    protected $testSeries;
    protected $students;

    public function __construct(TestSeries $testSeries, $students)
    {
        $this->testSeries = $testSeries;
        $this->students = $students;
    }

    public function download($filename)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Set sheet title
        $sheet->setTitle('Result Template');

        // ============ ROW 1: Test Series Information ============
        $sheet->setCellValue('A1', 'Test Series:');
        $sheet->setCellValue('B1', $this->testSeries->test_name);
        $sheet->mergeCells('B1:D1');
        
        $sheet->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 12],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'E8E8E8'],
            ],
        ]);
        
        $sheet->getStyle('B1')->applyFromArray([
            'font' => ['size' => 12, 'color' => ['rgb' => 'FD550D']],
        ]);

        // ============ ROW 2: Subject Information ============
        $sheet->setCellValue('A2', 'Subjects:');
        $subjectsText = implode(', ', $this->testSeries->subjects);
        $sheet->setCellValue('B2', $subjectsText);
        $sheet->mergeCells('B2:D2');
        
        $sheet->getStyle('A2')->applyFromArray([
            'font' => ['bold' => true, 'size' => 11],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'E8E8E8'],
            ],
        ]);

        // ============ ROW 3: Blank spacing ============
        $sheet->getRowDimension(3)->setRowHeight(5);

        // ============ ROW 4: Column Headers ============
        $headers = ['Roll No', 'Student Name'];
        
        // Add each subject as a header
        foreach ($this->testSeries->subjects as $subject) {
            $headers[] = $subject;
        }

        $columnIndex = 1;
        foreach ($headers as $header) {
            $sheet->setCellValueByColumnAndRow($columnIndex, 4, $header);
            $columnIndex++;
        }

        $highestColumn = $sheet->getHighestColumn();

        // Style header row (Row 4)
        $sheet->getStyle('A4:' . $highestColumn . '4')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 12,
                'name' => 'Arial'
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'FD550D'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_MEDIUM,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ]);

        $sheet->getRowDimension(4)->setRowHeight(25);

        // ============ ROW 5 onwards: Student Data ============
        $rowIndex = 5;
        foreach ($this->students as $student) {
            $sheet->setCellValue('A' . $rowIndex, $student->roll_no);
            $sheet->setCellValue('B' . $rowIndex, $student->student_name ?? $student->name);
            $rowIndex++;
        }

        $highestRow = $sheet->getHighestRow();

        // ============ Style Student Data Rows ============
        if ($highestRow > 4) {
            $sheet->getStyle('A5:' . $highestColumn . $highestRow)->applyFromArray([
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => 'CCCCCC'],
                    ],
                ],
            ]);

            // Alternate row colors
            for ($i = 5; $i <= $highestRow; $i++) {
                if (($i - 5) % 2 == 0) {
                    $sheet->getStyle('A' . $i . ':' . $highestColumn . $i)->applyFromArray([
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'startColor' => ['rgb' => 'F9F9F9'],
                        ],
                    ]);
                }
            }
        }

        // ============ Column Widths ============
        $sheet->getColumnDimension('A')->setWidth(15);
        $sheet->getColumnDimension('B')->setWidth(25);
        
        $currentCol = 'C';
        foreach ($this->testSeries->subjects as $subject) {
            $sheet->getColumnDimension($currentCol)->setWidth(18);
            $currentCol++;
        }

        // ============ Freeze Panes ============
        $sheet->freezePane('C5');

        // ============ Data Validation for Marks ============
        if ($highestRow > 4) {
            $startCol = 'C';
            $endCol = $highestColumn;
            
            $validation = $sheet->getCell($startCol . '5')->getDataValidation();
            $validation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_DECIMAL);
            $validation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP);
            $validation->setAllowBlank(true);
            $validation->setShowInputMessage(true);
            $validation->setShowErrorMessage(true);
            $validation->setErrorTitle('Invalid Marks');
            $validation->setError('Please enter marks between 0 and 100');
            $validation->setPromptTitle('Enter Marks');
            $validation->setPrompt('Enter marks obtained (0-100)');
            $validation->setFormula1(0);
            $validation->setFormula2(100);
            $validation->setOperator(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::OPERATOR_BETWEEN);
            
            $sheet->setDataValidation($startCol . '5:' . $endCol . $highestRow, $validation);
        }

        // ============ Add Instructions Sheet ============
        $instructionsSheet = $spreadsheet->createSheet();
        $instructionsSheet->setTitle('Instructions');
        
        $instructionsSheet->setCellValue('A1', 'HOW TO FILL THE RESULT TEMPLATE');
        $instructionsSheet->getStyle('A1')->applyFromArray([
            'font' => [
                'bold' => true, 
                'size' => 16, 
                'color' => ['rgb' => 'FD550D'] 
            ],
        ]);
        $instructionsSheet->getRowDimension(1)->setRowHeight(30);
        
        $row = 3;
        $instructions = [
            'IMPORTANT NOTES:',
            '',
            '1. DO NOT modify the Roll Number or Student Name columns',
            '2. DO NOT add or remove any columns',
            '3. DO NOT add or remove any rows',
            '4. DO NOT change the subject names in the header',
            '',
            'FILLING MARKS:',
            '',
            '5. Enter marks for each subject (between 0 and 100)',
            '6. Enter decimal marks if needed (e.g., 85.5)',
            '7. Leave cells BLANK for absent students',
            '8. Double-check all entries before uploading',
            '',
            'UPLOADING:',
            '',
            '9. Save the file after filling all marks',
            '10. Go to the test series page and click "Upload Result"',
            '11. Select this filled file and upload',
            '',
            'SUBJECTS IN THIS TEST:',
            '',
        ];
        
        foreach ($instructions as $instruction) {
            $instructionsSheet->setCellValue('A' . $row, $instruction);
            if (in_array($instruction, ['IMPORTANT NOTES:', 'FILLING MARKS:', 'UPLOADING:', 'SUBJECTS IN THIS TEST:'])) {
                $instructionsSheet->getStyle('A' . $row)->getFont()->setBold(true)->setSize(12);
            }
            $row++;
        }
        
        // List all subjects
        foreach ($this->testSeries->subjects as $index => $subject) {
            $instructionsSheet->setCellValue('A' . $row, ($index + 1) . '. ' . $subject);
            $instructionsSheet->getStyle('A' . $row)->applyFromArray([
                'font' => ['color' => ['rgb' => 'FD550D']], 
            ]);
            $row++;
        }
        
        $instructionsSheet->getColumnDimension('A')->setWidth(70);
        
        // Set active sheet back to the data sheet
        $spreadsheet->setActiveSheetIndex(0);

        // ============ Output File ============
        $writer = new Xlsx($spreadsheet);
        
        // Clear any output buffers
        if (ob_get_contents()) {
            ob_end_clean();
        }
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Pragma: public');
        
        $writer->save('php://output');
        exit;
    }
}