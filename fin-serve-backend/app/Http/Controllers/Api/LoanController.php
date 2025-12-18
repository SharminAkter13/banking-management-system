<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use Illuminate\Http\Request;

class LoanController extends Controller
{
    // ===============================
    // Get all loans
    // ===============================
    public function index()
    {
        $loans = Loan::with(['customer', 'branch', 'loanType'])->get();
        return response()->json($loans);
    }

    // ===============================
    // Get a single loan
    // ===============================
    public function show($id)
    {
        $loan = Loan::with(['customer', 'branch', 'loanType'])->findOrFail($id);
        return response()->json($loan);
    }

    // ===============================
    // Create a new loan
    // ===============================
    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'branch_id' => 'required|exists:branches,id',
            'loan_type_id' => 'required|exists:loan_types,id',
            'principal_amount' => 'required|numeric',
            'issued_date' => 'required|date',
            'duration_months' => 'required|numeric',
            'status' => 'required|string',
        ]);

        $loan = Loan::create([
            'customer_id' => $request->customer_id,
            'branch_id' => $request->branch_id,
            'loan_type_id' => $request->loan_type_id,
            'principal_amount' => $request->principal_amount,
            'issued_date' => $request->issued_date,
            'duration_months' => $request->duration_months,
            'status' => $request->status,
        ]);

        return response()->json($loan, 201);
    }

    // ===============================
    // Update a loan
    // ===============================
    public function update(Request $request, $id)
    {
        $loan = Loan::findOrFail($id);

        $request->validate([
            'customer_id' => 'sometimes|required|exists:customers,id',
            'branch_id' => 'sometimes|required|exists:branches,id',
            'loan_type_id' => 'sometimes|required|exists:loan_types,id',
            'principal_amount' => 'sometimes|required|numeric',
            'issued_date' => 'sometimes|required|date',
            'duration_months' => 'sometimes|required|numeric',
            'status' => 'sometimes|required|string',
        ]);

        $loan->update($request->all());

        return response()->json($loan);
    }

    // ===============================
    // Delete a loan
    // ===============================
    public function destroy($id)
    {
        $loan = Loan::findOrFail($id);
        $loan->delete();

        return response()->json(['message' => 'Loan deleted successfully.']);
    }
}
