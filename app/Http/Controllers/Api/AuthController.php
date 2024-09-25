<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        // Validate the request data
        $validateUser = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed', // password_confirmation
            'country' => 'required',
        ]);

        // Check if validation fails
        if ($validateUser->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'errors' => $validateUser->errors()->all()
            ], 401);
        }

        try {
            // Create the user
            $user = User::create([
                'name' => $request->name, // Corrected from `full_name` to `name`
                'country' => $request->country ?? null,
                'phone_number' => $request->phone_number ?? null,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'status' => 1,
            ]);

            // Assign role to the user
            $user->assignRole('student');

            // Return success response
            return response()->json(['status'=> true,'message' => 'User registered successfully!', 'user' => $user], 201);
        } catch (\Throwable $th) {
            // Return error response
            return response()->json(['status'=> false, 'message' => 'Registration failed: ' . $th->getMessage()], 400);
        }
    }

    public function login(Request $request)
    {

        $validateUser = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

         // Check if validation fails
         if ($validateUser->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'errors' => $validateUser->errors()->all()
            ], 401);
        }


        try {
            if (Auth::attempt($request->only('email', 'password'))) {
                $user = Auth::user();
                $token = $user->createToken('auth_token')->plainTextToken; 

                return response()->json([
                    'status'=> true,
                    'message'=>'Logged In Successfully',
                    'token' => $token,
                    'user' => $user
                ],200);

            }else{
                return response()->json([
                    'status'=> false,
                    'message'=>'Invalid Login Details'
                ],401);
            }
        } catch (\Throwable $th) {
           // Return error response
           return response()->json(['status'=> false, 'message' => 'Login failed: ' . $th->getMessage()], 400);
        }
        
    }

    public function logout(Request $request)
    {
        try {
            $user = $request->user();
            $user->token()->delete();
            return response()->json([
                'status'=>true,
                'message' => 'User logged out successfully!'
            ],200);
        } catch (\Throwable $th) {
            return response()->json(['status'=> false, 'message' => 'Logout failed: ' . $th->getMessage()], 400);
        }
    }
}
