<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student\Onboard;
use Illuminate\Support\Facades\Log;

class OnboardController extends Controller
{
    /**
     * Display all onboarded students (completely filled forms)
     */
    public function index()
    {
        try {
            // Get all onboarded students
            $students = Onboard::getAllOnboarded();
            
            Log::info('Fetching onboarded students:', [
                'count' => $students->count()
            ]);
            
            return view('student.onboard.onboard', [
                'students' => $students,
                'totalCount' => $students->count()
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error loading onboarded students: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to load students');
        }
    }

    /**
     * View onboarded student details
     */
    public function show($id)
    {
        try {
            $student = Onboard::findOrFail($id);
            
            Log::info('Viewing onboarded student details:', [
                'student_id' => $id,
                'student_name' => $student->name
            ]);
            
            return view('student.onboard.view', compact('student'));
            
        } catch (\Exception $e) {
            Log::error("View failed for student ID {$id}: " . $e->getMessage());
            return redirect()->route('student.onboard.onboard')
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
            return redirect()->route('student.onboard.onboard')
                ->with('error', 'Student not found');
        }
    }

    /**
     * Update onboarded student information
     */
    public function update(Request $request, $id)
    {
        try {
            Log::info('Update request received for onboarded student', [
                'id' => $id,
                'data' => $request->except(['_token', '_method'])
            ]);

            $student = Onboard::findOrFail($id);
            
            $validated = $request->validate([
                // Basic Details
                'name' => 'required|string|max:255',
                'father' => 'required|string|max:255',
                'mother' => 'required|string|max:255',
                'dob' => 'required|date',
                'mobileNumber' => 'required|string|max:15',
                'fatherWhatsapp' => 'nullable|string|max:15',
                'motherContact' => 'nullable|string|max:15',
                'studentContact' => 'nullable|string|max:15',
                'category' => 'required|in:OBC,SC,GENERAL,ST',
                'gender' => 'required|in:Male,Female,Others',
                'fatherOccupation' => 'nullable|string',
                'fatherGrade' => 'nullable|string',
                'motherOccupation' => 'nullable|string',
                
                // Address Details
                'state' => 'required|string',
                'city' => 'required|string',
                'pinCode' => 'required|string|max:10',
                'address' => 'required|string',
                'belongToOtherCity' => 'required|in:Yes,No',
                'economicWeakerSection' => 'required|in:Yes,No',
                'armyPoliceBackground' => 'required|in:Yes,No',
                'speciallyAbled' => 'required|in:Yes,No',
                
                // Course Details
                'courseType' => 'required|string',
                'courseName' => 'required|string',
                'deliveryMode' => 'required|string',
                'medium' => 'required|string',
                'board' => 'required|string',
                'courseContent' => 'required|string',
                
                // Academic Details
                'previousClass' => 'required|string',
                'previousMedium' => 'required|string',
                'schoolName' => 'required|string',
                'previousBoard' => 'required|string',
                'passingYear' => 'required|string',
                'percentage' => 'required|numeric|min:0|max:100',
                
                // Scholarship
                'isRepeater' => 'required|in:Yes,No',
                'scholarshipTest' => 'required|in:Yes,No',
                'lastBoardPercentage' => 'required|numeric|min:0|max:100',
                'competitionExam' => 'required|in:Yes,No',
                
                // Batch Details
                'batchName' => 'required|string',
                'batchStartDate' => 'nullable|date',
            ]);

            $student->update($validated);
            
            Log::info('Onboarded student updated successfully', [
                'id' => $id,
                'name' => $student->name
            ]);

            return redirect()
                ->route('student.onboard.onboard')
                ->with('success', 'Student details updated successfully!');

        } catch (\Exception $e) {
            Log::error('Update failed:', [
                'id' => $id,
                'error' => $e->getMessage()
            ]);
            
            return redirect()
                ->back()
                ->with('error', 'Failed to update student: ' . $e->getMessage())
                ->withInput();
        }
    }
}