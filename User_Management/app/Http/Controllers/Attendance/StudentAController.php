<?php

namespace App\Http\Controllers\Attendance;

use App\Http\Controllers\Controller;
use App\Models\Attendance\Student as AttendanceRecord;
use App\Models\Student\SMstudents;
use App\Models\Master\Batch;
use App\Models\Master\Branch;
use App\Models\Master\Courses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class StudentAController extends Controller
{
    /**
     * Display the student attendance page (Daily)
     */
    public function index()
    {
        try {
            $branches = Branch::where('status', 'Active')
                ->select('_id', 'name')
                ->get();

            $batches = Batch::where('status', 'Active')
                ->select('_id', 'batch_id', 'name', 'course')
                ->get();

            $courses = Courses::select('_id', 'name')
                ->get();

            Log::info('  Student Attendance Index Loaded', [
                'branches_count' => $branches->count(),
                'batches_count' => $batches->count(),
                'courses_count' => $courses->count()
            ]);

            return view('attendance.student.index', compact('branches', 'batches', 'courses'));
            
        } catch (\Exception $e) {
            Log::error('❌ Error loading student attendance page: ' . $e->getMessage());
            return back()->with('error', 'Failed to load student attendance page');
        }
    }

    /**
     * Get attendance data with filters
     */
    public function getData(Request $request)
    {
        try {
            Log::info('📊 Getting student attendance data', ['filters' => $request->all()]);

            $branch = $request->get('branch');
            $batch = $request->get('batch');
            $course = $request->get('course');
            $date = $request->get('date', date('Y-m-d'));
            $search = $request->get('search');
            $perPage = $request->get('per_page', 10);

            // Build query
            $query = SMstudents::where('status', 'active');

            if ($branch) {
                $query->where('branch', $branch);
            }

            if ($batch) {
                $query->where('batch_id', $batch);
            }

            if ($course) {
                $query->where('course_id', $course);
            }

            if ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('student_name', 'like', "%{$search}%")
                      ->orWhere('name', 'like', "%{$search}%")
                      ->orWhere('roll_no', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            }

            $students = $query->paginate($perPage);

            // Get attendance records for the date
            Log::info('🔍 Looking for attendance records', [
                'date' => $date,
                'student_count' => $students->count()
            ]);

            $attendanceRecords = AttendanceRecord::where('date', $date)
                ->whereIn('student_id', $students->pluck('_id')->map(function($id) {
                    return (string) $id;
                })->toArray())
                ->get();

            Log::info('📋 Found attendance records', [
                'count' => $attendanceRecords->count()
            ]);

            // Create attendance map
            $attendanceMap = [];
            foreach ($attendanceRecords as $record) {
                $studentId = (string) $record->student_id;
                $attendanceMap[$studentId] = $record->status;
            }

            // Format data
            $data = $students->map(function($student) use ($attendanceMap) {
                $studentIdStr = (string) $student->_id;
                $status = $attendanceMap[$studentIdStr] ?? 'not-marked';

                return [
                    '_id' => $studentIdStr,
                    'roll_no' => $student->roll_no ?? 'N/A',
                    'name' => $student->student_name ?? $student->name ?? 'N/A',
                    'email' => $student->email ?? 'N/A',
                    'batch_name' => $student->batch_name ?? ($student->batch->batch_id ?? 'N/A'),
                    'course_name' => $student->course_name ?? ($student->course->name ?? 'N/A'),
                    'shift' => $student->shift->name ?? $student->shift ?? 'N/A',
                    'branch' => $student->branch ?? 'N/A',
                    'status' => $status
                ];
            });

            $statistics = [
                'total' => $data->count(),
                'present' => $data->where('status', 'present')->count(),
                'absent' => $data->where('status', 'absent')->count(),
                'not_marked' => $data->where('status', 'not-marked')->count()
            ];

            Log::info('  Student attendance data retrieved', [
                'count' => $data->count(),
                'statistics' => $statistics
            ]);

            return response()->json([
                'success' => true,
                'data' => $data->values(),
                'statistics' => $statistics,
                'current_page' => $students->currentPage(),
                'last_page' => $students->lastPage(),
                'per_page' => $students->perPage(),
                'total' => $students->total()
            ]);

        } catch (\Exception $e) {
            Log::error('❌ Error getting student attendance data: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to load attendance data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mark individual student attendance
     */
    public function markAttendance(Request $request)
    {
        try {
            $request->validate([
                'student_id' => 'required',
                'status' => 'required|in:present,absent',
                'date' => 'required|date'
            ]);

            $studentId = $request->student_id;
            $status = $request->status;
            $dateString = $request->date;

            Log::info('📝 Marking student attendance', [
                'student_id' => $studentId,
                'status' => $status,
                'date' => $dateString
            ]);

            $student = SMstudents::find($studentId);
            if (!$student) {
                return response()->json([
                    'success' => false,
                    'message' => 'Student not found'
                ], 404);
            }

            // Store attendance
            $attendance = AttendanceRecord::updateOrCreate(
                [
                    'student_id' => $studentId,
                    'date' => $dateString
                ],
                [
                    'student_name' => $student->student_name ?? $student->name,
                    'student_email' => $student->email,
                    'roll_no' => $student->roll_no ?? 'N/A',
                    'batch_id' => (string)($student->batch_id ?? ''),
                    'batch_name' => $student->batch_name ?? ($student->batch->batch_id ?? 'N/A'),
                    'course_id' => (string)($student->course_id ?? ''),
                    'course_name' => $student->course_name ?? ($student->course->name ?? 'N/A'),
                    'shift' => $student->shift->name ?? $student->shift ?? 'N/A',
                    'branch' => $student->branch ?? 'N/A',
                    'status' => $status,
                    'marked_at' => now(),
                    'marked_by' => auth()->user()->name ?? 'System'
                ]
            );

            Log::info('  Student attendance saved', [
                'id' => $attendance->_id,
                'student_id' => $attendance->student_id,
                'status' => $attendance->status
            ]);

            return response()->json([
                'success' => true,
                'message' => ucfirst($status) . " marked successfully",
                'data' => $attendance
            ]);

        } catch (\Exception $e) {
            Log::error('❌ Error marking student attendance: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to mark attendance: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mark all students attendance (bulk)
     */
    public function markAllAttendance(Request $request)
    {
        try {
            $request->validate([
                'status' => 'required|in:present,absent',
                'date' => 'required|date'
            ]);

            $status = $request->status;
            $date = $request->date;
            $branch = $request->get('branch');
            $batch = $request->get('batch');
            $course = $request->get('course');

            Log::info('📝 Marking all student attendance', [
                'status' => $status,
                'date' => $date,
                'filters' => compact('branch', 'batch', 'course')
            ]);

            $query = SMstudents::where('status', 'active');

            if ($branch) $query->where('branch', $branch);
            if ($batch) $query->where('batch_id', $batch);
            if ($course) $query->where('course_id', $course);

            $students = $query->get();

            if ($students->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No students found'
                ], 404);
            }

            $marked = 0;
            foreach ($students as $student) {
                AttendanceRecord::updateOrCreate(
                    [
                        'student_id' => (string) $student->_id,
                        'date' => $date
                    ],
                    [
                        'student_name' => $student->student_name ?? $student->name,
                        'student_email' => $student->email,
                        'roll_no' => $student->roll_no ?? 'N/A',
                        'batch_id' => (string)($student->batch_id ?? ''),
                        'batch_name' => $student->batch_name ?? ($student->batch->batch_id ?? 'N/A'),
                        'course_id' => (string)($student->course_id ?? ''),
                        'course_name' => $student->course_name ?? ($student->course->name ?? 'N/A'),
                        'shift' => $student->shift->name ?? $student->shift ?? 'N/A',
                        'branch' => $student->branch ?? 'N/A',
                        'status' => $status,
                        'marked_at' => now(),
                        'marked_by' => auth()->user()->name ?? 'System'
                    ]
                );
                $marked++;
            }

            Log::info('  Bulk student attendance marked', ['count' => $marked]);

            return response()->json([
                'success' => true,
                'message' => "{$marked} students marked as {$status}",
                'count' => $marked
            ]);

        } catch (\Exception $e) {
            Log::error('❌ Error marking bulk student attendance: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to mark attendance: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display monthly attendance view
     */
    public function monthly()
    {
        try {
            $branches = Branch::where('status', 'Active')
                ->select('_id', 'name')
                ->get();

            $batches = Batch::where('status', 'Active')
                ->select('_id', 'batch_id', 'name')
                ->get();

            $courses = Courses::select('_id', 'name')
                ->get();

            Log::info('  Monthly Student Attendance Page Loaded');

            return view('attendance.student.monthly', compact('branches', 'batches', 'courses'));
            
        } catch (\Exception $e) {
            Log::error('❌ Error loading monthly student attendance page: ' . $e->getMessage());
            return back()->with('error', 'Failed to load monthly attendance page');
        }
    }

    /**
     * Get monthly attendance summary data
     */
    public function getMonthlyData(Request $request)
    {
        try {
            Log::info('📊 Getting monthly student attendance summary', ['filters' => $request->all()]);

            $branch = $request->get('branch');
            $batch = $request->get('batch');
            $course = $request->get('course');
            $month = $request->get('month', date('Y-m'));
            $search = $request->get('search');
            $perPage = $request->get('per_page', 10);

            // Parse month
            $year = (int) substr($month, 0, 4);
            $monthNum = (int) substr($month, 5, 2);
            $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $monthNum, $year);
            
            $firstDate = sprintf('%04d-%02d-01', $year, $monthNum);
            $lastDate = sprintf('%04d-%02d-%02d', $year, $monthNum, $daysInMonth);

            // Calculate total working days (excluding weekends)
            $totalWorkingDays = 0;
            for ($day = 1; $day <= $daysInMonth; $day++) {
                $date = sprintf('%04d-%02d-%02d', $year, $monthNum, $day);
                $dayOfWeek = date('w', strtotime($date));
                if ($dayOfWeek != 0 && $dayOfWeek != 6) {
                    $totalWorkingDays++;
                }
            }

            // Build student query
            $query = SMstudents::where('status', 'active');

            if ($branch) $query->where('branch', $branch);
            if ($batch) $query->where('batch_id', $batch);
            if ($course) $query->where('course_id', $course);

            if ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('student_name', 'like', "%{$search}%")
                      ->orWhere('name', 'like', "%{$search}%")
                      ->orWhere('roll_no', 'like', "%{$search}%");
                });
            }

            $students = $query->paginate($perPage);

            // Get student IDs
            $studentIds = $students->pluck('_id')->map(function($id) {
                return (string) $id;
            })->toArray();

            // Fetch attendance records for the month
            $attendanceRecords = AttendanceRecord::whereIn('student_id', $studentIds)
                ->where('date', '>=', $firstDate)
                ->where('date', '<=', $lastDate)
                ->get();

            // Group by student and count statuses
            $attendanceSummary = [];
            foreach ($attendanceRecords as $record) {
                $studentId = (string) $record->student_id;
                
                if (!isset($attendanceSummary[$studentId])) {
                    $attendanceSummary[$studentId] = [
                        'present' => 0,
                        'absent' => 0
                    ];
                }
                
                if ($record->status === 'present') {
                    $attendanceSummary[$studentId]['present']++;
                } elseif ($record->status === 'absent') {
                    $attendanceSummary[$studentId]['absent']++;
                }
            }

            // Format student data with attendance summary
            $data = $students->map(function($student) use ($attendanceSummary, $totalWorkingDays) {
                $studentIdStr = (string) $student->_id;
                $summary = $attendanceSummary[$studentIdStr] ?? ['present' => 0, 'absent' => 0];

                return [
                    '_id' => $studentIdStr,
                    'roll_no' => $student->roll_no ?? 'N/A',
                    'name' => $student->student_name ?? $student->name ?? 'N/A',
                    'batch_name' => $student->batch_name ?? ($student->batch->batch_id ?? 'N/A'),
                    'course_name' => $student->course_name ?? ($student->course->name ?? 'N/A'),
                    'present_count' => $summary['present'],
                    'absent_count' => $summary['absent'],
                    'total_working_days' => $totalWorkingDays
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $data->values(),
                'current_page' => $students->currentPage(),
                'last_page' => $students->lastPage(),
                'per_page' => $students->perPage(),
                'total' => $students->total()
            ]);

        } catch (\Exception $e) {
            Log::error('❌ Error getting monthly student summary: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to load monthly attendance data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get detailed attendance records for a specific student in a month
     */
    public function monthlyDetails(Request $request)
    {
        try {
            $studentId = $request->get('student_id');
            $month = $request->get('month', date('Y-m'));

            // Parse month
            $year = (int) substr($month, 0, 4);
            $monthNum = (int) substr($month, 5, 2);
            $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $monthNum, $year);

            $firstDate = sprintf('%04d-%02d-01', $year, $monthNum);
            $lastDate = sprintf('%04d-%02d-%02d', $year, $monthNum, $daysInMonth);

            $records = AttendanceRecord::where('student_id', $studentId)
                ->where('date', '>=', $firstDate)
                ->where('date', '<=', $lastDate)
                ->get()
                ->keyBy('date');

            // Generate all days of the month
            $allDays = [];
            $dayNames = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

            for ($day = 1; $day <= $daysInMonth; $day++) {
                $dateStr = sprintf('%04d-%02d-%02d', $year, $monthNum, $day);
                $timestamp = strtotime($dateStr);
                $dayOfWeek = date('w', $timestamp);
                $dayName = $dayNames[$dayOfWeek];
                
                $statusText = 'Not Marked';
                
                if (isset($records[$dateStr])) {
                    $record = $records[$dateStr];
                    $statusText = $record->status === 'present' ? 'Present' : 'Absent';
                } elseif ($dayOfWeek == 0 || $dayOfWeek == 6) {
                    $statusText = 'Weekend';
                }

                $allDays[] = [
                    'date' => $dateStr,
                    'day' => $dayName,
                    'status' => $statusText,
                    'is_weekend' => ($dayOfWeek == 0 || $dayOfWeek == 6)
                ];
            }

            return response()->json([
                'success' => true,
                'data' => $allDays
            ]);

        } catch (\Exception $e) {
            Log::error('❌ Error fetching monthly details: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}