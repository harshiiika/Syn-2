<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\Batch;
use App\Models\User\BatchAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BatchController extends Controller
{
    /**
     * Course mapping configuration
     * Maps course names to their respective class and course type
     */
    private function getCourseMapping()
    {
        return [
            'Anthesis 11th NEET' => [
                'class' => '11th (XI)',
                'course_type' => 'Pre-Medical'
            ],
            'Momentum 12th NEET' => [
                'class' => '12th (XII)',
                'course_type' => 'Pre-Medical'
            ],
            'Dynamic Target NEET' => [
                'class' => 'Target (XII +)',
                'course_type' => 'Pre-Medical'
            ],
            'Impulse 11th IIT' => [
                'class' => '11th (XI)',
                'course_type' => 'Pre-Engineering'
            ],
            'Intensity 12th IIT' => [
                'class' => '12th (XII)',
                'course_type' => 'Pre-Engineering'
            ],
            'Thrust Target IIT' => [
                'class' => 'Target (XII +)',
                'course_type' => 'Pre-Engineering'
            ],
            'Seedling 10th' => [
                'class' => '10th (X)',
                'course_type' => 'Pre-Foundation'
            ],
            'Plumule 9th' => [
                'class' => '9th (IX)',
                'course_type' => 'Pre-Foundation'
            ],
            'Radicle 8th' => [
                'class' => '8th (VIII)',
                'course_type' => 'Pre-Foundation'
            ],
            'Nucleus 7th' => [
                'class' => '7th (VII)',
                'course_type' => 'Pre-Foundation'
            ],
            'Atom 6th' => [
                'class' => '6th (VI)',
                'course_type' => 'Pre-Foundation'
            ]
        ];
    }

    /**
     * Display all batches
     */
    public function index()
    {
        $batches = Batch::all();
        return view('master.batch.index', compact('batches'));
    }

    /**
     * Store a new batch with automatic field population
     */
    public function store(Request $request)
    {
        // Validate incoming request
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
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Get course mapping
        $courseMapping = $this->getCourseMapping();
        $selectedCourse = $request->course;

        // Extract class and course_type from mapping
        $classData = $courseMapping[$selectedCourse] ?? [
            'class' => 'Unknown',
            'course_type' => 'Regular'
        ];

        // Create the batch with auto-filled data
        $batch = Batch::create([
            'batch_id' => $request->batch_id,
            'course' => $selectedCourse,
            'class' => $classData['class'],              // Auto-filled
            'course_type' => $classData['course_type'],  // Auto-filled
            'medium' => $request->medium,
            'mode' => $request->mode,
            'shift' => $request->shift,
            'branch_name' => $request->branch_name,
            'start_date' => $request->start_date,
            'installment_date_2' => $request->installment_date_2,
            'installment_date_3' => $request->installment_date_3,
            'status' => $request->status ?? 'Active'
        ]);

        // Create batch assignment automatically
        BatchAssignment::create([
            'batch_id' => $batch->batch_id,
            'start_date' => $batch->start_date,
            'username' => null,
            'shift' => $batch->shift,
            'status' => 'Active'
        ]);

        return redirect()->route('batches.index')
            ->with('success', 'Batch created successfully!');
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
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Get course mapping for update
        $courseMapping = $this->getCourseMapping();
        $selectedCourse = $request->course;

        $classData = $courseMapping[$selectedCourse] ?? [
            'class' => $batch->class,           // Keep existing if not found
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

        // Update batch assignment
        $assignment = BatchAssignment::where('batch_id', $batch->batch_id)->first();
        if ($assignment) {
            $assignment->update([
                'start_date' => $batch->start_date,
                'shift' => $batch->shift,
            ]);
        }

        return redirect()->route('batches.index')
            ->with('success', 'Batch updated successfully!');
    }

    /**
     * Toggle batch status (Active/Inactive)
     */
    public function toggleStatus($id)
    {
        $batch = Batch::findOrFail($id);
        
        $newStatus = $batch->status === 'Active' ? 'Inactive' : 'Active';
        $batch->update(['status' => $newStatus]);

        return redirect()->route('batches.index')
            ->with('success', 'Batch status updated successfully!');
    }


    /**
     * API endpoint to get course details (for AJAX)
     */
    public function getCourseDetails(Request $request)
    {
        $courseName = $request->input('course');
        $courseMapping = $this->getCourseMapping();
        
        if (isset($courseMapping[$courseName])) {
            return response()->json([
                'success' => true,
                'data' => $courseMapping[$courseName]
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Course not found'
        ], 404);
    }

}