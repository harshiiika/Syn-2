<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\FeesMaster;
use Illuminate\Http\Request;

class FeesMasterController extends Controller
{
    public function index()
    {
        $fees = FeesMaster::paginate(10);
        return view('master.fees_master.index', compact('fees'));
    }

    public function getData(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $search = $request->get('search', '');

        $query = FeesMaster::query();

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('course', 'like', "%{$search}%")
                  ->orWhere('course_type', 'like', "%{$search}%")
                  ->orWhere('class_name', 'like', "%{$search}%")
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

    public function show($id)
    {
        try {
            $fee = FeesMaster::findOrFail($id);
            
            // Map model fields to frontend field names for compatibility
            $response = [
                'id' => $fee->id,
                'course' => $fee->course,
                'course_type' => $fee->course_type,
                'class_name' => $fee->class_name,
                'status' => $fee->status,
                
                // Map model fields to expected frontend names
                'gst_percentage' => $fee->gst_percent,
                'classroom_course' => $fee->classroom_fee,
                'classroom_gst' => $fee->classroom_gst,
                'classroom_total' => $fee->classroom_total,
                
                'live_online_course' => $fee->live_fee,
                'live_online_gst' => $fee->live_gst,
                'live_online_total' => $fee->live_total,
                
                'recorded_online_course' => $fee->recorded_fee,
                'recorded_online_gst' => $fee->recorded_gst,
                'recorded_online_total' => $fee->recorded_total,
                
                'study_material_only' => $fee->study_fee,
                'study_material_gst' => $fee->study_gst,
                'study_material_total' => $fee->study_total,
                
                'test_series_only' => $fee->test_fee,
                'test_series_gst' => $fee->test_gst,
                'test_series_total' => $fee->test_total,
            ];
            
            return response()->json($response);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Fee not found'], 404);
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

            // Map frontend field names to model field names
            $data = [
                'course' => $validated['course'],
                'gst_percent' => $validated['gst_percentage'],
                'classroom_fee' => $validated['classroom_course'],
                'live_fee' => $validated['live_online_course'],
                'recorded_fee' => $validated['recorded_online_course'],
                'study_fee' => $validated['study_material_only'],
                'test_fee' => $validated['test_series_only'],
                'status' => 'Active',
            ];

            // The model's booted() method will auto-calculate GST and totals
            FeesMaster::create($data);

            return redirect()->route('fees.index')->with('success', 'Fees created successfully!');
        } catch (\Exception $e) {
            \Log::error('Fees Store Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to create fees: ' . $e->getMessage());
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

            // Map frontend field names to model field names
            $data = [
                'course' => $validated['course'],
                'gst_percent' => $validated['gst_percentage'],
                'classroom_fee' => $validated['classroom_course'],
                'live_fee' => $validated['live_online_course'],
                'recorded_fee' => $validated['recorded_online_course'],
                'study_fee' => $validated['study_material_only'],
                'test_fee' => $validated['test_series_only'],
            ];

            // The model's booted() method will auto-calculate GST and totals
            $fee->update($data);

            return redirect()->route('fees.index')->with('success', 'Fees updated successfully!');
        } catch (\Exception $e) {
            \Log::error('Fees Update Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to update fees: ' . $e->getMessage());
        }
    }

    public function toggle($id)
    {
        try {
            $fee = FeesMaster::findOrFail($id);
            $fee->status = ($fee->status === 'Active') ? 'Inactive' : 'Active';
            $fee->save();
            
            return redirect()->route('fees.index')->with('success', 'Fee status updated successfully!');
        } catch (\Exception $e) {
            return redirect()->route('fees.index')->with('error', 'Failed to update fee status: ' . $e->getMessage());
        }
    }
}