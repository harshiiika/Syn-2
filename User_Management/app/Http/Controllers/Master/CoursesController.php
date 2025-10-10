<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\CoursesRequest;
use App\Models\Master\Courses;
use Illuminate\Http\Request;

class CoursesController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $search = $request->get('search');

        $query = Courses::query();
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('course_name', 'like', "%{$search}%")
                  ->orWhere('course_code', 'like', "%{$search}%")
                  ->orWhere('class_name', 'like', "%{$search}%");
            });
        }

        $courses = $query->orderBy('created_at', 'desc')->paginate($perPage)->appends($request->all());

        return view('master.courses.index', compact('courses', 'search'));
    }

    public function create()
    {
        return view('master.courses.create');
    }

    public function edit($id)
    {
        $course = Courses::findOrFail($id);
        return view('master.courses.edit', compact('course'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'course_name' => 'required|string|max:255',
            'course_type' => 'required|string',
            'class_name' => 'required|string',
            'course_code' => 'required|string|max:255',
            'subjects' => 'required|array|min:1',
            'subjects.*' => 'string',
            'status' => 'required|in:active,inactive'
        ]);

        // Subjects will be stored as array thanks to the $casts in model
        // No need to json_encode manually
        Courses::create($validated);

        return redirect()->back()->with('success', 'Course created successfully!');
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'course_name' => 'required|string|max:255',
            'course_type' => 'required|string',
            'class_name' => 'required|string',
            'course_code' => 'required|string|max:255',
            'subjects' => 'required|array|min:1',
            'subjects.*' => 'string',
            'status' => 'required|in:active,inactive'
        ]);

        // Subjects will be stored as array thanks to the $casts in model
        // No need to json_encode manually
        $course = Courses::findOrFail($id);
        $course->update($validated);

        return redirect()->back()->with('success', 'Course updated successfully!');
    }

    public function destroy($id)
    {
        $course = Courses::findOrFail($id);
        $course->delete();
        
        return redirect()->route('courses.index')->with('success', 'Course deleted.');
    }
}

//phptag

// namespace App\Http\Controllers\Master;

// use App\Http\Controllers\Controller;
// use App\Http\Requests\Master\CoursesRequest;
// use App\Models\Master\Course;
// use App\Models\Master\Courses;
// use Illuminate\Http\Request;
// use App\Http\Requests\Master\CourseRequest;

// class CoursesController extends Controller
// {
//     public function index(Request $request)
//     {
//         $perPage = $request->get('per_page', 10);
//         $search = $request->get('search');

//         $query = Courses::query();
        
//         if ($search) {
//             $query->where(function($q) use ($search) {
//                 $q->where('course_name', 'like', "%{$search}%")
//                   ->orWhere('course_code', 'like', "%{$search}%")
//                   ->orWhere('class_name', 'like', "%{$search}%");
//             });
//         }

//         $courses = $query->orderBy('created_at', 'desc')->paginate($perPage)->appends($request->all());

//         return view('master.courses.index', compact('courses', 'search'));
//     }

//     public function create()
//     {
//         return view('master.courses.create');
//     }

//     public function edit($id)
//     {
//         $course = Courses::findOrFail($id);
//         return view('master.courses.edit', compact('course'));
//     }

//     public function store(Request $request)
//     {
//         $validated = $request->validate([
//             'course_name' => 'required|string|max:255',
//             'course_type' => 'required|string',
//             'class_name' => 'required|string',
//             'course_code' => 'required|string|max:255',
//             'subjects' => 'required|array',
//             'subjects.*' => 'string',
//             'status' => 'required|in:active,inactive'
//         ]);

//         // Convert subjects array to JSON for storage
//         $validated['subjects'] = json_encode($validated['subjects']);

//         Courses::create($validated);

//         return redirect()->back()->with('success', 'Course created successfully!');
//     }

//     public function update(Request $request, $id)
//     {
//         $validated = $request->validate([
//             'course_name' => 'required|string|max:255',
//             'course_type' => 'required|string',
//             'class_name' => 'required|string',
//             'course_code' => 'required|string|max:255',
//             'subjects' => 'required|array',
//             'subjects.*' => 'string',
//             'status' => 'required|in:active,inactive'
//         ]);

//         // Convert subjects array to JSON for storage
//         $validated['subjects'] = json_encode($validated['subjects']);

//         $course = Courses::findOrFail($id);
//         $course->update($validated);

//         return redirect()->back()->with('success', 'Course updated successfully!');
//     }

//     public function destroy($id)
//     {
//         $course = Courses::findOrFail($id);
//         $course->delete();
        
//         return redirect()->route('courses.index')->with('success', 'Course deleted.');
//     }


