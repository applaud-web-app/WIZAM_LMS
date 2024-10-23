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
use App\Models\User;
use Illuminate\Support\Str; 
use Carbon\Carbon;
use App\Models\Question;
use App\Models\Subscription;
use App\Models\Plan;


class QuizController extends Controller
{

    public function playQuiz(Request $request, $slug)
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
                    'quizzes.is_free',
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
                    'quizzes.negative_marking_type', 'quizzes.negative_marks',  'quizzes.is_free',
                )
                ->first();
    
            // If quiz not found
            if (!$quiz) {
                return response()->json(['status' => false, 'error' => 'Quiz not found'], 404);
            }

            // FOR PAID QUIZ ONLY
            if($quiz->is_free == 0){
                $type = "quizzes";
    
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
                    // return response()->json(['status' => true, 'data' => $subscription], 200);
                } else {
                    // Fetch the allowed features for this plan
                    $allowed_features = json_decode($plan->features, true);
        
                    // Check if the requested feature type is in the allowed features
                    if (in_array($type, $allowed_features)) {
                        // return response()->json(['status' => true, 'data' => $subscription], 200);
                    } else {
                        return response()->json(['status' => false, 'error' => 'Feature not available in your plan. Please upgrade your subscription.'], 403);
                    }
                }
            }
    
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
                    $data = [
                        'uuid'=>$ongoingQuiz->uuid,
                    ];
                    return response()->json(['status' => true, 'message' => 'Quiz Timed Out','data'=>$data]);
                } else {
                    // Return ongoing quiz details
                    return response()->json([
                        'status' => true,
                        'data' => [
                            'title' => $quiz->title,
                            'uuid'=>$ongoingQuiz->uuid,
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
            $correctAnswers = [];
            foreach ($quiz->quizQuestions as $quizQuestion) {
                $question = $quizQuestion->questions;
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
                } elseif ($question->type == "EMQ") {
                    $questionText = json_decode($question->question, true);
                }
    
                $questionsData[] = [
                    'id' => $question->id,
                    'type' => $question->type,
                    'question' => $questionText,
                    'options' => $options
                ];

                // Add correct answer info
                $correctAnswers[] = [
                    'id' => $question->id,
                    'correct_answer' => $question->answer,  // Use answer field
                    'default_marks' => $quiz->point_mode == "manual" ? $quiz->point : $question->default_marks
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
                'correct_answers' => json_encode($correctAnswers,true),
                'start_time' => $startTime,
                'end_time' => $endTime,
                'exam_duration' => $duration,
                'point_type' => $quiz->point_mode,
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
                    'uuid'=>$quizResult->uuid,
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
    

    // FINAL OLD
    // public function finishQuiz(Request $request, $uuid)
    // {
    //     // USER RESPONSE
    //     $user_answer = $request->input('answers');
    //     $user = $request->attributes->get('authenticatedUser');

    //     // Fetch quiz result by UUID and user ID
    //     $quizResult = QuizResult::where('uuid', $uuid)->where('user_id', $user->id)->firstOrFail();
    //     if (!$quizResult) {
    //         return response()->json([
    //             'status' => false,
    //             'message' => "Invalid Quiz"
    //         ]);
    //     }

    //     $score = 0;
    //     $correctAnswer = 0;
    //     $incorrect = 0;
    //     $totalMarks = 0;
    //     $incorrectMarks = 0; 

    //     foreach ($user_answer as $answer) {
    //         $question = Question::find($answer['id']);
    //         if (!$question) {
    //             $incorrect += 1;
    //             continue;
    //         }

    //         // Handle different question types
    //         $isCorrect = false; // Track if the answer is correct
    //         $userAnswer = $answer['answer'];
    //         $totalMarks += $quizResult->point_type == "manual" ? $quizResult->point : $question->default_marks;

    //         // Check correctness based on question type
    //         if ($question->type == 'MSA') {
    //             $isCorrect = $question->answer == $userAnswer;
    //         } elseif ($question->type == 'MMA') {
    //             $correctAnswers = json_decode($question->answer, true);
    //             sort($correctAnswers);
    //             sort($userAnswer);
    //             $isCorrect = $userAnswer == $correctAnswers;
    //         } elseif ($question->type == 'TOF') {
    //             $isCorrect = $userAnswer == $question->answer;
    //         } elseif ($question->type == 'SAQ') {
    //             $answers = json_decode($question->options);
    //             $isCorrect = in_array($userAnswer, $answers);
    //         } elseif ($question->type == 'FIB') {
    //             $correctAnswers = json_decode($question->answer, true);
    //             sort($correctAnswers);
    //             sort($userAnswer);
    //             $isCorrect = $userAnswer == $correctAnswers;
    //         } elseif ($question->type == 'MTF') {
    //             $correctAnswers = json_decode($question->answer, true);
    //             foreach ($correctAnswers as $key => $value) {
    //                 if ($userAnswer[$key] != $value) {
    //                     $isCorrect = false;
    //                     break;
    //                 }
    //             }
    //             $isCorrect = true;
    //         } elseif ($question->type == 'ORD') {
    //             $correctAnswers = json_decode($question->answer, true);
    //             $isCorrect = $userAnswer == $correctAnswers;
    //         } elseif ($question->type == 'EMQ') {
    //             $correctAnswers = json_decode($question->answer, true);
    //             sort($userAnswer);
    //             sort($correctAnswers);
    //             $isCorrect = $userAnswer == $correctAnswers;
    //         }
    //         if ($isCorrect) {
    //             $score += $question->default_marks;
    //             $correctAnswer += 1;
    //         } else {
    //             $incorrect += 1;
    //             $incorrectMarks += $question->default_marks; // Add marks of incorrect answers
    //         }
    //     }

    //     // Apply negative marking for incorrect answers
    //     if ($quizResult->negative_marking == 1) {
    //         if ($quizResult->negative_marking_type == "fixed") {
    //             // Deduct a fixed value from the total score
    //             $score = max(0, $score - $quizResult->negative_marking_value);
    //         } elseif ($quizResult->negative_marking_type == "percentage") {
    //             // Deduct a percentage of the total incorrect marks
    //             $negativeMarks = ($quizResult->negative_marking_value / 100) * $incorrectMarks;
    //             $score = max(0, $score - $negativeMarks);
    //         }
    //     }

    //     // CALCULATE STUDENT PERCENTAGE
    //     $studentPercentage = ($totalMarks > 0) ? ($score / $totalMarks) * 100 : 0;

    //     // Determine pass or fail
    //     $studentStatus = ($studentPercentage >= $quizResult->pass_percentage) ? 'PASS' : 'FAIL';

    //     // Update quiz result with correct/incorrect answers and student percentage
    //     $quizResult->answers = json_encode($user_answer,true);
    //     $quizResult->incorrect_answer = $incorrect;
    //     $quizResult->correct_answer = $correctAnswer;
    //     $quizResult->student_percentage = $studentPercentage;
    //     $quizResult->save();

    //     // Return results
    //     return response()->json([
    //         'status' => true,
    //         'score' => $score,
    //         'correct_answer' => $correctAnswer,
    //         'incorrect_answer' => $incorrect,
    //         'student_status' => $studentStatus,
    //         'student_percentage' => $studentPercentage
    //     ]);
        
    // }

    public function finishQuiz(Request $request, $uuid)
    {
        // USER RESPONSE
        $user_answer = $request->input('answers');
        $user = $request->attributes->get('authenticatedUser');
    
        // Fetch quiz result by UUID and user ID
        $quizResult = QuizResult::where('uuid', $uuid)->where('user_id', $user->id)->firstOrFail();
        if (!$quizResult) {
            return response()->json([
                'status' => false,
                'message' => "Invalid Quiz"
            ]);
        }
    
        $score = 0;
        $correctAnswer = 0;
        $incorrect = 0;
        $totalMarks = 0;
        $incorrectMarks = 0;
    
        // Total marks should be fixed in manual mode
        $totalMarks = $quizResult->point_type == "manual" ? $quizResult->point * count($user_answer) : 0; 
    
        foreach ($user_answer as $answer) {
            if (!isset($answer['id'])) {
                $incorrect += 1;
                continue;
            }
            $question = Question::find($answer['id']);
            if (!$question) {
                $incorrect += 1;
                continue;
            }

            // Handle different question types
            $isCorrect = false;
            if (isset($answer['answer'])) {
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
                    $isCorrect = false;
                    if (is_string($userAnswer) && is_array($question->options)) {
                        $answers = json_decode($question->options);
                        foreach ($answers as $option) {
                            // Convert both the option and the user answer to lowercase and trim them
                            $sanitizedOption = strtolower(trim(strip_tags($option)));
                            $sanitizedUserAnswer = strtolower(trim(strip_tags($userAnswer)));

                            // Check if the sanitized option matches the sanitized user answer
                            if ($sanitizedUserAnswer == $sanitizedOption) {
                                $isCorrect = true;
                                break;  // Exit the loop once a match is found
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
                    $isCorrect = true; // Assume correct until proven otherwise
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
                    if (isset($question->default_marks)) {
                        $incorrectMarks += $question->default_marks;
                    }
                }
            }else{
                $incorrect += 1;
                if (isset($question->default_marks)) {
                    $incorrectMarks += $question->default_marks;
                }
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
        $quizResult->status = "complete";
        $quizResult->updated_at = now();
        $quizResult->answers = json_encode($user_answer, true);
        $quizResult->incorrect_answer = $incorrect;
        $quizResult->correct_answer = $correctAnswer;
        $quizResult->student_percentage = round($studentPercentage,2);
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


    // public function quizResult(Request $request, $uuid)
    // {
    //    try {
    //         $user = $request->attributes->get('authenticatedUser');

    //         $quizResult = QuizResult::with('quiz')->where('uuid',$uuid)->where('user_id',$user->id)->first();
    //         if($quizResult){
    //             $leaderBoard = [];
    //             if(isset($quizResult->quiz) && $quizResult->quiz->leaderboard == 1){
    //                 $userQuiz = QuizResult::with('user')->where('quiz_id',$quizResult->quiz_id)->orderby('student_percentage','DESC')->take(10)->get();

    //                 foreach ($userQuiz as $userData) {
    //                     if (isset($userData->user)) {
    //                         $leaderBoard[] = [
    //                             "username"=> $users->user->name,
    //                             "score"=>  $userQuiz->student_percentage,
    //                             "status"=>  $userQuiz->student_percentage >= $userQuiz->pass_percentage ? "PASS" : "FAIL"
    //                         ];
    //                     }
    //                 }
    //             }

    //             $result = [
    //                 'correct' => $quizResult->correct_answer,
    //                 'incorrect' => $quizResult->incorrect_answer,
    //                 'skippedr' => $quizResult->total_question - ($quizResult->correct_answer+$quizResult->incorrect_answer),
    //                 'marks' => $quizResult->student_percentage,
    //                 'status'=> $quizResult->student_percentage >= $quizResult->pass_percentage ? "PASS" : "FAIL",
    //             ];

    //             // EXAM (HERE IS THE CODE WHICH SHOW WHICH ANSWER IS CORRECT AND WHICH ANSWER IS WRONG )
    //             $exam = [];

    //             // Return results
    //             return response()->json([
    //                 'status' => true,
    //                 'result' => $result,
    //                 'exam_preview'=> $exam,
    //                 'leaderBoard'=> $leaderBoard
    //             ]);
    //         }
    //    } catch (\Throwable $th) {
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'Something Went Wrong'
    //         ]);
    //    }

    // }


    // public function quizResult(Request $request, $uuid)
    // {
    //     try {
    //         $user = $request->attributes->get('authenticatedUser');

    //         $quizResult = QuizResult::with('quiz')->where('uuid', $uuid)->where('user_id', $user->id)->first();
    //         if ($quizResult) {
    //             // Build leaderboard
    //             $leaderBoard = [];
    //             if (isset($quizResult->quiz) && $quizResult->quiz->leaderboard == 1) {
    //                 $userQuiz = QuizResult::with('user')
    //                     ->where('quiz_id', $quizResult->quiz_id)
    //                     ->orderby('student_percentage', 'DESC')
    //                     ->take(10)
    //                     ->get();

    //                 foreach ($userQuiz as $userData) {
    //                     if (isset($userData->user)) {
    //                         $leaderBoard[] = [
    //                             "username" => $userData->user->name,
    //                             "score" => $userData->student_percentage,
    //                             "status" => $userData->student_percentage >= $userData->pass_percentage ? "PASS" : "FAIL",
    //                         ];
    //                     }
    //                 }
    //             }

    //             // Build result
    //             $result = [
    //                 'correct' => $quizResult->correct_answer,
    //                 'incorrect' => $quizResult->incorrect_answer,
    //                 'skipped' => $quizResult->total_question - ($quizResult->correct_answer + $quizResult->incorrect_answer),
    //                 'marks' => $quizResult->student_percentage,
    //                 'status' => $quizResult->student_percentage >= $quizResult->pass_percentage ? "PASS" : "FAIL",
    //             ];

    //             // Exam (Compare user answers with correct answers and create a detailed review)
    //             // $exam = [];
    //             // foreach ($quizResult->quiz->questions as $question) {
    //             //     $exam[] = [
    //             //         'question_id' => $question->id,
    //             //         'question_text' => $question->question,
    //             //         'correct_answer' => $question->answer,
    //             //         'user_answer' => $quizResult->answers[$question->id] ?? null,
    //             //         'is_correct' => ($quizResult->answers[$question->id] ?? null) == $question->answer,
    //             //     ];
    //             // }

    //             $exam = [];
    //             $questionBox = json_decode($quizResult->questions);
    //             $correct_answers = json_decode($quizResult->correct_answers);
    //             $userAnswers = json_decode($quizResult->answers);

    //             return [
    //                 'questionBox'=>$questionBox,
    //                 'userAnswers'=>$userAnswers,
    //                 'correct_answers'=>$correct_answers,
    //             ];

    //             foreach ($questionBox as $question) {
    //                 $userAnswer = $userAnswers[$question->id] ?? null;
    //                 $correctAnswer = $question['option'];
    //                 $isCorrect = false;

    //                 // Handle different question types
    //                 switch ($question['type']) {
    //                     case 'FIB': // Fill in the Blanks
    //                         // Check if userAnswer is same as correct answer
    //                         $isCorrect = $userAnswer === $correctAnswer;
    //                         break;

    //                     case 'MSA': // Multiple Selection Answer
    //                         // Check if userAnswer (which could be an array of selected choices) matches correctAnswer
    //                         $isCorrect = is_array($userAnswer) && !array_diff($userAnswer, $correctAnswer);
    //                         break;

    //                     case 'MMA': // Multiple Match Answer
    //                         // Assuming correctAnswer and userAnswer are arrays, compare them
    //                         $isCorrect = $userAnswer === $correctAnswer;
    //                         break;

    //                     case 'TOF': // True/False
    //                         // Check if the true/false answer matches
    //                         $isCorrect = $userAnswer == $correctAnswer;
    //                         break;

    //                     case 'MTF': // Match the Following
    //                         // Compare match answers by checking each pair
    //                         $isCorrect = $userAnswer === $correctAnswer;
    //                         break;

    //                     case 'ORD': // Ordering
    //                         // Check if the ordering is correct
    //                         $isCorrect = $userAnswer === $correctAnswer;
    //                         break;

    //                     case 'EMQ': // Extended Matching Questions
    //                         // Compare EMQ answers
    //                         $isCorrect = $userAnswer === $correctAnswer;
    //                         break;

    //                     case 'SAQ': // Short Answer Question
    //                         // Check if user's short answer matches the correct one
    //                         $isCorrect = $userAnswer === $correctAnswer;
    //                         break;
    //                 }

    //                 // Append the result for this question to the exam array
    //                 $exam[] = [
    //                     'question_id' => $question['id'],
    //                     'question_text' => $question['question'],
    //                     'correct_answer' => $correctAnswer,
    //                     'user_answer' => $userAnswer,
    //                     'is_correct' => $isCorrect,
    //                 ];
    //             }

    //             $quiz = [
    //                 'title'=>$quizResult->quiz->title,
    //                 'duration'=>$quizResult->exam_duration,
    //             ];

    //             return response()->json([
    //                 'status' => true,
    //                 'quiz'=>$quiz,
    //                 'result' => $result,
    //                 'exam_preview' => $exam,
    //                 'leaderBoard' => $leaderBoard,
    //             ]);
    //         }

    //     } catch (\Throwable $th) {
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'Something went wrong :'. $th->getMessage(),
    //         ]);
    //     }
    // }

    // WORKING BUT REPLACE WITH NEW LOGIC
    // public function quizResult(Request $request, $uuid)
    // {
    //     try {
    //         $user = $request->attributes->get('authenticatedUser');
    
    //         $quizResult = QuizResult::with('quiz')->where('uuid', $uuid)->where('user_id', $user->id)->first();
    //         if ($quizResult) {
    //             // Build leaderboard
    //             $leaderBoard = [];
    //             if (isset($quizResult->quiz) && $quizResult->quiz->leaderboard == 1) {
    //                 $userQuiz = QuizResult::with('user')
    //                     ->where('quiz_id', $quizResult->quiz_id)
    //                     ->orderby('student_percentage', 'DESC')
    //                     ->take(10)
    //                     ->get();
    
    //                 foreach ($userQuiz as $userData) {
    //                     if (isset($userData->user)) {
    //                         $leaderBoard[] = [
    //                             "username" => $userData->user->name,
    //                             "score" => $userData->student_percentage,
    //                             "status" => $userData->student_percentage >= $userData->pass_percentage ? "PASS" : "FAIL",
    //                         ];
    //                     }
    //                 }
    //             }

    //             $openTime = Carbon::parse($quizResult->created_at);
    //             $closeTime = Carbon::parse($quizResult->updated_at);
                
    //             $timeTakenInMinutes = round($openTime->diffInMinutes($closeTime));

    //             // Build result
    //             $result = [
    //                 'correct' => $quizResult->correct_answer,
    //                 'incorrect' => $quizResult->incorrect_answer,
    //                 'skipped' => $quizResult->total_question - ($quizResult->correct_answer + $quizResult->incorrect_answer),
    //                 'marks' => $quizResult->student_percentage,
    //                 'status' => $quizResult->student_percentage >= $quizResult->pass_percentage ? "PASS" : "FAIL",
    //                 'timeTaken' => $timeTakenInMinutes
    //             ];
    
    //             // Process exam details (Compare user answers with correct answers)
    //             $exam = [];
    //             $questionBox = json_decode($quizResult->questions);
    //             $correct_answers = json_decode($quizResult->correct_answers, true);
    //             $userAnswers = json_decode($quizResult->answers, true);

    //             foreach ($questionBox as $question) {
    //                 // Get the user answer for the current question by matching the IDs
    //                 $userAnswer = collect($userAnswers)->firstWhere('id', $question->id);
    //                 $correctAnswer = collect($correct_answers)->firstWhere('id', $question->id);
    //                 $isCorrect = false;

    //                 $user_answ = isset($userAnswer['answer']) ? $userAnswer['answer'] : null;
    //                 $correct_answ = isset($correctAnswer['correct_answer']) ? $correctAnswer['correct_answer'] : null;
                
    //                 // Ensure correctAnswer is an array when needed
    //                 switch ($question->type) {
    //                     case 'FIB':
    //                         $correct_answ = json_decode($correctAnswer['correct_answer']);
    //                         $isCorrect = $user_answ == $correct_answ;
    //                         break;
    //                     case 'MSA':
    //                         $correct_answ = $correctAnswer['correct_answer'];
    //                         $isCorrect = $user_answ == $correct_answ;
    //                         break;
    //                     case 'MMA':
    //                         $correct_answ = json_decode($correctAnswer['correct_answer']);
    //                         sort($user_answ);
    //                         sort($correct_answ);
    //                         $isCorrect = $user_answ == $correct_answ;
    //                         break;
    //                     case 'TOF':
    //                         $correct_answ = $correctAnswer['correct_answer'];
    //                         $isCorrect = $user_answ == $correct_answ;
    //                         break;
    //                     case 'MTF':
    //                         $isCorrect = true;
    //                         $correct_answ = json_decode($correctAnswer['correct_answer'],true);
    //                         foreach ($correct_answ as $key => $value) {
    //                             if ($user_answ[$key] != $value) {
    //                                 $isCorrect = false;
    //                                 break;
    //                             }
    //                         }
    //                         break;
    //                     case 'ORD':
    //                         $correct_answ = json_decode($correctAnswer['correct_answer'],true);
    //                         $isCorrect = $user_answ === $correct_answ;
    //                         break;
    //                     case 'EMQ':
    //                         $correct_answ = json_decode($correctAnswer['correct_answer'],true);
    //                         $isCorrect = $user_answ === $correct_answ;
    //                         break;
    //                     case 'SAQ': 
    //                         $correct_answ = $question->options;
    //                         $options = $question->options; // array
    //                         // Loop through each option and compare after sanitizing HTML
    //                         foreach ($options as $option) {
    //                             // Strip HTML tags and extra spaces from both user answer and the option
    //                             $sanitizedUserAnswer = trim(strip_tags($user_answ));
    //                             $sanitizedOption = trim(strip_tags($option));

    //                             // Check if the sanitized user answer matches any sanitized option
    //                             if ($sanitizedUserAnswer === $sanitizedOption) {
    //                                 $isCorrect = true;
    //                                 break;
    //                             }
    //                         }
    //                         break;
    //                 }
                

    //                 $exam[] = [
    //                     'question_id' => $question->id,
    //                     'question_type' => $question->type,
    //                     'question_text' => $question->question,
    //                     'question_option' => $question->options,
    //                     'correct_answer' => $correct_answ ?? null,
    //                     'user_answer' => $user_answ ?? null,  // Handle case where there's no user answer
    //                     'is_correct' => $isCorrect,
    //                 ];
    //             }
    
    //             $quiz = [
    //                 'title' => $quizResult->quiz->title,
    //                 'duration' => $quizResult->exam_duration,
    //             ];
    
    //             return response()->json([
    //                 'status' => true,
    //                 'quiz' => $quiz,
    //                 'result' => $result,
    //                 'exam_preview' => $exam,
    //                 'leaderBoard' => $leaderBoard,
    //             ]);
    //         }
    
    //     } catch (\Throwable $th) {
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'Something went wrong: '. $th->getMessage(),
    //         ]);
    //     }
    // }
    
    public function quizResult(Request $request, $uuid)
    {
        try {
            $user = $request->attributes->get('authenticatedUser');
    
            $quizResult = QuizResult::with('quiz')->where('uuid', $uuid)->where('user_id', $user->id)->first();
            if ($quizResult) {
                // Build leaderboard
                $leaderBoard = [];
                if (isset($quizResult->quiz) && $quizResult->quiz->leaderboard == 1) {
                    $userQuiz = QuizResult::with('user')
                        ->where('quiz_id', $quizResult->quiz_id)
                        ->orderby('student_percentage', 'DESC')
                        ->take(10)
                        ->get();
    
                    foreach ($userQuiz as $userData) {
                        if (isset($userData->user)) {
                            $leaderBoard[] = [
                                "username" => $userData->user->name,
                                "score" => $userData->student_percentage,
                                "status" => $userData->student_percentage >= $userData->pass_percentage ? "PASS" : "FAIL",
                            ];
                        }
                    }
                }

                $openTime = Carbon::parse($quizResult->created_at);
                $closeTime = Carbon::parse($quizResult->updated_at);
                $timeTakenInMinutes = round($openTime->diffInMinutes($closeTime),2);

                // Build result
                $result = [
                    'correct' => $quizResult->correct_answer,
                    'incorrect' => $quizResult->incorrect_answer,
                    'skipped' => $quizResult->total_question - ($quizResult->correct_answer + $quizResult->incorrect_answer),
                    'marks' => $quizResult->student_percentage,
                    'status' => $quizResult->student_percentage >= $quizResult->pass_percentage ? "PASS" : "FAIL",
                    'timeTaken' => $timeTakenInMinutes
                ];
    
                // Process exam details (Compare user answers with correct answers)
                $exam = [];
                $questionBox = json_decode($quizResult->questions);
                $correct_answers = json_decode($quizResult->correct_answers, true);
                $userAnswers = json_decode($quizResult->answers, true);

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
                

                    $exam[] = [
                        'question_id' => $question->id,
                        'question_type' => $question->type,
                        'question_text' => $question->question,
                        'question_option' => $question->options,
                        'correct_answer' => $correct_answ ?? null,
                        'user_answer' => $user_answ ?? null,  // Handle case where there's no user answer
                        'is_correct' => $isCorrect,
                    ];
                }
    
                $quiz = [
                    'title' => $quizResult->quiz->title,
                    'duration' => $quizResult->exam_duration,
                ];
    
                return response()->json([
                    'status' => true,
                    'quiz' => $quiz,
                    'result' => $result,
                    'exam_preview' => $exam,
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

    // public function quizAll(Request $request){
    //     try {
    //         // Validate the request
    //         $request->validate(['category' => 'required']);
    
    //         // Fetch all exam results for the authenticated user where status is complete
    //         $quizData = Quizze::select(
    //             'quizzes.slug',
    //             'quizzes.title',
    //             'quizzes.description',
    //             'quizzes.pass_percentage',
    //             'sub_categories.name as sub_category_name',
    //             'quiz_types.name as exam_type_name',
    //             'quizzes.duration_mode', 
    //             'quizzes.duration', 
    //             'quizzes.point_mode',
    //             'quizzes.point', 
    //             'quizzes.is_free', 
    //             DB::raw('COUNT(questions.id) as total_questions'),  // Count the total number of questions
    //             DB::raw('SUM(CAST(questions.default_marks AS DECIMAL)) as total_marks'),  // Sum the total marks
    //             DB::raw('SUM(COALESCE(questions.watch_time, 0)) as total_time')  // Sum the total time for the quiz
    //         )
    //         ->leftJoin('quiz_types', 'quizzes.quiz_type_id', '=', 'quiz_types.id')
    //         ->leftJoin('sub_categories', 'quizzes.subcategory_id', '=', 'sub_categories.id')
    //         ->leftJoin('quiz_questions', 'quizzes.id', '=', 'quiz_questions.quizzes_id')  // Join with quiz_questions
    //         ->leftJoin('questions', 'quiz_questions.question_id', '=', 'questions.id')  // Join with questions
    //         ->where('quizzes.subcategory_id', $request->category)  // Filter by category
    //         ->where('quizzes.status', 1)  // Only active quizzes
    //         ->groupBy(
    //             'quizzes.slug',
    //             'quizzes.id',
    //             'quizzes.title',
    //             'quizzes.description',
    //             'quizzes.pass_percentage',
    //             'sub_categories.name',
    //             'quiz_types.name',
    //             'quizzes.duration_mode', 
    //             'quizzes.duration', 
    //             'quizzes.point_mode',
    //             'quizzes.point',
    //             'quizzes.is_free',
    //         )
    //         ->havingRaw('COUNT(questions.id) > 0')  // Ensure quizzes with more than 0 questions
    //         ->get();
    
    //         // Return success JSON response
    //         return response()->json([
    //             'status' => true,
    //             'data' => $quizData
    //         ], 200);
    //     } catch (\Throwable $th) {
    //         // Return error JSON response
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'An error occurred while fetching the dashboard data.',
    //             'error' => 'Error logged. :' . $th->getMessage() // For security
    //         ], 500);
    //     }
    // }

    public function quizAll(Request $request)
    {
        try {
            // Get the authenticated user
            $user = $request->attributes->get('authenticatedUser');
    
            // Validate the request
            $request->validate(['category' => 'required']);
    
            // Fetch all quizzes with questions count, marks, etc.
            $quizData = Quizze::select(
                    'quizzes.id',
                    'quizzes.slug',
                    'quizzes.title',
                    'quizzes.description',
                    'quizzes.pass_percentage',
                    'sub_categories.name as sub_category_name',
                    'quiz_types.name as exam_type_name',
                    'quizzes.duration_mode', 
                    'quizzes.duration', 
                    'quizzes.point_mode',
                    'quizzes.point', 
                    'quizzes.is_free', 
                    DB::raw('COUNT(questions.id) as total_questions'),  // Count the total number of questions
                    DB::raw('SUM(CAST(questions.default_marks AS DECIMAL)) as total_marks'),  // Sum the total marks
                    DB::raw('SUM(COALESCE(questions.watch_time, 0)) as total_time')  // Sum the total time for the quiz
                )
                ->leftJoin('quiz_types', 'quizzes.quiz_type_id', '=', 'quiz_types.id')
                ->leftJoin('sub_categories', 'quizzes.subcategory_id', '=', 'sub_categories.id')
                ->leftJoin('quiz_questions', 'quizzes.id', '=', 'quiz_questions.quizzes_id')  // Join with quiz_questions
                ->leftJoin('questions', 'quiz_questions.question_id', '=', 'questions.id')  // Join with questions
                ->where('quizzes.subcategory_id', $request->category)  // Filter by category
                ->where('quizzes.status', 1)  // Only active quizzes
                ->groupBy(
                    'quizzes.slug',
                    'quizzes.id',
                    'quizzes.title',
                    'quizzes.description',
                    'quizzes.pass_percentage',
                    'sub_categories.name',
                    'quiz_types.name',
                    'quizzes.duration_mode', 
                    'quizzes.duration', 
                    'quizzes.point_mode',
                    'quizzes.point',
                    'quizzes.is_free',
                )
                ->havingRaw('COUNT(questions.id) > 0')  // Ensure quizzes with more than 0 questions
                ->get();
    
            // Check if the user has a subscription
            $currentDate = now();
            $subscription = Subscription::with('plans')->where('user_id', $user->id)
                ->where('stripe_status', 'complete')
                ->where('ends_at', '>', $currentDate)
                ->latest()
                ->first();
    
            // Loop through the quiz data and apply subscription logic
            foreach ($quizData as $quiz) {
                if ($quiz->visibility == 1) { // Check if the quiz is private
                    if ($subscription) {
                        $plan = $subscription->plans;
    
                        // Check if the subscription plan allows access to quizzes
                        if ($plan->feature_access != 1) {
                            // Fetch the allowed features for this plan (e.g., quiz types)
                            $allowed_features = json_decode($plan->features, true);
    
                            // Check if the quiz type (or other identifier) is in the allowed features
                            $type = "quizzes"; // Define the feature type for quiz access
                            if (!in_array($type, $allowed_features)) {
                                // Deny access to this quiz
                                $quiz->access_denied = true;
                                $quiz->access_message = 'This quiz is not included in your subscription plan.';
                            }
                        }
                    } else {
                        // No subscription, deny access to private quizzes
                        $quiz->access_denied = true;
                        $quiz->access_message = 'You need a subscription to access this quiz.';
                    }
                } else {
                    // Public quizzes are accessible by default, no need to set access denied
                    $quiz->access_denied = false; 
                }
            }
    
            // Return success JSON response
            return response()->json([
                'status' => true,
                'data' => $quizData
            ], 200);
    
        } catch (\Throwable $th) {
            // Return error JSON response
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while fetching the quiz data.',
                'error' => 'Error logged. :' . $th->getMessage() // For security
            ], 500);
        }
    }
    



    public function quizProgress(Request $request){
        try {
            $request->validate(['category' => 'required']);
        
            // Get the authenticated user
            $user = $request->attributes->get('authenticatedUser');
            $quizResults = QuizResult::join('quizzes', 'quiz_results.quiz_id', '=', 'quizzes.id')
            ->select(
                'quiz_results.updated_at', 
                'quiz_results.student_percentage', 
                'quiz_results.pass_percentage', 
                'quiz_results.status', 
                'quiz_results.uuid', 
                'quizzes.title as quiz_title',
                'quizzes.slug as quiz_slug'
            )
            ->where('quiz_results.user_id', $user->id)
            ->where('quiz_results.subcategory_id', $request->category)
            ->get();
   

            // Return success JSON response
            return response()->json([
                'status' => true,
                'data' => $quizResults
            ], 200);
            
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while fetching the data.',
                'error' => 'Error logged. :' . $th->getMessage() 
            ], 500);
        }
    }


}
