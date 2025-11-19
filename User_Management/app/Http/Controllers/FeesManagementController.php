<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Master\Batch;
use App\Models\Student\SMstudents;
use Illuminate\Support\Facades\Log;

class FeesManagementController extends Controller
{
    public function index()
    {
        try {
            $courses = [
                ['id' => 'intensity_12th_iit', 'name' => 'Intensity 12th IIT', 'db_name' => 'Intensity 12th IIT'],
                ['id' => 'plumule_9th', 'name' => 'Plumule 9th', 'db_name' => 'Plumule 9th'],
                ['id' => 'radicle_8th', 'name' => 'Radicle 8th', 'db_name' => 'Radicle 8th'],
                ['id' => 'anthesis_11th_neet', 'name' => 'Anthesis 11th NEET', 'db_name' => 'Anthesis 11th NEET'],
                ['id' => 'dynamic_target_neet', 'name' => 'Dynamic Target NEET', 'db_name' => 'Dynamic Target NEET'],
                ['id' => 'thurst_target_iit', 'name' => 'Thurst Target IIT', 'db_name' => 'Thurst Target IIT'],
                ['id' => 'seedling_10th', 'name' => 'Seedling 10th', 'db_name' => 'Seedling 10th'],
                ['id' => 'nucleus_7th', 'name' => 'Nucleus 7th', 'db_name' => 'Nucleus 7th'],
                ['id' => 'momentum_12th_neet', 'name' => 'Momentum 12th NEET', 'db_name' => 'Momentum 12th NEET'],
                ['id' => 'impulse_11th_iit', 'name' => 'Impulse 11th IIT', 'db_name' => 'Impulse 11th IIT'],
                ['id' => 'atom_6th', 'name' => 'Atom 6th', 'db_name' => 'Atom 6th'],
            ];

            $coursesBatchesMapping = [];
            
            $allBatches = Batch::where('status', 'Active')->get();
            
            $courseNameToId = [];
            foreach ($courses as $course) {
                $courseNameToId[$course['db_name']] = $course['id'];
            }
            
            foreach ($allBatches as $batch) {
                $batchArray = $batch->toArray();
                $courseName = $batchArray['course'] ?? null;
                
                if (!$courseName) continue;
                
                $courseId = $courseNameToId[$courseName] ?? null;
                if (!$courseId) continue;
                
                if (!isset($coursesBatchesMapping[$courseId])) {
                    $coursesBatchesMapping[$courseId] = [];
                }
                
                $coursesBatchesMapping[$courseId][] = [
                    'id' => (string)$batch->_id,
                    'name' => $batchArray['batch_id'] ?? 'Unnamed',
                    'mode' => $batchArray['mode'] ?? 'Offline',
                    'shift' => $batchArray['shift'] ?? 'Morning'
                ];
            }
            
            return view('fees_management.index', compact('courses', 'coursesBatchesMapping'));

        } catch (\Exception $e) {
            Log::error('Fatal Error in FeesManagement: ' . $e->getMessage());
            return view('fees_management.index', [
                'courses' => [], 
                'coursesBatchesMapping' => []
            ]);
        }
    }

