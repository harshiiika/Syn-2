<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\Batch;
use App\Models\Master\Courses;
use App\Models\User\BatchAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;



class BatchController extends Controller
{
    /**
     * Course mapping configuration
     */
    private function getCourseMapping()
    {
        return [
            'Anthesis 11th NEET' => ['class' => '11th (XI)', 'course_type' => 'Pre-Medical'],
            'Momentum 12th NEET' => ['class' => '12th (XII)', 'course_type' => 'Pre-Medical'],
            'Dynamic Target NEET' => ['class' => 'Target (XII +)', 'course_type' => 'Pre-Medical'],
            'Impulse 11th IIT' => ['class' => '11th (XI)', 'course_type' => 'Pre-Engineering'],
            'Intensity 12th IIT' => ['class' => '12th (XII)', 'course_type' => 'Pre-Engineering'],
            'Thrust Target IIT' => ['class' => 'Target (XII +)', 'course_type' => 'Pre-Engineering'],
            'Seedling 10th' => ['class' => '10th (X)', 'course_type' => 'Pre-Foundation'],
            'Plumule 9th' => ['class' => '9th (IX)', 'course_type' => 'Pre-Foundation'],
            'Radicle 8th' => ['class' => '8th (VIII)', 'course_type' => 'Pre-Foundation'],
            'Nucleus 7th' => ['class' => '7th (VII)', 'course_type' => 'Pre-Foundation'],
            'Atom 6th' => ['class' => '6th (VI)', 'course_type' => 'Pre-Foundation']
        ];
    }

    /**
     * Display all batches with pagination and search
     */
  /**
     * Display batch assignments with full course information
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $search = $request->get('search');

        // Query the BATCH table (master.batches), not batch_assignments
        $query = Batch::query();

        // Apply search filters
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('batch_id', 'like', '%' . $search . '%')
                  ->orWhere('course', 'like', '%' . $search . '%')
                  ->orWhere('class', 'like', '%' . $search . '%')
                  ->orWhere('course_type', 'like', '%' . $search . '%');
            });
        }

        // Get paginated results with course data
        $batches = $query->orderBy('created_at', 'desc')
                        ->paginate($perPage);

        // Preserve query parameters
        $batches->appends($request->except('page'));

        // Debug log
        Log::info('Batch Assignment Page - Total batches: ' . $batches->total());
        
        return view('master.batch.index', compact('batches'));
    }

    /**
     * Assign employees to a batch
     */
    public function assignEmployee(Request $request, $batchId)
    {
        $request->validate([
            'employee_id' => 'required|exists:users,_id',
            'role' => 'required|string'
        ]);

        $batch = Batch::findOrFail($batchId);

        BatchAssignment::create([
            'batch_id' => $batch->batch_id,
            'employee_id' => $request->employee_id,
            'role' => $request->role,
            'start_date' => $batch->start_date,
            'shift' => $batch->shift,
            'status' => 'Active'
        ]);

        return redirect()->back()->with('success', 'Employee assigned to batch successfully!');
    }


    /**
     * Store a new batch with automatic field population
     */
public function store(Request $request)
{
    // Debug logging - FIXED
    Log::info('=== BATCH STORE DEBUG ===');
    Log::info('Request All:', ['data' => $request->all()]);
    Log::info('Course Value:', ['course' => $request->course]);
    Log::info('Course Input:', ['input' => $request->input('course')]);

    $validator = Validator::make($request->all(), [
        'batch_id' => 'required|string|unique:batches,batch_id',
        'course' => 'required|string',
        'medium' => 'required|string',
        'mode' => 'required|string',
        'shift' => 'required|string',
        'branch_name' => 'required|string',
        'start_date' => 'required|date',
        'installment_date_2' => 'nullable|date',
        'installment_date_3' => 'nullable|date',
        'status' => 'nullable|in:Active,Inactive'
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'errors' => $validator->errors()
        ], 422);
    }

