<?php

namespace App\Http\Controllers\Api;


use App\Models\Loan;
use Illuminate\Http\Request;

class LoanController extends Controller
{
    public function index()
    {
        return response()->json(
            Loan::with(['customer', 'branch', 'loanType'])->get()
        );
    }

public function store(Request $request) {
    $validated = $request->validate([
        'customer_id' => 'required|exists:customers,id',
        'branch_id' => 'required|exists:branches,id',
        'loan_type_id' => 'required|exists:loan_types,id',
        'principal_amount' => 'required|numeric',
        'duration_months' => 'required|integer',
        'status' => 'in:Pending,Active,Closed,Rejected,Defaulted'
    ]);
    return response()->json(Loan::create($validated), 201);
}
    public function show($id)
    {
        return response()->json(
            Loan::with(['installments', 'loanPayments', 'loanType'])->findOrFail($id)
        );
    }

    public function update(Request $request, $id)
    {
        $loan = Loan::findOrFail($id);

        $validated = $request->validate([
            'status' => 'in:Pending,Active,Closed,Rejected,Defaulted',
            'issued_date' => 'nullable|date'
        ]);

        $loan->update($validated);

        return response()->json($loan);
    }

    public function destroy($id)
    {
        Loan::findOrFail($id)->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
