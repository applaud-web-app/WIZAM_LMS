<?php
namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;
use App\Models\QuestionType;
use App\Models\Comprehensions;
use App\Models\Lesson;
use App\Models\Skill;
use App\Models\Topic;
use App\Models\Video;
use App\Models\Question;
use App\Models\Solution;

class QuestionBankController extends Controller
{
    public function questionTypes(){
        $questionType = QuestionType::get();
        return view('questionBank.question-type',compact('questionType'));
    }

    public function questions(Request $request){
        if ($request->ajax()) {
            $sections = Question::with('skill','topic')->whereIn('status',[0,1]);
            return DataTables::of($sections)
                ->addIndexColumn()
                ->addColumn('action', function ($section) {
                    $parms = "id=".$section->id;
                    $editUrl = route('update-question-details',['id'=>$section->id]);
                    $deleteUrl = encrypturl(route('delete-question'),$parms);

                    return '
                        <a href="'.$editUrl.'" class="editItem cursor-pointer edit-task-title uil uil-edit-alt hover:text-info"></a>
                        <button type="button" data-url="'.$deleteUrl.'" class="deleteItem cursor-pointer remove-task-wrapper uil uil-trash-alt hover:text-danger" data-te-toggle="modal" data-te-target="#exampleModal" data-te-ripple-init data-te-ripple-color="light"></button>';
                })
                ->addColumn('question', function($row) {
                    if($row->type == "EMQ"){
                        $allQuestion = json_decode($row->question,true);
                        return $allQuestion[0];
                    }else{
                        return $row->question;
                    }
                })
                ->addColumn('type', function($row) {
                    return $row->type;
                })
                ->addColumn('section', function($row) {
                    return date('d/m/Y', strtotime($row->created_at));
                })
                ->addColumn('skill', function($row) {
                    if(isset($row->skill)){
                        return $row->skill->name;
                    }
                    return "----";
                })
                ->addColumn('topic', function($row) {
                    if(isset($row->topic)){
                        return $row->topic->name;
                    }
                    return "----";
                })
                ->addColumn('status', function($row) {
                    // Determine the status color and text based on `is_active`
                    $statusColor = $row->status == 1 ? 'success' : 'danger';
                    $statusText = $row->status == 1 ? 'Active' : 'Inactive';
                    // Create the status badge HTML
                    return $status = "<span class='bg-{$statusColor}/10 capitalize font-medium inline-flex items-center justify-center min-h-[24px] px-3 rounded-[15px] text-{$statusColor} text-xs'>{$statusText}</span>";
                })
                ->rawColumns(['status','created_at','action','question','section','topic','skill'])
                ->make(true);
        }
        $questionType = QuestionType::where('status',1)->get();
        return view('questionBank.view-question',compact('questionType'));
    }

    // 

    public function createQuestion(Request $request)
    {
        // Check if the 'type' parameter is present in the request
        if ($request->has('type')) {
            // Retrieve skill and topic data
            $skill = Skill::where('status', 1)->get();
    
            // Determine the view to return based on the 'type' parameter
            switch ($request->type) {
                case "MSA":
                    return view('questionBank.questionType.msa.detail', compact('skill'));
    
                case "MMA":
                    return view('questionBank.questionType.mma.detail', compact('skill'));
    
                case "TOF":
                    return view('questionBank.questionType.tof.detail', compact('skill'));
    
                case "SAQ":
                    return view('questionBank.questionType.saq.detail', compact('skill'));
    
                case "MTF":
                    return view('questionBank.questionType.mtf.detail', compact('skill'));
    
                case "ORD":
                    return view('questionBank.questionType.ord.detail', compact('skill'));
    
                case "FIB":
                    return view('questionBank.questionType.fib.detail', compact('skill'));
    
                case "FIBWO":
                    return view('questionBank.questionType.fibwo.detail', compact('skill'));
    
                case "EMQ":
                    return view('questionBank.questionType.emq.detail', compact('skill'));
    
                default:
                    // Handle unknown question types
                    return redirect()->route('view-question')->with('error', 'Invalid question type selected');
            }
        }
    
        // Handle the case where 'type' parameter is missing
        return redirect()->route('view-question')->with('error', 'Please select a question type');
    }

