<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\CoursesRequest;
use App\Models\Master\Courses;
use App\Models\Master\CourseImport;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\Log;


/**
 * CoursesController - Manages course catalog operations
 * Handles CRUD operations for educational courses including search, pagination, validation, and bulk import
 */
class CoursesController extends Controller
{
    /**
     * Display paginated list of courses with optional search filtering
     * @param Request $request - Contains pagination and search parameters
     * @return \Illuminate\View\View
     */
     public function index(Request $request)
    {
        // Get per_page value from request, default to 10
        $perPage = $request->input('per_page', 10);
        
        // Validate per_page to only allow specific values
        if (!in_array($perPage, [5, 10, 25, 50, 100])) {
            $perPage = 10;
        }

        // Get search query
        $search = $request->input('search', '');

        // Build the query
        $query = Courses::query();

        // Apply search filter if search term exists
        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('course_name', 'like', '%' . $search . '%')
                  ->orWhere('course_type', 'like', '%' . $search . '%')
                  ->orWhere('class_name', 'like', '%' . $search . '%')
                  ->orWhere('course_code', 'like', '%' . $search . '%')
                  ->orWhere('status', 'like', '%' . $search . '%');
            });
        }

        // Get paginated courses
        $courses = $query->orderBy('created_at', 'desc')
                        ->paginate($perPage)
                        ->appends([
                            'search' => $search,
                            'per_page' => $perPage
                        ]);

        return view('master.courses.index', compact('courses', 'search'));
    }


    /**
     * Download sample Excel file for bulk import
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function downloadSampleFile()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set headers
        $headers = ['Course Name', 'Course Type', 'Class Name', 'Course Code', 'Subjects', 'Status'];
        $sheet->fromArray([$headers], null, 'A1');

        // Style headers
        $headerStyle = [
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'FF6F42C1'],
            ],
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFFFF'],
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
        ];
        $sheet->getStyle('A1:F1')->applyFromArray($headerStyle);

        // Add sample data
        $sampleData = [
            ['Mathematics 101', 'Pre - Engineering', '11th (XI)', 'MATH-101', 'Algebra; Geometry; Trigonometry', 'active'],
            ['Physics Basics', 'Pre - Medical', '12th (XII)', 'PHY-201', 'Mechanics; Thermodynamics; Optics', 'active'],
            ['Chemistry Advanced', 'Pre - Engineering', '12th (XII)', 'CHEM-301', 'Organic; Inorganic; Physical Chemistry', 'inactive'],
        ];
        $sheet->fromArray($sampleData, null, 'A2');

        // Auto-size columns
        foreach (range('A', 'F') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Create temporary file
        $writer = new Xlsx($spreadsheet);
        $fileName = 'Courses_Sample_' . date('Y-m-d_H-i-s') . '.xlsx';
        $filePath = storage_path('temp/' . $fileName);

        // Create temp directory if it doesn't exist
        if (!file_exists(storage_path('temp'))) {
            mkdir(storage_path('temp'), 0755, true);
        }

        $writer->save($filePath);

        return response()->download($filePath)->deleteFileAfterSend(true);
    }

    /**
 * Handle bulk course import from Excel file
 * @param Request $request - Contains uploaded Excel file
 * @return \Illuminate\Http\RedirectResponse
 */
