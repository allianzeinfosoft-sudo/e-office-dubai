<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $allPermissions = DB::table('permissions')->pluck('name')->toArray();
        // Give all permissions to Developer (Super Admin)
        $developerRole = Role::where('name', 'Developer')->first();
        foreach ($allPermissions as $permName) {
            $permission = Permission::firstOrCreate([
                'name' => $permName,
                'guard_name' => 'web'
            ]);
            $developerRole->givePermissionTo($permission);
        }

        // Assign basic HR permissions (example: leave, attendance, recruitment)
        // $hrPermissions = DB::table('permissions')
        //     ->whereIn('permission_name', function($query) {
        //         $query->select('permission_name')
        //               ->from('permissions')
        //               ->whereIn('permission_name', [
        //                   'view leave requests', 'approve leave', 'reject leave',
        //                   'view attendance', 'approve attendance',
        //                   'view job applications', 'shortlist candidates', 'schedule interviews'
        //               ]);
        //     })
        //     ->pluck('permission_name');

        // $hrRole = Role::where('name', 'HR')->first();
        // foreach ($hrPermissions as $permName) {
        //     $permission = Permission::where('name', $permName)->first();
        //     if ($permission) {
        //         $hrRole->givePermissionTo($permission);
        //     }
        // }
    }
}