    $courseMapping = $this->getCourseMapping();
    $selectedCourse = $request->course;

    $classData = $courseMapping[$selectedCourse] ?? [
        'class' => 'Unknown',
        'course_type' => 'Regular'
    ];

    $courseRecord = Courses::where('course_name', $selectedCourse)->first();

    $batch = Batch::create([
        'batch_id' => $request->batch_id,
        'name' => $request->batch_id,
        'course' => $selectedCourse,
        'course_id' => $courseRecord?->_id,
        'class' => $classData['class'],
        'course_type' => $classData['course_type'],
        'medium' => $request->medium,
        'mode' => $request->mode,
        'delivery_mode' => $request->mode,
        'shift' => $request->shift,
        'branch_name' => $request->branch_name,
        'start_date' => $request->start_date,
        'installment_date_2' => $request->installment_date_2,
        'installment_date_3' => $request->installment_date_3,
        'status' => $request->status ?? 'Active'
    ]);

    BatchAssignment::create([
        'batch_id' => $batch->batch_id,
        'start_date' => $batch->start_date,
        'username' => null,
        'shift' => $batch->shift,
        'status' => 'Active'
    ]);

    // Debug after creation - FIXED
    Log::info('Batch Created:', [
        'id' => $batch->_id,
        'batch_id' => $batch->batch_id,
        'course_from_model' => $batch->course,
        'raw_attributes' => $batch->getAttributes(),
        'selected_course' => $selectedCourse
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Batch created successfully!'
    ]);
}

    /**
     * Update batch details
     */
    public function update(Request $request, $id)
    {
        $batch = Batch::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'batch_id' => 'required|string|unique:batches,batch_id,' . $id . ',_id',
            'course' => 'required|string',
            'medium' => 'required|string',
            'mode' => 'required|string',
            'shift' => 'required|string',
            'branch_name' => 'required|string',
            'start_date' => 'required|date',
            'installment_date_2' => 'nullable|date',
            'installment_date_3' => 'nullable|date',
            'status' => 'nullable|in:Active,Inactive'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $courseMapping = $this->getCourseMapping();
        $selectedCourse = $request->course;

        $classData = $courseMapping[$selectedCourse] ?? [
            'class' => $batch->class,
            'course_type' => $batch->course_type
        ];

        $batch->update([
            'batch_id' => $request->batch_id,
            'course' => $selectedCourse,
            'class' => $classData['class'],
            'course_type' => $classData['course_type'],
            'medium' => $request->medium,
            'mode' => $request->mode,
            'shift' => $request->shift,
            'branch_name' => $request->branch_name,
            'start_date' => $request->start_date,
            'installment_date_2' => $request->installment_date_2,
            'installment_date_3' => $request->installment_date_3,
            'status' => $request->status ?? 'Active'
        ]);

        $assignment = BatchAssignment::where('batch_id', $batch->batch_id)->first();
        if ($assignment) {
            $assignment->update([
                'start_date' => $batch->start_date,
                'shift' => $batch->shift,
            ]);
        }

        return redirect()->route('batches.index')->with('success', 'Batch updated successfully!');
    }

    /**
     * Toggle batch status
     */
    public function toggleStatus($id)
    {
        $batch = Batch::findOrFail($id);
        $newStatus = $batch->status === 'Active' ? 'Inactive' : 'Active';
        $batch->update(['status' => $newStatus]);

        return redirect()->route('batches.index')->with('success', 'Batch status updated successfully!');
    }

    /**
     * API endpoint to get course details
     */
    public function getCourseDetails(Request $request)
    {
        $courseName = $request->input('course');
        $courseMapping = $this->getCourseMapping();
        
        if (isset($courseMapping[$courseName])) {
            return response()->json(['success' => true, 'data' => $courseMapping[$courseName]]);
        }
        
        return response()->json(['success' => false, 'message' => 'Course not found'], 404);
    }

    /**
 * Export batches to Excel/CSV
 */
