<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User\User;
use Illuminate\Http\Request;
use App\Models\User\Role;
use App\Models\User\Department;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;


class UserController extends Controller
{
    /**
     * Add a new employee
     */

    //to add an employee/user 
    //1. validate the fields
    //2. auto assign roles based on department
    //3. find or create department and role
    //4. create user with the provided details and assigned role and department and redirect successfully

    
    /**
     * Display employee listing with search and pagination
     */
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $search = $request->input('search', '');

        // Build query with search
        $query = User::query();

        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%')
                  ->orWhere('mobileNumber', 'like', '%' . $search . '%');
            });
        }

        // Paginate results
        $users = $query->paginate($perPage)->appends([
            'search' => $search,
            'per_page' => $perPage
        ]);

        // Process department and role names
        $allRoleIds = collect();
        $allDepartmentIds = collect();

        foreach ($users as $user) {
            $userDepts = data_get($user, 'departments', []);
            $userRoles = data_get($user, 'roles', []);

            if (is_array($userDepts)) {
                $allDepartmentIds = $allDepartmentIds->merge($userDepts);
            }
            
            if (is_array($userRoles)) {
                $allRoleIds = $allRoleIds->merge($userRoles);
            }
        }

        $allRoleIds = $allRoleIds->map(fn($id) => (string) (is_object($id) ? $id : $id))->unique()->filter();
        $allDepartmentIds = $allDepartmentIds->map(fn($id) => (string) (is_object($id) ? $id : $id))->unique()->filter();

        $departments = Department::whereIn('_id', $allDepartmentIds->toArray())
            ->get()
            ->keyBy(fn($dept) => (string) $dept->_id);

        $roles = Role::whereIn('_id', $allRoleIds->toArray())
            ->get()
            ->keyBy(fn($role) => (string) $role->_id);

        foreach ($users as $user) {
            $userDepts = data_get($user, 'departments', []);
            $userRoles = data_get($user, 'roles', []);

            $user->departmentNames = collect(is_array($userDepts) ? $userDepts : [])
                ->map(fn($id) => $departments->get((string) (is_object($id) ? $id : $id))?->name)
                ->filter()
                ->values();

            $user->roleNames = collect(is_array($userRoles) ? $userRoles : [])
                ->map(fn($id) => $roles->get((string) (is_object($id) ? $id : $id))?->name)
                ->filter()
                ->values();
        }

        return view('user.emp.emp', compact('users'));
    }

    /**
     * Show employees
     */
