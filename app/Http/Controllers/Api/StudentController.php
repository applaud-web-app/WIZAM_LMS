<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SubCategory;
use App\Models\ExamType;
use App\Models\User;
use App\Models\Exam;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\Quizze;
use App\Models\QuizType;
use App\Models\PracticeSet;
use App\Models\PracticeLesson;
use App\Models\PracticeVideo;
use App\Models\Video;
use App\Models\Lesson;
use App\Models\Subscription;
use App\Models\SubscriptionItem;
use App\Models\GroupUsers;
use App\Models\Plan;
use Carbon\Carbon;
use App\Models\Payment;
use App\Models\BillingSetting;
use App\Models\AssignedExam;
use App\Models\ExamResult;
use App\Models\PracticeSetResult;
use App\Models\QuizResult;

class StudentController extends Controller
{
    public function syllabus(){
        try {
            $data = SubCategory::select('id','name','description')->where('status', 1)->whereJsonLength('sections', '>', 0)->get();
            return response()->json(['status'=> true,'data' => $data], 201);
        } catch (\Throwable $th) {
            return response()->json(['status'=> false,'error' => $th->getMessage()], 500);
        }
    }

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

    private function getUserPractice($userId)
    {
        return $this->getUserItemsByType($userId, 'practice');
    }

    private function getUserVideo($userId)
    {
        return $this->getUserItemsByType($userId, 'video');
    }

    private function getUserLesson($userId)
    {
        return $this->getUserItemsByType($userId, 'lesson');
    }

