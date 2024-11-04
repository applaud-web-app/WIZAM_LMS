<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;
use App\Models\ExamType;
use App\Models\QuizType;
use App\Models\Quizze;
use App\Models\SubCategory;
use App\Models\Topic;
use App\Models\Sections;
use App\Models\Skill;
use App\Models\Tags;
use App\Models\QuestionType;
use App\Models\QuizQuestion;
use App\Models\Question;
use App\Models\QuizSchedule;
use App\Models\Exam;
use App\Models\ExamSection;
use App\Models\ExamQuestion;
use App\Models\ExamSchedule;
use App\Models\UserGroup;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class ManageTest extends Controller
{
    // EXAM TYPE
    public function examTypes(Request $request){

        if (!Auth()->user()->can('exam-type')) { // Assuming 'file-manager' is the required permission
            return redirect()->route('admin-dashboard')->with('error', 'You do not have permission to this page.');
        }
        
        if ($request->ajax()) {
            $sections = ExamType::whereIn('status',[0,1]);

            return DataTables::of($sections)
                ->addIndexColumn()
                ->addColumn('action', function ($section) {
                    $parms = "id=".$section->id;
                    $editUrl = encrypturl(route('edit-exam-types'),$parms);
                    $deleteUrl = encrypturl(route('delete-exam-types'),$parms);
                    return '
                        <button type="button" data-url="'.$editUrl.'" data-name="'.$section->name.'" data-description="'.$section->description.'" data-status="'.$section->status.'" data-img_url="'.$section->img_url.'" data-color="'.$section->color.'" class="editItem cursor-pointer edit-task-title uil uil-edit-alt hover:text-info" data-te-toggle="modal" data-te-target="#editModal" data-te-ripple-init data-te-ripple-color="light"></button>
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
        return view('manageTest.examType.view-exam-type');
    }

    public function addExamTypes(Request $request){
        if (!Auth()->user()->can('exam-type')) { // Assuming 'file-manager' is the required permission
            return redirect()->route('admin-dashboard')->with('error', 'You do not have permission to this page.');
        }

        // Validation rules
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'status' => 'required|string|in:1,0',
        ]);

        $slug = Str::slug($request->input('name'));
        // Ensure the slug is unique
        $originalSlug = $slug;
        $count = 1;
        while (ExamType::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        // Create section
        ExamType::create([
            'name' => $request->name,
            'slug' => $slug,
            'color' => $request->color,
            'img_url' => $request->img_url,
            'description' => $request->description, 
            'status' => $request->status,
        ]);

        // Redirect with success message
        return redirect()->route('exam-types')->with('success', 'Exam Type created successfully.');
    }

    public function editExamTypes(Request $request){
        if (!Auth()->user()->can('exam-type')) { // Assuming 'file-manager' is the required permission
            return redirect()->route('admin-dashboard')->with('error', 'You do not have permission to this page.');
        }
        
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'status' => 'required|string|in:1,0',
        ]);

        $data = decrypturl($request->eq);
        $skillId = $data['id'];
        $user = ExamType::where('id',$skillId)->first();
        if($user){

            $slug = Str::slug($request->input('name'));
            $originalSlug = $slug;
            $count = 1;
            while (ExamType::where('slug', $slug)->where('id', '!=', $skillId)->exists()) {
                $slug = $originalSlug . '-' . $count;
                $count++;
            }
            $user->slug = $slug;
            $user->name = $request->name;
            $user->color = $request->color;
            $user->img_url = $request->img_url;
            $user->description = $request->description; 
            $user->status = $request->status;
            $user->save();
            return redirect()->back()->with('success','Exam Type Updated Successfully');
        }
        return redirect()->back()->with('error','Something Went Wrong');

    }
    
    public function deleteExamTypes(Request $request){
        if (!Auth()->user()->can('exam-type')) { // Assuming 'file-manager' is the required permission
            return redirect()->route('admin-dashboard')->with('error', 'You do not have permission to this page.');
        }
        
        $request->validate([
            'eq'=>'required'
        ]);

        $data = decrypturl($request->eq);
        $skillId = $data['id'];
        $user = ExamType::where('id',$skillId)->first();
        if($user){
            $user->status = 2; // Delete
            $user->save();
            return redirect()->back()->with('success','Exam Type Removed Successfully');
        }
        return redirect()->back()->with('error','Something Went Wrong');
    }

    // QUIZZ TYPE
    public function quizTypes(Request $request){

        if (!Auth()->user()->can('quiz-type')) { // Assuming 'file-manager' is the required permission
            return redirect()->route('admin-dashboard')->with('error', 'You do not have permission to this page.');
        }

        if ($request->ajax()) {
            $sections = QuizType::whereIn('status',[0,1]);

            return DataTables::of($sections)
                ->addIndexColumn()
                ->addColumn('action', function ($section) {
                    $parms = "id=".$section->id;
                    $editUrl = encrypturl(route('edit-quiz-types'),$parms);
                    $deleteUrl = encrypturl(route('delete-quiz-types'),$parms);
                    return '
                        <button type="button" data-url="'.$editUrl.'" data-name="'.$section->name.'" data-description="'.$section->description.'" data-status="'.$section->status.'" data-img_url="'.$section->img_url.'" data-color="'.$section->color.'" class="editItem cursor-pointer edit-task-title uil uil-edit-alt hover:text-info" data-te-toggle="modal" data-te-target="#editModal" data-te-ripple-init data-te-ripple-color="light"></button>
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
        return view('manageTest.quizType.view-quiz-type');
    }

    public function addQuizTypes(Request $request){
        if (!Auth()->user()->can('quiz-type')) { // Assuming 'file-manager' is the required permission
            return redirect()->route('admin-dashboard')->with('error', 'You do not have permission to this page.');
        }

        // Validation rules
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'status' => 'required|string|in:1,0',
        ]);

        $slug = Str::slug($request->input('name'));
        // Ensure the slug is unique
        $originalSlug = $slug;
        $count = 1;
        while (QuizType::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        // Create section
        QuizType::create([
            'name' => $request->name,
            'slug' => $slug,
            'color' => $request->color,
            'img_url' => $request->img_url,
            'description' => $request->description, 
            'status' => $request->status,
        ]);

        // Redirect with success message
        return redirect()->route('quiz-types')->with('success', 'Quiz Type created successfully.');
    }

    public function editQuizTypes(Request $request){
        if (!Auth()->user()->can('quiz-type')) { // Assuming 'file-manager' is the required permission
            return redirect()->route('admin-dashboard')->with('error', 'You do not have permission to this page.');
        }
        
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'status' => 'required|string|in:1,0',
        ]);

        $data = decrypturl($request->eq);
        $skillId = $data['id'];
        $user = QuizType::where('id',$skillId)->first();
        if($user){
            $slug = Str::slug($request->input('name'));
            $originalSlug = $slug;
            $count = 1;
            while (QuizType::where('slug', $slug)->where('id', '!=', $skillId)->exists()) {
                $slug = $originalSlug . '-' . $count;
                $count++;
            }
            $user->slug = $slug;
            $user->name = $request->name;
            $user->color = $request->color;
            $user->img_url = $request->img_url;
            $user->description = $request->description; 
            $user->status = $request->status;
            $user->save();
            return redirect()->back()->with('success','Quiz Type Updated Successfully');
        }
        return redirect()->back()->with('error','Something Went Wrong');

    }
    
    public function deleteQuizTypes(Request $request){
        if (!Auth()->user()->can('quiz-type')) { // Assuming 'file-manager' is the required permission
            return redirect()->route('admin-dashboard')->with('error', 'You do not have permission to this page.');
        }
        
        $request->validate([
            'eq'=>'required'
        ]);

        $data = decrypturl($request->eq);
        $skillId = $data['id'];
        $user = QuizType::where('id',$skillId)->first();
        if($user){
            $user->status = 2; // Delete
            $user->save();
            return redirect()->back()->with('success','Quiz Type Removed Successfully');
        }
        return redirect()->back()->with('error','Something Went Wrong');
    }

    // QUIZZE
    public function viewQuizzes(Request $request){

        if (!Auth()->user()->can('quizze')) { 
            return redirect()->route('admin-dashboard')->with('error', 'You do not have permission to this page.');
        }

        if ($request->ajax()) {
            $sections = Quizze::with('type','subCategory')->whereIn('status',[0,1]);

            return DataTables::of($sections)
                ->addIndexColumn()
                ->addColumn('action', function ($section) {
                    $parms = "id=".$section->id;
                    $editUrl = route('quizzes-detail',['id'=>$section->id]);
                    $deleteUrl = encrypturl(route('delete-quizzes'),$parms);
                    return '
                        <a href="'.$editUrl.'" class="editItem cursor-pointer edit-task-title uil uil-edit-alt hover:text-info"></a>
                        <button type="button" data-url="'.$deleteUrl.'" class="deleteItem cursor-pointer remove-task-wrapper uil uil-trash-alt hover:text-danger" data-te-toggle="modal" data-te-target="#exampleModal" data-te-ripple-init data-te-ripple-color="light"></button>';
                })
                ->addColumn('created_at', function($row) {
                    return date('d/m/Y', strtotime($row->created_at));
                })
                ->addColumn('type', function($row) {
                    if(isset($row->type)){
                        return $row->type->name;
                    }
                    return "----";
                })
                ->addColumn('category', function($row) {
                    if(isset($row->subCategory)){
                        return $row->subCategory->name;
                    }
                    return "----";
                })
                ->addColumn('visibility', function($row) {
                    // Determine the status color and text based on `is_active`
                    return $row->is_public == 1 ? 'Public' : 'Private';
                })
                ->addColumn('status', function($row) {
                    // Determine the status color and text based on `is_active`
                    $statusColor = $row->status == 1 ? 'success' : 'danger';
                    $statusText = $row->status == 1 ? 'Active' : 'Inactive';
                    // Create the status badge HTML
                    return $status = "<span class='bg-{$statusColor}/10 capitalize font-medium inline-flex items-center justify-center min-h-[24px] px-3 rounded-[15px] text-{$statusColor} text-xs'>{$statusText}</span>";
                })
                ->rawColumns(['status','created_at','action','visibility','type','category'])
                ->make(true);
        }
        return view('manageTest.quizzes.view-quizzes');
    }

    public function createQuizzes(){
        if (!Auth()->user()->can('quizze')) { 
            return redirect()->route('admin-dashboard')->with('error', 'You do not have permission to this page.');
        }
        
        $quizType = QuizType::where('status',1)->get();
        $category = SubCategory::where('status',1)->get();
        return view('manageTest.quizzes.create-quizzes',compact('category','quizType'));
    }

    public function saveQuizzes(Request $request){
        if (!Auth()->user()->can('quizze')) { 
            return redirect()->route('admin-dashboard')->with('error', 'You do not have permission to this page.');
        }
        
        $request->validate([
            'testTitle' => 'required|string|max:255',
            'subCategory' => 'required|exists:sub_categories,id',  // assumes categories are stored in the categories table
            'description' => 'nullable|string|max:1000',
            'isFee'=>'required|string|in:1,0',
            'visibility' => 'required|in:0,1',   // visibility should be either 0 or 1  
        ]);

        $slug = Str::slug($request->input('testTitle'));
        // Ensure the slug is unique
        $originalSlug = $slug;
        $count = 1;
        while (Quizze::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        // Create Quizz
        $quiz = Quizze::create([
            'title' => $request->testTitle,
            'slug' => $slug,
            'subcategory_id' => $request->subCategory,
            'quiz_type_id' => $request->quiz_type,
            'description' => $request->description, 
            'is_free'=> $request->isFee, 
            'is_public'=>$request->visibility, 
            'status' => 0
        ]);

        // Redirect with success message
        return redirect()->route('quizzes-setting',['id'=>$quiz->id])->with('success', 'Quiz created successfully.');
    }

    public function updateQuizzesDetail(Request $request,$id){
        if (!Auth()->user()->can('quizze')) { 
            return redirect()->route('admin-dashboard')->with('error', 'You do not have permission to this page.');
        }
        
        $request->validate([
            'testTitle' => 'required|string|max:255',
            'subCategory' => 'required|exists:sub_categories,id',  // assumes categories are stored in the categories table
            'description' => 'nullable|string|max:1000',
            'isFee'=>'required|string|in:1,0',
            'visibility' => 'required|in:0,1',   // visibility should be either 0 or 1
            'status' => 'required|in:0,1',
        ]);

        // Update Quizz
        $quiz = Quizze::where('id',$id)->first();

        $slug = Str::slug($request->input('testTitle'));
        $originalSlug = $slug;
        $count = 1;
        while (Quizze::where('slug', $slug)->where('id', '!=', $id)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        $quiz->slug = $slug;
        $quiz->title = $request->testTitle;
        $quiz->subcategory_id = $request->subCategory;
        $quiz->quiz_type_id = $request->quiz_type;
        $quiz->description = $request->description; 
        $quiz->is_free= $request->isFee; 
        $quiz->is_public= $request->visibility; 
        $quiz->status = $request->status; 
        $quiz->save();

        // Redirect with success message
        return redirect()->route('quizzes-setting',['id'=>$quiz->id])->with('success', 'Quiz Updated Successfully.');

    }

    public function quizzesSetting($id){
        if (!Auth()->user()->can('quizze')) { 
            return redirect()->route('admin-dashboard')->with('error', 'You do not have permission to this page.');
        }
        
        $quizSetting = Quizze::where('id',$id)->first();
        if($quizSetting){
            return view('manageTest.quizzes.quizzes-setting',compact('quizSetting'));
        }
        return redirect()->back()->with('error','Something Went Wrong');
    }

    public function updateQuizzesSetting(Request $request,$id){
        if (!Auth()->user()->can('quizze')) { 
            return redirect()->route('admin-dashboard')->with('error', 'You do not have permission to this page.');
        }
        
        // Validate request data
        $request->validate([
            'duration_mode' => 'required|in:automatic,manual',
            'duration' => 'nullable|numeric|min:1',
            'point_mode' => 'required|in:automatic,manual',
            'points' => 'nullable|numeric|min:1',
            'negative_marking' => 'required|boolean',
            'negative_marking_type' => 'nullable|string|in:fixed,percentage',
            'negative_marks' => 'nullable|numeric|min:0',
            'pass_percentage' => 'required|numeric|min:0|max:100',
            'restrict_attempts' => 'required|boolean',
            'total_attempts' => 'nullable|numeric|min:1',
            'shuffle_questions' => 'required|boolean',
            'disable_finish_button' => 'required|boolean',
            'question_view' => 'required|boolean',
            'hide_solutions' => 'required|boolean',
            'leaderboard' => 'required|boolean',
        ]);

        try {
            // Fetch the QuizSetting model by ID
            $quizSetting = Quizze::findOrFail($id);

            // Update the quiz setting
            $quizSetting->update([
                'duration_mode' => $request->duration_mode,
                'duration' => $request->duration,
                'point_mode' => $request->point_mode,
                'point' => $request->points,
                'negative_marking' => $request->negative_marking,
                'negative_marking_type' => $request->negative_marking_type,
                'negative_marks' => $request->negative_marks,
                'pass_percentage' => $request->pass_percentage,
                'restrict_attempts' => $request->restrict_attempts,
                'total_attempts' => $request->total_attempts,
                'shuffle_questions' => $request->shuffle_questions,
                'disable_finish_button' => $request->disable_finish_button,
                'question_view' => $request->question_view,
                'hide_solutions' => $request->hide_solutions,
                'leaderboard' => $request->leaderboard,
            ]);
           return redirect()->route('quizzes-question',['id'=>$quizSetting->id])->with('success','Quiz Setting Updated Succssfully!!');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error','Something Went Wrong : '.$th->getMessage());
        }       
    }

    public function quizzesQuestion($id){
        if (!Auth()->user()->can('quizze')) { 
            return redirect()->route('admin-dashboard')->with('error', 'You do not have permission to this page.');
        }
        
        $quizz = Quizze::where('id',$id)->first();
        if($quizz){
            $topic = Topic::where('status',1)->get();
            // $section = Sections::where('status',1)->get();
            $skill = Skill::where('status',1)->get();
            $tags = Tags::where('status',1)->get();
            $questionType = QuestionType::where('status',1)->get();
            return view('manageTest.quizzes.quizzes-question',compact('quizz','topic','tags','questionType','skill'));
        }
        return redirect()->back()->with('error','Something Went Wrong');
    }

    public function filterQuizzesQuestion(Request $request){
        // Get form inputs
        $quiz_id = $request->input('quiz_id');
        $topic = $request->input('topic');
        $skill = $request->input('skill');
        $section = $request->input('section');
        $questionTypes = $request->input('questionType', []);
        $tags = $request->input('tags');
        $difficultyLevels = $request->input('difficultyLevel', []);

        // Build query to filter based on inputs
        $query = Question::with('topic');

        // Retrieve questions already associated with the practice set
        $existingQuestionIds = QuizQuestion::where('quizzes_id', $quiz_id)
        ->pluck('question_id')
        ->toArray();

        // Exclude questions already in the practice set
        $query->whereNotIn('id', $existingQuestionIds);
        if ($topic) {
            $query->where('topic_id', $topic);
        }

        if ($skill) {
            $query->where('skill_id', $skill);
        }

        if (!empty($questionTypes)) {
            $query->whereIn('type', $questionTypes);
        }

        if ($tags) {
            $query->where('tags', 'like', "%$tags%");
        }

        if (!empty($difficultyLevels)) {
            $query->whereIn('level', $difficultyLevels);
        }

        $questions = $query->where('status',1)->get();

        return response()->json(['questions' => $questions]);
    }

    public function updateQuizzesQuestion(Request $request,$id){
        if (!Auth()->user()->can('quizze')) { 
            return redirect()->route('admin-dashboard')->with('error', 'You do not have permission to this page.');
        }
        
        // Validate the incoming request
        $request->validate([
            'question' => 'required|array'
        ]);

        // Find the practice set by ID
        $quiz = Quizze::find($id);
        if ($quiz) {
            // Get existing question IDs for the practice set
            $existingQuestions = QuizQuestion::where('quizzes_id',$id)->pluck('question_id')->toArray();
            
            // New question IDs from the request
            $newQuestionIds = $request->input('question');
            $addedQuestions = array_diff($newQuestionIds, $existingQuestions); // Questions to add

            // Add new questions
            foreach ($addedQuestions as $questionId) {
                QuizQuestion::create([
                    'quizzes_id' => $id,
                    'question_id' => $questionId,
                ]);
            }
            return redirect()->back()->with('success','Quiz Questions updated successfully.');
        }
        return redirect()->back()->with('success','Quiz not found.');
    }

    public function fetchQuizzesQuestion(Request $request){
        $actionType = $request->input('actionType');
        $quizId = $request->input('quiz_id');
    
        // Fetch existing question IDs from the practice set
        $existingQuestionIds = QuizQuestion::where('quizzes_id', $quizId)->pluck('question_id')->toArray();
    
        // Build the query based on the action type
        $query = Question::with('topic')->where('status', 1);
    
        if ($actionType === 'all') {
            // Fetch all questions in the practice set
            $query->whereIn('id', $existingQuestionIds);
        } elseif ($actionType === 'new') {
            // Fetch questions not in the practice set
            $query->whereNotIn('id', $existingQuestionIds);
        }
    
        // Execute the query
        $questions = $query->get();
    
        return response()->json(['questions' => $questions]);
    }

    public function removeQuizzesQuestion(Request $request){
        if (!Auth()->user()->can('quizze')) { 
            return redirect()->route('admin-dashboard')->with('error', 'You do not have permission to this page.');
        }
        
        $request->validate([
            'questionId' => 'required|integer|exists:questions,id',
            'quiz_id' => 'required|integer|exists:quizzes,id',
        ]);
    
        try {
            // Remove the question from the practice set
            QuizQuestion::where('quizzes_id', $request->quiz_id)
                ->where('question_id', $request->questionId)
                ->delete();
    
            return response()->json(['message' => 'Question removed successfully.'], 200);
        } catch (\Exception $e) {
            // Handle any errors that may occur
            return response()->json(['error' => 'Failed to remove question.'], 500);
        }
    }

    public function deleteQuizzes(Request $request){
        if (!Auth()->user()->can('quizze')) { 
            return redirect()->route('admin-dashboard')->with('error', 'You do not have permission to this page.');
        }
        
        $request->validate([
            'eq'=>'required'
        ]);

        $data = decrypturl($request->eq);
        $skillId = $data['id'];
        $user = Quizze::where('id',$skillId)->first();
        if($user){
            $user->status = 2; // Delete
            $user->save();
            return redirect()->back()->with('success','Quiz Removed Successfully');
        }
        return redirect()->back()->with('error','Something Went Wrong');
    }

    public function quizzesDetail($id){
        if (!Auth()->user()->can('quizze')) { 
            return redirect()->route('admin-dashboard')->with('error', 'You do not have permission to this page.');
        }
        
        $quiz = Quizze::where('id',$id)->first();
        if($quiz){
            $quizType = QuizType::where('status',1)->get();
            $category = SubCategory::where('status',1)->get();
            return view('manageTest.quizzes.quizzes-detail',compact('quiz','quizType','category'));
        }
        return redirect()->back()->with('error','Something Went Wrong');
    }

    public function quizzesSchedules(Request $request,$id){
        if (!Auth()->user()->can('quizze')) { 
            return redirect()->route('admin-dashboard')->with('error', 'You do not have permission to this page.');
        }
        
        $quiz = Quizze::where('id',$id)->where('status',1)->first();
        if($quiz){
            if ($request->ajax()) {
                $sections = QuizSchedule::where('quizzes_id',$id)->whereIn('status',[0,1]);
    
                return DataTables::of($sections)
                    ->addIndexColumn()
                    ->addColumn('action', function ($section) {
                        $parms = "id=".$section->id;
                        $editUrl = route('save-quizzes-schedules',['id'=>$section->quizzes_id]);
                        $deleteUrl = encrypturl(route('delete-quizzes-schedules'),$parms);
                        return '
                            <button type="button" data-url="'.$editUrl.'" data-id="'.$section->id.'" data-schedule_type="'.$section->schedule_type.'" data-start_date="'.$section->start_date.'" data-start_time="'.$section->start_time.'" data-end_date="'.$section->end_date.'" data-end_time="'.$section->end_time.'" data-grace_period="'.$section->grace_period.'" data-user_groups="'.$section->user_groups.'" data-status="'.$section->status.'" class="editItem cursor-pointer edit-task-title uil uil-edit-alt hover:text-info" data-te-toggle="modal" data-te-target="#editModal" data-te-ripple-init data-te-ripple-color="light"></button>
                            <button type="button" data-url="'.$deleteUrl.'" class="deleteItem cursor-pointer remove-task-wrapper uil uil-trash-alt hover:text-danger"  data-te-toggle="modal" data-te-target="#exampleModal" data-te-ripple-init data-te-ripple-color="light"></button>';
                    })
                    ->addColumn('type', function($row) {
                        return ucfirst($row->schedule_type);
                    })
                    ->addColumn('start_date', function($row) {
                        if (isset($row->start_date) && isset($row->start_time)) {
                            return date('d/m/Y', strtotime($row->start_date)) . ", " . date('H:i:s A', strtotime($row->start_time));
                        } else {
                            return 'N/A';  // or any placeholder text you want to show
                        }
                    })
                    ->addColumn('end_date', function($row) {
                        if (isset($row->end_date) && isset($row->end_time)) {
                            return date('d/m/Y', strtotime($row->end_date)) . ", " . date('H:i:s A', strtotime($row->end_time));
                        } else {
                            return 'N/A';  // or any placeholder text you want to show
                        }
                    })
                    ->addColumn('status', function($row) {
                        // Determine the status color and text based on `is_active`
                        $statusColor = $row->status == 1 ? 'success' : 'danger';
                        $statusText = $row->status == 1 ? 'Active' : 'Inactive';
                        // Create the status badge HTML
                        return $status = "<span class='bg-{$statusColor}/10 capitalize font-medium inline-flex items-center justify-center min-h-[24px] px-3 rounded-[15px] text-{$statusColor} text-xs'>{$statusText}</span>";
                    })
                    ->rawColumns(['status','start_date','action','type','end_date'])
                    ->make(true);
            }
            $userGroup = UserGroup::where('is_active',1)->get();
            return view('manageTest.quizzes.quizzes-schedules',compact('id','userGroup'));
        }
        return redirect()->back()->with('error','Make quiz active to schedule');
    }

    public function updateQuizzesSchedules(Request $request,$id){
        if (!Auth()->user()->can('quizze')) { 
            return redirect()->route('admin-dashboard')->with('error', 'You do not have permission to this page.');
        }
        
        // Validate the request data
        $validatedData = $request->validate([
            'scheduleType' => 'required|in:fixed,flexible',
            'startDate'    => 'required|date',
            'startTime'    => 'required|date_format:H:i',
            'endDate'      => 'nullable|date|required_if:scheduleType,flexible',
            'endTime'      => 'nullable|date_format:H:i|required_if:scheduleType,flexible',
            'gracePeriod'  => 'nullable|integer|min:0|required_if:scheduleType,fixed',
            'userGroup'    => 'required|string|max:255',
        ]);
        
        // Fetch the schedule by its ID
        $schedule = QuizSchedule::create([
            'quizzes_id'  => $id,
            'schedule_type'=> $validatedData['scheduleType'],
            'start_date'    => $validatedData['startDate'],
            'start_time'   => $validatedData['startTime'],
            'end_date'    => isset($validatedData['endDate']) ? $validatedData['endDate'] : null,
            'end_time'   => isset($validatedData['endDate']) ?  $validatedData['endTime'] : null,
            'grace_period'=> $validatedData['gracePeriod'] ?? null,
            'user_groups'  => $validatedData['userGroup'],
        ]);

        // Return a success response, e.g., redirect to the schedules list with a success message
        return redirect()->route('quizzes-schedules', ['id' => $id])->with('success', 'Quiz schedule updated successfully.');
    }

    public function saveQuizzesSchedules(Request $request,$id){
        if (!Auth()->user()->can('quizze')) { 
            return redirect()->route('admin-dashboard')->with('error', 'You do not have permission to this page.');
        }
        
        // Validate the request data
        $validatedData = $request->validate([
            'scheduleType' => 'required|in:fixed,flexible',
            'schedule_id'    => 'required',
            'startDate'    => 'required|date',
            'startTime'    => 'required|date_format:H:i',
            'endDate'      => 'nullable|date|required_if:scheduleType,flexible',
            'endTime'      => 'nullable|date_format:H:i|required_if:scheduleType,flexible',
            'gracePeriod'  => 'nullable|integer|min:0|required_if:scheduleType,fixed',
            'userGroup'    => 'required|string|max:255',
        ]);

        // Fetch the schedule by its ID
        $schedule = QuizSchedule::where('id',$request->schedule_id)->first();
        $schedule->schedule_type= $validatedData['scheduleType'];
        $schedule->start_date    = $validatedData['startDate'];
        $schedule->start_time   = $validatedData['startTime'];
        $schedule->end_date    = isset($validatedData['endDate']) ? $validatedData['endDate'] : null;
        $schedule->end_time   = isset($validatedData['endDate']) ?  $validatedData['endTime'] : null;
        $schedule->grace_period= $validatedData['gracePeriod'] ?? null;
        $schedule->user_groups  = $validatedData['userGroup'];
        $schedule->save();

        // Return a success response, e.g., redirect to the schedules list with a success message
        return redirect()->route('quizzes-schedules', ['id' => $id])->with('success', 'Quiz schedule updated successfully.');
    }

    public function deleteQuizzesSchedules(Request $request){
        if (!Auth()->user()->can('quizze')) { 
            return redirect()->route('admin-dashboard')->with('error', 'You do not have permission to this page.');
        }
        
        $request->validate([
            'eq'=>'required'
        ]);

        $data = decrypturl($request->eq);
        $skillId = $data['id'];
        $user = QuizSchedule::where('id',$skillId)->first();
        if($user){
            $user->status = 2; // Delete
            $user->save();
            return redirect()->back()->with('success','Quiz Schedule Removed Successfully');
        }
        return redirect()->back()->with('error','Something Went Wrong');
    }

    // Exams
    public function viewExam(Request $request){
        if (!Auth()->user()->can('exams')) { // Assuming 'file-manager' is the required permission
            return redirect()->route('admin-dashboard')->with('error', 'You do not have permission to this page.');
        }

        if ($request->ajax()) {
            $sections = Exam::with('type','subCategory')->whereIn('status',[0,1]);
            return DataTables::of($sections)
                ->addIndexColumn()
                ->addColumn('action', function ($section) {
                    $parms = "id=".$section->id;
                    $editUrl = route('exam-detail',['id'=>$section->id]);
                    $deleteUrl = encrypturl(route('delete-exam'),$parms);
                    return '
                        <a href="'.$editUrl.'" class="editItem cursor-pointer edit-task-title uil uil-edit-alt hover:text-info"></a>
                        <button type="button" data-url="'.$deleteUrl.'" class="deleteItem cursor-pointer remove-task-wrapper uil uil-trash-alt hover:text-danger" data-te-toggle="modal" data-te-target="#exampleModal" data-te-ripple-init data-te-ripple-color="light"></button>';
                })
                ->addColumn('created_at', function($row) {
                    return date('d/m/Y', strtotime($row->created_at));
                })
                ->addColumn('type', function($row) {
                    if(isset($row->type)){
                        return $row->type->name;
                    }
                    return "----";
                })
                ->addColumn('category', function($row) {
                    if(isset($row->subCategory)){
                        return $row->subCategory->name;
                    }
                    return "----";
                })
                ->addColumn('visibility', function($row) {
                    // Determine the status color and text based on `is_active`
                    return $row->is_public == 1 ? 'Public' : 'Private';
                })
                ->addColumn('status', function($row) {
                    // Determine the status color and text based on `is_active`
                    $statusColor = $row->status == 1 ? 'success' : 'danger';
                    $statusText = $row->status == 1 ? 'Active' : 'Inactive';
                    // Create the status badge HTML
                    return $status = "<span class='bg-{$statusColor}/10 capitalize font-medium inline-flex items-center justify-center min-h-[24px] px-3 rounded-[15px] text-{$statusColor} text-xs'>{$statusText}</span>";
                })
                ->rawColumns(['status','created_at','action','visibility','type','category'])
                ->make(true);
        }
        return view('manageTest.exams.view-exam');
    }

    public function createExams(){
        if (!Auth()->user()->can('exams')) { // Assuming 'file-manager' is the required permission
            return redirect()->route('admin-dashboard')->with('error', 'You do not have permission to this page.');
        }

        $examType = ExamType::where('status',1)->get();
        $category = SubCategory::where('status',1)->get();
        return view('manageTest.exams.create-exam',compact('examType','category'));
    }

    public function saveExams(Request $request)
    {
        if (!Auth()->user()->can('exams')) { // Assuming 'file-manager' is the required permission
            return redirect()->route('admin-dashboard')->with('error', 'You do not have permission to this page.');
        }

        // Update validation rules to include 'img_url'
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            // 'duration_type' => 'required|in:exam_wise,ques_wise',
            // 'exam_duration' => 'required_if:duration_type,exam_wise|nullable|integer|min:1',
            'sub_category' => 'required',
            'exam_type' => 'required|exists:exam_types,id',
            'is_free' => 'required|boolean',
            'price' => 'required_if:is_free,0|nullable|numeric|min:0',
            'download_report' => 'required|boolean',
            'description' => 'nullable|string',
            'visibility' => 'required|boolean',
            'favorite' => 'required|boolean',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Validate the image URL
        ]);


        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            
            // Store the image directly in the public folder
            $image->move(public_path('exams'), $imageName); 
            
            // Construct the full URL to the uploaded image
            $imageUrl = env('APP_URL') . '/exams/' . $imageName; 
        } else {
            return redirect()->back()->withErrors(['image' => 'Image upload failed.']);
        }


        // Generate a slug from the exam title
        $slug = Str::slug($validatedData['title']);

        // Ensure the slug is unique
        $originalSlug = $slug;
        $count = 1;
        while (Exam::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        // Create Exam with image URL
        $exam = Exam::create([
            'title' => $validatedData['title'],
            'duration_type' => $validatedData['duration_type'] ?? null,
            'exam_duration' => $validatedData['exam_duration'] ?? null, // Add duration if provided
            'subcategory_id' => $validatedData['sub_category'],
            'exam_type_id' => $validatedData['exam_type'],
            'description' => $validatedData['description'], 
            'is_free' => $validatedData['is_free'], 
            'price' => $validatedData['is_free'] ? null : $validatedData['price'], // Set price conditionally
            'download_report' => $validatedData['download_report'], 
            'is_public' => $validatedData['visibility'], 
            'favourite' => $validatedData['favorite'],
            'status' => 0, // DRAFT
            'slug' => $slug, // Add the slug to the database
            'img_url' => $imageUrl, // Save the complete image path
        ]);

        // Redirect with success message
        return redirect()->route('exam-setting', ['id' => $exam->id])->with('success', 'Exam created successfully.');
    }
    
    public function examDetail($id){
        if (!Auth()->user()->can('exams')) { // Assuming 'file-manager' is the required permission
            return redirect()->route('admin-dashboard')->with('error', 'You do not have permission to this page.');
        }

        $exam = Exam::where('id',$id)->first();
        if($exam){
            $examType = ExamType::where('status',1)->get();
            $category = SubCategory::where('status',1)->get();
            return view('manageTest.exams.update-exam-detail',compact('examType','exam','category'));
        }
        return redirect()->back()->with('error','Something Went Wrong');
    }

    public function updateExamDetail(Request $request, $id)
    {
        if (!Auth()->user()->can('exams')) { // Assuming 'file-manager' is the required permission
            return redirect()->route('admin-dashboard')->with('error', 'You do not have permission to this page.');
        }

        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            // 'duration_type' => 'required|in:exam_wise,ques_wise',
            // 'exam_duration' => 'required_if:duration_type,exam_wise|nullable|integer|min:1',
            'sub_category' => 'required',
            'exam_type' => 'required|exists:exam_types,id',
            'is_free' => 'required|boolean',
            'price' => 'required_if:is_free,0|nullable|numeric|min:0',
            'download_report' => 'required|boolean',
            'description' => 'nullable|string',
            'visibility' => 'required|boolean',
            'favorite' => 'required|boolean',
            'status' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Find the exam by ID
        $exam = Exam::find($id); // Using find() is more concise
        if ($exam) {
            $exam->title = $validatedData['title'];
            // $exam->duration_type = $validatedData['duration_type'];
            // $exam->exam_duration = $validatedData['exam_duration'] ?? null; // Add duration if provided
            $exam->subcategory_id = $validatedData['sub_category'];
            $exam->exam_type_id = $validatedData['exam_type'];
            $exam->description = $validatedData['description']; 
            $exam->is_free = $validatedData['is_free']; 
            $exam->price = $validatedData['is_free'] ? null : $validatedData['price']; // Set price conditionally
            $exam->download_report = $validatedData['download_report']; 
            $exam->is_public = $validatedData['visibility']; 
            $exam->favourite = $validatedData['favorite'];
            $exam->status = $validatedData['status']; // DRAFT
            
            // Handle image upload
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '_' . $image->getClientOriginalName();
                // Store the image directly in the public folder
                $image->move(public_path('exams'), $imageName); // Store in 'public/exams'
                // Construct the full URL to the uploaded image
                $exam->img_url = env('APP_URL') . '/exams/' . $imageName; // Save the new image URL
            } else {
                // Keep the existing image URL if no new image is uploaded
                $exam->img_url = $exam->img_url; 
            }
            
            // Generate a new slug from the title
            $slug = Str::slug($validatedData['title']);

            // Ensure the slug is unique
            $originalSlug = $slug;
            $count = 1;
            while (Exam::where('slug', $slug)->where('id', '!=', $id)->exists()) {
                $slug = $originalSlug . '-' . $count;
                $count++;
            }

            // Update the slug in the exam
            $exam->slug = $slug;

            // Save the updated exam data
            $exam->save();

            // Redirect with success message
            return redirect()->route('exam-setting', ['id' => $exam->id])->with('success', 'Exam updated successfully.');
        }

        return redirect()->back()->with('error', 'Something went wrong');
    }

    public function examSetting($id){
        if (!Auth()->user()->can('exams')) { // Assuming 'file-manager' is the required permission
            return redirect()->route('admin-dashboard')->with('error', 'You do not have permission to this page.');
        }

        $examSetting = Exam::where('id',$id)->first();
        if($examSetting){
            return view('manageTest.exams.exam-setting',compact('examSetting'));
        }
        return redirect()->back()->with('error','Something Went Wrong');
    }

    public function updateExamSetting(Request $request,$id){
        if (!Auth()->user()->can('exams')) { // Assuming 'file-manager' is the required permission
            return redirect()->route('admin-dashboard')->with('error', 'You do not have permission to this page.');
        }

        // Validate the incoming request data
        $validatedData = $request->validate([
            'duration_mode' => 'required|in:automatic,manual',
            'point_mode' => 'required|in:automatic,manual',
            'negative_marking' => 'required|boolean',
            'pass_percentage' => 'required|numeric|min:0|max:100',
            'cutoff' => 'required|boolean',
            'shuffle_questions' => 'required|boolean',
            'restrict_attempts' => 'required|boolean',
            'total_attempts' => 'required_if:restrict_attempts,1|nullable|numeric|min:1',
            'disable_navigation' => 'required|boolean',
            'disable_finish_button' => 'required|boolean',
            'question_view' => 'required|boolean',
            'hide_solutions' => 'required|boolean',
            'leaderboard' => 'required|boolean',
        ]);

        $validatedData['duration'] = null;
        if($request->duration_mode == "manual"){
            $validatedData['exam_duration'] = $request->duration;
        }

        $validatedData['point'] = null;
        if($request->point_mode == "manual"){
            $validatedData['point'] = $request->points;
        }

        $validatedData['negative_marking_type'] = null;
        $validatedData['negative_marks'] = null;
        if($request->negative_marking == 1){
            $validatedData['negative_marking_type'] = $request->negative_marking_type;
            $validatedData['negative_marks'] = $request->negative_marks;
        }

        $exam = Exam::where('id',$id)->first();
        if($exam){
            $exam->update($validatedData);
            return redirect()->route('exam-section',['id'=>$exam->id])->with('success', 'Exam settings updated successfully.');
        }
        return redirect()->back()->with('error','Something Went Wrong');
    }

    public function examSection(Request $request, $id)
    {
        if (!Auth()->user()->can('exams')) { // Assuming 'file-manager' is the required permission
            return redirect()->route('admin-dashboard')->with('error', 'You do not have permission to this page.');
        }

        // Fetch the exam settings
        $examSetting = Exam::find($id);
        
        if ($examSetting) {
            if ($request->ajax()) {
                // Retrieve sections along with their associated questions and questions details
                $sections = ExamSection::with(['questions.question']) // Eager load questions and related question details
                    ->where('exam_id', $id)
                    ->whereIn('status', [0, 1])
                    ->get();
    
                return DataTables::of($sections)
                    ->addIndexColumn()
                    ->addColumn('action', function ($section) {
                        $parms = "id=" . $section->id;
                        $editUrl = encrypturl(route('edit-exam-section'), $parms);
                        $deleteUrl = encrypturl(route('delete-exam-section'), $parms);
    
                        return '
                            <button type="button" data-url="' . $editUrl . '" data-section_category="' . $section->section_id . '" data-section_name="' . $section->display_name . '" data-display_order="' . $section->section_order . '" class="editItem cursor-pointer edit-task-title uil uil-edit-alt hover:text-info" data-te-toggle="modal" data-te-target="#editModal" data-te-ripple-init data-te-ripple-color="light"></button>
                            <button type="button" data-url="' . $deleteUrl . '" class="deleteItem cursor-pointer remove-task-wrapper uil uil-trash-alt hover:text-danger" data-te-toggle="modal" data-te-target="#exampleModal" data-te-ripple-init data-te-ripple-color="light"></button>';
                    })
                    ->addColumn('display_name', function($row) {
                        return ucfirst($row->display_name);
                    })
                    ->addColumn('section', function($row) {
                        return isset($row->section) ? $row->section->name : 'N/A';
                    })
                    ->addColumn('total_questions', function($row) {
                        return isset($row->questions) ? $row->questions->count() : 'N/A';
                    })
                    ->addColumn('total_duration', function($row) {
                        return $row->questions->sum(function($question) {
                            return $question->question ? $question->question->watch_time : 0; // Get watch_time from the related question
                        });
                    })
                    ->addColumn('total_marks', function($row) {
                        return $row->questions->sum(function($question) {
                            return $question->question ? $question->question->default_marks : 0; // Get default_marks from the related question
                        });
                    })
                    ->addColumn('status', function($row) {
                        $statusColor = $row->status == 1 ? 'success' : 'danger';
                        $statusText = $row->status == 1 ? 'Active' : 'Inactive';
    
                        return "<span class='bg-{$statusColor}/10 capitalize font-medium inline-flex items-center justify-center min-h-[24px] px-3 rounded-[15px] text-{$statusColor} text-xs'>{$statusText}</span>";
                    })
                    ->rawColumns(['status', 'total_marks', 'total_duration', 'total_questions', 'section', 'action', 'display_name'])
                    ->make(true);
            }
    
            // Retrieve sections for the view
            $section = Sections::whereIn('status', [1, 0])->get();
            return view('manageTest.exams.exam-section', compact('examSetting', 'section'));
        }
    
        return redirect()->back()->with('error', 'Something Went Wrong');
    }

    public function addExamSection(Request $request, $id){
        if (!Auth()->user()->can('exams')) { // Assuming 'file-manager' is the required permission
            return redirect()->route('admin-dashboard')->with('error', 'You do not have permission to this page.');
        }

        // Validate the incoming request data
        $validatedData = $request->validate([
            'section_name' => 'required|string|max:255',
            'section_category' => 'required|exists:sections,id',
            'display_order' => 'required|numeric|min:1',
        ]);

        // Create a new exam section
        $examSection = ExamSection::create([
            'exam_id' => $id, // Assuming you're passing the exam ID as a parameter
            'section_id' => $validatedData['section_category'],
            'display_name' => $validatedData['section_name'],
            'section_order' => $validatedData['display_order'],
        ]);

        if ($examSection) {
            return redirect()->route('exam-section',['id'=>$id])->with('success', 'Exam section Added successfully.');
        }

        return redirect()->back()->with('error', 'Failed to add exam section.');
    }

    public function editExamSection(Request $request){
        if (!Auth()->user()->can('exams')) { // Assuming 'file-manager' is the required permission
            return redirect()->route('admin-dashboard')->with('error', 'You do not have permission to this page.');
        }

        $validatedData = $request->validate([
            'section_name' => 'required|string|max:255',
            'section_category' => 'required|exists:sections,id',
            'display_order' => 'required|numeric|min:1',
            'eq'=>'required'
        ]);

        $data = decrypturl($request->eq);
        $skillId = $data['id'];
        $examSection = ExamSection::where('id',$skillId)->first();
        if($examSection){
            $examSection->section_id = $validatedData['section_category'];
            $examSection->display_name = $validatedData['section_name'];
            $examSection->section_order = $validatedData['display_order'];
            $examSection->save();
            return redirect()->route('exam-section',['id'=>$examSection->exam_id])->with('success', 'Exam section Added successfully.');
        }
        return redirect()->back()->with('error', 'Failed to add exam section.');
    }

    public function deleteExamSection(Request $request){
        if (!Auth()->user()->can('exams')) { // Assuming 'file-manager' is the required permission
            return redirect()->route('admin-dashboard')->with('error', 'You do not have permission to this page.');
        }

        $request->validate([
            'eq'=>'required'
        ]);

        $data = decrypturl($request->eq);
        $skillId = $data['id'];
        $user = ExamSection::where('id',$skillId)->first();
        if($user){
            $user->status = 2; // Delete
            $user->save();
            return redirect()->back()->with('success','Exam Section Removed Successfully');
        }
        return redirect()->back()->with('error','Something Went Wrong');
    }

    public function examQuestions($id){
        if (!Auth()->user()->can('exams')) { // Assuming 'file-manager' is the required permission
            return redirect()->route('admin-dashboard')->with('error', 'You do not have permission to this page.');
        }

        $exam = Exam::where('id',$id)->first();
        $examSection = ExamSection::where('exam_id',$id)->whereIn('status',[1,0])->get();
        if($exam && count($examSection)){
            $topic = Topic::where('status',1)->get();
            $skill = Skill::where('status',1)->get();
            $tags = Tags::where('status',1)->get();
            $questionType = QuestionType::where('status',1)->get();
            return view('manageTest.exams.exam-question',compact('exam','examSection','questionType','skill','topic','tags'));
        }
        return redirect()->back()->with('error','Please Add Sections First');
    }

    public function filterExamQuestion(Request $request){
        // Get form inputs
        $exam_id = $request->input('exam_id');
        $section_id = $request->input('section_id');
        $topic = $request->input('topic');
        $skill = $request->input('skill');
        $questionTypes = $request->input('questionType', []);
        $tags = $request->input('tags');
        $difficultyLevels = $request->input('difficultyLevel', []);

        // Build query to filter based on inputs
        $query = Question::with('topic');

        // Retrieve questions already associated with the practice set
        $existingQuestionIds = ExamQuestion::where(['exam_id'=>$exam_id,'section_id'=>$section_id])
        ->pluck('question_id')
        ->toArray();

        // Exclude questions already in the practice set
        $query->whereNotIn('id', $existingQuestionIds);

        if ($topic) {
            $query->where('topic_id', $topic);
        }

        if ($skill) {
            $query->where('skill_id', $skill);
        }

        if (!empty($questionTypes)) {
            $query->whereIn('type', $questionTypes);
        }

        if ($tags) {
            $query->where('tags', 'like', "%$tags%");
        }

        if (!empty($difficultyLevels)) {
            $query->whereIn('level', $difficultyLevels);
        }

        $questions = $query->where('status',1)->get();

        return response()->json(['questions' => $questions]);
    }

    public function updateExamQuestion(Request $request,$id){
        if (!Auth()->user()->can('exams')) { // Assuming 'file-manager' is the required permission
            return redirect()->route('admin-dashboard')->with('error', 'You do not have permission to this page.');
        }

        // Validate the incoming request
        $request->validate([
            'question' => 'required|array',
            'section_id' => 'required'
        ]);

        $section = $request->section_id;

        // Find the practice set by ID
        $exam = Exam::find($id);
        if ($exam) {
            // Get existing question IDs for the practice set
            $existingQuestions = ExamQuestion::where(['exam_id'=>$id,'section_id'=>$section])->pluck('question_id')->toArray();
            
            // New question IDs from the request
            $newQuestionIds = $request->input('question');
            $addedQuestions = array_diff($newQuestionIds, $existingQuestions); // Questions to add

            // Add new questions
            foreach ($addedQuestions as $questionId) {
                ExamQuestion::create([
                    'exam_id' => $id,
                    'section_id' => $section,
                    'question_id' => $questionId,
                ]);
            }
            return redirect()->back()->with('success','Exam Section Questions updated successfully.');
        }
        return redirect()->back()->with('success','Exam not found.');
    }

    public function fetchExamQuestion(Request $request){
        $actionType = $request->input('actionType');
        $examId = $request->input('examId');
        $sectionId = $request->input('sectionId');
    
        // Fetch existing question IDs from the practice set
        $existingQuestionIds = ExamQuestion::where(['exam_id'=>$examId,'section_id'=>$sectionId])->pluck('question_id')->toArray();
    
        // Build the query based on the action type
        $query = Question::with('topic')->where('status', 1);
    
        if ($actionType === 'all') {
            // Fetch all questions in the practice set
            $query->whereIn('id', $existingQuestionIds);
        } elseif ($actionType === 'new') {
            // Fetch questions not in the practice set
            $query->whereNotIn('id', $existingQuestionIds);
        }
    
        // Execute the query
        $questions = $query->get();
    
        return response()->json(['questions' => $questions]);
    }

    public function removeExamQuestion(Request $request){
        $request->validate([
            'questionId' => 'required|integer|exists:questions,id',
            'examId' => 'required|integer|exists:exams,id',
            'sectionid' => 'required|integer|exists:exam_sections,id',
        ]);
    
        try {
            // Remove the question from the practice set
            ExamQuestion::where(['exam_id'=>$request->examId,'question_id'=>$request->questionId,'section_id'=>$request->sectionid])
                ->delete();
    
            return response()->json(['message' => 'Question removed successfully.'], 200);
        } catch (\Exception $e) {
            // Handle any errors that may occur
            return response()->json(['error' => 'Failed to remove question.'], 500);
        }
    }

    public function examSchedules(Request $request, $id){
        if (!Auth()->user()->can('exams')) { // Assuming 'file-manager' is the required permission
            return redirect()->route('admin-dashboard')->with('error', 'You do not have permission to this page.');
        }

        $exam = Exam::where('id', $id)->where('status', 1)->first(); 
        if ($exam) {
            if ($request->ajax()) {
                $sections = ExamSchedule::whereIn('status', [0, 1])->where('exam_id',$id); // Change to ExamSchedule model
                return DataTables::of($sections)
                    ->addIndexColumn()
                    ->addColumn('action', function ($section) {
                        $parms = "id=" . $section->id;
                        $editUrl = encrypturl(route('update-exam-schedules', ['id' => $section->exam_id]), $parms);
                        $deleteUrl = encrypturl(route('delete-exam-schedules'), $parms); // Update route name
                        return '
                            <button type="button" data-url="' . $editUrl . '" data-id="' . $section->id . '" data-schedule_type="' . $section->schedule_type . '" data-start_date="' . $section->start_date . '" data-start_time="' . $section->start_time . '" data-end_date="' . $section->end_date . '" data-end_time="' . $section->end_time . '" data-grace_period="' . $section->grace_period . '" data-user_groups="' . $section->user_groups . '" data-status="' . $section->status . '" class="editItem cursor-pointer edit-task-title uil uil-edit-alt hover:text-info" data-te-toggle="modal" data-te-target="#editModal" data-te-ripple-init data-te-ripple-color="light"></button>
                            <button type="button" data-url="' . $deleteUrl . '" class="deleteItem cursor-pointer remove-task-wrapper uil uil-trash-alt hover:text-danger" data-te-toggle="modal" data-te-target="#exampleModal" data-te-ripple-init data-te-ripple-color="light"></button>';
                    })
                    ->addColumn('type', function($row) {
                        return ucfirst($row->schedule_type);
                    })
                    ->addColumn('start_date', function($row) {
                        if (isset($row->start_date) && isset($row->start_time)) {
                            return date('d/m/Y', strtotime($row->start_date)) . ", " . date('H:i:s A', strtotime($row->start_time));
                        } else {
                            return 'N/A';  // Placeholder text
                        }
                    })
                    ->addColumn('end_date', function($row) {
                        if (isset($row->end_date) && isset($row->end_time)) {
                            return date('d/m/Y', strtotime($row->end_date)) . ", " . date('H:i:s A', strtotime($row->end_time));
                        } else {
                            return 'N/A';  // Placeholder text
                        }
                    })
                    ->addColumn('status', function($row) {
                        // Determine the status color and text based on `status`
                        $statusColor = $row->status == 1 ? 'success' : 'danger';
                        $statusText = $row->status == 1 ? 'Active' : 'Inactive';
                        // Create the status badge HTML
                        return "<span class='bg-{$statusColor}/10 capitalize font-medium inline-flex items-center justify-center min-h-[24px] px-3 rounded-[15px] text-{$statusColor} text-xs'>{$statusText}</span>";
                    })
                    ->rawColumns(['status', 'start_date', 'action', 'type', 'end_date'])
                    ->make(true);
            }
            $userGroup = UserGroup::where('is_active',1)->get();
            return view('manageTest.exams.exam-schedules', compact('id','userGroup'));
        }
        return redirect()->back()->with('error', 'Exam is not published yet.');
    }

    public function updateExamSchedules(Request $request,$id) {
        if (!Auth()->user()->can('exams')) { // Assuming 'file-manager' is the required permission
            return redirect()->route('admin-dashboard')->with('error', 'You do not have permission to this page.');
        }
        
        // Validate the request data
        $validatedData = $request->validate([
            'scheduleType' => 'required|in:fixed,flexible,attempts',
            'startDate'    => 'required|date',
            'startTime'    => 'required|date_format:H:i',
            'endDate'      => 'nullable|date|required_if:scheduleType,flexible',
            'endTime'      => 'nullable|date_format:H:i|required_if:scheduleType,flexible',
            'gracePeriod'  => 'nullable|integer|min:0|required_if:scheduleType,fixed',
            'attempts'     => 'nullable|integer|min:1', // New validation for attempts
            'userGroup'    => 'required|string|max:255',
            'numAttempts'  => 'nullable|integer|min:0|required_if:scheduleType,attempts',
            'eq' => 'required'
        ]);

        $gracePoint =  null;
        if($validatedData['scheduleType'] == "fixed"){
            $gracePoint =  $validatedData['gracePeriod'];
        }else if($validatedData['scheduleType'] == "attempts"){
            $gracePoint =  $validatedData['numAttempts'];
        }

        $data = decrypturl($request->eq);
        $scheduleId = $data['id'];
        // Fetch the schedule by its ID
        $schedule = ExamSchedule::findOrFail($scheduleId);
        $schedule->schedule_type = $validatedData['scheduleType'];
        $schedule->start_date    = $validatedData['startDate'];
        $schedule->start_time    = $validatedData['startTime'];
        $schedule->end_date      = isset($validatedData['endDate']) ? $validatedData['endDate'] : null;
        $schedule->end_time      = isset($validatedData['endDate']) ? $validatedData['endTime'] : null;
        $schedule->grace_period  = $gracePoint;
        $schedule->user_groups    = $validatedData['userGroup'];
        $schedule->save();
    
        // Return a success response
        return redirect()->route('exam-schedules', ['id' => $id])->with('success', 'Exam schedule updated successfully.');
    }
    
    public function saveExamSchedules(Request $request, $id) {
        if (!Auth()->user()->can('exams')) { // Assuming 'file-manager' is the required permission
            return redirect()->route('admin-dashboard')->with('error', 'You do not have permission to this page.');
        }

         // Validate the request data
         $validatedData = $request->validate([
            'scheduleType' => 'required|in:fixed,flexible,attempts',
            'startDate'    => 'required|date',
            'startTime'    => 'required|date_format:H:i',
            'endDate'      => 'nullable|date|required_if:scheduleType,flexible',
            'endTime'      => 'nullable|date_format:H:i|required_if:scheduleType,flexible',
            'gracePeriod'  => 'nullable|integer|min:0|required_if:scheduleType,fixed',
            'attempts'     => 'nullable|integer|min:1', // New validation for attempts
            'userGroup'    => 'required|string|max:255',
            'numAttempts'  => 'nullable|integer|min:0|required_if:scheduleType,attempts',
        ]);

        $gracePoint =  null;
        if($validatedData['scheduleType'] == "fixed"){
            $gracePoint =  $validatedData['gracePeriod'];
        }else if($validatedData['scheduleType'] == "attempts"){
            $gracePoint =  $validatedData['numAttempts'];
        }
        
        // Create the schedule for the exam
        ExamSchedule::create([
            'exam_id'      => $id,
            'schedule_type' => $validatedData['scheduleType'],
            'start_date'   => $validatedData['startDate'],
            'start_time'   => $validatedData['startTime'],
            'end_date'     => isset($validatedData['endDate']) ? $validatedData['endDate'] : null,
            'end_time'     => isset($validatedData['endDate']) ? $validatedData['endTime'] : null,
            'grace_period' => $gracePoint,
            'attempts'     => $validatedData['attempts'] ?? null, // Save attempts
            'user_groups'  => $validatedData['userGroup'],
        ]);
    
        // Return a success response
        return redirect()->route('exam-schedules', ['id' => $id])->with('success', 'Exam schedule updated successfully.');

    }
    
    public function deleteExamSchedules(Request $request) {
        if (!Auth()->user()->can('exams')) { // Assuming 'file-manager' is the required permission
            return redirect()->route('admin-dashboard')->with('error', 'You do not have permission to this page.');
        }
        
        $request->validate([
            'eq' => 'required'
        ]);
    
        $data = decrypturl($request->eq);
        $scheduleId = $data['id'];
        $schedule = ExamSchedule::find($scheduleId);
        
        if ($schedule) {
            $schedule->status = 2; // Mark as deleted
            $schedule->save();
            return redirect()->back()->with('success', 'Exam Schedule Removed Successfully');
        }
        
        return redirect()->back()->with('error', 'Something Went Wrong');
    }

    public function deleteExam(Request $request){
        if (!Auth()->user()->can('exams')) { // Assuming 'file-manager' is the required permission
            return redirect()->route('admin-dashboard')->with('error', 'You do not have permission to this page.');
        }
        
        $request->validate([
            'eq' => 'required'
        ]);
    
        $data = decrypturl($request->eq);
        $scheduleId = $data['id'];
        $schedule = Exam::find($scheduleId);
        
        if ($schedule) {
            $schedule->status = 2; // Mark as deleted
            $schedule->save();
            return redirect()->back()->with('success', 'Exam Removed Successfully');
        }
        
        return redirect()->back()->with('error', 'Something Went Wrong');
    }


}
