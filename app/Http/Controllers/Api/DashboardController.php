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
            // Get the authenticated user
            $user = $request->attributes->get('authenticatedUser');

            // Fetch all exam results for the authenticated user where status is complete
            $exams = ExamResult::where('user_id', $user->id)
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

            $exams = Exam::where('status',1)->get();
            $quizs = Quizze::where('status',1)->get();

             // return  success json respose
            return [
                'status'=>true,
                'pass_exam'=>$passedExamCount ?? 0,
                'failed_exam'=>$failedExamCount ?? 0,
                'average_exam'=>$averageScore ?? 0,
                'exams'=>$exams,
                'quizs'=>$quizs,
            ];
        } catch (\Throwable $th) {
            // return  error json respose
        }

    }
}
