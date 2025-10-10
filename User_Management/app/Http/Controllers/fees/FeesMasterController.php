<?php

namespace App\Http\Controllers\Fees;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Fees\FeesMaster;

class FeesMasterController extends Controller
{
    /** LIST */
    public function index()
    {
        $fees = FeesMaster::orderByDesc('created_at')->paginate(10);
        return view('fees_master.index', compact('fees'));
    }

    /** (Optional) CREATE PAGE – you use a modal; keeping this route is fine */
    public function create()
    {
        return view('fees_master.create');
    }

    /** STORE */
    public function store(Request $request)
    {
        $data = $this->validated($request);
        FeesMaster::create($data);

        return redirect()->route('fees.index')->with('status', 'Fees created successfully.');
    }

    /** SHOW (JSON for the “View” button) */
    public function show(FeesMaster $fee)
    {
        return response()->json($fee);
    }

    /** UPDATE */
    public function update(Request $request, FeesMaster $fee)
    {
        $data = $this->validated($request);
        $fee->update($data);

        return redirect()->route('fees.index')->with('status', 'Fees updated successfully.');
    }

    /** TOGGLE STATUS */
    public function toggleStatus(FeesMaster $fee)
    {
        $fee->status = $fee->status === 'Active' ? 'Inactive' : 'Active';
        $fee->save();

        return redirect()->route('fees.index')->with('status', 'Status updated successfully.');
    }

    /** Validation */
    private function validated(Request $request): array
    {
        return $request->validate([
            'course'        => 'required|string|max:100',
            'gst_percent'   => 'required|numeric|min:0|max:100',
            'classroom_fee' => 'nullable|numeric|min:0',
            'live_fee'      => 'nullable|numeric|min:0',
            'recorded_fee'  => 'nullable|numeric|min:0',
            'study_fee'     => 'nullable|numeric|min:0',
            'test_fee'      => 'nullable|numeric|min:0',
            'status'        => 'required|in:Active,Inactive',
        ]);
    }
}
