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
            // Get students with 'onboarded' status (complete forms)
            $students = Onboard::where('status', 'onboarded')
                ->orderBy('created_at', 'desc')
                ->get();
            
            Log::info('Fetching onboarded students:', [
                'count' => $students->count(),
                'students' => $students->pluck('name', '_id')->toArray()
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
        // Redirect to StudentController's update method
        $controller = new StudentController();
        return $controller->update($request, $id);
    }
}