<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\Country;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed', // password_confirmation
            'country' => 'required|string|max:255',
            'phone_number' => 'nullable|string|max:20',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors()->all()
            ], 401);
        }

        DB::beginTransaction();
        try {
            $country = Country::where('name', $request->country)->first();
            $country_id = $country ? $country->id : null;

            // Create the user
            $user = User::create([
                'name' => $request->name,
                'country' => $country_id,
                'phone_number' => $request->phone_number ?? null,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'status' => 1,
            ]);

            // Assign role to the user
            $user->assignRole('student');
            
            // Log in the user
            Auth::login($user);

            // Create the authentication token
            $token = $user->createToken('auth_token')->plainTextToken;
            $cookie = cookie('jwt', $token, 60 * 24,null, null, true, true); // 1 DAY, HttpOnly, Secure

            DB::commit();
            // Return success response
            return response()->json(['status'=> true,'message' => 'User Registered Successfully!'], 201)->withCookie($cookie);
        } catch (\Throwable $th) {
            DB::rollBack();
            // Return error response
            return response()->json(['status'=> false, 'message' => 'Registration Failed: ' . $th->getMessage()], 400);
        }
    }

    public function login(Request $request){
        $validateUser = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|max:255',
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
            if (Auth::attempt($request->only('email', 'password'))) { // make sure user status is to be 1
                $user = Auth::user();

                // Check if the user's status is active (assuming 1 means active)
                if ($user->status !== 1) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Your account is inactive. Please contact support.'
                    ], 403);
                }

                // Check if the user has the 'student' role
                if (!$user->hasRole('student')) {
                    return response()->json([
                        'status' => false,
                        'message' => 'You do not have the required permission to access this resource.'
                    ], 403);
                }
                
                // CREATE TOKEN
                $token = $user->createToken('auth_token')->plainTextToken;
                $cookie = cookie('jwt',$token,60*24,null, null, true, true); // 1 DAY, HttpOnly, Secure

                return response()->json([
                    'status'=> true,
                    'message'=>'Logged In Successfully'
                ],200)->withCookie($cookie);

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

    

    // public function login(Request $request)
    // {

    //     $validateUser = Validator::make($request->all(), [
    //         'email' => 'required|string|email',
    //         'password' => 'required|string',
    //     ]);

    //      // Check if validation fails
    //      if ($validateUser->fails()) {
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'Validation Error',
    //             'errors' => $validateUser->errors()->all()
    //         ], 401);
    //     }


    //     try {
    //         if (Auth::attempt($request->only('email', 'password'))) {
    //             $user = Auth::user();
    //             $token = $user->createToken('auth_token')->plainTextToken; 

    //             return response()->json([
    //                 'status'=> true,
    //                 'message'=>'Logged In Successfully',
    //                 'token' => $token,
    //                 'user' => $user
    //             ],200);

    //         }else{
    //             return response()->json([
    //                 'status'=> false,
    //                 'message'=>'Invalid Login Details'
    //             ],401);
    //         }
    //     } catch (\Throwable $th) {
    //        // Return error response
    //        return response()->json(['status'=> false, 'message' => 'Login failed: ' . $th->getMessage()], 400);
    //     }
        
    // }


    public function logout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();
            $cookie = cookie('jwt', null, -1); // Clear the JWT cookie

            return response()->json([
                'status' => true,
                'message' => 'Logged out successfully'
            ], 200)->withCookie($cookie);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Logout failed: ' . $e->getMessage()
            ], 500);
        }
    }
}
