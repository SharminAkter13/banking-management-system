<?php

namespace App\Http\Controllers\Api;


use App\Models\KycForm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class KYCFormController extends Controller
{
    /**
     * Display a listing of KYC forms.
     */
    public function index()
    {
        $forms = KycForm::with('customer')->get();
        return response()->json($forms);
    }

    /**
     * Store a newly created KYC form in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'customer_id'       => 'required|exists:customers,id',
            'first_name'        => 'required|string|max:255',
            'last_name'         => 'required|string|max:255',
            'father_first_name' => 'nullable|string|max:255',
            'father_last_name'  => 'nullable|string|max:255',
            'gender'            => 'required|in:Male,Female',
            'marital_status'    => 'required|in:Single,Married',
            'dob'               => 'required|date',
            'nationality'       => 'required|string|max:255',
            'status_type'       => 'required|in:Resident Individual,Non Resident,Foreign National',
            'pan'               => 'nullable|string|max:255',
            'proof_identity'    => 'nullable|string|max:255',
            'address_line1'     => 'required|string|max:255',
            'address_line2'     => 'nullable|string|max:255',
            'city'              => 'required|string|max:255',
            'state'             => 'required|string|max:255',
            'country'           => 'required|string|max:255',
            'postal_code'       => 'required|string|max:255',
            'phone'             => 'required|string|max:255',
            'email'             => 'nullable|email|max:255',
            'proof_address'     => 'nullable|json', // Matches JSON type in SQL
            'documents'         => 'nullable|json', // Matches JSON type in SQL
            'signature_path'    => 'nullable|string|max:255',
            'signed_date'       => 'required|date',
            'status'            => 'nullable|in:Pending,Approved,Rejected',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $kycForm = KycForm::create($request->all());

        return response()->json([
            'message' => 'KYC Form submitted successfully',
            'data'    => $kycForm
        ], 201);
    }

    /**
     * Display the specified KYC form.
     */
    public function show($id)
    {
        $kycForm = KycForm::with('customer')->findOrFail($id);
        return response()->json($kycForm);
    }

    /**
     * Update the specified KYC form in storage.
     */
    public function update(Request $request, $id)
    {
        $kycForm = KycForm::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'status' => 'sometimes|in:Pending,Approved,Rejected',
            'email'  => 'sometimes|email|max:255',
            'phone'  => 'sometimes|string|max:255',
            // Add other fields here if you want to allow full updates
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $kycForm->update($request->all());

        return response()->json([
            'message' => 'KYC Form updated successfully',
            'data'    => $kycForm
        ]);
    }

    /**
     * Remove the specified KYC form from storage.
     */
    public function destroy($id)
    {
        $kycForm = KycForm::findOrFail($id);
        $kycForm->delete();

        return response()->json(['message' => 'KYC Form deleted successfully']);
    }
}