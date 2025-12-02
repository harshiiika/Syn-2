<?php

namespace App\Http\Controllers\TestSeries;

use App\Exports\TestResultTemplateExport;
use App\Models\TestSeries\TestSeries;
use App\Models\TestSeries\Test;
use App\Models\Master\Courses;
use App\Models\Student\SMstudents;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\TestResultImport;
use App\Models\Master\Batch;
use Carbon\Carbon;  


class TestSeriesController extends Controller
{
    /**
     * Display Test Master page (Image 1)
     */
    public function index()
    {
        try {
            Log::info('=== Test Series Index - START ===');
            
            // Get ALL active courses from Courses collection
            $courses = Courses::where('status', 'active')
                ->orderBy('course_name', 'asc')
                ->get();
            
            Log::info('Courses Count: ' . $courses->count());

            // Build dynamic test masters array
            $testMasters = $courses->map(function ($course) {
                $courseId = is_object($course->_id) ? (string)$course->_id : $course->_id;
                $courseName = $course->course_name ?? $course->name;
                
                // Count Type1 and Type2 test series for this course
                $type1Count = TestSeries::where('course_id', $courseId)
                    ->where('test_type', 'Type1')
                    ->count();
                
                $type2Count = TestSeries::where('course_id', $courseId)
                    ->where('test_type', 'Type2')
                    ->count();
                
                Log::info("Course: {$courseName}", [
                    'id' => $courseId,
                    'type1_count' => $type1Count,
                    'type2_count' => $type2Count
                ]);
                
                return [
                    'id' => $courseId,
                    'name' => $courseName,
                    'course_type' => $course->course_type ?? 'N/A',
                    'class_name' => $course->class_name ?? 'N/A',
                    'type1_count' => $type1Count,
                    'type2_count' => $type2Count,
                ];
            });

            Log::info('Test Masters Generated: ' . $testMasters->count());

            return view('test_series.index', compact('testMasters'));

        } catch (\Exception $e) {
            Log::error('Test Series Index ERROR: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->view('errors.500', [
                'error' => $e->getMessage()
            ], 500);
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
            
            // Find course by name
            $course = Courses::where('course_name', $courseName)
                ->orWhere('name', $courseName)
                ->first();

            if (!$course) {
                return redirect()->route('test_series.index')
                    ->with('error', 'Course not found: ' . $courseName);
            }

            $courseId = is_object($course->_id) ? (string)$course->_id : $course->_id;

            // Get all test series for this course
            $testSeries = TestSeries::where('course_id', $courseId)
                ->orderBy('created_at', 'desc')
                ->get();
            
            // Get course subjects for creation
            $courseSubjects = $course->subjects ?? [];
            
            Log::info('Test Series Found: ' . $testSeries->count(), [
                'course_id' => $courseId,
                'course_name' => $courseName
            ]);
            
            return view('test_series.detail', compact(
                'course', 
                'testSeries', 
                'courseName', 
                'courseSubjects'
                        ));

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

            // ⭐ FIX: Always get course name properly
            $courseName = $course->course_name ?? $course->name;
            
            Log::info('Course details', [
                'course_id' => $validated['course_id'],
                'course_name' => $courseName
            ]);
            
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
                'course_name' => $courseName, // ⭐ FIX: Always set course_name
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
                'subject_type' => 'required|in:Single,Double',
                'status' => 'nullable|in:Pending,Active,Completed',
            ]);

            $updateData = [
                'subjects' => $validated['subjects'],
                'subject_type' => $validated['subject_type'],
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

            Log::info('Test Series Updated Successfully');

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
            $courseName = $testSeries->course_name;
            $testSeries->delete();

            return redirect()
                ->route('test_series.show', urlencode($courseName))
                ->with('success', 'Test Series deleted successfully!');

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
            Log::info('=== View Students - START ===', ['test_series_id' => $id]);
            
            $testSeries = TestSeries::find($id);
            
            if (!$testSeries) {
                Log::error('Test Series NOT FOUND', ['id' => $id]);
                return back()->with('error', 'Test Series not found');
            }
            
            Log::info('✅ Test Series Found', [
                'test_name' => $testSeries->test_name,
                'course_id' => $testSeries->course_id,
                'course_name' => $testSeries->course_name
            ]);
            
            // Get students enrolled in this course
            $students = SMstudents::with(['batch', 'course', 'shift'])
                ->where('course_id', $testSeries->course_id)
                ->where('status', 'active')
                ->orderBy('roll_no', 'asc')
                ->get();
            
            Log::info('✅ Students Query Executed', [
                'count' => $students->count(),
                'course_id' => $testSeries->course_id
            ]);
            
            if ($students->isEmpty()) {
                Log::warning('⚠️ No students found for course', [
                    'course_id' => $testSeries->course_id,
                    'course_name' => $testSeries->course_name
                ]);
            }
            
            return view('test_series.students', compact('testSeries', 'students'));
            
        } catch (\Exception $e) {
            Log::error('❌ View Students ERROR', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'test_series_id' => $id
            ]);
            
            return back()->with('error', 'Error loading students: ' . $e->getMessage());
        }
    }

    
    /**
     * Lock the result (prevents further uploads)
     */
    public function lockResult($id)
    {
        try {
            Log::info('=== Lock Result - START ===', ['test_series_id' => $id]);
            
            $testSeries = TestSeries::findOrFail($id);
            
            // Check if result is already locked
            if ($testSeries->result_locked) {
                return back()->with('error', 'Result is already locked.');
            }
            
            // Check if result has been uploaded
            if (!$testSeries->result_uploaded) {
                return back()->with('error', 'Please upload result before locking.');
            }
            
            // Lock the result
            $testSeries->update([
                'result_locked' => true,
                'result_locked_at' => now(),
                'result_locked_by' => Auth::check() ? Auth::user()->name : 'Admin',
                'status' => 'Completed'
            ]);
            
            Log::info('Result locked successfully', ['test_series_id' => $id]);
            
            return back()->with('success', 'Result locked successfully! No further changes can be made.');
            
        } catch (\Exception $e) {
            Log::error('Lock Result ERROR: ' . $e->getMessage());
            return back()->with('error', 'Error locking result: ' . $e->getMessage());
        }
    }
    
    /**
     * Calculate ranks for all students in a test series
     */
    private function calculateRanks($testSeriesId)
    {
        try {
            // Get all results sorted by total marks (descending)
            $results = TestResult::where('test_series_id', $testSeriesId)
                ->orderBy('total_marks_obtained', 'desc')
                ->orderBy('student_roll_no', 'asc')
                ->get();
            
            $rank = 1;
            $previousMarks = null;
            $sameRankCount = 0;
            
            foreach ($results as $index => $result) {
                if ($previousMarks !== null && $result->total_marks_obtained < $previousMarks) {
                    $rank += $sameRankCount;
                    $sameRankCount = 1;
                } else {
                    $sameRankCount++;
                }
                
                $result->update(['rank' => $rank]);
                $previousMarks = $result->total_marks_obtained;
            }
            
            Log::info('Ranks calculated successfully', [
                'test_series_id' => $testSeriesId,
                'total_students' => $results->count()
            ]);
            
        } catch (\Exception $e) {
            Log::error('Calculate Ranks ERROR: ' . $e->getMessage());
        }
    }
    

    // Update the uploadResult method
