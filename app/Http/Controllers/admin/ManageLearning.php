<?php


namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;
use App\Models\PracticeSet;
use App\Models\SubCategory;
use App\Models\PracticeSetQuestion;
use App\Models\Skill;
use App\Models\Topic;
use App\Models\Tags;
use App\Models\QuestionType;
use App\Models\Question;
use App\Models\PracticeLesson;
use App\Models\Lesson;
use App\Models\Video;
use App\Models\PracticeVideo;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Models\PracticeSetResult;

class ManageLearning extends Controller
{
    public function practiceSets(Request $request) {
        if (!Auth()->user()->can('pratice-set')) { 
            return redirect()->route('admin-dashboard')->with('error', 'You do not have permission to this page.');
        }
        
        if ($request->ajax()) {
            $sections = PracticeSet::with('subCategory','skill') // Ensure the relationship is correct
                ->withCount('practiceQuestions') // Make sure 'practiceQuestions' is the correct method name
                ->whereIn('status', [0, 1]);
    
            return DataTables::of($sections)
                ->addIndexColumn()
                ->addColumn('action', function ($section) {
                    $parms = "id=".$section->id;
                    $editUrl = route('practice-set-detail',['id'=>$section->id]);
                    $deleteUrl = encrypturl(route('delete-practice-sets'),$parms);
                    $overall = route('overall-practice-set-report',['id'=>$section->id]);
                    return '
                        <a href="'.$editUrl.'" class="cursor-pointer edit-task-title uil uil-edit-alt hover:text-info" ></a>
                        <button type="button" data-url="'.$deleteUrl.'" class="deleteItem cursor-pointer remove-task-wrapper uil uil-trash-alt hover:text-danger"  data-te-toggle="modal" data-te-target="#exampleModal" data-te-ripple-init data-te-ripple-color="light"></button>
                        <a href="'.$overall.'" class="editItem cursor-pointer edit-task-title uil uil-eye hover:text-info"></a>';
                })
                ->addColumn('question', function($row) {
                    return $row->practice_questions_count;
                })
                ->addColumn('skill', function($row) {
                    if (isset($row->skill)) {
                        return $row->skill->name; // Access the 'name' property properly
                    }
                    return "----";
                })
                ->addColumn('sub_category', function($row) {
                    if (isset($row->subCategory)) {
                        return $row->subCategory->name; // Access the 'name' property properly
                    }
                    return "----";
                })
                ->addColumn('status', function($row) {
                    $statusColor = $row->status == 1 ? 'success' : 'danger';
                    $statusText = $row->status == 1 ? 'Published' : 'Unpublished';
                    return "<span class='bg-{$statusColor}/10 capitalize font-medium inline-flex items-center justify-center min-h-[24px] px-3 rounded-[15px] text-{$statusColor} text-xs'>{$statusText}</span>";
                })
                ->rawColumns(['status', 'sub_category', 'action', 'question','skill'])
                ->make(true);
        }
        return view('manageLearning.practiceSet.view-practice-set');
    }

    public function createPracticeSets(){
        if (!Auth()->user()->can('pratice-set')) { 
            return redirect()->route('admin-dashboard')->with('error', 'You do not have permission to this page.');
        }

        $skill = Skill::where('status',1)->get();
        $category = SubCategory::where('status',1)->get();
        return view('manageLearning.practiceSet.create-practice-set',compact('category','skill'));
    }

    public function savePracticeSets(Request $request){
        if (!Auth()->user()->can('pratice-set')) { 
            return redirect()->route('admin-dashboard')->with('error', 'You do not have permission to this page.');
        }
        
        $request->validate([
            'testTitle' => 'required|string|max:255',
            'subCategory'=>'required',
            'skill'=>'required',
            'description' => 'nullable|string|max:1000',
            'status' => 'required|string|in:1,0',
            'isFee'=>'required|string|in:1,0'
        ]);
        

        $slug = Str::slug($request->input('testTitle'));
        // Ensure the slug is unique
        $originalSlug = $slug;
        $count = 1;
        while (PracticeSet::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }


        // Create section
        $praticeSet = PracticeSet::create([
            'title' => $request->testTitle,
            'slug' => $slug,
            'subCategory_id' => $request->subCategory,
            'skill_id' => $request->skill,
            'description' => $request->description, 
            'is_free'=> $request->isFee, 
            'status' => $request->status,
        ]);

        // Redirect with success message
        return redirect()->route('practice-set-setting',['id'=>$praticeSet->id])->with('success', 'Practice Set created successfully.');
    }

