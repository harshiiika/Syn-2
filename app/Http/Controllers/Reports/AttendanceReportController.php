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

            // Try to find batches - check multiple possible field names
            $batches = Batch::where('status', 'Active')
                ->where(function($query) use ($courseId) {
                    $query->where('course', $courseId)
                          ->orWhere('course_id', $courseId)
                          ->orWhere('course._id', $courseId);
                })
                ->get(['_id', 'batch_id', 'name', 'course', 'course_id']);

            // If no batches found, try a different approach
            if ($batches->isEmpty()) {
                Log::warning('⚠️ No batches found with standard query, trying alternative', [
                    'course_id' => $courseId
                ]);
                
                // Get all active batches and filter in PHP
                $allBatches = Batch::where('status', 'Active')->get();
                
                $batches = $allBatches->filter(function($batch) use ($courseId) {
                    // Check various possible course field structures
                    $batchCourseId = null;
                    
                    if (isset($batch->course)) {
                        if (is_string($batch->course)) {
                            $batchCourseId = $batch->course;
                        } elseif (is_array($batch->course) && isset($batch->course['_id'])) {
                            $batchCourseId = $batch->course['_id'];
                        } elseif (is_object($batch->course) && isset($batch->course->_id)) {
                            $batchCourseId = $batch->course->_id;
                        }
                    }
                    
                    if (isset($batch->course_id)) {
                        $batchCourseId = $batch->course_id;
                    }
                    
                    return $batchCourseId == $courseId;
                })->values();
            }

            Log::info('✅ Batches loaded for course', [
                'course_id' => $courseId,
                'course_name' => $course->name,
                'batches_count' => $batches->count(),
                'batches' => $batches->map(function($b) {
                    return [
                        '_id' => $b->_id,
                        'batch_id' => $b->batch_id,
                        'name' => $b->name
                    ];
                })
            ]);

            return response()->json([
                'success' => true,
                'batches' => $batches->map(function($batch) {
                    return [
                        '_id' => (string) $batch->_id,
                        'batch_id' => $batch->batch_id ?? $batch->name,
                        'name' => $batch->name ?? $batch->batch_id
                    ];
                })->values()
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
/**
 * Get roll numbers by batch (AJAX) - FIXED VERSION
 */
/**
 * Get roll numbers by batch (AJAX) - COMPLETE FIX
 */
/**
 * Get roll numbers by batch (AJAX) - FIXED MongoDB Query
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
        
        if (!$batchId) {
            return response()->json([
                'success' => false,
                'message' => 'Batch ID is required'
            ], 400);
        }

        if (!$courseId) {
            return response()->json([
                'success' => false,
                'message' => 'Course ID is required'
            ], 400);
        }

        // Get the course
        $course = \App\Models\Master\Courses::find($courseId);
        
        if (!$course) {
            Log::warning('⚠️ Course not found', ['course_id' => $courseId]);
            return response()->json([
                'success' => false,
                'message' => 'Course not found'
            ], 404);
        }

        // Generate collection name from course name
        $courseName = $course->name ?? $course->course_name;
        $collectionName = 'students_' . strtolower(str_replace(' ', '_', $courseName));
        
        Log::info('🔍 Querying collection', [
            'collection' => $collectionName,
            'course_name' => $courseName,
            'batch_id' => $batchId
        ]);
        
        // Create model instance with correct collection
        $studentModel = new \App\Models\Student\SMstudents;
        $studentModel->setTable($collectionName);
        
        // Query students with roll numbers
        $students = $studentModel->where('status', 'active')
            ->where(function($query) use ($batchId) {
                $query->where('batch_id', $batchId)
                      ->orWhere('batch', $batchId);
            })
            ->whereNotNull('roll_no')
            ->where('roll_no', '!=', '')
            ->where('roll_no', '!=', 'N/A')
            ->get(['_id', 'roll_no', 'student_name', 'name', 'batch_id', 'course_id']);

        Log::info('✅ Query results', [
            'count' => $students->count(),
            'sample' => $students->take(3)->map(function($s) {
                return [
                    'roll_no' => $s->roll_no ?? 'N/A',
                    'name' => $s->student_name ?? $s->name ?? 'N/A',
                    'batch_id' => $s->batch_id ?? 'N/A'
                ];
            })
        ]);

        if ($students->isEmpty()) {
            // Debug: Check if any students exist in this batch at all
            $allStudentsInBatch = $studentModel->where(function($query) use ($batchId) {
                $query->where('batch_id', $batchId)
                      ->orWhere('batch', $batchId);
            })->get(['_id', 'roll_no', 'student_name', 'name', 'batch_id', 'batch']);
            
            Log::warning('🔍 Debug: No students with roll numbers found', [
                'collection' => $collectionName,
                'batch_id_searched' => $batchId,
                'total_students_in_batch' => $allStudentsInBatch->count(),
                'sample_students' => $allStudentsInBatch->take(3)->map(function($s) {
                    return [
                        'name' => $s->student_name ?? $s->name ?? 'N/A',
                        'roll_no' => $s->roll_no ?? 'MISSING',
                        'batch_id' => $s->batch_id ?? 'N/A',
                        'batch' => $s->batch ?? 'N/A',
                        '_id' => (string)$s->_id
                    ];
                })
            ]);
            
            // If students exist but don't have roll numbers
            if ($allStudentsInBatch->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => "Found {$allStudentsInBatch->count()} student(s) in this batch, but they don't have roll numbers assigned. Please run: php artisan students:assign-roll-numbers",
                    'debug_info' => [
                        'collection' => $collectionName,
                        'batch_id' => $batchId,
                        'total_students' => $allStudentsInBatch->count(),
                        'sample_students' => $allStudentsInBatch->take(3)->map(function($s) {
                            return [
                                'name' => $s->student_name ?? $s->name ?? 'N/A',
                                'roll_no' => $s->roll_no ?? 'NOT ASSIGNED',
                                'batch_id' => $s->batch_id ?? $s->batch ?? 'N/A'
                            ];
                        })
                    ]
                ], 422);
            }
            
            // Try fallback to default smstudents collection
            Log::info('🔄 Trying fallback to smstudents collection');
            
            $studentModel->setTable('smstudents');
            $students = $studentModel->where('status', 'active')
                ->where(function($query) use ($batchId) {
                    $query->where('batch_id', $batchId)
                          ->orWhere('batch', $batchId);
                })
                ->where(function($query) use ($courseId) {
                    $query->where('course_id', $courseId)
                          ->orWhere('course', $courseId);
                })
                ->whereNotNull('roll_no')
                ->where('roll_no', '!=', '')
                ->where('roll_no', '!=', 'N/A')
                ->get(['_id', 'roll_no', 'student_name', 'name']);
            
            Log::info('✅ Fallback query results', ['count' => $students->count()]);
            
            if ($students->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => "No students found in this batch with roll numbers.",
                    'debug_info' => [
                        'collections_tried' => [$collectionName, 'smstudents'],
                        'batch_id' => $batchId,
                        'course_id' => $courseId
                    ]
                ], 404);
            }
        }

        // Sort by roll number
        $sortedStudents = $students->sortBy('roll_no');

        return response()->json([
            'success' => true,
            'students' => $sortedStudents->map(function($student) {
                return [
                    '_id' => (string) $student->_id,
                    'roll_no' => $student->roll_no,
                    'name' => $student->student_name ?? $student->name ?? 'N/A'
                ];
            })->values()
        ]);

    } catch (\Exception $e) {
        Log::error('❌ Error loading students: ' . $e->getMessage(), [
            'trace' => $e->getTraceAsString(),
            'batch_id' => $batchId ?? 'unknown',
            'course_id' => $courseId ?? 'unknown',
            'line' => $e->getLine(),
            'file' => $e->getFile()
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
            $batchId = $request->get('batch');
            $rollNo = $request->get('roll_no');
            $startDate = $request->get('start_date');
            $endDate = $request->get('end_date');

            // Validate required fields
            if (!$courseId || !$batchId || !$rollNo || !$startDate || !$endDate) {
                return response()->json([
                    'success' => false,
                    'message' => 'All fields are required'
                ], 400);
            }

            // Get student details - try multiple query approaches
            $student = SMstudents::where('status', 'active')
                ->where('roll_no', $rollNo)
                ->where(function($q) use ($batchId) {
                    $q->where('batch_id', $batchId)
                      ->orWhere('batch', $batchId);
                })
                ->where(function($q) use ($courseId) {
                    $q->where('course_id', $courseId)
                      ->orWhere('course', $courseId);
                })
                ->first();

            // If not found, try simpler query
            if (!$student) {
                $student = SMstudents::where('status', 'active')
                    ->where('roll_no', $rollNo)
                    ->first();
                    
                if (!$student) {
                    Log::warning('⚠️ Student not found', [
                        'roll_no' => $rollNo,
                        'batch_id' => $batchId,
                        'course_id' => $courseId
                    ]);
                    
                    return response()->json([
                        'success' => false,
                        'message' => 'Student not found with roll number: ' . $rollNo
                    ], 404);
                }
            }

            // Get attendance records for date range
            $attendanceRecords = AttendanceRecord::where('student_id', (string) $student->_id)
                ->where('date', '>=', $startDate)
                ->where('date', '<=', $endDate)
                ->orderBy('date', 'desc')
                ->get();

            Log::info('📋 Found attendance records', [
                'count' => $attendanceRecords->count(),
                'student_id' => $student->_id,
                'roll_no' => $student->roll_no
            ]);

            // Calculate statistics
            $totalDays = 0;
            $presentCount = 0;
            $absentCount = 0;
            $notMarkedCount = 0;
            
            // Generate all dates in range
            $start = Carbon::parse($startDate);
            $end = Carbon::parse($endDate);
            $dateRange = [];
            
            $attendanceMap = $attendanceRecords->keyBy('date');
            
            while ($start->lte($end)) {
                $dateStr = $start->format('Y-m-d');
                $dayOfWeek = $start->dayOfWeek;
                
                // Skip weekends
                if ($dayOfWeek != 0 && $dayOfWeek != 6) {
                    $totalDays++;
                    
                    $status = 'not-marked';
                    if (isset($attendanceMap[$dateStr])) {
                        $status = $attendanceMap[$dateStr]->status;
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
                        'marked_at' => isset($attendanceMap[$dateStr]) ? $attendanceMap[$dateStr]->marked_at : null,
                        'marked_by' => isset($attendanceMap[$dateStr]) ? $attendanceMap[$dateStr]->marked_by : null
                    ];
                }
                
                $start->addDay();
            }

            // Calculate attendance percentage
            $attendancePercentage = $totalDays > 0 ? round(($presentCount / $totalDays) * 100, 2) : 0;

            // Get related data
            $batch = Batch::find($batchId);
            $course = Courses::find($courseId);

            // Student info
            $studentInfo = [
                '_id' => (string) $student->_id,
                'roll_no' => $student->roll_no ?? 'N/A',
                'name' => $student->student_name ?? $student->name ?? 'N/A',
                'email' => $student->email ?? 'N/A',
                'batch_name' => $batch ? ($batch->batch_id ?? $batch->name) : 'N/A',
                'course_name' => $course ? $course->name : 'N/A',
                'shift' => $student->shift ?? 'N/A',
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
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to load attendance report: ' . $e->getMessage()
            ], 500);
        }
    }
}