    public function saveMsaDetails(Request $request)
    {
        $request->validate([
            'skill' => 'required',
            'question' => 'required',
            'option' => 'required',
            'correctOption' => 'required'
        ]);
    
        try {
            $question = Question::create([
                'question' => $request->question,
                'options' => json_encode($request->option),
                'answer' => $request->correctOption,
                'type' => $request->type,
                'skill_id' => $request->skill,
                'status' => 1,
            ]);
    
            return redirect()->route('update-question-setting', ['id' => $question->id])->with('success', 'Details Added Successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }  

    public function saveMmaDetails(Request $request){
        $request->validate([
            'skill' => 'required',
            'question' => 'required',
            'option' => 'required',
            'correctOption' => 'required'
        ]);
    
        try {
            $question = Question::create([
                'question' => $request->question,
                'options' => json_encode($request->option),
                'answer' => json_encode($request->correctOption),
                'type' => "MMA",
                'skill_id' => $request->skill,
                'status' => 1,
            ]);
    
            return redirect()->route('update-question-setting', ['id' => $question->id])->with('success', 'Details Added Successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    // ORDER & SEQUENCE
    public function saveOrdDetails(Request $request){
        $request->validate([
            'skill' => 'required',
            'question' => 'required',
            'option' => 'required',
        ]);

        try {
            $keys = array_keys($request->option);
            $question = Question::create([
                'question' => $request->question,
                'options' => json_encode($request->option),
                'answer' => json_encode($keys),
                'type' => "ORD",
                'skill_id' => $request->skill,
                'status' => 1,
            ]);
            return redirect()->route('update-question-setting', ['id' => $question->id])->with('success', 'Details Added Successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function updateOrdDetails(Request $request, $id){
        $request->validate([
            'skill' => 'required',
            'question' => 'required',
            'option' => 'required',
        ]);

        try {
            $keys = array_keys($request->option);
            $question = Question::where('id',$id)->first();
            $question->question = $request->question;
            $question->options = json_encode($request->option);
            $question->answer = json_encode($keys);
            $question->type = "ORD";
            $question->skill_id = $request->skill;
            $question->status = 1;
            $question->save();
            
            return redirect()->route('update-question-setting', ['id' => $question->id])->with('success', 'Details Added Successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }
    // END ORDER & SEQUENCE

    // FILL IN THE BLANK
    public function saveFibDetails(Request $request){
        $request->validate([
            'skill' => 'required',
            'question' => 'required',
        ]);

        try {
            $question = $request->question; // Fetch the question with placeholders
            preg_match_all('/##(.*?)##/', $question, $matches);
            $answers = array_map('trim', $matches[1]);

            $question = Question::create([
                'question' => $request->question,
                'options' => json_encode([]),
                'answer' => json_encode($answers),
                'type' => "FIB",
                'skill_id' => $request->skill,
                'status' => 1,
            ]);
            return redirect()->route('update-question-setting', ['id' => $question->id])->with('success', 'Details Added Successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function updateFibDetails(Request $request, $id){
        $request->validate([
            'skill' => 'required',
            'question' => 'required',
        ]);

        try {
            $question = $request->question; // Fetch the question with placeholders
            preg_match_all('/##(.*?)##/', $question, $matches);
            $answers = array_map('trim', $matches[1]);

            $question = Question::where('id',$id)->first();
            if($question){
                $question->question = $request->question;
                $question->options = json_encode([]);
                $question->answer = json_encode($answers);
                $question->skill_id = $request->skill;
                $question->save();
                return redirect()->route('update-question-setting', ['id' => $question->id])->with('success', 'Details Updated Successfully');
            }
            return redirect()->back()->with('error', 'Something went wrong');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    // END FILL IN THE BLANK

    // EXTENDED MUTIPLE QUESTION
    public function saveEmqDetails(Request $request){
        $request->validate([
            'skill' => 'required',
            'question' => 'required',
            'option' => 'required',
            'answer' => 'required'
        ]);

        try {
            $question = Question::create([
                'question' => json_encode($request->question),
                'options' => json_encode($request->option),
                'answer' => json_encode($request->answer),
                'type' => "EMQ",
                'skill_id' => $request->skill,
                'status' => 1,
            ]);
            return redirect()->route('update-question-setting', ['id' => $question->id])->with('success', 'Details Added Successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function updateEmqDetails(Request $request, $id){
        $request->validate([
            'skill' => 'required',
            'question' => 'required',
            'option' => 'required',
            'answer' => 'required'
        ]);

        try {
            $question = Question::where('id',$id)->first();
            if($question){
                $question->question = json_encode($request->question);
                $question->options = json_encode($request->option);
                $question->answer = json_encode($request->answer);
                $question->skill_id = $request->skill;
                $question->save();
                return redirect()->route('update-question-setting', ['id' => $question->id])->with('success', 'Details Updated Successfully');
            }
            return redirect()->back()->with('error', 'Something went wrong');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    // END EXTENDED MUTIPLE QUESTION


    // TRUR AND FALSE
    public function saveTofDetails(Request $request){
        $request->validate([
            'skill' => 'required',
            'question' => 'required',
            'option' => 'required',
            'answer' => 'required'
        ]);

        try {
            $question = Question::create([
                'question' => $request->question,
                'options' => json_encode($request->option),
                'answer' => $request->answer,
                'type' => "TOF",
                'skill_id' => $request->skill,
                'status' => 1,
            ]);
            return redirect()->route('update-question-setting', ['id' => $question->id])->with('success', 'Details Added Successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function updateTofDetails(Request $request, $id){
        $request->validate([
            'skill' => 'required',
            'question' => 'required',
            'option' => 'required',
            'answer' => 'required'
        ]);

        try {
            $question = Question::where('id',$id)->first();
            if($question){
                $question->question = $request->question;
                $question->options = json_encode($request->option);
                $question->answer = $request->answer;
                $question->skill_id = $request->skill;
                $question->save();
                return redirect()->route('update-question-setting', ['id' => $question->id])->with('success', 'Details Updated Successfully');
            }
            return redirect()->back()->with('error', 'Something went wrong');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }
    // END TRUE OR FALSE

    // SHORT ANSWER TYPE QUESTION
    public function saveSoqDetails(Request $request){
        $request->validate([
            'skill' => 'required',
            'question' => 'required',
            'option' => 'required',
            'answer' => 'required'
        ]);

        try {
            $question = Question::create([
                'question' => $request->question,
                'options' => json_encode($request->option),
                'answer' => $request->answer,
                'type' => "SAQ",
                'skill_id' => $request->skill,
                'status' => 1,
            ]);
            return redirect()->route('update-question-setting', ['id' => $question->id])->with('success', 'Details Added Successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function updateSoqDetails(Request $request , $id){
        $request->validate([
            'skill' => 'required',
            'question' => 'required',
            'option' => 'required',
            'answer' => 'required'
        ]);

        try {
            $question = Question::where('id',$id)->first();
            if($question){
              $question->question = $request->question;
              $question->options = json_encode($request->option);
              $question->answer = $request->answer;
              $question->skill_id = $request->skill;
              $question->save();

              return redirect()->route('update-question-setting', ['id' => $question->id])->with('success', 'Details Added Successfully');
            }
            return redirect()->back()->with('error', 'Something went wrong');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }
    // END SHORT ANSWER TYPE QUESTION

    // MATCH THE FOLLOWING
    public function saveMtfDetails(Request $request){
        $request->validate([
            'skill' => 'required',
            'question' => 'required',
            'option' => 'required',
            'answer' => 'required'
        ]);

        try {
            $question = Question::create([
                'question' => $request->question,
                'options' => json_encode($request->option),
                'answer' => json_encode($request->answer),
                'type' => "MTF",
                'skill_id' => $request->skill,
                'status' => 1,
            ]);
            return redirect()->route('update-question-setting', ['id' => $question->id])->with('success', 'Details Added Successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function updateMtfDetails(Request $request,$id){
        $request->validate([
            'skill' => 'required',
            'question' => 'required',
            'option' => 'required',
            'answer' => 'required'
        ]);

        try {
            $question = Question::where('id',$id)->first();
            if($question){
                $question->question = $request->question;
                $question->options = json_encode($request->option);
                $question->answer = json_encode($request->answer);
                $question->skill_id = $request->skill;
                $question->save();
                return redirect()->route('update-question-setting', ['id' => $question->id])->with('success', 'Details Updated Successfully');
            }
            return redirect()->back()->with('error', 'Something went wrong');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }
    // END MATCH THE FOLLOWING

    public function updateMmaDetails(Request $request, $id){
        $request->validate([
            'skill' => 'required',
            'question' => 'required',
            'option' => 'required',
            'correctOption' => 'required'
        ]);

        $question = Question::where('id',$id)->first();
        if($question){
            $question->question = $request->question;
            $question->options = json_encode($request->option);
            $question->answer = json_encode($request->correctOption);
            $question->skill_id = $request->skill;
            $question->save();

            return redirect()->route('update-question-setting', ['id' => $question->id])->with('success', 'Details Added Successfully');
        }
        return redirect()->back()->with('error', 'Something went wrong');
    }

    public function updateQuestionDetails($id){
        $question = Question::where('id',$id)->first();
        if($question){
            $skill = Skill::where('status', 1)->get();
             // Determine the view to return based on the 'type' parameter
             switch ($question->type) {
                case "MSA":
                    return view('questionBank.questionType.msa.detail',compact('skill','question'));
                case "MMA":
                    return view('questionBank.questionType.mma.update-details',compact('skill','question'));
                case "TOF":
                    return view('questionBank.questionType.tof.update-details', compact('skill','question'));
                case "SAQ":
                    return view('questionBank.questionType.saq.update-details', compact('skill','question'));
                case "MTF":
                    return view('questionBank.questionType.mtf.update-details', compact('skill','question'));
                case "ORD":
                    return view('questionBank.questionType.ord.update-details', compact('skill','question'));
    
                case "FIB":
                    return view('questionBank.questionType.fib.update-details', compact('skill','question'));
    
                case "FIBWO":
                    return view('questionBank.questionType.fibwo.update-details', compact('skill','question'));
    
                case "EMQ":
                    return view('questionBank.questionType.emq.update-details', compact('skill','question'));
    
                default:
                    // Handle unknown question types
                    return redirect()->route('view-question')->with('error', 'Invalid question type selected');
            }
        } 
        return redirect()->back()->with('error', 'Something went wrong!');
    }  

    public function updateQuestionSetting($id){
        // dd($request->all(),$id);
        $question = Question::where('id',$id)->first();
        if($question){
            $skill = Skill::where('status', 1)->get();
            $topic = TOpic::where('status', 1)->get();
            return view('questionBank.questionType.msa.setting',compact('skill','question','topic'));
        } 
        return redirect()->back()->with('error', 'Something went wrong!');
    }

    public function deleteQuestion(Request $request){
        $request->validate([
            'eq'=>'required'
        ]);

        $data = decrypturl($request->eq);
        $compiId = $data['id'];
        $user = Question::where('id',$compiId)->first();
        if($user){
            $user->status = 2; // Delete
            $user->save();
            return redirect()->back()->with('success','Question Removed Successfully');
        }
        return redirect()->back()->with('error','Something Went Wrong');
    }

    public function saveQuestionDetails(Request $request, $id){
        $request->validate([
            'skill' => 'required',
            'question' => 'required',
            'option' => 'required',
            'correctOption' => 'required'
        ]);

        try {
            $question = Question::where('id',$id)->first();
            $question->question = $request->question;
            $question->options = json_encode($request->option);
            $question->answer = $request->correctOption;
            $question->skill_id = $request->skill;
            $question->save();
    
            return redirect()->route('update-question-setting', ['id' => $question->id])->with('success', 'Details Added Successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
       
    }

    public function saveQuestionSetting(Request $request,$id){
        $request->validate([
            'skill'=>'required',
            'topic'=>'required',
            'difficulty'=>'required',
            'default_grade'=>'required',
            'solve_time'=>'required',
            'status'=>'required'
        ]);

        try {
            $tag = [];
            if($request->has('tags') && isset($request->tags)){
                $tags = json_decode($request->tags, true);
                $tag = array_column($tags, 'value');
            }

            $question = Question::where('id',$id)->first();
            if($question){
                $question->skill_id = $request->skill;
                $question->topic_id = $request->topic;
                $question->tags = $tag;
                $question->level = $request->difficulty;
                $question->default_marks = $request->default_grade;
                $question->watch_time = $request->solve_time;
                $question->status = $request->status;
                $question->save();
            }
            return redirect()->route('update-question-solution', ['id' => $question->id])->with('success', 'Question Setting Updated Successfully');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Something went wrong: '.$th->getMessage());
        }
    }

    public function updateQuestionSolution($id){
        $question = Question::where('id',$id)->first();
        if($question){
            $solution = Solution::where('question_id',$id)->first();
            return view('questionBank.questionType.msa.solution',compact('solution'));
        }

        return redirect()->back()->with('error', 'Something went wrong!');
    }

    public function saveQuestionSolution(Request $request, $id)
    {
        // dd($request->all());
        try {
            $type = null;
            $source = null;
            if($request->video_solution == 1){
                $type = $request->video_type;
                if($type == "Vimeo"){
                    $source = $request->vimeo_id;
                }else if($type == "YouTube"){
                    $source = $request->youtube_id;
                }else if($type == "MP4"){
                    $source = $request->video_url;
                }
            }

            $solution = Solution::updateOrCreate(
                ['question_id' => $id], // Search for the solution by question ID
                [
                    'solution' => $request->solution,
                    'video_enable' => $request->video_solution,
                    'video_type' => $type,
                    'video_source' => $source,
                    'hint' => $request->hint
                ]
            );

            return redirect()->route('update-question-attachment', ['id' => $solution->id])->with('success', 'Question Solution Updated Successfully');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Something went wrong: '.$th->getMessage());
        }
    }

    public function updateQuestionAttachment($id){
        $solution = Solution::where('question_id',$id)->first();
        if($solution){
            $comprehension = Comprehensions::where('status',1)->get();
            return view('questionBank.questionType.msa.attachment',compact('solution','comprehension'));
        } 
        return redirect()->back()->with('error', 'Please submit solution to continue!');
    }

    // public function saveQuestionAttachment(Request $request, $id){
       
    //     $solution = Solution::where('question_id',$id)->first();
    //     if($solution){
    //         $solution->attachment_type = null;
    //         $solution->attachment_source = null;
    //         if($request->question_attachment == "yes"){
    //             if($request->attachment_type == "comprehension"){
    //                 $video_type = null;
    //                 $source = $request->comprehension_type;
    //             }else{
    //                 $video_type = $request->video_type;
    //                 $source = $request->source_val;
    //             }
    //             $solution->attachment_type = $request->attachment_type;
    //             $solution->attachment_video_type = $video_type;
    //             $solution->attachment_source = $source;
    //         }
    //         $solution->save();
    //         return redirect()->route('view-question')->with('success', 'Question Updated Successfully');
    //     }
    //     return redirect()->back()->with('error', 'Something went wrong!');
    // }

    public function saveQuestionAttachment(Request $request, $id)
    {

        // dd($request->all());
        $validated = $request->validate([
            'question_attachment' => 'required|string',
            'attachment_type' => 'nullable|string',
            'comprehension_type' => 'nullable|string',
            'video_type' => 'nullable|string',
            'source_val' => 'nullable|string',
        ]);
    
        try {
            // Find the solution by question_id
            $solution = Solution::where('question_id', $id)->first();
    
            if ($solution) {
                // Reset attachment fields
                $solution->attachment_type = null;
                $solution->attachment_source = null;
                $solution->attachment_video_type = null;
    
                // If attachment is set to 'yes', process the attachment type
                if ($request->question_attachment === "yes") {
                    if ($request->attachment_type === "comprehension") {
                        // For comprehension type, no video_type is needed
                        $source = $request->comprehension_type;
                        $video_type = null;
                    } else {
                        // For other types (e.g., video), get video_type and source
                        $video_type = $request->video_type;
                        $resarch = "source_".$video_type;
                        $source = $request->$resarch;
                    }
    
                    // Set attachment fields in the solution
                    $solution->attachment_type = $request->attachment_type;
                    $solution->attachment_video_type = $video_type;
                    $solution->attachment_source = $source;
                }
    
                // Save the solution
                $solution->save();
    
                // Redirect with success message
                return redirect()->route('view-question')->with('success', 'Question Updated Successfully');
            } else {
                // Handle case where no solution is found
                return redirect()->back()->with('error', 'Solution not found for the given question!');
            }
        } catch (\Exception $e) {
            // Handle exceptions and log the error if necessary
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }
    
    // public function createMsaSetting(){
    //     if (session()->has('quizData')) {
    //         // Retrieve skill and topic data
    //         $skill = Skill::where('status', 1)->get();
    //         $storedQuizData = session('quizData');
    //         $topic = TOpic::where('status', 1)->get();
    //         return view('questionBank.questionType.msa.setting',compact('skill','storedQuizData','topic'));
    //     }
    //     return redirect()->route('view-question')->with('error','Something Went Wrong');
    // }

    public function saveMsaSetting(Request $request){
        $request->validate([
            'skill'=>'required',
            'topic'=>'required',
            'difficulty'=>'required',
            'default_grade'=>'required',
            'solve_time'=>'required',
            'status'=>'required'
        ]);

        $tag = [];
        if($request->has('tags') && isset($request->tags)){
            $tags = json_decode($request->tags, true);
            $tag = array_column($tags, 'value');
        }

        // Define the data to be stored in the session
        $settingData = [
            'skill' => $request->skill,
            'topic' => $request->topic,
            'tags' => json_encode($tag),
            'difficulty' => $request->difficulty,
            'default_grade' => $request->default_grade,
            'solve_time' => $request->solve_time,
            'status' => $request->status
        ];

        // Store in session
        session(['settingData' => $settingData]);

        // Optional: To check if the session contains the quiz data
        if (session()->has('settingData')) {
            $storedsettingData = session('settingData');
            return redirect()->route('create-msa-solution')->with('success', 'Setting Added Successfully');
        }

    }

    public function createMsaSolution(){
        if (session()->has('quizData') && session()->has('settingData')) {
            return view('questionBank.questionType.msa.solution');
        }
        return redirect()->route('view-question')->with('error','Something Went Wrong');
    }

    public function saveMsaSolution(Request $request){
        $type = null;
        $source = null;
        if($request->filled('youtube_id')){
            $type = "YouTube";
            $source = $request->youtube_id;
        }else if($request->filled('vimeo_id')){
            $type = "Vimeo";
            $source = $request->vimeo_id;
        }else if($request->filled('video_url')){
            $type = "MP4";
            $source = $request->video_url;
        }

        // Define the data to be stored in the session
        $solutionData = [
            "solution" => $request->solution,
            "video_solution" => $request->video_solution,
            "source" => $source,
            "type" => $type,
            "hint" => $request->hint
        ];

        // Store in session
        session(['solutionData' => $solutionData]);

        // Optional: To check if the session contains the quiz data
        if (session()->has('solutionData')) {
            $storedsolutionData = session('solutionData');
            return redirect()->route('create-msa-attachment')->with('success', 'Solution Added Successfully');
        }

    }

    public function createMsaAttachment(){
        if (session()->has('quizData') && session()->has('settingData') && session()->has('solutionData')) {
            $comprehension = Comprehensions::where('status',1)->get();
            return view('questionBank.questionType.msa.attachment',compact('comprehension'));
        }
        return redirect()->route('view-question')->with('error','Something Went Wrong');
    }

    public function saveMsaAttachment(Request $request){
        dd($request->all());
    }

    // COMPREHENSION
    public function viewComprehension(Request $request){
        if ($request->ajax()) {
            $sections = Comprehensions::whereIn('status',[0,1])->select(['id', 'title', 'created_at','description','status']);

            return DataTables::of($sections)
                ->addIndexColumn()
                ->addColumn('action', function ($section) {
                    $parms = "id=".$section->id;
                    $editUrl = encrypturl(route('edit-comprehension'),$parms);
                    $deleteUrl = encrypturl(route('delete-comprehension'),$parms);

                    return '
                        <button type="button" data-url="'.$editUrl.'" data-title="'.$section->title.'" data-description="'.$section->description.'" data-status="'.$section->status.'" class="editItem cursor-pointer edit-task-title uil uil-edit-alt hover:text-info" data-te-toggle="modal" data-te-target="#editModal" data-te-ripple-init data-te-ripple-color="light"></button>
                        <button type="button" data-url="'.$deleteUrl.'" class="deleteItem cursor-pointer remove-task-wrapper uil uil-trash-alt hover:text-danger"  data-te-toggle="modal" data-te-target="#exampleModal" data-te-ripple-init data-te-ripple-color="light"></button>';
                })
                ->addColumn('created_at', function($row) {
                    return date('d/m/Y', strtotime($row->created_at));
                })
                ->addColumn('status', function($row) {
                    // Determine the status color and text based on `is_active`
                    $statusColor = $row->status == 1 ? 'success' : 'danger';
                    $statusText = $row->status == 1 ? 'Active' : 'Inactive';
                    // Create the status badge HTML
                    return $status = "<span class='bg-{$statusColor}/10 capitalize font-medium inline-flex items-center justify-center min-h-[24px] px-3 rounded-[15px] text-{$statusColor} text-xs'>{$statusText}</span>";
                })
                ->rawColumns(['status','created_at','action'])
                ->make(true);
        }
        return view('questionBank.comprehensions');
    }

    public function addComprehension(Request $request){
        // Validation rules
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'status' => 'required|string|in:1,0',
        ]);

        // Create section
        Comprehensions::create([
            'title' => $request->title,
            'description' => $request->description, // Nullable
            'status' => $request->status,
        ]);

        // Redirect with success message
        return redirect()->route('view-comprehension')->with('success', 'Comprehension created successfully.');
    }

    public function editComprehension(Request $request){
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'status' => 'required|string|in:1,0',
            'eq'=>'required'
        ]);

        $data = decrypturl($request->eq);
        $compiId = $data['id'];
        $user = Comprehensions::where('id',$compiId)->first();
        if($user){
            $user->title = $request->title; 
            $user->description = $request->description; 
            $user->status = $request->status;
            $user->save();
            return redirect()->back()->with('success','Comprehension Update Successfully');
        }
        return redirect()->back()->with('error','Something Went Wrong');

    }

    public function deleteComprehension(Request $request){
        $request->validate([
            'eq'=>'required'
        ]);

        $data = decrypturl($request->eq);
        $compiId = $data['id'];
        $user = Comprehensions::where('id',$compiId)->first();
        if($user){
            $user->status = 2; // Delete
            $user->save();
            return redirect()->back()->with('success','Comprehension Removed Successfully');
        }
        return redirect()->back()->with('error','Something Went Wrong');
    }

    // FOR LESSON
    public function viewLesson(Request $request){
        if ($request->ajax()) {
            $sections = Lesson::with('skill','topic')->whereIn('status',[0,1]);

            return DataTables::of($sections)
                ->addIndexColumn()
                ->addColumn('action', function ($section) {
                    $parms = "id=".$section->id;
                    $editUrl = encrypturl(route('edit-lesson'),$parms);
                    $deleteUrl = encrypturl(route('delete-lesson'),$parms);
                    return '
                        <a href="'.$editUrl.'" class="editItem cursor-pointer edit-task-title uil uil-edit-alt hover:text-info"></a>
                        <button type="button" data-url="'.$deleteUrl.'" class="deleteItem cursor-pointer remove-task-wrapper uil uil-trash-alt hover:text-danger"  data-te-toggle="modal" data-te-target="#exampleModal" data-te-ripple-init data-te-ripple-color="light"></button>';
                })
                ->addColumn('section', function($row) {
                    return date('d/m/Y', strtotime($row->created_at));
                })
                ->addColumn('skill', function($row) {
                    if($row->skill){
                        return $row->skill->name;
                    }
                    return "----";
                })
                ->addColumn('topic', function($row) {
                    if($row->topic){
                        return $row->topic->name;
                    }
                    return "----";
                })
                ->addColumn('status', function($row) {
                    // Determine the status color and text based on `is_active`
                    $statusColor = $row->status == 1 ? 'success' : 'danger';
                    $statusText = $row->status == 1 ? 'Active' : 'Inactive';
                    // Create the status badge HTML
                    $status = "<span class='bg-{$statusColor}/10 capitalize font-medium inline-flex items-center justify-center min-h-[24px] px-3 rounded-[15px] text-{$statusColor} text-xs'>{$statusText}</span>";
                    
                    // Add the "Free" badge if applicable
                    $free = $row->is_free == 1 ? '<span class="bg-primary/10 capitalize font-medium inline-flex items-center justify-center min-h-[24px] px-3 rounded-[15px] text-primary text-xs ml-1">Free</span>' : '';

                    // Return the combined HTML
                    return "{$status} {$free}";
                })
                ->rawColumns(['status','section','skill','topic','action'])
                ->make(true);
        }
        return view('questionBank.view-lesson');
    }

    public function addLesson(){
        $skill = Skill::where('status',1)->get();
        $topic = Topic::where('status',1)->get();
        return view('questionBank.add-lesson',compact('skill','topic'));
    }

    public function storeLesson(Request $request){
        $request->validate([
            'lesson_title' => 'required|min:2',
            'description' => 'required|min:10',
            'skill' => 'required',
            'difficulty_level' => 'required',
            'read_time' => 'required|numeric|min:1',
            'paid' => 'required|in:0,1',
            'status' => 'required|in:0,1',
        ]);

        $tag = [];
        if($request->has('tags') && isset($request->tags)){
            $tags = json_decode($request->tags, true);
            $tag = array_column($tags, 'value');
        }

        // Create a new lesson record
        Lesson::create([
            'title' => $request->input('lesson_title'),
            'description' => $request->input('description'),
            'skill_id' => $request->input('skill'),
            'topic_id' => $request->input('topic'),
            'tags' => json_encode($tag),
            'level' => $request->input('difficulty_level'),
            'read_time' => $request->input('read_time'),
            'is_free' => $request->input('paid') == 1 ? 0 : 1,
            'status' => $request->input('status'),
        ]);

        // Redirect or respond
        return redirect()->route('view-lesson')->with('success', 'Lesson created successfully.');
    }

    public function editLesson(Request $request){
        $request->validate([
            'eq'=>'required'
        ]);

        $data = decrypturl($request->eq);
        $compiId = $data['id'];
        $lesson = Lesson::where('id',$compiId)->first();
        $skill = Skill::where('status',1)->get();
        $topic = Topic::where('status',1)->get();
        return view('questionBank.edit-lesson',compact('skill','topic','lesson'));
    }

    public function updateLesson(Request $request){
        $request->validate([
            'lesson_title' => 'required|min:2',
            'description' => 'required|min:10',
            'skill' => 'required',
            'difficulty_level' => 'required',
            'read_time' => 'required|numeric|min:1',
            'paid' => 'required|in:0,1',
            'status' => 'required|in:0,1',
            'eq'=>'required'
        ]);

        $tag = [];
        if($request->has('tags') && isset($request->tags)){
            $tags = json_decode($request->tags, true);
            $tag = array_column($tags, 'value');
        }

        $data = decrypturl($request->eq);
        $compiId = $data['id'];
        $lesson = Lesson::where('id',$compiId)->first();
        $lesson->title = $request->input('lesson_title');
        $lesson->description = $request->input('description');
        $lesson->skill_id = $request->input('skill');
        $lesson->topic_id = $request->input('topic');
        $lesson->tags = json_encode($tag);
        $lesson->level = $request->input('difficulty_level');
        $lesson->read_time = $request->input('read_time');
        $lesson->is_free = $request->input('paid') == 1 ? 0 : 1;
        $lesson->status = $request->input('status');
        $lesson->save();

        // Redirect or respond
        return redirect()->route('view-lesson')->with('success', 'Lesson updated successfully.');
    }

    public function deleteLesson(Request $request){
        $request->validate([
            'eq'=>'required'
        ]);

        $data = decrypturl($request->eq);
        $compiId = $data['id'];
        $user = Lesson::where('id',$compiId)->first();
        if($user){
            $user->status = 2; // Delete
            $user->save();
            return redirect()->back()->with('success','Lesson Removed Successfully');
        }
        return redirect()->back()->with('error','Something Went Wrong');
    }
    
    // VIDEO BANK
    public function viewVideo(Request $request){
        if ($request->ajax()) {
            $sections = Video::with('skill','topic')->whereIn('status',[0,1]);

            return DataTables::of($sections)
                ->addIndexColumn()
                ->addColumn('action', function ($section) {
                    $parms = "id=".$section->id;
                    $editUrl = encrypturl(route('edit-video'),$parms);
                    $deleteUrl = encrypturl(route('delete-video'),$parms);
                    return '
                        <a href="'.$editUrl.'" class="editItem cursor-pointer edit-task-title uil uil-edit-alt hover:text-info"></a>
                        <button type="button" data-url="'.$deleteUrl.'" class="deleteItem cursor-pointer remove-task-wrapper uil uil-trash-alt hover:text-danger"  data-te-toggle="modal" data-te-target="#exampleModal" data-te-ripple-init data-te-ripple-color="light"></button>';
                })
                ->addColumn('section', function($row) {
                    return date('d/m/Y', strtotime($row->created_at));
                })
                ->addColumn('skill', function($row) {
                    if($row->skill){
                        return $row->skill->name;
                    }
                    return "----";
                })
                ->addColumn('topic', function($row) {
                    if($row->topic){
                        return $row->topic->name;
                    }
                    return "----";
                })
                ->addColumn('type', function($row) {
                    return $row->type." Video";
                })
                ->addColumn('status', function($row) {
                    // Determine the status color and text based on `is_active`
                    $statusColor = $row->status == 1 ? 'success' : 'danger';
                    $statusText = $row->status == 1 ? 'Active' : 'Inactive';
                    // Create the status badge HTML
                    $status = "<span class='bg-{$statusColor}/10 capitalize font-medium inline-flex items-center justify-center min-h-[24px] px-3 rounded-[15px] text-{$statusColor} text-xs'>{$statusText}</span>";
                    
                    // Add the "Free" badge if applicable
                    $free = $row->is_free == 1 ? '<span class="bg-primary/10 capitalize font-medium inline-flex items-center justify-center min-h-[24px] px-3 rounded-[15px] text-primary text-xs ml-1">Free</span>' : '';

                    // Return the combined HTML
                    return "{$status} {$free}";
                })
                ->rawColumns(['status','section','skill','topic','action','type'])
                ->make(true);
        }
        return view('questionBank.view-video');
    }

    public function createVideo(){
        $skill = Skill::where('status',1)->get();
        $topic = Topic::where('status',1)->get();
        return view('questionBank.add-video',compact('skill','topic'));
    }

    public function storeVideo(Request $request){
        $request->validate([
            'video_title' => 'required|min:2',
            'skill' => 'required',
            'difficulty_level' => 'required',
            'watch_time' => 'required|numeric|min:1',
            'paid' => 'required|in:0,1',
            'status' => 'required|in:0,1',
        ]);

        $type = null;
        $source = null;
        if($request->filled('youtube_id')){
            $type = "YouTube";
            $source = $request->youtube_id;
        }else if($request->filled('vimeo_id')){
            $type = "Vimeo";
            $source = $request->vimeo_id;
        }else if($request->filled('video_url')){
            $type = "MP4";
            $source = $request->video_url;
        }
        
        // Check if both type and source are not empty
        if (empty($type) || empty($source)) {
            return redirect()->back()->withErrors(['error' => 'Please provide a valid video source.']);
        }
        
        $tag = [];
        if($request->has('tags') && isset($request->tags)){
            $tags = json_decode($request->tags, true);
            $tag = array_column($tags, 'value');
        }

         // Handle the image upload
        $imagePath = null;
        if ($request->hasFile('video_thumbnail')) {
            $image = $request->file('video_thumbnail');
            
            // Generate a unique filename with the current timestamp
            $imageName = 'video_thumbnail_' . time() . '.' . $image->getClientOriginalExtension();
    
            // Move the image to the 'public/blogs' directory
            $image->move(public_path('fileManager/thumbnail/'), $imageName);
    
            // Store the image path in the database (relative to the public directory)
            $imagePath = $imageName;
        }

        // Create a new lesson record
        Video::create([
            'title' => $request->input('video_title'),
            'type' => $type,
            'source' => $source,
            'description' => $request->input('description'),
            'skill_id' => $request->input('skill'),
            'thumbnail' => $imagePath,
            'topic_id' => $request->input('topic'),
            'tags' => json_encode($tag),
            'level' => $request->input('difficulty_level'),
            'watch_time' => $request->input('watch_time'),
            'is_free' => $request->input('paid') == 1 ? 0 : 1,
            'status' => $request->input('status'),
        ]);

        // Redirect or respond
        return redirect()->route('view-video')->with('success', 'Video created successfully.');
    }

    public function editVideo(Request $request){
        $request->validate([
            'eq'=>'required'
        ]);

        $data = decrypturl($request->eq);
        $compiId = $data['id'];
        $video = Video::where('id',$compiId)->first();
        $skill = Skill::where('status',1)->get();
        $topic = Topic::where('status',1)->get();
        return view('questionBank.edit-video',compact('skill','topic','video'));
    }

    public function updateVideo(Request $request){
        $request->validate([
            'video_title' => 'required|min:2',
            'skill' => 'required',
            'difficulty_level' => 'required',
            'watch_time' => 'required|numeric|min:1',
            'paid' => 'required|in:0,1',
            'status' => 'required|in:0,1',
            'eq'=>'required'
        ]);

        $tag = [];
        if($request->has('tags') && isset($request->tags)){
            $tags = json_decode($request->tags, true);
            $tag = array_column($tags, 'value');
        }

        $data = decrypturl($request->eq);
        $compiId = $data['id'];

        $video = Video::where('id',$compiId)->first();

        $type = null;
        $source = null;
        if($request->filled('youtube_id')){
            $type = "YouTube";
            $source = $request->youtube_id;
        }else if($request->filled('vimeo_id')){
            $type = "Vimeo";
            $source = $request->vimeo_id;
        }else if($request->filled('video_url')){
            $type = "MP4";
            $source = $request->video_url;
        }

        // Check if both type and source are not empty
        if (empty($type) || empty($source)) {
            return redirect()->back()->withErrors(['error' => 'Please provide a valid video source.']);
        }

        
        $tag = [];
        if($request->has('tags') && isset($request->tags)){
            $tags = json_decode($request->tags, true);
            $tag = array_column($tags, 'value');
        }

         // Handle the image upload
        $imagePath = $video->thumbnail;
        if ($request->hasFile('video_thumbnail')) {
            $image = $request->file('video_thumbnail');
            
            // Generate a unique filename with the current timestamp
            $imageName = 'video_thumbnail_' . time() . '.' . $image->getClientOriginalExtension();
    
            // Move the image to the 'public/blogs' directory
            $image->move(public_path('fileManager/thumbnail/'), $imageName);
    
            // Store the image path in the database (relative to the public directory)
            $imagePath = $imageName;
        }


        $video->title = $request->input('video_title');
        $video->type = $type;
        $video->source = $source;
        $video->description = $request->input('description');
        $video->skill_id = $request->input('skill');
        $video->thumbnail = $imagePath;
        $video->topic_id = $request->input('topic');
        $video->tags = json_encode($tag);
        $video->level = $request->input('difficulty_level');
        $video->watch_time = $request->input('watch_time');
        $video->is_free = $request->input('paid') == 1 ? 0 : 1;
        $video->status = $request->input('status');
        $video->save();

        // Redirect or respond
        return redirect()->route('view-video')->with('success', 'Video updated successfully.');
    }

    public function deleteVideo(Request $request){
        $request->validate([
            'eq'=>'required'
        ]);

        $data = decrypturl($request->eq);
        $compiId = $data['id'];
        $user = Video::where('id',$compiId)->first();
        if($user){
            $user->status = 2; // Delete
            $user->save();
            return redirect()->back()->with('success','Video Removed Successfully');
        }
        return redirect()->back()->with('error','Something Went Wrong');
    }

}
