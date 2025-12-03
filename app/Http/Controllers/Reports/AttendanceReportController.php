<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\Attendance\Student as AttendanceRecord;
use App\Models\Student\SMstudents;
use App\Models\Master\Batch;
use App\Models\Master\Courses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class AttendanceReportController extends Controller
{
    /**
     * Display student attendance report page
     */
    public function studentIndex()
    {
        try {
            $courses = Courses::select('_id', 'name')
                ->get();

            Log::info('📊 Student Attendance Report Page Loaded', [
                'courses_count' => $courses->count()
            ]);

            return view('reports.attendance.student', compact('courses'));
            
        } catch (\Exception $e) {
            Log::error('❌ Error loading student attendance report page: ' . $e->getMessage());
            return back()->with('error', 'Failed to load attendance report page');
        }
    }

public function debugBatchData(Request $request)
{
    $courseId = $request->get('course_id');
    
    // Get 5 sample students from this course
    $students = SMstudents::where('status', 'active')
        ->where('course_id', $courseId)
        ->limit(5)
        ->get();
    
    $debugInfo = [];
    
    foreach ($students as $student) {
        $debugInfo[] = [
            'student_name' => $student->student_name ?? $student->name,
            'roll_no' => $student->roll_no,
            'batch_id' => $student->batch_id ?? 'NULL',
            'batch' => $student->batch ?? 'NULL',
            'batch_name' => $student->batch_name ?? 'NULL',
            'batchName' => $student->batchName ?? 'NULL',
            'raw_data' => [
                'batch_id' => $student->batch_id,
                'batch' => $student->batch,
                'batch_name' => $student->batch_name,
                'batchName' => $student->batchName
            ]
        ];
    }
    
    return response()->json([
        'course_id' => $courseId,
        'sample_students' => $debugInfo,
        'message' => 'Check which batch field has the readable ID (like 19L1, 14D4, etc)'
    ]);
}

    /**
     * Get batches by course (AJAX)
     */

public function getBatchesByCourse(Request $request)
{
    try {
        $courseId = $request->get('course_id');
        
        Log::info('📋 Getting batches for course', [
            'course_id' => $courseId,
            'request_data' => $request->all()
        ]);
        
        if (!$courseId) {
            return response()->json([
                'success' => false,
                'message' => 'Course ID is required'
            ], 400);
        }

        // Get the course first to verify it exists
        $course = Courses::find($courseId);
        
        if (!$course) {
            Log::warning('⚠️ Course not found', ['course_id' => $courseId]);
            return response()->json([
                'success' => false,
                'message' => 'Course not found'
            ], 404);
        }

        $courseName = $course->name ?? $course->course_name;
        
        Log::info('🔍 Finding batches with actual students', [
            'course_id' => $courseId,
            'course_name' => $courseName
        ]);

        // Get students and extract their batch information
        $students = SMstudents::where('status', 'active')
            ->where(function($query) use ($courseId, $courseName) {
                $query->where('course_id', $courseId)
                      ->orWhere('course', $courseId)
                      ->orWhere('course_name', $courseName)
                      ->orWhere('courseName', $courseName);
            })
            ->whereNotNull('batch_id')
            ->where('batch_id', '!=', '')
            ->where('batch_id', '!=', 'N/A')
            ->select('batch_id', 'batch_name', 'batchName', 'batch')
            ->get();

        Log::info('📊 Raw students query result', [
            'total_students' => $students->count(),
            'sample_student' => $students->first() ? [
                'batch_id' => $students->first()->batch_id,
                'batch_name' => $students->first()->batch_name,
                'batchName' => $students->first()->batchName,
                'batch' => $students->first()->batch
            ] : null
        ]);

        if ($students->isEmpty()) {
            Log::warning('⚠️ No students found for this course');
            return response()->json([
                'success' => true,
                'batches' => [],
                'message' => 'No batches with students found for this course'
            ]);
        }

        // Create unique batches collection
        // CRITICAL: Use the READABLE batch identifier (like 7YH6, 14D4)
        // NOT the MongoDB ObjectID
        $uniqueBatches = [];
        
        foreach ($students as $student) {
            // Priority order: batch > batch_name > batchName > batch_id
            // This ensures we get "7YH6" not "6923eb26a969c72c58074148"
            $readableBatchId = $student->batch 
                ?? $student->batch_name 
                ?? $student->batchName 
                ?? $student->batch_id;
            
            if ($readableBatchId && !isset($uniqueBatches[$readableBatchId])) {
                $uniqueBatches[$readableBatchId] = [
                    'batch_id' => $readableBatchId,  // "7YH6" - this is what students are stored with
                    'name' => $readableBatchId       // Display same as ID
                ];
            }
        }
        
        $batches = collect($uniqueBatches)->sortBy('batch_id')->values();

        Log::info('✅ Returning batches', [
            'count' => $batches->count(),
            'batches' => $batches
        ]);

        return response()->json([
            'success' => true,
            'batches' => $batches
        ]);

    } catch (\Exception $e) {
        Log::error('❌ Error loading batches: ' . $e->getMessage(), [
            'trace' => $e->getTraceAsString()
        ]);
        
        return response()->json([
            'success' => false,
            'message' => 'Failed to load batches: ' . $e->getMessage()
        ], 500);
    }
}



    /**
 * Get roll numbers by batch (AJAX)
 */


public function getRollsByBatch(Request $request)
{
    try {
        $batchId = $request->get('batch_id');
        $courseId = $request->get('course_id');
        
        Log::info('📋 Getting students for batch', [
            'batch_id' => $batchId,
            'course_id' => $courseId
        ]);
        
        if (!$batchId || !$courseId) {
            return response()->json([
                'success' => false,
                'message' => 'Both batch ID and course ID are required'
            ], 400);
        }

        $course = Courses::find($courseId);
        
        if (!$course) {
            return response()->json([
                'success' => false,
                'message' => 'Course not found'
            ], 404);
        }

        $courseName = $course->name ?? $course->course_name;
        
        Log::info('🔍 Searching for students', [
            'course' => $courseName,
            'batch_id' => $batchId
        ]);
        
        // Query with ALL possible batch field combinations
        // The batch_id parameter now contains the readable ID like "7YH6"
        $students = SMstudents::where('status', 'active')
            ->where(function($query) use ($batchId) {
                $query->where('batch', $batchId)           // Most common: "7YH6"
                      ->orWhere('batch_id', $batchId)      // Sometimes here
                      ->orWhere('batch_name', $batchId)    // Or here
                      ->orWhere('batchName', $batchId);    // Or here
            })
            ->where(function($query) use ($courseId, $courseName) {
                $query->where('course_id', $courseId)
                      ->orWhere('course', $courseId)
                      ->orWhere('course_name', $courseName)
                      ->orWhere('courseName', $courseName);
            })
            ->whereNotNull('roll_no')
            ->where('roll_no', '!=', '')
            ->where('roll_no', '!=', 'N/A')
            ->get(['_id', 'roll_no', 'student_name', 'name', 'studentName', 'batch_id', 'batch', 'batch_name', 'batchName']);

        Log::info('✅ Query results', [
            'count' => $students->count()
        ]);

        if ($students->isEmpty()) {
            $allStudentsInCourse = SMstudents::where('status', 'active')
                ->where(function($query) use ($courseId, $courseName) {
                    $query->where('course_id', $courseId)
                          ->orWhere('course', $courseId)
                          ->orWhere('course_name', $courseName)
                          ->orWhere('courseName', $courseName);
                })
                ->whereNotNull('roll_no')
                ->where('roll_no', '!=', '')
                ->where('roll_no', '!=', 'N/A')
                ->get(['_id', 'student_name', 'name', 'batch_id', 'batch', 'batch_name', 'batchName', 'roll_no']);
            
            $batchesAvailable = $allStudentsInCourse->map(function($s) {
                return $s->batch ?? $s->batch_name ?? $s->batchName ?? $s->batch_id ?? 'Unknown';
            })->unique()->values()->toArray();
            
            Log::warning('⚠️ No students found in batch', [
                'batch_id_searched' => $batchId,
                'course' => $courseName,
                'available_batches' => $batchesAvailable,
                'students_with_roll_numbers_in_course' => $allStudentsInCourse->count()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => "No students found in batch '{$batchId}'. Available batches: " . implode(', ', $batchesAvailable)
            ], 404);
        }

        $sortedStudents = $students->sortBy(function($student) {
            if (preg_match('/\d+$/', $student->roll_no, $matches)) {
                return (int)$matches[0];
            }
            return $student->roll_no;
        });

        Log::info('✅ Students found and sorted', [
            'count' => $sortedStudents->count(),
            'batch_id' => $batchId,
            'sample' => $sortedStudents->take(3)->map(function($s) {
                return [
                    'roll_no' => $s->roll_no,
                    'name' => $s->student_name ?? $s->name ?? $s->studentName ?? 'N/A',
                    'batch' => $s->batch ?? $s->batch_name ?? $s->batchName ?? $s->batch_id ?? 'N/A'
                ];
            })
        ]);

        return response()->json([
            'success' => true,
            'students' => $sortedStudents->map(function($student) {
                return [
                    '_id' => (string) $student->_id,
                    'roll_no' => $student->roll_no,
                    'name' => $student->student_name ?? $student->name ?? $student->studentName ?? 'N/A'
                ];
            })->values()
        ]);

    } catch (\Exception $e) {
        Log::error('❌ Error loading students', [
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
            'batch_id' => $batchId ?? 'unknown',
            'course_id' => $courseId ?? 'unknown'
        ]);
        
        return response()->json([
            'success' => false,
            'message' => 'Failed to load students: ' . $e->getMessage()
        ], 500);
    }
}


    /**
     * Get student attendance data
     */

public function getStudentData(Request $request)
{
    try {
        Log::info('📊 Getting student attendance report', ['filters' => $request->all()]);

        $courseId = $request->get('course');
        $batchId = $request->get('batch');  // Now contains "7YH6" not ObjectID
        $rollNo = $request->get('roll_no');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        if (!$courseId || !$batchId || !$rollNo || !$startDate || !$endDate) {
            return response()->json([
                'success' => false,
                'message' => 'All fields are required'
            ], 400);
        }

        $course = Courses::find($courseId);
        
        if (!$course) {
            return response()->json([
                'success' => false,
                'message' => 'Course not found'
            ], 404);
        }

        $courseName = $course->name ?? $course->course_name;
        
        Log::info('🔍 Looking for student', [
            'roll_no' => $rollNo,
            'batch_id' => $batchId,  // "7YH6"
            'course' => $courseName
        ]);

        // Search in main collection directly
        // Match against ALL possible batch fields
        $student = SMstudents::where('status', 'active')
            ->where('roll_no', $rollNo)
            ->where(function($q) use ($batchId) {
                $q->where('batch', $batchId)           // "7YH6"
                  ->orWhere('batch_id', $batchId)
                  ->orWhere('batch_name', $batchId)
                  ->orWhere('batchName', $batchId);
            })
            ->where(function($q) use ($courseId, $courseName) {
                $q->where('course_id', $courseId)
                  ->orWhere('course', $courseId)
                  ->orWhere('course_name', $courseName)
                  ->orWhere('courseName', $courseName);
            })
            ->first();
        
        if (!$student) {
            Log::warning('⚠️ Student not found', [
                'roll_no' => $rollNo,
                'batch_id' => $batchId,
                'course_id' => $courseId
            ]);
            
            return response()->json([
                'success' => false,
                'message' => "Student with roll number '{$rollNo}' not found in batch '{$batchId}'."
            ], 404);
        }

        Log::info('✅ Student found', [
            'student_id' => (string)$student->_id,
            'name' => $student->student_name ?? $student->name,
            'roll_no' => $student->roll_no
        ]);

        // Get attendance records
        $attendanceRecords = \App\Models\Attendance\Student::where('student_id', (string) $student->_id)
            ->where('date', '>=', $startDate)
            ->where('date', '<=', $endDate)
            ->orderBy('date', 'desc')
            ->get();

        Log::info('📋 Found attendance records', [
            'count' => $attendanceRecords->count(),
            'student_id' => (string)$student->_id,
            'date_range' => [$startDate, $endDate]
        ]);

        // Calculate statistics
        $totalDays = 0;
        $presentCount = 0;
        $absentCount = 0;
        $notMarkedCount = 0;
        
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);
        $dateRange = [];
        
        $attendanceMap = $attendanceRecords->keyBy('date');
        
        while ($start->lte($end)) {
            $dateStr = $start->format('Y-m-d');
            $dayOfWeek = $start->dayOfWeek;
            
            if ($dayOfWeek != 0 && $dayOfWeek != 6) {
                $totalDays++;
                
                $status = 'not-marked';
                $markedAt = null;
                $markedBy = null;
                
                if (isset($attendanceMap[$dateStr])) {
                    $record = $attendanceMap[$dateStr];
                    $status = $record->status;
                    $markedAt = $record->marked_at;
                    $markedBy = $record->marked_by;
                }
                
                if ($status === 'present') {
                    $presentCount++;
                } elseif ($status === 'absent') {
                    $absentCount++;
                } else {
                    $notMarkedCount++;
                }
                
                $dateRange[] = [
                    'date' => $dateStr,
                    'day' => $start->format('l'),
                    'status' => $status,
                    'marked_at' => $markedAt ? $markedAt->format('Y-m-d H:i:s') : null,
                    'marked_by' => $markedBy
                ];
            }
            
            $start->addDay();
        }

        $attendancePercentage = $totalDays > 0 
            ? round(($presentCount / $totalDays) * 100, 2) 
            : 0;

        // Get the readable batch name from student record
        $batchName = $student->batch ?? $student->batch_name ?? $student->batchName ?? $batchId;

        // Get shift - handle if it's an object or string
        $shiftValue = 'N/A';
        if (isset($student->shift)) {
            if (is_object($student->shift) || is_array($student->shift)) {
                $shiftArray = (array) $student->shift;
                $shiftValue = $shiftArray['name'] ?? $shiftArray['shift_name'] ?? 'N/A';
            } else {
                $shiftValue = $student->shift;
            }
        }

        $studentInfo = [
            '_id' => (string) $student->_id,
            'roll_no' => $student->roll_no ?? 'N/A',
            'name' => $student->student_name ?? $student->name ?? 'N/A',
            'email' => $student->email ?? 'N/A',
            'batch_name' => $batchName,
            'course_name' => $courseName,
            'shift' => $shiftValue,
            'branch' => $student->branch ?? 'N/A'
        ];

        $statistics = [
            'total_days' => $totalDays,
            'present' => $presentCount,
            'absent' => $absentCount,
            'not_marked' => $notMarkedCount,
            'attendance_percentage' => $attendancePercentage
        ];

        Log::info('✅ Student attendance report generated', [
            'student' => $studentInfo['name'],
            'roll_no' => $studentInfo['roll_no'],
            'statistics' => $statistics
        ]);

        return response()->json([
            'success' => true,
            'student' => $studentInfo,
            'statistics' => $statistics,
            'attendance_data' => $dateRange
        ]);

    } catch (\Exception $e) {
        Log::error('❌ Error getting student attendance report: ' . $e->getMessage(), [
            'trace' => $e->getTraceAsString(),
            'request_data' => $request->all()
        ]);
        
        return response()->json([
            'success' => false,
            'message' => 'Failed to load attendance report: ' . $e->getMessage()
        ], 500);
    }
}
}