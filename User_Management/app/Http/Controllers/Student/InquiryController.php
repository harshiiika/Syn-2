<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Student\Inquiry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use App\Models\Master\Scholarship;
use App\Models\Master\FeesMaster;
use App\Models\Student\Pending;


class InquiryController extends Controller
{
    /**
     * ✅ SINGLE ONBOARD - Transfer ONE inquiry to pending students
     */
    public function singleOnboard($id)
    {
        try {
            Log::info('=== SINGLE ONBOARD START ===', ['inquiry_id' => $id]);
            
            $inquiry = Inquiry::find($id);
            
            if (!$inquiry) {
                return response()->json([
                    'success' => false,
                    'message' => 'Inquiry not found!'
                ], 404);
            }

            // Check if already transferred
            if (in_array($inquiry->status, ['transferred', 'onboarded', 'converted'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'This inquiry has already been processed!'
                ], 400);
            }

            // Transfer to pending
            $pendingStudent = $this->transferToPending($inquiry);

            return response()->json([
                'success' => true,
                'message' => 'Student transferred to pending list successfully!',
                'pending_student_id' => (string) $pendingStudent->_id
            ]);

        } catch (\Exception $e) {
            Log::error('❌ Single onboard error: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to transfer student: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * ✅ BULK ONBOARD - Transfer multiple inquiries to pending students
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

            $transferredCount = 0;
            $errors = [];

            foreach ($inquiries as $inquiry) {
                try {
                    // Skip already processed
                    if (in_array($inquiry->status, ['transferred', 'onboarded', 'converted'])) {
                        $errors[] = "{$inquiry->student_name} - Already processed";
                        continue;
                    }

                    $this->transferToPending($inquiry);
                    $transferredCount++;
                    
                } catch (\Exception $e) {
                    Log::error("Failed to transfer inquiry {$inquiry->_id}: " . $e->getMessage());
                    $errors[] = "{$inquiry->student_name} - " . $e->getMessage();
                }
            }

            $message = "Successfully transferred {$transferredCount} student(s) to pending list!";
            if (!empty($errors)) {
                $message .= " Errors: " . implode(', ', $errors);
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'transferred_count' => $transferredCount,
                'errors' => $errors
            ]);

        } catch (\Exception $e) {
            Log::error('Bulk transfer error: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to transfer students: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * ✅ HELPER METHOD - Transfer inquiry to pending collection
     */
    private function transferToPending($inquiry)
    {
        Log::info('Transferring inquiry to pending', [
            'inquiry_id' => $inquiry->_id,
            'student_name' => $inquiry->student_name,
        ]);

        // Create pending student record
        $pendingData = [
            // Basic Details
            'name' => $inquiry->student_name,
            'father' => $inquiry->father_name,
            'mother' => $inquiry->mother ?? null,
            'dob' => $inquiry->dob ?? null,
            'mobileNumber' => $inquiry->father_contact,
            'fatherWhatsapp' => $inquiry->father_whatsapp ?? null,
            'motherContact' => $inquiry->motherContact ?? null,
            'studentContact' => $inquiry->student_contact ?? null,
            'category' => $inquiry->category ?? 'GENERAL',
            'gender' => $inquiry->gender ?? null,
            'fatherOccupation' => $inquiry->fatherOccupation ?? null,
            'fatherGrade' => $inquiry->fatherGrade ?? null,
            'motherOccupation' => $inquiry->motherOccupation ?? null,
            
            // Address Details
            'state' => $inquiry->state ?? null,
            'city' => $inquiry->city ?? null,
            'pinCode' => $inquiry->pinCode ?? null,
            'address' => $inquiry->address ?? null,
            'belongToOtherCity' => $inquiry->belongToOtherCity ?? 'No',
            'economicWeakerSection' => $inquiry->economicWeakerSection ?? 'No',
            'armyPoliceBackground' => $inquiry->armyPoliceBackground ?? 'No',
            'speciallyAbled' => $inquiry->speciallyAbled ?? 'No',
            
            // Course Details
            'course_type' => $inquiry->courseType ?? null,
            'courseType' => $inquiry->courseType ?? null,
            'courseName' => $inquiry->course_name ?? null,
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
            
            // Scholarship & Fees Details
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
            
            // Metadata
            'branch' => $inquiry->branch ?? 'Main Branch',
            'session' => session('current_session', '2025-2026'),
            'status' => 'pending',
            'transferred_from_inquiry' => true,
            'inquiry_id' => (string) $inquiry->_id,
            'transferred_at' => now(),
        ];

        $pendingStudent = Pending::create($pendingData);

        Log::info('✅ Pending student created', [
            'pending_id' => $pendingStudent->_id,
            'name' => $pendingStudent->name,
        ]);

        // Update inquiry with history
        $history = $inquiry->history ?? [];
        $history[] = [
            'action' => 'Transferred to Pending Students',
            'user' => auth()->check() ? auth()->user()->name : 'Admin',
            'description' => 'Student transferred to pending students list for form completion',
            'timestamp' => now()->toIso8601String(),
            'changes' => [
                'status' => [
                    'from' => $inquiry->status ?? 'new',
                    'to' => 'transferred'
                ],
                'pending_student_id' => (string) $pendingStudent->_id
            ]
        ];

        $inquiry->update([
            'status' => 'transferred',
            'transferred_to_pending' => true,
            'pending_student_id' => (string) $pendingStudent->_id,
            'history' => $history
        ]);

        Log::info('✅ Inquiry updated with transfer status');

        return $pendingStudent;
    }

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
            
            // Filter out transferred/onboarded inquiries
            $query->whereNotIn('status', ['transferred', 'onboarded', 'converted']);

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

            // Transform MongoDB _id to string
            $inquiries = $inquiries->map(function($inquiry) {
                $inquiry->_id = (string) $inquiry->_id;
                return $inquiry;
            });

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
     * Store a new inquiry
     */
    public function store(Request $request)
    {
        Log::info('Inquiry Store Request:', $request->all());

        $validator = Validator::make($request->all(), [
            'student_name' => 'required|string|max:255',
            'father_name' => 'required|string|max:255',
            'father_contact' => 'required|string|max:20',
            'father_whatsapp' => 'nullable|string|max:20',
            'student_contact' => 'nullable|string|max:20',
            'category' => 'required|string|in:General,OBC,SC,ST',
            'course_name' => 'nullable|string|max:255',
            'delivery_mode' => 'nullable|string|in:Online,Offline,Hybrid',
            'course_content' => 'nullable|string|max:255',
            'branch' => 'required|string|max:255',
            'state' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'ews' => 'required|string|in:Yes,No',
            'defense' => 'required|string|in:Yes,No',
            'specially_abled' => 'required|string|in:Yes,No',
            'status' => 'nullable|string|in:Pending,Active,Closed,Converted',
            'remarks' => 'nullable|string',
            'follow_up_date' => 'nullable|date',
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
            
            // Auto-calculate fees if course provided
            if (!empty($data['course_name'])) {
                $feesData = $this->calculateDefaultFees($data['course_name']);
                $data = array_merge($data, $feesData);
            }
            
            // Add initial history
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
     * Calculate default fees for a course
     */
    private function calculateDefaultFees($courseName)
    {
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
        $totalFees = $totalFeeBeforeDiscount;
        $gstAmount = ($totalFees * 18) / 100;
        $totalFeesInclusiveTax = $totalFees + $gstAmount;

        return [
            'eligible_for_scholarship' => 'No',
            'scholarship_name' => 'N/A',
            'total_fee_before_discount' => $totalFeeBeforeDiscount,
            'discretionary_discount' => 'No',
            'discretionary_discount_type' => null,
            'discretionary_discount_value' => null,
            'discretionary_discount_reason' => null,
            'discount_percentage' => 0,
            'discounted_fee' => $totalFeeBeforeDiscount,
            'fees_breakup' => 'Class room course (with test series & study material)',
            'total_fees' => $totalFees,
            'gst_amount' => $gstAmount,
            'total_fees_inclusive_tax' => $totalFeesInclusiveTax,
            'single_installment_amount' => $totalFeesInclusiveTax,
            'installment_1' => round($totalFeesInclusiveTax * 0.40, 2),
            'installment_2' => round($totalFeesInclusiveTax * 0.30, 2),
            'installment_3' => round($totalFeesInclusiveTax * 0.30, 2),
            'fees_calculated_at' => now(),
        ];
    }

    /**
     * Edit inquiry
     */
    public function edit($id)
    {
        try {
            $inquiry = Inquiry::findOrFail($id);
            
            Log::info('Edit inquiry data:', [
                'id' => $id,
                'student_name' => $inquiry->student_name,
                'father_name' => $inquiry->father_name,
                'all_data' => $inquiry->toArray()
            ]);
            
            return view('inquiries.edit', compact('inquiry'));
        } catch (\Exception $e) {
            Log::error('Edit page error: ' . $e->getMessage());
            return redirect()->route('inquiries.index')->with('error', 'Unable to load inquiry for editing.');
        }
    }

    /**
     * Update inquiry
     */
    public function update(Request $request, $id)
    {
        Log::info('UPDATE METHOD CALLED', [
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

        Log::info('VALIDATION PASSED');

        try {
            $inquiry = Inquiry::findOrFail($id);
            
            Log::info('INQUIRY FOUND', ['inquiry_id' => $inquiry->_id]);
            
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

            // Track changes
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

            // Recalculate fees if course changed
            if ($courseChanged && !empty($validatedData['courseName'])) {
                Log::info('Course changed, recalculating fees', [
                    'old_course' => $inquiry->course_name,
                    'new_course' => $validatedData['courseName']
                ]);
                
                $feesData = $this->calculateDefaultFees($validatedData['courseName']);
                $updateData = array_merge($updateData, $feesData);
                
                $changes['fees_recalculated'] = 'Due to course change';
            }

            // Add history entry
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

            Log::info('INQUIRY UPDATED SUCCESSFULLY WITH HISTORY', [
                'updated_fields' => array_keys($changes),
                'history_count' => count($history)
            ]);
            
            // Redirect to scholarship page
            return redirect()->route('inquiries.scholarship.show', $id)
                ->with('success', 'Inquiry saved! Please review scholarship details.');

        } catch (\Exception $e) {
            Log::error('ERROR IN UPDATE: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return redirect()->route('inquiries.edit', $id)
                ->with('error', 'Error updating inquiry: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show scholarship details page
     */
    public function showScholarshipDetails($id)
    {
        try {
            Log::info('=== SCHOLARSHIP PAGE LOADING ===', ['inquiry_id' => $id]);
            
            $inquiry = Inquiry::findOrFail($id);
            
            Log::info('Inquiry found:', [
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

            Log::info('Base fee calculated:', [
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
                
                Log::info('Student is eligible for scholarship');
                $eligibleForScholarship = true;

                // Priority 1: Scholarship Test
                if ($inquiry->scholarshipTest === 'Yes') {
                    Log::info('Checking Test Based scholarship');
                    $scholarship = Scholarship::where('scholarship_type', 'Test Based')
                        ->where('is_active', true)
                        ->orderBy('discount_percentage', 'desc')
                        ->first();
                }
                
                // Priority 2: Board Percentage
                if (!$scholarship && $inquiry->lastBoardPercentage >= 75) {
                    $percentage = $inquiry->lastBoardPercentage;
                    Log::info('Checking Board scholarship for percentage: ' . $percentage);
                    
                    $scholarship = Scholarship::where('scholarship_type', 'Board Examination Scholarship')
                        ->where('is_active', true)
                        ->where('min_percentage', '<=', $percentage)
                        ->where('max_percentage', '>=', $percentage)
                        ->orderBy('discount_percentage', 'desc')
                        ->first();
                }
                
                // Priority 3: Competition Exam
                if (!$scholarship && $inquiry->competitionExam === 'Yes') {
                    Log::info('Checking Competition Exam scholarship');
                    $scholarship = Scholarship::where('scholarship_type', 'Competition Exam Scholarship')
                        ->where('is_active', true)
                        ->orderBy('discount_percentage', 'desc')
                        ->first();
                }

                // Calculate discount if scholarship found
                if ($scholarship) {
                    Log::info('Scholarship found:', [
                        'name' => $scholarship->scholarship_name,
                        'discount' => $scholarship->discount_percentage
                    ]);
                    
                    $discountPercentage = $scholarship->discount_percentage ?? 0;
                    $discountAmount = ($totalFeeBeforeDiscount * $discountPercentage) / 100;
                    $scholarshipDiscountedFees = $totalFeeBeforeDiscount - $discountAmount;
                } else {
                    Log::warning('No matching scholarship found');
                }
            } else {
                Log::info('Student is NOT eligible for scholarship');
            }

            // Final fees
            $finalFees = $scholarshipDiscountedFees;
            
            // Create alias for backward compatibility
            $discountedFees = $scholarshipDiscountedFees;

            Log::info('=== SCHOLARSHIP PAGE DATA READY ===', [
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
            Log::error('=== SCHOLARSHIP PAGE ERROR ===');
            Log::error('Error message: ' . $e->getMessage());
            Log::error('File: ' . $e->getFile() . ':' . $e->getLine());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return redirect()->route('inquiries.edit', $id)
                ->with('error', 'Error loading scholarship details: ' . $e->getMessage());
        }
    }

    /**
     * Update scholarship details
     */
    public function updateScholarshipDetails(Request $request, $id)
    {
        try {
            Log::info('=== SCHOLARSHIP UPDATE START ===', [
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

            // Track changes for history
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

            // Add history entry
            $history = $inquiry->history ?? [];
            $history[] = [
                'action' => 'Scholarship Details Updated',
                'user' => auth()->check() ? auth()->user()->name : 'Admin',
                'description' => 'Scholarship and fees details updated for student ' . $inquiry->student_name,
                'timestamp' => now()->toIso8601String(),
                'changes' => $changes
            ];
            
            $updateData['history'] = $history;
            
            // Update the inquiry
            $inquiry->update($updateData);
            
            // Verify the save
            $inquiry->refresh();
            
            Log::info('✅ Scholarship data saved successfully', [
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
            Log::error('=== SCHOLARSHIP UPDATE ERROR ===', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()
                ->with('error', '❌ Error saving scholarship details: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show fees and batches page
     */
    public function showFeesBatchesDetails($id)
    {
        try {
            Log::info('=== FEES & BATCHES PAGE LOADING ===', ['inquiry_id' => $id]);
            
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
            
            Log::info('Fees data loaded', $feesData);
            
            return view('inquiries.fees-batches-details', compact('inquiry', 'feesData'));
            
        } catch (\Exception $e) {
            Log::error('=== FEES & BATCHES PAGE ERROR ===');
            Log::error('Error: ' . $e->getMessage());
            
            return redirect()->route('inquiries.edit', $id)
                ->with('error', 'Error loading fees details: ' . $e->getMessage());
        }
    }

    /**
     * Update fees and batches details
     */
    public function updateFeesBatches(Request $request, $id)
    {
        try {
            Log::info('=== FEES & BATCHES UPDATE START ===', [
                'inquiry_id' => $id,
                'request_data' => $request->all()
            ]);
            
            $inquiry = Inquiry::findOrFail($id);
            
            $validated = $request->validate([
                'batchName' => 'nullable|string|max:255',
                'batch_id' => 'nullable|string',
            ]);

            // Store old values
            $oldBatchName = $inquiry->batchName;
            
            // Update batch information
            $updateData = [
                'batchName' => $validated['batchName'] ?? null,
                'batch_id' => $validated['batch_id'] ?? null,
            ];

            // Add history
            $history = $inquiry->history ?? [];
            $history[] = [
                'action' => 'Batch Details Updated',
                'user' => auth()->check() ? auth()->user()->name : 'Admin',
                'description' => 'Batch information updated',
                'timestamp' => now()->toIso8601String(),
                'changes' => [
                    'batchName' => [
                        'from' => $oldBatchName,
                        'to' => $validated['batchName'] ?? null
                    ]
                ]
            ];
            
            $updateData['history'] = $history;
            $inquiry->update($updateData);
            
            Log::info('✅ Batch details updated successfully');
            
            return redirect()->route('inquiries.view', $id)
                ->with('success', 'Batch details updated successfully!');
                
        } catch (\Exception $e) {
            Log::error('=== FEES & BATCHES UPDATE ERROR ===', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()
                ->with('error', 'Error updating batch details: ' . $e->getMessage())
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
            
            Log::info('View inquiry data:', $inquiry->toArray());
            
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
            Log::error('Failed to load inquiry details: ' . $e->getMessage());
            return redirect()->route('inquiries.index')
                ->with('error', 'Unable to load inquiry details.');
        }
    }

    /**
     * Show single inquiry (API)
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
            
            Log::info('History retrieved for inquiry', [
                'inquiry_id' => $id,
                'history_count' => count($history)
            ]);
            
            return response()->json([
                'success' => true,
                'data' => $history
            ]);
            
        } catch (\Exception $e) {
            Log::error('Get history error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch history: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get paginated data (alternative method)
     */
    public function getData(Request $request)
    {
        try {
            $perPage = $request->get('per_page', 10);
            $search = $request->get('search', '');
            
            $query = Inquiry::query();
            
            // Filter out transferred inquiries
            $query->whereNotIn('status', ['transferred', 'onboarded', 'converted']);
            
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
     * Handle file upload
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
            // TODO: Implement file upload logic
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
     * Show onboard form (redirects to pending edit)
     */
    public function showOnboardForm($inquiryId)
    {
        try {
            $inquiry = Inquiry::findOrFail($inquiryId);
            
            // Check if already has pending student
            if ($inquiry->pending_student_id) {
                return redirect()->route('student.pending.edit', $inquiry->pending_student_id)
                    ->with('info', 'This inquiry already has a pending student. Edit details here.');
            }
            
            // Transfer to pending first
            $response = $this->singleOnboard($inquiryId);
            $responseData = json_decode($response->getContent(), true);
            
            if ($responseData['success']) {
                return redirect()->route('student.pending.edit', $responseData['pending_student_id'])
                    ->with('success', 'Student transferred to pending list. Complete the form.');
            } else {
                return redirect()->route('inquiries.index')
                    ->with('error', $responseData['message']);
            }
                
        } catch (\Exception $e) {
            Log::error('Show onboard form error: ' . $e->getMessage());
            return redirect()->route('inquiries.index')
                ->with('error', 'Error: ' . $e->getMessage());
        }
    }
}