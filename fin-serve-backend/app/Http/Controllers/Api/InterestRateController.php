<?php

namespace App\Http\Controllers\Api;


use App\Models\InterestRate;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class InterestRateController extends Controller
{
    public function index()
    {
        return response()->json(
            InterestRate::with('accountType')->get()
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'account_type_id' => 'required|exists:account_types,id',
            'rate' => 'required|numeric',
            'effective_date' => 'required|date'
        ]);

        $rate = InterestRate::create($validated);

        return response()->json($rate, 201);
    }

    public function show($id)
    {
        return response()->json(
            InterestRate::with('accountType')->findOrFail($id)
        );
    }

    public function destroy($id)
    {
        InterestRate::findOrFail($id)->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
