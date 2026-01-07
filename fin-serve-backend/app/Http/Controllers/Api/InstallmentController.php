<?php

namespace App\Http\Controllers\Api;

use App\Models\Installment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class InstallmentController extends Controller
{
    public function index()
    {
        return response()->json(
            Installment::with('loan')->get()
        );
    }

public function store(Request $request) {
    $validated = $request->validate([
        'loan_id' => 'required|exists:loans,id',
        'due_date' => 'required|date',
        'amount_due' => 'required|numeric',
        'status' => 'in:Pending,Paid,Overdue'
    ]);
    return response()->json(Installment::create($validated), 201);
}
    public function show($id)
    {
        return response()->json(
            Installment::with('loan')->findOrFail($id)
        );
    }

    public function update(Request $request, $id)
    {
        $installment = Installment::findOrFail($id);

        $validated = $request->validate([
            'amount_paid' => 'nullable|numeric',
            'status' => 'in:Pending,Paid,Overdue'
        ]);

        $installment->update($validated);

        return response()->json($installment);
    }

    public function destroy($id)
    {
        Installment::findOrFail($id)->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