public function addUser(Request $request)
{
    // Log incoming request
    \Log::info('=== ADD USER REQUEST STARTED ===');
    \Log::info('Request data:', $request->all());

    try {
        // Validate
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'mobileNumber' => 'nullable|string|max:15',
            'alternateNumber' => 'nullable|string|max:15',
            'branch' => 'required|string|max:255',
            'department' => 'required|string',
            'password' => 'required|string|min:6|confirmed',
        ]);

        \Log::info('Validation passed:', $validated);

        // Map departments to default roles
        $departmentRoleMapping = [
            'Front Office' => 'Finance',
            'Back Office' => 'Administration',
            'Office' => 'Attendance',
            'Test Management' => 'Floor Incharge',
            'Admin' => 'Records'
        ];

        $selectedDepartment = $request->department;
        $assignedRole = $departmentRoleMapping[$selectedDepartment] ?? 'Administration';

        \Log::info('Department:', ['selected' => $selectedDepartment, 'role' => $assignedRole]);

        // Fetch or create department
        $department = Department::firstOrCreate(['name' => $selectedDepartment]);
        \Log::info('Department created/found:', ['id' => $department->_id, 'name' => $department->name]);

        // Fetch or create role
        $role = Role::firstOrCreate(['name' => $assignedRole]);
        \Log::info('Role created/found:', ['id' => $role->_id, 'name' => $role->name]);

        // Prepare user data
        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'mobileNumber' => $request->mobileNumber,
            'alternateNumber' => $request->alternateNumber,
            'branch' => $request->branch,
            'roles' => [$role->_id],
            'departments' => [$department->_id],
            'password' => Hash::make($request->password),
            'status' => 'Active',
        ];

        \Log::info('User data prepared:', $userData);

        // Create user
        $user = User::create($userData);
        
        \Log::info('User created successfully:', [
            'id' => $user->_id,
            'name' => $user->name,
            'email' => $user->email
        ]);

        // Verify user was saved
        $savedUser = User::find($user->_id);
        if ($savedUser) {
            \Log::info('User verified in database:', ['id' => $savedUser->_id]);
        } else {
            \Log::error('User NOT found in database after creation!');
        }

        return redirect()->route('user.emp.emp')
            ->with('success', 'User added successfully!')
            ->with('new_user_id', $user->_id);

    } catch (\Illuminate\Validation\ValidationException $e) {
        \Log::error('Validation failed:', $e->errors());
        return back()->withErrors($e->errors())->withInput();
    } catch (\Exception $e) {
        \Log::error('Error creating user:', [
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString()
        ]);
        return back()->with('error', 'Failed to create user: ' . $e->getMessage())->withInput();
    }
}  
    /**
     * Show employees
     */

    //to simply show employee/user
    //1. fetch all users
    //2. collect all unique role and department IDs from users
    //3. fetch roles and departments based on collected IDs
    //4. attach role and department names to each user for display
    //5. return the view with users

  public function showUser(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $search = $request->input('search', '');

        // Build query with search
        $query = User::query();

        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%')
                  ->orWhere('mobileNumber', 'like', '%' . $search . '%');
            });
        }

        // Paginate results
        $users = $query->paginate($perPage)->appends([
            'search' => $search,
            'per_page' => $perPage
        ]);

        // Process department and role names
        $allRoleIds = collect();
        $allDepartmentIds = collect();

        foreach ($users as $user) {
            $userDepts = data_get($user, 'departments', []);
            $userRoles = data_get($user, 'roles', []);

            if (is_array($userDepts)) {
                $allDepartmentIds = $allDepartmentIds->merge($userDepts);
            }
            
            if (is_array($userRoles)) {
                $allRoleIds = $allRoleIds->merge($userRoles);
            }
        }

        $allRoleIds = $allRoleIds->map(fn($id) => (string) (is_object($id) ? $id : $id))->unique()->filter();
        $allDepartmentIds = $allDepartmentIds->map(fn($id) => (string) (is_object($id) ? $id : $id))->unique()->filter();

        $departments = Department::whereIn('_id', $allDepartmentIds->toArray())
            ->get()
            ->keyBy(fn($dept) => (string) $dept->_id);

        $roles = Role::whereIn('_id', $allRoleIds->toArray())
            ->get()
            ->keyBy(fn($role) => (string) $role->_id);

        foreach ($users as $user) {
            $userDepts = data_get($user, 'departments', []);
            $userRoles = data_get($user, 'roles', []);

            $user->departmentNames = collect(is_array($userDepts) ? $userDepts : [])
                ->map(fn($id) => $departments->get((string) (is_object($id) ? $id : $id))?->name)
                ->filter()
                ->values();

            $user->roleNames = collect(is_array($userRoles) ? $userRoles : [])
                ->map(fn($id) => $roles->get((string) (is_object($id) ? $id : $id))?->name)
                ->filter()
                ->values();
        }

        return view('user.emp.emp', compact('users'));
    }


    /**
     * Update an existing employee - Fixed version
     */


    //to update an employee/user
    //1. validate the fields
    //2. auto assign roles based on department
    //3. find or create department and role
    //4. update user with the provided details and assigned role and department and redirect successfully
    public function updateUser(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return redirect()->route('user.emp.emp')->with('error', 'User not found!');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id . ',_id',
            'mobileNumber' => [
                'required',
                'regex:/^[0-9]{10}$/',  // Exactly 10 digits
            ],
            'alternateNumber' => [
                'nullable',
                'regex:/^[0-9]{10}$/',  // Exactly 10 digits if provided
            ],
            'branch' => 'required|string',
            'department' => 'required|string',
        ], [
            'mobileNumber.regex' => 'Mobile number must be exactly 10 digits.',
            'alternateNumber.regex' => 'Alternate mobile number must be exactly 10 digits.',
        ]);

        // Auto-assign roles based on department
        $departmentRoleMapping = [
            'Front Office' => 'Administration',
            'Back Office' => 'Administration',
            'Office' => 'Administration',
            'Test Management' => 'Administration',
            'Admin' => 'Admin'
        ];

        $selectedDepartment = $request->input('department');
        $assignedRole = $departmentRoleMapping[$selectedDepartment] ?? 'Administration';

        // Find or create department
        $department = Department::where('name', $selectedDepartment)->first();
        if (!$department) {
            $department = Department::create(['name' => $selectedDepartment]);
        }

        // Find or create role
        $role = Role::where('name', $assignedRole)->first();
        if (!$role) {
            $role = Role::create(['name' => $assignedRole]);
        }

        $user->update([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'mobileNumber' => $request->input('mobileNumber'),
            'alternateNumber' => $request->input('alternateNumber'),
            'branch' => $request->input('branch'),
            'roles' => [$role->_id],
            'departments' => [$department->_id],
        ]);

        return redirect()->route('user.emp.emp')->with('success', 'User updated successfully!');
    }
    
    /**
     * Update user password
     */

    //to update password of an employee/user
    //1. validate the fields
    //2. check if user exists
    //3. check if current password matches
    //4. update password and redirect successfully
   /**
     * Update user password with proper validation
     */
    public function updatePassword(Request $request, $id)
    {
        Log::info('Password update started', ['user_id' => $id]);
        
        // Find the user
        $user = User::find($id);

        if (!$user) {
            Log::error('User not found', ['user_id' => $id]);
            return redirect()->route('user.emp.emp')
                ->with('error', 'User not found!');
        }

        Log::info('User found', ['user_id' => $id, 'user_name' => $user->name]);

        // Validate the request
        $validated = $request->validate([
            'current_password' => 'required|string',
            'new_password' => [
                'required',
                'string',
                'min:8',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
                'different:current_password',
            ],
            'confirm_new_password' => 'required|string|same:new_password',
        ], [
            'current_password.required' => 'Current password is required.',
            'new_password.required' => 'New password is required.',
            'new_password.min' => 'New password must be at least 8 characters long.',
            'new_password.regex' => 'New password must contain at least one uppercase letter, one lowercase letter, and one number.',
            'new_password.different' => 'New password must be different from current password.',
            'confirm_new_password.required' => 'Password confirmation is required.',
            'confirm_new_password.same' => 'Password confirmation does not match.',
        ]);

        Log::info('Validation passed');

        // Check if current password is correct
        if (!Hash::check($request->current_password, $user->password)) {
            Log::warning('Current password incorrect', ['user_id' => $id]);
            return back()
                ->withErrors(['current_password' => 'Current password is incorrect.'])
                ->with('error', 'Current password is incorrect.')
                ->withInput();
        }

        Log::info('Current password verified');

        try {
            // Update the password
            $user->password = Hash::make($request->new_password);
            $user->save();

            Log::info('Password updated successfully', ['user_id' => $id]);

            // Verify the update
            $updatedUser = User::find($id);
            if ($updatedUser && Hash::check($request->new_password, $updatedUser->password)) {
                Log::info('Password update verified in database');
                return redirect()->route('user.emp.emp')
                    ->with('success', 'Password updated successfully for ' . $user->name . '!');
            } else {
                Log::error('Password update verification failed');
                throw new \Exception('Password update could not be verified');
            }

        } catch (\Exception $e) {
            Log::error('Error updating password', [
                'user_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()
                ->with('error', 'Failed to update password: ' . $e->getMessage())
                ->withInput();
        }
    }



    /**
     * Toggle user status
     */

    //to toggle status of an employee/user between Active and Deactivated
    //1. find user by id
    //2. if user not found, redirect with error
    //3. determine new status based on current status
    //4. update user status and redirect successfully
     public function toggleStatus($id)
    {
        $user = User::find($id);

        if (!$user) {
            return redirect()->route('user.emp.emp')->with('error', 'User not found!');
        }

        $newStatus = ($user->status ?? 'Active') === 'Active' ? 'Deactivated' : 'Active';

        $user->update(['status' => $newStatus]);

        return redirect()->route('user.emp.emp')->with('success', 'User status changed to ' . $newStatus . '!');
    }

    /**
     * Debug method to check user data structure
     */
    public function debugUser($id = null)
    {
        if ($id) {
            $user = User::find($id);
            dd($user);
        } else {
            $users = User::take(1)->get();
            dd($users->first());
        }
    }

    /**
     * Debug method to check user data structure
     */

    //a debug function to check user data structure
    //if id is provided, fetch that user, else fetch the first user
    // public function debugUser($id = null)
    // {
    //     if ($id) {
    //         $user = User::find($id);
    //         dd($user);
    //     } else {
    //         $users = User::take(1)->get();
    //         dd($users->first());
    //     }
    // }
    /**
 * Export current employees to Excel
 */
public function exportToExcel(Request $request)
{
    try {
        $search = $request->input('search', '');

        // Build query with same filters as index page
        $query = User::query();

        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%')
                  ->orWhere('mobileNumber', 'like', '%' . $search . '%');
            });
        }

        // Get all users (not paginated for export)
        $users = $query->get();

        // Collect all department and role IDs
        $allRoleIds = collect();
        $allDepartmentIds = collect();

        foreach ($users as $user) {
            $userDepts = data_get($user, 'departments', []);
            $userRoles = data_get($user, 'roles', []);

            if (is_array($userDepts)) {
                $allDepartmentIds = $allDepartmentIds->merge($userDepts);
            }
            
            if (is_array($userRoles)) {
                $allRoleIds = $allRoleIds->merge($userRoles);
            }
        }

        $allRoleIds = $allRoleIds->map(fn($id) => (string) (is_object($id) ? $id : $id))->unique()->filter();
        $allDepartmentIds = $allDepartmentIds->map(fn($id) => (string) (is_object($id) ? $id : $id))->unique()->filter();

        // Fetch departments and roles
        $departments = Department::whereIn('_id', $allDepartmentIds->toArray())
            ->get()
            ->keyBy(fn($dept) => (string) $dept->_id);

        $roles = Role::whereIn('_id', $allRoleIds->toArray())
            ->get()
            ->keyBy(fn($role) => (string) $role->_id);

        // Prepare CSV data
        $csvData = [];
        
        // Add headers
        $csvData[] = [
            'Serial No.',
            'Name',
            'Email',
            'Mobile No.',
            'Alternate Mobile',
            'Branch',
            'Department',
            'Role',
            'Status',
            'Created At',
            'Updated At'
        ];

        // Add data rows
        foreach ($users as $index => $user) {
            $userDepts = data_get($user, 'departments', []);
            $userRoles = data_get($user, 'roles', []);

            $departmentNames = collect(is_array($userDepts) ? $userDepts : [])
                ->map(fn($id) => $departments->get((string) (is_object($id) ? $id : $id))?->name)
                ->filter()
                ->implode(', ');

            $roleNames = collect(is_array($userRoles) ? $userRoles : [])
                ->map(fn($id) => $roles->get((string) (is_object($id) ? $id : $id))?->name)
                ->filter()
                ->implode(', ');

            $csvData[] = [
                $index + 1,
                $user->name ?? '',
                $user->email ?? '',
                $user->mobileNumber ?? '—',
                $user->alternateNumber ?? '—',
                $user->branch ?? '—',
                $departmentNames ?: '—',
                $roleNames ?: '—',
                $user->status ?? 'Active',
                $user->created_at ? $user->created_at->format('d-m-Y H:i:s') : '—',
                $user->updated_at ? $user->updated_at->format('d-m-Y H:i:s') : '—',
            ];
        }

        // Generate filename with timestamp
        $timestamp = now()->format('Y-m-d_His');
        $filename = "employees_export_{$timestamp}.csv";

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
        Log::error('Error exporting employees to Excel:', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        
        return redirect()->back()
            ->with('error', 'Failed to export data: ' . $e->getMessage());
    }
}
/**
 * Download sample Excel file for bulk import
 */
