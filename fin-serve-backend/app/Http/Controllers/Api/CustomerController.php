<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    // GET /api/customers
    public function index()
    {
        $customers = Customer::with('user')->get(); // Include user relationship if needed
        return response()->json(['data' => $customers], 200);
    }

    // GET /api/customers/{customer}
    public function show(Customer $customer)
    {
        $customer->load(['user', 'accounts', 'loans', 'kyc']); // eager load relations
        return response()->json(['data' => $customer], 200);
    }

    // POST /api/customers
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'dob' => 'nullable|date',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|unique:customers,email',
            'address' => 'nullable|string|max:500',
            'id_number' => 'nullable|string|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $customer = Customer::create($request->all());

        return response()->json(['data' => $customer], 201);
    }

    // PUT /api/customers/{customer}
    public function update(Request $request, Customer $customer)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'sometimes|exists:users,id',
            'first_name' => 'sometimes|string|max:255',
            'last_name' => 'sometimes|string|max:255',
            'dob' => 'nullable|date',
            'phone' => 'sometimes|string|max:20',
            'email' => 'sometimes|email|unique:customers,email,' . $customer->id,
            'address' => 'nullable|string|max:500',
            'id_number' => 'nullable|string|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $customer->update($request->all());

        return response()->json(['data' => $customer], 200);
    }

    // DELETE /api/customers/{customer}
    public function destroy(Customer $customer)
    {
        $customer->delete();
        return response()->json(['message' => 'Customer deleted successfully.'], 200);
    }
}
