<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student\Onboard;
use App\Models\Student\PendingFee;
use Illuminate\Support\Facades\Log;
use App\Models\Student\SMstudents;
use MongoDB\BSON\ObjectId;
use Illuminate\Support\Facades\DB;
use App\Services\RollNumberService;
use App\Models\Student\Shift; 

class OnboardController extends Controller
{
    /**
     * Display all onboarded students
     */
    public function index()
    {
        try {
            Log::info('=== ONBOARDING PAGE LOADED ===');
            
            // Query students with 'onboarded' status from students collection
            $students = DB::connection('mongodb')
                ->table('students')
                ->where('status', 'onboarded')
                ->orderBy('created_at', 'desc')
                ->get();
            
            Log::info('Fetching onboarded students:', [
                'count' => count($students),
                'student_ids' => collect($students)->pluck('_id')->map(fn($id) => (string)$id)->toArray(),
                'student_names' => collect($students)->pluck('name')->toArray()
            ]);
            
            return view('student.onboard.onboard', [
                'students' => $students,
                'totalCount' => count($students)
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
     * View onboarded student details with scholarship and fees information
     */
    public function show($id)
    {
        try {
            // Query from 'students' collection
            $studentArray = DB::connection('mongodb')
                ->table('students')
                ->where('_id', $id)
                ->first();
            
            if (!$studentArray) {
                Log::error(' Onboarded student NOT FOUND', ['id' => $id]);
                return redirect()->route('student.onboard.onboard')
                    ->with('error', 'Student not found');
            }
            
            // Convert array to object
            $student = (object) $studentArray;
            
            // 🔍 DEBUG: Log what we got from database
            Log::info('=== STUDENT DATA FROM DATABASE ===', [
                'student_id' => $id,
                'student_name' => $student->name ?? 'N/A',
                'total_fees' => $student->total_fees ?? 'MISSING',
                'gst_amount' => $student->gst_amount ?? 'MISSING',
                'total_fees_inclusive_tax' => $student->total_fees_inclusive_tax ?? 'MISSING',
            ]);
            
            //   Access properties correctly (stdClass object, not array)
            $feesData = [
                // Scholarship info
                'eligible_for_scholarship' => $student->eligible_for_scholarship ?? 'No',
                'scholarship_name' => $student->scholarship_name ?? 'N/A',
                
                // Base fees - IMPORTANT: Check both possibilities
                'total_fee_before_discount' => isset($student->total_fee_before_discount) 
                    ? (float)$student->total_fee_before_discount 
                    : (isset($student->total_fees) ? (float)$student->total_fees : 0),
                
                // Discretionary discount
                'discretionary_discount' => $student->discretionary_discount ?? 'No',
                'discount_percentage' => isset($student->discount_percentage) 
                    ? (float)$student->discount_percentage 
                    : 0,
                'discounted_fee' => isset($student->discounted_fee) 
                    ? (float)$student->discounted_fee 
                    : (isset($student->total_fees) ? (float)$student->total_fees : 0),
                
                // Fees breakdown
                'fees_breakup' => $student->fees_breakup 
                    ?? 'Class room course (with test series & study material)',
                'total_fees' => isset($student->total_fees) 
                    ? (float)$student->total_fees 
                    : 0,
                'gst_amount' => isset($student->gst_amount) 
                    ? (float)$student->gst_amount 
                    : 0,
                'total_fees_inclusive_tax' => isset($student->total_fees_inclusive_tax) 
                    ? (float)$student->total_fees_inclusive_tax 
                    : 0,
                
                // Installments
                'single_installment_amount' => isset($student->single_installment_amount) 
                    ? (float)$student->single_installment_amount 
                    : 0,
                'installment_1' => isset($student->installment_1) 
                    ? (float)$student->installment_1 
                    : 0,
                'installment_2' => isset($student->installment_2) 
                    ? (float)$student->installment_2 
                    : 0,
                'installment_3' => isset($student->installment_3) 
                    ? (float)$student->installment_3 
                    : 0,
            ];
            
            // 🔍 Log what we're sending to the view
            Log::info('  Fees data being sent to view:', $feesData);
            
            return view('student.onboard.view', compact('student', 'feesData'));
            
        } catch (\Exception $e) {
            Log::error(" View failed for student ID {$id}: " . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return redirect()->route('student.onboard.onboard')
                ->with('error', 'Student not found');
        }
    }

    /**
     * Edit onboarded student
     */
    public function edit($id)
    {
        try {
            // Query from 'students' collection (same as index and transfer methods)
            $studentArray = DB::connection('mongodb')
                ->table('students')
                ->where('_id', $id)
                ->first();
            
            if (!$studentArray) {
                Log::error(' Onboarded student NOT FOUND for edit', ['id' => $id]);
                return redirect()->route('student.onboard.onboard')
                    ->with('error', 'Student not found');
            }
            
            // Convert array to object for compatibility with blade templates
            $student = (object) $studentArray;
            
            Log::info('Editing onboarded student:', [
                'student_id' => $id,
                'student_name' => $student->name ?? 'N/A'
            ]);
            
            return view('student.onboard.edit', compact('student'));
            
        } catch (\Exception $e) {
            Log::error("Edit failed for student ID {$id}: " . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return redirect()->route('student.onboard.onboard')
                ->with('error', 'Student not found');
        }
    }

    /**
     * Update onboarded student information
     */
    public function update(Request $request, $id)
    {
        try {
            Log::info('=== ONBOARDED STUDENT UPDATE REQUEST ===', [
                'student_id' => $id,
                'request_keys' => array_keys($request->all())
            ]);

            // Check if student exists in 'students' collection first
            $studentExists = DB::connection('mongodb')
                ->table('students')
                ->where('_id', $id)
                ->first();
            
            if (!$studentExists) {
                Log::error('Onboarded student not found:', ['id' => $id]);
                return redirect()->back()
                    ->with('error', 'Student not found')
                    ->withInput();
            }
            
            Log::info('Onboarded student found:', [
                'student_id' => $id,
                'student_name' => $studentExists->name ?? 'N/A'
            ]);

            $validated = $request->validate([
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
            ]);

            // Remove null values
            $validated = array_filter($validated, function($value) {
                return $value !== null;
            });

            // Add updated_at timestamp
            $validated['updated_at'] = now();

            Log::info('Updating onboarded student with data:', [
                'validated_fields' => array_keys($validated)
            ]);

            // Update using DB query builder (same as transfer method uses)
            DB::connection('mongodb')
                ->table('students')
                ->where('_id', $id)
                ->update($validated);
            
            Log::info('Onboarded student updated successfully:', [
                'student_id' => $id
            ]);

            return redirect()->route('student.onboard.onboard')
                ->with('success', 'Student updated successfully');
            
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
        }
    }

    /**
     * Transfer an onboarded student to Active Students (SMstudents)
     * ✅ WITH ROLL NUMBER SERVICE INTEGRATION
     * ✅ WITH DYNAMIC SHIFT INTEGRATION
     */
    public function transfer($id)
    {
        try {
            Log::info("=== TRANSFER ATTEMPT === ", [
                'onboard_id' => $id,
                'id_type' => gettype($id)
            ]);

            // Find student in 'students' collection
            $onboardStudentArray = DB::connection('mongodb')
                ->table('students')
                ->where('_id', $id)
                ->first();
            
            if (!$onboardStudentArray) {
                Log::error(' Onboard student NOT FOUND', ['id' => $id]);
                return redirect()->route('student.onboard.onboard')
                    ->with('error', 'Student not found in onboard list');
            }
            
            // Convert array to object - DB::table returns arrays
            $onboardStudent = (object) $onboardStudentArray;
            
            // MongoDB returns 'id' not '_id' when using DB::table
            $studentId = $onboardStudent->id ?? $onboardStudent->_id ?? $id;
            
            Log::info('  Onboard student FOUND:', [
                'id' => $studentId,
                'name' => $onboardStudent->name ?? 'N/A',
                'course_name' => $onboardStudent->courseName ?? $onboardStudent->course ?? 'N/A',
                'batch_name' => $onboardStudent->batchName ?? $onboardStudent->batch_name ?? 'N/A',
            ]);

            //   Generate Roll Number using RollNumberService
            $rollNumber = $onboardStudent->roll_no ?? RollNumberService::generateUniqueRollNumber(
                $onboardStudent->course_id ?? null,
                $onboardStudent->courseName ?? $onboardStudent->course ?? null,
                $onboardStudent->batch_id ?? null,
                $onboardStudent->batchName ?? null
            );

            Log::info('🎯 Roll Number for transfer:', [
                'generated_roll_no' => $rollNumber,
                'student_name' => $onboardStudent->name ?? 'N/A'
            ]);

            // ✅ ADDED: Handle Shift dynamically
            $shiftId = null;
            $shiftName = $onboardStudent->shift ?? null;

            if ($shiftName) {
                try {
                    // Find or create shift dynamically
                    $shift = Shift::firstOrCreate(
                        ['name' => $shiftName],
                        [
                            'is_active' => true,
                            'start_time' => null, // Will be set manually later in Masters
                            'end_time' => null
                        ]
                    );
                    $shiftId = $shift->_id;
                    
                    Log::info('✅ Shift processed:', [
                        'shift_name' => $shiftName,
                        'shift_id' => (string)$shiftId,
                        'action' => 'found_or_created'
                    ]);
                } catch (\Exception $shiftError) {
                    Log::warning('⚠️ Shift creation failed, continuing without shift_id:', [
                        'shift_name' => $shiftName,
                        'error' => $shiftError->getMessage()
                    ]);
                    // Continue transfer even if shift fails
                }
            }

            // Prepare data for SMstudents
            $studentData = [
                // Roll Number - DYNAMICALLY GENERATED
                'roll_no' => $rollNumber,
                
                // Basic Info
                'student_name' => $onboardStudent->name ?? $onboardStudent->student_name ?? null,
                'email' => $onboardStudent->email ?? null,
                'phone' => $onboardStudent->mobileNumber ?? $onboardStudent->phone ?? null,
                
                // Family Details
                'father_name' => $onboardStudent->father ?? $onboardStudent->father_name ?? null,
                'mother_name' => $onboardStudent->mother ?? $onboardStudent->mother_name ?? null,
                'dob' => $onboardStudent->dob ?? null,
                'father_contact' => $onboardStudent->mobileNumber ?? $onboardStudent->father_contact ?? null,
                'father_whatsapp' => $onboardStudent->fatherWhatsapp ?? $onboardStudent->father_whatsapp ?? null,
                'mother_contact' => $onboardStudent->motherContact ?? $onboardStudent->mother_contact ?? null,
                'gender' => $onboardStudent->gender ?? null,
                'father_occupation' => $onboardStudent->fatherOccupation ?? $onboardStudent->father_occupation ?? null,
                'father_caste' => $onboardStudent->category ?? $onboardStudent->father_caste ?? null,
                'mother_occupation' => $onboardStudent->motherOccupation ?? $onboardStudent->mother_occupation ?? null,
                
                // Address
                'state' => $onboardStudent->state ?? null,
                'city' => $onboardStudent->city ?? null,
                'pincode' => $onboardStudent->pinCode ?? $onboardStudent->pincode ?? null,
                'address' => $onboardStudent->address ?? null,
                'belongs_other_city' => $onboardStudent->belongToOtherCity ?? $onboardStudent->belongs_other_city ?? 'No',
                
                // Academic Details
                'previous_class' => $onboardStudent->previousClass ?? $onboardStudent->previous_class ?? null,
                'academic_medium' => $onboardStudent->previousMedium ?? $onboardStudent->medium ?? $onboardStudent->academic_medium ?? null,
                'school_name' => $onboardStudent->schoolName ?? $onboardStudent->school_name ?? null,
                'academic_board' => $onboardStudent->previousBoard ?? $onboardStudent->board ?? $onboardStudent->academic_board ?? null,
                'passing_year' => $onboardStudent->passingYear ?? $onboardStudent->passing_year ?? null,
                'percentage' => $onboardStudent->percentage ?? null,
                
                // Course & Batch
                'batch_id' => $onboardStudent->batch_id ?? null,
                'batch_name' => $onboardStudent->batchName ?? $onboardStudent->batch_name ?? null,
                'course_id' => $onboardStudent->course_id ?? null,
                'course_name' => $onboardStudent->courseName ?? $onboardStudent->course_name ?? null,
                'delivery' => $onboardStudent->deliveryMode ?? $onboardStudent->delivery ?? null,
                'delivery_mode' => $onboardStudent->deliveryMode ?? $onboardStudent->delivery_mode ?? null,
                'course_content' => $onboardStudent->courseContent ?? $onboardStudent->course_content ?? null,
                
                // ✅ ADDED: Shift with both ID and name for backward compatibility
                'shift_id' => $shiftId, // MongoDB ObjectId reference
                'shift' => $shiftName, // String name for reference
                
                // Scholarship & Fees
                'eligible_for_scholarship' => $onboardStudent->eligible_for_scholarship ?? 'No',
                'scholarship_name' => $onboardStudent->scholarship_name ?? null,
                'total_fee_before_discount' => $onboardStudent->total_fee_before_discount ?? 0,
                'discretionary_discount' => $onboardStudent->discretionary_discount ?? 'No',
                'discount_percentage' => $onboardStudent->discount_percentage ?? 0,
                'discounted_fee' => $onboardStudent->discounted_fee ?? 0,
                'total_fees' => $onboardStudent->total_fees ?? 0,
                'gst_amount' => $onboardStudent->gst_amount ?? 0,
                'total_fees_inclusive_tax' => $onboardStudent->total_fees_inclusive_tax ?? 0,
                'paid_fees' => $onboardStudent->paid_fees ?? 0,
                'remaining_fees' => $onboardStudent->total_fees ?? 0,
                
                // Status & Metadata
                'status' => 'active',
                'transferred_from' => 'onboard',
                'transferred_at' => now(),
            ];

            Log::info('📦 Creating student in SMstudents...', [
                'name' => $studentData['student_name'],
                'roll_no' => $studentData['roll_no'],
                'shift_id' => $shiftId ? (string)$shiftId : 'null',
                'shift_name' => $shiftName ?? 'null'
            ]);

            // Create in SMstudents
            $activeStudent = SMstudents::create($studentData);

            if (!$activeStudent) {
                throw new \Exception('Failed to create student in SMstudents');
            }

            Log::info('  Created in SMstudents:', [
                'new_id' => (string)$activeStudent->_id,
                'name' => $activeStudent->student_name,
                'roll_no' => $activeStudent->roll_no,
                'shift_id' => $activeStudent->shift_id ? (string)$activeStudent->shift_id : 'null',
                'shift' => $activeStudent->shift ?? 'null'
            ]);

            // Update status in students collection instead of deleting
            DB::connection('mongodb')
                ->table('students')
                ->where('_id', $id)
                ->update(['status' => 'transferred', 'transferred_at' => now()]);

            Log::info('🗑️ Updated status to transferred');

            return redirect()->route('smstudents.index')
                ->with('success', "Student '{$activeStudent->student_name}' successfully transferred with Roll No: {$activeStudent->roll_no}");

        } catch (\Exception $e) {
            Log::error(' TRANSFER FAILED', [
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('student.onboard.onboard')
                ->with('error', 'Transfer failed: ' . $e->getMessage());
        }
    }
}