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
            ]);
    
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
                    'exams.duration_type', // duration_type
                    'exams.point_mode', 
                    'exams.exam_duration', // exam_duration
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
                ->where('exams.slug', $slug)
                ->where('exams.subcategory_id', $request->category)
                ->where('exams.status', 1)
                ->where('questions.status', 1)
                ->groupBy(
                    'exams.id', 'exams.title', 'exams.description', 'exams.pass_percentage',
                    'exams.slug', 'exams.subcategory_id', 'exams.status', 'exams.duration_type',
                    'exams.point_mode', 'exams.exam_duration', 'exams.point', 'exams.shuffle_questions',
                    'exams.question_view', 'exams.disable_finish_button', 'exams.negative_marking',
                    'exams.negative_marking_type', 'exams.negative_marks','exams.is_free'
                )
                ->first();
    
            // If exam not found
            if (!$exam) {
                return response()->json(['status' => false, 'error' => 'Exam not found'], 404);
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
    
            // Get the authenticated user
            $user = $request->attributes->get('authenticatedUser');
    
            // Fetch all completed exam results
            $checkOngoingResult = ExamResult::where('user_id', $user->id)
                ->where('exam_id', $exam->id)
                ->where('status', 'complete')
                ->get();
    
            // Restrict exam attempts based on the configured limit
            if ($exam->restrict_attempts == 1 && $exam->total_attempts <= $checkOngoingResult->count()) {
                return response()->json(['status' => false, 'error' => 'Maximum Attempt Reached'], 403);
            }
    
            // Check for ongoing exam
            $ongoingExam = ExamResult::where('user_id', $user->id)
                ->where('exam_id', $exam->id)
                ->where('status', 'ongoing') // Correct the status check
                ->latest('created_at')
                ->first();
    
            if ($ongoingExam) {
                // $remainingDuration = $ongoingExam->end_time->diffInMinutes(now());
                $remainingDuration = now()->diffInMinutes($ongoingExam->end_time); // Ensure no negative duration

                if ($ongoingExam->end_time->isPast()) {
                    // If time has passed, mark the exam as complete
                    $ongoingExam->update(['status' => 'complete']);
                    $data = [
                        'uuid'=>$ongoingExam->uuid,
                    ];
                    return response()->json(['status' => true, 'message' => 'Exam Timed Out','data'=>$data]);
                } else {
                    // Return ongoing exam details
                    return response()->json([
                        'status' => true,
                        'data' => [
                            'title' => $exam->title,
                            'uuid'=>$ongoingExam->uuid,
                            'questions' => json_decode($ongoingExam->questions),
                            'duration' => $remainingDuration . " mins",
                            'points' => $ongoingExam->point,
                            'question_view' => $exam->question_view == 1 ? "enable" : "disable",
                            'finish_button' => $exam->disable_finish_button == 1 ? "enable" : "disable"
                        ]
                    ], 200);
                }
            }
    
            // Calculate exam duration and points
            $duration = (int) ($exam->duration_mode == "manual" && $exam->duration > 0 ? $exam->duration : round($exam->total_time / 60, 2));
            $points = $exam->point_mode == "manual" ? $exam->point : $exam->total_marks;
    
            // Prepare structured response data for questions
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
                'questions' => json_encode($questionsData,true),
                'correct_answers' => json_encode($correctAnswers,true),
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
                    'uuid'=>$examResult->uuid,
                    'questions' => json_decode($examResult->questions),
                    'duration' => $remainingDuration . " mins",
                    'points' => $examResult->point,
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
    
        // Total marks should be fixed in manual mode
        $totalMarks = $examResult->point_type == "manual" ? $examResult->point * count($user_answer) : 0; 
    
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

            // $userAnswer = $answer['answer'];
            // // In default mode, accumulate total possible marks
            // if ($examResult->point_type != "manual") {
            //     $totalMarks += $question->default_marks;
            // }
    
            // // Check correctness based on question type
            // if ($question->type == 'MSA') {
            //     $isCorrect = $question->answer == $userAnswer;
            // } elseif ($question->type == 'MMA') {
            //     $correctAnswers = json_decode($question->answer, true);
            //     sort($correctAnswers);
            //     sort($userAnswer);
            //     $isCorrect = $userAnswer == $correctAnswers;
            // } elseif ($question->type == 'TOF') {
            //     $isCorrect = $userAnswer == $question->answer;
            // } elseif ($question->type == 'SAQ') {
            //     $answers = json_decode($question->options);
            //     $isCorrect = in_array($userAnswer, $answers);
            // } elseif ($question->type == 'FIB') {
            //     $correctAnswers = json_decode($question->answer, true);
            //     sort($correctAnswers);
            //     sort($userAnswer);
            //     $isCorrect = $userAnswer == $correctAnswers;
            // } elseif ($question->type == 'MTF') {
            //     $correctAnswers = json_decode($question->answer, true);
            //     foreach ($correctAnswers as $key => $value) {
            //         if ($userAnswer[$key] != $value) {
            //             $isCorrect = false;
            //             break;
            //         }
            //     }
            //     $isCorrect = true;
            // } elseif ($question->type == 'ORD') {
            //     $correctAnswers = json_decode($question->answer, true);
            //     $isCorrect = $userAnswer == $correctAnswers;
            // } elseif ($question->type == 'EMQ') {
            //     $correctAnswers = json_decode($question->answer, true);
            //     sort($userAnswer);
            //     sort($correctAnswers);
            //     $isCorrect = $userAnswer == $correctAnswers;
            // }
    
            // if ($isCorrect) {
            //     $score += $examResult->point_type == "manual" ? $examResult->point : $question->default_marks;
            //     $correctAnswer += 1;
            // } else {
            //     $incorrect += 1;
            //     $incorrectMarks += $question->default_marks;
            // }


            if (isset($answer['answer'])) {
                $userAnswer = $answer['answer'];
                // In default mode, accumulate total possible marks
                if ($examResult->point_type != "manual") {
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
                    $score += $examResult->point_type == "manual" ? $examResult->point : $question->default_marks;
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
            'student_percentage' => $studentPercentage
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
    
                $exam = [
                    'title' => $examResult->exam->title,
                    'duration' => $examResult->exam_duration,
                    'download_report'=> $examResult->exam->download_report,
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

    public function examAll(Request $request)
    {
        try {
            // Validate the request
            $request->validate(['category' => 'required']);

            // Fetch the current authenticated user (assuming $user is passed correctly)
            $user = $request->attributes->get('authenticatedUser');

            // Fetch the exam IDs assigned to the current user
            $assignedExams = AssignedExam::select('exam_id')->where('user_id', $user->id)->get()->pluck('exam_id')->toArray();

            // Fetch all exam data including the assigned exams and making assigned exams free
            $examData = Exam::select(
                'exam_types.slug as exam_type_slug',
                'exams.slug',
                'exams.title',
                'exams.duration_mode',
                'exams.exam_duration',
                'exams.point_mode',
                'exams.point',
                'exams.is_free',
                'exams.price',
                DB::raw('COUNT(questions.id) as total_questions'), // Count total questions for each exam
                DB::raw('SUM(CAST(questions.default_marks AS DECIMAL)) as total_marks'), // Sum total marks for each exam
                DB::raw('SUM(COALESCE(questions.watch_time, 0)) as total_time') // Sum time for each question using watch_time
            )
            ->leftJoin('exam_types', 'exams.exam_type_id', '=', 'exam_types.id') // Join with exam_types
            ->leftJoin('exam_questions', 'exams.id', '=', 'exam_questions.exam_id') // Join with exam_questions
            ->leftJoin('questions', 'exam_questions.question_id', '=', 'questions.id') // Join with questions
            ->where(function($query) use ($assignedExams) {
                $query->where('exams.is_public', 1) // Public exams
                    ->orWhereIn('exams.id', $assignedExams); // Private exams assigned to the user
            })
            ->where('exams.subcategory_id', $request->category) // Filter by subcategory ID
            ->where('exams.status', 1) // Filter by exam status
            ->groupBy('exam_types.slug', 'exams.slug', 'exams.id', 'exams.title', 'exams.duration_mode', 
                    'exams.exam_duration', 'exams.point_mode', 'exams.point', 'exams.is_free', 'exams.price') // Group by necessary fields
            ->havingRaw('COUNT(questions.id) > 0') // Only include exams with more than 0 questions
            ->get();

            // Adjust 'is_free' for assigned exams
            $examData->transform(function ($exam) use ($assignedExams) {
                // If the exam is assigned to the user, set it to free regardless of its original price
                if (in_array($exam->id, $assignedExams)) {
                    $exam->is_free = 1; // Make assigned exams free
                }
                return $exam;
            });

            // Return success JSON response
            return response()->json([
                'status' => true,
                'data' => $examData
            ], 200);
        } catch (\Throwable $th) {
            // Return error JSON response
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while fetching the exam data.',
                'error' => 'Error logged. :' . $th->getMessage() // For security
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
    
}
