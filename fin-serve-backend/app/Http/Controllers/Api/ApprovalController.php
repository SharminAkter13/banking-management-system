<?php

namespace App\Http\Controllers\Api;


use App\Models\Approval;
use Illuminate\Http\Request;

class ApprovalController extends Controller
{
    public function index()
    {
        return response()->json(
            Approval::with(['steps', 'creator'])->get()
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'entity_type' => 'required',
            'entity_id' => 'required|numeric',
            'created_by' => 'required|exists:users,id'
        ]);

        $approval = Approval::create($validated);

        return response()->json($approval, 201);
    }

    public function show($id)
    {
        return response()->json(
            Approval::with(['steps.actions', 'creator'])
                ->findOrFail($id)
        );
    }

    public function update(Request $request, $id)
    {
        $approval = Approval::findOrFail($id);

        $validated = $request->validate([
            'status' => 'in:Pending,In Progress,Approved,Rejected',
            'current_step' => 'numeric'
        ]);

        $approval->update($validated);

        return response()->json($approval);
    }
}
