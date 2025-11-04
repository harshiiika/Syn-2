<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Student\Pending;
use App\Models\Student\Onboard;

class PendingController extends Controller
{
    /**
     * Display all pending (incomplete) students
     */
    public function index()
    {
        try {
            $students = Pending::orderBy('created_at', 'desc')->get();
            
            Log::info('Fetching pending students:', [
                'count' => $students->count(),
            ]);
            
            return view('student.student.pending', [  // ← Your existing blade
                'students' => $students,
                'totalCount' => $students->count(),
            ]);
        } catch (\Exception $e) {
            Log::error('Error loading pending students: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to load students');
        }
    }

    /**
     * Show edit form for pending student
     */
    public function edit($id)
    {
        try {
            $student = Pending::findOrFail($id);
            
            Log::info('Editing pending student:', [
                'student_id' => $id,
                'student_name' => $student->name
            ]);
            
            return view('student.pending.edit', compact('student'));
            
        } catch (\Exception $e) {
            Log::error("Edit failed for student ID {$id}: " . $e->getMessage());
           return redirect()->route('student.student.pending')
                 ->with('error', 'Student not found');

        }
    }

    /**
     * Check if all required fields are filled
     */
    private function isProfileComplete(array $data): bool
    {
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
     * Update pending student and move to onboard if complete
     */
    public function update(Request $request, $id)
    {
        try {
            Log::info('=== PENDING STUDENT UPDATE REQUEST ===', [
                'student_id' => $id,
            ]);

            $student = Pending::findOrFail($id);

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

            // Update student
            $student->update($validated);

            Log::info('Pending student updated');

            // Check if profile is complete
            $isComplete = $this->isProfileComplete($validated);

            if ($isComplete) {
                Log::info('✅ Profile is complete, moving to Onboard collection');

                // Prepare onboard data
                $onboardData = $student->toArray();
                unset($onboardData['_id']);
                $onboardData = array_merge($onboardData, $validated);
                $onboardData['status'] = 'onboarded';
                $onboardData['onboardedAt'] = now();

                // Create in Onboard collection
                $onboardedStudent = Onboard::create($onboardData);

                Log::info('✅ Student onboarded successfully', [
                    'onboarded_id' => $onboardedStudent->_id,
                    'name' => $onboardedStudent->name,
                ]);

                // Delete from Pending
                $student->delete();

                return redirect()->route('student.student.pending')
                       ->with('info', 'Student details updated! Complete all fields to move to Onboard.');
            }

            return redirect()->route('student.student.pending')
                ->with('info', 'Student details updated! Complete all fields to move to Onboard.');

        } catch (\Exception $e) {
            Log::error('Update failed:', [
                'id' => $id,
                'error' => $e->getMessage(),
            ]);

            return redirect()->back()
                ->with('error', 'Failed to update student: ' . $e->getMessage())
                ->withInput();
        }
    }
}