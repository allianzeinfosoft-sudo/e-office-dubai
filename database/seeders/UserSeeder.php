<?php

namespace Database\Seeders;

use App\Models\UserStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         // create users status
         $statuses = [
            ['status_name' => 'New User', 'created_at' => now(), 'updated_at' => now()],
            ['status_name' => 'Active', 'created_at' => now(), 'updated_at' => now()],
            ['status_name' => 'Inactive', 'created_at' => now(), 'updated_at' => now()],
            ['status_name' => 'Resigned', 'created_at' => now(), 'updated_at' => now()],
            ['status_name' => 'Admin', 'created_at' => now(), 'updated_at' => now()],
        ];
        UserStatus::insert($statuses);


    }
}
