<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PracticeSet;
use App\Models\PracticeSetResult;
use App\Models\PracticeSetQuestion;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\Quizze;
use App\Models\QuizResult;
use Illuminate\Support\Str; 
use Carbon\Carbon;
use App\Models\Question;

class PracticeSetController extends Controller
{
    // public function playPracticeSet(Request $request, $slug)
    // {
    //     try {
    //         // Validate incoming request data
    //         $request->validate([
    //             'category' => 'required|integer',
    //         ]);
    
    //         // Fetch the practice along with related questions in one query
    //         $practice = PracticeSet::with([
    //                 'practiceQuestions.questions' => function($query) {
    //                     $query->select('id', 'question', 'default_marks', 'watch_time', 'type', 'options', 'answer');
    //                 }
    //             ])
    //             ->select(
    //                 'practice_sets.id',
    //                 'practice_sets.title',
    //                 'practice_sets.description',
    //                 'practice_sets.slug',
    //                 'practice_sets.subcategory_id',
    //                 'practice_sets.status',
    //                 'practice_sets.allow_reward',
    //                 'practice_sets.reward_popup',
    //                 'practice_sets.point_mode',
    //                 'practice_sets.points',
    //                 'practice_sets.is_free',
    //                 DB::raw('SUM(questions.default_marks) as total_marks'),
    //                 DB::raw('SUM(COALESCE(questions.watch_time, 0)) as total_time')
    //             )
    //             ->leftJoin('practice_set_questions', 'practice_sets.id', '=', 'practice_set_questions.practice_set_id')
    //             ->leftJoin('questions', 'practice_set_questions.question_id', '=', 'questions.id')
    //             ->where('practice_sets.slug', $slug)
    //             ->where('practice_sets.subcategory_id', $request->category)
    //             ->where('practice_sets.status', 1)
    //             ->where('questions.status', 1)
    //             ->groupBy('practice_sets.id', 'practice_sets.title', 'practice_sets.description', 'practice_sets.slug', 'practice_sets.subcategory_id', 'practice_sets.status', 'practice_sets.allow_reward', 'practice_sets.reward_popup', 'practice_sets.point_mode', 'practice_sets.points', 'practice_sets.is_free')
    //             ->first();
    
    //         // If practice not found
    //         if (!$practice) {
    //             return response()->json(['status' => false, 'error' => 'Practice Set not found'], 404);
    //         }
    
    //         // Get the authenticated user
    //         $user = $request->attributes->get('authenticatedUser');
    
    //         // Fetch all completed practice results
    //         $checkOngoingResult = PracticeSetResult::where('user_id', $user->id)->where('practice_sets_id', $practice->id)->where('status', 'complete')->get();
    
    //         // Check for ongoing practice set
    //         $ongoingPractice = PracticeSetResult::where('user_id', $user->id)
    //             ->where('practice_sets_id', $practice->id)
    //             ->where('status', 'ongoing') // Correct the status check
    //             ->latest('created_at')
    //             ->first();
    
    //         if ($ongoingPractice) {
    //             // $remainingDuration = $ongoingPractice->end_time->diffInMinutes(now());
    //             $remainingDuration = max(now()->diffInMinutes($ongoingPractice->end_time), 0);

    //             if ($ongoingPractice->end_time->isPast()) {
    //                 // If time has passed, mark the practice as complete
    //                 $ongoingPractice->update(['status' => 'complete']);
    //                 $data = [
    //                     'uuid'=>$ongoingPractice->uuid,
    //                 ];
    //                 return response()->json(['status' => true, 'message' => 'Practice Set Timed Out','data'=>$data]);
    //             } else {
    //                 // Return ongoing practice set details
    //                 return response()->json([
    //                     'status' => true,
    //                     'data' => [
    //                         'title' => $practice->title,
    //                         'uuid'=>$ongoingPractice->uuid,
    //                         'answer'=>$ongoingPractice->answer,
    //                         'questions' => json_decode($ongoingPractice->questions),
    //                         'duration' => $remainingDuration . " mins",
    //                         'points' => $ongoingPractice->points,
    //                     ]
    //                 ], 200);
    //             }
    //         }
    
    //         // Calculate practice duration and points
    //         $duration = (int) round($practice->total_time / 60, 2);
    //         $points = $practice->point_mode == "manual" ? $practice->points : $practice->total_marks;
    
