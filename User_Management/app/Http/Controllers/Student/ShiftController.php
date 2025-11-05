<?php

namespace App\Http\Controllers;

use App\Models\Student\Shift;
use App\Models\Student\SMstudents;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ShiftController extends Controller
{
    /**
     * Display a listing of shifts
     */
    public function index()
    {
        try {
            $shifts = Shift::orderBy('name', 'asc')->get();
            
            Log::info('Shifts loaded:', [
                'count' => $shifts->count()
            ]);
            
            return view('shifts.index', compact('shifts'));
            
        } catch (\Exception $e) {
            Log::error('Error loading shifts: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to load shifts: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new shift
     */
    public function create()
    {
        return view('shifts.create');
    }

    /**
     * Store a newly created shift in database
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255|unique:shifts,name',
                'start_time' => 'nullable|date_format:H:i',
                'end_time' => 'nullable|date_format:H:i',
                'is_active' => 'boolean'
            ]);

            // Set default is_active if not provided
            if (!isset($validated['is_active'])) {
                $validated['is_active'] = true;
            }

            $shift = Shift::create($validated);

            Log::info('Shift created:', [
                'shift_id' => (string)$shift->_id,
                'name' => $shift->name
            ]);

            return redirect()->route('shifts.index')
                ->with('success', 'Shift created successfully!');
                
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
                
        } catch (\Exception $e) {
            Log::error('Error creating shift: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to create shift: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified shift
     */
    public function show($id)
    {
        try {
            $shift = Shift::findOrFail($id);
            
            // ✅ FIXED: Count students using BOTH shift_id and old shift field
            $studentsCount = SMstudents::where(function($query) use ($id, $shift) {
                $query->where('shift_id', $id)
                      ->orWhere('shift', $shift->name);
            })->count();
            
            return view('shifts.show', compact('shift', 'studentsCount'));
            
        } catch (\Exception $e) {
            Log::error('Error showing shift: ' . $e->getMessage());
            return redirect()->route('shifts.index')
                ->with('error', 'Shift not found');
        }
    }

    /**
     * Show the form for editing the specified shift
     */
    public function edit($id)
    {
        try {
            $shift = Shift::findOrFail($id);
            return view('shifts.edit', compact('shift'));
            
        } catch (\Exception $e) {
            Log::error('Error editing shift: ' . $e->getMessage());
            return redirect()->route('shifts.index')
                ->with('error', 'Shift not found');
        }
    }

    /**
     * Update the specified shift in database
     */
    public function update(Request $request, $id)
    {
        try {
            $shift = Shift::findOrFail($id);
            
            $validated = $request->validate([
                'name' => 'required|string|max:255|unique:shifts,name,' . $id . ',_id',
                'start_time' => 'nullable|date_format:H:i',
                'end_time' => 'nullable|date_format:H:i',
                'is_active' => 'boolean'
            ]);

            $shift->update($validated);

            Log::info('Shift updated:', [
                'shift_id' => (string)$shift->_id,
                'name' => $shift->name
            ]);

            return redirect()->route('shifts.index')
                ->with('success', 'Shift updated successfully!');
                
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
                
        } catch (\Exception $e) {
            Log::error('Error updating shift: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to update shift: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified shift from database
     */
    public function destroy($id)
    {
        try {
            $shift = Shift::findOrFail($id);
            
            // ✅ FIXED: Check students using BOTH shift_id and old shift field
            $studentsCount = SMstudents::where(function($query) use ($id, $shift) {
                $query->where('shift_id', $id)
                      ->orWhere('shift', $shift->name);
            })->count();
            
            if ($studentsCount > 0) {
                return redirect()->back()
                    ->with('error', "Cannot delete shift. {$studentsCount} student(s) are assigned to this shift.");
            }
            
            $shiftName = $shift->name;
            $shift->delete();

            Log::info('Shift deleted:', [
                'shift_id' => $id,
                'name' => $shiftName
            ]);

            return redirect()->route('shifts.index')
                ->with('success', 'Shift deleted successfully!');
                
        } catch (\Exception $e) {
            Log::error('Error deleting shift: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to delete shift: ' . $e->getMessage());
        }
    }

    /**
     * Toggle shift active status
     */
    public function toggleStatus($id)
    {
        try {
            $shift = Shift::findOrFail($id);
            $shift->is_active = !$shift->is_active;
            $shift->save();

            $status = $shift->is_active ? 'activated' : 'deactivated';

            Log::info('Shift status toggled:', [
                'shift_id' => (string)$shift->_id,
                'name' => $shift->name,
                'new_status' => $shift->is_active
            ]);

            return redirect()->back()
                ->with('success', "Shift {$status} successfully!");
                
        } catch (\Exception $e) {
            Log::error('Error toggling shift status: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to update shift status');
        }
    }

    /**
     * Get shifts as JSON (for AJAX requests)
     */
    public function getShifts()
    {
        try {
            $shifts = Shift::where('is_active', true)
                ->orderBy('name', 'asc')
                ->get(['_id', 'name', 'start_time', 'end_time']);
            
            return response()->json([
                'success' => true,
                'shifts' => $shifts
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error fetching shifts: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch shifts'
            ], 500);
        }
    }
}