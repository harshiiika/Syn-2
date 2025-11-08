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

    /**
     * Edit inquiry
     */
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
     * Store a new inquiry with initial history entry
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
            
            // ⭐ AUTO-CALCULATE FEES BASED ON COURSE
            if (!empty($data['course_name'])) {
                $feesData = $this->calculateDefaultFees($data['course_name']);
                $data = array_merge($data, $feesData);
            }
            
            // ⭐ ADD INITIAL HISTORY ENTRY
            $data['history'] = [[
                'action' => 'Inquiry Created',
                'user' => auth()->check() ? auth()->user()->name : 'Admin',
                'description' => 'New inquiry created for student ' . $data['student_name'],
                'timestamp' => now()->toIso8601String(),
                'changes' => [
                    'student_name' => $data['student_name'],
                    'father_name' => $data['father_name'],
                    'course_name' => $data['course_name'] ?? 'Not assigned',
                    'status' => $data['status']
                ]
            ]];
            
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
     * Calculate default fees for a course (without any discounts)
     */
    private function calculateDefaultFees($courseName)
    {
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

        $totalFeeBeforeDiscount = $courseFees[$courseName] ?? 88000;

        // No scholarship eligibility check here - just base fees
        $eligibleForScholarship = 'No';
        $scholarshipName = 'N/A';
        $discountPercentage = 0;
        $discountedFee = $totalFeeBeforeDiscount;
        $discretionaryDiscount = 'No';

        // Calculate final fees with GST
        $totalFees = $discountedFee;
        $gstAmount = ($totalFees * 18) / 100;
        $totalFeesInclusiveTax = $totalFees + $gstAmount;

        // Calculate installments
        $singleInstallmentAmount = $totalFeesInclusiveTax;
        $installment1 = round($totalFeesInclusiveTax * 0.40, 2);
        $installment2 = round($totalFeesInclusiveTax * 0.30, 2);
        $installment3 = round($totalFeesInclusiveTax * 0.30, 2);

        return [
            'eligible_for_scholarship' => $eligibleForScholarship,
            'scholarship_name' => $scholarshipName,
            'total_fee_before_discount' => $totalFeeBeforeDiscount,
            'discretionary_discount' => $discretionaryDiscount,
            'discretionary_discount_type' => null,
            'discretionary_discount_value' => null,
            'discretionary_discount_reason' => null,
            'discount_percentage' => $discountPercentage,
            'discounted_fee' => $discountedFee,
            'fees_breakup' => 'Class room course (with test series & study material)',
            'total_fees' => $totalFees,
            'gst_amount' => $gstAmount,
            'total_fees_inclusive_tax' => $totalFeesInclusiveTax,
            'single_installment_amount' => $singleInstallmentAmount,
            'installment_1' => $installment1,
            'installment_2' => $installment2,
            'installment_3' => $installment3,
            'fees_calculated_at' => now(),
        ];
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

    /**
     * Bulk onboard inquiries to students
     */
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
                //   DEBUG: Log inquiry data before creating student
                \Log::info('=== INQUIRY DATA BEFORE ONBOARD ===', [
                    'inquiry_id' => $inquiry->_id,
                    'student_name' => $inquiry->student_name,
                    'eligible_for_scholarship' => $inquiry->eligible_for_scholarship ?? 'NOT SET',
                    'scholarship_name' => $inquiry->scholarship_name ?? 'NOT SET',
                    'total_fee_before_discount' => $inquiry->total_fee_before_discount ?? 'NOT SET',
                    'total_fees' => $inquiry->total_fees ?? 'NOT SET',
                    'gst_amount' => $inquiry->gst_amount ?? 'NOT SET',
                    'total_fees_inclusive_tax' => $inquiry->total_fees_inclusive_tax ?? 'NOT SET',
                    'installment_1' => $inquiry->installment_1 ?? 'NOT SET',
                ]);

                //   Create student record in 'students' collection with ALL fields
                $student = \App\Models\Student\Student::create([
                    // Basic Details
                    'name' => $inquiry->student_name,
                    'father' => $inquiry->father_name,
                    'mother' => $inquiry->mother,
                    'dob' => $inquiry->dob,
                    'mobileNumber' => $inquiry->father_contact,
                    'fatherWhatsapp' => $inquiry->father_whatsapp ?? null,
                    'motherContact' => $inquiry->motherContact ?? null,
                    'studentContact' => $inquiry->student_contact ?? null,
                    'category' => $inquiry->category ?? 'General',
                    'gender' => $inquiry->gender ?? null,
                    'fatherOccupation' => $inquiry->fatherOccupation ?? null,
                    'fatherGrade' => $inquiry->fatherGrade ?? null,
                    'motherOccupation' => $inquiry->motherOccupation ?? null,
                    
                    // Address Details
                    'state' => $inquiry->state,
                    'city' => $inquiry->city,
                    'pinCode' => $inquiry->pinCode ?? null,
                    'address' => $inquiry->address,
                    'belongToOtherCity' => $inquiry->belongToOtherCity ?? 'No',
                    'economicWeakerSection' => $inquiry->economicWeakerSection ?? 'No',
                    'armyPoliceBackground' => $inquiry->armyPoliceBackground ?? 'No',
                    'speciallyAbled' => $inquiry->speciallyAbled ?? 'No',
                    
                    // Course Details
                    'course_type' => $inquiry->courseType ?? null,
                    'courseName' => $inquiry->course_name ?? 'Not Assigned',
                    'deliveryMode' => $inquiry->delivery_mode ?? 'Offline',
                    'medium' => $inquiry->medium ?? null,
                    'board' => $inquiry->board ?? null,
                    'courseContent' => $inquiry->course_content ?? 'Class Room Course',
                    
                    // Academic Details
                    'previousClass' => $inquiry->previousClass ?? null,
                    'previousMedium' => $inquiry->previousMedium ?? null,
                    'schoolName' => $inquiry->schoolName ?? null,
                    'previousBoard' => $inquiry->previousBoard ?? null,
                    'passingYear' => $inquiry->passingYear ?? null,
                    'percentage' => $inquiry->percentage ?? null,
                    
                    // Scholarship Eligibility
                    'isRepeater' => $inquiry->isRepeater ?? 'No',
                    'scholarshipTest' => $inquiry->scholarshipTest ?? 'No',
                    'lastBoardPercentage' => $inquiry->lastBoardPercentage ?? null,
                    'competitionExam' => $inquiry->competitionExam ?? 'No',
                    
                    // Batch Details
                    'batchName' => $inquiry->batchName ?? null,
                    
                    //   SCHOLARSHIP & FEES DETAILS (all fields from inquiry)
                    'eligible_for_scholarship' => $inquiry->eligible_for_scholarship ?? 'No',
                    'scholarship_name' => $inquiry->scholarship_name ?? 'N/A',
                    'total_fee_before_discount' => $inquiry->total_fee_before_discount ?? 0,
                    'discretionary_discount' => $inquiry->discretionary_discount ?? 'No',
                    'discretionary_discount_type' => $inquiry->discretionary_discount_type ?? null,
                    'discretionary_discount_value' => $inquiry->discretionary_discount_value ?? null,
                    'discretionary_discount_reason' => $inquiry->discretionary_discount_reason ?? null,
                    'discount_percentage' => $inquiry->discount_percentage ?? 0,
                    'discounted_fee' => $inquiry->discounted_fee ?? 0,
                    'fees_breakup' => $inquiry->fees_breakup ?? 'Class room course (with test series & study material)',
                    'total_fees' => $inquiry->total_fees ?? 0,
                    'gst_amount' => $inquiry->gst_amount ?? 0,
                    'total_fees_inclusive_tax' => $inquiry->total_fees_inclusive_tax ?? 0,
                    'single_installment_amount' => $inquiry->single_installment_amount ?? 0,
                    'installment_1' => $inquiry->installment_1 ?? 0,
                    'installment_2' => $inquiry->installment_2 ?? 0,
                    'installment_3' => $inquiry->installment_3 ?? 0,
                    'fees_calculated_at' => $inquiry->fees_calculated_at ?? null,
                    
                    //   METADATA & STATUS FIELDS
                    'email' => $inquiry->student_name . '@temp.com',
                    'branch' => $inquiry->branch ?? 'Main Branch',
                    'session' => session('current_session', '2025-2026'),
                    
                    //   PAYMENT STATUS (for pending fees tracking) - FIXED: No duplicate total_fees
                    'paid_fees' => 0,
                    'remaining_fees' => $inquiry->total_fees_inclusive_tax ?? 0,
                    'status' => 'pending_fees',
                    'fee_status' => 'pending',
                    'admission_date' => now(),
                ]);

                \Log::info('✅ Student created with scholarship data:', [
                    'id' => $student->_id,
                    'name' => $student->name,
                    'status' => $student->status,
                    'eligible_for_scholarship' => $student->eligible_for_scholarship,
                    'scholarship_name' => $student->scholarship_name,
                    'total_fee_before_discount' => $student->total_fee_before_discount,
                    'discount_percentage' => $student->discount_percentage,
                    'total_fees' => $student->total_fees,
                    'gst_amount' => $student->gst_amount,
                    'total_fees_inclusive_tax' => $student->total_fees_inclusive_tax,
                    'remaining_fees' => $student->remaining_fees
                ]);

                // ⭐ ADD ONBOARD HISTORY ENTRY
                $history = $inquiry->history ?? [];
                $history[] = [
                    'action' => 'Student Onboarded',
                    'user' => auth()->check() ? auth()->user()->name : 'Admin',
                    'description' => 'Student ' . $inquiry->student_name . ' successfully onboarded to student management',
                    'timestamp' => now()->toIso8601String(),
                    'changes' => [
                        'status' => [
                            'from' => $inquiry->status,
                            'to' => 'onboarded'
                        ],
                        'student_id' => (string) $student->_id
                    ]
                ];

                //   Mark inquiry as onboarded with history
                $inquiry->update([
                    'status' => 'onboarded',
                    'history' => $history
                ]);
                
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
                'email' => $inquiry->student_name . '@temp.com',
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
                return redirect()->route('student.student.pending')
                    ->with('success', 'Student onboarded! Pending fees: ₹' . number_format($remainingFees, 2));
            } else {
                return redirect()->route('student.student.pending')
                    ->with('success', 'Student onboarded successfully with full payment!');
            }

        } catch (\Exception $e) {
            \Log::error('Onboard error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to onboard student: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * View inquiry details
     */
    public function view($id)
    {
        try {
            $inquiry = Inquiry::findOrFail($id);
            
            \Log::info('View inquiry data:', $inquiry->toArray());
            
            // Prepare fees and scholarship data
            $feesData = [
                'eligible_for_scholarship' => $inquiry->eligible_for_scholarship ?? 'No',
                'scholarship_name' => $inquiry->scholarship_name ?? 'N/A',
                'total_fee_before_discount' => $inquiry->total_fee_before_discount ?? 0,
                'discretionary_discount' => $inquiry->discretionary_discount ?? 'No',
                'discount_percentage' => $inquiry->discount_percentage ?? 0,
                'discounted_fee' => $inquiry->discounted_fee ?? 0,
                'fees_breakup' => $inquiry->fees_breakup ?? 'Class room course (with test series & study material)',
                'total_fees' => $inquiry->total_fees ?? 0,
                'gst_amount' => $inquiry->gst_amount ?? 0,
                'total_fees_inclusive_tax' => $inquiry->total_fees_inclusive_tax ?? 0,
                'single_installment_amount' => $inquiry->single_installment_amount ?? 0,
                'installment_1' => $inquiry->installment_1 ?? 0,
                'installment_2' => $inquiry->installment_2 ?? 0,
                'installment_3' => $inquiry->installment_3 ?? 0,
            ];
            
            return view('inquiries.view', compact('inquiry', 'feesData'));
        } catch (\Exception $e) {
            \Log::error('Failed to load inquiry details: ' . $e->getMessage());
            return redirect()->route('inquiries.index')
                ->with('error', 'Unable to load inquiry details.');
        }
    }

    /**
     * Show scholarship details page after updating inquiry
     */
    public function showScholarshipDetails($id)
    {
        try {
            \Log::info('=== SCHOLARSHIP PAGE LOADING ===', ['inquiry_id' => $id]);
            
            $inquiry = Inquiry::findOrFail($id);
            
            \Log::info('Inquiry found:', [
                'id' => $inquiry->_id,
                'student_name' => $inquiry->student_name,
                'course_name' => $inquiry->course_name,
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

            \Log::info('Base fee calculated:', [
                'course_name' => $courseName,
                'total_fee' => $totalFeeBeforeDiscount
            ]);

            // Initialize scholarship variables
            $eligibleForScholarship = false;
            $scholarship = null;
            $discountPercentage = 0;
            $scholarshipDiscountedFees = $totalFeeBeforeDiscount;

            // Check eligibility
            if (($inquiry->scholarshipTest === 'Yes') || 
                ($inquiry->lastBoardPercentage && $inquiry->lastBoardPercentage >= 75) ||
                ($inquiry->competitionExam === 'Yes')) {
                
                \Log::info('Student is eligible for scholarship');
                $eligibleForScholarship = true;

                // Priority 1: Scholarship Test
                if ($inquiry->scholarshipTest === 'Yes') {
                    \Log::info('Checking Test Based scholarship');
                    $scholarship = Scholarship::where('scholarship_type', 'Test Based')
                        ->where('is_active', true)
                        ->orderBy('discount_percentage', 'desc')
                        ->first();
                }
                
                // Priority 2: Board Percentage
                if (!$scholarship && $inquiry->lastBoardPercentage >= 75) {
                    $percentage = $inquiry->lastBoardPercentage;
                    \Log::info('Checking Board scholarship for percentage: ' . $percentage);
                    
                    $scholarship = Scholarship::where('scholarship_type', 'Board Examination Scholarship')
                        ->where('is_active', true)
                        ->where('min_percentage', '<=', $percentage)
                        ->where('max_percentage', '>=', $percentage)
                        ->orderBy('discount_percentage', 'desc')
                        ->first();
                }
                
                // Priority 3: Competition Exam
                if (!$scholarship && $inquiry->competitionExam === 'Yes') {
                    \Log::info('Checking Competition Exam scholarship');
                    $scholarship = Scholarship::where('scholarship_type', 'Competition Exam Scholarship')
                        ->where('is_active', true)
                        ->orderBy('discount_percentage', 'desc')
                        ->first();
                }

                // Calculate discount if scholarship found
                if ($scholarship) {
                    \Log::info('Scholarship found:', [
                        'name' => $scholarship->scholarship_name,
                        'discount' => $scholarship->discount_percentage
                    ]);
                    
                    $discountPercentage = $scholarship->discount_percentage ?? 0;
                    $discountAmount = ($totalFeeBeforeDiscount * $discountPercentage) / 100;
                    $scholarshipDiscountedFees = $totalFeeBeforeDiscount - $discountAmount;
                } else {
                    \Log::warning('No matching scholarship found');
                }
            } else {
                \Log::info('Student is NOT eligible for scholarship');
            }

            // Final fees
            $finalFees = $scholarshipDiscountedFees;
            
            // Create alias for backward compatibility
            $discountedFees = $scholarshipDiscountedFees;

            \Log::info('=== SCHOLARSHIP PAGE DATA READY ===', [
                'eligible' => $eligibleForScholarship,
                'discount_percentage' => $discountPercentage,
                'final_fees' => $finalFees
            ]);

            return view('inquiries.scholarship-details', compact(
                'inquiry',
                'eligibleForScholarship',
                'scholarship',
                'totalFeeBeforeDiscount',
                'discountPercentage',
                'scholarshipDiscountedFees',
                'discountedFees',
                'finalFees'
            ));

        } catch (\Exception $e) {
            \Log::error('=== SCHOLARSHIP PAGE ERROR ===');
            \Log::error('Error message: ' . $e->getMessage());
            \Log::error('File: ' . $e->getFile() . ':' . $e->getLine());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return redirect()->route('inquiries.edit', $id)
                ->with('error', 'Error loading scholarship details: ' . $e->getMessage());
        }
    }

    /**
     * Update scholarship and fees information with history tracking
     */
    public function updateScholarshipDetails(Request $request, $id)
    {
        try {
            \Log::info('=== SCHOLARSHIP UPDATE START ===', [
                'inquiry_id' => $id,
                'request_data' => $request->all()
            ]);
            
            $inquiry = Inquiry::findOrFail($id);
            
            // Store old values for history tracking
            $oldValues = [
                'eligible_for_scholarship' => $inquiry->eligible_for_scholarship ?? 'No',
                'scholarship_name' => $inquiry->scholarship_name ?? 'N/A',
                'total_fee_before_discount' => $inquiry->total_fee_before_discount ?? 0,
                'discount_percentage' => $inquiry->discount_percentage ?? 0,
                'discretionary_discount' => $inquiry->discretionary_discount ?? 'No',
                'total_fees' => $inquiry->total_fees ?? 0,
            ];
            
            // Validate the request
            $validated = $request->validate([
                'total_fee_before_discount' => 'required|numeric',
                'scholarship_discount_percentage' => 'required|numeric|min:0|max:100',
                'scholarship_discounted_fees' => 'required|numeric',
                'final_fees' => 'required|numeric',
                'add_discretionary_discount' => 'required|in:Yes,No',
                'discretionary_discount_type' => 'nullable|in:percentage,fixed',
                'discretionary_discount_value' => 'nullable|numeric|min:0',
                'discretionary_discount_reason' => 'nullable|string',
            ]);

            // Determine scholarship eligibility and name
            $eligibleForScholarship = 'No';
            $scholarshipName = 'N/A';
            
            if (($inquiry->scholarshipTest === 'Yes') || 
                ($inquiry->lastBoardPercentage && $inquiry->lastBoardPercentage >= 75) ||
                ($inquiry->competitionExam === 'Yes')) {
                
                $eligibleForScholarship = 'Yes';
                
                $scholarship = null;
                
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
                
                if ($scholarship) {
                    $scholarshipName = $scholarship->scholarship_name;
                }
            }

            // Prepare update data
            $updateData = [
                'eligible_for_scholarship' => $eligibleForScholarship,
                'scholarship_name' => $scholarshipName,
                'total_fee_before_discount' => floatval($validated['total_fee_before_discount']),
                'discount_percentage' => floatval($validated['scholarship_discount_percentage']),
                'discounted_fee' => floatval($validated['scholarship_discounted_fees']),
            ];

            // Handle discretionary discount
            if ($validated['add_discretionary_discount'] === 'Yes') {
                $updateData['discretionary_discount'] = 'Yes';
                $updateData['discretionary_discount_type'] = $request->discretionary_discount_type;
                $updateData['discretionary_discount_value'] = floatval($request->discretionary_discount_value ?? 0);
                $updateData['discretionary_discount_reason'] = $request->discretionary_discount_reason;
            } else {
                $updateData['discretionary_discount'] = 'No';
                $updateData['discretionary_discount_type'] = null;
                $updateData['discretionary_discount_value'] = null;
                $updateData['discretionary_discount_reason'] = null;
            }
            
            // Calculate fees breakdown
            $finalFees = floatval($validated['final_fees']);
            $gstAmount = ($finalFees * 18) / 100;
            $totalFeesInclusiveTax = $finalFees + $gstAmount;
            
            // Installments
            $installment1 = round($totalFeesInclusiveTax * 0.40, 2);
            $installment2 = round($totalFeesInclusiveTax * 0.30, 2);
            $installment3 = round($totalFeesInclusiveTax * 0.30, 2);
            
            // Add fees data
            $updateData['fees_breakup'] = 'Class room course (with test series & study material)';
            $updateData['total_fees'] = $finalFees;
            $updateData['gst_amount'] = $gstAmount;
            $updateData['total_fees_inclusive_tax'] = $totalFeesInclusiveTax;
            $updateData['single_installment_amount'] = $totalFeesInclusiveTax;
            $updateData['installment_1'] = $installment1;
            $updateData['installment_2'] = $installment2;
            $updateData['installment_3'] = $installment3;
            $updateData['fees_calculated_at'] = now();

            // ⭐ TRACK CHANGES FOR HISTORY
            $changes = [];
            if ($oldValues['eligible_for_scholarship'] !== $eligibleForScholarship) {
                $changes['eligible_for_scholarship'] = [
                    'from' => $oldValues['eligible_for_scholarship'],
                    'to' => $eligibleForScholarship
                ];
            }
            if ($oldValues['scholarship_name'] !== $scholarshipName) {
                $changes['scholarship_name'] = [
                    'from' => $oldValues['scholarship_name'],
                    'to' => $scholarshipName
                ];
            }
            if ($oldValues['discount_percentage'] != $validated['scholarship_discount_percentage']) {
                $changes['discount_percentage'] = [
                    'from' => $oldValues['discount_percentage'] . '%',
                    'to' => $validated['scholarship_discount_percentage'] . '%'
                ];
            }
            if ($oldValues['discretionary_discount'] !== $validated['add_discretionary_discount']) {
                $changes['discretionary_discount'] = [
                    'from' => $oldValues['discretionary_discount'],
                    'to' => $validated['add_discretionary_discount']
                ];
            }
            if ($oldValues['total_fees'] != $finalFees) {
                $changes['total_fees'] = [
                    'from' => '₹' . number_format($oldValues['total_fees'], 2),
                    'to' => '₹' . number_format($finalFees, 2)
                ];
            }

            // ⭐ ADD HISTORY ENTRY
            $history = $inquiry->history ?? [];
            $history[] = [
                'action' => 'Scholarship Details Updated',
                'user' => auth()->check() ? auth()->user()->name : 'Admin',
                'description' => 'Scholarship and fees details updated for student ' . $inquiry->student_name,
                'timestamp' => now()->toIso8601String(),
                'changes' => $changes
            ];
            
            $updateData['history'] = $history;
            
            //   CRITICAL: Update the inquiry
            $inquiry->update($updateData);
            
            //   Verify the save
            $inquiry->refresh();
            
            \Log::info('✅ Scholarship data saved successfully', [
                'inquiry_id' => $id,
                'eligible_for_scholarship' => $inquiry->eligible_for_scholarship,
                'scholarship_name' => $inquiry->scholarship_name,
                'discretionary_discount' => $inquiry->discretionary_discount,
                'discretionary_discount_value' => $inquiry->discretionary_discount_value,
                'final_fees' => $finalFees,
                'total_with_tax' => $totalFeesInclusiveTax,
                'history_count' => count($history)
            ]);
            
            return redirect()->route('inquiries.fees-batches.show', $id)
                ->with('success', '✅ Scholarship details saved successfully!');
                
        } catch (\Exception $e) {
            \Log::error('=== SCHOLARSHIP UPDATE ERROR ===', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()
                ->with('error', '❌ Error saving scholarship details: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Update inquiry with history tracking
     */
    public function update(Request $request, $id)
    {
        \Log::info('UPDATE METHOD CALLED', [
            'id' => $id,
            'all_data' => $request->all()
        ]);

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
            'courseType' => 'nullable|string|max:255',
            'courseName' => 'nullable|string|max:255',
            'deliveryMode' => 'nullable|in:Offline,Online,Hybrid',
            'medium' => 'nullable|in:English,Hindi',
            'board' => 'nullable|in:CBSE,RBSE,ICSE',
            'courseContent' => 'nullable|string|max:255',
            'isRepeater' => 'nullable|in:Yes,No',
            'scholarshipTest' => 'nullable|in:Yes,No',
            'lastBoardPercentage' => 'nullable|numeric|min:0|max:100',
            'competitionExam' => 'nullable|in:Yes,No',
        ]);

        \Log::info('VALIDATION PASSED');

        try {
            $inquiry = Inquiry::findOrFail($id);
            
            \Log::info('INQUIRY FOUND', ['inquiry_id' => $inquiry->_id]);
            
            // Store old data for change tracking
            $oldData = $inquiry->toArray();
            
            // Check if course changed
            $courseChanged = ($inquiry->course_name !== $validatedData['courseName']);
            
            // Map form fields to database fields
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

            // ⭐ TRACK WHAT CHANGED
            $changes = [];
            foreach ($updateData as $key => $value) {
                $oldValue = $oldData[$key] ?? null;
                if ($oldValue != $value && $value !== null) {
                    $changes[$key] = [
                        'from' => $oldValue,
                        'to' => $value
                    ];
                }
            }

            // ⭐ RECALCULATE FEES IF COURSE CHANGED
            if ($courseChanged && !empty($validatedData['courseName'])) {
                \Log::info('Course changed, recalculating fees', [
                    'old_course' => $inquiry->course_name,
                    'new_course' => $validatedData['courseName']
                ]);
                
                $feesData = $this->calculateDefaultFees($validatedData['courseName']);
                $updateData = array_merge($updateData, $feesData);
                
                $changes['fees_recalculated'] = 'Due to course change';
            }

            // ⭐ ADD HISTORY ENTRY
            $history = $inquiry->history ?? [];
            $history[] = [
                'action' => 'Student Enquiry Updated',
                'user' => auth()->check() ? auth()->user()->name : 'Admin',
                'description' => 'Admin updated the enquiry for student ' . $inquiry->student_name,
                'timestamp' => now()->toIso8601String(),
                'changes' => $changes
            ];
            
            $updateData['history'] = $history;

            $inquiry->update($updateData);

            \Log::info('INQUIRY UPDATED SUCCESSFULLY WITH HISTORY', [
                'updated_fields' => array_keys($changes),
                'history_count' => count($history)
            ]);
            
            // Redirect to scholarship page
            return redirect()->route('inquiries.scholarship.show', $id)
                ->with('success', 'Inquiry saved! Please review scholarship details.');

        } catch (\Exception $e) {
            \Log::error('ERROR IN UPDATE: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return redirect()->route('inquiries.edit', $id)
                ->with('error', 'Error updating inquiry: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Get inquiry history
     */
    public function getHistory($id)
    {
        try {
            $inquiry = Inquiry::find($id);
            
            if (!$inquiry) {
                return response()->json([
                    'success' => false,
                    'message' => 'Inquiry not found'
                ], 404);
            }
            
            // Get history array, newest first
            $history = $inquiry->history ?? [];
            $history = array_reverse($history);
            
            \Log::info('History retrieved for inquiry', [
                'inquiry_id' => $id,
                'history_count' => count($history)
            ]);
            
            return response()->json([
                'success' => true,
                'data' => $history
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Get history error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch history: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get paginated data
     */
    public function getData(Request $request)
    {
        try {
            $perPage = $request->get('per_page', 10);
            $search = $request->get('search', '');
            
            $query = Inquiry::query();
            
            // Add search functionality
            if (!empty($search)) {
                $query->where(function($q) use ($search) {
                    $q->where('student_name', 'like', "%{$search}%")
                      ->orWhere('father_name', 'like', "%{$search}%")
                      ->orWhere('father_contact', 'like', "%{$search}%")
                      ->orWhere('course_name', 'like', "%{$search}%");
                });
            }
            
            $inquiries = $query->paginate($perPage);
            
            return response()->json([
                'success' => true,
                'data' => $inquiries->items(),
                'current_page' => $inquiries->currentPage(),
                'last_page' => $inquiries->lastPage(),
                'per_page' => $inquiries->perPage(),
                'total' => $inquiries->total()
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show fees and batches details page
     */
    public function showFeesBatchesDetails($id)
    {
        try {
            \Log::info('=== FEES & BATCHES PAGE LOADING ===', ['inquiry_id' => $id]);
            
            $inquiry = Inquiry::findOrFail($id);
            
            // Check if fees have been calculated
            if (!$inquiry->total_fees || !$inquiry->fees_calculated_at) {
                return redirect()->route('inquiries.scholarship.show', $id)
                    ->with('error', 'Please complete the scholarship details first.');
            }
            
            // Prepare data for display
            $feesData = [
                'eligible_for_scholarship' => $inquiry->eligible_for_scholarship ?? 'No',
                'scholarship_name' => $inquiry->scholarship_name ?? '-',
                'total_fee_before_discount' => $inquiry->total_fee_before_discount ?? 0,
                'discretionary_discount' => $inquiry->discretionary_discount ?? 'No',
                'discount_percentage' => $inquiry->discount_percentage ?? 0,
                'discounted_fee' => $inquiry->discounted_fee ?? 0,
                'fees_breakup' => $inquiry->fees_breakup ?? 'Class room course (with test series & study material)',
                'total_fees' => $inquiry->total_fees,
                'gst_amount' => $inquiry->gst_amount,
                'total_fees_inclusive_tax' => $inquiry->total_fees_inclusive_tax,
                'single_installment_amount' => $inquiry->single_installment_amount,
                'installment_1' => $inquiry->installment_1,
                'installment_2' => $inquiry->installment_2,
                'installment_3' => $inquiry->installment_3,
            ];
            
            \Log::info('Fees data loaded', $feesData);
            
            return view('inquiries.fees-batches-details', compact('inquiry', 'feesData'));
            
        } catch (\Exception $e) {
            \Log::error('=== FEES & BATCHES PAGE ERROR ===');
            \Log::error('Error: ' . $e->getMessage());
            
            return redirect()->route('inquiries.edit', $id)
                ->with('error', 'Error loading fees details: ' . $e->getMessage());
        }
    }
}