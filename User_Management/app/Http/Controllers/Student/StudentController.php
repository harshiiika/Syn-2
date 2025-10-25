<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Student\Student;
use App\Models\Student\Onboard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class StudentController extends Controller
{
    /**
     * Display pending students (incomplete forms)
     */
    public function index()
    {
        try {
            $students = Student::where('status', Student::STATUS_PENDING_FEES)
                ->orderBy('created_at', 'desc')
                ->get();
            
            Log::info('Fetching pending students:', [
                'count' => $students->count()
            ]);
            
            return view('student.student.pending', compact('students'));
            
        } catch (\Exception $e) {
            Log::error('Error loading pending students: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to load students');
        }
    }

    /**
     * Show edit form for pending student
     */
    public function edit($id)
    {
        try {
            $student = Student::findOrFail($id);
            
            Log::info('Editing pending student:', [
                'student_id' => $id,
                'student_name' => $student->name
            ]);
            
            return view('student.student.edit', compact('student'));
            
        } catch (\Exception $e) {
            Log::error("Edit failed for student ID {$id}: " . $e->getMessage());
            return redirect()->route('student.student.pending')
                ->with('error', 'Student not found');
        }
    }


public function pendingFees()
{
    return view('student.pendingfees.view'); 
}

    /**
     * Update pending student and move to onboarding if ALL fields complete
     */
    public function update(Request $request, $id)
    {
        try {
            Log::info('Update request received for pending student', [
                'id' => $id,
                'data' => $request->except(['_token', '_method'])
            ]);

            $student = Student::findOrFail($id);
            
            // Validate without making fields required (gradual filling allowed)
            $validated = $request->validate([
                // Basic Details
                'name' => 'nullable|string|max:255',
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
                
                // Address Details
                'state' => 'nullable|string',
                'city' => 'nullable|string',
                'pinCode' => 'nullable|string|max:10',
                'address' => 'nullable|string',
                'belongToOtherCity' => 'nullable|in:Yes,No',
                'economicWeakerSection' => 'nullable|in:Yes,No',
                'armyPoliceBackground' => 'nullable|in:Yes,No',
                'speciallyAbled' => 'nullable|in:Yes,No',
                
                // Course Details
                'courseType' => 'nullable|string',
                'courseName' => 'nullable|string',
                'deliveryMode' => 'nullable|string',
                'medium' => 'nullable|string',
                'board' => 'nullable|string',
                'courseContent' => 'nullable|string',
                
                // Academic Details
                'previousClass' => 'nullable|string',
                'previousMedium' => 'nullable|string',
                'schoolName' => 'nullable|string',
                'previousBoard' => 'nullable|string',
                'passingYear' => 'nullable|string|max:4',
                'percentage' => 'nullable|numeric|min:0|max:100',
                
                // Additional Details
                'isRepeater' => 'nullable|in:Yes,No',
                'scholarshipTest' => 'nullable|in:Yes,No',
                'lastBoardPercentage' => 'nullable|numeric|min:0|max:100',
                'competitionExam' => 'nullable|in:Yes,No',
                
                // Batch Details
                'batchName' => 'nullable|string',
                'batchStartDate' => 'nullable|date',
            ]);

            // Only update fields that are actually provided
            $dataToUpdate = array_filter($validated, function($value) {
                return $value !== null && $value !== '';
            });

            // Update the student record
            $student->update($dataToUpdate);

            // Refresh to get updated data
            $student->refresh();

            // Check if ALL required fields are now filled
            $allFieldsFilled = $this->checkAllRequiredFieldsFilled($student);

            Log::info('Completeness check result', [
                'student_id' => $id,
                'all_fields_filled' => $allFieldsFilled
            ]);

            if ($allFieldsFilled) {
                // ALL FIELDS COMPLETE - Move to Onboarding
                Log::info('All required fields filled, moving student to onboarding', [
                    'student_id' => $id,
                    'student_name' => $student->name
                ]);

                // Create onboarded student record (NO FEES FIELDS)
                $onboardData = [
                    // Basic Details
                    'name' => $student->name,
                    'father' => $student->father,
                    'mother' => $student->mother,
                    'dob' => $student->dob,
                    'mobileNumber' => $student->mobileNumber,
                    'fatherWhatsapp' => $student->fatherWhatsapp,
                    'motherContact' => $student->motherContact,
                    'studentContact' => $student->studentContact,
                    'category' => $student->category,
                    'gender' => $student->gender,
                    'fatherOccupation' => $student->fatherOccupation,
                    'fatherGrade' => $student->fatherGrade,
                    'motherOccupation' => $student->motherOccupation,
                    
                    // Address Details
                    'state' => $student->state,
                    'city' => $student->city,
                    'pinCode' => $student->pinCode,
                    'address' => $student->address,
                    'belongToOtherCity' => $student->belongToOtherCity,
                    'economicWeakerSection' => $student->economicWeakerSection,
                    'armyPoliceBackground' => $student->armyPoliceBackground,
                    'speciallyAbled' => $student->speciallyAbled,
                    
                    // Course Details
                    'courseType' => $student->courseType,
                    'courseName' => $student->courseName,
                    'deliveryMode' => $student->deliveryMode,
                    'medium' => $student->medium,
                    'board' => $student->board,
                    'courseContent' => $student->courseContent,
                    
                    // Academic Details
                    'previousClass' => $student->previousClass,
                    'previousMedium' => $student->previousMedium,
                    'schoolName' => $student->schoolName,
                    'previousBoard' => $student->previousBoard,
                    'passingYear' => $student->passingYear,
                    'percentage' => $student->percentage,
                    
                    // Additional Details
                    'isRepeater' => $student->isRepeater,
                    'scholarshipTest' => $student->scholarshipTest,
                    'lastBoardPercentage' => $student->lastBoardPercentage,
                    'competitionExam' => $student->competitionExam,
                    
                    // Batch Details
                    'batchName' => $student->batchName,
                    'batchStartDate' => $student->batchStartDate,
                    
                    // Metadata
                    'email' => $student->email,
                    'alternateNumber' => $student->alternateNumber,
                    'branch' => $student->branch,
                    'session' => $student->session,
                    'onboardedAt' => now(),
                ];

                // Create in onboarded_students collection
                $onboarded = Onboard::create($onboardData);

                if ($onboarded) {
                    // Delete from pending students collection
                    $student->delete();
                    
                    Log::info('Student moved to onboarding successfully', [
                        'student_id' => $id,
                        'onboard_id' => $onboarded->_id
                    ]);

                    return redirect()
                        ->route('student.onboard.onboard')
                        ->with('success', 'Student successfully onboarded! All details are complete.');
                } else {
                    throw new \Exception('Failed to create onboarded student record');
                }

            } else {
                // NOT ALL FIELDS FILLED - Stay in pending
                Log::info('Student updated but still pending (incomplete fields)', [
                    'student_id' => $id,
                    'student_name' => $student->name
                ]);

                return redirect()
                    ->route('student.student.edit', $id)
                    ->with('success', 'Student details updated successfully! Complete remaining fields to onboard.');
            }

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed:', [
                'id' => $id,
                'errors' => $e->errors()
            ]);
            
            return redirect()
                ->back()
                ->withErrors($e->errors())
                ->withInput();
                
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

    /**
     * Check if ALL required fields are filled in the student record
     */
    private function checkAllRequiredFieldsFilled($student)
    {
        // Define ALL fields that MUST be filled for onboarding
        $requiredFields = [
            // Basic Details
            'name',
            'father',
            'mother',
            'dob',
            'mobileNumber',
            'fatherWhatsapp',
            'motherContact',
            'studentContact',
            'category',
            'gender',
            'fatherOccupation',
            'fatherGrade',
            'motherOccupation',
            
            // Address Details
            'state',
            'city',
            'pinCode',
            'address',
            'belongToOtherCity',
            'economicWeakerSection',
            'armyPoliceBackground',
            'speciallyAbled',
            
            // Course Details
            'courseType',
            'courseName',
            'deliveryMode',
            'medium',
            'board',
            'courseContent',
            
            // Academic Details
            'previousClass',
            'previousMedium',
            'schoolName',
            'previousBoard',
            'passingYear',
            'percentage',
            
            // Additional Details
            'isRepeater',
            'scholarshipTest',
            'lastBoardPercentage',
            'competitionExam',
            
            // Batch Details
            'batchName',
            'batchStartDate',
        ];

        $missingFields = [];
        
        foreach ($requiredFields as $field) {
            $value = $student->$field ?? null;
            
            // Check if field is empty, null, or just whitespace
            if ($value === null || $value === '' || (is_string($value) && trim($value) === '')) {
                $missingFields[] = $field;
            }
        }

        if (!empty($missingFields)) {
            Log::info('Missing required fields for onboarding:', [
                'student_id' => $student->_id,
                'missing_count' => count($missingFields),
                'missing_fields' => $missingFields
            ]);
            return false;
        }

        Log::info('All required fields filled for student:', [
            'student_id' => $student->_id
        ]);
        
        return true;
    }
}