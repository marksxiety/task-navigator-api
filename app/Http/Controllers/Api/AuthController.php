<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Helpers\ResponseHelper;

class AuthController extends Controller
{
    public function profile(Request $request)
    {
        try {
            if ($request->user()) {
                return ResponseHelper::success('Profile data fetched', $request->user(), 200);
            } else {
                return ResponseHelper::error('Invalid Request', [], 400);
            }
        } catch (\Exception $e) {
            return ResponseHelper::error('An error occurred', $e->getMessage(), 500);
        }
    }

    public function register(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255|unique:users,email',
                'password' => 'required|string|min:8|max:255',
            ]);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            if ($user) {
                $token = $user->createToken($user->name, ['auth-token'])->plainTextToken;

                return ResponseHelper::success('Registration Successful',[
                    'token_type' => 'Bearer',
                    'token' => $token
                ], 200);
            } else {
                return ResponseHelper::error('Registration unsuccessful', [], 500);
            }
        } catch (\Exception $e) {
            return ResponseHelper::error('An error occurred', $e->getMessage(), 500);
        }
    }

    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email|max:255',
                'password' => 'required|string|min:8|max:255',
            ]);

            $user = User::where('email', $request->email)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                return ResponseHelper::error('Provided credentials are invalid', [], 401);
            }

            $token = $user->createToken($user->name, ['auth-token'])->plainTextToken;

            return ResponseHelper::success('Login Successful',[
                'token_type' => 'Bearer',
                'token' => $token
            ], 200);
        } catch (\Exception $e) {
            return ResponseHelper::error('An error occurred', $e->getMessage(), 500);
        }
    }

    public function logout(Request $request)
    {
        try {
            $user = User::where('id', $request->id)->first();

            if ($user) {
                $user->tokens()->delete();
                return ResponseHelper::success('Logout Successful', [], 200);
            } else {
                return ResponseHelper::error('User not found', [], 404);
            }
        } catch (\Exception $e) {
            return ResponseHelper::error('An error occurred', $e->getMessage(), 500);
        }
    }
}
