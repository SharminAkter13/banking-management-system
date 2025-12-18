<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    // Get all branches
    public function index()
    {
        $branches = Branch::all();
        return response()->json($branches);
    }

    // Get a single branch by ID
    public function show($id)
    {
        $branch = Branch::findOrFail($id);
        return response()->json($branch);
    }

    // Create a new branch
    public function store(Request $request)
    {
        $request->validate([
            'branch_name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'contact' => 'required|string|max:15',
        ]);

        $branch = Branch::create([
            'name' => $request->name,
            'location' => $request->location,
            'contact' => $request->contact,
        ]);

        return response()->json($branch, 201);
    }

    // Update an existing branch
    public function update(Request $request, $id)
    {
        $branch = Branch::findOrFail($id);

        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'location' => 'sometimes|required|string|max:255',
            'contact' => 'sometimes|required|string|max:15',
        ]);

        $branch->name = $request->name ?? $branch->name;
        $branch->location = $request->location ?? $branch->location;
        $branch->contact = $request->contact ?? $branch->contact;
        $branch->save();

        return response()->json($branch);
    }

    // Delete a branch
    public function destroy($id)
    {
        $branch = Branch::findOrFail($id);
        $branch->delete();

        return response()->json(['message' => 'Branch deleted successfully.']);
    }
}
