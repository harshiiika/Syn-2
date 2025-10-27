<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
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
    public function index()
    {
        try {
            // Get ALL students with pending_fees status (incomplete forms)
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
            \Log::error('Error loading pending students: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to load students');
        }
    }

    /**
     * Display ONBOARDED students - students with complete forms
     * Route: student.onboard.onboard
     */
    public function onboardedStudents()
    {
        try {
            \Log::info('=== ONBOARDING PAGE LOADED ===');
            
            // Get students with 'onboarded' status (complete forms)
            $students = Student::where('status', 'onboarded')
                ->orderBy('created_at', 'desc')
                ->get();
            
            \Log::info('Fetching onboarded students:', [
                'count' => $students->count(),
                'student_ids' => $students->pluck('_id')->toArray(),
                'student_names' => $students->pluck('name')->toArray()
            ]);
            
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
     * Show fully paid (active) students
     */
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
     * Show single student details (works for both pending and onboarded)
     */
    public function show($id)
    {
        try {
            $student = Student::findOrFail($id);
            
            Log::info('Viewing student details:', [
                'student_id' => $id,
                'student_name' => $student->name,
                'status' => $student->status
            ]);
            
            // Return appropriate view based on status
            if ($student->status === 'onboarded') {
                return view('student.onboard.view', compact('student'));
            }
            
            return view('master.student.show', ['student' => $student]);
            
        } catch (\Exception $e) {
            Log::error("View failed for student ID {$id}: " . $e->getMessage());
            return redirect()->back()->with('error', 'Student not found');
        }
    }

    /**
     * Edit student form (works for both pending and onboarded)
     */
    public function edit($id)
    {
        try {
            $student = Student::findOrFail($id);
            
            Log::info('Editing student:', [
                'student_id' => $id,
                'student_name' => $student->name,
                'status' => $student->status
            ]);
            
            return view('student.student.edit', compact('student'));
            
        } catch (\Exception $e) {
            Log::error("Edit failed for student ID {$id}: " . $e->getMessage());
            return redirect()->back()->with('error', 'Student not found');
        }
    }

    /**
     * Update student information
     * This handles BOTH pending and onboarded students
     */
    public function update(Request $request, $id)
    {
        try {
            \Log::info('=== UPDATE REQUEST START ===', [
                'student_id' => $id,
                'request_data_keys' => array_keys($request->all())
            ]);

            $student = Student::findOrFail($id);
            
            \Log::info('Student found - BEFORE update:', [
                'student_id' => $student->_id,
                'student_name' => $student->name,
                'current_status' => $student->status
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

            // Remove null values to avoid overwriting existing data
            $validated = array_filter($validated, function($value) {
                return $value !== null;
            });

            // Update student
            $student->update($validated);
            $student->refresh();
            
            \Log::info('Student AFTER update:', [
                'student_id' => $student->_id,
                'status' => $student->status
            ]);
            
            // Check if ALL required fields are filled (only for pending_fees students)
            if ($student->status === 'pending_fees') {
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
                
                foreach ($requiredFields as $field) {
                    $value = $student->$field;
                    if ($value === null || $value === '' || (is_string($value) && trim($value) === '')) {
                        $isComplete = false;
                        $missingFields[] = $field;
                    }
                }
                
                \Log::info('=== FORM COMPLETION CHECK ===', [
                    'is_complete' => $isComplete,
                    'total_required' => count($requiredFields),
                    'total_missing' => count($missingFields),
                    'missing_fields' => $missingFields
                ]);

                if ($isComplete) {
                    \Log::info('✓✓✓ FORM IS COMPLETE - MOVING TO ONBOARDED ✓✓✓', [
                        'student_id' => $student->_id,
                        'student_name' => $student->name
                    ]);

                    try {
                        // Get all student data
                        $onboardedData = $student->toArray();
                        
                        // Remove MongoDB _id to create new document
                        unset($onboardedData['_id']);
                        
                        // Add onboarded timestamp
                        $onboardedData['onboardedAt'] = now();
                        $onboardedData['status'] = 'onboarded';
                        
                        \Log::info('Creating onboarded student entry:', [
                            'data_keys' => array_keys($onboardedData),
                            'name' => $onboardedData['name'] ?? 'N/A'
                        ]);
                        
                        // Create entry in onboarded_students collection
                        $onboarded = \App\Models\Student\Onboard::create($onboardedData);
                        
                        \Log::info('✓ Onboarded student created:', [
                            'onboarded_id' => $onboarded->_id,
                            'collection' => 'onboarded_students'
                        ]);
                        
                        // Delete from students (pending) collection
                        $studentId = $student->_id;
                        $student->delete();
                        
                        \Log::info('✓ Deleted from pending students:', [
                            'deleted_id' => $studentId
                        ]);

                        \Log::info('✓✓✓ STUDENT MOVED TO ONBOARDED COLLECTION SUCCESSFULLY ✓✓✓');

                        return redirect()->route('student.student.pending')
                            ->with('success', 'Student form completed! Moved to Onboarding Students section.');
                            
                    } catch (\Exception $e) {
                        \Log::error('❌ ERROR MOVING STUDENT TO ONBOARDED:', [
                            'error' => $e->getMessage(),
                            'trace' => $e->getTraceAsString()
                        ]);
                        
                        return redirect()->route('student.student.pending')
                            ->with('error', 'Failed to move student: ' . $e->getMessage());
                    }
                }
                
                return redirect()->route('student.student.pending')
                    ->with('info', 'Student updated. Missing ' . count($missingFields) . ' field(s): ' . implode(', ', array_slice($missingFields, 0, 5)));
            }
            
            // For already onboarded students, just redirect back to onboarded list
            return redirect()->route('student.onboard.onboard')
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
     * Convert inquiry to student
     */
    public function convertFromInquiry(Request $request, $inquiryId)
    {
        try {
            $inquiry = Inquiry::findOrFail($inquiryId);
            
            $validated = $request->validate([
                'total_fees' => 'required|numeric|min:0',
                'paid_fees' => 'nullable|numeric|min:0',
                'courseName' => 'required|string',
                'deliveryMode' => 'required|string',
                'courseContent' => 'nullable|string',
                'branch' => 'required|string',
            ]);

            $totalFees = $validated['total_fees'];
            $paidFees = $validated['paid_fees'] ?? 0;
            $remainingFees = $totalFees - $paidFees;

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

            $inquiry->update(['status' => 'converted']);

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
     * Store a newly created student
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

            $totalFees = $validated['total_fees'];
            $paidFees = $validated['paid_fees'] ?? 0;
            $remainingFees = $totalFees - $paidFees;

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
     * Update student fees
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

            if ($student->remaining_fees <= 0) {
                $student->status = Student::STATUS_ACTIVE;
                $student->fee_status = 'paid';
                $student->remaining_fees = 0;
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