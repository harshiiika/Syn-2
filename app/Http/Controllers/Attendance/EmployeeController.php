<?php

namespace App\Http\Controllers\Attendance;

use App\Http\Controllers\Controller;
use App\Models\Attendance\Employee as AttendanceRecord;
use App\Models\User\User;
use App\Models\Master\Branch;
use App\Models\User\Role;
use App\Models\User\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class EmployeeController extends Controller
{
    /**
     * Display the employee attendance page
     */
    public function index()
    {
        try {
            $branches = Branch::where('status', 'Active')
                ->select('_id', 'name')
                ->get();

            $roles = Role::select('_id', 'name')
                ->get();

            Log::info('  Attendance Index Loaded', [
                'branches_count' => $branches->count(),
                'roles_count' => $roles->count()
            ]);

            return view('attendance.employee.index', compact('branches', 'roles'));
            
        } catch (\Exception $e) {
            Log::error(' Error loading attendance page: ' . $e->getMessage());
            return back()->with('error', 'Failed to load attendance page');
        }
    }

    /**
 * Get attendance data with attendance record retrieval
 */
public function getData(Request $request)
{
    try {
        Log::info('  Getting attendance data', ['filters' => $request->all()]);

        $branch = $request->get('branch');
        $role = $request->get('role');
        $date = $request->get('date', date('Y-m-d'));
        $search = $request->get('search');
        $perPage = $request->get('per_page', 10);

        // Build query
        $query = User::where('status', 'Active');

        if ($branch) {
            $query->where('branch', $branch);
        }

        // Apply role filter
        if ($role) {
            $roleDoc = Role::where('name', $role)->first();
            if ($roleDoc) {
                $query->whereRaw([
                    'roles' => ['$in' => [$roleDoc->_id]]
                ]);
            }
        }

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $employees = $query->paginate($perPage);

        // Get ALL roles for mapping
        $allRoles = Role::all()->keyBy('_id');

        //  Get attendance records with proper date matching
        Log::info(' Looking for attendance records', [
            'date' => $date,
            'employee_count' => $employees->count()
        ]);

        $attendanceRecords = AttendanceRecord::where('date', $date)
            ->whereIn('employee_id', $employees->pluck('_id')->map(function($id) {
                return (string) $id;
            })->toArray())
            ->get();

        Log::info('  Found attendance records', [
            'count' => $attendanceRecords->count(),
            'records' => $attendanceRecords->pluck('employee_id', 'status')->toArray()
        ]);

        // Create a map of employee_id => status
        $attendanceMap = [];
        foreach ($attendanceRecords as $record) {
            $empId = (string) $record->employee_id;
            $attendanceMap[$empId] = $record->status;
            Log::info('  Mapping attendance', [
                'employee_id' => $empId,
                'status' => $record->status
            ]);
        }

        // Format data with role extraction and attendance lookup
        $data = $employees->map(function($employee) use ($allRoles, $attendanceMap) {
            // Extract role names
            $roleNames = [];
            
            if (isset($employee->roles) && is_array($employee->roles)) {
                foreach ($employee->roles as $roleId) {
                    $roleIdStr = (string) $roleId;
                    if (isset($allRoles[$roleIdStr])) {
                        $roleNames[] = $allRoles[$roleIdStr]->name;
                    }
                }
            }

            $roleDisplay = !empty($roleNames) ? implode(', ', $roleNames) : 'N/A';

            //   Get attendance status from the map
            $employeeIdStr = (string) $employee->_id;
            $status = $attendanceMap[$employeeIdStr] ?? 'not-marked';

            Log::info('  Employee data', [
                'id' => $employeeIdStr,
                'name' => $employee->name,
                'status' => $status
            ]);

            return [
                '_id' => $employeeIdStr,
                'name' => $employee->name,
                'email' => $employee->email,
                'role' => $roleDisplay,
                'branch' => $employee->branch ?? 'N/A',
                'status' => $status
            ];
        });

        $statistics = [
            'total' => $data->count(),
            'present' => $data->where('status', 'present')->count(),
            'absent' => $data->where('status', 'absent')->count(),
            'not_marked' => $data->where('status', 'not-marked')->count()
        ];

        Log::info('  Data retrieved successfully', [
            'count' => $data->count(),
            'statistics' => $statistics
        ]);

        return response()->json([
            'success' => true,
            'data' => $data->values(),
            'statistics' => $statistics,
            'current_page' => $employees->currentPage(),
            'last_page' => $employees->lastPage(),
            'per_page' => $employees->perPage(),
            'total' => $employees->total()
        ]);

    } catch (\Exception $e) {
        Log::error(' Error getting attendance data: ' . $e->getMessage(), [
            'trace' => $e->getTraceAsString()
        ]);
        
        return response()->json([
            'success' => false,
            'message' => 'Failed to load attendance data: ' . $e->getMessage()
        ], 500);
    }
}

    /**
 * Mark individual employee attendance
 */
public function markAttendance(Request $request)
{
    try {
        $request->validate([
            'employee_id' => 'required',
            'status' => 'required|in:present,absent',
            'date' => 'required|date'
        ]);

        $employeeId = $request->employee_id;
        $status = $request->status;
        $dateString = $request->date; // e.g., "2025-11-17"

        Log::info('  Marking attendance', [
            'employee_id' => $employeeId,
            'status' => $status,
            'date' => $dateString,
            'date_type' => gettype($dateString)
        ]);

        $employee = User::find($employeeId);
        if (!$employee) {
            return response()->json([
                'success' => false,
                'message' => 'Employee not found'
            ], 404);
        }

        // Get ALL role names
        $roleNames = [];
        if (isset($employee->roles) && is_array($employee->roles)) {
            $roles = Role::whereIn('_id', $employee->roles)->get();
            $roleNames = $roles->pluck('name')->toArray();
        }
        $roleDisplay = !empty($roleNames) ? implode(', ', $roleNames) : 'N/A';

        // Get department names
        $deptNames = [];
        if (isset($employee->departments) && is_array($employee->departments)) {
            $depts = Department::whereIn('_id', $employee->departments)->get();
            $deptNames = $depts->pluck('name')->toArray();
        }
        $deptDisplay = !empty($deptNames) ? implode(', ', $deptNames) : 'N/A';

        //   Store date as string, not DateTime
        $attendance = AttendanceRecord::updateOrCreate(
            [
                'employee_id' => $employeeId,
                'date' => $dateString  // Store as string: "2025-11-17"
            ],
            [
                'employee_name' => $employee->name,
                'employee_email' => $employee->email,
                'role' => $roleDisplay,
                'department' => $deptDisplay,
                'branch' => $employee->branch ?? 'N/A',
                'status' => $status,
                'marked_at' => now(),
                'marked_by' => auth()->user()->name ?? 'System'
            ]
        );

        Log::info('  Attendance saved', [
            'id' => $attendance->_id,
            'employee_id' => $attendance->employee_id,
            'date' => $attendance->date,
            'date_type' => gettype($attendance->date),
            'status' => $attendance->status
        ]);

        //   VERIFICATION: Try to read it back immediately
        $verify = AttendanceRecord::where('employee_id', $employeeId)
            ->where('date', $dateString)
            ->first();

        Log::info(' Verification check', [
            'found' => $verify ? 'YES' : 'NO',
            'verify_id' => $verify ? $verify->_id : null
        ]);

        return response()->json([
            'success' => true,
            'message' => ucfirst($status) . " marked successfully",
            'data' => $attendance
        ]);

    } catch (\Exception $e) {
        Log::error(' Error marking attendance: ' . $e->getMessage(), [
            'trace' => $e->getTraceAsString()
        ]);
        
        return response()->json([
            'success' => false,
            'message' => 'Failed to mark attendance: ' . $e->getMessage()
        ], 500);
    }
}

    /**
     * Mark all employees attendance
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
            $role = $request->get('role');

            Log::info('  Marking all attendance', [
                'status' => $status,
                'date' => $date,
                'branch' => $branch,
                'role' => $role
            ]);

            $query = User::where('status', 'Active');

            if ($branch) {
                $query->where('branch', $branch);
            }

            if ($role) {
                $roleDoc = Role::where('name', $role)->first();
                if ($roleDoc) {
                    $query->whereRaw([
                        'roles' => ['$in' => [$roleDoc->_id]]
                    ]);
                }
            }

            $employees = $query->get();

            if ($employees->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No employees found'
                ], 404);
            }

            $allRoles = Role::all()->keyBy('_id');
            $allDepts = Department::all()->keyBy('_id');

            $marked = 0;
            foreach ($employees as $employee) {
                // Get role names
                $roleNames = [];
                if (isset($employee->roles) && is_array($employee->roles)) {
                    foreach ($employee->roles as $roleId) {
                        $roleIdStr = (string) $roleId;
                        if (isset($allRoles[$roleIdStr])) {
                            $roleNames[] = $allRoles[$roleIdStr]->name;
                        }
                    }
                }
                $roleDisplay = !empty($roleNames) ? implode(', ', $roleNames) : 'N/A';

                // Get department names
                $deptNames = [];
                if (isset($employee->departments) && is_array($employee->departments)) {
                    foreach ($employee->departments as $deptId) {
                        $deptIdStr = (string) $deptId;
                        if (isset($allDepts[$deptIdStr])) {
                            $deptNames[] = $allDepts[$deptIdStr]->name;
                        }
                    }
                }
                $deptDisplay = !empty($deptNames) ? implode(', ', $deptNames) : 'N/A';

                AttendanceRecord::updateOrCreate(
                    [
                        'employee_id' => (string) $employee->_id,
                        'date' => $date
                    ],
                    [
                        'employee_name' => $employee->name,
                        'employee_email' => $employee->email,
                        'role' => $roleDisplay,
                        'department' => $deptDisplay,
                        'branch' => $employee->branch ?? 'N/A',
                        'status' => $status,
                        'marked_at' => now(),
                        'marked_by' => auth()->user()->name ?? 'System'
                    ]
                );
                $marked++;
            }

            Log::info('  Bulk attendance marked', ['count' => $marked]);

            return response()->json([
                'success' => true,
                'message' => "{$marked} employees marked as {$status}",
                'count' => $marked
            ]);

        } catch (\Exception $e) {
            Log::error(' Error marking bulk attendance: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to mark attendance: ' . $e->getMessage()
            ], 500);
        }
    }


/**
 * Display monthly attendance view (simple table format)
 */
  public function monthly()
    {
        try {
            $branches = Branch::where('status', 'Active')
                ->select('_id', 'name')
                ->get();

            $roles = Role::select('_id', 'name')
                ->get();

            Log::info('  Monthly Attendance Page Loaded');

            return view('attendance.employee.monthly', compact('branches', 'roles'));
            
        } catch (\Exception $e) {
            Log::error(' Error loading monthly attendance page: ' . $e->getMessage());
            return back()->with('error', 'Failed to load monthly attendance page');
        }
    }

/**
 * Get monthly attendance summary data (simple aggregated view)
 */
 public function getMonthlyData(Request $request)
    {
        try {
            Log::info('  Getting monthly attendance summary', ['filters' => $request->all()]);

            $branch = $request->get('branch');
            $role = $request->get('role');
            $month = $request->get('month', date('Y-m')); // Format: "2025-11"
            $search = $request->get('search');
            $perPage = $request->get('per_page', 10);

            // Parse month to get date range
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
                if ($dayOfWeek != 0 && $dayOfWeek != 6) { // Not Sunday or Saturday
                    $totalWorkingDays++;
                }
            }

            // Build employee query
            $query = User::where('status', 'Active');

            if ($branch) {
                $query->where('branch', $branch);
            }

            if ($role) {
                $roleDoc = Role::where('name', $role)->first();
                if ($roleDoc) {
                    $query->whereRaw([
                        'roles' => ['$in' => [$roleDoc->_id]]
                    ]);
                }
            }

            if ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            }

            $employees = $query->paginate($perPage);

            // Get all roles for mapping
            $allRoles = Role::all()->keyBy('_id');

            // Get employee IDs
            $employeeIds = $employees->pluck('_id')->map(function($id) {
                return (string) $id;
            })->toArray();

            // Fetch attendance records for the month
            $attendanceRecords = AttendanceRecord::whereIn('employee_id', $employeeIds)
                ->where('date', '>=', $firstDate)
                ->where('date', '<=', $lastDate)
                ->get();

            Log::info('  Found attendance records', [
                'count' => $attendanceRecords->count(),
                'date_range' => $firstDate . ' to ' . $lastDate
            ]);

            // Group by employee and count statuses
            $attendanceSummary = [];
            foreach ($attendanceRecords as $record) {
                $empId = (string) $record->employee_id;
                
                if (!isset($attendanceSummary[$empId])) {
                    $attendanceSummary[$empId] = [
                        'present' => 0,
                        'absent' => 0
                    ];
                }
                
                if ($record->status === 'present') {
                    $attendanceSummary[$empId]['present']++;
                } elseif ($record->status === 'absent') {
                    $attendanceSummary[$empId]['absent']++;
                }
            }

            // Format employee data with attendance summary
            $data = $employees->map(function($employee) use ($allRoles, $attendanceSummary, $totalWorkingDays) {
                // Extract role names
                $roleNames = [];
                if (isset($employee->roles) && is_array($employee->roles)) {
                    foreach ($employee->roles as $roleId) {
                        $roleIdStr = (string) $roleId;
                        if (isset($allRoles[$roleIdStr])) {
                            $roleNames[] = $allRoles[$roleIdStr]->name;
                        }
                    }
                }
                $roleDisplay = !empty($roleNames) ? implode(', ', $roleNames) : 'N/A';

                $employeeIdStr = (string) $employee->_id;
                $summary = $attendanceSummary[$employeeIdStr] ?? ['present' => 0, 'absent' => 0];

                return [
                    '_id' => $employeeIdStr,
                    'name' => $employee->name,
                    'email' => $employee->email,
                    'role' => $roleDisplay,
                    'branch' => $employee->branch ?? 'N/A',
                    'present_count' => $summary['present'],
                    'absent_count' => $summary['absent'],
                    'total_working_days' => $totalWorkingDays
                ];
            });

            Log::info('  Monthly summary processed', [
                'employee_count' => $data->count(),
                'working_days' => $totalWorkingDays
            ]);

            return response()->json([
                'success' => true,
                'data' => $data->values(),
                'current_page' => $employees->currentPage(),
                'last_page' => $employees->lastPage(),
                'per_page' => $employees->perPage(),
                'total' => $employees->total()
            ]);

        } catch (\Exception $e) {
            Log::error(' Error getting monthly summary: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to load monthly attendance data: ' . $e->getMessage()
            ], 500);
        }
    }

