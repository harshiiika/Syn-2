<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User\BatchAssignment;
use Illuminate\Http\Request;

class BatchesController extends Controller
{

    public function index()
{
    $assignments = BatchAssignment::with('batch')->get();
    return view('batches.assign', compact('assignments'));
}

    /**
     * Show all batch assignments after login
     */
    public function showBatches()
    {
        // fetch all batches
        $batches = BatchAssignment::all();
        return view('user.batches.batches', compact('batches'));
    }

    /**
     * Toggle assignment status
     */
    public function updateStatus($id)
    {
        $batch = BatchAssignment::findOrFail($id);

        $batch->status = ($batch->status === 'Assigned') ? 'Not Assigned' : 'Assigned';
        $batch->save();

        return redirect()->back()->with('success', 'Batch status updated successfully.');
    }

    /**
     * Store a new batch assignment
     */

    public function addBatch(Request $request)
    {
        $validated = $request->validate([
            'batch_id' => 'required|string|max:50',
            'start_date' => 'required|date',
            'username'   => 'required|string|max:100',
            'shift'      => 'required|string|max:50',
            'status'     => 'nullable|string|max:50',
        ]);

    $assignment = BatchAssignment::create([
        'batch_id'   => $validated['batch_id'],
        'username'   => $validated['username'],
        'shift'      => $validated['shift'] ?? null,
        'status'     => 'Assigned',
        'start_date' => now()->toDateString(),
    ]);

    return redirect()->route('batches.assign')
                     ->with('success', 'Batch assigned successfully!');
}

}
