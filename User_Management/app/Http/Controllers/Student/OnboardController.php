<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student\Onboard;
use App\Models\Student\PendingFee;
use Illuminate\Support\Facades\Log;
use App\Models\Student\SMstudents;
use MongoDB\BSON\ObjectId;

class OnboardController extends Controller
{
    /**
     * Display all onboarded students
     */
public function index()
{
    try {
        Log::info('=== ONBOARDED STUDENTS PAGE LOADED ===');
        
        // Check BOTH possible collections
        $studentsFromOnboard = \DB::connection('mongodb')
            ->collection('onboard')
            ->get();
            
        $studentsFromOnboards = \DB::connection('mongodb')
            ->collection('onboards')
            ->get();
            
        $studentsFromOnboardedStudents = \DB::connection('mongodb')
            ->collection('onboarded_students')
            ->get();
            
        Log::info('🔍 Collection comparison:', [
            'onboard_collection' => [
                'count' => count($studentsFromOnboard),
                'ids' => collect($studentsFromOnboard)->pluck('_id')->map(fn($id) => (string)$id)->toArray()
            ],
            'onboards_collection' => [
                'count' => count($studentsFromOnboards),
                'ids' => collect($studentsFromOnboards)->pluck('_id')->map(fn($id) => (string)$id)->toArray()
            ],
            'onboarded_students_collection' => [
                'count' => count($studentsFromOnboardedStudents),
                'ids' => collect($studentsFromOnboardedStudents)->pluck('_id')->map(fn($id) => (string)$id)->toArray()
            ]
        ]);
        
        // Use the model
        $students = Onboard::orderBy('created_at', 'desc')->get();
        
        Log::info('Eloquent model query result:', [
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

        // Debug: Check database connection
        Log::info('Database connection check:', [
            'default_connection' => config('database.default'),
            'onboard_connection' => (new Onboard())->getConnectionName(),
            'onboard_table' => (new Onboard())->getTable(),
        ]);

        // Check all students with detailed info
        $allStudents = Onboard::all();
        Log::info('All onboard students BEFORE find:', [
            'count' => $allStudents->count(),
            'connection' => $allStudents->first() ? $allStudents->first()->getConnectionName() : 'no students',
            'ids_and_names' => $allStudents->map(function($s) {
                return [
                    '_id' => (string)$s->_id,
                    'name' => $s->name,
                    'id_type' => gettype($s->_id),
                    'id_class' => is_object($s->_id) ? get_class($s->_id) : 'not_object'
                ];
            })->toArray()
        ]);

        // Try multiple find methods
        Log::info('Attempting to find student with ID: ' . $id);
        
        // Method 1: Standard find
        $onboardStudent = Onboard::find($id);
        Log::info('Method 1 - Onboard::find($id):', ['found' => $onboardStudent ? 'YES' : 'NO']);

        // Method 2: Where _id
        if (!$onboardStudent) {
            $onboardStudent = Onboard::where('_id', $id)->first();
            Log::info('Method 2 - where(_id, $id):', ['found' => $onboardStudent ? 'YES' : 'NO']);
        }

        // Method 3: Raw where with string cast
        if (!$onboardStudent) {
            $onboardStudent = Onboard::where('_id', (string)$id)->first();
            Log::info('Method 3 - where(_id, (string)$id):', ['found' => $onboardStudent ? 'YES' : 'NO']);
        }

        // Method 4: Check if ID exists in fetched students
        if (!$onboardStudent) {
            $onboardStudent = $allStudents->first(function($student) use ($id) {
                return (string)$student->_id === $id;
            });
            Log::info('Method 4 - Manual search in collection:', ['found' => $onboardStudent ? 'YES' : 'NO']);
        }
        
        if (!$onboardStudent) {
            Log::error('❌ Student NOT FOUND after all methods', [
                'searched_id' => $id,
                'available_ids' => $allStudents->pluck('_id')->map(fn($id) => (string)$id)->toArray()
            ]);
            
            return redirect()->route('student.onboard.onboard')
                ->with('error', 'Student not found. ID mismatch detected. Check logs.');
        }
        
        Log::info('✅ Onboard student FOUND:', [
            'id' => (string)$onboardStudent->_id,
            'name' => $onboardStudent->name
        ]);

        // Prepare data for SMstudents
        $studentData = [
            'roll_no' => $onboardStudent->roll_no ?? null,
            'student_name' => $onboardStudent->name,
            'email' => $onboardStudent->email ?? null,
            'phone' => $onboardStudent->mobileNumber ?? $onboardStudent->phone ?? null,
            'father_name' => $onboardStudent->father ?? null,
            'mother_name' => $onboardStudent->mother ?? null,
            'dob' => $onboardStudent->dob ?? null,
            'father_contact' => $onboardStudent->mobileNumber ?? null,
            'father_whatsapp' => $onboardStudent->fatherWhatsapp ?? null,
            'mother_contact' => $onboardStudent->motherContact ?? null,
            'gender' => $onboardStudent->gender ?? null,
            'father_occupation' => $onboardStudent->fatherOccupation ?? null,
            'father_caste' => $onboardStudent->category ?? null,
            'mother_occupation' => $onboardStudent->motherOccupation ?? null,
            'state' => $onboardStudent->state ?? null,
            'city' => $onboardStudent->city ?? null,
            'pincode' => $onboardStudent->pinCode ?? null,
            'address' => $onboardStudent->address ?? null,
            'belongs_other_city' => $onboardStudent->belongToOtherCity ?? 'No',
            'previous_class' => $onboardStudent->previousClass ?? null,
            'academic_medium' => $onboardStudent->previousMedium ?? $onboardStudent->medium ?? null,
            'school_name' => $onboardStudent->schoolName ?? null,
            'academic_board' => $onboardStudent->previousBoard ?? $onboardStudent->board ?? null,
            'passing_year' => $onboardStudent->passingYear ?? null,
            'percentage' => $onboardStudent->percentage ?? null,
            'batch_id' => $onboardStudent->batch_id ?? null,
            'batch_name' => $onboardStudent->batchName ?? null,
            'course_id' => $onboardStudent->course_id ?? null,
            'course_name' => $onboardStudent->courseName ?? null,
            'delivery' => $onboardStudent->deliveryMode ?? null,
            'delivery_mode' => $onboardStudent->deliveryMode ?? null,
            'course_content' => $onboardStudent->courseContent ?? null,
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

        // Delete from Onboard
        $deleted = $onboardStudent->delete();

        Log::info('🗑️ Deleted from Onboard:', [
            'deleted' => $deleted ? 'YES' : 'NO'
        ]);

        return redirect()->route('smstudents.index')
            ->with('success', 'Student successfully transferred!');

    } catch (\Exception $e) {
        Log::error('❌ TRANSFER FAILED', [
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