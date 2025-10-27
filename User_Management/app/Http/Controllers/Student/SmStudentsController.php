<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student\SMstudents;
use App\Models\Master\Batch;
use App\Models\Master\Courses;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class SmStudentsController extends Controller
{
    /**
     * Display a listing of students
     */
    public function index()
    {
        try {
            $students = SMstudents::with(['batch', 'course'])->get();
            $batches = Batch::all();
            $courses = Courses::all();
            
            return view('student.smstudents.smstudents', compact('students', 'batches', 'courses'));
            
        } catch (\Exception $e) {
            Log::error('Error in smstudents index: ' . $e->getMessage());
            return back()->with('error', 'Failed to load students: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified student with full details
     */
    public function show($id)
    {
        try {
            $student = SMstudents::with(['batch', 'course'])->findOrFail($id);
            
            if (request()->wantsJson()) {
                return response()->json($student);
            }
            
            // Return the separate view page
            return view('student.smstudents.view', compact('student'));
        } catch (\Exception $e) {
            Log::error('Error showing student: ' . $e->getMessage());
            return back()->with('error', 'Student not found');
        }
    }

    /**
     * Update the specified student
     */
    public function update(Request $request, $id)
    {
        $student = SMstudents::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'roll_no' => 'required|unique:smstudents,roll_no,' . $id . ',_id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:smstudents,email,' . $id . ',_id',
            'phone' => 'required|string|max:15',
            'batch_id' => 'required',
            'course_id' => 'required',
            'course_content' => 'required|string',
            'delivery_mode' => 'required|in:Offline,Online,Hybrid',
            'shift' => 'required|in:Morning,Evening',
            'status' => 'required|in:active,inactive'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput()->with('error', 'Validation failed');
        }

        try {
            $updateData = [
                'roll_no' => $request->roll_no,
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'batch_id' => $request->batch_id,
                'course_id' => $request->course_id,
                'course_content' => $request->course_content,
                'delivery_mode' => $request->delivery_mode,
                'shift' => $request->shift,
                'status' => $request->status
            ];

            if ($request->filled('password')) {
                $updateData['password'] = Hash::make($request->password);
            }

            $student->update($updateData);

            return redirect()->route('smstudents.index')->with('success', 'Student updated successfully');
        } catch (\Exception $e) {
            Log::error('Error updating student: ' . $e->getMessage());
            return back()->with('error', 'Failed to update student: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Update student password
     */
    public function updatePassword(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|min:6|confirmed'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->with('error', 'Password validation failed');
        }

        try {
            $student = SMstudents::findOrFail($id);
            $student->update([
                'password' => Hash::make($request->password)
            ]);

            return redirect()->route('smstudents.index')->with('success', 'Password updated successfully');
        } catch (\Exception $e) {
            Log::error('Error updating password: ' . $e->getMessage());
            return back()->with('error', 'Failed to update password: ' . $e->getMessage());
        }
    }

    /**
     * Update student batch
     */
    public function updateBatch(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'batch_id' => 'required'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->with('error', 'Batch validation failed');
        }

        try {
            $student = SMstudents::findOrFail($id);
            $student->update([
                'batch_id' => $request->batch_id
            ]);

            return redirect()->route('smstudents.index')->with('success', 'Batch updated successfully');
        } catch (\Exception $e) {
            Log::error('Error updating batch: ' . $e->getMessage());
            return back()->with('error', 'Failed to update batch: ' . $e->getMessage());
        }
    }

    /**
     * Deactivate student
     */
    public function deactivate($id)
    {
        try {
            $student = SMstudents::findOrFail($id);
            $student->update(['status' => 'inactive']);

            return redirect()->route('smstudents.index')->with('success', 'Student deactivated successfully');
        } catch (\Exception $e) {
            Log::error('Error deactivating student: ' . $e->getMessage());
            return back()->with('error', 'Failed to deactivate student: ' . $e->getMessage());
        }
    }

    /**
     * Export students data
     */
    public function export(Request $request)
    {
        try {
            $students = SMstudents::with(['batch', 'course'])->get();
            
            $filename = 'students_' . date('Y-m-d_His') . '.csv';
            
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ];

            $callback = function() use ($students) {
                $file = fopen('php://output', 'w');
                
                // CSV Headers
                fputcsv($file, [
                    'Roll No',
                    'Student Name',
                    'Email',
                    'Phone',
                    'Batch Name',
                    'Course Name',
                    'Course Content',
                    'Delivery Mode',
                    'Shift',
                    'Status'
                ]);

                // CSV Data
                foreach ($students as $student) {
                    fputcsv($file, [
                        $student->roll_no,
                        $student->name,
                        $student->email,
                        $student->phone,
                        $student->batch->name ?? 'N/A',
                        $student->course->name ?? 'N/A',
                        $student->course_content,
                        $student->delivery_mode,
                        $student->shift,
                        ucfirst($student->status)
                    ]);
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
            
        } catch (\Exception $e) {
            Log::error('Error exporting students: ' . $e->getMessage());
            return back()->with('error', 'Failed to export students: ' . $e->getMessage());
        }
    }

    /**
     * Get student history/activity log
     */
    public function history($id)
    {
        try {
            $student = SMstudents::with(['batch', 'course'])->findOrFail($id);
            
            return view('student.smstudents.history', compact('student'));
        } catch (\Exception $e) {
            Log::error('Error loading history: ' . $e->getMessage());
            return back()->with('error', 'Failed to load history');
        }
    }
}

// namespace App\Http\Controllers\Student;

// use App\Http\Controllers\Controller;
// use Illuminate\Http\Request;
// use App\Models\Student\SMstudents;
// use App\Models\Master\Batch;
// use App\Models\Master\Courses;
// use Illuminate\Support\Facades\Validator;
// use Illuminate\Support\Facades\Hash;
// use Illuminate\Support\Facades\Log;

// class SmStudentsController extends Controller
// {
//     /**
//      * Display a listing of students
//      */
//     public function index()
//     {
//         try {
//             $students = SMstudents::with(['batch', 'course'])->get();
//             $batches = Batch::all();
//             $courses = Courses::all();
            
//             return view('student.smstudents.smstudents', compact('students', 'batches', 'courses'));
            
//         } catch (\Exception $e) {
//             Log::error('Error in smstudents index: ' . $e->getMessage());
//             return back()->with('error', 'Failed to load students: ' . $e->getMessage());
//         }
//     }

//     /**
//      * Display the specified student with full details
//      */
//     public function show($id)
//     {
//         try {
//             $student = SMstudents::with(['batch', 'course'])->findOrFail($id);
            
//             if (request()->wantsJson()) {
//                 return response()->json($student);
//             }
            
//             return view('student.smstudents.view', compact('student'));
//         } catch (\Exception $e) {
//             Log::error('Error showing student: ' . $e->getMessage());
//             return back()->with('error', 'Student not found');
//         }
//     }

//     /**
//      * Update the specified student
//      */
//     public function update(Request $request, $id)
//     {
//         $student = SMstudents::findOrFail($id);

//         $validator = Validator::make($request->all(), [
//             'roll_no' => 'required|unique:smstudents,roll_no,' . $id . ',_id',
//             'name' => 'required|string|max:255',
//             'email' => 'required|email|unique:smstudents,email,' . $id . ',_id',
//             'phone' => 'required|string|max:15',
//             'batch_id' => 'required',
//             'course_id' => 'required',
//             'course_content' => 'required|string',
//             'delivery_mode' => 'required|in:Offline,Online,Hybrid',
//             'shift' => 'required|in:Morning,Evening',
//             'status' => 'required|in:active,inactive'
//         ]);

//         if ($validator->fails()) {
//             return back()->withErrors($validator)->withInput()->with('error', 'Validation failed');
//         }

//         try {
//             $updateData = [
//                 'roll_no' => $request->roll_no,
//                 'student_name' => $request->name,
//                 'email' => $request->email,
//                 'phone' => $request->phone,
//                 'batch_id' => $request->batch_id,
//                 'course_id' => $request->course_id,
//                 'course_content' => $request->course_content,
//                 'delivery' => $request->delivery_mode,
//                 'shift' => $request->shift,
//                 'status' => $request->status
//             ];



//             if ($request->filled('password')) {
//                 $updateData['password'] = Hash::make($request->password);
//             }

//             $student->update($updateData);

//             return redirect()->route('smstudents.index')->with('success', 'Student updated successfully');
//         } catch (\Exception $e) {
//             Log::error('Error updating student: ' . $e->getMessage());
//             return back()->with('error', 'Failed to update student: ' . $e->getMessage())->withInput();
//         }
//     }

//     /**
//      * Update student password
//      */
//     public function updatePassword(Request $request, $id)
//     {
//         $validator = Validator::make($request->all(), [
//             'password' => 'required|min:6|confirmed'
//         ]);

//         if ($validator->fails()) {
//             return back()->withErrors($validator)->with('error', 'Password validation failed');
//         }

//         try {
//             $student = SMstudents::findOrFail($id);
//             $student->update([
//                 'password' => Hash::make($request->password)
//             ]);

//             return redirect()->route('smstudents.index')->with('success', 'Password updated successfully');
//         } catch (\Exception $e) {
//             Log::error('Error updating password: ' . $e->getMessage());
//             return back()->with('error', 'Failed to update password: ' . $e->getMessage());
//         }
//     }

//     /**
//      * Update student batch
//      */
//     public function updateBatch(Request $request, $id)
//     {
//         $validator = Validator::make($request->all(), [
//             'batch_id' => 'required'
//         ]);

//         if ($validator->fails()) {
//             return back()->withErrors($validator)->with('error', 'Batch validation failed');
//         }

//         try {
//             $student = SMstudents::findOrFail($id);
//             $student->update([
//                 'batch_id' => $request->batch_id
//             ]);

//             return redirect()->route('smstudents.index')->with('success', 'Batch updated successfully');
//         } catch (\Exception $e) {
//             Log::error('Error updating batch: ' . $e->getMessage());
//             return back()->with('error', 'Failed to update batch: ' . $e->getMessage());
//         }
//     }

//     /**
//      * Deactivate student
//      */
//     public function deactivate($id)
//     {
//         try {
//             $student = SMstudents::findOrFail($id);
//             $student->update(['status' => 'inactive']);

//             return redirect()->route('smstudents.index')->with('success', 'Student deactivated successfully');
//         } catch (\Exception $e) {
//             Log::error('Error deactivating student: ' . $e->getMessage());
//             return back()->with('error', 'Failed to deactivate student: ' . $e->getMessage());
//         }
//     }

//     /**
//      * Export students data
//      */
//     public function export(Request $request)
//     {
//         try {
//             $students = SMstudents::with(['batch', 'course'])->get();
            
//             $filename = 'students_' . date('Y-m-d_His') . '.csv';
            
//             $headers = [
//                 'Content-Type' => 'text/csv',
//                 'Content-Disposition' => 'attachment; filename="' . $filename . '"',
//             ];

//             $callback = function() use ($students) {
//                 $file = fopen('php://output', 'w');
                
//                 // CSV Headers
//                 fputcsv($file, [
//                     'Roll No',
//                     'Student Name',
//                     'Email',
//                     'Phone',
//                     'Batch Name',
//                     'Course Name',
//                     'Course Content',
//                     'Delivery Mode',
//                     'Shift',
//                     'Status'
//                 ]);

//                 // CSV Data
//                 foreach ($students as $student) {
//                     fputcsv($file, [
//                         $student->roll_no,
//                         $student->name,
//                         $student->email,
//                         $student->phone,
//                         $student->batch->name ?? 'N/A',
//                         $student->course->name ?? 'N/A',
//                         $student->course_content,
//                         $student->delivery_mode,
//                         $student->shift,
//                         ucfirst($student->status)
//                     ]);
//                 }

//                 fclose($file);
//             };

//             return response()->stream($callback, 200, $headers);
            
//         } catch (\Exception $e) {
//             Log::error('Error exporting students: ' . $e->getMessage());
//             return back()->with('error', 'Failed to export students: ' . $e->getMessage());
//         }
//     }

//     /**
//      * Get student history/activity log
//      */
//     public function history($id)
//     {
//         try {
//             $student = SMstudents::with(['batch', 'course'])->findOrFail($id);
            
//             return view('student.smstudents.history', compact('student'));
//         } catch (\Exception $e) {
//             Log::error('Error loading history: ' . $e->getMessage());
//             return back()->with('error', 'Failed to load history');
//         }
//     }
// }