    public function examType(Request $request) {
        try {
            // Fetch the current authenticated user
            $user = $request->attributes->get('authenticatedUser');
    
            // User group IDs
            $userGroup = GroupUsers::where('user_id',$user->id)
            ->where('status',1)
            ->pluck('group_id')
            ->toArray();
            
            // Assigned and purchased exams
            $assignedExams = AssignedExam::where('user_id', $user->id)->pluck('exam_id')->toArray();
            $purchaseExam = $this->getUserExam($user->id);

            $type = ExamType::select('name', 'slug')
                ->where('status', 1)
                ->withCount([
                    'exams as total_exams' => function ($query) use ($assignedExams, $purchaseExam, $userGroup) {
                        $query->leftJoin('exam_schedules', function ($join) {
                            $join->on('exams.id', '=', 'exam_schedules.exam_id')
                            ->where('exam_schedules.status', 1);
                        })
                        ->where('exams.status', 1)
                        ->where(function ($query) {
                            $query->where('exams.is_public', 1)
                            ->orWhereNotNull('exam_schedules.id'); 
                        })
                        ->where(function ($query) use ($assignedExams,$purchaseExam,$userGroup) {
                            $query->where('exams.is_public', 1)->orwhere('exams.id', $purchaseExam)
                                ->orWhereIn('exams.id', $assignedExams)->orwhereIn('exam_schedules.user_groups',$userGroup); 
                        })
                        ->distinct();
                    },
                    'exams as paid_exams' => function ($query) use ($assignedExams, $purchaseExam, $userGroup) {
                        $query->leftJoin('exam_schedules', function ($join) {
                                $join->on('exams.id', '=', 'exam_schedules.exam_id')
                                    ->where('exam_schedules.status', 1);
                            })
                            ->where('exams.status', 1)
                            ->where(function ($query) {
                                $query->where('exams.is_public', 1)
                                ->orWhereNotNull('exam_schedules.id'); 
                            })
                            ->where(function ($query) use ($assignedExams,$purchaseExam,$userGroup) {
                                $query->where('exams.is_public', 1)->orwhere('exams.id', $purchaseExam)
                                    ->orWhereIn('exams.id', $assignedExams)->orwhereIn('exam_schedules.user_groups',$userGroup); 
                            })
                            ->where('exams.is_free', 0)
                            ->distinct();
                    },
                    'exams as unpaid_exams' => function ($query) use ($assignedExams, $purchaseExam, $userGroup) {
                        $query->leftJoin('exam_schedules', function ($join) {
                                $join->on('exams.id', '=', 'exam_schedules.exam_id')
                                    ->where('exam_schedules.status', 1);
                            })
                            ->where('exams.status', 1)
                            ->where(function ($query) {
                                $query->where('exams.is_public', 1)
                                ->orWhereNotNull('exam_schedules.id'); 
                            })
                            ->where(function ($query) use ($assignedExams,$purchaseExam,$userGroup) {
                                $query->where('exams.is_public', 1)->orwhere('exams.id', $purchaseExam)
                                    ->orWhereIn('exams.id', $assignedExams)->orwhereIn('exam_schedules.user_groups',$userGroup); 
                            })
                            ->where('exams.is_free', 1)
                            ->distinct(); 
                    }
                ])
                ->get();
    
            return response()->json(['status' => true, 'data' => $type], 201);
    
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'error' => $th->getMessage()], 500);
        }
    }

    public function allExams(Request $request)
    {
        try {
            // Fetch exam type by slug and status
            $examType = ExamType::select('id', 'slug')
                ->where('slug', $request->slug)
                ->where('status', 1)
                ->first();
    
            if ($examType) {
                // Fetch the current authenticated user
                $user = $request->attributes->get('authenticatedUser');

                // User group IDs
                $userGroup = GroupUsers::where('user_id',$user->id)
                ->where('status',1)
                ->pluck('group_id')
                ->toArray();
    
                // Assigned and purchased exams
                $assignedExams = AssignedExam::where('user_id', $user->id)->pluck('exam_id')->toArray();
                $purchaseExam = $this->getUserExam($user->id);

                $examData = Exam::leftJoin('exam_schedules', function ($join) {
                    $join->on('exams.id', '=', 'exam_schedules.exam_id')
                        ->where('exam_schedules.status', 1);
                })
                ->leftJoin('exam_types', 'exams.exam_type_id', '=', 'exam_types.id')
                ->leftJoin('exam_questions', 'exams.id', '=', 'exam_questions.exam_id')
                ->leftJoin('questions', 'exam_questions.question_id', '=', 'questions.id')
                ->where('exams.status', 1)
                ->where(function ($query) {
                    $query->where('exams.is_public', 1) // Public exams
                        ->orWhereNotNull('exam_schedules.id'); // Private exams must have a schedule
                })
                ->where(function ($query) use ($assignedExams,$purchaseExam,$userGroup) {
                    $query->where('exams.is_public', 1)->orwhere('exams.id', $purchaseExam)
                    ->orWhereIn('exams.id', $assignedExams)->orwhereIn('exam_schedules.user_groups',$userGroup); 
                })
                ->where('exams.exam_type_id', $examType->id)
                ->where('exams.subcategory_id', $request->category)
                ->select(
                    'exams.id',
                    'exams.is_free',
                    'exams.slug as slug',
                    'exams.title as title',
                    'exam_types.slug as exam_type_slug',
                    'exams.duration_mode',
                    'exams.exam_duration',
                    'exams.point_mode',
                    'exams.point',
                    'exams.restrict_attempts',
                    'exams.total_attempts',
                    'exams.is_public',
                    'exam_schedules.user_groups',
                    DB::raw('SUM(CASE 
                        WHEN questions.type = "EMQ" AND JSON_VALID(questions.question) THEN JSON_LENGTH(questions.question) - 1
                        ELSE 1 
                    END) as total_questions'),
                    DB::raw('SUM(CAST(questions.default_marks AS DECIMAL)) as total_marks'),
                    DB::raw('SUM(COALESCE(questions.watch_time, 0)) as total_time'),
                    'exam_schedules.id as schedule_id',
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
                    'exams.total_attempts',
                    'exams.duration_mode',
                    'exams.exam_duration',
                    'exams.restrict_attempts',
                    'exams.point_mode',
                    'exams.is_public',
                    'exams.point',
                    'exam_schedules.id',
                    'exam_schedules.schedule_type',
                    'exam_schedules.user_groups',
                    'exam_schedules.start_date',
                    'exam_schedules.start_time',
                    'exam_schedules.end_date',
                    'exam_schedules.end_time',
                    'exam_schedules.grace_period'
                )
                ->havingRaw('COUNT(questions.id) > 0') // Only include exams with questions
                ->get();
    
                // Initialize array to store formatted exam data
                $formattedExamData = [];
    
                // Apply free logic for assigned exams
                $examData->transform(function ($exam) use ($assignedExams) {
                    if (in_array($exam->id, $assignedExams)) {
                        $exam->is_free = 1; // Make assigned exams free
                    }
                    return $exam;
                });

                // Resume Exam
                $current_time = now();
                $examResults = ExamResult::where('end_time', '>', $current_time)
                    ->where('user_id', $user->id)
                    ->where('status', 'ongoing')
                    ->get();

                $examResultExamScheduleMap = [];
                foreach ($examResults as $examResult) {
                    $key = $examResult->exam_id . '_' . $examResult->schedule_id;
                    $examResultExamScheduleMap[$key] = true;
                }
    
                foreach ($examData as $exam) {

                    // Free / Paid
                    $checkfree = $exam->is_free;
                    if(in_array($exam->id,$purchaseExam) || in_array($exam->id,$assignedExams) || in_array($exam->user_groups,$userGroup)){
                        $checkfree = 1;
                    }

                    // Duration / Point
                    $formattedTime = $this->formatTime($exam->total_time);
                    $time = $exam->duration_mode == "manual" ? $this->formatTime($exam->exam_duration*60) : $formattedTime;
                    $marks = $exam->point_mode == "manual" ? ($exam->point * $exam->total_questions) : $exam->total_marks;

                    

                    // Resume Exams
                    $examScheduleKey = $exam->id . '_' . ($exam->schedule_id ?: 0); // Use 0 if no schedule_id is provided
                    $isResume = isset($examResultExamScheduleMap[$examScheduleKey]);

                    if ($exam->is_public === 1 && !$exam->schedule_id) {
                        $isResume = isset($examResultExamScheduleMap[$exam->id . '_0']);
                    }

                    // Attempts
                    $totalAttempt = $exam->total_attempts ?? 1;
                    $totalAttempt = $exam->restrict_attempts == 1 ? $totalAttempt : null;

                    // Attempts Completed or not checking
                    $scheduleId = $exam->schedule_id ?? 0;
                    $userAttempt = ExamResult::where('user_id',$user->id)->where('exam_id',$exam->id)->where('schedule_id',$scheduleId)->count();

                    if($exam->restrict_attempts == 1 && $userAttempt >= $totalAttempt){
                        continue;
                    }
    
                    // Group exams by exam type slug
                    if (!isset($formattedExamData[$examType->slug])) {
                        $formattedExamData[$examType->slug] = [];
                    }

                    // Add exam details to the corresponding type slug, including schedule details
                    $formattedExamData[$examType->slug][] = [
                        'title' => $exam->title,
                        'slug' => $exam->slug,
                        'questions' => $exam->total_questions ?? 0,
                        'time' => $time ?? 0,
                        'marks' => $marks ?? 0,
                        'is_free' => $checkfree,
                        'is_resume' =>$isResume,
                        'is_public' =>$exam->is_public,
                        'total_attempts' => $totalAttempt,
                        'schedule' => [
                            'schedule_id'=>$exam->schedule_id,
                            'start_date' => $exam->start_date,
                            'start_time' => $exam->start_time,
                            'end_date' => $exam->end_date,
                            'end_time' => $exam->end_time,
                            'grace_period' => $exam->grace_period,
                            'schedule_type' => $exam->schedule_type,
                        ],
                    ];
                }
    
                // Return the formatted data as JSON
                return response()->json(['status' => true, 'data' => $formattedExamData], 200);
            }
    
            // Return error if exam type not found
            return response()->json(['status' => false, 'error' => "Exam Not Found"], 404);
        } catch (\Throwable $th) {
            // Return error response with exception message
            return response()->json(['status' => false, 'error' => $th->getMessage()], 500);
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

    public function examDetail(Request $request, $slug)
    {
        try {
            // Validate incoming request data
            $request->validate([
                'category' => 'required|integer',
            ]);

            // Fetch the current authenticated user
            $user = $request->attributes->get('authenticatedUser');

            // User group IDs
            $userGroup = GroupUsers::where('user_id',$user->id)
            ->where('status',1)
            ->pluck('group_id')
            ->toArray();

            // Assigned and purchased exams
            $assignedExams = AssignedExam::where('user_id', $user->id)->pluck('exam_id')->toArray();
            $purchaseExam = $this->getUserExam($user->id);

            // Fetch exam details based on the category and slug
            $examData = Exam::leftJoin('exam_schedules', function ($join){
                $join->on('exams.id', '=', 'exam_schedules.exam_id')
                    ->where('exam_schedules.status', 1);
            })->select(
                'exams.id', // Include exam ID for assigned exams check
                'exam_types.slug as exam_type_slug', 
                'exams.title',
                'exams.description',
                'exams.pass_percentage',
                'sub_categories.name as sub_category_name',
                'exam_types.name as exam_type_name',
                'exams.duration_mode', 
                'exams.exam_duration', 
                'exams.point_mode',
                'exams.point', 
                'exams.is_free',
                'exam_schedules.user_groups',
                DB::raw('SUM(CASE 
                    WHEN questions.type = "EMQ" AND JSON_VALID(questions.question) THEN JSON_LENGTH(questions.question) - 1
                    ELSE 1 
                END) as total_questions'),
                DB::raw('SUM(CAST(questions.default_marks AS DECIMAL)) as total_marks'), 
                DB::raw('SUM(COALESCE(questions.watch_time, 0)) as total_time')
            )
            ->leftJoin('exam_types', 'exams.exam_type_id', '=', 'exam_types.id') 
            ->leftJoin('sub_categories', 'exams.subcategory_id', '=', 'sub_categories.id') 
            ->leftJoin('exam_questions', 'exams.id', '=', 'exam_questions.exam_id') 
            ->leftJoin('questions', 'exam_questions.question_id', '=', 'questions.id') 
            ->where('exams.status', 1)
            ->where(function ($query) { // IS THE EXAM IS PUBLIC OR HAVE A SCHEDULE (for private schedule is maindatory)
                $query->where('exams.is_public', 1) 
                    ->orWhereNotNull('exam_schedules.id'); 
            })
            ->where(function ($query) use ($assignedExams) {
                $query->where('exams.is_public', 1) // Public exams
                    ->orWhereIn('exams.id', $assignedExams); // Private exams assigned to the user
            })
            ->where(function ($query) use ($assignedExams,$purchaseExam,$userGroup) {
                $query->where('exams.is_public', 1)->orwhere('exams.id', $purchaseExam)
                    ->orWhereIn('exams.id', $assignedExams)->orwhereIn('exam_schedules.user_groups',$userGroup); 
            })
            ->where('exams.subcategory_id', $request->category) 
            ->where('exams.slug', $slug) 
            ->groupBy('exam_types.slug', 'exams.id', 'exams.title', 'exams.description', 'exams.pass_percentage', 
                    'sub_categories.name', 'exam_types.name', 'exams.duration_mode', 'exams.exam_duration', 
                    'exams.point_mode', 'exams.point', 'exams.is_free','exam_schedules.user_groups')
            ->havingRaw('COUNT(questions.id) > 0')
            ->first();

            // Check if exam data is available
            if (!$examData) {
                return response()->json(['status' => false, 'message' => 'Exam not found'], 404);
            }

            // Adjust 'is_free' for assigned exams, regardless of public or private
            if (in_array($examData->id, $assignedExams) || in_array($examData->id, $purchaseExam) || in_array($examData->user_groups, $userGroup)) {
                $examData->is_free = 1; // Make assigned exams free
            }

            // Format time and marks
            $formattedTime = $this->formatTime($examData->total_time);
            $time = $examData->duration_mode == "manual" ? $this->formatTime($examData->exam_duration*60) : $formattedTime;
            $marks = $examData->point_mode == "manual" ? ($examData->point * $examData->total_questions) : $examData->total_marks;

            // Format response to match the structure needed by frontend
            return response()->json([
                'status' => true,
                'data' => [
                    'title' => $examData->title,
                    'examType' => $examData->exam_type_name,
                    'syllabus' => $examData->sub_category_name,
                    'totalQuestions' => $examData->total_questions,
                    'duration' => $time,
                    'marks' => $marks,
                    'description' => $examData->description,
                    'is_free'=> $examData->is_free,
                ]
            ], 200);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'error' => 'Internal Server Error: '.$th->getMessage()], 500);
        }
    }


    // QUIZ DATA
    public function quizType(Request $request) {
        try {
            // Fetch the current authenticated user
            $user = $request->attributes->get('authenticatedUser');

            // User group IDs
            $userGroup = GroupUsers::where('user_id',$user->id)
            ->where('status',1)
            ->pluck('group_id')
            ->toArray();

            // Purchased Quiz
            $purchaseQuiz = $this->getUserQuiz($user->id);

            $type = QuizType::select('name', 'slug')
                ->where('status', 1)
                ->withCount([
                    'quizzes as total_quizzes' => function ($query) use ($purchaseQuiz, $userGroup) {
                            $query->leftJoin('quiz_schedules', function ($join) {
                                $join->on('quizzes.id', '=', 'quiz_schedules.quizzes_id')
                                    ->where('quiz_schedules.status', 1);
                            })
                            ->where('quizzes.status', 1)
                            ->where(function ($query) {
                                $query->where('quizzes.is_public', 1)
                                ->orWhereNotNull('quiz_schedules.id'); 
                            })
                            ->where(function ($query) use ($purchaseQuiz,$userGroup) {
                                $query->where('quizzes.is_public', 1)
                                ->orWhereIn('quizzes.id', $purchaseQuiz)
                                ->orwhereIn('quiz_schedules.user_groups',$userGroup); 
                            })
                            ->distinct();
                    },
                    'quizzes as paid_quizzes' => function ($query) use ($purchaseQuiz, $userGroup) {
                        $query->leftJoin('quiz_schedules', function ($join) {
                                $join->on('quizzes.id', '=', 'quiz_schedules.quizzes_id')
                                    ->where('quiz_schedules.status', 1);
                            })
                            ->where('quizzes.status', 1)
                            ->where(function ($query) {
                                $query->where('quizzes.is_public', 1)
                                ->orWhereNotNull('quiz_schedules.id'); 
                            })
                            ->where(function ($query) use ($purchaseQuiz,$userGroup) {
                                $query->where('quizzes.is_public', 1)
                                ->orWhereIn('quizzes.id', $purchaseQuiz)
                                ->orwhereIn('quiz_schedules.user_groups',$userGroup); 
                            })
                            ->where('quizzes.is_free', 0)
                            ->distinct();  
                    },
                    'quizzes as unpaid_quizzes' => function ($query) use ($purchaseQuiz, $userGroup) {
                        // Count active, unpaid (free) quizzes (is_free = 1) with valid schedules (including multiple schedules for one exam)
                        $query->leftJoin('quiz_schedules', function ($join) {
                                $join->on('quizzes.id', '=', 'quiz_schedules.quizzes_id')
                                    ->where('quiz_schedules.status', 1);
                            })
                            ->where('quizzes.status', 1)
                            ->where(function ($query) {
                                $query->where('quizzes.is_public', 1)
                                ->orWhereNotNull('quiz_schedules.id'); 
                            })
                            ->where(function ($query) use ($purchaseQuiz,$userGroup) {
                                $query->where('quizzes.is_public', 1)
                                ->orWhereIn('quizzes.id', $purchaseQuiz)
                                ->orwhereIn('quiz_schedules.user_groups',$userGroup); 
                            })
                            ->where('quizzes.is_free', 1)
                            ->distinct();  // Ensures each schedule is counted separately
                    }
                ])
                ->get();
    
            return response()->json(['status' => true, 'data' => $type], 201);
    
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'error' => $th->getMessage()], 500);
        }
    }
    
    public function allQuiz(Request $request)
    {
        try {
     
            // Validate incoming request parameters
            $request->validate([
                'category' => 'required',
                'slug' => 'required'
            ]);

            // Fetch quiz type by slug and status
            $quizType = QuizType::select('id')->where('slug', $request->slug)->where('status', 1)->first();

            if ($quizType) {

                // Get the authenticated user
                $user = $request->attributes->get('authenticatedUser');

                // User group IDs
                $userGroup = GroupUsers::where('user_id',$user->id)
                ->where('status',1)
                ->pluck('group_id')
                ->toArray();

                // Purchased Quiz
                $purchaseQuiz = $this->getUserQuiz($user->id);

                $quizData = Quizze::leftJoin('quiz_schedules', function ($join) use($quizType){
                    $join->on('quizzes.id', '=', 'quiz_schedules.quizzes_id')
                        ->where('quiz_schedules.status', 1);
                })
                ->leftJoin('quiz_types', 'quizzes.quiz_type_id', '=', 'quiz_types.id')
                ->leftJoin('quiz_questions', 'quizzes.id', '=', 'quiz_questions.quizzes_id')
                ->leftJoin('questions', 'quiz_questions.question_id', '=', 'questions.id')
                ->where('quizzes.status', 1)
                ->where(function ($query) {
                    $query->where('quizzes.is_public', 1) 
                        ->orWhereNotNull('quiz_schedules.id'); 
                })
                ->where(function ($query) use ($purchaseQuiz,$userGroup) {
                    $query->where('quizzes.is_public', 1)
                    ->orWhereIn('quizzes.id', $purchaseQuiz)
                    ->orwhereIn('quiz_schedules.user_groups',$userGroup); 
                })
                ->where('quizzes.quiz_type_id', $quizType->id)
                ->where('quizzes.subcategory_id', $request->category)
                ->select(
                    'quizzes.slug as quizSlug',
                    'quizzes.title',
                    'quizzes.id',
                    'quizzes.duration_mode',
                    'quizzes.duration',
                    'quizzes.point_mode',
                    'quizzes.point',
                    'quizzes.is_free',
                    'quizzes.is_public',
                    'quiz_schedules.schedule_type',
                    'quiz_schedules.start_date',
                    'quiz_schedules.start_time',
                    'quiz_schedules.end_date',
                    'quiz_schedules.end_time',
                    'quiz_schedules.grace_period',
                    'quiz_schedules.id as schedule_id',
                    'quizzes.restrict_attempts',
                    'quizzes.total_attempts',
                    'quiz_schedules.user_groups',
                    DB::raw('SUM(CASE 
                        WHEN questions.type = "EMQ" AND JSON_VALID(questions.question) THEN JSON_LENGTH(questions.question) - 1
                        ELSE 1 
                    END) as total_questions'),
                    DB::raw('SUM(CAST(questions.default_marks AS DECIMAL)) as total_marks'),
                    DB::raw('SUM(COALESCE(questions.watch_time, 0)) as total_time')
                )
                ->groupBy(
                    'quizzes.slug',
                    'quizzes.id',
                    'quizzes.title',
                    'quizzes.duration_mode',
                    'quizzes.duration',
                    'quizzes.point_mode',
                    'quizzes.point',
                    'quizzes.is_free',
                    'quizzes.is_public',
                    'quiz_schedules.id',
                    'quiz_schedules.schedule_type',
                    'quiz_schedules.start_date',
                    'quiz_schedules.start_time',
                    'quiz_schedules.end_date',
                    'quiz_schedules.end_time',
                    'quizzes.restrict_attempts',
                    'quizzes.total_attempts',
                    'quiz_schedules.user_groups',
                    'quiz_schedules.grace_period'
                )
                ->havingRaw('COUNT(questions.id) > 0') // Only include exams with questions
                ->get();

                // Resume Quiz
                $current_time = now();
                $quizResults = QuizResult::where('end_time', '>', $current_time)
                ->where('user_id', $user->id)
                ->where('status', 'ongoing')
                ->get();

                // Create a map for quick lookup
                $quizResultScheduleMap = [];
                foreach ($quizResults as $quizResult) {
                    $key = $quizResult->quiz_id . '_' . $quizResult->schedule_id;
                    $quizResultScheduleMap[$key] = true;
                }


                $formattedQuizData = [];
                foreach ($quizData as $quiz) {

                    // Free / Paid
                    $checkfree = $quiz->is_free;
                    if(in_array($quiz->id,$purchaseQuiz) || in_array($quiz->user_groups,$userGroup)){
                        $checkfree = 1;
                    }

                    // Duration / Point
                    $formattedTime = $this->formatTime($quiz->total_time);
                    $time = $quiz->duration_mode == "manual" ? $this->formatTime($quiz->duration*60) : $formattedTime;
                    $marks = $quiz->point_mode == "manual" ? ($quiz->point * $quiz->total_questions) : $quiz->total_marks;

                    // Resume Exams
                    $quizScheduleKey = $quiz->id . '_' . ($quiz->schedule_id ?: 0); // Use 0 if no schedule_id is provided
                    $isResume = isset($quizResultScheduleMap[$quizScheduleKey]);

                    if ($quiz->is_public === 1 && !$quiz->schedule_id) {
                        $isResume = isset($quizResultScheduleMap[$quiz->id . '_0']);
                    }

                    // Attempts
                    $totalAttempt = $quiz->total_attempts ?? 1;
                    $totalAttempt = $quiz->restrict_attempts == 1 ? $totalAttempt : null;

                    // Attempts Completed or not checking
                    $scheduleId = $quiz->schedule_id ?? 0;
                    $userAttempt = QuizResult::where('user_id',$user->id)->where('quiz_id',$quiz->id)->where('schedule_id',$scheduleId)->count();

                    if($quiz->restrict_attempts == 1 && $userAttempt >= $totalAttempt){
                        continue;
                    }

                    // Add quiz details to the corresponding type slug, including schedule details
                    $formattedQuizData[] = [
                        'title' => $quiz->title,
                        'slug' => $quiz->quizSlug,
                        'questions' => $quiz->total_questions ?? 0,
                        'duration_mode' => $quiz->duration_mode,
                        'exam_duration' => $quiz->duration,
                        'point_mode' => $quiz->point_mode,
                        'point' => $quiz->point,
                        'is_free' => $checkfree,
                        'marks' => $marks,
                        'time' => $time,
                        'is_resume' => $isResume,
                        'total_attempts'=>$totalAttempt,
                        'schedule' => [
                            'schedule_id'=>$quiz->schedule_id,
                            'start_date' => $quiz->start_date,
                            'start_time' => $quiz->start_time,
                            'end_date' => $quiz->end_date,
                            'end_time' => $quiz->end_time,
                            'grace_period' => $quiz->grace_period,
                            'schedule_type' => $quiz->schedule_type,
                        ],
                    ];
                }

                // Format quiz data for the response
                // $formattedQuizData = $quizData->map(function ($quiz) use($quizResultExamScheduleMap,$quizType,$user){

                //     // Format the total time
                //     $formattedTime = $this->formatTime($quiz->total_time);

                //     // Public exam logic
                //     $examScheduleKey = $quiz->id . '_' . ($quiz->schedule_id ?: 0); // Use 0 if no schedule_id is provided
                //     $isResume = isset($quizResultExamScheduleMap[$examScheduleKey]);

                //     // If the exam is public and doesn't have a schedule, check for its record in resume state
                //     if ($quiz->is_public === 1 && !$quiz->schedule_id) {
                //         $isResume = isset($quizResultExamScheduleMap[$quiz->id . '_0']);
                //     }
    
                //     // Group exams by exam type slug
                //     if (!isset($formattedExamData[$quizType->slug])) {
                //         $formattedExamData[$quizType->slug] = [];
                //     }
    
                //     // Format time and marks based on the exam mode
                //     $time = $quiz->duration_mode == "manual" ? $quiz->exam_duration : $formattedTime;
                //     $marks = $quiz->point_mode == "manual" ? ($quiz->point * $quiz->total_questions) : $quiz->total_marks;
                //     $attempt = $quiz->total_attempts ?? "";

                //     $scheduleId = $quiz->schedule_id ?? 0;
                //     $userAttempt = QuizResult::where('user_id',$user->id)->where('quiz_id',$quiz->id)->where('schedule_id',$scheduleId)->count();

                //     $totalAttempts = $quiz->restrict_attempts == 0 ? null : $attempt;
                //     if($userAttempt >= $totalAttempts && $totalAttempts !== null && $quiz->restrict_attempts == 1){
                //         return null; // Skip quiz if user exceeded attempts
                //     }

                //     return [
                //         'title' => $quiz->title,
                //         'slug' => $quiz->quizSlug,
                //         'questions' => $quiz->total_questions ?? 0,
                //         'time' => $quiz->duration_mode == "manual" ? $this->formatTime($quiz->duration*60) : $this->formatTime($quiz->total_time),
                //         'marks' => $quiz->point_mode == "manual" ? ($quiz->point * $quiz->total_questions) : $quiz->total_marks,
                //         'is_free' => $quiz->is_free,
                //         'is_resume' =>$isResume,
                //         'total_attempts'=>$quiz->restrict_attempts == 0 ? "" : $attempt,
                //         'schedule' => [
                //             'schedule_id'=>$quiz->schedule_id,
                //             'start_date' => $quiz->start_date,
                //             'start_time' => $quiz->start_time,
                //             'end_date' => $quiz->end_date,
                //             'end_time' => $quiz->end_time,
                //             'grace_period' => $quiz->grace_period,
                //             'schedule_type' => $quiz->schedule_type,
                //         ],
                //     ];
                // });

                // Return the formatted data as JSON
                return response()->json(['status' => true, 'data' => $formattedQuizData], 200);
            }

            // Return error if quiz type not found
            return response()->json(['status' => false, 'error' => "Quiz Not Found"], 404);
        } catch (\Throwable $th) {
            // Log the error for debugging
            \Log::error('Error in allQuiz: ', ['error' => $th->getMessage()]);

            // Return error response with exception message
            return response()->json(['status' => false, 'error' => 'Internal Server Error: ' . $th->getMessage()], 500);
        }
    }
    
    public function quizDetail(Request $request, $slug)
    {
        try {
            // Validate incoming request data
            $request->validate([
                'category' => 'required|integer',
            ]);

            // Get the authenticated user
            $user = $request->attributes->get('authenticatedUser');
            
            // User group IDs
            $userGroup = GroupUsers::where('user_id',$user->id)
            ->where('status',1)
            ->pluck('group_id')
            ->toArray();

            // Purchased Quiz
            $purchaseQuiz = $this->getUserQuiz($user->id);

            // Fetch quiz details based on the category and slug
            $quizData = Quizze::leftJoin('quiz_schedules', function ($join) {
                $join->on('quizzes.id', '=', 'quiz_schedules.quizzes_id')
                    ->where('quiz_schedules.status', 1);
            })->select(
                'quizzes.id',
                'quizzes.slug',
                'quizzes.title',
                'quizzes.description',
                'quizzes.pass_percentage',
                'sub_categories.name as sub_category_name',
                'quiz_types.name as exam_type_name',
                'quizzes.duration_mode', 
                'quizzes.duration', 
                'quizzes.point_mode',
                'quizzes.point', 
                'quizzes.is_free', 
                'quizzes.is_public',
                'quiz_schedules.user_groups',
                DB::raw('SUM(CASE 
                    WHEN questions.type = "EMQ" AND JSON_VALID(questions.question) THEN JSON_LENGTH(questions.question) - 1
                    ELSE 1 
                END) as total_questions'),
                // DB::raw('COUNT(questions.id) as total_questions'),
                DB::raw('SUM(CAST(questions.default_marks AS DECIMAL)) as total_marks'),
                DB::raw('SUM(COALESCE(questions.watch_time, 0)) as total_time')
            )
            ->leftJoin('quiz_types', 'quizzes.quiz_type_id', '=', 'quiz_types.id')
            ->leftJoin('quiz_questions', 'quizzes.id', '=', 'quiz_questions.quizzes_id')
            ->leftJoin('sub_categories', 'quizzes.subcategory_id', '=', 'sub_categories.id')
            ->leftJoin('questions', 'quiz_questions.question_id', '=', 'questions.id')
            ->where('quizzes.subcategory_id', $request->category)
            ->where('quizzes.slug', $slug)
            ->where('quizzes.status', 1)
            ->where(function ($query) {
                $query->where('quizzes.is_public', 1) // IS THE Quiz IS PUBLIC OR HAVE A SCHEDULE (for private schedule is maindatory)
                    ->orWhereNotNull('quiz_schedules.id'); 
            })
            ->where(function ($query) use ($purchaseQuiz,$userGroup) {
                $query->where('quizzes.is_public', 1)
                ->orWhereIn('quizzes.id', $purchaseQuiz)
                ->orwhereIn('quiz_schedules.user_groups',$userGroup); 
            })
            ->groupBy(
                'quizzes.id',
                'quizzes.slug',
                'quizzes.title',
                'quizzes.description',
                'quizzes.pass_percentage',
                'quiz_types.name',
                'quizzes.duration_mode', 
                'sub_categories.name',
                'quizzes.duration', 
                'quizzes.point_mode',
                'quizzes.point',
                'quizzes.is_free',
                'quizzes.is_public',
                'quiz_schedules.user_groups'
            )
            ->havingRaw('COUNT(questions.id) > 0')
            ->first();

            // Check if quiz data is available
            if (!$quizData) {
                return response()->json(['status' => false, 'message' => 'Quiz not found'], 404);
            }

            // Adjust 'is_free' for assigned quiz, regardless of public or private
            if (in_array($quizData->id, $purchaseQuiz) || in_array($quizData->user_groups, $userGroup)) {
                $quizData->is_free = 1; // Make assigned quiz free
            }
            
            // Format time and marks
            $formattedTime = $this->formatTime($quizData->total_time);
            $time = $quizData->duration_mode == "manual" ? $this->formatTime($quizData->duration*60) : $formattedTime;
            $marks = $quizData->point_mode == "manual" ? ($quizData->point * $quizData->total_questions) : $quizData->total_marks;

            // Return the quiz data
            return response()->json([
                'status' => true,
                'data' => [
                    'title' => $quizData->title,
                    'quizType' => $quizData->exam_type_name,
                    'syllabus' => $quizData->sub_category_name,
                    'totalQuestions' => $quizData->total_questions,
                    'duration' => $time,
                    'marks' => $marks,
                    'description' => $quizData->description,
                    'is_free' => $quizData->is_free,
                ],
            ], 200);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'error' => 'Internal Server Error: ' . $th->getMessage()], 500);
        }
    }

    // PRACTICE SET
    public function practiceSet(Request $request)
    {
        try {
            // Validate incoming request data
            $request->validate([
                'category' => 'required|integer'
            ]);

            // Get the authenticated user
            $user = $request->attributes->get('authenticatedUser');

            // Purchased practice
            $purchasePractice = $this->getUserPractice($user->id);

            // Fetch practice sets
            $practiceSets = PracticeSet::select(
                    'practice_sets.id',
                    'practice_sets.title',
                    'practice_sets.slug',
                    'practice_sets.subCategory_id',
                    'skills.name as skill_name', 
                    'practice_sets.point_mode',
                    'practice_sets.points',
                    'practice_sets.is_free',
                    DB::raw('SUM(CASE 
                        WHEN questions.type = "EMQ" AND JSON_VALID(questions.question) THEN JSON_LENGTH(questions.question) - 1
                        ELSE 1 
                    END) as total_questions'), // Count total questions
                    DB::raw('SUM(CAST(questions.default_marks AS DECIMAL)) as total_marks'), // Sum total marks
                    DB::raw('SUM(COALESCE(questions.watch_time, 0)) as total_time') // Sum total watch time
                )
                ->leftJoin('practice_set_questions', 'practice_sets.id', '=', 'practice_set_questions.practice_set_id') // Join with practice_set_questions
                ->leftJoin('questions', 'practice_set_questions.question_id', '=', 'questions.id') // Join with questions
                ->leftJoin('skills', 'practice_sets.skill_id', '=', 'skills.id') // Join with skills to get skill name
                ->where('practice_sets.subCategory_id', $request->category)
                ->where('practice_sets.status', 1)
                ->orwhere(function ($query) use ($purchasePractice) {
                    $query->WhereIn('practice_sets.id', $purchasePractice); 
                })
                ->groupBy('practice_sets.id', 'practice_sets.title', 'practice_sets.slug', 'practice_sets.subCategory_id', 'skills.name','practice_sets.point_mode','practice_sets.points','practice_sets.is_free',) // Group by practice set and skill name
                ->havingRaw('COUNT(questions.id) > 0') 
                ->get();

            // Check if practice sets are found
            if ($practiceSets->isEmpty()) {
                return response()->json(['status' => false, 'message' => 'No practice sets found for this category.'], 404);
            }
            
            // Resume Practice Set
            $current_time = now();
            $resumepracticeSet = PracticeSetResult::where('end_time', '>', $current_time)
            ->where('user_id', $user->id)
            ->where('status', 'ongoing')
            ->pluck('practice_sets_id')
            ->toArray();


            // Group practice sets by skill name
            $groupedData = [];
            foreach ($practiceSets as $practiceSet) {

                $isResume = in_array($practiceSet->id,$resumepracticeSet) ? 1 : 0;
                $skillName = $practiceSet->skill_name ?? 'Unknown Skill'; // Handle null skill names
                if (!isset($groupedData[$skillName])) {
                    $groupedData[$skillName] = [];
                }

                // Duration / Point
                $marks = $practiceSet->point_mode == "manual" ? ($practiceSet->points * $practiceSet->total_questions) : $practiceSet->total_marks;

                $isFree = $practiceSet->is_free;
                if(in_array($practiceSet->id,$purchasePractice)){
                    $isFree = 1;
                }
                
                // Add the practice set data to the corresponding skill name group
                $groupedData[$skillName][] = [
                    'practice_title'   => $practiceSet->title,
                    'practice_question'=> $practiceSet->total_questions, // Use data from query result
                    'practice_time'    => $practiceSet->total_time,      // Use data from query result
                    'practice_marks'   => $marks,     // Use data from query result
                    'practice_slug'    => $practiceSet->slug,
                    'is_free' => $isFree,
                    'category' => $practiceSet->subCategory_id,
                    'is_resume'=>$isResume,
                ];
            }

            return response()->json(['status' => true, 'data' => $groupedData], 200);

        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'error' => 'Internal Server Error: ' . $th->getMessage()], 500);
        }
    }


    public function practiceSetDetail(Request $request, $slug)
    {
        try {
            // Validate incoming request data
            $request->validate([
                'category' => 'required|integer',
            ]);

            // Get the authenticated user
            $user = $request->attributes->get('authenticatedUser');

            // Purchased practice
            $purchasePractice = $this->getUserPractice($user->id);
    
            // Fetch practice set details based on the category and slug
            $practiceSetData = PracticeSet::select(
                    'practice_sets.id',
                    'practice_sets.title',
                    'practice_sets.description',
                    'sub_categories.name as sub_category_name',
                    'practice_sets.point_mode',
                    'practice_sets.points',
                    'practice_sets.is_free',
                    'practice_sets.subCategory_id',
                    DB::raw('SUM(CASE 
                        WHEN questions.type = "EMQ" AND JSON_VALID(questions.question) THEN JSON_LENGTH(questions.question) - 1
                        ELSE 1 
                    END) as total_questions'),
                    DB::raw('SUM(CAST(questions.default_marks AS DECIMAL)) as total_marks'),
                    DB::raw('SUM(COALESCE(questions.watch_time, 0)) as total_time')
                )
                ->leftJoin('practice_set_questions', 'practice_sets.id', '=', 'practice_set_questions.practice_set_id') // Join with practice_set_questions
                ->leftJoin('questions', 'practice_set_questions.question_id', '=', 'questions.id') // Join with questions to get actual question data
                ->leftJoin('sub_categories', 'practice_sets.subCategory_id', '=', 'sub_categories.id') // Join with sub_categories to get category name
                ->where('practice_sets.subCategory_id', $request->category)
                ->where('practice_sets.slug', $slug) // Assuming you have a slug column in the practice_sets table
                ->where('practice_sets.status', 1)
                ->orwhere(function ($query) use ($purchasePractice) {
                    $query->WhereIn('practice_sets.id', $purchasePractice); 
                })
                ->groupBy(
                    'practice_sets.id',
                    'practice_sets.subCategory_id',
                    'practice_sets.title',
                    'practice_sets.description',
                    'sub_categories.name',
                    'practice_sets.point_mode',
                    'practice_sets.points',
                    'practice_sets.is_free',
                )
                ->havingRaw('COUNT(questions.id) > 0')
                ->first();
    
            // Check if practice set data is available
            if (!$practiceSetData) {
                return response()->json(['status' => false, 'message' => 'Practice set not found'], 404);
            }

            // Adjust 'is_free' for assigned quiz, regardless of public or private
            if (in_array($practiceSetData->id, $purchasePractice)) {
                $practiceSetData->is_free = 1; // Make assigned quiz free
            }

            // Resume Exam
            $current_time = now();
            $resumepracticeSet = PracticeSetResult::where('end_time', '>', $current_time)->where('user_id', $user->id)->where('status', 'ongoing')->pluck('practice_sets_id')->toArray();

            // Format time and marks
            $time = $this->formatTime($practiceSetData->total_time);
            $marks = $practiceSetData->point_mode == "manual" ? $practiceSetData->points*$practiceSetData->total_questions : $practiceSetData->total_marks;

            $isResume = in_array($practiceSetData->id,$resumepracticeSet) ? 1 : 0;
    
            // Format response to match the structure needed by frontend
            return response()->json([
                'status' => true,
                'data' => [
                    'title' => $practiceSetData->title,
                    'syllabus' => $practiceSetData->sub_category_name,
                    'totalQuestions' => $practiceSetData->total_questions,
                    'duration' => $time,
                    'marks' => $marks,
                    'description' => $practiceSetData->description,
                    'is_free'=> $practiceSetData->is_free,
                    'category' => $practiceSetData->subCategory_id,
                    'is_resume'=> $isResume,
                ],
            ], 200);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'error' => 'Internal Server Error: ' . $th->getMessage()], 500);
        }
    }

    // VIDEO PRACTICE
    public function allVideo(Request $request)
    {
        try {
            // Validate incoming request data
            $request->validate([
                'category' => 'required|integer'
            ]);

            // Get the authenticated user
            $user = $request->attributes->get('authenticatedUser');

            // Get practice videos with related skill and video data
            $practiceVideos = PracticeVideo::with('skill', 'video')
            ->where('subcategory_id', $request->category)
            ->get();

            // Purchased video
            $purchaseVideo = $this->getUserVideo($user->id);

            // Initialize an empty array to hold the grouped data
            $groupedData = [];

            // Iterate over each practice video
            foreach ($practiceVideos as $practiceVideo) {
                // Ensure both skill and video exist (status is already handled in the relationship)
                if ($practiceVideo->skill && $practiceVideo->video && $practiceVideo->category) {
                    // Get the skill name (or use an ID if there's no specific skill name)
                    $skillName = $practiceVideo->skill->name ?? 'Unknown Skill';

                    // Initialize the skill group if it doesn't exist
                    if (!isset($groupedData[$skillName])) {
                        $groupedData[$skillName] = [];
                    }

                    $is_free = $practiceVideo->video->is_free;
                    if(in_array($practiceVideo->video->id,$purchaseVideo)){
                        $is_free = 1;
                    }

                    // Add the video data to the respective skill group
                    $groupedData[$skillName][] = [
                        'video_syllabus' => $practiceVideo->category->name,
                        'video_title' => $practiceVideo->video->title,
                        'video_slug' => $practiceVideo->video->slug,
                        'video_level' => $practiceVideo->video->level,
                        'video_watch_time' => $practiceVideo->video->watch_time,
                        'is_free' => $is_free,
                    ];
                }
            }
            // Return the formatted grouped data as a JSON response
            return response()->json(['status' => true, 'data' => $groupedData], 201);

        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'error' => $th->getMessage()], 500);
        }
    }

    public function videoDetail(Request $request, $slug)
    {
        try {
            // Get the authenticated user
            $user = $request->attributes->get('authenticatedUser');

            // Validate incoming request data
            $request->validate([
                'category' => 'required|integer'
            ]);

            // Purchased video
            $purchaseVideo = $this->getUserVideo($user->id);

            // Retrieve the video and its related skill
            $video = Video::with('skill')->where('slug', $slug)->where('status', 1)->first();

            if (!$video) {
                return response()->json(['status' => false, 'error' => 'Video not found.'], 404);
            }

            // Check if the video is related to the provided category (subcategory)
            $isVideoInCategory = PracticeVideo::where('subcategory_id', $request->category)
                ->where('video_id', $video->id)
                ->exists();

            if (!$isVideoInCategory) {
                return response()->json(['status' => false, 'error' => 'Video not found.'], 400);
            }

            $is_free = $video->is_free == 1 ? "Free" : "Paid";
            if(in_array($video->id,$purchaseVideo)){
                $is_free = "Free";
            }

            if ($is_free === "Paid") {
                return response()->json(['status' => false, 'error' => 'Please Purchase this video.'], 400);
            }

            // Prepare the video data to return (custom response format)
            $videoData = [
                'title' => $video->title,
                'skill' => $video->skill->name ?? 'Unknown Skill',  // Return skill name if available
                'watch_time' => $video->watch_time,
                'is_free' => $is_free,
                'level' => $video->level,
                'tags' => $video->tags,
                'thumbnail' => $video->thumbnail,
                'video_type' => $video->type,
                'description' => $video->description,
                'video' => $video->source,  // Assuming 'source' holds the video URL or source
            ];

            // Return the video details in the response
            return response()->json(['status' => true, 'data' => $videoData], 200);

        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'error' => $th->getMessage()], 500);
        }
    }

    // LESSON PRACTICE
    public function allLesson(Request $request){
        try {
            // Validate incoming request data
            $request->validate([
                'category' => 'required|integer'
            ]);

            // Get the authenticated user
            $user = $request->attributes->get('authenticatedUser');

            // Pruchase Lesson
            $purchaseLesson = $this->getUserLesson($user->id);

            // Get practice lesson with related skill and lesson data
            $practiceLessons = PracticeLesson::with('skill', 'lesson')
            ->where('subcategory_id', $request->category)
            ->get();

            // Initialize an empty array to hold the grouped data
            $groupedData = [];
            
            // Iterate over each practice lesson
            foreach ($practiceLessons as $practiceLesson) {
                // Ensure both skill and lesson exist (status is already handled in the relationship)
                if ($practiceLesson->skill && $practiceLesson->lesson && $practiceLesson->category) {

                    // Get the skill name (or use an ID if there's no specific skill name)
                    $skillName = $practiceLesson->skill->name ?? 'Unknown Skill';

                    // Initialize the skill group if it doesn't exist
                    if (!isset($groupedData[$skillName])) {
                        $groupedData[$skillName] = [];
                    }

                    $isFree = $practiceLesson->lesson->is_free;
                    if(in_array($practiceLesson->lesson->id,$purchaseLesson)){
                        $isFree = 1;
                    }

                    // Add the lesson data to the respective skill group
                    $groupedData[$skillName][] = [
                        'lesson_syllabus' => $practiceLesson->category->name,
                        'lesson_free' => $isFree,
                        'lesson_title' => $practiceLesson->lesson->title,
                        'lesson_slug' => $practiceLesson->lesson->slug,
                        'lesson_level' => $practiceLesson->lesson->level,
                        'lesson_read_time' => $practiceLesson->lesson->read_time,
                    ];
                }
            }
            // Return the formatted grouped data as a JSON response
            return response()->json(['status' => true, 'data' => $groupedData], 201);

        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'error' => $th->getMessage()], 500);
        }
    }
    

    public function lessonDetail(Request $request, $slug)
    {
        try {
            // Get the authenticated user
            $user = $request->attributes->get('authenticatedUser');

            // Validate incoming request data
            $request->validate([
                'category' => 'required|integer'
            ]);

            // Pruchase Lesson
            $purchaseLesson = $this->getUserLesson($user->id);

            // Retrieve the lesson and its related skill
            $lesson = Lesson::with('skill')->where('slug', $slug)->where('status', 1)->first();

            if (!$lesson) {
                return response()->json(['status' => false, 'error' => 'Lesson not found.'], 404);
            }

            // Check if the lesson is related to the provided category (subcategory)
            $isVessonInCategory = PracticeLesson::where('subcategory_id', $request->category)
                ->where('lesson_id', $lesson->id)
                ->exists();

            if (!$isVessonInCategory) {
                return response()->json(['status' => false, 'error' => 'Lesson not found.'], 400);
            }

            $is_free = $lesson->is_free == 1 ? "Free" : "Paid";
            if(in_array($lesson->id,$purchaseLesson)){
                $is_free = "Free";
            }

            if ($lesson->is_free === "Paid") {
                return response()->json(['status' => false, 'error' => 'Please Purhcase this Lesson.'], 400);
            }

            // Prepare the lesson data to return (custom response format)
            $lessonData = [
                'title' => $lesson->title,
                'skill' => $lesson->skill->name ?? 'Unknown Skill',  // Return skill name if available
                'read_time' => $lesson->read_time,
                'is_free' => $is_free,
                'level' => $lesson->level,
                'tags' => $lesson->tags,
                'description' => $lesson->description,
            ];

            // Return the lesson details in the response
            return response()->json(['status' => true, 'data' => $lessonData], 200);

        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'error' => $th->getMessage()], 500);
        }
    }

    public function userSubscription(Request $request){
        try {
            // Validate the request
            $request->validate([
                'type' => 'required' // Expected to be the feature type
            ]);
    
            $type = $request->type;
    
            // Retrieve the authenticated user from request attributes
            $user = $request->attributes->get('authenticatedUser');
    
            // Check if the user is authenticated
            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'User not authenticated',
                ], 401);
            }
    
            // Fetch the user from the database
            $user = User::findOrFail($user->id);
    
            // Get the current date and time
            $currentDate = now();
    
            // Fetch the user's active subscription
            $subscription = Subscription::with('plans')->where('user_id', $user->id)->where('stripe_status', 'complete')->where('ends_at', '>', $currentDate)->latest()->first();
    
            // If no active subscription, return error
            if (!$subscription) {
                return response()->json(['status' => false, 'error' => 'Please buy a subscription to access this course.'], 404);
            }
    
            // Fetch the plan related to this subscription
            $plan = $subscription->plans;
    
            if (!$plan) {
                return response()->json(['status' => false, 'error' => 'No associated plan found for this subscription.'], 404);
            }
    
            // Check if the plan allows unlimited access
            if ($plan->feature_access == 1) {
                return response()->json(['status' => true, 'data' => $subscription], 200);
            } else {
                // Fetch the allowed features for this plan
                $allowed_features = json_decode($plan->features, true);
    
                // Check if the requested feature type is in the allowed features
                if (in_array($type, $allowed_features)) {
                    return response()->json(['status' => true, 'data' => $subscription], 200);
                } else {
                    return response()->json(['status' => false, 'error' => 'Feature not available in your plan. Please upgrade your subscription.'], 403);
                }
            }
        } catch (\Throwable $th) {
            // Handle any exceptions
            return response()->json(['status' => false, 'error' => $th->getMessage()], 500);
        }
    }
    

    public function mySubscription(Request $request)
    {
        try {
            // Retrieve the authenticated user from request attributes
            $user = $request->attributes->get('authenticatedUser');
    
            // Check if the user is authenticated
            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'User not authenticated',
                ], 401);
            }
    
            $currentDate = now();
    
            // Fetch the user's subscriptions with associated plan details
            $subscriptions = Subscription::select(
                    'subscriptions.stripe_subscription_id as subscription_id',
                    'subscriptions.start_date as purchase_date',
                    'subscriptions.end_date as ends_date',
                    'subscriptions.status as subscription_status',
                    'subscriptions.type as subscription_type',
                    'plans.name as plan_name',
                    'plans.price as plan_price'
                )
                ->join('plans', 'subscriptions.plan_id', '=', 'plans.id')
                ->where('subscriptions.user_id', $user->id)
                ->orderBy('subscriptions.created_at', 'desc') // Get latest first
                ->get();
    
            // Track whether we have marked one subscription as Active
            $hasActive = false;
    
            // Loop through subscriptions and mark the latest active one, others as ended
            $subscriptions = $subscriptions->map(function ($subscription) use ($currentDate, &$hasActive) {
                // Convert dates to Carbon instances if they are not already
                $purchaseDate = Carbon::parse($subscription->purchase_date);
                $endsDate = Carbon::parse($subscription->ends_date);
    
                // Check if this is the first active subscription we encounter
                if (!$hasActive && $endsDate > $currentDate && $subscription->subscription_status == 'complete') {
                    $status = 'Active';
                    $hasActive = true; // Mark that we've found the active subscription
                } else {
                    $status = 'Ended';
                }

                $allFeatures = ["practice","quizzes","lessons","videos","exams"];
                $features = empty($subscription->features) ? $allFeatures : $subscription->features;

                return [
                    'id' => $subscription->subscription_id,
                    'features' => $features,
                    'plan_name' => $subscription->plan_name,
                    'plan_price' => $subscription->plan_price,
                    'purchase_date' => $purchaseDate->format('Y-m-d'),
                    'ends_date' => $endsDate->format('Y-m-d'),
                    'status' => $status,
                ];
            });
    
            // Return a successful response with the subscription data
            return response()->json([
                'status' => true,
                'subscriptions' => $subscriptions,
            ], 200);
    
        } catch (\Throwable $th) {
            // Return a JSON response with error details if an exception occurs
            return response()->json(['status' => false, 'error' => $th->getMessage()], 500);
        }
    }

    public function myPayment(Request $request){
        try {
            // Retrieve the authenticated user from request attributes
            $user = $request->attributes->get('authenticatedUser');
    
            // Check if the user is authenticated
            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'User not authenticated',
                ], 401);
            }
    
            $currentDate = now();
    
            // Fetch the user's subscriptions with associated plan details
            $payments = Payment::select('amount', 'currency', 'status', 'created_at','stripe_payment_id')
            ->where('user_id', $user->id)
            ->where('subscription_id','!=',null)
            ->get()
            ->map(function ($payment) {
                return [
                    'payment_id' => $payment->stripe_payment_id,
                    'amount' => $payment->amount,
                    'currency' => $payment->currency,
                    'status' => $payment->status,
                    'created_at' => Carbon::parse($payment->created_at)->format('Y-m-d'), // Format created_at
                ];
            });
    
            
            // Return a successful response with the subscription data
            return response()->json([
                'status' => true,
                'payments' => $payments,
            ], 200);
    
        } catch (\Throwable $th) {
            // Return a JSON response with error details if an exception occurs
            return response()->json(['status' => false, 'error' => $th->getMessage()], 500);
        }
    }
    
    public function invoiceDetail(Request $request, $paymentId)
    {
        try {
            // Get the authenticated user
            $user = $request->attributes->get('authenticatedUser');
            
            // Ensure $user is available
            if (!$user) {
                return response()->json(['status' => false, 'error' => 'User not authenticated'], 401);
            }

            // Retrieve billing information with joins
            $billing = BillingSetting::select(
                'billing_settings.vendor_name',
                'billing_settings.address',
                'billing_settings.city_id',
                'billing_settings.state_id',
                'billing_settings.country_id',
                'billing_settings.zip',
                'billing_settings.phone_number',
                'billing_settings.vat_number',
                'billing_settings.enable_invoicing',
                'billing_settings.invoice_prefix',
                'countries.name as country_name',
                'states.name as state_name',
                'cities.name as city_name'
            )
            ->leftJoin('countries', 'billing_settings.country_id', '=', 'countries.id')
            ->leftJoin('states', 'billing_settings.state_id', '=', 'states.id')
            ->leftJoin('cities', 'billing_settings.city_id', '=', 'cities.id')
            ->first();

            // Retrieve the most recent subscription for the user
            $payment = Payment::where('user_id', $user->id)->where('stripe_payment_id', $paymentId)->first();

            // Prepare the response data
            $data = [
                'billing' => $billing,
                'payment' => $payment
            ];

            // Return the response with status 200
            return response()->json(['status' => true, 'data' => $data], 200);
            
        } catch (\Throwable $th) {
            // Handle exceptions and return error message
            return response()->json(['status' => false, 'error' => $th->getMessage()], 500);
        }
    }

    

    
}
