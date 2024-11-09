<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\Exam;
use App\Models\ExamResult;
use App\Models\User;
use Illuminate\Support\Str; 
use Carbon\Carbon;
use App\Models\Question;
use App\Models\Subscription;
use App\Models\Plan;
use App\Models\AssignedExam;
use App\Models\ExamType;

class ExamController extends Controller
{
    // public function playExam(Request $request, $slug)
    // {
    //     try {
    //         // Get the authenticated user
    //         $user = $request->attributes->get('authenticatedUser');

            
    //         // Validate incoming request data
    //         $request->validate([
    //             'category' => 'required|integer',
    //         ]);

    //         // Fetch the exam IDs assigned to the current user
    //         $assignedExams = AssignedExam::select('exam_id')->where('user_id', $user->id)->get()->pluck('exam_id')->toArray();
            
    //         // Fetch the exam along with related questions in one query
    //         $exam = Exam::with([
    //                 'examQuestions.questions' => function($query) {
    //                     $query->select('id', 'question', 'default_marks', 'watch_time', 'type', 'options', 'answer');
    //                 }
    //             ])
    //             ->select(
    //                 'exams.id',
    //                 'exams.title',
    //                 'exams.description',
    //                 'exams.pass_percentage',
    //                 'exams.slug',
    //                 'exams.subcategory_id',
    //                 'exams.status',
    //                 'exams.duration_type', // duration_type
    //                 'exams.point_mode', 
    //                 'exams.exam_duration', // exam_duration
    //                 'exams.point',
    //                 'exams.shuffle_questions',
    //                 'exams.question_view',
    //                 'exams.disable_finish_button',
    //                 'exams.negative_marking',
    //                 'exams.negative_marking_type',
    //                 'exams.negative_marks',
    //                 'exams.is_free',
    //                 DB::raw('SUM(questions.default_marks) as total_marks'),
    //                 DB::raw('SUM(COALESCE(questions.watch_time, 0)) as total_time')
    //             )
    //             ->leftJoin('exam_questions', 'exams.id', '=', 'exam_questions.exam_id')
    //             ->leftJoin('questions', 'exam_questions.question_id', '=', 'questions.id')
    //             ->where(function ($query) use ($assignedExams) {
    //                 $query->where('exams.is_public', 1) // Public exams
    //                     ->orWhereIn('exams.id', $assignedExams); // Private exams assigned to the user
    //             })
    //             ->where('exams.slug', $slug)
    //             ->where('exams.subcategory_id', $request->category)
    //             ->where('exams.status', 1)
    //             ->where('questions.status', 1)
    //             ->groupBy(
    //                 'exams.id', 'exams.title', 'exams.description', 'exams.pass_percentage',
    //                 'exams.slug', 'exams.subcategory_id', 'exams.status', 'exams.duration_type',
    //                 'exams.point_mode', 'exams.exam_duration', 'exams.point', 'exams.shuffle_questions',
    //                 'exams.question_view', 'exams.disable_finish_button', 'exams.negative_marking',
    //                 'exams.negative_marking_type', 'exams.negative_marks','exams.is_free'
    //             )
    //             ->first();
    
    //         // If exam not found
    //         if (!$exam) {
    //             return response()->json(['status' => false, 'error' => 'Exam not found'], 404);
    //         }

    //         // Adjust 'is_free' for assigned exams, regardless of public or private
    //         if (in_array($exam->id, $assignedExams)) {
    //             $exam->is_free = 1; // Make assigned exams free
    //         }

    //         // PAID EXAM
    //         if ($exam->is_free == 0) {
    //             $type = "exams";

    //             // Get the current date and time
    //             $currentDate = now();

    //             // Fetch the user's active subscription
    //             $subscription = Subscription::with('plans')->where('user_id', $user->id)->where('stripe_status', 'complete')->where('ends_at', '>', $currentDate)->latest()->first();

    //             // If no active subscription, return error
    //             if (!$subscription) {
    //                 return response()->json(['status' => false, 'error' => 'Please buy a subscription to access this course.'], 404);
    //             }
        
    //             // Fetch the plan related to this subscription
    //             $plan = $subscription->plans;
        
    //             if (!$plan) {
    //                 return response()->json(['status' => false, 'error' => 'No associated plan found for this subscription.'], 404);
    //             }
        
    //             // Check if the plan allows unlimited access
    //             if ($plan->feature_access == 1) {
    //                 // return response()->json(['status' => true, 'data' => $subscription], 200);
    //             } else {
    //                 // Fetch the allowed features for this plan
    //                 $allowed_features = json_decode($plan->features, true);
        
    //                 // Check if the requested feature type is in the allowed features
    //                 if (in_array($type, $allowed_features)) {
    //                     // return response()->json(['status' => true, 'data' => $subscription], 200);
    //                 } else {
    //                     return response()->json(['status' => false, 'error' => 'Feature not available in your plan. Please upgrade your subscription.'], 403);
    //                 }
    //             }
    //         }
    
    //         // Get the authenticated user
    //         $user = $request->attributes->get('authenticatedUser');
    
    //         // Fetch all completed exam results
    //         $checkOngoingResult = ExamResult::where('user_id', $user->id)
    //             ->where('exam_id', $exam->id)
    //             ->where('status', 'complete')
    //             ->get();
    
    //         // Restrict exam attempts based on the configured limit
    //         if ($exam->restrict_attempts == 1 && $exam->total_attempts <= $checkOngoingResult->count()) {
    //             return response()->json(['status' => false, 'error' => 'Maximum Attempt Reached'], 403);
    //         }
    
    //         // Check for ongoing exam
    //         $ongoingExam = ExamResult::where('user_id', $user->id)
    //             ->where('exam_id', $exam->id)
    //             ->where('status', 'ongoing') // Correct the status check
    //             ->latest('created_at')
    //             ->first();
    
    //         if ($ongoingExam) {
    //             // $remainingDuration = $ongoingExam->end_time->diffInMinutes(now());
    //             $remainingDuration = now()->diffInMinutes($ongoingExam->end_time); // Ensure no negative duration

    //             if ($ongoingExam->end_time->isPast()) {
    //                 // If time has passed, mark the exam as complete
    //                 $ongoingExam->update(['status' => 'complete']);
    //                 $data = [
    //                     'uuid'=>$ongoingExam->uuid,
    //                 ];
    //                 return response()->json(['status' => true, 'message' => 'Exam Timed Out','data'=>$data]);
    //             } else {
    //                 // Return ongoing exam details
    //                 return response()->json([
    //                     'status' => true,
    //                     'data' => [
    //                         'title' => $exam->title,
    //                         'uuid'=>$ongoingExam->uuid,
    //                         'questions' => json_decode($ongoingExam->questions),
    //                         'duration' => $remainingDuration . " mins",
    //                         'points' => $ongoingExam->point,
    //                         'question_view' => $exam->question_view == 1 ? "enable" : "disable",
    //                         'finish_button' => $exam->disable_finish_button == 1 ? "enable" : "disable"
    //                     ]
    //                 ], 200);
    //             }
    //         }
    
    //         // Calculate exam duration and points
    //         $duration = (int) ($exam->duration_mode == "manual" && $exam->duration > 0 ? $exam->duration : round($exam->total_time / 60, 2));
    //         $points = $exam->point_mode == "manual" ? $exam->point : $exam->total_marks;
    