public function importCourses(Request $request)
{
    $request->validate([
        'import_file' => 'required|mimes:xlsx,xls,csv|max:2048'
    ]);

    try {
        $file = $request->file('import_file');
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file->path());
        $sheet = $spreadsheet->getActiveSheet();
        $data = $sheet->toArray();

        $imported = 0;
        $updated = 0;
        $failed = 0;
        $errors = [];

        // Skip header row and process data
        foreach (array_slice($data, 1) as $rowIndex => $row) {
            // Skip completely empty rows
            if (empty(array_filter($row))) continue;

            try {
                $rowNum = $rowIndex + 2; // Actual Excel row number

                // Validate required fields
                if (empty($row[0]) || empty($row[1]) || empty($row[2]) || empty($row[3]) || empty($row[5])) {
                    $errors[] = "Row {$rowNum}: Missing required fields";
                    $failed++;
                    continue;
                }

                // Parse and standardize subjects
                $subjectsRaw = !empty($row[4]) ? $row[4] : '';
                $subjects = array_map('trim', explode(';', $subjectsRaw));
                $subjects = array_filter($subjects); // Remove empty values
                
                // Standardize subject names (capitalize each word)
                $subjects = array_map(function($subject) {
                    return ucwords(strtolower(trim($subject)));
                }, $subjects);
                
                // Remove duplicates
                $subjects = array_values(array_unique($subjects));

                if (empty($subjects)) {
                    $errors[] = "Row {$rowNum}: At least one subject is required";
                    $failed++;
                    continue;
                }

                // Standardize and validate course type
                $courseType = trim($row[1]);
                $validTypes = ['Pre - Foundation', 'Pre - Medical', 'Pre - Engineering'];
                if (!in_array($courseType, $validTypes)) {
                    $errors[] = "Row {$rowNum}: Invalid course type '{$courseType}'";
                    $failed++;
                    continue;
                }

                // Prepare course data
                $courseData = [
                    'course_name' => trim($row[0]),
                    'course_type' => $courseType,
                    'class_name' => trim($row[2]),
                    'course_code' => trim($row[3]),
                    'subjects' => $subjects,
                    'status' => strtolower(trim($row[5])) === 'inactive' ? 'inactive' : 'active',
                ];

                // Check if course already exists by course_code (case-insensitive)
                $existing = Courses::where('course_code', 'LIKE', $courseData['course_code'])->first();
                
                if ($existing) {
                    // Update existing course
                    $existing->update($courseData);
                    $updated++;
                    Log::info("Updated course: {$courseData['course_code']}");
                } else {
                    // Create new course - FORCE SAVE TO DATABASE
                    $newCourse = new Courses($courseData);
                    $saved = $newCourse->save();
                    
                    if ($saved) {
                        $imported++;
                        Log::info("Created new course: {$courseData['course_code']}", [
                            'id' => $newCourse->id ?? $newCourse->_id,
                            'data' => $courseData
                        ]);
                    } else {
                        $errors[] = "Row {$rowNum}: Failed to save to database";
                        $failed++;
                        Log::error("Failed to save course: {$courseData['course_code']}");
                    }
                }

            } catch (\Exception $e) {
                $errors[] = "Row {$rowNum}: " . $e->getMessage();
                $failed++;
                Log::error("Import error on row {$rowNum}: " . $e->getMessage());
            }
        }

        // Log import summary
        try {
            if (class_exists('App\Models\Master\CourseImport')) {
                CourseImport::create([
                    'filename' => $file->getClientOriginalName(),
                    'imported_count' => $imported,
                    'updated_count' => $updated,
                    'failed_count' => $failed,
                    'errors' => $errors,
                ]);
            }
        } catch (\Exception $logError) {
            Log::warning('CourseImport logging failed: ' . $logError->getMessage());
        }

        // Prepare success message
        $message = "Import completed! ";
        $message .= $imported > 0 ? "{$imported} new courses created. " : "";
        $message .= $updated > 0 ? "{$updated} courses updated. " : "";
        $message .= $failed > 0 ? "{$failed} failed. " : "";
        
        if (!empty($errors)) {
            $message .= "Errors: " . implode("; ", array_slice($errors, 0, 3));
            if (count($errors) > 3) {
                $message .= " (and " . (count($errors) - 3) . " more)";
            }
        }

        return redirect()->back()->with('success', $message);

    } catch (\Exception $e) {
        Log::error('Import failed: ' . $e->getMessage());
        return redirect()->back()->with('error', 'Import failed: ' . $e->getMessage());
    }
}

    /**
     * Show form for creating new course
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('courses.create');
    }

    /**
     * Display edit form for specific course
     * @param string $id - Course MongoDB ID
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $course = Courses::findOrFail($id);
        $courses = Courses::orderBy('created_at', 'desc')->paginate(10);
        
        return view('master.courses.index', compact('courses', 'course'));
    }

    /**
     * Store newly created course in database
     * @param Request $request - Contains course form data
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
       $validated = $request->validate([
        'course_name' => 'required|string|max:255',
        'course_type' => 'required|string',
        'class_name' => 'required|string',
        'course_code' => 'required|string|max:255|unique:courses,course_code',
        'subjects' => 'required|array|min:1',
        'subjects.*' => 'string|max:100',
        'status' => 'required|in:active,inactive'
    ]);

      
    // Standardize subject names (capitalize first letter of each word)
    $validated['subjects'] = array_map(function($subject) {
        return ucwords(strtolower(trim($subject)));
    }, $validated['subjects']);

    // Remove duplicates (case-insensitive)
    $validated['subjects'] = array_values(array_unique(array_map('strtolower', $validated['subjects'])));
    $validated['subjects'] = array_map('ucwords', $validated['subjects']);

    Courses::create($validated);

    return redirect()->back()->with('success', 'Course created successfully!');
}

    /**
     * Update existing course with new data
     * @param Request $request - Contains updated course form data
     * @param string $id - Course MongoDB ID to update
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'course_name' => 'required|string|max:255',
            'course_type' => 'required|string',
            'class_name' => 'required|string',
            'course_code' => 'required|string|max:255',
            'subjects' => 'required|array|min:1',
            'subjects.*' => 'string',
            'status' => 'required|in:active,inactive'
        ]);

        $course = Courses::findOrFail($id);
        $course->update($validated);

        return redirect()->back()->with('success', 'Course updated successfully!');
    }

    /**
     * Remove course from database
     * @param string $id - Course MongoDB ID to delete
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $course = Courses::findOrFail($id);
        $course->delete();
        
        return redirect()->route('master.courses.index')->with('success', 'Course deleted.');
    }

    /**
 * Get all valid subjects list
 * @return \Illuminate\Http\JsonResponse
 */
