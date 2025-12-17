<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TellerDashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        return response()->json([
            'today_transactions' => \DB::table('transactions')
                ->whereDate('transaction_date', today())
                ->count(),

            'cash_processed' => \DB::table('transactions')
                ->whereDate('transaction_date', today())
                ->sum('amount'),
        ]);
    }
}

