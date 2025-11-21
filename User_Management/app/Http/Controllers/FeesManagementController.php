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

    // ✅ YOUR ORIGINAL WORKING searchStudent - UNCHANGED
    public function searchStudent(Request $request)
    {
        try {
            $search = $request->input('search', '');
            
            Log::info('🔍 Search request received: ' . $search);
            
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
            
            Log::info('📊 Students found: ' . $students->count());
            
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

            Log::info('✅ Returning ' . count($data) . ' students');

            return response()->json([
                'success' => true, 
                'data' => $data,
                'total' => count($data),
                'message' => count($data) . ' students found'
            ]);
            
        } catch (\Exception $e) {
            Log::error('❌ Search Student Error: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false, 
                'message' => 'Error searching students: ' . $e->getMessage(), 
                'data' => []
            ], 500);
        }
    }
    
    // ✅ ONLY THIS FUNCTION CHANGED - Simple version for Daily Transaction
    public function filterTransactions(Request $request)
    {
        try {
            Log::info('📅 Loading all transactions');
            
            // Get ALL students with paid fees
            $students = SMstudents::where('paid_fees', '>', 0)
                ->orderBy('updated_at', 'desc')
                ->limit(500)
                ->get();
            
            Log::info('Found ' . $students->count() . ' students with payments');
            
            $data = [];
            foreach ($students as $student) {
                $s = $student->toArray();
                
                $data[] = [
                    'id' => (string)($student->_id ?? uniqid()),
                    'transaction_date' => isset($s['updated_at']) ? Carbon::parse($s['updated_at'])->format('Y-m-d') : now()->format('Y-m-d'),
                    'payment_date' => isset($s['updated_at']) ? Carbon::parse($s['updated_at'])->format('Y-m-d') : now()->format('Y-m-d'),
                    'student_name' => $s['student_name'] ?? $s['name'] ?? 'Not Available',
                    'student_roll_no' => $s['roll_no'] ?? 'Not Available',
                    'roll_no' => $s['roll_no'] ?? 'Not Available',
                    'course' => $s['course_name'] ?? $s['course'] ?? 'Not Available',
                    'session' => '2025-2026',
                    'amount' => $s['paid_fees'] ?? 0,
                    'payment_type' => $s['payment_mode'] ?? $s['payment_method'] ?? 'Cash',
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

    // ✅ YOUR ORIGINAL WORKING searchByStatus - UNCHANGED
    public function searchByStatus(Request $request)
    {
        try {
            $courseId = $request->input('course_id', '');
            $batchId = $request->input('batch_id', '');
            $feeStatus = $request->input('fee_status', '');
            
            Log::info('📊 Fee Status Search Request:', [
                'course_id' => $courseId,
                'batch_id' => $batchId,
                'fee_status' => $feeStatus
            ]);
            
            $query = SMstudents::query();
            
            if ($courseId) {
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
                
                if (isset($courseMapping[$courseId])) {
                    $courseName = $courseMapping[$courseId];
                    Log::info('Filtering by course: ' . $courseName);
                    
                    $query->where(function($q) use ($courseName, $courseId) {
                        $q->where('course_name', 'LIKE', '%' . $courseName . '%')
                          ->orWhere('course', 'LIKE', '%' . $courseName . '%')
                          ->orWhere('course_id', $courseId)
                          ->orWhere('course_type', 'LIKE', '%' . $courseName . '%');
                    });
                }
            }
            
            if ($batchId && $batchId !== '') {
                Log::info('Filtering by batch ID: ' . $batchId);
                
                $batch = \App\Models\Master\Batch::find($batchId);
                if ($batch) {
                    $batchData = $batch->toArray();
                    $batchName = $batchData['batch_id'] ?? $batchData['name'] ?? '';
                    
                    Log::info('Found batch: ' . $batchName);
                    
                    $query->where(function($q) use ($batchName, $batchId) {
                        $q->where('batch_id', $batchName)
                          ->orWhere('batch', $batchName)
                          ->orWhere('batch_name', $batchName)
                          ->orWhere('_batch_id', $batchId);
                    });
                }
            }
            
            if ($feeStatus && $feeStatus !== 'All') {
                Log::info('Filtering by fee status: ' . $feeStatus);
                
                if (strtolower($feeStatus) === 'paid') {
                    $query->where(function($q) {
                        $q->where(function($subQ) {
                            $subQ->where('fee_status', 'LIKE', '%paid%')
                                 ->orWhere('fee_status', 'LIKE', '%Paid%')
                                 ->orWhere('fee_status', 'LIKE', '%PAID%');
                        })
                        ->orWhere(function($subQ) {
                            $subQ->where('remaining_fees', '<=', 0);
                        })
                        ->orWhere(function($subQ) {
                            $subQ->whereNull('remaining_fees')
                                 ->where('paid_fees', '>', 0);
                        });
                    });
                } elseif (strtolower($feeStatus) === 'pending') {
                    $query->where(function($q) {
                        $q->where(function($subQ) {
                            $subQ->where('fee_status', 'LIKE', '%pending%')
                                 ->orWhere('fee_status', 'LIKE', '%Pending%')
                                 ->orWhere('fee_status', 'LIKE', '%PENDING%')
                                 ->orWhere('fee_status', 'LIKE', '%due%')
                                 ->orWhere('fee_status', 'LIKE', '%Due%')
                                 ->orWhere('fee_status', 'LIKE', '%installment%');
                        })
                        ->orWhere('remaining_fees', '>', 0)
                        ->orWhere(function($subQ) {
                            $subQ->whereNull('paid_fees')
                                 ->orWhere('paid_fees', '<=', 0);
                        });
                    });
                }
            }
            
            $students = $query->limit(500)->get();
            
            Log::info('✅ Query executed - Found ' . $students->count() . ' students');
            
            if (!$courseId && !$batchId && (!$feeStatus || $feeStatus === 'All')) {
                Log::info('No filters applied, getting all students');
                $students = SMstudents::limit(200)->get();
            }
            
            $data = [];
            foreach ($students as $index => $student) {
                $studentArray = $student->toArray();
                
                $fatherName = $studentArray['father_name'] ?? null;
                if (!$fatherName || $fatherName === 'N/A' || trim($fatherName) === '') {
                    $fatherName = $studentArray['father_occupation'] ?? 
                                 $studentArray['guardian_name'] ?? 
                                 'Not Provided';
                }
                
                $feeStatusDisplay = $studentArray['fee_status'] ?? null;
                
                if (!$feeStatusDisplay || $feeStatusDisplay === '') {
                    $remainingFees = isset($studentArray['remaining_fees']) ? 
                                    floatval($studentArray['remaining_fees']) : null;
                    $paidFees = isset($studentArray['paid_fees']) ? 
                               floatval($studentArray['paid_fees']) : 0;
                    $totalFees = isset($studentArray['total_fees']) ? 
                                floatval($studentArray['total_fees']) : 0;
                    
                    if ($remainingFees !== null && $remainingFees <= 0 && $paidFees > 0) {
                        $feeStatusDisplay = 'Paid';
                    } elseif ($totalFees > 0 && $paidFees >= $totalFees) {
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
                    if (stripos($feeStatusDisplay, 'paid') !== false) {
                        $feeStatusDisplay = 'Paid';
                    } elseif (stripos($feeStatusDisplay, 'pending') !== false) {
                        $feeStatusDisplay = 'Pending';
                    } elseif (stripos($feeStatusDisplay, 'due') !== false) {
                        $feeStatusDisplay = 'Due';
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
            
            Log::info('✅ Returning ' . count($data) . ' formatted students');
            
            return response()->json([
                'success' => true,
                'data' => $data,
                'total' => count($data),
                'message' => count($data) . ' students found'
            ]);
            
        } catch (\Exception $e) {
            Log::error('❌ Fee Status Search Error: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Error searching by status: ' . $e->getMessage(),
                'data' => []
            ], 500);
        }
    }

    // ✅ ALL OTHER FUNCTIONS - YOUR ORIGINAL CODE UNCHANGED
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
            
            $totalFees = $studentArray['total_fees'] ?? 0;
            $paidFees = $studentArray['paid_fees'] ?? 0;
            $discountPercent = $studentArray['discount_percent'] ?? 0;
            
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
                    'batch_name' => $studentArray['batch_id'] ?? 'D2',
                    'batch_start_date' => $batch ? $batch->start_date : '2025-04-14',
                    'delivery_mode' => $studentArray['delivery_mode'] ?? 'Offline',
                    'total_fees' => $totalFees,
                    'paid_fees' => $paidFees,
                    'remaining_fees' => $studentArray['remaining_fees'] ?? 0,
                    'scholarship_eligible' => $studentArray['scholarship_eligible'] ?? 'No',
                    'discretionary_discount' => $studentArray['discretionary_discount'] ?? 'No',
                    'discount_percent' => $discountPercent,
                    'installments' => [$installment1, $installment2, $installment3]
                ]
            ]);
        } catch (\Exception $e) {
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
            $totalFees = $studentArray['total_fees'] ?? 118000;
            $paidFees = $studentArray['paid_fees'] ?? 0;
            
            $installments = [];
            
            $firstAmount = round($totalFees * 0.4);
            $installments[] = [
                'installment_no' => 1,
                'actual_amount' => $firstAmount,
                'paid_amount' => min($paidFees, $firstAmount),
                'due_date' => $studentArray['first_installment_date'] ?? '2024-04-26',
                'payment_date' => $paidFees > 0 ? ($studentArray['first_payment_date'] ?? '2024-09-04') : null,
                'status' => $paidFees >= $firstAmount ? 'Paid' : 'Due',
                'single_installment' => 'No'
            ];
            
            $secondAmount = round($totalFees * 0.3);
            $secondPaid = max(0, min($paidFees - $firstAmount, $secondAmount));
            $installments[] = [
                'installment_no' => 2,
                'actual_amount' => $secondAmount,
                'paid_amount' => $secondPaid,
                'due_date' => '2025-07-14',
                'payment_date' => $secondPaid > 0 ? ($studentArray['second_payment_date'] ?? '2024-09-04') : null,
                'status' => $paidFees >= ($firstAmount + $secondAmount) ? 'Paid' : 'Due',
                'single_installment' => 'No'
            ];
            
            $thirdAmount = round($totalFees * 0.3);
            $thirdPaid = max(0, $paidFees - $firstAmount - $secondAmount);
            $installments[] = [
                'installment_no' => 3,
                'actual_amount' => $thirdAmount,
                'paid_amount' => $thirdPaid,
                'due_date' => '2025-09-14',
                'payment_date' => $thirdPaid > 0 ? ($studentArray['third_payment_date'] ?? null) : null,
                'status' => $paidFees >= $totalFees ? 'Paid' : 'Due',
                'single_installment' => 'No'
            ];
            
            return response()->json([
                'success' => true,
                'data' => $installments
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function getOtherCharges($id)
    {
        try {
            return response()->json([
                'success' => true,
                'data' => []
            ]);
        } catch (\Exception $e) {
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
            
            $transactions = FeeManagement::where('student_id', $id)
                ->orderBy('transaction_date', 'desc')
                ->get();
            
            $data = [];
            foreach ($transactions as $index => $transaction) {
                $transArray = $transaction->toArray();
                $data[] = [
                    'sr_no' => $index + 1,
                    'transaction_id' => $transArray['transaction_id'] ?? 'SYNTH' . rand(100000, 999999),
                    'transaction_type' => $transArray['transaction_type'] ?? 'Credit',
                    'payment_date' => $transArray['transaction_date'] ?? $transArray['payment_date'] ?? '2024-09-04',
                    'amount' => $transArray['amount'] ?? 0
                ];
            }
            
            if (empty($data)) {
                $studentArray = $student->toArray();
                if (($studentArray['paid_fees'] ?? 0) > 0) {
                    $data = [
                        [
                            'sr_no' => 1,
                            'transaction_id' => 'SYNTH' . rand(100000, 999999),
                            'transaction_type' => 'Credit',
                            'payment_date' => $studentArray['payment_date'] ?? '2024-09-04',
                            'amount' => $studentArray['paid_fees']
                        ]
                    ];
                }
            }
            
            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}