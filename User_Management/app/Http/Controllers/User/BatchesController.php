<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User\BatchAssignment;
use Illuminate\Http\Request;

class BatchesController extends Controller
{
    /**
     * Display all batch assignments
     */
    public function index()
    {
        // Removed with('batch') - fetch assignments directly
        $batches = BatchAssignment::all();
        return view('user.batches.batches', compact('batches'));
    }

    /**
     * Show all batch assignments (fallback or non-Ajax view)
     */
    public function showBatches()
    {
        $batches = BatchAssignment::all();
        return view('user.batches.batches', compact('batches'));
    }

    /**
     * Handle batch assignment (called via Assign Batch modal form)
     */
    public function addBatch(Request $request)
    {
        // Validate only fields coming from the modal form
        $validated = $request->validate([
            'batch_id'   => 'required|string|max:50',
            'username'   => 'required|string|max:100',
            'status'     => 'nullable|string|in:Assigned,Active,Deactivated',
        ]);

        // Determine shift dynamically according to current server time
        $hour = now()->hour;
        if ($hour >= 6 && $hour < 12) {
            $shift = 'Morning';
        } elseif ($hour >= 12 && $hour < 18) {
            $shift = 'Afternoon';
        } else {
            $shift = 'Evening';
        }

        // Automatically assign start_date as today's date
        $startDate = now()->toDateString();

        // Create and store the batch assignment in MongoDB
        $assignment = BatchAssignment::create([
            'batch_id'   => $validated['batch_id'],
            'username'   => $validated['username'],
            'shift'      => $shift,
            'status'     => 'Active', // Default status is Active
            'start_date' => $startDate,
        ]);

        // If request was via Ajax (modal form submit)
        if ($request->ajax()) {
            return response()->json([
                'status' => 'success',
                'batch'  => [
                    'id'         => $assignment->_id,
                    'batch_id'   => $assignment->batch_id, 
                    'username'   => $assignment->username,
                    'start_date' => $assignment->start_date,
                    'shift'      => $assignment->shift,
                    'status'     => $assignment->status,
                ],
            ]);
        }

        // If not Ajax, fallback to normal redirect
        return redirect()->route('user.batches.batches')
                         ->with('success', 'Batch assigned successfully!');
    }

    /**
     * Store a new batch assignment (used by non-Ajax forms)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'batch_id'   => 'required|string|max:50',
            'start_date' => 'required|date',
            'username'   => 'required|string|max:100',
        ]);

        $hour = now()->hour;
        $shift = ($hour >= 6 && $hour < 12) ? 'Morning' : (($hour >= 12 && $hour < 18) ? 'Afternoon' : 'Evening');

        $batch = new BatchAssignment();
        $batch->batch_id   = $validated['batch_id'];
        $batch->start_date = $validated['start_date'];
        $batch->username   = $validated['username'];
        $batch->shift      = $shift;
        $batch->status     = 'Active';
        $batch->save();

        return redirect()->route('user.batches.batches')
                         ->with('success', 'Batch added successfully!');
    }

    /**
     * Toggle status of a batch assignment (Active ↔ Deactivated)
     */
    public function toggleStatus($id)
    {
        $batch = BatchAssignment::findOrFail($id);
        $batch->status = $batch->status === 'Active' ? 'Deactivated' : 'Active';
        $batch->save();

        return redirect()->back()->with('success', 'Batch status updated successfully.');
    }
}