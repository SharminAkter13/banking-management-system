<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TellerDashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        return response()->json([
            'role' => 'bank-teller',
            'message' => 'Welcome to Bank Teller Dashboard',
            'user' => $request->user(),
        ]);
    }
}
