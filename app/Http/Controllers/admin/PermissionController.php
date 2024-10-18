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
            $sections = Role::with('permissions')->latest();
            
            return DataTables::of($sections)
                ->addIndexColumn()
    
                // Action column: edit and delete buttons with encrypted URLs
                ->addColumn('action', function ($section) {
                    $parms = "id=" . $section->id;
                    $editUrl = encrypturl(route('edit-role'), $parms);  // Adjust route as per your actual route
    
                    return '
                        <button type="button" data-url="' . $editUrl . '" class="editItem cursor-pointer uil uil-edit-alt hover:text-info" data-te-toggle="modal" data-te-target="#editModal" data-te-ripple-init data-te-ripple-color="light"></button>';
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
            'permissions' => 'required'
        ]);

        $role = Role::findOrFail($request->id);
        $role->name = $request->name;
        
        // Update role permissions
        $role->permissions()->sync($request->permissions); // Assuming 'permissions' is an array of permission IDs

        $role->save();
        
        return response()->json(['success' => true, 'message' => 'Role updated successfully']);
    }


}
