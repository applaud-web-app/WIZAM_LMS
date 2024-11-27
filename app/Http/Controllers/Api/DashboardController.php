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
use App\Models\Subscription;
use App\Models\SubscriptionItem;
use App\Models\QuizSchedule;
use App\Models\GroupUsers;

class DashboardController extends Controller
{

    private function getUserItemsByType($userId, $itemType)
    {
        try {
            // Check if the user has an active subscription
            $subscriptionIds = Subscription::where('user_id', $userId)
            ->where('status', 'active')
            ->whereDate('end_date', '>=', now()) // Check subscription validity
            ->pluck('id') // Get subscription IDs
            ->toArray();

            if (!empty($subscriptionIds)) {
                // Fetch subscription items of the specified type
                $subscriptionItems = SubscriptionItem::whereIn('subscription_id', $subscriptionIds)
                ->where('item_type', $itemType)
                ->where('status', 'active')
                ->pluck('item_id')
                ->toArray();

                return $subscriptionItems ?? [];
            }

            return [];
        } catch (\Throwable $th) {
            \Log::error("Dashboard Error fetching user items for type {$itemType}: " . $th->getMessage());
            return [];
        }
    }

    // Wrapper methods for specific item types
    private function getUserExam($userId)
    {
        return $this->getUserItemsByType($userId, 'exam');
    }

    private function getUserQuiz($userId)
    {
        return $this->getUserItemsByType($userId, 'quizze');
    }


