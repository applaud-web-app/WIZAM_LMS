<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SubCategory;
use App\Models\ExamType;
use App\Models\User;
use App\Models\Exam;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class StudentController extends Controller
{

    public function profile(Request $request)
    {
        // Get the authenticated user
        $user = Auth::user();

        // Check if the user is authenticated
        if ($user) {
            return response()->json([
                'status' => true,
                'user' => $user,
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'User not authenticated',
            ], 401);
        }
    }

    public function updateProfile(Request $request)
    {
        try {
            // Get the authenticated user
            $user = Auth::user();

            // Check if the user is authenticated
            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'User not authenticated',
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
            if ($request->has('email') && $request->input('email') !== $user->email) {
                $emailVerify = User::where('email', $request->input('email'))->first();
                if ($emailVerify) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Email already exists',
                    ], 409); // Conflict status code
                }
            }

            // Update user profile fields
            $user->title = $request->input('title', $user->title);
            $user->full_name = $request->input('full_name', $user->full_name);
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
                    'message' => 'Profile updated successfully',
                    'user' => $user,
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

    public function updatePassword(Request $request)
    {
        try {
            // Get the authenticated user
            $user = Auth::user();
    
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

    public function logoutFromAllLoginDevices(Request $request)
    {
        try {
            // Get the authenticated user
            $user = Auth::user();
    
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
    


    public function syllabus(){
        try {
            $data = SubCategory::select('id','name','description')->where('status', 1)->whereJsonLength('sections', '>', 0)->get();
            return response()->json(['status'=> true,'data' => $data], 201);
        } catch (\Throwable $th) {
            return response()->json(['status'=> false,'error' => $th->getMessage()], 500);
        }
    }

    public function examType(){
        try {
            $type = ExamType::select('name','slug')->where('status', 1)->get();
            return response()->json(['status'=> true,'data' => $type], 201);
        } catch (\Throwable $th) {
            return response()->json(['status'=> false,'error' => $th->getMessage()], 500);
        }
    }

    public function exams(Request $request)
    {
        try {
            // Fetch exam type by slug and status
            $examType = ExamType::select('id')->where('slug', $request->slug)->where('status', 1)->first();

            if ($examType) {
                // Fetch exam data grouped by type.slug
                $examData = Exam::select(
                        'exam_types.slug', // Fetch type slug
                        'exams.title', // Fetch exam title
                        DB::raw('COUNT(questions.id) as total_questions'), // Count total questions for each exam
                        DB::raw('SUM(CAST(questions.default_marks AS DECIMAL)) as total_marks'), // Sum total marks for each exam
                        'exams.exam_duration as total_time' // Total exam duration for each exam
                    )
                    ->leftJoin('exam_types', 'exams.exam_type_id', '=', 'exam_types.id') // Join with the exam_types table
                    ->leftJoin('exam_questions', 'exams.id', '=', 'exam_questions.exam_id') // Join with exam_questions
                    ->leftJoin('questions', 'exam_questions.question_id', '=', 'questions.id') // Join with questions
                    ->where('exams.exam_type_id', $examType->id) // Filter by the provided exam type
                    ->where('exams.subcategory_id', $request->category) // Filter by subcategory_id
                    ->where('exams.status', 1) // Filter by exam status
                    ->groupBy('exam_types.slug', 'exams.id', 'exams.title', 'exams.exam_duration') // Group by type and exam details
                    ->get();

                // Initialize array to store formatted exam data
                $formattedExamData = [];

                foreach ($examData as $exam) {
                    // Group exams by slug (exam type)
                    if (!isset($formattedExamData[$exam->slug])) {
                        $formattedExamData[$exam->slug] = [];
                    }

                    // Add exam details to the corresponding type slug
                    $formattedExamData[$exam->slug][] = [
                        'title' => $exam->title,
                        'questions' => $exam->total_questions ?? 0,
                        'time' => $exam->total_time ?? 0 . ' hrs', // Append 'hrs' to time
                        'marks' => $exam->total_marks ?? 0,
                    ];
                }

                // Return the formatted data as JSON
                return response()->json(['status' => true, 'data' => $formattedExamData], 201);
            }

            // Return error if exam type not found
            return response()->json(['status' => false, 'error' => "Exam Not Found"], 404);
            
        } catch (\Throwable $th) {
            // Return error response with exception message
            return response()->json(['status' => false, 'error' => $th->getMessage()], 500);
        }
    }

}
