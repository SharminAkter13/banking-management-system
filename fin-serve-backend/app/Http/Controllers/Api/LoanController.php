<?php

namespace App\Http\Controllers\Api;

use App\Models\Loan;
use App\Models\Installment;
use App\Models\Approval;
use App\Models\ApprovalStep;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB; // Added missing import
use Carbon\Carbon;

class LoanController extends Controller
{
    public function index()
    {
        return response()->json(
            Loan::with(['customer', 'branch', 'loanType', 'installments', 'approval.steps.role'])
                ->latest()
                ->get()
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id'      => 'required|exists:customers,id',
            'branch_id'        => 'required|exists:branches,id',
            'loan_type_id'     => 'required|exists:loan_types,id',
            'principal_amount' => 'required|numeric|min:1',
            'interest_rate'    => 'required|numeric|min:0',
            'duration_months'  => 'required|integer|min:1',
            'issued_date'      => 'nullable|date',
            'status'           => 'required|string',
        ]);

        DB::beginTransaction();

        try {
            // EMI CALCULATION (Simple Interest Approach)
            $principal = $request->principal_amount;
            $rate = $request->interest_rate / 100;
            $months = $request->duration_months;

            $totalPayable = $principal + ($principal * $rate);
            $emiAmount = round($totalPayable / $months, 2);
            $startDate = $request->issued_date ? Carbon::parse($request->issued_date) : Carbon::now();

            $loan = Loan::create([
                'customer_id'       => $request->customer_id,
                'branch_id'         => $request->branch_id,
                'loan_type_id'      => $request->loan_type_id,
                'principal_amount'  => $principal,
                'interest_rate'     => $request->interest_rate,
                'emi_amount'        => $emiAmount,
                'total_payable'     => $totalPayable,
                'remaining_balance' => $totalPayable,
                'issued_date'       => $startDate->toDateString(),
                'duration_months'   => $months,
                'status'            => $request->status,
            ]);

            // CREATE APPROVAL FLOW
            $approval = Approval::create([
                'entity_type'  => 'loan',
                'entity_id'    => $loan->id,
                'current_step' => 1,
                'status'       => 'Pending',
                'created_by'   => auth()->id() ?? 1,
            ]);

            $roles = [2, 1]; // Branch Manager, Admin
            foreach ($roles as $index => $roleId) {
                ApprovalStep::create([
                    'approval_id' => $approval->id,
                    'step_number' => $index + 1,
                    'role_id'     => $roleId,
                    'status'      => 'Pending',
                    'required'    => true,
                ]);
            }

            // CREATE INSTALLMENTS
            for ($i = 1; $i <= $months; $i++) {
                Installment::create([
                    'loan_id'     => $loan->id,
                    'due_date'    => (clone $startDate)->addMonths($i)->toDateString(),
                    'amount_due'  => $emiAmount,
                    'emi_amount'  => $emiAmount,
                    'amount_paid' => 0,
                    'status'      => 'Pending',
                ]);
            }

            DB::commit();

            return response()->json([
                'message' => 'Loan created successfully',
                'loan'    => $loan->load('installments')
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Loan creation failed',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        return response()->json(
            Loan::with(['customer', 'branch', 'loanType', 'installments'])->findOrFail($id)
        );
    }

    public function update(Request $request, $id)
    {
        $loan = Loan::findOrFail($id);
        $validated = $request->validate([
            'status' => 'required|in:Pending,Active,Closed,Rejected,Defaulted'
        ]);

        $loan->update($validated);
        return response()->json($loan);
    }

    public function destroy($id)
    {
        $loan = Loan::findOrFail($id);
        // Optional: Only allow deletion if loan is still 'Pending'
        $loan->delete();
        return response()->json(['message' => 'Deleted']);
    }
}