<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\OtherFee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OtherFeeController extends Controller
{
    /**
     * Display the main page
     */
    public function index()
    {
        $otherFees = OtherFee::paginate(10);
        return view('master.other_fees.index', compact('otherFees'));
    }

    /**
     * Get paginated data for AJAX (for table)
     */
    public function getData(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $search = $request->get('search', '');

        $query = OtherFee::query();

        // Search filter
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('fee_type', 'like', "%{$search}%")
                  ->orWhere('amount', 'like', "%{$search}%")
                  ->orWhere('status', 'like', "%{$search}%");
            });
        }

        $query->orderBy('created_at', 'desc');
        $fees = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $fees->items(),
            'current_page' => $fees->currentPage(),
            'per_page' => $fees->perPage(),
            'total' => $fees->total(),
            'last_page' => $fees->lastPage()
        ]);
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
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            OtherFee::create([
                'fee_type' => $request->fee_type,
                'amount' => $request->amount,
                'status' => 'active',
            ]);

            return redirect()->route('master.other_fees.index')
                ->with('success', 'Other fee created successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to create other fee: ' . $e->getMessage());
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
                'fee_type' => 'required|string',
                'amount' => 'required|numeric|min:0',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $fee->update([
                'fee_type' => $request->fee_type,
                'amount' => $request->amount,
            ]);

            return redirect()->route('master.other_fees.index')
                ->with('success', 'Fee updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error updating fee: ' . $e->getMessage());
        }
    }

    /**
     * Toggle fee status (active/inactive)
     */
  public function toggle($id)
{
    try {
        $fee = OtherFee::findOrFail($id);
        $fee->status = ($fee->status === 'active') ? 'inactive' : 'active';
        $fee->save();

        return redirect()->route('master.other_fees.index')
            ->with('success', 'Fee status updated successfully to ' . ucfirst($fee->status));
    } catch (\Exception $e) {
        return redirect()->back()
            ->with('error', 'Failed to update status: ' . $e->getMessage());
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