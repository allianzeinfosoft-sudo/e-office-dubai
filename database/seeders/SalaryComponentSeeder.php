<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SalaryComponentSeeder extends Seeder
{
    public function run(): void
    {
        $components = [
            ['name' => 'Basic Salary', 'type' => 'earning', 'is_statutory' => true, 'is_variable' => false, 'status' => true],
            ['name' => 'HRA', 'type' => 'earning', 'is_statutory' => false, 'is_variable' => false, 'status' => true],
            ['name' => 'DA', 'type' => 'earning', 'is_statutory' => true, 'is_variable' => false, 'status' => true],
            ['name' => 'Special Allowance', 'type' => 'earning', 'is_statutory' => false, 'is_variable' => false, 'status' => true],
            ['name' => 'Conveyance Allowance', 'type' => 'earning', 'is_statutory' => false, 'is_variable' => false, 'status' => true],
            ['name' => 'Medical Allowance', 'type' => 'earning', 'is_statutory' => false, 'is_variable' => false, 'status' => true],
            ['name' => 'Provident Fund (PF)', 'type' => 'deduction', 'is_statutory' => true, 'is_variable' => false, 'status' => true],
            ['name' => 'ESI', 'type' => 'deduction', 'is_statutory' => true, 'is_variable' => false, 'status' => true],
            ['name' => 'Professional Tax', 'type' => 'deduction', 'is_statutory' => true, 'is_variable' => false, 'status' => true],
            ['name' => 'TDS', 'type' => 'deduction', 'is_statutory' => true, 'is_variable' => true, 'status' => true],
        ];

        foreach ($components as $component) {
            \App\Models\SalaryComponent::updateOrCreate(['name' => $component['name']], $component);
        }
    }
}