    public function studentDashboard(Request $request)
    {
        try {
            // Validate the request
            $request->validate(['category' => 'required']);
    
            // Get the authenticated user
            $user = $request->attributes->get('authenticatedUser');
            
            // User group IDs
            $userGroup = GroupUsers::where('user_id',$user->id)
            ->where('status',1)
            ->pluck('group_id')
            ->toArray();

            // Assigned and purchased exams
            $assignedExams = AssignedExam::where('user_id', $user->id)->pluck('exam_id')->toArray();
            $purchaseExam = $this->getUserExam($user->id);

            // Fetch exams
            $calldenderData = Exam::leftJoin('exam_schedules', function ($join) use($userGroup){
                $join->on('exams.id', '=', 'exam_schedules.exam_id')
                    ->where('exam_schedules.status', 1);
            })
            ->leftJoin('exam_types', 'exams.exam_type_id', '=', 'exam_types.id')
            ->leftJoin('exam_questions', 'exams.id', '=', 'exam_questions.exam_id')
            ->leftJoin('questions', 'exam_questions.question_id', '=', 'questions.id')
            ->where('exams.status', 1)
            ->where(function ($query) { // IS THE EXAM IS PUBLIC OR HAVE A SCHEDULE (for private schedule is maindatory)
                $query->where('exams.is_public', 1) 
                    ->orWhereNotNull('exam_schedules.id'); 
            })
            ->where(function ($query) use ($assignedExams,$purchaseExam,$userGroup) {
                $query->where('exams.is_public', 1)->orwhere('exams.id', $purchaseExam)
                    ->orWhereIn('exams.id', $assignedExams)->orwhereIn('exam_schedules.user_groups',$userGroup); 
            })
            ->where('exams.subcategory_id', $request->category)
            ->select(
                'exams.id',
                'exams.is_free',
                'exams.slug as exam_slug',
                'exams.title as exam_name',
                'exam_types.slug as exam_type_slug',
                'exams.duration_mode',
                'exams.exam_duration',
                'exams.point_mode',
                'exams.restrict_attempts',
                'exams.total_attempts',
                'exams.point',
                DB::raw('SUM(CASE 
                    WHEN questions.type = "EMQ" AND JSON_VALID(questions.question) THEN JSON_LENGTH(questions.question) - 1
                    ELSE 1 
                END) as total_questions'),
                DB::raw('SUM(CAST(questions.default_marks AS DECIMAL)) as total_marks'),
                DB::raw('SUM(COALESCE(questions.watch_time, 0)) as total_time'),
                'exam_schedules.schedule_type',
                'exam_schedules.start_date',
                'exam_schedules.start_time',
                'exam_schedules.end_date',
                'exam_schedules.end_time',
                'exam_schedules.grace_period',
                'exam_schedules.user_groups'
            )
            ->groupBy(
                'exams.id',
                'exams.is_free',
                'exam_types.slug',
                'exams.slug',
                'exams.title',
                'exams.duration_mode',
                'exams.exam_duration',
                'exams.point_mode',
                'exams.point',
                'exams.restrict_attempts',
                'exams.total_attempts',
                'exam_schedules.schedule_type',
                'exam_schedules.start_date',
                'exam_schedules.start_time',
                'exam_schedules.end_date',
                'exam_schedules.end_time',
                'exam_schedules.grace_period',
                'exam_schedules.user_groups'
            )
            ->havingRaw('COUNT(questions.id) > 0')
            ->get();

            $data = $calldenderData->map(function ($exam) use($purchaseExam,$assignedExams,$userGroup){
                $checkfree = $exam->is_free;
                if(in_array($exam->id,$purchaseExam) || in_array($exam->id,$assignedExams) || in_array($exam->user_groups,$userGroup)){
                    $checkfree = 1;
                }
                return [
                    'slug' => $exam->exam_slug,
                    'title' => $exam->exam_name,
                    'is_free' => $checkfree,
                    'schedule_type' => $exam->schedule_type ?? "NA",
                    'start_date' => $exam->start_date ?? "NA",
                    'start_time' => $exam->start_time ?? "NA",
                    'end_date' => $exam->end_date ?? "NA",
                    'end_time' => $exam->end_time ?? "NA",
                    'grace_period' => $exam->grace_period ?? "NA",
                ];
            });
            
            // Purchase Quiz
            $purchaseQuiz = $this->getUserQuiz($user->id);

            // Fetch quizzes 
            $quizData = Quizze::leftJoin('quiz_schedules', function ($join) use($userGroup){
                    $join->on('quizzes.id', '=', 'quiz_schedules.quizzes_id')
                    ->where('quiz_schedules.status', 1);
                })
                ->leftJoin('quiz_types', 'quizzes.quiz_type_id', '=', 'quiz_types.id')
                ->leftJoin('quiz_questions', 'quizzes.id', '=', 'quiz_questions.quizzes_id')
                ->leftJoin('questions', 'quiz_questions.question_id', '=', 'questions.id')
                ->where('quizzes.status', 1)
                ->where(function ($query) {  // IS THE QUIZ IS PUBLIC OR HAVE A SCHEDULE (for private schedule is maindatory)
                    $query->where('quizzes.is_public', 1)
                    ->orWhereNotNull('quiz_schedules.id'); 
                })
                ->where(function ($query) use ($purchaseQuiz,$userGroup) {
                    $query->where('quizzes.is_public', 1)
                    ->orWhereIn('quizzes.id', $purchaseQuiz)
                    ->orWhereIn('quiz_schedules.user_groups', $userGroup);
                })
                ->where('quizzes.subcategory_id', $request->category)
                ->select(
                    'quizzes.id',
                    'quizzes.is_free',
                    'quizzes.slug as quiz_slug',
                    'quizzes.title as quiz_name',
                    'quiz_types.slug as quiz_type_slug',
                    'quizzes.duration_mode',
                    'quizzes.duration',
                    'quizzes.point_mode',
                    'quizzes.point',
                    DB::raw('SUM(CASE 
                        WHEN questions.type = "EMQ" AND JSON_VALID(questions.question) THEN JSON_LENGTH(questions.question) - 1
                        ELSE 1 
                    END) as total_questions'),
                    DB::raw('SUM(CAST(questions.default_marks AS DECIMAL)) as total_marks'),
                    DB::raw('SUM(COALESCE(questions.watch_time, 0)) as total_time'),
                    'quiz_schedules.schedule_type',
                    'quiz_schedules.start_date',
                    'quiz_schedules.start_time',
                    'quiz_schedules.end_date',
                    'quiz_schedules.end_time',
                    'quiz_schedules.grace_period'
                )
                ->groupBy(
                    'quizzes.id',
                    'quizzes.is_free',
                    'quiz_types.slug',
                    'quizzes.slug',
                    'quizzes.title',
                    'quizzes.duration_mode',
                    'quizzes.duration',
                    'quizzes.point_mode',
                    'quizzes.point',
                    'quiz_schedules.schedule_type',
                    'quiz_schedules.start_date',
                    'quiz_schedules.start_time',
                    'quiz_schedules.end_date',
                    'quiz_schedules.end_time',
                    'quiz_schedules.grace_period'
                )
                ->havingRaw('COUNT(questions.id) > 0')
                ->get();
            
            // Format the data for response
            $data2 = $quizData->map(function ($quiz) use($purchaseQuiz,$userGroup){
                $checkfree = $quiz->is_free;
                if(in_array($quiz->id,$purchaseQuiz) || in_array($quiz->user_groups,$userGroup)){
                    $checkfree = 1;
                }
                return [
                    'slug' => $quiz->quiz_slug,
                    'title' => $quiz->quiz_name,
                    'is_free' => $checkfree,
                    'schedule_type' => $quiz->schedule_type ?? "NA",
                    'start_date' => $quiz->start_date ?? "NA",
                    'start_time' => $quiz->start_time ?? "NA",
                    'end_date' => $quiz->end_date ?? "NA",
                    'end_time' => $quiz->end_time ?? "NA",
                    'grace_period' => $quiz->grace_period ?? "NA",
                ];
            });

            // USER DASHBOARD ITEMS
            $examStats = ExamResult::selectRaw(
                'COUNT(CASE WHEN student_percentage >= pass_percentage THEN 1 END) as passed_count, ' .
                'COUNT(CASE WHEN student_percentage < pass_percentage THEN 1 END) as failed_count, ' .
                'AVG(student_percentage) as average_score'
            )->where('user_id', $user->id)->where('status', 'complete')->first();

            $passedExamCount = $examStats->passed_count;
            $failedExamCount = $examStats->failed_count;
            $averageScore = $examStats->average_score;
            
            // Fetch ongoing exam results (Resume Exam)
            $current_time = now();
            $examResult = ExamResult::select('schedule_id', 'exam_id')
                ->where('end_time', '>', $current_time)
                ->where('user_id', $user->id)
                ->where('status', 'ongoing')
                ->get();

            $examResultScheduleIds = $examResult->pluck('schedule_id')->toArray();
            $examResultExamIds = $examResult->pluck('exam_id')->toArray();

            $resumedExam = Exam::leftJoin('exam_schedules', function ($join) {
                    $join->on('exams.id', '=', 'exam_schedules.exam_id')
                    ->where('exam_schedules.status', 1);
                })
                ->select(
                    'exam_types.slug as exam_type_slug',
                    'exams.slug',
                    'exams.title',
                    'exams.duration_mode',
                    'exams.exam_duration',
                    'exams.point_mode',
                    'exams.point',
                    DB::raw('COALESCE(exam_schedules.id, 0) as schedule_id'), // Default schedule_id to 0 if null
                    DB::raw('SUM(CASE
                        WHEN questions.type = "EMQ" AND JSON_VALID(questions.question) THEN JSON_LENGTH(questions.question) - 1
                        ELSE 1
                    END) as total_questions'),
                    DB::raw('SUM(CAST(questions.default_marks AS DECIMAL)) as total_marks'),
                    DB::raw('SUM(COALESCE(questions.watch_time, 0)) as total_time')
                )
                ->leftJoin('exam_types', 'exams.exam_type_id', '=', 'exam_types.id')
                ->leftJoin('exam_questions', 'exams.id', '=', 'exam_questions.exam_id')
                ->leftJoin('questions', 'exam_questions.question_id', '=', 'questions.id')
                ->where('exams.subcategory_id', $request->category)
                ->where('exams.status', 1)
                ->where(function ($query) use ($examResultScheduleIds, $examResultExamIds) {
                    $query->where(function ($subquery) use ($examResultExamIds) {
                        // Public exams without a schedule
                        $subquery->where('exams.is_public', 1)
                            ->whereIn('exams.id', $examResultExamIds); // Match using exam_id
                    })
                    ->orWhere(function ($subquery) use ($examResultScheduleIds, $examResultExamIds) {
                        // Private exams with valid schedules
                        $subquery->whereNotNull('exam_schedules.id')
                            ->whereIn('exam_schedules.id', $examResultScheduleIds)
                            ->whereIn('exams.id', $examResultExamIds);
                    });
                })
                ->groupBy(
                    DB::raw('COALESCE(exam_schedules.id, 0)'), // Group by schedule_id (0 for public exams without a schedule)
                    'exam_types.slug',
                    'exams.slug',
                    'exams.id',
                    'exams.title',
                    'exams.duration_mode',
                    'exams.exam_duration',
                    'exams.point_mode',
                    'exams.point'
                )
                ->havingRaw('COUNT(questions.id) > 0')
                ->get();

            // UPCOMING EXAM 
            $currentDate = now()->toDateString();
            $currentTime = now()->toTimeString();

            // $upcomingExams = Exam::leftJoin('exam_schedules', function ($join) {
            //         $join->on('exams.id', '=', 'exam_schedules.exam_id')
            //             ->where('exam_schedules.status', 1);
            //     })
            //     ->leftJoin('exam_types', 'exams.exam_type_id', '=', 'exam_types.id')
            //     ->leftJoin('exam_questions', 'exams.id', '=', 'exam_questions.exam_id')
            //     ->leftJoin('questions', 'exam_questions.question_id', '=', 'questions.id')
            //     ->where('exams.status', 1)
            //     ->where(function ($query) use ($assignedExams) {
            //         $query->where('exams.is_public', 0)
            //             ->orWhereIn('exams.id', $assignedExams);
            //     })
            //     ->where(function ($query) {
            //         $query->where('exams.is_public', 0) // Public exams
            //             ->orWhereNotNull('exam_schedules.id'); // Private exams must have a schedule
            //     })
            //     ->where('exams.subcategory_id', $request->category)
            //     ->where(function ($query) use ($currentDate, $currentTime) {
            //         // For 'fixed' schedules: ensure that the start date and start time are in the future
            //         $query->where(function ($scheduleQuery) use ($currentDate, $currentTime) {
            //             $scheduleQuery->where('exam_schedules.schedule_type', 'fixed')
            //                 ->whereDate('exam_schedules.start_date', '>', $currentDate)
            //                 ->orWhere(function ($query) use ($currentDate, $currentTime) {
            //                     $query->where('exam_schedules.start_date', '=', $currentDate)
            //                         ->whereTime('exam_schedules.start_time', '>', $currentTime);
            //                 });
            //         })
            //         // For 'flexible' schedules: check both start and end date/time are in the future
            //         ->orWhere(function ($scheduleQuery) use ($currentDate, $currentTime) {
            //             $scheduleQuery->where('exam_schedules.schedule_type', 'flexible')
            //                 ->whereDate('exam_schedules.start_date', '>', $currentDate)
            //                 ->whereDate('exam_schedules.end_date', '>', $currentDate)
            //                 ->orWhere(function ($query) use ($currentDate, $currentTime) {
            //                     $query->where('exam_schedules.start_date', '=', $currentDate)
            //                         ->whereTime('exam_schedules.start_time', '>', $currentTime)
            //                         ->whereTime('exam_schedules.end_time', '>', $currentTime);
            //                 });
            //         })
            //         // For 'attempts' schedules: ensure the start date and start time are in the future
            //         ->orWhere(function ($scheduleQuery) use ($currentDate, $currentTime) {
            //             $scheduleQuery->where('exam_schedules.schedule_type', 'attempts')
            //                 ->whereDate('exam_schedules.start_date', '>', $currentDate)
            //                 ->orWhere(function ($query) use ($currentDate, $currentTime) {
            //                     $query->where('exam_schedules.start_date', '=', $currentDate)
            //                         ->whereTime('exam_schedules.start_time', '>', $currentTime);
            //                 });
            //         });
            //     })
            //     ->select(
            //         'exams.id',
            //         'exams.is_free',
            //         'exams.slug as exam_slug',
            //         'exams.title as exam_name',
            //         'exam_types.slug as exam_type_slug',
            //         'exams.duration_mode',
            //         'exams.exam_duration',
            //         'exams.point_mode',
            //         'exams.point',
            //         'exam_schedules.id as schedule_id',
            //         'exam_schedules.schedule_type',
            //         'exam_schedules.start_date',
            //         'exam_schedules.start_time',
            //         'exam_schedules.end_date',
            //         'exam_schedules.end_time',
            //         'exams.restrict_attempts',
            //         'exams.total_attempts',
            //         'exam_schedules.grace_period',
            //         DB::raw('SUM(CASE 
            //             WHEN questions.type = "EMQ" AND JSON_VALID(questions.question) THEN JSON_LENGTH(questions.question) - 1
            //             ELSE 1 
            //         END) as total_questions'),
            //         DB::raw('SUM(CAST(questions.default_marks AS DECIMAL)) as total_marks'),
            //         DB::raw('SUM(COALESCE(questions.watch_time, 0)) as total_time')
            //     )
            //     ->groupBy(
            //         'exams.id',
            //         'exams.is_free',
            //         'exam_types.slug', 
            //         'exams.slug', 
            //         'exams.title', 
            //         'exams.duration_mode', 
            //         'exams.exam_duration', 
            //         'exams.point_mode', 
            //         'exams.point',
            //         'exam_schedules.id',
            //         'exams.restrict_attempts',
            //         'exams.total_attempts',
            //         'exam_schedules.schedule_type',
            //         'exam_schedules.start_date',
            //         'exam_schedules.start_time',
            //         'exam_schedules.end_date',
            //         'exam_schedules.end_time',
            //         'exam_schedules.grace_period'
            //     )
            //     ->havingRaw('COUNT(questions.id) > 0')
            // ->get();

            // Filter upcoming exams from $calldenderData
            $upcomingExams = $calldenderData->filter(function ($exam) use ($currentDate, $currentTime) {
                if ($exam->schedule_type === 'fixed') {
                    // Fixed: Start date and time in the future
                    return $exam->start_date > $currentDate || 
                        ($exam->start_date == $currentDate && $exam->start_time > $currentTime);
                } elseif ($exam->schedule_type === 'flexible') {
                    // Flexible: Both start and end date/time in the future
                    return $exam->start_date > $currentDate ||
                        ($exam->start_date == $currentDate && $exam->start_time > $currentTime) ||
                        $exam->end_date > $currentDate ||
                        ($exam->end_date == $currentDate && $exam->end_time > $currentTime);
                } elseif ($exam->schedule_type === 'attempts') {
                    // Attempts: Start date and time in the future
                    return $exam->start_date > $currentDate || 
                        ($exam->start_date == $currentDate && $exam->start_time > $currentTime);
                }
                return false; // Default case: Not an upcoming exam
            })->map(function ($exam) use($purchaseExam,$assignedExams,$userGroup){

                // Free / Paid
                $checkfree = $exam->is_free;
                if(in_array($exam->id,$purchaseExam) || in_array($exam->id,$assignedExams) || in_array($exam->user_groups,$userGroup)){
                    $checkfree = 1;
                }

                // Duration / Point
                $formattedTime = $this->formatTime($exam->total_time);
                $time = $exam->duration_mode == "manual" ? $this->formatTime($exam->exam_duration) : $formattedTime;
                $marks = $exam->point_mode == "manual" ? ($exam->point * $exam->total_questions) : $exam->total_marks;

                // Attempts
                $totalAttempt = $exam->total_attempts ?? 1;
                $totalAttempt = $exam->restrict_attempts == 1 ? $totalAttempt : null;
                return [
                    'id' => $exam->id,
                    'exam_slug' => $exam->exam_slug,
                    'exam_name' => $exam->exam_name,
                    'duration_mode' => $exam->duration_mode,
                    'total_questions' => $exam->total_questions,
                    'exam_duration' => $exam->exam_duration ?? null,
                    'point_mode' => $exam->point_mode,
                    'total_marks' => $marks,
                    'total_time' => $time,
                    'restrict_attempts' => $exam->restrict_attempts,
                    'total_attempts' => $totalAttempt ?? null,
                    'point' => $exam->point ?? null,
                    'schedule_id' => $exam->schedule_id ?? null,
                    'is_free' => $checkfree,
                    'schedule_type' => $exam->schedule_type ?? "NA",
                    'start_date' => $exam->start_date ?? "NA",
                    'start_time' => $exam->start_time ?? "NA",
                    'end_date' => $exam->end_date ?? "NA",
                    'end_time' => $exam->end_time ?? "NA",
                    'grace_period' => $exam->grace_period ?? "NA",
                ];
            });

            // Convert the filtered upcoming exams into an array if needed
            $upcomingExamsArray = $upcomingExams->values()->toArray();

            // Return success JSON response
            return response()->json([
                'status' => true,
                'completed_exam' => $passedExamCount+$failedExamCount ?? 0,
                'pass_exam' => $passedExamCount ?? 0,
                'failed_exam' => $failedExamCount ?? 0,
                'average_exam' => $averageScore ?? 0,
                'resumedExam' => $resumedExam,
                'upcomingExams'=>$upcomingExams,
                'calenderExam'=>$data,
                'calenderQuiz'=>$data2,
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

    private function formatTime($totalTime)
    {
        // Convert total seconds into hours, minutes, and seconds
        $hours = floor($totalTime / 3600);
        $minutes = floor(($totalTime % 3600) / 60);
        $seconds = $totalTime % 60;

        // Format the time string accordingly
        $timeString = '';
        if ($hours > 0) {
            $timeString .= $hours . ' hr ';
        }
        if ($minutes > 0) {
            $timeString .= $minutes . ' min ';
        }
        if ($seconds > 0) {
            $timeString .= $seconds . ' sec';
        }

        return trim($timeString); // Trim any extra spaces
    }
    
}
