<?php

namespace App\Http\Controllers\admin;

use Yajra\DataTables\Facades\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Support\Str; 

class PermissionController extends Controller
{
    public function userRole(Request $request)
    {
        if ($request->ajax()) {
            // Fetch roles with their associated permissions
            $sections = Role::with('permissions')->where('status',1)->latest();
            
            return DataTables::of($sections)
                ->addIndexColumn()
    
                // Action column: edit and delete buttons with encrypted URLs
                ->addColumn('action', function ($section) {
                    $parms = "id=" . $section->id;
                    $editUrl = encrypturl(route('update-role-permission'), $parms);  // Adjust route as per your actual route
    
                    // Convert permissions to an array of permission names
                    $permissions = $section->permissions->pluck('name')->toArray();
                    $permissionsJson = json_encode($permissions);
                    return '
                        <button type="button" data-url="' . $editUrl . '" data-permissions=\'' . $permissionsJson . '\' class="editItem cursor-pointer uil uil-edit-alt hover:text-info" data-te-toggle="modal" data-te-target="#editModal" data-te-ripple-init data-te-ripple-color="light"></button>';
                })
    
                // Permission column: display permissions with colored badges
                ->addColumn('permission', function($row) {
                    $permissionList = ""; // Initialize empty string to build permission badges
                    
                    foreach ($row->permissions as $permission) {
                        $statusText = ucfirst(Str::replace('-', ' ', $permission->name)); // Use permission name directly

                        // Build the HTML badge for each permission
                        $permissionList .= "<span class='bg-primary/10 capitalize font-medium inline-flex items-center justify-center min-h-[24px] px-3 rounded-[15px] text-primary text-xs mr-1'>{$statusText}</span>";
                    }
    
                    return $permissionList;
                })
                ->addColumn('name', function ($section) {
                    return ucfirst($section->name);
                })
                // Allow HTML rendering for action and permission columns
                ->rawColumns(['permission', 'action','name'])
                ->make(true);
        }
    
        // Fetch all permissions, grouped by their section
        $permissions = Permission::all()->groupBy('section');
        return view('manageRole.view-role',compact('permissions'));
    }

    public function editRole(Request $request)
    {
        // Validate the incoming request to ensure 'id' is present and is a valid integer
        $request->validate([
            'id' => 'required|integer|exists:roles,id', // Ensure role ID exists
        ]);
    
        // Retrieve the role with its associated permissions
        $role = Role::with('permissions')->findOrFail($request->id);
        
        // Return the role's permissions
        return response()->json($role->permissions);
    }
    
    public function updateRole(Request $request)
    {
        // Validate the incoming request to ensure 'id' is present and is a valid integer
        $request->validate([
            'id' => 'required|integer|exists:roles,id', 
            'name' => 'required|string|max:255', // Validate 'name' to ensure it is not empty
            'permissions' => 'required|array', // Ensure 'permissions' is an array
        ]);

        // Find the role by ID
        $role = Role::findOrFail($request->id);

        // Update the role name
        $role->name = $request->name;

        // Sync the permissions with the role
        $role->permissions()->sync($request->permissions);

        // Save the updated role (optional, as `sync` does not require calling save)
        $role->save();

        // Return a success response
        return response()->json([
            'success' => true, 
            'message' => 'Role updated successfully'
        ]);
    }

    public function updateRolePermission(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'eq' => 'required', 
            'permissions' => 'required|array', // Ensure 'permissions' is an array
        ]);

        // Decrypt the 'eq' parameter
        $data = decrypturl($request->eq);

        // Extract role ID from the decrypted data
        $role_id = $data['id'];

        // Find the role by ID
        $role = Role::findOrFail($role_id);

        // Sync the permissions with the role
        $role->permissions()->sync($request->permissions);

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Permissions updated successfully.');
    }   

}
