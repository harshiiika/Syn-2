<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\FeesMaster;
use Illuminate\Http\Request;

class FeesMasterController extends Controller
{
    // public function index()
    // {
    //     try {
    //         $fees = FeesMaster::orderBy('created_at', 'desc')->get();
    //     } catch (\Exception $e) {
    //         \Log::error('Fees Master Error: ' . $e->getMessage());
    //         $fees = collect([]);
    //     }
        
    //     // FIXED: Changed from 'fees.index' to 'master.fees_master.index'
    //     return view('master.fees_master.index', compact('fees'));
    // }

    public function index()
    {
        // IMPORTANT: Use paginate() not get() or all()
        $fees = FeesMaster::paginate(10);
        
        return view('master.fees_master.index', compact('fees'));
    }

     /**
     * Toggle the status of a fee (Active/Inactive)
     */
    public function toggle($id)
    {
        try {
            $fee = FeesMaster::findOrFail($id);
            
            // Toggle the status
            $fee->status = ($fee->status === 'Active') ? 'Inactive' : 'Active';
            $fee->save();
            
            return redirect()->route('fees.index')
                ->with('success', 'Fee status updated successfully!');
                
        } catch (\Exception $e) {
            return redirect()->route('fees.index')
                ->with('error', 'Failed to update fee status: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'course' => 'required|string|max:255',
                'gst_percentage' => 'required|numeric|min:0|max:100',
                'classroom_course' => 'required|numeric|min:0',
                'live_online_course' => 'required|numeric|min:0',
                'recorded_online_course' => 'required|numeric|min:0',
                'study_material_only' => 'required|numeric|min:0',
                'test_series_only' => 'required|numeric|min:0',
            ]);

            // Calculate GST and totals
            $validated['classroom_gst'] = round($validated['classroom_course'] * ($validated['gst_percentage'] / 100), 2);
            $validated['classroom_total'] = round($validated['classroom_course'] + $validated['classroom_gst'], 2);
            
            $validated['live_online_gst'] = round($validated['live_online_course'] * ($validated['gst_percentage'] / 100), 2);
            $validated['live_online_total'] = round($validated['live_online_course'] + $validated['live_online_gst'], 2);
            
            $validated['recorded_online_gst'] = round($validated['recorded_online_course'] * ($validated['gst_percentage'] / 100), 2);
            $validated['recorded_online_total'] = round($validated['recorded_online_course'] + $validated['recorded_online_gst'], 2);
            
            $validated['study_material_gst'] = round($validated['study_material_only'] * ($validated['gst_percentage'] / 100), 2);
            $validated['study_material_total'] = round($validated['study_material_only'] + $validated['study_material_gst'], 2);
            
            $validated['test_series_gst'] = round($validated['test_series_only'] * ($validated['gst_percentage'] / 100), 2);
            $validated['test_series_total'] = round($validated['test_series_only'] + $validated['test_series_gst'], 2);

            $validated['status'] = 'active';
            $validated['created_at'] = now();
            $validated['updated_at'] = now();

            FeesMaster::create($validated);

            return redirect()->route('fees.index')->with('success', 'Fees created successfully!');
        } catch (\Exception $e) {
            \Log::error('Fees Store Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to create fees: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $fee = FeesMaster::findOrFail($id);
            return response()->json($fee);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Fee not found'], 404);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $fee = FeesMaster::findOrFail($id);
            
            $validated = $request->validate([
                'course' => 'required|string|max:255',
                'gst_percentage' => 'required|numeric|min:0|max:100',
                'classroom_course' => 'required|numeric|min:0',
                'live_online_course' => 'required|numeric|min:0',
                'recorded_online_course' => 'required|numeric|min:0',
                'study_material_only' => 'required|numeric|min:0',
                'test_series_only' => 'required|numeric|min:0',
            ]);

            // Recalculate GST and totals
            $validated['classroom_gst'] = round($validated['classroom_course'] * ($validated['gst_percentage'] / 100), 2);
            $validated['classroom_total'] = round($validated['classroom_course'] + $validated['classroom_gst'], 2);
            
            $validated['live_online_gst'] = round($validated['live_online_course'] * ($validated['gst_percentage'] / 100), 2);
            $validated['live_online_total'] = round($validated['live_online_course'] + $validated['live_online_gst'], 2);
            
            $validated['recorded_online_gst'] = round($validated['recorded_online_course'] * ($validated['gst_percentage'] / 100), 2);
            $validated['recorded_online_total'] = round($validated['recorded_online_course'] + $validated['recorded_online_gst'], 2);
            
            $validated['study_material_gst'] = round($validated['study_material_only'] * ($validated['gst_percentage'] / 100), 2);
            $validated['study_material_total'] = round($validated['study_material_only'] + $validated['study_material_gst'], 2);
            
            $validated['test_series_gst'] = round($validated['test_series_only'] * ($validated['gst_percentage'] / 100), 2);
            $validated['test_series_total'] = round($validated['test_series_only'] + $validated['test_series_gst'], 2);

            $validated['updated_at'] = now();

            $fee->update($validated);

            return redirect()->route('fees.index')->with('success', 'Fees updated successfully!');
        } catch (\Exception $e) {
            \Log::error('Fees Update Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to update fees: ' . $e->getMessage());
        }
    }

    public function activate($id)
    {
        try {
            $fee = FeesMaster::findOrFail($id);
            $fee->update(['status' => 'active', 'updated_at' => now()]);

            return redirect()->route('fees.index')->with('success', 'Fees activated successfully!');
        } catch (\Exception $e) {
            \Log::error('Fees Activate Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to activate fees: ' . $e->getMessage());
        }
    }

    public function deactivate($id)
    {
        try {
            $fee = FeesMaster::findOrFail($id);
            $fee->update(['status' => 'inactive', 'updated_at' => now()]);

            return redirect()->route('fees.index')->with('success', 'Fees deactivated successfully!');
        } catch (\Exception $e) {
            \Log::error('Fees Deactivate Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to deactivate fees: ' . $e->getMessage());
        }
    }
}