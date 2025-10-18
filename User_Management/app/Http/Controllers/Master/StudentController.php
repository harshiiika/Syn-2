<?php

// namespace App\Http\Controllers\Master;

// use App\Http\Controllers\Controller;
// use App\Models\Master\Student;
// use App\Models\Student\Inquiry;
// use Illuminate\Http\Request;

// class StudentController extends Controller
// {
//     /**
//      * Display pending students (newly converted from inquiry)
//      */
//    // Show pending fees students
// // public function index()
// // {
// //     try {
// //         $students = Student::all(); // Get ALL students first to test
        
// //         \Log::info('Fetching students for pending page:', [
// //             'count' => $students->count()
// //         ]);
        
// //         return view('student.html', [
// //             'students' => $students,
// //             'totalCount' => $students->count(),
// //         ]);
// //     } catch (\Exception $e) {
// //         \Log::error('Error loading students: ' . $e->getMessage());
// //         return redirect()->back()->with('error', 'Failed to load students');
// //     }
// // }

// // Show fully paid (onboarded) students
// public function activeStudents()
// {
//     $students = Student::getActiveStudents();
//     return view('master.student.onboard', compact('students'));
// }

//     /**
//      * Display pending fees students
//      */
//     public function pendingFees()
//     {
//         try {
//             $students = Student::getPendingFeesStudents();
            
//             return view('student.html_fees', [
//                 'students' => $students,
//                 'totalCount' => $students->count(),
//             ]);
//         } catch (\Exception $e) {
//             return redirect()->back()->with('error', 'Failed to load pending fees students');
//         }
//     }


//     /**
//      * Show single student details
//      */
//     public function show($id)
//     {
//         try {
//             $student = Student::getStudentById($id);
            
//             if (!$student) {
//                 return redirect()->back()->with('error', 'Student not found');
//             }
            
//             return view('master.student.show', ['student' => $student]);
//         } catch (\Exception $e) {
//             return redirect()->back()->with('error', 'Error retrieving student');
//         }
//     }

//     /**
//      * Convert inquiry to student (pending process)
//      */
//     public function convertFromInquiry(Request $request, $inquiryId)
//     {
//         try {
//             // Get inquiry data
//             $inquiry = Inquiry::findOrFail($inquiryId);
            
//             // Validate additional pending data
//             $validated = $request->validate([
//                 'total_fees' => 'required|numeric|min:0',
//                 'paid_fees' => 'nullable|numeric|min:0',
//                 'courseName' => 'required|string',
//                 'deliveryMode' => 'required|string',
//                 'courseContent' => 'nullable|string',
//                 'branch' => 'required|string',
//             ]);

//             // Calculate remaining fees
//             $totalFees = $validated['total_fees'];
//             $paidFees = $validated['paid_fees'] ?? 0;
//             $remainingFees = $totalFees - $paidFees;

//             // Determine status based on fees
//             if ($remainingFees <= 0) {
//                 $status = Student::STATUS_ACTIVE;
//                 $feeStatus = 'paid';
//             } elseif ($paidFees > 0) {
//                 $status = Student::STATUS_PENDING_FEES;
//                 $feeStatus = 'partial';
//             } else {
//                 $status = Student::STATUS_PENDING_FEES;
//                 $feeStatus = 'pending';
//             }

//             // Create student from inquiry
//             $student = Student::create([
//                 'name' => $inquiry->name,
//                 'father' => $inquiry->father,
//                 'mobileNumber' => $inquiry->mobileNumber,
//                 'alternateNumber' => $inquiry->alternateNumber ?? null,
//                 'email' => $inquiry->email,
//                 'courseName' => $validated['courseName'],
//                 'deliveryMode' => $validated['deliveryMode'],
//                 'courseContent' => $validated['courseContent'] ?? null,
//                 'branch' => $validated['branch'],
//                 'total_fees' => $totalFees,
//                 'paid_fees' => $paidFees,
//                 'remaining_fees' => $remainingFees,
//                 'status' => $status,
//                 'fee_status' => $feeStatus,
//             ]);

//             // Update inquiry status
//             $inquiry->update(['status' => 'converted']);

//             // Redirect based on fees status
//             if ($remainingFees > 0) {
//                 return redirect()->route('students.pending_fees')
//                     ->with('success', 'Student onboarded successfully! Pending fees: ₹' . $remainingFees);
//             } else {
//                 return redirect()->route('students.active')
//                     ->with('success', 'Student onboarded successfully with full payment!');
//             }

//         } catch (\Exception $e) {
//             return redirect()->back()
//                 ->with('error', 'Failed to onboard student: ' . $e->getMessage());
//         }
//     }

//     /**
//      * Store a newly created student (direct entry)
//      */
//     public function store(Request $request)
//     {
//         try {
//             $validated = $request->validate([
//                 'name' => 'required|string|max:255',
//                 'father' => 'required|string|max:255',
//                 'mobileNumber' => 'required|string|regex:/^[0-9]{10}$/',
//                 'courseName' => 'required|string|max:255',
//                 'deliveryMode' => 'required|string',
//                 'courseContent' => 'nullable|string',
//                 'email' => 'required|email|unique:mongodb.students',
//                 'alternateNumber' => 'nullable|string|regex:/^[0-9]{10}$/',
//                 'branch' => 'required|string',
//                 'total_fees' => 'required|numeric|min:0',
//                 'paid_fees' => 'nullable|numeric|min:0',
//             ]);

