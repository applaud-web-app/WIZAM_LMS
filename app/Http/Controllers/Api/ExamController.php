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
use App\Models\SubscriptionItem;
use App\Models\GroupUsers;
use App\Models\Plan;
use App\Models\AssignedExam;
use App\Models\ExamType;

class ExamController extends Controller
{
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

            // User group IDs
            $userGroup = GroupUsers::where('user_id',$user->id)
            ->where('status',1)
            ->pluck('group_id')
            ->toArray();

            // Assigned and purchased exams
            $assignedExams = AssignedExam::where('user_id', $user->id)->pluck('exam_id')->toArray();
            $purchaseExam = $this->getUserExam($user->id);

            // Fetch the exam along with related questions in one query
            $exam = Exam::leftJoin('exam_schedules', function ($join) use($userGroup){
                    $join->on('exams.id', '=', 'exam_schedules.exam_id')
                        ->where('exam_schedules.status', 1);
                })->with(['examQuestions.questions' => function($query) {
                        $query->select('id', 'question', 'default_marks', 'watch_time', 'type', 'options', 'answer');
                    }])
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
                    'exams.restrict_attempts',
                    'exams.total_attempts',
                    'exam_schedules.user_groups',
                    DB::raw('SUM(questions.default_marks) as total_marks'),
                    DB::raw('SUM(COALESCE(questions.watch_time, 0)) as total_time'),
                    DB::raw('SUM(CASE 
                        WHEN questions.type = "EMQ" AND JSON_VALID(questions.question) THEN JSON_LENGTH(questions.question) - 1
                        ELSE 1 
                    END) as total_questions'),
                )
                ->leftJoin('exam_types', 'exams.exam_type_id', '=', 'exam_types.id')
                ->leftJoin('exam_questions', 'exams.id', '=', 'exam_questions.exam_id')
                ->leftJoin('questions', 'exam_questions.question_id', '=', 'questions.id')
                ->where('exams.status', 1)
                ->where(function ($query) { 
                    $query->where('exams.is_public', 1) 
                        ->orWhereNotNull('exam_schedules.id'); 
                })
                ->where(function ($query) use ($assignedExams,$purchaseExam,$userGroup) {
                    $query->where('exams.is_public', 1)->orwhere('exams.id', $purchaseExam)
                        ->orWhereIn('exams.id', $assignedExams)->orwhereIn('exam_schedules.user_groups',$userGroup); 
                })
                ->where('exams.slug', $slug)
                ->where('exams.subcategory_id', $request->category)
                ->where('questions.status', 1)
                ->groupBy(
                    'exams.id', 'exams.title', 'exams.description', 'exams.pass_percentage',
                    'exams.slug', 'exams.subcategory_id', 'exams.status', 'exams.duration_mode',
                    'exams.point_mode', 'exams.exam_duration', 'exams.point', 'exams.shuffle_questions',
                    'exams.question_view', 'exams.disable_finish_button', 'exams.negative_marking',
                    'exams.negative_marking_type', 'exams.negative_marks','exams.is_free','exams.restrict_attempts',
                    'exams.total_attempts','exam_schedules.user_groups'
                )
                ->first();

            // If exam not found
            if (!$exam) {
                return response()->json(['status' => false, 'error' => 'Exam not found'], 404);
            }

            // Adjust 'is_free' for assigned exams, regardless of public or private
            if (in_array($exam->id, $assignedExams) || in_array($exam->id, $purchaseExam) || in_array($exam->user_groups, $userGroup)) {
                $exam->is_free = 1; // Make assigned exams free
            }

            if($exam->is_free == 0){
                return response()->json(['status' => false, 'error' => 'You donot have this exam. Please purchase it continue'], 404);
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
            $scheduleId = $request->schedule_id === "undefined" ? 0 : $request->schedule_id;
            $ongoingExam = ExamResult::where('user_id', $user->id)
                ->where('exam_id', $exam->id)
                ->where('schedule_id',$scheduleId)
                ->where('status', 'ongoing') 
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
                            'duration' => round($remainingDuration,2),
                            'points' => $ongoingExam->point,
                            'saved_answers'=> $ongoingExam->answers == null ? [] : json_decode($ongoingExam->answers),
                            'question_view' => $exam->question_view == 1 ? "enable" : "disable",
                            'finish_button' => $exam->disable_finish_button == 1 ? "disable" : "enable"
                        ]
                    ], 200);
                }
            }

            // Duration / Point
            $duration = (int) ($exam->duration_mode == "manual" ? $exam->exam_duration  : round($exam->total_time / 60)); // In Minutes
            $points = $exam->point_mode == "manual" ? ($exam->point * $exam->total_questions) : $exam->total_marks;

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
                'schedule_id'=>$request->schedule_id === "undefined" ? 0 : $request->schedule_id,
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
                'total_question' => count($questionsData), // CHANGE THIS FOR EMQ
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
                    'duration' => round($remainingDuration,2),
                    'points' => $examResult->point,
                    'saved_answers'=> $examResult->answers == null ? [] : json_decode($examResult->answers),
                    'question_view' => $exam->question_view == 1 ? "enable" : "disable",
                    'finish_button' => $exam->disable_finish_button == 1 ? "disable" : "enable"
                ]
            ], 200);

        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'error' => 'Internal Server Error: ' . $th->getMessage()], 500);
        }
    }

    public function finishExam(Request $request, $uuid){
        // USER RESPONSE
        $user_answer = $request->input('answers');
        $userIp = $request->ip() ?? null; 
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
        $unanswered = 0;

        // Total marks should be fixed in manual mode
        $totalMarks = $examResult->point_type == "manual" ? $examResult->point * $examResult->total_question : 0; 

        foreach ($user_answer as $answer) { 
            if (!isset($answer['id'])) {
                $unanswered += 1;
                continue;
            }
        
            $questionId = $answer['id'];
            $question = Question::find($answer['id']);
            
            if (!$question) {
                $incorrect += 1;
                continue;
            }

            // FOR TOTAL MARKS MARKS
            if ($examResult->point_type != "manual") {
                $totalMarks += $question->default_marks;
            }

            // Check if the answer is empty, which means the question was left unanswered
            if (empty($answer['answer'])) {
                $unanswered += 1;
                continue;
            }
            
            $isCorrect = false;
            if (isset($answer['answer'])) {
                $userAnswer = $answer['answer'];
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
                    $isCorrect = true;
                    foreach ($correctAnswers as $key => $value) {
                        if (!isset($userAnswer[$key]) || $userAnswer[$key] != $value) {
                            $isCorrect = false; 
                            break;
                        }
                    }
                } elseif ($question->type == 'ORD') {
                    $correctAnswers = json_decode($question->options, true); // UPDATED
                    $isCorrect = $userAnswer == $correctAnswers;
                } elseif ($question->type == 'EMQ') {
                    // $correctAnswers = json_decode($question->answer, true);
                    // $index = (int)explode("-", $questionId)[1] - 1;
                    // $isCorrect = $userAnswer == $correctAnswers[$index];

                    $correctAnswers = json_decode($question->answer, true);
                    // sort($userAnswer);
                    // sort($correctAnswers);
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
        $studentPercentage = $totalMarks > 0 ? ($score / $totalMarks) * 100 : 0;
    
        // Determine pass or fail
        $studentStatus = ($studentPercentage >= $examResult->pass_percentage) ? 'PASS' : 'FAIL';
    
        // Update exam result with correct/incorrect answers and student percentage
        $examResult->userIp = $userIp;
        $examResult->status = "complete";
        $examResult->updated_at = now();
        $examResult->score = $score;
        $examResult->answers = json_encode($user_answer, true);
        $examResult->incorrect_answer = $incorrect;
        $examResult->correct_answer = $correctAnswer;
        $examResult->student_percentage = round($studentPercentage,2);
        $examResult->unanswered = $unanswered;
        $examResult->save();
    
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
                    'correct' => $examResult->correct_answer ?? 0,
                    'incorrect' => $examResult->incorrect_answer ?? 0,
                    'skipped' => $examResult->unanswered ?? 0,
                    'marks' => $examResult->student_percentage ?? 0,
                    'status' => $examResult->student_percentage >= $examResult->pass_percentage ? "PASS" : "FAIL",
                    'timeTaken' => $timeTakenInMinutes ?? 0,
                    'score' => $examResult->score ?? 0,
                    'uuid'=>$examResult->uuid
                ];
    
                // Process exam details (Compare user answers with correct answers)
                $exam = [];
                $questionBox = json_decode($examResult->questions);
                $correct_answers = json_decode($examResult->correct_answers, true);
                $userAnswers = json_decode($examResult->answers, true);

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
                            // Decode `correct_answ` if it's a JSON string
                            if (is_string($correct_answ)) {
                                $correct_answ = json_decode($correct_answ, true);
                            }

                            // Ensure `correct_answ` is an array and normalize elements to lowercase
                            $correct_answ = is_array($correct_answ) ? array_map(function ($item) {
                                return is_string($item) ? strtolower($item) : $item;
                            }, $correct_answ) : [];

                            // Decode `user_answ` if it's a JSON string
                            if (is_string($user_answ)) {
                                $user_answ = json_decode($user_answ, true);
                            }

                            // Normalize `user_answ` or default to an empty array
                            $user_answ = $user_answ != null && is_array($user_answ) ? array_map(function ($item) {
                                return is_string($item) ? strtolower($item) : $item;
                            }, $user_answ) : [];

                            // Sort both arrays to ensure consistent comparison
                            sort($correct_answ);
                            sort($user_answ);

                            // Compare the sorted arrays
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

                    $examData[] = [
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

    // Wrapper methods for specific item types
    private function getUserExam($userId)
    {
        return $this->getUserItemsByType($userId, 'exam');
    }

    public function examAll(Request $request)
    {
        try {
            // Validate the request
            $request->validate(['category' => 'required']);

            // Fetch the current authenticated user
            $user = $request->attributes->get('authenticatedUser');

            // User group IDs
            $userGroup = GroupUsers::where('user_id',$user->id)
            ->where('status',1)
            ->pluck('group_id')
            ->toArray();
            
            // Assigned and purchased exams
            $assignedExams = AssignedExam::where('user_id', $user->id)->pluck('exam_id')->toArray();
            $purchaseExam = $this->getUserExam($user->id);

            // Fetch exams 
            $upcomingExams = Exam::leftJoin('exam_schedules', function ($join) {
                    $join->on('exams.id', '=', 'exam_schedules.exam_id')
                        ->where('exam_schedules.status', 1);
                })
                ->leftJoin('exam_types', 'exams.exam_type_id', '=', 'exam_types.id')
                ->leftJoin('exam_questions', 'exams.id', '=', 'exam_questions.exam_id')
                ->leftJoin('questions', 'exam_questions.question_id', '=', 'questions.id')
                ->where('exams.status', 1)
                ->where(function ($query) { // IS THE EXAM IS PUBLIC OR HAVE A SCHEDULE (for private schedule is maindatory)
                    $query->where('exams.is_public', 1) 
                        ->orWhereNotNull('exam_schedules.id'); 
                })
                ->where(function ($query) use ($assignedExams,$purchaseExam,$userGroup) {
                    $query->where('exams.is_public', 1)->orwhere('exams.id', $purchaseExam)
                    ->orWhereIn('exams.id', $assignedExams)->orwhereIn('exam_schedules.user_groups',$userGroup); 
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
                    'exams.restrict_attempts',
                    'exams.total_attempts',
                    'exams.is_public',
                    'exam_schedules.user_groups',
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
                    'exams.total_attempts',
                    'exams.duration_mode',
                    'exams.exam_duration',
                    'exams.restrict_attempts',
                    'exams.point_mode',
                    'exams.is_public',
                    'exams.point',
                    'exam_schedules.id',
                    'exam_schedules.user_groups',
                    'exam_schedules.schedule_type',
                    'exam_schedules.start_date',
                    'exam_schedules.start_time',
                    'exam_schedules.end_date',
                    'exam_schedules.end_time',
                    'exam_schedules.grace_period'
                )
                ->havingRaw('COUNT(questions.id) > 0') // Only include exams with questions
                ->get();

                
            // Resume Exam
            $current_time = now();
            $examResults = ExamResult::where('end_time', '>', $current_time)
                ->where('user_id', $user->id)
                ->where('status', 'ongoing')
                ->get();
                
            // Create a map for quick lookup
            $examResultExamScheduleMap = [];
            foreach ($examResults as $examResult) {
                $key = $examResult->exam_id . '_' . $examResult->schedule_id;
                $examResultExamScheduleMap[$key] = true;
            }

            $formattedExamData = [];
            foreach ($upcomingExams as $exam) {

                // Free / Paid
                $checkfree = $exam->is_free;
                if(in_array($exam->id,$purchaseExam) || in_array($exam->id,$assignedExams) || in_array($exam->user_groups,$userGroup)){
                    $checkfree = 1;
                }

                // Duration / Point
                $formattedTime = $this->formatTime($exam->total_time);
                $time = $exam->duration_mode == "manual" ? $this->formatTime($exam->exam_duration*60) : $formattedTime;
                $marks = $exam->point_mode == "manual" ? ($exam->point * $exam->total_questions) : $exam->total_marks;

                // Resume Exams
                $examScheduleKey = $exam->id . '_' . ($exam->schedule_id ?: 0); // Use 0 if no schedule_id is provided
                $isResume = isset($examResultExamScheduleMap[$examScheduleKey]);

                if ($exam->is_public === 1 && !$exam->schedule_id) {
                    $isResume = isset($examResultExamScheduleMap[$exam->id . '_0']);
                }

                // Attempts
                $totalAttempt = $exam->total_attempts ?? 1;
                $totalAttempt = $exam->restrict_attempts == 1 ? $totalAttempt : null;

                // Attempts Completed or not checking
                $scheduleId = $exam->schedule_id ?? 0;
                $userAttempt = ExamResult::where('user_id',$user->id)->where('exam_id',$exam->id)->where('schedule_id',$scheduleId)->count();

                if($exam->restrict_attempts == 1 && $userAttempt >= $totalAttempt){
                    continue;
                }

                // Add exam details to the corresponding type slug, including schedule details
                $formattedExamData[] = [
                    'id' => $exam->id,
                    'exam_type_slug' => $exam->exam_type_slug,
                    'slug' => $exam->exam_slug,
                    'title' => $exam->exam_name,
                    'duration_mode' => $exam->duration_mode,
                    'exam_duration' => $exam->exam_duration,
                    'point_mode' => $exam->point_mode,
                    'point' => $exam->point,
                    'is_free' => $checkfree,
                    'total_questions' => $exam->total_questions,
                    'total_marks' => $marks,
                    'total_time' => $time,
                    'is_resume' => $isResume,
                    'total_attempts'=>$totalAttempt,
                    'schedules' => [
                        'schedule_id' =>  $exam->schedule_id ?: 0,
                        'schedule_type' => $exam->schedule_type,
                        'start_date' => $exam->start_date,
                        'start_time' => $exam->start_time,
                        'end_date' => $exam->end_date,
                        'end_time' => $exam->end_time,
                        'grace_period' => $exam->grace_period,
                    ],
                ];
            }

            // Return success JSON response with upcoming exams and schedules
            return response()->json([
                'status' => true,
                'data' => $formattedExamData
            ], 200);
        } catch (\Throwable $th) {
            \Log::error('Error fetching exams: ' . $th->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while fetching exams.',
                'error' => $th->getMessage()
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
