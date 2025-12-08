<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\FeesMaster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FeesMasterController extends Controller
{
    /**
     * Display a listing of the fees
     */
    public function index(Request $request)
    {
        try {
            $perPage = $request->get('per_page', 10);
            $search = $request->get('search');

            $query = FeesMaster::query()->orderBy('created_at', 'desc');

            if ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('course', 'like', "%{$search}%")
                      ->orWhere('status', 'like', "%{$search}%");
                });
            }

            $fees = $query->paginate($perPage)->withQueryString();
            
            return view('master.fees_master.index', compact('fees'));
        } catch (\Exception $e) {
            Log::error('Fees Master Index Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to load fees: ' . $e->getMessage());
        }
    }

    /**
     * Store a newly created fee in storage
     */
   public function store(Request $request)
{
    try {
        $validated = $request->validate([
            'course' => 'required|string|max:255',
            'gst_percentage' => 'required|numeric|min:0|max:100',
            'classroom_course' => 'nullable|numeric|min:0',
            'live_online_course' => 'nullable|numeric|min:0',
            'recorded_online_course' => 'nullable|numeric|min:0',
            'study_material_only' => 'nullable|numeric|min:0',
            'test_series_only' => 'nullable|numeric|min:0',
        ]);

        //   AUTO-DETECT course_type and class_name from Model's $courseConfigs
        $config = FeesMaster::getCourseConfig($validated['course']);
        
        $data = [
            'course' => $validated['course'],
            'course_type' => $config['course_type'] ?? null,  
            'class_name' => $config['class_name'] ?? null,    
            'gst_percent' => $validated['gst_percentage'],
            'classroom_fee' => $validated['classroom_course'] ?? 0,
            'live_fee' => $validated['live_online_course'] ?? 0,
            'recorded_fee' => $validated['recorded_online_course'] ?? 0,
            'study_fee' => $validated['study_material_only'] ?? 0,
            'test_fee' => $validated['test_series_only'] ?? 0,
            'status' => 'Active',
        ];

        FeesMaster::create($data);

        return redirect()->route('fees.index')->with('success', 'Fees created successfully!');
    } catch (\Illuminate\Validation\ValidationException $e) {
        return redirect()->back()
            ->withErrors($e->errors())
            ->withInput()
            ->with('error', 'Validation failed. Please check the form.');
    } catch (\Exception $e) {
        Log::error('Fees Store Error: ' . $e->getMessage());
        return redirect()->back()
            ->withInput()
            ->with('error', 'Failed to create fees: ' . $e->getMessage());
    }
}

    /**
     * Display the specified fee (JSON for AJAX)
     */
    /**
 * Display the specified fee (JSON for AJAX)
 */
