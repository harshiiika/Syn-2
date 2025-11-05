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
            ->table('students')  // Use ->table() NOT ->collection()
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
            $student = Onboard::findOrFail($id);
            
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
        }
    }

    /**
     * Edit onboarded student
     */
    public function edit($id)
    {
        try {
            $student = Onboard::findOrFail($id);
            
            Log::info('Editing onboarded student:', [
                'student_id' => $id,
                'student_name' => $student->name
            ]);
            
            return view('student.onboard.edit', compact('student'));
            
        } catch (\Exception $e) {
            Log::error("Edit failed for student ID {$id}: " . $e->getMessage());
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

            $student = Onboard::findOrFail($id);
            
            Log::info('Onboarded student found:', [
                'student_id' => $student->_id,
                'student_name' => $student->name
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

            Log::info('Updating onboarded student with data:', [
                'validated_fields' => array_keys($validated)
            ]);

            // Update the onboarded student
            $student->update($validated);
            $student->refresh();
            
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
        }
    }

/**
 * Transfer an onboarded student to Active Students (SMstudents)
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
            Log::error('❌ Onboard student NOT FOUND', ['id' => $id]);
            return redirect()->route('student.onboard.onboard')
                ->with('error', 'Student not found in onboard list');
        }
        
        // Convert array to object - DB::table returns arrays
        $onboardStudent = (object) $onboardStudentArray;
        
        // MongoDB returns 'id' not '_id' when using DB::table
        $studentId = $onboardStudent->id ?? $onboardStudent->_id ?? $id;
        
        Log::info('✅ Onboard student FOUND:', [
            'id' => $studentId,
            'name' => $onboardStudent->name ?? 'N/A',
            'all_keys' => array_keys((array)$onboardStudent)
        ]);

        // Prepare data for SMstudents
           $studentData = [
            'roll_no' => $onboardStudent->roll_no ?? RollNumberService::generateUniqueRollNumber(
                    $onboardStudent->course_id ?? null,
                    $onboardStudent->courseName ?? $onboardStudent->course ?? null,
                    $onboardStudent->batch_id ?? null,
                    $onboardStudent->batchName ?? null
                ),
            'student_name' => $onboardStudent->name ?? $onboardStudent->student_name ?? null,
            'email' => $onboardStudent->email ?? null,
            'phone' => $onboardStudent->mobileNumber ?? $onboardStudent->phone ?? null,
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
            'state' => $onboardStudent->state ?? null,
            'city' => $onboardStudent->city ?? null,
            'pincode' => $onboardStudent->pinCode ?? $onboardStudent->pincode ?? null,
            'address' => $onboardStudent->address ?? null,
            'belongs_other_city' => $onboardStudent->belongToOtherCity ?? $onboardStudent->belongs_other_city ?? 'No',
            'previous_class' => $onboardStudent->previousClass ?? $onboardStudent->previous_class ?? null,
            'academic_medium' => $onboardStudent->previousMedium ?? $onboardStudent->medium ?? $onboardStudent->academic_medium ?? null,
            'school_name' => $onboardStudent->schoolName ?? $onboardStudent->school_name ?? null,
            'academic_board' => $onboardStudent->previousBoard ?? $onboardStudent->board ?? $onboardStudent->academic_board ?? null,
            'passing_year' => $onboardStudent->passingYear ?? $onboardStudent->passing_year ?? null,
            'percentage' => $onboardStudent->percentage ?? null,
            'batch_id' => $onboardStudent->batch_id ?? null,
            'batch_name' => $onboardStudent->batchName ?? $onboardStudent->batch_name ?? null,
            'course_id' => $onboardStudent->course_id ?? null,
            'course_name' => $onboardStudent->courseName ?? $onboardStudent->course_name ?? null,
            'delivery' => $onboardStudent->deliveryMode ?? $onboardStudent->delivery ?? null,
            'delivery_mode' => $onboardStudent->deliveryMode ?? $onboardStudent->delivery_mode ?? null,
            'course_content' => $onboardStudent->courseContent ?? $onboardStudent->course_content ?? null,
            'shift' => $onboardStudent->shift ?? null,
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
            'status' => 'active',
            'transferred_from' => 'onboard',
            'transferred_at' => now(),
        ];

        Log::info('📦 Creating student in SMstudents...', [
            'name' => $studentData['student_name']
        ]);

        // Create in SMstudents
        $activeStudent = SMstudents::create($studentData);

        if (!$activeStudent) {
            throw new \Exception('Failed to create student in SMstudents');
        }

        Log::info('✅ Created in SMstudents:', [
            'new_id' => (string)$activeStudent->_id,
            'name' => $activeStudent->student_name
        ]);

        // Update status in students collection instead of deleting
        DB::connection('mongodb')
            ->table('students')
            ->where('_id', $id)
            ->update(['status' => 'transferred', 'transferred_at' => now()]);

        Log::info('🗑️ Updated status to transferred');

        return redirect()->route('smstudents.index')
            ->with('success', 'Student successfully transferred!');

    } catch (\Exception $e) {
        Log::error('❌ TRANSFER FAILED', [
            'error' => $e->getMessage(),
            'line' => $e->getLine(),
            'file' => $e->getFile()
        ]);
        
        return redirect()->route('student.onboard.onboard')
            ->with('error', 'Transfer failed: ' . $e->getMessage());
    }
}

}