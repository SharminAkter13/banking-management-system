<?php

namespace App\Http\Controllers\Api;

use App\Models\Loan;
use App\Models\Installment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

class LoanController extends Controller
{
    // =========================
    // LIST ALL LOANS
    // =========================
    public function index()
    {
        return response()->json(
            Loan::with(['customer','branch','loanType'])->latest()->get()
        );
    }

    // =========================
    // CREATE LOAN + EMI SCHEDULE
    // =========================
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'branch_id' => 'required|exists:branches,id',
            'loan_type_id' => 'required|exists:loan_types,id',
            'principal_amount' => 'required|numeric|min:1',
            'interest_rate' => 'required|numeric|min:0',
            'duration_months' => 'required|integer|min:1'
        ]);

        $P = $validated['principal_amount'];
        $annualRate = $validated['interest_rate'];
        $n = $validated['duration_months'];

        $r = ($annualRate / 12) / 100;

        // EMI Formula
        if ($r > 0) {
            $emi = $P * $r * pow(1 + $r, $n) / (pow(1 + $r, $n) - 1);
        } else {
            $emi = $P / $n;
        }

        $emi = round($emi, 2);
        $totalPayable = round($emi * $n, 2);

        $loan = Loan::create([
            'customer_id' => $validated['customer_id'],
            'branch_id' => $validated['branch_id'],
            'loan_type_id' => $validated['loan_type_id'],
            'principal_amount' => $P,
            'interest_rate' => $annualRate,
            'emi_amount' => $emi,
            'total_payable' => $totalPayable,
            'remaining_balance' => $totalPayable,
            'duration_months' => $n,
            'issued_date' => now(),
            'status' => 'Active'
        ]);

        // =========================
        // CREATE INSTALLMENT SCHEDULE
        // =========================
        $startDate = Carbon::now();

        for ($i = 1; $i <= $n; $i++) {
            Installment::create([
                'loan_id' => $loan->id,
                'due_date' => $startDate->copy()->addMonths($i),
                'amount_due' => $emi,
                'status' => 'Pending'
            ]);
        }

        return response()->json($loan, 201);
    }

    // =========================
    // LOAN DETAILS + INSTALLMENTS
    // =========================
    public function show($id)
    {
        return response()->json(
            Loan::with(['customer','installments'])->findOrFail($id)
        );
    }

    // =========================
    // UPDATE STATUS ONLY
    // =========================
    public function update(Request $request, $id)
    {
        $loan = Loan::findOrFail($id);

        $validated = $request->validate([
            'status' => 'in:Pending,Active,Closed,Rejected,Defaulted'
        ]);

        $loan->update($validated);

        return response()->json($loan);
    }

    // =========================
    // DELETE LOAN
    // =========================
    public function destroy($id)
    {
        Loan::findOrFail($id)->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
