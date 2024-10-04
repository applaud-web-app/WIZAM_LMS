<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SubCategory;
use App\Models\ExamType;
use App\Models\User;
use App\Models\Exam;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{

    public function profile(Request $request)
    {
        // Get the authenticated user
        $user = Auth::user();

        // Check if the user is authenticated
        if ($user) {
            return response()->json([
                'status' => true,
                'user' => $user,
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'User not authenticated',
            ], 401);
        }
    }


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

    // public function exams(Request $request){
    //     try {
    //         $examType = ExamType::select('id')->where('slug',$request->slug)->where('status', 1)->first();
    //         if($examType){
    //             $exam = Exam::select('type.slug')->with('type')->where('exam_type_id', $examType->id)->where('subcategory_id',$request->category)->groupBy('type.slug')->where('status', 1)->get();



    //             $examData = Exam::select(
    //                 'types.slug', // Fetch type slug
    //                 'exams.title', // Fetch exam title
    //                 DB::raw('COUNT(questions.id) as total_questions'), // Count total questions for each exam
    //                 DB::raw('SUM(CAST(questions.default_marks AS DECIMAL)) as total_marks'), // Sum total marks for each exam
    //                 'exams.exam_duration as total_time' // Total exam duration for each exam
    //             )
    //             ->leftJoin('types', 'exams.exam_type_id', '=', 'types.id') // Join with the types table
    //             ->leftJoin('exam_questions', 'exams.id', '=', 'exam_questions.exam_id') // Join with exam_questions
    //             ->leftJoin('questions', 'exam_questions.question_id', '=', 'questions.id') // Join with questions
    //             ->where('exams.exam_type_id', $examType->id) // Filter by the provided exam type
    //             ->where('exams.subcategory_id', $request->category) // Filter by subcategory_id
    //             ->where('exams.status', 1) // Filter by status
    //             ->groupBy('types.slug', 'exams.id', 'exams.title', 'exams.exam_duration') // Group by type and exam details
    //             ->get();

    //             $formattedExamData = [];

    //                 foreach ($examData as $exam) {
    //                     // Use type slug as the key, and if not already set, initialize it as an array
    //                     if (!isset($formattedExamData[$exam->slug])) {
    //                         $formattedExamData[$exam->slug] = [];
    //                     }

    //                     // Push exam details into the corresponding type's array
    //                     $formattedExamData[$exam->slug][] = [
    //                         'title' => $exam->title,
    //                         'questions' => $exam->total_questions,
    //                         'time' => $exam->total_time . ' hrs', // Append 'hrs' to the time
    //                         'marks' => $exam->total_marks,
    //                     ];
    //                 }

    //                 // Return the final transformed data
    //                 return $formattedExamData;            
            
            


    //             return response()->json(['status'=> true,'data' => $exam], 201);
    //         }
    //         return response()->json(['status'=> false,'error' => "Exam Not Found"], 404);
    //     } catch (\Throwable $th) {
    //         return response()->json(['status'=> false,'error' => $th->getMessage()], 500);
    //     }
    // }


    public function exams(Request $request)
    {
        try {
            // Fetch exam type by slug and status
            $examType = ExamType::select('id')->where('slug', $request->slug)->where('status', 1)->first();

            if ($examType) {
                // Fetch exam data grouped by type.slug
                $examData = Exam::select(
                        'types.slug', // Fetch type slug
                        'exams.title', // Fetch exam title
                        DB::raw('COUNT(questions.id) as total_questions'), // Count total questions for each exam
                        DB::raw('SUM(CAST(questions.default_marks AS DECIMAL)) as total_marks'), // Sum total marks for each exam
                        'exams.exam_duration as total_time' // Total exam duration for each exam
                    )
                    ->leftJoin('types', 'exams.exam_type_id', '=', 'types.id') // Join with the types table
                    ->leftJoin('exam_questions', 'exams.id', '=', 'exam_questions.exam_id') // Join with exam_questions
                    ->leftJoin('questions', 'exam_questions.question_id', '=', 'questions.id') // Join with questions
                    ->where('exams.exam_type_id', $examType->id) // Filter by the provided exam type
                    ->where('exams.subcategory_id', $request->category) // Filter by subcategory_id
                    ->where('exams.status', 1) // Filter by exam status
                    ->groupBy('types.slug', 'exams.id', 'exams.title', 'exams.exam_duration') // Group by type and exam details
                    ->get();

                // Initialize array to store formatted exam data
                $formattedExamData = [];

                foreach ($examData as $exam) {
                    // Group exams by slug (exam type)
                    if (!isset($formattedExamData[$exam->slug])) {
                        $formattedExamData[$exam->slug] = [];
                    }

                    // Add exam details to the corresponding type slug
                    $formattedExamData[$exam->slug][] = [
                        'title' => $exam->title,
                        'questions' => $exam->total_questions,
                        'time' => $exam->total_time . ' hrs', // Append 'hrs' to time
                        'marks' => $exam->total_marks,
                    ];
                }

                // Return the formatted data as JSON
                return response()->json(['status' => true, 'data' => $formattedExamData], 201);
            }

            // Return error if exam type not found
            return response()->json(['status' => false, 'error' => "Exam Not Found"], 404);
            
        } catch (\Throwable $th) {
            // Return error response with exception message
            return response()->json(['status' => false, 'error' => $th->getMessage()], 500);
        }
    }

}