public function uploadResult(Request $request, $id)
{
    try {
        Log::info('=== Upload Result - START ===', ['test_series_id' => $id]);
        
        $testSeries = TestSeries::findOrFail($id);
        
        if ($testSeries->result_locked) {
            return back()->with('error', 'Result is locked. Cannot upload new results.');
        }
        
        $request->validate([
            'result_file' => 'required|file|mimes:xlsx,xls,csv|max:10240',
        ]);
        
        $file = $request->file('result_file');
        
        // Import using PhpSpreadsheet
        $import = new TestResultImport($testSeries);
        $import->import($file->getRealPath());
        
        $results = $import->getResults();
        $errors = $import->getErrors();
        
        if (!empty($errors)) {
            Log::warning('Result upload had errors', ['errors' => $errors]);
            return back()->with('error', 'Result upload had errors: ' . implode(', ', array_slice($errors, 0, 5)));
        }
        
        // Store results...
        foreach ($results as $result) {
            TestResult::updateOrCreate(
                [
                    'test_series_id' => $testSeries->_id,
                    'student_roll_no' => $result['roll_no']
                ],
                [
                    'student_name' => $result['student_name'],
                    'course_id' => $testSeries->course_id,
                    'course_name' => $testSeries->course_name,
                    'test_name' => $testSeries->test_name,
                    'test_type' => $testSeries->test_type,
                    'subject_type' => $testSeries->subject_type,
                    'subjects' => $testSeries->subjects,
                    'subject_marks' => $result['subject_marks'] ?? [],
                    'total_marks_obtained' => $result['total_marks'] ?? 0,
                    'total_marks' => $testSeries->total_marks ?? 0,
                    'percentage' => $result['percentage'] ?? 0,
                    'rank' => null,
                    'uploaded_at' => now(),
                    'uploaded_by' => Auth::check() ? Auth::user()->name : 'Admin',
                ]
            );
        }
        
        // Update test series...
        $testSeries->update([
            'result_uploaded' => true,
            'result_uploaded_at' => now(),
            'result_uploaded_by' => Auth::check() ? Auth::user()->name : 'Admin',
            'total_students_appeared' => count($results),
        ]);
        
        $this->calculateRanks($testSeries->_id);
        
        return back()->with('success', 'Result uploaded successfully! Total students: ' . count($results));
        
    } catch (\Exception $e) {
        Log::error('Upload Result ERROR: ' . $e->getMessage());
        return back()->with('error', 'Error uploading result: ' . $e->getMessage());
    }
}

