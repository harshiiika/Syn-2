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
use App\Models\Master\Courses;
use Carbon\Carbon;
use App\Models\Master\Batch;


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
            'fees_calculated_at' => $inquiry->fees_calculated_at,
            'discretionary_discount' => $inquiry->discretionary_discount
        ]);
        
        //  
        $feesAlreadyCalculated = !empty($inquiry->fees_calculated_at);
        
        if ($feesAlreadyCalculated) {
            Log::info('  Using SAVED scholarship data (not recalculating)');
            
            // Use saved data directly
            $eligibleForScholarship = ($inquiry->eligible_for_scholarship === 'Yes');
            $scholarship = null;
            $totalFeeBeforeDiscount = $inquiry->total_fee_before_discount ?? 0;
            $discountPercentage = $inquiry->discount_percentage ?? 0;
            
            //  Use discounted_fee for scholarship amount, total_fees for final amount
            $scholarshipDiscountedFees = $inquiry->discounted_fee ?? $totalFeeBeforeDiscount;
            $finalFees = $inquiry->total_fees ?? $scholarshipDiscountedFees;
            $discountedFees = $finalFees; // This should show final fees after ALL discounts
            
            Log::info('  Using saved scholarship data:', [
                'eligible' => $eligibleForScholarship,
                'discount_percentage' => $discountPercentage,
                'total_fee_before_discount' => $totalFeeBeforeDiscount,
                'scholarship_discounted_fees' => $scholarshipDiscountedFees,
                'final_fees_after_all_discounts' => $finalFees,
                'has_discretionary' => $inquiry->discretionary_discount === 'Yes'
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
        }
        
    
        
        Log::info('Calculating NEW scholarship data (first time)');
        
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

        // Final fees (initially same as scholarship discounted fees)
        $finalFees = $scholarshipDiscountedFees;
        
        // Create alias for backward compatibility
        $discountedFees = $scholarshipDiscountedFees;

        Log::info('=== NEW SCHOLARSHIP PAGE DATA READY ===', [
            'eligible' => $eligibleForScholarship,
            'discount_percentage' => $discountPercentage,
            'scholarship_discounted_fees' => $scholarshipDiscountedFees,
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
            
            Log::info('  Batch details updated successfully');
            
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
            
            // If no history, return empty array (not an error)
            if (empty($history)) {
                return response()->json([
                    'success' => true,
                    'data' => []
                ]);
            }
            
            // Sort by timestamp (newest first)
            usort($history, function($a, $b) {
                $timeA = strtotime($a['timestamp'] ?? '');
                $timeB = strtotime($b['timestamp'] ?? '');
                return $timeB - $timeA;
            });
            
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
        $data['status'] = 'Pending';
        
        // Auto-calculate fees if course provided
        if (!empty($data['course_name'])) {
            $feesData = $this->calculateDefaultFees($data['course_name']);
            $data = array_merge($data, $feesData);
        }
        
        //   ADD CREATION HISTORY WITH CORRECT TIMEZONE
        $now = Carbon::now('Asia/Kolkata'); // Force India timezone
        
        $data['history'] = [[
            'action' => 'Created',
            'user' => auth()->check() ? auth()->user()->name : 'Admin',
            'description' => "New inquiry created for {$data['student_name']}",
            'timestamp' => $now->toIso8601String(),
            'date' => $now->format('d M Y, h:i A'), // This will show correct IST time
            'changes' => []
        ]];
        
        Log::info('Creating inquiry with timestamp:', [
            'timestamp' => $now->toIso8601String(),
            'formatted_date' => $now->format('d M Y, h:i A')
        ]);
        
        $inquiry = Inquiry::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Inquiry created successfully',
            'data' => $inquiry,
        ], 201);
    } catch (\Exception $e) {
        Log::error('Inquiry Store Error: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Failed to create inquiry: ' . $e->getMessage(),
        ], 500);
    }
}


    /**
     * Update inquiry
     */
