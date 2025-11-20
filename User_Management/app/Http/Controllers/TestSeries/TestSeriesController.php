<?php

namespace App\Http\Controllers\TestSeries;

use App\Models\TestSeries\TestSeries;
use App\Models\Master\Courses;
use App\Models\Student\SMstudents;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;

class TestSeriesController extends Controller
{
    /**
     * Display Test Master page (Image 1)
     */
    public function index()
    {
        try {
            Log::info('=== Test Series Index - START ===');
            
            $courses = Courses::where('status', 'active')->get();
            Log::info('Courses Count: ' . $courses->count());

            $testMasters = $courses->map(function ($course) {
                $courseId = is_object($course->_id) ? (string)$course->_id : $course->_id;
                
                return [
                    'id' => $courseId,
                    'name' => $course->course_name ?? $course->name,
                    'type1_count' => TestSeries::where('course_id', $courseId)
                        ->where('test_type', 'Type1')
                        ->count(),
                    'type2_count' => TestSeries::where('course_id', $courseId)
                        ->where('test_type', 'Type2')
                        ->count(),
                ];
            });

            return view('test_series.index', compact('testMasters'));

        } catch (\Exception $e) {
            Log::error('Test Series Index ERROR: ' . $e->getMessage());
            return response()->view('errors.500', ['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display Test Series for a specific course (Image 2)
     */
    public function show($courseName)
    {
        try {
            Log::info('=== Test Series Show - START ===', ['courseName' => $courseName]);
            
            $courseName = urldecode($courseName);
            $course = Courses::where('course_name', $courseName)
                ->orWhere('name', $courseName)
                ->first();

            if (!$course) {
                return redirect()->route('test_series.index')
                    ->with('error', 'Course not found');
            }

            $courseId = is_object($course->_id) ? (string)$course->_id : $course->_id;

            $testSeries = TestSeries::where('course_id', $courseId)
                ->orderBy('created_at', 'desc')
                ->get();
            
            // Get course subjects for creation
            $courseSubjects = $course->subjects ?? [];
            
            return view('test_series.detail', compact('course', 'testSeries', 'courseName', 'courseSubjects'));

        } catch (\Exception $e) {
            Log::error('Test Series Show ERROR: ' . $e->getMessage());
            return redirect()->route('test_series.index')
                ->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Store new test series (Image 3 - Create Modal)
     */
    public function store(Request $request)
    {
        try {
            Log::info('=== Test Series Store - START ===', $request->all());
            
            $rules = [
                'course_id' => 'required|string',
                'test_type' => 'required|in:Type1,Type2',
                'subject_type' => 'required|in:Single,Double',
                'test_count' => 'required|integer|min:1',
                'subjects' => 'required|array|min:1',
            ];

            if ($request->test_type === 'Type1') {
                $rules['test_series_name'] = 'required|string|max:255';
            }

            $validated = $request->validate($rules);

            $course = Courses::find($validated['course_id']);
            if (!$course) {
                return back()->withInput()
                    ->with('error', 'Course not found!');
            }

            $courseName = $course->course_name ?? $course->name;
            
            // Get next test number for this course
            $lastTest = TestSeries::where('course_id', $validated['course_id'])
                ->where('test_type', $validated['test_type'])
                ->orderBy('test_number', 'desc')
                ->first();
            
            $testNumber = ($lastTest ? $lastTest->test_number : 0) + 1;

            // Build test name
            if ($request->test_type === 'Type1') {
                $testName = $courseName . '/Type1/' . $validated['test_series_name'] . '/' . str_pad($testNumber, 3, '0', STR_PAD_LEFT);
            } else {
                $testName = $courseName . '/Type2/' . str_pad($testNumber, 3, '0', STR_PAD_LEFT);
            }

            // Get students enrolled in this course
            $students = SMstudents::where('course_id', $validated['course_id'])
                ->where('status', 'active')
                ->pluck('_id')
                ->toArray();

            $testSeriesData = [
                'course_id' => $validated['course_id'],
                'course_name' => $courseName,
                'test_name' => $testName,
                'test_type' => $validated['test_type'],
                'subject_type' => $validated['subject_type'],
                'test_count' => $validated['test_count'],
                'test_number' => $testNumber,
                'status' => 'Pending',
                'subjects' => $validated['subjects'],
                'students_enrolled' => $students,
                'students_count' => count($students),
                'created_by' => Auth::check() ? Auth::user()->name : 'Admin',
            ];

            if ($request->test_type === 'Type1') {
                $testSeriesData['test_series_name'] = $validated['test_series_name'];
            }

            $testSeries = TestSeries::create($testSeriesData);

            Log::info('Test Series Created Successfully', ['id' => $testSeries->_id]);

            return redirect()
                ->route('test_series.show', urlencode($courseName))
                ->with('success', 'Test Series created successfully!');

        } catch (\Exception $e) {
            Log::error('Test Series Store ERROR: ' . $e->getMessage());
            return back()->withInput()
                ->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Get test details for edit modal (Image 4) - NOT USED, using direct modal
     */
    public function edit($id)
    {
        try {
            $testSeries = TestSeries::findOrFail($id);
            $course = Courses::find($testSeries->course_id);
            
            if (!$course) {
                return response()->json([
                    'success' => false,
                    'message' => 'Course not found'
                ], 404);
            }

            // Get course subjects with marks
            $subjects = $course->subjects ?? [];
            $subjectMarks = $testSeries->subject_marks ?? [];

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => (string)$testSeries->_id,
                    'test_type' => $testSeries->test_type,
                    'test_series_name' => $testSeries->test_series_name ?? '',
                    'subjects' => $testSeries->subjects,
                    'subject_type' => $testSeries->subject_type,
                    'available_subjects' => $subjects,
                    'subject_marks' => $subjectMarks,
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Test Edit ERROR: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update test series (Image 4 - Edit Modal)
     */
    public function update(Request $request, $id)
    {
        try {
            Log::info('=== Test Series Update - START ===', ['id' => $id]);
            
            $testSeries = TestSeries::findOrFail($id);

            $validated = $request->validate([
                'test_series_name' => 'nullable|string|max:255',
                'subjects' => 'required|array|min:1',
                'subject_marks' => 'nullable|array',
                'status' => 'nullable|in:Pending,Active,Completed',
            ]);

            $updateData = [
                'subjects' => $validated['subjects'],
                'updated_by' => Auth::check() ? Auth::user()->name : 'Admin',
            ];

            if (isset($validated['test_series_name'])) {
                $updateData['test_series_name'] = $validated['test_series_name'];
            }

            if (isset($validated['subject_marks'])) {
                $updateData['subject_marks'] = $validated['subject_marks'];
            }

            if (isset($validated['status'])) {
                $updateData['status'] = $validated['status'];
            }

            $testSeries->update($updateData);

            return back()->with('success', 'Test Series updated successfully!');

        } catch (\Exception $e) {
            Log::error('Test Series Update ERROR: ' . $e->getMessage());
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Delete test series
     */
    public function destroy($id)
    {
        try {
            $testSeries = TestSeries::findOrFail($id);
            $testSeries->delete();

            return back()->with('success', 'Test Series deleted successfully!');

        } catch (\Exception $e) {
            Log::error('Test Series Destroy ERROR: ' . $e->getMessage());
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Get subjects for a course (AJAX)
     */
    public function getCourseSubjects($courseId)
    {
        try {
            $course = Courses::find($courseId);
            
            if (!$course) {
                return response()->json([
                    'success' => false,
                    'message' => 'Course not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'subjects' => $course->subjects ?? []
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }


    /**
     * Filters students by the course_id of the test series
     */
    public function viewStudents($id)
    {
        try {
            // ⭐ STEP 1: Log the request
            Log::info('=== View Students - START ===', [
                'test_series_id' => $id,
                'url' => request()->url()
            ]);
            
            // ⭐ STEP 2: Find the test series
            $testSeries = TestSeries::find($id);
            
            if (!$testSeries) {
                Log::error('Test Series NOT FOUND', ['id' => $id]);
                return back()->with('error', 'Test Series not found with ID: ' . $id);
            }
            
            Log::info('✅ Test Series Found', [
                'test_name' => $testSeries->test_name,
                'course_id' => $testSeries->course_id,
                'course_name' => $testSeries->course_name
            ]);
            
            // ⭐ STEP 3: Get students enrolled in this course
            $students = SMstudents::with(['batch', 'course', 'shift'])
                ->where('course_id', $testSeries->course_id)
                ->where('status', 'active')
                ->orderBy('roll_no', 'asc')
                ->get();
            
            Log::info('✅ Students Query Executed', [
                'count' => $students->count(),
                'course_id' => $testSeries->course_id
            ]);
            
            // ⭐ STEP 4: Check if view file exists
            $viewPath = resource_path('views/test_series/students.blade.php');
            if (!file_exists($viewPath)) {
                Log::error('❌ VIEW FILE NOT FOUND', ['path' => $viewPath]);
                return back()->with('error', 'View file not found at: ' . $viewPath);
            }
            
            Log::info('✅ View file exists', ['path' => $viewPath]);
            
            // ⭐ STEP 5: If no students found, still show page with message
            if ($students->isEmpty()) {
                Log::warning('⚠️ No students found for course', [
                    'course_id' => $testSeries->course_id,
                    'course_name' => $testSeries->course_name
                ]);
            }
            
            // ⭐ STEP 6: Return the view
            Log::info('✅ Returning view: test_series.students');
            return view('test_series.students', compact('testSeries', 'students'));
            
        } catch (\Exception $e) {
            Log::error('❌ View Students ERROR', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'test_series_id' => $id
            ]);
            
            return back()->with('error', 'Error loading students: ' . $e->getMessage());
        }
    }
}