public function exportToExcel(Request $request)
{
    try {
        $search = $request->input('search', '');

        // Build query with same filters as index page
        $query = Batch::query();

        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('batch_id', 'like', '%' . $search . '%')
                  ->orWhere('course', 'like', '%' . $search . '%')
                  ->orWhere('class', 'like', '%' . $search . '%')
                  ->orWhere('course_type', 'like', '%' . $search . '%')
                  ->orWhere('medium', 'like', '%' . $search . '%')
                  ->orWhere('mode', 'like', '%' . $search . '%')
                  ->orWhere('shift', 'like', '%' . $search . '%');
            });
        }

        // Get all batches (not paginated for export)
        $batches = $query->orderBy('created_at', 'desc')->get();

        // Prepare CSV data
        $csvData = [];
        
        // Add headers
        $csvData[] = [
            'Serial No.',
            'Batch Code',
            'Class',
            'Course',
            'Course Type',
            'Branch',
            'Delivery Mode',
            'Medium',
            'Shift',
            'Start Date',
            'Installment Date 2',
            'Installment Date 3',
            'Status',
            'Created At',
            'Updated At'
        ];

        // Add data rows
        foreach ($batches as $index => $batch) {
            $csvData[] = [
                $index + 1,
                $batch->batch_id ?? '—',
                $batch->class ?? '—',
                $batch->course ?? '—',
                $batch->course_type ?? '—',
                $batch->branch_name ?? '—',
                $batch->mode ?? '—',
                $batch->medium ?? '—',
                $batch->shift ?? '—',
                $batch->start_date ?? '—',
                $batch->installment_date_2 ?? '—',
                $batch->installment_date_3 ?? '—',
                $batch->status ?? 'Active',
                $batch->created_at ? $batch->created_at->format('d-m-Y H:i:s') : '—',
                $batch->updated_at ? $batch->updated_at->format('d-m-Y H:i:s') : '—',
            ];
        }

        // Generate filename with timestamp
        $timestamp = now()->format('Y-m-d_His');
        $filename = "batches_export_{$timestamp}.csv";

        // Create CSV content
        $handle = fopen('php://temp', 'r+');
        
        foreach ($csvData as $row) {
            fputcsv($handle, $row);
        }
        
        rewind($handle);
        $csvContent = stream_get_contents($handle);
        fclose($handle);

        // Return as download
        return response($csvContent, 200)
            ->header('Content-Type', 'text/csv; charset=UTF-8')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');

    } catch (\Exception $e) {
        Log::error('Error exporting batches to Excel:', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        
        return redirect()->back()
            ->with('error', 'Failed to export data: ' . $e->getMessage());
    }
}

/**
 * Download sample Excel file for bulk batch import
 */
