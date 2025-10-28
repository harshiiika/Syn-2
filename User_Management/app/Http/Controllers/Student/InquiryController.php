<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Student\Inquiry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use App\Models\Master\Scholarship;
use App\Models\Master\FeesMaster;

class InquiryController extends Controller
{
    /**
     * Display the inquiry management page
     */
    public function index(Request $request)
    {
        return view('inquiries.index');
    }

    /**
     * Return paginated data for AJAX requests
     */
    public function data(Request $request)
{
    try {
        $query = Inquiry::query();
        
        // ⭐ Filter out onboarded inquiries - only show active inquiries
        $query->whereNotIn('status', ['onboarded', 'converted']);

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('student_name', 'regex', "/$search/i")
                  ->orWhere('father_name', 'regex', "/$search/i")
                  ->orWhere('father_contact', 'regex', "/$search/i")
                  ->orWhere('course_name', 'regex', "/$search/i");
            });
        }

        $perPage = $request->get('per_page', 10);
        $page = $request->get('page', 1);
        
        $total = $query->count();
        $inquiries = $query->orderBy('created_at', 'desc')
                           ->skip(($page - 1) * $perPage)
                           ->take($perPage)
                           ->get();

        // Transform MongoDB _id to string for JavaScript
        $inquiries = $inquiries->map(function($inquiry) {
            $inquiry->_id = (string) $inquiry->_id;
            return $inquiry;
        });

        Log::info('Data method - Returning inquiries', [
            'count' => $inquiries->count(),
            'total' => $total,
            'page' => $page
        ]);

        return response()->json([
            'success' => true,
            'data' => $inquiries,
            'current_page' => (int)$page,
            'last_page' => (int)ceil($total / $perPage),
            'per_page' => (int)$perPage,
            'total' => $total,
        ], 200);

    } catch (\Exception $e) {
        Log::error('Inquiry Data Error: ' . $e->getMessage());
        Log::error('Stack trace: ' . $e->getTraceAsString());
        
        return response()->json([
            'success' => false,
            'message' => 'Error loading inquiries: ' . $e->getMessage(),
            'data' => [],
            'current_page' => 1,
            'last_page' => 1,
            'per_page' => 10,
            'total' => 0,
        ], 500);
    }
}
public function edit($id)
{
    try {
        $inquiry = Inquiry::findOrFail($id);
        
        \Log::info('Edit inquiry data:', [
            'id' => $id,
            'student_name' => $inquiry->student_name,
            'father_name' => $inquiry->father_name,
            'father_contact' => $inquiry->father_contact,
            'course_name' => $inquiry->course_name,
            'courseType' => $inquiry->courseType,
            'all_data' => $inquiry->toArray()
        ]);
        
        return view('inquiries.edit', compact('inquiry'));
    } catch (\Exception $e) {
        \Log::error('Edit page error: ' . $e->getMessage());
        return redirect()->route('inquiries.index')->with('error', 'Unable to load inquiry for editing.');
    }
}

    /**
     * Show single inquiry
     */
    public function show($id)
{
    try {
        $inquiry = Inquiry::findOrFail($id);
        return response()->json([
            'success' => true,
            'data' => $inquiry
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Inquiry not found'
        ], 404);
    }
}


    /**
     * Store a new inquiry
     */
    public function store(Request $request)
    {
        Log::info('Inquiry Store Request:', $request->all());

        $validator = Validator::make($request->all(), [
            'student_name'       => 'required|string|max:255',
            'father_name'        => 'required|string|max:255',
            'father_contact'     => 'required|string|max:20',
            'father_whatsapp'    => 'nullable|string|max:20',
            'student_contact'    => 'nullable|string|max:20',
            'category'           => 'required|string|in:General,OBC,SC,ST',
            'course_name'        => 'nullable|string|max:255',
            'delivery_mode'      => 'nullable|string|in:Online,Offline,Hybrid',
            'course_content'     => 'nullable|string|max:255',
            'branch'             => 'required|string|max:255',
            'state'              => 'nullable|string|max:255',
            'city'               => 'nullable|string|max:255',
            'address'            => 'nullable|string',
            'ews'                => 'required|string|in:Yes,No',
            'defense'            => 'required|string|in:Yes,No',
            'specially_abled'    => 'required|string|in:Yes,No',
            'status'             => 'nullable|string|in:Pending,Active,Closed,Converted',
            'remarks'            => 'nullable|string',
            'follow_up_date'     => 'nullable|date',
        ]);

        if ($validator->fails()) {
            Log::error('Inquiry Validation Failed:', $validator->errors()->toArray());
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $data = $validator->validated();
            $data['status'] = $data['status'] ?? 'Pending';
            
            Log::info('Creating inquiry with data:', $data);
            $inquiry = Inquiry::create($data);

            return response()->json([
                'success' => true,
                'message' => 'Inquiry created successfully',
                'data' => $inquiry,
            ], 201);
        } catch (\Exception $e) {
            Log::error('Inquiry Store Error: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'Failed to create inquiry: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete an inquiry
     */
    public function destroy($id)
    {
        try {
            $inquiry = Inquiry::findOrFail($id);
            $inquiry->delete();

            return response()->json([
                'success' => true,
                'message' => 'Inquiry deleted successfully',
            ], 200);
        } catch (\Exception $e) {
            Log::error('Inquiry Delete Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error deleting inquiry: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Handle file upload for bulk inquiry import
     */
    public function upload(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:csv,xlsx,xls|max:5120',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid file format. Please upload CSV or Excel file.',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            return response()->json([
                'success' => true,
                'message' => 'File uploaded successfully',
            ], 200);
        } catch (\Exception $e) {
            Log::error('Inquiry Upload Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Upload failed: ' . $e->getMessage(),
            ], 500);
        }
    }

public function bulkOnboard(Request $request)
{
    try {
        $request->validate([
            'inquiry_ids' => 'required|array',
            'inquiry_ids.*' => 'required|string'
        ]);

        $inquiryIds = $request->inquiry_ids;
        $inquiries = Inquiry::whereIn('_id', $inquiryIds)->get();

        if ($inquiries->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No valid inquiries found'
            ], 404);
        }

        $onboardedCount = 0;

        foreach ($inquiries as $inquiry) {
            // ✅ Create student record in 'students' collection
            $student = \App\Models\Student\Student::create([
                'name' => $inquiry->student_name,
                'father' => $inquiry->father_name,
                'mobileNumber' => $inquiry->father_contact,
                'alternateNumber' => $inquiry->father_whatsapp ?? null,
                'email' => $inquiry->student_contact ?? 'noemail@example.com',
                'courseName' => $inquiry->course_name ?? 'Not Assigned',
                'deliveryMode' => $inquiry->delivery_mode ?? 'Offline',
                'courseContent' => $inquiry->course_content ?? 'Class Room Course',
                'branch' => $inquiry->branch ?? 'Main Branch',
                'state' => $inquiry->state,
                'city' => $inquiry->city,
                'address' => $inquiry->address,
                'category' => $inquiry->category ?? 'General',
                'economicWeakerSection' => $inquiry->ews ?? 'No',
                'armyPoliceBackground' => $inquiry->defense ?? 'No',
                'speciallyAbled' => $inquiry->specially_abled ?? 'No',
                
                // ✅ CRITICAL: These fields make them appear in pending pages
                'total_fees' => 0,
                'paid_fees' => 0,
                'remaining_fees' => 0,
                'status' => 'pending_fees', // Must be 'pending_fees'
                'fee_status' => 'pending',
                'admission_date' => now(),
                'session' => session('current_session', '2025-2026')
            ]);

            \Log::info('Student created:', [
                'id' => $student->_id,
                'name' => $student->name,
                'status' => $student->status,
                'remaining_fees' => $student->remaining_fees
            ]);

            // ✅ Mark inquiry as onboarded
            $inquiry->update(['status' => 'onboarded']);
            
            $onboardedCount++;
        }

        return response()->json([
            'success' => true,
            'message' => "Successfully onboarded {$onboardedCount} student(s)!"
        ]);

    } catch (\Exception $e) {
        \Log::error('Bulk onboard error: ' . $e->getMessage());
        \Log::error('Stack trace: ' . $e->getTraceAsString());
        
        return response()->json([
            'success' => false,
            'message' => 'Failed to onboard students: ' . $e->getMessage()
        ], 500);
    }
}

 /**
  * Show onboard form for an inquiry
  */
public function showOnboardForm($inquiryId)
{
    try {
        $inquiry = Inquiry::findOrFail($inquiryId);
        
        // Get dropdown data
        $courses = \App\Models\Master\Courses::all(); 
        $branches = ['Bikaner']; // Or get from database
        $deliveryModes = ['Offline', 'Online', 'Hybrid'];
        $courseContents = ['Class Room Course', 'Test Series Only'];
        
        return view('student.inquiry.onboard', compact(
            'inquiry', 
            'courses', 
            'branches', 
            'deliveryModes', 
            'courseContents'
        ));
    } catch (\Exception $e) {
        return redirect()->route('inquiries.index')
            ->with('error', 'Inquiry not found');
    }
}


/**
 * Process onboarding (convert inquiry to student)
 */
public function processOnboard(Request $request, $inquiryId)
{
    try {
        $inquiry = Inquiry::findOrFail($inquiryId);
        
        // Validate
        $validated = $request->validate([
            'courseName' => 'required|string',
            'deliveryMode' => 'required|string',
            'courseContent' => 'required|string',
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
            $status = \App\Models\Student\Student::STATUS_ACTIVE;
            $feeStatus = 'paid';
        } elseif ($paidFees > 0) {
            $status = \App\Models\Student\Student::STATUS_PENDING_FEES;
            $feeStatus = 'partial';
        } else {
            $status = \App\Models\Student\Student::STATUS_PENDING_FEES;
            $feeStatus = 'pending';
        }

        // Create student
        $student = \App\Models\Student\Student::create([
            'name' => $inquiry->student_name,
            'father' => $inquiry->father_name,
            'mobileNumber' => $inquiry->father_contact,
            'alternateNumber' => $inquiry->father_whatsapp ?? null,
            'email' => $inquiry->student_name . '@temp.com', // Generate temp email
            'courseName' => $validated['courseName'],
            'deliveryMode' => $validated['deliveryMode'],
            'courseContent' => $validated['courseContent'],
            'branch' => $validated['branch'],
            'total_fees' => $totalFees,
            'paid_fees' => $paidFees,
            'remaining_fees' => $remainingFees,
            'status' => $status,
            'fee_status' => $feeStatus,
            'session' => session('current_session', '2025-2026'),
        ]);

        // Update inquiry status
        $inquiry->update(['status' => 'converted']);

        // Redirect based on status
        if ($remainingFees > 0) {
            return redirect()->route('student.pendingfees.pending')
                ->with('success', 'Student onboarded! Pending fees: ₹' . number_format($remainingFees, 2));
        } else {
            return redirect()->route('student.onboard')
                ->with('success', 'Student onboarded successfully with full payment!');
        }

    } catch (\Exception $e) {
        \Log::error('Onboard error: ' . $e->getMessage());
        return redirect()->back()
            ->with('error', 'Failed to onboard student: ' . $e->getMessage())
            ->withInput();
    }
}

public function view($id)
{
    try {
        $inquiry = Inquiry::findOrFail($id);
        
        \Log::info('View inquiry data:', $inquiry->toArray());
        
        return view('inquiries.view', compact('inquiry'));
    } catch (\Exception $e) {
        \Log::error('Failed to load inquiry details: ' . $e->getMessage());
        return redirect()->route('inquiries.index')->with('error', 'Unable to load inquiry details.');
    }
}

/**
     * Show scholarship details page after updating inquiry
     */
public function showScholarshipDetails($id)
{
    try {
        $inquiry = Inquiry::findOrFail($id);
        
        \Log::info('Scholarship Details Page - Inquiry Data:', [
            'id' => $inquiry->_id,
            'course_name' => $inquiry->course_name,
            'scholarshipTest' => $inquiry->scholarshipTest ?? 'No',
            'lastBoardPercentage' => $inquiry->lastBoardPercentage ?? null,
            'competitionExam' => $inquiry->competitionExam ?? 'No',
        ]);
        
        // Course fees mapping
        $courseFees = [
            'Anthesis 11th NEET' => 88000,
            'Momentum 12th NEET' => 88000,
            'Dynamic Target NEET' => 88000,
            'Impulse 11th IIT' => 88000,
            'Intensity 12th IIT' => 88000,
            'Thurst Target IIT' => 88000,
            'Seedling 10th' => 60000,
            'Plumule 9th' => 55000,
            'Radicle 8th' => 50000
        ];

        // Get base fee
        $courseName = $inquiry->course_name ?? '';
        $totalFeeBeforeDiscount = $courseFees[$courseName] ?? 88000;

        // Initialize scholarship variables
        $eligibleForScholarship = false;
        $scholarship = null;
        $discountPercentage = 0;
        $scholarshipDiscountedFees = $totalFeeBeforeDiscount;

        // Check eligibility: Scholarship Test OR Board >= 75% OR Competition Exam
        if (($inquiry->scholarshipTest === 'Yes') || 
            ($inquiry->lastBoardPercentage && $inquiry->lastBoardPercentage >= 75) ||
            ($inquiry->competitionExam === 'Yes')) {
            
            $eligibleForScholarship = true;

            // Priority 1: Scholarship Test
            if ($inquiry->scholarshipTest === 'Yes') {
                $scholarship = Scholarship::where('scholarship_type', 'Test Based')
                    ->where('is_active', true)
                    ->orderBy('discount_percentage', 'desc')
                    ->first();
            }
            
            // Priority 2: Board Percentage
            if (!$scholarship && $inquiry->lastBoardPercentage >= 75) {
                $percentage = $inquiry->lastBoardPercentage;
                
                $scholarship = Scholarship::where('scholarship_type', 'Board Examination Scholarship')
                    ->where('is_active', true)
                    ->where('min_percentage', '<=', $percentage)
                    ->where('max_percentage', '>=', $percentage)
                    ->orderBy('discount_percentage', 'desc')
                    ->first();
            }
            
            // Priority 3: Competition Exam
            if (!$scholarship && $inquiry->competitionExam === 'Yes') {
                $scholarship = Scholarship::where('scholarship_type', 'Competition Exam Scholarship')
                    ->where('is_active', true)
                    ->orderBy('discount_percentage', 'desc')
                    ->first();
            }

            // Calculate discount if scholarship found
            if ($scholarship) {
                $discountPercentage = $scholarship->discount_percentage ?? 0;
                $discountAmount = ($totalFeeBeforeDiscount * $discountPercentage) / 100;
                $scholarshipDiscountedFees = $totalFeeBeforeDiscount - $discountAmount;
            }
        }

        // Final fees (before discretionary discount)
        $finalFees = $scholarshipDiscountedFees;

        \Log::info('Scholarship Calculation Results:', [
            'eligibleForScholarship' => $eligibleForScholarship,
            'scholarship_found' => $scholarship ? true : false,
            'discountPercentage' => $discountPercentage,
            'totalFeeBeforeDiscount' => $totalFeeBeforeDiscount,
            'scholarshipDiscountedFees' => $scholarshipDiscountedFees,
        ]);

        return view('inquiries.scholarship-details', compact(
            'inquiry',
            'eligibleForScholarship',
            'scholarship',
            'totalFeeBeforeDiscount',
            'discountPercentage',
            'scholarshipDiscountedFees',
            'finalFees'
        ));

    } catch (\Exception $e) {
        \Log::error('Scholarship details error: ' . $e->getMessage());
        \Log::error('Stack trace: ' . $e->getTraceAsString());
return redirect()->route('inquiries.scholarship.show', ['id' => $id])
            ->with('error', 'Error loading scholarship details: ' . $e->getMessage());
    }
}

    /**
     * Update scholarship and fees information
     */
   public function updateScholarshipDetails(Request $request, $id)
{
    try {
        $inquiry = Inquiry::findOrFail($id);

        // Validate discretionary discount
        if ($request->add_discretionary_discount === 'Yes') {
            $request->validate([
                'discretionary_discount_type' => 'required|in:percentage,fixed',
                'discretionary_discount_value' => 'required|numeric|min:0',
                'discretionary_discount_reason' => 'required|string|max:500',
            ]);

            // Validate percentage <= 100%
            if ($request->discretionary_discount_type === 'percentage' && 
                $request->discretionary_discount_value > 100) {
                return back()->with('error', 'Discount percentage cannot exceed 100%');
            }

            // Validate fixed amount <= discounted fees
            if ($request->discretionary_discount_type === 'fixed' && 
                $request->discretionary_discount_value > $request->scholarship_discounted_fees) {
                return back()->with('error', 'Discount amount cannot exceed the discounted fees');
            }
        }

        // Prepare update data
        $updateData = [
            'total_fee_before_discount' => $request->total_fee_before_discount,
            'scholarship_discount_percentage' => $request->scholarship_discount_percentage ?? 0,
            'scholarship_discounted_fees' => $request->scholarship_discounted_fees,
            'final_fees' => $request->final_fees,
            'eligible_for_scholarship' => $request->scholarship_discount_percentage > 0,
        ];

        // Handle discretionary discount
        if ($request->add_discretionary_discount === 'Yes') {
            $updateData['has_discretionary_discount'] = true;
            $updateData['discretionary_discount_type'] = $request->discretionary_discount_type;
            $updateData['discretionary_discount_value'] = $request->discretionary_discount_value;
            $updateData['discretionary_discount_reason'] = $request->discretionary_discount_reason;
        } else {
            $updateData['has_discretionary_discount'] = false;
            $updateData['discretionary_discount_type'] = null;
            $updateData['discretionary_discount_value'] = null;
            $updateData['discretionary_discount_reason'] = null;
        }

        // Update inquiry
        $inquiry->update($updateData);

        // Now create student record and move to onboarding
        $student = \App\Models\Student\Student::create([
            'name' => $inquiry->student_name,
            'father' => $inquiry->father_name,
            'mother' => $inquiry->mother,
            'dob' => $inquiry->dob,
            'mobileNumber' => $inquiry->father_contact,
            'alternateNumber' => $inquiry->father_whatsapp,
            'studentContact' => $inquiry->student_contact,
            'email' => $inquiry->student_contact ?? $inquiry->student_name . '@temp.com',
            
            // Address
            'state' => $inquiry->state,
            'city' => $inquiry->city,
            'pinCode' => $inquiry->pinCode,
            'address' => $inquiry->address,
            
            // Category & Background
            'category' => $inquiry->category,
            'gender' => $inquiry->gender,
            'economicWeakerSection' => $inquiry->economicWeakerSection ?? 'No',
            'armyPoliceBackground' => $inquiry->armyPoliceBackground ?? 'No',
            'speciallyAbled' => $inquiry->speciallyAbled ?? 'No',
            'belongToOtherCity' => $inquiry->belongToOtherCity ?? 'No',
            
            // Course Details
            'courseType' => $inquiry->courseType,
            'courseName' => $inquiry->course_name,
            'deliveryMode' => $inquiry->delivery_mode,
            'medium' => $inquiry->medium,
            'board' => $inquiry->board,
            'courseContent' => $inquiry->course_content,
            
            // Fees Details
            'total_fees' => $inquiry->final_fees,
            'paid_fees' => 0,
            'remaining_fees' => $inquiry->final_fees,
            
            // Scholarship Details (if applicable)
            'scholarship_discount' => $inquiry->scholarship_discount_percentage ?? 0,
            'discretionary_discount' => $inquiry->has_discretionary_discount ? $inquiry->discretionary_discount_value : 0,
            
            // Status
            'status' => 'pending_fees',
            'fee_status' => 'pending',
            'admission_date' => now(),
            'session' => session('current_session', '2025-2026'),
        ]);

        // Mark inquiry as converted
        $inquiry->update(['status' => 'converted']);

        \Log::info('Student created from inquiry', [
            'student_id' => $student->_id,
            'inquiry_id' => $inquiry->_id,
            'total_fees' => $inquiry->final_fees,
            'scholarship_discount' => $inquiry->scholarship_discount_percentage,
        ]);

        // Redirect to pending fees page
        return redirect()->route('student.pendingfees.pending')
            ->with('success', 'Student onboarded successfully! Pending fees: ₹' . number_format($inquiry->final_fees, 2));

    } catch (\Exception $e) {
        \Log::error('Scholarship update error: ' . $e->getMessage());
        \Log::error('Stack trace: ' . $e->getTraceAsString());
        
        return back()
            ->with('error', 'Error updating scholarship details: ' . $e->getMessage())
            ->withInput();
    }
}

    /**
     * Update inquiry
     */
public function update(Request $request, $id)
{
    \Log::info('UPDATE METHOD CALLED', [
        'id' => $id,
        'all_data' => $request->all()
    ]);

    // Validate only the REQUIRED fields - make everything else optional
    $validatedData = $request->validate([
        'name' => 'required|string|max:255',
        'father' => 'required|string|max:255',
        'mother' => 'nullable|string|max:255',
        'dob' => 'nullable|date',
        'mobileNumber' => 'required|string|max:15',
        'fatherWhatsapp' => 'nullable|string|max:15',
        'motherContact' => 'nullable|string|max:15',
        'studentContact' => 'nullable|string|max:15',
        'category' => 'required|in:GENERAL,OBC,SC,ST',
        'gender' => 'required|in:Male,Female,Others',
        'fatherOccupation' => 'nullable|string|max:255',
        'fatherGrade' => 'nullable|string|max:255',
        'motherOccupation' => 'nullable|string|max:255',
        'state' => 'nullable|string|max:255',
        'city' => 'nullable|string|max:255',
        'pinCode' => 'nullable|string|max:6',
        'address' => 'nullable|string',
        'belongToOtherCity' => 'nullable|in:Yes,No',
        'economicWeakerSection' => 'nullable|in:Yes,No',
        'armyPoliceBackground' => 'nullable|in:Yes,No',
        'speciallyAbled' => 'nullable|in:Yes,No',
        'courseType' => 'nullable|string|max:255',  // Changed to nullable
        'courseName' => 'nullable|string|max:255',  // Changed to nullable
        'deliveryMode' => 'nullable|in:Offline,Online,Hybrid',  // Changed to nullable
        'medium' => 'nullable|in:English,Hindi',  // Changed to nullable
        'board' => 'nullable|in:CBSE,RBSE,ICSE',  // Changed to nullable
        'courseContent' => 'nullable|string|max:255',  // Changed to nullable
        'isRepeater' => 'nullable|in:Yes,No',
        'scholarshipTest' => 'nullable|in:Yes,No',
        'lastBoardPercentage' => 'nullable|numeric|min:0|max:100',
        'competitionExam' => 'nullable|in:Yes,No',
    ]);

    \Log::info('VALIDATION PASSED');

    try {
        $inquiry = Inquiry::findOrFail($id);
        
        \Log::info('INQUIRY FOUND', ['inquiry_id' => $inquiry->_id]);
        
        // Map form fields to database fields - save whatever is provided
        $updateData = [
            'student_name' => $validatedData['name'],
            'father_name' => $validatedData['father'],
            'mother' => $validatedData['mother'] ?? null,
            'dob' => $validatedData['dob'] ?? null,
            'father_contact' => $validatedData['mobileNumber'],
            'father_whatsapp' => $validatedData['fatherWhatsapp'] ?? null,
            'motherContact' => $validatedData['motherContact'] ?? null,
            'student_contact' => $validatedData['studentContact'] ?? null,
            'category' => $validatedData['category'],
            'gender' => $validatedData['gender'],
            'fatherOccupation' => $validatedData['fatherOccupation'] ?? null,
            'fatherGrade' => $validatedData['fatherGrade'] ?? null,
            'motherOccupation' => $validatedData['motherOccupation'] ?? null,
            'state' => $validatedData['state'] ?? null,
            'city' => $validatedData['city'] ?? null,
            'pinCode' => $validatedData['pinCode'] ?? null,
            'address' => $validatedData['address'] ?? null,
            'belongToOtherCity' => $validatedData['belongToOtherCity'] ?? 'No',
            'economicWeakerSection' => $validatedData['economicWeakerSection'] ?? 'No',
            'armyPoliceBackground' => $validatedData['armyPoliceBackground'] ?? 'No',
            'speciallyAbled' => $validatedData['speciallyAbled'] ?? 'No',
            'courseType' => $validatedData['courseType'] ?? null,
            'course_name' => $validatedData['courseName'] ?? null,
            'delivery_mode' => $validatedData['deliveryMode'] ?? null,
            'medium' => $validatedData['medium'] ?? null,
            'board' => $validatedData['board'] ?? null,
            'course_content' => $validatedData['courseContent'] ?? null,
            'isRepeater' => $validatedData['isRepeater'] ?? 'No',
            'scholarshipTest' => $validatedData['scholarshipTest'] ?? 'No',
            'lastBoardPercentage' => $validatedData['lastBoardPercentage'] ?? null,
            'competitionExam' => $validatedData['competitionExam'] ?? 'No',
        ];

        $inquiry->update($updateData);

        \Log::info('INQUIRY UPDATED SUCCESSFULLY', [
            'updated_data' => $updateData
        ]);
        
        // ALWAYS redirect to scholarship details page regardless of form completion
return redirect()->route('inquiries.scholarship.show', ['id' => $id])
            ->with('success', 'Inquiry saved! Please review scholarship details.');

    } catch (\Exception $e) {
        \Log::error('ERROR IN UPDATE: ' . $e->getMessage());
        \Log::error('Stack trace: ' . $e->getTraceAsString());
        
        // Even on error, try to redirect to scholarship page with error message
return redirect()->route('inquiries.scholarship.show', ['id' => $id])
            ->with('error', 'Some data may not have been saved: ' . $e->getMessage());
    }
}
}


