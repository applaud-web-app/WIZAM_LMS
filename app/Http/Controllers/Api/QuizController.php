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
                    // pass uuid also
                ]
            ], 200);
    
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'error' => 'Internal Server Error: ' . $th->getMessage()], 500);
        }
    }
    
    public function saveProgress(Request $request, $uuid) {
        try {
            // Validate request data
            $request->validate([
                'answers' => 'required|array',
            ]);

            // Get the authenticated user
            $user = $request->attributes->get('authenticatedUser');

            // Find the quiz session by UUID
            $quizResult = StudentQuizResult::where('uuid', $uuid)->where('user_id',$user->id)->firstOrFail();

            // Update the answers in the database
            $quizResult->update([
                'answers' => json_encode($request->answers), // Save the user's answers as JSON
                'updated_at' => now() // Update the timestamp
            ]);

            return response()->json(['status' => true, 'message' => 'Progress saved successfully'], 200);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'error' => 'Internal Server Error: ' . $th->getMessage()], 500);
        }
    }

    public function finishQuiz(Request $request, $uuid) {
        try {
            // Validate request data
            $request->validate([
                'answers' => 'required|array',
            ]);

            $quizResult = StudentQuizResult::where('uuid', $uuid)->firstOrFail();
    
            // Update status to completed and mark the end time
            $quizResult->update([
                'status' => 'completed',
                'answers' => json_encode($request->answers), // Save the user's answers as JSON
                'end_time' => now(),
            ]);
    
            // Calculate correct and incorrect answers (you can customize this based on your logic)
            // $correctAnswers = calculateCorrectAnswers($quizResult->answers);
            // $incorrectAnswers = calculateIncorrectAnswers($quizResult->answers);
    
            // You can also calculate points based on correct answers, negative marking, etc.
    
            return response()->json(['status' => true, 'Progress saved successfully'], 200);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'error' => 'Internal Server Error: ' . $th->getMessage()], 500);
        }
    }
    
    
    

    
}
