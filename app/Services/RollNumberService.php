<?php

namespace App\Services;

use App\Models\Student\SMstudents;
use Illuminate\Support\Facades\Log;

class RollNumberService
{
    /**
     * Generate Roll Number: YY + MM + CC + NNN
     * YY = Year (25 for 2025)
     * MM = Month (01-12)
     * CC = Course Code (your courses mapped below)
     * NNN = Sequential number
     */
    public static function generateUniqueRollNumber($courseId = null, $courseName = null, $batchId = null, $batchName = null)
    {
        try {
            Log::info('  Generating Roll Number', [
                'course_name' => $courseName,
                'batch_name' => $batchName,
            ]);

            // Year (25 for 2025)
            $year = now()->format('y');

            // Month (current month)
            $month = now()->format('m');

            // Course Code (from your courses)
            $courseCode = self::getCourseCode($courseName);

            // Sequential number
            $sequential = self::getNextNumber($year, $month, $courseCode);

            // Final Roll Number
            $rollNumber = $year . $month . $courseCode . str_pad($sequential, 3, '0', STR_PAD_LEFT);

            Log::info('  Roll Number Generated: ' . $rollNumber, [
                'year' => $year,
                'month' => $month,
                'course_code' => $courseCode,
                'sequential' => $sequential,
            ]);

            return $rollNumber;

        } catch (\Exception $e) {
            Log::error(' Roll Number Failed: ' . $e->getMessage());
            return '25' . now()->format('md') . rand(100, 999);
        }
    }

    /**
     * Map YOUR course names to 2-digit codes
     */
    private static function getCourseCode($courseName)
    {
        if (!$courseName) {
            return '99'; // Default
        }

        $courseLower = strtolower(trim($courseName));

        //   YOUR EXACT COURSES MAPPED TO CODES
        $courseMap = [
            // IIT/JEE Courses (11-19)
            'impulse 11th iit' => '11',
            'intensity 12th iit' => '12',
            'thrust target iit' => '13',
            
            // NEET Courses (21-29)
            'momentum 12th neet' => '21',
            'anthesis 11th neet' => '22',
            'dynamic target neet' => '23',
            
            // Foundation Courses (01-09)
            'nucleus 7th' => '07',
            'radicle 8th' => '08',
            'plumule 9th' => '09',
            'seedling 10th' => '10',
        ];

        // Exact match first
        if (isset($courseMap[$courseLower])) {
            Log::info("  Exact match: '{$courseName}' -> " . $courseMap[$courseLower]);
            return $courseMap[$courseLower];
        }

        // Partial match (in case of typos or variations)
        foreach ($courseMap as $keyword => $code) {
            if (str_contains($courseLower, $keyword) || str_contains($keyword, $courseLower)) {
                Log::info("  Partial match: '{$courseName}' -> {$code}");
                return $code;
            }
        }

        // If 11th/12th/class found in name
        if (preg_match('/(\d{1,2})(th|st|nd|rd)?/', $courseLower, $matches)) {
            $classNum = str_pad($matches[1], 2, '0', STR_PAD_LEFT);
            Log::info("  Class extracted: '{$courseName}' -> {$classNum}");
            return $classNum;
        }

        Log::warning("  Course not mapped: '{$courseName}' - using default 99");
        return '99'; // Default for unmapped courses
    }

    /**
     * Get next sequential number
     */
    private static function getNextNumber($year, $month, $courseCode)
    {
        $pattern = $year . $month . $courseCode;

        // Find highest roll number with this pattern
        $lastStudent = SMstudents::where('roll_no', 'regex', '/^' . $pattern . '\d+$/')
            ->orderBy('roll_no', 'desc')
            ->first();

        if (!$lastStudent || !$lastStudent->roll_no) {
            return 1; // First student
        }

        // Extract number part and increment
        $lastRollNo = $lastStudent->roll_no;
        $numberPart = substr($lastRollNo, strlen($pattern));
        
        return intval($numberPart) + 1;
    }

    /**
     * Check if roll number exists
     */
    public static function isRollNumberUnique($rollNumber)
    {
        return !SMstudents::where('roll_no', $rollNumber)->exists();
    }
}