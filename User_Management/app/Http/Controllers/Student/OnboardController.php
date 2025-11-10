<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student\Onboard;
use App\Models\Student\SMstudents;
use App\Models\Master\Batch;
use App\Models\Master\Courses;
use App\Models\Master\Scholarship;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class OnboardController extends Controller
{
    /**
     * Display listing of onboarded students
     */
    public function index()
    {
        try {
            $students = Onboard::with(['batch', 'course'])
                ->orderBy('created_at', 'desc')
                ->get();
            
            return view('student.onboard.onboard', compact('students'));
        } catch (\Exception $e) {
            Log::error('Error loading onboarded students: ' . $e->getMessage());
            return back()->with('error', 'Failed to load students');
        }
    }

    /**
     * Display the specified onboarded student with COMPLETE details
     */
    public function show($id)
    {
        try {
            $student = Onboard::with(['batch', 'course'])->findOrFail($id);
            
            // Get batches and courses for dropdowns
            $batches = Batch::where('status', 'Active')->get();
            $courses = Courses::all();
            
            // Check scholarship eligibility
            $scholarshipEligible = $this->checkScholarshipEligibility($student);
            
            Log::info('Onboard Student View Data', [
                'student_id' => $id,
                'student_name' => $student->name ?? $student->student_name,
                'scholarship' => $scholarshipEligible
            ]);
            
            return view('student.onboard.view', compact('student', 'batches', 'courses', 'scholarshipEligible'));
        } catch (\Exception $e) {
            Log::error('Error showing onboarded student: ' . $e->getMessage());
            return back()->with('error', 'Student not found: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing onboarded student
     */
    public function edit($id)
    {
        try {
            $student = Onboard::with(['batch', 'course'])->findOrFail($id);
            $batches = Batch::where('status', 'Active')->get();
            $courses = Courses::all();
            
            return view('student.onboard.edit', compact('student', 'batches', 'courses'));
        } catch (\Exception $e) {
            Log::error('Error loading onboard edit form: ' . $e->getMessage());
            return back()->with('error', 'Failed to load student data');
        }
    }

    /**
     * Update the onboarded student
     */
    public function update(Request $request, $id)
    {
        try {
            $student = Onboard::findOrFail($id);
            
            // Update all fields from request
            $updateData = $request->except(['_token', '_method']);
            
            // Handle file uploads if any
            if ($request->hasFile('passport_photo')) {
                $updateData['passport_photo'] = $request->file('passport_photo')->store('documents/passport', 'public');
            }
            if ($request->hasFile('marksheet')) {
                $updateData['marksheet'] = $request->file('marksheet')->store('documents/marksheet', 'public');
            }
            if ($request->hasFile('caste_certificate')) {
                $updateData['caste_certificate'] = $request->file('caste_certificate')->store('documents/caste', 'public');
            }
            if ($request->hasFile('scholarship_proof')) {
                $updateData['scholarship_proof'] = $request->file('scholarship_proof')->store('documents/scholarship', 'public');
            }
            if ($request->hasFile('secondary_marksheet')) {
                $updateData['secondary_marksheet'] = $request->file('secondary_marksheet')->store('documents/secondary', 'public');
            }
            if ($request->hasFile('senior_secondary_marksheet')) {
                $updateData['senior_secondary_marksheet'] = $request->file('senior_secondary_marksheet')->store('documents/senior_secondary', 'public');
            }
            
            $student->update($updateData);
            
            return redirect()->route('student.onboard.onboard')
                ->with('success', 'Student details updated successfully');
        } catch (\Exception $e) {
            Log::error('Error updating onboarded student: ' . $e->getMessage());
            return back()->with('error', 'Failed to update student: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Transfer student from Onboard to Pending Fees
     */
   public function transfer(Request $request, $id)

{

    try {

        // Find the student in onboard collection

        $student = Onboard::findOrFail($id);

        // Prepare data for pending_fees collection

        $pendingFeesData = $student->toArray();

        // Remove MongoDB's _id to let it generate a new one

        unset($pendingFeesData['_id']);

        // Update status and transfer metadata

        $pendingFeesData['status'] = 'pending_fees';

        $pendingFeesData['transferred_from'] = 'onboard';

        $pendingFeesData['transferred_at'] = now();

        $pendingFeesData['transferred_by'] = auth()->user()->email ?? 'Admin';

        $pendingFeesData['transfer_reason'] = $request->input('transfer_reason', 'Moved to pending fees');

        // Initialize payment tracking fields if not present

        $pendingFeesData['paid_fees'] = $pendingFeesData['paid_fees'] ?? 0;

        $pendingFeesData['remaining_fees'] = $pendingFeesData['total_fees_inclusive_tax'] ?? $pendingFeesData['total_fees'] ?? 0;

        $pendingFeesData['fee_status'] = 'pending';

        $pendingFeesData['paymentHistory'] = [];

        // Create new record in pending_fees collection using the model

        PendingFee::create($pendingFeesData);

        // Delete from onboard collection

        $student->delete();

        Log::info('Student transferred to pending fees', [

            'student_id' => $id,

            'student_name' => $student->name ?? $student->student_name,

            'transferred_by' => auth()->user()->email ?? 'Admin'

        ]);

        return redirect()->route('student.onboard.onboard')

            ->with('success', 'Student transferred to Pending Fees successfully');

    } catch (\Exception $e) {

        Log::error('Error transferring student to pending fees', [

            'student_id' => $id,

            'error' => $e->getMessage(),

            'trace' => $e->getTraceAsString()

        ]);

        return redirect()->back()

            ->with('error', 'Failed to transfer student: ' . $e->getMessage());

    }

}
 

    /**
     * Check scholarship eligibility with Scholarship model integration
     */
    private function checkScholarshipEligibility($student)
    {
        $result = [
            'eligible' => false,
            'reason' => 'Not Eligible',
            'discountPercent' => 0
        ];

        // 1. Check if already has scholarship assigned
        if (in_array(strtolower($student->eligible_for_scholarship ?? ''), ['yes', 'true', '1'])) {
            $result['eligible'] = true;
            $result['reason'] = $student->scholarship_name ?? 'Scholarship Applied';
            $result['discountPercent'] = floatval($student->discount_percentage ?? 0);
            return $result;
        }

        // 2. Get student's course and category
        $courseName = $student->course_name ?? $student->courseName ?? $student->course->name ?? null;
        $category = $student->category ?? 'General';

        // 3. Check Scholarship Test
        if (in_array(strtolower($student->scholarshipTest ?? $student->scholarship_test ?? ''), ['yes'])) {
            $testPercentage = floatval($student->scholarship_percentage ?? 0);
            
            $scholarship = Scholarship::getByTestScore($testPercentage, $courseName, $category);
            
            if ($scholarship) {
                return [
                    'eligible' => true,
                    'reason' => $scholarship->scholarship_name ?? 'Scholarship Test',
                    'discountPercent' => floatval($scholarship->discount_percentage)
                ];
            }
        }

        // 4. Check Board Exam Percentage
        if (!empty($student->percentage) || !empty($student->board_percentage)) {
            $boardPercent = floatval($student->percentage ?? $student->board_percentage ?? 0);
            
            $scholarship = Scholarship::getByPercentage($boardPercent, $courseName, $category);
            
            if ($scholarship) {
                return [
                    'eligible' => true,
                    'reason' => $scholarship->scholarship_name ?? 'Board Exam Merit',
                    'discountPercent' => floatval($scholarship->discount_percentage)
                ];
            }
        }

        // 5. Check Special Categories
        $specialCategories = [
            'economicWeakerSection' => Scholarship::APPLICABLE_EWS,
            'economic_weaker_section' => Scholarship::APPLICABLE_EWS,
            'armyPoliceBackground' => Scholarship::APPLICABLE_DEFENCE,
            'army_police_background' => Scholarship::APPLICABLE_DEFENCE,
            'speciallyAbled' => Scholarship::APPLICABLE_PWD,
            'specially_abled' => Scholarship::APPLICABLE_PWD
        ];

        foreach ($specialCategories as $field => $applicableFor) {
            if (in_array(strtolower($student->$field ?? ''), ['yes'])) {
                $scholarships = Scholarship::getApplicableScholarships($category, $applicableFor, $courseName);
                
                if ($scholarships->isNotEmpty()) {
                    $scholarship = $scholarships->first();
                    return [
                        'eligible' => true,
                        'reason' => $scholarship->scholarship_name,
                        'discountPercent' => floatval($scholarship->discount_percentage)
                    ];
                }
            }
        }

        return $result;
    }
}