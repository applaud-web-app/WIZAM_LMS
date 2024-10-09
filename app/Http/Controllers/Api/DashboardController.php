<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ExamResult;
use App\Models\Exam;
use App\Models\Quizze;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str; 
use Carbon\Carbon;
use App\Models\Question;

class DashboardController extends Controller
{
    public function studentDashboard(Request $request)
    {
        try {
            // Validate the request
            $request->validate([
                'category' => 'required',
            ]);

            // Get the authenticated user
            $user = $request->attributes->get('authenticatedUser');

            // Fetch all exam results for the authenticated user where status is complete
            $exams = ExamResult::where('user_id', $user->id)
                ->where('subcategory_id', $request->category)
                ->where('status', 'complete')
                ->get();

            // Use a single query to get passed, failed counts, and the average score
            $examStats = ExamResult::selectRaw(
                    'COUNT(CASE WHEN student_percentage >= pass_percentage THEN 1 END) as passed_count, ' .
                    'COUNT(CASE WHEN student_percentage < pass_percentage THEN 1 END) as failed_count, ' .
                    'AVG(student_percentage) as average_score'
                )
                ->where('user_id', $user->id)
                ->where('status', 'complete')
                ->first();

            // Extract the counts and average score
            $passedExamCount = $examStats->passed_count ?? 0;
            $failedExamCount = $examStats->failed_count ?? 0;
            $averageScore = $examStats->average_score ?? 0;

            // Fetch exams
            // Fetch the exam along with related questions in one query
            $exam = Exam::with([
                'examQuestions.questions' => function($query) {
                    $query->select('id', 'default_marks', 'watch_time');
                }
            ])
            ->select(
                'exams.id',
                'exams.title',
                'exams.slug',
                'exams.subcategory_id',
                'exams.status',
                'exams.duration_type', // duration_type
                'exams.point_mode', 
                'exams.exam_duration', // exam_duration
                'exams.point',
                DB::raw('SUM(questions.default_marks) as total_marks'),
                DB::raw('SUM(COALESCE(questions.watch_time, 0)) as total_time')
            )
            ->leftJoin('exam_questions', 'exams.id', '=', 'exam_questions.exam_id')
            ->leftJoin('questions', 'exam_questions.question_id', '=', 'questions.id')
            ->where('exams.subcategory_id', $request->category)
            ->where('exams.status', 1)
            ->where('questions.status', 1)
            ->groupBy(
                'exams.id', 'exams.title',
                'exams.slug', 'exams.subcategory_id', 'exams.status', 'exams.duration_type',
                'exams.point_mode', 'exams.exam_duration', 'exams.point'
            )
            ->first();

            // Fetch quizzes
            $quizzes = Quizze::with([
                'quizQuestions.questions' => function($query) {
                    $query->select('id', 'default_marks', 'watch_time');
                }
            ])
            ->select(
                'quizzes.id',
                'quizzes.title',
                'quizzes.slug',
                'quizzes.subcategory_id',
                'quizzes.status',
                'quizzes.duration_mode',
                'quizzes.point_mode',
                'quizzes.duration',
                'quizzes.point',
                DB::raw('SUM(questions.default_marks) as total_marks'),
                DB::raw('SUM(COALESCE(questions.watch_time, 0)) as total_time')
            )
            ->leftJoin('quiz_questions', 'quizzes.id', '=', 'quiz_questions.quizzes_id')
            ->leftJoin('questions', 'quiz_questions.question_id', '=', 'questions.id')
            ->where('quizzes.subcategory_id', $request->category)
            ->where('quizzes.status', 1)
            ->where('questions.status', 1)
            ->groupBy(
                'quizzes.id', 'quizzes.title', 'quizzes.slug', 'quizzes.subcategory_id', 'quizzes.status', 'quizzes.duration_mode',
                'quizzes.point_mode', 'quizzes.duration', 'quizzes.point'
            )
            ->first();

            // Return success JSON response
            return response()->json([
                'status' => true,
                'pass_exam' => $passedExamCount,
                'failed_exam' => $failedExamCount,
                'average_exam' => $averageScore,
                'exams' => $exams,
                'quizzes' => $quizzes,
            ], 200);
        } catch (\Throwable $th) {
            // Return error JSON response
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while fetching the dashboard data.',
                'error' => $th->getMessage(), // You can choose to log this instead of sending to the client
            ], 500);
        }
    }
}
