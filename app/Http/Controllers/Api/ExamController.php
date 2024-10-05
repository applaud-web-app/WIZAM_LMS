<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StudentExamResult;

class ExamController extends Controller
{
    public function playExam(Request $request, $slug){
        try {
            // Validate incoming request data
            $request->validate([
                'category' => 'required|integer',
            ]);

            $exam = Exam::where('slug',$slug)->where('status',1)->first();
            if($exam){
                $examResult = StudentExamResult::where('exam_id',);
            }


            // Fetch exam details based on the category and slug
            // $examData = Exam::select(
            //     'exam_types.slug as exam_type_slug', 
            //     'exams.title',
            //     'exams.description',
            //     'exams.pass_percentage',
            //     'sub_categories.name as sub_category_name',
            //     'exam_types.name as exam_type_name',
            //     DB::raw('COUNT(questions.id) as total_questions'),
            //     DB::raw('SUM(CAST(questions.default_marks AS DECIMAL)) as total_marks'), 
            //     DB::raw('SUM(COALESCE(questions.watch_time, 0)) as total_time')
            // )
            // ->leftJoin('exam_types', 'exams.exam_type_id', '=', 'exam_types.id') 
            // ->leftJoin('sub_categories', 'exams.subcategory_id', '=', 'sub_categories.id') 
            // ->leftJoin('exam_questions', 'exams.id', '=', 'exam_questions.exam_id') 
            // ->leftJoin('questions', 'exam_questions.question_id', '=', 'questions.id') 
            // ->where('exams.subcategory_id', $request->category) 
            // ->where('exams.slug', $slug) 
            // ->where('exams.status', 1)
            // ->groupBy('exam_types.slug', 'exams.id', 'exams.title', 'exams.description', 'exams.pass_percentage', 'sub_categories.name', 'exam_types.name') 
            // ->havingRaw('COUNT(questions.id) > 0')
            // ->first();
            
            // // Check if exam data is available
            // if (!$examData) {
            //     return response()->json(['status' => false, 'message' => 'Exam not found'], 404);
            // }

            // // Format response to match the structure needed by frontend
            // return response()->json([
            //     'status' => true,
            //     'data' => [
            //         'title' => $examData->title,
            //         'examType' => $examData->exam_type_name,
            //         'syllabus' => $examData->sub_category_name,
            //         'totalQuestions' => $examData->total_questions,
            //         'duration' => $this->formatTime($examData->total_time),  // Call formatTime from within the class
            //         'marks' => $examData->total_marks,
            //         'description' => $examData->description
            //     ]
            // ], 200);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'error' => 'Internal Server Error : '.$th->getMessage()], 500);
        }
    }
}
