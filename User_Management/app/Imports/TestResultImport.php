<?php

namespace App\Imports;

use App\Models\TestSeries\TestSeries;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Log;

class TestResultImport
{
    protected $testSeries;
    protected $results = [];
    protected $errors = [];

    public function __construct(TestSeries $testSeries)
    {
        $this->testSeries = $testSeries;
    }

    public function import($filePath)
    {
        try {
            $spreadsheet = IOFactory::load($filePath);
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray();

            // Get headers from first row
            $headers = array_shift($rows);
            $headers = array_map('strtolower', array_map('trim', $headers));

            Log::info('Processing result rows', ['count' => count($rows)]);

            foreach ($rows as $index => $row) {
                try {
                    $rowNumber = $index + 2; // +2 because we removed header and array is 0-indexed

                    // Convert row to associative array
                    $data = array_combine($headers, $row);

                    if (empty($data['roll_no']) || empty($data['student_name'])) {
                        $this->errors[] = "Row {$rowNumber}: Missing roll number or student name";
                        continue;
                    }

                    $rollNo = trim($data['roll_no']);
                    $studentName = trim($data['student_name']);

                    $subjectMarks = [];
                    $totalMarks = 0;

                    foreach ($this->testSeries->subjects as $subject) {
                        $subjectKey = strtolower(str_replace(' ', '_', $subject));
                        
                        if (isset($data[$subjectKey]) && $data[$subjectKey] !== null && $data[$subjectKey] !== '') {
                            $marks = floatval($data[$subjectKey]);
                            $subjectMarks[$subject] = $marks;
                            $totalMarks += $marks;
                        } else {
                            $this->errors[] = "Row {$rowNumber}: Missing marks for {$subject}";
                        }
                    }

                    $maxMarks = $this->testSeries->total_marks ?? array_sum($this->testSeries->subject_marks ?? []);
                    $percentage = $maxMarks > 0 ? ($totalMarks / $maxMarks) * 100 : 0;

                    $this->results[] = [
                        'roll_no' => $rollNo,
                        'student_name' => $studentName,
                        'subject_marks' => $subjectMarks,
                        'total_marks' => $totalMarks,
                        'percentage' => round($percentage, 2),
                    ];

                } catch (\Exception $e) {
                    $this->errors[] = "Row {$rowNumber}: " . $e->getMessage();
                    Log::error("Error processing row {$rowNumber}", [
                        'error' => $e->getMessage(),
                    ]);
                }
            }
        } catch (\Exception $e) {
            $this->errors[] = "File reading error: " . $e->getMessage();
            Log::error("Error reading Excel file", ['error' => $e->getMessage()]);
        }

        return $this;
    }

    public function getResults()
    {
        return $this->results;
    }

    public function getErrors()
    {
        return $this->errors;
    }
}