    //         // Prepare structured response data for questions
    //         $questionsData = [];
    //         $correctAnswers = [];
    //         foreach ($exam->examQuestions as $examQuestion) {
    //             $question = $examQuestion->questions;
    //             $options = $question->options ? json_decode($question->options, true) : [];
    
    //             if ($question->type == "MTF" && !empty($question->answer)) {
    //                 $matchOption = json_decode($question->answer, true);
    //                 shuffle($matchOption);
    //                 $options = array_merge($options, $matchOption);
    //             }

    //             if ($question->type == "ORD") {
    //                 shuffle($options);
    //             }
    
    //             // Customize question display for different types
    //             $questionText = $question->question;
    //             if ($question->type == "FIB") {
    //                 $questionText = preg_replace('/##(.*?)##/', '<span class="border-b border-black inline-block w-[150px] text-center" style="width:150px;"></span>', $question->question);
    //                 $options = [json_decode($question->answer, true) ? count(json_decode($question->answer, true)) : 0];
    //             } elseif ($question->type == "EMQ") {
    //                 $questionText = json_decode($question->question, true);
    //             }
    
    //             $questionsData[] = [
    //                 'id' => $question->id,
    //                 'type' => $question->type,
    //                 'question' => $questionText,
    //                 'options' => $options
    //             ];

    //             // Add correct answer info
    //             $correctAnswers[] = [
    //                 'id' => $question->id,
    //                 'correct_answer' => $question->answer,  // Use answer field
    //                 'default_marks' => $exam->point_mode == "manual" ? $exam->point : $question->default_marks
    //             ];
    //         }
    
    //         // Shuffle questions if enabled
    //         if ($exam->shuffle_questions == 1) {
    //             shuffle($questionsData);
    //         }
    
    //         // Start exam result tracking
    //         $startTime = now();
    //         $endTime = $startTime->copy()->addMinutes($duration); 
    
    //         $examResult = ExamResult::create([
    //             'exam_id' => $exam->id,
    //             'uuid' => uniqid(), // Generate unique identifier
    //             'subcategory_id' => $exam->subcategory_id,
    //             'user_id' => $user->id,
    //             'questions' => json_encode($questionsData,true),
    //             'correct_answers' => json_encode($correctAnswers,true),
    //             'start_time' => $startTime,
    //             'end_time' => $endTime,
    //             'exam_duration' => $duration,
    //             'point_type' => $exam->point_mode,
    //             'point' => $points,
    //             'negative_marking' => $exam->negative_marking,
    //             'negative_marking_type' => $exam->negative_marking_type,
    //             'negative_marks' => $exam->negative_marks,
    //             'pass_percentage' => $exam->pass_percentage,
    //             'total_question' => count($questionsData),
    //             'status' => 'ongoing',
    //         ]);
    
    //         $remainingDuration = now()->diffInMinutes($examResult->end_time);
 
    //         return response()->json([
    //             'status' => true,
    //             'data' => [
    //                 'title' => $exam->title,
    //                 'uuid'=>$examResult->uuid,
    //                 'questions' => json_decode($examResult->questions),
    //                 'duration' => $remainingDuration . " mins",
    //                 'points' => $examResult->point,
    //                 'question_view' => $exam->question_view == 1 ? "enable" : "disable",
    //                 'finish_button' => $exam->disable_finish_button == 1 ? "enable" : "disable"
    //             ]
    //         ], 200);
    
    //     } catch (\Throwable $th) {
    //         return response()->json(['status' => false, 'error' => 'Internal Server Error: ' . $th->getMessage()], 500);
    //     }
    // }

