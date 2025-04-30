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
            ['status_name' => 'New User'],
            ['status_name' => 'Active'],
            ['status_name' => 'Inactive'],
            ['status_name' => 'Resigned'],
            ['status_name' => 'Admin']
        ];

        foreach ($statuses as $status) {
            UserStatus::firstOrCreate(
                ['status_name' => $status['status_name']],
                ['created_at' => now(), 'updated_at' => now()]
            );
        }

    }
}
