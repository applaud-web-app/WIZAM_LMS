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
use App\Models\SubscriptionItem;
use App\Models\GroupUsers;
use App\Models\Plan;
use App\Models\QuizType;

class QuizController extends Controller
{

    public function playQuiz(Request $request, $slug)
    {
        try {
            // Validate incoming request data
            $request->validate([
                'category' => 'required|integer',
                'schedule_id'  => 'required',
            ]);

            // Get the authenticated user
            $user = $request->attributes->get('authenticatedUser');

            // Check if the user is authenticated
            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'User not authenticated',
                ], 401);
            }

            // User group IDs
            $userGroup = GroupUsers::where('user_id',$user->id)
            ->where('status',1)
            ->pluck('group_id')
            ->toArray();

            // Purchased Quiz
            $purchaseQuiz = $this->getUserQuiz($user->id);
    
            // Fetch the quiz along with related questions in one query
            $quiz = Quizze::leftJoin('quiz_schedules', function ($join) {
                    $join->on('quizzes.id', '=', 'quiz_schedules.quizzes_id')
                        ->where('quiz_schedules.status', 1);
                })->with(['quizQuestions.questions' => function($query) {
                        $query->select('id', 'question', 'default_marks', 'watch_time', 'type', 'options', 'answer');
                    }])
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
                    'quizzes.restrict_attempts',
                    'quizzes.total_attempts',
                    'quiz_schedules.user_groups',
                    DB::raw('SUM(questions.default_marks) as total_marks'),
                    DB::raw('SUM(COALESCE(questions.watch_time, 0)) as total_time'),
                    DB::raw('SUM(CASE 
                        WHEN questions.type = "EMQ" AND JSON_VALID(questions.question) THEN JSON_LENGTH(questions.question) - 1
                        ELSE 1 
                    END) as total_questions'),
                )
                ->leftJoin('quiz_questions', 'quizzes.id', '=', 'quiz_questions.quizzes_id')
                ->leftJoin('questions', 'quiz_questions.question_id', '=', 'questions.id')
                ->where('quizzes.slug', $slug)
                ->where('quizzes.subcategory_id', $request->category)
                ->where('quizzes.status', 1)
                ->where('questions.status', 1)
                ->where(function ($query) {
                    $query->where('quizzes.is_public', 1) 
                        ->orWhereNotNull('quiz_schedules.id'); 
                })
                ->where(function ($query) use ($purchaseQuiz,$userGroup) {
                    $query->where('quizzes.is_public', 1)
                    ->orWhereIn('quizzes.id', $purchaseQuiz)
                    ->orwhereIn('quiz_schedules.user_groups',$userGroup); 
                })
                ->groupBy(
                    'quizzes.id', 'quizzes.title', 'quizzes.description', 'quizzes.pass_percentage',
                    'quizzes.slug', 'quizzes.subcategory_id', 'quizzes.status', 'quizzes.duration_mode',
                    'quizzes.point_mode', 'quizzes.duration', 'quizzes.point', 'quizzes.shuffle_questions',
                    'quizzes.question_view', 'quizzes.disable_finish_button', 'quizzes.negative_marking',
                    'quizzes.negative_marking_type', 'quizzes.negative_marks',  'quizzes.is_free','quizzes.restrict_attempts','quizzes.total_attempts','quiz_schedules.user_groups',
                )
                ->first();
    
            // If quiz not found
            if (!$quiz) {
                return response()->json(['status' => false, 'error' => 'Quiz not found'], 404);
            }

             // Adjust 'is_free' for assigned quiz, regardless of public or private
             if (in_array($quiz->id, $purchaseQuiz) || in_array($quiz->user_groups, $userGroup)) {
                $quiz->is_free = 1; // Make assigned quizs free
            }

            if($quiz->is_free == 0){
                return response()->json(['status' => false, 'error' => 'You donot have this quiz. Please purchase it continue'], 404);
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
            $scheduleId = $request->schedule_id === "undefined" ? 0 : $request->schedule_id;
            $ongoingQuiz = QuizResult::where('user_id', $user->id)
                ->where('quiz_id', $quiz->id)
                ->where('schedule_id',$scheduleId)
                ->where('status', 'ongoing') // Correct the status check
                ->latest('created_at')
                ->first();
    
            if ($ongoingQuiz) {
                // Calculate remaining duration
                $remainingDuration = now()->diffInMinutes($ongoingQuiz->end_time);

                if ($ongoingQuiz->end_time->isPast()) {
                    // If time has passed, mark the quiz as complete
                    $ongoingQuiz->update(['status' => 'complete']);
                    $data = ['uuid'=>$ongoingQuiz->uuid];
                    return response()->json(['status' => true, 'message' => 'Quiz Timed Out','data'=>$data]);
                } else {
                    // Return ongoing quiz details
                    return response()->json([
                        'status' => true,
                        'data' => [
                            'title' => $quiz->title,
                            'uuid'=>$ongoingQuiz->uuid,
                            'questions' => json_decode($ongoingQuiz->questions),
                            'total_time'=> $ongoingQuiz->exam_duration,
                            'duration' => round($remainingDuration,2),
                            'points' => $ongoingQuiz->point,
                            'saved_answers'=> $ongoingQuiz->answers == null ? [] : json_decode($ongoingQuiz->answers),
                            'question_view' => $quiz->question_view == 1 ? "enable" : "disable",
                            'finish_button' => $quiz->disable_finish_button == 1 ? "disable" : "enable"
                        ]
                    ], 200);
                }
            }
    
            // Calculate quiz duration and points
            $duration = (int) ($quiz->duration_mode == "manual"? $quiz->duration : round($quiz->total_time / 60));
            $points = $quiz->point_mode == "manual" ? $quiz->point * $quiz->total_questions : $quiz->total_marks;
    
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
    
                // Customize question display for different types
                $questionText = $question->question;
                if ($question->type == "FIB") {
                    $questionText = preg_replace('/##(.*?)##/', '<span class="border-b border-black inline-block w-[150px] text-center" style="width:150px;"></span>', $question->question);
                    $options = [json_decode($question->answer, true) ? count(json_decode($question->answer, true)) : 0];
                }elseif ($question->type == "EMQ") {
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
                'schedule_id'=>$request->schedule_id === "undefined" ? 0 : $request->schedule_id,
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
                'total_question' => count($questionsData), // CHANGE THIS FOR EMQ
                'status' => 'ongoing',
            ]);
    
            $remainingDuration = now()->diffInMinutes($quizResult->end_time);
 
            return response()->json([
                'status' => true,
                'data' => [
                    'title' => $quiz->title,
                    'uuid'=>$quizResult->uuid,
                    'questions' => json_decode($quizResult->questions),
                    'total_time'=> $quizResult->exam_duration,
                    'duration' =>  round($remainingDuration,2),
                    'points' => $quizResult->point,
                    'saved_answers'=> $quizResult->answers == null ? [] : json_decode($quizResult->answers),
                    'question_view' => $quiz->question_view == 1 ? "enable" : "disable",
                    'finish_button' => $quiz->disable_finish_button == 1 ? "disable" : "enable"
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
        $userIp = $request->ip() ?? null; 
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
        $wrongQuestionIds = [];  // Array to hold IDs of wrong questions
        $unanswered = 0;

        // WRONG AND UNanwered are 2 different things -- wrong count will be those where user give the answer and it dont correct while answered are those where user dont give the answer so make this logic 

        // Total marks should be fixed in manual mode
        $totalMarks = $quizResult->point_type == "manual" ? $quizResult->point * $quizResult->total_question : 0; 
    
        foreach ($user_answer as $answer) {
            if (!isset($answer['id'])) {
                $unanswered += 1;
                continue;
            }
            
            // $question = Question::find($answer['id']);
            $questionId = $answer['id'];
            $question = Question::find($answer['id']);

            if (!$question) {
                $incorrect += 1;
                continue;
            }

            // FOR TOTAL MARKS MARKS
            if ($quizResult->point_type != "manual") {
                $totalMarks += $question->default_marks;
            }

            // Check if the answer is empty, which means the question was left unanswered
            if (empty($answer['answer'])) {
                $unanswered += 1;
                continue;
            }

            // Handle different question types
            $isCorrect = false;
            if (isset($answer['answer'])) {
                $userAnswer = $answer['answer'];
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
                    if (is_string($userAnswer)) {
                        $answers = json_decode($question->options);
                        foreach ($answers as $option) {
                            $sanitizedOption = strtolower(trim(strip_tags($option)));
                            $sanitizedUserAnswer = strtolower(trim(strip_tags($userAnswer)));
                            if ($sanitizedUserAnswer == $sanitizedOption) {
                                $isCorrect = true;
                                break;  // Exit the loop once a match is found
                            }
                        }
                    }
                } elseif ($question->type == 'FIB') {
                    // $correctAnswers = json_decode($question->answer, true);
                    // sort($correctAnswers);
                    // sort($userAnswer);
                    // $isCorrect = $userAnswer == $correctAnswers;

                    $correctAnswers = array_map('strtolower', json_decode($question->answer, true));
                    $userAnswer = $userAnswer != null ? array_map('strtolower', $userAnswer) : [];
                    sort($correctAnswers);
                    sort($userAnswer);
                    $isCorrect = ($userAnswer == $correctAnswers);

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
                    $correctAnswers = json_decode($question->options, true);
                    $isCorrect = $userAnswer == $correctAnswers;
                } elseif ($question->type == 'EMQ') {
                    // $correctAnswers = json_decode($question->answer, true);
                    // sort($userAnswer);
                    // sort($correctAnswers);
                    // $isCorrect = $userAnswer == $correctAnswers;

                    $correctAnswers = json_decode($question->answer, true);
                    $isCorrect = $userAnswer == $correctAnswers;
                    // $correctAnswers = json_decode($question->answer, true);
                    // $index = (int)explode("-", $questionId)[1] - 1;
                    // $isCorrect = $userAnswer == $correctAnswers[$index];
                }

                 // Add to wrong question IDs if answer is incorrect
                 if (!$isCorrect) {
                    $wrongQuestionIds[] = $questionId;  // Collect wrong question IDs
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

                $wrongQuestionIds[] = $questionId; 

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
        $quizResult->userIp = $userIp;
        $quizResult->status = "complete";
        $quizResult->updated_at = now();
        $quizResult->score = $score;
        $quizResult->answers = json_encode($user_answer, true);
        $quizResult->unanswered = $unanswered;
        $quizResult->incorrect_answer = $incorrect;
        $quizResult->correct_answer = $correctAnswer;
        $quizResult->student_percentage = round($studentPercentage,2);
        $quizResult->save();
    
        // Return results
        return response()->json([
            'status' => true,
            'score' => $score,
            'totalMarks'=>$totalMarks,
            'correct_answer' => $correctAnswer,
            'incorrect_answer' => $incorrect,
            'student_status' => $studentStatus,
            'student_percentage' => $studentPercentage,
            'wrong_question_ids' => $wrongQuestionIds,
            'unanswered' => $unanswered
        ]);
    }
    
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
                    'correct' => $quizResult->correct_answer ?? 0,
                    'incorrect' => $quizResult->incorrect_answer ?? 0,
                    'skipped' => $quizResult->unanswered ?? 0,
                    'marks' => $quizResult->student_percentage ?? 0,
                    'status' => $quizResult->student_percentage >= $quizResult->pass_percentage ? "PASS" : "FAIL",
                    'timeTaken' => $timeTakenInMinutes ?? 0,
                    'score' => $quizResult->score ?? 0,
                    'uuid' =>$quizResult->uuid
                ];
    
                // Process exam details (Compare user answers with correct answers)
                $exam = [];
                $questionBox = json_decode($quizResult->questions);
                $correct_answers = json_decode($quizResult->correct_answers, true);
                $userAnswers = json_decode($quizResult->answers, true);

                $correctCount = 0;
                $incorrectCount = 0;
                $unansweredCount = 0;

                foreach ($questionBox as $question) {
                    // Get the user answer for the current question by matching the IDs
                    $userAnswer = collect($userAnswers)->firstWhere('id', $question->id);
                    $correctAnswer = collect($correct_answers)->firstWhere('id', $question->id);
                    $isCorrect = false;

                    $user_answ = isset($userAnswer['answer']) ? $userAnswer['answer'] : null;
                    $correct_answ = isset($correctAnswer['correct_answer']) ? $correctAnswer['correct_answer'] : null;
                
                    // Check if the question is unanswered
                    $isUnanswered = is_null($user_answ) || (is_array($user_answ) && empty($user_answ));

                    // Ensure correctAnswer is an array when needed
                    switch ($question->type) {
                        case 'FIB':
                            if (is_string($correct_answ)) {
                                $correct_answ = json_decode($correct_answ, true);
                            }
                            $correct_answ = array_map(function($item) {
                                return is_string($item) ? strtolower($item) : $item;
                            }, $correct_answ);

                            if (is_string($user_answ)) {
                                $user_answ = json_decode($user_answ, true);
                            }

                            // Normalize user answer array
                            $user_answ = $user_answ != null && is_array($user_answ) ? array_map(function ($item) {
                                return is_string($item) ? strtolower($item) : $item;
                            }, $user_answ) : [];

                            // Sort and compare
                            if(is_array($correct_answ)){
                                sort($correct_answ);
                            }
                            if(is_array($user_answ)){
                                sort($user_answ);
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
                            // Sort and compare
                            if(is_array($correct_answ)){
                                sort($correct_answ);
                            }
                            if(is_array($user_answ)){
                                sort($user_answ);
                            }
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
                            $correct_answ = $question->options;
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

                    // Increment counters based on answer status
                    if ($isUnanswered) {
                        $unansweredCount += 1;
                    } elseif ($isCorrect) {
                        $correctCount += 1;
                    } else {
                        $incorrectCount += 1;
                    }
                

                    $exam[] = [
                        'question_id' => $question->id,
                        'question_type' => $question->type,
                        'question_text' => $question->question,
                        'question_option' => $question->options,
                        'correct_answer' => $correct_answ ?? null,
                        'user_answer' => $user_answ ?? null,  // Handle case where there's no user answer
                        'is_correct' => $isCorrect,
                        'is_unanswered' => $isUnanswered,
                    ];
                }

                $quizType = QuizType::where('id', $quizResult->quiz->quiz_type_id)->value('name');
                $is_type = $quizType ?? "";

                $quiz = [
                    'title' => $quizResult->quiz->title ?? 'N/A',
                    'duration' => $quizResult->exam_duration ?? 0, 
                    'download_report' => $quizResult->quiz->download_report ?? false, 
                    'exam_result_date' => $quizResult->updated_at ? $quizResult->updated_at->format('d-m-Y') : 'N/A',
                    'exam_result_time' => $quizResult->updated_at ? $quizResult->updated_at->format('h:i:s') : 'N/A',
                    'exam_result_type' => $is_type,
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

    
    private function getUserItemsByType($userId, $itemType)
    {
        try {
            // Check if the user has an active subscription
            $subscriptionIds = Subscription::where('user_id', $userId)
            ->where('status', 'active')
            ->whereDate('end_date', '>=', now()) // Check subscription validity
            ->pluck('id') // Get subscription IDs
            ->toArray();

            if (!empty($subscriptionIds)) {
                // Fetch subscription items of the specified type
                $subscriptionItems = SubscriptionItem::whereIn('subscription_id', $subscriptionIds)
                ->where('item_type', $itemType)
                ->where('status', 'active')
                ->pluck('item_id')
                ->toArray();

                return $subscriptionItems ?? [];
            }

            return [];
        } catch (\Throwable $th) {
            \Log::error("Dashboard Error fetching user items for type {$itemType}: " . $th->getMessage());
            return [];
        }
    }

    private function getUserQuiz($userId)
    {
        return $this->getUserItemsByType($userId, 'quizze');
    }

    public function quizAll(Request $request)
    {
        try {
            // Validate the request
            $request->validate(['category' => 'required']);

            // Get the authenticated user
            $user = $request->attributes->get('authenticatedUser');

            // User group IDs
            $userGroup = GroupUsers::where('user_id',$user->id)
            ->where('status',1)
            ->pluck('group_id')
            ->toArray();

            // Purchased Quiz
            $purchaseQuiz = $this->getUserQuiz($user->id);

            // Fetch User Quiz
            $quizData = Quizze::leftJoin('quiz_schedules', function ($join) {
                $join->on('quizzes.id', '=', 'quiz_schedules.quizzes_id')
                    ->where('quiz_schedules.status', 1);
            })
            ->leftJoin('quiz_types', 'quizzes.quiz_type_id', '=', 'quiz_types.id')
            ->leftJoin('quiz_questions', 'quizzes.id', '=', 'quiz_questions.quizzes_id')
            ->leftJoin('questions', 'quiz_questions.question_id', '=', 'questions.id')
            ->where('quizzes.status', 1)
            ->where(function ($query) {
                $query->where('quizzes.is_public', 1) // IS THE Quiz IS PUBLIC OR HAVE A SCHEDULE (for private schedule is maindatory)
                    ->orWhereNotNull('quiz_schedules.id'); 
            })
            ->where(function ($query) use ($purchaseQuiz,$userGroup) {
                $query->where('quizzes.is_public', 1)
                ->orWhereIn('quizzes.id', $purchaseQuiz)
                ->orwhereIn('quiz_schedules.user_groups',$userGroup); 
            })
            ->where('quizzes.subcategory_id', $request->category)
            ->select(
                'quizzes.id',
                'quizzes.slug',
                'quizzes.title',
                'quizzes.description',
                'quizzes.pass_percentage',
                'quiz_types.name as exam_type_name',
                'quiz_types.slug as exam_type_slug',
                'quizzes.duration_mode',
                'quizzes.duration',
                'quizzes.point_mode',
                'quizzes.point',
                'quizzes.is_free',
                'quizzes.is_public',
                'quizzes.restrict_attempts',
                'quizzes.total_attempts',
                DB::raw('SUM(CASE 
                    WHEN questions.type = "EMQ" AND JSON_VALID(questions.question) THEN JSON_LENGTH(questions.question) - 1
                    ELSE 1 
                END) as total_questions'),
                // DB::raw('COUNT(questions.id) as total_questions'),
                DB::raw('SUM(CAST(questions.default_marks AS DECIMAL)) as total_marks'),
                DB::raw('SUM(COALESCE(questions.watch_time, 0)) as total_time'),
                'quiz_schedules.id as schedule_id',
                'quiz_schedules.schedule_type',
                'quiz_schedules.start_date',
                'quiz_schedules.start_time',
                'quiz_schedules.end_date',
                'quiz_schedules.end_time',
                'quiz_schedules.grace_period',
                'quiz_schedules.user_groups'
            )
            ->groupBy(
                'quizzes.id',
                'quizzes.slug',
                'quizzes.title',
                'quiz_types.slug',
                'quizzes.description',
                'quizzes.pass_percentage',
                'quiz_types.name',
                'quizzes.duration_mode',
                'quizzes.duration',
                'quizzes.point_mode',
                'quizzes.point',
                'quizzes.is_free',
                'quizzes.is_public',
                'quizzes.restrict_attempts',
                'quizzes.total_attempts',
                'quiz_schedules.id',
                'quiz_schedules.schedule_type',
                'quiz_schedules.start_date',
                'quiz_schedules.start_time',
                'quiz_schedules.end_date',
                'quiz_schedules.end_time',
                'quiz_schedules.grace_period',
                'quiz_schedules.user_groups'
            )
            ->havingRaw('COUNT(questions.id) > 0') // Only include quiz with questions
            ->get();

            // Resume Quiz
            $current_time = now();
            $quizResults = QuizResult::where('end_time', '>', $current_time)
                ->where('user_id', $user->id)
                ->where('status', 'ongoing')
                ->get();

            // Create a map for quick lookup
            $quizResultScheduleMap = [];
            foreach ($quizResults as $quizResult) {
                $key = $quizResult->quiz_id . '_' . $quizResult->schedule_id;
                $quizResultScheduleMap[$key] = true;
            }

            $formattedQuizData = [];
            foreach ($quizData as $quiz) {

                // Free / Paid
                $checkfree = $quiz->is_free;
                if(in_array($quiz->id,$purchaseQuiz) || in_array($quiz->user_groups,$userGroup)){
                    $checkfree = 1;
                }

                // Duration / Point
                $formattedTime = $this->formatTime($quiz->total_time);
                $time = $quiz->duration_mode == "manual" ? $this->formatTime($quiz->duration*60) : $formattedTime;
                $marks = $quiz->point_mode == "manual" ? ($quiz->point * $quiz->total_questions) : $quiz->total_marks;

                // Resume Exams
                $quizScheduleKey = $quiz->id . '_' . ($quiz->schedule_id ?: 0); // Use 0 if no schedule_id is provided
                $isResume = isset($quizResultScheduleMap[$quizScheduleKey]);

                if ($quiz->is_public === 1 && !$quiz->schedule_id) {
                    $isResume = isset($quizResultScheduleMap[$quiz->id . '_0']);
                }

                // Attempts
                $totalAttempt = $quiz->total_attempts ?? 1;
                $totalAttempt = $quiz->restrict_attempts == 1 ? $totalAttempt : null;

                // Attempts Completed or not checking
                $scheduleId = $quiz->schedule_id ?? 0;
                $userAttempt = QuizResult::where('user_id',$user->id)->where('quiz_id',$quiz->id)->where('schedule_id',$scheduleId)->count();

                if($quiz->restrict_attempts == 1 && $userAttempt >= $totalAttempt){
                    continue;
                }

                // Add quiz details to the corresponding type slug, including schedule details
                $formattedQuizData[] = [
                    'id' => $quiz->id,
                    'exam_type_slug' => $quiz->exam_type_slug,
                    'slug' => $quiz->slug,
                    'title' => $quiz->title,
                    'duration_mode' => $quiz->duration_mode,
                    'exam_duration' => $quiz->duration,
                    'point_mode' => $quiz->point_mode,
                    'point' => $quiz->point,
                    'is_free' => $checkfree,
                    'total_questions' => $quiz->total_questions,
                    'total_marks' => $marks,
                    'total_time' => $time,
                    'is_resume' => $isResume,
                    'total_attempts'=>$totalAttempt,
                    'schedules' => [
                        'schedule_id' =>  $quiz->schedule_id ?: 0,
                        'schedule_type' => $quiz->schedule_type,
                        'start_date' => $quiz->start_date,
                        'start_time' => $quiz->start_time,
                        'end_date' => $quiz->end_date,
                        'end_time' => $quiz->end_time,
                        'grace_period' => $quiz->grace_period,
                    ],
                ];
            }

            // Return success JSON response with upcoming exams and schedules
            return response()->json([
                'status' => true,
                'data' => $formattedQuizData
            ], 200);

            // return response()->json([
            //     'status' => true,
            //     'data' => $quizData
            //         ->filter(function ($exam) use ($quizResultExamScheduleMap, $user) {
            //             // Filter exams where the user exceeded attempts
            //             $scheduleId = $exam->schedule_id ?? 0;
            //             $userAttempt = QuizResult::where('user_id', $user->id)
            //                 ->where('quiz_id', $exam->id)
            //                 ->where('schedule_id', $scheduleId)
            //                 ->count();
            
            //             $totalAttempts = $exam->restrict_attempts == 0 ? null : $exam->total_attempts;
            
            //             // Exclude exams if attempts are restricted and the user has already exceeded the limit
            //             if ($exam->restrict_attempts == 1 && $totalAttempts !== null && $userAttempt >= $totalAttempts) {
            //                 return false; // Exclude this exam
            //             }
            
            //             return true; // Include this exam
            //         })
            //         ->map(function ($exam) use ($quizResultExamScheduleMap, $user) {
            //             $formattedTime = $this->formatTime($exam->total_time);
            
            //             // Public exam logic
            //             $examScheduleKey = $exam->id . '_' . ($exam->schedule_id ?: 0); // Use 0 if no schedule_id is provided
            //             $isResume = isset($quizResultExamScheduleMap[$examScheduleKey]);
            
            //             // If the exam is public and doesn't have a schedule, check for its record in resume state
            //             if ($exam->is_public === 1 && !$exam->schedule_id) {
            //                 $isResume = isset($quizResultExamScheduleMap[$exam->id . '_0']);
            //             }
            
            //             // Format time and marks based on the exam mode
            //             $time = $exam->duration_mode == "manual" ? $exam->exam_duration : $formattedTime;
            //             $marks = $exam->point_mode == "manual" ? ($exam->point * $exam->total_questions) : $exam->total_marks;
            //             $attempt = $exam->total_attempts ?? "";
            
            //             $totalAttempts = $exam->restrict_attempts == 0 ? null : $attempt;
            
            //             return [
            //                 'id' => $exam->id,
            //                 'exam_type_name' => $exam->exam_type_name,
            //                 'slug' => $exam->slug,
            //                 'title' => $exam->title,
            //                 'duration_mode' => $exam->duration_mode,
            //                 'exam_duration' => $exam->duration,
            //                 'point_mode' => $exam->point_mode,
            //                 'point' => $exam->point,
            //                 'is_free' => $exam->is_free,
            //                 'total_questions' => $exam->total_questions,
            //                 'total_marks' => $exam->total_marks,
            //                 'total_time' => $exam->total_time,
            //                 'is_resume' => $isResume,
            //                 'total_attempts' => $totalAttempts,
            //                 'schedules' => [
            //                     'schedule_id' => $exam->schedule_id ?: 0,
            //                     'schedule_type' => $exam->schedule_type,
            //                     'start_date' => $exam->start_date,
            //                     'start_time' => $exam->start_time,
            //                     'end_date' => $exam->end_date,
            //                     'end_time' => $exam->end_time,
            //                     'grace_period' => $exam->grace_period,
            //                 ],
            //             ];
            //         })
            //         ->filter() // Remove any null or false values from the mapped result
            //         ->values(), // Reset the array keys for the JSON response
            // ], 200);

        } catch (\Throwable $th) {
            // Return error JSON response with a generic message
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while fetching the quiz data.',
                'error' => 'Error logged: ' . $th->getMessage()
            ], 500);
        }
    }

    private function formatTime($totalTime)
    {
        // Convert total seconds into hours, minutes, and seconds
        $hours = floor($totalTime / 3600);
        $minutes = floor(($totalTime % 3600) / 60);
        $seconds = $totalTime % 60;

        // Format the time string accordingly
        $timeString = '';
        if ($hours > 0) {
            $timeString .= $hours . ' hr ';
        }
        if ($minutes > 0) {
            $timeString .= $minutes . ' min ';
        }
        if ($seconds > 0) {
            $timeString .= $seconds . ' sec';
        }

        return trim($timeString); // Trim any extra spaces
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

    public function downloadQuizReport(Request $request, $uuid){
        try {
            $user = $request->attributes->get('authenticatedUser');
    
            $examResult = QuizResult::with('quiz')->where('uuid', $uuid)->where('user_id', $user->id)->first();
    
            if (!$examResult) {
                return response()->json([
                    'status' => false,
                    'message' => 'Quiz result not found for this user.'
                ], 404);
            }
    
            $exam_data = Quizze::with('type', 'subCategory')->where('id', $examResult->quiz_id)->first();
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
                'quiz_info' => $examInfo,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong: ' . $th->getMessage(),
            ]);
        }
    }


    public function saveQuizAnswerProgress(Request $request, $uuid)
    {
        // Fetch the user and user answers
        $user_answer = $request->input('answers');
        $user = $request->attributes->get('authenticatedUser');
        // Find or create an exam result in progress by UUID and user ID
        $examResult = QuizResult::where('uuid', $uuid)->where('user_id', $user->id)->first();
        if (!$examResult) {
            return response()->json([
                'status' => false,
                'message' => "Quiz not found"
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
}
