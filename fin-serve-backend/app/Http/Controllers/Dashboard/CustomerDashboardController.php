<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CustomerDashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $customer = $request->user()->customer;

        return response()->json([
            'accounts' => $customer->accounts()
                ->with('type', 'status')
                ->get()
                ->map(fn ($a) => [
                    'id'      => $a->id,
                    'type'    => $a->type->account_name,
                    'status'  => $a->status->name,
                    'balance' => $a->balance,
                ]),

            'loans' => $customer->loans()
                ->withCount('installments')
                ->get()
                ->map(fn ($l) => [
                    'id'       => $l->id,
                    'amount'   => $l->principal_amount,
                    'status'   => $l->status,
                    'duration' => $l->duration_months,
                ]),

            'recent_transactions' => $customer->accounts()
                ->with(['transactions' => fn ($q) => $q->latest()->limit(5)])
                ->get()
                ->pluck('transactions')
                ->flatten()
                ->map(fn ($t) => [
                    'amount' => $t->amount,
                    'date'   => $t->transaction_date,
                ]),

            'kyc_status' => $customer->kyc?->status ?? 'Not Submitted'
        ]);
    }
}
