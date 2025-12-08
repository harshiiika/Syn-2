<?php

namespace App\Http\Controllers\study_material;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\study_material\Dispatch;
use App\Models\Student\Student; // Attendance
use App\Models\Student\SMstudents; // ← ACTUAL STUDENTS (note lowercase 's')
use Illuminate\Support\Facades\Log;

class DispatchController extends Controller
{
    public function index()
    {
        try {
            $recentDispatches = Dispatch::orderBy('dispatched_at', 'desc')->take(20)->get();
            return view('study_material.Dispatch', compact('recentDispatches'));
        } catch (\Exception $e) {
            return back()->with('error', 'Error loading page: ' . $e->getMessage());
        }
    }

    public function getBatches(Request $request)
    {
        try {
            $courseName = $request->input('course_name');
            
            if (!$courseName || trim($courseName) === '') {
                return response()->json(['success' => false, 'message' => 'Course required', 'batches' => []], 400);
            }
            
            //   USE SMSTUDENTS MODEL (actual students with father_name)
            $students = SMstudents::where('course_name', $courseName)
                ->whereNotNull('batch_name')
                ->where('batch_name', '!=', '')
                ->get();
            
            if ($students->count() === 0) {
                return response()->json(['success' => true, 'batches' => []]);
            }
            
            $batchNames = $students->pluck('batch_name')->filter()->unique()->values();
            $batches = [];
            foreach ($batchNames as $batchName) {
                $batches[] = ['batch_name' => $batchName, 'name' => $batchName, 'id' => $batchName];
            }
            
            return response()->json(['success' => true, 'batches' => $batches]);
            
        } catch (\Exception $e) {
            Log::error('getBatches error', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'batches' => []], 500);
        }
    }

    public function getStudents(Request $request)
    {
        try {
            $courseName = $request->input('course_name');
            $batchName = $request->input('batch_name');
            
            Log::info('=== GET STUDENTS ===', ['course' => $courseName, 'batch' => $batchName]);
            
            //   QUERY SMSTUDENTS - THE ACTUAL STUDENTS TABLE WITH FATHER_NAME
            $query = SMstudents::query();
            if ($courseName) $query->where('course_name', $courseName);
            if ($batchName && $batchName !== 'all') $query->where('batch_name', $batchName);
            
            $students = $query->orderBy('roll_no', 'asc')->get();
            
            Log::info('Students found in SMstudents', ['count' => $students->count()]);
            
            if ($students->count() === 0) {
                return response()->json(['success' => true, 'students' => []]);
            }
            
            // Log first student to see fields
            $firstData = $students->first()->toArray();
            Log::info('First student fields from SMstudents:', ['fields' => array_keys($firstData)]);
            
            $transformedStudents = $students->map(function ($student) {
                $data = $student->toArray();
                $isDispatched = Dispatch::where('student_id', $student->_id)->exists();
                
                //   EXTRACT FATHER NAME from SMstudents model
                $fatherName = '-';
                
                // Search for father field
                foreach ($data as $field => $value) {
                    if (!is_string($value) && !is_numeric($value)) continue;
                    if (empty($value) || $value === '-') continue;
                    
                    $lowerField = strtolower($field);
                    if (strpos($lowerField, 'father') !== false || 
                        strpos($lowerField, 'guardian') !== false || 
                        strpos($lowerField, 'parent') !== false) {
                        $fatherName = $value;
                        Log::info("  FATHER NAME FOUND in field '{$field}': {$fatherName}");
                        break;
                    }
                }
                
                // Fallback to common field names
                if ($fatherName === '-') {
                    $fatherName = $data['father_name'] 
                        ?? $data['fatherName'] 
                        ?? $data['fathers_name']
                        ?? $data['guardian_name']
                        ?? $data['parent_name']
                        ?? '-';
                        
                    if ($fatherName !== '-') {
                        Log::info("  FATHER NAME from fallback: {$fatherName}");
                    }
                }
                
                if ($fatherName === '-') {
                    Log::warning('⚠ No father name found', [
                        'student_id' => $student->_id,
                        'available_fields' => array_keys($data)
                    ]);
                }
                
                return [
                    '_id' => $student->_id ?? '',
                    'id' => $student->_id ?? '',
                    'roll_no' => $data['roll_no'] ?? $data['rollNo'] ?? '-',
                    'student_name' => $data['student_name'] ?? $data['name'] ?? $data['studentName'] ?? '-',
                    'father_name' => $fatherName,
                    'batch_name' => $data['batch_name'] ?? $data['batchName'] ?? '-',
                    'is_dispatched' => $isDispatched
                ];
            });
            
            Log::info('  Students transformed', ['count' => $transformedStudents->count()]);
            
            return response()->json(['success' => true, 'students' => $transformedStudents]);
            
        } catch (\Exception $e) {
            Log::error(' getStudents ERROR', [
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => basename($e->getFile())
            ]);
            return response()->json([
                'success' => false, 
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function dispatchMaterial(Request $request)
    {
        try {
            $request->validate(['student_ids' => 'required|array']);
            $studentIds = $request->input('student_ids');
            $dispatchedCount = 0;
            $alreadyDispatchedCount = 0;

            foreach ($studentIds as $studentId) {
                if (Dispatch::where('student_id', $studentId)->exists()) {
                    $alreadyDispatchedCount++;
                    continue;
                }
                
                //   USE SMSTUDENTS MODEL
                $student = SMstudents::find($studentId);
                
                if ($student) {
                    $data = $student->toArray();
                    
                    // Extract father name
                    $fatherName = '-';
                    foreach ($data as $field => $value) {
                        $lower = strtolower($field);
                        if ((strpos($lower, 'father') !== false || strpos($lower, 'guardian') !== false) && 
                            !empty($value) && $value !== '-') {
                            $fatherName = $value;
                            break;
                        }
                    }
                    if ($fatherName === '-') {
                        $fatherName = $data['father_name'] ?? $data['fatherName'] ?? '-';
                    }
                    
                    Dispatch::create([
                        'student_id' => $studentId,
                        'roll_no' => $data['roll_no'] ?? 'N/A',
                        'student_name' => $data['student_name'] ?? $data['name'] ?? 'N/A',
                        'father_name' => $fatherName,
                        'batch_id' => $student->batch_id ?? '',
                        'batch_name' => $data['batch_name'] ?? 'N/A',
                        'course_id' => $student->course_id ?? '',
                        'course_name' => $data['course_name'] ?? 'N/A',
                        'dispatched_at' => now(),
                        'dispatched_by' => auth()->user()->name ?? 'Admin',
                    ]);
                    
                    $dispatchedCount++;
                }
            }

            $message = "Dispatched to {$dispatchedCount} student(s)!";
            if ($alreadyDispatchedCount > 0) $message .= " ({$alreadyDispatchedCount} already dispatched)";

            return response()->json(['success' => true, 'message' => $message]);

        } catch (\Exception $e) {
            Log::error('Dispatch error', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function create() { }
    public function store(Request $request) { }
    public function show(string $id) { }
    public function edit(string $id) { }
    public function update(Request $request, string $id) { }
    public function destroy(string $id)
    {
        try {
            Dispatch::findOrFail($id)->delete();
            return response()->json(['success' => true, 'message' => 'Deleted!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
        }
    }
}