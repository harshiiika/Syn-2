<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student\Onboard;
use Illuminate\Support\Facades\Log;

class OnboardController extends Controller
{
    /**
     * Display onboarded students page with tabs
     */
    public function index()
    {
        try {
            // Get fully paid students
            $fullyPaid = Onboard::getAllOnboarded();
            
            // Get partially paid students (if any exist in onboard collection)
            $partiallyPaid = Onboard::getPartiallyPaid();
            
            Log::info('Fetching onboarded students:', [
                'fully_paid_count' => $fullyPaid->count(),
                'partially_paid_count' => $partiallyPaid->count()
            ]);
            
            return view('student.onboard.index', [
                'fullyPaid' => $fullyPaid,
                'partiallyPaid' => $partiallyPaid
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
            return redirect()->route('student.onboard')
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
            return redirect()->route('student.onboard')
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
                'batchName' => 'nullable|string',
            ]);

            $student->update($validated);
            
            Log::info('Onboarded student updated successfully', [
                'id' => $id,
                'name' => $student->name
            ]);

            return redirect()
                ->route('student.onboard')
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