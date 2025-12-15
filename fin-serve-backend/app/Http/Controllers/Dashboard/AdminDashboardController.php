<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        return response()->json([
            'role' => 'admin',
            'message' => 'Welcome to Admin Dashboard',
            'user' => $request->user(),
        ]);
    }
}
