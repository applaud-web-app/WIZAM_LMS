<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Sections;
use App\Models\SubCategory;
use App\Models\Tags;
use App\Models\Exam;
use App\Models\PracticeSet;
use App\Models\Plan;
use App\Models\Quizze;

class ManageCategory extends Controller
{
    public function viewCategory(Request $request){
        if ($request->ajax()) {
            $sections = Category::whereIn('status',[0,1])->select(['id', 'name', 'created_at','description','status']);

            return DataTables::of($sections)
                ->addIndexColumn()
                ->addColumn('action', function ($section) {
                    $parms = "id=".$section->id;
                    $editUrl = encrypturl(route('edit-category'),$parms);
                    $deleteUrl = encrypturl(route('delete-category'),$parms);

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
        return view('manageCategory.category.view-category');
    }

    public function addCategory(Request $request){
        $request->validate([
            'category_name' => 'required|string|max:255',
            'category_description' => 'nullable|string|max:1000',
            'category_status' => 'required|string|in:1,0',
        ]);

        // Create Category
        Category::create([
            'name' => $request->category_name,
            'description' => $request->category_description, // Nullable
            'status' => $request->category_status,
        ]);

        // Redirect with success message
        return redirect()->route('view-category')->with('success', 'Category created successfully.');
    }

    public function editCategory(Request $request){
        $request->validate([
            'category_name' => 'required|string|max:255',
            'category_description' => 'nullable|string|max:1000',
            'category_status' => 'required|string|in:1,0',
        ]);

        $data = decrypturl($request->eq);
        $categoryId = $data['id'];
        $user = Category::where('id',$categoryId)->first();
        if($user){
            $user->name = $request->category_name; 
            $user->description = $request->category_description; 
            $user->status = $request->category_status;
            $user->save();
            return redirect()->back()->with('success','Category Update Successfully');
        }
        return redirect()->back()->with('error','Something Went Wrong');
    }

    public function deleteCategory(Request $request){
        $request->validate([
            'eq'=>'required'
        ]);

        $data = decrypturl($request->eq);
        $categoryId = $data['id'];
        $user = Category::where('id',$categoryId)->first();
        if($user){

            // CHECK IF CATEGORY IS LINK TO SUB CATEGORY 
            $subcategory = SubCategory::where('status',1)->where('category_id',$categoryId)->count();
            if($subcategory){
                return redirect()->back()->with('error','Unable to delete category as it is associated with '.$subcategory.' sub categories. Remove all associations and try again!');
            }
            $user->status = 2;
            $user->save();
            return redirect()->back()->with('success','Category Removed Successfully');
        }
        return redirect()->back()->with('error','Something Went Wrong');
    }

    // FOR SUBCATEGORY
    public function viewSubCategory(Request $request){
        if ($request->ajax()) {
            $sections = SubCategory::with('category')->whereIn('status',[0,1]);

            return DataTables::of($sections)
                ->addIndexColumn()
                ->addColumn('action', function ($section) {
                    $parms = "id=".$section->id;
                    $editUrl = encrypturl(route('edit-sub-category'),$parms);
                    $deleteUrl = encrypturl(route('delete-sub-category'),$parms);

                    return '
                        <button type="button" data-url="'.$editUrl.'" data-name="'.$section->name.'" data-description="'.$section->description.'" data-status="'.$section->status.'" data-type="'.$section->type.'" data-category="'.$section->category_id.'" data-sections="' . htmlspecialchars($section->sections, ENT_QUOTES)  . '"   class="editItem cursor-pointer edit-task-title uil uil-edit-alt hover:text-info" data-te-toggle="modal" data-te-target="#editModal" data-te-ripple-init data-te-ripple-color="light"></button>
                        <button type="button" data-url="'.$deleteUrl.'" class="deleteItem cursor-pointer remove-task-wrapper uil uil-trash-alt hover:text-danger"  data-te-toggle="modal" data-te-target="#exampleModal" data-te-ripple-init data-te-ripple-color="light"></button>';
                })
                ->addColumn('parent_category', function($row) {
                    if(isset($row->category)){
                        return $row->category->name;
                    }
                    return "----";
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
                ->rawColumns(['status','created_at','action','parent_category'])
                ->make(true);
        }
        $category = Category::select('name','id')->where('status',1)->get();
        $section = Sections::where('status',1)->get();
        return view('manageCategory.subCategory.view-sub-category',compact('category','section'));
    }

    public function addsubCategory(Request $request)
    {
        // Validate the request data
        $request->validate([
            'subcategory_name' => 'required|string|max:255',
            'parent_category' => 'required|integer',
            'subcategory_type' => 'required|string',
            'subcategory_description' => 'nullable|string|max:1000',
            'subcategory_status' => 'required|in:1,0',
            'map_section' => 'required|array'
        ]);
    
        // Exclude the first element from the map_section array
        $map = array_slice($request->map_section, 1);
    
        // Convert $map to JSON
        $sections = json_encode($map);
    
        // Attempt to create the subcategory
        try {
            SubCategory::create([
                'name' => $request->subcategory_name,
                'category_id' => $request->parent_category,
                'type' => $request->subcategory_type,
                'description' => $request->subcategory_description,
                'sections' => $sections, // Store JSON encoded data
                'status' => $request->subcategory_status,
            ]);
    
            // Redirect with success message
            return redirect()->route('view-sub-category')->with('success', 'Sub-Category created successfully.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to create sub-category: ' . $e->getMessage()]);
        }
    }
    

    public function editsubCategory(Request $request){
        $request->validate([
            'subcategory_name' => 'required|string|max:255',
            'parent_category' => 'required|integer',
            'subcategory_type' => 'required|string',
            'subcategory_description' => 'nullable|string|max:1000',
            'subcategory_status' => 'required|in:1,0',  // Ensure it's either '1' or '0'
            'map_section' => 'required|array'
        ]);

        // Process the map_section array to exclude the first element
        $map = [];
        foreach ($request->map_section as $key => $value) {
            if ($key > 0) {
                $map[] = $value;
            }
        }

        // Convert $map to JSON or handle it according to your database column type
        $sections = json_encode($map);

        $data = decrypturl($request->eq);
        $categoryId = $data['id'];
        $user = SubCategory::where('id',$categoryId)->first();
        if($user){
            $user->name = $request->subcategory_name; 
            $user->category_id = $request->parent_category; 
            $user->type = $request->subcategory_type; 
            $user->description = $request->subcategory_description; 
            $user->status = $request->subcategory_status;
            $user->sections = $sections;
            $user->save();
            return redirect()->back()->with('success','Sub-Category Update Successfully');
        }
        return redirect()->back()->with('error','Something Went Wrong');
    }

    public function deletesubCategory(Request $request) {
        // Validate the request data
        $request->validate([
            'eq' => 'required'
        ]);
    
        // Decrypt the URL parameter
        $data = decrypturl($request->eq);
        $categoryId = $data['id'];
        
        // Find the subcategory by ID
        $subCategory = SubCategory::find($categoryId);
        
        if ($subCategory) {
            // Check associations with exams, quizzes, practice sets, and plans
            $examCount = Exam::where('status',1)->where('subcategory_id', $categoryId)->count();
            $quizCount = Quizze::where('status',1)->where('subcategory_id', $categoryId)->count();
            $practiceSetsCount = PracticeSet::where('status',1)->where('subCategory_id', $categoryId)->count();
            $planCount = Plan::where('status',1)->where('category_id', $categoryId)->count();
    
            // Prepare an array to collect error messages if any associations exist
            $errors = [];
            
            if ($examCount > 0) {
                $errors[] = "$examCount exam";
            }
            if ($quizCount > 0) {
                $errors[] = "$quizCount quiz";
            }
            if ($practiceSetsCount > 0) {
                $errors[] = "$practiceSetsCount practice set";
            }
            if ($planCount > 0) {
                $errors[] = "$planCount plan";
            }
    
            // If there are associations, prevent deletion and return an error
            if (!empty($errors)) {
                $errorMessage = "Unable to delete subcategory as it is associated with: " . implode(', ', $errors) . ". Remove all associations and try again!";
                return redirect()->back()->with('error', $errorMessage);
            }
    
            // Mark the subcategory as deleted (soft delete)
            $subCategory->status = 2; // Assuming 2 indicates deletion
            $subCategory->save();
    
            return redirect()->back()->with('success', 'Sub-Category Removed Successfully');
        }
    
        return redirect()->back()->with('error', 'Something Went Wrong');
    }
    

    // FOR TAGS
    public function viewTags(Request $request){
        if ($request->ajax()) {
            $sections = Tags::whereIn('status',[0,1])->select(['id', 'name', 'created_at','status']);
            return DataTables::of($sections)
                ->addIndexColumn()
                ->addColumn('action', function ($section) {
                    $parms = "id=".$section->id;
                    $editUrl = encrypturl(route('edit-tags'),$parms);
                    $deleteUrl = encrypturl(route('delete-tags'),$parms);

                    return '
                        <button type="button" data-url="'.$editUrl.'" data-name="'.$section->name.'" data-status="'.$section->status.'" class="editItem cursor-pointer edit-task-title uil uil-edit-alt hover:text-info" data-te-toggle="modal" data-te-target="#editModal" data-te-ripple-init data-te-ripple-color="light"></button>
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
        return view('manageCategory.tags.view-tags');
    }

    public function addTags(Request $request){
        // Validate the request data
        $request->validate([
            'tag_name' => 'required|string|max:255',
            'tag_status' => 'required|in:1,0',  // Ensure it's either '1' or '0'
        ]);

        // Create the subcategory
        Tags::create([
            'name' => $request->tag_name,
            'status' => $request->tag_status,
        ]);

        // Redirect with success message
        return redirect()->route('view-tags')->with('success', 'Tags created successfully.');
    }


    
    public function editTags(Request $request){
        $request->validate([
            'tag_name' => 'required|string|max:255',
            'tag_status' => 'required|in:1,0',  // Ensure it's either '1' or '0'
        ]);

        $data = decrypturl($request->eq);
        $tagsId = $data['id'];
        $user = Tags::where('id',$tagsId)->first();
        if($user){
            $user->name = $request->tag_name; 
            $user->status = $request->tag_status;
            $user->save();
            return redirect()->back()->with('success','Tags Update Successfully');
        }
        return redirect()->back()->with('error','Something Went Wrong');
    }

    public function deleteTags(Request $request){
        $request->validate([
            'eq'=>'required'
        ]);

        $data = decrypturl($request->eq);
        $skillId = $data['id'];
        $user = Tags::where('id',$skillId)->first();
        if($user){
            $user->status = 2; // Delete
            $user->save();
            return redirect()->back()->with('success','Tags Removed Successfully');
        }
        return redirect()->back()->with('error','Something Went Wrong');
    }

}
