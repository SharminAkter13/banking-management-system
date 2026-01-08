<?php

namespace App\Http\Controllers\Api;

use App\Models\Loan;
use App\Models\Installment;
use App\Models\Approval;
use App\Models\ApprovalStep;
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
Loan::with(['customer','branch','loanType','installments','approval.steps.role'])->latest()->get()
        );
    }

    // =========================
    // CREATE LOAN + EMI SCHEDULE + APPROVAL
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

        // =========================
        // EMI Calculation
        // =========================
        $P = $validated['principal_amount'];
        $annualRate = $validated['interest_rate'];
        $n = $validated['duration_months'];
        $r = ($annualRate / 12) / 100;

        $emi = $r > 0 ? $P * $r * pow(1 + $r, $n) / (pow(1 + $r, $n) - 1) : $P / $n;
        $emi = round($emi, 2);
        $totalPayable = round($emi * $n, 2);

        // =========================
        // Create Loan
        // =========================
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
            'status' => 'Pending'
        ]);

        // =========================
        // Create Installments
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

        // =========================
        // Optional: Create Approval Flow
        // =========================
        $approval = Approval::create([
            'entity_type' => 'loan',
            'entity_id' => $loan->id,
            'created_by' => auth()->id() ?? 1, // fallback admin
            'status' => 'Pending'
        ]);

        $roles = ['loan-officer','branch-manager','admin'];
        $stepNumber = 1;
        foreach ($roles as $roleSlug) {
            $role = \App\Models\Role::where('slug', $roleSlug)->first();
            if ($role) {
                ApprovalStep::create([
                    'approval_id' => $approval->id,
                    'step_number' => $stepNumber++,
                    'role_id' => $role->id,
                    'status' => 'Pending',
                    'required' => true
                ]);
            }
        }

        return response()->json($loan, 201);
    }

    // =========================
    // SHOW LOAN DETAILS + INSTALLMENTS
    // =========================
    public function show($id)
    {
        return response()->json(
            Loan::with(['customer','branch','loanType','installments'])->findOrFail($id)
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
