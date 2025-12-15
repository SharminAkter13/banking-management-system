<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CustomerDashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        return response()->json([
            'role' => 'customer',
            'message' => 'Welcome to Customer Dashboard',
            'user' => $request->user(),
        ]);
    }
}
