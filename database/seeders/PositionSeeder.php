<?php

namespace Database\Seeders;

use App\Models\Position;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $positions = [
            'Admin',  // Super admin
            'CEO',
            'COO',
            'HR',
            'Director',
            'Project Manager',
            'Team Leader',
            'Employee',
            'Technical',         // User-level
        ];

        foreach ($positions as $position) {
            Position::firstOrCreate(['position_name' => $position]);
        }
    }
}
