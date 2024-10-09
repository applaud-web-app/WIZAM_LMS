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

class QuizController extends Controller
{

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
        $totalMarks = $quizResult->point_type == "manual" 
            ? $quizResult->point * count($user_answer) // Manual mode total marks
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
        $quizResult->status = "complete";
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
    
                // Build result
                $result = [
                    'correct' => $quizResult->correct_answer,
                    'incorrect' => $quizResult->incorrect_answer,
                    'skipped' => $quizResult->total_question - ($quizResult->correct_answer + $quizResult->incorrect_answer),
                    'marks' => $quizResult->student_percentage,
                    'status' => $quizResult->student_percentage >= $quizResult->pass_percentage ? "PASS" : "FAIL",
                ];
    
                // Process exam details (Compare user answers with correct answers)
                $exam = [];
                $questionBox = json_decode($quizResult->questions);
                $correct_answers = json_decode($quizResult->correct_answers, true);
                $userAnswers = json_decode($quizResult->answers, true);
    
                foreach ($questionBox as $question) {
                    $userAnswer = $userAnswers[$question->id] ?? null;
                    $correctAnswer = $correct_answers[$question->id]['correct_answer'];
                    $isCorrect = false;
    
                    // Ensure correctAnswer is an array when needed
                    switch ($question->type) {
                        case 'FIB': // Fill in the Blanks
                            return [
                                'userAnswer'=>$userAnswer,
                                'correct_anser'=>$correctAnswer,
                                'status'=>$userAnswer === $correctAnswer
                            ];
                            $isCorrect = $userAnswer === $correctAnswer;
                            break;
    
                        case 'MSA': // Multiple Selection Answer
                            $isCorrect = $userAnswer == $correctAnswer;
                            break;

                        case 'MMA': // Multiple Match Answer
                            if (!is_array($correctAnswer)) {
                                $correctAnswer = json_decode($correctAnswer, true);
                            }
                            $isCorrect = $userAnswer === $correctAnswer;
                            break;
    
                        case 'TOF': // True/False
                            $isCorrect = $userAnswer == $correctAnswer;
                            break;
    
                        case 'MTF': // Match the Following
                            $isCorrect = $userAnswer === $correctAnswer;
                            break;
    
                        case 'ORD': // Ordering
                            $isCorrect = $userAnswer === $correctAnswer;
                            break;
    
                        case 'EMQ': // Extended Matching Questions
                            $isCorrect = $userAnswer === $correctAnswer;
                            break;
    
                        case 'SAQ': // Short Answer Question
                            $isCorrect = $userAnswer === $correctAnswer;
                            break;
                    }
    
                    // Append the result for this question to the exam array
                    $exam[] = [
                        'question_id' => $question->id,
                        'question_text' => $question->question,
                        'correct_answer' => $correctAnswer,
                        'user_answer' => $userAnswer,
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
    


}
