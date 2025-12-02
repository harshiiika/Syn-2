<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student\Inquiry;
use App\Models\Session\AcademicSession as session;
use App\Models\Reports\Staff;
use App\Models\Master\Courses;
use Carbon\Carbon;

class InquiryHistoryController extends Controller
{
    public function index()
    {
        $sessions = $this->getAvailableSessions();
        $roles = $this->getAvailableRoles();
        $staffMembers = Staff::select('_id', 'name')->get();
        
        return view('reports.inquiry-history.index', compact('sessions', 'roles', 'staffMembers'));
    }

    public function getData(Request $request)
    {
        $query = Inquiry::query();

        // Apply filters
        if ($request->filled('session')) {
            $query->where('session', $request->session);
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('staff')) {
            $query->where('staff_name', $request->staff);
        }

        if ($request->filled('from_date')) {
            $fromDate = Carbon::parse($request->from_date)->startOfDay();
            $query->where('created_at', '>=', $fromDate);
        }

        if ($request->filled('to_date')) {
            $toDate = Carbon::parse($request->to_date)->endOfDay();
            $query->where('created_at', '<=', $toDate);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('student_name', 'LIKE', "%{$search}%")
                  ->orWhere('father_name', 'LIKE', "%{$search}%")
                  ->orWhere('father_contact_no', 'LIKE', "%{$search}%")
                  ->orWhere('student_contact_no', 'LIKE', "%{$search}%");
            });
        }

        // Get paginated results
        $perPage = $request->input('per_page', 10);
        $inquiries = $query->orderBy('created_at', 'desc')
                           ->paginate($perPage);

        return response()->json($inquiries);
    }

    public function view($id)
    {
        $inquiry = Inquiry::findOrFail($id);
        return view('reports.inquiry-history.view', compact('inquiry'));
    }

    public function export(Request $request)
    {
        $query = Inquiry::query();

        // Apply same filters as getData
        if ($request->filled('session')) {
            $query->where('session', $request->session);
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('staff')) {
            $query->where('staff_name', $request->staff);
        }

        if ($request->filled('from_date')) {
            $fromDate = Carbon::parse($request->from_date)->startOfDay();
            $query->where('created_at', '>=', $fromDate);
        }

        if ($request->filled('to_date')) {
            $toDate = Carbon::parse($request->to_date)->endOfDay();
            $query->where('created_at', '<=', $toDate);
        }

        $inquiries = $query->get();

        $filename = "inquiry_history_" . date('Y-m-d_His') . ".csv";
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($inquiries) {
            $file = fopen('php://output', 'w');
            
            // Add CSV headers
            fputcsv($file, [
                'Serial No',
                'Student Name',
                'Father Name',
                'Contact No',
                'Staff',
                'Course Name',
                'Delivery Mode',
                'Course Content',
                'Status',
                'Date'
            ]);

            // Add data rows
            $serialNo = 1;
            foreach ($inquiries as $inquiry) {
                fputcsv($file, [
                    $serialNo++,
                    $inquiry->student_name ?? '',
                    $inquiry->father_name ?? '',
                    $inquiry->father_contact_no ?? '',
                    $inquiry->staff_name ?? '',
                    $inquiry->course_name ?? '',
                    $inquiry->delivery_mode ?? '',
                    $inquiry->course_content ?? '',
                    $inquiry->status ?? '',
                    $inquiry->created_at ? $inquiry->created_at->format('Y-m-d H:i:s') : ''
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function getAvailableSessions()
    {
        $currentYear = date('Y');
        $sessions = [];
        
        for ($i = -2; $i <= 2; $i++) {
            $startYear = $currentYear + $i;
            $endYear = $startYear + 1;
            $sessions[] = $startYear . '-' . $endYear;
        }
        
        return $sessions;
    }

    private function getAvailableRoles()
    {
        return [
            'Teacher',
            'Test Series Executive',
            'Account Executive',
            'Back Office',
            'Front Office',
            'Directors',
            'Floor Incharge'
        ];
    }
}