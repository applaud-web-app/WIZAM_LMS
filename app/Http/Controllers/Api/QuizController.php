<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\Quizze;
use App\Models\QuizResult;
use Illuminate\Support\Str; 
use Carbon\Carbon;

class QuizController extends Controller
{
    // public function playQuiz(Request $request, $slug) {
    //     try {
    //         // Validate incoming request data
    //         $request->validate([
    //             'category' => 'required|integer',
    //         ]);
    
    //         // SELECT QUIZ and related questions, marks, and watch time in one query
    //         $quiz = Quizze::with([
    //                 'quizQuestions.questions' => function($query) {
    //                     $query->select('id', 'question', 'default_marks', 'watch_time', 'type', 'options','answer');
    //                 }
    //             ])
    //             ->select(
    //                 'quizzes.id',
    //                 'quizzes.title',
    //                 'quizzes.description',
    //                 'quizzes.pass_percentage',
    //                 'quizzes.slug',
    //                 'quizzes.subcategory_id',
    //                 'quizzes.status',
    //                 'quizzes.duration_mode',
    //                 'quizzes.point_mode',
    //                 'quizzes.duration',
    //                 'quizzes.point',
    //                 'quizzes.shuffle_questions',
    //                 'quizzes.question_view',
    //                 'quizzes.disable_finish_button',
    //                 DB::raw('SUM(questions.default_marks) as total_marks'),
    //                 DB::raw('SUM(COALESCE(questions.watch_time, 0)) as total_time')
    //             )
    //             ->leftJoin('quiz_questions', 'quizzes.id', '=', 'quiz_questions.quizzes_id')
    //             ->leftJoin('questions', 'quiz_questions.question_id', '=', 'questions.id')
    //             ->where('quizzes.slug', $slug)
    //             ->where('quizzes.subcategory_id', $request->category)
    //             ->where('quizzes.status', 1)
    //             ->where('questions.status', 1)
    //             ->groupBy(
    //                 'quizzes.id',
    //                 'quizzes.title',
    //                 'quizzes.description',
    //                 'quizzes.pass_percentage',
    //                 'quizzes.slug',
    //                 'quizzes.subcategory_id',
    //                 'quizzes.status',
    //                 'quizzes.duration_mode',
    //                 'quizzes.point_mode',
    //                 'quizzes.duration',
    //                 'quizzes.point',
    //                 'quizzes.shuffle_questions',
    //                 'quizzes.question_view',
    //                 'quizzes.disable_finish_button',
    //             )
    //             ->first();
    
    //         // If quiz not found
    //         if (!$quiz) {
    //             return response()->json(['status' => false, 'error' => 'Quiz not found'], 404);
    //         }

    //         // Get the authenticated user
    //         $user = $request->attributes->get('authenticatedUser');
    //         $checkOngoingResult = QuizResult::where('user_id',$user->id)->where('quiz_id',$quiz->id)->where('status','complete')->get();

    //         // QUIZ RISTRICK ATTEMP 
    //         if($quiz->restrict_attempts == 1){
    //             if($quiz->total_attempts < count($checkOngoingResult)){
    //                 //  CONTINE YOU CAN MOVE FURTURE
    //             }else{
    //                 return response()->json(['status' => false, 'error' => 'Maximum Attempt Reached'], 404);
    //             }
    //         }

    //         $ongoingQuiz = $checkOngoingResult->latest()->first();
    //         if($ongoingQuiz->status == "ongoing"){
    //             $CurrentDuration = $ongoingQuiz->end_time - now();
    //             if($ongoingQuiz->end_time < now()){
    //                 // THEN MAKE THE QUIZ RESULT COMPLETE 
    //             }


    //             // Final output structure
    //             $examMockData = [
    //                 'title' => $quiz->title,
    //                 'questions' => $ongoingQuiz->question,
    //                 'duration' => $CurrentDuration . " mins",
    //                 'points' => $ongoingQuiz->points,
    //                 'question_view' =>$quiz->question_view == 1 ? "enable":"disable",
    //                 'finish_button' => $quiz->disable_finish_button == 1 ? "enable":"disable"
    //             ];
        
    //             // Return the quiz data
    //             return response()->json(['status' => true, 'data' => $examMockData], 200);
    //         }
    
