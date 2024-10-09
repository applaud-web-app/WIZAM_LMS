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
            $examData = Exam::select(
                'exam_types.slug as exam_type_slug', // Fetch exam type slug
                'exams.slug', // Fetch exam slug
                'exams.title', // Fetch exam title
                DB::raw('COUNT(questions.id) as total_questions'), // Count total questions for each exam
                DB::raw('SUM(CAST(questions.default_marks AS DECIMAL)) as total_marks'), // Sum total marks for each exam
                DB::raw('SUM(COALESCE(questions.watch_time, 0)) as total_time') // Sum time for each question using watch_time
            )
            ->leftJoin('exam_types', 'exams.exam_type_id', '=', 'exam_types.id') // Join with exam_types
            ->leftJoin('exam_questions', 'exams.id', '=', 'exam_questions.exam_id') // Join with exam_questions
            ->leftJoin('questions', 'exam_questions.question_id', '=', 'questions.id') // Join with questions
            ->where('exams.subcategory_id', $request->category) // Filter by subcategory ID
            ->where('exams.status', 1) // Filter by exam status
            ->groupBy('exam_types.slug', 'exams.slug', 'exams.id', 'exams.title') // Group by necessary fields
            ->havingRaw('COUNT(questions.id) > 0') // Only include exams with more than 0 questions
            ->get();


            $quizData = Quizze::select(
                'quiz_types.slug as exam_type_slug',
                'quizzes.title',
                'quizzes.description',
                'quizzes.pass_percentage',
                'sub_categories.name as sub_category_name',
                'quiz_types.name as exam_type_name',
                DB::raw('COUNT(questions.id) as total_questions'),  // Count the total number of questions
                DB::raw('SUM(CAST(questions.default_marks AS DECIMAL)) as total_marks'),  // Sum the total marks
                DB::raw('SUM(COALESCE(questions.watch_time, 0)) as total_time')  // Sum the total time for the quiz
            )
            ->leftJoin('quiz_types', 'quizzes.quiz_type_id', '=', 'quiz_types.id')
            ->leftJoin('sub_categories', 'quizzes.subcategory_id', '=', 'sub_categories.id')
            ->leftJoin('quiz_questions', 'quizzes.id', '=', 'quiz_questions.quizzes_id')  // Join with quiz_questions
            ->leftJoin('questions', 'quiz_questions.question_id', '=', 'questions.id')  // Join with questions
            ->where('quizzes.subcategory_id', $request->category)  // Filter by category
            ->where('quizzes.status', 1)  // Only active quizzes
            ->groupBy(
                'quiz_types.slug',
                'quizzes.id',
                'quizzes.title',
                'quizzes.description',
                'quizzes.pass_percentage',
                'sub_categories.name',
                'quiz_types.name'
            )
            ->havingRaw('COUNT(questions.id) > 0')  // Ensure quizzes with more than 0 questions
            ->first();
    
            // Return success JSON response
            return response()->json([
                'status' => true,
                'pass_exam' => $passedExamCount,
                'failed_exam' => $failedExamCount,
                'average_exam' => $averageScore,
                'exams' => $examData,
                'quizzes' => $quizData,
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