// Update generateResultTemplate method
public function generateResultTemplate($id)
{
    try {
        $testSeries = TestSeries::findOrFail($id);
        
        $students = SMstudents::where('course_id', $testSeries->course_id)
            ->where('status', 'active')
            ->orderBy('roll_no', 'asc')
            ->get();
        
        $export = new TestResultTemplateExport($testSeries, $students);
        $export->download('result_template_' . str_replace('/', '_', $testSeries->test_name) . '.xlsx');
        
    } catch (\Exception $e) {
        Log::error('Generate Template ERROR: ' . $e->getMessage());
        return back()->with('error', 'Error generating template: ' . $e->getMessage());
    }
}

public function uploadSyllabus(Request $request, $id)
{
    try {
        Log::info('=== Upload Syllabus - START ===', ['test_series_id' => $id]);
        
        $testSeries = TestSeries::findOrFail($id);
        
        $request->validate([
            'syllabus_file' => 'required|file|mimes:pdf,doc,docx,txt|max:10240', // 10MB max
        ]);
        
        $file = $request->file('syllabus_file');
        
        // Store the file
        $fileName = 'syllabus_' . $testSeries->_id . '_' . time() . '.' . $file->getClientOriginalExtension();
        $filePath = $file->storeAs('syllabus', $fileName, 'public');
        
        // Update test series with syllabus file info
        $testSeries->update([
            'syllabus_file_path' => $filePath,
            'syllabus_file_name' => $file->getClientOriginalName(),
            'syllabus_uploaded_at' => now(),
            'syllabus_uploaded_by' => Auth::check() ? Auth::user()->name : 'Admin',
        ]);
        
        Log::info('Syllabus uploaded successfully', [
            'test_series_id' => $id,
            'file_name' => $fileName
        ]);
        
        return back()->with('success', 'Syllabus uploaded successfully!');
        
    } catch (\Exception $e) {
        Log::error('Upload Syllabus ERROR: ' . $e->getMessage());
        return back()->with('error', 'Error uploading syllabus: ' . $e->getMessage());
    }
}

/**
 * Download syllabus
 */
public function downloadSyllabus($id)
{
    try {
        $testSeries = TestSeries::findOrFail($id);
        
        if (!$testSeries->syllabus_file_path) {
            return back()->with('error', 'No syllabus uploaded for this test series.');
        }
        
        $filePath = storage_path('app/public/' . $testSeries->syllabus_file_path);
        
        if (!file_exists($filePath)) {
            return back()->with('error', 'Syllabus file not found.');
        }
        
        return response()->download($filePath, $testSeries->syllabus_file_name);
        
    } catch (\Exception $e) {
        Log::error('Download Syllabus ERROR: ' . $e->getMessage());
        return back()->with('error', 'Error downloading syllabus: ' . $e->getMessage());
    }
}

/**
 * Delete syllabus
 */
public function deleteSyllabus($id)
{
    try {
        $testSeries = TestSeries::findOrFail($id);
        
        if ($testSeries->syllabus_file_path) {
            // Delete the file from storage
            $filePath = storage_path('app/public/' . $testSeries->syllabus_file_path);
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }
        
        $testSeries->update([
            'syllabus_file_path' => null,
            'syllabus_file_name' => null,
            'syllabus_uploaded_at' => null,
            'syllabus_uploaded_by' => null,
        ]);
        
        return back()->with('success', 'Syllabus deleted successfully!');
        
    } catch (\Exception $e) {
        Log::error('Delete Syllabus ERROR: ' . $e->getMessage());
        return back()->with('error', 'Error deleting syllabus: ' . $e->getMessage());
    }
}