    //         // Determine duration and points
    //         $duration = $quiz->duration_mode == "manual" ? $quiz->duration : round($quiz->total_time / 60, 2); // converting seconds to minutes
    //         $points = $quiz->point_mode == "manual" ? $quiz->point : $quiz->total_marks;
    
    //         // Prepare structured response data for questions
    //         $questionsData = [];
    //         foreach ($quiz->quizQuestions as $quizQuestion) {
    //             $question = $quizQuestion->questions;

    //             $options = $question->options ? json_decode($question->options) : []; // Decode original options

    //             if ($question->type == "MTF") {
    //                 // Decode the match values (answers)
    //                 $match_option = $question->answer ? json_decode($question->answer, true) : [];

    //                 // Check if match_option is not empty
    //                 if (!empty($match_option)) {
    //                     // Shuffle the match values
    //                     $shuffled_match_values = array_values($match_option); // Get values only for shuffling
    //                     shuffle($shuffled_match_values); // Shuffle the values

    //                     // Add the shuffled match values to the options array
    //                     $options = array_merge((array)$options, $shuffled_match_values);
    //                 }
    //             }

    //             $questionText = $question->question;
    //             if($question->type == "FIB"){
    //                 $questionText = preg_replace('/##(.*?)##/', '<span class="border-b border-black inline-block w-24 text-center"></span>', $question->question);
    //             }

    //             if($question->type == "EMQ"){
    //                 $questionText = $question->question ? json_decode($question->question) : [];
    //             }

    //             $questionsData[] = [
    //                 'id' => $question->id,
    //                 'type' => $question->type, // single, multiple, MTF, etc.
    //                 'question' => $questionText,
    //                 'options' => $options, // Updated options for MTF or other question types
    //             ];

    //         }

    //         // Shuffle questions if quiz shuffle is enabled
    //         if ($quiz->shuffle_questions == 1) {
    //             shuffle($questionsData); // Shuffle the questions array
    //         }

    //         // Final output structure
    //         // $examMockData = [
    //         //     'title' => $quiz->title,
    //         //     'questions' => $questionsData,
    //         //     'duration' => $duration . " mins",
    //         //     'points' => $points,
    //         //     'question_view' =>$quiz->question_view == 1 ? "enable":"disable",
    //         //     'finish_button' => $quiz->disable_finish_button == 1 ? "enable":"disable"
    //         // ];

    //         $startTime =  now();
    //         $endTIme = now() +  $duration;

    //         // QUIZ RESULT
    //         $quizResult = QuizResult::create([
    //             'quiz_id' => $quiz->id,
    //             'uuid' => "1234567", // Make something unique 
    //             'subcategory_id' => $quiz->subcategory_id,
    //             'user_id' => $user->id,
    //             'question'=>$questionsData,
    //             'start_time' => $startTime,
    //             'end_time' => $endTime,
    //             'exam_duration' => $duration, // MINUTES
    //             'point' => $points,
    //             'negative_marking'=> $quiz->negative_marking,
    //             'negative_marking_type'=> $quiz->negative_marking_type,
    //             'negative_marks'=> $quiz->negative_marks,
    //             'pass_percentage'=>$quiz->pass_percentage,
    //             'total_question'=>count($questionsData),
    //             'status' => 'ongoing', // Status set to ongoing
    //         ]);

    //         $CurrentDuration = $quizResult->end_time - now();

    //         // Final output structure
    //         $examMockData = [
    //             'title' => $quiz->title,
    //             'questions' => $quizResult->question,
    //             'duration' => $CurrentDuration . " mins",
    //             'points' => $quizResult->points,
    //             'question_view' =>$quiz->question_view == 1 ? "enable":"disable",
    //             'finish_button' => $quiz->disable_finish_button == 1 ? "enable":"disable"
    //         ];
    
    //         // Return the quiz data
    //         return response()->json(['status' => true, 'data' => $examMockData], 200);
    
    //     } catch (\Throwable $th) {
    //         return response()->json(['status' => false, 'error' => 'Internal Server Error: ' . $th->getMessage()], 500);
    //     }
    // }

    
    // public function playQuiz(Request $request, $slug) {
    //     try {
    //         // Validate incoming request data
    //         $request->validate([
    //             'category' => 'required|integer',
    //         ]);
    
