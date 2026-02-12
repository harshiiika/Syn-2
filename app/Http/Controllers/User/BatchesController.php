<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User\BatchAssignment;
use Illuminate\Http\Request;
use App\Models\Master\Batch;
use App\Models\User\User;
use Illuminate\Support\Facades\DB;

class BatchesController extends Controller
{
    /**
     * Display all batch assignments with pagination and search
     */

 public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        if (!in_array($perPage, [5, 10, 25, 50, 100])) {
            $perPage = 10;
        }

        $search = $request->input('search', '');
        $query = BatchAssignment::query();

        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('batch_id', 'like', '%' . $search . '%')
                  ->orWhere('username', 'like', '%' . $search . '%')
                  ->orWhere('shift', 'like', '%' . $search . '%');
            });
        }

        $batches = $query->orderBy('created_at', 'desc')
                        ->paginate($perPage)
                        ->appends(['search' => $search, 'per_page' => $perPage]);

        // GET BATCHES - Try all methods
        $availableBatches = $this->getAllBatches();
        
        // GET USERS
        $floorIncharges = User::where('status', 'Active')->get(['_id', 'name']);

        return view('user.batches.batches', compact('batches', 'availableBatches', 'floorIncharges', 'search'));
    }

   private function getAllBatches()
{
    // Simply use the Batch model - MongoDB Laravel works through Eloquent!
    try {
        return Batch::where('status', 'Active')
                    ->orderBy('batch_id', 'asc')
                    ->get();
    } catch (\Exception $e) {
        \Log::error('Failed to fetch batches: ' . $e->getMessage());
        return collect([]);
    }
}


    /**
     * Show all batch assignments (same as index for consistency)
     */
        public function showBatches(Request $request)
    {
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
    $masterBatch = Batch::where('batch_id', $validated['batch_id'])->first();

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
    $masterBatch = Batch::where('batch_id', $validated['batch_id'])->first();

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
        $assignment = BatchAssignment::findOrFail($id);
        $assignment->status = $assignment->status === 'Active' ? 'Deactivated' : 'Active';
        $assignment->save();
        return redirect()->back()->with('success', 'Status updated');
    }

 public function assignBatch(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string',
            'batch_id' => 'required|string',
        ]);

        // Get batch details
        try {
            $batch = DB::connection('mongodb')
                       ->collection('batches')
                       ->where('batch_id', $validated['batch_id'])
                       ->first();
        } catch (\Exception $e) {
            $batch = DB::table('batches')
                       ->where('batch_id', $validated['batch_id'])
                       ->first();
        }

        if (!$batch) {
            return response()->json([
                'status' => 'error',
                'message' => 'Batch not found'
            ], 404);
        }

        // Check if batch already assigned
        $existingAssignment = BatchAssignment::where('batch_id', $validated['batch_id'])
                                            ->where('status', 'Active')
                                            ->first();

        if ($existingAssignment) {
            return response()->json([
                'status' => 'error',
                'message' => 'This batch is already assigned to ' . $existingAssignment->username
            ], 422);
        }

        // Convert batch object to array if needed
        $batchArray = is_array($batch) ? $batch : (array) $batch;

        // Create assignment
        $assignment = BatchAssignment::create([
            'username' => $validated['username'],
            'batch_id' => $validated['batch_id'],
            'shift' => $batchArray['shift'] ?? 'Morning',
            'start_date' => $batchArray['start_date'] ?? now()->format('Y-m-d'),
            'status' => 'Active'
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Batch assigned successfully',
            'batch' => $assignment
        ]);
    }
}