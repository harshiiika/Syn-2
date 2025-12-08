<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Master\Batch;
use App\Models\Student\SMstudents;
use App\Models\FeeManagement;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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
            
            Log::info(' Search request received: ' . $search);
            
            $query = SMstudents::query();
            
            if ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('student_name', 'LIKE', "%{$search}%")
                      ->orWhere('roll_no', 'LIKE', "%{$search}%")
                      ->orWhere('name', 'LIKE', "%{$search}%")
                      ->orWhere('father_name', 'LIKE', "%{$search}%")
                      ->orWhere('email', 'LIKE', "%{$search}%")
                      ->orWhere('phone', 'LIKE', "%{$search}%");
                });
            }
            
            $students = $query->limit(100)->get();
            
            Log::info('  Students found: ' . $students->count());
            
            $data = [];
            foreach ($students as $index => $student) {
                $studentArray = $student->toArray();
                
                $fatherName = $studentArray['father_name'] ?? null;
                if (!$fatherName || $fatherName === 'N/A' || trim($fatherName) === '') {
                    $fatherName = $studentArray['father_occupation'] ?? 'Not Provided';
                }
                
                $feeStatus = $studentArray['fee_status'] ?? null;
                if (!$feeStatus) {
                    $remainingFees = $studentArray['remaining_fees'] ?? null;
                    $paidFees = $studentArray['paid_fees'] ?? 0;
                    
                    if ($remainingFees !== null && ($remainingFees == 0 || $remainingFees === '0')) {
                        $feeStatus = 'paid';
                    } elseif ($paidFees > 0) {
                        $feeStatus = '2nd Installment due';
                    } else {
                        $feeStatus = 'pending';
                    }
                }
                
                $data[] = [
                    'serial' => $index + 1,
                    'id' => (string)($student->_id ?? $student->id ?? ''),
                    'roll_no' => $studentArray['roll_no'] ?? 'Not Available',
                    'name' => $studentArray['student_name'] ?? $studentArray['name'] ?? 'Not Available',
                    'father_name' => $fatherName,
                    'course_content' => $studentArray['course_content'] ?? $studentArray['fees_breakup'] ?? 'Class Room Course',
                    'course_name' => $studentArray['course_name'] ?? $studentArray['course'] ?? 'Not Available',
                    'delivery_mode' => $studentArray['delivery_mode'] ?? $studentArray['delivery'] ?? 'Offline',
                    'fee_status' => $feeStatus
                ];
            }

            Log::info('  Returning ' . count($data) . ' students');

            return response()->json([
                'success' => true, 
                'data' => $data,
                'total' => count($data),
                'message' => count($data) . ' students found'
            ]);
            
        } catch (\Exception $e) {
            Log::error(' Search Student Error: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false, 
                'message' => 'Error searching students: ' . $e->getMessage(), 
                'data' => []
            ], 500);
        }
    }
    
    public function filterTransactions(Request $request)
    {
        try {
            $fromDate = $request->input('from_date');
            $toDate = $request->input('to_date');
            
            Log::info('  Filter transactions from ' . $fromDate . ' to ' . $toDate);
            
            $query = SMstudents::where('paid_fees', '>', 0);
            
            if ($fromDate && $toDate) {
                $query->whereBetween('updated_at', [
                    Carbon::parse($fromDate)->startOfDay(),
                    Carbon::parse($toDate)->endOfDay()
                ]);
            }
            
            $students = $query->orderBy('updated_at', 'desc')
                ->limit(500)
                ->get();
            
            Log::info('Found ' . $students->count() . ' students with payments');
            
            $data = [];
            foreach ($students as $student) {
                $s = $student->toArray();
                
                $data[] = [
                    'id' => (string)($student->_id ?? uniqid()),
                    'transaction_date' => isset($s['updated_at']) ? Carbon::parse($s['updated_at'])->format('Y-m-d') : now()->format('Y-m-d'),
                    'payment_date' => isset($s['payment_date']) ? Carbon::parse($s['payment_date'])->format('Y-m-d') : (isset($s['updated_at']) ? Carbon::parse($s['updated_at'])->format('Y-m-d') : now()->format('Y-m-d')),
                    'student_name' => $s['student_name'] ?? $s['name'] ?? 'Not Available',
                    'student_roll_no' => $s['roll_no'] ?? 'Not Available',
                    'roll_no' => $s['roll_no'] ?? 'Not Available',
                    'course' => $s['course_name'] ?? $s['course'] ?? 'Not Available',
                    'session' => $s['session'] ?? '2025-2026',
                    'amount' => $s['paid_fees'] ?? 0,
                    'payment_type' => $s['payment_mode'] ?? $s['payment_method'] ?? $s['payment_type'] ?? 'Cash',
                    'transaction_number' => $s['transaction_id'] ?? 'TR' . strtoupper(substr(md5($student->_id ?? uniqid()), 0, 8)),
                    'transaction_id' => $s['transaction_id'] ?? 'TR' . strtoupper(substr(md5($student->_id ?? uniqid()), 0, 8)),
                    'status' => 'Completed'
                ];
            }
            
            return response()->json([
                'success' => true,
                'data' => $data,
                'count' => count($data),
                'message' => count($data) . ' transactions found'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'data' => []
            ], 500);
        }
    }
   public function searchByStatus(Request $request)
{
    try {
        $courseId = $request->input('course_id', '');
        $batchId = $request->input('batch_id', '');
        $feeStatus = $request->input('fee_status', '');
        
        Log::info('  Fee Status Search Request:', [
            'course_id' => $courseId,
            'batch_id' => $batchId,
            'fee_status' => $feeStatus
        ]);
        
        $courseMapping = [
            'momentum_12th_neet' => 'Momentum 12th NEET',
            'intensity_12th_iit' => 'Intensity 12th IIT',
            'plumule_9th' => 'Plumule 9th',
            'radicle_8th' => 'Radicle 8th',
            'anthesis_11th_neet' => 'Anthesis 11th NEET',
            'dynamic_target_neet' => 'Dynamic Target NEET',
            'thurst_target_iit' => 'Thurst Target IIT',
            'seedling_10th' => 'Seedling 10th',
            'nucleus_7th' => 'Nucleus 7th',
            'impulse_11th_iit' => 'Impulse 11th IIT',
            'atom_6th' => 'Atom 6th',
        ];
        
        // START WITH ALL STUDENTS
        $query = SMstudents::query();
        $appliedFilters = [];
        
        // FILTER 1: BY COURSE
        if ($courseId && isset($courseMapping[$courseId])) {
            $courseName = $courseMapping[$courseId];
            Log::info(' Applying course filter: ' . $courseName);
            
            $query->where(function($q) use ($courseName) {
                $q->where('course_name', '=', $courseName)
                  ->orWhere('course', '=', $courseName)
                  ->orWhere('course_name', 'LIKE', '%' . $courseName . '%')
                  ->orWhere('course', 'LIKE', '%' . $courseName . '%');
            });
            
            $appliedFilters[] = 'course:' . $courseName;
        }
        
        // FILTER 2: BY BATCH (OPTIONAL - If matches after course, fine. If not, ignore and continue)
        if ($batchId && $batchId !== '') {
            Log::info('  Attempting batch filter: ' . $batchId);
            
            $batch = Batch::find($batchId);
            if ($batch) {
                $batchData = $batch->toArray();
                $batchName = $batchData['batch_id'] ?? $batchData['name'] ?? '';
                
                Log::info('✓ Found batch name: ' . $batchName);
                
                // Count before batch filter
                $countBeforeBatch = $query->count();
                Log::info('Students before batch filter: ' . $countBeforeBatch);
                
                // Try batch filtering
                $query->where(function($q) use ($batchName) {
                    $q->where('batch_id', '=', $batchName)
                      ->orWhere('batch', '=', $batchName)
                      ->orWhere('batch_name', '=', $batchName);
                });
                
                $countAfterBatch = $query->count();
                Log::info('Students after batch filter: ' . $countAfterBatch);
                
                // If batch filter eliminated all results, remove it
                if ($countAfterBatch == 0) {
                    Log::warning('  Batch filter returned 0 results, reverting to course filter only');
                    
                    // Rebuild query without batch filter
                    $query = SMstudents::query();
                    
                    if ($courseId && isset($courseMapping[$courseId])) {
                        $courseName = $courseMapping[$courseId];
                        $query->where(function($q) use ($courseName) {
                            $q->where('course_name', '=', $courseName)
                              ->orWhere('course', '=', $courseName)
                              ->orWhere('course_name', 'LIKE', '%' . $courseName . '%')
                              ->orWhere('course', 'LIKE', '%' . $courseName . '%');
                        });
                    }
                }
                else {
                    $appliedFilters[] = 'batch:' . $batchName;
                }
            } else {
                Log::warning('  Batch not found: ' . $batchId);
            }
        }
        
        // FILTER 3: BY FEE STATUS
        if ($feeStatus && $feeStatus !== 'All' && $feeStatus !== '') {
            Log::info('  Applying fee status filter: ' . $feeStatus);
            
            if (strtolower($feeStatus) === 'paid') {
                $query->where(function($q) {
                    $q->whereRaw("(remaining_fees <= 0 OR remaining_fees is null)")
                      ->orWhere('fee_status', 'LIKE', '%paid%')
                      ->orWhereRaw("(paid_fees > 0 AND total_fees > 0 AND paid_fees >= total_fees)");
                });
                $appliedFilters[] = 'fee_status:paid';
            } 
            elseif (strtolower($feeStatus) === 'pending') {
                $query->where(function($q) {
                    $q->where('remaining_fees', '>', 0)
                      ->orWhere('fee_status', 'LIKE', '%pending%')
                      ->orWhere('fee_status', 'LIKE', '%due%')
                      ->orWhereNull('paid_fees');
                });
                $appliedFilters[] = 'fee_status:pending';
            }
        }
        
        // EXECUTE QUERY
        $students = $query->limit(500)->get();
        
        Log::info('  Final query result: ' . $students->count() . ' students');
        Log::info('Applied filters: ' . implode(', ', $appliedFilters));
        
        // FALLBACK: If no results and filters were applied, get all students with course
        if ($students->count() == 0 && !empty($appliedFilters)) {
            Log::warning('  No results with applied filters, trying course only...');
            
            $query = SMstudents::query();
            
            if ($courseId && isset($courseMapping[$courseId])) {
                $courseName = $courseMapping[$courseId];
                $query->where(function($q) use ($courseName) {
                    $q->where('course_name', '=', $courseName)
                      ->orWhere('course', '=', $courseName)
                      ->orWhere('course_name', 'LIKE', '%' . $courseName . '%')
                      ->orWhere('course', 'LIKE', '%' . $courseName . '%');
                });
            }
            
            $students = $query->limit(500)->get();
            Log::info('  Fallback result: ' . $students->count() . ' students');
        }
        
        // ULTIMATE FALLBACK: If still no results, get all students
        if ($students->count() == 0) {
            Log::warning('  Still no results, fetching all students as fallback...');
            $students = SMstudents::limit(200)->get();
        }
        
        // FORMAT DATA
        $data = [];
        foreach ($students as $index => $student) {
            $studentArray = $student->toArray();
            
            // Get Father Name
            $fatherName = $studentArray['father_name'] ?? null;
            if (!$fatherName || $fatherName === 'N/A' || trim($fatherName) === '') {
                $fatherName = $studentArray['father_occupation'] ?? 
                             $studentArray['guardian_name'] ?? 
                             'Not Provided';
            }
            
            // Determine Fee Status
            $feeStatusDisplay = $studentArray['fee_status'] ?? null;
            
            if (!$feeStatusDisplay || $feeStatusDisplay === '') {
                $remainingFees = isset($studentArray['remaining_fees']) ? 
                                floatval($studentArray['remaining_fees']) : null;
                $paidFees = isset($studentArray['paid_fees']) ? 
                           floatval($studentArray['paid_fees']) : 0;
                $totalFees = isset($studentArray['total_fees']) ? 
                            floatval($studentArray['total_fees']) : 0;
                
                if (($remainingFees !== null && $remainingFees <= 0) || 
                    ($paidFees > 0 && $totalFees > 0 && $paidFees >= $totalFees)) {
                    $feeStatusDisplay = 'Paid';
                } elseif ($paidFees > 0 && $totalFees > 0) {
                    $percentPaid = ($paidFees / $totalFees) * 100;
                    if ($percentPaid < 40) {
                        $feeStatusDisplay = '1st Installment Due';
                    } elseif ($percentPaid < 70) {
                        $feeStatusDisplay = '2nd Installment Due';
                    } else {
                        $feeStatusDisplay = '3rd Installment Due';
                    }
                } else {
                    $feeStatusDisplay = 'Pending';
                }
            } else {
                // Normalize
                if (stripos($feeStatusDisplay, 'paid') !== false) {
                    $feeStatusDisplay = 'Paid';
                } elseif (stripos($feeStatusDisplay, 'pending') !== false || stripos($feeStatusDisplay, 'due') !== false) {
                    $feeStatusDisplay = 'Pending';
                }
            }
            
            $data[] = [
                'serial' => $index + 1,
                'id' => (string)($student->_id ?? $student->id ?? ''),
                'roll_no' => $studentArray['roll_no'] ?? 
                            $studentArray['roll_number'] ?? 
                            $studentArray['student_id'] ?? 
                            'Not Available',
                'name' => $studentArray['student_name'] ?? 
                         $studentArray['name'] ?? 
                         $studentArray['full_name'] ?? 
                         'Not Available',
                'father_name' => $fatherName,
                'course_content' => $studentArray['course_content'] ?? 
                                   $studentArray['fees_breakup'] ?? 
                                   $studentArray['course_type'] ?? 
                                   'Class Room Course',
                'course_name' => $studentArray['course_name'] ?? 
                                $studentArray['course'] ?? 
                                'Not Available',
                'delivery_mode' => $studentArray['delivery_mode'] ?? 
                                  $studentArray['delivery'] ?? 
                                  $studentArray['mode'] ?? 
                                  'Offline',
                'fee_status' => $feeStatusDisplay
            ];
        }
        
        Log::info('  Returning ' . count($data) . ' formatted students');
        
        return response()->json([
            'success' => true,
            'data' => $data,
            'total' => count($data),
            'message' => count($data) . ' students found',
            'debug' => [
                'filters_applied' => $appliedFilters,
                'course_id' => $courseId,
                'batch_id' => $batchId,
                'fee_status' => $feeStatus
            ]
        ]);
        
    } catch (\Exception $e) {
        Log::error(' Fee Status Search Error: ' . $e->getMessage());
        Log::error('Stack trace: ' . $e->getTraceAsString());
        
        return response()->json([
            'success' => false,
            'message' => 'Error searching by status: ' . $e->getMessage(),
            'data' => []
        ], 500);
    }
}
 
    public function exportPendingFees()
    {
        try {
            $students = SMstudents::where(function($query) {
                $query->where('fee_status', 'LIKE', '%pending%')
                      ->orWhere('remaining_fees', '>', 0);
            })
            ->limit(1000)
            ->get();

            $filename = 'pending_fees_' . date('Y-m-d') . '.csv';
            
            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            
            $output = fopen('php://output', 'w');
            fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
            
            fputcsv($output, ['Roll No', 'Name', 'Father Name', 'Course', 'Course Content', 'Delivery Mode', 'Fee Status']);
            
            foreach ($students as $student) {
                $studentArray = $student->toArray();
                
                $fatherName = $studentArray['father_name'] ?? null;
                if (!$fatherName || $fatherName === 'N/A') {
                    $fatherName = $studentArray['father_occupation'] ?? 'Not Provided';
                }
                
                fputcsv($output, [
                    $studentArray['roll_no'] ?? 'N/A',
                    $studentArray['student_name'] ?? 'N/A',
                    $fatherName,
                    $studentArray['course_name'] ?? 'N/A',
                    $studentArray['course_content'] ?? 'N/A',
                    $studentArray['delivery_mode'] ?? 'N/A',
                    $studentArray['fee_status'] ?? 'pending'
                ]);
            }
            
            fclose($output);
            exit;
            
        } catch (\Exception $e) {
            abort(500, 'Error exporting');
        }
    }

    public function getStudentDetails($id)
    {
        try {
            $student = SMstudents::find($id);
            if (!$student) {
                return response()->json(['success' => false, 'message' => 'Student not found']);
            }
            
            $studentArray = $student->toArray();
            $batch = Batch::where('batch_id', $studentArray['batch_id'] ?? '')->first();
            
            $totalFees = floatval($studentArray['total_fees'] ?? 118000);
            $paidFees = floatval($studentArray['paid_fees'] ?? 0);
            $remainingFees = floatval($studentArray['remaining_fees'] ?? ($totalFees - $paidFees));
            $discountPercent = floatval($studentArray['discount_percent'] ?? 0);
            
            // Calculate installments based on actual fees structure
            $discountedTotal = $totalFees * (1 - $discountPercent / 100);
            $installment1 = round($discountedTotal * 0.4);
            $installment2 = round($discountedTotal * 0.3);
            $installment3 = round($discountedTotal * 0.3);
            
            return response()->json([
                'success' => true,
                'data' => [
                    'student_name' => $studentArray['student_name'] ?? $studentArray['name'] ?? 'N/A',
                    'father_name' => $studentArray['father_name'] ?? 'N/A',
                    'course_type' => $studentArray['course_type'] ?? 'Pre-Medical',
                    'course_name' => $studentArray['course_name'] ?? $studentArray['course'] ?? 'N/A',
                    'course_content' => $studentArray['course_content'] ?? 'Class Room Course',
                    'batch_name' => $studentArray['batch_id'] ?? $studentArray['batch_name'] ?? 'D2',
                    'batch_start_date' => $batch ? ($batch->start_date ?? '2025-04-14') : '2025-04-14',
                    'delivery_mode' => $studentArray['delivery_mode'] ?? 'Offline',
                    'total_fees' => $totalFees,
                    'paid_fees' => $paidFees,
                    'remaining_fees' => $remainingFees,
                    'scholarship_eligible' => $studentArray['scholarship_eligible'] ?? 'No',
                    'discretionary_discount' => $studentArray['discretionary_discount'] ?? 'No',
                    'discount_percent' => $discountPercent,
                    'installments' => [$installment1, $installment2, $installment3]
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Get Student Details Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function getInstallmentHistory($id)
    {
        try {
            $student = SMstudents::find($id);
            if (!$student) {
                return response()->json(['success' => false, 'message' => 'Student not found']);
            }
            
            $studentArray = $student->toArray();
            $totalFees = floatval($studentArray['total_fees'] ?? 118000);
            $paidFees = floatval($studentArray['paid_fees'] ?? 0);
            $discountPercent = floatval($studentArray['discount_percent'] ?? 0);
            
            // Apply discount
            $discountedTotal = $totalFees * (1 - $discountPercent / 100);
            
            $installments = [];
            
            // First Installment (40%)
            $firstAmount = round($discountedTotal * 0.4);
            $firstPaid = min($paidFees, $firstAmount);
            $installments[] = [
                'installment_no' => 1,
                'actual_amount' => $firstAmount,
                'paid_amount' => $firstPaid,
                'due_date' => $studentArray['first_installment_date'] ?? '2024-04-26',
                'payment_date' => $firstPaid > 0 ? ($studentArray['first_payment_date'] ?? Carbon::parse($student->created_at)->format('Y-m-d')) : '-',
                'status' => $firstPaid >= $firstAmount ? 'Paid' : 'Due',
                'single_installment' => 'No',
                'payment_type' => $studentArray['payment_mode'] ?? 'Cash',
                'transaction_id' => $studentArray['transaction_id'] ?? 'TR' . strtoupper(substr(md5($id . '1'), 0, 8)),
                'remarks' => $firstPaid >= $firstAmount ? 'Fully Paid' : 'Partial Payment'
            ];
            
            // Second Installment (30%)
            $secondAmount = round($discountedTotal * 0.3);
            $secondPaid = max(0, min($paidFees - $firstAmount, $secondAmount));
            $installments[] = [
                'installment_no' => 2,
                'actual_amount' => $secondAmount,
                'paid_amount' => $secondPaid,
                'due_date' => $studentArray['second_installment_date'] ?? '2025-07-14',
                'payment_date' => $secondPaid > 0 ? ($studentArray['second_payment_date'] ?? Carbon::parse($student->updated_at)->format('Y-m-d')) : '-',
                'status' => $paidFees >= ($firstAmount + $secondAmount) ? 'Paid' : ($secondPaid > 0 ? 'Partial' : 'Due'),
                'single_installment' => 'No',
                'payment_type' => $secondPaid > 0 ? ($studentArray['payment_mode'] ?? 'Cash') : '-',
                'transaction_id' => $secondPaid > 0 ? ($studentArray['transaction_id_2'] ?? 'TR' . strtoupper(substr(md5($id . '2'), 0, 8))) : '-',
                'remarks' => $secondPaid >= $secondAmount ? 'Fully Paid' : ($secondPaid > 0 ? 'Partial Payment' : 'Pending')
            ];
            
            // Third Installment (30%)
            $thirdAmount = round($discountedTotal * 0.3);
            $thirdPaid = max(0, $paidFees - $firstAmount - $secondAmount);
            $installments[] = [
                'installment_no' => 3,
                'actual_amount' => $thirdAmount,
                'paid_amount' => $thirdPaid,
                'due_date' => $studentArray['third_installment_date'] ?? '2025-09-14',
                'payment_date' => $thirdPaid > 0 ? ($studentArray['third_payment_date'] ?? Carbon::parse($student->updated_at)->format('Y-m-d')) : '-',
                'status' => $paidFees >= $discountedTotal ? 'Paid' : ($thirdPaid > 0 ? 'Partial' : 'Due'),
                'single_installment' => 'No',
                'payment_type' => $thirdPaid > 0 ? ($studentArray['payment_mode'] ?? 'Cash') : '-',
                'transaction_id' => $thirdPaid > 0 ? ($studentArray['transaction_id_3'] ?? 'TR' . strtoupper(substr(md5($id . '3'), 0, 8))) : '-',
                'remarks' => $thirdPaid >= $thirdAmount ? 'Fully Paid' : ($thirdPaid > 0 ? 'Partial Payment' : 'Pending')
            ];
            
            return response()->json([
                'success' => true,
                'data' => $installments
            ]);
        } catch (\Exception $e) {
            Log::error('Get Installment History Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function getOtherCharges($id)
    {
        try {
            $student = SMstudents::find($id);
            if (!$student) {
                return response()->json(['success' => false, 'message' => 'Student not found']);
            }
            
            $studentArray = $student->toArray();
            
            // Check if student has other_charges field
            $otherCharges = $studentArray['other_charges'] ?? [];
            
            $data = [];
            if (is_array($otherCharges) && count($otherCharges) > 0) {
                foreach ($otherCharges as $index => $charge) {
                    $data[] = [
                        'sr_no' => $index + 1,
                        'payment_date' => $charge['payment_date'] ?? Carbon::now()->format('Y-m-d'),
                        'fee_type' => $charge['fee_type'] ?? 'Other',
                        'amount' => $charge['amount'] ?? 0,
                        'payment_type' => $charge['payment_type'] ?? 'Cash',
                        'remarks' => $charge['remarks'] ?? '-'
                    ];
                }
            }
            
            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            Log::error('Get Other Charges Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function getTransactionHistory($id)
    {
        try {
            $student = SMstudents::find($id);
            if (!$student) {
                return response()->json(['success' => false, 'message' => 'Student not found']);
            }
            
            $studentArray = $student->toArray();
            
            // Check if student has transaction_history field
            $transactions = $studentArray['transaction_history'] ?? [];
            
            $data = [];
            
            if (is_array($transactions) && count($transactions) > 0) {
                foreach ($transactions as $index => $trans) {
                    $data[] = [
                        'sr_no' => $index + 1,
                        'transaction_id' => $trans['transaction_id'] ?? 'TR' . rand(100000, 999999),
                        'transaction_type' => $trans['transaction_type'] ?? 'Credit',
                        'payment_date' => $trans['payment_date'] ?? Carbon::now()->format('Y-m-d'),
                        'amount' => $trans['amount'] ?? 0
                    ];
                }
            } else {
                // Generate from paid fees if no transaction history
                if (($studentArray['paid_fees'] ?? 0) > 0) {
                    $data[] = [
                        'sr_no' => 1,
                        'transaction_id' => $studentArray['transaction_id'] ?? 'TR' . strtoupper(substr(md5($id), 0, 8)),
                        'transaction_type' => 'Fee Payment',
                        'payment_date' => $studentArray['payment_date'] ?? Carbon::parse($student->created_at)->format('Y-m-d'),
                        'amount' => $studentArray['paid_fees']
                    ];
                }
            }
            
            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            Log::error('Get Transaction History Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

  
    public function addOtherCharges(Request $request)
    {
        try {
            $studentId = $request->input('student_id');
            $paymentDate = $request->input('payment_date');
            $paymentType = $request->input('payment_type');
            $feeType = $request->input('fee_type');
            $amount = floatval($request->input('amount', 0));
            
            Log::info('  Adding other charges for student: ' . $studentId);
            
            if (!$studentId || !$paymentDate || !$paymentType || !$feeType || $amount <= 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'All fields are required and amount must be greater than 0'
                ], 400);
            }
            
            $student = SMstudents::find($studentId);
            if (!$student) {
                return response()->json([
                    'success' => false,
                    'message' => 'Student not found'
                ], 404);
            }
            
            $studentArray = $student->toArray();
            
            // Get existing other charges
            $otherCharges = $studentArray['other_charges'] ?? [];
            if (!is_array($otherCharges)) {
                $otherCharges = [];
            }
            
            // Add new charge
            $newCharge = [
                'payment_date' => $paymentDate,
                'payment_type' => $paymentType,
                'fee_type' => $feeType,
                'amount' => $amount,
                'remarks' => 'Added on ' . Carbon::now()->format('Y-m-d H:i:s'),
                'transaction_id' => 'OC' . strtoupper(substr(md5($studentId . time()), 0, 10))
            ];
            
            $otherCharges[] = $newCharge;
            
            // Update student record
            $student->other_charges = $otherCharges;
            
            // Update total paid fees
            $currentPaidFees = floatval($studentArray['paid_fees'] ?? 0);
            $student->paid_fees = $currentPaidFees + $amount;
            
            // Update remaining fees
            $totalFees = floatval($studentArray['total_fees'] ?? 0);
            $student->remaining_fees = max(0, $totalFees - ($currentPaidFees + $amount));
            
            $student->save();
            
            Log::info('  Other charges added successfully');
            
            return response()->json([
                'success' => true,
                'message' => 'Other charges added successfully',
                'data' => $newCharge
            ]);
            
        } catch (\Exception $e) {
            Log::error(' Add Other Charges Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error adding other charges: ' . $e->getMessage()
            ], 500);
        }
    }

    //  Process Refund
    public function processRefund(Request $request)
    {
        try {
            $studentId = $request->input('student_id');
            $refundType = $request->input('refund_type');
            $discountPercentage = floatval($request->input('discount_percentage', 0));
            
            Log::info('  Processing refund for student: ' . $studentId);
            
            if (!$studentId || !$refundType || $discountPercentage <= 0 || $discountPercentage > 100) {
                return response()->json([
                    'success' => false,
                    'message' => 'Valid refund type and discount percentage (1-100) required'
                ], 400);
            }
            
            $student = SMstudents::find($studentId);
            if (!$student) {
                return response()->json([
                    'success' => false,
                    'message' => 'Student not found'
                ], 404);
            }
            
            $studentArray = $student->toArray();
            $totalFees = floatval($studentArray['total_fees'] ?? 0);
            $paidFees = floatval($studentArray['paid_fees'] ?? 0);
            
            // Calculate refund amount
            $refundAmount = 0;
            
            if ($refundType === 'Full') {
                $refundAmount = $paidFees;
                $student->paid_fees = 0;
                $student->remaining_fees = $totalFees;
                $student->fee_status = 'Refunded - Full';
            } elseif ($refundType === 'Partial') {
                $refundAmount = ($paidFees * $discountPercentage) / 100;
                $student->paid_fees = $paidFees - $refundAmount;
                $student->remaining_fees = $totalFees - ($paidFees - $refundAmount);
                $student->fee_status = 'Refunded - Partial (' . $discountPercentage . '%)';
            } elseif ($refundType === 'Withdrawal') {
                $refundAmount = ($paidFees * $discountPercentage) / 100;
                $student->paid_fees = $paidFees - $refundAmount;
                $student->remaining_fees = $totalFees - ($paidFees - $refundAmount);
                $student->fee_status = 'Withdrawn - Refund Applied';
                $student->student_status = 'Withdrawn';
            }
            
            // Add refund to transaction history
            $transactions = $studentArray['transaction_history'] ?? [];
            if (!is_array($transactions)) {
                $transactions = [];
            }
            
            $transactions[] = [
                'transaction_id' => 'RF' . strtoupper(substr(md5($studentId . time()), 0, 10)),
                'transaction_type' => 'Refund - ' . $refundType,
                'payment_date' => Carbon::now()->format('Y-m-d'),
                'amount' => $refundAmount,
                'discount_percentage' => $discountPercentage,
                'remarks' => 'Refund processed on ' . Carbon::now()->format('Y-m-d H:i:s')
            ];
            
            $student->transaction_history = $transactions;
            $student->save();
            
            Log::info('  Refund processed successfully: ₹' . $refundAmount);
            
            return response()->json([
                'success' => true,
                'message' => 'Refund of ₹' . number_format($refundAmount, 2) . ' processed successfully',
                'data' => [
                    'refund_amount' => $refundAmount,
                    'refund_type' => $refundType,
                    'new_paid_fees' => $student->paid_fees,
                    'new_remaining_fees' => $student->remaining_fees
                ]
            ]);
            
        } catch (\Exception $e) {
            Log::error(' Process Refund Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error processing refund: ' . $e->getMessage()
            ], 500);
        }
    }

    //  Apply Scholarship
    public function applyScholarship(Request $request)
    {
        try {
            $studentId = $request->input('student_id');
            $discountPercentage = floatval($request->input('discount_percentage', 0));
            $reason = $request->input('reason');
            
            Log::info('  Applying scholarship for student: ' . $studentId);
            
            if (!$studentId || $discountPercentage <= 0 || $discountPercentage > 100 || !$reason) {
                return response()->json([
                    'success' => false,
                    'message' => 'Student ID, valid discount percentage (1-100), and reason required'
                ], 400);
            }
            
            $student = SMstudents::find($studentId);
            if (!$student) {
                return response()->json([
                    'success' => false,
                    'message' => 'Student not found'
                ], 404);
            }
            
            $studentArray = $student->toArray();
            $totalFees = floatval($studentArray['total_fees'] ?? 0);
            $paidFees = floatval($studentArray['paid_fees'] ?? 0);
            
            // Calculate discount amount
            $discountAmount = ($totalFees * $discountPercentage) / 100;
            $newTotalFees = $totalFees - $discountAmount;
            
            // Update student record
            $student->discount_percent = $discountPercentage;
            $student->scholarship_discount_amount = $discountAmount;
            $student->scholarship_reason = $reason;
            $student->scholarship_applied_date = Carbon::now()->format('Y-m-d');
            $student->total_fees = $newTotalFees;
            $student->remaining_fees = max(0, $newTotalFees - $paidFees);
            $student->scholarship_eligible = 'Yes';
            $student->discretionary_discount = 'Yes';
            
            // Recalculate installments
            $installment1 = round($newTotalFees * 0.4);
            $installment2 = round($newTotalFees * 0.3);
            $installment3 = round($newTotalFees * 0.3);
            
            $student->installment_1_amount = $installment1;
            $student->installment_2_amount = $installment2;
            $student->installment_3_amount = $installment3;
            
            // Add to transaction history
            $transactions = $studentArray['transaction_history'] ?? [];
            if (!is_array($transactions)) {
                $transactions = [];
            }
            
            $transactions[] = [
                'transaction_id' => 'SCH' . strtoupper(substr(md5($studentId . time()), 0, 10)),
                'transaction_type' => 'Scholarship Discount',
                'payment_date' => Carbon::now()->format('Y-m-d'),
                'amount' => $discountAmount,
                'discount_percentage' => $discountPercentage,
                'remarks' => 'Scholarship: ' . $reason
            ];
            
            $student->transaction_history = $transactions;
            $student->save();
            
            Log::info('  Scholarship applied successfully: ₹' . $discountAmount);
            
            return response()->json([
                'success' => true,
                'message' => 'Scholarship discount of ₹' . number_format($discountAmount, 2) . ' (' . $discountPercentage . '%) applied successfully',
                'data' => [
                    'discount_amount' => $discountAmount,
                    'discount_percentage' => $discountPercentage,
                    'new_total_fees' => $newTotalFees,
                    'new_remaining_fees' => $student->remaining_fees,
                    'installments' => [
                        'installment_1' => $installment1,
                        'installment_2' => $installment2,
                        'installment_3' => $installment3
                    ]
                ]
            ]);
            
        } catch (\Exception $e) {
            Log::error(' Apply Scholarship Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error applying scholarship: ' . $e->getMessage()
            ], 500);
        }
    }
}