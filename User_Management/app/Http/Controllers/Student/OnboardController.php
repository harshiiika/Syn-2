<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student\Onboard;
use Illuminate\Support\Facades\Log;

class OnboardController extends Controller
{
    /**
     /**
     * Display all onboarded students (students with status = 'onboarded')
     */
    public function index()
    {
        try {
            Log::info('=== ONBOARDED STUDENTS PAGE LOADED ===');
            
            // Get students from onboarded_students collection
            $students = Onboard::orderBy('created_at', 'desc')->get();
            
            Log::info('Fetching onboarded students:', [
                'count' => $students->count(),
                'student_ids' => $students->pluck('_id')->toArray(),
                'student_names' => $students->pluck('name')->toArray()
            ]);
            
            return view('student.onboard.onboard', [
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
            
            // Use the same edit view
            return view('student.student.edit', compact('student'));
            
        } catch (\Exception $e) {
            Log::error("Edit failed for student ID {$id}: " . $e->getMessage());
            return redirect()->route('student.onboard.onboard')
                ->with('error', 'Student not found');
        }
    }

    /**
     * Update onboarded student information
     * This uses the same update logic as StudentController
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

            return redirect()->route('student.onboard.onboard')
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
}