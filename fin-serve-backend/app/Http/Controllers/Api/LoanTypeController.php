<?php

namespace App\Http\Controllers\Api;


use App\Models\LoanType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LoanTypeController extends Controller
{
    public function index()
    {
        return response()->json(LoanType::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'description' => 'nullable',
            'interest_rate' => 'required|numeric',
            'max_amount' => 'nullable|numeric'
        ]);

        $type = LoanType::create($validated);

        return response()->json($type, 201);
    }

    public function show($id)
    {
        return response()->json(LoanType::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $type = LoanType::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required',
            'interest_rate' => 'numeric',
            'max_amount' => 'numeric|nullable'
        ]);

        $type->update($validated);

        return response()->json($type);
    }

    public function destroy($id)
    {
        LoanType::findOrFail($id)->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
