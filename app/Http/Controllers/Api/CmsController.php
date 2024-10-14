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
use App\Models\Plan;
use App\Models\HomeCms;
use App\Models\User;
use Stripe\StripeClient;

class CmsController extends Controller
{
    // ABOUT PAGE CONTENT
    public function about(){
        try {
            // Fetch data for each section using a single query to minimize database calls
            $dataTypes = ['mission', 'vision', 'values', 'strategy', 'operate', 'bestData'];
            $data = [];
    
            foreach ($dataTypes as $type) {
                // Fetch each section by type and store it in the data array
                $data[$type] = HomeCms::select('title','description','image')->where('type', $type)->first();
            }
    
            // Return a successful response with the fetched data
            return response()->json(['status' => true, 'data' => $data], 200); // Use 200 for a successful response
        } catch (\Throwable $th) {
            // Return a JSON response with error details if an exception occurs
            return response()->json(['status' => false, 'error' => $th->getMessage()], 500);
        }
    }

    // HOME PAGE SECTIONS
    public function banners(){
        try {
            $banner = HomeCms::select('title','description','image','button_text','button_link')->where('status',1)->where('type','banner')->get();
            return response()->json(['status'=> true,'data' => $banner], 201);
        } catch (\Throwable $th) {
            return response()->json(['status'=> false,'error' => $th->getMessage()], 500);
        }
    }

    public function popularExamData(){
        try {
            $examData = HomeCms::select('title','button_text','button_link')->where('status',1)->where('type','exam')->first();
            return response()->json(['status'=> true,'data' => $examData], 201);
        } catch (\Throwable $th) {
            return response()->json(['status'=> false,'error' => $th->getMessage()], 500);
        }
    }

