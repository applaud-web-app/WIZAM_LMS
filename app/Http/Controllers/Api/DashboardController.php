<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ExamResult;
use App\Models\Exam;
use App\Models\Quizze;

class DashboardController extends Controller
{
    public function studentDashboard(Request $request){
        try {
            $request->valdiate([
                'category'=>'required',
            ]);
            // Get the authenticated user
            $user = $request->attributes->get('authenticatedUser');

            // Fetch all exam results for the authenticated user where status is complete
            $exams = ExamResult::where('user_id', $user->id)->where('subcategory_id',$request->category)
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
            $passedExamCount = $examStats->passed_count;
            $failedExamCount = $examStats->failed_count;
            $averageScore = $examStats->average_score;

            // $exams = Exam::where('subcategory_id',$request->category)->select('title','is_free','total_question','total_duration')->where('status',1)->get();
            // $quizs = Quizze::where('subcategory_id',$request->category)->select('title','is_free','total_question','total_duration')->where('status',1)->get();

            $exams = Exam::withCount('examQuestions') // Count total questions
            ->with([ 
                'examQuestions.questions' => function($query) {
                    $query->select('id', 'default_marks', 'watch_time', 'status');
                }
            ])
            ->select(
                'exams.id',
                'exams.title',
                'exams.is_free',
                'exams.pass_percentage',
                'exams.slug',
                'exams.subcategory_id',
                'exams.status',
                'exams.exam_duration',
                DB::raw('SUM(COALESCE(exam_questions.watch_time, 0)) as total_duration') // Total duration
            )
            ->where('exams.subcategory_id', $request->category)
            ->where('exams.status', 1)
            ->groupBy(
                'exams.id',
                'exams.title',
                'exams.is_free',
                'exams.pass_percentage',
                'exams.slug',
                'exams.subcategory_id',
                'exams.status',
                'exams.exam_duration'
            )
            ->get();

            $quizzes = Quizze::withCount('quizQuestions') // Count total questions
            ->with([
                'quizQuestions.questions' => function($query) {
                    $query->select('id', 'default_marks', 'watch_time', 'status');
                }
            ])
            ->select(
                'quizzes.id',
                'quizzes.title',
                'quizzes.is_free',
                DB::raw('COUNT(quiz_questions.id) as total_questions'), // Count total questions
                DB::raw('SUM(COALESCE(questions.watch_time, 0)) as total_duration') // Total duration
            )
            ->where('quizzes.subcategory_id', $request->category)
            ->where('quizzes.status', 1)
            ->groupBy(
                'quizzes.id',
                'quizzes.title',
                'quizzes.is_free'
            )
            ->get();



             // return  success json respose
            return [
                'status'=>true,
                'pass_exam'=>$passedExamCount ?? 0,
                'failed_exam'=>$failedExamCount ?? 0,
                'average_exam'=>$averageScore ?? 0,
                'exams'=>$exams,
                'quizs'=>$quizzes,
            ];
        } catch (\Throwable $th) {
            // return  error json respose
        }

    }
}
