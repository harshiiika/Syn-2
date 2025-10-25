<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student\Student;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use App\Models\Student\Pending;

class PendingFeesController extends Controller
{
    /**
     * Display all students with pending fees
     */
    public function index()
    {
        try {
            // Try to get data from Student model first (your original approach)
            if (method_exists(Student::class, 'getPendingFeesStudents')) {
                $pendingFees = Student::getPendingFeesStudents();
            } else {
                // Fallback to Pending model
                $pendingFees = Pending::all();
            }

            Log::info('Fetching pending fees students:', [
                'count' => $pendingFees->count()
            ]);

            return view('student.pendingfees.pending', [
                'pendingFees' => $pendingFees,
                'totalCount' => $pendingFees->count(),
            ]);
        } catch (\Exception $e) {
            Log::error('Error loading pending fees students: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to load students');
        }
    }

    /**
     * Display the student details (read-only view)
     */
    public function view(string $id)
    {
        try {
            // Try to fetch from Student model first, then Pending model
            $student = null;
            
            try {
                $student = Student::findOrFail($id);
            } catch (\Exception $e) {
                // If not found in Student, try Pending model
                $student = Pending::findOrFail($id);
            }
            
            Log::info('Viewing pending student details:', [
                'student_id' => $id,
                'student_name' => $student->name ?? 'N/A'
            ]);
            
            // Return the view with student data
            return view('student.pendingfees.view', compact('student'));
            
        } catch (\Exception $e) {
            Log::error("View failed for student ID {$id}: " . $e->getMessage());
            return redirect()->route('student.pendingfees.pending')
                ->with('error', 'Student not found');
        }
    }

    /**
     * Show the edit form for a specific student
     */
    public function edit($id) 
    {
        try {
            // Try to fetch from Student model first, then Pending model
            $student = null;
            
            try {
                $student = Student::findOrFail($id);
            } catch (\Exception $e) {
                // If not found in Student, try Pending model
                $student = Pending::findOrFail($id);
            }
            
            Log::info('Editing pending student:', [
                'student_id' => $id,
                'student_name' => $student->name ?? 'N/A'
            ]);
            
            return view('student.pendingfees.edit', compact('student'));
            
        } catch (\Exception $e) {
            Log::error("Edit failed for student ID {$id}: " . $e->getMessage());
            return redirect()->route('student.pendingfees.pending')
                ->with('error', 'Student not found');
        }
    }

    /**
     * Update the student information
     */
    public function update(Request $request, string $id)
{
    try {
        Log::info('Update request received', [
            'id' => $id,
            'data' => $request->except(['_token', '_method'])
        ]);

        // Find the student
        $student = Pending::findOrFail($id);
        
        Log::info('Student found', ['name' => $student->name]);

        // Validation rules
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'father' => 'nullable|string|max:255',
            'mother' => 'nullable|string|max:255',
            'dob' => 'nullable|date',
            'mobileNumber' => 'nullable|string|max:15',
            'fatherWhatsapp' => 'nullable|string|max:15',
            'motherContact' => 'nullable|string|max:15',
            'studentContact' => 'nullable|string|max:15',
            'category' => 'nullable|in:OBC,SC,GENERAL,ST',
            'gender' => 'nullable|in:Male,Female,Others',
            'fatherOccupation' => 'nullable|string',
            'fatherGrade' => 'nullable|string',
            'motherOccupation' => 'nullable|string',
            'state' => 'nullable|string',
            'city' => 'nullable|string',
            'pinCode' => 'nullable|string|max:10',
            'address' => 'nullable|string',
            'belongToOtherCity' => 'nullable|in:Yes,No',
            'economicWeakerSection' => 'nullable|in:Yes,No',
            'armyPoliceBackground' => 'nullable|in:Yes,No',
            'speciallyAbled' => 'nullable|in:Yes,No',
            'courseType' => 'nullable|string',
            'courseName' => 'nullable|string',
            'deliveryMode' => 'nullable|string',
            'medium' => 'nullable|string',
            'board' => 'nullable|string',
            'courseContent' => 'nullable|string',
            'previousClass' => 'nullable|string',
            'previousMedium' => 'nullable|string',
            'schoolName' => 'nullable|string',
            'previousBoard' => 'nullable|string',
            'passingYear' => 'nullable|string',
            'percentage' => 'nullable|numeric|min:0|max:100',
            'isRepeater' => 'nullable|in:Yes,No',
            'scholarshipTest' => 'nullable|in:Yes,No',
            'lastBoardPercentage' => 'nullable|numeric|min:0|max:100',
            'competitionExam' => 'nullable|in:Yes,No',
            'batchName' => 'nullable|string',
        ]);

        Log::info('Validation passed');

        // Update the student
        $student->update($validated);
        
        Log::info('Student updated successfully', [
            'id' => $id,
            'name' => $student->name
        ]);

        return redirect()
            ->route('student.pendingfees.pending')
            ->with('success', 'Student details updated successfully!');

    } catch (\Illuminate\Validation\ValidationException $e) {
        Log::error('Validation error:', ['errors' => $e->errors()]);
        return redirect()
            ->back()
            ->withErrors($e->errors())
            ->withInput();
            
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        Log::error('Student not found:', ['id' => $id]);
        return redirect()
            ->route('student.pendingfees.pending')
            ->with('error', 'Student not found');
            
    } catch (\Exception $e) {
        Log::error('Update failed:', [
            'id' => $id,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        
        return redirect()
            ->back()
            ->with('error', 'Failed to update student: ' . $e->getMessage())
            ->withInput();
    }
}

}