public function show($id)
{
    try {
        $fee = FeesMaster::findOrFail($id);
        
        // Calculate GST amounts and totals
        $gstPercent = floatval($fee->gst_percent);
        
        $classroomFee = floatval($fee->classroom_fee);
        $classroomGst = ($classroomFee * $gstPercent / 100);
        $classroomTotal = $classroomFee + $classroomGst;
        
        $liveFee = floatval($fee->live_fee);
        $liveGst = ($liveFee * $gstPercent / 100);
        $liveTotal = $liveFee + $liveGst;
        
        $recordedFee = floatval($fee->recorded_fee);
        $recordedGst = ($recordedFee * $gstPercent / 100);
        $recordedTotal = $recordedFee + $recordedGst;
        
        $studyFee = floatval($fee->study_fee);
        $studyGst = ($studyFee * $gstPercent / 100);
        $studyTotal = $studyFee + $studyGst;
        
        $testFee = floatval($fee->test_fee);
        $testGst = ($testFee * $gstPercent / 100);
        $testTotal = $testFee + $testGst;
        
        // Calculate installments (40%, 30%, 30% split)
        $classroomInstallment1 = round($classroomTotal * 0.40);
        $classroomInstallment2 = round($classroomTotal * 0.30);
        $classroomInstallment3 = $classroomTotal - $classroomInstallment1 - $classroomInstallment2;
        
        $liveInstallment1 = round($liveTotal * 0.40);
        $liveInstallment2 = round($liveTotal * 0.30);
        $liveInstallment3 = $liveTotal - $liveInstallment1 - $liveInstallment2;
        
        $recordedInstallment1 = round($recordedTotal * 0.40);
        $recordedInstallment2 = round($recordedTotal * 0.30);
        $recordedInstallment3 = $recordedTotal - $recordedInstallment1 - $recordedInstallment2;
        
        return response()->json([
            'id' => $fee->id,
            'course' => $fee->course,
            'course_type' => $fee->course_type ?? 'N/A',
            'class_name' => $fee->class_name ?? 'N/A',
            'gst_percent' => $gstPercent,
            
            'classroom_fee' => $classroomFee,
            'classroom_gst' => round($classroomGst, 2),
            'classroom_total' => round($classroomTotal, 2),
            
            'live_fee' => $liveFee,
            'live_gst' => round($liveGst, 2),
            'live_total' => round($liveTotal, 2),
            
            'recorded_fee' => $recordedFee,
            'recorded_gst' => round($recordedGst, 2),
            'recorded_total' => round($recordedTotal, 2),
            
            'study_fee' => $studyFee,
            'study_gst' => round($studyGst, 2),
            'study_total' => round($studyTotal, 2),
            
            'test_fee' => $testFee,
            'test_gst' => round($testGst, 2),
            'test_total' => round($testTotal, 2),
            
            // Installments
            'classroom_installment1' => $classroomInstallment1,
            'classroom_installment2' => $classroomInstallment2,
            'classroom_installment3' => $classroomInstallment3,
            
            'live_installment1' => $liveInstallment1,
            'live_installment2' => $liveInstallment2,
            'live_installment3' => $liveInstallment3,
            
            'recorded_installment1' => $recordedInstallment1,
            'recorded_installment2' => $recordedInstallment2,
            'recorded_installment3' => $recordedInstallment3,
            
            'status' => $fee->status,
        ]);
    } catch (\Exception $e) {
        Log::error('Fees Show Error: ' . $e->getMessage());
        return response()->json(['error' => 'Fee not found'], 404);
    }
}

    /**
     * Update the specified fee in storage
     */
   public function update(Request $request, $id)
{
    try {
        $fee = FeesMaster::findOrFail($id);
        
        $validated = $request->validate([
            'course' => 'required|string|max:255',
            'gst_percentage' => 'required|numeric|min:0|max:100',
            'classroom_course' => 'nullable|numeric|min:0',
            'live_online_course' => 'nullable|numeric|min:0',
            'recorded_online_course' => 'nullable|numeric|min:0',
            'study_material_only' => 'nullable|numeric|min:0',
            'test_series_only' => 'nullable|numeric|min:0',
        ]);

        //   AUTO-DETECT course_type and class_name
        $config = FeesMaster::getCourseConfig($validated['course']);
        
        $data = [
            'course' => $validated['course'],
            'course_type' => $config['course_type'] ?? null, 
            'class_name' => $config['class_name'] ?? null,  
            'gst_percent' => $validated['gst_percentage'],
            'classroom_fee' => $validated['classroom_course'] ?? 0,
            'live_fee' => $validated['live_online_course'] ?? 0,
            'recorded_fee' => $validated['recorded_online_course'] ?? 0,
            'study_fee' => $validated['study_material_only'] ?? 0,
            'test_fee' => $validated['test_series_only'] ?? 0,
        ];

        $fee->update($data);

        return redirect()->route('fees.index')->with('success', 'Fees updated successfully!');
    } catch (\Illuminate\Validation\ValidationException $e) {
        return redirect()->back()
            ->withErrors($e->errors())
            ->with('error', 'Validation failed. Please check the form.');
    } catch (\Exception $e) {
        Log::error('Fees Update Error: ' . $e->getMessage());
        return redirect()->back()->with('error', 'Failed to update fees: ' . $e->getMessage());
    }
}
    /**
     * Toggle the status of a fee (Active/Inactive)
     */
    public function toggle($id)
    {
        try {
            $fee = FeesMaster::findOrFail($id);
            
            $fee->status = ($fee->status === 'Active') ? 'Inactive' : 'Active';
            $fee->save();
            
            return redirect()->route('fees.index')
                ->with('success', "Fee status changed to {$fee->status} successfully!");
                
        } catch (\Exception $e) {
            Log::error('Fees Toggle Error: ' . $e->getMessage());
            return redirect()->route('fees.index')
                ->with('error', 'Failed to update fee status: ' . $e->getMessage());
        }
    }
}