    //         // SELECT QUIZ and related questions, marks, and watch time in one query
    //         $quiz = Quizze::with([
    //                 'quizQuestions.questions' => function($query) {
    //                     $query->select('id', 'question', 'default_marks', 'watch_time', 'type', 'options','answer');
    //                 }
    //             ])
    //             ->select(
    //                 'quizzes.id',
    //                 'quizzes.title',
    //                 'quizzes.description',
    //                 'quizzes.pass_percentage',
    //                 'quizzes.slug',
    //                 'quizzes.subcategory_id',
    //                 'quizzes.status',
    //                 'quizzes.duration_mode',
    //                 'quizzes.point_mode',
    //                 'quizzes.duration',
    //                 'quizzes.point',
    //                 'quizzes.shuffle_questions',
    //                 'quizzes.question_view',
    //                 'quizzes.disable_finish_button',
    //                 DB::raw('SUM(questions.default_marks) as total_marks'),
    //                 DB::raw('SUM(COALESCE(questions.watch_time, 0)) as total_time')
    //             )
    //             ->leftJoin('quiz_questions', 'quizzes.id', '=', 'quiz_questions.quizzes_id')
    //             ->leftJoin('questions', 'quiz_questions.question_id', '=', 'questions.id')
    //             ->where('quizzes.slug', $slug)
    //             ->where('quizzes.subcategory_id', $request->category)
    //             ->where('quizzes.status', 1)
    //             ->groupBy(
    //                 'quizzes.id',
    //                 'quizzes.title',
    //                 'quizzes.description',
    //                 'quizzes.pass_percentage',
    //                 'quizzes.slug',
    //                 'quizzes.subcategory_id',
    //                 'quizzes.status',
    //                 'quizzes.duration_mode',
    //                 'quizzes.point_mode',
    //                 'quizzes.duration',
    //                 'quizzes.point',
    //                 'quizzes.shuffle_questions',
    //                 'quizzes.question_view',
    //                 'quizzes.disable_finish_button',
    //             )
    //             ->first();
    
    //         // If quiz not found
    //         if (!$quiz) {
    //             return response()->json(['status' => false, 'error' => 'Quiz not found'], 404);
    //         }
    
    //         // Determine duration and points
    //         $duration = $quiz->duration_mode == "manual" ? $quiz->duration : round($quiz->total_time / 60, 2); // converting seconds to minutes
    //         $points = $quiz->point_mode == "manual" ? $quiz->point : $quiz->total_marks;
    
    //         // Check if the user has already started this quiz session
    //         $user = $request->attributes->get('authenticatedUser'); // Assuming you're using custom auth logic
    //         $quizResult = QuizResult::where('quiz_id', $quiz->id)
    //             ->where('user_id', $user->id)
    //             ->where('status', 'ongoing') // Check for ongoing sessions
    //             ->first();
    
    //         if (!$quizResult) {
    //             // Create new quiz result with current time as the start time
    //             $startTime = now();
    //             $endTime = $startTime->copy()->addMinutes($duration); // Add the quiz duration to the start time
    
    //             $quizResult = QuizResult::create([
    //                 'quiz_id' => $quiz->id,
    //                 'uuid' => "123",
    //                 'subcategory_id' => $quiz->subcategory_id,
    //                 'user_id' => $user->id,
    //                 'start_time' => $startTime,
    //                 'end_time' => $endTime,
    //                 'exam_duration' => $duration,
    //                 'point' => $points,
    //                 'status' => 'ongoing', // Status set to ongoing
    //             ]);
    //         }
    
    //         // Calculate the remaining duration
    //         $currentTime = now();
    //         $remainingDuration = max($quizResult->end_time->diffInSeconds($currentTime), 0); // Difference in seconds
    
    //         // Prepare structured response data for questions
    //         $questionsData = [];
    //         foreach ($quiz->quizQuestions as $quizQuestion) {
    //             $question = $quizQuestion->questions;
    //             $options = $question->options ? json_decode($question->options) : []; // Decode original options
    
    //             if ($question->type == "MTF") {
    //                 $match_option = $question->answer ? json_decode($question->answer, true) : [];
    //                 if (!empty($match_option)) {
    //                     $shuffled_match_values = array_values($match_option);
    //                     shuffle($shuffled_match_values); // Shuffle the match values
    //                     $options = array_merge((array)$options, $shuffled_match_values);
    //                 }
    //             }
    
