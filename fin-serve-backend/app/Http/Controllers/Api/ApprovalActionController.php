<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
<?php

namespace App\Http\Controllers;

use App\Models\ApprovalAction;
use Illuminate\Http\Request;

class ApprovalActionController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'approval_step_id' => 'required|exists:approval_steps,id',
            'approved_by' => 'required|exists:users,id',
            'action' => 'required|in:Approved,Rejected',
            'comments' => 'nullable'
        ]);

        $action = ApprovalAction::create($validated);

        return response()->json($action, 201);
    }
}
