<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student\Inquiry;
use App\Models\Session\AcademicSession as Session;
use App\Models\Reports\Staff;
use App\Models\Master\Courses;
use Carbon\Carbon;

class InquiryHistoryController extends Controller
{
    /**
     * Display inquiry history index page with filters
     */
    public function index()
    {
        $sessions = $this->getAvailableSessions();
        $roles = $this->getAvailableRoles();
        $staffMembers = Staff::select('_id', 'name')->orderBy('name', 'asc')->get();
        
        return view('reports.inquiry-history.index', compact('sessions', 'roles', 'staffMembers'));
    }

    /**
     * Get inquiry data with filters via AJAX
     */
    public function getData(Request $request)
    {
        try {
            $query = Inquiry::query();

            // Apply session filter
            if ($request->filled('session')) {
                $query->where('session', $request->input('session'));
            }

            // Apply role filter
            if ($request->filled('role')) {
                $query->where('role', $request->role);
            }

            // Apply staff filter
            if ($request->filled('staff')) {
                $query->where('staff_name', $request->staff);
            }

            // Apply branch filter
            if ($request->filled('branch')) {
                $query->where('branch', $request->branch);
            }

            // Apply date from filter
            if ($request->filled('from_date')) {
                $fromDate = Carbon::parse($request->from_date)->startOfDay();
                $query->where('created_at', '>=', $fromDate);
            }

            // Apply date to filter
            if ($request->filled('to_date')) {
                $toDate = Carbon::parse($request->to_date)->endOfDay();
                $query->where('created_at', '<=', $toDate);
            }

            // Apply search filter
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('student_name', 'LIKE', "%{$search}%")
                      ->orWhere('father_name', 'LIKE', "%{$search}%")
                      ->orWhere('father_contact_no', 'LIKE', "%{$search}%")
                      ->orWhere('student_contact_no', 'LIKE', "%{$search}%")
                      ->orWhere('staff_name', 'LIKE', "%{$search}%");
                });
            }

            // Get paginated results
            $perPage = $request->input('per_page', 10);
            $inquiries = $query->orderBy('created_at', 'desc')
                               ->paginate($perPage);

            // Return JSON response with pagination data
            return response()->json([
                'success' => true,
                'data' => $inquiries->items(),
                'current_page' => $inquiries->currentPage(),
                'per_page' => $inquiries->perPage(),
                'total' => $inquiries->total(),
                'last_page' => $inquiries->lastPage(),
                'from' => $inquiries->firstItem(),
                'to' => $inquiries->lastItem(),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error loading inquiries: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * View single inquiry details
     */
    public function view($id)
    {
        try {
            $inquiry = Inquiry::findOrFail($id);
            return view('reports.inquiry-history.view', compact('inquiry'));
        } catch (\Exception $e) {
            return redirect()->route('reports.inquiry-history.index')
                           ->with('error', 'Inquiry not found');
        }
    }

    /**
     * Export inquiries to CSV
     */
    public function export(Request $request)
    {
        try {
            $query = Inquiry::query();

            // Apply same filters as getData
            if ($request->filled('session')) {
                $query->where('session', $request->input('session'));
            }

            if ($request->filled('role')) {
                $query->where('role', $request->role);
            }

            if ($request->filled('staff')) {
                $query->where('staff_name', $request->staff);
            }

            if ($request->filled('branch')) {
                $query->where('branch', $request->branch);
            }

            if ($request->filled('from_date')) {
                $fromDate = Carbon::parse($request->from_date)->startOfDay();
                $query->where('created_at', '>=', $fromDate);
            }

            if ($request->filled('to_date')) {
                $toDate = Carbon::parse($request->to_date)->endOfDay();
                $query->where('created_at', '<=', $toDate);
            }

            // Get all matching inquiries (no pagination for export)
            $inquiries = $query->orderBy('created_at', 'desc')->get();

            // Generate filename with timestamp
            $filename = "inquiry_history_" . date('Y-m-d_His') . ".csv";
            
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => "attachment; filename=\"$filename\"",
                'Pragma' => 'no-cache',
                'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
                'Expires' => '0'
            ];

            $callback = function() use ($inquiries) {
                $file = fopen('php://output', 'w');
                
                // Add UTF-8 BOM for proper Excel encoding
                fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
                
                // Add CSV headers
                fputcsv($file, [
                    'S.No',
                    'Date',
                    'Student Name',
                    'Father Name',
                    'Father Contact No',
                    'Student Contact No',
                    'Staff Name',
                    'Role',
                    'Session',
                    'Branch',
                    'Category',
                    'State',
                    'City',
                    'Course Type',
                    'Course Name',
                    'Delivery Mode',
                    'Medium',
                    'Course Content',
                    'Total Fee',
                    'Discount %',
                    'Discounted Fee',
                    'Status'
                ]);

                // Add data rows
                $serialNo = 1;
                foreach ($inquiries as $inquiry) {
                    fputcsv($file, [
                        $serialNo++,
                        $inquiry->created_at ? $inquiry->created_at->format('d-M-Y') : '',
                        $inquiry->student_name ?? '',
                        $inquiry->father_name ?? '',
                        $inquiry->father_contact_no ?? '',
                        $inquiry->student_contact_no ?? '',
                        $inquiry->staff_name ?? '',
                        $inquiry->role ?? '',
                        $inquiry->session ?? '',
                        $inquiry->branch ?? '',
                        $inquiry->category ?? '',
                        $inquiry->state ?? '',
                        $inquiry->city ?? '',
                        $inquiry->course_type ?? '',
                        $inquiry->course_name ?? '',
                        $inquiry->delivery_mode ?? '',
                        $inquiry->medium ?? '',
                        $inquiry->course_content ?? '',
                        $inquiry->total_fee_before_discount ?? '',
                        $inquiry->discount_percentage ?? '0',
                        $inquiry->discounted_fee ?? '',
                        $inquiry->status ?? 'Pending'
                    ]);
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);

        } catch (\Exception $e) {
            return redirect()->route('reports.inquiry-history.index')
                           ->with('error', 'Error exporting data: ' . $e->getMessage());
        }
    }

    /**
     * Get available academic sessions
     */
    private function getAvailableSessions()
    {
        $currentYear = date('Y');
        $sessions = [];
        
        // Generate sessions from 2 years back to 2 years forward
        for ($i = -2; $i <= 2; $i++) {
            $startYear = $currentYear + $i;
            $endYear = $startYear + 1;
            $sessions[] = $startYear . '-' . substr($endYear, -2); // e.g., 2024-25
        }
        
        return $sessions;
    }

    /**
     * Get available staff roles
     */
    private function getAvailableRoles()
    {
        return [
            'Teacher',
            'Test Series Executive',
            'Account Executive',
            'Back Office',
            'Front Office',
            'Directors',
            'Floor Incharge',
            'Counselor',
            'Telecaller',
            'Admin'
        ];
    }
}