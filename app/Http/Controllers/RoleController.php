<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');

    }

    public function index()
    {
        $roles = Cache::remember('roles', now()->addMinutes(60), function () {
            return Role::with('permissions')->get();
        });
        return view('role.index', compact('roles'));
    }

    public function create()
    {
        $permissions = Cache::remember('permissions', now()->addMinutes(60), function () {
            return Permission::all();
        });
        return view('roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {

        $request->validate([

            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,name'
        ]);

        // Create or update the role
        $user_role = Role::updateOrCreate(
            ['name' => $request->name], // If ID exists, update; otherwise, create
            ['guard_name' => $request->guard_name]
        );

        // Assign permissions
        // if ($request->has('permissions')) {
            $user_role->syncPermissions($request->permissions ?? []);
        // }

        // Clear role cache
        Cache::forget('roles');
        return redirect()->route('roles.index')->with('success', 'Role saved successfully.');

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    public function edit(Role $role)
    {
        $permissions = Permission::all();
        return view('roles.edit', compact('role', 'permissions'));
    }

    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|unique:roles,name,' . $role->id,
            'permissions' => 'array|required',
        ]);
        $role->update(['name' => $request->name]);
        $role->syncPermissions($request->permissions);
        return redirect()->route('roles.index')->with('success', 'Role updated successfully.');




    }

    public function destroy($id)
    {

        $role = Role::find($id);

        if (!$role) {
            return response()->json(['success' => false, 'message' => 'Role not found'], 404);
        }

        $role->permissions()->detach(); // Remove associated permissions
        $role->delete(); // Delete role
        Cache::forget('roles');
        return response()->json(['success' => true, 'message' => 'Role deleted successfully.']);

    }

    public function getRolePermissions($id)
    {
        $role = Role::findOrFail($id);
        $permissions = Permission::with('category','roles')->get()->map(function ($permission) use ($role) {
            return [
                'id' => $permission->id,
                'name' => $permission->name,
                'category_id' => optional($permission->category)->id,
                'category_name' => optional($permission->category)->name,
                'assigned' => $role->hasPermissionTo($permission->name),
                'created_date' => $permission->created_at->format("d M Y, g:i A"),
            ];
        });

        return response()->json([
            'role' => $role,
            'permissions' => $permissions
        ]);
    }

    public function getroles()
    {
        return response()->json(Role::select('id', 'name')->get());
    }
}