    //             $questionText = $question->question;
    //             if ($question->type == "FIB") {
    //                 $questionText = preg_replace('/##(.*?)##/', '<span class="border-b border-black inline-block w-24 text-center"></span>', $question->question);
    //             }
    
    //             if ($question->type == "EMQ") {
    //                 $questionText = $question->question ? json_decode($question->question) : [];
    //             }
    
    //             $questionsData[] = [
    //                 'id' => $question->id,
    //                 'type' => $question->type,
    //                 'question' => $questionText,
    //                 'options' => $options,
    //             ];
    //         }
    
    //         // Shuffle questions if quiz shuffle is enabled
    //         if ($quiz->shuffle_questions == 1) {
    //             shuffle($questionsData); // Shuffle the questions array
    //         }
    
    //         // Final output structure
    //         $examMockData = [
    //             'title' => $quiz->title,
    //             'questions' => $questionsData,
    //             'duration' => round($remainingDuration / 60, 2) . " mins", // Remaining time in minutes
    //             'points' => $points,
    //             'question_view' => $quiz->question_view == 1 ? "enable" : "disable",
    //             'finish_button' => $quiz->disable_finish_button == 1 ? "enable" : "disable"
    //         ];
    
    //         // Return the quiz data
    //         return response()->json(['status' => true, 'data' => $examMockData], 200);
    
    //     } catch (\Throwable $th) {
    //         return response()->json(['status' => false, 'error' => 'Internal Server Error: ' . $th->getMessage()], 500);
    //     }
    // }

