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
            $request->validate(['category' => 'required']);
    
            // Get the authenticated user
            $user = $request->attributes->get('authenticatedUser');
    
            // Fetch all exam results for the authenticated user where status is complete
            $exams = ExamResult::where('user_id', $user->id)
                ->where('subcategory_id', $request->category)
                ->where('status', 'complete')
                ->with('exam') // Assuming you have a relation set up
                ->get()
                ->map(function($examResult) {
                    return [
                        'exam_title' => $examResult->exam->title ?? 'N/A',
                        'duration' => $examResult->exam->exam_duration ?? 'N/A',
                        'total_questions' => $examResult->examQuestions->count() ?? 0,
                        'status' => $examResult->status,
                    ];
                });
    
            // Fetch all exam results for the authenticated user where status is complete
            $exams = ExamResult::where('user_id', $user->id)
            ->where('subcategory_id', $request->category)
            ->where('status', 'complete')
            ->with(['exam', 'examQuestions']) // Eager load the related models
            ->get()
            ->map(function($examResult) {
                return [
                    'exam_title' => $examResult->exam->title ?? 'N/A',
                    'duration' => $examResult->exam->exam_duration ?? 'N/A',
                    'total_questions' => $examResult->examQuestions->count() ?? 0, // Safely count
                    'status' => $examResult->status,
                ];
            });
    
            // Extract the counts and average score
            $passedExamCount = $examStats->passed_count ?? 0;
            $failedExamCount = $examStats->failed_count ?? 0;
            $averageScore = $examStats->average_score ?? 0;
    
            // Fetch quizzes with only the required fields
            $quizzes = Quizze::where('subcategory_id', $request->category)
            ->where('status', 1)
            ->withCount('quizQuestions') // This should work correctly
            ->get()
            ->map(function($quiz) {
                return [
                    'quiz_title' => $quiz->title,
                    'duration' => $quiz->duration,
                    'total_questions' => $quiz->quiz_questions_count, // Should be accessible if counted correctly
                    'status' => $quiz->status,
                ];
            });
    
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
                'error' => 'Error logged. :' . $th->getMessage() // For security
            ], 500);
        }
    }
    
}
