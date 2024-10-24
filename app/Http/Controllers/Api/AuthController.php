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
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use App\Mail\ForgotPassword;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;

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
            $country = Country::where('shortname', $request->country)->first();
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

            DB::commit();
            // Return success response
            return response()->json(['status'=> true,'message' => 'User Registered Successfully!','token'=>$token], 201);
        } catch (\Throwable $th) {
            DB::rollBack();
            // Return error response
            return response()->json(['status'=> false, 'message' => 'Registration Failed: ' . $th->getMessage()], 400);
        }
    }

    public function login(Request $request)
    {
        // Validate the request
        $validateUser = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|max:255',
        ]);

        // Check if validation fails
        if ($validateUser->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'errors' => $validateUser->errors()->all(),
            ], 422); // Change status code to 422 for validation errors
        }

        try {
            // Attempt to log the user in
            if (Auth::attempt($request->only('email', 'password'))) {
                $user = Auth::user();

                // Check if the user's status is active (assuming 1 means active)
                if ($user->status !== 1) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Your account is inactive. Please contact support.',
                    ], 403);
                }

                // Check if the user has the 'student' role
                if (!$user->hasAnyRole(['student', 'guest'])) {
                    return response()->json([
                        'status' => false,
                        'message' => 'You do not have the required permission to access this resource.',
                    ], 403);
                }

                // CREATE TOKEN
                $token = $user->createToken('auth_token')->plainTextToken;

                return response()->json([
                    'status' => true,
                    'message' => 'Logged In Successfully',
                    'token'=> $token
                ], 200);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid Login Details',
                ], 401);
            }
        } catch (\Throwable $th) {
            // Return error response
            return response()->json([
                'status' => false,
                'message' => 'Login failed: ' . $th->getMessage(),
            ], 500); // Changed to 500 for general server errors
        }
    }

    // public function logout(Request $request)
    // {
    //     try {
    //         // Check if the user is authenticated
    //         if ($request->user()) {
    //             // Revoke the user's current access token
    //             $request->user()->currentAccessToken()->delete();
                
    //             return response()->json([
    //                 'status' => true,
    //                 'message' => 'Logged out successfully',
    //             ]);
    //         } else {
    //             return response()->json([
    //                 'status' => false,
    //                 'message' => 'User is not authenticated',
    //             ], 401); // Unauthorized
    //         }
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'Logout failed: ' . $e->getMessage(),
    //         ], 500); // Internal Server Error
    //     }
    // }


    public function forgotPassword(Request $request)
    {
        try {
            // Validate the incoming request
            $request->validate([
                'email' => 'required|email', // Ensure the email format is correct
            ]);
    
            // Find the user by email
            $user = User::where('email', $request->email)->first();
    
            // Check if user exists
            if (!$user) {
                return response()->json([
                    'status' => false, 
                    'message' => 'The email you entered does not match any account. Please try again with a registered email.'
                ], 404);
            }
    
            // Check if the user is active
            if ($user->status != 1) {
                return response()->json([
                    'status' => false, 
                    'message' => 'Your account is currently inactive. Please contact site support for assistance.'
                ], 403); // Changed to 403 for better HTTP status code
            }
    
            // Check if the user has the 'student' role
            if ($user && $user->hasRole('student')) {
                // Send reset password link
                $token = Password::createToken($user);
    
                // Create the reset URL using the frontend environment variable
                $resetUrl = env('FRONTEND_URL') . "/reset-password?token=" . urlencode($token) . "&email=" . urlencode($request->email);
    
                // Send the reset email
                Mail::to($user->email)->send(new ForgotPassword($resetUrl));
    
                return response()->json([
                    'status' => true, 
                    'message' => 'A password reset link has been sent to your email address. Please check your inbox.'
                ], 200);
            } else {
                return response()->json([
                    'status' => false, 
                    'message' => 'You do not have the necessary permissions to perform this action. Please contact site support.'
                ], 403); // Changed to 403 for unauthorized action
            }
        } catch (ValidationException $e) {
            // Return custom validation error response
            return response()->json([
                'status' => false,
                'message' => 'There was an issue with your submission. Please check the errors below and try again.',
                'errors' => $e->validator->errors(),
            ], 422);
        } catch (\Exception $e) {
            // Handle other exceptions and return a response
            return response()->json([
                'status' => false, 
                'message' => 'An unexpected error occurred while processing your request. Please try again later or contact support if the issue persists.',
                'error' => $e->getMessage(), // Optionally include the actual error message for debugging purposes
            ], 500);
        }
    }
    

    public function resetPassword(Request $request)
    {
        try {
            // Validate the incoming request
            $request->validate([
                'email' => 'required|email',
                'token' => 'required',
                'password' => 'required|confirmed|min:8', // Ensure password is confirmed and has a minimum length
            ]);
    
            // Attempt to reset the password
            $response = Password::reset(
                $request->only('email', 'password', 'password_confirmation', 'token'),
                function ($user, $password) {
                    // Hash the password using Hash::make() and save the user
                    $user->password = Hash::make($password);
                    $user->save();
                }
            );
    
            // Check if the password reset was successful
            if ($response == Password::PASSWORD_RESET) {
                return response()->json(['status' => true, 'message' => 'Your password has been reset successfully.'], 200);
            }
            return response()->json(['status' => false, 'message' => 'The password reset link is invalid or has expired. Please request a new link.'], 400);
            
        } catch (ValidationException $e) {
            // Handle validation errors
            return response()->json(['status' => false, 'errors' => $e->validator->errors()], 422);
        } catch (\Exception $e) {
            // Handle any other exceptions
            return response()->json(['status' => false, 'message' => 'An unexpected error occurred : '.$e->getMessage().' Please try again later.'], 500);
        }
    }

    // USER LOGOUT
    public function logout(Request $request)
    {
        // Retrieve the authenticated user from the request attributes
        $user = $request->attributes->get('authenticatedUser');

        // Check if the user is authenticated
        if ($user) {
            // Revoke the token
            $user->currentAccessToken()->delete();

            return response()->json([
                'status' => true,
                'message' => 'Logged out successfully',
            ], 200);
        }

        // Return an unauthorized response if user is not authenticated
        return response()->json([
            'status' => false,
            'message' => 'User not authenticated',
        ], 401);
    }



    // USER PROFILE

    public function profile(Request $request)
    {
        // Retrieve the authenticated user from the request attributes
        $user = $request->attributes->get('authenticatedUser');

        // Check if the user is authenticated
        if ($user) {
            return response()->json([
                'status' => true,
                'user' => $user,
            ], 200);
        }

        // Return an unauthorized response if user is not authenticated
        return response()->json([
            'status' => false,
            'message' => 'User not authenticated',
        ], 401);
    }


    // UPDATE PROFILE
    public function updateProfile(Request $request)
    {
        try {
            // Get the authenticated user
            $user = $request->attributes->get('authenticatedUser');

            // Check if the user is authenticated
            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'User not authenticated',
                    'user'=>$user
                ], 401);
            }

            // Validate the request data
            $request->validate([
                'title' => 'nullable|string|max:255',
                'full_name' => 'required|string|max:255',
                'phone_number' => 'required|string|max:15',
                'email' => 'required|email',
                'dob' => 'required|date',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', 
            ]);

            // VERIFY NEW EMAIL IS UNIQUE OR NOT
            $emailVerify = User::where('email', $request->input('email'))->where('id','!=',$user->id)->first();
            if ($emailVerify) {
                return response()->json([
                    'status' => false,
                    'message' => 'Email already exists',
                ], 409);
            }

            // Update user profile fields
            $user->title = $request->input('title', $user->title);
            $user->name = $request->input('full_name', $user->full_name);
            $user->phone_number = $request->input('phone_number', $user->phone_number);
            $user->email = $request->input('email', $user->email);
            $user->dob = $request->input('dob', $user->dob);

            // Handle image upload
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '_' . $image->getClientOriginalName();
                
                // Store the image directly in the public folder
                $image->move(public_path('users'), $imageName); 
                
                // Construct the full URL to the uploaded image
                $user->image = env('APP_URL') . '/users/' . $imageName; 
            }

            // Save the changes to the database
            if ($user->save()) {
                return response()->json([
                    'status' => true,
                    'message' => 'Profile updated successfully'
                ], 200);
            }

            return response()->json([
                'status' => false,
                'message' => 'Profile update failed',
            ], 500);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    // USER PASWWORD UPDATE
    public function updatePassword(Request $request)
    {
        try {
            // Get the authenticated user
            $user = $request->attributes->get('authenticatedUser');
    
            // Check if the user is authenticated
            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'User not authenticated',
                ], 401);
            }
    
            // Validate the request data
            $request->validate([
                'current_password' => 'required|string',
                'new_password' => 'required|string|min:6|confirmed', // Ensure it has a confirmation field
            ]);
    
            // Check if the current password is correct
            if (!Hash::check($request->input('current_password'), $user->password)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Current password is incorrect',
                ], 403); 
            }
    
            // Update the user's password
            $user->password = Hash::make($request->input('new_password'));
    
            // Save the changes to the database
            if ($user->save()) {
                return response()->json([
                    'status' => true,
                    'message' => 'Password updated successfully',
                ], 200);
            }
    
            return response()->json([
                'status' => false,
                'message' => 'Password update failed',
            ], 500);
    
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'An error occurred: ' . $e->getMessage(),
            ], 500);
        }
    }

    // USER LOGOUT ALL DEVICE
    public function logoutFromAllLoginDevices(Request $request)
    {
        try {
            // Get the authenticated user
            $user = $request->attributes->get('authenticatedUser');
    
            // Check if the user is authenticated
            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'User not authenticated',
                ], 401);
            }
    
            // Revoke all tokens for the user
            $user->tokens()->delete(); 
    
            // Optionally, you can flush the session if using sessions as well
            Session::flush(); // This will log out the user from the current session
    
            return response()->json([
                'status' => true,
                'message' => 'Logged out from all devices successfully.',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'An error occurred: ' . $e->getMessage(),
            ], 500);
        }
    }


}