/**
 * Get detailed attendance records for a specific employee in a month
 */
   public function monthlyDetails(Request $request)
    {
        try {
            $employeeId = $request->get('employee_id');
            $month = $request->get('month', date('Y-m'));

            // Parse month
            $year = (int) substr($month, 0, 4);
            $monthNum = (int) substr($month, 5, 2);
            $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $monthNum, $year);

            Log::info(' Fetching monthly details', [
                'employee_id' => $employeeId,
                'month' => $month,
                'days_in_month' => $daysInMonth
            ]);

            // Get all attendance records for this employee in this month
            $firstDate = sprintf('%04d-%02d-01', $year, $monthNum);
            $lastDate = sprintf('%04d-%02d-%02d', $year, $monthNum, $daysInMonth);

            $records = AttendanceRecord::where('employee_id', $employeeId)
                ->where('date', '>=', $firstDate)
                ->where('date', '<=', $lastDate)
                ->get()
                ->keyBy('date'); // Key by date for easy lookup

            Log::info('  Found records', [
                'count' => $records->count(),
                'dates' => $records->keys()->toArray()
            ]);

            // Generate all days of the month
            $allDays = [];
            $dayNames = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

            for ($day = 1; $day <= $daysInMonth; $day++) {
                $dateStr = sprintf('%04d-%02d-%02d', $year, $monthNum, $day);
                $timestamp = strtotime($dateStr);
                $dayOfWeek = date('w', $timestamp);
                $dayName = $dayNames[$dayOfWeek];
                
                // Format date nicely
                $formattedDate = date('Y-m-d', $timestamp);
                
                // Check if there's an attendance record
                $status = 'N'; // Default: Not Marked
                $statusText = 'Not Marked';
                
                if (isset($records[$dateStr])) {
                    $record = $records[$dateStr];
                    $status = $record->status === 'present' ? 'Present' : 'Absent';
                    $statusText = $status;
                }
                
                // If it's a weekend, mark as such
                if ($dayOfWeek == 0 || $dayOfWeek == 6) {
                    // Weekend - don't change status if already marked
                    if ($status === 'Not Marked') {
                        $statusText = 'Weekend';
                    }
                }

                $allDays[] = [
                    'date' => $formattedDate,
                    'day' => $dayName,
                    'status' => $statusText,
                    'is_weekend' => ($dayOfWeek == 0 || $dayOfWeek == 6)
                ];
            }

            Log::info('  Generated full month calendar', [
                'total_days' => count($allDays),
                'marked_days' => $records->count()
            ]);

            return response()->json([
                'success' => true,
                'data' => $allDays
            ]);

        } catch (\Exception $e) {
            Log::error(' Error fetching monthly details: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}