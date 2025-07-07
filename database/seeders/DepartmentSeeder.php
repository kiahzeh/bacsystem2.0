<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Department;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = [
            [
                'name' => 'Admin',
                'description' => 'Administrative Department',
            ],
            [
                'name' => 'COT',
                'description' => 'College of Technology',
            ],
            [
                'name' => 'CAS',
                'description' => 'College of Arts and Sciences',
            ],
            [
                'name' => 'CTE',
                'description' => 'College of Teacher Education',
            ],
            [
                'name' => 'CBPA',
                'description' => 'College of Business and Public Administration',
            ],
            [
                'name' => 'CCJE',
                'description' => 'College of Criminal Justice Education',
            ],
        ];

        foreach ($departments as $department) {
            Department::create($department);
        }
    }

    
}
