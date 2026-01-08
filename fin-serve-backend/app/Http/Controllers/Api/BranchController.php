<?php

namespace App\Http\Controllers\Api;

use App\Models\Branch;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class BranchController extends Controller
{
    public function index()
    {
        return response()->json(Branch::with('manager')->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'branch_name' => 'required',
            'location' => 'required',
            'manager_id' => 'nullable|exists:users,id',
            'contact' => 'nullable'
        ]);

        $branch = Branch::create($validated);

        return response()->json($branch, 201);
    }

    public function show($id)
    {
        return response()->json(Branch::with('manager')->findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $branch = Branch::findOrFail($id);

        $validated = $request->validate([
            'branch_name' => 'required',
            'location' => 'required',
            'manager_id' => 'nullable|exists:users,id',
            'contact' => 'nullable'
        ]);

        $branch->update($validated);

        return response()->json($branch);
    }

    public function destroy($id)
    {
        Branch::findOrFail($id)->delete();
        return response()->json(['message' => 'Deleted']);
    }
}

