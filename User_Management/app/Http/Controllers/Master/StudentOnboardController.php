<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\StudentOnboard;
use Illuminate\Http\Request;

class StudentOnboardController extends Controller
{
    /**
     * Display a listing of onboarded students.
     */
    public function index()
    {
        $onboards = StudentOnboard::all();
return view('master.student.onboard', compact('onboards'));    }

    /**
     * Show details of a single student.
     */
    public function show($id)
    {
        $onboards = StudentOnboard::findOrFail($id);
        return view('master.student.onboard.show', compact('onboards'));
    }

    /**
     * Show the edit form for a student.
     */
    public function edit($id)
    {
        $onboards = StudentOnboard::findOrFail($id);
        return view('master.student.onboard.edit', compact('onboards'));
    }

    /**
     * Update the student’s record.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'student_name' => 'required|string|max:255',
            'father_name' => 'required|string|max:255',
            'father_contact' => 'required|digits:10',
            'course_name' => 'nullable|string|max:255',
            'delivery_mode' => 'nullable|string|max:255',
            'course_content' => 'nullable|string|max:255',
        ]);

        $onboards = StudentOnboard::findOrFail($id);
        $onboards->update($request->all());

        return redirect()->route('master.student.onboard')
                         ->with('success', 'Student updated successfully!');
    }

    /**
     * Handle student transfer action.
     */
    public function transfer($id)
    {
        $onboards = StudentOnboard::findOrFail($id);
        $onboards->status = 'transferred';
        $onboards->save();

        return back()->with('success', 'Student transferred successfully.');
    }

    /**
     * Show student history.
     */
    public function history($id)
    {
        $onboards = StudentOnboard::findOrFail($id);
        $history = $onboards->history; // Assuming relation defined
        return view('master.student.onboard.history', compact('onboards', 'history'));
    }
}
