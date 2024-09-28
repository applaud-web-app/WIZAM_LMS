<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;
use App\Models\Sections;
use App\Models\Skill;
use App\Models\Topic;

class ManageSubject extends Controller
{
    public function viewSections(Request $request){

        // Check if the user has permission to access sections
        // if (!userHasPermission('sections')) {
        //     return redirect()->back()->with('error', 'You do not have permission to access the page');
        // }
        if ($request->ajax()) {
            $sections = Sections::whereIn('status',[0,1])->select(['id', 'name', 'created_at','description','status']);

            return DataTables::of($sections)
                ->addIndexColumn()
                ->addColumn('action', function ($section) {
                    $parms = "id=".$section->id;
                    $editUrl = encrypturl(route('edit-section'),$parms);
                    $deleteUrl = encrypturl(route('delete-section'),$parms);

                    return '
                        <button type="button" data-url="'.$editUrl.'" data-name="'.$section->name.'" data-description="'.$section->description.'" data-status="'.$section->status.'" class="editItem cursor-pointer edit-task-title uil uil-edit-alt hover:text-info" data-te-toggle="modal" data-te-target="#editModal" data-te-ripple-init data-te-ripple-color="light"></button>
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
        return view('manageSubject.section.view-section');
    }

    public function addSection(Request $request){
        // Validation rules
        $request->validate([
            'section_name' => 'required|string|max:255',
            'section_description' => 'nullable|string|max:1000',
            'status' => 'required|string|in:1,0',
        ]);

        // Create section
        Sections::create([
            'name' => $request->section_name,
            'description' => $request->section_description, // Nullable
            'status' => $request->status,
        ]);

        // Redirect with success message
        return redirect()->route('view-sections')->with('success', 'Section created successfully.');
    }

    public function editSection(Request $request){
        $request->validate([
            'section_name' => 'required|string|max:255',
            'section_description' => 'nullable|string|max:1000',
            'status' => 'required|string|in:1,0',
            'eq'=>'required'
        ]);

        $data = decrypturl($request->eq);
        $sectionId = $data['id'];
        $user = Sections::where('id',$sectionId)->first();
        if($user){
            $user->name = $request->section_name; 
            $user->description = $request->section_description; 
            $user->status = $request->status;
            $user->save();
            return redirect()->back()->with('success','Section Update Successfully');
        }
        return redirect()->back()->with('error','Something Went Wrong');

    }

    public function deleteSection(Request $request){
        $request->validate([
            'eq'=>'required'
        ]);

        $data = decrypturl($request->eq);
        $sectionId = $data['id'];
        $user = Sections::where('id',$sectionId)->first();
        if($user){
            $user->status = 2; // Delete
            $user->save();
            return redirect()->back()->with('success','Section Removed Successfully');
        }
        return redirect()->back()->with('error','Something Went Wrong');
    }

    // FOR SKILLS 
    public function viewSkills(Request $request){

        if ($request->ajax()) {
            $sections = Skill::with('section')->whereIn('status',[0,1])->select(['id', 'name', 'created_at','section_id','status','description']);

            return DataTables::of($sections)
                ->addIndexColumn()
                ->addColumn('section', function($row) {
                    if(isset($row->section)){
                        return $row->section->name;
                    }
                    return "----";
                })
                ->addColumn('action', function ($section) {
                    $parms = "id=".$section->id;
                    $editUrl = encrypturl(route('edit-skill'),$parms);
                    $deleteUrl = encrypturl(route('delete-skill'),$parms);
                    return '
                        <button type="button" data-url="'.$editUrl.'" data-name="'.$section->name.'" data-description="'.$section->description.'" data-status="'.$section->status.'" data-section="'.$section->section_id.'" class="editItem cursor-pointer edit-task-title uil uil-edit-alt hover:text-info" data-te-toggle="modal" data-te-target="#editModal" data-te-ripple-init data-te-ripple-color="light"></button>
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
                ->rawColumns(['status','created_at','action','section'])
                ->make(true);
        }

        $sections = Sections::select('id','name')->whereIn('status',[1,0])->get();
        return view('manageSubject.skills.view-skill',compact('sections'));
    }

    public function addSkill(Request $request){
        // Validation rules
        $request->validate([
            'skill_name' => 'required|string|max:255',
            'skill_section'=>'required',
            'skill_description' => 'nullable|string|max:1000',
            'skill_status' => 'required|string|in:1,0',
        ]);

        // Create section
        Skill::create([
            'name' => $request->skill_name,
            'section_id' => $request->skill_section,
            'description' => $request->skill_description, 
            'status' => $request->skill_status,
        ]);

        // Redirect with success message
        return redirect()->route('view-skills')->with('success', 'Skill created successfully.');
    }

    public function editSkill(Request $request){
        $request->validate([
            'skill_name' => 'required|string|max:255',
            'skill_section'=>'required',
            'skill_description' => 'nullable|string|max:1000',
            'skill_status' => 'required|string|in:1,0',
            'eq'=>'required'
        ]);

        $data = decrypturl($request->eq);
        $skillId = $data['id'];
        $user = Skill::where('id',$skillId)->first();
        if($user){
            $user->name = $request->skill_name; 
            $user->section_id = $request->skill_section;
            $user->description = $request->skill_description; 
            $user->status = $request->skill_status;
            $user->save();
            return redirect()->back()->with('success','Skill Updated Successfully');
        }
        return redirect()->back()->with('error','Something Went Wrong');

    }


    public function deleteSkill(Request $request){
        $request->validate([
            'eq'=>'required'
        ]);

        $data = decrypturl($request->eq);
        $skillId = $data['id'];
        $user = Skill::where('id',$skillId)->first();
        if($user){
            $user->status = 2; // Delete
            $user->save();
            return redirect()->back()->with('success','Skill Removed Successfully');
        }
        return redirect()->back()->with('error','Something Went Wrong');
    }

    // FOR TOPIC 
    public function viewTopics(Request $request){
        if ($request->ajax()) {
            $sections = Topic::with('skill')->whereIn('status',[0,1])->select(['id', 'name', 'created_at','skill_id','status','description']);

            return DataTables::of($sections)
                ->addIndexColumn()
                ->addColumn('section', function($row) {
                    if(isset($row->skill)){
                        return $row->skill->name;
                    }
                    return "----";
                })
                ->addColumn('action', function ($section) {
                    $parms = "id=".$section->id;
                    $editUrl = encrypturl(route('edit-topic'),$parms);
                    $deleteUrl = encrypturl(route('delete-topic'),$parms);
                    return '
                        <button type="button" data-url="'.$editUrl.'" data-name="'.$section->name.'" data-description="'.$section->description.'" data-status="'.$section->status.'" data-skill="'.$section->skill_id.'" class="editItem cursor-pointer edit-task-title uil uil-edit-alt hover:text-info" data-te-toggle="modal" data-te-target="#editModal" data-te-ripple-init data-te-ripple-color="light"></button>
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
                ->rawColumns(['status','created_at','action','section'])
                ->make(true);
        }

        $skills = Skill::select('id','name')->whereIn('status',[1,0])->get();
        return view('manageSubject.topics.view-topic',compact('skills'));
    }

    public function addTopic(Request $request){
        // Validation rules
        $request->validate([
            'topic_name' => 'required|string|max:255',
            'topic_skill'=>'required',
            'topic_description' => 'nullable|string|max:1000',
            'topic_status' => 'required|string|in:1,0',
        ]);

        // Create section
        Topic::create([
            'name' => $request->topic_name,
            'skill_id' => $request->topic_skill,
            'description' => $request->topic_description, 
            'status' => $request->topic_status,
        ]);

        // Redirect with success message
        return redirect()->route('view-topics')->with('success', 'Topic created successfully.');
    }


    public function editTopic(Request $request){
        $request->validate([
            'topic_name' => 'required|string|max:255',
            'topic_skill'=>'required',
            'topic_description' => 'nullable|string|max:1000',
            'topic_status' => 'required|string|in:1,0',
            'eq'=>'required'
        ]);

        $data = decrypturl($request->eq);
        $topicId = $data['id'];
        $user = Topic::where('id',$topicId)->first();
        if($user){
            $user->name = $request->topic_name; 
            $user->skill_id = $request->topic_skill;
            $user->description = $request->topic_description; 
            $user->status = $request->topic_status;
            $user->save();
            return redirect()->back()->with('success','Topic Updated Successfully');
        }
        return redirect()->back()->with('error','Something Went Wrong');

    }


    public function deleteTopic(Request $request){
        $request->validate([
            'eq'=>'required'
        ]);

        $data = decrypturl($request->eq);
        $skillId = $data['id'];
        $user = Topic::where('id',$skillId)->first();
        if($user){
            $user->status = 2; // Delete
            $user->save();
            return redirect()->back()->with('success','Topic Removed Successfully');
        }
        return redirect()->back()->with('error','Something Went Wrong');
    }


}