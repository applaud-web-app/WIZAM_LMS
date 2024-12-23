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
use App\Models\Quizze;
use App\Models\PracticeSet;
use App\Models\Lesson;
use App\Models\Video;
use App\Models\GeneralSetting;
use App\Models\Enquiry;
use App\Models\Plan;
use App\Models\HomeCms;
use App\Models\ExamType;
use App\Models\User;
use Stripe\StripeClient;
use Illuminate\Support\Facades\Log;
use App\Models\Payment;
use App\Models\Subscription;
use App\Models\SubscriptionItem;
use Illuminate\Support\Facades\DB;
use App\Mail\EnquiryMail;
use Illuminate\Support\Facades\Mail;

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

    public function aboutPage(){
        try {
            $about = HomeCms::select('description')->where('type','aboutPage')->first();
            // Return a successful response with the fetched data
            return response()->json(['status' => true, 'data' => $about], 200); // Use 200 for a successful response
        } catch (\Throwable $th) {
            // Return a JSON response with error details if an exception occurs
            return response()->json(['status' => false, 'error' => $th->getMessage()], 500);
        }
    }

    // HOME PAGE SECTIONS
    public function banners(){
        try {
            $banner = HomeCms::select('title','description','button_text','button_link')->where('status',1)->where('type','banner')->get();
            $youtube = HomeCms::select('description')->where('status',1)->where('type','youtube')->get();
            $data = [
                'banner'=>$banner,
                'youtube'=>$youtube,
            ];
            return response()->json(['status'=> true,'data' => $data], 201);
        } catch (\Throwable $th) {
            return response()->json(['status'=> false,'error' => $th->getMessage()], 500);
        }
    }

    // HOME Youtube Video
    public function youtube(){
        try {
            $youtube = HomeCms::select('description')->where('status',1)->where('type','youtube')->get();
            return response()->json(['status'=> true,'data' => $youtube], 201);
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
            $verification = HomeCms::select('title', 'image')->where('status', 1)->where('type', 'verified')->first();
            if (!$helpData) {
                return response()->json(['status' => false, 'error' => 'No data found.'], 404);
            }
    
            $data['title'] = $helpData->title;
            $data['data'] = isset($helpData->extra) ? json_decode($helpData->extra, true) : null;
    
            return response()->json(['status' => true, 'data' => $data,'verification'=>$verification], 200);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'error' => $th->getMessage()], 500);
        }
    }

    public function whyusData() {
        try {
            $whyus = HomeCms::select('title','extra','status')->where('type', 'whyus')->first();
    
            if (!$whyus) {
                return response()->json(['status' => false, 'error' => 'No data found.'], 404);
            }
    
            $data['title'] = $whyus->title;
            $data['data'] = isset($whyus->extra) ? json_decode($whyus->extra, true) : null;
            $data['status'] = $whyus->status;
    
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
            $coursePackage = Exam::where('subcategory_id', $id)->where('status',1)->where('is_public',1)->select('title','slug')->get();
            return response()->json(['status' => true, 'data' => $coursePackage], 201);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'error' => $th->getMessage()], 500);
        }
    }

    public function popularExams()
    {
        try {
            $popularExams = Exam::select(
                    'exams.img_url',
                    'exams.title',
                    'exams.description',
                    'exams.price',
                    'exams.is_free',
                    'exams.slug',
                    // 'exam_schedules.schedule_type',
                    // 'exam_schedules.start_date',
                    // 'exam_schedules.start_time',
                    // 'exam_schedules.end_date',
                    // 'exam_schedules.end_time',
                    // 'exam_schedules.grace_period'
                )
                // ->join('exam_schedules', 'exams.id', '=', 'exam_schedules.exam_id')
                ->where([
                    'exams.favourite' => 1,
                    'exams.status' => 1,
                    'exams.is_public' => 1
                ])
                // ->where('exam_schedules.status', 1)
                ->groupBy('exams.id', 'exams.img_url', 'exams.title', 'exams.description', 'exams.price', 'exams.is_free', 'exams.slug')
                // 'exam_schedules.schedule_type',
                // 'exam_schedules.start_date',
                // 'exam_schedules.start_time',
                // 'exam_schedules.end_date',
                // 'exam_schedules.end_time',
                // 'exam_schedules.grace_period'
                ->orderBy('exams.created_at','DESC')
                ->take(3)
                ->get()
                ->map(function ($exam) {
                    // If 'is_free' is 0, show the price; otherwise, set price to null.
                    $exam->price = $exam->is_free ? null : $exam->price;
                    return $exam;
                });

            return response()->json(['status' => true, 'data' => $popularExams], 201);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'error' => $th->getMessage()], 500);
        }
    }

    public function courseExamType(){
        try {
            $examType = ExamType::select('name','id')->where('status',1)->get();
            return response()->json(['status'=> true,'data' => $examType], 201);
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
                'exams.exam_duration',
                'exams.exam_type_id',
                'exams.subcategory_id',
                // 'exam_schedules.schedule_type',
                // 'exam_schedules.start_date',
                // 'exam_schedules.start_time',
                // 'exam_schedules.end_date',
                // 'exam_schedules.end_time',
                // 'exam_schedules.grace_period'
            )
            // ->join('exam_schedules', 'exams.id', '=', 'exam_schedules.exam_id')
            ->leftJoin('exam_questions', 'exams.id', '=', 'exam_questions.exam_id') // Join with exam_questions
            ->leftJoin('questions', 'exam_questions.question_id', '=', 'questions.id') // Join with questions
            ->selectRaw('COUNT(questions.id) as questions_count') // Count of questions
            ->selectRaw('SUM(CAST(questions.default_marks AS DECIMAL)) as total_marks') // Sum of default_marks
            ->where(['exams.status' => 1,'exams.is_public' => 1])
            // ->where('exam_schedules.status', 1)
            ->groupBy('exams.id', 'exams.img_url', 'exams.title', 'exams.description', 'exams.price', 'exams.is_free', 'exams.slug', 'exams.exam_duration','exams.exam_type_id','exams.subcategory_id',)
            // 'exam_schedules.schedule_type',
            // 'exam_schedules.start_date',
            // 'exam_schedules.start_time',
            // 'exam_schedules.end_date',
            // 'exam_schedules.end_time',
            // 'exam_schedules.grace_period'
            ->orderBy('exams.created_at', 'desc') 
            // ->havingRaw('COUNT(exam_schedules.id) > 0')
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
                'exams.exam_duration',
                'exams.subcategory_id',
                'sub_categories.name',
                // 'exam_schedules.id as schedule_id',
                // 'exam_schedules.schedule_type',
                // 'exam_schedules.start_date',
                // 'exam_schedules.start_time',
                // 'exam_schedules.end_date',
                // 'exam_schedules.end_time',
                // 'exam_schedules.grace_period'
            )
            // ->join('exam_schedules', 'exams.id', '=', 'exam_schedules.exam_id')
            ->leftJoin('sub_categories', 'exams.subcategory_id', '=', 'sub_categories.id')
            ->leftJoin('exam_questions', 'exams.id', '=', 'exam_questions.exam_id') // Join with exam_questions
            ->leftJoin('questions', 'exam_questions.question_id', '=', 'questions.id') // Join with questions
            ->selectRaw('COUNT(questions.id) as questions_count') // Count of questions
            ->selectRaw('SUM(CAST(questions.default_marks AS DECIMAL)) as total_marks') // Sum of default_marks
            ->where(['exams.status' => 1])
            ->groupBy('exams.id', 'exams.img_url', 'exams.title', 'exams.description', 'exams.price', 'exams.is_free', 'exams.slug', 'exams.exam_duration',  'exams.subcategory_id', 'sub_categories.name')
                // 'exam_schedules.schedule_type',
                // 'exam_schedules.id',
                // 'exam_schedules.start_date',
                // 'exam_schedules.start_time',
                // 'exam_schedules.end_date',
                // 'exam_schedules.end_time',
                // 'exam_schedules.grace_period'
            ->orderBy('exams.created_at', 'desc') // Order by exam created_at
            // ->where('exam_schedules.status', 1)
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

            $recentBlogs = Blog::with('category:id,name')->select('title','image','slug','created_at')->where('status', 1)->latest()->take(10)->get();

            $archiveData = Blog::selectRaw('YEAR(created_at) as year, COUNT(*) as count')->where('status',1)
            ->groupBy('year')
            ->get();

            return response()->json(['status'=> true,'data' => $resources,'recent'=>$recentBlogs,'archive'=>$archiveData], 201);
        } catch (\Throwable $th) {
            return response()->json(['status'=> false,'error' => $th->getMessage()], 500);
        }
    }

    public function resourceArchive($year){
        try {
            $blogsForYear = Blog::whereYear('created_at', $year)->where('status',1)->get();
            $recentBlogs = Blog::with('category:id,name')->select('title','image','slug','created_at')->where('status', 1)->latest()->take(10)->get();

            $archiveData = Blog::selectRaw('YEAR(created_at) as year, COUNT(*) as count')->where('status',1)
            ->groupBy('year')
            ->get();

            return response()->json(['status' => true, 'data'=>$blogsForYear,'recent'=>$recentBlogs,'archive'=>$archiveData], 200);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'error' => $th->getMessage()], 500);
        }
    }

    public function resourceDetail($slug){
        try {
            // Fetch the blog with its category and any other necessary relationships
            $blogImage = HomeCms::select('image')->where('type', 'resource')->first();
            $blog = Blog::with('category:id,name')->select('id','category_id','user','title', 'content', 'short_description', 'image', 'slug', 'created_at','content')->where(['slug'=>$slug,'status'=>1])->first();
            // Check if the blog exists
            if (!$blog) {
                return response()->json(['status' => false, 'error' => 'Blog not found'], 404);
            }

            // Add the blog image to the $blog data
            $blog->blog_image = $blogImage->image ?? null;
            $relatedBlogs = Blog::with('category:id,name')->select('title','category_id','short_description','image','slug','created_at')->where('status', 1)->where('id', '!=', $blog->id)->where('category_id', $blog->category_id)->latest()->take(3)->get();

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
            'course' => 'nullable', // Ensure that 'course' exists in 'subcategory' table
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
            $enquiry = Enquiry::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'study_mode' => $request->study_mode,
                'course' => $request->course,
                'hear_by' => $request->hear_by,
                'message' => $request->message,
                'accept_condition' => $request->accept_condition,
                'contact_me' => $request->contact_me, 
            ]);

                // Send the email with the enquiry data
                Mail::to('tdevansh099@gmail.com') // The email address to send the enquiry to
                ->send(new EnquiryMail($enquiry)); // Send the email with the mailable
            
    
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

    // public function pricing(Request $request) {
    //     try {
    //         // Retrieve the authenticated user from request attributes
    //         $user = $request->attributes->get('authenticatedUser');
    
    //         // Fetch the user from the database
    //         $userID = null;
    //         if($user){
    //             $user = User::findOrFail($user->id); // Automatically throws 404 if user not found
    //             $userID = $user->stripe_customer_id;
    //         }
    
    //         // Fetch pricing plans with sub-category information
    //         $pricing = Plan::join('sub_categories', 'plans.category_id', '=', 'sub_categories.id') 
    //             ->select(
    //                 'plans.id',
    //                 'plans.name',
    //                 'plans.price_type',
    //                 'plans.duration',
    //                 'plans.price',
    //                 'plans.discount',
    //                 'plans.description',
    //                 'plans.sort_order',
    //                 'plans.exams',
    //                 'plans.quizzes',
    //                 'plans.practices',
    //                 'plans.videos',
    //                 'plans.lessons',
    //                 'plans.popular',
    //                 'sub_categories.name as category_name',
    //                 'plans.stripe_product_id',
    //                 'plans.stripe_price_id'
    //             )
    //             ->where('plans.status', 1) // Only fetch active plans
    //             ->get();
    
    //         // Prepare response data
    //         $data = [
    //             'pricing' => $pricing,
    //             'customer_id' => $userID ?? null
    //         ];
    
    //         // Return response with data
    //         return response()->json(['status' => true, 'data' => $data], 200);
    
    //     } catch (\Throwable $th) {
    //         // Handle exceptions and return error response
    //         return response()->json(['status' => false, 'error' => $th->getMessage()], 500);
    //     }
    // }

    public function pricing(Request $request){
        try {
            
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
                'plans.exams',
                'plans.quizzes',
                'plans.practices',
                'plans.videos',
                'plans.lessons',
                'plans.popular',
                'sub_categories.name as category_name',
                'plans.stripe_product_id',
                'plans.stripe_price_id'
            )
            ->where('plans.status', 1) // Only fetch active plans
            ->get();

            // Map each plan to fetch exam, quiz, lesson, and practice names
            $pricing->map(function ($plan) {
                // Fetch Exam Names
                $examIds = json_decode($plan->exams); // Assuming JSON array like ["3", "4", "5"]
                $plan->exam_names = $examIds ? Exam::whereIn('id', $examIds)->pluck('title')->toArray() : [];

                // Fetch Quiz Names
                $quizIds = json_decode($plan->quizzes); // Assuming JSON array like ["1", "2"]
                $plan->quiz_names = $quizIds ? Quizze::whereIn('id', $quizIds)->pluck('title')->toArray() : [];

                // Fetch Lesson Names
                $lessonIds = json_decode($plan->lessons); // Assuming JSON array like ["8", "9"]
                $plan->lesson_names = $lessonIds ? Lesson::whereIn('id', $lessonIds)->pluck('title')->toArray() : [];

                // Fetch Practice Names
                $practiceIds = json_decode($plan->practices); // Assuming JSON array like ["10", "11"]
                $plan->practice_names = $practiceIds ? PracticeSet::whereIn('id', $practiceIds)->pluck('title')->toArray() : [];

                return $plan;
            });

            // Prepare response data
            $data = [
                'pricing' => $pricing,
                'customer_id' => $userID ?? null
            ];
    
            // Return response with data
            return response()->json(['status' => true, 'data' => $data], 200);
        } catch (\Throwable $th) {
            // Handle exceptions and return error response
            return response()->json(['status' => false, 'error' => $th->getMessage()], 500);
        }
    }

    public function popularPricing(){
        try {
            
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
                'plans.exams',
                'plans.quizzes',
                'plans.practices',
                'plans.videos',
                'plans.lessons',
                'plans.popular',
                'sub_categories.name as category_name',
                'plans.stripe_product_id',
                'plans.stripe_price_id'
            )
            ->where('plans.status', 1) // Only fetch active plans
            ->where('plans.popular', 1) // Only fetch active plans
            ->get();

            // Map each plan to fetch exam, quiz, lesson, and practice names
            $pricing->map(function ($plan) {
                // Fetch Exam Names
                $examIds = json_decode($plan->exams); // Assuming JSON array like ["3", "4", "5"]
                $plan->exam_names = $examIds ? Exam::whereIn('id', $examIds)->pluck('title')->toArray() : [];

                // Fetch Quiz Names
                $quizIds = json_decode($plan->quizzes); // Assuming JSON array like ["1", "2"]
                $plan->quiz_names = $quizIds ? Quizze::whereIn('id', $quizIds)->pluck('title')->toArray() : [];

                // Fetch Lesson Names
                $lessonIds = json_decode($plan->lessons); // Assuming JSON array like ["8", "9"]
                $plan->lesson_names = $lessonIds ? Lesson::whereIn('id', $lessonIds)->pluck('title')->toArray() : [];

                // Fetch Practice Names
                $practiceIds = json_decode($plan->practices); // Assuming JSON array like ["10", "11"]
                $plan->practice_names = $practiceIds ? PracticeSet::whereIn('id', $practiceIds)->pluck('title')->toArray() : [];

                return $plan;
            });

            // Prepare response data
            $data = [
                'pricing' => $pricing,
                'customer_id' => $userID ?? null
            ];
    
            // Return response with data
            return response()->json(['status' => true, 'data' => $data], 200);
        } catch (\Throwable $th) {
            // Handle exceptions and return error response
            return response()->json(['status' => false, 'error' => $th->getMessage()], 500);
        }
    }

    // WORKING
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
    //                 'email' => $user->email,
    //                 'name' => $user->name,
    //                 'metadata' => [
    //                     'user_id' => $user->id,
    //                 ],
    //             ]);
    
    //             // Update user with stripe_customer_id
    //             $user->update([
    //                 'stripe_customer_id' => $stripeCustomer->id,
    //             ]);
    
    //             $customerId = $stripeCustomer->id;
    //         }
    
    //         // Only cancel previous subscriptions if the priceType is 'monthly'
    //         if ($request->priceType === 'monthly') {
    //             $subscriptions = $stripe->subscriptions->all(['customer' => $customerId]);
    
    //             foreach ($subscriptions->data as $subscription) {
    //                 if ($subscription->status === 'active' || $subscription->status === 'trialing') {
    //                     // Cancel the subscription immediately
    //                     $stripe->subscriptions->cancel($subscription->id, [
    //                         'invoice_now' => true,
    //                         'prorate' => true,
    //                     ]);
    //                     // Log cancellation
    //                     \Log::info('Canceled subscription: ' . $subscription->id);

    //                     // UPDATE SUBSBCRIPTION STATUS 
    //                     $subscrption = Subscription::where('stripe_id', $subscription->id)->first();
    //                     if ($subscrption) {
    //                         $subscrption->stripe_status = "canceled";
    //                         $subscrption->save();
    //                     }
    //                 }
    //             }
    //         }
    
    //         // Determine payment mode based on priceType
    //         $paymentMode = $request->priceType === 'fixed' ? 'payment' : 'subscription';
    
    //         // Create the checkout session
    //         $session = $stripe->checkout->sessions->create([
    //             'payment_method_types' => ['card'],
    //             'mode' => $paymentMode, // 'payment' for one-time, 'subscription' for recurring
    //             'customer' => $customerId,
    //             'line_items' => [[
    //                 'price' => $request->priceId, // Ensure priceId is passed in the request
    //                 'quantity' => 1,
    //             ]],
    //             'metadata' => [
    //                 'plan_id' => $request->priceId, // Attach plan_id to the session metadata
    //             ],
    //             'success_url' => env('FRONTEND_URL')."/".$request->successUrl.'?session_id={CHECKOUT_SESSION_ID}',
    //             'cancel_url' => env('FRONTEND_URL') . '/failure',
    //         ]);
    
    //         return response()->json(['status' => true, 'sessionId' => $session->id], 200);
    //     } catch (\Throwable $th) {
    //         // Handle exceptions and return error response
    //         \Log::error('Error in createCheckoutSession: ' . $th->getMessage());
    //         return response()->json(['status' => false, 'error' => $th->getMessage()], 500);
    //     }
    // }

    public function createCheckoutSession(Request $request)
    {
        try {
            $request->validate([
                'priceId'=>'required',
                'priceType'=>'required'
            ]);

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
            $user = User::findOrFail($user->id);
             
            \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

            $stripe = new StripeClient(env('STRIPE_SECRET'));
            $customerId = $user->stripe_customer_id;

            // Create a customer if it doesn't exist
            if (!$customerId) {
                $stripeCustomer = $stripe->customers->create([
                    'email' => $user->email,
                    'name' => $user->name,
                    // 'test_clock'=> 'clock_1QPKkVSALL6oCDIiIP87QbtP',
                    'metadata' => [
                        'user_id' => $user->id,
                    ],
                ]);

                // Update user with stripe_customer_id
                $user->update([
                    'stripe_customer_id' => $stripeCustomer->id,
                ]);

                $customerId = $stripeCustomer->id;
            }

            // Determine the payment mode based on the price type
            $plan = Plan::where('stripe_price_id',$request->priceId)->first();
            if(!$plan) {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid Plan',
                ], 404);
            }

            $paymentMode = $plan->price_type === 'monthly' ? 'subscription' : 'payment';
            $cancelDate = now()->addMonths($plan->duration)->timestamp;

            $generalSetting = GeneralSetting::select('currency')->first();
            $currency = 'usd';
            if($generalSetting && isset($generalSetting->currency)){
                $currency = $generalSetting->currency;
            }
            $lineItem = [
                'price_data' => [
                    'currency' => $currency,
                    'product_data' => [
                        'name' => $plan->name,
                        'description' => $plan->description,
                    ],
                    'unit_amount' => $plan->price * 100, // Convert to cents
                ],
                'quantity' => 1,
            ];
            
            // Include `recurring` only for monthly plans
            if ($plan->price_type === 'monthly') {
                $lineItem['price_data']['recurring'] = [
                    'interval' => 'month',
                    'interval_count' => 1, // EACH MONTH FOR MONTHLY
                ];
            }

            // Create the checkout session
            $sessionData = [
                'payment_method_types' => ['card'],
                'customer' => $user->stripe_customer_id,
                'line_items' => [$lineItem],
                'mode' => $paymentMode,
                'success_url' => env('FRONTEND_URL')."/".$request->successUrl.'?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => env('FRONTEND_URL') . '/failure',
                'metadata' => [
                    'plan_id' => $plan->id,
                    'user_id' => $user->id,
                    'price_type' => $plan->price_type,
                    'duration' => $plan->duration,
                ],
            ];

            $session = $stripe->checkout->sessions->create($sessionData);
            return response()->json(['status' => true, 'sessionId' => $session->id], 200);
        } catch (\Throwable $th) {
            // Handle exceptions and return error response
            \Log::error('Error in createCheckoutSession: ' . $th->getMessage());
            return response()->json(['status' => false, 'error' => $th->getMessage()], 500);
        }
    }

    // private function storeSubscriptionDetails($userId, $planId, $sessionId, $priceType)
    // {
    //     // Fetch the plan from the database
    //     $plan = Plan::findOrFail($planId);

    //     // Calculate start and end dates
    //     $startDate = now();
    //     $endDate = $priceType === 'fixed' ? $startDate->addDays($plan->duration) : $startDate->addMonth();

    //     // Create subscription record
    //     $subscription = Subscription::create([
    //         'user_id' => $userId,
    //         'plan_id' => $planId,
    //         'type' => $priceType,
    //         'stripe_subscription_id' => $sessionId, // Save the session ID as a placeholder
    //         'start_date' => $startDate,
    //         'end_date' => $endDate,
    //         'status' => 'pending', // Update to 'active' after successful payment
    //     ]);

    //     // Assign subscription items if applicable
    //     $this->assignSubscriptionItems($subscription->id, $plan);
    // }

    // private function assignSubscriptionItems($subscriptionId, $plan)
    // {
    //     $types = ['exams', 'quizzes', 'practice_sets', 'videos', 'lessons'];

    //     foreach ($types as $type) {
    //         $itemIds = json_decode($plan->$type); // Decode the JSON array

    //         if ($itemIds && is_array($itemIds)) {
    //             foreach ($itemIds as $itemId) {
    //                 SubscriptionItem::create([
    //                     'subscription_id' => $subscriptionId,
    //                     'item_type' => rtrim($type, 's'), // Convert plural to singular
    //                     'item_id' => $itemId,
    //                     'assigned_at' => now(),
    //                     'expires_at' => now()->addDays($plan->duration),
    //                     'status' => 'pending', // Update to 'active' after successful payment
    //                 ]);
    //             }
    //         }
    //     }
    // }

    public function cancelSubscription(Request $request)
    {
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
    
            // Ensure the customer ID exists before proceeding
            if (!$customerId) {
                return response()->json([
                    'status' => false,
                    'message' => 'No Stripe customer ID found. Cannot cancel subscription.',
                ], 404);
            }
    
            // Retrieve all subscriptions for the customer
            $subscriptions = $stripe->subscriptions->all(['customer' => $customerId]);
    
            foreach ($subscriptions->data as $subscription) {
                if ($subscription->status === 'active' || $subscription->status === 'trialing') {
                    // Cancel the subscription immediately
                    $stripe->subscriptions->cancel($subscription->id, [
                        'invoice_now' => true,
                        'prorate' => true,
                    ]);
    
                    // Log cancellation
                    \Log::info('Canceled subscription: ' . $subscription->id);
    
                    // Update the subscription status in the database
                    $subscrption = Subscription::where('stripe_id', $subscription->id)->first();
                    if ($subscrption) {
                        $subscrption->stripe_status = "canceled";
                        $subscrption->save();
                    }
                }
            }
    
            return response()->json([
                'status' => true,
                'message' => 'Subscription(s) canceled successfully.',
            ], 200);
            
        } catch (\Throwable $th) {
            // Log the error
            \Log::error('Error canceling subscription: ' . $th->getMessage());
    
            // Return a JSON response with error details
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while canceling the subscription.',
                'error' => $th->getMessage(), // For debugging purposes (consider removing in production)
            ], 500);
        }
    }


    public function contactForm()
    {
        try {
            // Fetch contact page data
            $contactPage = HomeCms::where('type', 'contactPage')->first();
            $response = [];
    
            if ($contactPage) {
                $extraData = json_decode($contactPage->extra, true) ?? [];
    
                $response = [
                    'title' => $contactPage->title ?? '',
                    'contact_details' => [
                        'address' => $contactPage->description ?? '',
                        'phone' => $extraData['phone'] ?? '',
                        'email' => $extraData['email'] ?? '',
                    ],
                    'direction_data' => [
                        'direction_title' => $contactPage->button_text ?? '',
                        'directions' => $extraData['directions'] ?? [],
                    ],
                ];
            } else {
                // Handle missing contact page data
                $response = [
                    'title' => '',
                    'contact_details' => [
                        'address' => '',
                        'phone' => '',
                        'email' => '',
                    ],
                    'direction_data' => [
                        'direction_title' => '',
                        'directions' => [],
                    ],
                ];
            }
    
            // Fetch enquiry form data
            $enquiryForm = HomeCms::where('type', 'enquiryForm')->first();
            $formData = $enquiryForm ? json_decode($enquiryForm->extra, true) : null;
    
            // Ensure form data is an array or null
            $formData = is_array($formData) ? $formData : null;
    
            // Combine both sets of data into a response
            $data = [
                'contact' => $response,
                'form' => $formData,
            ];
    
            // Return a successful response with the fetched data
            return response()->json(['status' => true, 'data' => $data], 200);
        } catch (\Throwable $th) {
            // Log the exception (if a logging system is in place)
            \Log::error('Error in contactForm: ' . $th->getMessage());
    
            // Return a JSON response with error details
            return response()->json(['status' => false, 'error' => $th->getMessage()], 500);
        }
    }

    public function siteSeo()
    {
        try {
            // Define the pages you want to fetch
            $pages = ['home', 'about', 'contact', 'exams', 'pricing', 'resources', 'faq'];
            
            // Fetch all SEO data for the given pages in a single query
            $seoData = HomeCms::whereIn('type', array_map(fn($page) => "{$page}_seo", $pages))->get()->keyBy('type');
            
            // Prepare the response data
            $seo = [];
            foreach ($pages as $page) {
                $type = "{$page}_seo";
                $data = $seoData[$type] ?? null;
                
                $seo[$page] = [
                    'title' => $data->title ?? '',
                    'description' => $data->description ?? '',
                    'keyword' => $data ? (json_decode($data->extra, true)['keyword'] ?? '') : '',
                    'image' => $data->image ?? ''
                ];
            }
            
            return response()->json(['status' => true, 'data' => $seo], 200);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'error' => $th->getMessage()], 500);
        }
    }

    public function pricingCategory($id){
        try {
            $pricing = Plan::select('name','price','id')->where('category_id',$id)->where('status',1)->get();
            return response()->json(['status' => true, 'data' => $pricing], 200); 
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'error' => $th->getMessage()], 500);
        }
    }

    public function filterExam($category,$plan){
        try {

            $plan = Plan::where('id',$plan)->where('category_id',$category)->first();
            if(!$plan){
                $exams = [];
                return response()->json(['status'=> true,'data' => $exams], 201);
            }
            $examIds = [];
            if($plan->exams != null){
                $examIds = is_array($plan->exams) ? $plan->exams : json_decode($plan->exams, true);
            }
            $exams = Exam::select(
                'exams.img_url', 
                'exams.title', 
                'exams.description', 
                'exams.price', 
                'exams.is_free', 
                'exams.slug', 
                'exams.exam_duration',
                'exams.exam_type_id',
                'exams.subcategory_id',
            )
            // ->join('exam_schedules', 'exams.id', '=', 'exam_schedules.exam_id')
            ->leftJoin('exam_questions', 'exams.id', '=', 'exam_questions.exam_id') // Join with exam_questions
            ->leftJoin('questions', 'exam_questions.question_id', '=', 'questions.id') // Join with questions
            ->selectRaw('COUNT(questions.id) as questions_count') // Count of questions
            ->selectRaw('SUM(CAST(questions.default_marks AS DECIMAL)) as total_marks') // Sum of default_marks
            ->where(['exams.status' => 1,'exams.is_public' => 1])
            ->whereIn('exams.id',$examIds)
            // ->where('exam_schedules.status', 1)
            ->groupBy('exams.id', 'exams.img_url', 'exams.title', 'exams.description', 'exams.price', 'exams.is_free', 'exams.slug', 'exams.exam_duration','exams.exam_type_id','exams.subcategory_id',)
            // 'exam_schedules.schedule_type',
            // 'exam_schedules.start_date',
            // 'exam_schedules.start_time',
            // 'exam_schedules.end_date',
            // 'exam_schedules.end_time',
            // 'exam_schedules.grace_period'
            ->orderBy('exams.created_at', 'desc') 
            // ->havingRaw('COUNT(exam_schedules.id) > 0')
            ->get();
            return response()->json(['status'=> true,'data' => $exams], 201);
        } catch (\Throwable $th) {
            return response()->json(['status'=> false,'error' => $th->getMessage()], 500);
        }
    }

}
