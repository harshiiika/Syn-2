<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\OtherFee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OtherFeeController extends Controller
{
    /**
     * Display all other fees or return paginated JSON
     */
    public function index(Request $request)
    {
        if ($request->expectsJson() || $request->wantsJson()) {
            $query = OtherFee::query();

            if ($request->has('search') && $request->search) {
                $query->where('fee_type', 'like', '%' . $request->search . '%');
            }

            if ($request->has('session_id') && $request->session_id) {
                $query->where('session_id', $request->session_id);
            }

            if ($request->has('branch_id') && $request->branch_id) {
                $query->where('branch_id', $request->branch_id);
            }

            $perPage = $request->get('per_page', 10);
            $otherFees = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => $otherFees->items(),
                'current_page' => $otherFees->currentPage(),
                'last_page' => $otherFees->lastPage(),
                'per_page' => $otherFees->perPage(),
                'total' => $otherFees->total(),
            ], 200);
        }

        // For page load, return the view with all fees
        $otherFees = OtherFee::all();
        return view('master.other_fees.index', compact('otherFees'));
    }

    /**
     * Show single other fee
     */
    public function show($id)
    {
        try {
            $otherFee = OtherFee::findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $otherFee,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Other fee not found',
            ], 404);
        }
    }

    /**
     * Store a new other fee
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fee_type' => 'required|string',
            'amount' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $otherFee = OtherFee::create($validator->validated());

            return response()->json([
                'success' => true,
                'message' => 'Other fee created successfully',
                'data' => $otherFee,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create other fee: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update an other fee
     */
    public function update(Request $request, $id)
    {
        try {
            $otherFee = OtherFee::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'fee_type' => 'required|string',
                'amount' => 'required|numeric|min:0',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $otherFee->update($validator->validated());

            return response()->json([
                'success' => true,
                'message' => 'Other fee updated successfully',
                'data' => $otherFee,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating other fee: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete an other fee
     */
    public function destroy($id)
    {
        try {
            $otherFee = OtherFee::findOrFail($id);
            $otherFee->delete();

            return response()->json([
                'success' => true,
                'message' => 'Other fee deleted successfully',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting other fee: ' . $e->getMessage(),
            ], 500);
        }
    }
}