<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\Scholarship;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class ScholarshipController extends Controller
{
    /**
     * Display all scholarships or return paginated JSON
     */
   public function index(Request $request)
{
    if ($request->expectsJson() || $request->wantsJson()) {
        $query = Scholarship::query();

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('scholarship_name', 'like', '%' . $search . '%')
                  ->orWhere('short_name', 'like', '%' . $search . '%')
                  ->orWhere('scholarship_type', 'like', '%' . $search . '%');
            });
        }

        $perPage = $request->get('per_page', 10);
        $scholarships = $query->paginate($perPage);

        //   Transform each item to ensure all fields exist
        $transformedData = collect($scholarships->items())->map(function($scholarship) {
            return [
                'id' => $scholarship->_id ?? $scholarship->id,
                '_id' => $scholarship->_id ?? $scholarship->id,
                'scholarship_name' => $scholarship->scholarship_name ?? '',
                'short_name' => $scholarship->short_name ?? '',
                'scholarship_type' => $scholarship->scholarship_type ?? '',
                'category' => $scholarship->category ?? '',
                'applicable_for' => $scholarship->applicable_for ?? '',
                'description' => $scholarship->description ?? '',
                'status' => $scholarship->status ?? 'active',
                'created_at' => $scholarship->created_at,
                'updated_at' => $scholarship->updated_at,
            ];
        })->toArray();

        Log::info('Scholarships Data', ['data' => $transformedData]);

        return response()->json([
            'success' => true,
            'data' => $transformedData,
            'current_page' => $scholarships->currentPage(),
            'last_page' => $scholarships->lastPage(),
            'per_page' => $scholarships->perPage(),
            'total' => $scholarships->total(),
        ], 200);
    }

    $scholarships = Scholarship::all();
    return view('master.scholarship.index', compact('scholarships'));
}

    /**
     * Show single scholarship
     */
   public function show($id)
{
    try {
        $scholarship = Scholarship::findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $scholarship->_id ?? $scholarship->id,
                '_id' => $scholarship->_id ?? $scholarship->id,
                'scholarship_name' => $scholarship->scholarship_name ?? '',
                'short_name' => $scholarship->short_name ?? '',
                'scholarship_type' => $scholarship->scholarship_type ?? '',
                'category' => $scholarship->category ?? '',
                'applicable_for' => $scholarship->applicable_for ?? '',
                'description' => $scholarship->description ?? '',
                'status' => $scholarship->status ?? 'active',
                'created_at' => $scholarship->created_at,
                'updated_at' => $scholarship->updated_at,
            ],
        ], 200);
    } catch (\Exception $e) {
        Log::error('Scholarship not found', ['id' => $id, 'error' => $e->getMessage()]);
        return response()->json([
            'success' => false,
            'message' => 'Scholarship not found',
        ], 404);
    }
}
    /**
     * Store a new scholarship
     */
   public function store(Request $request)
{
    $validator = Validator::make($request->all(), [
        'scholarship_type' => 'required|string',
        'scholarship_name' => 'required|string|unique:scholarships,scholarship_name',
        'short_name' => 'required|string',
        'category' => 'required|string',
        'applicable_for' => 'required|string',
        'description' => 'nullable|string',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'message' => 'Validation failed',
            'errors' => $validator->errors(),
        ], 422);
    }

    try {
        $data = $validator->validated();
        
        //   Ensure status is set
        $data['status'] = 'active';
        
        //   Log what we're about to save
        Log::info('Creating scholarship with data:', $data);
        
        $scholarship = Scholarship::create($data);

        Log::info('Scholarship created successfully:', ['scholarship' => $scholarship]);

        return response()->json([
            'success' => true,
            'message' => 'Scholarship created successfully',
            'data' => $scholarship,
        ], 201);
    } catch (\Exception $e) {
        Log::error('Failed to create scholarship', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        
        return response()->json([
            'success' => false,
            'message' => 'Failed to create scholarship: ' . $e->getMessage(),
        ], 500);
    }
}

    /**
     * Update a scholarship
     */
    public function update(Request $request, $id)
    {
        try {
            $scholarship = Scholarship::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'scholarship_type' => 'required|string',
                'scholarship_name' => 'required|string|unique:scholarships,scholarship_name,' . $id . ',_id',
                'short_name' => 'required|string',
                'category' => 'required|string',
                'applicable_for' => 'required|string',
                'description' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $scholarship->update($validator->validated());

            Log::info('Scholarship updated', ['scholarship' => $scholarship]);

            return response()->json([
                'success' => true,
                'message' => 'Scholarship updated successfully',
                'data' => $scholarship,
            ], 200);
        } catch (\Exception $e) {
            Log::error('Failed to update scholarship', ['error' => $e->getMessage()]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error updating scholarship: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Toggle scholarship active/inactive
     */
    public function toggleStatus($id)
    {
        try {
            $scholarship = Scholarship::findOrFail($id);
            $scholarship->status = $scholarship->status === 'active' ? 'inactive' : 'active';
            $scholarship->save();

            return response()->json([
                'success' => true,
                'message' => 'Status updated successfully',
                'new_status' => $scholarship->status,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error toggling status: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete a scholarship
     */
    public function destroy($id)
    {
        try {
            $scholarship = Scholarship::findOrFail($id);
            $scholarship->delete();

            return response()->json([
                'success' => true,
                'message' => 'Scholarship deleted successfully',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting scholarship: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     *   NEW: Fix existing records with missing data
     * Run this once to fix old records: GET /master/scholarship/fix-missing-data
     */
    public function fixMissingData()
    {
        try {
            $scholarships = Scholarship::all();
            $fixed = 0;

            foreach ($scholarships as $scholarship) {
                $needsUpdate = false;

                // Fix missing short_name
                if (empty($scholarship->short_name)) {
                    $scholarship->short_name = substr($scholarship->scholarship_name, 0, 10);
                    $needsUpdate = true;
                }

                // Fix missing category
                if (empty($scholarship->category)) {
                    $scholarship->category = 'General';
                    $needsUpdate = true;
                }

                // Fix missing applicable_for
                if (empty($scholarship->applicable_for)) {
                    $scholarship->applicable_for = 'All';
                    $needsUpdate = true;
                }

                // Fix missing status
                if (empty($scholarship->status)) {
                    $scholarship->status = 'active';
                    $needsUpdate = true;
                }

                if ($needsUpdate) {
                    $scholarship->save();
                    $fixed++;
                }
            }

            return response()->json([
                'success' => true,
                'message' => "Fixed {$fixed} scholarships with missing data",
                'total_scholarships' => $scholarships->count(),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fixing data: ' . $e->getMessage(),
            ], 500);
        }
    }
}