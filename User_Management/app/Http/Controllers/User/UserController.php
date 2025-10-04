<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User\User;
use Illuminate\Http\Request;
use App\Models\User\Role;
use App\Models\User\Department;
use Illuminate\Support\Facades\Hash;

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

    public function index()
    {
        return $this->showUser();
    }

    // public function addUser(Request $request)
    // {
    //     $request->validate([
    //         'name' => 'required|string|max:255',
    //         'email' => 'required|email|unique:users,email',
    //         'mobileNumber' => 'required|string|max:15',
    //         'alternateNumber' => 'nullable|string|max:15',
    //         'branch' => 'required|string',
    //         'department' => 'required|string',
    //         'password' => 'required|min:6',
    //         'confirm_password' => 'required|same:password',
    //     ]);

    //     // Auto-assign roles based on department
    //     $departmentRoleMapping = [
    //         'Front Office' => 'Administration',
    //         'Back Office' => 'Administration', 
    //         'Office' => 'Administration',
    //         'Test Management' => 'Administration',
    //         'Admin' => 'Admin'
    //     ];

    //     $selectedDepartment = $request->input('department');
    //     $assignedRole = $departmentRoleMapping[$selectedDepartment] ?? 'Administration';

    //     // Find or create department
    //     $department = Department::where('name', $selectedDepartment)->first();
    //     if (!$department) {
    //         $department = Department::create(['name' => $selectedDepartment]);
    //     }

    //     // Find or create role
    //     $role = Role::where('name', $assignedRole)->first();
    //     if (!$role) {
    //         $role = Role::create(['name' => $assignedRole]);
    //     }

    //     User::create([
    //         'name' => $request->input('name'),
    //         'email' => $request->input('email'),
    //         'mobileNumber' => $request->input('mobileNumber'),
    //         'alternateNumber' => $request->input('alternateNumber'),
    //         'branch' => $request->input('branch'),
    //         'roles' => [$role->_id],
    //         'departments' => [$department->_id],
    //         'password' => Hash::make($request->input('password')),
    //         'status' => 'Active',
    //     ]);

    //     return redirect()->route('emp')->with('success', 'Employee added successfully!');
    // }

    /**
     * Show employees
     */
     public function addUser(Request $request)
    {
        // Debugging: uncomment if needed
        // dd($request->all());

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'mobileNumber' => 'required|string|max:15',
            'alternateNumber' => 'nullable|string|max:15',
            'branch' => 'required|string',
            'roles' => 'nullable|array',
            'roles.*' => 'string',
            'departments' => 'nullable|array',
            'departments.*' => 'string',
            'password' => 'required|min:6',
            'confirm_password' => 'required|same:password',
        ]);

        $departments = [];
        if ($request->has('departments')) {
            foreach ($request->input('departments') as $deptName) {
                $department = Department::where('name', 'like', $deptName)->first();
                if ($department) {
                    $departments[] = $department->_id;
                }
            }
        }

        $roles = [];
        if ($request->has('roles')) {
            foreach ($request->input('roles') as $roleName) {
                $role = Role::where('name', 'like', $roleName)->first();
                if ($role) {
                    $roles[] = $role->_id;
                }
            }
        }

        User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'mobileNumber' => $request->input('mobileNumber'),
            'alternateNumber' => $request->input('alternateNumber'),
            'branch' => $request->input('branch'),
            'roles' => $roles,
            'departments' => $departments,
            'password' => Hash::make($request->input('password')),
            'status' => 'Active',
        ]);

        return redirect()->route('emp')->with('success', 'Employee added successfully!');
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

    public function showUser()
    {
        $users = User::all();

        // Get all unique role and department IDs from all users
        $roleIds = collect();
        $departmentIds = collect();

        foreach ($users as $user) {
            if (isset($user->roles) && is_array($user->roles)) {
                $roleIds = $roleIds->merge($user->roles);
            }
            if (isset($user->departments) && is_array($user->departments)) {
                $departmentIds = $departmentIds->merge($user->departments);
            }
        }

        // Get unique IDs and convert to strings for MongoDB
        $roleIds = $roleIds->unique()->map(fn($id) => (string) $id)->filter();
        $departmentIds = $departmentIds->unique()->map(fn($id) => (string) $id)->filter();

        // Fetch roles and departments
        $roles = collect();
        $departments = collect();

        if ($roleIds->isNotEmpty()) {
            $roles = Role::whereIn('_id', $roleIds->toArray())->get()->keyBy(fn($role) => (string) $role->_id);
        }

        if ($departmentIds->isNotEmpty()) {
            $departments = Department::whereIn('_id', $departmentIds->toArray())->get()->keyBy(fn($dept) => (string) $dept->_id);
        }

        // Attach role and department names to each user
        foreach ($users as $user) {
            // Handle roles
            $user->roleNames = collect();
            if (isset($user->roles) && is_array($user->roles)) {
                $user->roleNames = collect($user->roles)
                    ->map(fn($id) => $roles->get((string) $id)?->name)
                    ->filter()
                    ->values();
            }

            // Handle departments
            $user->departmentNames = collect();
            if (isset($user->departments) && is_array($user->departments)) {
                $user->departmentNames = collect($user->departments)
                    ->map(fn($id) => $departments->get((string) $id)?->name)
                    ->filter()
                    ->values();
            }
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
            return redirect()->route('emp')->with('error', 'User not found!');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id . ',_id',
            'mobileNumber' => 'required|string|max:15',
            'alternateNumber' => 'nullable|string|max:15',
            'branch' => 'required|string',
            'department' => 'required|string',
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

        return redirect()->route('emp')->with('success', 'User updated successfully!');
    }

    /**
     * Update user password
     */

    //to update password of an employee/user
    //1. validate the fields
    //2. check if user exists
    //3. check if current password matches
    //4. update password and redirect successfully
    public function updatePassword(Request $request, $id)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6',
            'confirm_new_password' => 'required|same:new_password',
        ]);

        $user = User::find($id);

        if (!$user) {
            return redirect()->route('emp')->with('error', 'User not found!');
        }

        if (!Hash::check($request->input('current_password'), $user->password)) {
            return back()->withErrors(['current_password' => 'Incorrect current password']);
        }

        $user->update([
            'password' => Hash::make($request->input('new_password')),
        ]);

        return redirect()->route('emp')->with('success', 'Password updated successfully!');
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
            return redirect()->route('emp')->with('error', 'User not found!');
        }

        $newStatus = ($user->status ?? 'Active') === 'Active' ? 'Deactivated' : 'Active';

        $user->update(['status' => $newStatus]);

        return redirect()->route('emp')->with('success', 'User status changed to ' . $newStatus . '!');
    }

    /**
     * Debug method to check user data structure
     */

    //a debug function to check user data structure
    //if id is provided, fetch that user, else fetch the first user
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
}