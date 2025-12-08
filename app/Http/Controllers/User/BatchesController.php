<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User\BatchAssignment;
use Illuminate\Http\Request;

class BatchesController extends Controller
{
    /**
     * Display all batch assignments with pagination and search
     */

public function index(Request $request)
{
    $perPage = $request->get('per_page', 10);
    $search = $request->get('search');

    $query = BatchAssignment::query();

    // Apply search filter if search term exists
    if ($search) {
        $query->where(function($q) use ($search) {
            $q->where('batch_id', 'like', '%' . $search . '%')
              ->orWhere('username', 'like', '%' . $search . '%')
              ->orWhere('shift', 'like', '%' . $search . '%')
              ->orWhere('status', 'like', '%' . $search . '%');
        });
    }

    // Paginate results
    $batches = $query->orderBy('created_at', 'desc')->paginate($perPage);

    // Preserve query parameters in pagination links
    $batches->appends($request->except('page'));

    //Fetch all active batches from master.batches
    $availableBatches = \App\Models\Master\Batch::where('status', 'Active')
                            ->orderBy('batch_id', 'asc')
                            ->get();

    return view('user.batches.batches', compact('batches', 'availableBatches'));
}

    /**
     * Show all batch assignments (same as index for consistency)
     */
    public function showBatches(Request $request)
    {
        // Just call index() to avoid code duplication
        return $this->index($request);
    }

    /**
     * Handle batch assignment (called via Assign Batch modal form)
     */

public function addBatch(Request $request)
{
    \Log::info('=== ADD BATCH REQUEST ===', [
        'is_ajax' => $request->ajax(),
        'data' => $request->all()
    ]);

    // Validate only fields coming from the modal form
    $validated = $request->validate([
        'batch_id'   => 'required|string|max:50',
        'username'   => 'required|string|max:100',
        'status'     => 'nullable|string|in:Assigned,Active,Deactivated',
    ]);

    // Fetch the batch from master.batches to get its shift
    $masterBatch = \App\Models\Master\Batch::where('batch_id', $validated['batch_id'])->first();

    if (!$masterBatch) {
        if ($request->ajax()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Selected batch not found in master batches.'
            ], 404);
        }
        return redirect()->back()->with('error', 'Selected batch not found.');
    }

    // Use the shift from the master batch
    $shift = $masterBatch->shift;
    $startDate = $masterBatch->start_date;

    // Check if this assignment already exists
    $existingAssignment = BatchAssignment::where('batch_id', $validated['batch_id'])
        ->where('username', $validated['username'])
        ->first();

    if ($existingAssignment) {
        if ($request->ajax()) {
            return response()->json([
                'status' => 'error',
                'message' => 'This batch is already assigned to this user.'
            ], 422);
        }
        return redirect()->back()->with('error', 'This batch is already assigned to this user.');
    }

    // Create and store the batch assignment
    $assignment = BatchAssignment::create([
        'batch_id'   => $validated['batch_id'],
        'username'   => $validated['username'],
        'shift'      => $shift,
        'status'     => 'Active',
        'start_date' => $startDate,
    ]);

    \Log::info('Batch assignment created', ['id' => $assignment->_id]);

    //  ALWAYS return JSON for AJAX requests
    if ($request->ajax()) {
        return response()->json([
            'status' => 'success',
            'message' => 'Batch assigned successfully!',
            'batch'  => [
                'id'         => (string) $assignment->_id,
                'batch_id'   => $assignment->batch_id, 
                'username'   => $assignment->username,
                'start_date' => $assignment->start_date,
                'shift'      => $assignment->shift,
                'status'     => $assignment->status,
            ],
        ]);
    }

    // Fallback for non-AJAX (shouldn't happen with your form)
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
        'username'   => 'required|string|max:100',
    ]);

    // FETCH BATCH DATA FROM MASTER.BATCHES
    $masterBatch = \App\Models\Master\Batch::where('batch_id', $validated['batch_id'])->first();

    if (!$masterBatch) {
        return redirect()->back()->with('error', 'Selected batch not found.');
    }

    $batch = new BatchAssignment();
    $batch->batch_id   = $validated['batch_id'];
    $batch->username   = $validated['username'];
    $batch->shift      = $masterBatch->shift;  // From master batch
    $batch->start_date = $masterBatch->start_date;  // From master batch
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

    /**
     * Handle batch assignment (called via Assign Batch modal form)
     */
    // public function addBatch(Request $request)
    // {
    //     // Validate only fields coming from the modal form
    //     $validated = $request->validate([
    //         'batch_id'   => 'required|string|max:50',
    //         'username'   => 'required|string|max:100',
    //         'status'     => 'nullable|string|in:Assigned,Active,Deactivated',
    //     ]);

    //     // Determine shift dynamically according to current server time
    //     $hour = now()->hour;
    //     if ($hour >= 6 && $hour < 12) {
    //         $shift = 'Morning';
    //     } elseif ($hour >= 12 && $hour < 18) {
    //         $shift = 'Afternoon';
    //     } else {
    //         $shift = 'Evening';
    //     }

    //     // Automatically assign start_date as today's date
    //     $startDate = now()->toDateString();

    //     // Create and store the batch assignment in MongoDB
    //     $assignment = BatchAssignment::create([
    //         'batch_id'   => $validated['batch_id'],
    //         'username'   => $validated['username'],
    //         'shift'      => $shift,
    //         'status'     => 'Active', // Default status is Active
    //         'start_date' => $startDate,
    //     ]);

    //     // If request was via Ajax (modal form submit)
    //     if ($request->ajax()) {
    //         return response()->json([
    //             'status' => 'success',
    //             'batch'  => [
    //                 'id'         => $assignment->_id,
    //                 'batch_id'   => $assignment->batch_id, 
    //                 'username'   => $assignment->username,
    //                 'start_date' => $assignment->start_date,
    //                 'shift'      => $assignment->shift,
    //                 'status'     => $assignment->status,
    //             ],
    //         ]);
    //     }

    //     // If not Ajax, fallback to normal redirect
    //     return redirect()->route('user.batches.batches')
    //                      ->with('success', 'Batch assigned successfully!');
    // }

    /**
     * Store a new batch assignment (used by non-Ajax forms)
     */
    // public function store(Request $request)
    // {
    //     $validated = $request->validate([
    //         'batch_id'   => 'required|string|max:50',
    //         'start_date' => 'required|date',
    //         'username'   => 'required|string|max:100',
    //     ]);

    //     $hour = now()->hour;
    //     $shift = ($hour >= 6 && $hour < 12) ? 'Morning' : (($hour >= 12 && $hour < 18) ? 'Afternoon' : 'Evening');

    //     $batch = new BatchAssignment();
    //     $batch->batch_id   = $validated['batch_id'];
    //     $batch->start_date = $validated['start_date'];
    //     $batch->username   = $validated['username'];
    //     $batch->shift      = $shift;
    //     $batch->status     = 'Active';
    //     $batch->save();

    //     return redirect()->route('user.batches.batches')
    //                      ->with('success', 'Batch added successfully!');
    // }

    /**
     * Toggle status of a batch assignment (Active ↔ Deactivated)
     */
    // public function toggleStatus($id)
    // {
    //     $batch = BatchAssignment::findOrFail($id);
    //     $batch->status = $batch->status === 'Active' ? 'Deactivated' : 'Active';
    //     $batch->save();

    //     return redirect()->back()->with('success', 'Batch status updated successfully.');
    // }
}