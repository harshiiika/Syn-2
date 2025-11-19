<?php

namespace App\Http\Controllers;

use App\Models\TestSeries;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TestSeriesController extends Controller
{
    /**
     * Display the main Test Series page with grouped test masters
     */
    public function index()
    {
        try {
            // Get all test series grouped by test_master_id (course + test type combination)
            $allTestSeries = TestSeries::with('course')
                ->orderBy('created_at', 'desc')
                ->get();

            // Group by test master (course name + test type + subject type)
            $testMasters = [];
            foreach ($allTestSeries as $series) {
                $courseName = $series->course->name ?? 'Unknown';
                $key = $courseName; // Group by course name
                
                if (!isset($testMasters[$key])) {
                    $testMasters[$key] = [
                        'name' => $courseName,
                        'course_id' => $series->course_id,
                        'type1_count' => 0,
                        'type2_count' => 0,
                        'test_series' => []
                    ];
                }
                
                // Count test types
                if ($series->test_type === 'Type1') {
                    $testMasters[$key]['type1_count']++;
                } elseif ($series->test_type === 'Type2') {
                    $testMasters[$key]['type2_count']++;
                }
                
                $testMasters[$key]['test_series'][] = $series;
            }

            $courses = Course::all();

            return view('test_series.index', compact('testMasters', 'courses'));
            
        } catch (\Exception $e) {
            return back()->with('error', 'Error loading test series: ' . $e->getMessage());
        }
    }

    /**
     * Display test series for a specific course
     */
    public function show($courseName)
    {
        try {
            // Decode the course name from URL
            $courseName = urldecode($courseName);
            
            // Get all test series for this course
            $course = Course::where('name', $courseName)->first();
            
            if (!$course) {
                return redirect()->route('test_series.index')
                    ->with('error', 'Course not found');
            }

            $courseId = is_object($course->_id) ? (string)$course->_id : $course->_id;
            
            $testSeries = TestSeries::where('course_id', $courseId)
                ->with('course')
                ->orderBy('created_at', 'desc')
                ->get();

            return view('test_series.detail', compact('course', 'testSeries', 'courseName'));
            
        } catch (\Exception $e) {
            return redirect()->route('test_series.index')
                ->with('error', 'Error loading test series: ' . $e->getMessage());
        }
    }

    /**
     * Store a new test series
     */
    public function store(Request $request)
    {
        try {
            // Validate input
            $rules = [
                'course_id' => 'required|string',
                'test_type' => 'required|in:Type1,Type2',
                'subject_type' => 'required|in:Single,Double',
                'test_count' => 'required|integer|min:1',
            ];

            // Conditional validation based on test_type
            if ($request->test_type === 'Type1') {
                $rules['test_series_name'] = 'required|string|max:255';
                $rules['subjects'] = 'required|array|min:1';
            } elseif ($request->test_type === 'Type2') {
                $rules['subjects'] = 'required|array|min:1';
            }

            $validated = $request->validate($rules);

            // Get course
            $course = Course::find($validated['course_id']);
            if (!$course) {
                return back()->with('error', 'Course not found!');
            }

            // Generate test_name
            $courseName = $course->name ?? 'Course';
            if ($request->test_type === 'Type1' && !empty($validated['test_series_name'])) {
                $testName = $courseName . '/' . $validated['test_type'] . '/' . $validated['test_series_name'];
            } else {
                $testName = $courseName . '/' . $validated['test_type'];
            }

            // Create test series
            $testSeriesData = [
                'course_id' => $validated['course_id'],
                'test_name' => $testName,
                'test_type' => $validated['test_type'],
                'subject_type' => $validated['subject_type'],
                'test_count' => $validated['test_count'],
                'status' => 'Pending',
                'created_by' => Auth::check() ? Auth::user()->name : 'Admin',
            ];

            // Add subjects if provided
            if (isset($validated['subjects'])) {
                $testSeriesData['subjects'] = $validated['subjects'];
            }

            // Add test_series_name if Type1
            if ($request->test_type === 'Type1' && isset($validated['test_series_name'])) {
                $testSeriesData['test_series_name'] = $validated['test_series_name'];
            }

            TestSeries::create($testSeriesData);

            // Redirect back to the course detail page if courseName is provided
            if ($request->has('course_name')) {
                return redirect()->route('test_series.show', urlencode($request->course_name))
                    ->with('success', 'Test Series created successfully!');
            }

            return redirect()->route('test_series.index')
                ->with('success', 'Test Series created successfully!');
                
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return back()->with('error', 'Error creating test series: ' . $e->getMessage());
        }
    }

    /**
     * Update test series
     */
    public function update(Request $request, $id)
    {
        try {
            $testSeries = TestSeries::findOrFail($id);

            // Validate input
            $validated = $request->validate([
                'test_name' => 'required|string|max:255',
                'test_type' => 'required|in:Type1,Type2',
                'subject_type' => 'required|in:Single,Double',
                'status' => 'nullable|in:Pending,Active,Completed',
            ]);

            // Update test series
            $updateData = [
                'test_name' => $validated['test_name'],
                'test_type' => $validated['test_type'],
                'subject_type' => $validated['subject_type'],
                'status' => $validated['status'] ?? $testSeries->status,
                'updated_by' => Auth::check() ? Auth::user()->name : 'Admin',
            ];

            $testSeries->update($updateData);

            return back()->with('success', 'Test Series updated successfully!');
                
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return back()->with('error', 'Error updating test series: ' . $e->getMessage());
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
            return back()->with('error', 'Error deleting test series: ' . $e->getMessage());
        }
    }

    /**
     * Export test series to Excel (optional)
     */
    public function export()
    {
        try {
            $testSeries = TestSeries::with('course')
                ->orderBy('created_at', 'desc')
                ->get();

            // You can implement Excel export here using Laravel Excel package
            return response()->json($testSeries);
            
        } catch (\Exception $e) {
            return back()->with('error', 'Error exporting test series: ' . $e->getMessage());
        }
    }
}