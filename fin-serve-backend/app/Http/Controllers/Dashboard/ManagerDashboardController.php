<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class ManagerDashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $branch = $request->user()->employee->branch;

        return response()->json([
            'employees' => $branch->employees()->count(),
            'customers' => $branch->customers()->count(),

            'today_transactions' => $branch->accounts()
                ->whereHas('transactions', fn ($q) =>
                    $q->whereDate('transaction_date', today())
                )
                ->count(),

            'active_loans' => $branch->loans()
                ->where('status', 'Active')
                ->count(),
        ]);
    }
}
