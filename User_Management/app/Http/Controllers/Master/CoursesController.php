<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\CoursesRequest;
use App\Models\Master\Courses;
use Illuminate\Http\Request;


/**
 * CoursesController - Manages course catalog operations
 * Handles CRUD operations for educational courses including search, pagination, and validation
 */
class CoursesController extends Controller
{
    /**
     * Display paginated list of courses with optional search filtering
     * @param Request $request - Contains pagination and search parameters
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Get entries per page from request, default to 10 if not specified
        $perPage = $request->get('per_page', 10);
        
        // Retrieve search query string from request parameters
        $search = $request->get('search');

        // Initialize query builder for Courses model
        $query = Courses::query();
        
        // Apply search filters if search term exists
        if ($search) {
            // Create OR condition group for searching across multiple fields
            $query->where(function($q) use ($search) {
                // Search in course name field using partial match
                $q->where('course_name', 'like', "%{$search}%")
                  // Also search in course code field
                  ->orWhere('course_code', 'like', "%{$search}%")
                  // Include class name in search results
                  ->orWhere('class_name', 'like', "%{$search}%");
            });
        }

        // Sort courses by creation date (newest first) and paginate results
        $courses = $query->orderBy('created_at', 'desc')->paginate($perPage)->appends($request->all());

        // Return view with courses data and search term for display
        return view('master.courses.index', compact('courses', 'search'));
    }

    /**
     * Show form for creating new course
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // Return create view for course creation form
        return view('courses.create');
    }

    /**
     * Display edit form for specific course
     * @param string $id - Course MongoDB ID
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        // Find course by ID or throw 404 if not found
        $course = Courses::findOrFail($id);
        
        // Get all courses for index view (maintains list context)
        $courses = Courses::orderBy('created_at', 'desc')->paginate(10);
        
        // Return index view with both course list and selected course for editing
        return view('courses.index', compact('courses', 'course'));
    }

    /**
     * Store newly created course in database
     * @param Request $request - Contains course form data
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validate incoming request data with specific rules
        $validated = $request->validate([
            // Course name is required and max 255 characters
            'course_name' => 'required|string|max:255',
            // Course type must be one of predefined options
            'course_type' => 'required|string',
            // Class name is mandatory for course association
            'class_name' => 'required|string',
            // Course code is required with character limit
            'course_code' => 'required|string|max:255',
            // Subjects must be array with at least one entry
            'subjects' => 'required|array|min:1',
            // Each subject element must be string
            'subjects.*' => 'string',
            // Status must be either active or inactive
            'status' => 'required|in:active,inactive'
        ]);

        // Create new course record with validated data
        // Subjects stored as array automatically via model $casts
        Courses::create($validated);

        // Redirect back to previous page with success message
        return redirect()->back()->with('success', 'Course created successfully!');
    }

    /**
     * Update existing course with new data
     * @param Request $request - Contains updated course form data
     * @param string $id - Course MongoDB ID to update
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        // Validate updated course data with same rules as store
        $validated = $request->validate([
            // Course name validation (required, string, max length)
            'course_name' => 'required|string|max:255',
            // Course type must be valid string
            'course_type' => 'required|string',
            // Class name is mandatory
            'class_name' => 'required|string',
            // Course code with length restriction
            'course_code' => 'required|string|max:255',
            // At least one subject required in array
            'subjects' => 'required|array|min:1',
            // Individual subject validation
            'subjects.*' => 'string',
            // Status limited to active or inactive
            'status' => 'required|in:active,inactive'
        ]);

        // Find course by ID or fail with 404
        $course = Courses::findOrFail($id);
        
        // Update course record with validated data
        $course->update($validated);

        // Redirect back with success notification
        return redirect()->back()->with('success', 'Course updated successfully!');
    }

    /**
     * Remove course from database
     * @param string $id - Course MongoDB ID to delete
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        // Retrieve course by ID or throw 404 error
        $course = Courses::findOrFail($id);
        
        // Permanently delete course from database
        $course->delete();
        
        // Redirect to courses index with deletion confirmation
        return redirect()->route('courses.index')->with('success', 'Course deleted.');
    }
}