<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student\SMstudents;
use App\Models\Master\Batch;
use App\Models\Master\Courses;
use App\Models\Student\Shift; 
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class SmStudentsController extends Controller
{
    /**
     * Display a listing of students
     */
public function index()
{
    try {
        $students = SMstudents::with(['batch', 'course'])->get();
        $batches = Batch::orderBy('name')->get(); // ✅ Fetch batches sorted by name
        $courses = Courses::all();
        $shifts = Shift::where('is_active', true)->get();

        return view('student.smstudents.smstudents', compact('students', 'batches', 'courses', 'shifts'));
    } catch (\Exception $e) {
        Log::error('Error loading students: ' . $e->getMessage());
        return back()->with('error', 'Failed to load students');
    }
}


    /**
     * Display the specified student with full details
     */
    public function show($id)
    {
        try {
            $student = SMstudents::with(['batch', 'course', 'shift'])->findOrFail($id); // ✅ ADDED: 'shift' relationship
            
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
     * Show the form for editing student (NEW METHOD)
     */
    public function edit($id)
    {
        try {
            $student = SMstudents::with(['batch', 'course', 'shift'])->findOrFail($id); // ✅ ADDED: 'shift' relationship
            $batches = Batch::all();
            $courses = Courses::all();
            $shifts = Shift::where('is_active', true)->get(); // ✅ ADDED: Get shifts for edit form
            
            return view('student.smstudents.edit', compact('student', 'batches', 'courses', 'shifts')); // ✅ ADDED: 'shifts'
        } catch (\Exception $e) {
            Log::error('Error loading edit form: ' . $e->getMessage());
            return back()->with('error', 'Failed to load student data');
        }
    }

    /**
     * Update the specified student (UPDATED METHOD)
     */
    public function update(Request $request, $id)
    {
        $student = SMstudents::findOrFail($id);

        // Validation rules for the comprehensive form
        $validator = Validator::make($request->all(), [
            // Basic validation (existing)
            'roll_no' => 'nullable|unique:smstudents,roll_no,' . $id . ',_id',
            'student_name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:smstudents,email,' . $id . ',_id',
            'phone' => 'required|string|max:15',
            
            // New comprehensive validations
            'father_name' => 'nullable|string|max:255',
            'mother_name' => 'nullable|string|max:255',
            'dob' => 'nullable|date',
            'father_contact' => 'nullable|string|max:15',
            'father_whatsapp' => 'nullable|string|max:15',
            'mother_contact' => 'nullable|string|max:15',
            'gender' => 'nullable|in:Male,Female,Others',
            'father_occupation' => 'nullable|string|max:255',
            'father_caste' => 'nullable|string|max:255',
            'mother_occupation' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'pincode' => 'nullable|string|max:6',
            'address' => 'nullable|string',
            'belongs_other_city' => 'nullable|in:Yes,No',
            
            // Academic details
            'previous_class' => 'nullable|string|max:255',
            'academic_medium' => 'nullable|string|max:255',
            'school_name' => 'nullable|string|max:255',
            'academic_board' => 'nullable|string|max:255',
            'passing_year' => 'nullable|string|max:4',
            'percentage' => 'nullable|string|max:10',
            
            // Course related (existing)
            'batch_id' => 'nullable',
            'course_id' => 'nullable',
            'course_content' => 'nullable|string',
            'delivery_mode' => 'nullable|in:Offline,Online,Hybrid',
            'shift_id' => 'nullable|exists:shifts,_id', // ✅ CHANGED: From 'shift' to 'shift_id'
            'status' => 'nullable|in:active,inactive',
            
            // File uploads
            'passport_photo' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
            'marksheet' => 'nullable|mimes:pdf,jpeg,jpg,png|max:5120',
            'caste_certificate' => 'nullable|mimes:pdf,jpeg,jpg,png|max:5120',
            'scholarship_proof' => 'nullable|mimes:pdf,jpeg,jpg,png|max:5120',
            'secondary_marksheet' => 'nullable|mimes:pdf,jpeg,jpg,png|max:5120',
            'senior_secondary_marksheet' => 'nullable|mimes:pdf,jpeg,jpg,png|max:5120',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput()->with('error', 'Validation failed');
        }

        try {
            // ✅ ADDED: Handle shift update
            $shiftId = null;
            $shiftName = null;
            if ($request->filled('shift_id')) {
                $shiftId = $request->shift_id;
                $shift = Shift::find($shiftId);
                $shiftName = $shift ? $shift->name : null;
            }

            // Prepare update data (all fields)
            $updateData = [
                // Basic info
                'roll_no' => $request->roll_no,
                'student_name' => $request->student_name,
                'email' => $request->email,
                'phone' => $request->phone,
                
                // Personal details
                'father_name' => $request->father_name,
                'mother_name' => $request->mother_name,
                'dob' => $request->dob,
                'father_contact' => $request->father_contact,
                'father_whatsapp' => $request->father_whatsapp,
                'mother_contact' => $request->mother_contact,
                'gender' => $request->gender,
                'father_occupation' => $request->father_occupation,
                'father_caste' => $request->father_caste,
                'mother_occupation' => $request->mother_occupation,
                'state' => $request->state,
                'city' => $request->city,
                'pincode' => $request->pincode,
                'address' => $request->address,
                'belongs_other_city' => $request->belongs_other_city ?? 'No',
                
                // Academic details
                'previous_class' => $request->previous_class,
                'academic_medium' => $request->academic_medium,
                'school_name' => $request->school_name,
                'academic_board' => $request->academic_board,
                'passing_year' => $request->passing_year,
                'percentage' => $request->percentage,
                
                // Course details
                'batch_id' => $request->batch_id,
                'course_id' => $request->course_id,
                'course_content' => $request->course_content,
                'delivery_mode' => $request->delivery_mode,
                'shift_id' => $shiftId, // ✅ ADDED: Store shift_id
                'shift' => $shiftName, // ✅ ADDED: Store shift name for backward compatibility
                'status' => $request->status ?? 'active',
            ];

            // Handle file uploads
            $fileFields = [
                'passport_photo',
                'marksheet',
                'caste_certificate',
                'scholarship_proof',
                'secondary_marksheet',
                'senior_secondary_marksheet'
            ];

            foreach ($fileFields as $field) {
                if ($request->hasFile($field)) {
                    // Delete old file if exists
                    if (isset($student->$field) && Storage::disk('public')->exists($student->$field)) {
                        Storage::disk('public')->delete($student->$field);
                    }

                    // Store new file
                    $file = $request->file($field);
                    $filename = time() . '_' . $field . '_' . $file->getClientOriginalName();
                    $path = $file->storeAs('students/documents', $filename, 'public');
                    $updateData[$field] = $path;
                }
            }

            // Handle password update if provided
            if ($request->filled('password')) {
                $updateData['password'] = Hash::make($request->password);
            }

            // Update the student
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
     * Update student batch */

    public function updateBatch(Request $request, $id)
{
    try {
        $request->validate([
            'batch_id' => 'required'
        ]);

        $student = SMstudents::findOrFail($id);
        $student->batch_id = $request->batch_id;
        $student->save();

        return response()->json(['success' => true]);
    } catch (\Exception $e) {
        \Log::error('Batch update failed: ' . $e->getMessage());
        return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
    }
}

    /**
     * Update student shift (✅ FIXED METHOD)
     */
    public function updateShift(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'shift_id' => 'required|exists:shifts,_id'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->with('error', 'Shift validation failed');
        }
        
        try {
            $student = SMstudents::findOrFail($id);
            
            // Get shift name for backward compatibility
            $shift = Shift::find($request->shift_id);
            
            $student->update([
                'shift_id' => $request->shift_id,
                'shift' => $shift ? $shift->name : null,
            ]);
            
            Log::info('Shift updated for student:', [
                'student_id' => (string)$student->_id,
                'student_name' => $student->student_name,
                'new_shift_id' => (string)$request->shift_id,
                'new_shift_name' => $shift ? $shift->name : null
            ]);
            
            return redirect()->back()->with('success', 'Shift updated successfully!');
        } catch (\Exception $e) {
            Log::error('Error updating shift: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to update shift: ' . $e->getMessage());
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
            $students = SMstudents::with(['batch', 'course', 'shift'])->get(); // ✅ ADDED: 'shift' relationship
            
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
                        $student->student_name ?? $student->name,
                        $student->email,
                        $student->phone,
                        $student->batch->name ?? 'N/A',
                        $student->course->name ?? 'N/A',
                        $student->course_content,
                        $student->delivery_mode,
                        $student->shift->name ?? $student->shift ?? 'N/A', // ✅ FIXED: Use relationship first
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
            $student = SMstudents::with(['batch', 'course', 'shift'])->findOrFail($id); // ✅ ADDED: 'shift' relationship
            
            return view('student.smstudents.history', compact('student'));
        } catch (\Exception $e) {
            Log::error('Error loading history: ' . $e->getMessage());
            return back()->with('error', 'Failed to load history');
        }
    }
}