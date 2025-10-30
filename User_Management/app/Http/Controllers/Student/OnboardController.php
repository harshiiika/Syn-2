<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Student\Onboard;
use App\Models\Student\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class OnboardController extends Controller
{
    /**
     * Display all onboarded students
     */
    public function index()
    {
        try {
            $students = Onboard::orderBy('onboardedAt', 'desc')->get();
            return view('student.onboard.onboard', compact('students'));
        } catch (\Exception $e) {
            Log::error('Error fetching onboarded students: ' . $e->getMessage());
            return back()->with('error', 'Failed to load students');
        }
    }

    /**
     * Show single student details
     */
    public function show($id)
    {
        try {
            $student = Onboard::findOrFail($id);
            return view('student.onboard.show', compact('student'));
        } catch (\Exception $e) {
            Log::error('Error showing student: ' . $e->getMessage());
            return back()->with('error', 'Student not found');
        }
    }

    /**
     * Show edit form
     */
    public function edit($id)
    {
        try {
            $student = Onboard::findOrFail($id);
            return view('student.onboard.edit', compact('student'));
        } catch (\Exception $e) {
            Log::error('Error loading edit form: ' . $e->getMessage());
            return back()->with('error', 'Failed to load edit form');
        }
    }

    /**
     * Update student details
     */
    public function update(Request $request, $id)
    {
        try {
            $student = Onboard::findOrFail($id);
            
            $validated = $request->validate([
                'name' => 'required|string',
                'father' => 'required|string',
                'mobileNumber' => 'required|string',
                // Add other validation rules as needed
            ]);

            $student->update($validated);
            
            return redirect()
                ->route('student.onboard.onboard')
                ->with('success', 'Student updated successfully');
                
        } catch (\Exception $e) {
            Log::error('Error updating student: ' . $e->getMessage());
            return back()->with('error', 'Failed to update student');
        }
    }

    /**
     * Transfer single student to Pending Fees (Students collection)
     */
    public function transferToPending($id)
    {
        try {
            // Start transaction for data consistency
            DB::connection('mongodb')->getMongoClient()->startSession();
            
            // Find the student in onboarded_students collection
            $onboardedStudent = Onboard::findOrFail($id);
            
            Log::info('Starting transfer for student:', [
                'id' => $id,
                'name' => $onboardedStudent->name
            ]);
            
            // Create new record in students collection with status = 'pending_fees'
            $studentData = [
                // Basic Details
                'name' => $onboardedStudent->name,
                'father' => $onboardedStudent->father,
                'mother' => $onboardedStudent->mother,
                'dob' => $onboardedStudent->dob,
                'mobileNumber' => $onboardedStudent->mobileNumber,
                'fatherWhatsapp' => $onboardedStudent->fatherWhatsapp,
                'motherContact' => $onboardedStudent->motherContact,
                'studentContact' => $onboardedStudent->studentContact,
                'category' => $onboardedStudent->category,
                'gender' => $onboardedStudent->gender,
                'fatherOccupation' => $onboardedStudent->fatherOccupation,
                'fatherGrade' => $onboardedStudent->fatherGrade,
                'motherOccupation' => $onboardedStudent->motherOccupation,
                
                // Address Details
                'state' => $onboardedStudent->state,
                'city' => $onboardedStudent->city,
                'pinCode' => $onboardedStudent->pinCode,
                'address' => $onboardedStudent->address,
                'belongToOtherCity' => $onboardedStudent->belongToOtherCity,
                'economicWeakerSection' => $onboardedStudent->economicWeakerSection,
                'armyPoliceBackground' => $onboardedStudent->armyPoliceBackground,
                'speciallyAbled' => $onboardedStudent->speciallyAbled,
                
                // Course Details
                'courseType' => $onboardedStudent->courseType,
                'courseName' => $onboardedStudent->courseName,
                'deliveryMode' => $onboardedStudent->deliveryMode,
                'medium' => $onboardedStudent->medium,
                'board' => $onboardedStudent->board,
                'courseContent' => $onboardedStudent->courseContent,
                
                // Academic Details
                'previousClass' => $onboardedStudent->previousClass,
                'previousMedium' => $onboardedStudent->previousMedium,
                'schoolName' => $onboardedStudent->schoolName,
                'previousBoard' => $onboardedStudent->previousBoard,
                'passingYear' => $onboardedStudent->passingYear,
                'percentage' => $onboardedStudent->percentage,
                
                // Additional Details
                'isRepeater' => $onboardedStudent->isRepeater,
                'scholarshipTest' => $onboardedStudent->scholarshipTest,
                'lastBoardPercentage' => $onboardedStudent->lastBoardPercentage,
                'competitionExam' => $onboardedStudent->competitionExam,
                
                // Batch Details
                'batchName' => $onboardedStudent->batchName,
                'batchStartDate' => $onboardedStudent->batchStartDate,
                
                // Metadata
                'email' => $onboardedStudent->email,
                'alternateNumber' => $onboardedStudent->alternateNumber,
                'branch' => $onboardedStudent->branch,
                'session' => $onboardedStudent->session,
                'onboardedAt' => $onboardedStudent->onboardedAt,
                
                // IMPORTANT: Set status and fees info
                'status' => 'pending_fees',
                'transferredToPendingFeesAt' => now(),
                'total_fees' => 0,
                'paid_amount' => 0,
                'remaining_fees' => 0,
                'payment_history' => [],
            ];
            
            // Create the student in students collection
            $newStudent = Student::create($studentData);
            
            Log::info('Student created in students collection:', [
                'new_id' => $newStudent->_id,
                'name' => $newStudent->name,
                'status' => $newStudent->status
            ]);
            
            // Delete from onboarded_students collection
            $onboardedStudent->delete();
            
            Log::info('Student deleted from onboarded collection:', [
                'original_id' => $id,
                'name' => $onboardedStudent->name
            ]);
            
            return redirect()
                ->route('student.onboard.onboard')
                ->with('success', "Student {$onboardedStudent->name} transferred to Pending Fees successfully!");
                
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('Student not found in onboarded collection:', ['id' => $id]);
            return back()->with('error', 'Student not found in onboarded students');
            
        } catch (\Exception $e) {
            Log::error('Error transferring student to pending fees:', [
                'id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->with('error', 'Failed to transfer student: ' . $e->getMessage());
        }
    }

    /**
     * Transfer all students to Pending Fees (Students collection)
     */
    public function transferAllToPending()
    {
        try {
            $students = Onboard::all();
            
            if ($students->isEmpty()) {
                return redirect()
                    ->route('student.onboard.onboard')
                    ->with('warning', 'No students to transfer');
            }
            
            Log::info('Starting bulk transfer:', ['total_students' => $students->count()]);
            
            $successCount = 0;
            $failCount = 0;
            $errors = [];
            
            foreach ($students as $onboardedStudent) {
                try {
                    // Create student data with status = 'pending_fees'
                    $studentData = [
                        'name' => $onboardedStudent->name,
                        'father' => $onboardedStudent->father,
                        'mother' => $onboardedStudent->mother,
                        'dob' => $onboardedStudent->dob,
                        'mobileNumber' => $onboardedStudent->mobileNumber,
                        'fatherWhatsapp' => $onboardedStudent->fatherWhatsapp,
                        'motherContact' => $onboardedStudent->motherContact,
                        'studentContact' => $onboardedStudent->studentContact,
                        'category' => $onboardedStudent->category,
                        'gender' => $onboardedStudent->gender,
                        'fatherOccupation' => $onboardedStudent->fatherOccupation,
                        'fatherGrade' => $onboardedStudent->fatherGrade,
                        'motherOccupation' => $onboardedStudent->motherOccupation,
                        'state' => $onboardedStudent->state,
                        'city' => $onboardedStudent->city,
                        'pinCode' => $onboardedStudent->pinCode,
                        'address' => $onboardedStudent->address,
                        'belongToOtherCity' => $onboardedStudent->belongToOtherCity,
                        'economicWeakerSection' => $onboardedStudent->economicWeakerSection,
                        'armyPoliceBackground' => $onboardedStudent->armyPoliceBackground,
                        'speciallyAbled' => $onboardedStudent->speciallyAbled,
                        'courseType' => $onboardedStudent->courseType,
                        'courseName' => $onboardedStudent->courseName,
                        'deliveryMode' => $onboardedStudent->deliveryMode,
                        'medium' => $onboardedStudent->medium,
                        'board' => $onboardedStudent->board,
                        'courseContent' => $onboardedStudent->courseContent,
                        'previousClass' => $onboardedStudent->previousClass,
                        'previousMedium' => $onboardedStudent->previousMedium,
                        'schoolName' => $onboardedStudent->schoolName,
                        'previousBoard' => $onboardedStudent->previousBoard,
                        'passingYear' => $onboardedStudent->passingYear,
                        'percentage' => $onboardedStudent->percentage,
                        'isRepeater' => $onboardedStudent->isRepeater,
                        'scholarshipTest' => $onboardedStudent->scholarshipTest,
                        'lastBoardPercentage' => $onboardedStudent->lastBoardPercentage,
                        'competitionExam' => $onboardedStudent->competitionExam,
                        'batchName' => $onboardedStudent->batchName,
                        'batchStartDate' => $onboardedStudent->batchStartDate,
                        'email' => $onboardedStudent->email,
                        'alternateNumber' => $onboardedStudent->alternateNumber,
                        'branch' => $onboardedStudent->branch,
                        'session' => $onboardedStudent->session,
                        'onboardedAt' => $onboardedStudent->onboardedAt,
                        'status' => 'pending_fees',
                        'transferredToPendingFeesAt' => now(),
                        'total_fees' => 0,
                        'paid_amount' => 0,
                        'remaining_fees' => 0,
                        'payment_history' => [],
                    ];
                    
                    // Create in students collection
                    $newStudent = Student::create($studentData);
                    
                    // Delete from onboarded collection
                    $onboardedStudent->delete();
                    
                    $successCount++;
                    
                    Log::info('Student transferred successfully:', [
                        'name' => $onboardedStudent->name,
                        'new_id' => $newStudent->_id
                    ]);
                    
                } catch (\Exception $e) {
                    $failCount++;
                    $errors[] = "Failed to transfer {$onboardedStudent->name}: {$e->getMessage()}";
                    
                    Log::error('Failed to transfer student:', [
                        'name' => $onboardedStudent->name,
                        'error' => $e->getMessage()
                    ]);
                }
            }
            
            // Build response message
            $message = "Successfully transferred {$successCount} student(s) to Pending Fees.";
            
            if ($failCount > 0) {
                $message .= " {$failCount} student(s) failed to transfer.";
                Log::error('Bulk transfer errors:', $errors);
            }
            
            Log::info('Bulk transfer completed:', [
                'success' => $successCount,
                'failed' => $failCount
            ]);
            
            return redirect()
                ->route('student.onboard.onboard')
                ->with($failCount > 0 ? 'warning' : 'success', $message);
                
        } catch (\Exception $e) {
            Log::error('Error in bulk transfer:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->with('error', 'Failed to transfer students: ' . $e->getMessage());
        }
    }
}