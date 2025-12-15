<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OfficerDashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        return response()->json([
            'role' => 'loan-officer',
            'message' => 'Welcome to Loan Officer Dashboard',
            'user' => $request->user(),
        ]);
    }
}