    public function searchStudent(Request $request)
    {
        try {
            $search = $request->input('search', '');
            
            Log::info('🔍 Search request received', ['search' => $search]);
            
            $query = SMstudents::query();
            
            if ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('student_name', 'LIKE', "%{$search}%")
                      ->orWhere('name', 'LIKE', "%{$search}%")
                      ->orWhere('roll_no', 'LIKE', "%{$search}%")
                      ->orWhere('student_roll_no', 'LIKE', "%{$search}%");
                });
            }
            
            $students = $query->limit(100)->get();
            
            Log::info('📊 Students found: ' . $students->count());
            
            $data = [];
            foreach ($students as $index => $student) {
                try {
                    $studentArray = $student->toArray();
                    
                    $data[] = [
                        'serial' => $index + 1,
                        'id' => (string)($student->_id ?? $student->id ?? ''),
                        'roll_no' => $studentArray['roll_no'] ?? $studentArray['student_roll_no'] ?? '-',
                        'name' => $studentArray['name'] ?? $studentArray['student_name'] ?? '-',
                        'father_name' => $studentArray['father_name'] ?? $studentArray['father'] ?? '-',
                        'course_content' => $studentArray['course_content'] ?? $studentArray['course_type'] ?? 'Class Room Course (With Test Series & Study Material)',
                        'course_name' => $studentArray['course_name'] ?? $studentArray['course'] ?? '-',
                        'delivery_mode' => $studentArray['delivery_mode'] ?? $studentArray['mode'] ?? 'Offline',
                        'fee_status' => $studentArray['fee_status'] ?? 'Pending'
                    ];
                } catch (\Exception $e) {
                    Log::error('Error processing student: ' . $e->getMessage());
                    continue;
                }
            }

            return response()->json([
                'success' => true, 
                'data' => $data,
                'total' => count($data)
            ]);
            
        } catch (\Exception $e) {
            Log::error('❌ Search Student Error: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false, 
                'message' => 'Error searching students: ' . $e->getMessage(), 
                'data' => []
            ]);
        }
    }

    public function searchByStatus(Request $request)
    {
        try {
            $feeStatus = $request->input('fee_status');
            $courseId = $request->input('course_id');
            $batchId = $request->input('batch_id');

            Log::info('🔍 Fee Status Search', [
                'fee_status' => $feeStatus,
                'course_id' => $courseId,
                'batch_id' => $batchId
            ]);

            if (!$feeStatus) {
                return response()->json([
                    'success' => false,
                    'message' => 'Fee status is required',
                    'data' => []
                ]);
            }
            
            $query = SMstudents::query();

            if ($courseId) {
                $query->where('course_id', $courseId);
            }
            
            if ($batchId) {
                $query->where('batch_id', $batchId);
            }
            
            if ($feeStatus !== 'All') {
                $query->where('fee_status', $feeStatus);
            }

            $students = $query->limit(100)->get();
            
            Log::info('📊 Status search found: ' . $students->count());

            $data = [];
            foreach ($students as $student) {
                try {
                    $studentArray = $student->toArray();
                    
                    $data[] = [
                        'id' => (string)($student->_id ?? $student->id ?? ''),
                        'roll_no' => $studentArray['roll_no'] ?? $studentArray['student_roll_no'] ?? '-',
                        'name' => $studentArray['name'] ?? $studentArray['student_name'] ?? '-',
                        'father_name' => $studentArray['father_name'] ?? $studentArray['father'] ?? '-',
                        'course_content' => $studentArray['course_content'] ?? $studentArray['course_type'] ?? 'Class Room Course',
                        'course_name' => $studentArray['course_name'] ?? $studentArray['course'] ?? '-',
                        'delivery_mode' => $studentArray['delivery_mode'] ?? $studentArray['mode'] ?? 'Offline',
                        'fee_status' => $studentArray['fee_status'] ?? 'Pending'
                    ];
                } catch (\Exception $e) {
                    Log::error('Error processing student in status search: ' . $e->getMessage());
                    continue;
                }
            }

            return response()->json([
                'success' => true, 
                'data' => $data, 
                'count' => count($data)
            ]);
            
        } catch (\Exception $e) {
            Log::error('❌ Search By Status Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error searching by status: ' . $e->getMessage(),
                'data' => []
            ]);
        }
    }

    public function getBatchesByCourse(Request $request)
    {
        return response()->json(['success' => true, 'data' => []]);
    }

    public function filterTransactions(Request $request)
    {
        try {
            $fromDate = $request->from_date;
            $toDate = $request->to_date;
            
            return response()->json(['success' => true, 'data' => []]);
            
        } catch (\Exception $e) {
            Log::error('Filter Transactions Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error filtering transactions', 'data' => []]);
        }
    }

    public function exportPendingFees()
    {
        try {
            $students = SMstudents::whereIn('fee_status', ['Pending', '2nd Installment due', '3rd Installment due'])
                ->limit(1000)
                ->get();

            $filename = 'pending_fees_' . date('Y-m-d') . '.csv';
            
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            
            $output = fopen('php://output', 'w');
            fputcsv($output, ['Roll No', 'Name', 'Father Name', 'Course', 'Fee Status']);
            
            foreach ($students as $student) {
                $studentArray = $student->toArray();
                fputcsv($output, [
                    $studentArray['roll_no'] ?? '-',
                    $studentArray['name'] ?? $studentArray['student_name'] ?? '-',
                    $studentArray['father_name'] ?? $studentArray['father'] ?? '-',
                    $studentArray['course'] ?? '-',
                    $studentArray['fee_status'] ?? '-'
                ]);
            }
            
            fclose($output);
            exit;
            
        } catch (\Exception $e) {
            Log::error('Export Error: ' . $e->getMessage());
            abort(500);
        }
    }
}