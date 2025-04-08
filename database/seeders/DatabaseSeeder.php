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
        $this->call(PermissionCategorySeeder::class);
        $this->call(UserSeeder::class);
         // Create roles
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $userRole = Role::firstOrCreate(['name' => 'user']);

        // Create permissions
        $manageRoles = Permission::firstOrCreate(['name' => 'manage roles']);
        $managePermissions = Permission::firstOrCreate(['name' => 'manage permissions']);

        // Assign permissions to roles
        $adminRole->givePermissionTo([$manageRoles, $managePermissions]);

        // Create admin user
        User::updateOrCreate(
            ['email' => 'admin@mail.com'], // Search criteria
            [
                'username' => 'administrator',
                'role' => 'Developer',
                'password' => Hash::make('password') // Ensures password is always hashed
            ]
        );

        // Assign role to a user
        $admin = User::where('email', 'admin@mail.com')->first();
        if ($admin) {
            $admin->assignRole('Developer');
        }
    }
}
