<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller
{
    // For EmployeeList.vue
    public function index()
    {
        return Employee::with(['user', 'branch'])->get();
    }

    // For EmployeeCreate.vue
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            'branch_id' => 'required|exists:branches,id',
            'role_id' => 'required|exists:roles,id',
            'position' => 'required|string',
        ]);

        return DB::transaction(function () use ($request) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role_id' => $request->role_id,
                'status' => 'active',
            ]);

            return Employee::create([
                'user_id' => $user->id,
                'branch_id' => $request->branch_id,
                'position' => $request->position,
                'salary' => $request->salary ?? 0,
                'hire_date' => now(),
            ]);
        });
    }

    // For EmployeeView.vue and EmployeeEdit.vue (fetching data)
    public function show($id)
    {
        return Employee::with(['user', 'branch'])->findOrFail($id);
    }

    // For EmployeeEdit.vue (saving data)
    public function update(Request $request, $id)
    {
        $employee = Employee::findOrFail($id);
        
        $employee->user->update([
            'name' => $request->user['name'],
            'status' => $request->user['status'],
        ]);

        $employee->update([
            'position' => $request->position,
            'salary' => $request->salary,
        ]);

        return response()->json(['message' => 'Employee updated successfully']);
    }
}