public function downloadSample()
{
    try {
        // Define sample data
        $sampleData = [
            ['Name', 'Mobile Number', 'Alternate Mobile', 'Email', 'Branch', 'Department', 'Password'],
            ['John Doe', '9876543210', '9123456789', 'john.doe@example.com', 'Bikaner', 'Front Office', 'Password@123'],
            ['Jane Smith', '9876543211', '9123456788', 'jane.smith@example.com', 'Bikaner', 'Back Office', 'Password@123'],
            ['Mike Johnson', '9876543212', '9123456787', 'mike.johnson@example.com', 'Bikaner', 'Admin', 'Password@123'],
        ];

        // Create CSV content
        $filename = 'sample_users_import.csv';
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
 * Import users from Excel/CSV file
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
            // For Excel files, use a simple reader or require Laravel Excel
            return redirect()->back()->with('error', 'Please use CSV format or install Laravel Excel package for .xlsx files.');
        }
        
        // Skip header row
        $header = array_shift($data);
        
        // Department to Role mapping
        $departmentRoleMapping = [
            'Front Office' => 'Finance',
            'Back Office' => 'Administration',
            'Office' => 'Attendance',
            'Test Management' => 'Floor Incharge',
            'Admin' => 'Records'
        ];
        
        $imported = 0;
        $skipped = 0;
        $errors = [];

        foreach ($data as $rowIndex => $row) {
            $rowNumber = $rowIndex + 2; // +2 because we skipped header and arrays start at 0
            
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
            
            $name = trim($row[0] ?? '');
            $mobileNumber = trim($row[1] ?? '');
            $alternateNumber = trim($row[2] ?? '');
            $email = trim($row[3] ?? '');
            $branch = trim($row[4] ?? '');
            $department = trim($row[5] ?? '');
            $password = trim($row[6] ?? '');
            
            // Validate required fields
            if (empty($name) || empty($email) || empty($mobileNumber) || empty($department) || empty($password)) {
                $errors[] = "Row {$rowNumber}: Missing required fields (Name, Email, Mobile, Department, or Password)";
                $skipped++;
                continue;
            }
            
            // Validate email format
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Row {$rowNumber}: Invalid email format ({$email})";
                $skipped++;
                continue;
            }
            
            // Check if email already exists
            if (User::where('email', $email)->exists()) {
                $errors[] = "Row {$rowNumber}: Email already exists ({$email})";
                $skipped++;
                continue;
            }
            
            // Validate mobile number (10 digits)
            if (!preg_match('/^[0-9]{10}$/', $mobileNumber)) {
                $errors[] = "Row {$rowNumber}: Invalid mobile number format ({$mobileNumber})";
                $skipped++;
                continue;
            }
            
            // Validate alternate mobile if provided
            if (!empty($alternateNumber) && !preg_match('/^[0-9]{10}$/', $alternateNumber)) {
                $errors[] = "Row {$rowNumber}: Invalid alternate mobile number format ({$alternateNumber})";
                $skipped++;
                continue;
            }
            
            try {
                // Get assigned role based on department
                $assignedRole = $departmentRoleMapping[$department] ?? 'Administration';
                
                // Find or create department
                $dept = Department::firstOrCreate(['name' => $department]);
                
                // Find or create role
                $role = Role::firstOrCreate(['name' => $assignedRole]);
                
                // Create user
                User::create([
                    'name' => $name,
                    'email' => $email,
                    'mobileNumber' => $mobileNumber,
                    'alternateNumber' => $alternateNumber ?: null,
                    'branch' => $branch ?: 'Bikaner',
                    'departments' => [$dept->_id],
                    'roles' => [$role->_id],
                    'password' => Hash::make($password),
                    'status' => 'Active',
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
        $message = "Import completed: {$imported} users imported successfully";
        if ($skipped > 0) {
            $message .= ", {$skipped} rows skipped";
        }
        
        // If there are errors, add them to session
        if (!empty($errors)) {
            session()->flash('import_errors', $errors);
        }
        
        return redirect()->route('user.emp.emp')
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