public function update(Request $request, $id)
{
    Log::info('UPDATE METHOD CALLED', ['id' => $id]);

    $validatedData = $request->validate([
        // Basic Details
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
        
        //   Parent Details
        'fatherOccupation' => 'nullable|string|max:255',
        'fatherGrade' => 'nullable|string|max:255',
        'motherOccupation' => 'nullable|string|max:255',
        
        //   Address Details
        'state' => 'nullable|string|max:255',
        'city' => 'nullable|string|max:255',
        'pinCode' => 'nullable|string|max:6',
        'address' => 'nullable|string',
        'belongToOtherCity' => 'nullable|in:Yes,No',
        'economicWeakerSection' => 'nullable|in:Yes,No',
        'armyPoliceBackground' => 'nullable|in:Yes,No',
        'speciallyAbled' => 'nullable|in:Yes,No',
        
        // Course Details
        'courseType' => 'nullable|string|max:255',
        'courseName' => 'nullable|string|max:255',
        'deliveryMode' => 'nullable|in:Offline,Online,Hybrid',
        'medium' => 'nullable|in:English,Hindi',
        'board' => 'nullable|in:CBSE,RBSE,ICSE',
        'courseContent' => 'nullable|string|max:255',
        
        //   Academic Details
        'previousClass' => 'nullable|string',
        'previousMedium' => 'nullable|string',
        'schoolName' => 'nullable|string|max:255',
        'previousBoard' => 'nullable|string',
        'passingYear' => 'nullable|integer|min:2000|max:2030',
        'percentage' => 'nullable|numeric|min:0|max:100',
        
        //   Scholarship Eligibility
        'isRepeater' => 'nullable|in:Yes,No',
        'scholarshipTest' => 'nullable|in:Yes,No',
        'lastBoardPercentage' => 'nullable|numeric|min:0|max:100',
        'competitionExam' => 'nullable|in:Yes,No',
    ]);

    try {
        $inquiry = Inquiry::findOrFail($id);
        $oldData = $inquiry->toArray();

        // Map form fields to database fields
        $updateData = [
            // Basic Details
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
            
            //   Parent Details
            'fatherOccupation' => $validatedData['fatherOccupation'] ?? null,
            'fatherGrade' => $validatedData['fatherGrade'] ?? null,
            'motherOccupation' => $validatedData['motherOccupation'] ?? null,
            
            //   Address Details
            'state' => $validatedData['state'] ?? null,
            'city' => $validatedData['city'] ?? null,
            'pinCode' => $validatedData['pinCode'] ?? null,
            'address' => $validatedData['address'] ?? null,
            'belongToOtherCity' => $validatedData['belongToOtherCity'] ?? 'No',
            'economicWeakerSection' => $validatedData['economicWeakerSection'] ?? 'No',
            'armyPoliceBackground' => $validatedData['armyPoliceBackground'] ?? 'No',
            'speciallyAbled' => $validatedData['speciallyAbled'] ?? 'No',
            
            // Course Details
            'courseType' => $validatedData['courseType'] ?? null,
            'course_name' => $validatedData['courseName'] ?? null,
            'delivery_mode' => $validatedData['deliveryMode'] ?? null,
            'medium' => $validatedData['medium'] ?? null,
            'board' => $validatedData['board'] ?? null,
            'course_content' => $validatedData['courseContent'] ?? null,
            
            //   Academic Details
            'previousClass' => $validatedData['previousClass'] ?? null,
            'previousMedium' => $validatedData['previousMedium'] ?? null,
            'schoolName' => $validatedData['schoolName'] ?? null,
            'previousBoard' => $validatedData['previousBoard'] ?? null,
            'passingYear' => $validatedData['passingYear'] ?? null,
            'percentage' => $validatedData['percentage'] ?? null,
            
            //   Scholarship Eligibility
            'isRepeater' => $validatedData['isRepeater'] ?? 'No',
            'scholarshipTest' => $validatedData['scholarshipTest'] ?? 'No',
            'lastBoardPercentage' => $validatedData['lastBoardPercentage'] ?? null,
            'competitionExam' => $validatedData['competitionExam'] ?? 'No',
        ];

        // Track changes
        $changes = [];
        foreach ($updateData as $key => $newValue) {
            $oldValue = $oldData[$key] ?? null;
            if ($oldValue != $newValue && $newValue !== null) {
                $changes[$key] = [
                    'from' => $oldValue,
                    'to' => $newValue
                ];
            }
        }

        // Add history if something changed
        if (!empty($changes)) {
            $now = Carbon::now('Asia/Kolkata');
            $history = $this->addHistory(
                $inquiry,
                'Updated',
                "Inquiry updated - changed: " . implode(', ', array_keys($changes)),
                $changes,
                $now
            );
            $updateData['history'] = $history;
        }

        $inquiry->update($updateData);
        
        return redirect()->route('inquiries.scholarship.show', $id)
            ->with('success', 'Inquiry updated successfully!');

    } catch (\Exception $e) {
        Log::error('ERROR IN UPDATE: ' . $e->getMessage());
        return redirect()->route('inquiries.edit', $id)
            ->with('error', 'Error updating inquiry: ' . $e->getMessage())
            ->withInput();
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
            'discretionary_discount_value' => $inquiry->discretionary_discount_value ?? 0,
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

        Log::info('  Validation passed', $validated);

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

        //   CRITICAL: Calculate fees correctly after ALL discounts
        $finalFees = floatval($validated['final_fees']);
        
        Log::info('  Fees Calculation', [
            'base_fee' => $validated['total_fee_before_discount'],
            'scholarship_discount' => $validated['scholarship_discount_percentage'] . '%',
            'after_scholarship' => $validated['scholarship_discounted_fees'],
            'discretionary_discount' => $validated['add_discretionary_discount'],
            'final_fees' => $finalFees
        ]);
        
        // Calculate GST on FINAL discounted amount (not base fee!)
        $gstAmount = ($finalFees * 18) / 100;
        $totalFeesInclusiveTax = $finalFees + $gstAmount;
        
        // Calculate installments based on total WITH tax
        $installment1 = round($totalFeesInclusiveTax * 0.40, 2);
        $installment2 = round($totalFeesInclusiveTax * 0.30, 2);
        $installment3 = round($totalFeesInclusiveTax * 0.30, 2);
        
        Log::info('  Final Calculation', [
            'final_fees' => $finalFees,
            'gst_18%' => $gstAmount,
            'total_with_gst' => $totalFeesInclusiveTax,
            'installment_1' => $installment1,
            'installment_2' => $installment2,
            'installment_3' => $installment3
        ]);

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
            
            Log::info('💵 Discretionary discount saved:', [
                'type' => $updateData['discretionary_discount_type'],
                'value' => $updateData['discretionary_discount_value'],
                'reason' => $updateData['discretionary_discount_reason']
            ]);
        } else {
            $updateData['discretionary_discount'] = 'No';
            $updateData['discretionary_discount_type'] = null;
            $updateData['discretionary_discount_value'] = null;
            $updateData['discretionary_discount_reason'] = null;
        }
        
        //   CRITICAL: Add CORRECT fees data
        $updateData['fees_breakup'] = 'Class room course (with test series & study material)';
        $updateData['total_fees'] = $finalFees;  // Base fee after ALL discounts
        $updateData['gst_amount'] = $gstAmount;   // 18% of FINAL fees (not base!)
        $updateData['total_fees_inclusive_tax'] = $totalFeesInclusiveTax;  // Final + GST
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
            
            // Add details if discount was added
            if ($validated['add_discretionary_discount'] === 'Yes') {
                $changes['discretionary_discount_details'] = [
                    'type' => $request->discretionary_discount_type,
                    'value' => $request->discretionary_discount_value,
                    'reason' => $request->discretionary_discount_reason
                ];
            }
        }
        
        if ($oldValues['total_fees'] != $finalFees) {
            $changes['total_fees'] = [
                'from' => '₹' . number_format($oldValues['total_fees'], 2),
                'to' => '₹' . number_format($finalFees, 2)
            ];
        }

        // Add GST info to changes
        $changes['gst_amount'] = '₹' . number_format($gstAmount, 2);
        $changes['total_with_gst'] = '₹' . number_format($totalFeesInclusiveTax, 2);

        // Add history entry using the existing helper method
        $now = Carbon::now('Asia/Kolkata');
        $history = $this->addHistory(
            $inquiry,
            'Scholarship Updated',
            'Scholarship and fees details updated with correct GST calculation',
            $changes,
            $now
        );
        
        $updateData['history'] = $history;
        
        // Update the inquiry
        $inquiry->update($updateData);
        
        // Verify the save
        $inquiry->refresh();
        
        Log::info('  Scholarship data saved successfully', [
            'inquiry_id' => $id,
            'eligible_for_scholarship' => $inquiry->eligible_for_scholarship,
            'scholarship_name' => $inquiry->scholarship_name,
            'discretionary_discount' => $inquiry->discretionary_discount,
            'discretionary_discount_value' => $inquiry->discretionary_discount_value,
            'total_fees' => $inquiry->total_fees,
            'gst_amount' => $inquiry->gst_amount,
            'total_with_tax' => $inquiry->total_fees_inclusive_tax,
            'history_count' => count($history)
        ]);
        
        return redirect()->route('inquiries.fees-batches.show', $id)
            ->with('success', '  Scholarship details saved successfully!');
            
    } catch (\Exception $e) {
        Log::error('=== SCHOLARSHIP UPDATE ERROR ===', [
            'error' => $e->getMessage(),
            'line' => $e->getLine(),
            'file' => $e->getFile(),
            'trace' => $e->getTraceAsString()
        ]);
        
        return redirect()->back()
            ->with('error', ' Error saving scholarship details: ' . $e->getMessage())
            ->withInput();
    }
}

    /**
     * Single onboard
     */
    public function singleOnboard($id)
    {
        try {
            $inquiry = Inquiry::find($id);
            
            if (!$inquiry) {
                return response()->json([
                    'success' => false,
                    'message' => 'Inquiry not found!'
                ], 404);
            }

            if (in_array($inquiry->status, ['transferred', 'onboarded'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Already processed!'
                ], 400);
            }

            // Transfer to pending
            $pendingStudent = $this->transferToPending($inquiry);
            
            //   Add transfer history
            $history = $this->addHistory(
                $inquiry,
                'Transferred',
                "Student transferred to pending list",
                [
                    'status' => 'transferred',
                    'pending_id' => (string) $pendingStudent->_id
                ]
            );

            $inquiry->update([
                'status' => 'transferred',
                'pending_student_id' => (string) $pendingStudent->_id,
                'history' => $history
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Student transferred successfully!',
            ]);

        } catch (\Exception $e) {
            Log::error('Onboard error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed: ' . $e->getMessage()
            ], 500);
        }
    }


