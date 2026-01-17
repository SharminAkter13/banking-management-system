<?php

namespace App\Http\Controllers\Api;

use App\Models\AccountStatus;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AccountStatusController extends Controller
{
    // =========================
    // GET ALL STATUSES
    // =========================
    public function index()
    {
        return response()->json(AccountStatus::all());
    }

    // =========================
    // CREATE STATUS
    // =========================
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50|unique:account_statuses,name'
        ]);

        $status = AccountStatus::create($validated);

        return response()->json($status, 201);
    }

    // =========================
    // SHOW SINGLE STATUS
    // =========================
    public function show($id)
    {
        return response()->json(
            AccountStatus::with('accounts')->findOrFail($id)
        );
    }

    // =========================
    // UPDATE STATUS
    // =========================
    public function update(Request $request, $id)
    {
        $status = AccountStatus::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:50|unique:account_statuses,name,' . $id
        ]);

        $status->update($validated);

        return response()->json($status);
    }

    // =========================
    // DELETE STATUS
    // =========================
    public function destroy($id)
    {
        AccountStatus::findOrFail($id)->delete();

        return response()->json(['message' => 'Account Status deleted successfully']);
    }
}
