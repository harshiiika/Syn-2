<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Student\Student;
use App\Models\Student\Onboard;
use App\Models\Student\SMstudents;
use App\Models\Student\Pending;
use App\Models\Master\Batch;
use App\Models\Master\Courses;

class PendingFeesController extends Controller
{
    /**
     * Display all students with pending fees
     */
    public function index()
    {
        try {
            // Get ALL students with pending fees (remaining_fees > 0 OR status = pending_fees)
            $pendingFees = Student::where(function ($query) {
                    $query->where('remaining_fees', '>', 0)
                        ->orWhere('status', 'pending_fees');
                })
                ->orderBy('created_at', 'desc')
                ->get();

            Log::info('Fetching pending fees students:', [
                'count' => $pendingFees->count(),
                'students' => $pendingFees->pluck('name', '_id')->toArray(),
            ]);

            return view('student.pendingfees.pending', [
                'pendingFees' => $pendingFees,
                'totalCount' => $pendingFees->count(),
            ]);
        } catch (\Exception $e) {
            Log::error('Error loading pending fees students: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()->with('error', 'Failed to load students');
        }
    }

    /**
     * Display the student details (read-only view) with scholarship/fees data
     */
    public function view(string $id)
    {
        try {
            // Try to fetch from Student model first, then Pending model, then Onboard
            $student = Student::find($id) ?? (class_exists(Pending::class) ? Pending::find($id) : null) ?? Onboard::find($id);

            if (!$student) {
                throw new \Exception("Student not found");
            }

            Log::info('=== VIEWING PENDING FEES STUDENT DETAILS ===', [
                'student_id' => $id,
                'student_name' => $student->name ?? $student->student_name ?? 'N/A',
                'has_scholarship_data' => !empty($student->eligible_for_scholarship),
            ]);

            // Prepare fees data
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

            Log::info('  Fees data prepared for pending fees view', $feesData);

            return view('student.pendingfees.view', compact('student', 'feesData'));
        } catch (\Exception $e) {
            Log::error("View failed for pending fees student ID {$id}: " . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->route('student.pendingfees.pending')->with('error', 'Student not found');
        }
    }

    /**
     * Show the edit form for a specific student
     */
    public function edit($id)
    {
        try {
            // Try to fetch from Student model first, then Pending model, then Onboard
            $student = Student::find($id) ?? (class_exists(Pending::class) ? Pending::find($id) : null) ?? Onboard::find($id);

            if (!$student) {
                throw new \Exception("Student not found");
            }

            Log::info('Editing pending student:', [
                'student_id' => $id,
                'student_name' => $student->name ?? $student->student_name ?? 'N/A',
            ]);

            // load batches & courses for the edit form if needed
            $batches = Batch::where('status', 'active')->get();
            $courses = Courses::where('status', 'active')->get();

            return view('student.pendingfees.edit', compact('student', 'batches', 'courses'));
        } catch (\Exception $e) {
            Log::error("Edit failed for student ID {$id}: " . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->route('student.pendingfees.pending')->with('error', 'Student not found');
        }
    }

    /**
     * Check if all required fields are filled
     */
    private function isProfileComplete(array $data): bool
    {
        // Define required fields used previously
        $requiredFields = [
            'name', 'father', 'mother', 'dob', 'mobileNumber',
            'fatherWhatsapp', 'motherContact', 'studentContact',
            'category', 'gender', 'fatherOccupation', 'fatherGrade',
            'motherOccupation', 'state', 'city', 'pinCode', 'address',
            'belongToOtherCity', 'economicWeakerSection',
            'armyPoliceBackground', 'speciallyAbled',
            'courseType', 'courseName', 'deliveryMode',
            'medium', 'board', 'courseContent',
            'previousClass', 'previousMedium', 'schoolName',
            'previousBoard', 'passingYear', 'percentage',
            'isRepeater', 'scholarshipTest',
            'lastBoardPercentage', 'competitionExam',
            'batchName',
        ];

        foreach ($requiredFields as $field) {
            if (!isset($data[$field]) || $data[$field] === null || $data[$field] === '') {
                Log::info("Field missing or empty: {$field}");
                return false;
            }
        }

        return true;
    }

    /**
     * Update the student information and move to onboard if complete
     */
    public function update(Request $request, string $id)
    {
        try {
            Log::info('Update request received', [
                'id' => $id,
                'data_keys' => array_keys($request->except(['_token', '_method'])),
            ]);

            // Try Student -> Pending -> Onboard
            $student = Student::find($id) ?? (class_exists(Pending::class) ? Pending::find($id) : null) ?? Onboard::find($id);
            $modelUsed = $student instanceof Student ? 'Student' : ($student instanceof Onboard ? 'Onboard' : 'Pending');

            if (!$student) {
                throw new \Illuminate\Database\Eloquent\ModelNotFoundException("Student not found in any collection");
            }

            Log::info('Student found', [
                'student_id' => $id,
                'name' => $student->name ?? $student->student_name ?? null,
                'model' => $modelUsed,
            ]);

            // Validation rules (kept conservative and compatible with current forms)
            $validated = $request->validate([
                'name' => 'required|string|max:255',
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
                'state' => 'nullable|string',
                'city' => 'nullable|string',
                'pinCode' => 'nullable|string|max:10',
                'address' => 'nullable|string',
                'belongToOtherCity' => 'nullable|in:Yes,No',
                'economicWeakerSection' => 'nullable|in:Yes,No',
                'armyPoliceBackground' => 'nullable|in:Yes,No',
                'speciallyAbled' => 'nullable|in:Yes,No',
                'courseType' => 'nullable|string',
                'courseName' => 'nullable|string',
                'deliveryMode' => 'nullable|string',
                'medium' => 'nullable|string',
                'board' => 'nullable|string',
                'courseContent' => 'nullable|string',
                'previousClass' => 'nullable|string',
                'previousMedium' => 'nullable|string',
                'schoolName' => 'nullable|string',
                'previousBoard' => 'nullable|string',
                'passingYear' => 'nullable|string',
                'percentage' => 'nullable|numeric|min:0|max:100',
                'isRepeater' => 'nullable|in:Yes,No',
                'scholarshipTest' => 'nullable|in:Yes,No',
                'lastBoardPercentage' => 'nullable|numeric|min:0|max:100',
                'competitionExam' => 'nullable|in:Yes,No',
                'batchName' => 'nullable|string',
            ]);

            Log::info('Validation passed');

            // Update student
            $student->update($validated);

            Log::info('Student update result', [
                'id' => $id,
                'name' => $student->name ?? $student->student_name ?? null,
                'model' => $modelUsed,
            ]);

            // Check completeness using the validated input (prevents depending on DB shape)
            $isComplete = $this->isProfileComplete($validated);

            Log::info('Profile completion check', [
                'id' => $id,
                'is_complete' => $isComplete,
            ]);

            if ($isComplete) {
                Log::info('Profile is complete, moving to onboard collection', [
                    'id' => $id,
                    'name' => $student->name ?? $student->student_name ?? null,
                ]);

                // Prepare onboard data
                $onboardData = $student->toArray();
                unset($onboardData['_id']);
                $onboardData = array_merge($onboardData, $validated);
                $onboardData['status'] = 'onboarded';
                $onboardData['onboardedAt'] = now();

                $onboardedStudent = Onboard::create($onboardData);

                Log::info('  Onboarded student created from pending fees', [
                    'onboarded_id' => $onboardedStudent->_id,
                    'name' => $onboardedStudent->name ?? $onboardedStudent->student_name ?? null,
                ]);

                // Remove original
                $student->delete();

                Log::info('Student removed from original collection after onboarding', [
                    'id' => $id,
                    'from' => $modelUsed,
                ]);

                return redirect()->route('student.onboard.onboard')
                    ->with('success', 'Student profile completed and moved to onboard successfully!');
            }

            return redirect()->route('student.pendingfees.pending')
                ->with('info', 'Student details updated successfully! Please complete all fields to move to onboard.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error:', ['errors' => $e->errors()]);
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('Student not found:', ['id' => $id]);
            return redirect()->route('student.pendingfees.pending')->with('error', 'Student not found');
        } catch (\Exception $e) {
            Log::error('Update failed:', [
                'id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()->with('error', 'Failed to update student: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Show payment form
     */
    public function pay($id)
    {
        try {
            // Find student in Student -> Pending -> Onboard
            $student = Student::find($id) ?? (class_exists(Pending::class) ? Pending::find($id) : null) ?? Onboard::find($id);

            if (!$student) {
                throw new \Exception('Student not found');
            }

            // Totals
            $totalFees = floatval($student->total_fees ?? $student->total_fee_before_discount ?? 0);
            $gstAmount = floatval($student->gst_amount ?? 0);
            if ($gstAmount == 0 && $totalFees > 0) {
                $gstAmount = $totalFees * 0.18;
            }

            // Fallback (legacy)
            if ($totalFees == 0) {
                $totalFees = 100000;
                $gstAmount = 18000;
            }

            $totalFeesWithGST = $totalFees + $gstAmount;

            // Calculate paid amount
            $totalPaid = 0;
            if (isset($student->paymentHistory) && is_array($student->paymentHistory)) {
                foreach ($student->paymentHistory as $payment) {
                    $totalPaid += floatval($payment['amount'] ?? 0);
                }
            } else {
                $totalPaid = floatval($student->paid_fees ?? $student->paidAmount ?? 0);
            }

            $remainingBalance = max(0, $totalFeesWithGST - $totalPaid);
            $firstInstallment = $totalFeesWithGST * 0.40;

            Log::info('Payment form opened', [
                'student_id' => $id,
                'total_fees' => $totalFees,
                'total_paid' => $totalPaid,
                'remaining' => $remainingBalance,
            ]);

            return view('student.pendingfees.pay', compact(
                'student',
                'totalFees',
                'gstAmount',
                'totalFeesWithGST',
                'totalPaid',
                'remainingBalance',
                'firstInstallment'
            ));
        } catch (\Exception $e) {
            Log::error('Payment form error:', ['id' => $id, 'error' => $e->getMessage()]);
            return redirect()->route('student.pendingfees.pending')->with('error', 'Unable to load payment form');
        }
    }

    /**
     * Process payment and transfer to SM Students if fully paid
     */
    public function processPayment(Request $request, $id)
    {
        try {
            Log::info('=== PAYMENT PROCESSING STARTED ===', ['id' => $id]);

            // Validate payment data
            $validated = $request->validate([
                'payment_date' => 'required|date',
                'payment_type' => 'required|in:cash,online,cheque,card',
                'payment_amount' => 'required|numeric|min:1',
                'transaction_id' => 'nullable|string',
                'remarks' => 'nullable|string',
                'do_you_want_to_pay_fees' => 'required|in:single_payment,in_installment',
                'other_charges' => 'nullable|numeric|min:0',
            ]);

            // Find student in Student -> Pending -> Onboard
            $student = Student::find($id) ?? (class_exists(Pending::class) ? Pending::find($id) : null) ?? Onboard::find($id);
            if (!$student) {
                throw new \Illuminate\Database\Eloquent\ModelNotFoundException("Student not found");
            }

            // Determine original collection string for logging/audit
            $originalCollection = Student::find($id) ? 'students' : ((class_exists(Pending::class) && Pending::find($id)) ? 'pending' : 'onboard');

            Log::info('Student found in collection', ['collection' => $originalCollection]);

            // Payment bookkeeping
            $paymentAmount = floatval($validated['payment_amount']);
            $otherCharges = floatval($validated['other_charges'] ?? 0);

            $paymentRecord = [
                'date' => $validated['payment_date'],
                'amount' => $paymentAmount,
                'method' => $validated['payment_type'],
                'transaction_id' => $validated['transaction_id'] ?? null,
                'remarks' => $validated['remarks'] ?? null,
                'type' => $validated['do_you_want_to_pay_fees'],
                'other_charges' => $otherCharges,
                'recorded_at' => now()->toDateTimeString(),
                'recorded_by' => auth()->user()->name ?? 'Admin',
            ];

            // Update paymentHistory array
            $paymentHistory = $student->paymentHistory ?? [];
            if (!is_array($paymentHistory)) {
                $paymentHistory = [];
            }
            $paymentHistory[] = $paymentRecord;

            // Totals calculations
            $totalFees = floatval($student->total_fees ?? $student->total_fee_before_discount ?? 0);
            $gstAmount = floatval($student->gst_amount ?? 0);
            if ($gstAmount == 0 && $totalFees > 0) {
                $gstAmount = $totalFees * 0.18;
            }
            $totalFeesWithGST = $totalFees + $gstAmount;

            // New paid amount (sum of paymentHistory amounts)
            $newPaidAmount = 0;
            foreach ($paymentHistory as $p) {
                $newPaidAmount += floatval($p['amount'] ?? 0);
            }
            $newRemainingBalance = max(0, $totalFeesWithGST - $newPaidAmount);

            Log::info('Payment calculation', [
                'total_fees' => $totalFees,
                'gst' => $gstAmount,
                'total_with_gst' => $totalFeesWithGST,
                'new_paid' => $newPaidAmount,
                'remaining' => $newRemainingBalance,
            ]);

            // Fully paid -> transfer to SMstudents
            if ($newRemainingBalance <= 0) {
                Log::info('  FEES FULLY PAID - Transferring to SMstudents', ['student_id' => $id]);

                // Build SMstudents payload
                $smStudentData = [
                    'roll_no' => $student->roll_no ?? null,
                    'student_name' => $student->name ?? $student->student_name,
                    'email' => $student->email ?? $student->studentContact ?? null,
                    'phone' => $student->mobileNumber ?? $student->phone ?? $student->studentContact ?? null,
                    'father_name' => $student->father ?? null,
                    'mother_name' => $student->mother ?? null,
                    'dob' => $student->dob ?? null,
                    'father_contact' => $student->fatherWhatsapp ?? null,
                    'father_whatsapp' => $student->fatherWhatsapp ?? null,
                    'mother_contact' => $student->motherContact ?? null,
                    'gender' => $student->gender ?? null,
                    'father_occupation' => $student->fatherOccupation ?? null,
                    'father_caste' => $student->category ?? null,
                    'mother_occupation' => $student->motherOccupation ?? null,
                    'state' => $student->state ?? null,
                    'city' => $student->city ?? null,
                    'pincode' => $student->pinCode ?? null,
                    'address' => $student->address ?? null,
                    'belongs_other_city' => $student->belongToOtherCity ?? 'No',
                    'previous_class' => $student->previousClass ?? null,
                    'academic_medium' => $student->previousMedium ?? $student->medium ?? null,
                    'school_name' => $student->schoolName ?? null,
                    'academic_board' => $student->previousBoard ?? $student->board ?? null,
                    'passing_year' => $student->passingYear ?? null,
                    'percentage' => $student->percentage ?? null,
                    'batch_id' => $student->batch_id ?? null,
                    'batch_name' => $student->batchName ?? null,
                    'course_id' => $student->course_id ?? null,
                    'course_name' => $student->courseName ?? null,
                    'course_content' => $student->courseContent ?? $student->fees_breakup ?? null,
                    'delivery' => $student->deliveryMode ?? $student->delivery ?? 'Offline',
                    'delivery_mode' => $student->deliveryMode ?? $student->delivery ?? 'Offline',
                    'shift' => $student->shift ?? null,
                    'eligible_for_scholarship' => $student->eligible_for_scholarship ?? 'No',
                    'scholarship_name' => $student->scholarship_name ?? null,
                    'total_fee_before_discount' => $student->total_fee_before_discount ?? $totalFees,
                    'discretionary_discount' => $student->discretionary_discount ?? 'No',
                    'discount_percentage' => $student->discount_percentage ?? 0,
                    'discounted_fee' => $student->discounted_fee ?? $totalFees,
                    'total_fees' => $totalFees,
                    'gst_amount' => $gstAmount,
                    'total_fees_inclusive_tax' => $totalFeesWithGST,
                    'paid_fees' => $newPaidAmount,
                    'paidAmount' => $newPaidAmount,
                    'remaining_fees' => 0,
                    'remainingAmount' => 0,
                    'fee_status' => 'paid',
                    'paymentHistory' => $paymentHistory,
                    'last_payment_date' => $validated['payment_date'],
                    'passport_photo' => $student->passport_photo ?? null,
                    'marksheet' => $student->marksheet ?? null,
                    'caste_certificate' => $student->caste_certificate ?? null,
                    'scholarship_proof' => $student->scholarship_proof ?? null,
                    'secondary_marksheet' => $student->secondary_marksheet ?? null,
                    'senior_secondary_marksheet' => $student->senior_secondary_marksheet ?? null,
                    'status' => 'active',
                    'transferred_from' => $originalCollection,
                    'transferred_at' => now(),
                    'created_at' => $student->created_at ?? now(),
                    'updated_at' => now(),
                ];

                // Ensure roll_no exists - generate if missing
                if (empty($smStudentData['roll_no'])) {
                    $smStudentData['roll_no'] = 'SM' . now()->format('ymd') . rand(100, 999);
                }

                // Insert into s_mstudents
                $smStudent = SMstudents::create($smStudentData);

                Log::info('  Student transferred to SMstudents', [
                    'sm_student_id' => $smStudent->_id ?? null,
                    'name' => $smStudent->student_name ?? null,
                    'roll_no' => $smStudent->roll_no ?? null,
                ]);

                // Delete original student
                $student->delete();

                Log::info('  Student deleted from original collection after transfer', ['from' => $originalCollection]);

                return redirect()->route('smstudents.index')
                    ->with('success', "Payment successful! Student '{$smStudent->student_name}' moved to Active Students with full fees paid.");
            }

            // Partial payment -> update student record
            $feeStatus = $newRemainingBalance <= 0 ? 'paid' : ($newPaidAmount > 0 ? 'partial' : 'pending');

            $student->update([
                'paymentHistory' => $paymentHistory,
                'paid_fees' => $newPaidAmount,
                'paidAmount' => $newPaidAmount,
                'remaining_fees' => $newRemainingBalance,
                'remainingAmount' => $newRemainingBalance,
                'fee_status' => $feeStatus,
                'last_payment_date' => $validated['payment_date'],
                'total_fees' => $totalFees,
                'gst_amount' => $gstAmount,
                'total_fees_inclusive_tax' => $totalFeesWithGST,
                'updated_at' => now(),
            ]);

            Log::info('  Payment recorded (partial)', [
                'student_id' => $id,
                'amount' => $paymentAmount,
                'new_paid' => $newPaidAmount,
                'remaining' => $newRemainingBalance,
            ]);

            $message = "Payment of ₹" . number_format($paymentAmount, 2) . " recorded successfully! Remaining: ₹" . number_format($newRemainingBalance, 2);

            return redirect()->route('student.pendingfees.pending')->with('success', $message);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Payment validation failed', ['errors' => $e->errors()]);
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('Payment failed - student not found', ['id' => $id]);
            return redirect()->route('student.pendingfees.pending')->with('error', 'Student not found');
        } catch (\Exception $e) {
            Log::error('❌ Payment processing failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()->with('error', 'Payment failed: ' . $e->getMessage())->withInput();
        }
    }
}

//old

// namespace App\Http\Controllers\Student;

// use App\Http\Controllers\Controller;
// use Illuminate\Http\Request;
// use App\Models\Student\Student;
// use App\Models\Student\Onboard;
// use Illuminate\Support\Facades\Validator;
// use Illuminate\Support\Facades\Log;
// use App\Models\Student\Pending;

// class PendingFeesController extends Controller
// {
//     /**
//      * Display all students with pending fees
//      */
//     public function index()
//     {
//         try {
//             // Get ALL students with pending fees (any remaining_fees > 0 OR status = pending_fees)
//             $pendingFees = Student::where(function($query) {
//                 $query->where('remaining_fees', '>', 0)
//                       ->orWhere('status', 'pending_fees');
//             })
//             ->orderBy('created_at', 'desc')
//             ->get();

//             \Log::info('Fetching pending fees students:', [
//                 'count' => $pendingFees->count(),
//                 'students' => $pendingFees->pluck('name', '_id')->toArray()
//             ]);

//             return view('student.pendingfees.pending', [
//                 'pendingFees' => $pendingFees,
//                 'totalCount' => $pendingFees->count(),
//             ]);
//         } catch (\Exception $e) {
//             \Log::error('Error loading pending fees students: ' . $e->getMessage());
//             return redirect()->back()
//                 ->with('error', 'Failed to load students');
//         }
//     }

//     /**
//      * Display the student details (read-only view) with scholarship/fees data
//      */
//     public function view(string $id)
//     {
//         try {
//             // Try to fetch from Student model first, then Pending model
//             $student = null;
            
//             try {
//                 $student = Student::findOrFail($id);
//             } catch (\Exception $e) {
//                 // If not found in Student, try Pending model
//                 $student = Pending::findOrFail($id);
//             }
            
//             Log::info('=== VIEWING PENDING FEES STUDENT DETAILS ===', [
//                 'student_id' => $id,
//                 'student_name' => $student->name ?? 'N/A',
//                 'has_scholarship_data' => !empty($student->eligible_for_scholarship),
//                 'eligible_for_scholarship' => $student->eligible_for_scholarship ?? 'NOT SET',
//                 'scholarship_name' => $student->scholarship_name ?? 'NOT SET',
//                 'total_fee_before_discount' => $student->total_fee_before_discount ?? 'NOT SET',
//                 'total_fees' => $student->total_fees ?? 'NOT SET',
//                 'gst_amount' => $student->gst_amount ?? 'NOT SET',
//             ]);
            
//             //   Prepare fees data array (same as StudentController and OnboardController)
//             $feesData = [
//                 'eligible_for_scholarship' => $student->eligible_for_scholarship ?? 'No',
//                 'scholarship_name' => $student->scholarship_name ?? 'N/A',
//                 'total_fee_before_discount' => $student->total_fee_before_discount ?? 0,
//                 'discretionary_discount' => $student->discretionary_discount ?? 'No',
//                 'discount_percentage' => $student->discount_percentage ?? 0,
//                 'discounted_fee' => $student->discounted_fee ?? 0,
//                 'fees_breakup' => $student->fees_breakup ?? 'Class room course (with test series & study material)',
//                 'total_fees' => $student->total_fees ?? 0,
//                 'gst_amount' => $student->gst_amount ?? 0,
//                 'total_fees_inclusive_tax' => $student->total_fees_inclusive_tax ?? 0,
//                 'single_installment_amount' => $student->single_installment_amount ?? 0,
//                 'installment_1' => $student->installment_1 ?? 0,
//                 'installment_2' => $student->installment_2 ?? 0,
//                 'installment_3' => $student->installment_3 ?? 0,
//             ];
            
//             Log::info('  Fees data prepared for pending fees view:', $feesData);
            
//             // Return the view with student data AND feesData
//             return view('student.pendingfees.view', compact('student', 'feesData'));
            
//         } catch (\Exception $e) {
//             Log::error("❌ View failed for pending fees student ID {$id}: " . $e->getMessage());
//             Log::error('Stack trace: ' . $e->getTraceAsString());
            
//             return redirect()->route('student.pendingfees.pending')
//                 ->with('error', 'Student not found');
//         }
//     }

//     /**
//      * Show the edit form for a specific student
//      */
//     public function edit($id) 
//     {
//         try {
//             // Try to fetch from Student model first, then Pending model
//             $student = null;
            
//             try {
//                 $student = Student::findOrFail($id);
//             } catch (\Exception $e) {
//                 // If not found in Student, try Pending model
//                 $student = Pending::findOrFail($id);
//             }
            
//             Log::info('Editing pending student:', [
//                 'student_id' => $id,
//                 'student_name' => $student->name ?? 'N/A'
//             ]);
            
//             return view('student.pendingfees.edit', compact('student'));
            
//         } catch (\Exception $e) {
//             Log::error("Edit failed for student ID {$id}: " . $e->getMessage());
//             return redirect()->route('student.pendingfees.pending')
//                 ->with('error', 'Student not found');
//         }
//     }

//     /**
//      * Check if all required fields are filled
//      */
//     private function isProfileComplete($data)
//     {
//         // Define all required fields that must be filled
//         $requiredFields = [
//             // Basic Details
//             'name', 'father', 'mother', 'dob', 'mobileNumber', 
//             'fatherWhatsapp', 'motherContact', 'studentContact',
//             'category', 'gender', 'fatherOccupation', 'fatherGrade', 
//             'motherOccupation',
            
//             // Address Details
//             'state', 'city', 'pinCode', 'address', 
//             'belongToOtherCity', 'economicWeakerSection',
//             'armyPoliceBackground', 'speciallyAbled',
            
//             // Course Details
//             'courseType', 'courseName', 'deliveryMode',
//             'medium', 'board', 'courseContent',
            
//             // Academic Details
//             'previousClass', 'previousMedium', 'schoolName',
//             'previousBoard', 'passingYear', 'percentage',
            
//             // Scholarship Eligibility
//             'isRepeater', 'scholarshipTest', 
//             'lastBoardPercentage', 'competitionExam',
            
//             // Batch Allocation
//             'batchName'
//         ];

//         // Check if all required fields are present and not empty
//         foreach ($requiredFields as $field) {
//             if (!isset($data[$field]) || $data[$field] === null || $data[$field] === '') {
//                 Log::info("Field missing or empty: {$field}");
//                 return false;
//             }
//         }

//         return true;
//     }

//     /**
//      * Update the student information and move to onboard if complete
//      */
//     public function update(Request $request, string $id)
//     {
//         try {
//             Log::info('Update request received', [
//                 'id' => $id,
//                 'data' => $request->except(['_token', '_method'])
//             ]);

//             // Try Student model first, then Pending model
//             $student = null;
//             $modelUsed = null;
            
//             try {
//                 $student = Student::findOrFail($id);
//                 $modelUsed = 'Student';
//             } catch (\Exception $e) {
//                 try {
//                     $student = Pending::findOrFail($id);
//                     $modelUsed = 'Pending';
//                 } catch (\Exception $e2) {
//                     Log::error('Student not found in both models:', ['id' => $id]);
//                     throw new \Illuminate\Database\Eloquent\ModelNotFoundException();
//                 }
//             }
            
//             Log::info('Student found', [
//                 'student_id' => $id,
//                 'name' => $student->name,
//                 'model' => $modelUsed
//             ]);

//             // Validation rules
//             $validated = $request->validate([
//                 'name' => 'required|string|max:255',
//                 'father' => 'nullable|string|max:255',
//                 'mother' => 'nullable|string|max:255',
//                 'dob' => 'nullable|date',
//                 'mobileNumber' => 'nullable|string|max:15',
//                 'fatherWhatsapp' => 'nullable|string|max:15',
//                 'motherContact' => 'nullable|string|max:15',
//                 'studentContact' => 'nullable|string|max:15',
//                 'category' => 'nullable|in:OBC,SC,GENERAL,ST',
//                 'gender' => 'nullable|in:Male,Female,Others',
//                 'fatherOccupation' => 'nullable|string',
//                 'fatherGrade' => 'nullable|string',
//                 'motherOccupation' => 'nullable|string',
//                 'state' => 'nullable|string',
//                 'city' => 'nullable|string',
//                 'pinCode' => 'nullable|string|max:10',
//                 'address' => 'nullable|string',
//                 'belongToOtherCity' => 'nullable|in:Yes,No',
//                 'economicWeakerSection' => 'nullable|in:Yes,No',
//                 'armyPoliceBackground' => 'nullable|in:Yes,No',
//                 'speciallyAbled' => 'nullable|in:Yes,No',
//                 'courseType' => 'nullable|string',
//                 'courseName' => 'nullable|string',
//                 'deliveryMode' => 'nullable|string',
//                 'medium' => 'nullable|string',
//                 'board' => 'nullable|string',
//                 'courseContent' => 'nullable|string',
//                 'previousClass' => 'nullable|string',
//                 'previousMedium' => 'nullable|string',
//                 'schoolName' => 'nullable|string',
//                 'previousBoard' => 'nullable|string',
//                 'passingYear' => 'nullable|string',
//                 'percentage' => 'nullable|numeric|min:0|max:100',
//                 'isRepeater' => 'nullable|in:Yes,No',
//                 'scholarshipTest' => 'nullable|in:Yes,No',
//                 'lastBoardPercentage' => 'nullable|numeric|min:0|max:100',
//                 'competitionExam' => 'nullable|in:Yes,No',
//                 'batchName' => 'nullable|string',
//             ]);

//             Log::info('Validation passed');

//             // Update the student in original collection
//             $updated = $student->update($validated);
            
//             Log::info('Student update result', [
//                 'id' => $id,
//                 'name' => $student->name,
//                 'updated' => $updated,
//                 'model' => $modelUsed
//             ]);

//             // Check if profile is complete
//             $isComplete = $this->isProfileComplete($validated);
            
//             Log::info('Profile completion check:', [
//                 'id' => $id,
//                 'is_complete' => $isComplete
//             ]);

//             // If profile is complete, move to onboard collection
//             if ($isComplete) {
//                 Log::info('Profile is complete, moving to onboard collection:', [
//                     'id' => $id,
//                     'name' => $student->name
//                 ]);

//                 //   Get ALL student data including scholarship/fees
//                 $onboardData = $student->toArray();
                
//                 // Remove MongoDB _id to create new document
//                 unset($onboardData['_id']);
                
//                 // Add onboarded timestamp
//                 $onboardData['onboardedAt'] = now();
//                 $onboardData['status'] = 'onboarded';
                
//                 // Merge with validated data
//                 $onboardData = array_merge($onboardData, $validated);

//                 //   Log scholarship data before creating onboarded student
//                 Log::info('=== SCHOLARSHIP DATA IN PENDING FEES STUDENT ===', [
//                     'student_id' => $student->_id,
//                     'eligible_for_scholarship' => $student->eligible_for_scholarship ?? 'NOT SET',
//                     'scholarship_name' => $student->scholarship_name ?? 'NOT SET',
//                     'total_fee_before_discount' => $student->total_fee_before_discount ?? 'NOT SET',
//                     'total_fees' => $student->total_fees ?? 'NOT SET',
//                     'gst_amount' => $student->gst_amount ?? 'NOT SET',
//                 ]);

//                 // Create entry in Onboard collection
//                 $onboardedStudent = Onboard::create($onboardData);

//                 Log::info('  Onboarded student created from pending fees:', [
//                     'onboarded_id' => $onboardedStudent->_id,
//                     'name' => $onboardedStudent->name,
//                     'eligible_for_scholarship' => $onboardedStudent->eligible_for_scholarship ?? 'NOT SAVED',
//                     'scholarship_name' => $onboardedStudent->scholarship_name ?? 'NOT SAVED',
//                     'total_fees' => $onboardedStudent->total_fees ?? 'NOT SAVED',
//                 ]);

//                 // Delete from original Student/Pending collection
//                 $student->delete();

//                 Log::info('Student moved to onboard collection successfully:', [
//                     'id' => $id,
//                     'name' => $validated['name']
//                 ]);

//                 return redirect()
//                     ->route('student.onboard.onboard')
//                     ->with('success', 'Student profile completed and moved to onboard successfully!');
//             }

//             // If not complete, just return with success message
//             return redirect()
//                 ->route('student.pendingfees.pending')
//                 ->with('info', 'Student details updated successfully! Please complete all fields to move to onboard.');

//         } catch (\Illuminate\Validation\ValidationException $e) {
//             Log::error('Validation error:', ['errors' => $e->errors()]);
//             return redirect()
//                 ->back()
//                 ->withErrors($e->errors())
//                 ->withInput();
                
//         } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
//             Log::error('Student not found:', ['id' => $id]);
//             return redirect()
//                 ->route('student.pendingfees.pending')
//                 ->with('error', 'Student not found');
                
//         } catch (\Exception $e) {
//             Log::error('Update failed:', [
//                 'id' => $id,
//                 'error' => $e->getMessage(),
//                 'trace' => $e->getTraceAsString()
//             ]);
            
//             return redirect()
//                 ->back()
//                 ->with('error', 'Failed to update student: ' . $e->getMessage())
//                 ->withInput();
//         }
//     }

//     /**
//  * Show payment form
//  */
// public function pay($id)
// {
//     try {
//         // Find student in all collections
//         $student = null;
        
//         if ($student = Student::find($id)) {
//             $collection = 'students';
//         } elseif ($student = Pending::find($id)) {
//             $collection = 'pending';
//         } elseif ($student = Onboard::find($id)) {
//             $collection = 'onboard';
//         }
        
//         if (!$student) {
//             throw new \Exception('Student not found');
//         }
        
//         // Get fee details
//         $totalFees = floatval($student->total_fees ?? $student->total_fee_before_discount ?? 0);
//         $gstAmount = floatval($student->gst_amount ?? 0);
        
//         if ($gstAmount == 0 && $totalFees > 0) {
//             $gstAmount = $totalFees * 0.18;
//         }
        
//         if ($totalFees == 0) {
//             $totalFees = 100000;
//             $gstAmount = 18000;
//         }
        
//         $totalFeesWithGST = $totalFees + $gstAmount;
        
//         // Calculate paid amount
//         $totalPaid = 0;
//         if (isset($student->paymentHistory) && is_array($student->paymentHistory)) {
//             foreach ($student->paymentHistory as $payment) {
//                 $totalPaid += floatval($payment['amount'] ?? 0);
//             }
//         } else {
//             $totalPaid = floatval($student->paid_fees ?? $student->paidAmount ?? 0);
//         }
        
//         $remainingBalance = max(0, $totalFeesWithGST - $totalPaid);
//         $firstInstallment = $totalFeesWithGST * 0.40;
        
//         Log::info('Payment form opened:', [
//             'student_id' => $id,
//             'total_fees' => $totalFees,
//             'total_paid' => $totalPaid,
//             'remaining' => $remainingBalance
//         ]);
        
//         return view('student.pendingfees.pay', compact(
//             'student',
//             'totalFees',
//             'gstAmount',
//             'totalFeesWithGST',
//             'totalPaid',
//             'remainingBalance',
//             'firstInstallment'
//         ));
        
//     } catch (\Exception $e) {
//         Log::error('Payment form error:', [
//             'id' => $id,
//             'error' => $e->getMessage()
//         ]);
        
//         return redirect()->route('student.pendingfees.pending')
//             ->with('error', 'Unable to load payment form');
//     }
// }

// /**
//  * Process payment and transfer to SM Students if fully paid
//  */
// public function processPayment(Request $request, $id)
// {
//     try {
//         Log::info('=== PAYMENT PROCESSING STARTED ===', ['id' => $id]);
        
//         // Validate payment data
//         $validated = $request->validate([
//             'payment_date' => 'required|date',
//             'payment_type' => 'required|in:cash,online,cheque,card',
//             'payment_amount' => 'required|numeric|min:1',
//             'transaction_id' => 'nullable|string',
//             'remarks' => 'nullable|string',
//             'do_you_want_to_pay_fees' => 'required|in:single_payment,in_installment',
//             'other_charges' => 'nullable|numeric|min:0'
//         ]);
        
//         // Find student in any collection
//         $student = Student::find($id) ?? Pending::find($id) ?? Onboard::findOrFail($id);
//         $originalCollection = null;
        
//         if (Student::find($id)) {
//             $originalCollection = 'students';
//         } elseif (Pending::find($id)) {
//             $originalCollection = 'pending';
//         } else {
//             $originalCollection = 'onboard';
//         }
        
//         Log::info('Student found in collection:', ['collection' => $originalCollection]);
        
//         // Calculate payment amounts
//         $paymentAmount = floatval($validated['payment_amount']);
//         $otherCharges = floatval($validated['other_charges'] ?? 0);
        
//         // Create payment record
//         $paymentRecord = [
//             'date' => $validated['payment_date'],
//             'amount' => $paymentAmount,
//             'method' => $validated['payment_type'],
//             'transaction_id' => $validated['transaction_id'] ?? null,
//             'remarks' => $validated['remarks'] ?? null,
//             'type' => $validated['do_you_want_to_pay_fees'],
//             'other_charges' => $otherCharges,
//             'recorded_at' => now()->toDateTimeString(),
//             'recorded_by' => auth()->user()->name ?? 'Admin'
//         ];
        
//         // Update payment history
//         $paymentHistory = $student->paymentHistory ?? [];
//         if (!is_array($paymentHistory)) {
//             $paymentHistory = [];
//         }
//         $paymentHistory[] = $paymentRecord;
        
//         // Calculate totals
//         $totalFees = floatval($student->total_fees ?? $student->total_fee_before_discount ?? 0);
//         $gstAmount = floatval($student->gst_amount ?? 0);
        
//         if ($gstAmount == 0 && $totalFees > 0) {
//             $gstAmount = $totalFees * 0.18;
//         }
        
//         $totalFeesWithGST = $totalFees + $gstAmount;
        
//         // Calculate new paid amount
//         $newPaidAmount = 0;
//         foreach ($paymentHistory as $payment) {
//             $newPaidAmount += floatval($payment['amount'] ?? 0);
//         }
        
//         $newRemainingBalance = max(0, $totalFeesWithGST - $newPaidAmount);
        
//         Log::info('Payment calculation:', [
//             'total_fees' => $totalFees,
//             'gst' => $gstAmount,
//             'total_with_gst' => $totalFeesWithGST,
//             'new_paid' => $newPaidAmount,
//             'remaining' => $newRemainingBalance
//         ]);
        
//         //   CHECK IF FULLY PAID - TRANSFER TO SM STUDENTS
//         if ($newRemainingBalance <= 0) {
//             Log::info('  FEES FULLY PAID - Transferring to Students');
            
//             // Prepare data for SM Students collection
//             $smStudentData = [
//                 // Basic Info
//                 'roll_no' => $student->roll_no ?? null,
//                 'student_name' => $student->name ?? $student->student_name,
//                 'email' => $student->email ?? $student->studentContact,
//                 'phone' => $student->mobileNumber ?? $student->phone ?? $student->studentContact,
                
//                 // Personal Details
//                 'father_name' => $student->father ?? null,
//                 'mother_name' => $student->mother ?? null,
//                 'dob' => $student->dob ?? null,
//                 'father_contact' => $student->fatherWhatsapp ?? null,
//                 'father_whatsapp' => $student->fatherWhatsapp ?? null,
//                 'mother_contact' => $student->motherContact ?? null,
//                 'gender' => $student->gender ?? null,
//                 'father_occupation' => $student->fatherOccupation ?? null,
//                 'father_caste' => $student->category ?? null,
//                 'mother_occupation' => $student->motherOccupation ?? null,
                
//                 // Address
//                 'state' => $student->state ?? null,
//                 'city' => $student->city ?? null,
//                 'pincode' => $student->pinCode ?? null,
//                 'address' => $student->address ?? null,
//                 'belongs_other_city' => $student->belongToOtherCity ?? 'No',
                
//                 // Academic Details
//                 'previous_class' => $student->previousClass ?? null,
//                 'academic_medium' => $student->previousMedium ?? $student->medium ?? null,
//                 'school_name' => $student->schoolName ?? null,
//                 'academic_board' => $student->previousBoard ?? $student->board ?? null,
//                 'passing_year' => $student->passingYear ?? null,
//                 'percentage' => $student->percentage ?? null,
                
//                 // Course Details
//                 'batch_id' => $student->batch_id ?? null,
//                 'batch_name' => $student->batchName ?? null,
//                 'course_id' => $student->course_id ?? null,
//                 'course_name' => $student->courseName ?? null,
//                 'course_content' => $student->courseContent ?? $student->fees_breakup ?? null,
//                 'delivery' => $student->deliveryMode ?? 'Offline',
//                 'delivery_mode' => $student->deliveryMode ?? 'Offline',
//                 'shift' => $student->shift ?? null,
                
//                 // Fee Details (ALL scholarship and payment data)
//                 'eligible_for_scholarship' => $student->eligible_for_scholarship ?? 'No',
//                 'scholarship_name' => $student->scholarship_name ?? null,
//                 'total_fee_before_discount' => $student->total_fee_before_discount ?? $totalFees,
//                 'discretionary_discount' => $student->discretionary_discount ?? 'No',
//                 'discount_percentage' => $student->discount_percentage ?? 0,
//                 'discounted_fee' => $student->discounted_fee ?? $totalFees,
//                 'total_fees' => $totalFees,
//                 'gst_amount' => $gstAmount,
//                 'total_fees_inclusive_tax' => $totalFeesWithGST,
//                 'paid_fees' => $newPaidAmount,
//                 'paidAmount' => $newPaidAmount,
//                 'remaining_fees' => 0,
//                 'remainingAmount' => 0,
//                 'fee_status' => 'paid',
//                 'paymentHistory' => $paymentHistory,
//                 'last_payment_date' => $validated['payment_date'],
                
//                 // Documents
//                 'passport_photo' => $student->passport_photo ?? null,
//                 'marksheet' => $student->marksheet ?? null,
//                 'caste_certificate' => $student->caste_certificate ?? null,
//                 'scholarship_proof' => $student->scholarship_proof ?? null,
//                 'secondary_marksheet' => $student->secondary_marksheet ?? null,
//                 'senior_secondary_marksheet' => $student->senior_secondary_marksheet ?? null,
                
//                 // Status
//                 'status' => 'active',
//                 'transferred_from' => $originalCollection,
//                 'transferred_at' => now(),
//                 'created_at' => $student->created_at ?? now(),
//                 'updated_at' => now()
//             ];
            
//             // Create in SM Students collection
//             $smStudent = \App\Models\Student\SMstudents::create($smStudentData);
            
//             Log::info(' Student transferred to Registered Students:', [
//                 'sm_student_id' => $smStudent->_id,
//                 'name' => $smStudent->student_name,
//                 'roll_no' => $smStudent->roll_no
//             ]);
            
//             // Delete from original collection
//             $student->delete();
            
//             Log::info('Student removed from original collection:', ['collection' => $originalCollection]);
            
//             return redirect()->route('smstudents.index')
//                 ->with('success', "Payment successful! Student '{$smStudent->student_name}' has been moved to Active Student with full fees paid.");
//         }
        
//         // If NOT fully paid, just update payment details
//         $feeStatus = $newRemainingBalance <= 0 ? 'paid' : ($newPaidAmount > 0 ? 'partial' : 'pending');
        
//         $student->update([
//             'paymentHistory' => $paymentHistory,
//             'paid_fees' => $newPaidAmount,
//             'paidAmount' => $newPaidAmount,
//             'remaining_fees' => $newRemainingBalance,
//             'remainingAmount' => $newRemainingBalance,
//             'fee_status' => $feeStatus,
//             'last_payment_date' => $validated['payment_date'],
//             'total_fees' => $totalFees,
//             'gst_amount' => $gstAmount,
//             'total_fees_inclusive_tax' => $totalFeesWithGST,
//             'updated_at' => now()
//         ]);
        
//         Log::info('  Payment recorded (partial payment)');
        
//         $message = "Payment of ₹" . number_format($paymentAmount, 2) . " recorded successfully!";
//         $message .= " Remaining: ₹" . number_format($newRemainingBalance, 2);
        
//         return redirect()->route('student.pendingfees.pending')
//             ->with('success', $message);
            
//     } catch (\Exception $e) {
//         Log::error('❌ Payment processing failed:', [
//             'error' => $e->getMessage(),
//             'trace' => $e->getTraceAsString()
//         ]);
        
//         return redirect()->back()
//             ->with('error', 'Payment failed: ' . $e->getMessage())
//             ->withInput();
//     }
// }
// }