    //         // Prepare structured response data for questions
    //         $questionsData = [];
    //         $correctAnswers = [];
    //         foreach ($practice->practiceQuestions as $practiceQuestion) {
    //             $question = $practiceQuestion->questions;
    //             $options = $question->options ? json_decode($question->options, true) : [];
    
    //             if ($question->type == "MTF" && !empty($question->answer)) {
    //                 $matchOption = json_decode($question->answer, true);
    //                 shuffle($matchOption);
    //                 $options = array_merge($options, $matchOption);
    //             }
    
    //             // Customize question display for different types
    //             $questionText = $question->question;
    //             if ($question->type == "FIB") {
    //                 $questionText = preg_replace('/##(.*?)##/', '<span class="border-b border-black inline-block w-[150px] text-center" style="width:150px;"></span>', $question->question);
    //                 $options = [is_array(json_decode($question->answer, true)) ? count(json_decode($question->answer, true)) : 0];
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
    //                 'default_marks' => $practice->point_mode == "manual" ? $practice->points : $question->default_marks
    //             ];
    //         }
    
    //         // Start practice result tracking
    //         $startTime = now();
    //         $endTime = $startTime->copy()->addMinutes($duration); 
    
    //         $praticeResult = PracticeSetResult::create([
    //             'practice_sets_id' => $practice->id,  // Update to singular form
    //             'uuid' => (string) Str::uuid(),
    //             'subcategory_id' => $practice->subcategory_id,
    //             'user_id' => $user->id,
    //             'questions' => json_encode($questionsData, true),
    //             'correct_answers' => json_encode($correctAnswers, true),
    //             'start_time' => $startTime,
    //             'end_time' => $endTime,
    //             'exam_duration' => $duration,
    //             'point' => $points,
    //             'total_question' => count($questionsData),
    //             'status' => 'ongoing',
    //         ]);
    
    //         $remainingDuration = now()->diffInMinutes($praticeResult->end_time);
 
    //         return response()->json([
    //             'status' => true,
    //             'data' => [
    //                 'title' => $practice->title,
    //                 'uuid'=>$praticeResult->uuid,
    //                 'questions' => json_decode($praticeResult->questions),
    //                 'duration' => $remainingDuration . " mins",
    //                 'points' => $praticeResult->point
    //             ]
    //         ], 200);
    
    //     } catch (\Throwable $th) {
    //         return response()->json(['status' => false, 'error' => 'Internal Server Error: ' . $th->getMessage()], 500);
    //     }
    // }


