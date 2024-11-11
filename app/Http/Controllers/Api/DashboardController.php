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
use App\Models\QuizSchedule;

class DashboardController extends Controller
{


    public function studentDashboard(Request $request)
    {
        try {
            // Validate the request
            $request->validate(['category' => 'required']);
    
            // Get the authenticated user
            $user = $request->attributes->get('authenticatedUser');

            // START 
            // Fetch the exam IDs assigned to the current user
            $assignedExams = AssignedExam::where('user_id', $user->id)
            ->pluck('exam_id')
            ->toArray();

            // Get current date and time for upcoming exam logic
            $currentDate = now();

            // Fetch upcoming exams with schedules
            $calldenderData = Exam::join('exam_schedules', 'exams.id', '=', 'exam_schedules.exam_id')
            ->leftJoin('exam_types', 'exams.exam_type_id', '=', 'exam_types.id')
            ->leftJoin('exam_questions', 'exams.id', '=', 'exam_questions.exam_id')
            ->leftJoin('questions', 'exam_questions.question_id', '=', 'questions.id')
            ->where('exams.status', 1)
            ->where('exam_schedules.status', 1) 
            ->where(function ($query) use ($assignedExams) {
                $query->where('exams.is_public', 1)
                    ->orWhereIn('exams.id', $assignedExams);
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
                'exam_schedules.grace_period'
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
                'exam_schedules.schedule_type',
                'exam_schedules.start_date',
                'exam_schedules.start_time',
                'exam_schedules.end_date',
                'exam_schedules.end_time',
                'exam_schedules.grace_period'
            )
            ->havingRaw('COUNT(questions.id) > 0')
            ->havingRaw('COUNT(exam_schedules.id) > 0')
            ->get();

            // Fetch the user's active subscription
            $currentDate = now();
            $type = "exams"; 
            $subscription = Subscription::with('plans')->where('user_id', $user->id)->where('stripe_status', 'complete')->where('ends_at', '>', $currentDate)->latest()->first();

            // Fetch the user's active subscription
            $subscription = Subscription::with('plans')
            ->where('user_id', $user->id)
            ->where('stripe_status', 'complete')
            ->where('ends_at', '>', $currentDate)
            ->latest()
            ->first();

            // Apply subscription-based conditions to make exams free
            if ($subscription) {
            $plan = $subscription->plans;

            // Check if the plan allows unlimited access
            if ($plan->feature_access == 1) {
                // MAKE ALL EXAMS FREE
                $calldenderData->transform(function ($exam) {
                    $exam->is_free = 1; // Make all exams free for unlimited access
                    return $exam;
                });
            } else {
                // Get allowed features from the plan
                $allowed_features = json_decode($plan->features, true);
                // Check if exams are included in the allowed features
                if (in_array($type, $allowed_features)) {
                    // MAKE ALL EXAMS FREE
                    $calldenderData->transform(function ($exam) {
                        $exam->is_free = 1; // Make exams free as part of allowed features
                        return $exam;
                    });
                }
                }
            }

            $data = $calldenderData->map(function ($exam) {
                return [
                    'slug' => $exam->exam_slug,
                    'title' => $exam->exam_name,
                    'schedule_type' => $exam->schedule_type ?? "NA",
                    'start_date' => $exam->start_date ?? "NA",
                    'start_time' => $exam->start_time ?? "NA",
                    'end_date' => $exam->end_date ?? "NA",
                    'end_time' => $exam->end_time ?? "NA",
                    'grace_period' => $exam->grace_period ?? "NA",
                ];
            });
            // END

            // QUIZ CALENDER START
            $currentDate = now();
            $type = "quizzes"; 
            
            // Fetch upcoming quizzes with schedules
            $quizData = Quizze::join('quiz_schedules', 'quizzes.id', '=', 'quiz_schedules.quizzes_id')
                ->leftJoin('quiz_types', 'quizzes.quiz_type_id', '=', 'quiz_types.id')
                ->where('quizzes.status', 1)
                ->where('quizzes.subcategory_id', $request->category)
                ->where('quiz_schedules.status', 1) 
                ->where(function ($query) use ($subscription) {
                    if (!$subscription) {
                        // Show only public quizzes if there is no subscription
                        $query->where('quizzes.is_public', 1);
                    }
                })
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
                ->get();
            
            // Apply subscription-based conditions to make quizzes free
            if ($subscription) {
                $plan = $subscription->plans;
            
                // Check if the plan allows unlimited access
                if ($plan->feature_access == 1) {
                    // Make all quizzes free
                    $quizData->transform(function ($quiz) {
                        $quiz->is_free = 1;
                        return $quiz;
                    });
                } else {
                    // Check if quizzes are part of the allowed features
                    $allowed_features = json_decode($plan->features, true);
                    if (in_array($type, $allowed_features)) {
                        // Make all quizzes free if allowed in the features
                        $quizData->transform(function ($quiz) {
                            $quiz->is_free = 1;
                            return $quiz;
                        });
                    }
                }
            }
            
            // Format the data for response
            $data2 = $quizData->map(function ($quiz) {
                return [
                    'slug' => $quiz->quiz_slug,
                    'title' => $quiz->quiz_name,
                    'schedule_type' => $quiz->schedule_type ?? "NA",
                    'start_date' => $quiz->start_date ?? "NA",
                    'start_time' => $quiz->start_time ?? "NA",
                    'end_date' => $quiz->end_date ?? "NA",
                    'end_time' => $quiz->end_time ?? "NA",
                    'grace_period' => $quiz->grace_period ?? "NA",
                ];
            });

            // QUIZ CALENDER END

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
            $examResult = ExamResult::select('schedule_id','exam_id')
                ->where('end_time', '>', $current_time)
                ->where('user_id', $user->id)
                ->where('status', 'ongoing')
                ->get();
            $examResultScheduleIds = $examResult->pluck('schedule_id')->toArray();
            $examResultExamIds = $examResult->pluck('exam_id')->toArray();
            $resumedExam = Exam::join('exam_schedules', 'exams.id', '=', 'exam_schedules.exam_id')
                ->select(
                    'exam_types.slug as exam_type_slug',
                    'exams.slug',
                    'exams.title',
                    'exams.duration_mode',
                    'exams.exam_duration',
                    'exams.point_mode',
                    'exams.point',
                    'exam_schedules.id as schedule_id',
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
                ->where('exam_schedules.status', 1)
                ->whereIn('exam_schedules.id', $examResultScheduleIds)
                ->whereIn('exams.id', $examResultExamIds)
                ->groupBy(
                    'exam_schedules.id',
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

            ////////// ------ UPCOMING EXAM ------ //////////

            $assignedExams = AssignedExam::select('exam_id')
                ->where('user_id', $user->id)
                ->pluck('exam_id')
                ->toArray();

            $currentDate = now()->toDateString();
            $currentTime = now()->toTimeString();

            $upcomingExams = Exam::join('exam_schedules', 'exams.id', '=', 'exam_schedules.exam_id')
                ->leftJoin('exam_types', 'exams.exam_type_id', '=', 'exam_types.id')
                ->leftJoin('exam_questions', 'exams.id', '=', 'exam_questions.exam_id')
                ->leftJoin('questions', 'exam_questions.question_id', '=', 'questions.id')
                ->where('exams.status', 1)
                ->where(function ($query) use ($assignedExams) {
                    $query->where('exams.is_public', 1)
                        ->orWhereIn('exams.id', $assignedExams);
                })
                ->where('exam_schedules.status', 1)
                ->where('exams.subcategory_id', $request->category)
                ->where(function ($query) use ($currentDate, $currentTime) {
                    // For 'fixed' schedules: ensure that the start date and start time are in the future
                    $query->where(function ($scheduleQuery) use ($currentDate, $currentTime) {
                        $scheduleQuery->where('exam_schedules.schedule_type', 'fixed')
                            ->whereDate('exam_schedules.start_date', '>', $currentDate)
                            ->orWhere(function ($query) use ($currentDate, $currentTime) {
                                $query->where('exam_schedules.start_date', '=', $currentDate)
                                    ->whereTime('exam_schedules.start_time', '>', $currentTime);
                            });
                    })
                    // For 'flexible' schedules: check both start and end date/time are in the future
                    ->orWhere(function ($scheduleQuery) use ($currentDate, $currentTime) {
                        $scheduleQuery->where('exam_schedules.schedule_type', 'flexible')
                            ->whereDate('exam_schedules.start_date', '>', $currentDate)
                            ->whereDate('exam_schedules.end_date', '>', $currentDate)
                            ->orWhere(function ($query) use ($currentDate, $currentTime) {
                                $query->where('exam_schedules.start_date', '=', $currentDate)
                                    ->whereTime('exam_schedules.start_time', '>', $currentTime)
                                    ->whereTime('exam_schedules.end_time', '>', $currentTime);
                            });
                    })
                    // For 'attempts' schedules: ensure the start date and start time are in the future
                    ->orWhere(function ($scheduleQuery) use ($currentDate, $currentTime) {
                        $scheduleQuery->where('exam_schedules.schedule_type', 'attempts')
                            ->whereDate('exam_schedules.start_date', '>', $currentDate)
                            ->orWhere(function ($query) use ($currentDate, $currentTime) {
                                $query->where('exam_schedules.start_date', '=', $currentDate)
                                    ->whereTime('exam_schedules.start_time', '>', $currentTime);
                            });
                    });
                })
                ->select(
                    'exams.id',
                    'exams.is_free',
                    'exams.slug as exam_slug',
                    'exams.title as exam_name',
                    'exam_types.slug as exam_type_slug',
                    'exams.duration_mode',
                    'exams.exam_duration',
                    'exams.point_mode',
                    'exams.point',
                    'exam_schedules.id as schedule_id',
                    'exam_schedules.schedule_type',
                    'exam_schedules.start_date',
                    'exam_schedules.start_time',
                    'exam_schedules.end_date',
                    'exam_schedules.end_time',
                    'exam_schedules.grace_period',
                    DB::raw('SUM(CASE 
                        WHEN questions.type = "EMQ" AND JSON_VALID(questions.question) THEN JSON_LENGTH(questions.question) - 1
                        ELSE 1 
                    END) as total_questions'),
                    DB::raw('SUM(CAST(questions.default_marks AS DECIMAL)) as total_marks'),
                    DB::raw('SUM(COALESCE(questions.watch_time, 0)) as total_time')
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
                    'exam_schedules.id',
                    'exam_schedules.schedule_type',
                    'exam_schedules.start_date',
                    'exam_schedules.start_time',
                    'exam_schedules.end_date',
                    'exam_schedules.end_time',
                    'exam_schedules.grace_period'
                )
                ->havingRaw('COUNT(questions.id) > 0')
                ->get();

            // Fetch the user's active subscription
            $currentDate = now();
            $type = "exams"; 
            $subscription = Subscription::with('plans')->where('user_id', $user->id)->where('stripe_status', 'complete')->where('ends_at', '>', $currentDate)->latest()->first();

            // Fetch the user's active subscription
            $subscription = Subscription::with('plans')
                ->where('user_id', $user->id)
                ->where('stripe_status', 'complete')
                ->where('ends_at', '>', $currentDate)
                ->latest()
                ->first();

            // Apply subscription-based conditions to make exams free
            if ($subscription) {
                $plan = $subscription->plans;

                // Check if the plan allows unlimited access
                if ($plan->feature_access == 1) {
                    // MAKE ALL EXAMS FREE
                    $upcomingExams->transform(function ($exam) {
                        $exam->is_free = 1; // Make all exams free for unlimited access
                        return $exam;
                    });
                } else {
                    // Get allowed features from the plan
                    $allowed_features = json_decode($plan->features, true);
                    // Check if exams are included in the allowed features
                    if (in_array($type, $allowed_features)) {
                        // MAKE ALL EXAMS FREE
                        $upcomingExams->transform(function ($exam) {
                            $exam->is_free = 1; // Make exams free as part of allowed features
                            return $exam;
                        });
                    }
                }
            }

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
                'upcomingExams'=>$upcomingExams,
                'calenderExam'=>$data,
                'calenderQuiz'=>$data2
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