/**
 * TRANSFER TO PENDING - With COMPLETE data transfer and history tracking
 */
private function transferToPending($inquiry)
{
    Log::info('Transferring inquiry to pending', [
        'inquiry_id' => $inquiry->_id,
        'student_name' => $inquiry->student_name,
    ]);

    // Create pending student record with ALL fields from inquiry
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
        
        //   Parent occupation details
        'fatherOccupation' => $inquiry->fatherOccupation ?? null,
        'fatherGrade' => $inquiry->fatherGrade ?? null,
        'motherOccupation' => $inquiry->motherOccupation ?? null,
        
        //    : Address Details
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
        'courseContent' => $inquiry->course_content ?? 'Class Room Course',
        
        //    : Medium and Board
        'medium' => $inquiry->medium ?? null,
        'board' => $inquiry->board ?? null,
        
        //    : Academic Details
        'previousClass' => $inquiry->previousClass ?? null,
        'previousMedium' => $inquiry->previousMedium ?? null,
        'schoolName' => $inquiry->schoolName ?? null,
        'previousBoard' => $inquiry->previousBoard ?? null,
        'passingYear' => $inquiry->passingYear ?? null,
        'percentage' => $inquiry->percentage ?? null,
        
        //    : Scholarship Eligibility
        'isRepeater' => $inquiry->isRepeater ?? 'No',
        'scholarshipTest' => $inquiry->scholarshipTest ?? 'No',
        'lastBoardPercentage' => $inquiry->lastBoardPercentage ?? null,
        'competitionExam' => $inquiry->competitionExam ?? 'No',
        
        // Batch Details
        'batchName' => $inquiry->batch_name ?? $inquiry->batchName ?? null,
        'batch' => $inquiry->batch ?? null,
        
        //    : Complete Scholarship & Fees Details
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

    //   CRITICAL  : Ensure batch_id is set BEFORE creating pending record
    if (empty($pendingData['batch_id']) && !empty($pendingData['batchName'])) {
        $batch = Batch::where('name', $pendingData['batchName'])
            ->orWhere('batch_id', $pendingData['batchName'])
            ->first();
        
        if ($batch) {
            $pendingData['batch_id'] = (string)$batch->_id;
            $pendingData['batch'] = $batch->batch_id ?? $batch->name;
            
            Log::info('  Batch ID set from inquiry', [
                'batch_id' => $pendingData['batch_id'],
                'batch_name' => $pendingData['batch']
            ]);
        } else {
            Log::warning('  Batch not found in database', [
                'searched_batch_name' => $pendingData['batchName']
            ]);
        }
    }

    //   CRITICAL  : Ensure course_id is set BEFORE creating pending record
    if (empty($pendingData['course_id']) && !empty($pendingData['courseName'])) {
        $course = Courses::where('name', $pendingData['courseName'])
            ->orWhere('course_name', $pendingData['courseName'])
            ->first();
        
        if ($course) {
            $pendingData['course_id'] = (string)$course->_id;
            $pendingData['course'] = $course->name ?? $course->course_name;
            
            Log::info('  Course ID set from inquiry', [
                'course_id' => $pendingData['course_id'],
                'course_name' => $pendingData['course']
            ]);
        } else {
            Log::warning('  Course not found in database', [
                'searched_course_name' => $pendingData['courseName']
            ]);
        }
    }

    // Transfer existing history from inquiry
    $existingHistory = $inquiry->history ?? [];
    
    // Use Carbon with timezone for transfer entry
    $now = Carbon::now('Asia/Kolkata');
    
    $transferHistoryEntry = [
        'action' => 'Student Enquiry Transferred',
        'description' => 'Admin transferred the enquiry to Onboard for student ' . $inquiry->student_name,
        'changed_by' => auth()->user()->name ?? auth()->user()->email ?? 'Admin',
        'timestamp' => $now->toIso8601String(),
        'date' => $now->format('d M Y, h:i A')
    ];
    
    Log::info('Transfer timestamp:', [
        'timestamp' => $now->toIso8601String(),
        'formatted_date' => $now->format('d M Y, h:i A'),
        'current_time' => Carbon::now()->format('H:i:s')
    ]);
    
    array_unshift($existingHistory, $transferHistoryEntry);
    $pendingData['history'] = $existingHistory;
    $pendingData['transferred_at'] = $now;

    // Log all transferred data for verification
    Log::info('  Complete data being transferred', [
        'name' => $pendingData['name'],
        'fatherOccupation' => $pendingData['fatherOccupation'] ?? 'NOT SET',
        'medium' => $pendingData['medium'] ?? 'NOT SET',
        'board' => $pendingData['board'] ?? 'NOT SET',
        'previousClass' => $pendingData['previousClass'] ?? 'NOT SET',
        'scholarship_name' => $pendingData['scholarship_name'],
        'total_fees' => $pendingData['total_fees'],
        'gst_amount' => $pendingData['gst_amount']
    ]);

    // Create the pending student with ALL data
    $pendingStudent = Pending::create($pendingData);

    Log::info('  Pending student created with complete data', [
        'pending_id' => $pendingStudent->_id,
        'name' => $pendingStudent->name,
        'batch_id' => $pendingStudent->batch_id ?? 'NOT SET',
        'course_id' => $pendingStudent->course_id ?? 'NOT SET',
        'fatherOccupation' => $pendingStudent->fatherOccupation ?? 'NOT SET',
        'total_fees' => $pendingStudent->total_fees
    ]);

    // Update inquiry with correct timestamp
    $inquiryHistory = $inquiry->history ?? [];
    
    $inquiryUpdateEntry = [
        'action' => 'Transferred',
        'description' => 'Inquiry transferred to pending students list',
        'changed_by' => auth()->user()->name ?? auth()->user()->email ?? 'Admin',
        'timestamp' => $now->toIso8601String(),
        'date' => $now->format('d M Y, h:i A'),
        'changes' => [
            'status' => [
                'from' => $inquiry->status ?? 'new',
                'to' => 'transferred'
            ],
            'pending_student_id' => (string) $pendingStudent->_id
        ]
    ];
    
    array_unshift($inquiryHistory, $inquiryUpdateEntry);

    $inquiry->update([
        'status' => 'transferred',
        'transferred_to_pending' => true,
        'pending_student_id' => (string) $pendingStudent->_id,
        'history' => $inquiryHistory
    ]);

    return $pendingStudent;
}
    /**
     * BULK ONBOARD - Transfer multiple inquiries to pending students
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
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to transfer students: ' . $e->getMessage()
            ], 500);
        }
    }

      private function addHistory($inquiry, $action, $description, $changes = [], $now = null)
    {
        $history = $inquiry->history ?? [];
        
        //    : Use provided time or create new Carbon instance
        if (!$now) {
            $now = Carbon::now('Asia/Kolkata');
        }
        
        $entry = [
            'action' => $action,
            'user' => auth()->check() ? auth()->user()->name : 'Admin',
            'description' => $description,
            'timestamp' => $now->toIso8601String(),
            'date' => $now->format('d M Y, h:i A'), //   Shows correct time like "18 Nov 2025, 12:23 PM"
            'changes' => $changes
        ];
        
        array_unshift($history, $entry);
        
        return $history;
    }
/**
 * Edit inquiry - Show the edit form
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
        Log::error('Stack trace: ' . $e->getTraceAsString());
        
        return redirect()->route('inquiries.index')
            ->with('error', 'Unable to load inquiry for editing: ' . $e->getMessage());
    }
}
}