<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\UserGroup;
use App\Models\GroupUsers;
use App\Models\User;
use App\Models\Role;
use App\Models\Country;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Validation\ValidationException;
use App\Imports\UsersImport;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{
    public function userGroups(Request $request) {
        // Check if the user has permission to access user groups
        if (!userHasPermission('user-groups')) {
            return redirect()->back()->with('error', 'You do not have permission to access the page');
        }
    
        if ($request->ajax()) {
            // Fetch data for DataTables
            $data = UserGroup::where('is_deleted',0)->orderBy('id','DESC'); // Specify columns you need
        
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('status', function($row) {
                    // Determine the status color and text based on `is_active`
                    $statusColor = $row->is_active == 1 ? 'success' : 'danger';
                    $statusText = $row->is_active == 1 ? 'Active' : 'Inactive';

                    // Create the status badge HTML
                    $status = "<span class='bg-{$statusColor}/10 capitalize font-medium inline-flex items-center justify-center min-h-[24px] px-3 rounded-[15px] text-{$statusColor} text-xs'>{$statusText}</span>";

                    // Add the "Free" badge if applicable
                    $free = $row->is_free == 1 ? '<span class="bg-primary/10 capitalize font-medium inline-flex items-center justify-center min-h-[24px] px-3 rounded-[15px] text-primary text-xs ml-1">Free</span>' : '';

                    // Return the combined HTML
                    return "{$status} {$free}";
                })
                ->addColumn('visibility', function($row) {
                    $visibility = $row->is_public == 1 ? "Public Group" : "Private Group";
                    return '<span>'.$visibility.'</span>';
                })
                ->addColumn('created_at', function($row) {
                    return date('d/m/Y', strtotime($row->created_at));
                })
                ->addColumn('action', function($row) {
                    // Customize action buttons as needed
                    return '<div class="text-light dark:text-subtitle-dark text-[19px] flex items-center justify-start p-0 m-0 gap-[20px]">
                            <a href="javascript:void(0)" data-name="'.$row->name.'" data-description="'.$row->description.'" data-free="'.$row->is_free.'" data-active="'.$row->is_active.'" data-visibility="'.$row->is_public.'" data-id="'.$row->id.'" class="editItem cursor-pointer edit-task-title uil uil-edit-alt hover:text-info" data-te-toggle="modal" data-te-target="#editexampleModal" data-te-ripple-init
                           data-te-ripple-color="light"></a>
                            <a href="javascript:void(0)" data-id="'.$row->id.'" class="deleteItem cursor-pointer remove-task-wrapper uil uil-trash-alt hover:text-danger"  data-te-toggle="modal" data-te-target="#exampleModalConfirm" data-te-ripple-init
                           data-te-ripple-color="light"></a>
                        </div>';
                })
                ->rawColumns(['status', 'visibility', 'created_at', 'action'])
                ->make(true);
        }
        
    
        // Load the view when not an AJAX request
        return view('manageUsers.userGroups.user-groups');
    }

    public function userGroupDelete(Request $request){
        try {
            $request->validate([
                'id'=>'required'
            ]);
        } catch (ValidationException $e) {
            // Handle the validation exception
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid Request'
            ]);
        }

        if (!userHasPermission('user-groups')) {
            return response()->json([
                'status'=>"error",
                'message'=>'You do not have permission to access the page'
            ]);
        }

        $userGroup = UserGroup::where('id',$request->id)->first(); 
        if($userGroup){
            $userGroup->is_deleted = 1;
            $userGroup->save();
    
            return response()->json([
                'status'=>"success",
                'message'=>'Group Removed Successfully'
            ]);
        }

        return response()->json([
            'status'=>"error",
            'message'=>'Something Went Wrong'
        ]);
    }
    

    public function addNewGroup(Request $request){
        try {
            $request->validate([
                'name'=>'required',
                'desciption'=>'nullable|maxlength:1000'
            ]);
        } catch (\ValidationException $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ]);
        }

        if (!userHasPermission('user-groups')) {
            return response()->json([
                'status'=>"error",
                'message'=>'You do not have permission to access the page'
            ]);
        }

        $data = $request->all();
        $data['is_active'] = 0;
        $data['is_public'] = 1;
        $data['is_free'] = 1;
        if($request->has('status')){
            $data['is_active'] = 1;
        }
        if($request->has('visibility')){
            $data['is_public'] = 0;
        }
        if($request->has('is_free')){
            $data['is_free'] = 0;
        }
        $newgroup = UserGroup::create($data);
        if($newgroup){
            return response()->json([
                'status' => 'success',
                'message' => "New Group Created Successfully"
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => "Something Went Wrong"
        ]);
    }

    public function updateGroupData(Request $request) {
        try {
            $request->validate([
                'name' => 'required',
                'description' => 'nullable|max:1000', // Fixed validation rule name
                'id' => 'required|exists:user_groups,id' // Ensure the ID exists in the database
            ]);
        } catch (\Illuminate\Validation\ValidationException $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ]);
        }

        if (!userHasPermission('user-groups')) {
            return response()->json([
                'status'=>"error",
                'message'=>'You do not have permission to access the page'
            ]);
        }
    
        $newgroup = UserGroup::find($request->id);
        if ($newgroup) {
            // Get all request data except the ID
            $data = $request->except(['id']);
            
            // Set default values for fields
            $data['is_active'] = 0;
            $data['is_public'] = 1;
            $data['is_free'] = 1;
            
            // Update fields based on request parameters
            if ($request->has('status')) {
                $data['is_active'] = 1;
            }
            if ($request->has('visibility')) {
                $data['is_public'] = 0;
            }
            if ($request->has('is_free')) {
                $data['is_free'] = 0;
            }
            
            $newgroup->update($data);
    
            return response()->json([
                'status' => 'success',
                'message' => "Group Updated Successfully"
            ]);
        }
    
        return response()->json([
            'status' => 'error',
            'message' => "Group not found"
        ]);
    }


    // USERS //
    public function viewUsers(Request $request){
        // Check if the user has permission to access user groups
        if (!userHasPermission('users')) {
            return redirect()->back()->with('error', 'You do not have permission to access the page');
        }

        if ($request->ajax()) {
            // Fetch data for DataTables
            $data = User::where('id','!=',1)->whereIn('status',[0,1])->with('countries')->orderBy('id','DESC'); // Specify columns you need
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('status', function($row) {
                    // Determine the status color and text based on `is_active`
                    $statusColor = $row->is_active == 0 ? 'success' : 'danger';
                    $statusText = $row->is_active == 0 ? 'Active' : 'Inactive';
                    // Create the status badge HTML
                    return $status = "<span class='bg-{$statusColor}/10 capitalize font-medium inline-flex items-center justify-center min-h-[24px] px-3 rounded-[15px] text-{$statusColor} text-xs'>{$statusText}</span>";
                })
                ->addColumn('role', function($row) {
                    return isset($row->roles) && $row->roles->isNotEmpty() 
                    ? ucfirst($row->roles->first()->name) 
                    : 'No Role';                
                })
                ->addColumn('dob', function($row) {
                    return date('d/m/Y', strtotime($row->dob));
                })
                ->addColumn('country', function($row) {
                    return isset($row->countries) ? $row->countries->name : 'No Country';
                })
                ->addColumn('action', function($row) {
                    $parms = "id=".$row->id;
                    $editUrl = encrypturl(route('edit-user-details'),$parms);
                    $deleteUrl = encrypturl(route('delete-user-data'),$parms);

                    // Customize action buttons as needed
                    return '<div class="text-light dark:text-subtitle-dark text-[19px] flex items-center justify-start p-0 m-0 gap-[20px]">
                            <a href="'.$editUrl.'" class="editItem cursor-pointer edit-task-title uil uil-edit-alt hover:text-info"></a>
                            <button type="button" data-url="'.$deleteUrl.'" class="deleteItem cursor-pointer remove-task-wrapper uil uil-trash-alt hover:text-danger" data-te-toggle="modal" data-te-target="#exampleModal" data-te-ripple-init data-te-ripple-color="light"></button> 
                        </div>';
                })
                ->rawColumns(['status', 'role', 'dob','country','action'])
                ->make(true);
        }

        // Load the view when not an AJAX request
        return view('manageUsers.users.view-users');
    }


    public function addUsers(){
        $roles = Role::get();
        $userGroups = UserGroup::where(['is_active'=>1,'is_deleted'=>0])->get();
        $country = Country::orderBy('name','ASC')->get();
        return view('manageUsers.users.add-users',compact('roles','userGroups','country'));
    }

    public function storeUserDetails(Request $request)
    {
        // Validate the request data
        $request->validate([
            'full_name' => 'required|string|min:3',
            'dob' => 'required|date',
            'nationality' => 'required|string',
            'phone' => 'nullable|string',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|string',
            'groups' => 'required|array',
            'password' => 'required|string|min:8|confirmed',
            // 'email_verified' => 'required|string|in:yes,no',
            'status' => 'required|string|in:1,0',
        ]);
    
        // Begin transaction
        \DB::beginTransaction();
    
        try {
            // Create the user
            $user = User::create([
                'title' => $request->title ?? null, // Use null coalescing to handle optional 'title'
                'name' => $request->full_name,
                'dob' => $request->dob,
                'country' => $request->nationality,
                'phone_number' => $request->phone,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                // 'email_verified_at' => $request->email_verified === 'yes' ? now() : null,
                'status' => $request->status,
            ]);
    
            // Attach groups to the user
            foreach ($request->groups as $key => $groupId) {
                if($key > 0){
                    GroupUsers::create([
                        'group_id' => $groupId,
                        'user_id' => $user->id,
                    ]);
                }
            }
    
            // Assign roles
            $user->assignRole($request->role);
    
            // Commit transaction
            \DB::commit();
    
            // Redirect with success message
            return redirect()->route('users')->with('success', 'User created successfully.');
    
        } catch (\Throwable $th) {
            // Rollback transaction on failure
            \DB::rollback();
    
            // Redirect with error message
            return redirect()->route('add-users')->with('error', $th->getMessage());
        }
    }

    public function editUserdetails(Request $request)
    {
        $data = decrypturl($request->eq);
        if (isset($data['id'])) {
            $roles = Role::get();
            $userGroups = UserGroup::where(['is_active' => 1, 'is_deleted' => 0])->get();
            $country = Country::orderBy('name', 'ASC')->get();

            // Get the user with their associated groups
            $user = User::with('groupUsers')->where('id', $data['id'])->first();

            // Extract group_id values for the selected groups
            $groups = $user->groupUsers->pluck('group_id')->toArray();

            return view('manageUsers.users.edit-users', compact('roles', 'userGroups', 'country', 'user', 'groups'));
        }

        return redirect()->route('users')->with('error', 'Something Went Wrong');
    }


    public function updateUserDetails(Request $request)
    {
        // Validate the request data
        $request->validate([
            'full_name' => 'required|string|min:3',
            'dob' => 'required|date',
            'nationality' => 'required|string',
            'phone' => 'nullable|string',
            'email' => 'required|email',
            'role' => 'required|string',
            'groups' => 'required|array',
            // 'email_verified' => 'required|string|in:yes,no',
            'status' => 'required|string|in:1,0',
            'password' => 'nullable|string|min:6|confirmed', // Validate password and confirmation
            'eq' => 'required'
        ]);

        // Begin transaction
        \DB::beginTransaction();

        try {
            $data = decrypturl($request->eq);
            $userId = $data['id'];

            // VERIFY EMAIL IS UNIQUE OR NOT
            $emailVerify = User::where('email',$request->email)->where('id','!=',$userId)->first();
            if($emailVerify){
                return redirect()->back()->with('error',"This email already exist");
            }

            // Fetch the user
            $user = User::findOrFail($userId);

            // Update user details
            $user->update([
                'title' => $request->title ?? null,
                'name' => $request->full_name,
                'dob' => $request->dob,
                'country' => $request->nationality,
                'phone_number' => $request->phone,
                'email' => $request->email,
                // 'email_verified_at' => $request->email_verified === 'yes' ? now() : null,
                'status' => $request->status,
            ]);

            // Update password only if provided
            if ($request->filled('password')) {
                $user->update([
                    'password' => Hash::make($request->password),
                ]);
            }

            // Sync user groups by deleting existing and inserting new ones
            GroupUsers::where('user_id', $user->id)->delete();

            foreach ($request->groups as $key => $groupId) {
                if($key > 0){
                    GroupUsers::create([
                        'group_id' => $groupId,
                        'user_id' => $user->id,
                    ]);
                }
            }

            // Sync roles
            $user->syncRoles($request->role);

            // Commit transaction
            \DB::commit();

            // Redirect with success message
            return redirect()->route('users')->with('success', 'User updated successfully.');

        } catch (\Throwable $th) {
            // Rollback transaction on failure
            \DB::rollback();

            // Redirect with error message
            return redirect()->back()->with('error', $th->getMessage());
        }
    }


    public function deleteUserData(Request $request){
        $request->validate([
            'eq'=>'required'
        ]);

        $data = decrypturl($request->eq);
        $userId = $data['id'];
        $user = User::where('id',$userId)->first();
        if($user){
            $user->status = 2; // Delete
            $user->save();
            return redirect()->back()->with('success','User Deleted Successfully');
        }
        return redirect()->back()->with('error','Something Went Wrong');
    }
    
    // For Import User

    public function showImportForm(){
        return view('manageUsers.importUsers.import');
    }


    public function importUser(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        Excel::import(new UsersImport, $request->file('file'));

        return back()->with('success', 'Users imported successfully.');
    }
}
