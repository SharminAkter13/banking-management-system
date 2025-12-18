<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    // ===============================
    // Get all users with role info
    // ===============================
    public function index()
    {
        $users = User::with('role')->get();
        return response()->json(['data' => $users]);
    }

    // ===============================
    // Get single user by id
    // ===============================
    public function show(User $user)
    {
        $user->load('role');
        return response()->json(['data' => $user]);
    }

    // ===============================
    // Create new user
    // ===============================
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'role_id' => ['required', Rule::exists('roles', 'id')],
            'status' => 'sometimes|in:active,inactive'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id,
            'status' => $request->status ?? 'active',
        ]);

        $user->load('role');

        return response()->json(['data' => $user], 201);
    }

    // ===============================
    // Update user
    // ===============================
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => ['sometimes','required','email', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8',
            'role_id' => ['sometimes','required', Rule::exists('roles', 'id')],
            'status' => 'sometimes|in:active,inactive'
        ]);

        $user->name = $request->name ?? $user->name;
        $user->email = $request->email ?? $user->email;

        if ($request->password) {
            $user->password = Hash::make($request->password);
        }

        if ($request->role_id) {
            $user->role_id = $request->role_id;
        }

        if ($request->status) {
            $user->status = $request->status;
        }

        $user->save();
        $user->load('role');

        return response()->json(['data' => $user]);
    }

    // ===============================
    // Delete user
    // ===============================
    public function destroy(User $user)
    {
        $user->delete();
        return response()->json(['message' => 'User deleted successfully.']);
    }

    // ===============================
    // Get authenticated user info
    // ===============================
    public function me(Request $request)
    {
        $user = $request->user()->load('role');

        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => [
                'id' => $user->role->id,
                'slug' => $user->role->slug,
                'name' => $user->role->name,
            ],
            'status' => $user->status,
        ]);
    }
}
