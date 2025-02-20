<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin'); // Restrict access to admin role
    }

    public function index()
    {
        return view('permissions.index');
    }

    public function getPermissions()
    { 
        $permissions = Permission::all()->map(function ($permission) {
            return [
                'id' => $permission->id,
                'name' => $permission->name,
                'assigned_to' => $permission->roles ? $permission->roles->pluck('name')->toArray() : [], // Ensure it's an array
                'created_date' => $permission->created_at->format("d M Y, g:i A"),
            ];
        });

        $response = response()->json(['data' => $permissions]);
        $json_data = json_decode($response->getContent(), true)['data'];
        return json_encode(['data' => $json_data]);
        
    }

    public function create()
    {
        // return view('permissions.create');
    }

    public function store(Request $request)
    { 
      
        $request->validate([
            'name' => 'required|string|unique:permissions,name|max:255',
        ]); 
        Permission::create($request->all());
        return view('permissions.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    public function edit(Permission $permission)
    {
        return view('permissions.edit', compact('permission'));
    }

    public function update(Request $request, Permission $permission)
    {
        $request->validate([
            'name' => 'required|unique:permissions,name,' . $permission->id,
        ]);

        $permission->update(['name' => $request->name]);

        return redirect()->route('permissions.index')->with('success', 'Permission updated successfully.');
    }

    public function destroy(Permission $permission)
    {
        $permission->delete();
        return redirect()->route('permissions.index')->with('success', 'Permission deleted successfully.');
    }
}
