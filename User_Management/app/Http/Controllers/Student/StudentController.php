<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Student\Pending;
use App\Models\Student\Student;
use App\Models\Student\Onboard;
use App\Models\Student\Inquiry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class StudentController extends Controller
{
    /**
     * Display PENDING INQUIRY students - students with incomplete forms
     * Route: student.student.pending
     * this is pending lists ka controller 
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
            'student_id' => $id,
            'has_passport_photo' => $request->hasFile('passport_photo'),
            'has_marksheet' => $request->hasFile('marksheet'),
            'has_secondary_marksheet' => $request->hasFile('secondary_marksheet')
        ]);

        $student = Pending::findOrFail($id);
        
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

        // ✅ CRITICAL FIX: Handle file uploads and preserve existing documents
        $fileFields = [
            'passport_photo',
            'marksheet', 
            'caste_certificate',
            'scholarship_proof',
            'secondary_marksheet',
            'senior_secondary_marksheet'
        ];

        $documentData = [];
        
        foreach ($fileFields as $field) {
            if ($request->hasFile($field)) {
                // New file uploaded - convert to Base64
                $file = $request->file($field);
                $fileContent = file_get_contents($file->getRealPath());
                $base64 = base64_encode($fileContent);
                $mimeType = $file->getMimeType();
                $documentData[$field] = "data:{$mimeType};base64,{$base64}";
                
                Log::info("✅ New file uploaded: {$field}", [
                    'mime_type' => $mimeType,
                    'size' => strlen($base64)
                ]);
            } else {
                // No new file - preserve existing document if it exists
                if (!empty($student->$field) && $student->$field !== 'N/A') {
                    $documentData[$field] = $student->$field;
                    Log::info("✅ Preserved existing document: {$field}");
                }
            }
        }

        // Store courseType for compatibility
        $validated['courseType'] = $validated['course_type'];

        Log::info('✅ ALL REQUIRED FIELDS FILLED - MOVING TO ONBOARDED', [
            'documents_count' => count(array_filter($documentData))
        ]);

        try {
            // ✅ Create in student_onboard collection WITH ALL DOCUMENTS
            $onboardData = array_merge($student->toArray(), $validated, $documentData);
            $onboardData['status'] = 'onboarded';
            $onboardData['onboardedAt'] = now();
            unset($onboardData['_id']); // Remove old _id to create new
            
            Log::info('✅ Onboard data prepared:', [
                'documents' => [
                    'passport_photo' => !empty($onboardData['passport_photo']),
                    'marksheet' => !empty($onboardData['marksheet']),
                    'secondary_marksheet' => !empty($onboardData['secondary_marksheet']),
                    'senior_secondary_marksheet' => !empty($onboardData['senior_secondary_marksheet']),
                    'caste_certificate' => !empty($onboardData['caste_certificate']),
                    'scholarship_proof' => !empty($onboardData['scholarship_proof']),
                ]
            ]);
            
            $onboardStudent = \App\Models\Student\Onboard::create($onboardData);
            
            // Delete from pending
            $student->delete();
            
            Log::info('✅ Student moved to onboard collection WITH DOCUMENTS:', [
                'new_id' => $onboardStudent->_id,
                'name' => $onboardStudent->name,
                'has_passport_photo' => !empty($onboardStudent->passport_photo),
                'has_marksheet' => !empty($onboardStudent->marksheet),
                'has_secondary_marksheet' => !empty($onboardStudent->secondary_marksheet)
            ]);

            return redirect()->route('student.onboard.onboard')
                ->with('success', 'Student onboarding completed! All documents transferred successfully.');
                
        } catch (\Exception $e) {
            Log::error('❌ ERROR MOVING STUDENT:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
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

    
    /**
     * Transfer student from Pending to Onboard with proper history tracking
     */
    public function transferToOnboard(Request $request, $id)
    {
        try {
            Log::info('=== TRANSFER TO ONBOARD START ===', ['pending_id' => $id]);
            
            $pendingStudent = Pending::findOrFail($id);
            
            // Prepare onboard data
            $onboardData = $pendingStudent->toArray();
            unset($onboardData['_id']);
            
            // Set onboard metadata
            $onboardData['status'] = 'onboarded';
            $onboardData['transferred_from'] = 'pending';
            $onboardData['onboardedAt'] = now();
            $onboardData['transferred_at'] = now();
            $onboardData['transferred_by'] = auth()->user()->email ?? 'Admin';
            
            // ✅ BUILD COMPLETE HISTORY - Transfer existing history from inquiry
            $completeHistory = [];
            
            // 1. Get history from pending student (which came from inquiry)
            if (isset($pendingStudent->history) && is_array($pendingStudent->history)) {
                $completeHistory = $pendingStudent->history;
            }
            
            // 2. Add "Transferred to Onboard" entry
            $onboardHistoryEntry = [
                'action' => 'Student Onboarded',
                'description' => 'Student successfully onboarded and transferred to onboarding collection',
                'changed_by' => auth()->user()->name ?? auth()->user()->email ?? 'Admin',
                'timestamp' => now()->toIso8601String(),
                'date' => now()->format('d M Y, h:i A')
            ];
            
            // Add to beginning of array (newest first)
            array_unshift($completeHistory, $onboardHistoryEntry);
            
            // Set the complete history
            $onboardData['history'] = $completeHistory;
            
            Log::info('Creating onboard student with complete history', [
                'student_name' => $pendingStudent->name,
                'history_count' => count($completeHistory)
            ]);
            
            // Create in onboard collection
            $onboardStudent = Onboard::create($onboardData);
            
            Log::info('✅ Onboard student created', [
                'onboard_id' => $onboardStudent->_id,
                'name' => $onboardStudent->name,
                'history_entries' => count($onboardStudent->history ?? [])
            ]);
            
            // Delete from pending
            $pendingStudent->delete();
            
            Log::info('✅ Transfer to onboard complete');
            
            return redirect()->route('student.onboard.onboard')
                ->with('success', "Student '{$onboardStudent->name}' successfully onboarded!");
                
        } catch (\Exception $e) {
            Log::error('❌ Transfer to onboard failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()
                ->with('error', 'Failed to onboard student: ' . $e->getMessage());
        }
    }

}
