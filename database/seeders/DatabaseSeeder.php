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
        $this->call(UserSeeder::class);
        $this->call(PermissionSeeder::class);
        $this->call(RolePermissionSeeder::class);

        // Create admin user
        User::updateOrCreate(
            ['email' => 'admin@mail.com'],
            [
                'username' => 'administrator',
                'role' => 'Developer',
                'password' => Hash::make('password')
            ]
        );

        // Assign role to a user
        $admin = User::where('email', 'admin@mail.com')->first();
        if ($admin) {
            $admin->assignRole('Developer');
        }
    }
}