    public function playQuiz(Request $request, $slug)
    {
        try {
            // Validate incoming request data
            $request->validate([
                'category' => 'required|integer',
            ]);
    
            // Fetch the quiz along with related questions in one query
            $quiz = Quizze::with([
                    'quizQuestions.questions' => function($query) {
                        $query->select('id', 'question', 'default_marks', 'watch_time', 'type', 'options', 'answer');
                    }
                ])
                ->select(
                    'quizzes.id',
                    'quizzes.title',
                    'quizzes.description',
                    'quizzes.pass_percentage',
                    'quizzes.slug',
                    'quizzes.subcategory_id',
                    'quizzes.status',
                    'quizzes.duration_mode',
                    'quizzes.point_mode',
                    'quizzes.duration',
                    'quizzes.point',
                    'quizzes.shuffle_questions',
                    'quizzes.question_view',
                    'quizzes.disable_finish_button',
                    'quizzes.negative_marking',
                    'quizzes.negative_marking_type',
                    'quizzes.negative_marks',
                    DB::raw('SUM(questions.default_marks) as total_marks'),
                    DB::raw('SUM(COALESCE(questions.watch_time, 0)) as total_time')
                )
                ->leftJoin('quiz_questions', 'quizzes.id', '=', 'quiz_questions.quizzes_id')
                ->leftJoin('questions', 'quiz_questions.question_id', '=', 'questions.id')
                ->where('quizzes.slug', $slug)
                ->where('quizzes.subcategory_id', $request->category)
                ->where('quizzes.status', 1)
                ->where('questions.status', 1)
                ->groupBy(
                    'quizzes.id', 'quizzes.title', 'quizzes.description', 'quizzes.pass_percentage',
                    'quizzes.slug', 'quizzes.subcategory_id', 'quizzes.status', 'quizzes.duration_mode',
                    'quizzes.point_mode', 'quizzes.duration', 'quizzes.point', 'quizzes.shuffle_questions',
                    'quizzes.question_view', 'quizzes.disable_finish_button', 'quizzes.negative_marking',
                    'quizzes.negative_marking_type', 'quizzes.negative_marks'
                )
                ->first();
    
            // If quiz not found
            if (!$quiz) {
                return response()->json(['status' => false, 'error' => 'Quiz not found'], 404);
            }
    
            // Get the authenticated user
            $user = $request->attributes->get('authenticatedUser');
    
            // Fetch all completed quiz results
            $checkOngoingResult = QuizResult::where('user_id', $user->id)
                ->where('quiz_id', $quiz->id)
                ->where('status', 'complete')
                ->get();
    
            // Restrict quiz attempts based on the configured limit
            if ($quiz->restrict_attempts == 1 && $quiz->total_attempts <= $checkOngoingResult->count()) {
                return response()->json(['status' => false, 'error' => 'Maximum Attempt Reached'], 403);
            }
    
            // Check for ongoing quiz
            $ongoingQuiz = QuizResult::where('user_id', $user->id)
                ->where('quiz_id', $quiz->id)
                ->where('status', 'ongoing') // Correct the status check
                ->latest('created_at')
                ->first();
    
            if ($ongoingQuiz) {
                // $remainingDuration = $ongoingQuiz->end_time->diffInMinutes(now());
                $remainingDuration = now()->diffInMinutes($ongoingQuiz->end_time); // Ensure no negative duration

                if ($ongoingQuiz->end_time->isPast()) {
                    // If time has passed, mark the quiz as complete
                    $ongoingQuiz->update(['status' => 'complete']);
                } else {
                    // Return ongoing quiz details
                    return response()->json([
                        'status' => true,
                        'data' => [
                            'title' => $quiz->title,
                            'questions' => json_decode($ongoingQuiz->questions),
                            'duration' => $remainingDuration . " mins",
                            'points' => $ongoingQuiz->point,
                            'question_view' => $quiz->question_view == 1 ? "enable" : "disable",
                            'finish_button' => $quiz->disable_finish_button == 1 ? "enable" : "disable"
                        ]
                    ], 200);
                }
            }
    
            // Calculate quiz duration and points
            $duration = (int) ($quiz->duration_mode == "manual" && $quiz->duration > 0 ? $quiz->duration : round($quiz->total_time / 60, 2));
            $points = $quiz->point_mode == "manual" ? $quiz->point : $quiz->total_marks;
    
            // Prepare structured response data for questions
            $questionsData = [];
            foreach ($quiz->quizQuestions as $quizQuestion) {
                $question = $quizQuestion->questions;
                $options = $question->options ? json_decode($question->options, true) : [];
    
                if ($question->type == "MTF" && !empty($question->answer)) {
                    $matchOption = json_decode($question->answer, true);
                    shuffle($matchOption);
                    $options = array_merge($options, $matchOption);
                }
    
                // Customize question display for different types
                $questionText = $question->question;
                if ($question->type == "FIB") {
                    $questionText = preg_replace('/##(.*?)##/', '<span class="border-b border-black inline-block w-24 text-center"></span>', $question->question);
                } elseif ($question->type == "EMQ") {
                    $questionText = json_decode($question->question, true);
                }
    
                $questionsData[] = [
                    'id' => $question->id,
                    'type' => $question->type,
                    'question' => $questionText,
                    'options' => $options
                ];
            }
    
            // Shuffle questions if enabled
            if ($quiz->shuffle_questions == 1) {
                shuffle($questionsData);
            }
    
            // Start quiz result tracking
            $startTime = now();
            $endTime = $startTime->copy()->addMinutes($duration); 
    
            $quizResult = QuizResult::create([
                'quiz_id' => $quiz->id,
                'uuid' => uniqid(), // Generate unique identifier
                'subcategory_id' => $quiz->subcategory_id,
                'user_id' => $user->id,
                'questions' => json_encode($questionsData,true),
                'start_time' => $startTime,
                'end_time' => $endTime,
                'exam_duration' => $duration,
                'point' => $points,
                'negative_marking' => $quiz->negative_marking,
                'negative_marking_type' => $quiz->negative_marking_type,
                'negative_marks' => $quiz->negative_marks,
                'pass_percentage' => $quiz->pass_percentage,
                'total_question' => count($questionsData),
                'status' => 'ongoing',
            ]);
    
            $remainingDuration = now()->diffInMinutes($quizResult->end_time);
 
            return response()->json([
                'status' => true,
                'data' => [
                    'title' => $quiz->title,
                    'questions' => json_decode($quizResult->questions),
                    'duration' => $remainingDuration . " mins",
                    'points' => $quizResult->point,
                    'question_view' => $quiz->question_view == 1 ? "enable" : "disable",
                    'finish_button' => $quiz->disable_finish_button == 1 ? "enable" : "disable"
                ]
            ], 200);
    
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'error' => 'Internal Server Error: ' . $th->getMessage()], 500);
        }
    }

    
    

    
    
    

    
}
