<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Faq;
use App\Models\Blog;
use App\Models\ExamType;
use App\Models\BlogCategory;
use Illuminate\Support\Facades\Auth;
use App\Models\Pages;
use Illuminate\Support\Str; 
use Illuminate\Support\Facades\DB;

class CMSController extends Controller
{
    public function viewFaq(Request $request){
        
        if (!Auth()->user()->can('faq')) { 
            return redirect()->route('admin-dashboard')->with('error', 'You do not have permission to this page.');
        }

        if ($request->ajax()) {
            $sections = Faq::whereIn('status',[0,1])->select(['id', 'question', 'answer','status']);

            return DataTables::of($sections)
                ->addIndexColumn()
                ->addColumn('action', function ($section) {
                    $parms = "id=".$section->id;
                    $editUrl = encrypturl(route('edit-faq'),$parms);
                    $deleteUrl = encrypturl(route('delete-faq'),$parms);
                    return '
                        <button type="button" data-url="'.$editUrl.'" data-question="'.$section->question.'" data-answer="'.$section->answer.'" data-status="'.$section->status.'" class="editItem cursor-pointer edit-task-title uil uil-edit-alt hover:text-info" data-te-toggle="modal" data-te-target="#editModal" data-te-ripple-init data-te-ripple-color="light"></button>
                        <button type="button" data-url="'.$deleteUrl.'" class="deleteItem cursor-pointer remove-task-wrapper uil uil-trash-alt hover:text-danger"  data-te-toggle="modal" data-te-target="#exampleModal" data-te-ripple-init data-te-ripple-color="light"></button>';
                })
                ->addColumn('status', function($row) {
                    // Determine the status color and text based on `is_active`
                    $statusColor = $row->status == 1 ? 'success' : 'danger';
                    $statusText = $row->status == 1 ? 'Active' : 'Inactive';
                    // Create the status badge HTML
                    return $status = "<span class='bg-{$statusColor}/10 capitalize font-medium inline-flex items-center justify-center min-h-[24px] px-3 rounded-[15px] text-{$statusColor} text-xs'>{$statusText}</span>";
                })
                ->rawColumns(['status','action'])
                ->make(true);
        }
        return view('manageCms.view-faq');
    }

    public function addFaq(Request $request){
         
        if (!Auth()->user()->can('faq')) { 
            return redirect()->route('admin-dashboard')->with('error', 'You do not have permission to this page.');
        }

        $request->validate([
            'question' => 'required|string|max:255',
            'answer' => 'required|string|max:2000',
            'status' => 'required|string|in:1,0',
        ]);


        // Create Category
        Faq::create([
            'question' => $request->question,
            'answer' => $request->answer, 
            'status' => $request->status,
        ]);

        // Redirect with success message
        return redirect()->route('view-faq')->with('success', 'Faq created successfully.');
    }

    public function editFaq(Request $request){
        
        if (!Auth()->user()->can('faq')) { 
            return redirect()->route('admin-dashboard')->with('error', 'You do not have permission to this page.');
        }
        
        $request->validate([
            'question' => 'required|string|max:255',
            'answer' => 'required|string|max:2000',
            'status' => 'required|string|in:1,0',
        ]);

        $data = decrypturl($request->eq);
        $categoryId = $data['id'];
        $user = Faq::where('id',$categoryId)->first();
        if($user){
            $user->question = $request->question; 
            $user->answer = $request->answer; 
            $user->status = $request->status;
            $user->save();
            return redirect()->back()->with('success','Faq Update Successfully');
        }
        return redirect()->back()->with('error','Something Went Wrong');
    }

    public function deleteFaq(Request $request){
        
        if (!Auth()->user()->can('faq')) { 
            return redirect()->route('admin-dashboard')->with('error', 'You do not have permission to this page.');
        }
        
        $request->validate([
            'eq'=>'required'
        ]);

        $data = decrypturl($request->eq);
        $faqId = $data['id'];
        $user = Faq::where('id',$faqId)->first();
        if($user){
            $user->status = 2; // Delete
            $user->save();
            return redirect()->back()->with('success','Faq Removed Successfully');
        }
        return redirect()->back()->with('error','Something Went Wrong');
    }

