<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\Branch;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    /**
     * Display all branches with search and pagination
     */
    public function index(Request $request)
{
    // Get per_page value from request, default to 10
    $perPage = $request->input('per_page', 10);
    
    // Validate per_page to only allow specific values (ADDED 5 for testing)
    if (!in_array($perPage, [5, 10, 25, 50, 100])) {
        $perPage = 10;
    }
    
    $search = $request->input('search');

    $query = Branch::query();

    // Apply search filter if search term exists
    if ($search) {
        $query->where(function($q) use ($search) {
            $q->where('name', 'like', '%' . $search . '%')
              ->orWhere('city', 'like', '%' . $search . '%')
              ->orWhere('status', 'like', '%' . $search . '%');
        });
    }

    // Paginate results
    $branches = $query->paginate($perPage)->appends([
        'search' => $search,
        'per_page' => $perPage
    ]);

    return view('master.branch.branch', compact('branches'));
}

    /**
     * Store a new branch
     */
    public function store(Request $request) 
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'city' => 'required|string|max:255',
        ]);

        Branch::create([
            'name' => $request->input('name'),
            'city' => $request->input('city'),
            'status' => 'Active',
        ]);

        return redirect()->route('branches.index')->with('success', 'Branch added successfully!');
    }

    /**
     * Download sample Excel file for branch import
     */
    public function downloadSample()
    {
        // Option 1: If you have a sample file in public/samples directory
        $filePath = public_path('samples/branches_sample.xlsx');
        
        if (file_exists($filePath)) {
            return response()->download($filePath, 'branches_sample.xlsx');
        }
        
        // Option 2: Generate a simple CSV sample on the fly
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="branches_sample.csv"',
        ];

        $columns = ['Branch Name', 'City', 'Status'];
        $sampleData = [
            ['Synthesis Main Branch', 'Bikaner', 'Active'],
        ];

        $callback = function() use ($columns, $sampleData) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            foreach ($sampleData as $row) {
                fputcsv($file, $row);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Import branches from Excel/CSV file
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
                // For Excel files, you would need Laravel Excel package
                // composer require maatwebsite/excel
                return redirect()->back()->with('error', 'Excel import requires Laravel Excel package. Please use CSV format.');
            }
            
            // Skip header row
            $header = array_shift($data);
            
            $imported = 0;
            foreach ($data as $row) {
                if (count($row) >= 2) { // At least name and city required
                    Branch::create([
                        'name' => $row[0] ?? '',
                        'city' => $row[1] ?? '',
                        'status' => $row[2] ?? 'Active',
                    ]);
                    $imported++;
                }
            }
            
            return redirect()->route('branches.index')
                ->with('success', "Successfully imported {$imported} branches!");
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error importing file: ' . $e->getMessage());
        }
    }

    /**
     * Update an existing branch
     */
    public function update(Request $request, $id)
    {
        $branch = Branch::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'city' => 'required|string|max:255',
        ]);

        $branch->update([
            'name' => $request->input('name'),
            'city' => $request->input('city'),
        ]);

        return redirect()->route('branches.index')->with('success', 'Branch updated successfully!');
    }

    /**
     * Toggle branch status
     */
    public function toggleStatus($id)
    {
        $branch = Branch::findOrFail($id);

        $newStatus = ($branch->status ?? 'Active') === 'Active' ? 'Deactivated' : 'Active';

        $branch->update(['status' => $newStatus]);

        return redirect()->route('branches.index')->with('success', 'Branch status changed to ' . $newStatus . '!');
    }
    
/**
 * Get active branches for API
 */
public function getActiveBranches()
{
    try {
        \Log::info('=== FETCHING ACTIVE BRANCHES ===');
        
        // First check total branches in database
        $totalBranches = Branch::count();
        \Log::info('Total branches in database: ' . $totalBranches);
        
        // Get all branches to see what statuses exist
        $allBranches = Branch::all(['_id', 'name', 'city', 'status']);
        \Log::info('All branches:', $allBranches->toArray());
        
        // Query with case-insensitive status check OR get all active branches
        $branches = Branch::where(function($query) {
                $query->where('status', 'Active')
                      ->orWhere('status', 'active');
            })
            ->orderBy('name', 'asc')
            ->get(['_id', 'name', 'city', 'status']);
            
        \Log::info('Active branches found: ' . $branches->count());
        \Log::info('Active branches data:', $branches->toArray());
        
        // If no active branches found, return ALL branches as fallback for debugging
        if ($branches->isEmpty() && $totalBranches > 0) {
            \Log::warning('  No active branches found, returning all branches');
            $branches = $allBranches;
        }
        
        // Convert MongoDB ObjectId to string
        $branches = $branches->map(function($branch) {
            return [
                '_id' => (string) $branch->_id,
                'name' => $branch->name,
                'city' => $branch->city ?? '',
                'status' => $branch->status ?? 'Active'
            ];
        });
            
        return response()->json([
            'success' => true,
            'branches' => $branches,
            'total_count' => $branches->count()
        ]);
        
    } catch (\Exception $e) {
        \Log::error('  Get active branches error: ' . $e->getMessage());
        \Log::error('Stack trace: ' . $e->getTraceAsString());
        
        return response()->json([
            'success' => false,
            'message' => $e->getMessage(),
            'branches' => []
        ], 500);
    }
}
}