/**
 * Store a new test for a test series
 */
public function storeTest(Request $request, $testSeriesId)
{
    try {
        Log::info('=== Add Test - START ===', ['test_series_id' => $testSeriesId]);
        
        $testSeries = TestSeries::findOrFail($testSeriesId);
        
        $validated = $request->validate([
            'test_number' => 'required|integer|min:1',
            'batch_code' => 'required|string',
            'scheduled_date' => 'required|date',
            'scheduled_time' => 'nullable|date_format:H:i',
            'duration_minutes' => 'nullable|integer|min:30',
        ]);

        // Check if test number already exists for this test series and batch
        $existingTest = Test::where('test_series_id', $testSeriesId)
            ->where('test_number', $validated['test_number'])
            ->where('batch_code', $validated['batch_code'])
            ->first();

        if ($existingTest) {
            return back()->with('error', 'Test number ' . $validated['test_number'] . ' already exists for batch ' . $validated['batch_code']);
        }

        // Get students from the batch
        $students = SMstudents::where('course_id', $testSeries->course_id)
            ->where('batch_code', $validated['batch_code'])
            ->where('status', 'active')
            ->pluck('_id')
            ->toArray();

        // Combine date and time
        $scheduledDateTime = $validated['scheduled_date'];
        if (isset($validated['scheduled_time'])) {
            $scheduledDateTime .= ' ' . $validated['scheduled_time'];
        }

        $test = Test::create([
            'test_series_id' => $testSeries->_id,
            'test_number' => $validated['test_number'],
            'batch_code' => $validated['batch_code'],
            'scheduled_date' => Carbon::parse($scheduledDateTime),
            'duration_minutes' => $validated['duration_minutes'] ?? 180, // Default 3 hours
            'status' => 'Scheduled',
            'students_enrolled' => $students,
            'students_appeared' => 0,
            'result_uploaded' => false,
            'result_locked' => false,
            'created_by' => Auth::check() ? Auth::user()->name : 'Admin',
        ]);

        Log::info('Test added successfully', ['test_id' => $test->_id]);

        return back()->with('success', 'Test #' . $validated['test_number'] . ' scheduled successfully for batch ' . $validated['batch_code']);

    } catch (\Exception $e) {
        Log::error('Add Test ERROR: ' . $e->getMessage());
        return back()->with('error', 'Error adding test: ' . $e->getMessage());
    }
}

/**
 * Update a test
 */
public function updateTest(Request $request, $testId)
{
    try {
        $test = Test::findOrFail($testId);
        
        $validated = $request->validate([
            'scheduled_date' => 'nullable|date',
            'scheduled_time' => 'nullable|date_format:H:i',
            'duration_minutes' => 'nullable|integer|min:30',
            'status' => 'nullable|in:Scheduled,Completed,Cancelled',
        ]);

        $updateData = ['updated_by' => Auth::check() ? Auth::user()->name : 'Admin'];

        if (isset($validated['scheduled_date'])) {
            $scheduledDateTime = $validated['scheduled_date'];
            if (isset($validated['scheduled_time'])) {
                $scheduledDateTime .= ' ' . $validated['scheduled_time'];
            }
            $updateData['scheduled_date'] = Carbon::parse($scheduledDateTime);
        }

        if (isset($validated['duration_minutes'])) {
            $updateData['duration_minutes'] = $validated['duration_minutes'];
        }

        if (isset($validated['status'])) {
            $updateData['status'] = $validated['status'];
        }

        $test->update($updateData);

        return back()->with('success', 'Test updated successfully!');

    } catch (\Exception $e) {
        Log::error('Update Test ERROR: ' . $e->getMessage());
        return back()->with('error', 'Error updating test: ' . $e->getMessage());
    }
}

/**
 * Delete a test
 */
public function destroyTest($testId)
{
    try {
        $test = Test::findOrFail($testId);
        
        if ($test->result_uploaded) {
            return back()->with('error', 'Cannot delete test with uploaded results!');
        }
        
        $test->delete();

        return back()->with('success', 'Test deleted successfully!');

    } catch (\Exception $e) {
        Log::error('Delete Test ERROR: ' . $e->getMessage());
        return back()->with('error', 'Error deleting test: ' . $e->getMessage());
    }
}

/**
 * Get available batches for a course (AJAX)
 */
/**
 * Get available batches for a course (AJAX)
 */
