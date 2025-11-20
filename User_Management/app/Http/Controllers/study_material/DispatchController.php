<?php

namespace App\Http\Controllers\study_material;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\study_material\Dispatch;
use App\Models\Student\Student;  // ✅ Correct path based on your structure

class DispatchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            // Get unique courses from students
            $courses = Student::select('course_id', 'course_name')
                ->distinct()
                ->whereNotNull('course_id')
                ->whereNotNull('course_name')
                ->get()
                ->map(function($student) {
                    return [
                        '_id' => $student->course_id,
                        'name' => $student->course_name
                    ];
                })
                ->unique('_id')
                ->values();
            
            // Get recent dispatch records
            $recentDispatches = Dispatch::orderBy('dispatched_at', 'desc')
                ->take(20)
                ->get();
            
            return view('study_material.Dispatch', compact('courses', 'recentDispatches'));
        } catch (\Exception $e) {
            return back()->with('error', 'Error loading page: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $dispatch = Dispatch::findOrFail($id);
            $dispatch->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Dispatch record deleted successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting record: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get batches based on selected course
     */
    public function getBatches(Request $request)
    {
        try {
            $courseId = $request->input('course_id');
            
            if (!$courseId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Course ID is required'
                ], 400);
            }
            
            // Get unique batches from students for the selected course
            $batches = Student::select('batch_id', 'batch_name')
                ->where('course_id', $courseId)
                ->whereNotNull('batch_id')
                ->whereNotNull('batch_name')
                ->distinct()
                ->get()
                ->map(function($student) {
                    return [
                        '_id' => $student->batch_id,
                        'name' => $student->batch_name
                    ];
                })
                ->unique('_id')
                ->values();
            
            return response()->json([
                'success' => true,
                'batches' => $batches
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching batches: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get students based on selected course and batch
     */
    public function getStudents(Request $request)
    {
        try {
            $courseId = $request->input('course_id');
            $batchId = $request->input('batch_id');
            
            // Build query
            $query = Student::query();
            
            if ($courseId) {
                $query->where('course_id', $courseId);
            }
            
            if ($batchId) {
                $query->where('batch_id', $batchId);
            }
            
            // Get students with required fields
            $students = $query->orderBy('roll_no', 'asc')
                ->get([
                    '_id',
                    'roll_no',
                    'name',
                    'father_name',
                    'batch_id',
                    'batch_name',
                    'course_id',
                    'course_name'
                ]);
            
            // Check dispatch status for each student
            $students->transform(function ($student) {
                $student->is_dispatched = Dispatch::where('student_id', $student->_id)->exists();
                return $student;
            });
            
            return response()->json([
                'success' => true,
                'students' => $students,
                'total_count' => $students->count()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching students: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Dispatch study material to selected students
     */
    public function dispatchMaterial(Request $request)
    {
        try {
            $request->validate([
                'student_ids' => 'required|array',
                'student_ids.*' => 'required|string',
            ]);

            $studentIds = $request->input('student_ids');
            $dispatchedCount = 0;
            $alreadyDispatchedCount = 0;
            $errors = [];

            foreach ($studentIds as $studentId) {
                // Check if already dispatched
                $existingDispatch = Dispatch::where('student_id', $studentId)->first();
                
                if ($existingDispatch) {
                    $alreadyDispatchedCount++;
                    continue;
                }
                
                $student = Student::find($studentId);
                
                if ($student) {
                    Dispatch::create([
                        'student_id' => $studentId,
                        'roll_no' => $student->roll_no,
                        'student_name' => $student->name,
                        'father_name' => $student->father_name,
                        'batch_id' => $student->batch_id,
                        'batch_name' => $student->batch_name ?? 'N/A',
                        'course_id' => $student->course_id,
                        'course_name' => $student->course_name ?? 'N/A',
                        'dispatched_at' => now(),
                        'dispatched_by' => auth()->user()->name ?? 'Admin',
                    ]);
                    
                    $dispatchedCount++;
                } else {
                    $errors[] = "Student with ID {$studentId} not found";
                }
            }

            $message = "Study material dispatched to {$dispatchedCount} student(s) successfully!";
            
            if ($alreadyDispatchedCount > 0) {
                $message .= " {$alreadyDispatchedCount} student(s) were already dispatched.";
            }
            
            if (count($errors) > 0) {
                $message .= " Errors: " . implode(', ', $errors);
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'dispatched_count' => $dispatchedCount,
                'already_dispatched_count' => $alreadyDispatchedCount,
                'error_count' => count($errors)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error dispatching material: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get dispatch history/records
     */
    public function getDispatchHistory(Request $request)
    {
        try {
            $query = Dispatch::query();
            
            // Apply filters if provided
            if ($request->has('course_id') && $request->course_id) {
                $query->where('course_id', $request->course_id);
            }
            
            if ($request->has('batch_id') && $request->batch_id) {
                $query->where('batch_id', $request->batch_id);
            }
            
            if ($request->has('search') && $request->search) {
                $searchTerm = $request->search;
                $query->where(function($q) use ($searchTerm) {
                    $q->where('student_name', 'like', "%{$searchTerm}%")
                      ->orWhere('roll_no', 'like', "%{$searchTerm}%")
                      ->orWhere('father_name', 'like', "%{$searchTerm}%");
                });
            }
            
            // Order by most recent first
            $dispatches = $query->orderBy('dispatched_at', 'desc')
                ->paginate(50);
            
            return response()->json([
                'success' => true,
                'dispatches' => $dispatches
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching dispatch history: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk delete dispatch records
     */
    public function bulkDelete(Request $request)
    {
        try {
            $request->validate([
                'dispatch_ids' => 'required|array',
                'dispatch_ids.*' => 'required|string',
            ]);

            $dispatchIds = $request->input('dispatch_ids');
            $deletedCount = Dispatch::whereIn('_id', $dispatchIds)->delete();
            
            return response()->json([
                'success' => true,
                'message' => "{$deletedCount} dispatch record(s) deleted successfully!",
                'deleted_count' => $deletedCount
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting records: ' . $e->getMessage()
            ], 500);
        }
    }
}