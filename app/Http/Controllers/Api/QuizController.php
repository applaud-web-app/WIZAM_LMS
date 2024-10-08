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
    
    // public function saveQuizProgress(Request $request, $uuid) {
    //     try {
    //         // Validate request data
    //         $request->validate([
    //             'answers' => 'required|array', // USE QUESTION ID WITH ANSWER
    //         ]);

    //         // Get the authenticated user
    //         $user = $request->attributes->get('authenticatedUser');

    //         // Find the quiz session by UUID
    //         $quizResult = QuizResult::where('uuid', $uuid)->where('user_id',$user->id)->firstOrFail();

    //         // Update the answers in the database
    //         $quizResult->update([
    //             'answers' => json_encode($request->answers), // Save the user's answers as JSON
    //             'updated_at' => now() // Update the timestamp
    //         ]);

    //         return response()->json(['status' => true, 'message' => 'Progress saved successfully'], 200);
    //     } catch (\Throwable $th) {
    //         return response()->json(['status' => false, 'error' => 'Internal Server Error: ' . $th->getMessage()], 500);
    //     }
    // }

    // public function finishQuiz(Request $request, $uuid) {
    //     try {
    //         // Validate the incoming request data
    //         $request->validate([
    //             'answers' => 'required|array', // Expecting an array of answers with question IDs
    //         ]);
    
    //         // Get the authenticated user
    //         $user = $request->attributes->get('authenticatedUser');
    
    //         // Fetch the quiz result based on the UUID and user ID
    //         $quizResult = QuizResult::where('uuid', $uuid)->where('user_id', $user->id)->firstOrFail();
    
    //         // Retrieve the stored questions from the quiz result
    //         $questions = json_decode($quizResult->questions, true); // Convert to an array
    //         $userAnswers = $request->answers; // Array of user's submitted answers
    
    //         // Initialize counters for correct and incorrect answers
    //         $correctAnswersCount = 0;
    //         $incorrectAnswersCount = 0;
    //         $totalMarks = 0;
    //         $negativeMarking = (float) $quizResult->negative_marking; // Negative marking if applicable
    
    //         // Create an associative array for quick access to correct answers
    //         $correctAnswersMap = [];
    //         foreach ($questions as $question) {
    //             $correctAnswersMap[$question['id']] = $question['correct_answer']; // Assuming correct_answer is stored in correct_answers field
    //         }
    
    //         // Loop through each submitted answer and compare it with the correct answer from the database
    //         foreach ($userAnswers as $userAnswer) {
    //             $questionId = $userAnswer['id']; // Question ID
    //             $userSubmittedAnswer = $userAnswer['answer']; // User's submitted answer
    
    //             // Compare the user's answer with the correct answer
    //             if (array_key_exists($questionId, $correctAnswersMap)) {
    //                 $correctAnswer = $correctAnswersMap[$questionId];
    
    //                 // Compare based on the question type
    //                 $isCorrect = false;
    
    //                 // Handle different question types (assuming it's stored as a string or array)
    //                 if (is_array($correctAnswer)) {
    //                     // For multiple-choice, check if user answer matches any correct answer
    //                     $isCorrect = in_array($userSubmittedAnswer, $correctAnswer);
    //                 } else {
    //                     // For single answer question
    //                     $isCorrect = $userSubmittedAnswer == $correctAnswer;
    //                 }
    
    //                 if ($isCorrect) {
    //                     $correctAnswersCount++;
    //                     $totalMarks += (float) $quizResult->correct_answers[$questionId]['default_marks']; // Add marks for correct answer
    //                 } else {
    //                     $incorrectAnswersCount++;
    //                     if ($negativeMarking > 0) {
    //                         $totalMarks -= $negativeMarking; // Deduct marks for incorrect answer, if negative marking is enabled
    //                     }
    //                 }
    //             }
    //         }
    
    //         // Update the quiz result with the calculated values
    //         $quizResult->update([
    //             'correct_answer' => $correctAnswersCount,
    //             'incorrect_answer' => $incorrectAnswersCount,
    //             'point' => max(0, $totalMarks), // Ensure that total points don't go below zero
    //             'status' => 'completed',
    //             'end_time' => now(),
    //             'answers' => json_encode($request->answers), // Store user's answers
    //         ]);
    
    //         // Return success response
    //         return response()->json(['status' => true, 'message' => 'Quiz completed successfully'], 200);
    //     } catch (\Throwable $th) {
    //         return response()->json(['status' => false, 'error' => 'Internal Server Error: ' . $th->getMessage()], 500);
    //     }
    // }

    public function finishQuiz(Request $request, $uuid) {
        try {
            // Validate the incoming request data
            $request->validate([
                'answers' => 'required|array', // Expecting an array of answers with question IDs
            ]);
    
            // Get the authenticated user
            $user = $request->attributes->get('authenticatedUser');
    
            // Fetch the quiz result based on the UUID and user ID
            $quizResult = QuizResult::where('uuid', $uuid)->where('user_id', $user->id)->firstOrFail();
    
            // Retrieve the stored questions from the quiz result
            $questions = json_decode($quizResult->questions, true); // Convert to an array
            $userAnswers = $request->answers; // Array of user's submitted answers

            // ANSWERS 
            $correct_answers = json_decode($quizResult->correct_answers, true);
            // DEMO FOR correct_answers store value - [{"id":2,"correct_answer":"2","default_marks":"5"}]
    
            // Initialize counters for correct and incorrect answers
            $correctAnswersCount = 0;
            $incorrectAnswersCount = 0;
            $totalMarks = 0;
            $negativeMarking = (float) $quizResult->negative_marking; // Negative marking if applicable
    
            // Create an associative array for quick access to correct answers
            $correctAnswersMap = [];
            foreach ($questions as $question) {
                $correctAnswersMap[$question['id']] = $correct_answers[$question['id']]; 
            }
    
            // Loop through each submitted answer and compare it with the correct answer from the database
            foreach ($userAnswers as $userAnswer) {
                $questionId = $userAnswer['id']; // Question ID
                $userSubmittedAnswer = $userAnswer['answer']; // User's submitted answer
    
                // Compare the user's answer with the correct answer
                if (array_key_exists($questionId, $correctAnswersMap)) {
                    $correctAnswer = $correctAnswersMap[$questionId];
    
                    // Compare based on the question type
                    $isCorrect = false;
    
                    // Handle different question types (assuming it's stored as a string or array)
                    if (is_array($correctAnswer)) {
                        // For multiple-choice, check if user answer matches any correct answer
                        $isCorrect = !empty(array_intersect($userSubmittedAnswer, $correctAnswer));
                    } else {
                        // For single answer question
                        $isCorrect = $userSubmittedAnswer == $correctAnswer;
                    }
    
                    if ($isCorrect) {
                        $correctAnswersCount++;
                        $totalMarks += (float) $quizResult->correct_answers[$questionId]['default_marks']; // Add marks for correct answer
                    } else {
                        $incorrectAnswersCount++;
                        if ($negativeMarking > 0) {
                            $totalMarks -= $negativeMarking; // Deduct marks for incorrect answer, if negative marking is enabled
                        }
                    }
                }
            }
    
            // Update the quiz result with the calculated values
            $quizResult->update([
                'correct_answer' => $correctAnswersCount,
                'incorrect_answer' => $incorrectAnswersCount,
                'point' => max(0, $totalMarks), // Ensure that total points don't go below zero
                'status' => 'completed',
                'end_time' => now(),
                'answers' => json_encode($userAnswers), // Store user's answers
            ]);
    
            // Return success response
            return response()->json(['status' => true, 'message' => 'Quiz completed successfully'], 200);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'error' => 'Internal Server Error: ' . $th->getMessage()], 500);
        }
    }

    // public function finalSubmit(Request $request)
    // {
    //     // Validate the incoming request
    //     $validator = Validator::make($request->all(), [
    //         'quizId' => 'required|exists:quizzes,id', // Adjust table name
    //         'answers' => 'required|array',
    //         'answers.*.questionId' => 'required|exists:questions,id', // Adjust table name
    //         'answers.*.answers' => 'required|array',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json([
    //             'status' => false,
    //             'errors' => $validator->errors(),
    //         ], 400);
    //     }

    //     $quizId = $request->quizId;
    //     $submittedAnswers = $request->answers;

    //     // Fetch the correct answers from the database
    //     $correctAnswers = Question::whereIn('id', array_column($submittedAnswers, 'questionId'))
    //         ->with('correctAnswer') // Assuming you have a relationship set up to get correct answers
    //         ->get()
    //         ->keyBy('id');

    //     $score = 0;
    //     $totalQuestions = count($submittedAnswers);

    //     // Compare submitted answers with the correct answers
    //     foreach ($submittedAnswers as $submittedAnswer) {
    //         $questionId = $submittedAnswer['questionId'];
    //         $userAnswers = $submittedAnswer['answers'];
    //         $correctAnswer = $correctAnswers[$questionId]->correctAnswer; // Adjust this if you have a different structure

    //         // Implement your own logic to compare answers
    //         if ($this->checkAnswers($userAnswers, $correctAnswer)) {
    //             $score++;
    //         }
    //     }

    //     // Store the result in the quiz_results table
    //     $quizResult = new QuizResult();
    //     $quizResult->quiz_id = $quizId;
    //     $quizResult->user_id = auth()->id(); // Assuming you have authentication
    //     $quizResult->score = $score;
    //     $quizResult->total_questions = $totalQuestions;
    //     $quizResult->correct_answers = json_encode($submittedAnswers); // Store user answers for reference
    //     $quizResult->save();

    //     return response()->json([
    //         'status' => true,
    //         'message' => 'Quiz submitted successfully!',
    //         'score' => $score,
    //         'total_questions' => $totalQuestions,
    //     ]);
    // }

    // private function checkAnswers(array $userAnswers, $correctAnswer)
    // {
    //     // Implement your logic to check if the user answers match the correct answer
    //     // For example, for multiple-choice, check if they match
    //     return $userAnswers == $correctAnswer; // Adjust this comparison logic as necessary
    // }
}
