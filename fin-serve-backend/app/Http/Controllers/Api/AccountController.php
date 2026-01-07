<?php

namespace App\Http\Controllers\Api;

use App\Models\Account;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
class AccountController extends Controller
{
    public function index()
    {
        return response()->json(
            Account::with(['customer', 'branch', 'accountType', 'status'])->get()
        );
    }

public function store(Request $request) {
    $validated = $request->validate([
        'customer_id' => 'required|exists:customers,id',
        'branch_id' => 'required|exists:branches,id',
        'account_type_id' => 'required|exists:account_types,id',
        'account_status_id' => 'required|exists:account_statuses,id',
        'balance' => 'numeric',
        'opened_date' => 'required|date'
    ]);
    return response()->json(Account::create($validated), 201);
}
    public function show($id)
    {
        return response()->json(
            Account::with(['transactions', 'customer'])->findOrFail($id)
        );
    }

    public function update(Request $request, $id)
    {
        $account = Account::findOrFail($id);

        $validated = $request->validate([
            'account_status_id' => 'exists:account_status,id',
            'closed_date' => 'nullable|date'
        ]);

        $account->update($validated);

        return response()->json($account);
    }

    public function destroy($id)
    {
        Account::findOrFail($id)->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
