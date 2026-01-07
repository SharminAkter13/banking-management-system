<?php

namespace App\Http\Controllers\Api;

use App\Models\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller; // <--- ADD THIS LINE

class RoleController extends Controller
{
    public function index() { return response()->json(Role::all()); }

    public function store(Request $request) {
        $validated = $request->validate([
            'name' => 'required|unique:roles',
            'slug' => 'required|unique:roles',
        ]);
        return response()->json(Role::create($validated), 201);
    }

    public function update(Request $request, $id) {
        $role = Role::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|unique:roles,name,' . $id,
            'slug' => 'required|unique:roles,slug,' . $id,
        ]);
        $role->update($validated);
        return response()->json($role);
    }
}