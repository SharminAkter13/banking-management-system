<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Approval;

class ApprovalDashboardController extends Controller
{
    public function __invoke()
    {
        return response()->json([
            'pending' => Approval::where('status', 'Pending')->count(),
            'in_progress' => Approval::where('status', 'In Progress')->count(),
            'approved' => Approval::where('status', 'Approved')->count(),
            'rejected' => Approval::where('status', 'Rejected')->count(),

            'recent' => Approval::latest()
                ->limit(5)
                ->get(['entity_type', 'status', 'created_at'])
        ]);
    }
}
