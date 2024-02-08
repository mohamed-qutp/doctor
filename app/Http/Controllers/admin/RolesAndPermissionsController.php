<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Http\Requests\RoleStoreRequest;
use App\Http\Requests\RoleUpdateRequest;
use App\Traits\AuthorizeCheck;

class RolesAndPermissionsController extends Controller
{
    use AuthorizeCheck;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorizCheck('edit-settings');
        $roles = Role::all();
        return response()->json([
            'success'=>true,
            'roles' => $roles
        ],200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorizCheck('create-settings');
        $permissions = Permission::all();
        return response()->json([
            'success'=>true,
            'permissions' => $permissions
        ],200);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RoleStoreRequest $request)
    {
        $data = $request->validated();
        $role = Role::create([
            'name'=>$request->role,
            'guard_name'=>'api'
            ])->givePermissionTo($request->permissions);
            app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
            // return redirect()->route('admin.roles.index')->with('success', 'Role created successfully');
            return response()->json([
                'success'=>'Role created successfully',
                'data' => $role
            ],200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $this->authorizCheck('view-settings');
        $role = Role::findOrFail($id);
        $role->permissions;
        return response()->json([
          'success'=>true,
            'role' => $role
        ],200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $this->authorizCheck('edit-settings');
        $role = Role::findOrFail($id);
        $role->permissions;
        $permissions = Permission::all();
        return response()->json([
           'success'=>true,
            'role' => $role,
            'permissions' => $permissions
        ],200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RoleUpdateRequest $request, string $id)
    {
        $role = Role::findOrFail($id);
        $permissions = $role->permissions;
        $role->revokePermissionTo($permissions);
        $role->givePermissionTo($request->permissions);
        $role->update([
            'name' => $request->role
        ]);
        $role = $role->refresh();
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        return response()->json([
          'success'=>'Role updated successfully',
            'data' => $role
        ],200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->authorizCheck('delete-settings');
        $role = Role::findOrFail($id);
        $permissions = $role->permissions;
        $role->revokePermissionTo($permissions);
        $role->delete();
        return response()->json([
            'success'=>'Role deleted successfully',
            'data' => $role
        ],200);



    }
}
