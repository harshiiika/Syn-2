<?php

namespace App\Http\Controllers\TestSeries;

use App\Models\TestSeries\TestSeries;
use App\Models\Master\Courses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;

class TestSeriesController extends Controller
{
    public function index()
    {
        try {
            Log::info('=== Test Series Index - START ===');
            
            // Check if Courses model is accessible
            $courses = Courses::all();
            Log::info('Courses Count: ' . $courses->count());

            $testMasters = $courses->map(function ($course) {
                $courseId = is_object($course->_id) ? (string)$course->_id : $course->_id;
                
                Log::info('Processing Course', [
                    'name' => $course->name,
                    'id' => $courseId
                ]);
                
                return [
                    'name' => $course->name,
                    'type1_count' => TestSeries::where('course_id', $courseId)
                        ->where('test_type', 'Type1')
                        ->count(),
                    'type2_count' => TestSeries::where('course_id', $courseId)
                        ->where('test_type', 'Type2')
                        ->count(),
                ];
            });

            Log::info('Test Masters Count: ' . $testMasters->count());
            Log::info('=== Test Series Index - END ===');

            // Check if view exists
            if (!view()->exists('test_series.index')) {
                Log::error('View not found: test_series.index');
                return response('View test_series.index not found', 404);
            }

            return view('test_series.index', compact('testMasters'));

        } catch (\Exception $e) {
            Log::error('=== Test Series Index ERROR ===');
            Log::error('Message: ' . $e->getMessage());
            Log::error('File: ' . $e->getFile());
            Log::error('Line: ' . $e->getLine());
            Log::error('Trace: ' . $e->getTraceAsString());
            
            // Return error view instead of redirect
            return response()->view('errors.500', [
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($courseName)
    {
        try {
            Log::info('=== Test Series Show - START ===', ['courseName' => $courseName]);
            
            $courseName = urldecode($courseName);
            $course = Courses::where('name', $courseName)->first();

            if (!$course) {
                Log::warning('Course not found: ' . $courseName);
                return redirect()->route('test_series.index')
                    ->with('error', 'Course not found');
            }

            $courseId = is_object($course->_id) ? (string)$course->_id : $course->_id;
            Log::info('Course found', ['id' => $courseId]);

            $testSeries = TestSeries::where('course_id', $courseId)
                ->orderBy('created_at', 'desc')
                ->get();

            Log::info('Test Series Count: ' . $testSeries->count());
            
            // Check if view exists
            if (!view()->exists('test_series.detail')) {
                Log::error('View not found: test_series.detail');
                return response('View test_series.detail not found', 404);
            }

            Log::info('=== Test Series Show - END ===');
            
            return view('test_series.detail', compact('course', 'testSeries', 'courseName'));

        } catch (\Exception $e) {
            Log::error('=== Test Series Show ERROR ===');
            Log::error('Message: ' . $e->getMessage());
            Log::error('File: ' . $e->getFile());
            Log::error('Line: ' . $e->getLine());
            Log::error('Trace: ' . $e->getTraceAsString());
            
            return redirect()->route('test_series.index')
                ->with('error', 'Error loading test series: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            Log::info('=== Test Series Store - START ===');
            Log::info('Request Data: ', $request->all());
            
            // Base validation rules
            $rules = [
                'course_id' => 'required|string',
                'test_type' => 'required|in:Type1,Type2',
                'subject_type' => 'required|in:Single,Double',
                'test_count' => 'required|integer|min:1',
            ];

            // Conditional validation based on test type
            if ($request->test_type === 'Type1') {
                $rules['test_series_name'] = 'required|string|max:255';
                $rules['subjects'] = 'required|array|min:1';
            } else {
                $rules['subjects'] = 'required|array|min:1';
            }

            $validated = $request->validate($rules);
            Log::info('Validation passed');

            // Find course
            $course = Courses::find($validated['course_id']);
            if (!$course) {
                Log::error('Course not found with ID: ' . $validated['course_id']);
                return back()
                    ->withInput()
                    ->with('error', 'Course not found!');
            }

            $courseName = $course->name ?? 'Course';
            Log::info('Course found: ' . $courseName);

            // Build test name
            if ($request->test_type === 'Type1') {
                $testName = $courseName . '/Type1/' . $validated['test_series_name'];
            } else {
                $testName = $courseName . '/Type2';
            }

            // Prepare data
            $testSeriesData = [
                'course_id' => $validated['course_id'],
                'test_name' => $testName,
                'test_type' => $validated['test_type'],
                'subject_type' => $validated['subject_type'],
                'test_count' => $validated['test_count'],
                'status' => 'Pending',
                'subjects' => $validated['subjects'],
                'created_by' => Auth::check() ? Auth::user()->name : 'Admin',
            ];

            // Add test series name for Type1
            if ($request->test_type === 'Type1') {
                $testSeriesData['test_series_name'] = $validated['test_series_name'];
            }

            Log::info('Creating test series with data: ', $testSeriesData);

            // Create test series
            $testSeries = TestSeries::create($testSeriesData);

            Log::info('Test Series Created Successfully', ['id' => $testSeries->_id]);

            // Redirect back to course detail page
            if ($request->has('course_name')) {
                return redirect()
                    ->route('test_series.show', urlencode($request->course_name))
                    ->with('success', 'Test Series created successfully!');
            }

            return redirect()
                ->route('test_series.index')
                ->with('success', 'Test Series created successfully!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation Error: ', $e->errors());
            return back()
                ->withInput()
                ->withErrors($e->errors())
                ->with('error', 'Validation failed. Please check the form.');
        } catch (\Exception $e) {
            Log::error('=== Test Series Store ERROR ===');
            Log::error('Message: ' . $e->getMessage());
            Log::error('File: ' . $e->getFile());
            Log::error('Line: ' . $e->getLine());
            Log::error('Trace: ' . $e->getTraceAsString());
            
            return back()
                ->withInput()
                ->with('error', 'Error creating test series: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            Log::info('=== Test Series Update - START ===', ['id' => $id]);
            
            $testSeries = TestSeries::findOrFail($id);

            $validated = $request->validate([
                'test_name' => 'required|string|max:255',
                'test_type' => 'required|in:Type1,Type2',
                'subject_type' => 'required|in:Single,Double',
                'status' => 'nullable|in:Pending,Active,Completed',
            ]);

            $testSeries->update([
                'test_name' => $validated['test_name'],
                'test_type' => $validated['test_type'],
                'subject_type' => $validated['subject_type'],
                'status' => $validated['status'] ?? $testSeries->status,
                'updated_by' => Auth::check() ? Auth::user()->name : 'Admin',
            ]);

            Log::info('Test Series Updated Successfully');

            return back()->with('success', 'Test Series updated successfully!');

        } catch (\Exception $e) {
            Log::error('=== Test Series Update ERROR ===');
            Log::error('Message: ' . $e->getMessage());
            return back()->with('error', 'Error updating test series: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            Log::info('=== Test Series Destroy - START ===', ['id' => $id]);
            
            $testSeries = TestSeries::findOrFail($id);
            $testSeries->delete();

            Log::info('Test Series Deleted Successfully');

            return back()->with('success', 'Test Series deleted successfully!');

        } catch (\Exception $e) {
            Log::error('=== Test Series Destroy ERROR ===');
            Log::error('Message: ' . $e->getMessage());
            return back()->with('error', 'Error deleting test series: ' . $e->getMessage());
        }
    }
}