<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Department;

class TestDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a default department for testing
        Department::updateOrCreate(
            ['id' => 1],
            [
                'name' => 'Test Department',
                'description' => 'Default department for testing',
            ]
        );
    }
}