    public function practiceSetDetail($id){
        if (!Auth()->user()->can('pratice-set')) { 
            return redirect()->route('admin-dashboard')->with('error', 'You do not have permission to this page.');
        }
        
        $praticeSet = PracticeSet::where('id',$id)->first();
        if($praticeSet){
            $skill = Skill::where('status',1)->get();
            $category = SubCategory::where('status',1)->get();
            return view('manageLearning.practiceSet.practice-set-detail',compact('praticeSet','category','skill'));
        }
        return redirect()->back()->with('error','Something Went Wrong');
    }

    public function updatePracticeSetDetail(Request $request,$id){
        if (!Auth()->user()->can('pratice-set')) { 
            return redirect()->route('admin-dashboard')->with('error', 'You do not have permission to this page.');
        }
        
        $request->validate([
            'testTitle' => 'required|string|max:255',
            'subCategory'=>'required',
            'skill'=>'required',
            'description' => 'nullable|string|max:1000',
            'status' => 'required|string|in:1,0',
            'isFee'=>'required|string|in:1,0'
        ]);
        
        $praticeSet = PracticeSet::where('id',$id)->first();
        if($praticeSet){

            $slug = Str::slug($request->input('testTitle'));
            $originalSlug = $slug;
            $count = 1;
            while (PracticeSet::where('slug', $slug)->where('id', '!=', $id)->exists()) {
                $slug = $originalSlug . '-' . $count;
                $count++;
            }
            $praticeSet->slug = $slug;
            $praticeSet->title = $request->testTitle;
            $praticeSet->subCategory_id = $request->subCategory;
            $praticeSet->skill_id = $request->skill;
            $praticeSet->description = $request->description; 
            $praticeSet->is_free= $request->isFee; 
            $praticeSet->status = $request->status;
            $praticeSet->save();
            return redirect()->route('practice-set-setting',['id'=>$praticeSet->id])->with('success','Pratice Details Updated Succssfully!!');
        }
        return redirect()->back()->with('error','Something Went Wrong');
    }

    public function practiceSetSetting($id){
        if (!Auth()->user()->can('pratice-set')) { 
            return redirect()->route('admin-dashboard')->with('error', 'You do not have permission to this page.');
        }
        
        $praticeSet = PracticeSet::where('id',$id)->first();
        if($praticeSet){
            return view('manageLearning.practiceSet.setting-practice-set',compact('praticeSet'));
        }
        return redirect()->back()->with('error','Something Went Wrong');
    }

    public function updatePracticeSetSetting(Request $request,$id){
        if (!Auth()->user()->can('pratice-set')) { 
            return redirect()->route('admin-dashboard')->with('error', 'You do not have permission to this page.');
        }
        
        $request->validate([
            'allow_reward' => 'required',
            'reward_popup'=>'required',
            'point_mode'=>'required',
        ]);

        // Additional validation for points if point_mode is 'manual'
        if ($request->point_mode == "manual") {
            $request->validate([
                'points' => 'required|numeric|min:1',
            ], [
                'points.required' => 'Points are required for manual mode',
                'points.numeric'  => 'Points must be a number',
                'points.min'      => 'Points must be at least 1',
            ]);
            $points = $request->points;
        }else{
            $points = null;
        }

        $praticeSet = PracticeSet::where('id',$id)->first();
        if($praticeSet){
            // Create section
            $praticeSet->allow_reward = $request->allow_reward;
            $praticeSet->reward_popup = $request->reward_popup;
            $praticeSet->point_mode = $request->point_mode;
            $praticeSet->points = $points;
            $praticeSet->save();
            return redirect()->route('practice-set-question',['id'=>$praticeSet->id])->with('success','Pratice Setting Updated Succssfully!!');
        }
        return redirect()->back()->with('error','Something Went Wrong');
    }

