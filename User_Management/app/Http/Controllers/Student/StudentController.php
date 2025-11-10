<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Student\Pending;
use App\Models\Student\Student;
use App\Models\Student\Inquiry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class StudentController extends Controller
{
    /**
     * Display PENDING INQUIRY students - students with incomplete forms
     * Route: student.student.pending
     */

//this is pending controller named as StudentController 
    public function index()
    {
        try {
            // Get students from student_pending collection
            $students = Pending::orderBy('created_at', 'desc')->get();
            
            Log::info('Fetching pending inquiry students:', [
                'count' => $students->count()
            ]);
            
            return view('student.student.pending', [
                'students' => $students,
                'totalCount' => $students->count(),
            ]);
        } catch (\Exception $e) {
            Log::error('Error loading pending students: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to load students');
        }
    }

    /**
     * Edit student form (for pending students)
     */
    public function edit($id)
    {
        try {
            $student = Pending::findOrFail($id);
            
            Log::info('Editing pending student:', [
                'student_id' => $id,
                'student_name' => $student->name
            ]);
            
            return view('student.student.edit', compact('student'));
            
        } catch (\Exception $e) {
            Log::error("Edit failed for student ID {$id}: " . $e->getMessage());
            return redirect()->back()->with('error', 'Student not found');
        }
    }

    /**
     * Update pending student information with file uploads
     */
   public function update(Request $request, $id)
{
    try {
        Log::info('=== PENDING STUDENT UPDATE START ===', [
            'student_id' => $id
        ]);

        $student = Pending::findOrFail($id);
        
        Log::info('Pending student found:', [
            'name' => $student->name
        ]);

        // Validate all fields
        $validated = $request->validate([
            // Basic Details
            'name' => 'required|string|max:255',
            'father' => 'required|string|max:255',
            'mother' => 'required|string|max:255',
            'dob' => 'required|date',
            'mobileNumber' => 'required|string|regex:/^[0-9]{10}$/',
            'fatherWhatsapp' => 'required|string|regex:/^[0-9]{10}$/',
            'motherContact' => 'required|string|regex:/^[0-9]{10}$/',
            'studentContact' => 'required|string|regex:/^[0-9]{10}$/',
            'category' => 'required|in:GENERAL,OBC,SC,ST',
            'gender' => 'required|in:Male,Female,Others',
            'fatherOccupation' => 'required|string',
            'motherOccupation' => 'required|string',
            
            // Address Details
            'state' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'pinCode' => 'required|string|regex:/^[0-9]{6}$/',
            'address' => 'required|string',
            'belongToOtherCity' => 'required|in:Yes,No',
            'economicWeakerSection' => 'required|in:Yes,No',
            'armyPoliceBackground' => 'required|in:Yes,No',
            'speciallyAbled' => 'required|in:Yes,No',
            
            // Course Details
            'course_type' => 'required|string',
            'courseName' => 'required|string',
            'deliveryMode' => 'required|string',
            'medium' => 'required|string',
            'board' => 'required|string',
            'courseContent' => 'required|string',
            
            // Academic Details
            'previousClass' => 'required|string',
            'previousMedium' => 'required|string',
            'schoolName' => 'required|string|max:255',
            'previousBoard' => 'required|string',
            'passingYear' => 'required|string|regex:/^[0-9]{4}$/',
            'percentage' => 'required|numeric|min:0|max:100',
            
            // Scholarship Eligibility
            'isRepeater' => 'required|in:Yes,No',
            'scholarshipTest' => 'required|in:Yes,No',
            'lastBoardPercentage' => 'required|numeric|min:0|max:100',
            'competitionExam' => 'required|in:Yes,No',
            
            // Batch
            'batchName' => 'required|string|max:255',
            
            // File uploads (optional on update if already uploaded)
            'passport_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'marksheet' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:5120',
            'caste_certificate' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:5120',
            'scholarship_proof' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:5120',
            'secondary_marksheet' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:5120',
            'senior_secondary_marksheet' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:5120',
        ]);

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
                $file = $request->file($field);
                $fileContent = file_get_contents($file->getRealPath());
                $base64 = base64_encode($fileContent);
                $mimeType = $file->getMimeType();
                $validated[$field] = "data:{$mimeType};base64,{$base64}";
                
                Log::info("File uploaded: {$field}");
            }
        }

        // Store courseType for compatibility
        $validated['courseType'] = $validated['course_type'];

        Log::info('✅ ALL REQUIRED FIELDS FILLED - MOVING TO ONBOARDED');

        try {
            // Create in student_onboard collection
            $onboardData = array_merge($student->toArray(), $validated);
            $onboardData['status'] = 'onboarded';
            $onboardData['onboardedAt'] = now();
            unset($onboardData['_id']); // Remove old _id to create new
            
            $onboardStudent = \App\Models\Student\Onboard::create($onboardData);
            
            // Delete from pending
            $student->delete();
            
            Log::info('✅ Student moved to onboard collection:', [
                'new_id' => $onboardStudent->_id,
                'name' => $onboardStudent->name
            ]);

            return redirect()->route('student.onboard.onboard')
                ->with('success', 'Student onboarding completed! Student moved to Onboarding list.');
                
        } catch (\Exception $e) {
            Log::error('❌ ERROR MOVING STUDENT:', [
                'error' => $e->getMessage()
            ]);
            
            return redirect()->route('student.student.pending')
                ->with('error', 'Failed to complete onboarding: ' . $e->getMessage());
        }
    
    } catch (\Illuminate\Validation\ValidationException $e) {
        Log::error('Validation failed', ['errors' => $e->errors()]);
        return redirect()->back()
            ->withErrors($e->errors())
            ->withInput();
        
    } catch (\Exception $e) {
        Log::error('Error updating student: ' . $e->getMessage());
        return redirect()->back()
            ->with('error', 'Failed to update student: ' . $e->getMessage())
            ->withInput();
    }
}

    /**
     * Check if all required fields are filled
     */
    private function checkAllFieldsFilled($student)
    {
        $requiredFields = [
            'name', 'father', 'mother', 'dob', 'mobileNumber', 
            'fatherWhatsapp', 'motherContact', 'studentContact',
            'category', 'gender', 'fatherOccupation', 'motherOccupation',
            'state', 'city', 'pinCode', 'address',
            'belongToOtherCity', 'economicWeakerSection', 
            'armyPoliceBackground', 'speciallyAbled',
            'course_type', 'courseName', 'deliveryMode', 'medium', 
            'board', 'courseContent',
            'previousClass', 'previousMedium', 'schoolName', 
            'previousBoard', 'passingYear', 'percentage',
            'isRepeater', 'scholarshipTest', 'lastBoardPercentage', 
            'competitionExam', 'batchName',
            'passport_photo', 'marksheet' // Required files
        ];

        foreach ($requiredFields as $field) {
            $value = $student->$field;
            if ($value === null || $value === '' || (is_string($value) && trim($value) === '')) {
                Log::info("Missing field: {$field}");
                return false;
            }
        }
        
        return true;
    }
}
