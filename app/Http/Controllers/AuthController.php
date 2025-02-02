<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (auth()->attempt($request->only('email', 'password'))) {
            $user = auth()->user();
            $name = $user->name;
            $token = $request->user()->createToken($name);

            return response()->json([
                'user' => auth()->user(),
                'access_token' => $token->plainTextToken,
                'name' => $name,
                'message' => 'Login successful.',
            ]);
        }

        return response()->json(['message' => 'Invalid credentials.'], 401);
    }


    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'role' => 'required',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $request->role,
        ]);

        return response()->json(['message' => 'User created successfully']);
    }

    public function logout()
    {
        // auth()->logout();
        auth()->token()->revoke();
        return response()->json(['message' => 'Logout successful.']);
    }

    public function me()
    {
        return response()->json(auth()->user());
    }
}
