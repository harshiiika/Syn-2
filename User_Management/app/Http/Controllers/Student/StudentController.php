<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Student\Student;
use App\Models\Student\Inquiry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class StudentController extends Controller
{
    public function index()
    {
        try {
            \Log::info('=== ONBOARDING PAGE LOADED ===');
            
            // Get ALL students to debug
            $allStudents = Student::all();
            \Log::info('Total students in database:', [
                'count' => $allStudents->count(),
                'statuses' => $allStudents->pluck('status', '_id')->toArray()
            ]);
            
            // Get students with 'onboarded' status (complete forms)
            $students = Student::where('status', 'onboarded')
                ->orderBy('created_at', 'desc')
                ->get();
            
            \Log::info('Fetching onboarded students:', [
                'count' => $students->count(),
                'student_ids' => $students->pluck('_id')->toArray(),
                'student_names' => $students->pluck('name')->toArray(),
                'student_statuses' => $students->pluck('status', 'name')->toArray()
            ]);
            
            // If no students found, check if any have status issues
            if ($students->count() === 0) {
                $similarStatus = Student::whereIn('status', ['Onboarded', 'ONBOARDED', 'onboard', 'Onboard'])->get();
                \Log::warning('No students with status "onboarded" found. Checking similar statuses:', [
                    'similar_count' => $similarStatus->count(),
                    'similar_statuses' => $similarStatus->pluck('status', 'name')->toArray()
                ]);
            }
            
            return view('student.onboard.onboard', [
                'students' => $students,
                'totalCount' => $students->count()
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error loading onboarded students: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()
                ->with('error', 'Failed to load students: ' . $e->getMessage());
        }
    }


    /**
     * Display ONBOARDING STUDENTS - students with complete forms
     * These are students with status = 'onboarded'
     */
    public function onboardedStudents()
    {
        try {
            // Get students with onboarded status (complete forms)
            $students = Student::where('status', 'onboarded')
                ->orderBy('created_at', 'desc')
                ->get();
            
            \Log::info('Fetching onboarded students:', [
                'count' => $students->count(),
                'students' => $students->pluck('name', '_id')->toArray()
            ]);
            
            return view('student.onboard.onboard', [
                'students' => $students,
                'totalCount' => $students->count(),
            ]);
        } catch (\Exception $e) {
            \Log::error('Error loading onboarded students: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to load students');
        }
    }

    /**
     * Edit onboarded student
     */
    public function edit($id)
    {
        try {
            $student = Student::findOrFail($id);
            
            Log::info('Editing onboarded student:', [
                'student_id' => $id,
                'student_name' => $student->name
            ]);
            
            // Use the same edit view
            return view('student.student.edit', compact('student'));
            
        } catch (\Exception $e) {
            Log::error("Edit failed for student ID {$id}: " . $e->getMessage());
            return redirect()->route('student.onboard.onboard')
                ->with('error', 'Student not found');
        }
    }

   public function update(Request $request, $id)
{
    try {
        \Log::info('=== UPDATE REQUEST START ===', [
            'student_id' => $id,
            'all_input' => $request->all()
        ]);

        $student = Student::findOrFail($id);
        
        \Log::info('Student found - BEFORE update:', [
            'student_id' => $student->_id,
            'student_name' => $student->name,
            'current_status' => $student->status,
            'current_course_type' => $student->course_type ?? 'NOT SET'
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

        \Log::info('Validation passed:', [
            'validated_data' => $validated
        ]);

        // Remove null values to avoid overwriting existing data
        $validated = array_filter($validated, function($value) {
            return $value !== null;
        });

        \Log::info('After filtering nulls:', [
            'validated_data' => $validated
        ]);

        // Update student
        $student->update($validated);
        
        // Refresh student to get updated values
        $student->refresh();
        
        \Log::info('Student AFTER update:', [
            'student_id' => $student->_id,
            'status' => $student->status,
            'course_type' => $student->course_type ?? 'NOT SET',
            'courseName' => $student->courseName ?? 'NOT SET'
        ]);
        
        // Check if ALL required fields are filled
        $requiredFields = [
            'name', 'father', 'mother', 'dob', 'mobileNumber', 
            'category', 'gender', 
            'state', 'city', 'pinCode', 'address',
            'belongToOtherCity', 'economicWeakerSection', 
            'armyPoliceBackground', 'speciallyAbled',
            'course_type', 'courseName', 'deliveryMode', 'medium', 
            'board', 'courseContent',
            'previousClass', 'previousMedium', 'schoolName', 
            'previousBoard', 'passingYear', 'percentage',
            'isRepeater', 'scholarshipTest', 'lastBoardPercentage', 
            'competitionExam', 'batchName'
        ];

        $isComplete = true;
        $missingFields = [];
        $fieldStatus = [];
        
        foreach ($requiredFields as $field) {
            $value = $student->$field;
            // Check if field is null, empty string, or just whitespace
            if ($value === null || $value === '' || (is_string($value) && trim($value) === '')) {
                $isComplete = false;
                $missingFields[] = $field;
                $fieldStatus[$field] = '❌ MISSING';
            } else {
                $fieldStatus[$field] = '✓ ' . substr($value, 0, 20);
            }
        }
        
        \Log::info('=== FORM COMPLETION CHECK ===', [
            'is_complete' => $isComplete,
            'total_required' => count($requiredFields),
            'total_missing' => count($missingFields),
            'missing_fields' => $missingFields,
            'field_status' => $fieldStatus
        ]);

        if ($isComplete) {
            \Log::info('✓✓✓ FORM IS COMPLETE - CHANGING STATUS ✓✓✓', [
                'student_id' => $student->_id,
                'old_status' => $student->status
            ]);

            // Update status to 'onboarded' to move to Onboarding Students section
            $student->status = 'onboarded';
            $student->save();
            
            // Verify the save
            $student->refresh();

            \Log::info('✓✓✓ STATUS CHANGED SUCCESSFULLY ✓✓✓', [
                'new_status' => $student->status,
                'verified_in_db' => Student::find($id)->status
            ]);

            return redirect()->route('student.student.pending')
                ->with('success', 'Student form completed! Moved to Onboarding Students section.');
        }
        
        \Log::info('Form not complete yet', [
            'student_id' => $student->_id,
            'missing_count' => count($missingFields),
            'missing_fields' => $missingFields
        ]);

        return redirect()->route('student.student.pending')
            ->with('info', 'Student updated successfully. Missing ' . count($missingFields) . ' required field(s): ' . implode(', ', array_slice($missingFields, 0, 5)) . (count($missingFields) > 5 ? '...' : ''));
            
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        \Log::error('Student not found: ', ['id' => $id]);
        return redirect()->back()
            ->with('error', 'Student not found')
            ->withInput();
            
    } catch (\Illuminate\Validation\ValidationException $e) {
        \Log::error('Validation failed', ['errors' => $e->errors()]);
        return redirect()->back()
            ->withErrors($e->errors())
            ->withInput();
        
    } catch (\Exception $e) {
        \Log::error('Error updating student: ' . $e->getMessage(), [
            'trace' => $e->getTraceAsString()
        ]);

        return redirect()->back()
            ->with('error', 'Failed to update student: ' . $e->getMessage())
            ->withInput();
    }
}


public function activeStudents()
{
    $students = Student::getActiveStudents();
    return view('master.student.onboard', compact('students'));
}

    /**
     * Display pending fees students
     */
    public function pendingFees()
    {
        try {
            $students = Student::getPendingFeesStudents();
            
            return view('student.html_fees', [
                'students' => $students,
                'totalCount' => $students->count(),
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to load pending fees students');
        }
    }

      /**
     * View onboarded student details
     */
    public function show($id)
    {
        try {
            $student = Student::findOrFail($id);
            
            Log::info('Viewing onboarded student details:', [
                'student_id' => $id,
                'student_name' => $student->name
            ]);
            
            return view('student.onboard.view', compact('student'));
            
        } catch (\Exception $e) {
            Log::error("View failed for student ID {$id}: " . $e->getMessage());
            return redirect()->route('student.onboard.onboard')
                ->with('error', 'Student not found');
        }
    }

    /**
     * Convert inquiry to student (pending process)
     */
    public function convertFromInquiry(Request $request, $inquiryId)
    {
        try {
            // Get inquiry data
            $inquiry = Inquiry::findOrFail($inquiryId);
            
            // Validate additional pending data
            $validated = $request->validate([
                'total_fees' => 'required|numeric|min:0',
                'paid_fees' => 'nullable|numeric|min:0',
                'courseName' => 'required|string',
                'deliveryMode' => 'required|string',
                'courseContent' => 'nullable|string',
                'branch' => 'required|string',
            ]);

            // Calculate remaining fees
            $totalFees = $validated['total_fees'];
            $paidFees = $validated['paid_fees'] ?? 0;
            $remainingFees = $totalFees - $paidFees;

            // Determine status based on fees
            if ($remainingFees <= 0) {
                $status = Student::STATUS_ACTIVE;
                $feeStatus = 'paid';
            } elseif ($paidFees > 0) {
                $status = Student::STATUS_PENDING_FEES;
                $feeStatus = 'partial';
            } else {
                $status = Student::STATUS_PENDING_FEES;
                $feeStatus = 'pending';
            }

            // Create student from inquiry
            $student = Student::create([
                'name' => $inquiry->name,
                'father' => $inquiry->father,
                'mobileNumber' => $inquiry->mobileNumber,
                'alternateNumber' => $inquiry->alternateNumber ?? null,
                'email' => $inquiry->email,
                'courseName' => $validated['courseName'],
                'deliveryMode' => $validated['deliveryMode'],
                'courseContent' => $validated['courseContent'] ?? null,
                'branch' => $validated['branch'],
                'total_fees' => $totalFees,
                'paid_fees' => $paidFees,
                'remaining_fees' => $remainingFees,
                'status' => $status,
                'fee_status' => $feeStatus,
            ]);

            // Update inquiry status
            $inquiry->update(['status' => 'converted']);

            // Redirect based on fees status
            if ($remainingFees > 0) {
                return redirect()->route('students.pending_fees')
                    ->with('success', 'Student onboarded successfully! Pending fees: ₹' . $remainingFees);
            } else {
                return redirect()->route('students.active')
                    ->with('success', 'Student onboarded successfully with full payment!');
            }

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to onboard student: ' . $e->getMessage());
        }
    }

    /**
     * Store a newly created student (direct entry)
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'father' => 'required|string|max:255',
                'mobileNumber' => 'required|string|regex:/^[0-9]{10}$/',
                'courseName' => 'required|string|max:255',
                'deliveryMode' => 'required|string',
                'courseContent' => 'nullable|string',
                'email' => 'required|email|unique:mongodb.students',
                'alternateNumber' => 'nullable|string|regex:/^[0-9]{10}$/',
                'branch' => 'required|string',
                'total_fees' => 'required|numeric|min:0',
                'paid_fees' => 'nullable|numeric|min:0',
            ]);

            // Calculate fees
            $totalFees = $validated['total_fees'];
            $paidFees = $validated['paid_fees'] ?? 0;
            $remainingFees = $totalFees - $paidFees;

            // Determine status
            if ($remainingFees <= 0) {
                $status = Student::STATUS_ACTIVE;
                $feeStatus = 'paid';
            } elseif ($paidFees > 0) {
                $status = Student::STATUS_PENDING_FEES;
                $feeStatus = 'partial';
            } else {
                $status = Student::STATUS_PENDING_FEES;
                $feeStatus = 'pending';
            }

            $student = Student::create([
                'name' => $validated['name'],
                'father' => $validated['father'],
                'mobileNumber' => $validated['mobileNumber'],
                'alternateNumber' => $validated['alternateNumber'] ?? null,
                'email' => $validated['email'],
                'courseName' => $validated['courseName'],
                'deliveryMode' => $validated['deliveryMode'],
                'courseContent' => $validated['courseContent'] ?? null,
                'branch' => $validated['branch'],
                'total_fees' => $totalFees,
                'paid_fees' => $paidFees,
                'remaining_fees' => $remainingFees,
                'status' => $status,
                'fee_status' => $feeStatus,
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Student added successfully',
                    'student' => $student
                ], 201);
            }

            return redirect()->route('student.html')->with('success', 'Student added successfully');
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->ajax()) {
                return response()->json([
                    'status' => 'error',
                    'errors' => $e->errors()
                ], 422);
            }

            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed to add student'
                ], 500);
            }

            return redirect()->back()->with('error', 'Failed to add student');
        }
    }

    /**
     * Update student fees (collect payment)
     */
    public function updateFees(Request $request, $id)
    {
        try {
            $student = Student::findOrFail($id);
            
            $validated = $request->validate([
                'payment_amount' => 'required|numeric|min:0',
            ]);

            $paymentAmount = $validated['payment_amount'];
            $student->paid_fees += $paymentAmount;
            $student->remaining_fees -= $paymentAmount;

            // Update status based on remaining fees
            if ($student->remaining_fees <= 0) {
                $student->status = Student::STATUS_ACTIVE;
                $student->fee_status = 'paid';
                $student->remaining_fees = 0; // Ensure it doesn't go negative
            } elseif ($student->paid_fees > 0) {
                $student->fee_status = 'partial';
            }

            $student->save();

            if ($request->ajax()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Payment recorded successfully',
                    'remaining_fees' => $student->remaining_fees,
                    'redirect' => $student->remaining_fees <= 0 ? route('students.active') : null
                ]);
            }

            // Redirect based on remaining fees
            if ($student->remaining_fees <= 0) {
                return redirect()->route('students.active')
                    ->with('success', 'Payment completed! Student is now active.');
            }

            return redirect()->route('students.pending_fees')
                ->with('success', 'Payment recorded. Remaining: ₹' . $student->remaining_fees);

        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed to update fees'
                ], 500);
            }

            return redirect()->back()->with('error', 'Failed to update fees');
        }
    }

}