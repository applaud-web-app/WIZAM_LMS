<?php

namespace App\Http\Controllers\admin;

use Illuminate\Validation\ValidationException;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Mail\ForgotPassword;
use Carbon\Carbon;
use Mail;

class LoginController extends Controller
{
    public function login()
    {
        // IF ADMIN LOGIN THEN REDIRECT TO DASHBOARD
        if(Auth::check() && userHasPermission('admin-dashboard')){
            return redirect()->route('admin-dashboard');
        }
        return view('auth.login');
    }

    public function verifyUser(Request $request){
        $request->validate([
            'email'=>'required',
            'password'=>'required'
        ]);

        // Determine if 'remember me' is checked
        $rememberMe = $request->has('remember_me');

        // Check if the input is an email or username and authenticate
        // $credentials = filter_var($emailUsername, FILTER_VALIDATE_EMAIL) ? ['email' => $emailUsername, 'password' => $password]
        //     : ['username' => $emailUsername, 'password' => $password];

        $credentials = $request->only('email','password');
        if (Auth::attempt($credentials,$rememberMe)) {
            // Authentication passed, check if the user is an admin
            if (userHasPermission('admin-dashboard')) {
                // Redirect admin to dashboard
                return redirect()->route('admin-dashboard')->with('success','Successfully logged in');;
            }

            // If not an admin, log out the user and show an error
            Auth::logout();
            return redirect()->back()->with('error','You do not have permission to access the page');
        }
        return redirect()->back()->with('error','Invalid credentials');
    }

    public function forgotPassword()
    {
        return view('auth.forget-password');
    }

    public function verifyEmail(Request $request){
        // Validate the request
        try {
            // Validate the request
            $request->validate([
                'email' => 'required|email',
            ]);
        } catch (ValidationException $e) {
            // Handle the validation exception
            return response()->json([
                'status' => 'error',
                'message' => 'The email address provided is Invalid.'
            ]);
        }

        $user_email = $request->email;

        // Retrieve the user by email
        $user = User::where(['email'=>$user_email,'status'=>1])->first();

        // Check if the user exists and has the required permission
        if ($user && $user->hasPermissionTo('admin-dashboard')) {
            // CODE TO SEND MAIL
            try {
                $date = now();
                $expiryTime = $date->addMinutes(10);
                $expiryTimestamp = $expiryTime->timestamp;
                $url = route('update-forgot-password');
                $parms = "email=".$user_email."&expiry=".$expiryTimestamp;
                $encryptUrl = encrypturl($url,$parms);
                Mail::to($user_email)->send(new ForgotPassword($encryptUrl));
                return response()->json([
                    'status' => 'success',
                    'message' => 'Please check your mail for instructions on how to update your password! The mail will be valid for 10 minutes.'
                ]);
            } catch (\Throwable $th) {
                return response()->json([
                    'status' => 'error',
                    'message' => "Error : ".$th->getMessage()
                ]);
            }
        }

        // If the user does not exist or does not have the required permission
        return response()->json([
            'status' => 'error',
            'message' => 'The email address provided does not match any records in our system.'
        ]);
    }

    public function updateForgotPassword(Request $request){
        $data = decrypturl($request->eq);

        // GET BOTH PARAMETRES
        $user_email = $data['email'] ?? null;
        $expiry_time = $data['expiry'] ?? null;

        if(isset($user_email) && isset($expiry_time)){

            // CHECK LINK IS VALIDE OR NOT
            $date = now();
            $current_time = $date->timestamp;

            // AFTER 10 Minutes
            if($current_time > $expiry_time){
                return redirect(route('admin-login'))->with('error','The page has expired. Please visit the Forgot Password page to request a new link and try again.');
            }

            // Retrieve the user by email
            $user = User::where(['email'=>$user_email,'status'=>1])->first();

            // Check if the user exists and has the required permission
            if ($user && $user->hasPermissionTo('admin-dashboard')) {
                return view('auth.update-password');
            }
        }

        return redirect(route('admin-login'))->with('error','Something Went Wrong');
    }

    // UPDATE FORGOT PASSWORD
    public function updatepassword(Request $request){
  
        $request->validate([
            'password' => [
                'required',
                'string',
                'min:8', // Minimum length of 8 characters
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/'
            ],
            'confirm_password' => 'required|same:password'
        ]);
        $data = decrypturl($request->eq);

        // GET BOTH PARAMETRES
        $user_email = $data['email'] ?? null;
        $expiry_time = $data['expiry'] ?? null;

        if(isset($user_email) && isset($expiry_time)){
            // CHECK LINK IS VALIDE OR NOT
            $date = now();
            $current_time = $date->timestamp;

            // AFTER 10 Minutes
            if($current_time > $expiry_time){
                return redirect(route('admin-login'))->with('error','The page has expired. Please visit the Forgot Password page to request a new link and try again.');
            }

            // Retrieve the user by email
            $user = User::where(['email'=>$user_email,'status'=>1])->first();

            // Check if the user exists and has the required permission
            if ($user && $user->hasPermissionTo('admin-dashboard')) {
                $user->password = Hash::make($request->password);
                $user->save();
                return redirect(route('admin-login'))->with('success','Congratulations! Password Updated Successfully');
            }

        }

        return redirect(route('admin-login'))->with('error','Something Went Wrong');
    }


    // LOGOUT 
    public function logout(){
        Auth::logout();
        return redirect(route('admin-login'))->with('success','Logout Successfully ☺️☺️');
    }

}
