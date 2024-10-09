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

class ExamController extends Controller
{
    public function playExam(Request $request, $slug)
    {
        try {
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
                    'exams.duration_mode',
                    'exams.point_mode',
                    'exams.duration',
                    'exams.point',
                    'exams.shuffle_questions',
                    'exams.question_view',
                    'exams.disable_finish_button',
                    'exams.negative_marking',
                    'exams.negative_marking_type',
                    'exams.negative_marks',
                    DB::raw('SUM(questions.default_marks) as total_marks'),
                    DB::raw('SUM(COALESCE(questions.watch_time, 0)) as total_time')
                )
                ->leftJoin('exam_questions', 'exams.id', '=', 'exam_questions.exams_id')
                ->leftJoin('questions', 'exam_questions.question_id', '=', 'questions.id')
                ->where('exams.slug', $slug)
                ->where('exams.subcategory_id', $request->category)
                ->where('exams.status', 1)
                ->where('questions.status', 1)
                ->groupBy(
                    'exams.id', 'exams.title', 'exams.description', 'exams.pass_percentage',
                    'exams.slug', 'exams.subcategory_id', 'exams.status', 'exams.duration_mode',
                    'exams.point_mode', 'exams.duration', 'exams.point', 'exams.shuffle_questions',
                    'exams.question_view', 'exams.disable_finish_button', 'exams.negative_marking',
                    'exams.negative_marking_type', 'exams.negative_marks'
                )
                ->first();
    
            // If exam not found
            if (!$exam) {
                return response()->json(['status' => false, 'error' => 'Exam not found'], 404);
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
}
