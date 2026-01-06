<?php

namespace App\Http\Controllers\Api;


use App\Models\ApprovalStep;
use Illuminate\Http\Request;

class ApprovalStepController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'approval_id' => 'required|exists:approvals,id',
            'step_number' => 'required|numeric',
            'role_id' => 'required|exists:roles,id',
            'status' => 'in:Pending,Approved,Rejected',
            'required' => 'boolean',
            'approved_at' => 'nullable|date'
        ]);

        $step = ApprovalStep::create($validated);

        return response()->json($step, 201);
    }

    public function update(Request $request, $id)
    {
        $step = ApprovalStep::findOrFail($id);

        $validated = $request->validate([
            'status' => 'in:Pending,Approved,Rejected',
            'approved_at' => 'nullable|date'
        ]);

        $step->update($validated);

        return response()->json($step);
    }
}
