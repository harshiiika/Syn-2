<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student\Onboard;
use App\Models\Student\PendingFee;
use Illuminate\Support\Facades\Log;

class OnboardController extends Controller
{
    /**
     * Display all onboarded students
     */
    public function index()
    {
        try {
            Log::info('=== ONBOARDED STUDENTS PAGE LOADED ===');
            
            $students = Onboard::orderBy('created_at', 'desc')->get();
            
            Log::info('Fetching onboarded students:', [
                'count' => $students->count(),
                'student_ids' => $students->pluck('_id')->toArray(),
                'student_names' => $students->pluck('name')->toArray()
            ]);
            
            return view('student.onboard.onboard', [  // ✅ Changed to 'onboard'
                'students' => $students,
                'totalCount' => $students->count()
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error loading onboarded students: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()
                ->with('error', 'Failed to load students: ' . $e->getMessage());
        }
    }

    /**
     * View onboarded student details with scholarship and fees information
     */
    public function show($id)
    {
        try {
            $student = Onboard::findOrFail($id);
            
            Log::info('=== VIEWING ONBOARDED STUDENT DETAILS ===', [
                'student_id' => $id,
                'student_name' => $student->name,
                'has_scholarship_data' => !empty($student->eligible_for_scholarship),
                'eligible_for_scholarship' => $student->eligible_for_scholarship ?? 'NOT SET',
                'scholarship_name' => $student->scholarship_name ?? 'NOT SET',
                'total_fee_before_discount' => $student->total_fee_before_discount ?? 'NOT SET',
                'total_fees' => $student->total_fees ?? 'NOT SET',
                'gst_amount' => $student->gst_amount ?? 'NOT SET',
            ]);
            
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
            
            Log::info('✅ Fees data prepared for view:', $feesData);
            
            return view('student.onboard.view', compact('student', 'feesData'));
            
        } catch (\Exception $e) {
            Log::error("❌ View failed for onboarded student ID {$id}: " . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return redirect()->route('student.onboard.onboard')  // ✅ Correct
                ->with('error', 'Student not found');
        }
    }

    /**
     * Edit onboarded student
     */
    public function edit($id)
    {
        try {
            $student = Onboard::findOrFail($id);
            
            Log::info('Editing onboarded student:', [
                'student_id' => $id,
                'student_name' => $student->name
            ]);
            
            return view('student.onboard.edit', compact('student'));
            
        } catch (\Exception $e) {
            Log::error("Edit failed for student ID {$id}: " . $e->getMessage());
            return redirect()->route('student.onboard.onboard')  // ✅ Correct
                ->with('error', 'Student not found');
        }
    }

    /**
     * Update onboarded student information
     */
    public function update(Request $request, $id)
    {
        try {
            Log::info('=== ONBOARDED STUDENT UPDATE REQUEST ===', [
                'student_id' => $id,
                'request_keys' => array_keys($request->all())
            ]);

            $student = Onboard::findOrFail($id);
            
            Log::info('Onboarded student found:', [
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

            // Remove null values
            $validated = array_filter($validated, function($value) {
                return $value !== null;
            });

            Log::info('Updating onboarded student with data:', [
                'validated_fields' => array_keys($validated)
            ]);

            // Update the onboarded student
            $student->update($validated);
            $student->refresh();
            
            Log::info('Onboarded student updated successfully:', [
                'student_id' => $student->_id
            ]);

            return redirect()->route('student.onboard.onboard')  // ✅ Correct
                ->with('success', 'Student updated successfully');
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('Onboarded student not found:', ['id' => $id]);
            return redirect()->back()
                ->with('error', 'Student not found')
                ->withInput();
                
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed', ['errors' => $e->errors()]);
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
            
        } catch (\Exception $e) {
            Log::error('Error updating onboarded student: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->with('error', 'Failed to update student: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Transfer an onboarded student to Pending Fees
     */
    public function transfer(Request $request, $id)
    {
        try {
            Log::info('=== STUDENT TRANSFER TO PENDING FEES INITIATED ===', [
                'onboard_id' => $id,
                'request' => $request->all()
            ]);

            $onboardStudent = Onboard::findOrFail($id);

            // Optional validation
            $validated = $request->validate([
                'transfer_reason' => 'nullable|string|max:255',
                'batchName' => 'nullable|string|max:255',
                'transfer_date' => 'nullable|date',
            ]);

            // Prepare data for PendingFee model
            $pendingFeeData = $onboardStudent->toArray();
            unset($pendingFeeData['_id']); // Remove MongoDB _id

            // Set transfer metadata
            $pendingFeeData['status'] = 'pending_fees';
            $pendingFeeData['transferred_from'] = 'onboard';
            $pendingFeeData['transfer_reason'] = $validated['transfer_reason'] ?? null;
            $pendingFeeData['batchName'] = $validated['batchName'] ?? $onboardStudent->batchName;
            $pendingFeeData['transfer_date'] = $validated['transfer_date'] ?? now();
            $pendingFeeData['transferred_at'] = now();
            $pendingFeeData['updated_at'] = now();

            // Ensure remaining_fees is set
            if (empty($pendingFeeData['remaining_fees']) && !empty($pendingFeeData['total_fees'])) {
                $pendingFeeData['remaining_fees'] = $pendingFeeData['total_fees'];
            }

            // Log data being inserted
            Log::info('📝 Data being inserted into PendingFee:', [
                'status' => $pendingFeeData['status'],
                'name' => $pendingFeeData['name'] ?? 'N/A',
                'collection' => 'student_pending_fee',
            ]);

            // Create in PendingFee model
            $pendingFeeStudent = PendingFee::create($pendingFeeData);

            Log::info('✅ Student created in PendingFee collection', [
                'new_pending_fee_id' => $pendingFeeStudent->_id,
                'name' => $pendingFeeStudent->name,
                'status' => $pendingFeeStudent->status,
            ]);

            // Verify creation
            $verification = PendingFee::find($pendingFeeStudent->_id);
            if (!$verification) {
                throw new \Exception('Student was not found in PendingFee collection after creation!');
            }

            Log::info('✅ Verification successful - student exists in pending_fee collection');

            // Delete from Onboard
            $onboardStudent->delete();

            Log::info('✅ Student removed from onboard collection');

            return redirect()->route('student.pendingfees.pending')  // ✅ Changed to 'pending'
                ->with('success', 'Student transferred to Pending Fees successfully!');

        } catch (\Exception $e) {
            Log::error('❌ Transfer failed: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()
                ->with('error', 'Transfer failed: ' . $e->getMessage())
                ->withInput();
        }
    }
}