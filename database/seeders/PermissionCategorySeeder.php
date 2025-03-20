<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('permission_categories')->insert([
            ['name' => 'Default'],
            ['name' => 'Admin Report Info'],
            ['name' => 'Users'],
            ['name' => 'Leave Management'],
            ['name' => 'Project Details'],
            ['name' => 'Feedback Pages'],
            ['name' => 'SAR Pages'],
            ['name' => 'PAR Pages'],
            ['name' => 'Settings'],
            ['name' => 'RRF'],
            ['name' => 'Others'],
            ['name' => 'Tools'],
            ['name' => 'Policy']
        ]);
    }
}
