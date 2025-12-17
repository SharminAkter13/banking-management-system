<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Customer;
use App\Models\Loan;
use App\Models\Account;
use App\Models\Branch;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function __invoke()
    {
        return response()->json([
            'cards' => [
                'total_users'     => User::count(),
                'total_customers' => Customer::count(),
                'total_branches'  => Branch::count(),
                'active_loans'    => Loan::where('status', 'Active')->count(),
            ],

            'bank_balance' => Account::sum('balance'),

            'loan_chart' => Loan::selectRaw('MONTH(created_at) month, COUNT(*) total')
                ->whereYear('created_at', now()->year)
                ->groupBy('month')
                ->orderBy('month')
                ->pluck('total'),

            'recent_transactions' => DB::table('transactions')
                ->join('accounts', 'transactions.account_id', '=', 'accounts.id')
                ->join('customers', 'accounts.customer_id', '=', 'customers.id')
                ->select(
                    'transactions.id',
                    'transactions.amount',
                    'transactions.transaction_date',
                    'customers.first_name',
                    'customers.last_name'
                )
                ->latest('transactions.created_at')
                ->limit(5)
                ->get()
        ]);
    }
}
