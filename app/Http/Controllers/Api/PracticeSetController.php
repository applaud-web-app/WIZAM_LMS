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
use App\Models\Subscription;
use App\Models\SubscriptionItem;
use App\Models\GroupUsers;
use App\Models\Plan;
use App\Models\User;

class PracticeSetController extends Controller
{

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

    private function getUserPractice($userId)
    {
        return $this->getUserItemsByType($userId, 'practice');
    }

    public function playPracticeSet(Request $request, $slug)
    {
        try {
            // Get the authenticated user
            $user = $request->attributes->get('authenticatedUser');

            // Validate incoming request data
            $request->validate([
                'category' => 'required|integer',
            ]);

            // Purchased practice
            $purchasePractice = $this->getUserPractice($user->id);

            // Fetch the practice along with related questions in one query
            $practice = PracticeSet::with(['practiceQuestions.questions' => function($query) {
                        $query->select('id', 'question', 'default_marks', 'watch_time', 'type', 'options', 'answer');
                    }])
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
                ->orwhere(function ($query) use ($purchasePractice) {
                    $query->WhereIn('practice_sets.id', $purchasePractice); 
                })
                ->groupBy('practice_sets.id', 'practice_sets.title', 'practice_sets.description', 'practice_sets.slug', 'practice_sets.subcategory_id', 'practice_sets.status', 'practice_sets.allow_reward', 'practice_sets.reward_popup', 'practice_sets.point_mode', 'practice_sets.points', 'practice_sets.is_free')
                ->first();

            // If practice not found
            if (!$practice) {
                return response()->json(['status' => false, 'error' => 'Practice Set not found'], 404);
            }

            // Adjust 'is_free' for assigned quiz, regardless of public or private
            if (in_array($practice->id, $purchasePractice)) {
                $practice->is_free = 1; // Make assigned quizs free
            }

            if($practice->is_free == 0){
                return response()->json(['status' => false, 'error' => 'You donot have this pratice set. Please purchase it continue'], 404);
            }

            // Check for ongoing practice set
            $ongoingPractice = PracticeSetResult::where('user_id', $user->id)
                ->where('practice_sets_id', $practice->id)
                ->where('status', 'ongoing')
                ->latest('created_at')
                ->first();

            if ($ongoingPractice) {

                // Calculate remaining duration
                $remainingDuration = now()->diffInMinutes($ongoingPractice->end_time);

                if ($ongoingPractice->end_time->isPast()) {
                    $ongoingPractice->update(['status' => 'complete']);
                    return response()->json(['status' => true, 'message' => 'Practice Set Timed Out', 'data' => ['uuid' => $ongoingPractice->uuid]]);
                } else {
                    return response()->json([
                        'status' => true,
                        'data' => [
                            'title' => $practice->title,
                            'uuid' => $ongoingPractice->uuid,
                            'questions' => json_decode($ongoingPractice->questions),
                            'total_time'=> $ongoingPractice->exam_duration,
                            'duration' => round($remainingDuration,2),
                            'points' => $ongoingPractice->points,
                            'saved_answers'=> $ongoingPractice->answers == null ? [] : json_decode($ongoingPractice->answers),
                        ]
                    ], 200);
                }
            }

            // Calculate practice duration and points
            $duration = (int) round($practice->total_time / 60, 2); // in minute
            $points = $practice->point_mode == "manual" ? $practice->points : $practice->total_marks;

            // Prepare structured response data for questions and correct answers
            $questionsData = [];
            $correctAnswers = [];
            foreach ($practice->practiceQuestions as $practiceQuestion) {
                $question = $practiceQuestion->questions;
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
                    'correct_answer' => $question->answer,
                    'default_marks' => $practice->point_mode == "manual" ? $practice->point : $question->default_marks
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
                'allow_point' => $practice->allow_point,
                'point_mode' => $practice->point_mode,
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
                    'total_time'=> $practiceResult->exam_duration,
                    'duration' =>  round($remainingDuration,2),
                    'points' => $practiceResult->point,
                    'saved_answers'=> $practiceResult->answers == null ? [] : json_decode($practiceResult->answers),
                ]
            ], 200);

        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'error' => 'Internal Server Error: ' . $th->getMessage()], 500);
        }
    }


    public function allQuestion(){
        try {
            $banner = Question::select('question','options','answer','type')->get();
            return response()->json(['status'=> true,'data' => $banner], 201);
        } catch (\Throwable $th) {
            return response()->json(['status'=> false,'error' => $th->getMessage()], 500);
        }
    }

    public function finishPracticeSet(Request $request,$uuid){
        // USER RESPONSE
        $user_answer = $request->input('answers');
        $userIp = $request->ip() ?? null; 
        $user = $request->attributes->get('authenticatedUser');
    
        // Fetch pratice result by UUID and user ID
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
        $wrongQuestionIds = [];  // Array to hold IDs of wrong questions
        $unanswered = 0;

        // Total marks should be fixed in manual mode
        $totalMarks = $practiceSetResult->point_type == "manual" ? $practiceSetResult->point * $practiceSetResult->total_question  : 0; 
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
            if ($practiceSetResult->point_type != "manual") {
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
                                break;
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

                    // $correctAnswers = json_decode($question->answer, true);
                    // $index = (int)explode("-", $questionId)[1] - 1;
                    // $isCorrect = $userAnswer == $correctAnswers[$index];

                    $correctAnswers = json_decode($question->answer, true);
                    $isCorrect = $userAnswer == $correctAnswers;
                }

                // Add to wrong question IDs if answer is incorrect
                if (!$isCorrect) {
                    $wrongQuestionIds[] = $questionId;  // Collect wrong question IDs
                }
        
                if ($isCorrect) {
                    $score += $practiceSetResult->point_type == "manual" ? $practiceSetResult->point : $question->default_marks;
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
    
        // Calculate the student's percentage AFTER applying negative marking
        // $studentPercentage = ($practiceSetResult->total_question > 0) ? ($correctAnswer / $practiceSetResult->total_question) * 100 : 0;

        // Calculate the student's percentage AFTER applying negative marking
        $studentPercentage = ($totalMarks > 0) ? ($score / $totalMarks) * 100 : 0;
    
        // Update pratice result with correct/incorrect answers and student percentage
        $practiceSetResult->userIp = $userIp;
        $practiceSetResult->status = "complete";
        $practiceSetResult->updated_at = now();
        $practiceSetResult->score = $score;
        $practiceSetResult->answers = json_encode($user_answer, true);
        $practiceSetResult->incorrect_answer = $incorrect;
        $practiceSetResult->correct_answer = $correctAnswer;
        $practiceSetResult->student_percentage = round($studentPercentage,2);
        $practiceSetResult->unanswered = $unanswered;
        $practiceSetResult->save();
    
        // Return results
        return response()->json([
            'status' => true,
            'score' => $score,
            'correct_answer' => $correctAnswer,
            'incorrect_answer' => $incorrect,
            'student_percenatge' => $studentPercentage,
            'wrong_question_ids' => $wrongQuestionIds,
            'unanswered' => $unanswered
        ]);
    }

    public function practiceSetResult(Request $request, $uuid){
        try {
            $user = $request->attributes->get('authenticatedUser');
    
            $practiceResult = PracticeSetResult::with('pratice')->where('uuid', $uuid)->where('user_id', $user->id)->first();
            if ($practiceResult) {
                // Build leaderboard
                $leaderBoard = [];
                if (isset($practiceResult->pratice) && $practiceResult->pratice->leaderboard == 1) {
                    $userPractice = PracticeSetResult::with('user')
                        ->where('practice_sets_id', $practiceResult->practice_sets_id)
                        ->orderby('student_percentage', 'DESC')
                        ->take(10)
                        ->get();
    
                    foreach ($userPractice as $userData) {
                        if (isset($userData->user)) {
                            $leaderBoard[] = [
                                "username" => $userData->user->name,
                                "score" => $userData->student_percentage,
                                "status" => "COMPLETED",
                            ];
                        }
                    }
                }

                $openTime = Carbon::parse($practiceResult->created_at);
                $closeTime = Carbon::parse($practiceResult->updated_at); 
                $timeTakenInMinutes = round($openTime->diffInMinutes($closeTime),2);

                // Build result
                $result = [
                    'correct' => $practiceResult->correct_answer ?? 0,
                    'incorrect' => $practiceResult->incorrect_answer ?? 0,
                    'skipped' => $practiceResult->unanswered ?? 0,
                    'marks' => $practiceResult->student_percentage ?? 0,
                    'status' => $practiceResult->student_percentage >= $practiceResult->pass_percentage ? "PASS" : "FAIL",
                    'timeTaken' => $timeTakenInMinutes ?? 0,
                    'score' => $practiceResult->score ?? 0,
                    'uuid' => $practiceResult->uuid,
                ];
    
                // Process exam details (Compare user answers with correct answers)
                $exam = [];
                $questionBox = json_decode($practiceResult->questions);
                $correct_answers = json_decode($practiceResult->correct_answers, true);
                $userAnswers = json_decode($practiceResult->answers, true);

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

                            // Normalize `user_answ` or default to an empty array
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
    
                $pratice = [
                    'title' => $practiceResult->pratice->title,
                    'duration' => $practiceResult->exam_duration,
                ];
    
                return response()->json([
                    'status' => true,
                    'pratice' => $pratice,
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

       

    public function praticeSetProgress(Request $request){
        try {
            $request->validate(['category' => 'required']);
        
            // Get the authenticated user
            $user = $request->attributes->get('authenticatedUser');
            $practiceResults = PracticeSetResult::join('practice_sets', 'practice_set_results.practice_sets_id', '=', 'practice_sets.id')
            ->select(
                'practice_set_results.updated_at', 
                'practice_set_results.student_percentage', 
                'practice_set_results.status', 
                'practice_set_results.uuid', 
                'practice_sets.title as practice_title',
                'practice_sets.slug as practice_slug'
            )
            ->where('practice_set_results.user_id', $user->id)
            ->where('practice_set_results.subcategory_id', $request->category)
            ->get();
   

            // Return success JSON response
            return response()->json([
                'status' => true,
                'data' => $practiceResults
            ], 200);
            
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while fetching the data.',
                'error' => 'Error logged. :' . $th->getMessage() 
            ], 500);
        }
    }

    public function downloadPracticeSetReport(Request $request, $uuid){
        try {
            $user = $request->attributes->get('authenticatedUser');
    
            $examResult = PracticeSetResult::with('pratice')->where('uuid', $uuid)->where('user_id', $user->id)->first();
    
            if (!$examResult) {
                return response()->json([
                    'status' => false,
                    'message' => 'Practice Set result not found for this user.'
                ], 404);
            }
    
            $exam_data = PracticeSet::with('subCategory')->where('id', $examResult->practice_sets_id)->first();
            $userDetail = User::find($user->id);
    
            // Ensure $exam_data and relationships are valid
            if (!$exam_data || !$exam_data->subCategory) {
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
                'name' => $exam_data->title . " - " . $exam_data->subCategory->name,
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
                'practice_info' => $examInfo,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong: ' . $th->getMessage(),
            ]);
        }
    }

    public function savePracticeSetAnswerProgress(Request $request, $uuid){
        // Fetch the user and user answers
        $user_answer = $request->input('answers');
        $user = $request->attributes->get('authenticatedUser');
        // Find or create an exam result in progress by UUID and user ID
        $examResult = PracticeSetResult::where('uuid', $uuid)->where('user_id', $user->id)->first();
        if (!$examResult) {
            return response()->json([
                'status' => false,
                'message' => "Practice Set not found"
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
