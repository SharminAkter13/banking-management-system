<?php

namespace App\Http\Controllers\Api;

use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index()
    {
        return response()->json(
            Transaction::with(['account', 'type', 'status', 'creator'])->get()
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'account_id' => 'required|exists:accounts,account_id',
            'transaction_type_id' => 'required|exists:transaction_types,id',
            'transaction_status_id' => 'required|exists:transaction_status,id',
            'amount' => 'required|numeric',
            'transaction_date' => 'required|date',
            'created_by' => 'required|exists:users,id',
            'notes' => 'nullable'
        ]);

        $transaction = Transaction::create($validated);

        return response()->json($transaction, 201);
    }

    public function show($id)
    {
        return response()->json(
            Transaction::with(['account', 'type', 'status', 'creator'])
                ->findOrFail($id)
        );
    }

    public function update(Request $request, $id)
    {
        $transaction = Transaction::findOrFail($id);

        $validated = $request->validate([
            'transaction_status_id' => 'exists:transaction_status,id',
            'notes' => 'nullable'
        ]);

        $transaction->update($validated);

        return response()->json($transaction);
    }

    public function destroy($id)
    {
        Transaction::findOrFail($id)->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
________________________________________
