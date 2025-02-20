<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
         // Create roles
        $adminRole = Role::create(['name' => 'admin']);
        $userRole = Role::create(['name' => 'user']);

        // Create permissions
        $manageRoles = Permission::create(['name' => 'manage roles']);
        $managePermissions = Permission::create(['name' => 'manage permissions']);

        // Assign permissions to roles
        $adminRole->givePermissionTo([$manageRoles, $managePermissions]);

        // Create admin user
        User::create([
            'name' => 'administrator',
            'email' => 'admin@mail.com',
            'role' => 'admin',
            'password' => Hash::make('password')
        ]);

        // Assign role to a user
        $admin = User::where('email', 'admin@mail.com')->first();
        if ($admin) {
            $admin->assignRole('admin');
        }
    }
}