    public function playPracticeSet(Request $request, $slug)
    {
        try {
            // Validate incoming request data
            $request->validate([
                'category' => 'required|integer',
            ]);

            // Fetch the practice along with related questions in one query
            $practice = PracticeSet::with([
                    'practiceQuestions.questions' => function($query) {
                        $query->select('id', 'question', 'default_marks', 'watch_time', 'type', 'options', 'answer');
                    }
                ])
                ->select(
                    'practice_sets.id',
                    'practice_sets.title',
                    'practice_sets.description',
                    'practice_sets.slug',
                    'practice_sets.subcategory_id',
                    'practice_sets.status',
                    'practice_sets.allow_reward',
                    'practice_sets.reward_popup',
                    'practice_sets.point_mode',
                    'practice_sets.points',
                    'practice_sets.is_free',
                    DB::raw('SUM(questions.default_marks) as total_marks'),
                    DB::raw('SUM(COALESCE(questions.watch_time, 0)) as total_time')
                )
                ->leftJoin('practice_set_questions', 'practice_sets.id', '=', 'practice_set_questions.practice_set_id')
                ->leftJoin('questions', 'practice_set_questions.question_id', '=', 'questions.id')
                ->where('practice_sets.slug', $slug)
                ->where('practice_sets.subcategory_id', $request->category)
                ->where('practice_sets.status', 1)
                ->where('questions.status', 1)
                ->groupBy('practice_sets.id', 'practice_sets.title', 'practice_sets.description', 'practice_sets.slug', 'practice_sets.subcategory_id', 'practice_sets.status', 'practice_sets.allow_reward', 'practice_sets.reward_popup', 'practice_sets.point_mode', 'practice_sets.points', 'practice_sets.is_free')
                ->first();

            // If practice not found
            if (!$practice) {
                return response()->json(['status' => false, 'error' => 'Practice Set not found'], 404);
            }

            // Get the authenticated user
            $user = $request->attributes->get('authenticatedUser');

            // Fetch all completed practice results
            $checkOngoingResult = PracticeSetResult::where('user_id', $user->id)
                ->where('practice_sets_id', $practice->id)
                ->where('status', 'complete')
                ->get();

            // Check for ongoing practice set
            $ongoingPractice = PracticeSetResult::where('user_id', $user->id)
                ->where('practice_sets_id', $practice->id)
                ->where('status', 'ongoing')
                ->latest('created_at')
                ->first();

            if ($ongoingPractice) {
                $remainingDuration = max(now()->diffInMinutes($ongoingPractice->end_time), 0);

                if ($ongoingPractice->end_time->isPast()) {
                    $ongoingPractice->update(['status' => 'complete']);
                    return response()->json(['status' => true, 'message' => 'Practice Set Timed Out', 'data' => ['uuid' => $ongoingPractice->uuid]]);
                } else {
                    return response()->json([
                        'status' => true,
                        'data' => [
                            'title' => $practice->title,
                            'uuid' => $ongoingPractice->uuid,
                            'answer' => $ongoingPractice->answer,
                            'questions' => json_decode($ongoingPractice->questions),
                            'duration' => $remainingDuration . " mins",
                            'points' => $ongoingPractice->points,
                        ]
                    ], 200);
                }
            }

            // Calculate practice duration and points
            $duration = (int) round($practice->total_time / 60, 2);
            $points = $practice->point_mode == "manual" ? $practice->points : $practice->total_marks;

            // Prepare structured response data for questions and correct answers
            $questionsData = [];
            $correctAnswers = [];
            
            foreach ($practice->practiceQuestions as $practiceQuestion) {
                $question = $practiceQuestion->questions;
                $options = $question->options ? json_decode($question->options, true) : [];

                $formattedAnswer = null;

                // Handling each question type and formatting the answer
                switch ($question->type) {
                    case "MSA":
                        $formattedAnswer = (int) $question->answer; // Assuming single answer as an integer index
                        break;
                    case "MMA":
                        $formattedAnswer = json_decode($question->answer, true); // Array of correct indices
                        break;
                    case "TOF":
                        $formattedAnswer = (int) $question->answer; // Assuming True/False as integer (1 or 2)
                        break;
                    case "SAQ":
                        $formattedAnswer = $question->answer; // Assuming answer as string for short answers
                        break;
                    case "MTF":
                        $formattedAnswer = json_decode($question->answer, true); // Key-value pair for matching
                        break;
                    case "ORD":
                        $formattedAnswer = json_decode($question->answer, true); // Array of indices for ordering
                        break;
                    case "FIB":
                        $formattedAnswer = json_decode($question->answer, true); // Array of correct answers for blanks
                        break;
                    case "EMQ":
                        $formattedAnswer = json_decode($question->answer, true); // Array of correct options
                        break;
                }

                // Customize question display for different types
                $questionText = $question->question;
                if ($question->type == "FIB") {
                    $questionText = preg_replace('/##(.*?)##/', '<span class="border-b border-black inline-block w-[150px] text-center"></span>', $question->question);
                    $options = [is_array(json_decode($question->answer, true)) ? count(json_decode($question->answer, true)) : 0];
                } elseif ($question->type == "EMQ") {
                    $questionText = json_decode($question->question, true);
                }

                // Add question data
                $questionsData[] = [
                    'id' => $question->id,
                    'type' => $question->type,
                    'question' => $questionText,
                    'options' => $options
                ];

                // Add correct answer info
                $correctAnswers[] = [
                    'id' => $question->id,
                    'correct_answer' => $formattedAnswer,
                    'default_marks' => $practice->point_mode == "manual" ? $practice->points : $question->default_marks
                ];
            }

            // Start practice result tracking
            $startTime = now();
            $endTime = $startTime->copy()->addMinutes($duration);

            $practiceResult = PracticeSetResult::create([
                'practice_sets_id' => $practice->id,
                'uuid' => (string) Str::uuid(),
                'subcategory_id' => $practice->subcategory_id,
                'user_id' => $user->id,
                'questions' => json_encode($questionsData, true),
                'correct_answers' => json_encode($correctAnswers, true),
                'start_time' => $startTime,
                'end_time' => $endTime,
                'exam_duration' => $duration,
                'point' => $points,
                'total_question' => count($questionsData),
                'status' => 'ongoing',
            ]);

            $remainingDuration = now()->diffInMinutes($practiceResult->end_time);

            return response()->json([
                'status' => true,
                'data' => [
                    'title' => $practice->title,
                    'uuid' => $practiceResult->uuid,
                    'questions' => json_decode($practiceResult->questions),
                    'duration' => $remainingDuration . " mins",
                    'points' => $practiceResult->point
                ]
            ], 200);

        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'error' => 'Internal Server Error: ' . $th->getMessage()], 500);
        }
    }

    // public function playQuiz(Request $request, $slug)
    // {
    //     try {
    //         // Validate incoming request data
    //         $request->validate([
    //             'category' => 'required|integer',
    //         ]);

    //         // Fetch the quiz along with related questions in one query
    //         $quiz = Quizze::with([
    //                 'quizQuestions.questions' => function($query) {
    //                     $query->select('id', 'question', 'default_marks', 'watch_time', 'type', 'options', 'answer');
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
    //                 'quizzes.negative_marking',
    //                 'quizzes.negative_marking_type',
    //                 'quizzes.negative_marks',
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
    //                 'quizzes.id', 'quizzes.title', 'quizzes.description', 'quizzes.pass_percentage',
    //                 'quizzes.slug', 'quizzes.subcategory_id', 'quizzes.status', 'quizzes.duration_mode',
    //                 'quizzes.point_mode', 'quizzes.duration', 'quizzes.point', 'quizzes.shuffle_questions',
    //                 'quizzes.question_view', 'quizzes.disable_finish_button', 'quizzes.negative_marking',
    //                 'quizzes.negative_marking_type', 'quizzes.negative_marks'
    //             )
    //             ->first();

    //         // If quiz not found
    //         if (!$quiz) {
    //             return response()->json(['status' => false, 'error' => 'Quiz not found'], 404);
    //         }

    //         // Get the authenticated user
    //         $user = $request->attributes->get('authenticatedUser');

    //         // Fetch all completed quiz results
    //         $checkOngoingResult = QuizResult::where('user_id', $user->id)
    //             ->where('quiz_id', $quiz->id)
    //             ->where('status', 'complete')
    //             ->get();

    //         // Restrict quiz attempts based on the configured limit
    //         if ($quiz->restrict_attempts == 1 && $quiz->total_attempts <= $checkOngoingResult->count()) {
    //             return response()->json(['status' => false, 'error' => 'Maximum Attempt Reached'], 403);
    //         }

    //         // Check for ongoing quiz
    //         $ongoingQuiz = QuizResult::where('user_id', $user->id)
    //             ->where('quiz_id', $quiz->id)
    //             ->where('status', 'ongoing') // Correct the status check
    //             ->latest('created_at')
    //             ->first();

    //         if ($ongoingQuiz) {
    //             $remainingDuration = now()->diffInMinutes($ongoingQuiz->end_time); // Ensure no negative duration

    //             if ($ongoingQuiz->end_time->isPast()) {
    //                 // If time has passed, mark the quiz as complete
    //                 $ongoingQuiz->update(['status' => 'complete']);
    //                 $data = [
    //                     'uuid'=>$ongoingQuiz->uuid,
    //                 ];
    //                 return response()->json(['status' => true, 'message' => 'Quiz Timed Out','data'=>$data]);
    //             } else {
    //                 // Return ongoing quiz details
    //                 return response()->json([
    //                     'status' => true,
    //                     'data' => [
    //                         'title' => $quiz->title,
    //                         'uuid'=>$ongoingQuiz->uuid,
    //                         'questions' => json_decode($ongoingQuiz->questions),
    //                         'duration' => $remainingDuration . " mins",
    //                         'points' => $ongoingQuiz->point,
    //                         'question_view' => $quiz->question_view == 1 ? "enable" : "disable",
    //                         'finish_button' => $quiz->disable_finish_button == 1 ? "enable" : "disable"
    //                     ]
    //                 ], 200);
    //             }
    //         }

    //         // Calculate quiz duration and points
    //         $duration = (int) ($quiz->duration_mode == "manual" && $quiz->duration > 0 ? $quiz->duration : round($quiz->total_time / 60, 2));
    //         $points = $quiz->point_mode == "manual" ? $quiz->point : $quiz->total_marks;

    //         // Prepare structured response data for questions
    //         $questionsData = [];
    //         $correctAnswers = [];
    //         foreach ($quiz->quizQuestions as $quizQuestion) {
    //             $question = $quizQuestion->questions;
    //             $options = $question->options ? json_decode($question->options, true) : [];

    //             if ($question->type == "MTF" && !empty($question->answer)) {
    //                 $matchOption = json_decode($question->answer, true);
    //                 shuffle($matchOption);
    //                 $options = array_merge($options, $matchOption);
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

    //             // Add correct answer info based on question type
    //             switch ($question->type) {
    //                 case 'MSA':
    //                 case 'MMA':
    //                     // Store as JSON array
    //                     $correctAnswers[] = [
    //                         'id' => $question->id,
    //                         'correct_answer' => json_decode($question->answer, true), // Store as array
    //                         'default_marks' => $quiz->point_mode == "manual" ? $quiz->point : $question->default_marks
    //                     ];
    //                     break;
    //                 case 'TOF':
    //                     // Store as integer
    //                     $correctAnswers[] = [
    //                         'id' => $question->id,
    //                         'correct_answer' => (int) $question->answer, // Store as integer
    //                         'default_marks' => $quiz->point_mode == "manual" ? $quiz->point : $question->default_marks
    //                     ];
    //                     break;
    //                 case 'SAQ':
    //                     // Store answer directly
    //                     $correctAnswers[] = [
    //                         'id' => $question->id,
    //                         'correct_answer' => $question->answer, // Store as string
    //                         'default_marks' => $quiz->point_mode == "manual" ? $quiz->point : $question->default_marks
    //                     ];
    //                     break;
    //                 case 'MTF':
    //                     // Store as JSON object
    //                     $correctAnswers[] = [
    //                         'id' => $question->id,
    //                         'correct_answer' => json_decode($question->answer, true), // Store as associative array
    //                         'default_marks' => $quiz->point_mode == "manual" ? $quiz->point : $question->default_marks
    //                     ];
    //                     break;
    //                 case 'ORD':
    //                     // Store as JSON array
    //                     $correctAnswers[] = [
    //                         'id' => $question->id,
    //                         'correct_answer' => json_decode($question->answer, true), // Store as array of indices
    //                         'default_marks' => $quiz->point_mode == "manual" ? $quiz->point : $question->default_marks
    //                     ];
    //                     break;
    //                 case 'FIB':
    //                     // Store as array
    //                     $correctAnswers[] = [
    //                         'id' => $question->id,
    //                         'correct_answer' => json_decode($question->answer, true), // Store as array
    //                         'default_marks' => $quiz->point_mode == "manual" ? $quiz->point : $question->default_marks
    //                     ];
    //                     break;
    //                 case 'EMQ':
    //                     // Store as JSON array
    //                     $correctAnswers[] = [
    //                         'id' => $question->id,
    //                         'correct_answer' => json_decode($question->answer, true), // Store as array
    //                         'default_marks' => $quiz->point_mode == "manual" ? $quiz->point : $question->default_marks
    //                     ];
    //                     break;
    //                 default:
    //                     // Handle unknown question type if necessary
    //                     break;
    //             }
    //         }

    //         // Shuffle questions if enabled
    //         if ($quiz->shuffle_questions == 1) {
    //             shuffle($questionsData);
    //         }

    //         // Start quiz result tracking
    //         $startTime = now();
    //         $endTime = $startTime->copy()->addMinutes($duration);

    //         $quizResult = QuizResult::create([
    //             'quiz_id' => $quiz->id,
    //             'uuid' => uniqid(), // Generate unique identifier
    //             'subcategory_id' => $quiz->subcategory_id,
    //             'user_id' => $user->id,
    //             'questions' => json_encode($questionsData, true),
    //             'correct_answers' => json_encode($correctAnswers, true), // Store as JSON
    //             'start_time' => $startTime,
    //             'end_time' => $endTime,
    //             'exam_duration' => $duration,
    //             'point' => $points,
    //             'negative_marking' => $quiz->negative_marking,
    //             'negative_marking_type' => $quiz->negative_marking_type,
    //             'negative_marks' => $quiz->negative_marks,
    //             'pass_percentage' => $quiz->pass_percentage,
    //             'total_question' => count($questionsData),
    //             'status' => 'ongoing',
    //         ]);

    //         $remainingDuration = now()->diffInMinutes($quizResult->end_time);

    //         return response()->json([
    //             'status' => true,
    //             'data' => [
    //                 'title' => $quiz->title,
    //                 'uuid' => $quizResult->uuid,
    //                 'questions' => json_decode($quizResult->questions),
    //                 'duration' => $remainingDuration . " mins",
    //                 'points' => $quizResult->point,
    //                 'question_view' => $quiz->question_view == 1 ? "enable" : "disable",
    //                 'finish_button' => $quiz->disable_finish_button == 1 ? "enable" : "disable"
    //             ]
    //         ], 200);

    //     } catch (\Throwable $th) {
    //         return response()->json(['status' => false, 'error' => 'Internal Server Error: ' . $th->getMessage()], 500);
    //     }
    // }


    public function allQuestion(){
        try {
            $banner = Question::select('question','options','answer','type')->get();
            return response()->json(['status'=> true,'data' => $banner], 201);
        } catch (\Throwable $th) {
            return response()->json(['status'=> false,'error' => $th->getMessage()], 500);
        }
    }

    public function finishPracticeSet(){
        // USER RESPONSE
        $user_answer = $request->input('answers');
        $user = $request->attributes->get('authenticatedUser');
    
        // Fetch quiz result by UUID and user ID
        $practiceSetResult = PracticeSetResult::where('uuid', $uuid)->where('user_id', $user->id)->firstOrFail();
        if (!$practiceSetResult) {
            return response()->json([
                'status' => false,
                'message' => "Invalid Practice Set"
            ]);
        }
    
        $score = 0;
        $correctAnswer = 0;
        $incorrect = 0;
        $totalMarks = 0;
        $incorrectMarks = 0;
    
        // Total marks should be fixed in manual mode
        $totalMarks = $practiceSetResult->point_type == "manual" 
            ? $practiceSetResult->point * count($user_answer) // Manual mode total marks
            : 0; // For default mode, we accumulate question marks below
    
        foreach ($user_answer as $answer) {
            $question = Question::find($answer['id']);
            if (!$question) {
                $incorrect += 1;
                continue;
            }
    
            // Handle different question types
            $isCorrect = false;
            $userAnswer = $answer['answer'];
    
            // In default mode, accumulate total possible marks
            if ($quizResult->point_type != "manual") {
                $totalMarks += $question->default_marks;
            }
    
            // Check correctness based on question type
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
                $answers = json_decode($question->options);
                $isCorrect = in_array($userAnswer, $answers);
            } elseif ($question->type == 'FIB') {
                $correctAnswers = json_decode($question->answer, true);
                sort($correctAnswers);
                sort($userAnswer);
                $isCorrect = $userAnswer == $correctAnswers;
            } elseif ($question->type == 'MTF') {
                $correctAnswers = json_decode($question->answer, true);
                foreach ($correctAnswers as $key => $value) {
                    if ($userAnswer[$key] != $value) {
                        $isCorrect = false;
                        break;
                    }
                }
                $isCorrect = true;
            } elseif ($question->type == 'ORD') {
                $correctAnswers = json_decode($question->answer, true);
                $isCorrect = $userAnswer == $correctAnswers;
            } elseif ($question->type == 'EMQ') {
                $correctAnswers = json_decode($question->answer, true);
                sort($userAnswer);
                sort($correctAnswers);
                $isCorrect = $userAnswer == $correctAnswers;
            }
    
            if ($isCorrect) {
                $score += $quizResult->point_type == "manual" ? $quizResult->point : $question->default_marks;
                $correctAnswer += 1;
            } else {
                $incorrect += 1;
                $incorrectMarks += $question->default_marks;
            }
        }
    
        // Apply negative marking for incorrect answers
        if ($quizResult->negative_marking == 1) {
            if ($quizResult->negative_marking_type == "fixed") {
                $score = max(0, $score - $quizResult->negative_marking_value * $incorrect);
            } elseif ($quizResult->negative_marking_type == "percentage") {
                $negativeMarks = ($quizResult->negative_marking_value / 100) * $incorrectMarks;
                $score = max(0, $score - $negativeMarks);
            }
        }
    
        // Calculate the student's percentage AFTER applying negative marking
        $studentPercentage = ($totalMarks > 0) ? ($score / $totalMarks) * 100 : 0;
    
        // Determine pass or fail
        $studentStatus = ($studentPercentage >= $quizResult->pass_percentage) ? 'PASS' : 'FAIL';
    
        // Update quiz result with correct/incorrect answers and student percentage
        $quizResult->answers = json_encode($user_answer, true);
        $quizResult->incorrect_answer = $incorrect;
        $quizResult->correct_answer = $correctAnswer;
        $quizResult->student_percentage = $studentPercentage;
        $quizResult->save();
    
        // Return results
        return response()->json([
            'status' => true,
            'score' => $score,
            'correct_answer' => $correctAnswer,
            'incorrect_answer' => $incorrect,
            'student_status' => $studentStatus,
            'student_percentage' => $studentPercentage
        ]);
    }
    
}