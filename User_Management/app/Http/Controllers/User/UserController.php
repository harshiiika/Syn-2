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
}