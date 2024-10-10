<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;
use App\Models\SubCategory;
use App\Models\Plan;

class PaymentController extends Controller
{
   public function viewPlans(Request $request){
         if ($request->ajax()) {
            $sections = Plan::with('category')->whereIn('status',[0,1]);

            return DataTables::of($sections)
               ->addIndexColumn()
               ->addColumn('action', function ($section) {
                  $parms = "id=".$section->id;
                  $editUrl = encrypturl(route('edit-plan'),$parms);
                  $deleteUrl = encrypturl(route('delete-plan'),$parms);

                  return '<a href="'.$editUrl.'" type="button" class="cursor-pointer edit-task-title uil uil-edit-alt hover:text-info" ></a>
                        <button type="button" data-url="'.$deleteUrl.'" class="deleteItem cursor-pointer remove-task-wrapper uil uil-trash-alt hover:text-danger"  data-te-toggle="modal" data-te-target="#exampleModal" data-te-ripple-init data-te-ripple-color="light"></button>';
               })
               ->addColumn('category', function($row) {
                  if (isset($row->category)) {
                     return $row->category->name;
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
               ->rawColumns(['status','category','action'])
               ->make(true);
      }
      return view('managePayment.plans.view-plans');
   }

   public function createPlan(){
      $subCategory = SubCategory::whereIn('status',[1,0])->get();
      return view('managePayment.plans.create-plans',compact('subCategory'));
   }

   public function savePlan(Request $request){
      // Validate incoming request data
      $validatedData = $request->validate([
         'category' => 'required|string',
         'plan_name' => 'required|string',
         'price_type' => 'required|string',
         'duration' => 'nullable|integer', // Assuming duration is optional
         'price' => 'required|numeric',
         'discount_percentage' => 'nullable|integer',
         'description' => 'nullable|string',
         'order' => 'nullable|integer',
         'feature_access' => 'required|boolean',
         // 'features' => 'array', 
         'features.*' => 'nullable|string',
         'popular' => 'required|string|in:1,0',
         'status' => 'required|string|in:1,0',
      ]);

      // Ensure features is always an array
      if (!empty($validatedData['features'])) {
         $validatedData['features'] = [0 => $validatedData['features']];
      } else {
         $validatedData['features'] = []; // Use an empty array if no features are provided
      }

      // Create a new instance of your model and fill it with the validated data
      $data = new Plan;
      $data->category_id = $validatedData['category'] ?? null;
      $data->name = $validatedData['plan_name'] ?? null;
      $data->price_type = $validatedData['price_type'] ?? null;  // MONTHLY // FIXED
      $data->duration = $validatedData['discount_percentage'] ?? null;
      $data->price = $validatedData['price'] ?? null;
      $data->discount = $validatedData['discount'] ?? null;
      $data->description = $validatedData['description'] ?? null;
      $data->sort_order = $validatedData['order'] ?? null;
      $data->feature_access = $validatedData['feature_access'] ?? null;
      $data->features = json_encode($validatedData['features']) ?? null;
      $data->popular = $validatedData['popular'] ?? null; 
      $data->status = $validatedData['status'] ?? null;
      $data->save();

      // Return a success response
     return redirect()->route('view-plans')->with('success','Plan Created Successfully');
   }

   public function editPlan(Request $request){
      $request->validate([
         'eq'=>'required'
      ]);

      $data = decrypturl($request->eq);
      $plan_id = $data['id'];
      $plan = Plan::where('id',$plan_id)->first();
      if($plan){
         $subCategory = SubCategory::whereIn('status',[1,0])->get();
         return view('managePayment.plans.edit-plans',compact('subCategory','plan'));
      }
      return redirect()->back()->with('error','Invalid Plan');
   }

   public function updatePlan(Request $request){
      $validatedData = $request->validate([
         'category' => 'required|string',
         'plan_name' => 'required|string',
         'price_type' => 'required|string',
         'duration' => 'nullable|integer', // Assuming duration is optional
         'price' => 'required|numeric',
         'discount_percentage' => 'nullable|integer',
         'description' => 'nullable|string',
         'order' => 'nullable|integer',
         'feature_access' => 'required|boolean',
         // 'features' => 'array', 
         'features.*' => 'nullable|string',
         'popular' => 'required|string|in:1,0',
         'status' => 'required|string|in:1,0',
         'eq'=>'required'
      ]);

      // Ensure features is always an array
      if (!empty($validatedData['features'])) {
         $validatedData['features'] = [0 => $validatedData['features']];
      } else {
         $validatedData['features'] = []; // Use an empty array if no features are provided
      }

      // Create a new instance of your model and fill it with the validated data
      $data = decrypturl($request->eq);
      $plan_id = $data['id'];
      $data = Plan::where('id',$plan_id)->first();
      if($data){
         $data->category_id = $validatedData['category'];
         $data->name = $validatedData['plan_name'];
         $data->price_type = $validatedData['price_type'];  // MONTHLY // FIXED
         $data->duration = $validatedData['duration'];
         $data->price = $validatedData['price'];
         $data->discount = $validatedData['discount_percentage'];
         $data->description = $validatedData['description'];
         $data->sort_order = $validatedData['order'];
         $data->feature_access = $validatedData['feature_access'];
         $data->features = json_encode($validatedData['features']);
         $data->popular = $validatedData['popular']; 
         $data->status = $validatedData['status'];
         $data->save();

         // Return a success response
        return redirect()->route('view-plans')->with('success','Plan Updated Successfully');
      }
      return redirect()->back()->with('error','Something Went Wrong');
   }

   public function deletePlan(Request $request){
      $request->validate([
         'eq'=>'required'
      ]);

      $data = decrypturl($request->eq);
      $plan_id = $data['id'];
      $user = Plan::where('id',$plan_id)->first();
      if($user){
            $user->status = 2; // Delete
            $user->save();
            return redirect()->back()->with('success','Plan Removed Successfully');
      }
      return redirect()->back()->with('error','Something Went Wrong');
   }


}
