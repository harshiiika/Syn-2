<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student\Onboard;
use App\Models\Student\SMstudents;
use App\Models\Student\PendingFee; 
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
            Log::info('=== SHOW METHOD CALLED ===', ['id' => $id]);
            
            // Find student - NO relationships
            $student = Onboard::find($id);
            
            if (!$student) {
                return redirect()->route('student.onboard.onboard')
                    ->with('error', 'Student not found');
            }
            
            Log::info('Student found', ['name' => $student->name]);
            
            // Don't load Batch or Courses - just pass empty arrays
            $batches = collect([]); // Empty collection
            $courses = collect([]); // Empty collection
            
            // Simple scholarship check without complex queries
            $scholarshipEligible = [
                'eligible' => ($student->eligible_for_scholarship ?? 'No') === 'Yes',
                'reason' => $student->scholarship_name ?? 'Not Eligible',
                'discountPercent' => floatval($student->discount_percentage ?? 0)
            ];
            
            Log::info('About to render view');
            
            return view('student.onboard.view', compact('student', 'batches', 'courses', 'scholarshipEligible'));
            
        } catch (\Exception $e) {
            Log::error('SHOW ERROR', [
                'message' => $e->getMessage(),
                'line' => $e->getLine()
            ]);
            
            return redirect()->route('student.onboard.onboard')
                ->with('error', 'Error: ' . $e->getMessage());
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
     * Update the onboarded student with history tracking
     */
    public function update(Request $request, $id)
    {
        try {
            $student = Onboard::findOrFail($id);
            
            // Get the original data for comparison
            $originalData = $student->getOriginal();
            
            // Update all fields from request
            $updateData = $request->except(['_token', '_method']);
            
            // Track changes for history
            $changes = [];
            
            // Fields to track for history
            $fieldsToTrack = [
                'name' => 'Student Name',
                'father' => 'Father Name',
                'mother' => 'Mother Name',
                'mobileNumber' => 'Mobile Number',
                'alternateMobileNumber' => 'Alternate Mobile',
                'email' => 'Email',
                'courseName' => 'Course Name',
                'deliveryMode' => 'Delivery Mode',
                'courseContent' => 'Course Content',
                'batchName' => 'Batch Name',
                'category' => 'Category',
                'address' => 'Address',
                'percentage' => 'Percentage',
                'eligible_for_scholarship' => 'Scholarship Eligibility',
                'scholarship_name' => 'Scholarship Name',
                'discount_percentage' => 'Discount Percentage'
            ];
            
            foreach ($fieldsToTrack as $field => $label) {
                $oldValue = $originalData[$field] ?? null;
                $newValue = $updateData[$field] ?? null;
                
                // Compare values
                if ($oldValue != $newValue && !is_null($newValue)) {
                    $changes[$label] = [
                        'from' => $oldValue ?? 'Not Set',
                        'to' => $newValue
                    ];
                }
            }
            
            // Handle file uploads
            $fileFields = [
                'passport_photo' => 'documents/passport',
                'marksheet' => 'documents/marksheet',
                'caste_certificate' => 'documents/caste',
                'scholarship_proof' => 'documents/scholarship',
                'secondary_marksheet' => 'documents/secondary',
                'senior_secondary_marksheet' => 'documents/senior_secondary'
            ];
            
            foreach ($fileFields as $field => $path) {
                if ($request->hasFile($field)) {
                    $updateData[$field] = $request->file($field)->store($path, 'public');
                    $changes[ucwords(str_replace('_', ' ', $field))] = [
                        'from' => 'Previous File',
                        'to' => 'New File Uploaded'
                    ];
                }
            }
            
            // Add history entry if there are changes
            if (!empty($changes)) {
                $historyEntry = [
                    'action' => 'Student Details Updated',
                    'description' => 'Student information was modified',
                    'changes' => $changes,
                    'changed_by' => auth()->user()->name ?? auth()->user()->email ?? 'Admin',
                    'timestamp' => now()->toIso8601String()
                ];
                
                // Get existing history or create new array
                $history = $student->history ?? [];
                
                // Add new entry at the beginning
                array_unshift($history, $historyEntry);
                
                // Limit history to last 50 entries
                if (count($history) > 50) {
                    $history = array_slice($history, 0, 50);
                }
                
                $updateData['history'] = $history;
            }
            
            // Update the student
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
            $student = Onboard::findOrFail($id);
            
            Log::info('Starting transfer', [
                'student_id' => $id,
                'student_name' => $student->name
            ]);
            
            // Prepare data
            $pendingFeesData = $student->toArray();
            unset($pendingFeesData['_id']);
            
            // Set transfer metadata
            $pendingFeesData['status'] = 'pending_fees';
            $pendingFeesData['transferred_from'] = 'onboard';
            $pendingFeesData['transferred_at'] = now();
            $pendingFeesData['transferred_by'] = auth()->user()->email ?? 'Admin';
            
            // Initialize payment fields
            $totalFeesInclusive = $pendingFeesData['total_fees_inclusive_tax'] ?? 
                                 ($pendingFeesData['total_fees'] ?? 0);
            
            $pendingFeesData['paid_fees'] = 0;
            $pendingFeesData['remaining_fees'] = $totalFeesInclusive;
            $pendingFeesData['fee_status'] = 'pending';
            $pendingFeesData['paymentHistory'] = [];
            
            // Add transfer to history
            $historyEntry = [
                'action' => 'Transferred to Pending Fees',
                'description' => 'Student was transferred from Onboarding to Pending Fees collection',
                'changed_by' => auth()->user()->name ?? auth()->user()->email ?? 'Admin',
                'timestamp' => now()->toIso8601String()
            ];
            
            // Add to existing history
            $history = $pendingFeesData['history'] ?? [];
            array_unshift($history, $historyEntry);
            $pendingFeesData['history'] = $history;
            
            // Create in pending_fees
            $pendingFeeStudent = PendingFee::create($pendingFeesData);
            
            // Delete from onboard
            $student->delete();
            
            Log::info('Transfer successful', [
                'new_id' => $pendingFeeStudent->_id,
                'student_name' => $pendingFeeStudent->name
            ]);
            
            return redirect()->route('student.onboard.onboard')
                ->with('success', "Student '{$pendingFeeStudent->name}' transferred to Pending Fees successfully");
                
        } catch (\Exception $e) {
            Log::error('Transfer failed', [
                'student_id' => $id,
                'error' => $e->getMessage()
            ]);
            
            return redirect()->back()
                ->with('error', 'Failed to transfer student: ' . $e->getMessage());
        }
    }

    /**
     * Initialize history for existing students (run once)
     */
    public function initializeHistory()
    {
        try {
            $students = Onboard::all();
            $count = 0;
            
            foreach ($students as $student) {
                // Only add initial history if it doesn't exist
                if (!isset($student->history) || empty($student->history)) {
                    $initialHistory = [
                        [
                            'action' => 'Student Onboarded',
                            'description' => 'Initial student onboarding record',
                            'changed_by' => 'System',
                            'timestamp' => $student->created_at ? $student->created_at->toIso8601String() : now()->toIso8601String()
                        ]
                    ];
                    
                    $student->update(['history' => $initialHistory]);
                    $count++;
                }
            }
            
            return response()->json([
                'success' => true,
                'message' => "Successfully initialized history for {$count} students"
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error initializing history: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
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