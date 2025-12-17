<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use Illuminate\Http\Request;

class OfficerDashboardController extends Controller
{
    public function __invoke()
    {
        return response()->json([
            'pending_loans' => Loan::where('status', 'Pending')->count(),
            'approved_today' => Loan::where('status', 'Active')
                ->whereDate('updated_at', today())
                ->count(),
        ]);
    }
}