//             // Calculate fees
//             $totalFees = $validated['total_fees'];
//             $paidFees = $validated['paid_fees'] ?? 0;
//             $remainingFees = $totalFees - $paidFees;

//             // Determine status
//             if ($remainingFees <= 0) {
//                 $status = Student::STATUS_ACTIVE;
//                 $feeStatus = 'paid';
//             } elseif ($paidFees > 0) {
//                 $status = Student::STATUS_PENDING_FEES;
//                 $feeStatus = 'partial';
//             } else {
//                 $status = Student::STATUS_PENDING_FEES;
//                 $feeStatus = 'pending';
//             }

//             $student = Student::create([
//                 'name' => $validated['name'],
//                 'father' => $validated['father'],
//                 'mobileNumber' => $validated['mobileNumber'],
//                 'alternateNumber' => $validated['alternateNumber'] ?? null,
//                 'email' => $validated['email'],
//                 'courseName' => $validated['courseName'],
//                 'deliveryMode' => $validated['deliveryMode'],
//                 'courseContent' => $validated['courseContent'] ?? null,
//                 'branch' => $validated['branch'],
//                 'total_fees' => $totalFees,
//                 'paid_fees' => $paidFees,
//                 'remaining_fees' => $remainingFees,
//                 'status' => $status,
//                 'fee_status' => $feeStatus,
//             ]);

//             if ($request->ajax()) {
//                 return response()->json([
//                     'status' => 'success',
//                     'message' => 'Student added successfully',
//                     'student' => $student
//                 ], 201);
//             }

//             return redirect()->route('student.html')->with('success', 'Student added successfully');
//         } catch (\Illuminate\Validation\ValidationException $e) {
//             if ($request->ajax()) {
//                 return response()->json([
//                     'status' => 'error',
//                     'errors' => $e->errors()
//                 ], 422);
//             }

//             return redirect()->back()->withErrors($e->errors())->withInput();
//         } catch (\Exception $e) {
//             if ($request->ajax()) {
//                 return response()->json([
//                     'status' => 'error',
//                     'message' => 'Failed to add student'
//                 ], 500);
//             }

//             return redirect()->back()->with('error', 'Failed to add student');
//         }
//     }

//     /**
//      * Update the specified student
//      */
//     public function update(Request $request, $id)
//     {
//         try {
//             $student = Student::findOrFail($id);

//             $validated = $request->validate([
//                 'name' => 'required|string|max:255',
//                 'father' => 'required|string|max:255',
//                 'mobileNumber' => 'required|string|regex:/^[0-9]{10}$/',
//                 'courseName' => 'required|string|max:255',
//                 'deliveryMode' => 'required|string',
//                 'courseContent' => 'nullable|string',
//                 'email' => 'required|email|unique:mongodb.students,email,' . $id . ',_id',
//                 'alternateNumber' => 'nullable|string|regex:/^[0-9]{10}$/',
//                 'branch' => 'required|string',
//             ]);

//             $student->update($validated);

//             if ($request->ajax()) {
//                 return response()->json([
//                     'status' => 'success',
//                     'message' => 'Student updated successfully'
//                 ]);
//             }

//             return redirect()->route('student.html')->with('success', 'Student updated successfully');
//         } catch (\Illuminate\Validation\ValidationException $e) {
//             if ($request->ajax()) {
//                 return response()->json([
//                     'status' => 'error',
//                     'errors' => $e->errors()
//                 ], 422);
//             }

//             return redirect()->back()->withErrors($e->errors())->withInput();
//         } catch (\Exception $e) {
//             if ($request->ajax()) {
//                 return response()->json([
//                     'status' => 'error',
//                     'message' => 'Failed to update student'
//                 ], 500);
//             }

//             return redirect()->back()->with('error', 'Failed to update student');
//         }
//     }

//     /**
//      * Update student fees (collect payment)
//      */
//     public function updateFees(Request $request, $id)
//     {
//         try {
//             $student = Student::findOrFail($id);
            
//             $validated = $request->validate([
//                 'payment_amount' => 'required|numeric|min:0',
//             ]);

//             $paymentAmount = $validated['payment_amount'];
//             $student->paid_fees += $paymentAmount;
//             $student->remaining_fees -= $paymentAmount;

//             // Update status based on remaining fees
//             if ($student->remaining_fees <= 0) {
//                 $student->status = Student::STATUS_ACTIVE;
//                 $student->fee_status = 'paid';
//                 $student->remaining_fees = 0; // Ensure it doesn't go negative
//             } elseif ($student->paid_fees > 0) {
//                 $student->fee_status = 'partial';
//             }

//             $student->save();

//             if ($request->ajax()) {
//                 return response()->json([
//                     'status' => 'success',
//                     'message' => 'Payment recorded successfully',
//                     'remaining_fees' => $student->remaining_fees,
//                     'redirect' => $student->remaining_fees <= 0 ? route('students.active') : null
//                 ]);
//             }

//             // Redirect based on remaining fees
//             if ($student->remaining_fees <= 0) {
//                 return redirect()->route('students.active')
//                     ->with('success', 'Payment completed! Student is now active.');
//             }

//             return redirect()->route('students.pending_fees')
//                 ->with('success', 'Payment recorded. Remaining: ₹' . $student->remaining_fees);

//         } catch (\Exception $e) {
//             if ($request->ajax()) {
//                 return response()->json([
//                     'status' => 'error',
//                     'message' => 'Failed to update fees'
//                 ], 500);
//             }

//             return redirect()->back()->with('error', 'Failed to update fees');
//         }
//     }

// }