<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User\BatchAssignment;
use Illuminate\Http\Request;

class BatchesController extends Controller
{
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

        $batch = BatchAssignment::create($validated);

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
}
