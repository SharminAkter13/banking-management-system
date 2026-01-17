<?php

namespace App\Http\Controllers\Api;

use App\Models\AccountTypes;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AccountTypeController extends Controller
{
    // =========================
    // GET ALL ACCOUNT TYPES
    // =========================
    public function index()
    {
        return response()->json(AccountTypes::all());
    }

    // =========================
    // CREATE ACCOUNT TYPE
    // =========================
    public function store(Request $request)
    {
        $validated = $request->validate([
            'account_name'   => 'required|string|max:100|unique:account_types,account_name',
            'description' => 'nullable|string'
        ]);

        $type = AccountType::create($validated);

        return response()->json($type, 201);
    }

    // =========================
    // SHOW SINGLE ACCOUNT TYPE
    // =========================
    public function show($id)
    {
        return response()->json(
            AccountTypes::with('accounts')->findOrFail($id)
        );
    }

    // =========================
    // UPDATE ACCOUNT TYPE
    // =========================
    public function update(Request $request, $id)
    {
        $type = AccountTypes::findOrFail($id);

        $validated = $request->validate([
            'account_name'   => 'required|string|max:100|unique:account_types,account_name,' . $id,
            'description' => 'nullable|string'
        ]);

        $type->update($validated);

        return response()->json($type);
    }

    // =========================
    // DELETE ACCOUNT TYPE
    // =========================
    public function destroy($id)
    {
        AccountTypes::findOrFail($id)->delete();

        return response()->json(['message' => 'Account Type deleted successfully']);
    }
}
