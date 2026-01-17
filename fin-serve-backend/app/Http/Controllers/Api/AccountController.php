<?php

namespace App\Http\Controllers\Api;

use App\Models\Account;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AccountController extends Controller
{
    // List all accounts with relations
    public function index()
    {
        $user = auth()->user();

        // Check user role via role slug (assumes you have role relationship)
        $roleSlug = strtolower($user->role->slug ?? $user->role); // fallback to string role

        if ($roleSlug === 'admin' || $roleSlug === 'loan-officer') {
            // Admin and loan officer can see all accounts
            $accounts = Account::with(['customer', 'branch', 'accountType', 'status'])->get();
        } elseif ($roleSlug === 'customer') {
            // Customers only see their own accounts
            $accounts = Account::with(['customer', 'branch', 'accountType', 'status'])
                ->where('customer_id', $user->id)
                ->get();
        } elseif (in_array($roleSlug, ['branch-manager', 'bank-teller'])) {
            // Branch managers and tellers only see accounts in their branch
            $accounts = Account::with(['customer', 'branch', 'accountType', 'status'])
                ->where('branch_id', $user->branch_id)
                ->get();
        } else {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($accounts);
    }

    // Create account (Admin only)
    public function store(Request $request)
    {
        $user = auth()->user();
        $roleSlug = strtolower($user->role->slug ?? $user->role);

        if ($roleSlug !== 'admin') {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'branch_id' => 'required|exists:branches,id',
            'account_type_id' => 'required|exists:account_types,id',
            'account_status_id' => 'required|exists:account_statuses,id',
            'balance' => 'nullable|numeric',
            'opened_date' => 'required|date',
            'closed_date' => 'nullable|date',
        ]);

        $account = Account::create($validated);

        return response()->json(
            Account::with(['customer', 'branch', 'accountType', 'status'])->find($account->id),
            201
        );
    }

    // Show single account with full relations
    public function show($id)
    {
        $user = auth()->user();
        $roleSlug = strtolower($user->role->slug ?? $user->role);

        $account = Account::with(['transactions', 'customer', 'branch', 'accountType', 'status'])->findOrFail($id);

        // Access control
        if ($roleSlug === 'admin' || $roleSlug === 'loan-officer') {
            // allowed
        } elseif ($roleSlug === 'customer' && $account->customer_id !== $user->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        } elseif (in_array($roleSlug, ['branch-manager', 'bank-teller']) && $account->branch_id !== $user->branch_id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        return response()->json($account);
    }

    // Update account (Admin only)
    public function update(Request $request, $id)
    {
        $user = auth()->user();
        $roleSlug = strtolower($user->role->slug ?? $user->role);

        if ($roleSlug !== 'admin') {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $account = Account::findOrFail($id);

        $validated = $request->validate([
            'account_status_id' => 'exists:account_statuses,id',
            'balance' => 'nullable|numeric',
            'closed_date' => 'nullable|date',
        ]);

        $account->update($validated);

        return response()->json(
            Account::with(['customer', 'branch', 'accountType', 'status'])->find($id)
        );
    }

    // Delete account (Admin only)
    public function destroy($id)
    {
        $user = auth()->user();
        $roleSlug = strtolower($user->role->slug ?? $user->role);

        if ($roleSlug !== 'admin') {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        Account::findOrFail($id)->delete();

        return response()->json(['message' => 'Deleted']);
    }
}
