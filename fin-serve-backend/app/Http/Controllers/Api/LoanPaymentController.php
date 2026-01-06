<?php

namespace App\Http\Controllers\Api;


use App\Models\LoanPayment;
use Illuminate\Http\Request;

class LoanPaymentController extends Controller
{
    public function index()
    {
        return response()->json(
            LoanPayment::with(['loan', 'transaction'])->get()
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'loan_id' => 'required|exists:loans,id',
            'transaction_id' => 'required|exists:transactions,id',
            'amount' => 'required|numeric',
            'payment_date' => 'required|date'
        ]);

        $payment = LoanPayment::create($validated);

        return response()->json($payment, 201);
    }

    public function show($id)
    {
        return response()->json(
            LoanPayment::with(['loan', 'transaction'])->findOrFail($id)
        );
    }

    public function destroy($id)
    {
        LoanPayment::findOrFail($id)->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