    public function practiceSetQuestion($id){
        if (!Auth()->user()->can('pratice-set')) { 
            return redirect()->route('admin-dashboard')->with('error', 'You do not have permission to this page.');
        }
        
        $praticeSet = PracticeSet::where('id',$id)->first();
        if($praticeSet){
            $praticeQuestions = PracticeSetQuestion::where('practice_set_id',$id)->get();
            $topic = Topic::where('status',1)->get();
            $tags = Tags::where('status',1)->get();
            $questionType = QuestionType::get();
            return view('manageLearning.practiceSet.practice-set-question',compact('praticeSet','praticeQuestions','topic','tags','questionType'));
        }
        return redirect()->back()->with('error','Something Went Wrong');
    }

    public function filterPracticeSetQuestion(Request $request){
        // Get form inputs
        $practiceSetId = $request->input('pratice_set');
        $topic = $request->input('topic');
        $questionTypes = $request->input('questionType', []);
        $tags = $request->input('tags');
        $difficultyLevels = $request->input('difficultyLevel', []);

        // Build query to filter based on inputs
        $query = Question::with('topic');

        // Retrieve questions already associated with the practice set
        $existingQuestionIds = PracticeSetQuestion::where('practice_set_id', $practiceSetId)
        ->pluck('question_id')
        ->toArray();

        // Exclude questions already in the practice set
        $query->whereNotIn('id', $existingQuestionIds);

        if ($topic) {
            $query->where('topic_id', $topic);
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

    public function fetchPracticeSetQuestion(Request $request)
    {
        $actionType = $request->input('actionType');
        $practiceSetId = $request->input('practiceSet');
    
        // Fetch existing question IDs from the practice set
        $existingQuestionIds = PracticeSetQuestion::where('practice_set_id', $practiceSetId)->pluck('question_id')->toArray();
    
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

    public function removePracticeSetQuestion(Request $request)
    {
        $request->validate([
            'questionId' => 'required|integer|exists:questions,id',
            'practiceId' => 'required|integer|exists:practice_sets,id',
        ]);
    
        try {
            // Remove the question from the practice set
            PracticeSetQuestion::where('practice_set_id', $request->practiceId)
                ->where('question_id', $request->questionId)
                ->delete();
    
            return response()->json(['message' => 'Question removed successfully.'], 200);
        } catch (\Exception $e) {
            // Handle any errors that may occur
            return response()->json(['error' => 'Failed to remove question.'], 500);
        }
    }

    public function updatePracticeSetQuestion(Request $request,$id){
        if (!Auth()->user()->can('pratice-set')) { 
            return redirect()->route('admin-dashboard')->with('error', 'You do not have permission to this page.');
        }
        
        // Validate the incoming request
        $request->validate([
            'question' => 'required|array'
        ]);

        // Find the practice set by ID
        $practiceSet = PracticeSet::find($id);
        if ($practiceSet) {
            // Get existing question IDs for the practice set
            $existingQuestions = PracticeSetQuestion::where('practice_set_id', $id)->pluck('question_id')->toArray();
            
            // New question IDs from the request
            $newQuestionIds = $request->input('question');
            $addedQuestions = array_diff($newQuestionIds, $existingQuestions); // Questions to add

            // Add new questions
            foreach ($addedQuestions as $questionId) {
                PracticeSetQuestion::create([
                    'practice_set_id' => $id,
                    'question_id' => $questionId,
                ]);
            }
            return redirect()->back()->with('success','Questions updated successfully.');
        }
        return redirect()->back()->with('success','Practice set not found.');
    }

    public function deletePracticeSet(Request $request){
        if (!Auth()->user()->can('pratice-set')) { 
            return redirect()->route('admin-dashboard')->with('error', 'You do not have permission to this page.');
        }
        
        $request->validate([
            'eq'=>'required'
        ]);

        $data = decrypturl($request->eq);
        $skillId = $data['id'];
        $user = PracticeSet::where('id',$skillId)->first();
        if($user){
            $user->status = 2; // Delete
            $user->save();
            return redirect()->back()->with('success','Practice Set Removed Successfully');
        }
        return redirect()->back()->with('error','Something Went Wrong');
    }

    // Lessons
    public function configureLessons(){
        if (!Auth()->user()->can('lesson')) { // Assuming 'file-manager' is the required permission
            return redirect()->route('admin-dashboard')->with('error', 'You do not have permission to this page.');
        }

        $skill = Skill::where('status',1)->get();
        $subcategory = SubCategory::where('status',1)->get();
        return view('manageLearning.lesson.configure-lessons',compact('skill','subcategory'));
    }

    public function saveConfigureLessons(Request $request){

        if (!Auth()->user()->can('lesson')) { // Assuming 'file-manager' is the required permission
            return redirect()->route('admin-dashboard')->with('error', 'You do not have permission to this page.');
        }

        $request->validate([
            'subcategory'=>'required',
            'skill'=>'required',
        ]);

        return redirect()->route('practice-lessons',['category'=>$request->subcategory,'skill'=>$request->skill])->with('success','Config Updated');
    }

    public function practiceLessons($subcategoryId,$skillId){
        if (!Auth()->user()->can('lesson')) { // Assuming 'file-manager' is the required permission
            return redirect()->route('admin-dashboard')->with('error', 'You do not have permission to this page.');
        }
        
        $skill = Skill::where('status',1)->where('id',$skillId)->first();
        $subcategory = SubCategory::where('status',1)->where('id',$subcategoryId)->first();
        if (isset($skill) && isset($subcategory)) {
            $topic = Topic::where(['status'=>1,'skill_id'=>$skillId])->get();
            $tags = Tags::where('status',1)->get();
            return view('manageLearning.lesson.practice-lessons',compact('skill','subcategory','topic','tags'));
        }
        return redirect()->route('configure-lessons')->with('error','Something Went Wrong');
    }

    public function filterPracticeLesson(Request $request)
    {
        // Get form inputs
        $skill = $request->input('skill_id');
        $subcategory_id = $request->input('subcategory_id');
        $topic = $request->input('topic');
        $tags = $request->input('tags');
        $difficultyLevels = $request->input('difficultyLevel', []);
    
        // Start the query to filter lessons based on the skill
        $query = Lesson::where('skill_id', $skill)->with('skill');
    
        // Retrieve lessons already associated with the practice set
        $existingLessonIds = PracticeLesson::where(['skill_id' => $skill, 'subcategory_id' => $subcategory_id])
            ->pluck('lesson_id')
            ->toArray();
    
        // Exclude lessons already in the practice set
        if (!empty($existingLessonIds)) {
            $query->whereNotIn('id', $existingLessonIds);
        }
    
        // Filter by topic if provided
        if ($topic) {
            $query->where('topic_id', $topic);
        }
    
        // Filter by tags if provided (like search)
        if ($tags) {
            $query->where('tags', 'like', "%$tags%");
        }
    
        // Filter by difficulty levels if provided
        if (!empty($difficultyLevels)) {
            $query->whereIn('level', $difficultyLevels);
        }
    
        // Ensure only active lessons (status = 1) are retrieved
        $lessons = $query->where('status', 1)->get();
    
        // Return lessons as a JSON response
        return response()->json(['lessons' => $lessons]);
    }

    public function updatePracticeLessons(Request $request,$subcategoryId,$skillId){
        if (!Auth()->user()->can('lesson')) { // Assuming 'file-manager' is the required permission
            return redirect()->route('admin-dashboard')->with('error', 'You do not have permission to this page.');
        }
        
        $request->validate([
            'lesson' => 'required|array'
        ]);

        $skill = Skill::where('status',1)->where('id',$skillId)->first();
        $subcategory = SubCategory::where('status',1)->where('id',$subcategoryId)->first();
        if (isset($skill) && isset($subcategory)) {

            // Get existing question IDs for the practice set
            $existingLessson = PracticeLesson::where(['skill_id'=>$skillId,'subcategory_id'=>$subcategoryId])->pluck('lesson_id')->toArray();
            
            // New question IDs from the request
            $newLessons = $request->input('lesson');
            $addedLessons = array_diff($newLessons, $existingLessson); // Questions to add

            // Add new questions
            foreach ($addedLessons as $lessonId) {
                PracticeLesson::create([
                    'skill_id' => $skillId,
                    'subcategory_id' => $subcategoryId,
                    'lesson_id' => $lessonId,
                ]);
            }
            return redirect()->back()->with('success','Pratice Lesson updated successfully.');
        }
        return redirect()->back()->with('success','Pratice Lesson not found.');
    }

    public function fetchPracticeLessons(Request $request)
    {
        // Get input values
        $actionType = $request->input('actionType');
        $subcategory_id = $request->input('category');
        $skill_id = $request->input('skill');

        // Validate required inputs
        if (!$actionType || !$subcategory_id || !$skill_id) {
            return response()->json(['error' => 'Invalid input'], 400);
        }

        // Fetch existing lesson IDs from the practice set
        $existingLessonIds = PracticeLesson::where([
            'skill_id' => $skill_id,
            'subcategory_id' => $subcategory_id
        ])->pluck('lesson_id')->toArray();

        // Initialize the query for lessons
        $query = Lesson::with('skill')->where('status', 1)->where('skill_id', $skill_id);

        // Modify the query based on the action type
        if ($actionType === 'all') {
            // Fetch only lessons already in the practice set
            if (!empty($existingLessonIds)) {
                $query->whereIn('id', $existingLessonIds);
            } else {
                // If there are no lessons in the practice set, return an empty result
                return response()->json(['lessons' => []]);
            }
        } elseif ($actionType === 'new') {
            // Fetch lessons not already in the practice set
            if (!empty($existingLessonIds)) {
                $query->whereNotIn('id', $existingLessonIds);
            }
        } else {
            return response()->json(['error' => 'Invalid action type'], 400);
        }

        // Execute the query and get the lessons
        $lessons = $query->get();

        // Return the lessons as a JSON response
        return response()->json(['lessons' => $lessons]);
    }

    public function removePracticeLessons(Request $request)
    {
        if (!Auth()->user()->can('lesson')) { // Assuming 'file-manager' is the required permission
            return redirect()->route('admin-dashboard')->with('error', 'You do not have permission to this page.');
        }
        
        // Validate request inputs
        $request->validate([
            'lessonId' => 'required|integer|exists:lessons,id', // Validating the 'lessonId' exists in 'lessons' table
            'skillId' => 'required|integer|exists:skills,id', // Assuming 'skills' is the correct table for 'skillId'
            'subcategoryId' => 'required|integer|exists:sub_categories,id', // Assuming 'subcategories' is the correct table
        ]);

        try {
            // Remove the lesson from the practice set
            PracticeLesson::where([
                    'skill_id' => $request->skillId,
                    'subcategory_id' => $request->subcategoryId,
                    'lesson_id' => $request->lessonId
                ])->delete();

            return response()->json(['message' => 'Lesson removed successfully.'], 200);
        } catch (\Exception $e) {
            // Return a detailed error message
            return response()->json(['error' => 'Failed to remove lesson. ' . $e->getMessage()], 500);
        }
    }

    // Videos
    public function configureVideos(Request $request){
        if (!Auth()->user()->can('video')) { // Assuming 'file-manager' is the required permission
            return redirect()->route('admin-dashboard')->with('error', 'You do not have permission to this page.');
        }

        $skill = Skill::where('status',1)->get();
        $subcategory = SubCategory::where('status',1)->get();
        return view('manageLearning.video.configure-videos',compact('skill','subcategory'));
    }

    public function saveConfigureVideos(Request $request){
        if (!Auth()->user()->can('video')) { // Assuming 'file-manager' is the required permission
            return redirect()->route('admin-dashboard')->with('error', 'You do not have permission to this page.');
        }

        $request->validate([
            'subcategory'=>'required',
            'skill'=>'required',
        ]);

        return redirect()->route('practice-videos',['category'=>$request->subcategory,'skill'=>$request->skill])->with('success','Config Updated');
    }

    public function practiceVideos($subcategoryId,$skillId){
        if (!Auth()->user()->can('video')) { // Assuming 'file-manager' is the required permission
            return redirect()->route('admin-dashboard')->with('error', 'You do not have permission to this page.');
        }

        $skill = Skill::where('status',1)->where('id',$skillId)->first();
        $subcategory = SubCategory::where('status',1)->where('id',$subcategoryId)->first();
        if (isset($skill) && isset($subcategory)) {
            $topic = Topic::where(['status'=>1,'skill_id'=>$skillId])->get();
            $tags = Tags::where('status',1)->get();
            return view('manageLearning.video.practice-videos',compact('skill','subcategory','topic','tags'));
        }
        return redirect()->route('configure-lessons')->with('error','Something Went Wrong');
    }

    public function filterPracticeVideos(Request $request){
        // Get form inputs
        $skill = $request->input('skill_id');
        $subcategory_id = $request->input('subcategory_id');
        $topic = $request->input('topic');
        $tags = $request->input('tags');
        $difficultyLevels = $request->input('difficultyLevel', []);
    
        // Start the query to filter lessons based on the skill
        $query = Video::where('skill_id', $skill)->with('skill');
    
        // Retrieve lessons already associated with the practice set
        $existingLessonIds = PracticeVideo::where(['skill_id' => $skill, 'subcategory_id' => $subcategory_id])
            ->pluck('video_id')
            ->toArray();
    
        // Exclude lessons already in the practice set
        if (!empty($existingLessonIds)) {
            $query->whereNotIn('id', $existingLessonIds);
        }
    
        // Filter by topic if provided
        if ($topic) {
            $query->where('topic_id', $topic);
        }
    
        // Filter by tags if provided (like search)
        if ($tags) {
            $query->where('tags', 'like', "%$tags%");
        }
    
        // Filter by difficulty levels if provided
        if (!empty($difficultyLevels)) {
            $query->whereIn('level', $difficultyLevels);
        }
    
        // Ensure only active lessons (status = 1) are retrieved
        $videos = $query->where('status', 1)->get();
    
        // Return videos as a JSON response
        return response()->json(['videos' => $videos]);
    }

    public function updatePracticeVideos(Request $request, $subcategoryId, $skillId)
    {
        if (!Auth()->user()->can('video')) { // Assuming 'file-manager' is the required permission
            return redirect()->route('admin-dashboard')->with('error', 'You do not have permission to this page.');
        }

        // Validate the request
        $request->validate([
            'videos' => 'required|array' // Assuming you're passing an array of video IDs
        ]);

        // Fetch the skill and subcategory, ensuring they're valid and active
        $skill = Skill::where('status', 1)->find($skillId);
        $subcategory = SubCategory::where('status', 1)->find($subcategoryId);

        if ($skill && $subcategory) {
            // Get existing video IDs for the practice set
            $existingVideos = PracticeVideo::where([
                'skill_id' => $skillId,
                'subcategory_id' => $subcategoryId
            ])->pluck('video_id')->toArray();

            // New video IDs from the request
            $newVideos = $request->input('videos');
            $addedVideos = array_diff($newVideos, $existingVideos); // Videos to add

            // Add new videos to the practice set
            foreach ($addedVideos as $videoId) {
                PracticeVideo::create([
                    'skill_id' => $skillId,
                    'subcategory_id' => $subcategoryId,
                    'video_id' => $videoId,
                ]);
            }

            return redirect()->back()->with('success', 'Practice Videos updated successfully.');
        }

        return redirect()->back()->with('error', 'Practice Video not found.');
    }


    public function fetchPracticeVideos(Request $request)
    {
        // Get input values
        $actionType = $request->input('actionType');
        $subcategory_id = $request->input('category');
        $skill_id = $request->input('skill');

        // Validate required inputs
        if (!$actionType || !$subcategory_id || !$skill_id) {
            return response()->json(['error' => 'Invalid input'], 400);
        }

        // Fetch existing video IDs from the practice set
        $existingVideoIds = PracticeVideo::where([
            'skill_id' => $skill_id,
            'subcategory_id' => $subcategory_id
        ])->pluck('video_id')->toArray();

        // Initialize the query for videos
        $query = Video::with('skill')->where('status', 1)->where('skill_id', $skill_id);

        // Modify the query based on the action type
        if ($actionType === 'all') {
            // Fetch only videos already in the practice set
            if (!empty($existingVideoIds)) {
                $query->whereIn('id', $existingVideoIds);
            } else {
                return response()->json(['videos' => []]);
            }
        } elseif ($actionType === 'new') {
            // Fetch videos not already in the practice set
            if (!empty($existingVideoIds)) {
                $query->whereNotIn('id', $existingVideoIds);
            }
        } else {
            return response()->json(['error' => 'Invalid action type'], 400);
        }

        // Execute the query and get the videos
        $videos = $query->get();

        return response()->json(['videos' => $videos]);
    }


    public function removePracticeVideos(Request $request)
    {
        if (!Auth()->user()->can('video')) { // Assuming 'file-manager' is the required permission
            return redirect()->route('admin-dashboard')->with('error', 'You do not have permission to this page.');
        }

        // Validate request inputs
        $request->validate([
            'videoId' => 'required|integer|exists:videos,id', // Validating the 'videoId' exists in 'videos' table
            'skillId' => 'required|integer|exists:skills,id', // Assuming 'skills' is the correct table for 'skillId'
            'subcategoryId' => 'required|integer|exists:sub_categories,id', // Assuming 'subcategories' is the correct table
        ]);
    
        try {
            // Remove the video from the practice set
            PracticeVideo::where([
                    'skill_id' => $request->skillId,
                    'subcategory_id' => $request->subcategoryId,
                    'video_id' => $request->videoId
                ])->delete();
    
            return response()->json(['message' => 'Video removed successfully.'], 200);
        } catch (\Exception $e) {
            // Return a detailed error message
            return response()->json(['error' => 'Failed to remove video. ' . $e->getMessage()], 500);
        }
    }

    public function overallPracticeSetReport($id)
    {
        // Check for permission
        if (!auth()->user()->can('pratice-set')) { // Assuming 'quizze' is the required permission
            return redirect()->route('admin-dashboard')->with('error', 'You do not have permission to access this page.');
        }
    
        // Find the quiz with active status
        $practice = PracticeSet::whereIn('status',[1,0])->find($id);
        if ($practice) {
            // Get all results for the praticeset
            $praticesetResult = PracticeSetResult::where('practice_sets_id', $practice->id)->get();
    
            // Filter out non-numeric 'student_percentage' values
            $praticesetResult = $praticesetResult->filter(function ($result) {
                return is_numeric($result->student_percentage);
            });
    
            // Calculate required data
            $totalAttempt = $praticesetResult->count();
            $passPercentage = 60;
    
            $passedpractice = $praticesetResult->where('student_percentage', '>=', $passPercentage)->count();
            $failedpractice = $praticesetResult->where('student_percentage', '<', $passPercentage)->count();
            
            // Ensure all calculations involve numeric types
            $averagePercentage = $totalAttempt > 0 ? (float) $praticesetResult->avg('student_percentage') : 0;
            $highestPercentage = $totalAttempt > 0 ? (float) $praticesetResult->max('student_percentage') : 0;
            $lowestPercentage = $totalAttempt > 0 ? (float) $praticesetResult->min('student_percentage') : 0;

            // Score
            $averageScore = $totalAttempt > 0 ? (float) $praticesetResult->avg('score') : 0;
            $highestScore = $totalAttempt > 0 ? (float) $praticesetResult->max('score') : 0;
            $lowestScore = $totalAttempt > 0 ? (float) $praticesetResult->min('score') : 0;
    
            // Return the view with all required data
            return view('manageLearning.practiceSet.practice-set-overall-report', compact(
                'practice', 'totalAttempt', 'passedpractice', 'failedpractice', 'averagePercentage', 'highestPercentage', 'lowestPercentage','averageScore','highestScore','lowestScore'
            ));
        }
    
        // Redirect if the exam is not found
        return redirect()->back()->with('error', 'Practice Not Found');
    }
    
    public function detailedPracticeReport(Request $request ,$id){
        // Check for permission
        if (!auth()->user()->can('pratice-set')) { // Assuming 'practice' is the required permission
            return redirect()->route('admin-dashboard')->with('error', 'You do not have permission to access this page.');
        }

        // Find the practice with active status
        $practice = PracticeSet::where('id', $id)->whereIn('status',[1,0])->first(); 
        if ($practice) {
            if ($request->ajax()) {
                $sections = PracticeSetResult::with('user')->where('practice_sets_id',$id)->latest();
                return DataTables::of($sections)
                    ->addIndexColumn()
                    ->addColumn('action', function ($section) {
                        $parms = "id=" . $section->uuid;
                        $viewUrl = route('practice-report-detail',[$section->uuid]);
                        $deleteUrl = route('delete-practice-result',[$section->uuid]); // Update route name
                        return '
                            <a href="' . $viewUrl . '" class="cursor-pointer edit-task-title uil uil-eye hover:text-info"></a>
                            <button type="button" data-url="' . $deleteUrl . '" class="deleteItem cursor-pointer remove-task-wrapper uil uil-trash-alt hover:text-danger" data-te-toggle="modal" data-te-target="#exampleModal" data-te-ripple-init data-te-ripple-color="light"></button>';
                    })
                    ->addColumn('task_taker', function($row) {
                        if (isset($row->user->name)) {
                            return $row->user->name;
                        } else {
                            return "User";  // Placeholder text
                        }
                    })
                    ->addColumn('completed_on', function($row) {
                        if ($row->status == "complete") {
                            return date('d/m/Y', strtotime($row->updated_at)) . ", " . date('H:i:s A', strtotime($row->updated_at));
                        } else {
                            return "Resume";  // Placeholder text
                        }
                    })
                    ->addColumn('percenatge', function($row) {
                        if (isset($row->student_percentage)) {
                            $studentpercentage = (float) $row->student_percentage ?? 0;
                            return round($studentpercentage,2) ?? 0;
                        } else {
                            return 0;  // Placeholder text
                        }
                    })
                    ->addColumn('ipadddress', function($row) {
                        return $row->userIp ?? 'N/A';
                    })
                    ->addColumn('status', function($row) {
                        // Determine the status color and text based on `status`
                        $statusColor = $row->student_percentage >= 60 ? 'success' : 'danger';
                        $statusText = $row->student_percentage >= 60 ? 'PASS' : 'FAIL';
                        // Create the status badge HTML
                        return "<span class='bg-{$statusColor}/10 capitalize font-medium inline-flex items-center justify-center min-h-[24px] px-3 rounded-[15px] text-{$statusColor} text-xs'>{$statusText}</span>";
                    })
                    ->rawColumns(['status', 'percenatge','ipadddress','completed_on', 'task_taker', 'action'])
                    ->make(true);
            }
            return view('manageLearning.practiceSet.practice-detailed-report',compact('practice'));
        }
    
        // Redirect if the practice is not found
        return redirect()->back()->with('error', 'Practice Not Found');
    }

    public function practiceReportDetail($uuid){
         // Check for permission
         if (!auth()->user()->can('pratice-set')) { // Assuming 'practice' is the required permission
            return redirect()->route('admin-dashboard')->with('error', 'You do not have permission to access this page.');
        }

        $practiceResult = PracticeSetResult::with('user','pratice')->where('uuid',$uuid)->first();
        if($practiceResult){
            $passPercentage = 60;
            return view('manageLearning.practiceSet.practice-report-detail',compact('practiceResult','passPercentage'));
        }
        return redirect()->back()->with('error', 'Practice Result Not Found');
    }

    public function deletePracticeResult($uuid){
        $schedule = PracticeSetResult::where('uuid',$uuid)->first();
        if ($schedule) {
            $schedule->delete();
            return redirect()->back()->with('success', 'Practice Removed Successfully');
        }
        
        return redirect()->back()->with('error', 'Something Went Wrong');
    }

}