public function getCourseBatches($courseId)
{
    try {
        Log::info('=== Get Course Batches - START ===', ['course_id' => $courseId]);
        
        // Get the course first
        $course = Courses::find($courseId);
        
        if (!$course) {
            Log::error('Course not found', ['course_id' => $courseId]);
            return response()->json([
                'success' => false,
                'message' => 'Course not found'
            ], 404);
        }
        
        $courseName = $course->course_name ?? $course->name;
        
        Log::info('Course found', [
            'course_id' => $courseId,
            'course_name' => $courseName
        ]);
        
        // Get batches from Batch table where course matches the course name
        $batches = Batch::where('course', $courseName)
            ->where('status', 'Active')
            ->orderBy('batch_id', 'asc')
            ->pluck('batch_id')
            ->unique()
            ->filter()
            ->values()
            ->toArray();
        
        Log::info('Batches found', [
            'count' => count($batches),
            'batches' => $batches,
            'query_course_name' => $courseName
        ]);
        
        if (empty($batches)) {
            Log::warning('No batches found for course', [
                'course_name' => $courseName
            ]);
        }

        return response()->json([
            'success' => true,
            'batches' => $batches,
            'course_name' => $courseName
        ]);

    } catch (\Exception $e) {
        Log::error('Get Course Batches ERROR', [
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        
        return response()->json([
            'success' => false,
            'message' => 'Server error: ' . $e->getMessage()
        ], 500);
    }
}
/**
 * Store multiple tests at once
 */
public function storeMultipleTests(Request $request, $testSeriesId)
{
    try {
        Log::info('=== Store Multiple Tests - START ===', ['test_series_id' => $testSeriesId]);
        
        $testSeries = TestSeries::findOrFail($testSeriesId);
        
        $validated = $request->validate([
            'test_dates_data' => 'required|string',
            'test_number' => 'required|integer|min:1',
            'scheduled_time' => 'nullable|date_format:H:i',
            'duration_minutes' => 'nullable|integer|min:30',
        ]);

        $testDates = json_decode($validated['test_dates_data'], true);
        
        if (empty($testDates)) {
            return back()->with('error', 'No test dates selected');
        }

        $testNumber = $validated['test_number'];
        $scheduledTime = $validated['scheduled_time'] ?? '14:00';
        $durationMinutes = $validated['duration_minutes'] ?? 180;
        
        $createdCount = 0;
        $errors = [];

        foreach ($testDates as $testData) {
            try {
                $batchCode = $testData['batch_code'];
                $testDate = $testData['test_date'];
                
                // Check if test already exists
                $existing = Test::where('test_series_id', $testSeriesId)
                    ->where('test_number', $testNumber)
                    ->where('batch_code', $batchCode)
                    ->first();
                
                if ($existing) {
                    $errors[] = "Test #{$testNumber} already exists for batch {$batchCode}";
                    continue;
                }

                // Get students from the batch
                $students = SMstudents::where('course_id', $testSeries->course_id)
                    ->where('batch_code', $batchCode)
                    ->where('status', 'active')
                    ->pluck('_id')
                    ->toArray();

                // Combine date and time
                $scheduledDateTime = $testDate . ' ' . $scheduledTime;

                Test::create([
                    'test_series_id' => $testSeries->_id,
                    'test_number' => $testNumber,
                    'batch_code' => $batchCode,
                    'scheduled_date' => Carbon::parse($scheduledDateTime),
                    'duration_minutes' => $durationMinutes,
                    'status' => 'Scheduled',
                    'students_enrolled' => $students,
                    'students_appeared' => 0,
                    'result_uploaded' => false,
                    'result_locked' => false,
                    'created_by' => Auth::check() ? Auth::user()->name : 'Admin',
                ]);

                $createdCount++;
                $testNumber++; // Increment for next test
                
            } catch (\Exception $e) {
                Log::error('Error creating test', [
                    'batch' => $testData['batch_code'],
                    'error' => $e->getMessage()
                ]);
                $errors[] = "Failed to create test for batch {$testData['batch_code']}";
            }
        }

        $message = "{$createdCount} tests created successfully!";
        if (!empty($errors)) {
            $message .= " Some tests failed: " . implode(', ', $errors);
        }

        Log::info('Multiple tests created', ['count' => $createdCount]);

        return back()->with('success', $message);

    } catch (\Exception $e) {
        Log::error('Store Multiple Tests ERROR: ' . $e->getMessage());
        return back()->with('error', 'Error creating tests: ' . $e->getMessage());
    }
}
}