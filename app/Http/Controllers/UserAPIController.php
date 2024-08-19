<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Validation\ValidationException;
class UserAPIController extends Controller
{
    public function register(Request $request)
    {
        try {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone_no' => ['required', 'max:15', 'unique:users'],
            'nin' => ['required'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $roleId = 5;

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone_no' => $request->phone_no,
            'password' => Hash::make($request->password),
            'role_id' => $roleId,
            'nin' => $request->nin
        ]);

        // Generate JWT token for the new user
        $token = JWTAuth::fromUser($user);
        unset($user['role_id']);
        unset($user['updated_at']);
        unset($user['created_at']);

        return response()->json([
            'responseCode' => 201,
            'responseMessage' => 'success',
            'data' => $user,
            'token' => $token,
        ], 201);
    } catch (ValidationException $e) {
        return response()->json([
            'errors' => $e->errors(),
            'responseCode' => 422 // Adding the response code
        ], 422);
    }
        
    }

    public function login(Request $request)
    {
        if ($request->method() !== 'POST') {
            return response()->json(['error' => 'The request method is invalid. Please use POST.'], 405);
        }
        $credentials = $request->only('email', 'password');

        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['error' => 'Invalid credentials', 'responseCode' => 401], 401);
        }

        return response()->json(['responseCode' => 200, 'responseMessage' => 'success', 'token' => $token]);
    }


    public function refresh()
    {
        return response()->json([
            'token' => JWTAuth::refresh()
        ]);
    }

    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());

        return response()->json(['message' => 'User logged out successfully']);
    }

}
