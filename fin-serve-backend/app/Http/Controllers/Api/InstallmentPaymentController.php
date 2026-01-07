<?php

namespace App\Http\Controllers\Api;

use App\Models\Installment;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class InstallmentPaymentController extends Controller
{
    public function pay(Request $request)
    {
        $request->validate([
            'installment_id' => 'required|exists:installments,id',
            'amount' => 'required|numeric|min:1'
        ]);

        return DB::transaction(function () use ($request) {

            $inst = Installment::lockForUpdate()->findOrFail($request->installment_id);

            $newPaid = ($inst->amount_paid ?? 0) + $request->amount;

            if ($newPaid > $inst->amount_due) {
                return response()->json(['message' => 'Overpayment not allowed'], 422);
            }

            // create transaction (cash / bank)
            Transaction::create([
                'account_id' => $inst->loan->customer->account_id ?? 1,
                'transaction_type_id' => 2, // credit
                'transaction_status_id' => 1, // success
                'amount' => $request->amount,
                'transaction_date' => now(),
                'created_by' => auth()->id() ?? 1
            ]);

            $inst->amount_paid = $newPaid;

            if ($newPaid == $inst->amount_due) {
                $inst->status = 'Paid';
            }

            $inst->save();

            return response()->json(['message' => 'Payment Successful']);
        });
    }
}
