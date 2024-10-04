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
                ->groupBy('exam_types.slug', 'exams.slug', 'exams.id', 'exams.title') // Group by necessary fields
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

                    // Add exam details to the corresponding type slug
                    $formattedExamData[$examType->slug][] = [
                        'title' => $exam->title,
                        'slug' => $exam->slug,
                        'questions' => $exam->total_questions ?? 0,
                        'time' => $formattedTime,
                        'marks' => $exam->total_marks ?? 0,
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
            ->groupBy('exam_types.slug', 'exams.id', 'exams.title', 'exams.description', 'exams.pass_percentage', 'sub_categories.name', 'exam_types.name') 
            ->havingRaw('COUNT(questions.id) > 0')
            ->first();
            
            // Check if exam data is available
            if (!$examData) {
                return response()->json(['status' => false, 'message' => 'Exam not found'], 404);
            }

            // Format response to match the structure needed by frontend
            return response()->json([
                'status' => true,
                'data' => [
                    'title' => $examData->title,
                    'examType' => $examData->exam_type_name,
                    'syllabus' => $examData->sub_category_name,
                    'totalQuestions' => $examData->total_questions,
                    'duration' => $this->formatTime($examData->total_time),  // Call formatTime from within the class
                    'marks' => $examData->total_marks,
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
                    ->groupBy('quiz_types.slug','quizzes.slug', 'quizzes.id', 'quizzes.title') // Group by type and quiz details
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

                    // Add quiz details to the corresponding type slug
                    $formattedQuizData[$quiz->slug][] = [
                        'title' => $quiz->title,
                        'slug' => $quiz->quizSlug,
                        'questions' => $quiz->total_questions ?? 0,
                        'time' => $formattedTime, // Use the formatted time
                        'marks' => $quiz->total_marks ?? 0,
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

    public function quizDetail(Request $request, $slug)
    {
        try {
            // Validate incoming request data
            $request->validate([
                'category' => 'required|integer',
            ]);

            // Fetch quiz details based on the category and slug
            $quizData = Quizze::select(
                'quiz_types.slug as exam_type_slug',
                'quizzes.title',
                'quizzes.description',
                'quizzes.pass_percentage',
                'sub_categories.name as sub_category_name',
                'quiz_types.name as exam_type_name',
                DB::raw('COUNT(questions.id) as total_questions'),
                DB::raw('SUM(CAST(questions.default_marks AS DECIMAL)) as total_marks'),
                DB::raw('SUM(COALESCE(questions.watch_time, 0)) as total_time')
            )
            ->leftJoin('quiz_types', 'quizzes.quiz_type_id', '=', 'quiz_types.id')
            ->leftJoin('sub_categories', 'quizzes.subcategory_id', '=', 'sub_categories.id')
            ->leftJoin('exam_questions', 'quizzes.id', '=', 'exam_questions.exam_id')
            ->leftJoin('questions', 'exam_questions.question_id', '=', 'questions.id')
            ->where('quizzes.subcategory_id', $request->category)
            ->where('quizzes.slug', $slug)
            ->where('quizzes.status', 1)
            ->groupBy(
                'quiz_types.slug',
                'quizzes.id',
                'quizzes.title',
                'quizzes.description',
                'quizzes.pass_percentage',
                'sub_categories.name',
                'quiz_types.name'
            )
            ->havingRaw('COUNT(questions.id) > 0')
            ->first();

            // Check if exam data is available
            if (!$quizData) {
                return response()->json(['status' => false, 'message' => 'Quiz not found'], 404);
            }

            // Format response to match the structure needed by frontend
            return response()->json([
                'status' => true,
                'data' => [
                    'title' => $quizData->title,
                    'quizType' => $quizData->exam_type_name,
                    'syllabus' => $quizData->sub_category_name,
                    'totalQuestions' => $quizData->total_questions,
                    'duration' => $this->formatTime($quizData->total_time), // Call formatTime from within the class
                    'marks' => $quizData->total_marks,
                    'description' => $quizData->description,
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
    
            // Fetch practice sets and their related data
            $practiceSets = PracticeSet::select(
                    'practice_sets.title',
                    'practice_sets.subCategory_id',
                    DB::raw('COUNT(questions.id) as total_questions'), // Count total questions from the questions table
                    DB::raw('SUM(CAST(questions.default_marks AS DECIMAL)) as total_marks'), // Sum total marks from the questions table
                    DB::raw('SUM(COALESCE(questions.watch_time, 0)) as total_time') // Sum total watch time from the questions table
                )
                ->leftJoin('practice_set_questions', 'practice_sets.id', '=', 'practice_set_questions.practice_set_id') // Join with practice_set_questions
                ->leftJoin('questions', 'practice_set_questions.question_id', '=', 'questions.id') // Join with questions to get actual question data
                ->where('practice_sets.subCategory_id', $request->category)
                ->where('practice_sets.status', 1)
                ->groupBy('practice_sets.id', 'practice_sets.title', 'practice_sets.subCategory_id') // Group by practice set details
                ->havingRaw('COUNT(questions.id) > 0') // Filter to include only practice sets with questions
                ->get();
    
            // Check if practice sets are found
            if ($practiceSets->isEmpty()) {
                return response()->json(['status' => false, 'message' => 'No practice sets found for this category.'], 404);
            }
    
            return response()->json(['status' => true, 'data' => $practiceSets], 200);
            
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
                    'sub_categories.name'
                )
                ->havingRaw('COUNT(questions.id) > 0')
                ->first();
    
            // Check if practice set data is available
            if (!$practiceSetData) {
                return response()->json(['status' => false, 'message' => 'Practice set not found'], 404);
            }
    
            // Format response to match the structure needed by frontend
            return response()->json([
                'status' => true,
                'data' => [
                    'title' => $practiceSetData->title,
                    'syllabus' => $practiceSetData->sub_category_name,
                    'totalQuestions' => $practiceSetData->total_questions,
                    'duration' => $this->formatTime($practiceSetData->total_time), // Call formatTime from within the class
                    'marks' => $practiceSetData->total_marks,
                    'description' => $practiceSetData->description,
                ],
            ], 200);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'error' => 'Internal Server Error: ' . $th->getMessage()], 500);
        }
    }
    

    
    

    
}