    public function playExam(Request $request, $slug)
    {
        try {
            // Get the authenticated user
            $user = $request->attributes->get('authenticatedUser');

            // Validate incoming request data
            $request->validate([
                'category' => 'required|integer',
                'schedule_id'  => 'required',
            ]);

            // Fetch the exam IDs assigned to the current user
            $assignedExams = AssignedExam::select('exam_id')->where('user_id',$user->id)->get()->pluck('exam_id')->toArray();

            // Fetch the exam along with related questions in one query
            $exam = Exam::with([
                    'examQuestions.questions' => function($query) {
                        $query->select('id', 'question', 'default_marks', 'watch_time', 'type', 'options', 'answer');
                    }
                ])
                ->select(
                    'exams.id',
                    'exams.title',
                    'exams.description',
                    'exams.pass_percentage',
                    'exams.slug',
                    'exams.subcategory_id',
                    'exams.status',
                    'exams.duration_mode', 
                    'exams.point_mode', 
                    'exams.exam_duration', 
                    'exams.point',
                    'exams.shuffle_questions',
                    'exams.question_view',
                    'exams.disable_finish_button',
                    'exams.negative_marking',
                    'exams.negative_marking_type',
                    'exams.negative_marks',
                    'exams.is_free',
                    DB::raw('SUM(questions.default_marks) as total_marks'),
                    DB::raw('SUM(COALESCE(questions.watch_time, 0)) as total_time')
                )
                ->leftJoin('exam_questions', 'exams.id', '=', 'exam_questions.exam_id')
                ->leftJoin('questions', 'exam_questions.question_id', '=', 'questions.id')
                ->where(function ($query) use ($assignedExams) {
                    $query->where('exams.is_public', 1) 
                        ->orWhereIn('exams.id', $assignedExams); 
                })
                ->where('exams.slug', $slug)
                ->where('exams.subcategory_id', $request->category)
                ->where('exams.status', 1)
                ->where('questions.status', 1)
                ->groupBy(
                    'exams.id', 'exams.title', 'exams.description', 'exams.pass_percentage',
                    'exams.slug', 'exams.subcategory_id', 'exams.status', 'exams.duration_mode',
                    'exams.point_mode', 'exams.exam_duration', 'exams.point', 'exams.shuffle_questions',
                    'exams.question_view', 'exams.disable_finish_button', 'exams.negative_marking',
                    'exams.negative_marking_type', 'exams.negative_marks','exams.is_free'
                )
                ->first();

            // If exam not found
            if (!$exam) {
                return response()->json(['status' => false, 'error' => 'Exam not found'], 404);
            }

            // Adjust 'is_free' for assigned exams, regardless of public or private
            if (in_array($exam->id, $assignedExams)) {
                $exam->is_free = 1; // Make assigned exams free
            }

            // PAID EXAM
            if ($exam->is_free == 0) {
                $type = "exams";

                // Get the current date and time
                $currentDate = now();

                // Fetch the user's active subscription
                $subscription = Subscription::with('plans')->where('user_id', $user->id)->where('stripe_status', 'complete')->where('ends_at', '>', $currentDate)->latest()->first();

                // If no active subscription, return error
                if (!$subscription) {
                    return response()->json(['status' => false, 'error' => 'Please buy a subscription to access this course.'], 404);
                }

                // Fetch the plan related to this subscription
                $plan = $subscription->plans;

                if (!$plan) {
                    return response()->json(['status' => false, 'error' => 'No associated plan found for this subscription.'], 404);
                }

                // Check if the plan allows unlimited access
                if ($plan->feature_access == 1) {
                    // User has unlimited access, allow the exam
                } else {
                    // Fetch the allowed features for this plan
                    $allowed_features = json_decode($plan->features, true);

                    // Check if the requested feature type is in the allowed features
                    if (!in_array($type, $allowed_features)) {
                        return response()->json(['status' => false, 'error' => 'Feature not available in your plan. Please upgrade your subscription.'], 403);
                    }
                }
            }

            // Fetch all completed exam results
            $checkOngoingResult = ExamResult::where('user_id', $user->id)
                ->where('exam_id', $exam->id)
                ->where('status', 'complete')
                ->get();

            // Restrict exam attempts based on the configured limit
            if ($exam->restrict_attempts == 1 && $exam->total_attempts <= $checkOngoingResult->count()) {
                return response()->json(['status' => false, 'error' => 'Maximum Attempt Reached'], 403);
            }

            // Check for Ongoing Exam
            $ongoingExam = ExamResult::where('user_id', $user->id)
                ->where('exam_id', $exam->id)
                ->where('schedule_id',$request->schedule_id)
                ->where('status', 'ongoing') // Correct the status check
                ->latest('created_at')
                ->first();

            if ($ongoingExam) {
                // Calculate remaining duration
                $remainingDuration = now()->diffInMinutes($ongoingExam->end_time);

                if ($ongoingExam->end_time->isPast()) {
                    // If time has passed, mark the exam as complete
                    $ongoingExam->update(['status' => 'complete']);
                    return response()->json(['status' => true, 'message' => 'Exam Timed Out', 'data' => ['uuid' => $ongoingExam->uuid]]);
                } else {
                    // Return ongoing exam details
                    return response()->json([
                        'status' => true,
                        'data' => [
                            'title' => $exam->title,
                            'uuid' => $ongoingExam->uuid,
                            'questions' => json_decode($ongoingExam->questions),
                            'total_time'=> $ongoingExam->exam_duration,
                            'duration' => $remainingDuration . " mins",
                            'points' => $ongoingExam->point,
                            'saved_answers'=> json_decode($ongoingExam->answers),
                            'question_view' => $exam->question_view == 1 ? "enable" : "disable",
                            'finish_button' => $exam->disable_finish_button == 1 ? "enable" : "disable"
                        ]
                    ], 200);
                }
            }

            // Calculate exam duration and points
            // $duration = (int) ($exam->duration_mode == "manual" ? $exam->duration : round($exam->total_time / 60, 2));
            // $points = $exam->point_mode == "manual" ? $exam->point : $exam->total_marks;

            // Calculate exam duration and points
            $duration = (int) ($exam->duration_mode == "manual" ? $exam->exam_duration  : round($exam->total_time / 60, 2));
            $points = $exam->point_mode == "manual" ? $exam->point : $exam->total_marks;

            // Prepare structured response data for questions
            
            // $questionsData = [];
            // $correctAnswers = [];
            // foreach ($exam->examQuestions as $examQuestion) {
            //     $question = $examQuestion->questions;
            //     $options = $question->options ? json_decode($question->options, true) : [];

            //     if ($question->type == "MTF" && !empty($question->answer)) {
            //         $matchOption = json_decode($question->answer, true);
            //         shuffle($matchOption);
            //         $options = array_merge($options, $matchOption);
            //     }

            //     if ($question->type == "ORD") {
            //         shuffle($options);
            //     }

            //     // Customize question display for different types
            //     $questionText = $question->question;
            //     if ($question->type == "FIB") {
            //         $questionText = preg_replace('/##(.*?)##/', '<span class="border-b border-black inline-block w-[150px] text-center" style="width:150px;"></span>', $question->question);
            //         $options = [json_decode($question->answer, true) ? count(json_decode($question->answer, true)) : 0];
            //     } elseif ($question->type == "EMQ") {
            //         $questionText = json_decode($question->question, true);
            //     }


            //     // CHANGE EMQ QUESTION TO MSA (as I SAID YOU )
            //     if ($question->type == "EMQ") {
            //     }

            //     $questionsData[] = [
            //         'id' => $question->id,
            //         'type' => $question->type,
            //         'question' => $questionText,
            //         'options' => $options
            //     ];

            //     // Add correct answer info
            //     $correctAnswers[] = [
            //         'id' => $question->id,
            //         'correct_answer' => $question->answer,  // Use answer field
            //         'default_marks' => $exam->point_mode == "manual" ? $exam->point : $question->default_marks
            //     ];
            // }

            $questionsData = [];
            $correctAnswers = [];
            foreach ($exam->examQuestions as $examQuestion) {
                $question = $examQuestion->questions;
                $options = $question->options ? json_decode($question->options, true) : [];

                if ($question->type == "MTF" && !empty($question->answer)) {
                    $matchOption = json_decode($question->answer, true);
                    shuffle($matchOption);
                    $options = array_merge($options, $matchOption);
                }

                if ($question->type == "ORD") {
                    shuffle($options);
                }

                // Customize question display for different types
                $questionText = $question->question;
                if ($question->type == "FIB") {
                    $questionText = preg_replace('/##(.*?)##/', '<span class="border-b border-black inline-block w-[150px] text-center" style="width:150px;"></span>', $question->question);
                    $options = [json_decode($question->answer, true) ? count(json_decode($question->answer, true)) : 0];
                }elseif ($question->type == "EMQ") {
                    $questionText = json_decode($question->question, true);
                }
                
                // if($question->type == "EMQ") {
                //     // If EMQ, decode question text to access parent and child questions
                //     $parentChildQuestions = json_decode($question->question, true);
                    
                //     // Loop through each child question
                //     foreach ($parentChildQuestions as $index => $childQuestionText) {
                //         if ($index > 0) {
                //             // Treat the first question as the parent and others as separate child questions
                //             $QUESTIONNAME = $parentChildQuestions[0]."<br>".$childQuestionText;
                //             $childQuestionData = [
                //                 'id' => $question->id . "-$index",  // Unique ID for each child question
                //                 'type' => 'MSA',  // Treating as MSA as per your request
                //                 'question' => $QUESTIONNAME,
                //                 'options' => $options
                //             ];
                //             $questionsData[] = $childQuestionData;

                //             $optionArray = json_decode($question->answer,true);
                //             // Add correct answer for each child question
                //             $correctAnswers[] = [
                //                 'id' => $question->id . "-$index",
                //                 'correct_answer' => $optionArray[$index-1],  // Use the same answer for each child question
                //                 'default_marks' => $exam->point_mode == "manual" ? $exam->point : $question->default_marks
                //             ];
                //         }
                        
                //     }
                // } else {
                //     // Standard question processing for non-EMQ types
                //     $questionsData[] = [
                //         'id' => $question->id,
                //         'type' => $question->type,
                //         'question' => $questionText,
                //         'options' => $options
                //     ];

                //     // Add correct answer info
                //     $correctAnswers[] = [
                //         'id' => $question->id,
                //         'correct_answer' => $question->answer,
                //         'default_marks' => $exam->point_mode == "manual" ? $exam->point : $question->default_marks
                //     ];
                // }

                $questionsData[] = [
                    'id' => $question->id,
                    'type' => $question->type,
                    'question' => $questionText,
                    'options' => $options
                ];

                // Add correct answer info
                $correctAnswers[] = [
                    'id' => $question->id,
                    'correct_answer' => $question->answer,
                    'default_marks' => $exam->point_mode == "manual" ? $exam->point : $question->default_marks
                ];
            }

            // Shuffle questions if enabled
            if ($exam->shuffle_questions == 1) {
                shuffle($questionsData);
            }

            // Start exam result tracking
            $startTime = now();
            $endTime = $startTime->copy()->addMinutes($duration);

            $examResult = ExamResult::create([
                'exam_id' => $exam->id,
                'uuid' => uniqid(), // Generate unique identifier
                'subcategory_id' => $exam->subcategory_id,
                'user_id' => $user->id,
                'schedule_id'=>$request->schedule_id,
                'questions' => json_encode($questionsData, true),
                'correct_answers' => json_encode($correctAnswers, true),
                'start_time' => $startTime,
                'end_time' => $endTime,
                'exam_duration' => $duration,
                'point_type' => $exam->point_mode,
                'point' => $points,
                'negative_marking' => $exam->negative_marking,
                'negative_marking_type' => $exam->negative_marking_type,
                'negative_marks' => $exam->negative_marks,
                'pass_percentage' => $exam->pass_percentage,
                'total_question' => count($questionsData),
                'status' => 'ongoing',
            ]);

            $remainingDuration = now()->diffInMinutes($examResult->end_time);

            return response()->json([
                'status' => true,
                'data' => [
                    'title' => $exam->title,
                    'uuid' => $examResult->uuid,
                    'questions' => json_decode($examResult->questions),
                    'total_time'=> $examResult->exam_duration,
                    'duration' => $remainingDuration . " mins",
                    'points' => $examResult->point,
                    'saved_answers'=> json_decode($examResult->answers),
                    'question_view' => $exam->question_view == 1 ? "enable" : "disable",
                    'finish_button' => $exam->disable_finish_button == 1 ? "enable" : "disable"
                ]
            ], 200);

        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'error' => 'Internal Server Error: ' . $th->getMessage()], 500);
        }
    }

    public function finishExam(Request $request, $uuid){
        // USER RESPONSE
        $user_answer = $request->input('answers');
        $user = $request->attributes->get('authenticatedUser');
    
        // Fetch exam result by UUID and user ID
        $examResult = ExamResult::where('uuid', $uuid)->where('user_id', $user->id)->firstOrFail();
        if (!$examResult) {
            return response()->json([
                'status' => false,
                'message' => "Invalid Exam"
            ]);
        }
    
        $score = 0;
        $correctAnswer = 0;
        $incorrect = 0;
        $totalMarks = 0;
        $incorrectMarks = 0;
        $wrongQuestionIds = [];  // Array to hold IDs of wrong questions

        // Total marks should be fixed in manual mode
        $totalMarks = $examResult->point_type == "manual" ? $examResult->point * count($user_answer) : 0; 

        foreach ($user_answer as $answer) { 
            if (!isset($answer['id'])) {
                $incorrect += 1;
                continue;
            }
        
            $questionId = $answer['id'];
            $question = Question::find(explode("-", $questionId)[0]);
            
            if (!$question) {
                $incorrect += 1;
                continue;
            }
        
            $isCorrect = false;
            if (isset($answer['answer'])) {
                $userAnswer = $answer['answer'];
                if ($examResult->point_type != "manual") {
                    $totalMarks += $question->default_marks;
                }
        
                if ($question->type == 'MSA') {
                    $isCorrect = $question->answer == $userAnswer;
                } elseif ($question->type == 'MMA') {
                    $correctAnswers = json_decode($question->answer, true);
                    sort($correctAnswers);
                    sort($userAnswer);
                    $isCorrect = $userAnswer == $correctAnswers;
                } elseif ($question->type == 'TOF') {
                    $isCorrect = $userAnswer == $question->answer;
                } elseif ($question->type == 'SAQ') {
                    $isCorrect = false;
                    if (is_string($userAnswer) && is_array($question->options)) {
                        $answers = json_decode($question->options);
                        foreach ($answers as $option) {
                            $sanitizedOption = strtolower(trim(strip_tags($option)));
                            $sanitizedUserAnswer = strtolower(trim(strip_tags($userAnswer)));
                            if ($sanitizedUserAnswer == $sanitizedOption) {
                                $isCorrect = true;
                                break;
                            }
                        }
                    }
                } elseif ($question->type == 'FIB') {
                    $correctAnswers = json_decode($question->answer, true);
                    sort($correctAnswers);
                    sort($userAnswer);
                    $isCorrect = $userAnswer == $correctAnswers;
                } elseif ($question->type == 'MTF') {
                    $correctAnswers = json_decode($question->answer, true);
                    $isCorrect = true;
                    foreach ($correctAnswers as $key => $value) {
                        if (!isset($userAnswer[$key]) || $userAnswer[$key] != $value) {
                            $isCorrect = false; 
                            break;
                        }
                    }
                } elseif ($question->type == 'ORD') {
                    $correctAnswers = json_decode($question->answer, true);
                    $isCorrect = $userAnswer == $correctAnswers;
                } elseif ($question->type == 'EMQ') {
                    // $correctAnswers = json_decode($question->answer, true);
                    // $index = (int)explode("-", $questionId)[1] - 1;
                    // $isCorrect = $userAnswer == $correctAnswers[$index];

                    $correctAnswers = json_decode($question->answer, true);
                    sort($userAnswer);
                    sort($correctAnswers);
                    $isCorrect = $userAnswer == $correctAnswers;
                }

                 // Add to wrong question IDs if answer is incorrect
                if (!$isCorrect) {
                    $wrongQuestionIds[] = $questionId;  // Collect wrong question IDs
                }
                
        
                if ($isCorrect) {
                    $score += $examResult->point_type == "manual" ? $examResult->point : $question->default_marks;
                    $correctAnswer += 1;
                } else {
                    $incorrect += 1;
                    if (isset($question->default_marks)) {
                        $incorrectMarks += $question->default_marks;
                    }
                }
            } else {

                $wrongQuestionIds[] = $questionId;  

                $incorrect += 1;
                if (isset($question->default_marks)) {
                    $incorrectMarks += $question->default_marks;
                }
            }
        }
    
        // Apply negative marking for incorrect answers
        if ($examResult->negative_marking == 1) {
            if ($examResult->negative_marking_type == "fixed") {
                $score = max(0, $score - $examResult->negative_marking_value * $incorrect);
            } elseif ($examResult->negative_marking_type == "percentage") {
                $negativeMarks = ($examResult->negative_marking_value / 100) * $incorrectMarks;
                $score = max(0, $score - $negativeMarks);
            }
        }
    
        // Calculate the student's percentage AFTER applying negative marking
        $studentPercentage = ($totalMarks > 0) ? ($score / $totalMarks) * 100 : 0;
    
        // Determine pass or fail
        $studentStatus = ($studentPercentage >= $examResult->pass_percentage) ? 'PASS' : 'FAIL';
    
        // Update exam result with correct/incorrect answers and student percentage
        $examResult->status = "complete";
        $examResult->updated_at = now();
        $examResult->score = $score;
        $examResult->answers = json_encode($user_answer, true);
        $examResult->incorrect_answer = $incorrect;
        $examResult->correct_answer = $correctAnswer;
        $examResult->student_percentage = round($studentPercentage,2);
        $examResult->save();
    
        // Return results
        return response()->json([
            'status' => true,
            'score' => $score,
            'correct_answer' => $correctAnswer,
            'incorrect_answer' => $incorrect,
            'student_status' => $studentStatus,
            'student_percentage' => $studentPercentage,
            'wrong_question_ids' => $wrongQuestionIds  
        ]);
    }

    public function examResult(Request $request, $uuid){
        try {
            $user = $request->attributes->get('authenticatedUser');
    
            $examResult = ExamResult::with('exam')->where('uuid', $uuid)->where('user_id', $user->id)->first();
            if ($examResult) {
                // Build leaderboard
                $leaderBoard = [];
                if (isset($examResult->exam) && $examResult->exam->leaderboard == 1) {
                    $userExam = ExamResult::with('user')
                        ->where('exam_id', $examResult->exam_id)
                        ->orderby('student_percentage', 'DESC')
                        ->take(10)
                        ->get();
    
                    foreach ($userExam as $userData) {
                        if (isset($userData->user)) {
                            $leaderBoard[] = [
                                "username" => $userData->user->name,
                                "score" => $userData->student_percentage,
                                "status" => $userData->student_percentage >= $userData->pass_percentage ? "PASS" : "FAIL",
                            ];
                        }
                    }
                }

                $openTime = Carbon::parse($examResult->created_at);
                $closeTime = Carbon::parse($examResult->updated_at);
                $timeTakenInMinutes = round($openTime->diffInMinutes($closeTime),2);

                // Build result
                $result = [
                    'correct' => $examResult->correct_answer,
                    'incorrect' => $examResult->incorrect_answer,
                    'skipped' => $examResult->total_question - ($examResult->correct_answer + $examResult->incorrect_answer),
                    'marks' => $examResult->student_percentage,
                    'status' => $examResult->student_percentage >= $examResult->pass_percentage ? "PASS" : "FAIL",
                    'timeTaken' => $timeTakenInMinutes,
                    'uuid'=>$examResult->uuid
                ];
    
                // Process exam details (Compare user answers with correct answers)
                $exam = [];
                $questionBox = json_decode($examResult->questions);
                $correct_answers = json_decode($examResult->correct_answers, true);
                $userAnswers = json_decode($examResult->answers, true);

                foreach ($questionBox as $question) {
                    // Get the user answer for the current question by matching the IDs
                    $userAnswer = collect($userAnswers)->firstWhere('id', $question->id);
                    $correctAnswer = collect($correct_answers)->firstWhere('id', $question->id);
                    $isCorrect = false;

                    $user_answ = isset($userAnswer['answer']) ? $userAnswer['answer'] : null;
                    $correct_answ = isset($correctAnswer['correct_answer']) ? $correctAnswer['correct_answer'] : null;
                
                     // Ensure correctAnswer is an array when needed
                     switch ($question->type) {
                        case 'FIB':
                            if (is_string($correct_answ)) {
                                $correct_answ = json_decode($correct_answ, true);
                            }
                            $isCorrect = $user_answ == $correct_answ;
                            break;
                        case 'MSA':
                            $isCorrect = $user_answ == $correct_answ;
                            break;
                        case 'MMA':
                            if (is_string($correct_answ)) {
                                $correct_answ = json_decode($correct_answ, true);
                            }
                            sort($user_answ);
                            sort($correct_answ);
                            $isCorrect = $user_answ == $correct_answ;
                            break;
                        case 'TOF':
                            $isCorrect = $user_answ == $correct_answ;
                            break;
                        case 'MTF':
                            if (is_string($correct_answ)) {
                                $correct_answ = json_decode($correct_answ, true);
                            }
                            if (is_array($user_answ) && is_array($correct_answ)) {
                                $isCorrect = true;
                                foreach ($correct_answ as $key => $value) {
                                    if (!isset($user_answ[$key]) || $user_answ[$key] != $value) {
                                        $isCorrect = false;
                                        break;
                                    }
                                }
                            }
                            break;
                        case 'ORD':
                            if (is_string($correct_answ)) {
                                $correct_answ = json_decode($correct_answ, true);
                            }
                            $isCorrect = $user_answ == $correct_answ;
                            break;
                        case 'EMQ':
                            if (is_string($correct_answ)) {
                                $correct_answ = json_decode($correct_answ, true);
                            }
                            $isCorrect = $user_answ == $correct_answ;
                            break;
                        case 'SAQ': // string
                            $correct_answ = $question->options;
                            // Loop through each option and compare after sanitizing HTML
                            if (is_string($user_answ) && is_array($question->options)) {
                                $options = $question->options;
                                foreach ($options as $option) {
                                    // Strip HTML tags and extra spaces from both user answer and the option
                                    $sanitizedUserAnswer = strtolower(trim(strip_tags($user_answ)));
                                    $sanitizedOption = strtolower(trim(strip_tags($option)));

                                    // Check if the sanitized user answer matches any sanitized option
                                    if ($sanitizedUserAnswer == $sanitizedOption) {
                                        $isCorrect = true;
                                        break;
                                    }
                                }
                            }
                            break;
                    }
                

                    $examData[] = [
                        'question_id' => $question->id,
                        'question_type' => $question->type,
                        'question_text' => $question->question,
                        'question_option' => $question->options,
                        'correct_answer' => $correct_answ ?? null,
                        'user_answer' => $user_answ ?? null,  // Handle case where there's no user answer
                        'is_correct' => $isCorrect,
                    ];
                }
    
                $examType = ExamType::where('id', $examResult->exam->exam_type_id)->value('name');
                $is_type = $examType ?? "";
                
                $exam = [
                    'title' => $examResult->exam->title ?? 'N/A', 
                    'duration' => $examResult->exam_duration ?? 0, 
                    'download_report' => $examResult->exam->download_report ?? false, 
                    'exam_result_date' => $examResult->updated_at ? $examResult->updated_at->format('d-m-Y') : 'N/A',
                    'exam_result_time' => $examResult->updated_at ? $examResult->updated_at->format('h:i:s') : 'N/A',
                    'exam_result_type' => $is_type,
                ];
    
                return response()->json([
                    'status' => true,
                    'exam' => $exam,
                    'result' => $result,
                    'exam_preview' => $examData,
                    'leaderBoard' => $leaderBoard,
                ]);
            }
    
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong: '. $th->getMessage(),
            ]);
        }
    }

    // public function examAll(Request $request)
    // {
    //     try {
    //         // Validate the request
    //         $request->validate(['category' => 'required']);

    //         // Fetch the current authenticated user
    //         $user = $request->attributes->get('authenticatedUser');

    //         // Fetch the exam IDs assigned to the current user
    //         $assignedExams = AssignedExam::select('exam_id')
    //             ->where('user_id', $user->id)
    //             ->pluck('exam_id')
    //             ->toArray();

    //         // Get current date and time for upcoming exam logic
    //         $currentDate = now()->toDateString();
    //         $currentTime = now()->toTimeString();

    //         // Fetch upcoming exams with schedules
    //         $upcomingExams = Exam::join('exam_schedules', 'exams.id', '=', 'exam_schedules.exam_id') // Ensure only exams with schedules are included
    //             ->leftJoin('exam_types', 'exams.exam_type_id', '=', 'exam_types.id')
    //             ->leftJoin('exam_questions', 'exams.id', '=', 'exam_questions.exam_id')
    //             ->leftJoin('questions', 'exam_questions.question_id', '=', 'questions.id')
    //             ->where('exams.status', 1)
    //             ->where(function ($query) use ($assignedExams) {
    //                 $query->where('exams.is_public', 1)
    //                     ->orWhereIn('exams.id', $assignedExams);
    //             })
    //             ->where('exams.subcategory_id', $request->category)
    //             ->select(
    //                 'exams.id', 
    //                 'exams.is_free',
    //                 'exams.slug as exam_slug', 
    //                 'exams.title as exam_name', 
    //                 'exam_types.slug as exam_type_slug',
    //                 'exams.duration_mode',
    //                 'exams.exam_duration',
    //                 'exams.point_mode',
    //                 'exams.point', 
    //                 DB::raw('COUNT(questions.id) as total_questions'), 
    //                 DB::raw('SUM(CAST(questions.default_marks AS DECIMAL)) as total_marks'),
    //                 DB::raw('SUM(COALESCE(questions.watch_time, 0)) as total_time'),
    //                 'exam_schedules.schedule_type',
    //                 'exam_schedules.start_date',
    //                 'exam_schedules.start_time',
    //                 'exam_schedules.end_date',
    //                 'exam_schedules.end_time',
    //                 'exam_schedules.grace_period'
    //             )
    //             ->groupBy(
    //                 'exams.id',
    //                 'exams.is_free',
    //                 'exam_types.slug', 
    //                 'exams.slug', 
    //                 'exams.title', 
    //                 'exams.duration_mode', 
    //                 'exams.exam_duration', 
    //                 'exams.point_mode', 
    //                 'exams.point',
    //                 'exam_schedules.schedule_type',
    //                 'exam_schedules.start_date',
    //                 'exam_schedules.start_time',
    //                 'exam_schedules.end_date',
    //                 'exam_schedules.end_time',
    //                 'exam_schedules.grace_period'
    //             )
    //             ->havingRaw('COUNT(questions.id) > 0')
    //             ->havingRaw('COUNT(exam_schedules.id) > 0')
    //             ->get();

    //         // USER SUBSCRIPTION LOGIC
    //         $currentDate = now();
    //         $type = "exams"; // Type for feature access check

    //         // Fetch the user's active subscription
    //         $subscription = Subscription::with('plans')
    //             ->where('user_id', $user->id)
    //             ->where('stripe_status', 'complete')
    //             ->where('ends_at', '>', $currentDate)
    //             ->latest()
    //             ->first();

    //         // Apply subscription-based conditions to make exams free
    //         if ($subscription) {
    //             $plan = $subscription->plans;

    //             // Check if the plan allows unlimited access
    //             if ($plan->feature_access == 1) {
    //                 // MAKE ALL EXAMS FREE
    //                 $upcomingExams->transform(function ($exam) {
    //                     $exam->is_free = 1; // Make all exams free for unlimited access
    //                     return $exam;
    //                 });
    //             } else {
    //                 // Get allowed features from the plan
    //                 $allowed_features = json_decode($plan->features, true);

    //                 // Check if exams are included in the allowed features
    //                 if (in_array($type, $allowed_features)) {
    //                     // MAKE ALL EXAMS FREE
    //                     $upcomingExams->transform(function ($exam) {
    //                         $exam->is_free = 1; // Make exams free as part of allowed features
    //                         return $exam;
    //                     });
    //                 }
    //             }
    //         }

    //         // Apply the free logic to assigned exams
    //         $upcomingExams->transform(function ($exam) use ($assignedExams) {
    //             if (in_array($exam->id, $assignedExams)) {
    //                 $exam->is_free = 1; // Make assigned exams free
    //             }
    //             return $exam;
    //         });

    //         // Return success JSON response with upcoming exams and schedules
    //         return response()->json([
    //             'status' => true,
    //             'data' => $upcomingExams->map(function ($exam) {
    //                 return [
    //                     'id' => $exam->id,
    //                     'exam_type_slug' => $exam->exam_type_slug,
    //                     'slug' => $exam->exam_slug,
    //                     'title' => $exam->exam_name,
    //                     'duration_mode' => $exam->duration_mode,
    //                     'exam_duration' => $exam->exam_duration,
    //                     'point_mode' => $exam->point_mode,
    //                     'point' => $exam->point,
    //                     'is_free' => $exam->is_free,
    //                     'total_questions' => $exam->total_questions,
    //                     'total_marks' => $exam->total_marks,
    //                     'total_time' => $exam->total_time,
    //                     'schedules' => [
    //                         'schedule_type' => $exam->schedule_type,
    //                         'start_date' => $exam->start_date,
    //                         'start_time' => $exam->start_time,
    //                         'end_date' => $exam->end_date,
    //                         'end_time' => $exam->end_time,
    //                         'grace_period' => $exam->grace_period,
    //                     ],
    //                 ];
    //             })
    //         ], 200);

    //     } catch (\Throwable $th) {
    //         // Return error JSON response
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'An error occurred while fetching the exam data.',
    //             'error' => 'Error logged: ' . $th->getMessage()
    //         ], 500);
    //     }
    // }


    public function examAll(Request $request)
    {
        try {
            // Validate the request
            $request->validate(['category' => 'required']);

            // Fetch the current authenticated user
            $user = $request->attributes->get('authenticatedUser');

            // Fetch the exam IDs assigned to the current user
            $assignedExams = AssignedExam::where('user_id', $user->id)
                ->pluck('exam_id')
                ->toArray();

            // Get current date and time for upcoming exam logic
            $currentDate = now();
            
            // Fetch upcoming exams with schedules
            $upcomingExams = Exam::join('exam_schedules', 'exams.id', '=', 'exam_schedules.exam_id')
            ->leftJoin('exam_types', 'exams.exam_type_id', '=', 'exam_types.id')
            ->leftJoin('exam_questions', 'exams.id', '=', 'exam_questions.exam_id')
            ->leftJoin('questions', 'exam_questions.question_id', '=', 'questions.id')
            ->where('exams.status', 1)
            ->where('exam_schedules.status', 1)
            ->where(function ($query) use ($assignedExams) {
                $query->where('exams.is_public', 1)->orWhereIn('exams.id', $assignedExams);
            })
            ->where('exams.subcategory_id', $request->category)
            ->select(
                'exams.id',
                'exams.is_free',
                'exams.slug as exam_slug',
                'exams.title as exam_name',
                'exam_types.slug as exam_type_slug',
                'exams.duration_mode',
                'exams.exam_duration',
                'exams.point_mode',
                'exams.point',
                DB::raw('SUM(CASE 
                    WHEN questions.type = "EMQ" AND JSON_VALID(questions.question) THEN JSON_LENGTH(questions.question) - 1
                    ELSE 1 
                END) as total_questions'),  
                DB::raw('SUM(CAST(questions.default_marks AS DECIMAL)) as total_marks'),
                DB::raw('SUM(COALESCE(questions.watch_time, 0)) as total_time'),
                'exam_schedules.id as schedule_id',
                'exam_schedules.schedule_type',
                'exam_schedules.start_date',
                'exam_schedules.start_time',
                'exam_schedules.end_date',
                'exam_schedules.end_time',
                'exam_schedules.grace_period'
            )
            ->groupBy(
                'exams.id',
                'exams.is_free',
                'exam_types.slug',
                'exams.slug',
                'exams.title',
                'exams.duration_mode',
                'exams.exam_duration',
                'exams.point_mode',
                'exams.point',
                'exam_schedules.id',
                'exam_schedules.schedule_type',
                'exam_schedules.start_date',
                'exam_schedules.start_time',
                'exam_schedules.end_date',
                'exam_schedules.end_time',
                'exam_schedules.grace_period'
            )
            ->havingRaw('COUNT(questions.id) > 0')
            ->havingRaw('COUNT(exam_schedules.id) > 0')
            ->get();

            // Fetch the user's active subscription
            $currentDate = now();
            $type = "exams"; 
            $subscription = Subscription::with('plans')->where('user_id', $user->id)->where('stripe_status', 'complete')->where('ends_at', '>', $currentDate)->latest()->first();

            // Fetch the user's active subscription
            $subscription = Subscription::with('plans')
                ->where('user_id', $user->id)
                ->where('stripe_status', 'complete')
                ->where('ends_at', '>', $currentDate)
                ->latest()
                ->first();

            // Apply subscription-based conditions to make exams free
            if ($subscription) {
                $plan = $subscription->plans;

                // Check if the plan allows unlimited access
                if ($plan->feature_access == 1) {
                    // MAKE ALL EXAMS FREE
                    $upcomingExams->transform(function ($exam) {
                        $exam->is_free = 1; // Make all exams free for unlimited access
                        return $exam;
                    });
                } else {
                    // Get allowed features from the plan
                    $allowed_features = json_decode($plan->features, true);
                    // Check if exams are included in the allowed features
                    if (in_array($type, $allowed_features)) {
                        // MAKE ALL EXAMS FREE
                        $upcomingExams->transform(function ($exam) {
                            $exam->is_free = 1; // Make exams free as part of allowed features
                            return $exam;
                        });
                    }
                }
            }

            $current_time = now();
            $resumedExam = ExamResult::where('end_time', '>', $current_time)->where('user_id', $user->id)
            ->where('status', 'ongoing')
            ->pluck('exam_id')
            ->toArray();

            // Return success JSON response with upcoming exams and schedules
            return response()->json([
                'status' => true,
                'data' => $upcomingExams->map(function ($exam) use($resumedExam){
                    $isResume = in_array($exam->id, $resumedExam);
                    return [
                        'id' => $exam->id,
                        'exam_type_slug' => $exam->exam_type_slug,
                        'slug' => $exam->exam_slug,
                        'title' => $exam->exam_name,
                        'duration_mode' => $exam->duration_mode,
                        'exam_duration' => $exam->exam_duration,
                        'point_mode' => $exam->point_mode,
                        'point' => $exam->point,
                        'is_free' => $exam->is_free,
                        'total_questions' => $exam->total_questions,
                        'total_marks' => $exam->total_marks,
                        'total_time' => $exam->total_time,
                        'is_resume' => $isResume,
                        'schedules' => [
                            'schedule_id'=>$exam->schedule_id,
                            'schedule_type' => $exam->schedule_type,
                            'start_date' => $exam->start_date,
                            'start_time' => $exam->start_time,
                            'end_date' => $exam->end_date,
                            'end_time' => $exam->end_time,
                            'grace_period' => $exam->grace_period,
                        ],
                        'resumedExam'=>json_encode($resumedExam)
                    ];
                })
            ], 200);
        } catch (\Throwable $th) {
            // Log error and return error JSON response
            \Log::error('Error fetching exam data: ' . $th->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while fetching the exam data.',
                'error' => 'Error logged: ' . $th->getMessage()
            ], 500);
        }
    }

    public function examProgress(Request $request){
        try {
            $request->validate(['category' => 'required']);
        
            // Get the authenticated user
            $user = $request->attributes->get('authenticatedUser');
            $examResults = ExamResult::join('exams', 'exam_results.exam_id', '=', 'exams.id')
            ->select(
                'exam_results.updated_at', 
                'exam_results.student_percentage', 
                'exam_results.pass_percentage', 
                'exam_results.status', 
                'exam_results.uuid', 
                'exams.title as exam_title',
                'exams.slug as exam_slug'
            )
            ->where('exam_results.user_id', $user->id)
            ->where('exam_results.subcategory_id', $request->category)
            ->get();

            // Return success JSON response
            return response()->json([
                'status' => true,
                'data' => $examResults
            ], 200);
            
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while fetching the data.',
                'error' => 'Error logged. :' . $th->getMessage() 
            ], 500);
        }
    }


    // EXAM REPORT
    // public function downloadExamReport(Request $request, $uuid){
    //    try {
    //         // USER RESPONSE
    //         $user_answer = $request->input('answers');
    //         $user = $request->attributes->get('authenticatedUser');
    
    //         $userDetail = User::where('id',$user->id)->first();
        
    //         // Fetch exam result by UUID and user ID
    //         $examResult = ExamResult::where('uuid', $uuid)->where('user_id', $user->id)->firstOrFail();
    //         $exam = Exam::with('type','subCategory')->where('id',$examResult->exam_id)->first();
    //         if (!$examResult) {
    //             return response()->json([
    //                 'status' => false,
    //                 'message' => "Invalid Exam"
    //             ]);
    //         }
        
    //         $score = 0;
    //         $correctAnswer = 0;
    //         $incorrect = 0;
    //         $totalMarks = 0;
    //         $incorrectMarks = 0;
        
    //         // Total marks should be fixed in manual mode
    //         $totalMarks = $examResult->point_type == "manual" ? $examResult->point * count($user_answer) : 0; 
        
    //         foreach ($user_answer as $answer) {
    //             if (!isset($answer['id'])) {
    //                 $incorrect += 1;
    //                 continue;
    //             }
    //             $question = Question::find($answer['id']);
    //             if (!$question) {
    //                 $incorrect += 1;
    //                 continue;
    //             }
        
    //             // Handle different question types
    //             $isCorrect = false;
    
    //             if (isset($answer['answer'])) {
    //                 $userAnswer = $answer['answer'];
    //                 // In default mode, accumulate total possible marks
    //                 if ($examResult->point_type != "manual") {
    //                     $totalMarks += $question->default_marks;
    //                 }
            
    //                 // Check correctness based on question type
    //                 if ($question->type == 'MSA') {
    //                     $isCorrect = $question->answer == $userAnswer;
    //                 } elseif ($question->type == 'MMA') {
    //                     $correctAnswers = json_decode($question->answer, true);
    //                     sort($correctAnswers);
    //                     sort($userAnswer);
    //                     $isCorrect = $userAnswer == $correctAnswers;
    //                 } elseif ($question->type == 'TOF') {
    //                     $isCorrect = $userAnswer == $question->answer;
    //                 } elseif ($question->type == 'SAQ') {
    //                     $isCorrect = false;
    //                     if (is_string($userAnswer) && is_array($question->options)) {
    //                         $answers = json_decode($question->options);
    //                         foreach ($answers as $option) {
    //                             // Convert both the option and the user answer to lowercase and trim them
    //                             $sanitizedOption = strtolower(trim(strip_tags($option)));
    //                             $sanitizedUserAnswer = strtolower(trim(strip_tags($userAnswer)));
    
    //                             // Check if the sanitized option matches the sanitized user answer
    //                             if ($sanitizedUserAnswer == $sanitizedOption) {
    //                                 $isCorrect = true;
    //                                 break;  // Exit the loop once a match is found
    //                             }
    //                         }
    //                     }
    //                 } elseif ($question->type == 'FIB') {
    //                     $correctAnswers = json_decode($question->answer, true);
    //                     sort($correctAnswers);
    //                     sort($userAnswer);
    //                     $isCorrect = $userAnswer == $correctAnswers;
    //                 } elseif ($question->type == 'MTF') {
    //                     $correctAnswers = json_decode($question->answer, true);
    //                     $isCorrect = true; // Assume correct until proven otherwise
    //                     foreach ($correctAnswers as $key => $value) {
    //                         if (!isset($userAnswer[$key]) || $userAnswer[$key] != $value) {
    //                             $isCorrect = false; 
    //                             break;
    //                         }
    //                     }
    //                 } elseif ($question->type == 'ORD') {
    //                     $correctAnswers = json_decode($question->answer, true);
    //                     $isCorrect = $userAnswer == $correctAnswers;
    //                 } elseif ($question->type == 'EMQ') {
    //                     $correctAnswers = json_decode($question->answer, true);
    //                     sort($userAnswer);
    //                     sort($correctAnswers);
    //                     $isCorrect = $userAnswer == $correctAnswers;
    //                 }
            
    //                 if ($isCorrect) {
    //                     $score += $examResult->point_type == "manual" ? $examResult->point : $question->default_marks;
    //                     $correctAnswer += 1;
    //                 } else {
    //                     $incorrect += 1;
    //                     if (isset($question->default_marks)) {
    //                         $incorrectMarks += $question->default_marks;
    //                     }
    //                 }
    //             }else{
    //                 $incorrect += 1;
    //                 if (isset($question->default_marks)) {
    //                     $incorrectMarks += $question->default_marks;
    //                 }
    //             }
    //         }
        
    //         // Apply negative marking for incorrect answers
    //         if ($examResult->negative_marking == 1) {
    //             if ($examResult->negative_marking_type == "fixed") {
    //                 $score = max(0, $score - $examResult->negative_marking_value * $incorrect);
    //             } elseif ($examResult->negative_marking_type == "percentage") {
    //                 $negativeMarks = ($examResult->negative_marking_value / 100) * $incorrectMarks;
    //                 $score = max(0, $score - $negativeMarks);
    //             }
    //         }
        
    //         // Calculate the student's percentage AFTER applying negative marking
    //         $studentPercentage = ($totalMarks > 0) ? ($score / $totalMarks) * 100 : 0;
        
    //         // Determine pass or fail
    //         $studentStatus = ($studentPercentage >= $examResult->pass_percentage) ? 'PASS' : 'FAIL';
        
    //         // Update exam result with correct/incorrect answers and student percentage
    //         $examResult->status = "complete";
    //         $examResult->updated_at = now();
    //         $examResult->answers = json_encode($user_answer, true);
    //         $examResult->incorrect_answer = $incorrect;
    //         $examResult->correct_answer = $correctAnswer;
    //         $examResult->student_percentage = round($studentPercentage,2);
    //         $examResult->save();
    
    //         $examInfo = [
    //             'name'=>$exam->type->name." - ".$exam->subCategory->name,
    //             'completed_on'=>$examResult->created_at,
    //             'seesion_id'=>$examResult->uuid,
    //         ];
    
    //         $userInfo = [
    //             'name'=>$userDetail->name,
    //             'email'=>$userDetail->email,
    //         ];
    
    //         $studentAnswers = $correctAnswer + $incorrect;
    
    //         $resultInfo = [
    //             'total_question'=>$examResult->total_question,
    //             'answered'=>$studentAnswers,
    //             'correct'=>$correctAnswer,
    //             'wrong'=>$incorrect,
    //             'pass_percentage'=>$examResult->pass_percentage,
    //             'final_percentage'=>$studentPercentage,
    //             'final_score'=>$studentStatus,
    //             'time_taken'=>$timeTakenInMinutes,
    //         ];
    
        
           
    //    } catch (\Throwable $th) {
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'An error occurred while fetching the data.',
    //             'error' => 'Error logged. :' . $th->getMessage() 
    //         ], 500);
    //    }
    // }


    public function downloadExamReport(Request $request, $uuid)
    {
        try {
            $user = $request->attributes->get('authenticatedUser');
    
            $examResult = ExamResult::with('exam')->where('uuid', $uuid)->where('user_id', $user->id)->first();
    
            if (!$examResult) {
                return response()->json([
                    'status' => false,
                    'message' => 'Exam result not found for this user.'
                ], 404);
            }
    
            $exam_data = Exam::with('type', 'subCategory')->where('id', $examResult->exam_id)->first();
            $userDetail = User::find($user->id);
    
            // Ensure $exam_data and relationships are valid
            if (!$exam_data || !$exam_data->type || !$exam_data->subCategory) {
                return response()->json([
                    'status' => false,
                    'message' => 'Exam data not properly linked.'
                ], 500);
            }
    
            $openTime = Carbon::parse($examResult->created_at);
            $closeTime = Carbon::parse($examResult->updated_at);
            $timeTakenInMinutes = $openTime->diffInMinutes($closeTime);
    
            // FINAL OUTPUT
            $examInfo = [
                'name' => $exam_data->type->name . " - " . $exam_data->subCategory->name,
                'completed_on' => $examResult->created_at->format('d-m-y'),
                'session_id' => $examResult->uuid,
            ];
    
            $userInfo = [
                'name' => $userDetail->name,
                'email' => $userDetail->email,
                'number'=> $userDetail->phone_number,
            ];
    
            $studentAnswers = $examResult->correct_answer + $examResult->incorrect_answer;
    
            $resultInfo = [
                'total_question' => $examResult->total_question,
                'answered' => $studentAnswers,
                'correct' => $examResult->correct_answer,
                'wrong' => $examResult->incorrect_answer,
                'skipped' => $examResult->total_question - $studentAnswers,
                'pass_percentage' => $examResult->pass_percentage,
                'final_percentage' => $examResult->student_percentage,
                'final_score' => $examResult->student_percentage >= $examResult->pass_percentage ? "PASS" : "FAIL",
                'time_taken' => $timeTakenInMinutes,
            ];
    
            return response()->json([
                'status' => true,
                'result_info' => $resultInfo,
                'user_info' => $userInfo,
                'exam_info' => $examInfo,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong: ' . $th->getMessage(),
            ]);
        }
    }
    

    public function saveAnswerProgress(Request $request, $uuid)
    {
        // Fetch the user and user answers
        $user_answer = $request->input('answers');
        $user = $request->attributes->get('authenticatedUser');
        // Find or create an exam result in progress by UUID and user ID
        $examResult = ExamResult::where('uuid', $uuid)->where('user_id', $user->id)->first();
        if (!$examResult) {
            return response()->json([
                'status' => false,
                'message' => "Exam not found"
            ]);
        }
        // Update user answers in progress
        $examResult->answers = json_encode($user_answer, true);
        $examResult->updated_at = now();
        $examResult->save();
        return response()->json([
            'status' => true,
            'message' => 'Answer progress saved successfully',
        ]);
    }

    public function getSavedProgress($uuid)
    {
        $user = request()->attributes->get('authenticatedUser');
        $examResult = ExamResult::where('uuid', $uuid)->where('user_id', $user->id)->first();
        if (!$examResult) {
            return response()->json(['status' => false, 'message' => 'Exam not found']);
        }
        return response()->json(['status' => true, 'answers' => $examResult->answers]);
    }

}
