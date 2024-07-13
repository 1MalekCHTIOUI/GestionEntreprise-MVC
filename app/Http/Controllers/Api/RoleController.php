<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{/**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles=Role::latest()->simplepaginate(10);
        return view('roles.index',compact('roles'));
     }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
            'permission' => 'required|array',
            'permission.*' => 'exists:permissions,id',
        ]);

        $role = Role::create(['name' => $request->get('name')]);
        $role->syncPermissions($request->get('permission'));

        return response()->json([
            'success' => true,
            'message' => 'Role created successfully.',
            'data' => $role
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $role = Role::with('permissions')->find($id);

        if (!$role) {
            return response()->json([
                'success' => false,
                'message' => 'Role not found.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $role
        ], 200);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $role = Role::find($id);

        if (!$role) {
            return response()->json([
                'success' => false,
                'message' => 'Role not found.'
            ], 404);
        }

        $request->validate([
            'name' => 'required|unique:roles,name,' . $role->id,
            'permission' => 'required|array',
            'permission.*' => 'exists:permissions,id',
        ]);

        $role->update(['name' => $request->get('name')]);
        $role->syncPermissions($request->get('permission'));

        return response()->json([
            'success' => true,
            'message' => 'Role updated successfully.',
            'data' => $role
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $role = Role::find($id);

        if (!$role) {
            return response()->json([
                'success' => false,
                'message' => 'Role not found.'
            ], 404);
        }

        $role->delete();

        return response()->json([
            'success' => true,
            'message' => 'Role deleted successfully.'
        ], 200);
    }
}
