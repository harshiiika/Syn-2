<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\Branch;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    /**
     * Display all branches
     */
    public function index()
    {
        $branches = Branch::all();
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
}