public function getValidSubjects()
{
    $standardSubjects = [
        'Mathematics', 'Physics', 'Chemistry', 'Biology',
        'English', 'Hindi', 'Sanskrit', 'Social Science',
        'Computer Science', 'Economics', 'Accountancy',
        'Business Studies', 'Political Science', 'History',
        'Geography', 'Psychology', 'Physical Education',
        'Biotechnology', 'Engineering Drawing', 'Informatics Practices',
        'Algebra', 'Geometry', 'Trigonometry', 'Calculus',
        'Mechanics', 'Thermodynamics', 'Optics', 'Electromagnetism',
        'Organic Chemistry', 'Inorganic Chemistry', 'Physical Chemistry',
        'Zoology', 'Botany', 'Microbiology', 'Genetics',
        'Biochemistry', 'Environmental Science', 'Statistics'
    ];
    
    return response()->json($standardSubjects);
}

/**
 * Get standardized subject suggestions with fuzzy matching
 * @param Request $request - Contains partial subject name
 * @return \Illuminate\Http\JsonResponse
 */
public function getSubjectSuggestions(Request $request)
{
    $query = strtolower(trim($request->get('query', '')));
    
    if (strlen($query) < 1 || empty($query)) {
        return response()->json([]);
    }

    // Standard subject list (SINGLE SOURCE OF TRUTH)
    $standardSubjects = [
        'Mathematics', 'Physics', 'Chemistry', 'Biology',
        'English', 'Hindi', 'Sanskrit', 'Social Science',
        'Computer Science', 'Economics', 'Accountancy',
        'Business Studies', 'Political Science', 'History',
        'Geography', 'Psychology', 'Physical Education',
        'Biotechnology', 'Engineering Drawing', 'Informatics Practices',
        'Algebra', 'Geometry', 'Trigonometry', 'Calculus',
        'Mechanics', 'Thermodynamics', 'Optics', 'Electromagnetism',
        'Organic Chemistry', 'Inorganic Chemistry', 'Physical Chemistry',
        'Zoology', 'Botany', 'Microbiology', 'Genetics',
        'Biochemistry', 'Environmental Science', 'Statistics'
    ];

    $matches = [];
    foreach ($standardSubjects as $subject) {
        $subjectLower = strtolower($subject);
        $similarity = 0;
        
        // Exact match or starts with
        if ($subjectLower === $query) {
            $similarity = 100;
        } elseif (strpos($subjectLower, $query) === 0) {
            $similarity = 95;
        } elseif (strpos($subjectLower, $query) !== false) {
            $similarity = 80;
        } else {
            // Fuzzy matching
            $distance = levenshtein($query, substr($subjectLower, 0, strlen($query)));
            $maxLength = max(strlen($query), strlen($query));
            $similarity = max(0, (1 - ($distance / $maxLength)) * 60);
        }
        
        if ($similarity > 40) {
            $matches[] = [
                'subject' => $subject,
                'similarity' => $similarity
            ];
        }
    }

    usort($matches, function($a, $b) {
        return $b['similarity'] <=> $a['similarity'];
    });

    return response()->json(array_slice(array_column($matches, 'subject'), 0, 8));
}
/**
 * Get active courses list for dropdowns
 * @return \Illuminate\Http\JsonResponse
 */
public function getActiveCourses()
{
    try {
        $courses = Courses::where('status', 'active')
            ->orderBy('course_name', 'asc')
            ->get(['_id', 'course_name', 'course_type', 'class_name']);
        
        return response()->json([
            'success' => true,
            'courses' => $courses
        ]);
    } catch (\Exception $e) {
        Log::error('Error fetching courses: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Failed to fetch courses',
            'courses' => []
        ], 500);
    }
}
}