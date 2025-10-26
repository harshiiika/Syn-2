<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Student\Inquiry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

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
    $inquiry = Inquiry::findOrFail($id);
    return view('inquiries.edit', compact('inquiry'));
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
     * Update an inquiry
     */
public function update(Request $request, $id)
{
    try {
        $inquiry = Inquiry::findOrFail($id);

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
            'status' => 'nullable|string',
            // Add other fields as needed
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $inquiry->update($validator->validated());

        return redirect()->route('inquiries.index')
            ->with('success', 'Inquiry updated successfully');

    } catch (\Exception $e) {
        Log::error('Inquiry Update Error: ' . $e->getMessage());
        return redirect()->back()
            ->with('error', 'Error updating inquiry')
            ->withInput();
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
        return view('inquiries.view', compact('inquiry'));
    } catch (\Exception $e) {
        \Log::error('Failed to load inquiry details: ' . $e->getMessage());
        return redirect()->route('inquiries.index')->with('error', 'Unable to load inquiry details.');
    }
}

}


