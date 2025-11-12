<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $data['email'])->first();
        if (!$user || !Hash::check($data['password'], $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $token = Str::random(60);
        $user->api_token = $token;
        $user->save();

        return response()->json([
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'role' => $user->role,
            ],
            'permissions' => $user->role === 'admin'
                ? ['create_users', 'assign_courses', 'list_users']
                : ['list_assigned_courses'],
        ]);
    }

    public function logout(Request $request)
    {
        $user = auth()->user();
        if ($user) {
            $user->api_token = null;
            $user->save();
        }
        return response()->json(['message' => 'Logged out']);
    }
}

