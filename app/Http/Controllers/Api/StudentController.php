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
use App\Models\Plan;

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

    public function examType(){
        try {
            $type = ExamType::select('name','slug')->where('status', 1)->get();
            return response()->json(['status'=> true,'data' => $type], 201);
        } catch (\Throwable $th) {
            return response()->json(['status'=> false,'error' => $th->getMessage()], 500);
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
                // Fetch exam data grouped by exam type slug
                $examData = Exam::select(
                    'exam_types.slug as exam_type_slug', // Fetch exam type slug
                    'exams.slug', // Fetch exam slug
                    'exams.title', // Fetch exam title
                    'exams.duration_mode', 
                    'exams.exam_duration', 
                    'exams.point_mode',
                    'exams.point', 
                    'exams.is_free',
                    DB::raw('COUNT(questions.id) as total_questions'), // Count total questions for each exam
                    DB::raw('SUM(CAST(questions.default_marks AS DECIMAL)) as total_marks'), // Sum total marks for each exam
                    DB::raw('SUM(COALESCE(questions.watch_time, 0)) as total_time') // Sum time for each question using watch_time
                )
                ->leftJoin('exam_types', 'exams.exam_type_id', '=', 'exam_types.id') // Join with exam_types
                ->leftJoin('exam_questions', 'exams.id', '=', 'exam_questions.exam_id') // Join with exam_questions
                ->leftJoin('questions', 'exam_questions.question_id', '=', 'questions.id') // Join with questions
                ->where('exams.exam_type_id', $examType->id) // Filter by exam type ID
                ->where('exams.subcategory_id', $request->category) // Filter by subcategory ID
                ->where('exams.status', 1) // Filter by exam status
                ->groupBy('exam_types.slug', 'exams.slug', 'exams.id', 'exams.title',  'exams.duration_mode', 
                'exams.exam_duration',   'exams.point_mode','exams.point', 'exams.is_free',) // Group by necessary fields
                ->havingRaw('COUNT(questions.id) > 0') // Only include exams with more than 0 questions
                ->get();

                // Initialize array to store formatted exam data
                $formattedExamData = [];

                foreach ($examData as $exam) {
                    // Format the total time
                    $formattedTime = $this->formatTime($exam->total_time);

                    // Group exams by exam type slug
                    if (!isset($formattedExamData[$examType->slug])) {
                        $formattedExamData[$examType->slug] = [];
                    }

                    $time = $exam->duration_mode == "manual" ? $exam->exam_duration : $formattedTime;
                    $marks = $exam->point_mode == "manual" ? ($exam->point*$exam->total_questions) : $exam->total_marks;

                    // Add exam details to the corresponding type slug
                    $formattedExamData[$examType->slug][] = [
                        'title' => $exam->title,
                        'slug' => $exam->slug,
                        'questions' => $exam->total_questions ?? 0,
                        'time' => $time ?? 0,
                        'marks' => $marks ?? 0,
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
            $timeString .= $hours . ' hrs ';
        }
        if ($minutes > 0) {
            $timeString .= $minutes . ' mins ';
        }
        if ($seconds > 0) {
            $timeString .= $seconds . ' secs';
        }

        return trim($timeString); // Trim any extra spaces
    }

    public function examDetail(Request $request,$slug)
    {
        try {
            // Validate incoming request data
            $request->validate([
                'category' => 'required|integer',
            ]);

            // Fetch exam details based on the category and slug
            $examData = Exam::select(
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
                DB::raw('COUNT(questions.id) as total_questions'),
                DB::raw('SUM(CAST(questions.default_marks AS DECIMAL)) as total_marks'), 
                DB::raw('SUM(COALESCE(questions.watch_time, 0)) as total_time')
            )
            ->leftJoin('exam_types', 'exams.exam_type_id', '=', 'exam_types.id') 
            ->leftJoin('sub_categories', 'exams.subcategory_id', '=', 'sub_categories.id') 
            ->leftJoin('exam_questions', 'exams.id', '=', 'exam_questions.exam_id') 
            ->leftJoin('questions', 'exam_questions.question_id', '=', 'questions.id') 
            ->where('exams.subcategory_id', $request->category) 
            ->where('exams.slug', $slug) 
            ->where('exams.status', 1)
            ->groupBy('exam_types.slug', 'exams.id', 'exams.title', 'exams.description', 'exams.pass_percentage', 'sub_categories.name', 'exam_types.name',  'exams.duration_mode', 
            'exams.exam_duration', 
            'exams.point_mode',
            'exams.point', 
            'exams.is_free',) 
            ->havingRaw('COUNT(questions.id) > 0')
            ->first();
            
            // Check if exam data is available
            if (!$examData) {
                return response()->json(['status' => false, 'message' => 'Exam not found'], 404);
            }

            $time = $examData->duration_mode == "manual" ? $examData->exam_duration : $this->formatTime($examData->total_time);
            $marks = $examData->point_mode == "manual" ? ($examData->point*$examData->total_questions) : $examData->total_marks;

            // Format response to match the structure needed by frontend
            return response()->json([
                'status' => true,
                'data' => [
                    'title' => $examData->title,
                    'examType' => $examData->exam_type_name,
                    'syllabus' => $examData->sub_category_name,
                    'totalQuestions' => $examData->total_questions,
                    'duration' => $time,  // Call formatTime from within the class
                    'marks' => $marks,
                    'description' => $examData->description
                ]
            ], 200);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'error' => 'Internal Server Error : '.$th->getMessage()], 500);
        }
    }


    // QUIZ DATA
    public function quizType(){
        try {
            $type = QuizType::select('name','slug')->where('status', 1)->get();
            return response()->json(['status'=> true,'data' => $type], 201);
        } catch (\Throwable $th) {
            return response()->json(['status'=> false,'error' => $th->getMessage()], 500);
        }
    }

    public function allQuiz(Request $request){
        try {
            // Fetch quiz type by slug and status
            $quizType = QuizType::select('id')->where('slug', $request->slug)->where('status', 1)->first();

            if ($quizType) {
                // Fetch quiz data grouped by type.slug
                $quizData = Quizze::select(
                        'quizzes.slug as quizSlug',
                        'quiz_types.slug', // Fetch type slug
                        'quizzes.title', // Fetch quiz title
                        'quizzes.duration_mode', 
                        'quizzes.duration', 
                        'quizzes.point_mode',
                        'quizzes.point', 
                        'quizzes.is_free', 
                        DB::raw('COUNT(questions.id) as total_questions'), // Count total questions for each quiz
                        DB::raw('SUM(CAST(questions.default_marks AS DECIMAL)) as total_marks'), // Sum total marks for each quiz
                        DB::raw('SUM(COALESCE(questions.watch_time, 0)) as total_time') // Sum time for each question using watch_time
                    )
                    ->leftJoin('quiz_types', 'quizzes.quiz_type_id', '=', 'quiz_types.id') // Join with the quiz_types table
                    ->leftJoin('quiz_questions', 'quizzes.id', '=', 'quiz_questions.quizzes_id') // Join with quiz_questions
                    ->leftJoin('questions', 'quiz_questions.question_id', '=', 'questions.id') // Join with questions
                    ->where('quizzes.quiz_type_id', $quizType->id) // Filter by the provided quiz type
                    ->where('quizzes.subcategory_id', $request->category) // Filter by subcategory_id
                    ->where('quizzes.status', 1) // Filter by quiz status
                    ->groupBy('quiz_types.slug','quizzes.slug', 'quizzes.id', 'quizzes.title','quizzes.duration_mode', 
                    'quizzes.duration', 'quizzes.point_mode','quizzes.point',  'quizzes.is_free', ) // Group by type and quiz details
                    ->havingRaw('COUNT(questions.id) > 0') // Only include quizzes with more than 0 questions
                    ->get();

                // Initialize array to store formatted quiz data
                $formattedQuizData = [];

                foreach ($quizData as $quiz) {
                    // Format the total time using the new method
                    $formattedTime = $this->formatTime($quiz->total_time); // Use the total_time from questions

                    // Group quizs by slug (quiz type)
                    if (!isset($formattedQuizData[$quiz->slug])) {
                        $formattedQuizData[$quiz->slug] = [];
                    }

                    $time = $quiz->duration_mode == "manual" ? $quiz->duration : $formattedTime;
                    $marks = $quiz->point_mode == "manual" ? ($quiz->point*$quiz->total_questions) : $quiz->total_marks;

                    // Add quiz details to the corresponding type slug
                    $formattedQuizData[$quiz->slug][] = [
                        'title' => $quiz->title,
                        'slug' => $quiz->quizSlug,
                        'questions' => $quiz->total_questions ?? 0,
                        'time' => $time, // Use the formatted time
                        'marks' => $marks ?? 0,
                        'is_free'=> $quiz->is_free,
                    ];
                }

                // Return the formatted data as JSON
                return response()->json(['status' => true, 'data' => $formattedQuizData], 200);
            }

            // Return error if quiz type not found
            return response()->json(['status' => false, 'error' => "Quiz Not Found"], 404);
            
        } catch (\Throwable $th) {
            // Return error response with exception message
            return response()->json(['status' => false, 'error' => $th->getMessage()], 500);
        }
    }

    // public function quizDetail(Request $request, $slug)
    // {
    //     try {
    //         // Validate incoming request data
    //         $request->validate([
    //             'category' => 'required|integer',
    //         ]);

    //         // Fetch quiz details based on the category and slug
    //         $quizData = Quizze::select(
    //             'quiz_types.slug as exam_type_slug',
    //             'quizzes.title',
    //             'quizzes.description',
    //             'quizzes.pass_percentage',
    //             'sub_categories.name as sub_category_name',
    //             'quiz_types.name as exam_type_name',
    //             DB::raw('COUNT(questions.id) as total_questions'),
    //             DB::raw('SUM(CAST(questions.default_marks AS DECIMAL)) as total_marks'),
    //             DB::raw('SUM(COALESCE(questions.watch_time, 0)) as total_time')
    //         )
    //         ->leftJoin('quiz_types', 'quizzes.quiz_type_id', '=', 'quiz_types.id')
    //         ->leftJoin('sub_categories', 'quizzes.subcategory_id', '=', 'sub_categories.id')
    //         ->leftJoin('exam_questions', 'quizzes.id', '=', 'exam_questions.exam_id')
    //         ->leftJoin('questions', 'exam_questions.question_id', '=', 'questions.id')
    //         ->where('quizzes.subcategory_id', $request->category)
    //         ->where('quizzes.slug', $slug)
    //         ->where('quizzes.status', 1)
    //         ->groupBy(
    //             'quiz_types.slug',
    //             'quizzes.id',
    //             'quizzes.title',
    //             'quizzes.description',
    //             'quizzes.pass_percentage',
    //             'sub_categories.name',
    //             'quiz_types.name'
    //         )
    //         ->havingRaw('COUNT(questions.id) > 0')
    //         ->first();

    //         // Check if exam data is available
    //         if (!$quizData) {
    //             return response()->json(['status' => false, 'message' => 'Quiz not found'], 404);
    //         }

    //         // Format response to match the structure needed by frontend
    //         return response()->json([
    //             'status' => true,
    //             'data' => [
    //                 'title' => $quizData->title,
    //                 'quizType' => $quizData->exam_type_name,
    //                 'syllabus' => $quizData->sub_category_name,
    //                 'totalQuestions' => $quizData->total_questions,
    //                 'duration' => $this->formatTime($quizData->total_time), // Call formatTime from within the class
    //                 'marks' => $quizData->total_marks,
    //                 'description' => $quizData->description,
    //             ],
    //         ], 200);
    //     } catch (\Throwable $th) {
    //         return response()->json(['status' => false, 'error' => 'Internal Server Error: ' . $th->getMessage()], 500);
    //     }
    // }

    public function quizDetail(Request $request, $slug)
    {
        try {
            // Validate incoming request data
            $request->validate([
                'category' => 'required|integer',
            ]);

            // Fetch quiz details based on the category and slug, using the same joins as in allQuiz
            $quizData = Quizze::select(
                'quiz_types.slug as exam_type_slug',
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
                DB::raw('COUNT(questions.id) as total_questions'),  // Count the total number of questions
                DB::raw('SUM(CAST(questions.default_marks AS DECIMAL)) as total_marks'),  // Sum the total marks
                DB::raw('SUM(COALESCE(questions.watch_time, 0)) as total_time')  // Sum the total time for the quiz
            )
            ->leftJoin('quiz_types', 'quizzes.quiz_type_id', '=', 'quiz_types.id')
            ->leftJoin('sub_categories', 'quizzes.subcategory_id', '=', 'sub_categories.id')
            ->leftJoin('quiz_questions', 'quizzes.id', '=', 'quiz_questions.quizzes_id')  // Join with quiz_questions
            ->leftJoin('questions', 'quiz_questions.question_id', '=', 'questions.id')  // Join with questions
            ->where('quizzes.subcategory_id', $request->category)  // Filter by category
            ->where('quizzes.slug', $slug)  // Filter by quiz slug
            ->where('quizzes.status', 1)  // Only active quizzes
            ->groupBy(
                'quiz_types.slug',
                'quizzes.id',
                'quizzes.title',
                'quizzes.description',
                'quizzes.pass_percentage',
                'sub_categories.name',
                'quiz_types.name',
                'quizzes.duration_mode', 
                'quizzes.duration', 
                'quizzes.point_mode',
                'quizzes.point', 
                'quizzes.is_free'
            )
            ->havingRaw('COUNT(questions.id) > 0')  // Ensure quizzes with more than 0 questions
            ->first();

            // Check if quiz data is available
            if (!$quizData) {
                return response()->json(['status' => false, 'message' => 'Quiz not found'], 404);
            }

            $time = $quizData->duration_mode == "manual" ? $quizData->duration : $this->formatTime($quizData->total_time);
            $marks = $quizData->point_mode == "manual" ? ($quizData->point*$quizData->total_questions) : $quizData->total_marks;

            // Format response to match the structure needed by frontend
            return response()->json([
                'status' => true,
                'data' => [
                    'title' => $quizData->title,
                    'quizType' => $quizData->exam_type_name,
                    'syllabus' => $quizData->sub_category_name,
                    'totalQuestions' => $quizData->total_questions,
                    'duration' => $time,  // Use formatted time as in allQuiz
                    'marks' => $marks,
                    'description' => $quizData->description,
                    'is_free'=>$quizData->is_free,
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
                'category' => 'required|integer',
            ]);

            // Fetch practice sets and their related data, including skill name
            $practiceSets = PracticeSet::select(
                    'practice_sets.title',
                    'practice_sets.slug',
                    'practice_sets.subCategory_id',
                    'skills.name as skill_name', // Select skill name
                    'practice_sets.point_mode',
                    'practice_sets.points',
                    'practice_sets.is_free',
                    DB::raw('COUNT(questions.id) as total_questions'), // Count total questions
                    DB::raw('SUM(CAST(questions.default_marks AS DECIMAL)) as total_marks'), // Sum total marks
                    DB::raw('SUM(COALESCE(questions.watch_time, 0)) as total_time') // Sum total watch time
                )
                ->leftJoin('practice_set_questions', 'practice_sets.id', '=', 'practice_set_questions.practice_set_id') // Join with practice_set_questions
                ->leftJoin('questions', 'practice_set_questions.question_id', '=', 'questions.id') // Join with questions
                ->leftJoin('skills', 'practice_sets.skill_id', '=', 'skills.id') // Join with skills to get skill name
                ->where('practice_sets.subCategory_id', $request->category)
                ->where('practice_sets.status', 1)
                ->groupBy('practice_sets.id', 'practice_sets.title', 'practice_sets.slug', 'practice_sets.subCategory_id', 'skills.name','practice_sets.point_mode','practice_sets.points','practice_sets.is_free',) // Group by practice set and skill name
                ->havingRaw('COUNT(questions.id) > 0') // Only include practice sets with questions
                ->get();

            // Check if practice sets are found
            if ($practiceSets->isEmpty()) {
                return response()->json(['status' => false, 'message' => 'No practice sets found for this category.'], 404);
            }

            // Group practice sets by skill name
            $groupedData = [];
            foreach ($practiceSets as $practiceSet) {
                $skillName = $practiceSet->skill_name ?? 'Unknown Skill'; // Handle null skill names
                if (!isset($groupedData[$skillName])) {
                    $groupedData[$skillName] = [];
                }

                $marks = $practiceSet->point_mode == "manual" ? $practiceSet->points*$practiceSet->total_questions : $practiceSet->total_marks;
                
                // Add the practice set data to the corresponding skill name group
                $groupedData[$skillName][] = [
                    'practice_title'   => $practiceSet->title,
                    'practice_question'=> $practiceSet->total_questions, // Use data from query result
                    'practice_time'    => $practiceSet->total_time,      // Use data from query result
                    'practice_marks'   => $marks,     // Use data from query result
                    'practice_slug'    => $practiceSet->slug,
                    'is_free' => $practiceSet->is_free,
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
    
            // Fetch practice set details based on the category and slug
            $practiceSetData = PracticeSet::select(
                    'practice_sets.title',
                    'practice_sets.description',
                    'sub_categories.name as sub_category_name',
                    'practice_sets.point_mode',
                    'practice_sets.points',
                    'practice_sets.is_free',
                    DB::raw('COUNT(questions.id) as total_questions'),
                    DB::raw('SUM(CAST(questions.default_marks AS DECIMAL)) as total_marks'),
                    DB::raw('SUM(COALESCE(questions.watch_time, 0)) as total_time')
                )
                ->leftJoin('practice_set_questions', 'practice_sets.id', '=', 'practice_set_questions.practice_set_id') // Join with practice_set_questions
                ->leftJoin('questions', 'practice_set_questions.question_id', '=', 'questions.id') // Join with questions to get actual question data
                ->leftJoin('sub_categories', 'practice_sets.subCategory_id', '=', 'sub_categories.id') // Join with sub_categories to get category name
                ->where('practice_sets.subCategory_id', $request->category)
                ->where('practice_sets.slug', $slug) // Assuming you have a slug column in the practice_sets table
                ->where('practice_sets.status', 1)
                ->groupBy(
                    'practice_sets.id',
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

            $marks = $practiceSetData->point_mode == "manual" ? $practiceSetData->points*$practiceSetData->total_questions : $this->formatTime($practiceSetData->total_time);
    
            // Format response to match the structure needed by frontend
            return response()->json([
                'status' => true,
                'data' => [
                    'title' => $practiceSetData->title,
                    'syllabus' => $practiceSetData->sub_category_name,
                    'totalQuestions' => $practiceSetData->total_questions,
                    'duration' => $this->formatTime($practiceSetData->total_time),
                    'marks' => $marks,
                    'description' => $practiceSetData->description,
                    'is_free'=> $practiceSetData->is_free
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

            // Get practice videos with related skill and video data
            $practiceVideos = PracticeVideo::with('skill', 'video')
                ->where('subcategory_id', $request->category)
                ->get();

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

                    // Add the video data to the respective skill group
                    $groupedData[$skillName][] = [
                        'video_syllabus' => $practiceVideo->category->name,
                        'video_title' => $practiceVideo->video->title,
                        'video_slug' => $practiceVideo->video->slug,
                        'video_level' => $practiceVideo->video->level,
                        'video_watch_time' => $practiceVideo->video->watch_time,
                        'is_free' => $practiceVideo->video->is_free,
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
            $type = "videos";

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
                // return response()->json(['status' => true, 'data' => $subscription], 200);
            } else {
                // Fetch the allowed features for this plan
                $allowed_features = json_decode($plan->features, true);
    
                // Check if the requested feature type is in the allowed features
                if (in_array($type, $allowed_features)) {
                    // return response()->json(['status' => true, 'data' => $subscription], 200);
                } else {
                    return response()->json(['status' => false, 'error' => 'Feature not available in your plan. Please upgrade your subscription.'], 403);
                }
            }

            // Validate incoming request data
            $request->validate([
                'category' => 'required|integer'
            ]);

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

            // Prepare the video data to return (custom response format)
            $videoData = [
                'title' => $video->title,
                'skill' => $video->skill->name ?? 'Unknown Skill',  // Return skill name if available
                'watch_time' => $video->watch_time,
                'is_free' => $video->is_free == 1 ? "Free" : "Paid",
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

                    // Add the lesson data to the respective skill group
                    $groupedData[$skillName][] = [
                        'lesson_syllabus' => $practiceLesson->category->name,
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
            // Validate incoming request data
            $request->validate([
                'category' => 'required|integer'
            ]);

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

            // Prepare the lesson data to return (custom response format)
            $lessonData = [
                'title' => $lesson->title,
                'skill' => $lesson->skill->name ?? 'Unknown Skill',  // Return skill name if available
                'read_time' => $lesson->read_time,
                'is_free' => $lesson->is_free == 1 ? "Free" : "Paid",
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
    
}
