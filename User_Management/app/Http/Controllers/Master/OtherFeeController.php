<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\OtherFee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OtherFeeController extends Controller
{
    /**
     * Display list of other fees or return paginated JSON
     */
    public function index(Request $request)
    {
        // If API request, return JSON
        if ($request->expectsJson() || $request->wantsJson()) {
            $query = OtherFee::query();

            if ($request->has('search') && $request->search) {
                $query->where('fee_type', 'like', '%' . $request->search . '%');
            }

            $perPage = $request->get('per_page', 10);
            $fees = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => $fees->items(),
                'current_page' => $fees->currentPage(),
                'last_page' => $fees->lastPage(),
                'per_page' => $fees->perPage(),
                'total' => $fees->total(),
            ], 200);
        }

        // For page load, return the view with all fees
        $otherFees = OtherFee::all();
        return view('master.other_fees.index', compact('otherFees'));
    }

    /**
     * Show a single fee record
     */
    public function show($id)
    {
        try {
            $fee = OtherFee::findOrFail($id);
            
            return response()->json([
                'success' => true,
                'data' => $fee,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Fee not found',
            ], 404);
        }
    }

    /**
     * Store a new Other Fee
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fee_type' => 'required|string|unique:other_fees,fee_type',
            'amount' => 'required|numeric|min:0',
            'status' => 'nullable|in:active,inactive',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $fee = OtherFee::create([
                'fee_type' => $request->fee_type,
                'amount' => $request->amount,
                'status' => $request->status ?? 'active',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Other fee created successfully',
                'data' => $fee,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create other fee',
            ], 500);
        }
    }

    /**
     * Update an existing fee
     */
    public function update(Request $request, $id)
    {
        try {
            $fee = OtherFee::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'fee_type' => 'required|string|unique:other_fees,fee_type,' . $id . ',_id',
                'amount' => 'required|numeric|min:0',
                'status' => 'nullable|in:active,inactive',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $fee->update([
                'fee_type' => $request->fee_type,
                'amount' => $request->amount,
                'status' => $request->status ?? $fee->status,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Fee updated successfully',
                'data' => $fee,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating fee: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Toggle fee status (active/inactive)
     */
    public function toggle($id)
    {
        try {
            $fee = OtherFee::findOrFail($id);
            $fee->status = $fee->status === 'active' ? 'inactive' : 'active';
            $fee->save();

            return response()->json([
                'success' => true,
                'message' => 'Status updated successfully',
                'new_status' => $fee->status,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error toggling status: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete a fee
     */
    public function destroy($id)
    {
        try {
            $fee = OtherFee::findOrFail($id);
            $fee->delete();

            return response()->json([
                'success' => true,
                'message' => 'Fee deleted successfully',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting fee: ' . $e->getMessage(),
            ], 500);
        }
    }
}