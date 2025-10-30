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
<<<<<<< HEAD
     * Display all onboarded students
=======
     * Display all onboarded students (students with status = 'onboarded')
>>>>>>> f511f3813e8a0d0efe4af9d77513ecef0a386326
     */
    public function index()
    {
        try {
            Log::info('=== ONBOARDED STUDENTS PAGE LOADED ===');
            
            // Get students from onboarded_students collection
            $students = Onboard::orderBy('created_at', 'desc')->get();
            
            Log::info('Fetching onboarded students:', [
                'count' => $students->count(),
                'student_ids' => $students->pluck('_id')->toArray(),
                'student_names' => $students->pluck('name')->toArray()
            ]);
            
            return view('student.onboard.onboard', [
                'students' => $students,
                'totalCount' => $students->count()
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error loading onboarded students: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()
                ->with('error', 'Failed to load students: ' . $e->getMessage());
        }
    }

    /**
<<<<<<< HEAD
     * Show single student details
=======
     * View onboarded student details with scholarship and fees information
>>>>>>> f511f3813e8a0d0efe4af9d77513ecef0a386326
     */
    public function show($id)
    {
        try {
            $student = Onboard::findOrFail($id);
<<<<<<< HEAD
            return view('student.onboard.show', compact('student'));
        } catch (\Exception $e) {
            Log::error('Error showing student: ' . $e->getMessage());
            return back()->with('error', 'Student not found');
=======
            
            Log::info('=== VIEWING ONBOARDED STUDENT DETAILS ===', [
                'student_id' => $id,
                'student_name' => $student->name,
                'has_scholarship_data' => !empty($student->eligible_for_scholarship),
                'eligible_for_scholarship' => $student->eligible_for_scholarship ?? 'NOT SET',
                'scholarship_name' => $student->scholarship_name ?? 'NOT SET',
                'total_fee_before_discount' => $student->total_fee_before_discount ?? 'NOT SET',
                'total_fees' => $student->total_fees ?? 'NOT SET',
                'gst_amount' => $student->gst_amount ?? 'NOT SET',
            ]);
            
            // ✅ Prepare fees data array (same structure as InquiryController and StudentController)
            $feesData = [
                'eligible_for_scholarship' => $student->eligible_for_scholarship ?? 'No',
                'scholarship_name' => $student->scholarship_name ?? 'N/A',
                'total_fee_before_discount' => $student->total_fee_before_discount ?? 0,
                'discretionary_discount' => $student->discretionary_discount ?? 'No',
                'discount_percentage' => $student->discount_percentage ?? 0,
                'discounted_fee' => $student->discounted_fee ?? 0,
                'fees_breakup' => $student->fees_breakup ?? 'Class room course (with test series & study material)',
                'total_fees' => $student->total_fees ?? 0,
                'gst_amount' => $student->gst_amount ?? 0,
                'total_fees_inclusive_tax' => $student->total_fees_inclusive_tax ?? 0,
                'single_installment_amount' => $student->single_installment_amount ?? 0,
                'installment_1' => $student->installment_1 ?? 0,
                'installment_2' => $student->installment_2 ?? 0,
                'installment_3' => $student->installment_3 ?? 0,
            ];
            
            Log::info('✅ Fees data prepared for view:', $feesData);
            
            return view('student.onboard.view', compact('student', 'feesData'));
            
        } catch (\Exception $e) {
            Log::error("❌ View failed for onboarded student ID {$id}: " . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return redirect()->route('student.onboard.onboard')
                ->with('error', 'Student not found');
>>>>>>> f511f3813e8a0d0efe4af9d77513ecef0a386326
        }
    }

    /**
     * Show edit form
     */
    public function edit($id)
    {
        try {
            $student = Onboard::findOrFail($id);
<<<<<<< HEAD
            return view('student.onboard.edit', compact('student'));
=======
            
            Log::info('Editing onboarded student:', [
                'student_id' => $id,
                'student_name' => $student->name
            ]);
            
            // Use the same edit view as pending students
            return view('student.onboard.edit', compact('student'));
            
>>>>>>> a1a91f1a0f647cf13c380af20e246aef0762b52e
        } catch (\Exception $e) {
            Log::error('Error loading edit form: ' . $e->getMessage());
            return back()->with('error', 'Failed to load edit form');
        }
    }

    /**
<<<<<<< HEAD
     * Update student details
=======
     * Update onboarded student information
<<<<<<< HEAD
     * This uses the same update logic as StudentController
>>>>>>> a1a91f1a0f647cf13c380af20e246aef0762b52e
=======
>>>>>>> f511f3813e8a0d0efe4af9d77513ecef0a386326
     */
    public function update(Request $request, $id)
    {
        try {
<<<<<<< HEAD
=======
            Log::info('=== ONBOARDED STUDENT UPDATE REQUEST ===', [
                'student_id' => $id,
                'request_keys' => array_keys($request->all())
            ]);

>>>>>>> a1a91f1a0f647cf13c380af20e246aef0762b52e
            $student = Onboard::findOrFail($id);
            
            Log::info('Onboarded student found:', [
                'student_id' => $student->_id,
                'student_name' => $student->name
            ]);

            $validated = $request->validate([
<<<<<<< HEAD
                'name' => 'required|string',
                'father' => 'required|string',
                'mobileNumber' => 'required|string',
                // Add other validation rules as needed
=======
                // Basic Details
                'name' => 'nullable|string|max:255',
                'father' => 'nullable|string|max:255',
                'mother' => 'nullable|string|max:255',
                'dob' => 'nullable|date',
                'mobileNumber' => 'nullable|string|regex:/^[0-9]{10}$/',
                'fatherWhatsapp' => 'nullable|string|regex:/^[0-9]{10}$/',
                'motherContact' => 'nullable|string|regex:/^[0-9]{10}$/',
                'studentContact' => 'nullable|string|regex:/^[0-9]{10}$/',
                'category' => 'nullable|in:GENERAL,OBC,SC,ST',
                'gender' => 'nullable|in:Male,Female,Others',
                'fatherOccupation' => 'nullable|string|max:255',
                'fatherGrade' => 'nullable|string|max:255',
                'motherOccupation' => 'nullable|string|max:255',
                
                // Address Details
                'state' => 'nullable|string|max:255',
                'city' => 'nullable|string|max:255',
                'pinCode' => 'nullable|string|regex:/^[0-9]{6}$/',
                'address' => 'nullable|string',
                'belongToOtherCity' => 'nullable|in:Yes,No',
                'economicWeakerSection' => 'nullable|in:Yes,No',
                'armyPoliceBackground' => 'nullable|in:Yes,No',
                'speciallyAbled' => 'nullable|in:Yes,No',
                
                // Course Details
                'course_type' => 'nullable|string',
                'courseName' => 'nullable|string',
                'deliveryMode' => 'nullable|string',
                'medium' => 'nullable|string',
                'board' => 'nullable|string',
                'courseContent' => 'nullable|string',
                
                // Academic Details
                'previousClass' => 'nullable|string',
                'previousMedium' => 'nullable|string',
                'schoolName' => 'nullable|string|max:255',
                'previousBoard' => 'nullable|string',
                'passingYear' => 'nullable|string|regex:/^[0-9]{4}$/',
                'percentage' => 'nullable|numeric|min:0|max:100',
                
                // Scholarship Eligibility
                'isRepeater' => 'nullable|in:Yes,No',
                'scholarshipTest' => 'nullable|in:Yes,No',
                'lastBoardPercentage' => 'nullable|numeric|min:0|max:100',
                'competitionExam' => 'nullable|in:Yes,No',
                
                // Batch
                'batchName' => 'nullable|string|max:255',
>>>>>>> a1a91f1a0f647cf13c380af20e246aef0762b52e
            ]);

            // Remove null values
            $validated = array_filter($validated, function($value) {
                return $value !== null;
            });

            Log::info('Updating onboarded student with data:', [
                'validated_fields' => array_keys($validated)
            ]);

            // Update the onboarded student
            $student->update($validated);
            $student->refresh();
            
<<<<<<< HEAD
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
=======
            Log::info('Onboarded student updated successfully:', [
                'student_id' => $student->_id
            ]);

            return redirect()->route('student.onboard.onboard')
                ->with('success', 'Student updated successfully');
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('Onboarded student not found:', ['id' => $id]);
            return redirect()->back()
                ->with('error', 'Student not found')
                ->withInput();
                
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed', ['errors' => $e->errors()]);
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
            
        } catch (\Exception $e) {
            Log::error('Error updating onboarded student: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->with('error', 'Failed to update student: ' . $e->getMessage())
                ->withInput();
>>>>>>> a1a91f1a0f647cf13c380af20e246aef0762b52e
        }
    }
}