    // FOR BLOG CATEGORY
    public function viewBlogCategory(Request $request){
        
        if (!Auth()->user()->can('blog')) { 
            return redirect()->route('admin-dashboard')->with('error', 'You do not have permission to this page.');
        }
 
        if ($request->ajax()) {
            $sections = BlogCategory::whereIn('status',[0,1]);

            return DataTables::of($sections)
                ->addIndexColumn()
                ->addColumn('action', function ($section) {
                    $parms = "id=".$section->id;
                    $editUrl = encrypturl(route('edit-blog-category'),$parms);
                    $deleteUrl = encrypturl(route('delete-blog-category'),$parms);
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
                ->rawColumns(['status','action','created_at'])
                ->make(true);
        }
        return view('manageCms.blog.view-blog-category');
    }

    public function addBlogCategory(Request $request){
        
        if (!Auth()->user()->can('blog')) { 
            return redirect()->route('admin-dashboard')->with('error', 'You do not have permission to this page.');
        }
 
        $request->validate([
            'blog_category' => 'required|string|max:255',
            'status' => 'required|string|in:1,0',
        ]);


        // Create Category
        BlogCategory::create([
            'name' => $request->blog_category,
            'status' => $request->status,
        ]);

        // Redirect with success message
        return redirect()->route('view-blog-category')->with('success', 'Blog Category Created successfully.');
    }

    public function editBlogCategory(Request $request){
        
        if (!Auth()->user()->can('blog')) { 
            return redirect()->route('admin-dashboard')->with('error', 'You do not have permission to this page.');
        }
 
        $request->validate([
            'blog_category' => 'required|string|max:255',
            'status' => 'required|string|in:1,0',
        ]);

        $data = decrypturl($request->eq);
        $blogCategoryId = $data['id'];
        $user = BlogCategory::where('id',$blogCategoryId)->first();
        if($user){
            $user->name = $request->blog_category; 
            $user->status = $request->status;
            $user->save();
            return redirect()->back()->with('success','Blog Category Update Successfully');
        }
        return redirect()->back()->with('error','Something Went Wrong');
    }

    public function deleteBlogCategory(Request $request){
        
        if (!Auth()->user()->can('blog')) { 
            return redirect()->route('admin-dashboard')->with('error', 'You do not have permission to this page.');
        }
 
        $request->validate([
            'eq'=>'required'
        ]);

        $data = decrypturl($request->eq);
        $blogCategoryId = $data['id'];
        $user = BlogCategory::where('id',$blogCategoryId)->first();
        if($user){

            // CHECK IF CATEGORY IS LINK TO SUB CATEGORY 
            $blog = Blog::where('status',1)->where('category_id',$blogCategoryId)->count();
            if($blog){
                return redirect()->back()->with('error','Unable to delete blog category as it is associated with '.$blog.' blog. Remove all associations and try again!');
            }
            
            $user->status = 2;
            $user->save();
            return redirect()->back()->with('success','Blog Category Removed Successfully');
        }
        return redirect()->back()->with('error','Something Went Wrong');
    }

    // FOR BLOG
    public function viewBlog(Request $request){
        
        if (!Auth()->user()->can('blog')) { 
            return redirect()->route('admin-dashboard')->with('error', 'You do not have permission to this page.');
        }
 
        if ($request->ajax()) {
            $sections = Blog::with('category')->whereIn('status',[0,1]);

            return DataTables::of($sections)
                ->addIndexColumn()
                ->addColumn('action', function ($section) {
                    $parms = "id=".$section->id;
                    $editUrl = encrypturl(route('edit-blog'),$parms);
                    $deleteUrl = encrypturl(route('delete-blog'),$parms);
                    return '
                        <a href="'.$editUrl.'" class="editItem cursor-pointer edit-task-title uil uil-edit-alt hover:text-info"></a>
                        <button type="button" data-url="'.$deleteUrl.'" class="deleteItem cursor-pointer remove-task-wrapper uil uil-trash-alt hover:text-danger"  data-te-toggle="modal" data-te-target="#exampleModal" data-te-ripple-init data-te-ripple-color="light"></button>';
                })
                ->addColumn('status', function($row) {
                    // Determine the status color and text based on `is_active`
                    $statusColor = $row->status == 1 ? 'success' : 'danger';
                    $statusText = $row->status == 1 ? 'Active' : 'Inactive';
                    // Create the status badge HTML
                    return $status = "<span class='bg-{$statusColor}/10 capitalize font-medium inline-flex items-center justify-center min-h-[24px] px-3 rounded-[15px] text-{$statusColor} text-xs'>{$statusText}</span>";
                })
                ->addColumn('images', function($row) {
                    if(isset($row->image)){
                        return "<img src='".$row->image."' class='img-fluid' height='200px' />";
                    }
                    return "";
                })
                ->addColumn('author', function($row) {
                    return $row->user;
                })
                ->addColumn('category', function($row) {
                    if(isset($row->category)){
                        return $row->category->name;
                    }
                    return "----";
                })
                ->addColumn('publish_date', function($row) {
                    return date('d/m/Y', strtotime($row->created_at));
                })
                ->rawColumns(['status','action','images','author','category','publish_date'])
                ->make(true);
        }
        return view('manageCms.blog.view-blog');
    }

    public function addBlog(){
        
        if (!Auth()->user()->can('blog')) { 
            return redirect()->route('admin-dashboard')->with('error', 'You do not have permission to this page.');
        }
 
        $blogCategory = BlogCategory::whereIn('status',[0,1])->get();
        return view('manageCms.blog.add-blog',compact('blogCategory'));
    }

    public function storeBlog(Request $request)
    {
        if (!Auth()->user()->can('blog')) { 
            return redirect()->route('admin-dashboard')->with('error', 'You do not have permission to this page.');
        }
 
        // Validate the request inputs
        $request->validate([
            'blogTitle' => 'required|string|max:255',
            'blogCategory' => 'required',
            'shortDescription' => 'required|string|max:500',
            'blogContent' => 'required|string',
            'authorName' => 'required',
            'blogImage' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Optional image validation
        ]);

        // Handle the image upload
        $imagePath = null;
        $baseUrl = env('APP_URL');
        if ($request->hasFile('blogImage')) {
            $image = $request->file('blogImage');
            
            // Generate a unique filename with the current timestamp
            $imageName = 'blog_' . time() . '.' . $image->getClientOriginalExtension();

            // Move the image to the 'public/blogs' directory
            $image->move(public_path('blogs'), $imageName);

            // Store the image path in the database (relative to the public directory)
            $imagePath = $imageName;
        }

        // Generate a slug from the blog title
        $slug = Str::slug($request->input('blogTitle'));

        // Ensure the slug is unique
        $originalSlug = $slug;
        $count = 1;
        while (Blog::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        // Save the blog data to the database
        Blog::create([
            'title' => $request->input('blogTitle'),
            'user'=> $request->input('authorName'),
            'category_id' => $request->input('blogCategory'),
            'short_description' => $request->input('shortDescription'),
            'content' => $request->input('blogContent'), // Save summernote content
            'image' => $baseUrl."/blogs/".$imagePath, // Save the image path if exists
            'slug' => $slug, // Save the unique slug
        ]);

        // Redirect to a page with a success message
        return redirect()->route('view-blog')->with('success', 'Blog created successfully!');
    }

    public function editBlog(Request $request){
        
        if (!Auth()->user()->can('blog')) { 
            return redirect()->route('admin-dashboard')->with('error', 'You do not have permission to this page.');
        }
 
        $request->validate([
            'eq'=>'required'
        ]);
        $data = decrypturl($request->eq);
        $blogId = $data['id'];
        $blogCategory = BlogCategory::whereIn('status',[0,1])->get();
        $blog = Blog::where('id',$blogId)->first();
        return view('manageCms.blog.edit-blog',compact('blogCategory','blog'));
    }

    public function updateBlog(Request $request)
    {
        
        if (!Auth()->user()->can('blog')) { 
            return redirect()->route('admin-dashboard')->with('error', 'You do not have permission to this page.');
        }
 
        // Validate the request inputs
        $request->validate([
            'blogTitle' => 'required|string|max:255',
            'blogCategory' => 'required',
            'shortDescription' => 'required|string|max:500',
            'blogContent' => 'required|string',
            'authorName' => 'required',
            'blogImage' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Optional image validation
            'eq' => 'required',
        ]);
    
        $userId = Auth::id();
        $data = decrypturl($request->eq);
        $blogId = $data['id'];
    
        $blog = Blog::findOrFail($blogId); // Use findOrFail to handle the case where the blog does not exist
    
        // Handle the image upload
        $baseUrl = env('APP_URL');
        if ($request->hasFile('blogImage')) {
            $image = $request->file('blogImage');
            
            // Generate a unique filename with the current timestamp
            $imageName = 'blog_' . time() . '.' . $image->getClientOriginalExtension();
    
            // Move the image to the 'public/blogs' directory
            $image->move(public_path('blogs'), $imageName);
    
            // Store the image path in the database (relative to the public directory)
            $imagePath = $imageName;
            $blog->image = $baseUrl."/blogs/".$imagePath;
        }
    
        // Update blog data
        $blog->title = $request->input('blogTitle');
        $blog->category_id = $request->input('blogCategory');
        $blog->short_description = $request->input('shortDescription');
        $blog->content = $request->input('blogContent');
        $blog->user = $request->input('authorName');
    
        // Generate a new slug from the blog title
        $slug = Str::slug($request->input('blogTitle'));
    
        // Ensure the slug is unique
        $originalSlug = $slug;
        $count = 1;
    
        // Check if the slug already exists in other blogs (excluding the current blog)
        while (Blog::where('slug', $slug)->where('id', '!=', $blogId)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }
    
        // Update the slug
        $blog->slug = $slug;
    
        // Save the updated blog data
        $blog->save();
    
        // Redirect to a page with a success message
        return redirect()->route('view-blog')->with('success', 'Blog updated successfully!');
    }

    public function deleteBlog(Request $request){
        
        if (!Auth()->user()->can('blog')) { 
            return redirect()->route('admin-dashboard')->with('error', 'You do not have permission to this page.');
        }
 
        $request->validate([
            'eq'=>'required'
        ]);

        $data = decrypturl($request->eq);
        $blogId = $data['id'];
        $user = Blog::where('id',$blogId)->first();
        if($user){
            $user->status = 2; // Delete
            $user->save();
            return redirect()->back()->with('success','Blog Removed Successfully');
        }
        return redirect()->back()->with('error','Something Went Wrong');
    }

    // Dynmaic Pages
    public function viewPages(Request $request){
        
        if (!Auth()->user()->can('pages')) { 
            return redirect()->route('admin-dashboard')->with('error', 'You do not have permission to this page.');
        }

        if ($request->ajax()) {
            $sections = Pages::whereIn('status',[0,1]);

            return DataTables::of($sections)
                ->addIndexColumn()
                ->addColumn('action', function ($section) {
                    $parms = "id=".$section->id;
                    $editUrl = encrypturl(route('edit-page'),$parms);
                    $deleteUrl = encrypturl(route('delete-page'),$parms);
                    return '
                        <a href="'.$editUrl.'" class="cursor-pointer edit-task-title uil uil-edit-alt hover:text-info"></a>
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
                ->rawColumns(['status','action','created_at'])
                ->make(true);
        }
        return view('manageCms.pages.view-pages');
    }

    public function addPage(){
        if (!Auth()->user()->can('pages')) { 
            return redirect()->route('admin-dashboard')->with('error', 'You do not have permission to this page.');
        }
        return view('manageCms.pages.add-page');
    }

    public function storePage(Request $request){
        if (!Auth()->user()->can('pages')) { 
            return redirect()->route('admin-dashboard')->with('error', 'You do not have permission to this page.');
        }

        // Validate the incoming request data
        $request->validate([
            'page_title' => 'required|string|max:255',
            'description' => 'required|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:255',
            'meta_keywords' => 'nullable|string|max:255',
            'status'=>'required'
        ]);

        // Create a new Page instance
        $page = new Pages();
        
        // Set the properties
        $page->title = $request->input('page_title');
        
        // Generate a unique slug
        $slug = \Str::slug($request->input('page_title'));
        $originalSlug = $slug; // Store the original slug for reference
        $count = 1;

        // Check for uniqueness
        while (Pages::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count; // Append a number to the slug
            $count++;
        }

        $page->slug = $slug; // Set the unique slug
        $page->description = $request->input('description');
        $page->meta_title = $request->input('meta_title');
        $page->meta_description = $request->input('meta_description');
        $page->meta_keywords = $request->input('meta_keywords');
        $page->status = $request->input('status'); // Default to active status

        // Save the Page to the database
        $page->save();

        // Redirect or return a response
        return redirect()->route('view-pages')->with('success', 'Page created successfully.');
    }

    public function editPages(Request $request){
        if (!Auth()->user()->can('pages')) { 
            return redirect()->route('admin-dashboard')->with('error', 'You do not have permission to this page.');
        }
        $request->validate([
            'eq'=>'required'
        ]);
        $data = decrypturl($request->eq);
        $blogId = $data['id'];
        $page = Pages::where('id',$blogId)->first();
        if($page){
            return view('manageCms.pages.edit-page',compact('page'));
        }
        return redirect()->back()->with('error','Something Went Wrong');
    }

    public function updatePage(Request $request){
        if (!Auth()->user()->can('pages')) { 
            return redirect()->route('admin-dashboard')->with('error', 'You do not have permission to this page.');
        }
        $request->validate([
            'page_title' => 'required|string|max:255',
            'description' => 'required|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:255',
            'meta_keywords' => 'nullable|string|max:255',
            'status'=>'required',
            'eq'=>'required'
        ]);

        $data = decrypturl($request->eq);
        $pageId = $data['id'];
        $page = Pages::find($pageId);
        if (!$page) {
            return redirect()->back()->with('error', 'Page not found.');
        }
        
        
        // Update the page details
        $page->title = $request->input('page_title');
        
        // Generate a new slug and ensure it's unique
        $slug = \Str::slug($request->input('page_title'));
        $originalSlug = $slug; // Store the original slug for reference
        $count = 1;

        // Check for uniqueness and modify the slug if necessary
        while (Pages::where('slug', $slug)->where('id', '!=', $pageId)->exists()) {
            $slug = $originalSlug . '-' . $count; // Append a number to the slug
            $count++;
        }

        $page->slug = $slug; // Set the unique slug
        $page->description = $request->input('description');
        $page->meta_title = $request->input('meta_title');
        $page->meta_description = $request->input('meta_description');
        $page->meta_keywords = $request->input('meta_keywords');
        $page->status = $request->input('status'); // Ensure the status is being updated correctly

        // Save the updated page to the database
        $page->save();

        // Redirect to the pages index with a success message
        return redirect()->route('view-pages')->with('success', 'Page updated successfully.');

    }

    public function deletePage(Request $request){
        if (!Auth()->user()->can('pages')) { 
            return redirect()->route('admin-dashboard')->with('error', 'You do not have permission to this page.');
        }
        $request->validate([
            'eq'=>'required'
        ]);

        $data = decrypturl($request->eq);
        $blogId = $data['id'];
        $user = Pages::where('id',$blogId)->first();
        if($user){
            $user->status = 2; // Delete
            $user->save();
            return redirect()->back()->with('success','Page Removed Successfully');
        }
        return redirect()->back()->with('error','Something Went Wrong');
    }

}
