<?php

namespace App\Http\Controllers;

use App\Models\Permission_category;
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
        $permission_categories = Permission_category::all();
        return view('permissions.index',compact('permission_categories'));
    }

    public function getPermissions()
    {
        $permissions = Permission::with('category','roles')->get()->map(function ($permission) {
            return [
                'id' => $permission->id,
                'name' => $permission->name,
                'category_id' => optional($permission->category)->id,
                'category_name' => optional($permission->category)->name,
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
            'permission_category_id' => 'required',
        ]);
        Permission::create($request->all());
        return redirect()->route('permissions.index')->with('success', 'Permission created successfully.');
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

    public function destroy($id)
    {
        $permission = Permission::find($id);
        if (!$permission) {
            return response()->json(['message' => 'Record not found'], 404);
        }

        $permission->delete();

        return response()->json(['message' => 'Record deleted successfully']);
        }
}
