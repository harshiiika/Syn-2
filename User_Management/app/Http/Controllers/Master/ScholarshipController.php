<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\Scholarship;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
                $query->where('scholarship_name', 'like', '%' . $request->search . '%');
            }

            $perPage = $request->get('per_page', 10);
            $scholarships = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => $scholarships->items(),
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
                'data' => $scholarship,
            ], 200);
        } catch (\Exception $e) {
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
            $scholarship = Scholarship::create($validator->validated());

            return response()->json([
                'success' => true,
                'message' => 'Scholarship created successfully',
                'data' => $scholarship,
            ], 201); // ✅ FIXED: Changed from 200 to 201
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create scholarship',
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

            return response()->json([
                'success' => true,
                'message' => 'Scholarship updated successfully',
                'data' => $scholarship,
            ], 200);
        } catch (\Exception $e) {
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
}