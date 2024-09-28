<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Faq;
use App\Models\SubCategory;
use App\Models\Blog;
use App\Models\Pages;
use App\Models\Exam;
use App\Models\GeneralSetting;
use App\Models\Enquiry;

class CmsController extends Controller
{

    public function siteSetting(){
        try {
            $siteSetting = GeneralSetting::select('site_logo','favicon','site_name','tag_line','description')->first();
            return response()->json(['status'=> true,'data' => $siteSetting], 201);
        } catch (\Throwable $th) {
            return response()->json(['status'=> false,'error' => $th->getMessage()], 500);
        }
    }

    public function faq(){
        try {
            $faq = Faq::select('question','answer')->where('status',1)->get();
            return response()->json(['status'=> true,'data' => $faq], 201);
        } catch (\Throwable $th) {
            return response()->json(['status'=> false,'error' => $th->getMessage()], 500);
        }
    }

    public function course(){
        try {
            $course = SubCategory::select('name','id')->where('status',1)->get();
            return response()->json(['status'=> true,'data' => $course], 201);
        } catch (\Throwable $th) {
            return response()->json(['status'=> false,'error' => $th->getMessage()], 500);
        }
    }

    public function popularExams(){
        try {
            $popularExams = Exam::select('image','title','description','price','is_free','slug')->where(['favourite'=>1,'status'=>1])->latest()->take(3)->get();
            // WHERE IS_FREE IS 0 THEN SHOW PRICE
            return response()->json(['status'=> true,'data' => $popularExams], 201);
        } catch (\Throwable $th) {
            return response()->json(['status'=> false,'error' => $th->getMessage()], 500);
        }
    }

    public function exams(){
        try {
            $exam = Exam::select('image','title','description','price','is_free','slug')->where(['favourite'=>1,'status'=>1])->latest()->get();
            return response()->json(['status'=> true,'data' => $exam], 201);
        } catch (\Throwable $th) {
            return response()->json(['status'=> false,'error' => $th->getMessage()], 500);
        }
    }

    public function examDetail($slug){
        try {
            $exam = Exam::select('image','title','description','price','is_free','slug')->where(['slug'=>$slug,'status'=>1])->first();

            // Check if the exam exists
            if (!$exam) {
                return response()->json(['status' => false, 'error' => 'Exam not found'], 404);
            }
            return response()->json(['status' => true, 'data' => $exam], 200);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'error' => $th->getMessage()], 500);
        }
    }

    public function latestResources(){
        try {
            $latestResources = Blog::with('category:id,name')->select('title','short_description','image','slug','created_at')->where('status',1)->latest()->take(3)->get();
            return response()->json(['status'=> true,'data' => $latestResources], 201);
        } catch (\Throwable $th) {
            return response()->json(['status'=> false,'error' => $th->getMessage()], 500);
        }
    }

    public function resources(){
        try {
            $resources = Blog::with('category:id,name')->select('title','category_id','short_description','image','slug','created_at')->where('status',1)->latest()->get();
            return response()->json(['status'=> true,'data' => $resources], 201);
        } catch (\Throwable $th) {
            return response()->json(['status'=> false,'error' => $th->getMessage()], 500);
        }
    }

    public function resourceDetail($slug){
        try {
            // Fetch the blog with its category and any other necessary relationships
            $blog = Blog::with('category:id,name','user:id,name')->select('id','category_id','user_id','title', 'content', 'short_description', 'image', 'slug', 'created_at','content')->where(['slug'=>$slug,'status'=>1])->first();
            // Check if the blog exists
            if (!$blog) {
                return response()->json(['status' => false, 'error' => 'Blog not found'], 404);
            }

            $relatedBlogs = Blog::with('category:id,name','user:id,name')->select('title','category_id','short_description','image','slug','created_at')->where(['category_id'=>$blog->category_id,'status'=>1])->get();

            return response()->json(['status' => true, 'data' => $blog,'related'=>$relatedBlogs], 200);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'error' => $th->getMessage()], 500);
        }
    }

    public function pages(){
        try {
            $pages = Pages::select('title','slug')->where('status',1)->latest()->get();
            return response()->json(['status'=> true,'data' => $pages], 201);
        } catch (\Throwable $th) {
            return response()->json(['status'=> false,'error' => $th->getMessage()], 500);
        }
    }

    public function pageDetail($slug){
        try {
            // Fetch the blog with its category and any other necessary relationships
            $page = Pages::select('title','description','meta_title','meta_description','meta_keywords')->where(['slug'=>$slug,'status'=>1])->first();
            // Check if the page exists
            if (!$page) {
                return response()->json(['status' => false, 'error' => 'Page not found'], 404);
            }
            return response()->json(['status' => true, 'data' => $page], 200);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'error' => $th->getMessage()], 500);
        }
    }

    public function contactUs(Request $request){
        // Validate the request data
        $validateUser = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'phone' => 'nullable|string|max:255',
            'study_mode' => 'nullable|string|max:255',
            'course' => 'nullable|exists:sub_categories,id', // Ensure that 'course' exists in 'subcategory' table
            'hear_by' => 'nullable|string|max:255',
            'message' => 'nullable|string|max:1000',
            'accept_condition' => 'required|accepted', // Ensure that the checkbox is checked
            'contact_me' => 'required' 
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
            // Create the enquiry with validated data
            Enquiry::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'study_mode' => $request->study_mode,
                'course_id' => $request->course,
                'hear_by' => $request->hear_by,
                'message' => $request->message,
                'accept_condition' => $request->accept_condition,
                'contact_me' => $request->contact_me, 
            ]);
    
            // Return success response
            return response()->json([
                'status' => true,
                'message' => 'Enquiry submitted successfully! We will contact you shortly!',
            ], 201);
        } catch (\Throwable $th) {
            // Return error response
            return response()->json([
                'status' => false,
                'message' => 'Failed to submit! Please try again later: ' . $th->getMessage(),
            ], 400);
        }
    }

}