public function downloadSample()
{
    try {
        // Define sample data
        $sampleData = [
            ['Batch Code', 'Course', 'Branch', 'Start Date', 'Delivery Mode', 'Medium', 'Shift', 'Installment Date 2', 'Installment Date 3', 'Status'],
            ['20T1', 'Anthesis 11th NEET', 'Bikaner', '2025-04-01', 'Offline', 'English', 'Morning', '2025-05-01', '2025-06-01', 'Active'],
            ['19L1', 'Impulse 11th IIT', 'Bikaner', '2025-04-01', 'Online', 'Hindi', 'Evening', '2025-05-01', '2025-06-01', 'Active'],
            ['18M1', 'Seedling 10th', 'Bikaner', '2025-04-15', 'Offline', 'English', 'Morning', '', '', 'Active'],
        ];

        // Create CSV content
        $filename = 'sample_batches_import.csv';
        $handle = fopen('php://temp', 'r+');
        
        foreach ($sampleData as $row) {
            fputcsv($handle, $row);
        }
        
        rewind($handle);
        $csvContent = stream_get_contents($handle);
        fclose($handle);

        // Return as download
        return response($csvContent, 200)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');

    } catch (\Exception $e) {
        Log::error('Error generating sample file:', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        
        return redirect()->back()
            ->with('error', 'Failed to generate sample file: ' . $e->getMessage());
    }
}

/**
 * Import batches from Excel/CSV file
 */
public function import(Request $request)
{
    $request->validate([
        'import_file' => 'required|file|mimes:xlsx,xls,csv|max:2048',
    ]);

    try {
        $file = $request->file('import_file');
        $extension = $file->getClientOriginalExtension();
        
        // Read file content
        if ($extension === 'csv') {
            $data = array_map('str_getcsv', file($file->getRealPath()));
        } else {
            return redirect()->back()->with('error', 'Please use CSV format for imports.');
        }
        
        // Skip header row
        $header = array_shift($data);
        
        // Get course mapping
        $courseMapping = $this->getCourseMapping();
        
        $imported = 0;
        $skipped = 0;
        $errors = [];

        foreach ($data as $rowIndex => $row) {
            $rowNumber = $rowIndex + 2;
            
            // Skip empty rows
            if (empty(array_filter($row))) {
                continue;
            }
            
            // Validate required fields
            if (count($row) < 7) {
                $errors[] = "Row {$rowNumber}: Insufficient columns";
                $skipped++;
                continue;
            }
            
            $batchId = trim($row[0] ?? '');
            $course = trim($row[1] ?? '');
            $branchName = trim($row[2] ?? '');
            $startDate = trim($row[3] ?? '');
            $mode = trim($row[4] ?? '');
            $medium = trim($row[5] ?? '');
            $shift = trim($row[6] ?? '');
            $installmentDate2 = trim($row[7] ?? '');
            $installmentDate3 = trim($row[8] ?? '');
            $status = trim($row[9] ?? 'Active');
            
            // Validate required fields
            if (empty($batchId) || empty($course) || empty($branchName) || empty($startDate) || empty($mode) || empty($medium) || empty($shift)) {
                $errors[] = "Row {$rowNumber}: Missing required fields";
                $skipped++;
                continue;
            }
            
            // Check if batch already exists
            if (Batch::where('batch_id', $batchId)->exists()) {
                $errors[] = "Row {$rowNumber}: Batch code already exists ({$batchId})";
                $skipped++;
                continue;
            }
            
            // Get class and course type from mapping
            $classData = $courseMapping[$course] ?? null;
            
            if (!$classData) {
                $errors[] = "Row {$rowNumber}: Invalid course name ({$course})";
                $skipped++;
                continue;
            }
            
            try {
                // Create batch
                Batch::create([
                    'batch_id' => $batchId,
                    'course' => $course,
                    'class' => $classData['class'],
                    'course_type' => $classData['course_type'],
                    'branch_name' => $branchName,
                    'start_date' => $startDate,
                    'mode' => $mode,
                    'medium' => $medium,
                    'shift' => $shift,
                    'installment_date_2' => $installmentDate2 ?: null,
                    'installment_date_3' => $installmentDate3 ?: null,
                    'status' => $status === 'Inactive' ? 'Inactive' : 'Active',
                ]);
                
                $imported++;
                
            } catch (\Exception $e) {
                $errors[] = "Row {$rowNumber}: " . $e->getMessage();
                $skipped++;
                Log::error("Import error at row {$rowNumber}:", [
                    'error' => $e->getMessage(),
                    'data' => $row
                ]);
            }
        }
        
        // Build success message
        $message = "Import completed: {$imported} batches imported successfully";
        if ($skipped > 0) {
            $message .= ", {$skipped} rows skipped";
        }
        
        // If there are errors, add them to session
        if (!empty($errors)) {
            session()->flash('import_errors', $errors);
        }
        
        return redirect()->route('batches.index')
            ->with('success', $message);

    } catch (\Exception $e) {
        Log::error('Import file processing error:', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        
        return redirect()->back()
            ->with('error', 'Error processing file: ' . $e->getMessage());
    }
}
}