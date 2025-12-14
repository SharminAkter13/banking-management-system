<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        if ($user->status !== 'active') {
            return response()->json(['message' => 'Account inactive'], 403);
        }

        return response()->json([
            'token' => $user->createToken('banking-dashboard')->plainTextToken,
            'user'  => $user->load('role')
        ]);
    }

    public function register(Request $request)
{
    $request->validate([
        'name'     => 'required|string|max:255',
        'email'    => 'required|email|unique:users,email',
        'password' => 'required|min:8|confirmed',
        'role_id'  => 'required|exists:roles,id'
    ]);

    $user = User::create([
        'name'     => $request->name,
        'email'    => $request->email,
        'password' => Hash::make($request->password),
        'role_id'  => $request->role_id,

        // 🔐 Banking-grade default
        'status'   => 'inactive'
    ]);

    return response()->json([
        'message' => 'Registration successful. Awaiting approval.',
        'user'    => $user
    ], 201);
}


    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out successfully']);
    }

    public function me(Request $request)
    {
        return response()->json(
            $request->user()->load('role', 'employee.branch')
        );
    }
}