    public function helpData() {
        try {
            $helpData = HomeCms::select('title', 'extra')->where('status', 1)->where('type', 'help')->first();
    
            if (!$helpData) {
                return response()->json(['status' => false, 'error' => 'No data found.'], 404);
            }
    
            $data['title'] = $helpData->title;
            $data['data'] = isset($helpData->extra) ? json_decode($helpData->extra, true) : null;
    
            return response()->json(['status' => true, 'data' => $data], 200);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'error' => $th->getMessage()], 500);
        }
    }

    public function whyusData() {
        try {
            $whyus = HomeCms::select('title', 'extra')->where('status', 1)->where('type', 'whyus')->first();
    
            if (!$whyus) {
                return response()->json(['status' => false, 'error' => 'No data found.'], 404);
            }
    
            $data['title'] = $whyus->title;
            $data['data'] = isset($whyus->extra) ? json_decode($whyus->extra, true) : null;
    
            return response()->json(['status' => true, 'data' => $data], 200);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'error' => $th->getMessage()], 500);
        }
    }

    public function faqData(){
        try {
            $faqData = HomeCms::select('title','button_text','button_link')->where('status',1)->where('type','faq')->first();
            return response()->json(['status'=> true,'data' => $faqData], 201);
        } catch (\Throwable $th) {
            return response()->json(['status'=> false,'error' => $th->getMessage()], 500);
        }
    }

    public function resourceData(){
        try {
            $resourceData = HomeCms::select('title','button_text','button_link')->where('status',1)->where('type','resource')->first();
            return response()->json(['status'=> true,'data' => $resourceData], 201);
        } catch (\Throwable $th) {
            return response()->json(['status'=> false,'error' => $th->getMessage()], 500);
        }
    }

    public function getStarted(){
        try {
            $getStarted = HomeCms::select('title','description','button_text','button_link')->where('status',1)->where('type','started')->first();
            return response()->json(['status'=> true,'data' => $getStarted], 201);
        } catch (\Throwable $th) {
            return response()->json(['status'=> false,'error' => $th->getMessage()], 500);
        }
    }

    // HOME PAGE SECTION ENDS
    public function siteSetting(){
        try {
            $siteSetting = GeneralSetting::select('site_logo', 'light_site_logo','favicon', 'site_name', 'tag_line', 'description','maintenance_mode', 'debug_mode', 'default_payment', 'currency', 'currency_symbol', 'symbol_position', 'copyright', 'address', 'number', 'email', 'facebook', 'instagram', 'linkedin', 'youtube', 'twitter')->first();
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

    public function coursePackage($id)
    {
        try {
            // Assuming you have a CoursePackage model representing the table structure
            $coursePackage = Plan::where('category_id', $id)->where('status', 1)->select('id','name','price_type','duration','price','discount','feature_access','features','popular')->get();
            return response()->json(['status' => true, 'data' => $coursePackage], 201);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'error' => $th->getMessage()], 500);
        }
    }

    public function popularExams(){
        try {
            $popularExams = Exam::select('img_url','title','description','price','is_free','slug')->where(['favourite'=>1,'status'=>1])->latest()->take(3)->get();
            // WHERE IS_FREE IS 0 THEN SHOW PRICE
            return response()->json(['status'=> true,'data' => $popularExams], 201);
        } catch (\Throwable $th) {
            return response()->json(['status'=> false,'error' => $th->getMessage()], 500);
        }
    }

    public function exams(){
        try {
            $exams = Exam::select(
                'exams.img_url', 
                'exams.title', 
                'exams.description', 
                'exams.price', 
                'exams.is_free', 
                'exams.slug', 
                'exams.exam_duration'
            )
            ->leftJoin('exam_questions', 'exams.id', '=', 'exam_questions.exam_id') // Join with exam_questions
            ->leftJoin('questions', 'exam_questions.question_id', '=', 'questions.id') // Join with questions
            ->selectRaw('COUNT(questions.id) as questions_count') // Count of questions
            ->selectRaw('SUM(CAST(questions.default_marks AS DECIMAL)) as total_marks') // Sum of default_marks
            ->where(['exams.favourite' => 1, 'exams.status' => 1])
            ->groupBy('exams.id', 'exams.img_url', 'exams.title', 'exams.description', 'exams.price', 'exams.is_free', 'exams.slug', 'exams.exam_duration')
            ->orderBy('exams.created_at', 'desc') // Order by exam created_at
            ->get();
        
            return response()->json(['status'=> true,'data' => $exams], 201);
        } catch (\Throwable $th) {
            return response()->json(['status'=> false,'error' => $th->getMessage()], 500);
        }
    }

    public function examDetail($slug){
        try {
            $exam = Exam::select(
                'exams.img_url', 
                'exams.title', 
                'exams.description', 
                'exams.price', 
                'exams.is_free', 
                'exams.slug', 
                'exams.exam_duration'
            )
            ->leftJoin('exam_questions', 'exams.id', '=', 'exam_questions.exam_id') // Join with exam_questions
            ->leftJoin('questions', 'exam_questions.question_id', '=', 'questions.id') // Join with questions
            ->selectRaw('COUNT(questions.id) as questions_count') // Count of questions
            ->selectRaw('SUM(CAST(questions.default_marks AS DECIMAL)) as total_marks') // Sum of default_marks
            ->where(['exams.favourite' => 1, 'exams.status' => 1])
            ->groupBy('exams.id', 'exams.img_url', 'exams.title', 'exams.description', 'exams.price', 'exams.is_free', 'exams.slug', 'exams.exam_duration')
            ->orderBy('exams.created_at', 'desc') // Order by exam created_at
            ->where(['exams.slug'=>$slug,'exams.status'=>1])->first();

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

            $relatedBlogs = Blog::with('category:id,name','user:id,name')->select('title','category_id','short_description','image','slug','created_at')->where('status', 1)->where('id', '!=', $blog->id)->where('category_id', $blog->category_id)->latest()->take(3)->get();

            return response()->json(['status' => true, 'data' => $blog,'related'=>$relatedBlogs], 200);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'error' => $th->getMessage()], 500);
        }
    }

    public function pages(){
        try {
            $pages = Pages::select('title','slug')->where('status',1)->get();
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

    public function pricing(Request $request) {
        try {
            // Retrieve the authenticated user from request attributes
            $user = $request->attributes->get('authenticatedUser');
            
            // Check if the user is authenticated
            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'User not authenticated',
                ], 401);
            }
    
            // Fetch the user from the database
            $user = User::findOrFail($user->id); // Automatically throws 404 if user not found
    
            // Fetch pricing plans with sub-category information
            $pricing = Plan::join('sub_categories', 'plans.category_id', '=', 'sub_categories.id') 
                ->select(
                    'plans.id',
                    'plans.name',
                    'plans.price_type',
                    'plans.duration',
                    'plans.price',
                    'plans.discount',
                    'plans.description',
                    'plans.sort_order',
                    'plans.feature_access',
                    'plans.features',
                    'plans.popular',
                    'sub_categories.name as category_name',
                    'plans.stripe_product_id',
                    'plans.stripe_price_id'
                )
                ->where('plans.status', 1) // Only fetch active plans
                ->get();
    
            // Prepare response data
            $data = [
                'pricing' => $pricing,
                'customer_id' => $user->stripe_customer_id ?? null
            ];
    
            // Return response with data
            return response()->json(['status' => true, 'data' => $data], 200);
    
        } catch (\Throwable $th) {
            // Handle exceptions and return error response
            return response()->json(['status' => false, 'error' => $th->getMessage()], 500);
        }
    }

    // public function createCheckoutSession(Request $request){
    //     try {
    //         // Retrieve the authenticated user from request attributes
    //         $user = $request->attributes->get('authenticatedUser');
        
    //         // Check if the user is authenticated
    //         if (!$user) {
    //             return response()->json([
    //                 'status' => false,
    //                 'message' => 'User not authenticated',
    //             ], 401);
    //         }
    
    //         // Fetch the user from the database
    //         $user = User::findOrFail($user->id); // Automatically throws 404 if user not found
    //         if(!$user->stripe_customer_id){
    //             // CREATE CUSTOMER 
    //             $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
    //             $stripeCustomer = $stripe->customers->create([
    //                 'email' => $request->email,
    //                 'name' => $request->full_name,
    //                 'phone' => $request->phone,
    //                 'metadata' => [
    //                     'user_id' => $user->id,
    //                 ],
    //             ]);

    //             // Update user with stripe_customer_id
    //             $user->update([
    //                 'stripe_customer_id' => $stripeCustomer->id, // Add stripe_customer_id to user
    //             ]);
    //         }
    //         $customerId = $user->stripe_customer_id;
    //         // NOW CANCEL ALL PREVIOUS SUBSCRIPTION OR MANGE ITS PREVIOUS TO UPGRADE IT TO NEW (WHICH IS BEST YOU CAN DO)
    //     } catch (\Throwable $th) {
    //         // Handle exceptions and return error response
    //         return response()->json(['status' => false, 'error' => $th->getMessage()], 500);
    //     }
    // }

    // public function createCheckoutSession(Request $request) {
    //     try {
    //         // Retrieve the authenticated user from request attributes
    //         $user = $request->attributes->get('authenticatedUser');

    //         // Check if the user is authenticated
    //         if (!$user) {
    //             return response()->json([
    //                 'status' => false,
    //                 'message' => 'User not authenticated',
    //             ], 401);
    //         }

    //         // Fetch the user from the database
    //         $user = User::findOrFail($user->id); // Automatically throws 404 if user not found
            
    //         $stripe = new StripeClient(env('STRIPE_SECRET'));
    //         $customerId = $user->stripe_customer_id;

    //         // Create a customer if it doesn't exist
    //         if (!$customerId) {
    //             $stripeCustomer = $stripe->customers->create([
    //                 'email' => $request->email,
    //                 'name' => $request->full_name,
    //                 'phone' => $request->phone,
    //                 'metadata' => [
    //                     'user_id' => $user->id,
    //                 ],
    //             ]);

    //             // Update user with stripe_customer_id
    //             $user->update([
    //                 'stripe_customer_id' => $stripeCustomer->id, // Add stripe_customer_id to user
    //             ]);

    //             $customerId = $stripeCustomer->id;
    //         }

    //         // Cancel previous subscriptions if they exist
    //         $subscriptions = $stripe->subscriptions->all(['customer' => $customerId]);

    //         foreach ($subscriptions->data as $subscription) {
    //             // Check if the subscription is active
    //             if ($subscription->status === 'active' || $subscription->status === 'trialing') {
    //                 // Cancel the subscription
    //                 $stripe->subscriptions->update($subscription->id, [
    //                     'cancel_at_period_end' => false, // Cancel immediately
    //                 ]);
    //             }
    //         }

    //         // Create the checkout session
    //         $session = $stripe->checkout->sessions->create([
    //             'payment_method_types' => ['card'],
    //             'mode' => 'subscription', // Change to 'payment' if you're handling one-time payments
    //             'customer' => $customerId,
    //             'line_items' => [[
    //                 'price' => $request->priceId, // Ensure priceId is passed in the request
    //                 'quantity' => 1,
    //             ]],
    //             'success_url' => env('NEXT_PUBLIC_API_URL') . '/success?session_id={CHECKOUT_SESSION_ID}',
    //             'cancel_url' => env('NEXT_PUBLIC_API_URL') . '/cancel',
    //         ]);

    //         return response()->json(['status' => true, 'sessionId' => $session->id], 200);
    //     } catch (\Throwable $th) {
    //         // Handle exceptions and return error response
    //         return response()->json(['status' => false, 'error' => $th->getMessage()], 500);
    //     }
    // }

    public function createCheckoutSession(Request $request) {
        try {
            // Retrieve the authenticated user from request attributes
            $user = $request->attributes->get('authenticatedUser');

            // Check if the user is authenticated
            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'User not authenticated',
                ], 401);
            }

            // Fetch the user from the database
            $user = User::findOrFail($user->id); // Automatically throws 404 if user not found
            
            $stripe = new StripeClient(env('STRIPE_SECRET'));
            $customerId = $user->stripe_customer_id;

            // Create a customer if it doesn't exist
            if (!$customerId) {
                $stripeCustomer = $stripe->customers->create([
                    'email' => $request->email,
                    'name' => $request->full_name,
                    'phone' => $request->phone,
                    'metadata' => [
                        'user_id' => $user->id,
                    ],
                ]);

                // Update user with stripe_customer_id
                $user->update([
                    'stripe_customer_id' => $stripeCustomer->id, // Add stripe_customer_id to user
                ]);

                $customerId = $stripeCustomer->id;
            }

            // Cancel previous subscriptions if they exist
            $subscriptions = $stripe->subscriptions->all(['customer' => $customerId]);

            foreach ($subscriptions->data as $subscription) {
                // Check if the subscription is active
                if ($subscription->status === 'active' || $subscription->status === 'trialing') {
                    // Cancel the subscription
                    $stripe->subscriptions->update($subscription->id, [
                        'cancel_at_period_end' => false, // Cancel immediately
                    ]);
                }
            }

            // Create the checkout session
            $session = $stripe->checkout->sessions->create([
                'payment_method_types' => ['card'],
                'mode' => 'subscription', // Change to 'payment' if you're handling one-time payments
                'customer' => $customerId,
                'line_items' => [[
                    'price' => $request->priceId, // Ensure priceId is passed in the request
                    'quantity' => 1,
                ]],
                'success_url' => env('FRONTEND_URL') . '/success?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => env('FRONTEND_URL') . '/cancel',
            ]);

            return response()->json(['status' => true, 'sessionId' => $session->id], 200);
        } catch (\Throwable $th) {
            // Handle exceptions and return error response
            return response()->json(['status' => false, 'error' => $th->getMessage()], 500);
        }
    }

    

}
