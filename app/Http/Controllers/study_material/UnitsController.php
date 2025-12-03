<?php

namespace App\Http\Controllers\study_material;

use App\Http\Controllers\Controller;
use App\Models\study_material\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use MongoDB\BSON\ObjectId;

class Unitscontroller extends Controller
{
    /**
     * Display a listing of units
     */
    public function index()
    {
        return view('study_material.unit');
    }

    /**
     * Get courses list
     */
    public function getCourses()
    {
        try {
            $courses = Unit::distinct('course_name')->get();
            
            return response()->json([
                'success' => true,
                'data' => $courses
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get subjects by course
     */
    public function getSubjectsByCourse(Request $request)
    {
        try {
            $courseName = $request->input('course_name');
            $subjects = Unit::where('course_name', $courseName)
                          ->distinct('subject')
                          ->get();
            
            return response()->json([
                'success' => true,
                'data' => $subjects
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get units data for DataTables
     */
    public function getData(Request $request)
    {
        $query = Unit::query();

        if ($request->has('session') && $request->session) {
            $query->where('session', $request->session);
        }

        if ($request->has('search') && $request->search['value']) {
            $searchValue = $request->search['value'];
            $query->where(function($q) use ($searchValue) {
                $q->where('course_name', 'like', '%' . $searchValue . '%')
                  ->orWhere('subject', 'like', '%' . $searchValue . '%')
                  ->orWhere('units', 'like', '%' . $searchValue . '%');
            });
        }

        $totalRecords = Unit::count();
        $filteredRecords = $query->count();

        $units = $query->orderBy('created_at', 'desc')
                      ->skip($request->start ?? 0)
                      ->take($request->length ?? 10)
                      ->get();

        $data = [];
        $serialNo = ($request->start ?? 0) + 1;
        
        foreach ($units as $unit) {
            $data[] = [
                'serial_no' => $serialNo++,
                'course_name' => $unit->course_name,
                'subject' => $unit->subject,
                'action' => $unit->_id
            ];
        }

        return response()->json([
            'draw' => $request->draw,
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data
        ]);
    }

    /**
     * Store a newly created unit
     */
    public function store(Request $request)
    {
        // FIXED: Corrected the logging syntax
        Log::info('🔵 STORE METHOD CALLED', [
            'url' => request()->url(),
            'full_url' => request()->fullUrl(),
            'method' => request()->method(),
            'has_id_in_route' => request()->route('id'),
            '_method_param' => $request->input('_method'),
            'all_data' => $request->all()
        ]);

        $request->validate([
            'course_name' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'units' => 'required|array',
            'units.*.unit_number' => 'required',
            'units.*.unit_name' => 'required|string',
            'session' => 'required|string'
        ]);

        try {
            $unit = Unit::create([
                'course_name' => $request->course_name,
                'subject' => $request->subject,
                'units' => $request->units,
                'session' => $request->session,
                'created_by' => Auth::user()->email ?? 'admin'
            ]);

            Log::info('✅ Unit created successfully', ['id' => $unit->_id]);

            return response()->json([
                'success' => true,
                'message' => 'Unit added successfully!',
                'data' => $unit
            ]);
        } catch (\Exception $e) {
            Log::error('❌ Store failed', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to add unit: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified unit
     */
    public function show($id)
    {
        try {
            $unit = Unit::findOrFail($id);
            return response()->json([
                'success' => true,
                'data' => $unit
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Unit not found'
            ], 404);
        }
    }

    /**
     * Update the specified unit
     */
    public function update(Request $request, $id)
    {
        // DEBUG LOG
        Log::info('🟢 UPDATE METHOD CALLED', [
            'id' => $id,
            'url' => request()->url(),
            'full_url' => request()->fullUrl(),
            'method' => request()->method(),
            '_method_param' => $request->input('_method'),
            'all_data' => $request->all()
        ]);

        $request->validate([
            'course_name' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'units' => 'required|array',
            'units.*.unit_number' => 'required',
            'units.*.unit_name' => 'required|string',
            'session' => 'required|string'
        ]);

        try {
            $unit = Unit::findOrFail($id);
            
            Log::info('📝 Updating unit', [
                'id' => $id,
                'before' => [
                    'course' => $unit->course_name,
                    'subject' => $unit->subject
                ],
                'after' => [
                    'course' => $request->course_name,
                    'subject' => $request->subject
                ]
            ]);
            
            $unit->update([
                'course_name' => $request->course_name,
                'subject' => $request->subject,
                'units' => $request->units,
                'session' => $request->session,
                'updated_by' => Auth::user()->email ?? 'admin'
            ]);

            Log::info('✅ Unit updated successfully', ['id' => $id]);

            return response()->json([
                'success' => true,
                'message' => 'Unit updated successfully!',
                'data' => $unit
            ]);
        } catch (\Exception $e) {
            Log::error('❌ Update failed', [
                'id' => $id,
                'error' => $e->getMessage()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to update unit: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified unit
     */
    public function destroy($id)
    {
        try {
            $unit = Unit::findOrFail($id);
            $unit->delete();

            Log::info('🗑️ Unit deleted successfully', ['id' => $id]);

            return response()->json([
                'success' => true,
                'message' => 'Unit deleted successfully!'
            ]);
        } catch (\Exception $e) {
            Log::error('❌ Delete failed', [
                'id' => $id,
                'error' => $e->getMessage()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete unit: ' . $e->getMessage()
            ], 500);
        }
    }
}