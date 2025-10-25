<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Student\Student;
use App\Models\Student\Inquiry;
use Illuminate\Http\Request;

class StudentController extends Controller
{
 public function index()
{
    try {
        // Get ALL students with pending status
        $students = Student::where('status', 'pending_fees')
            ->orderBy('created_at', 'desc')
            ->get();
        
        \Log::info('Fetching pending inquiry students:', [
            'count' => $students->count(),
            'students' => $students->pluck('name', '_id')->toArray()
        ]);
        
        return view('student.student.pending', [
            'students' => $students,
            'totalCount' => $students->count(),
        ]);
    } catch (\Exception $e) {
        \Log::error('Error loading students: ' . $e->getMessage());
        return redirect()->back()->with('error', 'Failed to load students');
    }
}


// Show fully paid (onboarded) students
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
     * Show single student details
     */
    public function show($id)
    {
        try {
            $student = Student::getStudentById($id);
            
            if (!$student) {
                return redirect()->back()->with('error', 'Student not found');
            }
            
            return view('master.student.show', ['student' => $student]);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error retrieving student');
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
     * Update the specified student
     */
public function update(Request $request, $id)
{
    try {
        \Log::info('Update request received', [
            'id' => $id,
            'data' => $request->all()
        ]);

        $student = Student::findOrFail($id);
        
        \Log::info('Student found', [
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
            'course' => 'nullable|string',
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

        // Remove null values to avoid overwriting existing data with nulls
        $validated = array_filter($validated, function($value) {
            return $value !== null;
        });

        $student->update($validated);
        
        \Log::info('Student updated successfully', [
            'student_id' => $student->_id
        ]);

        return redirect()->route('student.student.pending')
            ->with('success', 'Student updated successfully');
            
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


    public function edit($id)
{
    // Fetch the student by ID
    $student = Student::findOrFail($id);

    // Return the edit view (make sure it exists)
    return view('student.student.edit', compact('student'));
}
}