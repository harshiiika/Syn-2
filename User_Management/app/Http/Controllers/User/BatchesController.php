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
<<<<<<< Updated upstream
{
    $validated = $request->validate([
        'batch_id' => 'required',
        'username' => 'required|string|max:255',
        'shift'    => 'nullable|string|max:255',
    ]);
=======
    {
        $validated = $request->validate([
            'batch_id' => 'required|string|max:50',
            'start_date' => 'required|date',
            'username'   => 'required|string|max:100',
            'shift'      => 'required|string|max:50',
            'status'     => 'nullable|string|max:50',
        ]);
>>>>>>> Stashed changes

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

<<<<<<< Updated upstream
=======
        return response()->json([
            'status' => 'success',
            'batch' => $batch,
        ]);
    }  //automatically today's date gets assigned here

    /**
     * Edit a batch assignment
     */
    public function edit($id)
    {
        $batch = BatchAssignment::findOrFail($id);
        return view('batchesassignment.edit', compact('batch'));
    }

    /**
     * Update batch assignment
     */
    public function update(Request $request, $id)
    {
        $batch = BatchAssignment::findOrFail($id);

        $validated = $request->validate([
            'batch_id' => 'required|string|max:50',
            'start_date' => 'required|date',
            'username'   => 'required|string|max:100',
            'shift'      => 'required|string|max:50',
            'status'     => 'nullable|string|max:50',
        ]);

        $batch->update($validated);

        return redirect()->route('batch-assignments.index')->with('success', 'Batch updated successfully.');
    }

    /**
     * Delete batch assignment
     */
    public function destroy($id)
    {
        $batch = BatchAssignment::findOrFail($id);
        $batch->delete();

        return redirect()->back()->with('success', 'Batch deleted successfully.');
    }
>>>>>>> Stashed changes
}
