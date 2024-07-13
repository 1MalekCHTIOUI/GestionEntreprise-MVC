<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $permissions = Permission::all();

        return response()->json([
            'success' => true,
            'data' => $permissions
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:permissions,name'
        ]);

        $permission = Permission::create($request->only('name'));

        return response()->json([
            'success' => true,
            'message' => 'Permission created successfully.',
            'data' => $permission
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $permission = Permission::find($id);

        if (!$permission) {
            return response()->json([
                'success' => false,
                'message' => 'Permission not found.'
            ], 404);
        }

        $request->validate([
            'name' => 'required|unique:permissions,name,' . $permission->id
        ]);

        $permission->update($request->only('name'));

        return response()->json([
            'success' => true,
            'message' => 'Permission updated successfully.',
            'data' => $permission
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $permission = Permission::find($id);

        if (!$permission) {
            return response()->json([
                'success' => false,
                'message' => 'Permission not found.'
            ], 404);
        }

        $permission->delete();

        return response()->json([
            'success' => true,
            'message' => 'Permission deleted successfully.'
        ], 200);
    }
}
