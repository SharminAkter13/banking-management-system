<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ManagerDashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        return response()->json([
            'role' => 'branch-manager',
            'message' => 'Welcome to Branch Manager Dashboard',
            'user' => $request->user(),
        ]);
    }
}
