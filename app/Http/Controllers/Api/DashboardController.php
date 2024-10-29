<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ExamResult;
use App\Models\ExamSchedule;
use App\Models\Exam;
use App\Models\Quizze;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str; 
use Carbon\Carbon;
use App\Models\Question;
use App\Models\AssignedExam;

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
            $exams = ExamResult::where('user_id', $user->id)->where('status', 'complete')->get();

            // Use a single query to get passed, failed counts, and the average score
            $examStats = ExamResult::selectRaw(
                'COUNT(CASE WHEN student_percentage >= pass_percentage THEN 1 END) as passed_count, ' .
                'COUNT(CASE WHEN student_percentage < pass_percentage THEN 1 END) as failed_count, ' .
                'AVG(student_percentage) as average_score'
            )->where('user_id', $user->id)->where('status', 'complete')->first();

            // Extract the counts and average score
            $passedExamCount = $examStats->passed_count;
            $failedExamCount = $examStats->failed_count;
            $averageScore = $examStats->average_score;
    
            // Fetch all exam results for the authenticated user where status is complete
            // $examData = Exam::select(
            //     'exam_types.slug as exam_type_slug', 
            //     'exams.slug', 
            //     'exams.title', 
            //     'exams.duration_mode', 
            //     'exams.exam_duration', 
            //     'exams.point_mode',
            //     'exams.point', 
            //     DB::raw('COUNT(questions.id) as total_questions'), // Count total questions for each exam
            //     DB::raw('SUM(CAST(questions.default_marks AS DECIMAL)) as total_marks'), // Sum total marks for each exam
            //     DB::raw('SUM(COALESCE(questions.watch_time, 0)) as total_time') // Sum time for each question using watch_time
            // )
            // ->leftJoin('exam_types', 'exams.exam_type_id', '=', 'exam_types.id') // Join with exam_types
            // ->leftJoin('exam_questions', 'exams.id', '=', 'exam_questions.exam_id') // Join with exam_questions
            // ->leftJoin('questions', 'exam_questions.question_id', '=', 'questions.id') // Join with questions
            // ->where('exams.subcategory_id', $request->category) // Filter by subcategory ID
            // ->where('exams.status', 1) // Filter by exam status
            // ->groupBy('exam_types.slug', 'exams.slug', 'exams.id', 'exams.title','exams.duration_mode', 'exams.exam_duration','exams.point_mode', 'exams.point') // Group by necessary fields
            // ->havingRaw('COUNT(questions.id) > 0') // Only include exams with more than 0 questions
            // ->get();

            // $quizData = Quizze::select(
            //     'quiz_types.slug as exam_type_slug',
            //     'quizzes.title',
            //     'quizzes.description',
            //     'quizzes.pass_percentage',
            //     'sub_categories.name as sub_category_name',
            //     'quiz_types.name as exam_type_name',
            //     'quizzes.duration_mode', 
            //     'quizzes.duration', 
            //     'quizzes.point_mode',
            //     'quizzes.point', 
            //     DB::raw('COUNT(questions.id) as total_questions'),  // Count the total number of questions
            //     DB::raw('SUM(CAST(questions.default_marks AS DECIMAL)) as total_marks'),  // Sum the total marks
            //     DB::raw('SUM(COALESCE(questions.watch_time, 0)) as total_time')  // Sum the total time for the quiz
            // )
            // ->leftJoin('quiz_types', 'quizzes.quiz_type_id', '=', 'quiz_types.id')
            // ->leftJoin('sub_categories', 'quizzes.subcategory_id', '=', 'sub_categories.id')
            // ->leftJoin('quiz_questions', 'quizzes.id', '=', 'quiz_questions.quizzes_id')  // Join with quiz_questions
            // ->leftJoin('questions', 'quiz_questions.question_id', '=', 'questions.id')  // Join with questions
            // ->where('quizzes.subcategory_id', $request->category)  // Filter by category
            // ->where('quizzes.status', 1)  // Only active quizzes
            // ->groupBy(
            //     'quiz_types.slug',
            //     'quizzes.id',
            //     'quizzes.title',
            //     'quizzes.description',
            //     'quizzes.pass_percentage',
            //     'sub_categories.name',
            //     'quiz_types.name',
            //     'quizzes.duration_mode', 
            //     'quizzes.duration', 
            //     'quizzes.point_mode',
            //     'quizzes.point'
            // )
            // ->havingRaw('COUNT(questions.id) > 0')  
            // ->get();

            ////////// ------ RESUMED EXAM ------ //////////
            $current_time = now();
            $examResult = ExamResult::where('end_time', '>', $current_time)->where('status', 'ongoing')->get()->pluck('exam_id')->toArray();
            $resumedExam = Exam::select(
                    'exam_types.slug as exam_type_slug', 
                    'exams.slug', 
                    'exams.title', 
                    'exams.duration_mode', 
                    'exams.exam_duration', 
                    'exams.point_mode', 
                    'exams.point', 
                    DB::raw('COUNT(questions.id) as total_questions'), // Count total questions for each exam
                    DB::raw('SUM(CAST(questions.default_marks AS DECIMAL)) as total_marks'), // Sum total marks for each exam
                    DB::raw('SUM(COALESCE(questions.watch_time, 0)) as total_time') // Sum time for each question using watch_time
                )
                ->leftJoin('exam_types', 'exams.exam_type_id', '=', 'exam_types.id') // Join with exam_types
                ->leftJoin('exam_questions', 'exams.id', '=', 'exam_questions.exam_id') // Join with exam_questions
                ->leftJoin('questions', 'exam_questions.question_id', '=', 'questions.id') // Join with questions
                ->whereIn('exams.id', $examResult) // Filter exams by matching IDs from examResult
                ->where('exams.subcategory_id', $request->category) // Filter by subcategory ID
                ->where('exams.status', 1) // Filter by exam status
                ->groupBy(
                    'exam_types.slug', 
                    'exams.slug', 
                    'exams.id', 
                    'exams.title', 
                    'exams.duration_mode', 
                    'exams.exam_duration', 
                    'exams.point_mode', 
                    'exams.point'
                ) // Group by necessary fields
                ->havingRaw('COUNT(questions.id) > 0') // Only include exams with more than 0 questions
                ->get();

            ////////// ------ UPCOMING EXAM ------ //////////

            $assignedExams = AssignedExam::select('exam_id')
            ->where('user_id', $user->id)
            ->pluck('exam_id')
            ->toArray();

            $currentDate = now()->toDateString();
            $currentTime = now()->toTimeString();

            $upcomingExams = Exam::join('exam_schedules', 'exams.id', '=', 'exam_schedules.exam_id') // Ensure only exams with schedules are included
                ->leftJoin('exam_types', 'exams.exam_type_id', '=', 'exam_types.id')
                ->leftJoin('exam_questions', 'exams.id', '=', 'exam_questions.exam_id')
                ->leftJoin('questions', 'exam_questions.question_id', '=', 'questions.id')
                ->where('exams.status', 1)
                ->where(function ($query) use ($assignedExams) {
                    $query->where('exams.is_public', 1)
                        ->orWhereIn('exams.id', $assignedExams);
                })
                ->where(function ($query) use ($currentDate, $currentTime) {
                    $query->where(function ($scheduleQuery) use ($currentDate, $currentTime) {
                        $scheduleQuery->where('exam_schedules.schedule_type', 'fixed')
                            ->whereDate('exam_schedules.start_date', '>', $currentDate);
                            // Uncomment if time constraint is needed
                            // ->whereTime('exam_schedules.start_time', '>', $currentTime);
                    })
                    ->orWhere(function ($scheduleQuery) use ($currentDate, $currentTime) {
                        $scheduleQuery->where('exam_schedules.schedule_type', 'flexible')
                            ->whereDate('exam_schedules.start_date', '>', $currentDate)
                            ->whereDate('exam_schedules.end_date', '>', $currentDate);
                            // Uncomment if time constraint is needed
                            // ->whereTime('exam_schedules.start_time', '>', $currentTime)
                            // ->whereTime('exam_schedules.end_time', '<', $currentTime);
                    })
                    ->orWhere(function ($scheduleQuery) use ($currentDate, $currentTime) {
                        $scheduleQuery->where('exam_schedules.schedule_type', 'attempts')
                            ->whereDate('exam_schedules.start_date', '>', $currentDate);
                            // Uncomment if time constraint or grace period condition is needed
                            // ->whereTime('exam_schedules.start_time', '>', $currentTime)
                            // ->whereDate('exam_schedules.end_date', '>', $currentDate)
                            // ->whereTime('exam_schedules.end_time', '>', $currentTime)
                            // ->orWhereNotNull('exam_schedules.grace_period');
                    });
                })
                ->select(
                    'exams.id', 
                    'exams.slug as exam_slug', 
                    'exams.title as exam_name', 
                    'exam_types.slug as exam_type_slug',
                    'exams.duration_mode',
                    'exams.exam_duration',
                    'exams.point_mode',
                    'exams.point', 
                    'exam_schedules.schedule_type',
                    'exam_schedules.start_date',
                    'exam_schedules.start_time',
                    'exam_schedules.end_date',
                    'exam_schedules.end_time',
                    'exam_schedules.grace_period',
                    DB::raw('COUNT(questions.id) as total_questions'), 
                    DB::raw('SUM(CAST(questions.default_marks AS DECIMAL)) as total_marks'),
                    DB::raw('SUM(COALESCE(questions.watch_time, 0)) as total_time')
                )
                ->groupBy(
                    'exams.id',
                    'exam_types.slug', 
                    'exams.slug', 
                    'exams.title', 
                    'exams.duration_mode', 
                    'exams.exam_duration', 
                    'exams.point_mode', 
                    'exams.point',
                    'exam_schedules.schedule_type',
                    'exam_schedules.start_date',
                    'exam_schedules.start_time',
                    'exam_schedules.end_date',
                    'exam_schedules.end_time',
                    'exam_schedules.grace_period'
                )
                ->havingRaw('COUNT(questions.id) > 0')
                ->get();

            // Return success JSON response
            return response()->json([
                'status' => true,
                'completed_exam' => $passedExamCount+$failedExamCount ?? 0,
                'pass_exam' => $passedExamCount ?? 0,
                'failed_exam' => $failedExamCount ?? 0,
                'average_exam' => $averageScore ?? 0,
                // 'exams' => $examData,
                // 'quizzes' => $quizData,
                'resumedExam' => $resumedExam,
                'upcomingExams'=>$upcomingExams
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
